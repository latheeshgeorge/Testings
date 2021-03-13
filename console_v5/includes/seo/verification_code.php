<?php
/*#################################################################
# Script Name 	: verification_code.php
# Description 	: Page for google settings
# Coded by 		: LHG
# Created on	: 12-Sep-2007
# Modified by	: Sny
# Modified on	: 17-Jun-2008
#################################################################*/

//#Define constants for this page
$page_type = 'Search Engine Settings';
$help_msg = get_help_messages('LIST_VERIF_CODE_MESS1');

$sql_meta = "SELECT is_meta_verificationcode,meta_verificationcode,is_google_urchinwebtracker_code,is_google_webtracker_code,
					is_google_adword_checkout,google_webtracker_urchin_code,google_webtracker_code,
					google_adword_conversion_id,google_adword_conversion_language,google_adword_conversion_format,
					google_adword_conversion_color,google_adword_conversion_label,is_yahoometa_verificationcode,
					yahoo_meta_verificationcode,is_msnmeta_verificationcode,msn_meta_verificationcode,is_twitter_account,
					site_twitter_account_id,google_ecomtracker_code, is_google_webtracker_ecom 
				FROM 
					sites 
				WHERE 
					site_id=$ecom_siteid";
$res_meta = $db->query($sql_meta);
$row_meta = $db->fetch_array($res_meta);

?>
<script language="javascript" type="text/javascript">
function valform(frm)
{
	fieldRequired = Array();
	fieldDescription = Array();
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
function handle_varstock(obj)
{
	
	if(obj.name=='is_meta_verificationcode')
	{
			if(obj.checked==true)
			{
				document.frm_google.meta_verificationcode.className='normal_class';
				document.frm_google.meta_verificationcode.readOnly  = false;
			}
			else if(obj.checked==false)
			{
				document.frm_google.meta_verificationcode.className='disabled_class';
				document.frm_google.meta_verificationcode.readOnly  = true;
			}	
	}
	if(obj.name=='is_msnmeta_verificationcode')
	{
			if(obj.checked==true)
			{
				document.frm_google.msn_meta_verificationcode.className='normal_class';
				document.frm_google.msn_meta_verificationcode.readOnly  = false;
			}
			else if(obj.checked==false)
			{
				document.frm_google.msn_meta_verificationcode.className='disabled_class';
				document.frm_google.msn_meta_verificationcode.readOnly  = true;
			}	
	}
	if(obj.name=='is_twitter_account')
	{
			if(obj.checked==true)
			{
				document.frm_google.site_twitter_account_id.className='normal_class';
				document.frm_google.site_twitter_account_id.readOnly  = false;
			}
			else if(obj.checked==false)
			{
				document.frm_google.site_twitter_account_id.className='disabled_class';
				document.frm_google.site_twitter_account_id.readOnly  = true;
			}	
	}
	if(obj.name=='is_yahoometa_verificationcode')
	{
			if(obj.checked==true)
			{
				document.frm_google.yahoo_meta_verificationcode.className='normal_class';
				document.frm_google.yahoo_meta_verificationcode.readOnly  = false;
			}
			else if(obj.checked==false)
			{
				document.frm_google.yahoo_meta_verificationcode.className='disabled_class';
				document.frm_google.yahoo_meta_verificationcode.readOnly  = true;
			}	
	}
	if(obj.name=='is_google_urchinwebtracker_code')
	{
			if(obj.checked==true)
			{
				document.frm_google.google_webtracker_urchin_code.className='normal_class';
				document.frm_google.google_webtracker_urchin_code.readOnly  = false;
			}
			else if(obj.checked==false)
			{
				document.frm_google.google_webtracker_urchin_code.className='disabled_class';
				document.frm_google.google_webtracker_urchin_code.readOnly  = true;
			}	
	}
	else if(obj.name=='is_google_webtracker_code')
	{
		if(obj.checked==true)
			{
			
				document.frm_google.google_webtracker_code.className='normal_class';
				document.frm_google.google_webtracker_code.readOnly  = false;
			}
			else if(obj.checked==false)
			{
				document.frm_google.google_webtracker_code.className='disabled_class';
				document.frm_google.google_webtracker_code.readOnly  = true;
			}
	}
	else if(obj.name=='is_google_webtracker_ecom')
	{
		if(obj.checked==true)
			{
			
				document.frm_google.google_ecomtracker_code.className='normal_class';
				document.frm_google.google_ecomtracker_code.readOnly  = false;
			}
			else if(obj.checked==false)
			{
				document.frm_google.google_ecomtracker_code.className='disabled_class';
				document.frm_google.google_ecomtracker_code.readOnly  = true;
			}
	}
	else if(obj.name=='is_google_adword_checkout')
	{
		if(obj.checked==true)
			{
			
				document.frm_google.google_adword_conversion_id.className			='normal_class';
				document.frm_google.google_adword_conversion_language.className		='normal_class';
				document.frm_google.google_adword_conversion_format.className		='normal_class';
				document.frm_google.google_adword_conversion_color.className		='normal_class';
				document.frm_google.google_adword_conversion_label.className		='normal_class';
				document.frm_google.google_adword_conversion_id.readOnly  			= false;
				document.frm_google.google_adword_conversion_language.readOnly  	= false;
				document.frm_google.google_adword_conversion_format.readOnly  		= false;
				document.frm_google.google_adword_conversion_color.readOnly  		= false;
				document.frm_google.google_adword_conversion_label.readOnly  		= false;
			}
			else if(obj.checked==false)
			{
				document.frm_google.google_adword_conversion_id.className			='disabled_class';
				document.frm_google.google_adword_conversion_language.className		='disabled_class';
				document.frm_google.google_adword_conversion_format.className		='disabled_class';
				document.frm_google.google_adword_conversion_color.className		='disabled_class';
				document.frm_google.google_adword_conversion_label.className		='disabled_class';
				document.frm_google.google_adword_conversion_id.readOnly  			= true;
				document.frm_google.google_adword_conversion_language.readOnly  	= true;
				document.frm_google.google_adword_conversion_format.readOnly 		= true;
				document.frm_google.google_adword_conversion_color.readOnly  		= true;
				document.frm_google.google_adword_conversion_label.readOnly			= true;
			}
	}	
}	
</script>
<form name='frm_google' action='home.php?request=seo_keyword&fpurpose=verification_code' method="post" onsubmit="return valform(this);">

<table width="100%" border="0" cellpadding="0" cellspacing="1">
      <tr>
		<td colspan="<?=$colspan?>">
		<table border="0" cellpadding="2" cellspacing="0" width="100%" align="left">
		  <tr> 
			<td class="treemenutd" align="left"><div class="treemenutd_div"><span> <?=$page_type?> 
			  <img src="images/blueline.gif" alt="" border="0" height="1" width="400"></span></div></td>
		  </tr>
		</table>
		</td>
	</tr>
      <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
	  <tr>
        <td align="center" class="maininnertabletd2">
		<div class="listingarea_div">
			<table width="100%"  border="0" cellpadding="2" cellspacing="0" class="">
				<tr align="left">
				  <td colspan="3" class="tdcolorgraybg"><div align="left"></div></td>
				</tr>
				
				<tr>
				  <td colspan="3" align="left" class="seperationtd" >Google</td>
			  </tr>
				<tr>
				  <td width="33%" align="right" class="tdcolorgraybg" >Google Meta Verification Code</td>
				  <td width="7%" align="center" class="tdcolorgraybg"><input type="checkbox" name="is_meta_verificationcode" onclick="handle_varstock(this)" value="1" <? echo $row_meta['is_meta_verificationcode']?"checked":''?> /></td>
				  <td width="60%" align="left" class="tdcolorgraybg" ><input name="meta_verificationcode" type="text" id="meta_verificationcode" value="<?=stripslashes($row_meta['meta_verificationcode'])?>" size="50" <?php echo $row_meta['is_meta_verificationcode']?' class="normal_class"':'readOnly="true" class="disabled_class"';?> >	[ Use comma (,) 	to	seperate	multiple	codes ]			  </td>
				</tr>
				<tr>
				  <td width="33%" align="right" class="tdcolorgraybg">Google Analytics Code</td>
				  <td width="7%" align="center" class="tdcolorgraybg"><input type="checkbox" name="is_google_webtracker_code" onclick="handle_varstock(this)" value="1" <? echo $row_meta['is_google_webtracker_code']?"checked":''?> /></td>
				  <td width="60%" align="left" class="tdcolorgraybg"><input name="google_webtracker_code" type="text" id="google_webtracker_code" value="<?=stripslashes($row_meta['google_webtracker_code'])?>" size="50" <?php echo $row_meta['is_google_webtracker_code']?' class="normal_class"':'readOnly="true" class="disabled_class"';?>   > [ Use comma (,) 	to	seperate	multiple	codes ] 
				    </td>
				</tr>
				<tr>
				  <td width="33%" align="right" class="tdcolorgraybg">Google Analytics Ecommerce Tracking Code</td>
				  <td width="7%" align="center" class="tdcolorgraybg"><input type="checkbox" name="is_google_webtracker_ecom" onclick="handle_varstock(this)" value="1" <? echo $row_meta['is_google_webtracker_ecom']?"checked":''?> /></td>
				  <td width="60%" align="left" class="tdcolorgraybg"><input name="google_ecomtracker_code" type="text" id="google_ecomtracker_code" value="<?=stripslashes($row_meta['google_ecomtracker_code'])?>" size="50" <?php echo $row_meta['is_google_webtracker_ecom']?' class="normal_class"':'readOnly="true" class="disabled_class"';?>   >  [ Use comma (,) 	to	seperate	multiple	codes ]
				    </td>
				</tr>
				<tr>
				  <td align="right" class="tdcolorgraybg" >Google Urchin  Code</td>
				  <td width="7%" align="center" class="tdcolorgraybg"><input type="checkbox" name="is_google_urchinwebtracker_code" onclick="handle_varstock(this)" value="1" <? echo $row_meta['is_google_urchinwebtracker_code']?"checked":''?> /></td>
				  <td width="60%" align="left" class="tdcolorgraybg"><input name="google_webtracker_urchin_code" type="text" id="google_webtracker_urchin_code" value="<?=stripslashes($row_meta['google_webtracker_urchin_code'])?>" size="50" <?php echo $row_meta['is_google_urchinwebtracker_code']?' class="normal_class"':'readOnly="true" class="disabled_class"';?>  ></td>
				</tr>
				<tr align="left">
				  <td colspan="3" class="tdcolorgraybg"><div align="left"></div></td>
				</tr>
				<tr >
				  <td width="33%" align="right" class="tdcolorgraybg">Google Purchase Conversion</td>
				  <td width="7%" align="center" class="tdcolorgraybg"><input type="checkbox" name="is_google_adword_checkout" onclick="handle_varstock(this)" value="1" <? echo $row_meta['is_google_adword_checkout']?"checked":''?> /></td>
				  <td width="60%" align="left" class="tdcolorgraybg">&nbsp;</td>
				</tr>
				<tr >
				  <td align="right" class="tdcolorgraybg" colspan="2">Conversion Id</td>
				  <td width="60%" align="left" class="tdcolorgraybg"><input name="google_adword_conversion_id" type="text" id="google_adword_conversion_id" value="<?=stripslashes($row_meta['google_adword_conversion_id'])?>" size="50" <?php echo $row_meta['is_google_adword_checkout']?' class="normal_class"':'readOnly="true" class="disabled_class"';?>></td>
				</tr>
				<tr >
				  <td align="right" class="tdcolorgraybg" colspan="2">Conversion Language</td>
				  <td width="60%" align="left" class="tdcolorgraybg"><input name="google_adword_conversion_language" type="text" id="google_adword_conversion_language" value="<?=stripslashes($row_meta['google_adword_conversion_language'])?>" size="50" <?php echo $row_meta['is_google_adword_checkout']?' class="normal_class"':'readOnly="true" class="disabled_class"';?>></td>
				</tr>
				<tr >
				  <td align="right" class="tdcolorgraybg" colspan="2">Conversion Format</td>
				  <td width="60%" align="left" class="tdcolorgraybg"><input name="google_adword_conversion_format" type="text" id="google_adword_conversion_format" value="<?=stripslashes($row_meta['google_adword_conversion_format'])?>" size="50" <?php echo $row_meta['is_google_adword_checkout']?' class="normal_class"':'readOnly="true" class="disabled_class"';?>></td>
				</tr>
				<tr >
				    <td align="right" class="tdcolorgraybg" colspan="2">Conversion Color</td>
				  <td width="60%" align="left" class="tdcolorgraybg"><input name="google_adword_conversion_color" type="text" id="google_adword_conversion_color" value="<?=stripslashes($row_meta['google_adword_conversion_color'])?>" size="50" <?php echo $row_meta['is_google_adword_checkout']?' class="normal_class"':'readOnly="true" class="disabled_class"';?>></td>
				</tr>
				<tr >
				  <td align="right" class="tdcolorgraybg" colspan="2">Conversion Label</td>
				  <td width="60%" align="left" class="tdcolorgraybg"><input name="google_adword_conversion_label" type="text" id="google_adword_conversion_label" value="<?=stripslashes($row_meta['google_adword_conversion_label'])?>" size="50" <?php echo $row_meta['is_google_adword_checkout']?' class="normal_class"':'readOnly="true" class="disabled_class"';?>></td>
				</tr>
				<tr>
				  <td colspan="3" align="left" class="seperationtd">Yahoo</td>
			  </tr>
				<tr>
				  <td align="right" class="tdcolorgraybg">Yahoo Meta Authentication Code </td>
			      <td align="center" class="tdcolorgraybg"><input type="checkbox" name="is_yahoometa_verificationcode" onclick="handle_varstock(this)" value="1" <? echo $row_meta['is_yahoometa_verificationcode']?"checked":''?> /></td>
			      <td align="left" class="tdcolorgraybg"><input name="yahoo_meta_verificationcode" type="text" id="yahoo_meta_verificationcode" value="<?=stripslashes($row_meta['yahoo_meta_verificationcode'])?>" size="50" <?php echo $row_meta['is_yahoometa_verificationcode']?' class="normal_class"':'readOnly="true" class="disabled_class"';?>>
		          [ Use comma (,) 	to	seperate	multiple	codes ] </td>
			  </tr>
			  <tr>
				  <td colspan="3" align="left" class="seperationtd">MSN</td>
			  </tr>
				<tr>
				  <td align="right" class="tdcolorgraybg">MSN Meta Authentication Code </td>
			      <td align="center" class="tdcolorgraybg"><input type="checkbox" name="is_msnmeta_verificationcode" onclick="handle_varstock(this)" value="1" <? echo $row_meta['is_msnmeta_verificationcode']?"checked":''?> /></td>
			      <td align="left" class="tdcolorgraybg"><input name="msn_meta_verificationcode" type="text" id="msn_meta_verificationcode" value="<?=stripslashes($row_meta['msn_meta_verificationcode'])?>" size="50" <?php echo $row_meta['is_msnmeta_verificationcode']?' class="normal_class"':'readOnly="true" class="disabled_class"';?>>
		          [ Use comma (,) 	to	seperate	multiple	codes ] </td>
			  </tr>
			   <tr>
				  <td colspan="3" align="left" class="seperationtd">Twitter</td>
			  </tr>
				<tr>
				  <td align="right" class="tdcolorgraybg">Twitter Account Id</td>
			      <td align="center" class="tdcolorgraybg"><input type="checkbox" name="is_twitter_account" onclick="handle_varstock(this)" value="1" <? echo $row_meta['is_twitter_account']?"checked":''?> /></td>
			      <td align="left" class="tdcolorgraybg"><input name="site_twitter_account_id" type="text" id="site_twitter_account_id" value="<?=stripslashes($row_meta['site_twitter_account_id'])?>" size="50" <?php echo $row_meta['is_twitter_account']?' class="normal_class"':'readOnly="true" class="disabled_class"';?>>
		          </td>
			  </tr>
				
			</table>
			</div>
		</td>
      </tr>
	  <tr>
        <td align="center" class="maininnertabletd2">
		<div class="listingarea_div">
			<table width="100%"  border="0" cellpadding="2" cellspacing="0" class="">
				<tr>
					<td width="100%" align="right" valign="middle">
					<input type="hidden" name="fpurpose" id="fpurpose" value="verification_code_saved" />
					<input type="Submit" name="Submit" id="Submit" value="Save" class="red">
					</td>
				</tr>
			</table>
		</div>
		</td>
	</tr>
    </table>
</form>
