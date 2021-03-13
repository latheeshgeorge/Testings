<?
/*****************************************************
 Include this single file at the top of each Web page
 to include all configuration files
*****************************************************/

// ***** DO NOT REMOVE THESE FILES BELOW *****
include ("includes/protx/init-protx.php");
include ("includes/protx/init-functions.php");

// Check whether the site is in testing mode
if ($ecom_testing==1)
{
	$TestSite=1;
	$LiveSite=0;
}	
else
{
	$TestSite=0;
	$LiveSite=1;
}

// ***** Add additional files here if you wish *****

/************************************************
 Do not modify the lines below.  They set up
 URLs and parameters for the VPS.
************************************************/

//A few standard definitions

// End of line default
$eoln = chr(13) . chr(10);

$ProtocolVersion 		= "2.22";	//"2.20";
$DefaultCompletionURL 	= "http://" . $InternalIPAddress . "/" . $DefaultOrderCompletePath;
$DefaultNotAuthedURL 	= "http://" . $InternalIPAddress . "/" . $DefaultNotAuthedPath;
$DefaultAbortURL 		= "http://" . $InternalIPAddress . "/" . $DefaultAbortPath;
$DefaultErrorURL 		= "http://" . $InternalIPAddress . "/" . $DefaultErrorPath;


/************************************************
 Information and URLs for the test site
************************************************/
if ($TestSite)
{
  $Verify		= false;
  /*$PurchaseURL	= "https://ukvpstest.protx.com/vspgateway/service/vspdirect-register.vsp";
  $RefundURL	= "https://ukvpstest.protx.com/vspgateway/service/refund.vsp";
  $ReleaseURL	= "https://ukvpstest.protx.com/vspgateway/service/release.vsp";
  $RepeatURL	= "https://ukvpstest.protx.com/vspgateway/service/repeat.vsp";
  $AbortURL		= "https://ukvpstest.protx.com/vspgateway/service/abort.vsp";
  $AuthoriseURL = "https://ukvpstest.protx.com/vspgateway/service/authorise.vsp";
  $CancelURL	= "https://ukvpstest.protx.com/vspgateway/service/cancel.vsp";*/
  
  $PurchaseURL	= "https://test.sagepay.com/gateway/service/vspdirect-register.vsp";
  $RefundURL	= "https://test.sagepay.com/gateway/service/refund.vsp";
  $ReleaseURL	= "https://test.sagepay.com/gateway/service/release.vsp";
  $RepeatURL	= "https://test.sagepay.com/gateway/service/repeat.vsp";
  $AbortURL		= "https://test.sagepay.com/gateway/service/abort.vsp";
  $AuthoriseURL = "https://ukvpstest.protx.com/vspgateway/service/authorise.vsp";
  $CancelURL	= "https://test.sagepay.com/gateway/service/cancel.vsp";
  
}

/************************************************
 Information and URLs for the Live site
************************************************/
if ($LiveSite)
{
  $Verify=true;
  /*$PurchaseURL	= "https://ukvps.protx.com/vspgateway/service/vspdirect-register.vsp";
  $RefundURL	= "https://ukvps.protx.com/vspgateway/service/refund.vsp";
  $ReleaseURL	= "https://ukvps.protx.com/vspgateway/service/release.vsp";
  $RepeatURL	= "https://ukvps.protx.com/vspgateway/service/repeat.vsp";
  $AbortURL		= "https://ukvps.protx.com/vspgateway/service/abort.vsp";
  $AuthoriseURL = "https://ukvps.protx.com/vspgateway/service/authorise.vsp";
  $CancelURL	= "https://ukvps.protx.com/vspgateway/service/cancel.vsp";*/
  
  $PurchaseURL	= "https://live.sagepay.com/gateway/service/vspdirect-register.vsp";
  $RefundURL	= "https://live.sagepay.com/gateway/service/refund.vsp";
  $ReleaseURL	= "https://live.sagepay.com/gateway/service/release.vsp";
  $RepeatURL	= "https://live.sagepay.com/gateway/service/repeat.vsp";
  $AbortURL		= "https://live.sagepay.com/gateway/service/abort.vsp";
  $AuthoriseURL = "https://ukvps.protx.com/vspgateway/service/authorise.vsp";
  $CancelURL	= "https://live.sagepay.com/gateway/service/cancel.vsp";
}
?>