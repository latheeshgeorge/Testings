<?php
/*
	#################################################################
	# Script Name 	: general_settings_gift_wrap.php
	# Description 	: Action Page for changing the details gift warp settings
	# Coded by 		: ANU
	# Created on	: 25-Jun-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################
	*/
if($_REQUEST['fpurpose'] == '') {
	include("includes/gift_wrap/edit_settings_gift_wrap.php");
}
elseif($_REQUEST['fpurpose'] == 'settings_Gift_Wrap') {

if($_REQUEST['Submit']){
$sql_chk_cnt = "SELECT giftwrap_id FROM general_settings_site_giftwrap WHERE sites_site_id=".$ecom_siteid;
	$res_giftwrap_cnt = $db->query($sql_chk_cnt);
	$num_cnt   =  $db->num_rows($res_giftwrap_cnt);
	$row 	= $db->fetch_array($res_giftwrap_cnt);
	if($num_cnt > 0){
			$update_array = array();
			$update_array['giftwrap_per'] = add_slash($_REQUEST['giftwrap_per']);
			$update_array['sites_site_id'] = $ecom_siteid;
			$update_array['giftwrap_minprice'] = add_slash($_REQUEST['giftwrap_minprice']);
			$update_array['giftwrap_active'] = add_slash($_REQUEST['giftwrap_active']);
			$update_array['giftwrap_messageprice'] = add_slash($_REQUEST['giftwrap_messageprice']);

			$db->update_from_array($update_array,'general_settings_site_giftwrap','giftwrap_id',$row['giftwrap_id']);
			
			$alert = '<center><font color="red"><b>Successfully Updated</b></font><br>';
			}else{
			$insert_array = array();
			$insert_array['giftwrap_per'] = add_slash($_REQUEST['giftwrap_per']);
			$insert_array['sites_site_id'] = $ecom_siteid;
			$insert_array['giftwrap_minprice'] = add_slash($_REQUEST['giftwrap_minprice']);
			$insert_array['giftwrap_active'] = add_slash($_REQUEST['giftwrap_active']);
			$insert_array['giftwrap_messageprice'] = add_slash($_REQUEST['giftwrap_messageprice']);
			
			$db->insert_from_array($insert_array, 'general_settings_site_giftwrap');
			$insert_id = $db->insert_id();
			$alert = '<center><font color="red"><b>Successfully Inserted</b></font><br>';
			}
			echo $alert;
			?>
			<br /><a class="smalllink"  href="home.php?request=general_settings">Go Back General Settings listing page Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=general_settings_Gift_Wrap">Go Back to Edit this Gift Wrap settings</a>
			<br /><br />
			
			
			</center>
			<?
}
}
?>