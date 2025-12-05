<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class NilaiMutuSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['nilai_huruf' => 'A', 'nilai_mutu' => 4.00],
            ['nilai_huruf' => 'A-', 'nilai_mutu' => 3.70],
            ['nilai_huruf' => 'B+', 'nilai_mutu' => 3.50],
            ['nilai_huruf' => 'B', 'nilai_mutu' => 3.00],
            ['nilai_huruf' => 'B-', 'nilai_mutu' => 2.70],
            ['nilai_huruf' => 'C+', 'nilai_mutu' => 2.50],
            ['nilai_huruf' => 'C', 'nilai_mutu' => 2.00],
            ['nilai_huruf' => 'D', 'nilai_mutu' => 1.00],
            ['nilai_huruf' => 'E', 'nilai_mutu' => 0.00],
        ];

        $this->db->table('nilai_mutu')->insertBatch($data);
    }
}
