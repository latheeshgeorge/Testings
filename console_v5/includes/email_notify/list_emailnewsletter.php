<?php
	/*#################################################################
	# Script Name 	: list_emailnewsletter.php
	# Description 	: Page for listing Newsletters
	# Coded by 		: ANU
	# Created on	: 29-Aug-2007
	# Modified by	: ANU
	# Modified On	: 29-Aug-2007
	#################################################################*/
//Define constants for this page
$table_name='customer_email_notification';
$page_type='Customer Email Notification ';
$help_msg = get_help_messages('LIST_NOTIFICATION_MESS1');
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistNotification,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistNotification,\'checkbox[]\')"/>','Slno.','Notification','Date Last Updated','Action');
$header_positions=array('left','left','left','left','left');
$colspan = count($table_headers);

//#Search terms
$search_fields = array('newsletter_title');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_options = array('newsletter_updatedate' => 'Last Updated Date');
$sort_by = (!array_key_exists($_REQUEST['sort_by'],$sort_options))?'newsletter_updatedate':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
if($_REQUEST['search_name']) {
	$where_conditions .= " AND (( newsletter_title LIKE '%".add_slash($_REQUEST['search_name'])."%') OR (preview_title LIKE '%".add_slash($_REQUEST['search_name'])."%'))";
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
$query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=email_notify&records_per_page=$records_per_page&search_name=".$_REQUEST['search_name']."&start=$start";
?>
<script language="javascript">

function checkSelected()
{
	len=document.frmlistNotification.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistNotification.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
				user_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one advert ');
		return false;
	}
	show_processing();
	return true;
}
function edit_selected()
{
	
	len=document.frmlistNotification.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistNotification.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
				user_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one News Letter');
	}
	else if(cnt>1 ){
		alert('Please select only one Notification to edit');
	}
	else
	{
		show_processing();
		document.frmlistNotification.fpurpose.value='edit';
		document.frmlistNotification.submit();
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
	for(i=0;i<document.frmlistNotification.elements.length;i++)
	{
		if (document.frmlistNotification.elements[i].type =='checkbox' && document.frmlistNotification.elements[i].name=='checkbox[]')
		{

			if (document.frmlistNotification.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmlistNotification.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select Notification to delete');
	}
	else
	{
		if(confirm('Are you sure you want to delete selected  Notification?'))
		{
			show_processing();
			Handlewith_Ajax('services/email_notify.php','fpurpose=delete&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
</script>
<form name="frmlistNotification" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="request" value="email_notify" />
<input type="hidden" name="start" value="<?=$start?>" />
<input type="hidden" name="pg" value="<?=$pg?>" />
<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
<input  type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>"  />
  <table border="0" cellpadding="0" cellspacing="0" class="maintable" width="100%">
    <tr>
      <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span> List Email Notifications</span></div></td>
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
		 ?> 
	<?php
	  if($numcount)
	  {
	?>
    <tr><td colspan="3" align="right" valign="middle" class="sorttd"><?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?></td></tr>
	<?php
	  }
	?>
	<tr>
      <td height="48" class="sorttd" colspan="3" >
	  <div class="sorttd_div">
      	<table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="11%" align="left" valign="middle">Notification title </td>
          <td colspan="3" align="left" valign="middle"><input name="search_name" type="text" class="textfeild" id="search_name" value="<?=$_REQUEST['search_name']?>"  /></td>
          <td width="4%" align="left">Show</td>
          <td width="30%" align="left"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/>
            <?=$page_type?> Per Page</td>
          <td width="5%" align="left">Sort By</td>
          <td width="17%" align="left" nowrap="nowrap"><?=$sort_option_txt?> in <?=$sort_by_txt?>  </td>
          <td width="6%" align="right">&nbsp;&nbsp;&nbsp;&nbsp;
            <input name="button5" type="submit" class="red" id="button5" value="Go" /> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_NOTIFICATION_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
      </table>
	  </div>
	  </td>
    </tr>
    
    <tr>
      <td colspan="3" class="listingarea">
	  <div class="listingarea_div">
      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable" width="100%">
	  <tr>
      <td colspan="5" align="left" valign="middle" class="listeditd"><a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=email_notify&fpurpose=add&records_per_page=<?=$records_per_page?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$start?>&pg=<?=$pg?>" class="addlist" onclick="show_processing()">Add</a> 
	  <?
	  if($numcount)
	  {
	  ?>
	  <a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
	  <?
	  }
	  ?>	  
	  </td>
    </tr>
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
	    $sql_group = "SELECT news_id,newsletter_title,preview_title, newsletter_updatedate 
	   						FROM $table_name 
								 $where_conditions 
									ORDER BY $sort_by $sort_order 
										LIMIT $start,$records_per_page ";
	   $res = $db->query($sql_group);
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
          <td align="left" valign="middle" class="<?=$class_val;?>" width="7%"><input name="checkbox[]" value="<? echo $row['news_id']?>" type="checkbox"></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>" width="2%"><?php echo $srno++?></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><a href="home.php?request=email_notify&fpurpose=edit&fmode=edit&newsletter_id=<?php echo $row['news_id']?>&search_name=<?php echo $_REQUEST['search_name']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>" title="<? echo $row['newsletter_title']?>" class="edittextlink" onclick="show_processing()"><? if(trim($row['newsletter_title'])) echo $row['newsletter_title']; else echo $row['preview_title']; ?></a></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><?=dateFormat($row['newsletter_updatedate'],'datetime');?></td>
          <td align="left" valign="middle" class="<?=$class_val;?>">
<? /* ?>   <a href="home.php?request=newsletter&fpurpose=listcustomers&checkbox[0]=<?php echo $row['newsletter_id']?>&search_name=<?php echo $_REQUEST['search_name']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>" title="Select customers to send this newsletter" class="edittextlink" onclick="show_processing()">Send mail</a> <? */ ?>
		  <a href="home.php?request=email_notify&fpurpose=preview_email&newspreview=preview&fmode=edit&newsletter_id=<?php echo $row['news_id']?>&search_name=<?php echo $_REQUEST['search_name']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>"  title="Click here To see Preview " class="edittextlink">Preview</a> 
		  </td>
        </tr>
      <?
	  }
	  }
	  else
	  {
	  ?>
	  <tr>
				  <td align="center" valign="middle" class="norecordredtext" colspan="5" >
				  	No Email Notifications  exists.				  </td>
			</tr>
		<?
		}
		?>	
	  <tr>
		  <td class="listeditd" colspan="5" align="right" valign="middle"><a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=email_notify&fpurpose=add&records_per_page=<?=$records_per_page?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$start?>&pg=<?=$pg?>" class="addlist" onclick="show_processing()">Add</a> 
		  <?
		  if($numcount)
		  {
		  ?>
		  <a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
		  <?
		  }
		  ?>	</td>
		</tr>
      </table>
	  </div>
	  </td>
    </tr>
  	
    </table>
	<input  type="hidden" name="newsletter_id" id="newsletter_id" value="<?=$row['news_id']?>"  />

</form>
