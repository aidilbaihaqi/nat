<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">Input Nilai</h1>
    <p class="text-gray-600 mt-2">
        <strong>Kelas:</strong> <?= esc($jadwal['nama_kelas']) ?> | 
        <strong>Mata Kuliah:</strong> <?= esc($jadwal['nama_mata_kuliah']) ?> | 
        <strong>Hari:</strong> <?= esc($jadwal['hari']) ?> | 
        <strong>Jam:</strong> <?= esc($jadwal['jam']) ?>
    </p>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <form action="<?= base_url('dosen/nilai/' . $jadwal['id']) ?>" method="POST">
        <?= csrf_field() ?>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIM</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Mahasiswa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai Angka</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai Huruf</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (empty($mahasiswaList)): ?>
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">Tidak ada mahasiswa yang mengambil kelas ini</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($mahasiswaList as $index => $mhs): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= esc($mhs['nim']) ?>
                                    <input type="hidden" name="nilai[<?= $index ?>][id_rencana_studi]" value="<?= $mhs['id_rencana_studi'] ?>">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($mhs['nama']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="number" 
                                           name="nilai[<?= $index ?>][nilai_angka]" 
                                           value="<?= esc($mhs['nilai_angka']) ?>"
                                           step="0.1"
                                           min="0"
                                           max="100"
                                           class="w-24 px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <select name="nilai[<?= $index ?>][nilai_huruf]" 
                                            class="w-24 px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">-</option>
                                        <option value="A" <?= $mhs['nilai_huruf'] == 'A' ? 'selected' : '' ?>>A</option>
                                        <option value="A-" <?= $mhs['nilai_huruf'] == 'A-' ? 'selected' : '' ?>>A-</option>
                                        <option value="B+" <?= $mhs['nilai_huruf'] == 'B+' ? 'selected' : '' ?>>B+</option>
                                        <option value="B" <?= $mhs['nilai_huruf'] == 'B' ? 'selected' : '' ?>>B</option>
                                        <option value="B-" <?= $mhs['nilai_huruf'] == 'B-' ? 'selected' : '' ?>>B-</option>
                                        <option value="C+" <?= $mhs['nilai_huruf'] == 'C+' ? 'selected' : '' ?>>C+</option>
                                        <option value="C" <?= $mhs['nilai_huruf'] == 'C' ? 'selected' : '' ?>>C</option>
                                        <option value="D" <?= $mhs['nilai_huruf'] == 'D' ? 'selected' : '' ?>>D</option>
                                        <option value="E" <?= $mhs['nilai_huruf'] == 'E' ? 'selected' : '' ?>>E</option>
                                    </select>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if (!empty($mahasiswaList)): ?>
            <div class="mt-6 flex space-x-2">
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">
                    Simpan Nilai
                </button>
                <a href="<?= base_url('dosen/jadwal') ?>" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded">
                    Kembali
                </a>
            </div>
        <?php endif; ?>
    </form>
</div>
<?= $this->endSection() ?>
