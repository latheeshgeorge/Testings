<?php
include_once('header.php');
	$filename ='garraways.csv';
	$fp = fopen ($filename,'w');
	fwrite($fp,'"Id","Product Name","Sale Price","Category","Tax Applied","Hidden"'."\n");

	// Get the list of customers existing in the source website
	$sql_prod = "SELECT product_id, product_name, product_hide, product_webprice, product_discount, 
					product_discount_enteredasval, product_applytax, product_default_category_id, 
					product_variablecomboprice_allowed
					FROM 
						products
					WHERE 
						sites_site_id = $siteid 
					ORDER By product_name";
	$ret_prod = $db->query($sql_prod);
	
	$i = 1;
	if($db->num_rows($ret_prod))
	{
		while ($row_prod = $db->fetch_array($ret_prod))
		{
			$err_msg 				= '';
			$pid				= (stripslashes($row_prod['product_id']));
			$name				= (stripslashes($row_prod['product_name']));
			$hidden				= (stripslashes($row_prod['product_hide']));
			$webprice			= (stripslashes($row_prod['product_webprice']));
			$discount			= (stripslashes($row_prod['product_discount']));
			$distype			= (stripslashes($row_prod['product_discount_enteredasval']));
			$applytax			= (stripslashes($row_prod['product_applytax']));
			$catid				= (stripslashes($row_prod['product_default_category_id']));
			$vp_allow			= (stripslashes($row_prod['product_variablecomboprice_allowed']));
			
			$price = $disc = 0;
			$catname = '';
			// find the name of the category
			
			$sql_cat = "SELECT category_name FROM product_categories WHERE sites_site_id = $siteid AND 
						category_id ='".$catid."' LIMIT 1";
			$ret_cat = $db->query($sql_cat);
			if($db->num_rows($ret_cat))
			{
				$row_cat  = $db->fetch_array($ret_cat);
				$catname = stripslashes($row_cat['category_name']);
			}
			
			$disc_asval	= $distype;
			$sale_price	= $webprice;
			if($discount>0)
			{
				if($disc_asval==1) // If discount is specified as value
				{
					$sale_price = $webprice - $discount;
					if ($sale_price<0)
					{
						$sale_price = 0;
					}	
						
				}
				else if($disc_asval==2) // Exact
				{
					$sale_price =  $discount;
				}
				else // case if discount is given as percentage
				{
					$sale_price 	= $webprice - ($webprice * $discount/100);
				}
			}			   
			
			if ($applytax=='Y')
			{
				$sale_price = $sale_price + ($sale_price * (20/100));
			}
			
			
			if($sale_price>0)
				$sale_price 		= round($sale_price,2);
			else
				$sale_price			= '0.00';
			$pid				= add_qts($pid);
			$name				= add_qts($name);
			$saleprice			= add_qts($sale_price);
			$category			= add_qts($catname);
			$applytax			= add_qts($applytax);
			$hidden				= add_qts($hidden);
			
			echo "<br>".$pid.','.$name.','.$saleprice.','.$category.','.$applytax.','.$hidden;	
			fwrite($fp,$pid.','.$name.','.$saleprice.','.$category.','.$applytax.','.$hidden."\n");
			$i++;
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
