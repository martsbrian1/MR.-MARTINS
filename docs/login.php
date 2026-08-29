<?php
session_start();
 include('../connect/config.php');  
?>

<?php
$errorMessage = '';
error_reporting(1);
if (isset($_POST['btnlogin'])) {
    $sysuser 	= $_POST['sysuser'];
    $password 	= $_POST['password'];
    $status   	= $_POST['status'];
	$store_id   = $_POST['store_id'];
	
    // Escape inputs
    $sysuser 	= mysqli_real_escape_string($conn, $sysuser);
    $password 	= mysqli_real_escape_string($conn, $password);
    $status 	= mysqli_real_escape_string($conn, $status);
	$store_id 	= mysqli_real_escape_string($conn, $store_id);

    // Validation
    if ($status == "Select one") {
        $errorMessage .= "<li> Please select role... </li>";
    }
    if ($sysuser == "") {
        $errorMessage .= "<li> Please specify user name... </li>";
    }
    if ($password == "") {
        $errorMessage .= "<li> Please enter password... </li>";
    } 
	if ($store_id == "") {
        $errorMessage .= "<li> Please specify your current store on duty... </li>";
    }else {
        // Query
        $query = "SELECT * FROM tbusers WHERE user = '$sysuser'";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_row($result);

            $salt = "estate";
            $token = md5("$salt$password");

            if ($token == $row[4] && $status == $row[5] && $row[5] == 1) {
                session_start();
                $_SESSION['username'] = $sysuser;
                $_SESSION['pass']     = $token;
                $_SESSION['status']   = $status;
                $_SESSION['type']     = $status;
				$_SESSION['store_id'] = $store_id;                
				header('Location: ./admin_console.php');
                exit;
            } elseif ($token == $row[4] && $status == $row[5] && $row[5] == 0) {
                session_start();
                $_SESSION['username'] = $sysuser;
                $_SESSION['pass']     = $token;
                $_SESSION['status']   = $status;
				$_SESSION['store_id'] = $store_id;

                //header('Location: docs/staff_console.php');
				header('Location: ./staff_console.php');
                exit;
            } else {
                $errorMessage .= "<li>Invalid username / password combination...</li>";
            }
        } else {
            $errorMessage .= "<li>User not found...</li>";
        }
    }

    mysqli_close($conn);
}
?>


<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Login</title>
	<link href="./styles/dave.css" rel="stylesheet" type="text/css">
	<meta name="viewport" content="width=device-width,intial-scale=1.0">

</head>


<body id="body">

	<header id="mainHeader">
	<img src="../images/banner_N.png" width="100%"/>	   
	</header>
	<div id="mainContent">
		<div id="contenter">
			<article id="topcontent">
				
				<content>
					<p>			
<form name="form1" id="mylogin" method="POST" action="">
<table id="table" border="5" style=margin:auto;  bordercolor="#33CCFF">
<tr>
<tr>
  <td colspan="2"> <div class="passErr" style="font-size:16px; color:red;">
       <?php
		    if(!empty($errorMessage)) 
		    {
			    echo("<p>Result...</p>\n");
			    echo("<ul>" . $errorMessage . "</ul>\n");
            }
        ?>

</div></td>
  </tr>
    
<td border="0" align="right">Role: </td>
<td>
<select name="status" id="status" style="margin:5px 0;width:200px !important;box-shadow:2px 2px 2px 2px #555;padding:5px;border-radius:3px;border:1px solid red;" >
        <option value="">Select one</option>
		<option value=0 >Help Desk</option>
		<option value=0 >Staff</option>	
		<option value=1 >Admin</option>
		
		</select>
	</td>
	</tr>
	
	<tr>
<td align="right">Username:</td><td><input name="sysuser" type="text" value="" size="35" maxlength="50"  style="margin:5px auto;width:140px !important;box-shadow:2px 2px 2px 2px #555;padding:5px;border-radius:3px;border:1px solid red;" /></td>
 </tr>
<tr>     
<td align="right">Password:</td> <td><input name="password" type="password" size="35" maxlength="50" style="margin:5px auto;width:140px !important;box-shadow:2px 2px 2px 2px #555;padding:5px;border-radius:3px;border:1px solid red;" /></td>
</tr>
<tr>

<tr>
<td align="right">Store on duty:</td><td>
<!--<input name="store_id" type="text" value="" size="35" maxlength="50"  style="margin:5px auto;width:140px !important;box-shadow:2px 2px 2px 2px #555;padding:5px;border-radius:3px;border:1px solid red;" />-->
<select class="form-control" style="margin:5px auto;width:200px !important;box-shadow:2px 2px 2px 2px #555;padding:5px;border-radius:3px;border:1px solid red;" id="store_id" name="store_id" required >
								<option value=""> -- Select one -- </option>
									<?php 										
										$sq = mysqli_query($conn, "SELECT * FROM tbretail_stores");
										while ($c = mysqli_fetch_assoc($sq)) {
											$sel = ($c['id'] == $store_id) ? "selected" : "";
											echo "<option value='{$c['id']}' $sel>{$c['store']}</option>";
										}
										?>	
						</select>
</td>
 </tr>     
  <td></td><td align="center"><input  name="btnlogin" type="submit" id="btnsave" value="Login" style=" width:80px; height:25px"/></td>

  </tr>  
  </table>			
  </form>
		   </p>
		</content>
	</article>				
						
	  </div></div>	
		
	<footer id="mainFooter">
		<p style="text-align: center;"><strong>Royalty Solution Systems Tel: 0245777328 / 0550350420	All Rigths  Reserved Copyright &copy; 2026 </strong>  </p>
<a href="#" title="royalty"></a> </p>
	</footer>	

</body>
</html>