<?php
	/*
	#################################################################
	# Script Name 	: edit_settings_gift_wrap.php
	# Description 	: Page for editing the gift wrap settings for the site
	# Coded by 		: ANU
	# Created on	: 25-Jun-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################
	*/	
	
	$help_msg = get_help_messages('EDIT_GIFT_WRAP_SETINGS_MESS1');
	# Retrieving the values of super admin from the table
	$sql = "SELECT giftwrap_id,giftwrap_per,giftwrap_minprice,giftwrap_active,giftwrap_messageprice 
				FROM general_settings_site_giftwrap WHERE sites_site_id=".$ecom_siteid;
	$res_giftwrap			= $db->query($sql);
	$row 	= $db->fetch_array($res_giftwrap);
?>
<script language="javascript" type="text/javascript">
function valform(frm)
{
	fieldRequired = Array('giftwrap_minprice','giftwrap_messageprice');
	fieldDescription = Array('Gift Wrap Minimum Price','Gift Wrap Message Price');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array('giftwrap_minprice','giftwrap_messageprice');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		if(frm.giftwrap_minprice.value<0)
		{
		  alert('Gift Wrap Minimum Price entered should be a positive value.');
		  frm.giftwrap_minprice.focus();
		  return false;
		}
		else if(frm.giftwrap_messageprice.value<0)
		{
		  alert('Gift Wrap Message Price entered should be a positive value.');
		  frm.giftwrap_messageprice.focus();
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
<form name="frmGeneralSettingsGiftWrap" method="post" action="home.php?request=general_settings_Gift_Wrap&fpurpose=settings_Gift_Wrap" onsubmit="return valform(this);">
<table border="0" cellpadding="0" cellspacing="0" width="100%" >
<tr>
<td valign="top" class="contentarea">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span>Gift Wrap Settings</span></div></td>
        </tr>
       <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="3">
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
          <td colspan="3" align="center" valign="middle" class="errormsg" ><?php echo $alert; ?></td>
          </tr>
		 <?php
		 	}
		 ?>
           <tr>
          <td colspan="3" align="center" valign="middle" class="tdcolorgray" >
		  <div class="editarea_div">
		  <table width="100%">
		  <tr>
         <td width="7%" align="right" valign="middle" class="tdcolorgray"  >&nbsp;</td>
		 <td align="left" valign="middle" class="tdcolorgray"  >Gift Wrap Per</td>
		 <td width="76%" align="left" valign="middle" class="tdcolorgray"  > 
		   <select name="giftwrap_per">
		     <option value="item" <?php if ($row['giftwrap_per']=='item') echo "selected='selected'";?>>Item</option>
		     <option value="order"  <?php if ($row['giftwrap_per']=='order') echo "selected='selected'";?>>Order</option>
	        </select>		<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_GIFT_WRAP_SETTNGS_PER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>  </td>
        </tr>
		<tr>
          
         <td  align="right" valign="middle" class="tdcolorgray">&nbsp;</td>
		 <td  align="left" valign="middle" class="tdcolorgray">Gift Wrap Minimum Price<span class="redtext">*</span> (<?php echo  display_curr_symbol()?>)	</td>
		 <td align="left" valign="middle" class="tdcolorgray"  ><input type="text" name="giftwrap_minprice"  value="<?=$row['giftwrap_minprice']?>" size="7"/>		 </td>
        </tr>
		
		<tr>
          
         <td  align="right" valign="middle" class="tdcolorgray">&nbsp;</td>
		 <td  align="left" valign="middle" class="tdcolorgray">Gift Wrap Message Price<span class="redtext">*</span> (<?php echo  display_curr_symbol()?>)</td>
		 <td align="left" valign="middle" class="tdcolorgray"  ><input type="text" name="giftwrap_messageprice" value="<?=$row['giftwrap_messageprice']?>" size="7" />		</td>
        </tr>
		<tr>
          
         <td   align="right" valign="middle" class="tdcolorgray">&nbsp;</td>
		 <td   align="left" valign="middle" class="tdcolorgray">Gift Wrap Active</td>
		 <td align="left" valign="middle" class="tdcolorgray"  >
		   <input name="giftwrap_active" type="radio" value="1"  <?php if ($row['giftwrap_active']=='1') echo "checked='checked'";?> />
		   Yes
		   <input name="giftwrap_active" type="radio" value="0" <?php if ($row['giftwrap_active']=='0') echo "checked='checked'";?>  /> 
		   No 	<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_GIFT_WRAP_SETTNGS_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>	  </td>
        </tr>
		
		</table>
		</div>
		</td>
		</tr>
		
		<tr>
          
         <td colspan="3" align="right" valign="middle" class="tdcolorgray">
		 <div class="editarea_div">
		  <table width="100%">
		  <tr>
			  <td align="right" valign="middle" class="tdcolorgray">	
			  <input name="Submit" type="submit" class="red" value="Save" />
			  </td>
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