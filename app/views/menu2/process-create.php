<?php
/*
File: app/views/menu2/process-create.php
Xử lý tạo đặt bàn từ form menu2 - sử dụng mysqli
*/

// Include config database
include __DIR__ . '../../../../config/connect.php';

// Chỉ cho phép POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /booking-web-app/index.php?page=menu2');
    exit();
}

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
    echo "Thiếu thông tin bắt buộc";
    exit();
}

if (empty($cartItems)) {
    echo "Giỏ hàng trống";
    exit();
}

try {
    // 1. Tạo hoặc lấy thông tin khách hàng
    $maKH = createOrGetCustomer($conn, $customerName, $customerPhone, $customerEmail);
    
    // 2. Tạo booking
    $bookingDateTime = $bookingDate . ' ' . $bookingTime;
    $bookingId = createBooking($conn, $maKH, $branchId, $guestCount, $bookingDateTime, $notes);
    
    // 3. Thêm món ăn vào booking
    addMenuItemsToBooking($conn, $bookingId, $branchId, $cartItems);
    
    // Chuyển hướng đến trang thanh toán SEPAY với mã đơn và tổng tiền
    header("Location: ../../../sepay/sepay_payment.php?booking_id={$bookingId}&amount={$totalAmount}");
    exit();
    
} catch (Exception $e) {
    echo "Lỗi: " . $e->getMessage();
    exit();
}

// === CÁC HÀM HELPER ===

function createOrGetCustomer($conn, $name, $phone, $email) {
    // Kiểm tra khách hàng đã tồn tại chưa
    $query = "SELECT MaKH FROM khachhang WHERE SDT = '$phone' LIMIT 1";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['MaKH'];
    }
    
    // Tạo khách hàng mới
    $query = "INSERT INTO khachhang (TenKH, SDT, Email) VALUES ('$name', '$phone', '$email')";
    if (mysqli_query($conn, $query)) {
        return mysqli_insert_id($conn);
    }
    
    throw new Exception('Không thể tạo thông tin khách hàng');
}

function createBooking($conn, $maKH, $maCoSo, $soLuongKH, $thoiGianBatDau, $ghiChu) {
    $query = "INSERT INTO dondatban (MaKH, MaCoSo, SoLuongKH, ThoiGianBatDau, GhiChu, TrangThai, ThoiGianTao) 
              VALUES ('$maKH', '$maCoSo', '$soLuongKH', '$thoiGianBatDau', '$ghiChu', 'cho_xac_nhan', NOW())";
    
    if (mysqli_query($conn, $query)) {
        return mysqli_insert_id($conn);
    }
    
    throw new Exception('Không thể tạo đơn đặt bàn');
}

function addMenuItemsToBooking($conn, $bookingId, $branchId, $cartItems) {
    foreach ($cartItems as $item) {
        // Lấy giá hiện tại từ menu_coso
        $priceQuery = "SELECT Gia FROM menu_coso WHERE MaMon = '{$item['id']}' AND MaCoSo = '$branchId'";
        $priceResult = mysqli_query($conn, $priceQuery);
        
        $currentPrice = $item['price']; // Mặc định dùng giá từ cart
        if ($priceResult && mysqli_num_rows($priceResult) > 0) {
            $priceRow = mysqli_fetch_assoc($priceResult);
            $currentPrice = $priceRow['Gia'];
        }
        
        // Thêm món vào chi tiết đơn
        $query = "INSERT INTO chitietdondatban (MaDon, MaMon, SoLuong, DonGia) VALUES ('$bookingId', '{$item['id']}', '{$item['quantity']}', '$currentPrice')";
        
        if (!mysqli_query($conn, $query)) {
            throw new Exception('Không thể thêm món ăn: ' . $item['name']);
        }
    }
}

?>