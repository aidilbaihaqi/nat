<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DosenSeeder extends Seeder
{
    public function run()
    {
        // Get user IDs for dosen users
        $userYanto = $this->db->table('user')->where('username', 'yanto.kopling')->get()->getRow();
        $userMaria = $this->db->table('user')->where('username', 'maria.spakbor')->get()->getRow();
        $userBayu = $this->db->table('user')->where('username', 'bayu.coconut')->get()->getRow();

        $data = [
            [
                'nidn' => '0101018801',
                'nama' => 'Prof. Yanto',
                'user_id' => $userYanto->id_user,
            ],
            [
                'nidn' => '0102019002',
                'nama' => 'Prof. Maria',
                'user_id' => $userMaria->id_user,
            ],
            [
                'nidn' => '0103017503',
                'nama' => 'Prof. Bayu',
                'user_id' => $userBayu->id_user,
            ],
        ];

        $this->db->table('dosen')->insertBatch($data);
    }
}
