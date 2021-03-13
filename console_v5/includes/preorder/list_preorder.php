<?php
	/*
	#################################################################
	# Script Name 		: edit_settings_default.php
	# Description 		: Page for managing the main shop settings
	# Coded by 			: Snl
	# Created on		: 14-Jun-2007
	# Modified by		: Sny
	# Modified On		: 25-Aug-2008
	#################################################################
	*/	
	$table_name='products';

	$help_msg 			= get_help_messages('PREORDER_MESS1');
	
	$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmPreorder,\'checkboxproduct[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmPreorder,\'checkboxproduct[]\')"/>','Slno.','Product Name','Total Preorder Allowed','In Stock Date','Order','Hidden');
	$header_positions=array('center','center','left','left','left');
	
	$search_fields = array('productname','manufactureid','categoryid','vendorid','bulkdiscount','sort_by','sort_order','cbo_bulkdisc','srch_startdate','srch_enddate');
    $query_string = "request=preorder";

	foreach($search_fields as $v) {

	$query_string .= "&$v=".$_REQUEST[$v]."";//#For passing searh terms to javascript for passing to different pages.
    }
	$sort_by = (!$_REQUEST['sort_by'])?'product_name':$_REQUEST['sort_by'];
	$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
	$sort_options = array('product_name' => 'Name','manufacture_id'=>'Manufacture Id','product_costprice'=>'Cost Price','product_webprice'=>'Web Price');
	$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
	$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);
 
	//#Search Options
	$where_conditions = "WHERE sites_site_id=$ecom_siteid  AND 
											   product_preorder_allowed='Y' AND 
											   product_hide='N'";
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
	
	//Date
	if($_REQUEST['srch_startdate'] && $_REQUEST['srch_enddate'] ) {
	$fromdate_arr = explode("-",add_slash($_REQUEST['srch_startdate']));
	$fromdate = $fromdate_arr[2]."-".$fromdate_arr[1]."-".$fromdate_arr[0];
	$todate_arr = explode("-",add_slash($_REQUEST['srch_enddate']));
	$todate =$todate_arr[2]."-".$todate_arr[1]."-".$todate_arr[0];
	$where_conditions .= "AND (product_instock_date >= '".$fromdate."' AND product_instock_date <= '".$todate."' )";
	}
if($_REQUEST['srch_startdate'] && $_REQUEST['srch_enddate']=='' ) {
	$fromdate_arr = explode("-",add_slash($_REQUEST['srch_startdate']));
	$fromdate =$fromdate_arr[2]."-".$fromdate_arr[1]."-".$fromdate_arr[0];
	$where_conditions .= "AND (product_instock_date >= '".$fromdate."')";
	}
if($_REQUEST['srch_startdate']=='' && $_REQUEST['srch_enddate'] ) {
	$todate_arr = explode("-",add_slash($_REQUEST['srch_enddate']));
	$todate =$todate_arr[2]."-".$todate_arr[1]."-".$todate_arr[0];
	$where_conditions .= "AND (product_instock_date <= '".$todate."' )";
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
		}else{
			$include_prod[] = -1;
		}	
		if (count($include_prod))
		{
			$include_prod_str = implode(",",$include_prod);
			$where_conditions .= " AND ( product_id IN ($include_prod_str)) ";
		}
	*/
	}
		//# Retrieving the values of super admin from the table
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
	
	
?>
<script type="text/javascript">

function save_order(mod,checkboxname)
{
	var atleastone 			= 0;
	var ch_ids 				= '';
	var ch_order			= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';
	var orderbox			= '';
	switch(mod)
	{
		case 'product_preorder': // Case of Static pages in the group
			atleastmsg 	= 'Please select the Products to Change the Order';
			confirmmsg 	= 'Are you sure you want to Change the Order of selected Product(s)?';
			//moredivid	= 'productbestsellerunassign_div';
			fpurpose	= 'save_order';
			orderbox	= 'product_preorder_order_';
		break;
		
	}
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmPreorder.elements.length;i++)
	{
	if (document.frmPreorder.elements[i].type =='checkbox' && document.frmPreorder.elements[i].name== checkboxname)
		{
		

			if (document.frmPreorder.elements[i].checked==true)
			{
				atleastone = 1;
				if (ch_ids!='')
					ch_ids += '~';
				 ch_ids += document.frmPreorder.elements[i].value;
				
				 obj = eval("document.getElementById('"+orderbox+document.frmPreorder.elements[i].value+"')");
				if (ch_order != '')
					ch_order += '~';
				 ch_order += obj.value; 
			}	
		}
	}
	if (atleastone==0)
	{
		alert(atleastmsg);
	}
	else
	{
		if(confirm(confirmmsg))
		{ 
			//document.frmPreorder.action = 'home.php?request=preorder&ch_order='+ch_order+'&ch_ids='+ch_ids+'&fpurpose='+fpurpose;
			document.frmPreorder.ch_order.value = ch_order;
			document.frmPreorder.ch_ids.value = ch_ids;
			document.frmPreorder.fpurpose.value = 'save_order';
			document.frmPreorder.submit();
			
			//document.getElementById('retdiv_id').value 	= retdivid;/* Name of div to show the result */
		 	//document.getElementById('retdiv_more').value 	= moredivid;/* Name of div to show the result */	
			//retobj 										= eval("document.getElementById('"+retdiv_id+"')");
			//retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
		//	Handlewith_Ajax('services/preorder.php','fpurpose='+fpurpose+'&ch_order='+ch_order+'&ch_ids='+ch_ids+'&'+qrystr);
		}	
	}	
}

</script>
<form action="home.php" name="frmPreorder" method="post">
<input type="hidden" name="fpurpose" id="fpurpose" value="" />
<input type="hidden" name="ch_order" id="ch_order" value="" />
<input type="hidden" name="ch_ids" id="ch_ids" value="" />
<input type="hidden" name="request" value="preorder" />
<input type="hidden" name="start" value="<?=$start?>" />
<input type="hidden" name="pg" value="<?=$pg?>" />
<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
<input  type="hidden" name="productname" id="productname" value="<?=$_REQUEST['productname']?>"  />
<input  type="hidden" name="categoryid" id="categoryid" value="<?=$_REQUEST['categoryid']?>"  />
<input  type="hidden" name="manufactureid" id="manufactureid" value="<?=$_REQUEST['manufactureid']?>"  />
<input  type="hidden" name="vendorid" id="vendorid" value="<?=$_REQUEST['vendorid']?>"  />
<input  type="hidden" name="cbo_bulkdisc" id="cbo_bulkdisc" value="<?=$_REQUEST['cbo_bulkdisc']?>"  />


<input type="hidden" name="src_id" id="src_id" value="" />

<table border="0" cellpadding="0" cellspacing="0" width="100%" >
<tr>
<td valign="top" class="contentarea">
<?
		if(is_module_valid('mod_preorder','any')){
		?>
		  <div id="master_div">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
          <td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span> Products In Preorder</span></div></td>
        </tr>
        
	<?PHP	// Get the list of products under current category group
		  $sql_product = "SELECT product_id,product_name,product_preorder_custom_order,
		  				  product_total_preorder_allowed,DATE_FORMAT(product_instock_date,'%d %b %Y') as product_instock_date 
		  				  		FROM products 
										$where_conditions
													ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page";
		 
		  $ret_product = $db->query($sql_product);
?>
		<tr>
          <td colspan="2" align="center" valign="middle" >
		
		  <table width="100%" cellpadding="0" cellspacing="1" border="0">
           
			<tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="7">
		  <?php 
		  	$help_msg = get_help_messages('PREORDER_PROD_MESS1');
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
            <?php
				if($alert)
				{
					?>
            <tr>
              <td colspan="7" align="center" class="errormsg"><?php echo $alert?></td>
            </tr>
            <?php
				}
				?>
		<?php 
		if ($db->num_rows($ret_product))
		{
		?>
         <tr>
		  <td colspan="7" align="right" valign="middle" class="sorttd"><?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan);	?></td>
		</tr>
		<?php
		}
		?>
		 <tr>
      <td height="48" colspan="7" class="sorttd">
		<div class="sorttd_div">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td align="left" valign="top">
		  <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td width="15%" align="left" valign="middle">Product Name</td>
              <td width="20%" align="left" valign="middle"><input name="productname" type="text" class="textfeild" id="productname" value="<?php echo $_REQUEST['productname']?>" /></td>
              <td width="14%" align="left" valign="middle">Category</td>
              <td colspan="3" align="left" valign="middle">
			  <?php
			  	$cat_arr = generate_category_tree(0,0,true);
				if(is_array($cat_arr))
				{
					echo generateselectbox('categoryid',$cat_arr,$_REQUEST['categoryid']);
				}
			  ?>			  </td>
            </tr>
            <tr>
              <td align="left" valign="middle">Product Id </td>
              <td align="left" valign="middle"><input name="manufactureid" type="text" class="textfeild" id="manufactureid" value="<?php echo $_REQUEST['manufactureid']?>" /></td>
              <td align="left" valign="middle">Vendor</td>
              <td colspan="3" align="left" valign="middle">
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
              &nbsp;&nbsp;&nbsp;Bulk Disc 
              <?php
			  	$bulkdisc_arr		= array(0=>'-- Any --','Y'=>'Yes','N'=>'No');
			  	echo generateselectbox('cbo_bulkdisc',$bulkdisc_arr,$_REQUEST['cbo_bulkdisc']);
				?>              </td>
              </tr>
            <tr>
              <td colspan="2" align="left" valign="middle">Date From
                <input class="textfeild" type="text" name="srch_startdate" size="8" value="<?=$_REQUEST['srch_startdate']?>"  />
                &nbsp;<a href="javascript:show_calendar('frmPreorder.srch_startdate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" align="absmiddle" /></a>&nbsp;&nbsp;&nbsp;Date To
                <input class="textfeild" type="text" name="srch_enddate" size="8" value="<?=$_REQUEST['srch_enddate']?>"  />
&nbsp; <a href="javascript:show_calendar('frmPreorder.srch_enddate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0"  align="absmiddle"/></a></td>
              <td align="left" valign="middle">Records Per Page</td>
              <td width="6%" align="left" valign="middle"><input name="records_per_page2" type="text" class="textfeild" id="records_per_page2" size="3" maxlength="3" value="<?php echo $records_per_page?>" /></td>
              <td width="22%" align="left" valign="middle">Sort By&nbsp;<?php echo $sort_option_txt;?>&nbsp;&nbsp;in&nbsp;&nbsp;<?php echo $sort_by_txt?> </td>
              <td width="23%" align="right" valign="middle"><input name="Search_go" type="submit" class="red" id="Search_go" value="Go" onclick="document.frm_products.search_click.value=1" />
                <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
            </tr>
          </table>            </td>
          </tr>
      </table> 
		</div></td>
    </tr>
            
		<tr>
		    <td width="100%" class="tdcolorgray" colspan="7">
			<div class="listingarea_div">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<?php 
		if ($db->num_rows($ret_product))
		{
		?>
	<tr>
	 <td  align="center" class="listeditd" colspan="5">
        
		</td>
		<td class="listeditd" colspan="2">
                        <input name="SaveOrder" type="button" class="red" id="SaveOrder" value="Save Order" onclick="save_order('product_preorder','checkboxproduct[]')" />
                  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_SAVEORDER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> 
		</td>
		</tr>
		
		
		<?
		  }
		?>
		<?php $colspan = count($table_headers);
				echo table_header($table_headers,$header_positions); 
		?>
            <?PHP
				if ($db->num_rows($ret_product))
				{
								$cnt = 1;
				while ($row_product = $db->fetch_array($ret_product))
				{
					
					$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
				?>
            <tr>
              <td width="7%" align="left" class="<?php echo $cls?>"><input type="checkbox" name="checkboxproduct[]" value="<?php echo $row_product['product_id'];?>" /></td>
              <td width="5%" align="left" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
              <td width="41%" align="left" class="<?php echo $cls?>"><a href="home.php?request=products&amp;fpurpose=edit&amp;checkbox[]=<? echo $row_product['product_id']; ?>" class="edittextlink"><?php echo stripslashes($row_product['product_name']);?></a></td>
              <td width="19%" align="left" class="<?php echo $cls?>"><?PHP echo $row_product['product_total_preorder_allowed']; ?></td>
              <td width="12%" align="left" class="<?php echo $cls?>"><?PHP echo $row_product['product_instock_date']; ?></td>
              <td width="7%" align="left" class="<?php echo $cls?>"><input type="text" name="product_preorder_order_<?php echo $row_product['product_id']?>" id="product_preorder_order_<?php echo $row_product['product_id']?>" value="<?php echo stripslashes($row_product['product_preorder_custom_order']);?>" size="2" /></td>
              <td width="9%" align="left" class="<?php echo $cls?>"><?php echo 'No';?></td>
            </tr>
            <?php
				}
				}
				else
				{
				?>
            <tr>
              <td colspan="7" align="center" valign="middle" class="norecordredtext"><input type="hidden" name="productbestseller_norec" id="productbestseller_norec" value="1" />
                No Products Set as Pre Order. </td>
            </tr>
            <?	
				}
				?>
          </table>
		  	</div>
		  </td>
		</tr>
	
		
		</table></td>
		</tr>	
		<?php 
		if ($db->num_rows($ret_product))
		{
		?>
         <tr>
		  <td colspan="7" align="right" valign="middle" class="listing_bottom_paging"><?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan);	?></td>
		</tr>
		<?php
		}
		?>
		<? }?>	
</table>
</form>