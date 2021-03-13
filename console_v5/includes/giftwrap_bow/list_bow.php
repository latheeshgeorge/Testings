<?php
	/*#################################################################
	# Script Name 	: list_bow.php
	# Description 	: Page for listing Giftwrap Bow
	# Coded by 		: SKR
	# Created on	: 21-July-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
//Define constants for this page
$table_name='giftwrap_bows';
$page_type='Bows';
$help_msg = get_help_messages('LIST_GIFTWRAP_BOW_MESS1');
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistBow,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistBow,\'checkbox[]\')"/>','Slno.','Bow Name','Extra Price','Order','Hidden');
$header_positions=array('left','left','left','left','left','left','left');
$colspan = count($table_headers);

//#Search terms
$search_fields = array('bow_name');
foreach($search_fields as $v) {
	$query_string .= "$v=${$v}&";#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'bow_name':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('bow_name' => 'Bow Name');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
if($_REQUEST['search_name']) {
	$where_conditions .= "AND ( bow_name LIKE '%".add_slash($_REQUEST['search_name'])."%')";
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
$query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=giftwrap_bows&records_per_page=$records_per_page&search_name=".$_REQUEST['search_name']."&start=$start";
?>
<script language="javascript">
function call_ajax_changestatus(search_name,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var curid				= 0;
	var bow_ids 			= '';
	var cat_orders			= '';
	var ch_status			= document.frmlistBow.cbo_changehide.value;
	var qrystr				= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&ch_status='+ch_status+'&start='+start+'&pg='+pg;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlistBow.elements.length;i++)
	{
		if (document.frmlistBow.elements[i].type =='checkbox' && document.frmlistBow.elements[i].name=='checkbox[]')
		{

			if (document.frmlistBow.elements[i].checked==true)
			{
				atleastone = 1;
				if (bow_ids!='')
					bow_ids += '~';
				 bow_ids += document.frmlistBow.elements[i].value;
			}	
		}
	}
	if (atleastone==0) 
	{
		alert('Please select the bows to change the hide status');
	}
	else
	{
		if(confirm('Change Hide Status of Seleted Bow(s)?'))
		{
				show_processing();
				Handlewith_Ajax('services/giftwrap_bow.php','fpurpose=change_hide&'+qrystr+'&bow_ids='+bow_ids);
		}	
	}	
}

function edit_selected()
{
	
	len=document.frmlistBow.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistBow.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
				bow_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one Bow ');
	}
	else if(cnt>1 ){
		alert('Please select only one Bow to edit');
	}
	else
	{
		show_processing();
		document.frmlistBow.fpurpose.value='edit';
		document.frmlistBow.submit();
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
	for(i=0;i<document.frmlistBow.elements.length;i++)
	{
		if (document.frmlistBow.elements[i].type =='checkbox' && document.frmlistBow.elements[i].name=='checkbox[]')
		{

			if (document.frmlistBow.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmlistBow.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select bow to delete');
	}
	else
	{
		if(confirm('Are you sure you want to delete selected Bow?'))
		{
			show_processing();
			Handlewith_Ajax('services/giftwrap_bow.php','fpurpose=delete&del_ids='+del_ids+'&'+qrystr);
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
	for(i=0;i<document.frmlistBow.elements.length;i++)
	{
		if (document.frmlistBow.elements[i].type =='text' && document.frmlistBow.elements[i].name!='records_per_page' && document.frmlistBow.elements[i].name!='search_name')
		{
			
			index=document.frmlistBow.elements[i].name;
			val=document.frmlistBow.elements[i].value;
			IdArr[j]=index;
			OrderArr[j]=val;
			j=j+1;
		}
	}
	
	
	var Idstr=IdArr.join('~');
	var OrderStr=OrderArr.join('~');
	
	if(confirm('Save Sort Order Of Bows?'))
		{
				show_processing();
				Handlewith_Ajax('services/giftwrap_bow.php','fpurpose=save_bow_order&'+qrystr+'&Idstr='+Idstr+'&OrderStr='+OrderStr);
		}

}
</script>
<form name="frmlistBow" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="request" value="giftwrap_bows" />
<input type="hidden" name="start" value="<?=$start?>" />
<input type="hidden" name="pg" value="<?=$pg?>" />
  <table border="0" cellpadding="0" cellspacing="0" class="maintable" width="100%">
    <tr>
      <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span>List Giftwrap Bows</span></div></td>
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
		?>	<tr>
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
		<td colspan="3" align="right" valign="middle" class="sorttd"><?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan);  ?>
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
          <td width="7%" height="30" align="left" valign="middle">Bow Name </td>
          <td width="12%" height="30" align="left" valign="middle"><input name="search_name" type="text" class="textfeild" id="search_name" value="<?=$_REQUEST['search_name']?>"  /></td>
          <td width="8%" height="30" align="left" valign="middle">Records Per Page </td>
          <td width="5%" height="30" align="left" valign="middle"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/></td>
          <td width="5%" height="30" align="left" valign="middle">Sort By</td>
          <td width="18%" height="30" align="left" valign="middle"><?=$sort_option_txt?>&nbsp;&nbsp;&nbsp;in&nbsp;&nbsp;&nbsp;  <?=$sort_by_txt?></td>
          <td width="10%" height="30" align="right" valign="middle"><input name="button5" type="submit" class="red" id="button5" value="Go" />
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_GIFTWRAP_BOW_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          </tr>
      </table>
	  </div>
      </td>
    </tr>
   
     
    <tr>
      <td colspan="3" class="listingarea">
	  <div class="listingarea_div">
      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable" width="100%">
	   <tr>
      <td class="listeditd" align="left" colspan="3"><a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=giftwrap_bows&fpurpose=add&records_per_page=<?=$records_per_page?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$start?>&pg=<?=$pg?>" class="addlist" onclick="show_processing()">Add</a> 
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
	  <?
	  if($numcount)
	  {
	  ?>
        <input name="save_order" type="button" class="red" id="save_order" value="Save Order" onclick="call_ajax_saveorder('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" />
        &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_GIFTWRAP_BOW_ORDER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;
		Change Status
          <select name="cbo_changehide" class="dropdown" id="cbo_changehide">
            <option value="0">Yes</option>
			<option value="1">No</option>
          </select>&nbsp;<input name="change_hide" type="button" class="red" id="change_hide" value="Change" onclick="call_ajax_changestatus('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_GIFTWRAP_BOW_CHSTATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
		<?
		}
		?>
   	   </td>
    </tr>
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
	   $sql_user = "SELECT bow_id,bow_name,bow_extraprice,bow_active,bow_order FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
	   
	   $res = $db->query($sql_user);
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
          <td align="left" valign="middle" class="<?=$class_val;?>"  width="6%"><input name="checkbox[]" value="<? echo $row['bow_id']?>" type="checkbox"></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"  width="2%"><?php echo $srno++?></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><a href="home.php?request=giftwrap_bows&fpurpose=edit&checkbox[0]=<?php echo $row['bow_id']?>&search_name=<?php echo $_REQUEST['search_name']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>" title="<? echo $row['bow_name']?>" class="edittextlink" onclick="show_processing()"><? echo $row['bow_name']?></a></td>
          
		  <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo  display_curr_symbol()?><?php echo $row['bow_extraprice']; ?></td>
		   <td align="left" valign="middle" class="<?=$class_val;?>"><input type="text" name="<? echo $row['bow_id']?>" value="<?php echo $row['bow_order']; ?>" size="3" /></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo ($row['bow_active'] == 1)?'No':'Yes'; ?></td>
         
        </tr>
      <?
	  }
	  }
	  else
	  {
	  ?>
	  <tr>
				  <td align="center" valign="middle" class="norecordredtext"  colspan="6">
				  	No Bow exists.				  </td>
			</tr>
		<?
		}
		?>
		<tr>
      <td class="listeditd" colspan="3" align="left"><a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=giftwrap_bows&fpurpose=add&records_per_page=<?=$records_per_page?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$start?>&pg=<?=$pg?>" class="addlist" onclick="show_processing()">Add</a> 
	  <?
	  if($numcount)
	  {
	  ?>
	  <a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
	  <?
	  }
	  ?>
	  </td>
	   <td class="listeditd" colspan="3"  align="right">
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
	  ?></td>
    </tr>  
    </table>
</form>
