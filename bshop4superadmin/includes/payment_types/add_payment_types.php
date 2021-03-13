<?php
/*#################################################################
# Script Name 	: add_payment_types.php
# Description 	: Page for addding payment types
# Coded by 		: LSH
# Created on	: 06-Nov-2007
# Modified by	: Sny
# Modified On	: 25-Feb-2008
#################################################################
*/
//#Define constants for this page
$page_type = 'Payment Types';
$help_msg = 'This section helps in adding the values for a Payment type.';

?>
<script language="javascript">
function valform(frm)
{
	fieldRequired = Array('paytype_name','paytype_code');
	fieldDescription = Array('Payment type Name','Payment type Code');
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
<form name='frmEditTypes' action='home.php?request=payment_types' method="post" onsubmit="return valform(this);">

<table width="100%" border="0" cellpadding="0" cellspacing="1" class="maininnertable">
      <tr>
        <td align="left" class="menutabletoptd">&nbsp;&nbsp;<a href="home.php?request=payment_types&pay_type=<?=$_REQUEST['pay_type']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>" title="List Themes">List Payment types </a><strong> <font size="1">>></font> Add <?=$page_type?></strong></td>
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
				  <td width="40%" align="right" class="fontblacknormal">Payment type  name</td>
				  <td width="5%" align="center">:</td>
				  <td width="55%" align="left"><input name="paytype_name" type="text" id="paytype_name" value="<?=$_REQUEST['paytype_name']?>" size="30" />
                  <span class="redtext">*</span></td>
			    </tr>
				<tr>
				  <td align="right" class="fontblacknormal">Payment type code  </td>
				  <td align="center">:</td>
				  <td align="left"><input name="paytype_code" type="text" id="paytype_code" value="<?=$_REQUEST['paytype_code']?>" size="30" />
                    <span class="redtext">*</span></td>
			  </tr>
				<tr>
				  <td width="40%" align="right" class="fontblacknormal">Payment type  order</td>
				  <td width="5%" align="center">:</td>
				  <td width="55%" align="left"><input name="paytype_order" type="text" id="paytype_order" value="<?=$_REQUEST['paytype_order']?>" size="5" />
                  <span class="redtext">*</span></td>
			    </tr>
				<tr>
				  <td width="40%" align="right" class="fontblacknormal">Payment type Show in Voucher</td>
				  <td width="5%" align="center">:</td>
				  <td width="55%" align="left"><input name="paytype_showinvoucher" type="checkbox" id="paytype_showinvoucher" value="1" <? if($_REQUEST['paytype_showinvoucher']==1) echo "checked";?>/>
                  <span class="redtext">*</span></td>
			    </tr>
				<tr>
				  <td width="40%" align="right" class="fontblacknormal">Login to use payment type</td>
				  <td width="5%" align="center">:</td>
				  <td width="55%" align="left"><input name="paytype_logintouse" type="checkbox" id="paytype_logintouse" value="1" <? if($_REQUEST['paytype_logintouse']==1) echo "checked";?>/>
                  <span class="redtext">*</span></td>
			    </tr>
				<tr>
				  <td width="40%" align="right" class="fontblacknormal">Payment type show in pay on credit</td>
				  <td width="5%" align="center">:</td>
				  <td width="55%" align="left"><input name="paytype_showinpayoncredit" type="checkbox" id="paytype_showinpayoncredit" value="1" <? if($_REQUEST['paytype_showinpayoncredit']==1) echo "checked";?>/>
                  <span class="redtext">*</span></td>
			    </tr>
				<tr>
				  <td colspan="3" align="right">&nbsp;</td>
				</tr>
				<tr align="center">
				<td>&nbsp;</td>
				<td colspan="3" align="left">
					<input type="hidden" name="pay_type" id="pay_type" value="<?=$_REQUEST['pay_type']?>" />
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