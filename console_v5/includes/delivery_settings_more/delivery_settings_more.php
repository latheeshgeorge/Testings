<?php
	/*
	#################################################################
	# Script Name 		: delivery_settings_more.php
	# Description 		: Page for managing the delivery settings
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
									   delivery_exclude_from_gift_prom_disc,delivery_extra_shipping_minimum_qty, enable_location_datetime,
									   date_compulsary,time_compulsary,allow_free_delivery, free_delivery_mintotal, allow_free_delivery_location
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
<script language="javascript">
	/*
function enable_min_ordtot()
{
	if(document.getElementById("allow_free_delivery").checked)
	{
		document.getElementById("min_ordtot").style.display	=	'block';
	}
	else
	{
		document.getElementById("min_ordtot").style.display	=	'none';
	}
}
*/
function enable_date_copulsary()
{
	if(document.getElementById("enable_location_datetime").checked)
	{
		document.getElementById("enable_date_copulsary").style.display	=	'block';

	}
	else
	{
		document.getElementById("enable_date_copulsary").style.display	=	'none';

	}
}
</script>
<form name="frmDeliverySettings" method="post" action="home.php?request=delivery_settings_more&fpurpose=delivery_settings_more" onsubmit="return valforms(this);">
<table border="0" cellpadding="0" cellspacing="0" width="100%" >
<tr>
<td valign="top" class="contentarea">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span> Delivery Settings Section</span></div></td>
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
          <td colspan="2" align="center" valign="middle" class="errormsg" id="mainerror_tr" ><?php echo $alert; ?></td>
          </tr>
		 <?php
		 	}
		 ?> 
         
		<tr>
		  <td colspan="2" align="left" valign="middle" class="tdcolorgray">
		  <div class="sorttd_div" >
		  <table width="100%" border="0" cellpadding="1" cellspacing="3">
           <tr>
             <td colspan="7" class="tdcolorgray">&nbsp;</td>
           </tr>
           <tr>
              <td colspan="7" class="tdcolorgray"><strong> Drop Down Box Settings</strong></td>
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
           <?php /*?> <tr>
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
             </tr><?php */?>
			 <tr>
              <td colspan="7" class="tdcolorgray">&nbsp;</td>
            </tr>
            <tr>
        <td align="left" valign="middle" ><input type="checkbox" name="delivery_exclude_from_gift_prom_disc" value="1" <?php echo($fetch_arr_admin['delivery_exclude_from_gift_prom_disc'] == 1)?"checked":"";?>/></td>
        <td colspan="6" align="left" valign="middle" class="" >Exclude delivery charge from gift voucher/ promotional code discount calculation 
		<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_MAKE_PROMOTIONAL_CODE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.gif" width="17" height="13" border="0" /></a></td>
      </tr>
       <tr>
            <td align="left" valign="middle" ><input name="enable_location_datetime" type="checkbox" id="enable_location_datetime" value="1" <?php echo($fetch_arr_admin['enable_location_datetime'] == 1)?"checked":"";?> onclick="javascript: enable_date_copulsary();"/></td>
            <td colspan="6" align="left" valign="middle" class="" >Enable location datetime settings<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_LOCATION_DATETIME_GENERAL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.gif" width="17" height="13" border="0" /></a></td>
            </tr>
          <tr>
            <td colspan="7" align="left" valign="middle" ><div id="enable_date_copulsary" style="display:none;">
                <table border="0" width="100%">
                    <tr><td>&nbsp;</td><td>
                Date Compulsary ? 
                <input name="date_compulsary" type="checkbox" id="date_compulsary" value="1" <?php echo($fetch_arr_admin['date_compulsary'] == 1)?"checked":"";?> />
                </td></tr>
                <tr><td>&nbsp;</td><td>
                 Time Compulsary ? 
                <input name="time_compulsary" type="checkbox" id="time_compulsary" value="1" <?php echo($fetch_arr_admin['time_compulsary'] == 1)?"checked":"";?> />
                 </td></tr>
                </table>
                </div>
                
            <?php 
                if($fetch_arr_admin['enable_location_datetime'] == 1)
                {
            ?>	<script language="javascript">document.getElementById("enable_date_copulsary").style.display	=	'block';</script>
            <?php
                }
            ?>
            </td>
            </tr>
            <?php
            /*
            ?>
        <tr>
            <td align="left" valign="middle" ><input name="allow_free_delivery" type="checkbox" id="allow_free_delivery" value="1" <?php echo($fetch_arr_admin['allow_free_delivery'] == 1)?"checked":"";?> onclick="javascript: enable_min_ordtot();"/>
            </td>
            <td colspan="6" align="left" valign="middle" class="" >Allow free delivery based on the order subtotal?</td>
            </tr>
       <tr>
            <td colspan="7" align="left" valign="middle" ><div id="min_ordtot" style="display:none;">The minimum subtotal to allow free delivery 
                <input type="text" name="free_delivery_mintotal" id="free_delivery_mintotal" value="<?php echo $fetch_arr_admin['free_delivery_mintotal'] ?>" /></div>
                
            <?php 
                if($fetch_arr_admin['allow_free_delivery'] == 1)
                {
            ?>	<script language="javascript">document.getElementById("min_ordtot").style.display	=	'block';</script>
            <?php
                }
            ?>
            </td>
            </tr>
            */?> 
        <tr>
            <td align="left" valign="middle" ><input name="allow_free_delivery_location" type="checkbox" id="allow_free_delivery_location" value="1" <?php echo($fetch_arr_admin['allow_free_delivery_location'] == 1)?"checked":"";?>/>
            </td>
            <td colspan="6" align="left" valign="middle" class="" >Allow free delivery based on the order subtotal for location?</td>
            </tr>
            
	  <tr>
          <td align="left" valign="middle" class="tdcolorgray" style="padding-left:22px;" colspan="7">Apply Extra Shipping Cost of Product to 
		    <select name="delivery_extra_shipping_minimum_qty" id="delivery_extra_shipping_minimum_qty">
		      <option value="0" <?php echo ($fetch_arr_admin['delivery_extra_shipping_minimum_qty'] == 0)?"selected":"";?>>Entire Quantity</option>
		      <option value="1" <?php echo ($fetch_arr_admin['delivery_extra_shipping_minimum_qty'] == 1)?"selected":"";?>>Quantity Above First Qty</option>
		        </select>
				<a href="#" onmouseover ="ddrivetip('If Extra shipping Cost is set for a product, then this option allows to decide whether the extra shipping is to be applied to the total quantity ordered or to the quantity above the first item. <br><br>That is, if the option <b>Entire Quantity</b> is selected and if the quantity ordered is 2, then the extra shipping cost will be calculated for the entire quantity 2. On the other hand, if the option selected is <b>Quantity Above First Qty</b> and if the quantity ordered is 2, then the extra shipping cost will be calculated for only for 1 quantity (i.e 2 - 1).')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          <td width="34%" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
        </tr>
           </table>
		   </div>
		   </td>
		  </tr>
        
        <tr>
         <td colspan="7" align="center" valign="middle" class="tdcolorgray">
		 <div class="sorttd_div" >
		 <table width="100%" cellpadding="0" cellspacing="0">
		 <tr>
		 	<td width="100%" align="right" valign="middle"><input name="Submit" type="submit" class="red" value="Save Settings" /></td>
		</tr>
		</table>
		</div>
		</td>
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
