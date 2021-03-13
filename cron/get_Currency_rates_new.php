<?php
/*#################################################################
# Script Name 	: get_Currency_rates.php
# Description 	: Page to get currency rates from xe.com
# Coded by 		: Sny
# Created on	: 08-Sep-2010
# Modified by	: 
# Modified On	: 
#################################################################*/
///require_once("/var/www/vhosts/bshop4.co.uk/httpdocs/config_db.php");
//require_once("/var/www/html/bshop4/config_db.php");

define('ORG_DOCROOT','/var/www/vhosts/bshop4.co.uk/httpdocs'); // Live path

		require_once(ORG_DOCROOT."/config.php");

		require_once(ORG_DOCROOT."/config_db.php");
		require_once(ORG_DOCROOT.'/functions/functions.php');
		require_once(ORG_DOCROOT.'/includes/session.php');
		require_once(ORG_DOCROOT.'/includes/price_display.php');

// Get the list of all websites from sites table
$sql_sites = "SELECT site_id 
				FROM 
					sites WHERE site_id = 113";
$ret_sites = $db->query($sql_sites);
if($db->num_rows($ret_sites))
{
	while ($row_sites = $db->fetch_array($ret_sites))
	{
		$site_id = $row_sites['site_id'];
		$pick_automatically = get_general_settings('pick_currency_rate_automatically',$site_id);
		//if($pick_automatically['pick_currency_rate_automatically']==1)
		{
			Currency_Rates_GetandSave($site_id);	
			create_Currency_CacheFile($site_id);
		}
	}	
}

function Currency_Rates_GetandSave($site_id)
 {
 	global $db;
	$sql_default = "SELECT curr_code 
						FROM 
							general_settings_site_currency 
						WHERE 
							sites_site_id=$site_id 
							AND curr_default=1 
						LIMIT 
							1";
	$res_default = $db->query($sql_default);
	if ($db->num_rows($res_default)) // do the following only if default currency exists
	{
		list($default_curr) = $db->fetch_array($res_default);
		$sql_all_curr = "SELECT currency_id,curr_code 
							FROM 
								general_settings_site_currency 
							WHERE 
								sites_site_id=$site_id 
								AND curr_default=0 ";
		$res_all_curr = $db->query($sql_all_curr);
		if ($db->num_rows($res_all_curr))
		{
			while(list($all_currency_id,$all_curr_code) = $db->fetch_array($res_all_curr)) 
			{
				//$rate = quote_xe_currency($all_curr_code,$default_curr);
				$rate = quote_yahoofinance_currency($all_curr_code,$default_curr);
				
				if($rate)
				{
					$sql_update = "UPDATE general_settings_site_currency 
									SET 
										curr_rate = $rate 
									WHERE 
										sites_site_id = $site_id 
										AND currency_id=$all_currency_id 
										AND curr_default = 0 
									LIMIT 
										1";
					$db->query($sql_update);								
				}
			}
		}	
	}	
}
// Function to pick the currency rate from XE .net
function quote_xe_currency($to, $from) 
{
    $page = file('http://www.xe.com/ucc/convert.cgi?Amount=1&From=' . $from . '&To=' . $to);
    $match = array();
    preg_match('/[0-9.]+\s*' . $from . '\s*=\s*([0-9.]+)\s*' . $to . '/', implode('', $page), $match);
    if (sizeof($match) > 0) {
		if(is_numeric($match[1])) { 
      		return $match[1];
	  	} else {
			return false;
		}
    } else {
      return false;
    }
 }
 
// Function to pick currency rate from Yahoo finance
function quote_yahoofinance_currency($to, $from) 
{
	$url			= 'http://finance.yahoo.com/d/quotes.csv?e=.csv&f=sl1d1t1&s='. $from . $to .'=X';
	$filehandler 	= @fopen($url, 'r');
	if ($filehandler) 
	{
		$data = fgets($filehandler, 4096);
		fclose($filehandler);
	}
	$InfoData 		= explode(',',$data); 
	$curr_rate 		= $InfoData[1];
	if($curr_rate)
	{
		return round($curr_rate,2);
	}
	else
	{
		return false;
	}
}
 
 function get_general_settings($fields,$siteid)
{
	global $db;
	$sql = "SELECT $fields FROM general_settings_sites_common WHERE sites_site_id=$siteid LIMIT 1";
	$ret  = $db->query($sql);
	if ($db->num_rows($ret))
	{
		$row = $db->fetch_array($ret);
	}
	return $row;
}
function create_Currency_CacheFile($siteid)
{

	global $db;
	$sql_site = "SELECT site_domain 
					FROM 
						sites 
					WHERE 
						site_id = $siteid 
					LIMIT 
						1";
	$ret_site = $db->query($sql_site);
	if($db->num_rows($ret_site))
	{
		$row_site = $db->fetch_array($ret_site);
	}
	$image_path	=  '/var/www/vhosts/bshop4.co.uk/httpdocs/images/'.$row_site['site_domain'];
	//$image_path	=  '/var/www/html/bshop4/images/'.$row_site['site_domain'];
	$file_path = $image_path .'/settings_cache';
	if(!file_exists($file_path))
		mkdir($file_path);
		
	// Case of currencies
	$file_name = $file_path.'/currency.php';
	// Open the file in write mod 
	$fp = fopen($file_name,'w');
	fwrite($fp,'<?php'."\n");
	// get the details from general_settings_site_currency
	$sql_curr = "SELECT currency_id, curr_name, curr_sign, curr_sign_char, curr_code, curr_rate, curr_margin, curr_default, curr_numeric_code 
					FROM 
						general_settings_site_currency  
					WHERE 
						sites_site_id = $siteid 
					ORDER BY 
						curr_default DESC";
	$ret_curr = $db->query($sql_curr);
	if($db->num_rows($ret_curr))
	{
		while ($row_curr = $db->fetch_array($ret_curr))
		{
			if($row_curr['curr_default']==1)
			{
				fwrite($fp,'$default_curr[\'currency_id\'] = "'. addslashes(stripslashes($row_curr['currency_id'])).'";'."\n");
				fwrite($fp,'$default_curr[\'curr_name\'] = "'. addslashes(stripslashes($row_curr['curr_name'])).'";'."\n");
				fwrite($fp,'$default_curr[\'curr_sign\'] = "'. addslashes(stripslashes($row_curr['curr_sign'])).'";'."\n");
				fwrite($fp,'$default_curr[\'curr_sign_char\'] = "'. addslashes(stripslashes($row_curr['curr_sign_char'])).'";'."\n");
				fwrite($fp,'$default_curr[\'curr_code\'] = "'. addslashes(stripslashes($row_curr['curr_code'])).'";'."\n");
				fwrite($fp,'$default_curr[\'curr_rate\'] = "'. addslashes(stripslashes($row_curr['curr_rate'])).'";'."\n");
				fwrite($fp,'$default_curr[\'curr_margin\'] = "'. addslashes(stripslashes($row_curr['curr_margin'])).'";'."\n");
				fwrite($fp,'$default_curr[\'curr_default\'] = "'. addslashes(stripslashes($row_curr['curr_default'])).'";'."\n");
				fwrite($fp,'$default_curr[\'curr_numeric_code\'] = "'. addslashes(stripslashes($row_curr['curr_numeric_code'])).'";'."\n");
			}
				fwrite($fp,'$sel_curr['.$row_curr['currency_id'].'][\'currency_id\'] = "'. addslashes(stripslashes($row_curr['currency_id'])).'";'."\n");
				fwrite($fp,'$sel_curr['.$row_curr['currency_id'].'][\'curr_name\'] = "'. addslashes(stripslashes($row_curr['curr_name'])).'";'."\n");
				fwrite($fp,'$sel_curr['.$row_curr['currency_id'].'][\'curr_sign\'] = "'. addslashes(stripslashes($row_curr['curr_sign'])).'";'."\n");
				fwrite($fp,'$sel_curr['.$row_curr['currency_id'].'][\'curr_sign_char\'] = "'. addslashes(stripslashes($row_curr['curr_sign_char'])).'";'."\n");
				fwrite($fp,'$sel_curr['.$row_curr['currency_id'].'][\'curr_code\'] = "'. addslashes(stripslashes($row_curr['curr_code'])).'";'."\n");
				fwrite($fp,'$sel_curr['.$row_curr['currency_id'].'][\'curr_rate\'] = "'. addslashes(stripslashes($row_curr['curr_rate'])).'";'."\n");
				fwrite($fp,'$sel_curr['.$row_curr['currency_id'].'][\'curr_margin\'] = "'. addslashes(stripslashes($row_curr['curr_margin'])).'";'."\n");
				fwrite($fp,'$sel_curr['.$row_curr['currency_id'].'][\'curr_default\'] = "'. addslashes(stripslashes($row_curr['curr_default'])).'";'."\n");
				fwrite($fp,'$sel_curr['.$row_curr['currency_id'].'][\'curr_numeric_code\'] = "'. addslashes(stripslashes($row_curr['curr_numeric_code'])).'";'."\n");
		}
	}
	fwrite($fp,'?>');		
	fclose($fp);	
}
?>
