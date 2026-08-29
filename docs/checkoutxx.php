<?php session_start();
$msg ="";
error_reporting(E_ERROR);
if (isset($_SESSION['username'])){
			$sysuser = $_SESSION['username'];
			$status = $_SESSION['status'];
			$store_id  = $_SESSION['store_id'];
			 $msg .="Hi " .$sysuser ." !.";
	}else{
			header('location:../index.php');
	} ?>

<?php
//session_start();  sales_id
$conn = mysqli_connect("localhost", "root", "", "eagle_cosmetics");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['checkout']) && !empty($_SESSION['cart'])) {
    //$staff_id = $_SESSION['staff_id'] ?? 1; // Example: logged-in staff ID
	$tot_cash = $_POST['grand_total'];
    $sysuser  = $_SESSION['username'];
	$transkey = date("Y-m-d H:i:s");
	$total = 0;
	$subtotal = 0;

    // Start transaction
    mysqli_begin_transaction($conn);

    try {
        // Create order
		 $dt_order = date('Y-m-d');		
        mysqli_query($conn, "INSERT INTO orders (dt_order, staff, total) VALUES ('$dt_order', '$sysuser', 0)");
		
        $order_id = mysqli_insert_id($conn);

        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            $result = mysqli_query($conn, "SELECT price, qty FROM products WHERE id=$product_id FOR UPDATE");
            $product = mysqli_fetch_assoc($result);

            if ($product['qty'] >= $quantity) { 
                $price = $product['price'];
                $line_total = $price * $quantity;
                $total += $line_total;
				$subtotal += $line_total;
				$type = "SALE";
                // Insert order item
                mysqli_query($conn, "INSERT INTO order_details (order_id, product_id, qty, price, subtotal) 
                                     VALUES ($order_id, $product_id, $quantity, $price, $line_total)");

                // Deduct stock
                mysqli_query($conn, "UPDATE products SET qty = qty - $quantity WHERE id=$product_id");

                				
				// stores_movement
				mysqli_query($conn, "INSERT INTO stock_movements (product_id, movement_type, quantity, warehouse_id, store_id, invoice_id) 
                                     VALUES ($product_id, '$type', $quantity, '', '$store_id', '')");
            } else {
                $qry = mysqli_query($conn, "SELECT name FROM products WHERE `id`='".$product_id."'");
			$row = mysqli_fetch_assoc($qry);
			$product = $row['name'];
		
            echo "<span style='background-color:red; padding:7px; color:red'> <h1><strong>Not enough stock for : ( $product )!</strong></h1></span>";
        }
    }

        // Update order total
        mysqli_query($conn, "UPDATE orders SET total=$total WHERE id=$order_id"); 
		
		// Insert cash total
		//mysqli_query($conn, "INSERT INTO tbcash_sales (order_date, order_id, amount, staff, trans_key) 
                                    // VALUES ('$dt_order', '$order_id', '$tot_cash', '$sysuser', '$transkey')");
		

        // Commit transaction
        mysqli_commit($conn);

        // Clear cart
        $_SESSION['cart'] = [];
        //echo "<h2>Checkout complete! Your order ID is $order_id</h2>";
		echo "<h2><img src='../images/logo.png' width='100px'><p style='background-color:#993300;color: #fff; padding:18px'>Checkout complete!  |<a href = 'products_on_sale.php'> <span style='color: #fff'> Back to Product Listing </span></a></p></h2>";

    } catch (Exception $e) {
        // Rollback on error
        mysqli_rollback($conn);
        echo "<p style='color:red;'>Transaction failed: " . $e->getMessage() . "</p>";
    }
}

mysqli_close($conn);
?>
