<?php
if($_REQUEST['fpurpose'] == '') {
	include("includes/console_levels/list_consolelevels.php");
}
else if($_REQUEST['fpurpose'] == 'add')
{
	include("includes/console_levels/add_consolelevels.php");
}
else if($_REQUEST['fpurpose'] == 'edit')
{
	if(is_numeric($_REQUEST['level_id']))
	{
		include("includes/console_levels/edit_consolelevels.php");
	}
	else
	{
		$alert = '<center><font color="red">Invalid console Id</font></center><br>';
		echo $alert;
		include("includes/console_levels/list_consolelevels.php");
	}	
}
else if($_REQUEST['fpurpose'] == 'insert')
{
	if($_REQUEST['Save_consolelevels'])
	{
		$alert = '';
		$fieldRequired = array($_REQUEST['level_name']);
		$fieldDescription = array('Level Name');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = $db->value_exists(array('level_name' => $_REQUEST['level_name']), 'console_levels');
		if($sql_check > 0) {
			$alert = 'Console Level already exists';
		}
		if(!$alert) {
			$insert_array = array();
			$insert_array['level_name']					= add_slash($_REQUEST['level_name']);
			$insert_array['level_description']			= add_slash($_REQUEST['level_description']);
			$insert_array['level_price']				= (is_numeric(trim($_REQUEST['level_price'])))?trim($_REQUEST['level_price']):0;
			$insert_array['level_duration']				= add_slash($_REQUEST['level_duration']);
			$db->insert_from_array($insert_array, 'console_levels');
			$insert_id = $db->insert_id();
			// Calling the function to save the selected features for this console levels
			Save_consoleFeatures($insert_id);
						
			$alert = '<center><font color="red"><b>Successfully Inserted</b></font><br>';
			echo $alert;
			?>
			<br /><a href="home.php?request=levels&levelname=<?=$_REQUEST['levelname']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Listing page</a><br /><br />
			<a href="home.php?request=levels&fpurpose=edit&level_id=<?=$insert_id?>&levelname=<?=$_REQUEST['levelname']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Edit this Console Level</a>
			<br /><br />
			<a href="home.php?request=levels&fpurpose=add&levelname=<?=$levelname?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>">Add a New Console Level</a>
			
			</center>
			<?php
			
		} else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/console_levels/add_consolelevels.php");
		}
		
	}
} else if($_REQUEST['fpurpose'] == 'update') {
	if($_REQUEST['Save_consolelevels'])
	{
		$alert = '';
		$fieldRequired = array($_REQUEST['level_name']);
		$fieldDescription = array('Theme Name');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = "SELECT count(*) as cnt FROM console_levels WHERE level_name='".$_REQUEST['level_name']."' AND level_id<>".$_REQUEST['level_id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
	
		if($row_check['cnt'] > 0) {
			$alert = 'Console Level already exists';
		}
		if(!$alert) {
			$update_array = array();
			$update_array['level_name']					= add_slash($_REQUEST['level_name']);
			$update_array['level_description']			= add_slash($_REQUEST['level_description']);
			$update_array['level_price']				= (is_numeric(trim($_REQUEST['level_price'])))?trim($_REQUEST['level_price']):0;
			$update_array['level_duration']				= add_slash($_REQUEST['level_duration']);
			$db->update_from_array($update_array, 'console_levels', 'level_id', $_REQUEST['level_id']);
			
			// Remove all the entries from console_levels_details table for current level id
			$sql_del = "DELETE FROM console_levels_details WHERE console_levels_level_id = ".$_REQUEST['level_id'];
			$ret_del = $db->query($sql_del);
			
			// Calling the function to save the selected features for this console levels
			Save_consoleFeatures($_REQUEST['level_id']);
			
			synchronize_console_level_features($_REQUEST['level_id']);
			
			// get the sites which use this console level
			$sql_dom 		= "SELECT site_id,site_domain 
									FROM 
										sites 
									WHERE 
										console_levels_level_id=".$_REQUEST['level_id'];
			$ret_dom 		= $db->query($sql_dom);
			if ($db->num_rows($ret_dom))
			{
				while ($row_dom 		= $db->fetch_array($ret_dom))
				{
					$base_path 	= IMAGE_ROOT_PATH."/".$row_dom['site_domain'];
					create_SitemenModmenu_CacheFile($base_path,$row_dom['site_id']); // site menu and mod menu cache
				}	
			}
			
			
			
			$alert = '<center><font color="red"><b>Successfully Updated</b></font><br>';
			echo $alert;
			?>
			<br /><a href="home.php?request=levels&levelname=<?=$_REQUEST['levelname']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Listing page</a><br /><br />
			<a href="home.php?request=levels&fpurpose=edit&level_id=<?=$_REQUEST['level_id']?>&levelname=<?=$_REQUEST['levelname']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Edit this Console Level</a>
			<br />
			<br />
			<a href="home.php?request=levels&fpurpose=add&levelname=<?=$levelname?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>">Add a New Console Level</a>
			
			</center>
			<?php
			
		} else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/console_levels/edit_consolelevels.php");
		}
		
	}
}
else if($_REQUEST['fpurpose'] == 'delete')
{
	// Check whether this theme is used in any of the sites
	$sql_sites 			= "SELECT count(site_id) as cnt FROM sites WHERE console_levels_level_id=".$_REQUEST['level_id'];
	$ret_sites 			= $db->query($sql_sites);
	list($use_cnt) 		= $db->fetch_array($ret_sites);
	if($use_cnt>0)
	{
		$error_msg = 'Sorry Delete not possible... Console Level is in use by site(s)';
	}
	else
	{
		$sql_del = "DELETE FROM console_levels_details WHERE console_levels_level_id=".$_REQUEST['level_id'];
		$db->query($sql_del);
		$sql_del = "DELETE FROM console_levels WHERE level_id=".$_REQUEST['level_id'];
		$db->query($sql_del);
		$error_msg = 'Console Level Deleted Successfully';
	}
	include("includes/console_levels/list_consolelevels.php");
}
function Save_consoleFeatures($level_id)
{
	global $db;
	if (count($_REQUEST['checkbox']))
	{
		for($i=0;$i<count($_REQUEST['checkbox']);$i++)
		{
			$featid = $_REQUEST['checkbox'][$i];
			$limit	= $_REQUEST['limit_'.$featid];
			// Find the service id for current feature
			$sql_service = "SELECT services_service_id FROM features WHERE feature_id=$featid";
			$ret_service = $db->query($sql_service);
			if ($db->num_rows($ret_service))
			{
				$row_service 	= $db->fetch_array($ret_service);
				$service_id		= $row_service['services_service_id'];
			}
			else
				$service_id		= 0;
			if(!$limit)	$limit = 0;
			if($service_id)
			{
				$insert_array 								= array();
				$insert_array['console_levels_level_id']	= $level_id;
				$insert_array['features_feature_id']		= $featid;
				$insert_array['services_service_id']		= $service_id;
				$insert_array['services_limit']				= $limit;
				$db->insert_from_array($insert_array, 'console_levels_details');
			}	
		}
	}
}
function synchronize_console_level_features($level_id)
{
	global $db;
	
	// ==========================================
	// Handling the case of site_menu table
	// ==========================================
	// Get all the features linked with current console level
	$sql_level = "SELECT a.feature_id,a.feature_title, a.feature_modulename,a.services_service_id,a.feature_insite ,a.feature_inconsole,feature_consoleurl,feature_ordering 
							FROM 
								features a,	console_levels_details b 
							WHERE 
								b.console_levels_level_id=$level_id  
								AND a.feature_id=b.features_feature_id 
							ORDER BY 
								a.feature_ordering";
	$ret_feat = $db->query($sql_level);
	$feat_site_arr  		= $feat_con_arr 			= array();
	$feat_site_det_arr 	= $feat_con_det_arr	= array();
	while($row_feat = $db->fetch_array($ret_feat)) 
	{
		if($row_feat['feature_insite']==1) // exists in site
		{
			$feat_site_arr[] 										= $row_feat['feature_id'];
		}
		if($row_feat['feature_inconsole']==1) // exists in console
		{
			$feat_con_arr[] 										= $row_feat['feature_id'];
		}	
		$feat_site_det_arr[$row_feat['feature_id']] 	= $row_feat;
	}
	// Find the list of sites which uses this console level
	$sql_site = "SELECT site_id 
						FROM 
							sites 
						WHERE 
							console_levels_level_id = $level_id ";
	$ret_site = $db->query($sql_site);
	if ($db->num_rows($ret_site))
	{
		while ($row_site = $db->fetch_array($ret_site))
		{
				//=================================================================================
				//																		SITE_MENU				
				//=================================================================================
				// Check whether all the features currently set for the console level is there with site in site_menu table
				for($i=0;$i<count($feat_site_arr);$i++)
				{
					// ==========================================
					// Checking site_menu table
					// ==========================================
					$sql_check = "SELECT menu_id 
												FROM 
													site_menu 
												WHERE 
													sites_site_id=".$row_site['site_id']." 
													AND features_feature_id=".$feat_site_arr[$i]." 
												LIMIT 
													1";
					$ret_check = $db->query($sql_check);
					if ($db->num_rows($ret_check)==0) // case if does not exists
					{
						$insert_array									= array();
						$insert_array['sites_site_id']				= $row_site['site_id'];
						$insert_array['features_feature_id']	= $feat_site_arr[$i];
						$insert_array['menu_title']				= $feat_site_det_arr[$feat_site_arr[$i]]['feature_title'];
						$db->insert_from_array($insert_array,'site_menu');
					}
				}
			
				if (count($feat_site_arr))
				{
					$feat_str = implode(',',$feat_site_arr);
					// the following query will remove any feature which was there earlier in current console level but no more exists now for current site in site_menu
					$sql_del= "DELETE 
										FROM 
											site_menu 
										WHERE 
											features_feature_id NOT IN ($feat_str) 
											AND sites_site_id = ".$row_site['site_id'];
					$db->query($sql_del);
					
					// the following query will remove any feature which was there earlier in current console level but no more exists now for current site in display_settings
					$sql_del= "DELETE 
										FROM 
											display_settings  
										WHERE 
											features_feature_id NOT IN ($feat_str) 
											AND sites_site_id = ".$row_site['site_id'];
					$db->query($sql_del);
				}	
				
				//=================================================================================
				//																		MOD_MENU				
				//=================================================================================
				// Check whether all the features currently set for the console level is there with site in mod_menu table
				for($i=0;$i<count($feat_con_arr);$i++)
				{
					// ==========================================
					// Checking mod_menu table
					// ==========================================
					$sql_check = "SELECT menu_id 
												FROM 
													mod_menu 
												WHERE 
													sites_site_id=".$row_site['site_id']." 
													AND features_feature_id=".$feat_con_arr[$i]." 
												LIMIT 
													1";
					$ret_check = $db->query($sql_check);
					if ($db->num_rows($ret_check)==0) // case if does not exists
					{
						$insert_array									= array();
						$insert_array['sites_site_id']				= $row_site['site_id'];
						$insert_array['services_service_id']		= $feat_site_det_arr[$feat_con_arr[$i]]['services_service_id'];
						$insert_array['features_feature_id']	= $feat_con_arr[$i];
						$insert_array['menu_title']				= $feat_site_det_arr[$feat_con_arr[$i]]['feature_title'];
						$insert_array['menu_url']					= $feat_site_det_arr[$feat_con_arr[$i]]['feature_consoleurl'];
						$insert_array['menu_order']				= $feat_site_det_arr[$feat_con_arr[$i]]['feature_ordering'];
						$db->insert_from_array($insert_array,'mod_menu');
					}
				}
				// the following query will remove any feature which was there earlier in current console level but no more exists now for current site in mod_menui
				if (count($feat_con_arr))
				{
					$feat_str = implode(',',$feat_con_arr);
					$sql_del= "DELETE 
										FROM 
											mod_menu 
										WHERE 
											features_feature_id NOT IN ($feat_str) 
											AND sites_site_id = ".$row_site['site_id'];
					$db->query($sql_del);
				}
		}
	}
}
?>
