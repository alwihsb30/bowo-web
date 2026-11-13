<?php
// Halaman form login pengguna
require __DIR__ . '/../layouts/header.php';
$pubActive = 'login';
require __DIR__ . '/../layouts/pub_header.php';
?>
<main class="auth-canvas">
    <div class="page-header">
        <h1>Masuk</h1>
    </div>

    <?php require __DIR__ . '/../layouts/flash.php'; ?>
    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>/index.php?c=auth&a=authenticate" method="POST" class="stack-form">
        <div class="field">
            <label for="identity">Username atau Email</label>
            <input type="text" id="identity" name="identity" placeholder="Masukkan username atau email" required>
        </div>

        <div class="field">
            <label for="password">Kata Sandi</label>
            <input type="password" id="password" name="password" placeholder="Masukkan kata sandi" required>
        </div>

        <div style="text-align: right; font-size: 12.5px;">
            <a href="<?= BASE_URL ?>/index.php?c=auth&a=register" class="link-subtle">Daftar akun</a>
        </div>

        <button type="submit" class="btn-primary" style="width: 100%;">Masuk</button>
    </form>
</main>
<?php require __DIR__ . '/../layouts/footer.php'; ?>