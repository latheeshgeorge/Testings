<?php
if($_REQUEST['fpurpose'] == '') {
	include("includes/clients/list_clients.php");
} else if($_REQUEST['fpurpose'] == 'add') {
	include("includes/clients/add_client.php");
} else if($_REQUEST['fpurpose'] == 'edit') {
	if(is_numeric($_REQUEST['client_id']))
	{
		include("includes/clients/edit_client.php");
	}
	else
	{
		$alert = '<center><font color="red">Invalid client Id</font></center><br>';
		echo $alert;
		include("includes/clients/list_clients.php");
	}	
} else if($_REQUEST['fpurpose'] == 'insert') {
	if($_REQUEST['Submit'])
	{
		$alert = '';
		$fieldRequired = array($_REQUEST['client_fname'],$_REQUEST['client_lname'],$_REQUEST['client_company'],$_REQUEST['client_email'],$_REQUEST['client_phone']);
		$fieldDescription = array('First Name','Last Name','Company','Email','Phone');
		$fieldEmail = array($_REQUEST['client_email']);
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = $db->value_exists(array('client_email' => $_REQUEST['client_email']), 'clients');
		if($sql_check > 0) {
			$alert = 'Client Email address already exists';
		}
		if(!$alert) {
			$insert_array = array();
			/*foreach($_REQUEST as $k => $v) {
				if($k != 'Submit' && $k != 'PHPSESSID' && $k != 'records_per_page' && $k != 'pg' && $k != 'client_name' && $k != 'client_company' && $k != 'sort_by' && $k != 'sort_order' && $k != 'fpurpose' && $k != 'ZDEDebuggerPresent' && $k != 'request') {
					$insert_array[$k] = add_slash($v);
				}
			}*/
			$insert_array['client_fname'] = add_slash($_REQUEST['client_fname']);
			$insert_array['client_lname'] = add_slash($_REQUEST['client_lname']);
			$insert_array['client_company'] = add_slash($_REQUEST['client_company']);
			$insert_array['client_address'] = add_slash($_REQUEST['client_address']);
			$insert_array['client_email'] = add_slash($_REQUEST['client_email']);
			$insert_array['client_postcode'] = add_slash($_REQUEST['client_postcode']);
			$insert_array['client_phone'] = add_slash($_REQUEST['client_phone']);
			$insert_array['client_mobile'] = add_slash($_REQUEST['client_mobile']);
			$insert_array['client_fax'] = add_slash($_REQUEST['client_fax']);
			if($_REQUEST['cbo_country']) {
				$insert_array['client_country_id'] = add_slash($_REQUEST['cbo_country']);
				
			} else {
				$insert_array['client_country_id'] = '0';
			}
			if($_REQUEST['cbo_state']) {
					$insert_array['client_state'] = add_slash($_REQUEST['cbo_state']);
					} 
			$db->insert_from_array($insert_array, 'clients');
			$insert_id = $db->insert_id();
			$alert = '<center><font color="red"><b>Successfully Inserted</b></font><br>';
			echo $alert;
			?>
			<br /><a href="home.php?request=clients&client_name=<?=$_REQUEST['client_name']?>&company=<?=$_REQUEST['company']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Listing page</a><br /><br />
			<a href="home.php?request=clients&fpurpose=edit&client_id=<?=$insert_id?>&client_name=<?=$_REQUEST['client_name']?>&company=<?=$_REQUEST['company']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Edit this Client</a>
			<br /><br />
			<a href="home.php?request=clients&fpurpose=add&client_name=<?=$_REQUEST['client_name']?>&company=<?=$_REQUEST['company']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Add New Client</a></center>
			<?php
			
		} else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/clients/add_client.php");
		}
	}
} else if($_REQUEST['fpurpose'] == 'update') {
	if($_REQUEST['Submit'])
	{
		$alert = '';
		$fieldRequired = array($_REQUEST['client_fname'],$_REQUEST['client_lname'],$_REQUEST['client_company'],$_REQUEST['client_email'],$_REQUEST['client_phone']);
		$fieldDescription = array('First Name','Last Name','Company','Email','Phone');
		$fieldEmail = array($_REQUEST['client_email']);
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = "SELECT count(*) as cnt FROM clients WHERE client_email='".$_REQUEST['client_email']."' AND client_id<>".add_slash($_REQUEST['client_id']);
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
	
		if($row_check['cnt'] > 0) {
			$alert = 'Client Email address already exists';
		}
		if(!$alert) {
			$update_array = array();
			/*foreach($_REQUEST as $k => $v) {
				if($k != 'Submit' && $k != 'PHPSESSID' && $k != 'records_per_page' && $k != 'pg' && $k != 'client_name' && $k != 'company' && $k != 'sort_by' && $k != 'sort_order' && $k != 'fpurpose' && $k != 'ZDEDebuggerPresent' && $k != 'request' && $k != 'client_id') {
					$update_array[$k] = add_slash($v);
				}
			}*/
			$update_array['client_fname'] = add_slash($_REQUEST['client_fname']);
			$update_array['client_lname'] = add_slash($_REQUEST['client_lname']);
			$update_array['client_company'] = add_slash($_REQUEST['client_company']);
			$update_array['client_address'] = add_slash($_REQUEST['client_address']);
			$update_array['client_email'] = add_slash($_REQUEST['client_email']);
			$update_array['client_postcode'] = add_slash($_REQUEST['client_postcode']);
			$update_array['client_phone'] = add_slash($_REQUEST['client_phone']);
			$update_array['client_mobile'] = add_slash($_REQUEST['client_mobile']);
			$update_array['client_fax'] = add_slash($_REQUEST['client_fax']);
			if($_REQUEST['cbo_country']) {
				$update_array['client_country_id'] = add_slash($_REQUEST['cbo_country']);
			} else {
				$update_array['client_country_id'] = '0';
			}
			if($_REQUEST['cbo_state']) {
					$update_array['client_state'] = add_slash($_REQUEST['cbo_state']);
					} 
			$db->update_from_array($update_array, 'clients', 'client_id', $_REQUEST['client_id']);
			$alert = '<center><font color="red"><b>Successfully Updated</b></font><br>';
			echo $alert;
			?>
			<br /><a href="home.php?request=clients&client_name=<?=$_REQUEST['client_name']?>&company=<?=$_REQUEST['company']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Listing page</a><br /><br />
			<a href="home.php?request=clients&fpurpose=edit&client_id=<?=$_REQUEST['client_id']?>&client_name=<?=$_REQUEST['client_name']?>&company=<?=$_REQUEST['company']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Edit this Client</a>
			<br /><br />
			<a href="home.php?request=clients&fpurpose=add&client_name=<?=$_REQUEST['client_name']?>&company=<?=$_REQUEST['company']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Add New Client</a></center>
			
			<?php
			
		} else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/clients/edit_client.php");
		}
		
	}
} elseif ($_REQUEST['fpurpose'] == 'delete') {
	if($_REQUEST['client_id']) {
		$alert_del = '';
		$sql_check = $db->value_exists(array('client_id' => $_REQUEST['client_id']), 'clients');
		if($sql_check) {
			//#Check whether sites exists under this client
			$sites_check = $db->value_exists(array('clients_client_id' => $_REQUEST['client_id']), 'sites');
			
			if($sites_check == 0) {
				//#Delete the client record.
				$db->delete_id($_REQUEST['client_id'], 'client_id', 'clients');
				//#Search Options
					
				$alert_del = '<font color="red">Successfully Deleted</font>';
				
			} else {
				$alert_del = '<font color="red">Error! Can\'t delete because sites exists under this client</font>';
			}
		} else {
			$alert_del = '<font color="red">Error! No client exists with this id</font>';
		}
	}
	include("includes/clients/list_clients.php");
}
?>