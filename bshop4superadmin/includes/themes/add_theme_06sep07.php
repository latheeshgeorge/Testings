<?php
/*#################################################################
# Script Name 	: add_theme.php
# Description 	: Page for addding themes
# Coded by 		: Sny
# Created on	: 31-May-2007
# Modified by	: Sny
# Modified On	: 25-Jul-2007
#################################################################
*/
//#Define constants for this page
$page_type = 'Theme';
$help_msg = 'This section helps in adding the values for a Theme.';

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
        <td align="left" class="menutabletoptd">&nbsp;&nbsp;<a href="home.php?request=themes&theme_name=<?=$_REQUEST['theme_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>" title="List Themes">List Themes</a><strong> <font size="1">>></font> Add <?=$page_type?></strong></td>
      </tr>
  
      <tr>
        <td class="maininnertabletd3">
			<?=$help_msg?>
		</td>
      </tr>
	  <tr>
        <td class="maininnertabletd2">
			<table width="100%"  border="0" cellpadding="4" cellspacing="1" class="">
				<tr align="left">
				  <td colspan="3" class="redtext"><div align="left">* <span>are required </span></div></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Theme name</td>
				  <td align="center">:</td>
				  <td align="left"><input name="themename" type="text" id="themename" value="<?=$_REQUEST['themename']?>" size="30">
					  <span class="redtext">*</span></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Theme Path</td>
				  <td align="center">:</td>
				  <td align="left"><input name="path" type="text" id="path" value="<?=$_REQUEST['path']?>" size="30">
				    <span class="redtext">*</span> (themes /themename.php) </td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Show in Setup</td>
				  <td align="center">:</td>
				  <td align="left"><input type="checkbox" name="in_setup" id="in_setup" value="1" <?php echo($_REQUEST['in_setup'])?'checked':'';?>/></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Static Page Positions</td>
				  <td align="center">:</td>
				  <td align="left"><input name="page_positions" type="text" id="page_positions" value="<?=$_REQUEST['page_positions']?>" size="30"></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Advert Positions</td>
				  <td align="center">:</td>
				  <td align="left"><input name="advert_positions" type="text" id="advert_positions" value="<?=$_REQUEST['advert_positions']?>" size="30">				  </td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Category Group Positions</td>
				  <td align="center">:</td>
				  <td align="left"><input name="categorygroup_positions" type="text" id="categorygroup_positions" value="<?=$_REQUEST['categorygroup_positions']?>" size="30">				  </td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Shelf Positions</td>
				  <td align="center">:</td>
				  <td align="left"><input name="shelf_positions" type="text" id="shelf_positions" value="<?=$_REQUEST['shelf_positions']?>" size="30">				  </td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Combo Deal  Positions</td>
				  <td align="center">:</td>
				  <td align="left"><input name="combo_positions" type="text" id="combo_positions" value="<?=$_REQUEST['combo_positions']?>" size="30" /></td>
			  </tr>
				<tr>
				  <td align="right" class="fontblacknormal">Thumb Image geometry</td>
				  <td align="center">:</td>
				  <td align="left"><input name="thumbimage_geometry" type="text" id="thumbimage_geometry" value="<?=$_REQUEST['thumbimage_geometry']?>" size="30" />                  </td>
			    </tr>
				<tr>
				  <td align="right" class="fontblacknormal">Advert Geometry</td>
				  <td align="center">:</td>
				  <td align="left"><input name="advertimage_geometry" type="text" id="advertimage_geometry" value="<?=$_REQUEST['advertimage_geometry']?>" size="30">				  </td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">&nbsp;</td>
				  <td align="center">&nbsp;</td>
				  <td align="left">&nbsp;</td>
			  </tr>
				<tr>
				  <td align="right" class="fontblacknormal">&nbsp;</td>
				  <td align="center">&nbsp;</td>
				  <td align="left">&nbsp;</td>
			  </tr>
				
				<tr>
				  <td colspan="3" align="right">&nbsp;</td>
				</tr>
				<tr align="center">
				<td>&nbsp;</td>
				<td colspan="3" align="left">
					<input type="hidden" name="theme_name" id="theme_name" value="<?=$_REQUEST['theme_name']?>" />
					<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
					<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
					<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
					<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
					<input type="hidden" name="fpurpose" id="fpurpose" value="insert" />
					<input type="Submit" name="Submit" id="Submit" value="Add" class="input-button">				</td>
				</tr>
				<tr>
				  <td colspan="3" align="right">&nbsp;</td>
				</tr>
			</table>
		</td>
      </tr>
    </table>
</form>