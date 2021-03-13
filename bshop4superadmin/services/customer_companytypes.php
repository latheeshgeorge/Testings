<?php
if($_REQUEST['fpurpose'] == '') {
	include("includes/customer_companytypes/list_companytypes.php");
} else if($_REQUEST['fpurpose'] == 'add') {
	include("includes/customer_companytypes/add_companytypes.php");
} else if($_REQUEST['fpurpose'] == 'edit') {
	if(is_numeric($_REQUEST['comptype_id']))
	{
		include("includes/customer_companytypes/edit_companytypes.php");
	}
	else
	{
		$alert = '<center><font color="red">Invalid company type Id</font></center><br>';
		echo $alert;
		include("includes/customer_companytypes/list_companytypes.php");
	}	
} else if($_REQUEST['fpurpose'] == 'insert') {
	if($_REQUEST['Submit'])
	{
		$alert = '';
		$fieldRequired = array($_REQUEST['comptype_name']);
		$fieldDescription = array('Company type Name');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = $db->value_exists(array('comptype_name' => $_REQUEST['comptype_name']), 'common_customer_company_types');
		if($sql_check > 0) {
			$alert = 'Company type Name already exists';
		}
		if(!$alert) {
			// Checking for duplicate company types names
			
			$insert_array = array();
			$insert_array['comptype_name']=add_slash($_REQUEST['comptype_name']); 
			$db->insert_from_array($insert_array, 'common_customer_company_types');
			$insert_id = $db->insert_id();
			$alert = '<center><font color="red"><b>Successfully Inserted</b></font><br>';
			echo $alert;
			?>
			<br /><a href="home.php?request=cust_comptype&comptypename=<?=$_REQUEST['comptypename']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Listing page</a><br /><br />
			<a href="home.php?request=cust_comptype&fpurpose=edit&comptype_id=<?=$insert_id?>&comptypename=<?=$_REQUEST['comptypename']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Edit this Company Type</a><br /><br />
			<a href="home.php?request=cust_comptype&fpurpose=add&comptypename=<?=$_REQUEST['comptypename']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Add new Company Type</a></center>
			<?php
			
		} else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/customer_companytypes/add_companytypes.php");
		}
		
	}
} else if($_REQUEST['fpurpose'] == 'update') {
	if($_REQUEST['Submit'])
	{
		$alert = '';
		$fieldRequired = array($_REQUEST['comptype_name']);
		$fieldDescription = array('Company Type Name');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = "SELECT count(*) as cnt FROM common_customer_company_types WHERE comptype_name='".$_REQUEST['comptype_name']."' AND comptype_id<>".$_REQUEST['comptype_id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
	
		if($row_check['cnt'] > 0) {
			$alert = 'Company Type Name already exists';
		}
		if(!$alert) {
			$update_array = array();
			$update_array['comptype_name']=add_slash($_REQUEST['comptype_name']);
			$db->update_from_array($update_array, 'common_customer_company_types', 'comptype_id', $_REQUEST['comptype_id']);
			$alert = '<center><font color="red"><b>Successfully Updated</b></font><br>';
			echo $alert;
			?>
			<br /><a href="home.php?request=cust_comptype&comptypename=<?=$_REQUEST['comptypename']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Listing page</a><br /><br />
			<a href="home.php?request=cust_comptype&fpurpose=edit&comptype_id=<?=$_REQUEST['comptype_id']?>&comptypename=<?=$_REQUEST['comptypename']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Edit this Company Type</a><br /><br />
			<a href="home.php?request=cust_comptype&fpurpose=add&comptypename=<?=$_REQUEST['comptypename']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Add new Company Type</a></center>
			<?php
			
		} else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/customer_companytypes/edit_companytypes.php");
		}
		
	}
}
elseif ($_REQUEST['fpurpose'] == 'delete') {
	if($_REQUEST['comptype_id'])
	{
		$alert_del = '';
		$sql_check = $db->value_exists(array('comptype_id' => $_REQUEST['comptype_id']), 'common_customer_company_types');
		if($sql_check)
		{
			//#Delete the client record.
			$db->delete_id($_REQUEST['comptype_id'], 'comptype_id', 'common_customer_company_types');
			//#Search Options
			$alert_del = '<font color="red">Successfully Deleted</font>';
		}
		else
		{
			$alert_del = '<font color="red">Error! No company type exists with this id</font>';
		}
	}
	include("includes/customer_companytypes/list_companytypes.php");
}

?>