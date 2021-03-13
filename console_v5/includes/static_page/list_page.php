<?php
	/*#################################################################
	# Script Name 	: list_page.php
	# Description 	: Page for listing Static Pages
	# Coded by 		: SKR
	# Created on	: 27-June-2007
	# Modified by	: SKR
	# Modified On	: 03-Aug-2007
	#################################################################*/
//Define constants for this page
$table_name='static_pages';
$page_type='Static Pages';
$help_msg = get_help_messages('LIST_STAT_PAGE_MESS1');
if($ecom_site_mobile_api==1)
{
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistPage,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistPage,\'checkbox[]\')"/>','Slno.','Page Title','Page in Groups','Type','Hidden','In Mobile Application');
}
else
{
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistPage,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistPage,\'checkbox[]\')"/>','Slno.','Page Title','Page in Groups','Type','Hidden');	
}
$header_positions=array('left','left','left','left','left','left','left');
$colspan = count($table_headers);

//#Search terms
$search_fields = array('title');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'title':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('title' => 'Title','page_type'=>'Type');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
if($_REQUEST['search_name']) {
	$where_conditions .= "AND ( title LIKE '%".add_slash($_REQUEST['search_name'])."%')";
}
if($_REQUEST['sel_group']) {
	$sql = "SELECT static_pages_page_id 
			   FROM static_pagegroup_static_page_map
				 WHERE static_pagegroup_group_id= '".$_REQUEST['sel_group']."'";
	$res = $db->query($sql);
	if($db->num_rows($res)) {
		while($row = $db->fetch_array($res)) {
			$find_arr[] = $row['static_pages_page_id'];		
		}
		$where_conditions .= " AND page_id IN (".implode(',',$find_arr).") ";	
	} else {
		$where_conditions .= " AND page_id IN (-1) ";			
	}
			 
	//$where_conditions .= "AND ( title LIKE '".add_slash($_REQUEST['sel_group'])."')";
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
$query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=stat_page&records_per_page=$records_per_page&search_name=".$_REQUEST['search_name']."&sel_group=".$_REQUEST['sel_group']."&start=$start";
?>
<script language="javascript">
function call_ajax_changestatus(search_name,sortby,sortorder,recs,start,pg,selgroup)
{
	var atleastone 			= 0;
	var curid				= 0;
	var page_ids 			= '';
	var cat_orders			= '';
	var ch_status			= document.frmlistPage.cbo_changehide.value;
	var qrystr				= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&ch_status='+ch_status+'&start='+start+'&pg='+pg+'&sel_group='+selgroup;
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
	var errcnt = 0;	
	var errpage = '';
	var errpagename = ''; 
	var user_id=0;	
	var obj;
	for (var j = 1; j <= len; j++) {
		el = document.frmlistPage.elements[j];
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
				user_id=el.value;
				obj = eval('document.getElementById("hid_edit_allow_'+el.value+'")');
				objname = eval(document.getElementById('hid_title_allow_'+user_id));
				if(obj.value==0) {
					errcnt++;
					errpage = objname.value;
				}
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one page ');
	}
	else if(cnt>1 ){
		alert('Please select only one page to edit');
	}
	else if(errcnt>0) {
		alert('No permission To Edit '+errpage+' ' );
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
function call_ajax_delete(search_name,sortby,sortorder,recs,start,pg,selgroup)
{
	var atleastone 	= 0;
	var del_ids 	= '';
	var qrystr				= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&sel_group='+selgroup;
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
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="request" value="stat_page" />
<input type="hidden" name="start" value="<?=$start?>" />
<input type="hidden" name="pg" value="<?=$pg?>" />
  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span> List Static Pages </span></div></td>
    </tr>
	<tr>
	  <td colspan="3" align="left" valign="middle" class="helpmsgtd_main">
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
      <td class="sorttd" colspan="3" align="right" >	  
		<?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?>
	  </td>
	</tr>
	<?php
		  }
	?>
    <tr>
      <td class="sorttd" colspan="3" >
	  <div class="sorttd_div">
      	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttabletop">
        <tr>
          <td width="6%" height="30" align="left" valign="middle">Title </td>
          <td width="17%" height="30" align="left" valign="middle"><input name="search_name" type="text" class="textfeild" id="search_name" value="<?=$_REQUEST['search_name']?>"  /></td>
          <td width="4%" height="30" align="left" valign="middle">Menu</td>
          <td width="14%" height="30" align="left" valign="middle">
		 <!-- <select name="sel_group">
		  <option value="">-Any-</option>-->
		  	<?PHP
			$sel_group = array(0=>'-- Any --');
			 $sql_group="SELECT group_id, group_name 
		 					FROM static_pagegroup  
								WHERE sites_site_id=$ecom_siteid";
			 $res_group=$db->query($sql_group);
			 while($row_group = $db->fetch_array($res_group)) {
			 $sel_group[$row_group['group_id']] =$row_group['group_name'];
			 	/*echo "<option value='$row_group[group_id]'";
				if($sel_group==$row_group['group_id']) echo "selected";
				echo ">$row_group[group_name]</option>";*/
			 }
			 if(is_array($sel_group))
				{
					echo generateselectbox('sel_group',$sel_group,$_REQUEST['sel_group']);
				}
			?>
		  <!--</select>--></td>
          <td width="11%" height="30" align="left" valign="middle">Records Per Page </td>
          <td width="12%" height="30" align="left" valign="middle"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/></td>
          <td width="5%" height="30" align="left" valign="middle">Sort By</td>
          <td width="21%" height="30" align="left" valign="middle"><?=$sort_option_txt?>&nbsp;&nbsp;&nbsp;in&nbsp;&nbsp;&nbsp;<?=$sort_by_txt?></td>
          <td width="10%" height="30" align="right" valign="middle">&nbsp;&nbsp;&nbsp;&nbsp;
            <input name="button5" type="submit" class="red" id="button5" value="Go" /> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_STAT_PAGE_GO')?>')"; onmouseout="hideddrivetip()" ><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
			</tr>
      </table>
	  </div>
	  </td>
    </tr>
	
	
	<tr>
		<td class="listingarea">
		<div class="listingarea_div">
			 <table width="100%" border="0" cellspacing="0" cellpadding="0">
			 <tr class="maintable">
			 	<td class="listeditd">
				<a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=stat_page&fpurpose=add&records_per_page=<?=$records_per_page?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$start?>&pg=<?=$pg?>" class="addlist" onclick="show_processing()">Add</a> 
	<?php	if($numcount)
			{
	?>			<a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>','<? echo $_REQUEST['sel_group']?>')" class="deletelist">Delete</a>
	<?php	}
	?>			</td>
				<td class="listeditd" align="right">
	<?php	if($numcount)
			{
?>				Change Status
				<select name="cbo_changehide" class="dropdown" id="cbo_changehide">
					<option value="1">Yes</option>
					<option value="0">No</option>
				</select>&nbsp;
				<input name="change_hide" type="button" class="red" id="change_hide" value="Change" onclick="call_ajax_changestatus('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>','<? echo $_REQUEST['sel_group']?>')" />
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_STAT_PAGE_CHSTATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
	<?php	}
	?>			</td>
			</tr>
			</table>
			<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="listingtable">
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
	   $sql_page = "SELECT page_id,title,hide,page_type,allow_edit,in_mobile_api_sites 
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
				
	  // 		echo "<input name='hid_edit_allow_$row[page_id]' type='hidden' value='$row[allow_edit]'/>";
	//	echo "<input name='hid_title_allow_$row[page_id]' type='hidden' value='$row[title]'/>";
	   ?>
        <tr >
          <td align="left" valign="middle" class="<?=$class_val;?>"  width="6%"><input name="checkbox[]" value="<? echo $row['page_id']?>" type="checkbox"></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"  width="2%"><?php echo $srno++?>
		  <input id='hid_edit_allow_<?PHP echo $row['page_id']; ?>' name='hid_edit_allow_<?PHP echo $row['page_id']; ?>' type='hidden' value='<?PHP echo $row['allow_edit']; ?>'/>
		  <input id='hid_title_allow_<?PHP echo $row['page_id']; ?>' name='hid_title_allow_<?PHP echo $row['page_id']; ?>' type='hidden' value='<?PHP echo stripslashes($row['title']); ?>'/>
		  </td>
          <td align="left" valign="middle" class="<?=$class_val;?>">
		  <?PHP if($row['allow_edit']==1) { ?>
		  <a href="home.php?request=stat_page&fpurpose=edit&checkbox[0]=<?php echo $row['page_id']?>&search_name=<?php echo $_REQUEST['search_name']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>&sel_group=<?=$_REQUEST['sel_group']?>" title="<? echo stripslashes($row['title'])?>" class="edittextlink" onclick="show_processing()">
		  <? echo stripslashes($row['title'])?>
		  </a><? } else { echo "<span class='enotditallowtext'>".stripslashes($row['title'])."</span>";  }?></td>
         <td align="left" valign="middle" class="<?=$class_val;?>">
		 <?
		 $sql_page_group="SELECT group_name 
		 					FROM static_pagegroup a,static_pagegroup_static_page_map b 
								WHERE b.static_pages_page_id=".$row['page_id']." AND a.group_id=b.static_pagegroup_group_id";
		 $res_page_group=$db->query($sql_page_group);
		 $num_page_group=$db->num_rows($res_page_group);
		  if($num_page_group)
		  {
		  ?>
		  <select name="page_group">
		  <?
		  while($row_page_group=$db->fetch_array($res_page_group))
		  {
		  	echo "<option value=$row_page_group[group_name]>".stripslashes($row_page_group[group_name])."</option>";
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
		 <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo $row['page_type']; ?></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo ($row['hide'] == 1)?'Yes':'No'; ?></td>
         <?php 
         if($ecom_site_mobile_api==1)
         {
         ?>
          <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo ($row['in_mobile_api_sites'] == 1)?'Yes':'No'; ?></td>
         <?php
	     }
         ?>  
        </tr>
      <?
	  }
	  }
	  else
	  {
	  ?>
	  <tr>
				  <td align="center" valign="middle" class="norecordredtext" colspan="6" >
				  	No Static Page exists.				  </td>
		  </tr>
		<?
		}
		?>	
		
      </table>
	  <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="listingtable">
	  <tr class="maintable">
			 	<td class="listeditd">
	  				<a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=stat_page&fpurpose=add&records_per_page=<?=$records_per_page?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$start?>&pg=<?=$pg?>" class="addlist" onclick="show_processing()">Add</a> 
		<?php	if($numcount)
				{
		?>			<a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>','<? echo $_REQUEST['sel_group']?>')" class="deletelist">Delete</a>
		<?php	}
		?>		</td>
				<td class="listeditd"  align="right">
			</td>
			</tr>
			</table>
		</div>
		</td>
	</tr>
	<tr class="maintable">
			 	
				<td class="listing_bottom_paging"  align="right" colspan="2">
		<?php	if($numcount)
				{
					paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
				}
		?>		</td>
			</tr>
  </table>
</form>
<!-- Script for auto complete starts here -->
<script language="javascript">
var $pnc = jQuery.noConflict();
$pnc().ready(function() { 
	auto_search('search_name','statpage'); 
});
</script>
<!-- Script for auto complete ends here -->