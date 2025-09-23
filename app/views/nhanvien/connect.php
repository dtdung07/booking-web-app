<?php
    $host = 'db';
    $user = 'root';
    $pass = 'rootpassword';
    $database = 'booking_restaurant';
    $port = '3306';
    $conn = mysqli_connect($host, $user, $pass, $database, $port);
    mysqli_set_charset($conn, "utf8");
?>