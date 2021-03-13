<?php
	/*#################################################################
	# Script Name 	: ad_sitetitles_list.php
	# Description 	: Page to manage the assignment of titles to category/property/static pages
	# Coded by 		: Latheesh
	# Created on	: 10-Sep-2007
	# Modified by	: Anu
	# Modified On	: 
	#################################################################*/

//#Define constants for this page
$page_type 		= 'Titles and Descriptions';
$help_msg 		= get_help_messages('LIST_SITE_TITLES_MESS1');

$keytype		= $_REQUEST['cbo_keytype'];
if(!$keytype)
	$keytype = 'home';
switch($keytype)
{	
	case 'home': // Case if Home is selected
		$showtype = 'Home';
		$where_conditions = "WHERE sites_site_id = $ecom_siteid";
	break;
	case 'help': // Case if Help is selected
		$showtype = 'Help';
		$where_conditions = "WHERE sites_site_id = $ecom_siteid";
	break;
	case 'faq': // Case if faq is selected
		$showtype = 'FAQ';
		$where_conditions = "WHERE sites_site_id = $ecom_siteid";
	break;
	case 'registration': // Case if Registration is selected
		$showtype = 'Registration';
		$where_conditions = "WHERE sites_site_id = $ecom_siteid";
	break;
	case 'sitemap': // Case if Sitemap is selected
		$showtype = 'Sitemap';
		$where_conditions = "WHERE sites_site_id = $ecom_siteid";
	break;
	case 'forgotpassword': // Case if forgotpassword is selected
		$showtype = 'Forgot Password';
		$where_conditions = "WHERE sites_site_id = $ecom_siteid";
	break;
	case 'savedsearchmain': // Case if saved search main is selected
		$showtype = 'Saved search Main';
		$where_conditions = "WHERE sites_site_id = $ecom_siteid";
	break;
	case 'sitereviews': // Case if Site reviews is selected
		$showtype = 'Site Reviews';
		$where_conditions = "WHERE sites_site_id = $ecom_siteid";
	break;
	case 'bestsellers': // Case if Best sellers is selected
		$showtype = 'Best Sellers';
		$where_conditions = "WHERE sites_site_id = $ecom_siteid";
	break;
	case 'cat': // Case if category is selected
		$showtype = 'Categories';
		$table_name = 'product_categories';
		$where_conditions = "WHERE sites_site_id = $ecom_siteid";
		if($_REQUEST['search_name'])
		{
			$where_conditions .= " AND category_name like '%".$_REQUEST['search_name']."%' ";
		}
		if($_REQUEST['parentid'])
		{
			$where_conditions .= " AND parent_id = ".$_REQUEST['parentid']." ";
		}
		
		$field_name		=	trim($_REQUEST['fieldName']);
		$field_data		=	trim($_REQUEST['fieldData']);
		$field_size		=	trim($_REQUEST['fieldSize']);
		
		if($field_name != "" && ($field_data != "" || $field_size != ""))
		{
			$where_conditions .= filterQueryGen($field_name, $field_data, $field_size, 'product_categories_category_id', 'se_category_title', 'category_id');
			/*if($_REQUEST['fieldName'] == 'title')
			{
				$field_condition	=	"LENGTH(title) > ".$_REQUEST['fieldSize'];
			}
			else if($_REQUEST['fieldName'] == 'desc')
			{
				$field_condition	=	"LENGTH(meta_description) > ".$_REQUEST['fieldSize'];
			}
			
			$sql_filter_catid	=	"SELECT
													product_categories_category_id
											FROM
													se_category_title
											WHERE
													sites_site_id = $ecom_siteid
											AND
													$field_condition";
			//echo $sql_filter_catid."<br>";
			$ret_filter_catid	=	$db->query($sql_filter_catid);
			
			$catIDList	=	"";
			if($db->num_rows($ret_filter_catid))
			{
				while($row_filter_catid = $db->fetch_array($ret_filter_catid))
				{
					$catIDList	.=	$row_filter_catid['product_categories_category_id'].",";
				}
				$catIDList	=	substr($catIDList, 0, -1);
				//echo $catIDList."<br>";
			}
			if($catIDList != "")
			{
				$where_conditions .= " AND category_id IN (".$catIDList.")";
			}
			else
			{
				$where_conditions .= " AND category_id IN (-1)";
			}
			//echo $where_conditions."<br>";*/
		}
		$query_string .= '&search_name='.$_REQUEST['search_name'].'&parentid='.$_REQUEST['search_name'].'&';
	break;
	case 'prod': // Case if property is selected
		$showtype = 'Products';
		$table_name = 'products';
		$where_conditions = "WHERE sites_site_id = $ecom_siteid";
		if($_REQUEST['search_name']){
		$where_conditions .= " AND product_name like '%".$_REQUEST['search_name']."%' ";
		}
		if($_REQUEST['parentid']){
		//$where_conditions .= " AND product_default_category_id = ".$_REQUEST['parentid']." ";
		$where_conditions .= " AND product_id IN (SELECT DISTINCT products_product_id FROM product_category_map WHERE product_categories_category_id=".$_REQUEST['parentid'].")";

		}
		
		$field_name		=	trim($_REQUEST['fieldName']);
		$field_data		=	trim($_REQUEST['fieldData']);
		$field_size		=	trim($_REQUEST['fieldSize']);
		
		if($field_name != "" && ($field_data != "" || $field_size != ""))
		{
			$where_conditions .= filterQueryGen($field_name, $field_data, $field_size, 'products_product_id', 'se_product_title', 'product_id');
			/*if($_REQUEST['fieldName'] == 'title')
			{
				$field_condition	=	"LENGTH(title) > ".$_REQUEST['fieldSize'];
			}
			else if($_REQUEST['fieldName'] == 'desc')
			{
				$field_condition	=	"LENGTH(meta_description) > ".$_REQUEST['fieldSize'];
			}
			
			$sql_filter_prdid	=	"SELECT
													products_product_id
											FROM
													se_product_title
											WHERE
													sites_site_id = $ecom_siteid
											AND
													$field_condition";
			//echo $sql_filter_prdid."<br>";
			$ret_filter_prdid	=	$db->query($sql_filter_prdid);
			
			$prdIDList	=	"";
			if($db->num_rows($ret_filter_prdid))
			{
				while($row_filter_prdid = $db->fetch_array($ret_filter_prdid))
				{
					$prdIDList	.=	$row_filter_prdid['products_product_id'].",";
				}
				$prdIDList	=	substr($prdIDList, 0, -1);
				//echo $prdIDList."<br>";
			}
			if($prdIDList != "")
			{
				$where_conditions .= " AND product_id IN (".$prdIDList.")";
			}
			else
			{
				$where_conditions .= " AND product_id IN (-1)";
			}
			//echo $where_conditions."<br>";*/
		}
		$query_string .= '&search_name='.$_REQUEST['search_name'].'&parentid='.$_REQUEST['parentid'].'&';
	break;
	case 'shelf': // Case if shelf is selected
		$showtype = 'Shelves';
		$table_name = 'product_shelf';
		$where_conditions = "WHERE sites_site_id = $ecom_siteid";
		if($_REQUEST['search_name']){
		$where_conditions .= " AND shelf_name like '%".$_REQUEST['search_name']."%' ";
		}
		
		$field_name		=	trim($_REQUEST['fieldName']);
		$field_data		=	trim($_REQUEST['fieldData']);
		$field_size		=	trim($_REQUEST['fieldSize']);
		
		if($field_name != "" && ($field_data != "" || $field_size != ""))
		{
			$where_conditions .= filterQueryGen($field_name, $field_data, $field_size, 'product_shelf_shelf_id', 'se_shelf_title', 'shelf_id');
			/*if($_REQUEST['fieldName'] == 'title')
			{
				$field_condition	=	"LENGTH(title) > ".$_REQUEST['fieldSize'];
			}
			else if($_REQUEST['fieldName'] == 'desc')
			{
				$field_condition	=	"LENGTH(meta_description) > ".$_REQUEST['fieldSize'];
			}
			
			$sql_filter_shfid	=	"SELECT
													product_shelf_shelf_id
											FROM
													se_shelf_title
											WHERE
													sites_site_id = $ecom_siteid
											AND
													$field_condition";
			//echo $sql_filter_shfid."<br>";
			$ret_filter_shfid	=	$db->query($sql_filter_shfid);
			
			$shfIDList	=	"";
			if($db->num_rows($ret_filter_shfid))
			{
				while($row_filter_shfid = $db->fetch_array($ret_filter_shfid))
				{
					$shfIDList	.=	$row_filter_shfid['product_shelf_shelf_id'].",";
				}
				$shfIDList	=	substr($shfIDList, 0, -1);
				//echo $shfIDList."<br>";
			}
			if($shfIDList != "")
			{
				$where_conditions .= " AND shelf_id IN (".$shfIDList.")";
			}
			else
			{
				$where_conditions .= " AND shelf_id IN (-1)";
			}
			//echo $where_conditions."<br>";*/
		}
		$query_string .= '&search_name='.$_REQUEST['search_name'].'&';
	break;
	case 'combo': // Case if combo is selected
		$showtype = 'Combo deals';
		$table_name = 'combo';
		$where_conditions = "WHERE sites_site_id = $ecom_siteid";
		if($_REQUEST['search_name']){
		$where_conditions .= " AND combo_name like '%".$_REQUEST['search_name']."%' ";
		}
		
		$field_name		=	trim($_REQUEST['fieldName']);
		$field_data		=	trim($_REQUEST['fieldData']);
		$field_size		=	trim($_REQUEST['fieldSize']);
		
		if($field_name != "" && ($field_data != "" || $field_size != ""))
		{
			$where_conditions .= filterQueryGen($field_name, $field_data, $field_size, 'combo_combo_id', 'se_combo_title', 'combo_id');
			/*if($_REQUEST['fieldName'] == 'title')
			{
				$field_condition	=	"LENGTH(title) > ".$_REQUEST['fieldSize'];
			}
			else if($_REQUEST['fieldName'] == 'desc')
			{
				$field_condition	=	"LENGTH(meta_description) > ".$_REQUEST['fieldSize'];
			}
			
			$sql_filter_comid	=	"SELECT
													combo_combo_id
											FROM
													se_combo_title
											WHERE
													sites_site_id = $ecom_siteid
											AND
													$field_condition";
			//echo $sql_filter_comid."<br>";
			$ret_filter_comid	=	$db->query($sql_filter_comid);
			
			$comIDList	=	"";
			if($db->num_rows($ret_filter_comid))
			{
				while($row_filter_comid = $db->fetch_array($ret_filter_comid))
				{
					$comIDList	.=	$row_filter_comid['combo_combo_id'].",";
				}
				$comIDList	=	substr($comIDList, 0, -1);
				//echo $comIDList."<br>";
			}
			if($comIDList != "")
			{
				$where_conditions .= " AND combo_id IN (".$comIDList.")";
			}
			else
			{
				$where_conditions .= " AND combo_id IN (-1)";
			}
			//echo $where_conditions."<br>";*/
		}
		$query_string .= '&search_name='.$_REQUEST['search_name'].'&';
	break;
	case 'shop': // Case if shops is selected
		$showtype = 'Shops';
		$table_name = 'product_shopbybrand';
		$where_conditions = "WHERE sites_site_id = $ecom_siteid";
		if($_REQUEST['search_name']){
		$where_conditions .= " AND shopbrand_name like '%".$_REQUEST['search_name']."%' ";
		}
		
		$field_name		=	trim($_REQUEST['fieldName']);
		$field_data		=	trim($_REQUEST['fieldData']);
		$field_size		=	trim($_REQUEST['fieldSize']);
		
		if($field_name != "" && ($field_data != "" || $field_size != ""))
		{
			$where_conditions .= filterQueryGen($field_name, $field_data, $field_size, 'product_shopbybrand_shopbrand_id', 'se_shop_title', 'shopbrand_id');
			/*if($_REQUEST['fieldName'] == 'title')
			{
				$field_condition	=	"LENGTH(title) > ".$_REQUEST['fieldSize'];
			}
			else if($_REQUEST['fieldName'] == 'desc')
			{
				$field_condition	=	"LENGTH(meta_description) > ".$_REQUEST['fieldSize'];
			}
			
			$sql_filter_shpid	=	"SELECT
													product_shopbybrand_shopbrand_id
											FROM
													se_shop_title
											WHERE
													sites_site_id = $ecom_siteid
											AND
													$field_condition";
			//echo $sql_filter_shpid."<br>";
			$ret_filter_shpid	=	$db->query($sql_filter_shpid);
			
			$shpIDList	=	"";
			if($db->num_rows($ret_filter_shpid))
			{
				while($row_filter_shpid = $db->fetch_array($ret_filter_shpid))
				{
					$shpIDList	.=	$row_filter_shpid['product_shopbybrand_shopbrand_id'].",";
				}
				$shpIDList	=	substr($shpIDList, 0, -1);
				//echo $shpIDList."<br>";
			}
			if($shpIDList != "")
			{
				$where_conditions .= " AND shopbrand_id IN (".$shpIDList.")";
			}
			else
			{
				$where_conditions .= " AND shopbrand_id IN (-1)";
			}
			//echo $where_conditions."<br>";*/
		}
		$query_string .= '&search_name='.$_REQUEST['search_name'].'&';
	break;
	case 'stat': // Case if static pages is selected
		$showtype = 'Static Pages';
		$table_name = 'static_pages';
		$where_conditions = "WHERE sites_site_id = $ecom_siteid AND pname <> 'Home'";
		if($_REQUEST['search_name']){
		$where_conditions .= " AND pname like '%".$_REQUEST['search_name']."%' ";
		}
		
		$field_name		=	trim($_REQUEST['fieldName']);
		$field_data		=	trim($_REQUEST['fieldData']);
		$field_size		=	trim($_REQUEST['fieldSize']);
		
		if($field_name != "" && ($field_data != "" || $field_size != ""))
		{
			$where_conditions .= filterQueryGen($field_name, $field_data, $field_size, 'static_pages_page_id', 'se_static_title', 'page_id');
			/*if($_REQUEST['fieldName'] == 'title')
			{
				$field_condition	=	"LENGTH(title) > ".$_REQUEST['fieldSize'];
			}
			else if($_REQUEST['fieldName'] == 'desc')
			{
				$field_condition	=	"LENGTH(meta_description) > ".$_REQUEST['fieldSize'];
			}
			
			$sql_filter_staid	=	"SELECT
													static_pages_page_id
											FROM
													se_static_title
											WHERE
													sites_site_id = $ecom_siteid
											AND
													$field_condition";
			//echo $sql_filter_staid."<br>";
			$ret_filter_staid	=	$db->query($sql_filter_staid);
			
			$staIDList	=	"";
			if($db->num_rows($ret_filter_staid))
			{
				while($row_filter_staid = $db->fetch_array($ret_filter_staid))
				{
					$staIDList	.=	$row_filter_staid['static_pages_page_id'].",";
				}
				$staIDList	=	substr($staIDList, 0, -1);
				//echo $staIDList."<br>";
			}
			if($staIDList != "")
			{
				$where_conditions .= " AND page_id IN (".$staIDList.")";
			}
			else
			{
				$where_conditions .= " AND page_id IN (-1)";
			}
			//echo $where_conditions."<br>";*/
		}
		$query_string .= '&search_name='.$_REQUEST['search_name'].'&';
	break;
	case 'saved': // Case if Saved saerch  is selected
		$showtype = 'Saved search';
		$table_name = 'saved_search';
		$where_conditions = "WHERE sites_site_id = $ecom_siteid";
		if($_REQUEST['search_name']){
		$where_conditions .= " AND search_keyword like '%".$_REQUEST['search_name']."%' ";
		}
		
		$field_name		=	trim($_REQUEST['fieldName']);
		$field_data		=	trim($_REQUEST['fieldData']);
		$field_size		=	trim($_REQUEST['fieldSize']);
		
		if($field_name != "" && ($field_data != "" || $field_size != ""))
		{
			$where_conditions .= filterQueryGen($field_name, $field_data, $field_size, 'saved_search_search_id', 'se_search_title', 'search_id');
			/*if($_REQUEST['fieldName'] == 'title')
			{
				$field_condition	=	"LENGTH(title) > ".$_REQUEST['fieldSize'];
			}
			else if($_REQUEST['fieldName'] == 'desc')
			{
				$field_condition	=	"LENGTH(meta_description) > ".$_REQUEST['fieldSize'];
			}
			
			$sql_filter_svdid	=	"SELECT
													saved_search_search_id
											FROM
													se_search_title
											WHERE
													sites_site_id = $ecom_siteid
											AND
													$field_condition";
			//echo $sql_filter_svdid."<br>";
			$ret_filter_svdid	=	$db->query($sql_filter_svdid);
			
			$svdIDList	=	"";
			if($db->num_rows($ret_filter_svdid))
			{
				while($row_filter_svdid = $db->fetch_array($ret_filter_svdid))
				{
					$svdIDList	.=	$row_filter_svdid['saved_search_search_id'].",";
				}
				$svdIDList	=	substr($svdIDList, 0, -1);
				//echo $svdIDList."<br>";
			}
			if($svdIDList != "")
			{
				$where_conditions .= " AND search_id IN (".$svdIDList.")";
			}
			else
			{
				$where_conditions .= " AND search_id IN (-1)";
			}
			//echo $where_conditions."<br>";*/
		}
		$query_string .= '&search_name='.$_REQUEST['search_name'].'&';
	break;
};
if($keytype != 'home' && $keytype != 'bestsellers' && $keytype != 'help' && $keytype != 'faq' && $keytype != 'registration' && $keytype != 'sitemap' && $keytype != 'forgotpassword' && $keytype != 'sitereviews' && $keytype != 'savedsearchmain') {
	//Select condition for getting total count
	$sql_count = "SELECT count(*) as cnt FROM $table_name $where_conditions";
	$res_count = $db->query($sql_count);
	list($numcount) = $db->fetch_array($res_count);//#Getting total count of records
	
	/////////////////////////////////For paging///////////////////////////////////////////
	$records_per_page = ($_REQUEST['records_per_page'])?$_REQUEST['records_per_page']:10;//#Total records shown in a page
	$pg= $_REQUEST['pg'];
	
	$pages = ceil($numcount / $records_per_page);//#Getting the total pages
	if($pg > $pages) {
		$pg = $pages;
	}
	if (!($pg > 0) || $pg == 0) { $pg = 1; }
		$startrec = ($pg - 1) * $records_per_page;//#Starting record.
		
	
	$count_no = ($pg == 1)?0:$records_per_page*($pg-1);
	/////////////////////////////////////////////////////////////////////////////////////
}
$sql_site = "SELECT is_apparel_site FROM sites WHERE site_id=$ecom_siteid LIMIT 1";
$ret_site = $db->query($sql_site);
if ($db->num_rows($ret_site))
{
$row_site = $db->fetch_array($ret_site);
}
switch($keytype)
{
	case 'cat':
		$sql_categories = "SELECT category_id,category_name,parent_id FROM $table_name $where_conditions ORDER BY category_name LIMIT $startrec, $records_per_page ";
		$res = $db->query($sql_categories);
	break;
	case 'prod':
	if($row_site['is_apparel_site']==1)
	{	
		$filed_name  = " product_id,product_name,apparel_agegroup,apparel_gender,apparel_color,apparel_size ";
	}
	else
	{
	    $filed_name  = " product_id,product_name ";

	}
		$sql_products = "SELECT $filed_name FROM $table_name $where_conditions ORDER BY product_name LIMIT $startrec, $records_per_page ";
		$res = $db->query($sql_products);
	break;
	case 'shelf':
		$sql_shelf = "SELECT shelf_id,shelf_name FROM $table_name $where_conditions ORDER BY shelf_name LIMIT $startrec, $records_per_page ";
		$res = $db->query($sql_shelf);
	break;
	case 'shop':
		$sql_shop = "SELECT shopbrand_id,shopbrand_name FROM $table_name $where_conditions ORDER BY shopbrand_name LIMIT $startrec, $records_per_page ";
		$res = $db->query($sql_shop);
	break;
	case 'combo':
		$sql_combo = "SELECT combo_id,combo_name FROM $table_name $where_conditions ORDER BY combo_name LIMIT $startrec, $records_per_page ";
		$res = $db->query($sql_combo);
	break;
	case 'stat':
		$sql_stat = "SELECT page_id,title FROM $table_name $where_conditions ORDER BY title LIMIT $startrec, $records_per_page ";
		$res = $db->query($sql_stat);
	break;
	case 'saved':
		$sql_saved = "SELECT search_id,search_keyword FROM $table_name $where_conditions ORDER BY search_keyword LIMIT $startrec, $records_per_page ";
		$res = $db->query($sql_saved);
	break;
};

$query_string .= "request=seo_title&cbo_keytype=".$keytype;
?>
<script src="js/overlib_tree.js" language="javascript"></script>
<script type="text/javascript">
	
function handle_typechange()
{
	show_processing();
	document.frmSitetitles.retain_val.value 	= '<?php echo $keytype?>';
	document.frmSitetitles.type_change.value 	= 1;
	
	if(document.frmSitetitles.search_name)
	document.frmSitetitles.search_name.value 	= '';
	if(document.frmSitetitles.parentid)
	document.frmSitetitles.parentid.value 		= 0;
	
	document.frmSitetitles.pg.value 	= 0;
	document.frmSitetitles.submit();
}

function check_length_title(fieldName,divName)
{
	//alert(fieldName);alert(divName);
	maxLen = 65; // max number of characters allowed
	document.getElementById(divName).innerHTML = (parseInt(document.getElementById(fieldName).value.length)+1);
}
function check_length_description(fieldName,divName)
{
	//alert(fieldName);alert(divName);
	maxLen = 165; // max number of characters allowed
	document.getElementById(divName).innerHTML = (parseInt(document.getElementById(fieldName).value.length)+1);
}

function check_length_title_load(fieldName,divName)
{
	//alert(fieldName);alert(divName);
	maxLen = 65; // max number of characters allowed
	document.getElementById(divName).innerHTML = (parseInt(document.getElementById(fieldName).value.length));
}
function check_length_description_load(fieldName,divName)
{
	//alert(fieldName);alert(divName);
	maxLen = 165; // max number of characters allowed
	document.getElementById(divName).innerHTML = (parseInt(document.getElementById(fieldName).value.length));
}

function check_default(showTitle,titleID,titleDivID,showDesc,descID,descDivID)
{
	//alert("title - "+showTitle);
	//alert("descr - "+showDesc);
	if(showTitle != "")
	{
		check_length_title_load(titleID,titleDivID);
	}
	if(showDesc != "")
	{
		check_length_description_load(descID,descDivID);
	}
}
function filterDisplayAction()
{	
	if(document.getElementById('filterContent').style.display == 'none')
	{
		document.getElementById('filterContent').style.display	=	'block';
	}
	else
	{
		document.getElementById('filterContent').style.display	=	'none';
		document.getElementById('fieldName').value	=	"";
		document.getElementById('fieldData').value	=	"";
		document.getElementById('fieldSize').value	=	"";
	}
}
function validateNsubmit(actionVal)
{
	//alert(actionVal);
	//alert(document.getElementById('fieldName').value);
	//alert(document.getElementById('fieldSize').value);
	
	if(document.getElementById('fieldName').value != "")
	{
		if(document.getElementById('fieldSize').value != "" || document.getElementById('fieldData').value != "")
		{
			document.frmSitetitles.fpurpose.value = actionVal;
			document.frmSitetitles.submit();
		}
		else
		{
			alert('Please enter charecters count to submit.');
		}
	}
	else if(document.getElementById('fieldSize').value != "" || document.getElementById('fieldData').value != "")
	{
		if(document.getElementById('fieldName').value != "")
		{
			document.frmSitetitles.fpurpose.value = actionVal;
			document.frmSitetitles.submit();
		}
		else
		{
			alert('Please select field to submit.');
		}
	}
	else
	{
		document.frmSitetitles.fpurpose.value = actionVal;
		document.frmSitetitles.submit();
	}
}
</script>
<?php
	function filterQueryGen($fieldName, $fieldData, $fieldSize, $tableID, $tableName, $filterFieldID)
	{
		global $db, $ecom_siteid;
		$filter_conditions	=	"";
		$size_condition		=	"";
		$data_condition		=	"";
		
		if($fieldName != "" && ($fieldData != "" || $fieldSize != ""))
		{
			if($fieldName == 'title')
			{
				if($fieldSize != "")
				{	$size_condition	=	"LENGTH(title) > ".$fieldSize;	}
				
				if($fieldData != "")
				{	$data_condition	=	"title LIKE '%".$fieldData."%'";	}
			}
			else if($fieldName == 'desc')
			{
				if($fieldSize != "")
				{	$size_condition	=	"LENGTH(meta_description) > ".$fieldSize;	}
				
				if($fieldData != "")
				{	$data_condition	=	"meta_description LIKE '%".$fieldData."%'";	}
			}
			
			if($size_condition != "" && $data_condition != "")
			{	$field_condition	=	"(".$size_condition." AND ".$data_condition.")";	}
			else if($size_condition != "")
			{	$field_condition	=	"".$size_condition."";	}
			else if($data_condition != "")
			{	$field_condition	=	"".$data_condition."";	}
			
			$sql_filter_id	=	"SELECT
												".$tableID."
										FROM
												".$tableName."
										WHERE
												sites_site_id = ".$ecom_siteid."
										AND
												".$field_condition;
			//echo $sql_filter_id."<br>";
			$ret_filter_id	=	$db->query($sql_filter_id);
			
			$fltrIDList	=	"";
			if($db->num_rows($ret_filter_id))
			{
				while($row_filter_id = $db->fetch_array($ret_filter_id))
				{
					$fltrIDList	.=	$row_filter_id[$tableID].",";
				}
				$fltrIDList	=	substr($fltrIDList, 0, -1);
				//echo $fltrIDList."<br>";
			}
			if($fltrIDList != "")
			{
				$filter_conditions .= " AND ".$filterFieldID." IN (".$fltrIDList.")";
			}
			else
			{
				$filter_conditions .= " AND ".$filterFieldID." IN (-1)";
			}
			//echo $filter_conditions."<br>";
		}
		return $filter_conditions;
	}
	function showFilter()
	{
		if($_REQUEST['fieldName'] != "" || $_REQUEST['fieldSize'] != "")
		{
			$dispCond	=	'display:block;';
		}
		else
		{
			$dispCond	=	'display:none;';
		}
?>
		<div id="filter">
        	<div id="filterLabel"><a href="javascript:void(0);" onclick="javascript: filterDisplayAction();" style="font-family:Arial,Helvetica,sans-serif; font-size:11px; font-weight:normal; color:#F00; text-decoration:;">Filters</a></div>
            <div id="filterContent" style=" <?php echo $dispCond;?>">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="30%" height="35" align="left" valign="middle">Field</td>
                  <td width="49%" height="35" align="left" valign="middle"><select name="fieldName" id="fieldName">
                    <option value="" selected="selected">--Select--</option>
                    <option value="title" <?php if($_REQUEST['fieldName'] == "title") echo ' selected="selected"'; ?>>Title</option>
                    <option value="desc" <?php if($_REQUEST['fieldName'] == "desc") echo ' selected="selected"'; ?>>Description</option>
                  </select></td>
                  <td width="21%" height="35" align="left" valign="middle">&nbsp;</td>
                </tr>
                <tr>
                  <td width="30%" height="35" align="left" valign="middle">Items having Content Like</td>
                  <td width="49%" height="35" align="left" valign="middle" colspan="2"><input type="text" name="fieldData" id="fieldData" value="<?php echo $_REQUEST['fieldData'];?>" size="45" /></td>
                </tr>
                <tr>
                  <td height="35" align="left" valign="middle">Items having More Than</td>
                  <td height="35" align="left" valign="middle"><input type="text" name="fieldSize" id="fieldSize" value="<?php echo $_REQUEST['fieldSize'];?>" />
                  &nbsp;Characters</td>
                  <td height="35" align="left" valign="middle">&nbsp;</td>
                </tr>
              </table>
            </div>
        </div>
<?php
	}
?>
<form method="post" action="home.php?request=seo_title" name="frmSitetitles">
<input type="hidden" name="fpurpose" value="Assign_titles" />
<input type="hidden" name="pg" value="<?php echo $_REQUEST['pg']?>" />
<input type="hidden" name="type_change" value="" />
<input type="hidden" name="retain_val" value="" />

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="2" align="left" valign="top" class="treemenutd"><div class="treemenutd_div"><span> Assign SEO <?=$page_type?></span></div>
   <br />
    <img src="images/blueline.gif" alt="" border="0" height="1" width="400" /></td>
    </tr>
 	 <tr>
	  <td align="left" valign="middle" class="helpmsgtd_main" colspan="2">
	  <?php 
		  Display_Main_Help_msg($help_arr,$help_msg);
	  ?>
	 </td>
	</tr>
  <tr>
    <td colspan="2"  align="center" valign="top" >
	<div class="editarea_div">
	 <table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="2"  align="center" valign="top" >
	 <table width="100%" border="0" cellpadding="0" cellspacing="0">

      <tr>
        <td valign="top" align="left" class="seperationtd" width="35%">&nbsp;<strong>Select type</strong>          <?php
		  $keytype_arr = array('home'=>'Home','cat'=>'Categories','prod'=>'Products','shop'=>'Shops','combo'=>'Combo Deals','shelf'=>'Shelves','bestsellers'=>'Best Sellers','stat'=>'Static Pages','saved'=>'Saved Search','savedsearchmain'=>'Saved Search Main','help'=>'Help','faq'=>'FAQ','registration'=>'Registration','sitemap'=>'Sitemap','forgotpassword'=>'Forgot Password','sitereviews'=>'Site Reviews');
		  echo generateselectbox('cbo_keytype',$keytype_arr,$_REQUEST['cbo_keytype'],'','handle_typechange()');
	  ?>        &nbsp;<a href="#" style="cursor:pointer;" onmouseover="return ddrivetip('<?=get_help_messages('LIST_SITE_TITLES_TYPE')?>');" onmouseout="return hideddrivetip();"><img src="images/helpicon.png" border="0" alt="" /></a></td>
      
       <td colspan="2"  align="left" valign="top" class="listeditd">
		<?php if($keytype == 'cat') {?>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="20%" height="35" align="left" valign="middle">Category Name </td>
            <td width="65%" height="35" align="left" valign="middle"><input name="search_name" type="text" class="textfeild" id="search_name" value="<?php echo $_REQUEST['search_name']?>" /></td>
            <td width="18%" height="35" align="left" valign="middle">&nbsp;</td>
          </tr>
          <tr>
            <td height="35" align="left" valign="middle">Parent Category</td>
            <td height="35" align="left" valign="middle">
			<?php
			  	$parent_arr = generate_category_tree(0,0,true);
				if(is_array($parent_arr))
				{
					echo generateselectbox('parentid',$parent_arr,$_REQUEST['parentid']);
				}
			  ?></td>
            <td height="35" align="left" valign="middle">&nbsp;</td>
          </tr>
           <tr>
            <td height="35" align="left" valign="middle" colspan="2"><?php showFilter(); ?></td>
            <td height="35" align="left" valign="middle"><input name="Search_go" type="button" class="red" id="Search_go" value="Go" onclick="javascript: validateNsubmit('search_<?=$keytype?>');" />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_SITE_TITLES_CATEGORY_SEARCH_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          </tr>
        </table>
		  <?php
		  }elseif($keytype == 'prod'){?>
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="20%" height="35" align="left" valign="middle">Product Name</td>
            <td width="65%" height="35" align="left" valign="middle"><input name="search_name" type="text" class="textfeild" id="search_name" value="<?php echo $_REQUEST['search_name']?>" /></td>
            <td width="18%" height="35" align="left" valign="middle">&nbsp;</td>
          </tr>
          <tr>
            <td height="35" align="left" valign="middle">Category</td>
            <td height="35" align="left" valign="middle">
			<?php
			  	$parent_arr = generate_category_tree(0,0,true);
				if(is_array($parent_arr))
				{
					echo generateselectbox('parentid',$parent_arr,$_REQUEST['parentid']);
				}
			  ?></td>
            <td height="35" align="left" valign="middle">&nbsp;</td>
          </tr>
           <tr>
            <td height="35" align="left" valign="middle" colspan="2"><?php showFilter(); ?></td>
            <td height="35" align="left" valign="middle"><input name="Search_go" type="button" class="red" id="Search_go" value="Go" onclick="javascript: validateNsubmit('search_<?=$keytype?>');" />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_SITE_TITLES_PRODUCTS_SEARCH_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          </tr>
        </table>
		   
		  <?
		    } elseif($keytype == 'shop'){?>
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="20%" height="35" align="left" valign="middle">Shop Name</td>
            <td width="65%" height="35" align="left" valign="middle"><input name="search_name" type="text" class="textfeild" id="search_name" value="<?php echo $_REQUEST['search_name']?>" /></td>
            <td width="18%" height="35" align="left" valign="middle">&nbsp;</td>
          </tr>
          
           <tr>
            <td height="35" align="left" valign="middle" colspan="2"><?php showFilter(); ?></td>
            <td height="35" align="left" valign="middle"><input name="Search_go" type="button" class="red" id="Search_go" value="Go" onclick="javascript: validateNsubmit('search_<?=$keytype?>');" />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_SITE_TITLES_SHOPS_SEARCH_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          </tr>
        </table>
		  
		  <?
		    }  
			 elseif($keytype == 'shelf'){?>
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="20%" height="35" align="left" valign="middle">Shelf Name</td>
            <td width="65%" height="35" align="left" valign="middle"> 
	     	<input name="search_name" type="text" class="textfeild" id="search_name" value="<?php echo $_REQUEST['search_name']?>" /></td>
            <td width="18%" height="35" align="left" valign="middle">&nbsp;</td>
          </tr>
          
           <tr>
            <td height="35" align="left" valign="middle" colspan="2"><?php showFilter(); ?></td>
            <td height="35" align="left" valign="middle"><input name="Search_go" type="button" class="red" id="Search_go" value="Go" onclick="javascript: validateNsubmit('search_<?=$keytype?>');" />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_SITE_TITLES_SHELF_SEARCH_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          </tr>
        </table>
		  <?
		    }  
			elseif($keytype == 'combo'){?>
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="20%" height="35" align="left" valign="middle">Combo Name</td>
            <td width="65%" height="35" align="left" valign="middle">
	      	<input name="search_name" type="text" class="textfeild" id="search_name" value="<?php echo $_REQUEST['search_name']?>" /></td>
            <td width="18%" height="35" align="left" valign="middle">&nbsp;</td>
          </tr>
          
           <tr>
            <td height="35" align="left" valign="middle" colspan="2"><?php showFilter(); ?></td>
            <td height="35" align="left" valign="middle"><input name="Search_go" type="button" class="red" id="Search_go" value="Go" onclick="javascript: validateNsubmit('search_<?=$keytype?>');" />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_SITE_TITLES_COMBO_SEARCH_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          </tr>
        </table>
		  <?
		    } elseif($keytype == 'stat'){?>
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="20%" height="35" align="left" valign="middle">Static Page Name </td>
            <td width="65%" height="35" align="left" valign="middle"> 
	      	<input name="search_name" type="text" class="textfeild" id="search_name" value="<?php echo $_REQUEST['search_name']?>" /></td>
            <td width="18%" height="35" align="left" valign="middle">&nbsp;</td>
          </tr>
          
           <tr>
            <td height="35" align="left" valign="middle" colspan="2"><?php showFilter(); ?></td>
            <td height="35" align="left" valign="middle"><input name="Search_go" type="button" class="red" id="Search_go" value="Go" onclick="javascript: validateNsubmit('search_<?=$keytype?>');" />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_SITE_TITLES_STATIC_SEARCH_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          </tr>
        </table>
		  <?
		    }elseif($keytype == 'saved'){?>
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="20%" height="35" align="left" valign="middle">Saved Keyword </td>
            <td width="65%" height="35" align="left" valign="middle"> 
	      	<input name="search_name" type="text" class="textfeild" id="search_name" value="<?php echo $_REQUEST['search_name']?>" /></td>
            <td width="18%" height="35" align="left" valign="middle">&nbsp;</td>
          </tr>
          
           <tr>
            <td height="35" align="left" valign="middle" colspan="2"><?php showFilter(); ?></td>
            <td height="35" align="left" valign="middle"><input name="Search_go" type="button" class="red" id="Search_go" value="Go" onclick="javascript: validateNsubmit('search_<?=$keytype?>');" />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_SITE_TITLES_SAVED_SEARCH_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>></td>
          </tr>
        </table>
        
		  <?
		    } ?></td>
	 </tr>
      
      <tr>
        <td valign="middle" align="left" class="seperationtd">&nbsp;Assign Page Title and Meta Description for <?php echo $showtype?></td>
        <td valign="middle" align="center" class="listeditd"> 
          <?php  if($keytype != 'home' && $keytype != 'bestsellers' && $keytype != 'help' && $keytype != 'faq' && $keytype != 'registration' && $keytype != 'sitemap' && $keytype != 'forgotpassword' && $keytype != 'sitereviews' && $keytype != 'savedsearchmain') {
		   paging_footer($query_string,$numcount,$pg,$pages,$showtype,0); }
		    ?>         </td>
        <td valign="middle" align="left" class="listeditd">&nbsp;</td>
      </tr>
	  <?php
	  if($keytype == 'home') {
			//Check whether any title assigned for the current property. If so show then in the text boxes
				$count_no++;
				if($count_no %2 == 0)
				$class_val="listingtablestyleB";
				else
				$class_val="listingtableheader";	
				$sql_home = "SELECT title,meta_description FROM se_home_title 
							WHERE sites_site_id=$ecom_siteid ";
				$ret_home = $db->query($sql_home);
				$show_arr = array();
				if ($db->num_rows($ret_home))
				{
					$row_home = $db->fetch_array($ret_home);
					$show_val = stripslashes($row_home['title']);
					$show_metaDesc	= stripslashes($row_home['meta_description']);
				}
				else {
					$show_val  ='';
					$show_metaDesc ='';
					}
			?>
      <tr>
        <td valign="middle" align="left" colspan="3">
		<table width="100%" border="0" cellspacing="0" cellpadding="2">
		 <tr>
		   <td align="left" class="<?= $class_val?>" width="2%"><?php echo $count_no?>.</td>
		   <td align="left" class="<?= $class_val?>"><a href="#" class="edittextlink"><strong>Home</strong></a></td>
		   </tr>
		 <td align="left" class="<?= $class_val?>" width="2%">&nbsp;</td>
              <td align="left" class="<?= $class_val?>"><table width="100%" border="0">
                <tr>
                  <td><b>Title:</b></td>
                  <td>
                  	<input type="text" name="txthome_" value="<?php echo $show_val?>" size="84" onkeydown="check_length_title('txthome_','home_remain_title')" onKeyPress="check_length_title('txthome_','home_remain_title')" onblur="check_length_title_load('txthome_','home_remain_title')" id="txthome_" style="float:left;"/>
                    <div id="home_remain_title" style=" padding-left:5px;width:2%; float:left;">0</div>Characters
                  </td>
                </tr>
                <tr>
                  <td><b>Meta description:</b></td>
                  <td>
                  <textarea  name="txtmetahome_"cols="63" rows="2" id="txtmetahome_" onkeydown="check_length_description('txtmetahome_','home_remain_description')" onKeyPress="check_length_description('txtmetahome_','home_remain_description')" onblur="check_length_description_load('txtmetahome_','home_remain_description')" style="float:left;"><?php echo $show_metaDesc?></textarea>
                  <div id="home_remain_description" style=" padding-left:5px;width:2%; float:left;">0</div>Characters
                  </td>
                </tr>
              </table></td>
              </tr>
        </table></td>
      </tr>
		<script language="javascript">
		check_default('<?=$show_val?>','txthome_','home_remain_title','<?=$show_metaDesc?>','txtmetahome_','home_remain_description');
        </script>
		<?php
	  }
	  
	   elseif($keytype == 'help') {
			//Check whether any title assigned for the current property. If so show then in the text boxes
				$count_no++;
				if($count_no %2 == 0)
				$class_val="listingtablestyleB";
				else
				$class_val="listingtableheader";	
				$sql_help = "SELECT title,meta_description FROM se_help_title 
							WHERE sites_site_id=$ecom_siteid ";
				$ret_help = $db->query($sql_help);
				$show_arr = array();
				if ($db->num_rows($ret_help))
				{
					$row_help = $db->fetch_array($ret_help);
					$show_val = stripslashes($row_help['title']);
					$show_metaDesc	= stripslashes($row_help['meta_description']);
				}
				else {
					$show_val  ='';
					$show_metaDesc ='';
					}
			?>
      <tr>
        <td valign="middle" align="left" colspan="3">
		<table width="100%" border="0" cellspacing="0" cellpadding="2">
		 <tr>
		   <td align="left" class="<?= $class_val?>" width="2%"><?php echo $count_no?>.</td>
		   <td align="left" class="<?= $class_val?>"><a href="#" class="edittextlink"><strong>Help</strong></a></td>
		   </tr>
		 <td align="left" class="<?= $class_val?>" width="2%">&nbsp;</td>
              <td align="left" class="<?= $class_val?>"><table width="100%" border="0">
                <tr>
                  <td><b>Title:</b></td>
                  <td><input type="text" name="txthelp_" value="<?php echo $show_val?>" size="84" onkeydown="check_length_title('txthelp_','help_remain_title')" onKeyPress="check_length_title('txthelp_','help_remain_title')" onblur="check_length_title_load('txthelp_','help_remain_title')" id="txthelp_" style="float:left;"/>
                   <div id="help_remain_title" style=" padding-left:5px;width:2%; float:left;">0</div>Characters</td>
                </tr>
                <tr>
                  <td><b>Meta description:</b></td>
                  <td><textarea  name="txtmetahelp_"cols="63" rows="2" onkeydown="check_length_description('txtmetahelp_','help_remain_description')" onKeyPress="check_length_description('txtmetahelp_','help_remain_description')" onblur="check_length_description_load('txtmetahelp_','help_remain_description')" id="txtmetahelp_" style="float:left;"><?php echo $show_metaDesc?></textarea>
                   <div id="help_remain_description" style=" padding-left:5px;width:2%; float:left;">0</div>Characters</td>
                </tr>
              </table></td>
              </tr>
        </table></td>
      </tr>
		<script language="javascript">
		check_default('<?=$show_val?>','txthelp_','help_remain_title','<?=$show_metaDesc?>','txtmetahelp_','help_remain_description');
        </script>
      
      <?php				
	  }
	  elseif($keytype == 'faq') {
			//Check whether any title assigned for the current property. If so show then in the text boxes
				$count_no++;
				if($count_no %2 == 0)
				$class_val="listingtablestyleB";
				else
				$class_val="listingtableheader";	
				$sql_help = "SELECT title,meta_description FROM se_faq_title 
							WHERE sites_site_id=$ecom_siteid ";
				$ret_help = $db->query($sql_help);
				$show_arr = array();
				if ($db->num_rows($ret_help))
				{
					$row_help = $db->fetch_array($ret_help);
					$show_val = stripslashes($row_help['title']);
					$show_metaDesc	= stripslashes($row_help['meta_description']);
				}
				else {
					$show_val  ='';
					$show_metaDesc ='';
					}
			?>
      <tr>
        <td valign="middle" align="left" colspan="3">
		<table width="100%" border="0" cellspacing="0" cellpadding="2">
		 <tr>
		   <td align="left" class="<?= $class_val?>" width="2%"><?php echo $count_no?>.</td>
		   <td align="left" class="<?= $class_val?>"><a href="#" class="edittextlink"><strong>FAQ</strong></a></td>
		   </tr>
		 <td align="left" class="<?= $class_val?>" width="2%">&nbsp;</td>
              <td align="left" class="<?= $class_val?>"><table width="100%" border="0">
                <tr>
                  <td><b>Title:</b></td>
                  <td><input type="text" name="txtfaq_" value="<?php echo $show_val?>" size="84" onkeydown="check_length_title('txtfaq_','faq_remain_title')" onKeyPress="check_length_title('txtfaq_','faq_remain_title')" onblur="check_length_title_load('txtfaq_','faq_remain_title')" id="txtfaq_" style="float:left;"/>
                   <div id="faq_remain_title" style=" padding-left:5px;width:2%; float:left;">0</div>Characters</td>
                </tr>
                <tr>
                  <td><b>Meta description:</b></td>
                  <td><textarea  name="txtmetafaq_"cols="63" rows="2" onkeydown="check_length_description('txtmetafaq_','help_remain_description')" onKeyPress="check_length_description('txtmetafaq_','faq_remain_description')" onblur="check_length_description_load('txtmetafaq_','help_remain_description')" id="txtmetafaq_" style="float:left;"><?php echo $show_metaDesc?></textarea>
                   <div id="faq_remain_description" style=" padding-left:5px;width:2%; float:left;">0</div>Characters</td>
                </tr>
              </table></td>
              </tr>
        </table></td>
      </tr>
		<script language="javascript">
		check_default('<?=$show_val?>','txtfaq_','faq_remain_title','<?=$show_metaDesc?>','txtmetafaq_','faq_remain_description');
        </script>
      
      
      <?php				
	  }
	   elseif($keytype == 'registration') {
			//Check whether any title assigned for the current property. If so show then in the text boxes
				$count_no++;
				if($count_no %2 == 0)
				$class_val="listingtablestyleB";
				else
				$class_val="listingtableheader";	
				$sql_registration = "SELECT title,meta_description FROM se_registration_title 
							WHERE sites_site_id=$ecom_siteid ";
				$ret_registration = $db->query($sql_registration);
				$show_arr = array();
				if ($db->num_rows($ret_registration))
				{
					$row_registration = $db->fetch_array($ret_registration);
					$show_val = stripslashes($row_registration['title']);
					$show_metaDesc	= stripslashes($row_registration['meta_description']);
				}
				else {
					$show_val  ='';
					$show_metaDesc ='';
					}
			?>
      <tr>
        <td valign="middle" align="left" colspan="3">
		<table width="100%" border="0" cellspacing="0" cellpadding="2">
		 <tr>
		   <td align="left" class="<?= $class_val?>" width="2%"><?php echo $count_no?>.</td>
		   <td align="left" class="<?= $class_val?>"><a href="#" class="edittextlink"><strong>Registration</strong></a></td>
		   </tr>
		 <td align="left" class="<?= $class_val?>" width="2%">&nbsp;</td>
              <td align="left" class="<?= $class_val?>"><table width="100%" border="0">
                <tr>
                  <td><b>Title:</b></td>
                  <td><input type="text" name="txtregistration_" value="<?php echo $show_val?>" size="84" onkeydown="check_length_title('txtregistration_','registration_remain_title')" onKeyPress="check_length_title('txtregistration_','registration_remain_title')" onblur="check_length_title_load('txtregistration_','registration_remain_title')" id="txtregistration_" style="float:left;"/>
                   <div id="registration_remain_title" style=" padding-left:5px;width:2%; float:left;">0</div>Characters</td>
                </tr>
                <tr>
                  <td><b>Meta description:</b></td>
                  <td><textarea  name="txtmetaregistration_"cols="63" rows="2" onkeydown="check_length_description('txtmetaregistration_','registration_remain_description')" onKeyPress="check_length_description('txtmetaregistration_','registration_remain_description')" onblur="check_length_description_load('txtmetaregistration_','registration_remain_description')" id="txtmetaregistration_" style="float:left;"><?php echo $show_metaDesc?></textarea>
                   <div id="registration_remain_description" style=" padding-left:5px;width:2%; float:left;">0</div>Characters</td>
                </tr>
              </table></td>
              </tr>
        </table></td>
      </tr>
		<script language="javascript">
		check_default('<?=$show_val?>','txtregistration_','registration_remain_title','<?=$show_metaDesc?>','txtmetaregistration_','registration_remain_description');
        </script>
            
      <?php				
	  }
	  elseif($keytype == 'savedsearchmain') {
			//Check whether any title assigned for the current saved search main opage. If so show then in the text boxes
				$count_no++;
				if($count_no %2 == 0)
				$class_val="listingtablestyleB";
				else
				$class_val="listingtableheader";	
				$sql_savedsearchmain = "SELECT title,meta_description FROM se_savedsearchmain_title 
							WHERE sites_site_id=$ecom_siteid ";
				$ret_savedsearchmain = $db->query($sql_savedsearchmain);
				$show_arr = array();
				if ($db->num_rows($ret_savedsearchmain))
				{
					$row_savedsearchmain = $db->fetch_array($ret_savedsearchmain);
					$show_val = stripslashes($row_savedsearchmain['title']);
					$show_metaDesc	= stripslashes($row_savedsearchmain['meta_description']);
				}
				else {
					$show_val  ='';
					$show_metaDesc ='';
					}
			?>
      <tr>
        <td valign="middle" align="left" colspan="3">
		<table width="100%" border="0" cellspacing="0" cellpadding="2">
		 <tr>
		   <td align="left" class="<?= $class_val?>" width="2%"><?php echo $count_no?>.</td>
		   <td align="left" class="<?= $class_val?>"><a href="#" class="edittextlink"><strong>Saved Search Main Page</strong></a></td>
		   </tr>
		 <td align="left" class="<?= $class_val?>" width="2%">&nbsp;</td>
              <td align="left" class="<?= $class_val?>"><table width="100%" border="0">
                <tr>
                  <td><b>Title:</b></td>
                  <td><input type="text" name="txtsavedsearchmain_" value="<?php echo $show_val?>" size="84" onkeydown="check_length_title('txtsavedsearchmain_','savedsearch_remain_title')" onKeyPress="check_length_title('txtsavedsearchmain_','savedsearch_remain_title')" onblur="check_length_title_load('txtsavedsearchmain_','savedsearch_remain_title')" id="txtsavedsearchmain_" style="float:left;"/>
                   <div id="savedsearch_remain_title" style=" padding-left:5px;width:2%; float:left;">0</div>Characters</td>
                </tr>
                <tr>
                  <td><b>Meta description:</b></td>
                  <td><textarea  name="txtmetasavedsearchmain_"cols="63" rows="2" onkeydown="check_length_description('txtmetasavedsearchmain_','savedsearch_remain_description')" onKeyPress="check_length_description('txtmetasavedsearchmain_','savedsearch_remain_description')" onblur="check_length_description_load('txtmetasavedsearchmain_','savedsearch_remain_description')" id="txtmetasavedsearchmain_" style="float:left;"><?php echo $show_metaDesc?></textarea>
                   <div id="savedsearch_remain_description" style=" padding-left:5px;width:2%; float:left;">0</div>Characters</td>
                </tr>
              </table></td>
              </tr>
        </table></td>
      </tr>
		<script language="javascript">
		check_default('<?=$show_val?>','txtsavedsearchmain_','savedsearch_remain_title','<?=$show_metaDesc?>','txtmetasavedsearchmain_','savedsearch_remain_description');
        </script>
                
      <?php				
	  }
	  elseif($keytype == 'sitemap') {
			//Check whether any title assigned for the current property. If so show then in the text boxes
				$count_no++;
				if($count_no %2 == 0)
				$class_val="listingtablestyleB";
				else
				$class_val="listingtableheader";	
				$sql_sitemap = "SELECT title,meta_description FROM se_sitemap_title 
							WHERE sites_site_id=$ecom_siteid ";
				$ret_sitemap = $db->query($sql_sitemap);
				$show_arr = array();
				if ($db->num_rows($ret_sitemap))
				{
					$row_sitemap = $db->fetch_array($ret_sitemap);
					$show_val = stripslashes($row_sitemap['title']);
					$show_metaDesc	= stripslashes($row_sitemap['meta_description']);
				}
				else {
					$show_val  ='';
					$show_metaDesc ='';
					}
			?>
      <tr>
        <td valign="middle" align="left" colspan="3">
		<table width="100%" border="0" cellspacing="0" cellpadding="2">
		 <tr>
		   <td align="left" class="<?= $class_val?>" width="2%"><?php echo $count_no?>.</td>
		   <td align="left" class="<?= $class_val?>"><a href="#" class="edittextlink"><strong>Sitemap</strong></a></td>
		   </tr>
		 <td align="left" class="<?= $class_val?>" width="2%">&nbsp;</td>
              <td align="left" class="<?= $class_val?>"><table width="100%" border="0">
                <tr>
                  <td><b>Title:</b></td>
                  <td><input type="text" name="txtsitemap_" value="<?php echo $show_val?>" size="84" onkeydown="check_length_title('txtsitemap_','sitemap_remain_title')" onKeyPress="check_length_title('txtsitemap_','sitemap_remain_title')" onblur="check_length_title_load('txtsitemap_','sitemap_remain_title')" id="txtsitemap_" style="float:left;"/>
                   <div id="sitemap_remain_title" style=" padding-left:5px;width:2%; float:left;">0</div>Characters</td>
                </tr>
                <tr>
                  <td><b>Meta description:</b></td>
                  <td><textarea  name="txtmetasitemap_"cols="63" rows="2" onkeydown="check_length_description('txtmetasitemap_','sitemap_remain_description')" onKeyPress="check_length_description('txtmetasitemap_','sitemap_remain_description')" onblur="check_length_description_load('txtmetasitemap_','sitemap_remain_description')" id="txtmetasitemap_" style="float:left;"><?php echo $show_metaDesc?></textarea>
                   <div id="sitemap_remain_description" style=" padding-left:5px;width:2%; float:left;">0</div>Characters</td>
                </tr>
              </table></td>
              </tr>
        </table></td>
      </tr>
		<script language="javascript">
		check_default('<?=$show_val?>','txtsitemap_','sitemap_remain_title','<?=$show_metaDesc?>','txtmetasitemap_','sitemap_remain_description');
        </script>
               
      <?php				
	  }
	  
	   elseif($keytype == 'forgotpassword') {
			//Check whether any title assigned for the current property. If so show then in the text boxes
				$count_no++;
				if($count_no %2 == 0)
				$class_val="listingtablestyleB";
				else
				$class_val="listingtableheader";	
				$sql_forgotpassword = "SELECT title,meta_description FROM se_forgotpassword_title 
							WHERE sites_site_id=$ecom_siteid ";
				$ret_forgotpassword = $db->query($sql_forgotpassword);
				$show_arr = array();
				if ($db->num_rows($ret_forgotpassword))
				{
					$row_forgotpassword = $db->fetch_array($ret_forgotpassword);
					$show_val = stripslashes($row_forgotpassword['title']);
					$show_metaDesc	= stripslashes($row_forgotpassword['meta_description']);
				}
				else {
					$show_val  ='';
					$show_metaDesc ='';
					}
			?>
      <tr>
        <td valign="middle" align="left" colspan="3">
		<table width="100%" border="0" cellspacing="0" cellpadding="2">
		 <tr>
		   <td align="left" class="<?= $class_val?>" width="2%"><?php echo $count_no?>.</td>
		   <td align="left" class="<?= $class_val?>"><a href="#" class="edittextlink"><strong>Forgot Password</strong></a></td>
		   </tr>
		 <td align="left" class="<?= $class_val?>" width="2%">&nbsp;</td>
              <td align="left" class="<?= $class_val?>"><table width="100%" border="0">
                <tr>
                  <td><b>Title:</b></td>
                  <td><input type="text" name="txtforgotpassword_" value="<?php echo $show_val?>" size="84" onkeydown="check_length_title('txtforgotpassword_','password_remain_title')" onKeyPress="check_length_title('txtforgotpassword_','password_remain_title')" onblur="check_length_title_load('txtforgotpassword_','password_remain_title')" id="txtforgotpassword_" style="float:left;"/>
                   <div id="password_remain_title" style=" padding-left:5px;width:2%; float:left;">0</div>Characters</td>
                </tr>
                <tr>
                  <td><b>Meta description:</b></td>
                  <td><textarea  name="txtmetaforgotpassword_"cols="63" rows="2" onkeydown="check_length_description('txtmetaforgotpassword_','password_remain_description')" onKeyPress="check_length_description('txtmetaforgotpassword_','password_remain_description')" onblur="check_length_description_load('txtmetaforgotpassword_','password_remain_description')" id="txtmetaforgotpassword_" style="float:left;"><?php echo $show_metaDesc?></textarea>
                   <div id="password_remain_description" style=" padding-left:5px;width:2%; float:left;">0</div>Characters</td>
                </tr>
              </table></td>
              </tr>
        </table></td>
      </tr>
		<script language="javascript">
		check_default('<?=$show_val?>','txtforgotpassword_','password_remain_title','<?=$show_metaDesc?>','txtmetaforgotpassword_','password_remain_description');
        </script>
      
      <?php				
	  }
	     elseif($keytype == 'sitereviews') {
			//Check whether any title assigned for the current property. If so show then in the text boxes
				$count_no++;
				if($count_no %2 == 0)
				$class_val="listingtablestyleB";
				else
				$class_val="listingtableheader";	
				$sql_sitereviews = "SELECT title,meta_description FROM se_sitereviews_title 
							WHERE sites_site_id=$ecom_siteid ";
				$ret_sitereviews = $db->query($sql_sitereviews);
				$show_arr = array();
				if ($db->num_rows($ret_sitereviews))
				{
					$row_sitereviews = $db->fetch_array($ret_sitereviews);
					$show_val = stripslashes($row_sitereviews['title']);
					$show_metaDesc	= stripslashes($row_sitereviews['meta_description']);
				}
				else {
					$show_val  ='';
					$show_metaDesc ='';
					}
			?>
      <tr>
        <td valign="middle" align="left" colspan="3">
		<table width="100%" border="0" cellspacing="0" cellpadding="2">
		 <tr>
		   <td align="left" class="<?= $class_val?>" width="2%"><?php echo $count_no?>.</td>
		   <td align="left" class="<?= $class_val?>"><a href="#" class="edittextlink"><strong>Site Reviews</strong></a></td>
		   </tr>
		 <td align="left" class="<?= $class_val?>" width="2%">&nbsp;</td>
              <td align="left" class="<?= $class_val?>"><table width="100%" border="0">
                <tr>
                  <td><b>Title:</b></td>
                  <td><input type="text" name="txtsitereviews_" value="<?php echo $show_val?>" size="84" onkeydown="check_length_title('txtsitereviews_','reviews_remain_title')" onKeyPress="check_length_title('txtsitereviews_','reviews_remain_title')" onblur="check_length_title_load('txtsitereviews_','reviews_remain_title')" id="txtsitereviews_" style="float:left;"/>
                   <div id="reviews_remain_title" style=" padding-left:5px;width:2%; float:left;">0</div>Characters</td>
                </tr>
                <tr>
                  <td><b>Meta description:</b></td>
                  <td><textarea  name="txtmetasitereviews_"cols="63" rows="2" onkeydown="check_length_description('txtmetasitereviews_','reviews_remain_description')" onblur="check_length_description_load('txtmetasitereviews_','reviews_remain_description')" onKeyPress="check_length_description('txtmetasitereviews_','reviews_remain_description')" id="txtmetasitereviews_" style="float:left;"><?php echo $show_metaDesc?></textarea>
                   <div id="reviews_remain_description" style=" padding-left:5px;width:2%; float:left;">0</div>Characters</td>
                </tr>
              </table></td>
              </tr>
        </table></td>
      </tr>
		<script language="javascript">
		check_default('<?=$show_val?>','txtsitereviews_','reviews_remain_title','<?=$show_metaDesc?>','txtmetasitereviews_','reviews_remain_description');
        </script>
      
      <?php				
	  }
	  elseif($keytype =='bestsellers')
	  {
	  
	  
			//Check whether any title assigned for the best sellers. If so show then in the text boxes
				$count_no++;
				if($count_no %2 == 0)
				$class_val="listingtablestyleB";
				else
				$class_val="listingtableheader";	
				$sql_bestseller = "SELECT title,meta_description FROM se_bestseller_titles 
							WHERE sites_site_id=$ecom_siteid ";
				$ret_bestseller = $db->query($sql_bestseller);
				$show_arr = array();
				if ($db->num_rows($ret_bestseller))
				{
					$row_bestseller = $db->fetch_array($ret_bestseller);
					$show_val = stripslashes($row_bestseller['title']);
					$show_metaDesc	= stripslashes($row_bestseller['meta_description']);
				}
				else {
					$show_val  ='';
					$show_metaDesc ='';
					}
			?>
      <tr>
        <td valign="middle" align="left" colspan="3">
		<table width="100%" border="0" cellspacing="0" cellpadding="2">
		 <tr>
		   <td align="left" class="<?= $class_val?>" width="2%"><?php echo $count_no?>.</td>
		   <td align="left" class="<?= $class_val?>"><strong>Best sellers</strong></td>
		   </tr>
		 <td align="left" class="<?= $class_val?>" width="2%">&nbsp;</td>
              <td align="left" class="<?= $class_val?>"><table width="100%" border="0">
                <tr>
                  <td><b>Title:</b></td>
                  <td><input type="text" name="txtbestsellers_" value="<?php echo $show_val;?>" size="84" onkeydown="check_length_title('txtbestsellers_','bestseller_remain_title')" onKeyPress="check_length_title('txtbestsellers_','bestseller_remain_title')" onblur="check_length_title_load('txtbestsellers_','bestseller_remain_title')" id="txtbestsellers_" style="float:left;"/>
                   <div id="bestseller_remain_title" style=" padding-left:5px;width:2%; float:left;">0</div>Characters</td>
                </tr>
                <tr>
                  <td><b>Meta description:</b></td>
                  <td><textarea name="txtmetabestsellers_" cols="63" rows="2" onkeydown="check_length_description('txtmetabestsellers_','bestseller_remain_description')" onKeyPress="check_length_description('txtmetabestsellers_','bestseller_remain_description')" onblur="check_length_description_load('txtmetabestsellers_','bestseller_remain_description')" id="txtmetabestsellers_" style="float:left;"><?php echo $show_metaDesc?></textarea>
                   <div id="bestseller_remain_description" style=" padding-left:5px;width:2%; float:left;">0</div>Characters</td>
                </tr>
              </table></td>
              </tr>
        </table></td>
      </tr>
		<script language="javascript">
		check_default('<?=$show_val?>','txtbestsellers_','bestseller_remain_title','<?=$show_metaDesc?>','txtmetabestsellers_','bestseller_remain_description');
        </script>
      
      <?php				
	  
	  }
	  
	 
		elseif($keytype=='cat') // show only in case of categories
		{
	?>
      <?php
				if($db->num_rows($res))
				{
					while ($row = $db->fetch_array($res))
					{
					    $count_no++;
						if($count_no %2 == 0)
						$class_val="listingtablestyleB";	
						else
						$class_val="listingtableheader";
						//Check whether any title set for current category. If so show then in the text boxes
						$sql_cats = "SELECT title,meta_description FROM se_category_title 
									WHERE sites_site_id=$ecom_siteid AND product_categories_category_id=".$row['category_id'];
						$ret_cats = $db->query($sql_cats);
						$show_arr = array();
						if ($db->num_rows($ret_cats))
						{
							$row_cats = $db->fetch_array($ret_cats);
							$show_val = stripslashes($row_cats['title']);
							$show_metaDesc	= stripslashes($row_cats['meta_description']);
						}
						else {
							$show_val  ='';
							$show_metaDesc ='';
						}
			?>
      <tr>
        <td valign="middle" align="left" colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
			  <td align="left" class="<?= $class_val?>" width="3%"><?php echo $count_no?>.</td>
              <td align="left" class="<?= $class_val?>"><a href="home.php?request=prod_cat&fpurpose=edit&checkbox[0]=<?php echo $row['category_id']?>" class="edittextlink"><?php echo stripslashes($row['category_name'])?></a>&nbsp;
              <td width="5%" align="left" class="<?= $class_val?>">
			     <?php
		  //echo $row['parent_id'];
	   		//check whether current category has any children
			if ($row['parent_id']!=0)
			{
				$cat_arr = array();
				$show_curnote = '';
				$cat_arr = getCategoryList($row['category_id']);
				if (count($cat_arr))
				{
					foreach($cat_arr as $k=>$v)
					{
						$show_curnote .= $v."<br>";
						$last = $v;
					}
				}
				$temp = $show_curnote ;
				$sr_arr = array('&raquo;','&nbsp;',' ');
				$rp_arr = array('','','');
				$show_curnote = "<strong>Hierarchy of &ldquo;".str_replace($sr_arr,$rp_arr,$last)."&rdquo;</strong><br>".$temp;

			?>	
                  <!--<a href="#" onclick="show_categorytree('<?php echo $row['category_id']?>')" title="View Hierarchy of <?php echo $row['cname']?>"><img src='images/cat_preview.gif' border='0' /></a>-->
                  <a href="#" style="cursor:pointer;" onmouseover="return ddrivetip('<?php echo $show_curnote?>');" onmouseout="return hideddrivetip();"><img src="images/parent_preview.gif" width="20" height="20" border="0" /></span></a>
                  <?php
	 
	   		}
	   ?>			  </td>
            </tr>
            <tr>
              <td align="left" class="<?= $class_val?>">&nbsp;</td>
              <td align="left" class="<?= $class_val?>"><table width="100%" border="0" >
                <tr>
                  <td><b>Title:</b></td>
                  <td><input type="text" name="txtcat_<?php echo $row['category_id']?>" value="<?php echo $show_val?>" size="84" onkeydown="check_length_description('txtcat_<?php echo $row['category_id']?>','txtcat_<?php echo $row['category_id']?>_remain_description')" onKeyPress="check_length_description('txtcat_<?php echo $row['category_id']?>','txtcat_<?php echo $row['category_id']?>_remain_description')" onblur="check_length_description_load('txtcat_<?php echo $row['category_id']?>','txtcat_<?php echo $row['category_id']?>_remain_description')" id="txtcat_<?php echo $row['category_id']?>" style="float:left;"/>
                   <div id="txtcat_<?php echo $row['category_id']?>_remain_description" style=" padding-left:5px;width:2%; float:left;">0</div>Characters</td>
                </tr>
                <tr>
                  <td><b>Meta description:</b></td>
                  <td><textarea name="txtmetacat_<?php echo $row['category_id']?>" cols="63" rows="2" onkeydown="check_length_description('txtmetacat_<?php echo $row['category_id']?>','txtmetacat_<?php echo $row['category_id']?>_remain_description')" onKeyPress="check_length_description('txtmetacat_<?php echo $row['category_id']?>','txtmetacat_<?php echo $row['category_id']?>_remain_description')" onblur="check_length_description_load('txtmetacat_<?php echo $row['category_id']?>','txtmetacat_<?php echo $row['category_id']?>_remain_description')" id="txtmetacat_<?php echo $row['category_id']?>" style="float:left;"><?php echo $show_metaDesc?></textarea>
                   <div id="txtmetacat_<?php echo $row['category_id']?>_remain_description" style=" padding-left:5px;width:2%; float:left;">0</div>Characters</td>
                </tr>
              </table>              
              <td width="5%" align="left" class="<?= $class_val?>">&nbsp;</td>
            </tr>
        </table></td>
      </tr>
		<script language="javascript">
		check_default('<?=$show_val?>','txtcat_<?php echo $row['category_id']?>','txtcat_<?php echo $row['category_id']?>_remain_description','<?=$show_metaDesc?>','txtmetacat_<?php echo $row['category_id']?>','txtmetacat_<?php echo $row['category_id']?>_remain_description');
        </script>
      <?php
				}
	?>
      <?php				
			}
			else
			{
				echo "<tr>
					  <td valign='middle' align='left' class='redtext'>No categories added yet
					  </td>
					  </tr>";
			}
	}
	elseif($keytype=='prod') // show only in case of property
	{
	?>
      <?php
				if($db->num_rows($res))
				{
					while ($row = $db->fetch_array($res))
					{
						//Check whether any title assigned for the current property. If so show then in the text boxes
						$count_no++;
						if($count_no %2 == 0)
						$class_val="listingtablestyleB";
						else
						$class_val="listingtableheader";	
						$sql_prod = "SELECT title,meta_description FROM se_product_title 
									WHERE sites_site_id=$ecom_siteid AND products_product_id=".$row['product_id'];
						$ret_prod = $db->query($sql_prod);
						$show_arr = array();
						if ($db->num_rows($ret_prod))
						{
							$row_pod = $db->fetch_array($ret_prod);
							$show_val = stripslashes($row_pod['title']);
							$show_metaDesc	= stripslashes($row_pod['meta_description']);
						}
						else {
							$show_val  ='';
							$show_metaDesc ='';
						}
			?>
      <tr>
        <td valign="middle" align="left" colspan="3">
		<table width="100%" border="0" cellspacing="0" cellpadding="2">
		 <td align="left" class="<?= $class_val?>" width="2%"><?php echo $count_no?>.</td>
              <td align="left" class="<?= $class_val?>"><a href="home.php?request=products&fpurpose=edit&checkbox[0]=<?php echo $row['product_id']?>" class="edittextlink"><strong><?php echo stripslashes($row['product_name'])?></strong></a></td>
              </tr><tr>
			  <td align="left" class="<?= $class_val?>">&nbsp;</td>
			  <td align="left" class="<?= $class_val?>"><table width="100%" border="0">
                <tr>
                  <td><b>Title:</b></td>
                  <td><input type="text" name="txtprod_<?php echo $row['product_id']?>" value="<?php echo $show_val?>" size="84" onkeydown="check_length_description('txtprod_<?php echo $row['product_id']?>','txtprod_<?php echo $row['product_id']?>_remain_description')" onKeyPress="check_length_description('txtprod_<?php echo $row['product_id']?>','txtprod_<?php echo $row['product_id']?>_remain_description')" onblur="check_length_description_load('txtprod_<?php echo $row['product_id']?>','txtprod_<?php echo $row['product_id']?>_remain_description')" id="txtprod_<?php echo $row['product_id']?>" style="float:left;"/>
                   <div id="txtprod_<?php echo $row['product_id']?>_remain_description" style=" padding-left:5px;width:2%; float:left;">0</div>Characters</td>
                </tr>
                <tr>
                  <td><b>Meta description:</b></td>
                  <td><textarea name="txtmetaprod_<?php echo $row['product_id']?>" cols="63" rows="2" onkeydown="check_length_description('txtmetaprod_<?php echo $row['product_id']?>','txtmetaprod_<?php echo $row['product_id']?>_remain_description')" onKeyPress="check_length_description('txtmetaprod_<?php echo $row['product_id']?>','txtmetaprod_<?php echo $row['product_id']?>_remain_description')" onblur="check_length_description_load('txtmetaprod_<?php echo $row['product_id']?>','txtmetaprod_<?php echo $row['product_id']?>_remain_description')" id="txtmetaprod_<?php echo $row['product_id']?>" style="float:left;"><?php echo $show_metaDesc?></textarea>
                   <div id="txtmetaprod_<?php echo $row['product_id']?>_remain_description" style=" padding-left:5px;width:2%; float:left;">0</div>Characters</td>
                </tr>
                <?php 
             if($row_site['is_apparel_site']==1)
              {				  
              ?>
              <input type="hidden" name="is_apparel_site" value="1">
                <tr>
                  <td><b>Gender:</b></td>
                  <td>			
                  <?php		
                  $gender_arr = array(''=>'Select','Male'=>'Male','Female'=>'Female','Unisex'=>'Unisex');
                          echo generateselectbox("txtgender_".$row['product_id']."",$gender_arr,$row['apparel_gender']); ?>
					  
					  </td>
                </tr>
                <tr>
                  <td><b>Age Group:</b></td>
                  <td>
					  <?php
					  $gender_arr = array(''=>'Select','Adult'=>'Adult','Kids'=>'Kids');
                       echo generateselectbox("txtage_".$row['product_id']."",$gender_arr,$row['apparel_agegroup']);
					  
					  ?>					 
					  </td>
                </tr>                
                <tr>
                  <td><b>Colour:</b></td>
                  <td><input type="text" name="txtcolour_<?php echo $row['product_id']?>" value="<?php echo $row['apparel_color']?>" size="14"/></td>
                </tr>
                <tr>
                  <td><b>Size:</b></td>
                  <td><input type="text" name="txtsize_<?php echo $row['product_id']?>" value="<?php echo $row['apparel_size']?>" size="14"/></td>
                </tr>
                <?php
				}
                ?>
              </table></td>
			  </tr>
        </table></td>
      </tr>
		<script language="javascript">
		check_default('<?=$show_val?>','txtprod_<?php echo $row['product_id']?>','txtprod_<?php echo $row['product_id']?>_remain_description','<?=$show_metaDesc?>','txtmetaprod_<?php echo $row['product_id']?>','txtmetaprod_<?php echo $row['product_id']?>_remain_description');
        </script>
      <?php
				}
	?>
      <?php				
			}
			else
			{
				echo "<tr>
					  <td valign='middle' align='left' class='redtext'>No products added yet
					  </td>
					  </tr>";
			}
	}elseif($keytype=='shop'){
	
	?>
      <?php
				if($db->num_rows($res))
				{
					while ($row = $db->fetch_array($res))
					{
						//Check whether any title assigned for the current shop. If so show then in the text boxes
						$count_no++;
						if($count_no %2 == 0)
						$class_val="listingtablestyleB";
						else
						$class_val="listingtableheader";	
						$sql_shop = "SELECT title,meta_description FROM se_shop_title 
									WHERE sites_site_id=$ecom_siteid AND product_shopbybrand_shopbrand_id=".$row['shopbrand_id'];
						$ret_shop = $db->query($sql_shop);
						$show_arr = array();
						if ($db->num_rows($ret_shop))
						{
							$row_shop = $db->fetch_array($ret_shop);
							$show_val = stripslashes($row_shop['title']);
							$show_metaDesc	= stripslashes($row_shop['meta_description']);
						}
						else {
							$show_val  ='';
							$show_metaDesc ='';
						}
			?>
      <tr>
        <td valign="middle" align="left" colspan="3">
		<table width="100%" border="0" cellspacing="0" cellpadding="2">
		 <tr>
		   <td align="left" class="<?= $class_val?>" width="2%"><?php echo $count_no?>.</td>
		   <td align="left" class="<?= $class_val?>"><a href="home.php?request=shopbybrand&amp;fpurpose=edit&amp;checkbox[0]=<?php echo $row['shopbrand_id']?>" class="edittextlink"><strong><?php echo stripslashes($row['shopbrand_name'])?></strong></a></td>
		   </tr>
		 <td align="left" class="<?= $class_val?>" width="2%">&nbsp;</td>
              <td align="left" class="<?= $class_val?>"><table width="100%" border="0">
                <tr>
                  <td><b>Title:</b></td>
                  <td width="84%"><input type="text" name="txtshop_<?php echo $row['shopbrand_id']?>" value="<?php echo $show_val?>" size="84" onkeydown="check_length_description('txtshop_<?php echo $row['shopbrand_id']?>','txtshop_<?php echo $row['shopbrand_id']?>_remain_description')" onKeyPress="check_length_description('txtshop_<?php echo $row['shopbrand_id']?>','txtshop_<?php echo $row['shopbrand_id']?>_remain_description')" onblur="check_length_description_load('txtshop_<?php echo $row['shopbrand_id']?>','txtshop_<?php echo $row['shopbrand_id']?>_remain_description')" id="txtshop_<?php echo $row['shopbrand_id']?>" style="float:left;"/>
                   <div id="txtshop_<?php echo $row['shopbrand_id']?>_remain_description" style=" padding-left:5px;width:2%; float:left;">0</div>Characters</td>
                </tr>
                <tr>
                  <td><b>Meta description:</b></td>
                  <td><textarea name="txtmetashop_<?php echo $row['shopbrand_id']?>" cols="63" rows="2" onkeydown="check_length_description('txtmetashop_<?php echo $row['shopbrand_id']?>','txtmetashop_<?php echo $row['shopbrand_id']?>_remain_description')" onKeyPress="check_length_description('txtmetashop_<?php echo $row['shopbrand_id']?>','txtmetashop_<?php echo $row['shopbrand_id']?>_remain_description')" onblur="check_length_description_load('txtmetashop_<?php echo $row['shopbrand_id']?>','txtmetashop_<?php echo $row['shopbrand_id']?>_remain_description')" id="txtmetashop_<?php echo $row['shopbrand_id']?>" style="float:left;"><?php echo $show_metaDesc?></textarea>
                   <div id="txtmetashop_<?php echo $row['shopbrand_id']?>_remain_description" style=" padding-left:5px;width:2%; float:left;">0</div>Characters</td>
                </tr>
              </table></td>
              </tr>
        </table></td>
      </tr>
		<script language="javascript">
		check_default('<?=$show_val?>','txtshop_<?php echo $row['shopbrand_id']?>','txtshop_<?php echo $row['shopbrand_id']?>_remain_description','<?=$show_metaDesc?>','txtmetashop_<?php echo $row['shopbrand_id']?>','txtmetashop_<?php echo $row['shopbrand_id']?>_remain_description');
        </script>
      <?php
				}
	?>
      <?php				
			}
			else
			{
				echo "<tr>
					  <td valign='middle' align='left' class='redtext'>No Shops added yet
					  </td>
					  </tr>";
			}
	
	
	}elseif($keytype == 'combo'){
	
	
	?>
      <?php
				if($db->num_rows($res))
				{
					while ($row = $db->fetch_array($res))
					{
						//Check whether any title assigned for the current combo. If so show then in the text boxes
						$count_no++;
						if($count_no %2 == 0)
						$class_val="listingtablestyleB";
						else
						$class_val="listingtableheader";	
						$sql_combo = "SELECT title,meta_description FROM se_combo_title 
									WHERE sites_site_id=$ecom_siteid AND combo_combo_id=".$row['combo_id'];
						$ret_combo = $db->query($sql_combo);
						$show_arr = array();
						if ($db->num_rows($ret_combo))
						{
							$row_combo = $db->fetch_array($ret_combo);
							$show_val = stripslashes($row_combo['title']);
							$show_metaDesc	= stripslashes($row_combo['meta_description']);
						}
						else {
							$show_val  ='';
							$show_metaDesc ='';
						}
			?>
      <tr>
        <td valign="middle" align="left" colspan="3">
		<table width="100%" border="0" cellspacing="0" cellpadding="2">
		 <tr>
		   <td align="left" class="<?= $class_val?>" width="2%"><?php echo $count_no?>.</td>
		   <td align="left" class="<?= $class_val?>"><a href="home.php?request=combo&amp;fpurpose=edit&amp;checkbox[0]=<?php echo $row['combo_id']?>" class="edittextlink"><strong><?php echo stripslashes($row['combo_name'])?></strong></a></td>
		   </tr>
		 <td align="left" class="<?= $class_val?>" width="2%">&nbsp;</td>
              <td align="left" class="<?= $class_val?>"><table width="100%" border="0">
                <tr>
                  <td><b>Title:</b></td>
                  <td><input type="text" name="txtcombo_<?php echo $row['combo_id']?>" value="<?php echo $show_val?>" size="84" onkeydown="check_length_description('txtcombo_<?php echo $row['combo_id']?>','txtcombo_<?php echo $row['combo_id']?>_remain_description')" onKeyPress="check_length_description('txtcombo_<?php echo $row['combo_id']?>','txtcombo_<?php echo $row['combo_id']?>_remain_description')" onblur="check_length_description_load('txtcombo_<?php echo $row['combo_id']?>','txtcombo_<?php echo $row['combo_id']?>_remain_description')" id="txtcombo_<?php echo $row['combo_id']?>" style="float:left;"/>
                   <div id="txtcombo_<?php echo $row['combo_id']?>_remain_description" style=" padding-left:5px;width:2%; float:left;">0</div>Characters</td>
                </tr>
                <tr>
                  <td><b>Meta description:</b></td>
                  <td><textarea name="txtmetacombo_<?php echo $row['combo_id']?>" cols="63" rows="2" onkeydown="check_length_description('txtmetacombo_<?php echo $row['combo_id']?>','txtmetacombo_<?php echo $row['combo_id']?>_remain_description')" onKeyPress="check_length_description('txtmetacombo_<?php echo $row['combo_id']?>','txtmetacombo_<?php echo $row['combo_id']?>_remain_description')" onblur="check_length_description_load('txtmetacombo_<?php echo $row['combo_id']?>','txtmetacombo_<?php echo $row['combo_id']?>_remain_description')" id="txtmetacombo_<?php echo $row['combo_id']?>" style="float:left;"><?php echo $show_metaDesc?></textarea>
                   <div id="txtmetacombo_<?php echo $row['combo_id']?>_remain_description" style=" padding-left:5px;width:2%; float:left;">0</div>Characters</td>
                </tr>
              </table></td>
              </tr>
        </table></td>
      </tr>
		<script language="javascript">
		check_default('<?=$show_val?>','txtcombo_<?php echo $row['combo_id']?>','txtcombo_<?php echo $row['combo_id']?>_remain_description','<?=$show_metaDesc?>','txtmetacombo_<?php echo $row['combo_id']?>','txtmetacombo_<?php echo $row['combo_id']?>_remain_description');
        </script>
      <?php
				}
	?>
      <?php				
			}
			else
			{
				echo "<tr>
					  <td valign='middle' align='left' class='redtext'>No Combo added yet
					  </td>
					  </tr>";
			}
	
	
	
	}
	
	elseif($keytype == 'shelf'){
	
	
	?>
      <?php
				if($db->num_rows($res))
				{
					while ($row = $db->fetch_array($res))
					{
						//Check whether any title assigned for the current shop. If so show then in the text boxes
						$count_no++;
						if($count_no %2 == 0)
						$class_val="listingtablestyleB";
						else
						$class_val="listingtableheader";	
						$sql_shelf = "SELECT title,meta_description FROM se_shelf_title 
									WHERE sites_site_id=$ecom_siteid AND product_shelf_shelf_id=".$row['shelf_id'];
						$ret_shelf = $db->query($sql_shelf);
						$show_arr = array();
						if ($db->num_rows($ret_shelf))
						{
							$row_shelf = $db->fetch_array($ret_shelf);
							$show_val = stripslashes($row_shelf['title']);
							$show_metaDesc	= stripslashes($row_shelf['meta_description']);
						}
						else {
							$show_val  ='';
							$show_metaDesc ='';
						}
			?>
      <tr>
        <td valign="middle" align="left" colspan="3">
		<table width="100%" border="0" cellspacing="0" cellpadding="2">
		 <tr>
		   <td align="left" class="<?= $class_val?>" width="2%"><?php echo $count_no?>.</td>
		   <td align="left" class="<?= $class_val?>"><a href="home.php?request=shelf&amp;fpurpose=edit&amp;checkbox[0]=<?php echo $row['shelf_id']?>" class="edittextlink"><strong><?php echo stripslashes($row['shelf_name'])?></strong></a></td>
		   </tr>
		 <td align="left" class="<?= $class_val?>" width="2%">&nbsp;</td>
              <td align="left" class="<?= $class_val?>"><table width="100%" border="0">
                <tr>
                  <td><b>Title:</b></td>
                  <td><input type="text" name="txtshelf_<?php echo $row['shelf_id']?>" value="<?php echo $show_val?>" size="84" onkeydown="check_length_description('txtshelf_<?php echo $row['shelf_id']?>','txtshelf_<?php echo $row['shelf_id']?>_remain_description')" onKeyPress="check_length_description('txtshelf_<?php echo $row['shelf_id']?>','txtshelf_<?php echo $row['shelf_id']?>_remain_description')" onblur="check_length_description_load('txtshelf_<?php echo $row['shelf_id']?>','txtshelf_<?php echo $row['shelf_id']?>_remain_description')" id="txtshelf_<?php echo $row['shelf_id']?>" style="float:left;"/>
                   <div id="txtshelf_<?php echo $row['shelf_id']?>_remain_description" style=" padding-left:5px;width:2%; float:left;">0</div>Characters</td>
                </tr>
                <tr>
                  <td><b>Meta description:</b></td>
                  <td><textarea name="txtmetashelf_<?php echo $row['shelf_id']?>" cols="63" rows="2" onkeydown="check_length_description('txtmetashelf_<?php echo $row['shelf_id']?>','txtmetashelf_<?php echo $row['shelf_id']?>_remain_description')" onKeyPress="check_length_description('txtmetashelf_<?php echo $row['shelf_id']?>','txtmetashelf_<?php echo $row['shelf_id']?>_remain_description')" onblur="check_length_description_load('txtmetashelf_<?php echo $row['shelf_id']?>','txtmetashelf_<?php echo $row['shelf_id']?>_remain_description')" id="txtmetashelf_<?php echo $row['shelf_id']?>" style="float:left;"><?php echo $show_metaDesc?></textarea>
                   <div id="txtmetashelf_<?php echo $row['shelf_id']?>_remain_description" style=" padding-left:5px;width:2%; float:left;">0</div>Characters</td>
                </tr>
              </table></td>
              </tr>
        </table></td>
      </tr>
		<script language="javascript">
		check_default('<?=$show_val?>','txtshelf_<?php echo $row['shelf_id']?>','txtshelf_<?php echo $row['shelf_id']?>_remain_description','<?=$show_metaDesc?>','txtmetashelf_<?php echo $row['shelf_id']?>','txtmetashelf_<?php echo $row['shelf_id']?>_remain_description');
        </script>
      <?php
				}
	?>
      <?php				
			}
			else
			{
				echo "<tr>
					  <td valign='middle' align='left' class='redtext'>No Shleves added yet
					  </td>
					  </tr>";
			}
	
	
	
	
	}

	elseif($keytype=='stat') // show only in case of Static pages
	{
	?>
      <?php
				if($db->num_rows($res))
				{
					while ($row = $db->fetch_array($res))
					{
						//Check whether any keywords assigned for the current static page. If so show then in the text boxes
						$count_no++;
						if($count_no %2 == 0)
						$class_val="listingtablestyleB";
						else
						$class_val="listingtableheader";
						$sql_stat = "SELECT title,meta_description FROM se_static_title 
									WHERE sites_site_id=$ecom_siteid AND static_pages_page_id=".$row['page_id'];
						$ret_stat = $db->query($sql_stat);
						$show_arr = array();
						if ($db->num_rows($ret_stat))
						{
							$row_stat = $db->fetch_array($ret_stat);
							$show_val = stripslashes($row_stat['title']);
							$show_metaDesc	= stripslashes($row_stat['meta_description']);
						}
						else {
							$show_val  ='';
							$show_metaDesc ='';
						}
			?>
      <tr>
        <td valign="middle" align="left" colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="2">
			 <tr>
			   <td align="left" class="<?= $class_val?>" width="2%"><?php echo $count_no?>.</td>
			   <td align="left" class="<?= $class_val?>"><strong><a href="home.php?request=stat_page&amp;fpurpose=edit&amp;checkbox[0]=<?php echo $row['page_id']?>" class="edittextlink"><?php echo stripslashes($row['title'])?></a></strong></td>
			   <td width="5%" align="left" class="<?= $class_val?>"><?php
			//Check whether this static page is assigned to some static page groups
			$sql_check = "SELECT b.group_name,a.static_pagegroup_group_id,a.static_pages_order FROM static_pagegroup_static_page_map a ,static_pagegroup b WHERE 
			a.static_pagegroup_group_id=b.group_id AND a.static_pages_page_id = ".$row['page_id']." ORDER BY b.group_name";
			$ret_check = $db->query($sql_check);
			if ($db->num_rows($ret_check))
			{
				$sr_arr = array('&raquo;','&nbsp;',' ');
				$rp_arr = array('','','');
				$temp = '';
				while($row_check = $db->fetch_array($ret_check))
				{
					$temp .= "<br>&nbsp;&#8226;&nbsp;".stripslashes($row_check['group_name']).' &raquo; '.stripslashes($row_check['position']);
				}
				$show_curnote = "<strong>&ldquo;".str_replace($sr_arr,$rp_arr,stripslashes($row['title']))."&rdquo; is assigned to Page Groups</strong>".$temp;
				
	   ?>
                   <!--<a href="#" onclick="show_categorytree('<?php echo $row['category_id']?>')" title="View Hierarchy of <?php echo $row['cname']?>"><img src='images/cat_preview.gif' border='0' /></a>-->
                   <a href="#" style="cursor:pointer;" onmouseover="return overlib('<?php echo $show_curnote?>',300,VAUTO);" onmouseout="return nd();"><img src="images/parent_preview.gif" width="20" height="20" border="0" /></span></a>
                   <?php
	   		}
	   ?>               </td>
			 </tr>
			 <td align="left" class="<?= $class_val?>" width="2%">&nbsp;</td>
              <td align="left" class="<?= $class_val?>"><table width="100%" border="0">
                <tr>
                  <td><b>Title:</b></td>
                  <td><input name="txtstat_<?php echo $row['page_id']?>" type="text" id="txtstat_<?php echo $row['page_id']?>_<?php echo $i;?>" value="<?php echo $show_val?>" size="84" onkeydown="check_length_description('txtstat_<?php echo $row['page_id']?>_<?php echo $i;?>','txtstat_<?php echo $row['page_id']?>_<?php echo $i;?>_remain_description')" onKeyPress="check_length_description('txtstat_<?php echo $row['page_id']?>_<?php echo $i;?>','txtstat_<?php echo $row['page_id']?>_<?php echo $i;?>_remain_description')" onblur="check_length_description_load('txtstat_<?php echo $row['page_id']?>_<?php echo $i;?>','txtstat_<?php echo $row['page_id']?>_<?php echo $i;?>_remain_description')" style="float:left;"/>
                   <div id="txtstat_<?php echo $row['page_id']?>_<?php echo $i;?>_remain_description" style=" padding-left:5px;width:2%; float:left;">0</div>Characters</td>
                </tr>
                <tr>
                  <td><b>Meta description:</b></td>
                  <td><textarea name="txtmetastat_<?php echo $row['page_id']?>" cols="63" rows="2" onkeydown="check_length_description('txtmetastat_<?php echo $row['page_id']?>_<?php echo $i;?>','txtmetastat_<?php echo $row['page_id']?>_<?php echo $i;?>_remain_description')" onKeyPress="check_length_description('txtmetastat_<?php echo $row['page_id']?>_<?php echo $i;?>','txtmetastat_<?php echo $row['page_id']?>_<?php echo $i;?>_remain_description')" onblur="check_length_description_load('txtmetastat_<?php echo $row['page_id']?>_<?php echo $i;?>','txtmetastat_<?php echo $row['page_id']?>_<?php echo $i;?>_remain_description')" id="txtmetastat_<?php echo $row['page_id']?>_<?php echo $i;?>" style="float:left;"><?php echo $show_metaDesc?></textarea>
                   <div id="txtmetastat_<?php echo $row['page_id']?>_<?php echo $i;?>_remain_description" style=" padding-left:5px;width:2%; float:left;">0</div>Characters</td>
                </tr>
              </table></td>
              <td width="5%" align="left" class="<?= $class_val?>">&nbsp;</td>
			</tr>
        </table></td>
      </tr>
		<script language="javascript">
		check_default('<?=$show_val?>','txtstat_<?php echo $row['page_id']?>_<?php echo $i;?>','txtstat_<?php echo $row['page_id']?>_<?php echo $i;?>_remain_description','<?=$show_metaDesc?>','txtmetastat_<?php echo $row['page_id']?>_<?php echo $i;?>','txtmetastat_<?php echo $row['page_id']?>_<?php echo $i;?>_remain_description');
        </script>
      <?php
				}
	?>
      <?php				
			}
			else
			{
				echo "<tr>
					  <td valign='middle' align='left' class='redtext'>No Static Pages added yet
					  </td>
					  </tr>";
			}
	}elseif($keytype == 'saved'){
	
	
	?>
      <?php
				if($db->num_rows($res))
				{
					while ($row = $db->fetch_array($res))
					{
						//Check whether any keywords assigned for the current static page. If so show then in the text boxes
						$count_no++;
						if($count_no %2 == 0)
						$class_val="listingtablestyleB";
						else
						$class_val="listingtableheader";
						$sql_saved = "SELECT title,meta_description FROM se_search_title 
									WHERE sites_site_id=$ecom_siteid AND saved_search_search_id=".$row['search_id'];
						$ret_saved = $db->query($sql_saved);
						$show_arr = array();
						if ($db->num_rows($ret_saved))
						{
							$row_saved = $db->fetch_array($ret_saved);
							$show_val = stripslashes($row_saved['title']);
							$show_metaDesc	= stripslashes($row_saved['meta_description']);
						}
						else {
							$show_val  ='';
							$show_metaDesc ='';
						}
			?>
      <tr>
        <td valign="middle" align="left" colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="2">
			 <tr>
			   <td align="left" class="<?= $class_val?>" width="2%"><?php echo $count_no?>.</td>
			   <td align="left" class="<?= $class_val?>"><strong><a href="home.php?request=seo_keyword&amp;fpurpose=saved_keyword" class="edittextlink"><?php echo stripslashes($row['search_keyword'])?></a></strong></td>
			   </tr>
			 <td align="left" class="<?= $class_val?>" width="2%">&nbsp;</td>
              <td align="left" class="<?= $class_val?>"><table width="100%" border="0">
                <tr>
                  <td><b>Title:</b></td>
                  <td><input name="txtsaved_<?php echo $row['search_id']?>" type="text" id="txtsaved_<?php echo $row['search_id']?>_<?php echo $i;?>" value="<?php echo $show_val?>" size="84" onkeydown="check_length_description('txtsaved_<?php echo $row['search_id']?>_<?php echo $i;?>','txtsaved_<?php echo $row['search_id']?>_<?php echo $i;?>_remain_description')" onKeyPress="check_length_description('txtsaved_<?php echo $row['search_id']?>_<?php echo $i;?>','txtsaved_<?php echo $row['search_id']?>_<?php echo $i;?>_remain_description')" onblur="check_length_description_load('txtsaved_<?php echo $row['search_id']?>_<?php echo $i;?>','txtsaved_<?php echo $row['search_id']?>_<?php echo $i;?>_remain_description')" style="float:left;"/>
                   <div id="txtsaved_<?php echo $row['search_id']?>_<?php echo $i;?>_remain_description" style=" padding-left:5px;width:2%; float:left;">0</div>Characters</td>
                </tr>
                <tr>
                  <td><b>Meta description:</b></td>
                  <td><textarea name="txtmetasaved_<?php echo $row['search_id']?>" cols="63" rows="2" onkeydown="check_length_description('txtmetasaved_<?php echo $row['search_id']?>_<?php echo $i;?>','txtmetasaved_<?php echo $row['search_id']?>_<?php echo $i;?>_remain_description')" onKeyPress="check_length_description('txtmetasaved_<?php echo $row['search_id']?>_<?php echo $i;?>','txtmetasaved_<?php echo $row['search_id']?>_<?php echo $i;?>_remain_description')" onblur="check_length_description_load('txtmetasaved_<?php echo $row['search_id']?>_<?php echo $i;?>','txtmetasaved_<?php echo $row['search_id']?>_<?php echo $i;?>_remain_description')" id="txtmetasaved_<?php echo $row['search_id']?>_<?php echo $i;?>" style="float:left;"><?php echo $show_metaDesc?></textarea>
                   <div id="txtmetasaved_<?php echo $row['search_id']?>_<?php echo $i;?>_remain_description" style=" padding-left:5px;width:2%; float:left;">0</div>Characters</td>
                </tr>
              </table></td>
              </tr>
        </table></td>
      </tr>
		<script language="javascript">
		check_default('<?=$show_val?>','txtsaved_<?php echo $row['search_id']?>_<?php echo $i;?>','txtsaved_<?php echo $row['search_id']?>_<?php echo $i;?>_remain_description','<?=$show_metaDesc?>','txtmetasaved_<?php echo $row['search_id']?>_<?php echo $i;?>','txtmetasaved_<?php echo $row['search_id']?>_<?php echo $i;?>_remain_description');
        </script>
      <?php
				}
	?>
      <?php				
			}
			else
			{
				echo "<tr>
					  <td valign='middle' align='left' class='redtext'>No Static Pages added yet
					  </td>
					  </tr>";
			}
	
	}
	
	?>
      <tr>
        <td valign="middle"  class="listeditd" colspan="3">&nbsp;</td>
      </tr>
	 
      <tr>
	 <td valign="middle" align="right" colspan="3"><input name="TitleReturn" type="submit" class="red" value="Save &amp; Return"  id="TitleReturn" onclick="show_processing();"/>&nbsp;&nbsp;<input name="TitleSubmit" type="submit" class="red" value="Save"  id="TitleSubmit" onclick="show_processing();"/></td>
      </tr>
	  
	   <?php 
	   if($numcount>0)
	{
	   if($keytype != 'home' && $keytype != 'bestsellers' && $keytype != 'help' && $keytype != 'faq' && $keytype != 'registration' && $keytype != 'sitemap' && $keytype != 'forgotpassword' && $keytype != 'sitereviews' && $keytype != 'savedsearchmain' ) { ?>
	  <tr>

	   <td  align="center" valign="top" class="listeditd" colspan="3"><?php  paging_footer($query_string,$numcount,$pg,$pages,$showtype,0);?></td>
	  </tr>
	  <? }
	  }?>
      <!--<tr>
        <td valign="middle" align="center" class="redtext">Please save changes in current page (if any) before moving to other pages </td>
      </tr>-->
      <tr>
        <td valign="middle" align="center">&nbsp;</td>
      </tr>
      <?php
       
  $query_string .= "&request=sitetitles&cbo_keytype=".$keytype;
  
?>
    </table></td>
  </tr>
   </table>
  </div>
  </td>
  </tr>
</table>

</form>
<?php
$db->db_close();

	function  getCategoryList($catid)
	{
		global $ecom_siteid,$db;
		$cat_arr	= array();
		do
		{
				$sql 					= "SELECT category_id,category_name,parent_id	FROM product_categories WHERE category_id=$catid AND sites_site_id = $ecom_siteid";
				$result_cat 			=  $db->query($sql);		
				if ($db->num_rows($result_cat))
				{
					$row_cat			= $db->fetch_array($result_cat);
					$parent 			= $row_cat['parent_id'];
					$cat_name 			= stripslashes($row_cat['category_name']);
					$catid				= $row_cat['category_id'];
					$cat_arr[$catid] 	= $cat_name; 
					$catid				= $parent;
				}
				else
					$parent =0;
				
		}while($parent!=0);
		if(count($cat_arr))
		{
			$cat_arr = array_reverse($cat_arr,true);
			$i=0;
			foreach ($cat_arr as $k=>$v)
			{
				$byte = '';
				for($j=0;$j<$i;$j++)
					$byte .= '&nbsp;';
				$i++;
				//$byte .= "<img src='images/directory_sm.gif' border='0'> "; 
				$byte .= '<strong>&raquo; </strong>';
				$ret_arr[$k] = $byte.$v;
			}
			return $ret_arr;
		}
		
	}
	
	?>