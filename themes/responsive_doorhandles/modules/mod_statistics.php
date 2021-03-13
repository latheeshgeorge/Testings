<?php
	/*###########################################################################################
	# Script Name 	: mod_statistics.php
	# Description 		: Page which call the function to display the Web statistics in left / right panel
	# Coded by 		: LH
	# Created on		: 25-Mar-2008
	# Modified by		: LH
	# Modified On		: 25-Mar-2008
	############################################################################################*/
	$query = "SELECT site_hits 
								FROM 
									sites 
								WHERE 
								site_id = '$ecom_siteid' LIMIT 1";
	$ret_query=$db->query($query );
	$components->mod_statistics($display_title,$ret_query);
?>