<?php
// Controller untuk menangani proses login, registrasi nasabah, dan logout

class AuthController extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = $this->model('User');
    }

    public function login()
    {
        if (!empty($_SESSION['user_id'])) {
            if ($_SESSION['role'] === 'admin') {
                $this->redirect('index.php?c=transaction&a=dashboard');
            }
            $this->redirect('index.php?c=deposit&a=dashboard');
        }

        $rememberedUsername = '';
        if (isset($_COOKIE['remember_username'])) {
            $rememberedUsername = trim((string)$_COOKIE['remember_username']);
        }

        $this->view('auth/login', array(
            'title'               => 'Masuk ke Akun — BOWO',
            'remembered_username' => $rememberedUsername,
            'error'               => '',
            'flash'               => $this->getFlash()
        ));
    }

    public function authenticate()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?c=auth&a=login');
        }

        $identity = trim(isset($_POST['identity']) ? (string)$_POST['identity'] : '');
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        if ($identity === '' || $password === '') {
            $this->view('auth/login', array(
                'title'               => 'Masuk ke Akun — BOWO',
                'remembered_username' => $identity,
                'error'               => 'Username/email dan kata sandi wajib diisi.',
                'flash'               => null
            ));
            return;
        }

        $user = $this->userModel->findByIdentity($identity);
        if (!$user || !password_verify($password, $user['password'])) {
            $this->view('auth/login', array(
                'title'               => 'Masuk ke Akun — BOWO',
                'remembered_username' => $identity,
                'error'               => 'Username/email atau kata sandi yang dimasukkan salah.',
                'flash'               => null
            ));
            return;
        }

        $_SESSION['user_id']  = (int)$user['id'];
        $_SESSION['name']     = $user['name'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role']     = $user['role'];

        if (!empty($_POST['remember'])) {
            setcookie('remember_username', $user['username'], time() + (86400 * 30), '/');
        } else {
            setcookie('remember_username', '', time() - 3600, '/');
        }

        $this->setFlash('success', 'Selamat datang kembali, ' . $user['name'] . '!');
        if ($user['role'] === 'admin') {
            $this->redirect('index.php?c=transaction&a=dashboard');
        }
        $this->redirect('index.php?c=deposit&a=dashboard');
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        session_start();
        $this->setFlash('success', 'Anda telah berhasil keluar dari sistem.');
        $this->redirect('index.php?c=auth&a=login');
    }
}