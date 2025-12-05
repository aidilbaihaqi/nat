<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<h1 class="text-3xl font-bold text-gray-900 mb-6">Hasil Studi</h1>

<!-- IPK Card -->
<?php if (!empty($hasil_studi)): ?>
    <div class="bg-blue-600 text-white rounded-lg shadow-lg p-6 mb-6">
        <div class="text-center">
            <h2 class="text-lg font-semibold mb-2">Indeks Prestasi Kumulatif (IPK)</h2>
            <p class="text-5xl font-bold"><?= number_format($ipk, 2) ?></p>
            <p class="text-sm mt-2">Total SKS: <?= $total_sks ?></p>
        </div>
    </div>
<?php endif; ?>

<!-- Tabel Hasil Studi -->
<div class="bg-white rounded-lg shadow overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Kuliah</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKS</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai Angka</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai Huruf</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai Mutu</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mutu × SKS</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php if (empty($hasil_studi)): ?>
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada nilai yang diinput</td>
                </tr>
            <?php else: ?>
                <?php foreach ($hasil_studi as $hs): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($hs['nama_mata_kuliah']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($hs['sks']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($hs['nilai_angka']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                <?php 
                                    if ($hs['nilai_huruf'] == 'A') echo 'bg-green-100 text-green-800';
                                    elseif (in_array($hs['nilai_huruf'], ['A-', 'B+', 'B'])) echo 'bg-blue-100 text-blue-800';
                                    elseif (in_array($hs['nilai_huruf'], ['B-', 'C+', 'C'])) echo 'bg-yellow-100 text-yellow-800';
                                    else echo 'bg-red-100 text-red-800';
                                ?>">
                                <?= esc($hs['nilai_huruf']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= number_format($hs['nilai_mutu'], 2) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= number_format($hs['nilai_mutu_sks'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
        <?php if (!empty($hasil_studi)): ?>
            <tfoot class="bg-gray-50">
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">Total</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900"><?= $total_sks ?></td>
                    <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900"></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900"><?= number_format($total_nilai_mutu, 2) ?></td>
                </tr>
            </tfoot>
        <?php endif; ?>
    </table>
</div>
<?= $this->endSection() ?>
