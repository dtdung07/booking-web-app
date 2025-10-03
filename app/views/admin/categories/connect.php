<?php
// Development: bật lỗi để debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Cấu hình DB — sửa theo database của bạn
$dbHost = getenv('DB_HOST') ?: '127.0.0.1';
$dbUser = getenv('DB_USER') ?: 'root';
$dbPass = getenv('DB_PASS') ?: '';
$dbName = getenv('DB_NAME') ?: 'your_database_name';
$dbCharset = 'utf8mb4';

// Kết nối mysqli an toàn
$mysqli = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
if ($mysqli->connect_errno) {
    // Hiện thông báo lỗi rõ ràng trong dev
    die("Database connection failed: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
}
$mysqli->set_charset($dbCharset);

// Chuẩn hóa biến $conn nếu code hiện tại dùng $conn
$conn = $mysqli;