<?php
// Komponen tampilan notifikasi pesan singkat (flash alert) dari session
if (!empty($flash)): ?>
    <div class="alert <?= $flash['type'] === 'error' ? 'alert-error' : '' ?>">
        <?= htmlspecialchars($flash['message']) ?>
    </div>
<?php endif; ?>
