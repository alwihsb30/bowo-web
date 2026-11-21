<?php
// Halaman beranda publik: statistik global, top 3 sampah terbanyak, dan katalog
require __DIR__ . '/../layouts/header.php';
$pubActive = 'beranda';
require __DIR__ . '/../layouts/pub_header.php';
?>
<main class="public-canvas">
    <section class="hero-block">
        <h1 class="hero-title">Bank Sampah BOWO</h1>
        <p class="hero-subtitle">Layanan pencatatan setoran sampah daur ulang dan tabungan nasabah.</p>
        <div class="global-meta-strip">
            <span><strong class="mono"><?= number_format((int)$total_users) ?></strong> Nasabah</span>
            <span class="meta-sep" aria-hidden="true">·</span>
            <span><strong class="mono"><?= number_format((float)$total_waste, 1) ?> Kg</strong> Terkumpul</span>
            <span class="meta-sep" aria-hidden="true">·</span>
            <span><strong class="mono"><?= number_format((int)$total_deposits) ?></strong> Setoran</span>
        </div>
    </section>

    <section class="top-waste-band" aria-labelledby="top-waste-heading">
        <h2 id="top-waste-heading" class="section-kicker">Jenis Sampah Terbanyak Disetor</h2>
        <div class="top-waste-grid">
            <?php foreach ($top_wastes as $idx => $tw): ?>
                <div class="top-waste-item">
                    <div class="top-waste-name">#<?= $idx + 1 ?> <?= htmlspecialchars($tw['name']) ?></div>
                    <div class="top-waste-meta mono"><?= number_format((float)$tw['total_weight'], 1) ?> Kg terkumpul · Rp <?= number_format((float)$tw['price_per_kg'], 0, ',', '.') ?>/kg</div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section id="katalog" class="home-section-sep">
        <div class="section-bar">
            <h2>Katalog Sampah</h2>
            <div class="actions-inline">
                <input type="text"
                       id="public-waste-search"
                       class="search-input"
                       placeholder="Cari jenis sampah...">
            </div>
        </div>

        <table id="public-catalog-table">
            <thead>
                <tr>
                    <th>Jenis Sampah</th>
                    <th>Kategori</th>
                    <th>Deskripsi</th>
                    <th>Harga / Kg</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($waste_types as $type): ?>
                    <?php $desc = !empty($type['description']) ? $type['description'] : '-'; ?>
                    <tr class="waste-card">
                        <td class="name-text"><?= htmlspecialchars($type['name']) ?></td>
                        <td class="muted-text"><?= htmlspecialchars($type['category']) ?></td>
                        <td class="muted-text"><?= htmlspecialchars($desc) ?></td>
                        <td class="mono">Rp <?= number_format((float)$type['price_per_kg'], 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($waste_types)): ?>
                    <tr>
                        <td colspan="4" class="muted-text">Belum ada data katalog sampah.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
</main>
<?php require __DIR__ . '/../layouts/footer.php'; ?>