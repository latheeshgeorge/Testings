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
		$sql_attach = "SELECT attachment_filename,attachment_orgfilename FROM product_attachments WHERE attachment_id=".$_REQUEST['attach_id']." LIMIT 1";
		$ret_attach = $db->query($sql_attach);
		if ($db->num_rows($ret_attach))
		{
			$row_attach = $db->fetch_array($ret_attach);
		}
		$fname 		= '../../../images/'.$hosts.'/attachments/'.$row_attach['attachment_filename'];
		$orgfname	= stripslashes($row_attach['attachment_orgfilename']);
	}
	elseif($_REQUEST['attach_icon_id'])
	{
		$sql_attach = "SELECT attachment_filename,attachment_orgfilename,attachment_icon, attachment_icon_img FROM product_attachments WHERE attachment_id=".$_REQUEST['attach_icon_id']." LIMIT 1";
		$ret_attach = $db->query($sql_attach);
		if ($db->num_rows($ret_attach))
		{
			$row_attach = $db->fetch_array($ret_attach);
		}
		$fname 		= '../../../images/'.$hosts.'/attachments/icons/'.$row_attach['attachment_icon_img'];
		$orgfname	= stripslashes($row_attach['attachment_icon']);
	}
	elseif ($_REQUEST['download_id'])
	{
		$sql_download = "SELECT proddown_orgfilename,proddown_filename FROM product_downloadable_products WHERE proddown_id=".$_REQUEST['download_id']." LIMIT 1";
		$ret_download = $db->query($sql_download);
		if ($db->num_rows($ret_download))
		{
			$row_download = $db->fetch_array($ret_download);
		}
		$fname 		= '../../../images/'.$hosts.'/product_downloads/'.$row_download['proddown_filename'];
		$orgfname	= stripslashes($row_download['proddown_orgfilename']);
	}
	elseif($_REQUEST['flv_id'])
	{
		$sql_download = "SELECT product_flv_orgfilename,product_flv_filename FROM products WHERE product_id=".$_REQUEST['flv_id']." AND sites_site_id=$ecom_siteid LIMIT 1";
		$ret_download = $db->query($sql_download);
		if ($db->num_rows($ret_download))
		{
			$row_download = $db->fetch_array($ret_download);
		}
		$fname 		= '../../../images/'.$hosts.'/product_flv/'.trim($row_download['product_flv_filename']);
		$orgfname	= stripslashes($row_download['product_flv_orgfilename']);
	}
	if ($fname)
	{
		if (empty($orgfname))
			$orgfname	= basename($fname);
	
	downloadFile($fname,$orgfname);
	}	
?>
