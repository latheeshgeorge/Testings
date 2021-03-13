<?php
if($_REQUEST['fpurpose'] == '') {
	include("includes/payment_methods/list_payment_methods.php");
} else if($_REQUEST['fpurpose'] == 'add') {
	include("includes/payment_methods/add_payment_methods.php");
} else if($_REQUEST['fpurpose'] == 'edit') {
	include("includes/payment_methods/edit_payment_methods.php");
} else if($_REQUEST['fpurpose'] == 'edit_layout') {
	include("includes/payment_methods/edit_theme_layouts.php");
} 

else if($_REQUEST['fpurpose'] == 'insert_paymethod_details') { //inserting a payment method details for a payment method
	
	if($_REQUEST['Submit'])
	{
	
		$alert = '';
		$fieldRequired = array($_REQUEST['payment_methods_details_caption'],$_REQUEST['payment_methods_details_key']);
		$fieldDescription = array('Payment Method Details Name','Payment Method Details Key');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
       $sql_check = "SELECT count(*) as cnt FROM payment_methods_details WHERE payment_methods_details_caption='".add_slash($_REQUEST['payment_methods_details_caption'])."' AND payment_methods_paymethod_id=".$_REQUEST['pass_paymethod_id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
	
		if($row_check['cnt'] > 0) {
			$alert = 'Payment Method Details Caption already exists for current theme';
		}
		 $sql_check_key = "SELECT count(*) as key_cnt FROM payment_methods_details WHERE payment_methods_details_key='".add_slash($_REQUEST['payment_methods_details_key'])."' AND payment_methods_paymethod_id=".$_REQUEST['pass_paymethod_id'];
		$res_check_key = $db->query($sql_check_key);
		$row_check_key = $db->fetch_array($res_check_key);
	
		if($row_check_key['key_cnt'] > 0) {
			$alert = 'Payment Method Details Key already exists for current theme';
		}
		
		if(!$alert) {
			$insert_array = array();
			$insert_array['payment_methods_paymethod_id'] = add_slash($_REQUEST['pass_paymethod_id']);
			$insert_array['payment_methods_details_caption'] = add_slash($_REQUEST['payment_methods_details_caption']);	
			$insert_array['payment_methods_details_key'] = add_slash($_REQUEST['payment_methods_details_key']);	
			$insert_array['payment_methods_details_isrequired'] = ($_REQUEST['payment_methods_details_isrequired'])?1:0;
			
			$db->insert_from_array($insert_array, 'payment_methods_details');
			$insert_id = $db->insert_id();
			$alert = '<center><font color="red"><b>Successfully Inserted</b></font><br>';
			echo $alert;
			?>
			<br /><a href="home.php?request=payment_methods&fpurpose=paymethod_details&amp;pass_paymethod_id=<?=$_REQUEST['pass_paymethod_id']?>&amp;pass_pay_method=<?=$_REQUEST['pass_pay_method']?>&amp;pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&amp;pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&amp;pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&amp;pass_pg=<?=$_REQUEST['pass_pg']?>&paymethod_details=<?=$_REQUEST['paymethod_details']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Payment Method Details Listing page</a><br /><br />
			<a href="home.php?request=payment_methods&fpurpose=edit_paymethod_details&payment_method_details_id=<?=$insert_id?>&paymethod_details=<?=$_REQUEST['paymethod_details']?>&amp;pass_paymethod_id=<?=$_REQUEST['pass_paymethod_id']?>&amp;pass_pay_method=<?=$_REQUEST['pass_pay_method']?>&amp;pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&amp;pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&amp;pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&amp;pass_pg=<?=$_REQUEST['pass_pg']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Edit this Payment Method Details</a>
			<br /><br />
			<a href="home.php?request=payment_methods&fpurpose=add_paymethod_details&paymethod_details=<?=$paymethod_details?>&amp;pass_paymethod_id=<?=$_REQUEST['pass_paymethod_id']?>&amp;pass_pay_method=<?=$_REQUEST['pass_pay_method']?>&amp;pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&amp;pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&amp;pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&amp;pass_pg=<?=$_REQUEST['pass_pg']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>">Add a New Payment Method Details</a>
		<?	
		}
		else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/payment_methods/add_paymethod_details.php");
			
		}
		
	}
	
	
} else if($_REQUEST['fpurpose'] == 'insert') {
	if($_REQUEST['Submit'])
	{
		$alert = '';
		$fieldRequired = array($_REQUEST['paymethod_name'],$_REQUEST['paymethod_key']);
		$fieldDescription = array('TPayment Method Name','Payment Method Key');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = $db->value_exists(array('paymethod_name' => $_REQUEST['paymethod_name']), 'payment_methods');
		if($sql_check > 0) {
			$alert = 'Payment Method Name already exists';
		}
		if(!$alert) {
			$insert_array = array();
			$insert_array['paymethod_name']				= add_slash($_REQUEST['paymethod_name']);
			$insert_array['paymethod_key']				= add_slash($_REQUEST['paymethod_key']);
			$insert_array['paymethod_takecarddetails']	= ($_REQUEST['paymethod_takecarddetails'])?1:0;
			$insert_array['paymethod_description']		= add_slash($_REQUEST['paymethod_description']);
			$insert_array['payment_minvalue']			= add_slash($_REQUEST['payment_minvalue']);
			//$insert_array['paymethod_ssl_imagelink']	= add_slash($_REQUEST['paymethod_ssl_imagelink']);
			$insert_array['payment_hide']			 	= ($_REQUEST['paymethod_hide'])?1:0;
			$insert_array['paymethod_showinvoucher']	= ($_REQUEST['paymethod_showinvoucher'])?1:0;
			$insert_array['paymethod_showinsetup']		= ($_REQUEST['paymethod_showinsetup'])?1:0;
			$insert_array['paymethod_secured_req']		= ($_REQUEST['paymethod_secured_req'])?1:0;
			$insert_array['paymethod_showinmobile']		= ($_REQUEST['paymethod_showinmobile'])?1:0;
			$insert_array['paymethod_showinpayoncredit']		= ($_REQUEST['paymethod_showinpayoncredit'])?1:0;

			$db->insert_from_array($insert_array, 'payment_methods');
			$insert_id = $db->insert_id();
			$alert = '<center><font color="red"><b>Successfully Inserted</b></font><br>';
			echo $alert;
			?>
			<br /><a href="home.php?request=payment_methods&pay_method=<?=$_REQUEST['pay_method']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Payment Method Listing page</a><br /><br />
			<a href="home.php?request=payment_methods&fpurpose=edit&paymethod_id=<?=$insert_id?>&pay_method=<?=$_REQUEST['pay_method']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Edit this Payment Method</a>
			<br /><br />
			
			<a href="home.php?request=payment_methods&fpurpose=add_paymethod_details&pass_paymethod_id=<?=$insert_id?>&pass_pay_method=<?=$_REQUEST['pay_method']?>&pass_sort_by=<?=$_REQUEST['sort_by']?>&pass_sort_order=<?=$_REQUEST['sort_order']?>&pass_records_per_page=<?=$_REQUEST['records_per_page']?>&pass_pg=<?=$_REQUEST['pg']?>">Add Details for this Payment Method</a>
			<br /><br />
			<a href="home.php?request=payment_methods&fpurpose=add&pay_method=<?=$_REQUEST['pay_method']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>">Add a New Payment Method </a>
			
			</center>
			<?php
			
		} else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/payment_methods/add_payment_methods.php");
		}
		
	}
} else if($_REQUEST['fpurpose'] == 'update_paymethod_details') {
	if($_REQUEST['Submit'])
	{
		$alert = '';
		$fieldRequired = array($_REQUEST['payment_methods_details_caption'],$_REQUEST['payment_methods_details_key']);
		$fieldDescription = array('Payment Method Details Caption','Payment Method Details key');
		
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = "SELECT count(*) as cnt FROM payment_methods_details WHERE payment_methods_details_caption='".add_slash($_REQUEST['payment_methods_details_caption'])."' AND payment_methods_paymethod_id=".$_REQUEST['pass_paymethod_id'] . " AND payment_method_details_id != ".$_REQUEST['payment_method_details_id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
	
		if($row_check['cnt'] > 0) {
			$alert = 'Payment Method Details Caption already exists for current Payment Method';
		}
		$sql_check_key = "SELECT count(*) as key_cnt FROM payment_methods_details WHERE payment_methods_details_key='".add_slash($_REQUEST['payment_methods_details_key'])."' AND payment_methods_paymethod_id=".$_REQUEST['pass_paymethod_id'] . " AND payment_method_details_id != ".$_REQUEST['payment_method_details_id'];
		$res_check_key = $db->query($sql_check_key);
		$row_check_key = $db->fetch_array($res_check_key);
	
		if($row_check_key['key_cnt'] > 0) {
			$alert = 'Payment Method Details Key already exists for current Payment Method';
		}
		if(!$alert) {
		$update_array = array();
		$update_array['payment_methods_paymethod_id']	    = add_slash($_REQUEST['pass_paymethod_id']);
		$update_array['payment_methods_details_caption']	= add_slash($_REQUEST['payment_methods_details_caption']);
		$update_array['payment_methods_details_key']	    = add_slash($_REQUEST['payment_methods_details_key']);
		$update_array['payment_methods_details_isrequired']	= ($_REQUEST['payment_methods_details_isrequired'])?1:0;
		
		$db->update_from_array($update_array, 'payment_methods_details', 'payment_method_details_id', $_REQUEST['payment_method_details_id']);
		$alert = '<center><font color="red"><b>Successfully Updated</b></font><br>';
		echo $alert;?>
		<br /><a href="home.php?request=payment_methods&fpurpose=paymethod_details&amp;pass_paymethod_id=<?=$_REQUEST['pass_paymethod_id']?>&amp;pass_pay_method=<?=$_REQUEST['pass_pay_method']?>&amp;pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&amp;pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&amp;pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&amp;pass_pg=<?=$_REQUEST['pass_pg']?>&paymethod_details=<?=$_REQUEST['paymethod_details']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Payment Method Details Listing page</a><br /><br />
			<a href="home.php?request=payment_methods&fpurpose=edit_paymethod_details&payment_method_details_id=<?=$_REQUEST['payment_method_details_id']?>&paymethod_details=<?=$_REQUEST['paymethod_details']?>&amp;pass_paymethod_id=<?=$_REQUEST['pass_paymethod_id']?>&amp;pass_pay_method=<?=$_REQUEST['pass_pay_method']?>&amp;pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&amp;pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&amp;pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&amp;pass_pg=<?=$_REQUEST['pass_pg']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Edit this Payment Method Details</a>
			<br /><br />
			<a href="home.php?request=payment_methods&amp;paymethod_details<?=$_REQUEST['paymethod_details']?>&fpurpose=add_paymethod_details&paymethod_details=<?=$paymethod_details?>&amp;pass_paymethod_id=<?=$_REQUEST['pass_paymethod_id']?>&amp;pass_pay_method=<?=$_REQUEST['pass_pay_method']?>&amp;pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&amp;pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&amp;pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&amp;pass_pg=<?=$_REQUEST['pass_pg']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>">Add a New Payment Method Details</a>
			<br /><br />
			<a href="home.php?request=payment_methods&amp;paymethod_details<?=$_REQUEST['paymethod_details']?>&paymethod_details=<?=$paymethod_details?>&amp;paymethod_id=<?=$_REQUEST['pass_paymethod_id']?>&amp;pay_method=<?=$_REQUEST['pass_pay_method']?>&amp;sort_by=<?=$_REQUEST['pass_sort_by']?>&amp;sort_order=<?=$_REQUEST['pass_sort_order']?>&amp;records_per_page=<?=$_REQUEST['pass_records_per_page']?>&amp;pg=<?=$_REQUEST['pass_pg']?>">Go Back to List Payment Methods</a>
			</center>
		
		<? 
		}else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/payment_methods/edit_paymethod_details.php");
		}
		
	}
} else if($_REQUEST['fpurpose'] == 'update') {
	if($_REQUEST['Submit'])
	{
		$alert = '';
		$fieldRequired = array($_REQUEST['paymethod_name'],$_REQUEST['paymethod_key']);
		$fieldDescription = array('Payment Method Name','Payment Method Key');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = "SELECT count(*) as cnt FROM payment_methods WHERE paymethod_name='".add_slash($_REQUEST['paymethod_name'])."' AND paymethod_id<>".$_REQUEST['paymethod_id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
	
		if($row_check['cnt'] > 0) {
			$alert = 'Payment Method Name already exists';
		}
		if(!$alert) {
			$update_array = array();
			$update_array['paymethod_name']				= add_slash($_REQUEST['paymethod_name']);
			$update_array['paymethod_key']					= add_slash($_REQUEST['paymethod_key']);
			$update_array['paymethod_description']			= add_slash($_REQUEST['paymethod_description']);
			$update_array['paymethod_takecarddetails']	= ($_REQUEST['paymethod_takecarddetails'])?1:0;
			$update_array['payment_minvalue']				= add_slash($_REQUEST['payment_minvalue']);
			//$update_array['paymethod_ssl_imagelink']	= add_slash($_REQUEST['paymethod_ssl_imagelink']);
			$update_array['payment_hide']			    		= ($_REQUEST['paymethod_hide'])?1:0; 
			$update_array['paymethod_showinvoucher']	= ($_REQUEST['paymethod_showinvoucher'])?1:0;
			$update_array['paymethod_showinsetup']		= ($_REQUEST['paymethod_showinsetup'])?1:0;
			$update_array['paymethod_secured_req']		= ($_REQUEST['paymethod_secured_req'])?1:0;
			$update_array['paymethod_showinmobile']		= ($_REQUEST['paymethod_showinmobile'])?1:0;
			$update_array['paymethod_showinpayoncredit']		= ($_REQUEST['paymethod_showinpayoncredit'])?1:0;

			$db->update_from_array($update_array, 'payment_methods', 'paymethod_id', $_REQUEST['paymethod_id']);
			
			
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
			<br /><a href="home.php?request=payment_methods&pay_method=<?=$_REQUEST['pay_method']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Payment Methods Listing page</a><br /><br />
			<a href="home.php?request=payment_methods&fpurpose=edit&paymethod_id=<?=$_REQUEST['paymethod_id']?>&pay_method=<?=$_REQUEST['pay_method']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Edit this Payment Method</a>
			<br />
			<br />
			<a href="home.php?request=payment_methods&fpurpose=add&pay_method=<?=$pay_method?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>">Add a New Payment Method</a>
			
			</center>
			<?php
			
		} else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/payment_methods/edit_payment_methods.php");
		}
		
	}
}
else if($_REQUEST['fpurpose'] == 'delete') {
	// Check whether this payment Method is used in any of the sites
	$sql_sites 	        = "SELECT count(sites_site_id) as cnt FROM payment_methods_forsites WHERE payment_methods_paymethod_id=".$_REQUEST['paymethod_id'];
	$ret_sites      	= $db->query($sql_sites);
	list($use_cnt) 		= $db->fetch_array($ret_sites);
	if($use_cnt>0) {
		$error_msg = 'Sorry Delete not possible... This Method is used by some sites';
	} else {
		   // Get all the entries in payment details table related to this payment method
		   $sql_payment_details = "SELECT count(payment_methods_paymethod_id) as cnt FROM payment_methods_details WHERE payment_methods_paymethod_id=".$_REQUEST['paymethod_id'];
		   $ret_payment_details = $db->query($sql_payment_details);
		   list($use_cnt) 		= $db->fetch_array($ret_payment_details);
				 if($use_cnt>0) {
				 $error_msg = 'Sorry Delete not possible... This Method has some details assigned';
				 }else{
				 		$sql_del = "DELETE FROM payment_methods WHERE paymethod_id =".$_REQUEST['paymethod_id'];
				 		$db->query($sql_del);
						$error_msg = 'Payment Method Deleted Successfully';
						
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
				 
			}
include("includes/payment_methods/list_payment_methods.php");
}
elseif($_REQUEST['fpurpose']=='delete_paymethod_details'){//deleteing a payment Method detail
// Check whether this payment Method is used in any of the sites
	$sql_sites 	        = "SELECT count(sites_site_id) as cnt FROM payment_methods_forsites_details WHERE payment_methods_details_payment_method_details_id=".$_REQUEST['payment_method_details_id'];
	$ret_sites      	= $db->query($sql_sites);
	list($use_cnt) 		= $db->fetch_array($ret_sites);
	if($use_cnt>0) {
		$error_msg = 'Sorry Delete not possible... This Payment Method Details is used by some sites';
	} else {
		  	$sql_del = "DELETE FROM payment_methods_details WHERE payment_method_details_id =".$_REQUEST['payment_method_details_id'];
	 		$db->query($sql_del);
			$error_msg = 'Payment Method Detail Deleted Successfully';
				 }
include("includes/payment_methods/list_payment_methods_details.php");
}
elseif($_REQUEST['fpurpose']=='paymethod_details')
{
	include("includes/payment_methods/list_payment_methods_details.php");
}
elseif($_REQUEST['fpurpose']=='add_paymethod_details')
{
	include("includes/payment_methods/add_paymethod_details.php");
}
elseif($_REQUEST['fpurpose']=='edit_paymethod_details')
{
	include("includes/payment_methods/edit_paymethod_details.php");
}
?>