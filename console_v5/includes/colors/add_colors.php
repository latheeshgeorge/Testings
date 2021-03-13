<?php
/*#################################################################
# Script Name 	: add_colors.php
# Description 	: Page for adding Colors for product variables
# Coded by 	: Sny
# Created on	: 11-Jan-2010
# Modified by	: 
# Modified On	: 
#################################################################*/
#Define constants for this page
$page_type = 'Product Variable Colors';
$help_msg = get_help_messages('ADD_PROD_VAR_COLOR_MESS1');

?>	
<script charset="UTF-8" src="js/jquery.js" type="text/javascript"></script>
<script type="text/javascript" src="js/mColorPicker.js"></script>
<script language="javascript" type="text/javascript">
function valform(frm)
{
	fieldRequired           = Array('color_name','color_hexcode');
	fieldDescription        = Array('Color Name','Color Hex Code');
	fieldEmail              = Array();
	fieldConfirm            = Array();
	fieldConfirmDesc        = Array();
	fieldNumeric            = Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric))
        {
			$(this).serialize();
            show_processing();
            return true;
	} 
        else
        {
            return false;
	}
}
</script>
<form name='frmaddcolors' action='home.php?request=colorcodes' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=colorcodes&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Product Variable Colors </a> <span> Add Product Variable Colors</span> </td>
        </tr>
        <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="3">
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
          <td colspan="3" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
        </tr>
		<?
		}
		?>
		<tr>
		<td colspan="3">
		<div class="editarea_div">
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="26%" align="left" valign="middle" class="tdcolorgray" >Color Name <span class="redtext">*</span>  </td>
          <td width="42%" align="left" valign="middle" class="tdcolorgray">
          <input name="color_name" type="text" class="input"  value="<?=$_REQUEST['color_name']?>" size="45" />		  </td>
          <td width="32%" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
        </tr>
		 <tr>
          <td width="26%" align="left" valign="middle" class="tdcolorgray" >Hexadecimal Code for color (#FF0000 for red))</td>
          <td width="42%" align="left" valign="middle" class="tdcolorgray"><input type="text" class="color" name="color_hexcode" value="<?=$_REQUEST['color_hexcode']?>" size='7'>
        <tr>
          <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
          <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
        </tr>
		
      </table>
	  </td>
	  </td>
	  </tr>
	  </table>
	  <div class="editarea_div">
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
          <td colspan="3" align="right" valign="middle" class="tdcolorgray">
            <input type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>" />
            <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
            <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
            <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
            <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
            <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
            <input type="hidden" name="fpurpose" id="fpurpose" value="insert" />
            <input name="Submit" type="submit" class="red" value="Save" />        </td>
        </tr>
	  </table>
	  </div>
</form>	  

