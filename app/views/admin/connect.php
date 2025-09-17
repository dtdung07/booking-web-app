<?php
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $database = 'booking_restaurant';
    $port = '3306';
    $conn = mysqli_connect($host, $user, $pass, $database, $port);
    mysqli_set_charset($conn, "utf8");
?>