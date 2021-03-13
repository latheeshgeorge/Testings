<?php
	/*#################################################################
	# Script Name 	: list_customer.php
	# Description 	: Page for listing Customer
	# Coded by 		: Latheesh
	# Created on	: 12-Sep-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
//Define constants for this page
$table_name='saved_search';
$page_type='Saved Keywords';
$help_msg = get_help_messages('LIST_SAVED_KEYWORD_MESS1');
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmsearchKeyword,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmsearchKeyword,\'checkbox[]\')"/>','Slno.','Saved Keyword','Description','Search Count');
$header_positions=array('left','left','left','left','center');
$colspan = count($table_headers);

//#Search terms
$search_fields = array('search_keyword');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'search_count':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'DESC':$_REQUEST['sort_order'];
$sort_options = array('search_keyword' => 'Keyword','search_count'=>'Search Count');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
if($_REQUEST['search_name']) {
	$where_conditions .= "AND ( search_keyword LIKE '%".add_slash($_REQUEST['search_name'])."%')";
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
 $query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=seo_keyword&fpurpose=saved_keyword&records_per_page=$records_per_page&search_name=".$_REQUEST['search_name']."&start=$start";

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
function call_ajax_delete(search_name,sortby,sortorder,recs,start,pg)
{
	var atleastone 	= 0;
	var del_ids 	= '';
	var qrystr				= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmsearchKeyword.elements.length;i++)
	{
		if (document.frmsearchKeyword.elements[i].type =='checkbox' && document.frmsearchKeyword.elements[i].name=='checkbox[]')
		{

			if (document.frmsearchKeyword.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmsearchKeyword.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select keyword to delete');
	}
	else
	{
		if(confirm('Are you sure you want to delete selected keyword?'))
		{
			show_processing();
			Handlewith_Ajax('services/seo_keyword.php','fpurpose=delete_savedkeyword&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
function save_desc()
 {
 var atleastone 	= 0;
 var del_ids 	= '';
 	for(i=0;i<document.frmsearchKeyword.elements.length;i++)
	{
		if (document.frmsearchKeyword.elements[i].type =='checkbox' && document.frmsearchKeyword.elements[i].name=='checkbox[]')
		{

			if (document.frmsearchKeyword.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmsearchKeyword.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select keyword to Save the Details ');
	}
	else {
	document.frmsearchKeyword.fpurpose.value='SaveKeywordDesc';
	document.frmsearchKeyword.submit();
	}
 }
</script>
<form name="frmsearchKeyword" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="saved_keyword" />
<input type="hidden" name="request" value="seo_keyword" />
<input type="hidden" name="start" value="<?=$start?>" />
<input type="hidden" name="pg" value="<?=$pg?>" />
  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td colspan="4" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span> List Entire SEO <?=$page_type?> </span></div>
		    <img src="images/blueline.gif" alt="" border="0" height="1" width="400"></td>
    </tr>
	<tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="4">
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
          			<td colspan="4" align="center" valign="middle" class="errormsg"><?php echo($alert);?></td>
          		</tr>
		 <?php
		 	}
		 ?> 
	<?php
	  if($numcount)
	  {
	  ?>
    <tr>
		<td colspan="4" align="right" valign="middle" class="sorttd"><?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?></td>
	</tr>
	<?php
	  }
	?>
	<tr>
      <td height="48" class="sorttd" colspan="4" >
	  <div class="sorttd_div">
      	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttabletop">
        <tr>
          <td width="13%" height="30" align="left" valign="middle">Saved Keyword Like </td>
          <td width="18%" height="30" align="left" valign="middle"><input name="search_name" type="text" class="textfeild" id="search_name" value="<?=$_REQUEST['search_name']?>"  /></td>
          <td width="10%" height="30" align="left" valign="middle">Records Per Page </td>
          <td width="16%" height="30" align="left" valign="middle"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/></td>
          <td width="7%" height="30" align="left" valign="middle">Sort By</td>
          <td width="26%" height="30" align="left" valign="middle"><?=$sort_option_txt?>&nbsp;&nbsp;&nbsp;in&nbsp;&nbsp;&nbsp;<?=$sort_by_txt?></td>
          <td width="10%" height="30" align="right" valign="middle"><input name="button5" type="submit" class="red" id="button5" value="Go" />
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_SAVED_KEYWORD_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          </tr>
      </table>
	  </div>
      </td>
	</tr>
    
    <tr>
      <td colspan="4" class="listingarea">
	  <div class="listingarea_div">
      <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="listingtable">
	  <tr><td class="listeditd" align="left" valign="middle" colspan="3">
	  <?
	  if($numcount)
	  {
	  ?>
	 <a href="#" onClick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
	  <?
	  }
	  ?>
	  </td>
	 <td class="listeditd" align="right" valign="middle" colspan="2">
	 <input type="button" class="red" name="key_save" value=" Save " onclick="save_desc();"  />
	 </td>
    </tr>
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
	   $sql_keyword="SELECT * from saved_search $where_conditions ORDER BY $sort_by $sort_order LIMIT $start, $records_per_page ";
	   $res = $db->query($sql_keyword);
	   //$srno = 1; 
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
       <td align="left" valign="middle" class="<?=$class_val;?>"  width="6%"><input name="checkbox[]" value="<? echo $row['search_id']?>" type="checkbox"></td>
	   <td align="left" valign="middle" class="<?=$class_val;?>"  width="12%"><?php echo $srno++?></td>
	   <td align="left" valign="middle" class="<?=$class_val;?>" ><?php echo $row['search_keyword']; ?></td>
	   <td align="left" valign="middle" class="<?=$class_val;?>" ><textarea name="txt_<?php echo $row['search_id']; ?>" rows="1" cols="50"><?php echo $row['search_desc']; ?></textarea></td>
	   <td align="center" valign="middle" class="<?=$class_val;?>" ><input type="text" name="searchcount_<?php echo $row['search_id']; ?>" value="<?php echo $row['search_count']; ?>" size="8" style="text-align:center" /></td>
    </tr>
      <?
	  }
	  }
	  else
	  {
	  ?>
	  <tr>
		  <td align="center" valign="middle" class="norecordredtext" >
			No Keyword exists.				  </td>
	  </tr>
		<?
		}
		?>	
		<tr><td class="listeditd" align="left" valign="middle" colspan="3">
	  <?
	  if($numcount)
	  {
	  ?>
	  <a href="#" onClick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
	  <?
	  }
	  ?>
	  </td>
	   <td class="listeditd" align="right" valign="middle" colspan="2">
	 </td>
    </tr>
      </table>
	  </div></td>
    </tr>
	<tr>
	   <td class="listing_bottom_paging" align="right" valign="middle" colspan="2">
	  <?
	  if($numcount)
	  {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?></td>
    </tr>
  
  </table>
</form>
