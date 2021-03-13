<?php
	/*#################################################################
	# Script Name 	: list_static_pages.php
	# Description 	: Page for listing Static Pages for assiging into the page groups
	# Coded by 		: ANU
	# Created on	: 05-July-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
//Define constants for this page

$table_name='static_pages';
$page_type='Static Pages';
$help_msg = 'This section lists the Static Pages that can be assigned to the group.';
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistPage,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistPage,\'checkbox[]\')"/>','Slno.','Page Title','Page in Groups','Active');
$header_positions=array('left','left','left','left','left','left');
$colspan = count($table_headers);

// Finding already assigned pages in the group.
$sql_assigned_pages = "SELECT static_pages_page_id FROM static_pagegroup_static_page_map where static_pagegroup_group_id =".$_REQUEST['group_id'];
$res_assigned_pages = $db->query($sql_assigned_pages);
$assigned_pages_str = '';
while($assigned_pages = $db->fetch_array($res_assigned_pages)){
if($assigned_pages_str !=''){
$assigned_pages_str= $assigned_pages_str.',';
}
$assigned_pages_str .=  $assigned_pages['static_pages_page_id'];
}

//#Search terms.
$search_fields = array('title');
foreach($search_fields as $v) {
	$query_string .= "$v=${$v}&";#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort.
$sort_by = (!$_REQUEST['sort_by'])?'title':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('title' => 'Title');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options.
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
if($assigned_pages_str!=''){
$where_conditions .=" AND  page_id NOT IN ($assigned_pages_str)";
}
if($_REQUEST['search_name']) {
	$where_conditions .= "AND (title LIKE '%".add_slash($_REQUEST['search_name'])."%')";
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
$query_string .="fpurpose=assign_static_pages&sort_by=$sort_by&sort_order=$sort_order&request=stat_group&records_per_page=$records_per_page&search_name=".$_REQUEST['search_name']."&start=$start&group_id=".$_REQUEST['group_id']."&pass_sort_by=$pass_sort_by&pass_sort_order=$pass_sort_order&pass_records_per_page=$pass_records_per_page&pass_search_name=".$_REQUEST['pass_search_name']."";
// to find the groupname
echo $group_id;
?>
<script language="javascript">

function assignpages()
{
	var atleastone 			= 0;
	var curid				= 0;
	var page_ids 			= '';
	var cat_orders			= '';
	//var ch_status			= document.frmlistPage.cbo_changehide.value;
	
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlistPage.elements.length;i++)
	{
		if (document.frmlistPage.elements[i].type =='checkbox' && document.frmlistPage.elements[i].name=='checkbox[]')
		{

			if (document.frmlistPage.elements[i].checked==true)
			{
				atleastone = 1;
				if (page_ids!='')
					page_ids += '~';
				 page_ids += document.frmlistPage.elements[i].value;
			}	
		}
	}
	if (atleastone==0) 
	{
		alert('Please select the pages to assign to the group');
		return false;
	}
	else
	{
		if(confirm('Assign Page(s) to the group?'))
		{
				show_processing();
				//alert('fpurpose=assign_pages&'+qrystr+'&page_ids='+page_ids);
				//Handlewith_Ajax('services/static_group.php','fpurpose=assign_pages&'+qrystr+'&page_ids='+page_ids);
				document.frmlistPage.page_ids.value=page_ids;
				document.frmlistPage.fpurpose.value='assign_pages';
				document.frmlistPage.submit();
		}	
	}	
}

</script>
<form name="frmlistPage" action="home.php?request=stat_group" method="post" >	
<input type="hidden" name="fpurpose" value="assign_static_pages" />
<input type="hidden" name="request" value="stat_group" />
  <input type="hidden" name="page_ids" id="page_ids" value="" />
  <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
  <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
  <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
  <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
  <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />  
  <input type="hidden" name="group_id" id="group_id" value="<?=$_REQUEST['group_id']?>" />  
  <input type="hidden" name="group_name" id="group_name" value="<?=$_REQUEST['group_name']?>" />  
   <input type="hidden" name="pass_search_name" id="pass_search_name" value="<?=$_REQUEST['pass_search_name']?>" />
   <input type="hidden" name="pass_start" id="pass_start" value="<?=$_REQUEST['pass_start']?>" />
  <input type="hidden" name="pass_sort_by" id="pass_sort_by" value="<?=$_REQUEST['pass_sort_by']?>" />
  <input type="hidden" name="pass_sort_order" id="pass_sort_order" value="<?=$_REQUEST['pass_sort_order']?>" />
  <input type="hidden" name="pass_records_per_page" id="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
  <input type="hidden" name="pass_pg" id="pass_pg" value="<?=$_REQUEST['pass_pg']?>" />
  <table border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td colspan="3" align="left" valign="middle" class="treemenutd"><a href="home.php?request=stat_group&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&search_name=<?=$_REQUEST['pass_search_name']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&group_id=<?=$_REQUEST['group_id']?>">List Static Page Group</a> >> <a href="home.php?request=stat_group&fpurpose=edit&group_id=<?=$_REQUEST['group_id']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_start=<?=$_REQUEST['pass_start']?>&pass_pg=<?=$_REQUEST['pass_pg']?>">Edit Group</a> &gt;&gt; Assign Pages to the group : <b>  '<?=$_REQUEST['group_name']?>' </b></td>
    </tr>
	 <tr>
          <td colspan="3" align="left" valign="middle" class="helpmsgtd" ><div class="helpmsg_divcls"><?=$help_msg ?></div></td>
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
          <td width="22%" align="left" valign="middle">Title </td>
          <td colspan="3" align="left" valign="middle"><input name="search_name" type="text" class="textfeild" id="search_name" value="<?=$_REQUEST['search_name']?>"  /></td>
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
          <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;<input name="button5" type="submit" class="red" id="button5" value="Go" /> <a href="#" onmouseover ="ddrivetip('Use \'Go\' button to search for Static Pages.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
      </table>
	  </td>
    </tr>
    <tr>
      <td class="listeditd">&nbsp;
	  </td>
	   <td class="listeditd" width="162" align="center">
	  <?
	  if($numcount)
	  {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?></td>
	   <td class="listeditd" align="right">
	   
	  <?
	  if($numcount)
	  {
	  ?>
        
		
		<input name="assign_pages" type="submit" class="red" id="assign_pages" value="Assign Pages" onclick="return assignpages();" />
		  <a href="#" onmouseover ="ddrivetip('Use \'Change\' button to change the hide status of pages. Select the hide status in the drop down, mark the pages to be changed and press \'Change\' button.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
		<?
		}
		?>
   	   </td>
    </tr>
     
    <tr>
      <td colspan="3" class="listingarea">
      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable">
       <?
	 
	   if($numcount)
	   {  echo table_header($table_headers,$header_positions);
	   $sql_page = "SELECT page_id,title,hide FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
	   
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
          <td align="left" valign="middle" class="<?=$class_val;?>"  width="5%"><input name="checkbox[]" value="<? echo $row['page_id']?>" type="checkbox"></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"  width="2%"><?php echo $srno++?></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><a href="home.php?request=stat_page&fpurpose=edit&checkbox[0]=<?php echo $row['page_id']?>&search_name=<?php echo $_REQUEST['search_name']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>" title="<? echo $row['title']?>" class="edittextlink" onclick="show_processing()"><? echo $row['title']?></a></td>
         <td align="left" valign="middle" class="<?=$class_val;?>">
		 <?
		 $sql_page_group="SELECT group_name FROM static_pagegroup a,static_pagegroup_static_page_map b WHERE b.static_pages_page_id=".$row['page_id']." AND a.group_id=b.static_pagegroup_group_id";
		 $res_page_group=$db->query($sql_page_group);
		 $num_page_group=mysql_num_rows($res_page_group);
		  if($num_page_group) {
		  ?>
		  <select name="page_group">
		  <?
		  while($row_page_group=$db->fetch_array($res_page_group))
		  {
		  	echo "<option value=$row_page_group[group_name]>$row_page_group[group_name]</option>";
		  }
		  ?>
		  </select>
		 <?
		 }
		 else
		 {
		 	echo "NOT Assigned to any group";
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
				  	No Static Page exists.				  </td>
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
	  if($numcount) {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?></td>
      <td class="listeditd" align="right">&nbsp;
	 	
      </td>
    </tr>
    </table>
</form>
