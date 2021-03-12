<?php
	include_once('header.php');
	
	$import_file 		= 'csv/disc_new_data_file.csv';	// Import filename
	
	$i=0;
	function cutoff($vals)
	{
		$str = explode('.',$vals);
		return $str[0];
	}
	
	?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<tr>
	<td align="left">#</td>
	<td align="left">Row</td>
	<td align="left">Product</td>
	<td align="left">Category</td>
	<td align="left">Type</td>
				<td align="right">Message</td>


	</tr>	
	<?php
	
		$fp = fopen($import_file,'r');
		if (!$fp)
		{
			echo "Cannot open the file";
			exit;
		}	
	$atleast_one_err = 0;
	$i =0;
	$previous_product_id = 0;
	$cnt=1;
	while (($data = fgetcsv($fp, 20000, ",")) !== FALSE)
	{
		if($i!=0) // case of header row
		{
			$err_msg 				= '';
			$type				= trim($data[4]);
			$pname				= trim($data[5]);
			$pname = str_replace('"',"'",$pname);

			$catname			= trim($data[1]);
			$barcode			= trim($data[7]);
			$model = trim($data[9]);
			$model = str_replace('"',"'",$model);
			$desc  =  trim($data[10]);
			if($desc=='')
			{
			  $desc = $pname;
			}
			$price = trim($data[14]);
			$price = cutoff($vals);
			$dtype = trim($data[19]);
			$value = trim($data[20]);
			$manid = trim($data[8]);
			if(strtoupper($type)=='NEW')
			{
				/*
			?>
				<tr>
				<td align="left"><?php echo $cnt?></td>	
				<td align="left"><?php echo $i?></td>
				<td align="left"><?php echo $pname?></td>
				<td align="left"><?php echo $catname?></td>
				<td align="left"><?php echo $type?></td>
								<td align="left"><?php echo $barcode?></td>
								<td align="left"><?php echo $model?></td>
								<td align="left"><?php echo $price?></td>
								<td align="left"><?php echo $dtype?></td>
								<td align="left"><?php echo $value?></td>
				</tr>		
			<?php
			* */
			if($pname!='')
			{
				$sql_existsc 	= "SELECT count(product_id) FROM products WHERE 
									product_name='".addslashes(trim($pname))."' AND sites_site_id=$siteid LIMIT 1";
				$ret_existsc 	= $db->query($sql_existsc);
				list($ext_cntc)	= $db->fetch_array($ret_existsc);
				if ($ext_cntc>0)
				{
				  $err_msg = "Sorry already exists";
				}
				else
				{
					 if($catname!='')
					 {
						     $sql_exists 	= "SELECT category_id FROM product_categories WHERE 
											    category_name='".addslashes(trim($catname))."' AND sites_site_id=$siteid LIMIT 1";
						    $ret_exists 	= $db->query($sql_exists);
						    $ext_cnt    	= $db->num_rows($ret_exists);
						 if($ext_cnt>0)
						 {
						    $row_cat = $db->fetch_array($ret_exists);
						    $cat_id  = $row_cat['category_id'];
						 }
						 else
						 {
							$insert_array = array();
							$insert_array['category_name']				= addslashes(trim($catname));
							$insert_array['sites_site_id']				= addslashes(trim($siteid));
							$db->insert_from_array($insert_array,'product_categories');
							$cat_id = $db->insert_id();
						 }
					 }
						$insert_sql = "INSERT INTO products (product_name,manufacture_id,product_model,product_shortdesc,product_webprice,product_default_category_id,sites_site_id) VALUES('".addslashes($pname)."', '".addslashes($manid)."','".addslashes($model)."','".addslashes($desc)."','".addslashes($value)."','".addslashes($cat_id)."','".addslashes($siteid)."')";
				//echo "<br/>".$update_sql;	
							$db->query($insert_sql);
							$prod_id  = $db->insert_id();
				            $insert_array = array();
							$insert_array['products_product_id']				        = $prod_id;
							$insert_array['product_categories_category_id']				= $cat_id;
							$db->insert_from_array($insert_array,'product_category_map');
				$cnt++;
				$err_msg = "Inserted Successfully";
			   }
			   ?>
			   <tr>
				<td align="left"><?php echo $cnt?></td>	
				<td align="left"><?php echo $i?></td>
				<td align="left"><?php echo $pname?></td>
				<td align="left"><?php echo $catname?></td>
				<td align="left"><?php echo $type?></td>
				<td align="right" style="color:#006600"><?php echo $err_msg?></td>
								
				</tr>	
			   <?php
			}
				
			}
		}	
		$i++;
		//echo "<br>".$err_msg;
	}
	fclose($fp);
	?>
    <tr>
		<td colspan="7" align="center" style="color:#006600"><strong>----- Completed Successfully ------</strong></td>
	</tr>
	</table>
