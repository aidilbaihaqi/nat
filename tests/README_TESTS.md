# Testing Documentation - Sistem Perkuliahan

## Overview

Comprehensive test suite telah dibuat untuk Sistem Perkuliahan Sederhana, mencakup semua aspek fungsionalitas aplikasi sesuai dengan requirements.

## Test Files Created

### 1. AuthenticationTest.php
**Coverage:** Requirements 1.1, 1.2, 1.3, 1.4, 1.5, 8.1, 8.2, 8.3

Tests meliputi:
- Login dengan kredensial valid (admin, mahasiswa, dosen)
- Login dengan kredensial invalid (username/password salah)
- Login dengan field kosong
- Logout functionality
- Akses halaman protected tanpa login
- Redirect ke dashboard jika sudah login

### 2. AdminCRUDTest.php
**Coverage:** Requirements 2.1, 2.2, 2.3, 2.5, 2.6, 2.7

Tests meliputi:
- CRUD operations untuk Mahasiswa (create, read, update, delete)
- CRUD operations untuk Dosen
- CRUD operations untuk Mata Kuliah
- CRUD operations untuk Ruangan
- CRUD operations untuk Jadwal

### 3. MahasiswaFeaturesTest.php
**Coverage:** Requirements 3.1, 3.2, 3.3, 3.4, 3.5, 5.1, 5.2, 5.3, 5.4, 5.5, 5.6

Tests meliputi:
- Tampilan halaman KRS
- Tambah jadwal ke KRS
- Validasi duplicate KRS
- Hapus jadwal dari KRS
- Tampilan hasil studi
- Perhitungan IPK
- Ownership validation (mahasiswa hanya akses data sendiri)

### 4. DosenFeaturesTest.php
**Coverage:** Requirements 4.1, 4.2, 4.3, 4.4, 4.5

Tests meliputi:
- Tampilan jadwal dosen
- Input nilai mahasiswa
- Validasi ownership jadwal
- Validasi nilai_huruf exists in nilai_mutu
- Input nilai dengan berbagai grade valid
- Update nilai yang sudah ada

### 5. AuthorizationTest.php
**Coverage:** Requirements 6.1, 6.2, 6.3, 6.4, 6.5

Tests meliputi:
- RoleFilter untuk setiap role (admin, mahasiswa, dosen)
- Cross-role access prevention
- Ownership validation untuk mahasiswa (KRS, hasil studi)
- Ownership validation untuk dosen (jadwal, input nilai)

### 6. DataValidationTest.php
**Coverage:** Requirements 7.1, 7.2, 7.3, 7.4, 7.5, 7.6

Tests meliputi:
- Unique constraints (username, kode_mata_kuliah, nama_ruangan)
- Unique constraint untuk rencana_studi (nim, id_jadwal)
- Foreign key constraints untuk semua relasi
- Cascade delete operations
- Validasi nilai_huruf exists in nilai_mutu

## Running Tests

### Prerequisites

Untuk menjalankan tests, Anda perlu:

1. **Install SQLite3 PHP Extension** (untuk testing database)
   ```bash
   # Windows (via XAMPP/PHP)
   # Uncomment extension=sqlite3 di php.ini
   
   # Linux
   sudo apt-get install php-sqlite3
   
   # Mac
   brew install php@8.1
   ```

2. **Configure Test Database**
   Edit `phpunit.xml.dist` untuk menggunakan database testing:
   ```xml
   <env name="database.tests.hostname" value="localhost"/>
   <env name="database.tests.database" value="tests"/>
   <env name="database.tests.username" value="root"/>
   <env name="database.tests.password" value=""/>
   <env name="database.tests.DBDriver" value="MySQLi"/>
   ```

### Run All Tests

```bash
vendor/bin/phpunit
```

### Run Specific Test File

```bash
vendor/bin/phpunit tests/unit/AuthenticationTest.php
vendor/bin/phpunit tests/unit/AdminCRUDTest.php
vendor/bin/phpunit tests/unit/MahasiswaFeaturesTest.php
vendor/bin/phpunit tests/unit/DosenFeaturesTest.php
vendor/bin/phpunit tests/unit/AuthorizationTest.php
vendor/bin/phpunit tests/unit/DataValidationTest.php
```

### Run with Test Documentation

```bash
vendor/bin/phpunit --testdox
```

## Test Structure

Semua test files menggunakan:
- `DatabaseTestTrait` - untuk database operations
- `FeatureTestTrait` - untuk HTTP request testing
- `migrate = true` - untuk menjalankan migrations sebelum test
- `refresh = true` - untuk refresh database setiap test

Setiap test file melakukan seeding data yang diperlukan di `setUp()` method.

## Test Coverage

Total: **85 test cases** covering:
- ✅ Authentication flow (12 tests)
- ✅ Admin CRUD operations (20 tests)
- ✅ Mahasiswa features (8 tests)
- ✅ Dosen features (9 tests)
- ✅ Authorization & access control (17 tests)
- ✅ Data validation (19 tests)

## Notes

1. Tests menggunakan session simulation untuk testing role-based access
2. Tests memverifikasi database state menggunakan `seeInDatabase()` dan `dontSeeInDatabase()`
3. Tests mencakup positive dan negative test cases
4. Foreign key dan cascade delete operations ditest dengan exception handling
5. Ownership validation ditest untuk memastikan users hanya dapat akses data mereka sendiri

## Troubleshooting

### SQLite3 Extension Not Loaded

Jika mendapat error "sqlite3 extension not loaded":
1. Buka `php.ini`
2. Uncomment `extension=sqlite3`
3. Restart web server

### Database Connection Issues

Jika tests gagal connect ke database:
1. Pastikan database test sudah dibuat
2. Verify credentials di `phpunit.xml.dist`
3. Pastikan MySQL service running

### Migration Errors

Jika migrations gagal:
1. Run migrations manual: `php spark migrate`
2. Check migration files di `app/Database/Migrations/`
3. Verify database permissions

## Next Steps

Setelah SQLite3 extension terinstall atau database test dikonfigurasi:
1. Run tests untuk verify semua functionality
2. Fix any failing tests
3. Add additional edge case tests jika diperlukan
4. Integrate tests ke CI/CD pipeline
