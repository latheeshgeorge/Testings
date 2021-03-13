<?
require("functions/functions.php");
require("includes/session.php");
require("includes/price_display.php");
require("includes/urls.php");
require("config.php");

// Split out the useful information into variables we can use

/*$creditrequest_id	= trim($_POST['CreditRequestID']); 
$ident_arr			= trim($_POST['Identification']);
$order_id_arr		= explode(':',trim($ident_arr['RetailerUniqueRef']));
$order_id			= $order_id_arr[1];
$api_key			= trim($ident_arr['api_key']);
$ins_id				= trim($ident_arr['InstallationID']);
$fin_status			= strtoupper(trim($_POST['Status']));
$fin_arr			= trim($POST['Finance']);
$fin_code			= trim($fin_arr['Code']);
$fin_deposit		= trim($fin_arr['Deposit']);*/


$creditrequest_id	= trim($_POST['CreditRequestID']); 
$fin_status			= strtoupper(trim($_POST['Status']));
//$order_id_arr		= explode(':',trim($_POST['RetailerUniqueRef']));
$order_id			= trim($_POST['Identification']['RetailerUniqueRef']);
$fin_code			= trim($_POST['Finance']['Code']);

$cons_title			= trim($_POST['Consumer']['Title']);
$cons_forename		= trim($_POST['Consumer']['Forename']);
$cons_surname		= trim($_POST['Consumer']['Surname']);

/* exact value*/
//$order_id			=  225035;
//$fin_status			= 'DECLINE';
/* end Exact value */

/*$cons_arr			= trim($_POST['Consumer']);

$cons_phone			= trim($cons_arr['PhoneNumber']);
$cons_mobile		= trim($cons_arr['MobileNumber']);
$cons_email			= trim($cons_arr['EmailAddress']);
$cons_houseno		= trim($cons_arr['HouseNumber']);
$cons_housename		= trim($cons_arr['HouseName']);
$cons_street		= trim($cons_arr['Street']);
$cons_town			= trim($cons_arr['Town']);
$cons_postcode		= trim($cons_arr['Postcode']);*/

$fp = fopen ('finance_test/response_new.txt','a+');

 ob_start();
 echo date("r")."\n================================\n";
/* PERFORM COMLEX QUERY, ECHO RESULTS, ETC. */
print_r($_POST);

$content = ob_get_contents();
	ob_end_clean();
	fwrite($fp,$content);
	fclose($fp);

// Check whether order id exists
if($order_id)
{
	// Check whether this is a valid order id
	$sql_check = "SELECT order_id,order_status FROM orders WHERE order_id = '".$order_id."' AND sites_site_id = $ecom_siteid LIMIT 1";
	$ret_check = $db->query($sql_check);
	if($db->num_rows($ret_check))
	{
		
		$row_check = $db->fetch_array($ret_check); 
		$add_condition = '';
		$email_content = '';
		if($fin_status=='ACCEPT' and $row_check['order_status']=='NOT_AUTH') // Set to order_status as new only if this condition is satisfied, otherwise do not change the order status.
		{
			$add_condition = ", order_status='NEW' ";
		}
		
		if($fin_status=='DECLINE' or $fin_status=='CANCELLED')
		{
			$add_condition = ", order_status='NOT_AUTH'";
			
			if($fin_status=='DECLINE')
			{
			  $sql_ord_cust = " SELECT 
									order_custtitle,order_custfname,order_custmname,order_custsurname,order_custemail 
								FROm 
									orders
								WHERE
									order_id=$order_id 
								AND 
									sites_site_id=$ecom_siteid 
								LIMIT 1";
				$ret_ord_cust = $db->query($sql_ord_cust);
				if($db->num_rows($ret_ord_cust)>0)
				{
				   $row_ord_cust = $db->fetch_array($ret_ord_cust);
				   $cust_title   = $row_ord_cust['order_custtitle'];
				   $cust_name    = $row_ord_cust['order_custsurname'];
				   $cust_email   = $row_ord_cust['order_custemail'];
				   $filenamed    = $image_path .'/otherfiles/decline.html'; 
				   
				   if (file_exists($filenamed))
				   {
				        $fp 				= fopen($filenamed,'r');
						$decline_content 	= fread($fp,filesize($filenamed));
						fclose($fp);
						$search_arr      = array('[title]','[cust_name]');
						$search_reparr      = array($cust_title,$cust_name);
						$decline_content    = str_replace($search_arr,$search_reparr,$decline_content);
						$email_from    ='finance.application@discount-mobility.co.uk';
						$email_subject = "Help With Your Recent Mobility Finance Application"; 
						$email_headers 	= "From: <$email_from>\n";
						$email_headers 	.= "MIME-Version: 1.0\n";
						$email_headers 	.= "Content-type: text/html; charset=iso-8859-1\n";
						$email_headers 	.="BCC: online.enquiries@discount-mobility.co.uk\n";

						$email_content = add_line_break($decline_content); 
						
				   } 			   
				}

			}
		}
		// Updating the payment status of the order 
		$update_sql = "UPDATE orders 
							SET 
								order_paystatus = '".add_slash($fin_status)."'  
								$add_condition 
							WHERE 
								order_id = '".$order_id."' 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
		$db->query($update_sql);
		if (file_exists($filenamed) && $email_content!='' && $fin_status=='DECLINE')
		{
		   $insert_array = array();
		   $insert_array['orders_order_id']  = $order_id;
		   $insert_array['note_add_date']    = 'now()';
		   $insert_array['user_id']    = 0;
		   $insert_array['note_type'] =14;
		   $insert_array['note_text'] ="Decline notification email has sent to the customer";
		   $insert_array['note_related_id'] =0;	
		   $db->insert_from_array($insert_array,'order_notes');	   
		   mail($cust_email, $email_subject,$email_content, $email_headers);
		}
		// Check whether an entry exists in order_payment_barclaycard table for current order
		$sql_check = "SELECT orders_order_id
						FROM
							order_payment_4minute_finance
						WHERE
							orders_order_id = $order_id
							AND sites_site_id = $ecom_siteid 
						LIMIT
							1";
		$ret_check = $db->query($sql_check);
		if(trim($fin_deposit)=='')
			$fin_deposit = '0.00';
		if ($db->num_rows($ret_check)) // case record exists. so update the details
		{
			$row_check = $db->fetch_array($ret_check);	
			$update_sql	= "UPDATE order_payment_4minute_finance 
							SET 
									fin_creditrequestid ='".$creditrequest_id."',
									fin_apikey='".$api_key."',
									fin_installationid = '".$ins_id."',
									fin_status='".$fin_status."',
									fin_code='".$fin_code."',
									fin_deposit='".$fin_deposit."',
									fin_title='".$cons_title."',
									fin_forename='".$cons_forename."',
									fin_surname='".$cons_surname."',
									fin_phone='".$cons_phone."',
									fin_mobile='".$cons_mobile."',
									fin_email='".$cons_email."',
									fin_houseno='".$cons_houseno."',
									fin_housename='".$cons_housename."',
									fin_street='".$cons_street."',
									fin_town='".$cons_town."',
									fin_postcode='".$cons_postcode."'  
								WHERE
									orders_order_id = $order_id
									AND sites_site_id = $ecom_siteid 
								LIMIT
									1";
			$db->query($update_sql);
		}
		else
		{
			$insert_array						= array();
			$insert_arr['orders_order_id']		= $order_id;
			$insert_arr['sites_site_id']		= $ecom_siteid;
			$insert_arr['fin_creditrequestid']	= $creditrequest_id;
			$insert_arr['fin_apikey']			= $api_key;
			$insert_arr['fin_installationid']	= $ins_id;
			$insert_arr['fin_status']			= $fin_status;
			$insert_arr['fin_code']				= $fin_code;
			$insert_arr['fin_deposit']			= $fin_deposit;
			$insert_arr['fin_title']			= $cons_title;
			$insert_arr['fin_forename']			= $cons_forename;
			$insert_arr['fin_surname']			= $cons_surname;
			$insert_arr['fin_phone']			= $cons_phone;
			$insert_arr['fin_mobile']			= $cons_mobile;
			$insert_arr['fin_email']			= $cons_email;
			$insert_arr['fin_houseno']			= $cons_houseno;
			$insert_arr['fin_housename']		= $cons_housename;
			$insert_arr['fin_street']			= $cons_street;
			$insert_arr['fin_town']				= $cons_town;
			$insert_arr['fin_postcode']			= $cons_postcode;
			$db->insert_from_array($insert_arr,'order_payment_4minute_finance');
		}
		$sql_chklog = "SELECT log_id 
							FROM 
								order_payment_4minute_finance_log 
							WHERE 
								orders_order_id = '".$order_id."' 
								AND credit_request_id='".$creditrequest_id."' 
								AND fin_status='".$fin_status."' 
							LIMIT 
								1";
		$ret_chklog = $db->query($sql_chklog);
		if($db->num_rows($ret_chklog)==0)
		{
			$insert_array						= array();
			$insert_array['log_datetime']		= 'now()';
			$insert_array['orders_order_id']	= $order_id;
			$insert_array['credit_request_id']	= $creditrequest_id;
			$insert_array['fin_status']			= $fin_status;
			$db->insert_from_array($insert_array,'order_payment_4minute_finance_log');
		}		
		if($fin_status=='VERIFIED') // if status is verified, then do the post order and send the emails related to order.
		{
			$update_sql = "UPDATE orders set order_status = 'NEW' WHERE order_id = $order_id AND order_status ='NOT_AUTH' LIMIT 1";
			$db->query($update_sql);
			
			// Stock Decrementing section over here
			do_PostOrderSuccessOperations($order_id);
		
			// calling function to send any mails 
			send_RequiredOrderMails($order_id);
		}
		
	}
	
	
}
?>
