<?php

require_once __DIR__ . '/../models/TableStatusManager.php';
require_once __DIR__ . '/../../includes/BaseController.php';
require_once __DIR__ . '/AuthController.php';

class TableStatusController extends BaseController {
    private $tableStatusModel;
    private $authController;

    public function __construct() {
        // Khởi tạo Model và Auth Controller
        $this->tableStatusModel = new TableStatusManager();
        $this->authController = new AuthController();
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Hiển thị trang quản lý trạng thái bàn cho nhân viên
     */
    public function index() {
        // Kiểm tra quyền nhân viên
        $this->authController->requireNhanVien();
        
        // Lấy thông tin nhân viên từ session
        $nhanVien = $_SESSION['user'];
        $maCoSo = $nhanVien['MaCoSo'];
        
        // Khởi tạo các biến cần thiết
        $thoiGianBatDau = $_GET['thoiGianBatDau'] ?? date('Y-m-d H:i');
        $thoiGianKetThuc = $_GET['thoiGianKetThuc'] ?? date('Y-m-d H:i', strtotime('+2 hours'));
        
        // Lấy thông tin cơ sở của nhân viên
        $thongTinCoSo = $this->tableStatusModel->layThongTinCoSo($maCoSo);
        
        // Lấy danh sách bàn với trạng thái
        $banList = $this->tableStatusModel->layBanTheoCoSo($maCoSo, $thoiGianBatDau, $thoiGianKetThuc);
        
        // Tính toán thống kê
        $thongKe = $this->tinhThongKe($banList);
        
        // Truyền dữ liệu tới view
        $data = [
            'nhanVien' => $nhanVien,
            'thongTinCoSo' => $thongTinCoSo,
            'thoiGianBatDau' => $thoiGianBatDau,
            'thoiGianKetThuc' => $thoiGianKetThuc,
            'banList' => $banList,
            'thongKe' => $thongKe
        ];
        $this->loadView('nhanvien/table_status', $data);
    }

    /**
     * Cập nhật trạng thái bàn
     */
    public function updateStatus() {
        // Kiểm tra quyền nhân viên
        $this->authController->requireNhanVien();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?page=nhanvien&action=table_status');
            return;
        }
        
        // Lấy thông tin nhân viên từ session
        $nhanVien = $_SESSION['user'];
        $maCoSoNhanVien = $nhanVien['MaCoSo'];
        
        // Lấy dữ liệu từ form
        $maBan = (int)($_POST['maBan'] ?? 0);
        $thoiGianBatDau = $_POST['thoiGianBatDau'] ?? '';
        $thoiGianKetThuc = $_POST['thoiGianKetThuc'] ?? '';
        $trangThai = $_POST['trangThai'] ?? '';
        
        // Validate dữ liệu
        if ($maBan <= 0 || empty($thoiGianBatDau) || empty($thoiGianKetThuc) || empty($trangThai)) {
            $_SESSION['error_message'] = 'Dữ liệu không hợp lệ!';
            $this->redirectBack();
            return;
        }
        
        // Kiểm tra bàn có thuộc cơ sở của nhân viên không
        $banInfo = $this->tableStatusModel->layThongTinBan($maBan);
        if (!$banInfo || $banInfo['MaCoSo'] != $maCoSoNhanVien) {
            $_SESSION['error_message'] = 'Bạn không có quyền thao tác với bàn này!';
            $this->redirectBack();
            return;
        }
        
        // Thực hiện cập nhật
        $result = $this->tableStatusModel->capNhatTrangThaiBan($maBan, $thoiGianBatDau, $thoiGianKetThuc, $trangThai);
        
        if ($result) {
            $_SESSION['success_message'] = 'Cập nhật trạng thái bàn thành công!';
        } else {
            $_SESSION['error_message'] = 'Có lỗi xảy ra khi cập nhật trạng thái bàn!';
        }
        
        // Redirect về trang quản lý với tham số thời gian
        $redirectUrl = 'index.php?page=nhanvien&action=table_status&thoiGianBatDau=' . urlencode($thoiGianBatDau) . '&thoiGianKetThuc=' . urlencode($thoiGianKetThuc);
        $this->redirect($redirectUrl);
    }

    /**
     * Lấy chi tiết bàn (AJAX)
     */
    public function getTableDetails() {
        // Kiểm tra quyền nhân viên
        $this->authController->requireNhanVien();
        
        $maBan = (int)($_GET['maBan'] ?? 0);
        
        if ($maBan <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Mã bàn không hợp lệ']);
            return;
        }
        
        // Lấy thông tin nhân viên từ session
        $nhanVien = $_SESSION['user'];
        $maCoSoNhanVien = $nhanVien['MaCoSo'];
        
        // Lấy thông tin bàn
        $banInfo = $this->tableStatusModel->layThongTinBanChiTiet($maBan);
        
        // Kiểm tra quyền truy cập
        if (!$banInfo || $banInfo['MaCoSo'] != $maCoSoNhanVien) {
            http_response_code(403);
            echo json_encode(['error' => 'Bạn không có quyền xem thông tin bàn này']);
            return;
        }
        
        header('Content-Type: application/json');
        echo json_encode($banInfo);
    }

    /**
     * Tính toán thống kê bàn
     */
    private function tinhThongKe($banList) {
        $tongBan = count($banList);
        $banTrong = count(array_filter($banList, function($ban) { 
            return $ban['TrangThai'] == 'trong'; 
        }));
        $banDaDat = $tongBan - $banTrong;
        
        return [
            'tongBan' => $tongBan,
            'banTrong' => $banTrong,
            'banDaDat' => $banDaDat
        ];
    }

    /**
     * Redirect về trang trước đó với tham số
     */
    private function redirectBack() {
        $thoiGianBatDau = $_POST['thoiGianBatDau'] ?? date('Y-m-d H:i');
        $thoiGianKetThuc = $_POST['thoiGianKetThuc'] ?? date('Y-m-d H:i', strtotime('+2 hours'));
        
        $redirectUrl = 'index.php?page=nhanvien&action=table_status&thoiGianBatDau=' . urlencode($thoiGianBatDau) . '&thoiGianKetThuc=' . urlencode($thoiGianKetThuc);
        $this->redirect($redirectUrl);
    }

    /**
     * Load view với dữ liệu
     */
    private function loadView($viewPath, $data = []) {
        // Extract dữ liệu để sử dụng trong view
        extract($data);
        
        // Include view file
        $viewFile = __DIR__ . '/../views/' . $viewPath . '.php';
        
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            throw new Exception("View file not found: " . $viewFile);
        }
    }
}