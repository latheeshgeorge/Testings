<?php
if($_REQUEST['fpurpose'] == '') {
	include("includes/features/list_features.php");
} else if($_REQUEST['fpurpose'] == 'add') {
	include("includes/features/add_feature.php");
} else if($_REQUEST['fpurpose'] == 'edit') {
 	if(is_numeric($_REQUEST['feature_id']))
	{
		include("includes/features/edit_feature.php");
	}
	else
	{
		$alert = '<center><font color="red">Invalid feature Id</font></center><br>';
		echo $alert;
		include("includes/features/list_features.php");
	}	
} else if($_REQUEST['fpurpose'] == 'insert') {
	if($_REQUEST['Submit'])
	{
		$alert = '';
		$fieldRequired = array($_REQUEST['feature_name'],$_REQUEST['feature_title'],$_REQUEST['feature_option']);
		$fieldDescription = array('Feature Name','Feature title','Feature Option value');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		      print_r($_REQUEST);
		if(!$alert) {
			$showincomp 		= (!$_REQUEST['feature_showincomponentposition'])?0:1;
			$showincompmobile 		= (!$_REQUEST['feature_showinmobilecomponentposition'])?0:1;
			
			$allowedinmiddle 	= (!$_REQUEST['feature_allowedinmiddlesection'])?0:1;
			$insert_array = array();
			$insert_array['feature_name'] = add_slash($_REQUEST['feature_name']);
			$insert_array['feature_title'] = add_slash($_REQUEST['feature_title']);
			$insert_array['feature_description'] = add_slash($_REQUEST['feature_description']);
			$insert_array['services_service_id'] = add_slash($_REQUEST['services_service_id']);
			$insert_array['parent_id'] = add_slash($_REQUEST['parent_id']);
			$insert_array['feature_consoleurl'] = add_slash($_REQUEST['feature_consoleurl']);
			$insert_array['feature_modulename'] = add_slash($_REQUEST['feature_modulename']);
			$insert_array['feature_hide'] = add_slash($_REQUEST['feature_hide']);
			$insert_array['feature_allowedit'] = add_slash($_REQUEST['feature_allowedit']);
			$insert_array['feature_insite'] = add_slash($_REQUEST['feature_insite']);
			$insert_array['feature_inconsole'] = add_slash($_REQUEST['feature_inconsole']);
			$insert_array['feature_ordering'] = add_slash($_REQUEST['feature_ordering']);
			$insert_array['feature_price'] = add_slash($_REQUEST['feature_price']);
			$insert_array['feature_duration'] = add_slash($_REQUEST['feature_duration']);
			$insert_array['feature_licenselimit'] = add_slash($_REQUEST['feature_licenselimit']);
			$insert_array['feature_option'] = add_slash($_REQUEST['feature_option']);
			$insert_array['feature_displaytouser'] = add_slash($_REQUEST['feature_displaytouser']);
			$insert_array['feature_showincomponentposition'] = $showincomp;
			$insert_array['feature_showinmobilecomponentposition'] = $showincompmobile;
			$insert_array['feature_allowedinmiddlesection'] = $allowedinmiddle;
			$insert_array['feature_icon'] = add_slash($_REQUEST['feature_icon']);
			$insert_array['feature_disable_icon'] = add_slash($_REQUEST['feature_disable_icon']);
                        $insert_array['feature_displayinallowedposition'] = ($_REQUEST['feature_displayinallowedposition'])?1:0;
                         print_r($insert_array);
			
			$db->insert_from_array($insert_array, 'features');
			$insert_id = $db->insert_id();
			$alert = '<center><font color="red"><b>Successfully Inserted</b></font><br>';
			echo $alert;
			?>
			<br /><a href="home.php?request=features&featuretitle=<?=$_REQUEST['featuretitle']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&servicetitle=<?=$_REQUEST['servicetitle']?>">Go Back to the Listing page</a><br /><br />
			<a href="home.php?request=features&fpurpose=edit&feature_id=<?=$insert_id?>&featuretitle=<?=$_REQUEST['featuretitle']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&servicetitle=<?=$_REQUEST['servicetitle']?>">Go Back to Edit this feature</a><br /><br />
			<a href="home.php?request=features&fpurpose=add&featuretitle=<?=$_REQUEST['featuretitle']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&servicetitle=<?=$_REQUEST['servicetitle']?>">Click here to Add a new feature</a></center>
			<?php
			
		} else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/features/add_feature.php");
		}
		
	}
} else if($_REQUEST['fpurpose'] == 'update') {
	if($_REQUEST['Submit'])
	{
		$alert = '';
		$fieldRequired = array($_REQUEST['feature_name'],$_REQUEST['feature_title'],$_REQUEST['feature_option']);
		$fieldDescription = array('Feature Name','Feature title','Feature Option value');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		
		if(!$alert) {
			$showincomp 		= (!$_REQUEST['feature_showincomponentposition'])?0:1;
			$allowedinmiddle 	= (!$_REQUEST['feature_allowedinmiddlesection'])?0:1;
			$showincompmobile 		= (!$_REQUEST['feature_showinmobilecomponentposition'])?0:1;
			$update_array = array();
			$update_array['feature_name'] = add_slash($_REQUEST['feature_name']);
			$update_array['feature_title'] = add_slash($_REQUEST['feature_title']);
			$update_array['feature_description'] = add_slash($_REQUEST['feature_description']);
			$update_array['services_service_id'] = add_slash($_REQUEST['services_service_id']);
			$update_array['parent_id'] = add_slash($_REQUEST['parent_id']);
			$update_array['feature_consoleurl'] = add_slash($_REQUEST['feature_consoleurl']);
			$update_array['feature_modulename'] = add_slash($_REQUEST['feature_modulename']);
			$update_array['feature_hide'] = add_slash($_REQUEST['feature_hide']);
			$update_array['feature_allowedit'] = add_slash($_REQUEST['feature_allowedit']);
			$update_array['feature_insite'] = add_slash($_REQUEST['feature_insite']);
			$update_array['feature_inconsole'] = add_slash($_REQUEST['feature_inconsole']);
			$update_array['feature_ordering'] = add_slash($_REQUEST['feature_ordering']);
			$update_array['feature_price'] = add_slash($_REQUEST['feature_price']);
			$update_array['feature_duration'] = add_slash($_REQUEST['feature_duration']);
			$update_array['feature_licenselimit'] = add_slash($_REQUEST['feature_licenselimit']);
			$update_array['feature_option'] = add_slash($_REQUEST['feature_option']);
			$update_array['feature_displaytouser'] = add_slash($_REQUEST['feature_displaytouser']);
			$update_array['feature_showincomponentposition'] = $showincomp;
			$update_array['feature_showinmobilecomponentposition'] = $showincompmobile;
			$update_array['feature_allowedinmiddlesection'] = $allowedinmiddle;
			//$update_array['feature_icon'] = add_slash($_REQUEST['feature_icon']);
			$update_array['feature_new_icon'] = add_slash($_REQUEST['feature_icon']);
			$update_array['feature_disable_icon'] = add_slash($_REQUEST['feature_disable_icon']);
                        $update_array['feature_displayinallowedposition'] = ($_REQUEST['feature_displayinallowedposition'])?1:0;
			
			$db->update_from_array($update_array, 'features', 'feature_id', $_REQUEST['feature_id']);
			$alert = '<center><font color="red"><b>Successfully Updated</b></font><br>';
			
			// get the domain name
			$sql_dom 		= "SELECT site_id,site_domain 
									FROM 
										sites ";
			$ret_dom 		= $db->query($sql_dom);
			if ($db->num_rows($ret_dom))
			{
				while ($row_dom 		= $db->fetch_array($ret_dom))
				{
					$base_path 	= IMAGE_ROOT_PATH."/".$row_dom['site_domain'];
					create_SitemenModmenu_CacheFile($base_path,$row_dom['site_id']); // site menu and mod menu cache
				}	
			}
			echo $alert;
			?>
			<br /><a href="home.php?request=features&featuretitle=<?=$_REQUEST['featuretitle']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&servicetitle=<?=$_REQUEST['servicetitle']?>">Go Back to the Listing page</a><br /><br />
			<a href="home.php?request=features&fpurpose=edit&feature_id=<?=$_REQUEST['feature_id']?>&featuretitle=<?=$_REQUEST['featuretitle']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&servicetitle=<?=$_REQUEST['servicetitle']?>">Go Back to Edit this feature</a><br /><br />
			<a href="home.php?request=features&fpurpose=add&featuretitle=<?=$_REQUEST['featuretitle']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&servicetitle=<?=$_REQUEST['servicetitle']?>">Click here to Add a new feature</a></center>
			<?php
			
		} else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/feature/edit_feature.php");
		}
		
	}
}

else if($_REQUEST['fpurpose'] == 'delete')
{
	
	if($_REQUEST['feature_id']) {
		$alert_del = '';
		$sql_check = $db->value_exists(array('feature_id' => $_REQUEST['feature_id']), 'features');
		if($sql_check) {
			
				#Delete the service record.
				$db->delete_id($_REQUEST['feature_id'], 'feature_id', 'features');
				$db->delete_id($_REQUEST['feature_id'], 'features_feature_id', 'site_menu');
				$db->delete_id($_REQUEST['feature_id'], 'features_feature_id', 'mod_menu');
				$db->delete_id($_REQUEST['feature_id'], 'features_feature_id', 'themes_layouts_features_default_positions');
				$db->delete_id($_REQUEST['feature_id'], 'features_feature_id', 'console_levels_details');
				$db->delete_id($_REQUEST['feature_id'], 'features_feature_id', 'display_settings');
				$alert_del = '<font color="red">Successfully Deleted</font>';
				
				// get the domain name
				$sql_dom 		= "SELECT site_id,site_domain 
										FROM 
											sites ";
				$ret_dom 		= $db->query($sql_dom);
				if ($db->num_rows($ret_dom))
				{
					while ($row_dom 		= $db->fetch_array($ret_dom))
					{
						$base_path 	= IMAGE_ROOT_PATH."/".$row_dom['site_domain'];
						create_SitemenModmenu_CacheFile($base_path,$row_dom['site_id']); // site menu and mod menu cache
					}	
				}
						
		} else {
			$alert_del = '<font color="red">Error! No feature exists with this id</font>';
		}
	}
	include("includes/features/list_features.php");

	
}
else if($_REQUEST['fpurpose'] == 'save_features')
{
//print_r($_REQUEST);
foreach($_REQUEST['ordering'] as $key => $val){
	$update_array = array();
	
	$update_array['feature_ordering'] 		=$_REQUEST['ordering'][$key];
	$update_array['feature_hide'] 			= $_REQUEST['hide'][$key];
	$db->update_from_array($update_array, 'features', 'feature_id', $key);
}
	// get the domain name
	$sql_dom 		= "SELECT site_id,site_domain 
							FROM 
								sites ";
	$ret_dom 		= $db->query($sql_dom);
	if ($db->num_rows($ret_dom))
	{
		while ($row_dom 		= $db->fetch_array($ret_dom))
		{
			$base_path 	= IMAGE_ROOT_PATH."/".$row_dom['site_domain'];
			create_SitemenModmenu_CacheFile($base_path,$row_dom['site_id']); // site menu and mod menu cache
		}	
	}	
	include("includes/features/list_features.php");

	
}
?>