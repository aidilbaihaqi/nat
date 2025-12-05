<?php

namespace App\Controllers\Dosen;

use App\Controllers\BaseController;
use App\Models\DosenModel;
use App\Models\JadwalModel;
use App\Models\RencanaStudiModel;
use App\Models\NilaiMutuModel;

class NilaiController extends BaseController
{
    protected $dosenModel;
    protected $jadwalModel;
    protected $rencanaStudiModel;
    protected $nilaiMutuModel;

    public function __construct()
    {
        $this->dosenModel = new DosenModel();
        $this->jadwalModel = new JadwalModel();
        $this->rencanaStudiModel = new RencanaStudiModel();
        $this->nilaiMutuModel = new NilaiMutuModel();
    }

    /**
     * Display form for input nilai for a specific jadwal
     * 
     * @param int $id_jadwal
     * @return string|\CodeIgniter\HTTP\RedirectResponse
     */
    public function show($id_jadwal)
    {
        // Get user_id from session
        $userId = session()->get('user_id');
        
        // Get dosen data by user_id
        $dosen = $this->dosenModel->where('user_id', $userId)->first();
        
        if (!$dosen) {
            return redirect()->to('/login')->with('error', 'Data dosen tidak ditemukan');
        }
        
        $nidn = $dosen['nidn'];
        
        // Get jadwal with details
        $jadwal = $this->jadwalModel->getWithDetails($id_jadwal);
        
        if (!$jadwal) {
            return redirect()->to('/dosen/jadwal')->with('error', 'Jadwal tidak ditemukan');
        }
        
        // Validate ownership - ensure this jadwal is taught by logged in dosen
        if ($jadwal['nidn'] !== $nidn) {
            return redirect()->to('/dosen/jadwal')->with('error', 'Anda tidak memiliki akses untuk jadwal ini');
        }
        
        // Get mahasiswa who are taking this jadwal
        $mahasiswaList = $this->rencanaStudiModel->getByJadwal($id_jadwal);
        
        // Get all available nilai_huruf for dropdown
        $nilaiMutuList = $this->nilaiMutuModel->findAll();
        
        $data = [
            'title' => 'Input Nilai',
            'jadwal' => $jadwal,
            'mahasiswaList' => $mahasiswaList,
            'nilaiMutuList' => $nilaiMutuList
        ];
        
        return view('dosen/nilai_form', $data);
    }

    /**
     * Update nilai for mahasiswa in a specific jadwal
     * 
     * @param int $id_jadwal
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function update($id_jadwal)
    {
        // Get user_id from session
        $userId = session()->get('user_id');
        
        // Get dosen data by user_id
        $dosen = $this->dosenModel->where('user_id', $userId)->first();
        
        if (!$dosen) {
            return redirect()->to('/login')->with('error', 'Data dosen tidak ditemukan');
        }
        
        $nidn = $dosen['nidn'];
        
        // Get jadwal
        $jadwal = $this->jadwalModel->find($id_jadwal);
        
        if (!$jadwal) {
            return redirect()->to('/dosen/jadwal')->with('error', 'Jadwal tidak ditemukan');
        }
        
        // Validate ownership - ensure this jadwal is taught by logged in dosen
        if ($jadwal['nidn'] !== $nidn) {
            return redirect()->to('/dosen/jadwal')->with('error', 'Anda tidak memiliki akses untuk jadwal ini');
        }
        
        // Get nilai data from POST
        $nilaiData = $this->request->getPost('nilai'); // Expected format: array of ['id_rencana_studi' => x, 'nilai_angka' => y, 'nilai_huruf' => 'A']
        
        if (!$nilaiData || !is_array($nilaiData)) {
            return redirect()->back()->with('error', 'Data nilai tidak valid');
        }
        
        $db = \Config\Database::connect();
        $db->transStart();
        
        $errors = [];
        $successCount = 0;
        
        foreach ($nilaiData as $nilai) {
            // Check if id_rencana_studi exists
            if (!isset($nilai['id_rencana_studi'])) {
                continue;
            }
            
            $idRencanaStudi = $nilai['id_rencana_studi'];
            
            // Skip if both nilai_angka and nilai_huruf are empty
            if (empty($nilai['nilai_angka']) && empty($nilai['nilai_huruf'])) {
                continue;
            }
            
            $nilaiAngka = !empty($nilai['nilai_angka']) ? $nilai['nilai_angka'] : null;
            $nilaiHuruf = !empty($nilai['nilai_huruf']) ? $nilai['nilai_huruf'] : null;
            
            // Validate nilai_huruf exists in nilai_mutu table
            if ($nilaiHuruf) {
                $nilaiMutu = $this->nilaiMutuModel->find($nilaiHuruf);
                if (!$nilaiMutu) {
                    $errors[] = "Nilai huruf '{$nilaiHuruf}' tidak valid untuk ID rencana studi {$idRencanaStudi}";
                    continue;
                }
            }
            
            // Update rencana_studi
            $updated = $this->rencanaStudiModel->update($idRencanaStudi, [
                'nilai_angka' => $nilaiAngka,
                'nilai_huruf' => $nilaiHuruf
            ]);
            
            if ($updated) {
                $successCount++;
            } else {
                $errors[] = "Gagal mengupdate nilai untuk ID rencana studi {$idRencanaStudi}";
            }
        }
        
        $db->transComplete();
        
        if ($db->transStatus() === false || !empty($errors)) {
            $errorMessage = 'Terjadi kesalahan saat menyimpan nilai';
            if (!empty($errors)) {
                $errorMessage .= ': ' . implode(', ', $errors);
            }
            return redirect()->back()->with('error', $errorMessage);
        }
        
        return redirect()->to('/dosen/jadwal')->with('success', "Berhasil menyimpan nilai untuk {$successCount} mahasiswa");
    }
}
