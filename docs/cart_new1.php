<?php //include"../inc/lay_header.php";
$msg ="";
//error_reporting(E_ERROR);
 if (isset($_SESSION['username'])){
			$sysuser = $_SESSION['username'];
			$status = $_SESSION['status'];
			$store_id = $_SESSION['store_id'];
			//print_r($_SESSION);
			 $msg .="Hi " .$sysuser ." !.";
	}else{
			header('location:../index.php');
	} 
?>

<?php
	//session_start(); // Always start session before using $_SESSION
	
	// Handle Clear Cart
if (isset($_POST['clear_cart'])) {
    $_SESSION['cart'] = []; // reset cart to empty array
    header("Location: cart.php"); // reload cart page
    exit();
}

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle Add to Cart
if (isset($_POST['add_to_cart'])) {
    $product_id = (int)$_POST['product_id'];
    $qty_requested   = (int)$_POST['qty_requested'];

    if ($qty_requested > 0) {
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] += $qty_requested;
        } else {
            $_SESSION['cart'][$product_id] = $qty_requested;
        }
    }

    header("Location: cart.php");
    exit();
}


// Handle Update Quantity 
if (isset($_POST['update_cart'])) { 
	$update_id = (int)$_POST['update_id']; 
	$new_quantity = (int)$_POST['new_quantity']; 
if ($new_quantity > 0) { 
	$_SESSION['cart'][$update_id] = $new_quantity; } 
	header("Location: cart.php"); exit(); }

// Handle Remove Item
if (isset($_GET['remove'])) {
    $remove_id = (int)$_GET['remove'];
    if (isset($_SESSION['cart'][$remove_id])) {
        unset($_SESSION['cart'][$remove_id]);
    }
    header("Location: cart.php"); // Refresh page after removal
    exit();
}
?>

<?php
	$errorMessage = "";
	if (isset($_POST['checkout']) && !empty($_SESSION['cart'])) {
		$total_amount  = $_POST['grand_total'];
		$sysuser       = $_SESSION['username'];
		$transkey      = $_POST['transkey'];
		$customer_id   = $_POST['customer_id'];
		$payMethod     = $_POST['pay_method'];
		$amount_paid   = $_POST['amount_paid'];
		$tranx_type    = $_POST['tranx_type'];
		$trans_date    = date('Y-m-d');
		$whse_id       = 1;
		$total    = 0;
		$subtotal = 0;
		//$staff_id = $_SESSION['staff_id'] ?? 1; // Example: logged-in staff ID  total_amount
		
		if(empty($_POST['pay_method'])){
			 $errorMessage .= "<li style='color:red; font-size:24px'>Sorry, you must select payment method!</li>";
		}
		
		if(empty($_POST['customer_id'])){
			 $errorMessage .= "<li style='color:red; font-size:24px'>Sorry, you must select customer!</li>";			
		}
		
		if(empty($_POST['amount_paid'])){
			 $errorMessage .= "<li style='color:red; font-size:24px'>Sorry, you must enter amount paid!</li>";			
		}
		
		
		if($errorMessage ==""){
			// Start transaction
			mysqli_begin_transaction($conn);

		try {
        
			$customer_id = (int)$_POST['customer_id'];
            $total_amount = (float)$_POST['grand_total'];
			$trnx = 'SALE';
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

        // Insert sale text
        $sql_sale = "INSERT INTO sales (sale_date, total_amount, customer_id, store_id) VALUES (NOW(), ?, ?, ?)";
        $stmt_sale = mysqli_prepare($conn, $sql_sale);
        mysqli_stmt_bind_param($stmt_sale, "dii", $total_amount, $customer_id, $store_id);
        if (!mysqli_stmt_execute($stmt_sale)) {
            throw new Exception("Error inserting sale: " . mysqli_error($conn));
        }
        $sale_id = mysqli_insert_id($conn);
        mysqli_stmt_close($stmt_sale);

        // Process each item  //foreach ($_SESSION['cart'] as $product_id => $quantity) {
		foreach ($_SESSION['cart'] as $product_id => $qty_requested) {
		$result = mysqli_query($conn, "SELECT store_inventory.store_id, store_inventory.product_id, store_inventory.stock_quantity, products.selling_price
				FROM store_inventory
				INNER JOIN products ON products.product_id = store_inventory.product_id
				WHERE store_inventory.product_id=$product_id AND store_id = $store_id FOR UPDATE");
            $item = mysqli_fetch_assoc($result);
        //foreach ($_POST['items'] as $item) {
            $pid = (int)$item['product_id'];
            $stock_qty = (int)$item['stock_quantity'];
            $price = (float)$item['selling_price'];

            if ($pid <= 0 || $stock_qty <= 0 || $price < 0) {
                throw new Exception("Invalid item data for product ID $pid.");
            }
			
			
            // Insert sale item
            $sql_item = "INSERT INTO sale_items (sale_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
            $stmt_item = mysqli_prepare($conn, $sql_item);
            mysqli_stmt_bind_param($stmt_item, "iiid", $sale_id, $pid, $qty_requested, $price);
            if (!mysqli_stmt_execute($stmt_item)) {
                throw new Exception("Error inserting sale item for product $pid: " . mysqli_error($conn));
            }
            mysqli_stmt_close($stmt_item);
			
			// Insert stock_movements
            $sql_move = "INSERT INTO stock_movements (product_id, movement_type, quantity, warehouse_id, store_id, created_at) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt_move = mysqli_prepare($conn, $sql_move);
            mysqli_stmt_bind_param($stmt_move, "isiiis", $product_id, $trnx, $qty_requested, $whse_id, $store_id, $trans_date);
            if (!mysqli_stmt_execute($stmt_move)) {
                //throw new Exception("Error inserting sale item for product $pid: " . mysqli_error($conn));
            }
            mysqli_stmt_close($stmt_move);

            // Update stock 
            $sql_stock = "UPDATE store_inventory SET stock_quantity = stock_quantity - ? 
			WHERE store_id = ? AND product_id = ? AND stock_quantity >= ?";
            $stmt_stock = mysqli_prepare($conn, $sql_stock);
            mysqli_stmt_bind_param($stmt_stock, "iiii", $qty_requested, $store_id, $product_id, $qty_requested);
            if (!mysqli_stmt_execute($stmt_stock) ) {				
                throw new Exception("Stock update failed for product ID $pid.");
            }
			//echo mysqli_stmt_affected_rows($stmt_stock);
            mysqli_stmt_close($stmt_stock);
        }

        // Insert customer transaction (debit)
        $desc = "Purchase - Sale #$sale_id";
        $sql_trans = "INSERT INTO customer_transaction (customer_id, trans_date, debit, credit, description) 
                      VALUES (?, ?, ?, ?, ?)";
        $stmt_trans = mysqli_prepare($conn, $sql_trans);
        mysqli_stmt_bind_param($stmt_trans, "isdds", $customer_id, $trans_date, $total_amount, $amount_paid, $desc);
        if (!mysqli_stmt_execute($stmt_trans)) {
            throw new Exception("Error inserting transaction: " . mysqli_error($conn));
        }
        mysqli_stmt_close($stmt_trans);

        

        mysqli_commit($conn);
		$_SESSION['cart'] = [];
        //echo json_encode(["status" => "<h2>", "message" => "Checkout completed successfully.", "sale_id" => $sale_id]) . "<a href='./products_on_sale.php'>BACK TO PRODUCT ON SALE</a></h2>";
        echo "<h2>Checkout completed successfully.<br>" . "<a href='./products_on_sale.php'>** -- BACK TO PRODUCT ON SALE -- **</a></h2>";
		exit;
   // }

	
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
        //echo json_encode(["status" => "success", "message" => "Payment recorded successfully."]);
        //exit;
    }        

        // Update customer balance
       /*  mysqli_query($conn, "UPDATE customer_accounts SET total_debit = total_debit + '$total_amount', 
							current_balance = current_balance + '$total_amount'  
							WHERE customer_id='$customer_id'");  */
		
		

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

//mysqli_close($conn);
?>
	
				<div class="passErr" style="font-size:12px; font-weight:bold; color:red; text-align:left;">
                <?php
                    if(!empty($errorMessage)) 
                    {
                        echo("<p>System Report:...</p>\n");
                        echo("<ul>" . $errorMessage . "</ul>\n");
                    }
                 ?>
               </div>


<style>
.cart-table input[type="number"] {
    padding: 5px;
    width: 60px;
    text-align: center;
}
.cart-table button {
    padding: 5px 10px;
    background: #007bff;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}
.cart-table button:hover {
    background: #0056b3;
}


/* Cart table */
.cart-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background: #fff;
}
.cart-table th, .cart-table td {
    border: 1px solid #ddd;
    padding: 12px;
    text-align: center;
}
.cart-table th {
    background: #333;
    color: #fff;
}
.cart-table tr:nth-child(even) {
    background: #f9f9f9;
}
.cart-table tr:hover {
    background: #f1f1f1;
}

/* Cart actions */
.cart-actions {
    margin-top: 20px;
    text-align: right;
}
.cart-actions button {
    padding: 10px 15px;
    background: #28a745;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}
.cart-actions button:hover {
    background: #218838;
}
.cart-actions a {
    margin-right: 15px;
    color: #dc3545;
    text-decoration: none;
}

.shop {
	padding: 10px 15px;
	background: #ccc;
	color: white;
}
.cart-actions a:hover {
    text-decoration: underline;
}

.cart-actions .clear-btn {
    padding: 10px 15px;
    background: #dc3545; /* red */
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    margin-right: 15px;
}
.cart-actions .clear-btn:hover {
    background: #c82333;
}

</style>


<?php
echo "<h1>Your Shopping Cart</h1>"; 
if (!empty($_SESSION['cart'])) {


echo "<table class='cart-table'>";
echo "<tr><th>Product</th><th>Price - GH</th><th>Quantity</th><th>Total - GH</th><th>Action</th></tr>";

$grand_total = 0;

foreach ($_SESSION['cart'] as $product_id => $qty_requested) {
    $result = mysqli_query($conn, "SELECT store_inventory.product_id, products.`sku`, products.`name`, products.category_id, products.selling_price,products.cost_price,
		store_inventory.store_id, store_inventory.stock_quantity
		FROM store_inventory
		INNER JOIN products ON products.product_id = store_inventory.product_id 	
	    WHERE store_id = '$store_id' AND products.product_id=$product_id");
    $product = mysqli_fetch_assoc($result);

    $line_total = $product['selling_price'] * $qty_requested;
    $grand_total += $line_total;

    echo "<tr>";
    echo "<td>" . $product['name'] . "</td>";
    echo "<td>" . $product['selling_price'] . "</td>";
    echo "<td> <form method='POST' action='cart.php' style='display:inline;'> 
	   <input type='hidden' name='update_id' value='$product_id'> 
	   <input type='number' name='new_quantity' value='$qty_requested' min='1' style='width:60px;'> 
	   <button type='submit' name='update_cart'>
	   Update</button> </form> </td>"; 
	echo "<td>" . number_format($line_total, 2) . "</td>"; 
	echo "<td><a href='cart.php?remove=$product_id'>Remove</a></td>"; 
	echo "</tr>"; 
	}

echo "<tr><td colspan='3' style='text-align: right ;font-size:30px;'><strong> Grand Total: </strong></td><td colspan=''; style='font-size:30px;'><strong> GH " . number_format($grand_total, 2) . "</strong></td></tr>";
echo "</table>"; ?>

<div class="cart-actions">
    <form method="POST" action="" style="display:inline;">
	<div style="margin-right:20px; margin-bottom:20px;">
	
	<style>
	table{
		border-collapse;
		width:70%;
	}
	td{
		border: 2px solid white;
		padding: 10px;
	}
	
	</style>
	
	<table align="right" border="0" border-spacing= "40px"; style="margin-bottom:90px; padding:0px;">
		<tr>
			<td align="right"><strong> Payment Method: </strong> </td>
			<td align="right"><select name="pay_method" style="width:160px !important;box-shadow:2px 2px 2px 2px #555;padding:5px;border-radius:8px;border:1px solid red;">
				<option value="">Select pay method</option>
				<option value="1">Cash </option>
				<option value="2">Mobile </option>
				<option value="3">Cheque </option>
			</select>
			</td>			
			<td>&nbsp;&nbsp;</td>
			<td align="right"><strong>Customer: </strong></td>
			<td align="right"><select name="customer_id" style="width:200px !important;box-shadow:2px 2px 2px 2px #555;padding:5px;border-radius:8px;border:1px solid red;">
				<option value=""> --Select Customer-- </option>
				<?php 										
					$sq = mysqli_query($conn, "SELECT * FROM customers ORDER BY customer_name");
						while ($c = mysqli_fetch_assoc($sq)) {
							//$sel = ($c['id'] == $customer_id) ? "selected" : "";
							echo "<option value='{$c['customer_id']}' >{$c['customer_name']}</option>";
						}
				?>	
			</select>
			</td>
		</tr>	
		<tr>
			<td align="right"><strong>Transaction Type: </strong>
			<td align="right">
			<select name="tranx_type" style="width:160px !important;box-shadow:2px 2px 2px 2px #555;padding:5px;border-radius:8px;border:1px solid red;">
				<option value=""> Transaction Type </option>
				
				<?php 										
					$sq = mysqli_query($conn, "SELECT * FROM transaction_types ORDER BY tranx_type");
						while ($c = mysqli_fetch_assoc($sq)) {
							//$sel = ($c['id'] == $id) ? "selected" : "";
							echo "<option value='{$c['id']}' >{$c['tranx_type']}</option>";
						}
				?>	
			</select>			
			</td>
			<td></td>
			<td align="right"><strong>Amount Paid: </strong></td>
			<td align="right"><input type="text" name="amount_paid" style="width:160px; font-weight:bold !important;box-shadow:2px 2px 2px 2px #555;padding:5px;border-radius:8px;border:1px solid red; text-align:right" value= "<?php echo @$amount_paid;?>" />
			<input type="hidden" name="grand_total" value="<?php echo $grand_total;?>" />
			</td>
			</tr>
			<tr>
				<td align="center" colspan="5">
				<div align="right"><button type="submit" style="padding:15px;" name="clear_cart" class="clear-btn"><strong>Clear Cart</strong></button> 
				<a class='shop' style="color:green; padding:15px;" href="home.php"><strong>Continue Shopping</strong></a>
				<strong>Tick box to confirm entries:</strong> <input type="checkbox" name="gentranskey" id="transkey_gen" />
				<input id = "transkey" type="hidden" name ="transkey" value="" />
				<button style="padding:15px;" type="submit" name="checkout"><strong>Checkout</strong></button></div>
				</td>				
			</tr>
		</table> 
    </form>
	</div>

<?php
  } else { 
	echo "<h2><img src='../images/logo.png' width='100px'><p style='background-color:#993300;color: #fff; padding:18px'>Your cart is empty.  |<a href = 'products_on_sale.php'> <span style='color: #fff'> Back to Product Listing </span></a></p></h2>"; }
 ?>



    