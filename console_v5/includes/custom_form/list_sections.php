<?php
	/*#################################################################
	# Script Name 	: list_sections.php
	# Description 		: Page for listing Dynamic Form Sections
	# Coded by 		: SKR
	# Created on		: 18-Aug-2007
	# Modified by		: Sny
	# Modified On		: 08-Aug-2008
	#################################################################*/
//Define constants for this page
$table_name='element_sections';
$page_type='Section';
//str_replace("[form_type]",$_REQUEST['form_type'], $help_msg);
$help_msg = get_help_messages('LIST_CHECKOUT_FORM_MESS1');
$form_type = $_REQUEST['form_type'];
if($_REQUEST['form_type']=='enquire')
{
 $form_type = 'enquiry';
}
$help_msg = str_replace('[formtype]',$form_type,$help_msg);
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistSection,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistSection,\'checkbox[]\')"/>','Slno.','Section Name','Sort Order','Hide Heading','Active','Action');
$header_positions=array('left','left','left','left','center','center','center');
$colspan = count($table_headers);

//#Search terms
$search_fields = array('country_name');
foreach($search_fields as $v) {
	$query_string .= "$v=${$v}&";#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'section_name':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('section_name' => 'Section Name');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
if($_REQUEST['form_type'])
{
	$where_conditions .= " AND section_type='".$_REQUEST['form_type']."'";
}
if($_REQUEST['search_name']) {
	$where_conditions .= " AND ( section_name LIKE '%".add_slash($_REQUEST['search_name'])."%')";
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
$query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=customform&form_type=".$_REQUEST['form_type']."&records_per_page=$records_per_page&search_name=".$_REQUEST['search_name']."&start=$start";
?>
<script language="javascript">
function call_ajax_changestatus(search_name,sortby,sortorder,recs,start,pg,form_type)
{
	var atleastone 			= 0;
	var curid				= 0;
	var section_ids 		= '';
	var cat_orders			= '';
	var ch_status			= document.frmlistSection.cbo_changehide.value;
	var qrystr				= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&ch_status='+ch_status+'&start='+start+'&pg='+pg+'&form_type='+form_type;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlistSection.elements.length;i++)
	{
		if (document.frmlistSection.elements[i].type =='checkbox' && document.frmlistSection.elements[i].name=='checkbox[]')
		{

			if (document.frmlistSection.elements[i].checked==true)
			{
				atleastone = 1;
				if (section_ids!='')
					section_ids += '~';
				 section_ids += document.frmlistSection.elements[i].value;
			}	
		}
	}
	if (atleastone==0) 
	{
		alert('Please select the sections to change the hide status');
	}
	else
	{
		if(confirm('Change Hide Status of Seleted Section(s)?'))
		{
				show_processing();
				Handlewith_Ajax('services/custom_form.php','fpurpose=change_hide&'+qrystr+'&section_ids='+section_ids);
		}	
	}	
}
function checkSelected()
{
	len=document.frmlistSection.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistSection.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
				user_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one country ');
		return false;
	}
	show_processing();
	return true;
}
function edit_selected()
{
	
	len=document.frmlistSection.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistSection.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
				user_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one section ');
	}
	else if(cnt>1 ){
		alert('Please select only one section to edit');
	}
	else
	{
		show_processing();
		document.frmlistSection.fpurpose.value='edit_section';
		document.frmlistSection.submit();
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
function call_ajax_delete(search_name,sortby,sortorder,recs,start,pg,form_type)
{
	var atleastone 	= 0;
	var del_ids 	= '';
	var qrystr				= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&form_type='+form_type;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlistSection.elements.length;i++)
	{
		if (document.frmlistSection.elements[i].type =='checkbox' && document.frmlistSection.elements[i].name=='checkbox[]')
		{

			if (document.frmlistSection.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmlistSection.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select section to delete');
	}
	else
	{
		if(confirm('Are you sure you want to delete selected Section?'))
		{
			show_processing();
			Handlewith_Ajax('services/custom_form.php','fpurpose=delete_section&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
</script>
<form name="frmlistSection" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="request" value="customform" />
<input type="hidden" name="form_type" value="<?=$_REQUEST['form_type']?>" />
<input type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>" />
<input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />

  <table border="0" cellpadding="0" cellspacing="0" class="maintable" width="100%">
    <tr>
      <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="javascript:void(0);"><? echo ucwords($_REQUEST['form_type'])?> Form</a><span> List Sections</span></div></td>
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
	  <div class="sorttd_div" >
      	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttabletop">
        <tr>
          <td width="10%" height="30" align="left" valign="middle">Section Name </td>
          <td width="19%" height="30" align="left" valign="middle"><input name="search_name" type="text" class="textfeild" id="search_name" value="<?=$_REQUEST['search_name']?>"  /></td>
          <td width="12%" height="30" align="left" valign="middle">Records Per Page </td>
          <td width="16%" height="30" align="left" valign="middle"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/></td>
          <td width="6%" height="30" align="left" valign="middle">Sort By</td>
          <td width="26%" height="30" align="left" valign="middle"><?=$sort_option_txt?>&nbsp;&nbsp;&nbsp;in&nbsp;&nbsp;&nbsp;<?=$sort_by_txt?></td>
          <td width="11%" height="30" align="right" valign="middle"><input name="button5" type="submit" class="red" id="button5" value="Go" />
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_CHECKOUT_FORM_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          </tr>
      </table>
      </div></td>
    </tr>
        
    <tr>
      <td colspan="3" class="listingarea">
	  <div class="listingarea_div" >
      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable" width="100%">
	  <tr>
      <td class="listeditd" align="left" valign="middle" colspan="4"><a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=customform&form_type=<?=$_REQUEST['form_type']?>&fpurpose=add_section&records_per_page=<?=$records_per_page?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$start?>&pg=<?=$pg?>" class="addlist" onclick="show_processing()">Add</a> 
	  <?
	  if($numcount)
	  {
	  ?>
	  <a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>','<?php echo $_REQUEST['form_type']?>')" class="deletelist">Delete</a>
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
          </select>&nbsp;<input name="change_hide" type="button" class="red" id="change_hide" value="Change" onclick="call_ajax_changestatus('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>','<?php echo $_REQUEST['form_type']?>')" />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_CHECKOUT_FORM_CHSTATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
		<?
		}
		?>
   	   </td>
    </tr>
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
	   $sql_user = "SELECT section_id,section_name,sort_no,activate,hide_heading FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
	   
	   $res = $db->query($sql_user);
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
          <td align="left" valign="middle" class="<?=$class_val;?>"  width="5%" ><input name="checkbox[]" value="<? echo $row['section_id']?>" type="checkbox"></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"  width="2%"><?php echo $srno++?></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><a href="home.php?request=customform&form_type=<?=$_REQUEST['form_type']?>&fpurpose=edit_section&checkbox[0]=<?php echo $row['section_id']?>&search_name=<?php echo $_REQUEST['search_name']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>" title="<? echo $row['section_name']?>" class="edittextlink" onclick="show_processing()"><? echo $row['section_name']?></a></td>
           <td align="left" valign="middle" class="<?=$class_val;?>"><?=$row['sort_no']?></td>
          <td align="center" valign="middle" class="<?=$class_val;?>"><?php echo ($row['hide_heading'] == 1)?'Yes':'No'; ?></td>
		  <td align="center" valign="middle" class="<?=$class_val;?>"><?php echo ($row['activate'] == 1)?'Yes':'No'; ?></td>
          <td align="center" valign="middle" class="<?=$class_val;?>"><a href="home.php?request=customform&fpurpose=manage_form&form_type=<?=$_REQUEST['form_type']?>&section_id=<?=$row['section_id']?>&search_name=<?php echo $_REQUEST['search_name']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>"><img src="images/form.gif" border="0" alt="Manage Forms" title="Manage Forms" /></a></td>
        </tr>
      <?
	  }
	  }
	  else
	  {
	  ?>
	  <tr>
				  <td align="center" valign="middle" class="norecordredtext"  colspan="6">
				  	No Section exists.				  </td>
			</tr>
		<?
		}
		?>
		<tr>
      <td class="listeditd" align="left" valign="middle" colspan="4"><a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=customform&form_type=<?=$_REQUEST['form_type']?>&fpurpose=add_section&records_per_page=<?=$records_per_page?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$start?>&pg=<?=$pg?>" class="addlist" onclick="show_processing()">Add</a> 
	  <?
	  if($numcount)
	  {
	  ?>
	  <a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>','<?php echo $_REQUEST['form_type']?>')" class="deletelist">Delete</a>
	  <?
	  }
	  ?>
	  </td>
	   <td class="listeditd" align="right" valign="middle" colspan="3">
	  </td>
    </tr>	
      </table>
	  </div>
	  </td>
    </tr>
	<tr>
     
	   <td class="listing_bottom_paging" align="right" valign="middle" colspan="2">
	  <?
	  if($numcount)
	  {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?></td>
    </tr>	
    </table>
</form>
