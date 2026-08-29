<?php
if (isset($_GET['cmd'])) { 	
	 @$product_id = (int)$_POST['product_id'];
     @$qty_requested   = (int)$_POST['qty_requested'];

}
?>

<style>
  button{
	  margin: 0 auto;
	  display: blocl;
	  width: 50px;
	}
</style>


<?php
$category_id = isset($_GET['cmd']) ? (int)$_GET['cmd'] : 0;
//$search      = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

//$sql = "SELECT id, sku, category_id, name, price, qty, reorder_level FROM products WHERE 1 LIMIT 15";
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
		INNER JOIN products ON products.product_id = store_inventory.product_id WHERE store_id = $store_id ";





// Apply category filter if selected
if ($category_id > 0) {
    $sql .= " AND category_id = $category_id";
}

$result = mysqli_query($conn, $sql);


?>

			
        <table class="sample" border="1" cellspacing="2" cellpadding="2" width="100%" style="margin-top:-10px" bordercolor="red">          
                <tr>                
                  <td colspan="9" class="register_table"> 
                  <div align="right">
                  <div align="center" style="color:#0080FF; font-size:22px; color:brown; font-weight:bold">
                  <?php 
				   if(isset($_GET['cmd'])){
					  $cid2= (int)$_GET['cmd'];
				  }else{
					  $cid2=0;
				  } 
                   $inv2 = "SELECT name FROM categories WHERE category_id = $cid2 LIMIT 1";
	               $res = mysqli_query($conn, $inv2);
	               $r = mysqli_fetch_array($res);
			         @$links=$r["name"];
	               // }
					
	              ?>
                            
				  <?php echo "General Items ";?> In Stock As At   <?php echo date("Y/m/d h:i:s");?>  </div>
				  </div>
				  </td>
                </tr>				
                
                   <tr  style="font-size:14px">
                    <th class="foo">NO.</th>
					<th class="foo">CODE</th>
					<th class="foo">PRODUCT</th>                  
                    <th class="foo">PRICE </th>	
					<th class="foo">QTY. IN STOCK </th>	
                    <th class="foo">QTY. TO BUY </th>
                    <th class="foo">ACTION</th>
                </tr>			
								
		<?php 
			$sql .= " AND categories_id = '@$cid' ORDER BY name";
		
		//$qry = mysql_query($sql);
		$count = 0;
		$tot_qty_issued = 0;
		$tot_amount =0;
		
		if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
			$reorder_level = 10;
			$count = ++$count;
			$id    = $row['product_id'];	
			$total = $row['stock_quantity'];	
			$name  = $row['name'];	
			//$tot_qty_issued += $total; 
			
			$level = $reorder_level;	
			$price = $row['selling_price'];	
			//$amount = $total * $price;
			//$tot_amount += $amount;
		?>				

				<tr style="font-size:14px;">
				    <td class="boo" width="20px" align="center"><a title="Modify material info" class="flat" href='prod_modify.php?cat_id=<?php echo $row["product_id"]; ?>'><?php echo $count ; ?></a></td>
					<td class="boo" width="60px" align="center" style="font-size:16px; pading-left:15px"><strong><a title="View detailed info" class="kkk" href='search_material_id.php?prod_id=<?php echo $row["product_id"]; ?>'><?php echo $row['sku'] ?></a></strong></td>	
                    <td class="boo" width="300px" align="left" style="font-weight:bold; font-size:16px; color:blue; padding-left:15px;"><?php echo $row['name']; ?> <small> </small></td>
                    <td class="boo" width="" style="font-size:25px" align="center"><b><?php echo $row['selling_price'];?></b></td>
					<td class="boo" width="" align="center"; style="color:blue; font-size: 30px"><strong><?php echo $row['stock_quantity'];?></strong></td>
                   
                    <td class="boo" width="" align="center">
                    <?php
						if($total <= 0){?>
                        
						<input align="center" style="width:80px; font-size:25px"; padding:3px" type="number" name="qty_requested" value="1" min="1"><td>
						<button class="btn" name="add_to_cart" type="submit" value="Add to Cart">
						<img src="../images/out_o_stock.jpg" width="80px" border="0px" >
					    </button>
                        
                     <?php } else if($total <= $level){?>
                        
                        <form id = "form1" name="foorm1" method="POST" action="cart.php">
                        <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>" >					
					    <input align="center" style="width:80px; font-size:25px"; padding:3px" type="number" name="qty_requested" value="1" min="1"><td>
                        <button class="btn" name="add_to_cart" type="submit" value="Add to Cart">
						<img src="../images/check_stock.jpg" width="80px" border="0px" >
					    </button>
                        </form>	
							
						<?php } else if($total > $level){?>							
						
                    <form id = "form1" name="foorm1" method="POST" action="cart.php">					
                    <input type="text" name="product_id" value="<?php echo $row['product_id']; ?>" >					
					<input align="center" style="width:80px; font-size:25px"; padding:3px" type="number" name="qty_requested" value="1" min="1"><td>
                    <button class="btn" name="add_to_cart" type="submit" value="Add to Cart">
						<img src="../images/add-to-cart-11.gif" width="80px" border="1px" align="center">
					</button>
					</form>
                    
                     <?php }
						else if($total == 0 || $total < $level){?>
                        
                        <input align="center" type="image" name="button" src="../images/out_o_stock.jpg" width="80px" border="0px" />
                       
                    
                  </td>		
				  <?php }}}?>			
                </tr>
		        	        
  </table>
</form>      
  