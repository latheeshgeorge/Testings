<?php
	/*#################################################################
	# Script Name 	: add_settings_captions.php
	# Description 	: Page for adding General settings Captions
	# Coded by 		: ANU
	# Created on	: 14-June-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
#Define constants for this page
$page_type = 'general_settings_site_captions';
$help_msg = get_help_messages('EDIT_CAPTION_MESS1');
if(is_array($_REQUEST['general_id'])){
	list($general_id)=$_REQUEST['general_id'];
}else{
$general_id = $_REQUEST['general_id'];
}
 
 //sql for selecting the details for the settings option
$sql_settings_caption = "SELECT general_settings_section_section_id,sites_site_id,general_key,general_text  
							 FROM general_settings_site_captions 
								WHERE sites_site_id=$ecom_siteid AND general_id = $general_id";
$res_settings_caption  = $db->query($sql_settings_caption );
if($db->num_rows($res_settings_caption)==0) { echo " <font color='red'> You Are Not Authorised  </a>"; exit; }
$settings_captions = $db->fetch_array($res_settings_caption);

// To get all the general settings sections in the site
$sql_settings_sections = "SELECT section_id,section_name FROM general_settings_section ORDER BY section_name";
$res_settings_sections = $db->query($sql_settings_sections);
$section_name = array();
$section_name[0] = 'Select a Section';
while ($captions = $db->fetch_array($res_settings_sections)){
 $section_name[$captions['section_id']] = $captions['section_name'];
}
$selected_section = (!$_REQUEST['settings_section'])?0:$_REQUEST['settings_section'];


?>	
<script language="javascript" type="text/javascript">
function valform(frm)
{
	fieldRequired = Array('settings_section_id','general_key');
	fieldDescription = Array('Section','Caption Key');
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
</script>
<form name='frmAddSettingsCaptions' action='home.php?request=general_settings' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=general_settings&fpurpose=captions&site_captions=<?=$_REQUEST['site_captions']?>&settings_section=<?=$selected_section?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">List General Settings Captions </a><span> Edit General Settings Captions</span></div></td>
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
          </tr><?php }?>
        <tr>
          <td colspan="2" align="left" valign="top" >
		  <div class="editarea_div" >
			<table cellpadding="0" cellpadding="0" width="100%" >
        <tr>
          <td width="19%" align="left" valign="middle" class="tdcolorgray" >Section <span class="redtext">*</span> </td>
          <td width="81%" align="left" valign="middle" class="tdcolorgray">
		 <?=generateselectbox('settings_section_id',$section_name,$settings_captions['general_settings_section_section_id']);?>		  </td>
        </tr>
        <tr>
          <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
        </tr>
        <tr>
          <td align="left" valign="middle" class="tdcolorgray" >General settings Caption Key <span class="redtext">*</span> </td>
        <td align="left" valign="middle" class="tdcolorgray"><input name="general_key"  type="text" id="general_key" value="<?=$settings_captions['general_key']?>" size="80" /> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('GENERAL_SETTINGS_CAPTIONS_KEY')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
         <tr>
          <td align="left" valign="middle" class="tdcolorgray" >General Settings Caption  </td>
          <td align="left" valign="middle" class="tdcolorgray"><input name="general_text"  type="text" id="general_text" value="<?=$settings_captions['general_text']?>" size="80"/> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('GENERAL_SETTINGS_CAPTIONS_ADD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		</table>
		</div>
		</td>
		</tr>
					
		<tr>
			<td align="right" valign="middle" >
				<div class="editarea_div">
					<table cellpadding="0" cellpadding="0" width="100%">
					<tr>
						<td align="right" valign="middle" class="tdcolorgray" >		  
							<input type="hidden" name="site_captions" id="site_captions" value="<?=$_REQUEST['site_captions']?>" />
							<input type="hidden" name="settings_section" id="settings_section" value="<?=$_REQUEST['settings_section']?>" />
							<input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
							<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
							<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
							<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
							<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
							<input type="hidden" name="general_id" id="general_id" value="<?=$general_id?>" />
							<input type="hidden" name="fpurpose" id="fpurpose" value="update_settings_captions" />
							<input name="Submit" type="submit" class="red" value="Update" />
						</td>
					</tr>
					</table>
				</div>
			</td>
        </tr>
      </table>
</form>	  
