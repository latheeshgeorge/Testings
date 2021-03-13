<?php
include_once("functions/functions.php");
include('includes/session.php');
include('includes/urls.php');
require_once("config.php");

$robots_template_filename = 'robots_template.php';

if(file_exists($image_path.'/otherfiles/'.$robots_template_filename))
{
	header('Content-Type: text/plain');
	readfile($image_path.'/otherfiles/'.$robots_template_filename);
}
else
{
	if(file_exists($robots_template_filename))
	{
		header('Content-Type: text/plain');
		readfile($robots_template_filename);
	}
	else
	{
		echo "No direct file exists";
	}
}
?>
