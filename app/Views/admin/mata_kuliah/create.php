<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">Tambah Mata Kuliah</h1>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <form action="<?= base_url('admin/mata-kuliah') ?>" method="POST">
        <?= csrf_field() ?>
        
        <div class="mb-4">
            <label for="kode_mata_kuliah" class="block text-gray-700 text-sm font-bold mb-2">Kode Mata Kuliah</label>
            <input type="text" 
                   id="kode_mata_kuliah" 
                   name="kode_mata_kuliah" 
                   value="<?= old('kode_mata_kuliah') ?>"
                   class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 <?= isset($validation) && $validation->hasError('kode_mata_kuliah') ? 'border-red-500' : '' ?>"
                   required>
            <?php if (isset($validation) && $validation->hasError('kode_mata_kuliah')): ?>
                <p class="text-red-500 text-xs mt-1"><?= $validation->getError('kode_mata_kuliah') ?></p>
            <?php endif; ?>
        </div>

        <div class="mb-4">
            <label for="nama_mata_kuliah" class="block text-gray-700 text-sm font-bold mb-2">Nama Mata Kuliah</label>
            <input type="text" 
                   id="nama_mata_kuliah" 
                   name="nama_mata_kuliah" 
                   value="<?= old('nama_mata_kuliah') ?>"
                   class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 <?= isset($validation) && $validation->hasError('nama_mata_kuliah') ? 'border-red-500' : '' ?>"
                   required>
            <?php if (isset($validation) && $validation->hasError('nama_mata_kuliah')): ?>
                <p class="text-red-500 text-xs mt-1"><?= $validation->getError('nama_mata_kuliah') ?></p>
            <?php endif; ?>
        </div>

        <div class="mb-6">
            <label for="sks" class="block text-gray-700 text-sm font-bold mb-2">SKS</label>
            <input type="number" 
                   id="sks" 
                   name="sks" 
                   value="<?= old('sks') ?>"
                   min="1"
                   max="6"
                   class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 <?= isset($validation) && $validation->hasError('sks') ? 'border-red-500' : '' ?>"
                   required>
            <?php if (isset($validation) && $validation->hasError('sks')): ?>
                <p class="text-red-500 text-xs mt-1"><?= $validation->getError('sks') ?></p>
            <?php endif; ?>
        </div>

        <div class="flex space-x-2">
            <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Simpan
            </button>
            <a href="<?= base_url('admin/mata-kuliah') ?>" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                Batal
            </a>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
