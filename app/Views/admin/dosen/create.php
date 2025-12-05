<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">Tambah Dosen</h1>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <form action="<?= base_url('admin/dosen') ?>" method="POST">
        <?= csrf_field() ?>
        
        <div class="mb-4">
            <label for="nidn" class="block text-gray-700 text-sm font-bold mb-2">NIDN</label>
            <input type="text" 
                   id="nidn" 
                   name="nidn" 
                   value="<?= old('nidn') ?>"
                   class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 <?= isset($validation) && $validation->hasError('nidn') ? 'border-red-500' : '' ?>"
                   required>
            <?php if (isset($validation) && $validation->hasError('nidn')): ?>
                <p class="text-red-500 text-xs mt-1"><?= $validation->getError('nidn') ?></p>
            <?php endif; ?>
        </div>

        <div class="mb-4">
            <label for="nama" class="block text-gray-700 text-sm font-bold mb-2">Nama</label>
            <input type="text" 
                   id="nama" 
                   name="nama" 
                   value="<?= old('nama') ?>"
                   class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 <?= isset($validation) && $validation->hasError('nama') ? 'border-red-500' : '' ?>"
                   required>
            <?php if (isset($validation) && $validation->hasError('nama')): ?>
                <p class="text-red-500 text-xs mt-1"><?= $validation->getError('nama') ?></p>
            <?php endif; ?>
        </div>

        <div class="mb-4">
            <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Username</label>
            <input type="text" 
                   id="username" 
                   name="username" 
                   value="<?= old('username') ?>"
                   class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 <?= isset($validation) && $validation->hasError('username') ? 'border-red-500' : '' ?>"
                   required>
            <?php if (isset($validation) && $validation->hasError('username')): ?>
                <p class="text-red-500 text-xs mt-1"><?= $validation->getError('username') ?></p>
            <?php endif; ?>
        </div>

        <div class="mb-6">
            <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
            <input type="password" 
                   id="password" 
                   name="password" 
                   class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 <?= isset($validation) && $validation->hasError('password') ? 'border-red-500' : '' ?>"
                   required>
            <?php if (isset($validation) && $validation->hasError('password')): ?>
                <p class="text-red-500 text-xs mt-1"><?= $validation->getError('password') ?></p>
            <?php endif; ?>
        </div>

        <div class="flex space-x-2">
            <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Simpan
            </button>
            <a href="<?= base_url('admin/dosen') ?>" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                Batal
            </a>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
