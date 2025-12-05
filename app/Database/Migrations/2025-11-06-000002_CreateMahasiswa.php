<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMahasiswa extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'nim' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'angkatan' => [
                'type' => 'YEAR',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('nim', true);
        $this->forge->addForeignKey('user_id', 'user', 'id_user', 'CASCADE', 'CASCADE');
        $this->forge->createTable('mahasiswa');
    }

    public function down()
    {
        $this->forge->dropTable('mahasiswa');
    }
}
