<?php
	/*#################################################################
	# Script Name 	: list_bestseller_selproduct.php
	# Description 	: Page for listing Products to be selected as best seller
	# Coded by 		: Sny
	# Created on	: 21-Nov-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/

$table_name		= 'products';
$page_type		= 'Products';
$help_msg 		= get_help_messages('LIST_BESTSEL_PRO_MESS1');

$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frm_products,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frm_products,\'checkbox[]\')"/>','Slno.','Product','Man ID','Category','Retail','Cost','Bulk Disc','Disc','Stock(All)','Hide');
$header_positions=array('center','left','left','left','left','left','left','center','center','left','center');
$colspan = count($table_headers);

//#Search terms
$search_fields = array('productname','manufactureid','categoryid','vendorid','rprice_from','rprice_to','cprice_from','cprice_to','discount','discountas','bulkdiscount','stockatleast','preorder','prodhidden');

$query_string = "request=general_settings&fpurpose=prodBestsellerAssign";
foreach($search_fields as $v) {
	$query_string .= "&$v=${$v}";//#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'product_name':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('product_name' => 'Name','manufacture_id'=>'Manufacture Id','product_costprice'=>'Cost Price','product_webprice'=>'Web Price');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";

//#Avoiding already assigned product
$sql_assigned="SELECT products_product_id FROM general_settings_site_bestseller WHERE  sites_site_id=$ecom_siteid";
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

// Webprice Condition
$rprice_f = trim($_REQUEST['rprice_from']);
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
$cprice_f = trim($_REQUEST['cprice_from']);
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
	$where_conditions .= " AND ( product_discount_enteredasval=$disc_as AND product_discount>=$discount ) ";
}

// Bulk Discount Condition
$bulk		= trim($_REQUEST['discount']);
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
			document.frm_products.fpurpose.value ='save_prodBestsellerAssign';
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
<input type="hidden" name="request" value="general_settings" />
<input type="hidden" name="fpurpose" value="prodBestsellerAssign" />
<input type="hidden" name="search_click" value="" />
<input type="hidden" name="pass_searchname" value="<?=$_REQUEST['pass_searchname']?>" />
<input type="hidden" name="pass_sort_by" value="<?=$_REQUEST['pass_sortby']?>" />
<input type="hidden" name="pass_sort_order" value="<?=$_REQUEST['pass_sortorder']?>" />
<input type="hidden" name="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
<input type="hidden" name="pass_start" value="<?=$_REQUEST['pass_start']?>" />
<input type="hidden" name="pass_pg" value="<?=$_REQUEST['pass_pg']?>" />
<table border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td colspan="3" align="left" valign="middle" class="treemenutd"><a href="home.php?request=general_settings&fpurpose=settings_default">Main Shop Settings </a> &gt;&gt; Assign Best Seller Products</td>
    </tr>
	<tr>
	  <td colspan="3" align="left" valign="middle" class="helpmsgtd"><?=$help_msg?></td>
	</tr>
	<?php 
			if($alert)
			{			
		?>
        		<tr>
          			<td colspan="3" align="center" valign="middle" class="errormsg"><?php echo($alert);?></td>
          		</tr>
		 <?php
		 	}
		 ?> 
    <tr>
      <td height="48" colspan="3" class="sorttd">

		<table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="66%" align="left" valign="top">
		  <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td width="27%" align="left">Product Name</td>
              <td width="34%" align="left"><input name="productname" type="text" class="textfeild" id="productname" value="<?php echo $_REQUEST['productname']?>" /></td>
              <td width="14%" align="left">Category</td>
              <td width="25%" align="left">
			  <?php
			  	$cat_arr = generate_category_tree(0,0);
				if(is_array($cat_arr))
				{
					echo generateselectbox('categoryid',$cat_arr,0);
				}
			  ?>			  </td>
            </tr>
            <tr>
              <td align="left">Product Id </td>
              <td align="left"><input name="manufactureid" type="text" class="textfeild" id="manufactureid" value="<?php echo $_REQUEST['manufactureid']?>" /></td>
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
					echo generateselectbox('vendorid',$vendor_arr,0);
				}
			  ?>			  </td>
            </tr>
            <tr>
              <td colspan="4" align="left"><div id="show_morediv" onclick="handle_showmorediv()" title="Click here">Options<img src="images/right_arr.gif"></div></td>
              </tr>
            <tr id="listmore_tr" style="display:none">
              <td colspan="4"><table width="100%" border="0" cellspacing="1" cellpadding="1">
                <tr>
                  <td width="9%">&nbsp;</td>
                  <td width="22%" align="left">Retail Price Between </td>
                  <td width="9%" align="left"><input name="rprice_from" type="text" class="textfeild" id="rprice_from" value="<?php echo $_REQUEST['rprice_from']?>" size="10" /></td>
                  <td width="5%" align="center">and </td>
                  <td width="55%" align="left"><input name="rprice_to" type="text" class="textfeild" id="rprice_to" value="<?php echo $_REQUEST['rprice_to']?>" size="10" /></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td align="left">Cost Price Between </td>
                  <td align="left">
				<input name="cprice_from" type="text" class="textfeild" id="cprice_from" value="<?php echo $_REQUEST['cprice_from']?>" size="10" /></td>
                  <td align="center">and </td>
                  <td align="left"><input name="cprice_to" type="text" class="textfeild" id="cprice_to" value="<?php echo $_REQUEST['cprice_to']?>" size="10" /></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td align="left">Discount Atleast </td>
                  <td align="left"><input name="discount" type="text" class="textfeild" id="discount" value="<?php echo $_REQUEST['discount']?>" size="10" /></td>
                  <td align="center">Type</td>
                  <td align="left"><?php
					$disc_type = array(0=>'-- Any --','2'=>'%','1'=>'Value');
					echo generateselectbox('discountas',$disc_type,$_REQUEST['discountas']);
					?></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td align="left">Stock Atleast </td>
                  <td colspan="3" align="left"><input name="stock" type="text" class="textfeild" id="stock" value="<?php echo $_REQUEST['stock']?>" size="10" /></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td align="left">Bulk Discount Enabled? </td>
                  <td colspan="3" align="left">
				  <?php
					$disc_type = array(0=>'-- Any --','Y'=>'Y',"N"=>'N');
					echo generateselectbox('discountas',$disc_type,$_REQUEST['discountas']);
					?>				  </td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td align="left">In Preorder?</td>
                  <td colspan="3" align="left">
				  	<?php
					$pre_order = array(0=>'-- Any --','Y'=>'Y',"N"=>'N');
					echo generateselectbox('preorder',$pre_order,$_REQUEST['preorder']);
					?>					</td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td align="left">Hidden?</td>
                  <td colspan="3" align="left">
				  <?php
					$hidden_arr = array(0=>'-- Any --','Y'=>'Yes',"N"=>'No');
					echo generateselectbox('prodhidden',$hidden_arr,$_REQUEST['prodhidden']);
					?>				  </td>
                </tr>
              </table></td>
            </tr>
          </table>
            </td>
          <td width="34%" align="left" valign="top">
		  <table width="100%" height="56" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td align="left">Show
                <input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3" value="<?php echo $records_per_page?>" />
                <?php echo $page_type?> Per Page</td>
              <td width="23%" align="left">&nbsp;</td>
            </tr>
            <tr>
              <td align="left">Sort By&nbsp;<?php echo $sort_option_txt;?> in <?php echo $sort_by_txt?> </td>
              <td align="left"><input name="Search_go" type="submit" class="red" id="Search_go" value="Go" onclick="document.frm_products.search_click.value=1" />
                <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_BESTSEL_SELPROD_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
            </tr>
          </table>
		  </td>
        </tr>
      </table>
      </td>
    </tr>
    <tr>
      <td width="162" class="listeditd">&nbsp;</td>
      <td width="232" align="center" class="listeditd">
        <?php 
		if ($db->num_rows($ret_qry))
		{
			paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan);
		}
		?></td>
      <td width="317" align="right" class="listeditd">
	  <input name="change_hide" type="button" class="red" id="change_hide" onClick="call_save_selected()" value="Assign Selected">
	  </td>
    </tr>
    <tr>
      <td colspan="3" class="listingarea">
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
				  ?>
				  </td>	
				  <td align="left" valign="middle" class="<?php echo $cls?>"><?php echo display_price($row_qry['product_webprice'])?></td>
				  <td align="left" valign="middle" class="<?php echo $cls?>"><?php echo display_price($row_qry['product_costprice'])?></td>
				  <td align="center" valign="middle" class="<?php echo $cls?>"><?php echo $row_qry['product_bulkdiscount_allowed']?></td>	
				  <td align="center" valign="middle" class="<?php echo $cls?>"><?php echo $disc?></td>
				  <td align="left" valign="middle" class="<?php echo $cls?>"><?php echo $row_qry['product_webstock']."(".$row_qry['product_actualstock'].")"?></td>
				  <td align="center" valign="middle" class="<?php echo $cls?>">
				  	<?php
				  		echo ($row_qry['product_hide']=='Y')?'Yes':'No';	
					?>
				</td>
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
      </table></td>
    </tr>
	<tr>
      <td class="listeditd">&nbsp; </td>
      <td width="232" align="center" class="listeditd">
	  <?php 
		if ($db->num_rows($ret_qry))
		{
			paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan);
		}
		?></td>
      <td class="listeditd">&nbsp;</td>
    </tr>
    </table>
</form>