<?php
// get current url

/*if($ecom_siteid==61)
{
	if($_REQUEST['req']=='search' and $_REQUEST['search_id'])
	{
		header ('HTTP/1.1 301 Moved Permanently');
		header ('Location: http://www.garraways.co.uk');
		exit;
	}
}*/

if(check_IndividualSslActive())
{
	$ecom_selfhttp = "https://";
}
else
{
	$ecom_selfhttp = "http://";
}


$full_seo_urls = $ecom_selfhttp.$ecom_hostname.$cur_urls;
// Check whether current url is there in the seo_redirect table
$sql_seoredirect = "SELECT redirect_id,redirect_new_url 
						FROM 
							seo_redirect 
						WHERE 
							sites_site_id = $ecom_siteid 
							AND redirect_old_url = '".mysqli_real_escape_string($full_seo_urls)."' 
						LIMIT 
							1";
$ret_seo_redirect = $db->query($sql_seoredirect);
	
if($db->num_rows($ret_seo_redirect))
{
	$row_seo_direct = $db->fetch_array($ret_seo_redirect);
	$new_seo_url = stripslashes($row_seo_direct['redirect_new_url']);
	// Updating the last accessed datatime
	$update_seoredirect = "UPDATE 
								seo_redirect 
							SET 
								redirect_last_access_date = now() 
							WHERE 
								redirect_id=".$row_seo_direct['redirect_id']."
							LIMIT 
								1";
	$db->query($update_seoredirect);
	header ('HTTP/1.1 301 Moved Permanently');
	header ('Location: '.$new_seo_url);
	exit;
}
?>
