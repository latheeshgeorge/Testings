<?php
	include_once("functions/functions.php");
	//include('session.php');
	require_once("sites.php");
	require_once("config.php");
    $sec_key 		= trim($_REQUEST['key']);
	$ord_id 		= trim($_REQUEST['order_id']);
	$error_msg  	= '';
	$valid_data		= true; 
    if($sec_key=='')
	{
		$error_msg = '-- Security key is missing --';
		$valid_data= false;
	}
	else
	{
		// Check whether security key is valid
		$sql_check = "SELECT sites_site_id 
						FROM 
							general_settings_sites_common 
						WHERE 
							sites_site_id = $ecom_siteid 
							AND steamdesk_security_key ='". mysql_real_escape_string($sec_key)."' 
						LIMIT 
							1";
		$ret_check = $db->query($sql_check);
		if($db->num_rowS($ret_check)==0)
		{
			$error_msg .= '-- Invalid Security Key --';
			$valid_data = false;
		}
	}
	if(!$ord_id)
	{
		$error_msg .= '-- Order id is missing --';
		$valid_data= false;
	}
	elseif(!is_numeric($ord_id))
	{
		$error_msg .= '-- Invalid Order Id --';
		$valid_data= false;
	}
	else
	{
		// Check whether order id is valid
		$sql_check = "SELECT order_id 
						FROM 
							orders 
						WHERE 
							order_id = '".mysql_real_escape_string($ord_id)."' 
							AND sites_site_id = $ecom_siteid 
						LIMIT 
							1";
		$ret_check = $db->query($sql_check);
		if($db->num_rows($ret_check)==0)
		{
			$error_msg .= '-- Invalid Order Id --';
			$valid_data = false;
		}
	}
	
	if($valid_data)
	{
		$sql_update = "UPDATE orders 
						SET 
							order_steamdesk_exported = 1 
						WHERE 
							order_id = '".mysql_real_escape_string($ord_id)."' 
							AND sites_site_id = $ecom_siteid 
							AND order_steamdesk_exported = 0 
						LIMIT 
							1";
		$db->query($sql_update);
		
	}
?>
