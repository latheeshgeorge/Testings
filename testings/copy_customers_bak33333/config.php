<?php

// ###############################################################################
// Source Site Details
// ###############################################################################
$src_siteid			= 165  ; // www.garraways.co.uk/ on old domain
$src_host 			= 'localhost';
$src_user 			= 'newserver';
$src_pass 			= 'yuj*dff';
$src_db 			= 'bshop_3_1';


// ###############################################################################
// Destination Site Details
// ###############################################################################
$dest_siteid		= 61; // garraways.bshop4.co.uk .bshop4.co.uk on new domain
$dest_host			= 'localhost';
$dest_user			= 'bshop_am4';
$dest_pass			= 'b$H0pF@Ur';
$dest_db			= 'business1st_bshop4';


// ###############################################################################
// Make connection to souce db
// ###############################################################################
$src_link = mysql_connect($src_host,$src_user,$src_pass);
if(!$src_link)
{
	echo "Cannot connect to $src_db";
	exit;
}

// ###############################################################################
// Make connection to destination db
// ###############################################################################
$dest_link = mysql_connect($dest_host,$dest_user,$dest_pass);
if(!$dest_link)
{
	echo "Cannot connect to $dest_db";
	exit;
}
// ###############################################################################
// Get the name of source site
// ###############################################################################
$sql_src = "SELECT domain 
					FROM 
						sites 
					WHERE 
						site_id = $src_siteid 
					LIMIT 
						1";
$ret_src = mysql_db_query($src_db,$sql_src,$src_link);

// ###############################################################################
// Get the details of destination site
// ###############################################################################
$sql_dest = "SELECT site_domain,themes_theme_id   
					FROM 
						sites 
					WHERE 
						site_id = $dest_siteid 
					LIMIT 
						1";
$ret_dest = mysql_db_query($dest_db,$sql_dest,$dest_link);

if (mysql_num_rows($ret_src))
{
	$row_src = mysql_fetch_array($ret_src);
	// Image path
	$src_image_path 		= SRC_DOCROOT . '/images/' . $row_src['domain'];
	$src_domain			= $row_src['domain'];
}
else
{
		echo "Source site does not exists";
		exit;
}
if (mysql_num_rows($ret_dest))
{
	$row_dest = mysql_fetch_array($ret_dest);	
	$cur_theme_id = $row_dest['themes_theme_id'];
	// Image path
	$dest_image_path 		= DEST_DOCROOT . '/images/' . $row_dest['site_domain'];
	$dest_domain			= $row_dest['site_domain'];
}	
else
{
		echo "Destination site does not exists";
		exit;
}

echo $tree = '<strong>Import From</strong> '.$row_src['domain'].'<strong> to </strong>'.$row_dest['site_domain'];

$process_div = '<div id="import_processing_div" style="display:none; color:#FF0000" align="center">
				<br>
 				.... Processing Please wait ....
 				<br><br>
				</div>';
?>