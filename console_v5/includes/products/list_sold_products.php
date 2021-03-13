<?php
	/*#################################################################
	# Script Name 		: list_sold_products.php
	# Description 		: Page for listing of products sold in a given range of dates
	# Coded by 			: Sny
	# Created on		: 17-Feb-2009
	# Modified by		: 
	# Modified On		: 
	#################################################################*/
//Define constants for this page
$table_name	= 'products';
$page_type	= 'Products';
$help_msg = get_help_messages('LIST_PRODUCTS_SOLD');
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frm_products,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frm_products,\'checkbox[]\')"/>','Slno.','Product','Category','Total Order Qty','Total Amount');
$header_positions=array('center','left','left','left','right','right');

$colspan = count($table_headers);

//#Search terms
$search_fields = array('productname','prd_fromdate','categoryid','vendorid','prd_todate');

$query_string = "request=products&fpurpose=list_sold_product";
$option_search_style_display = 'style="display:"';
if($_REQUEST['discountas']=='')
{
	$_REQUEST['discountas']=-1;
}
foreach($search_fields as $v) {

	$query_string .= "&$v=".$_REQUEST[$v]."";//#For passing searh terms to javascript for passing to different pages.
}
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
// ==================================================================================================
// ==================================================================================================
$sql_count = "SELECT p.product_id,sum(b.order_orgqty) as totcnt , sum(b.product_soldprice*b.order_orgqty) as totamt
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
		";
		
		
		
//#Select condition for getting total count
//$sql_count = "SELECT count(*) as cnt FROM $table_name  ";
$res_count = $db->query($sql_count);
$numcount  = $db->num_rows($res_count);#Getting total count of records
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
					totcnt DESC 
				LIMIT 
					$start,$records_per_page ";

//$sql_qry = "SELECT * FROM products $where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
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
function call_ajax_delete(cname,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var del_ids 			= '';
	var qrystr				= 'catgroupname='+cname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;;
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
function call_ajax_changestatus(pname,manid,catid,vendorid,rprice_from,rprice_to,cpricefrom,cpriceto,discount,discountas,bulkdiscount,stockatleast,stock_from,stock_to,preorder,prod_notification,prod_alloworderstock,prodhidden,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var prod_ids			= 0;
	var cat_ids 			= '';
	var cat_orders			= '';
	var ch_status			= document.frm_products.cbo_changehide.value;
	var qrystr				= 'productname='+pname+'&manufactureid='+manid+'&categoryid='+catid+'&vendorid='+vendorid+'&rprice_from='+rprice_from+'&rprice_to='+rprice_to+'&cprice_from='+cpricefrom+'&cprice_to='+cpriceto+'&discount='+discount+'&discountas='+discountas+'&bulkdiscount='+bulkdiscount+'&stockatleast='+stockatleast+'&preorder='+preorder+'&prodhidden='+prodhidden+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&ch_status='+ch_status+'&start='+start+'&pg='+pg+'&stock_from='+stock_from+'&stock_to='+stock_to+'&prodnotification='+prod_notification+'&alloworderstock='+prod_alloworderstock;
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
function call_ajax_changeorder(pname,manid,catid,vendorid,rprice_from,rprice_to,cpricefrom,cpriceto,discount,discountas,bulkdiscount,stockatleast,stock_from,stock_to,preorder,prod_notification,prod_alloworderstock,prodhidden,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var prod_ids			= 0;
	var cat_orders			= '';
	var qrystr				= 'productname='+pname+'&manufactureid='+manid+'&categoryid='+catid+'&vendorid='+vendorid+'&rprice_from='+rprice_from+'&rprice_to='+rprice_to+'&cprice_from='+cpricefrom+'&cprice_to='+cpriceto+'&discount='+discount+'&discountas='+discountas+'&bulkdiscount='+bulkdiscount+'&stockatleast='+stockatleast+'&preorder='+preorder+'&prodhidden='+prodhidden+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&stock_from='+stock_from+'&stock_to='+stock_to+'&prodnotification='+prod_notification+'&alloworderstock='+prod_alloworderstock;
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
		document.getElementById('show_morediv').innerHTML = 'Options<img src="images/right_arr.gif" />';
	}	
	else
	{
		document.getElementById('listmore_tr').style.display ='';
		document.getElementById('show_morediv').innerHTML = 'Options<img src="images/down_arr.gif" /> ';
	}	
}
function handle_showmorediv_options()
{
	if(document.getElementById('more_options_tr').style.display=='')
	{
		document.getElementById('more_options_tr').style.display = 'none';
		document.getElementById('show_morediv_options').innerHTML = 'More Actions<img src="images/right_arr.gif" />';
	}	
	else
	{
		document.getElementById('more_options_tr').style.display ='';
		document.getElementById('show_morediv_options').innerHTML = 'More Actions<img src="images/down_arr.gif" /> ';
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
<input type="hidden" name="fpurpose" value="list_sold_product" />
<input type="hidden" name="export_what" value="" />
<input type="hidden" name="ids" value="" />
<input type="hidden" name="search_click" value="" />

<table border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td width="1753" colspan="4" align="left" valign="middle" class="treemenutd">
		  <div class="treemenutd_div"><span>List Products Sold <?php echo $head_caption?></span></div></td>
    </tr>
	<tr>
	  <td colspan="4" align="left" valign="middle" class="helpmsgtd_main">
	  <?php 
		  Display_Main_Help_msg($help_arr,$help_msg);
	  ?>
	 </td>
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
	<tr>
	 <td  align="right" class="sorttd" colspan="4">
        <?
			paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan);
		?>		</td>
		</tr>
		<?
		  }
		?>
    <tr>
      <td height="48" colspan="4" class="sorttd">
		<div class="sorttd_div">

		<table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="70%" align="left" valign="top">
		  <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td width="11%" align="left">Product Name</td>
              <td width="36%" align="left"><input name="productname" type="text" class="textfeild" id="productname" value="<?php echo $_REQUEST['productname']?>" /></td>
              <td width="10%" align="left">Category</td>
              <td width="43%" align="left">
			  <?php
			  	$cat_arr = generate_category_tree(0,0,true);
				if(is_array($cat_arr))
				{
					echo generateselectbox('categoryid',$cat_arr,$_REQUEST['categoryid']);
				}
			  ?>			  </td>
            </tr>
            <tr>
              <td align="left">Between</td>
              <td align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="24%" align="left"><input name="prd_fromdate" class="textfeild" type="text" size="12" value="<?php echo $_REQUEST['prd_fromdate']?>" /></td>
                  <td width="12%" align="left"><a href="javascript:show_calendar('frm_products.prd_fromdate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a> </td>
                  <td width="14%" align="left"> and </td>
                  <td width="24%" align="left"><input name="prd_todate" class="textfeild" type="text" size="12" value="<?php echo $_REQUEST['prd_todate']?>" /></td>
                  <td width="26%" align="left"><a href="javascript:show_calendar('frm_products.prd_todate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a></td>
                  </tr>
              </table></td>
              <td align="left">Vendor</td>
              <td align="left">
			  <?php
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
			  ?>			  
              &nbsp;&nbsp;</td>
              </tr>
          </table>            </td>
          <td width="30%" align="left" valign="top">
		  <table width="89%" height="50" border="0" align="right" cellpadding="0" cellspacing="0">
            <tr>
              <td align="left">Show
                <input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3" value="<?php echo $records_per_page?>" />
                <?php echo $page_type?> Per Page</td>
              <td width="23%" align="left">&nbsp;</td>
            </tr>
            <tr>
              <td align="left">&nbsp;</td>
              <td align="left"><input name="Search_go" type="submit" class="red" id="Search_go" value="Go" onclick="document.frm_products.search_click.value=1" />
                <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
            </tr>
          </table>		  </td>
        </tr>
      </table>     
      </div>
       </td>
    </tr>
	
	<?php if ($db->num_rows($ret_qry))
			{
			?>
	<? }?>
	 <tr>
      <td colspan="4" class="listingarea">
		  		<div class="listingarea_div">

      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable">
	 <?php  
	 	echo table_header($table_headers,$header_positions); 
		if ($db->num_rows($ret_qry))
		{ 
			$srno = getStartOfPageno($records_per_page,$pg);
			$tot_cnts =	$totamts = 0;
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
				  <td align="left" valign="middle" class="<?php echo $cls?>" width="30%"><a href="home.php?request=products&fpurpose=edit&checkbox[0]=<?php echo $row_qry['product_id']?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>" title="edit" class="edittextlink"><?php echo stripslashes($row_qry['product_name'])?></a></td>
				  <td align="left" valign="middle" class="<?php echo $cls?>">
				  <?php
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
				  ?></td>	
				  <td align="right" valign="middle" class="<?php echo $cls?>"><?php echo stripslashes($row_qry['totcnt'])?></td>	
				  <td align="right" valign="middle" class="<?php echo $cls?>"><?php echo display_price($row_qry['totamt'])?></td>
				  
				</tr>
	<?php
		$tot_cnts += $row_qry['totcnt'];
		$totamts 	+= $row_qry['totamt'];
			}
		?>
				<tr>
				  <td align="right" valign="middle" class="<?php echo $cls?>" colspan="4">&nbsp;
				  
				  </td>
				  <td align="right" valign="middle" class="<?php echo $cls?>">--------------------</td>	
				  <td align="right" valign="middle" class="<?php echo $cls?>">--------------------</td>
				</tr>
				<tr>
				  <td align="right" valign="middle" class="<?php echo $cls?>" colspan="4">
				  <strong>Page Total</strong>
				  </td>
				  <td align="right" valign="middle" class="<?php echo $cls?>"><strong><?php echo $tot_cnts?></strong></td>	
				  <td align="right" valign="middle" class="<?php echo $cls?>"><strong><?php echo display_price($totamts)?></strong></td>
				</tr>
				<tr>
				  <td align="right" valign="middle" class="<?php echo $cls?>" colspan="4">&nbsp;
				  
				  </td>
				  <td align="right" valign="middle" class="<?php echo $cls?>">====================</td>	
				  <td align="right" valign="middle" class="<?php echo $cls?>">====================</td>
				</tr>
		<?php	
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
	?>
	<?php 
		if ($db->num_rows($ret_qry))
		{
		?>
		<tr>
		<td colspan="6" align="right" class="listeditd">
		<?
			paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan);
		?>		</td>
		</tr>
		
		<?
		}
		?>
      </table>
      </div>
      </td>
    </tr>
	
	
    </table>
</form>

<?php 
	if ($db->num_rows($ret_qry))
	{
	?>
		
		<form method="post" name="frm_soldproducts" class="frmcls" action="export_soldproducts.php">
		<input type="hidden" name="pass_productname" value="<?php echo $_REQUEST['productname']?>" />
		<input type="hidden" name="pass_prd_fromdate" value="<?php echo $_REQUEST['prd_fromdate']?>" />
		<input type="hidden" name="pass_prd_todate" value="<?php echo $_REQUEST['prd_todate']?>" />
		<input type="hidden" name="pass_categoryid" value="<?php echo $_REQUEST['categoryid']?>" />
		<input type="hidden" name="pass_vendorid" value="<?php echo $_REQUEST['vendorid']?>" />
		<div class="editarea_div">
		<table border="0" cellpadding="0" cellspacing="0" class="maintable">
		 <tr>
			<td colspan="4" align="left" class="listeditd">Download Details 
			<input type="button" class="red" value="Click to Dowload" onclick="handle_downloadsold()">
			</td>
		</tr>
		</table>
		</div>
		</form>
			
		<script type="text/javascript">
		function handle_downloadsold()
		{
			document.frm_soldproducts.submit();
		}
		</script>

<?
	}
?>


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
