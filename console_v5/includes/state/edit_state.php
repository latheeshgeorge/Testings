<?php
	/*#################################################################
	# Script Name 	: edit_state.php
	# Description 	: Page for editing Site State
	# Coded by 		: SKR
	# Created on	: 15-June-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
#Define constants for this page
$page_type = 'State';
$help_msg = get_help_messages('EDIT_STATE_MESS1');
$state_id=($_REQUEST['state_id']?$_REQUEST['state_id']:$_REQUEST['checkbox'][0]);
$sql_state="SELECT general_settings_site_country_country_id,state_name,state_hide FROM general_settings_site_state  WHERE state_id=".$state_id;
$res_state= $db->query($sql_state);
$row_state = $db->fetch_array($res_state);
?>	
<script language="javascript" type="text/javascript">
function valform(frm)
{
	fieldRequired = Array('general_settings_site_country_country_id','state_name');
	fieldDescription = Array('Country Name','State Name');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	fieldSpecChars = Array('state_name');
	fieldCharDesc = Array('State Name');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric,fieldSpecChars,fieldCharDesc)) {
		show_processing();
		return true;
	} else {
		return false;
	}
}
</script>
<form name='frmAddCountry' action='home.php?request=general_settings_state' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=general_settings">General Settings</a>  <a href="home.php?request=general_settings_state&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List State</a><span> Edit State</span></div></td>
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
          <td colspan="2" align="left" valign="top">
		  <div class="editarea_div">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
         <tr>
          <td width="15%" align="left" valign="middle" class="tdcolorgray" >Country Name <span class="redtext">*</span> </td>
          <td width="85%" align="left" valign="middle" class="tdcolorgray">
		 <?php  $countrylist=list_countries();
		echo generateselectbox('general_settings_site_country_country_id',$countrylist,$row_state['general_settings_site_country_country_id']); ?>
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_STATE_COUNTRY')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		  <tr>
          <td width="15%" align="left" valign="middle" class="tdcolorgray" >State Name <span class="redtext">*</span> </td>
          <td width="85%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="state_name" value="<?=$row_state['state_name']?>"  />
		  </td>
        </tr>
       
        <tr>
          <td align="left" valign="middle" class="tdcolorgray" >Active</td>
          <td align="left" valign="middle" class="tdcolorgray"><input type="radio" name="state_hide" value="1" checked="checked" />Yes<input type="radio" name="state_hide" value="0" <? if($row_state['state_hide']==0) echo "checked";?>  />No&nbsp;
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_STATE_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		</table>
		</div>
        <tr>
			<td colspan="2" align="right" valign="middle">
				<div class="editarea_div">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td align="right" valign="middle" class="tdcolorgray">
							<input type="hidden" name="state_id" id="country_id" value="<?=$state_id?>" />
							<input type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>" />
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

