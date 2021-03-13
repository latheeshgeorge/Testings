<?php
	/*#################################################################
	# Script Name 	: list_callback.php
	# Description 	: Page for listing Customer
	# Coded by 		: Latheesh
	# Created on	: 03-Sep-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
//Define constants for this page
$table_name			='callback';
$page_type			='Callback';
$help_msg 			= get_help_messages('LIST_CALLBACK_MESS1');
$table_headers  	= array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistCallback,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistCallback,\'checkbox[]\')"/>','Slno.','Callback Id','Date Added','Name','Email Address','Phone','Country','Status');
$header_positions	= array('left','left','left','left','left','left','left','left','left');
$colspan 			= count($table_headers);
$cur_user = $_SESSION['console_id'];
//#Search terms
$search_fields 		= array('callback_fname','status');
foreach($search_fields as $v) {
	 $query_string .= "$v=".$_REQUEST[$v]."&";#For passing search terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by 			= (!$_REQUEST['sort_by'])?'callback_adddate':$_REQUEST['sort_by'];
$sort_order 		= (!$_REQUEST['sort_order'])?'DESC':$_REQUEST['sort_order'];
$sort_options 		= array('callback_fname' => 'Name','callback_email' => 'Email','callback_adddate' => 'Date Added');
$sort_option_txt 	= generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt		= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions 	= "WHERE sites_site_id=$ecom_siteid ";
if($_REQUEST['search_name']) {
	$where_conditions .= "AND ( callback_fname LIKE '%".add_slash($_REQUEST['search_name'])."%')";
}
if($_REQUEST['search_email']) {
	$where_conditions .= "AND ( callback_email LIKE '%".add_slash($_REQUEST['search_email'])."%')";
}
if($_REQUEST['status']=='')
{
 $_REQUEST['status'] ='NEW';
 $status = 'NEW';
}
if($_REQUEST['status']){
$where_conditions .= "AND ( callback_status LIKE '%".add_slash($_REQUEST['status'])."%')";
}

//#Select condition for getting total count
$sql_count = "SELECT count(*) as cnt FROM $table_name  $where_conditions";
$res_count = $db->query($sql_count);
list($numcount) = $db->fetch_array($res_count);#Getting total count of records
/////////////////////////////////For paging///////////////////////////////////////////
$records_per_page = (is_numeric($_REQUEST['records_per_page']) and $_REQUEST['records_per_page'])?$_REQUEST['records_per_page']:10;#Total records shown in a page
$pg = ($_REQUEST['search_click'])?1:$_REQUEST['pg'];

if (!($pg > 0) || $pg == 0) { $pg = 1; }
$pages = ceil($numcount / $records_per_page);#Getting the total pages.
if($pg > $pages) {
	$pg = $pages;
}
$start = ($pg - 1) * $records_per_page;#Starting record.
$count_no = ($pg == 1)?0:$records_per_page*($pg-1);
/////////////////////////////////////////////////////////////////////////////////////
 $query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=callback&records_per_page=$records_per_page&search_name=".$_REQUEST['search_name']."&search_email=".$_REQUEST['search_email']."&start=$start";

?>

<script language="javascript">
function call_ajax_changestatus(search_name,sortby,sortorder,recs,start,pg,status)
{
	var atleastone 			= 0;
	var curid				= 0;
	var callback_ids 			= '';
	var cat_orders			= '';
	var ch_status			= document.frmlistCallback.cbo_changestatus.value;
	var search_email		= '<?php echo $_REQUEST['search_email']?>';
	var qrystr				= 'search_name='+search_name+'&search_email='+search_email+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&ch_status='+ch_status+'&start='+start+'&pg='+pg+'&status='+status;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlistCallback.elements.length;i++)
	{
		if (document.frmlistCallback.elements[i].type =='checkbox' && document.frmlistCallback.elements[i].name=='checkbox[]')
		{

			if (document.frmlistCallback.elements[i].checked==true)
			{
				atleastone = 1;
				if (callback_ids!='')
					callback_ids += '~';
				 callback_ids += document.frmlistCallback.elements[i].value;
			}	
		}
	}
	if (atleastone==0) 
	{
		alert('Please select the Callback to change the hide status');
	}
	else
	{
		if(confirm('Change Status of Seleted Customer(s)?'))
		{
				show_processing();
				Handlewith_Ajax('services/callback.php','fpurpose=change_status&'+qrystr+'&callback_ids='+callback_ids);
		}	
	}	
}

function edit_selected()
{

	len=document.frmlistCallback.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistCallback.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
				bow_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one Callback ');
	}
	else if(cnt>1 ){
		alert('Please select only one Callback to edit');
	}
	else
	{
		show_processing();
		document.frmlistCallback.fpurpose.value='edit';
		document.frmlistCallback.submit();
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
function call_ajax_delete(search_name,sortby,sortorder,recs,start,pg,status)
{
	var atleastone 	= 0;
	var del_ids 	= '';
	var search_email= '<?=$_REQUEST['search_email']?>';
	var qrystr		= 'search_email='+search_email+'&search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&status='+status;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlistCallback.elements.length;i++)
	{
		if (document.frmlistCallback.elements[i].type =='checkbox' && document.frmlistCallback.elements[i].name=='checkbox[]')
		{

			if (document.frmlistCallback.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmlistCallback.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select callback to delete');
	}
	else
	{
		if(confirm('Are you sure you want to delete selected Callback?'))
		{
			show_processing();
			Handlewith_Ajax('services/callback.php','fpurpose=delete&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}

</script>
<form name="frmlistCallback" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="request" value="callback" />
<input type="hidden" name="start" value="<?=$start?>" />
<input type="hidden" name="pg" value="<?=$pg?>" />
<input type="hidden" name="status" value="<?=$status?>" />
  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td colspan="4" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span>List Callback</span></div></td>
    </tr>
	<tr>
	  <td colspan="4" align="left" valign="middle" class="helpmsgtd_main">
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
	<tr>
		<td colspan="4" align="right" valign="middle" class="sorttd">
		<?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?>
	  </td>
	<?php
	  }
	?>
	</tr>
    <tr>
      <td height="48" class="sorttd" colspan="4" >
	  <div class="editarea_div">
      	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttabletop">
			 <tr>
				  <td width="10%" height="30" align="left" valign="middle">Customer Name </td>
			   <td width="25%" height="30" align="left" valign="middle"><input name="search_name" type="text" class="textfeild" id="search_name" value="<?=$_REQUEST['search_name']?>"  /></td>
				  <td width="7%" height="30" align="left" valign="middle">Email Id </td>
		       <td width="30%" height="30" align="left" valign="middle"><input name="search_email" type="text" class="textfeild" id="search_email" value="<?=$_REQUEST['search_email']?>" /></td>
			      <td width="5%" height="30" align="left" valign="middle">Status</td>
			      <td width="23%" height="30" align="left" valign="middle"><?= generateselectbox('status',array('0' => 'ANY','NEW' => 'NEW','READ' => 'READ','DONE' => 'DONE'),$_REQUEST['status']);?></td>
		    </tr>
			  <tr>    
				  <td width="10%" height="30" align="left" valign="middle">Records Per Page </td>
		        <td width="25%" height="30" align="left" valign="middle"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/></td>
			      <td width="7%" height="30" align="left" valign="middle">Sort By</td>
			      <td width="30%" height="30" align="left" valign="middle"><?=$sort_option_txt?>&nbsp;&nbsp;&nbsp;in&nbsp;&nbsp;&nbsp; <?=$sort_by_txt?></td>
		        <td width="5%" height="30" align="left" valign="middle">&nbsp;</td>
			      <td width="23%" height="30" align="right" valign="middle"><input name="button5" type="submit" class="red" id="button5" value="Go" />
                    <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_CALLBACK_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
			 </tr>      
      </table>
	  </div>
      </td>
    </tr>
     
    <tr>
      <td colspan="4" class="listingarea">
	  <div class="listingarea_div">
      <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="listingtable">
	  
    <tr>
      <td class="listeditd" colspan="5">
	  <?
	  if($numcount)
	  {
	  ?>
	   <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>','<?php echo $_REQUEST['status']?>')" class="deletelist">Delete</a>
	  <?
	  }
	  ?>
	  </td>
	   <td class="listeditd" align="right" colspan="4">
	  <?
	  if($numcount)
	  {
	  ?>
        
		Change Status
          <select name="cbo_changestatus" class="dropdown" id="cbo_changestatus">
            <option value="NEW">NEW</option>
			<option value="READ">READ</option>
			<option value="DONE">DONE</option>
          </select>&nbsp;<input name="change_status" type="button" class="red" id="change_status" value="Change" onclick="call_ajax_changestatus('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>','<?php echo $_REQUEST['status']?>')" />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_CALLBACK_CHSTATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
		<?
		}
		?>
   	   </td>
	 	   
    </tr>
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
		 $sql_user = "SELECT callback_id,callback_fname,callback_lname,callback_email,callback_phone,callback_country,date_format(callback_adddate,'%d-%b-%Y') as added_date,callback_status FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
	   
	   $res = $db->query($sql_user);
	   $srno = getStartOfPageno($records_per_page,$pg); 
	   while($row = $db->fetch_array($res))
	   {
			$count_no++;
			$array_values = array();
			if($count_no %2 == 0)
				$class_val="listingtablestyleA";
			else
				$class_val="listingtablestyleB";
		if($row['callback_country']!='' && is_numeric($row['callback_country'])){	
	     $sql_country="SELECT country_name FROM general_settings_site_country WHERE sites_site_id=".$ecom_siteid." AND country_id=".$row['callback_country']." ";
		  $res_country=$db->query($sql_country);
		  }
		 $row_country=$db->fetch_array($res_country)
		
	   ?>
        <tr >
          <td align="left" valign="middle" class="<?=$class_val;?>"  width="6%"><input name="checkbox[]" value="<? echo $row['callback_id']?>" type="checkbox"></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"  width="2%"><?php echo $srno++?></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"  width="10%"><?php echo $row['callback_id']?></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo $row['added_date']; ?></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"><a href="home.php?request=callback&fpurpose=edit&checkbox[0]=<?php echo $row['callback_id']?>&search_name=<?php echo $_REQUEST['search_name']?>&search_email=<?php echo $_REQUEST['search_email']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&status=<?php echo $_REQUEST['status']?>&sort_by=<?php echo $_REQUEST['sort_by']?>&sort_order=<?php echo $_REQUEST['sort_order']?>" title="<? echo $row['callback_fname']?>" class="edittextlink" onclick="show_processing()"><? echo $row['callback_fname']?>&nbsp;<? echo $row['callback_lname']?>&nbsp;</a></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo $row['callback_email']; ?></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo $row['callback_phone']; ?></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo $row_country['country_name']; ?></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo $row['callback_status']; ?></td>
        </tr>
      <?
	  }
	  }
	  else
	  {
	  ?>
	  <tr>
				  <td align="center" valign="middle" class="norecordredtext"  colspan="9">
				  	No Callback exists.				  </td>
		  </tr>
		<?
		}
		?>	
		<tr>
      <td class="listeditd" colspan="5"> 
	  <?
	  if($numcount)
	  {
	  ?>
	   <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>','<?php echo $status?>')" class="deletelist">Delete</a>
	  <?
	  }
	  ?>
	  </td>
	   <td class="listeditd" colspan="4" align="right">
	 </td>	    
    </tr>
      </table>
	  </div></td>
    </tr>
	<tr>
     
	   <td class=" listing_bottom_paging" colspan="2" align="right">
	  <?
	  if($numcount)
	  {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?></td>	    
    </tr>
  
  </table>
</form>
