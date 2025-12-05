<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * @internal
 */
final class DataValidationTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $migrate     = true;
    protected $migrateOnce = false;
    protected $refresh     = true;
    protected $namespace   = null;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed test data
        $this->seed('App\Database\Seeds\NilaiMutuSeeder');
        $this->seed('App\Database\Seeds\UserSeeder');
        $this->seed('App\Database\Seeds\MahasiswaSeeder');
        $this->seed('App\Database\Seeds\DosenSeeder');
        $this->seed('App\Database\Seeds\MataKuliahSeeder');
        $this->seed('App\Database\Seeds\RuanganSeeder');
        $this->seed('App\Database\Seeds\JadwalSeeder');
        
        // Set admin session for CRUD operations
        $_SESSION['user_id'] = 1;
        $_SESSION['role'] = 'admin';
        $_SESSION['nama_user'] = 'Admin';
    }

    // ============================================
    // Unique Constraint Tests
    // ============================================

    /**
     * Test unique constraint untuk username
     */
    public function testUsernameUniqueConstraint(): void
    {
        // Try to create mahasiswa with existing username
        $result = $this->post('/admin/mahasiswa', [
            'nim' => '2024999',
            'nama' => 'Test Mahasiswa',
            'username' => 'admin', // Existing username
            'password' => 'test123',
            'angkatan' => '2024'
        ]);

        $result->assertRedirect();
        $result->assertSessionHas('error');
        
        // Should not create new mahasiswa
        $this->dontSeeInDatabase('mahasiswa', ['nim' => '2024999']);
    }

    /**
     * Test unique constraint untuk username saat create dosen
     */
    public function testUsernameUniqueConstraintForDosen(): void
    {
        // Try to create dosen with existing username
        $result = $this->post('/admin/dosen', [
            'nidn' => '9999999999',
            'nama' => 'Test Dosen',
            'username' => 'mahasiswa1', // Existing username
            'password' => 'test123'
        ]);

        $result->assertRedirect();
        $result->assertSessionHas('error');
        
        // Should not create new dosen
        $this->dontSeeInDatabase('dosen', ['nidn' => '9999999999']);
    }

    /**
     * Test unique constraint untuk kode_mata_kuliah
     */
    public function testKodeMataKuliahUniqueConstraint(): void
    {
        // Try to create mata kuliah with existing kode
        $result = $this->post('/admin/mata-kuliah', [
            'kode_mata_kuliah' => 'MK101', // Existing kode
            'nama_mata_kuliah' => 'Test Mata Kuliah',
            'sks' => '3'
        ]);

        $result->assertRedirect();
        $result->assertSessionHas('error');
        
        // Should not create duplicate
        $mataKuliahModel = new \App\Models\MataKuliahModel();
        $count = $mataKuliahModel->where('kode_mata_kuliah', 'MK101')->countAllResults();
        $this->assertEquals(1, $count);
    }

    /**
     * Test unique constraint untuk nama_ruangan
     */
    public function testNamaRuanganUniqueConstraint(): void
    {
        // Try to create ruangan with existing nama
        $result = $this->post('/admin/ruangan', [
            'nama_ruangan' => 'R101' // Existing nama
        ]);

        $result->assertRedirect();
        $result->assertSessionHas('error');
        
        // Should not create duplicate
        $ruanganModel = new \App\Models\RuanganModel();
        $count = $ruanganModel->where('nama_ruangan', 'R101')->countAllResults();
        $this->assertEquals(1, $count);
    }

    /**
     * Test unique constraint untuk kombinasi nim dan id_jadwal di rencana_studi
     */
    public function testRencanaStudiUniqueConstraint(): void
    {
        // Set mahasiswa session
        $_SESSION['user_id'] = 4;
        $_SESSION['role'] = 'mahasiswa';
        $_SESSION['nama_user'] = 'Mahasiswa Satu';
        $_SESSION['nim'] = '2023001';
        
        $jadwalModel = new \App\Models\JadwalModel();
        $jadwal = $jadwalModel->first();
        
        // Add jadwal to KRS first time
        $result = $this->post('/mhs/krs', [
            'id_jadwal' => $jadwal['id']
        ]);
        
        $result->assertRedirect();
        $this->seeInDatabase('rencana_studi', [
            'nim' => '2023001',
            'id_jadwal' => $jadwal['id']
        ]);
        
        // Try to add same jadwal again
        $result = $this->post('/mhs/krs', [
            'id_jadwal' => $jadwal['id']
        ]);

        $result->assertRedirect();
        $result->assertSessionHas('error');
        
        // Should only have one record
        $rencanaStudiModel = new \App\Models\RencanaStudiModel();
        $count = $rencanaStudiModel->where([
            'nim' => '2023001',
            'id_jadwal' => $jadwal['id']
        ])->countAllResults();
        $this->assertEquals(1, $count);
    }

    // ============================================
    // Foreign Key Constraint Tests
    // ============================================

    /**
     * Test foreign key constraint untuk mahasiswa.user_id
     */
    public function testMahasiswaUserIdForeignKey(): void
    {
        $mahasiswaModel = new \App\Models\MahasiswaModel();
        
        try {
            // Try to insert mahasiswa with non-existent user_id
            $mahasiswaModel->insert([
                'nim' => '9999999',
                'nama' => 'Test',
                'user_id' => 99999, // Non-existent user_id
                'angkatan' => '2024'
            ]);
            
            $this->fail('Should throw exception for foreign key constraint');
        } catch (\Exception $e) {
            // Expected exception
            $this->assertTrue(true);
        }
    }

    /**
     * Test foreign key constraint untuk dosen.user_id
     */
    public function testDosenUserIdForeignKey(): void
    {
        $dosenModel = new \App\Models\DosenModel();
        
        try {
            // Try to insert dosen with non-existent user_id
            $dosenModel->insert([
                'nidn' => '9999999999',
                'nama' => 'Test',
                'user_id' => 99999 // Non-existent user_id
            ]);
            
            $this->fail('Should throw exception for foreign key constraint');
        } catch (\Exception $e) {
            // Expected exception
            $this->assertTrue(true);
        }
    }

    /**
     * Test foreign key constraint untuk jadwal.id_mata_kuliah
     */
    public function testJadwalMataKuliahForeignKey(): void
    {
        $jadwalModel = new \App\Models\JadwalModel();
        $ruanganModel = new \App\Models\RuanganModel();
        $ruangan = $ruanganModel->first();
        
        try {
            // Try to insert jadwal with non-existent id_mata_kuliah
            $jadwalModel->insert([
                'nama_kelas' => 'Test',
                'id_mata_kuliah' => 99999, // Non-existent
                'id_ruangan' => $ruangan['id_ruangan'],
                'nidn' => '0101018801',
                'hari' => 'Senin',
                'jam' => '08:00:00',
                'semester' => '1'
            ]);
            
            $this->fail('Should throw exception for foreign key constraint');
        } catch (\Exception $e) {
            // Expected exception
            $this->assertTrue(true);
        }
    }

    /**
     * Test foreign key constraint untuk jadwal.id_ruangan
     */
    public function testJadwalRuanganForeignKey(): void
    {
        $jadwalModel = new \App\Models\JadwalModel();
        $mataKuliahModel = new \App\Models\MataKuliahModel();
        $mataKuliah = $mataKuliahModel->first();
        
        try {
            // Try to insert jadwal with non-existent id_ruangan
            $jadwalModel->insert([
                'nama_kelas' => 'Test',
                'id_mata_kuliah' => $mataKuliah['id_mata_kuliah'],
                'id_ruangan' => 99999, // Non-existent
                'nidn' => '0101018801',
                'hari' => 'Senin',
                'jam' => '08:00:00',
                'semester' => '1'
            ]);
            
            $this->fail('Should throw exception for foreign key constraint');
        } catch (\Exception $e) {
            // Expected exception
            $this->assertTrue(true);
        }
    }

    /**
     * Test foreign key constraint untuk jadwal.nidn
     */
    public function testJadwalDosenForeignKey(): void
    {
        $jadwalModel = new \App\Models\JadwalModel();
        $mataKuliahModel = new \App\Models\MataKuliahModel();
        $mataKuliah = $mataKuliahModel->first();
        $ruanganModel = new \App\Models\RuanganModel();
        $ruangan = $ruanganModel->first();
        
        try {
            // Try to insert jadwal with non-existent nidn
            $jadwalModel->insert([
                'nama_kelas' => 'Test',
                'id_mata_kuliah' => $mataKuliah['id_mata_kuliah'],
                'id_ruangan' => $ruangan['id_ruangan'],
                'nidn' => '9999999999', // Non-existent
                'hari' => 'Senin',
                'jam' => '08:00:00',
                'semester' => '1'
            ]);
            
            $this->fail('Should throw exception for foreign key constraint');
        } catch (\Exception $e) {
            // Expected exception
            $this->assertTrue(true);
        }
    }

    /**
     * Test foreign key constraint untuk rencana_studi.nim
     */
    public function testRencanaStudiNimForeignKey(): void
    {
        $rencanaStudiModel = new \App\Models\RencanaStudiModel();
        $jadwalModel = new \App\Models\JadwalModel();
        $jadwal = $jadwalModel->first();
        
        try {
            // Try to insert rencana_studi with non-existent nim
            $rencanaStudiModel->insert([
                'nim' => '9999999', // Non-existent
                'id_jadwal' => $jadwal['id']
            ]);
            
            $this->fail('Should throw exception for foreign key constraint');
        } catch (\Exception $e) {
            // Expected exception
            $this->assertTrue(true);
        }
    }

    /**
     * Test foreign key constraint untuk rencana_studi.id_jadwal
     */
    public function testRencanaStudiJadwalForeignKey(): void
    {
        $rencanaStudiModel = new \App\Models\RencanaStudiModel();
        
        try {
            // Try to insert rencana_studi with non-existent id_jadwal
            $rencanaStudiModel->insert([
                'nim' => '2023001',
                'id_jadwal' => 99999 // Non-existent
            ]);
            
            $this->fail('Should throw exception for foreign key constraint');
        } catch (\Exception $e) {
            // Expected exception
            $this->assertTrue(true);
        }
    }

    /**
     * Test cascade delete untuk mahasiswa
     */
    public function testCascadeDeleteMahasiswa(): void
    {
        // Create mahasiswa with KRS
        $result = $this->post('/admin/mahasiswa', [
            'nim' => '2024888',
            'nama' => 'Test Mahasiswa',
            'username' => 'testmhs888',
            'password' => 'test123',
            'angkatan' => '2024'
        ]);
        
        // Add KRS for this mahasiswa
        $jadwalModel = new \App\Models\JadwalModel();
        $jadwal = $jadwalModel->first();
        
        $rencanaStudiModel = new \App\Models\RencanaStudiModel();
        $rencanaStudiModel->insert([
            'nim' => '2024888',
            'id_jadwal' => $jadwal['id']
        ]);
        
        // Verify data exists
        $this->seeInDatabase('mahasiswa', ['nim' => '2024888']);
        $this->seeInDatabase('rencana_studi', ['nim' => '2024888']);
        
        // Delete mahasiswa
        $result = $this->delete('/admin/mahasiswa/2024888');
        
        // Verify cascade delete
        $this->dontSeeInDatabase('mahasiswa', ['nim' => '2024888']);
        $this->dontSeeInDatabase('rencana_studi', ['nim' => '2024888']);
    }

    /**
     * Test cascade delete untuk jadwal
     */
    public function testCascadeDeleteJadwal(): void
    {
        // Create jadwal
        $mataKuliahModel = new \App\Models\MataKuliahModel();
        $mataKuliah = $mataKuliahModel->first();
        
        $ruanganModel = new \App\Models\RuanganModel();
        $ruangan = $ruanganModel->first();
        
        $result = $this->post('/admin/jadwal', [
            'nama_kelas' => 'Test Cascade',
            'id_mata_kuliah' => $mataKuliah['id_mata_kuliah'],
            'id_ruangan' => $ruangan['id_ruangan'],
            'nidn' => '0101018801',
            'hari' => 'Senin',
            'jam' => '08:00:00',
            'semester' => '1'
        ]);
        
        // Get created jadwal
        $jadwalModel = new \App\Models\JadwalModel();
        $jadwal = $jadwalModel->where('nama_kelas', 'Test Cascade')->first();
        
        // Add rencana_studi for this jadwal
        $rencanaStudiModel = new \App\Models\RencanaStudiModel();
        $rencanaStudiModel->insert([
            'nim' => '2023001',
            'id_jadwal' => $jadwal['id']
        ]);
        
        // Verify data exists
        $this->seeInDatabase('jadwal', ['id' => $jadwal['id']]);
        $this->seeInDatabase('rencana_studi', ['id_jadwal' => $jadwal['id']]);
        
        // Delete jadwal
        $result = $this->delete('/admin/jadwal/' . $jadwal['id']);
        
        // Verify cascade delete
        $this->dontSeeInDatabase('jadwal', ['id' => $jadwal['id']]);
        $this->dontSeeInDatabase('rencana_studi', ['id_jadwal' => $jadwal['id']]);
    }

    /**
     * Test validasi nilai_huruf exists in nilai_mutu
     */
    public function testNilaiHurufValidation(): void
    {
        // Set dosen session
        $_SESSION['user_id'] = 7;
        $_SESSION['role'] = 'dosen';
        $_SESSION['nama_user'] = 'Dosen Satu';
        $_SESSION['nidn'] = '0101018801';
        
        // Get jadwal for this dosen
        $jadwalModel = new \App\Models\JadwalModel();
        $jadwal = $jadwalModel->where('nidn', '0101018801')->first();
        
        // Add mahasiswa to jadwal
        $rencanaStudiModel = new \App\Models\RencanaStudiModel();
        $idRencanaStudi = $rencanaStudiModel->insert([
            'nim' => '2023001',
            'id_jadwal' => $jadwal['id']
        ]);
        
        // Try to input invalid nilai_huruf
        $result = $this->post('/dosen/nilai/' . $jadwal['id'], [
            'nilai' => [
                [
                    'id_rencana_studi' => $idRencanaStudi,
                    'nilai_angka' => 85.0,
                    'nilai_huruf' => 'X' // Invalid
                ]
            ]
        ]);

        $result->assertRedirect();
        $result->assertSessionHas('error');
        
        // Should not save invalid nilai_huruf
        $this->dontSeeInDatabase('rencana_studi', [
            'id_rencana_studi' => $idRencanaStudi,
            'nilai_huruf' => 'X'
        ]);
    }

    /**
     * Test valid nilai_huruf values
     */
    public function testValidNilaiHurufValues(): void
    {
        $nilaiMutuModel = new \App\Models\NilaiMutuModel();
        $validGrades = $nilaiMutuModel->findAll();
        
        // Verify all expected grades exist
        $expectedGrades = ['A', 'A-', 'B+', 'B', 'B-', 'C+', 'C', 'D', 'E'];
        
        foreach ($expectedGrades as $grade) {
            $this->seeInDatabase('nilai_mutu', ['nilai_huruf' => $grade]);
        }
        
        // Verify count
        $this->assertCount(9, $validGrades);
    }
}
