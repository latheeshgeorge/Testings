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

$page_type 	= 'General Downloads';
$help_msg 	= get_help_messages('EDIT_GENERAL_DOWNLOAD_MESS1');
$download_id=($_REQUEST['download_id']?$_REQUEST['download_id']:$_REQUEST['checkbox'][0]);
$sql_edit = "SELECT download_id,download_title,download_desc,download_orgfilename,download_order,download_hide,download_internalfilename FROM general_site_downloads WHERE download_id=".$download_id;
$res_edit = $db->query($sql_edit);
$row_edit = $db->fetch_array($res_edit);
?>	
<script language="javascript" type="text/javascript">

function valform(frm)
{
	fieldRequired = Array('download_title');
	fieldDescription = Array('Download Title');
	fieldEmail = Array();add
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array('download_order');
if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		show_processing();
		return true;
	}
	 else {
		return false;
	}
}

</script>
<form name='frmAddSiteHeaders' action='home.php?request=general_downloads' enctype="multipart/form-data"  method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="5" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=general_downloads&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List General Downloads </a><span> Edit Download</span></div></td>
        </tr>
        <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="5">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>		 </td>
		</tr>
		<?php 
		if($alert)
		{			
		?>
        <tr>
          <td colspan="5" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
        </tr>
		<?
		}
		?>
		<tr>
          <td colspan="5" align="left" valign="top">
		<div class="editarea_div"> 
			<table cellpadding="0" cellpadding="0" width="100%">
			<tr>
          <td colspan="5" align="center" valign="middle">
		  <div class="editarea_url">
			<table cellpadding="0" cellspacing="0" width="100%">			
			  <tbody><tr>
			  <td class="tdcolorgray_url" align="left" valign="middle" width="5%">URL </td>
			  <td class="tdcolorgray_url" align="left" valign="middle" width="95%">:&nbsp;<?php echo "http://$ecom_hostname/images/$ecom_hostname/general_downloads/".$row_edit['download_internalfilename'];?></td>
			  </tr>
			</tbody></table>
		</div>
		  
		 </td>
        </tr>
        <tr>
          <td width="18%" align="left" valign="middle" class="tdcolorgray" >Download Title  <span class="redtext">*</span> </td>
          <td colspan="2" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="download_title" value="<?=stripslashes($row_edit['download_title'])?>"  />		  </td>
          <td width="15%" align="left" valign="middle" class="tdcolorgray">Select File <span class="redtext">*</span></td>
          <td width="44%" align="left" valign="middle" class="tdcolorgray"><input name="header_filename" type="file" id="header_filename" />
            <br>File Uploaded: <b><?=$row_edit['download_orgfilename']?></b> <br />(Uploading a New file will remove the existing file).</td>
        </tr>
		 <tr  >
		   <td align="left" valign="middle" class="tdcolorgray" >Download Desc </td>
		   <td colspan="2" align="left" valign="middle" class="tdcolorgray"><textarea name="download_desc" rows="7" cols="30"><?=stripslashes($row_edit['download_desc'])?></textarea></td>
		   <td align="left" valign="middle" class="tdcolorgray">Hidden </td>
		   <td align="left" valign="middle" class="tdcolorgray"><input type="radio" name="header_hide" value="1" <?php echo($row_edit['header_hide'])?"checked=\"checked\"":"";?> />
Yes
  <input type="radio" name="header_hide" value="0" <?php echo(!$row_edit['header_hide'])?"checked=\"checked\"":"";?> />
No&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_SITE_HEADERS_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
    </tr>	
	 <tr  >
		   <td align="left" valign="middle" class="tdcolorgray">Sort Order</td>
		   <td width="44%" colspan="4" align="left" valign="middle" class="tdcolorgray"><input type="text" name="download_order" size="4" value="<?=$row_edit['download_order']?>" /></td>
    </tr>
		 
	</table>
	</div>
	</td>
	</tr>
	
	<tr>
		<td colspan="5" align="right" valign="top">
			<div class="editarea_div">
				<table cellpadding="0" cellpadding="0" width="100%">
				<tr>
					<td align="right" valign="middle" class="tdcolorgray" >		  
						<input type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>" />
						<input type="hidden" name="download_id" id="download_id" value="<?=$download_id?>" />
						<input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
						<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
						<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
						<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
						<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
						<input type="hidden" name="fpurpose" id="fpurpose" value="update" />
						<input name="Submit" type="submit" class="red" value="Save" />
        			</td>
				</tr>
				</table>
			</div>
		</td>
	</tr>
  </table>
</form>	  
