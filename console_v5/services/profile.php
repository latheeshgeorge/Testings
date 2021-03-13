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
if($_REQUEST['fpurpose'] == '') {
	include("includes/admin/view_profile.php");
} else if($_REQUEST['fpurpose'] == 'update') {
	
	if($_REQUEST['Submit'])
	{
		
		$txtnewpwd	= add_slash($_REQUEST['user_pwd']);
		$txtconfirm	= add_slash($_REQUEST['user_pwd_cnf']);
		
		$fieldRequired = array($_REQUEST['user_title'],$_REQUEST['user_fname'],$_REQUEST['user_lname'],$_REQUEST['user_email']);
		$fieldDescription = array('Title','First Name','Last Name','User Email');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		if($alert) {
			$erro_msg = $alert;
		}	
		else
		{
		$user_email	= add_slash($_REQUEST['user_email']);
		$sql = "SELECT user_title FROM sites_users_7584 WHERE user_id<>".$_SESSION['console_id']." 
				AND sites_site_id='".$ecom_siteid."' 
				AND user_email_9568='".$user_email."'";
		$res = $db->query($sql);
		$num = $db->num_rows($res);		
		if($num > 0) {	
			$erro_msg = "Error! Email ID Exists. Another User has same Email ID. ";
		}  else {
		
		
		
		if($txtconfirm && $txtnewpwd) {
			if($txtconfirm == $txtnewpwd) {
					$txtconfirm = trim($txtconfirm);
					$txtconfirm = str_replace(' ','',$txtconfirm);
					$txtconfirm = preg_replace("/[^0-9a-zA-Z_]+/", "", $txtconfirm);
					
					$txtnewpwd = trim($txtnewpwd);
					$txtnewpwd = str_replace(' ','',$txtnewpwd);
					$txtnewpwd = preg_replace("/[^0-9a-zA-Z_]+/", "", $txtnewpwd);
					
					if($txtconfirm == $txtnewpwd) {
						$txtconfirm = base64_encode($txtnewpwd);
						$update_array = array();
						$update_array['user_title'] = add_slash($_REQUEST['user_title']);
						$update_array['user_fname'] = add_slash($_REQUEST['user_fname']);
						$update_array['user_lname'] = add_slash($_REQUEST['user_lname']);
						$update_array['user_address'] = add_slash($_REQUEST['user_address']);
						
						$update_array['user_company'] = add_slash($_REQUEST['user_company']);
						$update_array['user_phone'] = add_slash($_REQUEST['user_phone']);
						$update_array['user_mobile'] = add_slash($_REQUEST['user_mobile']);
						$update_array['user_email_9568'] = add_slash($user_email);
						$update_array['user_pwd_5124'] = $txtconfirm;
						$db->update_from_array($update_array, 'sites_users_7584', 'user_id', $_SESSION['console_id']);
						$erro_msg = 'Successfully Updated';
					} else {
						$erro_msg = 'Invalid characters in the Password field. Allowed character are (0-9,a-z,A-Z)';
					}
				}
			else {
				$erro_msg="Error! Passwords Does not Match.";
			}
		}
		else
		{
			$update_array = array();
			$update_array['user_title'] = add_slash($_REQUEST['user_title']);
			$update_array['user_fname'] = add_slash($_REQUEST['user_fname']);
			$update_array['user_lname'] = add_slash($_REQUEST['user_lname']);
			$update_array['user_address'] = add_slash($_REQUEST['user_address']);
			$update_array['user_company'] = add_slash($_REQUEST['user_company']);
			$update_array['user_phone'] = add_slash($_REQUEST['user_phone']); 
			$update_array['user_mobile'] = add_slash($_REQUEST['user_mobile']);
			$update_array['user_email_9568'] = add_slash($user_email);
			$db->update_from_array($update_array, 'sites_users_7584', 'user_id', $_SESSION['console_id']);
			$erro_msg = 'Successfully Updated';
			// Check whether the site id is 0 for current user
			$sql_user = "SELECT sites_site_id FROM sites_users_7584 WHERE user_id=".$_SESSION['console_id'];
			$ret_user = $db->query($sql_user);
			if ($db->num_rows($ret_user))
			{
				$row_user = $db->fetch_array($ret_user);
				if($row_user['sites_site_id']==0)
					$_SESSION['log_user'] 		= stripslashes($_REQUEST['user_fname'])." ".stripslashes($_REQUEST['user_lname']);//#User name
				else
					$_SESSION['log_user'] 		= stripslashes($_REQUEST['user_title'])."".stripslashes($_REQUEST['user_fname'])." ".stripslashes($_REQUEST['user_lname']);//#User name	
			}
			
		}
	}
  }	
}	
	include("includes/admin/view_profile.php");
}
?>