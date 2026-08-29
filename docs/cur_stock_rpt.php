<?php //include"../inc/lay_header.php";
if (isset($_SESSION['username']))
	{
	$sysuser 	= $_SESSION['username'];
	$status 	= $_SESSION['status'];
	$store_id  	= $_SESSION['store_id'];	
	$msg .="<li> Welcome  $sysuser!.</li>";

	}else{
	header('location:../index.php');
	}


$result = mysqli_query($conn, "
    SELECT
products.sku,
products.`name`,
categories.`name` AS category,
products.selling_price,
store_inventory.stock_quantity
FROM
store_inventory
INNER JOIN products ON products.product_id = store_inventory.product_id
INNER JOIN categories ON categories.category_id = products.category_id
WHERE store_id = '$store_id' ");
?>

<!DOCTYPE html>
<html>
<head>
    <title></title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<!--<body class="p-4">-->

<div class="container">
<div id="t1">
    <h2 style="text-align:center"><?php echo $company . "<br> Current Stock For Shop $store_id  As At " . date("l jS \of F Y H:i:s");?></h2>
    
	<table class="table table-bordered table-striped mt-3" style= "width:95%">
        <!--<thead>-->
            <tr align="center">
                <td align="center"><strong>No.</strong></td>
				<td align="center"><strong>Product</strong></</td>
                <td align="center"><strong>Code</strong></</td>
                <td align="center"><strong>Category</strong></</td>
                <td align="center"><strong>Quantity</strong></</td>
                <td align="center"><strong>Selling Price</strong></</td>
                <!--<td align="center"><strong>Cost</strong></td>-->
            </tr>
        <!--</thead>
        <!--<tbody>-->
            <?php 
			$count = 0;
			while ($row = mysqli_fetch_assoc($result)) {
			$count = ++$count;
			?>
                <tr style= "">
                    <td width="5px" align="center"><?= $count; ?></td>
					<td width="60px"style="color:blue"><strong><?= $row['name'] ?></strong></td>
                    <td width="10px" align="center"><?= $row['sku'] ?></td>
                    <td width="10px"><?= $row['category'] ?></td>
                    <td width="5px" align="center" style="font-size:25px"><strong><?= $row['stock_quantity'] ?></strong></td>
                    <td width="8px" align="center"><?= $row['selling_price'] ?></td>
                    <!--<td width="8px" align="right"></td>-->
                </tr>
            <?php } ?>
        <!--</tbody>-->
    </table>
</div>
</div>
</body>
</html>