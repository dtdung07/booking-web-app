<?php

require_once __DIR__ . '/../../config/database.php'; 
require_once __DIR__ . '/../models/NhanVienModel.php'; 
require_once __DIR__ . '/../../includes/BaseController.php'; 
require_once __DIR__ . '/AuthController.php'; 

class NhanVienController extends BaseController 
{
    private $nhanVienModel;
    private $authController;

    public function __construct() {
        // Khởi tạo kết nối DB và NhanVienModel
        $database = new Database();
        $db = $database->getConnection();
        $this->nhanVienModel = new NhanVienModel($db);
        $this->authController = new AuthController();
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Hiển thị dashboard cho nhân viên
    public function dashboard()
    {
        // Kiểm tra quyền truy cập - chỉ cho phép nhân viên
        $this->authController->requireNhanVien();
        
        // Include view dashboard cho nhân viên
        include __DIR__ . '/../views/nhanvien/dashboard.php';
        exit;
    }

    // Hiển thị profile nhân viên
    public function profile()
    {
        $this->authController->requireNhanVien();
        
        $currentUser = $_SESSION['user'];
        
        // Lấy thông tin chi tiết nhân viên
        if ($this->nhanVienModel->getById($currentUser['MaNV'])) {
            $nhanVienData = $this->nhanVienModel->toArray();
        } else {
            $_SESSION['error_message'] = 'Không tìm thấy thông tin nhân viên.';
            $this->redirect('index.php?page=nhanvien&action=dashboard');
            return;
        }

        include __DIR__ . '/../views/nhanvien/profile.php';
        exit;
    }

    // Cập nhật thông tin cá nhân
    public function updateProfile()
    {
        $this->authController->requireNhanVien();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?page=nhanvien&action=profile');
            return;
        }

        $currentUser = $_SESSION['user'];
        
        // Lấy dữ liệu từ form
        $tenNhanVien = trim($_POST['TenNhanVien'] ?? '');
        $matKhauMoi = trim($_POST['MatKhauMoi'] ?? '');
        $xacNhanMatKhau = trim($_POST['XacNhanMatKhau'] ?? '');

        // Validate dữ liệu
        if (empty($tenNhanVien)) {
            $_SESSION['error_message'] = 'Tên nhân viên không được để trống.';
            $this->redirect('index.php?page=nhanvien&action=profile');
            return;
        }

        // Kiểm tra mật khẩu mới nếu có
        if (!empty($matKhauMoi)) {
            if (strlen($matKhauMoi) < 6) {
                $_SESSION['error_message'] = 'Mật khẩu mới phải có ít nhất 6 ký tự.';
                $this->redirect('index.php?page=nhanvien&action=profile');
                return;
            }

            if ($matKhauMoi !== $xacNhanMatKhau) {
                $_SESSION['error_message'] = 'Xác nhận mật khẩu không khớp.';
                $this->redirect('index.php?page=nhanvien&action=profile');
                return;
            }
        }

        // Cập nhật thông tin
        if ($this->nhanVienModel->getById($currentUser['MaNV'])) {
            $this->nhanVienModel->TenNhanVien = $tenNhanVien;
            
            // Cập nhật mật khẩu nếu có
            if (!empty($matKhauMoi)) {
                $this->nhanVienModel->MatKhau = password_hash($matKhauMoi, PASSWORD_DEFAULT);
            }

            if ($this->nhanVienModel->update()) {
                // Cập nhật session
                $_SESSION['user']['TenNhanVien'] = $tenNhanVien;
                $_SESSION['success_message'] = 'Cập nhật thông tin thành công!';
            } else {
                $_SESSION['error_message'] = 'Có lỗi xảy ra khi cập nhật thông tin.';
            }
        } else {
            $_SESSION['error_message'] = 'Không tìm thấy thông tin nhân viên.';
        }

        $this->redirect('index.php?page=nhanvien&action=profile');
    }

    // Xem danh sách đặt bàn (nếu có)
    public function bookings()
    {
        $this->authController->requireNhanVien();
        
        $currentUser = $_SESSION['user'];
        
        // TODO: Implement booking management for staff
        // For now, just show a placeholder page
        
        include __DIR__ . '/../views/nhanvien/bookings.php';
        exit;
    }

    // Cập nhật trạng thái đơn đặt bàn
    public function updateBookingStatus()
    {
        $this->authController->requireNhanVien();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error_message'] = 'Phương thức không hợp lệ.';
            $this->redirect('index.php?page=nhanvien&action=dashboard&section=bookings');
            return;
        }

        $maDon = $_POST['maDon'] ?? '';
        $status = $_POST['status'] ?? '';
        $reason = $_POST['reason'] ?? '';

        if (empty($maDon) || empty($status)) {
            $_SESSION['error_message'] = 'Thiếu thông tin cần thiết.';
            $this->redirect('index.php?page=nhanvien&action=dashboard&section=bookings');
            return;
        }

        // Kiểm tra trạng thái hợp lệ
        $validStatuses = ['cho_xac_nhan', 'da_xac_nhan', 'da_huy', 'hoan_thanh'];
        if (!in_array($status, $validStatuses)) {
            $_SESSION['error_message'] = 'Trạng thái không hợp lệ.';
            $this->redirect('index.php?page=nhanvien&action=dashboard&section=bookings');
            return;
        }

        // Cập nhật database
        try {
            $database = new Database();
            $conn = $database->getConnection();
            
            $currentUser = $_SESSION['user'];
            
            // Kiểm tra đơn đặt bàn có thuộc cơ sở của nhân viên không
            $checkQuery = "SELECT MaDon FROM dondatban WHERE MaDon = ? AND MaCoSo = ?";
            $checkStmt = $conn->prepare($checkQuery);
            $checkStmt->bindParam(1, $maDon);
            $checkStmt->bindParam(2, $currentUser['MaCoSo']);
            $checkStmt->execute();
            
            if ($checkStmt->rowCount() === 0) {
                $_SESSION['error_message'] = 'Không tìm thấy đơn đặt bàn hoặc bạn không có quyền cập nhật.';
                $this->redirect('index.php?page=nhanvien&action=dashboard&section=bookings');
                return;
            }

            // Cập nhật trạng thái
            $updateQuery = "UPDATE dondatban SET TrangThai = ?, MaNV_XacNhan = ?";
            $params = [$status, $currentUser['MaNV']];
            
            // Thêm ghi chú nếu có lý do hủy
            if (!empty($reason)) {
                $updateQuery .= ", GhiChu = CONCAT(IFNULL(GhiChu, ''), '\n[Lý do: " . date('d/m/Y H:i') . "] " . $reason . "')";
            }
            
            $updateQuery .= " WHERE MaDon = ?";
            $params[] = $maDon;

            $updateStmt = $conn->prepare($updateQuery);
            
            for ($i = 0; $i < count($params); $i++) {
                $updateStmt->bindParam($i + 1, $params[$i]);
            }
            
            if ($updateStmt->execute()) {
                // Thông báo thành công
                switch ($status) {
                    case 'da_xac_nhan':
                        $_SESSION['success_message'] = "Đã xác nhận đơn đặt bàn #{$maDon} thành công!";
                        break;
                    case 'da_huy':
                        $_SESSION['success_message'] = "Đã hủy đơn đặt bàn #{$maDon}!";
                        break;
                    case 'hoan_thanh':
                        $_SESSION['success_message'] = "Đã đánh dấu đơn đặt bàn #{$maDon} hoàn thành!";
                        break;
                    default:
                        $_SESSION['success_message'] = "Cập nhật trạng thái đơn đặt bàn #{$maDon} thành công!";
                }
            } else {
                $_SESSION['error_message'] = 'Có lỗi xảy ra khi cập nhật trạng thái.';
            }
        } catch (Exception $e) {
            error_log("Error updating booking status: " . $e->getMessage());
            $_SESSION['error_message'] = 'Có lỗi xảy ra. Vui lòng thử lại.';
        }

        // Kiểm tra redirect về trang detail
        // if (isset($_POST['redirect_to_detail']) && $_POST['redirect_to_detail'] == '1') {
        //     $this->redirect('index.php?page=nhanvien&action=viewBookingDetail&id=' . $maDon);
        // } else {
        //     $this->redirect('index.php?page=nhanvien&action=dashboard&section=bookings');
        // }
    }

    // Xem chi tiết đơn đặt bàn
    public function viewBookingDetail()
    {
    error_log("viewBookingDetail called----------------------------");
        $this->authController->requireNhanVien();
        
        $maDon = $_GET['id'] ?? '';
        
        if (empty($maDon)) {
            error_log("--------------------------------Mã đơn trống-------------");
            $_SESSION['error_message'] = 'Không tìm thấy đơn đặt bàn.';
            $this->redirect('index.php?page=nhanvien&action=dashboard&section=bookings');
            return;
        }

        try {
            $database = new Database();
            $conn = $database->getConnection();
            
            $currentUser = $_SESSION['user'];
            
            // Lấy thông tin chi tiết đơn đặt bàn
            $query = "SELECT d.*, kh.TenKH, kh.SDT, kh.Email as EmailKH,
                             GROUP_CONCAT(CONCAT(b.TenBan, ' (', b.SucChua, ' người)') SEPARATOR ', ') as DanhSachBan,
                             nv.TenNhanVien as NhanVienXacNhan,
                             cs.TenCoSo, cs.DiaChi as DiaChiCoSo
                      FROM dondatban d 
                      LEFT JOIN khachhang kh ON d.MaKH = kh.MaKH
                      LEFT JOIN dondatban_ban db ON d.MaDon = db.MaDon
                      LEFT JOIN ban b ON db.MaBan = b.MaBan
                      LEFT JOIN nhanvien nv ON d.MaNV_XacNhan = nv.MaNV
                      LEFT JOIN coso cs ON d.MaCoSo = cs.MaCoSo
                      WHERE d.MaDon = ? AND d.MaCoSo = ?
                      GROUP BY d.MaDon";
            
            $stmt = $conn->prepare($query);
            $stmt->bindParam(1, $maDon);
            $stmt->bindParam(2, $currentUser['MaCoSo']);
            $stmt->execute();
            
            $booking = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$booking) {
                $_SESSION['error_message'] = 'Không tìm thấy đơn đặt bàn.';
                $this->redirect('index.php?page=nhanvien&action=dashboard&section=bookings');
                return;
            }

            // Lấy danh sách món ăn đã đặt (nếu có)
            $menuQuery = "SELECT ct.*, m.TenMon, m.MoTa, mc.Gia as GiaMenu
                          FROM chitietdondatban ct
                          LEFT JOIN monan m ON ct.MaMon = m.MaMon
                          LEFT JOIN menu_coso mc ON ct.MaMon = mc.MaMon AND mc.MaCoSo = ?
                          WHERE ct.MaDon = ?
                          ORDER BY ct.MaMon";
            $menuStmt = $conn->prepare($menuQuery);
            $menuStmt->bindParam(1, $currentUser['MaCoSo']);
            $menuStmt->bindParam(2, $maDon);
            $menuStmt->execute();
            $menuItems = $menuStmt->fetchAll(PDO::FETCH_ASSOC);

            include __DIR__ . '/../views/nhanvien/booking_detail.php';
            exit;

        } catch (Exception $e) {
            error_log("Error viewing booking detail: " . $e->getMessage());
            $_SESSION['error_message'] = 'Có lỗi xảy ra khi tải thông tin đơn đặt bàn.';
            $this->redirect('index.php?page=nhanvien&action=dashboard&section=bookings');
        }
    }
}