<?php
	/*#################################################################
	# Script Name 	: list_orders.php
	# Description 	: Page for Listing Product Orders
	# Coded by 		: Sny
	# Created on	: 18-Apr-2008
	# Modified by	: Sny
	# Modified On	: 08-May-2008
	#################################################################*/
	$page_type			= 'Statistics';
	$help_msg = get_help_messages('LIST_PROD_VENDOR_STATISTICS'); 	
	$sql_vendor = "SELECT vendor_name FROM product_vendors WHERE vendor_id=".$_REQUEST['vendor_id'];
	$ret_vendor = $db->query($sql_vendor);
	if ($db->num_rows($ret_vendor))
	{
		$row_vendor = $db->fetch_array($ret_vendor);
		$sel_name	= ucwords(strtolower(stripslashes($row_vendor['vendor_name'])));
	}
	function appendZero($num)
	{
		if ($num<10 and substr($num,0,1)!=0)
			$num = '0'.$num;
		return $num;
	}
	
//#Sort

$sort_by 			= (!$_REQUEST['ord_sort_by'])?'product_name':$_REQUEST['ord_sort_by'];
$sort_order 		= (!$_REQUEST['ord_sort_order'])?'DESC':$_REQUEST['ord_sort_order'];
$sort_options 		= array('product_name' => 'Product Name','sumqty'=>'Quantity Sold','subtotal'=>'Total Amount');
$sort_option_txt 	= generateselectbox('ord_sort_by',$sort_options,$sort_by);
$sort_by_txt		= generateselectbox('ord_sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);
$query_string 		= "request=prod_vendor&fpurpose=statistics&vendor_id=".$_REQUEST['vendor_id']."&vendor_higo=Go&txt_fromdate=".$_REQUEST['txt_fromdate']."&txt_todate=".$_REQUEST['txt_todate']."&ord_sort_by=".$sort_by."&ord_sort_order=".$sort_order."&stat_records_per_page=".$_REQUEST['stat_records_per_page'];
$query_string      .= "&pass_records_per_page=".$_REQUEST['pass_records_per_page']."&pass_sort_order=".$_REQUEST['pass_sort_order']."&pass_sort_by=".$_REQUEST['pass_sort_by']."&pass_pg=".$_REQUEST['pass_pg']."&pass_start=".$_REQUEST['pass_start']."&pass_search_name=".$_REQUEST['pass_search_name']."&search_name=".$_REQUEST['search_name'];
if($_REQUEST['vendor_edit']==1)
{
$query_string      .= "&vendor_edit=".$_REQUEST['vendor_edit'];
}
$records_per_page 	= (is_numeric($_REQUEST['stat_records_per_page']) and $_REQUEST['stat_records_per_page']>0)?intval($_REQUEST['stat_records_per_page']):10;//#Total records shown in a page

if($_REQUEST['vendor_higo']=='Go')
{
		$cnt = 0;
		$date_condition = '';
		if($_REQUEST['txt_fromdate'] || $_REQUEST['txt_todate'])
		{ 
		$txt_fromdate 	= $_REQUEST['txt_fromdate'];
		$txt_todate 	= $_REQUEST['txt_todate']; 
			$show_from 	= explode("-",$txt_fromdate);
			$show_to	= explode("-",$txt_todate);
		
			if (count($show_from)==3 || count($show_to==3))
			{
			
				for($i=0;$i<count($show_from);$i++)
				{
					$show_from[$i] = (trim($show_from[$i]))?trim($show_from[$i]):0;
					$show_to[$i] = (trim($show_to[$i]))?trim($show_to[$i]):0; 
				}
				if ((is_numeric($show_from[0]) and is_numeric($show_from[1]) and is_numeric($show_from[2])) || (is_numeric($show_to[0]) and is_numeric($show_to[1]) and is_numeric($show_to[2])) )
				{	
					
					if(checkdate($show_from[1],$show_from[0],$show_from[2]) || checkdate($show_to[1],$show_to[0],$show_to[2]))
					{
						$user_from 		= appendZero($show_from[2])."-".appendZero($show_from[1])."-".appendZero($show_from[0]);
						$user_to 		= appendZero($show_to[2])."-".appendZero($show_to[1])."-".appendZero($show_to[0]);
						if((trim($txt_fromdate)) && (!trim($txt_todate))) {
						
						$date_condition = " AND (date_format(`order_date`,'%Y-%m-%d') >= '$user_from') ";
						} if(!trim($txt_fromdate) && trim($txt_todate)) {
						$date_condition = " AND (date_format(`order_date`,'%Y-%m-%d') <= '$user_to') ";
						} if(trim($txt_fromdate) && trim($txt_todate) ) {
						$date_condition = " AND (date_format(`order_date`,'%Y-%m-%d') BETWEEN '$user_from' AND '$user_to') ";
						}
					}
				}
			}
	
			if($date_condition)
			{	
				//Find the products assigned for current vendors
				$sql_vendprod	= "SELECT products_product_id FROM product_vendor_map WHERE product_vendors_vendor_id=".$vendor_id;
				$ret_vendprod	= $db->query($sql_vendprod);
				$selprod_arr[]	= -1;//Initializing the array
				if ($db->num_rows($ret_vendprod))
				{
					while($row_vendprod = $db->fetch_array($ret_vendprod))
					{
						$selprod_arr[]	= $row_vendprod['products_product_id'];
					}
					$selprod_str 	= implode(",",$selprod_arr);
					//To get the total count
					$sql_ordfull = "SELECT products_product_id FROM orders pr,order_details prd , products c
										WHERE 
											pr.order_id=prd.orders_order_id
											AND products_product_id IN($selprod_str) 
											$date_condition 
											AND pr.order_status NOT IN ('CANCELLED','NOT_AUTH') 
											AND prd.products_product_id=c.product_id 
											AND c.product_hide ='N'
										GROUP BY 
											prd.products_product_id";
					$ret_ordfull	= $db->query($sql_ordfull);					
					$cnt			= $db->num_rows($ret_ordfull);	
					if($OrdCnt=='')
						$OrdCnt = 0;
						
					
					//Retrieving the required records only
					$sql_ord 	= "SELECT 
										prd.products_product_id,prd.product_name,sum(prd.order_orgqty) sumqty,
										sum(prd.order_orgqty*prd.product_soldprice) subtotal 
										FROM 
											orders pr, order_details prd  , products c
										WHERE 
											pr.order_id=prd.orders_order_id
											AND products_product_id IN($selprod_str) 
											$date_condition 
											AND pr.order_status NOT IN ('CANCELLED','NOT_AUTH')
											AND prd.products_product_id=c.product_id 
											AND c.product_hide ='N'
											$where_conditions 
										GROUP BY 
											prd.products_product_id 
										ORDER BY 
											$sort_by $sort_order 
									";	
				$numres 	= $db->query($sql_ord);
				$numcount 	= $db->num_rows($numres);//#Getting total count of records
				
	/////////////////////////////////For paging///////////////////////////////////////////
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
					
				$sql_ord	.=" LIMIT $start,$records_per_page ";
				
					$ret_ord	= $db->query($sql_ord);	
					$retNum 	= $db->num_rows($ret_ord);			
					}	
				}	
			}
	}
?>
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
function handle_showmorediv()
{
	if(document.getElementById('listmore_tr1').style.display=='')
	{
		document.getElementById('listmore_tr1').style.display = 'none';
		document.getElementById('show_morediv_big').innerHTML = 'More Options<img src="images/right_arr.gif" />';
		document.getElementById('bottom_godiv').style.display = 'none';
		document.getElementById('top_godiv').style.display='';
	}	
	else
	{
		document.getElementById('listmore_tr1').style.display ='';
		document.getElementById('show_morediv_big').innerHTML = 'More Options<img src="images/down_arr.gif" /> ';
		document.getElementById('bottom_godiv').style.display = '';
		document.getElementById('top_godiv').style.display='none';
	}	
}
function handle_showlegenddiv()
{
	if(document.getElementById('legend_tr').style.display=='')
	{
		document.getElementById('legend_tr').style.display = 'none';
		document.getElementById('show_legenddiv_big').innerHTML = 'Show Legends<img src="images/right_arr.gif" />';
	}	
	else
	{
		document.getElementById('legend_tr').style.display ='';
		document.getElementById('show_legenddiv_big').innerHTML = 'Show Legends<img src="images/down_arr.gif" /> ';
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
}
function vendor_go() {     
	frm = document.frm_vendorstats;
	if(frm.txt_fromdate.value=='' && frm.txt_todate.value=='')
	{
		alert("Please Enter Any Date");
		frm.txt_fromdate.focus();
		return false;
	}
	else if(frm.txt_fromdate.value!='' && frm.txt_todate.value!='')
	{
		val_dates = compareDates(frm.txt_fromdate,"Start Date\n Correct Format:dd-mm-yyyy ",frm.txt_todate,"End Date\n Correct Format:dd-mm-yyyy");
		if(!val_dates)
		{
			return false;
		}
	}
	document.frm_vendorstats.search_click.value=1;
	frm.vendor_higo.value='Go';
	document.frm_vendorstats.submit();
}
/* Function which takes user back to order listing page*/
function goback_order()
{
	document.frm_vendorstats.fpurpose.value='';
	document.frm_vendorstats.submit();	
}
function goback_orderedit()
{
	document.frm_vendorstats.fpurpose.value='edit';
	document.frm_vendorstats.submit();	
}
</script>

<table border="0" cellpadding="0" cellspacing="0" class="maintable" width="100%">
  <form method="post" name="frm_vendorstats" class="frmcls" action="">
<input type="hidden" name="request" value="prod_vendor" />
<input type="hidden" name="fpurpose" value="statistics" />
  <tr>
      <td colspan="3" align="left" valign="middle" class="treemenutd" nowrap="nowrap"><div class="treemenutd_div">
	  <?PHP if($_REQUEST['vendor_edit']==1) { ?> 
	  <a href="home.php?request=prod_vendor&fpurpose=edit&vendor_id=<?PHP echo $_REQUEST['vendor_id']; ?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&search_name=<?=$_REQUEST['pass_search_name']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>">
	  
	 <!-- <a href="#" onclick="goback_orderedit()">  -->Edit Vendor </a> <span>Statistics for the selected Vendor <b>"<?php echo $sel_name?>"</b></span> </td></td>	  
	  <?PHP } else { ?>
	  <a href="home.php?request=prod_vendor&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['pass_start']?>">
	  <!--<a href="#" onclick="goback_order()"> --> List Vendors </a> <span>Statistics for the selected Vendor <b>"<?php echo $sel_name?>"</b></span><?PHP } ?>
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
          			<td align="center" valign="middle" class="errormsg" colspan="4"><?php echo($alert);?></td>
          		</tr>
		 <?php
		 	}
			
		 ?> 
    <tr>
      <td height="48" class="sorttd" colspan="4">
	<div class="sorttd_div">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="15%" align="left" valign="middle" nowrap="nowrap">Search Between Date</td>
          <td width="9%" align="left" valign="middle">
              <input name="txt_fromdate" class="textfeild" type="text" size="12" value="<?php echo $_REQUEST['txt_fromdate']?>" /></td>
          <td width="4%" align="left" valign="middle"><a href="javascript:show_calendar('frm_vendorstats.txt_fromdate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a></td>
          <td width="3%" align="left" valign="middle">&nbsp;And</td>
          <td width="9%" align="left" valign="middle"><input name="txt_todate" class="textfeild" id="txt_todate" type="text" size="12" value="<?php echo $_REQUEST['txt_todate']; ?>" /></td>
          <td width="5%" align="left" valign="middle"><a href="javascript:show_calendar('frm_vendorstats.txt_todate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a></td>
          <td width="19%" align="left" valign="middle">&nbsp;Show
            <input name="stat_records_per_page" type="text" class="textfeild" id="stat_records_per_page" size="3" maxlength="3" value="<?php echo $records_per_page?>" />
            <?php echo $page_type?> Per Page</td>
          <td width="30%" align="left" valign="middle">&nbsp;&nbsp;Sort By <?php echo $sort_option_txt;?> in <?php echo $sort_by_txt?></td>
          <td width="6%" align="left" valign="middle"><input name="vendor_search" class="red" type="button" id="vendor_search" value="Go" onclick="javascript:vendor_go()"/>
            <input name="vendor_id" type="hidden" id="vendor_id" value="<?php echo $_REQUEST['vendor_id']?>" />
            <input name="vendor_higo" type="hidden" id="vendor_higo"  /></td>
        </tr>
		</table>
		</div>
		<div class="editarea_div">
		 <table width="100%" border="0" cellspacing="1" cellpadding="1">
        <tr>
          <td colspan="10" align="left" valign="middle"><?PHP  
//echo $_REQUEST['vendor_higo']; 
	 if($_REQUEST['vendor_higo']=='Go') 
	 {
 ?> 
		   <table width="100%" border="0" cellspacing="1" cellpadding="1">
 
          <tr id="listmore_tr1" >
              <td align="left">
			  <table width="100%" border="0" cellspacing="0" cellpadding="0">
			  
			  <tr><td colspan="8" align="center"><? 
			  if($retNum>0) {		
			  	paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan);
			  }
 ?></td></tr>
                  <tr>
                    <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr >
                        <td align="center" width="42"  class="listingtableheader">Slno.</td>
                        <td width="507" align="left" class="listingtableheader"><b>Product Name </b> &nbsp;</td>
                        <td width="168" align="center" class="listingtableheader"><b>Qty Sold </b>&nbsp;</td>
                        <td width="224" align="center" class="listingtableheader"><b>Total Amount</b></td>
                      </tr>
                      <tr height="1" class="divline">
                        <td colspan="4" height="1"></td>
                      </tr>
					  
			<?PHP   if($retNum==0) {  ?>						   
                 <tr>
                    <td colspan="8" align="center" bgcolor="#FFFFFF" > <font color="#FF0000"> <strong>No Details Found</strong> </font></td>
                  </tr>
	  
   <?php
   } else {
   
		$i =1;
		//for ($i=$startCnt;$i<$endCnt;$i++) {
	while($row = $db->fetch_array($ret_ord))
	{

?>
                      <tr height="28" class="<?php echo ($i%2==0)?'listingtablestyleA':'listingtablestyleB'?>" >
                        <td  align="center" width="42"><?php echo $i++;?></td>
                        <td align="left" width="507"><a href="home.php?request=products&fpurpose=edit&checkbox[0]=<?php echo $row['products_product_id']?>" title="View Product Details" class="edittextlink" ><?php echo stripslashes($row['product_name']); ?></a></td>
                        <td align="center" width="168"><?php echo stripslashes($row['sumqty']); ?></td>
                        <td align="center" width="224"><?php echo display_price($row['subtotal']);?></td>
                      </tr>
                      <tr height="1" class="divline">
                        <td colspan="4" height="1"></td>
                      </tr>
                      <?php
		$pg_total 		+= $row['subtotal'];
		$pg_qtytotal	+= $row['sumqty'];
	}
?>
                      <tr height="28" class="<?php echo ($i%2==0)?'tdbgspecial':'tdbgnormal'?>" >
                        <td  align="center">&nbsp;</td>
                        <td align="right"><strong>Page Total</strong></td>
                        <td align="center"><strong><?php echo $pg_qtytotal;?></strong></td>
                        <td align="center"><strong><?php echo display_price($pg_total)?></strong></td>
                      </tr>
	<? } ?>
					 <tr >
                        <td colspan="9" >&nbsp;</td>
                   </tr>
	
                      <tr height="1" class="divline">
                        <td colspan="9" height="1" align="center">&nbsp;<?PHp 
					  if($retNum>0) {		
			  	paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan);
			  }
						?>						</td>
                      </tr>
		  
                      <tr height="1" class="divline">
                        <td colspan="9" height="1"></td>
                   </tr>
                    </table>	</td>
                    </tr>
                </table></td>
              </tr>
          </table>
		  	<? } ?>	
			<input type="hidden" name="search_name" value="<?php echo ($_REQUEST['search_name'])?$_REQUEST['search_name']:$_REQUEST['search_name']?>" />
<input type="hidden" name="ord_name" value="<?php echo ($_REQUEST['ord_name'])?$_REQUEST['ord_name']:$_REQUEST['ser_ord_name']?>" />
<input type="hidden" name="pass_search_name" value="<?php echo ($_REQUEST['pass_search_name'])?>" />
<input type="hidden" name="pass_records_per_page" value="<?php echo ($_REQUEST['pass_records_per_page'])?>" />
		
	<input type="hidden" name="pass_sort_by" value="<?php echo ($_REQUEST['pass_sort_by'])?>" />
		<input type="hidden" name="pass_sort_order" value="<?php echo ($_REQUEST['pass_sort_order'])?>" />
		<input type="hidden" name="pass_pg" value="<?php echo ($_REQUEST['pass_pg'])?>" />
		<input type="hidden" name="pass_start" value="<?php echo ($_REQUEST['pass_start'])?>" />
		
	 <input type="hidden" name="search_click" value="" />
<input type="hidden" name="vendor_edit" value="<?php echo ($_REQUEST['vendor_edit'])?$_REQUEST['vendor_edit']:$_REQUEST['vendor_edit']?>" />	  </td>
          </tr>
      </table>     
	  	</div>
	   </td>
    </tr></form>
    </table>
