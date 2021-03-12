<?php
	include_once('header.php');
	
	function strip_url($name)
	{
		$name = trim($name);
		$name = str_replace(" ","-",$name);
		$name = str_replace("_","-",$name);
		$name = preg_replace("/[^0-9a-zA-Z-]+/", "", $name);
		$name = str_replace("----","-",$name);
		$name = str_replace("---","-",$name);
		$name = str_replace("--","-",$name);
		$name = str_replace(".","-",$name);
		return strtolower($name);
	}
	
	// Get the list of customers existing in the source website
	$sql_custsrc = "SELECT * FROM product_categories WHERE sites_site_id = $des_siteid";
	$ret_custsrc = $db->query($sql_custsrc);
	
	?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<tr>
	<td align="left">#</td>
	<td align="left">Name</td>
	<td align="left">Type</td>
	<td align="left">Old Url</td>
	<td align="left">New Url</td>
	</tr>	
	<?php
	$i = 1;
	if($db->num_rows($ret_custsrc))
	{
		while ($row_custsrc = $db->fetch_array($ret_custsrc))
		{
			$err_msg 	= '';
			$name		= stripslashes($row_custsrc['category_name']);
			$type		= 'Category';
			
			
			
			// Check whether the product Exists in old website
			$sql_prodloc = "SELECT category_id,category_name FROM product_categories 
								WHERE 
									sites_site_id = $src_siteid 
									AND category_name='".addslashes($row_custsrc['category_name'])."' LIMIT 1";
			$ret_prodloc = $db->query($sql_prodloc);
			if($db->num_rows($ret_prodloc))
			{
				$row_prodloc = $db->fetch_array($ret_prodloc);
				$oldid = $row_prodloc['category_id'];
				$newid = $row_custsrc['category_id'];
				$oldurl = "http://".$dest_domain."/c$oldid/".strip_url($row_custsrc['category_name']).".html";
				$newurl = "http://".$dest_domain."/c$newid/".strip_url($row_custsrc['category_name']).".html";
				
				// Check whether the url already exists
				$sql_exst = "SELECT redirect_id FROM seo_redirect WHERE sites_site_id = $des_siteid AND 
								redirect_old_url ='".$oldurl."' LIMIT 1";
				$ret_exst = $db->query($sql_exst);
				if($db->num_rows($ret_exst)==0)
				{
					$sql_insert = "INSERT INTO seo_redirect SET 
									sites_site_id=".$des_siteid.",
									redirect_old_url='".addslashes($oldurl)."',
									redirect_new_url='".addslashes($newurl)."',
									redirect_last_access_date=now()";
					$db->query($sql_insert);
					$status = 'Done';	
				}
				else
					$status = 'Already exists';
			}	
			else
			{
				$status = 'Category Does not exists';	
			}	
			?>
			<tr>
			<td align="left"><?php echo $i?></td>
			<td align="left"><?php echo $name?></td>
			<td align="left"><?php echo $type?></td>
			<td align="left"><?php echo $oldurl?></td>
			<td align="left"><?php echo $newurl?></td>
			<td align="left"><?php echo $status?></td>
			</tr>		
			<?php
		
			$i++;
		}
	}
	$db->db_close();
	?>
    <tr>
		<td colspan="7" align="center" style="color:#006600"><strong>----- Category 301 Completed Successfully ------</strong></td>
	</tr>
	</table>
