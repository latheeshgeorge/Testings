<?php
/*
	#################################################################
	# Script Name 	: static_checkoutfiels.php
	# Description 	: Action Page for updating the table for static checkout fields
	# Coded by 		: LSH
	# Created on	: 08-Apr-2008
	# Modified by	: 
	# Modified On	: 
	#################################################################
	*/
if($_REQUEST['fpurpose'] == '') {
	include ('includes/static_checkoutfields/list_static_checkfields.php');
}
elseif($_REQUEST['fpurpose']=='save_order')
{
/* if(substr($k,0,6) == 'txtcat') //This section will be executed only in case of categories
				{*/
 foreach($_REQUEST as $k=>$v)
 {
		 $curr_id = array();
		 if((substr($k,0,12) == 'field_order~')){
		 $curr_id = explode('~',$k);
		 $update_array	 = array();
		 $update_array['field_order'] =  $v;
		 $update_array['field_error_msg'] =  $_REQUEST['field_error_msg~'.$curr_id[1]];
		 if($_REQUEST['checkbox_req'])
		 {
				$update_array['field_req'] = $_REQUEST['required~'.$curr_id[1]];
		 }
		 if($curr_id[1]){
		 	$db->update_from_array($update_array,'general_settings_site_checkoutfields',array('field_det_id'=>$curr_id[1],'sites_site_id'=>$ecom_siteid));
		 }
		 }
 }
 $alert ="Details Saved Successfully";
include ('includes/static_checkoutfields/list_static_checkfields.php');
}
?>