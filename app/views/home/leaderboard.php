<?php
// Halaman peringkat 10 nasabah terbaik (Leaderboard)
require __DIR__ . '/../layouts/header.php';

$userRole = isset($_SESSION['role']) ? $_SESSION['role'] : '';
$isMember = ($userRole === 'admin' || $userRole === 'nasabah');
?>
<?php if ($isMember): ?>
<div class="app-shell">
    <?php
    if ($userRole === 'admin') {
        require __DIR__ . '/../layouts/admin_sidebar.php';
    } else {
        require __DIR__ . '/../layouts/user_sidebar.php';
    }
    ?>
    <main class="main-canvas">
<?php else: ?>
    <?php
    $pubActive = 'leaderboard';
    require __DIR__ . '/../layouts/pub_header.php';
    ?>
<main class="public-canvas">
<?php endif; ?>

        <div class="page-header">
            <h1>Leaderboard</h1>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Peringkat</th>
                    <th>Nasabah</th>
                    <th>Sampah Terkumpul</th>
                    <th>Saldo</th>
                    <th>Poin</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rankings as $index => $rank): ?>
                    <tr>
                        <td class="mono muted-text">#<?= $index + 1 ?></td>
                        <td class="name-text"><?= htmlspecialchars($rank['name']) ?></td>
                        <td class="mono"><?= number_format((float)$rank['total_weight'], 1) ?> Kg</td>
                        <td class="mono">Rp <?= number_format((float)$rank['balance'], 0, ',', '.') ?></td>
                        <td class="mono"><?= number_format((int)$rank['points']) ?> Poin</td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($rankings)): ?>
                    <tr>
                        <td colspan="5" class="muted-text" style="text-align: center;">Belum ada data peringkat nasabah.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

<?php if ($isMember): ?>
    </main>
</div>
<?php else: ?>
</main>
<?php endif; ?>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
