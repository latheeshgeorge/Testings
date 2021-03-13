<?php
if($_REQUEST['fpurpose'] == '') {
	include("includes/payment_types/list_payment_types.php");
} else if($_REQUEST['fpurpose'] == 'add') {
	include("includes/payment_types/add_payment_types.php");
} else if($_REQUEST['fpurpose'] == 'edit') {
	include("includes/payment_types/edit_payment_types.php");
} 
	
 else if($_REQUEST['fpurpose'] == 'insert') {
	if($_REQUEST['Submit'])
	{
		$alert = '';
		$fieldRequired = array($_REQUEST['paytype_name'],$_REQUEST['paytype_code']);
		$fieldDescription = array('Payment type Name','Payment type Code');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = $db->value_exists(array('paytype_name' => $_REQUEST['paytype_name']), 'payment_types');
		if($sql_check > 0) {
			$alert = 'Payment type Name already exists';
		}
		$sql_check = $db->value_exists(array('paytype_code' => $_REQUEST['paytype_code']), 'payment_types');
		if($sql_check > 0) {
			$alert = 'Payment type Code already exists';
		}
		if(!$alert) {
			$insert_array = array();
			$insert_array['paytype_name']				  = add_slash($_REQUEST['paytype_name']);
			$insert_array['paytype_code']				  = add_slash($_REQUEST['paytype_code']);
			$insert_array['paytype_order']				  = add_slash($_REQUEST['paytype_order']);
			$insert_array['paytype_showinvoucher']		 = ($_REQUEST['paytype_showinvoucher'])?1:0;
			$insert_array['paytype_logintouse']			 = ($_REQUEST['paytype_logintouse'])?1:0;
			$insert_array['paytype_showinpayoncredit']	 = ($_REQUEST['paytype_showinpayoncredit'])?1:0;
			$db->insert_from_array($insert_array, 'payment_types');
			$insert_id = $db->insert_id();
			$alert = '<center><font color="red"><b>Successfully Inserted</b></font><br>';
			echo $alert;
			?>
			<br /><a href="home.php?request=payment_types&pay_type=<?=$_REQUEST['pay_type']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Payment type Listing page</a><br /><br />
			<a href="home.php?request=payment_types&fpurpose=edit&paytype_id=<?=$insert_id?>&pay_type=<?=$_REQUEST['pay_type']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Edit this Payment type</a>
			<br />
			<br />
			<a href="home.php?request=payment_types&fpurpose=add&pay_type=<?=$_REQUEST['pay_type']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>">Add a New Payment type </a>
			
			</center>
			<?php
			
		} else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/payment_types/add_payment_types.php");
		}
		
	}
} 
 else if($_REQUEST['fpurpose'] == 'update') {
	if($_REQUEST['Submit'])
	{
		$alert = '';
		$fieldRequired = array($_REQUEST['paytype_name'],$_REQUEST['paytype_code']);
		$fieldDescription = array('Payment type Name','Payment type Code');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = "SELECT count(*) as cnt FROM payment_types WHERE paytype_name='".add_slash($_REQUEST['paytype_name'])."' AND paytype_id<>".$_REQUEST['paytype_id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
	
		if($row_check['cnt'] > 0) {
			$alert = 'Payment type Name already exists';
		}
		if(!$alert) {
			$update_array = array();
			$update_array['paytype_name']				 = add_slash($_REQUEST['paytype_name']);
			$update_array['paytype_code']				 = add_slash($_REQUEST['paytype_code']);
			$update_array['paytype_order']				 = add_slash($_REQUEST['paytype_order']);
			$update_array['paytype_showinvoucher']		 = ($_REQUEST['paytype_showinvoucher'])?1:0;
			$update_array['paytype_logintouse']			 = ($_REQUEST['paytype_logintouse'])?1:0;
			$update_array['paytype_showinpayoncredit']	 = ($_REQUEST['paytype_showinpayoncredit'])?1:0;
			$db->update_from_array($update_array, 'payment_types', 'paytype_id', $_REQUEST['paytype_id']);
			
			// get the domain name
			$sql_dom 		= "SELECT site_id,site_domain 
									FROM 
										sites ";
			$ret_dom 		= $db->query($sql_dom);
			if ($db->num_rows($ret_dom))
			{
				while ($row_dom 		= $db->fetch_array($ret_dom))
				{
					$base_path 	= IMAGE_ROOT_PATH."/".$row_dom['site_domain'];
					create_Tax_Delivery_Paytype_Paymethod_CacheFile($base_path,$row_dom['site_id']);
				}	
			}	
			
			$alert = '<center><font color="red"><b>Successfully Updated</b></font><br>';
			echo $alert;
			?>
			<br /><a href="home.php?request=payment_types&pay_type=<?=$_REQUEST['pay_type']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Payment types Listing page</a><br /><br />
			<a href="home.php?request=payment_types&fpurpose=edit&paytype_id=<?=$_REQUEST['paytype_id']?>&pay_type=<?=$_REQUEST['pay_type']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Edit this Payment type</a>
			<br />
			<br />
			<a href="home.php?request=payment_types&fpurpose=add&pay_type=<?=$pay_type?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>">Add a New Payment type</a>
			
			</center>
			<?php
			
		} else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/payment_types/edit_payment_types.php");
		}
		
	}
}
 else if($_REQUEST['fpurpose'] == 'save_orders') {
		//serverside_validation($fieldRequired, $fieldDescription);
			foreach($_REQUEST['paytype_order'] as $key => $val){
			$update_array = array();
			$update_array['paytype_order']				 = $_REQUEST['paytype_order'][$key];
			$db->update_from_array($update_array, 'payment_types', 'paytype_id', $key);
		}
		// get the domain name
		$sql_dom 		= "SELECTsite_id,site_domain  
								FROM 
									sites ";
		$ret_dom 		= $db->query($sql_dom);
		if ($db->num_rows($ret_dom))
		{
			while ($row_dom 		= $db->fetch_array($ret_dom))
			{
				$base_path 	= IMAGE_ROOT_PATH."/".$row_dom['site_domain'];
				create_Tax_Delivery_Paytype_Paymethod_CacheFile($base_path,$row_dom['site_id']);
			}	
		}	
		include("includes/payment_types/list_payment_types.php");
}
else if($_REQUEST['fpurpose'] == 'delete') {
	// Check whether this payment type is used in any of the sites
	$sql_sites 	        = "SELECT count(sites_site_id) as cnt FROM payment_types_forsites WHERE paytype_id=".$_REQUEST['paytype_id'];
	$ret_sites      	= $db->query($sql_sites);
	list($use_cnt) 		= $db->fetch_array($ret_sites);
	if($use_cnt>0) {
		$error_msg = 'Sorry Delete not possible... This type is used by some sites';
	} else {
		   // Get all the entries in payment details table related to this payment type
				 		$sql_del = "DELETE FROM payment_types WHERE paytype_id =".$_REQUEST['paytype_id'];
				 		$db->query($sql_del);
						$error_msg = 'Payment type Deleted Successfully';
						
						// get the domain name
						$sql_dom 		= "SELECT site_id,site_domain 
												FROM 
													sites ";
						$ret_dom 		= $db->query($sql_dom);
						if ($db->num_rows($ret_dom))
						{
							while ($row_dom 		= $db->fetch_array($ret_dom))
							{
								$base_path 	= IMAGE_ROOT_PATH."/".$row_dom['site_domain'];
								create_Tax_Delivery_Paytype_Paymethod_CacheFile($base_path,$row_dom['site_id']);
							}	
						}	
				 }
include("includes/payment_types/list_payment_types.php");
}

?>