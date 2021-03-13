<?php
	/*#################################################################
	# Script Name 	: add_settings_captions.php
	# Description 	: Page for adding General settings Captions
	# Coded by 		: ANU
	# Created on	: 14-June-2007
	# Modified by	: Sny
	# Modified On	: 30-Jul-2007
	#################################################################*/
#Define constants for this page
include("classes/fckeditor.php");
$table_name='general_settings_site_letter_templates';
$page_type = 'General Settings Letter Templates';
$help_msg =get_help_messages('EDIT_LETTER_TEMPLATE_MESS1');
if(is_array($_REQUEST['lettertemplate_id'])){
	list($lettertemplate_id)=$_REQUEST['lettertemplate_id'];
}else{
$lettertemplate_id = $_REQUEST['lettertemplate_id'];
}
 
//sql for selecting the details for the settings option
$sql_settings_letter_templates = "SELECT lettertemplate_id,lettertemplate_subject,lettertemplate_from,
										 lettertemplate_letter_type,lettertemplate_contents 
										 		FROM general_settings_site_letter_templates 
													WHERE sites_site_id=$ecom_siteid AND lettertemplate_id = $lettertemplate_id";
$res_settings_letter_templates  = $db->query($sql_settings_letter_templates );
if($db->num_rows($res_settings_letter_templates)==0) { echo " <font color='red'> You Are Not Authorised  </a>"; exit; }
$settings_letter_templates = $db->fetch_array($res_settings_letter_templates);


$content = $settings_letter_templates['lettertemplate_contents'];

?>	
<script language="javascript" type="text/javascript">
function valform(frm)
{
	fieldRequired = Array('lettertemplate_from');
	fieldDescription = Array('Letter Template from ID');
	fieldEmail = Array('lettertemplate_from');
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
<form name='frmEditLetterTemplates' action='home.php?request=settings_letter_templates' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" >
        <tr>
          <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=settings_letter_templates&lettertemplate_letter_type=<?=$_REQUEST['lettertemplate_letter_type']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&lettertemplate_title=<?=$_REQUEST['lettertemplate_title']?>">List Email Templates</a><span> Edit Email Templates</span></div></td>
        </tr>
        <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="3">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
        <?php if($alert) {?>
        <tr>
          <td colspan="3" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
    </tr><? }?>
        <tr>
          <td colspan="3"align="left" valign="middle" >
		  <?php $sql_template_code = "SELECT template_code FROM common_emailtemplates WHERE template_lettertype = '".$settings_letter_templates['lettertemplate_letter_type']."'";
		  $res_template_code=$db->query($sql_template_code);
		  $template_code_string = $db->fetch_array($res_template_code);
		  $template_code = explode(',',$template_code_string['template_code']);
		  ?></td>
        </tr>
		<tr>
          <td colspan="3" align="center" valign="middle">
		  	<div class="editarea_div" >
			<table width="100%" cellpadding="0" cellspacing="0">
        <tr>
          <td align="left" valign="middle" class="tdcolorgraybg" >Template From email <span class="redtext">*</span> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_LETTER_TEMPLATE_FROM_EMAIL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> </td>
          <td  align="left" valign="middle" class="tdcolorgraybg"><input  type="text" name="lettertemplate_from" id="lettertemplate_from" value="<?=$settings_letter_templates['lettertemplate_from']?>" size="40"/></td>
          <td align="left" valign="top" class="tdcolorgraybg">&nbsp;</td>
        </tr>
        <tr>
          <td align="left" valign="middle" class="tdcolorgraybg" >Template Subject <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_LETTER_TEMPLATE_SUBJECT_EMAIL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          <td  align="left" valign="middle" class="tdcolorgraybg"><input  type="text" name="lettertemplate_subject" id="lettertemplate_subject" value="<?=$settings_letter_templates['lettertemplate_subject']?>" size="40"/></td>
          <td width="50%" align="left" valign="top" class="tdcolorgraybg">&nbsp;</td>
        </tr>
         <tr>
           <td colspan="2" align="left" valign="top" class="tdcolorgraybg"   >Template Letter Content <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_LETTER_TEMPLATE_CONTENT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
           <td  align="left" valign="top" class="tdcolorgraybg">&nbsp;</td>
         </tr>
         <tr>
           <td colspan="2" align="center" valign="top" class="tdcolorgraybg">
         <?php 
		 $editor_elements = "letter_content";
		 /*$editor = new FCKeditor('letter_content') ;
		$editor->BasePath = '/console/js/FCKeditor/';
		$editor->Width = '550';
		$editor->Height = '550';
		$editor->ToolbarSet = 'BshopWithImages';
		$editor->Value = stripslashes($content);
		$editor->Create() ;*/
		include_once("js/tinymce.php");
		?>
		<textarea style="height:550px; width:550px" id="letter_content" name="letter_content"><?=stripslashes($content)?></textarea>
		</td>
           <td  align="left" valign="top" class="tdcolorgraybg"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="innersmalltable">
             <tr>
               <td width="23%" align="left" class="maininnertabletd1"><a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_LETTER_TEMPLATE_CODE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a><strong class="fontredheading">Code</strong></td>
               <td width="13%" class="maininnertabletd1">&nbsp;</td>
               <td width="64%" align="left" class="maininnertabletd1"><strong class="fontredheading">Description</strong> </td>
             </tr>
			 <tr>
               <td align="left">&nbsp;</td>
               <td align="center">&nbsp;</td>
               <td align="left">&nbsp;</td>
             </tr>
             <?php foreach($template_code as $key => $data) {
			list($code,$description) = split("=>",$data);
			?>
             <tr>
               <td align="left"><?=$code?></td>
               <td align="center">=&gt;</td>
               <td align="left"><?=$description?></td>
             </tr>
             <? }?>
             <tr>
               <td colspan="2">&nbsp;</td>
               <td>&nbsp;</td>
             </tr>
           </table></td>
         </tr>
		 </table>
		 </div>
		 </td>
		 </tr>
					
		<tr>
			<td colspan="3" align="right" valign="middle">
				<div class="editarea_div" >
					<table width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td align="right" valign="middle" class="tdcolorgraybg" >		  
						  <input type="hidden" name="lettertemplate_letter_type" id="lettertemplate_letter_type" value="<?=$_REQUEST['lettertemplate_letter_type']?>" />
							<input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
							<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
							<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
							<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
							<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
							<input type="hidden" name="lettertemplate_id" id="lettertemplate_id" value="<?=$lettertemplate_id?>" />
							<input type="hidden" name="lettertemplate_title" id="lettertemplate_title" value="<?=$_REQUEST['lettertemplate_title']?>" />
							<input type="hidden" name="fpurpose" id="fpurpose" value="update_letter_template" />
							<input name="Submit" type="submit" class="red" value="Update" />
						</td>
					</tr>
					</table>
				</div>
			</td>
		</tr>
  </table>

</form>	  

