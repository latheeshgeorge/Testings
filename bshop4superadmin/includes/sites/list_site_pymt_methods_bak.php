<?php
/*#################################################################
# Script Name 	: edit_site.php
# Description 	: Page for editing sites
# Coded by 		: Sny
# Created on	: 04-June-2007
# Modified by	: Sny
# Modified On	: 06-Jun-2007
#################################################################
#Define constants for this page
*/
$page_type = 'Site';
$help_msg = 'This section helps in editing a Site.';

$sql = "SELECT * FROM sites WHERE site_id=$site_id";
$res = $db->query($sql);
$row = $db->fetch_array($res);
?>
<script language="javascript">
function valform(frm)
{
	fieldRequired = Array('site_title','site_domain','level_id','client_id','theme_id','site_type','site_status');
	fieldDescription = Array('Site Title','Domain name','Console Level','Client','Theme','Site Type','Status');
	fieldEmail = Array('site_email');
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		return true;
	} else {
		return false;
	}
}
function show_consolemenu(siteid)
{
	zz = window.open('includes/sites/site_consolemenu.php?sid='+siteid);
	zz.focus();
}
</script>
<form name='frmAddSite' action='home.php?request=sites' method="post" onsubmit="return valform(this);">

  <table width="100%" border="0" cellpadding="0" cellspacing="1" class="maininnertable">
    <tr>
      <td align="left" class="menutabletoptd">&nbsp;&nbsp;<strong>Edit
        <?=$page_type?>
      </strong></td>
    </tr>
    <tr>
      <td class="maininnertabletd3"><?=$help_msg?>
      </td>
    </tr>
    <tr>
      <td class="maininnertabletd2"><table width="100%"  border="0" cellpadding="4" cellspacing="1" class="">
          <tr align="left">
            <td colspan="7" class="redtext"><div align="left">* <span>are required </span></div></td>
          </tr>
          <tr>
            <td colspan="7" align="right" class="fontblacknormal">
		  	<a href="javascript:show_consolemenu('<?php echo $_REQUEST['site_id']?>')" onclick="" title="Console Menu"><img src="images/consolemenu.gif" width="16" height="16" border="0" title="View Console Menu" /></a>
			&nbsp;<a href="#" onclick="if(delete_confirm()) { location.href='home.php?request=sites&amp;fpurpose=delete&amp;site_id=<?=$row['site_id']?>&amp;title=<?=$title?>&amp;domain=<?=$domain?>&amp;client=<?=$client?>&amp;status=<?=$status?>&amp;theme=<?php echo $theme?>&amp;sort_by=<?=$sort_by?>&amp;sort_order=<?=$sort_order?>&amp;records_per_page=<?=$records_per_page?>';}" title="Site Menu"><img src="images/sitemenu.gif" width="16" height="16" border="0" title="View Site Menu" /></a></td>
          </tr>
          <tr>
            <td width="29%" align="right" class="fontblacknormal">Site Title</td>
            <td width="1%" align="center">:</td>
            <td width="25%" align="left"><input name="site_title" type="text" id="site_title" value="<?=stripslashes($row['site_title'])?>" size="30" />
                <span class="redtext">*</span></td>
            <td width="18%" align="left"><span class="fontblacknormal">Date Bought</span></td>
            <td width="1%" align="left">:</td>
            <td width="8%" align="left"><span class="fontblacknormal">
              <input type="text" name="site_date_bought" id="site_date_bought" maxlength="12" size="12" value="<?=$row['site_date_bought']?>" readonly="" />
            </span></td>
            <td width="18%" align="left"><span class="fontblacknormal"><a href="javascript:show_calendar('frmAddSite.site_date_bought');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a></span></td>
          </tr>
          <tr>
            <td align="right" class="fontblacknormal"><strong>(don't specify </strong><span class="redtext"><strong>http://</strong></span><strong>)</strong></span> Domain</td>
            <td align="center">:</td>
            <td align="left"><span class="fontblacknormal">
              <input name="site_domain" type="text" id="site_domain" value="<?=$row['site_domain']?>" size="30" />
              <span class="redtext">*</span>&nbsp;</span></td>
            <td align="left"><span class="fontblacknormal">Client</span></td>
            <td align="left">:</td>
            <td colspan="2" align="left"><?php
				  $clients_array = array(0 => '-- Select --');
				  $sql_clients = "SELECT client_id,client_company FROM clients";
				  $res_clients = $db->query($sql_clients);
				  while($row_clients = $db->fetch_array($res_clients)) {
				  	$clients_array[$row_clients['client_id']] = $row_clients['client_company'];
				  }
				  echo generateselectbox('client_id',$clients_array,$row['clients_client_id']);
				  ?>
                <span class="redtext">&nbsp;*</span></td>
          </tr>
          <tr>
            <td align="right" class="fontblacknormal">Site Admin email</td>
            <td align="center">:</td>
            <td align="left"><input name="site_email" type="text" id="site_email" value="<?=$row['site_email']?>" size="30" />
                <span class="redtext">&nbsp;*</span></td>
            <td align="left"><span class="fontblacknormal">Monthly Renewal Fee</span></td>
            <td align="left">:</td>
            <td colspan="2" align="left"><input name="site_monthly_fee" type="text" id="site_monthly_fee" value="<?=$row['site_monthly_fee']?>" size="8" /></td>
          </tr>
          <tr>
            <td align="right" class="fontblacknormal">Console Level</td>
            <td align="center">:</td>
            <td align="left"><?php
				  $level_array = array(0 => '-- Select --');
				  $sql_level = "SELECT level_id,level_name FROM console_levels";
				  $res_level = $db->query($sql_level);
				  while($row_level = $db->fetch_array($res_level)) {
				  	$level_array[$row_level['level_id']] = $row_level['level_name'];
				  }
				  echo generateselectbox('level_id',$level_array,$row['console_levels_level_id']);
				  ?>
                <span class="redtext">*</span></td>
            <td align="left"><span class="fontblacknormal">Theme</span></td>
            <td align="left">:</td>
            <td colspan="2" align="left"><?php
				  $clients_array = array(0 => '-- Select --');
				  $sql_themes = "SELECT theme_id,themename FROM themes";
				  $res_themes = $db->query($sql_themes);
				  while($row_themes = $db->fetch_array($res_themes)) {
				  	$themes_array[$row_themes['theme_id']] = $row_themes['themename'];
				  }
				  echo generateselectbox('theme_id',$themes_array,$row['themes_theme_id']);
				  ?>
                <span class="redtext">&nbsp;*</span></td>
          </tr>
          <tr>
            <td align="right" class="fontblacknormal">Site type </td>
            <td align="center">:</td>
            <td align="left"><?php
				  $type_array = array('N'=> 'Normal','B'=>'Broadcaster','R'=>'Receiver');
				  echo generateselectbox('site_type',$type_array,$row['site_type']);
				  ?>
                <span class="redtext">&nbsp;*</span></td>
            <td align="left"><span class="fontblacknormal">Status</span></td>
            <td align="left">:</td>
            <td colspan="2" align="left"><?php
				  $status_array = array('Under Construction' => 'Under Construction','Live' => 'Live','Suspended' => 'Suspended', "Cancelled" => "Cancelled");
				  echo generateselectbox('site_status',$status_array,$row['site_status']);
				  ?>
                <span class="redtext">&nbsp;*</span></td>
          </tr>
          <tr>
            <td align="right" class="fontblacknormal">In Webclinic?</td>
            <td align="center">:</td>
            <td align="left"><input name="in_web_clinic" type="checkbox" id="in_web_clinic" value="1" <?php echo ($row['in_web_clinic']==1)?'checked="checked"':''?> /></td>
            <td align="left"><span class="fontblacknormal">Is Managed SEO?</span></td>
            <td align="left">:</td>
            <td colspan="2" align="left"><input name="is_managed_seo" type="checkbox" id="is_managed_seo" value="1" <?php echo ($row['is_managed_seo']==1)?'checked="checked"':''?> /></td>
          </tr>
          <tr>
            <td align="right" class="fontblacknormal">Google Sitemap Verification Code </td>
            <td align="center">:</td>
            <td colspan="5" align="left"><input name="meta_verificationcode" type="text" id="meta_verificationcode" value="<?php echo $row['meta_verificationcode']?>" size="40" /></td>
          </tr>
          <tr>
            <td align="right" class="fontblacknormal">XML Filename </td>
            <td align="center">:</td>
            <td align="left"><input name="site_xml_filename" type="text" size="30" value="<?php echo $row['site_xml_filename']?>" /></td>
            <td align="left"><span class="fontblacknormal">XML Key </span></td>
            <td align="left">:</td>
            <td colspan="2" align="left"><input name="site_xml_key" type="text" size="30" value="<?php echo $row['site_xml_key']?>" /></td>
          </tr>
          <tr>
            <td align="right" class="fontblacknormal">Max Products</td>
            <td align="center">:</td>
            <td align="left"><input name="site_maxproducts" type="text" size="5" value="<?php echo $row['site_maxproducts']?>" /></td>
            <td align="left">Max Static Pages</td>
            <td align="left">:</td>
            <td colspan="2" align="left"><input name="site_maxstaticpages" type="text" size="5" value="<?php echo $row['site_maxstaticpages']?>" /></td>
          </tr>
          <tr>
            <td align="right" class="fontblacknormal">Max Categories</td>
            <td align="center">:</td>
            <td align="left"><input name="site_maxcategories" type="text" size="5" value="<?php echo $row['site_maxcategories']?>" /></td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td colspan="2" align="left">&nbsp;</td>
          </tr>
          <tr>
            <td align="right" class="fontblacknormal">&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td colspan="2" align="left">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="7" align="right">&nbsp;</td>
          </tr>
          <tr align="center">
            <td>&nbsp;</td>
            <td colspan="7" align="left">	<input type="hidden" name="site_id" id="site_id" value="<?=$_REQUEST['site_id']?>" />
					<input type="hidden" name="title" id="title" value="<?=$_REQUEST['title']?>" />
					<input type="hidden" name="domain" id="domain" value="<?=$_REQUEST['domain']?>" />
					<input type="hidden" name="client" id="site_client" value="<?=$_REQUEST['client']?>" />
					<input type="hidden" name="status" id="site_status" value="<?=$_REQUEST['status']?>" />
					<input type="hidden" name="theme" id="theme" value="<?=$_REQUEST['theme']?>" />
					<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
					<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
					<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
					<input type="hidden" name="old_domain" id="old_domain" value="<?=$row['site_domain']?>" />
					<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
					<input type="hidden" name="fpurpose" id="fpurpose" value="update" />
					<input type="Submit" name="Updatesite_submit" id="Updatesite_submit" value="Update" class="input-button">				</td>
          </tr>
          <tr>
            <td colspan="7" align="right">&nbsp;</td>
          </tr>
      </table></td>
    </tr>
  </table>
</form>