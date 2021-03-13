<?php
	/*#################################################################
	# Script Name 	: list_customer_corporation.php
	# Description 	: Page for listing Corporation
	# Coded by 		: ANU
	# Created on	: 19-July-2007
	# Modified by	: ANU
	# Modified On	: 19-July-2007
	#################################################################*/
//Define constants for this page
$table_name='customers_corporation';
$page_type='Business Customers';
$help_msg = get_help_messages('LIST_CUST_CCORP_MESS1');
/*$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistCustomerCorporation,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistCustomerCorporation,\'checkbox[]\')"/>','Slno.','Corporation Name','Corporation Type','Cost Plus','Discount','Hidden','Departments');
$header_positions=array('left','left','left','left','left','left','left','left','left','left','left');
*/
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistCustomerCorporation,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistCustomerCorporation,\'checkbox[]\')"/>','Slno.','Business Name','Business Type','Hidden','Departments');
$header_positions=array('left','left','left','left','left','left','left','left','left');

$colspan = count($table_headers);


//#Search terms
$search_fields = array('corporation_name');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'corporation_name':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('corporation_name' => 'Business Name');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
if($_REQUEST['search_name']) {
	$where_conditions .= "AND ( corporation_name LIKE '%".add_slash($_REQUEST['search_name'])."%')";
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
$query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=customer_corporation&records_per_page=$records_per_page&search_name=".$_REQUEST['search_name']."&start=$start";
?>
<script language="javascript">
function call_ajax_changestatus(search_name,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var curid				= 0;
	var corporation_ids 			= '';
	var cat_orders			= '';
	var ch_status			= document.frmlistCustomerCorporation.cbo_changehide.value;
	var qrystr				= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&ch_status='+ch_status+'&start='+start+'&pg='+pg;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlistCustomerCorporation.elements.length;i++)
	{
		if (document.frmlistCustomerCorporation.elements[i].type =='checkbox' && document.frmlistCustomerCorporation.elements[i].name=='checkbox[]')
		{

			if (document.frmlistCustomerCorporation.elements[i].checked==true)
			{
				atleastone = 1;
				if (corporation_ids!='')
					corporation_ids += '~';
				 corporation_ids += document.frmlistCustomerCorporation.elements[i].value;
			}	
		}
	}
	if (atleastone==0) 
	{
		alert('Please select the corporations to change the status');
	}
	else
	{
		if(confirm('Change Status of Selected corporation(s)?'))
		{
				show_processing();
				Handlewith_Ajax('services/customer_corporation.php','fpurpose=change_hide&'+qrystr+'&corporation_ids='+corporation_ids);
		}	
	}	
}
function checkSelected()
{
	len=document.frmlistCustomerCorporation.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistCustomerCorporation.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
				user_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one corporation ');
		return false;
	}
	show_processing();
	return true;
}
function edit_selected()
{
	
	len=document.frmlistCustomerCorporation.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistCustomerCorporation.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
				user_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one corporation ');
	}
	else if(cnt>1 ){
		alert('Please select only one corporation to edit');
	}
	else
	{
		show_processing();
		document.frmlistCustomerCorporation.fpurpose.value='edit';
		document.frmlistCustomerCorporation.submit();
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
	for(i=0;i<document.frmlistCustomerCorporation.elements.length;i++)
	{
		if (document.frmlistCustomerCorporation.elements[i].type =='checkbox' && document.frmlistCustomerCorporation.elements[i].name=='checkbox[]')
		{

			if (document.frmlistCustomerCorporation.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmlistCustomerCorporation.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select corporation to delete');
	}
	else
	{
		if(confirm('Are you sure you want to delete selected corporation?'))
		{
			show_processing();
			Handlewith_Ajax('services/customer_corporation.php','fpurpose=delete&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
</script>
<form name="frmlistCustomerCorporation" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="request" value="customer_corporation" />
<input type="hidden" name="start" value="<?=$start?>" />
<input type="hidden" name="pg" value="<?=$pg?>" />
<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
<input  type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>"  />
  <table border="0" cellpadding="0" cellspacing="0" class="maintable" width="100%">
    <tr>
      <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span> List Business Customers</span></div></td>
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
		<td colspan="3" align="right" valign="middle" class="sorttd">
		<?php
			 paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
		?>
		  </td>
	</tr>
	<?php
		}
	?>
    <tr>
      <td height="48" class="sorttd" colspan="3" >
	  <div class="sorttd_div" >
      	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttabletop">
        <tr>
          <td width="9%" height="30" align="left" valign="middle">Business Name </td>
          <td width="24%" height="30" align="left" valign="middle"><input name="search_name" type="text" class="textfeild" id="search_name" value="<?=$_REQUEST['search_name']?>"  /></td>
          <td width="11%" height="30" align="left" valign="middle">Records Per Page </td>
          <td width="15%" height="30" align="left" valign="middle"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/></td>
          <td width="5%" height="30" align="left" valign="middle">Sort By</td>
          <td width="25%" height="30" align="left" valign="middle"><?=$sort_option_txt?>&nbsp;&nbsp;&nbsp;in&nbsp;&nbsp;&nbsp;  <?=$sort_by_txt?></td>
          <td width="11%" height="30" align="right" valign="middle">&nbsp;&nbsp;&nbsp;&nbsp;
            <input name="button5" type="submit" class="red" id="button5" value="Go" />
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_CUST_CORP_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          </tr>
      </table>
	  </div>
      </td>
    </tr>
	
    
     
    <tr>
      <td colspan="3" class="listingarea">
	   <div class="listingarea_div" >
      <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="listingtable">
	  <tr>
      <td class="listeditd" colspan="3"><a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=customer_corporation&fpurpose=add&records_per_page=<?=$records_per_page?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$start?>&pg=<?=$pg?>" class="addlist" onclick="show_processing()">Add</a> 
	  <?
	  if($numcount)
	  {
	  ?>
	  <a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
	  <?
	  }
	  ?>
	  </td>
	   <td class="listeditd" align="right" colspan="3">
	  <?
	  if($numcount)
	  {
	  ?>
        Change Status
          <select name="cbo_changehide" class="dropdown" id="cbo_changehide">
            <option value="1">Yes</option>
			<option value="0">No</option>
          </select>&nbsp;<input name="change_hide" type="button" class="red" id="change_hide" value="Change" onclick="call_ajax_changestatus('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_CUST_CORP_CHSTATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
		<?
		}
		?>
   	   </td>
    </tr>
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
	   $sql_group = "SELECT corporation_id,corporation_name,corporation_type,corporation_discount,corporation_costplus,corporation_hide FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
	   
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
        <tr >
          <td align="left" valign="middle" class="<?=$class_val;?>"  width="5%"><input name="checkbox[]" value="<? echo $row['corporation_id']?>" type="checkbox"></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"  width="2%"><?php echo $srno++?></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><a href="home.php?request=customer_corporation&fpurpose=edit&checkbox[0]=<?php echo $row['corporation_id']?>&search_name=<?php echo $_REQUEST['search_name']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>" title="<? echo $row['corporation_name']?>" class="edittextlink" onclick="show_processing()"><? echo $row['corporation_name']?></a></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"><?=$row['corporation_type']; ?></td>
		    <?php /*<td align="left" valign="middle" class="<?=$class_val;?>"><?=$row['corporation_costplus']; ?></td>*/?>
			  <?php /*?>  <td align="left" valign="middle" class="<?=$class_val;?>"><?=$row['corporation_discount']; ?>%</td><?php */?>
				<td align="left" valign="middle" class="<?=$class_val;?>"><?=($row['corporation_hide'])?'Yes':'No'?></td>   
        	    <td align="left" valign="middle" class="<?=$class_val;?>">
        	  <?php 
				  $sql_department_cnt = "SELECT count(department_id) as cnt FROM customers_corporation_department WHERE customers_corporation_corporation_id = ".$row['corporation_id']." AND sites_site_id=".$ecom_siteid ;
				  $res_department_cnt = $db->query($sql_department_cnt);
				  $department_cnt = $db->fetch_array($res_department_cnt);
		 		  echo $department_cnt['cnt'];
		 	?></td>
      </tr>
      <?
	  }
	  }
	  else
	  {
	  ?>
  		<tr>
			  <td align="center" valign="middle" class="norecordredtext" colspan="7" >
			  	No Business Customers exists.
			  </td>
		</tr>
		<?
		}
		?>	
		<tr>
      <td class="listeditd" colspan="3"><a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=customer_corporation&fpurpose=add&records_per_page=<?=$records_per_page?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$start?>&pg=<?=$pg?>" class="addlist" onclick="show_processing()">Add</a> 
		  <?
		  if($numcount)
		  {
		  ?>
		  	<a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
		  <?
		  }
		  ?>
	  </td>
	   <td class="listeditd"  align="right" colspan="3">
		
	   </td>
    </tr>
      </table>
	  </div>
      </td>
    </tr>
	<tr>
     
	   <td class="listing_bottom_paging"  align="right" colspan="2">
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
