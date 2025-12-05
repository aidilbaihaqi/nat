<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * @internal
 */
final class AdminCRUDTest extends CIUnitTestCase
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
        
        // Set admin session
        $_SESSION['user_id'] = 1;
        $_SESSION['role'] = 'admin';
        $_SESSION['nama_user'] = 'Admin';
    }

    // ============================================
    // Mahasiswa CRUD Tests
    // ============================================

    /**
     * Test read/list mahasiswa
     */
    public function testReadMahasiswa(): void
    {
        $result = $this->get('/admin/mahasiswa');
        
        $result->assertOK();
        $result->assertSee('Daftar Mahasiswa');
    }

    /**
     * Test create mahasiswa
     */
    public function testCreateMahasiswa(): void
    {
        $result = $this->post('/admin/mahasiswa', [
            'nim' => '2024001',
            'nama' => 'Mahasiswa Test',
            'username' => 'mhstest',
            'password' => 'test123',
            'angkatan' => '2024'
        ]);

        $result->assertRedirect();
        
        // Verify data in database
        $this->seeInDatabase('mahasiswa', ['nim' => '2024001']);
        $this->seeInDatabase('user', ['username' => 'mhstest', 'role' => 'mahasiswa']);
    }

    /**
     * Test update mahasiswa
     */
    public function testUpdateMahasiswa(): void
    {
        $result = $this->put('/admin/mahasiswa/2023001', [
            'nama' => 'Mahasiswa Updated',
            'angkatan' => '2024'
        ]);

        $result->assertRedirect();
        
        // Verify updated data
        $this->seeInDatabase('mahasiswa', ['nim' => '2023001', 'nama' => 'Mahasiswa Updated']);
    }

    /**
     * Test delete mahasiswa
     */
    public function testDeleteMahasiswa(): void
    {
        $result = $this->delete('/admin/mahasiswa/2023003');

        $result->assertRedirect();
        
        // Verify data deleted
        $this->dontSeeInDatabase('mahasiswa', ['nim' => '2023003']);
    }

    // ============================================
    // Dosen CRUD Tests
    // ============================================

    /**
     * Test read/list dosen
     */
    public function testReadDosen(): void
    {
        $result = $this->get('/admin/dosen');
        
        $result->assertOK();
        $result->assertSee('Daftar Dosen');
    }

    /**
     * Test create dosen
     */
    public function testCreateDosen(): void
    {
        $result = $this->post('/admin/dosen', [
            'nidn' => '0404049901',
            'nama' => 'Dosen Test',
            'username' => 'dosentest',
            'password' => 'test123'
        ]);

        $result->assertRedirect();
        
        // Verify data in database
        $this->seeInDatabase('dosen', ['nidn' => '0404049901']);
        $this->seeInDatabase('user', ['username' => 'dosentest', 'role' => 'dosen']);
    }

    /**
     * Test update dosen
     */
    public function testUpdateDosen(): void
    {
        $result = $this->put('/admin/dosen/0101018801', [
            'nama' => 'Dosen Updated'
        ]);

        $result->assertRedirect();
        
        // Verify updated data
        $this->seeInDatabase('dosen', ['nidn' => '0101018801', 'nama' => 'Dosen Updated']);
    }

    /**
     * Test delete dosen
     */
    public function testDeleteDosen(): void
    {
        $result = $this->delete('/admin/dosen/0303039003');

        $result->assertRedirect();
        
        // Verify data deleted
        $this->dontSeeInDatabase('dosen', ['nidn' => '0303039003']);
    }

    // ============================================
    // Mata Kuliah CRUD Tests
    // ============================================

    /**
     * Test read/list mata kuliah
     */
    public function testReadMataKuliah(): void
    {
        $result = $this->get('/admin/mata-kuliah');
        
        $result->assertOK();
        $result->assertSee('Daftar Mata Kuliah');
    }

    /**
     * Test create mata kuliah
     */
    public function testCreateMataKuliah(): void
    {
        $result = $this->post('/admin/mata-kuliah', [
            'kode_mata_kuliah' => 'MK999',
            'nama_mata_kuliah' => 'Mata Kuliah Test',
            'sks' => '3'
        ]);

        $result->assertRedirect();
        
        // Verify data in database
        $this->seeInDatabase('mata_kuliah', ['kode_mata_kuliah' => 'MK999']);
    }

    /**
     * Test update mata kuliah
     */
    public function testUpdateMataKuliah(): void
    {
        $mataKuliahModel = new \App\Models\MataKuliahModel();
        $mataKuliah = $mataKuliahModel->where('kode_mata_kuliah', 'MK101')->first();
        
        $result = $this->put('/admin/mata-kuliah/' . $mataKuliah['id_mata_kuliah'], [
            'nama_mata_kuliah' => 'Mata Kuliah Updated',
            'sks' => '4'
        ]);

        $result->assertRedirect();
        
        // Verify updated data
        $this->seeInDatabase('mata_kuliah', [
            'id_mata_kuliah' => $mataKuliah['id_mata_kuliah'],
            'nama_mata_kuliah' => 'Mata Kuliah Updated'
        ]);
    }

    /**
     * Test delete mata kuliah
     */
    public function testDeleteMataKuliah(): void
    {
        $mataKuliahModel = new \App\Models\MataKuliahModel();
        $mataKuliah = $mataKuliahModel->where('kode_mata_kuliah', 'MK105')->first();
        
        $result = $this->delete('/admin/mata-kuliah/' . $mataKuliah['id_mata_kuliah']);

        $result->assertRedirect();
        
        // Verify data deleted
        $this->dontSeeInDatabase('mata_kuliah', ['id_mata_kuliah' => $mataKuliah['id_mata_kuliah']]);
    }

    // ============================================
    // Ruangan CRUD Tests
    // ============================================

    /**
     * Test read/list ruangan
     */
    public function testReadRuangan(): void
    {
        $result = $this->get('/admin/ruangan');
        
        $result->assertOK();
        $result->assertSee('Daftar Ruangan');
    }

    /**
     * Test create ruangan
     */
    public function testCreateRuangan(): void
    {
        $result = $this->post('/admin/ruangan', [
            'nama_ruangan' => 'R999'
        ]);

        $result->assertRedirect();
        
        // Verify data in database
        $this->seeInDatabase('ruangan', ['nama_ruangan' => 'R999']);
    }

    /**
     * Test update ruangan
     */
    public function testUpdateRuangan(): void
    {
        $ruanganModel = new \App\Models\RuanganModel();
        $ruangan = $ruanganModel->where('nama_ruangan', 'R101')->first();
        
        $result = $this->put('/admin/ruangan/' . $ruangan['id_ruangan'], [
            'nama_ruangan' => 'R101-Updated'
        ]);

        $result->assertRedirect();
        
        // Verify updated data
        $this->seeInDatabase('ruangan', [
            'id_ruangan' => $ruangan['id_ruangan'],
            'nama_ruangan' => 'R101-Updated'
        ]);
    }

    /**
     * Test delete ruangan
     */
    public function testDeleteRuangan(): void
    {
        $ruanganModel = new \App\Models\RuanganModel();
        $ruangan = $ruanganModel->where('nama_ruangan', 'R103')->first();
        
        $result = $this->delete('/admin/ruangan/' . $ruangan['id_ruangan']);

        $result->assertRedirect();
        
        // Verify data deleted
        $this->dontSeeInDatabase('ruangan', ['id_ruangan' => $ruangan['id_ruangan']]);
    }

    // ============================================
    // Jadwal CRUD Tests
    // ============================================

    /**
     * Test read/list jadwal
     */
    public function testReadJadwal(): void
    {
        $result = $this->get('/admin/jadwal');
        
        $result->assertOK();
        $result->assertSee('Daftar Jadwal');
    }

    /**
     * Test create jadwal
     */
    public function testCreateJadwal(): void
    {
        $mataKuliahModel = new \App\Models\MataKuliahModel();
        $mataKuliah = $mataKuliahModel->first();
        
        $ruanganModel = new \App\Models\RuanganModel();
        $ruangan = $ruanganModel->first();
        
        $result = $this->post('/admin/jadwal', [
            'nama_kelas' => 'Kelas Test',
            'id_mata_kuliah' => $mataKuliah['id_mata_kuliah'],
            'id_ruangan' => $ruangan['id_ruangan'],
            'nidn' => '0101018801',
            'hari' => 'Senin',
            'jam' => '08:00:00',
            'semester' => '1'
        ]);

        $result->assertRedirect();
        
        // Verify data in database
        $this->seeInDatabase('jadwal', ['nama_kelas' => 'Kelas Test']);
    }

    /**
     * Test update jadwal
     */
    public function testUpdateJadwal(): void
    {
        $jadwalModel = new \App\Models\JadwalModel();
        $jadwal = $jadwalModel->first();
        
        $result = $this->put('/admin/jadwal/' . $jadwal['id'], [
            'nama_kelas' => 'Kelas Updated',
            'id_mata_kuliah' => $jadwal['id_mata_kuliah'],
            'id_ruangan' => $jadwal['id_ruangan'],
            'nidn' => $jadwal['nidn'],
            'hari' => 'Selasa',
            'jam' => '10:00:00',
            'semester' => '1'
        ]);

        $result->assertRedirect();
        
        // Verify updated data
        $this->seeInDatabase('jadwal', [
            'id' => $jadwal['id'],
            'nama_kelas' => 'Kelas Updated'
        ]);
    }

    /**
     * Test delete jadwal
     */
    public function testDeleteJadwal(): void
    {
        $jadwalModel = new \App\Models\JadwalModel();
        $jadwal = $jadwalModel->orderBy('id', 'DESC')->first();
        
        $result = $this->delete('/admin/jadwal/' . $jadwal['id']);

        $result->assertRedirect();
        
        // Verify data deleted
        $this->dontSeeInDatabase('jadwal', ['id' => $jadwal['id']]);
    }
}
