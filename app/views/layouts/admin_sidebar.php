<?php
// Bilah navigasi kiri (Minimalist Left-Rail) untuk halaman Admin
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
            <a href="<?= BASE_URL ?>/index.php?c=transaction&a=dashboard" class="<?= ($curC === 'transaction' && $curA === 'dashboard') ? 'active' : '' ?>">Verifikasi</a>
            <a href="<?= BASE_URL ?>/index.php?c=wastetype&a=index" class="<?= ($curC === 'wastetype') ? 'active' : '' ?>">Jenis Sampah</a>
            <a href="<?= BASE_URL ?>/index.php?c=transaction&a=reports" class="<?= ($curC === 'transaction' && $curA === 'reports') ? 'active' : '' ?>">Laporan</a>
            <a href="<?= BASE_URL ?>/index.php?c=home&a=leaderboard" class="<?= ($curC === 'home' && $curA === 'leaderboard') ? 'active' : '' ?>">Leaderboard</a>
        </nav>
    </div>
    <div class="rail-footer">
        <span class="rail-user">Admin</span>
        <a href="<?= BASE_URL ?>/index.php?c=auth&a=logout" class="rail-logout">Logout</a>
    </div>
</aside>
