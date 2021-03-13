<?php
#################################################################
# Script Name 	: add_theme_layouts.php
# Description 	: Page for adding layouts
# Coded by 		: Sny
# Created on	: 31-May-2007
# Modified by	: SKR 
# Modified On	: 04-Jun-2007
#################################################################

#Define constants for this page
$page_type = 'Theme Layout';
$help_msg = 'This section helps in adding the Layouts for a Theme.';

?>
<script language="javascript">
function valform(frm)
{
	fieldRequired = Array('layout_name','layout_code');
	fieldDescription = Array('Layout Name','Layout Code');
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
        <td align="left" class="menutabletoptd">&nbsp;&nbsp;<a href="home.php?request=themes&theme_name=<?=$_REQUEST['pass_theme_name']?>&themetype=<?=$_REQUEST['pass_themetype']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>" title="List Themes">List Themes</a><strong> <font size="1">>></font> Add <?=$page_type?></strong></td>
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
				  <td align="right" class="fontblacknormal">Layout name</td>
				  <td align="center">:</td>
				  <td align="left"><input name="layout_name" type="text" id="layout_name" value="<?=$_REQUEST['layout_name']?>" size="30">
					  <span class="redtext">*</span></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Layout Code</td>
				  <td align="center">:</td>
				  <td align="left"><input name="layout_code" type="text" id="layout_code" value="<?=$_REQUEST['layout_code']?>" size="30">
				    <span class="redtext">*</span> </td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Layout Positions</td>
				  <td align="center">:</td>
				  <td align="left">
				  <input name="layout_positions" type="text" id="layout_positions" value="<?=$_REQUEST['layout_positions']?>" size="30">
				 <!-- <select name="layout_positions[]" multiple="multiple"   >
				  <option value="left">left</option>
				  <option value="right">right</option>
				  <option value="inline">inline</option>
				  <option value="top">top</option>
				  </select>-->
				  </td>
				</tr>
                                <tr>
                                  <td align="right" class="fontblacknormal">Support Product Details</td>
                                  <td align="center">:</td>
                                  <td align="left"><input name="layout_support_cart" type="checkbox" id="layout_support_cart" value="1" <? echo ($_REQUEST['layout_support_cart'])?'checked':''?> size="30">
                                    <span class="redtext">*</span> </td>
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
					<input type="hidden" name="pass_theme_id" id="pass_theme_id" value="<?=$_REQUEST['pass_theme_id']?>" />
					<input type="hidden" name="pass_theme_name" id="pass_theme_name" value="<?=$_REQUEST['pass_theme_name']?>" />
                    <input type="hidden" name="pass_themetype" id="pass_themetype" value="<?=$_REQUEST['pass_themetype']?>" />

					<input type="hidden" name="pass_sort_by" id="pass_sort_by" value="<?=$_REQUEST['pass_sort_by']?>" />
					<input type="hidden" name="pass_sort_order" id="pass_sort_order" value="<?=$_REQUEST['pass_sort_order']?>" />
					<input type="hidden" name="pass_records_per_page" id="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
					<input type="hidden" name="pass_pg" id="pass_pg" value="<?=$_REQUEST['pass_pg']?>" />
					<input type="hidden" name="pass_theme_name" id="pass_theme_name" value="<?=$_REQUEST['pass_theme_name']?>" />
					
					<input type="hidden" name="theme_name" id="theme_name" value="<?=$_REQUEST['theme_name']?>" />
					<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
					<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
					<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
					<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
					<input type="hidden" name="fpurpose" id="fpurpose" value="insert_layouts" />
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