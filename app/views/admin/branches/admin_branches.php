<?php
// File admin để quản lý cơ sở
require_once __DIR__ . '/../../../../config/database.php';
require_once __DIR__ . '/../../../models/CoSo.php';

// Tạo class đơn giản để quản lý cơ sở
class AdminBranchController
{
    private $db;
    private $coSo;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->coSo = new CoSo($this->db);
    }

    public function admin()
    {
        // Render admin view trực tiếp
        include __DIR__ . '/index.php';
    }

    public function getAdminData()
    {
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            $stmt = $this->coSo->getAll();
            $branches = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode([
                'success' => true,
                'data' => $branches,
                'count' => count($branches)
            ], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Lỗi khi lấy dữ liệu: ' . $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    public function add()
    {
        header('Content-Type: application/json; charset=utf-8');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ']);
            return;
        }

        $tenCoSo = $_POST['tenCoSo'] ?? '';
        $diaChi = $_POST['diaChi'] ?? '';
        $dienThoai = $_POST['dienThoai'] ?? '';
        $anhUrl = $_POST['anhUrl'] ?? '';

        if (empty($tenCoSo) || empty($diaChi) || empty($dienThoai)) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin bắt buộc']);
            return;
        }

        try {
            $query = "INSERT INTO coso (TenCoSo, DiaChi, DienThoai, AnhUrl) VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $result = $stmt->execute([$tenCoSo, $diaChi, $dienThoai, $anhUrl]);

            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Thêm cơ sở thành công']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Lỗi khi thêm cơ sở']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Lỗi database: ' . $e->getMessage()]);
        }
    }

    public function update()
    {
        header('Content-Type: application/json; charset=utf-8');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ']);
            return;
        }

        $maCoSo = $_POST['maCoSo'] ?? '';
        $tenCoSo = $_POST['tenCoSo'] ?? '';
        $diaChi = $_POST['diaChi'] ?? '';
        $dienThoai = $_POST['dienThoai'] ?? '';
        $anhUrl = $_POST['anhUrl'] ?? '';

        if (empty($maCoSo) || empty($tenCoSo) || empty($diaChi) || empty($dienThoai)) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin bắt buộc']);
            return;
        }

        try {
            $query = "UPDATE coso SET TenCoSo = ?, DiaChi = ?, DienThoai = ?, AnhUrl = ? WHERE MaCoSo = ?";
            $stmt = $this->db->prepare($query);
            $result = $stmt->execute([$tenCoSo, $diaChi, $dienThoai, $anhUrl, $maCoSo]);

            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Cập nhật cơ sở thành công']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Lỗi khi cập nhật cơ sở']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Lỗi database: ' . $e->getMessage()]);
        }
    }

    public function delete()
    {
        header('Content-Type: application/json; charset=utf-8');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ']);
            return;
        }

        $maCoSo = $_POST['maCoSo'] ?? '';

        if (empty($maCoSo)) {
            echo json_encode(['success' => false, 'message' => 'Mã cơ sở không được để trống']);
            return;
        }

        try {
            $query = "DELETE FROM coso WHERE MaCoSo = ?";
            $stmt = $this->db->prepare($query);
            $result = $stmt->execute([$maCoSo]);

            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Xóa cơ sở thành công']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Lỗi khi xóa cơ sở']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Lỗi database: ' . $e->getMessage()]);
        }
    }
}

$controller = new AdminBranchController();

// Kiểm tra action từ URL
$action = $_GET['action'] ?? 'admin';

switch ($action) {
    case 'admin':
        $controller->admin();
        break;
    case 'get_data':
        $controller->getAdminData();
        break;
    case 'add':
        $controller->add();
        break;
    case 'update':
        $controller->update();
        break;
    case 'delete':
        $controller->delete();
        break;
    default:
        $controller->admin();
        break;
}
?>
