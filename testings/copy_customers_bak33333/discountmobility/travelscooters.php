<?php

	include_once('header.php');	
	
	$import_file 			= 'discountmobility-csv/TRAVEL SCOOTERS.csv';	// Import filename
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
	$category_name = "TRAVEL SCOOTERS";
	$dir_name      = 'prductcode_map';
	$filename      = 'prductcode_map.csv';
/*	$header        = array('Product Id','Manufacture Code'); 
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
			$product_desc = '';
	while (($data = fgetcsv($fp, 20000, ",")) !== FALSE)
	{ 
		if($i!=0) // case of header row
		{
			$product_name     = rmv_double_qt($data[1]);
			$long_description = rmv_double_qt($data[2]);
			$manufacture_id   = trim($data[3]);
			$vendor 		  =  trim($data[4]);
			$exactprice       = trim($data[7]);
			$web_price        = trim($data[12]);
			$variable_values =  trim($data[16]);
			$variable_name   = "Colour";
			
			
			$decription_tab   = ""; 
			$decription_tab   .= "<table border=0 cellspacing=0 cellpadding=0  width=100%>";
			$decription_tab  .= "<tr><td class='desc_left'>RRP</td><td class='desc_middle'>&nbsp;:&nbsp;</td><td class='desc_right'>$data[6]</td></tr>";

			$decription_tab  .= "<tr><td class='desc_left'>Max Speed (mph)</td><td class='desc_middle'>&nbsp;:&nbsp;</td><td class='desc_right'>$data[13]</td></tr>";
			$decription_tab  .= "<tr><td class='desc_left'>Max Range (miles)</td><td class='desc_middle'>&nbsp;:&nbsp;</td><td class='desc_right'>$data[14]</td></tr>";
			$decription_tab  .= "<tr><td class='desc_left'>Max User Weight (stone)</td><td class='desc_middle'>&nbsp;:&nbsp;</td><td class='desc_right'>$data[15]</td></tr>";
			$decription_tab  .= "<tr><td class='desc_left'>Number of wheels</td><td class='desc_middle'>&nbsp;:&nbsp;</td><td class='desc_right'>$data[17]</td></tr>";
			$decription_tab  .= "<tr><td class='desc_left'>Tyres (Solid/Pnuematicc)</td><td class='desc_middle'>&nbsp;:&nbsp;</td><td class='desc_right'>$data[18]</td></tr>";
			$decription_tab  .= "<tr><td class='desc_left'>Heaviest Part (kg)</td><td class='desc_middle'>&nbsp;:&nbsp;</td><td class='desc_right'>$data[19]</td></tr>";
			$decription_tab  .= "<tr><td class='desc_left'>Battery Size Ah</td><td class='desc_middle'>&nbsp;:&nbsp;</td><td class='desc_right'>$data[20]</td></tr>";
			$decription_tab  .= "<tr><td class='desc_left'>Ground clearance (inches)</td><td class='desc_middle'>&nbsp;:&nbsp;</td><td class='desc_right'>$data[21]</td></tr>";
			$decription_tab  .= "<tr><td class='desc_left'>Turning radius (inches)</td><td class='desc_middle'>&nbsp;:&nbsp;</td><td class='desc_right'>$data[22]</td></tr>";
			$decription_tab  .= "<tr><td class='desc_left'>Width (inches)</td><td class='desc_middle'>&nbsp;:&nbsp;</td><td class='desc_right'>$data[23]</td></tr>";
			if($data[24]!='')
			$decription_tab  .= "<tr><td class='desc_left'>Length (inches)</td><td class='desc_middle'>&nbsp;:&nbsp;</td><td class='desc_right'>$data[24]</td></tr>";
			if($data[25]!='')
			$decription_tab  .= "<tr><td class='desc_left'>Folded Length (inches)</td><td class='desc_middle'>&nbsp;:&nbsp;</td><td class='desc_right'>$data[25]</td></tr>";
			if($data[26]!='')
			$decription_tab  .= "<tr><td class='desc_left'>Folded Width (inches)</td><td class='desc_middle'>&nbsp;:&nbsp;</td><td class='desc_right'>$data[26]</td></tr>";
			if($data[27]!='')
			$decription_tab  .= "<tr><td class='desc_left'>Folded Height (inches)</td><td class='desc_middle'>&nbsp;:&nbsp;</td><td class='desc_right'>$data[27]</td></tr>";
			if($data[28]!='')
			$decription_tab  .= "<tr><td class='desc_left'>Tiller Type</td><td class='desc_middle'>&nbsp;:&nbsp;</td><td class='desc_right'>$data[28]</td></tr>";
			if($data[29]!='')
			$decription_tab  .= "<tr><td class='desc_left'>Suspension</td><td class='desc_middle'>&nbsp;:&nbsp;</td><td class='desc_right'>$data[29]</td></tr>";
			 if($data[30]!='')

			$decription_tab  .= "<tr><td class='desc_left'>Swivel Seat</td><td class='desc_middle'>&nbsp;:&nbsp;</td><td class='desc_right'>$data[30]</td></tr>";
			if($data[31]!='')
			$decription_tab  .= "<tr><td class='desc_left'>Light</td><td class='desc_middle'>&nbsp;:&nbsp;</td><td class='desc_right'>$data[31]</td></tr>";
			if($data[32]!='')
			$decription_tab  .= "<tr><td class='desc_left'>On/Off-board charging</td><td class='desc_middle'>&nbsp;:&nbsp;</td><td class='desc_right'>$data[32]</td></tr>";
			if($data[33]!='')
			$decription_tab  .= "<tr><td class='desc_left'>Portable</td><td class='desc_middle'>&nbsp;:&nbsp;</td><td class='desc_right'>$data[33]</td></tr>";
			if($data[34]!='')
			$decription_tab  .= "<tr><td class='desc_left'>Web Weight (kg)</td><td class='desc_middle'>&nbsp;:&nbsp;</td><td class='desc_right'>$data[34]</td></tr>";
			if($data[35]!='')
			$decription_tab  .= "<tr><td class='desc_left'>Shipping Weight</td><td class='desc_middle'>&nbsp;:&nbsp;</td><td class='desc_right'>$data[35]</td></tr>";
			if($data[36]!='')
			$decription_tab  .= "<tr><td class='desc_left'>Shipping Size (S,L)</td><td class='desc_middle'>&nbsp;:&nbsp;</td><td class='desc_right'>$data[36]</td></tr>";
			if($data[39]!='')
			$decription_tab  .= "<tr><td class='desc_left'>Engineered Delivery</td><td class='desc_middle'>&nbsp;:&nbsp;</td><td class='desc_right'>$data[39]</td></tr>";
			if($data[40]!='')
			$decription_tab  .= "<tr><td class='desc_left'>Vatable</td><td class='desc_middle'>&nbsp;:&nbsp;</td><td class='desc_right'>$data[40]</td></tr>";
			$decription_tab  .= "</table>";
			//echo $decription_tab."<br>";			
			
			$sql_products = "SELECT product_id,product_name FROM products WHERE product_name='$product_name' AND sites_site_id=$siteid LIMIT 1"; 
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
			$var_exists = 0;
			if(count($variable_array)>0)
			{
			  foreach($variable_array as $k=>$v)
			  {
				  if($v!='')
				  {
				   $var_exists = 1;
				   $insert_array = array();
				   $insert_array['products_product_id'] = $product_id;
				   $insert_array['var_name'] 			= $k;
				   $insert_array['var_value_exists'] 	= 1;

				   $db->insert_from_array($insert_array,'product_variables');
				   $variable_id = $db->insert_id();
				   $var_array   = array();
				   $var_array   = explode(',',$v);	
					   if(count($var_array)>0)
					   {
						 foreach($var_array as $k=>$v)
						 {
						  
							$insert_array = array();
							$insert_array['product_variables_var_id'] = $variable_id;
							$insert_array['var_value'] = $v;	
							$db->insert_from_array($insert_array,'product_variable_data');		   
						 }	

					  }
			   }	
			  
			  }
			}
			if($var_exists==1)
			{
			$update_array					= array();
			$update_array['product_variables_exists']	= 'Y';
			$db->update_from_array($update_array,'products',array('product_id'=>$product_id));
			} 	
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
	
