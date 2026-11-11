<?php
// Konfigurasi BASE_URL dinamis untuk XAMPP/LAN dan koneksi database PDO

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('Asia/Jakarta');

$protocol  = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$host      = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '127.0.0.1';
$scriptDir = isset($_SERVER['SCRIPT_NAME']) ? str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])) : '';
$scriptDir = ($scriptDir === '/' || $scriptDir === '.') ? '' : rtrim($scriptDir, '/');

if (!defined('BASE_URL')) {
    define('BASE_URL', $protocol . $host . $scriptDir);
}

if (!defined('ASSET_URL')) {
    define('ASSET_URL', BASE_URL . '/public/assets');
}

class Database
{
    private $host     = '127.0.0.1';
    private $db_name  = 'bowo_db';
    private $username = 'root';
    private $password = '';
    private $pdo      = null;

    public function __construct()
    {
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->db_name . ';charset=utf8mb4';

        try {
            $this->pdo = new PDO($dsn, $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die('Koneksi database gagal: ' . htmlspecialchars($e->getMessage()));
        }
    }

    public function getConnection()
    {
        return $this->pdo;
    }
}
