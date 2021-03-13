<?php
	/*#################################################################
	# Script Name 	: list_country.php
	# Description 	: Page for listing Site Country
	# Coded by 		: SKR
	# Created on	: 15-June-2007
	# Modified by	: SKR
	# Modified On	: 25-June-2007
	#################################################################*/
//Define constants for this page
$table_name='general_settings_site_country';
$page_type='Country';
$help_msg = get_help_messages('LIST_COUNTRY_MESS1');
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistCountry,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistCountry,\'checkbox[]\')"/>','Slno.','Country Name','Country Code','Country Numeric Code','Active');
$header_positions=array('left','left','left','left','left','left');
$colspan = count($table_headers);

//#Search terms
$search_fields = array('country_name');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'country_name':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('country_name' => 'Country Name');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
if($_REQUEST['search_name']) {
	$where_conditions .= "AND ( country_name LIKE '%".add_slash($_REQUEST['search_name'])."%')";
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
$query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=general_settings_country&records_per_page=$records_per_page&search_name=".$_REQUEST['search_name']."&start=$start";
?>
<script language="javascript">
function call_ajax_changestatus(search_name,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var curid				= 0;
	var country_ids 			= '';
	var cat_orders			= '';
	var ch_status			= document.frmlistCountry.cbo_changehide.value;
	var qrystr				= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&ch_status='+ch_status+'&start='+start+'&pg='+pg;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlistCountry.elements.length;i++)
	{
		if (document.frmlistCountry.elements[i].type =='checkbox' && document.frmlistCountry.elements[i].name=='checkbox[]')
		{

			if (document.frmlistCountry.elements[i].checked==true)
			{
				atleastone = 1;
				if (country_ids!='')
					country_ids += '~';
				 country_ids += document.frmlistCountry.elements[i].value;
			}	
		}
	}
	if (atleastone==0) 
	{
		alert('Please select the countries to change the hide status');
	}
	else
	{
		if(confirm('Change Hide Status of Seleted Country(s)?'))
		{
				show_processing();
				Handlewith_Ajax('services/settings_countries.php','fpurpose=change_hide&'+qrystr+'&country_ids='+country_ids);
		}	
	}	
}
function checkSelected()
{
	len=document.frmlistCountry.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistCountry.elements[j]
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
	
	len=document.frmlistCountry.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistCountry.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
				user_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one country ');
	}
	else if(cnt>1 ){
		alert('Please select only one country to edit');
	}
	else
	{
		show_processing();
		document.frmlistCountry.fpurpose.value='edit';
		document.frmlistCountry.submit();
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
	for(i=0;i<document.frmlistCountry.elements.length;i++)
	{
		if (document.frmlistCountry.elements[i].type =='checkbox' && document.frmlistCountry.elements[i].name=='checkbox[]')
		{

			if (document.frmlistCountry.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmlistCountry.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select country to delete');
	}
	else
	{
		if(confirm('Are you sure you want to delete selected Country?'))
		{
			show_processing();
			Handlewith_Ajax('services/settings_countries.php','fpurpose=delete&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
function save_default_country(search_name,sortby,sortorder,recs,start,pg)
{
	var qrystr				= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;
	if(document.getElementById('default_country').value=='')
	{
		alert ('Please select the country to be set as default country');
	}
	else
	{
		if(confirm('Are you sure you want to set the selected country as default country?'))
		{
			show_processing();
			Handlewith_Ajax('services/settings_countries.php','fpurpose=save_default_country&def_id='+document.getElementById('default_country').value+'&'+qrystr);
		}
	}
}
</script>
<form name="frmlistCountry" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="request" value="general_settings_country" />
<input type="hidden" name="start" value="<?=$start?>" />
<input type="hidden" name="pg" value="<?=$pg?>" />
  <table border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=general_settings">General Settings</a> <span> List Country</span></div></td>
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
      <td class="sorttd" colspan="3" >
	  <div class="sorttd_div">
      	<table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="11%" height="30" align="left" valign="middle">Country Name </td>
          <td width="24%" height="30" align="left" valign="middle"><input name="search_name" type="text" class="textfeild" id="search_name" value="<?=$_REQUEST['search_name']?>"  /></td>
          <td width="13%" height="30" align="left" valign="middle">Records Per Page </td>
          <td width="12%" height="30" align="left" valign="middle"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/></td>
          <td width="7%" height="30" align="left" valign="middle">Sort By</td>
          <td width="18%" height="30" align="left" valign="middle"><?=$sort_option_txt?>&nbsp;&nbsp;&nbsp;in&nbsp;&nbsp;&nbsp;<?=$sort_by_txt?></td>
          <td width="10%" height="30" align="right" valign="middle"><input name="button5" type="submit" class="red" id="button5" value="Go" />
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_COUNTRY_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
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
      <td class="listeditd" align="left" valign="middle" colspan="3"><a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=general_settings_country&fpurpose=add&records_per_page=<?=$records_per_page?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$start?>&pg=<?=$pg?>" class="addlist" onclick="show_processing()">Add</a> 
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
	  <?
	  if($numcount)
	  {
	  	// Get the default country
		$sql_def = "SELECT default_country_id 
						FROM 
							general_settings_sites_common 
						WHERE 
							sites_site_id = $ecom_siteid 
						LIMIT 
							1";
		$ret_def = $db->query($sql_def);
		list($def_set_val) = $db->fetch_array($ret_def);
	  	$sql_def_count = "SELECT country_id,country_name 
							FROM 
								general_settings_site_country 
							WHERE 
								sites_site_id = $ecom_siteid 
							ORDER BY 
								country_name ";
		$ret_def_count = $db->query($sql_def_count);
		if($db->num_rows($ret_def_count))
		{
		?>
			Default Country <select name="default_country" class="dropdown" id="default_country" style="width:200px">
			<option value="">-- Select -- </option>
		<?php
			while ($row_def_count = $db->fetch_array($ret_def_count))
			{
			?>
				<option value="<?php echo $row_def_count['country_id']?>" <?php echo ($def_set_val==$row_def_count['country_id'])?'selected="selected"':''?>><?php echo stripslashes($row_def_count['country_name'])?></option>
			<?php	
			}
		?>
			</select>
			<input type="button" name="set_default" value="Set Default" id="set_default" class="red" onclick="save_default_country('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" />
			&nbsp;&nbsp;
		<?php	
		}	
	  ?>	
	  
	  
        Change Active Status
          <select name="cbo_changehide" class="dropdown" id="cbo_changehide">
            <option value="1">Yes</option>
			<option value="0">No</option>
          </select>&nbsp;<input name="change_hide" type="button" class="red" id="change_hide" value="Change" onclick="call_ajax_changestatus('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_COUNTRY_CHSTATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
		<?
		}
		?>
   	   </td>
    </tr>
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
	   $sql_user = "SELECT country_id,country_name,country_hide,country_numeric_code,country_code FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
	   
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
          <td align="left" valign="middle" class="<?=$class_val;?>"  width="5%"><input name="checkbox[]" value="<? echo $row['country_id']?>" type="checkbox"></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"  width="2%"><?php echo $srno++?></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><a href="home.php?request=general_settings_country&fpurpose=edit&checkbox[0]=<?php echo $row['country_id']?>&search_name=<?php echo $_REQUEST['search_name']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_order=<?=$_REQUEST['sort_order']?>&sort_by=<?=$_REQUEST['sort_by']?>" title="<? echo $row['country_name']?>" class="edittextlink" onclick="show_processing()"><? echo $row['country_name']?></a></td>
           <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo $row['country_code'] ?></td>
           <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo $row['country_numeric_code'] ?></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo ($row['country_hide'] == 1)?'Yes':'No'; ?></td>
         
        </tr>
      <?
	  }
	  }
	  else
	  {
	  ?>
	  		<tr>
		  		<td align="center" valign="middle" class="norecordredtext" colspan="6" >
		  		No Country exists.
		  		</td>
			</tr>
		<?
		}
		?>	
		<tr>
      <td class="listeditd" align="left" valign="middle" colspan="3"><a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=general_settings_country&fpurpose=add&records_per_page=<?=$records_per_page?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$start?>&pg=<?=$pg?>" class="addlist" onclick="show_processing()">Add</a> 
	  <?
	  if($numcount)
	  {
	  ?>
	  <a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
	  <?
	  }
	  ?>
	  </td>
	   <td class="listeditd"  align="right" valign="middle" colspan="3">
	 </td>
    </tr>
      </table>
	  </div></td>
    </tr>
	<tr>
      
	   <td class="listing_bottom_paging"  align="right" valign="middle" colspan="2">
	  <?
	  if($numcount)
	  {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?></td>
    </tr>
    </table>
</form>
