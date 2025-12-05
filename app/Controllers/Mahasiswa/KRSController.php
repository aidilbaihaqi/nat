<?php

namespace App\Controllers\Mahasiswa;

use App\Controllers\BaseController;
use App\Models\MahasiswaModel;
use App\Models\JadwalModel;
use App\Models\RencanaStudiModel;

class KRSController extends BaseController
{
    protected $mahasiswaModel;
    protected $jadwalModel;
    protected $rencanaStudiModel;

    public function __construct()
    {
        $this->mahasiswaModel = new MahasiswaModel();
        $this->jadwalModel = new JadwalModel();
        $this->rencanaStudiModel = new RencanaStudiModel();
    }

    /**
     * Display KRS page with available jadwal and current KRS
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
        
        // Get all available jadwal with details
        $jadwalAvailable = $this->jadwalModel->getAllWithDetails();
        
        // Get current KRS for this mahasiswa
        $krs = $this->rencanaStudiModel->getByMahasiswa($nim);
        
        // Get array of id_jadwal that already in KRS for filtering
        $krsJadwalIds = array_column($krs, 'id_jadwal');
        
        $data = [
            'title' => 'Kartu Rencana Studi (KRS)',
            'jadwalAvailable' => $jadwalAvailable,
            'krs' => $krs,
            'krsJadwalIds' => $krsJadwalIds,
            'nim' => $nim
        ];
        
        return view('mahasiswa/krs', $data);
    }

    /**
     * Store new jadwal to KRS
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function store()
    {
        // Get user_id from session
        $userId = session()->get('user_id');
        
        // Get mahasiswa data by user_id
        $mahasiswa = $this->mahasiswaModel->where('user_id', $userId)->first();
        
        if (!$mahasiswa) {
            return redirect()->to('/login')->with('error', 'Data mahasiswa tidak ditemukan');
        }
        
        $nim = $mahasiswa['nim'];
        $idJadwal = $this->request->getPost('id_jadwal');
        
        // Validation rules
        $rules = [
            'id_jadwal' => 'required|integer'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Check if already exists (unique constraint: nim, id_jadwal)
        $existing = $this->rencanaStudiModel
                         ->where('nim', $nim)
                         ->where('id_jadwal', $idJadwal)
                         ->first();
        
        if ($existing) {
            return redirect()->back()->with('error', 'Jadwal sudah ada dalam KRS Anda');
        }
        
        // Insert to rencana_studi
        $data = [
            'nim' => $nim,
            'id_jadwal' => $idJadwal,
            'nilai_angka' => null,
            'nilai_huruf' => null
        ];
        
        if ($this->rencanaStudiModel->insert($data)) {
            return redirect()->to('/mhs/krs')->with('success', 'Jadwal berhasil ditambahkan ke KRS');
        } else {
            return redirect()->back()->with('error', 'Gagal menambahkan jadwal ke KRS');
        }
    }

    /**
     * Delete jadwal from KRS
     * 
     * @param int $id_rencana_studi
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function delete($id_rencana_studi)
    {
        // Get user_id from session
        $userId = session()->get('user_id');
        
        // Get mahasiswa data by user_id
        $mahasiswa = $this->mahasiswaModel->where('user_id', $userId)->first();
        
        if (!$mahasiswa) {
            return redirect()->to('/login')->with('error', 'Data mahasiswa tidak ditemukan');
        }
        
        $nim = $mahasiswa['nim'];
        
        // Get rencana_studi record
        $rencanaStudi = $this->rencanaStudiModel->find($id_rencana_studi);
        
        if (!$rencanaStudi) {
            return redirect()->back()->with('error', 'Data KRS tidak ditemukan');
        }
        
        // Validate ownership - ensure this KRS belongs to logged in mahasiswa
        if ($rencanaStudi['nim'] !== $nim) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menghapus KRS ini');
        }
        
        // Delete the record
        if ($this->rencanaStudiModel->delete($id_rencana_studi)) {
            return redirect()->to('/mhs/krs')->with('success', 'Jadwal berhasil dihapus dari KRS');
        } else {
            return redirect()->back()->with('error', 'Gagal menghapus jadwal dari KRS');
        }
    }
}
