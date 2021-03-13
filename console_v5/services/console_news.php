<?php
/*
	#################################################################
	# Script Name 	: view_profile.php
	# Description 	: Action Page for changing the details of the logged in users
	# Coded by 		: ANU
	# Created on	: 13-Jun-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################
	*/	
if($_REQUEST['fpurpose'] =='') {
$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/console_news/console_news.php");
} 
if($_REQUEST['fpurpose'] =='list_newsdetails') {
         include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$ajax_return_function = 'ajax_return_contents';
		include "../ajax/ajax.php";
		include ('../includes/console_news/ajax/console_news_functions.php');
		show_news_details_list($_REQUEST['news_id']);
} 

?>