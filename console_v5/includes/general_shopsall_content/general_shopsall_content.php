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
$page_type = 'Shops Content';
$tablename ='general_settings_sites_common';
$help_msg = get_help_messages('PRODUCT_SHOPS_ALL_MESS1');
global $db,$ecom_siteid,$ecom_themeid ;
		$sql 							= "SELECT general_shopsall_topcontent,general_shopsall_bottomcontent FROM $tablename WHERE sites_site_id=".$ecom_siteid;
		$res_admin 				= $db->query($sql);
		$fetch_arr_admin 	= $db->fetch_array($res_admin);
		$editor_elements = "general_shopsall_topcontent,general_shopsall_bottomcontent";
	include_once(ORG_DOCROOT."/console/js/tinymce.php");
?>	
<form name='frmShopsall' action='home.php?request=general_shopsall_content' method="post" >
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
      <td colspan="4" align="left" valign="middle" class="treemenutd">Shops Content</td>
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
		<td colspan="2" align="left" valign="top" class="seperationtd" ><strong>Shops Top Content</strong></td>
		</tr>
	  <tr>
	    <td align="left" valign="top" class="tdcolorgray" >&nbsp;</td>
	    <td align="left" valign="top" class="tdcolorgray" >
		<textarea style="height:300px; width:650px" id="general_shopsall_topcontent" name="general_shopsall_topcontent"><?php echo stripslashes($fetch_arr_admin['general_shopsall_topcontent'])?></textarea>
		</td>
	    </tr>
	  
	   <tr>
		<td colspan="2" align="left" valign="top" class="seperationtd" ><strong>Shops Bottom Content</strong></td>
		</tr>
	  <tr>
	    <td align="left" valign="top" class="tdcolorgray" >&nbsp;</td>
	    <td align="left" valign="top" class="tdcolorgray" >
		<textarea style="height:300px; width:650px" id="general_shopsall_bottomcontent" name="general_shopsall_bottomcontent"><?php echo stripslashes($fetch_arr_admin['general_shopsall_bottomcontent'])?></textarea>
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

