<?php
	/*#################################################################
	# Script Name 	: buy_giftvouchers.php
	# Description 	: Page for adding content for buy voucher
	# Coded by 		: LSH
	# Created on	: 28-Jul-2009
	# Modified by	: LSH
	# Modified On	: 28-jul-2009
	#################################################################*/
//Define constants for this page
$page_type = 'Buy Voucher Content';
$tablename ='general_settings_sites_common';
$help_msg = get_help_messages('PRODUCT_FREE_DELIVERY_MESS1');
global $db,$ecom_siteid,$ecom_themeid ;
		$sql 							= "SELECT product_freedelivery_content FROM $tablename WHERE sites_site_id=".$ecom_siteid;
		$res_admin 				= $db->query($sql);
		$fetch_arr_admin 	= $db->fetch_array($res_admin);
		$editor_elements = "product_freedelivery_content";
	include_once(ORG_DOCROOT."/console/js/tinymce.php");
?>	

<form name='frmFreedelivery' action='home.php?request=product_free_delivery_content' method="post" >
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
      <td colspan="4" align="left" valign="middle" class="treemenutd">Product Free Delivery Content</td>
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
		<td colspan="2" align="left" valign="top" class="seperationtd" ><strong>Product Free Delivery Content</strong></td>
		</tr>
	  <tr>
	    <td align="left" valign="top" class="tdcolorgray" >&nbsp;</td>
	    <td align="left" valign="top" class="tdcolorgray" >
		<textarea style="height:300px; width:650px" id="pricepromise_topcontent" name="product_freedelivery_content"><?php echo stripslashes($fetch_arr_admin['product_freedelivery_content'])?></textarea>
		</td>
	    </tr>
	  
	  </table>
	  </td>
	  </tr>
	  <tr>
	 <td width="58%" align="center" valign="middle" class="tdcolorgray">
			<input type="hidden" name="fpurpose" id="fpurpose" value="save_add" />
        <input name="prod_Submit" type="submit" class="red" value="Save" /></td>
        
        </tr>
        <tr>
          <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
        </tr>
      </table>
</form>	  

