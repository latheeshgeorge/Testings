<?php
include_once("functions/functions.php");
include('includes/session.php');
include('includes/urls.php');
require_once("config.php");

$gz_filename = 'sitemap.xml.gz';

if(file_exists($image_path.'/otherfiles/'.$gz_filename))
{
	
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($gz_filename).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($image_path.'/otherfiles/'.$gz_filename));
    readfile($image_path.'/otherfiles/'.$gz_filename);
    exit;
}
?>
