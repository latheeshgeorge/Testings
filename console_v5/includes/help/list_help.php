<?php
/*#################################################################
# Script Name 	: list_help.php
# Description 		: Page for listing HELP
# Coded by 		: Sny
# Created on		: 01-Jan-2009
#################################################################*/
//Define constants for this page
$table_name='help';
$page_type='Help';
$help_msg = get_help_messages('LIST_HELP_MESS1');
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmhelp,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmhelp,\'checkbox[]\')"/>','Slno.','Heading','Order','Hidden'	);
$header_positions=array('left','left','left','left','left');
$colspan = count($table_headers);

//#Search terms
$search_fields = array('search_help_heading');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'help_heading':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('help_heading' => 'Heading', 'help_sortorder' => 'Order');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
if($_REQUEST['search_help_heading']) {
	$where_conditions .= "AND ( help_heading LIKE '%".add_slash($_REQUEST['search_help_heading'])."%')";
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
$query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=help&records_per_page=$records_per_page&search_help_heading=".$_REQUEST['search_help_heading']."&start=$start";
?>
<script language="javascript">
function call_ajax_changestatus_old(search_name,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var curid				= 0;
	var type_ids 			= '';
	var cat_orders			= '';
	var ch_status			= document.frmhelp.cbo_changehide.value;
	var qrystr				= 'search_help_heading='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&ch_status='+ch_status+'&start='+start+'&pg='+pg;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmhelp.elements.length;i++)
	{
		if (document.frmhelp.elements[i].type =='checkbox' && document.frmhelp.elements[i].name=='checkbox[]')
		{
			if (document.frmhelp.elements[i].checked==true)
			{
				atleastone = 1;
				if (type_ids!='')
					type_ids += '~';
				 type_ids += document.frmhelp.elements[i].value;
			}	
		}
	}
	if (atleastone==0) 
	{
		alert('Please select the HELPs to change the hide status');
	}
	else
	{
		if(confirm('Change Hide Status of Seleted Company Type(s)?'))
		{
				show_processing();
				Handlewith_Ajax('services/help.php','fpurpose=change_hide&'+qrystr+'&type_ids='+type_ids);
		}	
	}	
}
function  call_ajax_changestatus(search_name,sortby,sortorder,recs,start,pg)
{
	var atleastone 	= 0;
	var del_ids 	= '';
	var Idstr		= '';
	var Orderstr	= '';
	var ch_status			= document.frmhelp.cbo_changehide.value;
	var qrystr				= 'search_help_heading='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&ch_status='+ch_status+'&start='+start+'&pg='+pg;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmhelp.elements.length;i++)
	{
		if (document.frmhelp.elements[i].type =='checkbox' && document.frmhelp.elements[i].name=='checkbox[]')
		{

			if (document.frmhelp.elements[i].checked==true)
			{
				atleastone = 1;
				if (Idstr!='')
					Idstr += '~';
				 Idstr += document.frmhelp.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select the HELPs to change the hide status');
	}
	else
	{
		if(confirm('Change the status of selected HELP(s)?'))
		{
			show_processing();
			Handlewith_Ajax('services/help.php','fpurpose=change_hide&'+qrystr+'&type_ids='+Idstr);
		}	
	}	
}
function edit_selected()
{
	
	len=document.frmhelp.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmhelp.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
				user_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one HELP ');
	}
	else if(cnt>1 ){
		alert('Please select only one HELP to edit');
	}
	else
	{
		show_processing();
		document.frmhelp.fpurpose.value='edit';
		document.frmhelp.submit();
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
	var qrystr				= 'search_help_heading='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmhelp.elements.length;i++)
	{
		if (document.frmhelp.elements[i].type =='checkbox' && document.frmhelp.elements[i].name=='checkbox[]')
		{

			if (document.frmhelp.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmhelp.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select HELP to delete');
	}
	else
	{
		if(confirm('Are you sure you want to delete selected HELP?'))
		{
			show_processing();
			Handlewith_Ajax('services/help.php','fpurpose=delete&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
function call_ajax_saveorder(search_name,sortby,sortorder,recs,start,pg)
{
	var atleastone 	= 0;
	var del_ids 	= '';
	var Idstr		= '';
	var Orderstr	= '';
	var qrystr				= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmhelp.elements.length;i++)
	{
		if (document.frmhelp.elements[i].type =='checkbox' && document.frmhelp.elements[i].name=='checkbox[]')
		{

			if (document.frmhelp.elements[i].checked==true)
			{
				atleastone = 1;
				if (Idstr!='')
					Idstr += '~';
				 Idstr += document.frmhelp.elements[i].value;
				 if (Orderstr!='')
					Orderstr += '~';
				obj = eval('document.frmhelp.ord_'+document.frmhelp.elements[i].value);
				 Orderstr += obj.value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select the help to save the order');
	}
	else
	{
		if(confirm('Save Sort Order Of HELP?'))
		{
			show_processing();
			Handlewith_Ajax('services/help.php','fpurpose=save_help_order&'+qrystr+'&Idstr='+Idstr+'&OrderStr='+Orderstr);
		}	
	}	
}
function call_ajax_saveorder_old(search_name,sortby,sortorder,recs,start,pg)
{
	var qrystr= 'search_help_heading='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;
	var atleastone = 0;
	var IdArr=new Array();
	var OrderArr=new Array();
	var index;
	var val;
	var j=0;
	for(i=0;i<document.frmhelp.elements.length;i++)
	{
		if (document.frmhelp.elements[i].type =='text' && document.frmhelp.elements[i].name.substr(0,4)=='ord_')
		{
			if (document.frmhelp.elements[i].checked==true)
			{
				atleastone = 1;
				
				index_arr=document.frmhelp.elements[i].name.split('_');
				index = index_arr[1];
				val=document.frmhelp.elements[i].value;
				IdArr[j]=index;
				OrderArr[j]=val;
				j=j+1;
			}
		}
	}
	if (atleastone==0)
	{
		alert('Please select the help to save the order');
		return false;
	}
	var Idstr=IdArr.join('~');
	var OrderStr=OrderArr.join('~');
	if(confirm('Save Sort Order Of HELP?'))
	{
			show_processing();
			Handlewith_Ajax('services/help.php','fpurpose=save_help_order&'+qrystr+'&Idstr='+Idstr+'&OrderStr='+OrderStr);
	}

}
</script>
<form name="frmhelp" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="request" value="help" />
<input type="hidden" name="start" value="<?=$start?>" />
<input type="hidden" name="pg" value="<?=$pg?>" />
  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span>List Help</span></div></td>
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
		<td class="sorttd" colspan="3" align="right">
		<?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?>
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
          <td width="8%" height="30" align="left" valign="middle">Help Heading </td>
          <td width="20%" height="30" align="left" valign="middle"><input name="search_help_heading" type="text" class="textfeild" id="search_help_heading" value="<?=$_REQUEST['search_help_heading']?>"  /></td>
          <td width="10%" height="30" align="left" valign="middle">Records Per Page </td>
          <td width="15%" height="30" align="left" valign="middle"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/></td>
          <td width="5%" height="30" align="left" valign="middle">Sort By</td>
          <td width="26%" height="30" align="left" valign="middle"><?=$sort_option_txt?>&nbsp;&nbsp;&nbsp;in&nbsp;&nbsp;&nbsp; <?=$sort_by_txt?></td>
          <td width="16%" height="30" align="right" valign="middle">&nbsp;
            <input name="button5" type="submit" class="red" id="button5" value="Go" />
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_HLP_SRCH_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
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
      <td class="listeditd" colspan="3"><a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=help&fpurpose=add&records_per_page=<?=$records_per_page?>&search_help_heading=<?=$_REQUEST['search_help_heading']?>&start=<?=$start?>&pg=<?=$pg?>" class="addlist" onclick="show_processing()">Add</a> 
	  <?
	  if($numcount)
	  {
	  ?>
	  <a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_help_heading']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
	  <?
	  }
	  ?>
	  </td>
	   <td class="listeditd" align="right" colspan="2">
	  <?
	  if($numcount)
	  {
	  ?>
        <input name="save_order" type="button" class="red" id="save_order" value="Save Order" onclick="call_ajax_saveorder('<?php echo $_REQUEST['search_help_heading']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" />
        &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_COMPANY_TYPES_SAVE_ORDER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;
		Change Hidden Status
          <select name="cbo_changehide" class="dropdown" id="cbo_changehide">
            <option value="1">Yes</option>
			<option value="0">No</option>
          </select>&nbsp;<input name="change_hide" type="button" class="red" id="change_hide" value="Change" onclick="call_ajax_changestatus('<?php echo $_REQUEST['search_help_heading']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_HELP_SAVE_CHANGE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
		<?
		}
		?>
   	   </td>
    </tr>
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
	   $sql_help = "SELECT help_id, sites_site_id, help_heading, help_description, help_sortorder, help_hide 
	   								FROM 
										$table_name 
										$where_conditions 
									ORDER BY 
										$sort_by $sort_order 
									LIMIT 
										$start,$records_per_page ";
	   
	   $res = $db->query($sql_help);
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
          <td align="left" valign="middle" class="<?=$class_val;?>"  width="6%"><input name="checkbox[]" value="<? echo $row['help_id']?>" type="checkbox"></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"  width="2%"><?php echo $srno++?></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><a href="home.php?request=help&fpurpose=edit&checkbox[0]=<?php echo $row['help_id']?>&search_help_heading=<?php echo $_REQUEST['search_help_heading']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>"  class="edittextlink" onclick="show_processing()"><? echo stripslashes($row['help_heading'])?></a></td>
		 <td align="left" valign="middle" class="<?=$class_val;?>"><input type="text" name="ord_<? echo $row['help_id']?>" id="ord_<? echo $row['help_id']?>" value="<? echo $row['help_sortorder']?>" size="3" /></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo ($row['help_hide'] == 1)?'Yes':'No'; ?></td>
         
        </tr>
      <?
	  }
	  }
	  else
	  {
	  ?>
	  <tr>
				  <td align="center" valign="middle" class="norecordredtext" colspan="5" >
				  	No Help added yet.				  </td>
			</tr>
		<?
		}
		?>	
		<tr>
     	 <td class="listeditd" colspan="3">
		 <a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=help&fpurpose=add&records_per_page=<?=$records_per_page?>&search_help_heading=<?=$_REQUEST['search_help_heading']?>&start=<?=$start?>&pg=<?=$pg?>" class="addlist" onclick="show_processing()">Add</a> 
	  <?
	  if($numcount)
	  {
	  ?>
	  <a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_help_heading']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
	  <?
	  }
	  ?>
		 </td>
		 <td class="listeditd" colspan="2" align="right">
		
		 </td>
		</tr>
      </table>
	  </div></td>
    </tr>
	<tr>
		 <td class="listing_bottom_paging" colspan="2" align="right">
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
<!-- Script for auto complete starts here -->
<script language="javascript">
var $pnc = jQuery.noConflict();
$pnc().ready(function() { 
	auto_search('search_help_heading','sitehelp'); 
});
</script>
<!-- Script for auto complete ends here -->