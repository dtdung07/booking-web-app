<?php
require_once __DIR__ . '/../models/BookingModel.php';

class BookingController extends BaseController 
{
    private $bookingModel;
    
    public function __construct() {
        // Khởi tạo kết nối database
        require_once __DIR__ . '/../../config/database.php';
        $database = new Database();
        $db = $database->getConnection();
        $this->bookingModel = new BookingModel($db);
    }
    
    public function index() 
    {
        $this->render('booking/index');
    }
    
    public function create() 
    {
        $this->render('booking/create');
    }
    
    public function store() 
    {
        // Xử lý lưu booking và chuyển đến thanh toán
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Lấy dữ liệu từ form
                $customerName = $_POST['customer_name'] ?? '';
                $customerPhone = $_POST['customer_phone'] ?? '';  
                $customerEmail = $_POST['customer_email'] ?? '';
                $maCoSo = $_POST['branch_id'] ?? 1;
                $soLuongKH = $_POST['guest_count'] ?? 1;
                $thoiGianBatDau = $_POST['booking_date'] . ' ' . $_POST['booking_time'];
                $ghiChu = $_POST['notes'] ?? '';
                
                // Tạo hoặc lấy thông tin khách hàng
                $maKH = $this->createOrGetCustomer($customerName, $customerPhone, $customerEmail);
                
                // Tạo booking với trạng thái chờ thanh toán
                $bookingId = $this->createBooking($maKH, $maCoSo, $soLuongKH, $thoiGianBatDau, $ghiChu);
                
                if ($bookingId) {
                    // Chuyển hướng đến trang thanh toán
                    $this->redirect("?page=booking&action=payment&id=" . $bookingId);
                } else {
                    throw new Exception('Không thể tạo booking');
                }
                
            } catch (Exception $e) {
                $this->render('booking/create', ['error' => $e->getMessage()]);
            }
        }
    }
    
    public function payment() 
    {
        $bookingId = $_GET['id'] ?? null;
        
        if (!$bookingId) {
            $this->redirect('?page=booking');
            return;
        }
        
        // Lấy thông tin booking
        $booking = $this->bookingModel->getBookingDetail($bookingId);
        
        if (!$booking || $booking['TrangThai'] !== 'cho_xac_nhan') {
            $this->redirect('?page=booking');
            return;
        }
        
        // Render trang thanh toán
        $this->render('booking/payment', ['booking' => $booking]);
    }
    
    public function checkPaymentStatus() 
    {
        // API endpoint để check trạng thái thanh toán
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }
        
        $bookingId = $_POST['booking_id'] ?? null;
        
        if (!$bookingId) {
            echo json_encode(['error' => 'Missing booking_id']);
            return;
        }
        
        $booking = $this->bookingModel->getById($bookingId);
        
        if (!$booking) {
            echo json_encode(['payment_status' => 'booking_not_found']);
            return;
        }
        
        $paymentStatus = ($booking->TrangThai === 'da_xac_nhan') ? 'Paid' : 'Unpaid';
        echo json_encode(['payment_status' => $paymentStatus]);
    }
    
    public function success() 
    {
        $bookingId = $_GET['id'] ?? null;
        $booking = null;
        
        if ($bookingId) {
            $booking = $this->bookingModel->getBookingDetail($bookingId);
        }
        
        $this->render('booking/success', ['booking' => $booking]);
    }
    
    private function createOrGetCustomer($name, $phone, $email) 
    {
        // Kiểm tra khách hàng đã tồn tại chưa
        require_once __DIR__ . '/../../config/database.php';
        $database = new Database();
        $db = $database->getConnection();
        
        // Tìm theo số điện thoại hoặc email
        $query = "SELECT MaKH FROM khachhang WHERE SDT = :phone OR Email = :email LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($customer) {
            return $customer['MaKH'];
        }
        
        // Tạo khách hàng mới
        $query = "INSERT INTO khachhang (TenKH, SDT, Email) VALUES (:name, :phone, :email)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':email', $email);
        
        if ($stmt->execute()) {
            return $db->lastInsertId();
        }
        
        throw new Exception('Không thể tạo thông tin khách hàng');
    }
    
    private function createBooking($maKH, $maCoSo, $soLuongKH, $thoiGianBatDau, $ghiChu) 
    {
        require_once __DIR__ . '/../../config/database.php';
        $database = new Database();
        $db = $database->getConnection();
        
        $query = "INSERT INTO dondatban (MaKH, MaCoSo, SoLuongKH, ThoiGianBatDau, GhiChu, TrangThai, ThoiGianTao) 
                  VALUES (:maKH, :maCoSo, :soLuongKH, :thoiGianBatDau, :ghiChu, 'cho_xac_nhan', NOW())";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':maKH', $maKH);
        $stmt->bindParam(':maCoSo', $maCoSo);
        $stmt->bindParam(':soLuongKH', $soLuongKH);
        $stmt->bindParam(':thoiGianBatDau', $thoiGianBatDau);
        $stmt->bindParam(':ghiChu', $ghiChu);
        
        if ($stmt->execute()) {
            return $db->lastInsertId();
        }
        
        return false;
    }
}