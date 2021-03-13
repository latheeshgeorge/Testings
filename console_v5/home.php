<?php
/*#################################################################
# Script Name 	: home.php
# Description 	: Page after successfull login on admin side
# Coded by 		: Sny
# Created on	: 11-Jun-2007
# Modified by	: 
# Modified On	: 
#################################################################
*/
//	Include Common routines
include_once("functions/functions.php");
include('session.php');
require_once("config.php");
$_REQUEST['records_per_page'] = intval($_REQUEST['records_per_page']);
if($_REQUEST['records_per_page']<1)
{
	if($_REQUEST['request']=='img_gal')
		$_REQUEST['records_per_page']=54;
	else
		$_REQUEST['records_per_page']=50;
}	

include("themes/Default.php"); 
?>

