<?php
	/*#################################################################
	# Script Name 	: add_comptype.php
	# Description 	: Page for adding Shelf
	# Coded by 		: LTH
	# Created on	: 04-Nov-2007
	#################################################################*/
#Define constants for this page
$page_type = 'Company Type';
$help_msg = get_help_messages('EDIT_COMPANY_TYPES_MESS1');
$comptype_id=($_REQUEST['comptype_id']?$_REQUEST['comptype_id']:$_REQUEST['checkbox'][0]);
$sql_comp = "SELECT * FROM general_settings_sites_customer_company_types WHERE comptype_id =".$comptype_id. " AND sites_site_id=$ecom_siteid";
		$res_comp = $db->query($sql_comp);
		$row_comp = $db->fetch_array($res_comp);
?>	
<script language="javascript" type="text/javascript">
function valform(frm)
{
	fieldRequired = Array('comptype_name','comptype_order');
	fieldDescription = Array('Companytype Name','Company type Order');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		show_processing();
		return true;
	} else {
		return false;
	}
}
function change_show_date_period()
{
	
	if(document.frmAddcomptype.shelf_activateperiodchange.checked==true)
	{
		document.getElementById('show_date_period').style.display = '';
	}
	else
	{
		document.getElementById('show_date_period').style.display = 'none';
	}
}
</script>
<form name='frmAddcomptype' action='home.php?request=general_settings_comptype' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" align="left" valign="middle" class="treemenutd">
			  <div class="treemenutd_div">
			  <a href="home.php?request=general_settings_comptype&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Company Types</a> <span> Edit CompanyTypes</span></div></td>
        </tr>
        <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="2">
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
          <td colspan="2" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
        </tr>
		<?
		}
		?>
		<tr>
		<td width="66%" valign="top" class="tdcolorgray" >
			<div class="editarea_div">

		<table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Type Name <span class="redtext">*</span> </td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="comptype_name"  value="<?=$row_comp['comptype_name']?>" />
		  </td>
        </tr>
		
		 <tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Company Type Order <span class="redtext">*</span></td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="comptype_order" size="3" value="<?=$row_comp['comptype_order']?>"  />
		  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_COMPANY_TYPES_ORDER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		<tr>
          <td align="left" valign="middle" class="tdcolorgray" >Hide</td>
          <td align="left" valign="middle" class="tdcolorgray"><input type="radio" name="comptype_hide" value="1" <? if($row_comp['comptype_hide']==1) echo "checked"?>  />Yes<input type="radio" name="comptype_hide" value="0"  <? if($row_comp['comptype_hide']==0) echo "checked"?> />No
		  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_COMPANY_TYPES_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		</table>
		</div>
		</td>
		<tr>
			<td>
						<div class="editarea_div">

		<table border="0" cellspacing="2" cellpadding="2" width="100%">
		 
		<tr>
         <td colspan="2" align="right" valign="middle" class="tdcolorgray" width="100%">
		  		  <input type="hidden" name="comptype_id" id="comptype_id" value="<?=$comptype_id?>" />

		  <input type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>" />
		   <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
		  <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
		  <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
		  <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
		  <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
		  <input type="hidden" name="fpurpose" id="fpurpose" value="update" />
		  <input name="Submit" type="submit" class="red" value="Update" /></td>
        </tr>
      </table>
      </div>
      </td>
      </tr>
      </table>
</form>	  

