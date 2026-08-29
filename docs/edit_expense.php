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

 if(isset($_GET['id'])){
   $search_term = $_GET['id'];
?>
 
<?php


    
    if(isset($_POST['submit'])){
		$store_id = $store_id;
		$date_xp = $_POST['date_xp'];
		$receiver = $_POST['receiver'];
		$purpose = $_POST['purpose'];
		//$staff = $_POST['staff'];
		$amt_exp = $_POST['amt_exp'];
     
        //write query
        $sql= "UPDATE tbexpenses SET `date_xp` = ?, `receiver` = ?, `purpose` = ?, `staff` = ?, `amt_exp` = ? WHERE `id`= ? ";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("ssssdi", $date_xp, $receiver, $purpose, $sysuser, $amt_exp, $search_term);
		$stmt->execute();
			
			if($stmt){
				
				print $errorMessage =  "<h4><label style='color:green'><strong>Details modified successfully...</strong> </label> <a href='view_expenses.php'>*** BACK ***</a></h4>";
				//header('location: view_expense.php');
		
			}else{
				print $errorMessage = "Record was not added. Please try again later.";
				//exit;
			}
	//mysqli_close();
	} 
	}
		
  

?>
<?php
	//include'../connections/connectz.php'; 


		$sql = "SELECT * FROM tbexpenses WHERE `id`= ?";
		$stmt = mysqli_prepare($conn, $sql);
		mysqli_stmt_bind_param($stmt, "i", $search_term);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);		
		while($row = mysqli_fetch_assoc($result)) {		
			$date_xp = $row['date_xp'];
			$receiver = $row['receiver'];
			$purpose = $row['purpose'];
			//$staff = $row['staff'];
			$amt_exp = $row['amt_exp'];
		//}
?>
 
<!--we have our html form here where user information will be entered-->
<form action=''method='post'border='0'>
    <table style="margin: 0 auto">
        
        
        <tr>
            <td><strong>Date:</strong></td>
            <td><input type='text'name='date_xp' value= "<?php echo $date_xp; ?>" style="margin:5px auto;width:100px !important;box-shadow:2px 2px 2px 2px #555;padding:5px;border-radius:8px;border:1px solid red;" /></td>
        </tr>
        
        <tr>
            <td><strong>Paid To:</strong></td>
            <td><input type='text'name='receiver' value= "<?php echo $receiver; ?>" size="35px" style="margin:5px auto;width:200px !important;box-shadow:2px 2px 2px 2px #555;padding:5px;border-radius:8px;border:1px solid red;" /></td>
        </tr>
        
        <tr>
            <td><strong>Purpose:</strong></td>
            <td><textarea name='purpose' style="margin:5px auto;width:300px !important;box-shadow:2px 2px 2px 2px #555;padding:5px;border-radius:8px;border:1px solid red;" /><?php echo $purpose; ?></textarea></td>
        </tr>
        <!--<tr>
            <td><strong>Staff:</strong></td>
            <td><input type='text'name='staff' value= "<?php //echo $sysuser; ?>" size="35px" style="margin:5px auto;width:100px !important;box-shadow:2px 2px 2px 2px #555;padding:5px;border-radius:8px;border:1px solid red;" readonly /></td>
        </tr>-->
        <tr>
            <td><strong>Amount:</strong></td>
            <td><input type='text'name='amt_exp' value= "<?php echo $amt_exp; ?>" size="35px" style="margin:5px auto;width:100px !important;box-shadow:2px 2px 2px 2px #555;padding:5px;border-radius:8px;border:1px solid red;" /></td>
        </tr>
        
        <tr>
            <td></td>
            <td>
                <input type='hidden' name='action' value='edit'/>
                <input type='hidden' name='id' value="<?php echo $row['id']; ?>"/>
                <input type='submit' name='submit' value='  Modify Expenditure  '/>
                 
               
            </td>
        </tr>
 <?php }//} ?>
    </table>
</form>  
 
    

	   
	   </p>
    </fieldset>
  </div>

  <!-- Footer -->
  <?php include"../inc/lay_footer.php";?>
