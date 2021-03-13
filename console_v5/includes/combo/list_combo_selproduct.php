<?php
	/*#################################################################
	# Script Name 	: list_combo_selproduct.php
	# Description 	: Page for listing Products
	# Coded by 		: Skr
	# Created on	: 31-July-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/

$table_name='products';
$page_type='Products';
$help_msg = get_help_messages('LIST_COMBO_SEL_PROD_MESS1');
$combo_id=($_REQUEST['pass_combo_id']?$_REQUEST['pass_combo_id']:'0');

$tabale = "combo";
$where  = "combo_id=".$combo_id;
if(!server_check($tabale, $where)) {
	echo " <font color='red'> You Are Not Authorised  </a>";
	exit;
}	

$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frm_products,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frm_products,\'checkbox[]\')"/>','Slno.','Product','Man ID','Category','Retail','Cost','Bulk Disc','Disc','Stock(All)','Hide');
$header_positions=array('center','left','left','left','left','left','left','center','center','left','center');
$colspan = count($table_headers);

//#Search terms
$search_fields = array('productname','manufactureid','categoryid','vendorid','rprice_from','rprice_to','cprice_from','cprice_to','discount','discountas','bulkdiscount','stockatleast','preorder','prodhidden','sort_order','stock_from','stock_to','sort_by','records_per_page');

$query_string = "request=combo&fpurpose=prodComboAssign";
foreach($search_fields as $v) {
	$query_string .= "&$v=".$_REQUEST[$v]."";//#For passing searh terms to javascript for passing to different pages.
}
$query_string .="&pass_searchname=".$_REQUEST['pass_searchname']."&pass_sortby=".$_REQUEST['pass_sortby']."&pass_sortorder=".$_REQUEST['pass_sortorder']."&pass_start=".$_REQUEST['pass_start']."&pass_pg=".$_REQUEST['pass_pg']."&pass_records_per_page=".$_REQUEST['pass_records_per_page']."";	
$query_string .="&pass_combo_id=".$combo_id."";
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'product_name':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('product_name' => 'Name','manufacture_id'=>'Manufacture Id','product_costprice'=>'Cost Price','product_webprice'=>'Web Price');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";

//#Avoiding already assigned product
$sql_assigned="SELECT products_product_id FROM combo_products WHERE  combo_combo_id=".$combo_id;
$ret_assigned = $db->query($sql_assigned);
$str_assigned='-1';

while($row_assigned = $db->fetch_array($ret_assigned))
{
	$str_assigned.=','.$row_assigned['products_product_id'];
	
}
$str_assigned='('.$str_assigned.')';	
$where_conditions.=" AND product_id NOT IN $str_assigned";
	
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

/*
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
	}	
	$include_prod = array();
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
	}	
	if (count($include_prod))
	{
		$include_prod_str = implode(",",$include_prod);
		$where_conditions .= " AND ( product_id IN ($include_prod_str)) ";
	}
*/
}
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
if(is_numeric(trim($_REQUEST['stock_from'])))
$cstock_f = trim($_REQUEST['stock_from']);
if(is_numeric(trim($_REQUEST['stock_to'])))
$cstock_t = trim($_REQUEST['stock_to']);
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
$option_search_style_display = 'style="display:"';
// to hold the options TR if searched using the options feild
if(($_REQUEST['rprice_from']!='') || ($_REQUEST['rprice_to']!='') ||  ($_REQUEST['cprice_from']!='')|| ($_REQUEST['cprice_to']!='') || ($_REQUEST['discount']!='') || ($_REQUEST['stock']!='')  || ($_REQUEST['stockatleast'])  || ($_REQUEST['stock_from']!='')  || ($_REQUEST['stock_to']!='')  || ($_REQUEST['discounten'])  || ($_REQUEST['preorder']) || ($_REQUEST['prodhidden'])){
	$option_search_style_display = 'style="display:"';
}
else{
	$option_search_style_display = 'style="display:none"';
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

$sql_qry = "SELECT * FROM products	$where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
$ret_qry = $db->query($sql_qry);
?>
<script type="text/javascript">
function call_save_selected(cname,sortby,sortorder,recs,start,pg,group_id) 
{
	
	var atleastone 			= 0;
	for(i=0;i<document.frm_products.elements.length;i++)
	{
		if (document.frm_products.elements[i].type =='checkbox' && document.frm_products.elements[i].name=='checkbox[]')
		{

			if (document.frm_products.elements[i].checked==true)
			{
				atleastone ++;
				
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select the product  to assign');
	}
	
	else
	{
		if(confirm('Are you sure you want to assign selected Products ?'))
		{
			show_processing();
			document.frm_products.fpurpose.value='save_prodComboAssign';
			document.frm_products.submit();
		}	
	}	

}
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

function call_ajax_assign(cname,sortby,sortorder,recs,start,pg)
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
				atleastone ++;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frm_products.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select the product  to assign');
	}
	
	else
	{
		if(confirm('Are you sure you want to assign selected Product ?'))
		{
			show_processing();
			Handlewith_Ajax('services/featured_product.php','fpurpose=save_selected&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}

</script>
<form method="post" name="frm_products" class="frmcls" action="home.php">
<input type="hidden" name="request" value="combo" />
<input type="hidden" name="fpurpose" value="prodComboAssign" />
<input type="hidden" name="search_click" value="" />
<input type="hidden" name="pass_combo_id" value="<?=$combo_id?>" />
<input type="hidden" name="pass_searchname" value="<?=$_REQUEST['pass_searchname']?>" />
<input type="hidden" name="pass_sortby" value="<?=$_REQUEST['pass_sortby']?>" />
<input type="hidden" name="pass_sortorder" value="<?=$_REQUEST['pass_sortorder']?>" />
<input type="hidden" name="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
<input type="hidden" name="pass_start" value="<?=$_REQUEST['pass_start']?>" />
<input type="hidden" name="pass_pg" value="<?=$_REQUEST['pass_pg']?>" />
<? $sql_combo="SELECT combo_name FROM combo WHERE  combo_id=".$combo_id;
$ret_combo = $db->query($sql_combo);
$row_combo = $db->fetch_array($ret_combo);
?>


<table border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=combo&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>">List Combo </a> <a href="home.php?request=combo&fpurpose=edit&checkbox[0]=<?=$combo_id?>&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=products_tab_td">Edit Combo </a><span> Assign Product for '<? echo $row_combo['combo_name'];?>'</span></div></td>
    </tr>
	<tr>
	  <td align="left" valign="middle" class="helpmsgtd_main" colspan="2">
	  <?php 
		  Display_Main_Help_msg($help_arr,$help_msg);
	  ?>	 </td>
	</tr>
	<?php 
			if($alert)
			{			
		?>
        		<tr>
          			<td colspan="2" align="center" valign="middle" class="errormsg"><?php echo($alert);?></td>
          		</tr>
		 <?php
		 	}
		 ?> 
	<?php 
		if ($db->num_rows($ret_qry))
		{
	?>	
	<tr>
		<td colspan="2" align="right" valign="middle" class="sorttd"><?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?></td>
	</tr>
	<?php
		}
	?>
		 
    <tr>
      <td height="48" colspan="2" class="sorttd">
		<div class="sorttd_div">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="66%" align="left" valign="top">
		  <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td width="11%" height="30" align="left">Product Name</td>
              <td width="17%" height="30" align="left"><input name="productname" type="text" class="textfeild" id="productname" value="<?php echo $_REQUEST['productname']?>" /></td>
              <td width="7%" height="30" align="left">Category</td>
              <td width="27%" height="30" align="left">
			  <?php
			  	$cat_arr = generate_category_tree(0,0);
				if(is_array($cat_arr))
				{
					echo generateselectbox('categoryid',$cat_arr,$_REQUEST['categoryid']);
				}
			  ?>			  </td>
              <td width="13%" height="30" align="left">Records Per Page </td>
              <td width="25%" height="30" align="left"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3" value="<?php echo $records_per_page?>" /></td>
              </tr>
            <tr>
              <td height="30" align="left">Product Id </td>
              <td height="30" align="left"><input name="manufactureid" type="text" class="textfeild" id="manufactureid" value="<?php echo $_REQUEST['manufactureid']?>" /></td>
              <td height="30" align="left">Vendor</td>
              <td height="30" align="left">
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
			  ?>			  </td>
              <td height="30" align="left">Sort By </td>
              <td height="30" align="left"><?php echo $sort_option_txt;?>&nbsp;&nbsp;&nbsp;in&nbsp;&nbsp;&nbsp;<?php echo $sort_by_txt?> <input name="Search_go" type="submit" class="red" id="Search_go" value="Go" onclick="document.frm_products.search_click.value=1" />
                <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_COMBO_SEL_PROD_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
              </tr>
            <tr>
              <td colspan="6" align="left"><div id="show_morediv" onclick="handle_showmorediv()" title="Click here">Options<img src="images/right_arr.gif"></div></td>
              </tr>
            <tr id="listmore_tr" <?=$option_search_style_display?>>
              <td colspan="6"><table width="100%" border="0" cellspacing="1" cellpadding="1">
                <tr>
                  <td width="9%" height="30">&nbsp;</td>
                  <td width="22%" height="30" align="left">Retail Price Between </td>
                  <td width="9%" height="30" align="left"><input name="rprice_from" type="text" class="textfeild" id="rprice_from" value="<?php echo $_REQUEST['rprice_from']?>" size="10" /></td>
                  <td width="5%" height="30" align="center">and </td>
                  <td width="55%" height="30" align="left"><input name="rprice_to" type="text" class="textfeild" id="rprice_to" value="<?php echo $_REQUEST['rprice_to']?>" size="10" /></td>
                </tr>
                <tr>
                  <td height="30">&nbsp;</td>
                  <td height="30" align="left">Cost Price Between </td>
                  <td height="30" align="left">
				<input name="cprice_from" type="text" class="textfeild" id="cprice_from" value="<?php echo $_REQUEST['cprice_from']?>" size="10" /></td>
                  <td height="30" align="center">and </td>
                  <td height="30" align="left"><input name="cprice_to" type="text" class="textfeild" id="cprice_to" value="<?php echo $_REQUEST['cprice_to']?>" size="10" /></td>
                </tr>
                <tr>
                  <td height="30">&nbsp;</td>
                  <td height="30" align="left">Discount Atleast </td>
                  <td height="30" align="left"><input name="discount" type="text" class="textfeild" id="discount" value="<?php echo $_REQUEST['discount']?>" size="10" /></td>
                  <td height="30" align="center">Type</td>
                  <td height="30" align="left"><?php
					$disc_type = array(-1=>'-- Any --','0'=>'%','1'=>'Value','2'=>'Exact Discount Price',);
					if($_REQUEST['discountas']>-1)
					{
					 $field_rem = $_REQUEST['discountas'];
					}
					else
					 $field_rem = -1;
					echo generateselectbox('discountas',$disc_type,$field_rem);
					?></td>
                </tr>
                <? /*<tr>
                  <td>&nbsp;</td>
                  <td align="left">Stock Atleast </td>
                  <td colspan="3" align="left"><input name="stockatleast" type="text" class="textfeild" id="stock" value="<?php echo $_REQUEST['stockatleast']?>" size="10" /></td>
                </tr>*/
				?>
				<tr>
                  <td height="30">&nbsp;</td>
                  <td height="30" align="left">Total Stock Between </td>
                  <td height="30" align="left">
				<input name="stock_from" type="text" class="textfeild" id="stock_from" value="<?php echo $_REQUEST['stock_from']?>" size="10" /></td>
                  <td height="30" align="center">and </td>
                  <td height="30" align="left"><input name="stock_to" type="text" class="textfeild" id="stock_to" value="<?=($_REQUEST['stock_to']==-1)?0:$_REQUEST['stock_to'];?>" size="10" /></td>
                </tr>
                <? /*<tr>
                  <td>&nbsp;</td>
                  <td align="left">Bulk Discount Enabled? </td>
                  <td colspan="3" align="left">
				  
					$disc_type = array(0=>'-- Any --','Y'=>'Y',"N"=>'N');
					echo generateselectbox('discounten',$disc_type,$_REQUEST['discounten']);
								  </td>
                </tr>*/ ?>
                <tr>
                  <td height="30">&nbsp;</td>
                  <td height="30" align="left">In Preorder?</td>
                  <td height="30" colspan="3" align="left">
				  	<?php
					$pre_order = array(0=>'-- Any --','Y'=>'Y',"N"=>'N');
					echo generateselectbox('preorder',$pre_order,$_REQUEST['preorder']);
					?>					</td>
                </tr>
                <tr>
                  <td height="30">&nbsp;</td>
                  <td height="30" align="left">Hidden?</td>
                  <td height="30" colspan="3" align="left">
				  <?php
					$hidden_arr = array(0=>'-- Any --','Y'=>'Yes',"N"=>'No');
					echo generateselectbox('prodhidden',$hidden_arr,$_REQUEST['prodhidden']);
					?>				  </td>
                </tr>
              </table></td>
            </tr>
          </table>            </td>
          </tr>
      </table>
		</div></td>
    </tr>
    
    <tr>
      <td colspan="2" class="listingarea">
	  <div class="listingarea_div">
      <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="listingtable"><tr>
      <td colspan="11" align="right" valign="middle" class="listeditd">      
	  <input name="change_hide" type="button" class="red" id="change_hide" onClick="call_save_selected()" value="Assign Selected">
	  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_COMBO_SEL_PROD_ASSIGN')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
    </tr>
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
				  <td align="left" valign="middle" class="<?php echo $cls?>" width="20%"><a href="home.php?request=products&fpurpose=edit&checkbox[0]=<?php echo $row_qry['product_id']?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>" title="edit" class="edittextlink"><?php echo stripslashes($row_qry['product_name'])?></a></td>
				  <td align="left" valign="middle" class="<?php echo $cls?>"><?php echo stripslashes($row_qry['manufacture_id'])?></td>	
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
						
					 ?>				  </td>	
				  <td align="left" valign="middle" class="<?php echo $cls?>"><?php echo display_price($row_qry['product_webprice'])?></td>
				  <td align="left" valign="middle" class="<?php echo $cls?>"><?php echo display_price($row_qry['product_costprice'])?></td>
				  <td align="center" valign="middle" class="<?php echo $cls?>"><?php echo $row_qry['product_bulkdiscount_allowed']?></td>	
				  <td align="center" valign="middle" class="<?php echo $cls?>"><?php echo $disc?></td>
				  <td align="left" valign="middle" class="<?php echo $cls?>"><?php 
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
				  $actual_stock	= $row_qry['product_actualstock'];
				  echo $web_stock."(".$actual_stock.")" ; ?></td>
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
	?>
	
      </table>
	  </div></td>
    </tr>
	<?php 
		if ($db->num_rows($ret_qry))
		{
	?>	
	<tr>
      <td colspan="11" align="right" class="listing_bottom_paging"> <?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?></td>
    </tr>
	<?php
		}
	?>
    </table>
</form>
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