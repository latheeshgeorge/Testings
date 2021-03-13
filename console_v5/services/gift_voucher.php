<?php
	
	if($_REQUEST['fpurpose']=='')
	{
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include "includes/gift_voucher/ajax/gift_voucher_ajax_functions.php";
		include ('includes/gift_voucher/list_giftvouchers.php');
	}
	elseif($_REQUEST['fpurpose']=='add')
	{	$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include ('includes/gift_voucher/add_giftvouchers.php');
	}
	elseif($_REQUEST['fpurpose']=='save_add')
	{
		$alert='';
		$fieldRequired 		= array($_REQUEST['voucher_value'],$_REQUEST['voucher_boughton'],$_REQUEST['voucher_expireson']);
		$fieldDescription 	= array('Voucher Value','Select Start Date','Select End Date');
		$fieldEmail 		= array();
		$fieldConfirm 		= array();
		$fieldConfirmDesc 	= array();
		$fieldNumeric 		= array($_REQUEST['voucher_value'],$_REQUEST['voucher_max_usage']);
		$fieldNumericDesc	= array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		if(!$alert)
			{

			if(!is_valid_date($_REQUEST['voucher_boughton'],'normal','-') or !is_valid_date($_REQUEST['voucher_expireson'],'normal','-'))
				$alert = 'Sorry!! Start or End Date is Invalid';

			}
		if ($alert=='')
		{
		 	$voucher_num 	= get_UniqueVoucherNumber();//sprintf("%08d%08d", mt_rand(0, 99999999), mt_rand(0, 99999999));
			$startdate_arr	= explode("-",trim($_REQUEST['voucher_boughton']));
			$startdate		= $startdate_arr[2].'-'.$startdate_arr[1].'-'.$startdate_arr[0];
			$enddate_arr	= explode("-",trim($_REQUEST['voucher_expireson']));
			$enddate		= $enddate_arr[2].'-'.$enddate_arr[1].'-'.$enddate_arr[0];
			/*//check whether the voucher number is existing in promotional code
			$sql_check = "SELECT code_id FROM promotional_code WHERE sites_site_id=$ecom_siteid AND code_number ='".addslashes(trim($voucher_num))."'";
			$ret_check = $db->query($sql_check);
			if ($db->num_rows($ret_check))
			{
				$voucher_num = sprintf("%08d%08d", mt_rand(0, 99999999), mt_rand(0, 99999999));
			}*/
			// Check whether the dates as valid
			if(!is_numeric($startdate_arr[0]) or !is_numeric($startdate_arr[1]) or !is_numeric($startdate_arr[2]) or !is_numeric($enddate_arr[0]) or !is_numeric($enddate_arr[1]) or !is_numeric($enddate_arr[2]))
			{
				$alert = 'Sorry!! Invalid Start or End Date';
				include ('includes/gift_voucher/add_giftvouchers.php');
			}elseif(!checkdate($startdate_arr[1],$startdate_arr[0],$startdate_arr[2]) or !checkdate($enddate_arr[1],$enddate_arr[0],$enddate_arr[2]))
			{
				$alert = 'Sorry!! Invalid Start or End Date';
				include ('includes/gift_voucher/add_giftvouchers.php');
			}
			else
			{
	$sql_curr = "SELECT curr_sign_char,curr_rate,curr_margin,curr_code,curr_numeric_code FROM general_settings_site_currency WHERE
				sites_site_id=$ecom_siteid AND curr_default=1";
	$ret_curr = $db->query($sql_curr);
	if ($db->num_rows($ret_curr))
	{
		$row_curr 		= $db->fetch_array($ret_curr);
		$curr			= $row_curr['curr_sign_char'];
		$curr_rate 		= $row_curr['curr_rate'];
		$curr_code		= $row_curr['curr_code'];
		$curr_numeric	= $row_curr['curr_numeric_code'];
	}
				$insert_array								= array();
				$insert_array['sites_site_id']				= $ecom_siteid;
				$insert_array['voucher_number']				= $voucher_num;
				$insert_array['voucher_boughton']			= 'curdate()';
				$insert_array['voucher_activatedon']		= $startdate;
				$insert_array['voucher_expireson']			= $enddate; 
				$insert_array['voucher_type']				= trim($_REQUEST['voucher_type']);
				$insert_array['voucher_value']				= trim($_REQUEST['voucher_value']);
				$insert_array['voucher_max_usage']			= trim($_REQUEST['voucher_max_usage']);
				$insert_array['voucher_login_touse']		= ($_REQUEST['voucher_login_touse'])?1:0;
				$insert_array['voucher_freedelivery']		= ($_REQUEST['voucher_freedelivery'])?1:0;
				$insert_array['voucher_paystatus']			= 'Paid';
				$insert_array['voucher_createdby']			= 'A';
				$insert_array['voucher_curr_symbol']		= $curr;	
				$insert_array['voucher_curr_rate']			= $curr_rate;	
				$insert_array['voucher_curr_code']			= $curr_code;	
				$insert_array['voucher_curr_numeric_code']	= $curr_numeric;	
				$insert_array['voucher_hide']				= ($_REQUEST['voucher_hide'])?1:0;
				$insert_array['voucher_apply_direct_discount_also']			= ($_REQUEST['voucher_apply_direct_discount_also'])?'Y':'N';
				$insert_array['voucher_apply_custgroup_discount_also']		= ($_REQUEST['voucher_apply_custgroup_discount_also'])?'Y':'N';
				$insert_array['voucher_apply_direct_product_discount_also']	= ($_REQUEST['voucher_apply_direct_product_discount_also'])?'Y':'N';
				$db->insert_from_array($insert_array,'gift_vouchers');
				$insert_id = $db->insert_id();
				$sql = "SELECT voucher_number FROM gift_vouchers WHERE voucher_id='".$insert_id."'";
				$res = $db->query($sql);
				$row = $db->fetch_array($res);
				$alert .= '<br><span class="redtext"><b>Gift voucher Created Successfully</b></span><br><br>
							<span class="redtext"><b>New Gift voucher Number :
							'. $row[voucher_number] .'</b></span><br>';
				echo $alert;
		?>
				<br />
				<a class="smalllink" href="home.php?request=gift_voucher&vouchernumber=<?=$_REQUEST['vouchernumber']?>&paystatus=<?=$_REQUEST['paystatus']?>&addedby=<?=$_REQUEST['addedby']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Gift Voucher Listing page</a><br />
				 <br />
				<a class="smalllink" href="home.php?request=gift_voucher&fpurpose=edit&checkbox[0]=<?php echo $insert_id?>&vouchernumber=<?=$_REQUEST['vouchernumber']?>&paystatus=<?=$_REQUEST['paystatus']?>&addedby=<?=$_REQUEST['addedby']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Edit Gift Voucher Page </a><br />
				<br />
				<a class="smalllink" href="home.php?request=gift_voucher&fpurpose=add&vouchernumber=<?=$_REQUEST['vouchernumber']?>&paystatus=<?=$_REQUEST['paystatus']?>&addedby=<?=$_REQUEST['addedby']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Add New Gift Voucher Page </a>
			   
			<?
			}
		}
		else
		{
		 $ajax_return_function = 'ajax_return_contents';
				include "ajax/ajax.php";
				$edit_id = $_REQUEST['checkbox'][0];
			include ('includes/gift_voucher/add_giftvouchers.php');
		}
	}
	elseif($_REQUEST['fpurpose'] == 'delete')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Gift Vouchers not selected';
		}
		else
		{
			$del_arr 			= explode("~",$_REQUEST['del_ids']);
			$atleast_one		= false;
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					$proceed = true;
					// Check whether the payment status of current gift voucher
					$sql_vouch = "SELECT voucher_paystatus,voucher_createdby 
												FROM 
													gift_vouchers 
												WHERE 
													voucher_id = ".$del_arr[$i]." 
													AND sites_site_id = $ecom_siteid 
												LIMIT 
													1";
					$ret_vouch = $db->query($sql_vouch);
					if ($db->num_rows($ret_vouch))
					{
						$row_vouch = $db->fetch_array($ret_vouch);
						if($row_vouch['voucher_paystatus'] == 'Paid' && $row_vouch['voucher_createdby']=='C')
							$proceed = false;
					}
					if($proceed==true)
					{
						$atleast_one = true;
						$sql_del = "DELETE FROM gift_vouchers_payment WHERE gift_vouchers_voucher_id=".$del_arr[$i];
						$db->query($sql_del);
	
						$sql_del = "DELETE FROM gift_vouchers_cheque_details WHERE gift_vouchers_voucher_id=".$del_arr[$i];
						$db->query($sql_del);
	
						$sql_del = "DELETE FROM gift_vouchers_customer WHERE voucher_id=".$del_arr[$i];
						$db->query($sql_del);
	
						$sql_del = "DELETE FROM gift_voucher_details_authorized_amount WHERE gift_vouchers_voucher_id=".$del_arr[$i];
						$db->query($sql_del);
	
						$sql_del = "DELETE FROM gift_voucher_details_refunded WHERE gift_vouchers_voucher_id=".$del_arr[$i];
						$db->query($sql_del);
	
						$sql_del = "DELETE FROM gift_voucher_emails WHERE gift_vouchers_voucher_id=".$del_arr[$i];
						$db->query($sql_del);
	
						$sql_del = "DELETE FROM gift_voucher_emails_console_send WHERE gift_vouchers_voucher_id=".$del_arr[$i];
						$db->query($sql_del);
	
						$sql_del = "DELETE FROM gift_voucher_notes WHERE gift_vouchers_voucher_id=".$del_arr[$i];
						$db->query($sql_del);
	
						$sql_del = "DELETE FROM gift_voucher_payment_authorizedata WHERE gift_vouchers_voucher_id=".$del_arr[$i];
						$db->query($sql_del);
	
						$sql_del = "DELETE FROM gift_voucher_payment_cleardata WHERE gift_vouchers_voucher_id=".$del_arr[$i];
						$db->query($sql_del);
	
						$sql_del = "DELETE FROM gift_voucher_payment_repeatdata WHERE gift_vouchers_voucher_id=".$del_arr[$i];
						$db->query($sql_del);
	
						$sql_del = "DELETE FROM gift_voucher_refunded WHERE gift_vouchers_voucher_id=".$del_arr[$i];
						$db->query($sql_del);
						
						$sql_del = "DELETE FROM gift_vouchers WHERE voucher_id=".$del_arr[$i];
						$db->query($sql_del);
					}	
					else
						$alert_more = "Gift vouchers which have payment status as 'Paid' cannot be deleted. Please refund the gift voucher value completely to delete such gift vouchers.";
				}
			}
		}
		if ($atleast_one)
			$alert = 'Vouchers Deleted successfully.<br />'.$alert_more;
		else
			$alert = $alert_more;
		include ('../includes/gift_voucher/list_giftvouchers.php');
	}
	elseif($_REQUEST['fpurpose']=='change_hide')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		$giftid_arr 	= explode('~',$_REQUEST['giftids']);
		$new_status		= $_REQUEST['ch_status'];
		for($i=0;$i<count($giftid_arr);$i++)
		{
			$update_array					= array();
			$update_array['voucher_hide']	= $new_status;
			$cur_id 						= $giftid_arr[$i];
			$db->update_from_array($update_array,'gift_vouchers',array('voucher_id'=>$cur_id));
		}
		$alert = 'Hidden Status changed successfully.';
		include ('../includes/gift_voucher/list_giftvouchers.php');
	}
	elseif($_REQUEST['fpurpose']=='edit')
	{
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include "includes/gift_voucher/ajax/gift_voucher_ajax_functions.php";
		include ('includes/gift_voucher/edit_giftvouchers.php');
	}
	elseif($_REQUEST['fpurpose']=='save_update')
	{ 
		$alert='';
		// Get the details of current voucher
		$sql_vouch  = "SELECT voucher_paystatus,voucher_createdby
							FROM
								gift_vouchers
							WHERE
								voucher_id=".$_REQUEST['checkbox'][0]."
							LIMIT
								1";
		$ret_vouch = $db->query($sql_vouch);
		if ($db->num_rows($ret_vouch))
		{
			$row_vouch = $db->fetch_array($ret_vouch);
		}
		$fieldRequired = array($_REQUEST['voucher_value']);
		$fieldDescription = array('Voucher Value');
		$gone_in = 0;
		if($row_vouch['voucher_paystatus']=='Paid' or $row_vouch['voucher_paystatus']=='REFUNDED')
       	{
       		if($row_vouch['voucher_createdby']=='A') // case if created by admin
       		{
				$fieldRequired[]	= $_REQUEST['voucher_activatedon'];
				$fieldRequired[] 	= $_REQUEST['voucher_expireson'];
				$fieldDescription[] = 'Select Start Date';
				$fieldDescription[] = 'Select End Date';
				$gone_in = 1;
       		}
       		if($row_vouch['voucher_createdby']=='C') // case if created by admin
       		{
       			$fieldRequired[] 	= $_REQUEST['voucher_expireson'];
				$fieldDescription[] = 'Select End Date';
				$gone_in = 2;
       		}
       	}
       	$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array($_REQUEST['voucher_value'],$_REQUEST['voucher_max_usage']);
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		if(!$alert)
		{
			if ($gone_in==1)
			{
				if(!is_valid_date($_REQUEST['voucher_activatedon'],'normal','-') or !is_valid_date($_REQUEST['voucher_expireson'],'normal','-'))
					$alert = 'Sorry!! Activation or End Date is Invalid';
			}
			elseif($gone_in==2)
			{
				if(!is_valid_date($_REQUEST['voucher_expireson'],'normal','-'))
					$alert = 'Sorry!! End Date is Invalid';
			}
		}
		if ($alert=='')
		{
			$no_error = true;
			if ($gone_in==1)
			{
				$month_array = array('Jan'=>1,'Feb'=>2,'Mar'=>3,'Apr'=>4,'May'=>5,'Jun'=>6,'Jul'=>7,'Aug'=>8,'Sep'=>9,'Oct'=>10,'Nov'=>11,'Dec'=>12);

				$startdate_arr									= explode("-",trim($_REQUEST['voucher_activatedon']));
				$startdate										= $startdate_arr[2].'-'.$startdate_arr[1].'-'.$startdate_arr[0];
				$enddate_arr									= explode("-",trim($_REQUEST['voucher_expireson']));
				$enddate										= $enddate_arr[2].'-'.$enddate_arr[1].'-'.$enddate_arr[0];
				// Check whether the dates as valid
				if(!is_numeric($startdate_arr[0]) or !is_numeric($startdate_arr[1]) or !is_numeric($startdate_arr[2]) or !is_numeric($enddate_arr[0]) or !is_numeric($enddate_arr[1]) or !is_numeric($enddate_arr[2]))
				{
					$alert = 'Sorry!! Invalid Start or End Date';
					$no_error = false;
					include ('includes/gift_voucher/edit_giftvouchers.php');
				}
				elseif(!checkdate($startdate_arr[1],$startdate_arr[0],$startdate_arr[2]) or !checkdate($enddate_arr[1],$enddate_arr[0],$enddate_arr[2]))
				{
					$alert = 'Sorry!! Invalid Start or End Date';
					$no_error = false;
					include ('includes/gift_voucher/edit_giftvouchers.php');
				}
			}
			if ($gone_in==2)
			{
				$month_array = array('Jan'=>1,'Feb'=>2,'Mar'=>3,'Apr'=>4,'May'=>5,'Jun'=>6,'Jul'=>7,'Aug'=>8,'Sep'=>9,'Oct'=>10,'Nov'=>11,'Dec'=>12);

				$enddate_arr									= explode("-",trim($_REQUEST['voucher_expireson']));
				$enddate										= $enddate_arr[2].'-'.$enddate_arr[1].'-'.$enddate_arr[0];
				// Check whether the dates as valid
				if(!is_numeric($enddate_arr[0]) or !is_numeric($enddate_arr[1]) or !is_numeric($enddate_arr[2]))
				{
					$alert = 'Sorry!! Invalid End Date';
					$no_error = false;
					include ('includes/gift_voucher/edit_giftvouchers.php');
				}
				elseif(!checkdate($enddate_arr[1],$enddate_arr[0],$enddate_arr[2]))
				{
					$alert = 'Sorry!! Invalid End Date';
					$no_error = false;
					include ('includes/gift_voucher/edit_giftvouchers.php');
				}
			}
			if($no_error == true)
			{
				// Check whether
				// Check whether payment is made for this voucher
				$sql_check = "SELECT voucher_paystatus
									FROM
										gift_vouchers
									WHERE
										voucher_id = ".$_REQUEST['checkbox'][0]."
									LIMIT
										1";
				$ret_check = $db->query($sql_check);
				if ($db->num_rows($ret_check))
					$row_check = $db->fetch_array($ret_check);
				$update_array									= array();
				$update_array['sites_site_id']					= $ecom_siteid;
				if ($gone_in==1)
				{
					$update_array['voucher_activatedon']		= $startdate;
					$update_array['voucher_expireson']			= $enddate;
				}
				elseif ($gone_in==2)
				{
					$update_array['voucher_expireson']			= $enddate;
				}
				$update_array['voucher_type']					= trim($_REQUEST['voucher_type']);
				$update_array['voucher_value']					= trim($_REQUEST['voucher_value']);
				$update_array['voucher_max_usage']				= trim($_REQUEST['voucher_max_usage']);
				$update_array['voucher_login_touse']			= ($_REQUEST['voucher_login_touse'])?1:0;
				$update_array['voucher_freedelivery']			= ($_REQUEST['voucher_freedelivery'])?1:0;
				$update_array['voucher_hide']					= ($_REQUEST['voucher_hide'])?1:0;
				$update_array['voucher_apply_direct_discount_also']		= ($_REQUEST['voucher_apply_direct_discount_also'])?'Y':'N';
				$update_array['voucher_apply_custgroup_discount_also']	= ($_REQUEST['voucher_apply_custgroup_discount_also'])?'Y':'N';
				$update_array['voucher_apply_direct_product_discount_also']	= ($_REQUEST['voucher_apply_direct_product_discount_also'])?'Y':'N';
				$db->update_from_array($update_array,'gift_vouchers',array('voucher_id'=>$_REQUEST['checkbox'][0],'sites_site_id'=>$ecom_siteid));
				$alert .= '<br><span class="redtext"><b>Gift voucher Updated Successfully</b></span><br>';
			echo $alert;
		?>
			<br />
			<a class="smalllink" href="home.php?request=gift_voucher&vouchernumber=<?=$_REQUEST['vouchernumber']?>&paystatus=<?=$_REQUEST['paystatus']?>&addedby=<?=$_REQUEST['addedby']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Gift Voucher Listing page</a><br />
			<br />
			<a class="smalllink" href="home.php?request=gift_voucher&fpurpose=edit&checkbox[0]=<?=$_REQUEST['checkbox'][0]?>&vouchernumber=<?=$_REQUEST['vouchernumber']?>&paystatus=<?=$_REQUEST['paystatus']?>&addedby=<?=$_REQUEST['addedby']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Edit Gift Voucher Page </a><br />
			<br />
			<a class="smalllink" href="home.php?request=gift_voucher&fpurpose=add&vouchernumber=<?=$_REQUEST['vouchernumber']?>&paystatus=<?=$_REQUEST['paystatus']?>&addedby=<?=$_REQUEST['addedby']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Add New Gift Voucher Page </a>

			<?
			}
		}
		else
		{ 
				$ajax_return_function = 'ajax_return_contents';
				include "ajax/ajax.php";
				include ('includes/gift_voucher/ajax/gift_voucher_ajax_functions.php');
				$edit_id = $_REQUEST['checkbox'][0];
			   include ('includes/gift_voucher/edit_giftvouchers.php');
		}
	}
	elseif($_REQUEST['fpurpose']=='list_orders') // show orders linked with current voucher number
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/gift_voucher/ajax/gift_voucher_ajax_functions.php');
		show_order_list($_REQUEST['voucher_id']);
	}
	elseif($_REQUEST['fpurpose']=='voucher_customer_details') // show the details of customer who bought this voucher
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/gift_voucher/ajax/gift_voucher_ajax_functions.php');
		show_customer_details($_REQUEST['voucher_id']);
	}
	elseif($_REQUEST['fpurpose']=='voucher_payment_details') // show the payment details for current voucher
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/gift_voucher/ajax/gift_voucher_ajax_functions.php');
		show_payment_details($_REQUEST['voucher_id']);
	}
	elseif($_REQUEST['fpurpose']=='voucher_email_details') // show the emails related to current voucher
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/gift_voucher/ajax/gift_voucher_ajax_functions.php');
		show_voucher_emails($_REQUEST['voucher_id'],'');
	}
	elseif($_REQUEST['fpurpose']=='resend_VoucherEmail') //  Resend selected for voucher email
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/gift_voucher/ajax/gift_voucher_ajax_functions.php');
		if($_REQUEST['emailid'])
		{
			$sql = "SELECT email_id,email_to,email_subject,email_headers,
							email_type,email_was_disabled,email_sendonce,email_lastsenddate
						FROM
							gift_voucher_emails
						WHERE
							email_id = ".$_REQUEST['emailid']."
						LIMIT
							1";
			$ret = $db->query($sql);
			if ($db->num_rows($ret))
			{
				$row = $db->fetch_array($ret);
				// Call function to send the selected mail again
				resend_voucherEmail($_REQUEST['emailid'],$_REQUEST['voucher_id']);

				// Calling the function to save the email history
				save_VoucherEmailHistory($_REQUEST['emailid'],$_REQUEST['voucher_id']);

				$alert = 'Mail Send Successfully';
			}
			else
				$alert ='Sorry!! Email not found';
		}
		else
			$alert = 'Select the voucher mail to be send';
		show_voucher_emails($_REQUEST['voucher_id'],$alert);
	}
	elseif($_REQUEST['fpurpose']=='show_voucheroperation') // show the operations allowed on current voucher
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/gift_voucher/ajax/gift_voucher_ajax_functions.php');
		show_operations($_REQUEST['voucher_id'],'',0);
	}
	elseif($_REQUEST['fpurpose']=='operation_changevoucherpaystatus_sel') //  Selected voucher payment status from drop down for changing the payment status
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/gift_voucher/ajax/gift_voucher_ajax_functions.php');
		voucher_additionaldet_paymentstatus($_REQUEST['sel_stat']);
	}
	elseif($_REQUEST['fpurpose']=='operation_changeorderpaystatus_do') //  Changing the voucher payment status
	{
		$voucher_id 	= $_REQUEST['checkbox'][0];
		$ch_stat		= $_REQUEST['pass_cbo_voucherpaystatus'];
		// Get the current status of current order
		$sql_vouch = "SELECT voucher_id,voucher_number,voucher_boughton,voucher_activatedon,
							voucher_type,voucher_value,voucher_refundamt,voucher_max_usage,voucher_login_touse,
							voucher_paystatus,voucher_paystatus_manuallychanged,voucher_paystatus_manuallychanged_on,
							voucher_paystatus_manuallychanged_by,voucher_createdby,voucher_usedvalue,
							voucher_paymenttype,voucher_paymentmethod,voucher_curr_rate,
							voucher_curr_code,voucher_curr_symbol,voucher_activedays
					FROM
						gift_vouchers
					WHERE
						voucher_id = $voucher_id
					LIMIT
						1";
		$ret_vouch = $db->query($sql_vouch);
		if ($db->num_rows($ret_vouch))
		{
			$row_vouch = $db->fetch_array($ret_vouch);
			if ($row_vouch['voucher_paystatus']!=$ch_stat and $ch_stat!='')
			{
				$update_array												= array();
				$update_array['voucher_paystatus']							= add_slash($ch_stat);
				$update_array['voucher_paystatus_manuallychanged']			= 1;
				$update_array['voucher_paystatus_manuallychanged_by']		= $_SESSION['console_id'];
				$update_array['voucher_paystatus_manuallychanged_on']		= 'now()';
				$update_array['voucher_paystatus_changed_manually_paytype']	= $_REQUEST['cbo_paymethod'];
				$db->update_from_array($update_array,'gift_vouchers',array('voucher_id'=>$voucher_id));

				
				$alert = 1;
				// Check whether note is added
				$not = trim($_REQUEST['txt_additionalnote']);
				
				if($not!='') // case if note exists
				{
					if ($ch_stat=='Paid')
						$type_str = 1;
					else
						$type_str = 2;
					
					// Inserting the note to the gift_voucher_notes table
					$insert_array								= array();
					$insert_array['gift_vouchers_voucher_id']	= $voucher_id;
					$insert_array['note_add_date']				= 'now()';
					$insert_array['user_id']					= $_SESSION['console_id'];
					$insert_array['note_text']					= add_slash($not);
					$insert_array['note_type']					= $type_str;
					$db->insert_from_array($insert_array,'gift_voucher_notes');
					$alert = 2;
				}
				// If payment status is paid then set the activation date and the expiry date
				if ($ch_stat=='Paid')
				{
					// Set the activation date and expiry date
					$update_sql = "UPDATE gift_vouchers
									SET
										voucher_activatedon=curdate(),
										voucher_expireson = DATE_ADD(curdate(),INTERVAL voucher_activedays DAY),
										voucher_incomplete=0 
									WHERE
										voucher_id = $voucher_id
									LIMIT
										1";
					$db->query($update_sql);
					
					// Calling function to send the necessary voucher mails which are not send from the client area
					send_RequiredVoucherMails($voucher_id);
					
				}
				// Calling function to save and send payment status change mail
				$row_vouch['note'] = $not;
				save_and_send_VoucherMail($ch_stat,$row_vouch);
			}
			else 	
				$alert =3;
		}
		//$ajax_return_function = 'ajax_return_contents';
		//include "ajax/ajax.php";
		//include ('includes/gift_voucher/ajax/gift_voucher_ajax_functions.php');
	 $sort_order		= $_REQUEST['sort_order'];
	 $sort_by			= $_REQUEST['sort_by'];
	 $records_per_page 	= $_REQUEST['records_per_page'];
	 $addedby 			= $_REQUEST['addedby'];
	 $paystatus 		= $_REQUEST['paystatus'];
	 $vouchernumber 	= $_REQUEST['vouchernumber'];
	 $start 			= $_REQUEST['start'];
	 $pg				= $_REQUEST['pg'];
	 echo "<script>window.location='http://$ecom_hostname/console/home.php?request=gift_voucher&fpurpose=edit&curtab=payment_tab_td&voucher_id=$voucher_id&alert=$alert&vouchernumber=$vouchernumber&paystatus=$paystatus&addedby=$addedby&records_per_page=$records_per_page&sort_by=$sort_by&sort_order=$sort_order&start=$start&pg=$pg'</script>";exit;

		//include ('includes/gift_voucher/edit_giftvouchers.php');
	}
	elseif($_REQUEST['fpurpose']=='show_vouchernotes') // show notes added for selected voucher
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/gift_voucher/ajax/gift_voucher_ajax_functions.php');
		show_voucher_notes($_REQUEST['voucher_id']);
	}
	elseif($_REQUEST['fpurpose']=='save_note') //  Save voucher notes
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/gift_voucher/ajax/gift_voucher_ajax_functions.php');
		// Validating the fields
		if(trim($_REQUEST['note'])!='')
		{
			// Inserting the note
			$insert_array								= array();
			$insert_array['gift_vouchers_voucher_id']	= $_REQUEST['voucher_id'];
			$insert_array['note_add_date']				= 'now()';
			$insert_array['user_id']					= $_SESSION['console_id'];
			$insert_array['note_text']					= add_slash($_REQUEST['note']);
			$insert_array['note_type']					= 0;
			$db->insert_from_array($insert_array,'gift_voucher_notes');
			$alert = 'Note added successfully';
		}
		else
			$alert = 'Please specify the note';
		show_voucher_notes($_REQUEST['voucher_id'],$alert);
	}
	elseif($_REQUEST['fpurpose']=='delete_note') //  Delete voucher notes
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/gift_voucher/ajax/gift_voucher_ajax_functions.php');
		// Validating the fields
		if(trim($_REQUEST['noteid'])!='')
		{
			// Deleting the selected order note
			$sql_del = "DELETE FROM gift_voucher_notes WHERE note_id = ".$_REQUEST['noteid']." LIMIT 1";
			$db->query($sql_del);
			$alert = 'Note deleted successfully';
		}
		else
			$alert = 'Please select the note to be deleted';
		show_voucher_notes($_REQUEST['voucher_id'],$alert);
	}
	elseif($_REQUEST['fpurpose']=='operation_refund_sel') //  clicked the refund button
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/gift_voucher/ajax/gift_voucher_ajax_functions.php');
		voucher_additionaldet_refund($_REQUEST['voucher_id']);
	}
	elseif($_REQUEST['fpurpose']=='operation_refund_do')
	{
		$voucher_id 	= $_REQUEST['checkbox'][0];
		$ref_amt		= sprintf('%.2f',trim($_REQUEST['txt_refundamt']));
		$ref_reason		= trim($_REQUEST['txt_refundreason']);
		if ($voucher_id)
		{
			// Get the details required from gift_vouchers table
			$sql_vouch = "SELECT voucher_curr_symbol,voucher_curr_rate,voucher_boughton,voucher_number,voucher_max_usage,
								voucher_curr_numeric_code,voucher_value,voucher_refundamt,voucher_paystatus,
								voucher_paymenttype,voucher_paymentmethod,voucher_curr_code,voucher_totalauthorizeamt,customers_customer_id 
						FROM
							gift_vouchers
						WHERE
							voucher_id = $voucher_id
						LIMIT
							1";
			$ret_vouch = $db->query($sql_vouch);
			if($db->num_rows($ret_vouch))
			{
				$row_vouch = $db->fetch_array($ret_vouch);
				
				if($row_vouch['voucher_paystatus']=='REFUNDED')
				{
					$alert = 'Sorry!! Full value of voucher has already been refunded';
				}
				elseif($row_vouch['voucher_paystatus']!='Paid')
				{
					$alert = 'Sorry!! payment not made for this voucher';
				}
				else
				{
					if ($row_vouch['voucher_totalauthorizeamt']>0)
						$allowable_refund_amt = $row_vouch['voucher_totalauthorizeamt']-$row_vouch['voucher_refundamt'];
					else
						$allowable_refund_amt = ($row_vouch['voucher_value']-$row_vouch['voucher_refundamt']);

					// Converting the refund amount to the required currency value
					$allowable_refund_amt = print_price_selected_currency($allowable_refund_amt,$row_vouch['voucher_curr_rate'],'',true);

					if ($ref_amt>$allowable_refund_amt)
					{
						$alert = 'Refund amount is greater than the actual amount to be refunded';
					}
					else
					{
						if($row_vouch['voucher_totalauthorizeamt']>0)
						{
							if ($row_vouch['voucher_totalauthorizeamt']==$row_vouch['voucher_refundamt'])
							{
								$alert = 'Sorry!! order already refunded';
							}
						}
						else
						{
							if ($row_vouch['voucher_value']==$row_vouch['voucher_refundamt'])
							{
								$alert = 'Sorry!! order already refunded';
							}
						}
					}

					// If reached here then it is legal to refund the order
					// Now check for the payment method and payment types used in order
					if ($row_vouch['voucher_paymenttype']=='credit_card') // case if credit card in involved
					{
						if ($row_vouch['voucher_paymentmethod']=='PROTX') // check whether the payment method used in protx
						{
							$cur_mod = 'voucher';
							include 'console_refund.php';
						}
						else // gateway is not protx, so just change the status directly, no need to go to payment gateway
						{
							$baseStatus = "OK"; // forcing the refund to be successful
						}
					}
					else // case if credit card is not used directly in order. so just change the status directly, no need to go to payment gateway
					{
						$baseStatus = "OK"; // forcing the refund to be successful
					}
					// Check whether refund was successfull.
					if ($baseStatus == "OK")
					{
						// Converting the specified amount to default currency
						$ref_amt 		= print_price_default_currency($ref_amt,$row_vouch['voucher_curr_rate'],'',true);
						//Update the orders table to add the refunded amount to the order_refundamt
						$update_sql = "UPDATE gift_vouchers
												SET
													voucher_refundamt = voucher_refundamt + $ref_amt
												WHERE
													voucher_id = $voucher_id
												LIMIT
													1";
						$db->query($update_sql);

						// if voucher_paymenttype is pay_on_account then make a contra entry in the order_payonaccount_details table for the refunded amount
							if ($row_vouch['voucher_paymenttype']=='pay_on_account' and $row_vouch['voucher_paystatus']=='Paid')
							{
								// Finding amount remaining in the order after any of the refunds made
								if ($ref_amt>0)
								{
									$sql_currency = "SELECT curr_code,curr_numeric_code,curr_sign_char FROM general_settings_site_currency WHERE sites_site_id=$ecom_siteid AND curr_default=1";
									$ret_currency = $db->query($sql_currency);
									if ($db->num_rows($ret_currency))
									{
										$row_currency	= $db->fetch_array($ret_currency);
									}	
									// Making and entry to the order_payonaccount_details table with the details as Refunded due to order cancellation 
									$insert_array								= array();
									$insert_array['pay_date']					= 'now()';
									$insert_array['gift_vouchers_voucher_id']	= $voucher_id;
									$insert_array['sites_site_id']				= $ecom_siteid;
									$insert_array['customers_customer_id']		= $row_vouch['customers_customer_id'];
									$insert_array['pay_amount']					= $ref_amt;
									$insert_array['pay_transaction_type']		= 'C';
									$insert_array['pay_details']				= 'Refunded due to gift voucher refund - Voucher Number '.$row_vouch['voucher_number'];
									$insert_array['pay_paystatus']				= 'Paid';
									$insert_array['pay_paymenttype']			= 'OTHER';
									$insert_array['pay_paystatus_changed_by']	= $_SESSION['console_id'];
									$insert_array['pay_paystatus_changed_on']	= 'now()';
									$insert_array['pay_curr_rate']				= 1;
									$insert_array['pay_curr_code']				= $row_currency['curr_code'];
									$insert_array['pay_curr_symbol']			= $row_currency['curr_sign_char'];
									$insert_array['pay_curr_numeric_code']		= $row_currency['curr_numeric_code'];
									$db->insert_from_array($insert_array,'order_payonaccount_details');
									// Decrementing the used limit for current customer
									$update_cust = "UPDATE 
														customers 
															SET 
																customer_payonaccount_usedlimit = customer_payonaccount_usedlimit - $ref_amt 
														WHERE 
															customer_id =".$row_vouch['customers_customer_id']." 
															AND sites_site_id = $ecom_siteid 
														LIMIT 
															1";
									$db->query($update_cust);
								}	
							}
						
						// Insert the refund amount to voucher_details_refunded table
						$insert_array								= array();
						$insert_array['refund_on']					= 'now()';
						$insert_array['refund_by']					= $_SESSION['console_id'];
						$insert_array['refund_amt']					= $ref_amt;
						$insert_array['gift_vouchers_voucher_id']	= $voucher_id;
						$db->insert_from_array($insert_array,'gift_voucher_details_refunded');
						$refunded_id = $db->insert_id();

						// Making an entry for reason in voucher_notes table
						$insert_array								= array();
						$insert_array['gift_vouchers_voucher_id']	= $voucher_id;
						$insert_array['note_add_date']				= 'now()';
						$insert_array['user_id']					= $_SESSION['console_id'];
						$insert_array['note_text']					= add_slash($ref_reason);
						$insert_array['note_type']					= 3;
						$db->insert_from_array($insert_array,'gift_voucher_notes');
						$alert .= '. Additional note saved in notes section';

						// Check whether full amount is refunded. If so change the payment status to REFUNDED
						if ($row_vouch['voucher_totalauthorizeamt']>0)		 // case if authorize amount exists
						{
							$sql_update = "UPDATE gift_vouchers
												SET
													voucher_paystatus = 'REFUNDED'
												WHERE
													voucher_id = $voucher_id
													AND voucher_refundamt = voucher_totalauthorizeamt
												LIMIT
													1";
							$db->query($sql_update);
						}
						else
						{
							$sql_update = "UPDATE gift_vouchers
												SET
													voucher_paystatus = 'REFUNDED'
												WHERE
													voucher_id = $voucher_id
													AND voucher_refundamt = voucher_value
												LIMIT
													1";
							$db->query($sql_update);
						}
						// Saving and sending mail to customer;
						$voucher_arr['voucher_id'] 				= $voucher_id;
						$voucher_arr['note']					= $ref_reason;
						$voucher_arr['refund_amt']				= $ref_amt;
						$voucher_arr['voucher_curr_rate']		= $row_vouch['voucher_curr_rate'];
						$voucher_arr['voucher_curr_symbol']		= $row_vouch['voucher_curr_symbol'];
						$voucher_arr['voucher_boughton']		= $row_vouch['voucher_boughton'];
						$voucher_arr['voucher_value']			= $row_vouch['voucher_value'];
						$voucher_arr['voucher_number']			= $row_vouch['voucher_number'];
						$voucher_arr['voucher_max_usage']		= $row_vouch['voucher_max_usage'];
						save_and_send_VoucherMail('REFUND',$voucher_arr);
						$alert = 1;
					}
					else
						$alert = 2;
				}

			}
		}
		//$curtab ='refundamt_tab_td';
		//$ajax_return_function = 'ajax_return_contents';
		//include "ajax/ajax.php";
		//include "includes/gift_voucher/ajax/gift_voucher_ajax_functions.php";
		//voucher_refund_amount($_REQUEST['voucher_id'],$alert);
       
	 $sort_order		= $_REQUEST['sort_order'];
	 $sort_by			= $_REQUEST['sort_by'];
	 $records_per_page 	= $_REQUEST['records_per_page'];
	 $addedby 			= $_REQUEST['addedby'];
	 $paystatus 		= $_REQUEST['paystatus'];
	 $vouchernumber 	= $_REQUEST['vouchernumber'];
	 $start 			= $_REQUEST['start'];
	 $pg				= $_REQUEST['pg'];
	 echo "<script>window.location='http://$ecom_hostname/console/home.php?request=gift_voucher&fpurpose=edit&curtab=refundamt_tab_td&voucher_id=$voucher_id&alert=$alert&vouchernumber=$vouchernumber&paystatus=$paystatus&addedby=$addedby&records_per_page=$records_per_page&sort_by=$sort_by&sort_order=$sort_order&start=$start&pg=$pg'</script>";exit;
		///include ('includes/gift_voucher/edit_giftvouchers.php');
	}
	elseif($_REQUEST['fpurpose']=='refund_details') //  clicked for refund details
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include "../includes/gift_voucher/ajax/gift_voucher_ajax_functions.php";
		voucher_refund_details($_REQUEST['voucher_id']);
	}
	elseif($_REQUEST['fpurpose']=='refund_amount') //  clicked for refund details
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include "../includes/gift_voucher/ajax/gift_voucher_ajax_functions.php";
		voucher_refund_amount($_REQUEST['voucher_id']);
	}
	elseif($_REQUEST['fpurpose']=='operation_changevoucherpaystatus_Paidsel') //  Selected order payment status from drop down for changing the payment status
	{ 
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include "../includes/gift_voucher/ajax/gift_voucher_ajax_functions.php";
		show_PayReceived_TakeDetails($_REQUEST['sel_stat']);
	}
	elseif($_REQUEST['fpurpose']=='operation_changevoucherpaystatus_Failsel') //  Selected order payment status from drop down for changing the payment status
	{  
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include "../includes/gift_voucher/ajax/gift_voucher_ajax_functions.php";
		show_PayFailed_TakeDetails($_REQUEST['sel_stat']);
	}
	elseif($_REQUEST['fpurpose']=='operation_release_sel') //  clicked the release button
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include "../includes/gift_voucher/ajax/gift_voucher_ajax_functions.php";
		voucher_release_note();
	}
	elseif($_REQUEST['fpurpose']=='operation_paycapture_do') // section to handle the case of doing some operation related to payment captures
	{
		$voucher_id 	= $_REQUEST['checkbox'][0];
		if(trim($_REQUEST['txt_authamt'])!='')
		{
			$auth_amt	= sprintf('%.2f',trim($_REQUEST['txt_authamt']));
		}
		$cur_note	= trim($_REQUEST['txt_additionalnote']);
		if ($voucher_id)
		{
			// Get the details required from gift_vouchers table
			$sql_vouch = "SELECT voucher_id,voucher_paystatus,voucher_value,voucher_totalauthorizeamt,
						voucher_curr_rate,voucher_curr_code,voucher_boughton,voucher_curr_symbol,voucher_max_usage   
						FROM
							gift_vouchers
						WHERE
							voucher_id = $voucher_id
						LIMIT
							1";
			$ret_vouch = $db->query($sql_vouch);
			if($db->num_rows($ret_vouch))
			{
				$row_vouch= $db->fetch_array($ret_vouch);
				if($_REQUEST['paycapture_type']=='RELEASE') // case of coming to release
				{
					if ($row_vouch['voucher_paystatus']=='DEFERRED')// check whether the order is still in Deferred pay status
					{
						$curmod 	= 'RELEASE';
						$cursrc		= 'voucher';
						include 'console_manage_paymentcapture.php'; //Page which goes to Protx for Releasing the Transaction
						if($baseStatus=="OK")
						{
							//Change the payment status to Paid for current gift voucher
							$update_sql = "UPDATE gift_vouchers
											SET
												voucher_paystatus = 'Paid',
												voucher_paystatus_manuallychanged = 1,
												voucher_paystatus_manuallychanged_by=".$_SESSION['console_id'].",
												voucher_paystatus_manuallychanged_on=now()
											WHERE
												voucher_id = $voucher_id
											LIMIT
												1";
							$db->query($update_sql);
							$alert 		= 'Released Successfully';

							if ($cur_note!='')
							{
								// Making insertion to notes table if not added
								$insert_array								= array();
								$insert_array['gift_vouchers_voucher_id']	= $voucher_id;
								$insert_array['note_add_date']				= 'now()';
								$insert_array['user_id']					= $_SESSION['console_id'];
								$insert_array['note_text']					= add_slash($cur_note);
								$insert_array['note_type']					= 4;
								$db->insert_from_array($insert_array,'gift_voucher_notes');
								$alert .= '. Additional note saved in notes section';
							}
							// Saving and sending mail to customer;
							$voucher_arr['voucher_id'] 				= $voucher_id;
							$voucher_arr['note']					= $cur_note;
							$voucher_arr['voucher_curr_rate']		= $row_vouch['voucher_curr_rate'];
							$voucher_arr['voucher_curr_symbol']		= $row_vouch['voucher_curr_symbol'];
							$voucher_arr['voucher_boughton']		= $row_vouch['voucher_boughton'];
							$voucher_arr['voucher_value']			= $row_vouch['voucher_value'];
							$voucher_arr['voucher_number']			= $row_vouch['voucher_number'];
							$voucher_arr['voucher_max_usage']		= $row_vouch['voucher_max_usage'];
							
							// Calling function to send the necessary voucher mails which are not send from the client area
							send_RequiredVoucherMails($voucher_id);
							save_and_send_VoucherMail('RELEASE',$voucher_arr);
						}
						else
							$alert		= 'Release Not Successfull';
					}
					else
					{
						$alert = 'Sorry!! Release not successfull. Payment status is not Deferred.';
					}
				}
				elseif($_REQUEST['paycapture_type']=='ABORT') // case of coming to abort Deferred transaction
				{
					if ($row_vouch['voucher_paystatus']=='DEFERRED')// check whether the order is still in Deferred pay status
					{
						$curmod 		= 'ABORT';
						$cursrc			= 'voucher';
						include 'console_manage_paymentcapture.php'; // case of aborting
						if($baseStatus=="OK")
						{
							//Change the payment status to Aborted for current order
							$update_sql = "UPDATE gift_vouchers
											SET
												voucher_paystatus = 'ABORTED',
												voucher_paystatus_manuallychanged = 1,
												voucher_paystatus_manuallychanged_by=".$_SESSION['console_id'].",
												voucher_paystatus_manuallychanged_on=now()
											WHERE
												voucher_id = $voucher_id
											LIMIT
												1";
							$db->query($update_sql);
							$alert 		= 'Aborted Successfully';

							if ($cur_note!='')
							{
								// Making insertion to notes table if not added
								$insert_array								= array();
								$insert_array['gift_vouchers_voucher_id']	= $voucher_id;
								$insert_array['note_add_date']				= 'now()';
								$insert_array['user_id']					= $_SESSION['console_id'];
								$insert_array['note_text']					= add_slash($cur_note);
								$insert_array['note_type']					= 5;
								$db->insert_from_array($insert_array,'gift_voucher_notes');
								$alert .= '. Additional note saved in notes section';
							}
							// Saving and sending mail to customer;
							$voucher_arr['voucher_id'] 				= $voucher_id;
							$voucher_arr['note']					= $cur_note;
							$voucher_arr['voucher_curr_rate']		= $row_vouch['voucher_curr_rate'];
							$voucher_arr['voucher_curr_symbol']		= $row_vouch['voucher_curr_symbol'];
							$voucher_arr['voucher_boughton']		= $row_vouch['voucher_boughton'];
							$voucher_arr['voucher_value']			= $row_vouch['voucher_value'];
							$voucher_arr['voucher_number']			= $row_vouch['voucher_number'];
							$voucher_arr['voucher_max_usage']		= $row_vouch['voucher_max_usage'];
							save_and_send_VoucherMail('ABORTED',$voucher_arr);
						}
						else
							$alert		= 'Abort Not Successfull';
					}
					else
					{
						$alert = 'Sorry!! About not successfull. Payment status is not Deferred.';
					}

				}
				elseif($_REQUEST['paycapture_type']=='REPEAT') // case of coming to repeating Preauth transaction
				{
					if ($row_vouch['voucher_paystatus']=='PREAUTH')// check whether the order is still in Preauth pay status
					{
						$curmod 		= 'REPEAT';
						$cursrc			= 'voucher';
						include 'console_manage_paymentcapture.php'; // case of repeating
						if($baseStatus=="OK")
						{
							//Change the payment status to Paid for current order
							$update_sql = "UPDATE gift_vouchers
											SET
												voucher_paystatus = 'Paid',
												voucher_paystatus_manuallychanged = 1,
												voucher_paystatus_manuallychanged_by=".$_SESSION['console_id'].",
												voucher_paystatus_manuallychanged_on=now()
											WHERE
												voucher_id = $voucher_id
											LIMIT
												1";
							$db->query($update_sql);
							$alert 		= 'Repeated Successfully';
							if ($cur_note!='')
							{
								// Making insertion to notes table if not added
								$insert_array								= array();
								$insert_array['gift_vouchers_voucher_id']	= $voucher_id;
								$insert_array['note_add_date']				= 'now()';
								$insert_array['user_id']					= $_SESSION['console_id'];
								$insert_array['note_text']					= add_slash($cur_note);
								$insert_array['note_type']					= 6;
								$db->insert_from_array($insert_array,'gift_voucher_notes');
								$alert .= '. Additional note saved in notes section';
							}
							// Saving and sending mail to customer;
							// Saving and sending mail to customer;
							$voucher_arr['voucher_id'] 						= $voucher_id;
							$voucher_arr['note']							= $cur_note;
							$voucher_arr['voucher_curr_rate']				= $row_vouch['voucher_curr_rate'];
							$voucher_arr['voucher_curr_symbol']				= $row_vouch['voucher_curr_symbol'];
							$voucher_arr['voucher_boughton']				= $row_vouch['voucher_boughton'];
							$voucher_arr['voucher_value']					= $row_vouch['voucher_value'];
							$voucher_arr['voucher_number']					= $row_vouch['voucher_number'];
							$voucher_arr['voucher_max_usage']				= $row_vouch['voucher_max_usage'];
							// Calling function to send the necessary voucher mails which are not send from the client area
							send_RequiredVoucherMails($voucher_id);
							save_and_send_VoucherMail('REPEAT',$voucher_arr);
						}
						else
							$alert		= 'Repeat Not Successfull';

					}
					else
					{
						$alert = 'Sorry!! Repeat not successfull. Payment status is not Preauth.';
					}
				}
				elseif($_REQUEST['paycapture_type']=='AUTHORISE') // case of coming to authorise Authenticate transaction
				{
					if ($row_vouch['voucher_paystatus']=='AUTHENTICATE')// check whether the order is still in AUTHENTICATE pay status
					{
						$curmod 		= 'AUTHORISE';
						$cursrc			= 'voucher';
						include 'console_manage_paymentcapture.php'; // case of authorize
						if($baseStatus == "OK")
						{
							$cur_authamt 		= trim($auth_amt);
							$total_orderauth	= $row_vouch['voucher_totalauthorizeamt'];

							// Finding the max amount that can be authorised
							//check whether partial payment is made (product deposit case)
							$rem_amt 			= ($row_vouch['voucher_value'] - $row_vouch['voucher_refundamt']);
							// taking 115% of rem_amt
							$rem_amt_per		= $rem_amt * 115/100;
							// Less the amount already authorized
							$rem_amt_per		= $rem_amt_per - $row_vouch['voucher_totalauthorizeamt'];
							$max_authn_allowed	= $rem_amt_per; // only the paid amount is set to variable


							// converting the current auth amount to default currency
							$cur_authamt		= print_price_default_currency($cur_authamt,$row_vouch['voucher_curr_rate'],'',true);
							// find the new total of auth amount by adding it to te auth total in orders table
							$tot_authdef		= ($total_orderauth + $cur_authamt);

							// Check whether the amount being authorized is valid to authorize
							if ($max_authn_allowed>=$cur_authamt)
							{
								if (($max_authn_allowed==$cur_authamt) or ($tot_authdef>=$rem_amt)) // if the maximum auth allowed amount == current authorising amount or total auth amount is > remaining amount to be authorized the make the status to Paid
								{
									// Change the payment status of voucher to Paid
									$update_sql = "UPDATE gift_vouchers
													SET
														voucher_paystatus = 'Paid',
														voucher_totalauthorizeamt = $tot_authdef,
														voucher_activatedon=curdate(),
														voucher_expireson= (DATE_ADD(curdate(), INTERVAL voucher_activedays DAY)),
														voucher_paystatus_manuallychanged = 1,
														voucher_paystatus_manuallychanged_by=".$_SESSION['console_id'].",
														voucher_paystatus_manuallychanged_on=now()
													WHERE
														voucher_id = $voucher_id
													LIMIT
														1";
									$db->query($update_sql);

									// Inserting an entry to the gift_voucher_details_authorized_amount table
									$insert_array								= array();
									$insert_array['auth_on']					= 'now()';
									$insert_array['auth_by']					= $_SESSION['console_id'];
									$insert_array['auth_amt']					= $cur_authamt;
									$insert_array['gift_vouchers_voucher_id']	= $voucher_id;
									$db->insert_from_array($insert_array,'gift_voucher_details_authorized_amount');
									$alert 		= 'Amount authorised Successfully and voucher status changed';
									// Calling function to send the necessary voucher mails which are not send from the client area
									send_RequiredVoucherMails($voucher_id);
								}
								else // case if auth amt is < pass_tot
								{
									// updating the total authamount in gift_vouchers table
									$update_sql = "UPDATE gift_vouchers
													SET
														voucher_totalauthorizeamt = $tot_authdef
													WHERE
														voucher_id = $voucher_id
													LIMIT
														1";
									$db->query($update_sql);
									// Inserting an entry to the gift_voucher_details_authorized_amount table
									$insert_array								= array();
									$insert_array['auth_on']					= 'now()';
									$insert_array['auth_by']					= $_SESSION['console_id'];
									$insert_array['auth_amt']					= $cur_authamt;
									$insert_array['gift_vouchers_voucher_id']	= $voucher_id;
									$db->insert_from_array($insert_array,'gift_voucher_details_authorized_amount');
									$alert 		= 'Amount authorised Successfully';
								}
									// Saving and sending mail to customer;
									$voucher_arr['voucher_id'] 					= $voucher_id;
									$voucher_arr['note']						= $cur_note;
									$voucher_arr['auth_amt']					= $cur_authamt;
									$voucher_arr['voucher_curr_rate']			= $row_vouch['voucher_curr_rate'];
									$voucher_arr['voucher_curr_symbol']			= $row_vouch['voucher_curr_symbol'];
									$voucher_arr['voucher_boughton']			= $row_vouch['voucher_boughton'];
									$voucher_arr['voucher_value']				= $row_vouch['voucher_value'];
									$voucher_arr['voucher_number']				= $row_vouch['voucher_number'];
									$voucher_arr['voucher_max_usage']			= $row_vouch['voucher_max_usage'];
									save_and_send_VoucherMail('AUTHORISE',$voucher_arr);
								if ($cur_note!='')
								{
									// Making insertion to notes table if not added
									$insert_array								= array();
									$insert_array['gift_vouchers_voucher_id']	= $voucher_id;
									$insert_array['note_add_date']				= 'now()';
									$insert_array['user_id']					= $_SESSION['console_id'];
									$insert_array['note_text']					= add_slash($cur_note);
									$insert_array['note_type']					= 7;
									$db->insert_from_array($insert_array,'gift_voucher_notes');
									$alert .= '. Additional note saved in notes section';
								}
							}
							else
							{
								$alert = 'Sorry!! Maximum amount that can be authorized is '.print_price_selected_currency($max_authn_allowed,$row_vouch['voucher_curr_rate'],$_REQUEST['order_currency_symbol'],true);
							}
						}
						else
							$alert		= 'Authorise Not Successfull';

					}
					else
					{
						$alert = 'Sorry!! Authorise not successfull. Payment status is not Authenticate.';
					}
				}
				elseif($_REQUEST['paycapture_type']=='CANCEL') // case of coming to cancel authorise Authenticate transaction
				{
					if ($row_vouch['voucher_paystatus']=='AUTHENTICATE')// check whether the order is still in AUTHENTICATE pay status
					{
						$curmod 		= 'CANCEL';
						$cursrc			= 'voucher';
						include 'console_manage_paymentcapture.php'; // case of repeating
						if($baseStatus == "OK")
						{
							// Change the payment status of voucher to Cancelled
							$update_sql = "UPDATE gift_vouchers
											SET
												voucher_paystatus = 'CANCELLED',
												voucher_paystatus_manuallychanged = 1,
												voucher_paystatus_manuallychanged_by=".$_SESSION['console_id'].",
												voucher_paystatus_manuallychanged_on=now()
											WHERE
												voucher_id = $voucher_id
											LIMIT
												1";
							$db->query($update_sql);
							$alert 		= 'Cancelled Successfully';
							if ($cur_note!='')
							{
								// Making insertion to notes table if not added
								$insert_array								= array();
								$insert_array['gift_vouchers_voucher_id']	= $voucher_id;
								$insert_array['note_add_date']				= 'now()';
								$insert_array['user_id']					= $_SESSION['console_id'];
								$insert_array['note_text']					= add_slash($cur_note);
								$insert_array['note_text']					= 8;
								$db->insert_from_array($insert_array,'gift_voucher_notes');
								$alert .= '. Additional note saved in notes section';
							}
							// Saving and sending mail to customer;
							$voucher_arr['voucher_id'] 				= $voucher_id;
							$voucher_arr['note']					= $cur_note;
							$voucher_arr['voucher_curr_rate']		= $row_vouch['voucher_curr_rate'];
							$voucher_arr['voucher_curr_symbol']		= $row_vouch['voucher_curr_symbol'];
							$voucher_arr['voucher_boughton']		= $row_vouch['voucher_boughton'];
							$voucher_arr['voucher_value']			= $row_vouch['voucher_value'];
							$voucher_arr['voucher_number']			= $row_vouch['voucher_number'];
							$voucher_arr['voucher_max_usage']		= $row_vouch['voucher_max_usage'];
							save_and_send_VoucherMail('CANCELLED',$voucher_arr);
						}
						else
							$alert		= 'Cancel Not Successfull';
					}
					else
					{
						$alert = 'Sorry!! Cancel not successfull. Payment status is not Authenticate.';
					}
				}
			}
		}
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		$_REQUEST['curtab'] = 'payment_tab_td';
		include ('includes/gift_voucher/ajax/gift_voucher_ajax_functions.php');
		include ('includes/gift_voucher/edit_giftvouchers.php');
	}
	
	elseif($_REQUEST['fpurpose']=='show_giftvoucher_main') //  clicked the abort button
	{ 
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/gift_voucher/ajax/gift_voucher_ajax_functions.php');
		show_giftvoucher_main($_REQUEST['voucher_id']);
	}
	
	elseif($_REQUEST['fpurpose']=='operation_abort_sel') //  clicked the abort button
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/gift_voucher/ajax/gift_voucher_ajax_functions.php');
		vocher_abort_note();
	}
	elseif($_REQUEST['fpurpose']=='operation_repeat_sel') //  clicked the authorize button
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/gift_voucher/ajax/gift_voucher_ajax_functions.php');
		voucher_repeat_note();
	}
	elseif($_REQUEST['fpurpose']=='operation_authorise_sel') //  clicked the authorize do button
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/gift_voucher/ajax/gift_voucher_ajax_functions.php');
		voucher_authorise_details($_REQUEST['voucher_id']);
	}
	elseif($_REQUEST['fpurpose']=='operation_cancel_sel') //  clicked the cancel button
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/gift_voucher/ajax/gift_voucher_ajax_functions.php');
		voucher_cancel_note();
	}
	elseif ($_REQUEST['fpurpose'] == 'authorise_amount_details')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/gift_voucher/ajax/gift_voucher_ajax_functions.php');
		voucher_authorise_amount_details($_REQUEST['voucher_id']);
	}
?>

