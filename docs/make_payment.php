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
		
		if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_payment'])) {		
			$transkey   = mysqli_real_escape_string($conn, $_POST['transkey']);
		
		if(empty($_POST['trans_date'])){
			$errorMessage .= "<li>You forgot to specify transaction date!</li>"; 
		}		

		if(empty($_POST['pay_type'])){
			$errorMessage .= "<li>You forgot to select payment type!</li>";
		}
		
		if(empty($_POST['tranx_type'])){
			$errorMessage .= "<li>You forgot to select transaction type!</li>";
		}
		
		if(empty($_POST['amount_paid'])){
			$errorMessage .= "<li>You forgot to specify amount!</li>"; 
		}
		
		if(empty($_POST['transkey'])){
			$errorMessage .= "<li>You forgot to confirm your entries</li>";
		}
		
		if(empty($errorMessage)) {					
			$key = "SELECT trans_key FROM customer_transaction WHERE trans_key = '".mysqli_real_escape_string($conn, $transkey)."' LIMIT 1";
			$fed = mysqli_query($conn, $key);
			
			if ($fed && mysqli_num_rows($fed) > 0 ) {			  
				$errorMessage .= '<li><h4>Sorry, entry already committed to database!</h4></li>';
			}else{
				$ref ="CR". serialNumber();
				$customer_id= mysqli_real_escape_string($conn, $_POST['customer_id']);
				$trans_date = $_POST['trans_date'];
				$tranx_type = $_POST['tranx_type'];
				$amount_paid= $_POST['amount_paid'];
				$pay_type   = $_POST['pay_type'];
				$openning_balance = "";
				$transkey  	= mysqli_real_escape_string($conn, $_POST['transkey']);

				$sql = "INSERT INTO customer_transaction(store_id, customer_id, trans_date, tranx_type, pay_type, openning_balance, debit, credit, description, staff, trans_key) 
						VALUES ('$store_id', '$customer_id', '$trans_date', '$tranx_type', '$pay_type', '0', '', '$amount_paid', '', '$sysuser', '$transkey')";
				
				/* $stmt = mysqli_prepare($conn, "SELECT current_balance FROM customer_accounts WHERE customer_id = ?" );
						mysqli_stmt_bind_param($stmt, "i", $customer_id);
						mysqli_stmt_execute($stmt);

				$result = mysqli_stmt_get_result($stmt);
				$row = mysqli_fetch_assoc($result);
				$current_balance = $row['current_balance'];
				$new_balance = $current_balance + $amount_paid;
				
				$stmt = mysqli_prepare($conn,"UPDATE customer_accounts SET current_balance = ?, last_updated = NOW() WHERE customer_id = ?");				
						mysqli_stmt_bind_param( $stmt, "di", $new_balance, $customer_id);
				mysqli_stmt_execute($stmt); */
				
				$errorMessage = "<li><label style='color:green'><strong>Details saved successfully...</strong> <a href='./view_customers.php'>BACK</a></label></li> ";
				mysqli_query($conn, $sql);
			}
		}
}
?>

	<?php
		if(isset($_GET['pid'])){
			 $customer_id = $_GET['pid'];
		}    

?>					
	
  <!-- Header -->  
  
<header>
  <div class="d-flex align-items-center">    
    <h2 class="mb-0 text-white" style="text-align:center; line-height:8px"><strong><?php echo @$company . " - " . @$store; ?></strong></h2>
  </div>
  <div style="text-align:right; font-size:13px; color:#fff; margin-top:-38px;margin-right: 50px"><?php echo date("l jS \of F Y");?>
  <?php echo ucwords("$msg"); ?></div>
</header>


  <!-- Sidebar -->  
<?php include"../inc/lay_sidebar.php";?>

  <!-- Main content -->
  <div class="content">                                                                                                                                                                                                                                                                                                                                                                                                                                                            
  <div class = "new_products" >
  <div class = "row" style = "padding-left:130px; margin-right:30px" >
  <div class="dew" style = "margin-left:10px"> <span style="color:blue; font-size:24px"><a href="#" title="Home"><?php echo @$title_main;?></a>  | <a href ="view_customers.php"> View Customers</a></span> </div>
   <fieldset style="border:2px solid green; border-radius:8px;">
       <p>
	   <div class="passErr" style="font-size:14px; color:red; text-align:left">
				<?php
					if(!empty($errorMessage)) 
					{
						echo("<p>Result: ...</p>\n");
						echo("<ul>" . $errorMessage . "</ul>\n");
					}
				 ?>
		</div>

	<form id="form1" name="form1" method="post" action="">
	<input type="hidden" name ="rec_no" value="<?php echo $sn; ?>" />
	<table width="98%" border="0" style="margin-left:15px; margin-right: 0px;">
  <tr>
    <td colspan="5" align="center" style="font-weight:bold; color:brown;"><h2><!--<marquee direction="left" behavior="alternate">--> </marquee>Receive Payment For</h2></td>
  </tr>
  
  <tr>
    <td colspan="5" align="center" style="font-weight:bold; background-color:yellow; color:brown;"><h1>	
	<marquee direction="left" behavior="alternate">
	<?php
		$qry=mysqli_query($conn,"SELECT * FROM customers WHERE `customer_id`='".$customer_id."'");
		$dew = mysqli_fetch_assoc($qry);
	print $name = $dew['customer_name'];
		$reg_no = $dew['reg_no'];
		$id    = $dew['customer_id'];
	
	?>
	</marquee>
	</h1></td>	
  </tr>
  
  <tr>
    <td width="23%" style="font-size:14px"><strong>Customer's Reg. No.:</strong>
	<input name="customer_id" type="hidden" value="<?php echo $id;?>" />
	</td>
    <td width="23%"><input name="reg_no" type="text" value="<?php echo $reg_no;?>" style="margin:5px auto;width:100px !important;box-shadow:2px 2px 2px 2px #555;padding:5px;border-radius:8px;border:1px solid red; background-color:; font-weight:bold;" readonly="readonly" /></td>
    <td width="25%"><strong>Date of Payment:</strong><em>  </em></b></td>
    <td width="20%"><input name="trans_date" type="date" style="margin:2px auto;width:125px !important;box-shadow:2px 2px 2px 2px #555;padding:2px;border-radius:8px;border:1px solid red;" /></td>
    
    <td width="21%">&nbsp;</td>
  </tr>
  
  <tr>
    <td><strong>Payment Type:</strong><em>  </em></b></td>
    <td width="13%"><select name="pay_type" style="width:125px !important;box-shadow:2px 2px 2px 2px #555;padding:5px;border-radius:8px;border:1px solid red;">
      <option value="">--Select one--</option>
      <?php
		  $qry = mysqli_query($conn, "SELECT * FROM  payment_types");
		   while($ds = mysqli_fetch_assoc($qry)){
		   echo "<option value= ".$ds['id'].">".$ds['pay_type']."</option>";
		  }
		  ?>
    </select></td>
	
	<td><strong>Amount Paid GHC: </strong></td>
    <td><input name="amount_paid" type="text" value="0.00" style="text-align:right; margin:5px 0;width:125px; !important;box-shadow:2px 2px 2px 2px #555;padding:5px;border-radius:10px;border:1px solid red; font-weight:bold" /></td>
    <td>&nbsp;</td>
  </tr>
  
  
  <tr>
    <td><strong>Transaction Type:</strong><em>  </em></b></td>
    <td width="13%"><select name="tranx_type" style="width:125px !important;box-shadow:2px 2px 2px 2px #555;padding:5px;border-radius:8px;border:1px solid red;">
      <option value="">--Select one--</option>
      <?php
		  $qry = mysqli_query($conn, "SELECT * FROM  transaction_types");
		   while($ds = mysqli_fetch_assoc($qry)){
		   echo "<option value= ".$ds['id'].">".$ds['tranx_type']."</option>";
		  }
		  ?>
    </select></td>
	
	<td><strong></strong></td>
    <td></td>
    <td>&nbsp;</td>
  </tr>
  
  
  
  
  <tr>
    <td colspan="3" align="right"><strong>Tick box to confirm entries:</strong> 
      <input id = "transkey" type="hidden" name ="transkey" value="" />
      <input type="checkbox" name="gentranskey" id="transkey_gen"   />
     &nbsp;</td> 
      <td><input type="submit" name="add_payment" value="Save Payment" style="width:125px; padding:5px;" />
    </td>
     <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
    <td>&nbsp;</td>
    <td></a></td>
  </tr>
</table>

</form> 
	   
	   </p>
    </fieldset>
  </div>

  <!-- Footer -->
  <?php include"../inc/lay_footer.php";?>
