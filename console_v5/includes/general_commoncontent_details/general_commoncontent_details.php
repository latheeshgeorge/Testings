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

?>	
<script language="javascript" type="text/javascript">
function Select_content_type()
{ 
	switch(document.frmContentType.content_Type.value)
	{
	 case 'bonus':
	 document.frmContentType.fpurpose.value='showcontent';
	 document.frmContentType.cbo_keytype.value='bonus';
	 document.frmContentType.submit();
	 break;
	  case 'buygift':
	 document.frmContentType.fpurpose.value='showcontent';
	 document.frmContentType.cbo_keytype.value='buygift';
	 document.frmContentType.submit();
	 break;
	  case 'download':
	 document.frmContentType.fpurpose.value='showcontent';
	 document.frmContentType.cbo_keytype.value='download';
	 document.frmContentType.submit();
	 break;
	  case 'shops':
	 document.frmContentType.fpurpose.value='showcontent';
	 document.frmContentType.cbo_keytype.value='shops';
	 document.frmContentType.submit();
	 break;
	  case 'payonacc':
	 document.frmContentType.fpurpose.value='showcontent';
	 document.frmContentType.cbo_keytype.value='payonacc';
	 document.frmContentType.submit();
	 break;
	  case 'pricepromise':
	 document.frmContentType.fpurpose.value='showcontent';
	 document.frmContentType.cbo_keytype.value='pricepromise';
	 document.frmContentType.submit();
	 break;
	 case 'freedeli':
	 document.frmContentType.fpurpose.value='showcontent';
	 document.frmContentType.cbo_keytype.value='freedeli';
	 document.frmContentType.submit();
	 break;
	  case 'spendvouch':
	 document.frmContentType.fpurpose.value='showcontent';
	 document.frmContentType.cbo_keytype.value='spendvouch';
	 document.frmContentType.submit();
	 break;
	  case 'savedsearch':
	 document.frmContentType.fpurpose.value='showcontent';
	 document.frmContentType.cbo_keytype.value='savedsearch';
	 document.frmContentType.submit();
	 break;
	 case 'comboall':
	 document.frmContentType.fpurpose.value='showcontent';
	 document.frmContentType.cbo_keytype.value='comboall';
	 document.frmContentType.submit();
	 break;
	 case 'multibuy':
	 document.frmContentType.fpurpose.value='showcontent';
	 document.frmContentType.cbo_keytype.value='multibuy';
	 document.frmContentType.submit();
	 break;
	}
}
</script>
<?php 
$content_Type =($_REQUEST['content_Type'])?$_REQUEST['content_Type']:'bonus';
$cbo_keytype =($_REQUEST['cbo_keytype'])?$_REQUEST['cbo_keytype']:'bonus';

if($cbo_keytype=='bonus')
{
 $furpose_value = 'save_add_bonus';
 $header = 'Bonus point details';
 $id = 'bonus_point_details_content';
 $textname = 'bonus_point_details_content';
 $seltype= 'bonus_point_details_content';
 $editor = 'bonus_point_details_content';
 }
 if($cbo_keytype=='buygift')
 {
 $furpose_value = 'save_add_gift';
 $header = 'Buy Gift Voucher Content';
 $id = 'voucher_buy_text';
 $textname = 'voucher_buy_text';
 $seltype= 'voucher_buy_text';
 $editor = 'voucher_buy_text';
 }
 if($cbo_keytype=='download')
 {
 $furpose_value = 'save_add_download';
 $header = 'Top Content';
 $id = 'general_download_topcontent';
 $textname = 'general_download_topcontent';
 $seltype= 'general_download_topcontent';
 $editor = 'general_download_topcontent';
 }
 if($cbo_keytype=='freedeli')
 {
 $furpose_value = 'save_add_freedeli';
 $header = 'Product Free Delivery Content';
 $id = 'product_freedelivery_content';
 $textname = 'product_freedelivery_content';
 $seltype= 'product_freedelivery_content';
 $editor = 'product_freedelivery_content';
 }
if($cbo_keytype=='payonacc')
 {
 $furpose_value = 'save_add_payonacc';
 $header = 'Pay on Account Details Content';
 $id = 'payon_account_details_content';
 $textname = 'payon_account_details_content';
 $seltype= 'payon_account_details_content';
 $editor = 'payon_account_details_content';
 }
if($cbo_keytype=='spendvouch')
 {
 $furpose_value = 'save_add_spendvouch';
 $header 		= 'Spend Gift Voucher or Promotional code Top Content';
 $id 			= 'voucher_spend_text';
 $textname 		= 'voucher_spend_text';
 $seltype		= 'voucher_spend_text';
 $editor 		= 'voucher_spend_text';
 }
 if($cbo_keytype=='multibuy')
 {
 $furpose_value = 'save_add_multibuy';
 $header = 'Multibuy Bottom Content';
 $id = 'general_multibuy_bottomcontent';
 $textname = 'general_multibuy_bottomcontent';
 $seltype= 'general_multibuy_bottomcontent';
 $editor = 'general_multibuy_bottomcontent';
 }
 if($cbo_keytype=='pricepromise')
{
$seltype	= 'pricepromise_topcontent,pricepromise_bottomcontent,pricepromise_toaddress,general_pricepromise_addtocart';
$editor 	= 'pricepromise_topcontent,pricepromise_bottomcontent,general_pricepromise_addtocart';
 $furpose_value = 'save_add_pricepromise';
}
 if($cbo_keytype=='shops')
{
$seltype	= 'general_shopsall_topcontent,general_shopsall_bottomcontent';
$editor 	= 'general_shopsall_topcontent,general_shopsall_bottomcontent';
 $furpose_value = 'save_add_shops';
}
if($cbo_keytype=='savedsearch')
{
$seltype	= 'general_savedsearch_topcontent,general_savedsearch_bottomcontent';
$editor 	= 'general_savedsearch_topcontent,general_savedsearch_bottomcontent';
$furpose_value = 'save_add_savedsearch';
}
if($cbo_keytype=='comboall')
{
$seltype	= 'general_comboall_topcontent,general_comboall_bottomcontent';
$editor 	= 'general_comboall_topcontent,general_comboall_bottomcontent';
$furpose_value = 'save_add_comboall';
}
	$tablename ='general_settings_sites_common';
	$help_msg = get_help_messages('CONTENT_DETAILS_MESS1');
	global $db,$ecom_siteid,$ecom_themeid,$ecom_hostname ;
	$sql 							= "SELECT $seltype FROM $tablename WHERE sites_site_id=".$ecom_siteid;
	$res_admin 				= $db->query($sql);
	$fetch_arr_admin 	= $db->fetch_array($res_admin);
	$editor_elements = $editor;
	include_once(ORG_DOCROOT."/console/js/tinymce.php");
	$textvalue = stripslashes($fetch_arr_admin[$seltype]);
?>
<form name='frmContentType' action='home.php?request=general_commoncontent_details' method="post" >
<input type="hidden" name="cbo_keytype" value="<?=$cbo_keytype?>" />
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
      <td colspan="4" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span> General Page Content</span></div></td>
    </tr>
	<tr>
	  <td colspan="4" align="left" valign="middle" class="helpmsgtd_main">
	  <?php 
		  Display_Main_Help_msg($help_arr,$help_msg);
	  ?>	 </td>
	</tr>
	 <tr>
		<td colspan="4" align="left" valign="top" class="tdcolorgray" >&nbsp;</td>
	  </tr>
	<tr>
		<td colspan="4" align="left" valign="top" class="tdcolorgray" >
		<div class="editarea_div">
		<table width="100%">
		<tr>
		<td colspan="4" class="tdcolorgray">Select Page Type
			<select name="content_Type" id="content_Type" onchange="Select_content_type();">
				<option value="">--Select Page Type--</option>
				<option value="bonus" <? if($content_Type=='bonus') echo "selected";?>>Bonus Point Details</option>
				<option value="buygift" <? if($content_Type=='buygift') echo "selected";?>>Buy Gift Voucher Content</option>
				<option value="download" <? if($content_Type=='download') echo "selected";?>>General Downloads Top Content</option>
				<option value="shops" <? if($content_Type=='shops') echo "selected";?>>General Shops Content</option>
				<option value="multibuy" <? if($content_Type=='multibuy') echo "selected";?>>Multibuy Bottom Content</option>
				<option value="payonacc" <? if($content_Type=='payonacc') echo "selected";?>>Pay on Account Content</option>
				<option value="pricepromise" <? if($content_Type=='pricepromise') echo "selected";?>>Price Promise Content</option>
				<option value="freedeli" <? if($content_Type=='freedeli') echo "selected";?>>Product Free Delivery Content</option>
				<option value="spendvouch" <? if($content_Type=='spendvouch') echo "selected";?>>Spend Vouchers Content</option>
				<option value="savedsearch" <? if($content_Type=='savedsearch') echo "selected";?>>Saved Search Content</option>
				<option value="comboall" <? if($content_Type=='comboall') echo "selected";?>>Show All Combo Content</option>
			</select>
		</td>
		</tr>
	<?
	if($cbo_keytype=='bonus' || $cbo_keytype=='buygift' || $cbo_keytype=='download' || $cbo_keytype=='freedeli' || $cbo_keytype=='payonacc' || $cbo_keytype=='spendvouch' || $cbo_keytype=='multibuy' )
{

	?>
        <tr><td class="tdcolorgray">
	  <table width="100%" border="0">
	  <tr>
		<td colspan="2" align="left" valign="top" class="seperationtd" ><strong><?=$header?></strong></td>
		</tr>
	  <tr>
	    <td align="left" valign="top" class="tdcolorgray" >&nbsp;</td>
	    <td align="left" valign="top" class="tdcolorgray" >
		<textarea style="height:300px; width:650px" id="<?=$id?>" name="<?=$textname?>"><?php echo $textvalue?></textarea>
		</td>
	    </tr>
	  
	  </table>
	  </td>
	  </tr>
	  <?
	  }
	  elseif($cbo_keytype=='pricepromise')
	  {
	  ?>
	  <tr><td class="tdcolorgray">
	  <table width="100%" border="0">
	   <tr>
		<td colspan="2" align="left" valign="top" class="tdcolorgray" >&nbsp;</td>
	  </tr>
		 <?php /*?><tr>
		<td align="left" valign="top" class="seperationtd" ><strong>Price Promise To Address</strong></td>
		<td align="left" valign="top" class="seperationtd" ><input type="text" size="40" name="pricepromise_toaddress" value="<?php echo stripslashes($fetch_arr_admin['pricepromise_toaddress'])?>" /></td> 
		</tr><?php */?>
	  <tr>
		<td colspan="2" align="left" valign="top" class="seperationtd" ><strong>Price Promise Top Content</strong></td>
		</tr>
	  <tr>
	    <td align="left" valign="top" class="tdcolorgray" >&nbsp;</td>
	    <td align="left" valign="top" class="tdcolorgray" >
		<textarea style="height:300px; width:650px" id="pricepromise_topcontent" name="pricepromise_topcontent"><?php echo stripslashes($fetch_arr_admin['pricepromise_topcontent'])?></textarea>		</td>
	    </tr>
		<tr>
		<td colspan="2" align="left" valign="top" class="seperationtd" ><strong>Price Promise Bottom Content</strong></td>
		</tr>
	  <tr>
	    <td align="left" valign="top" class="tdcolorgray" >&nbsp;</td>
	    <td align="left" valign="top" class="tdcolorgray" >
		<textarea style="height:300px; width:650px" id="pricepromise_bottomcontent" name="pricepromise_bottomcontent"><?php echo stripslashes($fetch_arr_admin['pricepromise_bottomcontent'])?></textarea>		</td>
	    </tr>
	  <tr>
	    <td colspan="2" align="left" valign="top" class="seperationtd" ><strong>Price Promise Add to Cart Top Content </strong></td>
	    </tr>
	  <tr>
	    <td align="left" valign="top" class="tdcolorgray" >&nbsp;</td>
	    <td align="left" valign="top" class="tdcolorgray" >
		<textarea style="height:300px; width:650px" id="general_pricepromise_addtocart" name="general_pricepromise_addtocart"><?php echo stripslashes($fetch_arr_admin['general_pricepromise_addtocart'])?></textarea>
		</td>
	    </tr>
	  </table>
	  </td>
	  </tr>
	  <?php
	  }
	  else if($cbo_keytype=='shops')
	  {
	  ?>
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
	  <?
	  }
	   else if($cbo_keytype=='savedsearch')
	  {
	  ?>
	  <tr><td class="tdcolorgray">
	  <table width="100%" border="0">
	   <tr>
		<td colspan="2" align="left" valign="top" class="tdcolorgray" >&nbsp;</td>
	  </tr>
	  <tr>
		<td colspan="2" align="left" valign="top" class="seperationtd" ><strong>Saved Search Top Content</strong></td>
		</tr>
	  <tr>
	    <td align="left" valign="top" class="tdcolorgray" >&nbsp;</td>
	    <td align="left" valign="top" class="tdcolorgray" >
		<textarea style="height:300px; width:650px" id="general_savedsearch_topcontent" name="general_savedsearch_topcontent"><?php echo stripslashes($fetch_arr_admin['general_savedsearch_topcontent'])?></textarea>
		</td>
	    </tr>
	  
	   <tr>
		<td colspan="2" align="left" valign="top" class="seperationtd" ><strong>Saved Search Bottom Content</strong></td>
		</tr>
	  <tr>
	    <td align="left" valign="top" class="tdcolorgray" >&nbsp;</td>
	    <td align="left" valign="top" class="tdcolorgray" >
		<textarea style="height:300px; width:650px" id="general_savedsearch_bottomcontent" name="general_savedsearch_bottomcontent"><?php echo stripslashes($fetch_arr_admin['general_savedsearch_bottomcontent'])?></textarea>
		</td>
	    </tr>
	  </table>
	  </td>
	  </tr>
	  <?
	  }
	   else if($cbo_keytype=='comboall')
	  {
	  ?>
	  <tr><td class="tdcolorgray">
	  <table width="100%" border="0">
	   <tr>
		<td colspan="2" align="left" valign="top" class="tdcolorgray" >&nbsp;</td>
	  </tr>
	  <tr>
		<td colspan="2" align="left" valign="top" class="seperationtd" ><strong>ComboAll Top Content</strong></td>
		</tr>
	  <tr>
	    <td align="left" valign="top" class="tdcolorgray" >&nbsp;</td>
	    <td align="left" valign="top" class="tdcolorgray" >
		<textarea style="height:300px; width:650px" id="general_comboall_topcontent" name="general_comboall_topcontent"><?php echo stripslashes($fetch_arr_admin['general_comboall_topcontent'])?></textarea>
		</td>
	    </tr>
	  
	   <tr>
		<td colspan="2" align="left" valign="top" class="seperationtd" ><strong>ComboAll Bottom Content</strong></td>
		</tr>
	  <tr>
	    <td align="left" valign="top" class="tdcolorgray" >&nbsp;</td>
	    <td align="left" valign="top" class="tdcolorgray" >
		<textarea style="height:300px; width:650px" id="general_comboall_bottomcontent" name="general_comboall_bottomcontent"><?php echo stripslashes($fetch_arr_admin['general_comboall_bottomcontent'])?></textarea>
		</td>
	    </tr>
	  </table>
	  </td>
	  </tr>
	  <?
	  }
	  ?>
	  </table>
	  </div>
	  </td>
	  </tr>
	  <tr>
      <td colspan="4" align="center" valign="middle" class="tdcolorgray" >
	  <div class="editarea_div">
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
	 <td width="58%" align="right" valign="middle" class="tdcolorgray">
			<input type="hidden" name="fpurpose" id="fpurpose" value="<?=$furpose_value?>" />
        <input name="prod_Submit" type="submit" class="red" value="Save" /></td>
        
        </tr>
		</table>
		</div>
		</td>
		</tr>
      </table>
</form>	  
