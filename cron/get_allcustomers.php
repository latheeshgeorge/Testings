<?php
	include_once("../functions/functions.php");
	include('../includes/session.php');
	require_once("../config.php");
	$sql_query = "SHOW columns FROM customers";
	$ret_query = $db->query($sql_query);
	if($db->num_rows($ret_query))
	{
		$title_str = '';
		while ($row_query = $db->fetch_array($ret_query))
		{
			if($title_str!='')
				$title_str .=',';
			$title_str .= $row_query[0];
		} 
	}
	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=garraways_full_custlist.csv;");
	header("Accept-Ranges:bytes");
	print $title_str."\n";
	$sql_cust = "SELECT * 
					FROM 
						customers 
					WHERE 
						sites_site_id = $ecom_siteid";
	$ret_cust = $db->query($sql_cust);
	if($db->num_rows($ret_cust))
	{
		while ($row_cust = $db->fetch_assoc($ret_cust))
		{
			$row_data = '';
			foreach ($row_cust as $k=>$v)
			{
				if($row_data!='')
					$row_data .= ',';
				$data = '"'.$v.'"';	
				$row_data .= $data;
			}
			print $row_data."\n";
			$result_arr[] = 1;
		}	
	}

	exit;
?>
