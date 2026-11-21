<?php
// Controller untuk beranda publik dan papan peringkat Leaderboard

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
            'title'          => 'BOWO — Bank of Waste Online',
            'total_users'    => $this->userModel->countNasabah(),
            'total_waste'    => $this->depositModel->getTotalVerifiedWeightAll(),
            'total_deposits' => $this->depositModel->countByStatus('diverifikasi'),
            'top_wastes'     => $this->wasteTypeModel->getTopDeposited(3),
            'waste_types'    => $this->wasteTypeModel->getActive()
        ));
    }

    public function leaderboard()
    {
        $this->view('home/leaderboard', array(
            'title'    => 'Leaderboard Eco-Hero — BOWO',
            'rankings' => $this->userModel->getLeaderboard()
        ));
    }
}
