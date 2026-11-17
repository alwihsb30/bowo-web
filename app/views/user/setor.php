<?php
// Halaman formulir setor sampah nasabah
require __DIR__ . '/../layouts/header.php';
?>
<div class="app-shell">
    <?php require __DIR__ . '/../layouts/user_sidebar.php'; ?>

    <main class="main-canvas">
        <div class="page-header">
            <div>
                <h1>Setor Sampah</h1>
                <p class="muted-text" style="margin-top: 4px;">Catat penyetoran sampah daur ulang untuk ditinjau Admin dan masuk ke saldo tabungan.</p>
            </div>
        </div>

        <?php require __DIR__ . '/../layouts/flash.php'; ?>

        <form action="<?= BASE_URL ?>/index.php?c=deposit&a=store" method="POST" enctype="multipart/form-data" class="stack-form deposit-workspace">
            <div class="field">
                <label for="waste_type_id">Jenis Sampah</label>
                <select name="waste_type_id" id="waste_type_id" required>
                    <?php foreach ($waste_types as $w): ?>
                        <option value="<?= (int)$w['id'] ?>" data-price="<?= (float)$w['price_per_kg'] ?>">
                            <?= htmlspecialchars($w['name']) ?> (Rp <?= number_format((float)$w['price_per_kg'], 0, ',', '.') ?>/Kg)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-grid-2">
                <div class="field">
                    <label for="weight">Berat Sampah (Kg)</label>
                    <input type="number" step="0.01" min="0.01" name="weight" id="weight" class="mono" placeholder="2.5" required>
                </div>
                <div class="field">
                    <label for="deposit_date">Tanggal Setor</label>
                    <input type="date" name="deposit_date" id="deposit_date" value="<?= date('Y-m-d') ?>" class="mono" required>
                </div>
            </div>

            <div class="form-grid-2">
                <div class="field">
                    <label>Estimasi Nilai</label>
                    <div id="est_val" class="readonly-box mono">Rp 0</div>
                </div>
                <div class="field">
                    <label>Estimasi Poin</label>
                    <div id="est_pts" class="readonly-box mono">+0 Poin</div>
                </div>
            </div>

            <div class="field">
                <label for="photo_proof">Foto Bukti (Opsional, JPG/PNG/WEBP maks. 2 MB)</label>
                <input type="file" name="photo_proof" id="photo_proof" accept=".jpg,.jpeg,.png,.webp">
            </div>

            <div class="field">
                <label for="notes">Catatan (Opsional)</label>
                <textarea name="notes" id="notes" rows="2" placeholder="Contoh: Botol bersih tanpa label, dikemas rapi"></textarea>
            </div>

            <div style="padding-top: 6px;">
                <button type="submit" class="btn-primary">Kirim Setoran</button>
            </div>
        </form>
    </main>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
