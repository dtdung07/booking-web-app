<?php
include "connect.php"; // Kết nối CSDL

// Kiểm tra xem tham số MaUuDai có được truyền qua URL hay không
if(isset($_GET['MaUuDai'])){
    // Lấy ID ưu đãi
    $id = mysqli_real_escape_string($conn, $_GET['MaUuDai']);

    // Câu lệnh SQL DELETE: Xóa ưu đãi trong bảng 'uudai'
    // Nếu ưu đãi có bảng liên quan (ví dụ: uudai_coso), bạn cần thêm lệnh DELETE tương ứng.
    // Hiện tại, chỉ xóa khỏi bảng chính 'uudai'.
    $sql = "DELETE FROM `uudai` WHERE MaUuDai = '$id'";

    // Thực thi câu lệnh
    if (mysqli_query($conn, $sql)) {
        // Chuyển hướng về trang quản lý Ưu đãi sau khi xóa thành công
        header("location: ?page=admin&section=uudai");
        exit(); // Dừng thực thi
    } else {
        // Thông báo lỗi nếu thực thi thất bại
        echo "Lỗi khi xóa ưu đãi: " . mysqli_error($conn);
    }
}
else{
    // Thông báo nếu thiếu thông tin
    echo "Vui lòng cung cấp mã ưu đãi để xóa.";
}
?>