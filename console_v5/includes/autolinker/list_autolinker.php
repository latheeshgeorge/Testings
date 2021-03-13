<?php
/*#################################################################
# Script Name 		: list_autolinker.php
# Description 		: Page for listing autolinker details
# Coded by 			: Sny
# Created on		: 03-Aug-2009
#################################################################*/
//Define constants for this page
$table_name='seo_autolinker';
$page_type='Autolinker';
if(!$inWebclinic)
{
	echo "<center><br><br><span class='redtext'><strong>You are not authorised to view this page as your website is not there in Webclinic.</strong></span></center>";
	//exit;
}
$help_msg = get_help_messages('LIST_AUTOLINKER_MESS1');
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmautolinker,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmautolinker,\'checkbox[]\')"/>','Slno.','Keyword','URL','Number of Times','Allow No Follow?'	);
$header_positions=array('left','left','left','left','center','center');
$colspan = count($table_headers);

//#Search terms
$search_fields = array('search_autolinker_keyword');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'autolinker_keyword':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('autolinker_keyword' => 'Keyword', 'autolinker_no_of_times' => 'Number of times');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
if($_REQUEST['search_autolinker_keyword']) {
	$where_conditions .= "AND (autolinker_keyword LIKE '%".add_slash($_REQUEST['search_autolinker_keyword'])."%')";
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
$query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=autolinker&records_per_page=$records_per_page&search_autolinker_keyword=".$_REQUEST['search_autolinker_keyword']."&start=$start";
?>
<script language="javascript">
function edit_selected()
{
	
	len=document.frmautolinker.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmautolinker.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
				user_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one Autolinker ');
	}
	else if(cnt>1 ){
		alert('Please select only one Autolinker to edit');
	}
	else
	{
		show_processing();
		document.frmautolinker.fpurpose.value='edit';
		document.frmautolinker.submit();
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
	var qrystr				= 'search_autolinker_keyword='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmautolinker.elements.length;i++)
	{
		if (document.frmautolinker.elements[i].type =='checkbox' && document.frmautolinker.elements[i].name=='checkbox[]')
		{

			if (document.frmautolinker.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmautolinker.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select Autolinker to delete');
	}
	else
	{
		if(confirm('Are you sure you want to delete selected Auto linker Details?'))
		{
			show_processing();
			Handlewith_Ajax('services/autolinker.php','fpurpose=delete&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
</script>
<form name="frmautolinker" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="request" value="autolinker" />
<input type="hidden" name="start" value="<?=$start?>" />
<input type="hidden" name="pg" value="<?=$pg?>" />
  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span> List Auto Linker</span></div></td>
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
    <tr><td colspan="3" align="right" valign="middle" class="sorttd"><?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?></td></tr>
	<?php
		}
	?>
	<tr>
      <td height="48" class="sorttd" colspan="3" >
	  <div class="sorttd_div">
      	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttabletop">
        <tr>
          <td width="5%" height="30" align="left" valign="middle">Keyword </td>
          <td width="11%" height="30" align="left" valign="middle"><input name="search_autolinker_keyword" type="text" class="textfeild" id="search_autolinker_keyword" value="<?=$_REQUEST['search_autolinker_keyword']?>"  /></td>
          <td width="8%" height="30" align="left" valign="middle">Records Per Page </td>
          <td width="5%" height="30" align="left" valign="middle"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/></td>
          <td width="4%" height="30" align="left" valign="middle">Sort By</td>
          <td width="20%" height="30" align="left" valign="middle"><?=$sort_option_txt?>&nbsp;&nbsp;&nbsp;in&nbsp;&nbsp;&nbsp; <?=$sort_by_txt?></td>
          <td width="22%" height="30" align="left" valign="middle"><input name="button5" type="submit" class="red" id="button5" value="Go" />
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_AUTOLINKER_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          <td width="23%" height="30" align="left" valign="middle">&nbsp;</td>
        </tr>      
      </table>
      </div></td>
    </tr>
    <tr>
      <td colspan="3" class="listingarea">
	  <div class="listingarea_div">
      <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="listingtable">
	  
		<tr>
		  <td class="listeditd" align="left" valign="middle" colspan="6"><a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=autolinker&fpurpose=add&records_per_page=<?=$records_per_page?>&search_autolinker_keyword=<?=$_REQUEST['search_autolinker_keyword']?>&start=<?=$start?>&pg=<?=$pg?>" class="addlist" onclick="show_processing()">Add</a> 
		  <?
		  if($numcount)
		  {
		  ?>
		  <a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_autolinker_keyword']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
		  <?
		  }
		  ?>
		  </td>
		</tr>
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
	   $sql_autolinker = "SELECT autolinker_id, autolinker_keyword, autolinker_url, autolinker_no_of_times, autolinker_allow_no_follow
					FROM 
						$table_name 
						$where_conditions 
					ORDER BY 
						$sort_by $sort_order 
					LIMIT 
						$start,$records_per_page ";
	   
	   $res = $db->query($sql_autolinker);
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
          <td align="left" valign="middle" class="<?=$class_val;?>"  width="6%"><input name="checkbox[]" value="<? echo $row['autolinker_id']?>" type="checkbox"></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"  width="2%"><?php echo $srno++?></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><a href="home.php?request=autolinker&fpurpose=edit&checkbox[0]=<?php echo $row['autolinker_id']?>&search_autolinker_keyword=<?php echo $_REQUEST['search_autolinker_keyword']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>"  class="edittextlink" onclick="show_processing()"><? echo stripslashes($row['autolinker_keyword'])?></a></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>" width="30%"><? echo stripslashes($row['autolinker_url'])?></td>
          <td align="center" valign="middle" class="<?=$class_val;?>"><? echo stripslashes($row['autolinker_no_of_times'])?></td>
		  <td align="center" valign="middle" class="<?=$class_val;?>" width="12%"><? echo ($row['autolinker_allow_no_follow']==1)?'Yes':'No'?></td>
         
        </tr>
      <?
	  }
	  }
	  else
	  {
	  ?>
	  	<tr>
			<td align="center" valign="middle" class="norecordredtext" colspan="6" >No Autolinker Details exists.</td>
		</tr>
		<?
		}
		?>
		<tr>
      <td class="listeditd" align="left" valign="middle" colspan="3"><a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=autolinker&fpurpose=add&records_per_page=<?=$records_per_page?>&search_autolinker_keyword=<?=$_REQUEST['search_autolinker_keyword']?>&start=<?=$start?>&pg=<?=$pg?>" class="addlist" onclick="show_processing()">Add</a> 
	  <?
	  if($numcount)
	  {
	  ?>
	  <a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_autolinker_keyword']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
	  <?
	  }
	  ?>
	  </td>
	   <td class="listeditd" align="right" valign="middle" colspan="3">
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
