<?php
	/*#################################################################
	# Script Name 	: list_site_reviews.php
	# Description 	: Page for listing Site Reviews 
	# Coded by 		: ANU
	# Created on	: 14-Aug-2007
	# Modified by	: ANU
	# Modified On	: 13-Aug-2007
	#################################################################*/
//Define constants for this page
$table_name='sites_reviews ';
$page_type='Site Reviews';
$help_msg = get_help_messages('LIST_SITE_REVIEW_MESS1');
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistSiteReviews,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistSiteReviews,\'checkbox[]\')"/>','Slno.','Review Date','Author','Status','Hidden');
$header_positions=array('left','left','left','left','left','left','left');
$colspan = count($table_headers);

//#Search terms
$search_fields = array('review_author');


$query_string = "request=site_reviews";
foreach($search_fields as $v) {
	$query_string .= "&$v=".$_REQUEST[$v]."";//#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'review_date':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'DESC':$_REQUEST['sort_order'];
$sort_options = array('review_author' => 'Review Author','review_date' => 'Review Date');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = " WHERE  sites_site_id=$ecom_siteid ";

if($_REQUEST['srch_author']) {
	$where_conditions .= "AND (review_author LIKE '%".add_slash($_REQUEST['srch_author'])."%')";
}
if($_REQUEST['srch_review_status']=='')
{
$srch_review_status=$_REQUEST['srch_review_status'] = "ALL";
}
if($_REQUEST['srch_review_status'] && $_REQUEST['srch_review_status']!= 'ALL') {
	$where_conditions .= " AND (review_status LIKE '%".add_slash($_REQUEST['srch_review_status'])."%')";
}
if($_REQUEST['srch_review_startdate'] && $_REQUEST['srch_review_enddate'] ) {
	$fromdate_arr = explode("-",add_slash($_REQUEST['srch_review_startdate']));
	$fromdate = $fromdate_arr[2]."-".$fromdate_arr[1]."-".$fromdate_arr[0];
	$todate_arr = explode("-",add_slash($_REQUEST['srch_review_enddate']));
	$todate =$todate_arr[2]."-".$todate_arr[1]."-".$todate_arr[0];
	$where_conditions .= " AND (review_date >= '".$fromdate."' AND review_date <= '".$todate."' )";
}
if($_REQUEST['srch_review_startdate'] && $_REQUEST['srch_review_enddate']=='' ) {
	$fromdate_arr = explode("-",add_slash($_REQUEST['srch_review_startdate']));
	$fromdate =$fromdate_arr[2]."-".$fromdate_arr[1]."-".$fromdate_arr[0];
	$where_conditions .= " AND (review_date >= '".$fromdate."')";
}
if($_REQUEST['srch_review_startdate']=='' && $_REQUEST['srch_review_enddate'] ) {
	$todate_arr = explode("-",add_slash($_REQUEST['srch_review_enddate']));
	$todate =$todate_arr[2]."-".$todate_arr[1]."-".$todate_arr[0];
	$where_conditions .= " AND (review_date <= '".$todate."' )";
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
if ($pg>=1)
{
	$start = ($pg - 1) * $records_per_page;//#Starting record.
	$count_no = ($pg == 1)?0:$records_per_page*($pg-1);
}	
else
{
	$start = $count_no = 0;	
}

/////////////////////////////////////////////////////////////////////////////////////
$query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=site_reviews&records_per_page=$records_per_page&srch_author=".$_REQUEST['srch_author']."&srch_review_startdate=".$_REQUEST['srch_review_startdate']."&srch_review_enddate=".$_REQUEST['srch_review_enddate']."&srch_review_status=".$_REQUEST['srch_review_status']."&start=$start";

$sql_qry = "SELECT review_id,review_date,review_author,review_status,review_hide  
 					FROM $table_name 
							$where_conditions 
								ORDER BY $sort_by $sort_order 
									LIMIT $start,$records_per_page ";
$ret_qry = $db->query($sql_qry);
?>
<script type="text/javascript">
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
function call_ajax_delete(sortby,sortorder,recs,start,pg,srch_review_status,srch_author,srch_review_startdate,srch_review_enddate)
{
	var atleastone 			= 0;
	var del_ids 			= '';
	var qrystr				= '&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&srch_review_status='+srch_review_status+'&srch_author='+srch_author+'&srch_review_startdate='+srch_review_startdate+'&srch_review_enddate='+srch_review_enddate;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlistSiteReviews.elements.length;i++)
	{
		if (document.frmlistSiteReviews.elements[i].type =='checkbox' && document.frmlistSiteReviews.elements[i].name=='checkbox[]')
		{

			if (document.frmlistSiteReviews.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmlistSiteReviews.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select the Site Reviews to delete');
	}
	else
	{
		if(confirm('Are you sure you want to delete selected Site Reviews?'))
		{
			show_processing();
			Handlewith_Ajax('services/site_reviews.php','fpurpose=delete&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}

function call_ajax_changestatus(sortby,sortorder,recs,start,pg,srch_review_status,srch_author,srch_review_startdate,srch_review_enddate)
{
	var atleastone 			= 0;
	var review_ids			= 0;
	var ch_status			= document.frmlistSiteReviews.cbo_changehide.value;			
	var qrystr				= 'sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&ch_status='+ch_status+'&start='+start+'&pg='+pg+'&srch_review_status='+srch_review_status+'&srch_author='+srch_author+'&srch_review_startdate='+srch_review_startdate+'&srch_review_enddate='+srch_review_enddate;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlistSiteReviews.elements.length;i++)
	{
		if (document.frmlistSiteReviews.elements[i].type =='checkbox' && document.frmlistSiteReviews.elements[i].name=='checkbox[]')
		{
			if (document.frmlistSiteReviews.elements[i].checked==true)
			{
				atleastone = 1;
				if (review_ids!='')
					review_ids += '~';
				 review_ids += document.frmlistSiteReviews.elements[i].value;
			}	
		}
	}
	if (atleastone==0) 
	{
		alert('Please select the Site Reviews to change the hide status');
	}
	else
	{
		if(confirm('Change Hide Status of Seleted Site Review(s)?'))
		{
				show_processing();
				Handlewith_Ajax('services/site_reviews.php','fpurpose=change_hide&'+qrystr+'&reviewids='+review_ids);
		}	
	}	
}
function call_ajax_changereviewstatus(sortby,sortorder,recs,start,pg,srch_review_status,srch_author,srch_review_startdate,srch_review_enddate)
{
	var atleastone 			= 0;
	var review_ids			= 0;

	var ch_revstatus			= document.frmlistSiteReviews.cbo_changestatus.value;
		
	var qrystr				= 'sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&ch_revstatus='+ch_revstatus+'&pg='+pg+'&srch_review_status='+srch_review_status+'&srch_author='+srch_author+'&srch_review_startdate='+srch_review_startdate+'&srch_review_enddate='+srch_review_enddate;
	
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlistSiteReviews.elements.length;i++)
	{
		if (document.frmlistSiteReviews.elements[i].type =='checkbox' && document.frmlistSiteReviews.elements[i].name=='checkbox[]')
		{

			if (document.frmlistSiteReviews.elements[i].checked==true)
			{
				atleastone = 1;
				if (review_ids!='')
					review_ids += '~';
				 review_ids += document.frmlistSiteReviews.elements[i].value;
			}	
		}
	}
	if (atleastone==0) 
	{
		alert('Please select the Site Reviews to change the Review status');
	}
	else
	{
		if(confirm('Change Status of Seleted Site Review(s)?'))
		{
				show_processing();
				Handlewith_Ajax('services/site_reviews.php','fpurpose=change_status&'+qrystr+'&reviewids='+review_ids);
		}	
	}	
}
function go_edit(sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var del_ids 			= '';
	//var qrystr				= '&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlistSiteReviews.elements.length;i++)
	{
		if (document.frmlistSiteReviews.elements[i].type =='checkbox' && document.frmlistSiteReviews.elements[i].name=='checkbox[]')
		{

			if (document.frmlistSiteReviews.elements[i].checked==true)
			{
				atleastone += 1;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select the Site Reviews to edit');
	}
	else
	{
		if(atleastone==1)
		{
			show_processing();
			document.frmlistSiteReviews.fpurpose.value='edit';
			document.frmlistSiteReviews.submit();
		}	
		else
		{
			alert('Please select only one Site Review to delete.');
		}
	}	
}
</script>
<form method="post" name="frmlistSiteReviews" class="frmcls" action="home.php">
<input type="hidden" name="request" value="site_reviews" />
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="search_click" value="" />
<input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />  
  <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span>List Site Reviews</span></div></td>
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
		if ($db->num_rows($ret_qry))
		{
	?>
	<tr>
		<td colspan="3" align="right" valign="middle" class="sorttd">
		<?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?>
		</td>
	</tr>
	<?php
		}
	?>
    <tr>
      <td height="48" colspan="3" class="sorttd">
		<div class="editarea_div">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttabletop">
        <tr>
          <td width="55%" align="left" valign="top">
		  <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td width="7%" align="left">Author</td>
              <td width="8%" align="left"><input name="srch_author" type="text" class="textfeild" id="srch_author" value="<?php echo $_REQUEST['srch_author']?>" /></td>
              <td width="4%" align="left">Status</td>
              <td width="12%" align="left">
				<?=generateselectbox('srch_review_status',array('ALL' => 'All','NEW' => 'New','PENDING' => 'Pending','APPROVED' => 'Approved'),$_REQUEST['srch_review_status']);?></td>
              <td width="4%" align="left" valign="middle">Date Range </td>
              <td width="32%" align="left" valign="middle">From
                <input class="textfeild" type="text" name="srch_review_startdate" size="8" value="<?=$_REQUEST['srch_review_startdate']?>">
                &nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:show_calendar('frmlistSiteReviews.srch_review_startdate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" align="absmiddle" /></a>&nbsp; To   &nbsp;&nbsp;&nbsp;
                <input class="textfeild" type="text" name="srch_review_enddate" size="8" value="<?=$_REQUEST['srch_review_enddate']?>">
                <a href="javascript:show_calendar('frmlistSiteReviews.srch_review_enddate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" align="absmiddle" /></a>&nbsp;(dd-mm-yyyy)</td>
            </tr>
            <tr>
              <td align="left">Records Per Page  </td>
              <td align="left"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3" value="<?php echo $records_per_page?>" /></td>
              <td align="left">Sort By</td>
              <td colspan="2" align="left"><?php echo $sort_option_txt;?>&nbsp;&nbsp;&nbsp;in&nbsp;&nbsp;&nbsp;<?php echo $sort_by_txt?> </td>
              <td align="right"><input name="Search_go" type="submit" class="red" id="Search_go" value="Go" onclick="document.frmlistSiteReviews.search_click.value=1" />
                <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_SITE_REVIEW_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
            </tr>
            <tr>
              <td colspan="6" align="left"></td>
              </tr>
          </table>            </td>
          </tr>
      </table>
	  </div>
      </td>
    </tr>
 
    <tr>
      <td colspan="3" class="listingarea">
	  <div class="listingarea_div" >
      <table width="1100%"  border="0" cellpadding="0" cellspacing="0" class="listingtable">
	  <tr>
      <td class="listeditd" colspan="3">
	  <?php
			if ($db->num_rows($ret_qry))
			{
		?>	
				<a href="#" class="editlist" onclick="go_edit('<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>','<?php echo $_REQUEST['srch_review_status']?>','<?php echo $_REQUEST['srch_author']?>','<?php echo $_REQUEST['srch_review_startdate']?>','<?php echo $_REQUEST['srch_review_enddate']?>')" class="deletelist">Delete</a>
		<?php
			}
		?></td>
      <td colspan="4" align="right" class="listeditd">
	 <?php
			if ($db->num_rows($ret_qry))
			{
			
		?>
		Change Review Status to
		<?php $chstatus_array = array('New'=>'NEW','PENDING'=>'PENDING','APPROVED'=>'APPROVED');
					echo generateselectbox('cbo_changestatus',$chstatus_array,0);?>
		<input name="change_status" type="button" class="red" id="change_status" value="Change" onclick="call_ajax_changereviewstatus('<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>','<?php echo $_REQUEST['srch_review_status']?>','<?php echo $_REQUEST['srch_author'] ?>','<?php echo $_REQUEST['srch_review_startdate']?>','<?php echo $_REQUEST['srch_review_enddate']?>')" />
  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_SITE_REVIEW_REVIEWSTATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;Change Hide Status to
  <?php
					$chhide_array = array('0'=>'No','1'=>'Yes');
					echo generateselectbox('cbo_changehide',$chhide_array,0);
				?>
  <input name="change_hide" type="button" class="red" id="change_hide" value="Change" onclick="call_ajax_changestatus('<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>','<?php echo $_REQUEST['srch_review_status']?>','<?php echo $_REQUEST['srch_author'] ?>','<?php echo $_REQUEST['srch_review_startdate']?>','<?php echo $_REQUEST['srch_review_enddate']?>')" />
  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_SITE_REVIEW_CHSTATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
  <?php
			}
		?></td>
    </tr>
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
	 $sql_site_reviews = "SELECT review_id,review_date,review_author,review_status,review_hide  
	 							FROM $table_name 
									$where_conditions 
										ORDER BY $sort_by $sort_order 
											LIMIT $start,$records_per_page ";
	   
	   $res = $db->query($sql_site_reviews);
	   $srno = getStartOfPageno($records_per_page,$pg); 
	   while($row = $db->fetch_array($res))
	   {
			$count_no++;
			$array_values = array();
			if($count_no %2 == 0)
				$class_val="listingtablestyleA";
			else
				$class_val="listingtablestyleB";
			$exp_review_date1=explode(" ",$row['review_date']);
			$exp_review_date=explode("-",$exp_review_date1[0]);// to remove the time part
			//print_r($exp_review_date);
			$val_review_date=$exp_review_date[2]."-".$exp_review_date[1]."-".$exp_review_date[0];
			$display_review_date =  $val_review_date."&nbsp&nbsp;".$exp_review_date1[1];
   
	   ?>
        <tr >
          <td align="left" valign="middle" class="<?=$class_val;?>"  width="5%"><input name="checkbox[]" value="<? echo $row['review_id']?>" type="checkbox"></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"  width="2%"><?php echo $srno++?></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><a href="home.php?request=site_reviews&fpurpose=edit&checkbox[0]=<?php echo $row['review_id']?>&srch_author=<?=$_REQUEST['srch_author']?>&srch_review_status=<?=$_REQUEST['srch_review_status']?>&srch_review_startdate=<?=$_REQUEST['srch_review_startdate']?>&srch_review_enddate=<?=$_REQUEST['srch_review_enddate']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>" title="<? echo $row['review_date']?>" class="edittextlink" onclick="show_processing()"><?=$display_review_date?></a></td>
		  
		  <td align="left" valign="middle" class="<?=$class_val;?>"><? echo $row['review_author']?>
		
		  </td>  <td align="left" valign="middle" class="<?=$class_val;?>"><? echo $row['review_status']?>
		
		  </td>
        
          <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo ($row['review_hide'] == 1)?'Yes':'No'; ?></td>
         
        </tr>
      <?
	  }
	  }
	  else
	  {
	  ?>
	  <tr>
				  <td align="center" valign="middle" class="norecordredtext" colspan="7" >
				  	No Site Reviews exists.				  </td>
		  </tr>
		<?
		}
		?>	
		<tr>
      <td class="listeditd" colspan="3">
	  <?php 
		if ($db->num_rows($ret_qry))
		{
	?>
	  		<a href="#" class="editlist" onclick="go_edit('<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>')">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>','<?php echo $_REQUEST['srch_review_status']?>','<?php echo $_REQUEST['srch_author']?>','<?php echo $_REQUEST['srch_review_startdate']?>','<?php echo $_REQUEST['srch_review_enddate']?>')" class="deletelist">Delete</a>
	 <?php
	 	}
	 ?>	  </td>
      <td colspan="4" align="right" class="listeditd">
	 </td>
    </tr>
      </table>
	  </div></td>
    </tr>
	<tr>
     
      <td colspan="2" align="right" class="listing_bottom_paging">
	  <?php 
		if ($db->num_rows($ret_qry))
		{
			paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan);
		}
		?></td>
    </tr>
  </table>
</form>