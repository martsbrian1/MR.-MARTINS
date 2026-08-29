<?php include("../inc/lay_header.php");?>

<?php
//error_reporting(E_ERROR);

if(isset($_GET['id'])){
print	 $id = $_GET['id'];
}
?>
	
	<script language="JavaScript" type="text/javascript">
	function checkDelete(){
		return confirm('Are you sure?');
	}
	</script>
	
<?php
$sql  = mysqli_query($conn,"UPDATE customers SET status = 0 WHERE customer_id ='$id'");
header("Location: view_customers.php");
//echo $stmt->rowCount(). " records UPDATED successfully";

?>