<?
	//if($ecom_siteid==84) // dennis client
	//	$gw_type = 'PROTX_VSP';
	//else
		$gw_type = 'PROTX';
// Get the payment method id for protx
	$sql_paymethod = "SELECT paymethod_id
							FROM
								payment_methods
							WHERE
								paymethod_key = '".$gw_type."'
							LIMIT
								1";
	$ret_paymethod = $db->query($sql_paymethod);
	if ($db->num_rows($ret_paymethod))
	{
		$row_paymethod 	= $db->fetch_array($ret_paymethod);
		$paymethod_id	= $row_paymethod['paymethod_id'];
	}
	// Get the details realated to protx for current site
	$sql_method = "SELECT a.payment_methods_details_key,b.payment_methods_forsites_details_values
					FROM
						payment_methods_details a,payment_methods_forsites_details b
					WHERE
						a.payment_methods_paymethod_id = $paymethod_id
						AND a.payment_method_details_id = b.payment_methods_details_payment_method_details_id
						AND b.sites_site_id = $ecom_siteid";
	$ret_method = $db->query($sql_method);
	if ($db->num_rows($ret_method))
	{
		while($row_method = $db->fetch_array($ret_method))
		{
			$paymethod_arr[$row_method['payment_methods_details_key']] = $row_method['payment_methods_forsites_details_values'];
		}
	}
	$Vendor 	 = $paymethod_arr['VENDOR_ID'];

	if($cursrc=='voucher') // case of coming for voucher
	{
		// Get the relevant details related to payment from order_payment_main table
		$sql_payment = "SELECT vPSTxId as order_vPSTxId,vendorTxCode as order_vendorTxCode,
							securityKey as order_securityKey,txAuthNo as order_txAuthNo
							FROM
								gift_vouchers_payment
							WHERE
								gift_vouchers_voucher_id =".$row_vouch['voucher_id']."
							LIMIT
								1";
	}
	else // case of order
	{

		// Get the relevant details related to payment from order_payment_main table
		$sql_payment = "SELECT *
							FROM
								order_payment_main
							WHERE
								orders_order_id =".$row_ord['order_id']."
							LIMIT
								1";
		
	}
	$ret_payment = $db->query($sql_payment);
	if($db->num_rows($ret_payment))
	{
		$row_payment = $db->fetch_array($ret_payment);
	}
	include ("includes/protx/init-includes.php");

	// Set some variables
	if($curmod=='RELEASE') // case of release / repeat
	{
		$TargetURL = $ReleaseURL;					// Specified in init-includes.php
	}
	elseif ($curmod=='REPEAT')
	{
		$TargetURL 	= $RepeatURL;					// Specified in init-includes.php
	}
	elseif ($curmod=='AUTHORISE')
	{
		$TargetURL 	= $AuthoriseURL;				// Specified in init-includes.php
	} elseif ($curmod=='CANCEL')
	{
		$TargetURL 	= $CancelURL;					// Specified in init-includes.php
	}
	else
	{
		$TargetURL = $AbortURL;						// Specified in init-includes.php
	}

	$VerifyServer = $Verify;						// Specified in init-includes.php


	/**************************************************************************************************
		Set all the required outgoing properties for the initial HTTPS post to the VPS
	**************************************************************************************************/
	//$ProtocolVersion=2.22;
	if($cursrc=='voucher') // case of coming for voucher
	{
		$sql_custs = "SELECT voucher_fname,voucher_mname,voucher_surname
						FROM
							gift_vouchers_customer
						WHERE
							voucher_id =".$row_vouch['voucher_id']."
						LIMIT
							1";
		$ret_custs = $db->query($sql_custs);
		if ($db->num_rows($ret_custs))
		{
			$row_custs 	= $db->fetch_array($ret_custs);
		}
	}
	// Decide the required operation based on the value given for curmod variable
	switch ($curmod)
	{
		case 'REPEAT':
			$RepeatVendorTxCode 		= "Repeat" . (rand(0,320000) * rand(0,320000));
			if($cursrc=='voucher') // case of coming for voucher
			{
				$desc		= $row_vouch['voucher_id']." ".$row_custs['voucher_fname']." ".$row_custs['voucher_mname']." ".$row_custs['voucher_surname'];
			}
			else
			{
				$desc						= $row_ord['order_id']." ".$row_ord['order_custfname']." ".$row_ord['order_custmname']." ".$row_ord['order_custsurname'];
			}

			if($cursrc=='voucher') // case of coming for voucher
			{
				//check whether partial payment is made (product deposit case)
				$rem_amt = $row_vouch['voucher_value']-$row_vouch['voucher_refundamt'];

				$pass_tot		= print_price_selected_currency($rem_amt,$row_vouch['voucher_curr_rate'],'',true); // only the paid amount is set to variable
				$def_curr 		= $row_vouch['voucher_curr_code'];
			}
			else
			{
				//check whether partial payment is made (product deposit case)
				if ($row_ord['order_deposit_amt']>0)
				{
					if($row_ord['order_deposit_cleared']==0)
						$rem_amt	= $row_ord['order_deposit_amt']-$row_ord['order_totalauthorizeamt'];
					else
					{
						if($row_ord['voucher_totalauthorizeamt']>0)// case if authorize amount exists
							$rem_amt	= ($row_ord['voucher_totalauthorizeamt'] - $row_ord['order_refundamt'])- $row_ord['order_deposit_amt'];
						else
							$rem_amt	= ($row_ord['order_totalprice'] - $row_ord['order_refundamt'])-$row_ord['order_deposit_amt'];
					}
				}
				else
				{
					if($row_ord['voucher_totalauthorizeamt']>0)// case if authorize amount exists
						$rem_amt 	= ($row_ord['voucher_totalauthorizeamt'] - $row_ord['order_refundamt']);
					else
						$rem_amt 	= ($row_ord['order_totalprice'] - $row_ord['order_refundamt']);
				}
				$pass_tot		= print_price_selected_currency($rem_amt,$row_ord['order_currency_convertionrate'],'',true); // only the paid amount is set to variable

				$def_curr 		= $row_ord['order_currency_code'];
			}

			// Create an array of values to send
			$data = array (
					'VPSProtocol' 			=> $ProtocolVersion, 					// Protocol version (specified in init-includes.php)
					'TxType' 				=> $curmod,								// Transaction type
					'Vendor' 				=> $Vendor,								// Vendor name (specified in init-protx.php)
					'VendorTxCode' 			=> $RepeatVendorTxCode,					// Unique refund transaction code (generated by vendor)
					'Amount' 				=> $pass_tot,							// Value of refund (supplied by vendor)
					'Currency' 				=> $def_curr,							// Currency of order (default specified in init-protx.php)
					'Description' 			=> $desc,								// Description of order
					'RelatedVPSTxId' 		=> $row_payment['order_vPSTxId'],		// Original VPSTxId of order
					'RelatedVendorTxCode' 	=> $row_payment['order_vendorTxCode'],	// Original VendorTxCode
					'RelatedSecurityKey' 	=> $row_payment['order_securityKey'],	// Original Security Key
					'RelatedTxAuthNo' 		=> $row_payment['order_txAuthNo']		// Original Transaction authorisation number
				);
		break;
		case 'RELEASE':
			// Create an array of values to send
			$data = array (
					'VPSProtocol' 	=> $ProtocolVersion, 						// Protocol version (specified in init-includes.php)
					'TxType' 		=> $curmod,									// Transaction type
					'Vendor' 		=> $Vendor,									// Vendor name (specified in init-protx.php)
					'VendorTxCode' 	=> $row_payment['order_vendorTxCode'],		//VendorTxCode,// Unique Deffered on Preaturh transaction code (generated by vendor)
					'VPSTxId' 		=> $row_payment['order_vPSTxId'],			// Original VPSTxID of order
					'SecurityKey' 	=> $row_payment['order_securityKey'],		// Original Security Key
					'TxAuthNo' 		=> $row_payment['order_txAuthNo']			// Original Transaction authorisation number
				);
		break;
		case 'ABORT':
			// Create an array of values to send
			$data = array (
					'VPSProtocol' 	=> $ProtocolVersion, 					// Protocol version (specified in init-includes.php)
					'TxType' 		=> $curmod,								// Transaction type
					'Vendor' 		=> $Vendor,								// Vendor name (specified in init-protx.php)
					'VendorTxCode' 	=> $row_payment['order_vendorTxCode'],	//VendorTxCode,// Unique Deffered on Preaturh transaction code (generated by vendor)
					'VPSTxId' 		=> $row_payment['order_vPSTxId'],		// Original VPSTxID of order
					'SecurityKey' 	=> $row_payment['order_securityKey'],	// Original Security Key
					'TxAuthNo' 		=> $row_payment['order_txAuthNo']		// Original Transaction authorisation number
				);
		break;
		case 'CANCEL':
			// Create an array of values to send
			$data = array (
					'VPSProtocol' 	=> $ProtocolVersion, 					// Protocol version (specified in init-includes.php)
					'TxType' 		=> $curmod,								// Transaction type
					'Vendor'		=> $Vendor,								// Vendor name (specified in init-protx.php)
					'VendorTxCode' 	=> $row_payment['order_vendorTxCode'],	//VendorTxCode,// Unique Deffered on Preaturh transaction code (generated by vendor)
					'VPSTxId' 		=> $row_payment['order_vPSTxId'],		// Original VPSTxID of order
					'SecurityKey' 	=> $row_payment['order_securityKey']	// Original Security Key
				);
		break;
		case 'AUTHORISE':
			$AUTHORISEVendorTxCode = "AUTHORISE" . (rand(0,320000) * rand(0,320000));

			if($cursrc=='voucher') // case of coming for voucher
			{

				$desc		= $row_vouch['voucher_id']." ".$row_custs['voucher_fname']." ".$row_custs['voucher_mname']." ".$row_custs['voucher_surname'];

				//check whether partial payment is made (product deposit case)
				$rem_amt 	= $row_vouch['voucher_value']-$row_vouch['voucher_refundamt'];

				$pass_tot		= print_price_selected_currency($rem_amt,$row_ord['order_currency_convertionrate'],'',true); // only the paid amount is set to variable

				$def_curr 		= $row_vouch['voucher_curr_code'];
			}
			else
			{
				$desc		= $row_ord['order_id']." ".$row_ord['order_custfname']." ".$row_ord['order_custmname']." ".$row_ord['order_custsurname'];

				//check whether partial payment is made (product deposit case)
				if ($row_ord['order_deposit_amt']>0)
				{
					if($row_ord['order_deposit_cleared']==0)
						$rem_amt	= $row_ord['order_deposit_amt'];
					else
						$rem_amt	= ($row_ord['order_totalprice'] - $row_ord['order_refundamt'])-$row_ord['order_deposit_amt'];
				}
				else
				{
					$rem_amt 	= ($row_ord['order_totalprice'] - $row_ord['order_refundamt']);
				}
				$pass_tot		= print_price_selected_currency($rem_amt,$row_ord['order_currency_convertionrate'],'',true); // only the paid amount is set to variable

				$def_curr 		= $row_ord['order_currency_code'];
			}
			// Create an array of values to send
			$data = array (
					'VPSProtocol' 			=> $ProtocolVersion, 					// Protocol version (specified in init-includes.php)
					'TxType' 				=> $curmod,								// Transaction type
					'Vendor' 				=> $Vendor,								// Vendor name (specified in init-protx.php)
					'VendorTxCode' 			=> $AUTHORISEVendorTxCode,				// Unique refund transaction code (generated by vendor)
					'Amount' 				=> $auth_amt,				// Value of refund (supplied by vendor)
					'Currency' 				=> $def_curr,							// Currency of order (default specified in init-protx.php)
					'Description' 			=> $desc,								// Description of order
					'RelatedVPSTxId' 		=> $row_payment['order_vPSTxId'],		// Original VPSTxId of order
					'RelatedVendorTxCode' 	=> $row_payment['order_vendorTxCode'],	// Original VendorTxCode
					'RelatedSecurityKey' 	=> $row_payment['order_securityKey']	// Original Security Key
				);
		break;
	}
	//print "<pre>".var_dump($data)."</pre>";
	// Format values as url-encoded key=value pairs
	$data = formatData($data);

	/**************************************************************************************************
		Send the post to the target URL
			if anything goes wrong with the connection process:
			- ErrorLevel will be non-zero;
			- ErrorMessage will be set to describe the problem;
	**************************************************************************************************/
	//print "<pre>".var_dump($data)."</pre>";
	//echo "<br><br>$TargetURL";
	//exit;

	$response = requestPost($TargetURL, $data);

	/**************************************************************************************************
		Check the error level and act appropriately
	'*************************************************************************************************/

	$baseStatus = array_shift(split(" ",$response["Status"]));
//	$baseStatus="OK";
	
	if($baseStatus!="OK")
	{
		print_r($response);
		exit;
	}	
		
	switch($baseStatus) {

		case 'OK':
			/**************************************************************************************************
				Release successful, so store the AuthCode in your database here.
			**************************************************************************************************/
			$ret_status = "$curmod Successful...<BR><BR>";
				if($curmod == 'REPEAT')
				{
					if($cursrc=='voucher') // case of coming for voucher
					{
						//check whether there is a record for this voucher_ir id in the table orders_repeatdata
						$sql_check = "SELECT gift_vouchers_voucher_id
										FROM
											gift_voucher_payment_repeatdata
										WHERE
											gift_vouchers_voucher_id=".$row_vouch['voucher_id']."
										LIMIT
											1";;
						$ret_check = $db->query($sql_check);
						if ($db->num_rows($ret_check))
						{
							$update_array							= array();
							$update_array['voucher_vPSTxId']			= $response['VPSTxId'];
							$update_array['voucher_txAuthNo']			= $response['TxAuthNo'];
							$update_array['voucher_securityKey']		= $response['SecurityKey'];
							$update_array['voucher_vendorTxCode']		= $RepeatVendorTxCode;
							$db->update_from_array($update_array,'gift_voucher_payment_repeatdata',array('gift_vouchers_voucher_id'=>$row_vouch['voucher_id']));

						}
						else
						{
							$insert_array								= array();
							$insert_array['gift_vouchers_voucher_id']	= $row_ord['order_id'];
							$insert_array['voucher_vPSTxId']			= $response['VPSTxId'];
							$insert_array['voucher_txAuthNo']			= $response['TxAuthNo'];
							$insert_array['voucher_securityKey']		= $response['SecurityKey'];
							$insert_array['voucher_vendorTxCode']		= $RepeatVendorTxCode;
							$db->insert_from_array($insert_array,'gift_voucher_payment_repeatdata');
						}
					}
					else
					{
						//check whether there is a record for this order id in the table orders_repeatdata
						$sql_check = "SELECT orders_order_id
										FROM
											order_payment_repeatdata
										WHERE
											orders_order_id=".$row_ord['order_id']."
										LIMIT
											1";;
						$ret_check = $db->query($sql_check);
						if ($db->num_rows($ret_check))
						{
							$update_array							= array();
							$update_array['order_vPSTxId']			= $response['VPSTxId'];
							$update_array['order_txAuthNo']			= $response['TxAuthNo'];
							$update_array['order_securityKey']		= $response['SecurityKey'];
							$update_array['order_vendorTxCode']		= $RepeatVendorTxCode;
							$db->update_from_array($update_array,'order_payment_repeatdata',array('orders_order_id'=>$row_ord['order_id']));

						}
						else
						{
							$insert_array							= array();
							$insert_array['orders_order_id']		= $row_ord['order_id'];
							$insert_array['order_vPSTxId']			= $response['VPSTxId'];
							$insert_array['order_txAuthNo']			= $response['TxAuthNo'];
							$insert_array['order_securityKey']		= $response['SecurityKey'];
							$insert_array['order_vendorTxCode']		= $RepeatVendorTxCode;
							$db->insert_from_array($insert_array,'order_payment_repeatdata');
						}
					}
				}
				if($curmod == 'AUTHORISE')
				{
					if($cursrc=='voucher') // case of coming for voucher
					{
						$insert_array								= array();
						$insert_array['gift_vouchers_voucher_id']	= $row_ord['order_id'];
						$insert_array['voucher_authorizedate']		= 'now()';
						$insert_array['voucher_vPSTxId']			= $response['VPSTxId'];
						$insert_array['voucher_txAuthNo']			= $response['TxAuthNo'];
						$insert_array['voucher_securityKey']		= $response['SecurityKey'];
						$insert_array['voucher_vendorTxCode']		= $AUTHORISEVendorTxCode;
						$insert_array['voucher_avscv2']				= $response['AVSCV2'];
						$insert_array['voucher_CV2Result']			= $response['CV2Result'];
						$db->insert_from_array($insert_array,'gift_voucher_payment_authorizedata');
					}
					else
					{
						$insert_array							= array();
						$insert_array['orders_order_id']		= $row_ord['order_id'];
						$insert_array['order_authorizedate']	= 'now()';
						$insert_array['order_vPSTxId']			= $response['VPSTxId'];
						$insert_array['order_txAuthNo']			= $response['TxAuthNo'];
						$insert_array['order_securityKey']		= $response['SecurityKey'];
						$insert_array['order_vendorTxCode']		= $AUTHORISEVendorTxCode;
						$insert_array['order_avscv2']			= $response['AVSCV2'];
						$insert_array['order_CV2Result']		= $response['CV2Result'];
						$db->insert_from_array($insert_array,'order_payment_authorizedata');
					}
				}
			break; // End case 'OK'


		case 'INVALID';
		case 'NOTAUTHED';

			/**************************************************************************************************
				Unable to authenticate you or find the transaction, or the data provided is invalid. If the Deferred
				payment was already released, an INVALID response is returned. See StatusDetail for more information.
			**************************************************************************************************/

			//Write a message to the browser informing the admin of failure
			$ret_status = "$curmod Not Authorised by the bank.";

			break; // End case 'NOTAUTHED'


		case 'MALFORMED';
			/**************************************************************************************************
				Input message was malformed � normally will only occur during development. The StatusDetail
				(next field) will give more information
			**************************************************************************************************/

			//Write a message to the browser informing the admin of failure
			$ret_status = "The $curmod request sent to PROTX was Malformed.<BR><BR>
							Error: " . $response['StatusDetail'] ;

			break; // End case 'MALFORMED'

		case 'CANCEL':
		case 'ABORT':
		case 'ERROR':
		default: // If it's not any of the above
			/**************************************************************************************************
				The VPS returns either ABORT or ERROR if the post was okay but process was interrupted or
				failed.  You may wish to update your database to reflect this.
			**************************************************************************************************/

				/*
					The ABORT or ERROR message only occurs when something goes wrong at the VPS.
					You may wish to mail PROTX support here to inform them, or flag up something for
					an operator at your site.
				*/

			//Write a message to the browser informing the admin of failure
			$ret_status = "The PROTX VPS returned an abort or error message.  The $curmod was unsuccessful.";


			break; // End default
	} // END switch($baseStatus)
?>
