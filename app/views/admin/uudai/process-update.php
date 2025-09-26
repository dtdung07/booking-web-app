<?php
include __DIR__ . "/connect.php"; // Kết nối CSDL

// Kiểm tra xem các trường cần thiết cho Ưu đãi đã được gửi đi hay chưa
if(
    !empty($_GET['MaUuDai']) && // Cần có ID ưu đãi để biết bản ghi nào cần cập nhật
    !empty($_POST['TenUuDai']) &&
    !empty($_POST['GiaTri']) &&
    !empty($_POST['NgayBatDau']) &&
    !empty($_POST['NgayKetThuc']) &&
    !empty($_POST['AnhUuDai']) &&
    !empty($_POST['MoTaUuDai'])
) {
    // Lấy dữ liệu từ GET và POST (sử dụng mysqli_real_escape_string để an toàn hơn)
    $mauudai = mysqli_real_escape_string($conn, $_GET['MaUuDai']);
    $ten_uudai = mysqli_real_escape_string($conn, $_POST['TenUuDai']);
    $gia_tri = mysqli_real_escape_string($conn, $_POST['GiaTri']);
    $ngay_bat_dau = mysqli_real_escape_string($conn, $_POST['NgayBatDau']);
    $ngay_ket_thuc = mysqli_real_escape_string($conn, $_POST['NgayKetThuc']);
    $anh_uudai = mysqli_real_escape_string($conn, $_POST['AnhUuDai']);
    $mo_ta = mysqli_real_escape_string($conn, $_POST['MoTaUuDai']);

    // Câu lệnh SQL UPDATE bảng 'uudai'
    $sql = "UPDATE `uudai` SET 
            `TenUuDai`='$ten_uudai', 
            `GiaTri`='$gia_tri', 
            `NgayBatDau`='$ngay_bat_dau', 
            `NgayKetThuc`='$ngay_ket_thuc', 
            `AnhUuDai`='$anh_uudai', 
            `MoTaUuDai`='$mo_ta' 
            WHERE `MaUuDai`='$mauudai'";
    
    // Thực thi câu lệnh
    if(mysqli_query($conn, $sql)){
        // Chuyển hướng về trang quản lý Ưu đãi sau khi cập nhật thành công
        header("location: ?page=admin&section=uudai");
        exit(); // Dừng thực thi
    } else {
        echo "Lỗi cập nhật Ưu đãi: " . mysqli_error($conn);
    }
}
else{
    echo "Vui lòng nhập đầy đủ thông tin ưu đãi và ID để cập nhật!";
}
?>