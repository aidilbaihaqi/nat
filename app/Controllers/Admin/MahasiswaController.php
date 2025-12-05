<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MahasiswaModel;
use App\Models\UserModel;

class MahasiswaController extends BaseController
{
    protected $mahasiswaModel;
    protected $userModel;

    public function __construct()
    {
        $this->mahasiswaModel = new MahasiswaModel();
        $this->userModel = new UserModel();
    }

    /**
     * List all mahasiswa with user data
     */
    public function index()
    {
        $data['mahasiswa'] = $this->mahasiswaModel->getAllWithUser();
        return view('admin/mahasiswa/index', $data);
    }

    /**
     * Show form to create new mahasiswa
     */
    public function new()
    {
        return view('admin/mahasiswa/create');
    }

    /**
     * Create new mahasiswa with user account
     */
    public function create()
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'nim' => 'required|is_unique[mahasiswa.nim]',
            'nama' => 'required|max_length[100]',
            'username' => 'required|is_unique[user.username]',
            'password' => 'required|min_length[6]',
            'angkatan' => 'permit_empty|numeric'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Create user account
            $userId = $this->userModel->createWithRole([
                'nama_user' => $this->request->getPost('nama'),
                'username' => $this->request->getPost('username'),
                'password' => $this->request->getPost('password'),
                'kode_peran' => $this->request->getPost('nim')
            ], 'mahasiswa');

            if (!$userId) {
                throw new \Exception('Failed to create user account');
            }

            // Create mahasiswa record
            $mahasiswaData = [
                'nim' => $this->request->getPost('nim'),
                'nama' => $this->request->getPost('nama'),
                'user_id' => $userId,
                'angkatan' => $this->request->getPost('angkatan')
            ];

            $this->mahasiswaModel->insert($mahasiswaData);

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data mahasiswa');
            }

            return redirect()->to('/admin/mahasiswa')->with('success', 'Mahasiswa berhasil ditambahkan');
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show form to edit mahasiswa
     */
    public function edit($nim)
    {
        $data['mahasiswa'] = $this->mahasiswaModel->getWithUser($nim);
        
        if (!$data['mahasiswa']) {
            return redirect()->to('/admin/mahasiswa')->with('error', 'Mahasiswa tidak ditemukan');
        }

        return view('admin/mahasiswa/edit', $data);
    }

    /**
     * Update mahasiswa data
     */
    public function update($nim)
    {
        $mahasiswa = $this->mahasiswaModel->getWithUser($nim);
        
        if (!$mahasiswa) {
            return redirect()->to('/admin/mahasiswa')->with('error', 'Mahasiswa tidak ditemukan');
        }

        $validation = \Config\Services::validation();
        
        $rules = [
            'nama' => 'required|max_length[100]',
            'username' => 'required|is_unique[user.username,id_user,' . $mahasiswa['user_id'] . ']',
            'angkatan' => 'permit_empty|numeric'
        ];

        // Only validate password if provided
        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[6]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Update user data
            $userData = [
                'nama_user' => $this->request->getPost('nama'),
                'username' => $this->request->getPost('username')
            ];

            if ($this->request->getPost('password')) {
                $userData['password_hash'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
            }

            $this->userModel->update($mahasiswa['user_id'], $userData);

            // Update mahasiswa data
            $mahasiswaData = [
                'nama' => $this->request->getPost('nama'),
                'angkatan' => $this->request->getPost('angkatan')
            ];

            $this->mahasiswaModel->update($nim, $mahasiswaData);

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->withInput()->with('error', 'Gagal mengupdate data mahasiswa');
            }

            return redirect()->to('/admin/mahasiswa')->with('success', 'Mahasiswa berhasil diupdate');
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Delete mahasiswa
     */
    public function delete($nim)
    {
        $mahasiswa = $this->mahasiswaModel->getWithUser($nim);
        
        if (!$mahasiswa) {
            return redirect()->to('/admin/mahasiswa')->with('error', 'Mahasiswa tidak ditemukan');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Delete mahasiswa (will cascade to user due to foreign key)
            $this->mahasiswaModel->delete($nim);
            
            // Delete user account
            $this->userModel->delete($mahasiswa['user_id']);

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->to('/admin/mahasiswa')->with('error', 'Gagal menghapus mahasiswa');
            }

            return redirect()->to('/admin/mahasiswa')->with('success', 'Mahasiswa berhasil dihapus');
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->to('/admin/mahasiswa')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
