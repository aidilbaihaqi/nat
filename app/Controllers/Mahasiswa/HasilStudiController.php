<?php

namespace App\Controllers\Mahasiswa;

use App\Controllers\BaseController;
use App\Models\MahasiswaModel;
use App\Models\RencanaStudiModel;

class HasilStudiController extends BaseController
{
    protected $mahasiswaModel;
    protected $rencanaStudiModel;

    public function __construct()
    {
        $this->mahasiswaModel = new MahasiswaModel();
        $this->rencanaStudiModel = new RencanaStudiModel();
    }

    /**
     * Display hasil studi with IPK calculation
     * 
     * @return string
     */
    public function index()
    {
        // Get user_id from session
        $userId = session()->get('user_id');
        
        // Get mahasiswa data by user_id
        $mahasiswa = $this->mahasiswaModel->where('user_id', $userId)->first();
        
        if (!$mahasiswa) {
            return redirect()->to('/login')->with('error', 'Data mahasiswa tidak ditemukan');
        }
        
        $nim = $mahasiswa['nim'];
        
        // Query rencana_studi with join to get all needed data
        $db = \Config\Database::connect();
        $builder = $db->table('rencana_studi rs');
        
        $hasilStudi = $builder
            ->select('mk.nama_mata_kuliah, mk.sks, rs.nilai_huruf, rs.nilai_angka, nm.nilai_mutu, (nm.nilai_mutu * mk.sks) as nilai_mutu_sks')
            ->join('jadwal j', 'rs.id_jadwal = j.id')
            ->join('mata_kuliah mk', 'j.id_mata_kuliah = mk.id_mata_kuliah')
            ->join('nilai_mutu nm', 'rs.nilai_huruf = nm.nilai_huruf', 'left')
            ->where('rs.nim', $nim)
            ->where('rs.nilai_huruf IS NOT NULL')
            ->get()
            ->getResultArray();
        
        // Calculate IPK
        $totalNilaiMutu = 0;
        $totalSks = 0;
        $ipk = 0;
        
        foreach ($hasilStudi as $row) {
            if ($row['nilai_mutu'] !== null) {
                $totalNilaiMutu += $row['nilai_mutu_sks'];
                $totalSks += $row['sks'];
            }
        }
        
        // Calculate IPK = total_nilai_mutu / total_sks
        if ($totalSks > 0) {
            $ipk = $totalNilaiMutu / $totalSks;
        }
        
        $data = [
            'title' => 'Hasil Studi',
            'hasilStudi' => $hasilStudi,
            'totalSks' => $totalSks,
            'totalNilaiMutu' => $totalNilaiMutu,
            'ipk' => $ipk,
            'mahasiswa' => $mahasiswa
        ];
        
        return view('mahasiswa/hasil_studi', $data);
    }
}
