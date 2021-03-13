<?php
	/*#################################################################
	# Script Name 	: general_downloads_content.php
	# Description 	: Page for adding top content for general downloads page
	# Coded by 		: Sny
	# Created on	: 25-Aug-2009
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
//Define constants for this page
	$page_type = 'Price Promise Content';
	$help_msg = get_help_messages('GENERAL_DOWNLOAD_TOP_CONTENT_MESS1');
	global $db,$ecom_siteid,$ecom_themeid ;
	$sql 				= "SELECT general_download_topcontent FROM general_settings_sites_common WHERE sites_site_id=".$ecom_siteid." LIMIT 1";
	$res_admin 			= $db->query($sql);
	$fetch_arr_admin 	= $db->fetch_array($res_admin);
?>	

<form name='frmgeneraldownload' action='home.php?request=general_downloads_topcontent' method="post" >
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
      <td colspan="4" align="left" valign="middle" class="treemenutd">General Downloads Top Content</td>
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
		<td colspan="2" align="left" valign="top" class="seperationtd" ><strong>Top Content</strong></td>
		</tr>
	  <tr>
	    <td align="left" valign="top" class="tdcolorgray" >&nbsp;</td>
	    <td align="left" valign="top" class="tdcolorgray" >
		<textarea style="height:300px; width:650px" id="general_download_topcontent" name="general_download_topcontent"><?php echo stripslashes($fetch_arr_admin['general_download_topcontent'])?></textarea>
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

