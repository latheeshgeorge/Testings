<?php
	/*#################################################################
	# Script Name 	: add_giftvouchers.php
	# Description 	: Page for adding giftvouchers
	# Coded by 		: Sny
	# Created on	: 01-Aug-2007
	# Modified by	: Sny
	# Modified On	: 16-May-2008
	#################################################################*/
//Define constants for this page
$page_type = 'Gift Vouchers';
$help_msg = get_help_messages('ADD_GIFT_VOUCH_MESS1');
?>	
<script language="javascript" type="text/javascript">
function valforms(frm)
{
	var atleastone = false;
	fieldRequired = Array('voucher_boughton','voucher_expireson','voucher_value','voucher_max_usage');
	fieldDescription = Array('Active Date','Expired Date','Discount','Maximum Usage');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array('voucher_value','voucher_max_usage');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric))
	{
		if(frm.voucher_type.value=='per' && (isNaN(frm.voucher_value.value)|| frm.voucher_value.value >100 || frm.voucher_value.value < 0 ))
		{
			alert("Please Enter a numeric value less than or equal to 100 as Discount Percentage");
			frm.voucher_value.focus();
			return false;
		}
		else if(frm.voucher_type.value=='val' && (frm.voucher_value.value == "" || frm.voucher_value.value < 0) )
		{
			alert("Please Enter a numeric value");
			frm.voucher_value.focus();
			return false;
		}
		else if(frm.voucher_type.value=='val' && frm.voucher_value.value == "" )
		{
			alert("Please Enter-Discount for Minimum");
			frm.voucher_value.focus();
			return false;
		} 
		else if (frm.voucher_max_usage.value!="" && frm.voucher_max_usage.value<0) {
			alert("Please Enter-Maximum usage greater than Zero");
			frm.voucher_max_usage.focus();
			return false;

		}
		val_dates = compareDates_giftvoucher(frm.voucher_boughton,"Active Date\n Correct Format:dd-mm-yyyy ",frm.voucher_expireson,"Expiry Date\n Correct Format:dd-mm-yyyy");
			if(val_dates ){
				  show_processing();
				  return true;
			 }else{
			 return false
			 }
	}	
	else
		return false;
}
function handle_codetype(val)
{
	if(val=='val')
	{
		document.getElementById('dis_val').innerHTML = 'Discount Value';
	}
	else if (val=='per')
	{
		document.getElementById('dis_val').innerHTML = 'Discount %';
	}
}
</script>
<?php 
	// Get the default settings to be picked from general_settings_sites_common table
	$sql_settings = "SELECT gift_voucher_apply_customer_direct_disc_also, gift_voucher_apply_customer_group_disc_also,gift_voucher_apply_direct_product_discount_also  
						FROM 
							general_settings_sites_common 
						WHERE 
							sites_site_id = $ecom_siteid 
						LIMIT 
							1";
	$ret_settings = $db->query($sql_settings);
	if ($db->num_rows($ret_settings))
	{
		$row_settings 		= $db->fetch_array($ret_settings);
		$gift_direct 		= $row_settings['gift_voucher_apply_customer_direct_disc_also'];
		$gift_group 		= $row_settings['gift_voucher_apply_customer_group_disc_also'];
		$gift_proddirect 	= $row_settings['gift_voucher_apply_direct_product_discount_also'];
	}
?>
<form name='frmAddGiftVoucher' action='home.php?request=gift_voucher' method="post" onsubmit="return valforms(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=gift_voucher&vouchernumber=<?php echo $_REQUEST['vouchernumber']?>&paystatus=<?php echo $_REQUEST['paystatus']?>&addedby=<?php echo $_REQUEST['addedby']?>&start=<?php echo $_REQUEST['start']?>&pg=<?php echo $_REQUEST['pg']?>&records_per_page=<?php echo $_REQUEST['records_per_page']?>&sort_by=<?php echo $_REQUEST['sort_by']?>&sort_order=<?php echo $_REQUEST['sort_order']?>">List Gift Vouchers</a><span> Add Gift Vouchers</span></div></td>
        </tr>
       <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="4">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
		<?php
			if($alert)
			{
		?>
        <tr>
          <td colspan="4" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
          </tr>
		 <?php
		 	}
		 ?>
         <tr>
           <td colspan="4" align="left" valign="top" class="tdcolorgraynormal" >
		   <div class="editarea_div">
		   <table width="100%" border="0" cellspacing="1" cellpadding="1">
             <tr>
               <td width="16%" align="left">Activate On&nbsp;<span class="redtext">*</span></td>
               <td width="31%" align="left" valign="top"><input name="voucher_boughton" type="text" id="voucher_boughton" value="<?php echo $_REQUEST['voucher_boughton']?>" size="10"/>&nbsp;&nbsp;
			   <a style="vertical-align:bottom;" href="javascript:show_calendar('frmAddGiftVoucher.voucher_boughton');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a>
			   &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_GIFT_VOUCH_STDATE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
              
               <td width="16%" align="left">Expires on&nbsp;<span class="redtext">*</span> </td>
               <td width="19%" align="left" valign="top"><input name="voucher_expireson" type="text" id="voucher_expireson" value="<?php echo $_REQUEST['voucher_expireson']?>" size="10"/>&nbsp;&nbsp;
               <a style="vertical-align:bottom;" href="javascript:show_calendar('frmAddGiftVoucher.voucher_expireson');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a>
			   &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_GIFT_VOUCH_ENDDATE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
               <td width="18%" align="left">&nbsp;</td>
             </tr>
             <tr>
               <td align="left">Voucher Type </td>
               <td align="left"><?php 
			  $ctype = $row_gift['voucher_type'];
			   ?>
                 <select name="voucher_type" id="voucher_type" onchange="handle_codetype(this.value)">
                   <option value="val" <?php echo ($_REQUEST['voucher_type']=='val')?'selected':''?>>Value</option>
                   <option value="per" <?php echo ($_REQUEST['voucher_type']=='per')?'selected':''?>>%</option>
                 </select>
				 &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_GIFT_VOUCH_TYPE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
               <td align="left">Hide</td>
               <td colspan="2" align="left"><input type="radio" name="voucher_hide" value="1" <?php echo ($_REQUEST['voucher_hide']==1)?'checked="checked"':''?> />
Yes
  <input name="voucher_hide" type="radio" value="0" checked="checked" <?php echo ($_REQUEST['voucher_hide']==0)?'checked="checked"':''?> />
No<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_GIFT_VOUCH_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
             </tr>
             <tr id="tr_discval">
               <td align="left"><div id='dis_val'>Discount Value <span class="redtext">*</span></div></td>
               <td align="left"><input name="voucher_value" type="text" size="8" value="<?php echo $_REQUEST['voucher_value']?>" />
			   &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_GIFT_VOUCH_DISVALUE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                
               <td align="left">Maximum Usage<span class="redtext"> *</span></td>
               <td align="left"><input name="voucher_max_usage" type="text" id="voucher_max_usage" value="1" size="3"/> &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_GIFT_VOUCH_NO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
               <td align="left">&nbsp;</td>
             </tr>
             <tr>
               <td colspan="6" align="right">&nbsp;</td>
             </tr>
             <tr>
               <td align="right">&nbsp;</td>
               <td align="right"><input type="checkbox" name="voucher_login_touse" value="1" <?php echo ($_REQUEST['voucher_login_touse']==1)?'checked="checked"':''?> /></td>
               <td colspan="4" align="left">Users must be logged in to use this voucher (tick box for yes)&nbsp;
			   <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_GIFT_VOUCH_LOG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
             </tr>
			 <tr>
               <td align="right">&nbsp;</td>
               <td align="right"><input type="checkbox" name="voucher_freedelivery" value="1"  <?php echo ($_REQUEST['voucher_freedelivery']==1)?'checked="checked"':''?>/></td>
               <td align="left" colspan="4" >Allow Free Delivery&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ALLOW_FREE_DELIVERY_GIFT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
             </tr>
             <tr>
               <td align="right">&nbsp;</td>
               <td align="right"><input type="checkbox" name="voucher_apply_direct_discount_also" value="1"  <?php echo ($gift_direct=='Y')?'checked="checked"':''?>/></td>
               <td align="left" colspan="4" >Apply Customer Direct Discount also?&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('APPLY_CUST_DIRECT_DISC_VOUCH')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
             </tr>
             <tr>
               <td align="right">&nbsp;</td>
               <td align="right"><input type="checkbox" name="voucher_apply_custgroup_discount_also" value="1"  <?php echo ($gift_group=='Y')?'checked="checked"':''?>/></td>
               <td align="left" colspan="4" >Allow Customer Group Discount also?&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('APPLY_CUST_GROUP_DISC_VOUCH')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
             </tr>
              <tr>
               <td align="right">&nbsp;</td>
               <td align="right"><input type="checkbox" name=voucher_apply_direct_product_discount_also value="1"  <?php echo ($gift_proddirect=='Y')?'checked="checked"':''?>/></td>
               <td align="left" colspan="4" >Allow Product Direct Discount also?&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('APPLY_PROD_DIRECT_DISC_VOUCH')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
             </tr>
           </table>
		   </div></td>
         </tr>
        <tr>
          <td colspan="4" align="left" valign="middle" class="tdcolorgray" >
		  <div class="editarea_div">
		  <table  border="0" cellpadding="0" cellspacing="0" class="listingtable" width="100%">
			<tr>
				<td width="100%" align="right" valign="middle">
				<input type="hidden" name="vouchernumber" id="vouchernumber" value="<?=$_REQUEST['vouchernumber']?>" />
				<input type="hidden" name="paystatus" id="paystatus" value="<?=$_REQUEST['paystatus']?>" />
				<input type="hidden" name="addedby" id="addedby" value="<?=$_REQUEST['addedby']?>" />
				<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
				<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
				<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
				<input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
				<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
				<input type="hidden" name="fpurpose" id="fpurpose" value="save_add" />
				<input type="hidden" name="retdiv_id" id="retdiv_id" value="maincontent" />
				<input name="prod_Submit" type="submit" class="red" value="Save" />
				</td>
			</tr>
		</table>
		</div>
		</td>
		</tr>
		
        <tr>
          <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td colspan="3" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
        </tr>
      </table>
</form>	  

