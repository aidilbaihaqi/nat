<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\RuanganModel;

class RuanganController extends BaseController
{
    protected $ruanganModel;

    public function __construct()
    {
        $this->ruanganModel = new RuanganModel();
    }

    /**
     * List all ruangan
     */
    public function index()
    {
        $data['ruangan'] = $this->ruanganModel->findAll();
        return view('admin/ruangan/index', $data);
    }

    /**
     * Show form to create new ruangan
     */
    public function new()
    {
        return view('admin/ruangan/create');
    }

    /**
     * Create new ruangan
     */
    public function create()
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'nama_ruangan' => 'required|is_unique[ruangan.nama_ruangan]|max_length[50]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            'nama_ruangan' => $this->request->getPost('nama_ruangan')
        ];

        if ($this->ruanganModel->insert($data)) {
            return redirect()->to('/admin/ruangan')->with('success', 'Ruangan berhasil ditambahkan');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal menyimpan ruangan');
    }

    /**
     * Show form to edit ruangan
     */
    public function edit($id)
    {
        $data['ruangan'] = $this->ruanganModel->find($id);
        
        if (!$data['ruangan']) {
            return redirect()->to('/admin/ruangan')->with('error', 'Ruangan tidak ditemukan');
        }

        return view('admin/ruangan/edit', $data);
    }

    /**
     * Update ruangan data
     */
    public function update($id)
    {
        $ruangan = $this->ruanganModel->find($id);
        
        if (!$ruangan) {
            return redirect()->to('/admin/ruangan')->with('error', 'Ruangan tidak ditemukan');
        }

        $validation = \Config\Services::validation();
        
        $rules = [
            'nama_ruangan' => 'required|is_unique[ruangan.nama_ruangan,id_ruangan,' . $id . ']|max_length[50]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            'nama_ruangan' => $this->request->getPost('nama_ruangan')
        ];

        if ($this->ruanganModel->update($id, $data)) {
            return redirect()->to('/admin/ruangan')->with('success', 'Ruangan berhasil diupdate');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal mengupdate ruangan');
    }

    /**
     * Delete ruangan
     */
    public function delete($id)
    {
        $ruangan = $this->ruanganModel->find($id);
        
        if (!$ruangan) {
            return redirect()->to('/admin/ruangan')->with('error', 'Ruangan tidak ditemukan');
        }

        try {
            if ($this->ruanganModel->delete($id)) {
                return redirect()->to('/admin/ruangan')->with('success', 'Ruangan berhasil dihapus');
            }
        } catch (\Exception $e) {
            return redirect()->to('/admin/ruangan')->with('error', 'Gagal menghapus ruangan. Mungkin masih digunakan di jadwal.');
        }

        return redirect()->to('/admin/ruangan')->with('error', 'Gagal menghapus ruangan');
    }
}
