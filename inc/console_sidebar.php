	<aside id="top-sidebar">
	  <h1 style="color:green; font-weight:bold; font-size:26px"></h1>
		<nav> 
		    <ul>
	   		<li><a class="sel" href="logout.php">Logout</a><br /></li>	
			<li><a class="sel" href="help_pharm.php">Help</a></li>					
			<li><a class="sel" href="../docs/search_prod.php">Home </a></li>
            <li><a class="sel" href="search_prod.php">Search</a></li>
            <legend align="center" style="margin:0 auto; color:blue;font-weight:bold">SALES</legend> 
       <!--     <li><a class="sel" href="../docs/prod_listing.php">Products On Sale </a></li> 
            
		

    
	
	
	
    <a href="logout.php">logout</a>
	<a href="catalog.php">Home</a>
    <a href="catalog.php?category=all">Products</a>-->
	    <a href="./products_on_sale.php">All Products</a>
	
	<?php 
	    //$inv = "SELECT * FROM categories Order by name";
	    //$result = mysqli_query($conn, $inv);		
	    // while($col = mysqli_fetch_assoc($result)){
			// $cid=$col["id"];
	        // $links =  "<li>".$col['name']."</li>";
	?>
        <span style="font-size:16px">
           <?php 
		   echo "<a href='?cmd=$cid'>".strtoupper($links)."</a>";
		   } ?>
        </span>
   
	
	

			
			
		
			<legend align="center" style="margin:0 auto; color:blue;font-weight:bold"> VIEW / PRINT REPORTS </legend>
            <li><a class="sel" href="day_sales_rpt.php"> Sales Report</a></li>
            <li><a class="sel" href="add_expense.php">Add Expenses</a></li>
            <li><a class="sel" href="view_cash_acc.php">Sales / Expenditure Report</a></li>
            <li><a class="sel" href="staff_sales_acc.php">Staff transaction Report</a></li>
            
            <!--<li><a class="sel" href="items_expiry_range_rpt.php"> View Items Pending Expiration</a></li>-->
            
            <li><a class="sel" href="stock_taking_sheet.php"> Stock Taking Sheet</a></li>          
			
		</ul> 
		</nav> 
	</aside>