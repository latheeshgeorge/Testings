<?php
	/*#################################################################
	# Script Name 	: edit_help.php
	# Description 		: Page for editing help
	# Coded by 		: Sny
	# Created on		: 01-Jan-2008
	#################################################################*/
#Define constants for this page
$page_type = 'Help';
$help_msg = get_help_messages('EDIT_HELP_MESS1');
$help_id=($_REQUEST['help_id']?$_REQUEST['help_id']:$_REQUEST['checkbox'][0]);
$sql_comp = "SELECT * FROM help WHERE help_id =".$help_id. " AND sites_site_id=$ecom_siteid";
	$res_comp = $db->query($sql_comp);
	$row_comp = $db->fetch_array($res_comp);
?>	
<script language="javascript" type="text/javascript">
function valform(frm)
{
	fieldRequired = Array('help_heading','help_description');
	fieldDescription = Array('Heading','Description');
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
	
	if(document.frmedithelp.shelf_activateperiodchange.checked==true)
	{
		document.getElementById('show_date_period').style.display = '';
	}
	else
	{
		document.getElementById('show_date_period').style.display = 'none';
	}
}
</script>
<form name='frmedithelp' action='home.php?request=help' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=help&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_help_heading=<?=$_REQUEST['search_help_heading']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List help</a><span> Edit Help</span></div></td>
        </tr>
        <tr>
		  <td colspan="2" align="left" valign="middle" class="helpmsgtd_main">
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
		<td valign="top" class="tdcolorgray" colspan="2" >
		<div class="sorttd_div" >
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="17%" align="left" valign="top" class="tdcolorgray" >Heading <span class="redtext">*</span> </td>
          <td width="83%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="help_heading"  value="<?=stripslashes($row_comp['help_heading'])?>"  size="80"/>
		  </td>
        </tr>
		 <tr>
          <td width="17%" align="left" valign="top" class="tdcolorgray" >Description <span class="redtext">*</span> </td>
          <td width="83%" align="left" valign="middle" class="tdcolorgray">
		  		   <textarea name="help_description" rows="15" cols="93" ><?=stripslashes($row_comp['help_description'])?></textarea>
	  </td>
        </tr>
		 <tr>
          <td width="17%" align="left" valign="top" class="tdcolorgray" >Sort Order <span class="redtext">*</span></td>
          <td width="83%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="help_sortorder" size="3" value="<?=$row_comp['help_sortorder']?>"  />
		  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_HELP_ORDER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		<tr>
          <td align="left" valign="top" class="tdcolorgray" >Hide</td>
          <td align="left" valign="middle" class="tdcolorgray"><input type="radio" name="help_hide" value="1" <? if($row_comp['help_hide']==1) echo "checked"?>  />Yes<input type="radio" name="help_hide" value="0"  <? if($row_comp['help_hide']==0) echo "checked"?> />No
		  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_HELP_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		</table>
		</div>
		</td>
	
		<tr>
         <td colspan="2" align="center" valign="middle" class="tdcolorgray" width="100%">
		   <div class="sorttd_div" >
		   <table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td align="right" valign="middle" class="tdcolorgray" >
				  <input type="hidden" name="help_id" id="help_id" value="<?=$help_id?>" />
				  <input type="hidden" name="search_help_heading" id="search_help_heading" value="<?=$_REQUEST['search_help_heading']?>" />
				   <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
				  <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
				  <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
				  <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
				  <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
				  <input type="hidden" name="fpurpose" id="fpurpose" value="update" />
			  	  <input name="Submit" type="submit" class="red" value="Update" />
				</td>
			</tr>
			</table>
			</div>
		</td>
        </tr>
      </table>
</form>	  

