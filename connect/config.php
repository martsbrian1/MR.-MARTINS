<?php
// config.php - set DB credentials
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'eagle_cosmetics';

function db_connect() {
    global $DB_HOST, $DB_USER, $DB_PASS, $DB_NAME;
    $conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
    if (!$conn) die('DB Connect Error: '.mysqli_connect_error());
    mysqli_set_charset($conn, 'utf8mb4');
    return $conn;
}

$conn = db_connect(); // ✅ VERY IMPORTANT

define('UPLOAD_DIR', __DIR__ . '/cus_pix/');
define('UPLOAD_URL', 'cus_pix/');

if (session_status() === PHP_SESSION_NONE) session_start();
?>

