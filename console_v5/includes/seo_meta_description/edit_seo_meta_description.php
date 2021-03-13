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
$table_name='se_meta_description';
$page_type = 'SEO Meta Description';
$help_msg = get_help_messages('LIST_SITE_METADESC_MESS1');
$sql = "SELECT * FROM se_meta_description WHERE sites_site_id=$ecom_siteid";
$res = $db->query($sql);
if($db->num_rows($res))
{
	$row = $db->fetch_array($res);
}	
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
</script>
<form name='frmEditLetterTemplates' action='home.php?request=seo_meta_description' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" >
        <tr>
          <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span> Edit Seo Meta Description Templates</span></div></td>
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
          <td colspan="3" align="center" valign="middle" >
		   <div class="editarea_div">
		  	<table width="100%"  border="0" cellpadding="0" cellspacing="0" >
        <tr>
          <td width="22%" align="left" valign="middle" class="tdcolorgraybg" >Home Page Meta Tag Template</td>
          <td  align="left" valign="middle" class="tdcolorgraybg"> <textarea name="txt_homemeta" rows="3" cols="55"><?php echo stripslashes($row['home_meta']);?></textarea></td>
          <td rowspan="7"  align="left" valign="top" class="tdcolorgraybg"><table width="100%" border="0" cellpadding="1" cellspacing="1" class="innersmalltable">
            <tr>
              <td colspan="3" align="left" class="maininnertabletd1">Keywords that can be used in the meta description templates </td>
            </tr>
            <tr>
              <td width="33%" align="right" class="listingtableheader"><a href="#" onmouseover ="ddrivetip('If keywords are specified in the meta tag, they will be replaced with their respective values while displaying it.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png"  border="0" /></a>Keyword</td>
              <td width="1%" class="listingtableheader">&nbsp;</td>
              <td width="66%" align="left" class="listingtableheader">Description </td>
            </tr>
            <tr>
              <td align="right">&nbsp;</td>
              <td align="center">&nbsp;</td>
              <td align="left">&nbsp;</td>
            </tr>
            <tr>
              <td align="right">[title]</td>
              <td align="center">&nbsp;</td>
              <td align="left">=&gt; Site Title</td>
            </tr>
            <tr>
              <td align="right">[keywords]</td>
              <td align="center">&nbsp;</td>
              <td>=&gt; Keywords for the page </td>
            </tr>
            <tr>
              <td align="right">[first_keyword]</td>
              <td align="center">&nbsp;</td>
              <td>=&gt; Show First Keyword</td>
            </tr>
            <tr>
              <td colspan="3" align="right">&nbsp;</td>
            </tr>
          </table></td>
        </tr>
         <tr>
           <td align="left" valign="top" class="tdcolorgraybg"   >Static Page Meta Tag Template</td>
           <td align="left" valign="middle" class="tdcolorgraybg"> <textarea name="txt_staticmeta" rows="3" cols="55"><?php echo stripslashes($row['static_meta']);?></textarea></td>
         </tr>
         <tr>
           <td align="left" valign="top" class="tdcolorgraybg"   >Product Page Meta Tag Template </td>
           <td align="left" valign="middle" class="tdcolorgraybg"><textarea name="txt_productmeta" rows="3" cols="55"><?php echo stripslashes($row['product_meta']);?></textarea></td>
         </tr>
         <tr>
           <td align="left" valign="top" class="tdcolorgraybg"   >Category Page Meta Tag Template</td>
           <td align="left" valign="middle" class="tdcolorgraybg"> <textarea name="txt_categorymeta" rows="3" cols="55"><?php echo stripslashes($row['category_meta']);?></textarea></td>
         </tr>
         <tr>
           <td align="left" valign="top" class="tdcolorgraybg">Search Page Meta Tag Template </td>
           <td align="left" valign="middle" class="tdcolorgraybg"> <textarea name="txt_searchmeta" rows="3" cols="55"><?php echo stripslashes($row['search_meta']);?></textarea></td>
         </tr>
         <tr>
           <td align="left" valign="top" class="tdcolorgraybg">Other Page Meta Tag Template </td>
           <td align="left" valign="middle" class="tdcolorgraybg"> <textarea name="txt_othermeta" rows="3" cols="55"><?php echo stripslashes($row['other_meta']);?></textarea></td>
         </tr>
         <tr>
           <td align="left" valign="top" class="tdcolorgraybg"   >Search Page Top Content Template </td>
           <td align="left" valign="middle" class="tdcolorgraybg"> <textarea name="txt_searchcontent" rows="3" cols="55"><?php echo stripslashes($row['search_content']);?></textarea></td>
         </tr>
         
         <tr>
           <td align="left" valign="top" class="tdcolorgraybg"   >&nbsp;</td>
           <td align="left" valign="middle" class="tdcolorgraybg">&nbsp;</td>
         </tr>
         <tr>
           <td align="left" valign="top" class="tdcolorgraybg"   >&nbsp;</td>
           <td width="36%" align="left" valign="top" class="tdcolorgraybg">&nbsp;</td>
           <td width="42%" align="left" valign="middle" class="tdcolorgraybg">&nbsp;</td>
         </tr>
		 </table>
		 </div>
		 </td>
		 </tr>
					
		<tr>		
			<td colspan="3" align="center" valign="middle" class="tdcolorgraybg">
				<div class="editarea_div">
					<table width="100%"  border="0" cellpadding="2" cellspacing="0" class="">
					<tr>
						<td width="100%" align="right" valign="middle">
							<input type="hidden" name="fpurpose" id="fpurpose" value="Save_SiteDesc" />
							<input name="Submit" type="submit" class="red" value="Save Templates" />
						</td>
					</tr>
					</table>
				</div>
			</td>
		</tr>
  </table>

</form>	  

