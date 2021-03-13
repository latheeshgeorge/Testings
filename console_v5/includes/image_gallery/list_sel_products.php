<?php
	/*#################################################################
	# Script Name 	: list_sel_products.php
	# Description 	: Page for listing products under a selected category
	# Coded by 		: Sny
	# Created on	: 17-July-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/

if($_REQUEST['sel_catprods'])
{
	$selprod_arr = explode("~",$_REQUEST['sel_catprods']);
}
else
	$selprod_arr = array(0);
// Get the name of current category
$sql_cat = "SELECT category_name FROM product_categories WHERE category_id=".$sel_category;
$ret_cat = $db->query($sql_cat);
if ($db->num_rows($ret_cat))
{
	$row_cat = $db->fetch_array($ret_cat);
	$showcatname = stripslashes($row_cat['category_name']);
}
//Define constants for this page
$table_name='products';
$page_type='Products';
//$help_msg = 'This section lists the Products under "<strong>'.$showcatname.'</strong>", to which the selected images can be assigned . Mark the required products and press the Done button.';
$help_msg = get_help_messages('LIST_SEL_PRODUCTS_IMG_GALLERY_MESS1');
$help_msg = str_replace('[selcat]',$showcatname,$help_msg);
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all_sel(document.frm_products_sel,\'checkbox_link[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none_sel(document.frm_products_sel,\'checkbox_link[]\')"/>','Slno.','Product','Man ID','Category','Retail','Cost','Bulk Disc','Disc','Stock(All)','Hide');
$header_positions=array('center','left','left','left','left','left','left','center','center','left','center');
$colspan = count($table_headers);

//#Search terms
$search_fields = array('productname','manufactureid','categoryid','vendorid','rprice_from','rprice_to','cprice_from','cprice_to','discount','discountas','bulkdiscount','stockatleast','preorder','prodhidden');

$query_string = "request=products&fpurpose=add_prodlink&prod_dontsave=1&checkbox[0]=".$_REQUEST['checkbox'][0];
foreach($search_fields as $v) {
	$query_string .= "&$v=".$_REQUEST[$v]."";//#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by_link = (!$_REQUEST['sort_by_link'])?'product_name':$_REQUEST['sort_by_link'];
$sort_order_link = (!$_REQUEST['sort_order_link'])?'ASC':$_REQUEST['sort_order_link'];
$sort_options = array('product_name' => 'Name','manufacture_id'=>'Manufacture Id','product_costprice'=>'Cost Price','product_webprice'=>'Web Price');
$sort_option_txt = generateselectbox('sort_by_link',$sort_options,$sort_by_link);
$sort_by_txt= generateselectbox('sort_order_link',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order_link);

$where_conditions = "WHERE sites_site_id=$ecom_siteid ";

$ext_prods = array();
$ext_prods[] = 0;
// find the products ids under this category from product_category_map table
$sql_cats = "SELECT products_product_id FROM product_category_map WHERE product_categories_category_id=$sel_category";
$ret_cats = $db->query($sql_cats);
if ($db->num_rows($ret_cats))
{
	while ($row_cats = $db->fetch_array($ret_cats))
	{
		$ext_prods[] = $row_cats['products_product_id'];
	}
}	
$extprod_str = implode(",",$ext_prods);
$where_conditions .= " AND product_id IN ($extprod_str) ";
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

//#Select condition for getting total count
$sql_count = "SELECT count(*) as cnt FROM $table_name  $where_conditions";
$res_count = $db->query($sql_count);
list($numcount) = $db->fetch_array($res_count);#Getting total count of records
/////////////////////////////////For paging///////////////////////////////////////////
$records_per_page = 1;//(is_numeric($_REQUEST['records_per_page']) and $_REQUEST['records_per_page'])?$_REQUEST['records_per_page']:15;#Total records shown in a page
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

$sql_qry = "SELECT * FROM products	$where_conditions ORDER BY $sort_by_link $sort_order_link LIMIT $start,$records_per_page ";
$ret_qry = $db->query($sql_qry);
?>
<script type="text/javascript">
function val_form()
{
	var atleast = false;
	for(i=0;i<document.frm_products_sel.elements.length;i++)
	{
		if (document.frm_products_sel.elements[i].type =='checkbox' && document.frm_products_sel.elements[i].name=='checkbox_link[]')
		{
			if(document.frm_products_sel.elements[i].checked)
				atleast = true;
		}
	}
	if (atleast==false)
	{
		alert('Select the product(s)');
		return false;
	}
	else
	{
		document.frm_products_sel.fpurpose.value='save_sel_prod_for_cat';
		document.frm_products_sel.submit();
	}	
}
function handle_prodsel_normal(obj,id)
{
	var ret_str	= '';
	var new_str = ''
	if(obj.checked==true)
	{
		/* Case if current image is to be added to the selected list*/
		if(document.getElementById('sel_catprods').value!='')
			document.getElementById('sel_catprods').value += '~';
		document.getElementById('sel_catprods').value += id;
	}	
	else
	{
		id_arr	= document.getElementById('sel_catprods').value.split('~');
		if (id_arr.length==1)
			new_str = '';
		else
		{
			for(i=0;i<id_arr.length;i++)
			{
				if (id_arr[i] != id)
				{
					if (new_str!='')
						new_str +='~';
					new_str += id_arr[i];	
				}	
			}
		}
		document.getElementById('sel_catprods').value = new_str;
	}	
}
function handle_prodsel_bulk(obj,id)
{
	var ret_str	= '';
	var new_str = ''
	var exists = false;
	if(obj.checked==true)
	{
		if (document.getElementById('sel_catprods').value=='')
		{
			
			/* Case if current image is to be added to the selected list*/
			if(document.getElementById('sel_catprods').value!='')
				document.getElementById('sel_catprods').value += '~';
			document.getElementById('sel_catprods').value += id;
		}
		else
		{
			/* Check whether id already exists in the string*/
			id_arr	= document.getElementById('sel_catprods').value.split('~');
			for(i=0;i<id_arr.length;i++)
			{
				if (id_arr[i] == id)
				{
					exists = true;
					break;
				}	
			}
			if(exists==false)
			{
				if(document.getElementById('sel_catprods').value!='')
					document.getElementById('sel_catprods').value += '~';
				document.getElementById('sel_catprods').value += id;
			}	
		}	
	}	
	else
	{
		id_arr	= document.getElementById('sel_catprods').value.split('~');
		if (id_arr.length==1)
			new_str = '';
		else
		{
			for(i=0;i<id_arr.length;i++)
			{
				if (id_arr[i] != id)
				{
					if (new_str!='')
						new_str +='~';
					new_str += id_arr[i];	
				}	
			}
		}
		document.getElementById('sel_catprods').value = new_str;
	}	
}
function select_all_sel(frm,obj)
{
	var atleastone = false;
	var len  = frm.elements.length;
	for (i=0;i<len;i++)
	{
		
		if (frm.elements[i].type== "checkbox" && frm.elements[i].name ==obj) 
		{
			if (frm.elements[i].checked==false)
			{
				frm.elements[i].checked = true;
				handle_prodsel_bulk(frm.elements[i],frm.elements[i].value)
			}	
		}
	}
}
function select_none_sel(frm,obj)
{
	var atleastone = false;
	var len  = frm.elements.length;
	for (i=0;i<len;i++)
	{
		
		if (frm.elements[i].type== "checkbox" && frm.elements[i].name ==obj) 
		{
			frm.elements[i].checked=false;
			handle_prodsel_bulk(frm.elements[i],frm.elements[i].value)
		}
	}
}
function handle_prodsel_bulk(obj,id)
{
	var ret_str	= '';
	var new_str = ''
	
	if(obj.checked==true)
	{
		/* Case if current image is to be added to the selected list*/
		if(document.getElementById('sel_catprods').value!='')
			document.getElementById('sel_catprods').value += '~';
		document.getElementById('sel_catprods').value += id;
	}	
	else
	{
		id_arr	= document.getElementById('sel_catprods').value.split('~');
		for(ii=0;ii<id_arr.length;ii++)
		{
			if (id_arr[ii] != id)
			{
				if (new_str!='')
					new_str +='~';
				new_str += id_arr[ii];	
			}	
		}
		document.getElementById('sel_catprods').value = new_str;
	}	
}
function onchange_page(pg)
{
	document.getElementById('pg').value = pg;
	document.frm_products_sel.submit();
}
</script>
<form method="post" name="frm_products_sel" class="frmcls" action="home.php">
<input type="hidden" name="request" value="img_gal" />
<input type="hidden" name="fpurpose" value="sel_prod_for_cat" />
<input type="hidden" name="search_click" value="" />
<input type="hidden" name="curdir_id" id="curdir_id" value="<?=$_REQUEST['curdir_id']?>" />
<input type="hidden" name="src_caption" id="src_caption" value="<?=$_REQUEST['src_caption']?>" />
<input type="hidden" name="src_option" id="src_option" value="<?=$_REQUEST['src_option']?>" />
<input type="hidden" name="recs" id="recs" value="<?=$_REQUEST['recs']?>" />
<input type="hidden" name="pgs" id="pgs" value="<?=$_REQUEST['pgs']?>" />
<input type="hidden" name="sel_prods" id="sel_prods" value="<?=$_REQUEST['sel_prods']?>" />
<input type="hidden" name="sel_catprods" id="sel_catprods" value="<?=$_REQUEST['sel_catprods']?>" />
<input type="hidden" name="sel_category" id="sel_category" value="<?=$_REQUEST['sel_category']?>" />
<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
<input type="hidden" name="retsrc" id="retsrc" value="<?=$_REQUEST['retsrc']?>" />
<input type="hidden" name="curtab" id="curtab" value="show_imageedit_operation" />

<table border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div">
	  <a href="home.php?request=img_gal&txt_searchcaption=<?php echo $_REQUEST['src_caption']?>&search_option=<?php echo $_REQUEST['src_option']?>&records_per_page=<?php echo $_REQUEST['recs']?>&pg=<?php echo $_REQUEST['pgs']?>&curdir_id=<?php echo $_REQUEST['curdir_id']?>&sel_prods=<?php echo $_REQUEST['sel_prods']?>">Image Gallery</a> <span> Assign Selected Images to  Products under Category &quot;<?php echo $showcatname?>&quot;</span></div>	 </td>
    </tr>
	<tr>
	  <td colspan="2" align="left" valign="middle" class="helpmsgtd_main">
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
          <td width="66%" align="left" valign="top">
		  <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td width="27%" align="left">Product Name</td>
              <td width="34%" align="left"><input name="productname_link" type="text" class="textfeild" id="productname_link" value="<?php echo $_REQUEST['productname_link']?>" /></td>
              <td width="14%" align="left">Vendor</td>
              <td width="25%" align="left"><?php
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
					echo generateselectbox('vendorid',$vendor_arr,0);
				}
			  ?></td>
            </tr>
            <tr>
              <td align="left">Product Id </td>
              <td colspan="3" align="left"><input name="manufactureid_link" type="text" class="textfeild" id="manufactureid_link" value="<?php echo $_REQUEST['manufactureid_link']?>" /></td>
              </tr>
          </table>            </td>
          <td width="34%" align="left" valign="top">
		  <table width="100%" height="56" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td align="left">
                <input name="records_per_page" type="hidden" class="textfeild" id="records_per_page" size="3" maxlength="3" value="<?php echo $records_per_page?>" />
                <?php //echo $page_type?>
                Sort By&nbsp;<?php echo $sort_option_txt;?> in <?php echo $sort_by_txt?> </td>
              <td width="23%" align="left">&nbsp;</td>
            </tr>
            <tr>
              <td align="left">&nbsp;</td>
              <td align="left"><input name="Search_go" type="submit" class="red" id="Search_go" value="Go" onclick="document.frm_products_sel.search_click.value=1" />
                <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_SEL_PRODUCTS_IMG_GALLERY_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
            </tr>
          </table>		  </td>
        </tr>
      </table> 
	  	</div>
	       </td>
    </tr>
    <tr>
      <td colspan="2" class="listingarea">
	  <div class="listingarea_div">
      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable">
	  <tr>
      <td colspan="9" align="center" class="listeditd">
	  Page 
      <?php
		if($pages>1)
		{
			for($i=1;$i<=$pages;$i++)
			{
				$option_values[$i] = ($i); 
			}
			echo generateselectbox('pgs',$option_values,$pg,'',$onchange='onchange_page(this.value)');
		}
		else
			echo "1";		
	  ?>
	of <?php echo $pages?>						  </td>
      <td colspan="2" align="right" class="listeditd">
        <input name="Submit_Done" type="button" class="red" id="Submit_Done" value="Assign" onclick="val_form()" />
     <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_SEL_PRODUCTS_IMG_GALLERY_DONE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
	 </td>
    </tr>
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
				  <input type="checkbox" name="checkbox_link[]" id="checkbox_link[]" value="<?php echo $row_qry['product_id']?>" onclick="handle_prodsel_normal(this,'<?php echo $row_qry['product_id']?>')" <?php if(in_array($row_qry['product_id'],$selprod_arr)) echo 'checked="checked"';?>/></td>
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
				  	No Products found in current category.				  </td>
			</tr>	  
	<?php
		}
	?>
		<tr>
      <td align="center" valign="middle" class="listeditd" colspan="9">Page
        <?php
		if($pages>1)
		{
			for($i=1;$i<=$pages;$i++)
			{
				$option_values[$i] = ($i); 
			}
			echo generateselectbox('pgs',$option_values,$pg,'',$onchange='onchange_page(this.value)');
		}
		else
			echo "1";
?>
of <?php echo $pages?> </td>
      <td align="center" class="listeditd" colspan="2">&nbsp;</td>
	</tr>
      </table>
	  </div>
	  </td>
    </tr>
	
    </table>
</form>