<?php

	include_once('header.php');
	
	
	$import_stat_map			= 'map/static_map.csv';
	
	$i=0;
	
	
	// read the content of csv file
?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<?php
		// Open a csv file to hold the category mappings
		$fp_statmap = fopen($import_stat_map,'w');
		if (!$fp_statmap)
		{
			echo "Cannot open the file for writing";
			exit;
		}
	
	
	// Get the list of all categories from source site
	$sql_stat = "SELECT * FROM static_pages WHERE sites_site_id = $src_siteid";
	$ret_stat = $db->query($sql_stat);
	
	
	
	
		
	fwrite($fp_statmap,'Old Static Id,New Static Id'."\r\n"); // writing the header row
	$atleast_one_err = 0;
	$i =0;
	if ($db->num_rows($ret_stat))
	{
		while ($row_stat = $db->fetch_array($ret_stat))
		{
			$err_msg 											= '';
			
			$insert_array 							= array();
			$insert_array['sites_site_id'] 			= $dest_siteid;
			$insert_array['title'] 					= addslashes(stripslashes($row_stat['title']));
			$insert_array['content'] 				= addslashes(stripslashes($row_stat['content']));
			$insert_array['hide'] 					= addslashes(stripslashes($row_stat['hide']));
			$insert_array['pname'] 					= addslashes(stripslashes($row_stat['pname']));
			$insert_array['page_type'] 				= addslashes(stripslashes($row_stat['page_type']));
			$insert_array['page_link'] 				= addslashes(stripslashes($row_stat['page_link']));
			$insert_array['page_xml_filename'] 		= addslashes(stripslashes($row_stat['page_xml_filename']));
			$insert_array['page_link_newwindow'] 	= addslashes(stripslashes($row_stat['page_link_newwindow']));
			$insert_array['page_xml_key'] 			= addslashes(stripslashes($row_stat['page_xml_key']));
			$insert_array['allow_edit'] 			= addslashes(stripslashes($row_stat['allow_edit']));
			$insert_array['allow_auto_linker'] 		= addslashes(stripslashes($row_stat['allow_auto_linker']));
			$insert_array['in_mobile_api_sites'] 	= addslashes(stripslashes($row_stat['in_mobile_api_sites']));
						
			$db->insert_from_array($insert_array,'static_pages');
			$stat_id 	= $db->insert_id();
			
			$statid 	= $row_stat['page_id'];
			
			fwrite($fp_statmap,"$statid,$stat_id"."\r\n"); // writing the header row
		}
	}	
	fclose($fp_statmap);
	
	?>
<tr>
	<td colspan="3" align="center" style="color:#006600"><strong>----- Static Pages Imported Successfully ------ <?php echo date('r')?></strong></td>
</tr>
</table>
	
