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
		ob_end_flush();
		return true;
	}
	$hosts		= $ecom_hostname;
	
	if($_REQUEST['passport_id'])
	{
		$passport_id = $_REQUEST['passport_id'];
		
		 $sql_passport = "SELECT passport_id,passport_filename,passport_orgfilename,tenant_name FROM order_passport_details WHERE passport_id=$passport_id LIMIT 1";
		
		$ret_passport = $db->query($sql_passport);
		if ($db->num_rows($ret_passport))
		{
			$row_passport = $db->fetch_array($ret_passport);
		}
		$fname 		= '../../../images/'.$hosts.'/passport_details/'.$row_passport['passport_filename'];
		$orgfname	= stripslashes($row_passport['passport_orgfilename']);
	}
	
	if ($fname)
	{
		if (empty($orgfname))
			$orgfname	= basename($fname);
	
	downloadFile($fname,$orgfname);
	}	
?>
