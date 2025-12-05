<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class NoAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Jika user sudah login, redirect ke dashboard sesuai role
        if (session()->has('user_id')) {
            $role = session()->get('role');
            
            // Redirect berdasarkan role
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

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
