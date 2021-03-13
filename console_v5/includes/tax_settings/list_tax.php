<?php
	/*#################################################################
	# Script Name 	: list_tax.php
	# Description 	: Page for listing Site Tax
	# Coded by 		: SKR
	# Created on	: 25-June-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
//Define constants for this page
$table_name='general_settings_site_tax';
$page_type='Tax';
$help_msg = get_help_messages('LIST_TAX_MESS1');
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistTax,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistTax,\'checkbox[]\')"/>','Slno.','Tax Name','Tax %','Active');
$header_positions=array('left','left','left','left','left','left');
$colspan = count($table_headers);

//#Search terms
$search_fields = array('tax_name');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'tax_name':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('tax_name' => 'Tax Name');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
if($_REQUEST['search_name']) {
	$where_conditions .= "AND ( tax_name LIKE '%".add_slash($_REQUEST['search_name'])."%')";
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
$query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=general_settings_tax&records_per_page=$records_per_page&search_name=".$_REQUEST['search_name']."&start=$start";
?>
<script language="javascript">
function checkSelected()
{
	len=document.frmlistTax.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistTax.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
				user_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one Tax ');
		return false;
	}
	show_processing();
	return true;
}
function edit_selected()
{
	
	len=document.frmlistTax.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistTax.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
				user_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one tax ');
	}
	else if(cnt>1 ){
		alert('Please select only one tax to edit');
	}
	else
	{
		show_processing();
		document.frmlistTax.fpurpose.value='edit';
		document.frmlistTax.submit();
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
	for(i=0;i<document.frmlistTax.elements.length;i++)
	{
		if (document.frmlistTax.elements[i].type =='checkbox' && document.frmlistTax.elements[i].name=='checkbox[]')
		{

			if (document.frmlistTax.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmlistTax.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select tax to delete');
	}
	else
	{
		if(confirm('Are you sure you want to delete selected Tax?'))
		{
			show_processing();
			Handlewith_Ajax('services/settings_tax.php','fpurpose=delete&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
</script>
<form name="frmlistTax" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="request" value="general_settings_tax" />
<input type="hidden" name="start" value="<?=$start?>" />
<input type="hidden" name="pg" value="<?=$pg?>" />
  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span>List Tax </span></div></td>
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
	<td colspan="3" class="sorttd" align="right">
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

      	<table width="100%" border="0" cellpadding="0" cellspacing="0" >
        <tr>
          <td width="7%" align="left" valign="middle">Tax Name </td>
          <td width="19%" align="left" valign="middle"><input name="search_name" type="text" class="textfeild" id="search_name" value="<?=$_REQUEST['search_name']?>"  /></td>
         
          <td width="14%" align="left" valign="middle">Records Per Page </td>
          <td width="10%" align="left" valign="middle"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/></td>
       
          <td width="7%" align="left" valign="middle">Sort By</td>
          <td width="15%" align="left" valign="middle" nowrap="nowrap"><?=$sort_option_txt?>&nbsp;&nbsp;in&nbsp;&nbsp;<?=$sort_by_txt?>  </td>
          <td width="28%" align="right" valign="middle">&nbsp;&nbsp;&nbsp;&nbsp;
            <input name="button5" type="submit" class="red" id="button5" value="Go" /> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_TAX_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
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
      <td width="24%" class="listeditd"><a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=general_settings_tax&fpurpose=add&records_per_page=<?=$records_per_page?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$start?>" class="addlist" onclick="show_processing()">Add</a> 
	  <?
	  if($numcount)
	  {
	  ?>
	  <a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
	  <?
	  }
	  ?>	  </td>
	  
	   <td width="33%" align="right" class="listeditd">
	  <?
	  if($numcount)
	  {
	  ?>
        Change Status
          <select name="cmbstatus" class="dropdown" id="cmbstatus">
            <option value="1">Yes</option>
			<option value="0">No</option>
          </select>&nbsp;<input name="Update_Status" type="submit" class="red" id="button4" value="Change" onclick="return checkSelected()" />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_TAX_CHSTATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
		<?
		}
		?>
      </td>
    </tr>
    
    <tr>
      <td colspan="3" class="listingarea">
      <table width="100%" height="0%"  border="0" cellpadding="0" cellspacing="0" class="listingtable">
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
	   $sql_user = "SELECT tax_id,tax_name,tax_val,tax_active FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
	   
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
          <td width="6%" height="26%" align="left" valign="middle" class="<?=$class_val;?>"><input name="checkbox[]" value="<? echo $row['tax_id']?>" type="checkbox"></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"  width="8%"><?php echo $srno++?></td>
          <td width="22%" align="left" valign="middle" class="<?=$class_val;?>"><a href="home.php?request=general_settings_tax&fpurpose=edit&checkbox[0]=<?php echo $row['tax_id']?>&search_name=<?php echo $_REQUEST['search_name']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>" title="<? echo $row['tax_name']?>" class="edittextlink" onclick="show_processing()"><? echo $row['tax_name']?></a></td>
		  <td width="21%" align="left" valign="middle" class="<?=$class_val;?>"><? echo $row['tax_val']?></td>
        
          <td width="21%" align="left" valign="middle" class="<?=$class_val;?>"><?php echo ($row['tax_active'] == 1)?'Yes':'No'; ?></td>
         
        </tr>
      <?
	  }
	  }
	  else
	  {
	  ?>
	  <tr>
				  <td height="74%" align="center" valign="middle" class="norecordredtext" colspan="5" >
				  	No Tax exists.				  </td>
		  </tr>
		<?
		}
		?>
		
		</table>
		
		</td>
		</tr>
		<tr>
      <td class="listeditd" align="left"><a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=general_settings_tax&fpurpose=add&records_per_page=<?=$records_per_page?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$start?>" class="addlist" onclick="show_processing()">Add</a> 
	  <?
	  if($numcount)
	  {
	  ?>
	  <a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
	  <?
	  }
	  ?>
	  </td>
	  <td class="listeditd" align="right">
	 </td>
	  
    </tr>	
      </table></div></td>
    </tr>
	<tr>
	<td class="listing_bottom_paging" align="right" colspan="2">
	  <?
	  if($numcount)
	  {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?></td>
	  
    </tr>
	
  </table>
</form>