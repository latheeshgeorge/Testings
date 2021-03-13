<?php
	/*#################################################################
	# Script Name 	: add_site_headers.php
	# Description 	: Page for adding Sitea header Images
	# Coded by 		: ANU
	# Created on	: 2-Aug-2007
	# Modified by	: Sny
	# Modified On	: 26-Nov-2007
	#################################################################*/
//#Define constants for this page

$page_type 	= 'Common Product Attachments';
$help_msg 	= get_help_messages('ADD_COMMON_PROD_ATTACH');

?>	
<script language="javascript" type="text/javascript">

function valform(frm)
{
	fieldRequired = Array('attach_title','attach_file');
	fieldDescription = Array('Attachment Title','Attachment File');
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
<form name='frmcommon_attachment' action='home.php?request=common_prod_attachment' enctype="multipart/form-data"  method="post" onsubmit="return valform(this);">
<input type="hidden" name="fpurpose" id="fpurpose" value="save_add" />
<input type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>" />
<input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />

  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=common_prod_attachment&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Common Product Attachments </a><span> Add Common Product Attachments</span></div></td>
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
               <td width="31%" align="left" class="tdcolorgray"><input name="attach_title" type="text" id="attach_title" value="<?php echo $_REQUEST['attach_title']?>" size="30" /></td>
               <td width="10%" align="left" class="tdcolorgray">Hide</td>
               <td width="50%" align="left" class="tdcolorgray"><input type="radio" name="attach_hide" value="1" <?php echo ($_REQUEST['attach_hide']==1)?'checked="checked"':''?> />
Yes
  <input name="attach_hide" type="radio" value="0" <?php echo ($_REQUEST['attach_hide']==0)?'checked="checked"':''?> />
No<a href="#" onmouseover ="ddrivetip('Use this section to hide this attachment.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
             </tr>
             <tr>
               <td align="left" class="tdcolorgray">Type </td>
               <td align="left" class="tdcolorgray">
			   <?php
			  		$attach_type = array('Audio'=>'Audio(mp3,wma)','Video'=>'Video(mpg,mpeg,wmv)','Pdf'=>'Pdf','Other'=>'Other');
					echo generateselectbox('attach_type',$attach_type,$_REQUEST['attach_type']);
				?>			   </td>
               <td align="left" class="tdcolorgray">&nbsp;</td>
               <td align="left" class="tdcolorgray">&nbsp;</td>
             </tr>
             <tr>
               <td align="left" class="tdcolorgray">File</td>
               <td align="left" class="tdcolorgray"><input name="attach_file" type="file" id="attach_file" /></td>
               <td align="left" class="tdcolorgray">&nbsp;</td>
               <td align="left" class="tdcolorgray">&nbsp;</td>
             </tr>
             <tr>
               <td align="left" class="tdcolorgray">&nbsp;</td>
               <td align="left" class="tdcolorgray">&nbsp;</td>
               <td align="left" class="tdcolorgray">&nbsp;</td>
               <td align="left" class="tdcolorgray">&nbsp;</td>
             </tr>
			 <tr>
			   <td colspan="4" align="center">&nbsp;</td>
		    </tr>
			 <tr>
			 <td colspan="4" align="center">			 			 </td>
			 </tr>
           </table>
		   </div>
		  </td>
        </tr>
	<tr>
		<td align="right" valign="middle">
			<div class="editarea_div">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
				  <td width="100%" align="center" valign="middle" class="tdcolorgray"><input name="prodattach_Submit" type="submit" class="red" value="Save" /></td>
				</tr>
				</table>
			</div>
		</td>
	</tr>
  </table>
</form>	  
