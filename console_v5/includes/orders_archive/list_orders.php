<?php
	/*#################################################################
	# Script Name 	: list_orders.php
	# Description 		: Page for Listing Product Orders
	# Coded by 		: Sny
	# Created on		: 18-Apr-2008
	# Modified by		: Sny
	# Modified On		: 08-May-2008
	#################################################################*/
//#Define constants for this page
$table_name			= 'orders_archieve';
$page_type			= 'Order archives';
$help_msg 			= 'List of archived orders will be listed in this section';//get_help_messages('PROD_ORDERARCHIEVE_MESS1');

// Class Array for classes
$class_arr = array 
					(
						'NEW'=>'orderlisting_new',
						'PENDING'=>'orderlisting_pending',
						'DESPATCHED'=>'orderlisting_despatch',

						'ONHOLD'=>'orderlisting_hold',
						'BACK'=>'orderlisting_backorder',
						'CANCELLED'=>'orderlisting_cancelled'
					);
//'This section lists the Product Category Groups available on the site. Here there is provision for adding a Product Category Groups, editing, & deleting it.';
$table_headers 		= array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frm_orders,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frm_orders,\'checkbox[]\')"/>','Id','Date','Name','Email','Order Status','Total','Refund','Pay Type','Pay Status');
$header_positions	= array('center','left','center','left','left','left','right','right','left','left');
$colspan 			= count($table_headers);

//#Search terms
$search_fields = array('ord_id','ord_status','ord_name','ord_email','ord_fromdate','ord_todate','ord_stores','order_placed_from');
//#Sort
$sort_by 			= (!$_REQUEST['ord_sort_by'])?'order_date':$_REQUEST['ord_sort_by'];
$sort_order 		= (!$_REQUEST['ord_sort_order'])?'DESC':$_REQUEST['ord_sort_order'];
$sort_options 		= array('order_date' => 'Order Date','order_status'=>'Order Status','order_custfname'=>'Customer Name','order_custemail'=>'Email','order_pre_order'=>'Preorder','order_totalprice'=>'Order Total','order_refundamt'=>'Refund Amount');
$sort_option_txt 	= generateselectbox('ord_sort_by',$sort_options,$sort_by);
$sort_by_txt		= generateselectbox('ord_sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);


$query_string 		= "request=orders&ord_sort_by=".$sort_by."&ord_sort_order=".$sort_order;


if($sort_by=='order_status')
	$sort_by='ordstat';
foreach($search_fields as $v) {
	$query_string 	.= "&$v=".$_REQUEST[$v]."";//#For passing searh terms to javascript for passing to different pages.
}
//##########################################################################################################
// Building the query to be used to display the orders
//##########################################################################################################
$where_conditions 	= "WHERE sites_site_id=$ecom_siteid ";
$disp_more = false; // variable which decides whether the more options is to be made visible by default
// Check whether order status is selected 
if(!$_REQUEST['ord_status'])
	$_REQUEST['ord_status'] = -1;
if($_REQUEST['ord_status'])
{
	if ($_REQUEST['ord_status']==-1) // Done to handle the case of selected to show all orders
	{
		$where_conditions .= " AND order_status NOT IN ('CANCELLED','NOT_AUTH')"; // This is done to avoid the cancelled orders while asked to show all orders
	}
	elseif ($_REQUEST['ord_status'] == 'DEPOSIT')// case of listing order which have product deposit
	{
		$where_conditions .= " AND order_deposit_amt > 0 ";	
	}
	elseif($_REQUEST['ord_status']=='REFUNDED') // case of refunded orders listing 
	{
		$where_conditions .= " AND order_paystatus = '".$_REQUEST['ord_status']."' ";
	}	
	elseif($_REQUEST['ord_status']=='pay_on_account')
	{
		$where_conditions .= " AND order_paymenttype = '".$_REQUEST['ord_status']."' ";
	}
	else // case of all other types other than above
	{
		if($_REQUEST['ord_status']=='NOT_AUTH')
			$sp_cond = " AND order_paystatus <>'credit_card'  ";
		else
			$sp_cond = '';
		$where_conditions .= " AND order_status = '".$_REQUEST['ord_status']."' $sp_cond";
	}
}
else // If coming to the page for the first time, show all orders
{
	
	//$where_conditions .= " AND order_status = 'NEW' ";
	$_REQUEST['ord_status'] = -1;
}
//serach for which device the order placed from 
if(!$_REQUEST['order_placed_from'])
   $_REQUEST['order_placed_from'] = -1;
if($_REQUEST['order_placed_from']!='' && $_REQUEST['order_placed_from']!=-1)
{
 $where_conditions .= " AND order_placed_from = '".$_REQUEST['order_placed_from']."' ";
 $disp_more = true; 
}
//##########################################################################################################
// Check whether order id is given
if($_REQUEST['ord_id'])
{
	$where_conditions .= " AND order_id='".add_slash($_REQUEST['ord_id'])."'";
}
//##########################################################################################################
// Check whether cust/company name is given
if($_REQUEST['ord_name'])
{
	$sr_name = add_slash($_REQUEST['ord_name']);
	$where_conditions .= " AND (
								order_custfname LIKE '%".$sr_name."%' 
								OR order_custmname LIKE '%".$sr_name."%' 
								OR order_custsurname LIKE '%".$sr_name."%' 
								OR order_custcompany LIKE '%".$sr_name."%'  
								) ";
	$disp_more = true; 
}
//##########################################################################################################
// If customer email is given
if($_REQUEST['ord_email'])
{
	$where_conditions .= " AND order_custemail LIKE '%".add_slash($_REQUEST['ord_email'])."%' ";
	$disp_more = true; 
}
//##########################################################################################################
// If store is selected
if($_REQUEST['ord_stores'])
{
	$where_conditions .= " AND sites_shops_shop_id =".add_slash($_REQUEST['ord_stores'])." ";
	$disp_more = true; 
}
//##########################################################################################################
// Case if from or to date is given
$from_date 	= add_slash($_REQUEST['ord_fromdate']);
$to_date 	= add_slash($_REQUEST['ord_todate']);
if ($from_date or $to_date)
{
	// Check whether from and to dates are valid
	$valid_fromdate = is_valid_date($from_date,'normal','-');
	$valid_todate	= is_valid_date($to_date,'normal','-');
	if($valid_fromdate)
	{
		$frm_arr 		= explode('-',$from_date);
		$mysql_fromdate = $frm_arr[2].'-'.$frm_arr[1].'-'.$frm_arr[0].' 00:00:00'; 
	}
	else// case of invalid from date
		$_REQUEST['ord_fromdate'] = '';
		
	if($valid_todate)
	{
		$to_arr 		= explode('-',$to_date);
		$mysql_todate 	= $to_arr[2].'-'.$to_arr[1].'-'.$to_arr[0].' 23:59:59'; 
	}
	else // case of invalid to date
		$_REQUEST['ord_todate'] = '';
	if($valid_fromdate and $valid_todate)// both dates are valid
	{
		$where_conditions .= " AND (order_date BETWEEN '".$mysql_fromdate."' AND '".$mysql_todate."') ";
		$disp_more = true; 
	}
	elseif($valid_fromdate and !$valid_todate) // only from date is valid
	{
		$where_conditions .= " AND order_date >= '".$mysql_fromdate."' ";
		$disp_more = true; 
	}
	elseif(!$valid_fromdate and $valid_todate) // only to date is valid
	{
		$where_conditions .= " AND order_date <= '".$mysql_todate."' ";
		$disp_more = true; 
	}
}
//##########################################################################################################
//#Select condition for getting total count
$sql_count 			= "SELECT count(*) as cnt 
						FROM 
							$table_name  
							$where_conditions";
$res_count 			= $db->query($sql_count);

list($numcount) 	= $db->fetch_array($res_count);//#Getting total count of records
/////////////////////////////////For paging///////////////////////////////////////////
$records_per_page 	= (is_numeric($_REQUEST['records_per_page']) and $_REQUEST['records_per_page'])?$_REQUEST['records_per_page']:25;//#Total records shown in a page
if(!$_REQUEST['pg'])
{	
	if($_REQUEST['ser_pg'])
		$_REQUEST['pg'] = $_REQUEST['ser_pg'];
}
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

 $sql_qry = "SELECT 
				order_id,customers_customer_id,sites_site_id,sites_shops_shop_id ,
				order_date,order_custtitle,order_custfname,order_custmname,
				order_custsurname,order_custemail,order_paymenttype,order_paymentmethod,
				order_paystatus,order_status,order_refundamt,order_deposit_amt,
				order_deposit_cleared,order_currency_symbol,order_currency_convertionrate,
				order_totalprice,order_totalauthorizeamt,order_pre_order,
				order_paystatus_changed_manually,order_paystatus_changed_manually_by,
				order_paystatus_changed_manually_on,order_cancelled_by,
				order_cancelled_on,order_cancelled_from,
				CASE order_status 
				WHEN 'NEW' THEN 'Unviewed'
				else
					order_status
				END as ordstat,
				order_placed_from   
			FROM 
				$table_name 
				$where_conditions 
			ORDER BY 
				$sort_by $sort_order 
			LIMIT 
				$start,$records_per_page ";
$ret_order = $db->query($sql_qry);

?>
<style type="text/css" media="screen">
<?php /*?>#despatch_details_ajaxholder{
	position:absolute;
	z-index:999;
}
.proddet_loading_div_ajax{
	width:130px;
	height:100px;
	background-color:#F00;
	position:absolute;
}
.p_close{
	  float:RIGHT;
	height:47px;
	width:45px;
}
.p_main{
margin:auto;
width:702px;
height:521px;
background:url(images/main_ajax_outer.png) left top no-repeat;
}

.p_content_otr{
float:left;
width:680px;
height:401px;
overflow-y:scroll;
}	



.p_content_inner{
float:left;
width:630px;
margin-left:20px;
padding:10px 0;
border-top:1px solid #D7D7D7;
}	

.p_content_l{
float:left;
width:282px;
padding-right:20px;
padding-left:10px;
border-right:1px solid #D7D7D7;
}	
.p_content_r{
float:left;
width:282px;
padding-left:20px;
}	

.p_content_con{
float:left;
width:310px;
}	
.p_content_con{
float:left;
width:282px;
}	

.p_content_id{
float:left;
width:272px;
background-color:#365687;
font:bold 12px Arial, Helvetica, sans-serif; 
color: #fff;	
padding:5px;
text-align:left;
}

.p_content_ref{
float:left;
width:282px;
font:normal 12px Arial, Helvetica, sans-serif; 
color: #000;	
padding:10px 0 0 0;
text-align:left;
}

.p_content_note{
float:left;
width:282px;
font:normal 12px Arial, Helvetica, sans-serif; 
color: #000;	
padding:10px 0 0 0;
text-align:left;
}

.p_content_txt {
float:left;
width:282px;
font:bold 11px Arial, Helvetica, sans-serif; 
color: #000;
text-align:left;
}

.p_content_txt input {
border:1px solid #000;
width:190px;
font:normal 12px Arial, Helvetica, sans-serif; 
color: #000;	
padding:3px ;
}

.p_content_txt textarea {
border:1px solid #000;
width:250px;
height:70px;
font:normal 12px Arial, Helvetica, sans-serif; 
color: #000;	
padding:3px ;
}

.p_close_inner{

float:left;

height:30px;

text-align:right;

padding:20px 0 5px 0;width:97%;padding-right:3%;

}
.block_class{
background-color:#CCCCCC;
}
.flashvideo_outer{
position:absolute;
left:0;
top:0;
width:100%;
height:100%;
background-color:#000000;
opacity:.40;
filter: alpha(opacity=40); -moz-opacity:0.4;
z-index:1;
}<?php */?>
<?php /*?>.p_error_msg{
position:relative;
top:0;
background-color:#990000;
height:250px;
margin-top:250px;
}
<?php */?>
</style>
<script type="text/javascript">
function edit_order(edit_id)
{
	if (edit_id==-1)/* Case came here by clicking the details icon*/
	{
		/* Check Whether any order is selected*/
		var tot_sel = 0;
		for(i=0;i<document.frm_orders.elements.length;i++)
		{
			if (document.frm_orders.elements[i].name =='checkbox[]')
			{
				if(document.frm_orders.elements[i].checked)
				{
					tot_sel++;
					edit_id = document.frm_orders.elements[i].value;
				}	
			}
		}
		if(tot_sel==0)
		{
			alert('Please select the order for which the details is to be viewed');
			return false;	
		}
		else if (tot_sel>1)
		{
			alert('Please select only one order at a time');
			return false;		
		}
	}
	document.frm_orders.edit_id.value 	= edit_id;
	document.frm_orders.fpurpose.value 	= 'ord_details';
	document.frm_orders.submit();
}

function backto_order(edit_id)
{
	if (edit_id==-1)/* Case came here by clicking the details icon*/
	{
		/* Check Whether any order is selected*/
		var tot_sel = 0;
		for(i=0;i<document.frm_orders.elements.length;i++)
		{
			if (document.frm_orders.elements[i].name =='checkbox[]')
			{
				if(document.frm_orders.elements[i].checked)
				{
					tot_sel++;
					edit_id = document.frm_orders.elements[i].value;
				}	
			}
		}
		if(tot_sel==0)
		{
			alert('Please select the Archived orders which are to be moved to My Orders section');
			return false;	
		}
	}
	if(confirm('Are you sure you wanted to move the selected archived orders back to My Orders section?'))
	{
		document.frm_orders.edit_id.value 	= edit_id;
		document.frm_orders.fpurpose.value 	= 'do_archive_backtoorder';
		document.frm_orders.submit();
	}	
}


function delete_order()
{
	/* Check Whether any order is selected*/
	var tot_sel = 0;
	var sel_del_ids = '';
	for(i=0;i<document.frm_orders.elements.length;i++)
	{
		if (document.frm_orders.elements[i].name =='checkbox[]')
		{
			if(document.frm_orders.elements[i].checked)
			{
				tot_sel++;
				if(sel_del_ids!='')
					sel_del_ids += '~';
				sel_del_ids += document.frm_orders.elements[i].value;
			}	
		}
	}
	if(tot_sel==0)
	{
		alert('Please select the order(s) to be deleted');
		return false;	
	}
	if (confirm('All information related to selected order(s) will be deleted and this operation is Not Reversible\n\n Are you sure you want to delete the selected order(s)?'))
	{
		show_processing();
		document.frm_orders.fpurpose.value 	= 'ord_delete';
		document.frm_orders.submit();
	}	
}


/*function ajax_return_contents() 
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
}*/
function ajax_return_contents() 
{
	/*var ret_val='';
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
	}*/
	var ret_val = '';
	var disp 	= 'no';
	if(req.readyState==4)
	{ 
		if(req.status==200)
		{
			if(waiting_for_despatch_return==0)
			{
				ret_val 			= req.responseText;
				targetobj 			= document.getElementById('despatch_details_ajaxholder');
				targetobj.innerHTML = ret_val; /* Setting the output to required div */														
				hideme('#proddet_loading_div_ajax');
				showme('#despatch_details_ajaxholder');
			}
			else
			{
				ret_val 		= req.responseText;
				/*alert(despatch_array[current_despatch_index] + ' ---- '+ current_despatch_index);*/
				targetobj 		= eval('document.getElementById("p_err_msg_'+despatch_array[current_despatch_index]+'")');
				targetobj.innerHTML = ret_val; 
				
				targetobj2 		= eval('document.getElementById("lower_container_'+despatch_array[current_despatch_index]+'")');
				targetobj2.innerHTML = '';
				
				targetobj3 		= eval('document.getElementById("list_orderstatus_div_'+despatch_array[current_despatch_index]+'")');
				targetobj3.innerHTML = '<span style="color:#FF0000">Despatched</span>';
				
				change_despatch_class('mainordertd_'+despatch_array[current_despatch_index]);
				
				
				current_despatch_index++;
				if((despatch_array.length-1)>=current_despatch_index)
				{
					waiting_for_despatch_return = 1;
					do_bulk_despatch(current_despatch_index);
				}
			}	
		}
		else
		{
			waiting_for_despatch_return = 0;
			hideme('#proddet_loading_div_ajax');
		}
	}
}

function go_archive(sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var archive_order_ids   = '';
	var qrystr				= 'ord_sort_by='+sortby+'&ord_id=<?php echo $_REQUEST['ord_id']?>&ord_name=<?php echo $_REQUEST['ord_name']?>&ord_email=<?php echo $_REQUEST['ord_email']?>&ord_fromdate=<?php echo $_REQUEST['ord_fromdate']?>&ord_todate=<?php echo $_REQUEST['ord_todate']?>&ord_sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;
/*	var qrystr				= 'sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;      */
	
	for(i=0;i<document.frm_orders.elements.length;i++)
	{
		if (document.frm_orders.elements[i].type =='checkbox' && document.frm_orders.elements[i].name=='checkbox[]')
		{

			if (document.frm_orders.elements[i].checked==true)
			{
				atleastone = 1;
				if (archive_order_ids!='')
					archive_order_ids += '~';
				 archive_order_ids += document.frm_orders.elements[i].value;
				}	
			
			
		}
	}
	if (atleastone==0)
	{
		alert('Please select an order to archive');
	}
	else
	{
			if(confirm('Are you sure want to move the selected order into archive section?'))
			{
				show_processing();
				Handlewith_Ajax('services/orders.php','fpurpose=archive&archive_order_ids='+archive_order_ids+'&'+qrystr);
				
			}
	}	
}



function handle_showmorediv()
{
	if(document.getElementById('listmore_tr1').style.display=='')
	{
		document.getElementById('listmore_tr1').style.display = 'none';
		document.getElementById('show_morediv_big').innerHTML = 'Filters<img src="images/right_arr.gif" />';
		document.getElementById('bottom_godiv').style.display = 'none';
		document.getElementById('top_godiv').style.display='';
	}	
	else
	{
		document.getElementById('listmore_tr1').style.display ='';
		document.getElementById('show_morediv_big').innerHTML = 'Hide Filters<img src="images/down_arr.gif" /> ';
		document.getElementById('bottom_godiv').style.display = '';
		document.getElementById('top_godiv').style.display='none';
	}	
}

function handle_showmoreoperationsdiv()
{
	if(document.getElementById('more_operations_tr').style.display=='')
	{
		document.getElementById('more_operations_tr').style.display = 'none';
		document.getElementById('show_moreoperations_big').innerHTML = 'More Operations<img src="images/right_arr.gif" />';
	}	
	else
	{
		document.getElementById('more_operations_tr').style.display ='';
		document.getElementById('show_moreoperations_big').innerHTML = 'More Operations<img src="images/down_arr.gif" /> ';
	}	
}


function handle_showlegenddiv()
{
	if(document.getElementById('more_operations_tr').style.display=='')
	{
		/*document.getElementById('legend_tr').style.display = 'none';*/
		document.getElementById('more_operations_tr').style.display = 'none';
		document.getElementById('show_legenddiv_big').innerHTML = 'More Actions<img src="images/right_arr.gif" />';
	}	
	else
	{
		/*document.getElementById('legend_tr').style.display ='';*/
		document.getElementById('more_operations_tr').style.display ='';
		document.getElementById('show_legenddiv_big').innerHTML = 'Hide More Actions<img src="images/down_arr.gif" /> ';
	}	
}
function handle_export_orders()
{
	var exp_opt = document.frm_orders.cbo_export_order.value;
	if (exp_opt =='')
	{
		alert('Please select the export option');
		return false;	
	}
	if (exp_opt=='sel_ord') // case of selected order, check whether any orders ticked 
	{
		var atleast_one = false;
		var ids ='';
		for(i=0;i<document.frm_orders.elements.length;i++)
		{
			if (document.frm_orders.elements[i].type =='checkbox')
			{
				if (document.frm_orders.elements[i].name=='checkbox[]')
				{
					if (document.frm_orders.elements[i].checked==true)
					{
						atleast_one = true;
						if (ids!='')
							ids += '~';
				 		ids += document.frm_orders.elements[i].value;
					}
				}	
			}	
		}
		if (atleast_one==false)
		{
			alert('Please select the order(s) to export');
			return false;
		}
		/* Write the logic to submit the details to order export section here*/
		document.frm_orders.request.value 	= 'import_export';
		document.frm_orders.export_what.value 	= 'order';
		document.frm_orders.fpurpose.value 	= '';
		document.frm_orders.ids.value 	=ids;
		document.frm_orders.submit();
		
		
	}
	else
	{
	// var atleast_one = false;
		var ids ='';
		for(i=0;i<document.frm_orders.elements.length;i++)
		{
			if (document.frm_orders.elements[i].type =='checkbox')
			{
				if (document.frm_orders.elements[i].name=='checkbox[]')
				{
						atleast_one = true;
						if (ids!='')
							ids += '~';
				 		ids += document.frm_orders.elements[i].value;
				}	
			}	
		}
		/* Write the logic to submit the details to order export section here*/
		document.frm_orders.request.value 	= 'import_export';
		document.frm_orders.export_what.value 	= 'order';
		document.frm_orders.fpurpose.value 	= '';
		document.frm_orders.ids.value 	=ids;
		document.frm_orders.submit();
	   
	}
}

function printer_friendly() {
	frm = document.frm_orders;
	var atleastone 			= 0;
	var order_ids		= '';
	for(i=0;i<frm.elements.length;i++)
	{
		if (frm.elements[i].type =='checkbox' && frm.elements[i].name=='checkbox[]')
		{

			if (frm.elements[i].checked==true)
			{
			
				atleastone = 1;
				if (order_ids!='')
					order_ids += '~';
				 order_ids += frm.elements[i].value;
			}	
		}
	}
	if (atleastone==0) 
	{
		alert('Please select the Orders to Print Order Details');
		return false;
	} else {
    win_name = window.open('includes/orders/print_order_details.php?orderid='+order_ids,'po','height=600,width=900,scrollbars=yes,resizable=yes');
	win_name.focus();
	}
}

function print_packing() {
	frm = document.frm_orders;
	var atleastone 			= 0;
	var order_ids		= '';
	for(i=0;i<frm.elements.length;i++)
	{
		if (frm.elements[i].type =='checkbox' && frm.elements[i].name=='checkbox[]')
		{

			if (frm.elements[i].checked==true)
			{
			
				atleastone = 1;
				if (order_ids!='')
					order_ids += '~';
				 order_ids += frm.elements[i].value;
			}	
		}
	}
	if (atleastone==0) 
	{
		alert('Please select the Orders to Print Packing Slip');
		return false;
	} else {
    window.open('includes/orders/print_pack_slip.php?orderid='+order_ids,'po','height=600,width=600,scrollbars=yes,resizable=yes')
	}
}
function print_receipt() {
	frm = document.frm_orders;
	var atleastone 			= 0;
	var order_ids		= '';
	for(i=0;i<frm.elements.length;i++)
	{
		if (frm.elements[i].type =='checkbox' && frm.elements[i].name=='checkbox[]')
		{

			if (frm.elements[i].checked==true)
			{
			
				atleastone = 1;
				if (order_ids!='')
					order_ids += '~';
				 order_ids += frm.elements[i].value;
			}	
		}
	}
	if (atleastone==0) 
	{
		alert('Please select the Orders to Print the Receipt');
		return false;
	} else {
		win_name = window.open('includes/orders/showorder_receipt.php?f='+order_ids,'order_receipts', 'top=0, left=0, menubar=0, resizable=1, scrollbars=1, toolbar=0,width=950,height=600');
		win_name.focus();
	}
}
function handle_orderstatus_change()
{
	var msg = '';
	switch(document.frm_orders.cbo_orderstatus.value)
	{
		case 'NEW':
			msg = 'Are you sure you want to change the order status of selected orders to "Unviewed"?';
		break;
		case 'PENDING':
			msg = 'Are you sure you want to change the order status of selected orders to "Pending"?';
		break;
		case 'ONHOLD':
			msg = 'Are you sure you want to change the order status of selected orders to "On Hold"?';
		break;	
		case 'BACK':
			msg = 'Are you sure you want to change the order status of selected orders to "Back Order?"';
		break;	
		default:
			alert('Please select the order status in the dropdown box');
			return false;
		break;
	};
	if(msg != '')
	{
		var atleast_one = false;
		for(i=0;i<document.frm_orders.elements.length;i++)
		{
			if(document.frm_orders.elements[i].name == 'checkbox[]')
			{
				if(document.frm_orders.elements[i].checked==true)
					atleast_one = true;
			}
		}
		if(atleast_one==false)
		{
			alert('Please select orders to change the status');
			return false;
		}
		if(confirm(msg))
		{
			
				document.frm_orders.ch_ord_stat.value 	= document.frm_orders.cbo_orderstatus.value;
				document.frm_orders.chk_pay_stat.value 	= '';
				document.frm_orders.fpurpose.value 		= 'operation_changeorderstatus_do';
				document.frm_orders.submit();
		}
	}	
}



/*function export_to_xml()
{
		
		if(confirm('Are you sure want to export XML file?'))
		{
				var ecomhost = '<?php /*echo $ecom_hostname*/?>';			
				document.frm_orders.action = 'http://'+ecomhost+'/console/order_xml_export.php';
				document.frm_orders.submit();
		}	
}
*/


function export_xml(sortby,sortorder,recs,start,pg)
{
	var qrystr				= 'ord_sort_by='+sortby+'&ord_id=<?php echo $_REQUEST['ord_id']?>&ord_name=<?php echo $_REQUEST['ord_name']?>&ord_email=<?php echo $_REQUEST['ord_email']?>&ord_fromdate=<?php echo $_REQUEST['ord_fromdate']?>&ord_todate=<?php echo $_REQUEST['ord_todate']?>&ord_status=<?php echo $_REQUEST['ord_status']?>&ord_sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;

	
			if(confirm('Are you sure want to download the Order details file for Sage?'))
			{
				
				var ecomhost = '<?php echo $ecom_hostname?>';			
				opener.document.location = 'http://'+ecomhost+'/console/order_xml_export.php?'+qrystr;
				
			}
		
}




function handle_orderpaystatus_change()
{
	var msg = '';
	switch(document.frm_orders.cbo_paymentstatus.value)
	{
		case 'Pay_Failed':
			msg = 'Are you sure you want to change the payment status of selected orders to "Payment Failed"?';
		break;
		case 'Pay_Hold':
			msg = 'Are you sure you want to change the payment status of selected orders to "Placed on Account"?';
		break;	
		default:
			alert('Please select the payment status in the dropdown box');
			return false;
		break;
	};
	if(msg != '')
	{
		var atleast_one = false;
		for(i=0;i<document.frm_orders.elements.length;i++)
		{
			if(document.frm_orders.elements[i].name == 'checkbox[]')
			{
				if(document.frm_orders.elements[i].checked==true)
					atleast_one = true;
			}
		}
		if(atleast_one==false)
		{
			alert('Please select orders to change the payment status');
			return false;
		}
		if(confirm(msg))
		{
			
				document.frm_orders.ch_ord_stat.value 	= '';
				document.frm_orders.chk_pay_stat.value 	= document.frm_orders.cbo_paymentstatus.value;
				document.frm_orders.fpurpose.value 		= 'operation_changeorderpaystatus_do';
				document.frm_orders.submit();
		}
	}	
}
function handle_oldwebsite()
{
	win_name = window.open('http://iloveflooring.b-shop.co.uk/console/old_site.php','po','height=600,width=900,scrollbars=yes,resizable=yes');
	win_name.focus();
}
</script>
<script type = "text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script type="text/javascript">
jQuery.noConflict();
despatch_array = new Array();
var current_despatch_index = 0;
var waiting_for_despatch_return =0;
		var $ajax_j = jQuery; 
	$ajax_j(function () {
		var top = Math.max($ajax_j(window).height() / 2 - $ajax_j("#despatch_details_ajaxholder")[0].offsetHeight / 2, 0);
		var left = Math.max($ajax_j(window).width() / 2 - $ajax_j("#despatch_details_ajaxholder")[0].offsetWidth / 2, 0);
		$ajax_j("#despatch_details_ajaxholder").css('top', top-200 + "px");
		$ajax_j("#despatch_details_ajaxholder").css('right', (left-400) + "px");
		$ajax_j("#despatch_details_ajaxholder").css('position', 'fixed');
	});
	$ajax_j(function () {
		var top = Math.max($ajax_j(window).height() / 2 - $ajax_j("#div_defaultFlash_outer")[0].offsetHeight / 2, 0);
		var left = Math.max($ajax_j(window).width() / 2 - $ajax_j("#div_defaultFlash_outer")[0].offsetWidth / 2, 0);
		$ajax_j("#div_defaultFlash_outer").css('top',0 + "px");
		$ajax_j("#div_defaultFlash_outer").css('left', 0 + "px");
		$ajax_j("#div_defaultFlash_outer").css('position', 'fixed');
	});
	$ajax_j(function () {
		var top = Math.max($ajax_j(window).height() / 2 - $ajax_j("#proddet_loading_div_ajax")[0].offsetHeight / 2, 0);
		var left = Math.max($ajax_j(window).width() / 2 - $ajax_j("#proddet_loading_div_ajax")[0].offsetWidth / 2, 0);
		$ajax_j("#proddet_loading_div_ajax").css('top',top + "px");
		$ajax_j("#proddet_loading_div_ajax").css('left', (left-50) + "px");
		$ajax_j("#proddet_loading_div_ajax").css('position', 'fixed');
	});
	
	function showme(id) 
	{
		$ajax_j(id).show();		
	}
	function hideme(id)
	{
		$ajax_j(id).hide();
	}
function close_ajax_div()
{
	$ajax_j('#despatch_details_ajaxholder').hide();
	$ajax_j('#div_defaultFlash_outer').hide();
	$ajax_j('#proddet_loading_div_ajax').hide();
}
function change_despatch_class(id)
{
	var elTableRow = eval('document.getElementById("'+id+'")');
	var elTableCells = elTableRow.getElementsByTagName("td");
	for(ii=0;ii<elTableCells.length;ii++)
	{
		elTableCells[ii].className = 'orderlisting_despatch';
	}
}
function handle_despatch_bulk()
{
	waiting_for_despatch_return =0;
	current_despatch_index = 0;
	var atleastone 			= 0;
	var despatch_order_ids   = '';
	var qrystr 		= '';
	var frm 		= document.frm_bulk_despatch;
	var fpurpose 	= 'show_bulk_despatch';
	for(i=0;i<document.frm_orders.elements.length;i++)
	{
		if (document.frm_orders.elements[i].type =='checkbox' && document.frm_orders.elements[i].name=='checkbox[]')
		{

			if (document.frm_orders.elements[i].checked==true)
			{
				atleastone = 1;
				if (despatch_order_ids!='')
					despatch_order_ids += '~';
				 	despatch_order_ids += document.frm_orders.elements[i].value;
				}	
			
			
		}
	}
	if (atleastone==0)
	{
		alert('Please select an order to despatch');
	}
	else
	{
			if(confirm('Order(s) which have payment status as Paid or Placed on Account only will be allowed to despatch.\n\nAre you sure want to despatch the selected order(s)?'))
			{
				document.getElementById('ajax_div_holder').value = 'despatch_details_ajaxholder';
				/* Calling the ajax function */
				showme('#proddet_loading_div_ajax');
				showme('#div_defaultFlash_outer');
				waiting_for_despatch_return = 0;
				Handlewith_Ajax('includes/orders/ajax/order_listing_ajaxhandler.php','ajax_fpurpose='+fpurpose+'&'+qrystr+'&bulk_desp_id='+despatch_order_ids);
			}
	}
}
function handle_bulkdespatch_form()
{
	var totvalue = parseInt(document.getElementById('valid_despatch_total_count').value);
	if(totvalue>0)
	{
		if (confirm('Are you sure you want to despatch the selected orders?'))
		{
			var curordid = '';
			var arrDocForms = document.getElementsByTagName('form');
			waiting_for_despatch_return = 0;
			if (despatch_array.length)
			{
				for(var ix=0;ix<despatch_array.length;ix++)
				{
					despatch_array[ix] = '';
				}
			}	
			var normal_i = 0;
			for(var ix=0;ix<arrDocForms.length;ix++)
			{
				if(arrDocForms[ix].name.substr(0,18)=='bulkdespatch_form_')
				{
					curordidarr = arrDocForms[ix].name.split('_'); 
					curordid = curordidarr[2];
					despatch_array[normal_i] = curordid;
					normal_i++; 
					/*alert(arrDocForms[ix].name);
					p_obj = eval('document.getElementById("p_err_msg_'+curordid+'")');
					p_obj.style.display = 'block';*/
				}	
			}
			waiting_for_despatch_return = 1;
			do_bulk_despatch(0);
		}	
	}
	else
	{
		alert ('Sorry!! No orders to despatch');
		return false;
	}
}
function do_bulk_despatch(indx)
{
	waiting_for_despatch_return = 1;
	var fpurpose = 'do_bulk_despatch';
	var qrystr = '';
	var ordid = despatch_array[indx];
	var refno = '';
	var expdate = '';
	var addnote = '';
	var consoleuser = '<?php echo $_SESSION['console_id']?>';
	refobj = eval('document.getElementById("refno_'+ordid+'")');
	refno = refobj.value;
	expdateobj = eval('document.getElementById("date_'+ordid+'")');
	expdate = expdateobj.value;
	addnoteobj = eval('document.getElementById("addnote_'+ordid+'")');
	addnote = addnoteobj.value;
	targetobj 		= eval('document.getElementById("p_err_msg_'+ordid+'")');
	refobj.className ='block_class';
	refobj.readonly = "readonly";
	targetobj.style.display = 'block';
	qrystr = '&ordid='+ordid+'&refno='+refno+'&expdate='+expdate+'&addnote='+addnote+'&consoleuser='+consoleuser;
	document.getElementById('Despatch_bulk_button').style.display = 'none';
	window.location.hash = 'Details_'+ordid;
	Handlewith_Ajax('includes/orders/ajax/order_listing_ajaxhandler.php','ajax_fpurpose='+fpurpose+'&'+qrystr);
}
</script>
<div id="div_defaultFlash_outer" class="flashvideo_outer" style="display: none;"></div>
<div  id="despatch_details_ajaxholder" style="display:none;" ></div>
<div class="proddet_loading_div_ajax" id="proddet_loading_div_ajax" style="height:15px;display:none;padding:5px;"><img src="images/ajax-loader.gif" alt="loading..." ></div>
<form method="post" name="frm_orders" class="frmcls" action="home.php">
<input type="hidden" name="request" value="order_archive" />
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="ids" value="" />
<input type="hidden" name="export_what" value="" />
<input type="hidden" name="search_click"  value="" />
<input type="hidden" name="ch_ord_stat" id="ch_ord_stat" value="" />
<input type="hidden" name="chk_pay_stat" id="chk_pay_stat" value="" />
<input type="hidden" name="action_source" id="action_source" value="order_listing" />
<input type="hidden" name="table_name"  value="<?php echo $table_name ?>" />
<input type="hidden" name="where_conditions" id="where_conditions" value="<?php echo $where_conditions?>" />
<input type="hidden" name="sort_by" id="sort_by" value="<?php echo $sort_by?>" />
<input type="hidden" name="sort_order" id="sort_order" value="<?php echo $sort_order?>" />
<input type="hidden" name="ajax_div_holder" id="ajax_div_holder" value="" />

<input type="hidden" name="ser_order_placed_from" value="<?php echo $_REQUEST['order_placed_from']?>" />
<input type="hidden" name="ser_ord_status" value="<?php echo $_REQUEST['ord_status']?>" />
<input type="hidden" name="ser_ord_name" value="<?php echo $_REQUEST['ord_name']?>" />
<input type="hidden" name="ser_ord_email" value="<?php echo $_REQUEST['ord_email']?>" />
<input type="hidden" name="ser_ord_fromdate" value="<?php echo $_REQUEST['ord_fromdate']?>" />
<input type="hidden" name="ser_ord_todate" value="<?php echo $_REQUEST['ord_todate']?>" />
<input type="hidden" name="ser_ord_stores" value="<?php echo $_REQUEST['ord_stores']?>" />
<input type="hidden" name="ser_ord_sort_by" value="<?php echo $sort_by?>" />
<input type="hidden" name="ser_ord_sort_order" value="<?php echo $sort_order?>" />
<input type="hidden" name="ser_pg" value="<?php echo $pg?>" />
<input type="hidden" name="ser_start" value="<?php echo $start?>" />
<input type="hidden" name="ser_records_per_page" value="<?php echo $_REQUEST['records_per_page']?>" />
<input type="hidden" name="edit_id" id="edit_id" value="" />
<table border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span> List Archived Orders</span></div></td>
    </tr>
	<tr>
	  <td align="left" valign="middle" class="helpmsgtd_main" colspan="3">
	  <?php 
		  Display_Main_Help_msg($help_arr,$help_msg);
	  ?>	 </td>
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
	<?php
		if ($db->num_rows($ret_order))
		{
	?>
	<tr><td colspan="3" align="right" valign="middle" class="sorttd"><?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?></td></tr> 
	<?php
		}
	?>
    <tr>
      <td height="48" colspan="3" class="sorttd">
		<div class="sorttd_div">
		<table width="100%" border="0" cellpadding="1" cellspacing="0">
        <tr>
          <td width="18%" align="left" valign="middle">Order Id<br/>  
            <input name="ord_id" type="text" class="textfeild" id="ord_id" value="<?php echo $_REQUEST['ord_id']?>" /></td>
          <td width="23%" align="left" valign="middle">Show<br/>
            <?php
					$ordstatus_array = array(
										'-1'=>'All Order',
										'NEW'=>'Unviewed Orders',
										'PENDING'=>'Pending Orders',
										'DESPATCHED'=>'Despatched Orders',
										'REFUNDED'=>'Fully Refunded Orders',
										'ONHOLD'=>'On Hold Orders',
										'DEPOSIT'=>'Product Deposit Orders',
										'BACK'=>'Back Orders',
										'CANCELLED'=>'Cancelled Orders',
										'pay_on_account'=>'Pay on Account Orders',
										'NOT_AUTH'=>'Incomplete Orders'
										);
					echo generateselectbox('ord_status',$ordstatus_array,$_REQUEST['ord_status']);
				?></td>
          <td width="25%" align="left" valign="middle">Records Per Page 
            <br />
            <input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="5" maxlength="5" value="<?php echo $records_per_page?>" /></td>
          <td width="14%" align="left" valign="middle" nowrap="nowrap">Sort By <br />
            <?php echo $sort_option_txt;?> in <?php echo $sort_by_txt?></td>
          <td width="11%" align="center" valign="middle"><div id='show_morediv_big' onclick="handle_showmorediv()" title="Click here"> Filters<img src="<?php echo ($disp_more)?'images/down_arr.gif':'images/right_arr.gif'?>" /></div></td>
          <td width="9%" align="left" valign="middle">
		  <div id="top_godiv" style="<?php echo ($disp_more)?'display:none':''?>">
		  <input name="Search_go" type="submit" class="red" id="Search_go" value="Go" onclick="document.frm_orders.search_click.value=1" />
          <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_ORDER_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>		  </div>			</td>
        </tr>
        <tr>
          <td colspan="8" align="left" valign="middle">
		   <table width="100%" border="0" cellspacing="0" cellpadding="1">
<tr id="listmore_tr1" style="<?php echo ($disp_more)?'':'display:none'?>">
<td align="left">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td align="left">
		<script type="text/javascript">
			$(function() {
				$( "#ord_fromdate" ).datepicker({ dateFormat: "dd-mm-yy", changeMonth: true,changeYear: true, numberOfMonths: 1,showButtonPanel: false });
				$( "#ord_todate" ).datepicker({ dateFormat: "dd-mm-yy", changeMonth: true,changeYear: true, numberOfMonths: 1,showButtonPanel: false });
			});
			</script>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td width="16%">Customer/ Company Name like</td>
			<td width="19%">Email</td>
			<td width="11%">Order Placed From</td>
			<td align="left" colspan="5">Between Dates</td>
			<td width="14%" align="left"><?php
			// Get the list of stores available for the current site
			$sql_stores = "SELECT shop_id,shop_title 
							FROM 
								sites_shops 
							WHERE 
								sites_site_id = $ecom_siteid 
							ORDER BY 
								shop_order";
			$ret_stores = $db->query($sql_stores);
			if ($db->num_rows($ret_stores))
			{
				echo 'Warehouse ';
			}					
			?>			</td>
			<td></td>
			<td width="5%"></td>
			</tr>
			<tr>
			<td><input type="text" class="textfeild" name="ord_name" id="ord_name" value="<?php echo $_REQUEST['ord_name']?>"/></td>
			<td><input name="ord_email" id="ord_email" type="text" size="23" value="<?php echo $_REQUEST['ord_email']?>" class="textfeild" /></td>
			<td >
			<?php 
			$order_placed_from_array = array('-1'=>'All',
			'WEB' =>'Web',
			'IPHONE' => 'Iphone',
			'MOBILE' => 'Mobile');
			echo generateselectbox('order_placed_from',$order_placed_from_array,$_REQUEST['order_placed_from']);?>
			</td>

			<td width="8%" align="left"><input name="ord_fromdate" id="ord_fromdate" class="textfeild" type="text" size="12" value="<?php echo $_REQUEST['ord_fromdate']?>" /></td>
			<td width="4%" align="center" colspan="2">and </td>
			<td width="6%" align="left"><input name="ord_todate" class="textfeild" id="ord_todate" type="text" size="12" value="<?php echo $_REQUEST['ord_todate']?>" /></td>
			<td width="4%" align="left"><?php /*?><a href="javascript:show_calendar('frm_orders.ord_todate');" onMouseOver="window.status='Date Picker';return true;" onMouseOut="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a><?php */?></td>
			<td width="14%" align="left"><?php
			if ($db->num_rows($ret_stores))
			{
			$store_arr = array('0'=>'Web');
			while ($row_stores = $db->fetch_array($ret_stores))
			{
			$store_arr[$row_stores['shop_id']] = stripslashes($row_stores['shop_title']);
			} 
			echo generateselectbox('ord_stores',$store_arr,$_REQUEST['ord_stores']);
			}	
			?></td>
			<td width="13%" align="right"><div id="bottom_godiv" style="<?php echo ($disp_more)?'':'display:none'?>">
			<input name="Search_go2" type="submit" class="red" id="Search_go2" value="Go" onClick="document.frm_orders.search_click.value=1" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_ORDER_GO')?>')"; onMouseOut="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> </div></td>
							
			</tr>
			<?php /* iphone Search*/ ?>
			
			</table></td>
		</tr>
		</table></td>
	</tr>
	</table></td>
</tr>
</table>
		   </td>
          </tr>
      </table>
	  </div>  </td>
    </tr>
    
    
    <tr>
      <td colspan="3" class="listingarea">
	  <div class="listingarea_div">
	  <table  border="0" cellpadding="1" cellspacing="0" class="listingtable">
    <tr>
    <td colspan="<?php echo round($colspan/2)?>" align="left" valign="middle" class="listeditd">
    <?php
   	if ($db->num_rows($ret_order))
	{
		$sql_sagecheck = "SELECT site_sageexporter_active 
						FROM 
							sites 
						WHERE 
							site_id = $ecom_siteid 
						LIMIT 
							1";
		$ret_sagecheck = $db->query($sql_sagecheck);
		if($db->num_rows($ret_sagecheck))
		{
			$row_sagecheck = $db->fetch_array($ret_sagecheck);
			$show_sageexport = ($row_sagecheck['site_sageexporter_active']==1)?true:false;
		}
    ?>
    <table>
    <tr>
    	<td colspan="<?php echo round($colspan/2)?>">
		<table width="100%" cellpadding="1" cellspacing="0" border="0">
			<tr>
			<td>
			<a href="#" onclick="backto_order(-1)" class="backtoorderlist" title="Move archived orders back to My orders">Back to orders </a>
			</td>
			<td align="right">
						</td>
			</tr>
			</table>
		</td>
        
        <?php /*<td><a href="#" class="archivelist" onclick="go_archive('<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>','<?=$_REQUEST['categoryid']?>')">Archive</a></td>*/?>
        <!--<td><input type="button" name="export_xml" id="export_xml" value="Export To XML" onclick="export_to_xml()" class="red" /></td>-->
        <?php
        /*
        if($show_sageexport)
        {
		?>
        <td><a name="export_xml" id="export_xml" href="#" class="sageexporter" onclick="export_xml('<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>','<?=$_REQUEST['categoryid']?>')">Sage Exporter</a></td>
        <?php
		}
		*/
        ?>
      </tr>
      </table>  
        
    <?php
	}
    ?>
    </td>
      <td colspan="<?php echo round($colspan/2)?>" align="right" valign="middle" class="listeditd">&nbsp;</td>
    </tr>
	
	 <tr id="legend_tr" style="display:none1">
      <td colspan="<?php echo $colspan;?>" align="center" class="listingtableheader">
	  <table width="93%" border="0" align="center" cellpadding="1" cellspacing="0">
        <tr>
		  <td width="1%"><div style="width:10px; height:10px; border:solid 1px #550000;vertical-align:middle; text-align:center" class="orderlisting_new">O</div></td>
          <td align="left" width="15%">Unviewed Orders</td>
          <td width="1%"><div style="width:10px; height:10px; border:solid 1px #550000;vertical-align:middle; text-align:center" class="orderlisting_pending">O</div></td>
          <td align="left" width="12%">Pending Order</td>
          <td width="1%"><div style="width:10px; height:10px; border:solid 1px #550000;vertical-align:middle; text-align:center" class="orderlisting_despatch">O</div></td>
          <td align="left" width="15%">Despatched Orders</td>
            <td width="1%"><div style="width:10px; height:10px; border:solid 1px #550000;vertical-align:middle; text-align:center" class="orderlisting_hold">O</div></td>
          <td align="left" width="15%">On Hold Orders</td>
          <td width="1%"><div style="width:10px; height:10px; border:solid 1px #550000;vertical-align:middle; text-align:center" class="orderlisting_backorder">O</div></td>
          <td align="left" width="12%">Back Orders</td>
          <td width="1%"><div style="width:10px; height:10px; border:solid 1px #550000;vertical-align:middle; text-align:center" class="orderlisting_cancelled">O</div></td>
          <td align="left" width="15%">Cancelled Orders</td>
        </tr>
      </table></td>
    </tr>
     <?php  
	 	echo table_header($table_headers,$header_positions); 
		if ($db->num_rows($ret_order))
		{ 
			$hover_title = '';//'Click to view the order details';
			$srno = 1;
			$page_ordref = $page_ordtot = 0;
			while ($row_order = $db->fetch_array($ret_order))
			{
				//print_r($row_order);
				
				$page_ordtot = $page_ordtot + $row_order['order_totalprice'];
				$page_ordref = $page_ordref + $row_order['order_refundamt'];
				//$cls = ($srno%2==0)?'listingtablestyleA':'listingtablestyleB';
				$cls = ($class_arr[$row_order['order_status']])?$class_arr[$row_order['order_status']]:'listingtablestyleB';
	 ?>
        <tr style="cursor:pointer" id="mainordertd_<?php echo $row_order['order_id']?>">
          <td align="center" valign="middle" class="<?php echo $cls?>" width="7%"><?php if($row_order['order_placed_from']=='IPHONE') {?><img src="images/phoneicon.gif" alt="iphone" title="iphone"><?php }else if($row_order['order_placed_from']=='MOBILE'){?><img src="images/mobileicon.gif" alt="mobile" title="mobile"><?php }else{ ?><img src="images/blank.gif"><?php } ?><input type="checkbox" name="checkbox[]" id="checkbox[]" value="<?php echo $row_order['order_id']?>" /></td>
          <td align="left" valign="middle" class="<?php echo $cls?>" width="5%" title="<?php echo $hover_title?>">
		  <?php echo $row_order['order_id']?></td>
		  <td align="center" valign="middle" class="<?php echo $cls?>" width="8%" title="<?php echo $hover_title?>" >
		  <?php 
		  		echo dateFormat($row_order['order_date'],'datetime_break');
		  ?>		  </td>
          <td align="left" valign="middle" class="<?php echo $cls?>" width="15%" >
          <?php echo stripslashes($row_order['order_custtitle']).stripslashes($row_order['order_custfname']).' '.stripslashes($row_order['order_custsurname'])?>
          </td>
		  <td align="left" valign="middle" class="<?php echo $cls?>" width="15%" title="<?php echo $hover_title?>" >
		  <?php echo stripslashes($row_order['order_custemail'])?></td>
		  <?php /*?><td align="center" valign="middle" class="<?php echo $cls?>" width="1%" title="<?php echo $hover_title?>" >
		  <?php echo ucwords($row_order['order_pre_order'])?></td><?php */?>
		  <td align="left" valign="middle" class="<?php echo $cls?>" width="10%" >
		  <div id="list_orderstatus_div_<?php echo $row_order['order_id']?>">
		  <?php 
		  	echo getorderstatus_Name($row_order['order_status']);
		  ?>
		  </div>
		   </td>
		  <td align="right" valign="middle" class="<?php echo $cls?>" width="8%" title="<?php echo $hover_title?>" >
		  <?php echo print_price_selected_currency($row_order['order_totalprice'],$row_order['order_currency_convertionrate'],$row_order['order_currency_symbol'],true)?>		  </td>
		  <td align="right" valign="middle" class="<?php echo $cls?>" width="8%" title="<?php echo $hover_title?>" >
		  <?php echo print_price_selected_currency($row_order['order_refundamt'],$row_order['order_currency_convertionrate'],$row_order['order_currency_symbol'],true)?></td>
		  <td align="left" valign="middle" class="<?php echo $cls?>" width="7%" title="<?php echo $hover_title?>" >
		  <?php echo getpaymenttype_Name($row_order['order_paymenttype'])?></td>
		  <td align="left" valign="middle" class="<?php echo $cls?>" width="10%" >
		  <?php 
		  	echo getpaymentstatus_Name($row_order['order_paystatus']);
		  	/*if ($row_order['order_paystatus_changed_manually']==1)
		  	{
		  		$pay_usr = 'Changed : '.getConsoleUserName($row_order['order_paystatus_changed_manually_by']).'<br/> On: '.dateFormat($row_order['order_paystatus_changed_manually_on'],'datetime');	
		  ?>
		  		 <a href="#" onmouseover ="ddrivetip('<?php echo $pay_usr ?>')"; onmouseout="hideddrivetip()">
			  	<img src="images/down_arr.gif" align="center" border="0"/>			  	</a>
		  <?php			
		  	}	*/
		  ?> </td>
		</tr>
        <?php
			}
			$cls = 'listingtableheader';
		?>
				<tr>
					<td colspan="6" class="<?php echo $cls?>" align="right">Total in  Default Currency</td>
					<td align="right" class="<?php echo $cls?>"><?php echo display_price($page_ordtot)?></td>
					<td align="right" class="<?php echo $cls?>"><?php echo display_price($page_ordref)?></td>
					<td align="right" class="<?php echo $cls?>">&nbsp;</td>
					<td align="right" class="<?php echo $cls?>">&nbsp;</td>
	<?php	
		}
		else
		{
	?>
			<tr>
			  <td align="center" valign="middle" class="norecordredtext" colspan="<?php echo $colspan?>"> No <?php echo $page_type?> found. </td>
			</tr>
        <?php
		}
	?>
	<tr>
	 <td colspan="<?php echo round($colspan/2)?>" class="listeditd" align="left" valign="middle">
	 <?php
   		if ($db->num_rows($ret_order))
		{
	?>		<table>
    		<tr>
    		<td>
			<table width="100%" cellpadding="1" cellspacing="0" border="0">
			<tr>
			<td>
			<a href="#" onclick="backto_order(-1)" class="backtoorderlist" title="Move archived orders back to My orders">Back to orders </a>
			</td>
			<td align="right">
			</td>
			</tr>
			</table>
			</td>
            <?php /*<td><a href="#" class="archivelist" onclick="go_archive('<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>','<?=$_REQUEST['categoryid']?>')">Archive</a></td>*/?>
           <?php
           /*
			if($show_sageexport)
			{
			?>
            <td><a name="export_xml" id="export_xml" href="#" class="sageexporter" onclick="export_xml('<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>','<?=$_REQUEST['categoryid']?>')">Sage Exporter</a></td>
            <?php
		}
           */
            ?>
            </tr>
            </table>
    <?php
		}
    ?>    </td>
     
    </tr>
      </table>
	  </div></td>
    </tr>
	<tr>
	 <td align="right" valign="middle" class="listing_bottom_paging" colspan="2">
    <?php 
		if ($db->num_rows($ret_order))
		{
			paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan);
		}
	?>	</td>
	</tr>
    <tr>
	 <td colspan="3" class="listeditd">&nbsp;	 </td>
	 </tr>
	  <?php 
		/*if ($db->num_rows($ret_order))
		{
		?>
	<tr>
		<td colspan="3" align="left" valign="middle">
			<div class="listingarea_div">
				<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td width="100%" class="seperationtd" align="left">Export Order(s)</td>
				</tr>
				<tr>
					<td width="100%" class="seperationtd" align="left">
					<select name="cbo_export_order" id="cbo_export_order">
					<option value="">-- Select --</option>
					<option value="sel_ord">Export Selected Orders</option>
					<option value="all_ord">Export All Orders</option>
					</select>&nbsp;
					<input type="button" name="submit_orderexport" id="submit_orderexport" value="Export Now" class="red" onclick="handle_export_orders()" />
					</td>
				</tr>
				</table>
			</div>
		</td>
	</tr>
	 <? }*/?>
    </table>
</form>