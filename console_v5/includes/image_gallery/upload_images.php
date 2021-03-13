<?php
	/*#################################################################
	# Script Name 	: upload_images.php
	# Description 	: Page for uploading images to gallery
	# Coded by 		: Sny
	# Created on	: 16-Jul-2007
	# Modified by	: Sny
	# Modified On	: 23-Jul-2007
	#################################################################*/
#Define constants for this page
$page_type = 'Upload Images';
$help_msg = get_help_messages('IMG_GAL_UPIMG_MAIN');
?>	
<script language="javascript" type="text/javascript">
	function validate_fileupload()
	{
		var atleastone = false;
		for(i=0;i<document.frmImageUpload.elements.length;i++)
		{
			if(document.frmImageUpload.elements[i].type=='file' && document.frmImageUpload.elements[i].name.substr(0,11)=='upload_big_')
			{
				if(document.frmImageUpload.elements[i].value!='')
					atleastone = true;
			}	
		}
		if (atleastone==false)
		{
			alert('Please select atleast one Big Image for uploading');	
			return false;
		}
		else
		{
			show_processing();
			return true;
		}
	}
	function handle_thumb_option()
	{
		if(document.frmImageUpload.chk_thumb.checked)
			dis_val = true;
		else
			dis_val = false;
		for(i=0;i<document.frmImageUpload.elements.length;i++)
		{
			if (document.frmImageUpload.elements[i].name.substr(0,13)=='upload_thumb_')
			{
				document.frmImageUpload.elements[i].disabled = dis_val;
			}
		}
	}
</script>
<?php
	if(!$_REQUEST['curdir_id'])
		$curdir_id = 0;
	else
		$curdir_id = $_REQUEST['curdir_id'];	
  	$sql_dir = "SELECT directory_name FROM images_directory WHERE directory_id=$curdir_id LIMIT 1";
	$ret_dir = $db->query($sql_dir);
	if ($db->num_rows($ret_dir))
	{
		$row_dir = $db->fetch_array($ret_dir);
		$disp_dir_name = stripslashes($row_dir['directory_name']);
		
	}
	if($curdir_id==0)
		$disp_dir_name =  "Root";
  ?>

<form name="frmImageUpload" method="post" enctype="multipart/form-data" action="home.php?request=img_gal" onSubmit="return validate_fileupload()">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
  <td align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=img_gal&txt_searchcaption=<?php echo $_REQUEST['txt_searchcaption']?>&search_option=<?php echo $_REQUEST['search_option']?>&records_per_page=<?php echo $_REQUEST['records_per_page']?>&pg=<?php echo $_REQUEST['pg']?>&curdir_id=<?php echo $_REQUEST['curdir_id']?>&sel_prods=<?php echo $_REQUEST['sel_prods']?>&src_page=<?php echo $_REQUEST['src_page']?>&src_id=<?php echo $_REQUEST['src_id']?>&productname=<?php echo $_REQUEST['sel_prods']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&curtab=<?php echo $_REQUEST['curtab']?>">Image Gallery</a> <span>Upload Images to directory: "<?php echo $disp_dir_name?>"</span></div></td>
</tr>
<tr>
  <td  align="left" valign="middle" class="helpmsgtd_main">
  <?php 
	  Display_Main_Help_msg($help_arr,$help_msg);
  ?>
 </td>
</tr>
<?php
	if ($alert)
	{
?>
		<tr>
		  <td align="center" valign="middle" class="errormsg" ><?php echo $alert?></td>
		</tr>
<?php
	}
?>
<tr>
  <td align="right" valign="middle" class="listingtablestyleA1"><input type="hidden" name="chk_thumb" id="chk_thumb" value="1" checked="checked" onclick="handle_thumb_option()"><?php /*?>&nbsp;<span class="redtext">Disable Small Image Upload (recommended to keep this ticked)</span><?php */?></td>
</tr>
<tr>
  <td align="left" valign="top">
  <div class="editarea_div">
  	<table width="100%" border="0" cellspacing="1" cellpadding="1">
    <tr>
	<?php
		$max_cols 	= 2;
		$cur_col	= 0;
		for($i=0;$i<10;$i++)
		{
	?>
      <td align="righ" class="imageoptionscolorA">
	  <table width="100%" border="0" cellspacing="1" cellpadding="1">
        <tr>
          <td width="14%">Caption</td>
          <td width="1%" align="center">:</td>
          <td width="85%"><input type="text" name="upload_caption_<?php echo $i?>"></td>
        </tr>
        <tr>
          <td>Select Image </td>
          <td align="center">:</td>
          <td><input type="file" name="upload_big_<?php echo $i?>"></td>
        </tr>
       <?php /*?> <tr>
          <td>Small Image </td>
          <td align="center">:</td>
          <td><input type="file" name="upload_thumb_<?php echo $i?>" disabled></td>
        </tr><?php */?>
      </table>	  </td>
	  <?php
	  	$cur_col++;
		if ($cur_col>=$max_cols)
		{
			$cur_col = 0;
			echo "</tr><tr>";
		}
	  }
	  ?>
    </tr>
  </table>
  </div>
  </td>
  </tr>
<tr>
  <td align="left" valign="top" >&nbsp;</td>
</tr>
<tr>
  <td align="center" valign="top" >
  <div class="editarea_div">
  <input name="Submit_Upload" type="submit" class="red" id="Submit_Upload" value="Upload" />
  </div>
  </td>
</tr>
<tr>
  <td align="center" valign="top" >&nbsp;</td>
</tr>
</table>
	  <input type="hidden" name="txt_searchcaption" id="txt_searchcaption" value="<?php echo $_REQUEST['txt_searchcaption']?>" />
	  <input type="hidden" name="search_option" id="search_option" value="<?php echo $_REQUEST['search_option']?>" />
	  <input type="hidden" name="records_per_page" id="records_per_page" value="<?php echo $_REQUEST['records_per_page']?>" />
	  <input type="hidden" name="pg" id="pg" value="<?php echo $_REQUEST['pg']?>" />
	  <input type="hidden" name="sel_prods" id="sel_prods" value="<?php echo $_REQUEST['sel_prods']?>" />
	  <input type="hidden" name="fpurpose" id="fpurpose" value="save_upload" />
	  <input type="hidden" name="curdir_id" id="curdir_id" value="<?php echo ($_REQUEST['curdir_id'])?$_REQUEST['curdir_id']:0?>" />
	  <input type="hidden" name="src_page" id="src_page" value="<? echo $_REQUEST['src_page']?>" />
	  <input type="hidden" name="src_id" id="src_id" value="<? echo $_REQUEST['src_id']?>" />
	  <input type="hidden" name="productname" id="productname" value="<?=$_REQUEST['productname']?>" />
	  <input type="hidden" name="manufactureid" id="manufactureid" value="<?=$_REQUEST['manufactureid']?>" />
	  <input type="hidden" name="curtab" id="curtab" value="<?=$_REQUEST['curtab']?>" />
	 
</form>
