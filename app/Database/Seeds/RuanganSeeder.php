<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RuanganSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['nama_ruangan' => 'Lab Komputer 1'],
            ['nama_ruangan' => 'Lab Komputer 2'],
            ['nama_ruangan' => 'Ruang Kelas A301'],
        ];

        $this->db->table('ruangan')->insertBatch($data);
    }
}
