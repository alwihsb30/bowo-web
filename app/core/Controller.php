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