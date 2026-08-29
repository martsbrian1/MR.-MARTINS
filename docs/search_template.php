<form action="" method="post" name="form1">  
  <table class="sample" border="0" cellspacing="0" cellpadding="1" width="100%" align="center" bordercolor="#F1F1F1">
<tr> 
<td> 

<div align="right">

<label style="font-weight:bold;">               
Date Range From:  &nbsp; <input name="start_dt" type="date" value="<?php echo @$start_dt; ?>" style="margin:5px auto;width:110px !important;box-shadow:2px 2px 2px 2px #555;padding:2px;border-radius:3px;border:1px solid red;" />
  &nbsp;&nbsp; To: &nbsp; <input name="end_dt" type="date" value="<?php echo @$end_dt; ?>" style="margin:5px auto;width:140px !important;box-shadow:2px 2px 2px 2px #555;padding:2px;border-radius:3px;border:1px solid red;" />
 </label>&nbsp;&nbsp;
  
 
&nbsp;&nbsp; <input class="submitbtn" type="submit" name="submit" value="View" style="margin-bottom:1px; margin-left:10px; width:90px; !important;box-shadow:2px 2px 2px 2px #555;padding:2px;border-radius:3px;border:1px solid red;">
&nbsp;&nbsp; <button style="width:60px; margin-bottom:15px !important;box-shadow:2px 2px 2px 2px #555;padding:2px;border-radius:3px;border:1px solid red;" class="submitbtn"  onClick="printContent('t1')">Print </button> 

</div>
</td>
</tr> 
</table> 