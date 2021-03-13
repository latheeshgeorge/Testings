<?php
/*#################################################################
# Script Name 	: add_payment_methods.php
# Description 	: Page for addding payment methods
# Coded by 		: ANU
# Created on	: 1-June-2007
# Modified by	: ANU
# Modified On	: 07 April 08
#################################################################
*/
//#Define constants for this page
$page_type = 'Payment Methods';
$help_msg = 'This section helps in adding the values for a Payment Method.';

?>
<script language="javascript">
function valform(frm)
{
	fieldRequired = Array('paymethod_name','paymethod_key');
	fieldDescription = Array('Payment Method Name','Payment Method Key');
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
<form name='frmEditTheme' action='home.php?request=payment_methods' method="post" onsubmit="return valform(this);">

<table width="100%" border="0" cellpadding="0" cellspacing="1" class="maininnertable">
      <tr>
        <td align="left" class="menutabletoptd">&nbsp;&nbsp;<a href="home.php?request=payment_methods&pay_method=<?=$_REQUEST['pay_method']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>" title="List Themes">List Payment Methods </a><strong> <font size="1">>></font> Add <?=$page_type?></strong></td>
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
				  <td width="40%" align="right" class="fontblacknormal">Payment Method  name</td>
				  <td width="5%" align="center">:</td>
				  <td width="55%" align="left"><input name="paymethod_name" type="text" id="paymethod_name" value="<?=$_REQUEST['paymethod_name']?>" size="30" />
                  <span class="redtext">*</span></td>
			    </tr>
				<tr>
				  <td align="right" class="fontblacknormal">Payment Method Key </td>
				  <td align="center">:</td>
				  <td align="left"><input name="paymethod_key" type="text" id="paymethod_key" value="<?=$_REQUEST['paymethod_key']?>" size="30" />
				      <span class="redtext">*</span></td>
			    </tr>
				<tr>
				  <td align="right" class="fontblacknormal">Payment Method Description </td>
				  <td align="center">:</td>
				  <td align="left"><textarea name="paymethod_description" cols="27" rows="7"><?=$_REQUEST['paymethod_description']?></textarea></td>
			    </tr>
				<tr>
				  <td align="right" class="fontblacknormal">Payment Method Take card details </td>
				  <td align="center">:</td>
				  <td align="left"><input type="checkbox" name="paymethod_takecarddetails" id="paymethod_takecarddetails" value="1" <?php echo($_REQUEST['paymethod_takecarddetails']==1)?'checked':'';?>/></td>
			    </tr>
				<?php
				/*<tr>
				  <td align="right" class="fontblacknormal">Payment Minimum value</td>
				  <td align="center">:</td>
				  <td align="left"><input name="payment_minvalue" type="text" id="payment_minvalue" value="<?=$_REQUEST['payment_minvalue']?>" size="30" /></td>
			    </tr>
				*/
				?>
				<!--<tr>
				  <td align="right" class="fontblacknormal">Payment Method SSL Image Link </td>
				  <td align="center">:</td>
				  <td align="left"><input name="paymethod_ssl_imagelink" type="text" id="paymethod_ssl_imagelink" value="<?//=$_REQUEST['paymethod_ssl_imagelink']?>" size="30" /></td>
			  </tr>-->
				<tr>
				  <td align="right" class="fontblacknormal">Payment Method Hide </td>
			      <td align="center">:</td>
			      <td align="left"><input type="checkbox" name="paymethod_hide" id="paymethod_hide" value="1" <?php echo($_REQUEST['paymethod_hide']==1)?'checked':'';?>/></td>
			  </tr>
				<tr>
				  <td align="right">Payment Method Show in Voucher </td>
			      <td align="center">:</td>
			      <td align="left"><input type="checkbox" name="paymethod_showinvoucher" id="paymethod_showinvoucher" value="1" <?php echo($_REQUEST['paymethod_showinvoucher']==1)?'checked':'';?>/></td>
			  </tr>
			    <tr>
			      <td align="right">Payment Method Show in Setup Wizard </td>
			      <td align="center">:</td>
			      <td align="left"><input type="checkbox" name="paymethod_showinsetup" id="paymethod_showinsetup" value="1" <?php echo($_REQUEST['paymethod_showinsetup']==1)?'checked':'';?>/></td>
		      </tr>
		      <tr>
				  <td align="right">Secured Area Required</td>
			      <td align="center">:</td>
			      <td align="left"><input type="checkbox" name="paymethod_secured_req" id="paymethod_secured_req" value="1" <?php echo($_REQUEST['paymethod_secured_req']==1)?'checked':'';?>/></td>
			  </tr>
			   <tr>
				  <td align="right">Payment Method show in pay on credit</td>
			      <td align="center">:</td>
			      <td align="left"><input type="checkbox" name="paymethod_showinpayoncredit" id="paymethod_showinpayoncredit" value="1" <?php echo($_REQUEST['paymethod_showinpayoncredit']==1)?'checked':'';?>/></td>
			  </tr>
              <tr>
				  <td align="right">Show in Mobile?</td>
			      <td align="center">:</td>
			      <td align="left"><input type="checkbox" name="paymethod_showinmobile" id="paymethod_showinmobile" value="1" <?php echo($_REQUEST['paymethod_showinmobile']==1)?'checked':'';?>/></td>
			  </tr>
				<tr>
				  <td colspan="3" align="right">&nbsp;</td>
				</tr>
				<tr align="center">
				<td>&nbsp;</td>
				<td colspan="3" align="left">
					<input type="hidden" name="pay_method" id="pay_method" value="<?=$_REQUEST['pay_method']?>" />
					<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
					<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
					<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
					<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
					<input type="hidden" name="fpurpose" id="fpurpose" value="insert" />
					<input type="Submit" name="Submit" id="Submit" value="Add" class="input-button">				</td>
				</tr>
				<tr>
				  <td colspan="3" align="right">&nbsp;</td>
				</tr>
			</table>
		</td>
      </tr>
    </table>
</form>