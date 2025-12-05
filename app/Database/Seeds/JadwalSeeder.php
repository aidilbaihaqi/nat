<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class JadwalSeeder extends Seeder
{
    public function run()
    {
        // Get IDs for mata kuliah
        $mkPemdasar = $this->db->table('mata_kuliah')->where('kode_mata_kuliah', 'IF101')->get()->getRow();
        $mkStrukdat = $this->db->table('mata_kuliah')->where('kode_mata_kuliah', 'IF102')->get()->getRow();
        $mkBasisData = $this->db->table('mata_kuliah')->where('kode_mata_kuliah', 'IF201')->get()->getRow();
        $mkPemweb = $this->db->table('mata_kuliah')->where('kode_mata_kuliah', 'IF202')->get()->getRow();
        $mkRPL = $this->db->table('mata_kuliah')->where('kode_mata_kuliah', 'IF301')->get()->getRow();

        // Get IDs for ruangan
        $ruangLab1 = $this->db->table('ruangan')->where('nama_ruangan', 'Lab Komputer 1')->get()->getRow();
        $ruangLab2 = $this->db->table('ruangan')->where('nama_ruangan', 'Lab Komputer 2')->get()->getRow();
        $ruangKelas = $this->db->table('ruangan')->where('nama_ruangan', 'Ruang Kelas A301')->get()->getRow();

        // Get NIDN for dosen
        $dosenYanto = $this->db->table('dosen')->where('nama', 'Prof. Yanto')->get()->getRow();
        $dosenMaria = $this->db->table('dosen')->where('nama', 'Prof. Maria')->get()->getRow();
        $dosenBayu = $this->db->table('dosen')->where('nama', 'Prof. Bayu')->get()->getRow();

        $data = [
            [
                'nama_kelas' => 'IF101-A',
                'id_mata_kuliah' => $mkPemdasar->id_mata_kuliah,
                'id_ruangan' => $ruangLab1->id_ruangan,
                'nidn' => $dosenYanto->nidn,
                'hari' => 'Senin',
                'jam' => '08:00:00',
                'semester' => 1,
            ],
            [
                'nama_kelas' => 'IF102-A',
                'id_mata_kuliah' => $mkStrukdat->id_mata_kuliah,
                'id_ruangan' => $ruangLab1->id_ruangan,
                'nidn' => $dosenYanto->nidn,
                'hari' => 'Selasa',
                'jam' => '10:00:00',
                'semester' => 1,
            ],
            [
                'nama_kelas' => 'IF201-A',
                'id_mata_kuliah' => $mkBasisData->id_mata_kuliah,
                'id_ruangan' => $ruangLab2->id_ruangan,
                'nidn' => $dosenMaria->nidn,
                'hari' => 'Rabu',
                'jam' => '08:00:00',
                'semester' => 1,
            ],
            [
                'nama_kelas' => 'IF202-A',
                'id_mata_kuliah' => $mkPemweb->id_mata_kuliah,
                'id_ruangan' => $ruangLab2->id_ruangan,
                'nidn' => $dosenMaria->nidn,
                'hari' => 'Kamis',
                'jam' => '13:00:00',
                'semester' => 1,
            ],
            [
                'nama_kelas' => 'IF301-A',
                'id_mata_kuliah' => $mkRPL->id_mata_kuliah,
                'id_ruangan' => $ruangKelas->id_ruangan,
                'nidn' => $dosenBayu->nidn,
                'hari' => 'Jumat',
                'jam' => '08:00:00',
                'semester' => 1,
            ],
            [
                'nama_kelas' => 'IF101-B',
                'id_mata_kuliah' => $mkPemdasar->id_mata_kuliah,
                'id_ruangan' => $ruangLab1->id_ruangan,
                'nidn' => $dosenBayu->nidn,
                'hari' => 'Senin',
                'jam' => '13:00:00',
                'semester' => 1,
            ],
        ];

        $this->db->table('jadwal')->insertBatch($data);
    }
}
