<?php
// File utama (Front Controller) untuk mengatur routing halaman aplikasi BOWO

require_once __DIR__ . '/app/config/Database.php';
require_once __DIR__ . '/app/core/Model.php';
require_once __DIR__ . '/app/core/Controller.php';

// Menentukan controller dan method default
$controllerKey = isset($_GET['c']) ? strtolower(trim($_GET['c'])) : 'home';
$actionMethod  = isset($_GET['a']) ? trim($_GET['a']) : 'index';

// Daftar pemetaan nama parameter c ke class Controller
$controllerMap = array(
    'home'        => 'HomeController',
    'auth'        => 'AuthController',
    'wastetype'   => 'WasteTypeController',
    'deposit'     => 'DepositController',
    'transaction' => 'TransactionController'
);

if (!isset($controllerMap[$controllerKey])) {
    http_response_code(404);
    die('Halaman tidak ditemukan. <a href="' . BASE_URL . '/index.php">Kembali ke Beranda</a>');
}

$controllerName = $controllerMap[$controllerKey];
$controllerFile = __DIR__ . '/app/controllers/' . $controllerName . '.php';

if (!file_exists($controllerFile)) {
    http_response_code(404);
    die('File controller tidak ditemukan.');
}

require_once $controllerFile;
$controller = new $controllerName();

if (!method_exists($controller, $actionMethod)) {
    http_response_code(404);
    die('Halaman atau aksi tidak tersedia. <a href="' . BASE_URL . '/index.php">Kembali ke Beranda</a>');
}

// Menjalankan method pada controller yang diminta
$controller->$actionMethod();
