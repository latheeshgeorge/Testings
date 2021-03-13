<?php
	/*#################################################################
	# Script Name 	: list_survey.php
	# Description 	: Page for listing Surveys
	# Coded by 		: ANU
	# Created on	: 6-Aug-2007
	# Modified by	: ANU
	# Modified On	: 6-Aug-2007
	#################################################################*/
//Define constants for this page
$table_name='survey';
$page_type='Surveys';
$help_msg = get_help_messages('LIST_SURVAY_MESS1');
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistSurveys,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistSurveys,\'checkbox[]\')"/>','Slno.','Survey Title','Layout (position)(order)','Survey Status','Hidden');
$header_positions=array('left','left','left','left','left','left','left');
$colspan = count($table_headers);

//#Search terms
$search_fields = array('survey_title');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'survey_title':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('survey_title' => 'Survey Title','survey_status' => 'Survey Status');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
if($_REQUEST['search_name']) {
	$where_conditions .= "AND ( survey_title LIKE '%".add_slash($_REQUEST['search_name'])."%')";
}
if($_REQUEST['status']){
$where_conditions .= "AND ( survey_status LIKE '%".add_slash($_REQUEST['status'])."%')";
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
$status=$_REQUEST['status'];
$query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=survey&records_per_page=$records_per_page&search_name=".$_REQUEST['search_name']."&start=$start&status=$status";
?>
<script language="javascript">
function call_ajax_changestatus(search_name,sortby,sortorder,recs,start,pg,status)
{
	var atleastone 			= 0;
	var curid				= 0;
	var survey_ids 			= '';
	var cat_orders			= '';
	var ch_status			= document.frmlistSurveys.cbo_changehide.value;
	var qrystr				= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&ch_status='+ch_status+'&start='+start+'&pg='+pg+'&status='+status;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlistSurveys.elements.length;i++)
	{
		if (document.frmlistSurveys.elements[i].type =='checkbox' && document.frmlistSurveys.elements[i].name=='checkbox[]')
		{

			if (document.frmlistSurveys.elements[i].checked==true)
			{
				atleastone = 1;
				if (survey_ids!='')
					survey_ids += '~';
				 survey_ids += document.frmlistSurveys.elements[i].value;
			}	
		}
	}
	if (atleastone==0) 
	{
		alert('Please select the Survey to change the status');
	}
	else
	{
		if(confirm('Change Status of Selected Survey(s)?'))
		{
				show_processing();
				Handlewith_Ajax('services/survey.php','fpurpose=change_hide&'+qrystr+'&survey_ids='+survey_ids);
		}	
	}	
}
function call_ajax_change_survey_status(search_name,sortby,sortorder,recs,start,pg,status)
{
	var atleastone 			= 0;
	var curid				= 0;
	var survey_ids 			= '';
	var cat_orders			= '';
	var ch_survey_status			= document.frmlistSurveys.survey_status.value;
	var qrystr				= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&ch_survey_status='+ch_survey_status+'&start='+start+'&pg='+pg+'&status='+status;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlistSurveys.elements.length;i++)
	{
		if (document.frmlistSurveys.elements[i].type =='checkbox' && document.frmlistSurveys.elements[i].name=='checkbox[]')
		{

			if (document.frmlistSurveys.elements[i].checked==true)
			{
				atleastone = 1;
				if (survey_ids!='')
					survey_ids += '~';
				 survey_ids += document.frmlistSurveys.elements[i].value;
			}	
		}
	}
	if (atleastone==0) 
	{
		alert('Please select the Survey to change the status');
	}
	else
	{
		if(confirm('Change Status of Selected Survey(s)?'))
		{
				show_processing();
				Handlewith_Ajax('services/survey.php','fpurpose=change_survey_status&'+qrystr+'&survey_ids='+survey_ids);
		}	
	}	
}
function checkSelected()
{
	len=document.frmlistSurveys.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistSurveys.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
				user_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one Survey ');
		return false;
	}
	show_processing();
	return true;
}
function edit_selected()
{
	
	len=document.frmlistSurveys.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistSurveys.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
				user_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one Survey ');
	}
	else if(cnt>1 ){
		alert('Please select only one Survey to edit');
	}
	else
	{
		show_processing();
		document.frmlistSurveys.fpurpose.value='edit';
		document.frmlistSurveys.submit();
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
function call_ajax_delete(search_name,sortby,sortorder,recs,start,pg,status)
{
	var atleastone 	= 0;
	var del_ids 	= '';
	var qrystr				= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&status='+status;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlistSurveys.elements.length;i++)
	{
		if (document.frmlistSurveys.elements[i].type =='checkbox' && document.frmlistSurveys.elements[i].name=='checkbox[]')
		{

			if (document.frmlistSurveys.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmlistSurveys.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select Survey to delete');
	}
	else
	{
		if(confirm('Are you sure you want to delete selected Survey?'))
		{
			show_processing();
			Handlewith_Ajax('services/survey.php','fpurpose=delete&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
</script>
<form name="frmlistSurveys" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="request" value="survey" />
<input type="hidden" name="pass_start" value="<?=$start?>" />
<input type="hidden" name="pass_pg" value="<?=$pg?>" />
<input type="hidden" name="status" value="<?=$status?>" />
<input type="hidden" name="pass_sort_by" id="pass_sort_by" value="<?=$_REQUEST['sort_by']?>" />
<input type="hidden" name="pass_sort_order" id="pass_sort_order" value="<?=$_REQUEST['sort_order']?>" />
<input type="hidden" name="pass_records_per_page" id="pass_records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
<input  type="hidden" name="pass_search_name" id="pass_search_name" value="<?=$_REQUEST['search_name']?>"  />
  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span> List Surveys</span></div></td>
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
          <td width="6%" height="30" align="left" valign="middle">Survey  title </td>
          <td width="12%" height="30"   align="left" valign="middle"><input name="search_name" type="text" class="textfeild" id="search_name" value="<?=$_REQUEST['search_name']?>"  /></td>
		  <td width="4%" height="30" align="left" valign="middle">Status </td>
          <td width="8%" height="30"   align="left" valign="middle"><?= generateselectbox('status',array('0' => 'Any','1' => 'New','2' => 'Active','3' => 'Finished','4' => 'Published'),$_REQUEST['status']);?> </td>
          <td width="8%" height="30"   align="left" valign="middle">Records Per Page </td>
          <td width="4%" height="30"   align="left" valign="middle"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/></td>
          <td width="4%" height="30"   align="left" valign="middle">Sort By</td>
          <td width="20%" height="30"   align="left" valign="middle"><?=$sort_option_txt?>&nbsp;&nbsp;&nbsp;in&nbsp;&nbsp;&nbsp;<?=$sort_by_txt?></td>
          <td width="6%" height="30"   align="right" valign="middle">&nbsp;
            <input name="button5" type="submit" class="red" id="button5" value="Go" />
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_SURVAY_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
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
      <td class="listeditd" align="left" valign="middle" colspan="3"><a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=survey&fpurpose=add&records_per_page=<?=$records_per_page?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$start?>&pg=<?=$pg?>&status=<?=$status?>" class="addlist" onclick="show_processing()">Add</a> 
	  <?
	  if($numcount)
	  {
	  ?>
	  <a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>','<?=$status?>')" class="deletelist">Delete</a>
	  <?
	  }
	  ?>
	  </td>
	   <td class="listeditd" align="right" valign="middle" colspan="3">
	  
	  <?
	  if($numcount)
	  {
	  ?>
	  Change Survey Status
         
		   <select name="survey_status" class="dropdown" id="survey_status">
		     <option value="1">New</option>
		     <option value="2">Active</option>
		     <option value="3">Finish</option>
		     <option value="4">Publish</option>
             </select>&nbsp;
		  <? //$status_array = array('1' =>'NEW','2' => 'ACTIVE','3' => 'FINISH','4' => 'PUBLISH');
		  // echo generateselectbox('survey_status',$status_array,$selected);?>
		  <input name="change_survey_status" type="button" class="red" id="change_survey_status" value="Change" onclick="call_ajax_change_survey_status('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>','<?php echo $status?>')" />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_SURVAY_CHSTAUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
        Change Hidden  Status
          <select name="cbo_changehide" class="dropdown" id="cbo_changehide">
            <option value="1">Yes</option>
			<option value="0">No</option>
          </select>&nbsp;<input name="change_hide" type="button" class="red" id="change_hide" value="Change" onclick="call_ajax_changestatus('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>','<?php echo $status?>')" />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_SURVAY_CHHIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
		   
		<?
		}
		?>
   	   </td>
    </tr>
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
	   $sql_group = "SELECT survey_id,survey_title,survey_question,survey_hide,survey_status FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
	   
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
          <td align="left" valign="middle" class="<?=$class_val;?>"  width="5%"><input name="checkbox[]" value="<? echo $row['survey_id']?>" type="checkbox"></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"  width="2%"><?php echo $srno++?></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><a href="home.php?request=survey&fpurpose=edit&checkbox[0]=<?php echo $row['survey_id']?>&pass_search_name=<?php echo $_REQUEST['search_name']?>&pass_start=<?php echo $start?>&pass_pg=<?php echo $pg?>&pass_records_per_page=<?php echo $records_per_page?>&pass_sort_by=<?=$_REQUEST['sort_by']?>&pass_sort_order=<?=$_REQUEST['sort_order']?>&status=<?=$status?>" title="<? echo $row['survey_title']?>" class="edittextlink" onclick="show_processing()"><? echo $row['survey_title']?></a></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>">
		  <?php
				  		// Find the feature_id for mod_survey module from features table
						$sql_feat = "SELECT feature_id FROM features WHERE feature_modulename='mod_survey'";
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
									display_component_id = ".$row['survey_id']. " AND features_feature_id=".$feat_id."";
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
						}
		 		 ?>	
		  &nbsp;</td>
		    <td align="left" valign="middle" class="<?=$class_val;?>"><?php switch($row['survey_status']){
			case 1:
			echo "New";
			break;
			case 2:
			echo "Active";
			break;
			case 3:
			echo "Finished";
			break;
			case 4:
			echo "Published";
			break;
			
			} 
		   ?></td>
        
          <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo ($row['survey_hide'] == 1)?'Yes':'No'; ?></td>
         
        </tr>
      <?
	  }
	  }
	  else
	  {
	  ?>
	  <tr>
				  <td align="center" valign="middle" class="norecordredtext" colspan="<?=$colspan?>">
				  	No Surveys  exists.				  </td>
		  </tr>
		<?
		}
		?>	
		<tr>
      <td class="listeditd" align="left" valign="middle"  colspan="<?=round($colspan/2)?>"><a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=survey&fpurpose=add&records_per_page=<?=$records_per_page?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$start?>&pg=<?=$pg?>&status=<?=$status?>" class="addlist" onclick="show_processing()">Add</a> 
	  <?
	  if($numcount)
	  {
	  ?>
	  <a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>','<?=$status?>')" class="deletelist">Delete</a>
	  <?
	  }
	  ?>
	  </td>
	   <td  align="right" valign="middle"  colspan="<?=round($colspan/2)?>">
	</td>
    </tr>
      </table>
	  </div>
	  </td>
    </tr>
	<tr>
	 <td class="listing_bottom_paging" align="right" valign="middle"  colspan="1">
	  <?
	  if($numcount)
	  {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?></td>
	  </tr>
  </table>
</form>
