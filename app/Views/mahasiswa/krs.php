<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<h1 class="text-3xl font-bold text-gray-900 mb-6">Kartu Rencana Studi (KRS)</h1>

<!-- Jadwal Available -->
<div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-900 mb-4">Jadwal Tersedia</h2>
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Kuliah</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKS</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dosen</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ruangan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hari</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($jadwalAvailable)): ?>
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">Tidak ada jadwal tersedia</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($jadwalAvailable as $j): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($j['nama_kelas']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($j['nama_mata_kuliah']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($j['sks']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($j['nama_dosen']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($j['nama_ruangan']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($j['hari']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($j['jam']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <form action="<?= base_url('mhs/krs') ?>" method="POST" class="inline">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id_jadwal" value="<?= $j['id'] ?>">
                                    <button type="submit" 
                                            class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs">
                                        Tambah
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- KRS Mahasiswa -->
<div>
    <h2 class="text-2xl font-bold text-gray-900 mb-4">KRS Saya</h2>
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Kuliah</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKS</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dosen</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ruangan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hari</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($krs)): ?>
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">Belum ada KRS</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($krs as $k): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($k['nama_kelas']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($k['nama_mata_kuliah']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($k['sks']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($k['nama_dosen']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($k['nama_ruangan']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($k['hari']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($k['jam']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <form action="<?= base_url('mhs/krs/' . $k['id_rencana_studi']) ?>" method="POST" class="inline">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" 
                                            onclick="return confirm('Yakin ingin menghapus mata kuliah ini dari KRS?')"
                                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <?php if (!empty($krs)): ?>
        <div class="mt-4 bg-blue-50 border border-blue-200 rounded p-4">
            <p class="text-sm text-gray-700">
                <strong>Total SKS:</strong> 
                <?php 
                    $total_sks = 0;
                    foreach ($krs as $k) {
                        $total_sks += $k['sks'];
                    }
                    echo $total_sks;
                ?>
            </p>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
