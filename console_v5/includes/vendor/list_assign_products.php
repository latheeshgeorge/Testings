<?php
	/*#################################################################
	# Script Name 	: list_assign_products.php
	# Description 	: Page for listing Products for assiging into the page groups
	# Coded by 		: ANU
	# Created on	: 12-July-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
//Define constants for this page
$table_name='products';
$page_type='Products';
$help_msg = get_help_messages('LIST_ASSIGNPROD_VENDOR_MESS1'); 
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistProducts,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistProducts,\'checkbox[]\')"/>','Slno.','Product Name','Category','Active');
$header_positions=array('left','left','left','left','left','left');
$colspan = count($table_headers);

/*$tabale = "product_vendors";
$where  = "vendor_id=".$_REQUEST['vendor_id'];
if(!server_check($tabale, $where)) {
	echo " <font color='red'> You Are Not Authorised  </a>";
	exit;
}*/


if(trim($_REQUEST['vendor_id'])) {
// Finding already assigned Products in the group.
/*$sql_assigned_products = "SELECT products_product_id FROM product_vendor_map
						 where product_vendors_vendor_id =".$_REQUEST['vendor_id']." AND sites_site_id=".$ecom_siteid;
$res_assigned_products = $db->query($sql_assigned_products);
$assigned_products_str = '';
while($assigned_products = $db->fetch_array($res_assigned_products)){
if($assigned_products_str !=''){
$assigned_products_str= $assigned_products_str.',';
}
$assigned_products_str .=  $assigned_products['products_product_id'];
}*/
//
$assigned_products_str = '';
$hid_vend_arr = explode("~",$_REQUEST['vendor_id']);
			if(count($hid_vend_arr)==1)
			{
				for($x=0;$x<count($hid_vend_arr);$x++)
				{
					$sql_assigned_products = "SELECT products_product_id FROM product_vendor_map WHERE product_vendors_vendor_id =".$hid_vend_arr[$x]." AND sites_site_id=".$ecom_siteid;
					$res_assigned_products = $db->query($sql_assigned_products);
					//$assigned_products_arr = array();
					while($assigned_products = $db->fetch_array($res_assigned_products))
					{
							$assigned_products_arr[]= $assigned_products['products_product_id'];
							if($assigned_products_str !=''){
								$assigned_products_str= $assigned_products_str.',';
						}
						$assigned_products_str .=  $assigned_products['products_product_id'];
					}
				}
			}
//

}
//#Search terms.
$search_fields = array('search_name_ass','categoryid','records_per_page_ass','prod_sort_order','prod_sort_by');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
$query_string .="request=prod_vendor&fpurpose=list_assign_products&vendor_id=".$_REQUEST['vendor_id']."&pass_sort_order=".$_REQUEST['pass_sort_order']."&pass_sort_by=".$_REQUEST['pass_sort_by']."&pass_records_per_page=".$_REQUEST['pass_records_per_page']."&pass_start=".$_REQUEST['pass_start']."&pass_search_name=".$_REQUEST['pass_search_name']."&pass_pg=".$_REQUEST['pass_pg']."";	
//#Sort.
$prod_sort_by = (!$_REQUEST['prod_sort_by'])?'product_name':$_REQUEST['prod_sort_by'];
$prod_sort_order = (!$_REQUEST['prod_sort_order'])?'ASC':$_REQUEST['prod_sort_order'];
$prod_sort_options = array('product_name' => 'Product Name');
$prod_sort_option_txt = generateselectbox('prod_sort_by',$prod_sort_options,$prod_sort_by);
$prod_sort_by_txt= generateselectbox('prod_sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$prod_sort_order);

//#Search Options.
$category_id = $_REQUEST['categoryid'];
if($category_id){
$table_name = 'products p,product_category_map pcm ';
$where_conditions = "WHERE sites_site_id=$ecom_siteid AND p.product_id=pcm.products_product_id AND product_categories_category_id =$category_id";
$selected=$category_id;// for displaying the selected value in the category tree
}else{
$selected = 0;// for displaying the selected value in the category tree
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
}
if(($assigned_products_str!='') && (!trim($_REQUEST['hid_vend_id']))){
$where_conditions .=" AND product_id NOT IN ($assigned_products_str)";
}
if($_REQUEST['search_name_ass']) {
	$where_conditions .= " AND (product_name LIKE '%".add_slash($_REQUEST['search_name_ass'])."%')";
}

//#Select condition for getting total count.
$sql_count = "SELECT count(*) as cnt FROM $table_name  $where_conditions";
$res_count = $db->query($sql_count);
list($numcount) = $db->fetch_array($res_count);#Getting total count of records.
/////////////////////////////////For paging///////////////////////////////////////////
$records_per_page_ass = (is_numeric($_REQUEST['records_per_page_ass']) and $_REQUEST['records_per_page_ass']>0)?intval($_REQUEST['records_per_page_ass']):10;#Total records shown in a page
$pg = ($_REQUEST['search_click'])?1:$_REQUEST['pg'];

if (!($pg > 0) || $pg == 0) { $pg = 1; }
$pages = ceil($numcount / $records_per_page_ass);#Getting the total pages.
if($pg > $pages) {
	$pg = $pages;
}
$start = ($pg - 1) * $records_per_page_ass;#Starting record.
$count_no = ($pg == 1)?0:$records_per_page_ass*($pg-1);
/////////////////////////////////////////////////////////////////////////////////////
//$query_string .= "request=stat_group&fpurpose=list_assign_products&sort_by=$sort_by&sort_order=$sort_order&records_per_page=$records_per_page&search_name=".$_REQUEST['search_name']."&start=$start&group_id=".$_REQUEST['group_id']."&group_name=".$_REQUEST['group_name']."&pass_sort_by=$pass_sort_by&pass_sort_order=$pass_sort_order&pass_records_per_page=$pass_records_per_page&pass_search_name=".$_REQUEST['pass_search_name']."";
// to find the groupname
?>
<script language="javascript">


function assignproducts()
{ 
	var atleastone 			= 0;
	var curid				= 0;
	var product_ids		= '';
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
		alert('Please select the Products to assign to the Vendor');
		return false;
	}
	else
	{
		if(confirm('Assign Products to the Vendor?'))
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
<form name="frmlistProducts" action="home.php?request=prod_vendor" method="post" >	
  <input type="hidden" name="fpurpose" value="list_assign_products"/>
  <input type="hidden" name="request" value="prod_vendor"/>
  <input type="hidden" name="product_ids" id="product_ids" value="" />
  <input type="hidden" name="prod_sort_by" id="prod_sort_by" value="<?=$_REQUEST['prod_sort_by']?>" />
  <input type="hidden" name="prod_sort_order" id="prod_sort_order" value="<?=$_REQUEST['prod_sort_order']?>" />
  <input type="hidden" name="records_per_page" id="records_per_page_ass" value="<?=$_REQUEST['records_per_page_ass']?>" />
  <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
  <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />  
  <input type="hidden" name="vendor_id" id="vendor_id" value="<?=$vendor_id?>" />  
  <input type="hidden" name="vendor_name" id="vendor_name" value="<?=$_REQUEST['vendor_name']?>" />  
  <input type="hidden" name="pass_search_name" id="pass_search_name" value="<?=$_REQUEST['pass_search_name']?>" />
  <input type="hidden" name="pass_start" id="pass_start" value="<?=$_REQUEST['pass_start']?>" />
  <input type="hidden" name="pass_sort_by" id="pass_sort_by" value="<?=$_REQUEST['pass_sort_by']?>" />
  <input type="hidden" name="pass_sort_order" id="pass_sort_order" value="<?=$_REQUEST['pass_sort_order']?>" />
  <input type="hidden" name="pass_records_per_page" id="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
  <input type="hidden" name="pass_pg" id="pass_pg" value="<?=$_REQUEST['pass_pg']?>" />
  <input type="hidden" name="hid_vend_id" value="<?=$hid_vend_id?>" />

  <table border="0" cellpadding="0" cellspacing="0" class="maintable" width="100%">
    <tr>
      <td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=prod_vendor&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&search_name=<?=$_REQUEST['pass_search_name']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&group_id=<?=$_REQUEST['group_id']?>">List Vendors</a>
	 <? if(count($hid_vend_arr)==1)
			{ ?> <a href="home.php?request=prod_vendor&fpurpose=edit&vendor_id=<?=$_REQUEST['vendor_id']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&search_name=<?=$_REQUEST['pass_search_name']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=prods_tab_td">Edit Vendors</a><span> <? }?>
   Assign Products to the <? if(!trim($_REQUEST['vendor_name'])) echo 'Selected Vendors'; ?>  <? if(trim($_REQUEST['vendor_name'])) { ?> Vendor : <b>  '<? echo $_REQUEST['vendor_name']; ?>'</b> <? } ?></span></div> </td>
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
	  if($numcount)
	  {
	  ?>
    <tr>
		<td colspan="2" align="right" valign="middle" class="sorttd"><?	  paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 	  ?></td>
	</tr>
	<?php
		}
	?>
	<tr>
      <td height="48" class="sorttd" colspan="2" >
	  <div class="sorttd_div">
      	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttableleft">
        <tr>
          <td width="22%" height="30" align="left" valign="middle">Title </td>
          <td height="30" colspan="3" align="left" valign="middle"><input name="search_name_ass" type="text" class="textfeild" id="search_name_ass" value="<?=$_REQUEST['search_name_ass']?>"  /></td>
          </tr>
		  
		  <tr>
          <td width="22%" height="30" align="left" valign="middle">Category </td>
          <td height="30" colspan="3" align="left" valign="middle">  <?php
			  	$cat_arr = generate_category_tree(0,0,true);
				$categoryname = '';
				if($category_id){
				$categoryname = $cat_arr[$selected];
				}
				if(is_array($cat_arr))
				{
					echo generateselectbox('categoryid',$cat_arr,$selected);
				}
			  ?></td>
          </tr>	
      </table>
      <table width="18%" border="0" cellpadding="0" cellspacing="0" class="sorttableright">
        <tr>
          <td width="28%" height="30" align="left" valign="middle">Records Per Page </td>
          <td width="31%" height="30" align="left" valign="middle"><input name="records_per_page_ass" type="text" class="textfeild" id="records_per_page_ass" size="3" maxlength="3"  value="<?=$records_per_page_ass?>"/></td>
          <td width="41%" height="30" align="left" valign="middle">&nbsp;</td>
        </tr>
        <tr>
          <td height="30" align="left" valign="middle">Sort By</td>
          <td height="30" align="left" valign="middle" nowrap="nowrap"><?=$prod_sort_option_txt?> in <?=$prod_sort_by_txt?>  </td>
          <td height="30" align="left" valign="middle">&nbsp;&nbsp;&nbsp;&nbsp;
            <input name="button5" type="submit" class="red" id="button5" value="Go" /> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_VENDOR_ASSIGNPROD_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
      </table></div>	  </td>
    </tr> 
	 
    <tr>
      <td colspan="2" class="listingarea">
	  <div class="listingarea_div">
      <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="listingtable">
	  <?
	  if($numcount)
	  {
	   
	  ?>
    <tr>
	   <td align="right" class="listeditd" valign="middle" colspan="6">
	   	<input name="assign_products" type="button" class="red" id="assign_products" value="Assign Products" onclick="return assignproducts();" />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_VENDOR_ASSIGNPROD_ASSIGN')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
		</td>
    </tr>
    <?
		}
	?>
       <?
	    echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
	  	   
	   $sql_products = "SELECT product_id,product_name FROM $table_name $where_conditions ORDER BY $prod_sort_by $prod_sort_order LIMIT $start,$records_per_page_ass ";
	   
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
	   
	   ?>
        <tr >
          <td align="left" valign="middle" class="<?=$class_val;?>"  width="5%"><input name="checkbox[]" value="<? echo $row['product_id']?>" type="checkbox"></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"  width="2%"><?php echo $srno++?></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><a href="home.php?request=products&fpurpose=edit&checkbox[0]=<?php echo $row['product_id']?>" title="<? echo $row['title']?>" class="edittextlink" onclick="show_processing()"><? echo $row['product_name']?></a></td>
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
							echo generateselectbox('catid_'.$row_qry['product_id'],$cat_arr,0);
						}
						else
							echo "--";	
				  ?></td>
		  
		  <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo ($row['hide'] == 0)?'Yes':'No'; ?></td>
        </tr>
      	<?
	  	}
	  }
	  else
	  {
	  ?>
	  <tr>  <td align="center" valign="middle" class="norecordredtext" colspan="6" >	  	No UnAssigned Products exists.  </td>  </tr>
		<?
		}
		?>	
		
      </table>
	  </div></td>
    </tr>
	<tr>
      <td align="right" class="listing_bottom_paging" valign="middle" colspan="6">
	    <?
	  if($numcount) {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?></td>
    </tr>
  
  </table>
</form>
