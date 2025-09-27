<?php
include "connect.php"; // Kết nối CSDL

if (isset($_GET['MaUuDai'])) {
    $maUuDai = $_GET['MaUuDai'];

    $sql = "DELETE FROM uudai WHERE MaUD = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $maUuDai);
    
    if ($stmt->execute()) {
        header("Location: ?page=admin&section=uudai&status=delete_success");
    } else {
        header("Location: ?page=admin&section=uudai&status=delete_failed");
    }
    $stmt->close();
} else {
    header("Location: ?page=admin&section=uudai");
}
exit();
?>