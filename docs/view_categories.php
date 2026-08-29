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
  <div style="text-align:right; font-size:13px; color:#fff; margin-top:-38px"><?php echo date("l jS \of F Y");?>
  <?php echo ucwords("$msg"); ?></div>
</header>


  <!-- Sidebar -->  
<?php include"../inc/lay_sidebar.php";?>

  <!-- Main content -->
  <div class="content">
  <div class = "new_products" >
  <div class = "row" style = "padding-left:140px" >
  <div class="dew"> <span style="color:blue; font-size:24px"><a href="#" title="Home"><?php echo @$title_main;?></a> | <a href ="add_category.php"> Add Category</a></span> </div>
    <fieldset style="border:2px solid green; border-radius:8px; width="50%">
       
	   <?php

$action= isset($_GET['action']) ? $_GET['action'] : "";
 
// if it was redirected from delete.php
if($action=='deleted'){
    echo"<div>Record was deleted.</div>";
}
 
	
	$stmt = $conn->prepare("SELECT * FROM categories WHERE  1 ORDER BY name");
	
	$stmt->execute();
            
 
//echo"<a href='./view_inactive.php'>Inactive Membership Record</a>";
 
if($sysuser>0){ //check if more than 0 record found
 
    echo"<table border='1' width='80%'; align='center'>";//start table
     
        //creating our table heading
        echo'<tr bgcolor="blue" style="padding-left:25px;color: #fff;">';
            echo"<th style='padding: 8px; padding-left:15px; color: #fff;'>#</th>";
            echo"<th style='padding-left:25px; color: #fff;'>Name</th>";
			echo"<th style='text-align:center; color: #fff;'>Action</th>";            
        echo"</tr>";
         
        $count = 0;
		$result = $stmt->get_result();
			while ($row = $result->fetch_assoc()) {
				$count = ++$count;
				$id = $row['category_id'];
             
            //creating new table row per record
            echo"<tr bgcolor='#FFCC66'>";
                echo "<td width='30px'; align='center'; style='padding:9px'>" .$count. "</td>";
				echo "<td width='350px' ; style='padding-left:15px'><strong>" .$row['name'] . "</strong></td>";                
                echo"<td width='200px'; align='center';>";
                    //we will use this links on next part of this post
                    echo"<a href='modify_category.php?id={$id}'>Modify</a>";
                   // echo" / ";
					
					//we will use this links on next part of this post
                    //echo"<a href='activate_product.php?id={$id}'>Activate</a>";
                    //echo" / ";

                    //we will use this link to flag product record.
                    //echo"<a href='flag_product.php?id={$id}' onclick='delete_homeCell( {$id} );'>Flag-Out</a>";
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
