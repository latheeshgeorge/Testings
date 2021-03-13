<?php
	set_time_limit(0);
	require_once("sites.php");
	require_once("config.php");
	
	function add_slash($str)
	{
		return $str;
	}
	
	function is_valid_date($date,$format='normal',$sep='-')
	{
		$date_arr 	= explode(" ",$date); // done to extract the time section if exists
		$t_date		= $date_arr[0];
		$sp_date	= explode($sep,$t_date); // splitting the date base on the seperator
		$valid_Date	= true;
		if(count($sp_date)!= 3) // check whether there is exactly 3 elements in array after splitting
		$valid_Date = false;
		if($valid_Date)
		{
			// Check whether all the splitted elements are valid
			if(!is_numeric($sp_date[0]) or $sp_date[0]==0 or !is_numeric($sp_date[1]) or $sp_date[1]==0 or !is_numeric($sp_date[2]) or $sp_date[2]==0)
			$valid_Date = false;
			else
			{
				if($sp_date[0]<1 or $sp_date[1]<1 or $sp_date[2]<1)
				$valid_Date = false;
			}
		}
		if($valid_Date)
		{
			switch($format)
			{
				case 'normal':
				if (!checkdate($sp_date[1],$sp_date[0],$sp_date[2]))
				$valid_Date = false;
				break;
				case 'mysql':
				if (!checkdate($sp_date[1],$sp_date[2],$sp_date[0]))
				$valid_Date = false;
				break;
			};
		}
		return $valid_Date;
	}
	$download_name 	= 'products_sold';
	
	$_REQUEST['productname'] 	= $_REQUEST['pass_productname']; 
	$_REQUEST['prd_fromdate'] 	= $_REQUEST['pass_prd_fromdate']; 
	$_REQUEST['prd_todate'] 	= $_REQUEST['pass_prd_todate']; 
	$_REQUEST['categoryid'] 	= $_REQUEST['pass_categoryid']; 
	$_REQUEST['vendorid'] 		= $_REQUEST['pass_vendorid'];
	
	
	//#Search Options
	$where_conditions = "";
	// Product Name Condition
	if($_REQUEST['productname'])
	{
		$where_conditions .= " AND ( p.product_name LIKE '%".add_slash($_REQUEST['productname'])."%') ";
	}

	//##########################################################################################################
	// Case if from or to date is given
	$from_date 	= add_slash($_REQUEST['prd_fromdate']);
	$to_date 	= add_slash($_REQUEST['prd_todate']);
	if ($from_date or $to_date)
	{
		// Check whether from and to dates are valid
		$valid_fromdate = is_valid_date($from_date,'normal','-');
		$valid_todate	= is_valid_date($to_date,'normal','-');
		if($valid_fromdate)
		{
			$frm_arr 		= explode('-',$from_date);
			$mysql_fromdate = $frm_arr[2].'-'.$frm_arr[1].'-'.$frm_arr[0]; 
		}
		else// case of invalid from date
			$_REQUEST['prd_fromdate'] = '';
			
		if($valid_todate)
		{
			$to_arr 		= explode('-',$to_date);
			$mysql_todate 	= $to_arr[2].'-'.$to_arr[1].'-'.$to_arr[0]; 
		}
		else // case of invalid to date
			$_REQUEST['prd_todate'] = '';
			
		if($valid_fromdate and $valid_todate)// both dates are valid
		{
			$head_caption = ' Between '. $from_date.' and '.$to_date;
			$where_conditions .= " AND (order_date BETWEEN '".$mysql_fromdate." 00:00:00' AND '".$mysql_todate." 23:59:59') ";
		}
		elseif($valid_fromdate and !$valid_todate) // only from date is valid
		{
			$head_caption = ' Since '. $from_date;
			$where_conditions .= " AND order_date >= '".$mysql_fromdate." 00:00:00' ";
		}
		elseif(!$valid_fromdate and $valid_todate) // only to date is valid
		{
			$head_caption = ' Till '.$to_date;
			$where_conditions .= " AND order_date <= '".$mysql_todate." 23:59:59' ";
		}
	}
	if(trim($_REQUEST['prd_fromdate'])=='' and trim($_REQUEST['prd_todate'])=='')
	{
		 $start = date("Y-m-d",mktime(0, 0, 0, date("m")-3  , date("d"), date("Y")));
		 $end = date("Y-m-d");
		 $where_conditions .= " AND (order_date BETWEEN '".$start." 00:00:00' AND '".$end." 23:59:59') ";
		 $_REQUEST['prd_fromdate'] = date("d-m-Y",mktime(0, 0, 0, date("m")-3  , date("d"), date("Y")));
		 $_REQUEST['prd_todate']   = date("d-m-Y");
	}

	// ==================================================================================================
	// Case if category or vendor is selected 
	// ==================================================================================================
	if ($_REQUEST['categoryid'] or $_REQUEST['vendorid'])
	{ 
		$count_check ='Y';
		$catinclude_prod		= array(0);
		$vendinclude_prod		= array(0);
		if($_REQUEST['categoryid']) // case if category is selected
		{
			// Get the id's of products under this category
			$sql_catmap = "SELECT products_product_id FROM product_category_map WHERE product_categories_category_id=".$_REQUEST['categoryid'];
			$ret_catmap = $db->query($sql_catmap);
			if ($db->num_rows($ret_catmap))
			{
				while ($row_catmap = $db->fetch_array($ret_catmap))
				{
					$catinclude_prod[] = $row_catmap['products_product_id'];
				}
			}
			else
			{
				/*	$catinclude_prod		= array(0);
					$vendinclude_prod		= array(0);*/
					$count_check='N';
			}
		}
		if($_REQUEST['vendorid']) // case if vendor is selected
		{
			
			// Get the id's of products under this vendor
			$sql_vendmap = "SELECT products_product_id FROM product_vendor_map WHERE product_vendors_vendor_id=".$_REQUEST['vendorid'];
			$ret_vendmap = $db->query($sql_vendmap);
			if ($db->num_rows($ret_vendmap))
			{
				while ($row_vendmap = $db->fetch_array($ret_vendmap))
				{
					$vendinclude_prod[] = $row_vendmap['products_product_id'];
				}
			}
			else
			{
				/*$catinclude_prod		= array(0);
				$vendinclude_prod		= array(0);*/
				$count_check='N';
			}	
		}	
		$include_prod = array();
		if($count_check=='Y')
		{
			if(count($catinclude_prod)>1 and count($vendinclude_prod)>1)
			{
				$include_prod = array_intersect($catinclude_prod,$vendinclude_prod);
			}	
			elseif(count($catinclude_prod)==1 and count($vendinclude_prod)>1)
			{
				$include_prod = $vendinclude_prod;
			}	
			elseif(count($catinclude_prod)>1 and count($vendinclude_prod)==1)
			{
				$include_prod = $catinclude_prod;
			}else{
				$include_prod[] = -1;
			}
		}
		else
		{
		 $include_prod[] = -1;
		}	
		if (count($include_prod))
		{
			$include_prod_str = implode(",",$include_prod);
			$where_conditions .= " AND ( p.product_id IN ($include_prod_str)) ";
		}
	}
	
	function display_price($price)
	{
		global $db,$ecom_siteid;
		$sql_curr = "SELECT curr_sign_char FROM general_settings_site_currency WHERE
					sites_site_id=$ecom_siteid AND curr_default=1";
		$ret_curr = $db->query($sql_curr);
		if ($db->num_rows($ret_curr))
		{
			$row_curr 	= $db->fetch_array($ret_curr);
			$curr		= $row_curr['curr_sign_char'];
		}
		$price = sprintf("%.2f",$price);
		return $curr.$price;
	}
	
	// ==================================================================================================
	// ==================================================================================================
	$sql_qry = "SELECT p.*,sum(b.order_orgqty) as totcnt , sum(b.product_soldprice*b.order_orgqty) as totamt
				FROM 
					orders a,order_details b,products p 
				WHERE 
					a.order_id=b.orders_order_id 
					AND a.sites_site_id=$ecom_siteid 
					AND b.products_product_id=p.product_id 
					AND p.product_hide ='N'  
					AND a.order_status NOT IN ('CANCELLED','NOT_AUTH') 
					$where_conditions 
				GROUP BY 
					b.products_product_id  
				ORDER BY 
					totcnt DESC ";
	$ret_qry = $db->query($sql_qry);		
	
	$totqty = 0;
	$totamt = 0;	
	
	// Setting the header type
	
	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=".$download_name.".csv;");
	header("Accept-Ranges:bytes");
	
	print "#,Product,Total Order Qty,Total Amount(GBP)\n";
	
	if($db->num_rows($ret_qry))
	{
		$rowcnt = 1;
		while ($row_qry = $db->fetch_array($ret_qry))
		{
			print $rowcnt.",";
			$rowcnt++;
			
			print '"'.stripslashes($row_qry['product_name']).'"'.",";
			
			print '"'.($row_qry['totcnt']).'"'.",";
			
			$totqty += $row_qry['totcnt'];
			
			print '"'.($row_qry['totamt']).'"';
			
			$totamt += $row_qry['totamt'];
			
			print "\n";
		}
		print " , , , \n";
		print " ,Total:,$totqty,$totamt\n";
		
	}
?>
