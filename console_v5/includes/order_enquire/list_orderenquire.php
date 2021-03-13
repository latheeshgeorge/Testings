
<?php
	/*#################################################################
	# Script Name 	: list_orderenquire.php
	# Description 	: Page for listing Customer
	# Coded by 		: Latheesh
	# Created on	: 21-Apr-2008
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
//Define constants for this page
$table_name='order_queries';
$page_type='Order Qieries';
$help_msg = get_help_messages('LIST_ORDER_QUERY_MESS1');
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistOrderquery,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistOrderquery,\'checkbox[]\')"/>','Slno.','Date Added','Order Id','User','Subject','Status','New Posts');
$header_positions=array('left','left','left','left','left','left','left');
$colspan = count($table_headers);
$cur_user = $_SESSION['console_id'];
//#Search terms
$search_fields = array('order_id');
foreach($search_fields as $v) {
	$query_string .= "$v=${$v}&";#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'query_date':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'DESC':$_REQUEST['sort_order'];
$sort_options = array('orders_order_id' => 'Order Id','query_date' => 'Date');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
if($_REQUEST['order_id']) {
	$where_conditions .= " AND ( orders_order_id ='".$_REQUEST['order_id']."')";
}
if($_REQUEST['search_status']=='')
{
 $_REQUEST['search_status'] ="N";
}
if($_REQUEST['search_status'] && $_REQUEST['search_status']!='NPOST' && $_REQUEST['search_status']!='All') {
	$where_conditions .= " AND ( query_status LIKE '%".add_slash($_REQUEST['search_status'])."%')";
}

if($_REQUEST['search_status']=='NPOST') {
$sel_post = "SELECT DISTINCT order_queries_query_id FROM order_queries_posts oqp,order_queries oq WHERE oqp.post_status='N' AND oqp.order_queries_query_id=oq.query_id AND oq.sites_site_id=$ecom_siteid";
$ret_post =$db->query($sel_post);
if($db->num_rows($ret_post)){
while($row_post = $db->fetch_array($ret_post))
{
 $arr_post_id[]=$row_post['order_queries_query_id'];
}
if(is_array($arr_post_id)){
$arr_post_str = implode(',',$arr_post_id);
}
$arr_post_str = '('.$arr_post_str.')';
	$where_conditions .= "AND ( query_id IN ".$arr_post_str.")";
}
}
/*if($_REQUEST['search_email']) {
	$where_conditions .= "AND ( enquiry_email LIKE '%".add_slash($_REQUEST['search_email'])."%')";
}
*/

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
		$where_conditions .= " AND (query_date BETWEEN '".$mysql_fromdate."' AND '".$mysql_todate."') ";
	}
	elseif($valid_fromdate and !$valid_todate) // only from date is valid
	{
		$where_conditions .= " AND query_date >= '".$mysql_fromdate."' ";
	}
	elseif(!$valid_fromdate and $valid_todate) // only to date is valid
	{
		$where_conditions .= " AND query_date <= '".$mysql_todate."' ";
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
 $query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=order_enquiries&records_per_page=$records_per_page&order_id=".$_REQUEST['order_id']."&search_status=".$_REQUEST['search_status']."&start=$start&srch_review_startdate=".$_REQUEST['srch_review_startdate']."&srch_review_enddate=".$_REQUEST['srch_review_enddate']."&cur_userid=".$cur_user;

?>
<script language="javascript" type="text/javascript">
function call_ajax_changestatus(search_id,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var Eid				= 0;
	var E_ids 			= '';
	var E_orders			= '';
	var ch_status			= document.frmlistOrderquery.cbo_changestatus.value;
	var search_status		= '<?php echo $_REQUEST['search_status']?>';
	var qrystr				= 'order_id='+search_id+'&search_status='+search_status+'&sortby='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlistOrderquery.elements.length;i++)
	{
		if (document.frmlistOrderquery.elements[i].type =='checkbox' && document.frmlistOrderquery.elements[i].name=='checkbox[]')
		{

			if (document.frmlistOrderquery.elements[i].checked==true)
			{
				atleastone = 1;
				if (E_ids !='')
					E_ids  += '~';
				 E_ids  += document.frmlistOrderquery.elements[i].value;
			}	
		}
	}
	if (atleastone==0) 
	{
		alert('Please select the Queries to change the status');
	}
	else
	{
		if(confirm('Change Status of Seleted Query(s)?'))
		{
				show_processing();
				Handlewith_Ajax('services/order_enquire.php','fpurpose=change_status&'+qrystr+'&query_ids='+E_ids+'&ch_status='+ch_status);
		}	
	}	
}

function call_ajax_delete(search_id,sortby,sortorder,recs,start,pg)
{
	var atleastone 	= 0;
	var del_ids 	= '';
	var search_status		= '<?php echo $_REQUEST['search_status']?>';
	var qrystr				= 'order_id'+search_id+'&search_status='+search_status+'&sortby='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlistOrderquery.elements.length;i++)
	{
		if (document.frmlistOrderquery.elements[i].type =='checkbox' && document.frmlistOrderquery.elements[i].name=='checkbox[]')
		{

			if (document.frmlistOrderquery.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmlistOrderquery.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select Order Query to delete');
	}
	else
	{
		if(confirm('Are you sure you want to delete selected Query?'))
		{
			show_processing();
			Handlewith_Ajax('services/order_enquire.php','fpurpose=delete&del_ids='+del_ids+'&'+qrystr);
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
</script>
<form name="frmlistOrderquery" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="request" value="order_enquiries" />
<input type="hidden" name="ch_status" value="" />
<input type="hidden" name="search_status" value="<?=$_REQUEST['search_status']?>" />
<input type="hidden" name="start" value="<?=$start?>" />
<input type="hidden" name="pg" value="<?=$pg?>" />
<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />

<input type="hidden" name="pg" value="<?=$pg?>" />
  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td colspan="5" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span> List Order Queries</span></div></td>
    </tr>
	<tr>
	  <td align="left" valign="middle" class="helpmsgtd_main" colspan="5">
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
	  if($numcount)
	  {
	    
		 ?> 
    <tr>
		<td colspan="5" align="right" valign="middle" class="sorttd"><?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?></td>
	</tr>
	<?php
	  }
	?>
	<tr>
		<td colspan="5" align="left" valign="top">
		<div class="sorttd_div">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
      <td height="48"  colspan="10" class="listeditd" >
      	<table width="100%" border="0" cellpadding="1" cellspacing="0" class="listingtable" >
        <tr>
          <td align="left" valign="middle" colspan="6" > </td>
          <td  align="left" valign="middle" colspan="6" >          </td><td colspan="6" align="left" valign="middle" >Order Id </td>
		  <td colspan="6" align="left" valign="middle" >
            <input name="order_id" type="text" class="textfeild" id="order_id" value="<?=$_REQUEST['order_id']?>"></td>
          <td align="left" valign="middle">Status:</td> 
          <td align="left" valign="middle" > 
		    <select name="search_status" class="dropdown" id="search_status">
			  <option value="All">Show All Status</option>
            <option value="N" <?=($_REQUEST['search_status']=='N')?'selected':'';?> >NEW</option>
			<option value="R" <?=($_REQUEST['search_status']=='R')?'selected':''; ?>>READ</option>
			<option value="C" <?= ($_REQUEST['search_status']=='C')?'selected':''; ?>>CLOSED</option>
			<option value="NPOST" <?= ($_REQUEST['search_status']=='NPOST')?'selected':'';?>>NEW POST FOUND</option>
          </select>&nbsp;         </td>
		   <td colspan="3" align="left" valign="bottom" >Date From <input class="textfeild" type="text" name="srch_review_startdate" size="6" value="<?=$_REQUEST['srch_review_startdate']?>"  />&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:show_calendar('frmlistOrderquery.srch_review_startdate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" align="absmiddle" /></a></td> <td width="22%" colspan="3" align="left" valign="bottom" >Date To  
                <input class="textfeild" type="text" name="srch_review_enddate" size="6" value="<?=$_REQUEST['srch_review_enddate']?>"  />&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:show_calendar('frmlistOrderquery.srch_review_enddate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0"  align="absmiddle"/></a></td>
		  </tr>
        <tr>
          <td align="left" valign="middle" colspan="6" ></td>
          <td  align="left" valign="middle" colspan="6" ></td>
          <td colspan="6" align="left" valign="middle" >Show</td>
          <td colspan="6" align="left" valign="middle" ><input name="records_per_page2" type="text" class="textfeild" id="records_per_page2" size="3" maxlength="3"  value="<?=$records_per_page?>"/>
            <?=$page_type?>
Per Page</td>
          <td align="left" valign="middle">Sort by </td>
          <td colspan="3" align="left" valign="middle"><?=$sort_option_txt?>
in
  <?=$sort_by_txt?>
&nbsp;&nbsp;&nbsp;&nbsp;</td>
          <td colspan="4" align="right" valign="middle"><input name="button5" type="submit" class="red" id="button5" value="Go" />
            <a href="#" onmouseover ="ddrivetip('Use \'Go\' button to search .')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          </tr>
      </table>
	  </td>
    </tr>
	</table>
	</div>
	</td>
	</tr>
    
     
    <tr>
      <td colspan="5" class="listingarea">
	  <div class="listingarea_div">
      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable">
	  <tr>
      <td  class="listeditd" align="left" valign="middle" colspan="5"> 
	  <?
	  if($numcount)
	  {
	  ?>
	   <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
	  <?
	  }
	  ?>	  </td>
	   <td  align="right" class="listeditd" colspan="3" valign="middle">
	  <?
	  if($numcount)
	  {
	  ?>
        
		Change Status
          <select name="cbo_changestatus" class="dropdown" id="cbo_changestatus">
            <option value="N">NEW</option>
			<option value="R">READ</option>
			<option value="C">CLOSED</option>
          </select>&nbsp;<input name="change_status" type="button" class="red" id="change_status" value="Change" onclick="call_ajax_changestatus('<?php echo $_REQUEST['order_id']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_ORDER_QUERY_CHSTATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
		<?
		}
		?>
      </td>
    </tr>
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
	   $sql_user = "SELECT query_id,orders_order_id,query_source,user_id,query_subject,query_status,date_format(query_date,'%d-%b-%Y') as added_date FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
	   
	   $res = $db->query($sql_user);
	   $srno = 1; 
	   while($row = $db->fetch_array($res))
	   {
			$count_no++;
			$array_values = array();
			if($count_no %2 == 0)
				$class_val="listingtablestyleA";
			else
				$class_val="listingtablestyleB";	
	        
		  if($row['query_source']=='A')
		  {
		    $sel_cons_cust = "SELECT user_fname,user_lname,sites_site_id FROM sites_users_7584 WHERE user_id=".$row['user_id']." LIMIT 1";
		   $ret_cons_cust =$db->query($sel_cons_cust);
			if($db->num_rows($ret_cons_cust))
			{
			  $row_cons_cust = $db->fetch_array($ret_cons_cust);
			}
			if($row_cons_cust['sites_site_id']==0)
			{
			$name = "(Super Admin)";
			}
			else
			{
			$name = $row_cons_cust['user_fname']."&nbsp;".$row_cons_cust['user_lname']."(Admin)";
			}
		  }
		  else
		  {
		    $sel_cust ="SELECT customer_fname,customer_mname,customer_surname FROM customers WHERE customer_id=".$row['user_id']." AND sites_site_id=$ecom_siteid LIMIT 1";
			$ret_cust =$db->query($sel_cust);
			if($db->num_rows($ret_cust))
			{
			  $row_cust = $db->fetch_array($ret_cust);
			}
			$name = $row_cust['customer_fname']."&nbsp;".$row_cust['customer_mname']."&nbsp;".$row_cust['customer_surname']."(Customer)";
		  }
		  $sql_sel_posts = "SELECT post_id FROM order_queries_posts WHERE order_queries_query_id=".$row['query_id']." AND post_status='N' AND post_details!=''";
		  $ret_sel_posts = $db->query($sql_sel_posts);
		  $num_posts= $db->num_rows($ret_sel_posts); 
	   ?>
        <tr >
          <td align="left" valign="middle" class="<?=$class_val;?>"  width="6%"><input name="checkbox[]" value="<? echo $row['query_id']?>" type="checkbox"></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"  width="2%"><?php echo $srno++?></td>
		  		   <td align="left" valign="middle" class="<?=$class_val;?>"><a href="home.php?request=order_enquiries&fpurpose=edit&checkbox[0]=<?php echo $row['query_id']?>&order_id=<?php echo $_REQUEST['order_id']?>&pass_start=<?php echo $start?>&pass_pg=<?php echo $pg?>&pass_records_per_page=<?php echo $records_per_page?>&pass_sort_by=<?=$_REQUEST['sort_by']?>&pass_sort_order=<?=$_REQUEST['sort_order']?>&pass_srch_review_startdate=<?=$_REQUEST['srch_review_startdate']?>&pass_srch_review_enddate=<?=$_REQUEST['srch_review_enddate']?>&search_status=<?=$_REQUEST['search_status']?>&cur_userid=<?=$cur_user?>" title="<? echo $row['added_date']?>" class="edittextlink" onclick="show_processing()"><? echo $row['added_date']?></a></td>

		   <td align="left" valign="middle" class="<?=$class_val;?>"><? echo $row['orders_order_id']?></td>
		   <td align="left" valign="middle" class="<?=$class_val;?>"><? echo $name ?></td>

		  <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo $row['query_subject']; ?></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"><?php if($row['query_status']=='N')echo "NEW";elseif($row['query_status']=='R') echo "READ";else echo "CLOSED";?></td>
		   <td align="center" valign="middle" class="<?=$class_val;?>"><?php echo "(". $num_posts. ")"; ?></td>
        </tr>
      <?
	  }
	  }
	  else
	  {
	  ?>
	  <tr>
				  <td align="center" valign="middle" class="norecordredtext" colspan="8" >No Query Exists.</td>
		  </tr>
		<?
		}
		?>	
		<tr><?
	  if($numcount)
	  {
	  ?>
      <td class="listeditd" align="left" valign="middle" colspan="5"> 	  
	   <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['order_id']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
	 
	  </td> <?
	  }
	  ?> <?
	  if($numcount)
	  { ?>
	   <td class="listeditd" align="right" colspan="3" valign="middle">
	 
	  </td>
	  <?
	  }?>
	  
    </tr>
      </table>
	  </div></td>
    </tr>
	<tr><?
	  if($numcount)
	  { ?>
	   <td class="listing_bottom_paging" align="right" colspan="2" valign="middle">
	  <?
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	   ?>
	  </td>
	  <?
	  }?>
	  
    </tr>
  
  </table>
</form>