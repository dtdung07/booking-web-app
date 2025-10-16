<?php
/*
File: app/views# Chỉ cho phép POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Chỉ cho phép phương thức POST'
    ]);
    exit();
}lidate dữ liệu cơ bản
if (empty($customerName) || empty($customerPhone) || empty($branchId) || empty($bookingDate) || empty($bookingTime)) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Thiếu thông tin bắt buộc'
    ]);
    exit();
}

if (empty($cartItems)) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Giỏ hàng trống'
    ]);
    exit();
}ess-create.php
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
$totalAmount = intval($_POST['total_amount'] ?? 0);
$discountId = $_POST['discount_id'] ?? '';
$finalAmount = intval($_POST['final_amount'] ?? 0);
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
    
    // 2.1. Lưu mã ưu đãi (nếu có) vào đợn đặt bàn để webhook tính đúng số tiền
    if (!empty($discountId)) {
        $discountIdInt = intval($discountId);
        mysqli_query($conn, "UPDATE dondatban SET MaUD = '$discountIdInt' WHERE MaDon = '$bookingId'");
    }

    // 2.2. TÌM VÀ GÁN BÀN TRỐNG CHO BOOKING (THUẬT TOÁN MỚI)
    $assignedTable = findAndAssignTable($conn, $bookingId, $branchId, $guestCount, $bookingDateTime);
    if (!$assignedTable) {
        echo "Rất tiếc! Không còn bàn trống phù hợp với thời gian bạn chọn. Vui lòng chọn thời gian khác.";
        // Có thể xóa booking đã tạo nếu không tìm được bàn
        mysqli_query($conn, "DELETE FROM dondatban WHERE MaDon = '$bookingId'");
        exit();
    }

    // 3. Thêm món ăn vào booking
    addMenuItemsToBooking($conn, $bookingId, $branchId, $cartItems);
    
<<<<<<< HEAD
    // Trả về JSON response cho JavaScript
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'booking_id' => $bookingId,
        'message' => 'Đặt bàn thành công'
    ]);
=======
    // Quyết định số tiền thanh toán: ưu tiên final_amount nếu hợp lệ
    $payAmount = ($finalAmount > 0 && $finalAmount <= $totalAmount) ? $finalAmount : $totalAmount;

    // TODO: (khuyến nghị) lưu MaUD (mã giảm giá) vào cột MaUD của dondatban nếu cần
    // Có thể cập nhật sau khi tạo booking: UPDATE dondatban SET MaUD = ? WHERE MaDon = ?

    // Chuyển hướng đến trang thanh toán SEPAY với mã đơn và số tiền cần thanh toán
    header("Location: ../../../sepay/sepay_payment.php?booking_id={$bookingId}&amount={$payAmount}");
>>>>>>> main
    exit();
    
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
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

/**
 * THUẬT TOÁN TÌM BÀN TRỐNG VÀ GÁN CHO BOOKING
 * 
 * Function này sẽ:
 * 1. Tìm tất cả bàn của cơ sở có đủ sức chứa cho số lượng khách
 * 2. Tìm những bàn đã bị đặt trong khoảng thời gian xung đột (±2 giờ)
 * 3. Loại bỏ những bàn đã bị đặt ra khỏi danh sách
 * 4. Chọn bàn tốt nhất (sức chứa nhỏ nhất mà vẫn đủ chỗ)
 * 5. Gán bàn đó cho booking hiện tại
 * 
 * @param mysqli $conn - Kết nối database
 * @param int $bookingId - Mã đơn đặt bàn vừa tạo
 * @param int $branchId - Mã cơ sở
 * @param int $guestCount - Số lượng khách
 * @param string $bookingDateTime - Thời gian đặt bàn (Y-m-d H:i:s)
 * @return array|false - Trả về thông tin bàn đã gán hoặc false nếu không tìm được
 */
function findAndAssignTable($conn, $bookingId, $branchId, $guestCount, $bookingDateTime) {
    // BƯỚC 1: Tìm tất cả bàn của cơ sở có đủ sức chứa
    $availableTablesQuery = "
        SELECT MaBan, TenBan, SucChua 
        FROM ban 
        WHERE MaCoSo = '$branchId' 
        AND SucChua >= '$guestCount'
        ORDER BY SucChua ASC
    ";
    
    $result = mysqli_query($conn, $availableTablesQuery);
    if (!$result || mysqli_num_rows($result) == 0) {
        return false; // Không có bàn nào đủ sức chứa
    }
    
    // Lưu danh sách tất cả bàn có đủ sức chứa
    $allSuitableTables = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $allSuitableTables[] = $row;
    }
    
    // BƯỚC 2: Tìm những bàn đã bị đặt trong khoảng thời gian xung đột
    // Quy định: mỗi lần đặt bàn kéo dài 2 giờ
    // Thời gian xung đột = từ (giờ_đặt_mới - 2h) đến (giờ_đặt_mới + 2h)
    $timeStart = date('Y-m-d H:i:s', strtotime($bookingDateTime . ' -2 hours'));
    $timeEnd = date('Y-m-d H:i:s', strtotime($bookingDateTime . ' +2 hours'));
    
    $bookedTablesQuery = "
        SELECT DISTINCT ddb.MaBan 
        FROM dondatban d
        INNER JOIN dondatban_ban ddb ON d.MaDon = ddb.MaDon
        WHERE d.MaCoSo = '$branchId'
        AND d.TrangThai IN ('cho_xac_nhan', 'da_xac_nhan')
        AND (
            (d.ThoiGianBatDau >= '$timeStart' AND d.ThoiGianBatDau <= '$timeEnd')
            OR 
            (DATE_ADD(d.ThoiGianBatDau, INTERVAL 2 HOUR) >= '$timeStart' AND DATE_ADD(d.ThoiGianBatDau, INTERVAL 2 HOUR) <= '$timeEnd')
            OR
            (d.ThoiGianBatDau <= '$timeStart' AND DATE_ADD(d.ThoiGianBatDau, INTERVAL 2 HOUR) >= '$timeEnd')
        )
    ";
    
    $bookedResult = mysqli_query($conn, $bookedTablesQuery);
    $bookedTableIds = [];
    if ($bookedResult) {
        while ($row = mysqli_fetch_assoc($bookedResult)) {
            $bookedTableIds[] = $row['MaBan'];
        }
    }
    
    // BƯỚC 3: Loại bỏ những bàn đã bị đặt ra khỏi danh sách
    $availableTables = [];
    foreach ($allSuitableTables as $table) {
        if (!in_array($table['MaBan'], $bookedTableIds)) {
            $availableTables[] = $table;
        }
    }
    
    // Kiểm tra xem còn bàn trống không
    if (empty($availableTables)) {
        return false; // Hết bàn trống
    }
    
    // BƯỚC 4: Chọn bàn tốt nhất (đã được sắp xếp theo SucChua ASC ở BƯỚC 1)
    // Bàn đầu tiên trong danh sách sẽ là bàn có sức chứa nhỏ nhất mà vẫn đủ chỗ
    $bestTable = $availableTables[0];
    
    // BƯỚC 5: Gán bàn cho booking
    $assignTableQuery = "
        INSERT INTO dondatban_ban (MaDon, MaBan) 
        VALUES ('$bookingId', '{$bestTable['MaBan']}')
    ";
    
    if (mysqli_query($conn, $assignTableQuery)) {
        // Trả về thông tin bàn đã gán
        return [
            'MaBan' => $bestTable['MaBan'],
            'TenBan' => $bestTable['TenBan'],
            'SucChua' => $bestTable['SucChua'],
            'Message' => "Đã tự động gán bàn {$bestTable['TenBan']} (sức chứa {$bestTable['SucChua']} người) cho đơn đặt bàn của bạn."
        ];
    }
    
    return false; // Lỗi khi gán bàn
}

?>