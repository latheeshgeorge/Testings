<?php
	/*#################################################################
	# Script Name 	: list_comptype.php
	# Description 	: Page for listing Site Shelf
	# Coded by 		: LSH
	# Created on	: 03-Nov-2007
	#################################################################*/
//Define constants for this page
$table_name='general_settings_sites_customer_company_types';
$page_type='Company Types';
$help_msg = get_help_messages('LIST_COMPANY_TYPES_MESS1');
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistcomptype,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistcomptype,\'checkbox[]\')"/>','Slno.','Type Name','Order','Hidden'	);
$header_positions=array('left','left','left','left','left');
$colspan = count($table_headers);

//#Search terms
$search_fields = array('comptype_name');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'comptype_name':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('comptype_name' => 'Type Name', 'comptype_order' => 'Order');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
if($_REQUEST['search_name']) {
	$where_conditions .= "AND ( comptype_name LIKE '%".add_slash($_REQUEST['search_name'])."%')";
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
$query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=general_settings_comptype&records_per_page=$records_per_page&search_name=".$_REQUEST['search_name']."&start=$start";
?>
<script language="javascript">
function call_ajax_changestatus(search_name,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var curid				= 0;
	var type_ids 			= '';
	var cat_orders			= '';
	var ch_status			= document.frmlistcomptype.cbo_changehide.value;
	var qrystr				= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&ch_status='+ch_status+'&start='+start+'&pg='+pg;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlistcomptype.elements.length;i++)
	{
		if (document.frmlistcomptype.elements[i].type =='checkbox' && document.frmlistcomptype.elements[i].name=='checkbox[]')
		{

			if (document.frmlistcomptype.elements[i].checked==true)
			{
				atleastone = 1;
				if (type_ids!='')
					type_ids += '~';
				 type_ids += document.frmlistcomptype.elements[i].value;
			}	
		}
	}
	if (atleastone==0) 
	{
		alert('Please select the Company Types to change the hide status');
	}
	else
	{
		if(confirm('Change Hide Status of Seleted Company Type(s)?'))
		{
				show_processing();
				Handlewith_Ajax('services/comptype.php','fpurpose=change_hide&'+qrystr+'&type_ids='+type_ids);
		}	
	}	
}

function edit_selected()
{
	
	len=document.frmlistcomptype.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistcomptype.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
				user_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one Company type ');
	}
	else if(cnt>1 ){
		alert('Please select only one Company type to edit');
	}
	else
	{
		show_processing();
		document.frmlistcomptype.fpurpose.value='edit';
		document.frmlistcomptype.submit();
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
	for(i=0;i<document.frmlistcomptype.elements.length;i++)
	{
		if (document.frmlistcomptype.elements[i].type =='checkbox' && document.frmlistcomptype.elements[i].name=='checkbox[]')
		{

			if (document.frmlistcomptype.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmlistcomptype.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select Company Type  to delete');
	}
	else
	{
		if(confirm('Are you sure you want to delete selected Company Type?'))
		{
			show_processing();
			Handlewith_Ajax('services/comptype.php','fpurpose=delete&del_ids='+del_ids+'&'+qrystr);
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
	for(i=0;i<document.frmlistcomptype.elements.length;i++)
	{
		if (document.frmlistcomptype.elements[i].type =='text' && document.frmlistcomptype.elements[i].name!='records_per_page' && document.frmlistcomptype.elements[i].name!='search_name')
		{
			
			index=document.frmlistcomptype.elements[i].name;
			val=document.frmlistcomptype.elements[i].value;
			IdArr[j]=index;
			OrderArr[j]=val;
			j=j+1;
		}
	}
	
	
	var Idstr=IdArr.join('~');
	var OrderStr=OrderArr.join('~');
	
	if(confirm('Save Sort Order Of Company Types?'))
		{
				show_processing();
				Handlewith_Ajax('services/comptype.php','fpurpose=save_comptype_order&'+qrystr+'&Idstr='+Idstr+'&OrderStr='+OrderStr);
		}

}
</script>
<form name="frmlistcomptype" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="request" value="general_settings_comptype" />
<input type="hidden" name="start" value="<?=$start?>" />
<input type="hidden" name="pg" value="<?=$pg?>" />
  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td colspan="3" align="left" valign="middle" class="treemenutd">
		  <div class="treemenutd_div">
		  <a href="home.php?request=general_settings">General Settings</a> 
	  <span> List Company Types </span></div></td>
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
		 <tr> 
		  <td class="sorttd" colspan="3"  align="right">
	  <?
	  if($numcount)
	  {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?></td> 
	  </tr>
    <tr>
      <td height="48" class="sorttd" colspan="3" >
		  		<div class="sorttd_div">

      	<table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td align="left" valign="middle">Type Name </td>
          <td  align="left" valign="middle"><input name="search_name" type="text" class="textfeild" id="search_name" value="<?=$_REQUEST['search_name']?>"  /></td>
          <td align="left">Show</td>
          <td  align="left"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/>
            <?=$page_type?> Per Page</td>        
          <td align="left">Sort By</td>
          <td align="left" nowrap="nowrap"><?=$sort_option_txt?> in <?=$sort_by_txt?>  </td>
          <td align="right">&nbsp;&nbsp;&nbsp;&nbsp;
            <input name="button5" type="submit" class="red" id="button5" value="Go" /> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_COMPANY_TYPES_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
      </table>
      </div>
	  </td>
    </tr>
     <tr>
      <td height="48" class="sorttd" colspan="3" >
		  		<div class="listingarea_div">
					  <table width="100%" border="0" cellpadding="0" cellspacing="0" >

    <tr>
      <td class="listeditd"><a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=general_settings_comptype&fpurpose=add&records_per_page=<?=$records_per_page?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$start?>&pg=<?=$pg?>" class="addlist" onclick="show_processing()">Add</a> 
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
        <input name="save_order" type="button" class="red" id="save_order" value="Save Order" onclick="call_ajax_saveorder('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" />&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_COMPANY_TYPES_SAVE_ORDER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;
		Change Status
          <select name="cbo_changehide" class="dropdown" id="cbo_changehide">
            <option value="1">Yes</option>
			<option value="0">No</option>
          </select>&nbsp;<input name="change_hide" type="button" class="red" id="change_hide" value="Change" onclick="call_ajax_changestatus('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_COMPANY_TYPES_SAVE_CHANGE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
		<?
		}
		?>
   	   </td>
    </tr>
     
    <tr>
      <td colspan="3" class="listingarea">
      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable">
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
	   $sql_comptype = "SELECT comptype_id,comptype_name,comptype_order,comptype_hide FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
	   
	   $res = $db->query($sql_comptype);
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
          <td align="left" valign="middle" class="<?=$class_val;?>"  width="6%"><input name="checkbox[]" value="<? echo $row['comptype_id']?>" type="checkbox"></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"  width="2%"><?php echo $srno++?></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><a href="home.php?request=general_settings_comptype&fpurpose=edit&checkbox[0]=<?php echo $row['comptype_id']?>&search_name=<?php echo $_REQUEST['search_name']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>"  class="edittextlink" onclick="show_processing()"><? echo $row['comptype_name']?></a></td>
		 <td align="left" valign="middle" class="<?=$class_val;?>"><input type="text" name="<? echo $row['comptype_id']?>" value="<? echo $row['comptype_order']?>" size="3" /></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo ($row['comptype_hide'] == 1)?'Yes':'No'; ?></td>
         
        </tr>
      <?
	  }
	  }
	  else
	  {
	  ?>
	  <tr>
				  <td align="center" valign="middle" class="norecordredtext" colspan="<?=$colspan?>" >
				  	No Company Types exists.				  </td>
			</tr>
		<?
		}
		?>	
      </table></td>
    </tr>
	
  <tr>
      <td class="listeditd"><a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=general_settings_comptype&fpurpose=add&records_per_page=<?=$records_per_page?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$start?>&pg=<?=$pg?>" class="addlist" onclick="show_processing()">Add</a> 
	  <?
	  if($numcount)
	  {
	  ?>
	  <a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
	  <?
	  }
	  ?>
	  </td>
	   <td class="listeditd" width="162"  align="right" colspan="2">
	 </td>
	   
    </tr>
    </table>
    </div>
    </td>
    </tr>
	 <tr>
     
	   <td class="listing_bottom_paging" width="162"  align="right" colspan="2">
	  <?
	  if($numcount)
	  {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?></td>
	   
    </tr>
  </table>
</form>
