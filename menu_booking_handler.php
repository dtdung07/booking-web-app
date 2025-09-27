<?php
/*
File menu_booking_handler.php
Xử lý tạo booking từ menu2 với món ăn đã chọn
*/

header('Content-Type: application/json');

// Chỉ cho phép POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    die();
}

// Include config database
require_once __DIR__ . '/config/database.php';

try {
    // Kết nối database
    $database = new Database();
    $db = $database->getConnection();
    
    // Lấy dữ liệu từ POST
    $customerName = $_POST['customer_name'] ?? '';
    $customerPhone = $_POST['customer_phone'] ?? '';
    $customerEmail = $_POST['customer_email'] ?? '';
    $branchId = $_POST['branch_id'] ?? '';
    $guestCount = $_POST['guest_count'] ?? 1;
    $bookingDate = $_POST['booking_date'] ?? '';
    $bookingTime = $_POST['booking_time'] ?? '';
    $notes = $_POST['notes'] ?? '';
    $totalAmount = $_POST['total_amount'] ?? 0;
    $cartItems = json_decode($_POST['cart_items'] ?? '[]', true);
    
    // Validate dữ liệu cơ bản
    if (empty($customerName) || empty($customerPhone) || empty($branchId) || empty($bookingDate) || empty($bookingTime)) {
        throw new Exception('Thiếu thông tin bắt buộc');
    }
    
    if (empty($cartItems)) {
        throw new Exception('Giỏ hàng trống');
    }
    
    // Bắt đầu transaction
    $db->beginTransaction();
    
    // 1. Tạo hoặc lấy thông tin khách hàng
    $maKH = createOrGetCustomer($db, $customerName, $customerPhone, $customerEmail);
    
    // 2. Tạo booking
    $bookingDateTime = $bookingDate . ' ' . $bookingTime;
    $bookingId = createBooking($db, $maKH, $branchId, $guestCount, $bookingDateTime, $notes);
    
    // 3. Thêm món ăn vào booking
    addMenuItemsToBooking($db, $bookingId, $branchId, $cartItems);
    
    // Commit transaction
    $db->commit();
    
    // Trả về kết quả thành công
    echo json_encode([
        'success' => true,
        'booking_id' => $bookingId,
        'message' => 'Tạo đặt bàn thành công'
    ]);
    
} catch (Exception $e) {
    // Rollback transaction nếu có lỗi
    if (isset($db) && $db->inTransaction()) {
        $db->rollBack();
    }
    
    error_log("Booking creation error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

// === CÁC HÀM HELPER ===

function createOrGetCustomer($db, $name, $phone, $email) {
    // Kiểm tra khách hàng đã tồn tại chưa
    $query = "SELECT MaKH FROM khachhang WHERE SDT = :phone LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':phone', $phone);
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

function createBooking($db, $maKH, $maCoSo, $soLuongKH, $thoiGianBatDau, $ghiChu) {
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
    
    throw new Exception('Không thể tạo đơn đặt bàn');
}

function addMenuItemsToBooking($db, $bookingId, $branchId, $cartItems) {
    $query = "INSERT INTO chitietdondatban (MaDon, MaMon, SoLuong, DonGia) VALUES (:maDon, :maMon, :soLuong, :donGia)";
    $stmt = $db->prepare($query);
    
    foreach ($cartItems as $item) {
        // Lấy giá hiện tại từ menu_coso
        $priceQuery = "SELECT Gia FROM menu_coso WHERE MaMon = :maMon AND MaCoSo = :maCoSo";
        $priceStmt = $db->prepare($priceQuery);
        $priceStmt->bindParam(':maMon', $item['id']);
        $priceStmt->bindParam(':maCoSo', $branchId);
        $priceStmt->execute();
        
        $priceResult = $priceStmt->fetch(PDO::FETCH_ASSOC);
        $currentPrice = $priceResult ? $priceResult['Gia'] : $item['price'];
        
        // Thêm món vào chi tiết đơn
        $stmt->bindParam(':maDon', $bookingId);
        $stmt->bindParam(':maMon', $item['id']);
        $stmt->bindParam(':soLuong', $item['quantity']);
        $stmt->bindParam(':donGia', $currentPrice);
        
        if (!$stmt->execute()) {
            throw new Exception('Không thể thêm món ăn: ' . $item['name']);
        }
    }
}

?>
