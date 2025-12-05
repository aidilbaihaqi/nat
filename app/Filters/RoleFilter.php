<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Terima parameter role yang diizinkan
        $allowedRoles = $arguments;
        
        // Ambil role dari session
        $userRole = session()->get('role');
        
        // Cek session role sesuai dengan parameter
        if (!$userRole || !in_array($userRole, $allowedRoles)) {
            // Tampilkan error 403 atau redirect jika tidak sesuai
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
