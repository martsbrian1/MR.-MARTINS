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
	
	$errorMessage = "";

	if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_supplier'])) {
		$transkey   = mysqli_real_escape_string($conn, $_POST['transkey']);

		if(empty($_POST['supplier_name'])){
			$errorMessage .= "<li>You forgot to specify name of supplier!</li>";
		}
		
		if(empty($_POST['phone'])){
			$errorMessage .= "<li>You forgot to specify phone number!</li>"; 
		}
		
		if(empty($_POST['transkey'])){
			$errorMessage .= "<li>You forgot to confirm your entries</li>";
		}
		
		if(empty($errorMessage)) {					
			$key = "SELECT trans_key FROM suppliers WHERE trans_key = '".mysqli_real_escape_string($conn, $transkey)."' LIMIT 1";
			$fed = mysqli_query($conn, $key);
			
			if ($fed && mysqli_num_rows($fed) > 0 ) {			  
				$errorMessage .= '<li><h4>Sorry, entry already committed to database!</h4></li>';
			}else{
				
				$supplier_name = mysqli_real_escape_string($conn, $_POST['supplier_name']);	
				$address       = mysqli_real_escape_string($conn, $_POST['address']);	
				$phone         = mysqli_real_escape_string($conn, $_POST['phone']);	
				$transkey      = mysqli_real_escape_string($conn, $_POST['transkey']);

				$sql = "INSERT INTO suppliers ( supplier_name, address, phone, trans_key) 
						VALUES ('$supplier_name', '$address', '$phone', '$transkey')";
				
				$errorMessage = "<li><label style='color:green'><strong>Details saved successfully...</strong> <a href='./add_suppliers.php'>BACK</a></label></li> ";
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
  <div class="dew"> <span style="color:blue; font-size:24px"><a href="#" title="Home"><?php echo @$title_main;?></a> | <a href ="view_suppliers.php"> View Suppliers</a></span> </div>
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
						
						<label for="prod_name"> Name of Supplier</label> 
						<div class="mb-2"><input type="text" class="form-control" id="supplier_name" name="supplier_name" value="<?php echo @$supplier_name;?>" placeholder="Supplier's Name"  /></div>
							
						<br>
						<label for="supplier_contact"> Supplier's Address</label>
						<div class="mb-2"><input type="text" class="form-control" id="address" name="address"  value="<?php echo @$address;?>" placeholder="Supplier's Address"  />
						<br>						
						<label for="supplier_contact"> Supplier's Contact Phone</label>
						<div class="mb-2"><input type="text" class="form-control" id="phone" name="phone"  value="<?php echo @$phon;?>" placeholder="Supplier's Contact"  />
						<br>	
										
						<div align="right"> 
						<label for="transkey_gen"> Check box below to confirm entries:</label> 
						<input id = "transkey" type="hidden" name ="transkey" value="" >
      
					<input type="checkbox" name="gentranskey" id="transkey_gen" style="width:20px;" class="form-control" id="confirm_entries">
					<button style="width:300px; margin:5px 5px; float: right;" class="btn btn-info" name="add_supplier" value="Save Info">Submit</button>
					</div>
					</form>			
    </fieldset>
  </div>

  <!-- Footer -->
  <?php include"../inc/lay_footer.php";?>
