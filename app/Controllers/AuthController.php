<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\MahasiswaModel;
use App\Models\DosenModel;

class AuthController extends BaseController
{
    /**
     * Menampilkan form login
     * 
     * @return string
     */
    public function showLogin()
    {
        // Jika sudah login, redirect ke dashboard sesuai role
        if (session()->has('user_id')) {
            return $this->redirectToDashboard(session()->get('role'));
        }

        return view('auth/login');
    }

    /**
     * Proses autentikasi dengan validasi kredensial, verify password, set session
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function login()
    {
        $validation = \Config\Services::validation();
        
        // Validasi input
        $validation->setRules([
            'username' => 'required',
            'password' => 'required'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Cari user berdasarkan username
        $userModel = new UserModel();
        $user = $userModel->findByUsername($username);

        // Validasi kredensial
        if (!$user || !password_verify($password, $user['password_hash'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Username atau password salah');
        }

        // Set session dengan user_id, role, dan nama_user
        $sessionData = [
            'user_id' => $user['id_user'],
            'role' => $user['role'],
            'nama_user' => $user['nama_user']
        ];

        // Tambahkan nim atau nidn ke session jika role mahasiswa atau dosen
        if ($user['role'] === 'mahasiswa') {
            $mahasiswaModel = new MahasiswaModel();
            $mahasiswa = $mahasiswaModel->where('user_id', $user['id_user'])->first();
            if ($mahasiswa) {
                $sessionData['nim'] = $mahasiswa['nim'];
            }
        } elseif ($user['role'] === 'dosen') {
            $dosenModel = new DosenModel();
            $dosen = $dosenModel->where('user_id', $user['id_user'])->first();
            if ($dosen) {
                $sessionData['nidn'] = $dosen['nidn'];
            }
        }

        session()->set($sessionData);

        // Redirect berdasarkan role
        return $this->redirectToDashboard($user['role']);
    }

    /**
     * Hapus session dan redirect ke login
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Anda telah logout');
    }

    /**
     * Helper untuk redirect ke dashboard sesuai role
     * 
     * @param string $role
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    private function redirectToDashboard($role)
    {
        switch ($role) {
            case 'admin':
                return redirect()->to('/admin/mahasiswa');
            case 'mahasiswa':
                return redirect()->to('/mhs/krs');
            case 'dosen':
                return redirect()->to('/dosen/jadwal');
            default:
                return redirect()->to('/');
        }
    }
}
