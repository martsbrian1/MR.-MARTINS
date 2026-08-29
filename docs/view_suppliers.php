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
  <div class="dew" style = "margin-left: 40px;" > <span style="color:blue; font-size:24px"><a href="#" title="Home"><?php echo @$title_main;?></a> | <a href ="add_suppliers.php"> Add supplier</a></span> </div>
    <fieldset style="border:2px solid green; border-radius:8px; width="80%">
       
	   <?php

$action= isset($_GET['action']) ? $_GET['action'] : "";
 
// if it was redirected from delete.php
if($action=='deleted'){
    echo"<div>Record was deleted.</div>";
}
 
	
	$stmt = $conn->prepare("SELECT * FROM suppliers WHERE 1 ORDER BY supplier_name");	
	$stmt->execute();
            
 
//echo"<a href='./view_inactive.php'>Inactive Membership Record</a>";
 
if($sysuser>0){ //check if more than 0 record found
 
    echo"<table border='1'; width='100%'; style='margin-left: 30px ; align: center'>";//start table
     
        //creating our table heading
        echo'<tr bgcolor="#FFCC66" >';
            echo"<th class='foo' align='center'>#</th>";
            echo"<th class='foo' style='align:center'>NAME</th>";
			//echo"<th class='foo' style='align:center'>ADDRESS</th>";
			//echo"<th class='foo' style='align:center'>PHONE</th>";
			//echo"<th class='foo' style='align:center'>STATUS</th>";
			echo"<th colspan='4' class='foo' align='center'>ACTION</th>";            
        echo"</tr>";
         
        $count = 0;
		$show  = 0; // bgcolor="#000000"
		$dt_start="2026-01-1";
		$dt_end=date('Y-m-d'); 
		
		$result = $stmt->get_result();
			while ($row = $result->fetch_assoc()) {
				$count = ++$count;
				$id = $row['supplier_id'];				
             
            //creating new table row per record   bgcolor=<?php echo $col;
            echo"<tr bgcolor='#FFCC66';>";
                echo "<td width='20px'; align='center'; style='padding:5px'>" .$count. "</td>";
				echo "<td width='300px' ; style='padding:5px'; font-size:25px><strong>" .$row['supplier_name'] . "</strong></td>";
				//echo "<td width='230px' ; style='padding-left: 15px'><strong>" .$row['address'] . "</strong></td>";
				//echo "<td width='40px' ; style='padding:5px'>" .$row['phone'] . "</td>";										
				
					echo"<td colspan='4' width=''; align='center';>";
                    //we will use this links on next part of this post
                    echo"<a style='background-color:white'; href='make_supplier_payment.php?pid={$id}'><strong>Make Payment</strong></a>";
                    echo" / ";
					
					echo"<a style='background-color:yellow'; href='sup_account.php?pid=$id" . '&sdp=' .$dt_start. '&edp=' .$dt_end."'> &nbsp;&nbsp;View Accounts </a>" ;             
					echo" / ";
					
					//echo"<a style='background-color:white'; href='update_openingBal.php?pid={$id}'>Update Opening Balance</a>";
                    //echo" / ";
					
					echo"<a href='modify_suplier.php?pid={$id}'>Edit</a>";
                    echo" / ";					
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
