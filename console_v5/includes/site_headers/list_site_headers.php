<?php
	/*#################################################################
	# Script Name 	: list_site_headers.php
	# Description 	: Page for listing Site header images
	# Coded by 		: ANU
	# Created on	: 1-Aug-2007
	# Modified by	: ANU
	# Modified On	: 1-Aug-2007
	#################################################################*/
//Define constants for this page
$table_name='site_headers';
$page_type='Site Headers';
$help_msg = get_help_messages('LIST_SITE_HEADERS_MESS1');
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistSiteHeader,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistSiteHeader,\'checkbox[]\')"/>','Slno.','Site Headers','Periodical change Required','Hidden');
$header_positions=array('left','left','left','left','left','left','left');
$colspan = count($table_headers);

//#Search terms
$search_fields = array('header_title');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'header_title':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('header_title' => 'Header Title');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
if($_REQUEST['search_name']) {
	$where_conditions .= "AND ( header_title LIKE '%".add_slash($_REQUEST['search_name'])."%')";
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
$query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=site_headers&records_per_page=$records_per_page&search_name=".$_REQUEST['search_name']."&start=$start";
?>
<script language="javascript">
function call_ajax_changestatus(search_name,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var curid				= 0;
	var header_ids 			= '';
	var cat_orders			= '';
	var ch_status			= document.frmlistSiteHeader.cbo_changehide.value;
	var qrystr				= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&ch_status='+ch_status+'&start='+start+'&pg='+pg;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlistSiteHeader.elements.length;i++)
	{
		if (document.frmlistSiteHeader.elements[i].type =='checkbox' && document.frmlistSiteHeader.elements[i].name=='checkbox[]')
		{

			if (document.frmlistSiteHeader.elements[i].checked==true)
			{
				atleastone = 1;
				if (header_ids!='')
					header_ids += '~';
				 header_ids += document.frmlistSiteHeader.elements[i].value;
			}	
		}
	}
	if (atleastone==0) 
	{
		alert('Please select the Site Headers to change the status');
	}
	else
	{
		if(confirm('Change Status of Selected Site Header(s)?'))
		{ 
				show_processing();
				Handlewith_Ajax('services/site_headers.php','fpurpose=change_hide&'+qrystr+'&header_ids='+header_ids);
		}	
	}	
}
function checkSelected()
{
	len=document.frmlistSiteHeader.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistSiteHeader.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
				user_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one Site Header ');
		return false;
	}
	show_processing();
	return true;
}
function edit_selected()
{
	
	len=document.frmlistSiteHeader.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistSiteHeader.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
				user_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one Site Header ');
	}
	else if(cnt>1 ){
		alert('Please select only one Site Header to edit');
	}
	else
	{
		show_processing();
		document.frmlistSiteHeader.fpurpose.value='edit';
		document.frmlistSiteHeader.submit();
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
	for(i=0;i<document.frmlistSiteHeader.elements.length;i++)
	{
		if (document.frmlistSiteHeader.elements[i].type =='checkbox' && document.frmlistSiteHeader.elements[i].name=='checkbox[]')
		{

			if (document.frmlistSiteHeader.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmlistSiteHeader.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select Site Header to delete');
	}
	else
	{
		if(confirm('Are you sure you want to delete selected Site Header?'))
		{
			show_processing();
			Handlewith_Ajax('services/site_headers.php','fpurpose=delete&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
</script>
<form name="frmlistSiteHeader" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="request" value="site_headers" />
<input type="hidden" name="pass_start" value="<?=$start?>" />
<input type="hidden" name="pass_pg" value="<?=$pg?>" />
<input type="hidden" name="pass_sort_by" id="pass_sort_by" value="<?=$_REQUEST['sort_by']?>" />
<input type="hidden" name="pass_sort_order" id="pass_sort_order" value="<?=$_REQUEST['sort_order']?>" />
<input type="hidden" name="pass_records_per_page" id="pass_records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
<input  type="hidden" name="pass_search_name" id="pass_search_name" value="<?=$_REQUEST['search_name']?>"  />
  <table border="0" cellpadding="0" cellspacing="0" class="maintable" width="100%">
    <tr>
      <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span>List Site Headers</span></div></td>
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
	<tr>
		<td colspan="3" align="right" valign="middle" class="sorttd">
		<?php	paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?>
		</td>
	</tr>
	<?php
		}
	?> 
    <tr>
      <td height="48" class="sorttd" colspan="3" >
	  <div class="editarea_div">
      	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttabletop">
        <tr>
          <td width="8%" height="30" align="left" valign="middle">Headers Title  </td>
          <td width="22%" height="30" align="left" valign="middle"><input name="search_name" type="text" class="textfeild" id="search_name" value="<?=$_REQUEST['search_name']?>"  /></td>
          <td width="10%" height="30" align="left" valign="middle">Records Per Page </td>
          <td width="9%" height="30" align="left" valign="middle"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/></td>
          <td width="4%" height="30" align="left" valign="middle">Sort By</td>
          <td width="33%" height="30" align="left" valign="middle"><?=$sort_option_txt?>&nbsp;&nbsp;&nbsp;in&nbsp;&nbsp;&nbsp;  <?=$sort_by_txt?></td>
          <td width="14%" height="30" align="right" valign="middle">&nbsp;
            <input name="button5" type="submit" class="red" id="button5" value="Go" />
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_SITE_HEADERS_GO') ?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
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
      <td class="listeditd" colspan="3"><a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=site_headers&fpurpose=add&records_per_page=<?=$records_per_page?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$start?>&pg=<?=$pg?>" class="addlist" onclick="show_processing()">Add</a> 
	  <?
	  if($numcount)
	  {
	  ?>
	  <a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
	  <?
	  }
	  ?>
	  </td>
	   <td class="listeditd" align="right" colspan="2">
	  
	  <?
	  if($numcount)
	  {
	  ?>
        Change Status
          <select name="cbo_changehide" class="dropdown" id="cbo_changehide">
            <option value="1">Yes</option>
			<option value="0">No</option>
          </select>&nbsp;<input name="change_hide" type="button" class="red" id="change_hide" value="Change" onclick="call_ajax_changestatus('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_SITE_HEADERS_CHSTATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
		<?
		}
		?>
   	   </td>
    </tr>
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
	   $sql_group = "SELECT header_id,header_title,header_period_change_required,header_hide FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
	   
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
          <td align="left" valign="middle" class="<?=$class_val;?>"  width="5%"><input name="checkbox[]" value="<? echo $row['header_id']?>" type="checkbox"></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"  width="2%"><?php echo $srno++?></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><a href="home.php?request=site_headers&fpurpose=edit&checkbox[0]=<?php echo $row['header_id']?>&pass_search_name=<?php echo $_REQUEST['search_name']?>&pass_start=<?php echo $start?>&pass_pg=<?php echo $pg?>&pass_records_per_page=<?php echo $records_per_page?>&pass_sort_by=<?=$_REQUEST['sort_by']?>&pass_sort_order=<?=$_REQUEST['sort_order']?>" title="<? echo $row['advert_title']?>" class="edittextlink" onclick="show_processing()"><? echo $row['header_title']?></a></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>" width="30%">
		  <?php echo ($row['header_period_change_required'] == 1)?'Yes':'No';
				  		/*// Find the feature_id for mod_productcatgroup module from features table
						$sql_feat = "SELECT feature_id FROM features WHERE feature_modulename='mod_adverts'";
						$ret_feat = $db->query($sql_feat);
						if ($db->num_rows($ret_feat))
						{
							$row_feat 	= $db->fetch_array($ret_feat);
							$feat_id	= $row_feat['feature_id'];
						}
						$disp_array	= array();
						$sql_disp = "SELECT display_position,themes_layouts_layout_id,
									layout_code,display_order FROM display_settings 
									WHERE sites_site_id=$ecom_siteid AND 
									display_component_id = ".$row['advert_id'];
						$ret_disp = $db->query($sql_disp);
						if ($db->num_rows($ret_disp))
						{
							while ($row_disp = $db->fetch_array($ret_disp))
							{
								$layoutid		= $row_disp['themes_layouts_layout_id'];
								$layoutcode		= $row_disp['layout_code'];
								//Find the layout name 
								$sql_lay = "SELECT layout_name FROM themes_layouts WHERE layout_id=$layoutid AND layout_code='$layoutcode'";
								$ret_lay = $db->query($sql_lay);
								if ($db->num_rows($ret_lay))
								{	
									$row_lay = $db->fetch_array($ret_lay);
								}
								$disp_array[] 	= $row_lay['layout_name']."(".stripslashes($row_disp['display_position']).")(".$row_disp['display_order'].")";
							}
						}
						if(count($disp_array)){		
						echo generateselectbox('display_ids',$disp_array,0);
						}else{
						echo "No Display Position Selected";
						}*/
		 		 ?>	
		  &nbsp;</td>
		   
        
          <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo ($row['header_hide'] == 1)?'Yes':'No'; ?></td>
         
        </tr>
      <?
	  }
	  }
	  else
	  {
	  ?>
	  <tr> <td align="center" valign="middle" class="norecordredtext" colspan="5" >  	No Site Headers  exists.</td>	</tr>
		<?
		}
		?>	
		 <tr>
      <td class="listeditd" colspan="3"><a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=site_headers&fpurpose=add&records_per_page=<?=$records_per_page?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$start?>&pg=<?=$pg?>" class="addlist" onclick="show_processing()">Add</a> 
	  <?
	  if($numcount)
	  {
	  ?>
	  <a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
	  <?
	  }
	  ?>
	  </td>
	   <td class="listeditd" colspan="2"  align="right">
	 
   	   </td>
    </tr>
      </table>
	  </div></td>
    </tr>
	<tr>
      <td class="listing_bottom_paging" colspan="2"  align="right">
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
