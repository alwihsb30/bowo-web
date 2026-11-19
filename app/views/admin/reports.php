<?php
// Halaman laporan rekapitulasi sistem dan ekspor CSV untuk admin
require __DIR__ . '/../layouts/header.php';

$statusMap = array(
    'diverifikasi' => array('dot-green', 'Diverifikasi'),
    'ditolak'      => array('dot-red',   'Ditolak'),
    'menunggu'     => array('dot-amber', 'Menunggu')
);
?>
<div class="app-shell">
    <?php require __DIR__ . '/../layouts/admin_sidebar.php'; ?>

    <main class="main-canvas">
        <div class="page-header">
            <h1>Laporan</h1>
            <div class="actions-inline">
                <button type="button" onclick="window.print()" class="btn-sm">Cetak</button>
                <a href="<?= BASE_URL ?>/index.php?c=transaction&a=exportCsv" class="btn-sm btn-sm-primary">Unduh CSV</a>
            </div>
        </div>

        <section class="metrics-row">
            <div>
                <span class="metric-label">Nasabah</span>
                <div class="metric-value"><?= number_format((int)$summary['users']) ?></div>
            </div>
            <div>
                <span class="metric-label">Total Terverifikasi</span>
                <div class="metric-value"><?= number_format((float)$total_volume, 1) ?> Kg</div>
            </div>
            <div>
                <span class="metric-label">Total Saldo</span>
                <div class="metric-value">Rp <?= number_format((float)$total_cashout, 0, ',', '.') ?></div>
            </div>
        </section>

        <div class="section-bar">
            <h2>Riwayat Setoran (<?= count($reports) ?>)</h2>
            <div class="actions-inline">
                <input type="text" id="report-search-input" class="search-input" placeholder="Cari...">
                <?php if (count($reports) > 10): ?>
                    <button type="button"
                            class="btn-sm js-expand-table"
                            data-target="admin-report-table"
                            data-label="Lihat semua (<?= count($reports) ?>)">
                        Lihat semua (<?= count($reports) ?>)
                    </button>
                <?php endif; ?>
            </div>
        </div>

        <table id="admin-report-table" class="is-compact">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nasabah</th>
                    <th>Jenis Sampah</th>
                    <th>Berat</th>
                    <th>Nilai</th>
                    <th>Bukti</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reports as $idx => $row): ?>
                    <?php
                    $rowClass = ($idx >= 10) ? 'report-row extra-row' : 'report-row';
                    $st       = isset($statusMap[$row['status']]) ? $statusMap[$row['status']] : $statusMap['menunggu'];
                    ?>
                    <tr class="<?= $rowClass ?>">
                        <td class="mono muted-text"><?= htmlspecialchars($row['deposit_date']) ?></td>
                        <td class="name-text"><?= htmlspecialchars($row['user_name']) ?></td>
                        <td><?= htmlspecialchars($row['waste_name']) ?></td>
                        <td class="mono"><?= number_format((float)$row['weight'], 1) ?> Kg</td>
                        <td class="mono">Rp <?= number_format((float)$row['total_value'], 0, ',', '.') ?></td>
                        <td>
                            <?php if (!empty($row['photo_proof'])): ?>
                                <a href="<?= BASE_URL . '/' . ltrim($row['photo_proof'], '/') ?>" target="_blank" class="link-subtle">Lihat</a>
                            <?php else: ?>
                                <span class="muted-text">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="status-dot"><span class="dot <?= $st[0] ?>"></span><?= $st[1] ?></span>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($reports)): ?>
                    <tr>
                        <td colspan="7" class="muted-text" style="text-align: center;">Belum ada data setoran sampah.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
