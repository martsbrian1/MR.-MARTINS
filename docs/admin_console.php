<?php include"../inc/lay_header.php";
$msg = "";
//if (isset($_SESSION['username'])){
if (isset($_SESSION['username']) && $_SESSION['status'] ==1){
$sysuser = $_SESSION['username'];
$status = $_SESSION['status'];
$store_id = $_SESSION['store_id'];

$msg .="<li> Welcome  $sysuser !.</li>";
}else{
header('location:restrict.php');
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
    <h2 class="mb-0 text-white" style="text-align:center; line-height:8px"><strong><?php echo @$company . " - " . @$store; ?>  </strong></h2><br> 
	
</strong>
  </div>
  <div style="text-align:right; font-size:13px; color:#fff; margin-top:-52px"><?php echo date("l jS \of F Y");?>
  <?php echo ucwords("$msg"); ?></div>
</header>


  <!-- Sidebar -->  
<?php //include"../inc/lay_sidebar.php";?>

  <!-- Main content -->
  <div class="content" style="margin-left:-70px">
  <div class="dewl"> <span style="color:blue; font-size:34px; margin-left:140px;"><a href="#" title="Admin Console"><strong><?php echo @$title_main;?></strong></a></span> </div>
    <fieldset style="width:90%; margin:auto; border-color:#CC00FF;border-radius:10px; margin-top:0px; padding:30px"> 

                <ul>
					<p>
					<li><a class="srn" href="./logout.php">Logout</a></li> 
					<p>
                    <li><a class="srn" href="cur_stock.php">Current Stock </a></li>
					<p>
                    <li><a class="srn" href="stock_movement_rpt.php">Stock Movement Report </a></li>
					
                    <p>					
                    <li><a class="srn" href="products_on_sale.php">Products On Sale</a></li> 
					<p>					
                    <li><a class="srn" href="view_customers.php">Receive Customer Payments</a></li> 
					
					
					<p>
                    <li><a class="srn" href="add_product.php">Add Product </a></li>
                    <p>
                    <li><a class="srn" href="add_category.php">Add Category </a></li>
					
					<p>
                    <li><a class="srn" href="add_expense.php">Add Expense </a></li>
					
					<p>
                    <li><a class="srn" href="view_available_products.php">View Products In Stock</a></li>
                    					
					<p>
                    <li><a class="srn" href="view_products.php">View And Modify Products Details</a></li>
                    
					<p>
                    <li><a class="srn" href="view_categories.php">View Product Categories </a></li>
                    
					
					<p>
                    <li><a class="srn" href="replenish_stock.php">Replenish Stock </a></li>
                    
                    
                    
                </p>   
                <li style="text-align:left; font-weight:bold ; color:blue">TRANSACTION REPORTS </li>
                <li><a class="srn" href="day_sales_rpt.php">General Sale Report</a></li>
                <p>
                <li><a class="srn" href="sales_account.php">Sales Acc Report</a></li>
				
				<p>
                <li><a class="srn" href="sales_cash_rpt.php">Sales Cash Report</a></li>
				
                <p>
                <li><a class="srn" href="product_sales_account.php">Products Sold Report </a></li>
				
				<p>
                <li><a class="srn" href="sales_account.php">Sales Account</a></li>
				
				<p>
                <li><a class="srn" href="staff_sales_acc.php">Staff transaction Report</a></li>
				
				<p>
                <li><a class="srn" href="staff_sales_acc.php">Staff transaction Report</a></li>
				
				<p>
                <li><a class="srn" href="staff_sales_acc.php">Staff transaction Report</a></li>
				
				<p>
                <li><a class="srn" href="staff_sales_acc.php">Staff transaction Report</a></li>
				
				<p>
                <li><a class="srn" href="staff_sales_acc.php">Staff transaction Report</a></li>
				
				<p>
                <li><a class="srn" href="staff_sales_acc.php">Staff transaction Report</a></li>
                <p>
                <li style="text-align:left; font-weight:bold ; color:blue">UPDATE PRICES </li>  
                <li><a class="srn" href="modify_prices.php">Update prices</a></li> 
                
                <!--<li><a class="srn" href="items_expiry_rpt.php">View Items Pending Expiration</a></li>
                
                <li><a class="srn" href="items_expiry_range_rpt.php">View Items Pending Expiration 2</a></li>-->
                
                
                
                <li style="text-align:left; font-weight:bold ; color:blue">GENERAL REPORTS </li>  
                <li><a class="srn" href="purchases_rpt.php">Supply / Purcahses Report</a></li>                                         
                
               <!-- <li style="text-align:left; font-weight:bold ; color:blue">PRODUCTS SET UP </li>                
				
				<li><a class="srn" href="add_category.php">Add New Category</a></li>
                
                 <li><a class="srn" href="add_inv_items.php">Add New Product</a></li> 
				 
				 <li><a class="srn" href="add_inventory.php">Add To Inventory</a></li> 
                 //////////////////////////////////////////////////////////////////////-->
				 
                <li style="text-align:left; font-weight:bold ; color:blue"> WAREHOUSE SYSTEM</li>
				
                <li><a class="srn" href="../../inventory/index.php">login Warehouse Sys</a></li>              
                <li><a class="srn" href="add_warehouse_whse.php">Add Warehouse </a></li>
				<li><a class="srn" href="add_suppliers_whse.php">Add Supplier</a></li>  
				<li><a class="srn" href="view_suppliers.php">View Suppliers</a></li>
				<li><a class="srn" href="goods_receipts_whse.php">Goods Receipts</a></li>
                
                
                <li><a class="srn" href="add_supplier.php">Add New Supplier</a></li>
                <li><a class="srn" href="modify_supplier.php">View / Modify Suppliers </a></li>
                
				<li style="text-align:left; font-weight:bold ; color:blue">MANAGE CUSTOMERS </li>
                              
                <li><a class="srn" href="view_customers_all.php">view_customers</a></li> 
				
				 <li><a class="srn" href="make_supplier_payment.php">Make Supplier Payments</a></li> 
				
                <li style="text-align:left; font-weight:bold ; color:blue">MANAGE SYSTEM USERS </li>
                              
                <li><a class="srn" href="createNew_user.php">Add System User</a></li>
				<li><a class="srn" href="replenish_openningBal.php">Update Customer Balance</a></li>
                <li><a class="srn" href="view_user.php">View / Modify System Users</a></li>
                
                 <!--<li><a class="srn" href="reset_sys.php" onclick="return confirm('Are you resetting the system? \nClick Cancel if you not ! \nElse click OK!')">Reset System </a></li>-->
                
                
                
                </ul>
                        
				</fieldset>
  </div>

  <!-- Footer -->
  <?php include"../inc/lay_footer.php";?>
