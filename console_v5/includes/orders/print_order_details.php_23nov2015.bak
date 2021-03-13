<?php
/*#################################################################
# Script Name 	: order_details.php
# Description 	: Page for showing the details of selected orders
# Coded by 		: Sny
# Created on	: 21-Apr-2008
# Modified by	: Sny
# Modified On	: 09-May-2008
#################################################################*/
//#Define constants for this page
include_once("../../functions/functions.php");
include('../../session.php');
require_once("../../config.php");
require_once("ajax/order_ajax_functions.php");

$page_type 	= 'Order Details';
$help_msg 	= get_help_messages('EDIT_PRODUCT_STORE_SHORT');

global $ecom_hostname;
$file_name = $image_path.'/otherfiles/print_order_header.php';
if(file_exists($file_name))
{
	ob_start();
	include "$file_name";
	$content = ob_get_contents();
	ob_end_clean();
}
else
	$content = '';

?>	
<html>
<head>
<title>Order Details</title>
<?php
if($download_file!=1)
{
?>
<link href="../../css/style_print.css" rel="stylesheet" media="screen">
<link href="../../css/default_print.css" rel="stylesheet" media="screen">
<?php
}
else
{
	include 'print_order_details_css.php';
}
?>
<style>
p.page_break { page-break-after: always; }
</style>
</head>
<body>
<form name='frmOrderDetails' action='' method="post">
<table width="100%">
<tr>
<td>&nbsp;

</td>
</tr>
<tr>
<td>
<?php
	$show_order_details = 1;
	$print_buttons = 1;
	
	$orderid = split("~",$_REQUEST['orderid']);
	if (count($orderid)>1)
		$break_req = "<p class='page_break'></p>";
	else
		$break_req = '';
	foreach($orderid AS $val) {
		if($content!='')
			echo $content;
		echo show_Order_Summary($val,$alert='',1);
		echo "<hr>$break_req";
	}
?>	
</td>
</tr>
<tr>
<td>&nbsp;

</td>
</tr>
<?php
if($download_file!=1)
{
?>
<tr>
<td align="center">
<input type="button" name="Submit" value=" Print " onClick="javascript:window.print();" />
<input type="button" name="download_button" value=" Download " onClick="javascript:document.temp_form.submit();" title="Click to download details in HTML format." />
</td>
</tr>
<?php
}
?>
</table>
</form>	  
<?php
if($download_file!=1)
{
?>
<form method="post" action="download.php" name="temp_form" id="temp_form">.
<input type="hidden" name="orderid" id="orderid" value="<?php echo $_REQUEST['orderid']?>" />
<input type="hidden" name="pass_mode" id="pass_mode" value="friendly" />
</form>
<?php
}
?>
</body>
</html>
