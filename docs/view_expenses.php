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
		$errorMessage ="";	
		
		if(isset($_POST['submit'])){
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

				$stmt = $conn->query("SELECT * FROM tbexpenses WHERE store_id = '$store_id' AND date_xp BETWEEN '$start_dt' AND '$end_dt' ORDER BY date_xp");
			//}
		//}
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
  <div class="dew" style = "margin-left:10px"> <span style="color:blue; font-size:24px"><a href="#" title="Home"><?php echo @$title_main;?></a> View / Modify Expense | <a href='add_expense.php'>Create New Record</a></span> </div>
   <fieldset style="border:2px solid green; border-radius:8px;">
       <p>
		 
                 
                <br/>
				
				<form action="" method="post" name="form1">  
				  <table class="sample" border="0" cellspacing="0" cellpadding="1" width="100%" align="center" bordercolor="#F1F1F1">
				<tr> 
				<td> 

				<div align="right">

				<label style="font-weight:bold;">               
				Date Range From:  &nbsp; <input name="start_dt" type="date" value="<?php echo @$start_dt; ?>" style="margin:5px auto;width:110px !important;box-shadow:2px 2px 2px 2px #555;padding:2px;border-radius:3px;border:1px solid red;" />
				  &nbsp;&nbsp; To: &nbsp; <input name="end_dt" type="date" value="<?php echo @$end_dt; ?>" style="margin:5px auto;width:140px !important;box-shadow:2px 2px 2px 2px #555;padding:2px;border-radius:3px;border:1px solid red;" />
				 </label>&nbsp;&nbsp;
				  
				 
				&nbsp;&nbsp; <input class="submitbtn" type="submit" name="submit" value="View" style="margin-bottom:1px; margin-left:10px; width:90px; !important;box-shadow:2px 2px 2px 2px #555;padding:2px;border-radius:3px;border:1px solid red;">
				&nbsp;&nbsp; <button style="width:60px; margin-bottom:15px !important;box-shadow:2px 2px 2px 2px #555;padding:2px;border-radius:3px;border:1px solid red;" class="submitbtn"  onClick="printContent('t1')">Print </button> 

				</div>
				</td>
				</tr> 
				</table> 			
						<form action="123.php" method="post" name="form2"> 
                   <table border='1' width='100%' > 
                        
                       <tr bgcolor="#FFCC66">
                            <td align='center'><strong>Nos </strong></td>
                           <td align='center'><strong>Date </strong></td>							
							<td align='center'><strong>Purpose </strong></td>
							<td align='center'><strong>Staff </strong></td>	
                           <td align='center'><strong>Amount </strong></td>
							<td align='center'><strong> Action </strong></th>                            
                        </tr>
						
                         <?php
						 
                        $count = 0; 			
                                
                            foreach($stmt as $row) { 
                            $count = ++$count; 
                            $id = $row['id'];
                            $s = $row['purpose']; 
                            //creating new table row per record
                            echo"<tr>";
                                echo "<td width=''; align='center'; style='padding:5px'>" .$count. "</td>";
                                echo "<td width='' ; style='padding:5px'>" .$row['date_xp'] . "</td>";
								echo "<td width='' align='left'; style='padding:5px'>" .$s. "</td>";
								echo "<td width='' align='center'; style='padding:5px'>" .$row['receiver'] . "</td>";
								
								
								//echo "<td width='' align='center'; style='padding:5px'>" .$row['staff'] . "</td>";
								echo "<td width='' align='right'; style='padding:5px'>" .number_format($row['amt_exp'],2) . "</td>";
                                echo"<td width='' align='center'>";
                                    //we will use this links on next part of this post
                                    echo"<a href='edit_expense.php?id={$id}'>Edit</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; 
                                   
                                    echo"<a href='del_expense.php?id={$id}' onclick='delete_expense( {$id} );'>Delete</a>";
                                echo"</td>";
                            echo"</tr>";
                        }
                     
                    //end table
                    echo"</table> </form>";          
              
                }
                ?>
	   

	   
	   </p>
    </fieldset>
  </div>

  <!-- Footer -->
  <?php include"../inc/lay_footer.php";?>
