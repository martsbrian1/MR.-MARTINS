<?php include"../inc/lay_header.php";
 include("../inc/serials.php");
 $msg = "";
if (isset($_SESSION['username']) && $_SESSION['status'] ==1){
$sysuser = $_SESSION['username'];
$status = $_SESSION['status'];

$msg .="<li> Welcome  $sysuser !.</li>";
}else{
header('location:../index.php');
}
?>


<?php
// Fetch category for dropdown
	$result = mysqli_query($conn, "SELECT category_id, name FROM categories ORDER BY name ASC");
	
	$errorMessage = "";

	if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
		$transkey   = mysqli_real_escape_string($conn, $_POST['transkey']);

		if(empty($_POST['name'])){
			$errorMessage .= "You forgot to specify name of product!";
		}
		
		if(empty($_POST['cat_id'])){
			$errorMessage .= "You forgot to select product category!"; 
		}
		
		if(empty($_POST['transkey'])){
			$errorMessage .= "You forgot to confirm your entries";
		}
		
		if(empty($errorMessage)) {					
			$key = "SELECT trans_key FROM customers WHERE trans_key = '".mysqli_real_escape_string($conn, $transkey)."' LIMIT 1";
			$fed = mysqli_query($conn, $key);
			
			if ($fed && mysqli_num_rows($fed) > 0 ) {			  
				$errorMessage .= '<li><h4>Sorry, entry already committed to database!</h4></li>';
			}else{
				$sku ="BQ". serialNumber();
				$name       = mysqli_real_escape_string($conn, $_POST['name']);
				$price      = (float)$_POST['selling_price'];
				$cost_price = (float)$_POST['cost_price'];
				$qty        = (int)$_POST['qty'];
				$cat_id     = (int)$_POST['cat_id'];
				$reorder_level  = (int)$_POST['reorder_level'];
				$transkey   	= mysqli_real_escape_string($conn, $_POST['transkey']);

				$sql = "INSERT INTO products (sku, category_id, name, selling_price, cost_price, qty, reorder_level, trans_key) 
						VALUES ('$sku', $cat_id, '$name', $price, $cost_price, $qty, $reorder_level, '$transkey')";
				
				$errorMessage = "<li><label style='color:green'><strong>Details saved successfully...</strong> <a href='./add_product.php'>BACK</a></label></li> ";
				mysqli_query($conn, $sql);
			}
		}
}
?>
  <link href="../css/bootstrap.min.css" rel="stylesheet">
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
  <div class = "new_products" >
  <div class = "row" style = "padding-left:100px" >
  <div class="dew"> <span style="color:blue; font-size:24px"><a href="#" title="Home"><?php echo @$title_main;?></a> | <a href ="view_products.php"> View Product</a></span> </div>
    <fieldset style="border:2px solid green; border-radius:8px; width:80%; padding:30px; margin: 0 auto;">
    			
				<div class="passErr" style="font-size:14px; color:red; text-align:left">
				<?php
					if(!empty($errorMessage)) 
					{
						echo("<p>Result: ...</p>\n");
						echo("<ul>" . $errorMessage . "</ul>\n");
					}
				 ?>
				</div>
				
					<form class="form-horizontal" name="contact_form" id="contact_form" enctype="multipart/form-data" method="post" action="">
        
						<!--<label for="code"> Product Code</label> 
						<div class="mb-2"><input type="h" class="form-control" id="code" name="sku" value="<?php //echo @$sku ;?>" placeholder="Product Code" /></div>-->
						
						<label for="prod_name"> Product Name</label> 
						<div class="mb-2"><input type="text" class="form-control" id="prod_name" name="name" value="<?php echo @$name;?>" placeholder="Product Name" required /></div>
							
						<label for="cat_id"> Product Category</label>
						<select class="form-control" id="cat_id" name="cat_id" required >
								<option value=""> Select one </option>
									<?php 										
										$sq = mysqli_query($conn, "SELECT * FROM categories ORDER BY name");
										while ($c = mysqli_fetch_assoc($sq)) {
											$sel = ($c['id'] == $cat_id) ? "selected" : "";
											echo "<option value='{$c['id']}' $sel>{$c['name']}</option>";
										}
										?>	
						</select>
						
						<label for="price">Cost Price </label> 
						<div class="mb-2"><input class="form-control" id="cost_price" type="number" step="0.01" name="cost_price" value="<?php echo @$Cost_price;?>" placeholder="Cost Price" required /></div>
						
						<label for="price">Selling Price </label> 
						<div class="mb-2"><input class="form-control" id="price" type="number" step="0.01" name="price" value="<?php echo @$price;?>" placeholder="Selling Price (Normally higher than Cost Price)" required /></div>
												
						<label for="quantity"> Quantity </label>
						<div class="mb-2"><input type="number" class="form-control" id="quantity" name="qty" align="right" min=1 value="<?php echo @$qty;?>" placeholder="Quantity" required />
						
						<label for="reorder_level"> Re-order Level </label>
						<div class="mb-2"><input type="number" class="form-control" id="reorder_level" name="reorder_level" align="right" min=1 value="<?php echo @$reorder_level;?>" placeholder="Re-order Level" required />
												
						
										
						<div align="right"> 
						<label for="transkey_gen"> Check box below to confirm entries:</label> 
						<input id = "transkey" type="hidden" name ="transkey" value="" >
      
					<input type="checkbox" name="gentranskey" id="transkey_gen" style="width:20px;" class="form-control" id="confirm_entries">
					<button style="width:300px; margin:5px 5px; float: right;" class="btn btn-info" name="add_product" value="Register">Submit</button>
					</div>
					</form>			
    </fieldset>
  </div>

  <!-- Footer -->
  <?php include"../inc/lay_footer.php";?>
