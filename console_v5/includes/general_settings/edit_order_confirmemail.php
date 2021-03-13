<?php
	/*
	#################################################################
	# Script Name 	: view_profile.php
	# Description 	: Page for logged in users to change the details
	# Coded by 		: ANU
	# Created on	: 14-Jun-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################
	*/	
	$help_msg 	= get_help_messages('ORDER_CONFIRM_EMAIL__MESS1');
	# Retrieving the values of super admin from the table
	$sql = "SELECT * FROM general_settings_sites_common WHERE sites_site_id=".$ecom_siteid;
	$res_admin 			= $db->query($sql);
	$fetch_arr_admin 	= $db->fetch_array($res_admin);
	
	$emails = explode(",",$fetch_arr_admin['order_confirmationmail']);
	$pricepromise_toaddress = stripslashes($fetch_arr_admin['pricepromise_toaddress']);
		$newsletter_toaddress   = stripslashes($fetch_arr_admin['newsletter_replytoaddress']);

	$order_despatch_additional_email = stripslashes($fetch_arr_admin['order_despatch_additional_email']);
?>
<script language="javascript" type="text/javascript">
function valform(frm)
{
	fieldRequired = Array();
	fieldDescription = Array();
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	fieldSpecChars = Array();
	fieldCharDesc = Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric,fieldSpecChars,fieldCharDesc)) {
	 var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
	   for(var i=0 ;i<frm.elements.length;i++)
	   { 
		   var address = frm.elements[i].value;
		   if(frm.elements[i].value!='' && frm.elements[i].name.substr(0,12)=='confirmemail')
		   {
			   if(reg.test(address) == false) {
				  alert('Invalid Email Address');
				  frm.elements[i].select();
				  return false;
				}
		   }	
		}
		show_processing();
		return true;
	} else {
		return false;
	}
}
</script>
<form name="frmGeneralSettings" method="post" action="home.php?request=general_settings&fpurpose=orderconfirmemail_update" onsubmit="return valform(this);">
<table border="0" cellpadding="0" cellspacing="0" width="100%" >
<tr>
<td valign="top" class="contentarea">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=general_settings">General Settings</a> <a href="home.php?request=general_settings&amp;fpurpose=orderconfirmemail"> Confirmation Emails</a> </div></td>
        </tr>
       <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="2">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
		<?php
			if ($alert)
			{
		?>
        <tr>
          <td colspan="2" align="center" valign="middle" class="errormsg" ><?php echo $alert; ?></td>
          </tr>
		 <?php
		 	}
		 ?> 
		 <tr>
		<td  class="tdcolorgray">	
		<div class="listingarea_div">
	
		<table border="0" cellspacing="2" cellpadding="2" width="100%" >
         <tr>
         <td align="left" valign="middle" class="seperationtd" colspan="2">Order Confirmation Email Id</td>
		 </tr>
        <tr>
         <td align="left" valign="middle" class="tdcolorgray" width="10%">Email ID 1:</td>
		 <td width="90%" align="left" valign="middle" class="tdcolorgray"  ><input type="text" name="confirmemail1" value="<?=$emails[0]?>" /> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_ORDER_CON_EMAI_1')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		<tr>
         <td align="left" valign="middle" class="tdcolorgray" width="10%">Email ID 2:</td>
		 <td align="left" valign="middle" class="tdcolorgray"  ><input type="text" name="confirmemail2" value="<?=$emails[1]?>" /> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_ORDER_CON_EMAI_2')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		<tr>
         <td align="left" valign="middle" class="tdcolorgray" width="10%">Email ID 3:</td>
		 <td align="left" valign="middle" class="tdcolorgray"  ><input type="text" name="confirmemail3" value="<?=$emails[2]?>" /> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_ORDER_CON_EMAI_3')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		<tr>
         <td align="left" valign="middle" class="tdcolorgray" width="10%">Email ID 4:</td>
		 <td align="left" valign="middle" class="tdcolorgray"  ><input type="text" name="confirmemail4" value="<?=$emails[3]?>" /> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_ORDER_CON_EMAI_4')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		<tr>
         <td align="left" valign="middle" class="tdcolorgray" width="10%">Email ID 5:</td>
		 <td align="left" valign="middle" class="tdcolorgray"  ><input type="text" name="confirmemail5" value="<?=$emails[4]?>" /> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_ORDER_CON_EMAI_5')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
        
        
        <tr>
         <td align="left" valign="middle" class="seperationtd" colspan="2">Order Despatch Notification Email Id</td>
		 </tr>
		  <tr>
         <td align="left" valign="middle" class="tdcolorgray" width="10%">Email ID</td>
		 <td width="90%" align="left" valign="middle" class="tdcolorgray"  ><input type="text" name="order_despatch_additional_email" value="<?=$order_despatch_additional_email?>" /> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_ORD_DESP_ADDITIONAL_EMAIL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
        
        
          <tr>
         <td align="left" valign="middle" class="seperationtd" colspan="2">Price Promise Notification Email Id</td>
		 </tr>
		  <tr>
         <td align="left" valign="middle" class="tdcolorgray" width="10%">Email ID</td>
		 <td width="90%" align="left" valign="middle" class="tdcolorgray"  ><input type="text" name="pricepromise_toaddress" value="<?=$pricepromise_toaddress?>" /> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PRICE_PROM_CONF_EMAIL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
        <tr>
         <td align="left" valign="middle" class="seperationtd" colspan="2">Product Review Replyto Email Id</td>
		 </tr>
		  <tr>
         <td align="left" valign="middle" class="tdcolorgray" width="10%">Email ID</td>
		 <td width="90%" align="left" valign="middle" class="tdcolorgray"  ><input type="text" name="newsletter_toaddress" value="<?=$newsletter_toaddress?>" /> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_NEWS_PROM_CONF_EMAIL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
        </table>
        </div>
        </td>
        </tr>
        <tr>
		<td  class="tdcolorgray">	
		<div class="listingarea_div">
	
		<table border="0" cellspacing="2" cellpadding="2" width="100%" class="tdcolorgray">
        <tr>
          <td colspan="2" align="right" valign="middle" class="tdcolorgray"><input name="Submit" type="submit" class="red" value="Save" /></td>
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