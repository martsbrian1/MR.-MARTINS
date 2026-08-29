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
if (isset($_POST['submit'])) {  
    $q = trim(mysqli_real_escape_string($conn, $_POST['criteria'])); 

    // Correct SQL with parentheses
    $sql = "SELECT * FROM products WHERE status = 1 AND (name LIKE ? OR sku = ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $qry = '%' . $q . '%';
    $stmt->bind_param("ss", $qry, $q);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<table border='1' cellpadding='5' cellspacing='0'>";
        echo "<tr><th>Name</th><th>SKU</th><th>Price</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['sku']) . "</td>";
            echo "<td>" . htmlspecialchars($row['price']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color:red;'>No products found.</p>";
    }

    $stmt->close();
}
?>

<!-- Form should always be visible -->
<form action="" method="post">            
  <table border="0" cellspacing="0" cellpadding="1" width="90%" align="center">
    <tr>
      <td colspan="8" align="center" height="40px" style="color:#0000FF;"><hr></td>
    </tr>
    <tr>
      <td colspan="8" class="register_table">
        <div align="right"> 
          <table border=0>
            <tr>
              <td>    
                <p style="font-weight:bold; color:green;">
                  <em>Please enter Product Name or Code.</em>:
                </p>
                <input type="text" name="criteria" 
                       value="<?php echo @$_POST['criteria'];?>" 
                       style="margin:5px auto;width:200px !important;
                              box-shadow:2px 2px 2px 2px #555;
                              padding:5px;border-radius:3px;
                              border:1px solid red;" />
              </td> 
            </tr>
            <tr>
              <td align='right'>        
                <input style="width:180px; padding:5px; margin-top:10px" 
                       type="submit" name="submit" value="Search">
              </td> 
            </tr>
          </table>
        </div>
      </td>
    </tr>
  </table>
</form>

	   
	   </p>
    </fieldset>
  </div>

  <!-- Footer -->
  <?php include"../inc/lay_footer.php";?>
