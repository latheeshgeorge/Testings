<?php
	/*#################################################################
	# Script Name 	: edit_tax.php
	# Description 	: Page for editing Site Tax
	# Coded by 		: SKR
	# Created on	: 25-June-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
#Define constants for this page
$page_type = 'Tax Settings';
$help_msg		= get_help_messages('EDIT_TAX_MESS1');
$tax_id=($_REQUEST['tax_id']?$_REQUEST['tax_id']:$_REQUEST['checkbox'][0]);
$sql_tax="SELECT tax_name,tax_description,tax_val,tax_active 
				FROM general_settings_site_tax 
					WHERE sites_site_id=$ecom_siteid AND tax_id=".$tax_id;
$res_tax= $db->query($sql_tax);
if($db->num_rows($res_tax)==0) { echo " <font color='red'> You Are Not Authorised  </a>"; exit; }
$row_tax = $db->fetch_array($res_tax);
?>	
<script language="javascript" type="text/javascript">
function valform(frm)
{
	fieldRequired = Array('tax_name','tax_val');
	fieldDescription = Array('Tax Name','Value');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array('tax_val');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
	 		 if (document.frmEditTax.tax_val.value>=100 || document.frmEditTax.tax_val.value<0)
				{
					alert('Discount % should be less than 100 and greater than Zero');
					return false;
				}
		show_processing();
		return true;
	} else {
		return false;
	}
}
</script>
<form name='frmEditTax' action='home.php?request=general_settings_tax' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" align="left" valign="middle" class="treemenutd">
			  <div class="treemenutd_div">
			  <a href="home.php?request=general_settings_tax&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>">List Tax</a> <span> Edit Tax</span></div></td>
        </tr>
       <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="2">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
		<?php if($alert) {?>
        <tr>
          <td colspan="2" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
          </tr>
		  <?php }?>
		     <tr>
         <td height="48" class="sorttd" colspan="2" >
		  <div class="editarea_div">

      	<table width="100%" border="0" cellpadding="1" cellspacing="1" >
         <tr>
          <td width="15%" align="left" valign="middle" class="tdcolorgray" >Tax Name <span class="redtext">*</span> </td>
          <td width="85%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="tax_name" value="<?=$row_tax['tax_name']?>"  /> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_TAX_NAME')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> 
		  </td>
        </tr>
		 <tr>
          <td width="15%" align="left" valign="middle" class="tdcolorgray" >Description </td>
          <td width="85%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="tax_description" value="<?=$row_tax['tax_description']?>" />
		  </td>
        </tr>
		 <tr>
          <td width="15%" align="left" valign="middle" class="tdcolorgray" >Tax %<span class="redtext">*</span> </td>
          <td width="85%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="tax_val" value="<?=$row_tax['tax_val']?>"  />
		  </td>
        </tr>
       <tr>
          <td align="left" valign="middle" class="tdcolorgray" >Active</td>
          <td align="left" valign="middle" class="tdcolorgray"><input type="radio" name="tax_active" value="1" checked="checked" />Yes<input type="radio" name="tax_active" value="0" <? if($row_tax['tax_active']==0) echo "checked";?> />No
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_TAX_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
        </table>
        </div>
        </td>
        </tr>
        <tr>
         <td colspan="2" >
		  <div class="editarea_div">

      	<table width="100%" border="0" cellpadding="1" cellspacing="1" >
        <tr>
          <td align="right" valign="middle" class="tdcolorgray">
		  
		  <input type="hidden" name="tax_id" id="tax_id" value="<?=$tax_id?>" />
		  <input type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>" />
		  <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
		  <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
		  <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
		  <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
		  <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
		  <input type="hidden" name="fpurpose" id="fpurpose" value="update" />
		  <input name="Submit" type="submit" class="red" value="Update" />&nbsp;</td>
        </tr>
        </table>
        </div>
        </td>
        </tr>
      </table>
</form>	  

