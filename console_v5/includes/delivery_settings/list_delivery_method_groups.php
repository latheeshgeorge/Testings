<?php
	/*#################################################################
	# Script Name 	: list_delivery_method_groups.php
	# Description 	: Page for listing the groups of delivery methods
	# Coded by 		: Sny
	# Created on	: 12-Nov-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
//Define constants for this page
$table_name			= 'general_settings_site_delivery_group';
$page_type			= 'Delivery Method Groups';
$help_msg 			= get_help_messages('LIST_DELIVERY_METHOD_GROUP_MESS1');
$table_headers 		= array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmListDeliveryMethod,\'delgroupid[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmListDeliveryMethod,\'delgroupid[]\')"/>','Slno.','Group Name','Order','Hidden');
$header_positions	= array('left','left','left','left','center');
$colspan 			= count($table_headers);

//#Search terms
$search_fields = array('group_name');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing search terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'delivery_group_name':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('delivery_group_name' => 'Group Name');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
if($_REQUEST['group_name']) {
	$where_conditions .= "AND  delivery_group_name LIKE '%".add_slash($_REQUEST['group_name'])."%' ";
}

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
//$query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=delivery_settings&fpurpose=captions&records_per_page=$records_per_page&start=$start";
$query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=delivery_settings&fpurpose=list_delmethod_groups&records_per_page=$records_per_page&group_name=".$_REQUEST['group_name']."&start=$start";
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
	
	len=document.frmListDeliveryMethod.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmListDeliveryMethod.elements[j]
		if (el!=null && el.name== "delgroupid[]" )
		   if(el.checked) {
		   		cnt++;
				delgroupid=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one Group ');
		return false;
		
	}
	else if(cnt>1 ){
		alert('Please select only one Group to edit');
		return false;
	}
	else
	{
		document.frmListDeliveryMethod.fpurpose.value=mode;
		document.frmListDeliveryMethod.submit();
	}
	
	
}

function getSelected(purpose){
		document.frmListDeliveryMethod.fpurpose.value=purpose;
		document.frmListDeliveryMethod.submit();
}
function call_changestatus()
{
	var atleastone 			= 0;
	var curid				= 0;
	var group_ids 			= '';
	var cat_orders			= '';
	var ch_status			= document.frmListDeliveryMethod.change_status.value;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmListDeliveryMethod.elements.length;i++)
	{
		if (document.frmListDeliveryMethod.elements[i].type =='checkbox' && document.frmListDeliveryMethod.elements[i].name=='delgroupid[]')
		{

			if (document.frmListDeliveryMethod.elements[i].checked==true)
			{
				atleastone = 1;
				if (group_ids!='')
					group_ids += '~';
				 group_ids += document.frmListDeliveryMethod.elements[i].value;
			}	
		}
	}
	if (atleastone==0) 
	{
		alert('Please select the groups to change the hide status');
	}
	else
	{
		if(confirm('Change Hide Status of Seleted Group(s)?'))
		{
				show_processing();
				document.frmListDeliveryMethod.fpurpose.value 	= 'change_groupstatus';
				document.frmListDeliveryMethod.group_ids.value 	= group_ids;
				document.frmListDeliveryMethod.submit();
		}	
	}	
}
function call_ajax_saveorder(search_name,sortby,sortorder,recs,start,pg)
{
	var qrystr= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;
	
	var IdArr=new Array();
	var OrderArr=new Array();
	var index;
	var val;
	var j=0;
	for(i=0;i<document.frmListDeliveryMethod.elements.length;i++)
	{
		if (document.frmListDeliveryMethod.elements[i].type =='text' && document.frmListDeliveryMethod.elements[i].name!='search_name' && document.frmListDeliveryMethod.elements[i].name!='records_per_page')
		{
			
			index=document.frmListDeliveryMethod.elements[i].name;
			val=document.frmListDeliveryMethod.elements[i].value;
			IdArr[j]=index;
			OrderArr[j]=val;
			j=j+1;
		}
	}
	
	var Idstr=IdArr.join('~');
	var OrderStr=OrderArr.join('~');
	
	if(confirm('Save Sort Order Of delivery method Groups?'))
		{
				show_processing();
				Handlewith_Ajax('services/delivery_settings.php','fpurpose=change_grouporder&'+qrystr+'&Idstr='+Idstr+'&OrderStr='+OrderStr);
		}

}
function call_delete()
{
	var atleastone 			= 0;
	var del_ids 			= '';
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmListDeliveryMethod.elements.length;i++)
	{
		if (document.frmListDeliveryMethod.elements[i].type =='checkbox' && document.frmListDeliveryMethod.elements[i].name=='delgroupid[]')
		{

			if (document.frmListDeliveryMethod.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmListDeliveryMethod.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select the Groups to delete');
	}
	else
	{
		if(confirm('When a group is deleted all the delivery details related to that group will also be removed.\n\n Are you sure you want to delete selected Group(s)?'))
		{
			show_processing();
			document.frmListDeliveryMethod.fpurpose.value 	= 'delete_groups';
			document.frmListDeliveryMethod.group_ids.value 	= del_ids;
			document.frmListDeliveryMethod.submit();
		}	
	}	
}

</script>
<form name="frmListDeliveryMethod" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="list_delmethod_groups" />
<input type="hidden" name="request" value="delivery_settings" />
<input type="hidden" name="start" value="<?=$start?>" />
<input type="hidden" name="pg" value="<?=$pg?>" />
<input type="hidden" name="group_ids" value="" />
<input type="hidden" name="search_click" value="" />


  <table border="0" cellpadding="0" cellspacing="0" class="maintable" width="100%">
    <tr>
      <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=delivery_settings">Delivery Settings</a> <span> List Delivery Method Groups</span></div></td>
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
          			<td colspan="3" align="center" valign="middle" class="errormsg"><?php echo $alert?></td>
          		</tr>
		 <?php
		 	}
			if($numcount){  
		 ?> 
    <tr>
		<td colspan="3" align="right" valign="middle" class="sorttd">  <?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?></td>
	</tr>
	<?php } ?>
	<tr>
      <td height="48" colspan="3" class="sorttd">
	  <div class="editarea_div">
      	<table  border="0" cellpadding="0" cellspacing="0" class="sorttabletop" width="100%">
        <tr>
          <td width="8%" height="30"  align="left" valign="middle">Group Name Like </td>
          <td width="12%" height="30"  align="left" valign="middle"><input name="group_name" type="text" class="textfeild" id="group_name" value="<?=$_REQUEST['group_name']?>" /></td>
          <td width="8%" height="30"  align="left" valign="middle">Records Per Page </td>
          <td width="4%" height="30"  align="left" valign="middle"><input name="records_per_page" class="textfeild" type="text"  id="records_per_page" size="2" value="<?=$records_per_page?>"/></td>
          <td width="4%" height="30"  align="left" valign="middle">Sort By</td>
          <td width="18%" height="30"  align="left" valign="middle"><?=$sort_option_txt?>&nbsp;&nbsp;&nbsp;in&nbsp;&nbsp;&nbsp;<?=$sort_by_txt?></td>
          <td width="15%" height="30"  align="left" valign="middle"><input name="go_button" type="button" class="red" id="go_button" value="Go" onclick="document.frmListDeliveryMethod.search_click.value=1;getSelected('list_delmethod_groups')" />
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_DELIVERY_METHOD_GROUP_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          <td width="30%" height="30"  align="left" valign="middle">&nbsp;</td>
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
      <td class="listeditd" align="left" valign="middle" colspan="3"><a href="home.php?request=delivery_settings&fpurpose=add_methodgroups&group_name=<?=$_REQUEST['group_name']?>&settings_section=<?=$settings_section?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>" class="addlist">Add</a><? if($numcount){?> <a href="#" onclick="return edit_selected('edit_methodgroups')" class="editlist">Edit</a> <a href="#" onclick="call_delete()" class="deletelist">Delete</a><? }?></td>
      <td class="listeditd" align="right" valign="middle" colspan="2"><? if($numcount){?>ChangeHideStatus <?php echo generateselectbox('change_status',array(0=>'No',1=>'Yes'),$_REQUEST['change_status']);?>&nbsp;&nbsp;		<input name="change_hide" type="button" class="red" id="change_hide" value="Change" onclick="call_changestatus()" />
        <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_DELIVERY_METHOD_GROUP_CHSTATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
      <input name="change_order" type="button" class="red" id="change_order" value="Save order" onclick="call_ajax_saveorder('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" /> <? }?>
	  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_DELIVERY_METHOD_GROUP_SAVEORDER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
    </tr>
        <?
	   echo table_header($table_headers,$header_positions);
	   $sql_settings_captions = "SELECT * FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
	  
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
          <td width="6%" align="left" valign="middle" class="<?=$class_val;?>"><input name="delgroupid[]" value="<? echo $row['delivery_group_id']?>" type="checkbox" /></td>
          <td width="6%" align="left" valign="middle"  class="<?=$class_val;?>"><?=$count_no?></td>
          <td align="left" valign="middle" class="<?=$class_val;?>" width="50%"><a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=delivery_settings&fpurpose=edit_methodgroups&delgroup_id=<?=$row['delivery_group_id']?>&group_name=<?php echo $_REQUEST['group_name']?>&records_per_page=<?=$records_per_page?>&start=<?=$start?>&pg=<?=$pg?>" class="edittextlink"><? echo stripslashes($row['delivery_group_name'])?></a></td>
          <td align="left" valign="middle" class="<?=$class_val;?>" width="12%"><input type="text" size="3" name="<? echo $row['delivery_group_id']?>" value="<? echo $row['delivery_group_order']?>"  />          </td>
          <td width="13%" align="center" valign="middle" class="<?=$class_val;?>"><?php echo ($row['delivery_group_hidden']==1)?'Yes':'No'?></td>
        </tr>
        <?
	  }
	}
	 else
		{
	?>
        <tr>
          <td align="center" valign="middle" class="norecordredtext" colspan="<?php echo $colspan?>"> No Delivery Method Groups found. </td>
        </tr>
        <?php
		}
	?>
	<tr>
      <td   class="listeditd" align="left" valign="middle" colspan="3"><a href="home.php?request=delivery_settings&fpurpose=add_methodgroups&group_name=<?=$_REQUEST['group_name']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>" class="addlist">Add</a><? if($numcount){?> <a href="#" onclick="return edit_selected('edit_methodgroups')" class="editlist">Edit</a> <a href="#" onclick="call_delete()" class="deletelist">Delete</a> <? }?></td>
      <td  align="right" valign="middle" colspan="2" class="listeditd"><?php if($numcount){ paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); }?></td>
    </tr>
      </table>
	  </div></td>
    </tr> 
    </table>
</form>
