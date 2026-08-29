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
  <div class = "row" style = "padding-left:130px; margin-right:30px" >
  <div class="dew" style = "margin-left:10px"> <span style="color:blue; font-size:24px"><a href="#" title="Home"><?php echo @$title_main;?></a></span> </div>
   <fieldset style="border:2px solid green; border-radius:8px;">
    <?php
		$errorMessage = "";
	
		if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {			

			if(empty($_POST['dt_start']) || (empty($_POST['dt_start']))){
				$errorMessage .=  "<li>You forgot to specify preferred dates!</li>";
			}
			
			$dt_start   = mysqli_real_escape_string($conn, $_POST['dt_start']);
			$dt_end     = mysqli_real_escape_string($conn, $_POST['dt_end']);
			
			$stmt = $conn->prepare("SELECT
					sale_items.sale_id,
					sales.sale_date,
					products.`name`,
					sale_items.quantity,
					products.selling_price,
					sales.store_id
					FROM
					sales
					INNER JOIN sale_items ON sales.id = sale_items.sale_id
					INNER JOIN products ON products.product_id = sale_items.product_id
					WHERE store_id = '$store_id' AND sale_date BETWEEN '$dt_start' AND '$dt_end'");	
			$stmt->execute();
			$result = $stmt->get_result();
		}
?>


	<p>
	   
	   <form action="" method="post" name="form1">  
    
			<table class="sample" border="0" cellspacing="0" cellpadding="1" width="100%" align="center" bordercolor="#F1F1F1">
<tr> 
<td>

  <form action="" method="post" name="form1">  
   
 <div style="float:right">
  <b>Date Range From: </b>  <input name="dt_start" type="date" style="margin:5px auto;width:130px !important;box-shadow:2px 2px 2px 2px #555;padding:2px;border-radius:3px;border:1px solid red;" >
  &nbsp;  &nbsp; &nbsp; &nbsp;
 <b> To:  </b> <input name="dt_end" type="date" style="margin:5px auto;width:130px !important;box-shadow:2px 2px 2px 2px #555;padding:2px;border-radius:3px;border:1px solid red;" >
  
 <input class="submitbtn" type="submit" name="submit" value="View" style="margin-bottom:1px; margin-left:10px; padding:7px">
 <button class="submitbtn"  onClick="printContent('t1')" style="padding:7px">Print </button> 
</div>

</td>
</tr> 
</table>      
       
	<form name="form1" method="POST" action="" />    
<table class="sample" border="0" cellspacing="0" cellpadding="1" width="100%" align="center" bordercolor="#F1F1F1">
              <tr>

              <td colspan="8" align="center" height="40px" style="color:blue;">
			 
<div class="passErr" style="font-size:14px; color:red; text-align:left;">
<?php
	if(!empty($errorMessage)) 
	{
		echo("<p>Result:...</p>\n");
		echo("<ul><b>" . $errorMessage . "</b></ul>\n");
	}
 ?>
</div>			  
			  </td>			  
              </tr>
  </table>
  <div id = 't1'>


	  <table border = "1"; width="100% cellpadding = "8px"> 
	   <tr>
                  <td colspan="6" ">
				  <div align="right">		       
				      
					  <div align="center" style="color:#0080FF; font-size:20px; color:brown; font-weight:bold">
                    <span style="color:#0080FF; font-size:36px; color:brown; font-weight:bold"> SAMGABA COSMETICS - ACCRA <br></span>
					(SALES ACCOUNT FOR  <?php echo $store; ?>
					  
					  <?php 
					  @$sdate = strtotime($start_dt); //echo date("j F Y", $date); 
					  @$edate = strtotime($end_dt);
					  ?>
					 
			      BETWEEN <?php echo date( $dt_start) ; ?>  AND  <?php echo date( $dt_end); ?>) </div>
				  </div>
				  </td>
                </tr>				
                
                  <tr>
                    <td  align="center"><strong> No. </strong></td>
					<td  align="center"><strong> Date </strong></th>
					<td  align="center"><strong> Product </strong></td>
                    <td  align="center"><strong> Price</strong></td>
					<td  align="center"><strong> Qty</strong></td>
                    <td  align="center"><strong> Cost </strong></td>										
									
                 </tr>				
								
				<?php 
				$count = 0;
				$cost = 0;
				$tot_amount = 0;
				
				//$result = $stmt->get_result();
				while ($row = $result->fetch_assoc()) {
					$count = ++$count;
					$count = ++$count;					
					$name = $row['name'];
					$quantity = $row['quantity'];
					$price = $row['selling_price'];					
					$cost  = $quantity * $price;
					$tot_amount += $cost;
				?>	
				<tr style="font-size:14px;">
				    <td class="boo" width="4%" align="center"><?php echo $count; ?></td>
					<td class="boo" width="20%" align="center"><?php echo $row['sale_date']; ?></td>
					<td class="boo" width="30%" align=" "><?php echo $row['name']; ?></td>
					<td class="boo" width="8%" align="right"><?php echo $row['selling_price']; ?></td>	
                    <td class="boo" width="15%" align="center" ><?php echo $row['quantity']; ?> </td>
					<td class="boo" width="15%" align="right" style="font-size:16px; padding-right:10px" ><?php echo number_format($cost, 2); ?> </td>
                </tr>	
				
				<?php }?>
                <tr>
  <td colspan="5" align="right" style="font-size:18px"> <strong><em>Total Amount Received (GHC):</em></strong></td>

 <td width="17%" align="right"style="font-size:16px; padding-right:10px" > <strong><?php 
if(isset($tot_amount)){
print number_format($tot_amount, 2) ; }?> </strong></td>
 </tr>	   
 </table>	   
	   </p>
    </fieldset>
  </div>

  <!-- Footer -->
  <?php include"../inc/lay_footer.php";?>
