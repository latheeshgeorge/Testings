<?php
	/*#################################################################
	# Script Name 	: list_settings_options.php
	# Description 	: Page for listing Site general settings Captions
	# Coded by 		: ANU
	# Created on	: 12-June-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
//Define constants for this page
$table_name='general_settings_section as gss,general_settings_site_captions as gssc';
$page_type='Captions';
$help_msg =get_help_messages('LIST_CAPTIONS_MESS1');
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistSettingsOptions,\'general_id[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistSettingsOptions,\'general_id[]\')"/>','Slno.','Caption','Settings Key','Section');
$header_positions=array('left','left','left','left','left');
$colspan = count($table_headers);

//#Search terms
$search_fields = array('site_captions','settings_section');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing search terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'general_text':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('general_text' => 'Caption Text','general_key' => 'Caption Key','section_name' => 'Section Name');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid and gss.section_id = gssc.general_settings_section_section_id ";
if($_REQUEST['site_captions']) {
	$where_conditions .= "AND  ( general_text LIKE '%".add_slash($_REQUEST['site_captions'])."%' || general_key LIKE '%".add_slash($_REQUEST['site_captions'])."%'  )";
}
if($_REQUEST['settings_section']){
	$where_conditions .= "AND  gssc.general_settings_section_section_id =".add_slash($_REQUEST['settings_section'])." ";
}
// To get all the general settings sections in the site
$sql_settings_sections = "SELECT section_id,section_name FROM general_settings_section ORDER BY section_name ";
$res_settings_sections = $db->query($sql_settings_sections);
$section_name = array();
$section_name[0] = 'All';
while ($captions = $db->fetch_array($res_settings_sections)){
 $section_name[$captions['section_id']] = $captions['section_name'];
}
$selected_section = (!$_REQUEST['settings_section'])?0:$_REQUEST['settings_section'];

//#Select condition for getting total count
$sql_count = "SELECT count(*) as cnt FROM $table_name  $where_conditions";
$res_count = $db->query($sql_count);
list($numcount) = $db->fetch_array($res_count);#Getting total count of records
/////////////////////////////////For paging///////////////////////////////////////////
/*$records_per_page = (is_numeric($_REQUEST['records_per_page']))?$_REQUEST['records_per_page']:10;#Total records shown in a page
          


$start 		= (!isset($_REQUEST['start']))?0:$_REQUEST['start'];// This variable is set to zero for the first page
$p_f 		= (!isset($_REQUEST['p_f']))?0:$_REQUEST['p_f']; // This variable is set to zero for the first page
$limit 		= $records_per_page;   	// No of records to be shown per page.
$page_limit	= 15;	
$totcount	= $numcount;	// total number of records */
/////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////For paging///////////////////////////////////////////
$records_per_page = (is_numeric($_REQUEST['records_per_page']) && ($_REQUEST['records_per_page']) )?$_REQUEST['records_per_page']:10;#Total records shown in a page
$pg= ($_REQUEST['search_click']==1)?1:$_REQUEST['pg'];

if (!($pg > 0) || $pg == 0) { $pg = 1; }
$start = ($pg - 1) * $records_per_page;#Starting record.
$pages = ceil($numcount / $records_per_page);#Getting the total pages
if($pg > $pages) {
	$pg = $pages;
}
$count_no = ($pg == 1)?0:$records_per_page*($pg-1);
/////////////////////////////////////////////////////////////////////////////////////
//$query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=general_settings&fpurpose=captions&records_per_page=$records_per_page&start=$start";
$query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=general_settings&fpurpose=captions&records_per_page=$records_per_page&site_captions=".$_REQUEST['site_captions']."&start=$start";
/////// end paging/////////
/*$records_per_page = (is_numeric($_REQUEST['records_per_page']))?$_REQUEST['records_per_page']:10;#Total records shown in a page
$pg= $_REQUEST['pg'];

if (!($pg > 0) || $pg == 0) { $pg = 1; }
$startrec = ($pg - 1) * $records_per_page;#Starting record.
$pages = ceil($numcount / $records_per_page);#Getting the total pages
if($pg > $pages) {
	$pg = $pages;
}
$count_no = ($pg == 1)?0:$records_per_page*($pg-1);*/


?>
<script type="text/javascript">
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
function edit_selected(mode)
{
	
	len=document.frmlistSettingsOptions.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistSettingsOptions.elements[j]
		if (el!=null && el.name== "general_id[]" )
		   if(el.checked) {
		   		cnt++;
				general_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one Caption ');
		return false;
		
	}
	else if(cnt>1 ){
		alert('Please select only one Caption to edit');
		return false;
	}
	else
	{
		document.frmlistSettingsOptions.fpurpose.value=mode;
		document.frmlistSettingsOptions.submit();
	}
	
	
}

function getSelected(purpose){
		document.frmlistSettingsOptions.fpurpose.value=purpose;
		document.frmlistSettingsOptions.submit();
}
function call_ajax_delete(settings_section,site_captions,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var del_ids 			= '';
	var qrystr				= 'settings_section='+settings_section+'&site_captions='+site_captions+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlistSettingsOptions.elements.length;i++)
	{
		if (document.frmlistSettingsOptions.elements[i].type =='checkbox' && document.frmlistSettingsOptions.elements[i].name=='general_id[]')
		{

			if (document.frmlistSettingsOptions.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmlistSettingsOptions.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select the Captions to delete');
	}
	else
	{
		if(confirm('Are you sure you want to delete selected Captions?'))
		{
			show_processing();
			Handlewith_Ajax('services/general_settings.php','fpurpose=captions_delete&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}

</script>
<form name="frmlistSettingsOptions" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="request" value="general_settings" />
<input type="hidden" name="start" value="<?=$start?>" />
<input type="hidden" name="pg" value="<?=$pg?>" />
<input type="hidden" name="search_click" value="" />


  <table border="0" cellpadding="0" cellspacing="0" class="maintable" width="100%">
    <tr>
      <td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span> List Settings Captions</span></div></td>
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
          			<td colspan="2" align="center" valign="middle" class="errormsg"><?php echo $alert?></td>
          		</tr>
		 <?php
		 	}
		 ?> 
	<?php
		if($numcount > 0)
		{
	?>
    <tr>
		<td colspan="2" align="right" valign="middle" class="sorttd"><?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?></td>
	</tr>
	<?php
		}
	?>
	<tr>
      <td height="48" colspan="2" class="sorttd">
	  <div class="sorttd_div">
      	<table  border="0" cellpadding="0" cellspacing="1" width="100%">
        <tr>
          <td width="15%"  align="left" valign="middle">Caption</td>
          <td width="32%"  align="left" valign="middle"><input name="site_captions" type="text" class="textfeild" id="site_captions" value="<?=$_REQUEST['site_captions']?>" /></td>
          <td width="9%"  align="left" valign="middle">Under Section </td>
          <td width="44%" colspan="2"  align="left" valign="middle"><?=generateselectbox('settings_section',$section_name,$selected_section);?></td>
        </tr>
        <tr>
          <td  align="left" valign="middle">Records Per Page</td>
          <td  align="left" valign="middle"><input name="records_per_page" class="textfeild" type="text"  id="records_per_page" size="2" value="<?=$records_per_page?>"/>
            <?=$page_type?>
Per Page </td>
          <td  align="left" valign="middle">Sort By</td>
          <td  align="left" valign="middle"><?=$sort_option_txt?>
in
  <?=$sort_by_txt?>
  &nbsp;&nbsp;</td>
          <td  align="right" valign="middle"><input name="go_button" type="button" class="red" id="go_button" value="Go" onclick="		document.frmlistSettingsOptions.search_click.value=1;
getSelected('captions')" />
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_CAPTIONS_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
      </table>
      </div></td>
    </tr>
    
 
    <tr>
      <td colspan="2" class="listingarea">
	  <div class="listingarea_div">
      <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="listingtable">
	  <tr>
      <td align="left" valign="middle" colspan="5" class="listeditd"><a href="home.php?request=general_settings&fpurpose=add_captions&site_captions=<?=$_REQUEST['site_captions']?>&settings_section=<?=$settings_section?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>" class="addlist">Add</a> <a href="#" onclick="return edit_selected('edit_captions')" class="editlist"> Edit</a> <a href="#" onclick="call_ajax_delete('<?=$_REQUEST['settings_section']?>','<?php echo $_REQUEST['site_captions']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a></td>
    </tr>
       <?
	   echo table_header($table_headers,$header_positions);
	   $sql_settings_captions = "SELECT gss.section_id,gss.section_name,gssc.general_id,gssc.sites_site_id,gssc.general_key,general_text FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
	  
	   $res = $db->query($sql_settings_captions); 
	   	if ($db->num_rows($res)){
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
          <td width="5%" align="left" valign="middle" class="<?=$class_val;?>"><input name="general_id[]" value="<? echo $row['general_id']?>" type="checkbox"></td>  <td width="2%" align="left" valign="middle"  class="<?=$class_val;?>"><?=$count_no?></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=general_settings&fpurpose=edit_captions&settings_section=<?=$_REQUEST['settings_section']?>&general_id=<?=$row['general_id']?>&site_captions=<?php echo $_REQUEST['site_captions']?>&records_per_page=<?=$records_per_page?>&start=<?=$start?>&pg=<?=$pg?>" class="edittextlink"><? echo $row['general_text']?></a></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><? echo $row['general_key']?></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><? echo $row['section_name']?></td>
        </tr>
      <?
	  }
	}
	 else
		{
	?>	
			<tr>
				  <td align="center" valign="middle" class="norecordredtext" colspan="<?php echo $colspan?>">
				  	No Captions found.				  </td>
			</tr>	  
	<?php
		}
	?>
	<tr>
      <td align="left" valign="middle" colspan="3" class="listeditd"><a href="home.php?request=general_settings&fpurpose=add_captions&site_captions=<?=$_REQUEST['site_captions']?>&settings_section=<?=$settings_section?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>" class="addlist">Add</a> <a href="#" onclick="return edit_selected('edit_captions')" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['settings_section']?>','<?php echo $_REQUEST['site_captions']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a></td>
      <td align="right" valign="middle" colspan="2"  class="listeditd"></td>
    </tr>
      </table>
	  </div>
	  </td>
    </tr>	 
	<tr>
       <td align="right" valign="middle" colspan="2"  class="listing_bottom_paging"><?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?></td>
    </tr>
    </table>
</form>
<!-- Script for auto complete starts here -->
<script language="javascript">
var $pnc = jQuery.noConflict();
$pnc().ready(function() { 
	auto_search('site_captions','gssc'); 
});
</script>
<!-- Script for auto complete ends here -->