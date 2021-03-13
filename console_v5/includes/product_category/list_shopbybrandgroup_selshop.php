<?php
	/*#################################################################
	# Script Name 	: list_shopbybrandgroup_selshop.php
	# Description 	: Page for listing Shops to be assigned to shop group
	# Coded by 		: Sny
	# Created on	: 13-Nov-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
//Define constants for this page
$table_name				= 'product_shopbybrand';
$page_type				= 'Product Shops';
$cat_id			= ($_REQUEST['pass_cat_id']?$_REQUEST['pass_cat_id']:'0');
$help_msg 				= get_help_messages('LIST_CAT_SHOP_BRAND_MESS1');
$table_headers 			= array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmshops,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmshops,\'checkbox[]\')"/>','Slno.','Shop Name','Parent Shop','Shop in Groups','Active');
$header_positions		= array('left','left','left','left','left');
$colspan 				= count($table_headers);

$tabale = "product_categories";
$where  = "category_id=".$cat_id;
if(!server_check($tabale, $where)) {
	echo " <font color='red'> You Are Not Authorised  </a>";
	exit;
}

//#Search terms
$search_fields = array('title','parentid','shopgroupid');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by 			= (!$_REQUEST['sort_by'])?'shopbrand_name':$_REQUEST['sort_by'];
$sort_order 		= (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options 		= array('shopbrand_name' => 'Shop Name');
$sort_option_txt 	= generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt		= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions 	= "WHERE sites_site_id=$ecom_siteid ";

//#Avoiding already assigned page
$sql_assigned	= "SELECT shopbybrand_shopbybrand_id FROM category_shop_map 
					WHERE shopbybrand_category_id=".$cat_id." AND sites_site_id=".$ecom_siteid;
$ret_assigned 	= $db->query($sql_assigned);
$str_assigned	= '-1';

while($row_assigned = $db->fetch_array($ret_assigned))
{
	$str_assigned.=','.$row_assigned['shopbybrand_shopbybrand_id'];
	
}
$str_assigned='('.$str_assigned.')';	
$where_conditions.=" AND shopbrand_id NOT IN $str_assigned";


if($_REQUEST['search_name']) {
	$where_conditions .= "AND ( shopbrand_name LIKE '%".add_slash($_REQUEST['search_name'])."%')";
}
if($_REQUEST['parentid']=='')
		$_REQUEST['parentid'] = -1;
	if($_REQUEST['parentid']!=-1)
	{
		if($_REQUEST['parentid']!='')
		{
			$where_conditions .= " AND shopbrand_parent_id= ".$_REQUEST['parentid'];
		}	
	}
if($_REQUEST['shopgroupid'])
{
	// Find the ids of categories which fall under the selected category group
	$sql_shops 	= "SELECT product_shopbybrand_shopbrand_id FROM product_shopbybrand_group_shop_map WHERE product_shopbybrand_shopbrandgroup_id=".$_REQUEST['shopgroupid'];
	$ret_shops 	= $db->query($sql_shops);
	if($db->num_rows($ret_shops))
	{
		while($row_shops = $db->fetch_array($ret_shops))
		{
			$find_arr[] = $row_shops['product_shopbybrand_shopbrand_id'];
		}
		
		$where_conditions .= " AND shopbrand_id IN (".implode(',',$find_arr).") ";
	}
	else
		$where_conditions .= " AND shopbrand_id IN(-1) "; 
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
$query_string .= "request=prod_cat&pass_cat_id=".$cat_id."&fpurpose=shop_sel&sort_by=$sort_by&sort_order=$sort_order&records_per_page=$records_per_page&search_name=".$_REQUEST['search_name']."&start=$start";
$query_string .="&pass_shopgroupname=".$_REQUEST['pass_shopgroupname']."&pass_sort_by=".$_REQUEST['pass_sort_by']."&pass_sort_order=".$_REQUEST['pass_sort_order']."&pass_records_per_page=".$_REQUEST['pass_records_per_page']."&pass_start=".$_REQUEST['pass_start']."&pass_pg=".$_REQUEST['pass_pg'];

?>
<script language="javascript">
function call_save_selected(cname,sortby,sortorder,recs,start,pg,shopgroup_id) 
{
	
	var atleastone 			= 0;
	for(i=0;i<document.frmshops.elements.length;i++)
	{
		if (document.frmshops.elements[i].type =='checkbox' && document.frmshops.elements[i].name=='checkbox[]')
		{

			if (document.frmshops.elements[i].checked==true)
			{
				atleastone ++;
				
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select the Product Shop to assign');
	}
	
	else
	{
		if(confirm('Are you sure you want to assign selected Product Shop(s) ?'))
		{
			show_processing();
			document.frmshops.fpurpose.value='save_shop_sel';
			document.frmshops.submit();
		}	
	}	

}
</script>
<form name="frmshops" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="shop_sel" />
<input type="hidden" name="request" value="prod_cat" />
<input type="hidden" name="start" value="<?=$_REQUEST['start']?>" />
<input type="hidden" name="pg" value="<?=$_REQUEST['pg']?>" />
<input type="hidden" name="sort_by" value="<?=$_REQUEST['sort_by']?>" />
<input type="hidden" name="sort_order" value="<?=$_REQUEST['sort_order']?>" />
<input type="hidden" name="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
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
$sql_group = "SELECT  category_name FROM product_categories WHERE category_id=".$cat_id;
	$ret_group = $db->query($sql_group);
	if ($db->num_rows($ret_group))
	{
		$row_group 		= $db->fetch_array($ret_group);
		$show_catname	= stripslashes($row_group['category_name']);
	}
?>
  <table border="0" cellpadding="0" cellspacing="0" class="maintable" width="100%">
    <tr>
      <td colspan="3" align="left" valign="middle" class="treemenutd"><a href="home.php?request=prod_cat&catname=<?=$_REQUEST['pass_catname']?>&parentid=<?=$_REQUEST['pass_parentid']?>&catgroupid=<?=$_REQUEST['pass_catgroupid']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>">List Product Categories</a> &gt;&gt; <a href="home.php?request=prod_cat&fpurpose=edit&checkbox[0]=<?php echo $cat_id?>&catname=<?=$_REQUEST['pass_catname']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=shops_tab_td">Edit Product Category</a> &gt;&gt; Assign Shops to '<? echo $show_catname;?>'</td>
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
    <tr>
      <td height="48" class="sorttd" colspan="3" >
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttableleft">
        <tr>
          <td  align="left" valign="middle">Shop Name </td>
          <td  align="left" valign="middle"><input name="search_name" type="text" class="textfeild" id="search_name" value="<?=$_REQUEST['search_name']?>"  /></td>
        </tr>
		<tr>
		 <td  align="left" valign="middle">Shop Menu </td>
			    <td  align="left" valign="middle">
				<?php
			  	$top_group_arr = array(0=>'-- Any --');
				$sql_group = "SELECT   	shopbrandgroup_id,shopbrandgroup_name,shopbrandgroup_hide FROM product_shopbybrand_group WHERE sites_site_id=$ecom_siteid 
								ORDER BY shopbrandgroup_name";
				$ret_group = $db->query($sql_group);
				if ($db->num_rows($ret_group))
				{
					while ($row_group = $db->fetch_array($ret_group))
					{
						$id 		= $row_group['shopbrandgroup_id'];
						$hide 		= ($row_group['shopbrandgroup_hide']==1)?' (Hidden)':'';
						$top_group_arr[$id] = stripslashes($row_group['shopbrandgroup_name']).$hide;
					}
				}
				echo generateselectbox('shopgroupid',$top_group_arr,$_REQUEST['shopgroupid']);
			  ?></td> 
			  </tr>
			<tr>
			<td  align="left" valign="middle">&nbsp;Parent Shop </td>
			  <td  align="left" valign="middle">
			  <?php
			  	$parent_arr = generate_shop_tree(0,0,true,false,false,true);
				if(is_array($parent_arr))
				{
					echo generateselectbox('parentid',$parent_arr,$_REQUEST['parentid']);
				}
			  ?></td>
			</tr>
      
      </table>
	  <table width="37%" border="0" cellpadding="0" cellspacing="0" class="sorttableright">
        <tr>
          <td width="12%" align="left">Show</td>
          <td width="41%" align="left"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/>
            <?=$page_type?> Per Page</td>
          <td width="47%" align="left">&nbsp;</td>
        </tr>
        <tr>
          <td align="left">Sort By</td>
          <td align="left" nowrap="nowrap"><?=$sort_option_txt?> in <?=$sort_by_txt?>  </td>
          <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;<input name="button5" type="submit" class="red" id="button5" value="Go" /> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_SHOP_SHOP_BRAND_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> </td>
        </tr>
      </table>
	  </td>
    </tr>
    <tr>
      <td class="listeditd" width="250">&nbsp;
	  </td>
	   <td class="listeditd"  align="center">
	  <?
	  if($numcount)
	  {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?></td>
	   <td class="listeditd" align="right"><input name="change_hide" type="button" class="red" id="change_hide" onClick="call_save_selected()" value="Assign Selected">
	   
	  
   	  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_CAT_SHOP_BRAND_ASS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>  </td>
    </tr>
     
    <tr>
      <td colspan="3" class="listingarea">
      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable" width="100%">
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
	   $sql_page = "SELECT shopbrand_id,shopbrand_name,shopbrand_hide,shopbrand_parent_id FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
	   
	   $res = $db->query($sql_page);
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
          <td align="left" valign="middle" class="<?=$class_val;?>"  width="5%"><input name="checkbox[]" value="<? echo $row['shopbrand_id']?>" type="checkbox"></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"  width="2%"><?php echo $srno++?></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><a href="home.php?request=shopbybrand&fpurpose=edit&checkbox[0]=<?php echo $row['shopbrand_id']?>&search_name=<?php echo $_REQUEST['search_name']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>" title="<? echo stripslashes($row['shopbrand_name'])?>" class="edittextlink" onclick="show_processing()"><? echo stripslashes($row['shopbrand_name'])?></a></td>
         <td align="left" valign="middle" class="<?php echo $class_val?>">
		  <?php
		  	if ($row['shopbrand_parent_id']==0)
				echo ' - ';
			else
			{
				$sql_parent = "SELECT shopbrand_name FROM product_shopbybrand WHERE shopbrand_id=".$row['shopbrand_parent_id'];
				$ret_parent = $db->query($sql_parent);
				if ($db->num_rows($ret_parent))
				{
					$row_parent = $db->fetch_array($ret_parent);
					echo stripslashes($row_parent['shopbrand_name']);
				}
			}	
		  ?>		  </td>
		 <td align="left" valign="middle" class="<?=$class_val;?>">
		 <?
		 $sql_shop_group="SELECT shopbrandgroup_name FROM product_shopbybrand_group a,product_shopbybrand_group_shop_map b WHERE 
		 					b.product_shopbybrand_shopbrand_id=".$row['shopbrand_id']." AND a.shopbrandgroup_id=b.product_shopbybrand_shopbrandgroup_id";
		 $res_shop_group=$db->query($sql_shop_group);
		 $num_shop_group=$db->num_rows($res_shop_group);
		  if($num_shop_group)
		  {
		  ?>
		  <select name="shopbrand_group">
		  <?
		  while($row_shop_group=$db->fetch_array($res_shop_group))
		  {
		  	echo "<option value=>".$row_shop_group['shopbrandgroup_name']."</option>";
		  }
		  ?>
		  </select>
		 <?
		 }
		 else
		 {
		 	echo "Not Assigned to any group";
		 }
		 ?>
		 </td>
		 <td align="center" valign="middle" class="<?=$class_val;?>"><?php echo ($row['hide'] == 0)?'Yes':'No'; ?></td>
         </tr>
      <?
	  }
	  }
	  else
	  {
	  ?>
	  <tr>
				  <td align="center" valign="middle" class="norecordredtext" colspan="<?=$colspan?>" >
				  	No Shop exists.				  </td>
			</tr>
		<?
		}
		?>	
      </table></td>
    </tr>
	
  <tr>
      <td class="listeditd">&nbsp;
	  </td>
	   <td class="listeditd" width="162"  align="center">
	  <?
	  if($numcount)
	  {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?></td>
	   <td class="listeditd" align="right">&nbsp;
	 
   	   </td>
    </tr>
    </table>
</form>
