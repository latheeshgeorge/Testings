<?php
	/*#################################################################
	# Script Name 	: list_sel_discgrp_product.php
	# Description 		: Page for Assigning Products to Group
	# Coded by 		: ANU
	# Created on		: 1-Mar-2008
	# Modified by		: 
	# Modified On		: 
	#################################################################*/
//Define constants for this page
$table_name='products';
$page_type='Products';
$help_msg = get_help_messages('LIST_PROD_DISC_CUST_GROUP_MESS1');
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistProducts,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistProducts,\'checkbox[]\')"/>','Slno.','Product Name','Price','Discount','Hide');
$header_positions=array('left','left','left','left','left','left','left','left','left');
$colspan = count($table_headers);
$pass_cust_disc_grp_id=($_REQUEST['pass_cust_disc_grp_id']?$_REQUEST['pass_cust_disc_grp_id']:'0');

$tabale = "customer_discount_group";
$where  = "cust_disc_grp_id=".$pass_cust_disc_grp_id;
if(!server_check($tabale, $where)) {
	echo " <font color='red'> You Are Not Authorised  </a>";
	exit;
}	

//#Search terms
$search_fields = array('search_name');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'product_name':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('product_name' => 'Product Name');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
//#Avoiding already assigned product
//$sql_assigned="SELECT 
$sql_assigned="SELECT products_product_id FROM customer_discount_group_products_map WHERE sites_site_id =  ".$ecom_siteid." AND customer_discount_group_cust_disc_grp_id = ".$pass_cust_disc_grp_id;
$ret_assigned = $db->query($sql_assigned);
$str_assigned='-1';

while($row_assigned = $db->fetch_array($ret_assigned))
{
	$str_assigned 	.= ','.$row_assigned['products_product_id'];
}
$str_assigned 		= '('.$str_assigned.')';	
$where_conditions 	.= " AND product_id NOT IN $str_assigned";

if($_REQUEST['search_name']) {
	$where_conditions .= "AND ( product_name LIKE '%".add_slash($_REQUEST['search_name'])."%')";
}
if($_REQUEST['categoryid']) // case if category is selected
{
	$catinclude_prod = array();
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
	if(count($catinclude_prod))
	{
		$where_conditions .=  "AND product_id IN(".implode(',',$catinclude_prod).")";
	}	
	else
		$where_conditions .=  "AND product_id IN(-1)";
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
$start = ($pg - 1) * $records_per_page;#Starting record.
$count_no = ($pg == 1)?0:$records_per_page*($pg-1);
/////////////////////////////////////////////////////////////////////////////////////
$query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=cust_discount_group&fpurpose=add_products&records_per_page=$records_per_page&start=$start";
$query_string .= "&pass_sortby=".$_REQUEST['pass_sortby']."&pass_sortorder=".$_REQUEST['pass_sortorder']."&pass_group_name=".$_REQUEST['pass_group_name']."&pass_records_per_page=".$_REQUEST['pass_records_per_page']."&pass_start=".$_REQUEST['pass_start']."&pass_pg=".$_REQUEST['pass_pg']."";
$query_string .="&pass_cust_disc_grp_id=$pass_cust_disc_grp_id";

?>
<script>
function call_save_selected(cname,sortby,sortorder,recs,start,pg,group_id) 
{
	
	var atleastone 			= 0;
	for(i=0;i<document.frmlistProducts.elements.length;i++)
	{
		if (document.frmlistProducts.elements[i].type =='checkbox' && document.frmlistProducts.elements[i].name=='checkbox[]')
		{

			if (document.frmlistProducts.elements[i].checked==true)
			{
				atleastone ++;
				
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select the Products  to assign');
	}
	
	else
	{
		if(confirm('Are you sure you want to assign selected Products ?'))
		{
			show_processing();
			document.frmlistProducts.fpurpose.value='save_add_products';
			document.frmlistProducts.submit();
		}	
	}	

}
</script>
<form name="frmlistProducts" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="add_products" />
<input type="hidden" name="request" value="cust_discount_group" />
<input type="hidden" name="start" value="<?=$start?>" />
<input type="hidden" name="pg" value="<?=$pg?>" />
<input type="hidden" name="pass_cust_disc_grp_id" value="<?=$pass_cust_disc_grp_id?>" />
<input type="hidden" name="pass_group_name" value="<?=$_REQUEST['pass_group_name']?>" />
<input type="hidden" name="pass_sortby" value="<?=$_REQUEST['pass_sortby']?>" />
<input type="hidden" name="pass_sortorder" value="<?=$_REQUEST['pass_sortorder']?>" />
<input type="hidden" name="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
<input type="hidden" name="pass_start" value="<?=$_REQUEST['pass_start']?>" />
<input type="hidden" name="pass_pg" value="<?=$_REQUEST['pass_pg']?>" /> 
 <table border="0" cellpadding="0" cellspacing="0" class="maintable" width="100%">
   <tr>
          <td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=cust_discount_group&pass_cust_disc_grp_id=<?=$pass_cust_disc_grp_id?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&search_name=<?=$_REQUEST['pass_group_name']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>">List Customer Discount Groups</a> <a href="home.php?request=cust_discount_group&fpurpose=edit&checkbox[0]=<?=$pass_cust_disc_grp_id?>&pass_cust_disc_grp_id=<?=$pass_cust_disc_grp_id?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_group_name=<?=$_REQUEST['pass_group_name']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=prodmenu_tab_td">Edit Customer Discount Groups </a><span> Assign Product</span></div></td>
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
      <td height="48" class="sorttd" colspan="2" align="right" >
	   <?
	  if($numcount)
	  {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?>
	  </td>
	</tr>
    <tr>
      <td class="sorttd" colspan="2" >
	 <div class="editarea_div">
      	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttabletop">
        <tr>
          <td width="7%" height="30" align="left" valign="middle">Product Name </td>
          <td width="11%" height="30" align="left" valign="middle"><input name="search_name" type="text" class="textfeild" id="search_name" value="<?=$_REQUEST['search_name']?>"  /></td>
          <td width="5%" height="30" align="left" valign="middle">Category</td>
          <td width="23%" height="30" align="left" valign="middle"><?php
			  	$cat_arr = generate_category_tree(0,0,true);
				if(is_array($cat_arr))
				{
					echo generateselectbox('categoryid',$cat_arr,$_REQUEST['categoryid']);
				}
			?></td>
          <td width="9%" height="30" align="left" valign="middle">Records Per Page </td>
          <td width="4%" height="30" align="left" valign="middle"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/></td>
          <td width="4%" height="30" align="left" valign="middle">Sort By</td>
          <td width="18%" height="30" align="left" valign="middle"><?=$sort_option_txt?>&nbsp;&nbsp;&nbsp;in&nbsp;&nbsp;&nbsp;  <?=$sort_by_txt?></td>
          <td width="8%" height="30" align="left" valign="middle">&nbsp;&nbsp;&nbsp;
            <input name="button5" type="submit" class="red" id="button5" value="Go" />
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_CUST_DISC_GROUP_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          </tr>
      </table>
	  </div>
      </td>
    </tr>
     
    <tr>
      <td colspan="2" class="listingarea">
	  <div class="listingarea_div">
      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable" width="100%">
	  <tr>
		  <td align="right" valign="middle" class="listeditd" colspan="<?=$colspan?>" >
		  <input name="change_hide" type="button" class="red" id="change_hide" onClick="call_save_selected()" value="Assign Selected">
      &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_CUST_DISC_GROUP_ASS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
	  </td>
	</tr>
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
	   $sql_products = "SELECT product_id,product_name,product_webprice,product_discount,product_discount_enteredasval FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
	   
	   $res = $db->query($sql_products);
	    $srno = getStartOfPageno($records_per_page,$pg); 
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
					$discval_arr= explode(".",$row_qry['product_discount']);
					if($discval_arr[1]=='00')
						$discval = $discval_arr[0];
					else
						$discval = $row['product_discount'];
					$disc		= $discval.$disctype;
					if(($row['product_discount_enteredasval']==1))
					{
					 $disc = display_price($disc);
					}
				}	
				else
					$disctype = $disc = '--';
	   ?>
        <tr >
          <td align="left" valign="middle" class="<?=$class_val;?>"  width="6%"><input name="checkbox[]" value="<? echo $row['product_id']?>" type="checkbox"></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"  width="2%"><?php echo $srno++?></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><a href="home.php?request=products&fpurpose=edit&checkbox[0]=<?php echo $row['product_id']?>&search_name=<?php echo $_REQUEST['search_name']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>" title="<? echo $row['product_name']?>" class="edittextlink" onclick="show_processing()"><? echo $row['product_name']?></a></td>
    	  <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo display_price($row['product_webprice']); ?></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo $disc; ?></td>
		  
		  <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo ($row['product_hide'] == 'Y')?'Yes':'No'; ?></td>
        </tr>
      <?
	  }
	  }
	  else
	  {
	  ?>
	  <tr>
				  <td align="center" valign="middle" class="norecordredtext" colspan="<?=$colspan?>" >
				  	No Product to add in this GROUP exists.				  </td>
			</tr>
		<?
		}
		?>	
		<tr>
		  <td align="right" valign="middle" class="listeditd" colspan="<?=$colspan?>" >
		   <?
		  if($numcount)
		  {
			paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
		  }
		  ?>
		  </td>
		</tr>
      </table>
	  </div></td>
    </tr>
    </table>
</form>
