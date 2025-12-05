<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Sistem Perkuliahan' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <!-- Navbar -->
    <nav class="bg-blue-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <span class="text-xl font-bold">Sistem Perkuliahan</span>
                </div>
                
                <!-- Menu Navigation -->
                <div class="hidden md:flex space-x-4">
                    <?php if (session()->get('role') === 'admin'): ?>
                        <a href="<?= base_url('admin/mahasiswa') ?>" class="hover:bg-blue-700 px-3 py-2 rounded">Mahasiswa</a>
                        <a href="<?= base_url('admin/dosen') ?>" class="hover:bg-blue-700 px-3 py-2 rounded">Dosen</a>
                        <a href="<?= base_url('admin/mata-kuliah') ?>" class="hover:bg-blue-700 px-3 py-2 rounded">Mata Kuliah</a>
                        <a href="<?= base_url('admin/ruangan') ?>" class="hover:bg-blue-700 px-3 py-2 rounded">Ruangan</a>
                        <a href="<?= base_url('admin/jadwal') ?>" class="hover:bg-blue-700 px-3 py-2 rounded">Jadwal</a>
                    <?php elseif (session()->get('role') === 'mahasiswa'): ?>
                        <a href="<?= base_url('mhs/krs') ?>" class="hover:bg-blue-700 px-3 py-2 rounded">KRS</a>
                        <a href="<?= base_url('mhs/hasil-studi') ?>" class="hover:bg-blue-700 px-3 py-2 rounded">Hasil Studi</a>
                    <?php elseif (session()->get('role') === 'dosen'): ?>
                        <a href="<?= base_url('dosen/jadwal') ?>" class="hover:bg-blue-700 px-3 py-2 rounded">Jadwal Mengajar</a>
                    <?php endif; ?>
                </div>

                <!-- User Info & Logout -->
                <div class="flex items-center space-x-4">
                    <span class="text-sm"><?= esc(session()->get('nama_user')) ?></span>
                    <a href="<?= base_url('logout') ?>" class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded text-sm">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline"><?= session()->getFlashdata('success') ?></span>
            </div>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline"><?= session()->getFlashdata('error') ?></span>
            </div>
        </div>
    <?php endif; ?>

    <!-- Main Content -->
    <main class="flex-grow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <?= $this->renderSection('content') ?>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-4 mt-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-sm">&copy; <?= date('Y') ?> Sistem Perkuliahan. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
