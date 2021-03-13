<?php
require("functions/functions.php");
require("includes/session.php");
require("includes/price_display.php");
require("includes/urls.php");
require("config.php");

if(check_IndividualSslActive())
{
	$ecom_selfhttp = "https://";
}
else
{
	$ecom_selfhttp = "http://";
}

$checkouturl = $ecom_selfhttp.$ecom_hostname.'/checkout.html';
if ($_SERVER['HTTP_REFERER']!=$checkouturl)
{
	echo "Sorry !! an error occured - malformed data";
	exit;
}

function gatewaydatetime() 
{
	$ctime = strtotime('now');
	//$ctime = strtotime('+ 10 minutes'); // need to comment this line when making live
	date_default_timezone_set("UTC");
	$str = date('Y-m-d H:i:s P',$ctime);
	date_default_timezone_set("Europe/London");  
	return $str;
} 
function stripGWInvalidChars($strToCheck) {
	$toReplace = array("#","\\",">","<", "\"", "[", "]");
	$cleanString = str_replace($toReplace, "", $strToCheck);
	return $cleanString;
}

// Including the general settings array file
if(file_exists($image_path.'/settings_cache/general_settings.php'))
	include "$image_path/settings_cache/general_settings.php";
	
// Including the price display settings array file
if(file_exists($image_path.'/settings_cache/price_display_settings.php'))
	include "$image_path/settings_cache/price_display_settings.php";	

$ecom_common_settings 	= get_Common_Settings();
$ecom_tax_total_arr 	= $ecom_common_settings['tax'];// // Calling the function to get the tax for the current site






$order_id 	= trim($_POST['ps']);
$curr_code 	= trim($_POST['ccd']);
$pass_type 	= trim($_POST['tp']);

if($pass_type=='order') // case of coming for orders .. the desc is the order details
{
	$desc 			= "Payment for Order";
}
elseif($pass_type=='voucher') 				
{
	$desc 			= "Payment for Voucher";
}		
elseif($pass_type=='payonaccount')
{
	$desc 			= "Payment for Pay on Account";
}

// Get the details of orders from order table

$sql_ord = "SELECT * 
				FROM 
					orders 
				WHERE 
					order_id=$order_id 
					AND sites_site_id=$ecom_siteid 
				LIMIT 
					1";
$ret_ord = $db->query($sql_ord);
if($db->num_rows($ret_ord))
{
	$row_ord = $db->fetch_array($ret_ord);
}
else
{
	echo "Sorry !! an error occured";
	exit;
}

// get currency numeric code and currency id for the currency in which order is placed 
$sql_currs = "SELECT currency_id,curr_numeric_code 
				FROM 
					general_settings_site_currency 
				WHERE 
					sites_site_id = $ecom_siteid 
					AND curr_code ='".$curr_code."' 
				LIMIT 
					1";
$ret_currs = $db->query($sql_currs);
if($db->num_rows($ret_currs))
{
	$row_currs = $db->fetch_array($ret_currs);
	$curr_numeric_code = trim($row_currs['curr_numeric_code']);
	$curr_order_currencyid = $row_currs['currency_id'];
}

// Calling the function to get the details of default currency
$default_Currency_arr = get_default_currency();

$sitesel_curr		= $curr_order_currencyid;

// Get details of current currency
$current_currency_details = get_current_currency_details();

if($pass_type=='order') // case if country is textbox and also coming only for orders
{
	// so get the id of country
	$sql_country = "SELECT country_id 
						FROM 
							general_settings_site_country 
						WHERE 
							country_name='".($row_ord['order_country'])."' 
							AND sites_site_id = $ecom_siteid 
						LIMIT 
							1";
	$ret_country = $db->query($sql_country);
	if($db->num_rows($ret_country))
	{
		$row_country = $db->fetch_array($ret_country);
		$curr_pass_delcountry = $row_country['country_id'];
	}
	else
		$curr_pass_delcountry = 0;
		
}
if($curr_pass_delcountry)
{
	// Get the numeric code for country 
	$sql_numeric = "SELECT country_numeric_code 
						FROM 
							general_settings_site_country 
						WHERE 
							country_id='$curr_pass_delcountry'
							AND sites_site_id = $ecom_siteid 
						LIMIT 
							1";
	$ret_numeric = $db->query($sql_numeric);
	if($db->num_rows($ret_numeric))
	{
		$row_numeric = $db->fetch_array($ret_numeric);
		$numeric_deliverycountry_code = stripslashes($row_numeric['country_numeric_code']);
	}	
	 
}
else
	$numeric_deliverycountry_code = 826; // setting default country code to that of United kingdom




// Get payment methodid
$sql_pid = "SELECT paymethod_id FROM payment_methods WHERE paymethod_key ='".$row_ord['order_paymentmethod']."' LIMIT 1";
$ret_pid = $db->query($sql_pid);
if ($db->num_rows($ret_pid))
{
	$row_pid = $db->fetch_array($ret_pid);
}
if ($row_pid['paymethod_id'])
{
	$sql_method = "SELECT a.payment_methods_details_key,b.payment_methods_forsites_details_values 
					FROM 
						payment_methods_details a,payment_methods_forsites_details b 
					WHERE 
						a.payment_methods_paymethod_id = ".$row_pid['paymethod_id']."
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
}	


$CARDSAVE_submiturl = 'https://mms.cardsaveonlinepayments.com/Pages/PublicPages/PaymentForm.aspx';


$CARDSAVE_merchantid	= $paymethod_arr['CARDSAVE_MERCHANT_ID'];
$CARDSAVE_password		= $paymethod_arr['CARDSAVE_PASSWORD'];
$CARDSAVE_sharedkey		= $paymethod_arr['CARDSAVE_PRESHARED_KEY'];

if(check_IndividualSslActive())
{
	$CARDSAVE_callbackurl	= $ecom_selfhttp."$ecom_hostname/cardsave_return_process.php";
}
else
{
	$CARDSAVE_callbackurl	= "https://www.bsecured.co.uk/$ecom_hostname/cardsave_return_process.php";//"http://$ecom_hostname/cardsave_return_process.php";
}	

// Get the actual amount to be paid now
if ($row_ord["order_deposit_amt"]>0)
	$pass_paytotal = $row_ord["order_totalprice"]-$row_ord["order_deposit_amt"];
else
	$pass_paytotal = $row_ord["order_totalprice"];

$pass_paytotal = convertPrice_to_selectedCurrrency($pass_paytotal);	

$pass_paytotal = $pass_paytotal*100;



$cname = '';
if(trim($row_ord['order_custfname'])!='')
{
	$cname = trim($row_ord['order_custfname']);
}
if(trim($row_ord['order_custmname'])!='')
{
	$cname .= ' '.trim($row_ord['order_custmname']);
}
if(trim($row_ord['order_custsurname'])!='')
{
	$cname .= ' '.trim($row_ord['order_custsurname']);
}

$address1= $address2 = $address3 = $address4 = '';

if(trim($row_ord['order_buildingnumber'])!='')
{
	$address1 = trim($row_ord['order_buildingnumber']);
}
if(trim($row_ord['order_street'])!='')
{
	$address2 = trim($row_ord['order_street']);
}

$order_id = '{'.$order_id.'-'.$pass_type.'-'.Get_session_Id_from().'}';

//Strip Invalid characters on the following fields for use in HashString and Form Post
$CustomerName 	= stripGWInvalidChars($cname);
$Address1 		= stripGWInvalidChars($address1);
$Address2 		= stripGWInvalidChars($address2);
$Address3 		= stripGWInvalidChars($address3);
$Address4 		= stripGWInvalidChars($address4);
$City 			= stripGWInvalidChars(trim($row_ord['order_city']));
$State 			= stripGWInvalidChars(trim($row_ord['order_state']));
$PostCode 		= stripGWInvalidChars(trim($row_ord['order_custpostcode']));
$EmailAddress 	= stripGWInvalidChars(trim($row_ord['order_custemail']));
$PhoneNumber 	= stripGWInvalidChars(trim($row_ord['order_custphone']));
$desc			= stripGWInvalidChars($desc);

$passdate 		= gatewaydatetime();
$falseval		= 'false';
$trueval		= 'true';
$saleval		= 'SALE';
//Generate Hashstring - use combination of post variables and variables stripped of invalid characters
$HashString="PreSharedKey=" . $CARDSAVE_sharedkey;
$HashString=$HashString . '&MerchantID=' . $CARDSAVE_merchantid;
$HashString=$HashString . '&Password=' . $CARDSAVE_password;
$HashString=$HashString . '&Amount=' . $pass_paytotal;
$HashString=$HashString . '&CurrencyCode=' . $curr_numeric_code;
$HashString=$HashString . '&EchoAVSCheckResult='. 'true';
$HashString=$HashString . '&EchoCV2CheckResult='. 'true';
$HashString=$HashString . '&EchoThreeDSecureAuthenticationCheckResult='. 'true';
$HashString=$HashString . '&EchoCardType='. 'true';
$HashString=$HashString . '&OrderID=' . $order_id;
$HashString=$HashString . '&TransactionType='. $saleval;
$HashString=$HashString . '&TransactionDateTime='. $passdate;
$HashString=$HashString . '&CallbackURL=' . $CARDSAVE_callbackurl;
$HashString=$HashString . '&OrderDescription=' . $desc;
$HashString=$HashString . '&CustomerName=' . $CustomerName;
$HashString=$HashString . '&Address1=' . $Address1;
$HashString=$HashString . '&Address2=' . $Address2;
$HashString=$HashString . '&Address3=' . $Address3;
$HashString=$HashString . '&Address4=' . $Address4;
$HashString=$HashString . '&City=' . $City;
$HashString=$HashString . '&State=' . $State;
$HashString=$HashString . '&PostCode=' . $PostCode;
$HashString=$HashString . '&CountryCode=' . $numeric_deliverycountry_code;
$HashString=$HashString . '&EmailAddress=' . $EmailAddress;
$HashString=$HashString . '&PhoneNumber=' . $PhoneNumber;
$HashString=$HashString . '&EmailAddressEditable='. 'false';
$HashString=$HashString . '&PhoneNumberEditable='. 'false';
$HashString=$HashString . "&CV2Mandatory=". 'true';
$HashString=$HashString . "&Address1Mandatory=". 'true';
$HashString=$HashString . "&CityMandatory=". 'true';
$HashString=$HashString . "&PostCodeMandatory=". 'true';
$HashString=$HashString . "&StateMandatory=". 'false';
$HashString=$HashString . "&CountryMandatory=". 'true';
$HashString=$HashString . "&ResultDeliveryMethod=" . 'POST';
$HashString=$HashString . "&ServerResultURL=" . '';
$HashString=$HashString . "&PaymentFormDisplaysResult=". 'false';

//echo $HashString;

//Encode HashDigest using SHA1 encryption (and create HashDigest for later use) - This is used as a checksum by the gateway to ensure form post hasn't been tampered with.
$HashDigest = sha1($HashString); 
?>
<style type='text/css'>
.cardsavegateway_msg_cls{
	text-align:center;
	padding-top:50px;
	font-size:14px;
	font-weight:bold;
	color:#FF0000;
}
</style>
<form name="cardsaveform" id="cardsaveform" method="post" action="<?php echo $CARDSAVE_submiturl?>" target="_self">
<input type="hidden" name="HashDigest" value="<?php echo $HashDigest; ?>" />
<input type="hidden" name="MerchantID" value="<?php echo $CARDSAVE_merchantid; ?>" />
<input type="hidden" name="Amount" value="<?php echo $pass_paytotal; ?>" />                                       
<input type="hidden" name="CurrencyCode" value="<?php echo $curr_numeric_code; ?>" />
<input type="hidden" name="EchoAVSCheckResult" value="true" />
<input type="hidden" name="EchoCV2CheckResult" value="true" />
<input type="hidden" name="EchoThreeDSecureAuthenticationCheckResult" value="true" />
<input type="hidden" name="EchoCardType" value="true" />
<input type="hidden" name="OrderID" value="<?php echo $order_id; ?>" />
<input type="hidden" name="TransactionType" value="SALE" />
<input type="hidden" name="TransactionDateTime" value="<? echo $passdate; ?>" />
<input type="hidden" name="CallbackURL" value="<?php echo $CARDSAVE_callbackurl; ?>" />
<input type="hidden" name="OrderDescription" value="<?php echo $desc; ?>" />
<input type="hidden" name="CustomerName" value="<?php echo $CustomerName; ?>" />
<input type="hidden" name="Address1" value="<?php echo $Address1; ?>" />
<input type="hidden" name="Address2" value="<?php echo $Address2; ?>" />
<input type="hidden" name="Address3" value="<?php echo $Address3; ?>" />
<input type="hidden" name="Address4" value="<?php echo $Address4; ?>" />
<input type="hidden" name="City" value="<?php echo $City; ?>" /> 
<input type="hidden" name="State" value="<?php echo $State; ?>" />
<input type="hidden" name="PostCode" value="<?php echo $PostCode; ?>" />
<input type="hidden" name="CountryCode" value="<?php echo $numeric_deliverycountry_code; ?>" />
<input type="hidden" name="EmailAddress" value="<?php echo $EmailAddress; ?>" />
<input type="hidden" name="PhoneNumber" value="<?php echo $PhoneNumber; ?>" />
<input type="hidden" name="EmailAddressEditable" value="false" />
<input type="hidden" name="PhoneNumberEditable" value="false" />
<input type="hidden" name="CV2Mandatory" value="true" />
<input type="hidden" name="Address1Mandatory" value="true" />
<input type="hidden" name="CityMandatory" value="true" />
<input type="hidden" name="PostCodeMandatory" value="true" />
<input type="hidden" name="StateMandatory" value="false" />
<input type="hidden" name="CountryMandatory" value="true" />
<input type="hidden" name="ResultDeliveryMethod" value="POST" />
<input type="hidden" name="ServerResultURL" value="" />
<input type="hidden" name="PaymentFormDisplaysResult" value="false" />
<input type="hidden" name="ThreeDSecureCompatMode" value="false" />

</form>
<div class='cardsavegateway_msg_cls'>... Loading payment gateway ..<br><br> Please do not refresh or press the back button in your browser.</div>
<script type='text/javascript'>
document.cardsaveform.submit();
</script>
