<?php
    // Thiết lập múi giờ Việt Nam
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $database = 'booking_restaurant';
    $port = '3306';

    $conn = mysqli_connect($host, $user, $pass, $database, $port);
    mysqli_set_charset($conn, "utf8");
    
    // Thiết lập múi giờ cho MySQL connection
    mysqli_query($conn, "SET time_zone = '+07:00'");

    // if (!$conn) {
    // die("Connection failed: " . mysqli_connect_error());
    // }
    // echo "Connected successfully";
?>