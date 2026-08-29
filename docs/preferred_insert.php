Got it ✅ — you want the customer_accounts table to have:

opening_balance — the balance at the start of the account
current_balance — the live balance after transactions
customer_id — the link to the customer

In that case, the checkout script should only update current_balance (and possibly opening_balance if it’s a new account).

Updated Checkout Script for customer_accounts with opening_balance & current_balance
Php<?php
/****************************************************
 * POS Checkout Script - PHP + MySQLi (Procedural)
 * Updates customer_accounts with opening_balance & current_balance
 ****************************************************/

// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$db   = "pos_db";

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Validate POST data
if (
    !isset($_POST['items']) || 
    !is_array($_POST['items']) || 
    empty($_POST['items']) ||
    !isset($_POST['total_amount']) ||
    !is_numeric($_POST['total_amount']) ||
    !isset($_POST['customer_id']) ||
    !is_numeric($_POST['customer_id'])
) {
    die("Invalid checkout data.");
}

$customer_id = (int)$_POST['customer_id'];
$total_amount = (float)$_POST['total_amount'];

// Start transaction
mysqli_begin_transaction($conn);

try {
    // Insert into sales table
    $sql_sale = "INSERT INTO sales (sale_date, total_amount, customer_id) VALUES (NOW(), ?, ?)";
    $stmt_sale = mysqli_prepare($conn, $sql_sale);
    mysqli_stmt_bind_param($stmt_sale, "di", $total_amount, $customer_id);
    if (!mysqli_stmt_execute($stmt_sale)) {
        throw new Exception("Error inserting sale: " . mysqli_error($conn));
    }
    $sale_id = mysqli_insert_id($conn);
    mysqli_stmt_close($stmt_sale);

    // Prepare statements for sale_items and stock update
    $sql_item = "INSERT INTO sale_items (sale_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
    $stmt_item = mysqli_prepare($conn, $sql_item);

    $sql_stock = "UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?";
    $stmt_stock = mysqli_prepare($conn, $sql_stock);

    foreach ($_POST['items'] as $item) {
        if (
            !isset($item['product_id'], $item['quantity'], $item['price']) ||
            !is_numeric($item['product_id']) ||
            !is_numeric($item['quantity']) ||
            !is_numeric($item['price']) ||
            $item['quantity'] <= 0
        ) {
            throw new Exception("Invalid item data.");
        }

        $pid = (int)$item['product_id'];
        $qty = (int)$item['quantity'];
        $price = (float)$item['price'];

        // Insert sale item
        mysqli_stmt_bind_param($stmt_item, "iiid", $sale_id, $pid, $qty, $price);
        if (!mysqli_stmt_execute($stmt_item)) {
            throw new Exception("Error inserting sale item: " . mysqli_error($conn));
        }

        // Update stock
        mysqli_stmt_bind_param($stmt_stock, "iii", $qty, $pid, $qty);
        if (!mysqli_stmt_execute($stmt_stock) || mysqli_stmt_affected_rows($stmt_stock) === 0) {
            throw new Exception("Stock update failed for product ID $pid (insufficient stock).");
        }
    }

    // Check if customer account exists
    $sql_check = "SELECT opening_balance, current_balance FROM customer_accounts WHERE customer_id = ?";
    $stmt_check = mysqli_prepare($conn, $sql_check);
    mysqli_stmt_bind_param($stmt_check, "i", $customer_id);
    mysqli_stmt_execute($stmt_check);
    $result = mysqli_stmt_get_result($stmt_check);

    if ($row = mysqli_fetch_assoc($result)) {
        // Update current balance
        $new_balance = $row['current_balance'] + $total_amount;   //////
        $sql_update = "UPDATE customer_accounts SET current_balance = ? WHERE customer_id = ?";
        $stmt_update = mysqli_prepare($conn, $sql_update);
        mysqli_stmt_bind_param($stmt_update, "di", $new_balance, $customer_id);
        if (!mysqli_stmt_execute($stmt_update)) {
            throw new Exception("Failed to update customer balance.");
        }
        mysqli_stmt_close($stmt_update);
    } else {
        // Create new account with opening_balance = total_amount
        $sql_insert_acc = "INSERT INTO customer_accounts (customer_id, opening_balance, current_balance) VALUES (?, ?, ?)";
        $stmt_insert_acc = mysqli_prepare($conn, $sql_insert_acc);
        mysqli_stmt_bind_param($stmt_insert_acc, "idd", $customer_id, $total_amount, $total_amount);
        if (!mysqli_stmt_execute($stmt_insert_acc)) {
            throw new Exception("Failed to create customer account.");
        }
        mysqli_stmt_close($stmt_insert_acc);
    }
    mysqli_stmt_close($stmt_check);

    // Commit transaction
    mysqli_commit($conn);

    echo json_encode([
        "status" => "success",
        "message" => "Checkout completed successfully.",
        "sale_id" => $sale_id
    ]);

} catch (Exception $e) {
    mysqli_rollback($conn);
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}

// Close statements and connection
if (isset($stmt_item)) mysqli_stmt_close($stmt_item);
if (isset($stmt_stock)) mysqli_stmt_close($stmt_stock);
mysqli_close($conn);
?>


Database Structure
SqlCREATE TABLE customer_accounts (
    customer_id INT PRIMARY KEY,
    opening_balance DECIMAL(10,2) DEFAULT 0.00,
    current_balance DECIMAL(10,2) DEFAULT 0.00,
    last_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id)
);


Logic Changes

If customer exists → only current_balance is updated.
If customer doesn’t exist → a new record is created with:

opening_balance = total_amount
current_balance = total_amount


Still transaction-safe — if anything fails, all changes roll back.


If you want, I can extend this further so that it also deducts payments if the customer pays partially or fully at checkout, meaning current_balance would reflect only unpaid amounts.
Do you want me to make it support partial payments in the same transaction? That would make it a real-world POS credit system.
