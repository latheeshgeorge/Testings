<?php
/*#################################################################
# Script Name 	: showorder_invioce.php
# Description 	: Page for showing the order receipt
# Coded by 		: Sny
# Created on	: 14-Jul-2009
# Modified by	: 
# Modified On	: 
#################################################################*/
	include_once("../../functions/functions.php");
	include('../../session.php');
	require_once("../../config.php");
	require_once("ajax/order_ajax_functions.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Order ID <? echo $order_id ?></title>
<style type="text/css">
.pgbreak {page-break-before: always;}
body {
font-family:Arial, Helvetica, sans-serif;
}
</style>
</head>
<body>
<?php
	$order_arr = explode('~',$_REQUEST['f']);
	for ($i=0;$i<count($order_arr);$i++)
	{
			$order_id = $order_arr[$i];
		// Get the details from orders table
		$sql_ord = "SELECT *,date_format(order_date,'%d-%m-%Y %r') as order_showdate 
						FROM 
							orders 
						WHERE 
							order_id = $order_id 
							AND sites_site_id = $ecom_siteid 
						LIMIT 
							1";
		$ret_ord = $db->query($sql_ord);
		if($db->num_rows($ret_ord))
		{
			$row_ord = $db->fetch_array($ret_ord);
			$cust_address = stripslashes($row_ord['order_custtitle']).stripslashes($row_ord['order_custfname']).' '.stripslashes($row_ord['order_custmname']).' '.stripslashes($row_ord['order_custsurname']);
			if(trim($row_ord['order_custcompany'])!='')
				$cust_address .='<br>'.stripslashes($row_ord['order_custcompany']) ;
			if(trim($row_ord['order_buildingnumber'])!='')
				$cust_address .='<br>'.stripslashes($row_ord['order_buildingnumber']) ;	
			if(trim($row_ord['order_street'])!='')
				$cust_address .='<br>'.stripslashes($row_ord['order_street']) ;
			if(trim($row_ord['order_city'])!='')
				$cust_address .='<br>'.stripslashes($row_ord['order_city']) ;
			if(trim($row_ord['order_state'])!='')
				$cust_address .='<br>'.stripslashes($row_ord['order_state']) ;
			if(trim($row_ord['order_custpostcode'])!='')
				$cust_address .='<br>'.stripslashes($row_ord['order_custpostcode']) ;
			if(trim($row_ord['order_country'])!='')
				$cust_address .='<br>'.stripslashes($row_ord['order_country']) ;	
			// get the delivery address of current order
			$sql_del = "SELECT  delivery_title, delivery_fname, delivery_mname, delivery_lname, delivery_companyname, delivery_buildingnumber,
							delivery_street, delivery_city, delivery_state, delivery_country, delivery_zip, delivery_phone, delivery_fax, 
							delivery_mobile, delivery_email 
						FROM 
							order_delivery_data 
						WHERE 
							orders_order_id = $order_id 
						LIMIT 
							1";
			$ret_del = $db->query($sql_del);
			if($db->num_rows($ret_del))
			{
				$row_del = $db->fetch_array($ret_del);
				$del_address = stripslashes($row_del['delivery_title']).stripslashes($row_del['delivery_fname']).' '.stripslashes($row_del['delivery_mname']).' '.stripslashes($row_del['delivery_lname']);
				if(trim($row_del['delivery_companyname'])!='')
					$del_address .='<br>'.stripslashes($row_del['delivery_companyname']);
				if(trim($row_del['delivery_buildingnumber'])!='')
					$del_address .='<br>'.stripslashes($row_del['delivery_buildingnumber']);	
				if(trim($row_del['delivery_street'])!='')
					$del_address .='<br>'.stripslashes($row_del['delivery_street']);
				if(trim($row_del['delivery_city'])!='')
					$del_address .='<br>'.stripslashes($row_del['delivery_city']);
				if(trim($row_del['delivery_state'])!='')
					$del_address .='<br>'.stripslashes($row_del['delivery_state']);
				if(trim($row_del['delivery_zip'])!='')
					$del_address .='<br>'.stripslashes($row_del['delivery_zip']);
				if(trim($row_del['delivery_country'])!='')
					$del_address .='<br>'.stripslashes($row_del['delivery_country']);	
				if(trim($row_del['delivery_phone'])!='')
					$del_address .='<br><strong>Tel:</strong> '.stripslashes($row_del['delivery_phone']);
				if(trim($row_del['delivery_fax'])!='')
					$del_address .='<br><strong>Fax:</strong> '.stripslashes($row_del['delivery_fax']);
			}
					
		}
		?>
		<table width="100%" border="0" cellspacing="1" cellpadding="1" style="padding: 2px 2px 2px 2px">
		<tr>
		<td align="left" valign="top"><img src="http://<?php echo $ecom_hostname?>/images/<?php echo $ecom_hostname?>/site_images/logo.gif" border="0" /></td>
		<td width="53%" style="font-size:10px; text-align:right" >Stoneybrook<br>
			The Old Library<br>
			Wakefield Road<br> 
			Fitzwilliam <br>
			West Yorkshire <br>
			WF9 5BP <br>
			United Kingdom <br>
			Telephone: 0844 576 3437 <br>
			Web: www.stoneybrook.co.uk <br>
			Email: sales@stoneybrook.co.uk <br>
			VAT No: 987 6106 75		</td>
		</tr>
		<tr>
		<td align="left" valign="top" style="border:1px solid #000000; padding: 2px 2px 2px 8px; font-size:14px">
		<strong>Customer Address: </strong><br />
		<?php echo $cust_address?></td>
		<td align="left" valign="top" style="border:1px solid #000000; padding: 2px 2px 2px 8px; font-size:14px">
		<strong>Delivery Address: </strong><br />
		<?php echo $del_address?><br /><strong>Delivery Instruction:</strong> After 9.30 please</td>
		</tr>
		<tr>
		<td colspan="2" style="border-top:1px solid #000000; border-left:1px solid #000000; border-right:1px solid #000000; padding: 2px 2px 2px 2px">
		<table width="100%" border="0" cellspacing="1" cellpadding="1" style="font-size:14px">
		<tr>
		<td width="25%" align="center" ><strong>Email:</strong></td>
		<td width="28%" align="center" ><strong>Order date: </strong></td>
		<td width="47%" align="center" ><strong>Order Number: </strong></td>
		</tr>
		<tr>
		<td align="center" ><?php echo stripslashes($row_ord['order_custemail'])?></td>
		<td align="center" ><?php echo stripslashes($row_ord['order_showdate'])?></td>
		<td align="center" ><?php echo $order_id?></td>
		</tr>
		<tr>
		<td colspan="3" align="center">
		<?php
			$print_buttons = 1;
			$product_no_link = 1;
			Receipt_show_Products_Remaining_In_Order($order_id,$row_ord,'main_sel',1);
			Receiptshow_OrderTotals($order_id,$row_ord);
		?>		</td>
		</tr>
		</table></td>
		</tr>
		<tr>
		<td colspan="2" style="border-left:1px solid #000000; border-right:1px solid #000000; padding: 2px 2px 2px 2px; font-size:14px">If you have any questions regarding your order please telephone the number at the top right of this receipt or email <strong>sales@stoneybrook.co.uk</strong> <br />
		  <br /></td>
		</tr>
		<tr>
		<td colspan="2" style="border-left:1px solid #000000; border-right:1px solid #000000; border-bottom:1px solid #000000; font-size:14px">Soneybrook's trading terms are wholly compliant with Distance Selling Regulations (DSR); as such any and all returns must be notified, in writing, within 7 days of receipt. For details, and to review your Statutory Duty according to DSR, please refer to our Terms of Business. </td>
		</tr>
</table>
		<p class="pgbreak">&nbsp;</p>
		<table width="100%" border="0" cellspacing="1" cellpadding="1" style="border:1px solid #000000; padding: 2px 2px 2px 2px; font-size:14px ">
		<tr>
		  <td colspan="3" ><strong>Please complete and include with return:</strong>		</td>
		  <td width="22%" ><strong>Stoneybrook </strong></td>
		</tr>
		<tr>
		  <td width="33%" style="padding:8px 0;">Customer Name:</td>
		<td width="37%" valign="bottom"  style="border-bottom:dotted 1px #000000;"></td>
		<td >&nbsp;</td>
		<td ><strong>Order Number:</strong> <?php echo $order_id?> </td>
		</tr>
		<tr>
		  <td style="padding:8px 0;" >Item Returned:</td>
		<td  style="border-bottom:dotted 1px #000000"></td>
		<td width="8%" >&nbsp;</td>
		<td >&nbsp;</td>
		</tr>
		<tr>
		  <td style="padding:8px 0;">Reason for Return:</td>
		<td  style="border-bottom:dotted 1px #000000"></td>
		<td >&nbsp;</td>
		<td >&nbsp;</td>
		</tr>
		<tr>
		  <td style="padding:8px 0;">Please Refund / Replace with:<br /></td>
		<td  style="border-bottom:dotted 1px #000000"></td>
		<td >&nbsp;</td>
		<td >&nbsp;</td>
		</tr>
		<tr>
		  <td style="padding:8px 0;">(Delete as appropriate) </td>
		  <td ></td>
		  <td >&nbsp;</td>
		  <td >&nbsp;</td>
		  </tr>
		</table>
		<p>&nbsp;</p>
		<table width="100%" border="0" cellspacing="1" cellpadding="1" style="border:1px solid #000000; padding: 2px 2px 2px 2px; font-size:14px">
		<tr>
		<td colspan="2" ><strong>Office Use Only:      </strong></td>
		</tr>
		<tr>
		<td style="padding:8px 0;">Delivery Date:  </td>
		<td >............/............/............ </td>
		</tr>
		<tr>
		<td width="33%">&nbsp;</td>
		<td width="67%" align="center" >}  7 days maximum difference </td>
	  </tr>
	  <tr>
		<td style="padding:8px 0;" >Return Postmark Date:</td>
	    <td >............/............/............ </td>
	  </tr>
	  <tr>
		<td  style="padding:8px 0;">Tags Attached:</td>
	    <td > Y/N </td>
	  </tr>
	  <tr>
		<td style="padding:8px 0;">Packaging Included:</td>
	    <td > Y/N </td>
	  </tr>
	  <tr>
		<td style="padding:8px 0;">Clean, Saleable Condition:</td>
	    <td > Y/N </td>
	  </tr>
	  <tr>
		<td style="padding:8px 0;">Refund/Replacement sanctioned:</td>
	    <td > Y/N </td>
	  </tr>
	  <tr>
		<td style="padding:8px 0;">Refund Amount:</td>
	    <td >&pound; ............................. or </td>
	  </tr>
	  <tr>
		<td style="padding:8px 0;">Uplift Charged:</td>
	    <td >&pound; .............................</td>
	  </tr>
	</table>
	<?php
		if(count($order_arr)-1!=$i)
		{
	?>	
			<p class="pgbreak">&nbsp;</p>
<?php
		}
	}
?>
<script type="text/javascript">
window.print();
</script>
</body>
</html>