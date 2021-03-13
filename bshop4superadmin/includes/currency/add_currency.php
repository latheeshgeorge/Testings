<?php
/*
#################################################################
# Script Name 	: add_currency.php
# Description 	: Page for adding Currency 
# Coded by 		: SKR
# Created on	: 31-May-2007
# Modified by	: 
# Modified On	: 
#################################################################
*/
#Define constants for this page
$page_type = 'Currency';
$help_msg = 'This section helps in adding the values for a Currency.';

?>
<script language="javascript">
function valform(frm)
{
	fieldRequired = Array('curr_name','curr_sign_char','curr_code','num_curr_code');
	fieldDescription = Array('Currency Name','Currency Character','Currency Code','Numeric Code');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array('num_curr_code');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		return true;
	} else {
		return false;
	}
}
</script>
<form name='frmEditTheme' action='home.php?request=currency' method="post" onsubmit="return valform(this);">

<table width="100%" border="0" cellpadding="0" cellspacing="1" class="maininnertable">
      <tr>
        <td align="left" class="menutabletoptd">&nbsp;&nbsp;<strong>Add <?=$page_type?></strong></td>
      </tr>
      
      <tr>
        <td class="maininnertabletd2">
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
				  <td align="right" class="fontblacknormal">Currency name</td>
				  <td align="center">:</td>
				  <td align="left"><input name="curr_name" type="text" id="curr_name" value="<?=$_REQUEST['curr_name']?>" size="30">
					  <span class="redtext">*</span></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Currency sign</td>
				  <td align="center">:</td>
				  <td align="left"><input name="curr_sign" type="text" id="curr_sign" value="<?=$_REQUEST['curr_sign']?>" size="30"></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Currency sign Character</td>
				  <td align="center">:</td>
				  <td align="left"><input name="curr_sign_char" type="text" id="curr_sign_char" value="<?=$_REQUEST['curr_sign_char']?>" size="30"><span class="redtext">*</span></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Currecny Code</td>
				  <td align="center">:</td>
				  <td align="left"><input name="curr_code" type="text" id="curr_code" value="<?=$_REQUEST['curr_code']?>" size="30"> <span class="redtext">*</span></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Numeric Currecny Code</td>
				  <td align="center">:</td>
				  <td align="left"><input name="num_curr_code" type="text" id="num_curr_code" value="<?=$_REQUEST['num_curr_code']?>" size="30"> <span class="redtext">*</span></td>
				</tr>
				
				<tr>
				  <td colspan="3" align="right">&nbsp;</td>
				</tr>
				<tr align="center">
				<td>&nbsp;</td>
				<td colspan="3" align="left">
					<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
					<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
					<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
					<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
					<input type="hidden" name="fpurpose" id="fpurpose" value="insert" />
					<input type="Submit" name="Submit" id="Submit" value="Add" class="input-button">
				</td>
				</tr>
				<tr>
				  <td colspan="3" align="right">&nbsp;</td>
				</tr>
			</table>
		</td>
      </tr>
    </table>
</form>