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
$table_name='customer_productquotes_details';
$page_type='Product Quotes';
$help_msg = get_help_messages('LIST_QUOTE_MESS1');
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistQuote,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistQuote,\'checkbox[]\')"/>','Slno.','Date Added','Name','Email Address','Phone','Status','View');
$header_positions=array('left','left','left','left','left','left','left','left','left','left');
$colspan = count($table_headers);

//#Search terms
$search_fields = array('org_name');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'date_added':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'DESC':$_REQUEST['sort_order'];
$sort_options = array('org_name' => 'Name','contact_email' => 'Email','status' => 'Status','date_added' => 'Date Added' );
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
if($_REQUEST['search_name']) {
	$where_conditions .= "AND ( org_name LIKE '%".add_slash($_REQUEST['search_name'])."%')";
}
if($_REQUEST['search_email']) {
	$where_conditions .= "AND ( contact_email LIKE '%".add_slash($_REQUEST['search_email'])."%')";
}
if($_REQUEST['search_status']=='')
{
 $_REQUEST['search_status'] ='All';
}
if($_REQUEST['search_status'] && $_REQUEST['search_status']!='All') {
	$where_conditions .= "AND ( status LIKE '%".add_slash($_REQUEST['search_status'])."%')";
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
		$where_conditions .= " AND (date_added BETWEEN '".$mysql_fromdate."' AND '".$mysql_todate."') ";
	}
	elseif($valid_fromdate and !$valid_todate) // only from date is valid
	{
		$where_conditions .= " AND date_added >= '".$mysql_fromdate."' ";
	}
	elseif(!$valid_fromdate and $valid_todate) // only to date is valid
	{
		$where_conditions .= " AND date_added <= '".$mysql_todate."' ";
	}
}
/*if($_REQUEST['srch_review_startdate'] && $_REQUEST['srch_review_enddate'] ) {
	$fromdate_arr = explode("-",add_slash($_REQUEST['srch_review_startdate']));
	$fromdate = $fromdate_arr[2]."-".$fromdate_arr[1]."-".$fromdate_arr[0];
	$todate_arr = explode("-",add_slash($_REQUEST['srch_review_enddate']));
	$todate =$todate_arr[2]."-".$todate_arr[1]."-".$todate_arr[0];
	$where_conditions .= "AND (enquiry_date >= '".$fromdate."' AND enquiry_date <= '".$todate."' )";
}
if($_REQUEST['srch_review_startdate'] && $_REQUEST['srch_review_enddate']=='' ) {
	$fromdate_arr = explode("-",add_slash($_REQUEST['srch_review_startdate']));
	$fromdate =$fromdate_arr[2]."-".$fromdate_arr[1]."-".$fromdate_arr[0];
	$where_conditions .= "AND (enquiry_date >= '".$fromdate."')";
}
if($_REQUEST['srch_review_startdate']=='' && $_REQUEST['srch_review_enddate'] ) {
	$todate_arr = explode("-",add_slash($_REQUEST['srch_review_enddate']));
	$todate =$todate_arr[2]."-".$todate_arr[1]."-".$todate_arr[0];
	$where_conditions .= "AND (enquiry_date <= '".$todate."' )";
}*/
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
 $query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=listquotes&records_per_page=$records_per_page&search_name=".$_REQUEST['search_name']."&search_email=".$_REQUEST['search_email']."&search_status=".$_REQUEST['search_status']."&start=$start&srch_review_startdate=".$_REQUEST['srch_review_startdate']."&srch_review_enddate=".$_REQUEST['srch_review_enddate']."";

?>

<script language="javascript">
function call_ajax_changestatus(search_name,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var Eid				= 0;
	var E_ids 			= '';
	var E_orders			= '';
	var ch_status			= document.frmlistQuote.cbo_changestatus.value;
	var startdate   = '<?=$_REQUEST['srch_review_startdate']?>';
	var enddate   	= '<?=$_REQUEST['srch_review_enddate']?>';
	var search_email		= '<?php echo $_REQUEST['search_email']?>';
	var search_status		= '<?php echo $_REQUEST['search_status']?>';
	var qrystr				= 'search_status='+search_status+'&search_name='+search_name+'&search_email='+search_email+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&ch_status='+ch_status+'&start='+start+'&pg='+pg+'&srch_review_startdate='+startdate+'&srch_review_enddate='+enddate;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlistQuote.elements.length;i++)
	{
		if (document.frmlistQuote.elements[i].type =='checkbox' && document.frmlistQuote.elements[i].name=='checkbox[]')
		{

			if (document.frmlistQuote.elements[i].checked==true)
			{
				atleastone = 1;
				if (E_ids !='')
					E_ids  += '~';
				 E_ids  += document.frmlistQuote.elements[i].value;
			}	
		}
	}
	if (atleastone==0) 
	{
		alert('Please select the enquiries to change the status');
	}
	else
	{
		if(confirm('Change Status of Seleted Quote(s)?'))
		{
				show_processing();
				Handlewith_Ajax('services/listof_quotes.php','fpurpose=change_status&'+qrystr+'&enquire_ids='+E_ids);
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
	var search_email= '<?=$_REQUEST['search_email']?>';
	var search_status		= '<?php echo $_REQUEST['search_status']?>';
	var startdate   = '<?=$_REQUEST['srch_review_startdate']?>';
	var enddate   	= '<?=$_REQUEST['srch_review_enddate']?>';

	var qrystr				= 'search_status='+search_status+'&search_email='+search_email+'&search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&srch_review_startdate='+startdate+'&srch_review_enddate='+enddate;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlistQuote.elements.length;i++)
	{
		if (document.frmlistQuote.elements[i].type =='checkbox' && document.frmlistQuote.elements[i].name=='checkbox[]')
		{

			if (document.frmlistQuote.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmlistQuote.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select Quote to delete');
	}
	else
	{
		if(confirm('Are you sure you want to delete selected Quote?'))
		{
			show_processing();
			Handlewith_Ajax('services/listof_quotes.php','fpurpose=delete&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
function handle_expansionall(id)
{
	imgobj = document.getElementById('imgexp_'+id);

	if(document.getElementById(id).style.display=='none')
	{
		imgobj.src = 'images/minus.gif';
	 document.getElementById(id).style.display='';
	}
	else
	{
	imgobj.src = 'images/plus.gif';	
	document.getElementById(id).style.display='none';
	}
	

}
</script>
<form name="frmlistQuote" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="request" value="listquotes" />
<input type="hidden" name="start" value="<?=$start?>" />
<input type="hidden" name="pg" value="<?=$pg?>" />
  <table width="973" border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td colspan="5" align="left" valign="middle" class="treemenutd">List Quotes</td>
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
    <tr>
      <td height="48"  colspan="10" class="listeditd" >
      	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="listingtable" >
        <tr>
          <td align="left" valign="middle" colspan="6" >Organisation Name </td>
          <td  align="left" valign="middle" colspan="6" ><input name="search_name" type="text" class="textfeild" id="search_name" value="<?=$_REQUEST['search_name']?>"  /> 
          </td><td colspan="6" align="left" valign="middle" >Email Id </td>
		  <td colspan="6" align="left" valign="middle" >
            <input name="search_email" type="text" class="textfeild" id="search_email" value="<?=$_REQUEST['search_email']?>"></td>
          <td colspan="6" align="left" valign="middle">Status:</td> 
          <td width="17%" colspan="6" align="left" valign="middle" > 
		    <select name="search_status" class="dropdown" id="search_status">
			  <option value="All">Show All Status</option>
            <option value="OPEN" <?=($_REQUEST['search_status']=='OPEN')?'selected':'';?> >OPEN</option>
			<option value="CLOSED" <?=($_REQUEST['search_status']=='CLOSED')?'selected':''; ?>>CLOSED</option>
			 </select>&nbsp;
         </td>
		  
		   <td colspan="6" align="left" valign="bottom" >Date From <input class="textfeild" type="text" name="srch_review_startdate" size="6" value="<?=$_REQUEST['srch_review_startdate']?>"  />&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:show_calendar('frmlistQuote.srch_review_startdate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" align="absmiddle" /></a></td> <td colspan="6" align="left" valign="bottom" >Date To  
                <input class="textfeild" type="text" name="srch_review_enddate" size="6" value="<?=$_REQUEST['srch_review_enddate']?>"  />&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:show_calendar('frmlistQuote.srch_review_enddate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0"  align="absmiddle"/></a></td>
		  </tr>
      </table>
	  </td>
	  </tr>
	  <tr>
	  <td colspan="10" class="listeditd">
      <table width="100%" border="0" cellpadding="0" cellspacing="0"  class="listingtable">
        <tr>
          <td  align="left" colspan="3">Show</td>
          <td  align="left" ><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/>
            <?=$page_type?> Per Page</td>
          <td align="left" nowrap="nowrap" ><?=$sort_option_txt?> in <?=$sort_by_txt?>  
          &nbsp;&nbsp;&nbsp;&nbsp;<input name="button5" type="submit" class="red" id="button5" value="Go" /> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_ENQUIRE_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.gif" width="17" height="13" border="0" /></a></td>
          <td align="left">&nbsp;</td>
		  <td align="left">&nbsp;</td>        
	     </tr>
      </table>
	  </td>
    </tr>
    <tr>
      <td  class="listeditd" width="35%"> 
	  <?
	  if($numcount)
	  {
	  ?>
	   <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
	  <?
	  }
	  ?>	  </td>
	   <td class="listeditd" align="center" width="30%">
	  <?
	  if($numcount)
	  {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?></td>
	   <td  align="right" class="listeditd" colspan="3" width="35%">
	  <?
	  if($numcount)
	  {
	  ?>
        
		Change Status
          <select name="cbo_changestatus" class="dropdown" id="cbo_changestatus">
            <option value="OPEN">OPEN</option>
			<option value="CLOSED">CLOSED</option>
		</select>&nbsp;<input name="change_status" type="button" class="red" id="change_status" value="Change" onclick="call_ajax_changestatus('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_QUOTE_CHSTATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.gif" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
		<?
		}
		?>
      </td>
    </tr>
     
    <tr>
      <td colspan="5" class="listingarea">
      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable">
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
	   $sql_user = " SELECT organisation_id,org_name,contact_full_name,contact_position,contact_tel,contact_mobile,contact_fax,contact_email,contact_refno,org_otherdetails,date_format(date_added,'%d-%b-%Y') as added_date
,status,date_format(status_changed_date,'%d-%b-%Y') as status_date,status_changed_by FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page  ";
	   $res = $db->query($sql_user);
	    $srno = getStartOfPageno($records_per_page,$pg); 
	   while($row = $db->fetch_array($res))
	   {
			$count_no++;
			$array_values = array();
			if($count_no %2 == 0)
				$class_val="listingtablestyleB";
			else
				$class_val="listingtablestyleB";		    
		
	   ?>
        <tr >
          <td align="left" valign="middle" class="<?=$class_val;?>"  width="6%"><input name="checkbox[]" value="<? echo $row['organisation_id']?>" type="checkbox"></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"  width="2%"><?php echo $srno++?></td>
		   <td align="left" valign="middle" class="<?=$class_val;?>"><? echo $row['added_date']?></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><? echo $row['contact_full_name']?></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo $row['contact_email']; ?></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo $row['contact_tel']; ?></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo $row['status']; ?> </td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"><img id="imgexp_<?php echo $row['organisation_id']?>" src="images/plus.gif" onclick="handle_expansionall('<? echo $row['organisation_id']?>')" title="Click" border="0"></td>
		   </tr>
		   <tr id="<?php echo $row['organisation_id']?>" style="display:none">
		   <td colspan="8" class="listingtablestyleA">
		   <table cellspacing="0" cellpadding="2" border="0" width="70%">
		   <tr >
		   <td class="listingtablestyleA" valign="top" ><strong>Contact Position:</strong></td><td valign="top" class="listingtablestyleA"><?php echo $row['contact_position']?></td>
		   <td class="listingtablestyleA" valign="top"><strong>Telephone:</strong></td><td valign="top" class="listingtablestyleA"><?php echo $row['contact_tel']?></td>
		   </tr>
		    <tr>
		    <td class="listingtablestyleA" valign="top"><strong>Mobile:</strong></td><td valign="top" class="listingtablestyleA"><?php echo $row['contact_mobile']?></td>
		    <td class="listingtablestyleA" valign="top"><strong>Fax:</strong></td><td valign="top" class="listingtablestyleA"><?php echo $row['contact_fax']?></td>
		    </tr>
		    <tr>
		    <td class="listingtablestyleA" valign="top"><strong>Reference No:</strong></td><td valign="top" class="listingtablestyleA"><?php echo $row['contact_refno']?></td>
		    <td class="listingtablestyleA" valign="top"><strong>Quote Added On:</strong></td><td valign="top" class="listingtablestyleA"><?php echo $row['added_date']?></td>
		    		   </tr>
				<?php
				if($row['status_changed_by'])
				{
				?>
				<tr>
				<td class="listingtablestyleA" valign="top"><strong>Status Changed By:</strong></td><td valign="top" class="listingtablestyleA"><?php echo getConsoleUserName($row['status_changed_by'])?></td>
				<td class="listingtablestyleA" valign="top"><strong>Status Changed Date:</strong></td><td valign="top" class="listingtablestyleA"><?php echo $row['status_date']?></td>
				</tr>
				<?php
				}
				?>
		    <tr>
		   	<td class="listingtablestyleA" valign="top" ><strong>Other Details:</strong></td><td valign="top" colspan="3"  class="listingtablestyleA"><?php echo nl2br($row['org_otherdetails'])?></td> 
			</tr>
		   </tr>
		   </table>
		   </td>
		   </tr>
      <?
	  }
	  }
	  else
	  {
	  ?>
	  <tr>
				  <td align="center" valign="middle" class="norecordredtext" colspan="9" >No Qoutes exists.</td>
		  </tr>
		<?
		}
		?>	
      </table></td>
    </tr>
	
  <tr><?
	  if($numcount)
	  {
	  ?>
      <td class="listeditd" width="35%"> 
	  
	   <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
	 
	  </td> <?
	  }
	  ?> <?
	  if($numcount)
	  { ?>
	   <td class="listeditd"   align="center" width="30%">
	  <?
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	   ?>
	  </td> <td class="listeditd" align="right" width="35%" colspan="3">&nbsp;
	 
   	   </td>
	  <?
	  }?>
	  
    </tr>
  </table>
</form>
