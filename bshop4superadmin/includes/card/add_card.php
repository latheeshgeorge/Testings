<?php
/*
#################################################################
# Script Name 	: add_card.php
# Description 	: Page for adding Cards
# Coded by 		: SKR
# Created on	: 05-June-2007
# Modified by	: 
# Modified On	: 
#################################################################
*/
#Define constants for this page
$page_type = 'Card Type';
$help_msg = 'This section helps in adding the values for a card type.';

?>
<script language="javascript">
function valform(frm)
{
	fieldRequired = Array('cardtype_key','cardtype_caption','cardtype_numberofdigits','cardtype_securitycode_count');
	fieldDescription = Array('Key','Caption','Num Digits','Security Code count');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array('cardtype_numberofdigits','cardtype_securitycode_count');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		return true;
	} else {
		return false;
	}
}
</script>
<form name='frmEditTheme' action='home.php?request=credit_cards' method="post" onsubmit="return valform(this);">

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
				  <td width="31%" align="right" class="fontblacknormal">Key</td>
				  <td width="3%" align="center">:</td>
				  <td width="66%" align="left"><input name="cardtype_key" type="text" id="cardtype_key" value="" size="30">
					  <span class="redtext">*</span></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Caption</td>
				  <td align="center">:</td>
				  <td align="left"><input name="cardtype_caption" type="text" id="cardtype_caption" value="" size="30">
					  <span class="redtext">*</span></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">No:of digits</td>
				  <td align="center">:</td>
				  <td align="left"><input name="cardtype_numberofdigits" type="text" id="cardtype_numberofdigits" value="" size="5">
					  <span class="redtext">*</span></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Issue Number Required? </td>
				  <td align="center">:</td>
				  <td align="left"> 
				    
				    <input name="cardtype_issuenumber_req" type="radio" value="1" />Yes
				   
			        <input name="cardtype_issuenumber_req" type="radio" value="0" checked="checked" />No
			       
		          </td>
			  </tr>
				<tr>
				  <td align="right" class="fontblacknormal">No:of digits in security code count </td>
				  <td align="center">:</td>
				  <td align="left"><input name="cardtype_securitycode_count" type="text" id="cardtype_securitycode_count" value="<?=$_REQUEST['cardtype_securitycode_count']?>" size="5" />
                      <span class="redtext">*</span></td>
			  </tr>
								
				<tr>
				  <td colspan="3" align="right">&nbsp;</td>
				</tr>
				<tr align="center">
				<td>&nbsp;</td>
				<td colspan="3" align="left">
					
					<input type="hidden" name="caption" id="caption" value="<?=$_REQUEST['caption']?>" />
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