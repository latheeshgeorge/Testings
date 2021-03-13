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
$page_type = 'Client';
$help_msg = 'This section helps in editing the values for a client.';

#Sql
$sql_client = "SELECT fname,surname,company,address,email,country_id,postcode,phone,fax FROM client WHERE client_id=".$_REQUEST['client_id'];
$res_client = $db->query($sql_client);
$row 		= $db->fetch_array($res_client);
?>
<script language="javascript">
function valform(frm)
{
	fieldRequired = Array('fname','surname','company','email','phone');
	fieldDescription = Array('First Name','Surname','Company','Email','Phone');
	fieldEmail = Array('email');
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
<form name='frmEditClient' action='home.php?request=clients' method="post" >

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
				  <td align="right" class="fontblacknormal">First name</td>
				  <td align="center">:</td>
				  <td align="left"><input name="fname" type="text" id="fname" value="<?=$row['fname']?>" size="30">
					  <span class="redtext">*</span></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Surname</td>
				  <td align="center">:</td>
				  <td align="left"><input name="surname" type="text" id="surname" value="<?=$row['surname']?>" size="30"><span class="redtext">*</span></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Company</td>
				  <td align="center">:</td>
				  <td align="left"><input name="company" type="text" id="company" value="<?=$row['company']?>" size="30"><span class="redtext">*</span></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Address</td>
				  <td align="center">:</td>
				  <td align="left"><textarea name="address" id="address"><?=$row['address']?></textarea></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Email</td>
				  <td align="center">:</td>
				  <td align="left"><input name="email" type="text" id="email" value="<?=$row['email']?>" size="30">
				  <span class="redtext">*</span>
				  </td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Country</td>
				  <td align="center">:</td>
				  <td align="left">
				  <?php
				  $sql_country = "SELECT country_id,country_name FROM country ORDER BY country_name ASC";
				  $res_country = $db->query($sql_country);
				  while($row_cuntry = $db->fetch_array($res_country)) {
				  	$country_array[$row_cuntry['country_id']] = stripslashes($row_cuntry['country_name']);
				  }
				  echo generateselectbox('country_id',$country_array,$row['country_id']);
				  ?>
				  </td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Postcode</td>
				  <td align="center">:</td>
				  <td align="left"><input name="postcode" type="text" id="postcode" value="<?=$row['postcode']?>" size="30"></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Phone</td>
				  <td align="center">:</td>
				  <td align="left"><input name="phone" type="text" id="phone" value="<?=$row['phone']?>" size="30">
				  <span class="redtext">*</span>
				  </td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Fax</td>
				  <td align="center">:</td>
				  <td align="left"><input name="fax" type="text" id="fax" value="<?=$row['fax']?>" size="30"></td>
				</tr>
				<tr>
				  <td colspan="3" align="right">&nbsp;</td>
				</tr>
				<tr align="center">
				<td>&nbsp;</td>
				<td colspan="3" align="left">
					<input type="hidden" name="client_id" id="client_id" value="<?=$_REQUEST['client_id']?>" />
					<input type="hidden" name="client_name" id="client_name" value="<?=$_REQUEST['client_name']?>" />
					<input type="hidden" name="client_company" id="client_company" value="<?=$_REQUEST['client_company']?>" />
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