<?php include"../inc/lay_header.php";
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
    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_expense'])) {	
		$date_xp = $_POST['date_xp'];
		$purpose = $_POST['purpose'];
		$receiver= $_POST['receiver'];
		$amt_exp = $_POST['amt_exp'];
		$staff   = $sysuser;
		$transkey = $_POST['transkey'];
		
		$purpose 	= mysqli_real_escape_string($conn, $purpose);
		$receiver 	= mysqli_real_escape_string($conn, $receiver);
		$staff 		= mysqli_real_escape_string($conn, $sysuser);
		
		if(empty($errorMessage)){					
			$key = "SELECT trans_key FROM tbexpenses WHERE trans_key = '".mysqli_real_escape_string($conn, $transkey)."' LIMIT 1";
			$fed = mysqli_query($conn, $key);
			
			if ($fed && mysqli_num_rows($fed) > 0 ) {			  
				$errorMessage .= '<li><h4>Sorry, entry already committed to database!</h4></li>';
			}else{						
				$query = "INSERT INTO tbexpenses(store_id, date_xp, purpose, receiver, amt_exp, staff, trans_key) 
										VALUES ('$store_id', '$date_xp', '$purpose','$receiver','$amt_exp','$sysuser','$transkey')";
										
				$errorMessage .= "<li><label style='color:green'><strong>Details saved successfully...</strong> <a href='./add_expense.php'>BACK</a></label></li> ";
				mysqli_query($conn, $query);
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
  <?php //echo ucwords("$msg"); ?></div>
</header>


  <!-- Sidebar -->  
<?php include"../inc/lay_sidebar.php";?>

  <!-- Main content -->
  <div class="content">                                                                                                                                                                                                                                                                                                                                                                                                                                                            
  <div class = "new_products" >
  <div class = "row" style = "padding-left:130px; margin-right:30px" >
  <div class="dew" style = "margin-left:10px"> <span style="color:blue; font-size:24px"><a href="#" title="Home"><?php echo @$title_main;?></a> | <a href ="./view_expenses.php"> View Expenses</a></span> </div>
   <fieldset style="border:2px solid green; border-radius:8px;">
       <p>
	    <div style="margin:35px;">
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
						
						<label for="date_xp"> Date: </label> 
						<div class="mb-2"><input type="date" class="form-control" id="date_xp" name="date_xp" value="<?php echo @$date_xp;?>" placeholder="Payment Date" required /></div>
							
						
						<label for="purpose"> Purpose: </label> 
						<div class="mb-2"><input class="form-control" id="purpose" type="text" name="purpose" value="<?php echo @$purpose;?>" placeholder="Purpose" required /></div>
								
						<label for="receiver"> Receiver: </label> 
						<div class="mb-2"><input type="text" class="form-control" id="receiver" name="receiver" align="right" value="<?php echo @$receiver;?>" placeholder="Receiver" required />
						
						<label for="reorder_level"> Amount GHC: </label> 
						<div class="mb-2"><input type="number" class="form-control" id="amt_exp" name="amt_exp" align="right" value="<?php echo @$amt_exp;?>" step="0.01" placeholder="Amount" required />
										
						<div align="right"> 
						<label for="transkey_gen"> Tick box below to confirm entries:</label> 
						<input id = "transkey" type="hidden" name ="transkey" value="" >
      
					<input type="checkbox" name="gentranskey" id="transkey_gen" style="width:20px;" class="form-control" id="confirm_entries">
					<button style="width:300px; margin:5px 5px; float: right;" class="btn btn-info" name="add_expense" value="Register">Submit</button>
					</div>
					</form>
			</div>
	   
	   </p>
    </fieldset>
  </div>

  <!-- Footer -->
  <?php include"../inc/lay_footer.php";?>
