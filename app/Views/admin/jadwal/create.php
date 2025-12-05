<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">Tambah Jadwal</h1>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <form action="<?= base_url('admin/jadwal') ?>" method="POST">
        <?= csrf_field() ?>
        
        <div class="mb-4">
            <label for="nama_kelas" class="block text-gray-700 text-sm font-bold mb-2">Nama Kelas</label>
            <input type="text" 
                   id="nama_kelas" 
                   name="nama_kelas" 
                   value="<?= old('nama_kelas') ?>"
                   class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 <?= isset($validation) && $validation->hasError('nama_kelas') ? 'border-red-500' : '' ?>"
                   required>
            <?php if (isset($validation) && $validation->hasError('nama_kelas')): ?>
                <p class="text-red-500 text-xs mt-1"><?= $validation->getError('nama_kelas') ?></p>
            <?php endif; ?>
        </div>

        <div class="mb-4">
            <label for="id_mata_kuliah" class="block text-gray-700 text-sm font-bold mb-2">Mata Kuliah</label>
            <select id="id_mata_kuliah" 
                    name="id_mata_kuliah" 
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 <?= isset($validation) && $validation->hasError('id_mata_kuliah') ? 'border-red-500' : '' ?>"
                    required>
                <option value="">Pilih Mata Kuliah</option>
                <?php foreach ($mataKuliah as $mk): ?>
                    <option value="<?= $mk['id_mata_kuliah'] ?>" <?= old('id_mata_kuliah') == $mk['id_mata_kuliah'] ? 'selected' : '' ?>>
                        <?= esc($mk['kode_mata_kuliah']) ?> - <?= esc($mk['nama_mata_kuliah']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($validation) && $validation->hasError('id_mata_kuliah')): ?>
                <p class="text-red-500 text-xs mt-1"><?= $validation->getError('id_mata_kuliah') ?></p>
            <?php endif; ?>
        </div>

        <div class="mb-4">
            <label for="nidn" class="block text-gray-700 text-sm font-bold mb-2">Dosen</label>
            <select id="nidn" 
                    name="nidn" 
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 <?= isset($validation) && $validation->hasError('nidn') ? 'border-red-500' : '' ?>"
                    required>
                <option value="">Pilih Dosen</option>
                <?php foreach ($dosen as $d): ?>
                    <option value="<?= $d['nidn'] ?>" <?= old('nidn') == $d['nidn'] ? 'selected' : '' ?>>
                        <?= esc($d['nidn']) ?> - <?= esc($d['nama']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($validation) && $validation->hasError('nidn')): ?>
                <p class="text-red-500 text-xs mt-1"><?= $validation->getError('nidn') ?></p>
            <?php endif; ?>
        </div>

        <div class="mb-4">
            <label for="id_ruangan" class="block text-gray-700 text-sm font-bold mb-2">Ruangan</label>
            <select id="id_ruangan" 
                    name="id_ruangan" 
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 <?= isset($validation) && $validation->hasError('id_ruangan') ? 'border-red-500' : '' ?>"
                    required>
                <option value="">Pilih Ruangan</option>
                <?php foreach ($ruangan as $r): ?>
                    <option value="<?= $r['id_ruangan'] ?>" <?= old('id_ruangan') == $r['id_ruangan'] ? 'selected' : '' ?>>
                        <?= esc($r['nama_ruangan']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($validation) && $validation->hasError('id_ruangan')): ?>
                <p class="text-red-500 text-xs mt-1"><?= $validation->getError('id_ruangan') ?></p>
            <?php endif; ?>
        </div>

        <div class="mb-4">
            <label for="hari" class="block text-gray-700 text-sm font-bold mb-2">Hari</label>
            <select id="hari" 
                    name="hari" 
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 <?= isset($validation) && $validation->hasError('hari') ? 'border-red-500' : '' ?>"
                    required>
                <option value="">Pilih Hari</option>
                <option value="Senin" <?= old('hari') == 'Senin' ? 'selected' : '' ?>>Senin</option>
                <option value="Selasa" <?= old('hari') == 'Selasa' ? 'selected' : '' ?>>Selasa</option>
                <option value="Rabu" <?= old('hari') == 'Rabu' ? 'selected' : '' ?>>Rabu</option>
                <option value="Kamis" <?= old('hari') == 'Kamis' ? 'selected' : '' ?>>Kamis</option>
                <option value="Jumat" <?= old('hari') == 'Jumat' ? 'selected' : '' ?>>Jumat</option>
                <option value="Sabtu" <?= old('hari') == 'Sabtu' ? 'selected' : '' ?>>Sabtu</option>
            </select>
            <?php if (isset($validation) && $validation->hasError('hari')): ?>
                <p class="text-red-500 text-xs mt-1"><?= $validation->getError('hari') ?></p>
            <?php endif; ?>
        </div>

        <div class="mb-4">
            <label for="jam" class="block text-gray-700 text-sm font-bold mb-2">Jam</label>
            <input type="time" 
                   id="jam" 
                   name="jam" 
                   value="<?= old('jam') ?>"
                   class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 <?= isset($validation) && $validation->hasError('jam') ? 'border-red-500' : '' ?>"
                   required>
            <?php if (isset($validation) && $validation->hasError('jam')): ?>
                <p class="text-red-500 text-xs mt-1"><?= $validation->getError('jam') ?></p>
            <?php endif; ?>
        </div>

        <div class="mb-6">
            <label for="semester" class="block text-gray-700 text-sm font-bold mb-2">Semester</label>
            <input type="number" 
                   id="semester" 
                   name="semester" 
                   value="<?= old('semester') ?>"
                   min="1"
                   max="14"
                   class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 <?= isset($validation) && $validation->hasError('semester') ? 'border-red-500' : '' ?>">
            <?php if (isset($validation) && $validation->hasError('semester')): ?>
                <p class="text-red-500 text-xs mt-1"><?= $validation->getError('semester') ?></p>
            <?php endif; ?>
        </div>

        <div class="flex space-x-2">
            <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Simpan
            </button>
            <a href="<?= base_url('admin/jadwal') ?>" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                Batal
            </a>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
