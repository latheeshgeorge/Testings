<?php
	/*###########################################################################################
	# Script Name 	: mod_recentlyviewedproduct.php
	# Description 	: Page which call the function to display the recently viewed products
	# Coded by 		: Sny
	# Created on	: 20-Dec-2007
	# Modified by	: Sny
	# Modified On	: 01-Jan-2008
	############################################################################################*/
	// Check whether the function to display the shop is to be called
	if ($cookieval)	
	{
		$components->mod_recentlyviewedproduct($cookieval,$display_title);
		
	}
?>
