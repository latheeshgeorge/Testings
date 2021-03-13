<?php
/*#################################################################
# Script Name 	: edit_client.php
# Description 	: Page for editing Clients 
# Coded by 		: ANU
# Created on	: 31-May-2007
# Modified by	: 
# Modified On	: 
#################################################################*/

//#Define constants for this page
$page_type = 'Client';
$help_msg = 'This section helps in editing the values for a client.';

//#Sql
$sql_client = "SELECT client_fname,client_lname,client_company,client_address,client_email,client_postcode,client_country_id,client_state,client_phone,client_mobile,client_fax FROM clients WHERE client_id=".add_slash($_REQUEST['client_id']);
$res_client = $db->query($sql_client);
$row 		= $db->fetch_array($res_client);

//Get the list of all countries for this site for which state is added
$sql_country = "SELECT country_id,country_name FROM common_country   
				 ORDER BY country_name";
$ret_country = $db->query($sql_country);
$country_arr = array(0=>'-- Select Country --');
if ($db->num_rows($ret_country))
{
	while ($row_country = $db->fetch_array($ret_country))
	{
		$country_id 				= $row_country['country_id'];
		$country_name 				= stripslashes($row_country['country_name']);
		$country_arr[$country_id] 	= $country_name;		
		//Get the list of states under this country
	}
}
?>
<script language="javascript">
function valform(frm)
{
	fieldRequired = Array('client_fname','client_lname','client_company','client_email','client_phone');
	fieldDescription = Array('First Name','Last Name','Company','Email','Phone');
	fieldEmail = Array('client_email');
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
<form name='frmEditClient' action='home.php?request=clients' method="post"  onsubmit="return valform(this);">

<table width="100%" border="0" cellpadding="0" cellspacing="1" class="maininnertable">
      <tr>
        <td align="left" class="menutabletoptd">&nbsp;&nbsp;<a href="home.php?request=clients&client_name=<?=$_REQUEST['client_name']?>&company=<?=$company?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>" title="List Themes">List Clients</a><strong> <font size="1">>></font>Edit <?=$page_type?></strong></td>
      </tr>
      
      <tr>
        <td class="maininnertabletd3">
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
				  <td align="left"><input name="client_fname" type="text" id="client_fname" value="<?=$row['client_fname']?>" size="30">
					  <span class="redtext">*</span></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Last Name </td>
				  <td align="center">:</td>
				  <td align="left"><input name="client_lname" type="text" id="client_lname" value="<?=$row['client_lname']?>" size="30"><span class="redtext">*</span></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Company</td>
				  <td align="center">:</td>
				  <td align="left"><input name="client_company" type="text" id="client_company" value="<?=$row['client_company']?>" size="30"><span class="redtext">*</span></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Address</td>
				  <td align="center">:</td>
				  <td align="left"><textarea name="client_address" id="client_address"><?=$row['client_address']?></textarea></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Email</td>
				  <td align="center">:</td>
				  <td align="left"><input name="client_email" type="text" id="client_email" value="<?=$row['client_email']?>" size="30">
				  <span class="redtext">*</span>				  </td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Country</td>
				  <td align="center">:</td>
				  <td align="left">
				  <?php
				  echo generateselectbox('cbo_country',$country_arr,$row['client_country_id'],'','showstate(this.value)');
				  ?>				  </td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">State</td>
				  <td align="center">:</td>
				  <td align="left"><input type="text" name="cbo_state" value="<?=$row['client_state']?>" id="cbo_state"  size="30"/></td>
			  </tr>
				<tr>
				  <td align="right" class="fontblacknormal">Postcode</td>
				  <td align="center">:</td>
				  <td align="left"><input name="client_postcode" type="text" id="client_postcode" value="<?=$row['client_postcode']?>" size="30"></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Phone</td>
				  <td align="center">:</td>
				  <td align="left"><input name="client_phone" type="text" id="client_phone" value="<?=$row['client_phone']?>" size="30">
				  <span class="redtext">*</span>				  </td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Mobile</td>
				  <td align="center">:</td>
				  <td align="left"><input name="client_mobile" type="text" id="client_mobile" value="<?=$row['client_mobile']?>" size="30"></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Fax</td>
				  <td align="center">:</td>
				  <td align="left"><input name="client_fax" type="text" id="client_fax" value="<?=$row['client_fax']?>" size="30"></td>
				</tr>
				<tr>
				  <td colspan="3" align="right">&nbsp;</td>
				</tr>
				<tr align="center">
				<td>&nbsp;</td>
				<td colspan="3" align="left">
					<input type="hidden" name="client_id" id="client_id" value="<?=$_REQUEST['client_id']?>" />
					<input type="hidden" name="client_name" id="client_name" value="<?=$_REQUEST['client_name']?>" />
					<input type="hidden" name="company" id="company" value="<?=$_REQUEST['company']?>" />
					<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
					<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
					<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
					<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
					<input type="hidden" name="fpurpose" id="fpurpose" value="update" />
					<input type="Submit" name="Submit" id="Submit" value="Update" class="input-button">				</td>
				</tr>
				<tr>
				  <td colspan="3" align="right">&nbsp;</td>
				</tr>
			</table>
		</td>
      </tr>
    </table>
</form>