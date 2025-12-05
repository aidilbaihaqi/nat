<?php

namespace App\Models;

use CodeIgniter\Model;

class RencanaStudiModel extends Model
{
    protected $table = 'rencana_studi';
    protected $primaryKey = 'id_rencana_studi';
    protected $allowedFields = ['nim', 'id_jadwal', 'nilai_angka', 'nilai_huruf'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get KRS by mahasiswa NIM with jadwal details
     * 
     * @param string $nim
     * @return array
     */
    public function getByMahasiswa($nim)
    {
        return $this->select('rencana_studi.*, 
                             jadwal.nama_kelas, jadwal.hari, jadwal.jam, jadwal.semester,
                             mata_kuliah.kode_mata_kuliah, mata_kuliah.nama_mata_kuliah, mata_kuliah.sks,
                             ruangan.nama_ruangan,
                             dosen.nama as nama_dosen')
                    ->join('jadwal', 'jadwal.id = rencana_studi.id_jadwal')
                    ->join('mata_kuliah', 'mata_kuliah.id_mata_kuliah = jadwal.id_mata_kuliah')
                    ->join('ruangan', 'ruangan.id_ruangan = jadwal.id_ruangan')
                    ->join('dosen', 'dosen.nidn = jadwal.nidn')
                    ->where('rencana_studi.nim', $nim)
                    ->findAll();
    }

    /**
     * Get mahasiswa by jadwal ID
     * 
     * @param int $id_jadwal
     * @return array
     */
    public function getByJadwal($id_jadwal)
    {
        return $this->select('rencana_studi.*, mahasiswa.nama')
                    ->join('mahasiswa', 'mahasiswa.nim = rencana_studi.nim')
                    ->where('rencana_studi.id_jadwal', $id_jadwal)
                    ->findAll();
    }

    /**
     * Update nilai for rencana studi
     * 
     * @param int $id
     * @param float $nilai_angka
     * @param string $nilai_huruf
     * @return bool
     */
    public function updateNilai($id, $nilai_angka, $nilai_huruf)
    {
        return $this->update($id, [
            'nilai_angka' => $nilai_angka,
            'nilai_huruf' => $nilai_huruf
        ]);
    }
}
