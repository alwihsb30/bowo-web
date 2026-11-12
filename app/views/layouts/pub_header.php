<?php
// Navbar atas bersama untuk halaman publik dan autentikasi BOWO
$pubActive = isset($pubActive) ? $pubActive : '';

if ($pubActive === 'beranda') {
    $katalogHref = '#katalog';
    $katalogId   = ' id="nav-katalog-link"';
} else {
    $katalogHref = BASE_URL . '/index.php#katalog';
    $katalogId   = '';
}

$showDashBtn = ($pubActive === 'beranda' && !empty($_SESSION['user_id']));
$dashUrl     = BASE_URL . '/index.php?c=deposit&a=dashboard';
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $dashUrl = BASE_URL . '/index.php?c=transaction&a=dashboard';
}
?>
<header class="pub-header-wrap">
    <div class="pub-nav">
        <a href="<?= BASE_URL ?>/index.php" class="brand">
            <img src="<?= ASSET_URL ?>/images/bowo.png" alt="BOWO">
            <span>BOWO</span>
        </a>
        <nav class="pub-links">
            <a href="<?= BASE_URL ?>/index.php"<?= ($pubActive === 'beranda') ? ' class="active"' : '' ?>>Beranda</a>
            <a href="<?= $katalogHref ?>"<?= $katalogId ?>>Katalog</a>
            <a href="<?= BASE_URL ?>/index.php?c=home&a=leaderboard"<?= ($pubActive === 'leaderboard') ? ' class="active"' : '' ?>>Leaderboard</a>
            <?php if ($showDashBtn): ?>
                <a href="<?= $dashUrl ?>" class="btn-primary" style="height:32px; padding:0 14px;">Dashboard</a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/index.php?c=auth&a=login"<?= ($pubActive === 'login') ? ' class="active"' : '' ?>>Masuk</a>
                <a href="<?= BASE_URL ?>/index.php?c=auth&a=register" class="btn-primary" style="height:32px; padding:0 14px;">Daftar</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
