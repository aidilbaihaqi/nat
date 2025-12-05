<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\JadwalModel;
use App\Models\MataKuliahModel;
use App\Models\RuanganModel;
use App\Models\DosenModel;

class JadwalController extends BaseController
{
    protected $jadwalModel;
    protected $mataKuliahModel;
    protected $ruanganModel;
    protected $dosenModel;

    public function __construct()
    {
        $this->jadwalModel = new JadwalModel();
        $this->mataKuliahModel = new MataKuliahModel();
        $this->ruanganModel = new RuanganModel();
        $this->dosenModel = new DosenModel();
    }

    /**
     * List all jadwal with details
     */
    public function index()
    {
        $data['jadwal'] = $this->jadwalModel->getAllWithDetails();
        return view('admin/jadwal/index', $data);
    }

    /**
     * Show form to create new jadwal
     */
    public function new()
    {
        $data['mataKuliah'] = $this->mataKuliahModel->findAll();
        $data['ruangan'] = $this->ruanganModel->findAll();
        $data['dosen'] = $this->dosenModel->findAll();
        
        return view('admin/jadwal/create', $data);
    }

    /**
     * Create new jadwal
     */
    public function create()
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'nama_kelas' => 'required|max_length[50]',
            'id_mata_kuliah' => 'required|is_not_unique[mata_kuliah.id_mata_kuliah]',
            'id_ruangan' => 'required|is_not_unique[ruangan.id_ruangan]',
            'nidn' => 'required|is_not_unique[dosen.nidn]',
            'hari' => 'required|in_list[Senin,Selasa,Rabu,Kamis,Jumat,Sabtu]',
            'jam' => 'required',
            'semester' => 'permit_empty|numeric'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            'nama_kelas' => $this->request->getPost('nama_kelas'),
            'id_mata_kuliah' => $this->request->getPost('id_mata_kuliah'),
            'id_ruangan' => $this->request->getPost('id_ruangan'),
            'nidn' => $this->request->getPost('nidn'),
            'hari' => $this->request->getPost('hari'),
            'jam' => $this->request->getPost('jam'),
            'semester' => $this->request->getPost('semester')
        ];

        if ($this->jadwalModel->insert($data)) {
            return redirect()->to('/admin/jadwal')->with('success', 'Jadwal berhasil ditambahkan');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal menyimpan jadwal');
    }

    /**
     * Show form to edit jadwal
     */
    public function edit($id)
    {
        $data['jadwal'] = $this->jadwalModel->find($id);
        
        if (!$data['jadwal']) {
            return redirect()->to('/admin/jadwal')->with('error', 'Jadwal tidak ditemukan');
        }

        $data['mataKuliah'] = $this->mataKuliahModel->findAll();
        $data['ruangan'] = $this->ruanganModel->findAll();
        $data['dosen'] = $this->dosenModel->findAll();

        return view('admin/jadwal/edit', $data);
    }

    /**
     * Update jadwal data
     */
    public function update($id)
    {
        $jadwal = $this->jadwalModel->find($id);
        
        if (!$jadwal) {
            return redirect()->to('/admin/jadwal')->with('error', 'Jadwal tidak ditemukan');
        }

        $validation = \Config\Services::validation();
        
        $rules = [
            'nama_kelas' => 'required|max_length[50]',
            'id_mata_kuliah' => 'required|is_not_unique[mata_kuliah.id_mata_kuliah]',
            'id_ruangan' => 'required|is_not_unique[ruangan.id_ruangan]',
            'nidn' => 'required|is_not_unique[dosen.nidn]',
            'hari' => 'required|in_list[Senin,Selasa,Rabu,Kamis,Jumat,Sabtu]',
            'jam' => 'required',
            'semester' => 'permit_empty|numeric'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            'nama_kelas' => $this->request->getPost('nama_kelas'),
            'id_mata_kuliah' => $this->request->getPost('id_mata_kuliah'),
            'id_ruangan' => $this->request->getPost('id_ruangan'),
            'nidn' => $this->request->getPost('nidn'),
            'hari' => $this->request->getPost('hari'),
            'jam' => $this->request->getPost('jam'),
            'semester' => $this->request->getPost('semester')
        ];

        if ($this->jadwalModel->update($id, $data)) {
            return redirect()->to('/admin/jadwal')->with('success', 'Jadwal berhasil diupdate');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal mengupdate jadwal');
    }

    /**
     * Delete jadwal
     */
    public function delete($id)
    {
        $jadwal = $this->jadwalModel->find($id);
        
        if (!$jadwal) {
            return redirect()->to('/admin/jadwal')->with('error', 'Jadwal tidak ditemukan');
        }

        try {
            if ($this->jadwalModel->delete($id)) {
                return redirect()->to('/admin/jadwal')->with('success', 'Jadwal berhasil dihapus');
            }
        } catch (\Exception $e) {
            return redirect()->to('/admin/jadwal')->with('error', 'Gagal menghapus jadwal. Mungkin masih ada mahasiswa yang mengambil jadwal ini.');
        }

        return redirect()->to('/admin/jadwal')->with('error', 'Gagal menghapus jadwal');
    }
}
