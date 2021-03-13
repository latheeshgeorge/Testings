<?
	// Get the payment method id for protx 
	$sql_paymethod = "SELECT paymethod_id 
							FROM 
								payment_methods 
							WHERE 
								paymethod_key = 'PROTX' 
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
	
	$Vendor 			= $paymethod_arr['VENDOR_ID'];

	// Set some variables
	include ("includes/protx/init-includes.php");
	$TargetURL 			= $RefundURL;	// Specified in init-includes.php
	$VerifyServer 		= $Verify;	// Specified in init-includes.php<br />	
	
	if($cur_mod=='voucher') // case of coming to refund vouchers
	{
		$sql_pay = "SELECT vPSTxId as order_vPSTxId,securityKey as order_securityKey,txAuthNo as order_txAuthNo,vendorTxCode as order_vendorTxCode  
							FROM 
								gift_vouchers_payment  
							WHERE 
								gift_vouchers_voucher_id = $voucher_id 
							LIMIT 
								1";
	}
	else  // coming for order refund
	{
		// Get the payment related details for current order
		$sql_pay = "SELECT order_vPSTxId,order_securityKey,order_txAuthNo,order_vendorTxCode  
							FROM 
								order_payment_main 
							WHERE 
								orders_order_id = $order_id 
							LIMIT 
								1";
	}	
	$ret_pay = $db->query($sql_pay);
	if ($db->num_rows($ret_pay))
		$row = $db->fetch_array($ret_pay);
			
	/*******************************************/
	
	// ************* Generate a random refund transaction code -- you will need to replace this with your own system
	$RefundVendorTxCode = "Refund" . (rand(0,320000) * rand(0,320000));
	
	// Set order description
	if($cur_mod=='voucher') // case of coming to refund vouchers
	{
		$Description = "Refunding Gift Voucher ".$voucher_id;
	}
	else 
	{
		$Description = "Refunding Order ".$order_id;	
	}
	
	/**************************************************************************************************
		Set all the required outgoing properties for the initial HTTPS post to the VPS
	**************************************************************************************************/
	
	//Check whether there is data in the table order_repeatdata table. If REPEAT is done for current order
	//then this table holds the value for vPSTxId, txAuthNo, securityKey which is return back after transaction.
	//If in case of refund this value should be used otherwise the value for this fields from orders table should
	//be used
	if($cur_mod=='voucher') // case of coming to refund vouchers
	{
		$sql_check = "SELECT voucher_vPSTxId as order_vPSTxId,voucher_txAuthNo as order_txAuthNo,
						voucher_securityKey as order_securityKey,voucher_vendorTxCode as order_vendorTxCode 
					FROM 
						gift_voucher_payment_repeatdata 
					WHERE 
						gift_vouchers_voucher_id=$voucher_id 
					LIMIT 
						1";
		$ret_check = $db->query($sql_check);
	}
	else 
	{
	
		$sql_check = "SELECT order_vPSTxId,order_txAuthNo,order_securityKey,order_vendorTxCode 
					FROM 
						order_payment_repeatdata 
					WHERE 
						orders_order_id=$order_id 
					LIMIT 
						1";
		$ret_check = $db->query($sql_check);
	}
	if ($db->num_rows($ret_check))
	{
		$row_check 		= $db->fetch_array($ret_check);
		$vpstxtid 		= $row_check['order_vPSTxId'];
		$securitykey	= $row_check['order_securityKey'];
		$txtauthno		= $row_check['order_txAuthNo'];
		$txtvendorcode 	= $row_check['order_vendorTxCode'];
	}
	else
	{
		if($cur_mod=='voucher') // case of coming to refund vouchers
		{
			$sql_check = "SELECT voucher_vPSTxId as order_vPSTxId,voucher_txAuthNo as order_txAuthNo,
								voucher_securityKey as order_securityKey,voucher_vendorTxCode as order_vendorTxCode  
							FROM 
								gift_voucher_payment_authorizedata 
							WHERE 
								gift_vouchers_voucher_id=$voucher_id 
							LIMIT 
								1";
		}
		else 
		{
			$sql_check = "SELECT order_vPSTxId,order_txAuthNo,order_securityKey,order_vendorTxCode  
							FROM 
								order_payment_authorizedata 
							WHERE 
								orders_order_id=$order_id 
							LIMIT 
								1";
		}
		$ret_check = $db->query($sql_check);
		if ($db->num_rows($ret_check))
		{
			$row_check 		= $db->fetch_array($ret_check);
			$vpstxtid 		= $row_check['order_vPSTxId'];
			$securitykey	= $row_check['order_securityKey'];
			$txtauthno		= $row_check['order_txAuthNo'];
			$txtvendorcode 	= $row_check['order_vendorTxCode'];
		}
		else
		{
			$vpstxtid 		= $row['order_vPSTxId'];
			$securitykey	= $row['order_securityKey'];
			$txtauthno		= $row['order_txAuthNo'];
			$txtvendorcode 	= $row['order_vendorTxCode'];
		}
	}
			
		$refund			= $ref_amt;
		if($cur_mod=='voucher') // case of coming to refund vouchers
		{
			$def_curr 		= $row_vouch['voucher_curr_code'];
		}
		else 
		{
			$def_curr 		= $row_ord['order_currency_code'];	
		}
		// Create an array of values to send
		$data = array (
				'VPSProtocol' 			=> $ProtocolVersion, 				// Protocol version (specified in init-includes.php)
				'TxType' 				=> "REFUND",							// Transaction type 
				'Vendor' 				=> $Vendor,							// Vendor name (specified in init-protx.php)
				'VendorTxCode' 			=> $RefundVendorTxCode,			// Unique refund transaction code (generated by vendor)
				'Amount' 				=> $refund,							// Value of refund (supplied by vendor)
				'Currency' 				=> $def_curr,					// Currency of order (default specified in init-protx.php)
				'Description' 			=> $Description,					// Description of order 
				'RelatedVPSTxID' 		=> $vpstxtid,					// Original VPSTxID of order
				'RelatedVendorTxCode' 	=> $txtvendorcode,	// Original VendorTxCode
				'RelatedSecurityKey' 	=> $securitykey,			// Original Security Key
				'RelatedTxAuthNo'		=> $txtauthno					// Original Transaction authorisation number
			);
	
		// Format values as url-encoded key=value pairs
		$data = formatData($data);
		
		/**************************************************************************************************
			Send the post to the target URL
				if anything goes wrong with the connection process: 
				- ErrorLevel will be non-zero;
				- ErrorMessage will be set to describe the problem;
		**************************************************************************************************/
		$response = requestPost($TargetURL, $data);
		
		
		/**************************************************************************************************
			Check the error level and act appropriately
		'*************************************************************************************************/
		
		$baseStatus = array_shift(split(" ",$response["Status"]));
	//	$baseStatus="OK";
		
		switch($baseStatus) {
		
			case 'OK':
				/**************************************************************************************************
					Refund successful, so store the AuthCode in your database here.
				**************************************************************************************************/
				
				$ret_status = "Refund successful...<BR><BR>AuthNo=" . $response['TxAuthNo'] . "<BR>VPSTxId=" . $response['VPSTxId'];

				/*//echo "successful";
				echo "VPSProtocol " . $data['VPSProtocol'] . "<br>";
				echo "TxType" . $data['TxType'] . "<br>";														// Transaction type 
				echo "Vendor " . $Vendor . "<br>";														// Vendor name (specified in init-protx.php)
				echo "VendorTxCode " . $RefundVendorTxCode . "<br>";					// Unique refund transaction code (generated by vendor)
				echo "Amount " . $_POST['refundAmnt'] . "<br>";										// Value of refund (supplied by vendor)
				echo "Currency " . $DefaultCurrency . "<br>";									// Currency of order (default specified in init-protx.php)
				echo "Description " . $Description . "<br>";									// Description of order 
				echo "RelatedVPSTxID" . $row['vPSTxId'] . "<br>";						// Original VPSTxID of order
				echo "RelatedVendorTxCode " . $row['vendorTxCode'] . "<br>";	// Original VendorTxCode
				echo "RelatedSecurityKey " . $row['securityKey'] . "<br>";		// Original Security Key
				echo "RelatedTxAuthNo " . $row['txAuthNo'] . "<br>";*/
		
				break; // End case 'OK'
		
		
			case 'NOTAUTHED';
				/**************************************************************************************************
					Status was not OK, so whilst communication was successful, something was wrong with the POST
					Display information about the error on screen and update your database with this information
				**************************************************************************************************/
		
					/*
						The refund has NOT been authorised, so update your database to reflect that and
					*/
		
				//Write a message to the browser informing the admin of failure
				$ret_status = "The Refund was Not Authorised by the bank.";
		
				break; // End case 'NOTAUTHED'
		
		
			case 'MALFORMED';
				/**************************************************************************************************
					The refund post sent by your site contained incorrect or unrecognisable data.
					You may wish to update your database to reflect this.
				**************************************************************************************************/
		
					/*
						The refund request was malformed.  Update your database to reflect this, or you
						may wish to try to resubmit the request here.
					*/
		
				//Write a message to the browser informing the admin of failure
				$ret_status = "The Refund request sent to PROTX was Malformed.<BR><BR>
								Error: " . $response['StatusDetail'] ;
						
				break; // End case 'MALFORMED'
		
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
				$ret_status = "The PROTX VPS returned an abort or error message.  The refund was unsuccessful.<br><br>
								".$response["Status"] . ": " . $response["StatusDetail"];
			
		
				break; // End default
		} // END switch($baseStatus)
?>