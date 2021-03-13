<?php
	/*#################################################################
	# Script Name 	: spend_giftvouchers.php
	# Description 	: Page for adding content for spend voucher
	# Coded by 		: LSH
	# Created on	: 28-Jul-2009
	# Modified by	: LSH
	# Modified On	: 28-Jul-2009
	#################################################################*/
//Define constants for this page
$page_type = 'Spend Voucher Content';
$help_msg = get_help_messages('SPEND_GIFT_VOUCH_MESS1');
global $db,$ecom_siteid,$ecom_themeid ;
		$sql 							= "SELECT voucher_spend_text FROM general_settings_sites_common WHERE sites_site_id=".$ecom_siteid;
		$res_admin 				= $db->query($sql);
		$fetch_arr_admin 	= $db->fetch_array($res_admin);
		$editor_elements = "voucher_spend_text";
	include_once(ORG_DOCROOT."/console/js/tinymce.php");
?>	

<form name='frmAddGiftVoucher' action='home.php?request=spend_voucher' method="post" >
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
      <td colspan="4" align="left" valign="middle" class="treemenutd">Spend Voucher</td>
    </tr>
	<tr>
	  <td colspan="4" align="left" valign="middle" class="helpmsgtd_main">
	  <?php 
		  Display_Main_Help_msg($help_arr,$help_msg);
	  ?>
	 </td>
	</tr>
        <tr><td class="tdcolorgray">
	  <table width="100%" border="0">
	   <tr>
		<td colspan="2" align="left" valign="top" class="tdcolorgray" >&nbsp;</td>
	  </tr>
		<tr>
		<td colspan="2" align="left" valign="top" class="seperationtd" ><strong>Spend Gift Voucher or Promotional code Top Content</strong></td>
		</tr>
	  <tr>
	    <td align="left" valign="top" class="tdcolorgray" >&nbsp;</td>
	    <td align="left" valign="top" class="tdcolorgray" ><textarea style="height:300px; width:650px" id="voucher_spend_text" name="voucher_spend_text"><?php echo stripslashes($fetch_arr_admin['voucher_spend_text'])?></textarea></td>
	    </tr>
	  </table>
	  </td>
	  </tr>
	  <tr>
	  <td width="58%" align="center" valign="middle" class="tdcolorgray">
			<input type="hidden" name="fpurpose" id="fpurpose" value="save_add" />
			<input type="hidden" name="retdiv_id" id="retdiv_id" value="maincontent" />
          <input name="prod_Submit" type="submit" class="red" value="Save" /></td>
        </tr>
        <tr>
          <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
        </tr>
      </table>
</form>	  

