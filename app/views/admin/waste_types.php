<?php
// Halaman kelola jenis dan harga sampah untuk admin
require __DIR__ . '/../layouts/header.php';

$editing      = !empty($edit_item);
$formAction   = $editing ? 'update' : 'store';
$formName     = '';
$formCategory = '';
$formPrice    = '';
$formDesc     = '';

if ($editing) {
    $formName     = $edit_item['name'];
    $formCategory = $edit_item['category'];
    $formPrice    = $edit_item['price_per_kg'];
    $formDesc     = isset($edit_item['description']) ? $edit_item['description'] : '';
}
?>
<div class="app-shell">
    <?php require __DIR__ . '/../layouts/admin_sidebar.php'; ?>

    <main class="main-canvas">
        <div class="page-header">
            <h1>Jenis Sampah</h1>
            <input type="text" id="admin-waste-search" class="search-input" placeholder="Cari...">
        </div>

        <?php require __DIR__ . '/../layouts/flash.php'; ?>

        <form action="<?= BASE_URL ?>/index.php?c=wastetype&a=<?= $formAction ?>" method="POST" class="inline-form">
            <?php if ($editing): ?>
                <input type="hidden" name="id" value="<?= (int)$edit_item['id'] ?>">
            <?php endif; ?>

            <div class="field" style="flex: 1.5;">
                <label for="name">Nama Sampah</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($formName) ?>" placeholder="Plastik HDPE" required>
            </div>

            <div class="field" style="flex: 1;">
                <label for="category">Kategori</label>
                <input type="text" id="category" name="category" value="<?= htmlspecialchars($formCategory) ?>" placeholder="Plastik" required>
            </div>

            <div class="field" style="flex: 1;">
                <label for="price_per_kg">Harga / Kg (Rp)</label>
                <input type="number" step="100" min="0" id="price_per_kg" name="price_per_kg" class="mono" value="<?= htmlspecialchars($formPrice) ?>" placeholder="3500" required>
            </div>

            <div class="field" style="flex: 1.5;">
                <label for="description">Deskripsi</label>
                <input type="text" id="description" name="description" value="<?= htmlspecialchars($formDesc) ?>" placeholder="Botol sampo bersih">
            </div>

            <?php if ($editing): ?>
                <div class="field" style="flex: 0.9;">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="active" <?= ($edit_item['status'] === 'active') ? 'selected' : '' ?>>Aktif</option>
                        <option value="inactive" <?= ($edit_item['status'] === 'inactive') ? 'selected' : '' ?>>Nonaktif</option>
                    </select>
                </div>
                <a href="<?= BASE_URL ?>/index.php?c=wastetype&a=index" class="btn-sm" style="height: 36px;">Batal</a>
            <?php else: ?>
                <input type="hidden" name="status" value="active">
            <?php endif; ?>

            <button type="submit" class="btn-primary">Simpan</button>
        </form>

        <div class="section-bar">
            <h2>Daftar Jenis Sampah (<?= count($types) ?>)</h2>
            <?php if (count($types) > 10): ?>
                <button type="button"
                        class="btn-sm js-expand-table"
                        data-target="admin-waste-table"
                        data-label="Lihat semua (<?= count($types) ?>)">
                    Lihat semua (<?= count($types) ?>)
                </button>
            <?php endif; ?>
        </div>

        <table id="admin-waste-table" class="is-compact">
            <thead>
                <tr>
                    <th>Nama Sampah</th>
                    <th>Kategori</th>
                    <th>Harga / Kg</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="admin-waste-tbody">
                <?php foreach ($types as $idx => $t): ?>
                    <tr<?= ($idx >= 10) ? ' class="extra-row"' : '' ?>>
                        <td class="name-text"><?= htmlspecialchars($t['name']) ?></td>
                        <td class="muted-text"><?= htmlspecialchars($t['category']) ?></td>
                        <td class="mono">Rp <?= number_format((float)$t['price_per_kg'], 0, ',', '.') ?></td>
                        <td>
                            <?php if ($t['status'] === 'active'): ?>
                                <span class="status-dot"><span class="dot dot-green"></span>Aktif</span>
                            <?php else: ?>
                                <span class="status-dot"><span class="dot dot-amber"></span>Nonaktif</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="actions-inline">
                                <a href="<?= BASE_URL ?>/index.php?c=wastetype&a=index&edit_id=<?= (int)$t['id'] ?>" class="btn-sm">Ubah</a>
                                <form action="<?= BASE_URL ?>/index.php?c=wastetype&a=delete" method="POST" style="display:inline;" onsubmit="return confirm('Hapus jenis sampah ini?');">
                                    <input type="hidden" name="id" value="<?= (int)$t['id'] ?>">
                                    <button type="submit" class="btn-sm">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($types)): ?>
                    <tr>
                        <td colspan="5" class="muted-text" style="text-align: center;">Data jenis sampah tidak ditemukan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
