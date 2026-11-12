<?php
// Bagian header HTML utama aplikasi BOWO
$pageTitle = isset($title) ? $title : 'BOWO — Bank of Waste Online';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link rel="stylesheet" href="<?= ASSET_URL ?>/css/style.css?v=<?= time() ?>">
    <script>
        window.BASE_URL = "<?= htmlspecialchars(BASE_URL) ?>";
    </script>
</head>
<body>
