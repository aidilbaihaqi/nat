<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MahasiswaSeeder extends Seeder
{
    public function run()
    {
        // AMBIL USER MAHASISWA
        $mhsUsers = $this->db->table('user')->where('role', 'mahasiswa')->get()->getResultArray();

        $mhsData = [];

        foreach ($mhsUsers as $u) {
            $mhsData[] = [
                'nim'       => rand(220000000, 229999999),
                'nama'      => $u['nama_user'],
                'user_id'   => $u['id_user'],
                'angkatan'  => 2022,
            ];
        }

        $this->db->table('mahasiswa')->insertBatch($mhsData);
    }
}
