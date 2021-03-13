<?php
	/*#################################################################
	# Script Name 	: add_prdt_var_group.php
	# Description 	: Page for adding Product Variable Group
	# Coded by 		: Sobin Babu
	# Created on	: 26-July-2013
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
#Define constants for this page
$page_type = 'Product Variables Group';
$help_msg =get_help_messages('ADD_PRDT_VAR_GROUP_MESS1');

?>	
<script language="javascript" type="text/javascript">
function valform(frm)
{
	fieldRequired = Array('var_group_name');
	fieldDescription = Array('Product Variables Group Name');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) 
	{
		show_processing();
		return true;
	}
	else 
	{
		return false;
	}
}
</script>
<form name='frmAddVariableGroup' action='home.php?request=product_variable_group' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=product_variable_group&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Product Variables Groups</a> <span>Add Group</span></div></td>
        </tr>
        <tr>
		  <td colspan="2" align="left" valign="middle" class="helpmsgtd_main">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>		 </td>
		</tr>
		<?php 
		if($alert)
		{			
		?>
        <tr>
          <td colspan="2" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
        </tr>
		<?
		}
		?>
        <tr>
          <td colspan="2" align="center" valign="middle">
          <div class="listingarea_div">
          <table cellpadding="0" cellspacing="0" width="100%">
        <tr>
          <td width="26%" align="left" valign="middle" class="tdcolorgray" >Product Variables Group Name <span class="redtext">*</span> </td>
          <td width="74%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="var_group_name"  value="<?=$_REQUEST['var_group_name']?>"  maxlength="100"/>		  </td>
        </tr>

		<tr>
          <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td align="left" valign="middle" class="tdcolorgray">
		  
		  <input type="hidden" name="pass_group_name" id="pass_group_name" value="<?=$_REQUEST['pass_group_name']?>" />
		   <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
		  <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
		  <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
		  <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
		  <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
		  <input type="hidden" name="fpurpose" id="fpurpose" value="insert" />
		  <input name="Submit" type="submit" class="red" value="Submit" /></td>
        </tr>
        </table>
        </div>
        </td>
        </tr>
      </table>
</form>	  

