<?php
/*
#################################################################
# Script Name 	: add_template.php
# Description 	: Page for adding Template
# Coded by 		: SKR
# Created on	: 06-June-2007
# Modified by	: 
# Modified On	: 
#################################################################
*/
#Define constants for this page
include("classes/fckeditor.php");
$page_type = 'Email Template';
$help_msg = 'This section helps in adding the values for a template.';

?>
<script language="javascript">
function valform(frm)
{
	fieldRequired = Array('template_lettertype','template_lettertitle','template_lettersubject');
	fieldDescription = Array('Letter Type','Letter Title','Letter Subject');
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
<style type="text/css">
<!--
.style1 {font-weight: bold}
-->
</style>

<form name='frmEditTheme' action='home.php?request=email_templates' method="post" onsubmit="return valform(this);">

<table width="100%" border="0" cellpadding="0" cellspacing="1" class="maininnertable">
      <tr>
        <td align="left" class="menutabletoptd">&nbsp;&nbsp;<a href="home.php?request=email_templates&lettertype=<?=$_REQUEST['lettertype']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>" title="List Themes">List Email Templates</a><strong> <font size="1">>></font><strong>Add <?=$page_type?></strong></td>
      </tr>
      <tr>
        <td class="maininnertabletd2">
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
				  <td align="right" class="fontblacknormal">Letter Title </td>
				  <td align="center"><strong>:</strong></td>
				  <td align="left"><input name="template_lettertitle" type="text" id="template_lettertitle" value="" size="60" />
                    <span class="redtext">*</span></td>
			  </tr>
				<tr>
				  <td align="right" class="fontblacknormal">Letter Type</td>
				  <td align="center"><strong>:</strong></td>
				  <td align="left"><input name="template_lettertype" type="text" id="template_lettertype" value="" size="60">
					  <span class="redtext">*</span></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Subject</td>
				  <td align="center"><strong>:</strong></td>
				  <td align="left"><input name="template_lettersubject" type="text" id="template_lettersubject" value="" size="100" />
                    <span class="redtext">*</span></td>
			  </tr>
				<tr>
				  <td align="right" class="fontblacknormal" valign="top">
			      <p>Content</p></td>
				  <td align="center" valign="top" class="fontblacknormal style1" ><p>:</p></td>
				  <td align="left"><textarea name="template_content" rows="20" cols="100"></textarea>
					  <?php 
					  	/*$editor = new FCKeditor('template_content') ;
						$editor->BasePath = './js/FCKeditor/';
						$editor->Width = '550';
						$editor->Height = '550';
						$editor->ToolbarSet = 'BshopWithImages';
						$editor->Value = $content;
						$editor->Create() ;
						*/
					  	
						?>
				</td>
				</tr>
												
				<tr>
				  <td align="right" valign="top">Code</td>
			      <td align="center" valign="top"><strong>:</strong></td>
			      <td align="left" valign="top"><textarea name="template_code" cols="100" rows="5" id="template_code"></textarea>
			        <br />
			        (Should be in the format '[code]=&gt;Description' seperated by comma)</td>
			  </tr>
				<tr>
				  <td colspan="3" align="right">&nbsp;</td>
				</tr>
				<tr align="center">
				<td>&nbsp;</td>
				<td colspan="3" align="left">
					
					<input type="hidden" name="lettertype" id="lettertype" value="<?=$_REQUEST['lettertype']?>" />
					<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
					<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
					<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
					<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
					<input type="hidden" name="fpurpose" id="fpurpose" value="insert" />
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