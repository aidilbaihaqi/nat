<?php

namespace App\Models;

use CodeIgniter\Model;

class JadwalModel extends Model
{
    protected $table = 'jadwal';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama_kelas', 'id_mata_kuliah', 'id_ruangan', 'nidn', 'hari', 'jam', 'semester'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get jadwal with details (mata kuliah, ruangan, dosen) by ID
     * 
     * @param int $id
     * @return array|null
     */
    public function getWithDetails($id)
    {
        return $this->select('jadwal.*, 
                             mata_kuliah.kode_mata_kuliah, mata_kuliah.nama_mata_kuliah, mata_kuliah.sks,
                             ruangan.nama_ruangan,
                             dosen.nama as nama_dosen')
                    ->join('mata_kuliah', 'mata_kuliah.id_mata_kuliah = jadwal.id_mata_kuliah')
                    ->join('ruangan', 'ruangan.id_ruangan = jadwal.id_ruangan')
                    ->join('dosen', 'dosen.nidn = jadwal.nidn')
                    ->where('jadwal.id', $id)
                    ->first();
    }

    /**
     * Get all jadwal with details (mata kuliah, ruangan, dosen)
     * 
     * @return array
     */
    public function getAllWithDetails()
    {
        return $this->select('jadwal.*, 
                             mata_kuliah.kode_mata_kuliah, mata_kuliah.nama_mata_kuliah, mata_kuliah.sks,
                             ruangan.nama_ruangan,
                             dosen.nama as nama_dosen')
                    ->join('mata_kuliah', 'mata_kuliah.id_mata_kuliah = jadwal.id_mata_kuliah')
                    ->join('ruangan', 'ruangan.id_ruangan = jadwal.id_ruangan')
                    ->join('dosen', 'dosen.nidn = jadwal.nidn')
                    ->findAll();
    }

    /**
     * Get jadwal by dosen NIDN
     * 
     * @param string $nidn
     * @return array
     */
    public function getByDosen($nidn)
    {
        return $this->select('jadwal.*, 
                             mata_kuliah.kode_mata_kuliah, mata_kuliah.nama_mata_kuliah, mata_kuliah.sks,
                             ruangan.nama_ruangan')
                    ->join('mata_kuliah', 'mata_kuliah.id_mata_kuliah = jadwal.id_mata_kuliah')
                    ->join('ruangan', 'ruangan.id_ruangan = jadwal.id_ruangan')
                    ->where('jadwal.nidn', $nidn)
                    ->findAll();
    }
}
