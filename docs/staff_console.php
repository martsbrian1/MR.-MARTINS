<?php include"../inc/lay_header.php";
$msg = "";
if (isset($_SESSION['username'])){
$user = $_SESSION['username']; 
$status = $_SESSION['status'];
$store_id = $_SESSION['store_id'];

$msg .="<li> Welcome  $user !.</li>";
}else{
header('location:../index.php');
}
?>
	
  <!-- Header -->  
  <style>
	ul {
		list-style-type:none;	
	}
  </style>
  
  <header>
  <div class="d-flex align-items-center">    
    <h2 class="mb-0 text-white" style="text-align:center; line-height:8px"><strong><?php echo @$company . " - " . @$store; ?></strong></h2>
  </div>
  <div style="text-align:right; font-size:13px; color:#fff; margin-top:-38px"><?php echo date("l jS \of F Y");?>
  <?php echo ucwords("$msg"); ?></div>
</header>


  <!-- Sidebar -->  
<?php //include"../inc/lay_sidebar.php";?>

  <!-- Main content -->
  <div class="content" style="margin-left:-70px">
  <div class="dewl"> <span style="color:blue; font-size:34px; margin-left:140px;"><a href="#" title="Admin Console"><strong><?php echo @$title_main;?></strong></a></span> </div>
    <fieldset style="width:100%; margin:auto; border-color:#CC00FF;border-radius:10px; margin-top:0px; padding:30px"> 

                <ul>
					<p>
					<li><a class="srn" href="../docs/logout.php">Logout</a></li> 
					<p>
                    <li><a class="srn" href="cur_stock.php">Current Stock </a></li>
					
                    <p>					
                    <li><a class="srn" href="products_on_sale.php">Products On Sale</a></li> 					
					<p>
                    <li><a class="srn" href="add_expense.php">Add / View Expense </a></li>
                </p>   
                <li style="text-align:left; font-weight:bold ; color:blue">TRANSACTION REPORTS </li>
                <li><a class="srn" href="sales_account.php">Sales Account Report</a></li>
               <!-- <p>
                <li><a class="srn" href="">Sales Cash Report</a></li><!--sales_cash_rpt.php</li>
				
                <p>
                <li><a class="srn" href="">Products Sold Report </a></li><!--day_sales_rpt.php  //product_sales_rpt.php-->
				
				<!--<p>
                <li><a class="srn" href="">Sales Account</a></li><!--sales_account.php-->
				
				<!--<p>
                <li><a class="srn" href="">Staff transaction Report</a></li><!--staff_sales_acc.php-->
				
				<!--<p>					
                    <li><a class="srn" href="view_customers.php">Receive Customer Payments</a></li> -->
				<p>
				<li><a class="srn" href="view_customers_all.php">View Customers And Receive Payment</a></li> 
				
				<!-- <li><a class="srn" href="make_supplier_payment.php">Make Supplier Payments</a></li>
				
				
				<p>
                <li><a class="srn" href="">Staff transaction Report</a></li><!---staff_sales_acc.php--> 
                <p>
               <!-- <li style="text-align:left; font-weight:bold ; color:blue">EXPIRY REPORTS </li>  
                <li><a class="srn" href="purchases_rpt.php">Supply / Purcahses Report</a></li> 
                
                <li><a class="srn" href="items_expiry_rpt.php">View Items Pending Expiration</a></li>
                
                <li><a class="srn" href="items_expiry_range_rpt.php">View Items Pending Expiration 2</a></li>
                
                
                
                <li style="text-align:left; font-weight:bold ; color:blue">GENERAL REPORTS </li>  
                <li><a class="srn" href="purchases_rpt.php">Supply / Purcahses Report</a></li>                                         
               
                <li><a class="srn" href="add_supplier.php">Add New Supplier</a></li>
                <li><a class="srn" href="modify_supplier.php">View / Modify Suppliers </a></li>-->
                
                </ul>
                        
				</fieldset>
  </div>

  <!-- Footer -->
  <?php include"../inc/lay_footer.php";?>
