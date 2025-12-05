<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDosen extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'nidn' => [
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
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('nidn', true);
        $this->forge->addForeignKey('user_id', 'user', 'id_user', 'CASCADE', 'CASCADE');
        $this->forge->createTable('dosen');
    }

    public function down()
    {
        $this->forge->dropTable('dosen');
    }
}
