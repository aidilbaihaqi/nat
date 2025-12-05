<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Urutan seeder berdasarkan dependency foreign key
        
        // 1. NilaiMutu - tidak ada dependency
        $this->call('NilaiMutuSeeder');
        
        // 2. User - tidak ada dependency
        $this->call('UserSeeder');
        
        // 3. Mahasiswa - depends on User
        $this->call('MahasiswaSeeder');
        
        // 4. Dosen - depends on User
        $this->call('DosenSeeder');
        
        // 5. MataKuliah - tidak ada dependency
        $this->call('MataKuliahSeeder');
        
        // 6. Ruangan - tidak ada dependency
        $this->call('RuanganSeeder');
        
        // 7. Jadwal - depends on MataKuliah, Ruangan, Dosen
        $this->call('JadwalSeeder');
    }
}
