<?php
	/*#################################################################
	# Script Name 	: download.php
	# Description 	: Page to download attachments
	# Coded by 		: Sny
	# Created on	: 27-Jul-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
	include_once("../../functions/functions.php");
	include('../../session.php');
	require_once("../../config.php");
	function downloadFile($filename,$orgfname=-1) 
	{
		$download_size = @filesize("$filename");
		
		header("Content-type: application/x-download");
		header("Pragma: public");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Disposition: attachment; filename=".$orgfname.";");
		header("Accept-Ranges:bytes");
		header("Content-Length: $download_size");
		@readfile("$filename");
		return true;
	}
	$hosts		= $ecom_hostname;
	
	if($_REQUEST['attach_id'])
	{
		$sql_attach = "SELECT attachment_filename,attachment_orgfilename FROM product_common_attachments WHERE common_attachment_id=".$_REQUEST['attach_id']." LIMIT 1";
		$ret_attach = $db->query($sql_attach);
		if ($db->num_rows($ret_attach))
		{
			$row_attach = $db->fetch_array($ret_attach);
		}
		$fname 		= '../../../images/'.$hosts.'/commonattachments/'.$row_attach['attachment_filename'];
		$orgfname	= stripslashes($row_attach['attachment_orgfilename']);
	}
	if ($fname)
	{
		if (empty($orgfname))
			$orgfname	= basename($fname);
	
		downloadFile($fname,$orgfname);
	}	
?>
