<?
	/*#################################################################
	# Script Name 	: Components.php
	# Description 	: This page which decides the components to be shown.
	# Coded by 		: Sny
	# Created on	: 03-Dec-2007
	# Modified by	: Sny
	# Modified On	: 17-Feb-2010
	#################################################################*/
if(count($display_layout_cache_array[$default_layout][$position]) and $layout_cache_file_included==true) // case of picking from cache files
{
	for($inx=0;$inx<count($display_layout_cache_array[$default_layout][$position]);$inx++)
	{
		$display_title 			= stripslashes($display_layout_cache_array[$default_layout][$position][$inx]['display_title']);
		$display_id				= $display_layout_cache_array[$default_layout][$position][$inx]['display_id'];
		$display_module 		= $display_layout_cache_array[$default_layout][$position][$inx]['feature_modulename'];
		$display_componentid	= $display_layout_cache_array[$default_layout][$position][$inx]['display_component_id'];
		$filepath 				= Display_Component_Module($display_module,ORG_DOCROOT);
		if ($filepath)
			include($filepath);	
	}
}
else // case of picking from database
{
	$query	= "SELECT 
						a.display_title,a.display_id,feature_id,feature_modulename,display_component_id 
					FROM 
						display_settings a, features b 
					WHERE 
						a.sites_site_id='$ecom_siteid' 
						AND a.display_position LIKE '$position' 
						AND a.features_feature_id=b.feature_id 
						AND a.layout_code='$default_layout' 
					ORDER BY 
						a.display_order;";		
	$result = $db->query($query);
	while ($row = $db->fetch_array($result))
	{
		$display_title 			= stripslashes($row['display_title']);
		$display_id				= $row['display_id'];
		$display_module 		= $row['feature_modulename'];
		$display_componentid	= $row['display_component_id'];
		$filepath 				= Display_Component_Module($display_module,ORG_DOCROOT);
		include($filepath);
	}
}
?>
