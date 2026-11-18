<?php
// Halaman riwayat pengajuan setoran fisik sampah milik nasabah
require __DIR__ . '/../layouts/header.php';

$statusMap = array(
    'diverifikasi' => array('dot-green', 'Diverifikasi'),
    'ditolak'      => array('dot-red',   'Ditolak'),
    'menunggu'     => array('dot-amber', 'Menunggu')
);
?>
<div class="app-shell">
    <?php require __DIR__ . '/../layouts/user_sidebar.php'; ?>

    <main class="main-canvas">
        <div class="page-header">
            <h1>Riwayat</h1>
            <a href="<?= BASE_URL ?>/index.php?c=deposit&a=create" class="btn-primary">+ Setor Sampah</a>
        </div>

        <nav class="sub-tabs">
            <a href="<?= BASE_URL ?>/index.php?c=deposit&a=history" class="active">Pengajuan Setoran (Fisik)</a>
            <a href="<?= BASE_URL ?>/index.php?c=transaction&a=history">Mutasi Saldo (Rupiah)</a>
        </nav>
        <p class="sub-desc">Daftar pengajuan setoran fisik sampah berdasarkan jenis, berat (Kg), foto bukti, dan status verifikasi Admin.</p>

        <?php require __DIR__ . '/../layouts/flash.php'; ?>

        <section class="metrics-row">
            <div>
                <span class="metric-label">Total Pengajuan</span>
                <div class="metric-value"><?= number_format((int)$total_count) ?></div>
            </div>
            <div>
                <span class="metric-label">Sampah Terverifikasi</span>
                <div class="metric-value"><?= number_format((float)$verified_weight, 1) ?> Kg</div>
            </div>
            <div>
                <span class="metric-label">Menunggu Verifikasi</span>
                <div class="metric-value"><?= number_format((int)$pending_count) ?></div>
            </div>
        </section>

        <div class="section-bar">
            <h2>Daftar Pengajuan Setoran (<?= count($deposits) ?>)</h2>
            <div class="actions-inline">
                <input type="text" id="deposit-search-input" class="search-input" placeholder="Cari setoran...">
                <?php if (count($deposits) > 10): ?>
                    <button type="button"
                            class="btn-sm js-expand-table"
                            data-target="user-deposits-table"
                            data-label="Lihat semua (<?= count($deposits) ?>)">
                        Lihat semua (<?= count($deposits) ?>)
                    </button>
                <?php endif; ?>
            </div>
        </div>

        <table id="user-deposits-table" class="is-compact">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Jenis Sampah</th>
                    <th>Berat</th>
                    <th>Nilai</th>
                    <th>Bukti</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($deposits as $idx => $dep): ?>
                    <?php
                    $rowClass = ($idx >= 10) ? 'deposit-row extra-row' : 'deposit-row';
                    $st       = isset($statusMap[$dep['status']]) ? $statusMap[$dep['status']] : $statusMap['menunggu'];
                    ?>
                    <tr class="<?= $rowClass ?>">
                        <td class="mono muted-text"><?= htmlspecialchars($dep['deposit_date']) ?></td>
                        <td class="name-text"><?= htmlspecialchars($dep['waste_name']) ?></td>
                        <td class="mono"><?= number_format((float)$dep['weight'], 1) ?> Kg</td>
                        <td class="mono">Rp <?= number_format((float)$dep['total_value'], 0, ',', '.') ?></td>
                        <td>
                            <?php if (!empty($dep['photo_proof'])): ?>
                                <a href="<?= BASE_URL . '/' . ltrim($dep['photo_proof'], '/') ?>" target="_blank" class="link-subtle">Lihat</a>
                            <?php else: ?>
                                <span class="muted-text">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="status-dot"><span class="dot <?= $st[0] ?>"></span><?= $st[1] ?></span>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($deposits)): ?>
                    <tr>
                        <td colspan="6" class="muted-text">Belum ada riwayat setoran.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
