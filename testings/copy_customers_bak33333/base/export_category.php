<?php
	include_once('../../../classes/db_class.inc.php');	// Page which holds the class for db operations
	include '../../../config_db.php';
	$db	 				= new db_mysql($dbhost,$dbuname,$dbpass,$dbname);
	$db->connect();
	$db->select_db();
	$i=0;
	$domainname = strtolower($_SERVER['HTTP_HOST']);;
	if(!$domainname)
	{
		echo "Please specify a domain name";
		exit;
	}	
	$sql_query = "SELECT site_id 
					FROM 
						sites 
					WHERE 
						site_domain ='".$domainname."' 
					LIMIT 
						1";
	$ret_query = $db->query($sql_query);
	if ($db->num_rows($ret_query)==0)
	{
		echo "Domain not found";
		exit;
	}
	else
	{
		$row_query = $db->fetch_array($ret_query);
		$cur_siteid = $row_query['site_id'];
	}
	$ret_arr = generate_category_tree(0);
	if(count($ret_arr))
	{
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=".$domainname.".csv;");
		header("Accept-Ranges:bytes");
		print "ID (Don't Modify),Category (Don't Modify),Google Product Category (Copy & Paste Full text)"."\n";
		foreach ($ret_arr as $k=>$v)
		{
			$sql_taxonomy = "SELECT google_taxonomy_id FROM product_categories
					WHERE category_id ='".$k."' LIMIT 1";
			$ret_taxonomy = $db->query($sql_taxonomy);
			$google_product_category = '';
			if ($db->num_rows($ret_taxonomy)!=0)
			{
				$row_taxonomy = $db->fetch_array($ret_taxonomy);
				$google_taxonomy_id = $row_taxonomy['google_taxonomy_id'];
                                if($google_taxonomy_id != 0)
				{
				$sql_taxonomy_keyword = "SELECT google_taxonomy_keyword FROM google_productcategory_taxonomy
					WHERE google_taxonomy_id  ='".$google_taxonomy_id."' LIMIT 1";
				$ret_taxonomy_keyword = $db->query($sql_taxonomy_keyword);
					if ($db->num_rows($ret_taxonomy_keyword)!=0)
					{
						$row_taxonomy_keyword = $db->fetch_array($ret_taxonomy_keyword);
						$google_product_category = $row_taxonomy_keyword['google_taxonomy_keyword'];
					}
				}
			}
			$display_path = generate_tree_menu($k,-1);
			print "$k,$display_path,$google_product_category,"."\n";
		}
	}
	else
	{
		echo "No Categories found";
		exit;
	}
	
	function generate_category_tree($id)
	{
		global $db,$cur_siteid;
		$query = "select category_id,category_name 
							from 
								product_categories 
							where 
								sites_site_id=$cur_siteid  
								AND parent_id=$id
							ORDER BY 
								category_name";
		$result = $db->query($query);
		while(list($id,$name) = $db->fetch_array($result))
		{
			$categories[$id] = stripslashes($name);
			$subcategories = generate_category_tree($id,$level+1);
			if(is_array($subcategories))
			{
				foreach($subcategories as $k => $v)
				{
					$categories[$k] = $v;
				}
			}
		}
		return $categories;
	}
	
	function generate_tree_menu($cat_id,$prod_id,$seperator='>>',$prefix='',$suffix='')
	{
		global $db,$cur_siteid,$ecom_hostname;
		$break_counter_at 	= 10000; // Variable to break the infinite loop
		$counter_val		= 1;
		$found				= false;
		$ret_str			= '';
		if ($prod_id==-1)
		{
			// find the details of current category category
			$sql_det = "SELECT category_name,parent_id,default_catgroup_id
						FROM
							product_categories
						WHERE
							sites_site_id = $cur_siteid
							AND category_id=$cat_id";
			$ret_det = $db->query($sql_det);
			if ($db->num_rows($ret_det))
			{
				$row_det 	= $db->fetch_array($ret_det);
				$cur_id		= $row_det['parent_id']; // setting the parent of current category as the next category to be fetched
				$ret_str	= $prefix.str_replace(',',' ',stripslashes($row_det['category_name'])).$suffix;
			}
			else
				$break_counter_at = $break_counter_at; // done to handle the case if details of a category is not able to retrieve
		}
		if($cat_id==-1)
		{
			$cur_id = $_REQUEST['category_id'];// Getting the category id from $_REQUEST object
			// Get the detail of current product
			$sql_prod = "SELECT product_name,product_default_category_id
							FROM
								products
							WHERE
								product_id=$prod_id
								AND sites_site_id=$cur_siteid";
			$ret_prod = $db->query($sql_prod);
			if ($db->num_rows($ret_prod))
			{
				$row_prod = $db->fetch_array($ret_prod);
				if ($cur_id=='')
				{
					$cur_id = $row_prod['product_default_category_id'];
				}
				$ret_str	= $prefix.str_replace(',',' ',stripslashes($row_prod['product_name'])).$suffix; // place the name of the product in tree.
			}

		}
		while($cur_id>0 and $counter_val<$break_counter_at)
		{
			// find the details of category
			$sql_det = "SELECT category_name,parent_id,default_catgroup_id,category_hide 
							FROM
								product_categories
							WHERE
								sites_site_id = $cur_siteid
								AND category_id=$cur_id";
			$ret_det = $db->query($sql_det);
			if ($db->num_rows($ret_det))
			{
				$row_det 	= $db->fetch_array($ret_det);
				$grp_id		= $row_det['default_catgroup_id']; // Get the default category group id
				// Building the tree node and saving it in a string variable.
				$ret_str	= "$prefix" .str_replace(',',' ',stripslashes($row_det['category_name']))." $seperator $suffix ".$ret_str;
				$cur_id		= $row_det['parent_id'];
			}
			else
				$break_counter_at = $break_counter_at; // done to handle the case if details of a category is not able to retrieve
		}
		//$ret_str			= " $prefix Home $seperator $suffix ".$ret_str;
		return $ret_str;
	}
	
?>
	
