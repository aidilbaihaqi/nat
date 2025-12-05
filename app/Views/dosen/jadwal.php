<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<h1 class="text-3xl font-bold text-gray-900 mb-6">Jadwal Mengajar</h1>

<div class="bg-white rounded-lg shadow overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kelas</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Kuliah</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKS</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ruangan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hari</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Semester</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php if (empty($jadwal)): ?>
                <tr>
                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">Tidak ada jadwal mengajar</td>
                </tr>
            <?php else: ?>
                <?php foreach ($jadwal as $j): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($j['nama_kelas']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($j['nama_mata_kuliah']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($j['sks']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($j['nama_ruangan']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($j['hari']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($j['jam']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($j['semester'] ?? '-') ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="<?= base_url('dosen/nilai/' . $j['id']) ?>" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs">
                                Input Nilai
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
