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
            <input type="text" id="identity" name="identity" value="<?= htmlspecialchars(isset($remembered_username) ? $remembered_username : '') ?>" placeholder="Masukkan username atau email" required>
        </div>

        <div class="field">
            <label for="password">Kata Sandi</label>
            <input type="password" id="password" name="password" placeholder="Masukkan kata sandi" required>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 12.5px;">
            <label class="muted-text" style="display: inline-flex; align-items: center; gap: 6px; font-size: 12.5px; cursor: pointer;">
                <input type="checkbox" name="remember" value="1" <?= !empty($remembered_username) ? 'checked' : '' ?>>
                <span>Ingat Username</span>
            </label>
            <a href="<?= BASE_URL ?>/index.php?c=auth&a=register" class="link-subtle">Daftar akun</a>
        </div>

        <button type="submit" class="btn-primary" style="width: 100%;">Masuk</button>
    </form>
</main>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
