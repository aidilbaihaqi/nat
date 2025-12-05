<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNilaiMutu extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'nilai_huruf' => [
                'type'       => 'CHAR',
                'constraint' => '2',
            ],
            'nilai_mutu' => [
                'type'       => 'DECIMAL',
                'constraint' => '3,2',
            ],
        ]);
        
        $this->forge->addKey('nilai_huruf', true);
        $this->forge->createTable('nilai_mutu');
    }

    public function down()
    {
        $this->forge->dropTable('nilai_mutu');
    }
}
