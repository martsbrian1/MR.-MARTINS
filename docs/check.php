<?php
try {

    /************** CHECKOUT **************/
    if ($action === "checkout") {
        if (
            !isset($_POST['items']) || !is_array($_POST['items']) || empty($_POST['items']) ||
            !isset($_POST['total_amount']) || !is_numeric($_POST['total_amount']) ||
            !isset($_POST['customer_id']) || !is_numeric($_POST['customer_id'])
        ) {
            throw new Exception("Invalid checkout data.");
        }

        $customer_id = (int)$_POST['customer_id'];
        $total_amount = (float)$_POST['total_amount'];

        // Check or create customer account
        $sql_check = "SELECT current_balance, credit_limit FROM customer_account WHERE customer_id = ?";
        $stmt_check = mysqli_prepare($conn, $sql_check);
        mysqli_stmt_bind_param($stmt_check, "i", $customer_id);
        mysqli_stmt_execute($stmt_check);
        $result = mysqli_stmt_get_result($stmt_check);

        if ($row = mysqli_fetch_assoc($result)) {
            $current_balance = (float)$row['current_balance'];
            $credit_limit = (float)$row['credit_limit'];

            if ($credit_limit > 0 && ($current_balance + $total_amount) > $credit_limit) {
                throw new Exception("Credit limit exceeded. Please make a payment before new purchases.");
            }
        } else {
            $sql_insert_acc = "INSERT INTO customer_account (customer_id, total_debit, total_credit, current_balance, credit_limit) 
                               VALUES (?, 0, 0, 0, 0)";
            $stmt_insert_acc = mysqli_prepare($conn, $sql_insert_acc);
            mysqli_stmt_bind_param($stmt_insert_acc, "i", $customer_id);
            mysqli_stmt_execute($stmt_insert_acc);
            mysqli_stmt_close($stmt_insert_acc);
        }
        mysqli_stmt_close($stmt_check);

        // Insert sale
        $sql_sale = "INSERT INTO sales (sale_date, total_amount, customer_id) VALUES (NOW(), ?, ?)";
        $stmt_sale = mysqli_prepare($conn, $sql_sale);
        mysqli_stmt_bind_param($stmt_sale, "di", $total_amount, $customer_id);
        if (!mysqli_stmt_execute($stmt_sale)) {
            throw new Exception("Error inserting sale: " . mysqli_error($conn));
        }
        $sale_id = mysqli_insert_id($conn);
        mysqli_stmt_close($stmt_sale);

        // Process each item
        foreach ($_POST['items'] as $item) {
            $pid = (int)$item['product_id'];
            $qty = (int)$item['quantity'];
            $price = (float)$item['price'];

            if ($pid <= 0 || $qty <= 0 || $price < 0) {
                throw new Exception("Invalid item data for product ID $pid.");
            }

            // Insert sale item
            $sql_item = "INSERT INTO sale_items (sale_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
            $stmt_item = mysqli_prepare($conn, $sql_item);
            mysqli_stmt_bind_param($stmt_item, "iiid", $sale_id, $pid, $qty, $price);
            if (!mysqli_stmt_execute($stmt_item)) {
                throw new Exception("Error inserting sale item for product $pid: " . mysqli_error($conn));
            }
            mysqli_stmt_close($stmt_item);

            // Update stock
            $sql_stock = "UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?";
            $stmt_stock = mysqli_prepare($conn, $sql_stock);
            mysqli_stmt_bind_param($stmt_stock, "iii", $qty, $pid, $qty);
            if (!mysqli_stmt_execute($stmt_stock) || mysqli_stmt_affected_rows($stmt_stock) === 0) {
                throw new Exception("Stock update failed for product ID $pid.");
            }
            mysqli_stmt_close($stmt_stock);
        }

        // Insert customer transaction (debit)
        $desc = "Purchase - Sale #$sale_id";
        $sql_trans = "INSERT INTO customer_transaction (customer_id, trans_date, debit, credit, description) 
                      VALUES (?, NOW(), ?, 0, ?)";
        $stmt_trans = mysqli_prepare($conn, $sql_trans);
        mysqli_stmt_bind_param($stmt_trans, "ids", $customer_id, $total_amount, $desc);
        if (!mysqli_stmt_execute($stmt_trans)) {
            throw new Exception("Error inserting transaction: " . mysqli_error($conn));
        }
        mysqli_stmt_close($stmt_trans);

        // Update account summary
        $sql_update_acc = "UPDATE customer_account 
                           SET total_debit = total_debit + ?, 
                               current_balance = current_balance + ? 
                           WHERE customer_id = ?";
        $stmt_update_acc = mysqli_prepare($conn, $sql_update_acc);
        mysqli_stmt_bind_param($stmt_update_acc, "ddi", $total_amount, $total_amount, $customer_id);
        if (!mysqli_stmt_execute($stmt_update_acc)) {
            throw new Exception("Error updating account summary: " . mysqli_error($conn));
        }
        mysqli_stmt_close($stmt_update_acc);

        mysqli_commit($conn);
        echo json_encode(["status" => "success", "message" => "Checkout completed successfully.", "sale_id" => $sale_id]);
        exit;
    }

	
	/************** PAYMENT **************/
    if ($action === "payment") {
        if (
            !isset($_POST['customer_id']) || !is_numeric($_POST['customer_id']) ||
            !isset($_POST['payment_amount']) || !is_numeric($_POST['payment_amount']) ||
            $_POST['payment_amount'] <= 0
        ) {
            throw new Exception("Invalid payment data.");
        }

        $customer_id = (int)$_POST['customer_id'];
        $payment_amount = (float)$_POST['payment_amount'];

        // Check customer exists
        $sql_check = "SELECT customer_id FROM customer_account WHERE customer_id = ?";
        $stmt_check = mysqli_prepare($conn, $sql_check);
        mysqli_stmt_bind_param($stmt_check, "i", $customer_id);
        mysqli_stmt_execute($stmt_check);
        mysqli_stmt_store_result($stmt_check);

        if (mysqli_stmt_num_rows($stmt_check) === 0) {
            mysqli_stmt_close($stmt_check);
            throw new Exception("Customer account not found.");
        }
        mysqli_stmt_close($stmt_check);

        // Insert customer transaction (credit)
        $desc = "Payment received";
        $sql_trans = "INSERT INTO customer_transaction (customer_id, trans_date, debit, credit, description) 
                      VALUES (?, NOW(), 0, ?, ?)";
        $stmt_trans = mysqli_prepare($conn, $sql_trans);
        mysqli_stmt_bind_param($stmt_trans, "ids", $customer_id, $payment_amount, $desc);
        if (!mysqli_stmt_execute($stmt_trans)) {
            throw new Exception("Error inserting payment transaction: " . mysqli_error($conn));
        }
        mysqli_stmt_close($stmt_trans);

        // Update account summary
        $sql_update_acc = "UPDATE customer_account 
                           SET total_credit = total_credit + ?, 
                               current_balance = current_balance - ? 
                           WHERE customer_id = ?";
        $stmt_update_acc = mysqli_prepare($conn, $sql_update_acc);
        mysqli_stmt_bind_param($stmt_update_acc, "ddi", $payment_amount, $payment_amount, $customer_id);
        if (!mysqli_stmt_execute($stmt_update_acc)) {
            throw new Exception("Error updating account after payment: " . mysqli_error($conn));
        }
        mysqli_stmt_close($stmt_update_acc);

        mysqli_commit($conn);
        echo json_encode(["status" => "success", "message" => "Payment recorded successfully."]);
        exit;
    }

	/************** DEBTORS REPORT **************/
    if ($action === "debtors_report") {
        // This assumes you have created the debtors_report view in MySQL
        $sql = "SELECT * FROM debtors_report";
        $result = mysqli_query($conn, $sql);

        if (!$result) {
            throw new Exception("Error fetching debtors report: " . mysqli_error($conn));
        }

        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }

        echo json_encode(["status" => "success", "data" => $rows]);
        exit;
    }

} catch (Exception $e) {
    // Rollback only if we were in a transaction
    if ($action !== 'debtors_report') {
        mysqli_rollback($conn);
    }

    // Send error response
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}

// Close DB connection
mysqli_close($conn);
