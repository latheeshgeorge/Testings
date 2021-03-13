<?php
	/*#################################################################
	# Script Name 	: list_assign_categories.php
	# Description 	: Page for listing categories for assiging into the Adverts
	# Coded by 		: ANU
	# Created on	: 10-July-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
//Define constants for this page
$table_name='product_categories';
$page_type='Categories';
$help_msg =get_help_messages('LIST_CAT_ASS_ADVERT_MESS1');
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistCategories,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistCategories,\'checkbox[]\')"/>','Slno.','Category Name','Banners assigned for the Category','Active');
$header_positions=array('left','left','left','left','left');
$colspan = count($table_headers);
($_REQUEST['pass_groupid']>0)?$group_id=$_REQUEST['pass_groupid']:$group_id=0;
//$group_id=($_REQUEST['pass_group_id']?$_REQUEST['pass_group_id']:'0');

	$tabale = "adverts";
	$where  = "advert_id=".$group_id;
	if(!server_check($tabale, $where)) {
		echo " <font color='red'> You Are Not Authorised  </a>";
		exit;
	}

// Finding already assigned Categories in the Adverts.
$sql_assigned_categories = "SELECT product_categories_category_id FROM advert_display_category 
									WHERE adverts_advert_id =".$group_id." AND sites_site_id=".$ecom_siteid;
$res_assigned_categories = $db->query($sql_assigned_categories);
$assigned_categories_str = '';
while($assigned_categories = $db->fetch_array($res_assigned_categories)){
if($assigned_categories_str !=''){
$assigned_categories_str= $assigned_categories_str.',';
}
$assigned_categories_str .=  $assigned_categories['product_categories_category_id'];
}
//#Search terms.
$search_fields = array('category_name','catgroupid','parentid');
foreach($search_fields as $v) {
	$query_string .= "$v=${$v}&";#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort.
$sort_by = (!$_REQUEST['sort_by'])?'category_name':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('category_name' => 'Category Name');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options.
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
if($assigned_categories_str!=''){
$where_conditions .=" AND category_id NOT IN ($assigned_categories_str)";
}
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
$query_string .= "advert_id=".$_REQUEST['advert_id']."&pass_groupid=$group_id&sort_by=$sort_by&sort_order=$sort_order&request=adverts&fpurpose=list_assign_categories&records_per_page=$records_per_page&search_name=".$_REQUEST['search_name']."&advert_title=".$_REQUEST['advert_title']."&start=$start&pass_sort_by=".$_REQUEST['pass_sort_by']."&pass_sort_order=".$_REQUEST['pass_sort_order']."&pass_records_per_page=".$_REQUEST['pass_records_per_page']."&pass_search_name=".$_REQUEST['pass_search_name']."";

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
		alert('Please select the Categories to assign to the Banner');
		return false;
	}
	else
	{
		if(confirm('Assign Categories to the Banner?'))
		{
				show_processing();
				//alert('fpurpose=assign_pages&'+qrystr+'&page_ids='+page_ids);
				//Handlewith_Ajax('services/static_group.php','fpurpose=assign_pages&'+qrystr+'&page_ids='+page_ids);
				document.frmlistCategories.category_ids.value=category_ids;
				document.frmlistCategories.fpurpose.value='assign_categories';
				document.frmlistCategories.submit();
		}	
	}	
}

</script>
<form name="frmlistCategories" action="home.php?request=adverts" method="post" >	
<input type="hidden" name="fpurpose" value="list_assign_categories" />
<input type="hidden" name="request" value="adverts" />
  <input type="hidden" name="category_ids" id="category_ids" value="" />
  <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
  <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
  <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
  <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
  <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />  
  <input type="hidden" name="advert_id" id="advert_id" value="<?=$group_id?>" />
  <input type="hidden" name="pass_groupid" id="pass_groupid" value="<?=$group_id?>" />  
  <input type="hidden" name="advert_title" id="advert_title" value="<?=$_REQUEST['advert_title']?>" />  
   <input type="hidden" name="pass_search_name" id="pass_search_name" value="<?=$_REQUEST['pass_search_name']?>" />
   <input type="hidden" name="pass_start" id="pass_start" value="<?=$_REQUEST['pass_start']?>" />
  <input type="hidden" name="pass_sort_by" id="pass_sort_by" value="<?=$_REQUEST['pass_sort_by']?>" />
  <input type="hidden" name="pass_sort_order" id="pass_sort_order" value="<?=$_REQUEST['pass_sort_order']?>" />
  <input type="hidden" name="pass_records_per_page" id="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
  <input type="hidden" name="pass_pg" id="pass_pg" value="<?=$_REQUEST['pass_pg']?>" />
  <table border="0" cellpadding="0" cellspacing="0" class="maintable" width="100%">
    <tr>
      <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=adverts&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&search_name=<?=$_REQUEST['pass_search_name']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&advert_id=<?=$group_id?>">List Banners</a> <a href="home.php?request=adverts&fpurpose=edit&advert_id=<?=$group_id?>&advert_title=<?=$_REQUEST['advert_title']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&search_name=<?=$_REQUEST['pass_search_name']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_start=<?=$_REQUEST['pass_start']?>&pass_pg=<?=$_REQUEST['pass_pg']?>&curtab=category_tab_td">Edit Banner</a><span> Assign Categories to the Banner : <b>  '<?=$_REQUEST['advert_title']?>' </b></span></div></td>
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
		<td colspan="3" align="right" valign="middle" class="sorttd"><?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?> </td>
	</tr>
	<?php
		  }
	?>
	<tr>
      <td height="48" class="sorttd" colspan="3" >
	  <div class="sorttd_div">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="9%" align="left" valign="middle">Category Name </td>
          <td width="19%" align="left" valign="middle"><input name="search_name" type="text" class="textfeild" id="search_name" value="<?=$_REQUEST['search_name']?>"  /></td>
          <td width="10%" align="left" valign="middle">Parent Category </td>
          <td colspan="4" align="left" valign="middle"><?php
			  	$parent_arr = generate_category_tree_special(0,0,true,false,false,true);
				if(is_array($parent_arr))
				{
					echo generateselectbox('parentid',$parent_arr,$_REQUEST['parentid']);
				}
			  ?></td>
        </tr>
      <tr>
         <td width="9%" align="left" valign="middle">Category Menu </td>
			    <td  align="left" valign="middle"><?php
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
		        <td  align="left" valign="middle">Show</td>
		        <td width="15%"  align="left" valign="middle"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/>
                  <?=$page_type?>
Per Page</td>
                <td width="5%"  align="left" valign="middle">Sort By</td>
                <td width="32%"  align="left" valign="middle"><?=$sort_option_txt?>
in
  <?=$sort_by_txt?></td>
                <td width="10%"  align="right" valign="middle">&nbsp;
                  <input name="button5" type="submit" class="red" id="button5" value="Go" />
                  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_CAT_ASS_ADVERT_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
      </tr>
      </table>
      </div></td>
    </tr>
     
    <tr>
      <td colspan="3" class="listingarea">
	  <div class="listingarea_div">
      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable">
	  
    <tr>
	   <td class="listeditd" align="right" colspan="5" valign="middle">	   
	  <?
	  if($numcount)
	  {
	  ?>
		<input name="assign_categories" type="submit" class="red" id="assign_categories" value="Assign Categories" onclick="return assigncategories();" />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_CAT_ASS_ADVERT_ASSCAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
		<?
		}
		?>
   	   </td>
    </tr>
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   { 
	  $sql_page = "SELECT category_id,category_name FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
	   
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
		  $sql_advert="SELECT advert_title FROM adverts a,advert_display_category b WHERE b.product_categories_category_id=".$row['category_id']." AND a.advert_id=b.adverts_advert_id";
		  $res_advert=$db->query($sql_advert);
		  $num_advert=$db->num_rows($res_advert);
		  if($num_advert) {
		  ?>
		  <select name="adverts">
		  <?
		  while($row_adverts=$db->fetch_array($res_advert))
		  {
		  	echo "<option value=".$row_adverts['advert_title'].">".$row_adverts['advert_title']."</option>";
		  }
		  ?>
		  </select>
		 <?
		 }
		 else
		 {
		 	echo "No Banners assigned";
		 }
		 ?>
		 </td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo ($row['hide'] == 0)?'Yes':'No'; ?></td>
         
        </tr>
      	<?
	  	}
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
		
      </table>
	  </div></td>
    </tr>
	<tr>
	   <td class="listing_bottom_paging" colspan="5"  align="right" valign="middle">
	  <?
	  if($numcount) {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?></td>
    </tr>
  
    </table>
</form>
