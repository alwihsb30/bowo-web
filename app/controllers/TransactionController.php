<?php
// Controller untuk verifikasi admin, mutasi saldo nasabah, laporan, dan ekspor CSV

class TransactionController extends Controller
{
    private $transactionModel;
    private $depositModel;
    private $userModel;

    public function __construct()
    {
        $this->transactionModel = $this->model('Transaction');
        $this->depositModel     = $this->model('Deposit');
        $this->userModel        = $this->model('User');
    }

    public function dashboard()
    {
        $this->requireAdmin();

        $this->view('admin/dashboard', array(
            'title'   => 'Dashboard Verifikasi Admin — BOWO',
            'pending' => $this->depositModel->getPendingWithDetails(),
            'stats'   => array(
                'pending' => $this->depositModel->countByStatus('menunggu'),
                'weight'  => $this->depositModel->getTotalVerifiedWeightAll(),
                'value'   => $this->depositModel->getTotalVerifiedValueAll()
            ),
            'flash'   => $this->getFlash()
        ));
    }

    public function verify()
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?c=transaction&a=dashboard');
        }

        $depositId = isset($_POST['deposit_id']) ? (int)$_POST['deposit_id'] : 0;
        $status    = 'ditolak';
        if (isset($_POST['action']) && $_POST['action'] === 'diverifikasi') {
            $status = 'diverifikasi';
        }

        $success = $this->transactionModel->processDepositVerification($depositId, (int)$_SESSION['user_id'], $status);

        if (!$success) {
            $this->setFlash('error', 'Data setoran gagal diproses atau sudah pernah diverifikasi.');
            $this->redirect('index.php?c=transaction&a=dashboard');
        }

        if ($status === 'diverifikasi') {
            $this->setFlash('success', 'Setoran berhasil disetujui. Saldo dan poin nasabah telah ditambahkan.');
        } else {
            $this->setFlash('success', 'Pengajuan setoran sampah telah ditolak.');
        }

        $this->redirect('index.php?c=transaction&a=dashboard');
    }

    public function history()
    {
        $this->requireLogin();

        $userId = (int)$_SESSION['user_id'];
        $this->view('user/riwayat', array(
            'title'           => 'Riwayat Mutasi Saldo — BOWO',
            'user'            => $this->userModel->findById($userId),
            'transactions'    => $this->transactionModel->getByUserWithDetails($userId),
            'total_masuk'     => $this->transactionModel->getTotalAmountByUserAndType($userId, 'setoran_masuk'),
            'total_penarikan' => $this->transactionModel->getTotalAmountByUserAndType($userId, 'penarikan'),
            'flash'           => $this->getFlash()
        ));
    }

    public function reports()
    {
        $this->requireAdmin();

        $this->view('admin/reports', array(
            'title'         => 'Laporan Rekapitulasi — Admin BOWO',
            'total_volume'  => $this->depositModel->getTotalVerifiedWeightAll(),
            'total_cashout' => $this->depositModel->getTotalVerifiedValueAll(),
            'reports'       => $this->depositModel->getAllWithDetails(),
            'summary'       => array('users' => $this->userModel->countNasabah())
        ));
    }

    public function exportCsv()
    {
        $this->requireAdmin();

        $reports  = $this->depositModel->getAllWithDetails();
        $fileName = 'laporan_bowo_' . date('Y-m-d') . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');

        $output = fopen('php://output', 'w');
        fputcsv($output, array('ID', 'Tanggal', 'Nasabah', 'Jenis Sampah', 'Kategori', 'Berat (Kg)', 'Total Nilai (Rp)', 'Poin', 'Status'));

        foreach ($reports as $row) {
            fputcsv($output, array(
                $row['id'],
                $row['deposit_date'],
                $row['user_name'],
                $row['waste_name'],
                $row['waste_category'],
                $row['weight'],
                $row['total_value'],
                $row['points'],
                $row['status']
            ));
        }

        fclose($output);
        exit;
    }
}
