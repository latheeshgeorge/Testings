<?php
	include_once('../../../classes/db_class.inc.php');	// Page which holds the class for db operations
	include '../../../config_db.php';
	include_once '../../../functions/functions.php';

	include_once '../../../includes/urls.php';

	$db	 				= new db_mysql($dbhost,$dbuname,$dbpass,$dbname);
	$db->connect();
	$db->select_db();
	//$siteid 				= 126;//local
	$siteid 				= 105;//local
	$added_date 			= '2010-01-10 00:00:00';
	$filename ='puregusto_product_301.csv';
	$fp = fopen ($filename,'w');
	
	fwrite($fp,'"Product Id","Product Name","URL"'."\n");
	// Check whether the product Exists in old website
	echo $sql_prod = "SELECT product_id,product_name FROM products  
								WHERE 
									sites_site_id = $siteid ";
    $ret_prod = $db->query($sql_prod);
	
	
	$i = 1;
	$sql_srcsite = "SELECT site_domain FROM sites WHERE site_id = $siteid LIMIT 1";
	$ret_srcsite = $db->query($sql_srcsite);
	if($db->num_rows($ret_srcsite))
	{
		$row_srcsite = $db->fetch_array($ret_srcsite);
		$source_domain = stripslashes($row_srcsite['site_domain']);
	}
	?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<tr>
	<td align="left"></td>
	</tr>	
	<?php
	if($db->num_rows($ret_prod))
	{
		while ($row_prod = $db->fetch_array($ret_prod))
		{
			  $prodName = stripslashes(strip_url($row_prod['product_name']));
			  $prodId = $row_prod['product_id'];
			  $prod_url = "https://".$source_domain."/".$prodName."-p$prodId.html";			
			  fwrite($fp,add_qts($prodId).','.add_qts($prodName).','.add_qts($prod_url)."\n");
			//$i++;
		}
	}	
	
	echo "<br><br>Done";
	fclose($fp);
	$db->db_close();
	
	function add_qts(&$str)
	{
		$str = '"' . str_replace('"', '""', stripslashes($str)) . '"';
		return $str;
	}
	?>
    <tr>
		<td colspan="7" align="center" style="color:#006600"><strong>----- Product 301 Completed Successfully ------</strong></td>
	</tr>
	</table>
