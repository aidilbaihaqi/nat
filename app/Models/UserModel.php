<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'id_user';
    protected $allowedFields = ['nama_user', 'username', 'password_hash', 'role', 'kode_peran'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Find user by username
     * 
     * @param string $username
     * @return array|null
     */
    public function findByUsername($username)
    {
        return $this->where('username', $username)->first();
    }

    /**
     * Create user with specific role
     * 
     * @param array $data User data including nama_user, username, password
     * @param string $role Role: 'admin', 'mahasiswa', or 'dosen'
     * @return int|false User ID if successful, false otherwise
     */
    public function createWithRole($data, $role)
    {
        $userData = [
            'nama_user' => $data['nama_user'],
            'username' => $data['username'],
            'password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
            'role' => $role,
            'kode_peran' => $data['kode_peran'] ?? null
        ];

        return $this->insert($userData);
    }
}
