<?php include"../inc/lay_header.php";
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
	    
			$result = mysqli_query($conn, "
			SELECT
			Sum(sales.total_amount) AS total_sales,
			Count(*) AS total_transactions,
			tbretail_stores.store
			FROM sales
			INNER JOIN tbretail_stores ON tbretail_stores.id = sales.store_id
			WHERE store_id = '$store_id' AND DATE(sale_date) BETWEEN '$start_dt' AND '$end_dt'
			GROUP BY store_id "); 
			//}
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
				<div class="passErr" style="font-size:14px; color:red; text-align:left">
				<?php
					if(!empty($errorMessage)) 
					{
						echo("<p>Result: ...</p>\n");
						echo("<ul>" . $errorMessage . "</ul>\n");
					}
				 ?>
				</div>
	<!-- Form should always be visible -->
	<form action="" method="post">            
	  <table border="0" cellspacing="0" cellpadding="1" width="90%" align="center">
		<tr>
      <td colspan="8" align="center" height="40px" style="color:#0000FF;"><hr></td>
    </tr>
    <tr>
      <td colspan="8" class="register_table">
        <div align="right"> 
          <table border=0>
            <tr> 
	<td align="right" style="margin-right:100px">

	  

	<label style="font-weight:bold;">               
	Date Range From: &nbsp;&nbsp;<input name="start_dt" type="date" value="<?php echo @$start_dt; ?>"  style="margin:5px auto;width:140px !important;box-shadow:2px 2px 2px 2px #555;padding:2px;border-radius:3px;border:1px solid red;" />
	  
	 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  To:  </label> <input name="end_dt" type="date" value="<?php echo @$end_dt; ?>" style="margin:5px auto;width:140px !important;box-shadow:2px 2px 2px 2px #555;padding:2px;border-radius:3px;border:1px solid red;" />
	  
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <input class="submitbtn" type="submit" name="submit" value="View" style="margin-bottom:1px; margin-left:10px; width:90px; !important;box-shadow:2px 2px 2px 2px #555;padding:2px;border-radius:3px;border:1px solid red;">
	 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <button style="width:60px; margin-bottom:15px !important;box-shadow:2px 2px 2px 2px #555;padding:2px;border-radius:3px;border:1px solid red;" class="submitbtn"  onClick="printContent('t1')">Print </button> 


	</td>
	</tr> 
            <tr>
              <td align=''>        
                <!--<input style="width:180px; padding:5px; margin-top:10px" 
                       type="submit" name="submit" value="Search">-->
              </td> 
            </tr>
          </table>
        </div>
      </td>
    </tr>
  </table>
  <form name="form1" method="POST" action="" /> 
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
</form>

	   
	   </p>
    </fieldset>
  </div>

  <!-- Footer -->
  <?php include"../inc/lay_footer.php";?>
