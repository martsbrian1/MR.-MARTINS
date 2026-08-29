<?php

if(isset($_POST['transkey'])){
	
	print $transkey = md5(date("Y-m-d H:I:S")) . time();
	
	}
