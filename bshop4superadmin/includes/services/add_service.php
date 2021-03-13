<?php
#################################################################
# Script Name 	: edit_client.php
# Description 	: Page for editing Clients 
# Coded by 		: SG
# Created on	: 13-June-2006
# Modified by	: SG
# Modified On	: 13-June-2006
#################################################################

#Define constants for this page
$page_type = 'Service';
$help_msg = 'This section helps in adding the values for a service.';

$sql = "SELECT max(ordering)+1 as sort FROM services";
$res = $db->query($sql);
$row = $db->fetch_array($res);
?>
<script language="javascript">
function valform(frm)
{
	fieldRequired = Array('service_name');
	fieldDescription = Array('Service Name');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array('ordering');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		return true;
	} else {
		return false;
	}
}
</script>
<form name='frmEditTheme' action='home.php?request=services' method="post" onsubmit="return valform(this);">

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
				  <td align="right" class="fontblacknormal">Service Name</td>
				  <td align="center">:</td>
				  <td align="left"><input name="service_name" type="text" id="service_name" value="<?=$_REQUEST['service_name']?>" size="30">
					  <span class="redtext">*</span></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Ordering</td>
				  <td align="center">:</td>
				  <td align="left"><input name="ordering" type="text" id="ordering" value="<?=$row['sort']?>" size="30">
					  <span class="redtext">*</span></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Hide Service</td>
				  <td align="center">:</td>
				  <td align="left"><input name="hide" type="radio" id="hide" value="0" checked="checked">N <input name="hide" type="radio" id="hide" value="1">Y
					  </td>
				</tr>			
				<tr>
				  <td colspan="3" align="right">&nbsp;</td>
				</tr>
				<tr align="center">
				<td>&nbsp;</td>
				<td colspan="3" align="left">
					<input type="hidden" name="servicename" id="servicename" value="<?=$_REQUEST['servicename']?>" />
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