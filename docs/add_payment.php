<?php include"../inc/lay_header.php";?>
<style>
option {
	color: blue;
	font-weight: bold;
}	


</style>                
   
	<form method="post" action="">
    <div class="col-md-6">
      <select style="font-size:22px" name="customer_id" class="form-control" required>
        <option value="">-- select preferred product --</option>
        <?php 										
			$sq = mysqli_query($conn, "SELECT * FROM customers");
				while ($c = mysqli_fetch_assoc($sq)) {
					$sel = ($c['customer_id'] == $customer_id) ? "selected" : "";
					echo "<option value='{$c['customer_id']}' $sel>{$c['customer_name']}</option>";
				}
		?>	
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