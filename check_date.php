<?php
$ctime = strtotime('now');
	//$ctime = strtotime('+ 10 minutes'); // need to comment this line when making live
	date_default_timezone_set("UTC");
	$str = date('Y-m-d H:i:s P',$ctime);
	date_default_timezone_set("Europe/London");  
	echo $str;

?>
