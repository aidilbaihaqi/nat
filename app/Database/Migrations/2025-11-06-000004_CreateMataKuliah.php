<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMataKuliah extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_mata_kuliah' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kode_mata_kuliah' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
            ],
            'nama_mata_kuliah' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'sks' => [
                'type'       => 'TINYINT',
                'constraint' => 2,
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
        
        $this->forge->addKey('id_mata_kuliah', true);
        $this->forge->addUniqueKey('kode_mata_kuliah');
        $this->forge->createTable('mata_kuliah');
    }

    public function down()
    {
        $this->forge->dropTable('mata_kuliah');
    }
}
