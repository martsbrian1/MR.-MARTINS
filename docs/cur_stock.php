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
		<?php echo ucwords("$msg"); ?>
    </div>
</header>

  <!-- Sidebar -->  
<?php include"../inc/lay_sidebar.php";?>

  <!-- Main content -->
  <div class="content">
  <div class = "new_products" >
  <div class = "row" style = "padding-left:120px" >
  <div class="dew" style = "margin-left:8px" > <span style="color:blue; font-size:24px"><a href="#" title="Home"><?php echo @$title_main;?></a></span><span style= "float:right"><button onClick="printContent('t1')">--Print--</button></span> </div>
    <fieldset style="border:2px solid green; border-radius:8px; width:98%; margin-left:10px">
       <?php include"./cur_stock_rpt.php";?>
    </fieldset>
  </div>

  <!-- Footer -->
  <?php include"../inc/lay_footer.php";?>
