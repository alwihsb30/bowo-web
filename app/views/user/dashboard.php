<?php
// Halaman dashboard ringkasan akun nasabah dengan daftar 3 aktivitas terakhir
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
            <h1>Dashboard</h1>
            <a href="<?= BASE_URL ?>/index.php?c=deposit&a=create" class="btn-primary">+ Setor Sampah</a>
        </div>

        <?php require __DIR__ . '/../layouts/flash.php'; ?>

        <section class="metrics-row">
            <div>
                <span class="metric-label">Saldo</span>
                <div class="metric-value">Rp <?= number_format((float)$user['balance'], 0, ',', '.') ?></div>
            </div>
            <div>
                <span class="metric-label">Poin</span>
                <div class="metric-value"><?= number_format((int)$user['points']) ?> Poin</div>
            </div>
            <div>
                <span class="metric-label">Sampah Terverifikasi</span>
                <div class="metric-value"><?= number_format((float)$total_weight, 1) ?> Kg</div>
            </div>
        </section>

        <div class="section-bar">
            <h2>Aktivitas Setoran Terakhir</h2>
            <a href="<?= BASE_URL ?>/index.php?c=deposit&a=history" class="link-subtle">Lihat semua</a>
        </div>

        <ul class="activity-list">
            <?php foreach ($recent_deposits as $dep): ?>
                <?php $st = isset($statusMap[$dep['status']]) ? $statusMap[$dep['status']] : $statusMap['menunggu']; ?>
                <li class="activity-item">
                    <div>
                        <div class="name-text"><?= htmlspecialchars($dep['waste_name']) ?></div>
                        <div class="muted-text mono"><?= htmlspecialchars($dep['deposit_date']) ?></div>
                    </div>
                    <div class="activity-meta">
                        <span class="mono"><?= number_format((float)$dep['weight'], 1) ?> Kg</span>
                        <span class="status-dot"><span class="dot <?= $st[0] ?>"></span><?= $st[1] ?></span>
                    </div>
                </li>
            <?php endforeach; ?>
            <?php if (empty($recent_deposits)): ?>
                <li class="activity-item">
                    <span class="muted-text">Belum ada pengajuan setoran sampah.</span>
                </li>
            <?php endif; ?>
        </ul>
    </main>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
