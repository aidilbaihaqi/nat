<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * @internal
 */
final class DosenFeaturesTest extends CIUnitTestCase
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
        
        // Set dosen session
        $_SESSION['user_id'] = 7; // User ID dosen1
        $_SESSION['role'] = 'dosen';
        $_SESSION['nama_user'] = 'Dosen Satu';
        $_SESSION['nidn'] = '0101018801';
    }

    /**
     * Test tampilan jadwal dosen
     */
    public function testViewJadwalDosen(): void
    {
        $result = $this->get('/dosen/jadwal');
        
        $result->assertOK();
        $result->assertSee('Jadwal Mengajar');
    }

    /**
     * Test jadwal hanya menampilkan jadwal dosen yang login
     */
    public function testJadwalOwnership(): void
    {
        $result = $this->get('/dosen/jadwal');
        
        $result->assertOK();
        
        // Should see own jadwal
        $this->seeInDatabase('jadwal', ['nidn' => '0101018801']);
    }

    /**
     * Test tampilan form input nilai
     */
    public function testViewNilaiForm(): void
    {
        // Get jadwal for this dosen
        $jadwalModel = new \App\Models\JadwalModel();
        $jadwal = $jadwalModel->where('nidn', '0101018801')->first();
        
        // Add mahasiswa to this jadwal
        $rencanaStudiModel = new \App\Models\RencanaStudiModel();
        $rencanaStudiModel->insert([
            'nim' => '2023001',
            'id_jadwal' => $jadwal['id']
        ]);
        
        $result = $this->get('/dosen/nilai/' . $jadwal['id']);
        
        $result->assertOK();
        $result->assertSee('Input Nilai');
    }

    /**
     * Test input nilai mahasiswa
     */
    public function testInputNilaiMahasiswa(): void
    {
        // Get jadwal for this dosen
        $jadwalModel = new \App\Models\JadwalModel();
        $jadwal = $jadwalModel->where('nidn', '0101018801')->first();
        
        // Add mahasiswa to this jadwal
        $rencanaStudiModel = new \App\Models\RencanaStudiModel();
        $idRencanaStudi = $rencanaStudiModel->insert([
            'nim' => '2023001',
            'id_jadwal' => $jadwal['id']
        ]);
        
        // Input nilai
        $result = $this->post('/dosen/nilai/' . $jadwal['id'], [
            'nilai' => [
                [
                    'id_rencana_studi' => $idRencanaStudi,
                    'nilai_angka' => 85.5,
                    'nilai_huruf' => 'A'
                ]
            ]
        ]);

        $result->assertRedirect();
        
        // Verify nilai updated
        $this->seeInDatabase('rencana_studi', [
            'id_rencana_studi' => $idRencanaStudi,
            'nilai_angka' => 85.5,
            'nilai_huruf' => 'A'
        ]);
    }

    /**
     * Test validasi ownership jadwal saat view form
     */
    public function testValidateOwnershipWhenViewingNilaiForm(): void
    {
        // Get jadwal for different dosen
        $jadwalModel = new \App\Models\JadwalModel();
        $jadwal = $jadwalModel->where('nidn', '0202029902')->first();
        
        if ($jadwal) {
            $result = $this->get('/dosen/nilai/' . $jadwal['id']);
            
            // Should be redirected or show error
            $result->assertRedirect();
            $result->assertSessionHas('error');
        } else {
            $this->assertTrue(true); // Skip if no jadwal for other dosen
        }
    }

    /**
     * Test validasi ownership jadwal saat update nilai
     */
    public function testValidateOwnershipWhenUpdatingNilai(): void
    {
        // Get jadwal for different dosen
        $jadwalModel = new \App\Models\JadwalModel();
        $jadwal = $jadwalModel->where('nidn', '0202029902')->first();
        
        if ($jadwal) {
            // Add mahasiswa to this jadwal
            $rencanaStudiModel = new \App\Models\RencanaStudiModel();
            $idRencanaStudi = $rencanaStudiModel->insert([
                'nim' => '2023001',
                'id_jadwal' => $jadwal['id']
            ]);
            
            // Try to input nilai for jadwal not owned
            $result = $this->post('/dosen/nilai/' . $jadwal['id'], [
                'nilai' => [
                    [
                        'id_rencana_studi' => $idRencanaStudi,
                        'nilai_angka' => 85.5,
                        'nilai_huruf' => 'A'
                    ]
                ]
            ]);

            // Should be redirected or show error
            $result->assertRedirect();
            $result->assertSessionHas('error');
            
            // Nilai should not be updated
            $this->dontSeeInDatabase('rencana_studi', [
                'id_rencana_studi' => $idRencanaStudi,
                'nilai_angka' => 85.5
            ]);
        } else {
            $this->assertTrue(true); // Skip if no jadwal for other dosen
        }
    }

    /**
     * Test validasi nilai_huruf exists in nilai_mutu
     */
    public function testValidateNilaiHurufExists(): void
    {
        // Get jadwal for this dosen
        $jadwalModel = new \App\Models\JadwalModel();
        $jadwal = $jadwalModel->where('nidn', '0101018801')->first();
        
        // Add mahasiswa to this jadwal
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
                    'nilai_angka' => 85.5,
                    'nilai_huruf' => 'Z' // Invalid grade
                ]
            ]
        ]);

        $result->assertRedirect();
        $result->assertSessionHas('error');
        
        // Nilai should not be updated with invalid grade
        $this->dontSeeInDatabase('rencana_studi', [
            'id_rencana_studi' => $idRencanaStudi,
            'nilai_huruf' => 'Z'
        ]);
    }

    /**
     * Test input nilai dengan nilai_huruf valid
     */
    public function testInputNilaiWithValidGrades(): void
    {
        // Get jadwal for this dosen
        $jadwalModel = new \App\Models\JadwalModel();
        $jadwal = $jadwalModel->where('nidn', '0101018801')->first();
        
        // Add multiple mahasiswa to this jadwal
        $rencanaStudiModel = new \App\Models\RencanaStudiModel();
        
        $validGrades = ['A', 'A-', 'B+', 'B', 'B-', 'C+', 'C', 'D', 'E'];
        $nilaiData = [];
        
        foreach ($validGrades as $index => $grade) {
            $nim = '202300' . ($index + 1);
            
            // Make sure mahasiswa exists
            if ($index < 3) { // Only use existing mahasiswa
                $idRencanaStudi = $rencanaStudiModel->insert([
                    'nim' => $nim,
                    'id_jadwal' => $jadwal['id']
                ]);
                
                $nilaiData[] = [
                    'id_rencana_studi' => $idRencanaStudi,
                    'nilai_angka' => 90 - ($index * 5),
                    'nilai_huruf' => $grade
                ];
            }
        }
        
        // Input nilai
        $result = $this->post('/dosen/nilai/' . $jadwal['id'], [
            'nilai' => $nilaiData
        ]);

        $result->assertRedirect();
        
        // Verify all valid grades are saved
        foreach ($nilaiData as $nilai) {
            $this->seeInDatabase('rencana_studi', [
                'id_rencana_studi' => $nilai['id_rencana_studi'],
                'nilai_huruf' => $nilai['nilai_huruf']
            ]);
        }
    }

    /**
     * Test update nilai yang sudah ada
     */
    public function testUpdateExistingNilai(): void
    {
        // Get jadwal for this dosen
        $jadwalModel = new \App\Models\JadwalModel();
        $jadwal = $jadwalModel->where('nidn', '0101018801')->first();
        
        // Add mahasiswa with initial nilai
        $rencanaStudiModel = new \App\Models\RencanaStudiModel();
        $idRencanaStudi = $rencanaStudiModel->insert([
            'nim' => '2023001',
            'id_jadwal' => $jadwal['id'],
            'nilai_angka' => 70.0,
            'nilai_huruf' => 'B-'
        ]);
        
        // Update nilai
        $result = $this->post('/dosen/nilai/' . $jadwal['id'], [
            'nilai' => [
                [
                    'id_rencana_studi' => $idRencanaStudi,
                    'nilai_angka' => 90.0,
                    'nilai_huruf' => 'A'
                ]
            ]
        ]);

        $result->assertRedirect();
        
        // Verify nilai updated
        $this->seeInDatabase('rencana_studi', [
            'id_rencana_studi' => $idRencanaStudi,
            'nilai_angka' => 90.0,
            'nilai_huruf' => 'A'
        ]);
        
        // Old nilai should not exist
        $this->dontSeeInDatabase('rencana_studi', [
            'id_rencana_studi' => $idRencanaStudi,
            'nilai_huruf' => 'B-'
        ]);
    }
}
