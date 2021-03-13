<?php
if($_REQUEST['fpurpose'] == '') {
	include("includes/payment_capture_types/list_payment_capture_types.php");
} else if($_REQUEST['fpurpose'] == 'add') {
	include("includes/payment_capture_types/add_payment_capture_types.php");
} else if($_REQUEST['fpurpose'] == 'edit') {
	include("includes/payment_capture_types/edit_payment_capture_types.php");
} 
	
 else if($_REQUEST['fpurpose'] == 'insert') {
	if($_REQUEST['Submit'])
	{
		$alert = '';
		$fieldRequired = array($_REQUEST['paymentcapture_name'],$_REQUEST['paymentcapture_code']);
		$fieldDescription = array('TPayment capture type Name','TPayment capture type Code');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = $db->value_exists(array('paymentcapture_name' => $_REQUEST['paymentcapture_name']), 'payment_capture_types');
		$sql_checkcode = $db->value_exists(array('paymentcapture_code' => $_REQUEST['paymentcapture_code']), 'payment_capture_types');
		if($sql_checkcode > 0 && $sql_check > 0) {
			$alert = 'Payment type Code,type Name already exists';
		}
		else if($sql_check > 0) {
			$alert = 'Payment type Name already exists';
		}
		elseif($sql_checkcode > 0 ) {
			$alert = 'Payment type Code already exists';
		}
		if(!$alert) {
			$insert_array = array();
			$insert_array['paymentcapture_name']				  = add_slash($_REQUEST['paymentcapture_name']);
			$insert_array['paymentcapture_code']				  = add_slash($_REQUEST['paymentcapture_code']);
		    $insert_array['paymentcapture_order']				 = add_slash($_REQUEST['paymentcapture_order']);
			$db->insert_from_array($insert_array, 'payment_capture_types');
			$insert_id = $db->insert_id();
			$alert = '<center><font color="red"><b>Successfully Inserted</b></font><br>';
			echo $alert;
			?>
			<br /><a href="home.php?request=payment_capture_types&pay_type=<?=$_REQUEST['pay_type']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Payment capture type Listing page</a><br /><br />
			<a href="home.php?request=payment_capture_types&fpurpose=edit&paymentcapture_id=<?=$insert_id?>&pay_type=<?=$_REQUEST['pay_capture_types']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Edit this Payment capture type</a>
			<br />
			<br />
			<a href="home.php?request=payment_capture_types&fpurpose=add&pay_capture_types=<?=$_REQUEST['pay_capture_types']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>">Add a New Payment capture type </a>
			
			</center>
			<?php
			
		} else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/payment_capture_types/add_payment_capture_types.php");
		}
		
	}
} 
 else if($_REQUEST['fpurpose'] == 'update') {
	if($_REQUEST['Submit'])
	{
		$alert = '';
		$fieldRequired = array($_REQUEST['paymentcapture_name'],$_REQUEST['paymentcapture_code']);
		$fieldDescription = array('Payment type capture Name','Payment type capture Code');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		//serverside_validation($fieldRequired, $fieldDescription);
		$sql_check = "SELECT count(*) as cnt FROM payment_capture_types WHERE paymentcapture_name='".add_slash($_REQUEST['paymentcapture_name'])."' AND paymentcapture_id<>".$_REQUEST['paymentcapture_id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
	    $sql_check_code = "SELECT count(*) as cnt FROM payment_capture_types WHERE paymentcapture_code='".add_slash($_REQUEST['paymentcapture_code'])."' AND paymentcapture_id<>".$_REQUEST['paymentcapture_id'];
		$res_check_code = $db->query($sql_check_code);
		$row_check_code = $db->fetch_array($res_check_code);
	
		if($row_check['cnt'] > 0 && $row_check_code['cnt'] > 0 ) {
			$alert = 'Payment capture type Name,Code already exists';
		}
		else if($row_check['cnt'] > 0  ) {
			$alert = 'Payment capture type Name already exists';
		}
		else if( $row_check_code['cnt'] > 0 ) {
			$alert = 'Payment capture type Code already exists';
		}
		if(!$alert) {
			$update_array = array();
			$update_array['paymentcapture_name']				 = add_slash($_REQUEST['paymentcapture_name']);
			$update_array['paymentcapture_code']				 = add_slash($_REQUEST['paymentcapture_code']);
		    $update_array['paymentcapture_order']				 = add_slash($_REQUEST['paymentcapture_order']);

			$db->update_from_array($update_array, 'payment_capture_types', 'paymentcapture_id', $_REQUEST['paymentcapture_id']);
			$alert = '<center><font color="red"><b>Successfully Updated</b></font><br>';
			echo $alert;
			?>
			<br /><a href="home.php?request=payment_capture_types&pay_capture_types=<?=$_REQUEST['pay_capture_types']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Payment capture types Listing page</a><br /><br />
			<a href="home.php?request=payment_capture_types&fpurpose=edit&paymentcapture_id=<?=$_REQUEST['paymentcapture_id']?>&pay_capture_types=<?=$_REQUEST['pay_capture_types']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Edit this Payment capture type</a>
			<br />
			<br />
			<a href="home.php?request=payment_capture_types&fpurpose=add&pay_capture_types=<?=$pay_capture_types?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>">Add a New Payment capture type</a>
			
			</center>
			<?php
			
		} else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/payment_capture_types/edit_payment_capture_types.php");
		}
		
	}
}
 else if($_REQUEST['fpurpose'] == 'save_orders') {
		//serverside_validation($fieldRequired, $fieldDescription);
			foreach($_REQUEST['paymentcapture_order'] as $key => $val){
			$update_array = array();
			$update_array['paymentcapture_order']				 = $_REQUEST['paymentcapture_order'][$key];
			$db->update_from_array($update_array, 'payment_capture_types', 'paymentcapture_id', $key);
		}
		include("includes/payment_capture_types/list_payment_capture_types.php");
}
else if($_REQUEST['fpurpose'] == 'delete') {
	// Check whether this payment capture type is used in any of the sites
	$sql_sites 	        = "SELECT count(sites_site_id) as cnt FROM general_settings_site_paymentcapture_type WHERE payment_capture_types_paymentcapture_id=".$_REQUEST['paymentcapture_id'];
	$ret_sites      	= $db->query($sql_sites);
	list($use_cnt) 		= $db->fetch_array($ret_sites);
	if($use_cnt>0) {
		$error_msg = 'Sorry Delete not possible... This capture type is used by some sites';
	} else {
	// Get all the entries in payment details table related to this payment type
				 		$sql_del = "DELETE FROM payment_capture_types WHERE paymentcapture_id =".$_REQUEST['paymentcapture_id'];
				 		$db->query($sql_del);
						$error_msg = 'Payment Capture type Deleted Successfully';
				 }
include("includes/payment_capture_types/list_payment_capture_types.php");
}

?>