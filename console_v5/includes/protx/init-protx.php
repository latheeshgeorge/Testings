<?

/*******************************************************
 PROTX VPS Specific Definitions
*******************************************************/



/*******************************************************
 Enter the your Vendor Name, agreed when you registered with PROTX */

//$Vendor="[Your vendor name]";
//$Vendor="TestVendor";

/*******************************************************
 Enter your default description of goods here.
 NOTE: You may wish to over-ride this with specific descriptions for
      each transaction.  Insert code in WebSaveOrder if you wish to do so*/

$DefaultDescription="[Your default description]";

/*******************************************************
 Enter the default currency below.  In the UK this is likely to be GBP
 NOTE: If you site supports multiple currencies, you will need to add
       currency selection code into the WebSaveOrder script.*/

$DefaultCurrency="GBP";

/*******************************************************
 Enter your default description of goods here.
 NOTE: You may wish to over-ride this with specific descriptions for
      each transaction.  Insert code in WebSaveOrder if you wish to do so*/

$DefaultRefundDescription="[Your default refund description]";

/********************************************************
 Enter the External and Internal IP addresses of your web site server

 If you are not using a PROXY Server or other form of network address
 translation, both Internal and External address will be the same.

 If you ARE using network address translation, the External IP address
 should be set to the address of this server that visible from the Internet,
 and the Internal IP address to the real address on your local network.*/

//$ExternalIPAddress="[Your Server IP Address]";
//$InternalIPAddress="[Your Server IP Address]";
//$ExternalIPAddress="217.158.66.141";
//$InternalIPAddress="217.158.66.141";

$ExternalIPAddress="213.171.205.3";
$InternalIPAddress="213.171.205.3";

/*******************************************************
Default file path */
$DefaultFilePath = '';

/*******************************************************
 Enter the name of the Order Completion page
 By default, the VPSTxID and Vendor unique ID are passed
 to this script as query strings.  You can override
 or add to these by modifying the VPSHandlePROTXResponse code*/
 
$DefaultOrderCompletePath= $DefaultFilePath . "vps_order_complete.php";

/*******************************************************
 Enter the name of the information page shown when
 a user's details are not authroised by the bank.
 By default, the Vendor unique ID is passed
 to this script a query string. You can override, or add 
 to this by modifying the VPSHandlePROTXResponse code*/
 
$DefaultNotAuthedPath= $DefaultFilePath . "vps_order_not_authed.php";

/*
'*******************************************************
 Enter the name of the information page shown when
 the a user either cancel or times out whilst at PROTX.
 By default, no information is passed to this page.
 You can override this by modifying the VPSHandleResponse code*/

$DefaultAbortPath= $DefaultFilePath . "vps_order_abort.php";

/*******************************************************
 Enter the name of the information page shown when
 the PROTX returns are error to your web site.
 By default, no information is passed to this page.
 You can override this by modifying the VPSHandleResponse code*/

$DefaultErrorPath= $DefaultFilePath . "vps_order_error.php";

/******************************************************/

?>
