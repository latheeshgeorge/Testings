<?php
	/*
	#################################################################
	# Script Name 		: edit_settings_default.php
	# Description 		: Page for managing the main shop settings
	# Coded by 			: Snl
	# Created on		: 14-Jun-2007
	# Modified by		: Sny
	# Modified On		: 25-Aug-2008
	#################################################################
	*/	
	
	$help_msg 			= get_help_messages('EDIT_DELIVERY_SETTINGS_MESS1');
	//# Retrieving the values of super admin from the table
	$sql 							= "SELECT delivery_settings_common_min, delivery_settings_common_max, delivery_settings_common_increment,
									   delivery_settings_weight_min_limit, delivery_settings_weight_max_limit, delivery_settings_weight_increment,
									   delivery_exclude_from_gift_prom_disc	 
												FROM general_settings_sites_common 
														WHERE sites_site_id=".$ecom_siteid;
	$res_admin 				= $db->query($sql);
	$fetch_arr_admin 	= $db->fetch_array($res_admin);
	
/*	$sql 							= "SELECT delivery_settings_common_min, delivery_settings_common_max, delivery_settings_common_increment,
									   delivery_settings_weight_min_limit, delivery_settings_weight_max_limit, delivery_settings_weight_increment	 
												 FROM general_settings_sites_common_onoff WHERE sites_site_id=".$ecom_siteid;
	$res_admin 				= $db->query($sql);
	$fetch_arr_admin_1 	= $db->fetch_array($res_admin);
	
	// This is done to move the fields and its value from general_settings_sites_common_onoff table to the same array which have values from general_settings_sites_common table	
	foreach ($fetch_arr_admin_1 as $k=>$v)
	{
		$fetch_arr_admin[$k]=$v;	
	}
	*/
	//$fetch_arr_admin 	= $db->fetch_array($res_admin);

?>
<form name="frmDeliverySettings" method="post" action="home.php?request=delivery_settings_more&fpurpose=delivery_settings_more" onsubmit="return valforms(this);">
<table border="0" cellpadding="0" cellspacing="0" width="100%" >
<tr>
<td valign="top" class="contentarea">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="43240%" colspan="6" align="left" valign="middle" class="treemenutd">Delivery Settings Section</td>
        </tr>
        <tr>
          <td colspan="6" align="left" valign="middle" class="helpmsgtd"><?=$help_msg?></td>
        </tr>
		<?php
			if ($alert)
			{
		?>
        <tr>
          <td colspan="6" align="center" valign="middle" class="errormsg" id="mainerror_tr" ><?php echo $alert; ?></td>
          </tr>
		 <?php
		 	}
		 ?> 
         
		<tr>
		  <td colspan="6" align="left" valign="middle" class="tdcolorgray"><table width="100%" border="0" cellpadding="1" cellspacing="3">
           <tr>
             <td colspan="7" class="tdcolorgray">&nbsp;</td>
           </tr>
           <tr>
              <td colspan="7" class="tdcolorgray">&nbsp;<strong>First Drop Down Box Settings</strong></td>
              </tr>
            <tr>
              <td width="1%" class="">&nbsp;</td>
              <td width="13%" class="">Minimum Value</td>
              <td width="10%" class=""><input type="text" name="txt_min_gen" size="3" value="<?PHP echo $fetch_arr_admin['delivery_settings_common_min'] ?>" />
			  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_MAKE_MIN_DELIVERY')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
              <td width="15%" align="left" class="">&nbsp;Maximum Value</td>
              <td width="12%" align="left" class=""><input type="text" name="txt_max_gen" size="5" value="<?PHP echo $fetch_arr_admin['delivery_settings_common_max'] ?>"/>
			  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_MAKE_MAX_DELIVERY')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
              <td width="14%" class="" align="left">&nbsp;Increment value</td>
              <td width="35%" class="" align="left"><input type="text" name="txt_inc_gen" size="3" value="<?PHP echo $fetch_arr_admin['delivery_settings_common_increment'] ?>"/>
			  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_MAKE_INCREMENT_DELIVERY')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
            </tr>
            
            <tr>
              <td colspan="7" class="tdcolorgray">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="7" class="">&nbsp;<strong>Second Drop Down Box Settings</strong> ( This setting will be considered only if delivery method depends on weight)</td>
              </tr>
             <tr>
              <td class="">&nbsp;</td>
              <td class="">Minimum Value</td>
              <td width="10%" class=""><input type="text" name="txt_min_wgt" size="3" value="<?PHP echo $fetch_arr_admin['delivery_settings_weight_min_limit'] ?>" />
			  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_MAKE_MIN_DELIVERY_WEIGHT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
              <td align="left" class="">&nbsp;Maximum Value</td>
              <td align="left" class=""><input type="text" name="txt_max_wgt" size="5" value="<?PHP echo $fetch_arr_admin['delivery_settings_weight_max_limit'] ?>" />
			  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_MAKE_MAX_DELIVERY_WEIGHT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
              <td class="">&nbsp;Increment value</td>
              <td class=""><input type="text" name="txt_inc_wgt" size="3" value="<?PHP echo $fetch_arr_admin['delivery_settings_weight_increment'] ?>" />
			  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_MAKE_INCREMENT_DELIVERY_WEIGHT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
             </tr>
			 <tr>
              <td colspan="7" class="tdcolorgray">&nbsp;</td>
            </tr>
			  <tr>
        <td align="left" valign="middle" ><input type="checkbox" name="delivery_exclude_from_gift_prom_disc" value="1" <?php echo($fetch_arr_admin['delivery_exclude_from_gift_prom_disc'] == 1)?"checked":"";?>/></td>
        <td colspan="6" align="left" valign="middle" class="" >Exclude delivery charge from gift voucher/ promotional code discount calculation 
		<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_MAKE_PROMOTIONAL_CODE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
      </tr>
           </table></td>
		  </tr>
        <tr>
          <td colspan="6" align="right" valign="middle" class="tdcolorgray">&nbsp;</td>
        </tr>
        <tr>
         <td colspan="6" align="center" valign="middle" class="tdcolorgray"><input name="Submit" type="submit" class="red" value="Save Settings" /></td>
        </tr>
        <tr>
          <td colspan="6" align="center" valign="middle" class="tdcolorgray">&nbsp;</td>
        </tr>
      </table>
</td>
</tr>
</table>
<input type="hidden" name="retdiv_id" id="retdiv_id" value="maincontent" />
<input type="hidden" name="retdiv_more" id="retdiv_more" value="" />
<input type="hidden" name="src_page" id="src_page" value="mainshop" />
<input type="hidden" name="fpurpose" id="fpurpose" value="delivery_settings_update" />
		<input type="hidden" name="src_id" id="src_id" value="" />


</form>