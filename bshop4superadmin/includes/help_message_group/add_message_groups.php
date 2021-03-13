<?php
/*#################################################################
# Script Name 	: add_message_groups.php
# Description 	: Page for additing message groups
# Coded by 		: LSH
# Created on	: 12-Nov-2007
# Modified by	: 
# Modified On	: 
#################################################################
*/
//#Define constants for this page
$page_type 	= 'Message Groups';
$help_msg 	= 'This section helps in Additing the values for a Message Groups.';
?>
<script language="javascript">
function valform(frm)
{
	fieldRequired = Array('help_group_name');
	fieldDescription = Array('Group Name');
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
<form name='frmEditmessageGroup' action='home.php?request=help_message_group' method="post" onsubmit="return valform(this);">
<table width="100%" border="0" cellpadding="0" cellspacing="1" class="maininnertable">
      <tr>
        <td align="left" class="menutabletoptd">&nbsp;&nbsp;<a href="home.php?request=help_message_group&help_group=<?=$_REQUEST['help_group']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>" title="List Themes">List Message Groups </a> <font size="1">>></font> <strong>Edit <?=$page_type?></strong></td>
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
				  <td width="40%" align="right" class="fontblacknormal">Message Group  name</td>
				  <td width="5%" align="center">:</td>
				  <td width="55%" align="left"><input name="help_group_name" type="text" id="help_group_name" value="<?=$_REQUEST['help_group_name']?>" size="30" />
                  <span class="redtext">*</span></td>
			    </tr>
				<tr align="center">
				<td width="30%">&nbsp;</td>
				<td align="left">
				<input type="hidden" name="help_group_id" id="help_group_id" value="<?=$_REQUEST['help_group_id']?>" />
				<input type="hidden" name="help_group" id="help_group" value="<?=$_REQUEST['help_group']?>" />
				<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
				<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
				<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
				<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
				<input type="hidden" name="fpurpose" id="fpurpose" value="insert" />
				<input type="Submit" name="Submit" id="Submit" value="Add" class="input-button">				</td>
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