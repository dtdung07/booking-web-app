<?php
/*
File check_booking_status.php
API endpoint để check trạng thái thanh toán booking
*/

// Include config database
include __DIR__ . '/../config/connect.php';

// Chỉ cho phép POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Phuong thuc khong duoc phep']);
    die();
}

// Kiểm tra booking_id
if (!isset($_POST['booking_id']) || !is_numeric($_POST['booking_id'])) {
    echo json_encode(['error' => 'Thieu hoac booking_id khong hop le']);
    die();
}

$bookingId = $_POST['booking_id'];

// Lấy trạng thái booking
$query = "SELECT TrangThai FROM dondatban WHERE MaDon = '$bookingId'";
$result = mysqli_query($conn, $query);
$booking = mysqli_fetch_assoc($result);

if (!$booking) {
    echo json_encode(['payment_status' => 'booking_not_found']);
    die();
}

// Trả về trạng thái thanh toán
$paymentStatus = ($booking['TrangThai'] === 'da_xac_nhan') ? 'Paid' : 'Unpaid';
echo json_encode(['payment_status' => $paymentStatus]);

?>
