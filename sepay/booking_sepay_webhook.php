<?php
/*
File booking_sepay_webhook.php
Webhook SePay cho hệ thống đặt bàn
Endpoint nhận webhook sẽ là: https://yourdomain.com/booking-web-app/sepay/booking_sepay_webhook.php
*/

// Include cấu hình database của hệ thống đặt bàn
include __DIR__ . '/../config/connect.php';

// Lấy dữ liệu từ webhook SePay
$data = json_decode(file_get_contents('php://input'));

if (!is_object($data)) {
    echo json_encode(['success' => false, 'message' => 'No data']);
    die('No data found!');
}

// Khởi tạo các biến từ webhook SePay
$gateway = $data->gateway;
$transaction_date = $data->transactionDate;  
$account_number = $data->accountNumber;
$sub_account = $data->subAccount;
$transfer_type = $data->transferType;
$transfer_amount = $data->transferAmount;
$accumulated = $data->accumulated;
$code = $data->code;
$transaction_content = $data->content;
$reference_number = $data->referenceCode;
$body = $data->description;

$amount_in = 0;
$amount_out = 0;

// Kiểm tra giao dịch tiền vào hay tiền ra
if ($transfer_type == "in") {
    $amount_in = $transfer_amount;
} else if ($transfer_type == "out") {
    $amount_out = $transfer_amount;
}

// Ghi log thông tin giao dịch vào file (thay vì database)
$logData = [
    'timestamp' => date('Y-m-d H:i:s'),
    'gateway' => $gateway,
    'transaction_date' => $transaction_date,
    'amount_in' => $amount_in,
    'content' => $transaction_content,
    'reference' => $reference_number
];

error_log("SePay Transaction: " . json_encode($logData));

// Chỉ xử lý giao dịch tiền vào
if ($transfer_type !== "in" || $amount_in <= 0) {
    echo json_encode(['success' => false, 'message' => 'Not an incoming transaction']);
    die();
}

// Tách mã đặt bàn từ nội dung giao dịch
// Định dạng: DH123 hoặc DB123 (DH/DB + số ID đặt bàn)
$regex = '/(?:DH|DB)(\d+)/';
preg_match($regex, $transaction_content, $matches);

if (empty($matches[1]) || !is_numeric($matches[1])) {
    echo json_encode(['success' => false, 'message' => 'Booking ID not found in transaction content']);
    die();
}

$bookingId = $matches[1];

// Kiểm tra đặt bàn có tồn tại và chưa thanh toán
$checkBookingQuery = "SELECT * FROM dondatban 
                     WHERE MaDon = '$bookingId' 
                     AND TrangThai = 'cho_xac_nhan'";

$result = mysqli_query($conn, $checkBookingQuery);
$booking = mysqli_fetch_assoc($result);

if (!$booking) {
    echo json_encode([
        'success' => false, 
        'message' => 'Booking not found or already processed',
        'booking_id' => $bookingId
    ]);
    die();
}

// Lấy tổng tiền món ăn của đơn đặt bàn
$totalQuery = "SELECT SUM(SoLuong * DonGia) as total_food FROM chitietdondatban WHERE MaDon = '$bookingId'";
$totalResult = mysqli_query($conn, $totalQuery);
$totalData = mysqli_fetch_assoc($totalResult);

$expectedAmount = $totalData['total_food'] ?? 0;

if ($amount_in < $expectedAmount) {
    echo json_encode([
        'success' => false, 
        'message' => 'Insufficient payment amount',
        'expected' => $expectedAmount,
        'received' => $amount_in,
        'booking_id' => $bookingId
    ]);
    die();
}

// Cập nhật trạng thái đặt bàn thành đã xác nhận (đã thanh toán)
$updateBookingQuery = "UPDATE dondatban 
                      SET TrangThai = 'da_xac_nhan' 
                      WHERE MaDon = '$bookingId'";

if (mysqli_query($conn, $updateBookingQuery)) {
    // Thêm log ghi chú về việc thanh toán
    $paymentNote = "\n[Thanh toán - " . date('d/m/Y H:i') . "]: Đã thanh toán " . number_format($amount_in) . "đ qua quét mã QR";
    $updateNoteQuery = "UPDATE dondatban 
                       SET GhiChu = CONCAT(IFNULL(GhiChu, ''), '$paymentNote')
                       WHERE MaDon = '$bookingId'";
    
    mysqli_query($conn, $updateNoteQuery);
    
    echo json_encode([
        'success' => true, 
        'message' => 'Booking payment confirmed successfully',
        'booking_id' => $bookingId,
        'amount_paid' => $amount_in
    ]);
    
} else {
    echo json_encode([
        'success' => false, 
        'message' => 'Failed to update booking status',
        'booking_id' => $bookingId
    ]);
}

?>
