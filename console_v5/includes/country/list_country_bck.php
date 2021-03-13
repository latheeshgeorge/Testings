<?php
	/*#################################################################
	# Script Name 	: list_country.php
	# Description 	: Page for listing Site Country
	# Coded by 		: SKR
	# Created on	: 15-June-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
//Define constants for this page
$table_name='general_settings_site_country';
$page_type='Country';
$help_msg = 'This section lists the Countries available on the site. Here there is provision for adding a Country, editing, & deleting it.';
$table_headers = array('','Country Name','Active');
$header_positions=array('left','left','left');
$colspan = count($table_headers);

//#Search terms
$search_fields = array('country_name');
foreach($search_fields as $v) {
	$query_string .= "$v=${$v}&";#For passing searh terms to javascript for passing to different pages.
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
$pg= $_REQUEST['pg'];
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
function call_ajax_delete()
{
	var atleastone 	= 0;
	var del_ids 	= '';
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
			Handlewith_Ajax('services/countries.php','fpurpose=delete&del_ids='+del_ids);
		}	
	}	
}
</script>
<form name="frmlistCountry" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="request" value="general_settings_country" />
<input type="hidden" name="start" value="<?=$start?>" />
  <table border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td colspan="2" align="left" valign="middle" class="treemenutd"><a href="home.php?request=general_settings">General Settings</a> &gt;&gt; List Country </td>
    </tr>
    <tr>
      <td height="48" class="sorttd" colspan="2" >
      	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttableleft">
        <tr>
          <td width="22%" align="left" valign="middle">Country Name </td>
          <td colspan="3" align="left" valign="middle"><input name="search_name" type="text" class="textfeild" id="search_name" value="<?=$_REQUEST['search_name']?>"  /></td>
          </tr>
      
      </table>
      <table width="18%" border="0" cellpadding="0" cellspacing="0" class="sorttableright">
        <tr>
          <td width="12%" align="left">Show</td>
          <td width="41%" align="left"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/>
            <?=$page_type?> Per Page</td>
          <td width="47%" align="left">&nbsp;</td>
        </tr>
        <tr>
          <td align="left">Sort By</td>
          <td align="left" nowrap="nowrap"><?=$sort_option_txt?> in <?=$sort_by_txt?>  </td>
          <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;<input name="button5" type="submit" class="red" id="button5" value="Go" /> <a href="#" onmouseover ="ddrivetip('Use \'Go\' button to search for countries.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
      </table>
	  </td>
    </tr>
    <tr>
      <td class="listeditd"><a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=general_settings_country&fpurpose=add&records_per_page=<?=$records_per_page?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$start?>" class="addlist" onclick="show_processing()">Add</a> 
	  <?
	  if($numcount)
	  {
	  ?>
	  <a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="#" onclick="javascript:call_ajax_delete()" class="deletelist">Delete</a>
	  <?
	  }
	  ?>
	  </td>
	   <td class="listeditd" align="right">
	  <?
	  if($numcount)
	  {
	  ?>
        Change Hide Status
          <select name="cmbstatus" class="dropdown" id="cmbstatus">
            <option value="1">Yes</option>
			<option value="0">No</option>
          </select>&nbsp;<input name="Update_Status" type="submit" class="red" id="button4" value="Change" />
		  <a href="#" onmouseover ="ddrivetip('Use \'Change\' button to change the hide status of countries. Select the hide status in the drop down, mark the countries to be changed and press \'Change\' button.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
		<?
		}
		?>
   	   </td>
    </tr>
     <?
	  if($numcount)
	  {
	  ?>
    <tr>
			  <td colspan="2" align="right" valign="middle" class="listnavtd">
				<table width="100%" border="0" cellpadding="0" cellspacing="0" class="listnavtdtable">
				<tr>
				  <td align="center">
						  <?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?></td>
				</tr>
			  </table></td>
			</tr>
	<?
	}
	?>
    <tr>
      <td colspan="2" class="listingarea">
      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable">
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
	   $sql_user = "SELECT country_id,country_name,country_hide FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
	   
	   $res = $db->query($sql_user); 
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
          <td align="left" valign="middle" class="<?=$class_val;?>"><input name="checkbox[]" value="<? echo $row['country_id']?>" type="checkbox"></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><? echo $row['country_name']?></td>
        
          <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo ($row['country_hide'] == 1)?'Yes':'No'; ?></td>
         
        </tr>
      <?
	  }
	  }
	  else
	  {
	  ?>
	  <tr>
				  <td align="center" valign="middle" class="norecordredtext" >
				  	No Country exists.				  </td>
			</tr>
		<?
		}
		?>	
      </table></td>
    </tr>
	<?
	if($numcount)
	{
	?>
   <tr>
			  <td colspan="2" align="right" valign="middle" class="listnavtd">
				<table width="100%" border="0" cellpadding="0" cellspacing="0" class="listnavtdtable">
				<tr>
				  <td align="center">
						  <?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?></td>
				</tr>
			  </table></td>
			</tr>
	<?
	}
	?>
	
    <tr>
      <td colspan="2" class="listeditd"><a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=general_settings_country&fpurpose=add&records_per_page=<?=$records_per_page?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$start?>" class="addlist">Add</a> 
	  <?
	  if($numcount)
	  {
	  ?>
	  <a href="#" onclick="edit_selected('edit')" class="editlist">Edit</a> <a href="#" onclick="javascript:call_ajax_delete()" class="deletelist">Delete</a>
	  <?
	  }
	  ?>
	  </td>
    </tr>
    </table>
</form>
