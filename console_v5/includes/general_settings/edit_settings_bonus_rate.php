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
	
	$help_msg 				= get_help_messages('EDIT_BONUSPOINT_EDIT_MAIN');
	$bonus_help 			= get_help_messages('EDIT_BONUSPOINT_EDIT');
	$bonusminimum_help 		= get_help_messages('EDIT_BONUSPOINT_EDIT_MINIMUM');
	$bonusspendallow_help 	=  get_help_messages('EDIT_BONUSPOINT_CUST_ALLOW_SPEND');
	# Retrieving the values of super admin from the table
	$sql = "SELECT * FROM general_settings_sites_common WHERE sites_site_id=".$ecom_siteid;
	$res_admin 			= $db->query($sql);
	$fetch_arr_admin 	= $db->fetch_array($res_admin);
	
	$sql_default_curr = "SELECT * FROM general_settings_site_currency WHERE sites_site_id=$ecom_siteid AND curr_default=1";
	$res_default_curr = $db->query($sql_default_curr);
	$row_default_curr = $db->fetch_array($res_default_curr);
	$bonus_help = str_replace('[curr]',$row_default_curr['curr_sign_char'],$bonus_help);
	
	$help_msg = str_replace('[curr]',$row_default_curr['curr_sign_char'],$help_msg);
?>
<script language="javascript" type="text/javascript">
function valform(frm)
{
	fieldRequired = Array();
	fieldDescription = Array();
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array('bonuspoint_rate');
	fieldSpecChars = Array();
	fieldCharDesc = Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric,fieldSpecChars,fieldCharDesc)) {
	    if(document.frmGeneralSettings.bonuspoint_rate.value<0)
		{
			 alert('Enter a positive value for the bonus rate.');
			 document.frmGeneralSettings.bonuspoint_rate.select();
			 return false;
		}
		else if(document.frmGeneralSettings.minimum_bonuspoints.value<0)
		{
			 alert('Enter a positive value for the minimum bonus points.');
			 document.frmGeneralSettings.minimum_bonuspoints.select();
			 return false;
		}
		else
		{
			show_processing();
			return true;
		}
	} else {
		return false;
	}
}
</script>
<form name="frmGeneralSettings" method="post" action="home.php?request=general_settings&fpurpose=settings_bonus_rate_update" onsubmit="return valform(this);">
<table border="0" cellpadding="0" cellspacing="0" width="100%" >
<tr>
<td valign="top" class="contentarea">
<table width="100%" border="0" cellspacing="2" cellpadding="2">
        <tr>
          <td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=general_settings">General Settings</a> <a href="home.php?request=general_settings&amp;fpurpose=bonus_rate">Bonus Points</a> </div></td>
        </tr>
        <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="2">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>		 </td>
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
        <?php /*?><tr>
          <td colspan="2" align="left" valign="middle" class="sorttd" ><b>Bonus points conversion rate</b></td>
         
        </tr><?php */?>
        <?php /*?><tr>
          
         <td colspan="2" align="left" valign="middle" class="tdcolorgray"  >If you wish to retain customer visits then offer a loyalty scheme where your customers will gather points that can be exchanged for discount against future purchases. The settings here allow you to determine how many points equate to a discount of <?=$row_default_curr['curr_sign_char']?>1.00. By adding this facility it will promote the number of customers registering on your website and your chances of selling more.
 </td>
        </tr><?php */?>
        <tr>
			<td><div class="editarea_div">
  <table width="100%" border="0" cellspacing="2" cellpadding="2">	
		<tr>
          
         <td width="28%" align="right" valign="middle" class="tdcolorgray" >Bonus points equals to 1 <?=$row_default_curr['curr_sign_char']?></td>
		 <td width="72%" align="left" valign="middle" class="tdcolorgray"  ><input type="text" name="bonuspoint_rate" value="<?=$fetch_arr_admin['bonuspoint_rate']?>" size="10"/> &nbsp;<a href="#" onmouseover ="ddrivetip('<?=$bonus_help?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		<tr>
          
         <td align="right" valign="middle" class="tdcolorgray" >Minimum Bonus points required to spend</td>
		 <td align="left" valign="middle" class="tdcolorgray"  ><input type="text" name="minimum_bonuspoints" value="<?=$fetch_arr_admin['minimum_bonuspoints']?>" size="10"/> &nbsp;<a href="#" onmouseover ="ddrivetip('<?=$bonusminimum_help?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		<tr>
		  <td align="right" valign="middle" class="tdcolorgray" >Show Spend Bonus Points option in Shopping Cart </td>
		  <td align="left" valign="middle" class="tdcolorgray"  ><input type="checkbox" name="cust_allowspendbonuspoints" id="cust_allowspendbonuspoints" value="1" <? echo ($fetch_arr_admin['cust_allowspendbonuspoints'])?'checked="checked"':''?>/>&nbsp;<a href="#" onmouseover ="ddrivetip('<?=$bonusspendallow_help?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		  </tr>
		</table>
		</div>
		</td>
		</tr>
		<tr>
			<td><div class="editarea_div">
  <table width="100%" border="0" cellspacing="2" cellpadding="2">	
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
