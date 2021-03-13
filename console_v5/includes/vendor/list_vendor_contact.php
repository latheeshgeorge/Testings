<?php
	/*#################################################################
	# Script Name 	: list_vendor_contact.php
	# Description 	: Page for listing Vendor Contacts
	# Coded by 		: SKR
	# Created on	: 21-June-2007
	# Modified by	: SKR
	# Modified On	: 25-June-2007
	#################################################################*/
//Define constants for this page
$table_name='product_vendor_contacts';
$page_type='Vendor Contact';
$help_msg = get_help_messages('LIST_PROD_VENDOR_CONTACTS_MESS1'); 	
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistContact,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistContact,\'checkbox[]\')"/>','Slno.','Name','Email','Order','Position');
$header_positions=array('left','left','left','left','left','left');
$colspan = count($table_headers);

//#Search terms
$search_fields = array('contact_name');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'contact_name':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('contact_name' => 'Contact Name','contact_sortorder'=>'Order');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE product_vendors_vendor_id=".$_REQUEST['vendor_id'];
if($_REQUEST['search_name']) {
	$where_conditions .= " AND ( contact_name LIKE '%".add_slash($_REQUEST['search_name'])."%')";
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
$query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=prod_vendor&fpurpose=list_contact&records_per_page=$records_per_page&search_name=".$_REQUEST['search_name']."&start=$start&vendor_id=".$_REQUEST['vendor_id']."&pass_sort_by=".$_REQUEST['pass_sort_by']."&pass_sort_order=".$_REQUEST['pass_sort_order']."&pass_records_per_page=".$_REQUEST['pass_records_per_page']."&pass_search_name=".$_REQUEST['pass_search_name']."&pass_start=".$_REQUEST['start']."&pass_pg=".$_REQUEST['pass_pg'];
?>
<script language="javascript">
function checkSelected()
{
	len=document.frmlistContact.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistContact.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
				user_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one contact ');
		return false;
	}
	show_processing();
	return true;
}
function edit_selected()
{
	
	len=document.frmlistContact.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistContact.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
				vendor_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one contact ');
	}
	else if(cnt>1 ){
		alert('Please select only one contact to edit');
	}
	else
	{
		show_processing();
		document.frmlistContact.fpurpose.value='edit_contact';
		document.frmlistContact.submit();
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
function call_ajax_delete(search_name,sortby,sortorder,recs,start,pg,vendor_id,pass_search_name,pass_sortby,pass_sortorder,pass_recs,pass_start,pass_pg)
{
	var atleastone 	= 0;
	var del_ids 	= '';
	var qrystr				= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&vendor_id='+vendor_id+'&pass_search_name='+pass_search_name+'&pass_sort_by='+pass_sortby+'&pass_sort_order='+pass_sortorder+'&pass_records_per_page='+pass_recs+'&pass_start='+pass_start+'&pass_pg='+pass_pg;;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlistContact.elements.length;i++)
	{
		if (document.frmlistContact.elements[i].type =='checkbox' && document.frmlistContact.elements[i].name=='checkbox[]')
		{

			if (document.frmlistContact.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmlistContact.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select contcat to delete');
	}
	else
	{
		if(confirm('Are you sure you want to delete selected Contact?'))
		{
			show_processing();
			Handlewith_Ajax('services/prod_vendor.php','fpurpose=delete_contact&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
function SavesortOrder(search_name,sortby,sortorder,recs,start,pg,vendor_id,pass_search_name,pass_sortby,pass_sortorder,pass_recs,pass_start,pass_pg)
{
var qrystr				= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&vendor_id='+vendor_id+'&pass_search_name='+pass_search_name+'&pass_sort_by='+pass_sortby+'&pass_sort_order='+pass_sortorder+'&pass_records_per_page='+pass_recs+'&pass_start='+pass_start+'&pass_pg='+pass_pg;;
var IdArr =new Array();
var OrderArr =new Array();
var j=0;
	for(i=0;i<document.frmlistContact.elements.length;i++)
	{
		if (document.frmlistContact.elements[i].type =='text' && document.frmlistContact.elements[i].name!='search_name' && document.frmlistContact.elements[i].name!='records_per_page')
		{
				
				 IdArr[j]    = document.frmlistContact.elements[i].name;
				 OrderArr[j] = document.frmlistContact.elements[i].value;
				 j=j+1;
		}
	}	
	var Idstr=IdArr.join('~');
	var OrderStr=OrderArr.join('~');
	if(confirm('Are you sure you want to Save order of contact?'))
		{
		show_processing();
		Handlewith_Ajax('services/prod_vendor.php','fpurpose=save_order&Idstr='+Idstr+'&OrderStr='+OrderStr+'&'+qrystr);
		}

}
</script>
<form name="frmlistContact" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="list_contact" />
<input type="hidden" name="request" value="prod_vendor" />
<input type="hidden" name="start" value="<?=$start?>" />
<input type="hidden" name="pg" value="<?=$pg?>" />
<input type="hidden" name="vendor_id" value="<?=$_REQUEST['vendor_id']?>" />
<input type="hidden" name="pass_sort_by" value="<?=$_REQUEST['pass_sort_by']?>" />
<input type="hidden" name="pass_sort_order" value="<?=$_REQUEST['pass_sort_order']?>" />
<input type="hidden" name="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
<input type="hidden" name="pass_search_name" value="<?=$_REQUEST['pass_search_name']?>" />
<input type="hidden" name="pass_start" value="<?=$_REQUEST['pass_start']?>" />
<input type="hidden" name="pass_pg" value="<?=$_REQUEST['pass_pg']?>" />
  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td align="left" valign="middle" class="treemenutd" colspan="3"><div class="treemenutd_div"><a href="home.php?request=prod_vendor&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&search_name=<?=$_REQUEST['pass_search_name']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>">Product Vendors</a> <span> List Contacts of
	  &quot;<?
	  $sql_vedor="SELECT vendor_name FROM product_vendors WHERE vendor_id=".$_REQUEST['vendor_id']." AND sites_site_id=$ecom_siteid";
	  $res_vedor = $db->query($sql_vedor); 
	  $row_vedor = $db->fetch_array($res_vedor);
	  echo "<b>".$row_vedor['vendor_name']."</b>";
	  ?>&quot;</span></div></td>
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
		  <td class="sorttd"  align="right" colspan="3" >
	 <?
	  if($numcount)
	  {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?>		</td>
	  </tr>
    <tr>
      <td height="48" class="sorttd" colspan="3">
		  		  		  <div class="editarea_div">

      	<table width="100%" border="0" cellpadding="0" cellspacing="0" >
        <tr>
          <td  align="left" valign="middle">Contact Name </td>
          <td  align="left" valign="middle"><input name="search_name" type="text" class="textfeild" id="search_name" value="<?=$_REQUEST['search_name']?>"  /></td>          
          <td  align="left">Show</td>
          <td  align="left"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/>
            <?=$page_type?> Per Page</td>
        
          <td align="left">Sort By</td>
          <td align="left" nowrap="nowrap"><?=$sort_option_txt?>
            in <?=$sort_by_txt?>		   </td>
          <td align="left">&nbsp;<input name="button5" type="submit" class="red" id="button5" value="Go" /> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_VENDOR_CONTACTS_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
      </table>	 </div> </td>
    </tr>
     <td height="48" class="sorttd" colspan="3">
		  		  <div class="editarea_div">
      	<table width="100%" border="0" cellpadding="0" cellspacing="0" >
    <tr>
      <td class="listeditd" width="30%"><a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=prod_vendor&fpurpose=add_contact&records_per_page=<?=$records_per_page?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$start?>&pg=<?=$pg?>&vendor_id=<?=$_REQUEST['vendor_id']?>&pass_sort_by=<?=$_REQUEST['sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_start=<?=$_REQUEST['pass_start']?>&pass_pg=<?=$_REQUEST['pass_pg']?>" class="addlist" onclick="show_processing()">Add</a> 
	  <?
	  if($numcount)
	  {
	  ?>
	  <a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>','<?php echo $_REQUEST['vendor_id']?>','<?php echo $_REQUEST['pass_search_name']?>','<?php echo $_REQUEST['pass_sort_by']?>','<?php echo $_REQUEST['pass_sort_order']?>','<?php echo $_REQUEST['pass_records_per_page']?>','<?php echo $_REQUEST['pass_start']?>','<?php echo $_REQUEST['pass_pg']?>')" class="deletelist">Delete</a>
	  <?
	  }
	  ?>	  </td>
	   
      <td class="listeditd" align="right" colspan="2" >
        <?
	  if($numcount)
	  {
	  ?>
		<input name="button52" type="button" class="red" id="button52" value="Save Order" onclick="SavesortOrder('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>','<?php echo $_REQUEST['vendor_id']?>','<?php echo $_REQUEST['pass_search_name']?>','<?php echo $_REQUEST['pass_sort_by']?>','<?php echo $_REQUEST['pass_sort_order']?>','<?php echo $_REQUEST['pass_records_per_page']?>','<?php echo $_REQUEST['pass_start']?>','<?php echo $_REQUEST['pass_pg']?>')" />
      <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_VENDOR_CONTACT_SAVE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>   	 
	  <? }?>  </td>
    </tr>
    
  
    <tr>
      <td class="listingarea" colspan="3">
      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable" width="100%">
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
	   $sql_contact = "SELECT id,contact_name,contact_email,contact_position,contact_sortorder FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
	   
	   $res_contact = $db->query($sql_contact); 
	    $srno = 1;
	   while($row = $db->fetch_array($res_contact))
	   {
			$count_no++;
			$array_values = array();
			if($count_no %2 == 0)
				$class_val="listingtablestyleA";
			else
				$class_val="listingtablestyleB";	
	   
	   ?>
        <tr >
          <td align="left" valign="middle" class="<?=$class_val;?>" width="5%"><input name="checkbox[]" value="<? echo $row['id']?>" type="checkbox"></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>" width="2%"><?php echo $srno++?></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><a href="home.php?request=prod_vendor&fpurpose=edit_contact&checkbox[0]=<?php echo $row['id']?>&search_name=<?php echo $_REQUEST['search_name']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&vendor_id=<?=$_REQUEST['vendor_id']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_start=<?=$_REQUEST['pass_start']?>&pass_pg=<?=$_REQUEST['pass_pg']?>" title="edit" class="edittextlink" onclick="show_processing()"><? echo $row['contact_name']?></a></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"><? echo $row['contact_email']?></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"><input type="text" size="2" value="<? echo $row['contact_sortorder']?>" maxlength="4" name="<?=$row['id']?>"/></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"><? echo $row['contact_position']?></td>
        </tr>
      <?
	  }
	  }
	  else
	  {
	  ?>
	  <tr>
				  <td align="center" valign="middle" class="norecordredtext" colspan="6" >
				  	No Contact exists.</td>
		  </tr>
		<?
		}
		?>	
      </table></td>
    </tr>
	<tr>
      <td class="listeditd" width="30%" ><a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=prod_vendor&fpurpose=add_contact&records_per_page=<?=$records_per_page?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$start?>&pg=<?=$pg?>&vendor_id=<?=$_REQUEST['vendor_id']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_start=<?=$_REQUEST['pass_start']?>&pass_pg=<?=$_REQUEST['pass_pg']?>" class="addlist" onclick="show_processing()">Add</a> 
	  <?
	  if($numcount)
	  {
	  ?>
	  <a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>','<?php echo $_REQUEST['vendor_id']?>','<?php echo $_REQUEST['pass_search_name']?>','<?php echo $_REQUEST['pass_sort_by']?>','<?php echo $_REQUEST['pass_sort_order']?>','<?php echo $_REQUEST['pass_records_per_page']?>','<?php echo $_REQUEST['pass_start']?>','<?php echo $_REQUEST['pass_pg']?>')" class="deletelist">Delete</a>
	  <?
	  }
	  ?>	  </td>
	    <td class="listeditd" colspan="3" align="right">
	 <?
	  if($numcount)
	  {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?>		</td>
    </tr>
    </table>
    </div>
    </td>
    </tr>
  </table>
</form>
