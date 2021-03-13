<?php
if($_REQUEST['fpurpose'] == '') {
	include("includes/country/list_countries.php");
} else if($_REQUEST['fpurpose'] == 'add') {
	include("includes/country/add_country.php");
} else if($_REQUEST['fpurpose'] == 'edit') {
	if(is_numeric($_REQUEST['country_id']))
	{
		include("includes/country/edit_country.php");
	}
	else
	{
		$alert = '<center><font color="red">Invalid country Id</font></center><br>';
		echo $alert;
		include("includes/country/list_countries.php");
	}	
} else if($_REQUEST['fpurpose'] == 'insert') {
	if($_REQUEST['Submit'])
	{
		$alert = '';
		$fieldRequired = array($_REQUEST['country_name'],$_REQUEST['country_code'],$_REQUEST['numeric_code']);
		$fieldDescription = array('Country Name','Country Code','Numeric Code');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = $db->value_exists(array('country_name' => $_REQUEST['country_name']), 'common_country');
		if($sql_check > 0) {
			$alert = 'Country Name already exists';
		}
		if(!$alert) {
			// Checking for duplicate country names
			
			$insert_array = array();
			$insert_array['country_name']			= add_slash($_REQUEST['country_name']); 
			$insert_array['country_numeric_code']	= add_slash($_REQUEST['numeric_code']); 
			$insert_array['country_code']			= add_slash($_REQUEST['country_code']);

			$db->insert_from_array($insert_array, 'common_country');
			$insert_id = $db->insert_id();
			$alert = '<center><font color="red"><b>Successfully Inserted</b></font><br>';
			echo $alert;
			?>
			<br /><a href="home.php?request=country&countryname=<?=$_REQUEST['countryname']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Listing page</a><br /><br />
			<a href="home.php?request=country&fpurpose=edit&country_id=<?=$insert_id?>&countryname=<?=$_REQUEST['countryname']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Edit this country</a><br /><br />
			<a href="home.php?request=country&fpurpose=add&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Add new Country</a></center>
			<?php
			
		} else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/country/add_country.php");
		}
		
	}
} else if($_REQUEST['fpurpose'] == 'update') {
	if($_REQUEST['Submit'])
	{
		$alert = '';
		$fieldRequired = array($_REQUEST['country_name'],$_REQUEST['country_code'],$_REQUEST['numeric_code']);
		$fieldDescription = array('Country Name','Country Code','Numeric Code');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = "SELECT count(*) as cnt FROM common_country WHERE country_name='".$_REQUEST['country_name']."' AND country_id<>".$_REQUEST['country_id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
	
		if($row_check['cnt'] > 0) {
			$alert = 'Country Name already exists';
		}
		if(!$alert) {
			$update_array = array();
			$update_array['country_name']			= add_slash($_REQUEST['country_name']);
			$update_array['country_numeric_code']	= add_slash($_REQUEST['numeric_code']);
			$update_array['country_code']			= add_slash($_REQUEST['country_code']);
			$db->update_from_array($update_array, 'common_country', 'country_id', $_REQUEST['country_id']);
			$alert = '<center><font color="red"><b>Successfully Updated</b></font><br>';
			echo $alert;
			?>
			<br /><a href="home.php?request=country&countryname=<?=$_REQUEST['countryname']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Listing page</a><br /><br />
			<a href="home.php?request=country&fpurpose=edit&country_id=<?=$_REQUEST['country_id']?>&countryname=<?=$_REQUEST['countryname']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Edit this country</a><br /><br />
			<a href="home.php?request=country&fpurpose=add&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Add new country</a></center>
			<?php
			
		} else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/country/edit_country.php");
		}
		
	}
}
elseif ($_REQUEST['fpurpose'] == 'delete') {
	if($_REQUEST['country_id']) {
		$alert_del = '';
		$sql_check = $db->value_exists(array('country_id' => $_REQUEST['country_id']), 'common_country');
		if($sql_check) {
			#Delete the client record.
			$db->delete_id($_REQUEST['country_id'], 'country_id', 'common_country');
			#Search Options
			$alert_del = '<font color="red">Successfully Deleted</font>';
				
			
		} else {
			$alert_del = '<font color="red">Error! No country exists with this id</font>';
		}
	}
	include("includes/country/list_countries.php");
}

?>