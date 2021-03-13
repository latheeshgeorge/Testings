<?php
	/*#################################################################
	# Script Name 	:apply_settings_many.php
	# Description 	: Page for appliying settings like discount,tax,show cart link,show enquiry link for multiple products in single step
	# Coded by 		: Anu
	# Created on	: 18-Apr-2008
	# Modified by	: Anu
	# Modified On	: 18-Mar-2008

	#################################################################*/

	//Define constants for this page
	$page_type 	= 'Products';
	$help_msg 	= get_help_messages('SPECIAL_PROD_CODE');
	
	$gen_arr 	= get_general_settings('product_maintainstock,epos_available','general_settings_sites_common');
	$curtab		= ($_REQUEST['curtab'])?$_REQUEST['curtab']:'main_tab_td';
	global $ecom_site_mobile_api;
?>	
<script language="javascript" type="text/javascript">
	function go_download_code()
	{
		if(confirm('Are you sure you wanted to download the file?'))
		{
			document.frm_specialproductcode.cur_mod.value = 'do_download';
			document.frm_specialproductcode.submit();
		}	
		
	}
	function go_upload_code()
	{
		if(document.frm_specialproductcode.upload_file.value=='')
		{
			alert ('Please select the file');
		}
		else
		{
			if(confirm('Are you sure you wanted to upload the file?'))
			{
				document.frm_specialproductcode.cur_mod.value = 'specialcode_upload';
				document.frm_specialproductcode.submit();
			}	
		}	
		
	}
</script>
<form action='do_specialproductcode.php' method="post" enctype="multipart/form-data" name='frm_specialproductcode'>
<input type="hidden" name="cur_mod" id="cur_mod" value="" />
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=products&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&start=<?php echo $_REQUEST['start']?>&pg=<?php echo $_REQUEST['pg']?>&records_per_page=<?php echo $_REQUEST['records_per_page']?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>">List Products</a> <span> Manage Special Product Code </span></div></td>
</tr>
<tr>
	<td align="left" valign="middle" class="helpmsgtd_main">
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
		<td colspan="2" align="center" valign="middle" class="errormsg"><?php echo($alert);?></td>
	</tr>
<?php
}
?> 
  <tr>
	<td align="left" class="seperationtd"></td>
  </tr>
  
<tr>
<td align="left" valign="middle" class="tdcolorgray" ><table width="100%" border="0" cellspacing="1" cellpadding="1">
<tr>
  <td width="50%" class="listingtableheader">Download</td>
  <td width="50%" class="listingtableheader">Upload</td>
</tr>
<tr>
  <td class="tdcolorgray"><?php echo get_help_messages('SPECIAL_PROD_CODE_DOWNLOAD')?></td>
  <td class="tdcolorgray"><?php echo get_help_messages('SPECIAL_PROD_CODE_UPLOAD')?></td>
</tr>
<tr>
	<td align="center"><input name="click_to_download" type="button" id="click_to_download" value="Click to Download" class="red" onclick="go_download_code()" /></td>
	<td>
		<table width="100%" border="0" cellspacing="1" cellpadding="1">
		<tr>
			<td width="41%" class="tdcolorgray"><strong>Upload your file</strong> </td>
			<td width="2%">:</td>
			<td width="57%"><input name="upload_file" type="file" id="upload_file" /></td>
		</tr>
		<tr>
			<td colspan="3" align="center"><input name="click_to_upload" type="button" id="click_to_upload" value="Upload" class="red" onclick="go_upload_code()" /></td>
		</tr>
		</table>
	</td>
</tr>
</table></td>
</tr>
<tr>
	<td colspan="3" align="left" valign="bottom" class="tdcolorgray">&nbsp;</td>
</tr>
</table>
</form>	 
<?php
if($_REQUEST['show']=='duplicate')
{
	$table_name='products';
$page_type='Products';
$help_msg = get_help_messages('LIST_PRODUCTS1');
$table_headers = array('Slno.','Special Product Code','Product Nmae','Hidden?');

$header_positions=array('left','left','left','left');

$colspan = count($table_headers);

//#Search terms
$search_fields = array('productname','categoryid','prodhidden','sort_by','sort_order');

$query_string = "request=products&fpurpose=manage_specialprodcode&show=duplicate";
$option_search_style_display = 'style="display:"';

foreach($search_fields as $v) {

	$query_string .= "&$v=".$_REQUEST[$v]."";//#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('code' => 'Code');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";



$sku_prod_str = "-1";
$sku_b_prod = array();

	//Getting the products with code blank
 $sql_prod_specialA = "(SELECT product_special_product_code as code,count(product_special_product_code) as cnt,product_id FROM products  WHERE sites_site_id=$ecom_siteid AND 
	product_variables_exists='N' AND product_special_product_code!=' ' GROUP BY code HAVING cnt>1) 
	UNION  (SELECT b.comb_special_product_code as code,count(b.comb_special_product_code) as cnt,b.products_product_id as product_id FROM product_variable_combination_stock 
	b LEFT JOIN products c ON (c.product_id=b.products_product_id) WHERE c.sites_site_id=$ecom_siteid AND b.comb_special_product_code!=' ' GROUP BY code HAVING cnt>1 
	)";
 $ret_prod_specialA = $db->query($sql_prod_specialA);
	
	

//#Select condition for getting total count
$sql_count = "SELECT count(*) FROM(".$sql_prod_specialA.") as cnt ORDER BY code";
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
$sql_prod_special = "SELECT * FROM(".$sql_prod_specialA.")as ab ORDER BY code $sort_order LIMIT $start,$records_per_page ";
$ret_prod_special = $db->query($sql_prod_special);
/////////////////////////////////////////////////////////////////////////////////////
	
	?>
<form method="post" name="frm_products" class="frmcls" action="home.php">
<input type="hidden" name="request" value="products" />
<input type="hidden" name="fpurpose" value="manage_specialprodcode" />
<input type="hidden" name="show" value="duplicate" />

<input type="hidden" name="replicate_product_id" value="" />
<input type="hidden" name="export_what" value="" />
<input type="hidden" name="ids" value="" />
<input type="hidden" name="search_click" value="" />

<table border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td colspan="4" align="left" valign="middle" class="treemenutd"><?php echo get_help_messages('LIST_PRODUCTS_SPECIALCODE_DUPLICATE')?></td>
    </tr>
	<tr>
      <td colspan="4" align="left" valign="middle" class="helpmsgtd"><?php echo get_help_messages('MESS_SPECIALCODE_DUPLICATE1')?></td>
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
    <tr>
      <td height="48" colspan="4" class="sorttd">

		<table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="100%" align="left" valign="top" colspan="4">
		  		<div class="editarea_div">

		  <table width="100%" border="0" cellspacing="1" cellpadding="1">
           
           
            <tr id="listmore_tr" >
              <td >
              		  &nbsp; </td>
          <td width="40%" align="left" valign="top">
		  <table width="100%" height="50" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td align="left">Show
                <input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3" value="<?php echo $records_per_page?>" />
                <?php echo $page_type?> Per Page</td>
            
              <td align="left">Sort By&nbsp;<?php echo $sort_option_txt;?> in <?php echo $sort_by_txt?> </td>
              <td align="left"><input name="Search_go" type="submit" class="red" id="Search_go" value="Go" onclick="document.frm_products.search_click.value=1" />
                <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.gif" width="17" height="13" border="0" /></a></td>
            </tr>
          </table>	</div>	  </td>
        </tr>
      </table>      </td>
    </tr>
	
  
	 <tr>
      <td colspan="4" class="listingarea">
		  	  <div class="listingarea_div">

      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable">
	 <?php 
		if ($db->num_rows($ret_prod_special))
		{
		?>
	<tr>
		<td  align="left" class="listeditd" style="width:20%;" colspan="2">
	<a href="home.php?request=products&fpurpose=manage_specialprodcode" class="specialcodelist">Code Search</a>
	 </td>
	 <td  align="center" class="listeditd" colspan="2">
        <?
			paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan);
		?>		</td>
		</tr>
		<?
		  }
		?>
	 <?php  
	 	echo table_header($table_headers,$header_positions); 
		if ($db->num_rows($ret_prod_special))
		{ 
			$srno = getStartOfPageno($records_per_page,$pg);
			while($row_prod_special=$db->fetch_array($ret_prod_special))
				{
					$code = $row_prod_special['code'];
					$cls 		= ($srno%2==0)?'listingtablestyleA':'listingtablestyleB';
				
	 ?>
			   	<tr>
				 
				  <td align="left" valign="middle" class="<?php echo $cls?>" width="1%"><?php echo $srno++?></td>
				  <td align="left" valign="middle" class="<?php echo $cls?>" width="20%"><?php echo stripslashes($code)?></td>
				  <td colspan="2" class="<?php echo $cls?>">&nbsp;</td> 
				</tr>
				<?php
				/*$sql_prod_code = "(SELECT product_id,product_name,product_hide FROM products  WHERE sites_site_id=$ecom_siteid AND 
	product_variables_exists='N' AND product_special_product_code='$code') 
	UNION  (SELECT b.products_product_id as product_id,c.product_name,c.product_hide FROM product_variable_combination_stock 
	b LEFT JOIN products c ON (c.product_id=b.products_product_id) WHERE c.sites_site_id=113 AND b.comb_special_product_code='$code' )";
	*/
	$sql_prod_code = "SELECT DISTINCT product_id, a.product_name, a.product_hide
FROM products a
LEFT JOIN product_variable_combination_stock b ON ( a.product_id = b.products_product_id )
WHERE CASE WHEN a.product_variables_exists = 'Y'
THEN b.comb_special_product_code = '$code'
WHEN a.product_variables_exists = 'N'
THEN a.product_special_product_code = '$code'
END AND a.sites_site_id =$ecom_siteid"; 
				$ret_prod_code = $db->query($sql_prod_code);
				while($row_prod_code=$db->fetch_array($ret_prod_code))
				{
				?>
					<tr>
						<td colspan="2" class="<?php echo $cls?>">&nbsp;</td><td align="left" valign="middle"  class="<?php echo $cls?>" ><a href="home.php?request=products&fpurpose=edit&checkbox[0]=<?php echo $row_prod_code['product_id']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&curtab=stock_tab_td" title="edit" class="edittextlink"><?php echo stripslashes($row_prod_code['product_name'])?></a></td><td align="left" class="<?php echo $cls?>"><?php echo $row_prod_code['product_hide']?></td>

					</tr>
					<?php
				}
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
      </table></div></td>
    </tr>
	
	<?php 
		if ($db->num_rows($ret_prod_special))
		{
		?>
		<tr>
		<td  align="left" class="listeditd" colspan="2" style="width:20%">&nbsp;
		</td>
		<td colspan="2" align="center" class="listeditd">
		<?
			paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan);
		?>		</td>
		</tr>
		<?
		}
		?>	 
    </table>
</form>
	<?php
}
else
{
$table_name='products';
$page_type='Products';
$help_msg = get_help_messages('LIST_PRODUCTS1');
$table_headers = array('Slno.','Product','Category','Hidden?');

$header_positions=array('left','left','left','center');

$colspan = count($table_headers);

//#Search terms
$search_fields = array('productname','categoryid','prodhidden','sort_by','sort_order');

$query_string = "request=products&fpurpose=manage_specialprodcode";
$option_search_style_display = 'style="display:"';

foreach($search_fields as $v) {

	$query_string .= "&$v=".$_REQUEST[$v]."";//#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'product_name':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('product_name' => 'Name');
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
// Hidden
$hide 		= trim($_REQUEST['prodhidden']);
if($hide)
{
	$where_conditions .= " AND ( product_hide ='".$hide."') ";
}
$sku_prod_str = "-1";
$sku_b_prod = array();

if($_REQUEST['skucode']=='')
{
	//Getting the products with code blank
	//$sql_prod_special = "(SELECT product_id FROM products  WHERE sites_site_id=$ecom_siteid AND product_variables_exists='N' AND  	product_special_product_code=' ') UNION  (SELECT b.products_product_id as product_id FROM product_variable_combination_stock b LEFT JOIN products c ON (c.product_id=b.products_product_id) WHERE c.sites_site_id=113 AND b.comb_special_product_code=' ' )";
	$sql_prod_special ="SELECT DISTINCT product_id, a.product_name, a.product_variables_exists
FROM products a
LEFT JOIN product_variable_combination_stock b ON ( a.product_id = b.products_product_id )
WHERE CASE WHEN a.product_variables_exists = 'Y'
THEN b.comb_special_product_code = ''
WHEN a.product_variables_exists = 'N'
THEN a.product_special_product_code = ''
END AND a.sites_site_id =$ecom_siteid";
	$ret_prod_special = $db->query($sql_prod_special);
	while($row_prod_special=$db->fetch_array($ret_prod_special))
	{
		$sku_b_prod[]=$row_prod_special['product_id'];	
	}
	if(count($sku_b_prod))
	{
		$sku_prod_str = implode(",",$sku_b_prod);
	}
		$where_conditions .= " AND ( product_id IN ($sku_prod_str)) ";

}
else
{
	//Getting the products with code blank
	//$sql_prod_special = "(SELECT product_id FROM products  WHERE sites_site_id=$ecom_siteid AND product_variables_exists='N' AND  	product_special_product_code='".$_REQUEST['skucode']."') UNION  (SELECT b.products_product_id as product_id FROM product_variable_combination_stock b LEFT JOIN products c ON (c.product_id=b.products_product_id) WHERE c.sites_site_id=113 AND b.comb_special_product_code='".$_REQUEST['skucode']."' )";
	$sql_prod_special ="SELECT DISTINCT product_id, a.product_name, a.product_variables_exists
FROM products a
LEFT JOIN product_variable_combination_stock b ON ( a.product_id = b.products_product_id )
WHERE CASE WHEN a.product_variables_exists = 'Y'
THEN b.comb_special_product_code = '".$_REQUEST['skucode']."'
WHEN a.product_variables_exists = 'N'
THEN a.product_special_product_code = '".$_REQUEST['skucode']."'
END AND a.sites_site_id =$ecom_siteid";
	$ret_prod_special = $db->query($sql_prod_special);
	while($row_prod_special=$db->fetch_array($ret_prod_special))
	{
		$sku_b_prod[]=$row_prod_special['product_id'];	
	}
	if(count($sku_b_prod))
	{
		$sku_prod_str = implode(",",$sku_b_prod);
	}
		$where_conditions .= " AND ( product_id IN ($sku_prod_str)) ";
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
<form method="post" name="frm_products" class="frmcls" action="home.php">
<input type="hidden" name="request" value="products" />
<input type="hidden" name="fpurpose" value="manage_specialprodcode" />
<input type="hidden" name="replicate_product_id" value="" />
<input type="hidden" name="export_what" value="" />
<input type="hidden" name="ids" value="" />
<input type="hidden" name="search_click" value="" />

<table border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td colspan="4" align="left" valign="middle" class="treemenutd"><?php echo get_help_messages('LIST_PRODUCTS_SPECIALCODE')?></td>
    </tr>
	<tr>
      <td colspan="4" align="left" valign="middle" class="helpmsgtd"><?php echo get_help_messages('MESS_SPECIALCODE_DUPLICATE')?></td>
    </tr>
	<?php echo get_help_messages('')?>
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
    <tr>
      <td height="48" colspan="4" class="sorttd">

		<table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="100%" align="left" valign="top" colspan="4">
<div class="editarea_div">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
          <td align="left" valign="top">
		  <table width="100%" border="0" cellspacing="1" cellpadding="1">
           
           
            <tr id="listmore_tr" >
              <td colspan="4">
              		  <table width="100%" height="50" border="0" cellpadding="0" cellspacing="0">
              		   <tr>
              <td width="15%" align="left">Special Product Code</td>
              <td  align="left" width="35%"><input name="skucode" type="text" class="textfeild" id="skucode" value="<?php echo stripslashes($_REQUEST['skucode'])?>" /></td>
               <td width="12%" align="left">Product Name</td>
              <td width="20%" align="left"><input name="productname" type="text" class="textfeild" id="productname" value="<?php echo stripslashes($_REQUEST['productname'])?>" /></td>

            </tr>
 <tr>
                           <td width="10%" align="left">Category</td>
              <td align="left" colspan="3">
			  <?php
			  	$cat_arr = generate_category_tree(0,0,true);
				if(is_array($cat_arr))
				{
					echo generateselectbox('categoryid',$cat_arr,$_REQUEST['categoryid']);
				}
			  ?>			  </td>
            </tr>
            
</table>                      </td>
          <td width="34%" align="left" valign="top">
		  <table width="100%" height="50" border="0" cellpadding="0" cellspacing="0">
           <tr>
              <td align="left">Show
                <input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3" value="<?php echo $records_per_page?>" />
                <?php echo $page_type?> Per Page</td>
              <td width="23%" align="left">&nbsp;</td>
            </tr>
            <tr>
              <td align="left">Sort By&nbsp;<?php echo $sort_option_txt;?> in <?php echo $sort_by_txt?> </td>
              <td align="left"><input name="Search_go" type="submit" class="red" id="Search_go" value="Go" onclick="document.frm_products.search_click.value=1" />
                <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.gif" width="17" height="13" border="0" /></a></td>
            </tr>
          </table>		  </td>
        </tr>
      </table>     </td>
      </tr>
      </table>
      </div>
      </td>
    </tr>
	
  
	 <tr>
      <td colspan="4" class="listingarea">
		  	  <div class="listingarea_div">

      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable">
		  <?php 
		if ($db->num_rows($ret_qry))
		{
		?>
	<tr>
		<td  align="left" class="listeditd" colspan="2" style="width:20%">
	<a href="home.php?request=products&fpurpose=manage_specialprodcode&show=duplicate" class="specialcodelist">Duplicate Codes</a>
	 </td>
	 <td  align="center" class="listing_bottom_paging" colspan="2">
        <?
			paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan);
		?>		</td>
		</tr>
		<?
		  }
		?>
	 <?php  
	 	echo table_header($table_headers,$header_positions); 
		if ($db->num_rows($ret_qry))
		{ 
			$srno = getStartOfPageno($records_per_page,$pg);
			while ($row_qry = $db->fetch_array($ret_qry))
			{
				$cls 		= ($srno%2==0)?'listingtablestyleA':'listingtablestyleB';
				
	 ?>
			   	<tr>
				 
				  <td align="left" valign="middle" class="<?php echo $cls?>" width="1%"><?php echo $srno++?></td>
				  <td align="left" valign="middle" class="<?php echo $cls?>" ><a href="home.php?request=products&fpurpose=edit&checkbox[0]=<?php echo $row_qry['product_id']?>&curtab=stock_tab_td" title="edit" class="edittextlink"><?php echo stripslashes($row_qry['product_name'])?></a></td>
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
      </table></div></td>
    </tr>
	
	<?php 
		if ($db->num_rows($ret_qry))
		{
		?>
		<tr>
			<tr>
		<td  align="left" class="listing_bottom_paging" colspan="2" style="width:20%">&nbsp;
		</td>
		<td colspan="2" align="center" class="listing_bottom_paging">
		<?
			paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan);
		?>		</td>
		</tr>
		<?
		}
		?>	 
    </table>
</form>
<?php
}
?>
