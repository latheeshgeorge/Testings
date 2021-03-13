<?php
	include_once("../functions/functions.php");
	include('../includes/session.php');
	require_once("../config.php");
	
	$sql = "SELECT distinct order_custemail,order_custtitle,order_custfname,order_custmname,order_custsurname
				FROM 
					orders 
				WHERE 
					sites_site_id = $ecom_siteid 
					AND order_status <> 'NOT_AUTH'";
	$ret = $db->query($sql);
	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=discount_ordered_customers.csv;");
	header("Accept-Ranges:bytes");
	print "#,Name,Email Id"."\n";

	if($db->num_rows($ret))
	{
		$row1 = 1;
		while ($row = $db->fetch_array($ret))
		{
			print "$row1,".$row['order_custtitle'].$row['order_custfname'].' '.$row['order_custmname'].' '.$row['order_custsurname'].",".$row['order_custemail']."\n";
			$row1++;
		}
	}
	exit;
?>
