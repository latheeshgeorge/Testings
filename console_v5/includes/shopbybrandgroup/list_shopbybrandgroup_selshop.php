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
$shopgroup_id			= ($_REQUEST['pass_shopgroup_id']?$_REQUEST['pass_shopgroup_id']:'0');
$help_msg 				= get_help_messages('LIST_STAT_PAGE_SHOP_BRAND_MESS1');
$table_headers 			= array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmshops,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmshops,\'checkbox[]\')"/>','Slno.','Shop Name','Parent Shop','Shop in Groups','Active');
$header_positions		= array('left','left','left','left','left');
$colspan 				= count($table_headers);

$tabale = "product_shopbybrand_group";
$where  = "shopbrandgroup_id=".$shopgroup_id;
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
$sql_assigned	= "SELECT product_shopbybrand_shopbrand_id FROM product_shopbybrand_group_shop_map 
					WHERE product_shopbybrand_shopbrandgroup_id=".$shopgroup_id;
$ret_assigned 	= $db->query($sql_assigned);
$str_assigned	= '-1';

while($row_assigned = $db->fetch_array($ret_assigned))
{
	$str_assigned.=','.$row_assigned['product_shopbybrand_shopbrand_id'];
	
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
$query_string .= "request=shopbybrandgroup&pass_shopgroup_id=".$shopgroup_id."&fpurpose=shop_sel&sort_by=$sort_by&sort_order=$sort_order&records_per_page=$records_per_page&search_name=".$_REQUEST['search_name']."&start=$start";
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
<input type="hidden" name="request" value="shopbybrandgroup" />
<input type="hidden" name="start" value="<?=$_REQUEST['start']?>" />
<input type="hidden" name="pg" value="<?=$_REQUEST['pg']?>" />
<input type="hidden" name="sort_by" value="<?=$_REQUEST['sort_by']?>" />
<input type="hidden" name="sort_order" value="<?=$_REQUEST['sort_order']?>" />
<input type="hidden" name="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
<input type="hidden" name="pass_shopgroupname" value="<?=$_REQUEST['pass_shopgroupname']?>" />
<input type="hidden" name="pass_shopgroup_id" value="<?=$shopgroup_id?>" />
<input type="hidden" name="pass_shopname" value="<?=$_REQUEST['pass_shopname']?>" />
<input type="hidden" name="pass_sort_by" value="<?=$_REQUEST['pass_sort_by']?>" />
<input type="hidden" name="pass_sort_order" value="<?=$_REQUEST['pass_sort_order']?>" />
<input type="hidden" name="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
<input type="hidden" name="pass_start" value="<?=$_REQUEST['pass_start']?>" />
<input type="hidden" name="pass_pg" value="<?=$_REQUEST['pass_pg']?>" />
<?
$sql_group = "SELECT shopbrandgroup_name FROM product_shopbybrand_group WHERE shopbrandgroup_id=".$shopgroup_id;
	$ret_group = $db->query($sql_group);
	if ($db->num_rows($ret_group))
	{
		$row_group 		= $db->fetch_array($ret_group);
		$show_groupname	= stripslashes($row_group['shopbrandgroup_name']);
	}
?>
  <table border="0" cellpadding="0" cellspacing="0" class="maintable" width="100%">
    <tr>
      <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=shopbybrandgroup&shopgroupname=<?=$_REQUEST['pass_shopgroupname']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>">List Product Shop Groups</a>  <a href="home.php?request=shopbybrandgroup&fpurpose=edit&checkbox[0]=<?php echo $shopgroup_id?>&shopgroupname=<?=$_REQUEST['pass_shopgroupname']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=shop_tab_td">Edit Product Shop Group</a><span> Assign Shops to '<? echo $show_groupname;?>'</span></div></td>
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
		 <td class="sorttd"  align="right" colspan="3">
	  <?
	  if($numcount)
	  {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?></td>
	  </tr>
    <tr>
      <td height="48" class="sorttd" colspan="3" >
		 <div class="editarea_div">
		<table width="100%" border="0" cellpadding="2" cellspacing="2" >
        <tr>
          <td  align="right" valign="middle">Shop Name </td>
          <td  align="left" valign="middle"><input name="search_name" type="text" class="textfeild" id="search_name" value="<?=$_REQUEST['search_name']?>"  /></td>
       
		 <td  align="right" valign="middle">Shop Menu </td>
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
			 
			<td  align="right" valign="middle">&nbsp;Parent Shop </td>
			  <td  align="left" valign="middle">
			  <?php
			  	$parent_arr = generate_shop_tree(0,0,true,false,false,true);
				if(is_array($parent_arr))
				{
					echo generateselectbox('parentid',$parent_arr,$_REQUEST['parentid']);
				}
			  ?></td>
			   <td  align="right">Show</td>
          <td  align="left"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/>
            <?=$page_type?> Per Page</td>
			</tr>      
     
        <tr>
          
       
          <td colspan="7" align="right">Sort By&nbsp;<?=$sort_option_txt?> in <?=$sort_by_txt?></td>
          <td align="right">&nbsp;
            <input name="button5" type="submit" class="red" id="button5" value="Go" />
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_SHOP_SHOP_BRAND_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
      </table>
      </div>
	  </td>
    </tr>
   
     
    <tr>
      <td colspan="3" class="listingarea">
		  		  		  			  <div class="editarea_div">

      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable" width="100%">
		   <tr>
     
	   
	   <td class="listeditd" align="right" colspan="6"><input name="change_hide" type="button" class="red" id="change_hide" onClick="call_save_selected()" value="Assign Selected">
	   
	  
   	  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_SHOP_SHOP_BRAND_ASS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>  </td>
    </tr>
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
		
      </table>
      </div>
      </td>
    </tr>
	 <tr>
      
	   <td class="listing_bottom_paging"  align="right" colspan="6">
	  <?
	  if($numcount)
	  {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?></td>
	  
    </tr>
 
    </table>
</form>
