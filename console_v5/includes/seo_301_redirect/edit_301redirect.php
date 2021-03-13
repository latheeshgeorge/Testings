<?php
/*#################################################################
# Script Name 		: edit_seo_301redirect.php
# Description 		: Page for editing seo_301redirect details
# Coded by 			: Sny
# Created on		: 03-Aug-2009
#################################################################*/
#Define constants for this page
$page_type 		= '301 Redirect';
$help_msg		= get_help_messages('EDIT_REDIRECT_MESS1');
$redirect_id	= ($_REQUEST['redirect_id']?$_REQUEST['redirect_id']:$_REQUEST['checkbox'][0]);
$sql_comp		= "SELECT * FROM seo_redirect WHERE redirect_id =".$redirect_id. " AND sites_site_id=$ecom_siteid";
$res_comp 		= $db->query($sql_comp);
$row_comp 		= $db->fetch_array($res_comp);
?>	
<script language="javascript" type="text/javascript">
function valform(frm)
{
	fieldRequired = Array('old_url','new_url');
	fieldDescription = Array('Old URL','New URL');
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
<form name='frmeditredirect' action='home.php?request=seo_301redirect' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=seo_301redirect&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&redirect_old_url=<?=$_REQUEST['redirect_old_url']?>&redirect_new_url=<?=$_REQUEST['redirect_new_url']?>&srch_access_startdate=<?=$_REQUEST['srch_access_startdate']?>&srch_access_enddate=<?=$_REQUEST['srch_access_enddate']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List 301 Redirect URLs</a><span> Edit 301 Redirect URLs</span></td>
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
          <td align="left" valign="top" class="tdcolorgray" >Old URL<span class="redtext">*</span></td>
          <td align="left" valign="middle" class="tdcolorgray"><input name="old_url" type="text" class="input" id="old_url" value="<? echo stripslashes($row_comp['redirect_old_url'])?>" size="80" />&nbsp;&nbsp; (e.g. Address = "http://example.com/oldURL.html)</td>
        </tr>
        <tr>
          <td align="left" valign="top" class="tdcolorgray" >New URL<span class="redtext">*</span></td>
          <td align="left" valign="middle" class="tdcolorgray"><input name="new_url" type="text" class="input" id="new_url" value="<? echo stripslashes($row_comp['redirect_new_url'])?>" size="80" />&nbsp;&nbsp; (e.g. Address = "http://example.com/newURL.html)</td>
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
							<input type="hidden" name="redirect_id" id="redirect_id" value="<?=$redirect_id?>" />
							<input type="hidden" name="redirect_old_url" id="redirect_old_url" value="<?=$_REQUEST['redirect_old_url']?>" />
							<input type="hidden" name="redirect_new_url" id="redirect_new_url" value="<?=$_REQUEST['redirect_new_url']?>" />
							<input type="hidden" name="srch_access_startdate" id="srch_access_startdate" value="<?=$_REQUEST['srch_access_startdate']?>" />
							<input type="hidden" name="srch_access_enddate" id="srch_access_enddate" value="<?=$_REQUEST['srch_access_enddate']?>" />
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

