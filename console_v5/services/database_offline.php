<?php
if($ecom_siteid==101 || $ecom_siteid==108 )
	{
		echo "<span style='font-size:12px;font-weight:bold;color:#FF0000;'><br><br><br>Sorry!! you are not authorized to view this page</span>";
		exit;
	}
	if($_REQUEST['fpurpose']=='')
	{
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include ("includes/database_offline/database_offline.php");
	}
?>
