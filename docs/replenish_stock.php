<?php include"../inc/lay_header.php";
require_once( '../docs/functions.php');
$msg = "";
if (isset($_SESSION['username']) && $_SESSION['status'] ==1){
$sysuser = $_SESSION['username'];
$status = $_SESSION['status'];
$store_id = $_SESSION['store_id'];

$msg .="<li> Welcome  $sysuser !.</li>";
}else{
header('location:../index.php');
}
?>

	<?php 
	$errorMessage = "";
	$conn = db_connect();		
	$products = fetch_all_products();

	if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_stock'])) {	
		$transkey   = mysqli_real_escape_string($conn, $_POST['transkey']);
		$product_id = intval($_POST['product_id']);
		$qty = intval($_POST['qty']);
    if ($qty <= 0) { header('Location: replenish_stock.php'); exit; }
    //$ref = 'REPL-' . time();
		$type = "STOCK-IN";
		mysqli_begin_transaction($conn);
		try {        		
			$stmt = mysqli_prepare($conn, 
			"INSERT INTO stock_movements (product_id, type, quantity, staff, trans_key, movement_date) 
			VALUES (?, ?, ?, ?, ?, ?)");

		$movement_date = date('Y-m-d H:i:s'); // or whatever format you want

		mysqli_stmt_bind_param($stmt, 'isisss', 
		$product_id,   // i = integer
		$type,         // s = string
		$qty,          // i = integer
		$sysuser,         // s = string
		$transkey,     // s = string
		$movement_date // s = string (date/time)
	);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
	
		$p = mysqli_prepare($conn, "SELECT qty FROM products WHERE id = ? FOR UPDATE");
        mysqli_stmt_bind_param($p, 'i', $product_id);
        mysqli_stmt_execute($p);
        mysqli_stmt_bind_result($p, $current_qty);
        mysqli_stmt_fetch($p);
        mysqli_stmt_close($p);

        $new_qty = $current_qty + $qty;
        $u = mysqli_prepare($conn, "UPDATE products SET qty = ? WHERE id = ?");
        mysqli_stmt_bind_param($u, 'ii', $new_qty, $product_id);
        mysqli_stmt_execute($u);
        mysqli_stmt_close($u);

        mysqli_commit($conn);
        header('Location: view_products.php'); exit;
		//$errorMessage .= "Stock details updated successfully";
		//header('Location: success_direct.php'); exit;
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo "Error: " . htmlspecialchars($e->getMessage());
    }
}
?>

  <!-- Header -->  
  
  <header>
  <div class="d-flex align-items-center">    
    <h2 class="mb-0 text-white" style="text-align:center; line-height:8px"><strong><?php echo @$company . " - " . @$store; ?></strong></h2>
  </div>
  <div style="text-align:right; font-size:13px; color:#fff; margin-top:-38px"><?php echo date("l jS \of F Y");?>
  <?php echo ucwords("$msg"); ?></div>
</header>



  <!-- Sidebar -->  
<?php include"../inc/lay_sidebar.php";?>

  <!-- Main content -->
  <div class="content">
  <div class="dew"> <span style="color:blue; font-size:24px"><a href="#" title="Home"><?php echo @$title_main;?></a></span> </div>
    <fieldset style="border:2px solid green; border-radius:8px; width:80%; padding:30px; margin: 0 auto;">
    			
				<div class="passErr" style="font-size:20px; color:red; text-align:left">
				<?php
					if(!empty($errorMessage)) 
					{
						echo("<p>Result: ...</p>\n");
						echo("<ul>" . $errorMessage . "</ul>\n");
					}
				 ?>
				</div>
				
					<?php include"./add_to_stock.php";?>
    </fieldset>
  </div>

  <!-- Footer -->
  <?php include"../inc/lay_footer.php";?>
