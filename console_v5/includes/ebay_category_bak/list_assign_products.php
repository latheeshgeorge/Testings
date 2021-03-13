<?php
	/*#################################################################
	# Script Name 	: list_assign_products.php
	# Description 		: Page for listing Products for assiging to selected categories
	# Coded by 		: ANU
	# Created on		: 12-July-2007
	# Modified by		: 
	# Modified On		: 
	#################################################################*/
//Define constants for this page
$table_name='products';
$page_type='Products';
$help_msg =get_help_messages('LIST_PROD_ASS_ADVERT_MESS1');
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistProducts,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistProducts,\'checkbox[]\')"/>','Slno.','Product Name','Banners assigned for the product','Category','Active');
$header_positions=array('left','left','left','left','left','left');
$colspan = count($table_headers);

$group_id=$pass_cat_id=($_REQUEST['pass_cat_id']?$_REQUEST['pass_cat_id']:'0');
/*
	$tabale = "product_categories";
	$where  = "category_id=".$group_id;
	if(!server_check($tabale, $where)) {
		echo " <font color='red'> You Are Not Authorised  </a>";
		exit;
	}*/

// Finding already assigned Products in the Advert.
$sql_assigned_products = "SELECT products_product_id FROM product_category_map where product_categories_category_id =".$group_id;
$res_assigned_products = $db->query($sql_assigned_products);
$assigned_products_str = '';
while($assigned_products = $db->fetch_array($res_assigned_products)){
if($assigned_products_str !=''){
$assigned_products_str= $assigned_products_str.',';
}
$assigned_products_str .=  $assigned_products['products_product_id'];
}
//#Search terms.
$query_string .= "request=prod_cat&fpurpose=productAssign";
$search_fields = array('product_name','categoryid','search_name','sort_by','sort_order');
foreach($search_fields as $v) {
	$query_string .= "&$v=".$_REQUEST[$v]."";#For passing searh terms to javascript for passing to different pages.
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
$selected=$category_id;// for displaying the selected value in the category tree
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

$query_string .= "&pass_catname=".$_REQUEST['pass_catname']."&catgroupid=".$_REQUEST['pass_catgroupid']."&parentid=".$_REQUEST['pass_parentid']."&pass_sort_by=".$_REQUEST['pass_sort_by']."&pass_sort_order=".$_REQUEST['pass_sort_order']."&pass_records_per_page=".$_REQUEST['pass_records_per_page']."&pass_start=".$_REQUEST['pass_start']."&pass_pg=".$_REQUEST['pass_pg']."&curtab=product_tab_td&pass_cat_id=".$_REQUEST['pass_cat_id'];
// 
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
		alert('Please select the Products to assign to the current Category');
		return false;
	}
	else
	{
		if(confirm('Assign Selected Product(s) to current Category?'))
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
<form name="frmlistProducts" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="productAssign" />
<input type="hidden" name="request" value="prod_cat" />
  <input type="hidden" name="product_ids" id="product_ids" value="" />
	<input type="hidden" name="search_click" value="" />
	<input type="hidden" name="pass_cat_id" value="<?=$pass_cat_id?>" />
	<input type="hidden" name="pass_catname" value="<?=$_REQUEST['pass_catname']?>" />
	<input type="hidden" name="pass_catgroupid" value="<?=$_REQUEST['pass_catgroupid']?>" />
	<input type="hidden" name="pass_parentid" value="<?=$_REQUEST['pass_parentid']?>" />
	<input type="hidden" name="pass_sort_by" value="<?=$_REQUEST['pass_sort_by']?>" />
	<input type="hidden" name="pass_sort_order" value="<?=$_REQUEST['pass_sort_order']?>" />
	<input type="hidden" name="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
	<input type="hidden" name="pass_start" value="<?=$_REQUEST['pass_start']?>" />
	<input type="hidden" name="pass_pg" value="<?=$_REQUEST['pass_pg']?>" />
<? 
$sql_cat = "SELECT category_name FROM product_categories WHERE category_id=".$_REQUEST['pass_cat_id'];
	$ret_cat = $db->query($sql_cat);
	if ($db->num_rows($ret_cat))
	{
		$row_cat 		= $db->fetch_array($ret_cat);
		$show_catname	= stripslashes($row_cat['category_name']);
	}
?>
  <table border="0" cellpadding="0" cellspacing="0" class="maintable" width="100%">
    <tr>
      <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=prod_cat&catname=<?=$_REQUEST['pass_catname']?>&catgroupid=<?php echo $_REQUEST['pass_catgroupid']?>&parentid=<?php echo $_REQUEST['pass_parentid']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=product_tab_td">List Product Categories</a> <a href="home.php?request=prod_cat&fpurpose=edit&checkbox[0]=<?php echo $pass_cat_id?>&catname=<?=$_REQUEST['pass_catname']?>&catgroupid=<?php echo $_REQUEST['pass_catgroupid']?>&parentid=<?php echo $_REQUEST['pass_parentid']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=product_tab_td"> Edit Product Category</a><span> Assign Products   for '<? echo $show_catname;?>'</span></div></td>
    </tr>
	 <tr>
	  <td align="left" valign="middle" class="helpmsgtd_main" colspan="3">
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
          			<td colspan="3" align="center" valign="middle" class="errormsg"><?php echo($alert);?></td>
          		</tr>
		 <?php
		 	}
		 ?>
	<?php
	  if($numcount)
	  {
	?> 
    <tr><td colspan="3" align="right" valign="middle" class="sorttd"><?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?></td></tr>
	<?php
	  }
	?>
	<tr>
      <td height="48" class="sorttd" colspan="3" >
	  <div class="sorttd_div">
      	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttableleft">
        <tr>
          <td width="22%" align="left" valign="middle">Product Name </td>
          <td colspan="3" align="left" valign="middle"><input name="search_name" type="text" class="textfeild" id="search_name" value="<?=$_REQUEST['search_name']?>"  /></td>
          </tr>
		  
		  <tr>
          <td width="22%" align="left" valign="middle">Category </td>
          <td colspan="3" align="left" valign="middle">  <?php
		  		
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
          <td width="12%" align="left">Show</td>
          <td width="41%" align="left"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/>
            <?=$page_type?> Per Page</td>
          <td width="47%" align="left">&nbsp;</td>
        </tr>
        <tr>
          <td align="left">Sort By</td>
          <td align="left" nowrap="nowrap"><?=$sort_option_txt?> in <?=$sort_by_txt?>  </td>
          <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;<input name="button5" type="submit" class="red" id="button5" value="Go" /> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_ASS_ADVERT_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
      </table>
	  </div>
	  </td>
    </tr>
        
    <tr>
      <td colspan="3" class="listingarea">
	  <div class="listingarea_div">
      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable" width="100%">
	  <tr>
	   <td class="listeditd" align="right" valign="middle" colspan="6">
	   
	  <?
	  if($numcount)
	  {
	  ?>
        
		
		<input name="assign_products" type="button" class="red" id="assign_products" value="Assign Products" onclick="return assignproducts();" />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_ASS_ADVERT_ASSIGN')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
		<?
		}
		?>
   	   </td>
    </tr>
       <?
	   	echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
	   $sql_products = "SELECT product_id,product_name FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
	   
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
          <td align="left" valign="middle" class="<?=$class_val;?>"><a href="home.php?request=products&fpurpose=edit&checkbox[0]=<?php echo $row['product_id']?>&search_name=<?php echo $_REQUEST['search_name']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>" title="<? echo $row['title']?>" class="edittextlink" onclick="show_processing()"><? echo $row['product_name']?></a></td>
         <td align="left" valign="middle" class="<?=$class_val;?>">
		 <?
		 $sql_advert="SELECT advert_title FROM adverts a,advert_display_product b WHERE b.products_product_id=".$row['product_id']." AND a.advert_id=b.adverts_advert_id";
		 $res_advert=$db->query($sql_advert);
		 $num_advert=$db->num_rows($res_advert);
		  if($num_advert) {
		  ?>
		  <select name="adverts">
		  <?
		  while($row_advert=$db->fetch_array($res_advert))
		  {
		  	echo "<option value=$row_advert[advert_title]>$row_advert[advert_title]</option>";
		  }
		  ?>
		  </select>
		 <?
		 }
		 else
		 {
		 	echo "No Banners assigned ";
		 }
		 ?>
		 </td>
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
	  <tr>
				  <td align="center" valign="middle" class="norecordredtext" colspan="6" >
				  	No UnAssigned Products exists.				  </td>
			</tr>
		<?
		}
		?>	
		
      </table>
	  </div></td>
    </tr>
	<tr>
	   <td class="listeditd"  align="right" valign="middle" colspan="6">
	  <?
	  if($numcount) {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?></td>
    </tr>
  
    </table>
</form>
