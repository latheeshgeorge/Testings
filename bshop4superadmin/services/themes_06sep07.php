<?php
if($_REQUEST['fpurpose'] == '') {
	include("includes/themes/list_themes.php");
} else if($_REQUEST['fpurpose'] == 'add') {
	include("includes/themes/add_theme.php");
} else if($_REQUEST['fpurpose'] == 'edit') {
	if(is_numeric($_REQUEST['theme_id']))
	{
		include("includes/themes/edit_theme.php");
	}
	else
	{
		$alert = '<center><font color="red">Invalid theme Id</font></center><br>';
		echo $alert;
		include("includes/themes/list_themes.php");
	}	
} else if($_REQUEST['fpurpose'] == 'edit_layout') {
	if(is_numeric($_REQUEST['layout_id']))
	{	
		include("includes/themes/edit_theme_layouts.php");
	}
	else
	{
		$alert = '<center><font color="red">Invalid theme layout Id</font></center><br>';
		echo $alert;
		include("includes/themes/list_theme_layouts.php");
	}	
} 

else if($_REQUEST['fpurpose'] == 'insert_layouts') {
	
	if($_REQUEST['Submit'])
	{
	
		$alert = '';
		$fieldRequired = array($_REQUEST['layout_name'],$_REQUEST['layout_code']);
		$fieldDescription = array('Layout Name','Layout Code');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
       $sql_check = "SELECT count(*) as cnt FROM themes_layouts WHERE layout_name='".add_slash($_REQUEST['layout_name'])."' AND themes_theme_id=".$_REQUEST['pass_theme_id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
	
		if($row_check['cnt'] > 0) {
			$alert = 'Layout  Name already exists for current theme';
		}
		
		if(!$alert) {
			$insert_array = array();
			$insert_array['layout_name'] = add_slash($_REQUEST['layout_name']);
			$insert_array['layout_code'] = add_slash($_REQUEST['layout_code']);
			
			/*
			$str_layout_positions='';
			if(count($_REQUEST['layout_positions'])>0)
			{
					foreach($_REQUEST['layout_positions'] as $v)
					{
						$str_layout_positions.=$v.',';
						
					}
					$str_layout_positions=substr($str_layout_positions,0,(strlen($str_layout_positions)-1));
					
			}*/
			
			$insert_array['layout_positions'] =$_REQUEST['layout_positions'] ;
			$insert_array['themes_theme_id'] = $_REQUEST['pass_theme_id'];
			$db->insert_from_array($insert_array, 'themes_layouts');
			$insert_id = $db->insert_id();
			$alert = '<center><font color="red"><b>Successfully Inserted</b></font><br>';
			echo $alert;
			?>
			<br /><a href="home.php?request=themes&theme_name=<?=$_REQUEST['theme_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&theme_name=<?=$_REQUEST['pass_theme_name']?>">Go Back to the Theme Listing page</a><br /><br />
			<a href="home.php?request=themes&fpurpose=theme_layout&pass_theme_id=<?=$_REQUEST['pass_theme_id']?>&theme_name=<?=$_REQUEST['theme_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Theme Layout Listing page</a><br /><br />
			<a href="home.php?request=themes&fpurpose=theme_layout_add&pass_theme_id=<?=$_REQUEST['pass_theme_id']?>&theme_name=<?=$_REQUEST['theme_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Add a New Layout</a><br /><br />
			<a href="home.php?request=themes&fpurpose=edit_layout&layout_id=<?=$insert_id?>&pass_theme_id=<?=$_REQUEST['pass_theme_id']?>&theme_name=<?=$theme_name?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>">Go Back to Edit Current Layout</a>
		<?	
		}
		else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/themes/add_theme_layouts.php");
			
		}
		
	}
	
	
} else if($_REQUEST['fpurpose'] == 'insert') {
	if($_REQUEST['Submit'])
	{
		$alert = '';
		$fieldRequired = array($_REQUEST['themename'],$_REQUEST['path']);
		$fieldDescription = array('Theme Name','Theme Path');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = $db->value_exists(array('themename' => $_REQUEST['themename']), 'themes');
		if($sql_check > 0) {
			$alert = 'Theme Name already exists';
		}
		if(!$alert) {
			$insert_array = array();
			$insert_array['themename']					= add_slash($_REQUEST['themename']);
			$insert_array['path']						= add_slash($_REQUEST['path']);
			$insert_array['in_setup']					= ($_REQUEST['in_setup'])?1:0;
			$insert_array['page_positions']				= add_slash($_REQUEST['page_positions']);
			$insert_array['advert_positions']			= add_slash($_REQUEST['advert_positions']);
			$insert_array['categorygroup_positions']	= add_slash($_REQUEST['categorygroup_positions']);
			$insert_array['shelf_positions']			= add_slash($_REQUEST['shelf_positions']);
			$insert_array['combo_positions']			= add_slash($_REQUEST['combo_positions']);
			$insert_array['thumbimage_geometry']		= add_slash($_REQUEST['thumbimage_geometry']);
			$insert_array['bigimage_geometry']			= add_slash($_REQUEST['bigimage_geometry']);
			$insert_array['advertimage_geometry']		= add_slash($_REQUEST['advertimage_geometry']);
			$db->insert_from_array($insert_array, 'themes');
			$insert_id = $db->insert_id();
			$alert = '<center><font color="red"><b>Successfully Inserted</b></font><br>';
			echo $alert;
			?>
			<br /><a href="home.php?request=themes&theme_name=<?=$_REQUEST['theme_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Listing page</a><br /><br />
			<a href="home.php?request=themes&fpurpose=edit&theme_id=<?=$insert_id?>&theme_name=<?=$_REQUEST['theme_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Edit this Theme</a>
			<br /><br />
			<a href="home.php?request=themes&fpurpose=add&theme_name=<?=$theme_name?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>">Add a New Theme</a>
			
			</center>
			<?php
			
		} else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/themes/add_theme.php");
		}
		
	}
} else if($_REQUEST['fpurpose'] == 'update_layouts') {
	if($_REQUEST['Submit'])
	{
		$alert = '';
		$fieldRequired = array($_REQUEST['layout_name'],$_REQUEST['layout_code']);
		$fieldDescription = array('Layout Name','Layout Code');
		
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = "SELECT count(*) as cnt FROM themes_layouts WHERE layout_name='".add_slash($_REQUEST['layout_name'])."' AND themes_theme_id=".$_REQUEST['pass_theme_id'] . " AND layout_id<>".$_REQUEST['layout_id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
	
		if($row_check['cnt'] > 0) {
			$alert = 'Layout Name already exists for current theme';
		}
		if(!$alert) {
			$update_array = array();
			$update_array['layout_name'] = add_slash($_REQUEST['layout_name']);
			$update_array['layout_code'] = add_slash($_REQUEST['layout_code']);
			/*
			$str_layout_positions='';
			if(count($_REQUEST['layout_positions'])>0)
			{
					foreach($_REQUEST['layout_positions'] as $v)
					{
						$str_layout_positions.=$v.',';
						
					}
					$str_layout_positions=substr($str_layout_positions,0,(strlen($str_layout_positions)-1));
					
			}*/
			
			$update_array['layout_positions'] = $_REQUEST['layout_positions'];
			$update_array['themes_theme_id'] = $_REQUEST['pass_theme_id'];
			$db->update_from_array($update_array, 'themes_layouts', 'layout_id', $_REQUEST['layout_id']);
			
			//Features default positions
			$sql_del = "DELETE FROM themes_layouts_features_default_positions WHERE themes_layouts_layout_id=".$_REQUEST['layout_id'];
			$db->query($sql_del);
			
			$i=1;
			while($i<=$_REQUEST['features_cnt'])
			{
				
				if($_REQUEST['feature_position'.$i])
				{
					$insert_array_pos = array();
					$insert_array_pos['themes_layouts_layout_id']=$_REQUEST['layout_id'];
					$insert_array_pos['features_feature_id']=$_REQUEST['feature_id'.$i];
					$insert_array_pos['def_position']=$_REQUEST['feature_position'.$i];
					$insert_array_pos['def_order']=add_slash($_REQUEST['feature_order'.$i]);
					
					$db->insert_from_array($insert_array_pos, 'themes_layouts_features_default_positions');
				}
				$i++;
			}
			
			$alert = '<center><font color="red"><b>Successfully Updated</b></font><br>';
			echo $alert;
			?>
			<br /><a href="home.php?request=themes&theme_name=<?=$_REQUEST['theme_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&theme_name=<?=$_REQUEST['pass_theme_name']?>">Go Back to the Theme Listing page</a><br /><br />
			<a href="home.php?request=themes&fpurpose=theme_layout&pass_theme_id=<?=$_REQUEST['pass_theme_id']?>&theme_name=<?=$_REQUEST['theme_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Theme Layout Listing page</a><br /><br />
			<a href="home.php?request=themes&fpurpose=theme_layout_add&pass_theme_id=<?=$_REQUEST['pass_theme_id']?>&theme_name=<?=$_REQUEST['theme_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Add a New Layout</a><br /><br />
			<a href="home.php?request=themes&fpurpose=edit_layout&layout_id=<?=$_REQUEST['layout_id']?>&pass_theme_id=<?=$_REQUEST['pass_theme_id']?>&theme_name=<?=$theme_name?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>">Go Back to Edit Current Layout</a>
			
			
		<?	
		}
		else
		{
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/themes/edit_theme_layouts.php");	
		}
		
	}
} else if($_REQUEST['fpurpose'] == 'update') {
	if($_REQUEST['Submit'])
	{
		$alert = '';
		$fieldRequired = array($_REQUEST['themename'],$_REQUEST['path']);
		$fieldDescription = array('Theme Name','Theme Path');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = "SELECT count(*) as cnt FROM themes WHERE themename='".$_REQUEST['themename']."' AND theme_id<>".$_REQUEST['theme_id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
	
		if($row_check['cnt'] > 0) {
			$alert = 'Theme Name already exists';
		}
		if(!$alert) {
			$update_array = array();
			$update_array['themename']					= add_slash($_REQUEST['themename']);
			$update_array['path']						= add_slash($_REQUEST['path']);
			$update_array['in_setup']					= ($_REQUEST['in_setup'])?1:0;
			$update_array['page_positions']				= add_slash($_REQUEST['page_positions']);
			$update_array['advert_positions']			= add_slash($_REQUEST['advert_positions']);
			$update_array['categorygroup_positions']	= add_slash($_REQUEST['categorygroup_positions']);
			$update_array['shelf_positions']			= add_slash($_REQUEST['shelf_positions']);
			$update_array['combo_positions']			= add_slash($_REQUEST['combo_positions']);
			$update_array['thumbimage_geometry']		= add_slash($_REQUEST['thumbimage_geometry']);
			$update_array['bigimage_geometry']			= add_slash($_REQUEST['bigimage_geometry']);
			$update_array['advertimage_geometry']		= add_slash($_REQUEST['advertimage_geometry']);
			$db->update_from_array($update_array, 'themes', 'theme_id', $_REQUEST['theme_id']);
			$alert = '<center><font color="red"><b>Successfully Updated</b></font><br>';
			echo $alert;
			?>
			<br /><a href="home.php?request=themes&theme_name=<?=$_REQUEST['theme_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Listing page</a><br /><br />
			<a href="home.php?request=themes&fpurpose=edit&theme_id=<?=$_REQUEST['theme_id']?>&theme_name=<?=$_REQUEST['theme_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Edit this Theme</a>
			<br />
			<br />
			<a href="home.php?request=themes&fpurpose=add&theme_name=<?=$theme_name?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>">Add a New Theme</a>
			
			</center>
			<?php
			
		} else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/themes/edit_theme.php");
		}
		
	}
}
else if($_REQUEST['fpurpose'] == 'delete_layout'){
    
	$sql_del = "DELETE FROM themes_layouts WHERE layout_id=".$_REQUEST['layout_id'];
	$db->query($sql_del);
	$error_msg = 'Layout Deleted Successfully';
	include("includes/themes/list_theme_layouts.php");
	
}else if($_REQUEST['fpurpose'] == 'delete')
{
	// Check whether this theme is used in any of the sites
	$sql_sites 			= "SELECT count(site_id) as cnt FROM sites WHERE themes_theme_id=".$_REQUEST['theme_id'];
	$ret_sites 			= $db->query($sql_sites);
	list($use_cnt) 		= $db->fetch_array($ret_sites);
	if($use_cnt>0)
	{
		$error_msg = 'Sorry Delete not possible... Theme is in use by site(s)';
	}
	else
	{
		// Get all the entries in layout table related to this theme
		$sql_layout = "SELECT layout_id FROM themes_layouts WHERE themes_theme_id=".$_REQUEST['theme_id'];
		$ret_layout = $db->query($sql_layout);
		if($db->num_rows($ret_layout))
		{
			while ($row_layout = $db->fetch_array($ret_layout))
			{
				// Deleting the entries related to current layout from themes_layouts_features_default_positions
				$sql_del = "DELETE FROM themes_layouts_features_default_positions WHERE themes_layouts_layout_id =".$row_layout['layout_id'];
				$db->query($sql_del);
			}
		}
		$sql_del = "DELETE FROM themes_layouts WHERE themes_theme_id=".$_REQUEST['theme_id'];
		$db->query($sql_del);
		$sql_del = "DELETE FROM themes WHERE theme_id=".$_REQUEST['theme_id'];
		$db->query($sql_del);
		$error_msg = 'Theme Deleted Successfully';
	}
	include("includes/themes/list_themes.php");
}
elseif($_REQUEST['fpurpose']=='theme_layout')
{
	include("includes/themes/list_theme_layouts.php");
}
elseif($_REQUEST['fpurpose']=='theme_layout_add')
{
	include("includes/themes/add_theme_layouts.php");
}
elseif($_REQUEST['fpurpose']=='theme_layout_edit')
{
	include("includes/themes/edit_theme_layouts.php");
}
?>