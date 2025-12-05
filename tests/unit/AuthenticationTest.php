<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * @internal
 */
final class AuthenticationTest extends CIUnitTestCase
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
    }

    /**
     * Test login dengan kredensial valid
     */
    public function testLoginWithValidCredentials(): void
    {
        $result = $this->post('/login', [
            'username' => 'admin',
            'password' => 'admin123'
        ]);

        $result->assertRedirectTo('/admin/mahasiswa');
        $this->assertTrue(session()->has('user_id'));
        $this->assertEquals('admin', session()->get('role'));
    }

    /**
     * Test login mahasiswa dengan kredensial valid
     */
    public function testLoginMahasiswaWithValidCredentials(): void
    {
        $result = $this->post('/login', [
            'username' => 'mahasiswa1',
            'password' => 'mahasiswa123'
        ]);

        $result->assertRedirectTo('/mhs/krs');
        $this->assertTrue(session()->has('user_id'));
        $this->assertTrue(session()->has('nim'));
        $this->assertEquals('mahasiswa', session()->get('role'));
    }

    /**
     * Test login dosen dengan kredensial valid
     */
    public function testLoginDosenWithValidCredentials(): void
    {
        $result = $this->post('/login', [
            'username' => 'dosen1',
            'password' => 'dosen123'
        ]);

        $result->assertRedirectTo('/dosen/jadwal');
        $this->assertTrue(session()->has('user_id'));
        $this->assertTrue(session()->has('nidn'));
        $this->assertEquals('dosen', session()->get('role'));
    }

    /**
     * Test login dengan username invalid
     */
    public function testLoginWithInvalidUsername(): void
    {
        $result = $this->post('/login', [
            'username' => 'invaliduser',
            'password' => 'password123'
        ]);

        $result->assertRedirect();
        $result->assertSessionHas('error', 'Username atau password salah');
        $this->assertFalse(session()->has('user_id'));
    }

    /**
     * Test login dengan password invalid
     */
    public function testLoginWithInvalidPassword(): void
    {
        $result = $this->post('/login', [
            'username' => 'admin',
            'password' => 'wrongpassword'
        ]);

        $result->assertRedirect();
        $result->assertSessionHas('error', 'Username atau password salah');
        $this->assertFalse(session()->has('user_id'));
    }

    /**
     * Test login dengan field kosong
     */
    public function testLoginWithEmptyFields(): void
    {
        $result = $this->post('/login', [
            'username' => '',
            'password' => ''
        ]);

        $result->assertRedirect();
        $result->assertSessionHas('errors');
    }

    /**
     * Test logout
     */
    public function testLogout(): void
    {
        // Login terlebih dahulu
        $_SESSION['user_id'] = 1;
        $_SESSION['role'] = 'admin';
        $_SESSION['nama_user'] = 'Admin';

        $result = $this->get('/logout');

        $result->assertRedirectTo('/login');
        $result->assertSessionHas('success', 'Anda telah logout');
        $this->assertFalse(session()->has('user_id'));
    }

    /**
     * Test akses halaman protected tanpa login
     */
    public function testAccessProtectedPageWithoutLogin(): void
    {
        $result = $this->get('/admin/mahasiswa');
        
        $result->assertRedirectTo('/login');
    }

    /**
     * Test akses halaman mahasiswa tanpa login
     */
    public function testAccessMahasiswaPageWithoutLogin(): void
    {
        $result = $this->get('/mhs/krs');
        
        $result->assertRedirectTo('/login');
    }

    /**
     * Test akses halaman dosen tanpa login
     */
    public function testAccessDosenPageWithoutLogin(): void
    {
        $result = $this->get('/dosen/jadwal');
        
        $result->assertRedirectTo('/login');
    }

    /**
     * Test redirect ke dashboard jika sudah login
     */
    public function testRedirectToDashboardWhenAlreadyLoggedIn(): void
    {
        $_SESSION['user_id'] = 1;
        $_SESSION['role'] = 'admin';
        $_SESSION['nama_user'] = 'Admin';

        $result = $this->get('/login');
        
        $result->assertRedirectTo('/admin/mahasiswa');
    }
}
