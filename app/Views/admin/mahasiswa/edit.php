<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">Edit Mahasiswa</h1>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <form action="<?= base_url('admin/mahasiswa/' . $mahasiswa['nim']) ?>" method="POST">
        <?= csrf_field() ?>
        <input type="hidden" name="_method" value="PUT">
        
        <div class="mb-4">
            <label for="nim" class="block text-gray-700 text-sm font-bold mb-2">NIM</label>
            <input type="text" 
                   id="nim" 
                   name="nim" 
                   value="<?= esc($mahasiswa['nim']) ?>"
                   class="w-full px-3 py-2 border border-gray-300 rounded bg-gray-100"
                   readonly>
        </div>

        <div class="mb-4">
            <label for="nama" class="block text-gray-700 text-sm font-bold mb-2">Nama</label>
            <input type="text" 
                   id="nama" 
                   name="nama" 
                   value="<?= old('nama', $mahasiswa['nama']) ?>"
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
                   value="<?= old('username', $mahasiswa['username']) ?>"
                   class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 <?= isset($validation) && $validation->hasError('username') ? 'border-red-500' : '' ?>"
                   required>
            <?php if (isset($validation) && $validation->hasError('username')): ?>
                <p class="text-red-500 text-xs mt-1"><?= $validation->getError('username') ?></p>
            <?php endif; ?>
        </div>

        <div class="mb-4">
            <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password (kosongkan jika tidak ingin mengubah)</label>
            <input type="password" 
                   id="password" 
                   name="password" 
                   class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 <?= isset($validation) && $validation->hasError('password') ? 'border-red-500' : '' ?>">
            <?php if (isset($validation) && $validation->hasError('password')): ?>
                <p class="text-red-500 text-xs mt-1"><?= $validation->getError('password') ?></p>
            <?php endif; ?>
        </div>

        <div class="mb-6">
            <label for="angkatan" class="block text-gray-700 text-sm font-bold mb-2">Angkatan</label>
            <input type="number" 
                   id="angkatan" 
                   name="angkatan" 
                   value="<?= old('angkatan', $mahasiswa['angkatan']) ?>"
                   class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 <?= isset($validation) && $validation->hasError('angkatan') ? 'border-red-500' : '' ?>">
            <?php if (isset($validation) && $validation->hasError('angkatan')): ?>
                <p class="text-red-500 text-xs mt-1"><?= $validation->getError('angkatan') ?></p>
            <?php endif; ?>
        </div>

        <div class="flex space-x-2">
            <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Update
            </button>
            <a href="<?= base_url('admin/mahasiswa') ?>" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                Batal
            </a>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
