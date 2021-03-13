<?php
/*
#################################################################
# Script Name 	: edit_country.php
# Description 	: Page for editing Countries 
# Coded by 		: SKR
# Created on	: 31-May-2007
# Modified by	: 
# Modified On	: 
#################################################################
*/
#Define constants for this page
$page_type = 'Country';
$help_msg = 'This section helps in editing the values for a country.';

$sql = "SELECT country_name,country_numeric_code,country_code FROM common_country WHERE country_id=".$_REQUEST['country_id'];
$res = $db->query($sql);
$row = $db->fetch_array($res);
?>
<script language="javascript">
function valform(frm)
{
	fieldRequired = Array('country_name','country_code','numeric_code');
	fieldDescription = Array('country Name','Country Code','Numeric Code');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array('numeric_code');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		return true;
	} else {
		return false;
	}
}
</script>
<form name='frmEditTheme' action='home.php?request=country' method="post" onsubmit="return valform(this);">

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
				  <td align="right" class="fontblacknormal">Country name</td>
				  <td align="center">:</td>
				  <td align="left"><input name="country_name" type="text" id="country_name" value="<?=$row['country_name']?>" size="30">
					  <span class="redtext">*</span></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Country code</td>
				  <td align="center">:</td>
				  <td align="left"><input name="country_code" type="text" id="country_code" value="<?=$row['country_code']?>" size="30">
					  <span class="redtext">*</span></td>
				</tr>	
				<tr>
				  <td align="right" class="fontblacknormal">Country numeric code</td>
				  <td align="center">:</td>
				  <td align="left"><input name="numeric_code" type="text" id="numeric_code" value="<?=$row['country_numeric_code']?>" size="30">
					  <span class="redtext">*</span></td>
				</tr>			
				<tr>
				  <td colspan="3" align="right">&nbsp;</td>
				</tr>
				<tr align="center">
				<td>&nbsp;</td>
				<td colspan="3" align="left">
					<input type="hidden" name="country_id" id="country_id" value="<?=$_REQUEST['country_id']?>" />
					<input type="hidden" name="countryname" id="countryname" value="<?=$_REQUEST['countryname']?>" />
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