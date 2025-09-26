<?php
include "connect.php"; // Kết nối CSDL

// Kiểm tra xem các trường cần thiết cho Ưu đãi đã được gửi đi hay chưa
if(
    !empty($_POST['TenUuDai']) &&
    !empty($_POST['GiaTri']) &&
    !empty($_POST['NgayBatDau']) &&
    !empty($_POST['NgayKetThuc']) &&
    !empty($_POST['AnhUuDai']) &&
    !empty($_POST['MoTaUuDai'])
) {
    // Lấy dữ liệu từ POST
    $ten_uudai = mysqli_real_escape_string($conn, $_POST['TenUuDai']);
    $gia_tri = mysqli_real_escape_string($conn, $_POST['GiaTri']);
    $ngay_bat_dau = mysqli_real_escape_string($conn, $_POST['NgayBatDau']);
    $ngay_ket_thuc = mysqli_real_escape_string($conn, $_POST['NgayKetThuc']);
    $anh_uudai = mysqli_real_escape_string($conn, $_POST['AnhUuDai']);
    $mo_ta = mysqli_real_escape_string($conn, $_POST['MoTaUuDai']);

    // Câu lệnh SQL INSERT vào bảng 'uudai'
    $sql = "INSERT INTO `uudai` (`TenUuDai`, `GiaTri`, `NgayBatDau`, `NgayKetThuc`, `AnhUuDai`, `MoTaUuDai`) 
            VALUES ('$ten_uudai', '$gia_tri', '$ngay_bat_dau', '$ngay_ket_thuc', '$anh_uudai', '$mo_ta')";

    // Thực thi câu lệnh
    if (mysqli_query($conn, $sql)) {
        // Chuyển hướng về trang quản lý Ưu đãi sau khi thêm thành công
        header("location: ?page=admin&section=uudai");
        exit(); // Dừng thực thi
    } else {
        // Thông báo lỗi nếu thực thi thất bại
        echo "Lỗi: " . $sql . "<br>" . mysqli_error($conn);
    }
} else {
    // Thông báo nếu thiếu thông tin
    echo "Vui lòng nhập đầy đủ thông tin cho Ưu đãi.";
}
?>