<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MataKuliahModel;

class MataKuliahController extends BaseController
{
    protected $mataKuliahModel;

    public function __construct()
    {
        $this->mataKuliahModel = new MataKuliahModel();
    }

    /**
     * List all mata kuliah
     */
    public function index()
    {
        $data['mataKuliah'] = $this->mataKuliahModel->findAll();
        return view('admin/mata_kuliah/index', $data);
    }

    /**
     * Show form to create new mata kuliah
     */
    public function new()
    {
        return view('admin/mata_kuliah/create');
    }

    /**
     * Create new mata kuliah
     */
    public function create()
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'kode_mata_kuliah' => 'required|is_unique[mata_kuliah.kode_mata_kuliah]|max_length[20]',
            'nama_mata_kuliah' => 'required|max_length[100]',
            'sks' => 'required|numeric|greater_than[0]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            'kode_mata_kuliah' => $this->request->getPost('kode_mata_kuliah'),
            'nama_mata_kuliah' => $this->request->getPost('nama_mata_kuliah'),
            'sks' => $this->request->getPost('sks')
        ];

        if ($this->mataKuliahModel->insert($data)) {
            return redirect()->to('/admin/mata-kuliah')->with('success', 'Mata kuliah berhasil ditambahkan');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal menyimpan mata kuliah');
    }

    /**
     * Show form to edit mata kuliah
     */
    public function edit($id)
    {
        $data['mataKuliah'] = $this->mataKuliahModel->find($id);
        
        if (!$data['mataKuliah']) {
            return redirect()->to('/admin/mata-kuliah')->with('error', 'Mata kuliah tidak ditemukan');
        }

        return view('admin/mata_kuliah/edit', $data);
    }

    /**
     * Update mata kuliah data
     */
    public function update($id)
    {
        $mataKuliah = $this->mataKuliahModel->find($id);
        
        if (!$mataKuliah) {
            return redirect()->to('/admin/mata-kuliah')->with('error', 'Mata kuliah tidak ditemukan');
        }

        $validation = \Config\Services::validation();
        
        $rules = [
            'kode_mata_kuliah' => 'required|is_unique[mata_kuliah.kode_mata_kuliah,id_mata_kuliah,' . $id . ']|max_length[20]',
            'nama_mata_kuliah' => 'required|max_length[100]',
            'sks' => 'required|numeric|greater_than[0]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            'kode_mata_kuliah' => $this->request->getPost('kode_mata_kuliah'),
            'nama_mata_kuliah' => $this->request->getPost('nama_mata_kuliah'),
            'sks' => $this->request->getPost('sks')
        ];

        if ($this->mataKuliahModel->update($id, $data)) {
            return redirect()->to('/admin/mata-kuliah')->with('success', 'Mata kuliah berhasil diupdate');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal mengupdate mata kuliah');
    }

    /**
     * Delete mata kuliah
     */
    public function delete($id)
    {
        $mataKuliah = $this->mataKuliahModel->find($id);
        
        if (!$mataKuliah) {
            return redirect()->to('/admin/mata-kuliah')->with('error', 'Mata kuliah tidak ditemukan');
        }

        try {
            if ($this->mataKuliahModel->delete($id)) {
                return redirect()->to('/admin/mata-kuliah')->with('success', 'Mata kuliah berhasil dihapus');
            }
        } catch (\Exception $e) {
            return redirect()->to('/admin/mata-kuliah')->with('error', 'Gagal menghapus mata kuliah. Mungkin masih digunakan di jadwal.');
        }

        return redirect()->to('/admin/mata-kuliah')->with('error', 'Gagal menghapus mata kuliah');
    }
}
