<?php include"../inc/lay_header.php";
 $msg = "";
if (isset($_SESSION['username']) && $_SESSION['status'] ==1){
$sysuser = $_SESSION['username'];
$status = $_SESSION['status'];
$store_id = $_SESSION['store_id'];

$msg .="<li> Welcome  $sysuser !.</li>";
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
  <?php echo ucwords("$msg"); ?></div>
</header>


  <!-- Sidebar -->  
<?php include"../inc/lay_sidebar.php";?>

  <!-- Main content -->
  <div class="content">
  <div class = "new_products" >
  <div class = "row" style = "padding-left:90px" >
  <div class="dew" style = "margin-left: 40px;" > <span style="color:blue; font-size:24px"><a href="#" title="Home"><?php echo @$title_main;?></a> <a href ="add_product.php"> Add Product</a></span> </div>
    <fieldset style="border:2px solid green; border-radius:8px; width="80%">
       
	   <?php

$action= isset($_GET['action']) ? $_GET['action'] : "";
 
// if it was redirected from delete.php
if($action=='deleted'){
    echo"<div>Record was deleted.</div>";
}
 
	
	$stmt = $conn->prepare("SELECT
							products.product_id,
							products.sku,
							products.`name`,
							products.`qty`,
							products.`status`,
							categories.`name` AS cat,
							products.reorder_level
							FROM
							products
							INNER JOIN categories ON categories.category_id = products.category_id
							WHERE  1 ORDER BY name");	
	$stmt->execute();
            
 
//echo"<a href='./view_inactive.php'>Inactive Membership Record</a>";
 
if($sysuser>0){ //check if more than 0 record found
 
    echo"<table border='1'; width='90%'; style='margin-left: 30px ; align: center'>";//start table
     
        //creating our table heading
        echo'<tr bgcolor="#FFCC66" >';
            echo"<th class='foo' align='center'>#</th>";
            echo"<th class='foo' style='align:center'>CODE</th>";
			echo"<th class='foo' style='align:center'>NAME</th>";
			echo"<th class='foo' style='align:center'>STATUS</th>";
			echo"<th class='foo' style='align:center'>CATEGORY</th>";
			echo"<th class='foo' style='align:center'>IN STOCK</th>";
			echo"<th class='foo' style='align:center'>RE-ORDER LEVEL</th>";
			echo"<th class='foo' align='center'>ACTION</th>";            
        echo"</tr>";
         
        $count = 0;
		$show  = 0; // bgcolor="#000000"
		$result = $stmt->get_result();
			while ($row = $result->fetch_assoc()) {
				$count = ++$count;
				$id = $row['product_id'];
				$stat = $row['status'];
				
				
				if($stat == 1){
					$col="lightgreen";
				}else{				
					$col="red";
				}
             
            //creating new table row per record   bgcolor=<?php echo $col;
            echo"<tr bgcolor='#FFCC66';>";
                echo "<td width='20px'; align='center'; style='padding:5px'>" .$count. "</td>";
				echo "<td width='40px' ; style='padding:5px'>" .$row['sku'] . "</td>";
				echo "<td width='230px' ; style='padding-left: 15px'><strong>" .$row['name'] . "</strong></td>";
				?>
				
				<td bgcolor=<?php echo $col;?>  width="50px" align="center"></td>
				<!--<td width='100px'; style='padding-left: 15px; bgcolor=' .$col . '></td>";-->
				
				<?php
				echo "<td width='100px' ; style='padding-left:15px';>" .$row['cat'] . "</td>";
				echo "<td width='50px' ; style='padding:5px; text-align:center; font-weight:bold; color:blue'>" .$row['qty'] . "</td>";
				echo "<td width='50px' ; style='padding:5px; text-align:center'>" .$row['reorder_level'] . "</td>";
				//echo "<td width='270px' ; style='padding:5px'>" .$row['flag'] . "</td>";
				//echo "<td width='50px'; align='center'; style='padding:5px'>" .$row['flag'] . "</td>";
                
                echo"<td width='100px'; align='center';>";
                    //we will use this links on next part of this post
                    echo"<a href='modify_product.php?pid={$id}'>Edit</a>";
                    echo" / ";
					
					//we will use this links on next part of this post
                    echo"<a href='activate_product.php?id={$id}'>Activate</a>";
                    echo" / ";

                    //we will use this link to flag product record.
                    echo"<a href='flag_product.php?id={$id}' onclick='delete_product( {$id} );'>Flag</a>";
                echo"</td>";
            echo"</tr>";
        }
     
    //end table
    echo"</table>";
     
}
 
//if no records found
else{
    echo"No records found.";
}
 
?>

    </fieldset>
  </div>

  <!-- Footer -->
  <?php include"../inc/lay_footer.php";?>
