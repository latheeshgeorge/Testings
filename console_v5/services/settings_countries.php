<?php
if($_REQUEST['fpurpose']=='')
{
	
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/country/list_country.php");
}
elseif($_REQUEST['fpurpose']=='change_hide')
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	$country_ids_arr 		= explode('~',$_REQUEST['country_ids']);
	$new_status		= $_REQUEST['ch_status'];
	for($i=0;$i<count($country_ids_arr);$i++)
	{
		$update_array					= array();
		$update_array['country_hide']	= $new_status;
		$country_id 						= $country_ids_arr[$i];	
		$db->update_from_array($update_array,'general_settings_site_country',array('country_id'=>$country_id));
		
	}
	$alert = 'Active Status changed successfully.';
	include ('../includes/country/list_country.php');
}
elseif($_REQUEST['fpurpose']=='save_default_country')
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	$defid = $_REQUEST['def_id'];
	$sql_update = "UPDATE general_settings_sites_common 
					SET 
						default_country_id = '".trim($_REQUEST['def_id'])."' 
					WHERE 
						sites_site_id = $ecom_siteid 
					LIMIT 
						1";
	$db->query($sql_update);
	$alert = 'Default Country set successfully.';
	// Creating the general settings cache files to be included in client area to save time to access the settings each time from db
	create_GeneralSettings_CacheFile();
	include ('../includes/country/list_country.php');
}
elseif($_REQUEST['fpurpose']=='delete')
{
	
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Country not selected';
		}
		else
		{
			$del_count = 0;
			$del_arr = explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					# Check for states of this country
					$sql_state_count = "SELECT count(*) as cnt FROM general_settings_site_state WHERE general_settings_site_country_country_id=".$del_arr[$i];
					$res_state_count = $db->query($sql_state_count);
					list($numcount_state) = $db->fetch_array($res_state_count);
					if(!$numcount_state)
					{
						$sql_del = "DELETE FROM general_settings_site_country WHERE country_id=".$del_arr[$i];
						$db->query($sql_del);
						$sql_del = "DELETE FROM general_settings_site_country_location_map WHERE 
								general_settings_site_country_country_id=".$del_arr[$i];
						$db->query($sql_del);
						$del_count ++;		
					}
					else
					{
						if($alert) $alert .="<br />";
						$alert .= "<span ><strong>Error!!</strong>State exists for current country</span>";
					}	
				}	
			}
			if($del_count>0)
			{
				if($alert) $alert .="<br />";
					$alert .= $del_count." Country(s) Deleted";
			}				
		}
		include ('../includes/country/list_country.php');
	

}
else if($_REQUEST['fpurpose']=='add')
{
	
	include("includes/country/add_country.php");
}
else if($_REQUEST['fpurpose']=='edit')
{
	include("includes/country/edit_country.php");
	
}
else if($_REQUEST['fpurpose']=='insert')
{
	if($_REQUEST['Submit'])
	{
		
		$alert='';
		$fieldRequired = array($_REQUEST['country_name'],$_REQUEST['country_code'],$_REQUEST['numeric_code']);
		$fieldDescription = array('Country Name','Country Code','Numeric Code');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		
		$sql_check = "SELECT count(*) as cnt FROM general_settings_site_country WHERE country_name = '".add_slash($_REQUEST['country_name'])."' AND sites_site_id=$ecom_siteid";
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		
		if($row_check['cnt'] > 0)
			$alert = 'Country Name Already exists '; 
		if(!$alert) 
		{
			$insert_array = array();
			$insert_array['country_name']=add_slash($_REQUEST['country_name']);
			$insert_array['country_code']=add_slash($_REQUEST['country_code']);
			$insert_array['country_numeric_code']=add_slash($_REQUEST['numeric_code']);
			$insert_array['country_hide']=$_REQUEST['country_hide'];
			$insert_array['sites_site_id']=$ecom_siteid;
			$db->insert_from_array($insert_array, 'general_settings_site_country');
			$insert_id = $db->insert_id();
			$alert .= '<br><span class="redtext"><b>Country added successfully</b></span><br>';
			echo $alert;
			?>
			<br /><a class="smalllink" href="home.php?request=general_settings_country&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=general_settings_country&fpurpose=edit&country_id=<?=$insert_id?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Edit Page</a><br /><br />
			<a class="smalllink" href="home.php?request=general_settings_country&fpurpose=add&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Add New Page</a>
			<?
		}	
		else
		{
			$alert = '<strong>Error!!</strong> '.$alert;
			$alert .= '';
			include("includes/country/add_country.php");
		}
	}
}
else if($_REQUEST['fpurpose'] == 'update') {
	if($_REQUEST['Submit'])
	{
		
		$alert='';
		$fieldRequired = array($_REQUEST['country_name'],$_REQUEST['country_code'],$_REQUEST['numeric_code']);
		$fieldDescription = array('Country Name','Country Code','Numeric Code');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = "SELECT count(*) as cnt FROM general_settings_site_country WHERE country_name = '".add_slash($_REQUEST['country_name'])."' AND sites_site_id=$ecom_siteid AND country_id<>".$_REQUEST['country_id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		
		if($row_check['cnt'] > 0)
			$alert = 'Country Name Already exists '; 
		if(!$alert) {
			$update_array = array();
			$update_array['country_name']			= add_slash($_REQUEST['country_name']);
			$update_array['country_code']			= add_slash($_REQUEST['country_code']);
			$update_array['country_numeric_code']	= add_slash($_REQUEST['numeric_code']);
			$update_array['country_hide']			= add_slash($_REQUEST['country_hide']);
			$db->update_from_array($update_array, 'general_settings_site_country', 'country_id', $_REQUEST['country_id']);
			$alert .= '<br><span class="redtext"><b>Country Updated successfully</b></span><br>';
			echo $alert;
			?>
			<br /><a class="smalllink" href="home.php?request=general_settings_country&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=general_settings_country&fpurpose=edit&country_id=<?=$_REQUEST['country_id']?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Edit  Page</a><br /><br />
			<a class="smalllink" href="home.php?request=general_settings_country&fpurpose=add&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Add New Page</a>
		<?	
		}
		else {
			$alert = '<center>Error! '.$alert;
			$alert .= '</center>';
		?>
			<br />
			<?php
			include("includes/country/edit_country.php");
		}
	}
}
?>