<?php
	/*#################################################################
	# Script Name 	: add_common_product_tabs.php
	# Description 	: Page for adding common product tabs
	# Coded by 		: Sny
	# Created on	: 13-Aug-2010
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
//#Define constants for this page

$page_type 	= 'Common Product Tabs';
$help_msg 	= get_help_messages('ADD_COMMON_PROD_TAB');

?>	
<script language="javascript" type="text/javascript">

function valform(frm)
{
	fieldRequired = Array('tab_title');
	fieldDescription = Array('Tab Title');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric))
	{
		show_processing();
		return true;
	}
	else
	{
		return false;
	}
}
</script>
<form name='frmcommon_attachment' action='home.php?request=common_prod_tab' enctype="multipart/form-data"  method="post" onsubmit="return valform(this);">
<input type="hidden" name="fpurpose" id="fpurpose" value="save_add" />
<input type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>" />
<input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />

  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=common_prod_tab&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Common Product Tabs </a><span> Add Common Product Tabs</span></div></td>
        </tr>
        <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>		 </td>
		</tr>
		<?php 
		if($alert)
		{			
		?>
        <tr>
          <td align="center" valign="middle" class="errormsg" ><?=$alert?></td>
        </tr>
		<?
		}
		?>
        <tr>
          <td align="center">
		  <div class="editarea_div">
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
             <tr>
               <td width="9%" align="left" class="tdcolorgray">Title <span class="redtext"> *</span></td>
               <td width="31%" align="left" class="tdcolorgray"><input name="tab_title" type="text" id="tab_title" value="<?php echo $_REQUEST['tab_title']?>" size="30" /></td>
               <td width="10%" align="left" class="tdcolorgray">Hide</td>
               <td width="50%" align="left" class="tdcolorgray"><input type="radio" name="tab_hide" value="1" <?php echo ($_REQUEST['tab_hide']==1)?'checked="checked"':''?> />
Yes
  <input name="tab_hide" type="radio" value="0" <?php echo ($_REQUEST['tab_hide']==0)?'checked="checked"':''?> />
No<a href="#" onmouseover ="ddrivetip('Use this section to hide this tab.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
             </tr>
             <tr>
               <td align="left" class="tdcolorgray" valign="top">Description</td>
               <td align="left" class="tdcolorgray" colspan="3">
			    <?php
						$editor_elements = "tab_content";
						include_once(ORG_DOCROOT."/console/js/tinymce.php");
				?>			   
				<textarea style="height:300px; width:650px" id="tab_content" name="tab_content"><?=stripslashes($_REQUEST['tab_content'])?></textarea>
			   </td>
             </tr>
			 
           </table>
		   </div>
		  </td>
        </tr>
		
		<tr>
			<td align="center">
				<div class="editarea_div">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td valign="middle" align="right" class="tdcolorgray"><input name="prodtab_Submit" type="submit" class="red" value="Save" />	</td>
					</tr>
					</table>
				</div>
			</td>
		</tr>
		
  </table>
</form>	  
