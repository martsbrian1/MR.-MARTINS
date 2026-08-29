<?php include"../inc/lay_header.php";

require_once 'functions_cus.php';
$conn = db_connect();
$customers = fetch_all_customers();

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
	   
	    <style>
option {
	color: blue;
	font-weight: bold;
}	


</style>                
   
	<form method="post" action="">
    <div class="col-md-10">
      <select style="font-size:18px" name="customer_id" class="form-control" required>
        <option value="">-- select preferred customer --</option>
        <?php foreach ($customer_id as $p): ?>
          <option value="<?= (int)$p['customer_id'] ?>"><?= htmlspecialchars($p['customer_name']) ?> </option>
        <?php endforeach; ?>
      </select>	 
	<p>
    <div class="mb-10"><input name="qty" type="number" class="form-control" min="1" required placeholder="Quantity to add"></div>
    </div> <br><br><br><br>
	
	<div align="right" class="mb-10">
	<label for="transkey_gen" style="color:blue"> Check box below to confirm entries:</label> 
		<input id = "transkey" type="hidden" name ="transkey" value="" >
		<input type="checkbox" name="gentranskey" id="transkey_gen" style="width:20px;" class="form-control" id="confirm_entries" required>
					
	<button name= "add_stock" class="btn btn-primary">Add To Stock</button> 
  </div>
  
  </div>
</form>
   <!-- </div>-->
	
  
	</div>
	<br><br><br><br><br>
	</div></fieldset>
<?php //include("../inc/footer.php");?>
	   
	   </p>
    </fieldset>
  </div>

  <!-- Footer -->
  <?php include"../inc/lay_footer.php";?>
