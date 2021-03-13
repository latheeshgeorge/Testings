<?php
	/*
	#################################################################
	# Script Name 	: list_seo.php
	# Description 	: Page for managing Seo 
	# Coded by 		: LSH
	# Created on	: 09-Oct-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################
	*/

//#Define constants for this page
include("classes/fckeditor.php");
$cbo_sites = $_REQUEST['cbo_sites'];
$table_name='se_meta_description';
$page_type = 'SEO Meta Description';
$help_msg = 'This section helps in editing the meta descriptions for the site';
$sql = "SELECT * FROM se_meta_description WHERE sites_site_id=$cbo_sites";
$res = $db->query($sql);
if($db->num_rows($res))
{
	$row = $db->fetch_array($res);
}	
?>	
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="<?=$colspan?>" valign="top">
		<table border="0" cellpadding="0" cellspacing="0" width="100%" align="left">
		  <tr> 
			<td class="menutabletoptd">&nbsp;<b><a href="home.php?request=seo&cbo_sites=<?=$cbo_sites?>">Manage SEO</a><font size="1">>></font> 
			  List <?=$page_type?> 
			  </b><br /><img src="images/blueline.gif" alt="" border="0" height="1" width="400"></td>
		  </tr>
		</table><br />		</td>
	</tr>
      <tr>
        <td colspan="<?=$colspan?>" class="maininnertabletd3">
		<?=$help_msg?>		</td>
      </tr>
	  <?php
	  	if($error_msg)
		{
	  ?>
		  <tr>
			<td colspan="<?=$colspan?>" align="center" class="error_msg"><?php echo $error_msg?></td>
		  </tr>
	  <?php
	  	}
	  ?>
	  <!-- Search Section Starts here -->
	    <tr class="maininnertabletd1">
		<td></td>
		</tr>
	     <tr><td>
<form name='frmEditLetterTemplates' action='home.php?request=seo' method="post" onsubmit="return valform(this);">
<input type="hidden" name="cbo_sites" value="<?=$cbo_sites?>" />

  <table width="100%" border="0" cellspacing="0" cellpadding="0" >
      
        
        <tr>
          <td width="32%" align="center" valign="middle" class="tdcolorgraybg" ><div align="left">Home Page Meta Tag Template</div></td>
          <td colspan="2"  align="left" valign="middle" class="tdcolorgraybg"> <textarea name="txt_homemeta" rows="3" cols="60"><?php echo stripslashes($row['home_meta']);?></textarea></td>
        </tr>
         <tr>
           <td align="center" valign="top" class="tdcolorgraybg"   ><div align="left">Static Page Meta Tag Template</div></td>
           <td colspan="2" align="left" valign="middle" class="tdcolorgraybg"> <textarea name="txt_staticmeta" rows="2" cols="60"><?php echo stripslashes($row['static_meta']);?></textarea></td>
         </tr>
         <tr>
           <td align="center" valign="top" class="tdcolorgraybg"   ><div align="left">Product Page Meta Tag Template </div></td>
           <td colspan="2" align="left" valign="middle" class="tdcolorgraybg"><textarea name="txt_productmeta" rows="2" cols="60"><?php echo stripslashes($row['product_meta']);?></textarea></td>
         </tr>
         <tr>
           <td align="center" valign="top" class="tdcolorgraybg"   ><div align="left">Category Page Meta Tag Template</div></td>
           <td colspan="2" align="left" valign="middle" class="tdcolorgraybg"> <textarea name="txt_categorymeta" rows="2" cols="60"><?php echo stripslashes($row['category_meta']);?></textarea></td>
         </tr>
         <tr>
           <td align="center" valign="top" class="tdcolorgraybg"><div align="left">Search Page Meta Tag Template </div></td>
           <td colspan="2" align="left" valign="middle" class="tdcolorgraybg"> <textarea name="txt_searchmeta" rows="2" cols="60"><?php echo stripslashes($row['search_meta']);?></textarea></td>
         </tr>
         <tr>
           <td align="center" valign="top" class="tdcolorgraybg"   ><div align="left">Search Page Top Content </div></td>
           <td colspan="2" align="left" valign="middle" class="tdcolorgraybg"> <textarea name="txt_searchcontent" rows="2" cols="60"><?php echo stripslashes($row['search_content']);?></textarea></td>
         </tr>
          <tr>
           <td align="center" valign="top" class="tdcolorgraybg"><div align="left">Other Page Meta Tag Template </div></td>
           <td colspan="2" align="left" valign="middle" class="tdcolorgraybg"> <textarea name="txt_othermeta" rows="3" cols="60"><?php echo stripslashes($row['other_meta']);?></textarea></td>
         </tr>
         <tr>
           <td align="left" valign="top" class="tdcolorgraybg"   ><div align="left"></div></td>
           <td colspan="2" align="left" valign="middle" class="tdcolorgraybg">&nbsp;</td>
         </tr>
         <tr>
           <td align="left" valign="top" class="tdcolorgraybg"   ><div align="left"></div></td>
           <td width="53%" align="left" valign="top" class="tdcolorgraybg"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="innersmalltable">
             <tr>
               <td width="37%" align="right" class="tdcolorgraybg"><strong class="fontredheading">Keyword</strong></td>
               <td width="9%" class="tdcolorgraybg">&nbsp;</td>
               <td width="54%" align="left" class="tdcolorgraybg"><strong class="fontredheading">Description</strong> </td>
             </tr>
             <tr>
               <td align="right">&nbsp;</td>
               <td align="center">&nbsp;</td>
               <td align="left">&nbsp;</td>
             </tr>
             
             <tr>
               <td align="right">[title]</td>
               <td align="center">=></td>
               <td align="left">Site Title</td>
             </tr>
          
             <tr>
               <td align="right">[keywords]</td>
               <td align="center">=&gt;</td>
               <td>Keywords for home page </td>
             </tr>
             <tr>
               <td align="right">[first_keyword]</td>
               <td align="center">=&gt;</td>
               <td>Show First Keyword</td>
             </tr>
			 <tr>
               <td align="right"></td>
               <td align="center"></td>
               <td></td>
             </tr>
           </table>		   </td>
           <td width="25%" align="left" valign="middle" class="tdcolorgraybg">&nbsp;</td>
         </tr>
		 
					
        <tr>
          <td colspan="3" align="center" valign="middle" class="tdcolorgraybg">
		<input type="hidden" name="fpurpose" id="fpurpose" value="Save_SiteDesc" />
		  <input name="Submit" type="submit" class="red" value="Update" /></td>
        </tr>
  </table>

</form>	  

</td>
      </tr>
	  <!-- Search Section Ends here -->
	<?php
		?>
    <tr  class="<?=$class_val;?>">
      <td height="20" align="center">
        &nbsp;&nbsp;&nbsp;</td>
      </tr>
   
  <?php 
  $query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=themes";
  ?>
   </table>
  

	

