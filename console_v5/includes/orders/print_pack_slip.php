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


$orderid = $_REQUEST['orderid'];
$page_type 	= 'Order Details';
$help_msg 	= get_help_messages('EDIT_PRODUCT_STORE_SHORT');
global $ecom_hostname, $ecom_siteid;

$file_name = $image_path.'/otherfiles/print_slip_header.php';
if(file_exists($file_name))
{
	ob_start();
	include "$file_name";
	$content = ob_get_contents();
	ob_end_clean();
}
else
	$content = '';
$packing_slip_prod_show = 0;
// Check whether product details need to be displayed in packing slip
$file_name = $image_path.'/otherfiles/print_slip_product_details.php';
if(file_exists($file_name))
{
	$packing_slip_prod_show = 1;
	include "$file_name";
}
/*	if($db->num_rows($ret_ord))
	{
		$row_ord = $db->fetch_array($ret_ord);
	}
	else // case if not record found
	{
		echo "Sorry Invalid Input";
		exit;
	} */


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Packing Slip</title>
<?php
if($download_file!=1)
{
?>
<link href="../../css/screen.css" rel="stylesheet" media="screen">
<link href="../../css/print.css" rel="stylesheet" media="print">
<?php
}
else
{
	include 'print_pack_slip_css.php';
}
?>
</head>
<body>
<?PHP
$ord = split("~",$_REQUEST['orderid']);	
foreach($ord AS $val) {
  $sql_ord = "SELECT * 
					FROM 
						order_delivery_data 
					WHERE 
						orders_order_id=".$val." 
					
					LIMIT 
						1";
	$ret_ord = $db->query($sql_ord);
	if($db->num_rows($ret_ord)>0)
	{
	while($row_ord = $db->fetch_array($ret_ord)) 
	{
		if($content!='')
			echo $content;
?>
<table width="89%" border="0" cellpadding="0" cellspacing="0" class="innertable">
      <tr>
        <td width="30%" align="left" class="innertablecontent">Order Id </td>
        <td width="70%" align="left" valign="middle" class="innertableborder"><? echo $val ?></td>
      </tr>
      <tr>
        <td align="left" class="innertablecontent">Customer Name </td>
        <td align="left" valign="middle" class="innertableborder"><? echo $row_ord["delivery_title"].' '.$row_ord["delivery_fname"].$row_ord["delivery_mname"].$row_ord["delivery_lname"]; ?></td>
      </tr>
      <tr >
        <td align="left" class="innertablecontent" valign="top">
		Delivery Address</td>
        <td align="left" class="innertableborder" valign="top">
		<? echo $row_ord["delivery_companyname"]."<br>".$row_ord["delivery_buildingnumber"]."<br>".$row_ord["delivery_street"]."<br>".$row_ord["delivery_city"]."<br>".$row_ord["delivery_state"]."<br>".$row_ord["delivery_country"]."<br>".$row_ord["delivery_zip"]."<br> Phone : ".$row_ord["delivery_phone"]."<br> Fax : ".$row_ord["delivery_fax"]; ?>		</td>
      </tr>
	  <?
	  if($packing_slip_prod_show == 1)
	  {
	  ?>
	  <tr >
        <td align="left" class="innertablecontent" valign="top" colspan="2">
		<?
	  if($packing_slip_prod_show == 1)
			show_printslip_product_details($val);
			?>
		</td>
		</tr>
		<? 
		
	}	?>
	   <tr>
        <td colspan="2" align="left" class="innertablecontent">------------------------------------------------------------------------------------------------------------------------------------------------</td>
      </tr>
    </table>      
<? 
		
	} 
}
}
if($download_file!=1)
{
?>
<center>
	<input type="button" name="download_button" value=" Download " onClick="javascript:document.temp_form.submit();" title="Click to download slips in HTML format." />
</center>	
	<script language="javascript">
	window.print();
	</script>
	<form method="post" action="download.php" name="temp_form" id="temp_form">.
	<input type="hidden" name="orderid" id="orderid" value="<?php echo $_REQUEST['orderid']?>" />
	<input type="hidden" name="pass_mode" id="pass_mode" value="slip" />
	</form>
<?php
}
?>
</body>
</html>
