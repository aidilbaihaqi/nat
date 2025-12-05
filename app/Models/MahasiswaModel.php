<?php

namespace App\Models;

use CodeIgniter\Model;

class MahasiswaModel extends Model
{
    protected $table = 'mahasiswa';
    protected $primaryKey = 'nim';
    protected $allowedFields = ['nim', 'nama', 'user_id', 'angkatan'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get mahasiswa with user data by NIM
     * 
     * @param string $nim
     * @return array|null
     */
    public function getWithUser($nim)
    {
        return $this->select('mahasiswa.*, user.nama_user, user.username, user.role')
                    ->join('user', 'user.id_user = mahasiswa.user_id')
                    ->where('mahasiswa.nim', $nim)
                    ->first();
    }

    /**
     * Get all mahasiswa with user data
     * 
     * @return array
     */
    public function getAllWithUser()
    {
        return $this->select('mahasiswa.*, user.nama_user, user.username, user.role')
                    ->join('user', 'user.id_user = mahasiswa.user_id')
                    ->findAll();
    }
}
