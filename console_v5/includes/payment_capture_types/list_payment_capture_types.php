<?php
	/*#################################################################
	# Script Name 	: list_payment_capture_types.php
	# Description 	: Page for listing the payment types enabled for the site
	# Coded by 		: ANU
	# Created on	: 27-June-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
	
	//Define constants for this page
	$table_name = 'payment_capture_types as pt,general_settings_site_paymentcapture_type as pts';
	$page_type='Payment Types';
	$help_msg = get_help_messages('LIST_PAYMENT_CAPTURE_TYPE_MESS1');
	$table_headers = array('Slno.','Payment Capture Types','');
	$header_positions=array('left','left','left');
	$colspan = count($table_headers);
	

		
	//#Sort
	$sort_by = (!$_REQUEST['sort_by'])?'paymentcapture_name':$_REQUEST['sort_by'];
	$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
	$sort_options = array('paymentcapture_name' => 'Payment Capture Type Name');
	$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
	$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);
	
	//#Search Options
	$where_conditions = "WHERE 1=1 AND pt.paymentcapture_id = pts.payment_capture_types_paymentcapture_id AND sites_site_id=$ecom_siteid ";
	

	//#Select condition for getting total count
	$sql_count = "SELECT count(*) as cnt FROM $table_name  $where_conditions";
	$res_count = $db->query($sql_count);
	list($numcount) = $db->fetch_array($res_count);//#Getting total count of records
	/////////////////////////////////For paging///////////////////////////////////////////
	$records_per_page = (is_numeric($_REQUEST['records_per_page']) and $_REQUEST['records_per_page'])?$_REQUEST['records_per_page']:10;//#Total records shown in a page
	$pg = ($_REQUEST['search_click'])?1:$_REQUEST['pg'];
	
	if (!($pg > 0) || $pg == 0) { $pg = 1; }
	$pages = ceil($numcount / $records_per_page);//#Getting the total pages
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
	$sql_setting_type = "SELECT pt.paymentcapture_name,pts.payment_capture_types_paymentcapture_id FROM $table_name $where_conditions ORDER BY $sort_by $sort_order";
	 //$sql_payment_type;
	$ret_setting_qry = $db->query($sql_setting_type);
	$row_setting_qry =$db->fetch_array($ret_setting_qry);	
	//echo $row_setting_qry['payment_capture_types_paymentcapture_id'];
 $sql_payment_type = "SELECT paymentcapture_name,paymentcapture_id FROM payment_capture_types";
	//echo $sql_payment_type;
	$ret_qry = $db->query($sql_payment_type);
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
function call_ajax_select_paymenttype(sortby,sortorder)
{
	
	var new_default_id 			= '';
	var disabled_ids 		    = '';
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frm_pymttypes.elements.length;i++)
	{
		if (document.frm_pymttypes.elements[i].type =='radio' && document.frm_pymttypes.elements[i].name=='paytypes_id[]')
		{

			if (document.frm_pymttypes.elements[i].checked==true)
			{
			atleastone = 1;
				if (disabled_ids!='')
					disabled_ids += '~';
				 disabled_ids += document.frm_pymttypes.elements[i].value;
			 }
			
		} else
			 {
			 atleastone = 0;
			 }
		
	}if(atleastone == 0)
		{
		 alert('Please select the payment capture tyoe');
		  return false;
		}
	var qrystr = '&sort_by='+sortby+'&sort_order='+sortorder+'&pymt_ids='+disabled_ids;
	if(confirm('Are you sure you want to Save the Selected Payment  Capture Types ?'))
		{
			show_processing();
			Handlewith_Ajax('services/payment_capture_types.php','fpurpose=select_paymenttype&'+qrystr);
		}
		
}
	</script>
	<form method="post" name="frm_pymttypes" class="frmcls" action="home.php">
	<input type="hidden" name="request" value="payment_capture_types" />
	<input type="hidden" name="fpurpose" value="" />
	<input type="hidden" name="search_click" value="" />
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintable">
		<tr>
		  <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span> Payment capture types</span></div></td>
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
		    <td width="100%" class="tdcolorgray" colspan="3">
			<div class="listingarea_div">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
		  <td align="right" valign="middle" class="listeditd">		  	</td>
		  </tr>
		<tr>
		  <td class="listingarea">
		  <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="listingtable">
		 <?php 
			echo table_header($table_headers,$header_positions); 
			if ($db->num_rows($ret_qry))
			{ 
				$srno = 1;
				while ($row_qry = $db->fetch_array($ret_qry))
				{
					$cls = ($srno%2==0)?'listingtablestyleA':'listingtablestyleB';
		 ?>
					<tr>
					  <td align="left" valign="middle" class="<?php echo $cls?>" width="5%"><?php echo $srno++?></td>
					  <td align="left" valign="middle" class="<?php echo $cls?>" width="30%" ><div style="float:left" > <?php echo stripslashes($row_qry['paymentcapture_name'])?></div>					  </td>		  
					<td align="left" valign="middle" class="<?php echo $cls?>">
					  <input type="radio" name="paytypes_id[]" id="paytypes_id[]" value="<?php echo $row_qry['paymentcapture_id']?>"  <?php echo ($row_qry['paymentcapture_id']==$row_setting_qry['payment_capture_types_paymentcapture_id'])?'checked="checked"':''?> /></td>	
					</tr>
		<?php
				}
			}
			else
			{
		?>	
				<tr>
					  <td align="center" valign="middle" class="norecordredtext" colspan="<?php echo $colspan?>">
						No Payment Types found.					</td>
				</tr>	  
		<?php
			}
		?>
		  </table></td>
		</tr>
		</table>
		</div>
		</td>
		</tr>
		<tr>
		<td align="right">
		<div class="editarea_div">
		<input name="select_paymenttype" type="button" class="red" id="select_paymenttype" value="Save Changes" onclick="call_ajax_select_paymenttype('<?=$sort_by?>','<?=$sort_order?>')"/ />		    &nbsp;&nbsp;&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PAYMENT_CAPTURE_TYPE_SAVE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
		</div>
		</td>
		</tr>
	  </table>
	</form>
