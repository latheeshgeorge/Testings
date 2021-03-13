<?php
if($_REQUEST['fpurpose'] == '') {
	include("includes/help_message_group/list_message_groups.php");
}
else if($_REQUEST['fpurpose'] == 'add') {
	include("includes/help_message_group/add_message_groups.php");
}
if($_REQUEST['fpurpose'] == 'edit') {
	include("includes/help_message_group/edit_message_groups.php");
}
else if($_REQUEST['fpurpose'] == 'update') {
	if($_REQUEST['Submit'])
	{
		$alert = '';
		$fieldRequired 		= array($_REQUEST['help_group_name']);
		$fieldDescription 	= array('Message Group Name');
		$fieldEmail 		= array();
		$fieldConfirm	    = array();
		$fieldConfirmDesc 	= array();
		$fieldNumeric 		= array();
		$fieldNumericDesc 	= array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		//check whether group name existing
		$sql_check = "SELECT count(*) as cnt FROM console_help_group WHERE help_group_name='".add_slash($_REQUEST['help_group_name'])."' AND help_group_id<>".$_REQUEST['help_group_id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		if($row_check['cnt'] > 0) {
			$alert = 'Group  Name already exists';
		}
		if(!$alert) {
			$update_array = array();
			$update_array['help_group_name']				 = add_slash($_REQUEST['help_group_name']);

			$db->update_from_array($update_array, 'console_help_group', 'help_group_id', $_REQUEST['help_group_id']);
			$alert = '<center><font color="red"><b>Successfully Updated</b></font><br>';
			echo $alert;
			?>
			<br /><a href="home.php?request=help_message_group&help_group=<?=$_REQUEST['help_group']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Groups Listing page</a><br /><br />
			<a href="home.php?request=help_message_group&fpurpose=edit&help_group_id=<?=$_REQUEST['help_group_id']?>&help_group=<?=$_REQUEST['help_group']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Edit this Group</a>
			<br />
			<br />
			<a href="home.php?request=help_message_group&fpurpose=add&help_group=<?=$help_group?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>">Add a New Group</a>
			
			</center>
			<?php
			
		} else {
			$alert  = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/help_message_group/edit_message_groups.php");
		}
		
	}
}	
else if($_REQUEST['fpurpose'] == 'insert') {
	if($_REQUEST['Submit'])
	{
		$alert = '';
		$fieldRequired 		= array($_REQUEST['help_group_name']);
		$fieldDescription 	= array('Group Name');
		$fieldEmail 		= array();
		$fieldConfirm 		= array();
		$fieldConfirmDesc 	= array();
		$fieldNumeric 		= array();
		$fieldNumericDesc 	= array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = $db->value_exists(array('help_group_name' => $_REQUEST['help_group_name']), 'console_help_group');
		if($sql_check > 0) {
			$alert = 'Group Name already exists';
		}
		if(!$alert) {
			$insert_array = array();
			$insert_array['help_group_name']				  = add_slash($_REQUEST['help_group_name']);
			$db->insert_from_array($insert_array, 'console_help_group');
			$insert_id = $db->insert_id();
			$alert = '<center><font color="red"><b>Successfully Inserted</b></font><br>';
			echo $alert;
			?>
			<br /><a href="home.php?request=help_message_group&help_group=<?=$_REQUEST['help_group']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Message Group Listing page</a><br /><br />
			<a href="home.php?request=help_message_group&fpurpose=edit&help_group_id=<?=$insert_id?>&help_group=<?=$_REQUEST['help_group']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Edit this Message Group</a>
			<br />
			<br />
			<a href="home.php?request=help_message_group&fpurpose=add&help_group=<?=$_REQUEST['help_group']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>">Add a New Message Group type </a>
			</center>
			<?php
		} else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/help_message_group/add_message_groups.php");
		}
		
	}
} 
else if($_REQUEST['fpurpose'] == 'delete') {
				 		$sql_del = "DELETE FROM console_help_group WHERE help_group_id =".$_REQUEST['help_group_id'];
				 		$db->query($sql_del);
						$error_msg = 'Message Group Deleted Successfully';
			include("includes/help_message_group/list_message_groups.php");
}
 
?>