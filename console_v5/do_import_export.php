<? 
	set_time_limit(0);
	if ($_REQUEST['cur_mod']=='')
	{
		echo 'Invalid Parameter';
		exit;
	}

	include_once("functions/functions.php");
	include('session.php');
	require_once("sites.php");
	require_once("config.php");
	include_once("import_export_variables.php");
	$headers 	= array();
	$data 		= array();
	
	
	
	switch($_REQUEST['mod'])
	{
	 case 'export':
		switch ($_REQUEST['cur_mod'])
		{
			
			// ##############################################################################################
			// Case of Exporting Category Details
			// ##############################################################################################
			case 'export_category': 
				$mod 		= 'export';
				$filename	= 'categories';
				foreach ($cat_field_arr as $k=>$v)
				{
					if (in_array($k,$_REQUEST['export_fields']))
						$headers[] = $v;
				}		
				$field_list = implode(",",$_REQUEST['export_fields']);
				$enter_cat= 1;
				if(count($_REQUEST['sel_category_id'])>0 && $_REQUEST['sel_catgroup_id'])
				{ 
				$sel_cat_ids	= implode(",",$_REQUEST['sel_category_id']);
				//to get the categories form the list
				 $sql_grp_par = "SELECT pc.category_id FROM product_categorygroup_category pcc,product_categories pc WHERE pcc.catgroup_id=".$_REQUEST['sel_catgroup_id']." AND pc.parent_id  IN ($sel_cat_ids) AND pc.category_id=pcc.category_id" ;
				 $ret_grp_par = $db->query($sql_grp_par);
				 if($db->num_rows($ret_grp_par))
					{
					 $enter_cat= 2;
						while ($row_grp_par = $db->fetch_array($ret_grp_par))
						{
							$ext_parent_grp[] = $row_grp_par['category_id'];
						}
						$ext_pargrp_ids 	= implode(",",$ext_parent_grp);
						$add_condition 	= " AND (category_id IN($ext_pargrp_ids)) ";
					}
					else
						{
						 $enter_cat= 2;
							$add_condition .= " AND (category_id IN(-1))"; 
						}
				//
				}
				else
				{
				if(count($_REQUEST['sel_category_id']))
				{
					
					$sel_cat_ids	= implode(",",$_REQUEST['sel_category_id']);
					// Get the list of products under the selected categories
					$sql_cat = "SELECT category_id FROM product_categories WHERE 
								parent_id IN ($sel_cat_ids)";
								//echo $sql_cat;exit;
					$ret_cat = $db->query($sql_cat);
					if($db->num_rows($ret_cat))
					{
					 $enter_cat= 2;
						while ($row_cat = $db->fetch_array($ret_cat))
						{
							$ext_parent[] = $row_cat['category_id'];
						}
						$ext_par_ids 	= implode(",",$ext_parent);
						$add_condition 	= " AND (category_id IN($ext_par_ids) ";
					}
					else
						{
						 $enter_cat= 2;
							$add_condition .= " AND (category_id IN(-1)"; 
						}
				}
				
				
				if($_REQUEST['sel_catgroup_id'])
					{
						// Find the ids of categories which fall under the selected category group
						$sql_cats 	= "SELECT category_id FROM product_categorygroup_category WHERE catgroup_id=".$_REQUEST['sel_catgroup_id'];
						$ret_cats 	= $db->query($sql_cats);
						if($db->num_rows($ret_cats))
						{
							while($row_cats = $db->fetch_array($ret_cats))
							{
								$find_arr[] = $row_cats['category_id'];
							}
							if($enter_cat== 2){
								$add_condition .= " OR category_id IN (".implode(',',$find_arr).") )";
							}
							elseif($enter_cat== 1)
							{
							   $add_condition .= " AND category_id IN (".implode(',',$find_arr).") ";
							}
						}
						else
						{
						  if($enter_cat== 2){
							$add_condition .= " OR category_id IN(-1) )"; 
							}
							elseif($enter_cat== 1)
							{
								$add_condition .= " AND category_id IN(-1) "; 
							}
						}
					}
					else
					{
					   if($enter_cat== 2)
					   {
					    $add_condition .= ")";
					   }
					}
				}//End else	
					if($_REQUEST['ids']!=''){
				$arr_cnt = explode("~",$_REQUEST['ids']);
				}
				if($field_list!=''){
					if(count($arr_cnt)){
						$str = implode(",",$arr_cnt);
						$str  ="(".$str.")";
					// Building the sql
						$sql = "SELECT $field_list FROM product_categories WHERE sites_site_id = $ecom_siteid $add_condition AND category_id IN $str ORDER BY ".$_REQUEST['export_sort'];
					}
					else
					{
						$sql = "SELECT $field_list FROM product_categories WHERE sites_site_id = $ecom_siteid $add_condition ORDER BY ".$_REQUEST['export_sort'];
	
					}
					$ret = $db->query($sql);
				}
				if ($db->num_rows($ret))
				{
					while ($row = $db->fetch_array($ret))
					{	
						$temp = array();
						$cnt = 1;
						foreach ($row as $k=>$v)//filtering the repeated fields due to mysql_fetch_array
						{
							if($cnt%2==0)
							{
								if($_REQUEST['export_output_format'] != 'sql')
								{
									if($k=='category_hide')// case of category hide
										$v = ($v==1)?'Y':'N';
									if($k=='category_shortdescription')// case of category hide
										$v = add_slash($row['category_shortdescription']);
									if($k=='category_paid_description')// case of category hide
										$v = add_slash($row['category_paid_description']);
								}		
								$temp[$k] = stripslashes($v);
							}
							$cnt++;
						}	
						array_push($data,$temp);
					}
				}
			break;
			// ##############################################################################################
			// Case of Exporting Product Details
			// ##############################################################################################
			case 'export_product': 
				$mod 		= 'export';
				$filename	= 'products';
				foreach ($prod_field_arr as $k=>$v)
				{
					if (in_array($k,$_REQUEST['export_fields']))
						$headers[] = $v;
				}		
				// case if any categories selected from the category list box
				if(count($_REQUEST['sel_category_id']))
				{
					$sel_cat_ids	= implode(",",$_REQUEST['sel_category_id']);
					// Get the list of products under the selected categories
					$sql_cat = "SELECT distinct products_product_id FROM product_category_map WHERE 
								product_categories_category_id IN ($sel_cat_ids)";
					$ret_cat = $db->query($sql_cat);
					if($db->num_rows($ret_cat))
					{
						while ($row_cat = $db->fetch_array($ret_cat))
						{
							$ext_prod[] = $row_cat['products_product_id'];
						}
						$ext_prod_ids 	= implode(",",$ext_prod);
						$add_condition 	= " AND product_id IN($ext_prod_ids) ";
					}
					else
					{
					  $add_condition 	= " AND product_id IN(-1) "; 
					}
				}
				// Decide whether special fields selected for exporting. 
				$cat_exists		= false;
				if(count($prod_special_arr))
				{
					$temp 			= array();
					
					for($i=0;$i<count($_REQUEST['export_fields']);$i++)
					{
						if (!array_key_exists($_REQUEST['export_fields'][$i],$prod_special_arr))
						{
							$temp[] = $_REQUEST['export_fields'][$i];
						}
						else
						{
							if ($_REQUEST['export_fields'][$i]=='categories')
								$cat_exists = true;
						}	
					}
					$field_list = implode(",",$temp);
					
				}
				else	
					$field_list = implode(",",$_REQUEST['export_fields']);
					
				// Check whether the special fields are to shown in the header row
				if($_REQUEST['export_output_format'] != 'sql' and $cat_exists)
				{
					if(count($prod_special_arr))
					{
						foreach ($prod_special_arr as $k=>$v)
						{
							$headers[$k] = $v;
						}
					}
					// Getting the ids of product to an array
					
				}	
				if($_REQUEST['ids']!=''){
				$arr_cnt = explode("~",$_REQUEST['ids']);
				}
				if($field_list!=''){
				if(count($arr_cnt)){ // The sql building if the export is from the listing of products.
					$str = implode(",",$arr_cnt);
					$str  ="(".$str.")";
						$prod_arr 		= array();
						$sql_prod 		= "SELECT product_id FROM products WHERE sites_site_id = $ecom_siteid $add_condition AND product_id IN $str ORDER BY ".$_REQUEST['export_sort'];
						$ret_prod 		= $db->query($sql_prod);
						if ($db->num_rows($ret_prod))
						{
							while ($row_prod = $db->fetch_array($ret_prod))
							{
								$prod_arr[] = $row_prod['product_id'];
							}
						}
						$sql = "SELECT $field_list FROM products WHERE sites_site_id = $ecom_siteid $add_condition AND product_id IN $str ORDER BY ".$_REQUEST['export_sort'];
						}
					    else
						{
						$prod_arr 		= array();
						$sql_prod 		= "SELECT product_id FROM products WHERE sites_site_id = $ecom_siteid $add_condition ORDER BY ".$_REQUEST['export_sort'];
						$ret_prod 		= $db->query($sql_prod);
						if ($db->num_rows($ret_prod))
						{
							while ($row_prod = $db->fetch_array($ret_prod))
							{
								$prod_arr[] = $row_prod['product_id'];
							}
						}
						
				// Building the sql
				    $sql = "SELECT $field_list FROM products WHERE sites_site_id = $ecom_siteid $add_condition ORDER BY ".$_REQUEST['export_sort'];
					  }
					 $ret = $db->query($sql);
				     }
				$ii = 0;
				if ($db->num_rows($ret))
				{
					while ($row = $db->fetch_array($ret))
					{	
						$temp = array();
						$cnt = 1;
						foreach ($row as $k=>$v)//filtering the repeated fields due to mysql_fetch_array
						{
							if($cnt%2==0)
							{
								if($_REQUEST['export_output_format'] != 'sql')
								{
									if($k=='product_hide')
										$v = ($v==1)?'Y':'N';
									elseif ($k=='product_discount_enteredasval')
										$v = ($v==1)?'Value':'%';
									elseif ($k=='product_show_cartlink')
										$v = ($v==1)?'Y':'N';
									elseif ($k=='product_show_enquirelink')
										$v = ($v==1)?'Y':'N';
									if($k=='product_shortdesc')// case of category hide
										$v = add_slash($row['product_shortdesc']);
									if($k=='product_longdesc')// case of category hide
										$v = add_slash($row['product_longdesc']);
								}		
								$temp[$k] = stripslashes($v);
								
							}
							$cnt++;
						}	
						// case if categories for products to be picked from database
						$cats_arr = array();
						$cat_str = '';
						if($_REQUEST['export_output_format'] != 'sql' and $cat_exists)
						{
							$sql_cat = "SELECT category_name FROM product_categories a,product_category_map b WHERE 
										a.category_id = b.product_categories_category_id AND b.products_product_id=".$prod_arr[$ii];
							$ret_cat = $db->query($sql_cat);
							if ($db->num_rows($ret_cat))
							{
								while ($row_cat = $db->fetch_array($ret_cat))
								{
									$cats_arr[] = stripslashes($row_cat['category_name']);
								}
								$cat_str = implode(",",$cats_arr);
							}
							// Setting the categories mapped with current product seperated by comma to a single field of array.
							$temp['categories'] = $cat_str;
						}
						$ii++;
						array_push($data,$temp);
					}
				}
			break;
			case 'export_shops': 
			// ##############################################################################################
			// Case of Exporting Product Shops
			// ##############################################################################################
				$mod 		= 'export';
				$filename	= 'product_shops';
				foreach ($shop_field_arr as $k=>$v)
				{
					if (in_array($k,$_REQUEST['export_fields']))
						$headers[] = $v;
				}	
				// Check whether special fields exists. If exists temporarly remove it from the field list	
				if(count($shop_special_arr))
				{
					$temp 			= array();
					$prod_exists	= false;
					for($i=0;$i<count($_REQUEST['export_fields']);$i++)
					{
						if (!array_key_exists($_REQUEST['export_fields'][$i],$shop_special_arr))
						{
							$temp[] = $_REQUEST['export_fields'][$i];
						}
						else
						{
							if ($_REQUEST['export_fields'][$i]=='products')
								$prod_exists = true;
						}	
					}
					$field_list = implode(",",$temp);
				}
				else	
					$field_list = implode(",",$_REQUEST['export_fields']);
				
				// Check whether linked products are to be shown seperated by comma. If yes then add it to show the required headings
				if($_REQUEST['export_output_format'] != 'sql' and $prod_exists)
				{
					if(count($shop_special_arr))
					{
						foreach ($shop_special_arr as $k=>$v)
						{
							$headers[$k] = $v;
						}
					}
				}
				$enter =1;
				if(count($_REQUEST['sel_shop_id'])>0 && $_REQUEST['sel_shopgroup_id'])
				{ 
				$sel_shp_ids	= implode(",",$_REQUEST['sel_shop_id']);
				//to get the categories form the list
				 $sql_grp_par = "SELECT ps.shopbrand_id FROM product_shopbybrand_group_shop_map pss,product_shopbybrand ps WHERE pss.product_shopbybrand_shopbrandgroup_id=".$_REQUEST['sel_shopgroup_id']." AND ps.shopbrand_parent_id  IN ($sel_shp_ids) AND ps.shopbrand_id=pss.product_shopbybrand_shopbrand_id" ;
				 $ret_grp_par = $db->query($sql_grp_par);
				 if($db->num_rows($ret_grp_par))
					{
					 $enter= 2;
						while ($row_grp_par = $db->fetch_array($ret_grp_par))
						{
							$ext_parent_grp[] = $row_grp_par['shopbrand_id'];
						}
						$ext_pargrp_ids 	= implode(",",$ext_parent_grp);
						$add_condition 	= " AND (shopbrand_id IN($ext_pargrp_ids)) ";
					}
					else
						{
						 $enter= 2;
							$add_condition .= " AND (shopbrand_id IN(-1))"; 
						}
				//
				}
				else
				{
				if(count($_REQUEST['sel_shop_id']))
				{
					$enter=2;
					$sel_shop_ids	= implode(",",$_REQUEST['sel_shop_id']);
					// Get the list of products under the selected categories
					$sql_shop = "SELECT shopbrand_id FROM product_shopbybrand WHERE 
								shopbrand_parent_id IN ($sel_shop_ids)";
								//echo $sql_cat;exit;
					$ret_shop = $db->query($sql_shop);
					/*if($db->num_rows($ret_shop))
					{
						while ($row_shop = $db->fetch_array($ret_shop))
						{
							$ext_parent[] = $row_shop['shopbrand_id'];
						}
						$ext_par_ids 	= implode(",",$ext_parent);
						$add_condition 	= " AND shopbrand_id IN($ext_par_ids) ";
					}*/
					$add_condition 	= "AND (shopbrand_parent_id IN($sel_shop_ids) ";
				}
				if(($_REQUEST['sel_shopgroup_id']))
				{
					$sel_shop_id	= $_REQUEST['sel_shopgroup_id'];
					$sql_shopgrp_id ="SELECT  DISTINCT product_shopbybrand_shopbrand_id FROM product_shopbybrand_group_shop_map WHERE product_shopbybrand_shopbrandgroup_id=".$sel_shop_id;
					$ret_shopgrp_id = $db->query($sql_shopgrp_id); 
					if($db->num_rows($ret_shopgrp_id)>0)
					{
					while($row_grp_id = $db->fetch_array($ret_shopgrp_id))
					{
					$grp_ids[] = $row_grp_id['product_shopbybrand_shopbrand_id'];
					}
					// Get the list of products under the selected shops
					   if($enter==2){
						$add_condition 	.= " OR shopbrand_id IN (".implode(',',$grp_ids).")) ";
						}
						elseif($enter==1)
						{
						$add_condition 	.= " AND shopbrand_id IN (".implode(',',$grp_ids).") ";
						}
					}
					else
					{
						if($enter==2){
						$add_condition 	= " OR shopbrand_id IN(-1) ) "; 
						}
						elseif($enter==1)
						{
						  $add_condition 	= " AND shopbrand_id IN(-1) "; 
						 }
					}	
				}
				else
				{	
					if($enter==2)
					{
					  $add_condition 	.= ")";
					}
				
				}
				}
				if($_REQUEST['ids']!=''){
				$arr_cnt = explode("~",$_REQUEST['ids']);
				}
				//Checking for the order fields
				if($field_list!=''){
				if(count($arr_cnt)){ // Query buiding if the export from the listing.
					$str = implode(",",$arr_cnt);
					$str  ="(".$str.")";
					$shop_arr 		= array();
						$sql_prod 		= "SELECT shopbrand_id FROM product_shopbybrand WHERE sites_site_id = $ecom_siteid $add_condition AND shopbrand_id IN $str ORDER BY ".$_REQUEST['export_sort'];
						$ret_prod 		= $db->query($sql_prod);
						if ($db->num_rows($ret_prod))
						{
							while ($row_prod = $db->fetch_array($ret_prod))
							{
								$shop_arr[] = $row_prod['shopbrand_id'];
							}
						}
						// Building the sql
						$sql 	= "SELECT $field_list FROM product_shopbybrand WHERE sites_site_id = $ecom_siteid $add_condition AND shopbrand_id IN $str ORDER BY ".$_REQUEST['export_sort'];
					}
					else
					{
						// Getting the ids of product shops to an array
						$prod_arr 		= array();
						$sql_prod 		= "SELECT shopbrand_id FROM product_shopbybrand WHERE sites_site_id = $ecom_siteid $add_condition ORDER BY ".$_REQUEST['export_sort'];
						$ret_prod 		= $db->query($sql_prod);
						if ($db->num_rows($ret_prod))
						{
							while ($row_prod = $db->fetch_array($ret_prod))
							{
								$shop_arr[] = $row_prod['shopbrand_id'];
							}
						}
						// Building the sql
						$sql 	= "SELECT $field_list FROM product_shopbybrand WHERE sites_site_id = $ecom_siteid $add_condition ORDER BY ".$_REQUEST['export_sort'];
				     }
				$ret 	= $db->query($sql);
				}
				$ii 	= 0;
				if ($db->num_rows($ret))
				{
					while ($row = $db->fetch_array($ret))
					{	
						$temp = array();
						$cnt = 1;
						foreach ($row as $k=>$v)//filtering the repeated fields due to mysql_fetch_array
						{
							if($cnt%2==0)
							{
								if($_REQUEST['export_output_format'] != 'sql')
								{
									if($k=='shopbrand_parent_id' and ($_REQUEST['export_output_format']=='html' or $_REQUEST['export_output_format']=='csv'))
									{
										$sql_shp  = "SELECT shopbrand_name FROM product_shopbybrand WHERE shopbrand_id=$v";
										$ret_shp = $db->query($sql_shp);
										if ($db->num_rows($ret_shp))
										{
											$row_shp = $db->fetch_array($ret_shp);
											$v = stripslashes($row_shp['shopbrand_name']);
										}
										else
											$v = ' -- ';
									}
									elseif($k=='shopbrand_hide')// case of product shop hide
										$v = ($v==1)?'Y':'N';
									elseif($k=='shopbrand_showinall')// case of show in all
										$v = ($v==1)?'Y':'N';
									elseif($k=='shopbrand_product_showimage')// case of show image
										$v = ($v==1)?'Y':'N';
									elseif($k=='shopbrand_product_showtitle')// case of show title
										$v = ($v==1)?'Y':'N';
									elseif($k=='shopbrand_product_showshortdescription')// case of show description
										$v = ($v==1)?'Y':'N';
									elseif($k=='shopbrand_product_showprice')// case of show price
										$v = ($v==1)?'Y':'N';		
									elseif($k=='shopbrand_activateperiodchange')// case of period change
										$v = ($v==1)?'Y':'N';
									elseif($k=='shopbrand_displaystartdate')// case of start date
										$v = ($v==1)?'Y':'N';
									elseif($k=='shopbrand_displayenddate')// case of end date
										$v = ($v==1)?'Y':'N';	
								}		
								$temp[$k] = stripslashes($v);
							}
							$cnt++;
						}	
						$prods_arr = array();
						$prod_str = '';
						if($_REQUEST['export_output_format'] != 'sql' and $prod_exists)// case if products for shops to be picked from database
						{
							$sql_prod = "SELECT product_name FROM products a,product_shopbybrand_product_map b WHERE 
										a.product_id = b.products_product_id AND b.product_shopbybrand_shopbrand_id=".$shop_arr[$ii];
							$ret_prod = $db->query($sql_prod);
							if ($db->num_rows($ret_prod))
							{
								while ($row_prod = $db->fetch_array($ret_prod))
								{
									$prods_arr[] = stripslashes($row_prod['product_name']);
								}
								$prod_str = implode(",",$prods_arr);
							}
							$temp['products'] = $prod_str;
						}
						$ii++;
						array_push($data,$temp);
					}
				}
			break;
			// ##############################################################################################
			// Case of Exporting Customer Details
			// ##############################################################################################
			case 'export_cust': 
				$mod 		= 'export';
				$filename	= 'customers';
				foreach ($cust_field_arr as $k=>$v)
				{
					if (in_array($k,$_REQUEST['export_fields']))
						$headers[] = $v;
				}		
				$headers[] = 'Bonus Points';
				$field_list = implode(",",$_REQUEST['export_fields']);
				// Building the sql
				if($_REQUEST['ids']!=''){
				$arr_cnt = explode("~",$_REQUEST['ids']);
				}
				//Checking for the order fields
				if($field_list!=''){
				if(count($arr_cnt)){ //Query  building if the export from the listing.
					$str = implode(",",$arr_cnt);
					$str  ="(".$str.")";
				   $sql 		= "SELECT $field_list,customer_bonus FROM customers WHERE sites_site_id = $ecom_siteid AND customer_id IN $str ORDER BY ".$_REQUEST['export_sort'];
				}else
				{
				 $sql 		= "SELECT $field_list,customer_bonus FROM customers WHERE sites_site_id = $ecom_siteid  ORDER BY ".$_REQUEST['export_sort'];
				}
				$ret 		= $db->query($sql);
				}
				if ($db->num_rows($ret))
				{
					while ($row = $db->fetch_array($ret))
					{	
						$temp = array();
						$cnt = 1;
						foreach ($row as $k=>$v)//filtering the repeated fields due to mysql_fetch_array
						{
							if($cnt%2==0)
							{
								if($_REQUEST['export_output_format'] != 'sql')
								{
									if($k=='customer_hide')// case of customer hide
										$v = ($v==1)?'Y':'N';
									if($k=='customer_in_mailing_list')// case of mailing list customer
										$v = ($v==1)?'Y':'N';
									if($k=='customer_addedon')// case of customer_addedon
									{
										if(trim($v))
										{
											if(trim($v)!='0000-00-00')
											{
												$mon_arr= array('01'=>'Jan','02'=>'Feb','03'=>'Mar','04'=>'Apr','05'=>'May','06'=>'Jun','07'=>'Jul','08'=>'Aug','09'=>'Sep','10'=>'Oct','11'=>'Nov','12'=>'Dec');
												$add_date = explode('-',$v);
												$v = $add_date[2].'-'.$mon_arr[trim($add_date[1])].'-'.$add_date[0];
											}
											else
											{
												$v = '';
											}	
											//$v = ($v==1)?'Y':'N';		
										}
									}	
								  /* if($k=='customer_statecounty'){// case of customer hide
										if($row['customer_statecounty']){
												$sql_state = "SELECT state_name FROM general_settings_site_state WHERE sites_site_id=$ecom_siteid AND state_id=".$row['customer_statecounty']."";		
												$ret_state = $db->query($sql_state);
												if($db->num_rows($ret_state)>0){
													$row_state = $db->fetch_array($ret_state);
													$v = $row_state['state_name'];
													}
												}
										}*/
								}		
								$temp[$k] = stripslashes($v);
							}
							$cnt++;
						}	
						array_push($data,$temp);
					}
				}
			break;
			// ##############################################################################################
			// Case of Exporting Customer Details
			// ##############################################################################################
			case 'export_newscust': 
				$mod 		= 'export';
				$filename	= 'newsletter_customers';
				foreach ($newscust_field_arr as $k=>$v)
				{
					if (in_array($k,$_REQUEST['export_fields']))
						$headers[] = $v;
				}		
				$field_list = implode(",",$_REQUEST['export_fields']);
				// Building the sql
				if($_REQUEST['ids']!=''){
				$arr_cnt = explode("~",$_REQUEST['ids']);
				}
				//Checking for the order fields
				if($field_list!=''){
				if(count($arr_cnt)){ //Query  building if the export from the listing.
					$str = implode(",",$arr_cnt);
					$str  ="(".$str.")";
				   $sql 		= "SELECT $field_list FROM newsletter_customers WHERE sites_site_id = $ecom_siteid AND news_customer_id IN $str ORDER BY ".$_REQUEST['export_sort'];
				}else
				{
				 $sql 		= "SELECT $field_list FROM newsletter_customers WHERE sites_site_id = $ecom_siteid  ORDER BY ".$_REQUEST['export_sort'];
				}
				$ret 		= $db->query($sql);
				}
				if ($db->num_rows($ret))
				{
					while ($row = $db->fetch_array($ret))
					{	
						$temp = array();
						$cnt = 1;
						foreach ($row as $k=>$v)//filtering the repeated fields due to mysql_fetch_array
						{
							if($cnt%2==0)
							{
								if($_REQUEST['export_output_format'] != 'sql')
								{
									if($k=='news_custhide')// case of customer hide
										$v = ($v==1)?'Y':'N';
									
								}		
								$temp[$k] = stripslashes($v);
							}
							$cnt++;
						}	
						array_push($data,$temp);
					}
				}
			break;
			
			// ##############################################################################################
			// Case of Exporting order Details
			// ##############################################################################################
			case 'export_order':
		
				$mod 		= 'export';
				$filename	= 'orders';
				$cnt_del=0;
				$cnt_ord=0;
				$cnt_gft =0;
				foreach ($order_field_arr as $k=>$v)
				{
					if (in_array($k,$_REQUEST['export_fields']))
						$headers[] = $v;
					if($k=='promotional_code_code_number')
					{
						$headers[] = 'Promotional Code Discount Value';
					}
					if($k=='order_gift_voucher_number')
					{
						$headers[] = 'Gift Voucher Discount Value';
					}	
				}		
				//$field_list = implode(",",$_REQUEST['export_fields']);
				$prod_exists		= false;
				$field_list = implode(",",$_REQUEST['export_fields']);
				if(count($order_special_arr))
				{
					$temp 			= array();
					
					for($i=0;$i<count($_REQUEST['export_fields']);$i++)
					{
						if (!array_key_exists($_REQUEST['export_fields'][$i],$order_special_arr))
						{
							$temp[] = $_REQUEST['export_fields'][$i];
						}
						else
						{
							if ($_REQUEST['export_fields'][$i]=='products_order')
								$prod_exists = true;
						}	
					}
					$field_list = implode(",",$temp);
					
				}
				
				
				// Check whether the special fields are to shown in the header row
				if($_REQUEST['export_output_format'] != 'sql' and $prod_exists)
				{
					if(count($order_special_arr))
					{
						foreach ($order_special_arr as $k=>$v)
						{
							$headers[$k] = $v;
						}
					}
				}
				$gift_exists		= false;
				if(count($order_special_gift_arr))
				{
					$temp 			= array();
					for($i=0;$i<count($_REQUEST['export_fields']);$i++)
					{
						if (!array_key_exists($_REQUEST['export_fields'][$i],$order_special_gift_arr))
						{
							$temp[] = $_REQUEST['export_fields'][$i];
						}
						else
						{
							if ($_REQUEST['export_fields'][$i]=='gift_wrap')
								$gift_exists = true;
						}	
					}
					$field_list = implode(",",$temp);
					
				}
				
				
				// Check whether the special fields are to shown in the header row
				if($_REQUEST['export_output_format'] != 'sql' and $gift_exists)
				{
					if(count($order_special_gift_arr))
					{
						foreach ($order_special_gift_arr as $k=>$v)
						{
							$headers[$k] = $v;
						}
					}
					if(count($order_gift_arr))
					{
						foreach ($order_gift_arr as $k=>$v)
						{
							$fields[] = $k;
						}
					}
					$field_gift_list = implode(',',$fields);	
				}	
				
					
				foreach($_REQUEST['export_fields'] as $v)
					{
						 if(substr($v,0,5)=='order' OR substr($v,0,11)=='promotional')
						 {
							$cnt_ord++;
						  	if($v!=''){
								if($cnt_ord>1)
								{
									$field_order_list .=",";
								}
								$field_order_list .=$v;
						  	}
						 }
						 elseif(substr($v,0,8)=='delivery')
						 {
							   $cnt_del++;
							   if($v!='')
							   {
									if($cnt_del>1){
									$field_delivery_list .=",";
								}
									$field_delivery_list .=$v;
						  }
						  
						 }
						 
					}
					
				// Building the sql
				if($_REQUEST['ids']!=''){
				$arr_cnt = explode("~",$_REQUEST['ids']);
				}
				//Checking for the order fields
				if($field_order_list!=''){
				if(count($arr_cnt)){ //Query building if the export from the listing.
				$str = implode(",",$arr_cnt);
				$str  ="(".$str.")";
				$sql 		= "SELECT $field_order_list FROM orders WHERE sites_site_id = $ecom_siteid  AND order_id IN $str ORDER BY ".$_REQUEST['export_sort'];
				}
				else
				{
				//$sql 		= "SELECT $field_order_list FROM orders WHERE order_status NOT IN ('CANCELLED','NOT_AUTH') AND sites_site_id = $ecom_siteid";  
				
				if ($ecom_siteid==70)
				{
					$sql 		= "SELECT $field_order_list FROM orders WHERE order_status NOT IN ('CANCELLED') AND sites_site_id = $ecom_siteid";  
				}
				else
				{
					$sql 		= "SELECT $field_order_list FROM orders WHERE order_status NOT IN ('CANCELLED','NOT_AUTH') AND sites_site_id = $ecom_siteid";  
				}
				
				
				$from_date 	= add_slash($_REQUEST['ord_fromdate']);
				$to_date 	= add_slash($_REQUEST['ord_todate']);
				$valid_fromdate = is_valid_date($from_date,'normal','-');
						$valid_todate	= is_valid_date($to_date,'normal','-');
						if($valid_fromdate)
						{
							$frm_arr 		= explode('-',$from_date);
							$mysql_fromdate = $frm_arr[2].'-'.$frm_arr[1].'-'.$frm_arr[0]; 
						}
						else// case of invalid from date
							$_REQUEST['ord_fromdate'] = '';
							
						if($valid_todate)
						{
							$to_arr 		= explode('-',$to_date);
							$mysql_todate 	= $to_arr[2].'-'.$to_arr[1].'-'.$to_arr[0]; 
						}
						else // case of invalid to date
							$_REQUEST['ord_todate'] = '';
						if($valid_fromdate and $valid_todate)// both dates are valid
						{
							$sql .= " AND (order_date BETWEEN '".$mysql_fromdate." 00:00:00' AND '".$mysql_todate." 23:59:59') ";
							$disp_more = true; 
						}
						elseif($valid_fromdate and !$valid_todate) // only from date is valid
						{
							$sql .= " AND order_date >= '".$mysql_fromdate." 00:00:00' ";
							$disp_more = true; 
						}
						elseif(!$valid_fromdate and $valid_todate) // only to date is valid
						{
							$sql .= " AND order_date <= '".$mysql_todate." 23:59:59' ";
							$disp_more = true; 
						}
									// Check whether from and to dates are valid
					if($_REQUEST['export_sort'])
					{
					$sql .=" ORDER BY ".$_REQUEST['export_sort'];	
					}
					
					//if($ecom_siteid==72)
					//echo $sql;
				}
				$ret 		= $db->query($sql);
				}
				if ($db->num_rows($ret))
				{     $ii =0;
					while ($row = $db->fetch_array($ret))
					{	
						
						$temp = array();
						$cnt = 1;
						foreach ($row as $k=>$v)//filtering the repeated fields due to mysql_fetch_array
						{
							if($cnt%2==0)
							{
								if($_REQUEST['export_output_format'] != 'sql')
								{
									if($k=='order_pre_order')// case of preorder
										$v = ($v==1)?'Y':'N';
										if($k=='order_status')// case of preorder
										$v = getorderstatus_Name($row['order_status'],true);
										if($k=='order_paymenttype')// case of preorder
										$v = getpaymenttype_Name($row['order_paymenttype']);
										if($k=='order_paystatus')// case of preorder
										$v = getpaymentstatus_Name($row['order_paystatus']);
										if($k=='order_paymentmethod')// case of preorder
										$v = getpaymentmethod_Name($row['order_paymentmethod']);
										if($k=='order_date')// case of preorder
										$v = dateFormat($row['order_date'],'');
									
									if($k=='order_custemail' or $k=='order_custphone' or $k=='order_custmobile')
									{
										$v = hide_export_fields($v);
									}
										
								}		
								$temp[$k] = $v;
								
								
									
							}
							$cnt++;
						}	
						$hold_temp = array();
						foreach ($temp as $k=>$v)//filtering the repeated fields due to mysql_fetch_array
						{
							//echo "<br>".$k.' v--'.$v;
							$hold_temp[$k]=$v;
							if($k=='promotional_code_code_number')
							{
								if($v!='')
								{
									// Get the promotional code discount value from the table order_promotional_code
									$sql_ordprm = "SELECT code_lessval 
													FROM 
														order_promotional_code 
													WHERE 
														orders_order_id=".$row['order_id']." 
														AND code_number='".$v."' 
													LIMIT 
														1";
									$ret_ordprm = $db->query($sql_ordprm);
									if($db->num_rows($ret_ordprm))
									{
										$row_ordprm = $db->fetch_array($ret_ordprm);
										$hold_temp['promotional_code_discount'] = $row_ordprm['code_lessval'];
									}
									else
									{
										$hold_temp['promotional_code_discount']='';
									}
								}
								else
								{
									$hold_temp['promotional_code_discount']='';
								}	
							}
							if($k=='order_gift_voucher_number')
							{
								if($v!='')
								{
									// Get the gift vocher value from the table order_voucher
									$sql_ordprm = "SELECT voucher_value_used 
													FROM 
														order_voucher 
													WHERE 
														orders_order_id=".$row['order_id']." 
														AND voucher_no='".$v."' 
													LIMIT 
														1";
									$ret_ordprm = $db->query($sql_ordprm);
									if($db->num_rows($ret_ordprm))
									{
										$row_ordprm = $db->fetch_array($ret_ordprm);
										$hold_temp['order_gift_voucher_discount'] = $row_ordprm['voucher_value_used'];
									}
									else
									{
										$hold_temp['order_gift_voucher_discount']='';
									}
								}
								else
								{
									$hold_temp['order_gift_voucher_discount']='';
								}
							}
							
						}
						$temp = $hold_temp;
						//print_r($temp);
					$res =array();
					if($row['order_id'])//case of orderid exists
					{	
					   if($field_delivery_list!=''){//Gets the delivery details section.
					   $sql_del		= "SELECT $field_delivery_list FROM order_delivery_data WHERE orders_order_id=".$row['order_id']." LIMIT 1";
	                   $ret_del		= $db->query($sql_del);
						if ($db->num_rows($ret_del))
						{
							while ($row_del = $db->fetch_array($ret_del))
							{
								$temp_del = array();
								$cnt_del = 1;
								foreach ($row_del as $k=>$v)//filtering the repeated fields due to mysql_fetch_array
								{
									if($cnt_del%2==0)
									{
										if($_REQUEST['export_output_format'] != 'sql')
										{
											if($k=='delivery_same_as_billing')// case of preorder
												$v = ($v==1)?'Y':'N';
											
											if($k=='delivery_phone' or $k=='delivery_mobile' or $k =='delivery_email')
											{
												$v = hide_export_fields($v);
											}	
										}		
										$temp[$k] = $v;
									}
									$cnt_del++;
								}
							}
					    }
						else
						{
						 for($i=0;$i<$cnt_del;$i++)
						  {
						  $temp[$i] = "--";
						  }
						}
					  }
					  $row_prod_arrys_str ='';
					  $row_prod_str = '';
					  $row_prod_arrys = array();
					  $row_field_list = array();
					   if($_REQUEST['export_output_format'] != 'sql' and $prod_exists)// case if products for order picked from database
						{
						  foreach($order_product_arr as $k=>$v)
							{
							  $order_prod_name[] = $v;
							  $order_prod_arr[]=$k;
							}
							$order_product_list = implode(',',$order_prod_arr);
							$sql_prods = "SELECT $order_product_list,orderdet_id 
									FROM 
										order_details 
									WHERE 
										orders_order_id = ".$row['order_id']."";
							$ret_prods = $db->query($sql_prods);
						  if($db->num_rows($ret_prods)>0)
							{
								$cnt_prods=0;
									while($row_prods = $db->fetch_array($ret_prods))
									{
										$row_field_list = array();
										$row_field_list[$cnt_prods]=$row_prods['product_name'];
										$cnt_prods ++;
										$sql_var = "SELECT var_name,var_value
										FROM 
											order_details_variables 
										WHERE 
											orders_order_id = ".$row['order_id']."
										AND order_details_orderdet_id =".$row_prods['orderdet_id'];
							
										$ret_var = $db->query($sql_var);
										$cnt_vars = $cnt_prods;//1;
										if ($db->num_rows($ret_var))
											{
												while ($row_var = $db->fetch_array($ret_var))
												{ 
													//$cnt_vars++;
													$row_field_list[$cnt_vars]= stripslashes($row_var['var_name']).': '.stripslashes($row_var['var_value']);
														$cnt_vars++;
												}
											}
										$row_prod_str = implode(',',$row_field_list);
										$row_prod_arrys[$cnt_prods]=$row_prod_str." x ".$row_prods['order_orgqty'] .'(qty)';
									}
									$row_prod_arrys_str = implode('~',$row_prod_arrys);
									$temp['products_order'] = $row_prod_arrys_str;
								}
							}
							$row_gfield_list = array();
							$row_gift_arrys_str ='';
							if($_REQUEST['export_output_format'] != 'sql' and $gift_exists)// case if products for order picked from database
							{	
								$sql_gift		= "SELECT $field_gift_list,id FROM order_giftwrap_details WHERE orders_order_id=".$row['order_id'];
								$ret_gift		= $db->query($sql_gift);
								if ($db->num_rows($ret_gift))
								{ 
									$cnt_gfts=0;
									while ($row_gift = $db->fetch_array($ret_gift))
									{
										 $id= $row_gift['id'];
										 $cnt_gfts++;
										 $row_gfield_list[$cnt_gfts]=$row_gift['giftwrap_name'].",".$row_gift['giftwrap_price'];
									}  
									  $row_gift_arrys_str = implode('~',$row_gfield_list);
									  $temp['gift_wrap'] = $row_gift_arrys_str;
								}
							}	
						}
						$ii++;
						array_push($data,$temp);
					  }
					}
			break;
			case 'export_giftvoucher': 
				$mod 		= 'export';
				$filename	= 'giftvouchers';
				foreach ($giftvoucher_field_arr as $k=>$v)
				{
					if (in_array($k,$_REQUEST['export_fields']))
						$headers[] = $v;
				}
				$field_list = implode(",",$_REQUEST['export_fields']);
				$cust_exists =false;
									
									if(count($giftvoucher_special_arr))
									{
										$temp 			= array();
										for($i=0;$i<count($_REQUEST['export_fields']);$i++)
										{
											if (!array_key_exists($_REQUEST['export_fields'][$i],$giftvoucher_special_arr))
											{
												$temp[] = $_REQUEST['export_fields'][$i];
											}
											else
											{
												if ($_REQUEST['export_fields'][$i]=='giftvoucher_customer')
													$cust_exists = true;
											}	
										}
										$field_list = implode(",",$temp);
										
									}
									
									
									// Check whether the special fields are to shown in the header row
									if($_REQUEST['export_output_format'] != 'sql' and $cust_exists)
									{
										if(count($giftvoucher_special_cust_arr))
										{
											
											foreach ($giftvoucher_special_cust_arr as $k=>$v)
											{
												$headers[$k] = $v;
											}
										}
										if(count($giftvoucher_special_cust_arr))
										{
											foreach ($giftvoucher_special_cust_arr as $k=>$v)
											{
												$fields[] = $k;
											}
											
										}
										$field_cust_list = implode(',',$fields);	
									}	
				
				
				// Building the sql
				//voucher type
				// Section from the voucher listing page.
				if($_REQUEST['ids']!=''){
				$arr_cnt = explode("~",$_REQUEST['ids']);
				}
				//Checking for the order fields
				if($field_list!=''){
				if(count($arr_cnt)){
					$str = implode(",",$arr_cnt);
					$str  ="(".$str.")";
					$sql 			= "SELECT $field_list FROM gift_vouchers WHERE sites_site_id = $ecom_siteid  AND voucher_id IN $str ORDER BY ".$_REQUEST['export_sort'];
					$sql_type 		= "SELECT voucher_type,voucher_id FROM gift_vouchers WHERE sites_site_id = $ecom_siteid AND voucher_id IN $str ORDER BY ".$_REQUEST['export_sort'];
					$ret_type 		= $db->query($sql_type);
							while($row_type = $db->fetch_array($ret_type))
							{
							  $type_arr[] = $row_type['voucher_type'];
							  $voucher_id[] = $row_type['voucher_id'];
							}
					}
					else
					{
							$sql_type 		= "SELECT voucher_type,voucher_id FROM gift_vouchers WHERE sites_site_id = $ecom_siteid ORDER BY ".$_REQUEST['export_sort'];
							$ret_type 		= $db->query($sql_type);
							while($row_type = $db->fetch_array($ret_type))
							{
							  $type_arr[] = $row_type['voucher_type'];
							  $voucher_id[] = $row_type['voucher_id'];
							}
							
							$sql 		= "SELECT $field_list FROM gift_vouchers WHERE sites_site_id = $ecom_siteid  ORDER BY ".$_REQUEST['export_sort'];
					}
				$ret 		= $db->query($sql);
			  }	
				if ($db->num_rows($ret))
				{   $type_cnt =0;
					while ($row = $db->fetch_array($ret))
					{	
					    
						$temp = array();
						$cnt = 1;
						foreach ($row as $k=>$v)//filtering the repeated fields due to mysql_fetch_array
						{
							if($cnt%2==0)
							{  
								if($_REQUEST['export_output_format'] != 'sql')
								{
									if($k=='voucher_hide')// case of customer hide
										$v = ($v==1)?'Y':'N';
										if($k=='voucher_boughton')// case of customer hide
										$v = dateFormat($row['voucher_boughton'],'');
										if($k=='voucher_expireson')// case of customer hide
										$v = dateFormat($row['voucher_expireson'],'');
										if($k=='voucher_createdby')// case of customer hide
										$v = ($row['voucher_createdby']=='A')?'Admin':'Customer';
										if($k=='voucher_paystatus')// case of customer hide
										$v = getpaymentstatus_Name($row['voucher_paystatus'],'');
										
										if($k=='voucher_value')// case of customer hide
										{
											if($type_arr[$type_cnt]=='val')
											{
											   $v=display_price($row['voucher_value']);
											}
											else
											$v = $row['voucher_value'] ."%";
										}
										
								}		
								$temp[$k] = $v;
							}
							$cnt++;
						}
						if($field_cust_list!=''){
						$sql_cust 		= "SELECT $field_cust_list FROM gift_vouchers_customer WHERE voucher_id =".$voucher_id[$type_cnt] ;
						$ret_cust		= $db->query($sql_cust);
						}
						$cnt_cust = 1;
						if($db->num_rows($ret_cust)>0)
						{   $row_cust 		= $db->fetch_array($ret_cust);
							foreach($row_cust as $k=>$v)
							{
								if($cnt_cust%2==0)
								{ 
								  $temp[$k] = $v;
								}
								$cnt_cust++;
							}
						}
						$type_cnt++;
						array_push($data,$temp);
					}// End While
				}
			break;
		}
      break;
	  case 'import':
	  if($_REQUEST['import_Submit']){
				$name   	= $_FILES["file_import"]["name"];
				$arr		= explode('.',$name);
				$arr_cnt	= count($arr);
				//print_r($arr[$arr_cnt-1]);exit;
				if(strtolower($arr[$arr_cnt-1])!='csv')
					{   
					     		$alert=1;
								header('Location:home.php?request=import_export&alert='.$alert.'');
					}
	             	elseif($_FILES['file_import']['type']=='text/csv' || $_FILES["file_import"]["type"]=='application/vnd.ms-excel' || $_FILES["file_import"]["type"]=='application/octet-stream' || $_FILES["file_import"]["type"]=='text/comma-separated-valuestest')
					{

					switch ($_REQUEST['cur_mod'])
						{
							case 'import_cust':
									$fs_check = filesize($_FILES["file_import"]["tmp_name"]);
									$fh_check = fopen($_FILES["file_import"]["tmp_name"], "r");
									$row_check = fgetcsv($fh_check, $fs_check);
											if(count($row_check)!=16)
												{
												  $alert =2;
												  header('Location:home.php?request=import_export&alert='.$alert.'&import_what=cust');
												}
												fclose($fh_check);
									$fs = filesize($_FILES["file_import"]["tmp_name"]);
									$fh = fopen($_FILES["file_import"]["tmp_name"], "r");
									 if(!$fh)
									 {
										$alert =8;
										header('Location:home.php?request=import_export&alert='.$alert.'&import_what=cust');

									 } 
									 else
									 { 
											if($_REQUEST['header_include']=='on'){
											fgets($fh, $fs);
											}
											$cnt=1;
											while($row = fgetcsv($fh, $fs))
											{
												$cnt++;
												$cust_file=array();	
												if($row['0']==''){
												 $alert[$cnt] ="Error!!!! Customer title is empty.<br>";
												}
												if($row['1']==''){
												 $alert[$cnt] .="Error!!!!  Customer First name is empty.<br>";
												}
												if($row['13']==''){
												 $alert[$cnt] .="Error!!!!  Customer Email id is empty.<br>";
												}
												$email_id= $row['13'];
												if($row['13']){
												if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $email_id)) 
													{
													   $alert[$cnt] .= "Enter a Valid Email Address<br>";
													}
												}
												$sql_check = "SELECT count(*) as cnt FROM customers WHERE customer_email_7503 = '".add_slash($email_id)."' AND sites_site_id=$ecom_siteid";
												$res_check = $db->query($sql_check);
												$row_check = $db->fetch_array($res_check);
						
												if($row_check['cnt'] > 0)
													$alert[$cnt] .= "Error!!! Email Already exists.";
											}
											fclose($fh);	
											$fs1 = filesize($_FILES["file_import"]["tmp_name"]);
											$fh1 = fopen($_FILES["file_import"]["tmp_name"], "r");	
											if($_REQUEST['header_include']=='on'){
											fgets($fh1, $fs1);
											}		
												while($row1 = fgetcsv($fh1, $fs1))
												{
														if(!$alert) 
														{
															$insert_array = array();
															//if($row1['0']=='Mr') {$row1['0']='Mr.';}elseif($row1['0']=='Ms'){ $row1['0']='Ms.';}elseif($row1['0']=='Mrs'){ $row1['0']='Mrs.';}elseif($row1['0']=='M/s'){ $row1['0']='M/s.';}else {$row1['0']='Mr.';};
															if($row1['0']=='Mr') {$row1['0']='Mr.';}elseif($row1['0']=='Ms'){ $row1['0']='Ms.';}elseif($row1['0']=='Mrs'){ $row1['0']='Mrs.';}elseif($row1['0']=='M/s'){ $row1['0']='M/s.';}elseif($row1['0']=='Dr'){ $row1['0']='Dr.';}elseif($row1['0']=='Sir'){ $row1['0']='Sir.';}elseif($row1['0']=='Rev'){ $row1['0']='Rev.';}elseif($row1['0']=='Miss'){ $row1['0']='Miss.';}else {$row1['0']='Mr.';};
																											
															$insert_array['customer_title'] 				=$row1['0'];
															$insert_array['customer_fname']					=add_slash(html_entity($row1['1']));
															$insert_array['customer_mname']					=add_slash(html_entity($row1['2']));
															$insert_array['customer_surname']				=add_slash(html_entity($row1['3']));
															$insert_array['customer_position']				=add_slash(html_entity($row1['4']));
															$insert_array['customer_buildingname']			=add_slash(html_entity($row1['5']));
															$insert_array['customer_streetname']			=add_slash(html_entity($row1['6']));
															$insert_array['customer_towncity']				=add_slash(html_entity($row1['7']));
															$insert_array['customer_statecounty']			=add_slash(html_entity($row1['8']));
															$insert_array['customer_phone']					=add_slash(html_entity($row1['9']));
															$insert_array['customer_fax']					=add_slash(html_entity($row1['10']));
															$insert_array['customer_mobile']				=add_slash(html_entity($row1['11']));
															$insert_array['customer_postcode']				=add_slash(html_entity($row1['12']));
															$insert_array['customer_email_7503']			=add_slash(html_entity($row1['13']));
															$insert_array['customer_pwd_9501']				=base64_encode(add_slash($row1['1']));
															$insert_array['customer_addedon']				='curdate()';
															$insert_array['customer_hide']					=($row1['14']=='Y')?1:0;
															$insert_array['customer_in_mailing_list']		=($row1['15']=='Y')?1:0;
															$insert_array['sites_site_id']					=$ecom_siteid;
															$db->insert_from_array($insert_array, 'customers');
															$insert_id = $db->insert_id();
															// Check whether there already exists a customer with same email id in newsletter table for current website
															$sql_check = "SELECT news_customer_id 
																			FROM 
																				newsletter_customers 
																			WHERE 
																				sites_site_id=$ecom_siteid 
																				AND news_custemail='".add_slash(html_entity($row1['13']))."'
																			LIMIT 
																				1";
															$ret_check = $db->query($sql_check);
															if($db->num_rows($ret_check))
															{
																$row_check = $db->fetch_array($ret_check);
																// case if entry already exists, then updating various fields
																$update_array 					= array();
																$update_array['news_title']		= $row1['0'];
																$update_array['news_custname']	= add_slash(html_entity($row1['1'])).' '.add_slash(html_entity($row1['2'])).' '.add_slash(html_entity($row1['3']));
																$update_array['news_custphone']	= add_slash(html_entity($row1['9']));
																$update_array['customer_id']	= $insert_id;
																$db->update_from_array($update_array,'newsletter_customers',array('news_customer_id'=>$row_check['news_customer_id']));
																
																//Update the customers table to make the field customer_in_mailing_list to 1
																$sql_update = "UPDATE customers 
																				SET 
																					customer_in_mailing_list=1 
																				WHERE 
																					customer_id = $insert_id 
																					AND sites_site_id = $ecom_siteid 
																				LIMIT 
																					1";
																$ret_update= $db->query($sql_update);
															}
															else // case if entry does not exists
															{
																if ($row1[15]=='Y') // case if mailing list is ticked
																{
																	$insert_array						= array();
																	$insert_array['sites_site_id']		= $ecom_siteid;
																	$insert_array['news_title']			= $row1['0'];
																	$insert_array['news_custname']		= add_slash(html_entity($row1['1'])).' '.add_slash(html_entity($row1['2'])).' '.add_slash(html_entity($row1['3']));
																	$insert_array['news_custemail']		= add_slash(html_entity($row1['13']));
																	$insert_array['news_custphone']		= add_slash(html_entity($row1['9']));
																	$insert_array['news_join_date']		= 'curdate()';
																	$insert_array['customer_id']		= $insert_id;
																	$insert_array['news_custhide']		= 0;
																	$db->insert_from_array($insert_array,'newsletter_customers');
																}	
															}															
															
														}
												}	
													if($insert_id){
															$alert =3;
															header('Location:home.php?request=import_export&alert='.$alert.'');
															}
															fclose($fh1);	
												// Calling function to show the error messages if any
												show_errors($cnt,$alert,'home.php?request=import_export&import_what=cust');
										}					
							break;
							
							case 'import_newscust':
									$fs_check = filesize($_FILES["file_import"]["tmp_name"]);
									$fh_check = fopen($_FILES["file_import"]["tmp_name"], "r");
									$row_check = fgetcsv($fh_check, $fs_check);
											if(count($row_check)!=5)
												{
												  $alert =2;
												  header('Location:home.php?request=import_export&alert='.$alert.'&import_what=newsletter_customers');
												}
												fclose($fh_check);
									$fs = filesize($_FILES["file_import"]["tmp_name"]);
									$fh = fopen($_FILES["file_import"]["tmp_name"], "r");
									 if(!$fh)
									 {
										$alert =8;
										header('Location:home.php?request=import_export&alert='.$alert.'&import_what=newsletter_customers');

									 } 
									 else
									 { 
											if($_REQUEST['header_include']=='on'){
											fgets($fh, $fs);
											}
											$cnt=1;
											while($row = fgetcsv($fh, $fs))
											{
												$cnt++;
												$cust_file=array();	
												if($row['0']==''){
												 $alert[$cnt] ="Error!!!! Customer title is empty.<br>";
												}
												if($row['1']==''){
												 $alert[$cnt] .="Error!!!!  Customer name is empty.<br>";
												}
												if($row['2']==''){
												 $alert[$cnt] .="Error!!!!  Customer Email id is empty.<br>";
												}
												$email_id= $row['2'];
												if($row['2']){
												if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $email_id)) 
													{
													   $alert[$cnt] .= "Enter a Valid Email Address<br>";
													}
												}
												$sql_check = "SELECT count(*) as cnt FROM newsletter_customers WHERE news_custemail = '".add_slash($email_id)."' AND sites_site_id=$ecom_siteid";
												$res_check = $db->query($sql_check);
												$row_check = $db->fetch_array($res_check);
						
												if($row_check['cnt'] > 0)
													$alert[$cnt] .= "Error!!! Email Already exists.";
											}
											fclose($fh);	
											$fs1 = filesize($_FILES["file_import"]["tmp_name"]);
											$fh1 = fopen($_FILES["file_import"]["tmp_name"], "r");	
											if($_REQUEST['header_include']=='on'){
											fgets($fh1, $fs1);
											}		
												while($row1 = fgetcsv($fh1, $fs1))
												{
														if(!$alert) 
														{
															$insert_array = array();
															if($row1['0']=='Mr') {$row1['0']='Mr.';}elseif($row1['0']=='Ms'){ $row1['0']='Ms.';}elseif($row1['0']=='Mrs'){ $row1['0']='Mrs.';}elseif($row1['0']=='M/s'){ $row1['0']='M/s.';}elseif($row1['0']=='Dr'){ $row1['0']='Dr.';}elseif($row1['0']=='Sir'){ $row1['0']='Sir.';}elseif($row1['0']=='Rev'){ $row1['0']='Rev.';}elseif($row1['0']=='Miss'){ $row1['0']='Miss.';}else {$row1['0']='Mr.';};
																											
															$insert_array['news_title'] 					=$row1['0'];
															$insert_array['news_custname']					=add_slash(html_entity($row1['1']));
															$insert_array['news_custemail']					=add_slash(html_entity($row1['2']));
															$insert_array['news_custphone']					=add_slash(html_entity($row1['3']));
															$insert_array['news_custhide']					=($row1['4']=='Y')?1:0;
															$insert_array['news_join_date']					='curdate()';
															$insert_array['sites_site_id']					=$ecom_siteid;
															$db->insert_from_array($insert_array, 'newsletter_customers');
															
															$insert_id = $db->insert_id();
															// Check whether there already exists a customer with same email id in customers table for current website
															$sql_check = "SELECT customer_id, customer_title, customer_fname, customer_mname,
																				customer_surname  
																			FROM 
																				customers 
																			WHERE 
																				sites_site_id=$ecom_siteid 
																				AND customer_email_7503='".add_slash(html_entity($row1['2']))."'
																			LIMIT 
																				1";
															$ret_check = $db->query($sql_check);
															if($db->num_rows($ret_check))
															{
																$row_check = $db->fetch_array($ret_check);
																$curname = stripslashes($row_check['customer_fname']). ' '.stripslashes($row_check['customer_mname']).' '.stripslashes($row_check['customer_surname']);
																// case if entry already exists, then updating various fields
																$update_array 					= array();
																$update_array['news_title']		= add_slash(stripslashes($row_check['customer_title']));
																$update_array['news_custname']	= add_slash(html_entity($curname));
																$update_array['customer_id']	= $row_check['customer_id'];
																$db->update_from_array($update_array,'newsletter_customers',array('news_customer_id'=>$insert_id));
																
																//Update the customers table to make the field customer_in_mailing_list to 1
																$sql_update = "UPDATE customers 
																				SET 
																					customer_in_mailing_list=1 
																				WHERE 
																					customer_id = ".$row_check['customer_id']."  
																					AND sites_site_id = $ecom_siteid 
																				LIMIT 
																					1";
																$ret_update= $db->query($sql_update);
															}
														}
												}	
													if($insert_id){
															$alert =3;
															header('Location:home.php?request=import_export&alert='.$alert.'');
															}
															fclose($fh1);	
												// Calling function to show the error messages if any
												show_errors($cnt,$alert,'home.php?request=import_export&import_what=newsletter_customers');
										}					
							break;
							case 'import_shops':
							        $fs_check = filesize($_FILES["file_import"]["tmp_name"]);
									$fh_check = fopen($_FILES["file_import"]["tmp_name"], "r");
									$row_check = fgetcsv($fh_check, $fs_check);
									if(count($row_check)!=3)
											{
											  	$alert =2;
											  	header('Location:home.php?request=import_export&alert='.$alert.'&import_what=shop');
											}
												fclose($fh_check);	
									$name 	= $_FILES["file_import"]["name"];
									$fs 	= filesize($_FILES["file_import"]["tmp_name"]);
									$fh 	= fopen($_FILES["file_import"]["tmp_name"], "r");
									if(!$fh)
									 {
										$alert =8;
											header('Location:home.php?request=import_export&alert='.$alert.'&import_what=shop');
									 } 
									 else
											{ 
											if($_REQUEST['header_include']=='on')
												{
													fgets($fh, $fs);
												}
											$cnt=1;	
											while($row = fgetcsv($fh,$fs))
												{ 
													$cnt++;
													if($row['0']==''){
													$alert[$cnt] ="Error!!!! Shop Name is empty.";
													}
													if($row['1']=='Y' || $row['1']=='N'){
													$flag =1;
													}
													else
													{
													 $alert[$cnt] ="Error!!!! Hidden Variable Should 'Y' or 'N'.";
													}
													// Check whether the product shop name already exists
													$sql_exists 	= "SELECT count(shopbrand_id) FROM product_shopbybrand WHERE 
													shopbrand_name='".add_slash($row['0'])."' AND sites_site_id=$ecom_siteid
													AND shopbrand_parent_id=".$_REQUEST['sel_shop_id']." LIMIT 1";
													$ret_exists 	= $db->query($sql_exists);
													list($ext_cnt)	= $db->fetch_array($ret_exists);
													if ($ext_cnt>0)
													$alert[$cnt] = "Sorry! Product Shop name already exists";
												}
											fclose($fh);
											$fs1 = filesize($_FILES["file_import"]["tmp_name"]);
											$fh1 = fopen($_FILES["file_import"]["tmp_name"], "r");
											if($_REQUEST['header_include']=='on'){
												fgets($fh1, $fs1);
											}	
											while($row1 = fgetcsv($fh1, $fs1))
													{ 			
														if ($alert=='')
														{
															
															$insert_array											= array();
															$insert_array['sites_site_id']							= $ecom_siteid;
															$insert_array['shopbrand_parent_id']					= $_REQUEST['sel_shop_id'];
															sql_data($row1['0'],false);
															$insert_array['shopbrand_name']							= add_slash(html_entity($row1['0']));
															$insert_array['shopbrand_hide']							= ($row1['1']=='Y')?1:0;
															$insert_array['shopbrand_order']							= $row1['2'];
															if(count($_REQUEST['sel_shopgroup_id'])){
																	$insert_array['shopbrand_default_shopbrandgroup_id']	= $_REQUEST['sel_shopgroup_id'][0];
															}
															$db->insert_from_array($insert_array,'product_shopbybrand');
															$insert_id = $db->insert_id();
															// Section to make entry to product_shopbybrand_group_shop_map
																if(count($_REQUEST['sel_shopgroup_id']))
																{
																	for($i=0;$i<count($_REQUEST['sel_shopgroup_id']);$i++)
																	{
																		$insert_array											= array();
																		$insert_array['product_shopbybrand_shopbrandgroup_id']	= $_REQUEST['sel_shopgroup_id'][$i];
																		$insert_array['product_shopbybrand_shopbrand_id']		= $insert_id;
																		$insert_array['shop_order']								= 0;
																		$db->insert_from_array($insert_array,'product_shopbybrand_group_shop_map');
																	}
																}
														}
														
													}
													if($insert_id){
															$alert =4;
															header('Location:home.php?request=import_export&alert='.$alert.'');
														}
														fclose($fh1);
														
														// Calling function to show the error messages if any
														show_errors($cnt,$alert,'home.php?request=import_export&import_what=shop');
											}
							break;
							case 'import_category':
							        $fs_check = filesize($_FILES["file_import"]["tmp_name"]);
									$fh_check = fopen($_FILES["file_import"]["tmp_name"], "r");
									$row_check = fgetcsv($fh_check, $fs_check);
									if(count($row_check)!=3)
											{
											  	$alert =2;
											  	header('Location:home.php?request=import_export&alert='.$alert.'&import_what=cat');
											}
												fclose($fh_check);
									$name = $_FILES["file_import"]["name"];
									$fs = filesize($_FILES["file_import"]["tmp_name"]);
									$fh = fopen($_FILES["file_import"]["tmp_name"], "r");
									if(!$fh)
									 {
										$alert =8;
									 header('Location:home.php?request=import_export&alert='.$alert.'&import_what=cat');
									 } 
									 else
									{ 
										if($_REQUEST['header_include']=='on'){
													fgets($fh, $fs);;
												}	
										$cnt=1;
										while($row = fgetcsv($fh, $fs))
										{ 
										  $cnt++;
										  if($row['0']==''){
										  $flag =1;
											   $alert[$cnt] ="Error!!!! Category Name is empty.<br>";
											}
											if($row['2']=='Y' || $row['2']=='N'){
											   $flag =2;
											}
											else
											{
											 $alert[$cnt] .="Error!!!! Enter 'Y' Or 'N' For Hidden.<br>";
											}
										
												// Check whether the categroy name already exists
												$sql_exists 	= "SELECT count(category_id) FROM product_categories WHERE 
																	category_name='".add_slash($row['0'])."' AND sites_site_id=$ecom_siteid";
												$ret_exists 	= $db->query($sql_exists);
												list($ext_cnt)	= $db->fetch_array($ret_exists);
												if ($ext_cnt>0){
													$alert[$cnt] .= "Sorry! Category already exists";
													$flag=3;
													}
										}
										fclose($fh);
										$fs1 = filesize($_FILES["file_import"]["tmp_name"]);
										$fh1 = fopen($_FILES["file_import"]["tmp_name"], "r");
										
										if($_REQUEST['header_include']=='on'){
													fgets($fh1, $fs1);
												}	
										while($row1 = fgetcsv($fh1, $fs1))
											{ 
													if ($alert=='')
													{
														
														$insert_array									= array();
														$insert_array['sites_site_id']					= $ecom_siteid;
														$insert_array['parent_id']						= $_REQUEST['sel_category_id'];
														$insert_array['category_name']					= add_slash(trim($row1['0']));
														
												        sql_data($row1['1'],false);
														
														$insert_array['category_shortdescription']		= add_slash(html_entity($row1['1']));
														$insert_array['category_hide']					= ($row1['2']=='Y')?1:0;
														if(count($_REQUEST['sel_catgroup_id']))
														{
														$insert_array['default_catgroup_id']			= $_REQUEST['sel_catgroup_id'][0];
														}
											//	print_r($insert_array);
											//	exit;		
														$db->insert_from_array($insert_array,'product_categories');
														$insert_id = $db->insert_id();
														// Section to make entry to product_categorygroup_category
														if(count($_REQUEST['sel_catgroup_id']))
														{
															for($i=0;$i<count($_REQUEST['sel_catgroup_id']);$i++)
															{
																$insert_array								= array();
																$insert_array['catgroup_id']				= $_REQUEST['sel_catgroup_id'][$i];
																$insert_array['category_id']				= $insert_id;
																$insert_array['category_order']				= 0;
																$db->insert_from_array($insert_array,'product_categorygroup_category');
																//To delete the cache from the front end.
															    delete_catgroup_cache($_REQUEST['sel_catgroup_id'][$i]);
															}
														}
														//$alert .= 'Product Category imported successfully';
													}
											}
											if($insert_id){
															$alert =5;
															header('Location:home.php?request=import_export&alert='.$alert.'&import_what=cat');
														}
											fclose($fh1);
											
											// Calling function to show the error messages if any
											show_errors($cnt,$alert,'home.php?request=import_export&import_what=cat');
									   } 
							break;
						
							case 'import_product':
							 		$fs_check = filesize($_FILES["file_import"]["tmp_name"]);
									$fh_check = fopen($_FILES["file_import"]["tmp_name"], "r");
									$row_check = fgetcsv($fh_check, $fs_check);
									if(count($row_check)!=22)
											{
											  	$alert =2;
											  	header('Location:home.php?request=import_export&alert='.$alert.'&import_what=prod');
											}
									fclose($fh_check);	
									$name = $_FILES["file_import"]["name"];
									$fs = filesize($_FILES["file_import"]["tmp_name"]);
									$fh = fopen($_FILES["file_import"]["tmp_name"], "r");
									if(!$fh)
									 {
										$alert =8;
										header('Location:home.php?request=import_export&alert='.$alert.'&import_what=prod');
									 } 
									 else
									 { 
										if($_REQUEST['header_include']=='on'){
													fgets($fh, $fs);
												}	
										$cnt=1;
										while($row = fgetcsv($fh,$fs))
										{
											$cnt++;
											if($row['0']==''){
												$alert[$cnt] ="Error!!!! Product Name is empty.<br>";
											}
											if($row['3']==''){
												$alert[$cnt] .="Error!!!! Product short description is empty.<br>";
											}
											if($row['5']=='Y' || $row['5']=='N'){
												$flag= 1;
											}
											else
											{
												$alert[$cnt] .="Error!!!! Product Hidden Variable Should Be 'Y' or 'N'.<br>";
											}
										 }
										fclose($fh);
										$fs1= filesize($_FILES["file_import"]["tmp_name"]);
										$fh1 = fopen($_FILES["file_import"]["tmp_name"], "r");
										if($_REQUEST['header_include']=='on'){
													fgets($fh1, $fs1);
												}	
										while($row1 = fgetcsv($fh1,$fs1))
										{ 
											if ($alert=='')
											{
												$insert_array									= array();
												$insert_array['sites_site_id']					= $ecom_siteid;
												$insert_array['parent_id']						= ($_REQUEST['parent_id'])?$_REQUEST['parent_id']:0;
												$insert_array['product_adddate']				= 'now()';
												
												sql_data($row1['1'],false);
												sql_data($row1['0'],false);
												sql_data($row1['2'],false);
												sql_data($row1['3'],false);
												sql_data($row1['4'],false);
												sql_data($row1['6'],false);
												sql_data($row1['7'],false);
												sql_data($row1['8'],false);
												sql_data($row1['9'],false);
												sql_data($row1['10'],false);
												sql_data($row1['11'],false);
												sql_data($row1['18'],false);
												sql_data($row1['19'],false);
														
													//	$insert_array['category_shortdescription']		= add_slash(html_entity($row1['1']));
												$insert_array['manufacture_id']					= add_slash(html_entity($row1['1']));
												$insert_array['product_name']					= add_slash(html_entity($row1['0']));
												$insert_array['product_model']					= add_slash(html_entity($row1['2']));
												$insert_array['product_shortdesc']				= add_slash(html_entity($row1['3']));
												$insert_array['product_longdesc']				= add_slash(html_entity($row1['4']));
												$insert_array['product_hide']					= ($row1['5']=='Y')?'Y':'N';
												$insert_array['product_costprice']				= str_replace("'","",html_entity($row1['6']));
												$insert_array['product_webprice']				= str_replace("'","",html_entity($row1['7']));
												$insert_array['product_weight']					= str_replace("'","",html_entity($row1['8']));
												$insert_array['product_reorderqty']				= str_replace("'","",html_entity($row1['9']));
												$insert_array['product_bonuspoints']			= str_replace("'","",html_entity($row1['10']));
												$insert_array['product_discount']				= str_replace("'","",html_entity($row1['11']));
												$insert_array['product_discount_enteredasval']	= ($row1['12']=='V')?1:0;
												$insert_array['product_bulkdiscount_allowed']	= ($row1['13']=='Y')?'Y':'N';
												$insert_array['product_preorder_allowed']		= ($row1['15']=='Y')?'Y':'N';
												$insert_array['product_deposit']				= add_slash(html_entity($row1['18']));
												$insert_array['product_deposit_message']		= add_slash(html_entity($row1['19']),false);
												$insert_array['product_applytax']				= ($row1['14']=='Y')?'Y':'N';
												$insert_array['product_show_cartlink']			= ($row1['20']=='Y')?1:0;
												$insert_array['product_show_enquirelink']		= ($row1['21']=='Y')?1:0;
												$insert_array['product_default_category_id']	= $_REQUEST['sel_prod_category_id'][0];
												//echo $row1['15'];exit;
												if($row1['15']=='Y') // case of preorder is ticked
													{
														$insert_array['product_total_preorder_allowed']	= add_slash($row1['17']);
														$instock_arr 									= explode("/",add_slash($row1['16']));
														$instockdate									= $instock_arr[2]."-".$instock_arr[1]."-".$instock_arr[0];
														//echo $instockdate;exit;
														$insert_array['product_instock_date']			= $instockdate;
													}
													else	
													{
														$insert_array['product_total_preorder_allowed']	= 'N';
														$insert_array['product_instock_date']			= '0000-00-00';
													}
												$db->insert_from_array($insert_array,'products');
												$insert_id = $db->insert_id();
												
												// ########################################################################################################
												// Making insertion to category-product map
												// ########################################################################################################
												if (count($_REQUEST['sel_prod_category_id']))
												{
													for($i=0;$i<count($_REQUEST['sel_prod_category_id']);$i++)
													{
														$insert_array									= array();
														$insert_array['products_product_id']			= $insert_id;
														$insert_array['product_categories_category_id']	= $_REQUEST['sel_prod_category_id'][$i];
														$insert_array['product_order']					= 0;
														$db->insert_from_array($insert_array,'product_category_map');
													}
												}
												// Making insertion to vendor table
												/*if (count($_REQUEST['vendor_id']))
												{
													for($i=0;$i<count($_REQUEST['vendor_id']);$i++)
													{
														$insert_array								= array();
														$insert_array['product_vendors_vendor_id']	= $_REQUEST['vendor_id'][$i];
														$insert_array['products_product_id']		= $insert_id;
														$db->insert_from_array($insert_array,'product_vendor_map');
													}
												}*/
												
												// ########################################################################################################
												// Making the insertion to product_labels table
												// ########################################################################################################
												/*foreach ($_REQUEST as $k=>$v)
												{
													if (substr($k,0,6)=='label_')
													{
														$cur_arr 	= explode("_",$k);
														$curid		= $cur_arr[1];
														$istext		= $cur_arr[2];
														$insert_array													= array();
														$insert_array['products_product_id']							= $insert_id;
														$insert_array['product_site_labels_label_id']					= $curid;
														if($istext=='text')
														{
															$insert_array['label_value']								= add_slash($v);
															$insert_array['is_textbox']									= 1;
															$insert_array['product_site_labels_values_label_value_id']	= 0;
														}	
														else
														{
															$insert_array['label_value']								= '';
															$insert_array['is_textbox']									= 0;
															$insert_array['product_site_labels_values_label_value_id']	= $v;
														}
														$db->insert_from_array($insert_array,'product_labels');
													}
											   }*/
												
											}
										}
										if($insert_id){
															$alert =6;
															header('Location:home.php?request=import_export&alert='.$alert.'');
														}
										fclose($fh1);
									}
										// Calling function to show the error messages if any
											show_errors($cnt,$alert,'home.php?request=import_export&import_what=prod');
							break;
					}
				}
			else
			{
			 $alert=7;
						header('Location:home.php?request=import_export&alert='.$alert.'');
			}
						
				//echo $alert;
	/*  header('Location:home.php?request=import_export&alert='.$alert.'');*/
			}
			elseif($_REQUEST['template_Submit']){
					switch ($_REQUEST['cur_mod'])
					 {
						   case 'import_cust':
							   $mod = 'import';
							   $filename	= 'customers';
								foreach ($cust_importfield_arr as $k=>$v)
								{
									$headers[] = $v;
								}		
								header("Content-Type: text/plain");
								header("Content-Disposition: attachment; filename=$filename.csv");
								array_walk($headers, "add_quotes");
								print implode(",", $headers) . "\r\n";
								/*foreach($data as $r) {
									array_walk($r, "add_quotes");
									print implode(",", $r) . "\r\n";
								}*/
								 //echo "test"; 
						   break;
						    case 'import_newscust':
							   $mod = 'import';
							   $filename	= 'newsletter_customers';
								foreach ($newscust_importfield_arr as $k=>$v)
								{
									$headers[] = $v;
								}		
								header("Content-Type: text/plain");
								header("Content-Disposition: attachment; filename=$filename.csv");
								array_walk($headers, "add_quotes");
								print implode(",", $headers) . "\r\n";
								/*foreach($data as $r) {
									array_walk($r, "add_quotes");
									print implode(",", $r) . "\r\n";
								}*/
								 //echo "test"; 
						   break;
						   case 'import_shops':
							   $mod ='import';
							   $filename ='shops';
							   foreach($importshop_field_arr as $k=>$v)
							   {
							   $headers[] = $v;
							   }
								header("Content-Type: text/plain");
								header("Content-Disposition: attachment; filename=$filename.csv");
								array_walk($headers, "add_quotes");
								print implode(",", $headers) . "\r\n";
							break;
						  case 'import_category':
							   $mod ='import';
							   $filename ='categories';
							   foreach($cat_importfield_arr as $k=>$v)
							   {
							   $headers[] = $v;
							   }
								header("Content-Type: text/plain");
								header("Content-Disposition: attachment; filename=$filename.csv");
								array_walk($headers, "add_quotes");
								print implode(",", $headers) . "\r\n";
							break;
							case 'import_product':
							   $mod ='import';
							   $filename ='products';
							   foreach($prod_importfield_arr as $k=>$v)
							   {
							   $headers[] = $v;
							   }
								header("Content-Type: text/plain");
								header("Content-Disposition: attachment; filename=$filename.csv");
								array_walk($headers, "add_quotes");
								print implode(",", $headers) . "\r\n";
							break;
					 }
			}
	  break;
	 }	   
	if($_REQUEST['mod']=='export') // Case of Export
	{
		// Output the data
		switch($_REQUEST['export_output_format'])
		{
		case html:
		?>
			<html>
				<head>
				<title><? echo $ecom_title ?></title>
				<link href="css/style.css" rel="stylesheet" media="screen">
				</head>
			<body>
				<table width="100%" cellpadding="1" cellspacing="1" border="0">
				<thead>
				<tr>
				<?
					array_walk($headers, "htmlize");
					foreach($headers as $d) print "<th align='left' class='listingtableheader'>$d</th>";
				?>
				</tr>
				</thead>
				<tbody>
				<? 
					foreach($data as $r) {
						array_walk($r, "htmlize");
						print "<tr>";
						foreach($r as $d) print "<td class='tdcolorgray'>$d</td>";
						print "</tr>\n";		
					}
				?>
				</tbody>
				</table>
			</body>
			</html>
		<?
		break;
		case "csv":
			header("Content-Type: text/plain");
			header("Content-Disposition: attachment; filename=$filename.csv");
			array_walk($headers, "add_quotes");
			print implode(",", $headers) . "\r\n";
			foreach($data as $r) {
				array_walk($r, "add_quotes");
				print implode(",", $r) ."\r\n";
			}
		break;
		case "pdf":
			/*chdir("../pdf");
			include("class.ezpdf.php");
			$pdf = new Cezpdf();
			$pdf->ezTable($data, $headers, $title, array("fontSize" => 8, "maxWidth" => 580));
			$pdf->ezStream(array("Content-Disposition" => "$filename.pdf"));
			*/
		break;
		case "sql":
			header("Content-Type: text/plain");
			header("Content-Disposition: attachment; filename=$filename.sql");
			array_walk($_REQUEST['export_fields'], "sql_fields");
			$fields = $field_list;//implode(",",$_REQUEST['export_fields']);
			foreach($data as $r)
			{
				array_walk($r, "sql_data");
				print "INSERT INTO $filename ($fields) VALUES (" . implode(",", $r) . ");\r\n";
			}
		break;
		default:
			echo "<script>alert('Invalid Output Format');</script>";		
		};
	}
	/*else // Case of Import
	{
	  echo $alert;
	  header('Location:home.php?request=import_export&alert='.$alert.'');
	  //include("includes/import_export/import_export.php");
	}*/
	function show_errors($cnt,$alert,$link)
	{
		?>
		<br><br>
		<link href="css/style.css" rel="stylesheet" media="screen">
		<table border="0" width="50%" cellspacing="1" border="0" align="center">
			<tr>
			<td align="center" class="listingtableheader">Line
			</td>
			<td align="center" class="listingtableheader">Error</td>
			</tr>
		
		<?
		for($i=1;$i<=$cnt;$i++)
		{
		if ($alert[$i])
		{
		?>
			<tr>
				<td  class="listingtablestyleB" align="center">
				<? if($alert[$i]){ if($_REQUEST['header_include']=='on') { echo $i;} else echo $i-1;}?>
				</td>
				<td class="listingtablestyleB" align="center"><?=$alert[$i]?></td>
			</tr>
		<?
		}
		}
		?>
		<tr>
			<td  class="listingtablestyleB" align="center" colspan="2">
			<br /><br /><a class="smalllink" href="<?php echo $link?>">Click here to Go Back to the Import Page .</a>
			<br><br /><font color="red">If the header is there in the importing file then the option to include header should be checked.</font>
			</td>
		</tr>
		</table>
		<?php	
	}
	
	function hide_export_fields($str)
	{
		global $ecom_siteid;
		if($ecom_siteid==104 or $ecom_siteid == 105)
		{
			if ($_SESSION['console_id']==26054)
			{
				return $str;
			}
			else
			{
				return '';
			}
		}
		else
		{
			return $str;
		}
	}	
		
	$db->db_close();	
?>
