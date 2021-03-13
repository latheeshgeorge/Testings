<?php
	/*#################################################################
	# Script Name 	: list_assign_products.php
	# Description 	: Page for listing Products for assiging into the Adverts
	# Coded by 		: ANU
	# Created on	: 12-July-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
//Define constants for this page
$table_name='products';
$page_type='Products';
//$help_msg = 'This section lists the Products that can be assigned to the Newsletter.';
$help_msg = get_help_messages('NEWSLETTER_ASSIGN_PROD_MESS1');
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistProducts,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistProducts,\'checkbox[]\')"/>','Slno.','Product Name','Category','Retail','Cost','Bulk Discount','Disc','Stock(All)','Hide');
$header_positions=array('center','left','left','left','left','left','left','left','left');
$colspan = count($table_headers);

	$tabale = "newsletters";
	$where  = "newsletter_id=".$_REQUEST['newsletter_id'];
	if(!server_check($tabale, $where)) {
		echo " <font color='red'> You Are Not Authorised  </a>";
		exit;
	}

// Finding already assigned Products in the Advert.
$sql_assigned_products = "SELECT products_product_id FROM newsletter_products where newsletters_newsletter_id =".$_REQUEST['newsletter_id']." AND sites_site_id=".$ecom_siteid;
$res_assigned_products = $db->query($sql_assigned_products);
$assigned_products_str = '';
while($assigned_products = $db->fetch_array($res_assigned_products)){
if($assigned_products_str !=''){
$assigned_products_str= $assigned_products_str.',';
}
$assigned_products_str .=  $assigned_products['products_product_id'];
}
//#Search terms.
$search_fields = array('product_name','categoryid');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort.
$sort_by = (!$_REQUEST['sort_by'])?'product_name':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('product_name' => 'Product Name');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options.
$category_id = $_REQUEST['categoryid'];
if($category_id){
$table_name = 'products p,product_category_map pcm ';
$where_conditions = "WHERE sites_site_id=$ecom_siteid AND p.product_id=pcm.products_product_id AND product_categories_category_id =$category_id";
$selected=$categoryid;// for displaying the selected value in the category tree
}else{
$selected = 0;// for displaying the selected value in the category tree
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
}
if($assigned_products_str!=''){
$where_conditions .=" AND product_id NOT IN ($assigned_products_str)";
}
if($_REQUEST['search_name']) {
	$where_conditions .= " AND (product_name LIKE '%".add_slash($_REQUEST['search_name'])."%')";
}

//#Select condition for getting total count.
$sql_count = "SELECT count(*) as cnt FROM $table_name  $where_conditions";
$res_count = $db->query($sql_count);
list($numcount) = $db->fetch_array($res_count);#Getting total count of records.
/////////////////////////////////For paging///////////////////////////////////////////
$records_per_page = (is_numeric($_REQUEST['records_per_page']) and $_REQUEST['records_per_page'])?$_REQUEST['records_per_page']:10;#Total records shown in a page
$pg = ($_REQUEST['search_click'])?1:$_REQUEST['pg'];

if (!($pg > 0) || $pg == 0) { $pg = 1; }
$pages = ceil($numcount / $records_per_page);#Getting the total pages.
if($pg > $pages) {
	$pg = $pages;
}
$start = ($pg - 1) * $records_per_page;#Starting record.
$count_no = ($pg == 1)?0:$records_per_page*($pg-1);
/////////////////////////////////////////////////////////////////////////////////////
$query_string .= "request=newsletter&fpurpose=list_assign_products&newsletter_id=$newsletter_id&sort_by=$sort_by&sort_order=$sort_order&records_per_page=$records_per_page&search_name=".$_REQUEST['search_name']."&start=$start&advert_id=".$_REQUEST['advert_id']."&advert_title=".$_REQUEST['advert_title']."&pass_sort_by=$pass_sort_by&pass_sort_order=$pass_sort_order&pass_records_per_page=$pass_records_per_page&pass_search_name=".$_REQUEST['pass_search_name']."";
// 
?>
<script language="javascript">
function assignproducts()
{ 
	var atleastone 			= 0;
	var curid				= 0;
	var product_ids		    = '';
	var cat_orders			= '';
	//var ch_status			= document.frmlistPage.cbo_changehide.value;
	
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlistProducts.elements.length;i++)
	{
		if (document.frmlistProducts.elements[i].type =='checkbox' && document.frmlistProducts.elements[i].name=='checkbox[]')
		{

			if (document.frmlistProducts.elements[i].checked==true)
			{
			
				atleastone = 1;
				if (product_ids!='')
					product_ids += '~';
				 product_ids += document.frmlistProducts.elements[i].value;
			}	
		}
	}
	if (atleastone==0) 
	{
		alert('Please select the Products to assign to the Newsletter');
		return false;
	}
	else
	{
		if(confirm('Assign Products to the Newsletter?'))
		{
				show_processing();
				//alert('fpurpose=assign_pages&'+qrystr+'&page_ids='+page_ids);
				//Handlewith_Ajax('services/static_group.php','fpurpose=assign_pages&'+qrystr+'&page_ids='+page_ids);
				document.frmlistProducts.product_ids.value=product_ids;
				document.frmlistProducts.fpurpose.value='assign_products';
				document.frmlistProducts.submit();
		}	
	}	
}


</script>
<form name="frmlistProducts" action="home.php?request=newsletter" method="post" >	
  <input type="hidden" name="fpurpose" value="list_assign_products" />
  <input type="hidden" name="request" value="newsletter" />
  <input type="hidden" name="product_ids" id="product_ids" value="" />
  <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
  <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
  <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
  <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
  <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />  
  <input type="hidden" name="newsletter_id" id="newsletter_id" value="<?=$_REQUEST['newsletter_id']?>" />  
  <input type="hidden" name="newsletter_title" id="newsletter_title" value="<?=$_REQUEST['newsletter_title']?>" />  
  <input type="hidden" name="pass_search_name" id="pass_search_name" value="<?=$_REQUEST['pass_search_name']?>" />
  <input type="hidden" name="pass_start" id="pass_start" value="<?=$_REQUEST['pass_start']?>" />
  <input type="hidden" name="pass_sort_by" id="pass_sort_by" value="<?=$_REQUEST['pass_sort_by']?>" />
  <input type="hidden" name="pass_sort_order" id="pass_sort_order" value="<?=$_REQUEST['pass_sort_order']?>" />
  <input type="hidden" name="pass_records_per_page" id="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
  <input type="hidden" name="pass_pg" id="pass_pg" value="<?=$_REQUEST['pass_pg']?>" />
  <table border="0" cellpadding="0" cellspacing="0" class="maintable" width="100%">
    <tr>
      <td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=newsletter&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&search_name=<?=$_REQUEST['pass_search_name']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&newsletter_id=<?=$_REQUEST['newsletter_id']?>">List Newsletter</a><a href="home.php?request=newsletter&fpurpose=edit&newsletter_id=<?=$_REQUEST['newsletter_id']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_start=<?=$_REQUEST['pass_start']?>&pass_pg=<?=$_REQUEST['pass_pg']?>">Edit Newsletter</a><a href="home.php?request=newsletter&fpurpose=prodnewsletter&newsletter_id=<?=$_REQUEST['newsletter_id']?>"> Assigned Products </a><span> Assign Products to the Newsletter : <b>  '<?=$_REQUEST['newsletter_title']?>' </b></span></div></td>
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
	<?php
		  if($numcount)
		  {
	?>
    <tr><td colspan="2" align="right" valign="middle" class="sorttd"><?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan);?></td></tr>
	<?php 
		  }
	?>
	<tr>
      <td height="48" class="sorttd" colspan="2" >
	  <div class="sorttd_div">
      	<table width="100%" border="0" cellpadding="1" cellspacing="0">
        <tr>
          <td align="left">Title </td>
          <td align="left"><input name="search_name" type="text" class="textfeild" id="search_name" value="<?=$_REQUEST['search_name']?>"  /></td>
          <td align="left">Show</td>
          <td align="left"><input name="records_per_page2" type="text" class="textfeild" id="records_per_page2" size="3" maxlength="3"  value="<?=$records_per_page?>"/>
            <?=$page_type?>
Per Page</td>
          <td align="right">&nbsp;</td>
        </tr>
        <tr>
          <td width="10%" align="left">Category </td>
          <td width="35%" align="left"><?php
		  		
			  	$cat_arr = generate_category_tree(0,0);
				$categoryname = '';
				if($category_id){
				$categoryname = $cat_arr[$selected];
				}
				if(is_array($cat_arr))
				{
					echo generateselectbox('categoryid',$cat_arr,$selected);
				}
			  ?></td>
          <td width="7%" align="left">Sort By</td>
          <td width="39%" align="left"><?=$sort_option_txt?>
in
  <?=$sort_by_txt?></td>
          <td width="9%" align="right"><input name="button5" type="submit" class="red" id="button5" value="Go" />
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ASSIGN_PROD_NEWSLETTER_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
      </table>
	  </div></td>
    </tr>
     
    <tr>
      <td colspan="2" class="listingarea">
	  <div class="listingarea_div">
      <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="listingtable">
	  
    <tr>
	   <td align="right" class="listeditd" colspan="10">	   
	  <?
	  if($numcount)
	  {
	  ?>	
		<input name="assign_products" type="button" class="red" id="assign_products" value="Assign Products" onclick="return assignproducts();" />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ASSIGN_PROD_NEWSLETTER_ASSIGN')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
		<?
		}
		?>   	   </td>
    </tr>
       <?
	   
	   if($numcount)
	   {
	   echo table_header($table_headers,$header_positions);
	  $sql_products = "SELECT * FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
	   
	   $res = $db->query($sql_products);
	   $srno = 1; 
	   while($row = $db->fetch_array($res))
	   {
			$count_no++;
			$array_values = array();
			if($count_no %2 == 0)
				$class_val="listingtablestyleA";
			else
				$class_val="listingtablestyleB";	
				
		
		if($row['product_discount']>0)
				{
					$disctype	= ($row['product_discount_enteredasval']==0)?'%':'';
					$discval_arr= explode(".",$row['product_discount']);
					if($discval_arr[1]=='00')
						$discval = $discval_arr[0];
					else
						$discval = $row['product_discount'];
					$disc		= $discval.$disctype;
					if($row['product_discount_enteredasval']==1)
					{
					 $disc = display_price($disc);
					}
				}	
				else
					$disctype = $disc = '--';		
	   
	   ?>
        <tr >
          <td align="center" valign="middle" class="<?=$class_val;?>"  width="9%"><input name="checkbox[]" value="<? echo $row['product_id']?>" type="checkbox"></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"  width="2%"><?php echo $srno++?></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><a href="home.php?request=products&fpurpose=edit&checkbox[0]=<?php echo $row['product_id']?>&search_name=<?php echo $_REQUEST['search_name']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>" title="<? echo $row['title']?>" class="edittextlink" onclick="show_processing()"><? echo $row['product_name']?></a></td>
        <?
		 /*  <td align="left" valign="middle" class="<?=$class_val;?>">
		
		 $sql_newsletter="SELECT newsletter_title FROM newsletters a,newsletter_products b WHERE b.products_product_id=".$row['product_id']." AND a.newsletter_id=b.newsletters_newsletter_id";
		 $res_newsletter=$db->query($sql_newsletter);
		 $num_newsletter=$db->num_rows($res_newsletter);
		  if($num_newsletter) {
		  ?>
		  <select name="adverts">
		  <?
		  while($row_newsletter=$db->fetch_array($res_newsletter))
		  {
		  	echo "<option value=$row_advert[advert_title]>$row_newsletter[newsletter_title]</option>";
		  }
		  ?>
		  </select>
		 <?
		 }
		 else
		 {
		 	echo "No Newsletters assigned ";
		 }
		 </td> */
		 ?>
          <td align="left" valign="middle" class="<?=$class_val;?>"> <?php
				  		$cat_arr		= array();
				  		// Get the list of categories to which the current product is assigned to 
						$sql_cats = "SELECT a.category_id,a.category_name FROM product_categories a,product_category_map b WHERE 
									b.products_product_id=".$row['product_id']." AND a.category_id=b.product_categories_category_id";
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
							echo generateselectbox('catid_'.$row['product_id'],$cat_arr,0);
						}
						else
							echo "--";	
				  ?></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo display_price($row['product_webprice'])?></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo display_price($row['product_costprice'])?></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo $row['product_bulkdiscount_allowed']?></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo $disc?></td>
		    <td align="left" valign="middle" class="<?=$class_val;?>">
			  <?php 
				  if($row['product_variablestock_allowed']=='Y')
				  {
				 	$ret_stock  	= get_product_stock($row);
					//$actual_stock	= $ret_stock['act_stock'];
					$web_stock		= $ret_stock['web_stock']	;
				  }
				  else
				  {
				   	//$actual_stock	= $row_qry['product_actualstock'];
					$web_stock		= $row['product_webstock']	;
				  }
				  $actual_stock	= $row['product_actualstock'];
				  echo $web_stock."(".$actual_stock.")" ; 
			// echo $row['product_webstock']."(".$row['product_actualstock'].")"
			?></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo ($row['product_hide'] == 'Y')?'Yes':'No'; ?></td>
        </tr>
      	<?
	  	}
	  }
	  else
	  {
	  ?>
	  <tr>
				  <td align="center" valign="middle" class="norecordredtext" colspan="10" >
				  	No UnAssigned Products exists.				  </td>
		  </tr>
		<?
		}
		?>
		
      </table>
	  </div>
	  </td>
    </tr><tr>
      <td align="right" class="listeditd" valign="middle" colspan="10">	  
	    <?
	  if($numcount) {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?></td>
    </tr>	
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