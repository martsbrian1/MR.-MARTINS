<?php include"../inc/lay_header.php";
error_reporting(E_ERROR);
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
$errorMessage = "";
$successMessage = "";

if (isset($_POST['btn_update'])) {
    $cat_id = isset($_POST['category_id']) ? (int)$_POST['category_id'] : 0;
    $transkey = isset($_POST['transkey']) ? trim($_POST['transkey']) : '';

    if (empty($_POST['product_ids']) || !is_array($_POST['product_ids'])) {
        $errorMessage .= "You did not select any items to update.<br>";
    } elseif (empty($transkey)) {
        $errorMessage .= "Transaction verification key is missing. Please retry.<br>";
    } else {
        // Prepare the update statement once for optimal execution speed
        $update_stmt = $conn->prepare("UPDATE products SET cost_price = ?, selling_price = ? WHERE product_id = ?");
        
        if ($update_stmt === false) {
            die("SQL Preparation Error: " . $conn->error);
        }

        $updated_count = 0;
        
        // Loop through checked items only
        foreach ($_POST['product_ids'] as $product_id) {
            $id = (int)$product_id;
            
            // Extract the corresponding text field values for this specific product ID
            $new_Cprice = isset($_POST['cost_price'][$id]) ? (float)$_POST['cost_price'][$id] : 0.00;
            $new_Sprice = isset($_POST['selling_price'][$id]) ? (float)$_POST['selling_price'][$id] : 0.00;

            // Bind values: 'ddi' represents decimal, decimal, integer
            $update_stmt->bind_param("ddi", $new_Cprice, $new_Sprice, $id);
            
            if ($update_stmt->execute()) {
                $updated_count++;
            }
        }
        
        $update_stmt->close();
        $successMessage = "Successfully updated prices for $updated_count products!";
    }
}
?>



<?php
require_once( './functions_category.php');
$conn 		= db_connect();
$category   = fetch_all_categories();
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

<style>
	input[type="checkbox"]{
		transform: scale(1.5);
	}
</style>

  <!-- Main content -->
  <div class="content">                                                                                                                                                                                                                                                                                                                                                                                                                                                            
  <div class = "new_products" >
  <div class = "row" style = "padding-left:130px" >
  <div class="dew"> <span style="color:blue; font-size:24px"><a href="#" title="Home"><?php echo @$title_main;?></a></span> </div>
    <fieldset style="border:2px solid green; border-radius:8px;">
       <p>
		<form method="post" id="frm" name="frm" action="">
    <!-- Your anti-double entry transkey element -->
    <input type="hidden" name="transkey" value="<?php echo isset($transkey) ? htmlspecialchars($transkey) : 'GEN_12345'; ?>">

    <div align="center" style="color:#0000FF; font-size:18px">
    <table>
        <tr align="center">
            <td><strong>Select Category :</strong></td>		  
            <td>
                <select style="width:300px; font-size:18px" name="category_id" class="form-control" onchange="this.form.submit()">
                    <option value="">-- Select preferred category --</option>
                    <?php foreach ($category as $p): ?>
                        <?php $selected = (isset($_POST['category_id']) && $_POST['category_id'] == $p['id']) ? 'selected' : ''; ?>
                        <option value="<?= (int)$p['category_id'] ?>" <?= $selected ?>><?= htmlspecialchars($p['name']) ?> </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
    </table>
    <form name="f1" method ="POST" action = "" >
    <table width="100%" border="1" style="padding:5px">	
		<tr>
			<td colspan=5 align="center">
			<?php
				$q	=	$_POST['category_id'];
				$sql = "SELECT * FROM categories WHERE category_id = ?";
				$stmt = $conn->prepare($sql);
				$stmt->bind_param("i", $q);
				$stmt->execute();
				$result = $stmt->get_result();

				while ($row = $result->fetch_assoc()) {
					// Process each row
					$cat = strtoupper($row['name']) ;
				}
			echo "<h2 style='color:blue'><strong>$cat</strong></h2>";
			?>
			</td>
		</tr>
        <tr >
            <td style = "padding:8px" align="center">NO.</td>
            <td align="center"><strong>NAME</strong></td>
            <td align="center"><strong>COST PRICE</strong></td>
            <td align="center"><strong>SELLING PRICE</strong></td>
            <td align="center"><strong>TICK </strong></td>
        </tr>
        
    <?php 				
        $count = 0;
        // Keep the UI rendering safe when the page first loads
        if (isset($_POST['category_id']) && !empty($_POST['category_id'])) {
            $cat_id = (int)$_POST['category_id'];
            $stmt = $conn->prepare("SELECT product_id, name, cost_price, selling_price FROM products WHERE status = 1 AND category_id = ?");
            $stmt->bind_param("i", $cat_id);
        } else {
            $stmt = $conn->prepare("SELECT product_id, name, cost_price, selling_price FROM products WHERE status = 1 LIMIT 10");
        }

        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $count      = ++$count;
            $id         = $row['product_id'];
            $product    = $row['name']; 
            $Cprice     = $row['cost_price'];
            $Sprice     = $row['selling_price'];				
    ?>
        <tr>
            <td align = "center"><strong><?php echo $count; ?></strong></td>
            <td style = "padding-left:6px; padding:8px"><strong><?php echo $product; ?></strong></td>
            <td align = "center"><strong><input style="width:90px ; text-align: right" type="number" step="0.01" class="sell-input" name="cost_price[<?php echo $id; ?>]" value="<?php echo $Cprice; ?>"><strong></td>
            <td align = "center"><strong><input style="width:90px ; text-align: right" type="number" step="0.01" class="price-input" name="selling_price[<?php echo $id; ?>]" value="<?php echo $Sprice; ?>"></strong></td>
            <!-- FIX: Renamed checkbox to product_ids[] containing the row ID -->
            <td align = "center"><input style="width:17px" type="checkbox" name="product_ids[]" value="<?php echo $id; ?>"></td>
        </tr>
    <?php } ?>
        <tr>				
            <td colspan="4" align="right" height="54px"><strong style="color:blue">Tick box to confirm selected Products:</strong></td>
            <td align="center"><!--<input type="checkbox" id="select_all">-->
			<input id = "transkey" type="hidden" name ="transkey" value="" >      
			<input type="checkbox" name="gentranskey" id="transkey_gen" style="width:20px;" class="form-control" id="confirm_entries">				
			</td>			
        </tr>	 
        <tr> 
            <td colspan="3"></td>
            <td colspan="2" align="center">
                <input style="padding:8px" type="submit" name="btn_update" value="UPDATE SELECTED"> 
            </td>
        </tr> 
    </table>	
</form>	   
	   </p>
    </fieldset>
  </div>

  <script>
document.addEventListener("DOMContentLoaded", function() {
  // Attach event listeners to all price and quantity inputs
  document.querySelectorAll(".price-input, .sell-input").forEach(function(input) {
    input.addEventListener("input", function() {
      // Find the checkbox in the same row
      let row = input.closest("tr");
      let checkbox = row.querySelector("input[type='checkbox']");
      if (checkbox) {
        checkbox.checked = true; // auto‑check when input changes
      }
    });
  });
});
</script>

  
  
  <!-- Footer -->
  <?php include"../inc/lay_footer.php";?>
