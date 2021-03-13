<?php
	// TODO: make it change the file name if the file you are trying to upload already exists

	include ("../../../sites.php");
	include ("../../../config.php");
	$upload_dir = "$image_path/static";
	
	if(!file_exists($upload_dir)) {
		mkdir($upload_dir, 0777) or die("Failed to create static images directory");
	}
	
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" >
<HTML>
	<HEAD>
		<TITLE>File Uploader</TITLE>
		<LINK rel="stylesheet" type="text/css" href="../../css/fck_dialog.css">
	</HEAD>
	<BODY><form>
		<TABLE eight="100%" width="100%">
			<TR>
				<TD align=center valign=middle><B>
					Upload in progress...
<font color='red'><BR><BR>
<?php 
$filename = str_replace(" ", "_", $_FILES['FCKeditor_File']['name']);
$savefile = "$upload_dir/" . $filename;
$temp_filename = str_replace(" ", "_", $_FILES['FCKeditor_File']['tmp_name']);


if (file_exists($savefile)) {
	echo "Error : File " . $filename." exists, can't overwrite it...";
	echo '<BR><BR><INPUT type="button" value=" Cancel " onclick="window.close()">';
}
elseif ($_FILES['FCKeditor_File']['size']>301000)
{
	echo "Error : File size exceeds 300KB.. Cannot Upload";
	echo '<BR><BR><INPUT type="button" value=" Cancel " onclick="window.close()">';
}
 else {
	if (is_uploaded_file($temp_filename)) {
		if (move_uploaded_file($temp_filename, $savefile)) {
			chmod($savefile, 0666);
			?>
		<SCRIPT language=javascript>
		window.opener.document.image.imgPreview.style.display='block';
	    window.opener.document.kipper.txtUrl.value = "<?php echo "http://$ecom_hostname/images/$ecom_hostname/static/" . $filename ?>";
	    window.opener.document.image.imgPreview.src = "<?php echo "http://$ecom_hostname/images/$ecom_hostname/static/" . $filename ?>";
		/*//window.opener.setImage('<?php echo "/images/$ecom_hostname/static/" . $_FILES['FCKeditor_File']['name'] ?>') ; window.close();</SCRIPT>
		<?php
		echo "Upload Completed";
		}
	} else {
		echo "Error : ";
		switch($_FILES['FCKeditor_File']['error']) {
			case 0: //no error; possible file attack!
				echo "There was a problem with your upload.";
				break;
			case 1: //uploaded file exceeds the upload_max_filesize directive in php.ini
				echo "The file you are trying to upload is too big.";
				break;
			case 2: //uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the html form
				echo "The file you are trying to upload is too big.";
				break;
			case 3: //uploaded file was only partially uploaded
				echo "The file you are trying upload was only partially uploaded.";
				break;
			case 4: //no file was uploaded
				echo "You must select an image for upload.";
				break;
			default: //a default error, just in case!  :)
				echo "There was a problem with your upload.";
				break;
		}
	}
	echo '<BR><BR><INPUT type="button" value=" OK " onclick="window.close()">';
} ?>
				</font></B></TD>
			</TR>
		</TABLE>
	</form></BODY>
</HTML>
