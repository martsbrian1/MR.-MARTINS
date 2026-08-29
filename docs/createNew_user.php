<?php include"../inc/lay_header.php";
 include("../inc/serials.php");
 $msg = "";
if (isset($_SESSION['username']) && $_SESSION['status'] ==1){
$sysuser = $_SESSION['username'];
$status = $_SESSION['status'];

$msg .="<li> Welcome  $sysuser !.</li>";
}else{
header('location:../index.php');
}
?>


<?php
// Fetch category for dropdown
	//$result = mysqli_query($conn, "SELECT id, name FROM categories ORDER BY name ASC");
	
	$errorMessage = "";

	if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_staff'])) {
		$salt = "estate"; 
		if(empty($_POST['fullname'])){
			$errorMessage .= "You forgot to enter fullname of staff!";
		}
		
		if(empty($_POST['sys_user'])){
			$errorMessage .= "You forgot to enter user / nick name!"; 
		}
		
		if(empty($_POST['phone'])){
			$errorMessage .= "You forgot to enter phone number!"; 
		}
		
		if(empty($_POST['password'])){
			$errorMessage .= "You forgot to enter password";
		}
		
		if(empty($_POST['password2'])){
			$errorMessage .= "You forgot to repeat password";
		}
		
		if($_POST['password']!= $_POST['password2']){
			$errorMessage .= "<li>The passwords does not match ...</li>";
		}
		
		if(empty($errorMessage)){
        // Check for duplicate trans_key
		$transkey = mysqli_real_escape_string($conn, $_POST['transkey']);
		
        $sql = "SELECT COUNT(*) AS cnt FROM tbusers WHERE trans_key = '".mysqli_real_escape_string($conn, $transkey)."'";
        $res = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($res);

        if ($row['cnt'] > 0) {
            $errorMessage .= '<li><h4>Sorry, entry already committed to database!</h4></li>';
        } else {
            // Check for duplicate sysuser
            if ($sysuser) {
                $sql = "SELECT COUNT(*) AS sys FROM tbusers WHERE user = '".mysqli_real_escape_string($conn, $_POST['sys_user'])."'";
                $res = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($res);

                if ($row['sys'] > 0) {
                    $errorMessage .= '<h3>Sorry, the username / nick name: '.$reg_no.' - is already in use. Choose another one.</h3>';
                } else {
					$fullname   = mysqli_real_escape_string($conn, $_POST['fullname']);
					$sysuser    = mysqli_real_escape_string($conn, $_POST['sys_user']);
					$phone      = mysqli_real_escape_string($conn, $_POST['phone']);
					$password   = mysqli_real_escape_string($conn, $_POST['password']);
					$password2  = mysqli_real_escape_string($conn, $_POST['password2']);
					$status     = mysqli_real_escape_string($conn, $_POST['status']);
					$transkey   = mysqli_real_escape_string($conn, $_POST['transkey']);
					$token 		= md5("$salt$password"); 
					

				$sql = "INSERT INTO tbusers (user, email, phone, password, status, trans_key) 
						VALUES ('$sysuser', '$fullname', '$phone', '$token', '$status', '$transkey')";
				
				$errorMessage = "<li><label style='color:green'><strong>Details saved successfully...</strong> <a href='./add_product.php'>BACK</a></label></li> ";
				mysqli_query($conn, $sql);
			}
	}	}
}}
?>
  <link href="../css/bootstrap.min.css" rel="stylesheet">
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
  <div class="dew"> <span style="color:blue; font-size:24px"><a href="#" title="Home"><?php echo @$title_main;?></a> </span></div>
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
        
						<label for="fullname"> <strong>STAFF'S FULL NAME :</strong></label> 
						<div class="mb-2"><input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo @$fullname;?>" placeholder="Staff's Full Name" required /></div>
						
						<label for="sys_user"> <strong>STAFF'S USER OR NICK NAME :</strong></label> 
						<div class="mb-2"><input type="text" class="form-control" id="sys_user" name="sys_user" value="<?php echo @$sysuser;?>" placeholder="Staff's User or Nick Name" required /></div>
						
						<label for="phone"> <strong>STAFF'S PHONE NUMBER :</strong></label> 
						<div class="mb-2"><input type="text" class="form-control" id="phone" name="phone" value="<?php echo @$phone;?>" placeholder="Staff's Phone Number" required /></div>
						
						<label for="password"> <strong>STAFF'S PASSWORD :</strong></label> 
						<div class="mb-2"><input type="password" class="form-control" id="password" name="password" value="" placeholder="Staff's Password" required /></div>
						
						<label for="password2"> <strong>REPEAT STAFF'S PASSWORD :</strong></label> 
						<div class="mb-2"><input type="password" class="form-control" id="password2" name="password2" value="" placeholder="Repeat Staff's Password" required /></div>
						
						<label for="password"> <strong>ROLE :</strong></label> 
						<select class="form-control" id="status" name="status" required >
								<option value=""> Select one </option>
								<option value="0">Help Desk</option>
								<option value="0">Staff</option>
								<option value="1">Administrator</option>										
						</select>						
										
						<div align="right"> 
						<label for="transkey_gen"> Tick box below to confirm entries:</label> 
						<input id = "transkey" type="hidden" name ="transkey" value="" >
      
					<input type="checkbox" name="gentranskey" id="transkey_gen" style="width:20px;" class="form-control" id="confirm_entries">
					<button style="width:300px; margin:5px 5px; float: right;" class="btn btn-info" name="add_staff" value="Register">Submit</button>
					</div>
					</form>			
    </fieldset>
  </div>

  <!-- Footer -->
  <?php include"../inc/lay_footer.php";?>
