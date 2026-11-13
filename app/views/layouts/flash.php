<?php
// Komponen tampilan notifikasi pesan singkat dari session
if (!empty($flash)): ?>
    <div class="alert">
        <?= htmlspecialchars($flash['message']) ?>
    </div>
<?php endif; ?>