<?php include"../inc/lay_header.php";?>
	
  <!-- Header -->  
  
  <header>
  <div class="d-flex align-items-center">    
    <h2 class="mb-0 text-white" style="text-align:center; line-height:8px"><strong><?php echo @$company; ?></strong></h2>
  </div>
</header>


  <!-- Sidebar -->  
<?php //include"../inc/lay_sidebar.php";?>

  <!-- Main content -->
  <div class="content">
  <div class = "new_products" >
  <div class = "row" style = "padding-left:140px" >
  <div class="dew"> <span style="color:blue; font-size:16px"><a href="#" title="Home"><?php echo @$title_main;?></a></span> </div>
    <!--<fieldsety style="border:2px solid green; border-radius:8px; width:80%; padding:30px;"> -->
       <h1>RESTRICTED AREA!!</h1>
                <center>
                
                <img src="../images/block.jpg" width="466" height="224">
                <br /><br />
                <h1 style="color:red"><span><br>WARNING!! </span><br> Please, you are NOT allowed in there! <br/> Contact Admin. </h1><a href="./staff_console.php">Back</a>
				
				
                </center>
    <!--</fieldset>-->
  </div>

  <!-- Footer -->
  <?php include"../inc/lay_footer.php";?>
