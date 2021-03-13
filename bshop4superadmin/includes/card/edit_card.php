<?php
/*
#################################################################
# Script Name 	: edit_card.php
# Description 	: Page for editing Cards 
# Coded by 		: SKR
# Created on	: 05-June-2007
# Modified by	: 
# Modified On	: 
#################################################################
*/
#Define constants for this page
$page_type = 'Card Type';
$help_msg = 'This section helps in editing the values for a card.';

$sql = "SELECT cardtype_key,cardtype_caption,cardtype_numberofdigits,cardtype_issuenumber_req,cardtype_securitycode_count 
				FROM payment_methods_supported_cards 
						WHERE cardtype_id=".$_REQUEST['cardtype_id'];
$res = $db->query($sql);
$row = $db->fetch_array($res);
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
				  <td width="28%" align="right" class="fontblacknormal">Key</td>
				  <td width="3%" align="center">:</td>
				  <td width="69%" align="left"><input name="cardtype_key" type="text" id="cardtype_key" value="<?=$row['cardtype_key']?>" size="30">
				  <span class="redtext">*</span></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Caption</td>
				  <td align="center">:</td>
				  <td align="left"><input name="cardtype_caption" type="text" id="cardtype_caption" value="<?=$row['cardtype_caption']?>" size="30">
					  <span class="redtext">*</span></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">No:of digits</td>
				  <td align="center">:</td>
				  <td align="left"><input name="cardtype_numberofdigits" type="text" id="cardtype_numberofdigits" value="<?=$row['cardtype_numberofdigits']?>" size="5">
					  <span class="redtext">*</span></td>
				</tr>
					<tr>
				  <td align="right" class="fontblacknormal">Issue Number Required? </td>
				  <td align="center">:</td>
				  <td align="left"> 
				    
				    <input name="cardtype_issuenumber_req" type="radio" value="1" <?=($row['cardtype_issuenumber_req'])?"checked":""?> />Yes
				    
			        <input name="cardtype_issuenumber_req" type="radio" value="0" <?=($row['cardtype_issuenumber_req']==0)?"checked":""?>  />No 
			       
		          </td>
			  </tr>
				<tr>
				  <td align="right" class="fontblacknormal">No:of digits in security code count </td>
				  <td align="center">:</td>
				  <td align="left"><input name="cardtype_securitycode_count" type="text" id="cardtype_securitycode_count" value="<?=$row['cardtype_securitycode_count']?>" size="5" />
                      <span class="redtext">*</span></td>
			  </tr>
								
				<tr>
				  <td colspan="3" align="right">&nbsp;</td>
				</tr>
				<tr align="center">
				<td>&nbsp;</td>
				<td colspan="3" align="left">
					<input type="hidden" name="cardtype_id" id="cardtype_id" value="<?=$_REQUEST['cardtype_id']?>" />
					<input type="hidden" name="caption" id="caption" value="<?=$_REQUEST['caption']?>" />
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