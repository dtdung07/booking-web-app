<?php
/*
File invoice.php - Trang xem hóa đơn chi tiết cho khách hàng
URL: invoice.php?booking_id={booking_id}&token={security_token}
*/

// Include cấu hình database
include __DIR__ . '/../config/connect.php';

// Lấy tham số từ URL
$bookingId = $_GET['booking_id'] ?? null;
$token = $_GET['token'] ?? null;

if (!$bookingId || !is_numeric($bookingId)) {
    die('ID đơn đặt bàn không hợp lệ!');
}

// Lấy thông tin đặt bàn chi tiết
// Đoạn truy vấn SQL dưới đây lấy tất cả thông tin đơn đặt bàn đã thanh toán (trạng thái 'da_xac_nhan'), bao gồm:
// - Thông tin đơn từ dondatban (d.*)
// - Thông tin khách hàng (TênKH, SĐT, Email)
// - Thông tin cơ sở (Tên cơ sở, địa chỉ)
// - Danh sách bàn đã đặt (GROUP_CONCAT các bàn, định dạng: Tên bàn (sức chứa chỗ)), nếu đặt nhiều bàn sẽ nối bằng dấu phẩy
// - Thông tin ưu đãi áp dụng nếu có (Tên mã, giá trị giảm, loại giảm giá: phần trăm hoặc tiền trực tiếp)
$query = "SELECT d.*, kh.TenKH, kh.SDT, kh.Email, cs.TenCoSo, cs.DiaChi,
                 GROUP_CONCAT(CONCAT(b.TenBan, ' (', b.SucChua, ' chỗ)') SEPARATOR ', ') as DanhSachBan,
                 ud.TenMaUD, ud.GiaTriGiam, ud.LoaiGiamGia
          FROM dondatban d
          LEFT JOIN khachhang kh ON d.MaKH = kh.MaKH  
          LEFT JOIN coso cs ON d.MaCoSo = cs.MaCoSo
          LEFT JOIN dondatban_ban ddb ON d.MaDon = ddb.MaDon
          LEFT JOIN ban b ON ddb.MaBan = b.MaBan
          LEFT JOIN uudai ud ON d.MaUD = ud.MaUD
          WHERE d.MaDon = '$bookingId' AND d.TrangThai = 'da_xac_nhan'
          GROUP BY d.MaDon";
// End: Truy vấn trả về thông tin chi tiết hóa đơn đã thanh toán cho khách xem.

$result = mysqli_query($conn, $query);
$booking = mysqli_fetch_assoc($result);

if (!$booking) {
    die('Không tìm thấy đơn đặt bàn hoặc đơn chưa được thanh toán!');
}

// Lấy danh sách món ăn
// Đoạn code sau truy vấn danh sách các món ăn trong đơn đặt bàn có mã $bookingId.
// - Lấy tên món (m.TenMon), số lượng đặt (ct.SoLuong), đơn giá của từng món (ct.DonGia)
// - Tính thành tiền cho từng món (SoLuong * DonGia) đặt tên cột là ThanhTien
// - Dữ liệu này lấy từ bảng chi tiết đơn đặt bàn (chitietdondatban) kết hợp với bảng món ăn (monan) để lấy tên món ăn
// - Mỗi bản ghi là một món của đơn, sắp xếp theo tên món (ORDER BY m.TenMon)
$menuQuery = "SELECT m.TenMon, ct.SoLuong, ct.DonGia, (ct.SoLuong * ct.DonGia) as ThanhTien
              FROM chitietdondatban ct
              JOIN monan m ON ct.MaMon = m.MaMon
              WHERE ct.MaDon = '$bookingId'
              ORDER BY m.TenMon";

$menuResult = mysqli_query($conn, $menuQuery);
$menuItems = [];
$tongTienMonAn = 0;

while ($row = mysqli_fetch_assoc($menuResult)) {
    $menuItems[] = $row;
    $tongTienMonAn += $row['ThanhTien'];
}

// Tính toán giảm giá và tổng tiền
$giaTriGiamGia = 0;
if (!empty($booking['GiaTriGiam'])) {
    if ($booking['LoaiGiamGia'] === 'phantram') {
        $giaTriGiamGia = ($tongTienMonAn * $booking['GiaTriGiam']) / 100;
    } else {
        $giaTriGiamGia = $booking['GiaTriGiam'];
    }
    $giaTriGiamGia = min($giaTriGiamGia, $tongTienMonAn);
}

$tongTienThanhToan = $tongTienMonAn - $giaTriGiamGia;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn đặt bàn #DH<?= $booking['MaDon'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .invoice-container { max-width: 800px; margin: 2rem auto; background: white; border-radius: 15px; box-shadow: 0 0 20px rgba(0,0,0,0.1); }
        .invoice-header { background: linear-gradient(135deg, #1B4E30 0%, #21A256 100%); color: white; padding: 2rem; border-radius: 15px 15px 0 0; }
        .invoice-header h1 { font-size: 2rem; margin: 0; }
        .invoice-header p { margin: 0; opacity: 0.9; }
        .invoice-body { padding: 2rem; }
        .info-section { margin-bottom: 2rem; }
        .info-card { background: #f8f9fa; padding: 1.5rem; border-radius: 10px; border-left: 4px solid #21A256; }
        .table-custom { border-radius: 10px; overflow: hidden; }
        .table-custom th { background: #21A256; color: white; border: none; }
        .summary-card { background: linear-gradient(135deg, #f8f9fa, #e9ecef); padding: 1.5rem; border-radius: 10px; }
        .total-amount { font-size: 1.5rem; font-weight: bold; color: #1B4E30; }
        .success-badge { background: linear-gradient(135deg, #28a745, #20c997); color: white; padding: 0.5rem 1rem; border-radius: 25px; font-size: 0.9rem; }
        .btn-print { background: linear-gradient(135deg, #6c757d, #5a6268); border: none; }
        .btn-home { background: linear-gradient(135deg, #1B4E30, #21A256); border: none; }
        @media print {
            .no-print { display: none !important; }
            body { background: white; }
            .invoice-container { box-shadow: none; }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header hóa đơn -->
        <div class="invoice-header text-center">
            <h1><i class="fas fa-receipt me-3"></i>HÓA ĐƠN ĐẶT BÀN</h1>
            <p class="mb-0">Mã đơn: #DH<?= $booking['MaDon'] ?></p>
            <span class="success-badge mt-2 d-inline-block">
                <i class="fas fa-check-circle me-1"></i>Đã thanh toán
            </span>
        </div>

        <div class="invoice-body">
            <!-- Thông tin nhà hàng và khách hàng -->
            <div class="row info-section">
                <div class="col-md-6">
                    <div class="info-card">
                        <h5 class="text-primary mb-3"><i class="fas fa-store me-2"></i>Thông tin nhà hàng</h5>
                        <p class="mb-2"><strong><?= htmlspecialchars($booking['TenCoSo']) ?></strong></p>
                        <p class="mb-2"><i class="fas fa-map-marker-alt me-2"></i><?= htmlspecialchars($booking['DiaChi']) ?></p>
                        <p class="mb-2"><i class="fas fa-phone me-2"></i>Hotline: 0987.654.321</p>
                        <p class="mb-0"><i class="fas fa-envelope me-2"></i>contact@restaurant.com</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-card">
                        <h5 class="text-primary mb-3"><i class="fas fa-user me-2"></i>Thông tin khách hàng</h5>
                        <p class="mb-2"><strong><?= htmlspecialchars($booking['TenKH']) ?></strong></p>
                        <p class="mb-2"><i class="fas fa-phone me-2"></i><?= htmlspecialchars($booking['SDT']) ?></p>
                        <p class="mb-2"><i class="fas fa-envelope me-2"></i><?= htmlspecialchars($booking['Email']) ?></p>
                        <p class="mb-0"><i class="fas fa-users me-2"></i>Số người: <?= $booking['SoLuongKH'] ?></p>
                    </div>
                </div>
            </div>

            <!-- Thông tin đặt bàn -->
            <div class="info-section">
                <div class="info-card">
                    <h5 class="text-primary mb-3"><i class="fas fa-calendar-check me-2"></i>Thông tin đặt bàn</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><i class="fas fa-clock me-2"></i>Thời gian: <strong><?= date('d/m/Y H:i', strtotime($booking['ThoiGianBatDau'])) ?></strong></p>
                            <p class="mb-2"><i class="fas fa-chair me-2"></i>Bàn: <strong><?= htmlspecialchars($booking['DanhSachBan'] ?: 'Sẽ sắp xếp khi đến') ?></strong></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><i class="fas fa-calendar-plus me-2"></i>Đặt lúc: <?= date('d/m/Y H:i', strtotime($booking['ThoiGianTao'])) ?></p>
                        </div>
                    </div>
                    <?php if (!empty($booking['GhiChu'])): ?>
                    <div class="mt-3">
                        <p class="mb-0"><i class="fas fa-sticky-note me-2"></i><strong>Ghi chú:</strong> <?= nl2br(htmlspecialchars($booking['GhiChu'])) ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Danh sách món ăn -->
            <?php if (!empty($menuItems)): ?>
            <div class="info-section">
                <h5 class="text-primary mb-3"><i class="fas fa-utensils me-2"></i>Chi tiết món ăn</h5>
                <div class="table-responsive">
                    <table class="table table-custom">
                        <thead>
                            <tr>
                                <th>Tên món</th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-end">Đơn giá</th>
                                <th class="text-end">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($menuItems as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['TenMon']) ?></td>
                                <td class="text-center"><?= $item['SoLuong'] ?></td>
                                <td class="text-end"><?= number_format($item['DonGia']) ?>đ</td>
                                <td class="text-end"><?= number_format($item['ThanhTien']) ?>đ</td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>

            <!-- Tổng kết thanh toán -->
            <div class="summary-card">
                <h5 class="text-primary mb-3"><i class="fas fa-calculator me-2"></i>Tổng kết thanh toán</h5>
                
                <?php if (!empty($menuItems)): ?>
                <div class="d-flex justify-content-between mb-2">
                    <span>Tổng tiền món ăn:</span>
                    <span><?= number_format($tongTienMonAn) ?>đ</span>
                </div>
                <?php endif; ?>
                
                <?php if ($giaTriGiamGia > 0): ?>
                <div class="d-flex justify-content-between mb-2 text-success">
                    <span><i class="fas fa-tag me-1"></i>Giảm giá (<?= htmlspecialchars($booking['TenMaUD']) ?>):</span>
                    <span>-<?= number_format($giaTriGiamGia) ?>đ</span>
                </div>
                <?php endif; ?>
                
                <hr>
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Tổng thanh toán:</h5>
                    <div class="total-amount"><?= number_format($tongTienThanhToan) ?>đ</div>
                </div>
                
                <div class="text-center mt-3">
                    <span class="success-badge">
                        <i class="fas fa-check-circle me-1"></i>
                        Đã thanh toán bằng chuyển khoản ngân hàng
                    </span>
                </div>
            </div>

            <!-- Lời cảm ơn -->
            <div class="text-center mt-4 p-3" style="background: #f0f8ff; border-radius: 10px;">
                <h6 class="text-primary mb-2">
                    <i class="fas fa-heart me-2"></i>Cảm ơn bạn đã đặt bàn tại nhà hàng!
                </h6>
                <p class="mb-0 text-muted">Hẹn gặp lại bạn vào thời gian đã đặt. Chúc bạn có một bữa ăn ngon miệng!</p>
            </div>

            <!-- Nút hành động -->
            <div class="text-center mt-4 no-print">
                <button onclick="window.print()" class="btn btn-print text-white me-3">
                    <i class="fas fa-print me-2"></i>In hóa đơn
                </button>
                <a href="../index.php?page=menu&coso=<?= $booking['MaCoSo'] ?>" class="btn btn-home text-white">
                    <i class="fas fa-home me-2"></i>Về trang chủ
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
