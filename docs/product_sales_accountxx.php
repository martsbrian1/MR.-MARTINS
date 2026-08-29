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
	   
	   <?php                
 $date_del = date('Y-m-d');
 
	/* if(isset($_GET['pid'])){
		$pid = $_GET['pid'];
		$dt_start = $_GET['sdp'];
		$dt_end = $_GET['edp']; */

?>              
               
 <div class="passErr" style="font-size:12px; color:red;">
<?php
	if(!empty($errorMessage)) 
	{
		echo("<p>Please take notice of the following:</p>\n");
		echo("<ul>" . $errorMessage . "</ul>\n");
	}
 ?>
</div>
 
 <div id = 't1'>
 
 <table border="1" width='100%' align="center" cellspacing="5" cellpadding="5">
	<tr>
		<td align="center">
		<?php
			/* $stmt1 = $conn->prepare("SELECT * FROM customers WHERE customer_id = '$pid'");
			$stmt1->execute();		
			$result1 = $stmt1->get_result();			
			
			while ($c = $result1->fetch_assoc()) {
				$name = $c['customer_name'];
				
				print "<span style='font-size:26px; font-weight:bold;'>CUSTOMER ACCOUNTS <br>Name: $name; </span>";
			} */
		?>
		</td>
	<tr>  
 </table>
<table border="1" width='100%' align="center" cellspacing="5" cellpadding="5">
		<tr style="background-color:#000; color:#fff;"; align="center">
		<td align="center">No.</td>
		<td align="center">PRODUCT</td>
		<td align="center">QUANTITY SOLD</td>
        <td align="center">AMOUNT </td>
		<!--<td align="center">Debit</td>
        <td align="center">Credit</td>
        <td align="center">Balance (GHC)</td>-->
</tr>
<?php 

//check if the starting row variable was passed in the URL or not
if (!isset($_GET['startrow']) or !is_numeric($_GET['startrow'])) {
  //we give the value of the starting row to 0 because nothing was found in URL
  $startrow = 0;
//otherwise we take the value from the URL
} else {
  $startrow = (int)$_GET['startrow'];
}

$prev = $startrow - 40;
//now this is the link..
//echo '<a href="'.$_SERVER['PHP_SELF'].'?startrow='.($startrow+40).'">Next |</a>';
//only print a "Previous" link if a "Next" was clicked
//if ($prev >= 0)
//    echo '<a href="'.$_SERVER['PHP_SELF'].'?startrow='.$prev.'"> Previous</a>';


?>

		

<?php
			$stmt = $conn->prepare("SELECT				
						m.store_id,
							p.name AS prod,
						SUM(ABS(m.quantity)) AS qty_sold,
						SUM(ABS(m.quantity) * p.selling_price) AS total_amount
					FROM stock_movements m
					JOIN products p ON p.product_id = m.product_id
					WHERE m.movement_type = 'SALE' AND store_id='$store_id'
					GROUP BY m.product_id");	
					
			$stmt->execute();  //WHERE customers.customer_id = '$pid' AND trans_date BETWEEN '$dt_start' AND '$dt_end'");
		
		$count = 0;
		$bal = 0;
		$tot_debit = 0;
		$tot_credit = 0;
		$tot_bal = 0;
		
		$result = $stmt->get_result();
			while ($g = $result->fetch_assoc()) {		
			$count++;	
			$product      = $g['prod'];
			$qty_sold     = $g['qty_sold'];
			$amount       = $g['total_amount'];
			//$bal       = ($open_bal + $debit) - $credit;
			//$tot_debit += $debit;
			//$tot_credit += $credit;
			//$tot_bal += $bal;

		echo "<tr>";
		echo "<td align='center'>" . "<label>" . $count . "</labe> </td>";
		echo "<td align='left' width='410px' style='padding-left:15px;'>" . "<label>" . $product . "</labe> </td>";

		
		echo "<td align='center' style='padding-left:25px;'>" . "<label>" . $g['qty_sold']  ."</labe></td>";
		echo "<td align='right' style='padding-left:25px;'>" . "<label>" . number_format($amount, 2) ."</labe></td>";
		//echo "<td align='right'>" . "<label>" . number_format($g['debit'],2) . "</labe> </td>";
		//echo "<td align='right'>" . "<label>" . number_format($g['credit'],2) . "</labe> </td>";
		//echo "<td align='right'>" . "<label>" . number_format($bal,2) . "</labe> </td>";

		//echo "<td align='left' style='padding-left:15px;'>" . "<label>" . $g['remarks'] . "</labe> </td>";
		?>
		<td align="center" style="padding-left:10px;"><!--<a href="../docs/del_cus_details.php?id=<?php //echo $g['reg_no']; ?>" onclick="return confirm('Are you sure to delete and post info to Archives?')">Post to Archives</a></td>
		--><td>
		</td>
		</tr>
		<?php
		}
		?>
		<!--<tr>		
			<td colspan=4 align="RIGHT"><strong>TOTAL</strong></td>
			<td align="RIGHT"><strong><?php echo  number_format($tot_debit, 2); ?></strong></td>
			<td align="RIGHT"><strong><?php echo  number_format($tot_credit, 2); ?></strong></td>
			<td align="RIGHT"><strong><?php echo "GHC ". number_format($tot_bal, 2); ?></strong></td>			
		</tr>-->
		</table>
		</form>
		</div>
<?php
//now this is the link..
//echo '<a href="'.$_SERVER['PHP_SELF'].'?startrow='.($startrow+5).'">Next |</a>';

//$prev = $startrow - 10;

//only print a "Previous" link if a "Next" was clicked
//if ($prev >= 0)
  //  echo '<a href="'.$_SERVER['PHP_SELF'].'?startrow='.$prev.'"> Previous</a>';


//mysqli_close();

?>

	   
	   </p>
    </fieldset>
	<!--<a style="background-color:black; color:white ;padding:6px;" href="view_customers_all.php"><strong>View Customers</strong></a>-->  <button style="float:right" onClick="printContent('t1')">Click to Print</button>
  </div>

  <!-- Footer -->
  <?php include"../inc/lay_footer.php";?>
