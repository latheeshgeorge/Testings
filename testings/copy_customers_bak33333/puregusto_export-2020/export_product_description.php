<?php
	include_once('../../../classes/db_class.inc.php');	// Page which holds the class for db operations
	include '../../../config_db.php';
	include_once '../../../functions/functions.php';

	include_once '../../../includes/urls.php';

	$db	 				= new db_mysql($dbhost,$dbuname,$dbpass,$dbname);
	$db->connect();
	$db->select_db();
//include_once('header.php');
$siteid = 105;
$sql_srcsite = "SELECT site_domain FROM sites WHERE site_id = $siteid LIMIT 1";
	$ret_srcsite = $db->query($sql_srcsite);
	if($db->num_rows($ret_srcsite))
	{
		$row_srcsite = $db->fetch_array($ret_srcsite);
		$source_domain = stripslashes($row_srcsite['site_domain']);
	}
$ecom_hostname = $source_domain;
	$filename ='products_desc.csv';
	$fp = fopen ($filename,'w');
	//define('ORG_DOCROOT','/var/www/html/webclinic/bshop4');//local
     define('ORG_DOCROOT','/var/www/vhosts/bshop4.co.uk/httpdocs');//live
	
	//$srcbase_folder 		= ORG_DOCROOT."/images/".$domainname."/"; 
	$destnase_folder 		= ORG_DOCROOT."/testings/copy_customers/puregusto_export-2020/";
	
	
	
	fwrite($fp,'"Product Id","Product Name","Short Description","Main Description Filename","Tab Titles"'."\n");
	
	
	
		// Get the list of customers existing in the source website
		$sql_ord = "SELECT product_id,manufacture_id,product_name,product_model,product_shortdesc,
						product_longdesc
						FROM 
							products
							WHERE 
								sites_site_id = $siteid	";
		$ret_ord = $db->query($sql_ord);
		
		if($db->num_rows($ret_ord))
		{
			while ($row_ord = $db->fetch_array($ret_ord))
			{
				$err_msg 				= '';
				$prod_longdesc_file     = '';
				$pr_id			= stripslashes($row_ord['product_id']);
				$pr_name		= stripslashes($row_ord['product_name']);
				$pr_shortdesc	= stripslashes($row_ord['product_shortdesc']);
				$pr_longdesc	= stripslashes($row_ord['product_longdesc']);
				$pr_man			= stripslashes($row_ord['manufacture_id']);
				$pr_mdl			= stripslashes($row_ord['product_model']);
				
				$prod_longdesc_file = $pr_id.'_maindesc.txt';
				
				
				$fpcat = fopen($destnase_folder.'product_descriptions/'.$prod_longdesc_file,'w');
				fwrite($fpcat,$pr_longdesc."\n");
				fclose($fpcat);
				$sql_tab = "SELECT tab_id,tab_title,tab_content FROM product_tabs WHERE products_product_id = ".$row_ord['product_id'];
				$ret_tab = $db->query($sql_tab);
				$prod_tab_title = '';
			if($db->num_rows($ret_tab))
			{
				$cnttb = 0;
				while($row_tab = $db->fetch_array($ret_tab))
				{   $pr_tabdesc ="";
					$prod_tabdesc_file = '';		
					
					if($cnttb>0)
					{
					$prod_tabdesc_file .= ",";
					$prod_tab_title .= ",";
					}
					$tab_id = $row_tab['tab_id'];
					$pr_tabdesc = $row_tab['tab_content'];
					$pr_tabtitle = $row_tab['tab_title'];
					$prod_tabdesc_file .= $pr_id."_".$tab_id.'_tabdesc.txt';
					$prod_tab_title    .= $tab_id.'_'.$pr_tabtitle;
					$cnttb++;
					$fpcat = fopen($destnase_folder.'product_descriptions/'.$prod_tabdesc_file,'w');
				fwrite($fpcat,$pr_tabdesc."\n");
				fclose($fpcat);
				}
				$prod_longdesc_file = $prod_longdesc_file.",".$prod_tabdesc_file;
                	
			}	
								
								
				fwrite($fp,'"'.$pr_id.'","'.$pr_name.'","'.$pr_shortdesc.'","'.$prod_longdesc_file.'","'.$prod_tab_title.'"'."\n");
			}
		}
	echo "Products Exported Successfully";
	fclose($fp);
	$db->db_close();
?>
