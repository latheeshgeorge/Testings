<?php
/*#################################################################
# Script Name 		: list_kmllocation.php
# Description 		: Page for listing kmlsitemap locations
# Coded by 			: Sny
# Created on		: 05-Oct-2009
# Modified on		: 06-Oct-2009
#################################################################*/
//Define constants for this page
$table_name			= 'seo_kml_location';
$page_type			= 'KML Locations';
$help_msg 				= get_help_messages('LIST_KML_MAIN_MSG');
$table_headers 		= array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlocation,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlocation,\'checkbox[]\')"/>','Slno.','Location Name','Company Name','Order','Hidden'	);
$header_positions 	= array('left','left','left','left','center');
$colspan 				= count($table_headers);

//#Search terms
$search_fields = array('search_location_name','search_location_name');
foreach($search_fields as $v)
{
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by 			= (!$_REQUEST['sort_by'])?'kml_location_name':$_REQUEST['sort_by'];
$sort_order 		= (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options 		= array('kml_location_name' => 'Location Name', 'kml_company_name' => 'Company Name','kml_order'=>'Order');
$sort_option_txt	= generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt		= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
if($_REQUEST['search_location_name']) {
	$where_conditions .= "AND ( kml_location_name LIKE '%".add_slash($_REQUEST['search_location_name'])."%') ";
}
if($_REQUEST['search_company_name']) {
	$where_conditions .= "AND ( kml_company_name LIKE '%".add_slash($_REQUEST['search_company_name'])."%') ";
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
$query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=kmlsitemap&records_per_page=$records_per_page&start=$start";
?>
<script language="javascript">
function  call_ajax_changestatus(search_comp,search_loc,sortby,sortorder,recs,start,pg)
{
	var atleastone 	= 0;
	var del_ids 	= '';
	var Idstr		= '';
	var Orderstr	= '';
	var ch_status			= document.frmlocation.cbo_changehide.value;
	var qrystr				= 'search_company_name='+search_comp+'&search_location_name='+search_loc+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&ch_status='+ch_status+'&start='+start+'&pg='+pg;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlocation.elements.length;i++)
	{
		if (document.frmlocation.elements[i].type =='checkbox' && document.frmlocation.elements[i].name=='checkbox[]')
		{

			if (document.frmlocation.elements[i].checked==true)
			{
				atleastone = 1;
				if (Idstr!='')
					Idstr += '~';
				 Idstr += document.frmlocation.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select the KML Locations to change the hidden status');
	}
	else
	{
		if(confirm('Change the status of selected HELP(s)?'))
		{
			show_processing();
			Handlewith_Ajax('services/kmlsitemap.php','fpurpose=change_hide&'+qrystr+'&type_ids='+Idstr);
		}	
	}	
}
function edit_selected()
{
	
	len=document.frmlocation.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlocation.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
				user_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one KML Location  ');
	}
	else if(cnt>1 ){
		alert('Please select only one KML Location to edit');
	}
	else
	{
		show_processing();
		document.frmlocation.fpurpose.value='edit';
		document.frmlocation.submit();
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
function call_ajax_delete(search_comp,search_loc,sortby,sortorder,recs,start,pg)
{
	var atleastone 	= 0;
	var del_ids 	= '';
	var qrystr				= 'search_company_name='+search_comp+'&search_location_name='+search_loc+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlocation.elements.length;i++)
	{
		if (document.frmlocation.elements[i].type =='checkbox' && document.frmlocation.elements[i].name=='checkbox[]')
		{

			if (document.frmlocation.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmlocation.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select KML location to delete');
	}
	else
	{
		if(confirm('Are you sure you want to delete selected Locations?'))
		{
			show_processing();
			Handlewith_Ajax('services/kmlsitemap.php','fpurpose=delete&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
function call_ajax_saveorder(search_comp,search_loc,sortby,sortorder,recs,start,pg)
{
	var atleastone 	= 0;
	var del_ids 	= '';
	var Idstr		= '';
	var Orderstr	= '';
	var qrystr				= 'search_company_name='+search_comp+'&search_location_name='+search_loc+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlocation.elements.length;i++)
	{
		if (document.frmlocation.elements[i].type =='checkbox' && document.frmlocation.elements[i].name=='checkbox[]')
		{

			if (document.frmlocation.elements[i].checked==true)
			{
				atleastone = 1;
				if (Idstr!='')
					Idstr += '~';
				 Idstr += document.frmlocation.elements[i].value;
				 if (Orderstr!='')
					Orderstr += '~';
				obj = eval('document.frmlocation.ord_'+document.frmlocation.elements[i].value);
				 Orderstr += obj.value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select the location to save the order');
	}
	else
	{
		if(confirm('Save Sort Order Of locations?'))
		{
			show_processing();
			Handlewith_Ajax('services/kmlsitemap.php','fpurpose=save_kml_order&'+qrystr+'&Idstr='+Idstr+'&OrderStr='+Orderstr);
		}	
	}	
}
</script>
<form name ="frmlocation" action="home.php" method="post" >	
<input type ="hidden" name="fpurpose" value="" />
<input type ="hidden" name="request" value="kmlsitemap" />
<input type ="hidden" name="start" value="<?=$start?>" />
<input type ="hidden" name="pg" value="<?=$pg?>" />
  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span> List KML Sitemap Locations</span></div> </td>
    </tr>
	<tr>
	  <td colspan="3" align="left" valign="middle" class="helpmsgtd_main">
	  <?php 
		  Display_Main_Help_msg($help_arr,$help_msg);
	  ?>	 </td>
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
	  <div class="listingarea_div">
      	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttabletop">
        <tr>
          <td width="8%" height="30" align="left" valign="middle">Location Name </td>
          <td width="12%" height="30" align="left" valign="middle"><input name="search_location_name" type="text" class="textfeild" id="search_location_name" value="<?=$_REQUEST['search_location_name']?>"  /></td>
          <td width="8%" height="30" align="left" valign="middle">Company Name </td>
          <td width="12%" height="30" align="left" valign="middle"><input name="search_company_name" type="text" class="textfeild" id="search_company_name" value="<?=$_REQUEST['search_company_name']?>" /></td>
          <td width="8%" height="30" align="left" valign="middle">Records Per Page </td>
          <td width="4%" height="30" align="left" valign="middle"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/></td>
          <td width="4%" height="30" align="left" valign="middle">Sort By</td>
          <td width="18%" height="30" align="left" valign="middle"><?=$sort_option_txt?>&nbsp;&nbsp;&nbsp;in&nbsp;&nbsp;&nbsp;  <?=$sort_by_txt?></td>
          <td width="8%" height="30" align="left" valign="middle"><input name="button5" type="submit" class="red" id="button5" value="Go" />
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_COMPANY_TYPES_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          <td width="18%" height="30" align="left" valign="middle">&nbsp;</td>
        </tr>
      </table>
	  </div>
      </td>
	</tr>
    <tr>
      <td class="listeditd"><a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=kmlsitemap&fpurpose=add&records_per_page=<?=$records_per_page?>&search_location_name=<?=$_REQUEST['search_location_name']?>&search_company_name=<?=$_REQUEST['search_company_name']?>&start=<?=$start?>&pg=<?=$pg?>" class="addlist" onclick="show_processing()">Add</a> 
	  <?
	  if($numcount)
	  {
	  ?>
	  <a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_company_name']?>','<?php echo $_REQUEST['search_location_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
	  <?
	  }
	  ?>	  </td>
      <td class="listeditd" width="162" align="center">&nbsp;</td>
	   <td class="listeditd" align="right">
	  <?
	  if($numcount)
	  {
	  ?>
        <input name="save_order" type="button" class="red" id="save_order" value="Save Order" onclick="call_ajax_saveorder('<?php echo $_REQUEST['search_company_name']?>','<?php echo $_REQUEST['search_location_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" />&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_COMPANY_TYPES_SAVE_ORDER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;
		Change Status
          <select name="cbo_changehide" class="dropdown" id="cbo_changehide">
            <option value="1">Yes</option>
			<option value="0">No</option>
          </select>&nbsp;<input name="change_hide" type="button" class="red" id="change_hide" value="Change" onclick="call_ajax_changestatus('<?php echo $_REQUEST['search_company_name']?>','<?php echo $_REQUEST['search_location_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_HELP_SAVE_CHANGE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
		<?
		}
		?>   	   </td>
    </tr>
     
    <tr>
      <td colspan="3" class="listingarea">
	  <div class="listingarea_div">
      <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="listingtable">
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
	   $sql_help = "SELECT kml_id, kml_location_name, kml_company_name, kml_order ,kml_hide 
						FROM 
							$table_name 
							$where_conditions 
						ORDER BY 
							$sort_by $sort_order 
						LIMIT 
							$start,$records_per_page ";
	   $res = $db->query($sql_help);
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
         <td align="left" valign="middle" class="<?=$class_val;?>"  width="6%"><input name="checkbox[]" value="<? echo $row['kml_id']?>" type="checkbox"></td>
		 <td align="left" valign="middle" class="<?=$class_val;?>"  width="2%"><?php echo $srno++?></td>
         <td align="left" valign="middle" class="<?=$class_val;?>"><a href="home.php?request=kmlsitemap&fpurpose=edit&checkbox[0]=<?php echo $row['kml_id']?>&search_location_name=<?php echo $_REQUEST['search_location_name']?>&search_company_name=<?php echo $_REQUEST['search_company_name']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>"  class="edittextlink" onclick="show_processing()"><? echo stripslashes($row['kml_location_name'])?></a></td>
		 <td align="left" valign="middle" class="<?=$class_val;?>"><? echo stripslashes($row['kml_company_name'])?></td>
		 <td align="center" valign="middle" class="<?=$class_val;?>"><input type="text" name="ord_<? echo $row['kml_id']?>" id="ord_<? echo $row['kml_id']?>" style="text-align:center"  value="<? echo $row['kml_order']?>" size="3" /></td>
         <td align="center" valign="middle" class="<?=$class_val;?>"><?php echo ($row['kml_hide'] == 1)?'Yes':'No'; ?></td>
        </tr>
      <?
	  }
	  }
	  else
	  {
	  ?>
	  <tr>
				  <td align="center" valign="middle" class="norecordredtext" colspan="5" >
				  	No KML Locations Added yet.				  </td>
		  </tr>
		<?
		}
		?>	
		<tr>
      <td class="listeditd" align="left" valign="middle" colspan="3"><a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=kmlsitemap&fpurpose=add&records_per_page=<?=$records_per_page?>&search_location_name=<?=$_REQUEST['search_location_name']?>&search_company_name=<?=$_REQUEST['search_company_name']?>&start=<?=$start?>&pg=<?=$pg?>" class="addlist" onclick="show_processing()">Add</a> 
	  <?
	  if($numcount)
	  {
	  ?>
	  <a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_company_name']?>','<?php echo $_REQUEST['search_location_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
	  <?
	  }
	  ?>	  </td>
	   <td class="listeditd"  align="right" valign="middle" colspan="2">
	  <?
	  if($numcount)
	  {
	  	  paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?></td>
    </tr>
      </table>
	  </div>
	  </td>
    </tr>
	
  
  <?php
  	// Get common details (if any) from sites table
	$sql_sites = "SELECT kml_companyname, kml_author 
							FROM 
								sites 
							WHERE 
								site_id = $ecom_siteid 
							LIMIT 
							1";
	$ret_sites = $db->query($sql_sites);
	if($db->num_rows($ret_sites))
	{
		$row_sites = $db->fetch_array($ret_sites);
	}
  ?>
  	<tr>
    <td colspan="3">
	<div class="listingarea_div">
	<table width="100%" border="0">
	<tr>
        <td colspan="3" class="listingtableheader">Details to be used in KML file header section  </td>
		</tr>
      <tr>
        <td width="12%" class="listeditd">Company Name </td>
        <td colspan="2"><input name="main_comp_name" id="main_comp_name" type="text" size="50" value="<?php echo stripslashes($row_sites['kml_companyname'])?>" /></td>
      </tr>
      <tr>
        <td class="listeditd">Author</td>
        <td colspan="2"><input name="main_author" id="main_author" type="text" size="50" value="<?php echo stripslashes($row_sites['kml_author'])?>"/></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td width="25%">&nbsp;</td>
        <td width="63%" align="right"><input type="button" name="main_det_save" value="Save Details" class="red" onclick="document.frmlocation.fpurpose.value='save_main_kml_details';document.frmlocation.submit()"/></td>
      </tr>
    </table>
	</div>
	</td>
    </tr>
  </table>
</form>
