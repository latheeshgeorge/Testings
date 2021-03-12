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
	$filename ='puregusto_page_301.csv';
	$fp = fopen ($filename,'w');
	
	fwrite($fp,'"Page Id","Title","Page Name","URL"'."\n");
	// Check whether the product Exists in old website
	$sql_custsrc = "SELECT page_id,title,pname FROM static_pages WHERE sites_site_id = $siteid";
	$ret_custsrc = $db->query($sql_custsrc);
	
	
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
	$i = 1;
	if($db->num_rows($ret_custsrc))
	{
		while ($row_custsrc = $db->fetch_array($ret_custsrc))
		{
				$newid = $row_custsrc['page_id'];
				$newname = $row_custsrc['pname'];
				$newntitle = $row_custsrc['title'];
				$newurl = "https://".$source_domain."/".strip_url($newname)."-pg$newid.html";	
			  fwrite($fp,add_qts($newid).','.add_qts($newntitle).','.add_qts($newname).','.add_qts($newurl)."\n");
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
		<td colspan="7" align="center" style="color:#006600"><strong>----- Static Page 301 Completed Successfully ------</strong></td>
	</tr>
	</table>
