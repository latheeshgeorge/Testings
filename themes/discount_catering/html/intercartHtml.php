<?php
	/*############################################################################
	# Script Name 		: cartHtml.php
	# Description 		: Page which holds the display logic for cart and checkout
	# Coded by 			: Sny
	# Created on		: 04-Feb-2008
	# Modified by		: Sny
	# Modified On		: 04-Aug-2008
	##########################################################################*/
	class showdisplay_Html
	{
		// Defining function to show the cart details
		function showdisplay()
		{
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,
					$Captions_arr,$inlineSiteComponents,$sitesel_curr,$default_Currency_arr,$ecom_testing,$ecom_themename,$components,$loginUrl,$ecom_fb_enable,
					$ecom_common_settings;
					$cartData = cartCalc(); // Calling the function to calculate the details related to cart
					$session_id = Get_session_Id_from();

					if (count($cartData['products'])==0) // Done to show the message if no products in cart
					  {
									   echo '
											<script type="text/javascript">
											window.location = "http://'.$ecom_hostname.'/cart.html";
											</script>';
										exit;
					  }
					    if($cartData['payment']['type']=='4min_finance')
						{
							$saved_deposit = 0;
							$sql_crt = "SELECT finance_id,finance_deposit FROM cart_supportdetails WHERE sites_site_id = $ecom_siteid AND session_id = '".$session_id."' LIMIT 1";			
							$ret_crt = $db->query($sql_crt);
							if($db->num_rows($ret_crt))
							{
							$row_crt = $db->fetch_array($ret_crt);
							$saved_finid 	= $row_crt['finance_id'];
							$saved_deposit	= $row_crt['finance_deposit'];
							//$fndeposit = $saved_deposit;
							}
							$tenper_deposit = round($cartData["totals"]["bonus_price"]*(10/100),2);
							$fiftyper_deposit = round($cartData["totals"]["bonus_price"]*(50/100),2);
							$sql_getc = "SELECT finance_id,finance_name FROM finance_details WHERE sites_site_id = $ecom_siteid and  finance_id=$saved_finid LIMIT 1";
							$ret_getc = $db->query($sql_getc);
							if($db->num_rows($ret_getc))
							{
								$row_getc = $db->fetch_array($ret_getc);
								$def_fincode = $row_getc['finance_id'];
								$fname_checkout = $row_getc['finance_name'];
							}
						}
						if ($saved_deposit<$tenper_deposit || $saved_deposit>$fiftyper_deposit)
						{
								$msg =  $Captions_arr['CART']['CART_FINANCE_DEP'];
								echo "<form method='post' action='http://$ecom_hostname/cart.html' id='cart_invalid_form' name='cart_invalid_form'><input type='hidden' name='hold_section' value='".$msg."' size='1'/><div style='position:absolute; left:0;top:0;padding:5px;background-color:#CC0000;color:#FFFFFF;font-size:12px;font-weight:bold'>Loading...</div></form><script type='text/javascript'>document.cart_invalid_form.submit();</script>";
								exit;
						}	
					  ?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="">
<?php
					if($cart_alert and count($cartData['products'])>0)
				{
			?>
					<tr>
						<td  align="center" valign="middle" class="red_msg">
						- <?php echo $cart_alert?> -
						</td>
					</tr>
			<?php
				}
				
				?>
			  <tr>
				<td  align="left" valign="middle"><div class="treemenu"><a href="http://<?php echo $ecom_hostname?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> Shopping Cart</div></td>
			  </tr>
					</table>
					
					<div class="cartcontentsWrap_inner">
						
<?php
     $this->linked_products();
?>
<div class="cartouter">
	<div class="" id="intercart_ajax_container">

	<?php 
	  $this->show_cartdetails('',$fname_checkout);
	  ?>
	  </div>
	  <?php
	  $this->show_reviews();
	  /*
	  // Handling the case of login required before checkout.
				$show_checkoutbutton = true;
				if($Settings_arr['forcecustomer_login_checkout'] and !$cust_id)
				{
						$show_checkoutbutton = false;
				}
						    $on_changeA = "onclick=\" handle_intercart_submit('$ecom_hostname','intercart','".$cartData["totals"]["bonus_price"]."');\"";

	?>
	<div class="cart_checkout_div1"  align='right'>
             		 <input name="continue_checkout" type="button" class="buttonred_cart" id="continue_checkout" value="<?php echo $Captions_arr['CART']['CART_GO_CHKOUT']?>" <?php echo $on_changeA?> />
             		<?php /*<a href="#" onclick="handle_checkout_submit('<?php echo $ecom_hostname?>',0,'<?php echo $cartData["totals"]["bonus_price"]?>')"><img src="<?php url_site_image('credit_debit_card_new.jpg')?>" alt="Checkout"  border="0" style="cursor:hand"></a>*//*?>
             		</div> 
             		*/?> 
</div>

					</div>
<tr>
            <td  align="right" valign="middle" class="shoppingcartcontent">			
            <div class="cart_continue_div" align="left">
	             <input name="backto_cart" type="button" class="buttonred_cart_continue_small" id="backto_cart" value="<?php echo $Captions_arr['CART']['CART_BACK_CARTPAGE']?>" onclick="show_wait_button(this,'Please Wait...');gobackto_cart('<?php echo ($protectedUrl)? base64_encode($ecom_hostname):$ecom_hostname?>')"/>
	         </div>    
             <?php
			 // Handling the case of login required before checkout.
				$show_checkoutbutton = true;
				$checkout_caption	= 'Continue Checkout';

				if($Settings_arr['forcecustomer_login_checkout'] and !$cust_id)
				{
					$show_checkoutbutton = false;
				}
										    $on_changeA = "onclick=\" handle_intercart_submit('$ecom_hostname','intercart','".$cartData["totals"]["bonus_price"]."');\"";

				if($show_checkoutbutton==true)	
				{
			  ?>	
			  		<div class="cart_checkout_div" align="right">
					             		 <input name="continue_checkout" type="button" class="buttonred_cart" id="continue_checkout" value="<?php echo $checkout_caption?>" <?php echo $on_changeA?> />

					</div>
			  <?php
			  	}
				else
				{
						echo "<div class='cart_checkout_div' align='right'><span class='red_msg'>".$Captions_arr['CART']['CART_LOGIN_CHECKOUT']."</span></div>";
				}
			  ?>
				 <input type="hidden" name="pass_url" id="pass_url" value="<?php echo $_REQUEST['pass_url']?>"/>
				 <div id='checkout_msg_div' align="center" style="width:100%; display:none">
					<span class="red_msg">
					<?php echo $Captions_arr['CART']['CART_PLEASE_WAIT']?>
					</span>
				</div>
			</td>
          </tr>

					<?php
		}
		function array_random($prod_cart=array(),$cnt=5)
		{ 
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,
					$Captions_arr,$inlineSiteComponents,$sitesel_curr,$default_Currency_arr,$ecom_testing,$ecom_themename,$components,$loginUrl,$ecom_fb_enable,
					$ecom_common_settings;
					$prod_cart_new = array();
					if(is_array($prod_cart))
					{
					$rand_keys = array_rand($prod_cart,$cnt);
					foreach($rand_keys as $k=>$v)
					{
					     $prod_cart_new[] = $prod_cart[$v];
					}
				    }
				    return $prod_cart_new;
		}
		function linked_products()
		{
		global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,
		$Captions_arr,$inlineSiteComponents,$sitesel_curr,$default_Currency_arr,$ecom_testing,$ecom_themename,$components,$loginUrl,$ecom_fb_enable,
		$ecom_common_settings;
		$session_id 			= session_id();	// Get the session id for the current section
		$cust_id				= get_session_var("ecom_login_customer"); // Get the customer id from session
		$Captions_arr['CART'] 	= getCaptions('CART'); // Getting the captions to be used in this page
		
		$cartData = cartCalc(); // Calling the function to calculate the details related to cart
		$prod_cart_arr = array();
		$prod_cart = array();
		?>
		<div class="customerboughtWrap">
		<div class="customerboughtTop"></div>	
		<div class="customerboughtbg">
		<div class="orderSummaryHead">Customers Also Bought</div>	

		<?php
		// Following section iterate through the products in cart and show its list
		foreach ($cartData['products'] as $products_arr)
		{
		$sql_linked = "SELECT link_product_id FROM product_linkedproducts a,products b WHERE a.link_parent_id =".$products_arr['product_id']." AND a.sites_site_id=$ecom_siteid AND (a.show_in='C' OR a.show_in='CP') AND a.link_parent_id=b.product_id AND b.product_hide='N'";
		$ret_linked =  $db->query($sql_linked);
		while($row_linked =  $db->fetch_array($ret_linked))
		{
		   $prod_cart_arr[] = $row_linked['link_product_id']; 
		}
		}	       $max_cnt = 10;
		   if(is_array($prod_cart_arr))	  
		   {
			   $prod_cart_arr = array_unique($prod_cart_arr);
			   if(count($prod_cart_arr)>=$max_cnt)
			   {
				  $cnt = $max_cnt; 
				}
				else
				{
				  $cnt = count($prod_cart_arr);
				}
				if($cnt>1)
				{
				$prod_cart = $this->array_random($prod_cart_arr,$cnt);
			    }
			    else
			    {
				$prod_cart = $prod_cart_arr;
				}
				$prod_ids = -1;
				if(is_array($prod_cart) and count($prod_cart))
				{
				  $prod_ids = implode(",",$prod_cart);
				}
				  $sql_prod = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
							a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
							a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
							product_total_preorder_allowed,a.product_applytax,a.product_shortdesc,a.product_bonuspoints,
							a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,a.product_variablesaddonprice_exists,
							a.product_variablecomboprice_allowed,a.product_variablecombocommon_image_allowed,a.default_comb_id,
							a.price_normalprefix,a.price_normalsuffix, a.price_fromprefix, a.price_fromsuffix,a.price_specialofferprefix, a.price_specialoffersuffix, 
							a.price_discountprefix, a.price_discountsuffix, a.price_yousaveprefix, a.price_yousavesuffix,a.price_noprice,
							a.product_averagerating,a.product_saleicon_show,a.product_saleicon_text,a.product_newicon_show,a.product_newicon_text,
							a.product_freedelivery         
						FROM 
							products a  
						WHERE 
							a.product_id IN(".$prod_ids.")
							AND a.product_hide = 'N'";
				 $ret_prod = $db->query($sql_prod);
				 while($row_linked = $db->fetch_array($ret_prod))
				 {			
			   ?>
		   <div class="customerboughtinner">
				<div class="proBox">
				<a href="<?php url_product($row_linked['product_id'],$row_linked['product_name'],-1)?>" title="<?php echo stripslashes($row_linked['product_name'])?>" <?php echo getmicrodata_producturl()?> class="product_image" >
														<?php
														$pass_type = 'image_thumbpath';
														// Calling the function to get the image to be shown
														$img_arr = get_imagelist('prod',$row_linked['product_id'],$pass_type,0,0,1);
														if(count($img_arr))
														{
															show_image(url_root_image($img_arr[0][$pass_type],1),$row_linked['product_name'],$row_linked['product_name']);
														}
														else
														{
															// calling the function to get the default image
															$no_img = get_noimage('prod',$pass_type); 
															if ($no_img)
															{
																show_image($no_img,$row_linked['product_name'],$row_linked['product_name']);
															}
														}
														?>							
													</a>
				</div>
				<div class="proBoxDetails">
				<div class="proBoxtitle"><a href="<?php url_product($row_linked['product_id'],$row_linked['product_name'],-1)?>" title="<?php echo stripslashes($row_linked['product_name'])?>"><span <?php echo getmicrodata_productname()?>><?php echo stripslashes($row_linked['product_name'])?></a></div>
		
				<p><?php show_prod_det_more_link(url_product($row_linked['product_id'],$row_linked['product_name'],1),$row_linked['product_shortdesc'])?></p>
				</div>
				<div>
				<?php
				$price_class_arr['ul_class'] 		= 'shelfBul_three_column';
										$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
										$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
										$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
										$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
																							$price_class_arr['link_capt'] 	= 'appr_cls';

										//	echo show_Price($row_prod,$price_class_arr,'shelfcenter_1'); price_categorydetails_1_reqbreak
										echo show_Price($row_linked,$price_class_arr,'cat_detail_1');
										show_excluding_vat_msg($row_linked,'vat_div');// show excluding VAT msg
		
				?>
				</div>
		
		<div class="proBoxAddcart">
		<?php
														$frm_name = uniqid('catdet_');
										?>
														<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_linked['product_id']?>)">
														<input type="hidden" name="fpurpose" value="" />
														<input type="hidden" name="intercart" id="intercart" value="1" />
														<input type="hidden" name="fproduct_id" value="" />
														<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
														<input type="hidden" name="fproduct_url" value="<?php url_product($row_linked['product_id'],$row_linked['product_name'])?>" />
														<div class="prod_list_buy_link">
										<?php			$class_arr['ADD_TO_CART']       = '';
														$class_arr['PREORDER']          = '';
														$class_arr['ENQUIRE']           = '';
														$class_arr['QTY_DIV']           = 'normal_shlfA_pdt_input';
														$class_arr['QTY']               = ' ';
														$class_td['QTY']				= 'prod_list_buy_a';
														$class_td['TXT']				= 'prod_list_buy_b';
														$class_td['BTN']				= 'prod_list_buy_c';
														echo show_addtocart_v5($row_linked,$class_arr,$frm_name,false,'','',true,$class_td);
										?>						</div>
									</form></div>
		</div>
		   <?php
			
		 }
		}
		
		
		?>
		
		</div>
		<div class="customerboughtBottom"></div>
		</div>
		
		<?php
		}			
		function show_cartdetails($alert='',$fname_checkout='')
		{
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents,$Settings_arr,$protectedUrl,$ecom_common_settings;
			global $show_cart_password;
			
			$session_id 			= session_id();	// Get the session id for the current section
			$cust_id				= get_session_var("ecom_login_customer"); // Get the customer id from session
			$Captions_arr['CART'] 	= getCaptions('CART'); // Getting the captions to be used in this page
			
			$cartData = cartCalc(); // Calling the function to calculate the details related to cart
			// Calling function to get the messages to be shown in the cart page
			$cart_alert = get_CartMessages($_REQUEST['hold_section']);
			// Calling function to get the checkout details saved temporarly for current site in current session
			
			
			$saved_checkoutvals = get_CheckoutValues($address);
			// Get the details from cart_supportdetails related to current session and current site
			$sql_cartdet = "SELECT * 
							FROM 
								cart_supportdetails 
							WHERE 
								session_id='".$session_id."'  
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$ret_cartdet = $db->query($sql_cartdet);
			if($db->num_rows($ret_cartdet)) 
				$row_cartdet = $db->fetch_array($ret_cartdet); // Fetch the details to a record set
					?>
						<div class="cartrightmain">
						<div class="summarywraptop"></div>
						<div class="summarywrapbg"><div class="cartrightWrapper">

						<div class="orderSummaryHead">Order Summary</div>
						<table class="orderSummaryTable">
						<tbody><tr>
						<td colspan="2" class="colHead"><?php echo $Captions_arr['CART']['CART_ITEM']?></td>
						<td class="colHead" align="center"><?php echo $Captions_arr['CART']['CART_QTY']?></td>
						<td class="colHead" align="right"><?php echo $Captions_arr['CART']['CART_PRICE']?></td>
						</tr>
						
						<tr><td colspan="4"><div class="smallDivider">&nbsp;</div></td></tr>
<?php
        if (count($cartData['products'])==0) // Done to show the message if no products in cart
		  {
			?>
				<tr>
					<td align="center" valign="middle" class="total" colspan="4">
						<?php echo $Captions_arr['CART']['CART_NO_PRODUCTS']?>
					</td>
				</tr>	
			<?php
		  }
		  else
		  {
		      $prod_cart_arr =array();
		  	  // Following section iterate through the products in cart and show its list
			  foreach ($cartData['products'] as $products_arr)
			  {
				  $prod_cart_arr[] = $products_arr['product_id']; 
				$vars_exists = false;
				if ($products_arr['prod_vars'] or $products_arr['prod_msgs'])  // Check whether variable of messages exists
				{
					$vars_exists 	= true;
					/*$trmainclass		= 'shoppingcartcontent_noborder';
					$tdpriceBclass		= 'shoppingcartpriceB_noborder';
					$tdpriceAclass		= 'shoppingcartpriceA_noborder';
					*/ 
				}
?>						<tr>						
							<td class="image" valign="top">
							<?php
								// Check whether thumb nail is to be shown here
							if ($Settings_arr['thumbnail_in_viewcart']==1)
							{
							?>
							<a href="<?php url_product($products_arr['product_id'],$products_arr['product_name'],-1)?>" title="<?php echo stripslashes($products_arr['product_name'])?>" class="cart_img_link">
							<?php

								// Calling the function to get the image to be shown
								$pass_type = get_default_imagetype('cart');
								//$img_arr = get_imagelist('prod',$products_arr['product_id'],$pass_type,0,0,1);
								if($products_arr['cart_comb_id'] and $products_arr['product_variablecombocommon_image_allowed']=='Y')
									$img_arr = 	get_imagelist_combination($products_arr['cart_comb_id'],$pass_type,0,1);
								else
									$img_arr = get_imagelist('prod',$products_arr['product_id'],$pass_type,0,0,1);
								if(count($img_arr))
								{
									show_image(url_root_image($img_arr[0][$pass_type],1),$products_arr['product_name'],$products_arr['product_name']);
								}
								else
								{
									// calling the function to get the default image
									$no_img = get_noimage('prod',$pass_type); 
									if ($no_img)
									{
										show_image($no_img,$products_arr['product_name'],$products_arr['product_name']);
									}	
								}	
							?>
							</a>
							<?php
							}
							?>
							</td>
						     <td class="desc" valign="top">
							<a href="<?php echo url_product($products_arr['product_id'],$products_arr['product_name'])?>" title="<?php echo stripslashes($products_arr['product_name'])?>" class="newCartTitle"><?php echo stripslashes($products_arr['product_name'])?></a>

						</td>
						<td align="center" valign="top" class="qty"><?php 
					if($products_arr['product_det_qty_type']=='DROP')
					{
						if (trim($products_arr['product_det_qty_drop_prefix'])!='')
							echo stripslashes($products_arr['product_det_qty_drop_prefix']).' ';
						echo $products_arr['cart_qty'];
						if (trim($products_arr['product_det_qty_drop_suffix'])!='')
							echo ' '.stripslashes($products_arr['product_det_qty_drop_suffix']);
					}
					else
						echo $products_arr["cart_qty"]?></td>
						<td class="price" valign="top"><?php echo print_price($products_arr['final_price'],true)?></td>
						</tr>                       
						<tr><td colspan="4">
					<?php
						// If variables exists for current product, show it in the following section
					if ($vars_exists) 
					{
				  ?>
					
						<?
						// show the variables for the product if any
						if ($products_arr['prod_vars']) 
						{
							echo "<div class='rightVariables'>";
							//print_r($products_arr['prod_vars']);
							foreach($products_arr["prod_vars"] as $productVars)
							{
								if (trim($productVars['var_value'])!='')
									print "<span class='cartvariable'>".$productVars['var_name'].": ". $productVars['var_value']."</span><br />"; 
								else
									print "<span class='cartvariable'>".$productVars['var_name']."</span><br />"; 
									
							}	
							echo "</div>";
						}
						// Show the product messages if any
						if ($products_arr['prod_msgs']) 
						{
							echo "<div class='rightVariables'>";	
							foreach($products_arr["prod_msgs"] as $productMsgs)
							{
								print "<span class='cartvariable'>".$productMsgs['message_title'].": ". $productMsgs['message_value']."</span><br />"; 
							}	
							echo "</div>";
						}	
					
				}
				?>	<div class="divider">&nbsp;</div></td></tr>
			   <?php				
						
               }
               ?>
               <tr>
						<td colspan="4">
						<table cellpadding="2" cellspacing="2" class="summaryTotals">						   
							<tbody>
							  <tr>
								<td  class="totalsHead"><?php echo $Captions_arr['CART']['CART_TOTPRICE']?></td>
								<td align="right" class="total"><?php echo print_price($cartData["totals"]["subtotal"],true)?></td>
							</tr>
							<?php
              
			// Calling the function to decide the delivery details display section 
			$deliverydet_Arr = get_Delivery_Display_Details();
			if ($deliverydet_Arr['delivery_type'] != 'None') // Check whether any delivery method is selected for the site
			{
				 	// Case if location is to be displayed
					if ($row_cartdet['location_id'])
					{
						// Get the name of location from the current site for current delivery method
						$sql_loc = "SELECT location_name 
										FROM 
											delivery_site_location 
										WHERE 
											delivery_methods_deliverymethod_id =".$deliverydet_Arr['delivery_id']." 
											AND sites_site_id = $ecom_siteid 
											AND location_id = ".$row_cartdet['location_id']. "
										LIMIT 
											1";
						$ret_loc = $db->query($sql_loc);
						if ($db->num_rows($ret_loc))
						{
							$row_loc = $db->fetch_array($ret_loc);					
				?>	
							<tr>
								<td  align="right" valign="middle" class="totalsHead"><?php echo $Captions_arr['CART']['CART_DELIVERY_LOC']?></td>
							<td align="right" class="total"> <?php echo stripslashes($row_loc['location_name'])?></td>
							</tr>
				<?php
						}
					}						
				if ($row_cartdet['split_delivery']==1)
				{
				?>
					<tr>
						<td  align="left" valign="middle" class="totalsHead"><?php echo $Captions_arr['CART']['CART_DELIVERY_SPLIT_ACTIVATED']?></td>
						<td  align="left" valign="middle" class="total"><?php echo $Captions_arr['CART']['CART_YES']?></td>
					</tr>
				<?php	
				}
				// Section to handle the case of split orders selected 
				if($cartData["totals"]["number_deliveries"] > 1)
				{ 
					for($i = 1; $i <= $cartData["totals"]["number_deliveries"]; $i++)
					{
						$ddate 		= explode('-',$cartData["delivery"]["group".$i]["date"]);
						$show_date	= date('d-M-Y',mktime(0,0,0,$ddate[1],$ddate[2],$ddate[0]));
					?>
						<tr>
							<td class="totalsHead"  align="left">
							<? 
							  	print $cartData["delivery"]["group".$i]["items"]."Delivery Charge for";
								if($cartData["delivery"]["group".$i]["items"] > 1)
								{ 
									print " Items "; 
								}
								else
								{ 
									print " Item ";
								}
								print "to be dispatched on " . $show_date ?>
							</td>
							<td class="total"  align="left">
							<? print print_price($cartData["delivery"]["group".$i]["cost"],true)?>
							</td>
						</tr>
					<?
					}				
				} 
				// Check whether delivery is charged, then show the total after applying delivery charge
				if($cartData["totals"]["delivery"])
				{
				?>
				  <tr>
					<td  align="right" valign="middle" class="totalsHead"><?php echo $Captions_arr['CART']['CART_TOTAL_DELIVERY_CHARGES']?></td>
					<td align="right" valign="middle" class="total"><?php echo print_price($cartData["totals"]["delivery"],true)?></td>
				  </tr>
		  <?php
		  		}
		  	}
		  	// Section to show the extra shipping cost
			 if($cartData["totals"]["extraShipping"])
			 {
			 ?>
				 <tr>
					<td  align="right" valign="middle" class="totalsHead"><?php echo $Captions_arr['CART']['CART_EXTRA_SHIPPING']?></td>
					<td align="right" valign="middle" class="total"><?php echo print_price($cartData["totals"]["extraShipping"],true)?></td>
				  </tr>
			 <?php	
			 }
			
			 // Section to show the tax details
			 if($cartData["totals"]["tax"])
			 {
			 ?>
					<tr>
						<td  align="right" valign="top" class="totalsHead">
						<?php echo $Captions_arr['CART']['CART_TAX_CHARGE_APPLIED']?>
						<?	
							foreach($cartData["tax"] as $tax)
							{
								echo '<br/>('.$tax['tax_name']; ?> @ <? print $tax['tax_val']; ?>%)
						<?	
							}
						?>						</td>
						<td align="right" valign="top" class="total">
						<?	
							echo print_price($cartData["totals"]["tax"],true);
						?>						</td>
					</tr>
				<?php
			}
				
				// If gift voucher or promotional code is valid
				if (($row_cartdet['promotionalcode_id']!=0 or $row_cartdet['voucher_id']!=0))
				{
				 ?>
				  <tr>
					<td  align="right" valign="middle" class="totalsHead">
					<?php 
					if ($row_cartdet['promotionalcode_id']!=0 and $cartData["bonus"]['type']=='promotional' and $cartData['totals']['pro_type']!='product')
					{
					?>
					<?php echo $Captions_arr['CART']['CART_PROMOTIONAL_CODE_DISC_APPLIED']?>
					
					<?php
					}
					elseif($row_cartdet['voucher_id']!=0 and $cartData["bonus"]['type']=='voucher')
					{
					?>
					<?php echo $Captions_arr['CART']['CART_GIFTVOUCHER_DISC_APPLIED']?>
					<?php
					}
					?>
					</td>
					<td align="right" valign="middle" class="total">
					<?php 
					if ($row_cartdet['promotionalcode_id']!=0 and $cartData["bonus"]['type']=='promotional' and $cartData['totals']['pro_type']!='product')
						echo print_price($cartData['totals']['lessval'],true);
					elseif($row_cartdet['voucher_id']!=0 and $cartData["bonus"]['type']=='voucher')
						echo print_price($cartData['totals']['lessval'],true);
					?></td>
				  </tr>
		  <?php
		  		}
		  		
			// Check whether the bonus points module is available in the site
			if (is_Feature_exists('mod_bonuspoints'))
			{
				if ($cartData["customer"]["customer_bonus"]) // does current customer have bonus points
				{
				  	// Show the following only if any bonus point is spend
					if ($cartData["bonus"]["value"])
					{
					?>
					  <tr>
						<td  align="right" valign="middle" class="totalsHead"><?php echo $Captions_arr['CART']['CART_SPENT_BONUS_POINTS_VALUE']?> </td>
						<td align="right" valign="middle" class="total"><? echo '(-) '.print_price($cartData["bonus"]["value"],true);?></td>
					  </tr>
			<?php	
					}
				}
			}
			/* Donate bonus Start */
			// Bonus Points Donated
			if ($cartData["bonus"]["donating"]>0 and $cust_id)
			{
			?>
				 <tr>
					<td  align="left" valign="middle" class="totalsHead"><?php echo $Captions_arr['CART']['CART_BONUS_DONATING']?>&nbsp;</td>
					<td  align="left" valign="middle" class="total"><?php echo $cartData["bonus"]["donating"]?></td>
				  </tr>
			<?php	
			}
			/* Donate bonus End */
			// Bonus Points Earned
				if ($cartData["totals"]["bonus"]>0 and $cust_id)
				{
			?>
				 <tr>
					<td  align="right" valign="middle" class="totalsHead"><?php echo $Captions_arr['CART']['CART_TOTAL_POINTS_EARN']?>&nbsp;</td>
					<td colspan="1" align="right" valign="middle" class="total"><?php echo $cartData["totals"]["bonus"]?></td>
				  </tr>
			<?php	
			}
			?>
					
			  <?php
			  
			  	
		  	// show the total final price
			if($cartData["totals"]["bonus_price"]>0)
			{
			?>
			 
			  <tr>
				<td  align="right" valign="middle" class="grandTotalHead"><?php echo $Captions_arr['CART']['CART_TOTAL_FINAL_COST']?>&nbsp; </td>
				<td align="right" valign="middle" class="grandTotal"><?php echo print_price($cartData["totals"]["bonus_price"],true)?></td>
			  </tr>
		  <?php
		  	}
			$rem_val = $cartData["totals"]["bonus_price"] - $str_reduceval;
			if($rem_val >0 and $str_reduceval>0)
			{
			?>
				<tr>
					<td  align="right" valign="middle" class="totalsHead"><?php echo $Captions_arr['CART']['CART_LESS_AMT_LATER']?>&nbsp; </td>
					<td align="right" valign="middle" class="total"><?php echo print_price($str_reduceval,true)?></td>
			  	</tr>
				<tr>
					<td  align="right" valign="middle" class="grandTotalHead"><?php echo $Captions_arr['CART']['CART_TOTAL_AMT_NOW']?>&nbsp; </td>
					<td align="right" valign="middle" class="grandTotal"><?php echo print_price($rem_val,true)?>
					<input type="hidden" name="store_reduceval" value="<?php echo $str_reduceval?>" />
					</td>
			  	</tr>
			<?php
			}		
		  	
		  ?>	
		  <tr>
				<td  align="right" valign="middle" colspan="2">
			    <table cellspacing="0" cellpadding="0" class="ckeckout_right" width="100%">	
			    <tr>
				<td  align="left">&nbsp;</td>
				</tr>
			<?php
			if ($deliverydet_Arr['delivery_type'] != 'None') // Check whether any delivery method is selected for the site
			{
// Check whether any delivery group exists for the site
					if ($row_cartdet['delopt_det_id'])
					{
						// Get the name of location from the current site for current delivery method
						$sql_grp = "SELECT delivery_group_name 
										FROM 
											general_settings_site_delivery_group 
										WHERE 
										sites_site_id = $ecom_siteid 
											AND delivery_group_id = ".$row_cartdet['delopt_det_id']. "
										LIMIT 
											1";

						$ret_grp = $db->query($sql_grp);
						if ($db->num_rows($ret_grp))
						{
							$row_grp = $db->fetch_array($ret_grp);					
				?>	
							<tr>
								<td  align="left" valign="middle" class="totalsHead"><?php echo $Captions_arr['CART']['CART_DELIVERY_OPT']?>&nbsp;:&nbsp; <?php echo stripslashes($row_grp['delivery_group_name'])?></td>
							</tr>
				<?php
						}
					}
				}
				?>
				<tr>
				<td  align="left" valign="middle" class="totalsHead"  >		
					<?php echo $Captions_arr['CART']['CART_PAYMENT_TYPE']?>&nbsp;:&nbsp;
				
				<?php 
					// Get the name of payment type to be shown to the user
					/*$sql_paytype = "SELECT paytype_name 
										FROM 
											payment_types 
										WHERE 
											paytype_code ='".$row_ord['order_paymenttype']."' 
										LIMIT 
											1";
					$ret_paytype = $db->query($sql_paytype);
					if ($db->num_rows($ret_paytype))
					{
						$row_paytype 	= $db->fetch_array($ret_paytype);
						$paytype_name	= stripslashes($row_paytype['paytype_name']);
					}
					else
						$paytype_name	= $row_ord['order_paymenttype'];
						echo $paytype_name;*/
					echo getpaymenttype_Name($cartData['payment']['type']);
				?>
				</td>
			  </tr>
			  <?php
			  	if($cartData['payment']['type'] =='credit_card')
				{
			  ?>
				  <tr>
					<td  align="left" valign="middle" class="totalsHead" >			
						<?php echo $Captions_arr['CART']['CART_GATEWAY_USED']?>&nbsp;:&nbsp;
							
					<?php 
					// Get the name of payment method to be shown to the user
					/*$sql_paymethod = "SELECT paymethod_name 
										FROM 
											payment_methods  
										WHERE 
											paymethod_key ='".$row_ord['order_paymentmethod']."' 
										LIMIT 
											1";
					$ret_paymethod = $db->query($sql_paymethod);
					if ($db->num_rows($ret_paymethod))
					{
						$row_paymethod 	= $db->fetch_array($ret_paymethod);
						$paymethod_name	= stripslashes($row_paymethod['paymethod_name']);
					}
					else
						$paymethod_name	= $row_ord['order_paymentmethod'];
						echo $paymethod_name;*/
					echo getpaymentmethod_Name($cartData['payment']['method']['paymethod_key']);
					?>
					</td>
				  </tr>
			  <?php
			  	}
			  	if($fname_checkout!='')
			  	{
					?>
				   <tr>
					<td  align="left" valign="middle" class="totalsHead" >			
						<?php echo $Captions_arr['CART']['CART_FINANCE_OPT']?>
					&nbsp;:&nbsp;		
					<?php 
					// Get the name of payment method to be shown to the user
					/*$sql_paymethod = "SELECT paymethod_name 
										FROM 
											payment_methods  
										WHERE 
											paymethod_key ='".$row_ord['order_paymentmethod']."' 
										LIMIT 
											1";
					$ret_paymethod = $db->query($sql_paymethod);
					if ($db->num_rows($ret_paymethod))
					{
						$row_paymethod 	= $db->fetch_array($ret_paymethod);
						$paymethod_name	= stripslashes($row_paymethod['paymethod_name']);
					}
					else
						$paymethod_name	= $row_ord['order_paymentmethod'];
						echo $paymethod_name;*/
					echo $fname_checkout;
					?>
					</td>
				  </tr>
				  <?php
				}	
				if($row_cartdet['disable_id'])
			 {
				$sql_disb = "SELECT type FROM disability_type WHERE id = ".$row_cartdet['disable_id']." LIMIT 1";
				$ret_disb = $db->query($sql_disb);
				if($db->num_rows($ret_disb))
				{
					$row_disb = $db->fetch_array($ret_disb);
			 ?>
					<tr>
						<td  align="left" valign="middle" class="totalsHead"><?php echo $Captions_arr['CART']['CART_DISABILITY_CAPTION']?>&nbsp;:&nbsp; <?php echo stripslashes($row_disb['type'])?></td>
					</tr>
			<?php
				}	
			 }	
			   	
		  ?>
		  </table></td></tr>
		  </table> </td>
						</tr>
						<?php
					}
						?>
						</tbody></table>
						</div></div>
						<div class="summarywrapbottom"></div>
						</div>
					<?php
		}
		function show_reviews()
		{
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,
					$Captions_arr,$inlineSiteComponents,$sitesel_curr,$default_Currency_arr,$ecom_testing,$ecom_themename,$components,$loginUrl,$ecom_fb_enable,
					$ecom_common_settings;
					$session_id 			= session_id();	// Get the session id for the current section
					$cust_id				= get_session_var("ecom_login_customer"); // Get the customer id from session
					$Captions_arr['CART'] 	= getCaptions('CART'); // Getting the captions to be used in this page
		
					$cartData = cartCalc(); // Calling the function to calculate the details related to cart
					$rev_cart_arr = array();
					$rev_cart = array();
					// Following section iterate through the products in cart and show its list
					foreach ($cartData['products'] as $products_arr)
					{
					        $sql_rew   = "SELECT review_id 
						   								FROM 
														product_reviews 
														WHERE products_product_id=".$products_arr['product_id']." 
														AND sites_site_id=".$ecom_siteid." AND review_hide=0 
														AND review_status='APPROVED' ";
							$ret_rew = $db->query($sql_rew);
							while($row_rew = $db->fetch_array($ret_rew))
							{
							   $rev_cart_arr[] = $row_rew['review_id'];
							}
														
					}
					$max_cnt = 10;
					if(is_array($rev_cart_arr))	  
					{
					   $rev_cart_arr = array_unique($rev_cart_arr);
					   if(count($rev_cart_arr)>=$max_cnt)
					   {
						  $cnt = $max_cnt; 
						}
						else
						{
						  $cnt = count($rev_cart_arr);
						}
						$rev_cart = $this->array_random($rev_cart_arr,$cnt);
						$rev_ids = -1;
						if(is_array($rev_cart) and count($rev_cart))
						{
						  $rev_ids = implode(",",$rev_cart);
						}
						$sql_reviews = "SELECT * FROM product_reviews WHERE review_id IN(".$rev_ids.") AND review_hide=0 AND sites_site_id=$ecom_siteid";
						$ret_reviews = $db->query($sql_reviews); 
						if($db->num_rows($ret_reviews)>0)
						{
						?>
							<div class="cartrightmain">
							<div class="summarywraptop"></div>
							<div class="summarywrapbg">
							<div class="cartrightWrapper">
							<div class="orderSummaryHead">Customer Service Reviews</div>
							<?php
							while($row_reviews = $db->fetch_array($ret_reviews))
							{
							?>
								<div class="testimonywrap">
								<div class="starBlock">
								<div class="ratingStars">
								<?php 
								$HTML_rating = display_rating($row_reviews['review_rating'],1,'star-green.gif','star-white.gif',$row_reviews['products_product_id']);
								echo $HTML_rating; 
								?>
								</div>
								</div>
								<?php echo $row_reviews['review_details']?><div class="testimonyname"><?php echo $row_reviews['review_author'];  ?></div>
								</div>
							<?php
							}
							?>
							<div class="summarywrapbottom"></div>
							</div>
							</div>
							</div>
						<?php
					  }
					}
					?>
										
					
					
					
					<?php
		}						 
	};	
	
?>
