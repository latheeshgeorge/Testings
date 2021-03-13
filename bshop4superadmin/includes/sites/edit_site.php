<?php
/*#################################################################
# Script Name 	: edit_site.php
# Description 	: Page for editing sites
# Coded by 		: Sny
# Created on	: 04-June-2007
# Modified by	: Sny
# Modified On	: 27-May-2008
#################################################################
#Define constants for this page
*/
$page_type = 'Site';
$help_msg = 'This section helps in editing a Site.';

$sql = "SELECT * FROM sites WHERE site_id=".$_REQUEST['site_id']." LIMIT 1";
$res = $db->query($sql);
$row = $db->fetch_array($res);
?>
<script language="javascript">
function valform(frm)
{
	fieldRequired = Array('site_title','site_domain','site_domain_alias','level_id','client_id','site_type','site_status'); //'theme_id',
	fieldDescription = Array('Site Title','Domain name','Domain name alias','Console Level','Client','Site Type','Status'); //'Theme',
	fieldEmail = Array('site_email');
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		if(frm.theme_id.value==-1) {
			alert("Please Select Theme");
			return false;
		}
		return true;
	} else {
		return false;
	}
}
function show_consolemenu(siteid)
{
	zz = window.open('includes/sites/site_consolemenu.php?sid='+siteid,'console_menu','top=0, left=0, menubar=0, resizable=0, scrollbars=1, toolbar=0,width=420,height=600');
	zz.focus();
}
function show_sitefeatures(siteid)
{
	zz = window.open('includes/sites/site_features.php?sid='+siteid,'site_menu','top=0, left=0, menubar=0, resizable=0, scrollbars=1, toolbar=0,width=480,height=600');
	zz.focus();
}
function changechecked(check,type)
{
if(type=='in_web_clinic')
{
 document.frmAddSite.is_managed_seo.checked = false; 
}
else if(type=='is_managed_seo')
{
document.frmAddSite.in_web_clinic.checked = false; 
}
}

function handle_googlesettings(obj)
{
	
	if(obj.name=='is_meta_verificationcode')
	{
			if(obj.checked==true)
			{
				document.frmAddSite.meta_verificationcode.className='normal_class';
				document.frmAddSite.meta_verificationcode.readOnly  = false;
			}
			else if(obj.checked==false)
			{
				document.frmAddSite.meta_verificationcode.className='disabled_class';
				document.frmAddSite.meta_verificationcode.readOnly  = true;
			}	
	}
	if(obj.name=='is_yahoometa_verificationcode')
	{
			if(obj.checked==true)
			{
				document.frmAddSite.yahoo_meta_verificationcode.className='normal_class';
				document.frmAddSite.yahoo_meta_verificationcode.readOnly  = false;
			}
			else if(obj.checked==false)
			{
				document.frmAddSite.yahoo_meta_verificationcode.className='disabled_class';
				document.frmAddSite.yahoo_meta_verificationcode.readOnly  = true;
			}	
	}
	if(obj.name=='is_msnmeta_verificationcode')
	{
			if(obj.checked==true)
			{
				document.frmAddSite.msn_meta_verificationcode.className='normal_class';
				document.frmAddSite.msn_meta_verificationcode.readOnly  = false;
			}
			else if(obj.checked==false)
			{
				document.frmAddSite.msn_meta_verificationcode.className='disabled_class';
				document.frmAddSite.msn_meta_verificationcode.readOnly  = true;
			}	
	}
	if(obj.name=='is_twitter_account')
	{
			if(obj.checked==true)
			{
				document.frmAddSite.site_twitter_account_id.className='normal_class';
				document.frmAddSite.site_twitter_account_id.readOnly  = false;
			}
			else if(obj.checked==false)
			{
				document.frmAddSite.site_twitter_account_id.className='disabled_class';
				document.frmAddSite.site_twitter_account_id.readOnly  = true;
			}	
	}
	if(obj.name=='is_google_urchinwebtracker_code')
	{
			if(obj.checked==true)
			{
				document.frmAddSite.google_webtracker_urchin_code.className='normal_class';
				document.frmAddSite.google_webtracker_urchin_code.readOnly  = false;
			}
			else if(obj.checked==false)
			{
				document.frmAddSite.google_webtracker_urchin_code.className='disabled_class';
				document.frmAddSite.google_webtracker_urchin_code.readOnly  = true;
			}	
	}
	else if(obj.name=='is_google_webtracker_code')
	{
		if(obj.checked==true)
			{
			
				document.frmAddSite.google_webtracker_code.className='normal_class';
				document.frmAddSite.google_webtracker_code.readOnly  = false;
			}
			else if(obj.checked==false)
			{
				document.frmAddSite.google_webtracker_code.className='disabled_class';
				document.frmAddSite.google_webtracker_code.readOnly  = true;
			}
	}
	else if(obj.name=='is_google_adword_checkout')
	{
		if(obj.checked==true)
			{
			
				document.frmAddSite.google_adword_conversion_id.className='normal_class';
				document.frmAddSite.google_adword_conversion_language.className='normal_class';
				document.frmAddSite.google_adword_conversion_format.className='normal_class';
				document.frmAddSite.google_adword_conversion_color.className='normal_class';
				document.frmAddSite.google_adword_conversion_label.className='normal_class';
				document.frmAddSite.google_adword_conversion_id.readOnly  = false;
				document.frmAddSite.google_adword_conversion_language.readOnly  = false;
				document.frmAddSite.google_adword_conversion_format.readOnly  = false;
				document.frmAddSite.google_adword_conversion_color.readOnly  = false;
				document.frmAddSite.google_adword_conversion_label.readOnly  = false;
			}
			else if(obj.checked==false)
			{
				document.frmAddSite.google_adword_conversion_id.className='disabled_class';
				document.frmAddSite.google_adword_conversion_language.className='disabled_class';
				document.frmAddSite.google_adword_conversion_format.className='disabled_class';
				document.frmAddSite.google_adword_conversion_color.className='disabled_class';
				document.frmAddSite.google_adword_conversion_label.className='disabled_class';
				document.frmAddSite.google_adword_conversion_id.readOnly  = true;
				document.frmAddSite.google_adword_conversion_language.readOnly  = true;
				document.frmAddSite.google_adword_conversion_format.readOnly  = true;
				document.frmAddSite.google_adword_conversion_color.readOnly  = true;
				document.frmAddSite.google_adword_conversion_label.readOnly  = true;
			}
	}	
}	
</script>
<form name='frmAddSite' action='home.php?request=sites' method="post" onsubmit="return valform(this);">

  <table width="100%" border="0" cellpadding="0" cellspacing="1" class="maininnertable">
    <tr>
      <td align="left" class="menutabletoptd">&nbsp;<a href="home.php?request=sites&amp;title=<?=$title?>&amp;domain=<?=$domain?>&amp;client=<?=$client?>&amp;theme=<?php echo $_REQUEST['theme']?>&amp;mobiletheme=<?php echo $_REQUEST['mobiletheme']?>&amp;site_status=<?=$site_status?>&amp;sort_by=<?=$sort_by?>&amp;sort_order=<?=$sort_order?>&amp;records_per_page=<?=$records_per_page?>&amp;pg=<?=$pg?>"><strong>List Sites</strong></a><strong> <b><font size="1">&gt;&gt;</font></b></strong><strong>Edit
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
            <td colspan="8" class="redtext"><div align="left">* <span>are required </span></div></td>
          </tr>
          <tr>
            <td colspan="8" align="right" class="fontblacknormal">
		  	<a href="javascript:show_consolemenu('<?php echo $_REQUEST['site_id']?>')" onclick="" title="Console Menu"><img src="images/consolemenu.gif" width="16" height="16" border="0" title="View Console Menu" /></a>
			&nbsp;<a href="javascript:show_sitefeatures('<?php echo $_REQUEST['site_id']?>')" title="Site Menu"><img src="images/sitemenu.gif" width="16" height="16" border="0" title="View Features in Site" /></a></td>
          </tr>
          <tr>
            <td width="29%" align="right" class="fontblacknormal">Site Title</td>
            <td width="1%" align="center">:</td>
            <td colspan="2" align="left"><input name="site_title" type="text" id="site_title" value="<?=stripslashes($row['site_title'])?>" size="30" />
                <span class="redtext">*</span></td>
            <td width="18%" align="left"><span class="fontblacknormal">Date Bought</span></td>
            <td width="1%" align="left">:</td>
            <td width="7%" align="left"><span class="fontblacknormal">
              <input type="text" name="site_date_bought" id="site_date_bought" maxlength="12" size="12" value="<?=$row['site_date_bought']?>" readonly="" />
            </span></td>
            <td width="18%" align="left"><span class="fontblacknormal"><a href="javascript:show_calendar('frmAddSite.site_date_bought');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a></span></td>
          </tr>
          <tr>
            <td align="right" class="fontblacknormal"><strong>(don't specify </strong><span class="redtext"><strong>http://</strong></span><strong>)</strong></span> Domain</td>
            <td align="center">:</td>
            <td colspan="2" align="left"><span class="fontblacknormal">
              <input name="site_domain" type="text" id="site_domain" value="<?=$row['site_domain']?>" size="30" />
              <span class="redtext">*</span>&nbsp;</span></td>
            <td align="left">Domain Alias </td>
            <td align="left">:</td>
            <td colspan="2" align="left"><input name="site_domain_alias" type="text" id="site_domain_alias" value="<?=$row['site_domain_alias']?>" size="30" />
              <span class="fontblacknormal"><span class="redtext">*</span>&nbsp;</span></td>
          </tr>
          <tr>
            <td align="right" class="fontblacknormal">Client</td>
            <td align="center">:</td>
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
            <td align="left"><span class="fontblacknormal">Monthly Renewal Fee</span></td>
            <td align="left">:</td>
            <td colspan="2" align="left"><input name="site_monthly_fee" type="text" id="site_monthly_fee" value="<?=$row['site_monthly_fee']?>" size="8" /></td>
          </tr>
          <tr>
            <td align="right" class="fontblacknormal">Site Admin email</td>
            <td align="center">:</td>
            <td colspan="2" align="left"><input name="site_email" type="text" id="site_email" value="<?=$row['site_email']?>" size="30" />
                <span class="redtext">&nbsp;*</span></td>
            <td align="left"></td>
            <td align="left"></td>
            <td colspan="2" align="left"></td>
          </tr>
          <tr>
            <td align="right"><span class="fontblacknormal">Web Theme</span></td>
           <td align="left">:</td>
            <td colspan="2" align="left"><?php
				  $themes_array = array(-1 => '-- Select --');
				  $sql_themes = "SELECT theme_id,themename FROM themes WHERE themetype='Normal'";
				  $res_themes = $db->query($sql_themes);
  				  	$themes_array[0] = 'Custom';
				  while($row_themes = $db->fetch_array($res_themes)) {
				  	$themes_array[$row_themes['theme_id']] = $row_themes['themename'];
				  }
					
				  echo generateselectbox('theme_id',$themes_array,$row['themes_theme_id']);
				  ?>
              <span class="redtext">&nbsp;*</span></td>
            <td align="left"><span class="fontblacknormal">Mobile Theme</span></td>
            <td align="left">:</td>
            <td colspan="2" align="left"><?php
				  $themes_array = array(-1 => '-- Select --');
				  $sql_themes = "SELECT theme_id,themename FROM themes WHERE themetype='Mobile'";
				  $res_themes = $db->query($sql_themes);
				  while($row_themes = $db->fetch_array($res_themes)) {
				  	$themes_array[$row_themes['theme_id']] = $row_themes['themename'];
				  }
					
				  echo generateselectbox('mobile_theme_id',$themes_array,$row['mobile_themes_theme_id']);
				  ?>             </td>
          </tr>
          <tr>
            <td align="right" class="fontblacknormal">Console Level</td>
            <td align="center">:</td>
            <td colspan="2" align="left"><?php
				  $level_array = array(0 => '-- Select --');
				  $sql_level = "SELECT level_id,level_name FROM console_levels";
				  $res_level = $db->query($sql_level);
				  while($row_level = $db->fetch_array($res_level)) {
				  	$level_array[$row_level['level_id']] = $row_level['level_name'];
				  }
				  echo generateselectbox('level_id',$level_array,$row['console_levels_level_id']);
				  ?>
                <span class="redtext">*</span></td>
            <td align="left"><span class="fontblacknormal">Status</span></td>
            <td align="left">:</td>
            <td colspan="2" align="left"><?php
				$status_array = array(0=>'-- Select --','Awaiting Setup'=>'Awaiting Setup','Setup Completed'=>'Setup Completed','Under Construction' => 'Under Construction','Live' => 'Live','Suspended' => 'Suspended', "Cancelled" => "Cancelled");
				  echo generateselectbox('site_status',$status_array,$row['site_status']);
				  ?>
              <span class="redtext">&nbsp;*</span></td>
          </tr>
          <tr>
            <td align="right" class="fontblacknormal" valign='top'>Site type </td>
            <td align="center" valign='top'>:</td>
            <td colspan="2" align="left" valign='top'><?php
				  $type_array = array('N'=> 'Normal','B'=>'Broadcaster','R'=>'Receiver');
				  echo generateselectbox('site_type',$type_array,$row['site_type']);
				  ?>
                <span class="redtext">&nbsp;*</span></td>
            <td align="left" valign='top'>SEO Engineer Email Ids</td>
                          <td align="left" valign='top'>:</td>
                          <td colspan="2" align="left"><input type="text" name='site_updation_notification_emailids' value='<?php echo stripslashes($row['site_updation_notification_emailids'])?>' size='30'><br>(use , for multiple ids)</td>
          </tr>
          <tr>
            <td align="right" class="fontblacknormal">In Webclinic?</td>
            <td align="center">:</td>
            <td colspan="2" align="left"><input name="in_web_clinic" type="checkbox" id="in_web_clinic" value="1" <?php echo ($row['in_web_clinic']==1)?'checked="checked"':''?> onclick="changechecked(this.checked,this.name)" /></td>
            <td align="left"><span class="fontblacknormal">Is Managed SEO?</span></td>
            <td align="left">:</td>
            <td colspan="2" align="left"><input name="is_managed_seo" type="checkbox" id="is_managed_seo" value="1" <?php echo ($row['is_managed_seo']==1)?'checked="checked"':''?> onclick="changechecked(this.checked,this.name)" />
            <?php /*?><input name="webtracker_account_id" type="text" id="webtracker_account_id" size="25" value="<?=$row['webtracker_account_id']?>" /><?php */?></td>
          </tr>
          <tr>
            <td align="right" class="fontblacknormal">Use Urls without Subfolders? </td>
            <td align="center">:</td>
            <td colspan="2" align="left"><input name="advanced_seo" type="checkbox" id="1" value="1" <?php echo ($row['advanced_seo']=='Y')?'checked="checked"':''?> /></td>
            <td align="left">Payment in testing mode</td>
            <td align="left">:</td>
            <td colspan="2" align="left"><input name="site_intestmod" type="checkbox" id="site_intestmod" value="1" <?php echo ($row['site_intestmod']==1)?'checked="checked"':''?>/></td>
          </tr>
          <tr>
            <td align="right" class="fontblacknormal">Activate Order Invoice?</td>
            <td align="center">:</td>
            <td colspan="2" align="left"><input name="site_activate_invoice" type="checkbox" id="site_activate_invoice" value="1" <?php echo ($row['site_activate_invoice']==1)?'checked="checked"':''?> /></td>
            <td align="left">In Mobile Application</td>
            <td align="left">:</td>
            <td colspan="2" align="left"><input name="in_mobile_api" type="checkbox" id="in_mobile_api" value="1" <?php echo ($row['in_mobile_api']==1)?'checked="checked"':''?> /></td>
          </tr>
          <tr>
              <td align="right" class="fontblacknormal">Enable Category Grid Display? </td>
              <td align="center">&nbsp;</td>
              <td colspan="2" align="left"><input name="site_grid_enable" type="checkbox" id="site_grid_enable" value="1" <?php echo ($row['site_grid_enable']==1)?'checked="checked"':''?> /></td>
              <td align="left">Customer registration email verification</td>
              <td align="left">:</td>
              <td colspan="2" align="left"><input name="site_email_verification" type="checkbox" id="site_email_verification" value="1" <?php echo ($row['site_email_verification']==1)?'checked="checked"':''?> /></td>
            </tr>
            <tr>
              <td align="right" class="fontblacknormal">Enable Linked Products In Cart? </td>
              <td align="center">&nbsp;</td>
              <td colspan="6" align="left"><input name="linked_product_cart" type="checkbox" id="linked_product_cart" value="1" <?php echo ($row['linked_product_cart']==1)?'checked="checked"':''?> /></td>
                     </tr>
         <?php /*?> <tr>
            <td align="right" class="fontblacknormal">XML Filename </td>
            <td align="center">:</td>
            <td colspan="2" align="left"><input name="site_xml_filename" type="text" size="30" value="<?php echo $row['site_xml_filename']?>" /></td>
            <td align="left"><span class="fontblacknormal">XML Key </span></td>
            <td align="left">:</td>
            <td colspan="2" align="left"><input name="site_xml_key" type="text" size="30" value="<?php echo $row['site_xml_key']?>" /></td>
          </tr><?php */?>
        <?php /*?>  <tr>
            <td align="right" class="fontblacknormal">Max Products</td>
            <td align="center">:</td>
            <td colspan="2" align="left"><input name="site_maxproducts" type="text" size="5" value="<?php echo $row['site_maxproducts']?>" /></td>
            <td align="left">Max Static Pages</td>
            <td align="left">:</td>
            <td colspan="2" align="left"><input name="site_maxstaticpages" type="text" size="5" value="<?php echo $row['site_maxstaticpages']?>" /></td>
          </tr><?php */?>
        <?php /*?>  <tr>
            <td align="right" class="fontblacknormal">Max Categories</td>
            <td align="center">:</td>
            <td colspan="2" align="left"><input name="site_maxcategories" type="text" size="5" value="<?php echo $row['site_maxcategories']?>" /></td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td colspan="2" align="left">&nbsp;</td>
          </tr><?php */?>
		  <!--FB login script starts here-->
          <tr>
            <td  align="left" class="fontblacknormal"  nowrap="nowrap" colspan="8"><b>Facebook Login Settings</b></td>
          </tr>
          <tr>
            <td  align="right" class="fontblacknormal" nowrap="nowrap">Enable Facebook Logn? </td>
            <td  align="left" class="fontblacknormal" nowrap="nowrap">:</td>
            <td colspan="2"  align="left" nowrap="nowrap" class="fontblacknormal"><input name="site_fb_enable" type="checkbox" id="site_fb_enable" value="1" <?php echo ($row['site_fb_enable']==1)?'checked="checked"':''?> /></td>
            <td  align="left" class="fontblacknormal" nowrap="nowrap">&nbsp;</td>
            <td  align="left" class="fontblacknormal" nowrap="nowrap">&nbsp;</td>
            <td colspan="2"  align="left" nowrap="nowrap" class="fontblacknormal">&nbsp;</td>
          </tr>
          <tr>
            <td  align="right" class="fontblacknormal" nowrap="nowrap">Enter App ID </td>
            <td  align="left" class="fontblacknormal" nowrap="nowrap">:</td>
            <td colspan="2"  align="left" nowrap="nowrap" class="fontblacknormal"><input name="site_fb_appid" type="text" id="site_fb_appid" value="<?=stripslashes($row['site_fb_appid'])?>" size="30" /></td>
            <td  align="left" class="fontblacknormal" nowrap="nowrap">&nbsp;</td>
            <td  align="left" class="fontblacknormal" nowrap="nowrap">&nbsp;</td>
            <td colspan="2"  align="left" nowrap="nowrap" class="fontblacknormal">&nbsp;</td>
          </tr>
          <tr>
            <td  align="right" class="fontblacknormal" nowrap="nowrap">Enter Secret Key </td>
            <td  align="left" class="fontblacknormal" nowrap="nowrap">:</td>
            <td colspan="2"  align="left" nowrap="nowrap" class="fontblacknormal"><input name="site_fb_secretkey" type="text" id="site_fb_secretkey" value="<?=stripslashes($row['site_fb_secretkey'])?>" size="30" /></td>
            <td  align="left" class="fontblacknormal" nowrap="nowrap">&nbsp;</td>
            <td  align="left" class="fontblacknormal" nowrap="nowrap">&nbsp;</td>
            <td colspan="2"  align="left" nowrap="nowrap" class="fontblacknormal">&nbsp;</td>
          </tr>
		  <!--FB login script ends here-->
		  <tr>  <td  align="left" class="fontblacknormal" nowrap="nowrap" colspan="8"><b>Scripts to be displayed in website</b></td>    </tr>
          <tr>
            <td  align="right" valign="top" nowrap="nowrap" class="fontblacknormal">Before the close of Body Tag in every page </td>
            <td  align="left" valign="top" nowrap="nowrap" class="fontblacknormal">:</td>
            <td colspan="6"  align="left" valign="top" nowrap="nowrap" class="fontblacknormal">
			<textarea name="site_footer_scripts" id="site_footer_scripts" cols="60" rows="15"><?php echo stripslashes($row['site_footer_scripts'])?></textarea></td>
          </tr>
          <tr>
            <td  align="right" valign="top" nowrap="nowrap" class="fontblacknormal">Order Success Message Page </td>
            <td  align="left" valign="top" nowrap="nowrap" class="fontblacknormal">:</td>
            <td colspan="6"  align="left" valign="top" nowrap="nowrap" class="fontblacknormal">
			<textarea name="site_checkout_scripts" id="site_checkout_scripts" cols="60" rows="15"><?php echo stripslashes($row['site_checkout_scripts'])?></textarea></td>
          </tr>
          <tr>
			      <td  align="left" class="fontblacknormal" nowrap="nowrap" colspan="8"><b>Search Engine  Settings</b>			      </td>
			      </tr>
			    <tr>
			      <td  align="left" nowrap="nowrap" class="redtext"><strong>Google</strong></td>
			      <td align="center">&nbsp;</td>
			      <td align="left">&nbsp;</td>
			      <td align="left">&nbsp;</td>
			      <td colspan="5" align="left">&nbsp;</td>
	      </tr>
			    <tr>
			      <td  align="right" class="fontblacknormal" nowrap="nowrap">Google Sitemap Verification Code </td>
			      <td align="center">:</td>
			      <td align="left"><input type="checkbox" name="is_meta_verificationcode" onclick="handle_googlesettings(this)" value="1" <? echo $row['is_meta_verificationcode']?"checked":''?> /></td>
			      <td align="left"><span class="tdcolorgraybg">
			        <input name="meta_verificationcode" type="text" id="meta_verificationcode" value="<?=stripslashes($row['meta_verificationcode'])?>" size="40" <?php echo $row['is_meta_verificationcode']?' class="normal_class"':'readOnly="true" class="disabled_class"';?> />
			      </span></td>
			      <td colspan="5" align="left">&nbsp;</td>
		      </tr>
			    <tr>
			      <td  align="right" class="fontblacknormal"><span class="tdcolorgraybg">Google Webtracker Code</span></td>
			      <td align="center">:</td>
			      <td align="left"><span class="tdcolorgraybg">
			        <input type="checkbox" name="is_google_webtracker_code" onclick="handle_googlesettings(this)" value="1" <? echo $row['is_google_webtracker_code']?"checked":''?> />
			      </span></td>
			      <td align="left"><span class="tdcolorgraybg">
			        <input name="google_webtracker_code" type="text" id="google_webtracker_code" value="<?=stripslashes($row['google_webtracker_code'])?>" size="40" <?php echo $row['is_google_webtracker_code']?' class="normal_class"':'readOnly="true" class="disabled_class"';?> />
			      </span></td>
			      <td colspan="5" align="left">&nbsp;</td>
		      </tr>
			    <tr>
			      <td  align="right" class="fontblacknormal">Google Urchin Webtracker Code</td>
			      <td align="center">:</td>
			      <td align="left"><span class="tdcolorgraybg">
			        <input type="checkbox" name="is_google_urchinwebtracker_code" onclick="handle_googlesettings(this)" value="1" <? echo $row['is_google_urchinwebtracker_code']?"checked":''?> />
			      </span></td>
			      <td align="left"><input name="google_webtracker_urchin_code" type="text" id="google_webtracker_urchin_code" value="<?=stripslashes($row['google_webtracker_urchin_code'])?>" size="40" <?php echo $row['is_google_urchinwebtracker_code']?' class="normal_class"':'readOnly="true" class="disabled_class"';?> /></td>
			      <td colspan="5" align="left">&nbsp;</td>
		      </tr>
		      <tr>
	            <td  align="right" class="fontblacknormal"><span class="tdcolorgraybg">Google Purchase Conversion</span></td>
	            <td align="center">:</td>
	            <td width="5%" align="left"><span class="tdcolorgraybg">
	              <input type="checkbox" name="is_google_adword_checkout" onclick="handle_googlesettings(this)" value="1" <? echo $row['is_google_adword_checkout']?"checked":''?> />
	            </span></td>
	            <td width="21%" align="left"><span class="tdcolorgraybg">
	              &nbsp;
	            </span></td>
	            <td colspan="5" align="left">&nbsp;</td>
	          </tr>
	          <tr>
	            <td  align="right" class="fontblacknormal" colspan="3">Conversion Id</td>
	            <td colspan="5" align="left"><input name="google_adword_conversion_id" type="text" id="google_adword_conversion_id" value="<?=stripslashes($row['google_adword_conversion_id'])?>" size="50" <?php echo $row['is_google_adword_checkout']?' class="normal_class"':'readOnly="true" class="disabled_class"';?>></td>
	          </tr>
	          <tr>
	            <td  align="right" class="fontblacknormal" colspan="3">Conversion Language</td>
	            <td colspan="5" align="left"><input name="google_adword_conversion_language" type="text" id="google_adword_conversion_language" value="<?=stripslashes($row['google_adword_conversion_language'])?>" size="50" <?php echo $row['is_google_adword_checkout']?' class="normal_class"':'readOnly="true" class="disabled_class"';?>></td>
	          </tr>
	          <tr>
	            <td  align="right" class="fontblacknormal" colspan="3">Conversion Format</td>
	            <td colspan="5" align="left"><input name="google_adword_conversion_format" type="text" id="google_adword_conversion_format" value="<?=stripslashes($row['google_adword_conversion_format'])?>" size="50" <?php echo $row['is_google_adword_checkout']?' class="normal_class"':'readOnly="true" class="disabled_class"';?>></td>
	          </tr>
	          <tr>
	            <td  align="right" class="fontblacknormal" colspan="3">Conversion Color</td>
	            <td colspan="5" align="left"><input name="google_adword_conversion_color" type="text" id="google_adword_conversion_color" value="<?=stripslashes($row['google_adword_conversion_color'])?>" size="50" <?php echo $row['is_google_adword_checkout']?' class="normal_class"':'readOnly="true" class="disabled_class"';?>></td>
	          </tr>
	          <tr>
	            <td  align="right" class="fontblacknormal" colspan="3">Conversion Label</td>
	            <td colspan="5" align="left"><input name="google_adword_conversion_label" type="text" id="google_adword_conversion_label" value="<?=stripslashes($row['google_adword_conversion_label'])?>" size="50" <?php echo $row['is_google_adword_checkout']?' class="normal_class"':'readOnly="true" class="disabled_class"';?>></td>
	          </tr>
              <tr>
                <td colspan="8" align="left" class="redtext"><strong>Yahoo</strong></td>
              </tr>
              <tr>
                <td align="right" class="fontblacknormal">Yahoo Meta Authentication Code </td>
                <td align="center">:</td>
                <td align="left"><input type="checkbox" name="is_yahoometa_verificationcode" onclick="handle_googlesettings(this)" value="1" <? echo $row['is_yahoometa_verificationcode']?"checked":''?> /></td>
                <td align="left">                  <input name="yahoo_meta_verificationcode" type="text" id="yahoo_meta_verificationcode" value="<?=stripslashes($row['yahoo_meta_verificationcode'])?>" size="40" <?php echo $row['is_yahoometa_verificationcode']?' class="normal_class"':'readOnly="true" class="disabled_class"';?> />                </td>
                <td colspan="4" align="left">&nbsp;</td>
              </tr>
          <tr>
            <td colspan="8" align="left" class="redtext"><strong>Msn</strong></td>
          </tr>
          <tr>
            <td align="right"><span class="fontblacknormal">MSN Meta Authentication Code</span></td>
            <td align="right">:</td>
            <td align="left"><input type="checkbox" name="is_msnmeta_verificationcode" onclick="handle_googlesettings(this)" value="1" <? echo $row['is_msnmeta_verificationcode']?"checked":''?> /></td>
            <td align="right">              <input name="msn_meta_verificationcode" type="text" id="msn_meta_verificationcode" value="<?=stripslashes($row['msn_meta_verificationcode'])?>" size="40" <?php echo $row['is_msnmeta_verificationcode']?' class="normal_class"':'readOnly="true" class="disabled_class"';?> />            </td>
            <td colspan="4" align="right">&nbsp;</td>
          </tr>
           <tr>
            <td colspan="8" align="left" class="redtext"><strong>Twitter</strong></td>
          </tr>
           <tr>
            <td align="right"><span class="fontblacknormal">Twitter Account Id</span></td>
            <td align="right">:</td>
            <td align="left"><input type="checkbox" name="is_twitter_account" onclick="handle_googlesettings(this)" value="1" <? echo $row['is_twitter_account']?"checked":''?> /></td>
            <td align="right"><input name="site_twitter_account_id" type="text" id="site_twitter_account_id" value="<?=stripslashes($row['site_twitter_account_id'])?>" size="40" <?php echo $row['is_twitter_account']?' class="normal_class"':'readOnly="true" class="disabled_class"';?> />            </td>
            <td colspan="4" align="right">&nbsp;</td>
          </tr>
          <tr>
            <td  align="left" class="fontblacknormal" nowrap="nowrap" colspan="8"><b>Default layout</b></td>
           </tr>
           <tr>
            <td  align="right" valign="top" nowrap="nowrap" class="fontblacknormal">Newsletter Products Default Layout </td>
            <td  align="left" valign="top" nowrap="nowrap" class="fontblacknormal">:</td>
            <td colspan="6"  align="left" valign="top" nowrap="nowrap" class="fontblacknormal"><textarea name="template_product_layout" id="template_product_layout" cols="60" rows="15"><?php echo stripslashes($row['template_product_layout'])?></textarea></td>
            </tr>
          <tr align="center">
            <td>&nbsp;</td>
            <td colspan="8" align="left">&nbsp;</td>
          </tr>
          <tr align="center">
            <td>&nbsp;</td>
            <td colspan="8" align="left">	<input type="hidden" name="site_id" id="site_id" value="<?=$_REQUEST['site_id']?>" />
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
            <td colspan="8" align="right">&nbsp;</td>
          </tr>
      </table></td>
    </tr>
  </table>
</form>
