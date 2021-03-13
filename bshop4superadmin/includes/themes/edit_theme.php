<?php
/*#################################################################
# Script Name 	: edit_theme.php
# Description 	: Page for editing themes 
# Coded by 		: sny
# Created on	: 31-May-2007
# Modified by	: Sny
# Modified On	: 26-Nov-2007
# Modified by	: Joby
# Modified On	: 11-May-2011
#################################################################
*/
//#Define constants for this page
$page_type 	= 'Theme';
$help_msg 	= 'This section helps in editing the values for a Theme.';

//#Sql
$sql_theme 	= "SELECT themename,path,themetype,in_setup,page_positions,advert_positions,shelfgroup_positions,categorygroup_positions,bigimage_geometry,
					   thumbimage_geometry,advertimage_geometry,shelf_positions,survey_positions,combo_positions,featuredproduct_positions,
					   categoryimage_geometry,categorythumbimage_geometry,headerimage_geometry,shelf_displaytypes,shelf_listingstyles,
					   product_listingstyles,image_listingstyles,shopbybrand_positions,paymenttype_displaytypes,iconimage_geometry,
					   theme_background_colour, theme_font_style,subcategory_listingstyles,allow_special_category_details,
					   product_image_display_format,allow_attachment_icon,themes_support_allowed_positions,advert_support_types,theme_var_onlyasdropdown,
					   theme_top_cat_dropdownmenu_support  
			   	FROM 
					themes 
				WHERE 
					theme_id=".$_REQUEST['theme_id'];
$res_theme 	= $db->query($sql_theme);
$row 		= $db->fetch_array($res_theme);
?>
<script language="javascript">
function valform(frm)
{
	fieldRequired = Array('themename','path');
	fieldDescription = Array('Theme Name','Theme Path');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		return true;
	} else {
		return false;
	}
}
</script>
<form name='frmEditTheme' action='home.php?request=themes' method="post" onsubmit="return valform(this);">

<table width="100%" border="0" cellpadding="0" cellspacing="1" class="maininnertable">
      <tr>
        <td align="left" class="menutabletoptd">&nbsp;&nbsp;<a href="home.php?request=themes&theme_name=<?=$_REQUEST['theme_name']?>&themetype=<?=$_REQUEST['themetype']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>" title="List Themes">List Themes</a> <font size="1">>></font> <strong>Edit <?=$page_type?></strong></td>
      </tr>
      
      <tr>
        <td class="maininnertabletd3">
			<?=$help_msg?>
		</td>
      </tr>
	  <tr>
        <td align="left" class="maininnertabletd2">
			<table   width="100%" border="0" cellpadding="4" cellspacing="1" class="">
			    <tr align="left">
				  <td colspan="4" class="redtext"><div align="left">* <span>are required </span></div></td>
				</tr> 
			    <tr align="left">
				<td width="44%" colspan="0" class="maininnertabletd2">
				<table width="100%"  border="0" cellpadding="4" cellspacing="1" class="">
				<tr>
				  <td width="39%" align="right" class="fontblacknormal">Theme name</td>
				  <td width="3%" align="center">:</td>
				  <td width="58%" align="left"><input name="themename" type="text" id="themename" value="<?=$row['themename']?>" size="30">
				  <span class="redtext">*</span></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Theme Path</td>
				  <td align="center">:</td>
				  <td align="left"><input name="path" type="text" id="path" value="<?=$row['path']?>" size="30"><span class="redtext">*</span><br />
			      (themes /themename.php) </td>
				</tr>
                  <tr>
				  <td align="right" class="fontblacknormal">Theme Type</td>
				  <td align="center">:</td>
				  <td align="left">
				    <select name="themetype" id="themetype">
                    	<option value="Normal" <? if($row['themetype']=='Normal') echo 'selected';?>>Normal</option>
                    	<option value="Mobile" <? if($row['themetype']=='Mobile') echo 'selected';?>>Mobile</option>
			        </select>
			      </td>
				  </tr>
				<tr>
				<td colspan="3" align="left" class="fontblacknormal"><strong>Allowable Positions</strong></td>
				</tr> 
				<tr>
				  <td align="right" class="fontblacknormal">Static Page Positions</td>
				  <td align="center">:</td>
				  <td align="left"><input name="page_positions" type="text" id="positions" value="<?=$row['page_positions']?>" size="30"></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Advert Positions</td>
				  <td align="center">:</td>
				  <td align="left"><input name="advert_positions" type="text" id="advert_positions" value="<?=$row['advert_positions']?>" size="30">				  </td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Category Group Positions</td>
				  <td align="center">:</td>
				  <td align="left"><input name="categorygroup_positions" type="text" id="categorygroup_positions" value="<?=$row['categorygroup_positions']?>" size="30">				  </td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Shelf Positions</td>
				  <td align="center">:</td>
				  <td align="left"><input name="shelf_positions" type="text" id="shelf_position" value="<?=$row['shelf_positions']?>" size="30">				  </td>
				</tr>
                <tr>
				  <td align="right" class="fontblacknormal">Shelf Menu Positions</td>
				  <td align="center">:</td>
				  <td align="left"><input name="shelfgroup_positions" type="text" id="shelfgroup_position" value="<?=$row['shelfgroup_positions']?>" size="30">				  </td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Combo Deal  Positions</td>
				  <td align="center">:</td>
				  <td align="left"><input name="combo_positions" type="text" id="combo_positions" value="<?=$row['combo_positions']?>" size="30" /></td>
			  </tr>
			  <tr>
				  <td align="right" class="fontblacknormal">Featured Product Positions</td>
				  <td align="center">:</td>
				  <td align="left"><input name="featuredproduct_positions" type="text" id="featuredproduct_positions" value="<?=$row['featuredproduct_positions']?>" size="30" /></td>
			  </tr>
			  <tr>
			    <td align="right" class="fontblacknormal">Shopby Brand Positions </td>
			    <td align="center">:</td>
			    <td align="left"><input name="shopbybrand_positions" type="text" id="shopbybrand_positions" value="<?=$row['shopbybrand_positions']?>" size="30" /></td>
			    </tr>
				<tr>
			    <td align="right" class="fontblacknormal">Survey Positions </td>
			    <td align="center">:</td>
			    <td align="left"><input name="survey_positions" type="text" id="survey_positions" value="<?=$row['survey_positions']?>" size="30" /></td>
			    </tr>
				<tr>
				  <td align="right" class="fontblacknormal">&nbsp; Back Ground Colour</td>
				  <td align="center">:</td>
				  <td align="left"><input name="backgrd_colour" type="text" id="backgrd_colour" value="<?=$row['theme_background_colour']?>" size="30" /></td>
				  </tr>
				<tr>
				  <td align="right" class="fontblacknormal">Font Family </td> 
				  <td align="center">:</td>
				  <td align="left"><input name="font_family" type="text" id="font_family" value="<?=$row['theme_font_style']?>" size="30" /></td>
				  </tr>
				</table>
				</td>
				<td width="56%" colspan="0" align="left" valign="top" class="" >
				  <table width=""  border="0" cellpadding="4" cellspacing="1" class="">
				 
				<tr>
				  <td align="right" class="fontblacknormal">Show in Setup</td>
				  <td align="center">:</td>
				  <td align="left"><input type="checkbox" name="in_setup" id="in_setup" value="1" <?php echo($row['in_setup']==1)?'checked':'';?>/></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Special Category Details Page Allowed</td>
				  <td align="center">:</td>
				  <td align="left"><input type="checkbox" name="allow_special_category_details" id="allow_special_category_details" value="1" <?php echo($row['allow_special_category_details'])?'checked':'';?>/></td>
				</tr>
				<tr>
				<td align="right" class="fontblacknormal">Product Attachment Icon Allowed </td>
				<td align="center">:</td>
				  <td align="left"><input type="checkbox" name="allow_attachment_icon" id="allow_attachment_icon" value="1" <?php echo($row['allow_attachment_icon'])?'checked':'';?>/></td>
				</tr>
                                <tr>
                                <td align="right" class="fontblacknormal">Allow website layout coloring?</td>
                                <td align="center">:</td>
                                  <td align="left"><input type="checkbox" name="themes_support_allowed_positions" id="themes_support_allowed_positions" value="1" <?php echo($row['themes_support_allowed_positions'])?'checked':'';?>/></td>
                                </tr>
								<tr>
                                    <td align="right" class="fontblacknormal">Variable value in Dropdown style only? </td>
                                    <td align="center">:</td>
                                    <td align="left"><input type="checkbox" name="theme_var_onlyasdropdown" id="theme_var_onlyasdropdown" value="1" <?php echo($row['theme_var_onlyasdropdown'])?'checked':'';?>/></td>
                                  </tr>
								<tr>
								  <td align="right" class="fontblacknormal">Support Top Category Dropdown Menu? </td>
								  <td align="center">:</td>
								  <td align="left"><input type="checkbox" name="theme_top_cat_dropdownmenu_support" id="theme_top_cat_dropdownmenu_support" value="1" <?php echo($row['theme_top_cat_dropdownmenu_support'])?'checked':'';?>/></td>
				    </tr>
				 <tr>
				<td colspan="3" align="left" class="fontblacknormal"><strong>Image Geometries</strong></td>
				</tr>
				  <tr>
				 	 <td align="right" class="fontblacknormal">Thumb Image geometry</td>
				  	 <td align="center">:</td>
				  	 <td align="left"><input name="thumbimage_geometry" type="text" id="thumbimage_geometry" value="<?=$row['thumbimage_geometry']?>" size="30" />                  </td>
			  	  </tr>
				  <tr>
				    <td align="right" class="fontblacknormal">Big Image geometry</td>
				  	<td align="center">:</td>
				    <td align="left"><input name="bigimage_geometry" type="text" id="bigimage_geometry" value="<?=$row['bigimage_geometry']?>" size="30">				  </td>
				 </tr>
				 <tr>
				   <td align="right" class="fontblacknormal">Advert Geometry</td>
				   <td align="center">:</td>
				   <td align="left"><input name="advertimage_geometry" type="text" id="advertimage_geometry" value="<?=$row['advertimage_geometry']?>" size="30">				  </td>
				</tr>
				<tr>
				 	 <td align="right" class="fontblacknormal">Category Image geometry</td>
				  	 <td align="center">:</td>
				  	 <td align="left"><input name="categoryimage_geometry" type="text" id="categoryimage_geometry" value="<?=$row['categoryimage_geometry']?>" size="30" />                  </td>
			  	  </tr>
				  <tr>
				    <td align="right" class="fontblacknormal">Category Thumb Image geometry</td>
				  	<td align="center">:</td>
				    <td align="left"><input name="categorythumbimage_geometry" type="text" id="categorythumbimage_geometry" value="<?=$row['categorythumbimage_geometry']?>" size="30">				  </td>
				 </tr>
				 <tr>
				   <td height="30" align="right" class="fontblacknormal">Header Image Geometry</td>
				   <td align="center">:</td>
				   <td align="left"><input name="headerimage_geometry" type="text" id="headerimage_geometry" value="<?=$row['headerimage_geometry']?>" size="30">				  </td>
				</tr>
				 <tr>
				   <td height="30" align="right" class="fontblacknormal">Icon Image Geometry </td>
				   <td align="center">:</td>
				   <td align="left"><input name="iconimage_geometry" type="text" id="iconimage_geometry" value="<?=$row['iconimage_geometry']?>" size="30"></td>
				   </tr>
				</table>
				 </td> 
				</tr>
				 <tr align="left">
				<td valign="top" class="maininnertabletd2" colspan="2" align="left"><table width="100%" border="0">
                  <tr>
                    <td colspan="3" align="left"><b>Shelf Listing Allowable Values </b></td>
                  </tr>
                  <tr>
                    <td width="22%">Shelf Display Types </td>
                    <td width="1%" align="center" valign="top">:</td>
                    <td width="77%"><input name="shelf_displaytypes" type="text" id="shelf_displaytypes" value="<?=$row['shelf_displaytypes']?>" size="85" />
(val1=&gt;caption1,val2=&gt;cap2) </td>
                  </tr>
                  <tr>
                    <td>Shelf Listing Styles </td>
                    <td align="center" valign="top">:</td>
                    <td><input name="shelf_listingstyles" type="text" id="shelf_listingstyles" value="<?=$row['shelf_listingstyles']?>" size="85" />
(val1=&gt;caption1,val2=&gt;cap2) </td>
                  </tr>
                  <tr>
                    <td><strong>Payment Type Display Types </strong></td>
                    <td align="center" valign="top">&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td>Allowable Values </td>
                    <td align="center" valign="top">:</td>
                    <td><input name="paymenttype_displaytypes" type="text" id="paymenttype_displaytypes" value="<?=$row['paymenttype_displaytypes']?>" size="85" />
(val1=&gt;caption1,val2=&gt;cap2)</td>
                  </tr>
                </table></td>
				</tr>
				
				<tr>
				  <td colspan="3" align="right"><table width="100%" border="0">
                    <tr>
                      <td colspan="3" align="left"><strong>Subcategory Listing </strong></td>
                    </tr>
                    <tr>
                      <td align="left">Allowable Values </td>
                      <td align="left">:</td>
                      <td align="left"><input name="subcategory_listingstyles" type="text" id="subcategory_listingstyles" value="<?=$row['subcategory_listingstyles']?>" size="85" />
(val1=&gt;caption1,val2=&gt;cap2)</td>
                    </tr>
                    <tr>
                      <td colspan="3" align="left"><b>Product Listing </b></td>
                    </tr>
                    <tr>
                      <td width="22%">Allowable Values</td>
                      <td width="1%" align="center" valign="top">:</td>
                      <td width="77%"><input name="product_listingstyles" type="text" id="product_listingstyles" value="<?=$row['product_listingstyles']?>" size="85" />
                        (val1=&gt;caption1,val2=&gt;cap2) </td>
                    </tr>
                  </table></td>
				</tr>
				<tr>
				  <td colspan="3" align="right"><table width="100%" border="0">
                    <tr>
                      <td colspan="3" align="left"><b>Image Listing </b></td>
                    </tr>
                    <tr>
                      <td width="22%">Image Listing Styles</td>
                      <td width="1%" align="center" valign="top">:</td>
                      <td width="77%"><input name="image_listingstyles" type="text" id="image_listingstyles" value="<?=$row['image_listingstyles']?>" size="85" />
                        (val1=&gt;caption1,val2=&gt;cap2) </td>
					</tr>
                    <tr>
                      <td>Product Image Format </td>
                      <td align="center" valign="top">:</td>
                      <td><input name="product_image_display_format" type="text" id="product_image_display_format" value="<?=$row['product_image_display_format']?>" size="85" />
(val1=&gt;caption1,val2=&gt;cap2) </td>
                    </tr>
                    <tr>
                      <td>Advert Types </td>
                      <td align="center" valign="top">:</td>
                      <td><input name="advert_support_types" type="text" id="advert_support_types" value="<?=$row['advert_support_types']?>" size="85" />
(val1=&gt;caption1,val2=&gt;cap2) </td>
                    </tr>
                  </table></td>
				</tr>
				<tr align="center">
				<td>&nbsp;</td>
				<td colspan="3" align="left">
					<input type="hidden" name="theme_id" id="theme_id" value="<?=$_REQUEST['theme_id']?>" />
					<input type="hidden" name="theme_name" id="theme_name" value="<?=$_REQUEST['theme_name']?>" />
					<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
					<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
					<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
					<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
					<input type="hidden" name="fpurpose" id="fpurpose" value="update" />
					<input type="Submit" name="Submit" id="Submit" value="Update" class="input-button">				</td>
				</tr>
				<tr>
				  <td colspan="3" align="right">&nbsp;</td>
				</tr>
		  </table>
		</td>
      </tr>
  </table>
</form>