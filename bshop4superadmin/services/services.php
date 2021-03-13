<?php
if($_REQUEST['fpurpose'] == '') {
	include("includes/services/list_services.php");
} else if($_REQUEST['fpurpose'] == 'add') {
	include("includes/services/add_service.php");
} else if($_REQUEST['fpurpose'] == 'edit') {
	if(is_numeric($_REQUEST['service_id']))
	{
		include("includes/services/edit_service.php");
	}
	else
	{
		$alert = '<center><font color="red">Invalid service Id</font></center><br>';
		echo $alert;
		include("includes/services/list_services.php");
	}	
} else if($_REQUEST['fpurpose'] == 'insert') {
	if($_REQUEST['Submit'])
	{
		$alert = '';
		$fieldRequired = array($_REQUEST['service_name']);
		$fieldDescription = array('Service name');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array($_REQUEST['ordering']);
		$fieldNumericDesc = array("Sort order");
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		
		if(!$alert) {
			$insert_array = array();
			$insert_array['service_name'] 	= add_slash($_REQUEST['service_name']);
			$insert_array['ordering'] 		= add_slash($_REQUEST['ordering']);
			$insert_array['hide'] 			= add_slash($_REQUEST['hide']);
			
			$db->insert_from_array($insert_array, 'services');
			$insert_id = $db->insert_id();
			$alert = '<center><font color="red"><b>Successfully Inserted</b></font><br>';
			echo $alert;
			?>
			<br /><a href="home.php?request=services&servicename=<?=$_REQUEST['servicename']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Listing page</a><br /><br />
			<a href="home.php?request=services&fpurpose=edit&service_id=<?=$insert_id?>&servicename=<?=$_REQUEST['servicename']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Edit this Service</a><br /><br />
			<a href="home.php?request=services&fpurpose=add&servicename=<?=$_REQUEST['servicename']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Click here to Add a new Service</a></center>
			<?php
		} else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/services/add_service.php");
		}
		
	}
} else if($_REQUEST['fpurpose'] == 'update') {
	if($_REQUEST['Submit'])
	{
		$alert = '';
		$fieldRequired = array($_REQUEST['service_name']);
		$fieldDescription = array('Service name');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array($_REQUEST['ordering']);
		$fieldNumericDesc = array("Sort order");
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		
		if(!$alert) {
			$update_array = array();
			$update_array['service_name'] 	= add_slash($_REQUEST['service_name']);
			$update_array['ordering'] 		= add_slash($_REQUEST['ordering']);
			$update_array['hide'] 			= add_slash($_REQUEST['hide']);
			$db->update_from_array($update_array, 'services', 'service_id', $_REQUEST['service_id']);
			$alert = '<center><font color="red"><b>Successfully Updated</b></font><br>';
			echo $alert;
			?>
			<br /><a href="home.php?request=services&servicename=<?=$_REQUEST['servicename']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Listing page</a><br /><br />
			<a href="home.php?request=services&fpurpose=edit&service_id=<?=$_REQUEST['service_id']?>&servicename=<?=$_REQUEST['servicename']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Edit this Service</a><br /><br />
			<a href="home.php?request=services&fpurpose=add&servicename=<?=$_REQUEST['servicename']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Click here to Add a new Service</a></center>
			<?php
			
		} else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/services/edit_service.php");
		}
		
	}
}
else if($_REQUEST['fpurpose'] == 'save_services')
{
//print_r($_REQUEST);
foreach($_REQUEST['ordering'] as $key => $val){
	$update_array = array();
	
	$update_array['ordering'] 		= $_REQUEST['ordering'][$key];
	$update_array['hide'] 			= $_REQUEST['hide'][$key];
	$db->update_from_array($update_array, 'services', 'service_id', $key);
}
		
	include("includes/services/list_services.php");

	
}
else if($_REQUEST['fpurpose'] == 'delete')
{
	if($_REQUEST['service_id']) {
		$alert_del = '';
		$sql_check = $db->value_exists(array('service_id' => $_REQUEST['service_id']), 'services');
		if($sql_check) {
			$sql_check_feature=$db->value_exists(array('services_service_id' => $_REQUEST['service_id']), 'features');
			if($sql_check_feature) {
				 $alert_del = '<font color="red">Error! feature exists with this service id</font>';
			}
			else {
				#Delete the service record.
				$db->delete_id($_REQUEST['service_id'], 'service_id', 'services');
				$alert_del = '<font color="red">Successfully Deleted</font>';
			}
		} else {
			$alert_del = '<font color="red">Error! No service exists with this id</font>';
		}
	}
	include("includes/services/list_services.php");
}

?>