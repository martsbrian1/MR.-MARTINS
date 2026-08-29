<?php include"../inc/lay_header.php";
	  include("../inc/serials.php");
$msg = "";
	
	if (isset($_SESSION['username']))
	{
	$sysuser = $_SESSION['username']; 
	$status = $_SESSION['status'];
	$store_id = $_SESSION['store_id'];
	$msg .="<li> Welcome  $sysuser!.</li>";

	}else{
	header('location:../index.php');
	}
?>

	<?php
		$errorMessage = "";
		if(isset($_POST['add_customer'])){	  
			if(empty($_POST['customer_name'])) 	
			{
				$errorMessage .= "<li>The customer's name field is required, please! </li>";
			}
			
			if(empty($_POST['contact_person'])) 
			{
				$errorMessage .= "<li>You forgot to enter name of contact person!</li>";
			}
			if(empty($_POST['address'])) 
			{
				$errorMessage .= "<li>You forgot to specify address!</li>";
			}		
			
			
			if(empty($_POST['mphone'])) 
			{
				$errorMessage .= "<li>The customer's mobile phone field is required, please!!</li>";
			}
			
			
			if(empty($_POST['transkey'])) 
			{
				$errorMessage .= "<li>You forgot to confirm entries!</li>";

			}           
                 			 					 				 
			if(empty($errorMessage)) {					
			$key = "SELECT trans_key FROM customers WHERE trans_key = '".mysqli_real_escape_string($conn, $_POST['transkey'])."' LIMIT 1";
			$fed = mysqli_query($conn, $key);
			
				if ($fed && mysqli_num_rows($fed) > 0 ) {			  
					$errorMessage .= '<li><h4>Sorry, entry already committed to database!</h4></li>';
				}else{				
					$reg_no ="SG". serialNumber();
					$customer_name  = mysqli_real_escape_string($conn, $_POST['customer_name']); 
					$contact_person = mysqli_real_escape_string($conn, $_POST['contact_person']); 
					$address        = mysqli_real_escape_string($conn, $_POST['address']); 
					$phone          = mysqli_real_escape_string($conn, $_POST['mphone']);
					 //$email = mysql_real_escape_string($_POST['email']);
					$telphone       = mysqli_real_escape_string($conn, $_POST['telphone']);	  
					$transkey       = mysqli_real_escape_string($conn, $_POST['transkey']);
				
					mysqli_begin_transaction($conn);
					try {
						$status = 1;
						mysqli_query($conn, "INSERT INTO customers(reg_no, customer_name, address, phone, status, trans_key)
									VALUES('$reg_no', '$customer_name', '$address', '$phone', '$status', '$transkey')");
						
						$cus_id = mysqli_insert_id($conn); 
						
						mysqli_query($conn, "INSERT INTO customer_accounts(customer_id, openning_balance, current_balance, last_updated)
								VALUES('$cus_id', '0', '0', NOW())");
					
						$errorMessage = "<li><label style='color:green'><strong>Details saved successfully...</strong> <a href='./add_customer.php'>BACK</a></label></li> ";
						mysqli_commit($conn);
					} catch (Exception $e) {
						mysqli_rollback($conn);
						throw $e;
					}
				}
			}
		}
	?>
	
  <!-- Header -->  
  
<header>
  <div class="d-flex align-items-center">    
    <h2 class="mb-0 text-white" style="text-align:center; line-height:8px"><strong><?php echo @$company . " - " . @$store; ?></strong></h2>
  </div>
  <div style="text-align:right; font-size:13px; color:#fff; margin-top:-38px;margin-right: 50px"><?php echo date("l jS \of F Y");?>
  <?php //echo ucwords("$msg"); ?>
  </div>
</header>


  <!-- Sidebar -->  
<?php include"../inc/lay_sidebar.php";?>

  <!-- Main content -->
  <div class="content">                                                                                                                                                                                                                                                                                                                                                                                                                                                            
  <div class = "new_products" >
  <div class = "row" style = "padding-left:130px; margin-right:30px" >
  <div class="dew" style = "margin-left:10px"> <span style="color:blue; font-size:24px"><a href="#" title="Home"><?php echo @$title_main;?></a> | <a href ="view_customers_all.php"> View Customers</a> </span> </div>
    <fieldset style="border:2px solid green; border-radius:8px;">
       <p>
	   
				<div class="passErr" style="font-size:13px; color:red; text-align:left">
				<?php
					if(!empty($errorMessage)) 
					{
						echo("<p>Result: ...</p>\n");
						echo("<ul>" . $errorMessage . "</ul>\n");
					}
				 ?>
				</div>
					
					<div class="formContainer" style="margin-left:40px">
					
						    <div class="fileds1" style="margin-left:20px">
							
							   <!--<h3> Add New Customer Info </h3>-->
							   							   
							   <form class="form-horizontal" name="contact_form" id="contact_form" enctype="multipart/form-data" method="post" action="">
        
								<label for="client_name"> Customer's Name:</label> 
								<input type="text" class="form-control" id="customer_name" name="customer_name" value="<?php echo @$cliencustomer_namet_name ;?>" placeholder="Customer's Name" />
								
								<label for="address"> Address:</label> 
								<input type="text" class="form-control" id="address" name="address" value="<?php echo @$address ;?>" placeholder="Address" />
								
						  													  
								<label for="mphone"> Customer's Phone #:</label> 
								<input type="text" class="form-control" id="mphone" name="mphone" value="<?php echo @$mphone ;?>" placeholder="Phone Number"/>
								
								<!--<label for="email"> E-mail Address:</label> 
								<input type="text" class="form-control" id="email" name="email" value="<?php echo @$email ;?>" placeholder="E-mail Address"/>-->
								
								
								<label for="contact_person">Contact Person:</label> 
								<input type="text" class="form-control" id="contact_person" name="contact_person" value="<?php echo @$contact_person;?>" placeholder="Contact Person"/>
							
								
								<label for="contact_person">Contact's Phone:</label> 
								<input type="text" class="form-control" id="telphone" name="telphone" value="<?php echo @$telphone;?>" placeholder="Contact's Phone"/>
							</div>
					</div>									
										
							
		<div align="right"> 
				<label for="transkey_gen"> Click to Confirm Entries:</label> 
				<input id = "transkey" type="hidden" name ="transkey" value="" >
      
				<input type="checkbox" name="gentranskey" id="transkey_gen" style="width:20px;" class="form-control" id="confirm_entries">
				<button style="width:300px; margin:5px 5px; float: right;" class="btn btn-info" name="add_customer" value="Submit">Submit</button>
		</div>			
			</form>

	   
		</p>
		</fieldset>
  </div>

  <!-- Footer -->
  <?php include"../inc/lay_footer.php";?>
