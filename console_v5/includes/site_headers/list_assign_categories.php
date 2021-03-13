<?php
	/*#################################################################
	# Script Name 	: list_assign_categories.php
	# Description 	: Page for listing categories that is to be displayed for a site header
	# Coded by 		: ANU
	# Created on	: 2-Aug-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
//Define constants for this page
$table_name='product_categories';
$page_type='Categories';
$help_msg =get_help_messages('LIST_ASS_CAT_MESS1');
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistCategories,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistCategories,\'checkbox[]\')"/>','Slno.','Category Name','Parent','Site Headers already assigned to this Category','Hidden');
$header_positions=array('left','left','left','left','left');
$colspan = count($table_headers);

$tabale = "site_headers";
$where  = "header_id=".$_REQUEST['header_id'];
if(!server_check($tabale, $where)) {
	echo " <font color='red'> You Are Not Authorised  </a>";
	exit;
}	


// Finding already assigned Categories in the group.
$sql_assigned_categories = "SELECT product_categories_category_id FROM header_display_category WHERE site_headers_header_id =".$_REQUEST['header_id']." AND sites_site_id=".$ecom_siteid;
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
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
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
$query_string .= "header_id=".$_REQUEST['header_id']."&sort_by=$sort_by&sort_order=$sort_order&request=site_headers&fpurpose=list_assign_categories&records_per_page=$records_per_page&search_name=".$_REQUEST['search_name']."&header_title=".$_REQUEST['header_title']."&start=$start&pass_sort_by=".$_REQUEST['pass_sort_by']."&pass_sort_order=".$_REQUEST['pass_sort_order']."&pass_records_per_page=".$_REQUEST['pass_records_per_page']."&pass_search_name=".$_REQUEST['pass_search_name']."&pass_pg=".$_REQUEST['pass_pg']."&pass_start=".$_REQUEST['pass_start'];
// to find the header title
if($_REQUEST['header_id'])
{
$sql_header =  "SELECT
 						  	header_title
				FROM
							site_headers
				WHERE 
							sites_site_id=$ecom_siteid
				AND
							header_id=".$_REQUEST['header_id']." LIMIT 1";
$ret_header = $db->query($sql_header);
$row_header = $db->fetch_array($ret_header);
}							
	
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
		alert('Please select the Categories to assign to the Site Headers');
		return false;
	}
	else
	{
		if(confirm('Assign Categories to the Site headers?'))
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
<form name="frmlistCategories" action="home.php?request=site_headers" method="post" >	
<input type="hidden" name="fpurpose" value="list_assign_categories" />
<input type="hidden" name="request" value="site_headers" />
  <input type="hidden" name="category_ids" id="category_ids" value="" />
  <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
  <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
  <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
  <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
  <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />  
  <input type="hidden" name="header_id" id="header_id" value="<?=$_REQUEST['header_id']?>" />  
  <input type="hidden" name="header_title" id="header_title" value="<?=$_REQUEST['header_title']?>" />  
   <input type="hidden" name="pass_search_name" id="pass_search_name" value="<?=$_REQUEST['pass_search_name']?>" />
   <input type="hidden" name="pass_start" id="pass_start" value="<?=$_REQUEST['pass_start']?>" />
  <input type="hidden" name="pass_sort_by" id="pass_sort_by" value="<?=$_REQUEST['pass_sort_by']?>" />
  <input type="hidden" name="pass_sort_order" id="pass_sort_order" value="<?=$_REQUEST['pass_sort_order']?>" />
  <input type="hidden" name="pass_records_per_page" id="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
  <input type="hidden" name="pass_pg" id="pass_pg" value="<?=$_REQUEST['pass_pg']?>" />
  <table border="0" cellpadding="0" cellspacing="0" class="maintable" width="100%">
    <tr>
      <td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=site_headers&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&search_name=<?=$_REQUEST['pass_search_name']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&header_id=<?=$_REQUEST['header_id']?>">List Site Headers</a> <a href="home.php?request=site_headers&fpurpose=edit&header_id=<?=$_REQUEST['header_id']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_start=<?=$_REQUEST['pass_start']?>&pass_pg=<?=$_REQUEST['pass_pg']?>&curtab=catmenu_tab_td">Edit Site Headers</a><span> Assign Categories to the Site Headers : <b>  '<?=$row_header['header_title']?>' </b></span></div></td>
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
	
    <tr>
	   <td width="100%" align="right" class="sorttd" colspan="2">
		 <?php
		  if($numcount)
		  {
			paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
		  }
		  ?>
		  </td>
	  </tr>
    <tr>
      <td height="48" class="sorttd" colspan="2" >
	  <div class="editarea_div">
      	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttabletop">
        <tr>
          <td width="9%" height="30" align="left" valign="middle">Title </td>
          <td width="12%" height="30" align="left" valign="middle"><input name="search_name" type="text" class="textfeild" id="search_name" value="<?=$_REQUEST['search_name']?>"  /></td>
          <td width="10%" height="30" align="left" valign="middle">Category Menu </td>
          <td width="20%" height="30" align="left" valign="middle"><?php
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
          <td width="7%" height="30" align="left" valign="middle">Parent Category </td>
          <td width="12%" height="30" align="left" valign="middle"><?php
			  	$parent_arr = generate_category_tree_special(0,0,true,false,false,true);
				if(is_array($parent_arr))
				{
					echo generateselectbox('parentid',$parent_arr,$_REQUEST['parentid']);
				}
			  ?></td>
          </tr>
		  <tr>
         <td width="9%" height="30" align="left" valign="middle">Records Per Page </td>
			    <td height="30" align="left" valign="middle"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/></td>          
		        <td height="30" align="left" valign="middle">Sort By</td>
		        <td height="30" align="left" valign="middle"><?=$sort_option_txt?>&nbsp;&nbsp;&nbsp;in&nbsp;&nbsp;&nbsp;<?=$sort_by_txt?></td>
		        <td height="30" align="left" valign="middle">&nbsp;</td>
		        <td height="30" align="right" valign="middle"><input name="button" type="submit" class="red" id="button" value="Go" />
                  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_ASS_CAT_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
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
		  <td align="left" valign="middle" class="listeditd" colspan="3" >&nbsp;</td> 
		  <td align="right" valign="middle" class="listeditd" colspan="3" >
		  <?
		  if($numcount)
		  {
		  ?>
			
			
			<input name="assign_categories" type="submit" class="red" id="assign_categories" value="Assign Categories" onclick="return assigncategories();" />
			  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_ASS_CAT_ASSCAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
			<?
			}
			?>
		  </td> 
	</tr>
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   { 
	  $sql_page = "SELECT category_id,category_name,parent_id,category_hide 
	  					FROM $table_name 
								$where_conditions 
									ORDER BY $sort_by $sort_order 
										LIMIT $start,$records_per_page ";
	   $res = $db->query($sql_page);
	   $srno = getStartOfPageno($records_per_page,$pg); 
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
         <td align="left" valign="middle" class="<?php echo $class_val?>">
					  <?php
					  	if ($row['parent_id']!=0)
						{
							//Find the name of the parent category
							$sql_parent = "SELECT category_name FROM product_categories WHERE category_id=".$row['parent_id'];
							$ret_parent = $db->query($sql_parent);
							if ($db->num_rows($ret_parent))
							{
								$row_parent = $db->fetch_array($ret_parent);
								echo stripslashes($row_parent['category_name']);
							}
						}
						else
							echo '-- Root --';
					  ?>					  </td>
		 <td align="left" valign="middle" class="<?=$class_val;?>">
		 <?
		 $sql_header="SELECT header_title FROM site_headers a,header_display_category b WHERE b.product_categories_category_id=".$row['category_id']." AND a.header_id=b.site_headers_header_id";
		 $res_headers=$db->query($sql_header);
		 $num_header=$db->num_rows($res_headers);
		  if($num_header) {
		  ?>
		  <select name="site_headers">
		  <?
		  while($row_headers=$db->fetch_array($res_headers))
		  {
		  	echo "<option value=$row_headers[header_title]>$row_headers[header_title]</option>";
		  }
		  ?>
		  </select>
		 <?
		 }
		 else
		 {
		 	echo "No Site Headers assigned";
		 }
		 ?>		 </td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo ($row['category_hide'] == 0)?'No':'Yes'; ?></td>
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
	<?php
		  if($numcount)
		  {	 
		?>
		<tr>
		  <td align="right" valign="middle" class="listing_bottom_paging" colspan="2" >
		  <?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?>
		  </td> 
		</tr>	
		<?php
			}
		?>
 
    </table>
</form>
