<?php

require_once __DIR__ . '/../../config/database.php'; 
require_once __DIR__ . '/../models/NhanVienModel.php'; 
require_once __DIR__ . '/../models/BookingModel.php'; 
require_once __DIR__ . '/../models/BranchModel.php'; 
require_once __DIR__ . '/../../includes/BaseController.php'; 
require_once __DIR__ . '/AuthController.php'; 

class NhanVienController extends BaseController 
{
    private $nhanVienModel;
    private $bookingModel;
    private $branchModel;
    private $authController;
    private $db;

    public function __construct() {
        // Khởi tạo kết nối DB và các Model
        $database = new Database();
        $this->db = $database->getConnection();
        $this->nhanVienModel = new NhanVienModel($this->db);
        $this->bookingModel = new BookingModel($this->db);
        $this->branchModel = new BranchModel($this->db);
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
        
        $currentUser = $_SESSION['user'];
        $maCoSo = $currentUser['MaCoSo'];
        
        // Lấy thống kê dashboard
        $dashboardData = $this->getDashboardStatistics($maCoSo);
        
        // Xử lý section hiển thị
        $section = $_GET['section'] ?? 'overview';
        
        switch ($section) {
            case 'bookings':
                $bookingsData = $this->getBookingsList($maCoSo);
                break;
            case 'profile':
                $profileData = $this->getProfileData($currentUser['MaNV']);
                break;
            default:
                $section = 'overview';
                break;
        }
        
        // Truyền dữ liệu cho view
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
    error_log("\033[31mviewBookingDetail called----------------------------\033[0m");

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
                $_SESSION['error_message'] = 'Không tìm thấy đơn đặt bàn hoặc bạn không có quyền xem.';
                $this->redirect('index.php?page=nhanvien&action=dashboard&section=bookings');
                return;
            }

            // Lấy thông tin món ăn đã đặt
            $menuQuery = "SELECT 
                    m.TenMon, 
                    mc.Gia, 
                    dm.SoLuong, 
                    (mc.Gia * dm.SoLuong) as ThanhTien
                FROM chitietdondatban dm
                JOIN monan m ON dm.MaMon = m.MaMon
                JOIN menu_coso mc ON m.MaMon = mc.MaMon AND mc.MaCoSo = ?
                WHERE dm.MaDon = ?
                ORDER BY m.TenMon;
                ";
            
            $menuStmt = $conn->prepare($menuQuery);
            $menuStmt->bindParam(1, $currentUser['MaCoSo']);
            $menuStmt->bindParam(2, $maDon);
            $menuStmt->execute();
            
            $menuItems = $menuStmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Error loading booking detail: " . $e->getMessage());
            $_SESSION['error_message'] = 'Có lỗi xảy ra khi tải thông tin đơn đặt bàn.';
            $this->redirect('index.php?page=nhanvien&action=dashboard&section=bookings');
            return;
        }

        // Truyền dữ liệu cho view
        include __DIR__ . '/../views/nhanvien/booking_detail.php';
        exit;
    }


    private function create_bill(){
        include __DIR__ . '/../views/nhanvien/create_bill.php';
    }

    // Lấy thống kê dashboard
    private function getDashboardStatistics($maCoSo)
    {
        try {
            // Lấy thông tin cơ sở
            $coSoInfo = $this->branchModel->getById($maCoSo);
            
            // Lấy các thống kê booking
            $todayBookings = $this->bookingModel->countBookingsByBranch($maCoSo);
            $todayNewBookings = $this->bookingModel->countTodayBookingsByBranch($maCoSo);
            $pendingBookings = $this->bookingModel->countPendingBookingsByBranch($maCoSo);
            $confirmedBookings = $this->bookingModel->countConfirmedBookingsByBranch($maCoSo);
            
            return [
                'coSoInfo' => $coSoInfo,
                'todayBookings' => $todayBookings,
                'todayNewBookings' => $todayNewBookings,
                'pendingBookings' => $pendingBookings,
                'confirmedBookings' => $confirmedBookings
            ];
        } catch (Exception $e) {
            error_log("Error getting dashboard statistics: " . $e->getMessage());
            return [
                'coSoInfo' => 'hello',
                'todayBookings' => 0,
                'todayNewBookings' => 0,
                'pendingBookings' => 0,
                'confirmedBookings' => 0
            ];
        }
    }

    // Lấy danh sách đơn đặt bàn với phân trang và lọc
    private function getBookingsList($maCoSo)
    {
        try {
            // Phân trang
            $page = isset($_GET['booking_page']) ? (int)$_GET['booking_page'] : 1;
            $limit = 10;
            $offset = ($page - 1) * $limit;
            
            // Các filter
            $statusFilter = $_GET['status_filter'] ?? 'all';
            $timeFilter = $_GET['time_filter'] ?? 'hom_nay';
            $searchKeyword = $_GET['search'] ?? '';
            
            // Lấy danh sách booking
            $bookings = $this->bookingModel->getBookingsByBranch(
                $maCoSo, 
                $limit, 
                $offset, 
                $statusFilter, 
                $timeFilter, 
                $searchKeyword
            );
            
            // Đếm tổng số
            $totalBookings = $this->bookingModel->countBookingsByBranchWithFilter(
                $maCoSo, 
                $statusFilter, 
                $timeFilter, 
                $searchKeyword
            );
            
            $totalPages = ceil($totalBookings / $limit);
            
            return [
                'bookingsList' => $bookings,
                'totalBookings' => $totalBookings,
                'totalPages' => $totalPages,
                'currentPage' => $page,
                'limit' => $limit
            ];
        } catch (Exception $e) {
            error_log("Error getting bookings list: " . $e->getMessage());
            return [
                'bookingsList' => [],
                'totalBookings' => 0,
                'totalPages' => 0,
                'currentPage' => 1,
                'limit' => 10
            ];
        }
    }

    // Lấy thông tin profile nhân viên
    private function getProfileData($maNV)
    {
        try {
            $nhanVien = $this->nhanVienModel->getById($maNV);
            if ($nhanVien) {
                return $this->nhanVienModel->toArray();
            }
            return null;
        } catch (Exception $e) {
            error_log("Error getting profile data: " . $e->getMessage());
            return null;
        }
    }
}