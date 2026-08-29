<?php include"../inc/lay_header.php";
$msg = "";
	
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
	
  <!-- Header -->  
  
  <header>
  <div class="d-flex align-items-center">    
    <h2 class="mb-0 text-white" style="text-align:center; line-height:8px"><strong><?php echo @$company . " - " . @$store; ?></strong></h2>
  </div>
<div style="text-align:right; font-size:13px; color:#fff; margin-top:-38px"><?php echo date("l jS \of F Y");?>
  <?php echo ucwords("$msg"); ?></div>
</header>


  <!-- Sidebar -->  
<?php //include"../inc/lay_sidebar.php";?>

  <!-- Main content -->
  <div class="content" style="margin-left: 90px">
  <div class="dew"> <span style="color:blue; font-size:16px"><a href="#" title="Home"><?php echo @$title_main;?></a></span> </div>
  
    <!--<fieldsety style="border:2px solid green; border-radius:8px;" > -->
	<div> 
	<img src="../images/logo.png" width=100px;  alt="Company Logo">
	</div>
       <?php include"./cart_new1.php";?>
    <!--</fieldsety>-->
  </div>

  <!-- Footer -->
  <?php include"../inc/lay_footer.php";?>
