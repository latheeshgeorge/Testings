<?php
	/*#################################################################
	# Script Name 	: list_newsletter_customers.php
	# Description 	: Page for listing news letter customers.
	# Coded by 		: ANU
	# Created on	: 21-Apr-2008
	# Modified by	: ANU
	# Modified On	: 21-Apr-2008
	#################################################################*/
//Define constants for this page

$table_name='newsletter_customers';
$page_type='Search Customers';
$help_msg = get_help_messages('LIST_NEWSLETTERCUSTOMERS_MESS1');
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistNewsletterCustomers,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistNewsletterCustomers,\'checkbox[]\')"/>','Slno','Customer Name','Customer Email','Registered','Join Date','Hide');
$header_positions=array('left','left','left','left','left','center','left','left','left','center','left');
$colspan = count($table_headers);

//#Search terms
$search_fields = array('search_name','search_email','sort_by','sort_order');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'news_custname':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('news_custname' => 'Customer Name','news_custemail' => 'Customer Email','news_join_date'=>'Join Date');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
if($_REQUEST['search_name']) {
	$where_conditions .= " AND news_custname LIKE '%".add_slash($_REQUEST['search_name'])."%'
	";
}
if($_REQUEST['search_email']) {
	$where_conditions .= " AND ( news_custemail LIKE '%".add_slash($_REQUEST['search_email'])."%'
	) ";
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
$query_string .= "request=newsletter_customers&sort_by=$sort_by&sort_order=$sort_order&records_per_page=$records_per_page&start=$start";
?>
<script language="javascript">
function call_ajax_changestatus(search_name,search_email,sortby,sortorder,recs,start,pg)
{

	var atleastone 			= 0;
	var curid				= 0;
	var news_customer_ids 	= '';
	var cat_orders			= '';
	var ch_status			= document.frmlistNewsletterCustomers.cbo_changehide.value;
	var qrystr				= 'search_name='+search_name+'&search_email='+search_email+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&ch_status='+ch_status+'&start='+start+'&pg='+pg;
	/* check whether any checkbox is ticked */
	
	
	for(i=0;i<document.frmlistNewsletterCustomers.elements.length;i++)
	{
		if (document.frmlistNewsletterCustomers.elements[i].type =='checkbox' && document.frmlistNewsletterCustomers.elements[i].name=='checkbox[]')
		{

			if (document.frmlistNewsletterCustomers.elements[i].checked==true)
			{
				atleastone = 1;
				if (news_customer_ids!='')
					news_customer_ids += '~';
				 news_customer_ids += document.frmlistNewsletterCustomers.elements[i].value;
				 
			}	
		}
	}
	if (atleastone==0) 
	{
		alert('Please select the Newsletter Customers to change the hide status');
	}
	else
	{
		if(confirm('Change Hide Status of Newsletter Seleted Customer(s)?'))
		{
				show_processing();
				Handlewith_Ajax('services/newsletter_customers.php','fpurpose=change_hide&'+qrystr+'&news_customer_ids='+news_customer_ids);
		}	
	}
	
	document.getElementById('retdiv_id').value 		= retdivid;	
}

function checkSelected()
{
	len=document.frmlistNewsletterCustomers.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistNewsletterCustomers.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
				user_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one Newsletter Customer ');
		return false;
	}
	show_processing();
	return true;
}
function edit_selected()
{
	
	len=document.frmlistNewsletterCustomers.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistNewsletterCustomers.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
				user_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one Newsletter Customer ');
	}
	else if(cnt>1 ){
		alert('Please select only one Newsletter Customer to edit');
	}
	else
	{
		show_processing();
		document.frmlistNewsletterCustomers.fpurpose.value='edit';
		document.frmlistNewsletterCustomers.submit();
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
function call_ajax_delete(search_name,search_email,sortby,sortorder,recs,start,pg)
{
	var atleastone 	= 0;
	var del_ids 	= '';
	var qrystr				= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&search_email='+search_email;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlistNewsletterCustomers.elements.length;i++)
	{
		if (document.frmlistNewsletterCustomers.elements[i].type =='checkbox' && document.frmlistNewsletterCustomers.elements[i].name=='checkbox[]')
		{

			if (document.frmlistNewsletterCustomers.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmlistNewsletterCustomers.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select customer to delete');
	}
	else
	{
		if(confirm('Are you sure you want to delete selected customer?'))
		{
			show_processing();
			Handlewith_Ajax('services/newsletter_customers.php','fpurpose=delete&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
</script>
<form name="frmlistNewsletterCustomers" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="request" value="newsletter_customers" />
<input type="hidden" name="start" value="<?=$start?>" />
<input type="hidden" name="pg" value="<?=$pg?>" />
<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
<input  type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>"  />
<input  type="hidden" name="search_email" id="search_email" value="<?=$_REQUEST['search_email']?>"  />
  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span> List Newsletter Customers</span></div></td>
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
		<td colspan="3" align="right" class="sorttd">
		  <?
			paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
		  
		  ?></td>
		</tr>
		<? }?>
    <tr>
      <td colspan="3" align="left" valign="middle" class="sorttd">
		<div class="editarea_div">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttabletop">
		<tr>
          <td width="9%" height="30" align="left" valign="middle">Customer Name</td>
		  <td width="13%" height="30" align="left" valign="middle"><input name="search_name" type="text" class="textfeild" id="search_name" value="<?php echo $_REQUEST['search_name']?>" /></td>
		  <td width="10%" height="30" align="center" valign="middle">Customer E-mail </td>
		  <td width="17%" height="30" align="left" valign="middle"><input name="search_email" type="text" class="textfeild" id="search_email" value="<?php echo $_REQUEST['search_email']?>" /></td>
		  <td width="11%" height="30" align="left" valign="middle">Records Per Page </td>
		  <td width="5%" height="30" align="left" valign="middle"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3" value="<?php echo $records_per_page?>" /></td>
		  <td width="4%" height="30" align="left" valign="middle">Sort By</td>
		  <td width="24%" height="30" align="left" valign="middle"><?php echo $sort_option_txt;?>&nbsp;&nbsp;&nbsp;in&nbsp;&nbsp;&nbsp;<?php echo $sort_by_txt?> </td>
		  <td width="7%" height="30" align="left" valign="middle"><input name="Search_go" type="submit" class="red" id="Search_go" value="Go" onclick="document.frmlistNewsletterCustomers.search_click.value=1" />
&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_NEWSLETTER_CUSTOMERS_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
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
			<td colspan="<?=round($colspan/2)?>"  align="left" valign="middle" class="listeditd" >
			<a href="home.php?request=newsletter_customers&fpurpose=add&search_name=<?php echo $_REQUEST['search_name']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&search_email=<?php echo $_REQUEST['search_email']?>" class="addlist" onclick="show_processing()">Add</a> 
			  <?
			  if($numcount)
			  {
			  ?>
			  <a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<? echo $_REQUEST['search_email']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
			  <?
			  }
			  ?>
			</td>
			<td colspan="<?=round($colspan/2)?>"  align="right" valign="middle" class="listeditd" >
			<?
			  if($numcount)
			  {
			  ?>				
				Change Status
				  <select name="cbo_changehide" class="dropdown" id="cbo_changehide">
					<option value="1">Yes</option>
					<option value="0">No</option>
				  </select>&nbsp;<input name="change_hide" type="button" class="red" id="change_hide" value="Change" onclick="call_ajax_changestatus('<?php echo $_REQUEST['search_name']?>','<?php echo $_REQUEST['search_email']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" />
				  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_NEWSLETTERCUSTOMERS_CHSTATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
				<?
				}
				?>
			</td>
		</tr>
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
	   $sql_group = "SELECT news_customer_id,news_title,news_custname,news_custemail,customer_id,news_custhide,news_join_date,DATE_FORMAT(news_join_date,'%d-%b-%Y') joindate  
					         FROM $table_name $where_conditions 
						        ORDER BY $sort_by $sort_order 
							       LIMIT $start,$records_per_page ";
	   $res = $db->query($sql_group);
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
			<td align="left" valign="middle" class="<?=$class_val;?>"  width="8%"><input name="checkbox[]" value="<? echo $row['news_customer_id']?>" type="checkbox"></td>
			<td align="left" valign="middle" class="<?=$class_val;?>"  width="2%"><?php echo $srno++?></td>
			<td width="31%" align="left" valign="middle" class="<?=$class_val;?>">
			<a href="home.php?request=newsletter_customers&fpurpose=edit&checkbox[0]=<?php echo $row['news_customer_id']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&search_name=<?php echo $_REQUEST['search_name']?>&search_email=<?php echo $_REQUEST['search_email']?>&pg=<?=$_REQUEST['pg']?>" title="Edit Customer" class="edittextlink" onclick="show_processing()"><? echo stripslashes($row['news_title'])."&nbsp;",stripslashes($row['news_custname'])?></a></td>
		  	<td width="34%" align="left" valign="middle" class="<?=$class_val;?>"><?=mask_emails($row['news_custemail'])?></td>
	        <td width="14%" align="left" valign="middle" class="<?=$class_val;?>"><?=($row['customer_id'])?'Yes':'No'?></td>  
			<td width="11%" align="center" valign="middle" class="<?=$class_val;?>"><?=($row['joindate'])?></td>  
			<td width="11%" align="left" valign="middle" class="<?=$class_val;?>"><?=($row['news_custhide'])?'Yes':'No'?></td>
        </tr>
      <?
	  }
	  }
	  else
	  {
	  ?>
	  <tr>
				  <td colspan="<?=$colspan?>"  align="center" valign="middle" class="norecordredtext" >
				  	No Newsletter Customers  exists.				  </td>
			</tr>
		<?
		}
		?>	
		
	  <tr>
			<td colspan="<?=round($colspan/2)?>"  align="left" valign="middle" class="listeditd" >
			<a href="home.php?request=newsletter_customers&fpurpose=add&search_name=<?php echo $_REQUEST['search_name']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&search_email=<?php echo $_REQUEST['search_email']?>" class="addlist" onclick="show_processing()">Add</a>  
			  <?
			  if($numcount)
			  {
			  ?><a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<? echo $_REQUEST['search_email']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
			  <?
			  }
			  ?>
			</td>
			<td colspan="<?=round($colspan/2)?>"  align="right" valign="middle" class="listeditd" >
			
			</td>
		</tr>
      </table>
	  </div></td>
    </tr>
	 <tr>
			
			<td colspan="2"  align="right" valign="middle" class="listing_bottom_paging" >
			<?
			if($numcount)
			  {	paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); }
			?>
			</td>
		</tr>
  </table>
</form>
