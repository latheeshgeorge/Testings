<?php
	/*#################################################################
	# Script Name 	: list_todo.php
	# Description 	: Page for listing product enquire
	# Coded by 		: Latheesh
	# Created on	: 03-Jan-2013
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
//Define constants for this page
$table_name='events_calendar';
$page_type='Events';
$help_msg = get_help_messages('LIST_TODO_MESS1');
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistTdolist,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistTdolist,\'checkbox[]\')"/>','Slno.','Date Added','Title','Event Order' ,'Suspended?');
$header_positions=array('left','left','center','left','left','left');
$colspan = count($table_headers);

//#Search terms
$search_fields = array(' 	event_title');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?' event_date':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'DESC':$_REQUEST['sort_order'];
$sort_options = array('event_title' => 'Title','event_suspend' => 'Status','event_date' => 'Date Added' );
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
if($_REQUEST['search_name']) {
	$where_conditions .= "AND ( event_title LIKE '%".add_slash($_REQUEST['search_name'])."%')";
}
if($_REQUEST['search_status']=='')
{
 $_REQUEST['search_status'] ='0';
}
if($_REQUEST['search_status'] && $_REQUEST['search_status']!='All') {
	$where_conditions .= "AND ( event_suspend LIKE '%".add_slash($_REQUEST['search_status'])."%')";
}

$from_date 	= add_slash($_REQUEST['srch_review_startdate']);
$to_date 	= add_slash($_REQUEST['srch_review_enddate']);
if ($from_date or $to_date)
{
	// Check whether from and to dates are valid
	$valid_fromdate = is_valid_date($from_date,'normal','-');
	$valid_todate	= is_valid_date($to_date,'normal','-');
	if($valid_fromdate)
	{
		$frm_arr 		= explode('-',$from_date);
		$mysql_fromdate = $frm_arr[2].'-'.$frm_arr[1].'-'.$frm_arr[0]; 
	}
	else// case of invalid from date
		$_REQUEST['srch_review_startdate'] = '';
		
	if($valid_todate)
	{
		$to_arr 		= explode('-',$to_date);
		$mysql_todate 	= $to_arr[2].'-'.$to_arr[1].'-'.$to_arr[0]; 
	}
	else // case of invalid to date
		$_REQUEST['srch_review_enddate'] = '';
	if($valid_fromdate and $valid_todate)// both dates are valid
	{
		$where_conditions .= " AND (event_date BETWEEN '".$mysql_fromdate."' AND '".$mysql_todate."') ";
	}
	elseif($valid_fromdate and !$valid_todate) // only from date is valid
	{
		$where_conditions .= " AND event_date >= '".$mysql_fromdate."' ";
	}
	elseif(!$valid_fromdate and $valid_todate) // only to date is valid
	{
		$where_conditions .= " AND event_date <= '".$mysql_todate."' ";
	}
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
 $query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=todo&records_per_page=$records_per_page&search_name=".$_REQUEST['search_name']."&search_status=".$_REQUEST['search_status']."&start=$start&srch_review_startdate=".$_REQUEST['srch_review_startdate']."&srch_review_enddate=".$_REQUEST['srch_review_enddate']."";

?>
<script language="javascript">
function call_ajax_changestatus(search_name,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var Eid				= 0;
	var E_ids 			= '';
	var E_orders			= '';
	var ch_status			= document.frmlistTdolist.cbo_changestatus.value;
	var startdate   = '<?=$_REQUEST['srch_review_startdate']?>';
	var enddate   	= '<?=$_REQUEST['srch_review_enddate']?>';
	var search_status		= '<?php echo $_REQUEST['search_status']?>';
	var qrystr				= 'search_status='+search_status+'&search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&ch_status='+ch_status+'&start='+start+'&pg='+pg+'&srch_review_startdate='+startdate+'&srch_review_enddate='+enddate;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlistTdolist.elements.length;i++)
	{
		if (document.frmlistTdolist.elements[i].type =='checkbox' && document.frmlistTdolist.elements[i].name=='checkbox[]')
		{

			if (document.frmlistTdolist.elements[i].checked==true)
			{
				atleastone = 1;
				if (E_ids !='')
					E_ids  += '~';
				 E_ids  += document.frmlistTdolist.elements[i].value;
			}	
		}
	}
	if (atleastone==0) 
	{
		alert('Please select the event to change the status');
	}
	else
	{
		if(confirm('Change Status of Seleted Event(s)?'))
		{
				show_processing();
				Handlewith_Ajax('services/todolist.php','fpurpose=change_status&'+qrystr+'&event_ids='+E_ids);
		}	
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
	var search_status		= '<?php echo $_REQUEST['search_status']?>';
	var startdate   = '<?=$_REQUEST['srch_review_startdate']?>';
	var enddate   	= '<?=$_REQUEST['srch_review_enddate']?>';

	var qrystr				= 'search_status='+search_status+'&search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&srch_review_startdate='+startdate+'&srch_review_enddate='+enddate;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlistTdolist.elements.length;i++)
	{
		if (document.frmlistTdolist.elements[i].type =='checkbox' && document.frmlistTdolist.elements[i].name=='checkbox[]')
		{

			if (document.frmlistTdolist.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmlistTdolist.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select Event to delete');
	}
	else
	{
		if(confirm('Are you sure you want to delete selected Event?'))
		{
			show_processing();
			Handlewith_Ajax('services/todolist.php','fpurpose=delete&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
function edit_selected()
{
	
	len=document.frmlistTdolist.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistTdolist.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
				user_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one Event ');
	}
	else if(cnt>1 ){
		alert('Please select only one Event to edit');
	}
	else
	{
		show_processing();
		document.frmlistTdolist.fpurpose.value='list';
		document.frmlistTdolist.submit();
	}
}
</script>
<form name="frmlistTdolist" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="request" value="todo" />
<input type="hidden" name="start" value="<?=$start?>" />
<input type="hidden" name="pg" value="<?=$pg?>" />
  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td colspan="5" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span>List Events</span></div></td>
    </tr>
	<tr>
	  <td colspan="5" align="left" valign="middle" class="helpmsgtd_main">
	  <?php 
		  Display_Main_Help_msg($help_arr,$help_msg);
	  ?>	 </td>
	</tr>
	<?php 
			if($alert)
			{			
		?>
        		<tr>
          			<td colspan="5" align="center" valign="middle" class="errormsg"><?php echo($alert);?></td>
          		</tr>
		 <?php
		 	}
		 ?> 
	<?php
	  if($numcount)
	  {	   
	  ?>
	<tr>
		<td colspan="5" align="right" valign="middle" class="sorttd"><?php  paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?></td>
	</tr>
	<?php
	  }
	?>
    <tr>
      <td height="48"  colspan="10" class="sorttd" >
	  <div class="editarea_div">
      	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttabletop" >
        <tr>
          <td height="30"  align="left" valign="middle" >Title </td>
          <td height="30"   align="left" valign="middle" ><input name="search_name" type="text" class="textfeild" id="search_name" value="<?=$_REQUEST['search_name']?>"  />          </td>
          <td height="30"  align="left" valign="middle">Suspended:</td> 
          <td height="30"  align="left" valign="middle" > 
		    <select name="search_status" class="dropdown" id="search_status">
			  <option value="All">Show All Status</option>
            <option value="0" <?=($_REQUEST['search_status']=='0')?'selected':'';?> >No</option>
			<option value="1" <?=($_REQUEST['search_status']=='1')?'selected':''; ?>>Yes</option>
			
          </select>&nbsp;         </td>
		  
		   <td height="30"  align="left" valign="middle" >Date Range </td> 
		   <td  height="30" colspan="6" align="left" valign="middle" >From
             <input class="textfeild" type="text" name="srch_review_startdate" size="6" value="<?=$_REQUEST['srch_review_startdate']?>">
             &nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:show_calendar('frmlistTdolist.srch_review_startdate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" align="absmiddle" /></a> To  
                <input class="textfeild" type="text" name="srch_review_enddate" size="6" value="<?=$_REQUEST['srch_review_enddate']?>"  />&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:show_calendar('frmlistTdolist.srch_review_enddate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0"  align="absmiddle"/></a></td>
		  </tr>
        <tr>
          <td height="30"  align="left" valign="middle" >Records Per Page </td>
          <td height="30"   align="left" valign="middle" ><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/></td>
          <td height="30"  align="left" valign="middle" >Sort By </td>
          <td height="30"  align="left" valign="middle" ><?=$sort_option_txt?>&nbsp;&nbsp;&nbsp;in&nbsp;&nbsp;&nbsp; <?=$sort_by_txt?></td>
          <td height="30"  align="left" valign="middle">&nbsp;</td>
          <td height="30"  align="left" valign="middle" >&nbsp;</td>
          <td height="30"  align="left" valign="middle" >&nbsp;</td>
          <td height="30"  align="right" valign="middle" ><input name="button5" type="submit" class="red" id="button5" value="Go" />
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_TODO_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
      </table>
	  </div>	  </td>
	  </tr>
	  
     
    <tr>
      <td colspan="10" class="listingarea">
	  <div class="listingarea_div">
      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable">
	  <tr>
			<td align="left" valign="middle" class="listeditd" colspan="4" >
			<a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=todo&fpurpose=add&records_per_page=<?=$records_per_page?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$start?>&pg=<?=$pg?>" class="addlist" onclick="show_processing()">Add</a> 
			<?
			  if($numcount)
			  {
			  ?> <a href="#" onclick="edit_selected()" class="editlist">Edit</a><a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>	  </td> <?
			  }
			  ?>
			</td>
			  <td align="right" valign="middle" class="listeditd" colspan="5" >
			  <?
			  if($numcount)
			  {
			 ?>Suspend
          <select name="cbo_changestatus" class="dropdown" id="cbo_changestatus">
            <option value="0">No</option>
            <option value="1">Yes</option>			
          </select>&nbsp;<input name="change_status" type="button" class="red" id="change_status" value="Change" onclick="call_ajax_changestatus('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_TODO_CHSTATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
			 <?php
			  }
			 ?>
			  </td>
	  </tr>
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
	   $sql_user = "SELECT  event_id, event_title, event_suspend,event_order,date_format(event_date,'%d-%b-%Y') as added_date FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
	   $res = $db->query($sql_user);
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
        <tr>
          <td align="left" valign="middle" class="<?=$class_val;?>"  width="6%"><input name="checkbox[]" value="<? echo $row['event_id']?>" type="checkbox"></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"  width="2%"><?php echo $srno++?></td>
		   <td align="center" valign="middle" class="<?=$class_val;?>" width="18%"><a href="home.php?request=todo&fpurpose=list&checkbox[0]=<?php echo $row['event_id']?>&search_name=<?php echo $_REQUEST['search_name']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&search_status=<?=$_REQUEST['search_status']?>&srch_review_startdate=<?=$_REQUEST['srch_review_startdate']?>&srch_review_enddate=<?=$_REQUEST['srch_review_enddate']?>" title="<? echo $row['event_date']?>" class="edittextlink" onclick="show_processing()"><? echo $row['added_date']?></a></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><? echo $row['event_title'] ?></td>
		    <td align="left" valign="middle" class="<?=$class_val;?>" width="12%"><?php echo $row['event_order']; ?></td>
					   <td align="left" valign="middle" class="<?=$class_val;?>" width="12%"><?php echo $row['event_suspend']; ?></td>


        </tr>
      <?
	  }
	  }
	  else
	  {
	  ?>
	  <tr>
				  <td align="center" valign="middle" class="norecordredtext" colspan="9" >No Enquiry exists.</td>
		  </tr>
		<?
		}
		?>
		<tr>
			<td align="left" valign="middle" class="listeditd" colspan="4" >
			<a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=todo&fpurpose=add&records_per_page=<?=$records_per_page?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$start?>&pg=<?=$pg?>" class="addlist" onclick="show_processing()">Add</a> 
			<?
			  if($numcount)
			  {
			  ?> <a href="#" onclick="edit_selected()" class="editlist">Edit</a><a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>	  </td> <?
			  }
			  ?>
			</td>
			  <td align="right" valign="middle" class="listeditd" colspan="5" >
			 
			  </td>
	  </tr>
      </table>
	  </div></td>
    </tr>
	<tr>
			
			  <td align="right" valign="middle" class="listing_bottom_paging" colspan="2" >
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
