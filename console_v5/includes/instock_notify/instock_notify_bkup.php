<?php
	/*#################################################################
	# Script Name 	: console_news.php
	# Description 	: Page for listing the news
	# Coded by 		: Lathhesh
	# Created on	: 03-Jul-2008
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
#Define constants for this page

//#Select condition for getting total count
$table_name  = 'product_stock_update_notification';
$page_type = 'Stock Notification';
$order_by = " ORDER BY 
    					 notify_date DESC";
//#Sort
$sort_by 			= (!$_REQUEST['sort_by'])?'news_add_date':$_REQUEST['sort_by'];
$sort_order 		= (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options 		= array('cust_fname' => 'Customer Name','notify_date' => 'Notify Date');
$sort_option_txt 	= generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt		= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);
//#Search Options
$where_conditions 	= " WHERE 
							sites_site_id=$ecom_siteid ";
if($_REQUEST['search_name']) {
$where_conditions .= " AND ( cust_fname LIKE '%".add_slash($_REQUEST['search_name'])."%' OR cust_midname LIKE '%".add_slash($_REQUEST['search_name'])."%' OR cust_lastname LIKE '%".add_slash($_REQUEST['search_name'])."%')";
}

if($_REQUEST['search_mail']) {
	$where_conditions .= " AND cust_email LIKE '%".add_slash($_REQUEST['search_mail'])."%'";
}

$from_date 	= add_slash($_REQUEST['not_fromdate']);
$to_date 	= add_slash($_REQUEST['not_todate']);

if ($from_date or $to_date)
{
	// Check whether from and to dates are valid
	$valid_fromdate = is_valid_date($from_date,'normal','-');
	$valid_todate	= is_valid_date($to_date,'normal','-');
	if($valid_fromdate)
	{
		$frm_arr 		= explode('-',$from_date);
		$mysql_fromdate = $frm_arr[2].'-'.$frm_arr[1].'-'.$frm_arr[0]; 
	}
	else// case of invalid from date
		$_REQUEST['not_fromdate'] = '';
		
	if($valid_todate)
	{
		$to_arr 		= explode('-',$to_date);
		$mysql_todate 	= $to_arr[2].'-'.$to_arr[1].'-'.$to_arr[0]; 
	}
	else // case of invalid to date
		$_REQUEST['not_todate'] = '';
	if($valid_fromdate and $valid_todate)// both dates are valid
	{
		$where_conditions .= " AND (notify_date BETWEEN '".$mysql_fromdate."' AND '".$mysql_todate."') ";
		$disp_more = true; 
	}
	elseif($valid_fromdate and !$valid_todate) // only from date is valid
	{
		$where_conditions .= " AND notify_date >= '".$mysql_fromdate."' ";
		$disp_more = true; 
	}
	elseif(!$valid_fromdate and $valid_todate) // only to date is valid
	{
		$where_conditions .= " AND notify_date <= '".$mysql_todate."' ";
		$disp_more = true; 
	}
}

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
 $query_string .= "&request=instock_notify&records_per_page=$records_per_page&status=$status&start=$start&search_name=".$_REQUEST['search_name']."";
 
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmListNotify,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmListNotify,\'checkbox[]\')"/>','Slno.','Requested Date','Customer Name','Email Address','Phone Number','');
$header_positions=array('left','left','left','left','left','left','center');
$colspan = count($table_headers);

?>	
<script language="javascript">
	function comment_disply(val, dispval)
	{
	 if(document.getElementById('comment_'+val).style.display != 'none') {
	  if(dispval=='yes') {
	 	document.getElementById('varhead_'+val).style.display = 'none';
		document.getElementById('varname_'+val).style.display = 'none';
		}
		document.getElementById('comment_'+val).style.display = 'none';
	 } else {
	  if(dispval=='yes') {
		document.getElementById('varhead_'+val).style.display = '';
		document.getElementById('varname_'+val).style.display = '';
		}
		document.getElementById('comment_'+val).style.display = '';
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
}function call_ajax_delete(notify_id,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var del_ids 			= '';
	var qrystr				= 'notify_id='+notify_id+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmListNotify.elements.length;i++)
	{
		if (document.frmListNotify.elements[i].type =='checkbox' && document.frmListNotify.elements[i].name=='checkbox[]')
		{

			if (document.frmListNotify.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmListNotify.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select atlease one notification to delete');
	}
	else
	{
		if(confirm('Are you sure you want to delete selected Notification?'))
		{
			show_processing();
			Handlewith_Ajax('services/instock_notify.php','fpurpose=delete_notification&del_ids='+del_ids);
		}	
	}	
}	
function handle_downloadhistory(val, dispval) 
{
	divobj	= eval("document.getElementById('downloadhistory_div_"+val+"')");
	if(document.getElementById('notdetails_'+val).style.display != 'none') {
		document.getElementById('notdetails_'+val).style.display = 'none';
		divobj.innerHTML = 'Click to View Details<img src="images/right_arr.gif" /> ';
	/*  if(dispval=='yes') {
	 	document.getElementById('varhead_'+val).style.display = 'none';
		document.getElementById('varname_'+val).style.display = 'none';
		}
		document.getElementById('comment_'+val).style.display = 'none'; 
		*/
	 } else {
	 	document.getElementById('notdetails_'+val).style.display = '';
	  if(dispval=='yes') {
		document.getElementById('varhead_'+val).style.display = '';
		document.getElementById('varname_'+val).style.display = '';
		}
		document.getElementById('comment_'+val).style.display = '';
		divobj.innerHTML = 'Click to Hide Details<img src="images/down_arr.gif" />';
	 }	
	
	/*
	trobj 	= eval("document.getElementById('downloadhistory_tr_"+id+"')");
	divobj	= eval("document.getElementById('downloadhistory_div_"+id+"')");
	if(trobj.style.display=='')
	{
		trobj.style.display = 'none';
		divobj.innerHTML = 'Click to view download history<img src="images/right_arr.gif" />';
	}
	else
	{
		trobj.style.display ='';
		divobj.innerHTML = 'Click to hide download history<img src="images/down_arr.gif" /> ';
	} */
}

</script>
<form name='frmListNotify' action='home.php' method="post">
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="request" value="instock_notify" />
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="7" align="left" valign="middle" class="treemenutd"> Stock Notification Request </td>
        </tr>
		<tr>
      <td height="48" class="sorttd" colspan="7" >
      	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttableleft">
			 <tr>
				  <td width="19%" align="left" valign="middle" nowrap="nowrap">Customer Email &nbsp;</td>
				  <td colspan="6" align="left" valign="middle"><input name="search_mail" type="text" class="textfeild" id="search_mail" value="<?=$_REQUEST['search_mail']?>">				  				  </td>
			<!--      <td colspan="2" align="left" valign="middle" nowrap="nowrap">&nbsp; Customer Email &nbsp;</td>
			      <td width="23%" colspan="2" align="left" valign="middle"><input name="search_name" type="text" class="textfeild" id="search_name" value="<?=$_REQUEST['search_name']?>"  /></td>
 -->			 </tr>
			 <tr>
			   <td align="left" valign="middle" nowrap="nowrap">Between Dates </td>
			   <td colspan="2" align="left" valign="middle"><input name="not_fromdate" class="textfeild" type="text" size="12" value="<?php echo $_REQUEST['not_fromdate']?>" /></td>
			   <td width="50%" align="left" valign="middle"><a href="javascript:show_calendar('frmListNotify.not_fromdate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a></td>
			   <td width="8%" align="left" valign="middle" nowrap="nowrap"><input name="not_todate" class="textfeild" id="ord_todate" type="text" size="12" value="<?php echo $_REQUEST['not_todate']?>" />&nbsp;</td>
			   <td width="15%" align="left" valign="middle" nowrap="nowrap"><a href="javascript:show_calendar('frmListNotify.not_todate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a></td>
		  </tr>
      </table>
      <table width="18%" border="0" cellpadding="0" cellspacing="0" class="sorttableright">
			<tr>
			  <td width="12%" align="left" >&nbsp;Show</td>
			  <td width="41%" align="left" ><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/>
				<?=$page_type?> Per Page</td>
			  <td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;<input name="button5" type="submit" class="red" id="button5" value="Go" /> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_CONSOLE_NEWS_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
			</tr>
      </table>	  </td>
    </tr>
	 
	  <? if($help_msg){?>
        <tr>
          <td colspan="7" align="left" valign="middle" class="helpmsgtd" ><?=$help_msg ?></td>
        </tr>
		<? } ?>
		 <?
	  if($numcount)
	  {
	  ?>
	  <tr>
	  <td align="center" class="listeditd"><a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['notify_id']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a> </td>
		 <td class="listeditd"  align="center" colspan="6" >
	  <?
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  ?>		</td>
	  </tr>
	  <? 
	  }
	  ?>
		<?
		if($alert)
		{			
		?>
        <tr>
          <td colspan="7" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
        </tr>
		<?
		} 
	echo table_header($table_headers,$header_positions);	
	if($numcount)
	  {
    	 $sql_notify = "SELECT 
							notify_id,sites_site_id,products_product_id,
							DATE_FORMAT(notify_date,'%d %b %Y') as notifydate,cust_title,cust_fname,cust_midname,
							cust_lastname,cust_email,cust_phonenumber,cust_comments,product_stock_comb_id
    		 		FROM 
			   				$table_name 
					$where_conditions 
						$order_by 
							LIMIT $start,$records_per_page";
	    $ret_notify = $db->query($sql_notify);
	    	if ($db->num_rows($ret_notify))
	    	{
	    		while ($row_notify = $db->fetch_array($ret_notify))
	    		{
					$count_no++;
					if($count_no %2 == 0)
						$class_val="listingtablestyleB";
					else
						$class_val="listingtablestyleA";	
			
			$prodsql = "SELECT product_name FROM products WHERE product_id='".$row_notify['products_product_id']."'";
			$prodres = $db->query($prodsql);
			$prodrow = $db->fetch_array($prodres);
			$productname = $prodrow['product_name'];
			
			$varsql = "SELECT var_name, var_value
								FROM product_stock_update_notification_variables 
									WHERE product_stock_update_notification_notify_id='".$row_notify['notify_id']."'";
			$varres = $db->query($varsql);
			$varnum = $db->num_rows($varres);
			
			$smssql = "SELECT message_caption, message_value
								FROM product_stock_update_notification_messages 
									WHERE product_stock_update_notification_notify_id='".$row_notify['notify_id']."'";
			$smsres = $db->query($smssql);
			$smsnum = $db->num_rows($smsres);
						
		?>
	
		<tr >
          <td align="left" valign="top" class="<?=$class_val;?>"  width="6%"><input name="checkbox[]" value="<? echo $row_notify['notify_id']; ?>" type="checkbox"></td>
		  <td align="left" valign="top" class="<?=$class_val;?>"  width="5%"><?php echo $count_no; ?></td>
		  <td width="25%" align="left" valign="top" class="<?=$class_val;?>"><?PHP echo $row_notify['notifydate']; ?></td>
          <td align="left" valign="top" class="<?=$class_val;?>" width="25%">
		  <?PHP
		  if($varnum>0) { 
		  		$vardisplay = 'yes';
		  }
		  ?>
		  <?PHP echo $row_notify['cust_title'].' '.$row_notify['cust_fname'].''.$row_notify['cust_midname'].''.$row_notify['cust_lastname'];  ?>	 
		  	   </td>
		  <td align="left" valign="top" class="<?=$class_val;?>" width="25%"><?PHP echo $row_notify['cust_email']; ?></td>
		  <td align="left" valign="top" class="<?=$class_val;?>" width="25%"><?PHP echo $row_notify['cust_phonenumber']; ?></td>
		  <td align="center" valign="top" class="<?=$class_val;?>" width="25%"><div id="downloadhistory_div_<?php echo $row_notify['notify_id']?>" onclick="handle_downloadhistory('<?PHP echo $row_notify['notify_id']; ?>', '<?PHP echo $vardisplay; ?>')" style="width:180px; float:right; cursor:pointer">
							Click to View Details<img src="images/right_arr.gif" border="0" /></div>	 </td>
        </tr>
		
	<tr id="notdetails_<?PHP echo $row_notify['notify_id'];  ?>" style="display:none; "  >
	 <td  colspan="4" align="right" valign="top"    > 
	 <table width="90%" cellpadding="1" cellspacing="1" border="0">
		  <?PHP
		  
		  if($varnum>0) {
		   /*
		  ?>
          <tr id="varhead_<?PHP echo $row_notify['notify_id'];  ?>"  >
              <td colspan="7" align="left" class="listingtableheader">&nbsp;<?=$productname?></td>
            </tr>
		<?PHP	$varcnt = 0;
				while($varrow = $db->fetch_array($varres))		
				{
					$varcnt += 1;
					
					if($varcnt %2 == 0)
						$class_val="listingtablestyleB";
					else
						$class_val="listingtablestyleB";	
		?>
            
            <tr id="varname_<?PHP echo $row_notify['notify_id'];  ?>" class="<?=$class_val?>">
              <td colspan="7" align="center" class="listingtablestyleB">
			  <span  style="float:left; padding-left:10px;"><?=$varrow['var_name']  ."  :  ". $varrow['var_value']?></span> &nbsp;</td>
            </tr>
			<? /* ?>
            <tr id="varname_<?PHP echo $row_notify['notify_id'];  ?>" class="<?=$class_val?>">
			
              <td width="17%" align="center" class="listingtablestyleB"><?=$varcnt?>&nbsp;</td>
              <td width="36%" align="left" class="listingtablestyleB"><?=$varrow['var_name']?>&nbsp;</td>
              <td width="47%" align="left" class="listingtablestyleB"><?=$varrow['var_value']?>&nbsp;</td>
            </tr>
			<? */ ?>
				<? /* }  
				while($smsrow = $db->fetch_array($smsres))		
				{
					$varcnt += 1;
					
					if($varcnt %2 == 0)
						$class_val="listingtablestyleB";
					else
						$class_val="listingtablestyleB";
				?>
				 <tr id="varname_<?PHP echo $row_notify['notify_id'];  ?>" class="<?=$class_val?>">
              <td colspan="7" align="center" class="listingtablestyleB">
			  <span  style="float:left; padding-left:10px;"><?=$varrow['message_caption']  ."  :  ". $varrow['message_value']?></span> &nbsp;</td>
            </tr>
			<? /* ?>
		 <tr id="varname_<?PHP echo $row_notify['notify_id'];  ?>" class="<?=$class_val?>">
              <td align="center" class="listingtablestyleB"><?=$varcnt?>&nbsp;</td>
              <td align="left" class="listingtablestyleB"><?=$smsrow['message_caption']?>&nbsp;</td>
              <td align="left" class="listingtablestyleB"><?=$smsrow['message_value']?>&nbsp;</td>
            </tr>
				
			<? */ ?>
			<? /* }	 */ } ?>
		    <tr id="comment_<?PHP echo $row_notify['notify_id'];  ?>"  >
              <td colspan="7" align="left" class="listingtableheader">&nbsp;Comments  </td>
            </tr>
		    <tr >
		      <td colspan="7"  align="left" class="listingtablestyleB">&nbsp;<?PHP echo nl2br($row_notify['cust_comments']); ?></td>
	        </tr>	
          </table>
	 </td>
	    <td colspan="4" align="left" valign="top"     >
		  <table width="100%" cellpadding="1" cellspacing="1" border="0">
		  <?PHP
		 
		  ?>
          <tr id="varhead_<?PHP echo $row_notify['notify_id'];  ?>"  >
              <td colspan="4" align="left" class="listingtableheader">&nbsp;Product : <?=$productname?></td>
            </tr>
		<?PHP	
		if($varnum>0) { 
		$varcnt = 0;
				while($varrow = $db->fetch_array($varres))		
				{
					$varcnt += 1;
					
					if($varcnt %2 == 0)
						$class_val="listingtablestyleB";
					else
						$class_val="listingtablestyleB";	
		?>
            
            <tr id="varname_<?PHP echo $row_notify['notify_id'];  ?>" class="<?=$class_val?>">
              <td colspan="4" align="center" class="listingtablestyleB">
			  <span  style="float:left; padding-left:10px;"><?=$varrow['var_name']  ."  :  ". $varrow['var_value']?></span> &nbsp;</td>
            </tr>
			<? /* ?>
            <tr id="varname_<?PHP echo $row_notify['notify_id'];  ?>" class="<?=$class_val?>">
			
              <td width="17%" align="center" class="listingtablestyleB"><?=$varcnt?>&nbsp;</td>
              <td width="36%" align="left" class="listingtablestyleB"><?=$varrow['var_name']?>&nbsp;</td>
              <td width="47%" align="left" class="listingtablestyleB"><?=$varrow['var_value']?>&nbsp;</td>
            </tr>
			<? */ ?>
				<? }  
				while($smsrow = $db->fetch_array($smsres))		
				{
					$varcnt += 1;
					
					if($varcnt %2 == 0)
						$class_val="listingtablestyleB";
					else
						$class_val="listingtablestyleB";
				?>
				 <tr id="varname_<?PHP echo $row_notify['notify_id'];  ?>" class="<?=$class_val?>">
              <td colspan="4" align="center" class="listingtablestyleB">
			  <span  style="float:left; padding-left:10px;"><?=$varrow['message_caption']  ."  :  ". $varrow['message_value']?></span> &nbsp;</td>
            </tr>
			<? /* ?>
		 <tr id="varname_<?PHP echo $row_notify['notify_id'];  ?>" class="<?=$class_val?>">
              <td align="center" class="listingtablestyleB"><?=$varcnt?>&nbsp;</td>
              <td align="left" class="listingtablestyleB"><?=$smsrow['message_caption']?>&nbsp;</td>
              <td align="left" class="listingtablestyleB"><?=$smsrow['message_value']?>&nbsp;</td>
            </tr>
				
			<? */ ?>
			<? }	} /* ?>
		    <tr id="comment_<?PHP echo $row_notify['notify_id'];  ?>"  >
              <td colspan="4" align="left" class="listingtableheader">&nbsp;Comments About <?=$productname?> </td>
            </tr>
		    <tr >
		      <td colspan="4"  align="left" class="listingtablestyleB">&nbsp;<?PHP echo nl2br($row_notify['cust_comments']); ?></td>
	        </tr>	
			<? */ ?>
          </table>
	  </td>
    </tr>
	
       <!--<tr id="varhead_<?PHP// echo $row_notify['notify_id'];  ?>" style="display:none;">
		   <td  colspan="2" align="center" valign="top" class="<?=$class_val;?>"><strong>Var SlNo</strong></td>
           <td  align="left" valign="top" class="<?=$class_val;?>"><strong>Variable Name</strong></td>
           <td  colspan="2" align="left" valign="top" class="<?=$class_val;?>"><strong>Variable value</strong></td>
           <td align="center" valign="top" class="<?=$class_val;?>">&nbsp;</td>
       </tr>	
	   
	   <tr id="varname_<?PHP// echo $row_notify['notify_id'];  ?>"  style="display:none;">
		   <td  colspan="2" align="center" valign="top"class="<?=$class_val;?>" ><?=$varcnt?></td>
           <td  align="left" valign="top" class="<?=$class_val;?>">&nbsp;</td>
           <td  colspan="2" align="left" valign="top" class="<?=$class_val;?>">&nbsp;</td>
           <td align="center" valign="top" class="<?=$class_val;?>">&nbsp;</td>
       </tr>
	   		<? //} ?>
		 <tr id="comment_<?PHP// echo $row_notify['notify_id'];  ?>"  style="display:none;">
		   <td  colspan="2" align="left" valign="top" class="<?=$class_val;?>">&nbsp; <strong>Message</strong> </td>
	       <td  colspan="4" align="left" valign="top" class="<?=$class_val;?>">&nbsp;<?PHP echo $row_notify['cust_comments']; ?></td>
       </tr> -->
		
	 <? 
	  }
	  }
	  }
	  else
	  {
	    ?>
		  <tr>
		  <td align="center" valign="middle" class="norecordredtext"  colspan="7">No Notifications Found</td>
	  </tr>
		<?   
	  }
	
	  ?>
		<tr>
		<td colspan="7"  class="tdcolorgray" >&nbsp;</td>
		</tr>
		<tr>
          <td width="6%" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td width="5%" align="left" valign="middle" class="tdcolorgray">
			<input type="hidden" name="retdiv_id" id="retdiv_id" value="maincontent" />
			<input type="hidden" name="retdiv_more" id="retdiv_more" value="" />
		  <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
	    <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />        </tr>
      </table>
</form>	  

