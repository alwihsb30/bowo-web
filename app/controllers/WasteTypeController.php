<?php
// Controller untuk mengelola jenis sampah (Admin) dan pencarian AJAX

class WasteTypeController extends Controller
{
    private $wasteTypeModel;

    public function __construct()
    {
        $this->wasteTypeModel = $this->model('WasteType');
    }

    public function index()
    {
        $this->requireAdmin();

        $editItem = null;
        if (!empty($_GET['edit_id'])) {
            $editItem = $this->wasteTypeModel->findById((int)$_GET['edit_id']);
        }

        $this->view('admin/waste_types', array(
            'title'     => 'Kelola Jenis & Harga Sampah — Admin BOWO',
            'types'     => $this->wasteTypeModel->getAll(),
            'edit_item' => $editItem,
            'flash'     => $this->getFlash()
        ));
    }

    public function store()
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?c=wastetype&a=index');
        }

        $name        = trim(isset($_POST['name']) ? (string)$_POST['name'] : '');
        $category    = trim(isset($_POST['category']) ? (string)$_POST['category'] : '');
        $pricePerKg  = isset($_POST['price_per_kg']) ? (float)$_POST['price_per_kg'] : 0;
        $description = trim(isset($_POST['description']) ? (string)$_POST['description'] : '');
        $status      = (isset($_POST['status']) && $_POST['status'] === 'inactive') ? 'inactive' : 'active';

        if ($name === '' || $category === '' || $pricePerKg <= 0) {
            $this->setFlash('error', 'Nama sampah, kategori, dan harga per kg wajib diisi dengan benar.');
            $this->redirect('index.php?c=wastetype&a=index');
        }

        $this->wasteTypeModel->createWasteType(array(
            'name'         => $name,
            'category'     => $category,
            'price_per_kg' => $pricePerKg,
            'description'  => $description,
            'status'       => $status
        ));

        $this->setFlash('success', 'Jenis sampah baru berhasil ditambahkan.');
        $this->redirect('index.php?c=wastetype&a=index');
    }
}