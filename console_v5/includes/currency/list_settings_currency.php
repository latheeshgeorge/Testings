<?php
	/*#################################################################
	# Script Name 	: list_settings_currencies.php
	# Description 	: Page for listing Curriencies avilable in the site
	# Coded by 		: ANU
	# Created on	: 15-June-2007
	# Modified by	: Sny
	# Modified On	: 05-Jun-2008
	#################################################################*/
//Define constants for this page
$table_name='general_settings_site_currency';
$page_type='General Settings Currency';
$help_msg = get_help_messages('LIST_CURENCY_MESS1');
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistSettingsCurrencies,\'currency_id[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistSettingsCurrencies,\'currency_id[]\')"/>','Slno.','Currency Name','Currency Symbol','Currency Code','Current Rate','Margin for Rate','Numeric Code','Default Currency');
$header_positions=array('center','left','left','left','left','left','left','left','center');
$colspan = count($table_headers);

//#Search terms
$search_fields = array('currency_name');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing search terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'curr_name':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('curr_name' => 'Currency Name');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
if($_REQUEST['currency_name']) {
	$where_conditions .= "AND  curr_name LIKE '%".add_slash($_REQUEST['currency_name'])."%' ";
}

// check whether currency rates to be picked automatically or not
$pick_automatically = get_general_settings('pick_currency_rate_automatically');
if($pick_automatically['pick_currency_rate_automatically']==0)
	$rate_msg = get_help_messages('LIST_CURENCY_SAVE_RATE');
else
	$rate_msg = get_help_messages('LIST_CURENCY_FETCH_RATE');

// get the id of default currency for current site
$sql_def = "SELECT currency_id FROM general_settings_site_currency WHERE sites_site_id=$ecom_siteid AND curr_default = 1";
$ret_def = $db->query($sql_def);
if($db->num_rows($ret_def))
{
	$row_def  	= $db->fetch_array($ret_def);
	$def_id		= $row_def['currency_id'];
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
$totcount	= $numcount;	// total number of records 
*//////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////For paging///////////////////////////////////////////
$records_per_page = (is_numeric($_REQUEST['records_per_page']) &&($_REQUEST['records_per_page']))?$_REQUEST['records_per_page']:10;#Total records shown in a page
$pg= ($_REQUEST['search_click']==1)?1:$_REQUEST['pg'];
if (!($pg > 0) || $pg == 0) { $pg = 1; }
$start = ($pg - 1) * $records_per_page;#Starting record.
$pages = ceil($numcount / $records_per_page);#Getting the total pages
if($pg > $pages) {
	$pg = $pages;
}
$count_no = ($pg == 1)?0:$records_per_page*($pg-1);
/////////////////////////////////////////////////////////////////////////////////////
//$query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=general_settings&fpurpose=captions&records_per_page=$records_per_page&start=$start";
$query_string .= "request=general_settings_currency&sort_by=$sort_by&sort_order=$sort_order";
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
<script  type="text/javascript">
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
	
	len=document.frmlistSettingsCurrencies.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistSettingsCurrencies.elements[j]
		if (el!=null && el.name== "currency_id[]" )
		   if(el.checked) {
		   		cnt++;
				general_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one Currency ');
		return false;
		
	}
	else if(cnt>1 ){
		alert('Please select only one Currency to edit');
		return false;
	}
	else
	{
		document.frmlistSettingsCurrencies.fpurpose.value=mode;
		document.frmlistSettingsCurrencies.submit();
	}
	
	
}

function getSelected(purpose){
		document.frmlistSettingsCurrencies.fpurpose.value=purpose;
		document.frmlistSettingsCurrencies.submit();
}
function call_ajax_ChangeDefault(currency_name,sortby,sortorder,recs,start,pg)
{
	var new_default_id 		= '';
	var rate_arr 					= '';
	var margin_arr				= '';
	var not_numeric				= false;
	var pick_automatic			= '<?php echo $pick_automatically['pick_currency_rate_automatically']?>';
	var msg							= '';
	var fpurpose					= '';
	if(document.frmlistSettingsCurrencies.pick_currency_rate_automatically.checked==true)
	var curr_auto_set =1;
	else
	{
	var curr_auto_set =0;
	}
	
	if(document.getElementById('mainalert_tr'))
		document.getElementById('mainalert_tr').style.display = 'none';
	if (curr_auto_set==0)
	{
		for(i=0;i<document.frmlistSettingsCurrencies.elements.length;i++)
		{
			if (document.frmlistSettingsCurrencies.elements[i].type =='radio' && document.frmlistSettingsCurrencies.elements[i].name=='curr_default')
			{	
	
				if (document.frmlistSettingsCurrencies.elements[i].checked==true)
				{
					
					 new_default_id = document.frmlistSettingsCurrencies.elements[i].value;
					 
				}	
			}
			else if(document.frmlistSettingsCurrencies.elements[i].type=='text')
			{
				if(document.frmlistSettingsCurrencies.elements[i].name.substr(0,8)=='txtrate_')
				{
				if(rate_arr!='')
					rate_arr += '~';
				id_arr = document.frmlistSettingsCurrencies.elements[i].name.split('_');
				if(document.frmlistSettingsCurrencies.elements[i].value!='')
				{
					if(isNaN(document.frmlistSettingsCurrencies.elements[i].value))
					{
						alert('Currency rate should be numeric');
						document.frmlistSettingsCurrencies.elements[i].focus();
						return false;
					}
					if(document.frmlistSettingsCurrencies.elements[i].value<0)
					{
						alert('Currency rate should be positive');
						document.frmlistSettingsCurrencies.elements[i].focus();
						return false;
					}		
				}
				rate_arr += id_arr[1]+','+document.frmlistSettingsCurrencies.elements[i].value;
				}
				if(document.frmlistSettingsCurrencies.elements[i].name.substr(0,10)=='txtmargin_')
				{
					if(document.frmlistSettingsCurrencies.elements[i].value!='')
					{
						if(isNaN(document.frmlistSettingsCurrencies.elements[i].value))
						{
							alert('Margin for rate should be numeric');
							document.frmlistSettingsCurrencies.elements[i].focus();
							return false;
						}
						if(document.frmlistSettingsCurrencies.elements[i].value<0)
						{
							alert('Margin for rate should be positive');
							document.frmlistSettingsCurrencies.elements[i].focus();
							return false;
						}		
					}
				}
				
			}
		}
		fpurpose	= 'Change_Default_Currency';
		var qrystr 	= 'pick_currency_rate_automatically='+curr_auto_set+'&rate_str='+rate_arr+'&currency_name='+currency_name+'&curr_default='+new_default_id+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;
		msg 			= 'Are you sure you want to Save the details?';
	}
	else
	{
		var qrystr 	= 'pick_currency_rate_automatically='+curr_auto_set+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&currency_name='+currency_name;
		if(document.getElementById('save_det_clicked').value!=1)
		{
			msg 			= 'Live rate fetching process will take some time.\n\nAre you sure you want to get the Live Currency rates?';
		 	fpurpose	= 'Get_Currency_Rates';
		}
		else
		{
			msg 			= 'Are you sure you want to save the details?';
		 	fpurpose	= 'Change_Default_Currency';
		}	
	}
	for(i=0;i<document.frmlistSettingsCurrencies.elements.length;i++)
	{
		if(document.frmlistSettingsCurrencies.elements[i].type=='text' && document.frmlistSettingsCurrencies.elements[i].name.substr(0,10)=='txtmargin_')
		{
			if(margin_arr!='')
				margin_arr += '~';
			id_arr = document.frmlistSettingsCurrencies.elements[i].name.split('_');
			if(document.frmlistSettingsCurrencies.elements[i].value!='')
			{
				if(isNaN(document.frmlistSettingsCurrencies.elements[i].value))
				{
					alert('Currency margin should be numeric');
					document.frmlistSettingsCurrencies.elements[i].focus();
					return false;
				}
				if(document.frmlistSettingsCurrencies.elements[i].value<0)
				{
					alert('Margin for rate should be positive');
					document.frmlistSettingsCurrencies.elements[i].focus();
					return false;
				}		
			}
			margin_arr += id_arr[1]+','+document.frmlistSettingsCurrencies.elements[i].value;
		}
	}
	qrystr		+= '&margin_str='+margin_arr;
	if(confirm(msg))
	{
		show_processing();
		Handlewith_Ajax('services/settings_currency.php','fpurpose='+fpurpose+'&'+qrystr);
	}
		
}
function checkDelete(){
len=document.frmlistSettingsCurrencies.length;
	var cnt=0;	
	var def=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistSettingsCurrencies.elements[j]
		if (el!=null && el.name== "currency_id[]" )
		   if(el.checked) {
		   		cnt++;
				currency_id=el.value;
				//alert(currency_id);
		   }
		   
	}
	if(cnt==0) {
		alert('Please select atleast one Currency to delete ');
		return false;
		
	}
	
if(confirm('Are you sure you want to delete the currency?')){
getSelected('delete_currency');
}else{
return false;
}
}
function call_ajax_delete(currency_name,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var del_ids 			= '';
	var qrystr				= 'currency_name='+currency_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlistSettingsCurrencies.elements.length;i++)
	{
		if (document.frmlistSettingsCurrencies.elements[i].type =='checkbox' && document.frmlistSettingsCurrencies.elements[i].name=='currency_id[]')
		{

			if (document.frmlistSettingsCurrencies.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmlistSettingsCurrencies.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select the Currency to delete');
	}
	else
	{
		if(confirm('Are you sure you want to delete selected Currency?'))
		{
			show_processing();
			Handlewith_Ajax('services/settings_currency.php','fpurpose=delete_currency&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
function auto_save()
{
	if(document.frmlistSettingsCurrencies.pick_currency_rate_automatically.checked==true)
	{ 
	document.getElementById('auto_save_div').style.display = '';
	}
	else
	{		document.getElementById('auto_save_div').style.display = 'none';
	}

}
</script>
<form name="frmlistSettingsCurrencies" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="request" value="general_settings_currency" />
<input type="hidden" name="start" value="<?=$start?>" />
<input type="hidden" name="pg" value="<?=$pg?>" />
<input type="hidden" name="search_click" value="" />
<input type="hidden" name="save_det_clicked" id="save_det_clicked" value="" />

  <table border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span> List Settings Curencies</span></div></td>
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
          			<td  colspan="" align="center" valign="middle" class="errormsg" id="mainalert_tr"><?php echo $alert?></td>
          		</tr>
		 <?php
		 	}
		 ?> 
    <?php
		if($numcount>0)
		{ 
	?>
	<tr><td colspan="3" align="right" valign="middle" class="sorttd"><?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?></td></tr>
	<?php
		}
	?>
	<tr>
      <td height="48" colspan="3" class="sorttd">
	  <div class="sorttd_div" >
      	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttabletop">
		
        <tr>
          <td width="11%"   align="left" valign="middle">Currency Name </td>
          <td width="21%" align="left" valign="middle"><input name="currency_name" type="text" class="textfeild" id="currency_name" value="<?=$_REQUEST['currency_name']?>" /></td>
          <td width="11%" align="left" valign="middle">Records Per Page </td>
          <td width="15%" align="left" valign="middle"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" value="<?=$records_per_page?>" /></td>
          <td width="6%" align="left" valign="middle">Sort By</td>
          <td width="27%" align="left" valign="middle"><?=$sort_option_txt?>&nbsp;&nbsp;&nbsp;in&nbsp;&nbsp;&nbsp;<?=$sort_by_txt?>
&nbsp;</td>
          <td width="9%" align="right" valign="middle"><input name="go_button" type="button" class="red" id="go_button" value="Go" onclick="document.frmlistSettingsCurrencies.search_click.value=1;getSelected('')" />
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_CURENCY_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          </tr>
      </table>
      </div></td>
    </tr>
	
    <tr>
      <td colspan="3" class="listingarea">
	  <div class="listingarea_div" >
	  <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="listingtable">
	  
	  
	  <? if($numcount>0)
	  {
	?>
	<tr>
	<td align="right" valign="middle" class="listeditd"  colspan="9" ><input type="checkbox" name="pick_currency_rate_automatically" value="1" <?php echo($pick_automatically['pick_currency_rate_automatically'] == 1)?"checked":"";?> onclick="auto_save()"/>
       Fetch the currency rates automatically <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('AUTO_SET_RATE_CURRENCY')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>

	</tr>

    <tr>
      <td class="listeditd" align="left" valign="middle" colspan="5"><a href="home.php?request=general_settings_currency&fpurpose=add_currency&currency_name=<?=$_REQUEST['currency_name']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>" class="addlist">Add</a> <a href="#" onclick="return edit_selected('edit_currency')" class="editlist">Edit</a><a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['currency_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a> </td>
      <td class="listeditd" align="right" valign="middle" colspan="4"> 
	  <?php
	  	/*if($pick_automatically['pick_currency_rate_automatically']==1)
		{*/
		?>
	  		<div style="float:left;width:50%"> <input name="Change_Default" type="button" class="red" id="save_Det" value="Save Details" onclick="document.getElementById('save_det_clicked').value=1;call_ajax_ChangeDefault('<?=$_REQUEST['currency_name']?>','<?=$sort_by?>','<?=$sort_order?>','<?=$records_per_page?>','<?=$start?>',<?=$pg?>)"/>
			 &nbsp;&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_CURENCY_SAVE_MARGIN');?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></div>
	  <?php
	  	/*}*/
	  ?>
	   <?php
	  	if($pick_automatically['pick_currency_rate_automatically']==1)
		{
		 $display = '';
		 }
		 else
		  $display = 'none';
		  ?>
      <!--<input name="Change_Default" type="button" class="red" id="Change_Default" value="Update" onclick="getSelected('Change_Default_Currency')"/>-->
	 <div id="auto_save_div" style="display:<?=$display?>;float:right;width:50%"> <input name="Change_Default" type="button" class="red" id="Change_Default" value="<?php  echo 'Get Live Currency Rates'; /*else { echo 'Get Live Currency Rates';}*/?>" onclick="call_ajax_ChangeDefault('<?=$currency_name?>','<?=$sort_by?>','<?=$sort_order?>','<?=$records_per_page?>','<?=$start?>',<?=$pg?>)"/>
      &nbsp;&nbsp;<a href="#" onmouseover ="ddrivetip('<?=$rate_msg?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></div>
		
	  </td>
    </tr>
	<? }?>
	  
	  
	  
        <?
		
	   	echo table_header($table_headers,$header_positions);
		$sql_settings_currency = "SELECT currency_id,curr_name,curr_sign,curr_code,curr_default,curr_numeric_code,curr_rate,curr_margin FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
	  
	   $res = $db->query($sql_settings_currency); 
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
        <tr>
          <td  width="5%" align="left" valign="middle" class="<?=$class_val;?>">
		  <input name="currency_id[]" value="<? echo $row['currency_id']?>" type="checkbox" /></td>
          <td width="2%" align="left" valign="middle"  class="<?=$class_val;?>"><?=$count_no?></td>
          <td  align="left" valign="middle" class="<?=$class_val;?>"><a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=general_settings_currency&fpurpose=edit_currency&currency_id=<?=$row['currency_id']?>&currency_name=<?php echo $_REQUEST['currency_name']?>&records_per_page=<?=$records_per_page?>&start=<?=$start?>&pg=<?=$pg?>" class="edittextlink"><? echo $row['curr_name']?></a></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><? echo $row['curr_sign']?></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><? echo $row['curr_code']?></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>">
		  <?php
		  	if($pick_automatically['pick_currency_rate_automatically']==0) // Show the rate checkbox only if rate is not picked automatically
			{
				if ($row['currency_id']!=$def_id)
				{
			?>
		  		<input type="text" name="txtrate_<? echo $row['currency_id']?>" id="txtrate_<? echo $row['currency_id']?>" value="<? echo $row['curr_rate']?>" size="7" />
			<?php
				}
				else
				{
			?>
					<input type="hidden" name="txtrate_<? echo $row['currency_id']?>" id="txtrate_<? echo $row['currency_id']?>" value="<? echo $row['curr_rate']?>" size="7" />
		  <?php
		  				echo $row['curr_rate'];
		  		}
		  	}
			else
				echo $row['curr_rate'];
		  ?>
		  
		  </td>
		  <td align="left" valign="middle" class="<?=$class_val;?>">
		  <?php
		  	if ($row['currency_id']!=$def_id)
			{
			?>
			  <input type="text" name="txtmargin_<?php echo $row['currency_id']?>" id="txtmargin_<?php echo $row['currency_id']?>" value="<? echo $row['curr_margin']?>"  size="5"/>
		 <?php
		 	}
			else
			{
				echo $row['curr_margin'];
		?>
			<input type="hidden" name="txtmargin_<?php echo $row['currency_id']?>" id="txtmargin_<?php echo $row['currency_id']?>" value="<? echo $row['curr_margin']?>"  size="5"/>
		<?php	
			}
		 ?>	  
		    </td>
		   <td align="left" valign="middle" class="<?=$class_val;?>"><? echo $row['curr_numeric_code']?></td>
          <td align="center" valign="middle" class="<?=$class_val;?>">
			  <?php 
				  /*<input name="curr_default" id="curr_default" type="radio" <?php echo($row['curr_default']==1)?'checked':'';?> value="<? echo $row['currency_id']?>"  />*/
				  echo($row['curr_default']==1)?'Yes':'No';
			  ?>
		  </td>
        </tr>
        <?
	  }
	}
	  else
		{
	?>
        <tr>
          <td align="center" valign="middle" class="norecordredtext" colspan="<?php echo $colspan?>"> No Currency exists for this site. </td>
        </tr>
        <?php
		}
		if($numcount>0)
	  {
	?>
    <tr>
      <td class="listeditd" align="left" valign="middle" colspan="5"><a href="home.php?request=general_settings_currency&fpurpose=add_currency&currency_name=<?=$_REQUEST['currency_name']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>" class="addlist">Add</a> <a href="#" onclick="return edit_selected('edit_currency')" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['currency_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a></td>
      <td class="listeditd" align="right" valign="middle" colspan="4"></td>
    </tr>
	<? }?>
      </table>
	  </div></td>
    </tr>
    <tr>

      <td class="listing_bottom_paging" align="right" valign="middle" colspan="2"><?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?></td>
    </tr>
    </table>
</form>
