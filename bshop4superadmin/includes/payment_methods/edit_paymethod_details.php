<?php
/*#################################################################
# Script Name 	: edit_paymethod_details.php
# Description 	: Page for editing details of a payment method
# Coded by 		: ANU
# Created on	: 04-June-2007
# Modified by	: 
# Modified On	: 
#################################################################*/

#Define constants for this page
$page_type = 'Payment Method details';
$help_msg = 'This section helps in editing the details for a payment method.';

// Get the name of the selected Payment Method
$sql_paymentmethod = "SELECT paymethod_name FROM payment_methods WHERE paymethod_id='".add_slash($_REQUEST['pass_paymethod_id'])."'";
$ret_paymentmethod = $db->query($sql_paymentmethod);
if ($db->num_rows($ret_paymentmethod))
{
	$row_paymentmethod 		= $db->fetch_array($ret_paymentmethod);
	$selpaymentmethod	= '"'.stripslashes($row_paymentmethod['paymethod_name']).'"';
}	
//#Sql
$sql_paymethod_details = "SELECT payment_method_details_id,payment_methods_paymethod_id,payment_methods_details_caption,payment_methods_details_key,payment_methods_details_isrequired FROM payment_methods_details WHERE payment_method_details_id='".add_slash($_REQUEST['payment_method_details_id'])."'";
$res_paymethod_details = $db->query($sql_paymethod_details);
$row 		= $db->fetch_array($res_paymethod_details);
?>
<script language="javascript">
function valform(frm)
{
	fieldRequired = Array('payment_methods_details_caption','payment_methods_details_key');
	fieldDescription = Array('Payment Methods Details Caption','Payment Methods Details Key');
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
<form name='frmEditPaymethodDetails' action='home.php?request=payment_methods' method="post" onsubmit="return valform(this);">

<table width="100%" border="0" cellpadding="0" cellspacing="1" class="maininnertable">
      <tr>
        <td align="left" class="menutabletoptd"><a href="home.php?request=payment_methods&paymethod_details=<?=$_REQUEST['paymethod_details']?>&paymethod_id=<?=$_REQUEST['pass_paymethod_id']?>&amp;pay_method=<?=$_REQUEST['pass_pay_method']?>&amp;sort_by=<?=$_REQUEST['pass_sort_by']?>&amp;sort_order=<?=$_REQUEST['pass_sort_order']?>&amp;records_per_page=<?=$_REQUEST['pass_records_per_page']?>&amp;pg=<?=$_REQUEST['pass_pg']?>" title="List Payment Method">List Payment Methods </a>&nbsp;&gt;&gt;&nbsp;<a href="home.php?request=payment_methods&fpurpose=paymethod_details&paymethod_details=<?=$_REQUEST['paymethod_details']?>&amp;pass_pay_method=<?=$_REQUEST['pass_pay_method']?>&pass_paymethod_id=<?=$_REQUEST['pass_paymethod_id']?>&amp;pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&amp;pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&amp;pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&amp;pass_pg=<?=$_REQUEST['pass_pg']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>" title="List Themes">List Payment Method Details </a><strong> <font size="1">>></font> Edit <?=$page_type?> For <?=$selpaymentmethod?></strong></td>
      </tr>
  
      <tr>
        <td class="maininnertabletd3">
			<?=$help_msg?>
		</td>
      </tr>
	  <tr>
        <td class="maininnertabletd2">
			<table width="100%"  border="0" cellpadding="4" cellspacing="1" class="">
				<tr align="left">
				  <td colspan="3" class="redtext"><div align="left">* <span>are required </span></div></td>
				</tr>
				<tr>
				  <td width="32%" align="right" class="fontblacknormal">Payment Method Details Caption </td>
				  <td width="2%" align="center">:</td>
				  <td width="66%" align="left"><input name="payment_methods_details_caption" type="text" id="payment_methods_details_caption" value="<?=$row['payment_methods_details_caption']?>" size="30">
				  <span class="redtext">*</span></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Payment Method Details Key </td>
				  <td align="center">:</td>
				  <td align="left"><input name="payment_methods_details_key" type="text" id="payment_methods_details_key" value="<?=$row['payment_methods_details_key']?>" size="30" />
                      <span class="redtext">*</span></td>
			  </tr>
				<tr>
				  <td height="30" align="right" class="fontblacknormal">Is Required? </td>
				  <td align="center">:</td>
				  <td align="left"><label>
				  <input type="checkbox" name="payment_methods_details_isrequired" value="1"  <?php echo($row['payment_methods_details_isrequired']==1)?'checked':'';?> />
				  </label>				  </td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">&nbsp;</td>
				  <td align="center">&nbsp;</td>
				  <td align="left">&nbsp;</td>
			  </tr>
				<tr>
				  <td align="right" class="fontblacknormal">&nbsp;</td>
				  <td align="center">&nbsp;</td>
				  <td align="left">&nbsp;</td>
			  </tr>
				
				<tr>
				  <td colspan="3" align="right">&nbsp;</td>
				</tr>
				<tr align="center">
				<td>&nbsp;</td>
				<td colspan="3" align="left">
					<input type="hidden" name="pass_paymethod_id" id="pass_paymethod_id" value="<?=$_REQUEST['pass_paymethod_id']?>" />
					<input type="hidden" name="pass_pay_method" id="pass_pay_method" value="<?=$_REQUEST['pass_pay_method']?>" />
					<input type="hidden" name="pass_sort_by" id="pass_sort_by" value="<?=$_REQUEST['pass_sort_by']?>" />
					<input type="hidden" name="pass_sort_order" id="pass_sort_order" value="<?=$_REQUEST['pass_sort_order']?>" />
					<input type="hidden" name="pass_records_per_page" id="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
					<input type="hidden" name="pass_pg" id="pass_pg" value="<?=$_REQUEST['pass_pg']?>" />
					
					<input type="hidden" name="payment_method_details_id" id="payment_method_details_id" value="<?=$_REQUEST['payment_method_details_id']?>" />
					<input type="hidden" name="paymethod_details" id="paymethod_details" value="<?=$_REQUEST['paymethod_details']?>" />
					<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
					<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
					<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
					<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
					<input type="hidden" name="fpurpose" id="fpurpose" value="update_paymethod_details" />
					<input type="Submit" name="Submit" id="Submit" value="Update" class="input-button">				</td>
				</tr>
				<tr>
				  <td colspan="3" align="right">&nbsp;</td>
				</tr>
			</table>
		</td>
      </tr>
    </table>
</form>