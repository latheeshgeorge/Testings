<?php
	/*#################################################################
	# Script Name 	: list_giftvouchers.php
	# Description 	: Page for listing Gift Vouchers
	# Coded by 		: Sny
	# Created on	: 31-Jul-2007
	# Modified by	: Sny
	# Modified On	: 20-May-2008
	#################################################################*/
// Define constants for this page
$table_name			= 'gift_vouchers';
$page_type			= 'Gift Vouchers';
$help_msg 			= get_help_messages('LIST_GIFT_VOUCH_MESS1');
$table_headers 		= array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frm_giftvoucher,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frm_giftvoucher,\'checkbox[]\')"/>','Slno.','Voucher Number','Discount','Created on','Activated on','Expires on','Pay Status','Spent','Added By','Hidden');
$header_positions	= array('center','left','left','right','center','center','center','left','center','left','center');
$colspan 			= count($table_headers);


//#Sort
$sort_by 			= (!$_REQUEST['sort_by'])?'voucher_boughton':$_REQUEST['sort_by'];
$sort_order 		= (!$_REQUEST['sort_order'])?'DESC':$_REQUEST['sort_order'];
$sort_options 		= array('voucher_number' => 'Voucher Number','voucher_value'=>'Value','voucher_boughton'=>'Created On','voucher_activatedon'=>'Activated On','voucher_expireson'=>'Expires on','voucher_paystatus'=>'Payment Status');
$sort_option_txt 	= generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt		= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

// Search Options
$where_conditions 	= " WHERE sites_site_id=$ecom_siteid ";
if ($_REQUEST['vouchernumber'])
	$where_conditions .= " AND voucher_number LIKE '%".$_REQUEST['vouchernumber']."%' ";

// Payment status
if($_REQUEST['paystatus'])
{
	if($_REQUEST['paystatus']=='Paid')
		$where_conditions .= " AND voucher_paystatus = 'Paid'";
	else if($_REQUEST['paystatus']=='Unpaid')
	{
		if ($_REQUEST['chk_sel_incomplete'])
			$more_cond = " AND voucher_incomplete = 1 ";
		else
			$more_cond = " AND voucher_incomplete = 0 ";
		$where_conditions .= " AND voucher_paystatus <> 'Paid'  AND voucher_paystatus != 'REFUNDED' $more_cond";	
	}	
	else if($_REQUEST['paystatus']=='REFUNDED')
		$where_conditions .= " AND voucher_paystatus = 'REFUNDED'";		
	else
		$where_conditions .= " AND voucher_incomplete = 0 ";
}
else
 $where_conditions .= " AND voucher_incomplete = 0 ";
// Added by
if($_REQUEST['addedby']) {
	$where_conditions .= " AND voucher_createdby = '".$_REQUEST['addedby']."' ";
}

if($_REQUEST['vouchertype'] == 'N')
{
	$where_conditions .= " AND reviewmail_orderid = 0";
}
elseif($_REQUEST['vouchertype'] == 'A')
{
	$where_conditions .= " AND reviewmail_orderid > 0";
}
elseif($_REQUEST['vouchertype'] == '0')
{
	$where_conditions .= " AND (reviewmail_orderid > 0 ||reviewmail_orderid = 0)";
}
else
{
	$where_conditions .= " AND reviewmail_orderid = 0";
	$_REQUEST['vouchertype']	=	'N';
}


// Select condition for getting total count
$sql_count = "SELECT count(*) as cnt FROM $table_name $where_conditions";
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

//#Search terms
$search_fields 		= array('vouchernumber','paystatus','addedby');

$query_string 		= "request=gift_voucher&sort_by=$sort_by&sort_order=$sort_order&records_per_page=$records_per_page&vouchernumber=".$_REQUEST['vouchernumber']."&start=$start";
foreach($search_fields as $v) {
	$query_string .= "&$v=".$_REQUEST[$v]."";//#For passing searh terms to javascript for passing to different pages.
}

$sql_qry = "SELECT * FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
$ret_qry = $db->query($sql_qry);
?>
<script type="text/javascript">
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
function call_ajax_delete(cname,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var del_ids 			= '';
	var vouchernumber		= '<?php echo $_REQUEST['vouchernumber']?>';
	var paystatus			= '<?php echo $_REQUEST['paystatus']?>';
	var addedby				= '<?php echo $_REQUEST['addedby']?>';
	var sortby				= '<?php echo $sort_by?>';
	var sortorder			= '<?php echo $sort_order?>';
	var records_per_page	= '<?php echo $records_per_page?>';
	var start				= '<?php echo $start?>';
	var pg					= '<?php echo $pg?>';
	var qrystr				= 'vouchernumber='+vouchernumber+'&paystatus='+paystatus+'&addedby='+addedby+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+records_per_page+'&start='+start+'&pg='+pg;

	/* check whether any checkbox is ticked */
	for(i=0;i<document.frm_giftvoucher.elements.length;i++)
	{
		if (document.frm_giftvoucher.elements[i].type =='checkbox' && document.frm_giftvoucher.elements[i].name=='checkbox[]')
		{

			if (document.frm_giftvoucher.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frm_giftvoucher.elements[i].value;
			}
		}
	}
	if (atleastone==0)
	{
		alert('Please select the gift voucher(s) to delete');
	}
	else
	{
		if(confirm('Are you sure you want to delete selected gift vouchers?'))
		{
			show_processing();
			Handlewith_Ajax('services/gift_voucher.php','fpurpose=delete&del_ids='+del_ids+'&'+qrystr);
		}
	}
}
function call_ajax_changestatus()
{
	var atleastone 			= 0;
	var gift_ids			= '';
	var cat_ids 			= '';
	var cat_orders			= '';
	var vouchernumber		= '<?php echo $_REQUEST['vouchernumber']?>';
	var paystatus			= '<?php echo $_REQUEST['paystatus']?>';
	var addedby				= '<?php echo $_REQUEST['addedby']?>';
	var sortby				= '<?php echo $sort_by?>';
	var sortorder			= '<?php echo $sort_order?>';
	var records_per_page	= '<?php echo $records_per_page?>';
	var start				= '<?php echo $start?>';
	var pg					= '<?php echo $pg?>';
	var ch_status			= document.frm_giftvoucher.cbo_changehide.value;

	var qrystr				= 'vouchernumber='+vouchernumber+'&paystatus='+paystatus+'&addedby='+addedby+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+records_per_page+'&ch_status='+ch_status+'&start='+start+'&pg='+pg;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frm_giftvoucher.elements.length;i++)
	{
		if (document.frm_giftvoucher.elements[i].type =='checkbox' && document.frm_giftvoucher.elements[i].name=='checkbox[]')
		{

			if (document.frm_giftvoucher.elements[i].checked==true)
			{
				atleastone = 1;
				if (gift_ids!='')
					gift_ids += '~';
				 gift_ids += document.frm_giftvoucher.elements[i].value;
			}
		}
	}
	if (atleastone==0)
	{
		alert('Please select the Vouchers to change the hide status');
	}
	else
	{
		if(confirm('Change Hide Status of Seleted Voucher(s)?'))
		{
				show_processing();
				Handlewith_Ajax('services/gift_voucher.php','fpurpose=change_hide&'+qrystr+'&giftids='+gift_ids);
		}
	}
}
/////////////////

function edit_selected(id)
{
	var id_exists = false;
	if(id!=0)
		id_exists = true;
	len=document.frm_giftvoucher.length;
	var cnt=0;
	for (var j = 1; j <= len; j++) {
		el = document.frm_giftvoucher.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		{
			if(id_exists)
			{
				if (el.value==id)
				{
					el.checked=true;
				}
			}
			if(el.checked)
			{
				cnt++;
				voucher_id=el.value;
			}
		 }
	}
	if(cnt==0) {
		alert('Please select atleast one Voucher ');
	}
	else if(cnt>1 ){
		alert('Please select only one Voucher to edit');
	}
	else
	{
		show_processing();
		document.frm_giftvoucher.fpurpose.value='edit';
		document.frm_giftvoucher.submit();
	}


}


//////////////////
function go_edit(cname,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var del_ids 			= '';
	var qrystr				= 'catgroupname='+cname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frm_giftvoucher.elements.length;i++)
	{
		if (document.frm_giftvoucher.elements[i].type =='checkbox' && document.frm_giftvoucher.elements[i].name=='checkbox[]')
		{

			if (document.frm_giftvoucher.elements[i].checked==true)
			{
				atleastone += 1;
			}
		}
	}
	if (atleastone==0)
	{
		alert('Please select the product category groups to edit');
	}
	else
	{
		if(atleastone==1)
		{
			show_processing();
			document.frm_giftvoucher.fpurpose.value='edit';
			document.frm_giftvoucher.submit();
		}
		else
		{
			alert('Please select only one Product Category Group to delete.');
		}
	}
}
function handle_showmorediv()
{
	if(document.getElementById('listmore_tr').style.display=='')
	{
		document.getElementById('listmore_tr').style.display = 'none';
		document.getElementById('show_morediv').innerHTML = 'Options<img src="images/right_arr.gif" />';
	}
	else
	{
		document.getElementById('listmore_tr').style.display ='';
		document.getElementById('show_morediv').innerHTML = 'Options<img src="images/down_arr.gif" /> ';
	}
}
function handle_export_giftvoucher()
{
	var exp_opt = document.frm_giftvoucher.cbo_export_giftvoucher.value;
	if (exp_opt =='')
	{
		alert('Please select the export option');
		return false;
	}
	if (exp_opt=='sel_giftvoucher') // case of selected order, check whether any orders ticked
	{
		var atleast_one = false;
		var ids ='';
		for(i=0;i<document.frm_giftvoucher.elements.length;i++)
		{
			if (document.frm_giftvoucher.elements[i].type =='checkbox')
			{
				if (document.frm_giftvoucher.elements[i].name=='checkbox[]')
				{
					if (document.frm_giftvoucher.elements[i].checked==true)
					{
						atleast_one = true;
						if (ids!='')
							ids += '~';
				 		ids += document.frm_giftvoucher.elements[i].value;
					}
				}
			}
		}
		if (atleast_one==false)
		{
			alert('Please select the giftvoucher(s) to export');
			return false;
		}
		/* Write the logic to submit the details to order export section here*/
		document.frm_giftvoucher.request.value 	= 'import_export';
		document.frm_giftvoucher.export_what.value 	= 'giftvoucher';
		document.frm_giftvoucher.fpurpose.value 	= '';
		document.frm_giftvoucher.ids.value 	=ids;
		document.frm_giftvoucher.submit();


	}
	else
	{
	// var atleast_one = false;
		var ids ='';
		for(i=0;i<document.frm_giftvoucher.elements.length;i++)
		{
			if (document.frm_giftvoucher.elements[i].type =='checkbox')
			{
				if (document.frm_giftvoucher.elements[i].name=='checkbox[]')
				{
						atleast_one = true;
						if (ids!='')
							ids += '~';
				 		ids += document.frm_giftvoucher.elements[i].value;
				}
			}
		}
		/* Write the logic to submit the details to order export section here*/
		document.frm_giftvoucher.request.value 	= 'import_export';
		document.frm_giftvoucher.export_what.value 	= 'giftvoucher';
		document.frm_giftvoucher.fpurpose.value 	= '';
		document.frm_giftvoucher.ids.value 	=ids;
		document.frm_giftvoucher.submit();

	}
}
function handle_paydrop_action(obj)
{
	document.getElementById('chk_sel_incomplete').checked=false;
	if (obj.value=='Unpaid')
	{
		document.getElementById('incomplete_1').style.display = '';
		document.getElementById('incomplete_2').style.display = '';
	}
	else
	{
		document.getElementById('incomplete_1').style.display = 'none';
		document.getElementById('incomplete_2').style.display = 'none';
	}
}
</script>
<form method="post" name="frm_giftvoucher" class="frmcls" action="home.php">
<input type="hidden" name="request" value="gift_voucher" />
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="export_what" value="" />
<input type="hidden" name="ids" value="" />
<input type="hidden" name="search_click" value="" />			
<input type="hidden" name="start" id="start" value="<?=$start?>" />
<input type="hidden" name="pg" id="pg" value="<?=$pg?>" />
<table border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td colspan="4" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span> List Gift Vouchers</span></div></td>
    </tr>
	<tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="4">
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
		if ($db->num_rows($ret_qry))
		{
	?>
    <tr><td colspan="4" align="right" valign="middle" class="sorttd"><?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?></td></tr>
	<?php
		}
	?>
	<tr>
      <td height="48" colspan="4" class="sorttd">
		<div class="sorttd_div">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="58%" align="left" valign="top">
		  <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td width="16%" height="30" align="left" valign="middle">Voucher Number </td>
              <td width="21%" height="30" align="left" valign="middle"><input name="vouchernumber" type="text" class="textfeild" id="vouchernumber" value="<?php echo $_REQUEST['vouchernumber']?>" /></td>
              <td width="14%" height="30" align="left" valign="middle">Payment Status </td>
              <td width="24%" height="30" align="left" valign="middle">
			  <?php
			  	$pay_arr = array('0'=>'-- Any --','Paid'=>'Paid','Unpaid'=>'Not Paid','REFUNDED'=>'Refunded'); //
				echo generateselectbox('paystatus',$pay_arr,$_REQUEST['paystatus'],'','handle_paydrop_action(this)');
			 ?>			 </td>
              <td width="25%" align="left" valign="middle">&nbsp;</td>
            </tr>
            <tr>
              <td height="30" align="left" valign="middle">Added By </td>
              <td height="30" align="left" valign="middle"><?php
			  	$add_arr = array('0'=>'-- All --','A'=>'Site Admin','C'=>'Customer');
				echo generateselectbox('addedby',$add_arr,$_REQUEST['addedby']);
			 ?>
&nbsp;&nbsp;</td>
              <td height="30" align="left" valign="middle">Voucher Type </td>
              <td height="30" align="left" valign="middle"><?php
			  	$type_arr = array('0'=>'-- All --','N'=>'Normal','A'=>'Auto Created');
				echo generateselectbox('vouchertype',$type_arr,$_REQUEST['vouchertype']);
			 ?>
&nbsp;&nbsp;</td>
              <td height="30" align="left" valign="middle"><div id='incomplete_1' <?php echo ($_REQUEST['paystatus']=='Unpaid')?'':'style="display:none"'?>>
                <input name="chk_sel_incomplete" type="checkbox" id="chk_sel_incomplete" value="1" <?php if($_REQUEST['chk_sel_incomplete']==1) echo 'checked="checked"'?> />
              </div>
                <div id='incomplete_2' <?php echo ($_REQUEST['paystatus']=='Unpaid')?'':'style="display:none"'?>>Show only Incomplete Purchases</div></td>
            </tr>
            <tr>
              <td colspan="5" align="left"></td>
              </tr>
          </table>          </td>
          <td width="42%" align="left" valign="top">
		  <table width="98%" height="50" border="0" align="right" cellpadding="0" cellspacing="0">
            <tr>
              <td height="30" align="left" valign="middle">Records Per Page 
                <input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3" value="<?php echo $records_per_page?>" /></td>
              <td width="23%" align="left">&nbsp;</td>
            </tr>
            <tr>
              <td height="30" align="left" valign="middle">Sort By&nbsp;<?php echo $sort_option_txt;?> in <?php echo $sort_by_txt?> </td>
              <td align="right"><input name="Search_go" type="submit" class="red" id="Search_go" value="Go" onclick="document.frm_giftvoucher.search_click.value=1" />
                <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_GIFT_VOUCH_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
            </tr>
          </table>		  </td>
        </tr>
      </table></div></td>
    </tr>
    
    <tr>
      <td colspan="4" class="listingarea">
	  <div class="listingarea_div">
	  <table  border="0" cellpadding="0" cellspacing="0" class="listingtable">
	  <tr>
      <td class="listeditd" colspan="<?php echo round($colspan/2)?>" align="left" valign="middle"><a href="home.php?request=gift_voucher&fpurpose=add&vouchernumber=<?php echo $_REQUEST['vouchernumber']?>&paystatus=<?php echo $_REQUEST['paystatus']?>&addedby=<?php echo $_REQUEST['addedby']?>&vouchertype=<?php echo $_REQUEST['vouchertype'];?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>" class="addlist">Add</a><a href="#" onclick="edit_selected(0)" class="editlist">Edit</a>
	  	<?php
			if ($db->num_rows($ret_qry))
			{
		?>
				<a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['catgroupname']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
		<?php
			}
		?>		</td>
      <td align="right" valign="middle" class="listeditd" colspan="<?php echo round($colspan/2)?>" ><?php
			if ($db->num_rows($ret_qry))
			{
		?>
			Change Hide Status to
  <?php
					$chhide_array = array('0'=>'No','1'=>'Yes');
					echo generateselectbox('cbo_changehide',$chhide_array,0);
				?>
 <input name="change_hide" type="button" class="red" id="change_hide" value="Change" onclick="call_ajax_changestatus()" />
  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_GIFT_VOUCH_CHHIDE')?>.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
  <?php
			}
		?></td>
    </tr>
        <?php
	 	echo table_header($table_headers,$header_positions);
		$voucher_type_arr = array('val'=>'Value','per'=>'%');
		if ($db->num_rows($ret_qry))
		{
			$srno = 1;
			while ($row_qry = $db->fetch_array($ret_qry))
			{
				$cls 		= ($srno%2==0)?'listingtablestyleA':'listingtablestyleB';
				if($row_qry['product_discount']>0)
				{
					$disctype	= ($row_qry['product_discount_enteredasval']==0)?'%':'';
					$discval_arr= explode(".",$row_qry['product_discount']);
					if($discval_arr[1]=='00')
						$discval = $discval_arr[0];
					else
						$discval = $row_qry['product_discount'];
					$disc		= $discval.$disctype;
				}
				else
					$disctype = $disc = '--';
	 ?>
        <tr>
          <td align="left" valign="middle" class="<?php echo $cls?>" width="5%"><input type="checkbox" name="checkbox[]" id="checkbox[]" value="<?php echo $row_qry['voucher_id']?>" /></td>
          <td align="left" valign="middle" class="<?php echo $cls?>" width="1%"><?php echo $srno++?>.</td>
          <td align="left" valign="middle" class="<?php echo $cls?>" width="10%"><a href="#" onclick="edit_selected('<?php echo $row_qry['voucher_id']?>')" title="View Details" class="edittextlink"><?php echo stripslashes($row_qry['voucher_number'])?></a></td>
          <td align="right" valign="middle" class="<?php echo $cls?>"><?php
				   	if($row_qry['voucher_type']=='val')
				   	{
				   		if($row_qry['voucher_createdby']=='C')
				   			echo print_price_selected_currency($row_qry['voucher_value'],$row_qry['voucher_curr_rate'],$row_qry['voucher_curr_symbol'],true);
				   		else
				   			echo display_price($row_qry['voucher_value']);
				   	}
					else
						echo $row_qry['voucher_value'].'%';
					?>          </td>
          <td align="center" valign="middle" class="<?php echo $cls?>"><?php
				  		//$start_arr		= explode("-",$row_qry['voucher_boughton']);
						echo dateFormat($row_qry['voucher_boughton'],'');
						//echo date('d/M/Y',mktime(0,0,0,$start_arr[1],$start_arr[2],$start_arr[0]));
				  ?>          </td>
          <td align="center" valign="middle" class="<?php echo $cls?>"><?php
				  		if ($row_qry['voucher_paystatus']=='Paid' or $row_qry['voucher_paystatus']=='REFUNDED')
				  		{
					  		echo dateFormat($row_qry['voucher_activatedon'],'');
				  		}
				  		else
				  			echo '-';
				  ?>          </td>
          <td align="center" valign="middle" class="<?php echo $cls?>"><?php
				  		if ($row_qry['voucher_paystatus']=='Paid' or $row_qry['voucher_paystatus']=='REFUNDED')
				  		{
					  		echo dateFormat($row_qry['voucher_expireson'],'');
				  		}
				  		else
				  			echo '-';
				  ?>          </td>
          <td align="left" valign="middle" class="<?php echo $cls?>">
		  <?php 
		  	echo getpaymentstatus_Name($row_qry['voucher_paystatus']) ;
			if($row_qry['voucher_incomplete']==1)
				echo  ' <span style="color:#FF0000">(Incomplete)</span>';
		  
		  ?></td>
          <td align="center" valign="middle" class="<?php echo $cls?>"><?php echo $row_qry['voucher_usage']?>/<?php echo $row_qry['voucher_max_usage']?></td>
          <td align="left" valign="middle" class="<?php echo $cls?>"><?php echo ($row_qry['voucher_createdby']=='A')?'Admin':'Customer'?></td>
          <td align="center" valign="middle" class="<?php echo $cls?>"><?php
					  		echo ($row_qry['voucher_hide']=='1')?'Yes':'No';
						?>          </td>
        </tr>
        <?php
			}
		}
		else
		{
	?>
        <tr>
          <td align="center" valign="middle" class="norecordredtext" colspan="<?php echo $colspan?>"> No Vouchers found. </td>
        </tr>
        <?php
		}
	?>
	<tr>
      <td class="listeditd" align="left" valign="middle" colspan="<?php echo round($colspan/2)?>"><a href="home.php?request=gift_voucher&fpurpose=add&vouchernumber=<?php echo $_REQUEST['vouchernumber']?>&paystatus=<?php echo $_REQUEST['paystatus']?>&addedby=<?php echo $_REQUEST['addedby']?>&vouchertype=<?php echo $_REQUEST['vouchertype'];?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>" class="addlist" onclick="show_processing();">Add</a><a href="#" onclick="edit_selected()" class="editlist">Edit</a>
	  <?php
		if ($db->num_rows($ret_qry))
		{
	?>
			<a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['catgroupname']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
	 <?php
	 	}
	 ?>	  </td>
      <td align="right" class="listeditd" valign="middle" colspan="<?php echo round($colspan/2)?>" >
	 </td>	
    </tr>
      </table>
	  </div>
	  </td>
    </tr>
	<tr>
     
      <td align="right" class="listing_bottom_paging" valign="middle" colspan="2" >
	  <?php
		if ($db->num_rows($ret_qry))
		{
			paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan);
		}
		?></td>	
    </tr>
	<?
	  /*if($db->num_rows($ret_qry))
	  {
	    if(is_module_valid('mod_importexport','onconsole'))
	 	{
	?>
	 
	<tr>
	 <td colspan="4" class="seperationtd" align="left">
	 		Export Giftvoucher(s)	 </td>
	 </tr>
	  <tr>
	 <td colspan="4" class="seperationtd" align="left">
	 		<select name="cbo_export_giftvoucher" id="cbo_export_giftvoucher">
	 			<option value="">-- Select --</option>
	 			<option value="sel_giftvoucher">Export Selected Giftvouchers</option>
	 			<option value="all_giftvoucher">Export All Giftvouchers</option>
	 		</select>
	 		&nbsp;
	 		<input type="button" name="submit_voucherexport" id="submit_voucherexport" value="Export Now" class="red" onclick="handle_export_giftvoucher()" />	 </td>
	 </tr>
	 <?
	    }
	 }*/
	  ?>
    </table>
</form>