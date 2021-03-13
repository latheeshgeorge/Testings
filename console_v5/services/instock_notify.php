<?php
/*
	#################################################################
	# Script Name 	: view_profile.php
	# Description 	: Action Page for changing the details of the logged in users
	# Coded by 		: ANU
	# Created on	: 13-Jun-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################
	*/	

if($_REQUEST['fpurpose'] =='') {
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/instock_notify/instock_notify.php");
} 
 
elseif($_REQUEST['fpurpose'] == 'delete_notification') {
	// code for deleting a currency  from the site
	$ajax_return_function = 'ajax_return_contents';
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
	if ($_REQUEST['del_ids'] == '')
	{
		$alert = 'Sorry Notifications not selected';
	}
	else
	{
		$del_arr = explode("~",$_REQUEST['del_ids']);
		foreach($del_arr as $key => $val){
		
			$sql_delCurrency = "DELETE FROM product_stock_update_notification 
									WHERE notify_id = ".$val." AND sites_site_id = ".$ecom_siteid;
			$db->query($sql_delCurrency);
			
			$sql_delnot = "DELETE FROM product_stock_update_notification_variables
									WHERE product_stock_update_notification_notify_id=".$val."";
			$db->query($sql_delnot);		
			
			$sql_delnot = "DELETE FROM product_stock_update_notification_messages 
									WHERE product_stock_update_notification_notify_id=".$val."";
			$db->query($sql_delnot);						
		}	
			$alert = "Notification request removed successfully";
			
		include("../includes/instock_notify/instock_notify.php");
	
	}
}
?>