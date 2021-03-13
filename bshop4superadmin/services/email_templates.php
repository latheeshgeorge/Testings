<?php
if($_REQUEST['fpurpose'] == '') {
	include("includes/template/list_templates.php");
} else if($_REQUEST['fpurpose'] == 'add') {
	include("includes/template/add_template.php");
} else if($_REQUEST['fpurpose'] == 'edit') {
	if(is_numeric($_REQUEST['template_id']))
	{
		include("includes/template/edit_template.php");
	}
	else
	{
		$alert = '<center><font color="red">Invalid card Id</font></center><br>';
		echo $alert;
		include("includes/template/list_templates.php");
	}	
}
 else if($_REQUEST['fpurpose'] == 'disable_newsletter')
 {
 	$disable_now 			= ($_REQUEST['disable_now'])?1:0;
	$disable_end_on 		= stripslashes(trim($_REQUEST['disable_end_on']));
	
 	$sql_update = "UPDATE newsletter_disable 
						SET 
							disable_now = $disable_now,
							disable_end_on = '".$disable_end_on."'";
	$db->query($sql_update);
	$alert_bottom = '<center><font color="red">Updated Successfully</font></center><br>';
		include("includes/template/list_templates.php");
 }
 else if($_REQUEST['fpurpose'] == 'insert') {
	if($_REQUEST['Submit'])
	{
		$alert = '';
		$fieldRequired = array($_REQUEST['template_lettertype'],$_REQUEST['template_lettertitle'],$_REQUEST['template_lettersubject']);
		$fieldDescription = array('Letter Type','Letter Title','Letter Subject');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = $db->value_exists(array('template_lettertype' => add_slash($_REQUEST['template_lettertype'])), 'common_emailtemplates');
		if($sql_check > 0) {
			$alert = 'Letter Type already exists';
		}
		if(!$alert) {
			// Checking for duplicate card names
			
			$insert_array = array();
			$insert_array['template_lettertype']	= add_slash($_REQUEST['template_lettertype']);
			$insert_array['template_lettertitle']	= add_slash($_REQUEST['template_lettertitle']); 
			$insert_array['template_lettersubject']	= add_slash($_REQUEST['template_lettersubject']); 
			$insert_array['template_content']		= add_slash($_REQUEST['template_content'],false);
			$insert_array['template_code']			= add_slash($_REQUEST['template_code']);
			
			$db->insert_from_array($insert_array, 'common_emailtemplates');
			$insert_id = $db->insert_id();
			$alert = '<center><font color="red"><b>Successfully Inserted</b></font><br>';
			echo $alert;
			?>
			<br /><a href="home.php?request=email_templates&lettertype=<?=$_REQUEST['lettertype']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Listing page</a><br /><br />
			<a href="home.php?request=email_templates&fpurpose=edit&template_id=<?=$insert_id?>&lettertype=<?=$_REQUEST['lettertype']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Edit this Template</a><br /><br />
			<a href="home.php?request=email_templates&fpurpose=add&sort_by=<?=$_REQUEST['sort_by']?>&lettertype=<?=$_REQUEST['lettertype']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Add new Template</a></center>
			<?php
			
		} else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/template/add_template.php");
		}
		
	}
} else if($_REQUEST['fpurpose'] == 'update') {
	if($_REQUEST['Submit'])
	{
		
		$alert = '';
		$fieldRequired = array($_REQUEST['template_lettertype'],$_REQUEST['template_lettertitle'],$_REQUEST['template_lettersubject']);
		$fieldDescription = array('Letter Type','Letter Title','Letter Subject');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		// Checking for duplicate card names
		$sql_check = "SELECT count(*) as cnt FROM common_emailtemplates WHERE template_lettertype='".add_slash($_REQUEST['template_lettertype'])."' AND template_id<>".$_REQUEST['template_id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
	
		if($row_check['cnt'] > 0) {
			$alert = 'Letter Type already exists';
		}
		if(!$alert) {
			$update_array = array();
			$update_array['template_lettertype']	= add_slash($_REQUEST['template_lettertype']);
			$update_array['template_lettertitle']	= add_slash($_REQUEST['template_lettertitle']);
			$update_array['template_lettersubject']	= add_slash($_REQUEST['template_lettersubject']);
			$update_array['template_content']		= add_slash($_REQUEST['template_content'],false);
			$update_array['template_code']			= add_slash($_REQUEST['template_code']);
			$db->update_from_array($update_array, 'common_emailtemplates', 'template_id', $_REQUEST['template_id']);
			$alert = '<center><font color="red"><b>Successfully Updated</b></font><br>';
			echo $alert;
			?>
			<br /><a href="home.php?request=email_templates&lettertype=<?=$_REQUEST['lettertype']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Listing page</a><br /><br />
			<a href="home.php?request=email_templates&fpurpose=edit&template_id=<?=$_REQUEST['template_id']?>&lettertype=<?=$_REQUEST['lettertype']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Edit this Template</a><br /><br />
			<a href="home.php?request=email_templates&fpurpose=add&lettertype=<?=$_REQUEST['lettertype']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Add Template</a></center>
			<?php
			
		} else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/template/edit_template.php");
		}
		
	}
}
elseif ($_REQUEST['fpurpose'] == 'delete') {
	if($_REQUEST['template_id']) {
		$alert_del = '';
		$sql_check = $db->value_exists(array('template_id' => $_REQUEST['template_id']), 'common_emailtemplates');
		if($sql_check) {
			#Delete the client record.
			$db->delete_id($_REQUEST['template_id'], 'template_id', 'common_emailtemplates');
			#Search Options
			$alert_del = '<font color="red">Successfully Deleted</font>';
				
			
		} else {
			$alert_del = '<font color="red">Error! No country exists with this id</font>';
		}
	}
	include("includes/template/list_templates.php");
}

?>