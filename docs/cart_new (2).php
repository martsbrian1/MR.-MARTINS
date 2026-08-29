<?php //session_start();
$msg ="";
error_reporting(E_ERROR);
 if (isset($_SESSION['username'])){
			$sysuser = $_SESSION['username'];
			$status = $_SESSION['status'];
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


// Database connection
	$conn = mysqli_connect("localhost", "root", "", "samgaba_db");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle Add to Cart
if (isset($_POST['add_to_cart'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity   = (int)$_POST['quantity'];

    if ($quantity > 0) {
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = $quantity;
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

foreach ($_SESSION['cart'] as $product_id => $quantity) {
    $result = mysqli_query($conn, "SELECT name, price FROM products WHERE id=$product_id");
    $product = mysqli_fetch_assoc($result);

    $line_total = $product['price'] * $quantity;
    $grand_total += $line_total;

    echo "<tr>";
    echo "<td>" . $product['name'] . "</td>";
    echo "<td>" . $product['price'] . "</td>";
    echo "<td> <form method='POST' action='cart.php' style='display:inline;'> 
	   <input type='hidden' name='update_id' value='$product_id'> 
	   <input type='number' name='new_quantity' value='$quantity' min='1' style='width:60px;'> 
	   <button type='submit' name='update_cart'>
	   Update</button> </form> </td>"; 
	echo "<td>" . number_format($line_total, 2) . "</td>"; 
	echo "<td><a href='cart.php?remove=$product_id'>Remove</a></td>"; 
	echo "</tr>"; 
	}

echo "<tr><td colspan='3' style='text-align: right ;font-size:30px;'><strong> Grand Total: </strong></td><td colspan=''; style='font-size:30px;'><strong> GH " . number_format($grand_total, 2) . "</strong></td></tr>";
echo "</table>"; ?>

<div class="cart-actions">
    <form method="POST" action="cart.php" style="display:inline;">
		<input type="hidden" name="tot_cash" value="<?php echo $grand_total;?>" />
        <button type="submit" name="clear_cart" class="clear-btn"><strong>Clear Cart</strong></button>
    </form>

    <a class='shop' style="color:green" href="home.php"><strong>Continue Shopping</strong></a>

    <form method="POST" action="checkout.php" style="display:inline;">
        <button type="submit" name="checkout"><strong>Proceed to Checkout</strong></button>
    </form>
</div>

<?php
//echo "<div class='cart-actions'>";
//echo "<a class='shop' href='catalog.php'><strong>Continue Shopping</strong></a>";
//echo "<form method='POST' action='checkout.php' style='display:inline;'>";
//echo "<button type='submit' name='checkout'><strong>Proceed to Checkout</button></strong>";
//echo "</form>";
//echo "</div>";
} else { 
 echo "<h2><img src='../images/logo.png' width='100px'><p style='background-color:#993300;color: #fff; padding:18px'>Your cart is empty.  |<a href = 'products_on_sale.php'> <span style='color: #fff'> Back to Product Listing </span></a></p></h2>"; }?>



    