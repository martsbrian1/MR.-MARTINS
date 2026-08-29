<style>
option {
	color: blue;
	font-weight: bold;
}	


</style>                
   
	<form method="post" action="">
    <div class="col-md-10">
      <select style="font-size:18px" name="product_id" class="form-control" required>
        <option value="">-- select preferred product --</option>
        <?php foreach ($products as $p): ?>
          <option value="<?= (int)$p['id'] ?>"><?= htmlspecialchars($p['name']) ?> ( In stock: <?= (int)$p['qty'] ?> )</option>
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