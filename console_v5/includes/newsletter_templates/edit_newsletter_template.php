<?php
	/*#################################################################
	# Script Name 	: add_newsletter_template.php
	# Description 	: Page for adding Product Store 
	# Coded by 		: SG
	# Created on	: 26-March-2008
	# Modified by	: SG
	# Modified On	: 
	#################################################################*/
#Define constants for this page
$page_type 	= 'Newsletter Templates';
$help_msg 	= get_help_messages('ADD_PRODUCT_STORE_SHORT');
if($edit_id)
{
 	$sql_shops = "SELECT newstemplate_id, newstemplate_name, newstemplate_template, newstemplate_hide, product_layout
	 							FROM newsletter_template 
										WHERE newstemplate_id=$edit_id AND sites_site_id=$ecom_siteid";
	$ret_shops = $db->query($sql_shops);
	if($db->num_rows($ret_shops)==0)  { echo " <font color='red'> You Are Not Authorised  </a>"; exit; } 
	if($db->num_rows($ret_shops))
	{
		$row_shops = $db->fetch_array($ret_shops);
	}
}
?>	
<script language="javascript" type="text/javascript">
function valforms(frm)
{
	var atleastone = false;
	fieldRequired = Array('newstemplate_name');
	fieldDescription = Array('Newsletter Template Name');
fieldEmail = Array('shop_email');
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	fieldSpecChars = Array('newstemplate_name');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric,fieldSpecChars))
	{
			/* Check whether dispay location is selected*/
			show_processing();
			return true;
	}
	else
	{
		return false;
	}
}
</script>
<form name='frmAddNewsletterTemplate' action='home.php?request=newsletter_templates' method="post" onsubmit="return valforms(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=newsletter_templates&search_newstemplate_name=<?php echo $_REQUEST['search_newstemplate_name']?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>">List Newsletter Template </a><span>  Edit Newsletter Template</span></div></td>
        </tr>
       <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="4">
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
          <td colspan="4" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
    </tr>
		 <?php
		 	}
		 ?> 
         <tr>
          <td colspan="4" align="center" valign="middle" class="tdcolorgray" >
		  <div class="editarea_div">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
          <td width="13%" align="left" valign="middle" class="tdcolorgray" >Template Name <span class="redtext">*</span> </td>
          <td width="87%" align="left" valign="middle" class="tdcolorgray"><input name="newstemplate_name" type="text" class="input" size="25" value="<?php echo $row_shops['newstemplate_name']?>"  maxlength="100"/></td>
          </tr>
		 <tr>
          <td width="13%" align="left" valign="middle" class="tdcolorgray" >Template Content <span class="redtext">*</span> </td>
          <td width="87%" align="left" valign="middle" class="tdcolorgray">
		  <?php
						$editor_elements = "newstemplate_template,product_layout_temp";
						include_once("js/tinymce.php");
						/*$editor 			= new FCKeditor('newstemplate_template') ;
						$editor->BasePath 	= '/console/js/FCKeditor/';
						$editor->Width 		= '650';
						$editor->Height 	= '300';
						$editor->ToolbarSet = 'BshopWithImages';
						$editor->Value 		= stripslashes($row_shops['newstemplate_template']);
						$editor->Create() ;*/
				       
				?>		  
				<textarea style="height:300px; width:650px" id="newstemplate_template" name="newstemplate_template"><?=stripslashes($row_shops['newstemplate_template'])?></textarea>
			  </td>
		  </tr>
		  
		   
		   <tr>
		     <td align="left" valign="middle" class="tdcolorgray">Product Layout Design </td>
		     <td align="left" valign="middle" class="tdcolorgray"><?php
						
						/*$editor 			= new FCKeditor('product_layout_temp') ;
						$editor->BasePath 	= '/console/js/FCKeditor/';
						$editor->Width 		= '650';
						$editor->Height 	= '300';
						$editor->ToolbarSet = 'BshopWithImages';
						$editor->Value 		= stripslashes($row_shops['product_layout']);
						$editor->Create() ;*/
				       
				?>
				<textarea style="height:300px; width:650px" id="product_layout_temp" name="product_layout_temp"><?=stripslashes($row_shops['product_layout'])?></textarea>
				</td>
    </tr>
		   <tr>
          <td width="13%" align="left" valign="middle" class="tdcolorgray">Hide Template </td>
          <td width="87%" align="left" valign="middle" class="tdcolorgray"><input type="radio" name="newstemplate_hide" value="1" <? if($row_shops['newstemplate_hide']==1) echo "checked";?> />
            Yes
              <input name="newstemplate_hide" type="radio" value="0" <? if($row_shops['newstemplate_hide']==0) echo "checked";?> />
          No<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_SHOP_BRAND_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		  </tr>
		  <tr>
		    <td colspan="2" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
		</tr>
		</table>
		</div>
		</td>
		</tr>
	
        <tr>
			<td align="left" valign="middle" class="tdcolorgray">
				<div class="editarea_div">
					<table width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td width="100%" align="right" valign="middle">
							<input type="hidden" name="storename" id="search_newstemplate_name" value="<?=$_REQUEST['search_newstemplate_name']?>" />
							<input type="hidden" name="edit_id" id="edit_id" value="<?=$edit_id?>" />
							<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
							<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
							<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
							<input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
							<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
							<input type="hidden" name="fpurpose" id="fpurpose" value="edit_store" />
							<input name="updatenewslettertemplate_Submit" type="submit" class="red" value="Save" />
						</td>
					</tr>
					</table>
				</div>
			</td>
		</tr>
  </table>
</form>	  

