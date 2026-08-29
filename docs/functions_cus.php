<?php
require_once '../connect/config.php';
function fetch_all_customers() {
    $conn = db_connect();
    $sql = "SELECT * FROM customers ORDER BY customer_id";
    $res = mysqli_query($conn, $sql);
    $customers = [];
    while ($row = mysqli_fetch_assoc($res)) $customers[] = $row;
    mysqli_close($conn);
    return $customers;
}

function fetch_customers($id) {
    $conn = db_connect();
    $sql = "SELECT * FROM customers WHERE customer_id = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $row;
}

function format_money($v) { return number_format((float)$v, 2); }

?>