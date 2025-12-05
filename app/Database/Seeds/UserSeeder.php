<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Admin user
            [
                'nama_user' => 'Administrator',
                'username' => 'admin',
                'password_hash' => password_hash('admin123', PASSWORD_DEFAULT),
                'role' => 'admin',
                'kode_peran' => null,
            ],
            // Dosen users
            [
                'nama_user' => 'Prof. Yanto',
                'username' => 'yanto.kopling',
                'password_hash' => password_hash('dosen123', PASSWORD_DEFAULT),
                'role' => 'dosen',
                'kode_peran' => null,
            ],
            [
                'nama_user' => 'Prof. Maria',
                'username' => 'maria.spakbor',
                'password_hash' => password_hash('dosen123', PASSWORD_DEFAULT),
                'role' => 'dosen',
                'kode_peran' => null,
            ],
            [
                'nama_user' => 'Prof. Bayu',
                'username' => 'bayu.coconut',
                'password_hash' => password_hash('dosen123', PASSWORD_DEFAULT),
                'role' => 'dosen',
                'kode_peran' => null,
            ],
            // Mahasiswa users
            [
                'nama_user' => 'Mamat Suhendi',
                'username' => 'mamat.hebat',
                'password_hash' => password_hash('mhs123', PASSWORD_DEFAULT),
                'role' => 'mahasiswa',
                'kode_peran' => null,
            ],
            [
                'nama_user' => 'Nanang Ismail',
                'username' => 'nangis.123',
                'password_hash' => password_hash('mhs123', PASSWORD_DEFAULT),
                'role' => 'mahasiswa',
                'kode_peran' => null,
            ],
            [
                'nama_user' => 'Nurul Sabella',
                'username' => 'nurul.aja',
                'password_hash' => password_hash('mhs123', PASSWORD_DEFAULT),
                'role' => 'mahasiswa',
                'kode_peran' => null,
            ],
        ];

        $this->db->table('user')->insertBatch($data);
    }
}
