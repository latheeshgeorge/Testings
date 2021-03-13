<?php
	/*#################################################################
	# Script Name 	: download.php
	# Description 	: Page to download attachments
	# Coded by 		: Sny
	# Created on	: 27-Jul-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
	include_once("functions/functions.php");
	include('includes/session.php');
	require_once("config.php");
	if($ecom_siteid==80)
	{
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
	$sql_attach = "SELECT * FROM product_attachments WHERE attachment_id=".$_REQUEST['attach_id'];
	$ret_attach = $db->query($sql_attach);
	if ($db->num_rows($ret_attach))
	{
		$row_attach = $db->fetch_array($ret_attach);
	}
	if($row_attach['product_common_attachments_common_attachment_id']==0)
	{
		$fname 		= 'images/'.$hosts.'/attachments/'.$row_attach['attachment_filename'];
	}
	else 
	{
		$fname 		= 'images/'.$hosts.'/commonattachments/'.$row_attach['attachment_filename'];
	}
	$orgfname	= stripslashes($row_attach['attachment_orgfilename']);
	if (empty($orgfname))
		$orgfname	= basename($fname);

	downloadFile($fname,$orgfname);
	}
?>
