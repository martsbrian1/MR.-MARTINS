<?php session_start();
$msg ="";
//error_reporting(E_ERROR);
if (isset($_SESSION['username'])){
			$sysuser = $_SESSION['username'];
			$status = $_SESSION['status'];
			$store_id  = (int)$_SESSION['store_id'];
			 $msg .="Hi " .$sysuser ." !.";
	}else{
			header('location:../index.php');
	} ?>

<?php
//session_start();


$conn = mysqli_connect("localhost", "root", "", "eagle_cosmetics");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$errorMessage = "";

if (isset($_POST['checkout']) && !empty($_SESSION['cart'])) {
	$grand_total = round((float)$_POST['grand_total'], 2);
    $sysuser     = $_SESSION['username'];
	$transkey    = $_POST['transkey'];
	$customer_id = (int)$_POST['customer_id'];
	$amount_paid  = round((float)$_POST['amount_paid'], 2);
	$payMethod    = $_POST['pay_method'];
	$total    = 0;
	$subtotal = 0;
    //$staff_id = $_SESSION['staff_id'] ?? 1; // Example: logged-in staff ID
	if(empty($_POST['pay_method'])){
		 $errorMessage .= "<li style='color:red; font-size:24px'>Sorry, you must select payment method!</li>";
	}
	
	if(empty($_POST['customer_id'])){
		 $errorMessage .= "<li style='color:red; font-size:24px'>Sorry, you must select customer!</li>";
		print'<a href="./cart.php">-- BACK TO CART --</a>';
	}
	
	if($errorMessage !=""){	
	//print $tot_cash = $_POST['grand_total'];
    $sysuser  = $_SESSION['username'];
	//$transkey = date("Y-m-d H:i:s");
	$total = 0;
	$subtotal = 0;

    // Start transaction
    mysqli_begin_transaction($conn);

    try {
        // Create order
		$dt_order = date('Y-m-d');		
        mysqli_query($conn, "INSERT INTO sales (store_id, staff, customer_id, date_paid, subtotal, grand_total) 
					VALUES ('$store_id', '$sysuser', '$customer_id', '$dt_paid', 0, '$grand_total')");
		
        $sale_id = mysqli_insert_id($conn);

        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            $result = mysqli_query($conn, "SELECT selling_price, quantity FROM store_inventory WHERE store_id=$store_id AND product_id=$product_id FOR UPDATE");
            $product = mysqli_fetch_assoc($result);

            if ($product['quantity'] >= $quantity) { 
                $price = $product['selling_price'];
                $line_total = $price * $quantity;
                $total += $line_total;
				$subtotal += $line_total;
				$type = "SALE";
                // Insert order item
                mysqli_query($conn, "INSERT INTO sale_items (sale_id, product_id, quantity, unit_price, line_total) 
                                     VALUES ($sale_id, $product_id, $quantity, $selling_price, $line_total)");

                // Deduct stock
                mysqli_query($conn, "UPDATE store_inventory SET quantity = quantity - $quantity WHERE store_id=$store_id AND product_id=$product_id");

                				
				// stores_movement
				mysqli_query($conn, "INSERT INTO stock_movements (product_id, movement_type, quantity, warehouse_id, store_id, invoice_id) 
                                     VALUES ($product_id, '$type', $quantity, '', '$store_id', '')");
            } else {
                $qry = mysqli_query($conn, "SELECT products.product_id, products.sku, products.`name`, store_inventory.store_id, store_inventory.quantity, products.selling_price,products.cost_price
						FROM store_inventory
						INNER JOIN products ON products.product_id = store_inventory.product_id 				
						WHERE store_id=$store_id AND `product_id`='".$product_id."'");
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
}

mysqli_close($conn);
?>
