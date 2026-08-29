<?php 
include"../inc/lay_header.php";
include("../inc/serials.php");
 
$msg = "";
if (isset($_SESSION['username']) && $_SESSION['status'] ==1){
$user = $_SESSION['username'];
$status = $_SESSION['status'];

$msg .="<li> Welcome  $user !.</li>";
}else{
header('location:../index.php');
}
?>


<?php

	$errorMessage = "";

	if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_category'])) {
		$category       = mysqli_real_escape_string($conn, $_POST['category']);
		$transkey   	= mysqli_real_escape_string($conn, $_POST['transkey']);
	
		if(empty($_POST['category'])){
			$errorMessage .= "You forgot to specify category!";
		}		
		
		if(empty($_POST['transkey'])){
			$errorMessage .= "You forgot to confirm your entries";
		}
		
		if(empty($errorMessage)){					
			$key = "SELECT trans_key FROM categories WHERE trans_key = '".mysqli_real_escape_string($conn, $transkey)."' LIMIT 1";
			$fed = mysqli_query($conn, $key);
			
			if ($fed && mysqli_num_rows($fed) > 0 ) {			  
				$errorMessage .= '<li><h4>Sorry, entry already committed to database!</h4></li>';
			}else{
				$sql = "INSERT INTO categories (name, trans_key) 
						VALUES ('$category', '$transkey')";
				
				$errorMessage = "<li><label style='color:green'><strong>Details saved successfully...</strong> <a href='./add_category.php'>BACK</a></label></li> ";
				mysqli_query($conn, $sql);
			}
		}
}
?>
  
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
  <div class = "row" style = "padding-left:100px" >
  <div class="dew"> <span style="color:blue; font-size:22px"><a href="#" title="Home"><?php echo @$title_main;?></a> | <a href = "view_categories.php"> View Categories</a></span> </div>
    <fieldset style="border:2px solid green; border-radius:8px; width:80%; padding:30px; margin: 0 auto;">
    			
				<div class="passErr" style="font-size:14px; color:red; text-align:left">
				<?php
					if(!empty($errorMessage)) 
					{
						echo("<p>Result: ...</p>\n");
						echo("<ul>" . $errorMessage . "</ul>\n");
					}
				 ?>
				</div>
				
					<form class="form-horizontal" name="contact_form" id="contact_form" enctype="multipart/form-data" method="post" action="">
        
						<label for="prod_name"> Product Category</label> 
						<div class="mb-2"><input type="text" class="form-control" id="category" name="category" value="<?php echo @$category;?>" placeholder="Enter product category" required /></div>
							
																
						<div align="right"> 
						<label for="transkey_gen"> Check box below to confirm entry:</label> 
						<input id = "transkey" type="hidden" name ="transkey" value="" >
      
					<input type="checkbox" name="gentranskey" id="transkey_gen" style="width:20px;" class="form-control" id="confirm_entries">
					<button style="width:150px; margin:5px 5px; float: right;" class="btn btn-info" name="add_category" value="Register">Submit</button>
					</div>
					</form>
			
    </fieldset>
  </div>

  <!-- Footer -->
  <?php include"../inc/lay_footer.php";?>
