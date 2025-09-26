<?php
class UuDaiController extends BaseController 
{
    private $db;
    private $uuDaiModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->uuDaiModel = new UuDaiModel($this->db);
    }

    public function index() 
    {
        $maCoSo = $_GET['coso'] ?? 21;
        $uuDais = $this->uuDaiModel->findUuDaisByCoSo($maCoSo);
        $branches = $this->getBranches();
        $stats = $this->uuDaiModel->getUuDaiStatsByCoSo($maCoSo);

        $this->render('uudai/index', [
            'uuDais' => $uuDais,
            'branches' => $branches,
            'stats' => $stats,
            'maCoSo' => $maCoSo
        ]);
    }

    // Các phương thức khác cũng đổi tên tương tự...
    public function create() { }
    public function store() { }
    public function edit() { }
    public function update() { }
    public function delete() { }
}
?>