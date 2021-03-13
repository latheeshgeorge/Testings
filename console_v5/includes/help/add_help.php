<?php
/*#################################################################
# Script Name 	: add_help.php
# Description 		: Page for adding help
# Coded by 		: Sny
# Created on		: 01-Jan-2009
#################################################################*/
#Define constants for this page
$page_type = 'HELP';
$help_msg = get_help_messages('ADD_HELP_MESS1');
?>	
<script language="javascript" type="text/javascript">
function valform(frm)
{
	fieldRequired = Array('help_heading','help_description');
	fieldDescription = Array('heading','Description');
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
	
	if(document.frmhelp.shelf_activateperiodchange.checked==true)
	{
		document.getElementById('show_date_period').style.display = '';
	}
	else
	{
		document.getElementById('show_date_period').style.display = 'none';
	}
}
</script>
<form name='frmhelp' action='home.php?request=help' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=help&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_help_heading=<?=$_REQUEST['search_help_heading']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Help</a><span> Add Help</span></div></td>
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
		<td colspan="2" valign="top" class="tdcolorgray" >
		 <div class="sorttd_div" >
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="15%" align="left" valign="top" class="tdcolorgray" >Heading <span class="redtext">*</span> </td>
          <td width="85%" align="left" valign="middle" class="tdcolorgray">
		  <input name="help_heading" type="text" class="input" id="help_heading" value="<?=$_REQUEST['help_heading']?>" size="80"  />		  </td>
        </tr>
		
		 <tr>
		   <td align="left" valign="top" class="tdcolorgray" >Description * </td>
		   <td align="left" valign="middle" class="tdcolorgray">
		   <textarea name="help_description" rows="15" cols="93" ><?=stripslashes($_REQUEST['help_description'])?></textarea>
		   </td>
	      </tr>
		 <tr>
          <td width="15%" align="left" valign="top" class="tdcolorgray" >Sort Order  <span class="redtext">*</span></td>
          <td width="85%" align="left" valign="middle" class="tdcolorgray">
		  <input name="help_sortorder" type="text" class="input" id="help_sortorder" value="<?=$_REQUEST['help_sortorder']?>" size="3"  /> 
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_HELP_ORDER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		<tr>
          <td align="left" valign="top" class="tdcolorgray" >Hide</td>
          <td align="left" valign="middle" class="tdcolorgray"><input type="radio" name="help_hide" value="1" <? if($_REQUEST['help_hide']==1){ echo "checked";} ?>  />Yes<input type="radio" name="help_hide" value="0" <? if($_REQUEST['help_hide']==0){ echo "checked";} ?>  />No
		  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_HELP_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		</table></div>
		</td>
		
		<tr>
         <td colspan="2" align="center" valign="middle" class="tdcolorgray" width="100%">
		   <div class="sorttd_div" >
		   <table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td align="right" valign="middle" class="tdcolorgray" >
				  <input type="hidden" name="search_help_heading" id="search_help_heading" value="<?=$_REQUEST['search_help_heading']?>" />
				   <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
				  <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
				  <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
				  <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
				  <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
				  <input type="hidden" name="fpurpose" id="fpurpose" value="insert" />
				  <input name="Submit" type="submit" class="red" value="Save" />
				</td>
			</tr>
			</table>
		  </div></td>
        </tr>
      </table>
</form>	  

