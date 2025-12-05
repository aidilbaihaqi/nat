<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DosenModel;
use App\Models\UserModel;

class DosenController extends BaseController
{
    protected $dosenModel;
    protected $userModel;

    public function __construct()
    {
        $this->dosenModel = new DosenModel();
        $this->userModel = new UserModel();
    }

    /**
     * List all dosen with user data
     */
    public function index()
    {
        $data['dosen'] = $this->dosenModel->getAllWithUser();
        return view('admin/dosen/index', $data);
    }

    /**
     * Show form to create new dosen
     */
    public function new()
    {
        return view('admin/dosen/create');
    }

    /**
     * Create new dosen with user account
     */
    public function create()
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'nidn' => 'required|is_unique[dosen.nidn]',
            'nama' => 'required|max_length[100]',
            'username' => 'required|is_unique[user.username]',
            'password' => 'required|min_length[6]'
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
                'kode_peran' => $this->request->getPost('nidn')
            ], 'dosen');

            if (!$userId) {
                throw new \Exception('Failed to create user account');
            }

            // Create dosen record
            $dosenData = [
                'nidn' => $this->request->getPost('nidn'),
                'nama' => $this->request->getPost('nama'),
                'user_id' => $userId
            ];

            $this->dosenModel->insert($dosenData);

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data dosen');
            }

            return redirect()->to('/admin/dosen')->with('success', 'Dosen berhasil ditambahkan');
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show form to edit dosen
     */
    public function edit($nidn)
    {
        $data['dosen'] = $this->dosenModel->getWithUser($nidn);
        
        if (!$data['dosen']) {
            return redirect()->to('/admin/dosen')->with('error', 'Dosen tidak ditemukan');
        }

        return view('admin/dosen/edit', $data);
    }

    /**
     * Update dosen data
     */
    public function update($nidn)
    {
        $dosen = $this->dosenModel->getWithUser($nidn);
        
        if (!$dosen) {
            return redirect()->to('/admin/dosen')->with('error', 'Dosen tidak ditemukan');
        }

        $validation = \Config\Services::validation();
        
        $rules = [
            'nama' => 'required|max_length[100]',
            'username' => 'required|is_unique[user.username,id_user,' . $dosen['user_id'] . ']'
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

            $this->userModel->update($dosen['user_id'], $userData);

            // Update dosen data
            $dosenData = [
                'nama' => $this->request->getPost('nama')
            ];

            $this->dosenModel->update($nidn, $dosenData);

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->withInput()->with('error', 'Gagal mengupdate data dosen');
            }

            return redirect()->to('/admin/dosen')->with('success', 'Dosen berhasil diupdate');
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Delete dosen
     */
    public function delete($nidn)
    {
        $dosen = $this->dosenModel->getWithUser($nidn);
        
        if (!$dosen) {
            return redirect()->to('/admin/dosen')->with('error', 'Dosen tidak ditemukan');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Delete dosen (will cascade to user due to foreign key)
            $this->dosenModel->delete($nidn);
            
            // Delete user account
            $this->userModel->delete($dosen['user_id']);

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->to('/admin/dosen')->with('error', 'Gagal menghapus dosen');
            }

            return redirect()->to('/admin/dosen')->with('success', 'Dosen berhasil dihapus');
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->to('/admin/dosen')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
