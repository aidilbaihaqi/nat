<?php

namespace App\Models;

use CodeIgniter\Model;

class MataKuliahModel extends Model
{
    protected $table = 'mata_kuliah';
    protected $primaryKey = 'id_mata_kuliah';
    protected $allowedFields = ['kode_mata_kuliah', 'nama_mata_kuliah', 'sks'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
