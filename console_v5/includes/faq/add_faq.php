<?php
/*#################################################################
# Script Name 	: add_faq.php
# Description 		: Page for adding faq
# Coded by 		: Sny
# Created on		: 01-Jan-2009
#################################################################*/
#Define constants for this page
$page_type = 'FAQ';
$help_msg = get_help_messages('ADD_FAQ_MESS1');
?>	
<script language="javascript" type="text/javascript">
function valform(frm)
{
	fieldRequired = Array('faq_question');
	fieldDescription = Array('Question');
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
	
	if(document.frmfaq.shelf_activateperiodchange.checked==true)
	{
		document.getElementById('show_date_period').style.display = '';
	}
	else
	{
		document.getElementById('show_date_period').style.display = 'none';
	}
}
</script>
<form name='frmfaq' action='home.php?request=faq' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=faq&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_faq_question=<?=$_REQUEST['search_faq_question']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List FAQ</a><span> Add FAQ</span></div></td>
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
		<div class="editarea_div">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="15%" align="left" valign="top" class="tdcolorgray" >Question <span class="redtext">*</span> </td>
          <td width="85%" align="left" valign="middle" class="tdcolorgray">
		  <input name="faq_question" type="text" class="input" id="faq_question" value="<?=$_REQUEST['faq_question']?>" size="80"  />		  </td>
        </tr>
		
		 <tr>
		   <td align="left" valign="top" class="tdcolorgray" >Answer </td>
		   <td align="left" valign="middle" class="tdcolorgray">
		   <?php
				//$editor_elements = "faq_answer";
				//include_once("js/tinymce.php");	
			
		       
			   ?>
			   <textarea cols="93" rows="15" id="faq_answer" name="faq_answer"><?=stripslashes($_REQUEST['faq_answer'])?></textarea>
		   </td>
	      </tr>
		 <tr>
          <td width="15%" align="left" valign="top" class="tdcolorgray" >Sort Order  <span class="redtext">*</span></td>
          <td width="85%" align="left" valign="middle" class="tdcolorgray">
		  <input name="faq_sortorder" type="text" class="input" id="faq_sortorder" value="<?=$_REQUEST['faq_sortorder']?>" size="3"  /> 
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_FAQ_ORDER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		<tr>
          <td align="left" valign="top" class="tdcolorgray" >Hide</td>
          <td align="left" valign="middle" class="tdcolorgray"><input type="radio" name="faq_hide" value="1" <? if($_REQUEST['faq_hide']==1){ echo "checked";} ?>  />Yes<input type="radio" name="faq_hide" value="0" <? if($_REQUEST['faq_hide']==0){ echo "checked";} ?>  />No
		  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_FAQ_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		</table>
		</div>
		</td>		
		</tr>
		<tr>
         <td colspan="2" align="center" valign="middle" class="tdcolorgray" width="100%">
		  <div class="editarea_div">
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td align="right" valign="middle" class="tdcolorgray" >
			  <input type="hidden" name="search_faq_question" id="search_faq_question" value="<?=$_REQUEST['search_faq_question']?>" />
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
		  </div>
		  </td>
        </tr>
      </table>
</form>	  
