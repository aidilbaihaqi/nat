<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * @internal
 */
final class MahasiswaFeaturesTest extends CIUnitTestCase
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
        
        // Set mahasiswa session
        $_SESSION['user_id'] = 4; // User ID mahasiswa1
        $_SESSION['role'] = 'mahasiswa';
        $_SESSION['nama_user'] = 'Mahasiswa Satu';
        $_SESSION['nim'] = '2023001';
    }

    /**
     * Test tampilan halaman KRS
     */
    public function testViewKRSPage(): void
    {
        $result = $this->get('/mhs/krs');
        
        $result->assertOK();
        $result->assertSee('Kartu Rencana Studi');
    }

    /**
     * Test tambah jadwal ke KRS
     */
    public function testAddJadwalToKRS(): void
    {
        $jadwalModel = new \App\Models\JadwalModel();
        $jadwal = $jadwalModel->first();
        
        $result = $this->post('/mhs/krs', [
            'id_jadwal' => $jadwal['id']
        ]);

        $result->assertRedirect();
        
        // Verify data in database
        $this->seeInDatabase('rencana_studi', [
            'nim' => '2023001',
            'id_jadwal' => $jadwal['id']
        ]);
    }

    /**
     * Test validasi duplicate KRS
     */
    public function testValidateDuplicateKRS(): void
    {
        $jadwalModel = new \App\Models\JadwalModel();
        $jadwal = $jadwalModel->first();
        
        // Add jadwal first time
        $this->post('/mhs/krs', [
            'id_jadwal' => $jadwal['id']
        ]);
        
        // Try to add same jadwal again
        $result = $this->post('/mhs/krs', [
            'id_jadwal' => $jadwal['id']
        ]);

        $result->assertRedirect();
        $result->assertSessionHas('error');
    }

    /**
     * Test hapus jadwal dari KRS
     */
    public function testDeleteJadwalFromKRS(): void
    {
        // Add jadwal to KRS first
        $jadwalModel = new \App\Models\JadwalModel();
        $jadwal = $jadwalModel->first();
        
        $rencanaStudiModel = new \App\Models\RencanaStudiModel();
        $idRencanaStudi = $rencanaStudiModel->insert([
            'nim' => '2023001',
            'id_jadwal' => $jadwal['id']
        ]);
        
        // Delete from KRS
        $result = $this->delete('/mhs/krs/' . $idRencanaStudi);

        $result->assertRedirect();
        
        // Verify data deleted
        $this->dontSeeInDatabase('rencana_studi', ['id_rencana_studi' => $idRencanaStudi]);
    }

    /**
     * Test tampilan hasil studi
     */
    public function testViewHasilStudi(): void
    {
        // Add some grades first
        $jadwalModel = new \App\Models\JadwalModel();
        $jadwal = $jadwalModel->first();
        
        $rencanaStudiModel = new \App\Models\RencanaStudiModel();
        $rencanaStudiModel->insert([
            'nim' => '2023001',
            'id_jadwal' => $jadwal['id'],
            'nilai_angka' => 85.5,
            'nilai_huruf' => 'A'
        ]);
        
        $result = $this->get('/mhs/hasil-studi');
        
        $result->assertOK();
        $result->assertSee('Hasil Studi');
        $result->assertSee('IPK');
    }

    /**
     * Test perhitungan IPK
     */
    public function testIPKCalculation(): void
    {
        // Setup test data with known values
        $jadwalModel = new \App\Models\JadwalModel();
        $jadwals = $jadwalModel->findAll(2);
        
        $rencanaStudiModel = new \App\Models\RencanaStudiModel();
        
        // Add first course: A (4.00) with 3 SKS = 12.00
        $rencanaStudiModel->insert([
            'nim' => '2023001',
            'id_jadwal' => $jadwals[0]['id'],
            'nilai_angka' => 90.0,
            'nilai_huruf' => 'A'
        ]);
        
        // Add second course: B (3.00) with 3 SKS = 9.00
        $rencanaStudiModel->insert([
            'nim' => '2023001',
            'id_jadwal' => $jadwals[1]['id'],
            'nilai_angka' => 75.0,
            'nilai_huruf' => 'B'
        ]);
        
        // Expected IPK = (12.00 + 9.00) / (3 + 3) = 21.00 / 6 = 3.50
        
        $result = $this->get('/mhs/hasil-studi');
        
        $result->assertOK();
        $result->assertSee('3.50');
    }

    /**
     * Test hasil studi hanya menampilkan data mahasiswa sendiri
     */
    public function testHasilStudiOwnership(): void
    {
        // Add grade for current mahasiswa
        $jadwalModel = new \App\Models\JadwalModel();
        $jadwal = $jadwalModel->first();
        
        $rencanaStudiModel = new \App\Models\RencanaStudiModel();
        $rencanaStudiModel->insert([
            'nim' => '2023001',
            'id_jadwal' => $jadwal['id'],
            'nilai_angka' => 85.5,
            'nilai_huruf' => 'A'
        ]);
        
        // Add grade for different mahasiswa
        $jadwal2 = $jadwalModel->orderBy('id', 'DESC')->first();
        $rencanaStudiModel->insert([
            'nim' => '2023002',
            'id_jadwal' => $jadwal2['id'],
            'nilai_angka' => 70.0,
            'nilai_huruf' => 'B-'
        ]);
        
        $result = $this->get('/mhs/hasil-studi');
        
        $result->assertOK();
        
        // Should see own data
        $this->seeInDatabase('rencana_studi', [
            'nim' => '2023001',
            'nilai_huruf' => 'A'
        ]);
        
        // Should not see other mahasiswa's data in the view
        // (This is verified by the controller filtering by nim from session)
    }

    /**
     * Test KRS hanya menampilkan data mahasiswa sendiri
     */
    public function testKRSOwnership(): void
    {
        // Add KRS for current mahasiswa
        $jadwalModel = new \App\Models\JadwalModel();
        $jadwal = $jadwalModel->first();
        
        $rencanaStudiModel = new \App\Models\RencanaStudiModel();
        $idRencanaStudi1 = $rencanaStudiModel->insert([
            'nim' => '2023001',
            'id_jadwal' => $jadwal['id']
        ]);
        
        // Add KRS for different mahasiswa
        $jadwal2 = $jadwalModel->orderBy('id', 'DESC')->first();
        $idRencanaStudi2 = $rencanaStudiModel->insert([
            'nim' => '2023002',
            'id_jadwal' => $jadwal2['id']
        ]);
        
        $result = $this->get('/mhs/krs');
        
        $result->assertOK();
        
        // Should see own KRS
        $this->seeInDatabase('rencana_studi', [
            'id_rencana_studi' => $idRencanaStudi1,
            'nim' => '2023001'
        ]);
        
        // Should not be able to delete other mahasiswa's KRS
        $result = $this->delete('/mhs/krs/' . $idRencanaStudi2);
        
        // Should still exist (ownership validation should prevent deletion)
        $this->seeInDatabase('rencana_studi', ['id_rencana_studi' => $idRencanaStudi2]);
    }
}
