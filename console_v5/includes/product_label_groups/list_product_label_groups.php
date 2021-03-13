<?php
	/*#################################################################
	# Script Name 	: list_product_label_groups.php
	# Description 	: Page for listing Product label Groups
	# Coded by 		: Sny
	# Created on	: 07-AprJune-2010
	# Modified by	: 
	# Modified On	:
	#################################################################*/
//Define constants for this page
$table_name			= 'product_labels_group';
$page_type			= 'Product Label Groups';
$help_msg 			= get_help_messages('LIST_PROD_LAB_GRP_MESS1');
$table_headers 		= array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistProductLabelGroups,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistProductLabelGroups,\'checkbox[]\')"/>','Slno.','Group Name','Sort Order','Hidden?','Group Name Hidden?');
$header_positions	= array('left','left','left','center','center');
$colspan 			= count($table_headers);

//#Search terms
$search_fields = array('group_name');
foreach($search_fields as $v) {
	$query_string .= "$v=${$v}&";#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by 			= (!$_REQUEST['sort_by'])?'group_name':$_REQUEST['sort_by'];
$sort_order 		= (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options 		= array('group_name' => 'Group Name','group_order'=>'Sort Order');
$sort_option_txt 	= generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt		= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
if($_REQUEST['search_name']) {
	$where_conditions .= "AND ( group_name LIKE '%".add_slash($_REQUEST['search_name'])."%')";
}

//#Select condition for getting total count
$sql_count 		= "SELECT count(*) as cnt FROM $table_name  $where_conditions";
$res_count 		= $db->query($sql_count);
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
$query_string .= "request=prod_label_groups&sort_by=$sort_by&sort_order=$sort_order&records_per_page=$records_per_page&search_name=".$_REQUEST['search_name']."&start=$start";
?>
<script language="javascript">
function call_ajax_changestatus(search_name,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var curid				= 0;
	var group_ids 			= '';
	var ch_status			= document.frmlistProductLabelGroups.cbo_changehide.value;
	var qrystr				= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&ch_status='+ch_status+'&start='+start+'&pg='+pg;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlistProductLabelGroups.elements.length;i++)
	{
		if (document.frmlistProductLabelGroups.elements[i].type =='checkbox' && document.frmlistProductLabelGroups.elements[i].name=='checkbox[]')
		{

			if (document.frmlistProductLabelGroups.elements[i].checked==true)
			{
				atleastone = 1;
				if (group_ids!='')
					group_ids += '~';
				 group_ids += document.frmlistProductLabelGroups.elements[i].value;
			}	
		}
	}
	if (atleastone==0) 
	{
		alert('Please select the Product Label Group(s) to change the hide status');
	}
	else
	{
		if(confirm('Change Hidden Status of Seleted Product Label Group(s)?'))
		{
				show_processing();
				Handlewith_Ajax('services/product_label_groups.php','fpurpose=change_hide&'+qrystr+'&group_ids='+group_ids);
		}	
	}	
}
function call_ajax_saveorder(search_name,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var curid				= 0;
	var group_ids 			= '';
	var group_orders			= '';
	var qrystr				= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlistProductLabelGroups.elements.length;i++)
	{
		if (document.frmlistProductLabelGroups.elements[i].type =='checkbox' && document.frmlistProductLabelGroups.elements[i].name=='checkbox[]')
		{

			if (document.frmlistProductLabelGroups.elements[i].checked==true)
			{
				atleastone = 1;
				if (group_ids!='')
					group_ids += '~';
				 group_ids += document.frmlistProductLabelGroups.elements[i].value;
				obj = eval("document.getElementById('group_order_"+document.frmlistProductLabelGroups.elements[i].value+"')");
				if(obj)
				{
					if (group_orders != '')
						group_orders += '~';
				 	group_orders += ' '+obj.value;
				} 
			}	
		}
	}
	if (atleastone==0) 
	{
		alert('Please select the Product Group(s) to save the sort order');
	}
	else
	{
		if(confirm('Save the sort order of selected group(s)?'))
		{
				show_processing();
				Handlewith_Ajax('services/product_label_groups.php','fpurpose=save_order&'+qrystr+'&group_ids='+group_ids+'&group_orders='+group_orders);
		}	
	}	
}
function checkSelected()
{
	len=document.frmlistProductLabelGroups.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistProductLabelGroups.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
				user_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one Product Label ');
		return false;
	}
	show_processing();
	return true;
}
function edit_selected()
{
	
	len=document.frmlistProductLabelGroups.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistProductLabelGroups.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
				vendor_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one Label Group ');
	}
	else if(cnt>1 ){
		alert('Please select only one Label Group to edit');
	}
	else
	{
		show_processing();
		document.frmlistProductLabelGroups.fpurpose.value='edit';
		document.frmlistProductLabelGroups.submit();
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
	for(i=0;i<document.frmlistProductLabelGroups.elements.length;i++)
	{
		if (document.frmlistProductLabelGroups.elements[i].type =='checkbox' && document.frmlistProductLabelGroups.elements[i].name=='checkbox[]')
		{

			if (document.frmlistProductLabelGroups.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmlistProductLabelGroups.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select product label group(s) to delete');
	}
	else
	{
		if(confirm('Are you sure you want to delete selected Product label Group(s)?'))
		{
			show_processing();
			Handlewith_Ajax('services/product_label_groups.php','fpurpose=delete&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
</script>
<form name="frmlistProductLabelGroups" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="request" value="prod_label_groups" />
<input type="hidden" name="start" value="<?=$start?>" />
<input type="hidden" name="pg" value="<?=$pg?>" />
  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td align="left" valign="middle" class="treemenutd" colspan="3"><div class="treemenutd_div"><span> List Product Label Groups</span></div></td>
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
	  if($numcount)
	  {
	    
		 ?> 
    <tr>
		<td colspan="3" align="right" valign="middle" class="sorttd"><?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?></td>
	</tr>
	<?php
	  }
	?>
	<tr>
      <td height="48" class="sorttd" colspan="3">
	  <div class="sorttd_div">
      	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttabletop">
        <tr>
          <td width="8%" height="30" align="left" valign="middle">Group Name </td>
          <td width="20%" height="30" align="left" valign="middle"><input name="search_name" type="text" class="textfeild" id="search_name" value="<?=$_REQUEST['search_name']?>"  /></td>
          <td width="11%" height="30" align="left" valign="middle">Records Per Page </td>
          <td width="11%" height="30" align="left" valign="middle"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/></td>
          <td width="8%" height="30" align="left" valign="middle">Sort By</td>
          <td width="22%" height="30" align="left" valign="middle"><?=$sort_option_txt?>&nbsp;&nbsp;&nbsp;in&nbsp;&nbsp;&nbsp;<?=$sort_by_txt?></td>
          <td width="20%" height="30" align="right" valign="middle"><input name="button5" type="submit" class="red" id="button5" value="Go" />
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_LAB_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          </tr>
      </table>
	  </div>
      </td>
	</tr>
    
    <tr>
      <td class="listingarea" colspan="3">
	  <div class="listingarea_div">
      <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="listingtable">
	  <tr>
      <td class="listeditd" align="left" valign="middle" colspan="3"><a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=prod_label_groups&fpurpose=add&records_per_page=<?=$records_per_page?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$start?>&pg=<?=$_REQUEST['pg']?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>" class="addlist" onclick="show_processing()">Add</a> 
	  <?
	  if($numcount)
	  {
	  ?>
	  <a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
	  <?
	  }
	  ?>
	  </td>
	   <td class="listeditd" align="right" valign="middle" colspan="3">
	  <?
	  if($numcount)
	  {
	  ?>
		<input name="save_order" type="button" class="red" id="save_order" value="Save Sort Order" onclick="call_ajax_saveorder('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" />	  	
        &nbsp;
		Change Status
          <select name="cbo_changehide" class="dropdown" id="cbo_changehide">
            <option value="1">Yes</option>
			<option value="0">No</option>
          </select>&nbsp;<input name="change_hide" type="button" class="red" id="change_hide" value="Change" onclick="call_ajax_changestatus('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_LAB_CHSTATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
		<?
		}
		?>
   	   </td>
    </tr>
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
	   $sql_vendor = "SELECT group_id,group_name,group_order,group_hide,group_name_hide  FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
	   
	   $res = $db->query($sql_vendor); 
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
          <td align="left" valign="middle" class="<?=$class_val;?>" width="5%" ><input name="checkbox[]" value="<? echo $row['group_id']?>" type="checkbox"></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>" width="5%"><?php echo $srno++?></td>
          <td align="left" valign="middle" class="<?=$class_val;?>" width="36%"><a href="home.php?request=prod_label_groups&fpurpose=edit&checkbox[0]=<?php echo $row['group_id']?>&search_name=<?php echo $_REQUEST['search_name']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_order=<?=$_REQUEST['sort_order']?>&sort_by=<?=$_REQUEST['sort_by']?>" title="<? echo $row['vendor_name']?>" class="edittextlink" onclick="show_processing()"><? echo $row['group_name']?></a></td>
		   <td width="28%" align="center" valign="middle" class="<?=$class_val;?>"><input type="text" size="5" name="group_order_<?php echo $row['group_id']?>" id="group_order_<?php echo $row['group_id']?>" value="<?php echo $row['group_order']?>" /></td>
		  <td width="13%" align="center" valign="middle" class="<?=$class_val;?>"><?php echo ($row['group_hide'] == 1)?'Yes':'No'; ?></td>
		  <td width="13%" align="center" valign="middle" class="<?=$class_val;?>"><?php echo ($row['group_name_hide'] == 1)?'Yes':'No'; ?></td>
        </tr>
      <?
	  }
	  }
	  else
	  {
	  ?>
	  <tr><td align="center" colspan="6" valign="middle" class="norecordredtext" >No Product Label Groups Exists. </td></tr>
		<?
		}
		?>	<tr>
      <td class="listeditd" align="left" valign="middle" colspan="3" ><a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=prod_label_groups&fpurpose=add&records_per_page=<?=$records_per_page?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$start?>&pg=<?=$_REQUEST['pg']?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>" class="addlist" onclick="show_processing()">Add</a> 
	  <?
	  if($numcount)
	  {
	  ?>
	  <a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
	  <?
	  }
	  ?>
	  </td>
	  <td class="listeditd" align="right" valign="middle" colspan="3" >
	   
	  </td>
    </tr>
      </table>
	  </div></td>
    </tr>
	<tr>
	<td class="listing_bottom_paging" align="right" valign="middle" colspan="2" >
	    <?
	  if($numcount)
	  {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?>
	  </td>
	  </tr>
  </table>
</form>
