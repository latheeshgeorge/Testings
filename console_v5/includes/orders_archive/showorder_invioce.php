<?php
/*#################################################################
# Script Name 	: showorder_invioce.php
# Description 	: Page for showing the details of selected invoice
# Coded by 		: Sny
# Created on	: 14-Jul-2009
# Modified by	: 
# Modified On	: 
#################################################################*/
//#Define constants for this page
include_once("../../functions/functions.php");
include('../../session.php');
require_once("../../config.php");
$inv_id = $_REQUEST['f'];
// Get the details of invoce 
// Check whether invoice related to current order exists. If exist then get the details
$sql_inv = "SELECT invoice_filename 
				FROM 
					order_invoice 
				WHERE 
					invoice_id = $inv_id 
				LIMIT 
					1";
$ret_inv = $db->query($sql_inv);
if ($db->num_rows($ret_inv))
{
	$row_inv 		= $db->fetch_array($ret_inv);
	$cur_inv_file	= $image_path.'/invoices/'.stripslashes($row_inv['invoice_filename']);
	// Get the content of current file
	$fp = fopen($cur_inv_file,'r');
	$fcontent = fread($fp,filesize($cur_inv_file));
	echo $fcontent;
}
?>
<script type="text/javascript">
function print_invoice()
{
	if (confirm('Are you sure you want to print this invoice?'))
		window.print();
}
</script>
<table width="100%" cellpadding="2" cellspacing="3" border="0">
<tr>
<td align="center"><a href="javascript:print_invoice()" style="color: #000000; font-size:10px;">Print</a>
</td>
</tr>
</table>

