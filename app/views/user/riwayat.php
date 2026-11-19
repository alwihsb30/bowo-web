<?php
// Halaman riwayat mutasi saldo (arus masuk & penarikan Rupiah) nasabah
require __DIR__ . '/../layouts/header.php';
?>
<div class="app-shell">
    <?php require __DIR__ . '/../layouts/user_sidebar.php'; ?>

    <main class="main-canvas">
        <div class="page-header">
            <h1>Riwayat</h1>
            <a href="<?= BASE_URL ?>/index.php?c=deposit&a=create" class="btn-primary">+ Setor Sampah</a>
        </div>

        <nav class="sub-tabs">
            <a href="<?= BASE_URL ?>/index.php?c=deposit&a=history">Pengajuan Setoran (Fisik)</a>
            <a href="<?= BASE_URL ?>/index.php?c=transaction&a=history" class="active">Mutasi Saldo (Rupiah)</a>
        </nav>
        <p class="sub-desc">Catatan arus masuk saldo tabungan dari setoran terverifikasi serta riwayat penarikan dana (Rupiah).</p>

        <?php require __DIR__ . '/../layouts/flash.php'; ?>

        <section class="metrics-row">
            <div>
                <span class="metric-label">Total Masuk</span>
                <div class="metric-value">+Rp <?= number_format((float)$total_masuk, 0, ',', '.') ?></div>
            </div>
            <div>
                <span class="metric-label">Total Penarikan</span>
                <div class="metric-value">-Rp <?= number_format((float)$total_penarikan, 0, ',', '.') ?></div>
            </div>
            <div>
                <span class="metric-label">Saldo Akhir</span>
                <div class="metric-value">Rp <?= number_format((float)$user['balance'], 0, ',', '.') ?></div>
            </div>
        </section>

        <div class="section-bar">
            <h2>Mutasi Saldo (<?= count($transactions) ?>)</h2>
            <div class="actions-inline">
                <input type="text" id="tx-search-input" class="search-input" placeholder="Cari mutasi...">
                <?php if (count($transactions) > 10): ?>
                    <button type="button"
                            class="btn-sm js-expand-table"
                            data-target="tx-history-table"
                            data-label="Lihat semua (<?= count($transactions) ?>)">
                        Lihat semua (<?= count($transactions) ?>)
                    </button>
                <?php endif; ?>
            </div>
        </div>

        <table id="tx-history-table" class="is-compact">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Keterangan</th>
                    <th>Jenis Sampah</th>
                    <th>Status</th>
                    <th>Nilai</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transactions as $idx => $tx): ?>
                    <?php
                    $rowClass  = ($idx >= 10) ? 'tx-row extra-row' : 'tx-row';
                    $desc      = isset($tx['description']) ? $tx['description'] : '-';
                    $wasteName = !empty($tx['waste_name']) ? $tx['waste_name'] : '-';
                    $sign      = ($tx['type'] === 'setoran_masuk') ? '+' : '-';
                    ?>
                    <tr class="<?= $rowClass ?>">
                        <td class="mono muted-text"><?= date('Y-m-d', strtotime($tx['created_at'])) ?></td>
                        <td class="name-text"><?= htmlspecialchars($desc) ?></td>
                        <td class="muted-text"><?= htmlspecialchars($wasteName) ?></td>
                        <td>
                            <?php if ($tx['type'] === 'setoran_masuk'): ?>
                                <span class="status-dot"><span class="dot dot-green"></span>Setoran Masuk</span>
                            <?php else: ?>
                                <span class="status-dot"><span class="dot dot-amber"></span>Penarikan</span>
                            <?php endif; ?>
                        </td>
                        <td class="mono"><?= $sign ?>Rp <?= number_format((float)$tx['amount'], 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($transactions)): ?>
                    <tr>
                        <td colspan="5" class="muted-text">Belum ada mutasi saldo.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
