<?php
	/*#################################################################
	# Script Name 	: list_vendor.php
	# Description 	: Page for listing Product Vendors
	# Coded by 		: SKR
	# Created on	: 18-June-2007
	# Modified by	: SKR
	# Modified On	: 25-June-2007
	#################################################################*/
//Define constants for this page
$table_name='product_vendors';
$page_type='Product Vendor';
$help_msg = get_help_messages('LIST_PROD_VENDOR_MESS1'); 
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistVendor,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistVendor,\'checkbox[]\')"/>','Slno.','Name','Email','Website','Products','Hidden?','Contacts <a href="#" onmouseover ="ddrivetip(\''.get_help_messages('LIST_PROD_VENDOR_CONTACTS').'\')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>','Statistics<a href="#" onmouseover ="ddrivetip(\''.get_help_messages('LIST_PROD_VENDOR_STATISTICS_HEADING').'\')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>');
$header_positions=array('left','left','left','left','left','left','left','center','center');
$colspan = count($table_headers);

//#Search terms
$search_fields = array('vendor_name');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
//#Sort
$sort_options = array('vendor_name' => 'Vendor Name');
$sort_by = (!array_key_exists($_REQUEST['sort_by'],$sort_options))?'vendor_name':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];

$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
if($_REQUEST['search_name']) {
	$where_conditions .= "AND ( vendor_name LIKE '%".add_slash($_REQUEST['search_name'])."%')";
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
$query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=prod_vendor&records_per_page=$records_per_page&search_name=".$_REQUEST['search_name']."&start=$start&pg=".$_REQUEST['pg'];
?>
<script language="javascript">
function call_ajax_changestatus(search_name,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var curid				= 0;
	var vendor_ids 			= '';
	var cat_orders			= '';
	var ch_status			= document.frmlistVendor.cbo_changehide.value;
	var qrystr				= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&ch_status='+ch_status+'&start='+start+'&pg='+pg;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlistVendor.elements.length;i++)
	{
		if (document.frmlistVendor.elements[i].type =='checkbox' && document.frmlistVendor.elements[i].name=='checkbox[]')
		{

			if (document.frmlistVendor.elements[i].checked==true)
			{
				atleastone = 1;
				if (vendor_ids!='')
					vendor_ids += '~';
				 vendor_ids += document.frmlistVendor.elements[i].value;
			}	
		}
	}
	if (atleastone==0) 
	{
		alert('Please select the vendors to change the hide status');
	}
	else
	{
		if(confirm('Change Hide Status of Seleted Vendor(s)?'))
		{
				show_processing();
				Handlewith_Ajax('services/prod_vendor.php','fpurpose=change_hide&'+qrystr+'&vendor_ids='+vendor_ids);
		}	
	}	
}
function checkSelected()
{
	len=document.frmlistVendor.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistVendor.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
				user_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one vendor ');
		return false;
	}
	show_processing();
	return true;
}
function edit_selected()
{
	
	len=document.frmlistVendor.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistVendor.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
				vendor_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one vendor ');
	}
	else if(cnt>1 ){
		alert('Please select only one vendor to edit');
	}
	else
	{
		show_processing();
		document.frmlistVendor.fpurpose.value='edit';
		document.frmlistVendor.submit();
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
			//alert("Problem in requesting XML :"+req.statusText);
			 show_request_alert(req.status);
		}
	}
}
function call_ajax_delete(search_name,sortby,sortorder,recs,start,pg)
{
	var atleastone 	= 0;
	var del_ids 	= '';
	var qrystr				= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlistVendor.elements.length;i++)
	{
		if (document.frmlistVendor.elements[i].type =='checkbox' && document.frmlistVendor.elements[i].name=='checkbox[]')
		{

			if (document.frmlistVendor.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmlistVendor.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select vendor to delete');
	}
	else
	{
		if(confirm('Are you sure you want to delete selected Vendor?'))
		{
			show_processing();
			Handlewith_Ajax('services/prod_vendor.php','fpurpose=delete&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}

function prod_more() {

	var atleastone 	= 0;
	var vend_ids = '';
	for(i=0;i<document.frmlistVendor.elements.length;i++)
	{
		if (document.frmlistVendor.elements[i].type =='checkbox' && document.frmlistVendor.elements[i].name=='checkbox[]')
		{

			if (document.frmlistVendor.elements[i].checked==true)
			{
				atleastone = 1;
				if (vend_ids!='')
					vend_ids += '~';
				 vend_ids += document.frmlistVendor.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select vendor to assign products ');
	}
	else {
	document.frmlistVendor.fpurpose.value='list_assign_products';
	document.frmlistVendor.vendor_id.value=vend_ids;
	document.frmlistVendor.submit();
	}
	
}
</script>
<form name="frmlistVendor" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="request" value="prod_vendor" />
<input type="hidden" name="pass_start" value="<?=$start?>" />
<input type="hidden" name="pass_pg" value="<?=$pg?>" />
<input type="hidden" name="start" value="<?=$start?>" />
<input type="hidden" name="pg" value="<?=$pg?>" />
<input type="hidden" name="hid_vend_id" /> 
<input type="hidden" name="vendor_id" />
 <input type="hidden" name="pass_search_name" id="pass_search_name" value="<?=$_REQUEST['search_name']?>" />
 <input type="hidden" name="pass_sort_by" id="pass_sort_by" value="<?=$_REQUEST['sort_by']?>" />
  <input type="hidden" name="pass_sort_order" id="pass_sort_order" value="<?=$_REQUEST['sort_order']?>" />
  <input type="hidden" name="pass_records_per_page" id="pass_records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
  <table border="0" cellpadding="0" cellspacing="0" class="maintable" width="100%">
    <tr>
      <td align="left" valign="middle" class="treemenutd" colspan="4"><div class="treemenutd_div"><span>List Vendors</span></div></td>
    </tr>
	<tr>
	  <td align="left" valign="middle" class="helpmsgtd_main" colspan="5">
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
          			<td colspan="4" align="center" valign="middle" class="errormsg"><?php echo($alert);?></td>
          		</tr>
		 <?php
		 	}
		 ?>
		<?php
			if($numcount)
			{
		?> 
		<tr><td class="sorttd" colspan="4"  align="right"><?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?> </td></tr>
		<?php
			}
		?>
    <tr>
      <td height="48" class="sorttd" colspan="4">
		  		  <div class="sorttd_div">
      	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttabletop" >
        <tr>
          <td width="10%" height="30"  align="left" valign="middle">Vendor Name </td>
          <td width="17%" height="30" align="left" valign="middle"><input name="search_name" type="text" class="textfeild" id="search_name" value="<?=$_REQUEST['search_name']?>"  /></td>
         
          <td width="12%" height="30"  align="left" valign="middle">Records Per Page</td>
          <td width="11%" height="30"  align="left" valign="middle">
		  <input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/>            </td>
          <td width="7%" height="30" align="left" valign="middle">Sort By</td>
          <td width="22%" height="30" align="left" valign="middle" nowrap="nowrap"><?=$sort_option_txt?>&nbsp;&nbsp;&nbsp;in&nbsp;&nbsp;&nbsp;<?=$sort_by_txt?></td>
          <td width="21%" height="30" align="right" valign="middle">
            <input name="button5" type="submit" class="red" id="button5" value="Go" /> <a href="#" onmouseover ="ddrivetip('Use \'Go\' button to search for vendors.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          </tr>
      </table>	  
      </div></td>
    </tr>
     <tr>
      <td colspan="4" class="tdcolorgray">
		  <div class="listingarea_div">

      <table  border="0" cellpadding="0" cellspacing="0"  width="100%">
    <tr>
      <td class="listeditd" ><a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=prod_vendor&fpurpose=add&records_per_page=<?=$records_per_page?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$start?>&pg=<?=$_REQUEST['pg']?>" class="addlist" onclick="show_processing()">Add</a> 
	  <?
	  if($numcount)
	  {
	  ?>
	  <a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
	  <?
	  }
	  ?>	  </td>

	   <td class="listeditd" align="right"><?
	  if($numcount)
	  {
	  ?>
        Change Status
          <select name="cbo_changehide" class="dropdown" id="cbo_changehide">
            <option value="Y">Yes</option>
			<option value="N">No</option>
          </select>&nbsp;<input name="change_hide" type="button" class="red" id="change_hide" value="Change" onclick="call_ajax_changestatus('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_VENDOR_CHSTATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
		<?
		}
		?>   </td>
		<? 
	if($numcount)
	  {
		 $sql_checkprod = "SELECT 
		  							product_id 
							FROM 
									products 
							WHERE
									sites_site_id=$ecom_siteid"; 
		 $ret_check		=  $db->query($sql_checkprod);
	  }	
		?>
	   <td class="listeditd" align="center">
	   <? if($db->num_rows($ret_check)>0){?>
		<input name="more_hide" type="button" class="red" id="change_hide" value=" Assign More Products " onclick="prod_more()" /> 	
  		<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_VENDOR_PRODASSIAGN')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
       <? }?>
	  </td>
    </tr>
    <tr>
      <td class="listingarea" colspan="4">
      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable" width="100%">
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
	   $sql_vendor = "SELECT vendor_id,vendor_name,vendor_email,vendor_website,vendor_hide FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
	   
	   $res = $db->query($sql_vendor); 
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
          <td align="left" valign="middle" class="<?=$class_val;?>" width="5%" ><input name="checkbox[]" value="<? echo $row['vendor_id']?>" type="checkbox"></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>" width="2%"><?php echo $srno++?></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><a href="home.php?request=prod_vendor&fpurpose=edit&checkbox[0]=<?php echo $row['vendor_id']?>&search_name=<?php echo $_REQUEST['search_name']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>" title="<? echo $row['vendor_name']?>" class="edittextlink" onclick="show_processing()"><? echo $row['vendor_name']?></a></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"><? echo $row['vendor_email']?></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"><? echo $row['vendor_website']?></td>
		    <td align="left" valign="middle" class="<?=$class_val;?>">
			<?
			$sql_vend_prod="SELECT count(*) as num_product FROM product_vendor_map WHERE product_vendors_vendor_id=".$row['vendor_id'];
			$res_vend_prod = $db->query($sql_vend_prod);
			list($num_product) = $db->fetch_array($res_vend_prod);
			echo $num_product;
			?>
			
			&nbsp;</td>      
          <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo ($row['vendor_hide'] == 'Y')?'Yes':'No'; ?></td>
         <td align="center" valign="middle" class="<?=$class_val;?>">
		 <a href="home.php?pass_sort_by=<?=$sort_by?>&vendor_id=<? echo $row['vendor_id']?>&pass_sort_order=<?=$sort_order?>&request=prod_vendor&fpurpose=list_contact&pass_records_per_page=<?=$records_per_page?>&pass_search_name=<?=$_REQUEST['search_name']?>&pass_start=<?=$start?>&pass_pg=<?=$pg?>"  title="View Contacts"><img src="images/contacts.gif" border="0" title="View Contacts"/></a>		 </td>
		 <td align="center" valign="middle" class="<?=$class_val;?>"><input name="more_hide" type="button" class="red" id="change_hide" value=" Statistics " onclick="document.frmlistVendor.fpurpose.value='statistics'; document.frmlistVendor.vendor_id.value='<?php echo $row['vendor_id']; ?>';show_processing(); document.frmlistVendor.submit();" /></td>
        </tr>
      <?
	  }
	  }
	  else
	  {
	  ?>
	  <tr>
				  <td align="center" colspan="9" valign="middle" class="norecordredtext" >
				  	No Vendor exists.				  </td>
			</tr>
		<?
		}
		?>	
      </table></td>
    </tr>
	
	<tr>
      <td class="listeditd" ><a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=prod_vendor&fpurpose=add&records_per_page=<?=$records_per_page?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$start?>&pg=<?=$pg?>" class="addlist" onclick="show_processing()">Add</a> 
	  <?
	  if($numcount)
	  {
	  ?>
	  <a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
	  <?
	  }
	  ?>	  </td>
	  <td class="listeditd" align="right" colspan="3">
	    </td>
    </tr>
    </table>
    </div>
    </td>
    </tr>
	<tr>
	 <td class="listing_bottom_paging" align="right" colspan="2">
	    <?
	  if($numcount)
	  {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?>	  </td>
    </tr>
    </table>
</form>
