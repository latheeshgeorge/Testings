<?php
	/*#################################################################
	# Script Name 	: list_shelf_selproduct.php
	# Description 	: Page for listing Products
	# Coded by 		: Sny
	# Created on	: 25-Feb-2011
	# Modified by	: 
	# Modified by	: LSH
	# Modified On	: 17-Jan-2012
	#################################################################*/
$table_name='general_settings_site_country';
$page_type='Countries';
$help_msg = get_help_messages('LIST_SHELVES_ASSIGN_PROD_MESS1');
//$where  = " WHERE sites_site_id = $ecom_siteid AND delivery_site_location_location_id=0";
if($ecom_site_delivery_location_country_map!=1)
	exit;

$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frm_countries,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frm_countries,\'checkbox[]\')"/>','Slno.','Country Name','Hidden?');
$header_positions=array('center','left','center');
$colspan = count($table_headers);
$search_fields = array('countryname');
$query_string = "request=delivery_settings&fpurpose=assign_country&deliveryid=".$_REQUEST['deliveryid']."&locationid=".$_REQUEST['locationid'].'&countryname='.$_REQUEST['countryname'];


//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'country_name':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('country_name' => 'Name');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
// Get the list of countries which are already assigned to current locationid
$sql_existing = "SELECT general_settings_site_country_country_id 
			FROM 
				general_settings_site_country_location_map 
			WHERE 
				delivery_methods_deliverymethod_id = ".$_REQUEST['deliveryid']." 
				AND sites_site_id = $ecom_siteid";
$ret_existing = $db->query($sql_existing);
$existing_arr = array(0);
if ($db->num_rows($ret_existing))
{
	while ($row_existing = $db->fetch_array($ret_existing))
	{
		$existing_arr[] = $row_existing['general_settings_site_country_country_id'];
	}
}
$where_conditions = "WHERE sites_site_id = $ecom_siteid AND country_id NOT IN (".implode(',',$existing_arr).") ";
// Product Name Condition
if($_REQUEST['countryname'])
{
	$where_conditions .= " AND ( country_name LIKE '%".add_slash($_REQUEST['countryname'])."%') ";
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
if ($pg>=1)
{
	$start = ($pg - 1) * $records_per_page;//#Starting record.
	$count_no = ($pg == 1)?0:$records_per_page*($pg-1);
}	
else
{
	$start = $count_no = 0;	
}

/////////////////////////////////////////////////////////////////////////////////////

$sql_qry = "SELECT * FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
//echo $sql_qry;
$ret_qry = $db->query($sql_qry);
?>
<script type="text/javascript">
function call_save_selected(cname,sortby,sortorder,recs,start,pg,group_id) 
{
	
	var atleastone 			= 0;
	for(i=0;i<document.frm_countries.elements.length;i++)
	{
		if (document.frm_countries.elements[i].type =='checkbox' && document.frm_countries.elements[i].name=='checkbox[]')
		{

			if (document.frm_countries.elements[i].checked==true)
			{
				atleastone ++;
				
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select the countries to assign');
	}
	
	else
	{
		if(confirm('Are you sure you want to assign selected Countries to the Current Location ?'))
		{
			show_processing();
			document.frm_countries.fpurpose.value='save_assign_country';
			document.frm_countries.submit();
		}	
	}	

}
</script>
<form method="post" name="frm_countries" class="frmcls" action="home.php">
<input type="hidden" name="request" value="delivery_settings" />
<input type="hidden" name="fpurpose" value="assign_country" />
<input type="hidden" name="search_click" value="" />
<input type="hidden" name="deliveryid" value="<?=$_REQUEST['deliveryid']?>" />
<input type="hidden" name="locationid" value="<?=$_REQUEST['locationid']?>" />
<?
$sql_shelf="SELECT location_name FROM delivery_site_location  WHERE location_id='".$_REQUEST['locationid']."' LIMIT 1";
$res_shelf= $db->query($sql_shelf);
$row_shelf = $db->fetch_array($res_shelf);

$sql_delname="SELECT deliverymethod_name FROM delivery_methods  WHERE deliverymethod_id='".$_REQUEST['deliveryid']."' LIMIT 1";
$res_delname = $db->query($sql_delname);
if($db->num_rows($res_delname))
{
	$row_delname 	= $db->fetch_array($res_delname);
	$deloption_name	= $row_delname['deliverymethod_name'];
}	
?>


<table border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td colspan="3" align="left" valign="middle" class="treemenutd"><a href="home.php?request=delivery_settings">Delivery Charges </a>&gt;&gt; <a href="home.php?request=delivery_settings&fpurpose=editdeliverylocation&deliveryid=<?php echo $_REQUEST['deliveryid']?>&location_id=<?php echo $_REQUEST['locationid']?>"><?php echo $deloption_name?> </a>&gt;&gt; Assign Countries for Location  '<? echo $row_shelf['location_name'];?>'</td>
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
      <td height="48" colspan="3" class="sorttd">

		<table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="66%" align="left" valign="top">
		  <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td width="27%" align="left">Country Name</td>
              <td width="34%" align="left"><input name="countryname" type="text" class="textfeild" id="countryname" value="<?php echo $_REQUEST['countryname']?>" /></td>
              <td width="14%" align="left">&nbsp;</td>
              <td width="25%" align="left">&nbsp;</td>
            </tr>
          </table>
          </td>
          <td width="34%" align="left" valign="top">
		  <table width="100%" height="56" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td align="left">Show
                <input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3" value="<?php echo $records_per_page?>" />
                <?php echo $page_type?> Per Page</td>
              <td width="23%" align="left">&nbsp;</td>
            </tr>
            <tr>
              <td align="left">Sort By&nbsp;<?php echo $sort_option_txt;?> in <?php echo $sort_by_txt?> </td>
              <td align="left"><input name="Search_go" type="submit" class="red" id="Search_go" value="Go" onClick="document.frm_countries.search_click.value=1" />
                <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_SHELVES_ASSIGN_PROD_GO')?>')"; onMouseOut="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
            </tr>
          </table>
		  </td>
        </tr>
      </table>
      </td>
    </tr>
    <tr>
      <td width="162" class="listeditd">&nbsp;</td>
      <td width="232" align="center" class="listeditd">
        <?php 
		if ($db->num_rows($ret_qry))
		{
			paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan);
		}
		?></td>
      <td width="317" align="right" class="listeditd">
	  <input name="change_hide" type="button" class="red" id="change_hide" onClick="call_save_selected()" value="Assign Selected">
	  </td>
    </tr>
    <tr>
      <td colspan="3" class="listingarea">
      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable">
	 <?php  
	 	echo table_header($table_headers,$header_positions); 
		if ($db->num_rows($ret_qry))
		{ 

			 $srno = getStartOfPageno($records_per_page,$pg);
			while ($row_qry = $db->fetch_array($ret_qry))
			{
				$cls 		= ($srno%2==0)?'listingtablestyleA':'listingtablestyleB';
	 ?>
			   	<tr>
				  <td align="left" valign="middle" class="<?php echo $cls?>" width="5%">
				  <input type="checkbox" name="checkbox[]" id="checkbox[]" value="<?php echo $row_qry['country_id']?>" /></td>
				  <td align="left" valign="middle" class="<?php echo $cls?>" width="1%"><?php echo $srno++?></td>
				  <td align="left" valign="middle" class="<?php echo $cls?>" width="20%"><a href="home.php?request=general_settings_country&fpurpose=edit&checkbox[0]=<?php echo $row_qry['country_id']?>" title="edit" class="edittextlink"><?php echo stripslashes($row_qry['country_name'])?></a></td>
				  <td align="center" valign="middle" class="<?php echo $cls?>">
				  	<?php
				  		echo ($row_qry['country_hide']==1)?'Yes':'No';	
					?>
				</td>
				</tr>
	<?php
			}
		}
		else
		{
	?>	
			<tr>
				  <td align="center" valign="middle" class="norecordredtext" colspan="<?php echo $colspan?>">
				  	No Countries found.				  </td>
			</tr>	  
	<?php
		}
	?>
      </table></td>
    </tr>
	<tr>
      <td class="listeditd">&nbsp; </td>
      <td width="232" align="center" class="listeditd">
	  <?php 
		if ($db->num_rows($ret_qry))
		{
			paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan);
		}
		?></td>
      <td class="listeditd">&nbsp;</td>
    </tr>
    </table>
</form>
