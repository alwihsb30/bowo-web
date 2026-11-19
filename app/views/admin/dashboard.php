<?php
// Halaman verifikasi setoran sampah untuk admin
require __DIR__ . '/../layouts/header.php';
?>
<div class="app-shell">
    <?php require __DIR__ . '/../layouts/admin_sidebar.php'; ?>

    <main class="main-canvas">
        <div class="page-header">
            <h1>Verifikasi</h1>
            <a href="<?= BASE_URL ?>/index.php?c=transaction&a=exportCsv" class="btn-sm">Unduh CSV</a>
        </div>

        <?php require __DIR__ . '/../layouts/flash.php'; ?>

        <section class="metrics-row">
            <div>
                <span class="metric-label">Menunggu Verifikasi</span>
                <div class="metric-value"><?= number_format((int)$stats['pending']) ?></div>
            </div>
            <div>
                <span class="metric-label">Total Terverifikasi</span>
                <div class="metric-value"><?= number_format((float)$stats['weight'], 1) ?> Kg</div>
            </div>
            <div>
                <span class="metric-label">Total Saldo</span>
                <div class="metric-value">Rp <?= number_format((float)$stats['value'], 0, ',', '.') ?></div>
            </div>
        </section>

        <div class="section-bar">
            <h2>Antrean Verifikasi (<?= count($pending) ?>)</h2>
            <?php if (count($pending) > 10): ?>
                <button type="button" class="btn-sm js-expand-table" data-target="admin-pending-table" data-label="Lihat semua (<?= count($pending) ?>)">
                    Lihat semua (<?= count($pending) ?>)
                </button>
            <?php endif; ?>
        </div>

        <table id="admin-pending-table" class="is-compact">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nasabah</th>
                    <th>Jenis Sampah</th>
                    <th>Berat</th>
                    <th>Nilai</th>
                    <th>Bukti</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pending as $idx => $item): ?>
                    <tr<?= ($idx >= 10) ? ' class="extra-row"' : '' ?>>
                        <td class="mono muted-text"><?= htmlspecialchars($item['deposit_date']) ?></td>
                        <td class="name-text"><?= htmlspecialchars($item['user_name']) ?></td>
                        <td><?= htmlspecialchars($item['waste_name']) ?></td>
                        <td class="mono"><?= number_format((float)$item['weight'], 1) ?> Kg</td>
                        <td class="mono">Rp <?= number_format((float)$item['total_value'], 0, ',', '.') ?></td>
                        <td>
                            <?php if (!empty($item['photo_proof'])): ?>
                                <a href="<?= BASE_URL . '/' . ltrim($item['photo_proof'], '/') ?>" target="_blank" class="link-subtle">Lihat</a>
                            <?php else: ?>
                                <span class="muted-text">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="actions-inline">
                                <form action="<?= BASE_URL ?>/index.php?c=transaction&a=verify" method="POST" style="display:inline;">
                                    <input type="hidden" name="deposit_id" value="<?= (int)$item['id'] ?>">
                                    <input type="hidden" name="action" value="diverifikasi">
                                    <button type="submit" class="btn-sm btn-sm-primary">Setujui</button>
                                </form>
                                <form action="<?= BASE_URL ?>/index.php?c=transaction&a=verify" method="POST" style="display:inline;" onsubmit="return confirm('Tolak setoran ini?');">
                                    <input type="hidden" name="deposit_id" value="<?= (int)$item['id'] ?>">
                                    <input type="hidden" name="action" value="ditolak">
                                    <button type="submit" class="btn-sm">Tolak</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($pending)): ?>
                    <tr><td colspan="7" class="muted-text" style="text-align: center;">Tidak ada pengajuan setoran yang menunggu verifikasi.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
