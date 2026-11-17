<?php
// Controller untuk mengelola dashboard nasabah, setor sampah, dan riwayat setoran

class DepositController extends Controller
{
    private $depositModel;
    private $wasteTypeModel;
    private $userModel;

    public function __construct()
    {
        $this->depositModel   = $this->model('Deposit');
        $this->wasteTypeModel = $this->model('WasteType');
        $this->userModel      = $this->model('User');
    }

    public function dashboard()
    {
        $this->requireNasabah();

        $userId      = (int)$_SESSION['user_id'];
        $allDeposits = $this->depositModel->getByUserWithWaste($userId);

        $this->view('user/dashboard', array(
            'title'           => 'Dashboard Nasabah — BOWO',
            'user'            => $this->userModel->findById($userId),
            'total_weight'    => $this->depositModel->getTotalVerifiedWeightByUser($userId),
            'recent_deposits' => array_slice($allDeposits, 0, 3),
            'flash'           => $this->getFlash()
        ));
    }

    public function create()
    {
        $this->requireNasabah();

        $this->view('user/setor', array(
            'title'       => 'Setor Sampah — BOWO',
            'waste_types' => $this->wasteTypeModel->getActive(),
            'flash'       => $this->getFlash()
        ));
    }

    public function store()
    {
        $this->requireNasabah();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?c=deposit&a=create');
        }

        $userId      = (int)$_SESSION['user_id'];
        $wasteTypeId = isset($_POST['waste_type_id']) ? (int)$_POST['waste_type_id'] : 0;
        $weight      = isset($_POST['weight']) ? (float)$_POST['weight'] : 0;
        $depositDate = trim(isset($_POST['deposit_date']) ? (string)$_POST['deposit_date'] : date('Y-m-d'));
        $notes       = trim(isset($_POST['notes']) ? (string)$_POST['notes'] : '');

        if ($weight <= 0) {
            $this->setFlash('error', 'Berat sampah harus lebih dari 0 kg.');
            $this->redirect('index.php?c=deposit&a=create');
        }

        $waste = $this->wasteTypeModel->findById($wasteTypeId);
        if (!$waste) {
            $this->setFlash('error', 'Jenis sampah yang dipilih tidak valid.');
            $this->redirect('index.php?c=deposit&a=create');
        }

        $photoPath = null;
        if (isset($_FILES['photo_proof']) && $_FILES['photo_proof']['error'] !== UPLOAD_ERR_NO_FILE) {
            $file = $_FILES['photo_proof'];

            if ($file['error'] !== UPLOAD_ERR_OK) {
                $this->setFlash('error', 'Gagal mengunggah file foto.');
                $this->redirect('index.php?c=deposit&a=create');
            }

            if ($file['size'] > 2 * 1024 * 1024) {
                $this->setFlash('error', 'Ukuran foto maksimal adalah 2 MB.');
                $this->redirect('index.php?c=deposit&a=create');
            }

            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, array('jpg', 'jpeg', 'png', 'webp'), true)) {
                $this->setFlash('error', 'Format foto harus JPG, JPEG, PNG, atau WEBP.');
                $this->redirect('index.php?c=deposit&a=create');
            }

            $fileName   = 'bukti_' . $userId . '_' . time() . '.' . $ext;
            $uploadPath = __DIR__ . '/../../public/uploads/' . $fileName;

            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                $photoPath = 'public/uploads/' . $fileName;
            }
        }

        $pricePerKg = (float)$waste['price_per_kg'];
        $totalValue = round($weight * $pricePerKg, 2);
        $points     = (int)ceil($weight * 10);

        $this->depositModel->createDeposit(array(
            'user_id'       => $userId,
            'waste_type_id' => $wasteTypeId,
            'weight'        => $weight,
            'price_per_kg'  => $pricePerKg,
            'total_value'   => $totalValue,
            'points'        => $points,
            'deposit_date'  => $depositDate,
            'notes'         => $notes,
            'photo_proof'   => $photoPath
        ));

        $this->setFlash('success', 'Pengajuan setoran sampah berhasil dikirim dan menunggu verifikasi Admin.');
        $this->redirect('index.php?c=deposit&a=history');
    }
}