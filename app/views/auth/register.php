<?php
// Halaman form pendaftaran akun nasabah baru
require __DIR__ . '/../layouts/header.php';
$pubActive = 'register';
require __DIR__ . '/../layouts/pub_header.php';
?>
<main class="auth-canvas">
    <div class="page-header">
        <h1>Daftar Nasabah</h1>
    </div>

    <?php require __DIR__ . '/../layouts/flash.php'; ?>
    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>/index.php?c=auth&a=store" method="POST" class="stack-form">
        <div class="field">
            <label for="name">Nama Lengkap</label>
            <input type="text"
                   id="name"
                   name="name"
                   value="<?= htmlspecialchars(isset($old['name']) ? $old['name'] : '') ?>"
                   placeholder="Budi Santoso"
                   required>
        </div>

        <div class="field">
            <label for="username">Username</label>
            <input type="text"
                   id="username"
                   name="username"
                   value="<?= htmlspecialchars(isset($old['username']) ? $old['username'] : '') ?>"
                   placeholder="budi123"
                   required>
        </div>

        <div class="field">
            <label for="phone">Telepon</label>
            <input type="text"
                   id="phone"
                   name="phone"
                   value="<?= htmlspecialchars(isset($old['phone']) ? $old['phone'] : '') ?>"
                   placeholder="081234567890"
                   required>
        </div>

        <div class="field">
            <label for="email">Email</label>
            <input type="email"
                   id="email"
                   name="email"
                   value="<?= htmlspecialchars(isset($old['email']) ? $old['email'] : '') ?>"
                   placeholder="budi@gmail.com"
                   required>
        </div>

        <div class="field">
            <label for="address">Alamat</label>
            <input type="text"
                   id="address"
                   name="address"
                   value="<?= htmlspecialchars(isset($old['address']) ? $old['address'] : '') ?>"
                   placeholder="Alamat tempat tinggal"
                   required>
        </div>

        <div class="field">
            <label for="password">Kata Sandi</label>
            <input type="password"
                   id="password"
                   name="password"
                   placeholder="Minimal 6 karakter"
                   required>
        </div>

        <button type="submit" class="btn-primary" style="width: 100%;">Daftar</button>

        <div style="text-align: center;">
            <a href="<?= BASE_URL ?>/index.php?c=auth&a=login" class="link-subtle">Sudah punya akun? Masuk</a>
        </div>
    </form>
</main>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
