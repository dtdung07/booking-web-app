<?php
/*
File check_booking_status.php
API endpoint để check trạng thái thanh toán booking
*/

// Include config database
require_once __DIR__ . '/config/database.php';

// Chỉ cho phép POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    die();
}

// Kiểm tra booking_id
if (!isset($_POST['booking_id']) || !is_numeric($_POST['booking_id'])) {
    echo json_encode(['error' => 'Missing or invalid booking_id']);
    die();
}

$bookingId = $_POST['booking_id'];

try {
    // Kết nối database
    $database = new Database();
    $db = $database->getConnection();
    
    // Lấy trạng thái booking
    $query = "SELECT TrangThai FROM dondatban WHERE MaDon = :bookingId";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':bookingId', $bookingId, PDO::PARAM_INT);
    $stmt->execute();
    
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$booking) {
        echo json_encode(['payment_status' => 'booking_not_found']);
        die();
    }
    
    // Trả về trạng thái thanh toán
    $paymentStatus = ($booking['TrangThai'] === 'da_xac_nhan') ? 'Paid' : 'Unpaid';
    echo json_encode(['payment_status' => $paymentStatus]);
    
} catch (Exception $e) {
    error_log("Error checking booking status: " . $e->getMessage());
    echo json_encode(['error' => 'Database error']);
}

?>
