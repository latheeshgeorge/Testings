<?php
	include ("../../../sites.php");
	include ("../../../config.php");
	$files = array();
	$image_path = IMAGE_ROOT_PATH.'/'.$ecom_hostname;
	$d = opendir($image_path);
	while(($file = readdir($d)) !== false) {
		
		if(filetype("$image_path/$file") == "file" && CheckImgExt($file))
		{
		 array_push($files, $file);
		 }
	}
	closedir($d);

	if(file_exists("$image_path/static")) {
		$d = opendir("$image_path/static");
		while(($file = readdir($d)) !== false)
			if(filetype("$image_path/static/$file") == "file" && CheckImgExt($file)) array_push($files, "static/$file");
		closedir($d);
	}

	sort($files);	//sorting array


	// generating $html_img_lst
	foreach ($files as $file) $html_img_lst .= "<a href=\"javascript:getImage('$file'); \">" . basename($file) . "</a><br>\n";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" >
<HTML>
	<HEAD>

		<TITLE>Image Browser</TITLE>

		<LINK rel="stylesheet" type="text/css" href="css/fck_dialog.css">

		<SCRIPT language="javascript">

var sImagesPath  = "<?php echo "/images/$ecom_hostname/" ?>";

var sActiveImage = "<?php if($files[0]) echo "/images/$ecom_hostname/" . $files[0] ?>";



function getImage(imageName)

{

	sActiveImage = sImagesPath + imageName ;
    sActiveImage = "http://<? print $ecom_hostname; ?>" + sActiveImage;
	imgPreview.src = sActiveImage ;
// alert(sActiveImage);

}



function ok()

{	

	window.opener.document.image.imgPreview.style.display='block';
	window.opener.document.kipper.txtUrl.value = sActiveImage;
	window.opener.document.image.imgPreview.src = sActiveImage;
	window.close() ; 

}

		</SCRIPT>

	</HEAD>

	<BODY bottommargin="5" leftmargin="5" topmargin="5" rightmargin="5">

<TABLE cellspacing="1" cellpadding="1" border="0" width="100%" class="dlg" height="100%">

	<TR height="100%">

		<TD>

			<TABLE cellspacing="0" cellpadding="0" width="100%" border="0" height="100%">

				<TR>

					<TD width="45%" valign="top">

						<table cellpadding="0" cellspacing="0" height="100%" width="100%">

							<tr>

								<td width="100%">File : </td>

							</tr>

							<tr height="100%">

								<td>

									<DIV class="ImagePreviewArea"><? echo $html_img_lst ?></DIV>

								</td>

							</tr>

						</table>

					</TD>

					<TD width="10%" >&nbsp;&nbsp;&nbsp;</TD>

					<TD>

						<table cellpadding="0" cellspacing="0" height="100%" width="100%">

							<tr>

								<td width="100%">Preview : </td>

							</tr>

							<tr>

								<td height="100%" align="center" valign="middle">

									<DIV class="ImagePreviewArea"><IMG id="imgPreview" border="1"

										src="<?php if($files[0]) echo "/images/$ecom_hostname/" . $files[0] ?>"></DIV>

								</td>

							</tr>

						</table>

					</TD>

				</TR>

			</TABLE>

		</TD>

	</TR>

	<TR>

		<TD align="center">

			<INPUT style="WIDTH: 80px" type="button" value="OK"     onclick="ok();"> &nbsp;&nbsp;&nbsp;&nbsp;

			<INPUT style="WIDTH: 80px" type="button" value="Cancel" onClick="window.close();"><BR>

		</TD>

	</TR>

</TABLE>

	</BODY>

</HTML>

<?



	function CheckImgExt($filename) {
		$filename = strtolower($filename);
		$img_exts = array("gif","jpg", "jpeg","png");

		foreach($img_exts as $this_ext) if (preg_match("/\.$this_ext$/", $filename)) return TRUE;

		return FALSE;

	}

?>