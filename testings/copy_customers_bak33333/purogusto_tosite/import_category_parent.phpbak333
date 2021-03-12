<?php

	include_once('header.php');
	
	$import_cat_map			= 'map/category_map.csv';
	
	$fp_cat = fopen($import_cat_map,'r');
	if (!$fp_cat)
	{
		echo "Cannot open the file map";
		exit;
	}
	$i = 0;
	$row_i = 1;
	$cat_arr = array();
	while (($data = fgetcsv($fp_cat, 1000, ",")) !== FALSE)
	{
		if($i!=0)
		{
			$cat_arr[$data[0]]= $data[1];
		}
		$i++;
	}	
	fclose($fp_cat);
	
	$i=0;
	
	
	// read the content of csv file
?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<?php
	
		$fp = fopen($import_cat_map,'r');
		if (!$fp)
		{
			echo "Cannot open the file";
			exit;
		}
	$atleast_one_err = 0;
	$i =0;
	while (($data = fgetcsv($fp, 1000, ",")) !== FALSE)
	{
		if($i!=0) // case of header row
		{
			$err_msg 				= '';
			$old_catid	 				= trim($data[0]);
			$new_catid	 				= trim($data[1]);
			$parent						= trim($data[2]);
			
			$update_array				= array();
			$update_array['parent_id']	= $cat_arr[$parent];
			$db->update_from_array($update_array,'product_categories',array('category_id'=>$new_catid));
			
		}			
		
		$i++;
	}
	fclose($fp);
	?>
<tr>
	<td colspan="3" align="center" style="color:#006600"><strong>----- Catagories Parent Mapped Successfully ------ <?php echo date('r')?></strong></td>
</tr>
</table>
	
