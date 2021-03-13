<?php
/*
#################################################################
# Script Name 	: edit_text_link_ad.php
# Description 	: Page for editing Text Link Ad 
# Coded by 		: SKR
# Created on	: 06-June-2007
# Modified by	: 
# Modified On	: 
#################################################################
*/
#Define constants for this page
$page_type = 'Text Link Ad';
$help_msg = 'This section helps in editing the text link ad values for a site.';

$sql ="SELECT site_title,site_domain,site_xml_filename,site_xml_key FROM sites WHERE site_id=".$_REQUEST['site_id']; 
$res = $db->query($sql);
$row = $db->fetch_array($res);
?>
<script language="javascript">
function valform(frm)
{
	fieldRequired = Array('site_xml_filename','site_xml_key');
	fieldDescription = Array('File Name','Key');
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
<form name='frmEditTheme' action='home.php?request=text_link_ad' method="post" onsubmit="return valform(this);">

<table width="100%" border="0" cellpadding="0" cellspacing="1" class="maininnertable">
      <tr>
        <td align="left" class="menutabletoptd">&nbsp;<a href="home.php?request=text_link_ad&title=<?=$_REQUEST['title']?>&domain=<?=$_REQUEST['domain']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&title=<?=$_REQUEST['title']?>&domain=<?=$_REQUEST['domain']?>"><b>Manage Text Link Ads</b></a> <font size="1">>></font>&nbsp;&nbsp;<strong>Edit <?=$page_type?></strong></td>
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
				  <td align="right" class="fontblacknormal"><b>Title</b></td>
				  <td align="center">:</td>
				  <td align="left"><?=$row['site_title']?></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal"><b>Domain</b></td>
				  <td align="center">:</td>
				  <td align="left"><?=$row['site_domain']?></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Default XML File name</td>
				  <td align="center">:</td>
				  <td align="left"><input name="site_xml_filename" type="text" id="site_xml_filename" value="<?=$row['site_xml_filename']?>" size="30">
					  <span class="redtext">*</span></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Default XML Key</td>
				  <td align="center">:</td>
				  <td align="left"><input name="site_xml_key" type="text" id="site_xml_key" value="<?=$row['site_xml_key']?>" size="5">
					  <span class="redtext">*</span></td>
				</tr>
					<tr>
				  <td align="center" class="fontblacknormal" colspan="3">&nbsp;</td>
				 
				</tr>
					<tr>
				  <td align="center" class="fontblacknormal" colspan="3"><b><a href="home.php?request=text_link_ad&site_id=<?=$_REQUEST['site_id']?>&fpurpose=manage_category_xml&title=<?=$_REQUEST['title']?>&domain=<?=$_REQUEST['domain']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>">Click here to manage XML files for Categories</a></b></td>
				 
				</tr>
					<tr>
				  <td align="center" class="fontblacknormal" colspan="3"><b><a href="home.php?request=text_link_ad&site_id=<?=$_REQUEST['site_id']?>&fpurpose=manage_product_xml&title=<?=$_REQUEST['title']?>&domain=<?=$_REQUEST['domain']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>">Click here to manage XML files for Products</a></b></td>
				 
				</tr>
				<tr>
				  <td align="center" class="fontblacknormal" colspan="3"><b><a href="home.php?request=text_link_ad&site_id=<?=$_REQUEST['site_id']?>&fpurpose=manage_staticpage_xml&title=<?=$_REQUEST['title']?>&domain=<?=$_REQUEST['domain']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>">Click here to manage XML files for Static Pages</a></b></td>
				 
				</tr>
								
				<tr>
				  <td colspan="3" align="right">&nbsp;</td>
				</tr>
				<tr align="center">
				<td>&nbsp;</td>
				<td colspan="3" align="left">
					<input type="hidden" name="site_id" id="site_id" value="<?=$_REQUEST['site_id']?>" />
					<input type="hidden" name="title" id="title" value="<?=$_REQUEST['title']?>" />
					<input type="hidden" name="domain" id="domain" value="<?=$_REQUEST['domain']?>" />
					<input type="hidden" name="pass_sort_by" id="pass_sort_by" value="<?=$_REQUEST['sort_by']?>" />
					<input type="hidden" name="pass_sort_order" id="pass_sort_order" value="<?=$_REQUEST['sort_order']?>" />
					<input type="hidden" name="pass_records_per_page" id="pass_records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
					<input type="hidden" name="pass_pg" id="pass_pg" value="<?=$_REQUEST['pg']?>" />
					<input type="hidden" name="fpurpose" id="fpurpose" value="update" />
					<input type="Submit" name="Submit" id="Submit" value="Edit" class="input-button">
				</td>
				</tr>
				<tr>
				  <td colspan="3" align="right">&nbsp;</td>
				</tr>
			</table>
		</td>
      </tr>
    </table>
</form>