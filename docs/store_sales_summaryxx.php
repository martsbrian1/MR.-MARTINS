<?php include"../inc/lay_header.php";
if (isset($_SESSION['username']))
	{
	$sysuser 	= $_SESSION['username'];
	$status 	= $_SESSION['status'];
	$store_id  	= $_SESSION['store_id'];
	
	$msg .="<li> Welcome  $sysuser!.</li>";

	}else{
	header('location:../index.php');
	}
?>

<?php
		if(isset($_POST['submit'])){				
		$errorMessage ="";		
		 
      $start_dt = mysqli_real_escape_string($conn, $_POST['start_dt']); 
      $end_dt   = mysqli_real_escape_string($conn, $_POST['end_dt']); 	  
	  
	  if(empty($start_dt))
	  {
		  $errorMessage .= "<li>The 'from' date field is required, please!</li>";
	  }
	  
	  if(empty($end_dt))
	  {
		  $errorMessage .= "<li>The 'to' date field is required, please!</li>";
	  } 	  
	    
	    $sql = "SELECT
		$result = mysqli_query($conn, "
		SELECT store_id, SUM(total_amount) AS total_sales, COUNT(*) AS total_transactions
        FROM sales
        WHERE store_id = '$store_id' AND DATE(sale_date) BETWEEN '2026-5-1' AND '2026-8-3' ");
?>

<!DOCTYPE html>
<html>
<head>
    <title></title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<!--<body class="p-4">-->


<div id="t1">
    <h2 style="text-align:center"><?php echo $company . "<br> Cash Sale  Summary As At " . date("l jS \of F Y H:i:s");?></h2>
    
		<form action="" method="post" name="form1">  
	  <table class="sample" border="0" cellspacing="0" cellpadding="1" width="100%" align="right" bordercolor="#F1F1F1">
	<tr> 
	<td align="rightr" style="margin-right:100px">

	  

	<label style="font-weight:bold;">               
	Date Range From: &nbsp;&nbsp;<input name="start_dt" type="date" value="<?php echo @$start_dt; ?>"  style="margin:5px auto;width:140px !important;box-shadow:2px 2px 2px 2px #555;padding:2px;border-radius:3px;border:1px solid red;" />
	  
	 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  To:  </label> <input name="end_dt" type="date" value="<?php echo @$end_dt; ?>" style="margin:5px auto;width:140px !important;box-shadow:2px 2px 2px 2px #555;padding:2px;border-radius:3px;border:1px solid red;" />
	  
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <input class="submitbtn" type="submit" name="submit" value="View" style="margin-bottom:1px; margin-left:10px; width:90px; !important;box-shadow:2px 2px 2px 2px #555;padding:2px;border-radius:3px;border:1px solid red;">
	 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <button style="width:60px; margin-bottom:15px !important;box-shadow:2px 2px 2px 2px #555;padding:2px;border-radius:3px;border:1px solid red;" class="submitbtn"  onClick="printContent('t1')">Print </button> 


	</td>
	</tr> 
	</table> 
	</div>
	</div>
	
	<table class="table table-bordered table-striped mt-3" style= "width:95%">
        <!--<thead>-->
            <tr align="center">
                <td align="center"><strong>No.</strong></td>
				<td align="center"><strong>Store</strong></</td>
                <td align="center"><strong>Total Sales</strong></</td>
                <td align="center"><strong>Total Transactions</strong></</td>
                <!--<td align="center"><strong>Quantity</strong></</td>
                <td align="center"><strong>Selling Price</strong></</td>
                <td align="center"><strong>Cost</strong></td>-->
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
					<td width="60px" align="center" style="color:blue"><strong><?= $row['store_id'] ?></strong></td>
                    <td width="10px" align="center">GHS <?=  number_format($row['total_sales'] , 2) ?></td>
                    <td width="10px" align="center" ><?= $row['total_transactions'] ?></td>
                    <!--<td width="5px" align="center" style="font-size:25px"><strong><?= $row['stock_quantity'] ?></strong></td>
                    <td width="8px" align="center"><?= $row['selling_price'] ?></td>
                    <td width="8px" align="right"></td>-->
                </tr>
            <?php } ?>
        <!--</tbody>-->
    </table>
</div>
</div>
</body>
</html>