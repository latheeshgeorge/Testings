<?php
	/*#################################################################
	# Script Name 	: add_bow.php
	# Description 	: Page for adding Giftwrap Bow
	# Coded by 		: SKR
	# Created on	: 21-July-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
#Define constants for this page
$page_type = 'Add Keywords';
$help_msg =get_help_messages('ADD_KEYWORD_MESS1');

?>	
<script language="javascript" type="text/javascript">
function valform(frm)
{
	fieldRequired = Array('keyword_word');
	fieldDescription = Array('Keyword');
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
<form name='frmAddKeyword' action='home.php?request=costperclick_keyword' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" align="left" valign="middle" class="treemenutd"><a href="home.php?request=costperclick_keyword&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Cost Per Click  Keywords</a> &gt;&gt; Add Cost Per Click Keyword</td>
        </tr>
        <tr>
          <td colspan="2" align="left" valign="middle" class="helpmsgtd" ><?=$help_msg ?></td>
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
          <td width="15%" align="left" valign="middle" class="tdcolorgray" >Keyword <span class="redtext">*</span> </td>
          <td width="85%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="keyword_word"  value="<?=$_REQUEST['keyword_word']?>" />		  </td>
        </tr>
		 
		 <tr>
          <td colspan="2" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
        </tr>
		<tr>
          <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td align="left" valign="middle" class="tdcolorgray">
		  
		  <input type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>" />
		   <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
		  <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
		  <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
		  <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
		  <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
		  <input type="hidden" name="fpurpose" id="fpurpose" value="insert" />
		  <input name="Submit" type="submit" class="red" value="Submit" /></td>
        </tr>
      </table>
</form>	  

