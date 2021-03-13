<?php
	/*#################################################################
	# Script Name 	: list_customer.php
	# Description 	: Page for listing Customer
	# Coded by 		: SKR
	# Created on	: 13-Aug-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
//Define constants for this page
$table_name='customers';
$page_type='Customer';
$help_msg = 'This section lists the Customers available on the site. Here there is provision for adding a Customer, editing, & deleting it.';
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistCustomer,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistCustomer,\'checkbox[]\')"/>','Slno.','Customer Name','Email Address','Phone','Bonus points','Discount','On Mailing List','Date Added','Hide');
$header_positions=array('left','left','left','left','left','left','left','left','left','left','left');
$colspan = count($table_headers);

//#Search terms
$search_fields = array('customer_fname');
foreach($search_fields as $v) {
	$query_string .= "$v=${$v}&";#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'customer_fname':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('customer_fname' => 'Customer Name','customer_email_7503' => 'Customer Email');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
if($_REQUEST['search_name']) {
	$where_conditions .= "AND ( customer_fname LIKE '%".add_slash($_REQUEST['search_name'])."%')";
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
$query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=customer&records_per_page=$records_per_page&search_name=".$_REQUEST['search_name']."&start=$start";
?>
<script language="javascript">
function call_ajax_changestatus(search_name,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var curid				= 0;
	var customer_ids 			= '';
	var cat_orders			= '';
	var ch_status			= document.frmlistCustomer.cbo_changehide.value;
	var qrystr				= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&ch_status='+ch_status+'&start='+start+'&pg='+pg;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlistCustomer.elements.length;i++)
	{
		if (document.frmlistCustomer.elements[i].type =='checkbox' && document.frmlistCustomer.elements[i].name=='checkbox[]')
		{

			if (document.frmlistCustomer.elements[i].checked==true)
			{
				atleastone = 1;
				if (customer_ids!='')
					customer_ids += '~';
				 customer_ids += document.frmlistCustomer.elements[i].value;
			}	
		}
	}
	if (atleastone==0) 
	{
		alert('Please select the customers to change the hide status');
	}
	else
	{
		if(confirm('Change Hide Status of Seleted Customer(s)?'))
		{
				show_processing();
				Handlewith_Ajax('services/customer.php','fpurpose=change_hide&'+qrystr+'&customer_ids='+customer_ids);
		}	
	}	
}

function edit_selected()
{
	
	len=document.frmlistCustomer.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistCustomer.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
				bow_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one Customer ');
	}
	else if(cnt>1 ){
		alert('Please select only one Customer to edit');
	}
	else
	{
		show_processing();
		document.frmlistCustomer.fpurpose.value='edit';
		document.frmlistCustomer.submit();
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
	for(i=0;i<document.frmlistCustomer.elements.length;i++)
	{
		if (document.frmlistCustomer.elements[i].type =='checkbox' && document.frmlistCustomer.elements[i].name=='checkbox[]')
		{

			if (document.frmlistCustomer.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmlistCustomer.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select customer to delete');
	}
	else
	{
		if(confirm('Are you sure you want to delete selected Customer?'))
		{
			show_processing();
			Handlewith_Ajax('services/customer.php','fpurpose=delete&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
function call_ajax_saveorder(search_name,sortby,sortorder,recs,start,pg)
{
	var qrystr= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;
	
	var IdArr=new Array();
	var OrderArr=new Array();
	var index;
	var val;
	var j=0;
	for(i=0;i<document.frmlistCustomer.elements.length;i++)
	{
		if (document.frmlistCustomer.elements[i].type =='text' && document.frmlistCustomer.elements[i].name!='records_per_page' && document.frmlistCustomer.elements[i].name!='search_name')
		{
			
			index=document.frmlistCustomer.elements[i].name;
			val=document.frmlistCustomer.elements[i].value;
			IdArr[j]=index;
			OrderArr[j]=val;
			j=j+1;
		}
	}
	
	
	var Idstr=IdArr.join('~');
	var OrderStr=OrderArr.join('~');
	
	if(confirm('Save Sort Order Of Bows?'))
		{
				show_processing();
				Handlewith_Ajax('services/giftwrap_bow.php','fpurpose=save_bow_order&'+qrystr+'&Idstr='+Idstr+'&OrderStr='+OrderStr);
		}

}
</script>
<form name="frmlistCustomer" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="request" value="customer" />
<input type="hidden" name="start" value="<?=$start?>" />
<input type="hidden" name="pg" value="<?=$pg?>" />
  <table border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td colspan="3" align="left" valign="middle" class="treemenutd">List Customers1</td>
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
      <td height="48" class="sorttd" colspan="3" >
      	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttableleft">
        <tr>
          <td width="22%" align="left" valign="middle">Customer Name </td>
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
          <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;<input name="button5" type="submit" class="red" id="button5" value="Go" /> <a href="#" onmouseover ="ddrivetip('Use \'Go\' button to search for customer groups.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
      </table>
	  </td>
    </tr>
    <tr>
      <td class="listeditd"><a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=customer&fpurpose=add&records_per_page=<?=$records_per_page?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$start?>&pg=<?=$pg?>" class="addlist" onclick="show_processing()">Add</a> 
	  <?
	  if($numcount)
	  {
	  ?>
	  <a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
	  <?
	  }
	  ?>
	  </td>
	   <td class="listeditd" width="162" align="center">
	  <?
	  if($numcount)
	  {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?></td>
	   <td class="listeditd" align="right">
	  <?
	  if($numcount)
	  {
	  ?>
        
		Change Status
          <select name="cbo_changehide" class="dropdown" id="cbo_changehide">
            <option value="1">Yes</option>
			<option value="0">No</option>
          </select>&nbsp;<input name="change_hide" type="button" class="red" id="change_hide" value="Change" onclick="call_ajax_changestatus('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" />
		  <a href="#" onmouseover ="ddrivetip('Use \'Change\' button to change the hide status of customers. Select the hide status in the drop down, mark the groups to be changed and press \'Change\' button.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
		<?
		}
		?>
   	   </td>
    </tr>
     
    <tr>
      <td colspan="3" class="listingarea">
      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable">
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
	   $sql_user = "SELECT customer_id,customer_fname,customer_mname,customer_surname,customer_email_7503,customer_phone,customer_bonus,customer_discount,date_format(customer_addedon,'%d-%b-%Y') as added_date,customer_hide FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
	   
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
          <td align="left" valign="middle" class="<?=$class_val;?>"  width="6%"><input name="checkbox[]" value="<? echo $row['customer_id']?>" type="checkbox"></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"  width="2%"><?php echo $srno++?></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><a href="home.php?request=customer&fpurpose=edit&checkbox[0]=<?php echo $row['customer_id']?>&search_name=<?php echo $_REQUEST['search_name']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>" title="<? echo $row['customer_fname']?>" class="edittextlink" onclick="show_processing()"><? echo $row['customer_fname']?>&nbsp;<? echo $row['customer_mname']?>&nbsp;<? echo $row['customer_surname']?></a></td>
          
		  <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo $row['customer_email_7503']; ?></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo $row['customer_phone']; ?></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo $row['customer_bonus']; ?></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo $row['customer_discount']; ?></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>">
		  <?
		  $sql_list="SELECT cg.custgroup_name FROM customer_group cg,customer_group_customers_map cm WHERE cg.sites_site_id=".$ecom_siteid." AND cm.customer_id=".$row['customer_id']."  AND cg.custgroup_id=cm.custgroup_id ";
		  $ret_list=$db->query($sql_list);
		  if($db->num_rows($ret_list)>0)
		  {
		  ?>
		  <select name="mail_list">
		  <?
		  while($row_list=$db->fetch_array($ret_list))
		  {
		  ?>
		  <option value="<?=$row_list['custgroup_name']?>"><?=$row_list['custgroup_name']?></option>
		  <?
		  }
		  ?>
		  </select>
		  <?
		  }
		  else
		  {
		  	echo "Not Assigned";
		  }
		  ?>
		 
		  </td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo $row['added_date']; ?></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo ($row['customer_hide'] == 1)?'Yes':'No'; ?></td>
         
        </tr>
      <?
	  }
	  }
	  else
	  {
	  ?>
	  <tr>
				  <td align="center" valign="middle" class="norecordredtext" >
				  	No Customer exists.				  </td>
			</tr>
		<?
		}
		?>	
      </table></td>
    </tr>
	
  <tr>
      <td class="listeditd"><a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=customer&fpurpose=add&records_per_page=<?=$records_per_page?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$start?>&pg=<?=$pg?>" class="addlist" onclick="show_processing()">Add</a> 
	  <?
	  if($numcount)
	  {
	  ?>
	  <a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
	  <?
	  }
	  ?>
	  </td>
	   <td class="listeditd" width="162"  align="center">
	  <?
	  if($numcount)
	  {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?></td>
	   <td class="listeditd" align="right">&nbsp;
	 
   	   </td>
    </tr>
    </table>
</form>
