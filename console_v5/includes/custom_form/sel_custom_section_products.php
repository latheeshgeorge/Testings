<?php
	/*#################################################################
	# Script Name 	: sel_custom_section_products.php
	# Description 	: Page for selecting products to which the current dynamic section to be linked
	# Coded by 		: Sny
	# Created on	: 07-Nov-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/

// Get the section name
$sql_sec = "SELECT section_name FROM element_sections WHERE section_id=".$_REQUEST['pass_editid'];
$ret_sec = $db->query($sql_sec);
if ($db->num_rows($ret_sec))
{
	$row_sec	 	= $db->fetch_array($ret_sec);
	$showsection 	= stripslashes($row_sec['section_name']);
}

$ext_arr[0] = 0;
// Get the list of products which are already linked with the current dynamic section
$sql_linkexist = "SELECT products_product_id FROM element_section_products WHERE element_sections_section_id=".$_REQUEST['pass_editid'];
$ret_linkexist = $db->query($sql_linkexist);
if ($db->num_rows($ret_linkexist))
{
	while ($row_linkexist = $db->fetch_array($ret_linkexist))
	{
		$ext_arr[] = $row_linkexist['products_product_id'];
	}
}
//Define constants for this page
$table_name='products';
$page_type='Products';
$help_msg = get_help_messages('LIST_PROD_ASS_ENQUIRE_MESS1');
$help_msg = str_replace("[formtype]",$showsection,$help_msg);
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frm_products_link,\'checkbox_link[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frm_products_link,\'checkbox_link[]\')"/>','Slno.','Product','Man ID','Category','Retail','Cost','Bulk Disc','Disc','Stock(All)','Hide');
$header_positions=array('center','left','left','left','left','left','left','center','center','left','center');
$colspan = count($table_headers);

//#Search terms
$search_fields = array('productname_link','manufactureid_link','categoryid_link','vendorid_link','rprice_from','rprice_to','cprice_from','cprice_to','discount','discountas','bulkdiscount','stockatleast','preorder','prodhidden','sort_by_link','sort_order_link');

$query_string = "request=customform&fpurpose=assign_secprod&pass_editid=".$_REQUEST['pass_editid']."";
foreach($search_fields as $v) {
	$query_string .= "&$v=".$_REQUEST[$v]."";//#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by_link = (!$_REQUEST['sort_by_link'])?'product_name':$_REQUEST['sort_by_link'];
$sort_order_link = (!$_REQUEST['sort_order_link'])?'ASC':$_REQUEST['sort_order_link'];
$sort_options = array('product_name' => 'Name','manufacture_id'=>'Manufacture Id','product_costprice'=>'Cost Price','product_webprice'=>'Web Price');
$sort_option_txt = generateselectbox('sort_by_link',$sort_options,$sort_by_link);
$sort_by_txt= generateselectbox('sort_order_link',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order_link);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid AND product_id NOT IN (".implode(",",$ext_arr).") ";

// Product Name Condition
if($_REQUEST['productname_link'])
{
	$where_conditions .= " AND ( product_name LIKE '%".add_slash($_REQUEST['productname_link'])."%') ";
}
// Manufacturer id Condition
if($_REQUEST['manufactureid_link'])
{
	$where_conditions .= " AND ( manufacture_id LIKE '%".add_slash($_REQUEST['manufactureid_link'])."%') ";
}

// ==================================================================================================
// Case if category or vendor is selected 
// ==================================================================================================
if ($_REQUEST['categoryid_link'] or $_REQUEST['vendorid_link'])
{
 
	$count_check ='Y';
	$catinclude_prod		= array(0);
	$vendinclude_prod		= array(0);
	if($_REQUEST['categoryid_link']) // case if category is selected
	{
		// Get the id's of products under this category
		$sql_catmap = "SELECT products_product_id FROM product_category_map WHERE product_categories_category_id=".$_REQUEST['categoryid_link'];
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
	if($_REQUEST['vendorid_link']) // case if vendor is selected
	{
		
		// Get the id's of products under this vendor
		$sql_vendmap = "SELECT products_product_id FROM product_vendor_map WHERE product_vendors_vendor_id=".$_REQUEST['vendorid_link'];
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
	if($_REQUEST['categoryid_link']) // case if category is selected
	{
		
		// Get the id's of products under this category
		$sql_catmap = "SELECT products_product_id FROM product_category_map WHERE product_categories_category_id=".$_REQUEST['categoryid_link'];
		$ret_catmap = $db->query($sql_catmap);
		if ($db->num_rows($ret_catmap))
		{
			while ($row_catmap = $db->fetch_array($ret_catmap))
			{
				$catinclude_prod[] = $row_catmap['products_product_id'];
			}
		}
	}
	if($_REQUEST['vendorid_link']) // case if vendor is selected
	{
		
		// Get the id's of products under this vendor
		$sql_vendmap = "SELECT products_product_id FROM product_vendor_map WHERE product_vendors_vendor_id=".$_REQUEST['vendorid_link'];
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
// ==================================================================================================

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

$sql_qry = "SELECT * FROM products $where_conditions ORDER BY $sort_by_link $sort_order_link LIMIT $start,$records_per_page ";
$ret_qry = $db->query($sql_qry);
?>
<script type="text/javascript">
function val_form()
{
	var atleast = false;
	for(i=0;i<document.frm_products_link.elements.length;i++)
	{
		if (document.frm_products_link.elements[i].type =='checkbox' && document.frm_products_link.elements[i].name=='checkbox_link[]')
		{
			if(document.frm_products_link.elements[i].checked)
				atleast = true;
		}
	}
	if (atleast==false)
	{
		alert('Select the product(s) to be linked');
		return false;
	}
	else
	{
		document.frm_products_link.fpurpose.value='assig_prodlink';
		document.frm_products_link.submit();
	}	
}
</script>
<form method="post" name="frm_products_link" class="frmcls" action="home.php">
<input type="hidden" name="request" value="customform" />
<input type="hidden" name="form_type" value="<?php echo $_REQUEST['form_type']?>" />
<input type="hidden" name="fpurpose" value="assign_secprod" />
<input type="hidden" name="search_click" value="" />
<input type="hidden" name="pass_search_name" id="pass_search_name" value="<?=$_REQUEST['pass_search_name']?>" />
<input type="hidden" name="pass_sort_by" id="pass_sort_by" value="<?=$_REQUEST['pass_sort_by']?>" />
<input type="hidden" name="pass_sort_order" id="pass_sort_order" value="<?=$_REQUEST['pass_sort_order']?>" />
<input type="hidden" name="pass_records_per_page" id="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
<input type="hidden" name="pass_start" id="pass_start" value="<?=$_REQUEST['pass_start']?>" />
<input type="hidden" name="pass_pg" id="pass_pg" value="<?=$_REQUEST['pass_pg']?>" />
<input type="hidden" name="pass_editid" id="pass_editid" value="<? echo $_REQUEST['pass_editid'];?>" />
<table border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td colspan="2" align="left" valign="middle" class="treemenutd">
	  <div class="treemenutd_div"><a href="home.php?request=customform&form_type=<?php echo $_REQUEST['form_type']?>&search_name=<?php echo $_REQUEST['pass_search_name']?>&start=<?php echo $_REQUEST['pass_start']?>&pg=<?php echo $_REQUEST['pass_pg']?>&records_per_page=<?php echo $_REQUEST['pass_records_per_page']?>&sort_by=<?php echo $_REQUEST['pass_sort_by']?>&sort_order=<?php echo $_REQUEST['pass_sort_order']?>">List Custom Section </a> <a href="home.php?request=customform&fpurpose=edit_section&form_type=<?php echo $_REQUEST['form_type']?>&checkbox[0]=<?php echo $_REQUEST['pass_editid']?>&search_name=<?php echo $_REQUEST['pass_search_name']?>&start=<?php echo $_REQUEST['pass_start']?>&pg=<?php echo $_REQUEST['pass_pg']?>&records_per_page=<?php echo $_REQUEST['pass_records_per_page']?>&sort_by=<?php echo $_REQUEST['pass_sort_by']?>&sort_order=<?php echo $_REQUEST['pass_sort_order']?>">Edit Section</a>  <span> Assign More Products to Custom Section &quot;<?php echo $showsection?>&quot;</span></td>
    </tr>
	<tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="2">
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
      <td height="48" colspan="2" class="sorttd">
<div class="sorttd_div">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="62%" align="left" valign="top">
		  <table width="96%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td width="27%" align="left">Product Name</td>
              <td width="25%" align="left"><input name="productname_link" type="text" class="textfeild" id="productname_link" value="<?php echo $_REQUEST['productname_link']?>" /></td>
              <td width="18%" align="left">Category</td>
              <td width="30%" align="left">
			  <?php
			  	$cat_arr = generate_category_tree(0,0);
				if(is_array($cat_arr))
				{
					echo generateselectbox('categoryid_link',$cat_arr,$_REQUEST['categoryid_link']);
				}
			  ?>			  </td>
            </tr>
            <tr>
              <td align="left">Product Id </td>
              <td align="left"><input name="manufactureid_link" type="text" class="textfeild" id="manufactureid_link" value="<?php echo $_REQUEST['manufactureid_link']?>" /></td>
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
					echo generateselectbox('vendorid_link',$vendor_arr,$_REQUEST['vendorid_link']);
				}
			  ?>			  </td>
            </tr>
          </table>            </td>
          
        </tr>
		<tr>
	<td  align="left" valign="top" class="sorttd" colspan="2">
		  <table width="100%"  border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td align="right">
                Show
                  <input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3" value="<?php echo $records_per_page?>" />
                  <?php echo $page_type?> Sort By&nbsp;<?php echo $sort_option_txt;?> in <?php echo $sort_by_txt?> 
                  <input name="Search_go" type="submit" class="red" id="Search_go" value="Go" onclick="document.frm_products_link.search_click.value=1" />
                  <a href="#" onmouseover ="ddrivetip('<? echo get_help_messages('LIST_PROD_ASS_ENQUIRE_GO') ?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
            </tr>
        </table>		  </td>
	</tr>
      </table>      
</div>	  </td>
    </tr>
	
    <tr>
      <td width="80%" align="center" class="sorttd">
        <?php 
		if ($db->num_rows($ret_qry))
		{
			paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan);
		}
		?></td>
      <td width="30%" align="right" class="sorttd"><span >
        <input name="Assignmore_tab" type="button" class="red" id="Assignmore_tab" value="Assign Selected" onclick="val_form()" />
      </span></td>
    </tr>
    <tr>
      <td colspan="2" class="listingarea">
	  <div class="listingarea_div">
      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable">
	 <?php  
	 	echo table_header($table_headers,$header_positions); 
		if ($db->num_rows($ret_qry))
		{ 
			$srno = 1;
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
				}	
				else
					$disctype = $disc = '--';
	 ?>
			   	<tr>
				  <td align="left" valign="middle" class="<?php echo $cls?>" width="5%">
				  <input type="checkbox" name="checkbox_link[]" id="checkbox_link[]" value="<?php echo $row_qry['product_id']?>" /></td>
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
				  <td align="left" valign="middle" class="<?php echo $cls?>"><?php echo $row_qry['product_webstock']."(".$row_qry['product_actualstock'].")"?></td>
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
				  	No unassigned Products found.				  </td>
			</tr>	  
	<?php
		}
	?>
      </table>
	  </div>
	  </td>
    </tr>
	<tr>
      <td align="center" valign="middle" class="listing_bottom_paging" colspan="2">
	    <?php 
		if ($db->num_rows($ret_qry))
		{
			paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan);
		}
		?></td>
	</tr>
    </table>
</form>