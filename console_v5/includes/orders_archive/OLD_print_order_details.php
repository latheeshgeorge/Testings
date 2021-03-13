<?php
/*#################################################################
# Script Name 	: order_details.php
# Description 	: Page for showing the details of selected orders
# Coded by 		: Sny
# Created on	: 21-Apr-2008
# Modified by	: Sny
# Modified On	: 09-May-2008
#################################################################*/
//#Define constants for this page
include_once("../../functions/functions.php");
include('../../session.php');
require_once("../../config.php");

$page_type 	= 'Order Details';
$help_msg 	= get_help_messages('EDIT_PRODUCT_STORE_SHORT');

global $ecom_hostname;
 
?>	
<html><head>
<link href="../../css/style_print.css" rel="stylesheet" media="print">
<link href="../../css/style_screen.css" rel="stylesheet" media="screen">

</head><body>
<form name='frmOrderDetails' action='' method="post">
<?PHP
	$ord = split("~",$_REQUEST['orderid']);	
	foreach($ord AS $val) {
	    $sql_ord = "SELECT * 
					FROM 
						orders 
					WHERE 
						order_id=".$val." 
						AND sites_site_id = $ecom_siteid 
					LIMIT 
						1";
		$ret_ord = $db->query($sql_ord);
		if($db->num_rows($ret_ord)>0)
		{
			while($row_ord = $db->fetch_array($ret_ord)) 
			{
		
		


?>

 		<table width="100%" border="0" cellspacing="1" cellpadding="1">
        
		<?php
			if($alert) // section to show the alert message if any
			{
		?>
			<tr id="mainerror_div">
			  <td colspan="4" align="center" valign="middle" class="errormsg" >&nbsp;</td>
			</tr>
		 <?php
		 	}
			$srno = 1;
			$cls = ($srno%2==0)?'listingtablestyleB':'listingtablestyleB';
			$srno++;
		 ?> 
         <tr>
           <td colspan="3" align="left" valign="middle" class="subcaption <?php echo $cls?>" >&nbsp;<h1><?PHP echo $ecom_hostname."   -  ".$row_ord['order_id']; ?></h1></td>
           <td align="left" valign="middle" class="<?php echo $cls?>">&nbsp;</td>
         </tr>
         
         <tr>
           <td width="14%" align="left" valign="middle" class="subcaption <?php echo $cls?>" >Order ID & Date </td>
           <td width="35%" align="left" valign="middle" class="<?php echo $cls?>" ><?php echo $row_ord['order_id']?>&nbsp;&nbsp;(<?php echo dateFormat($row_ord['order_date'],'datetime')?>)</td>
           <td width="20%" align="left" valign="middle" class="subcaption <?php echo $cls?>" >Order Status </td>
           <td width="31%" align="left" valign="middle" class="<?php echo $cls?>">
		   <div id="orderstatus_maindiv">
		   <?php echo getorderstatus_Name($row_ord['order_status']);
		   
		    // If order is cancelled, the date of cancellation and also the person who cancelled it
		   	if($row_ord['order_cancelled_by']!=0)
			{
				if($row_ord['order_cancelled_from']=='A') // case cancelled from admin area
				{
					$cancelled_by = getConsoleUserName($row_ord['order_cancelled_by']);
				}
				else // case cancelled from client area
				{
					$sql_customer = "SELECT customer_title,customer_fname,customer_mname,customer_surname 
										FROM 
											customers 
										WHERE 
											customer_id = ".$row_ord['order_cancelled_by']." 
											AND sites_site_id = $ecom_siteid 
										LIMIT 
											1";
					$ret_customer = $db->query($sql_customer);
					if($db->num_rows($ret_customer))
					{
						$row_usr = $db->fetch_array($ret_customer);
						$cancelled_by = stripslashes($row_usr['customer_title']).stripslashes($row_usr['customer_fname'])." ".stripslashes($row_usr['customer_mname'])." ".stripslashes($row_usr['customer_surname']).' (customer)';
					}
				}	
				
				echo " (By ".$cancelled_by." on ".dateFormat($row_ord['order_cancelled_on'],'datetime').")";
			}
		   ?>		   </div>		   </td>
         </tr>
		 <?php 
		 	$cls = ($srno%2==0)?'listingtablestyleB':'listingtablestyleB';
			$srno++;
		 ?>
         <tr>
           <td align="left" valign="middle" class="subcaption <?php echo $cls?>" >
           <?php
           	if(trim($row_ord['order_paymenttype'])!='')
           	{
           	?>	
           		Payment Type 
           <?php
           	}
           ?>           </td>
           <td align="left" valign="middle" class="<?php echo $cls?>" ><?php echo getpaymenttype_Name($row_ord['order_paymenttype'])?></td>
           <td align="left" valign="middle" class="subcaption <?php echo $cls?>" >
           Payment Status			</td>
           <td align="left" valign="middle" class="<?php echo $cls?>">
            <div id="paymentstatus_maindiv">
		   <?php echo  getpaymentstatus_Name($row_ord['order_paystatus'])?>		   </div>		   </td>
         </tr>
		 <?php
		 	$pay_str = $pay_usr = '';
		    if($row_ord['order_paystatus_changed_manually']==1) // case if payment status changed manually
			{
				$pay_usr = getConsoleUserName($row_ord['order_paystatus_changed_manually_by']).' ( on '.dateFormat($row_ord['order_paystatus_changed_manually_on'],'datetime').')'; ;
				$pay_str =  "Payment Status Changed By ";
			}		
			$cls = ($srno%2==0)?'listingtablestyleB':'listingtablestyleB';
			$srno++;
			?>
		 <tr>
           <td align="left" valign="middle" class="subcaption <?php echo $cls?>" >
           <?php
		   	if($row_ord['order_paymentmethod']!='')
			{
			?>	
		    	Payment Method 
			<?php
			}
			else
				echo '&nbsp;';
			?>            </td>
           <td align="left" valign="middle" class="<?php echo $cls?>" >
		   <?php
		   	if($row_ord['order_paymentmethod']!='')
			{
				echo getpaymentmethod_Name($row_ord['order_paymentmethod']);
			}
			else
				echo '&nbsp;';
			?>		   </td>
           <td align="left" valign="middle" class="subcaption <?php echo $cls?>" >
		  <?php
		   if($pay_str!='')
		   {
		   		echo $pay_str;
		   }
		   ?>		   </td>
           <td align="left" valign="middle" class="<?php echo $cls?>">
		   <?php
		   	if($pay_usr!='')
		   	{
		  	 echo $pay_usr;
			}	 
		   ?>		   </td>
         </tr>
		
		
		 <tr>
          <td colspan="4" align="left" valign="bottom">
            <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
                <td align="left" class="seperationtd_special" colspan="2">Products in Order</td>
                
            </tr>
          </table></td>
        </tr>
			<?php
				$edit_id = $row_ord['order_id'];
				// Get the products in this order
				$sql_prods = "SELECT orderdet_id,order_delivery_data_delivery_id,products_product_id,product_name,order_qty,
									order_orgqty,product_soldprice,order_retailprice,product_costprice,order_discount,
									order_discount_type,order_rowtotal,order_preorder,order_preorder_available_date,
									order_refunded,order_refundedon,order_deposit_percentage,order_deposit_value,
									order_refundedby,order_deposit_percentage,order_deposit_value,order_dispatched,
									order_dispatched_on,order_dispatchedby,order_dispatched_id,order_stock_combination_id 
							 	FROM 
									order_details 
								WHERE 
									orders_order_id = $orderid ";//									AND order_qty>0";
				$ret_prods = $db->query($sql_prods);
				if ($db->num_rows($ret_prods))
				{
			?>
					<tr>
					<td align="right" colspan="4" class="tdcolorgray_normal">
			<?php	
					$table_headers 		= array('Product','Available?','Retail Price','Disc','Sale Price','Rem Qty','Ord Qty','Net');
					$header_positions	= array('left','left','center','right','right','right','center','center','right');
					$colspan 			= count($table_headers);
				?>
					<table width="100%" border="0" cellspacing="1" cellpadding="1">
				<?php
					$srno=1;
					echo table_header_print($table_headers,$header_positions); 
					while ($row_prods = $db->fetch_array($ret_prods))
					{
						$cls = ($srno%2==0)?'listingtablestyleA':'listingtablestyleB';
						$srno++;
						// Check whether the current product still exists in products table
						$sql_check = "SELECT product_id 
										FROM 
											products 
										WHERE 
											product_id = ".$row_prods['products_product_id']." 
											AND sites_site_id = $ecom_siteid 
										LIMIT 
											1";
						$ret_check = $db->query($sql_check);
						if ($db->num_rows($ret_check))
						{
						//	$link_req_prefix = '<a href="home.php?request=products&fpurpose=edit&checkbox[0]='.$row_prods['products_product_id'].'" title="View Product Details" class="edittextlink">';
						//	$link_req_suffix = '</a>';
						}
						else
							$link_req = $link_req_suffix= '';
				?>
						<tr>
						<td width="30%" align="left" class="<?php echo $cls?>"><?php echo stripslashes($row_prods['product_name']); ?>&nbsp;
						<?php
							// Check whether the arrow is to be displayed here
							// So check whether variables exists for products or whether it is despatched
							$sql_varcheck = "SELECT orders_order_id 
												FROM 
													order_details_variables 
												WHERE 
													orders_order_id = ".$edit_id." 
												LIMIT 
													1";
							$ret_varcheck = $db->query($sql_varcheck);
							if($row_prods['order_dispatched']=='Y' or $db->num_rows($ret_varcheck))
							{
						?>
								<div id='vardiv_<?php echo $row_prods['orderdet_id']?>' class="show_vardiv_big" onClick="handle_showvariables('<?php echo $row_prods['orderdet_id']?>')" title="Click here"></div>
						<?php
							}
						?>						</td>
						<td width="10%" align="center" class="<?php echo $cls?>">
						<?php 
							if ($row_prods['order_preorder']=='N')
							{
								echo 'In Stock';
							}
							else
								echo 'Available on <br>'.dateFormat($row_prods['order_preorder_available_date'],'datetime');
						?></td>
						<td width="10%" class="<?php echo $cls?>" align="right"><?php echo print_price_selected_currency($row_prods['order_retailprice'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
						<td width="8%" class="<?php echo $cls?>" align="right"><?php echo print_price_selected_currency($row_prods['order_discount'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
						<td width="10%" class="<?php echo $cls?>" align="right"><?php echo print_price_selected_currency($row_prods['product_soldprice'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
						<td width="8%" class="<?php echo $cls?>" align="center">
						
						<?php
					
								echo $row_prods['order_qty'];
						?>
						<input type="hidden" name="orgqty_<?php echo $row_prods['orderdet_id']?>" id="orgqty_<?php echo $row_prods['orderdet_id']?>" value="<?php echo $row_prods['order_qty']?>" />						</td>
						<td width="7%" class="<?php echo $cls?>" align="center">
						<?php echo $row_prods['order_orgqty']?>						</td>
						<td width="20%" class="<?php echo $cls?>" align="center"><?php echo print_price_selected_currency($row_prods['order_rowtotal'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
						</tr>	
						<tr id="vartr_<?php echo $row_prods['orderdet_id']?>" >
							<td align="left" class="<?php echo $cls?>_var" colspan="<?php echo $colspan?>">	
							<div  style="text-align:center"><?PHP 
							// Get the currency symbol and conversion rate in current order
	$sql_ord = "SELECT order_currency_symbol,order_currency_convertionrate
						FROM 
							orders 
						WHERE 
							order_id = ".$edit_id." 
						LIMIT 
							1";
	$ret_ord = $db->query($sql_ord);
	if ($db->num_rows($ret_ord))
	{
		$row_cur_ord 	= $db->fetch_array($ret_ord);
	}
	// Check whether any variables exists for current product in order_details_variables
	$sql_var = "SELECT var_name,var_value
						FROM 
							order_details_variables 
						WHERE 
							orders_order_id = $edit_id 
							AND order_details_orderdet_id =".$row_prods['orderdet_id'];
	$ret_var = $db->query($sql_var);
	if ($db->num_rows($ret_var))
	{
		$var_td 	= 34;
		$desp_td	= 66;
	}
	else
	{
		$var_td 	= 0;
		$desp_td	= 100;
	}
	?>
				<table width="100%" border="0" cellpadding="1" cellspacing="1">
				<tr>
				<?php 
				if($var_td>0)
				{
				?>	
						<td align="left" valign="top" width="<?php echo $var_td?>%" class="listingtablestyleA">
				<?php
				}
				?>	
							
				<?php	
				$cnts = 0;
				if ($db->num_rows($ret_var))
				{
					while ($row_var = $db->fetch_array($ret_var))
					{
						if($cnts>0)
						echo "<br>";
						$cnts++;
						echo stripslashes($row_var['var_name']).': '.stripslashes($row_var['var_value']);
					}
				}
				// Check whether any variables messages exists for current product in order_details_messages
				$sql_msg = "SELECT message_caption,message_value
										FROM 
											order_details_messages 
										WHERE 
											orders_order_id = $edit_id 
											AND order_details_orderdet_id =".$row_prods['orderdet_id'];
				$ret_msg = $db->query($sql_msg);
				if ($db->num_rows($ret_msg))
				{
					while ($row_msg = $db->fetch_array($ret_msg))
					{
						if($cnts>0)
						echo "<br>";
						$cnts++;
						echo '<strong>'.stripslashes($row_msg['message_caption']).':</strong> '.stripslashes($row_msg['message_value']);
					}
				}
				if($var_td>0)
				{
				?>						</td>
				<?php
				}
				?>		
								<td width="<?php echo $desp_td?>%" valign="top">
								<?php
								
									// Get the despatched details for current order details
									$sql_desp = "SELECT despatched_id,despatched_qty,despatched_on,despatched_by,despatched_reference
														FROM 
															order_details_despatched 
														WHERE 
															orderdet_id = ".$row_prods['orderdet_id']. "
														ORDER BY 
															despatched_on DESC";
									$ret_desp = $db->query($sql_desp);
									if ($db->num_rows($ret_desp))
									{
									?>
											<table width="100%" cellpadding="1" cellspacing="1" border="0">
											<tr>
											<td align="left" colspan="6" class="shoppingcartheader">Despatched Details</td>
											</tr>
											<?php
											$table_varheaders 		= array('Date','Qty','Price','Reference','By');
											$headervar_positions	= array('center','center','right','left','left');
											$var_colspans			= count($table_varheaders);
											$varsrno				= 1;
											$tot_qty				= $tot_price = 0;
											$price_per_item			= get_priceofOneitem($row_prods['orderdet_id']);
											echo table_header_print($table_varheaders,$headervar_positions);
											while ($row_desp = $db->fetch_array($ret_desp))
											{
												$tot_qty += $row_desp['despatched_qty'];
												$cls = ($varsrno%2==0)?'listingtablestyleA':'listingtablestyleB';
											?>
													<tr>
														<td align="center" width="25%" class="<?php echo $cls?>">
															<?php
															echo dateFormat($row_desp['despatched_on'],'datetime')
															?>														</td>
														<td align="center" width="5%" class="<?php echo $cls?>">
															<?php
															echo $row_desp['despatched_qty'];
															?>														</td>
														<td align="right" width="17%" class="<?php echo $cls?>">
															<?php
															$c_price 	=  ($price_per_item * $row_desp['despatched_qty']);
															$tot_price += $c_price;
															echo print_price_selected_currency($c_price,$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true);
															?>														</td>
														<td align="left" class="<?php echo $cls?>">
														<?php
														echo stripslashes($row_desp['despatched_reference']);
														?>														</td>
														<td align="left" class="<?php echo $cls?>">
														<?php
														echo getConsoleUserName($row_desp['despatched_by']);
														?>														</td>
													</tr>
											<?php
											$varsrno++;
											}
											?>
												<tr>
													<td align="center" class="shoppingcartpriceB">Total</td>
													<td align="center" class="shoppingcartpriceB">
														<?php
														echo $tot_qty;
														?>													</td>
													<td align="right" class="shoppingcartpriceB">
													<?php echo print_price_selected_currency($tot_price,$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true);
													?>													</td>
													<td align="left" class="shoppingcartpriceB">&nbsp;													</td>
													<td align="left" class="shoppingcartpriceB">&nbsp;													</td>
												</tr>
											</table>
								<?php
									}
								
								?>
															  </td>
				  </tr>
</table>
							</div>							</td>
						</tr>
						
				<?php		
						
					}
				?>
						</table>					</td>
					</tr>
				<?php
				}
				// Get the products removed from current order
				$sql_prods = "SELECT a.orderdet_id,order_delivery_data_delivery_id,products_product_id,product_name,b.order_qty,
									product_soldprice,order_retailprice,product_costprice,order_discount,
									order_discount_type,order_rowtotal,order_preorder,order_preorder_available_date,
									order_refunded,order_refundedon,order_deposit_percentage,order_deposit_value,
									order_refundedby,order_deposit_percentage,order_deposit_value,order_dispatched,
									order_dispatched_on,order_dispatchedby,order_dispatched_id,order_stock_combination_id,
									b.order_removedon 
								FROM 
									order_details a,order_details_removed b 
								WHERE 
									orders_order_id = $edit_id 
									AND a.orderdet_id=b.orderdet_id";
				$ret_prods = $db->query($sql_prods);
				if ($db->num_rows($ret_prods))
				{
				?>
					<tr>
					  <td height="58" colspan="4" align="left" valign="bottom">
						<table width="100%" border="0" cellspacing="1" cellpadding="1">
						<tr>
						  <td colspan="2" align="left">&nbsp;</td>
						  </tr>
						<tr>
							<td width="79%" align="left" class="seperationtd_special">Products Placed on Hold</td>
						    <td width="21%" align="left" class="seperationtd_special">											  </td>
						</tr>
					  </table></td>
					</tr>
					<tr>
					<td align="right" colspan="4" class="tdcolorgray_normal">
				    <?php
						$table_headers 		= array('Product','Available?','Qty','Removed on');
						$header_positions	= array('center','left','center','center','center');
						$colspan 			= count($table_headers);
					?>
					<table width="100%" border="0" cellspacing="1" cellpadding="1">
					<?php
						$srno=1;
						$cnts = 0;
						echo table_header_print($table_headers,$header_positions); 
						while ($row_prods = $db->fetch_array($ret_prods))
						{
							$cls = ($srno%2==0)?'listingtablestyleA':'listingtablestyleB';
							$srno++;
							// Check whether the current product still exists in products table
							$sql_check = "SELECT product_id 
											FROM 
												products 
											WHERE 
												product_id = ".$row_prods['products_product_id']." 
												AND sites_site_id = $ecom_siteid 
											LIMIT 
												1";
							$ret_check = $db->query($sql_check);
								$link_req = $link_req_suffix= '';
					?>
							<tr>
							<td width="30%" align="left" class="<?php echo $cls?>"><?php echo $link_req_prefix.stripslashes($row_prods['product_name']).$link_req_suffix?>
							<?php /*?><div id='varremdiv_<?php echo $row_prods['orderdet_id']?>' class="show_vardiv_big" onclick="handle_showvariables(document.getElementById('varremtr_<?php echo $row_prods['orderdet_id']?>'),document.getElementById('varremdiv_<?php echo $row_prods['orderdet_id']?>'))" title="Click here"><img src="images/right_arr.gif" /></div><?php */?>
							<?php
						    // Check whether the arrow is to be displayed here
							// So check whether variables exists for products or whether it is despatched
							$sql_varcheck = "SELECT orders_order_id 
												FROM 
													order_details_variables 
												WHERE 
													orders_order_id = ".$edit_id." 
												LIMIT 
													1";
							$ret_varcheck = $db->query($sql_varcheck);
							if($row_prods['orderdet_dispatched']=='Y' or $db->num_rows($ret_varcheck))
							{
							
							?>
								<div id='varremdiv_<?php echo $row_prods['orderdet_id']?>' class="show_vardiv_big" onClick="handle_showvariables_rem('<?php echo $row_prods['orderdet_id']?>')" title="Click here"></div>
							<?php
							}
							?>							</td>
							<td width="15%" align="center" class="<?php echo $cls?>">
							<?php 
								if ($row_prods['order_preorder']=='N')
								{
									echo 'In Stock';
								}
								else
									echo 'Available on <br>'.dateFormat($row_prods['order_preorder_available_date'],'datetime');
							?>							</td>
							<td width="20%" class="<?php echo $cls?>" align="center">
							<?php		
									 echo $row_prods['order_qty'];
							?>
							<input type="hidden" name="orgqtyrem_<?php echo $row_prods['orderdet_id']?>" id="orgqtyrem_<?php echo $row_prods['orderdet_id']?>" value="<?php echo $row_prods['order_qty']?>" /></td>
							<td width="30%" align="center" class="<?php echo $cls?>"><?php echo dateFormat($row_prods['order_removedon'],'datetime')?></td>
						</tr>		
						<tr id="varremtr_<?php echo $row_prods['orderdet_id']?>" >
						<td align="left" class="<?php echo $cls?>_var" colspan="<?php echo $colspan?>">	
						<div id="orddetrem_<?php echo $row_prods['orderdet_id']?>_div" style="text-align:center">
						<?PHP 
							// Get the currency symbol and conversion rate in current order
	$sql_ord = "SELECT order_currency_symbol,order_currency_convertionrate
						FROM 
							orders 
						WHERE 
							order_id = ".$edit_id." 
						LIMIT 
							1";
	$ret_ord = $db->query($sql_ord);
	if ($db->num_rows($ret_ord))
	{
		$row_cur_ord 	= $db->fetch_array($ret_ord);
	}
	// Check whether any variables exists for current product in order_details_variables
	$sql_var = "SELECT var_name,var_value
						FROM 
							order_details_variables 
						WHERE 
							orders_order_id = $edit_id 
							AND order_details_orderdet_id =".$row_prods['orderdet_id'];
	$ret_var = $db->query($sql_var);
	if ($db->num_rows($ret_var))
	{
		$var_td 	= 34;
		$desp_td	= 66;
	}
	else
	{
		$var_td 	= 0;
		$desp_td	= 100;
	}
	?>
				<table width="100%" border="0" cellpadding="1" cellspacing="1">
				<tr>
				<?php 
				if($var_td>0)
				{
				?>	
						<td align="left" valign="top" width="<?php echo $var_td?>%" class="listingtablestyleA">
				<?php
				}
				?>	
							
				<?php	
				$cnts = 0;
				if ($db->num_rows($ret_var))
				{
					while ($row_var = $db->fetch_array($ret_var))
					{
						if($cnts>0)
						echo "<br>";
						$cnts++;
						echo stripslashes($row_var['var_name']).': '.stripslashes($row_var['var_value']);
					}
				}
				// Check whether any variables messages exists for current product in order_details_messages
				$sql_msg = "SELECT message_caption,message_value
										FROM 
											order_details_messages 
										WHERE 
											orders_order_id = $edit_id 
											AND order_details_orderdet_id =".$row_prods['orderdet_id'];
				$ret_msg = $db->query($sql_msg);
				if ($db->num_rows($ret_msg))
				{
					while ($row_msg = $db->fetch_array($ret_msg))
					{
						if($cnts>0)
						echo "<br>";
						$cnts++;
						echo '<strong>'.stripslashes($row_msg['message_caption']).':</strong> '.stripslashes($row_msg['message_value']);
					}
				}
				if($var_td>0)
				{
				?>						</td>
				<?php
				}
				?>		
								<td width="<?php echo $desp_td?>%" valign="top">
								<?php
									if($show_despatch==true)
						            	{
							
									// Get the despatched details for current order details
									$sql_desp = "SELECT despatched_id,despatched_qty,despatched_on,despatched_by,despatched_reference
														FROM 
															order_details_despatched 
														WHERE 
															orderdet_id = ".$row_prods['orderdet_id']. "
														ORDER BY 
															despatched_on DESC";
									$ret_desp = $db->query($sql_desp);
									if ($db->num_rows($ret_desp))
									{
									?>
											<table width="100%" cellpadding="1" cellspacing="1" border="0">
											<tr>
											<td align="left" colspan="6" class="shoppingcartheader">Despatched Details</td>
											</tr>
											<?php
											$table_varheaders 		= array('Date','Qty','Price','Reference','By');
											$headervar_positions	= array('center','center','right','left','left');
											$var_colspans			= count($table_varheaders);
											$varsrno				= 1;
											$tot_qty				= $tot_price = 0;
											$price_per_item			= get_priceofOneitem($row_prods['orderdet_id']);
											echo table_header_print($table_varheaders,$headervar_positions);
											while ($row_desp = $db->fetch_array($ret_desp))
											{
												$tot_qty += $row_desp['despatched_qty'];
												$cls = ($varsrno%2==0)?'listingtablestyleA':'listingtablestyleB';
											?>
													<tr>
														<td align="center" width="25%" class="<?php echo $cls?>">
															<?php
															echo dateFormat($row_desp['despatched_on'],'datetime')
															?>														</td>
														<td align="center" width="5%" class="<?php echo $cls?>">
															<?php
															echo $row_desp['despatched_qty'];
															?>														</td>
														<td align="right" width="17%" class="<?php echo $cls?>">
															<?php
															$c_price 	=  ($price_per_item * $row_desp['despatched_qty']);
															$tot_price += $c_price;
															echo print_price_selected_currency($c_price,$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true);
															?>														</td>
														<td align="left" class="<?php echo $cls?>">
														<?php
														echo stripslashes($row_desp['despatched_reference']);
														?>														</td>
														<td align="left" class="<?php echo $cls?>">
														<?php
														echo getConsoleUserName($row_desp['despatched_by']);
														?>														</td>
													</tr>
											<?php
											$varsrno++;
											}
											?>
												<tr>
													<td align="center" class="shoppingcartpriceB">Total</td>
													<td align="center" class="shoppingcartpriceB">
														<?php
														echo $tot_qty;
														?>													</td>
													<td align="right" class="shoppingcartpriceB">
													<?php echo print_price_selected_currency($tot_price,$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true);
													?>													</td>
													<td align="left" class="shoppingcartpriceB">&nbsp;													</td>
													<td align="left" class="shoppingcartpriceB">&nbsp;													</td>
												</tr>
											</table>
								<?php
									}
								}
								?>
															  </td>
				  </tr>
</table>
						</div>
						</td>
						</tr>	
					<?php
						}
					?>	
						</table>					</td>
					</tr>
				<?php
				}
				?>	
						<tr>
					<td align="right" colspan="4" class="tdcolorgray_normal">
						<table width="100%" border="0" cellspacing="1" cellpadding="1">
						<tr>
							<td colspan="6" align="right" class="shoppingcartpriceB">Sub Total</td>
							<td width="24%" colspan="2" align="right" class="shoppingcartpriceB"><?php echo print_price_selected_currency($row_ord['order_subtotal'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
						</tr>	
						<tr>
							<td colspan="6" align="right" class="shoppingcartpriceB">+ Total Delivery Charge</td>
							<td colspan="2" align="right" class="shoppingcartpriceB"><?php echo print_price_selected_currency($row_ord['order_deliverytotal'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
						</tr>	
						<tr>
							<td colspan="6" align="right" class="shoppingcartpriceB">+ Total Tax</td>
							<td colspan="2" align="right" class="shoppingcartpriceB"><?php echo print_price_selected_currency($row_ord['order_tax_total'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
						</tr>
						<?PHP if($row_ord['order_giftwraptotal']>0) { ?>	
						<tr>
							<td colspan="6" align="right" class="shoppingcartpriceB">+ Total Gift Wrap Charge</td>
							<td colspan="2" align="right" class="shoppingcartpriceB"><?php echo print_price_selected_currency($row_ord['order_giftwraptotal'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
						</tr><? } 
						if($row_ord['gift_vouchers_voucher_id']>0) {
						$sql = "SELECT voucher_value_used FROM order_voucher WHERE orders_order_id='".$row_ord['order_id']."'";
						$res = $db->query($sql);
						$row = $db->fetch_array($res);
						$usedprice = $row['voucher_value_used'];
						?>
						 <tr> 
						  <td colspan="6" align="right" class="shoppingcartpriceB">&nbsp; - Total Gift Voucher Used</td>
						  <td colspan="2" align="right" class="shoppingcartpriceB">&nbsp;<?PHP echo $usedprice ?></td>
						  </tr>
						<?PHP } if($row_ord['promotional_code_code_id']>0) {
						$sql = "SELECT code_lessval FROM order_promotional_code WHERE orders_order_id='".$row_ord['order_id']."'";
						$res = $db->query($sql);
						$row = $db->fetch_array($res);
						$usedprice = $row['code_lessval'];
						if($usedprice > 0) {
						 ?>	
					
						<tr>
							<td colspan="6" align="right" class="shoppingcartpriceB">- Total Promotional Code Used</td>
							<td colspan="2" align="right" class="shoppingcartpriceB"><?php echo print_price_selected_currency($usedprice,$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
						</tr>
						<?php
						}
							}
							if($row_ord['order_customer_discount_value']>0) // Check whether discount exists
							{
								if($row_ord['order_customer_or_corporate_disc']=='CUST')
								{
									if($row_ord['order_customer_discount_type']=='Disc_Group')
									$caption = 'Customer Group Discount ('.$row_ord['order_customer_discount_percent'].'%)';
								else
									$caption = 'Customer Discount ('.$row_ord['order_customer_discount_percent'].'%)';
								}	
								elseif($row_ord['order_customer_or_corporate_disc']=='CORP')
								{
									$caption = 'Corporate Discount ('.$row_ord['order_customer_discount_percent'].'%)';
								}						
						?>
						<tr>
							<td colspan="6" align="right" class="shoppingcartpriceB">- <?php echo $caption?></td>
							<td colspan="2" align="right" class="shoppingcartpriceB"><?php echo print_price_selected_currency($row_ord['order_customer_discount_value'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
						</tr>
						<?php
							}
						?>	
						<?php
							if($row_ord['order_bonuspoint_discount']>0) // Check whether discount due to bonus points exists
							{
						?>
						<tr>
							<td colspan="6" align="right" class="shoppingcartpriceB">- Bonus Points Discount (<?php echo $row_ord['order_bonuspoints_used']?> used)</td>
							<td colspan="2" align="right" class="shoppingcartpriceB"><?php echo print_price_selected_currency($row_ord['order_bonuspoint_discount'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
						</tr>
						<?php
							}
						?>	
						<tr>
						  <td colspan="6" align="right" class="shoppingcartpriceB">&nbsp;</td>
						  <td colspan="2" align="right" class="shoppingcartpriceB">------------------------------</td>
						  </tr>
						<tr>
							<td colspan="6" align="right" class="shoppingcartpriceB">Grand Total</td>
							<td colspan="2" align="right" class="shoppingcartpriceB"><?php echo print_price_selected_currency($row_ord['order_totalprice'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
						</tr>
						<tr>
							  <td colspan="6" align="right" class="shoppingcartpriceB">&nbsp;</td>
							  <td colspan="2" align="right" class="shoppingcartpriceB">====================</td>
						  </tr>
						<?php
							if($row_ord['order_refundamt']>0) // Check whether deposit exists and not cleared yet
							{
						?>
							<tr>
								<td colspan="6" align="right" class="shoppingcartpriceB">Total Refunded</td>
								<td colspan="2" align="right" class="shoppingcartpriceB"><?php echo print_price_selected_currency($row_ord['order_refundamt'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
							</tr>
							<tr>
								<td colspan="6" align="right" class="shoppingcartpriceB">Total Remaining after Refund</td>
								<td colspan="2" align="right" class="shoppingcartpriceB"><?php echo print_price_selected_currency(($row_ord['order_totalprice']-$row_ord['order_refundamt']),$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
							</tr>
							<tr>
							  <td colspan="6" align="right" class="shoppingcartpriceB">&nbsp;</td>
							  <td colspan="2" align="right" class="shoppingcartpriceB">====================</td>
						  </tr>
						<?php
							}
						?>	
						<?php
							if($row_ord['order_deposit_amt']>0) // Check whether deposit exists and not cleared yet
							{
						?>
								<tr>
									<td colspan="6" align="right" class="shoppingcartpriceB">Product Deposit Amount</td>
									<td colspan="2" align="right" class="shoppingcartpriceB"><?php echo print_price_selected_currency($row_ord['order_deposit_amt'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
								</tr>
								<tr>
								  <td colspan="6" align="right" class="shoppingcartpriceB">&nbsp;</td>
								  <td colspan="2" align="right" class="shoppingcartpriceB">------------------------------</td>
								  </tr>
								<tr>
								<tr>
									<td colspan="6" align="right" class="shoppingcartpriceB">
									<div id="productdeposit_div">
									<?php
										if($row_ord['order_deposit_cleared']==1)
										{	
											$cleared_on = dateFormat($row_ord['order_deposit_cleared_on'],'datetime');
											$sql_usr	= "SELECT sites_site_id,user_title,user_fname,user_lname 
															FROM 
																sites_users_7584 
															WHERE 
																user_id = ".$row_ord['order_deposit_cleared_by']." 
															LIMIT 
																1";
											$ret_usr 	= $db->query($sql_usr);
											if ($db->num_rows($ret_usr))
											{
												$row_user = $db->fetch_array($ret_usr);
												if ($row_user['sites_site_id']==0) // case of super admin
													$cleared_by = stripslashes($row_user['user_fname'])." ".stripslashes($row_user['user_lname']);
												else
													$cleared_by = stripslashes($row_user['user_title']).".".stripslashes($row_user['user_fname'])." ".stripslashes($row_user['user_lname']);
											}	
											$cleared_msg  = 'Released Amount Remaining by '.$cleared_by.' ('.$cleared_on.')';
											echo $cleared_msg;
										}
										else
										{
									?>
											Amount Remaining to be Released
									<?php
										}
									?>
									</div></td>
									<td colspan="2" align="right" class="shoppingcartpriceB"><?php echo print_price_selected_currency(($row_ord['order_totalprice']-$row_ord['order_deposit_amt']),$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
								</tr>
								<tr>
								<td colspan="6" align="right" class="shoppingcartpriceB">&nbsp;</td>
								<td colspan="2" align="right" class="shoppingcartpriceB">====================</td>
								</tr>
						<?php
							}
						?>	
						</table>				</td>
					</tr>
				<?php	
				// Check whether refund details exists. If exists 
				 $sql_refcheck = "SELECT orders_order_id 
									FROM 
										order_details_refunded 
									WHERE 
										orders_order_id = $edit_id  
									LIMIT 
										1";
				$ret_refcheck = $db->query($sql_refcheck);
				if($db->num_rows($ret_refcheck))
				{
									
				?>
					<tr>
						<td colspan="4" align="left" valign="bottom">
							<table width="100%" border="0" cellspacing="1" cellpadding="1">
							<tr>
							  <td colspan="2">&nbsp;</td>
							  </tr>
							<tr>
							<td width="100%" align="left" class="seperationtd_special">Refund Details</td>
							</tr>
							</table>						</td>
					</tr>
					<tr id="refunddet_tr" >
						<td align="right" colspan="4" class="tdcolorgray_buttons">
							<div id="refunddet_div" style="text-align:left"><?PHP // Get the currency symbol and conversion rate in current order
	/*$sql_ord = "SELECT *  
						FROM 
							orders 
						WHERE 
							order_id = ".$ordid." 
						LIMIT 
							1";
	$ret_ord = $db->query($sql_ord);
	if ($db->num_rows($ret_ord))
	{
		$row_ord 	= $db->fetch_array($ret_ord);
	} */
	$sql_ref = "SELECT refund_id,refund_on,refund_by,refund_amt
					FROM 
						order_details_refunded 
					WHERE 
						orders_order_id = $edit_id 
					ORDER BY 
						refund_on DESC";
	$ret_ref = $db->query($sql_ref);
	if($db->num_rows($ret_ref))
	{
		?>
			<table width="80%" cellpadding="1" cellspacing="1" border="0">
		<?php
		$table_varheaders 		= array('#','Date','Refunded By','Refunded Amount');
		$headervar_positions	= array('center','left','left','right');
		$var_colspans			= count($table_varheaders);
		$varsrno				= 1;
		$tot_amt 				= 0;
		echo table_header_print($table_varheaders,$headervar_positions);
		while ($row_ref = $db->fetch_array($ret_ref))
		{
			$tot_amt += $row_ref['refund_amt'];
			$cls = ($varsrno%2==0)?'listingtablestyleA':'listingtablestyleB';
		?>
				<tr>
					<td align="center" width="8%" class="<?php echo $cls?>">
						<?php echo $varsrno?>.
					</td>
					<td align="left" width="20%" class="<?php echo $cls?>">
						<?php
						echo dateFormat($row_ref['refund_on'],'datetime');
						// Check whether any products related to current refund
						$sql_refundprod = "SELECT refund_id
													FROM 
														order_details_refunded_products 
													WHERE 
														refund_id = ".$row_ref['refund_id']." 
													LIMIT 
														1";
						$ret_refundprod = $db->query($sql_refundprod);
						if ($db->num_rows($ret_refundprod))
						{
						?>
								<div id='refprodimgdiv_<?php echo $row_ref['refund_id']?>' class="show_vardiv_big" onClick="handle_showrefund_prods('<?php echo $row_ref['refund_id']?>')" title="Click here"><img src="images/right_arr.gif" /></div>
						<?php
						}
						?>
					</td>
					<td align="left" class="<?php echo $cls?>" width="45%">
					<?php
					echo getConsoleUserName($row_ref['refund_by']);
					?>
					</td>
					<td align="right" class="<?php echo $cls?>">
					<?php
					echo print_price_selected_currency($row_ref['refund_amt'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true);
					?>						
					</td>
				</tr>
				<tr id="refunddet_tr_<?php echo $row_ref['refund_id']?>" style="display:none">
				<td align="center" width="8%">&nbsp;</td>
				<td colspan="3">
				<div id="refunddet_div_<?php echo $row_ref['refund_id']?>">
				</div>
				</td>
				</tr>
		<?php
		$varsrno++;
		}
		?>
			<tr>
				<td align="center" class="shoppingcartpriceB">&nbsp;</td>
				<td align="center" class="shoppingcartpriceB">&nbsp;</td>
				<td align="right" class="shoppingcartpriceB">
				Total Refunded
				</td>
				<td align="right" class="shoppingcartpriceB">
					<?php
					echo print_price_selected_currency($tot_amt,$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true);
					?>
				</td>
			</tr>
		</table>
		<?php
	} ?></div>						</td>
					</tr>
				<?php
				}
			?>		
		
		<tr>
          <td colspan="4" align="left" valign="bottom">
            <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td width="100%" align="left" class="seperationtd_special">Billing Details</td>
            </tr>
          </table></td>
        </tr>
		<tr id="bill_tr" >
			<td align="right" colspan="4" class="tdcolorgray_buttons">
				<div id="bill_div" style="text-align:center"><?PHP 
				$order_id = $edit_id;
				$sql_ord = "SELECT order_custtitle,order_custfname,order_custmname,order_custsurname,order_custcompany,
							order_buildingnumber,order_street,order_city,order_state,order_country,
							order_custpostcode,order_custphone,order_custfax,order_custmobile,
							order_custemail,order_notes  
					FROM 
						orders 
					WHERE 
						order_id = $order_id 
						AND sites_site_id = $ecom_siteid 
					LIMIT 
						1";
	$ret_billord = $db->query($sql_ord);
	if ($db->num_rows($ret_billord))
	{
		$row_billord = $db->fetch_array($ret_billord);
	}
	$cls = 'listingtablestyleB';
	?>
		<table width="100%" border="0" cellspacing="1" cellpadding="1">
		<?php
		$max_cols	= 2;
		$cur_col	= 0;
		// Including the file to show the dynamic fields for orders to the give position of static fields
		$cur_pos 		= 'Top';
		$show_header	= 1;
		include 'show_dynamic_fields_orders.php';
		if ($cur_col<$max_cols)
			echo '<td colspan="'.($max_cols-$cur_col).'" class="'.$cls.'">&nbsp;</td></tr>';
		?>
			<tr>
				<td colspan="2" align="left" class="shoppingcartheader">Billing Address</td>
			</tr>
			<?php

			// Including the file to show the dynamic fields for orders to the give position of static fields
			$cur_pos 		= 'TopInStatic';
			$show_header	= 0;
			$max_cols	= 2;
			$cur_col	= 0;
			include 'show_dynamic_fields_orders.php';

			// Get the list of billing address static fields to be shown in the checkout out page in required order
			$sql_checkout = "SELECT field_key,field_name,field_orgname
										FROM 
											general_settings_site_checkoutfields 
										WHERE 
											sites_site_id = $ecom_siteid 
											AND field_hidden = 0 
											AND field_type = 'PERSONAL' 
										ORDER BY 
											field_order";
			$ret_checkout = $db->query($sql_checkout);
			if($db->num_rows($ret_checkout))
			{
				$srno = 0;
				$name = stripslashes($row_billord['order_custtitle']).stripslashes($row_billord['order_custfname']).' '.stripslashes($row_billord['order_custmname']).' '.stripslashes($row_billord['order_custsurname']);
				while($row_checkout = $db->fetch_array($ret_checkout))
				{
					$caption 	= '';
					$value		= '';
					if ($row_checkout['field_key']=='checkout_title')
					{
						$caption 	= 'Name';
						$value		= $name;
						$proceed 	= true;
					}
					else if($row_checkout['field_key'] !='checkout_fname' and $row_checkout['field_key']!= 'checkout_mname' and $row_checkout['field_key'] != 'checkout_surname')
					{
						$caption = stripslashes($row_checkout['field_name']);
						$value	= stripslashes($row_ord[$row_checkout['field_orgname']]);
						$proceed = true;
					}
					else
					{
						$proceed = false;
					}

					if ($proceed==true and $value!='')
					{
						if($cur_col==0)
						echo "<tr>";
				?>
									<td align="left" width="50%" class="<?php echo $cls?>">
										<table width="100%" cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td align="left" width="50%" class="subcaption">
											<?php echo $caption?>											</td>
											<td align="left" valign="middle"  class="listingtablestyleB">
											<?php echo $value?>											</td>
										</tr>
										</table>									</td>	
				<?php
				$cur_col++;
				//echo '<br> curcol: '.$cur_col.' ---';
				if ($cur_col>=$max_cols)
				{
					echo "</tr>";
					$cur_col = 0;
					$cls = ($srno%2==0)?'listingtablestyleB':'listingtablestyleB';
					$srno++;
				}
					}
				}
			}
			// Including the file to show the dynamic fields for orders to the give position of static fields
			$cur_pos 		= 'BottomInStatic';
			$show_header	= 0;
			include 'show_dynamic_fields_orders.php';
			if ($cur_col<$max_cols)
			echo '<td colspan="'.($max_cols-$cur_col).'" class="'.$cls.'">&nbsp;</td></tr>';
		?>		
		</table>
		</div></td>
		</tr>
		<tr>
          <td colspan="4" align="left" valign="bottom">
            <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td width="100%" align="left" class="seperationtd_special">Delivery Details </td>
            </tr>
          </table></td>
        </tr>
		<tr id="delivery_tr" >
			<td align="right" colspan="4" class="tdcolorgray_buttons">
				<div id="delivery_div" style="text-align:center">
				<?PHP
				$sql_devord = "SELECT order_currency_convertionrate,order_currency_symbol,
							order_deliverytype,order_deliverylocation,order_delivery_option,
							order_deliverytotal,order_splitdeliveryreq,order_extrashipping,
							order_deliveryprice_only    
						FROM 
							orders 
						WHERE 
							order_id = $order_id 
						LIMIT 
							1";
	$ret_devord = $db->query($sql_devord);
	if ($db->num_rows($ret_devord))
	{
		$row_devord = $db->fetch_array($ret_devord);
	}

	 $sql_del = "SELECT delivery_title,delivery_fname,delivery_mname,delivery_lname,delivery_companyname,
							delivery_buildingnumber,delivery_street,delivery_city,delivery_state,delivery_country,
							delivery_zip,delivery_phone,delivery_fax,delivery_mobile,delivery_email 
					FROM 
						order_delivery_data  
					WHERE 
						orders_order_id = $order_id 
					LIMIT 
						1";
	$ret_del = $db->query($sql_del);
	if ($db->num_rows($ret_del))
	{
		$row_del = $db->fetch_array($ret_del);
	}
	$cls = 'listingtablestyleB';
	?>
		<table width="100%" border="0" cellspacing="1" cellpadding="1">
		<tr>
			<td colspan="2" align="left" class="shoppingcartheader">Delivery Address</td>
		</tr>
			<?php
			$max_cols	= 2;
			$cur_col	= 0;
			// Get the list of billing address static fields to be shown in the checkout out page in required order
			$sql_checkout = "SELECT field_key,field_name,field_orgname
										FROM 
											general_settings_site_checkoutfields 
										WHERE 
											sites_site_id = $ecom_siteid 
											AND field_hidden = 0 
											AND field_type = 'DELIVERY' 
										ORDER BY 
											field_order";
			$ret_checkout = $db->query($sql_checkout);
			if($db->num_rows($ret_checkout))
			{
				$name = stripslashes($row_del['delivery_title']).stripslashes($row_del['delivery_fname']).' '.stripslashes($row_del['delivery_mname']).' '.stripslashes($row_del['delivery_lname']);
				while($row_checkout = $db->fetch_array($ret_checkout))
				{
					$caption 	= '';
					$value		= '';
					if ($row_checkout['field_key']=='checkoutdelivery_title')
					{
						$caption 	= 'Name';
						$value		= $name;
						$proceed 	= true;
					}
					else if($row_checkout['field_key'] !='checkoutdelivery_fname' and $row_checkout['field_key']!= 'checkoutdelivery_mname' and $row_checkout['field_key'] != 'checkoutdelivery_surname')
					{
						$caption = stripslashes($row_checkout['field_name']);
						$value	= stripslashes($row_del[$row_checkout['field_orgname']]);
						$proceed = true;
					}
					else
					{
						$proceed = false;
					}

					if ($proceed==true and $value!='')
					{
						if($cur_col==0)
						echo "<tr>";
				?>
									<td align="left" width="50%" class="<?php echo $cls?>">
										<table width="100%" cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td align="left" width="50%" class="subcaption">
											<?php echo $caption?>											</td>
											<td align="left" valign="middle" class="listingtablestyleB">
											<?php echo $value?>											</td>
										</tr>
										</table>									</td>	
				<?php
				$cur_col++;
				//echo '<br> curcol: '.$cur_col.' ---';
				if ($cur_col>=$max_cols)
				{
					echo "</tr>";
					$cur_col = 0;
					$cls = ($srno%2==0)?'listingtablestyleB':'listingtablestyleB';
					$srno++;
				}
					}
				}
				if ($cur_col<$max_cols)
					echo '<td colspan="'.($max_cols-$cur_col).'" class="'.$cls.'">&nbsp;</td></tr>';
			}
			// Including the file to show the dynamic fields for orders to the give position of static fields
			$cur_pos 		= 'Bottom';
			$show_header	= 1;
			$max_cols		= 2;
			$cur_col		= 0;
			include 'show_dynamic_fields_orders.php';
			if ($cur_col<$max_cols)
			echo '<td colspan="'.($max_cols-$cur_col).'" class="'.$cls.'">&nbsp;</td></tr>';
		?>	
		<tr>
			<td colspan="2" align="left" class="shoppingcartheader">Delivery Method Details</td>
		</tr>	
		<tr>
			<td colspan="2" align="left">
			<table width="100%" cellpadding="1" cellspacing="1" border="0">
			<?php
			$srno =1;
			if($row_devord['order_deliverytype']!='None')
			{
			?>
					<tr>
						<td align="left" width="25%" class="subcaption listingtablestyleB">Delivery Type</td>
						<td align="left" width="25%" class="listingtablestyleB"><?php echo ucwords(strtolower(stripslashes($row_devord['order_deliverytype'])))?></td>
						<td align="left" width="25%" class="listingtablestyleB">&nbsp;</td>
						<td align="left" width="25%" class="listingtablestyleB">&nbsp;</td>
					</tr>
			<?php
			$srno++;
			}
			if($row_devord['order_deliverylocation']!='')
			{
				$cls = ($srno%2==0)?'listingtablestyleB':'listingtablestyleB';
				$srno++;
			?>
					<tr>
						<td align="left" width="25%" class="subcaption <?php echo $cls?>">Delivery Location</td>
						<td align="left" width="25%" class="<?php echo $cls?>"><?php echo ucwords(strtolower(stripslashes($row_devord['order_deliverylocation'])))?>(<?php echo ucwords(strtolower(stripslashes($row_devord['order_delivery_option'])))?>)</td>
						<td align="left" width="25%" class="<?php echo $cls?>"></td>
						<td align="left" width="25%" class="<?php echo $cls?>"><?php echo print_price_selected_currency($row_devord['order_deliveryprice_only'],$row_devord['order_currency_convertionrate'],$row_devord['order_currency_symbol'],true)?></td>
					</tr>
			<?php
			}
			if($row_devord['order_extrashipping']>0)
			{
				$cls = ($srno%2==0)?'listingtablestyleB':'listingtablestyleB';
				$srno++;
			?>	
					<tr>
						<td align="left" width="25%" class="subcaption <?php echo $cls?>">Extra Shipping Charge</td>
						<td align="left" width="25%" class="<?php echo $cls?>">&nbsp;</td>
						<td align="left" width="25%" class="<?php echo $cls?>">&nbsp;</td>
						<td align="left" width="25%" class="<?php echo $cls?>"><?php echo print_price_selected_currency($row_devord['order_extrashipping'],$row_devord['order_currency_convertionrate'],$row_devord['order_currency_symbol'],true)?></td>
					</tr>
			<?php
			}
			if ($row_devord['order_deliverytotal']>0)
			{
			?>
					<tr>
						<td align="left" width="25%" class="shoppingcartpriceB">&nbsp;</td>
						<td align="left" width="25%" class="shoppingcartpriceB">&nbsp;</td>
						<td align="left" width="25%" class="shoppingcartpriceB">Total Delivery Charge</td>
						<td align="left" width="25%" class="shoppingcartpriceB"><?php echo print_price_selected_currency($row_devord['order_deliverytotal'],$row_devord['order_currency_convertionrate'],$row_devord['order_currency_symbol'],true)?></td>
					</tr>
			<?php
			}
			?>		
			</table>			</td>
		</tr>
		</table></div></td>
		</tr>
		<?php 
			if($row_ord['order_giftwrap']=='Y') // show only if gift wrap exists
			{
		?>
				<tr>
				  <td colspan="4" align="left" valign="bottom">
					<table width="100%" border="0" cellspacing="1" cellpadding="1">
					<tr>
					  <td width="100%" align="left" class="seperationtd_special">Giftwrap Details</td>
					</tr>
				  </table></td>
				</tr>
				<tr id="gift_tr">
					<td align="right" colspan="4" class="tdcolorgray_buttons">
						<div id="gift_div" style="text-align:center"><?PHP
							// Get the order current related things from order table inorder to show the price in the currency selected by
	// the customer
	$sql_ord = "SELECT order_currency_convertionrate,order_currency_symbol,
							order_giftwrap_per,order_giftwrapmessage,
							order_giftwrapmessage_text,order_giftwrap_message_charge,order_giftwrap_minprice,
							order_giftwraptotal   
						FROM 
							orders 
						WHERE 
							order_id = $order_id 
						LIMIT 
							1";
	$ret_ord = $db->query($sql_ord);
	if ($db->num_rows($ret_ord))
	{
		$row_giftord = $db->fetch_array($ret_ord);
	}

	$sql_gift = "SELECT giftwrap_name,giftwrap_price,giftwrap_type
					FROM 
						order_giftwrap_details   
					WHERE 
						orders_order_id = $order_id";
	$ret_gift = $db->query($sql_gift);
	if ($db->num_rows($ret_gift))
	{
		$srno = 0;
	?>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td align="left" colspan="2">
				<table width="100%" cellpadding="1" cellspacing="1" border="0">
				<tr>
					<td align="left" width="25%" class="subcaption listingtablestyleB">
					Gift Wrap Apply to					</td>
					<td align="left" width="25%" class="listingtablestyleB">
					<?php 
					echo ucwords(stripslashes($row_giftord['order_giftwrap_per']));

					?>					</td>
					<td align="left" width="25%" class="subcaption listingtablestyleB">
					Gift Wrap Minimum Charge					</td>
					<td align="left" width="25%" class="listingtablestyleB">
					<?php 
					echo print_price_selected_currency($row_giftord['order_giftwrap_minprice'],$row_giftord['order_currency_convertionrate'],$row_giftord['order_currency_symbol'],true);

					?>					</td>
				</tr>
				</table>			</td>
		</tr>
		<?php
		
		if($row_giftord['order_giftwrapmessage']=='Y')
		{
			$cls = ($srno%2==0)?'listingtablestyleB':'listingtablestyleB';
			$srno++;
		?>
				<tr>
					<td align="left" colspan="2">
						<table width="100%" cellpadding="1" cellspacing="1" border="0">
						<tr>
							<td width="51%" align="left" valign="top" class="subcaption <?php echo $cls?>">
							Gift Wrap Message</td>
							<td width="24%" align="left" valign="top" class="subcaption <?php echo $cls?>">Message charge </td>
							<td width="25%" align="left" valign="top" class="<?php echo $cls?>">
							<?php echo print_price_selected_currency($row_giftord['order_giftwrap_message_charge'],$row_giftord['order_currency_convertionrate'],$row_giftord['order_currency_symbol'],true);?>						  </td>
						</tr>
						<tr>
						  <td colspan="3" align="left" valign="top" style="padding:2px 5px 2px 25px" class="<?php echo $cls?>">
						  <?php 
						  echo nl2br(stripslashes($row_giftord['order_giftwrapmessage_text']));
							?></td>
						  </tr>
						</table>					</td>
				</tr>
		<?php
		}
		$max_cols	= 1;
		$cur_col	= 0;
		$cls = ($srno%2==0)?'listingtablestyleB':'listingtablestyleB';
		$srno++;
		while($row_gift = $db->fetch_array($ret_gift))
		{

			if($cur_col==0)
			echo "<tr>";
	?>
						<td align="left" width="50%">
							<table width="100%" cellpadding="1" cellspacing="1" border="0">
							<tr>
								<td align="left" width="25%" class="subcaption <?php echo $cls?>">
									<?php echo ucwords(strtolower(stripslashes($row_gift['giftwrap_type'])))?>								</td>
								<td align="left" valign="middle" width="25%" class="<?php echo $cls?>">
									<?php echo stripslashes($row_gift['giftwrap_name'])?></td>
								<td align="left" width="25%" class="subcaption <?php echo $cls?>"><?php echo ucwords(strtolower(stripslashes($row_gift['giftwrap_type'])))?> Charge								</td>
								<td align="left" valign="middle" width="25%" class="<?php echo $cls?>">
									<?php echo print_price_selected_currency($row_gift['giftwrap_price'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true);?>								</td>
							</tr>
							</table>						</td>	
	<?php
	$cur_col++;
	//echo '<br> curcol: '.$cur_col.' ---';
	if ($cur_col>=$max_cols)
	{
		echo "</tr>";
		$cur_col = 0;
		$cls = ($srno%2==0)?'listingtablestyleB':'listingtablestyleB';
		$srno++;
	}
		}

		if ($cur_col<$max_cols)
		echo '<td colspan="'.($max_cols-$cur_col).'">&nbsp;</td></tr>';
		?>		
		<tr>
		  <td align="right" colspan="2">
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr>
				  <td align="left" width="25%" class="shoppingcartpriceB">&nbsp;</td>
				  <td align="left" valign="middle" width="25%" class="shoppingcartpriceB">&nbsp;</td>
				  <td align="left" class="shoppingcartpriceB" width="25%">Gift Wrap Total:</td>
				  <td align="left" valign="middle" class="shoppingcartpriceB" width="25%"><?php echo print_price_selected_currency($row_giftord['order_giftwraptotal'],$row_giftord['order_currency_convertionrate'],$row_giftord['order_currency_symbol'],true);?></td>
			  	</tr>
				</table>		</td>
		</tr>	
		</table>
	<?php
	}
						 ?></div></td>
				</tr>
		<?php
			}
			if($row_ord['order_tax_total']>0)
			{
		?>
				<tr>
				  <td colspan="4" align="left" valign="bottom">
					<table width="100%" border="0" cellspacing="1" cellpadding="1">
					<tr>
					  <td width="100%" align="left" class="seperationtd_special">Tax Details</td>
					</tr>
				  </table></td>
				</tr>
				<tr id="tax_tr" >
					<td align="right" colspan="4" class="tdcolorgray_buttons">
						<div id="tax_div" style="text-align:center"><?PHP 
						
						// Get the order current related things from order table inorder to show the price in the currency selected by
	// the customer
	/* $sql_ord = "SELECT order_currency_convertionrate,order_currency_symbol,
							order_tax_total,order_tax_to_delivery,
							order_tax_to_giftwrap  
						FROM 
							orders 
						WHERE 
							order_id = $order_id 
						LIMIT 
							1";
	$ret_ord = $db->query($sql_ord);
	if ($db->num_rows($ret_ord))
	{
		$row_ord = $db->fetch_array($ret_ord);
	} */

	$sql_tax = "SELECT tax_name,tax_percent,tax_charge
					FROM 
						order_tax_details   
					WHERE 
						orders_order_id = $order_id";
	$ret_tax = $db->query($sql_tax);
	if ($db->num_rows($ret_tax))
	{
	?>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td align="left" colspan="2">
				<table width="100%" cellpadding="1" cellspacing="1" border="0">
				<?php 
				$srno=1;
				while ($row_tax = $db->fetch_array($ret_tax))
				{
					$cls = ($srno%2==0)?'listingtablestyleB':'listingtablestyleB';
					$srno++;
				?>
						<tr>
							<td align="left" width="25%" class="subcaption <?php echo $cls?>">
							<?php echo stripslashes($row_tax['tax_name'])?>							</td>
							<td align="left" width="25%" class="<?php echo $cls?>">
							<?php echo stripslashes($row_tax['tax_percent'])?>%							</td>
							<td align="left" width="25%" class="<?php echo $cls?>">							</td>
							<td align="left" width="25%" class="<?php echo $cls?>">
							<?php 
							echo print_price_selected_currency($row_tax['tax_charge'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true);
							?>							</td>
						</tr>
				<?php
				}
				?>	
				<tr>
					<td align="left" width="25%" class="shoppingcartpriceB"></td>
					<td align="left" width="25%" class="shoppingcartpriceB"></td>
					<td align="left" width="25%" class="shoppingcartpriceB">Total Tax</td>
					<td align="left" width="25%" class="shoppingcartpriceB">
					<?php 
					echo print_price_selected_currency($row_ord['order_tax_total'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true);
					?>					</td>
				</tr>			
				</table>			</td>
		</tr>
		</table>
		
	<?php
	}
						?></div></td>
				</tr>
		<?php
			}
			if($row_ord['gift_vouchers_voucher_id']>0)// show only if gift voucher exists in current order
			{
		?>	
				<tr>
				  <td colspan="4" align="left" valign="bottom">
					<table width="100%" border="0" cellspacing="1" cellpadding="1">
					<tr>
					  <td width="100%" align="left" class="seperationtd_special">Gift Voucher Details</td>
					</tr>
				  </table></td>
				</tr>
				<tr id="voucher_tr" >
					<td align="right" colspan="4" class="tdcolorgray_buttons">
						<div id="voucher_div" style="text-align:center"><?PHP 
						// Get the order current related things from order table inorder to show the price in the currency selected by
	// the customer
/*	echo $sql_ord = "SELECT *
						FROM 
							orders 
						WHERE 
							order_id = $order_id 
						LIMIT 
							1";
	$ret_ord = $db->query($sql_ord);
	if ($db->num_rows($ret_ord))
	{
		$row_ord = $db->fetch_array($ret_ord);
	}
*/
	$sql_voucher = "SELECT voucher_no,voucher_value_used,voucher_type,actual_voucher_value
					FROM 
						order_voucher  
					WHERE 
						orders_order_id = $order_id";
	$ret_voucher = $db->query($sql_voucher);
	if ($db->num_rows($ret_voucher))
	{
	?>
		<table width="100%" border="0" cellspacing="1" cellpadding="1">
		<tr>
			<td align="left" colspan="2">
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td align="left" width="25%" class="shoppingcartheader">
					Voucher Number					</td>
					<td align="center" width="25%" class="shoppingcartheader">
					Type					</td>
					<td align="left" width="25%" class="shoppingcartheader">&nbsp;</td>
					<td align="left" width="25%" class="shoppingcartheader">
					Total Discount					</td>
				</tr>
				<?php 
				while ($row_voucher = $db->fetch_array($ret_voucher))
				{
				?>
						<tr>
							<td align="left" width="25%" class="listingtablestyleB">
							<?php echo stripslashes($row_voucher['voucher_no'])?>							</td>
							<td align="center" width="25%" class="listingtablestyleB">
							<?php echo stripslashes($row_voucher['voucher_type'])?>							</td>
							<td align="left" width="25%" class="listingtablestyleB">&nbsp;							</td>
							<td align="left" width="25%" class="listingtablestyleB">
							<?php 
							echo print_price_selected_currency($row_voucher['voucher_value_used'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true);
							?>							</td>
						</tr>
				<?php
				}
				?>	
				</table>			</td>
		</tr>
		</table>
	<?php
	}
						?></div></td>
				</tr>
		<?php
			}
			if($row_ord['promotional_code_code_id']>0)// show only if promotional code exists in current order
			{
		?>	
				<tr>
				  <td colspan="4" align="left" valign="bottom">
					<table width="100%" border="0" cellspacing="1" cellpadding="1">
					<tr>
					  <td width="100%" align="left" class="seperationtd_special">Promotional Code Details</td>
					</tr>
				  </table></td>
				</tr>
				<tr id="prom_tr" >
					<td align="right" colspan="4" class="tdcolorgray_buttons">
						<div id="prom_div" style="text-align:center"><?PHP 
	// Get the order current related things from order table inorder to show the price in the currency selected by
	// the customer
	/*$sql_ord = "SELECT order_currency_convertionrate,order_currency_symbol
						FROM 
							orders 
						WHERE 
							order_id = $order_id 
						LIMIT 
							1";
	$ret_ord = $db->query($sql_ord);
	if ($db->num_rows($ret_ord))
	{
		$row_ord = $db->fetch_array($ret_ord);
	}*/

	$sql_prom = "SELECT code_type,code_number,code_orgvalue,code_lessval,code_minimum,code_value
					FROM 
						order_promotional_code 
					WHERE 
						orders_order_id = $order_id";
	$ret_prom = $db->query($sql_prom);
	if ($db->num_rows($ret_prom))
	{
	?>
		<table width="100%" border="0" cellspacing="1" cellpadding="1">
		<tr>
			<td align="left">
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td align="left" width="25%" class="shoppingcartheader">
					Promotional Code					</td>
					<td align="left" width="25%" class="shoppingcartheader">
					Type					</td>
					<td align="left" width="25%" class="shoppingcartheader">&nbsp;					</td>
					<td align="left" width="25%" class="shoppingcartheader">
					Total Discount					</td>
				</tr>
				<?php 
				while ($row_prom = $db->fetch_array($ret_prom))
				{
				?>
						<tr>
							<td align="left" width="25%" class="shoppingcartpriceB">
							<?php echo stripslashes($row_prom['code_number'])?>							</td>
							<td align="left" width="25%" class="shoppingcartpriceB">
							<?php echo get_promotional_type(stripslashes($row_prom['code_type']))?>							</td>
							<td align="left" width="25%" class="shoppingcartpriceB">&nbsp;							</td>
							<td align="left" width="25%" class="shoppingcartpriceB">
							<?php 
							echo print_price_selected_currency($row_prom['code_lessval'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true);
							?>							</td>
						</tr>
				<?php
				}
				?>	
				</table>			</td>
		</tr>
		</table>
	<?php
	}
						?></div></td>
				</tr>
		<?php
			}
           	if($row_ord['order_paymenttype']=='credit_card' || $row_ord['order_paymenttype']=='cheque' )
           	{

		?>
		<tr>
          <td colspan="4" align="left" valign="bottom">
            <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td width="100%" align="left" class="seperationtd_special">Payment Details</td>
            </tr>
          </table></td>
        </tr>
		<tr id="payment_tr" >
			<td align="right" colspan="4" class="tdcolorgray_buttons">
				<div id="payment_div" style="text-align:center"><?PHP 
				// Get the order current related things from order table inorder to show the price in the currency selected by
	// the customer
/*	echo $sql_ord = "SELECT order_currency_convertionrate,order_currency_symbol,order_paymenttype,
							order_paymentmethod,order_paystatus,order_paystatus_changed_manually,
							order_paystatus_changed_manually_by,order_paystatus_changed_manually_on  
						FROM 
							orders 
						WHERE 
							order_id = $order_id 
						LIMIT 
							1";
	$ret_ord = $db->query($sql_ord);
	if ($db->num_rows($ret_ord))
	{
		$row_ord = $db->fetch_array($ret_ord);
	} */
	if($row_ord['order_paymenttype']=='credit_card') // case of payment is by credit card
	{
		if ($row_ord['order_paymentmethod']=='SELF' or $row_ord['order_paymentmethod']=='protx') // If method is self or protx
		{
	?>
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
				<td align="left" colspan="2">
					<table width="100%" cellpadding="1" cellspacing="1" border="0">
					<?php
					// Check whether any record exists for current order in order_payment_main table
					$sql_pay = "SELECT *
									FROM 
										order_payment_main 
									WHERE 
										orders_order_id = $order_id 
										AND sites_site_id = $ecom_siteid 
									LIMIT 1";
					$ret_pay = $db->query($sql_pay);
					if ($db->num_rows($ret_pay))
					{
						$row_pay= $db->fetch_array($ret_pay);
					?>
						<tr>
							<td align="left" colspan="2" class="shoppingcartheader">
							Credit Card Details							</td>
						</tr>
					<?php	
					$srno=1;
					if ($row_pay['order_card_type']!='')
					{
						$cls = ($srno%2==0)?'listingtablestyleB':'listingtablestyleB';
						$srno++;
					?>
							<tr>
								<td align="left" width="25%" class="subcaption <?php echo $cls?>">
								Card Type								</td>
								<td align="left" class="<?php echo $cls?>">
									<?php echo $row_pay['order_card_type']?>								</td>
							</tr>
					<?php
					}
					if ($row_pay['order_name_on_card']!='')
					{
						$cls = ($srno%2==0)?'listingtablestyleB':'listingtablestyleB';
						$srno++;
					?>
							<tr>
								<td align="left" width="25%" class="subcaption <?php echo $cls?>">
								Name on Card								</td>
								<td align="left" class="<?php echo $cls?>">
								<?php echo stripslashes($row_pay['order_name_on_card'])?>								</td>
							</tr>
					<?php
					}
					if ($row_pay['order_card_number']!='')
					{
						$cls = ($srno%2==0)?'listingtablestyleB':'listingtablestyleB';
						$srno++;
						if($row_pay['order_card_encrypted']==1)
						$cc = base64_decode(base64_decode($row_pay['order_card_number']));
						else
						$cc = $row_pay['order_card_number'];
						if($row_ord['order_paystatus']=='Paid')
						{
							$len	= (strlen($cc)-4);
							$cc 	= substr($cc,-4);
							for($i=0;$i<$len;$i++)
							{
								$ccs .='x';
							}
							$cc		= $ccs.$cc;
						}
					?>
							<tr>
								<td align="left" width="25%" class="subcaption <?php echo $cls?>">
								Card Number								</td>
								<td align="left" class="<?php echo $cls?>">
								<?php echo stripslashes($cc)?>								</td>
							</tr>
					<?php
					}
					if ($row_pay['order_sec_code']!=0)
					{
						$cls = ($srno%2==0)?'listingtablestyleB':'listingtablestyleB';
						$srno++;
					?>
							<tr>
								<td align="left" width="25%" class="subcaption <?php echo $cls?>">
								Security Code								</td>
								<td align="left" class="<?php echo $cls?>">
								<?php echo stripslashes($row_pay['order_sec_code'])?>								</td>
							</tr>
					<?php
					}
					if ($row_pay['order_expiry_date_m']!=0 and $row_pay['order_expiry_date_y']!=0)
					{
						$cls = ($srno%2==0)?'listingtablestyleB':'listingtablestyleB';
						$srno++;
					?>
							<tr>
								<td align="left" width="25%" class="subcaption <?php echo $cls?>">
								Expiry Date								</td>
								<td align="left" class="<?php echo $cls?>">
								<?php echo $row_pay['order_expiry_date_m'].'/'.$row_pay['order_expiry_date_y']?>								</td>
							</tr>
					<?php
					}
					if ($row_pay['order_issue_number']!=0)
					{
						$cls = ($srno%2==0)?'listingtablestyleB':'listingtablestyleB';
						$srno++;
					?>
							<tr>
								<td align="left" width="25%" class="subcaption <?php echo $cls?>">
								Issue Number								</td>
								<td align="left" class="<?php echo $cls?>">
								<?php echo $row_pay['order_issue_number']?>								</td>
							</tr>
					<?php
					}
					if ($row_pay['order_issue_date_m']!=0 and $row_pay['order_issue_date_y']!=0)
					{
						$cls = ($srno%2==0)?'listingtablestyleB':'listingtablestyleB';
						$srno++;
					?>
							<tr>
								<td align="left" width="25%" class="subcaption <?php echo $cls?>">
								Issue Date								</td>
								<td align="left" class="<?php echo $cls?>">
								<?php echo $row_pay['order_issue_date_m'].'/'.$row_pay['order_issue_date_y']?>								</td>
							</tr>
					<?php
					}
					if ($row_pay['order_vendorTxCode']!='')
					{
						$cls = ($srno%2==0)?'listingtablestyleB':'listingtablestyleB';
						$srno++;
					?>
							<tr>
								<td align="left" width="25%" class="subcaption <?php echo $cls?>">
								Vendor								</td>
								<td align="left" class="<?php echo $cls?>">
								<?php echo $row_pay['order_issue_date_m'].'/'.$row_pay['order_issue_date_y']?>								</td>
							</tr>
					<?php
					}
					}
				
					?>
					</table>				</td>
			</tr>
			</table>
	<?php
		}
		else // case of paymethod other than self or protx
		{
		?>	
				<table width="100%" cellpadding="1" cellspacing="1" border="0">
				<tr>
					<td align="left" colspan="2" class="shoppingcartheader">
					Payment Gateway Return Details					</td>
				</tr>
				<tr>
					<td align="left" width="25%" class="subcaption listingtablestyleB">
					Transaction ID					</td>
					<td align="left" class="listingtablestyleB">
					<?php echo $row_pay['order_googletransId']?>					</td>
				</tr>
				</table>
		<?php
		}
	}
	elseif ($row_ord['order_paymenttype']=='cheque')
	{
		// Get the cheque details from order_cheque_details table
		$sql_cheque = "SELECT cheque_date,cheque_number,cheque_bankname,cheque_branchdetails
							FROM 
								order_cheque_details 
							WHERE 
								orders_order_id = $order_id 
							LIMIT 
								1";
		$ret_cheque = $db->query($sql_cheque);
		if($db->num_rows($ret_cheque))
		{
			$row_cheque = $db->fetch_array($ret_cheque);
		?>
				<table width="100%" cellpadding="1" cellspacing="1" border="0">
				<tr>
					<td align="left" colspan="4" class="shoppingcartheader">
					Cheque Details					</td>
				</tr>
				<tr>
					<td align="left" width="25%" class="subcaption listingtablestyleB">
					Date of Cheque					</td>
					<td align="left" width="25%" class="listingtablestyleB">
					<?php echo stripslashes($row_cheque['cheque_date'])?>					</td>
					<td align="left" width="25%" class="subcaption listingtablestyleB">
					Cheque Number					</td>
					<td align="left" width="25%" class="listingtablestyleB">
					<?php echo stripslashes($row_cheque['cheque_number'])?>					</td>
				</tr>
				<tr>
					<td align="left" width="25%" class="subcaption listingtablestyleB">
					Bank Name					</td>
					<td align="left" width="25%" class="listingtablestyleB">
					<?php echo stripslashes($row_cheque['cheque_bankname'])?>					</td>
					<td align="left" width="25%" class="subcaption listingtablestyleB">
					Branch Details					</td>
					<td align="left" width="25%" class="listingtablestyleB">
					<?php echo nl2br(stripslashes($row_cheque['cheque_branchdetails']))?>					</td>
				</tr>
				</table>
		<?php
		}
	}
				?></div></td>
		</tr>
					<? } ?>

<!--		<tr>
				<td align="center" colspan="4" class="tdcolorgray_buttons"><input type="button" name="Submit" value=" Print " onClick="javascript:window.print();" /></td>
		</tr>
 -->	</table>
		<?PHP } //While Finish hEre
			}  //If Finish here
	echo "<tr>
	<td align='center' colspan='4' class='tdcolorgray_buttons'>
	============================================================================================================================ 
	</td>
	</tr> ";		
		  } //foreach Finish here ?>
    <table width="100%" border="0" cellpadding="1" cellspacing="1"><tr>
	<td align="center" colspan="4" class="tdcolorgray_buttons"><input type="button" name="Submit" value=" Print " onClick="javascript:window.print();" /></td>
	</tr> </table>		  
		  
		  
<? /* ?>
		<input type="hidden" name="checkbox[0]" id="checkbox[0]" value="<?=$edit_id?>" />
		
		<input type="hidden" name="ord_status" value="<?php echo ($_REQUEST['ord_status'])?$_REQUEST['ord_status']:$_REQUEST['ser_ord_status']?>" />
		<input type="hidden" name="ord_name" value="<?php echo ($_REQUEST['ord_name'])?$_REQUEST['ord_name']:$_REQUEST['ser_ord_name']?>" />
		<input type="hidden" name="ord_email" value="<?php echo ($_REQUEST['ord_email'])?$_REQUEST['ord_email']:$_REQUEST['ser_ord_email']?>" />
		<input type="hidden" name="ord_fromdate" value="<?php echo ($_REQUEST['ord_fromdate'])?$_REQUEST['ord_fromdate']:$_REQUEST['ser_ord_fromdate']?>" />
		<input type="hidden" name="ord_todate" value="<?php echo ($_REQUEST['ord_todate'])?$_REQUEST['ord_todate']:$_REQUEST['ser_ord_todate']?>" />
		<input type="hidden" name="ord_stores" value="<?php echo ($_REQUEST['ord_stores'])?$_REQUEST['ord_stores']:$_REQUEST['ser_ord_stores']?>" />
		<input type="hidden" name="ord_sort_by" value="<?php echo ($_REQUEST['ord_sort_by'])?$_REQUEST['ord_sort_by']:$_REQUEST['ser_ord_sort_by']?>" />
		<input type="hidden" name="ord_sort_order" value="<?php echo ($_REQUEST['ord_sort_order'])?$_REQUEST['ord_sort_order']:$_REQUEST['ser_ord_sort_order']?>" />
		<input type="hidden" name="pg" value="<?php echo ($_REQUEST['pg'])?$_REQUEST['pg']:$_REQUEST['ser_pg']?>" />
		<input type="hidden" name="start" value="<?php echo ($_REQUEST['start'])?$_REQUEST['start']:$_REQUEST['ser_start']?>" />
		<input type="hidden" name="records_per_page" value="<?php echo ($_REQUEST['records_per_page'])?$_REQUEST['records_per_page']:$_REQUEST['ser_records_per_page']?>" />

		<input type="hidden" name="req_change_ordstat" id="req_change_ordstat" value="" />
		<input type="hidden" name="req_change_ordpaystat" id="req_change_ordpaystat" value="" />
		<input type="hidden" name="sel_orddet" id="sel_orddet" value="" />
		<input type="hidden" name="req_release_amt" id="req_release_amt" value="" />
		<input type="hidden" name="del_note_id" id="del_note_id" value="" />
		<input type="hidden" name="paycapture_type" id="paycapture_type" value="" />
		<input type="hidden" name="retdiv_id" id="retdiv_id" value="" />
		<input type="hidden" name="retdiv_more" id="retdiv_more" value="" />
		<input type="hidden" name="fpurpose" id="fpurpose" value="" /><? */ ?>
	
</form>	  

</body>
</html>
