<?php
if($_REQUEST['fpurpose'] == '') {
	include("includes/currency/list_currencies.php");
} else if($_REQUEST['fpurpose'] == 'add') {
	include("includes/currency/add_currency.php");
} else if($_REQUEST['fpurpose'] == 'edit') {
	if(is_numeric($_REQUEST['currency_id']))
	{
		include("includes/currency/edit_currency.php");
	}
	else
	{
		$alert = '<center><font color="red">Invalid currency Id</font></center><br>';
		echo $alert;
		include("includes/currency/list_currencies.php");
	}	
} else if($_REQUEST['fpurpose'] == 'insert') {
	if($_REQUEST['Submit'])
	{
		$alert = '';
		$fieldRequired = array($_REQUEST['curr_name'],$_REQUEST['curr_sign_char'],$_REQUEST['curr_code'], $_REQUEST['num_curr_code']);
		$fieldDescription = array('Currency Name','Currency sign','Currency Code','Numeric Code');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array($_REQUEST['num_curr_code']);
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = $db->value_exists(array('curr_name' => $_REQUEST['curr_name']), 'common_currency');
		if($sql_check > 0) {
			$alert = 'Currency Name already exists';
		}
		if(!$alert) {
			$insert_array = array();
			$insert_array['curr_name']=add_slash($_REQUEST['curr_name']);
			$insert_array['curr_sign']=add_slash($_REQUEST['curr_sign']);
			$insert_array['curr_sign_char']=add_slash($_REQUEST['curr_sign_char']);
			$insert_array['curr_code']=add_slash($_REQUEST['curr_code']);
			$insert_array['curr_numeric_code']=add_slash($_REQUEST['num_curr_code']);

			$db->insert_from_array($insert_array, 'common_currency');
			$insert_id = $db->insert_id();
			$alert = '<center><font color="red"><b>Successfully Inserted</b></font><br>';
			echo $alert;
			?>
			<br /><a href="home.php?request=currency&currency_name=<?=$_REQUEST['currency_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Listing page</a><br /><br />
			<a href="home.php?request=currency&fpurpose=edit&currency_id=<?=$insert_id?>&currency_name=<?=$_REQUEST['currency_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Edit this Currency</a>
			<br /><br />
			<a href="home.php?request=currency&fpurpose=add&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Add new Currency</a>
			</center>
			<?php
			
		} else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/currency/add_currency.php");
		}
		
	}
} else if($_REQUEST['fpurpose'] == 'update') {
	if($_REQUEST['Submit'])
	{
		$alert = '';
		$fieldRequired = array($_REQUEST['curr_name'],$_REQUEST['curr_sign_char'],$_REQUEST['curr_code'], $_REQUEST['num_curr_code']);
		$fieldDescription = array('Currency Name','Currency sign','Currency Code','Numeric Code');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array($_REQUEST['num_curr_code']);
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = "SELECT count(*) as cnt FROM common_currency WHERE curr_name='".$_REQUEST['curr_name']."' AND currency_id<>".$_REQUEST['currency_id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
	
		if($row_check['cnt'] > 0) {
			$alert = 'Currency Name already exists';
		}
		if(!$alert) {
			$update_array = array();
			$update_array['curr_name']=add_slash($_REQUEST['curr_name']);
			$update_array['curr_sign']=add_slash($_REQUEST['curr_sign']);
			$update_array['curr_sign_char']=add_slash($_REQUEST['curr_sign_char']);
			$update_array['curr_code']=add_slash($_REQUEST['curr_code']);
			$update_array['curr_numeric_code']=add_slash($_REQUEST['num_curr_code']);
			$db->update_from_array($update_array, 'common_currency', 'currency_id', $_REQUEST['currency_id']);
			$alert = '<center><font color="red"><b>Successfully Updated</b></font><br>';
			echo $alert;
			?>
			<br /><a href="home.php?request=currency&currency_name=<?=$_REQUEST['currency_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Listing page</a><br /><br />
			<a href="home.php?request=currency&fpurpose=edit&currency_id=<?=$_REQUEST['currency_id']?>&currency_name=<?=$_REQUEST['currency_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Edit this currency</a>
			<br /><br />
			<a href="home.php?request=currency&fpurpose=add&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Add new currency</a>
			</center>
			<?php
			
		} else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/currency/edit_currency.php");
		}
		
	}
}

elseif ($_REQUEST['fpurpose'] == 'delete') {
	if($_REQUEST['currency_id']) {
		$alert_del = '';
		$sql_check = $db->value_exists(array('currency_id' => $_REQUEST['currency_id']), 'common_currency');
		if($sql_check) {
			#Delete the client record.
			$db->delete_id($_REQUEST['currency_id'], 'currency_id', 'common_currency');
			#Search Options
			$alert_del = '<font color="red">Successfully Deleted</font>';
				
			
		} else {
			$alert_del = '<font color="red">Error! No currency exists with this id</font>';
		}
	}
	include("includes/currency/list_currencies.php");
}
?>