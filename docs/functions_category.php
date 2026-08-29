<?php
require_once '../connect/config.php';
function fetch_all_categories() {
    $conn = db_connect();
    $sql = "SELECT * FROM categories ORDER BY name";
    $res = mysqli_query($conn, $sql);
    $category = [];
    while ($row = mysqli_fetch_assoc($res)) $category[] = $row;
    mysqli_close($conn);
    return $category;
}

function fetch_category($id) {
    $conn = db_connect();
    $sql = "SELECT * FROM categories WHERE category_id = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $row;
}

//function format_money($v) { return number_format((float)$v, 2); }

?>