<?php
/*#################################################################
# Script Name 	: add_message.php
# Description 	: Page for adding messages.
# Coded by 		: LSH
# Created on	: 12-Nov-2007
# Modified by	: 
# Modified On	: 
#################################################################
*/
//#Define constants for this page
$page_type = 'Help Messages';
$help_msg  = 'This section helps in additing the Help Messages.';
?>
<script language="javascript">
function valform(frm)
{
	fieldRequired = Array('help_help_message','help_type','help_code','console_help_group_id');
	fieldDescription = Array('Message','Message Type','Message Code','Message Group');
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
<form name='frmEditMessagee' action='home.php?request=help_message' method="post" onsubmit="return valform(this);">

<table width="100%" border="0" cellpadding="0" cellspacing="1" class="maininnertable">
      <tr>
        <td align="left" class="menutabletoptd">&nbsp;&nbsp;<a href="home.php?request=help_message&help_message=<?=$_REQUEST['help_message']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>" title="List Themes">List Messages </a> <font size="1">>></font> <strong>Edit <?=$page_type?></strong></td>
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
				  <td width="40%" align="right" class="fontblacknormal">Message</td>
				  <td width="5%" align="center">:</td>
				  <td width="55%" align="left"><textarea name="help_help_message" type="text" id="help_help_message"  rows="4" cols="50"/><?=$_REQUEST['help_help_message']?> </textarea>
                  <span class="redtext">*</span></td>
			    </tr>
				<? 
				$type_array = array(0 => '-- Any --', 'short' => 'short','long' => 'long','popup' => 'popup');

				?>
				<tr>
				  <td width="40%" align="right" class="fontblacknormal">Message type  </td>
				  <td width="5%" align="center">:</td>
				  <td  align="left">
				  <? echo generateselectbox('help_type',$type_array,$_REQUEST['help_type']);
					?>
                  <span class="redtext">*</span></td>
			    </tr>
				<tr>
				  <td width="40%" align="right" class="fontblacknormal">Message Code  </td>
				  <td width="5%" align="center">:</td>
				  <td width="55%" align="left"><input name="help_code" type="text" id="help_code" value="<?=$_REQUEST['help_code']?>" size="30" />
                  <span class="redtext">*</span></td>
			    </tr>
				<?
				$group_array = array(0 => '-- Select --');
				$sql_message_group = "SELECT help_group_name,help_group_id FROM console_help_group ORDER BY help_group_name ASC ";
				$res_message_group = $db->query($sql_message_group);
				
				?>
				<tr>
				  <td width="40%" align="right" class="fontblacknormal">Message Group </td>
				  <td width="5%" align="center">:</td>
				  <td width="55%" align="left">
					<?
					 while($row_group 		= $db->fetch_array($res_message_group)){
					 $group_array[$row_group['help_group_id']] = $row_group['help_group_name'];
					 }
					 echo generateselectbox('console_help_group_id',$group_array,$_REQUEST['console_help_group_id']);
					?>
					
                  <span class="redtext">*</span></td>
			    </tr>
				<tr align="center">
				<td width="30%">&nbsp;</td>
				<td align="left">
				<input type="hidden" name="paytype_id" id="paytype_id" value="<?=$_REQUEST['paytype_id']?>" />
				<input type="hidden" name="help_message" id="help_message" value="<?=$_REQUEST['help_message']?>" />
				<input type="hidden" name="help_code_search" id="help_code_search" value="<?=$_REQUEST['help_code_search']?>" />
				<input type="hidden" name="help_type_search" id="help_type_search" value="<?=$_REQUEST['help_type_search']?>" />
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