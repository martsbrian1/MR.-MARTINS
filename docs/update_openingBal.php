<?php include"../inc/lay_header.php";
$msg = "";
	error_reporting(E_ERROR);
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
		if(isset($_POST['btn_update'])){
			$criteria    = mysqli_real_escape_string($conn, $_POST['criteria']);
			$txt_bal_id  = mysqli_real_escape_string($conn, $_POST['txt_bal_id']);
			
			if (empty($_POST['criteria'])) {
				$errorMessage .= "You did no specify amount.<br>";
			}			
				
			if (empty($errorMessage)) {	
			
				$up = "UPDATE customer_transaction SET
			   `openning_balance` = '$criteria',
					   WHERE `id` = '$txt_bal_id'";	
				mysqli_query($conn, $up);
				header("Location: view_customers_all.php");
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


  <!-- Sidear -->  
<?php include"../inc/lay_sidebar.php";?>

  <!-- Main content -->
  <div class="content">                                                                                                                                                                                                                                                                                                                                                                                                                                                            
  <div class = "new_products" >
  <div class = "row" style = "padding-left:130px; margin-right:30px" >
  <div class="dew" style = "margin-left:10px"> <span style="color:blue; font-size:24px"><a href="#" title="Home"><?php echo @$title_main;?></a> | <a href="view_customers_all.php" > View Customers </a></span> </div>
   <fieldset style="border:2px solid green; border-radius:8px;">
       <p>
	   <?php
			if(isset($_GET['pid'])){
		    	$cus_id = $_GET['pid'];
			
				$result = mysqli_query($conn, "SELECT customer_transaction.id, customer_transaction.openning_balance, customers.customer_name
					FROM customer_transaction
					INNER JOIN customers ON customers.customer_id = customer_transaction.customer_id
					WHERE customers.customer_id = '$cus_id'  LIMIT 1");
			
			
					
						while ($row   = mysqli_fetch_assoc($result)) {
							$id       = $row['id'];
							$customer = $row['customer_name'];							
			           }
	   ?>
	         <div style="text-align:center; color:blue"><h2> UPDATING OPENING BALANCE ACCOUNT FOR <br><?php echo $customer; ?></h2></div>
			 
			 
			<div class="passErr" style="font-size:16px; color:red; font-weight:bold; text-align:left">
				<?php
					 //echo $customer;
					if(!empty($errorMessage)) 
					{
						echo("<p>Result: ...</p>\n");
						echo("<ul>" . $errorMessage . "</ul>\n");
					}
				 ?>
				</div>	   
	   
	   
	   <?php

    

			
           
        }
        echo "</table>";
   

?>



<?php

?>

<!-- Form should always be visible -->
<form action="" method="post">   
<input type = "hidden" name ="txt_bal_id" value = <?php echo $id; ?> />          
  <table border="0" cellspacing="0" cellpadding="1" width="90%" align="center">
    <tr>
      <td colspan="8" align="center" height="40px" style="color:#0000FF;"><hr></td>
    </tr>
    <tr>
      <td colspan="8" class="register_table">
        <div align="right"> 
          <table border=0>
            <tr>
              <td align="right">    
                <p style="font-weight:bold; color:green;">
                  <em>Please enter Customer's Opening Balance.</em>:
                </p>
                <strong>GHS </strong><input type="text" name="criteria" 
                       value="<?php echo @$_POST['criteria'];?>" 
                       style="margin:5px auto;width:100px ; font-weight:bold;!important;
                              box-shadow:2px 2px 2px 2px #555;
                              padding:5px;border-radius:3px;
                              border:1px solid red; text-align:right" />
              </td> 
            </tr>
            <tr>
              <td align='right'>        
                <input style="width:180px; padding:5px; margin-top:10px" 
                       type="submit" name="btn_update" value="Update">
              </td> 
            </tr>
          </table>
        </div>
      </td>
    </tr>
  </table>
</form>

	   
	   </p>
    </fieldset>
  </div>

  <!-- Footer -->
  <?php include"../inc/lay_footer.php";?>
