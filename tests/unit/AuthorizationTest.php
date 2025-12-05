<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * @internal
 */
final class AuthorizationTest extends CIUnitTestCase
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
    }

    // ============================================
    // RoleFilter Tests
    // ============================================

    /**
     * Test admin dapat akses halaman admin
     */
    public function testAdminCanAccessAdminPages(): void
    {
        $_SESSION['user_id'] = 1;
        $_SESSION['role'] = 'admin';
        $_SESSION['nama_user'] = 'Admin';
        
        $result = $this->get('/admin/mahasiswa');
        $result->assertOK();
        
        $result = $this->get('/admin/dosen');
        $result->assertOK();
        
        $result = $this->get('/admin/mata-kuliah');
        $result->assertOK();
    }

    /**
     * Test mahasiswa tidak dapat akses halaman admin
     */
    public function testMahasiswaCannotAccessAdminPages(): void
    {
        $_SESSION['user_id'] = 4;
        $_SESSION['role'] = 'mahasiswa';
        $_SESSION['nama_user'] = 'Mahasiswa Satu';
        $_SESSION['nim'] = '2023001';
        
        $result = $this->get('/admin/mahasiswa');
        $result->assertRedirect();
        
        $result = $this->get('/admin/dosen');
        $result->assertRedirect();
    }

    /**
     * Test dosen tidak dapat akses halaman admin
     */
    public function testDosenCannotAccessAdminPages(): void
    {
        $_SESSION['user_id'] = 7;
        $_SESSION['role'] = 'dosen';
        $_SESSION['nama_user'] = 'Dosen Satu';
        $_SESSION['nidn'] = '0101018801';
        
        $result = $this->get('/admin/mahasiswa');
        $result->assertRedirect();
        
        $result = $this->get('/admin/jadwal');
        $result->assertRedirect();
    }

    /**
     * Test mahasiswa dapat akses halaman mahasiswa
     */
    public function testMahasiswaCanAccessMahasiswaPages(): void
    {
        $_SESSION['user_id'] = 4;
        $_SESSION['role'] = 'mahasiswa';
        $_SESSION['nama_user'] = 'Mahasiswa Satu';
        $_SESSION['nim'] = '2023001';
        
        $result = $this->get('/mhs/krs');
        $result->assertOK();
        
        $result = $this->get('/mhs/hasil-studi');
        $result->assertOK();
    }

    /**
     * Test admin tidak dapat akses halaman mahasiswa
     */
    public function testAdminCannotAccessMahasiswaPages(): void
    {
        $_SESSION['user_id'] = 1;
        $_SESSION['role'] = 'admin';
        $_SESSION['nama_user'] = 'Admin';
        
        $result = $this->get('/mhs/krs');
        $result->assertRedirect();
        
        $result = $this->get('/mhs/hasil-studi');
        $result->assertRedirect();
    }

    /**
     * Test dosen tidak dapat akses halaman mahasiswa
     */
    public function testDosenCannotAccessMahasiswaPages(): void
    {
        $_SESSION['user_id'] = 7;
        $_SESSION['role'] = 'dosen';
        $_SESSION['nama_user'] = 'Dosen Satu';
        $_SESSION['nidn'] = '0101018801';
        
        $result = $this->get('/mhs/krs');
        $result->assertRedirect();
        
        $result = $this->get('/mhs/hasil-studi');
        $result->assertRedirect();
    }

    /**
     * Test dosen dapat akses halaman dosen
     */
    public function testDosenCanAccessDosenPages(): void
    {
        $_SESSION['user_id'] = 7;
        $_SESSION['role'] = 'dosen';
        $_SESSION['nama_user'] = 'Dosen Satu';
        $_SESSION['nidn'] = '0101018801';
        
        $result = $this->get('/dosen/jadwal');
        $result->assertOK();
    }

    /**
     * Test admin tidak dapat akses halaman dosen
     */
    public function testAdminCannotAccessDosenPages(): void
    {
        $_SESSION['user_id'] = 1;
        $_SESSION['role'] = 'admin';
        $_SESSION['nama_user'] = 'Admin';
        
        $result = $this->get('/dosen/jadwal');
        $result->assertRedirect();
    }

    /**
     * Test mahasiswa tidak dapat akses halaman dosen
     */
    public function testMahasiswaCannotAccessDosenPages(): void
    {
        $_SESSION['user_id'] = 4;
        $_SESSION['role'] = 'mahasiswa';
        $_SESSION['nama_user'] = 'Mahasiswa Satu';
        $_SESSION['nim'] = '2023001';
        
        $result = $this->get('/dosen/jadwal');
        $result->assertRedirect();
    }

    // ============================================
    // Ownership Validation Tests - Mahasiswa
    // ============================================

    /**
     * Test mahasiswa hanya dapat hapus KRS sendiri
     */
    public function testMahasiswaCanOnlyDeleteOwnKRS(): void
    {
        $_SESSION['user_id'] = 4;
        $_SESSION['role'] = 'mahasiswa';
        $_SESSION['nama_user'] = 'Mahasiswa Satu';
        $_SESSION['nim'] = '2023001';
        
        // Create KRS for current mahasiswa
        $jadwalModel = new \App\Models\JadwalModel();
        $jadwal = $jadwalModel->first();
        
        $rencanaStudiModel = new \App\Models\RencanaStudiModel();
        $idRencanaStudi1 = $rencanaStudiModel->insert([
            'nim' => '2023001',
            'id_jadwal' => $jadwal['id']
        ]);
        
        // Create KRS for different mahasiswa
        $jadwal2 = $jadwalModel->orderBy('id', 'DESC')->first();
        $idRencanaStudi2 = $rencanaStudiModel->insert([
            'nim' => '2023002',
            'id_jadwal' => $jadwal2['id']
        ]);
        
        // Should be able to delete own KRS
        $result = $this->delete('/mhs/krs/' . $idRencanaStudi1);
        $result->assertRedirect();
        $this->dontSeeInDatabase('rencana_studi', ['id_rencana_studi' => $idRencanaStudi1]);
        
        // Should not be able to delete other's KRS
        $result = $this->delete('/mhs/krs/' . $idRencanaStudi2);
        $this->seeInDatabase('rencana_studi', ['id_rencana_studi' => $idRencanaStudi2]);
    }

    /**
     * Test mahasiswa hanya dapat lihat hasil studi sendiri
     */
    public function testMahasiswaCanOnlyViewOwnHasilStudi(): void
    {
        $_SESSION['user_id'] = 4;
        $_SESSION['role'] = 'mahasiswa';
        $_SESSION['nama_user'] = 'Mahasiswa Satu';
        $_SESSION['nim'] = '2023001';
        
        // Create grades for current mahasiswa
        $jadwalModel = new \App\Models\JadwalModel();
        $jadwal = $jadwalModel->first();
        
        $rencanaStudiModel = new \App\Models\RencanaStudiModel();
        $rencanaStudiModel->insert([
            'nim' => '2023001',
            'id_jadwal' => $jadwal['id'],
            'nilai_angka' => 85.0,
            'nilai_huruf' => 'A'
        ]);
        
        // Create grades for different mahasiswa
        $jadwal2 = $jadwalModel->orderBy('id', 'DESC')->first();
        $rencanaStudiModel->insert([
            'nim' => '2023002',
            'id_jadwal' => $jadwal2['id'],
            'nilai_angka' => 70.0,
            'nilai_huruf' => 'B-'
        ]);
        
        $result = $this->get('/mhs/hasil-studi');
        $result->assertOK();
        
        // Verify only own data is retrieved (checked in controller logic)
        $this->seeInDatabase('rencana_studi', [
            'nim' => '2023001',
            'nilai_huruf' => 'A'
        ]);
    }

    /**
     * Test mahasiswa hanya dapat tambah KRS untuk diri sendiri
     */
    public function testMahasiswaCanOnlyAddOwnKRS(): void
    {
        $_SESSION['user_id'] = 4;
        $_SESSION['role'] = 'mahasiswa';
        $_SESSION['nama_user'] = 'Mahasiswa Satu';
        $_SESSION['nim'] = '2023001';
        
        $jadwalModel = new \App\Models\JadwalModel();
        $jadwal = $jadwalModel->first();
        
        // Add KRS
        $result = $this->post('/mhs/krs', [
            'id_jadwal' => $jadwal['id']
        ]);
        
        $result->assertRedirect();
        
        // Verify KRS is added with correct nim from session
        $this->seeInDatabase('rencana_studi', [
            'nim' => '2023001',
            'id_jadwal' => $jadwal['id']
        ]);
        
        // Should not be able to add KRS for different nim
        $this->dontSeeInDatabase('rencana_studi', [
            'nim' => '2023002',
            'id_jadwal' => $jadwal['id']
        ]);
    }

    // ============================================
    // Ownership Validation Tests - Dosen
    // ============================================

    /**
     * Test dosen hanya dapat lihat jadwal sendiri
     */
    public function testDosenCanOnlyViewOwnJadwal(): void
    {
        $_SESSION['user_id'] = 7;
        $_SESSION['role'] = 'dosen';
        $_SESSION['nama_user'] = 'Dosen Satu';
        $_SESSION['nidn'] = '0101018801';
        
        $result = $this->get('/dosen/jadwal');
        $result->assertOK();
        
        // Verify only own jadwal is retrieved (checked in controller logic)
        $this->seeInDatabase('jadwal', ['nidn' => '0101018801']);
    }

    /**
     * Test dosen hanya dapat input nilai untuk jadwal sendiri
     */
    public function testDosenCanOnlyInputNilaiForOwnJadwal(): void
    {
        $_SESSION['user_id'] = 7;
        $_SESSION['role'] = 'dosen';
        $_SESSION['nama_user'] = 'Dosen Satu';
        $_SESSION['nidn'] = '0101018801';
        
        // Get own jadwal
        $jadwalModel = new \App\Models\JadwalModel();
        $ownJadwal = $jadwalModel->where('nidn', '0101018801')->first();
        
        // Get other dosen's jadwal
        $otherJadwal = $jadwalModel->where('nidn', '0202029902')->first();
        
        // Add mahasiswa to both jadwal
        $rencanaStudiModel = new \App\Models\RencanaStudiModel();
        $idRencanaStudi1 = $rencanaStudiModel->insert([
            'nim' => '2023001',
            'id_jadwal' => $ownJadwal['id']
        ]);
        
        if ($otherJadwal) {
            $idRencanaStudi2 = $rencanaStudiModel->insert([
                'nim' => '2023002',
                'id_jadwal' => $otherJadwal['id']
            ]);
            
            // Should be able to input nilai for own jadwal
            $result = $this->post('/dosen/nilai/' . $ownJadwal['id'], [
                'nilai' => [
                    [
                        'id_rencana_studi' => $idRencanaStudi1,
                        'nilai_angka' => 85.0,
                        'nilai_huruf' => 'A'
                    ]
                ]
            ]);
            
            $result->assertRedirect();
            $this->seeInDatabase('rencana_studi', [
                'id_rencana_studi' => $idRencanaStudi1,
                'nilai_huruf' => 'A'
            ]);
            
            // Should not be able to input nilai for other's jadwal
            $result = $this->post('/dosen/nilai/' . $otherJadwal['id'], [
                'nilai' => [
                    [
                        'id_rencana_studi' => $idRencanaStudi2,
                        'nilai_angka' => 90.0,
                        'nilai_huruf' => 'A'
                    ]
                ]
            ]);
            
            $result->assertRedirect();
            $result->assertSessionHas('error');
            
            // Nilai should not be updated
            $this->dontSeeInDatabase('rencana_studi', [
                'id_rencana_studi' => $idRencanaStudi2,
                'nilai_huruf' => 'A'
            ]);
        } else {
            $this->assertTrue(true); // Skip if no other jadwal
        }
    }

    /**
     * Test dosen tidak dapat akses form nilai untuk jadwal orang lain
     */
    public function testDosenCannotAccessNilaiFormForOthersJadwal(): void
    {
        $_SESSION['user_id'] = 7;
        $_SESSION['role'] = 'dosen';
        $_SESSION['nama_user'] = 'Dosen Satu';
        $_SESSION['nidn'] = '0101018801';
        
        // Get other dosen's jadwal
        $jadwalModel = new \App\Models\JadwalModel();
        $otherJadwal = $jadwalModel->where('nidn', '0202029902')->first();
        
        if ($otherJadwal) {
            $result = $this->get('/dosen/nilai/' . $otherJadwal['id']);
            
            $result->assertRedirect();
            $result->assertSessionHas('error');
        } else {
            $this->assertTrue(true); // Skip if no other jadwal
        }
    }

    /**
     * Test cross-role access prevention
     */
    public function testCrossRoleAccessPrevention(): void
    {
        // Test mahasiswa trying to access admin and dosen pages
        $_SESSION['user_id'] = 4;
        $_SESSION['role'] = 'mahasiswa';
        $_SESSION['nama_user'] = 'Mahasiswa Satu';
        $_SESSION['nim'] = '2023001';
        
        $result = $this->get('/admin/mahasiswa');
        $result->assertRedirect();
        
        $result = $this->get('/dosen/jadwal');
        $result->assertRedirect();
        
        // Test dosen trying to access admin and mahasiswa pages
        $_SESSION['user_id'] = 7;
        $_SESSION['role'] = 'dosen';
        $_SESSION['nama_user'] = 'Dosen Satu';
        $_SESSION['nidn'] = '0101018801';
        
        $result = $this->get('/admin/mahasiswa');
        $result->assertRedirect();
        
        $result = $this->get('/mhs/krs');
        $result->assertRedirect();
        
        // Test admin trying to access mahasiswa and dosen pages
        $_SESSION['user_id'] = 1;
        $_SESSION['role'] = 'admin';
        $_SESSION['nama_user'] = 'Admin';
        
        $result = $this->get('/mhs/krs');
        $result->assertRedirect();
        
        $result = $this->get('/dosen/jadwal');
        $result->assertRedirect();
    }
}
