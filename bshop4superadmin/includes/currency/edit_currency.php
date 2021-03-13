<?php
/*
#################################################################
# Script Name 	: edit_currency.php
# Description 	: Page for editing Currency 
# Coded by 		: SG
# Created on	: 31-May-2007
# Modified by	:
# Modified On	: 
#################################################################
*/
#Define constants for this page
$page_type = 'Currency';
$help_msg = 'This section helps in editing the values for a Currency.';

$sql = "SELECT curr_name,curr_sign,curr_sign_char,curr_code,curr_numeric_code 
			FROM common_currency 
				WHERE currency_id=".$_REQUEST['currency_id'];
$res = $db->query($sql);
$row = $db->fetch_array($res);
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
        <td align="left" class="menutabletoptd">&nbsp;&nbsp;<strong>Edit <?=$page_type?></strong></td>
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
				  <td align="left"><input name="curr_name" type="text" id="curr_name" value="<?=$row['curr_name']?>" size="30">
					  <span class="redtext">*</span></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Currency sign</td>
				  <td align="center">:</td>
				  <td align="left"><input name="curr_sign" type="text" id="curr_sign" value="<?=htmlspecialchars($row['curr_sign'])?>" size="30"></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Currency sign Character</td>
				  <td align="center">:</td>
				  <td align="left"><input name="curr_sign_char" type="text" id="curr_sign_char" value="<?=$row['curr_sign_char']?>" size="30"><span class="redtext">*</span></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Currecny Code</td>
				  <td align="center">:</td>
				  <td align="left"><input name="curr_code" type="text" id="curr_code" value="<?=$row['curr_code']?>" size="30"> <span class="redtext">*</span></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Numeric Currecny Code</td>
				  <td align="center">:</td>
				  <td align="left"><input name="num_curr_code" type="text" id="num_curr_code" value="<?=$row['curr_numeric_code']?>" size="30"> <span class="redtext">*</span></td>
				</tr>
				
				
				<tr>
				  <td colspan="3" align="right">&nbsp;</td>
				</tr>
				<tr align="center">
				<td>&nbsp;</td>
				<td colspan="3" align="left">
					<input type="hidden" name="currency_id" id="currency_id" value="<?=$_REQUEST['currency_id']?>" />
					<input type="hidden" name="currency_name" id="currency_name" value="<?=$_REQUEST['currency_name']?>" />
					<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
					<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
					<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
					<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
					<input type="hidden" name="fpurpose" id="fpurpose" value="update" />
					<input type="Submit" name="Submit" id="Submit" value="Edit" class="input-button">
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