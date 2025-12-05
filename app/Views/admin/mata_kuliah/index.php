<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-3xl font-bold text-gray-900">Data Mata Kuliah</h1>
    <a href="<?= base_url('admin/mata-kuliah/new') ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
        Tambah Mata Kuliah
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Mata Kuliah</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKS</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php if (empty($mataKuliah)): ?>
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">Tidak ada data mata kuliah</td>
                </tr>
            <?php else: ?>
                <?php foreach ($mataKuliah as $mk): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($mk['kode_mata_kuliah']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($mk['nama_mata_kuliah']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($mk['sks']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                            <a href="<?= base_url('admin/mata-kuliah/' . $mk['id_mata_kuliah'] . '/edit') ?>" 
                               class="text-blue-600 hover:text-blue-900">Edit</a>
                            <form action="<?= base_url('admin/mata-kuliah/' . $mk['id_mata_kuliah']) ?>" method="POST" class="inline">
                                <?= csrf_field() ?>
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" 
                                        onclick="return confirm('Yakin ingin menghapus mata kuliah ini?')"
                                        class="text-red-600 hover:text-red-900">Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
