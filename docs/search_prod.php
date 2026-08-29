<?php include"../inc/lay_header.php";
$msg = "";
	$errorMessage = '';
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
  <div class="dew" style = "margin-left:10px"> <span style="color:blue; font-size:24px"><a href="#" title="Home"><?php echo @$title_main;?></a></span> </div>
   <fieldset style="border:2px solid green; border-radius:8px;">
       <p>
	   
	   	   <form action="" method="post" name="form1">  
    
			<table class="sample" border="0" cellspacing="0" cellpadding="1" width="100%" align="center" bordercolor="#F1F1F1">
<tr> 
<td>

  <form action="" method="post" name="form2">  
   
 <div style="float:right">
  <b>Enter search criteria: </b>  <input name="criteria" type="text" style="margin:5px auto;width:200px !important;box-shadow:2px 2px 2px 2px #555;padding:12px;border-radius:3px;border:1px solid red;" >
  &nbsp;  &nbsp; &nbsp; &nbsp;
 <b> 
  
 <input class="submitbtn" type="submit" name="submit" value="View" style="margin-bottom:1px; margin-left:10px; padding:7px">
 <button class="submitbtn"  onClick="printContent('t1')" style="padding:7px">Print </button> 
</div>

</td>
</tr> 
</table> 



<?php
//$category_id = isset($_GET['cmd']) ? (int)$_GET['cmd'] : 0;
        if(isset($_POST['submit'])) {		
			if(empty($_POST['criteria'])){
				$errorMessage .= "<li> Please enter product name... </li>";
			}else{
				$q = mysqli_real_escape_string($conn, $_POST['criteria']); 
				
				$sql = "SELECT
				store_inventory.product_id,
				products.`sku`,
				products.`name`,
				products.unit_of_measure,
				products.category_id,
				products.selling_price,
				products.cost_price,
				store_inventory.store_id,
				store_inventory.stock_quantity
				FROM
				store_inventory
				INNER JOIN products ON products.product_id = store_inventory.product_id 
				WHERE store_id = '$store_id' ";
		// }	
		


			


?>









<style>
  button{
	  margin: 0 auto;
	  display: blocl;
	  width: 50px;
	}
</style>


<?php
/* $category_id = isset($_GET['cmd']) ? (int)$_GET['cmd'] : 0;
//$search      = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

$sql = "SELECT
store_inventory.product_id,
products.`sku`,
products.`name`,
products.unit_of_measure,
products.category_id,
products.selling_price,
products.cost_price,
store_inventory.store_id,
store_inventory.stock_quantity
FROM
store_inventory
INNER JOIN products ON products.product_id = store_inventory.product_id 
WHERE store_id = $store_id"; */



// Apply category filter if selected
/* if ($category_id > 0) {
    $sql .= " AND category_id = $category_id";
} */

//$result = mysqli_query($conn, $sql);


?>

			
        <table class="sample" border="1" cellspacing="2" cellpadding="2" width="100%" style="margin-left:0px" bordercolor="red">          
                <tr>                
                  <td colspan="9" class="register_table"> 
                  <div align="right">
                  <div align="center" style="color:#0080FF; font-size:22px; color:brown; font-weight:bold">
                  <?php 
				   /* if(isset($_GET['cmd'])){
					  $cid2= (int)$_GET['cmd'];
				   }else{
					  $cid2=0;
				   } 
                   $inv2 = "SELECT name FROM categories WHERE category_id = $cid2 LIMIT 1";
	               $res = mysqli_query($conn, $inv2);
	               $r = mysqli_fetch_array($res);
			         @$links=$r["name"];
	               // } */
					
	              ?>
                            
				  <?php echo $links;?> In Stock As At   <?php echo date("Y/m/d h:i:s");?>  </div>
				  </div>
				  </td>
                </tr>				
                
                   <tr  style="font-size:14px">
                    <th class="foo">NO.</th>
					<th class="foo">CODE</th>
					<th class="foo">PRODUCT</th>                  
                    <th class="foo">SELLING PRICE </th>	
					<th class="foo">COST PRICE </th>
					<th class="foo">QTY. IN STOCK </th>	
                    <th class="foo">QTY. TO BUY </th>					
                    <th class="foo">ACTION</th>
                </tr>			
								
		<?php 
			//$sql .= " AND categories_id = '@$cid' ORDER BY name";
			$sql .= "AND products.name LIKE '%$q%'";				
				$stmt = $conn->prepare($sql);	
			$stmt->execute();	
		
		$count          = 0;
		$level 	        = 50;
		$tot_qty_issued = 0;
		$tot_amount     = 0;
		
		if (mysqli_num_rows($result) > 0) {
			$result = $stmt->get_result();				

            while ($row = mysqli_fetch_assoc($result)) {
			
			$count = ++$count;
			$id    = $row['product_id'];
			$sku    = $row['sku'];
			$total = $row['stock_quantity'];	
			$name  = $row['name'];
			$cost_price  = $row['cost_price'];
			//$tot_qty_issued += $total; 
			
			//$level = $row['reorder_level'];	
			$price = $row['selling_price'];	
			$amount = $total * $price;
			$tot_amount += $amount;
			///}
		?>				

				<tr style="font-size:14px;">
				    <td class="boo" width="20px" align="center"><a title="Modify material info" class="flat" href='prod_modify.php?prod_id=<?php echo $id; ?>'><?php echo $count ; ?></a></td>
					<td class="boo" width="60px" align="center" style="font-size:16px; pading-left:15px"><strong><a title="View detailed info" class="kkk" href='search_material_id.php?prod_id=<?php echo $id; ?>'><?php echo $sku ?></a></strong></td>	
                    <td class="boo" width="300px" align="left" style="font-weight:bold; font-size:16px; color:blue; padding-left:15px;"><?php echo $name; ?> <small> </small></td>
                    <td class="boo" width="" style="font-size:25px; color:red;" align="center"><b><?php echo $price;?></b></td>
					<td class="boo" width="" style="font-size:25px" align="center"><b><?php echo $cost_price;?></b></td>
					<td class="boo" width="" align="center"; style="color:blue; font-size: 30px"><strong><?php echo $total;?> </strong></td>
                   
				   
                    <td class="boo" width="" align="center">
                    <?php
						if($total <= 0){?>
                        
						<input align="center" style="width:80px; font-size:25px; padding:3px" type="number" name="qty_requested" value="1" min="1"><td>
						<button class="btn" name="add_to_cart" type="submit" value="Add to Cart">
						<img src="../images/out_o_stock.jpg" width="80px" border="0px" >
					    </button>
                        
                     <?php } else if($total <= $level){?>
                        
                        <form id = "form1" name="foorm1" method="POST" action="cart.php">
                        <input type="hidden" name="product_id" value="<?php echo $id; ?>" >					
					    <input align="center" style="width:80px; font-size:25px; padding:3px" type="number" name="qty_requested" value="1" min="1"><td>
                        <button class="btn" name="add_to_cart" type="submit" value="Add to Cart">
						<img src="../images/check_stock.jpg" width="80px" border="0px" >
					    </button>
                        </form>	
							
						<?php } else if($total > $level){?>							
						
                    <form id = "form1" name="foorm1" method="POST" action="cart.php">					
                    <input type="hidden" name="product_id" value="<?php echo $id; ?>" >					
					<input align="center" style="width:80px; font-size:25px; padding:3px" type="number" name="qty_requested" value="1" min="1"><td>
                    <button class="btn" name="add_to_cart" type="submit" value="Add to Cart">
						<img src="../images/add-to-cart-11.gif" width="80px" border="1px" align="center">
					</button>
					</form>
                    
                     <?php }
						else if($total == 0 || $total < $level){?>
                        
                        <input align="center" type="image" name="button" src="../images/out_o_stock.jpg" width="80px" border="0px" />
                       
                    
                  </td>	
					<td><input type="text" value="<?php echo $amount; ?>"/></td>
				  <?php }}}}}?>			
                </tr>
				</tr>
				
		        <tr>
					<td align="center" colspan="8" style="background-color:blue; color: white; padding:10px"><strong>CURRENT WORTH OF GOODS : GHS <?php echo number_format(@$tot_amount, 2); ?></strong></td>
					<td></td>
				</tr>
  </table>
</form>
	   
	   </p>
    </fieldset>
  </div>

  <!-- Footer -->
  <?php include"../inc/lay_footer.php";?>
