<?php

namespace App\Models;

use CodeIgniter\Model;

class DosenModel extends Model
{
    protected $table = 'dosen';
    protected $primaryKey = 'nidn';
    protected $allowedFields = ['nidn', 'nama', 'user_id'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get dosen with user data by NIDN
     * 
     * @param string $nidn
     * @return array|null
     */
    public function getWithUser($nidn)
    {
        return $this->select('dosen.*, user.nama_user, user.username, user.role')
                    ->join('user', 'user.id_user = dosen.user_id')
                    ->where('dosen.nidn', $nidn)
                    ->first();
    }

    /**
     * Get all dosen with user data
     * 
     * @return array
     */
    public function getAllWithUser()
    {
        return $this->select('dosen.*, user.nama_user, user.username, user.role')
                    ->join('user', 'user.id_user = dosen.user_id')
                    ->findAll();
    }
}
