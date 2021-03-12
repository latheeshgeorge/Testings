<?php

	include_once('header.php');	
	
	$import_file 			= 'discountmobility-csv/MOBILITY SCOOTER RAMPS.csv';	// Import filename
	$i=0;
	// read the content of csv file
	?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<?php
	
		$fp = fopen($import_file,'r');
		if (!$fp)
		{
			echo "Cannot open the file";
			exit;
		}
	$atleast_one_err = 0;
	$i =0;
	$category_name = "MOBILITY SCOOTER RAMPS";
	$dir_name      = 'prductcode_map';
	$filename      = 'prductcode_map.csv';
	/*
	$header        = array('Product Id','Manufacture Code'); 
	if(!file_exists($dir_name))
	{
		mkdir($dir_name, 0777, true);
    }
	$fp1 = fopen($dir_name.'/'.$filename, 'a');
				fputcsv($fp1, $header);
    fclose($fp1);
    */ 
    $sql_categories = "SELECT category_id FROM product_categories WHERE category_name='$category_name' AND sites_site_id=$siteid LIMIT 1"; 
			$ret_categories = $db->query($sql_categories);
			if($db->num_rows($ret_categories)>0)
			{
			 $row_categories = $db->fetch_array($ret_categories);
			 $category_id    = $row_categories['category_id'];
			}
			else
			{
			 $insert_array = array();
			 $insert_array['category_name'] = $category_name;
			 $insert_array['sites_site_id'] = $siteid;
			 $db->insert_from_array($insert_array,'product_categories');
			 $category_id = $db->insert_id();
			}
		$product_desc  = '';
		
	while (($data = fgetcsv($fp, 20000, ",")) !== FALSE)
	{ 
		if($i!=0) // case of header row
		{
			$product_name     = rmv_double_qt($data[1]);
			$long_description = rmv_double_qt($data[2]);
			$manufacture_id   =  trim($data[3]);
			$vendor 		  =  trim($data[4]);
			$exactprice       =  trim($data[7]);
			$web_price        =  trim($data[12]);
		
			$decription_tab   = ""; 
			$decription_tab   .= "<table border=0 cellspacing=0 cellpadding=0 width=100%>";
			$decription_tab  .= "<tr><td class='desc_left'>RRP</td><td class='desc_middle'>&nbsp;:&nbsp;</td><td class='desc_right'>$data[6]</td></tr>";
			if($data[13]!='')
			$decription_tab  .= "<tr><td class='desc_left'>Lenth Min (mm)</td><td class='desc_middle'>&nbsp;:&nbsp;</td><td class='desc_right'>$data[13]</td></tr>";
			if($data[14]!='')
			$decription_tab  .= "<tr><td class='desc_left'>Length Max (mm)</td><td class='desc_middle'>&nbsp;:&nbsp;</td><td class='desc_right'>$data[14]</td></tr>";
			if($data[15]!='')
			$decription_tab  .= "<tr><td class='desc_left'>Width (mm)</td><td class='desc_middle'>&nbsp;:&nbsp;</td><td class='desc_right'>$data[15]</td></tr>";
			if($data[16]!='')
			$decription_tab  .= "<tr><td class='desc_left'>Height (mm)</td><td class='desc_middle'>&nbsp;:&nbsp;</td><td class='desc_right'>$data[16]</td></tr>";
			if($data[17]!='')
			$decription_tab  .= "<tr><td class='desc_left'>Loading Capacity (kg)</td><td class='desc_middle'>&nbsp;:&nbsp;</td><td class='desc_right'>$data[17]</td></tr>";
			if($data[18]!='')
			$decription_tab  .= "<tr><td class='desc_left'>Web Weight (kg)</td><td class='desc_middle'>&nbsp;:&nbsp;</td><td class='desc_right'>$data[18]</td></tr>";
			
			if($data[19]!='')
			$decription_tab  .= "<tr><td class='desc_left'>Shipping Weight</td><td class='desc_middle'>&nbsp;:&nbsp;</td><td class='desc_right'>$data[19]</td></tr>";

			if($data[21]!='')
			$decription_tab  .= "<tr><td class='desc_left'>Vatable</td><td class='desc_middle'>&nbsp;:&nbsp;</td><td class='desc_right'>$data[21]</td></tr>";

			$decription_tab  .= "</table>";
			//echo $decription_tab."<br>";			
			
			$sql_products = "SELECT product_id,product_name  FROM products WHERE product_name='$product_name' AND sites_site_id=$siteid LIMIT 1"; 
			$ret_products = $db->query($sql_products);
			if($db->num_rows($ret_products)==0)
			{
			$insert_array = array();
			$insert_array['product_name']      				= $product_name;
			$insert_array['product_shortdesc'] 				= $product_name;
			$insert_array['product_longdesc']  				= addslashes($long_description);
			$insert_array['product_webprice']  				= $web_price ;
			$insert_array['sites_site_id']     				= $siteid;
			$insert_array['product_default_category_id']    = $category_id ;

			
			if($exactprice!='')
			{
			   if($exactprice<$web_price)
			   {
				  $insert_array['product_discount']  = $exactprice ;
				  $insert_array['product_discount_enteredasval']  = 2;
			   
			   }
			}
			$insert_array['manufacture_id']  = $manufacture_id ;
			$insert_array['product_alloworder_notinstock']  = "Y" ;

			
			$db->insert_from_array($insert_array,'products');
			$product_id = $db->insert_id();
			
			if($vendor!='')
			{
				$sql_vendors = "SELECT vendor_id FROM product_vendors WHERE vendor_name='$vendor' AND sites_site_id=$siteid LIMIT 1"; 
				$ret_vendors = $db->query($sql_vendors);
				if($db->num_rows($ret_vendors)>0)
				{
				 $row_vendors = $db->fetch_array($ret_vendors);
				 $vendor_id    = $row_vendors['vendor_id'];
				}
				else
				{
				 $insert_array = array();
				 $insert_array['vendor_name'] = $vendor;
				 $insert_array['sites_site_id'] = $siteid;
				 $db->insert_from_array($insert_array,'product_vendors');
				 $vendor_id = $db->insert_id();
				}
				 $insert_array = array();
				 $insert_array['product_vendors_vendor_id'] = $vendor_id;
				 $insert_array['products_product_id'] = $product_id;
				 $insert_array['sites_site_id'] = $siteid;

				 $db->insert_from_array($insert_array,'product_vendor_map');
		    }
		    
			$insert_array   = array();
			$insert_array['products_product_id']   			= $product_id;
			$insert_array['product_categories_category_id'] = $category_id;
			$db->insert_from_array($insert_array,'product_category_map');
			
			$insert_array   = array();
			$insert_array['products_product_id']   = $product_id;
			$insert_array['tab_title']             = 'More Specification';
			$insert_array['tab_content']           = addslashes($decription_tab);
			$db->insert_from_array($insert_array,'product_tabs');
			
			$pfield   = array($product_id,$manufacture_id);
			$fp2 = fopen($dir_name.'/'.$filename, 'a');				
				fputcsv($fp2, $pfield);	
			    fclose($fp2);
			 	
		  }	
		   else
			{
				$row_prod = $db->fetch_array($ret_products);
				
				$product_desc  .= "<tr><td class='desc_left'>$manufacture_id</td><td class='desc_right'>$row_prod[product_name]</td><td><span style='color:#FF0000;'>!!!Error Not imported allready exists</span></td></tr>";
			}				
		}
		$i++;
	}
	if($product_desc!='')
	{
		$error_mes = '';
		$error_mes .= "<table border=1 cellspacing=0 cellpadding=0  width=100%>";
		$error_mes .= "<tr><td class='desc_left'><strong>Product Code</strong></td><td class='desc_right'><strong>Product Name</strong></td><td><strong>Error message</strong></td></tr>";
		$error_mes .= $product_desc;
		$error_mes .= "</table>";
	}
	echo $error_mes;
	fclose($fp);
	
	?>
    <tr>
		<td colspan="3" align="center" style="color:#006600"><strong>----- Products Imported Successfully ------</strong></td>
	</tr>
	</table>
    
    <?php
	function rmv_double_qt($dbl_qt_str)
	{
		$bodytag = str_replace("\"", "\'\'", $dbl_qt_str);
		return $bodytag;
	}
	
	?>
	
