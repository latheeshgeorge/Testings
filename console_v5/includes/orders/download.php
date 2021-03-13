<?php
	/*#################################################################
	# Script Name 	: download.php
	# Description 	: Page to download attachments
	# Coded by 		: Sny
	# Created on	: 08-Dec-2010  
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
	include_once("../../session_manage.php");;
	include_once("../../functions/functions.php");
	include('../../sites.php');
	include('../../config.php');

	function downloadFile($filename,$orgfname,$contents,$size) 
	{
		header("Content-type: application/x-download");
		header("Pragma: public");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Disposition: attachment; filename=".$orgfname.";");
		header("Accept-Ranges:bytes");
		header("Content-Length:$size");
		echo $contents;
		return true;
	}
	$hosts		= $ecom_hostname;
	$mod		= $_REQUEST['pass_mode'];
	if($mod=='slip')
	{
		$inc_filename 	= 'print_pack_slip.php';
		$fname			= 'packing_slip.html';
	}	
	elseif($mod =='friendly')
	{
		$inc_filename 	= 'print_order_details.php';
		$fname			= 'order_details.html';
	}	
	
	ob_start();
	$download_file = 1;
	include "$inc_filename";
	$contents = ob_get_contents();
	$size = ob_get_length();
	ob_end_clean();
	$contents = str_replace('</a>', '', $contents);
	$contents = preg_replace('/<a[^>]+href[^>]+>/', '', $contents);

	$contents = str_replace('call_ajax_showlistall("operation_changeorderpaystatus_sel")','',$contents);
	downloadFile($fname,$fname,$contents,$size);
?>
