<?php
if($_REQUEST['fpurpose'] == '') {
	include("includes/help_message/list_message.php");
}
else if($_REQUEST['fpurpose'] == 'add') {
	include("includes/help_message/add_message.php");
}
if($_REQUEST['fpurpose'] == 'edit') {
	include("includes/help_message/edit_message.php");
}
else if($_REQUEST['fpurpose'] == 'update') {
	if($_REQUEST['Submit'])
	{
		$alert = '';
		$fieldRequired 		= array($_REQUEST['help_help_message'],$_REQUEST['help_type'],$_REQUEST['help_code'],$_REQUEST['console_help_group_id']);
		$fieldDescription 	= array('Message','Message Type','Message Code','Message Group');
		$fieldEmail 		= array();
		$fieldConfirm 		= array();
		$fieldConfirmDesc 	= array();
		$fieldNumeric 		= array();
		$fieldNumericDesc 	= array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		//Check whether the code existing or not
		$sql_check 	= "SELECT count(*) as cnt FROM console_help_messages WHERE help_code='".add_slash($_REQUEST['help_code'])."' AND help_id <>".$_REQUEST['help_id'];
		$res_check 	= $db->query($sql_check);
		$row_check 	= $db->fetch_array($res_check);
	
		if($row_check['cnt'] > 0) {
			$alert = 'Message code already exists';
		}
		if(!$alert) {
			$update_array = array();
			$update_array['help_help_message']		 = add_slash($_REQUEST['help_help_message'],false);
			$update_array['help_type']				 = add_slash($_REQUEST['help_type']);
			$update_array['help_code']				 = add_slash($_REQUEST['help_code']);
			$update_array['console_help_section_help_group_id']	= add_slash($_REQUEST['console_help_group_id']);
			$db->update_from_array($update_array, 'console_help_messages', 'help_id', $_REQUEST['help_id']);
			$alert = '<center><font color="red"><b>Successfully Updated</b></font><br>';
			echo $alert;
			?>
			<br /><a href="home.php?request=help_message&help_message=<?=$_REQUEST['help_message']?>&help_group_search=<?=$_REQUEST['help_group_search']?>&help_code_search=<?=$_REQUEST['help_code_search']?>&help_type_search=<?=$_REQUEST['help_type_search']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Message Listing page</a><br /><br />
			<a href="home.php?request=help_message&fpurpose=edit&help_id=<?=$_REQUEST['help_id']?>&help_group_search=<?=$_REQUEST['help_group_search']?>&help_message=<?=$_REQUEST['help_message']?>&help_code_search=<?=$_REQUEST['help_code_search']?>&help_type_search=<?=$_REQUEST['help_type_search']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Edit this Message</a>
			<br />
			<br />
			<a href="home.php?request=help_message&fpurpose=add&help_message=<?=$_REQUEST['help_message']?>&help_group_search=<?=$_REQUEST['help_group_search']?>&help_code_search=<?=$_REQUEST['help_code_search']?>&help_type_search=<?=$_REQUEST['help_type_search']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>">Add a New Message</a>
			</center>
			<?php
		} else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/help_message/edit_message.php");
		}
		
	}
}	
else if($_REQUEST['fpurpose'] == 'insert') {
	if($_REQUEST['Submit'])
	{
		$alert = '';
		$fieldRequired 		= array($_REQUEST['help_help_message'],$_REQUEST['help_type'],$_REQUEST['help_code'],$_REQUEST['console_help_group_id']);
		$fieldDescription 	= array('Message','Message Type','Message Code','Message Group');
		$fieldEmail			= array();
		$fieldConfirm 		= array();
		$fieldConfirmDesc 	= array();
		$fieldNumeric 		= array();
		$fieldNumericDesc 	= array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = $db->value_exists(array('help_code' => $_REQUEST['help_code']), 'console_help_messages');
		if($sql_check > 0) {
			$alert = 'Message code already exists';
		}
		if(!$alert) {
			$insert_array 	= array();
			$insert_array['help_help_message']				 	 = add_slash($_REQUEST['help_help_message'],false);
			$insert_array['help_type']				 			 = add_slash($_REQUEST['help_type']);
			$insert_array['help_code']							 = add_slash($_REQUEST['help_code']);
			$insert_array['console_help_section_help_group_id']	 = add_slash($_REQUEST['console_help_group_id']);

			$db->insert_from_array($insert_array, 'console_help_messages');
			$insert_id = $db->insert_id();
			$alert = '<center><font color="red"><b>Successfully Inserted</b></font><br>';
			echo $alert;
			?>
			<br /><a href="home.php?request=help_message&help_message=<?=$_REQUEST['help_message']?>&help_code_search=<?=$_REQUEST['help_code_search']?>&help_type_search=<?=$_REQUEST['help_type_search']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Message Listing page</a><br /><br />
			<a href="home.php?request=help_message&fpurpose=edit&help_id=<?=$insert_id?>&help_message=<?=$_REQUEST['help_message']?>&help_code_search=<?=$_REQUEST['help_code_search']?>&help_type_search=<?=$_REQUEST['help_type_search']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Edit this Message</a>
			<br />
			<br />
			<a href="home.php?request=help_message&fpurpose=add&help_message=<?=$_REQUEST['help_message']?>&help_code_search=<?=$_REQUEST['help_code_search']?>&help_type_search=<?=$_REQUEST['help_type_search']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>">Add a New Message</a>
			</center>
			<?php
		} else {
			$alert  = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/help_message/add_message.php");
		}
	}
} 
else if($_REQUEST['fpurpose'] == 'save_hidden')
{
foreach($_REQUEST['hide'] as $key => $val){
	$update_array = array();
	$update_array['help_hidden'] 			= $_REQUEST['hide'][$key];
	$db->update_from_array($update_array, 'console_help_messages', 'help_id', $key);
}
	include("includes/help_message/list_message.php");
}
else if($_REQUEST['fpurpose'] == 'delete') {
				 		$sql_del = "DELETE FROM console_help_messages WHERE help_id =".$_REQUEST['help_id'];
				 		$db->query($sql_del);
						$error_msg = 'Message Deleted Successfully';
include("includes/help_message/list_message.php");
}
 
?>