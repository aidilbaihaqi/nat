<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MataKuliahSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'kode_mata_kuliah' => 'IF101',
                'nama_mata_kuliah' => 'Pemrograman Dasar',
                'sks' => 3,
            ],
            [
                'kode_mata_kuliah' => 'IF102',
                'nama_mata_kuliah' => 'Struktur Data',
                'sks' => 3,
            ],
            [
                'kode_mata_kuliah' => 'IF201',
                'nama_mata_kuliah' => 'Basis Data',
                'sks' => 3,
            ],
            [
                'kode_mata_kuliah' => 'IF202',
                'nama_mata_kuliah' => 'Pemrograman Web',
                'sks' => 4,
            ],
            [
                'kode_mata_kuliah' => 'IF301',
                'nama_mata_kuliah' => 'Rekayasa Perangkat Lunak',
                'sks' => 3,
            ],
        ];

        $this->db->table('mata_kuliah')->insertBatch($data);
    }
}
