<?php
if($_REQUEST['fpurpose']=='')
{
	
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/consoleusers/list_user.php");
}
else if($_REQUEST['fpurpose']=='add')
{
	include("includes/consoleusers/add_user.php");
}
else if($_REQUEST['fpurpose']=='edit')
{
	include("includes/consoleusers/edit_user.php");
	
}
else if($_REQUEST['fpurpose']=='insert')
{
	if($_REQUEST['Submit'])
	{
		
		$alert='';
		$fieldRequired = array($_REQUEST['user_fname'],$_REQUEST['user_lname'],$_REQUEST['user_email'],$_REQUEST['user_pwd']);
		$fieldDescription = array('First Name','Last Name','Email','Password');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		
		$sql_check = "SELECT count(*) as cnt FROM sites_users_7584 WHERE user_email_9568 = '".add_slash($_REQUEST['user_email'])."' AND sites_site_id=$ecom_siteid";
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		
		if($row_check['cnt'] > 0 or trim(strtolower(stripslashes($_REQUEST['user_email'])))=='admin@bshop4.co.uk')
			$alert = 'Email Already exists '; 
		
		if(isset($_REQUEST['user_pwd'])) {
		  if((isset($_REQUEST['user_cnfmpwd'])) && ($_REQUEST['user_pwd'] != $_REQUEST['user_cnfmpwd']) ) {
			$alert = 'Password does not Match'; 
			}
		}
			
		if(!$alert) 
		{
			$insert_array = array();
			$insert_array['user_title']			= add_slash($_REQUEST['user_title']);
			$insert_array['user_fname']			= add_slash($_REQUEST['user_fname']);
			$insert_array['user_lname']			= add_slash($_REQUEST['user_lname']);
			$insert_array['user_address']		= add_slash($_REQUEST['user_address']);
			$insert_array['user_company']		= add_slash($_REQUEST['user_company']);
			
			$insert_array['user_phone']			= add_slash($_REQUEST['user_phone']);
			$insert_array['user_mobile']		= add_slash($_REQUEST['user_mobile']);
			$insert_array['user_email_9568']	= add_slash($_REQUEST['user_email']);
			$insert_array['user_type']			= add_slash($_REQUEST['user_type']);
			$insert_array['shop_id']			= add_slash($_REQUEST['shop_id']);
			$insert_array['user_active']		= add_slash($_REQUEST['user_active']);
			$insert_array['default_user']		= add_slash($_REQUEST['default_user']);
			$insert_array['sites_site_id']		= $ecom_siteid;
			if(isset($_REQUEST['user_pwd']) && ($_REQUEST['user_pwd']<>'') ) {
				$insert_array['user_pwd_5124'] = base64_encode($_REQUEST['user_pwd']);
			}
			if($_REQUEST['user_console_turnoverdisplay']==1)
			{
				$insert_array['user_console_turnoverdisplay']		= 1;
			}
			else
			{
				$insert_array['user_console_turnoverdisplay']		= 0;
			}
			$db->insert_from_array($insert_array, 'sites_users_7584');
			$insert_id = $db->insert_id();
			$sql_un = "SELECT user_id,user_fname,user_lname FROM sites_users_7584 WHERE user_id=".$insert_id." AND sites_site_id=".$ecom_siteid." LIMIT 1";

		     $res_un = $db->query($sql_un);
					  $row_un = $db->fetch_array($res_un);
					  $sql = "SELECT user_id,user_fname,user_lname FROM sites_users_7584 WHERE user_id=".$_SESSION['console_id']." AND sites_site_id=".$ecom_siteid." LIMIT 1";
					  $res = $db->query($sql);
		              $row = $db->fetch_array($res);
					  if($db->num_rows($res)) {					  
						 
						$insert_array_action 		= array();
						$insert_array_action['done_by']		= $_SESSION['console_id'];
						$insert_array_action['done_for']    =   $insert_id;
						$insert_array_action['action_made']		= 'ADDED';  
						$insert_array_action['sites_site_id']   = $ecom_siteid;
						$insert_array_action['action_date']     = 'now()';
						$insert_array_action['done_for_fname']   = $row_un['user_fname']." ".$row_un['user_lname'];
						$insert_array_action['done_for_lname']     = $row['user_fname']." ".$row['user_lname'];
						
						$db->insert_from_array($insert_array_action, 'listof_console_useractions');
						}
			# User Permissions
			if(count($_REQUEST['checkbox'])>0 and $_REQUEST['user_type']=='su')
			{
				foreach($_REQUEST['checkbox'] as $v)
				{
					$insert_permission_array 						= array();
					$insert_permission_array['sites_site_id']		= $ecom_siteid;
					$insert_permission_array['sites_users_user_id']	= $insert_id;
					$insert_permission_array['mod_menu_menu_id']	= $v;
					$db->insert_from_array($insert_permission_array, 'site_user_permissions');
				}
				
			}
			clear_all_cache();// Clearing all cache
			$alert .= '<br><span class="redtext"><b>User Added Successfully</b></span><br>';
			echo $alert;
			?>
			<br /><a class="smalllink" href="home.php?request=console_user&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=console_user&fpurpose=edit&user_id=<?=$insert_id?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Edit Page</a><br /><br />
			<a class="smalllink" href="home.php?request=console_user&fpurpose=add&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Add New the Page</a>
			<?
		}	
		else
		{
			$alert = '<strong>Error!!</strong> '.$alert;
			$alert .= '</span>';
			include("includes/consoleusers/add_user.php");
		}
	}
}
else if($_REQUEST['fpurpose'] == 'update') {
	if($_REQUEST['Submit'])
	{
		
                $alert='';
		$fieldRequired = array($_REQUEST['user_fname'],$_REQUEST['user_lname']);
		$fieldDescription = array('First Name','Last Name');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = "SELECT count(*) as cnt FROM sites_users_7584 WHERE user_email_9568 = '".add_slash($_REQUEST['user_email'])."' AND user_id<>".$_REQUEST['user_id']." AND sites_site_id=$ecom_siteid";
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		if($row_check['cnt'] > 0 or trim(strtolower(stripslashes($_REQUEST['user_email'])))=='admin@bshop4.co.uk')
			$alert = 'Email Already exists '; 
			
		if(isset($_REQUEST['user_pwd'])) {
		  if((isset($_REQUEST['user_cnfmpwd'])) && ($_REQUEST['user_pwd'] != $_REQUEST['user_cnfmpwd']) ) {
			$alert = 'Password does not Match'; 
			}
		}
		if(!$alert) {                                        
			$update_array = array();
			$update_array['user_title']		= add_slash($_REQUEST['user_title']);
			$update_array['user_fname']		= add_slash($_REQUEST['user_fname']);
			$update_array['user_lname']		= add_slash($_REQUEST['user_lname']);
			$update_array['user_address']	= add_slash($_REQUEST['user_address']);
			$update_array['user_company']	= add_slash($_REQUEST['user_company']);
			
			$update_array['user_phone']		= add_slash($_REQUEST['user_phone']);
			$update_array['user_mobile']	= add_slash($_REQUEST['user_mobile']);
			$update_array['user_type']		= add_slash($_REQUEST['user_type']);
			$update_array['shop_id']		= add_slash($_REQUEST['shop_id']);
			$update_array['user_active']	= add_slash($_REQUEST['user_active']);
			$update_array['user_email_9568']	= add_slash($_REQUEST['user_email']);
			if(isset($_REQUEST['user_pwd']) && ($_REQUEST['user_pwd']<>'') ) {
					$update_array['user_pwd_5124'] = base64_encode($_REQUEST['user_pwd']);
				}
			if($_REQUEST['user_console_turnoverdisplay']==1)
			{
				$update_array['user_console_turnoverdisplay']		= 1;
			}
			else
			{
				$update_array['user_console_turnoverdisplay']		= 0;
			}
			$db->update_from_array($update_array, 'sites_users_7584', 'user_id', $_REQUEST['user_id']);
			# User Permissions
			#Delete the current record.
			$sql_remove="DELETE FROM site_user_permissions WHERE sites_site_id=".$ecom_siteid." AND sites_users_user_id=".$_REQUEST['user_id'];
			$db->query($sql_remove);
			if(count($_REQUEST['checkbox'])>0 and $_REQUEST['user_type']=='su')
			{
				foreach($_REQUEST['checkbox'] as $v)
				{
					$insert_permission_array 						= array();
					$insert_permission_array['sites_site_id']		= $ecom_siteid;
					$insert_permission_array['sites_users_user_id']	= $_REQUEST['user_id'];
					$insert_permission_array['mod_menu_menu_id']	= $v;
					$db->insert_from_array($insert_permission_array, 'site_user_permissions');
				}
				
			}
			clear_all_cache();// Clearing all cache
			$alert = '<center><font color="red"><b>User Updated Successfully</b></font><br>';
			echo $alert;
			?>
			<br /><a class="smalllink" href="home.php?request=console_user&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=console_user&fpurpose=edit&user_id=<?=$_REQUEST['user_id']?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Edit Page</a><br /><br />
			<a class="smalllink" href="home.php?request=console_user&fpurpose=add&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Add New the Page</a>
		<?	
		}
		else {
			/*$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;*/
			?>
			<br />
			<?php
			include("includes/consoleusers/edit_user.php");
		}
	}
}
elseif($_REQUEST['fpurpose']=='change_status')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$userr_ids_arr 		= explode('~',$_REQUEST['user_ids']);
		$new_status		= $_REQUEST['ch_status'];
		for($i=0;$i<count($userr_ids_arr);$i++)
		{
			$update_array					= array();
			$update_array['user_active']	= $new_status;
			$user_id 					= $userr_ids_arr[$i];	
			$db->update_from_array($update_array,'sites_users_7584',array('user_id'=>$user_id));
			
		}
		
		$alert = 'Status changed successfully.';
		include ('../includes/consoleusers/list_user.php');
		
}
else if($_REQUEST['fpurpose']=='delete')
{
	
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry User not selected';
		}
		else
		{
			$del_arr = explode("~",$_REQUEST['del_ids']);
			$deleted_cnt = 0;
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					  $sql_note = "select count(*) as cnt from order_notes WHERE user_id=$del_arr[$i]"; 
					  $ret_note=$db->query($sql_note);
					  $note_cnt = $db->fetch_array($ret_note);
					  $sql_note = "select count(*) as cnt from quote_admin_notes WHERE user_id=$del_arr[$i]"; 
					  $ret_adnote=$db->query($sql_note);
					  $notead_cnt = $db->fetch_array($ret_adnote);
					  if($note_cnt['cnt']==0 && $notead_cnt['cnt']==0 )
					  {  
						++$deleted_cnt;
						 $sql_un = "SELECT user_id,user_fname,user_lname FROM sites_users_7584 WHERE user_id=".$del_arr[$i]." AND sites_site_id=".$ecom_siteid." LIMIT 1";
					  $res_un = $db->query($sql_un);
					  $row_un = $db->fetch_array($res_un);
					  $sql = "SELECT user_id,user_id,user_fname,user_lname FROM sites_users_7584 WHERE user_id=".$_SESSION['console_id']." AND sites_site_id=".$ecom_siteid." LIMIT 1";
					  $res = $db->query($sql);
		              $row = $db->fetch_array($res);
					  if($db->num_rows($res)) {
						$insert_array_action 		= array();
						$insert_array_action['done_by']		= $_SESSION['console_id'];
						$insert_array_action['done_for']    =   $del_arr[$i];
						$insert_array_action['action_made']		= 'DELETED';  
						$insert_array_action['sites_site_id']   = $ecom_siteid;
						$insert_array_action['action_date']     = 'now()';
						$insert_array_action['done_for_fname']   = $row_un['user_fname']." ".$row_un['user_lname'];
						$insert_array_action['done_for_lname']     = $row['user_fname']." ".$row['user_lname'];;
						
						$db->insert_from_array($insert_array_action, 'listof_console_useractions');
						}
					  $sql_del = "DELETE FROM sites_users_7584 WHERE user_id=".$del_arr[$i];
					  $db->query($sql_del);
					
					  }	
					  else 
					  { 
					   if($alert) 
					   $alert .="<br />";
					   $alert .= "<span class=\"\">User with ID -".$del_arr[$i]." Cannot Be Deleted  </span>";
					  }
			      }
				} 
				 if($deleted_cnt){
					  $alert .="<br />";
					    $alert .= "<span class=\"\"> $deleted_cnt Users Deleted Sucessfully </span>";
				} 
		}
		include ('../includes/consoleusers/list_user.php');
	

}
?>