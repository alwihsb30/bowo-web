<?php
// Bilah navigasi kiri (Minimalist Left-Rail) untuk halaman Nasabah
$curC = isset($_GET['c']) ? strtolower($_GET['c']) : '';
$curA = isset($_GET['a']) ? strtolower($_GET['a']) : 'dashboard';
?>
<aside class="left-rail">
    <div>
        <div class="rail-brand">
            <a href="<?= BASE_URL ?>/index.php" class="brand">
                <img src="<?= ASSET_URL ?>/images/bowo.png" alt="BOWO">
                <span>BOWO</span>
            </a>
        </div>
        <nav class="nav-list">
            <a href="<?= BASE_URL ?>/index.php?c=deposit&a=dashboard" class="<?= ($curC === 'deposit' && $curA === 'dashboard') ? 'active' : '' ?>">Dashboard</a>
            <a href="<?= BASE_URL ?>/index.php?c=deposit&a=create" class="<?= ($curC === 'deposit' && $curA === 'create') ? 'active' : '' ?>">Setor Sampah</a>
            <a href="<?= BASE_URL ?>/index.php?c=deposit&a=history" class="<?= (($curC === 'deposit' && $curA === 'history') || $curC === 'transaction') ? 'active' : '' ?>">Riwayat</a>
            <a href="<?= BASE_URL ?>/index.php?c=home&a=leaderboard" class="<?= ($curC === 'home' && $curA === 'leaderboard') ? 'active' : '' ?>">Leaderboard</a>
        </nav>
    </div>
    <div class="rail-footer">
        <span class="rail-user"><?= htmlspecialchars(isset($_SESSION['name']) ? $_SESSION['name'] : 'Nasabah') ?></span>
        <a href="<?= BASE_URL ?>/index.php?c=auth&a=logout" class="rail-logout">Logout</a>
    </div>
</aside>
