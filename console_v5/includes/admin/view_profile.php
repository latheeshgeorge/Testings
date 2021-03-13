<?php
	/*
	#################################################################
	# Script Name 	: view_profile.php
	# Description 	: Page for logged in users to change the details
	# Coded by 		: ANU
	# Created on	: 14-Jun-2007
	# Modified by	: Sny
	# Modified On	: 11-Oct-2007
	#################################################################
	*/	
	$help_msg = get_help_messages('MYACC1');
	# Retrieving the values of super admin from the table
	if($_SESSION['user_type']=='su')
	{
		$sql = "SELECT user_title,user_fname,user_lname,user_address,user_phone,user_mobile,user_email_9568,user_company 
							FROM sites_users_7584 
									WHERE user_id='".$_SESSION['console_id']."' AND sites_site_id='".$ecom_siteid."'" ;
	}
	else
	{
			$sql = "SELECT user_title,user_fname,user_lname,user_address,user_phone,user_mobile,user_email_9568,user_company 
							FROM sites_users_7584 
									WHERE user_id='".$_SESSION['console_id']."'"  ;
	}									
	$res_admin 			= $db->query($sql);
	if($db->num_rows($res_admin)==0)  { echo " You Are Not Authorised "; exit; } 
	$fetch_arr_admin 	= $db->fetch_array($res_admin);
	$user_title			= stripslashes($fetch_arr_admin['user_title']);
	$user_fname			= stripslashes($fetch_arr_admin['user_fname']);
	$user_lname			= stripslashes($fetch_arr_admin['user_lname']);
	$user_address		= stripslashes($fetch_arr_admin['user_address']);
	$user_phone			= stripslashes($fetch_arr_admin['user_phone']);
	$user_mobile		= stripslashes($fetch_arr_admin['user_mobile']); 
	$user_company		= stripslashes($fetch_arr_admin['user_company']); 
	$user_email			= stripslashes($fetch_arr_admin['user_email_9568']);
?>
<script language="javascript" type="text/javascript">
function valform(frm)
{
		fieldRequired = Array('user_title','user_fname','user_lname','user_email');
		fieldDescription = Array('Title','First Name','Last Name','Email Id');
		fieldEmail = Array('user_email');
		fieldConfirm = Array('user_pwd','user_pwd_cnf');
		fieldConfirmDesc  = Array('New password','Retype Password');
		fieldNumeric = Array();
		fieldSpecChars = Array('user_fname','user_lname','user_phone','user_mobile','user_pwd','user_pwd_cnf');
		fieldCharDesc = Array('First Name','Last Name','Phone','Mobile','Password','Confirm Password');
		
		if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric,fieldSpecChars,fieldCharDesc)) {
			if(frm.user_pwd.value!='' && frm.user_pwd_cnf.value!=''){
				if( frm.user_pwd.value.match(/[\s]+/) ||  frm.user_pwd_cnf.value.match(/[\s]+/)){
					alert("Password should not contain white space");
					frm.user_pwd.select();
					return false;
				}
			}
		return true;
		} else {
			return false;
		}
}
</script>
<form name="frmMyAccount" method="post" action="home.php?request=account&fpurpose=update" onsubmit="return valform(this);">
<table border="0" cellpadding="0" cellspacing="0" width="100%" >
<tr>
<td valign="top" class="contentarea">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span>My Account</span></div></td>
        </tr>
		<tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="2">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
		<?php
			if ($erro_msg)
			{
		?>
        <tr>
          <td colspan="2" align="center" valign="middle" class="errormsg" ><?php echo $erro_msg; ?></td>
          </tr>
		 <?php
		 	}
		 ?> 
		<tr>
			<td colspan="2" align="center" valign="middle">
				<div class="editarea_div">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">	 
        <tr>
          <td width="18%" align="left" valign="middle" class="tdcolorgray"  >Title <span class="redtext">*</span> </td>
          <td width="82%" align="left" valign="middle" class="tdcolorgray"><select name="user_title" id="user_title">
		  <option value="Mr">Mr.</option>
			  <option value="Ms">Ms.</option>
			  <option value="Mrs">Mrs.</option>
			  <option value="Miss">Miss.</option>
			  <option value="M/s">M/s.</option>
            </select>          </td>
        </tr>
        <tr>
          <td align="left" valign="middle" class="tdcolorgray"  >First name <span class="redtext">*</span> </td>
         <td align="left" valign="middle" class="tdcolorgray"><input  type="text" name="user_fname" id="user_fname" value="<?=$user_fname;?>" maxlength="30" size="35" /></td>
        </tr>
        <tr>
          <td align="left" valign="middle" class="tdcolorgray"  >Last name <span class="redtext">*</span></td>
          <td align="left" valign="middle" class="tdcolorgray"><input  type="text" name="user_lname" id="user_lname" value="<?=$user_lname?>" maxlength="30" size="35" /></td>
        </tr>
        <tr>
          <td align="left" valign="middle" class="tdcolorgray"  >Address</td>
          <td align="left" valign="middle" class="tdcolorgray"><textarea name="user_address" id="user_address" rows="4" cols="25"><?=$user_address?>
          </textarea></td>
        </tr>
        <tr>
          <td align="left" valign="middle" class="tdcolorgray"  >Phone</td>
          <td align="left" valign="middle" class="tdcolorgray"><input type="text" name="user_phone" id="user_phone" value="<?=$user_phone?>" size="35" /></td>
        </tr>
        <tr>
          <td align="left" valign="middle" class="tdcolorgray"  >Mobile</td>
          <td align="left" valign="middle" class="tdcolorgray"><input type="text" name="user_mobile" id="user_mobile" value="<?=$user_mobile?>" maxlength="15" size="35" /></td>
        </tr>
        <tr>
          <td align="left" valign="middle" class="tdcolorgray"  >Company</td>
          <td align="left" valign="middle" class="tdcolorgray"><input type="text" name="user_company" id="user_company" value="<?=$user_company?>" size="35" /></td>
        </tr>
        <tr>
          <td align="left" valign="middle" class="tdcolorgray"  >&nbsp;</td>
          <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
        </tr>
        <tr>
          <td align="left" valign="middle" class="tdcolorgray"  >Email</td>
          <td align="left" valign="middle" class="tdcolorgray"><input type="text" name="user_email" id="user_email" value="<?=$user_email?>" size="35" /></td>
        </tr>
        <tr>
          <td align="left" valign="middle" class="tdcolorgray"  >New password</td>
          <td align="left" valign="middle" class="tdcolorgray"><input type="password" name="user_pwd" id="user_pwd" />
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('MYACC_PWD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
        <tr>
          <td align="left" valign="middle" class="tdcolorgray"  >Confirm new password </td>
          <td align="left" valign="middle" class="tdcolorgray"><input  type="password" name="user_pwd_cnf" id="user_pwd_cnf"/>
          <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('MYACC_CONFIRMPWD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
        </table>
        </div>
		 </td>
		 </tr>
		 
		<tr>
			<td colspan="2" align="center" valign="middle">
				<div class="editarea_div">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">	 
					<tr>
						<td width="100%" align="right" valign="middle" class="tdcolorgray"><input name="Submit" type="submit" class="red" value="Update" /></td>
					</tr>
					</table>
				</div>
			</td>
		</tr>
      </table>
</td>
</tr>
</table>
</form>
