<?php
	/*#################################################################
	# Script Name 	: list_prodenquire.php
	# Description 	: Page for listing product enquire
	# Coded by 		: Latheesh
	# Created on	: 03-Mar-2008
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
//Define constants for this page
$table_name='seo_redirect';
$page_type='301 Redirect URL';
$help_msg = get_help_messages('LIST_REDIRECT_MESS1');
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistRedirect,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistRedirect,\'checkbox[]\')"/>','Slno.','Old URL','New URL','Date Of Access');
$header_positions=array('left','left','left','left');
$colspan = count($table_headers);

//#Search terms
$search_fields = array('redirect_old_url','redirect_new_url');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'redirect_last_access_date':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'DESC':$_REQUEST['sort_order'];
$sort_options = array('redirect_old_url' => 'Old URL','redirect_new_url' => 'New URL','redirect_last_access_date' => 'Last Access Date' );
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
if($_REQUEST['redirect_old_url']) {
	$where_conditions .= "AND ( redirect_old_url LIKE '%".add_slash($_REQUEST['redirect_old_url'])."%')";
}
if($_REQUEST['redirect_new_url']) {
	$where_conditions .= "AND ( redirect_new_url LIKE '%".add_slash($_REQUEST['redirect_new_url'])."%')";
}
$from_date 	= add_slash($_REQUEST['srch_access_startdate']);
$to_date 	= add_slash($_REQUEST['srch_access_enddate']);
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
		$_REQUEST['srch_access_startdate'] = '';
		
	if($valid_todate)
	{
		$to_arr 		= explode('-',$to_date);
		$mysql_todate 	= $to_arr[2].'-'.$to_arr[1].'-'.$to_arr[0]; 
	}
	else // case of invalid to date
		$_REQUEST['srch_access_enddate'] = '';
	if($valid_fromdate and $valid_todate)// both dates are valid
	{
		$where_conditions .= " AND (date_format(redirect_last_access_date,'%Y-%m-%d') BETWEEN '".$mysql_fromdate."' AND '".$mysql_todate."') ";
	}
	elseif($valid_fromdate and !$valid_todate) // only from date is valid
	{
		$where_conditions .= " AND date_format(redirect_last_access_date,'%Y-%m-%d') >= '".$mysql_fromdate."' ";
	}
	elseif(!$valid_fromdate and $valid_todate) // only to date is valid
	{
		$where_conditions .= " AND date_format(redirect_last_access_date,'%Y-%m-%d') <= '".$mysql_todate."' ";
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
 $query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=seo_301redirect&records_per_page=$records_per_page&redirect_old_url=".$_REQUEST['redirect_old_url']."&redirect_new_url=".$_REQUEST['redirect_new_url']."&start=$start&srch_access_startdate=".$_REQUEST['srch_access_startdate']."&srch_access_enddate=".$_REQUEST['srch_access_enddate']."";

?>

<script language="javascript">
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
function call_ajax_delete(old_url,new_url,start_date,end_date,sortby,sortorder,recs,start,pg)
{
	var atleastone 	= 0;
	var del_ids 	= '';
	var qrystr				= 'redirect_old_url='+old_url+'&redirect_new_url='+new_url+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&srch_access_startdate='+start_date+'&srch_access_enddate='+end_date;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlistRedirect.elements.length;i++)
	{
		if (document.frmlistRedirect.elements[i].type =='checkbox' && document.frmlistRedirect.elements[i].name=='checkbox[]')
		{

			if (document.frmlistRedirect.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmlistRedirect.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select URL to delete');
	}
	else
	{
		if(confirm('Are you sure you want to delete selected URL?'))
		{
			show_processing();
			Handlewith_Ajax('services/seo_301redirect.php','fpurpose=delete&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
function edit_selected()
{
	
	len=document.frmlistRedirect.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistRedirect.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
				redirect_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one URL ');
	}
	else if(cnt>1 ){
		alert('Please select only one URL to edit');
	}
	else
	{
		show_processing();
		document.frmlistRedirect.fpurpose.value='edit';
		document.frmlistRedirect.submit();
	}
	
	
}

</script>
<form name="frmlistRedirect" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="request" value="seo_301redirect" />
<input type="hidden" name="start" value="<?=$start?>" />
<input type="hidden" name="pg" value="<?=$pg?>" />
  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td colspan="5" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span> List 301 Redirect URLs</span></div></td>
    </tr>
	<tr>
	  <td colspan="5" align="left" valign="middle" class="helpmsgtd_main">
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
          			<td colspan="5" align="center" valign="middle" class="errormsg"><?php echo($alert);?></td>
          		</tr>
		 <?php
		 	}
		 ?> 
	<?php	if($numcount)
	  		{
	?>
    <tr><td colspan="5" align="right" valign="middle" class="sorttd"><?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?></td></tr>
	<?php	}
	?>
	<tr>
      <td height="48"  colspan="5" class="sorttd" >
	  <div class="sorttd_div">
      	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="listingtable" >
        <tr>
          <td align="left" valign="middle" colspan="6" >Old URL</td>
          <td  align="left" valign="middle" colspan="6" ><input name="redirect_old_url" type="text" class="textfeild" id="redirect_old_url" value="<?=$_REQUEST['redirect_old_url']?>"  /> 
          </td><td colspan="6" align="left" valign="middle" >New URL </td>
		  <td colspan="6" align="left" valign="middle" >
            <input name="redirect_new_url" type="text" class="textfeild" id="redirect_new_url" value="<?=$_REQUEST['redirect_new_url']?>"></td>
          <td colspan="6" align="left" valign="middle"></td> 
          <td width="17%" colspan="6" align="left" valign="middle" >&nbsp; 
		    
         </td>
		  
		   <td colspan="6" align="left" valign="bottom" >Date From <input class="textfeild" type="text" name="srch_access_startdate" size="10" value="<?=$_REQUEST['srch_access_startdate']?>"  />&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:show_calendar('frmlistRedirect.srch_access_startdate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" align="absmiddle" /></a></td> <td colspan="6" align="left" valign="bottom" >Date To  
                <input class="textfeild" type="text" name="srch_access_enddate" size="10" value="<?=$_REQUEST['srch_access_enddate']?>"  />&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:show_calendar('frmlistRedirect.srch_access_enddate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0"  align="absmiddle"/></a></td>
		  </tr>
      </table><br /><br />
	  <table width="100%" border="0" cellpadding="0" cellspacing="0"  class="listingtable">
        <tr>
          <td  align="left" colspan="3">Records Per Page </td>
          <td width="35%"  align="left" ><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/></td>
          <td width="45%" align="left" nowrap="nowrap" ><?=$sort_option_txt?> in <?=$sort_by_txt?>  
          &nbsp;&nbsp;&nbsp;&nbsp;
          <input name="button5" type="submit" class="red" id="button5" value="Go" /> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_REDIRECT_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          <td width="4%" align="left">&nbsp;</td>
		  <td width="4%" align="left">&nbsp;</td>        
	     </tr>
      </table></div>
	  </td>
    </tr>
	  
    
    <tr>
      <td colspan="5" class="listingarea">
	  <div class="listingarea_div">
      <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="listingtable">
	  <tr> <td class="listeditd" align="left" valign="middle" colspan="5"><a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=seo_301redirect&fpurpose=add&records_per_page=<?=$records_per_page?>&redirect_old_url=<?=$_REQUEST['redirect_old_url']?>&redirect_new_url=<?=$_REQUEST['redirect_new_url']?>&srch_access_startdate=<?=$_REQUEST['srch_access_startdate']?>&srch_access_enddate=<?=$_REQUEST['srch_access_enddate']?>&start=<?=$start?>&pg=<?=$pg?>" class="addlist" onclick="show_processing()">Add</a><?
	  if($numcount)
	  {
	  ?>
	   <a href="#" onclick="edit_selected()" class="editlist">Edit</a><a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['redirect_old_url']?>','<?php echo $_REQUEST['redirect_new_url']?>','<?php echo $_REQUEST['srch_access_startdate']?>','<?php echo $_REQUEST['srch_access_enddate']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
	   <?
	  }
	  ?> </td>
	  
    </tr>
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
	   $sql_user = "SELECT redirect_id,redirect_old_url,redirect_new_url, date_format(redirect_last_access_date,'%d-%b-%Y') as added_date FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
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
			<tr >
			  <td align="left" valign="middle" class="<?=$class_val;?>"  width="6%"><input name="checkbox[]" value="<? echo $row['redirect_id']?>" type="checkbox"></td>
			  <td align="left" valign="middle" class="<?=$class_val;?>"  width="3%"><?php echo $srno++?></td>
			  <td width="30%" align="left" valign="middle" class="<?=$class_val;?>"><a href="home.php?request=seo_301redirect&fpurpose=edit&checkbox[0]=<?php echo $row['redirect_id']?>&redirect_old_url=<?php echo $_REQUEST['redirect_old_url']?>&redirect_new_url=<?php echo $_REQUEST['redirect_new_url']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&search_status=<?=$_REQUEST['search_status']?>&srch_access_startdate=<?=$_REQUEST['srch_access_startdate']?>&srch_access_enddate=<?=$_REQUEST['srch_access_enddate']?>" title="<? echo $row['added_date']?>" class="edittextlink" onclick="show_processing()"><? echo stripslashes($row['redirect_old_url']);?></a></td>
			  <td width="31%" align="left" valign="middle" class="<?=$class_val;?>"><a href="home.php?request=seo_301redirect&fpurpose=edit&checkbox[0]=<?php echo $row['redirect_id']?>&redirect_old_url=<?php echo $_REQUEST['redirect_old_url']?>&redirect_new_url=<?php echo $_REQUEST['redirect_new_url']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&search_status=<?=$_REQUEST['search_status']?>&srch_access_startdate=<?=$_REQUEST['srch_access_startdate']?>&srch_access_enddate=<?=$_REQUEST['srch_access_enddate']?>" title="<? echo $row['added_date']?>" class="edittextlink" onclick="show_processing()"><? echo stripslashes($row['redirect_new_url']);?></a></td>
				 <td width="30%" align="center" valign="middle" class="<?=$class_val;?>"><? echo $row['added_date']?></td>
		  </tr>
		  <?
		  }
	  }
	  else
	  {
	  ?>
	  <tr>
				  <td align="center" valign="middle" class="norecordredtext" colspan="5" >No URL exists.</td>
		  </tr>
		<?
		}
		?>	
		<tr> <td class="listeditd" colspan="3" align="left" valign="middle"><a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=seo_301redirect&fpurpose=add&records_per_page=<?=$records_per_page?>&redirect_old_url=<?=$_REQUEST['redirect_old_url']?>&redirect_new_url=<?=$_REQUEST['redirect_new_url']?>&srch_access_startdate=<?=$_REQUEST['srch_access_startdate']?>&srch_access_enddate=<?=$_REQUEST['srch_access_enddate']?>&start=<?=$start?>&pg=<?=$pg?>" class="addlist" onclick="show_processing()">Add</a><?
	  if($numcount)
	  {
	  ?>
	   <a href="#" onclick="edit_selected()" class="editlist">Edit</a><a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['redirect_old_url']?>','<?php echo $_REQUEST['redirect_new_url']?>','<?php echo $_REQUEST['srch_access_startdate']?>','<?php echo $_REQUEST['srch_access_enddate']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
	 
	   <?
	  }
	  ?> </td>
	   <td class="listeditd"   align="right" valign="middle" colspan="2">
	  <?
	  	if($numcount)
	  	{	paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 	}
	   ?>
	  </td> 
    </tr>
      </table>
	  </div>
	  </td>
    </tr>
  </table>
</form>
