<?php
	/*#################################################################
	# Script Name 	: list_group.php
	# Description 	: Page for listing Static Page Groups
	# Coded by 		: SKR
	# Created on	: 26-June-2007
	# Modified by	: SKR
	# Modified On	: 27-June-2007
	#################################################################*/
//Define constants for this page
$table_name='static_pagegroup';
$page_type='Static Page Menu';
$help_msg = get_help_messages('LIST_STAT_PAGE_GROUP_MESS1');
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistStaticGroup,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistStaticGroup,\'checkbox[]\')"/>','Slno.','Page Menu Name','Layout (position)(order)','Hidden','Hide Menu Name'); //'Menu Order',
$header_positions=array('left','left','left','left','center','center','center'); //'left',
$colspan = count($table_headers);

//#Search terms
$search_fields = array('group_name');
foreach($search_fields as $v) {
	$query_string .= "$v=${$v}&";#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'group_name':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('group_name' => 'Menu Name','group_order' => 'Menu Order');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
if($_REQUEST['search_name']) {
	$where_conditions .= "AND ( group_name LIKE '%".add_slash($_REQUEST['search_name'])."%')";
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
$query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=stat_group&records_per_page=$records_per_page&search_name=".$_REQUEST['search_name']."&start=$start";
?>
<script language="javascript">
function call_ajax_saveorder(search_name,sortby,sortorder,recs,start,pg)
{
	var qrystr= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;
	
	var IdArr=new Array();
	var OrderArr=new Array();
	var index;
	var val;
	var j=0;
	for(i=0;i<document.frmlistStaticGroup.elements.length;i++)
	{
		if (document.frmlistStaticGroup.elements[i].type =='text' && document.frmlistStaticGroup.elements[i].name!='search_name' && document.frmlistStaticGroup.elements[i].name!='records_per_page')
		{
			
			index=document.frmlistStaticGroup.elements[i].name;
			val=document.frmlistStaticGroup.elements[i].value;
			IdArr[j]=index;
			OrderArr[j]=val;
			j=j+1;
		}
	}
	
	
	var Idstr=IdArr.join('~');
	var OrderStr=OrderArr.join('~');
	
	if(confirm('Save Sort Order Of Static Page Menus?'))
		{
				show_processing();
				Handlewith_Ajax('services/static_group.php','fpurpose=save_order&'+qrystr+'&Idstr='+Idstr+'&OrderStr='+OrderStr);
		}

}
function call_ajax_changestatus(search_name,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var curid				= 0;
	var group_ids 			= '';
	var cat_orders			= '';
	var ch_status			= document.frmlistStaticGroup.cbo_changehide.value;
	var qrystr				= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&ch_status='+ch_status+'&start='+start+'&pg='+pg;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlistStaticGroup.elements.length;i++)
	{
		if (document.frmlistStaticGroup.elements[i].type =='checkbox' && document.frmlistStaticGroup.elements[i].name=='checkbox[]')
		{

			if (document.frmlistStaticGroup.elements[i].checked==true)
			{
				atleastone = 1;
				if (group_ids!='')
					group_ids += '~';
				 group_ids += document.frmlistStaticGroup.elements[i].value;
			}	
		}
	}
	if (atleastone==0) 
	{
		alert('Please select the Menus to change the status');
	}
	else
	{
		if(confirm('Change Status of Seleted Menu(s)?'))
		{
				show_processing();
				Handlewith_Ajax('services/static_group.php','fpurpose=change_hide&'+qrystr+'&group_ids='+group_ids);
		}	
	}	
}
function checkSelected()
{
	len=document.frmlistStaticGroup.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistStaticGroup.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
				user_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one Menu ');
		return false;
	}
	show_processing();
	return true;
}
function edit_selected()
{
	
	len=document.frmlistStaticGroup.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistStaticGroup.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
				user_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one Menu ');
	}
	else if(cnt>1 ){
		alert('Please select only one Menu to edit');
	}
	else
	{
		show_processing();
		document.frmlistStaticGroup.fpurpose.value='edit';
		document.frmlistStaticGroup.submit();
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
	for(i=0;i<document.frmlistStaticGroup.elements.length;i++)
	{
		if (document.frmlistStaticGroup.elements[i].type =='checkbox' && document.frmlistStaticGroup.elements[i].name=='checkbox[]')
		{

			if (document.frmlistStaticGroup.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmlistStaticGroup.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select Menu to delete');
	}
	else
	{
		if(confirm('Are you sure you want to delete selected Menu?'))
		{
			show_processing();
			Handlewith_Ajax('services/static_group.php','fpurpose=delete&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
</script>
<form name="frmlistStaticGroup" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="request" value="stat_group" />
<input type="hidden" name="start" value="<?=$start?>" />
<input type="hidden" name="pg" value="<?=$pg?>" />
<input type="hidden" name="sort_by" id="pass_sort_by" value="<?=$_REQUEST['sort_by']?>" />
<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
<input  type="hidden" name="pass_group_name" id="pass_group_name" value="<?=$_REQUEST['search_name']?>"  />
  <table border="0" cellpadding="0" cellspacing="0" class="maintable" width="100%">
    <tr>
      <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span> List Static Page Menu</span></div></td>
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
          <td width="9%" height="30" align="left" valign="middle">Menu Name </td>
          <td width="19%" height="30" align="left" valign="middle"><input name="search_name" type="text" class="textfeild" id="search_name" value="<?=$_REQUEST['search_name']?>"  /></td>
          <td width="10%" height="30" align="left" valign="middle">Records Per Page</td>
          <td width="14%" height="30" align="left" valign="middle"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/></td>
          <td width="5%" height="30" align="left" valign="middle">Sort By</td>
          <td width="31%" height="30" align="left" valign="middle"><?=$sort_option_txt?>&nbsp;&nbsp;&nbsp;in&nbsp;&nbsp;&nbsp; <?=$sort_by_txt?></td>
          <td width="12%" height="30" align="right" valign="middle">&nbsp;&nbsp;
            <input name="button5" type="submit" class="red" id="button5" value="Go" />
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_STAT_PAGE_GROUP_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
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
      <td class="listeditd" colspan="3"><a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=stat_group&fpurpose=add&records_per_page=<?=$records_per_page?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$start?>&pg=<?=$pg?>" class="addlist" onclick="show_processing()">Add</a> 
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
	 <!--  <input name="save_order" type="button" class="red" id="save_order" value="Save Order" onclick="call_ajax_saveorder('<?php// echo $_REQUEST['search_name']?>','<?php// //echo $sort_by?>','<?php// //echo $sort_order?>','<?php// echo $records_per_page?>','<?php// echo $start?>','<?php// echo $pg?>')" />
	    <a href="#" onmouseover ="ddrivetip('<?//=get_help_messages('LIST_STAT_PAGE_GROUP_SAVE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> -->&nbsp;&nbsp;
	  <?
	  if($numcount)
	  {
	  ?>
        Change Status
          <select name="cbo_changehide" class="dropdown" id="cbo_changehide">
            <option value="1">Yes</option>
			<option value="0">No</option>
          </select>&nbsp;<input name="change_hide" type="button" class="red" id="change_hide" value="Change" onclick="call_ajax_changestatus('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_STAT_PAGE_GROUP_CHSTATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
		<?
		}
		?>
   	   </td>
    </tr>
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
	   $sql_group = "SELECT group_id,group_name,group_order,group_hide,group_hidename FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
	   
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
          <td align="left" valign="middle" class="<?=$class_val;?>"  width="5%"><input name="checkbox[]" value="<? echo $row['group_id']?>" type="checkbox"></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"  width="2%"><?php echo $srno++?></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><a href="home.php?request=stat_group&fpurpose=edit&checkbox[0]=<?php echo $row['group_id']?>&pass_group_name=<?php echo $_REQUEST['search_name']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>" title="<? echo $row['group_name']?>" class="edittextlink" onclick="show_processing()"><? echo $row['group_name']?></a></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>">
		  <?php
				  		// Find the feature_id for mod_productcatgroup module from features table
						$sql_feat = "SELECT feature_id FROM features WHERE feature_modulename='mod_staticgroup'";
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
									display_component_id = ".$row['group_id'] . " AND features_feature_id=".$feat_id."";
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
		   <!--<td align="left" valign="middle" class="<?//=$class_val;?>"><input type="text" name="<?// echo $row['group_id']?>" value="<?// echo $row['group_order']?>" size="2" /></td> -->
        
          <td align="center" valign="middle" class="<?=$class_val;?>"><?php echo ($row['group_hide'] == 1)?'Yes':'No'; ?></td>
		  <td align="center" valign="middle" class="<?=$class_val;?>"><?php echo ($row['group_hidename'] == 1)?'Yes':'No'; ?></td>
         
        </tr>
      <?
	  }
	  }
	  else
	  {
	  ?>
	  <tr>
				  <td align="center" valign="middle" class="norecordredtext" colspan="<?=$colspan?>" >
				  	No Static Page Menu exists.				  </td>
			</tr>
		<?
		}
		?>
		<tr>
        <td class="listeditd" colspan="3"><a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=stat_group&fpurpose=add&records_per_page=<?=$records_per_page?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$start?>&pg=<?=$pg?>" class="addlist" onclick="show_processing()">Add</a> 

<!--      <td class="listeditd"><a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=general_settings_country&fpurpose=add&records_per_page=<?=$records_per_page?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$start?>&pg=<?=$pg?>" class="addlist" onclick="show_processing()">Add</a> 
-->	  <?
	  if($numcount)
	  {
	  ?>
	  <a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
	  <?
	  }
	  ?>
	  </td>
	   <td class="listeditd" align="right" colspan="2">
	 </td>
    </tr>
      </table>
	  </div></td>
    </tr>
	<tr>
       
	   <td class="listing_bottom_paging" width="162"  align="right" colspan="3">
	  <?
	  if($numcount)
	  {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?></td>
    </tr>
    </table>
</form>
