<?php

namespace App\Controllers\Dosen;

use App\Controllers\BaseController;
use App\Models\DosenModel;
use App\Models\JadwalModel;

class JadwalController extends BaseController
{
    protected $dosenModel;
    protected $jadwalModel;

    public function __construct()
    {
        $this->dosenModel = new DosenModel();
        $this->jadwalModel = new JadwalModel();
    }

    /**
     * Display jadwal that are taught by the logged-in dosen
     * 
     * @return string
     */
    public function index()
    {
        // Get user_id from session
        $userId = session()->get('user_id');
        
        // Get dosen data by user_id
        $dosen = $this->dosenModel->where('user_id', $userId)->first();
        
        if (!$dosen) {
            return redirect()->to('/login')->with('error', 'Data dosen tidak ditemukan');
        }
        
        $nidn = $dosen['nidn'];
        
        // Get jadwal for this dosen with mata_kuliah and ruangan details
        $jadwal = $this->jadwalModel->getByDosen($nidn);
        
        $data = [
            'title' => 'Jadwal Mengajar',
            'jadwal' => $jadwal,
            'dosen' => $dosen
        ];
        
        return view('dosen/jadwal', $data);
    }
}
