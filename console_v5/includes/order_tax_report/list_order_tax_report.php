<?php
	/*#################################################################
	# Script Name 	: 	list_order_tax_report.php
	# Description 		: Page for Listing Tax report of Orders
	# Coded by 		: Sny
	# Created on		: 04-Mar-2011
	# Modified by		: Sny
	# Modified On		: 04-Mar-2011
	#################################################################*/
//#Define constants for this page
$table_name			= 'orders';
$page_type			= 'Orders';
$help_msg 			= get_help_messages('PROD_ORDER_MESS_TAX');
if($_REQUEST['paid_only']=='')
	$_REQUEST['paid_only'] =1;
//'This section lists the Product Category Groups available on the site. Here there is provision for adding a Product Category Groups, editing, & deleting it.';
$table_headers 		= array('Order Id','Date','Net Order Value','Vat Amount','Gross Order Value','Country of Delivery');
$header_positions	= array('center','center','right','right','right','center');
$colspan 			= count($table_headers);

//#Search terms
$search_fields = array('ord_id','ord_status','ord_fromdate','ord_todate','ord_stores','paid_only','chk_vat_zero_only');
//#Sort
$sort_by 		= (!$_REQUEST['ord_sort_by'])?'order_date':$_REQUEST['ord_sort_by'];
$sort_order 		= (!$_REQUEST['ord_sort_order'])?'DESC':$_REQUEST['ord_sort_order'];
$sort_options 		= array('order_date' => 'Order Date','order_status'=>'Order Status');
$sort_option_txt 	= generateselectbox('ord_sort_by',$sort_options,$sort_by);
$sort_by_txt		= generateselectbox('ord_sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);


$query_string 		= "request=order_tax_report&ord_sort_by=".$sort_by."&ord_sort_order=".$sort_order;

// Class Array for classes
$class_arr = array 
					(
						'NEW'=>'orderlisting_new',
						'PENDING'=>'orderlisting_pending',
						'DESPATCHED'=>'orderlisting_despatch',

						'ONHOLD'=>'orderlisting_hold',
						'BACK'=>'orderlisting_backorder',
					);
					
if($sort_by=='order_status')
	$sort_by='ordstat';
foreach($search_fields as $v) {
	$query_string 	.= "&$v=".$_REQUEST[$v]."";//#For passing searh terms to javascript for passing to different pages.
}
//##########################################################################################################
// Building the query to be used to display the orders
//##########################################################################################################
//$where_conditions 	= "WHERE sites_site_id=$ecom_siteid AND order_specialtax_orgtotalamt>0";
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
		$where_conditions .= " AND order_status = '".$_REQUEST['ord_status']."' ";
	}
}
else // If coming to the page for the first time, show all orders
{
	
	//$where_conditions .= " AND order_status = 'NEW' ";
	$_REQUEST['ord_status'] = -1;
}

//##########################################################################################################
// Check whether order id is given
if($_REQUEST['ord_id'])
{
	$where_conditions .= " AND order_id='".add_slash($_REQUEST['ord_id'])."'";
}
if($_REQUEST['paid_only']==1)
{
	$where_conditions .= " AND order_paystatus='Paid'";
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

if($_REQUEST['chk_vat_zero_only']!=1)
{
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
}
/////////////////////////////////////////////////////////////////////////////////////

$sql_qry = "SELECT 
				order_id,customers_customer_id,sites_site_id,sites_shops_shop_id , order_subtotal,order_deliverytotal,order_deliverytype,order_deliverylocation,
				order_date,order_custtitle,order_custfname,order_custmname,
				order_custsurname,order_custemail,order_paymenttype,order_paymentmethod,
				order_paystatus,order_status,order_refundamt,order_deposit_amt,
				order_deposit_cleared,order_currency_symbol,order_currency_convertionrate,
				order_totalprice,order_totalauthorizeamt,order_pre_order,
				order_paystatus_changed_manually,order_paystatus_changed_manually_by,
				order_paystatus_changed_manually_on,order_cancelled_by,
				order_cancelled_on,order_cancelled_from,
				order_specialtax_calculation,order_specialtax_totalamt,order_specialtax_productamt,
				order_specialtax_deliveryamt,order_specialtax_extrashippingamt,
				order_specialtax_orgtotalamt,order_specialtax_orgproductamt,
				order_specialtax_orgdeliveryamt,order_specialtax_orgextrashippingamt, 
				CASE order_status 
				WHEN 'NEW' THEN 'Unviewed'
				else
					order_status
				END as ordstat  
			FROM 
				$table_name 
				$where_conditions 
			ORDER BY 
				$sort_by $sort_order ";
 if($_REQUEST['chk_vat_zero_only']!=1)
 {
 	$sql_qry  .="				
			LIMIT 
				$start,$records_per_page ";
 }				
$ret_order = $db->query($sql_qry);
?>
<script type="text/javascript">
function edit_order(edit_id)
{
	if (edit_id==-1)/* Case came here by clicking the details icon*/
	{
		/* Check Whether any order is selected*/
		var tot_sel = 0;
		for(i=0;i<document.from_order_tax.elements.length;i++)
		{
			if (document.from_order_tax.elements[i].name =='checkbox[]')
			{
				if(document.from_order_tax.elements[i].checked)
				{
					tot_sel++;
					edit_id = document.from_order_tax.elements[i].value;
				}	
			}
		}
		if(tot_sel==0)
		{
			alert('Please select the order for which the tax details is to be viewed');
			return false;	
		}
		else if (tot_sel>1)
		{
			alert('Please select only one order at a time');
			return false;		
		}
	}
	document.from_order_tax.edit_id.value 	= edit_id;
	document.from_order_tax.fpurpose.value 	= 'order_details';
	document.from_order_tax.submit();
}
function handle_showlegenddiv()
{
	if(document.getElementById('legend_tr').style.display=='')
	{
		document.getElementById('legend_tr').style.display = 'none';
		document.getElementById('show_legenddiv_big').innerHTML = 'Show Colour Codes<img src="images/right_arr.gif" />';
	}	
	else
	{
		document.getElementById('legend_tr').style.display ='';
		document.getElementById('show_legenddiv_big').innerHTML = 'Hide Colour Codes<img src="images/down_arr.gif" /> ';
	}	
}
function printer_friendly() {
	frm = document.from_order_tax;
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

</script>
<form method="post" name="from_order_tax" class="frmcls" action="home.php">
<input type="hidden" name="request" value="order_tax_report" />
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="ids" value="" />
<input type="hidden" name="export_what" value="" />
<input type="hidden" name="search_click"  value="" />
<input type="hidden" name="ch_ord_stat" id="ch_ord_stat" value="" />
<input type="hidden" name="chk_pay_stat" id="chk_pay_stat" value="" />
<input type="hidden" name="action_source" id="action_source" value="order_listing" />

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
      <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span>List Order Tax Report</span></div></td>
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
    <tr>
      <td height="48" colspan="3" class="sorttd">
		<div class="sorttd_div">
		<table width="100%" border="0" cellpadding="1" cellspacing="1">
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
										'ONHOLD'=>'On Hold Orders',
										'BACK'=>'Back Orders',
										'pay_on_account'=>'Pay on Account Orders',
										);
					echo generateselectbox('ord_status',$ordstatus_array,$_REQUEST['ord_status']);
				?></td>
          <td width="25%" align="left" valign="middle">Show
            <br />
            <input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="5" maxlength="5" value="<?php echo $records_per_page?>" />
            <?php echo $page_type?> Per Page </td>
          <td width="14%" align="left" valign="middle" nowrap="nowrap">Sort By <br />
            <?php echo $sort_option_txt;?> in <?php echo $sort_by_txt?></td>
          <td width="11%" align="center" valign="middle">&nbsp;</td>
          <td width="9%" align="left" valign="middle">
		  <div id="top_godiv">
		  <input name="Search_go" type="submit" class="red" id="Search_go" value="Go" onClick="document.from_order_tax.search_click.value=1" />
          <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_ORDER_GO')?>')"; onMouseOut="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>		  </div>			</td>
        </tr>
        <tr>
          <td colspan="8" align="left" valign="middle">
		   <table width="100%" border="0" cellspacing="1" cellpadding="1">
            
            <tr id="listmore_tr1">
              <td align="left">
			  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td colspan="6" align="left"><table width="100%" border="0" cellspacing="1" cellpadding="1">
                          <tr>
                            <td width="12%" align="left">Between Dates</td>
                            <td align="left"><a href="javascript:show_calendar('from_order_tax.ord_fromdate');" onMouseOver="window.status='Date Picker';return true;" onMouseOut="window.status='';return true;"></a><a href="javascript:show_calendar('from_order_tax.ord_todate');" onMouseOver="window.status='Date Picker';return true;" onMouseOut="window.status='';return true;"></a></td>
                            <td align="left">&nbsp;</td>
                            <td align="left">&nbsp;</td>
                            <td align="left">&nbsp;</td>
                            <td align="left"><?php
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
						?></td>
                            <td align="left">&nbsp;</td>
                          </tr>
                          <tr>
                            <td align="left"><input name="ord_fromdate" class="textfeild" type="text" size="12" value="<?php echo $_REQUEST['ord_fromdate']?>" /></td>
                            <td width="6%" align="left"><a href="javascript:show_calendar('from_order_tax.ord_fromdate');" onMouseOver="window.status='Date Picker';return true;" onMouseOut="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a></td>
                            <td width="6%">and</td>
                            <td width="11%" align="left"><input name="ord_todate" class="textfeild" id="ord_todate" type="text" size="12" value="<?php echo $_REQUEST['ord_todate']?>" /></td>
                            <td width="13%" align="left"><a href="javascript:show_calendar('from_order_tax.ord_todate');" onMouseOver="window.status='Date Picker';return true;" onMouseOut="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a></td>
                            <td width="19%" align="left"><?php
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
                            <td width="33%" align="left">
                            Payment Status 
                              <select name="paid_only" id="paid_only">
                            <option value="1" <?php echo ($_REQUEST['paid_only']==1)?'selected="yes"':''?>>Paid Only</option>
                            <option value="2"<?php echo ($_REQUEST['paid_only']==2)?'selected="yes"':''?>>Any</option>
                            </select>
                            <input type="checkbox" name="chk_vat_zero_only" id="chk_vat_zero_only" value="1" <?php echo ($_REQUEST['chk_vat_zero_only']==1)?'checked="checked"':''?>/>
Show Orders with Zero VAT Only                            </td>
                          </tr>
                        </table></td>
                      </tr>
                    </table></td>
                    </tr>
                </table></td>
              </tr>
          </table></td>
          </tr>
      </table> 
	  	</div>
	       </td>
    </tr>
    <tr>
    <td width="3%" class="listeditd">
    <?php
   	if ($db->num_rows($ret_order))
	{
    ?>
    	<?php /*?><a href="#" onClick="edit_order(-1)" class="editlist" title="View Order Tax Details">Details</a><?php */?>
    <?php
	}
    ?>    </td>
      <td width="87%" align="center" class="listeditd">
      <?php 
	   if($_REQUEST['chk_vat_zero_only']!=1)// case if orders with vat amount = 0 only is to be considered
	 {
					if ($db->num_rows($ret_order))
					{
						paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan);
					}
	}				
					?>	  </td>
       <td width="15%" align="right" class="listeditd"><div id='show_legenddiv_big' onclick="handle_showlegenddiv()" title="Click here">Show Colour Codes<img src="images/right_arr.gif" /></div>	  </td>
    </tr>
    <tr id="legend_tr" style="display:none">
      <td colspan="3" align="center" class="listeditd"><table width="93%" border="0" align="center" cellpadding="1" cellspacing="1">
        <tr>
          <td width="1%"><div style="width:10px; height:10px; border:solid 1px #550000;vertical-align:middle; text-align:center" class="orderlisting_new">A</div></td>
          <td align="left" width="15%">Unviewed</td>
          <td width="1%"><div style="width:10px; height:10px; border:solid 1px #550000;vertical-align:middle; text-align:center" class="orderlisting_pending">A</div></td>
          <td align="left" width="15%">Pending</td>
          <td width="1%"><div style="width:10px; height:10px; border:solid 1px #550000;vertical-align:middle; text-align:center" class="orderlisting_despatch">A</div></td>
          <td align="left" width="15%">Despatched</td>
            <td width="1%"><div style="width:10px; height:10px; border:solid 1px #550000;vertical-align:middle; text-align:center" class="orderlisting_hold">A</div></td>
          <td align="left" width="15%">On Hold</td>
          <td width="1%"><div style="width:10px; height:10px; border:solid 1px #550000;vertical-align:middle; text-align:center" class="orderlisting_backorder">A</div></td>
          <td align="left" width="15%">Back Order</td>
          <td width="1%"><div style="width:10px; height:10px; border:solid 1px #550000;vertical-align:middle; text-align:center" class="orderlisting_cancelled">A</div></td>
          <td align="left" width="15%">Cancelled</td>
        </tr>
      </table></td>
    </tr>
    
    <tr>
      <td colspan="3" class="listingarea">
	  <div class="listingarea_div">
	  <table  border="0" cellpadding="1" cellspacing="1" class="listingtable">
     <?php  
	 if($_REQUEST['chk_vat_zero_only']==1)// case if orders with vat amount = 0 only is to be considered
	 {
	 	$order_id_arr = array(-1);
	 	if ($db->num_rows($ret_order))
		{ 
			$srno = 1;
			$page_ordref = $page_ordtot = 0;
			$page_netorder =	$page_vatamt  = $page_grossorder=0;
			while ($row_order = $db->fetch_array($ret_order))
			{
				$taxcalculation_required = 1;
				// Get the id of the country in delivery details
				$sql_del_country = "SELECT delivery_country FROM order_delivery_data WHERE orders_order_id = ".$row_order['order_id'];
				$ret_del_country = $db->query($sql_del_country);
				if($db->num_rows($ret_del_country))
				{
					$row_del_country = $db->fetch_array($ret_del_country);
					$del_country = stripslashes($row_del_country['delivery_country']);
					// Get the id of current coutnry
					$sql_cname = "SELECT country_id FROM general_settings_site_country WHERE country_name = '".addslashes($del_country)."' 
										AND sites_site_id = $ecom_siteid LIMIT 1";
					$ret_cname = $db->query($sql_cname);
					if($db->num_rows($ret_cname))
					{
						$row_cname = $db->fetch_array($ret_cname);
						$delivery_country_id = $row_cname['country_id'];
						// check whether this country is under a delivery location where tax is not applicable
						// get the delivery method id for current website
						$sql_del = "SELECT delivery_methods_delivery_id FROM 
										general_settings_site_delivery 
									WHERE 
										sites_site_id = $ecom_siteid 
									LIMIT 
										1";
						$ret_del = $db->query($sql_del);
						$row_del = $db->fetch_array($ret_del);
						$del_id = $row_del['delivery_methods_delivery_id'];
						// get the id of location 
						$sql_locid = "SELECT delivery_site_location_location_id 
										FROM 
											general_settings_site_country_location_map 
										WHERE 
											delivery_methods_deliverymethod_id = $del_id 
											AND general_settings_site_country_country_id = $delivery_country_id 
											AND sites_site_id = $ecom_siteid 
										LIMIT 
											1";
						$ret_locid = $db->query($sql_locid);
						if($db->num_rows($ret_locid))
						{
							$row_locid = $db->fetch_array($ret_locid);
							$sql_loc = "SELECT location_tax_applicable 
										FROM 
											delivery_site_location 
										WHERE 
											location_id = ".$row_locid['delivery_site_location_location_id']."
										LIMIT 
											1";
							$ret_loc = $db->query($sql_loc);
							if($db->num_rows($ret_loc))
							{
								$row_loc = $db->fetch_array($ret_loc);
								if ($row_loc['location_tax_applicable']==0)
									$taxcalculation_required = 0;
							}
						}
						
						$netorder = $vatamt = $grossorder = 0;
						$netorder_1 = $netorder_2 = $deltotal_1 = $deltotal_2 = 0;
						$deltotal = 0;
						if($taxcalculation_required==0)
						{
							$netorder = $grossorder = $row_order['order_totalprice'];
							$vatamt = 0;
						}
						else
						{
							$netorder_1 = $row_order['order_subtotal']/1.20; 
							$netorder_2 = $netorder_1 * 20/100;
							
							$deltotal_1 = $row_order['order_deliverytotal']/1.20;
							$deltotal_2 = $deltotal_1*20/100;
							
							$netorder = $netorder_1 + $deltotal_1;
							
							$vatamt =  $netorder_2 + $deltotal_2;
							
							$grossorder = $netorder + $vatamt;
							
						}
						if($vatamt==0)
						{
							$order_id_arr[] = $row_order['order_id'];
						}
						$page_netorder += $netorder;
						$page_vatamt += $vatamt;
						$page_grossorder += $netorder;
										
						
					}
					
				}
			}	
		}
		$order_str = implode(',',$order_id_arr);
		//#Select condition for getting total count
		$sql_count 			= "SELECT count(*) as cnt 
								FROM 
									$table_name  
									$where_conditions 
									AND order_id IN ($order_str)";
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
		$sql_qry = "SELECT 
				order_id,customers_customer_id,sites_site_id,sites_shops_shop_id , order_subtotal,order_deliverytotal,order_deliverytype,order_deliverylocation,
				order_date,order_custtitle,order_custfname,order_custmname,
				order_custsurname,order_custemail,order_paymenttype,order_paymentmethod,
				order_paystatus,order_status,order_refundamt,order_deposit_amt,
				order_deposit_cleared,order_currency_symbol,order_currency_convertionrate,
				order_totalprice,order_totalauthorizeamt,order_pre_order,
				order_paystatus_changed_manually,order_paystatus_changed_manually_by,
				order_paystatus_changed_manually_on,order_cancelled_by,
				order_cancelled_on,order_cancelled_from,
				order_specialtax_calculation,order_specialtax_totalamt,order_specialtax_productamt,
				order_specialtax_deliveryamt,order_specialtax_extrashippingamt,
				order_specialtax_orgtotalamt,order_specialtax_orgproductamt,
				order_specialtax_orgdeliveryamt,order_specialtax_orgextrashippingamt, 
				CASE order_status 
				WHEN 'NEW' THEN 'Unviewed'
				else
					order_status
				END as ordstat  
			FROM 
				$table_name 
				$where_conditions 
				AND order_id IN ($order_str) 
			ORDER BY 
				$sort_by $sort_order 
			LIMIT 
				$start,$records_per_page ";
			$ret_order = $db->query($sql_qry);
	 }
	 
	 
	 
	 
	 	echo table_header($table_headers,$header_positions); 
		if ($db->num_rows($ret_order))
		{ 
			$hover_title = 'Click to view the order tax details';
			$srno = 1;
			$page_ordref = $page_ordtot = 0;
			$page_netorder =	$page_vatamt  = $page_grossorder=0;
			while ($row_order = $db->fetch_array($ret_order))
			{
				$taxcalculation_required = 1;
				// Get the id of the country in delivery details
				$sql_del_country = "SELECT delivery_country FROM order_delivery_data WHERE orders_order_id = ".$row_order['order_id'];
				$ret_del_country = $db->query($sql_del_country);
				if($db->num_rows($ret_del_country))
				{
					$row_del_country = $db->fetch_array($ret_del_country);
					$del_country = stripslashes($row_del_country['delivery_country']);
					// Get the id of current coutnry
					$sql_cname = "SELECT country_id FROM general_settings_site_country WHERE country_name = '".addslashes($del_country)."' 
										AND sites_site_id = $ecom_siteid LIMIT 1";
					$ret_cname = $db->query($sql_cname);
					if($db->num_rows($ret_cname))
					{
						$row_cname = $db->fetch_array($ret_cname);
						$delivery_country_id = $row_cname['country_id'];
						// check whether this country is under a delivery location where tax is not applicable
						// get the delivery method id for current website
						$sql_del = "SELECT delivery_methods_delivery_id FROM 
										general_settings_site_delivery 
									WHERE 
										sites_site_id = $ecom_siteid 
									LIMIT 
										1";
						$ret_del = $db->query($sql_del);
						$row_del = $db->fetch_array($ret_del);
						$del_id = $row_del['delivery_methods_delivery_id'];
						// get the id of location 
						$sql_locid = "SELECT delivery_site_location_location_id 
										FROM 
											general_settings_site_country_location_map 
										WHERE 
											delivery_methods_deliverymethod_id = $del_id 
											AND general_settings_site_country_country_id = $delivery_country_id 
											AND sites_site_id = $ecom_siteid 
										LIMIT 
											1";
						$ret_locid = $db->query($sql_locid);
						if($db->num_rows($ret_locid))
						{
							$row_locid = $db->fetch_array($ret_locid);
							$sql_loc = "SELECT location_tax_applicable 
										FROM 
											delivery_site_location 
										WHERE 
											location_id = ".$row_locid['delivery_site_location_location_id']."
										LIMIT 
											1";
							$ret_loc = $db->query($sql_loc);
							if($db->num_rows($ret_loc))
							{
								$row_loc = $db->fetch_array($ret_loc);
								if ($row_loc['location_tax_applicable']==0)
									$taxcalculation_required = 0;
							}
						}
						
						$netorder = $vatamt = $grossorder = 0;
						$netorder_1 = $netorder_2 = $deltotal_1 = $deltotal_2 = 0;
						$deltotal = 0;
						if($taxcalculation_required==0)
						{
							$netorder = $grossorder = $row_order['order_totalprice'];
							$vatamt = 0;
						}
						else
						{
							$netorder_1 = $row_order['order_subtotal']/1.20; 
							$netorder_2 = $netorder_1 * 20/100;
							
							$deltotal_1 = $row_order['order_deliverytotal']/1.20;
							$deltotal_2 = $deltotal_1*20/100;
							
							$netorder = $netorder_1 + $deltotal_1;
							
							$vatamt =  $netorder_2 + $deltotal_2;
							
							$grossorder = $netorder + $vatamt;
							
						}
						$page_netorder += $netorder;
						$page_vatamt += $vatamt;
						$page_grossorder += $netorder;
										
						
					}
					
				}
				
				
				/*$page_prodtaxtot = $page_prodtaxtot + $row_order['order_specialtax_orgproductamt'];
				$page_deltaxtot = $page_deltaxtot + $row_order['order_specialtax_orgdeliveryamt'];
				$page_extrataxtot = $page_extrataxtot + $row_order['order_specialtax_orgextrashippingamt'];
				$page_ordtot = $page_ordtot + $row_order['order_specialtax_orgtotalamt'];*/
				$cls = ($class_arr[$row_order['order_status']])?$class_arr[$row_order['order_status']]:'listingtablestyleB';
				
	 ?>
				<tr style="cursor:pointer" >
				<td align="center" valign="middle" class="<?php echo $cls?>" width="7%" title="<?php echo $hover_title?>">
				<a class="edittextlink" href="home.php?request=orders&fpurpose=ord_details&edit_id=<?php echo $row_order['order_id']?>&ser_ord_id=<?php echo $_REQUEST['ord_id']?>&ser_ord_status=<?php echo $_REQUEST['ord_status']?>&ser_ord_name=<?php echo $_REQUEST['ord_name']?>&ser_ord_email=<?php echo $_REQUEST['ord_email']?>&ser_ord_fromdate=<?php echo $_REQUEST['ord_fromdate']?>&ser_ord_todate=<?php echo $_REQUEST['ord_todate']?>&ser_ord_stores=<?php echo $_REQUEST['ord_stores']?>&paid_only=<?php echo $_REQUEST['paid_only']?>&ser_ord_sort_by=<?php echo $sort_by?>&ser_ord_sort_order=<?php echo $sort_order?>&ser_pg=<?php echo $pg?>&ser_start=<?php echo $start?>&ser_records_per_page=<?php echo $_REQUEST['records_per_page']?>"> <?php echo $row_order['order_id']?></a></td>
				<td align="center" valign="middle" class="<?php echo $cls?>" width="10%" title="<?php echo $hover_title?>" onClick="edit_order('<?php echo $row_order['order_id']?>')">
				<?php 
				echo dateFormat($row_order['order_date'],'datetime_break');
				?>
				</td>
				<td align="right" valign="middle" class="<?php echo $cls?>" width="10%" title="<?php echo $hover_title?>" onClick="edit_order('<?php echo $row_order['order_id']?>')">
				<?php echo print_price_selected_currency($netorder,$row_order['order_currency_convertionrate'],$row_order['order_currency_symbol'],true)?></td>
				<td align="right" valign="middle" class="<?php echo $cls?>" width="13%" title="<?php echo $hover_title?>" onClick="edit_order('<?php echo $row_order['order_id']?>')">
				<?php echo print_price_selected_currency($vatamt,$row_order['order_currency_convertionrate'],$row_order['order_currency_symbol'],true)?>		  </td>
				<td align="right" valign="middle" class="<?php echo $cls?>" width="10%" onClick="edit_order('<?php echo $row_order['order_id']?>')">
				<?php echo print_price_selected_currency($grossorder,$row_order['order_currency_convertionrate'],$row_order['order_currency_symbol'],true)?></td>
				<td align="center" valign="middle" class="<?php echo $cls?>" width="20%" title="<?php echo $hover_title?>" onClick="edit_order('<?php echo $row_order['order_id']?>')">
				<?php echo $del_country?>
				</td>
				</tr>
        <?php
        		
			}
			$cls = 'listingtableheader';
		?>
				<tr>
					<td colspan="2" class="<?php echo $cls?>" align="right">Page Total in  Default Currency</td>
					<td align="right" class="<?php echo $cls?>"><?php echo display_price($page_netorder)?></td>
					<td align="right" class="<?php echo $cls?>"><?php echo display_price($page_vatamt)?></td>
					<td align="right" class="<?php echo $cls?>"><?php echo display_price($page_grossorder)?></td>
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
      </table>
	  </div>
	  </td>
    </tr>
	<tr>
	 <td width="3%" class="listeditd">
	 <?php
   		if ($db->num_rows($ret_order))
		{
	?>		
    		<?php /*?><a href="#" onClick="edit_order(-1)" class="editlist" title="View Order Details">Details</a><?php */?>
    <?php
		}
    ?>    </td>
      <td align="center" class="listeditd"  colspan="7">
    <?php 
		if ($db->num_rows($ret_order))
		{
			paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan);
		}
	?>	</td>
    </tr>
    </table>
</form>
