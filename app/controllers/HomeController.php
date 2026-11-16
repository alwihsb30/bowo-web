<?php
// Controller halaman publik (Beranda, Katalog Sampah, dan Leaderboard)
class HomeController extends Controller
{
    private $userModel;
    private $wasteTypeModel;
    private $depositModel;

    public function __construct()
    {
        $this->userModel      = $this->model('User');
        $this->wasteTypeModel = $this->model('WasteType');
        $this->depositModel   = $this->model('Deposit');
    }

    public function index()
    {
        $this->view('home/index', array(
            'pageTitle'      => 'Beranda - BOWO Bank Sampah',
            'total_users'    => $this->userModel->countNasabah(),
            'total_waste'    => $this->depositModel->getTotalVerifiedWeightAll(),
            'total_deposits' => $this->depositModel->countByStatus('approved'),
            'waste_types'    => $this->wasteTypeModel->getActive()
        ));
    }
    public function leaderboard()
    {
        $this->view('home/leaderboard', array(
            'pageTitle' => 'Leaderboard Nasabah - BOWO',
            'leaders'   => $this->userModel->getLeaderboard()
        ));
    }
}
