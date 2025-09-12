<?php
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $database = 'booking_restaurant';
    $port = '3306';

    $conn = mysqli_connect($host, $user, $pass, $database, $port);
    mysqli_set_charset($conn, "utf8");

    // if (!$conn) {
    // die("Connection failed: " . mysqli_connect_error());
    // }
    // echo "Connected successfully";
?>
