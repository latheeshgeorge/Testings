<?php
	/*#################################################################
	# Script Name 	: list_prod_labels.php
	# Description 	: Page for listing Product labels
	# Coded by 		: ANU
	# Created on	: 28-June-2007
	# Modified by	: 
	# Modified On	:
	#################################################################*/
//Define constants for this page
$table_name='product_site_labels';
$page_type='Product Labels';
$help_msg = get_help_messages('LIST_PROD_LAB_MESS1');
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistProductLabels,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistProductLabels,\'checkbox[]\')"/>','Slno.','Label Name','Label Group','In search','Is Text box','Hidden');
$header_positions=array('left','left','left','left','left','left','left');
$colspan = count($table_headers);

// Check whether product label groups exists in current website. If not exists, then show the msg and link to go to add label group page
$sql_check = "SELECT group_id 
				FROM 
					product_labels_group 
				WHERE 
					sites_site_id = $ecom_siteid 
				LIMIT 
					1";
$ret_check = $db->query($sql_check);
if($db->num_rows($ret_check)==0)
{
?>
	<br />
	<span class='errormsg'> Product Labels Groups not added yet. Please add Product Label Group First.</span><br />
	<br /><br />
	<a class="smalllink" href="home.php?request=prod_label_groups">Go to the Product Label Group Menu Listing page</a><br />
	<br />
	<a class="smalllink" href="home.php?request=prod_label_groups&fpurpose=add">Go to Add Product Label Group Page</a>
<?php
	exit;
}

//#Search terms
$search_fields = array('label_name','search_labelgroup');
foreach($search_fields as $v) {
	$query_string .= "$v=${$v}&";#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'label_name':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('label_name' => 'Label Name');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
if($_REQUEST['search_name']) {
	$where_conditions .= "AND ( label_name LIKE '%".add_slash($_REQUEST['search_name'])."%')";
}

if($_REQUEST['search_labelgroup'])
{
	$map_arr = array(-1);
	$sql_map = "SELECT product_site_labels_label_id  
					FROM 
						product_labels_group_label_map 
					WHERE 
						product_labels_group_group_id = ".$_REQUEST['search_labelgroup'];
	$ret_map = $db->query($sql_map);						
	if($db->num_rows($ret_map))
	{
		while ($row_map = $db->fetch_array($ret_map))
		{
			$map_arr[] = $row_map['product_site_labels_label_id'];
		}
	}
	$where_conditions .= "AND ( label_id IN (".implode(',',$map_arr).") )";
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
$query_string .= "request=prod_labels&sort_by=$sort_by&sort_order=$sort_order&records_per_page=$records_per_page&search_name=".$_REQUEST['search_name']."&search_labelgroup=".$_REQUEST['search_labelgroup']."&start=$start";
?>
<script language="javascript">
function call_ajax_changestatus(search_name,search_group,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var curid				= 0;
	var label_ids 			= '';
	var cat_orders			= '';
	var ch_status			= document.frmlistProductLabels.cbo_changehide.value;
	var qrystr				= 'search_name='+search_name+'&search_labelgroup='+search_group+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&ch_status='+ch_status+'&start='+start+'&pg='+pg;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlistProductLabels.elements.length;i++)
	{
		if (document.frmlistProductLabels.elements[i].type =='checkbox' && document.frmlistProductLabels.elements[i].name=='checkbox[]')
		{

			if (document.frmlistProductLabels.elements[i].checked==true)
			{
				atleastone = 1;
				if (label_ids!='')
					label_ids += '~';
				 label_ids += document.frmlistProductLabels.elements[i].value;
			}	
		}
	}
	if (atleastone==0) 
	{
		alert('Please select the Product Label to change the hide status');
	}
	else
	{
		if(confirm('Change Hide Status of Seleted Product Labels(s)?'))
		{
				show_processing();
				Handlewith_Ajax('services/product_labels.php','fpurpose=change_hide&'+qrystr+'&label_ids='+label_ids);
		}	
	}	
}
function checkSelected()
{
	len=document.frmlistProductLabels.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistProductLabels.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
				user_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one Product Label ');
		return false;
	}
	show_processing();
	return true;
}
function edit_selected()
{
	
	len=document.frmlistProductLabels.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistProductLabels.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
				vendor_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one Label ');
	}
	else if(cnt>1 ){
		alert('Please select only one Label to edit');
	}
	else
	{
		show_processing();
		document.frmlistProductLabels.fpurpose.value='edit';
		document.frmlistProductLabels.submit();
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
function call_ajax_delete(search_name,search_group,sortby,sortorder,recs,start,pg)
{
	var atleastone 	= 0;
	var del_ids 	= '';
	var qrystr				= 'search_name='+search_name+'&search_labelgroup='+search_group+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlistProductLabels.elements.length;i++)
	{
		if (document.frmlistProductLabels.elements[i].type =='checkbox' && document.frmlistProductLabels.elements[i].name=='checkbox[]')
		{

			if (document.frmlistProductLabels.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmlistProductLabels.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select product labels to delete');
	}
	else
	{
		if(confirm('Are you sure you want to delete selected Product label?'))
		{
			show_processing();
			Handlewith_Ajax('services/product_labels.php','fpurpose=delete&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
</script>
<form name="frmlistProductLabels" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="request" value="prod_labels" />
<input type="hidden" name="start" value="<?=$start?>" />
<input type="hidden" name="pg" value="<?=$pg?>" />
  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td align="left" valign="middle" class="treemenutd" colspan="3"><div class="treemenutd_div"><span> List Product Label</span></div></td>
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
      <td height="48" class="sorttd" colspan="3">
	  <div class="sorttd_div">
      	<table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="7%" height="30" align="left" valign="middle">Label Name </td>
          <td width="15%" height="30" align="left" valign="middle"><input name="search_name" type="text" class="textfeild" id="search_name" value="<?=$_REQUEST['search_name']?>"  />		   </td>
          <td width="9%" align="left" valign="middle">In Label Group</td>
          <td width="12%" align="left" valign="middle"><?php
		  	$group_arr = array(0=>'Any');
			$sql_group = "SELECT group_id,group_name 
							FROM 
								product_labels_group 
							WHERE 	
								sites_site_id = $ecom_siteid 
							ORDER BY 
								group_name";
			$ret_group = $db->query($sql_group);
			if($db->num_rows($ret_group))
			{
				while ($row_group = $db->fetch_array($ret_group))
				{
					$group_arr[$row_group['group_id']] = stripslashes($row_group['group_name']);
				}
			}
			echo generateselectbox('search_labelgroup',$group_arr,$_REQUEST['search_labelgroup'],'','');				
		  ?></td>
          <td width="12%" height="30" align="left" valign="middle">Records Per Page </td>
          <td width="6%" height="30" align="left" valign="middle"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/></td>
          <td width="6%" height="30" align="left" valign="middle">Sort By</td>
          <td width="19%" height="30" align="left" valign="middle"><?=$sort_option_txt?>&nbsp;&nbsp;in&nbsp;&nbsp; <?=$sort_by_txt?></td>
          <td width="14%" height="30" align="right" valign="middle"><input name="button5" type="submit" class="red" id="button5" value="Go" />
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_LAB_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
      </table>
      </div></td>
    </tr>
    
    <tr>
      <td class="listingarea" colspan="3">
	  <div class="listingarea_div">
      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable">
	  <tr>
      <td class="listeditd" align="left" valign="middle" colspan="4"><a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=prod_labels&fpurpose=add&records_per_page=<?=$records_per_page?>&search_name=<?=$_REQUEST['search_name']?>&search_labelgroup=<?=$_REQUEST['search_labelgroup']?>&start=<?=$start?>&pg=<?=$_REQUEST['pg']?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>" class="addlist" onclick="show_processing()">Add</a> 
	  <?
	  if($numcount)
	  {
	  ?>
	  <a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<?=$_REQUEST['search_labelgroup']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
	  <?
	  }
	  ?>
	  </td>
	   <td class="listeditd" align="right" valign="middle" colspan="3">
	  <?
	  if($numcount)
	  {
	  ?>
        Change Status
          <select name="cbo_changehide" class="dropdown" id="cbo_changehide">
            <option value="1">Yes</option>
			<option value="0">No</option>
          </select>&nbsp;<input name="change_hide" type="button" class="red" id="change_hide" value="Change" onclick="call_ajax_changestatus('<?php echo $_REQUEST['search_name']?>','<?php echo $_REQUEST['search_labelgroup']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_LAB_CHSTATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
		<?
		}
		?>
   	   </td>
    </tr>
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
	   $sql_vendor = "SELECT label_id,label_name,in_search,is_textbox,label_hide  FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
	   
	   $res = $db->query($sql_vendor); 
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
          <td align="left" valign="middle" class="<?=$class_val;?>" width="5%" ><input name="checkbox[]" value="<? echo $row['label_id']?>" type="checkbox"></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>" width="10%"><?php echo $srno++?></td>
          <td align="left" valign="middle" class="<?=$class_val;?>" width="30%"><a href="home.php?request=prod_labels&fpurpose=edit&checkbox[0]=<?php echo $row['label_id']?>&search_name=<?php echo $_REQUEST['search_name']?>&search_labelgroup=<?=$_REQUEST['search_labelgroup']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_order=<?=$_REQUEST['sort_order']?>&sort_by=<?=$_REQUEST['sort_by']?>" title="<? echo $row['vendor_name']?>" class="edittextlink" onclick="show_processing()"><? echo $row['label_name']?></a></td>
		   <td align="left" valign="middle" class="<?=$class_val;?>">
		   <?php 
		   // Get the name of the label group to which current label is mapped with
		   $sql_map = "SELECT a.group_name,a.group_id  
		   				FROM 
							product_labels_group a, product_labels_group_label_map b 
						WHERE 
							a.group_id = b.product_labels_group_group_id 
							AND b.product_site_labels_label_id = ".$row['label_id']." 
						LIMIT 
							1";
			$ret_map = $db->query($sql_map);
			if($db->num_rows($ret_map))
			{
				$row_map = $db->fetch_array($ret_map);
				echo '<a href="home.php?request=prod_label_groups&fpurpose=edit&checkbox[0]='.$row_map['group_id'].'" class="edittextlink" onclick="show_processing()">'.stripslashes($row_map['group_name']).'</a>';
			}
			else
				echo '<span class="redtext">-- Not Mapped --</span>';
		   ?></td>
		   <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo ($row['in_search'] == 1)?'Yes':'No'; ?></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo ($row['is_textbox'] == 1)?'Yes':'No'; ?></td>

		  <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo ($row['label_hide'] == 1)?'Yes':'No'; ?></td>
	    
        </tr>
      <?
	  }
	  }
	  else
	  {
	  ?>
	  <tr> <td align="center" colspan="7" valign="middle" class="norecordredtext" >	No Product Labels found. </td></tr>
		<?
		}
		?>
		<tr>
      <td class="listeditd" align="left" valign="middle" colspan="4"><a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=prod_labels&fpurpose=add&records_per_page=<?=$records_per_page?>&search_name=<?=$_REQUEST['search_name']?>&search_labelgroup=<?=$_REQUEST['search_labelgroup']?>&start=<?=$start?>&pg=<?=$_REQUEST['pg']?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>" class="addlist" onclick="show_processing()">Add</a> 
	  <?
	  if($numcount)
	  {
	  ?>
	  <a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
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
	  ?>
	  </td>
	</tr>
  </table>
</form>
