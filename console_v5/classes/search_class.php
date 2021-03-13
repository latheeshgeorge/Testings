<?php
	/*#################################################################
	# Script Name 		: search_class.php
	# Description 		: Base file which handle the logic related to search
	# Coded by 			: Sny
	# Created on		: 16-Apr-2010
	# Modified by 		: Sny
	# Modified on		: 17-Apr-2010
	#################################################################*/
class Search_Class
{
	function searchProduct_fields($from_actions)
	{
		if($from_actions == true)
		{
			$sql_search 		= " SELECT product_id,product_webprice,
												IF(product_discount >0, 
												case product_discount_enteredasval
												WHEN 0 THEN (product_webprice-product_webprice*product_discount/100) 
												WHEN 1 THEN (IF((product_webprice-product_discount)>0,(product_webprice-product_discount),0)) 
												WHEN 2 THEN (product_discount) 
												END
												,product_webprice) calc_disc_price 
										FROM 
											products ";
		}
		else
		{
			$sql_search 		= " SELECT product_id,product_name,product_variablestock_allowed,product_show_cartlink,
												product_preorder_allowed,product_show_enquirelink,product_webstock,product_webprice,
												product_discount,product_discount_enteredasval,product_bulkdiscount_allowed,
												product_total_preorder_allowed,product_applytax,product_shortdesc,product_bonuspoints,
												product_stock_notification_required,product_alloworder_notinstock,product_variables_exists,product_variablesaddonprice_exists,
												product_variablecomboprice_allowed,product_variablecombocommon_image_allowed,default_comb_id,
												price_normalprefix,price_normalsuffix,price_fromprefix,price_fromsuffix,price_specialofferprefix,price_specialoffersuffix, 
												price_discountprefix,price_discountsuffix,price_yousaveprefix,price_yousavesuffix,price_noprice,
												product_averagerating,product_saleicon_show,product_saleicon_text,
												product_newicon_show,product_newicon_text,product_freedelivery,product_discontinue,
												IF(product_discount >0, 
												case product_discount_enteredasval
												WHEN 0 THEN (product_webprice-product_webprice*product_discount/100) 
												WHEN 1 THEN (IF((product_webprice-product_discount)>0,(product_webprice-product_discount),0)) 
												WHEN 2 THEN (product_discount) 
												END
												,product_webprice) calc_disc_price 
										FROM 
											products ";
		}
		return $sql_search;	
	}
	function searchProduct_Tot_cnt($sql_basesearch_cond)
	{
		global $db;
		$sql_totprod 		= "SELECT count(product_id)
								FROM 
									products ".$sql_basesearch_cond;	
		$ret_totprod		= $db->query($sql_totprod);
		list($tot_cnt)		= $db->fetch_array($ret_totprod);
		if($tot_cnt>0)
		{
			$ret_arr['tot_cnt'] 	= $tot_cnt;
			// Build the main query 
			$sql_qry = $this->searchProduct_fields($from_actions);
			$ret_arr['search_sql']	= $sql_qry.$sql_basesearch_cond;
		}
		else
		{
			$ret_arr['tot_cnt'] 	= 0;
			$ret_arr['search_sql']	= '';
		}
		return $ret_arr;								
									
	}
	function searchProduct_exactPhrase_title($search_keyword,$sql_basesearch_cond,$one_side=false)
	{
		global $db,$ecom_siteid,$ecom_hostname,$from_actions;
		$sql_search_cond	= $sql_basesearch_cond;
		$start_percent		= ($one_side==true)?'%':'';
		$sql_search_cond 	.= " AND  " ;
		$rtrim_keyword		= $this->my_rtrim($search_keyword,'s',true);
		$sql_search_cond 	.= " ( product_name LIKE '".$start_percent.add_slash($search_keyword)."%' OR product_name LIKE '".$start_percent.add_slash($rtrim_keyword)."%' 
								 	OR product_keywords LIKE '".$start_percent.add_slash($search_keyword)."%' OR product_keywords LIKE '".$start_percent.add_slash($rtrim_keyword)."%' 
								 ) ";	
		
		$tot_arr = $this->searchProduct_Tot_cnt($sql_search_cond);
		$tot_cnt = $tot_arr['tot_cnt'];
		if($tot_cnt>0)
		{
			$ret_arr['tot_cnt'] 	= $tot_cnt;
			// Build the main query 
			$sql_qry = $this->searchProduct_fields($from_actions);
			$ret_arr['search_sql']	= $sql_qry.$sql_search_cond;
		}
		else
		{
			$ret_arr['tot_cnt'] 	= 0;
			$ret_arr['search_sql']	= '';
		}
		return $ret_arr;	
	}
	function searchProduct_exactPhrase_titleDesc($search_keyword,$sql_basesearch_cond,$one_side=false)
	{
		global $db,$ecom_siteid,$ecom_hostname,$from_actions;
		$sql_search_cond	= $sql_basesearch_cond;
		$start_percent		= ($one_side==true)?'%':'';
		$sql_search_cond 	.=" AND  " ;
		$rtrim_keyword		= $this->my_rtrim($search_keyword,'s',true);
		$sql_search_cond 	.=	" ( product_name LIKE '".$start_percent.add_slash($search_keyword)."%' OR product_name LIKE '".$start_percent.add_slash($rtrim_keyword)."%' 
										OR  product_model LIKE '".$start_percent.add_slash($search_keyword)."%' OR  product_model LIKE '".$start_percent.add_slash($rtrim_keyword)."%' 
										OR product_shortdesc LIKE '".$start_percent.add_slash($search_keyword)."%' OR product_shortdesc LIKE '".$start_percent.add_slash($rtrim_keyword)."%' 
										OR product_longdesc LIKE '".$start_percent.add_slash($search_keyword)."%' OR product_longdesc LIKE '".$start_percent.add_slash($rtrim_keyword)."%' 
										OR product_keywords LIKE '".$start_percent.add_slash($search_keyword)."%' OR product_keywords LIKE '".$start_percent.add_slash($rtrim_keyword)."%' 
									)";		
		$tot_arr = $this->searchProduct_Tot_cnt($sql_search_cond);
		$tot_cnt = $tot_arr['tot_cnt'];
		if($tot_cnt>0)
		{
			$ret_arr['tot_cnt'] = $tot_cnt;
			// Build the main query 
			$sql_qry = $this->searchProduct_fields($from_actions);
			$ret_arr['search_sql']	= $sql_qry.$sql_search_cond;
		}
		else
		{
			$ret_arr['tot_cnt'] 	= 0;
			$ret_arr['search_sql']	= '';
		}
		return $ret_arr;	
	}
	function searchProduct_allWord_title($search_keyword,$sql_basesearch_cond,$one_side=false,$discard_array=array())
	{
		global $db,$ecom_siteid,$ecom_hostname,$from_actions;
		$sql_search_cond	= $sql_basesearch_cond;
		$start_percent		= ($one_side==true)?'%':'';
		$sql_search_cond 	.=" AND  " ;
		$entered_once		= 0;
		$search_keywords 		= explode(" ",$search_keyword);
		foreach($search_keywords as $quick_searchsin )
		{
			//Lookoing for each and every word of the entered text
			if(!in_array($quick_searchsin,$discard_array) && strlen($quick_searchsin) > 1) 
			{
				$entered_once =1;
				$rtrim_keyword		= $this->my_rtrim($quick_searchsin,'s');
				$sql_search_cond .= " (product_name LIKE '".$start_percent.add_slash($quick_searchsin)."%' OR product_name LIKE '".$start_percent.add_slash($rtrim_keyword)."%' ) AND ";
			}
		}	
		$sql_search_cond = substr($sql_search_cond, 0, strlen($sql_search_cond)-5);

		$tot_arr = $this->searchProduct_Tot_cnt($sql_search_cond);
		$tot_cnt = $tot_arr['tot_cnt'];
		if($tot_cnt>0)
		{
			$ret_arr['tot_cnt'] = $tot_cnt;
			// Build the main query 
			$sql_qry = $this->searchProduct_fields($from_actions);
			$ret_arr['search_sql']	= $sql_qry.$sql_search_cond;
		}
		else
		{
			$ret_arr['tot_cnt'] 	= 0;
			$ret_arr['search_sql']	= '';
		}
		return $ret_arr;	
	}
	function searchProduct_allWord_titleDesc($search_keyword,$sql_basesearch_cond,$one_side=false,$discard_array=array())
	{
		global $db,$ecom_siteid,$ecom_hostname,$from_actions;
		$sql_search_cond	= $sql_basesearch_cond;
		$start_percent		= ($one_side==true)?'%':'';
		$sql_search_cond 	.=" AND  " ;
		$entered_once		= 0;
		$search_keywords 		= explode(" ",$search_keyword);
		foreach($search_keywords as $quick_searchsin )
		{
			//Lookoing for each and every word of the entered text
			if(!in_array($quick_searchsin,$discard_array) && strlen($quick_searchsin) > 1) 
			{
				$entered_once =1;
				$rtrim_keyword		= $this->my_rtrim($quick_searchsin,'s');
				$sql_search_cond .= " ( product_name LIKE '%".add_slash($quick_searchsin)."%' OR product_name LIKE '%".add_slash($rtrim_keyword)."%' 
										OR  product_model LIKE '%".add_slash($quick_searchsin)."%' OR  product_model LIKE '%".add_slash($rtrim_keyword)."%' 
										OR product_shortdesc LIKE '%".add_slash($quick_searchsin)."%' OR product_shortdesc LIKE '%".add_slash($rtrim_keyword)."%' 
										OR product_longdesc LIKE '%".add_slash($quick_searchsin)."%' OR product_longdesc LIKE '%".add_slash($rtrim_keyword)."%' 
										OR product_keywords LIKE '%".add_slash($quick_searchsin)."%' OR product_keywords LIKE '%".add_slash($rtrim_keyword)."%' 
										)
										 AND ";
			}
		}	
		$sql_search_cond = substr($sql_search_cond, 0, strlen($sql_search_cond)-5);
			
		$tot_arr = $this->searchProduct_Tot_cnt($sql_search_cond);
		$tot_cnt = $tot_arr['tot_cnt'];
		if($tot_cnt>0)
		{
			$ret_arr['tot_cnt'] = $tot_cnt;
			// Build the main query 
			$sql_qry = $this->searchProduct_fields($from_actions);
			$ret_arr['search_sql']	= $sql_qry.$sql_search_cond;
		}
		else
		{
			$ret_arr['tot_cnt'] 	= 0;
			$ret_arr['search_sql']	= '';
		}
		return $ret_arr;	
	}
	
	function searchProduct_anyWord_title($search_keyword,$sql_basesearch_cond,$one_side=false,$discard_array=array())
	{
		global $db,$ecom_siteid,$ecom_hostname,$from_actions;
		$sql_search_cond	= $sql_basesearch_cond;
		$start_percent		= ($one_side==true)?'%':'';
		$sql_search_cond 	.=" AND (" ;
		$entered_once		= 0;
		$search_keywords 		= explode(" ",$search_keyword);
		foreach($search_keywords as $quick_searchsin )
		{
			//Lookoing for each and every word of the entered text
			if(!in_array($quick_searchsin,$discard_array) && strlen($quick_searchsin) > 1) 
			{
				$entered_once =1;
				$rtrim_keyword		= $this->my_rtrim($quick_searchsin,'s');
				$sql_search_cond .= "  product_name LIKE '".$start_percent.add_slash($quick_searchsin)."%'  OR   product_name LIKE '".$start_percent.add_slash($rtrim_keyword)."%'  OR   ";
			}
		}	
		$sql_search_cond = substr($sql_search_cond, 0, strlen($sql_search_cond)-5);
		if($entered_once ==1)
			$sql_search_cond .=' ) ';
			
		$tot_arr = $this->searchProduct_Tot_cnt($sql_search_cond);
		$tot_cnt = $tot_arr['tot_cnt'];
		if($tot_cnt>0)
		{
			$ret_arr['tot_cnt'] = $tot_cnt;
			// Build the main query 
			$sql_qry = $this->searchProduct_fields($from_actions);
			$ret_arr['search_sql']	= $sql_qry.$sql_search_cond;
		}
		else
		{
			$ret_arr['tot_cnt'] 	= 0;
			$ret_arr['search_sql']	= '';
		}
		return $ret_arr;	
	}
	function searchProduct_anyWord_titleDesc($search_keyword,$sql_basesearch_cond,$one_side=false,$discard_array=array())
	{
		global $db,$ecom_siteid,$ecom_hostname,$from_actions;
		$sql_search_cond	= $sql_basesearch_cond;
		$start_percent		= ($one_side==true)?'%':'';
		$sql_search_cond 	.=" AND (" ;
		$entered_once		= 0;
		$search_keywords 		= explode(" ",$search_keyword);
		foreach($search_keywords as $quick_searchsin )
		{
			//Lookoing for each and every word of the entered text
			if(!in_array($quick_searchsin,$discard_array) && strlen($quick_searchsin) > 1) 
			{
				$entered_once =1;
				$rtrim_keyword		= $this->my_rtrim($quick_searchsin,'s');
				$sql_search_cond .= " product_name LIKE '".$start_percent.add_slash($quick_searchsin)."%' OR product_name LIKE '".$start_percent.add_slash($rtrim_keyword)."%' 
										OR  product_model LIKE '".$start_percent.add_slash($quick_searchsin)."%' OR  product_model LIKE '".$start_percent.add_slash($rtrim_keyword)."%' 
										OR product_shortdesc LIKE '".$start_percent.add_slash($quick_searchsin)."%' OR product_shortdesc LIKE '".$start_percent.add_slash($rtrim_keyword)."%'
										OR product_longdesc LIKE '".$start_percent.add_slash($quick_searchsin)."%' OR product_longdesc LIKE '".$start_percent.add_slash($rtrim_keyword)."%'  
										OR product_keywords LIKE '".$start_percent.add_slash($quick_searchsin)."%' OR product_keywords LIKE '".$start_percent.add_slash($rtrim_keyword)."%' OR  ";
			}
		}	
		$sql_search_cond = substr($sql_search_cond, 0, strlen($sql_search_cond)-5);
		if($entered_once ==1)
			$sql_search_cond .=' ) ';
			
		$tot_arr = $this->searchProduct_Tot_cnt($sql_search_cond);
		$tot_cnt = $tot_arr['tot_cnt'];
		if($tot_cnt>0)
		{
			$ret_arr['tot_cnt'] = $tot_cnt;
			// Build the main query 
			$sql_qry = $this->searchProduct_fields($from_actions);
			$ret_arr['search_sql']	= $sql_qry.$sql_search_cond;
		}
		else
		{
			$ret_arr['tot_cnt'] 	= 0;
			$ret_arr['search_sql']	= '';
		}
		return $ret_arr;	
	}
	// Function which return the advanced search conditions
	function searchProductbuilding_search_base_conditions()
	{
		global $db,$ecom_siteid,$ecom_hostname;
		$sql_search_cond  				= " WHERE 
												sites_site_id = $ecom_siteid 
												AND product_hide = 'N' ";
		
		return $sql_search_cond;
	}
	function searchProductbuilding_search_advanced_conditions()
	{
		global $db,$ecom_siteid,$ecom_hostname;
		$search_category_id   			= $_REQUEST['search_category_id'];
		$search_model  					= $_REQUEST['search_model'];
		$search_minstk 					= add_slash($_REQUEST['search_minstk']);
		$search_minprice				= add_slash($_REQUEST['search_minprice']);
		$search_maxprice    			= add_slash($_REQUEST['search_maxprice']);
		$searchVariableName    			= addslashes($_REQUEST['searchVariableName']);
		$chk_buynow						= $_REQUEST['chk_buynow'];
		$chk_enquirenow					= $_REQUEST['chk_enquirenow'];
		$chk_preorder					= $_REQUEST['chk_preorder'];
		$search_sortby					= $_REQUEST['search_sortby'];
		$search_sortorder				= $_REQUEST['search_sortorder'];
		$search_prodperpage				= $_REQUEST['search_prodperpage'];
		$search_label_value				= $_REQUEST['search_label_value'];
		$searchVariableOption			= $_REQUEST['searchVariableOption'];
		// Resetting the values related to category
		$searchcat_sortby				= '';
		$searchcat_sortorder			= '';
		$searchcat_perpage				= '';
		$atleast_one_cond				= false; // variable to decide whether atleast one search criteria is there
		
		// ==========================================================================	
		// Case if category is selected in the drop down box
		// ==========================================================================	
		if($search_category_id!=0) 
		{
			// Get the list of subcategories of current category
			$subcat_arr = generate_subcategory_tree($search_category_id);
			$checkcat_arr = array($search_category_id);
			if (count($subcat_arr))
			{
				foreach ($subcat_arr as $k=>$v)
					$checkcat_arr[] = $k;
			}
			$atleast_one_cond = true;
			$search_category_str = implode(",",$checkcat_arr);
			
			 // Getting the products from the product cate gory map table with the category id
			 $cat_sql = "SELECT products_product_id FROM product_category_map WHERE product_categories_category_id IN ($search_category_str)";
			 $ret_cat = $db->query($cat_sql); 
			 if($db->num_rows($ret_cat))
			 {
				 while($row_cat=$db->fetch_array($ret_cat))
				 {
				   $prod_id[] 			= $row_cat['products_product_id'];
				   $use_prod_arr[]	= $row_cat['products_product_id'];
				 }
			 }
			 else // case if no products mapped with the selected category found
			 {
				 // If no products found
				 $prod_id 			= array(-1);
				 $use_prod_arr 	= array(-1);
			 
			 }		 
				// $prod_str = implode(",",$prod_id);
				// $sql_search_cond .= " AND product_id IN($prod_str)";	
				 $conutprod = count($prod_id);
			
		}
		// ==========================================================================	
		//Search for the product model
		// ==========================================================================	
		if($search_model!='')
		{
			
			$atleast_one_cond = true;
			$sql_search_cond .= " AND  product_model LIKE '%".add_slash($search_model)."%'";	
		}
		// ==========================================================================	
		//Search for the minimum stock
		// ==========================================================================	
		if($search_minstk!='' && is_numeric($search_minstk))
		{
			$atleast_one_cond = true;
			$sql_search_cond .= " AND product_actualstock >= $search_minstk";	
		}
		// ==========================================================================	
		//search for the minimum and the maximum price
		// ==========================================================================	
		if($search_minprice!='' && is_numeric($search_minprice))
		{
			
			$atleast_one_cond = true;
		   $sql_search_cond .= " AND product_webprice >= $search_minprice";	
		}
		if($search_maxprice!='' && is_numeric($search_maxprice))
		{
			
			$atleast_one_cond = true;
		   $sql_search_cond .= " AND product_webprice <= $search_maxprice";	
		}
				
		if(count($use_prod_arr)==1)
		{
			if($use_prod_arr[0]==-1)
				$prod_id_include = false;
		}	
		// ==========================================================================	
		// Search for all the label values only if the product_id_include variable is true
		// ==========================================================================	
		if(count($search_label_value)>0)// and $prod_id_include)
		{
				$search_label =array();$search_label_drop = array();
				foreach($search_label_value as $v)
				{
					  // Avoid the null value of the for the product id
					  if($v)
					  { 
							$atleast_one_cond = true;
						  if(is_numeric($v))
						  {
							  $sql_drop_down = "SELECT DISTINCT products_product_id FROM product_labels WHERE is_textbox=0 AND product_site_labels_values_label_value_id=".$v." "; 
							  $ret_drop_down = $db->query($sql_drop_down);
							  if($db->num_rows( $ret_drop_down))
							  {
								  while($row_drop_down=$db->fetch_array($ret_drop_down))
								  {
									// For getting the product id with the label vaue
									 $search_label_drop[] 	= $row_drop_down['products_product_id'];
									 $label_prod_arr[] 		= $row_drop_down['products_product_id'];
								  }
							  }
						  }
						  else
						  {
							  $sql_drop_down = "SELECT DISTINCT products_product_id FROM product_labels WHERE label_value='".$v."' "; 
							  $ret_drop_down = $db->query($sql_drop_down);
							  if($db->num_rows( $ret_drop_down))
							  {
								 
								  while($row_drop_down=$db->fetch_array($ret_drop_down))
								  {
									// For getting the product id with the label vaue
									 $search_label[] 		= $row_drop_down['products_product_id'];
									 $label_prod_arr[] 	= $row_drop_down['products_product_id'];
								  }
							  }
						  }
				   }
				}
			}
			// ==========================================================================	
			//Section for product variables.
			// ==========================================================================	
			$searchVariableOptions = array();
			$searchVariableNames = array();
			if ($searchVariableOption!="")
			{
				$atleast_one_cond = true;
				// Getting ids from product variable data where var value exists
				$query = "SELECT product_variables_var_id 
													FROM 
													product_variable_data 
													WHERE 
													var_value='$searchVariableOption';";
				$rstAdvSearchVarOpQ = $db->query($query);
				while(list($searchVariableOption) = $db->fetch_array($rstAdvSearchVarOpQ)) 
				{
					array_push($searchVariableOptions, $searchVariableOption); 
				}
			}
			if (trim($searchVariableName)!="")// and $prod_id_include)
			{
				$atleast_one_cond = true;
				// Getting all the product ids from this site
				$prod_sql = "SELECT product_id 
										FROM 
											products 
										WHERE 
											sites_site_id=$ecom_siteid 
											AND product_hide='N'";
				$ret_prod= $db->query($prod_sql);
				while($row_prod= $db->fetch_array($ret_prod))
				{
					$prod_ids[]=$row_prod['product_id']; 
					//$use_prod_arr[] 	= $row_prod['product_id']; 
				}
				if(count($prod_ids)>0)
				{ 
					$prod_str = implode(',',$prod_ids);
					$prod_str= "(".$prod_str.")";
					if (count($searchVariableOptions)!=0)
					{ 
						//Query for the products id with the varible name and value.
						$query = "SELECT DISTINCT products_product_id 
													FROM 
														product_variables 
													WHERE 
														var_name='$searchVariableName' 
														AND (";
						$printOr = 0;
							foreach ($searchVariableOptions as $searchVariableOption)
							{
								if ($printOr==1) 
								{ 
									$query .= " OR ";
								}
								$query .= " var_id='$searchVariableOption' ";
								$printOr = 1;
							}
						$query .= ") AND products_product_id in $prod_str AND var_value_exists=1";
					}
					else
					{
						//case if the variable option not exists.
						$query = "SELECT DISTINCT products_product_id 
															FROM 
																product_variables 
															WHERE 
																var_name='$searchVariableName' 
																AND products_product_id in $prod_str 
																AND var_value_exists=1";
					}
					$rstAdvSearchVarNameQ =$db->query($query);
					while(list($searchVariableName) = $db->fetch_array($rstAdvSearchVarNameQ))
					{ 
						array_push($searchVariableNames, $searchVariableName); 
						$var_prod_arr[] 	= $searchVariableName; 
					}
					$entered_var=0;
					if(count($searchVariableNames))
					{ //For product id list
						$entered_var =1;
						$var_prod_str = implode(',',$searchVariableNames);
						$var_prod_str = "(".$var_prod_str.")";
					}
				}
			}
			//Start ajaxfilter
			if($_REQUEST['fromajax_searchfilter']==true)
			{							
				$prod_sql_filt = "SELECT DISTINCT product_id 
				FROM 
				products 
				WHERE 
				sites_site_id=$ecom_siteid 
				AND product_hide='N'";
				$ret_prodfilt= $db->query($prod_sql_filt);
				while($row_prodfilt= $db->fetch_array($ret_prodfilt))
				{
				$prod_idsfilt[]=$row_prodfilt['product_id']; 
				//$use_prod_arr[] 	= $row_prod['product_id']; 
				}
				$prod_strfilt = implode(',',$prod_idsfilt);
				$prod_strfilt= "(".$prod_strfilt.")";
				$prevvar_get_id ='';
				foreach($_REQUEST as $key_search=>$reqfromajax)
				{
					if(substr($key_search,0,26)=='filtersearchVariableOption')
					{
						$var_name_arr  = explode('_',$key_search);
						$searchVariableIdfilter = $var_name_arr[1];
						$var_id = $var_name_arr[1];
						$var_get_id =  $var_name_arr[0]."_".$var_name_arr[1];
						if($var_get_id!=$prevvar_get_id)
						{
						$sql_varname = "SELECT var_name FROM product_variables WHERE var_id=".$searchVariableIdfilter." LIMIT 1";
						$res_var = $db->query($sql_varname);
						$row_varname = $db->fetch_array($res_var);
						$var_name = $row_varname['var_name'];
						}
						if ($reqfromajax!="")
						{
							// Getting ids from product variable data where var value exists
							$query_varopt = "SELECT DISTINCT product_variables_var_id 
											FROM 
											product_variable_data 
											WHERE 
											var_value='$reqfromajax';";
							$rstAdvSearchVarOpQf = $db->query($query_varopt);
							$searchVariableOptionsfilt =array();
							while(list($searchVariableOptionf) = $db->fetch_array($rstAdvSearchVarOpQf)) 
							{
								$searchVariableOptionsfilt[] = $searchVariableOptionf;
							}
						}
						if (count($searchVariableOptionsfilt)!=0)
							{ 
							//Query for the products id with the varible name and value.
							$query_filt = "SELECT DISTINCT products_product_id 
													FROM 
														product_variables 
													WHERE 
														var_name='$var_name' 
														AND (";
							$printOrfilt = 0;
							foreach ($searchVariableOptionsfilt as $searchVariableOptionfilt)
							{
								if ($printOrfilt==1) 
								{ 
									$query_filt .= " OR ";
								}
								$query_filt .= " var_id='$searchVariableOptionfilt' ";
								$printOrfilt = 1;
							}
							$query_filt .= ") AND products_product_id in $prod_strfilt AND var_value_exists=1";
							}
						else
							{
							//case if the variable option not exists.
							$query_filt = "SELECT DISTINCT products_product_id 
															FROM 
																product_variables 
															WHERE 
																var_name='$searchVariableNamefilter' 
																AND products_product_id in $prod_strfilt 
																AND var_value_exists=1";
							}
							$rstAdvSearchVarNameQfilt =$db->query($query_filt);
							while(list($searchVariableNamefilt) = $db->fetch_array($rstAdvSearchVarNameQfilt))
							{ 
								$searchVariableNamesfilt[$var_id][]= $searchVariableNamefilt; 
							}
						$prevvar_get_id = $var_get_id;
					}  
				}
				$arr_inter = array();
					//print_r($var_prod_arr);
				if(count($searchVariableNamesfilt))
				{
					$cnt =0;
					foreach($searchVariableNamesfilt as $keynow=>$searchVariableNamefilt)
					{ $entered_var1 =1;
						$cnt++;
						if($cnt==1)
						{
						 $arr_inter = array_merge($arr_inter,$searchVariableNamefilt);
						}
						elseif($cnt>1)
						{
						 $arr_inter = array_intersect($arr_inter,$searchVariableNamefilt);
						}
					}
					if(count($arr_inter)==0)
					{
						$arr_inter =array(-1);
					}
				}
			}
			if(count($arr_inter) && count($var_prod_arr))//11
			{
			  $var_prod_arr	=array_intersect($arr_inter,$var_prod_arr);
			}
			elseif(count($arr_inter) && count($var_prod_arr)==0)//11
			{
			  $var_prod_arr	= $arr_inter;
			}
			elseif(count($arr_inter)==0 && count($var_prod_arr))//11
			{
			  $var_prod_arr	= $var_prod_arr;
			}
			if($entered_var1==1 || $entered_var1==1) 
			{
			  if(count($var_prod_arr)==0)
			  {
			   $var_prod_arr = array(-1);
			  }
			}
			if(count($var_prod_arr))
			{ //For product id list
				$var_prod_str = implode(',',$var_prod_arr);
				$var_prod_str = "(".$var_prod_str.")";
			}
				
			// ==========================================================================	
			// Handling the case of product ids existing in search condition
			// ==========================================================================	
			if(count($use_prod_arr)==0 and count($label_prod_arr)==0 and count($var_prod_arr)) //001
			{
				 $use_prod_arr = $var_prod_arr;
			}
			elseif(count($use_prod_arr)==0 and count($label_prod_arr) and count($var_prod_arr)==0) //010
			{
				$use_prod_arr = $label_prod_arr;
			}
			elseif(count($use_prod_arr)==0 and count($label_prod_arr) and count($var_prod_arr)) // 011
			{
				$use_prod_arr = array_intersect($label_prod_arr,$var_prod_arr);
			}
			elseif(count($use_prod_arr) and count($label_prod_arr)==0 and count($var_prod_arr)) // 101
			{
				$use_prod_arr = array_intersect($use_prod_arr,$var_prod_arr);
			}
			elseif(count($use_prod_arr) and count($label_prod_arr) and count($var_prod_arr)==0) //110
			{
				$use_prod_arr = array_intersect($use_prod_arr,$label_prod_arr);
			}
			elseif(count($use_prod_arr) and count($label_prod_arr) and count($var_prod_arr)==0) //111
			{
				$use_prod_arr = array_intersect($use_prod_arr,$label_prod_arr,$var_prod_arr);
			}
			if(count($use_prod_arr))
			{
				array_unique($use_prod_arr);
				$sql_search_cond .= " AND product_id IN(".implode(',',$use_prod_arr).") ";	
			}
		return $sql_search_cond;
	}
	
	function searchCategory_fields()
	{
		$sql_search 		= " SELECT category_id,category_name,category_shortdescription,category_paid_description,category_showimageofproduct   
									FROM 
										product_categories ";
		return $sql_search;	
	}
	function searchCategory_Tot_cnt($sql_basesearch_cond)
	{
		global $db;
		$sql_totcat 		= "SELECT count(category_id)
								FROM 
									product_categories ".$sql_basesearch_cond;	
		$ret_totcat		= $db->query($sql_totcat);
		list($tot_cnt)		= $db->fetch_array($ret_totcat);
		if($tot_cnt>0)
		{
			$ret_arr['tot_cnt'] 	= $tot_cnt;
			// Build the main query 
			$sql_qry = $this->searchCategory_fields();
			$ret_arr['search_sql']	= $sql_qry.$sql_basesearch_cond;
		}
		else
		{
			$ret_arr['tot_cnt'] 	= 0;
			$ret_arr['search_sql']	= '';
		}
		return $ret_arr;								
									
	}
	function searchCategory_exactPhrase_title($search_keyword,$sql_basesearch_cond,$one_side=false)
	{
		global $db,$ecom_siteid,$ecom_hostname,$from_actions;
		$sql_search_cond	= $sql_basesearch_cond;
		$start_percent		= ($one_side==true)?'%':'';
		$sql_search_cond 	.= " AND  " ;
		$rtrim_keyword		= $this->my_rtrim($search_keyword,'s',true);
		$sql_search_cond 	.=	" ( category_name LIKE '".$start_percent.add_slash($search_keyword)."%' OR category_name LIKE '".$start_percent.add_slash($rtrim_keyword)."%' )";	
		$tot_arr = $this->searchCategory_Tot_cnt($sql_search_cond);
		$tot_cnt = $tot_arr['tot_cnt'];
		if($tot_cnt>0)
		{
			$ret_arr['tot_cnt'] 	= $tot_cnt;
			// Build the main query 
			$sql_qry = $this->searchCategory_fields();
			$ret_arr['search_sql']	= $sql_qry.$sql_search_cond;
		}
		else
		{
			$ret_arr['tot_cnt'] 	= 0;
			$ret_arr['search_sql']	= '';
		}
		return $ret_arr;	
	}
	function searchCategory_exactPhrase_titleDesc($search_keyword,$sql_basesearch_cond,$one_side=false)
	{
		global $db,$ecom_siteid,$ecom_hostname,$from_actions;
		$sql_search_cond	= $sql_basesearch_cond;
		$start_percent		= ($one_side==true)?'%':'';
		$sql_search_cond 	.=" AND  " ;
		$rtrim_keyword		= $this->my_rtrim($search_keyword,'s',true);
		$sql_search_cond 	.=	" ( category_name LIKE '".$start_percent.add_slash($search_keyword)."%' OR category_name LIKE '".$start_percent.add_slash($rtrim_keyword)."%' 
									OR  category_shortdescription LIKE '".$start_percent.add_slash($search_keyword)."%' OR  category_shortdescription LIKE '".$start_percent.add_slash($rtrim_keyword)."%' 
									OR category_paid_description LIKE '".$start_percent.add_slash($search_keyword)."%' OR category_paid_description LIKE '".$start_percent.add_slash($rtrim_keyword)."%' 
								  )";	
		$tot_arr = $this->searchCategory_Tot_cnt($sql_search_cond);
		$tot_cnt = $tot_arr['tot_cnt'];
		if($tot_cnt>0)
		{
			$ret_arr['tot_cnt'] = $tot_cnt;
			// Build the main query 
			$sql_qry = $this->searchCategory_fields();
			$ret_arr['search_sql']	= $sql_qry.$sql_search_cond;
		}
		else
		{
			$ret_arr['tot_cnt'] 	= 0;
			$ret_arr['search_sql']	= '';
		}
		return $ret_arr;	
	}
	function searchCategory_allWord_title($search_keyword,$sql_basesearch_cond,$one_side=false,$discard_array=array())
	{
		global $db,$ecom_siteid,$ecom_hostname,$from_actions;
		$sql_search_cond	= $sql_basesearch_cond;
		$start_percent		= ($one_side==true)?'%':'';
		$sql_search_cond 	.=" AND  " ;
		$entered_once		= 0;
		$search_keywords 		= explode(" ",$search_keyword);
		foreach($search_keywords as $quick_searchsin )
		{
			//Lookoing for each and every word of the entered text
			if(!in_array($quick_searchsin,$discard_array) && strlen($quick_searchsin) > 1) 
			{
				$rtrim_keyword		= $this->my_rtrim($quick_searchsin,'s');
				$entered_once =1;
				$sql_search_cond .= " ( category_name LIKE '".$start_percent.add_slash($quick_searchsin)."%' OR category_name LIKE '".$start_percent.add_slash($rtrim_keyword)."%' ) AND ";
			}
		}	
		$sql_search_cond = substr($sql_search_cond, 0, strlen($sql_search_cond)-5);
			
		$tot_arr = $this->searchCategory_Tot_cnt($sql_search_cond);
		$tot_cnt = $tot_arr['tot_cnt'];
		if($tot_cnt>0)
		{
			$ret_arr['tot_cnt'] = $tot_cnt;
			// Build the main query 
			$sql_qry = $this->searchCategory_fields();
			$ret_arr['search_sql']	= $sql_qry.$sql_search_cond;
		}
		else
		{
			$ret_arr['tot_cnt'] 	= 0;
			$ret_arr['search_sql']	= '';
		}
		return $ret_arr;	
	}
	function searchCategory_allWord_titleDesc($search_keyword,$sql_basesearch_cond,$one_side=false,$discard_array=array())
	{
		global $db,$ecom_siteid,$ecom_hostname,$from_actions;
		$sql_search_cond	= $sql_basesearch_cond;
		$start_percent		= ($one_side==true)?'%':'';
		$sql_search_cond 	.=" AND  " ;
		$entered_once		= 0;
		$search_keywords 		= explode(" ",$search_keyword);
		foreach($search_keywords as $quick_searchsin )
		{
			//Lookoing for each and every word of the entered text
			if(!in_array($quick_searchsin,$discard_array) && strlen($quick_searchsin) > 1) 
			{
				$entered_once =1;
				$rtrim_keyword		= $this->my_rtrim($quick_searchsin,'s');
				$sql_search_cond .= " ( category_name LIKE '".$start_percent.add_slash($quick_searchsin)."%' OR category_name LIKE '".$start_percent.add_slash($rtrim_keyword)."%' 
										OR  category_shortdescription LIKE '".$start_percent.add_slash($quick_searchsin)."%' OR  category_shortdescription LIKE '".$start_percent.add_slash($rtrim_keyword)."%' 
										OR category_paid_description LIKE '".$start_percent.add_slash($quick_searchsin)."%' OR OR category_paid_description LIKE '".$start_percent.add_slash($rtrim_keyword)."%' ) AND ";
			}
		}	
		$sql_search_cond = substr($sql_search_cond, 0, strlen($sql_search_cond)-5);
			
		$tot_arr = $this->searchCategory_Tot_cnt($sql_search_cond);
		$tot_cnt = $tot_arr['tot_cnt'];
		if($tot_cnt>0)
		{
			$ret_arr['tot_cnt'] = $tot_cnt;
			// Build the main query 
			$sql_qry = $this->searchCategory_fields($from_actions);
			$ret_arr['search_sql']	= $sql_qry.$sql_search_cond;
		}
		else
		{
			$ret_arr['tot_cnt'] 	= 0;
			$ret_arr['search_sql']	= '';
		}
		return $ret_arr;	
	}
	
	function searchCategory_anyWord_title($search_keyword,$sql_basesearch_cond,$one_side=false,$discard_array=array())
	{ 																			

		global $db,$ecom_siteid,$ecom_hostname,$from_actions;
		$sql_search_cond	= $sql_basesearch_cond;
		$start_percent		= ($one_side==true)?'%':'';
		$sql_search_cond 	.=" AND (" ;
		$entered_once		= 0;
		$search_keywords 		= explode(" ",$search_keyword);
		foreach($search_keywords as $quick_searchsin )
		{
			//Lookoing for each and every word of the entered text
			if(!in_array($quick_searchsin,$discard_array) && strlen($quick_searchsin) > 1) 
			{
				$entered_once =1;
				$rtrim_keyword		= $this->my_rtrim($quick_searchsin,'s');
				$sql_search_cond .= " ( category_name LIKE '".$start_percent.add_slash($quick_searchsin)."%' ) OR ( category_name LIKE '".$start_percent.add_slash($rtrim_keyword)."%' ) OR  ";
			}
		}	

		$sql_search_cond = substr($sql_search_cond, 0, strlen($sql_search_cond)-5);
		if($entered_once ==1)
			$sql_search_cond .=' ) ';

		$tot_arr = $this->searchCategory_Tot_cnt($sql_search_cond);
		$tot_cnt = $tot_arr['tot_cnt'];
		if($tot_cnt>0)
		{
			$ret_arr['tot_cnt'] = $tot_cnt;
			// Build the main query 
			$sql_qry = $this->searchCategory_fields($from_actions);
			$ret_arr['search_sql']	= $sql_qry.$sql_search_cond;
		}
		else
		{
			$ret_arr['tot_cnt'] 	= 0;
			$ret_arr['search_sql']	= '';
		}
		
		return $ret_arr;	
	}
	function searchCategory_anyWord_titleDesc($search_keyword,$sql_basesearch_cond,$one_side=false,$discard_array=array())
	{
		global $db,$ecom_siteid,$ecom_hostname,$from_actions;
		$sql_search_cond	= $sql_basesearch_cond;
		$start_percent		= ($one_side==true)?'%':'';
		$sql_search_cond 	.=" AND (" ;
		$entered_once		= 0;
		$search_keywords 		= explode(" ",$search_keyword);
		foreach($search_keywords as $quick_searchsin )
		{
			//Lookoing for each and every word of the entered text
			if(!in_array($quick_searchsin,$discard_array) && strlen($quick_searchsin) > 1) 
			{
				$entered_once = 1;
				$rtrim_keyword		= $this->my_rtrim($quick_searchsin,'s');
				$sql_search_cond .= " ( category_name LIKE '".$start_percent.add_slash($quick_searchsin)."%' OR category_name LIKE '".$start_percent.add_slash($rtrim_keyword)."%' 
										OR  category_shortdescription LIKE '".$start_percent.add_slash($quick_searchsin)."%' OR  category_shortdescription LIKE '".$start_percent.add_slash($rtrim_keyword)."%' 
										OR category_paid_description LIKE '".$start_percent.add_slash($quick_searchsin)."%' OR category_paid_description LIKE '".$start_percent.add_slash($rtrim_keyword)."%') OR  ";
			}
		}	
		$sql_search_cond = substr($sql_search_cond, 0, strlen($sql_search_cond)-5);
		if($entered_once ==1)
			$sql_search_cond .=' ) ';
			
		$tot_arr = $this->searchCategory_Tot_cnt($sql_search_cond);
		$tot_cnt = $tot_arr['tot_cnt'];
		if($tot_cnt>0)
		{
			$ret_arr['tot_cnt'] = $tot_cnt;
			// Build the main query 
			$sql_qry = $this->searchCategory_fields($from_actions);
			$ret_arr['search_sql']	= $sql_qry.$sql_search_cond;
		}
		else
		{
			$ret_arr['tot_cnt'] 	= 0;
			$ret_arr['search_sql']	= '';
		}
		return $ret_arr;	
	}
	function my_rtrim($str,$char,$avoid_splitting=false)
	{
		if($avoid_splitting!=true)
			$str_arr = explode(' ',$str);
		else
			$str_arr = array($str);
		$new_arr = array();
		$new_str = '';
		for($i=0;$i<count($str_arr);$i++)
		{
			if(substr(strtolower($str_arr[$i]),-1)=='s')
			{
				$new_arr[] = substr($str_arr[$i],0,strlen($str_arr[$i])-1);
			}
			else
				$new_arr[] = $str_arr[$i];
		}	
		$new_str = implode(' ',$new_arr);
		return $new_str;
	}
};
?>