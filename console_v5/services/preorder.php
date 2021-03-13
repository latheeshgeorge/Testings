<?php
/*
	#################################################################
	# Script Name 	: view_profile.php
	# Description 	: Action Page for changing the details of the logged in users
	# Coded by 		: ANU
	# Created on		: 13-Jun-2007
	# Modified by	: Sny
	# Modified On	: 08-Jul-2008
	#################################################################
	
	*/
if($_REQUEST['fpurpose'] == '') {
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	//include ('includes/bestseller/ajax/bestseller_ajax_functions.php');
	include("includes/preorder/list_preorder.php");
} 

elseif($_REQUEST['fpurpose']=='save_order') // Products order for bestseller list
{
	
	//include_once("../functions/functions.php");
	//include_once('../session.php');
	//include_once("../config.php");	
	$IdArr=explode('~',$_REQUEST['ch_ids']);
	$OrderArr=explode('~',$_REQUEST['ch_order']);
	for($i=0;$i<count($IdArr);$i++)
	{
		$update_array						= array();
		$update_array['product_preorder_custom_order']	= $OrderArr[$i];

		$db->update_from_array($update_array,'products',array('product_id'=>$IdArr[$i]));
		
	}
	
	clear_all_cache();// Clearing all cache
	$alert = 'Order saved successfully.';
	include("includes/preorder/list_preorder.php");
}


	
	
?>