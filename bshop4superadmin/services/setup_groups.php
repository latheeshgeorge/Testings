<?php
if($_REQUEST['fpurpose'] == '')
{
	include("includes/setup_groups/list_setupgroups.php");
}
else if($_REQUEST['fpurpose'] == 'list_layout')
{
	include("includes/setup_groups/list_layoutname.php");
}
else if($_REQUEST['fpurpose'] == 'edit_layout')
{
	include("includes/setup_groups/edit_layoutname.php");
}
else if($_REQUEST['fpurpose'] == 'add_layout')
{
	include("includes/setup_groups/add_layoutname.php");
}
else if($_REQUEST['fpurpose'] == 'add')
{
	include("includes/setup_groups/add_setupgroups.php");
}
else if($_REQUEST['fpurpose'] == 'edit')
{
	include("includes/setup_groups/edit_setupgroups.php");
} 
else if($_REQUEST['fpurpose'] == 'insert') {
	if($_REQUEST['Submit'])
	{
		$alert = '';
		$fieldRequired = array($_REQUEST['group_title']);
		$fieldDescription = array('Group Title');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = "SELECT group_id 
								FROM 
									setup_groups 
								WHERE 
									themes_theme_id=".$_REQUEST['theme_id']." 
									AND group_title='".add_slash($_REQUEST['group_title'])."' 
								LIMIT 
									1";
		$ret_check = $db->query($sql_check);
		if ($db->num_rows($ret_check))
		{
			$alert = 'Group already exists for current theme';
		}
		if(!$alert) {
			// Checking for duplicate card names
			$order = (!is_numeric($_REQUEST['group_order']))?0:$_REQUEST['group_order'];
			$insert_array 								= array();
			$insert_array['group_title']			= add_slash($_REQUEST['group_title']); 
			$insert_array['group_order']			= $order;
			$insert_array['themes_theme_id']	= add_slash($_REQUEST['theme_id']);
			$insert_array['group_hidden']		=($_REQUEST['group_hidden'])?1:0;
			$db->insert_from_array($insert_array, 'setup_groups');
			$insert_id = $db->insert_id();
			$alert = '<center><font color="red"><b>Group Added Successfully</b></font><br>';
			echo $alert;
			?>
			<br /><a href="home.php?request=setup_groups&src_title=<?=$_REQUEST['src_title']?>&theme_id=<?php echo $_REQUEST['theme_id']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Listing page</a><br /><br />
			<a href="home.php?request=setup_groups&fpurpose=edit&group_id=<?=$insert_id?>&src_title=<?=$_REQUEST['src_title']?>&theme_id=<?php echo $_REQUEST['theme_id']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Edit this Setup Wizard Group</a><br /><br />
			<a href="home.php?request=setup_groups&fpurpose=add&sort_by=<?=$_REQUEST['sort_by']?>&src_title=<?=$_REQUEST['src_title']?>&theme_id=<?php echo $_REQUEST['theme_id']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Add new Setup Wizard</a></center>
			<?php
			
		} else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/setup_groups/add_setupgroups.php");
		}
		
	}
} else if($_REQUEST['fpurpose'] == 'update') {
	if($_REQUEST['Submit'])
	{
		
		$alert = '';
		$fieldRequired = array($_REQUEST['group_title']);
		$fieldDescription = array('Group Title');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		// Checking for duplicate card names
		$sql_check = "SELECT count(*) as cnt 
									FROM 
										setup_groups 
									WHERE 
										group_title='".$_REQUEST['group_title']."' 
										AND group_id<>".$_REQUEST['group_id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		if($row_check['cnt'] > 0) {
			$alert = 'Sorry!! Group already exists';
		}
		if(!$alert) {
			$order = (!is_numeric($_REQUEST['group_order']))?0:$_REQUEST['group_order'];
			$update_array = array();
			$update_array['group_title']					=	add_slash($_REQUEST['group_title']);
			$update_array['group_order']				= $order;
			$update_array['themes_theme_id']		= $_REQUEST['theme_id'];
			$update_array['group_hidden']				= ($_REQUEST['group_hidden'])?1:0;
			$db->update_from_array($update_array, 'setup_groups', array('group_id'=>$_REQUEST['group_id']));
			$alert = '<center><font color="red"><b>Setup Wizard Group updated Successfully</b></font><br>';
			echo $alert;
			?>
			<br /><a href="home.php?request=setup_groups&src_title=<?=$_REQUEST['src_title']?>&theme_id=<?php echo $_REQUEST['theme_id']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Listing page</a><br /><br />
			<a href="home.php?request=setup_groups&fpurpose=edit&group_id=<?=$_REQUEST['group_id']?>&src_title=<?=$_REQUEST['src_title']?>&theme_id=<?php echo $_REQUEST['theme_id']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Edit this Setup Wizard Group</a><br /><br />
			<a href="home.php?request=setup_groups&fpurpose=add&src_title=<?=$_REQUEST['src_title']?>&theme_id=<?php echo $_REQUEST['theme_id']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Add new Setup Wizard Group</a></center>
			<?php
			
		} else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/setup_groups/edit_setupgroups.php");
		}
		
	}
}
elseif ($_REQUEST['fpurpose'] == 'delete') {
	if($_REQUEST['group_id']) {
		$alert_del = '';
		// Check whether any items exists for current group
		$sql_items = "SELECT item_id 
								FROM 
									setup_items 
								WHERE 
									setup_groups_group_id=".$_REQUEST['group_id']." 
								LIMIT 
									1";
		$ret_check = $db->query($sql_items);
		if($db->num_rows($ret_check)==0)
		 {
			#Delete the client record.
			$db->delete_id($_REQUEST['group_id'], 'group_id', 'setup_groups');
			#Search Options
			$alert_del = '<font color="red">Setup Wizard Group deleted Successfully</font>';
		}
		else
		{
			$alert_del = '<font color="red">Sorry!! Items exists under current group. Delete the items first</font>';
		}
	}
	include("includes/setup_groups/list_setupgroups.php");
}


else if($_REQUEST['fpurpose'] == 'layout_insert')
{
	if($_REQUEST['Submit'])   
	{
		$alert = '';
		$fieldRequired = array($_REQUEST['layout_name'], $_REQUEST['layout_id']);
		$fieldDescription = array('Layout Name', 'Layout Code');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		
		$sql_check = "SELECT layout_id 
								FROM 
									setup_layout
								WHERE 
									themes_theme_id=".$_REQUEST['theme_id']." 
									AND layout_name='".add_slash($_REQUEST['layout_name'])."' 
								LIMIT 
									1";
		$ret_check = $db->query($sql_check);
		if ($db->num_rows($ret_check))
		{
			$alert = 'Layout Name already exists for current theme';
		}
		if(!$alert) {
			// Checking for duplicate card names
			$insert_array 								= array();
			$insert_array['layout_name']			= add_slash($_REQUEST['layout_name']); 
			$insert_array['layout_id']			= add_slash($_REQUEST['layout_id']); 
			$insert_array['themes_theme_id']	= add_slash($_REQUEST['theme_id']);
			$db->insert_from_array($insert_array, 'setup_layout');
		
			$alert = '<center><font color="red"><b>layout Name Added Successfully</b></font><br>';
			echo $alert;
			?>
			<br /><a href="home.php?request=setup_groups&fpurpose=list_layout&theme_id=<?php echo $_REQUEST['theme_id']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Listing page</a><br /><br />
			<a href="home.php?request=setup_groups&fpurpose=edit_layout&group_id=<?=$insert_id?>&src_title=<?=$_REQUEST['src_title']?>&theme_id=<?php echo $_REQUEST['theme_id']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Edit this Setup Wizard Group</a><br /><br />
			<a href="home.php?request=setup_groups&fpurpose=layout_add&sort_by=<?=$_REQUEST['sort_by']?>&src_title=<?=$_REQUEST['src_title']?>&theme_id=<?php echo $_REQUEST['theme_id']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Add new Setup Wizard</a></center>
			<?php
			
		} else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/setup_groups/add_layoutname.php");
		}
		
	}
}
else if($_REQUEST['fpurpose'] == 'layout_update')
{
	if($_REQUEST['Submit'])
	{
		$alert = '';
		$fieldRequired = array($_REQUEST['layout_name'], $_REQUEST['layout_id']);
		$fieldDescription = array('Layout Name', 'Layout Code');		
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		// Checking for duplicate card names
		
		$sql_check = "SELECT layout_id 
								FROM 
									setup_layout
								WHERE 
									themes_theme_id=".$_REQUEST['theme_id']."  
									AND id<>".$_REQUEST['id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		if($row_check['cnt'] > 0) {
			$alert = 'Sorry!! Layout Name already exists';
		}
		if(!$alert) {
			$update_array = array();
			$update_array['layout_name']		=	add_slash($_REQUEST['layout_name']);
			$update_array['layout_id']			= add_slash($_REQUEST['layout_id']); 
			$update_array['themes_theme_id']	= $_REQUEST['theme_id'];
	
			$db->update_from_array($update_array, 'setup_layout', array('id'=>$_REQUEST['id']));
			$alert = '<center><font color="red"><b>Setup Wizard Layout Name updated Successfully</b></font><br>';
			echo $alert;
			?>
			<br /><a href="home.php?request=setup_groups&fpurpose=list_layout&src_title=<?=$_REQUEST['src_title']?>&theme_id=<?php echo $_REQUEST['theme_id']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Listing page</a><br /><br />
			<a href="home.php?request=setup_groups&fpurpose=edit_layout&id=<?=$_REQUEST['id']?>&src_title=<?=$_REQUEST['src_title']?>&theme_id=<?php echo $_REQUEST['theme_id']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Edit this Setup Wizard Layout Name</a><br /><br />
			<a href="home.php?request=setup_groups&fpurpose=layout_add&src_title=<?=$_REQUEST['src_title']?>&theme_id=<?php echo $_REQUEST['theme_id']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Add new Setup Wizard Layout Name</a></center>
			<?php
			
		} else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/setup_groups/edit_layoutname.php");
		}
		
	}
}
elseif ($_REQUEST['fpurpose'] == 'delete_layout') {
	if($_REQUEST['id']) {
		$alert_del = '';
		// Check whether any items exists for current group
		$sql_items = "SELECT id 
								FROM 
									setup_layout 
								WHERE 
									id=".$_REQUEST['id']." 
								LIMIT 
									1";
		$ret_check = $db->query($sql_items);
		if($db->num_rows($ret_check)>0)
		 {
			#Delete the client record.
			$db->delete_id($_REQUEST['id'], 'id', 'setup_layout');
			#Search Options
			$alert_del = '<font color="red">Setup Wizard Layout Name deleted Successfully</font>';
		}
		
	}
	include("includes/setup_groups/list_layoutname.php");
}
?>