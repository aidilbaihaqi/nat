<?php

namespace App\Models;

use CodeIgniter\Model;

class RuanganModel extends Model
{
    protected $table = 'ruangan';
    protected $primaryKey = 'id_ruangan';
    protected $allowedFields = ['nama_ruangan'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
