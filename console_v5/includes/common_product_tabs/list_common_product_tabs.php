<?php
	/*#################################################################
	# Script Name 	: list_common_product_tabs.php
	# Description 	: Page for listing common product tabs
	# Coded by 		: Sny
	# Created on	: 10-Aug-2010
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
//Define constants for this page
$table_name='product_common_tabs';
$page_type='Common Product Tabs';
$help_msg = get_help_messages('LIST_GEN_PROD_TAB');
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistcommontabs,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistcommontabs,\'checkbox[]\')"/>','Slno.','Title','Hidden');
$header_positions=array('center','center','left','center');
$colspan = count($table_headers);

//#Search terms
$search_fields = array('search_name');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'tab_title':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('tab_title' => 'Title');
if(!array_key_exists($sort_by,$sort_options))
	$sort_by = 'tab_title';

$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
if($_REQUEST['search_name']) {
	$where_conditions .= "AND ( tab_title LIKE '%".add_slash($_REQUEST['search_name'])."%')";
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
$query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=common_prod_tab&records_per_page=$records_per_page&search_name=".$_REQUEST['search_name']."&start=$start";
?>
<script language="javascript">
function call_ajax_changestatus(search_name,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var curid				= 0;
	var header_ids 			= '';
	var cat_orders			= '';
	var ch_status			= document.frmlistcommontabs.cbo_changehide.value;
	var qrystr				= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&ch_status='+ch_status+'&start='+start+'&pg='+pg;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlistcommontabs.elements.length;i++)
	{
		if (document.frmlistcommontabs.elements[i].type =='checkbox' && document.frmlistcommontabs.elements[i].name=='checkbox[]')
		{

			if (document.frmlistcommontabs.elements[i].checked==true)
			{
				atleastone = 1;
				if (header_ids!='')
					header_ids += '~';
				 header_ids += document.frmlistcommontabs.elements[i].value;
			}	
		}
	}
	if (atleastone==0) 
	{
		alert('Please select the Tab to change the status');
	}
	else
	{
		if(confirm('Change Status of Selected Tab(s)?'))
		{ 
				show_processing();
				Handlewith_Ajax('services/common_product_tab.php','fpurpose=change_hide&'+qrystr+'&header_ids='+header_ids);
		}	
	}	
}
function checkSelected()
{
	len=document.frmlistcommontabs.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistcommontabs.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
				user_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one tab ');
		return false;
	}
	show_processing();
	return true;
}
function edit_selected()
{
	
	len=document.frmlistcommontabs.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistcommontabs.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
				user_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one Tab ');
	}
	else if(cnt>1 ){
		alert('Please select only one Tab for editing');
	}
	else
	{
		show_processing();
		document.frmlistcommontabs.fpurpose.value='edit';
		document.frmlistcommontabs.submit();
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
	for(i=0;i<document.frmlistcommontabs.elements.length;i++)
	{
		if (document.frmlistcommontabs.elements[i].type =='checkbox' && document.frmlistcommontabs.elements[i].name=='checkbox[]')
		{

			if (document.frmlistcommontabs.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmlistcommontabs.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select Tab(s) to delete');
	}
	else
	{
		if(confirm('Are you sure you want to delete selected Tab(s)?'))
		{
			show_processing();
			Handlewith_Ajax('services/common_product_tab.php','fpurpose=delete&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
</script>
<form name="frmlistcommontabs" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="request" value="common_prod_tab" />
<input type="hidden" name="pass_start" value="<?=$start?>" />
<input type="hidden" name="pass_pg" value="<?=$pg?>" />
<input type="hidden" name="pass_sort_by" id="pass_sort_by" value="<?=$_REQUEST['sort_by']?>" />
<input type="hidden" name="pass_sort_order" id="pass_sort_order" value="<?=$_REQUEST['sort_order']?>" />
<input type="hidden" name="pass_records_per_page" id="pass_records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
<input  type="hidden" name="pass_search_name" id="pass_search_name" value="<?=$_REQUEST['search_name']?>"  />
  <table border="0" cellpadding="0" cellspacing="0" class="maintable" width="100%">
    <tr>
      <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span> List Common Product Tabs</span></div></td>
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
      <td height="48" class="sorttd" colspan="3" >
	  <div class="sorttd_div">
      	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttabletop">
        <tr>
          <td width="6%" align="left" valign="middle">Tab Title  </td>
          <td width="17%" align="left" valign="middle"><input name="search_name" type="text" class="textfeild" id="search_name" value="<?=$_REQUEST['search_name']?>"  /></td>
          <td width="12%" align="left" valign="middle">Records Per Page </td>
          <td width="10%" align="left" valign="middle"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/></td>
          <td width="11%" align="left" valign="middle">Sort By</td>
          <td width="23%" align="left" valign="middle"><?=$sort_option_txt?>&nbsp;&nbsp;&nbsp;in&nbsp;&nbsp;&nbsp;<?=$sort_by_txt?></td>
          <td width="21%" align="right" valign="middle"><input name="button5" type="submit" class="red" id="button5" value="Go" />
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_COMM_PROD_TAB_GO') ?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          </tr>
      </table>
	  </div>
      </td>
	</tr>
    
     
    <tr>
      <td colspan="3" class="listingarea">
	  <div class="listingarea_div">
      <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="listingtable">
	  <tr>
      <td class="listeditd" align="left" valign="middle" colspan="3"><a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=common_prod_tab&fpurpose=add&records_per_page=<?=$records_per_page?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$start?>&pg=<?=$pg?>" class="addlist" onclick="show_processing()">Add</a> 
	  <?
	  if($numcount)
	  {
	  ?>
	  <a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
	  <?
	  }
	  ?>
	  </td>
	   <td class="listeditd" align="right" valign="middle">
	  
	  <?
	  if($numcount)
	  {
	  ?>
        Change Hidden Status
          <select name="cbo_changehide" class="dropdown" id="cbo_changehide">
            <option value="1">Yes</option>
			<option value="0">No</option>
          </select>&nbsp;<input name="change_hide" type="button" class="red" id="change_hide" value="Change" onclick="call_ajax_changestatus('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_COMM_PROD_TAB_CHSTATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
		<?
		}
		?>
   	   </td>
    </tr>
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
	   $sql_group = "SELECT  common_tab_id,tab_title,tab_hide 
	   					FROM 
							$table_name 
							$where_conditions 
						ORDER BY 
							$sort_by $sort_order 
						LIMIT 
							$start,$records_per_page ";
	   
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
          <td align="left" valign="middle" class="<?=$class_val;?>"  width="5%"><input name="checkbox[]" value="<? echo $row['common_tab_id']?>" type="checkbox"></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"  width="2%"><?php echo $srno++?></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><a href="home.php?request=common_prod_tab&fpurpose=edit&checkbox[0]=<?php echo $row['common_tab_id']?>&pass_search_name=<?php echo $_REQUEST['search_name']?>&pass_start=<?php echo $start?>&pass_pg=<?php echo $pg?>&pass_records_per_page=<?php echo $records_per_page?>&pass_sort_by=<?=$_REQUEST['sort_by']?>&pass_sort_order=<?=$_REQUEST['sort_order']?>" title="<? echo $row['advert_title']?>" class="edittextlink" onclick="show_processing()"><? echo stripslashes($row['tab_title'])?></a></td>
		  <td align="center" valign="middle" class="<?=$class_val;?>"><?php echo ($row['tab_hide'] == 1)?'Yes':'No'; ?></td>
        </tr>
      <?
	  }
	  }
	  else
	  {
	  ?>
	  	<tr>
			<td align="center" valign="middle" class="norecordredtext" colspan="4" >
			No Common Tabs exists.
			</td>
		</tr>
		<?
		}
		?>	
		<tr>
      <td class="listeditd" align="left" valign="middle" colspan="3"><a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=common_prod_tab&fpurpose=add&records_per_page=<?=$records_per_page?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$start?>&pg=<?=$pg?>" class="addlist" onclick="show_processing()">Add</a> 
	  <?
	  if($numcount)
	  {
	  ?>
	  <a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
	  <?
	  }
	  ?>
	  </td>
	   <td class="listeditd"  align="right" valign="middle" colspan="2">
	 </td>
    </tr>
	    </table>
		</div>
		</td>
    </tr>
	<tr>
     
	   <td class="listing_bottom_paging"  align="right" valign="middle">
	  <?
	  if($numcount)
	  {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?></td>
    </tr>
    </table>
</form>
