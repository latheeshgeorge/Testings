<?php
/*
#################################################################
# Script Name 	: edit_setupgroups.php
# Description 		: Page for editing setup groups 
# Coded by 		: SNY
# Created on		: 10-Juj-2008
# Modified by		: 
# Modified On		: 
#################################################################
*/
#Define constants for this page
$page_type 	= 'Setup Wizard Layout Name';
$help_msg		= 'This section helps in editing the values for a setup wizard LAyout Name.';

$sql 	= "SELECT id,layout_id,layout_name,themes_theme_id 
				FROM 
					setup_layout
				WHERE 
					id=".$_REQUEST['id']." 
				LIMIT 
					1";
$res 	= $db->query($sql);
$row = $db->fetch_array($res);
?>
<script language="javascript">
function valform(frm)
{
	fieldRequired = Array('layout_name','layout_id');
	fieldDescription = Array('Layout Name','Layout Code');
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
<form name='frmEditTheme' action='home.php?request=setup_groups' method="post" onsubmit="return valform(this);">

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
				  <td width="26%" align="right" class="fontblacknormal">Layout Name </td>
				  <td width="2%" align="center">:</td>
				  <td width="72%" align="left"><input name="layout_name" type="text" id="layout_name" value="<?php echo $row['layout_name']?>" size="30" />
                      <span class="redtext">*</span></td>
			    </tr>
				<tr>
				  <td align="right" class="fontblacknormal">Layout Code</td>
				  <td align="center">:</td>
				  <td align="left"><input name="layout_id" type="text" id="layout_id" value="<?php echo $row['layout_id']?>" size="5" />
                      <span class="redtext">*</span></td>
			    </tr>
				
								
				<tr>
				  <td colspan="3" align="right">&nbsp;</td>
				</tr>
				<tr align="center">
				<td>&nbsp;</td>
				<td colspan="3" align="left">
					<input type="hidden" name="id" id="id" value="<?=$_REQUEST['id']?>" />
					<input type="hidden" name="src_title" id="src_title" value="<?=$_REQUEST['src_title']?>" />
					<input type="hidden" name="theme_id" id="theme_id" value="<?=$_REQUEST['theme_id']?>" />
					<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
					<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
					<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
					<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
					<input type="hidden" name="fpurpose" id="fpurpose" value="layout_update" />
					<input type="Submit" name="Submit" id="Submit" value="Edit" class="input-button">					</td>
				</tr>
				<tr>
				  <td colspan="3" align="right">&nbsp;</td>
				</tr>
			</table>
		</td>
      </tr>
    </table>
</form>