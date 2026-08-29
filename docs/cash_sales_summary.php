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

	if(isset($_POST['btn_submit'])){
				
		$errorMessage ="";		
		$dt_start = mysqli_real_escape_string($conn, $_POST['dt_start']); 
		$dt_end = mysqli_real_escape_string($conn, $_POST['dt_end']);  

		print $sql = "SELECT store_id, SUM(total_amount) AS total_sales, COUNT(*) AS total_transactions
        FROM sales
        WHERE store_id = '$store_id' AND DATE(sale_date) BETWEEN '$dt_start' AND '$dt_end'";
		
	  
		if(empty($_POST['dt_start']) || empty($_POST['dt_end'])){
			$errorMessage .= "<li>The  date range fields are required, please!</li>";
		} else {		
	   
		print $sql = "SELECT store_id, SUM(total_amount) AS total_sales, COUNT(*) AS total_transactions
        FROM sales
        WHERE store_id = '$store_id' AND DATE(sale_date) BETWEEN '$dt_start' AND '$dt_end'";		 
		$result =mysqli_query($conn, $sql);		  
		}
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
       <p>
	   
				<table class="sample" border="0" cellspacing="0" cellpadding="1" width="100%" align="center" bordercolor="#F1F1F1">
<tr> 
<td>

  <form action="" method="post" name="form1">  
   
 <div style="float:right">
  <b>Date Range From: </b>  <input name="dt_start" type="date" style="margin:5px auto;width:130px !important;box-shadow:2px 2px 2px 2px #555;padding:2px;border-radius:3px;border:1px solid red;" >
  &nbsp;  &nbsp; &nbsp; &nbsp;
 <b> To:  </b> <input name="dt_end" type="date" style="margin:5px auto;width:130px !important;box-shadow:2px 2px 2px 2px #555;padding:2px;border-radius:3px;border:1px solid red;" >
  
 <input class="submitbtn" type="submit" name="btn_submit" value="View" style="margin-bottom:1px; margin-left:10px; padding:7px">
 <button class="submitbtn"  onClick="printContent('t1')" style="padding:7px">Print </button> 
</div>

</td>
</tr> 
</table>      
       
	   
<table class="sample" border="0" cellspacing="0" cellpadding="1" width="100%" align="center" bordercolor="#F1F1F1">
              <tr>
<form name="form1" method="POST" action="" /> 
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
    <table class="sample" border="1" cellspacing="2" cellpadding="4" width="100%" align="center" bordercolor="red" style="margin-bottom:25px">
    <tr> 
    		 <td colspan="8" align="center" bgcolor="#000000" style="color:white; font-size:20px;"><strong>
               <?php echo $company;?> <br /> 
               <?php echo $address;?>

               </strong></td>
                </tr>          
                <tr>
                  <td colspan="8" class="register_table">
				  <div align="right">		       
				      
					  <div align="center" style="color:#0080FF; font-size:18px; color:brown; font-weight:bold"> Sales Report <?php // echoing the product name here`
					  				  
					  $sdate = $dt_start; 
					  $edate = $dt_end;
					  ?>
					 
			      Between <?php echo $sdate ; ?>  And <?php echo $edate; ?> </div>
				  </div>
				  </td>
                </tr>				
                
                  <tr>
                  <th class="foo"> NO. </th>
					<th class="foo"> SHOP </th>
                    <th class="foo"> TOTAL SALES</th>
                    <th class="foo"> TRANSACTIONS </th>						
                    </tr>				
								
				<?php 
				$count = 0;				
				
				//if(!$result) { 
					//die("Query failed: " . mysqli_error($conn)); 
				//}else{
					//$errorMessage .= "<li>Wait on me please!<li>";				
					while ($row = mysqli_fetch_assoc($result)) { 
					$count = ++$count;					
					$store = $row['store'];				
					$sales = $row['total_sales'];	
					$transact = $row['total_transactions'];	
					?>	
				<tr style="font-size:14px;">
				    <td class="boo" width="40" align="center"><?php echo $count ; ?></td>
					<td class="boo" width="90px" align="center"><?php echo $store; ?></td>
                    <td class="boo" width="204" style="padding-left:15px"; align="" ><?php echo number_format($sales , 2); ?> </td>
				    <td class="boo" width="55" align="right" style="font-weight:bold"><?php  echo $transact; ?></td>
                </tr>	
				
				<?php }?>
                <tr>
  <td colspan="5" align="right"> <strong><em></em></strong></td>
 


 <td width="65" align="right"><hr> <strong><?php 
//if(isset($tot_amount)){
//print number_format($tot_amount, 2) ; }?> </strong></td>
    
 </tr>						
		        
  </table>
</form>
	   
	   </p>
    </fieldset>
  </div>

  <!-- Footer -->
  <?php include"../inc/lay_footer.php";?>
