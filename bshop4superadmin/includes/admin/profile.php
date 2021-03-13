<?php
	/*
	#################################################################
	# Script Name 	: profile.php
	# Description 	: Page for super admin to change the details
	# Coded by 		: SKR
	# Created on	: 31-May-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################
	*/	
	# Retrieving the values of super admin from the table
	$sql = "SELECT user_fname,user_email_9568 FROM sites_users_7584 WHERE user_id=".$_SESSION['user_id'];
	$res_admin 			= $db->query($sql);
	$fetch_arr_admin 	= $db->fetch_array($res_admin);
	$txtname			= stripslashes($fetch_arr_admin['user_fname']);
	$txtuname			= stripslashes($fetch_arr_admin['user_email_9568']);
?>
<script language="javascript">
function valform(frm)
{
	if(frm.txtoldpass.value) {
		fieldRequired = Array('txtname','txtuname','txtoldpass','txtpassnew','txtconfirm');
		fieldDescription = Array('Name','Username','Password','New Password','Confirm Password');
	} else {
		fieldRequired = Array('txtname','txtuname');
		fieldDescription = Array('Name','Username');
	}
		fieldEmail = Array('txtuname');
		fieldConfirm = Array('txtpassnew','txtconfirm');
		fieldConfirmDesc  = Array('New password','Retype Password');
		fieldNumeric = Array();
		if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
			return true;
		} else {
			return false;
		}
}
</script>
<form name="My_form" class="frm_cls"  method="post" action="home.php?request=admin&fpurpose=update" onsubmit="return valform(this);">
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="middlerightcolumn">
<tr>
<td valign="top">
<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="maininnertabletd2">
  <tr>
    <td height="20" class="menutabletoptd" align="left"><strong>&nbsp;&nbsp;Admin Profile</strong></td>
  </tr>
  
  <tr>
    <td align="center" class="redtext"><div id="showdetails"><?php echo $erro_msg; ?></div><table width="100%"  border="0" cellspacing="1" cellpadding="1">
  <tr>
        <td valign="top"><table width="100%"  border="0" cellpadding="4" cellspacing="1" class="">
            <tr align="left">
              <td colspan="3" class="redtext"><div align="left">* <span>are required </span></div></td>
            </tr>
            <tr>
              <td width="22%" align="right" class="fontblacknormal">Name</td>
              <td width="1%" align="center">:</td>
              <td width="77%" align="left"><input name="txtname" type="text" id="txtname" value="<?php echo $txtname?>" size="30">
                  <span class="redtext">*</span></td>
            </tr>
            <tr>
              <td align="right" class="fontblacknormal">Email(Username)</td>
              <td align="center">:</td>
              <td align="left"><input name="txtuname" type="text" id="txtuname" value="<?php echo $txtuname?>" size="30">
                  <span class="redtext">*</span></td>
            </tr>
			<tr>
              <td align="right" class="fontblacknormal">Old Password</td>
              <td align="center">:</td>
              <td align="left"><input name="txtoldpass" type="password" id="txtoldpass" size="30"></td>
            </tr>
            <tr>
              <td align="right" class="fontblacknormal">New Password</td>
              <td align="center">:</td>
              <td align="left"><input name="txtpassnew" type="password" id="txtpassnew" size="30"></td>
            </tr>
            <tr>
              <td align="right" class="fontblacknormal">Retype Password</td>
              <td align="center">:</td>
              <td align="left"><input name="txtconfirm" type="password" id="txtconfirm" size="30"></td>
            </tr>
            <tr>
              <td colspan="3" align="right">&nbsp;</td>
            </tr>
            <tr align="center">
			<td>&nbsp;</td>
		  	<td colspan="3" align="left">
		  		<input type="submit" name="Submit" id="Submit" value="UPDATE" class="input-button">
		   	</td>
            </tr>
            <tr>
              <td colspan="3" align="right">&nbsp;</td>
            </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
</td>
</tr>
</table>
</form>