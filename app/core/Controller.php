<?php
// Class induk untuk seluruh Controller pada aplikasi BOWO
class Controller
{
    protected function view($viewPath, $data = array())
    {
        if (!empty($data)) extract($data);
        $viewFile = __DIR__ . '/../views/' . $viewPath . '.php';
        if (!file_exists($viewFile)) {
            die('File view app/views/' . htmlspecialchars($viewPath) . '.php tidak ditemukan.');
        }
        require $viewFile;
    }

    protected function model($modelName)
    {
        require_once __DIR__ . '/../models/' . $modelName . '.php';
        return new $modelName();
    }

    protected function requireLogin()
    {
        if (empty($_SESSION['user_id'])) {
            $this->setFlash('error', 'Silakan login terlebih dahulu.');
            $this->redirect('index.php?c=auth&a=login');
        }
    }

    protected function requireAdmin()
    {
        $this->requireLogin();
        if ($_SESSION['role'] !== 'admin') {
            $this->setFlash('error', 'Halaman tersebut khusus untuk Administrator.');
            $this->redirect('index.php?c=deposit&a=dashboard');
        }
    }

    protected function requireNasabah()
    {
        $this->requireLogin();
        if ($_SESSION['role'] !== 'nasabah') {
            $this->setFlash('error', 'Halaman tersebut khusus untuk Nasabah.');
            $this->redirect('index.php?c=transaction&a=dashboard');
        }
    }

    protected function redirect($path)
    {
        header('Location: ' . BASE_URL . '/' . ltrim($path, '/'));
        exit;
    }

    protected function setFlash($type, $message)
    {
        $_SESSION['flash'] = array('type' => $type, 'message' => $message);
    }

    protected function getFlash()
    {
        if (!isset($_SESSION['flash'])) return null;
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
}
