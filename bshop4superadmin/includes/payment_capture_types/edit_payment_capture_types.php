<?php
/*#################################################################
# Script Name 	: edit_payment_types.php
# Description 	: Page for editing Payment types
# Coded by 		: LSH
# Created on	: 06-Nov-2007
# Modified by	: 
# Modified On	: 
#################################################################
*/
//#Define constants for this page
$page_type = 'Payment Capture Types';
$help_msg = 'This section helps in editing the values for a Payment capture  type.';

//#Sql
$sql_payment_capture_type = "SELECT paymentcapture_id,paymentcapture_name,paymentcapture_code,paymentcapture_order FROM payment_capture_types WHERE paymentcapture_id='".add_slash($_REQUEST['paymentcapture_id'])."'";
$res_payment_capture_type = $db->query($sql_payment_capture_type);
$row = $db->fetch_array($res_payment_capture_type);
?>
<script language="javascript">
function valform(frm)
{
	fieldRequired = Array('paymentcapture_name','paymentcapture_code');
	fieldDescription = Array('Payment capture type Name','Payment Acpture type Code');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		return true;
	} else {
		return false;
	}
}
</script>
<form name='frmEditPaymenttype' action='home.php?request=payment_capture_types' method="post" onsubmit="return valform(this);">

<table width="100%" border="0" cellpadding="0" cellspacing="1" class="maininnertable">
      <tr>
        <td align="left" class="menutabletoptd">&nbsp;&nbsp;<a href="home.php?request=payment_capture_types&pay_capture_types=<?=$_REQUEST['pay_capture_types']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>" title="List Themes">List Payment Capture types </a> <font size="1">>></font> <strong>Edit <?=$page_type?></strong></td>
      </tr>
      
      <tr>
        <td class="maininnertabletd3">
			<?=$help_msg?>
		</td>
      </tr>
	  <tr>
        <td class="maininnertabletd2" valign="top" >
			<table width="100%"  border="0" cellpadding="4" cellspacing="1" class="">
				<tr align="left">
				  <td colspan="3" class="redtext"><div align="left">* <span>are required </span></div></td>
				</tr>
				<tr>
				  <td width="40%" align="right" class="fontblacknormal">Payment type capture name</td>
				  <td width="5%" align="center">:</td>
				  <td width="55%" align="left"><input name="paymentcapture_name" type="text" id="paymentcapture_name" value="<?=$row['paymentcapture_name']?>" size="30" />
                  <span class="redtext">*</span></td>
			    </tr>
				<tr>
				  <td width="40%" align="right" class="fontblacknormal">Payment type capture code</td>
				  <td width="5%" align="center">:</td>
				  <td width="55%" align="left"><input name="paymentcapture_code" type="text" id="paymentcapture_code" value="<?=$row['paymentcapture_code']?>" size="30" />
                  <span class="redtext">*</span></td>
			    </tr>
				<tr>
				  <td width="40%" align="right" class="fontblacknormal">Payment type capture order</td>
				  <td width="5%" align="center">:</td>
				  <td width="55%" align="left"><input name="paymentcapture_order" type="text" id="paymentcapture_order" value="<?=$row['paymentcapture_order']?>" size="5" />
                  <span class="redtext">*</span></td>
			    </tr>
				<tr align="center">
				<td width="30%">&nbsp;</td>
				<td align="left">
				<input type="hidden" name="paymentcapture_id" id="paymentcapture_id" value="<?=$_REQUEST['paymentcapture_id']?>" />
				<input type="hidden" name="pay_capture_types" id="pay_capture_types" value="<?=$_REQUEST['pay_capture_types']?>" />
				<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
				<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
				<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
				<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
				<input type="hidden" name="fpurpose" id="fpurpose" value="update" />
				<input type="Submit" name="Submit" id="Submit" value="Update" class="input-button">				</td>
				<td align="left">&nbsp;</td>
				<td align="left">&nbsp;</td>
				</tr>
				<tr>
				  <td colspan="3" align="right">&nbsp;</td>
				</tr>
			</table>
		</td>
      </tr>
    </table>
</form>