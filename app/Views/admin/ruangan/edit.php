<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">Edit Ruangan</h1>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <form action="<?= base_url('admin/ruangan/' . $ruangan['id_ruangan']) ?>" method="POST">
        <?= csrf_field() ?>
        <input type="hidden" name="_method" value="PUT">
        
        <div class="mb-6">
            <label for="nama_ruangan" class="block text-gray-700 text-sm font-bold mb-2">Nama Ruangan</label>
            <input type="text" 
                   id="nama_ruangan" 
                   name="nama_ruangan" 
                   value="<?= old('nama_ruangan', $ruangan['nama_ruangan']) ?>"
                   class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 <?= isset($validation) && $validation->hasError('nama_ruangan') ? 'border-red-500' : '' ?>"
                   required>
            <?php if (isset($validation) && $validation->hasError('nama_ruangan')): ?>
                <p class="text-red-500 text-xs mt-1"><?= $validation->getError('nama_ruangan') ?></p>
            <?php endif; ?>
        </div>

        <div class="flex space-x-2">
            <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Update
            </button>
            <a href="<?= base_url('admin/ruangan') ?>" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                Batal
            </a>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
