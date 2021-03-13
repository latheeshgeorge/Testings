<?php
	/*#################################################################
	# Script Name 	: list_assign_categories.php
	# Description 	: Page for listing categories for assiging into the Surveys
	# Coded by 		: ANU
	# Created on	: 8-Aug-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
//Define constants for this page
$table_name='product_categories';
$page_type='Categories';
$help_msg = get_help_messages('LIST_CAT_ASS_SURVAY');
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistCategories,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistCategories,\'checkbox[]\')"/>','Slno.','Category Name','Survey assigned for the Category','Active');
$header_positions=array('left','left','left','left','left');
$colspan = count($table_headers);
//$survey_id=($_REQUEST['survey_id']?$_REQUEST['survey_id']:$_REQUEST['checkbox'][0]);
($_REQUEST['pass_survey_id']>0)?$survey_id=$_REQUEST['pass_survey_id']:$survey_id=0;

$tabale = "survey";
$where  = "survey_id=".$survey_id;
if(!server_check($tabale, $where)) {
	echo " <font color='red'> You Are Not Authorised  </a>";
	exit;
}

// Finding already assigned Categories in the group.
$sql_assigned_categories = "SELECT product_categories_category_id FROM survey_display_category WHERE survey_survey_id =".$survey_id." AND sites_site_id=".$ecom_siteid;
$res_assigned_categories = $db->query($sql_assigned_categories);
$assigned_categories_str = '';
while($assigned_categories = $db->fetch_array($res_assigned_categories)){
if($assigned_categories_str !=''){
$assigned_categories_str= $assigned_categories_str.',';
}
$assigned_categories_str .=  $assigned_categories['product_categories_category_id'];
}
//#Search terms.
$search_fields = array('category_name','survey_id','catgroupid','parentid');
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
$query_string .= "survey_id=".$survey_id."&sort_by=$sort_by&sort_order=$sort_order&request=survey&fpurpose=list_assign_categories&records_per_page=$records_per_page&search_name=".$_REQUEST['search_name']."&survey_title=".$_REQUEST['survey_title']."&start=$start&pass_sort_by=".$_REQUEST['pass_sort_by']."&pass_sort_order=".$_REQUEST['pass_sort_order']."&pass_records_per_page=".$_REQUEST['pass_records_per_page']."&pass_search_name=".$_REQUEST['pass_search_name']."&pass_survey_id=".$_REQUEST['pass_survey_id']."&status=".$_REQUEST['status']."&pass_start=".$_REQUEST['pass_start']."&pass_pg=".$_REQUEST['pass_pg'];
// to find the groupname
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
		alert('Please select the Categories to assign to the Survey');
		return false;
	}
	else
	{
		if(confirm('Assign Categories to the survey?'))
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
<form name="frmlistCategories" action="home.php?request=survey" method="post" >	
<input type="hidden" name="fpurpose" value="list_assign_categories" />
<input type="hidden" name="request" value="survey" />
  <input type="hidden" name="category_ids" id="category_ids" value="" />
  <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
  <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
  <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
  <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
  <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" /> 
  <input type="hidden" name="status" value="<?=$_REQUEST['status']?>" /> 
  <input type="hidden" name="survey_id" id="survey_id" value="<?=$survey_id?>" />
    <input type="hidden" name="pass_survey_id" id="pass_survey_id" value="<?=$survey_id?>" />  
  <input type="hidden" name="survey_title" id="survey_title" value="<?=$_REQUEST['survey_title']?>" />  
   <input type="hidden" name="pass_search_name" id="pass_search_name" value="<?=$_REQUEST['pass_search_name']?>" />
   <input type="hidden" name="pass_start" id="pass_start" value="<?=$_REQUEST['pass_start']?>" />
  <input type="hidden" name="pass_sort_by" id="pass_sort_by" value="<?=$_REQUEST['pass_sort_by']?>" />
  <input type="hidden" name="pass_sort_order" id="pass_sort_order" value="<?=$_REQUEST['pass_sort_order']?>" />
  <input type="hidden" name="pass_records_per_page" id="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
  <input type="hidden" name="pass_pg" id="pass_pg" value="<?=$_REQUEST['pass_pg']?>" />
  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=survey&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&search_name=<?=$_REQUEST['pass_search_name']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&survey_id=<?=$_REQUEST['survey_id']?>&status=<?=$_REQUEST['status']?>">List Survey</a> <a href="home.php?request=survey&fpurpose=edit&survey_id=<?=$survey_id?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_start=<?=$_REQUEST['pass_start']?>&pass_pg=<?=$_REQUEST['pass_pg']?>&status=<?=$_REQUEST['status']?>&curtab=survycatg_tab_td">Edit Survey</a><span> Assign Categories to the Survey : <b>  '<?=$_REQUEST['survey_title']?>' </b></span></div></td>
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
    <tr><td colspan="2" align="right" valign="middle" class="sorttd"><?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?> </td></tr>
	<?php
		  }
	?>
	<tr>
      <td height="48" class="sorttd" colspan="2" >
	  <div class="sorttd_div">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttableleft">
        <tr>
          <td height="30"  align="left" valign="middle">Title </td>
          <td height="30"  align="left" valign="middle"><input name="search_name" type="text" class="textfeild" id="search_name" value="<?=$_REQUEST['search_name']?>"  /></td>
          </tr>
		  <tr>
         <td height="30"  align="left" valign="middle">Category Menu </td>
			    <td height="30"  align="left" valign="middle">
				<?php
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
			   <td height="30" align="left" valign="middle">Parent Category </td>
          <td height="30"  align="left" valign="middle">
		  <?php
			  	$parent_arr = generate_category_tree_special(0,0,true,false,false,true);
				if(is_array($parent_arr))
				{
					echo generateselectbox('parentid',$parent_arr,$_REQUEST['parentid']);
				}
			  ?></td>          
			 </tr>
      </table>
      <table width="18%" border="0" cellpadding="0" cellspacing="0" class="sorttableright">
        <tr>
          <td width="33%" height="30" align="left" valign="middle">Records Per Page </td>
          <td width="27%" height="30" align="left" valign="middle"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/></td>
          <td width="40%" height="30" align="left" valign="middle">&nbsp;</td>
        </tr>
        <tr>
          <td height="30" align="left" valign="middle">Sort By</td>
          <td height="30" align="left" valign="middle" nowrap="nowrap"><?=$sort_option_txt?>&nbsp;&nbsp;in&nbsp;&nbsp;<?=$sort_by_txt?>  </td>
          <td height="30" align="left" valign="middle">&nbsp;&nbsp;&nbsp;&nbsp;
            <input name="button5" type="submit" class="red" id="button5" value="Go" /> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SURVAY_ASS_CAT_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
      </table>
	  </div>	  </td>
    </tr>
    
     
    <tr>
      <td colspan="2" class="listingarea">
	  <div class="listingarea_div">
      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable" width="100%">
	  <tr>
	   <td align="right" class="listeditd" valign="middle" colspan="5">
	   
	  <?
	  if($numcount)
	  {
	  ?>
        
		
		<input name="assign_categories" type="submit" class="red" id="assign_categories" value="Assign Categories" onclick="return assigncategories();" />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SURVAY_ASS_CAT_ASSCAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
		<?
		}
		?>      </td>
    </tr>
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   { 
	   $sql_page = "SELECT category_id,category_name,category_hide FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
	   
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
          <td align="left" valign="middle" class="<?=$class_val;?>"  width="7%"><input name="checkbox[]" value="<? echo $row['category_id']?>" type="checkbox"></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"  width="2%"><?php echo $srno++?></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><a href="home.php?request=prod_cat&fpurpose=edit&checkbox[0]=<?php echo $row['category_id']?>&search_name=<?php echo $_REQUEST['search_name']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>" title="<? echo $row['title']?>" class="edittextlink" onclick="show_processing()"><? echo $row['category_name']?></a></td>
         <td align="left" valign="middle" class="<?=$class_val;?>">
		 <?
		 $sql_survey="SELECT survey_title FROM survey a,survey_display_category b WHERE b.product_categories_category_id=".$row['category_id']." AND a.survey_id=b.survey_survey_id";
		 $res_survey=$db->query($sql_survey);
		 $num_survey=$db->num_rows($res_survey);
		  if($num_survey) {
		  ?>
		  <select name="survey">
		  <?
		  while($row_survey=$db->fetch_array($res_survey))
		  {
		  	echo "<option value=$row_survey[survey_title]>$row_survey[survey_title]</option>";
		  }
		  ?>
		  </select>
		 <?
		 }
		 else
		 {
		 	echo "No Surveys assigned";
		 }
		 ?>		 </td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo ($row['category_hide'] == 0)?'Yes':'No'; ?></td>
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
			<td align="right" class="listing_bottom_paging" valign="middle" colspan="5">	  
		<?
		if($numcount) {
		paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
		}
		?></td>
		</tr>
  
  </table>
</form>
