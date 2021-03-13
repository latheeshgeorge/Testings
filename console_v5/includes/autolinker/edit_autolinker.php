<?php
/*#################################################################
# Script Name 		: edit_autolinker.php
# Description 		: Page for editing autolinker details
# Coded by 			: Sny
# Created on		: 03-Aug-2009
#################################################################*/

if(!$inWebclinic)
{
	echo "<center><br><br><span class='redtext'><strong>You are not authorised to view this page as your website is not there in Webclinic.</strong></span></center>";
	exit;
}
#Define constants for this page
$page_type 		= 'Autolinker';
$help_msg		= get_help_messages('EDIT_AUTOLINKER_MESS1');
$autolinker_id	= ($_REQUEST['autolinker_id']?$_REQUEST['autolinker_id']:$_REQUEST['checkbox'][0]);
$sql_comp		= "SELECT * FROM seo_autolinker WHERE autolinker_id =".$autolinker_id. " AND sites_site_id=$ecom_siteid";
$res_comp 		= $db->query($sql_comp);
$row_comp 		= $db->fetch_array($res_comp);
?>	
<script language="javascript" type="text/javascript">
function valform(frm)
{
	fieldRequired = Array('autolinker_keyword','autolinker_url');
	fieldDescription = Array('Keyword','URL');
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
<form name='frmeditautlinker' action='home.php?request=autolinker' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=autolinker&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_autolinker_keyword=<?=$_REQUEST['search_autolinker_keyword']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Autolinker Details</a><span> Edit Autolinker Details</span></div></td>
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
		<div class="editarea_div">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="17%" align="left" valign="top" class="tdcolorgray" >Keyword <span class="redtext">*</span> </td>
          <td width="83%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="autolinker_keyword"  value="<?=stripslashes($row_comp['autolinker_keyword'])?>" size="80" />		  </td>
        </tr>
        <tr>
          <td align="left" valign="top" class="tdcolorgray" >URL<span class="redtext">*</span></td>
          <td align="left" valign="middle" class="tdcolorgray"><input name="autolinker_url" type="text" class="input" id="autolinker_url" value="<? echo stripslashes($row_comp['autolinker_url'])?>" size="80" /></td>
        </tr>
        <tr>
          <td align="left" valign="top" class="tdcolorgray" >Number of Times </td>
          <td align="left" valign="middle" class="tdcolorgray"><input name="autolinker_no_of_times" type="text" class="input" id="autolinker_no_of_times" value="<?=$row_comp['autolinker_no_of_times']?>" /></td>
        </tr>
        <tr>
          <td align="left" valign="top" class="tdcolorgray" >Allow No Follow </td>
          <td align="left" valign="middle" class="tdcolorgray"><input type="checkbox" name="autolinker_allow_no_follow" id="autolinker_allow_no_follow" value="1" <? echo ($row_comp['autolinker_allow_no_follow']==1)?'checked':''?>/></td>
        </tr>
        <tr>
          <td align="left" valign="top" class="tdcolorgray" >CSS Class Name </td>
          <td align="left" valign="middle" class="tdcolorgray"><input name="autolinker_css_class" type="text" class="input" id="autolinker_css_class" value="<?=$row_comp['autolinker_css_class']?>" size="40" /></td>
        </tr>
        <tr>
          <td align="left" valign="top" class="tdcolorgray" >&nbsp;</td>
          <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
        </tr>
		</table>
		</div>
		</td>
        </tr>
		
		<tr>
			<td colspan="2" align="center" valign="middle" class="tdcolorgray" width="100%">
				<div class="editarea_div">
					<table width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td width="100%" align="right" valign="middle">
							<input type="hidden" name="autolinker_id" id="autolinker_id" value="<?=$autolinker_id?>" />
							<input type="hidden" name="search_autolinker_keyword" id="search_autolinker_keyword" value="<?=$_REQUEST['search_autolinker_keyword']?>" />
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

