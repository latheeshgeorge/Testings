<?php
	/*#################################################################
	# Script Name 	: list_assign_discgrp_category.php
	# Description 	: Page for listing categories to be assigned to customer discount groups
	# Coded by 		: Sny
	# Created on	: 24-Aug-2009
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
//Define constants for this page
$table_name='product_categories';
$page_type='Categories';
$pass_cust_disc_grp_id = $_REQUEST['pass_cust_disc_grp_id'];
$tabale = "customer_discount_group";
$where  = "cust_disc_grp_id=".$pass_cust_disc_grp_id;
if(!server_check($tabale, $where)) {
	echo " <font color='red'> You Are Not Authorised  </a>";
	exit;
}	

$help_msg =get_help_messages('LIST_CAT_ASS_ADVERT_MESS1');
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistCategories,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistCategories,\'checkbox[]\')"/>','Slno.','Category Name','Parent Category','Active');
$header_positions=array('left','left','left','left','left');
$colspan = count($table_headers);

//#Search terms.
$search_fields = array('search_name','parentid','catgroupid');
foreach($search_fields as $v) {
	$query_string .= "$v=${$v}&";#For passing searh terms to javascript for passing to different pages.
}
if($_REQUEST['catgroupid'])
	{
		// Find the ids of categories which fall under the selected category group
		$sql_cats 	= "SELECT category_id FROM product_categorygroup_category WHERE catgroup_id=".$_REQUEST['catgroupid'];
		$ret_cats 	= $db->query($sql_cats);
		if($db->num_rows($ret_cats))
		{
			while($row_cats = $db->fetch_array($ret_cats))
			{
				$find_arr[] = $row_cats['category_id'];
			}
			
			$where_conditions .= " AND category_id IN (".implode(',',$find_arr).") ";
		}
		else
			$where_conditions .= " AND category_id IN(-1) "; 
	}
//#Sort.
$sort_by = (!$_REQUEST['sort_by'])?'category_name':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('category_name' => 'Category Name');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options.
$where_conditions = "WHERE sites_site_id=$ecom_siteid AND parent_id = 0 ";
/*
if($assigned_categories_str!=''){
$where_conditions .=" AND category_id NOT IN ($assigned_categories_str)";
}
*/ 
if($_REQUEST['search_name']) {
	$where_conditions .= "AND (category_name LIKE '%".add_slash($_REQUEST['search_name'])."%')";
}
if($_REQUEST['catgroupid'])
{
	// Find the ids of categories which fall under the selected category group
	$sql_cats 	= "SELECT category_id FROM product_categorygroup_category WHERE catgroup_id=".$_REQUEST['catgroupid'];
	$ret_cats 	= $db->query($sql_cats);
	if($db->num_rows($ret_cats))
	{
		while($row_cats = $db->fetch_array($ret_cats))
		{
			$find_arr[] = $row_cats['category_id'];
		}
		
		$where_conditions .= " AND category_id IN (".implode(',',$find_arr).") ";
	}
	else
		$where_conditions .= " AND category_id IN(-1) "; 
}
if($_REQUEST['parentid']=='')
		$_REQUEST['parentid'] = -1;
	if($_REQUEST['parentid']!=-1)
	{
		if($_REQUEST['parentid']!='')
		{
			$where_conditions .= " AND parent_id= ".$_REQUEST['parentid'];
		}	
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
$query_string .= "pass_cust_disc_grp_id=$pass_cust_disc_grp_id&sort_by=$sort_by&sort_order=$sort_order&request=cust_discount_group&fpurpose=add_categories&records_per_page=$records_per_page&search_name=".$_REQUEST['search_name']."&start=$start&pass_sort_by=".$_REQUEST['pass_sort_by']."&pass_sort_order=".$_REQUEST['pass_sort_order']."&pass_records_per_page=".$_REQUEST['pass_records_per_page']."&pass_search_name=".$_REQUEST['pass_search_name']."";

?>
<script language="javascript">

function assigncategories()
{
	var atleastone 			= 0;
	var curid				= 0;
	var category_ids		= '';
	var cat_orders			= '';
	//var ch_status			= document.frmlistPage.cbo_changehide.value;
	
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlistCategories.elements.length;i++)
	{
		if (document.frmlistCategories.elements[i].type =='checkbox' && document.frmlistCategories.elements[i].name=='checkbox[]')
		{

			if (document.frmlistCategories.elements[i].checked==true)
			{
			
				atleastone = 1;
				if (category_ids!='')
					category_ids += '~';
				 category_ids += document.frmlistCategories.elements[i].value;
			}	
		}
	}
	if (atleastone==0) 
	{
		alert('Please select the Categories to assign to the Customer Discount Group');
		return false;
	}
	else
	{
		if(confirm('Assign Categories to the Customer Discount Group?'))
		{
				show_processing();
				document.frmlistCategories.fpurpose.value='save_add_categories';
				document.frmlistCategories.submit();
		}	
	}	
}

</script>
<link rel="stylesheet" type="text/css" href="Tree_menu/simpletree.css" />

<script type="text/javascript" src="Tree_menu/simpletreemenu.js">
</script>
<form name="frmlistCategories" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="add_categories" />
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
  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=cust_discount_group&pass_cust_disc_grp_id=<?=$pass_cust_disc_grp_id?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&search_name=<?=$_REQUEST['pass_group_name']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>">List Customer Discount Groups</a> <a href="home.php?request=cust_discount_group&fpurpose=edit&checkbox[0]=<?=$pass_cust_disc_grp_id?>&pass_cust_disc_grp_id=<?=$pass_cust_disc_grp_id?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_group_name=<?=$_REQUEST['pass_group_name']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=catmenu_tab_td">Edit Customer Discount Groups </a><span> Assign Categories</span></div></td>
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
	<tr>
		<td colspan="3" align="right" valign="middle" class="sorttd"><?php	paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?></td>
	</tr>
	<?php }
	?>
    <tr>
      <td height="48" class="sorttd" colspan="3" >
	  <div class="editarea_div">
      	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttabletop">
        <?php
        /*
        <tr>
          <td width="6%" height="30" align="left" valign="middle">Category Name </td>
          <td width="6%" height="30" align="left" valign="middle"><input name="search_name" type="text" class="textfeild" id="search_name" value="<?=$_REQUEST['search_name']?>"  /></td>
          <td width="6%" height="30" align="left" valign="middle">Parent Category </td>
          <td width="6%" height="30" align="left" valign="middle"><?php
			  	$parent_arr = generate_category_tree_special(0,0,true,false,false,true);
				if(is_array($parent_arr))
				{
					echo generateselectbox('parentid',$parent_arr,$_REQUEST['parentid']);
				}
			?></td>
          <td width="6%" height="30" align="left" valign="middle">Category Menu </td>
          */
          ?>
          <tr> 
           <td width="13%" align="left" valign="middle">Category Menu </td>
          <td width="16%" height="30" align="left" valign="middle"><?php
			  	$top_group_arr = array(0=>'-- Any --');
				$sql_group = "SELECT catgroup_id,catgroup_name,catgroup_hide FROM product_categorygroup WHERE sites_site_id=$ecom_siteid 
								ORDER BY catgroup_name";
				$ret_group = $db->query($sql_group);
				if ($db->num_rows($ret_group))
				{
					while ($row_group = $db->fetch_array($ret_group))
					{
						$id 		= $row_group['catgroup_id'];
						$hide 		= ($row_group['catgroup_hide']==1)?' (Hidden)':'';
						$top_group_arr[$id] = stripslashes($row_group['catgroup_name']).$hide;
					}
				}
				echo generateselectbox('catgroupid',$top_group_arr,$_REQUEST['catgroupid']);
			  ?></td>
          </tr>
        <tr>
          <td height="30" align="left" valign="middle">Records Per Page </td>
          <td height="30" align="left" valign="middle"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/></td>
          <td height="30" align="left" valign="middle">Sort By</td>
          <td height="30" align="left" valign="middle"><?=$sort_option_txt?>
            &nbsp;&nbsp;&nbsp;in&nbsp;&nbsp;&nbsp;
            <?=$sort_by_txt?></td>
          <td height="30" align="left" valign="middle">&nbsp;</td>
          <td height="30" align="right" valign="middle"><input name="button5" type="submit" class="red" id="button5" value="Go" />
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_CAT_ASS_ADVERT_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          </tr>
      </table>
	  </div>
      </td>
    </tr>
    
     
    <tr>
      <td colspan="3" class="listingarea">
	  <div class="listingarea_div">
      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable">
	  <?php
	  if($numcount)
	  {
	  ?>
	  <tr>
	   <td class="listeditd" align="right" colspan="5">
		<input name="assign_categories" type="submit" class="red" id="assign_categories" value="Assign Categories" onclick="return assigncategories();" />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_CAT_ASS_ADVERT_ASSCAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
   	   </td>
    </tr>
	<?php
		}
		function show_subcat_tree($cat_id,$check_arr=array())
		{ 
				global $db,$ecom_siteid,$ecom_themeid ;

		// Check whether first level subcategories exists for current subcategory
		 $sql_subsubcat = "SELECT category_id,category_name 
			FROM 
				product_categories 
			WHERE 
				parent_id =".$cat_id." 
				AND sites_site_id = $ecom_siteid 
				AND category_hide = 0 
			ORDER BY 
				category_order";
		$ret_subsubcat = $db->query($sql_subsubcat);
		$num_subrows	= $db->num_rows($ret_subsubcat);
		if($num_subrows)
		{
			?>
			<ul>
			<?php
			while ($row_subsubcat = $db->fetch_array($ret_subsubcat))
			{
				$cat_id = $row_subsubcat['category_id'];
				if(!in_array($cat_id,$check_arr))
				{
					$chk_box = "<input name=\"checkbox[]\" value=\"".$cat_id."\" type=\"checkbox\" style=\"float:left; margin:0px; padding:0px; position:relative; right:-22px; top:2px;\"></span>";
			    }
			     else
			    {
				    $chk_box = "<img src=\"Tree_menu/tick.png\" style=\"float:left; margin:0px; padding:0px; position:relative; right:-22px; top:2px;\">";
				}
		?>			
				<?php echo $chk_box?><li style="padding-left:40px;"><a href="home.php?request=prod_cat&fpurpose=edit&checkbox[0]=<?php echo $row_subsubcat['category_id']?>&search_name=<?php echo $_REQUEST['search_name']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>" title="<? echo $row['title']?>" class="edittextlink" onclick="show_processing()"><?php echo stripslashes($row_subsubcat['category_name'])?></a>				
				<?php
				show_subcat_tree($cat_id,$check_arr)
				?>
				</li>
					<?php
			}
			?>
			</ul>
			<?php
		}
		else
		{
		   return;
		}

		}
		/*
	?>
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   { 
	  $sql_page = "SELECT category_id,category_name,parent_id FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
	   
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
          <td align="left" valign="middle" class="<?=$class_val;?>"  width="5%"><input name="checkbox[]" value="<? echo $row['category_id']?>" type="checkbox"></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"  width="2%"><?php echo $srno++?></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><a href="home.php?request=prod_cat&fpurpose=edit&checkbox[0]=<?php echo $row['category_id']?>&search_name=<?php echo $_REQUEST['search_name']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>" title="<? echo $row['title']?>" class="edittextlink" onclick="show_processing()"><? echo $row['category_name']?></a></td>
         <td align="left" valign="middle" class="<?=$class_val;?>">
		 <?
		  $sql_advert="SELECT category_name FROM product_categories WHERE category_id=".$row['parent_id'];
		  $res_advert=$db->query($sql_advert);
		  $num_advert=$db->num_rows($res_advert);
		  if($num_advert) {
		  
		  while($row_adverts=$db->fetch_array($res_advert))
		  {
		  	echo "<option value=".$row_adverts['category_name'].">".$row_adverts['category_name']."</option>";
		  }
		 }
		 else
		 {
		 	echo "--Root Level--";
		 }
		 ?>
		 </td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo ($row['hide'] == 0)?'Yes':'No'; ?></td>
         
        </tr>
      	<?
	  	}
	  }
	  */ 
	  //echo table_header($table_headers,$header_positions);
	   if($numcount)
	   { 
	   $sql_page = "SELECT category_id,category_name,parent_id FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
	   
	   $res = $db->query($sql_page);
	   $srno = 1; 
	   ?>
	    <tr >
			<td colspan="5" class="listingtablestyleBB">
				<div >
	   <ul id="treemenu1" class="treeview">
	   <?php
	   
	   $check_arr = array();
		if($pass_cust_disc_grp_id>0)
		{
			// Finding already assigned Categories in the Adverts.
			$sql_assigned_categories = "SELECT product_categories_category_id 
											FROM 
												customer_discount_group_categories_map 
											WHERE 
												customer_discount_group_cust_disc_grp_id =".$pass_cust_disc_grp_id." 
												AND sites_site_id=".$ecom_siteid;
			$res_assigned_categories = $db->query($sql_assigned_categories);
			$assigned_categories_str = '';
			while($assigned_categories = $db->fetch_array($res_assigned_categories)){

				$check_arr[] =  $assigned_categories['product_categories_category_id'];
			}
		}
		
	   while($row = $db->fetch_array($res))
	   {
		   $chk_box = "";
			$count_no++;
			$cat_id =$row['category_id'];
			$array_values = array();
			if($count_no %2 == 0)
				$class_val="listingtablestyleA";
			else
				$class_val="listingtablestyleB";	
				if(!in_array($cat_id,$check_arr))
				{
					$chk_box = "<input name=\"checkbox[]\" value=\"".$cat_id."\" type=\"checkbox\" style=\"float:left; margin:0px; padding:0px; position:relative; right:-22px; top:2px;\">";
			    }
			    else
			    {
				    $chk_box = "<img src=\"Tree_menu/tick.png\" style=\"float:left; margin:0px; padding:0px; position:relative; right:-22px; top:2px;\">";
				}
				?>
				<?php echo $chk_box?>
			<li style="padding-left:40px;"><a href="home.php?request=prod_cat&fpurpose=edit&checkbox[0]=<?php echo $row['category_id']?>&search_name=<?php echo $_REQUEST['search_name']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>" title="<? echo $row['title']?>" class="edittextlink" onclick="show_processing()"><? echo $row['category_name']?></a>
	
			<?php
			
			show_subcat_tree($cat_id,$check_arr);				
			?>	
      	</li>

      	<?PHP
	  	}
	  	?>
	     </ul>
</div>
	  	</td>
	  	</tr>
	  	<?php
	  }
	  else
	  {
	  ?>
	  <tr>
				  <td align="center" valign="middle" class="norecordredtext" colspan="5" >
				  	No Product Category exists.				  </td> 
		  </tr>
		<?
		}
		?>	
		 <?php
		  if($numcount)
		  {
		  ?>
		  <tr>
		   <td class="listeditd" align="right" colspan="5"><?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?>  </td>
		</tr>
		<?php
			}
		?>
      </table>
	  </div></td>
    </tr>
  </table>
</form>
<script type="text/javascript">

//ddtreemenu.createTree(treeid, enablepersist, opt_persist_in_days (default is 1))

ddtreemenu.createTree("treemenu1", false)


</script>
