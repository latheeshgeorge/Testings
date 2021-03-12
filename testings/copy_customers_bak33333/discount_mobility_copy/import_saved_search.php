<?php
	set_time_limit(0);
	include_once('header.php');
		
	$i=0;
	
	
	// read the content of csv file
?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<?php
	
		$sql_rev = "SELECT * FROM saved_search WHERE sites_site_id = $src_siteid";
		$ret_rev = $db->query($sql_rev);
		if($db->num_rows($ret_rev))
		{
			while ($row_rev = $db->fetch_array($ret_rev))
			{
				$insert_array 					= array();
				$insert_array['sites_site_id'] 	= $dest_siteid;
				$insert_array['search_keyword'] = addslashes(stripslashes($row_rev['search_keyword']));
				$insert_array['search_desc'] 	= addslashes(stripslashes($row_rev['search_desc']));
				$insert_array['search_count'] 	= addslashes(stripslashes($row_rev['search_count']));
				$db->insert_from_array($insert_array,'saved_search');
			}
		}
	
	?>
<tr>
	<td colspan="3" align="center" style="color:#006600"><strong>----- Saved Search Copied Successfully ------ <?php echo date('r')?></strong></td>
</tr>
</table>
