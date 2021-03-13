<?php
	/*#################################################################
	# Script Name 	: list_products.php
	# Description 		: Page for listing Products
	# Coded by 		: Sny
	# Created on		: 26-June-2007
	# Modified by		: Sny
	# Modified On		: 28-Oct-2008
	#################################################################*/

//Define constants for this page
$table_name='products';
$page_type='Products';
$help_msg = get_help_messages('LIST_PRODUCTS1');
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frm_products,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frm_products,\'checkbox[]\')"/>','Slno.','Product','Product Id','Category','Retail','Cost','Bulk Disc','Disc','WebStock(All)','Data Feed(Excluded)','Hide');

$header_positions=array('center','left','left','left','left','left','left','center','center','center','center','center');
if($_REQUEST['categoryid'])
{
	$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frm_products,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frm_products,\'checkbox[]\')"/>','Slno.','Product','Product ID','Category','Retail','Cost','Order','Bulk Disc','Disc','Web Stk(All)','Data Feed(Excluded)','Hide');
	$header_positions=array('center','left','left','left','left','left','left','center','center','center','center','center','center');
}
$colspan = count($table_headers);

//#Search terms
$search_fields = array('productname','manufactureid','categoryid','vendorid','bulkdiscount','rprice_from','rprice_to','cprice_from','cprice_to','discount','discountas','stockatleast','stock_from','stock_to','preorder','prodhidden','in_mobile_api_sites','sort_by','sort_order','cbo_bulkdisc','prodnotification','alloworderstock');

$query_string = "request=products";
$option_search_style_display = 'style="display:"';
if($_REQUEST['discountas']=='')
{
	$_REQUEST['discountas']=-1;
}
// to hold the options TR if searched using the options feild
if(($_REQUEST['rprice_from']!='') || ($_REQUEST['rprice_to']!='') ||  ($_REQUEST['cprice_from']!='')|| ($_REQUEST['cprice_to']!='') || ($_REQUEST['discount']!='') || ($_REQUEST['discountas']>-1) || ($_REQUEST['stock']!='')  || ($_REQUEST['stockatleast'])  || ($_REQUEST['stock_from']!='')  || ($_REQUEST['stock_to']!='')  || ($_REQUEST['discounten'])  || ($_REQUEST['preorder']) || ($_REQUEST['prodhidden']) || ($_REQUEST['in_mobile_api_sites']) || ($_REQUEST['prodnotification']) || ($_REQUEST['alloworderstock'])){
	$option_search_style_display = 'style="display:"';
}
else{
	$option_search_style_display = 'style="display:none"';
}
foreach($search_fields as $v) {

	$query_string .= "&$v=".$_REQUEST[$v]."";//#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'product_name':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('product_name' => 'Name','manufacture_id'=>'Product Id','product_costprice'=>'Cost Price','product_webprice'=>'Web Price','product_bulkdiscount_allowed'=>'Bulk Discount','product_id'=>'Added Date','product_exclude_from_feed'=>'Exclude from Feed');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
if ($_REQUEST['cbo_bulkdisc'])
	$where_conditions .= " AND product_bulkdiscount_allowed ='".$_REQUEST['cbo_bulkdisc']."' ";

// Product Name Condition
if($_REQUEST['productname'])
{
	$where_conditions .= " AND ( product_name LIKE '%".add_slash($_REQUEST['productname'])."%') ";
}
// Manufacturer id Condition
if($_REQUEST['manufactureid']) {
	$where_conditions .= " AND ( manufacture_id LIKE '%".add_slash($_REQUEST['manufactureid'])."%') ";
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
		$where_conditions .= " AND ( product_id IN ($include_prod_str)) ";
	}
}
// ==================================================================================================
// ==================================================================================================

// Webprice Condition
if(is_numeric(trim($_REQUEST['rprice_from'])))
$rprice_f = trim($_REQUEST['rprice_from']);
if(is_numeric(trim($_REQUEST['rprice_to'])))
$rprice_t = trim($_REQUEST['rprice_to']);
if($rprice_f && $rprice_t) {
	$where_conditions .= " AND ( product_webprice BETWEEN $rprice_f AND $rprice_t ) ";
}
elseif($rprice_f && !$rprice_t)
{
	$where_conditions .= " AND ( product_webprice >= $rprice_f) ";
}
elseif(!$rprice_f && $rprice_t)
{
if($rprice_t==-1)
$rprice_t=0;
	$where_conditions .= " AND ( product_webprice <= $rprice_t) ";
}

// Costprice Condition
if(is_numeric(trim($_REQUEST['cprice_from'])))
$cprice_f = trim($_REQUEST['cprice_from']);
if(is_numeric(trim($_REQUEST['cprice_to'])))
$cprice_t = trim($_REQUEST['cprice_to']);
if($cprice_f && $cprice_t) {
	$where_conditions .= " AND ( product_costprice BETWEEN $cprice_f AND $cprice_t ) ";
}
elseif($cprice_f && !$cprice_t)
{
	$where_conditions .= " AND ( product_costprice >= $cprice_f) ";
}
elseif(!$cprice_f && $cprice_t)
{
	$where_conditions .= " AND ( product_costprice <= $cprice_t) ";
}
// Stock Condition
if(is_numeric(trim($_REQUEST['stock_from'])))
$cstock_f = trim($_REQUEST['stock_from']);
if(is_numeric(trim($_REQUEST['stock_to'])))
$cstock_t = trim($_REQUEST['stock_to']);
/*if($cstock_f && $cstock_t) {
	$where_conditions .= " AND ( product_webstock BETWEEN $cstock_f AND $cstock_t ) ";
}
elseif($cstock_f && !$cstock_t)
{
	$where_conditions .= " AND ( product_webstock >= $cstock_f) ";
}
elseif(!$cstock_f && $cstock_t)
{
if($cstock_t==-1)
$cstock_t=0;
	$where_conditions .= " AND ( product_webstock <= $cstock_t) ";
}*/

if($cstock_f!='' && $cstock_t!='') {
	$where_conditions .= " AND ( product_actualstock BETWEEN $cstock_f AND $cstock_t ) ";
}
elseif($cstock_f!='' && $cstock_t=='')
{
	$where_conditions .= " AND ( product_actualstock >= $cstock_f) ";
}
elseif($cstock_f=='' && $cstock_t!='')
{
if($cstock_t==-1)
$cstock_t=0;
	$where_conditions .= " AND ( product_actualstock <= $cstock_t) ";
}



// Discount Condition
$disc		= trim($_REQUEST['discount']);
$disc_as	= trim($_REQUEST['discountas']);
$discount   = trim($_REQUEST['discount']);
if (is_numeric($disc))
{
	$where_conditions .= "  AND product_discount>=$discount ";
	if($disc_as>=0)
	{	
		$where_conditions .= "AND product_discount_enteredasval=$disc_as";
	}
}

// Bulk Discount Condition
$bulk		= trim($_REQUEST['discounten']);
if ($bulk)
{
	$where_conditions .= " AND ( product_bulkdiscount_allowed='".$bulk."') ";
} 

// Stock Condition
$stock		= trim($_REQUEST['stockatleast']);

if(is_numeric($stock))
{

$where_conditions .= " AND ( product_actualstock >=$stock) ";
}

// Preorder Condition 
$pre 		= trim($_REQUEST['preorder']);
if ($pre)
{
	$where_conditions .= " AND ( product_preorder_allowed='".$pre."') ";
}

// Hidden
$hide 		= trim($_REQUEST['prodhidden']);
if($hide)
{
	$where_conditions .= " AND ( product_hide ='".$hide."') ";
}


$in_mobile_api_sites 		= trim($_REQUEST['in_mobile_api_sites']);
if($in_mobile_api_sites == 1)
{
	$where_conditions .= " AND ( in_mobile_api_sites ='".$in_mobile_api_sites."') ";
}


$prodnotification = trim($_REQUEST['prodnotification']);
if($prodnotification)
{
	$where_conditions .= " AND ( product_stock_notification_required ='".$prodnotification."') ";
}
$alloworderstock  = trim($_REQUEST['alloworderstock']);
if($alloworderstock)
{
	$where_conditions .= " AND (product_alloworder_notinstock ='".$alloworderstock."') ";
}
//#Select condition for getting total count
$sql_count = "SELECT count(*) as cnt FROM $table_name  $where_conditions";
$res_count = $db->query($sql_count);
list($numcount) = $db->fetch_array($res_count);#Getting total count of records
/////////////////////////////////For paging///////////////////////////////////////////
$records_per_page = (is_numeric($_REQUEST['records_per_page']) and $_REQUEST['records_per_page'])?$_REQUEST['records_per_page']:10;#Total records shown in a page
$pg = ($_REQUEST['search_click'])?1:$_REQUEST['pg'];

if (!($pg > 0) || $pg == 0) { $pg = 1; }
$pages = ceil($numcount / $records_per_page);#Getting the total pages
if($pg > $pages) {
	$pg = $pages;
}
if ($pg>=1)
{
	$start = ($pg - 1) * $records_per_page;//#Starting record.
	$count_no = ($pg == 1)?0:$records_per_page*($pg-1);
}	
else
{
	$start = $count_no = 0;	
}

/////////////////////////////////////////////////////////////////////////////////////

$sql_qry = "SELECT * FROM products $where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
$ret_qry = $db->query($sql_qry);
?>
<script type="text/javascript">
function ajax_return_contents() 
{
	var ret_val='';
	if(req.readyState==4)
	{
		if(req.status==200)
		{
			ret_val = req.responseText;
			document.getElementById('maincontent').innerHTML=ret_val;
			hide_processing();
		}
		else
		{
			 show_request_alert(req.status);
			//alert("Problem in requesting XML :"+req.statusText);
		}
	}
}
/*function call_ajax_delete(cname,sortby,sortorder,recs,start,pg,cat_id) */
function call_ajax_delete(cname,pname,manid,catid,vendorid,rprice_from,rprice_to,cpricefrom,cpriceto,discount,discountas,bulkdiscount,stockatleast,stock_from,stock_to,preorder,prod_notification,prod_alloworderstock,prodhidden,in_mobile_api_sites,sortby,sortorder,recs,start,pg,cat_id)
{
	var atleastone 			= 0;
	var del_ids 			= '';
/*	var qrystr				= 'catgroupname='+cname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&categoryid='+cat_id; */
    var qrystr				= 'catgroupname='+cname+'&productname='+pname+'&manufactureid='+manid+'&categoryid='+catid+'&vendorid='+vendorid+'&rprice_from='+rprice_from+'&rprice_to='+rprice_to+'&cprice_from='+cpricefrom+'&cprice_to='+cpriceto+'&discount='+discount+'&discountas='+discountas+'&bulkdiscount='+bulkdiscount+'&stockatleast='+stockatleast+'&preorder='+preorder+'&prodhidden='+prodhidden+'&in_mobile_api_sites='+in_mobile_api_sites+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&stock_from='+stock_from+'&stock_to='+stock_to+'&prodnotification='+prod_notification+'&alloworderstock='+prod_alloworderstock+'&categoryid='+cat_id;


    /* check whether any checkbox is ticked */
   
	for(i=0;i<document.frm_products.elements.length;i++)
	{
		if (document.frm_products.elements[i].type =='checkbox' && document.frm_products.elements[i].name=='checkbox[]')
		{

			if (document.frm_products.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frm_products.elements[i].value;
				}	
		}
	}
	
	if (atleastone==0)
	{
		alert('Please select the product category groups to delete');
	}
	else
	{
		if(confirm('Are you sure you want to delete selected Products ?'))
		{
			show_processing();
			Handlewith_Ajax('services/products.php','fpurpose=delete&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}




function go_replicate(cname,pname,manid,catid,vendorid,rprice_from,rprice_to,cpricefrom,cpriceto,discount,discountas,bulkdiscount,stockatleast,stock_from,stock_to,preorder,prod_notification,prod_alloworderstock,prodhidden,in_mobile_api_sites,sortby,sortorder,recs,start,pg,cat_id)
{
	var atleastone 			= 0;
	var del_ids 			= '';
	var replicate_product_id    = '';
	//var qrystr				= 'catgroupname='+cname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;
	var qrystr				= 'catgroupname='+cname+'&productname='+pname+'&manufactureid='+manid+'&categoryid='+catid+'&vendorid='+vendorid+'&rprice_from='+rprice_from+'&rprice_to='+rprice_to+'&cprice_from='+cpricefrom+'&cprice_to='+cpriceto+'&discount='+discount+'&discountas='+discountas+'&bulkdiscount='+bulkdiscount+'&stockatleast='+stockatleast+'&preorder='+preorder+'&prodhidden='+prodhidden+'&in_mobile_api_sites='+in_mobile_api_sites+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&stock_from='+stock_from+'&stock_to='+stock_to+'&prodnotification='+prod_notification+'&alloworderstock='+prod_alloworderstock+'&categoryid='+cat_id;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frm_products.elements.length;i++)
	{
		if (document.frm_products.elements[i].type =='checkbox' && document.frm_products.elements[i].name=='checkbox[]')
		{

			if (document.frm_products.elements[i].checked==true)
			{
				atleastone += 1;
				if(replicate_product_id  == '')
				{
				replicate_product_id = document.frm_products.elements[i].value;
				}
				
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select a product to copy');
	}
	else
	{
		if(atleastone==1)
		{
			if(confirm('Are you sure you want to copy the selected Product as a new product?'))
			{
				show_processing();
				Handlewith_Ajax('services/products.php','fpurpose=replicate&replicate_product_id='+replicate_product_id+'&'+qrystr);
				
			/*	show_processing();
				document.frm_products.fpurpose.value ='replicate';
				document.frm_products.replicate_product_id.value =replicate_product_id;
				document.frm_products.submit();
				return;   */

			}
		}	
		else
		{
			alert('Please select only one Product for copying.');
		}
	}	
}



function call_ajax_saveorder(cname,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var curid				= 0;
	var cat_ids 			= '';
	var cat_orders			= '';
	var qrystr				= 'catgroupname='+cname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;
	if(confirm('Save Order for all Products in the list?'))
	{
			for(i=0;i<document.frm_products.elements.length;i++)
			{
				if (document.frm_products.elements[i].type =='checkbox' && document.frm_products.elements[i].name=='checkbox[]')
				{
						if (cat_ids!='')
							cat_ids += '~';
						 curid = document.frm_products.elements[i].value;	
						 cat_ids += curid;
						if (cat_orders!='')
							cat_orders += '~';
						 cat_orders += eval('document.frm_products.'+'catgroup_order_'+curid+'.value');
				}
			}
			show_processing();
			Handlewith_Ajax('services/product_category_groups.php','fpurpose=save_order&'+qrystr+'&catids='+cat_ids+'&catorders='+cat_orders);
	}	
}
function call_ajax_changestatus(pname,manid,catid,vendorid,rprice_from,rprice_to,cpricefrom,cpriceto,discount,discountas,bulkdiscount,stockatleast,stock_from,stock_to,preorder,prod_notification,prod_alloworderstock,prodhidden,in_mobile_api_sites,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var prod_ids			= 0;
	var cat_ids 			= '';
	var cat_orders			= '';
	var ch_status			= document.frm_products.cbo_changehide.value;
	var qrystr				= 'productname='+pname+'&manufactureid='+manid+'&categoryid='+catid+'&vendorid='+vendorid+'&rprice_from='+rprice_from+'&rprice_to='+rprice_to+'&cprice_from='+cpricefrom+'&cprice_to='+cpriceto+'&discount='+discount+'&discountas='+discountas+'&bulkdiscount='+bulkdiscount+'&stockatleast='+stockatleast+'&preorder='+preorder+'&prodhidden='+prodhidden+'&in_mobile_api_sites='+in_mobile_api_sites+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&ch_status='+ch_status+'&start='+start+'&pg='+pg+'&stock_from='+stock_from+'&stock_to='+stock_to+'&prodnotification='+prod_notification+'&alloworderstock='+prod_alloworderstock;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frm_products.elements.length;i++)
	{
		if (document.frm_products.elements[i].type =='checkbox' && document.frm_products.elements[i].name=='checkbox[]')
		{

			if (document.frm_products.elements[i].checked==true)
			{
				atleastone = 1;
				if (prod_ids!='')
					prod_ids += '~';
				 prod_ids += document.frm_products.elements[i].value;
			}	
		}
	}
	if (atleastone==0) 
	{
		alert('Please select the products to change the hide status');
	}
	else
	{
		if(confirm('Change Hide Status of Seleted Product(s)?'))
		{
				show_processing();
				Handlewith_Ajax('services/products.php','fpurpose=change_hide&'+qrystr+'&prodids='+prod_ids);
		}	
	}	
}
function call_ajax_changedatafeedstatus(pname,manid,catid,vendorid,rprice_from,rprice_to,cpricefrom,cpriceto,discount,discountas,bulkdiscount,stockatleast,stock_from,stock_to,preorder,prod_notification,prod_alloworderstock,prodhidden,in_mobile_api_sites,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var prod_ids			= 0;
	var cat_ids 			= '';
	var cat_orders			= '';
	var ch_status			= document.frm_products.cbo_exclude_feed.value;
	var qrystr				= 'productname='+pname+'&manufactureid='+manid+'&categoryid='+catid+'&vendorid='+vendorid+'&rprice_from='+rprice_from+'&rprice_to='+rprice_to+'&cprice_from='+cpricefrom+'&cprice_to='+cpriceto+'&discount='+discount+'&discountas='+discountas+'&bulkdiscount='+bulkdiscount+'&stockatleast='+stockatleast+'&preorder='+preorder+'&prodhidden='+prodhidden+'&in_mobile_api_sites='+in_mobile_api_sites+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&ch_status='+ch_status+'&start='+start+'&pg='+pg+'&stock_from='+stock_from+'&stock_to='+stock_to+'&prodnotification='+prod_notification+'&alloworderstock='+prod_alloworderstock;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frm_products.elements.length;i++)
	{
		if (document.frm_products.elements[i].type =='checkbox' && document.frm_products.elements[i].name=='checkbox[]')
		{

			if (document.frm_products.elements[i].checked==true)
			{
				atleastone = 1;
				if (prod_ids!='')
					prod_ids += '~';
				 prod_ids += document.frm_products.elements[i].value;
			}	
		}
	}
	if (atleastone==0) 
	{
		alert('Please select the products to change the Data Feed(Excluded) status');
	}
	else
	{
		if(confirm('Are you sure you want to change the Data Feed (Excluded) status of Seleted Product(s)?'))
		{
				show_processing();
				Handlewith_Ajax('services/products.php','fpurpose=change_feedhide&'+qrystr+'&prodids='+prod_ids);
		}	
	}	
}
function call_ajax_changeorder(pname,manid,catid,vendorid,rprice_from,rprice_to,cpricefrom,cpriceto,discount,discountas,bulkdiscount,stockatleast,stock_from,stock_to,preorder,prod_notification,prod_alloworderstock,prodhidden,in_mobile_api_sites,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var prod_ids			= 0;
	var cat_orders			= '';
	var qrystr				= 'productname='+pname+'&manufactureid='+manid+'&categoryid='+catid+'&vendorid='+vendorid+'&rprice_from='+rprice_from+'&rprice_to='+rprice_to+'&cprice_from='+cpricefrom+'&cprice_to='+cpriceto+'&discount='+discount+'&discountas='+discountas+'&bulkdiscount='+bulkdiscount+'&stockatleast='+stockatleast+'&preorder='+preorder+'&prodhidden='+prodhidden+'&in_mobile_api_sites='+in_mobile_api_sites+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&stock_from='+stock_from+'&stock_to='+stock_to+'&prodnotification='+prod_notification+'&alloworderstock='+prod_alloworderstock;
	var IdArr=new Array();
	var OrderArr=new Array();
	var index;
	var val;
	var j=0;
	for(i=0;i<document.frm_products.elements.length;i++)
	{
		if (document.frm_products.elements[i].type =='text' && document.frm_products.elements[i].name!='search_name' && document.frm_products.elements[i].name!='records_per_page')
		{
			index=document.frm_products.elements[i].name;
			val=document.frm_products.elements[i].value;
			IdArr[j]=index;
			OrderArr[j]=val;
			j=j+1;
		}
	}
	
	var Idstr=IdArr.join('~');
	var OrderStr=OrderArr.join('~');
	
	if(confirm('Save Sort Order Of Products?'))
		{
				show_processing();
				Handlewith_Ajax('services/products.php','fpurpose=change_order&'+qrystr+'&Idstr='+Idstr+'&OrderStr='+OrderStr);
		}
}
function go_edit(cname,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var del_ids 			= '';
	var qrystr				= 'catgroupname='+cname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frm_products.elements.length;i++)
	{
		if (document.frm_products.elements[i].type =='checkbox' && document.frm_products.elements[i].name=='checkbox[]')
		{

			if (document.frm_products.elements[i].checked==true)
			{
				atleastone += 1;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select the product  to edit');
	}
	else
	{
		if(atleastone==1)
		{
			show_processing();
			document.frm_products.fpurpose.value='edit';
			document.frm_products.submit();
		}	
		else
		{
			alert('Please select only one Product to delete.');
		}
	}	
}






function normal_assign_ImageAssign(productname,manufactureid,vendorid,bulkdisc,sortby,sortorder,recs,start,pg,cat_id)
{
	var atleastone 			= 0;
	var img_ids 			= '';
	var qrystr				= 'cbo_bulkdisc='+bulkdisc+'&vendorid='+vendorid+'&manufactureid='+manufactureid+'&productname='+productname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page_img='+recs+'&start='+start+'&pg='+pg+'&categoryid='+cat_id;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frm_products.elements.length;i++)
	{
		if (document.frm_products.elements[i].type =='checkbox' && document.frm_products.elements[i].name=='checkbox[]')
		{

			if (document.frm_products.elements[i].checked==true)
			{
				atleastone += 1;
				 img_ids += document.frm_products.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select the product to assign images');
	}
	else
	{
		if(atleastone==1)
		{
			show_processing();
			window.location 			= 'home.php?request=img_gal&src_page=listprod&src_id='+img_ids+'&'+qrystr;			}	
		else
		{
			alert('Please select only one Product To Assign Images.');
		}
	}	
		
}
function call_ajax_assignto_cat(mod,pname,sortby,sortorder,recs,start,pg)
	{
		var atleastone 			= 0;
		var curid				= 0;
		var prod_ids 			= '';
		
		if (mod=='category_assign')
		{
			var ch_category		= document.frm_products.cat_category_id.value;
			}
		else{
			var ch_category		= document.frm_products.un_cat_category_id.value;	
			}
			
		var qrystr				= 'productname='+pname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&ch_category='+ch_category+'&start='+start+'&pg='+pg;
		/* check whether any checkbox is ticked */
		for(i=0;i<document.frm_products.elements.length;i++)
		{
			if (document.frm_products.elements[i].type =='checkbox' && document.frm_products.elements[i].name=='checkbox[]')
			{
	
				if (document.frm_products.elements[i].checked==true)
				{
					atleastone = 1;
					if (prod_ids!='')
						prod_ids += '~';
					 prod_ids += document.frm_products.elements[i].value;
				}	
			}
		}
		if (atleastone==0) 
		{
			alert('Please select the products');
		}
		else
		{
			if(mod=='category_assign')
				var msg = 'Assign the selected product(s)  to the selected product category?';
			else
				var msg = 'Unassign the selected product(s)  from the selected product category ?';
			if(confirm(msg))
			{
					show_processing();
					Handlewith_Ajax('services/products.php','fpurpose='+mod+'&'+qrystr+'&prodids='+prod_ids);
			}	
		}	
	}
function handle_showmorediv()
{
	if(document.getElementById('listmore_tr').style.display=='')
	{
		document.getElementById('listmore_tr').style.display = 'none';
		document.getElementById('show_morediv').innerHTML = 'Filters<img src="images/right_arr.gif" />';
	}	
	else
	{
		document.getElementById('listmore_tr').style.display ='';
		document.getElementById('show_morediv').innerHTML = 'Filters<img src="images/down_arr.gif" /> ';
	}	
}
function handle_showmorediv_options()
{
	if(document.getElementById('more_options_tr').style.display=='')
	{
		document.getElementById('more_options_tr').style.display = 'none';
		/*document.getElementById('more_options_trimg').style.display = 'none';*/
		document.getElementById('show_morediv_options').innerHTML = 'More Actions<img src="images/right_arr.gif" />';
	}	
	else
	{
		document.getElementById('more_options_tr').style.display ='';
		/*document.getElementById('more_options_trimg').style.display ='';*/
		document.getElementById('show_morediv_options').innerHTML = 'Hide More Actions<img src="images/down_arr.gif" /> ';
	}	
}
function handle_export_products()
{
	var exp_opt = document.frm_products.cbo_export_product.value;
	if (exp_opt =='')
	{
		alert('Please select the export option');
		return false;	
	}
	if (exp_opt=='sel_prod') // case of selected order, check whether any orders ticked 
	{
		var atleast_one = false;
		var ids ='';
		for(i=0;i<document.frm_products.elements.length;i++)
		{
			if (document.frm_products.elements[i].type =='checkbox')
			{
				if (document.frm_products.elements[i].name=='checkbox[]')
				{
					if (document.frm_products.elements[i].checked==true)
					{
						atleast_one = true;
						if (ids!='')
							ids += '~';
				 		ids += document.frm_products.elements[i].value;
					}
				}	
			}	
		}
		if (atleast_one==false)
		{
			alert('Please select the product(s) to export');
			return false;
		}
		/* Write the logic to submit the details to order export section here*/
		document.frm_products.request.value 	= 'import_export';
		document.frm_products.export_what.value 	= 'prod';
		document.frm_products.fpurpose.value 	= '';
		document.frm_products.ids.value 	=ids;
		document.frm_products.submit();
		
		
	}
	else
	{
	// var atleast_one = false;
		var ids ='';
		for(i=0;i<document.frm_products.elements.length;i++)
		{
			if (document.frm_products.elements[i].type =='checkbox')
			{
				if (document.frm_products.elements[i].name=='checkbox[]')
				{
						atleast_one = true;
						if (ids!='')
							ids += '~';
				 		ids += document.frm_products.elements[i].value;
				}	
			}	
		}
		/* Write the logic to submit the details to order export section here*/
		document.frm_products.request.value 	= 'import_export';
		document.frm_products.export_what.value 	= 'prod';
		document.frm_products.fpurpose.value 	= '';
		document.frm_products.ids.value 	=ids;
		document.frm_products.submit();
	   
	}
}

</script>
<form method="post" name="frm_products" class="frmcls" action="home.php">
<input type="hidden" name="request" value="products" />
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="replicate_product_id" value="" />
<input type="hidden" name="export_what" value="" />
<input type="hidden" name="ids" value="" />
<input type="hidden" name="search_click" value="" />

<table border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td colspan="4" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span>List Products</span></div></td>
    </tr>
	<tr>
	  <td colspan="4" align="left" valign="middle" class="helpmsgtd_main">
	  <?php 
		  Display_Main_Help_msg($help_arr,$help_msg);
	  ?>	 </td>
	</tr>
	<?php 
			if($alert)
			{			
		?>
        		<tr>
          			<td colspan="4" align="center" valign="middle" class="errormsg"><?php echo($alert);?></td>
          		</tr>
		 <?php
		 	}
		 ?> 
	<?php 
		if ($db->num_rows($ret_qry))
		{
		?>
	<tr> <td  align="right" class="sorttd" colspan="4"><?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?> </td></tr>
		<?
		  }
		?>
    <tr>
      <td height="48" colspan="4" class="sorttd">
		<div class="editarea_div">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td align="left" valign="top">
		  <table width="100%" border="0" cellspacing="1" cellpadding="0">
            <tr>
              <td width="7%" align="left">Product Name</td>
              <td width="17%"  align="left"><input name="productname" type="text" class="textfeild" id="productname" value="<?php echo stripslashes($_REQUEST['productname'])?>" /></td>
              <td width="6%"  align="left">Product Id </td>
              <td width="17%" align="left">
			  <input name="manufactureid" type="text" class="textfeild" id="manufactureid" value="<?php echo $_REQUEST['manufactureid']?>" /></td>
              <td width="28%" align="left">Bulk Disc
<?php
			  	$bulkdisc_arr		= array(0=>'-- Any --','Y'=>'Yes','N'=>'No');
			  	echo generateselectbox('cbo_bulkdisc',$bulkdisc_arr,$_REQUEST['cbo_bulkdisc']);
				?></td>
              <td width="9%" align="left">Vendor</td>
              <td width="10%" align="left"><?php
			  	$vendor_arr		= array('-- Any --');
			  	$sql_vendors = "SELECT vendor_id,vendor_name FROM product_vendors WHERE sites_site_id=$ecom_siteid ORDER BY vendor_name";
				$ret_vendors = $db->query($sql_vendors);
				if ($db->num_rows($ret_vendors))
				{
					while ($row_vendors = $db->fetch_array($ret_vendors))
					{
						$id 				= $row_vendors['vendor_id'];
						$vendor_arr[$id] 	= stripslashes($row_vendors['vendor_name']);
					}
				}
				if(is_array($vendor_arr))
				{
					echo generateselectbox('vendorid',$vendor_arr,$_REQUEST['vendorid']);
				}
			  ?></td>
              <td align="left">&nbsp;</td>
            </tr>
            <tr>
              <td align="left">Category</td>
              <td colspan="3" align="left"><?php
			  	$cat_arr = generate_category_tree(0,0,true);
				if(is_array($cat_arr))
				{
					echo generateselectbox('categoryid',$cat_arr,$_REQUEST['categoryid']);
				}
			  ?></td>
              <td align="left">Sort By&nbsp;<?php echo $sort_option_txt;?>&nbsp;&nbsp;&nbsp;in&nbsp;&nbsp;&nbsp;<?php echo $sort_by_txt?></td>
              <td align="left">Records Per Page                </td>
              <td align="left"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3" value="<?php echo $records_per_page?>" /></td>
              <td width="6%" align="right"><input name="Search_go" type="submit" class="red" id="Search_go" value="Go" onclick="document.frm_products.search_click.value=1" />
                <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
            </tr>
            <tr>
              <td colspan="8" align="left"><div id="show_morediv" onclick="handle_showmorediv()" title="Click here">Filters<img src="images/right_arr.gif"></div></td>
              </tr>
            <tr id="listmore_tr" <?=$option_search_style_display?> >
              <td colspan="8" style="border-top:solid 1px #666666; padding-top:3px">

			  <table width="100%" border="0" cellspacing="1" cellpadding="1">
                <tr>
                  <td width="5%">&nbsp;</td>
                  <td width="15%" align="left">Retail Price Between </td>
                  <td width="5%" align="left"><input name="rprice_from" type="text" class="textfeild" id="rprice_from" value="<?php echo $_REQUEST['rprice_from']?>" size="10" /></td>
                  <td width="6%" align="center">and </td>
                  <td width="23%" align="left"><input name="rprice_to" type="text" class="textfeild" id="rprice_to" value="<?=($_REQUEST['rprice_to']==-1)?0:$_REQUEST['rprice_to']?>" size="10" /></td>
                  <td width="18%" align="left">In Preorder?</td>
                  <td width="28%" align="left"><?php
					$pre_order = array(0=>'-- Any --','Y'=>'Yes',"N"=>'No');
					echo generateselectbox('preorder',$pre_order,$_REQUEST['preorder']);
					?></td>
                  </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td align="left">Cost Price Between </td>
                  <td align="left">
				<input name="cprice_from" type="text" class="textfeild" id="cprice_from" value="<?php echo $_REQUEST['cprice_from']?>" size="10" /></td>
                  <td align="center">and </td>
                  <td align="left"><input name="cprice_to" type="text" class="textfeild" id="cprice_to" value="<?php echo $_REQUEST['cprice_to']?>" size="10" /></td>
                  <td align="left">Product Notification Required?</td>
                  <td align="left"><?php
					$not_order = array(0=>'-- Any --','Y'=>'Yes',"N"=>'No');
					echo generateselectbox('prodnotification',$not_order,$_REQUEST['prodnotification']);
					?></td>
                  </tr>
				
                <tr>
                  <td>&nbsp;</td>
                  <td align="left">Discount Atleast </td>
                  <td align="left"><input name="discount" type="text" class="textfeild" id="discount" value="<?php echo $_REQUEST['discount']?>" size="10" /></td>
                  <td align="center">Type</td>
                  <td align="left"><?php
					$disc_type = array(-1=>'-- Any --',0=>'%',1=>'Value',2=>'Exact Discount Price');
					if($_REQUEST['discountas']>-1)
					{
					 $field_rem = $_REQUEST['discountas'];
					}
					else
					 $field_rem = -1;
					echo generateselectbox('discountas',$disc_type,$field_rem);
					?></td>
                  <td align="left">Allow ordering even if out of stock?</td>
                  <td align="left"><?php
					$allow_order = array(0=>'-- Any --','Y'=>'Yes',"N"=>'No');
					echo generateselectbox('alloworderstock',$allow_order,$_REQUEST['alloworderstock']);
					?></td>
                  </tr>
				<tr>
                  <td>&nbsp;</td>
                  <td align="left">Total Stock Between </td>
                  <td align="left">
				<input name="stock_from" type="text" class="textfeild" id="stock_from" value="<?php echo $_REQUEST['stock_from']?>" size="10" /></td>
                  <td align="center">and </td>
                  <td align="left"><input name="stock_to" type="text" class="textfeild" id="stock_to" value="<?=($_REQUEST['stock_to']==-1)?0:$_REQUEST['stock_to'];?>" size="10" /></td>
                  <td align="left">Hidden?</td>
                  <td align="left"><?php
					$hidden_arr = array(0=>'-- Any --','Y'=>'Yes',"N"=>'No');
					echo generateselectbox('prodhidden',$hidden_arr,$_REQUEST['prodhidden']);
					?></td>
                  </tr>
				<?php /*?><tr>
                  <td>&nbsp;</td>
                  <td align="left">Stock Atleast </td>
                  <td colspan="3" align="left"><input name="stockatleast" type="text" class="textfeild" id="stockatleast" value="<?php echo $_REQUEST['stockatleast'];?>" size="10" /></td>
                </tr><?php */?>
                <?php /*?><tr>
                  <td>&nbsp;</td>
                  <td align="left">Bulk Discount Enabled? </td>
                  <td colspan="3" align="left">
				  <?php
					$disc_type = array(0=>'-- Any --','Y'=>'Yes',"N"=>'No');
					echo generateselectbox('discounten',$disc_type,$_REQUEST['discounten']);
					?>				  </td>
                </tr><?php */?>
				 
                <?php
				if($ecom_site_mobile_api==1)
				{
				?>
                 <tr>
                  <td>&nbsp;</td>
                  <td align="left">In Mobile Application</td>
                  <td colspan="5" align="left">
					<input name="in_mobile_api_sites" type="checkbox" id="in_mobile_api_sites" value="1" <?php echo ($_REQUEST['in_mobile_api_sites']==1)?'checked="checked"':''?>/>				  </td>
                </tr>
                <?php
				}
                ?>
              </table>
			  
			  </td>
            </tr>
          </table>            </td>
          </tr>
      </table>
		</div>      </td>
    </tr>
	
    
	 <tr>
      <td colspan="4" class="listingarea">
	  <div class="listingarea_div">
      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable">
	  <tr>
      <td colspan="12">
        <!--<a href="javascript:void(0);" class="scrollToTop" onclick="javascript:loadScroll();" id="scrollToTop">
        	<input type="hidden" name="scrollactive" id="scrollactive" value="1" />
            <img src="images/helpicon.png" alt="scrolltop" title="Click To Load Scroll Nav" />
        </a>-->
      <div id="nav">
        <ul>
            <li>
	  <table width="100%">
	  <tr><td colspan="3" align="right" class="listeditd">
	  <a href="home.php?request=products&fpurpose=add&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>" class="addlist">Add</a>
	  	<?php
			if ($db->num_rows($ret_qry))
			{
		?>	
				<a href="#" class="editlist" onclick="go_edit('<?php echo $_REQUEST['catgroupname']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['catgroupname']?>','<?=$_REQUEST['productname']?>','<?=$_REQUEST['manufactureid']?>','<?=$_REQUEST['categoryid']?>','<?=$_REQUEST['vendorid']?>','<?=$_REQUEST['rprice_from']?>','<?=$_REQUEST['rprice_to']?>','<?=$_REQUEST['cprice_from']?>','<?=$_REQUEST['cprice_to']?>','<?=$_REQUEST['discount']?>','<?=$_REQUEST['discountas']?>','<?=$_REQUEST['bulkdiscount']?>','<?=$_REQUEST['stockatleast']?>','<?=$_REQUEST['stock_from']?>','<?=$_REQUEST['stock_to']?>','<?=$_REQUEST['preorder']?>','<?=$_REQUEST['prodnotification']?>','<?=$_REQUEST['alloworderstock']?>','<?=$_REQUEST['prodhidden']?>','<?=$_REQUEST['in_mobile_api_sites']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>','<?=$_REQUEST['categoryid']?>')" class="deletelist">Delete</a> <a href="home.php?request=products&fpurpose=settingstomany&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&start=<?php echo $_REQUEST['start']?>&pg=<?php echo $_REQUEST['pg']?>&records_per_page=<?php echo $_REQUEST['records_per_page']?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>" class="settingslist">Multiple Settings</a><?php if($ecom_siteid==109){?><a href="home.php?request=listofcallback" style="color:black;text-decoration:none"  class="settingslist">View Callback</a><?php }?>
				<?php
					if(is_product_special_product_code_active())
					{
				?>
						<a href="home.php?request=products&fpurpose=manage_specialprodcode&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&start=<?php echo $_REQUEST['start']?>&pg=<?php echo $_REQUEST['pg']?>&records_per_page=<?php echo $_REQUEST['records_per_page']?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>" class="specialcodelist">Special Codes</a>
				<?php
					}
				?>
				<?php /*
<a href="#" class="replicatelist" onclick="go_replicate('<?php echo $_REQUEST['catgroupname']?>','<?=$_REQUEST['productname']?>','<?=$_REQUEST['manufactureid']?>','<?=$_REQUEST['categoryid']?>','<?=$_REQUEST['vendorid']?>','<?=$_REQUEST['rprice_from']?>','<?=$_REQUEST['rprice_to']?>','<?=$_REQUEST['cprice_from']?>','<?=$_REQUEST['cprice_to']?>','<?=$_REQUEST['discount']?>','<?=$_REQUEST['discountas']?>','<?=$_REQUEST['bulkdiscount']?>','<?=$_REQUEST['stockatleast']?>','<?=$_REQUEST['stock_from']?>','<?=$_REQUEST['stock_to']?>','<?=$_REQUEST['preorder']?>','<?=$_REQUEST['prodnotification']?>','<?=$_REQUEST['alloworderstock']?>','<?=$_REQUEST['prodhidden']?>','<?=$_REQUEST['in_mobile_api_sites']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>','<?=$_REQUEST['categoryid']?>')">copy</a>*/?>
		<?php
			}
		?>       
	 </td>
      <td width="35%" align="right" class="listeditd">
	    <?php if ($db->num_rows($ret_qry))
			{
			//echo $_REQUEST['categoryid']; 
			if($_REQUEST['categoryid']){?>
  <input name="change_order" type="button" class="red" id="change_order" value="Save Order" onclick="call_ajax_changeorder('<?=$_REQUEST['productname']?>','<?=$_REQUEST['manufactureid']?>','<?=$_REQUEST['categoryid']?>','<?=$_REQUEST['vendorid']?>','<?=$_REQUEST['rprice_from']?>','<?=$_REQUEST['rprice_to']?>','<?=$_REQUEST['cprice_from']?>','<?=$_REQUEST['cprice_to']?>','<?=$_REQUEST['discount']?>','<?=$_REQUEST['discountas']?>','<?=$_REQUEST['bulkdiscount']?>','<?=$_REQUEST['stockatleast']?>','<?=$_REQUEST['stock_from']?>','<?=$_REQUEST['stock_to']?>','<?=$_REQUEST['preorder']?>','<?=$_REQUEST['prodnotification']?>','<?=$_REQUEST['alloworderstock']?>','<?=$_REQUEST['prodhidden']?>','<?=$_REQUEST['in_mobile_api_sites']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" />
     <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_CHORD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
  <?php }
      }
  ?> 
	  
	  <?php if ($db->num_rows($ret_qry))
			{
			?>
Change Hide Status to
  <?php
					$chhide_array = array('0'=>'No','1'=>'Yes');
					echo generateselectbox('cbo_changehide',$chhide_array,0);
				?>
  <input name="change_hide" type="button" class="red" id="change_hide" value="Change" onclick="call_ajax_changestatus('<?=$_REQUEST['productname']?>','<?=$_REQUEST['manufactureid']?>','<?=$_REQUEST['categoryid']?>','<?=$_REQUEST['vendorid']?>','<?=$_REQUEST['rprice_from']?>','<?=$_REQUEST['rprice_to']?>','<?=$_REQUEST['cprice_from']?>','<?=$_REQUEST['cprice_to']?>','<?=$_REQUEST['discount']?>','<?=$_REQUEST['discountas']?>','<?=$_REQUEST['bulkdiscount']?>','<?=$_REQUEST['stockatleast']?>','<?=$_REQUEST['stock_from']?>','<?=$_REQUEST['stock_to']?>','<?=$_REQUEST['preorder']?>','<?=$_REQUEST['prodnotification']?>','<?=$_REQUEST['alloworderstock']?>','<?=$_REQUEST['prodhidden']?>','<?=$_REQUEST['in_mobile_api_sites']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" />
  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_CHST')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
  <?php
			}
		?></td>
      <td width="13%" align="right" class="listeditd">
	  <?php if ($db->num_rows($ret_qry))
			{
			?>
	  <div id="show_morediv_options" onclick="handle_showmorediv_options()" title="Click here" >More Actions<img src="images/right_arr.gif"></div>
	  <?php
	  		}
	  ?>	  </td>
	  </tr>
	<?php if ($db->num_rows($ret_qry))
			{
			?>	
		  
	<tr id="more_options_tr" style="display:none">
	<td colspan="5">
	<div class="sorttd_div">
	<table  border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
	<td width="50%" align="left" class="listeditd">Assign to category &nbsp;
		  <? //$category_arr = generate_category_tree(0,0);
						$category_arr = generate_category_tree(0,0,false,true);

				if(is_array($category_arr))
				{
					echo generateselectbox('cat_category_id',$category_arr,0);
				} ?>
		<input name="assign_to_category cat" type="button" class="red" id="assign_to_category" value="Assign" onclick="call_ajax_assignto_cat('category_assign','<?php echo $_REQUEST['productname']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" />
		<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_ASS_CAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>		</td>
	<td align="left" class="listeditd" colspan="2">
	Unassign From Category &nbsp;
	  <? 
				if(is_array($category_arr))
				{
					echo generateselectbox('un_cat_category_id',$category_arr,0);
				} ?>
      <input name="unassign_from_category" type="button" class="red" id="unassign_from_category" value="UnAssign" onclick="call_ajax_assignto_cat('category_unassign','<?php echo $_REQUEST['productname']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" />
      <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_UNASS_CAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>	</td>
	</tr>
	<tr id="more_options_tr2"  align="left" class="listeditd" colspan="5">
	<td align="left" class="listeditd">
	<div style="float:right;"><input name="bulkupdatedesc" type="button" class="red" id="bulkupdatedesc" value="Bulk Update Product Description" onclick="window.location='home.php?request=products&fpurpose=showbulkupdatedesc';"  /></div>		
		
		<?php if ($db->num_rows($ret_qry))
		{
		?>
  Exclude from Data Feed&nbsp;
  <?php
					$exclude_array = array('0'=>'No','1'=>'Yes');
					echo generateselectbox('cbo_exclude_feed',$exclude_array,0);
				?>
  <input name="assignimagetoprod" type="button" class="red" id="excludeprod_feed" value="Change" onclick="call_ajax_changedatafeedstatus('<?=$_REQUEST['productname']?>','<?=$_REQUEST['manufactureid']?>','<?=$_REQUEST['categoryid']?>','<?=$_REQUEST['vendorid']?>','<?=$_REQUEST['rprice_from']?>','<?=$_REQUEST['rprice_to']?>','<?=$_REQUEST['cprice_from']?>','<?=$_REQUEST['cprice_to']?>','<?=$_REQUEST['discount']?>','<?=$_REQUEST['discountas']?>','<?=$_REQUEST['bulkdiscount']?>','<?=$_REQUEST['stockatleast']?>','<?=$_REQUEST['stock_from']?>','<?=$_REQUEST['stock_to']?>','<?=$_REQUEST['preorder']?>','<?=$_REQUEST['prodnotification']?>','<?=$_REQUEST['alloworderstock']?>','<?=$_REQUEST['prodhidden']?>','<?=$_REQUEST['in_mobile_api_sites']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')">
  <a href="#" onmouseover ="ddrivetip('If you wish to avoid some product from appearing in data feed exported for Google base, NextTag, Bing etc, then this can be done using this option. Tick mark the products and select YES or NO from the drop down box and then click on the Change button.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
  <?php
	}
	?></td>
	<td width="30%" align="left" class="listeditd">Assign Image To Product &nbsp;
      <? //$category_arr = generate_category_tree(0,0);
				 ?>
      <input name="assignimagetoprod2" type="button" class="red" id="assignimagetoprod" value="Assign" onclick="normal_assign_ImageAssign('<?php echo $_REQUEST['productname']?>','<?php echo $_REQUEST['manufactureid']?>','<?php echo $_REQUEST['vendorid']?>','<?php echo $_REQUEST['cbo_bulkdisc']?>','<?php echo $_REQUEST['sort_by']?>','<?php echo $_REQUEST['sort_order']?>','<?php echo $_REQUEST['records_per_page']?>','<?php echo $_REQUEST['start']?>','<?php echo $_REQUEST['pg']?>','<?php echo $_REQUEST['categoryid']?>');" />
      <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_LINK_ASSIMAGE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	<td width="27%" align="left" class="listeditd">&nbsp;</td>
	</tr>
	</table>
	</div>
	</td>
	</tr>
	</table></li></ul></div>
	</td>
	</tr>
	<? }?>
	 <?php  
	 	echo table_header($table_headers,$header_positions); 
		if ($db->num_rows($ret_qry))
		{ 
			$srno = getStartOfPageno($records_per_page,$pg);
			while ($row_qry = $db->fetch_array($ret_qry))
			{
				$cls 		= ($srno%2==0)?'listingtablestyleA':'listingtablestyleB';
				if($row_qry['product_discount']>0)
				{
					$disctype	= ($row_qry['product_discount_enteredasval']==0)?'%':'';
					$discval_arr= explode(".",$row_qry['product_discount']);
					if($discval_arr[1]=='00')
						$discval = $discval_arr[0];
					else
						$discval = $row_qry['product_discount'];
					$disc		= $discval.$disctype;
					if(($row_qry['product_discount_enteredasval']==1 || $row_qry['product_discount_enteredasval']==2))
					{
					 $disc = display_price($disc);
					}
				}	
				else
					$disctype = $disc = '--';
	 ?>
			   	<tr>
				  <td align="left" valign="middle" class="<?php echo $cls?>" width="5%">
				  <input type="checkbox" name="checkbox[]" id="checkbox[]" value="<?php echo $row_qry['product_id']?>" /></td>
				  <td align="left" valign="middle" class="<?php echo $cls?>" width="1%"><?php echo $srno++?></td>
				  <td align="left" valign="middle" class="<?php echo $cls?>" width="20%"><a href="home.php?request=products&fpurpose=edit&checkbox[0]=<?php echo $row_qry['product_id']?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>" title="edit" class="edittextlink"><?php echo stripslashes($row_qry['product_name'])?></a></td>
				  <td align="left" valign="middle" class="<?php echo $cls?>"><?php echo stripslashes($row_qry['manufacture_id'])?></td>	
				  <td align="left" valign="middle" class="<?php echo $cls?>">
				  <?php
				  		$catSET_WIDTH = '150px';
				  		$cat_arr		= array();
				  		// Get the list of categories to which the current product is assigned to 
						$sql_cats = "SELECT a.category_id,a.category_name FROM product_categories a,product_category_map b WHERE 
									b.products_product_id=".$row_qry['product_id']." AND a.category_id=b.product_categories_category_id";
						$ret_cats = $db->query($sql_cats);
						if ($db->num_rows($ret_cats))
						{
							while ($row_cats = $db->fetch_array($ret_cats))
							{
								$catid = $row_cats['category_id'];
								$cat_arr[$catid] = stripslashes($row_cats['category_name']);
							}	
						}
						if (count($cat_arr))
						{
							echo generateselectbox('catid_'.$row_qry['product_id'],$cat_arr,0);
						}
						else
							echo "--";	
						$catSET_WIDTH = '';	
				  ?>				  </td>	
				  <td align="left" valign="middle" class="<?php echo $cls?>"><?php echo display_price($row_qry['product_webprice'])?></td>
				  <td align="left" valign="middle" class="<?php echo $cls?>"><?php echo display_price($row_qry['product_costprice'])?></td>
		          <?php 
				  if ($_REQUEST['categoryid']){
				  		$sql_query_order = "SELECT product_order FROM product_category_map WHERE products_product_id=".$row_qry['product_id']." AND product_categories_category_id=".$_REQUEST['categoryid']."";
						$ret_query_order = $db->query($sql_query_order);
						$row_query_order = $db->fetch_array($ret_query_order);
				  ?>
				  <td align="left" valign="middle" class="<?php echo $cls?>"><input type="text" name="<? echo $row_qry['product_id']?>"  value="<?php echo $row_query_order['product_order']?>" size="3" /></td>
				  <?php }?>
				  <td align="center" valign="middle" class="<?php echo $cls?>"><?php echo ($row_qry['product_bulkdiscount_allowed']=='Y')?'Yes':'No';?></td>	
				  <td align="center" valign="middle" class="<?php echo $cls?>"><?php echo $disc?></td>
				  <td align="center" valign="middle" class="<?php echo $cls?>">
				  <?php 
				  $web_stock = '';
				  if($row_qry['product_variablestock_allowed']=='Y')
				  {
				 	$ret_stock  	= get_product_stock($row_qry);
					//$actual_stock	= $ret_stock['act_stock'];
					$web_stock		= $ret_stock['web_stock']	;
				  }
				  else
				  {
				   	//$actual_stock	= $row_qry['product_actualstock'];
					$web_stock		= $row_qry['product_webstock']	;
				  }
				  if($web_stock=='')
				  	$web_stock = 0;
				  $actual_stock	= $row_qry['product_actualstock'];
				  echo $web_stock."(".$actual_stock.")" ; //echo $row_qry['product_webstock']."(".$row_qry['product_actualstock'].")"?></td>
					<td align="center" valign="middle" class="<?php echo $cls?>">
					<?php
				  		echo ($row_qry['product_exclude_from_feed']=='Y')?'Yes':'No';	
					?>	
					</td>
					 <td align="center" valign="middle" class="<?php echo $cls?>">
				  	<?php
				  		echo ($row_qry['product_hide']=='Y')?'Yes':'No';	
					?>				</td>
					</tr>
	<?php
			}
		}
		else
		{
	?>	
			<tr>
				  <td align="center" valign="middle" class="norecordredtext" colspan="<?php echo $colspan?>">
				  	No Products found.				  </td>
			</tr>	  
	<?php
		}
	?><tr>
		  <td align="left" valign="middle" class="listeditd" colspan="<?php echo round($colspan/2);?>">
			<a href="home.php?request=products&fpurpose=add&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&start=<?php echo $start?>&p_f=<?php echo $p_f?>&records_per_page=<?php echo $records_per_page?>" class="addlist" onclick="show_processing();">Add</a>
	<?php 
		if ($db->num_rows($ret_qry))
		{
	?>
	  		<a href="#" class="editlist" onclick="go_edit('<?php echo $_REQUEST['catgroupname']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>')">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['catgroupname']?>','<?=$_REQUEST['productname']?>','<?=$_REQUEST['manufactureid']?>','<?=$_REQUEST['categoryid']?>','<?=$_REQUEST['vendorid']?>','<?=$_REQUEST['rprice_from']?>','<?=$_REQUEST['rprice_to']?>','<?=$_REQUEST['cprice_from']?>','<?=$_REQUEST['cprice_to']?>','<?=$_REQUEST['discount']?>','<?=$_REQUEST['discountas']?>','<?=$_REQUEST['bulkdiscount']?>','<?=$_REQUEST['stockatleast']?>','<?=$_REQUEST['stock_from']?>','<?=$_REQUEST['stock_to']?>','<?=$_REQUEST['preorder']?>','<?=$_REQUEST['prodnotification']?>','<?=$_REQUEST['alloworderstock']?>','<?=$_REQUEST['prodhidden']?>','<?=$_REQUEST['in_mobile_api_sites']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>','<?=$_REQUEST['categoryid']?>')" class="deletelist">Delete</a>
	        <a href="home.php?request=products&amp;fpurpose=settingstomany&amp;productname=<?php echo $_REQUEST['productname']?>&amp;manufactureid=<?php echo $_REQUEST['manufactureid']?>&amp;categoryid=<?php echo $_REQUEST['categoryid']?>&amp;vendorid=<?php echo $_REQUEST['vendorid']?>&amp;rprice_from=<?php echo $_REQUEST['rprice_from']?>&amp;rprice_to=<?php echo $_REQUEST['rprice_to']?>&amp;cprice_from=<?php echo $_REQUEST['cprice_from']?>&amp;cprice_to=<?php echo $_REQUEST['cprice_to']?>&amp;discount=<?php echo $_REQUEST['discount']?>&amp;discountas=<?php echo $_REQUEST['discountas']?>&amp;bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&amp;stockatleast=<?php echo $_REQUEST['stockatleast']?>&amp;preorder=<?php echo $_REQUEST['preorder']?>&amp;prodhidden=<?php echo $_REQUEST['prodhidden']?>&amp;in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&amp;start=<?php echo $_REQUEST['start']?>&amp;pg=<?php echo $_REQUEST['pg']?>&amp;records_per_page=<?php echo $_REQUEST['records_per_page']?>&amp;sort_by=<?php echo $sort_by?>&amp;sort_order=<?php echo $sort_order?>" class="settingslist">Multiple Settings</a>
			
			<?php
					if(is_product_special_product_code_active())
					{
				?>
						<a href="home.php?request=products&fpurpose=manage_specialprodcode&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&start=<?php echo $_REQUEST['start']?>&pg=<?php echo $_REQUEST['pg']?>&records_per_page=<?php echo $_REQUEST['records_per_page']?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>" class="specialcodelist">Special Codes</a>
				<?php
					}
				?>
           <?php /* <a href="#" class="replicatelist" onclick="go_replicate('<?php echo $_REQUEST['catgroupname']?>','<?=$_REQUEST['productname']?>','<?=$_REQUEST['manufactureid']?>','<?=$_REQUEST['categoryid']?>','<?=$_REQUEST['vendorid']?>','<?=$_REQUEST['rprice_from']?>','<?=$_REQUEST['rprice_to']?>','<?=$_REQUEST['cprice_from']?>','<?=$_REQUEST['cprice_to']?>','<?=$_REQUEST['discount']?>','<?=$_REQUEST['discountas']?>','<?=$_REQUEST['bulkdiscount']?>','<?=$_REQUEST['stockatleast']?>','<?=$_REQUEST['stock_from']?>','<?=$_REQUEST['stock_to']?>','<?=$_REQUEST['preorder']?>','<?=$_REQUEST['prodnotification']?>','<?=$_REQUEST['alloworderstock']?>','<?=$_REQUEST['prodhidden']?>','<?=$_REQUEST['in_mobile_api_sites']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>','<?=$_REQUEST['categoryid']?>')">Copy</a>*/?>
      <?php
	 	}
	 ?></td>
			<td align="right" valign="middle" class="listeditd" colspan="<?php echo round($colspan/2);?>">
			</td>
	</tr>
      </table>
	  </div></td>
    </tr>
	<tr>
	<td colspan="13" class="listing_bottom_paging">
	<?php 
	if ($db->num_rows($ret_qry))
	{	
		paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan);
	}
	?>
	</td>
	</tr>
	<?
	  if($db->num_rows($ret_qry))
	  {
	    if(is_module_valid('mod_importexport','onconsole'))
	 	{
			if($ecom_siteid != 104 and $ecom_siteid != 105)
			{
	?>
	<tr>
      <td colspan="4" class="listingarea">
	  <div class="editarea_div">
	  <table width="100%">
	<tr>
	 <td width="100%" class="listeditd" align="left">Export Product(s)&nbsp;&nbsp;&nbsp;
	  <select name="cbo_export_product" id="cbo_export_product">
	 			<option value="">-- Select --</option>
	 			<option value="sel_prod">Export Selected Products</option>
	 			<option value="all_prod">Export All Products</option>
	 		</select>
	 		<input type="button" name="submit_prodexport" id="submit_prodexport" value="Export Now" class="red" onclick="handle_export_products()" />
	 <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_EXPORT_CAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	 </tr>
	 <?
			}
	     }
	 }
	  ?>
    </table>
	</div>
</form>
<!-- Script for auto complete starts here -->
<script language="javascript">
var $pnc = jQuery.noConflict();
$pnc().ready(function() { 
	auto_search('productname','prdts'); 
	auto_search('manufactureid','prdts'); 
});
</script>
<!-- Script for auto complete ends here -->
<?php
function get_product_stock($row_prod)
{
	global $db;
	if($row_prod['product_variablestock_allowed'] == 'Y') // Case of variable stock exists
	{
		// Get the combinations for current product
		$sql_comb = "SELECT sum(actual_stock) as actual_stock_sum,sum(web_stock) as totwebstock FROM product_variable_combination_stock WHERE products_product_id=".$row_prod['product_id'];
		$ret_comb = $db->query($sql_comb);
		if ($db->num_rows($ret_comb))
		{
			list($actual_stock,$web_stock) = $db->fetch_array($ret_comb);
			$ret_arr['act_stock'] = $actual_stock;
			$ret_arr['web_stock'] = $web_stock;
		}
	}
	else // Case variable stock does not exists
	{
		$ret_arr['act_stock'] = $row_prod['product_actualstock'];
		$ret_arr['web_stock'] = $row_prod['product_webstock'];;
	}
	return $ret_arr;
}
?>
