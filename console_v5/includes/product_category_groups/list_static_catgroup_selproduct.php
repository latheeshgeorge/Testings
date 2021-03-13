<?php
	/*#################################################################
	# Script Name 	: list_static_catgroup_selproduct.php
	# Description 	: Page for listing Static Pages
	# Coded by 		: SKR
	# Created on	: 13-July-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
//Define constants for this page
$table_name='static_pages';
$page_type='Static Pages';
$group_id=($_REQUEST['pass_group_id']?$_REQUEST['pass_group_id']:'0');
$help_msg = get_help_messages('LIST_STAT_ASSIGN_PROD_CAT_GROUP');
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistPage,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistPage,\'checkbox[]\')"/>','Slno.','Page Title','Page in Groups','Active');
$header_positions=array('left','left','left','left','left','left');
$colspan = count($table_headers);

	$tabale = "product_categorygroup";
	$where  = "catgroup_id=".$group_id;
	if(!server_check($tabale, $where)) {
		echo " <font color='red'> You Are Not Authorised  </a>";
		exit;
	}


//#Search terms
$search_fields = array('title','pass_group_id');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'title':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('title' => 'Title');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";

//#Avoiding already assigned page
$sql_assigned="SELECT static_pages_page_id FROM product_categorygroup_display_staticpages WHERE sites_site_id=$ecom_siteid AND product_categorygroup_catgroup_id=".$group_id;
$ret_assigned = $db->query($sql_assigned);
$str_assigned='-1';

while($row_assigned = $db->fetch_array($ret_assigned))
{
	$str_assigned.=','.$row_assigned['static_pages_page_id'];
	
}
$str_assigned='('.$str_assigned.')';	
$where_conditions.=" AND page_id NOT IN $str_assigned";


if($_REQUEST['search_name']) {
	$where_conditions .= "AND ( title LIKE '%".add_slash($_REQUEST['search_name'])."%')";
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
$query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=prod_cat_group&fpurpose=staticGroupAssign&records_per_page=$records_per_page&search_name=".$_REQUEST['search_name']."&start=$start"."&pass_catgroupname=".$_REQUEST['pass_catgroupname']."&pass_sort_by=".$_REQUEST['pass_sort_by']."&pass_sort_order=".$_REQUEST['pass_sort_order']."&pass_records_per_page=".$_REQUEST['pass_records_per_page']."&pass_start=".$_REQUEST['pass_start']."&pass_pg=".$_REQUEST['pass_pg'];
?>
<script language="javascript">
function call_save_selected(cname,sortby,sortorder,recs,start,pg,group_id) 
{
	
	var atleastone 			= 0;
	for(i=0;i<document.frmlistPage.elements.length;i++)
	{
		if (document.frmlistPage.elements[i].type =='checkbox' && document.frmlistPage.elements[i].name=='checkbox[]')
		{

			if (document.frmlistPage.elements[i].checked==true)
			{
				atleastone ++;
				
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select the page  to assign');
	}
	
	else
	{
		if(confirm('Are you sure you want to assign selected Pages ?'))
		{
			show_processing();
			document.frmlistPage.fpurpose.value='save_staticGroupAssign';
			document.frmlistPage.submit();
		}	
	}	

}
function call_ajax_changestatus(search_name,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var curid				= 0;
	var page_ids 			= '';
	var cat_orders			= '';
	var ch_status			= document.frmlistPage.cbo_changehide.value;
	var qrystr				= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&ch_status='+ch_status+'&start='+start+'&pg='+pg;
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
		alert('Please select the pages to change the hide status');
	}
	else
	{
		if(confirm('Change Hide Status of Seleted Page(s)?'))
		{
				show_processing();
				Handlewith_Ajax('services/static_page.php','fpurpose=change_hide&'+qrystr+'&page_ids='+page_ids);
		}	
	}	
}
function checkSelected()
{
	len=document.frmlistPage.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistPage.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
				user_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one page ');
		return false;
	}
	show_processing();
	return true;
}
function edit_selected()
{
	
	len=document.frmlistPage.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistPage.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
				user_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one page ');
	}
	else if(cnt>1 ){
		alert('Please select only one page to edit');
	}
	else
	{
		show_processing();
		document.frmlistPage.fpurpose.value='edit';
		document.frmlistPage.submit();
	}
	
	
}
function ajax_return_contents() 
{
	var ret_val='';
	if(req.readyState==4)
	{
		if(req.status==200)
		{
			ret_val = req.responseText;
			document.getElementById('maincontent').innerHTML=ret_val;
			hide_processing();
		}
		else
		{
			 show_request_alert(req.status);
			//alert("Problem in requesting XML :"+req.statusText);
		}
	}
}
function call_ajax_delete(search_name,sortby,sortorder,recs,start,pg)
{
	var atleastone 	= 0;
	var del_ids 	= '';
	var qrystr				= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlistPage.elements.length;i++)
	{
		if (document.frmlistPage.elements[i].type =='checkbox' && document.frmlistPage.elements[i].name=='checkbox[]')
		{

			if (document.frmlistPage.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmlistPage.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select page to delete');
	}
	else
	{
		if(confirm('Are you sure you want to delete selected Page?'))
		{
			show_processing();
			Handlewith_Ajax('services/static_page.php','fpurpose=delete&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
</script>
<form name="frmlistPage" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="staticGroupAssign" />
<input type="hidden" name="request" value="prod_cat_group" />
<input type="hidden" name="start" value="<?=$start?>" />
<input type="hidden" name="pg" value="<?=$pg?>" />
<input type="hidden" name="pass_group_id" value="<?=$group_id?>" />
<input type="hidden" name="pass_catgroupname" value="<?=$_REQUEST['pass_catgroupname']?>" />
<input type="hidden" name="pass_sort_by" value="<?=$_REQUEST['pass_sort_by']?>" />
<input type="hidden" name="pass_sort_order" value="<?=$_REQUEST['pass_sort_order']?>" />
<input type="hidden" name="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
<input type="hidden" name="pass_start" value="<?=$_REQUEST['pass_start']?>" />
<input type="hidden" name="pass_pg" value="<?=$_REQUEST['pass_pg']?>" />
<?
$sql_group = "SELECT catgroup_name FROM product_categorygroup WHERE catgroup_id=".$group_id;
	$ret_group = $db->query($sql_group);
	if ($db->num_rows($ret_group))
	{
		$row_group 		= $db->fetch_array($ret_group);
		$show_groupname	= stripslashes($row_group['catgroup_name']);
	}
?>
  <table border="0" cellpadding="0" cellspacing="0" class="maintable" width="100%">
    <tr>
      <td align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=prod_cat_group&catgroupname=<?=$_REQUEST['pass_catgroupname']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>">List Category Menus</a> <a href="home.php?request=prod_cat_group&fpurpose=edit&checkbox[0]=<?php echo $group_id?>&catgroupname=<?=$_REQUEST['pass_catgroupname']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=statmenu_tab_td">Edit  Category Menu</a><span> Assign Static Page to '<? echo $show_groupname;?>'</span></div></td>
    </tr>
	<tr>
	  <td align="left" valign="middle" class="helpmsgtd_main" colspan="5">
	  <?php 
		  Display_Main_Help_msg($help_arr,$help_msg);
	  ?>	 </td>
	</tr>
	<?php 
			if($alert)
			{			
		?>
        		<tr>
          			<td align="center" valign="middle" class="errormsg"><?php echo($alert);?></td>
          		</tr>
		 <?php
		 	}
		 ?> 
    <?php
	  if($numcount)
	  {
	?>
	<tr><td align="right" valign="middle" class="sorttd"><?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?></td></tr>
	<?php
	  }
	?>
	<tr>
      <td height="48" class="sorttd" >
	  <div class="sorttd_div" >
      	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttabletop">
        <tr>
          <td width="8%" height="30" align="left" valign="middle">Title </td>
          <td width="17%" height="30" align="left" valign="middle"><input name="search_name" type="text" class="textfeild" id="search_name" value="<?=$_REQUEST['search_name']?>"  /></td>
          <td width="10%" height="30" align="left" valign="middle">Records Per Page </td>
          <td width="18%" height="30" align="left" valign="middle"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/></td>
          <td width="5%" height="30" align="left" valign="middle">Sort By</td>
          <td width="29%" height="30" align="left" valign="middle"><?=$sort_option_txt?>&nbsp;&nbsp;&nbsp;in&nbsp;&nbsp;&nbsp;<?=$sort_by_txt?></td>
          <td width="13%" height="30" align="right" valign="middle"><input name="button5" type="submit" class="red" id="button5" value="Go" />
            <a href="#" onmouseover ="ddrivetip('<? echo get_help_messages('LIST_PROD_CAT_GROUP_ASSIGNSTAT_GO') ?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          </tr>
      </table>
	  </div>      </td>
	</tr>
     
    <tr>
      <td class="listingarea">
	  <div class="sorttd_div" >
      <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="listingtable">
	  <tr>
      <td align="right" valign="middle" class="listeditd" colspan="5">	  	  <input name="change_hide" type="button" class="red" id="change_hide" onClick="call_save_selected()" value="Assign Selected">
	   
	    <a href="#" onmouseover ="ddrivetip('<? echo get_help_messages('LIST_PROD_CAT_GROUP_ASSIGNSTAT_ASSIGN') ?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>      </td>
    </tr>
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
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
		 $num_page_group=$db->num_rows($res_page_group);
		  if($num_page_group)
		  {
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
		 ?>		 </td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo ($row['hide'] == 0)?'Yes':'No'; ?></td>
        </tr>
      <?
	  }
	  }
	  else
	  {
	  ?>
	  <tr>
				  <td align="center" valign="middle" class="norecordredtext" >
				  	No Static Page exists.				  </td>
		  </tr>
		<?
		}
		?>	
		
      </table>
	  </div>
	  </td>
    </tr>
	<tr>
      <td align="right" valign="middle" class="listeditd" colspan="5">
	    <?
	  if($numcount)
	  {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?>   	   </td>
    </tr>
  
  </table>
</form>
