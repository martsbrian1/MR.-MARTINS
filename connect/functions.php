<?php
require_once 'config.php';

function fetch_all_products() {
    $conn = db_connect();
    $sql = "SELECT * FROM products";
    $res = mysqli_query($conn, $sql);
    $products = [];
    while ($row = mysqli_fetch_assoc($res)) $products[] = $row;
    mysqli_close($conn);
    return $products;
}

function fetch_product($id) {
    $conn = db_connect();
    $sql = "SELECT * FROM products WHERE product_id  = ? LIMIT 1";
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