<?php
if($_REQUEST['fpurpose']=='')
{
	
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/state/list_state.php");
}
elseif($_REQUEST['fpurpose']=='change_hide')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$state_ids_arr 		= explode('~',$_REQUEST['state_ids']);
		$new_status		= $_REQUEST['ch_status'];
		for($i=0;$i<count($state_ids_arr);$i++)
		{
			$update_array					= array();
			$update_array['state_hide']	= $new_status;
			$state_id 						= $state_ids_arr[$i];	
			$db->update_from_array($update_array,'general_settings_site_state',array('state_id'=>$state_id));
			
		}
		
		
		$alert = 'Hidden Status changed successfully.';
		include ('../includes/state/list_state.php');
		
}
else if($_REQUEST['fpurpose']=='delete')
{
	
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry State not selected';
		}
		else
		{
			$del_count = 0;
			$del_arr = explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					   $sql_del = "DELETE FROM general_settings_site_state WHERE state_id=".$del_arr[$i];
						$db->query($sql_del);
						$del_count ++;				
				}	
			}
			if($del_count>0)
			{
			 if($alert) $alert .="<br />";
						$alert .= $del_count." State(s) Deleted";
			}
		}	
		
		include ('../includes/state/list_state.php');
	

}
else if($_REQUEST['fpurpose']=='add')
{
	
	include("includes/state/add_state.php");
}
else if($_REQUEST['fpurpose']=='edit')
{
	include("includes/state/edit_state.php");
	
}
else if($_REQUEST['fpurpose']=='insert')
{
	if($_REQUEST['Submit'])
	{
		
		$alert='';
		$fieldRequired = array($_REQUEST['general_settings_site_country_country_id'],$_REQUEST['state_name']);
		$fieldDescription = array('Country Name','State Name');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		
		$sql_check = "SELECT count(*) as cnt FROM general_settings_site_state WHERE state_name = '".trim(add_slash($_REQUEST['state_name']))."' AND sites_site_id=$ecom_siteid AND general_settings_site_country_country_id=".$_REQUEST['general_settings_site_country_country_id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		
		if($row_check['cnt'] > 0)
			$alert = 'State Name Already exists '; 
		if(!$alert) 
		{
			$insert_array = array();
			$insert_array['general_settings_site_country_country_id']=add_slash($_REQUEST['general_settings_site_country_country_id']);
			$insert_array['state_name']=add_slash($_REQUEST['state_name']);
			$insert_array['state_hide']=$_REQUEST['state_hide'];
			$insert_array['sites_site_id']=$ecom_siteid;
			$db->insert_from_array($insert_array, 'general_settings_site_state');
			$insert_id = $db->insert_id();
			$alert .= '<br><span class="redtext"><b>State added successfully</b></span><br>';
			echo $alert;
			?>
			<br /><a class="smalllink" href="home.php?request=general_settings_state&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=general_settings_state&fpurpose=edit&state_id=<?=$insert_id?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Edit Page</a><br /><br />
			<a class="smalllink" href="home.php?request=general_settings_state&fpurpose=add&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Add New Page</a>
			<?
		}	
		else
		{
			$alert = '<strong>Error!!</strong> '.$alert;
			$alert .= '';
			include("includes/state/add_state.php");
		}
	}
}
else if($_REQUEST['fpurpose'] == 'update') {
	if($_REQUEST['Submit'])
	{
		
		$alert='';
		$fieldRequired = array($_REQUEST['general_settings_site_country_country_id'],$_REQUEST['state_name']);
		$fieldDescription = array('Country Name','State Name');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = "SELECT count(*) as cnt FROM general_settings_site_state WHERE state_name = '".add_slash($_REQUEST['state_name'])."' AND sites_site_id=$ecom_siteid AND general_settings_site_country_country_id=".$_REQUEST['general_settings_site_country_country_id']." AND state_id<>".$_REQUEST['state_id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		
		if($row_check['cnt'] > 0)
			$alert = 'State Name Already exists '; 
		if(!$alert) {
			$update_array = array();
			$update_array['general_settings_site_country_country_id']=$_REQUEST['general_settings_site_country_country_id'];
			$update_array['state_name']=add_slash($_REQUEST['state_name']);
			$update_array['state_hide']=$_REQUEST['state_hide'];
			$db->update_from_array($update_array, 'general_settings_site_state', 'state_id', $_REQUEST['state_id']);
			$alert = '<center><font color="red"><b>State Updated successfully</b></font><br>';
			echo $alert;
			?>
			<br /><a class="smalllink" href="home.php?request=general_settings_state&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=general_settings_state&fpurpose=edit&state_id=<?=$_REQUEST['state_id']?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Edit  Page</a><br /><br />
			<a class="smalllink" href="home.php?request=general_settings_state&fpurpose=add&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Add New Page</a>
		<?	
		}
		else {
			$alert = '<center><font >Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/state/edit_state.php");
		}
	}
}
?>