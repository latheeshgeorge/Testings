<?php
	/*############################################################################
	# Script Name 		: cartHtml.php
	# Description 		: Page which holds the display logic for cart and checkout
	# Coded by 			: Sny
	# Created on		: 04-Feb-2008
	# Modified by		: Sny
	# Modified On		: 04-Aug-2008
	##########################################################################*/
	class cart_Html
	{
		// Defining function to show the cart details
		function Show_Cart()
		{
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,
					$Captions_arr,$inlineSiteComponents,$sitesel_curr,$default_Currency_arr,$ecom_testing,$ecom_themename,$components,$loginUrl,$ecom_fb_enable,
					$ecom_common_settings;
			/* FB login script */
			$cust_fbid 				= get_session_var("ecom_login_customer_fbid");
			//echo $cust_fbid;echo "<br>";
			$session_id 			= session_id();	// Get the session id for the current section
			$cust_id				= get_session_var("ecom_login_customer"); // Get the customer id from session
			$Captions_arr['CART'] 	= getCaptions('CART'); // Getting the captions to be used in this page
			$Captions_arr['CUST_LOGIN'] 	= getCaptions('CUST_LOGIN'); // Getting the captions to be used in this page
			$cartData 				= cartCalc(); // Calling the function to calculate the details related to cart
		
			// Calling function to get the messages to be shown in the cart page
			$cart_alert_arr 			= get_CartMessages($_REQUEST['hold_section'],1);
			$cart_alert					= $cart_alert_arr['msg'];
			if($cart_alert_arr['err']==1) // Check whether this cart message is to be displayed in a div or in above cart as normal error message
			{
				$this->show_error_msg($cart_alert);
				$cart_alert = '';
			}
			else
			{
			  if($_REQUEST['hold_section_new']!='')
			  {
			     $this->show_error_msg($_REQUEST['hold_section_new']);
				 $cart_alert = '';
			  }
			}	
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
			
			$pass_fnpaytypeid = ($row_cartdet['paytype_id'])?$row_cartdet['paytype_id']:0;
				
			// Section to decide whether the promotional code popup message is to be displayed
			$prom_alert					= '';
			if ($row_cartdet['promotionalcode_id']!=0 and $cartData["bonus"]['type']=='promotional' and $cartData['totals']['promotional_type']!='product')
			{
				if ($cartData['totals']['lessval']<=0 and $cartData["totals"]["prom_cust_msg"]!='')
				{
					$prom_alert = str_replace('[min_amt]',print_price($cartData["bonus"]['promotion']['code_minimum']),$Captions_arr['CART']['CART_PROM_MIN_REQ_MSG']);
					if($prom_alert!='')
						$this->showpromotional_error_msg($prom_alert);
				}
			}
			
		?>	
		<script type="text/javascript">
		function show_pdf_new(url)
		{
			var winxx = window.open(url,'pdf_window','width=600,height=600,top=100,left=200,location=no',false);
			winxx.focus();
		}	
		</script>
			<div class="widthadjust"></div>
			<form method="post" name="frm_cart"  id="frm_cart" class="frm_cls" action="<?php url_link('cart.html')?>">
			<input type="hidden" name="fpurpose" value="" />
			<input type="hidden" name="remcart_id" value="" />
			<input type="hidden" name="cart_mod" value="show_cart" />
			<input type="hidden" name="hold_section" value="" />
			<input type="hidden" name="ajax_div_holder" id="ajax_div_holder" value="" />
			<input type="hidden" name="dis_msg_disp" value="" id="dis_msg_disp" />
		<?php /* Added for the jquery div    */?>

			 <script type="text/javascript">
				 		jQuery.noConflict();
		var $j = jQuery; 
		$j(function() {
		$j( "#popupwrapA" ).dialog({ autoOpen: false,modal:true,height: 330,
		width: 550,resizable: true,
		dialogClass: 'no-close success-dialog',open: function(event, ui) { $j(".ui-dialog-titlebar").hide(); }});


		$j( "#cart_deliveryoption" )
		.change(function() {
		if($j("#cart_deliveryoption" ).val()==40)
		{
		$j( "#popupwrapA" ).dialog( "open" );
		}
		});
		$j( "#close_pop" ).click(function() {
		$j( "#popupwrapA" ).dialog( "close" );
		});

		$j( "#confirm_prev" ).click(function() {
		if($j("input[name=opt]").is(":checked")){
		$j( "#popupwrapA" ).dialog( "close" );
		}

		});
		});
</script>
		<?php /* End Added for the jquery div    */?>	
		<div id="popupwrapA" style="display:none">
		<div class="closebts"><img id="close_pop" src="<?php url_site_image('close_pop.png')?>" width="22" height="21" onclick="handle_form_submit_popup(document.frm_cart,'save_commondetails','','close')"/></div>
		<div id="popupwrap" >
<p><?php echo $Captions_arr['CART']['CART_POPUP_OPTION'];?></p>

<p class="popupwrap-confirm"><?php echo $Captions_arr['CART']['CART_POPUP_CONFIRM'];?> 
<input type="radio" name="opt"  value="Yes" >Yes&nbsp;
<input type="radio" name="opt" value="No" >No
&nbsp;<input type="button" id="confirm_prev" name="prev_button" value="Confirm" class="confirm_prev" onclick="handle_form_submit_popup(document.frm_cart,'save_commondetails','','confirm')"></p>


</div> 
</div>
			<div class="cartcontentsWrap">
				
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td><table  border="0" cellpadding="0" cellspacing="0" class="tableCartA">
        <tbody>
         <?php
				if($cart_alert and count($cartData['products'])>0)
				{
			?>
					<tr>
						<td colspan="6" align="center" valign="middle" class="red_msg">
						- <?php echo $cart_alert?> -
						</td>
					</tr>
			<?php
				}
				if (count($cartData['products'])>0)
				{
				?>
				<tr>
				  <td colspan="6" align="left" valign="middle" class="cartlogin_msg">
				  <a id="a_deliv" name="a_deliv">&nbsp;</a>

					<?php
					// Check whether logged in 
					if ($cust_id) // Case logged in 
					{
					  echo $Captions_arr['CART']['CART_LOGGED_IN_AS']; echo "&nbsp;".$cartData['customer']['customer_title']." ".$cartData['customer']['customer_fname']." ".$cartData['customer']['customer_mname']." ".$cartData['customer']['customer_surame'];?>. <? echo $Captions_arr['CART']['CART_IF_YOU_NOT_LOG'];?> <a href="http://<?php echo $ecom_hostname?>/logout.html?rets=1" title="Logout" class="cartlogin_link"><? echo $Captions_arr['CART']['CART_HERE'];?></a><? echo "&nbsp;". $Captions_arr['CART']['CART_TO_LOGOUT'];?> 
					<?php
					}
					else // Case not logged in
					{
					?>
						<?php echo $Captions_arr['CART']['CART_NOT_LOGGED_IN']; ?> <a href="<?php url_link('custlogin.html')?>?redirect_back=1&pagetype=cart" title="Login" class="cartlogin_link"><? echo $Captions_arr['CART']['CART_HERE'];?></a><? echo "&nbsp;". $Captions_arr['CART']['CART_TO_LOGIN'];?> 
					<?php
					}
					?>	
					</td>
				</tr>
				<?php
				}
				?>
			  <tr>
				<td colspan="6" align="left" valign="middle"><div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?php echo $Captions_arr['CART']['CART_MAINHEADING']?></div></td>
				
			  </tr>
			  <tr><td colspan="5" align="left"><div class="shipTitleA">Your Shopping Basket</div>
</td><td width="9%" align="left" valign="middle" class="shoppingcartheader">
				<?php
					// Check whether the clear cart button is to be displayed
					if($Settings_arr['empty_cart']==1 and count($cartData['products']))
					{
				?>
				  		<input name="clearcart_button" type="button" class="buttonred_cart_clear" id="clearcart_button" value="<?php echo $Captions_arr['CART']['CART_CLEAR']?>" onclick="if(confirm_message('Are you sure you want to clear all items in the cart?')){show_wait_button(this,'Please Wait...');document.frm_cart.cart_mod.value='clear_cart';document.frm_cart.submit();}" />
			    <?php
				 	}
				 ?>	</td></tr>
          <tr>
            <td colspan="2" align="left" valign="middle" class="shoppingcartheaderA11"><?php echo $Captions_arr['CART']['CART_ITEM']?></td>
            <td width="12%" align="left" valign="middle" class="shoppingcartheaderB11"><?php echo $Captions_arr['CART']['CART_PRICE']?></td>
            <td width="21%" align="left" valign="middle" class="shoppingcartheaderC11"><?php echo $Captions_arr['CART']['CART_DISCOUNT']?></td>
            <td width="10%" align="center" valign="middle" class="shoppingcartheaderD11"><?php echo $Captions_arr['CART']['CART_QTY']?></td>
            <td align="right" valign="middle" class="shoppingcartheaderE11"><?php echo $Captions_arr['CART']['CART_TOTAL']?></td>
          </tr>
           <?php
		  if (count($cartData['products'])==0) // Done to show the message if no products in cart
		  {
			?>
				<tr>
					<td align="center" valign="middle" class="noproductsA" colspan="6">
						<?php echo $Captions_arr['CART']['CART_NO_PRODUCTS']?>					</td>
				</tr>	
			<?php
		  }
		  else
		  {
		// Following section iterate through the products in cart and show its list
			  foreach ($cartData['products'] as $products_arr)
			  {
			  	$spunique_id = 0;
				$sp_caption = '';
				
				// get the default product category for this product
				$sql_def_cat = "SELECT product_default_category_id FROM products WHERE product_id = ".$products_arr['product_id']." LIMIT 1";
				$ret_def_cat = $db->query($sql_def_cat);
				if($db->num_rows($ret_def_cat))
				{
					$row_def_cat = $db->fetch_array($ret_def_cat);
					$tc_default_cat_id = $row_def_cat['product_default_category_id'];
				}
				
				//echo "cid".$products_arr['cart_comb_id'];
				//if($_SERVER['REMOTE_ADDR']=='182.72.159.170')
				//	print_r($products_arr);
							  
				$vars_exists = false;
				
				if ($products_arr['prod_vars'] or $products_arr['prod_msgs'] or $sp_caption!='')  // Check whether variable of messages exists
				{
					$vars_exists 	= true;
					/*$trmainclass		= 'shoppingcartcontent_noborder';
					$trmainclassB 		= 'shoppingcartcontentB_noborder';	

					$tdpriceBclass		= 'shoppingcartpriceB_noborder';
					$tdpriceAclass		= 'shoppingcartpriceA_noborder';
					$tdpriceCclass      = 'shoppingcartpriceC_noborder';
					$tdpriceDclass      = 'shoppingcartpriceD_noborder';
					*/ 

				}
				//else
				{
					$trmainclass 		= 'shoppingcartcontentA';
					$trmainclassB 		= 'shoppingcartcontentB';	
	
					$tdpriceBclass		= 'shoppingcartpriceB';
					$tdpriceAclass		= 'shoppingcartpriceA';
					$tdpriceCclass      = 'shoppingcartpriceC';
					$tdpriceDclass      = 'shoppingcartpriceD';
				}	
				if($products_arr['product_deposit'])
				{
					if ($products_arr['final_price']>$products_arr['product_deposit_less'])
						$str_reduceval	+= $products_arr['product_deposit_less'];
				}
				
				
			  ?>
				  <tr>
					<td align="left" valign="top" class="<?php echo $trmainclass?>">
					<?php 
					// Check whether thumb nail is to be shown here
					if ($Settings_arr['thumbnail_in_viewcart']==1)
					{
					?>
						<a href="<?php url_product($products_arr['product_id'],$products_arr['product_name'],-1)?>" title="<?php echo stripslashes($products_arr['product_name'])?>" class="cart_img_link">
						<?php
							// Calling the function to get the image to be shown
							$pass_type = get_default_imagetype('cart');
							//$pass_type = 'image_thumbpath';
							//$img_arr = get_imagelist('prod',$products_arr['product_id'],$pass_type,0,0,1);
							if($products_arr['cart_comb_id'] and $products_arr['product_variablecombocommon_image_allowed']=='Y')
								$img_arr = 	get_imagelist_combination($products_arr['cart_comb_id'],$pass_type,0,1);
							else
								$img_arr = get_imagelist('prod',$products_arr['product_id'],$pass_type,0,0,1);
							if(count($img_arr))
							{
								show_image(url_root_image($img_arr[0][$pass_type],1),$products_arr['product_name'],$products_arr['product_name'],'cart_img_cls');
							}
							else
							{
								// calling the function to get the default image
								$no_img = get_noimage('prod',$pass_type); 
								if ($no_img)
								{
									show_image($no_img,$products_arr['product_name'],$products_arr['product_name'],'cart_img_cls');
								}	
							}	
						?>
						</a>
					<?php
					}
					?>
					</td>
					<td width="41%" align="left" valign="top" class="shoppingcartcontentA">
					<a href="<?php echo url_product($products_arr['product_id'],$products_arr['product_name'])?>" title="<?php echo $products_arr['product_name']?>" class="shopLink"><?php echo stripslashes($products_arr['product_name'])?></a>					
					<?php
					// If variables exists for current product, show it in the following section
					if ($vars_exists or $sp_caption!='') 
					{						
						// show the variables for the product if any
						if ($products_arr['prod_vars']) 
						{
							echo "<div >";
							foreach($products_arr["prod_vars"] as $productVars)
							{
								if (trim($productVars['var_value'])!='')
								{
									$tc_vurval = tc_remove_spaces ($productVars['var_value']);
									//echo $tc_default_cat_id." - default cat id ";
									$tc_return = tc_checkbox_check($tc_vurval,$tc_default_cat_id);
									if($tc_return!='')
									{
										$sql_ckeck_term = "SELECT terms_req FROM cart WHERE cart_id=".$products_arr['cart_id']." AND sites_site_id=".$ecom_siteid." LIMIT 1";
										$ret_check_term = $db->query($sql_ckeck_term);
										$selected = "";
										if($db->num_rows($ret_check_term)>0)
										{
											$row_chk_term = $db->fetch_array($ret_check_term);
											if($row_chk_term['terms_req']==1)
											{
											   $selected = "checked";
											}
									    }
										
										print "<span class='cartvariable'>".$productVars['var_name'].": ". $productVars['var_value']."</span><br />";
										
										//<span class='cartvariable_checkbox'><input type='checkbox' name='tccheckbox_".$products_arr['cart_id']."' value='".$products_arr['cart_id']."' ".$selected."><a href='javascript:show_pdf_new(\"".$tc_return."\")'>".$Captions_arr['CART']['CART_TC_CHECKBOX_CAPTION']."</a></span><br />";
										 
									}
									else
									{
										print "<span class='cartvariable'>".$productVars['var_name'].": ". $productVars['var_value']."</span><br />"; 
									}	
								}	
								else
									print "<span class='cartvariable'>".$productVars['var_name']."</span><br />"; 
									
							}	
							echo "</div>"; 
						}
						// Show the product messages if any
						if ($products_arr['prod_msgs']) 
						{	
							echo "<div style='float:left'>";
							foreach($products_arr["prod_msgs"] as $productMsgs)
							{
								print "<span class='cartvariable'>".$productMsgs['message_title'].": ". $productMsgs['message_value']."</span><br />"; 
							}	
						}	
						if ($sp_caption!='')
							print "<span class='cartvariable'>".$sp_caption."</span><br />";
						echo "</div>";	
					?>						
			  <?php
				}
				$preorder = check_Inpreorder($products_arr['product_id']);
								if ($preorder['in_preorder']=='Y')
									echo '<div class="nostock">Available in Stock on'.' '.$preorder['in_date'].'</div>';
				?>
					</td>
					
					<td align="left" valign="top" class="<?php echo $tdpriceBclass?>"><?php if($this->checkprice_display($pass_fnpaytypeid)) { echo print_price($products_arr['product_webprice']);}?></td>
					<td align="left" valign="top" class="<?php echo $tdpriceCclass?>">
					-<?php 
							// if combo disc exists then that should be displayed otherwise the normal or promotional disc is to be shown
							/*if ($products_arr["prom_prodcode_disc"] and $products_arr["savings"]["product"]) // Case if promotional code disc is there for current product
								echo $Captions_arr['CART']['CART_PROM_DISC'].' '.print_price($products_arr["savings"]["product"],true);
							elseif ($products_arr["cust_disc_type"] !='' and $products_arr["savings"]["product"]) // Case if promotional code disc is there for current product
							{	
								switch($products_arr['cust_disc_type'])
								{
									case 'custgroup':
										echo $Captions_arr['CART']['CART_GROUP_DISC'];
									break;
									case 'customer':
										echo $Captions_arr['CART']['CART_CUST_DISC'];
									break;
								};
								echo ' <br/>'.print_price($products_arr["savings"]["product"],true);
							}
							elseif($products_arr['savings']['product_combo'] or $products_arr['userin_combo']) // Check whether combo discount is there
							{
								echo $Captions_arr['CART']['CART_COMBO_DISC'].'<br/> '.print_price($products_arr["savings"]["product_combo"],true);
							}
							elseif($products_arr["savings"]["bulk"]) // Check whether bulk discount is there
							{
								echo $Captions_arr['CART']['CART_BULK_DISC'].' <br/>'.print_price($products_arr["savings"]["bulk"],true);
							}
							else
								echo print_price($products_arr["savings"]["product"],true);*/
								 if($this->checkprice_display($pass_fnpaytypeid)) {
								display_cart_discount($products_arr,$Captions_arr);
								}	
					?>
					</td>
					<td align="center" valign="top" class="<?php echo $trmainclassB?>">
					<?php
						if($products_arr['product_det_qty_type']=='DROP')
						{
							if (trim($products_arr['product_det_qty_drop_prefix'])!='')
								echo stripslashes($products_arr['product_det_qty_drop_prefix']).' ';
							echo $products_arr['cart_qty'];
							if (trim($products_arr['product_det_qty_drop_suffix'])!='')
								echo ' '.stripslashes($products_arr['product_det_qty_drop_suffix']);
					?>	
							
							<input type="hidden" name="cart_qty_<?php echo $products_arr['cart_id']?>" id="cart_qty_<?php echo $products_arr['cart_id']?>" value="<?php echo $products_arr["cart_qty"]?>" />
							<div class="updatediv" align="center"><a href="#" class="update_link" onclick="if (confirm_message('<?php echo $Captions_arr['CART']['CART_ITEM_REM_MSG']?>')) {document.frm_cart.remcart_id.value='<?php echo $products_arr['cart_id']?>';handle_form_submit(document.frm_cart,'Remove_qty','#rem')}" title="<?php echo $Captions_arr['CART']['CART_REMOVE']?>"></a></div>
					<?php	
						}
						else
						{
					?>
					<div class="updatediv" align="center"><input name="cart_qty_<?php echo $products_arr['cart_id']?>" type="text" id="cart_qty_<?php echo $products_arr['cart_id']?>" size="3" maxlength="4" value="<?php echo $products_arr["cart_qty"]?>"  />
					</div> 
					<div class="updatediv" align="center"><a href="#" onclick="document.frm_cart.remcart_id.value='<?php echo $products_arr['cart_id']?>';handle_form_submit(document.frm_cart,'Update_qty','#upd')" title="<?php echo $Captions_arr['CART']['CART_UPDATE']?>"><?php echo $Captions_arr['CART']['CART_UPDATE']?></a> <br/> <a href="#" class="update_link" onclick="if (confirm_message('<?php echo $Captions_arr['CART']['CART_ITEM_REM_MSG']?>')) {document.frm_cart.remcart_id.value='<?php echo $products_arr['cart_id']?>';handle_form_submit(document.frm_cart,'Remove_qty','#rem')}" title="<?php echo $Captions_arr['CART']['CART_REMOVE']?>"><?php echo $Captions_arr['CART']['CART_REMOVE']?></a></div>
					<?php
						}
					?>
					</td>
					<td align="right" valign="top" class="<?php echo $tdpriceDclass?>"><?php 
					if($this->checkprice_display($pass_fnpaytypeid)) {
					echo print_price($products_arr['final_price'],true);
					}?></td>
				  </tr>
				  <?php
				  	
			  }
		 
	}
        ?>
      </table></td>
    </tr>
    <?php
    if (count($cartData['products'])>0) // Done to show the message if no products in cart
		  {
    
   // Calling the function to decide the delivery details display section 
			$deliverydet_Arr = get_Delivery_Display_Details();
			if ($deliverydet_Arr['delivery_type'] != 'None') // Check whether any delivery method is selected for the site
			{
		  ?>
				<tr>
				<td height="44" valign="middle">
				<input type="hidden" name="cart_delivery_id" id="cart_delivery_id" value="<?php echo $deliverydet_Arr['delivery_id']?>"  />	
				<table width="100%" cellpadding="0"  cellspacing="0">
				 
				 <?php
				 	// Case if location is to be displayed
					if (count($deliverydet_Arr['locations']))
					{
				?>	
						 <tr>
						 <td align="right" class="shoppingcartcontent" ><span class="shoppingcartpriceOtr"><?php echo $Captions_arr['CART']['CART_DELIVERY_LOC']?></span> 
							<?php
								
									echo generateselectbox('cart_deliverylocation',$deliverydet_Arr['locations'],$row_cartdet['location_id'],'','handle_form_submit(document.frm_cart,"save_commondetails","#a_deliv")');
							?>	</td>
					   </tr>
				   <?php
				   }
				   // Check whether any delivery group exists for the site
				   if (count($deliverydet_Arr['del_groups']))
				   {
					   			$deliverydet_Arr['del_groups'][0] = "--Select--";
					   			ksort($deliverydet_Arr['del_groups']);//for sorting the array
				   ?>
					   <tr>
						 <td align="right" class="shoppingcartcontent"><span class="shoppingcartpriceOtr"><?php echo $Captions_arr['CART']['CART_DELIVERY_OPT']?></span>  
						
						  <?php
							echo generateselectbox('cart_deliveryoption',$deliverydet_Arr['del_groups'],$row_cartdet['delopt_det_id'],'','handle_form_submit_popup(document.frm_cart,"save_commondetails","#a_deliv","normal")');
						  ?>						 
						  <input type="hidden" id="previous" name="previous" value="<?php echo $row_cartdet['delopt_det_id']?>">
						  </td>
					   </tr>
					<?php
					}
					?>   
				 </table></td>
				</tr>
				<?php
				 // Check whether split delivery is supported by current site for current delivery method
				if ($deliverydet_Arr['allow_split_delivery']=='Y' and $cartData["pre_order"] != "none" and count($cartData['products'])>1)
				{
				?>
					<tr>
						<td colspan="5" align="center">
							<table width="100%" border="0" cellspacing="1" cellpadding="1">
							<tr>
							<td width="53%" align="left" valign="middle" class="shoppingcartcontent"><?php echo $Captions_arr['CART']['CART_WANT_DELIVERY_SPLIT']?></td>
							<td width="47%" align="left" valign="middle" class="shoppingcartpriceC"><input type="checkbox" name="cart_splitdelivery" id="cart_splitdelivery" <?php echo ($row_cartdet['split_delivery']==1)?'checked="checked"':''?> onclick="handle_form_submit(document.frm_cart,'save_commondetails','#a_deliv')" /></td>
							</tr>
							</table></td>
					</tr>
				<?php	
				}
				// Section to handle the case of split orders selected 
				if($cartData["totals"]["number_deliveries"] > 1)
				{ 
					for($i = 1; $i <= $cartData["totals"]["number_deliveries"]; $i++)
					{
						$ddate 		= explode('-',$cartData["delivery"]["group".$i]["date"]);
						if($ddate!='')
						{
							$show_date	= date('d-M-Y',mktime(0,0,0,$ddate[1],$ddate[2],$ddate[0]));
						}	
					?>
						<tr>
							<td class="shoppingcartcontent" colspan="3" align="center">
							<? 
							  	print $cartData["delivery"]["group".$i]["items"].$Captions_arr['CART']['CART_DELIVERY_CHARGE_FOR'];
								if($cartData["delivery"]["group".$i]["items"] > 1)
								{ 
									print ' '.$Captions_arr['CART']['CART_SPLIT_ITEMS']; 
								}
								else
								{ 
									print ' '.$Captions_arr['CART']['CART_SPLIT_ITEM']; 
								}
								print ' '.$Captions_arr['CART']['CART_TO_DESPATCH_ON']." " . $show_date ?></td>
							<td class="shoppingcartcontent" colspan="2" align="center">
							<? 
							if($this->checkprice_display($pass_fnpaytypeid)) {
							print print_price($cartData["delivery"]["group".$i]["cost"],true);}?></td>
						</tr>
					<?
					}				
				} 
		  	}
    ?>
    <tr>
      <td valign="top">
		  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="custinfowraps">
        <tr>
          <td width="27%" valign="top">
			<?php
			if (($row_cartdet['promotionalcode_id']!=0 or $row_cartdet['voucher_id']!=0) or $Settings_arr['show_cart_promotional_voucher']==1)
				{
			?> 
          <div class="promocodeWrap">
          <div class="promocodeTop"></div>
          <div class="promocodeBg">
  
  
  
  <div class="promocontent"><div class="promoTitle">Promotional Code</div>
  <p>
	   <?php
				// If gift voucher or promotional code is valid
				if (($row_cartdet['promotionalcode_id']!=0 or $row_cartdet['voucher_id']!=0))
				{
				
					if ($row_cartdet['promotionalcode_id']!=0 and $cartData["bonus"]['type']=='promotional' and $cartData['totals']['pro_type']!='product')
					{
					?>
					<?php echo $Captions_arr['CART']['CART_PROMOTIONAL_CODE_DISC']?>
					<?php
					}
					elseif($row_cartdet['voucher_id']!=0 and $cartData["bonus"]['type']=='voucher')
					{
					?>
					<?php echo $Captions_arr['CART']['CART_GIFTVOUCHER_DISC']?>
					<?php
					}
					?>:<b>	
					<?php 
					if ($row_cartdet['promotionalcode_id']!=0 and $cartData["bonus"]['type']=='promotional' and $cartData['totals']['pro_type']!='product')
						echo ($cartData['totals']['lessval']>0)?print_price($cartData['totals']['lessval'],true):0;
					elseif($row_cartdet['voucher_id']!=0 and $cartData["bonus"]['type']=='voucher')
						echo ($cartData['totals']['lessval']>0)?print_price($cartData['totals']['lessval'],true):0;
					?>
					</b>
					<?php
					if ($row_cartdet['promotionalcode_id']!=0 and $cartData["bonus"]['type']=='promotional' and $cartData['totals']['promotional_type']!='product')
					{
					} 
					elseif ($row_cartdet['promotionalcode_id']!=0 and $cartData['totals']['promotional_type']!='product')
					{
					?>	 
						
								<?php echo stripslash_normal($Captions_arr['CART']['CART_PROMO_NO_LINK_PROD']);?>
					<?php
					}
			    }		
				if ($Settings_arr['show_cart_promotional_voucher']==1)
			 	{
					if ($cartData["bonus"]['type']=='' or $cartData["bonus"]['type']=='promotional' or $cartData["bonus"]['type']=='voucher')
					{
					?>
  </p>
  <p><?php	
						 	if ($cartData["bonus"]['type']=='promotional' or $cartData['totals']['promotional_type']=='product')
							{
							?>
								 <?php echo $Captions_arr['CART']['CART_PROMO_CANCEL']?> 
						  <?php
							}
							elseif($cartData["bonus"]['type']=='voucher')
							{
							?>
								 <?php echo $Captions_arr['CART']['CART_VOUCHER_CANCEL']?>
						  <?php
							}
							elseif ($cartData["bonus"]['type']=='')
							{
						  ?>
								  <?php echo $Captions_arr['CART']['CART_PROMO_CAP']?>
						  <?php
						  	}
						  ?><br /><br />

<?php
						 	 if ($cartData["bonus"]['type']=='voucher' or $cartData["bonus"]['type']=='promotional' or $cartData['totals']['promotional_type']=='product') // handle the case of logged in or not.
							{
							?>
									<input name="cart_savepromotional" type="hidden" id="cart_savepromotional" value="0"/>	
								 	<input name="cancel_promotionalcode" type="button" class="buttonred_cart_clear" id="cancel_promotionalcode" value="<?php echo $Captions_arr['CART']['CART_PROMO_CANCEL_BUTTON']?>" onclick="document.frm_cart.cart_savepromotional.value=1;handle_form_submit(document.frm_cart,'save_commondetails','#a_prom')" />
						  <?php
						  	}
						  	elseif ($cartData["bonus"]['type']=='')
							{
						  ?>
						  		<input name="cart_savepromotional" type="hidden" id="cart_savepromotional" value="0"/>
							  	<input name="cart_promotionalcode" type="text" id="cart_promotionalcode" size="15" class="offerInput" />
							  	<?php /*<input name="submit_promotionalcode" type="button" class="buttongray" id="submit_promotionalcode" value="<?php echo $Captions_arr['CART']['CART_PROMO_GO']?>" onclick="document.frm_cart.cart_savepromotional.value=1;handle_form_submit(document.frm_cart,'save_commondetails','')" /> */?>
							  	<img src="<?php echo url_site_image('addcode_bt.jpg')?>" width="95" height="26" border="0" onclick="document.frm_cart.cart_savepromotional.value=1;handle_form_submit(document.frm_cart,'save_commondetails','')"/>
						  <?php
						  	}
							
						  ?>

  </p>
  <?php
  }
  ?>
<div></div>
  
  
		
          
          
          </div>
          <div class="promocodeBottom"></div>
          
          
          
          </div></div>
  <?php
		}
  }
  	
          ?>
          </td>
          <td width="73%" valign="top">
		  <?php
		  	$show_bonus_main_div 	= true;
			$proceed_bonus 			= show_cart_bonus_point_section($cartData,$cust_id);
  			if (is_Feature_exists('mod_bonuspoints') || $proceed_bonus)
			{
				$hide_bonus = false;
					if($cust_id ) // case if logged in
					{
						if ($cartData["customer"]["customer_bonus"] and $Settings_arr['cust_allowspendbonuspoints']==1) // does current customer have bonus points
						{
							if ($Settings_arr['minimum_bonuspoints'] >= (int)($cartData["customer"]['customer_bonus']))
							{
								$hide_bonus = true;
							}
							else
							{
							   if($cartData["totals"]["bonus"]==0)
							   {
								  $hide_bonus = true;
							   }
							}
							
						}
						else
						{
						   if($cartData["totals"]["bonus"]==0)
						   {
						      $hide_bonus = true;
						   }
						}
						
					}
				if($Settings_arr['cust_allowspendbonuspoints']==1 && $hide_bonus == false)
				{
					?>        
					<div class="promocodeWrap">
					<div class="promocodeTop"></div>
					<div class="promocodeBg">



					<div class="promocontent">
					<?php
					$show_bonus_main_div = false;
					if($show_bonus_main_div==true and $Settings_arr['cust_allowspendbonuspoints']==1)
					{
					 /*
					* <div class='shoppingcartcontent_left' style="float:left;"><?php echo $Captions_arr['CART']['CART_BONUS_MORE_MSGS']?></div><div class="cart_bonus_more" style="float:left">
					<?php
					$sql 				= "SELECT bonus_point_details_content FROM general_settings_sites_common WHERE sites_site_id=".$ecom_siteid;
					$res_admin 		= $db->query($sql);
					$fetch_arr_admin 	= $db->fetch_array($res_admin);
					if($fetch_arr_admin['bonus_point_details_content']!='')
					{
					?>
						<a href="<?php url_link('bonuspoint_content.html')?>" title=""><img src="<?php url_site_image('bonusmoreinfo.gif')?>" border="0" /></a>
						
					<?php
					}
					?>
					</div>
					*/ 
					}				
									
					?>
					<div class="promoTitle">Bonus Points</div>
					<?php
					if ($proceed_bonus)
					{					
					if ($cartData["totals"]["bonus"] and $cust_id)
					{
					?>
					<p><?php echo $Captions_arr['CART']['CART_TOTAL_POINTS_EARN']?>: <strong><?php echo $cartData["totals"]["bonus"]?></strong><br />
					<?php
					}
					if($cust_id) // case if logged in
					{
					if ($cartData["customer"]["customer_bonus"] and $Settings_arr['cust_allowspendbonuspoints']==1) // does current customer have bonus points
					{
					if ($Settings_arr['minimum_bonuspoints'] < (int)($cartData["customer"]['customer_bonus']))
					{
					?>
					<div class="bonusTitle"><?php echo $Captions_arr['CART']['CART_SPENT_BONUS_POINTS']?>
					<br />
					<input type="hidden" name="maxBonusPoints"  class="input" value="<? print $cartData["customer"]["customer_bonus"]; ?>">
					<input type="text" name="spendBonusPoints" size="4" value="<? print $cartData["bonus"]["spending"]; ?>">
					<input type="hidden" name="leftBonusPoints" value="<? print $cartData["bonus"]["left"] ?>">
					<br />
					<input  class="buttonred_cart_clear" type="button" value="<?php echo $Captions_arr['CART']['CART_SPEND']?>" onclick="handle_form_submit(document.frm_cart,'save_commondetails','#bonus')">
					&nbsp;&nbsp;<strong>(<? print (int)(($cartData["bonus"]["left"])); ?> &nbsp;<?php echo $Captions_arr['CART']['CART_REMAINING']?>)</strong>
					</div>
					<?php
					}
					else
					{
					?>
					<div class="remain"><?php echo str_replace('[min_points]',$Settings_arr['minimum_bonuspoints'],$Captions_arr['CART']['CART_MIN_BONUS_REQ'])?></div>
					<?php
					}
					}
					}
					else
					{
					if ($cartData["totals"]["bonus"] and $Settings_arr['cust_allowspendbonuspoints']==1)
					{
					?>
					<div class="remain"><?php echo $Captions_arr['CART']['CART_TOTAL_POINTS_EARN'];?>:<b><?php echo $cartData["totals"]["bonus"];?></b></div>
					<?php
					}
					}	
					?>
					</p>
					<?php
					}
					?>
					<?php
					
					if(!$cust_id)
					{
					if($Captions_arr['CART']['CART_BONUS_LOGIN_MSG']!='' and $Settings_arr['cust_allowspendbonuspoints']==1)
					{
					?>
					<?php echo $Captions_arr['CART']['CART_IF_HAVE_ACCT'];?><input type="button" name="cust_login_bonus" id="cust_login_bonus" value="<?php echo $Captions_arr['CART']['CART_BONUS_LOGIN_MSG']?>"  class="buttonred_cart_logbonus" onclick="window.location ='<?php url_link('custlogin.html?redirect_back=1&pagetype=cart')?>'"/>
					<?php
					}
					}	
					?>
					<div></div>
					</div>
					
					
					<div class="promocodeBottom"></div>
					</div>
					</div>
					<?php
				  } 
				}
		  	?>
		  </td>
        </tr>
        <tr>
          <td colspan="2" valign="top">
		  <?php
		  if($this->checkprice_display($pass_fnpaytypeid)) {
		  ?>
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td valign="top">
              
              <div class="subtotalcartzwrap"><div class="subtotalcartztop"></div>
              <div class="subtotalcartzbg">
              <div class="subtotalcartContent">
                <table width="100%" border="0" align="right" cellpadding="1" cellspacing="1" class="contentcarts">
                 <tr>
				<td align="right" valign="middle" class="colsubtotalz"  width="790"><?php echo $Captions_arr['CART']['CART_TOTPRICE']?></td>
				<td align="right" valign="middle" class="colsubtotalamt"><?php echo print_price($cartData["totals"]["subtotal"],true)?></td>
			  </tr>
                  <?php
					// Check whether delivery is charged, then show the total after applying delivery charge
			if($cartData["totals"]["delivery"])
			{
			?>
			<tr>
				<td  width="790" align="right" class="colsubtotalz"><?php echo $Captions_arr['CART']['CART_TOTAL_DELIVERY_CHARGES']?></td>
				<td align="right" class="colsubtotalamt"><?php echo print_price($cartData["totals"]["delivery"],true)?></td>
			  </tr>
			<?php
			}
		  	// Section to show the extra shipping cost
			 if($cartData["totals"]["extraShipping"])
			 {
			 ?>
				 <tr>
					<td width="790" align="right" class="colsubtotalz"><?php echo $Captions_arr['CART']['CART_EXTRA_SHIPPING']?></td>
					<td align="right" class="colsubtotalamt"><?php echo print_price($cartData["totals"]["extraShipping"],true)?></td>
				  </tr>
			 <?php	
			 }
			
		   // Section to show the tax details
			 if($cartData["totals"]["tax"])
			 {
			 ?>
					<tr>
						<td width="790" align="right" class="colsubtotalz">
						<?php echo $Captions_arr['CART']['CART_TAX_CHARGE_APPLIED']?>
						<?	
							foreach($cartData["tax"] as $tax)
							{
								echo '('.$tax['tax_name']; ?> @ <? print $tax['tax_val']; ?>%)
						<?	
							}
						?>						</td>
						<td align="right" class="colsubtotalamt">
						<?	
							foreach($cartData["tax"] as $tax)
							{
								echo print_price($tax["charge"],true); ?> <br />
						<?	
							}
						?>
						</td>
					</tr>
				<?php
			}
			 // Show the following only if any bonus point is spend
						if ($cartData["bonus"]["value"])
						{
						?>
						  <tr>
							<td width="790" align="right" class="colsubtotalz"><?php echo $Captions_arr['CART']['CART_SPENT_BONUS_POINTS_VALUE']?> </td>
							<td align="right" class="colsubtotalamt"><? echo '(-) '.print_price($cartData["bonus"]["value"],true);?></td>
						  </tr>
				<?php	
						}
						/*
				// show the total final price
			if($cartData["totals"]["bonus_price"])
			{
			?>
			 
			  <tr>
				<td width="790" align="right" class="colsubtotalz"><?php echo $Captions_arr['CART']['CART_TOTAL_FINAL_COST']?>&nbsp; </td>
				<td align="right" class="colsubtotalamt"><?php echo print_price($cartData["totals"]["bonus_price"],true)?></td>
			  </tr>
		  <?php
		  	}
		  	*/ 
		  	$rem_val = $cartData["totals"]["bonus_price"] - $str_reduceval;
			if($rem_val >0 and $str_reduceval>0)
			{
			?>
				<tr>
					<td width="790" align="right" class="colsubtotalz"><?php echo $Captions_arr['CART']['CART_LESS_AMT_LATER']?>&nbsp; </td>
					<td align="right" class="colsubtotalamt">
					<input type="hidden" name="store_reduceval" value="<?php echo $str_reduceval?>" />

						<?php echo print_price($str_reduceval,true)?></td>
			  	</tr>				
			<?php
			}	?>
            </table>
              </div>
              
              </div>
              <div class="subtotalcartzbottom"></div>
              
              
              </div>
             
              <div class="finalcostWrap"><?php echo $Captions_arr['CART']['CART_TOTAL_FINAL_COST']?>&nbsp;:&nbsp;<?php echo print_price($rem_val,true)?></div>
              </td>
            </tr>
          </table>
		  <?php
		  }
		  ?>
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
			 	 <tr>
              <td valign="top">
			  <?php
			  
            $cc_exists 		= 0;
// Show the payment type and payment method sections only if total amount > 0
			if($cartData["totals"]["bonus_price"]>0)
			{
		  	// Check whether google checkout is required
		  	$sql_google = "SELECT a.paymethod_id,a.paymethod_name,a.paymethod_key,
										  a.paymethod_takecarddetails,a.payment_minvalue,
										  b.payment_method_sites_caption 
									FROM 
										payment_methods a,
										payment_methods_forsites b 
									WHERE 
										a.paymethod_id=b.payment_methods_paymethod_id 
										AND b.sites_site_id = $ecom_siteid 
										AND b.payment_method_sites_active = 1 
										AND paymethod_key='GOOGLE_CHECKOUT' 
									LIMIT 
										1";
			$ret_google = $db->query($sql_google);
			if($db->num_rows($ret_google))
			{
				$google_exists = true;
			}
			else 	
				$google_exists = false;
			$more_pay_condition = '';
			// Check whether google checkout is set for current site
			if($ecom_common_settings['paymethodKey']['GOOGLE_CHECKOUT']['paymethod_key'] == "GOOGLE_CHECKOUT")
			{
				$google_prev_req 		= $ecom_common_settings['paymethodKey']['GOOGLE_CHECKOUT']['payment_method_preview_req'];
				$google_recommended		= $ecom_common_settings['paymethodKey']['GOOGLE_CHECKOUT']['payment_method_google_recommended'];
				if($google_recommended ==0) // case if google checkout is set to work in the way google recommend
					$more_pay_condition = " AND paymethod_key<>'GOOGLE_CHECKOUT' ";
			}		
			// Check whether paypal express is set for current site
			if($ecom_common_settings['paymethodKey']['PAYPAL_EXPRESS']['paymethod_key'] == "PAYPAL_EXPRESS")
			{
				$paypal_prev_req 		= $ecom_common_settings['paymethodKey']['PAYPAL_EXPRESS']['payment_method_preview_req'];
				$more_pay_condition 	= " AND paymethod_key<>'PAYPAL_EXPRESS' ";
				$show_multiple			= true;
			}
			$cc_exists 		= 0;
				
			// Running the qry to pick the payment methods to be displayed
			$sql_paymethods = "SELECT a.paymethod_id,a.paymethod_name,a.paymethod_key,
									  a.paymethod_takecarddetails,a.payment_minvalue,a.paymethod_ssl_imagelink,
									  b.payment_method_sites_caption 
								FROM 
									payment_methods a,
									payment_methods_forsites b 
								WHERE 
									a.paymethod_id=b.payment_methods_paymethod_id 
									$more_pay_condition  
									AND payment_method_sites_active = 1  
									AND b.sites_site_id=$ecom_siteid";
			$ret_paymethods = $db->query($sql_paymethods);
			$totpaycnt		= $db->num_rows($ret_paymethods);
			if ($totpaycnt==1)
			{
				$cc_exists = true;
			}
			if ($totpaycnt>0)
			{
				$paytype_add_condition  = " ";
				$show_cc = true;
			}	
			else
			{
				$paytype_add_condition  = " AND a.paytype_code <> 'credit_card' ";
				$show_cc = false;
			}	
			
			if($google_exists && $Captions_arr['CART']['CART_PAYMENT_MULTIPLE_MSG'] && $google_recommended ==0 && $totpaycnt>0)
				$show_multiple=true;
			
			
				
			/*if($show_multiple==true)
			{	
		  ?>
		  	
					<?php 

						echo $Captions_arr['CART']['CART_PAYMENT_MULTIPLE_MSG'];
					?>
						
		  <?php
			}
			*/ 
			// Running the qry to pick the payment types to be displayed
				if($cust_id) // case if customer is logged in
				{
					// Check whether pay_on_account is active for current customer
					$sql_custcheck = "SELECT customer_payonaccount_status ,customer_payonaccount_maxlimit,customer_payonaccount_usedlimit 
													FROM 
														customers 
													WHERE 
														customer_id = $cust_id 
														AND sites_site_id = $ecom_siteid 
														AND customer_payonaccount_status ='ACTIVE' 
													LIMIT 
														1";
					$ret_custcheck = $db->query($sql_custcheck);
					if ($db->num_rows($ret_custcheck)) // case if payon account is active for current customer
					{
						$row_custcheck 						= $db->fetch_array($ret_custcheck);
						$payonaccount_maxlimit 			= $row_custcheck['customer_payonaccount_maxlimit'];
						$payonaccount_usedmaxlimit 	= $row_custcheck['customer_payonaccount_usedlimit'];
						$payonaccount_remlimit			= $payonaccount_maxlimit - $payonaccount_usedmaxlimit;
						
					}
					else
					{
						$paytype_add_cond				= " AND a.paytype_code <> 'pay_on_account' ";
						$payonaccount_remlimit = 0;
					}	
					$sql_paytypes 	= "SELECT a.paytype_code,b.paytype_forsites_id,a.paytype_id,a.paytype_name,b.images_image_id,
											b.paytype_caption   
										FROM 
											payment_types a, payment_types_forsites b 
										WHERE 
											b.sites_site_id = $ecom_siteid 
											AND paytype_forsites_active=1 
											AND paytype_forsites_userdisabled=0 
											AND a.paytype_id=b.paytype_id 
											$paytype_add_cond 
											$paytype_add_condition 
										ORDER BY 
											a.paytype_order";
				}
				else // case if customer in not logged in. So show only those payment types whose value for paytype_logintouse is set to 0
				{
					$sql_paytypes 	= "SELECT a.paytype_code,b.paytype_forsites_id,a.paytype_id,a.paytype_name,b.images_image_id,
											b.paytype_caption  
										FROM 
											payment_types a, payment_types_forsites b 
										WHERE 
											b.sites_site_id = $ecom_siteid 
											AND paytype_forsites_active=1 
											AND paytype_forsites_userdisabled=0 
											AND a.paytype_id=b.paytype_id 
											AND paytype_logintouse = 0 
											$paytype_add_condition 
										ORDER BY 
											a.paytype_order";
				}							
				$ret_paytypes = $db->query($sql_paytypes);
				if ($db->num_rows($ret_paytypes))
				{
					
					if($db->num_rows($ret_paytypes)==1)// Check whether there are more than 1 payment type. If no then dont show the payment option to user, just use hidden field
					{
						$row_paytypes = $db->fetch_array($ret_paytypes);
						if($row_paytypes['paytype_code']=='credit_card')
						{
							//if($row_paytypes['paytype_id']==$row_cartdet['paytype_id'])
								$cc_exists = true;
						}
					?>
						<input type="hidden" name="cart_paytype" id="cart_paytype" value="<?php echo $row_paytypes['paytype_id']?>" />
					<?php
					}
					else
					{
						
						// Check how the payment types to be displayed to the user
						if ($Settings_arr['paytype_listingtype']=='dropdown') // Case of payment listing type is to shown as drop downbox
						{
							$paymethod[0] = $Captions_arr['CART']['CART_SELECT'];
							while ($row_paytypes = $db->fetch_array($ret_paytypes))
							{
								if($row_paytypes['paytype_code']=='pay_on_account')
								{
									$add_text = ' (Credit Available '. print_price($payonaccount_remlimit).')';
								}	
								else
									$add_text = '';
								$paymethod[$row_paytypes['paytype_id']] = stripslashes($row_paytypes['paytype_caption']).$add_text;	
								if($row_paytypes['paytype_code']=='credit_card')
								{
									if($row_paytypes['paytype_id']==$row_cartdet['paytype_id'])
										$cc_exists = true;
								}					
							}
					?>	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
								  <td colspan="2" align="right" class="shoppingcartcontent_delivery"><a name="a_pay">&nbsp;</a><?php echo $Captions_arr['CART']['CART_SEL_PAYTYPE']?>								
								 <?php
										if($totpaycnt>1)// decide whether to add the onchange event
											echo generateselectbox('cart_paytype',$paymethod,$row_cartdet['paytype_id'],'','handle_form_submit(document.frm_cart,"save_commondetails","#a_pay")');
										else 
											echo generateselectbox('cart_paytype',$paymethod,$row_cartdet['paytype_id'],'','');
									  ?>
									  </td>
								</tr>
							  </table>							
					<?php
						}
						elseif ($Settings_arr['paytype_listingtype']=='icons') // Case if payment types is to be displayed as list items
						{
							$pay_maxcnt = 3;
							$pay_cnt	= 0;
							$finance_available = false;
							$sql_4minfinance_exists	= "SELECT a.paytype_code
														FROM 
															payment_types a, payment_types_forsites b 
														WHERE 
															b.sites_site_id = $ecom_siteid 
															AND paytype_forsites_active=1 
															AND paytype_forsites_userdisabled=0 
															AND a.paytype_id=b.paytype_id 
															AND paytype_code = '4min_finance'
														LIMIT 1";
							$ret_4minfinance_exists = $db->query($sql_4minfinance_exists);
							if($db->num_rows($ret_4minfinance_exists))
							{
								if($Settings_arr['4minfinance_lowerlimit']<=$cartData["totals"]["bonus_price"])
									$finance_available = true;
							}
							?>
							
              			<a name="a_pay"></a>

              <div class="subtotalcartzwrap">
				  <div class="subtotalcartztop"></div>
              <div class="subtotalcartzbg">
              <div class="subtotalcartContent"><div class="payTitle"><?php echo $Captions_arr['CART']['CART_SEL_PAYTYPE']?></div>
							<table width="90%" border="0" cellspacing="0" cellpadding="0" class="payCard">
								<tr>
								<?php
										while ($row_paytypes = $db->fetch_array($ret_paytypes))
										{
											$cur_cc = false;
											if($row_paytypes['paytype_code']=='credit_card')
											{
												$cur_cc		= true;
												if($row_paytypes['paytype_id']==$row_cartdet['paytype_id'])
												{
													$cc_exists 	= true;
												}	
											}	
											if($row_paytypes['paytype_code']=='pay_on_account')
											{
												$add_text = ' (Credit Available '. print_price($payonaccount_remlimit,true).')';
											}	
											else
												$add_text = '';
												if($pay_cnt%2==0)
												{
												 $classB = "payclassA";
												}
												else
												{
												 $classB = "payclassAB";
												}
												?>
												<td width="3%" align="right">
												<?php
												$show_ptype = true;
												if($row_paytypes['paytype_code']=='4min_finance')
												{
													if($Settings_arr['4minfinance_lowerlimit']>$cartData["totals"]["bonus_price"])
													{
														$show_ptype = false;
													}
												}
												if ($show_ptype)
												{
												
												
											// image to shown for payment types
											$pass_type = 'image_iconpath';
											// Calling the function to get the image to be shown
											$img_arr = get_imagelist('paytype',$row_paytypes['paytype_forsites_id'],$pass_type,0,0,1);
											if(count($img_arr))
											{
												show_image(url_root_image($img_arr[0][$pass_type],1),$row_paytypes['paytype_caption'],$row_paytypes['paytype_caption']);
											}
											else
											{
											?>
												<img src="<?php url_site_image('cash.gif')?>" alt="Payment Type"/>
											<?php	
											}	
											
											
											if($totpaycnt>1)// case if number of payment methods are more than one.
											{
												// case if there are more than one payment method exists and currently looping for the credit card payment method display. so the onlick should be in such a way that reload is required
												if ($cur_cc==true)
													$on_change = "onclick=\"handle_form_submit(document.frm_cart,'save_commondetails','#a_pay');\"";
												else // case if more than one payment method and currently looping through non credit card option, so onlick reloading is required only if the cc option is already existing.
													$on_change = "onclick=\"if(document.getElementById('paymethod_id')){ handle_form_submit(document.frm_cart,'save_commondetails','#a_pay');}\"";														
											}
											else
												$on_change = '';
											
											if($finance_available==true)
											{
												$on_change = "onclick=\"handle_form_submit(document.frm_cart,'save_commondetails','#a_pay');\"";
											}
													
												/*								
												<input class="shoppingcart_radio" type="radio" name="cart_paytype" id="cart_paytype" value="<?php echo $row_paytypes['paytype_id']?>" <?php echo ($row_paytypes['paytype_id']==$row_cartdet['paytype_id'])?'checked="checked"':''?>"/><?php echo stripslashes($row_paytypes['paytype_name'])?>
											<?php
											}
											else 
											{
											?>
											<?php
											}*/
											?>	
												</td>
												<td width="2%" align="right">
												<input class="shoppingcart_radio" type="radio" name="cart_paytype" id="cart_paytype" value="<?php echo $row_paytypes['paytype_id']?>" <?php echo ($row_paytypes['paytype_id']==$row_cartdet['paytype_id'])?'checked="checked"':''?> <?php echo $on_change?>/>
													<label for="radio"></label></td>	
												<td class="<?php echo $classB;?>" align="left">
													<?php echo stripslashes($row_paytypes['paytype_caption']).$add_text?>
													</td>	
												<?php
												$pay_cnt++;
										if ($pay_cnt>=$pay_maxcnt)
										{
											echo "</tr><tr>";
											$pay_cnt = 0;
										}
										}
									}	
									  ?>
									  </tr>
								</table>	
														<table width='100%' cellpadding='0' cellspacing='0' border='0'>

								<?php
								$tenper_deposit = 0;
								$fiftyper_deposit = 0;
								 if($finance_available==true)
		  	{
				// check whether the selected payment type is finance
				$sql_pcheck = "SELECT paytype_code FROM payment_types WHERE paytype_id =".$row_cartdet['paytype_id']." LIMIT 1";
				$ret_pcheck = $db->query($sql_pcheck);
				if($db->num_rows($ret_pcheck))
				{
					$row_pcheck = $db->fetch_array($ret_pcheck);
					if($row_pcheck['paytype_code']=='4min_finance')
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

						if(!$saved_deposit or $saved_deposit=='0.00')
						{
							$findeposit = $tenper_deposit;
						}
						else
						{	
							if($saved_deposit<$tenper_deposit)
							{
								$findeposit = $tenper_deposit;
							}
							else
							{
								$findeposit = $saved_deposit;
							}	
						}
					?>
					<tr>
						<td colspan="6" align="left" valign="middle">
						<table width='100%' cellpadding='0' cellspacing='0' border='0'>
						<tr>
							<td align="left" class="fin_text">
							Enter the Deposit Amount 	&pound;<input type='text' name='fin_deposit' id="fin_deposit" value='<?php echo $findeposit?>' onblur="reload_finance(this)">
							</td>
						</tr>
						</table>	
						<div id="finance_holder">
						<?php $this->finance_details($cartData,$findeposit);?>
						</div>	
						</td>
					</tr>		
					<?php	
					}
				}
			}
								?> 
								</table> 
				</div>
              
              </div>
              <div class="subtotalcartzbottom"></div>
              
              
              </div>			
							<?php
						}
						
					}
				}
				else // case if no payment type is selected for the site. so keep the invoiced method by default 
				{
				 $sql_invoice = "SELECT paytype_id FROM payment_types WHERE paytype_code='invoice' LIMIT 1";
				 $ret_invoice = $db->query($sql_invoice);
				 $row_invoice = $db->fetch_array($ret_invoice);
			?>
					<input type="hidden" name="cart_paytype" id="cart_paytype" value="<?=$row_invoice['paytype_id']?>" />
			<?php
				}
				if ($cc_exists)
			{
				if ($db->num_rows($ret_paymethods))
				{
					if ($db->num_rows($ret_paymethods)==1)
					{
						$row_paymethods = $db->fetch_array($ret_paymethods);
					?>
						<input type="hidden" name="cart_paymethod" id="cart_paymethod" value="<?php echo $row_paymethods['paymethod_id']?>" />
					<?php
					}
					else
					{
			?>
					
							<?php
								$pay_maxcnt = 3;
								$pay_cnt	= 0;
							?>
								<div class="subtotalcartzwrap">
				  <div class="subtotalcartztop"></div>
              <div class="subtotalcartzbg">
              <div class="subtotalcartContent"><div class="payTitle"><?php echo $Captions_arr['CART']['CART_SEL_PAYGATEWAY']?></div>
							<table width="90%" border="0" cellspacing="0" cellpadding="0" class="payCard" id="paymethod_id">
								<tr>
								
								  <?php
									while ($row_paymethods = $db->fetch_array($ret_paymethods))
									{
										$caption = ($row_paymethods['payment_method_sites_caption'])?$row_paymethods['payment_method_sites_caption']:$row_paymethods['paymethod_name'];
										// Check whether image is assigned for current payment method
										if ($row_paymethods['paymethod_ssl_imagelink']!='')
										{
											$img_path="./images/".$ecom_hostname."/site_images/payment_methods_images/".$row_paymethods['paymethod_ssl_imagelink'];										
											 if(file_exists($img_path))
											 	$caption = '<img src="'.$img_path.'" border="0" alt="'.$caption.'" />';
										}
										if($pay_cnt%2==0)
												{
												 $classB = "payclassC";
												}
												else
												{
												 $classB = "payclassCB";
												}
								  ?>
										<td width="2%" align="right">
											<input class="shoppingcart_radio" type="radio" name="cart_paymethod" id="cart_paymethod" value="<?php echo $row_paymethods['paymethod_id']?>" <?php echo ($row_paymethods['paymethod_id']==$row_cartdet['paymethod_id'])?'checked="checked"':''?> <?php /*onclick="handle_form_submit(document.frm_cart,'save_commondetails','#a_pay')"*/?> />
											</td>
<td class="<?php echo $classB ?>" align="left">											
<?php echo stripslashes($caption)?>
											</td>
										
								<?php
										$pay_cnt++;
										if ($pay_cnt>=$pay_maxcnt)
										{
											echo "</tr><tr>";
											$pay_cnt = 0;
										}
									}
									
								?>	
								  </tr>
								  
								  
								  
								  
								</table>
								
								
								
								</div>
              
              </div>
              <div class="subtotalcartzbottom"></div>
              
              
              </div>
						
          <?php
		  			}
		  		}
		  	}
		  	/*
				?>
				              <div class="finalcostWrap"><?php echo $Captions_arr['CART']['CART_TOTAL_FINAL_COST']?>:<?php echo print_price($rem_val,true)?></div>

				<?php
				*/ 
			
		 
				
			}
		  ?>             
              </td>
            </tr>
          </table>
                     <table width="100%" border="0" cellspacing="0" cellpadding="0">
						 <tr>
            <td colspan="5" align="right" valign="middle" class="shoppingcartcontent">
            
			  <?php
				switch($Settings_arr['config_continue_shopping'])
				{
					case 'home':
						// Calling function to get the url to which customer to be taken back when hit the Continue shopping button
						$ps_url 	= 'http://'.$ecom_hostname;
					break;
					default:
						// Calling function to get the url to which customer to be taken back when hit the Continue shopping button
						$ps_url = get_continueURL($_REQUEST['pass_url']);
					break;
				}	
			    if($_REQUEST['cont_pass_val'] !='')
					$ps_url = $_REQUEST['cont_pass_val'];
			  ?>
			  	  <div class="cart_continue_div"  align='left'>
				<?php
					if($ps_url) // show the continue shopping button only if ps_url have value
					{
				?>
				  
				  <input name="continue_submit" type="button" class="buttonred_cart_continue" id="continue_submit" value="<?php echo $Captions_arr['CART']['CART_CONT_SHOP']?>" onclick="show_wait_button(this,'Please Wait...');window.location='<?php echo $ps_url?>'" />
				<?php
					}
					else
						echo '&nbsp;';
				?>  
				  </div>
			  <?php
				// Handling the case of login required before checkout.
				$show_checkoutbutton = true;
				if($Settings_arr['forcecustomer_login_checkout'] and !$cust_id)
				{
						$show_checkoutbutton = false;
				}
				$inter_proceed = check_intermediate_required();
		    if($inter_proceed)
		    {
		    $inter = "yes";
		    $on_changeA = "onclick=\" handle_intercart_submit('$ecom_hostname','cart','".$cartData["totals"]["bonus_price"]."');\"";

			}
		    else
		    {
		     $inter = "no";
		     $on_changeA = "onclick=\"handle_checkout_submit('$ecom_hostname',0,'".$cartData["totals"]["bonus_price"]."');\"";
 
			}
				if($show_checkoutbutton==true)	
				{
			  ?>	
			  		<div class="cart_checkout_div1"  align='right'>
             		 <input name="continue_checkout" type="button" class="buttonred_cart" id="continue_checkout" value="<?php echo $Captions_arr['CART']['CART_GO_CHKOUT']?>" <?php echo $on_changeA?> />
             		<?php /*<a href="#" onclick="handle_checkout_submit('<?php echo $ecom_hostname?>',0,'<?php echo $cartData["totals"]["bonus_price"]?>')"><img src="<?php url_site_image('credit_debit_card_new.jpg')?>" alt="Checkout"  border="0" style="cursor:hand"></a>*/?>
             		</div> 
			  <?php
			  	}
				else
				{
						
						echo "<div class='cart_checkout_div' align='right'><span class='red_msg'>You need to login to continue to checkout</span></div>";
						
				}
			  ?>
			  <?php /*?>Used to redirect back<?php */?>
			  <input type="hidden" name="pass_url" id="pass_url" value="<?php echo $ps_url?>"/>  
			  
			  <?php /*?>paymethod exists indicatory */?>
			  <input type="hidden" name="cc_req_indicator" id="cc_req_indicator" value="<?php echo $cc_exists?>" />
			  
			  
			  
			  <input type="hidden" name="paysel_msg_disp" id="paysel_msg_disp" value="<?php echo $Captions_arr['CART']['CART_SEL_PAYTYPE_MSG']?>" />
			  <input type="hidden" name="gate_msg_disp" id="gate_msg_disp" value="<?php echo $Captions_arr['CART']['CART_SEL_PAYGATEWAY_MSG']?>" />
			  <input type="hidden" name="del_msg_disp" id="del_msg_disp" value="<?php echo $Captions_arr['CART']['CART_DELIVERY_LOC_MSG']?>" />
			  <input type="hidden" name="tc_msg_disp" id="tc_msg_disp" value="<?php echo $Captions_arr['CART']['CART_TC_CHECKBOX_MSG']?>" />
	       	  <input type="hidden" name="meth_msg_disp" id="meth_msg_disp" value="<?php echo $Captions_arr['CART']['CART_DELIVERY_METH_MSG']?>" />

	       	</td>
          </tr>

</table>
          </td>
          </tr>
      </table></td>
    </tr>
    <?php
}
    ?>
  </table>
</div>
</form>

  <?php
		    
		    	// Check whether the google checkout button is to be displayed
		    	if($google_exists and $google_recommended==0 && $show_checkoutbutton==true)
		    	{
					$row_google = $db->fetch_array($ret_google);
		  ?>
				<table width="100%" border="0" cellpadding="0" cellspacing="0" class="shoppingcarttable">
				<?php 
				if($totpaycnt>0)
				{
				?>
				<tr>
					<td align="right" valign="middle" class="google_or" colspan="2">
					<img src="<?php echo url_site_image('gateway_or.gif')?>" alt="Or" border="0" />
					</td>
				</tr>	
				<?php
				}
				?>
				<tr>
					<td colspan="6" align="right" valign="middle" class="google_td">
					<?php
						$pass_type = 'ord';
						$display_option = 'ALL';
						require_once('includes/google_library/googlecart.php');
						require_once('includes/google_library/googleitem.php');
						require_once('includes/google_library/googleshipping.php');
						require_once('includes/google_library/googletax.php');
						include("includes/google_checkout.php");
					?>	
					</td>
				</tr>	
				</table>
		<?php
			}
			/*
			// case if paypal button is to be displayed
			if($ecom_common_settings['paymethodKey']['PAYPAL_EXPRESS']['paymethod_key'] == "PAYPAL_EXPRESS" and $show_checkoutbutton==true)// and $_SERVER['REMOTE_ADDR']=='118.102.196.27')
			{
			?>
				<table width="100%" border="0" cellpadding="0" cellspacing="0" class="shoppingcarttable">
				<?php
				  // if($ecom_siteid != 61) // garraways live siteid 61
					{
						if(($totpaycnt>0) or ($google_exists and $google_recommended==0))
						{
						?>
							<?php /*<tr>
								<td align="right" valign="middle" class="google_or" colspan="2">
								<img src="<?php echo url_site_image('gateway_or.gif')?>" alt="Or" border="0" />
								</td>
							</tr>	*//* ?>
						<?php
						}
					}
				?>
								<tr>
								<td align="right" valign="top" class="google_tdA"  colspan="2" style="padding-right:70px">

								<img src="<?php echo url_site_image('gateway_or.gif')?>" alt="Or" border="0" />
								</td>
								</tr>

					<tr>
						<td align="right" valign="top" class="google_tdA" width="85%" style="padding-right:20px;padding-top:10px;">
							<?php							
															?>
							    

								<?php
								echo stripslashes($Captions_arr['CART']['CART_PAYPAL_HELP_MSG']);
								$button_img_path = 'https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif';
								
							?>
							</td>
						<td align="right" valign="middle" class="google_tdA">
						<input type='image' name='submit' src='<?php echo $button_img_path?>' border='0' align='top' alt='PayPal' onclick="handle_checkout_submit('<?php echo $ecom_hostname?>',2,'<?php echo $cartData["totals"]["bonus_price"]?>')"/>
						</td>
					</tr>	
				</table>
			<?php 	
		}	
		*/ 	
		?>
		<script type="text/javascript">
		/*function reload_finance(objs)
		{
			var fpurpose = 'Reload_financeoptions';
			var qrystr = '&deposit='+objs.value;
			retobj	= document.getElementById('finance_holder');
			document.getElementById('ajax_div_holder').value = 'finance_holder';
			retobj.innerHTML = 'loading...';
			Handlewith_Ajax('<?php echo $ORG_DOCROOT?>/includes/base_files/add_tocart_ajax.php','ajax_fpurpose='+fpurpose+'&'+qrystr);
		}
		*/
		function reload_finance(objs)
		{
			var minval = <?php echo $tenper_deposit?>;
			var maxval = <?php echo $fiftyper_deposit?>;
			var depval = objs.value;
			if (isNaN(depval))
			{
				alert ('Deposit amount should be a number');
				document.getElementById('fin_deposit').focus();
			}
			else
			{
				if (depval<minval || depval>maxval)
				{
					alert ('Deposit value should be between '+String.fromCharCode('163')+minval+' and '+String.fromCharCode('163')+maxval);
					document.getElementById('fin_deposit').focus();
				}
				else
				{
					var fpurpose = 'Reload_financeoptions';
					var qrystr = '&deposit='+objs.value;
					retobj	= document.getElementById('finance_holder');
					document.getElementById('ajax_div_holder').value = 'finance_holder';
					retobj.innerHTML = 'loading...';
					Handlewith_Ajax('<?php echo $ORG_DOCROOT?>/includes/base_files/add_tocart_ajax.php','ajax_fpurpose='+fpurpose+'&'+qrystr);
				}	
			}	
		}
		</script>
		</script>
		<?php		 
		}
		function checkprice_display($pass_paymenttypeid=0)
		{
			global $db,$ecom_siteid;
			$return_status = '';
			/*if($ecom_siteid==106 or $ecom_siteid==104)
			{
				if(!$pass_paymenttypeid)
				{
					$return_status= false;
				}	
				else
				{
					$return_status= true;
				}
			}
			else
			{*/
				$return_status= true;
			/*}	*/
			return $return_status;
		}	
		function finance_details($cartData,$deposit=0)
		{
			global $db,$ecom_siteid;
			$session_id = Get_session_Id_from();
			$sql_finance = "SELECT * FROM finance_details WHERE sites_site_id = $ecom_siteid ORDER BY finance_sortorder";
			$ret_finance = $db->query($sql_finance);
			if($db->num_rows($ret_finance))
			{
			/*$finance_arr = array(
							0=>array('code'=>'ONIB24-19.5','rate'=>'19.5','name'=>'2 Year Classic Credit (19.5% APR)'),
							1=>array('code'=>'ONIB36-19.5','rate'=>'19.5','name'=>'3 Year Classic Credit (19.5% APR)'),
							2=>array('code'=>'ONIB48-19.5','rate'=>'19.5','name'=>'4 Year Classic Credit (19.5% APR)')
						);	*/
			// Check whether finance id is saved in cart_supportdetails table 
			$saved_finid = 0;
			$saved_deposit = 0;
			$sql_crt = "SELECT finance_id,finance_deposit FROM cart_supportdetails WHERE sites_site_id = $ecom_siteid AND session_id = '".$session_id."' LIMIT 1";			
			$ret_crt = $db->query($sql_crt);
			if($db->num_rows($ret_crt))
			{
				$row_crt = $db->fetch_array($ret_crt);
				$saved_finid 	= $row_crt['finance_id'];
				$saved_deposit	= $row_crt['finance_deposit'];
				if(!$deposit)
				{
					$deposit = $saved_deposit;
				}	
			}
			// Get the id of 48 months finance code ONIB48-19.5
			$sql_getc = "SELECT finance_id FROM finance_details WHERE sites_site_id = $ecom_siteid and finance_code='ONIB48-19.5' LIMIT 1";
			$ret_getc = $db->query($sql_getc);
			if($db->num_rows($ret_getc))
			{
				$row_getc = $db->fetch_array($ret_getc);
				$def_fincode = $row_getc['finance_id'];
			}
			$sel_code = ($saved_finid)?$saved_finid:$def_fincode;			
		?>
<style type='text/css'>

.finwrap{
	
	width:900px;
	float:left;
	margin-top:20px;
}
.finwrap table{
	font:normal 13px Arial, Helvetica, sans-serif;
	
}

.finwrap table td{
	padding:3px;
	height:20px;
	color:#666;

	vertical-align:middle;
	
}
.finwrap2Year{
	
	float:left;
	width:271px;
	height:233px;
	background:#ebf5fe;
	padding:6px;
	margin-right:10px;
	padding-top:15px;
}

.finwrap3Year{
	
	float:left;
	width:271px;
	height:233px;
	background:#e2fcf1;
	padding:6px;	margin-right:10px;	padding-top:15px;
}

.finwrap4Year{
	
	float:left;
	width:271px;
	height:233px;
	background:#f3f4e6;
	padding:6px;	margin-right:10px;	padding-top:15px;
}

.finselect{
	background-color:#77cea1;

	width:50px;
	color:#fff;
	font:bold 13px Arial, Helvetica, sans-serif;
	float:right;
	text-align:center;
	padding:4px;
}
	

</style>
			<table width="100%"	cellpadding="1" cellspacing="1" border="0" class="finance_table">
			<tr>
				<?php
				if($deposit)
				{
					$fin_deposit_amt = $deposit;
				}
				else
				{
					$fin_deposit_amt = $cartData["totals"]["bonus_price"]*(10/100);
				}	
				$fin_order_price = $cartData["totals"]["bonus_price"];
				$clr_i=1;
				while ($row_finance = $db->fetch_array($ret_finance))
				{
					$fin_id 		= $row_finance['finance_id'];
					$fin_code 		= $row_finance['finance_code'];
					$fin_name 		= $row_finance['finance_name'];
					$fin_rate 		= $row_finance['finance_rate'];
					$fin_loanamt	= $fin_order_price - $fin_deposit_amt;
					$tm_arr			= explode('-',$fin_code);
					$tm_arr1		= str_replace('ONIB','',$tm_arr[0]);
					$years			= $tm_arr1;
					$clr_i++;
					
					/* calculation formula 
					 EMI =    (p*r) (1+r)^n
								__________
								(1+r)^n - 1 
								 
						where r is to be calculated in months as  r/12/100		
					*/
					$fn_rinmonths = ($fin_rate/12/100); // rate converted to months
					$fn_1plusrraisedton = pow((1+$fn_rinmonths),$years); // (1+r)^n
					$fn_permonth = ($fin_loanamt*$fn_rinmonths) * (($fn_1plusrraisedton)/ (($fn_1plusrraisedton) -1));
					
					$fn_loanrepay = $fn_permonth * $tm_arr1;
					
					$fn_costofloan = $fn_loanrepay - $fin_loanamt;
					
					$fn_totalpayable = $fn_loanrepay + $fin_deposit_amt;
				?>
				<td align="left" width="33%">
					<div class="finwrap<?php echo $clr_i?>Year">
					<table width="100%"	cellpadding="1" cellspacing="1" border="0">
					 <tr>
					  <td width="10%" height="35" align="left" valign="middle"><input type="radio" name="finradio" id="radio" value="<?php echo $fin_id?>" <?php echo ($sel_code==$fin_id)?'checked':''?> />
						<label for="radio"></label></td>
					  <td width="90%" align="left" valign="middle"><strong><?php echo $fin_name?></strong></td>
					</tr>
					
					
					<tr>
					  <td colspan="2">
						  <table width="100%" border="0" cellspacing="0" cellpadding="5">
						<tr>
						  <td width="50%">Order Value</td>
						  <td width="50%" align="right"><?php echo print_price($fin_order_price,true);?></td>
						</tr>
						<tr>
						  <td>Deposit</td>
						  <td align="right"><?php echo print_price($fin_deposit_amt,true);?></td>
						</tr>
						<tr>
						  <td>Loan Amount</td>
						  <td align="right"><?php echo print_price($fin_loanamt,true);?></td>
						</tr>
						<tr>
						  <td height="26">Monthly Payment</td>
						  <td align="right"><div class="finselect"><?php echo print_price($fn_permonth,true);?></div></td>
						</tr>
						<tr>
						  <td>Loan Repayment</td>
						  <td align="right"><?php echo print_price($fn_loanrepay,true);?></td>
						</tr>
						<tr>
						  <td>Cost Of Loan</td>
						  <td align="right"><?php echo print_price($fn_costofloan,true);?></td>
						</tr>
						<tr>
						  <td>Total payable</td>
						  <td align="right"><?php echo print_price($fn_totalpayable,true);?></td>
						</tr>
					  </table></td>
					  </tr>
					 </table>
					</div>
				</td>
			<?php
			}
			?>
			<tr>
			<td colspan="3" width = "100%">
				<div class="disability_divA">
					<img border="0" title="New" src="<?php echo url_site_image('new_post.gif')?>"></img>
			Please note that your EMI finance example and monthly repayment are designed to give you an indication of how much you may expect to pay for your loan. Due to the many factors involved in 4 minute finance making their decisions, we cannot guarantee being able to obtain a loan at the above quoted rates / prices.
			</div>
			</td>
			</tr>
				</tr>
				</table>
			<?php
			}
		}
		// Defining function to show the checkout page
		function Show_Checkout()
		{
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents,$Settings_arr,$protectedUrl,$ecom_common_settings;
			global $show_cart_password,$ecom_themename;
			
			$session_id 			= session_id();	// Get the session id for the current section
			$cust_id				= get_session_var("ecom_login_customer"); // Get the customer id from session
			$Captions_arr['CART'] 	= getCaptions('CART'); // Getting the captions to be used in this page
			
			$cartData = cartCalc(); // Calling the function to calculate the details related to cart
			// Calling function to get the messages to be shown in the cart page
			$cart_alert = get_CartMessages($_REQUEST['hold_section']);
			// Calling function to get the checkout details saved temporarly for current site in current session
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
			
			if($_REQUEST['pret']==1) // case if coming back from PAYPAL with token.
			{
				if($_REQUEST['token'])
				{
					$address = GetShippingDetails($_REQUEST['token']);
					$ack = strtoupper($address["ACK"]);
					if($ack == "SUCCESS" ) // case if address details obtained correctly
					{
						$_REQUEST['payer_id'] = $address['PAYERID'];
						$cartData["payment"]["method"]['paymethod_key'] = 'PAYPAL_EXPRESS';
					}
					else // case if address not obtained from paypay .. so show the error msg in cart
					{
						$msg = 'CART_PAYPAL_EXP_NO_ADDRESS_RET';
						echo "<form method='post' action='http://$ecom_hostname/cart.html' id='cart_invalid_form' name='cart_invalid_form'><input type='hidden' name='hold_section' value='".$msg."' size='1'/><div style='position:absolute; left:0;top:0;padding:5px;background-color:#CC0000;color:#FFFFFF;font-size:12px;font-weight:bold'>Loading...</div></form><script type='text/javascript'>document.cart_invalid_form.submit();</script>";
						exit;
					}
				}	
			}
			
			
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
			//if($ecom_siteid==104)
			/*{
				if (!$row_cartdet['disable_id'])// case if disability is not selected
				{
						echo "<form method='post' action='http://$ecom_hostname/cart.html' id='cart_invalid_form' name='cart_invalid_form'><input type='hidden' name='hold_section' value='#incomp' size='1'/><div style='position:absolute; left:0;top:0;padding:5px;background-color:#CC0000;color:#FFFFFF;font-size:12px;font-weight:bold'>Loading...</div></form><script type='text/javascript'>document.cart_invalid_form.submit();</script>";
						exit;
				}
			}*/
			/*if ($row_cartdet['delopt_det_id']==0 || !$row_cartdet['delopt_det_id'])// case if disability is not selected
				{
						echo "<form method='post' action='http://$ecom_hostname/cart.html' id='cart_invalid_form' name='cart_invalid_form'><input type='hidden' name='hold_section' value='#incomp' size='1'/><div style='position:absolute; left:0;top:0;padding:5px;background-color:#CC0000;color:#FFFFFF;font-size:12px;font-weight:bold'>Loading...</div></form><script type='text/javascript'>document.cart_invalid_form.submit();</script>";
						exit;
				}	*/
			/*if ($saved_deposit<$tenper_deposit || $saved_deposit>$fiftyper_deposit)
			{
				        $msg =  $Captions_arr['CART']['CART_FINANCE_DEP'];
				        echo "<form method='post' action='http://$ecom_hostname/cart.html' id='cart_invalid_form' name='cart_invalid_form'><input type='hidden' name='hold_section_new' value='".$msg."' size='1'/><div style='position:absolute; left:0;top:0;padding:5px;background-color:#CC0000;color:#FFFFFF;font-size:12px;font-weight:bold'>Loading...</div></form><script type='text/javascript'>document.cart_invalid_form.submit();</script>";
						exit;
			}*/		
			// done to handle the case of protected or normal area	
			if($protectedUrl)
				$http = url_protected('index.php?req=cart&cart_mod=show_checkout',1);
			else 	
				$http = url_link('checkout.html',1);	
				
				/*<?php echo $http?>index.php?req=cart&cart_mod=show_checkout*/				
		?>	
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="">
<?php
if($_REQUEST['alert1']==1)
			{
			   $cart_alert .= $Captions_arr['CART']['CUST_REG_EMAIL_ALREADY_EXISTS'];
			}
			else if($_REQUEST['alert1']==2){
			    $cart_alert .= $Captions_arr['CART']['CUST_REG_VALID_EMAIL'];
				}
				if($cart_alert and count($cartData['products'])>0)
				{
			?>
					<tr>
						<td colspan="5" align="center" valign="middle" class="red_msg">
						- <?php echo $cart_alert?> -
						</td>
					</tr>
			<?php
				}
				?>
			  <tr>
				<td colspan="5" align="left" valign="middle"><div class="treemenu"><a href="http://<?php echo $ecom_hostname?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?php echo $Captions_arr['CART']['CART_CHECKOUTHEADING']?></div></td>
			  </tr>
			  </table>
			  <form method="post" name="frm_checkout"  id="frm_checkout" class="frm_cls" action="<?php echo $http?>" onsubmit="return validate_checkout_fields(document.frm_checkout)">
			<input type="hidden" name="fpurpose" id="fpurpose" value="place_order_preview" />
			<input type="hidden" name="remcart_id" id="remcart_id" value="" />
			<input type="hidden" name="cart_mod" id="cart_mod" value="show_checkout" />
			<input type="hidden" name="hold_section" id="hold_section" value="" />
			<input type="hidden" name="save_checkoutdetails" id="save_checkoutdetails" value="" />
			<input type="hidden" name="pret" value="<?php echo $_REQUEST['pret'];?>"/>
			<input type="hidden" name="token" value="<?php echo $_REQUEST['token'];?>"/>
			<input type="hidden" name="payer_id" value="<?php echo $_REQUEST['payer_id'];?>"/>
<div class="cartcontentsWrap">
<div class="customerboughtWrap">
<div class="customerboughtTop"></div>
<div class="customerboughtbg">
<div class="shipwrap">
<div class="shipTitle"><?php echo $Captions_arr['CART']['CART_FILL_BILLING_DETAILS']?></div>
<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
<?php
/*
?>
<tr>
				<td colspan="5" align="left" >
			<div class="cart_continue_div" align="left">
	             <input name="backto_cart" type="button" class="buttonred_cart" id="backto_cart" value="<?php echo $Captions_arr['CART']['CART_BACK_CARTPAGE']?>" onclick="show_wait_button(this,'Please Wait...');gobackto_cart('<?php echo ($protectedUrl)? base64_encode($ecom_hostname):$ecom_hostname?>')"/>
	         </div>
			   </td>
			  </tr> 
		  <?php
		  */ 
		  		  		// Initializing the array to hold the values to be used in the javascript validation of the static and dynamic fields

				$chkout_Req			= $chkout_Req_Desc = $chkout_Email = $chkout_Confirm = $chkout_Confirmdesc = array();
				$chkout_Numeric 	= $chkout_multi = $chkout_multi_msg	= array();

				// Including the file to show the dynamic fields for checkout to the top of static fields 		

		  		$head_class  			= 'shoppingcartheaderP';
				$cur_pos 				= 'Top';
				$section_typ			= 'checkout';
				$formname 				= 'frm_checkout';
				$head_class  			= 'shoppingcartheaderP';
				$colspan 				= '';
				$cont_leftwidth 		= '25%';
				$cont_rightwidth 		= '75%';
				$cellspacing 			= 1;
				$cont_class 			= 'col1	'; 
				$texttd_class           ='col2';
				$cellpadding 			= 3;			
				$colspan 	 			= 5;
				$table_class   = 'dyn_tableAB';
				include 'show_dynamic_fields.php';
		?><?php
				// Including the file to show the dynamic fields for checkout to the top of static fields in same section as that of static fields
				$colspan 		= 5;
				$head_class  	= 'shoppingcartheaderP';
				$cur_pos 		= 'TopInStatic';
				$section_typ	= 'checkout'; 
				$formname 	= 'frm_checkout';
				$head_class  	= 'shoppingcartheaderP';
				$colspan 		= '';
				$cont_leftwidth = '25%'; 
				$cont_rightwidth = '75%';
				$cellspacing 	= 1;
				$cont_class 	= 'col1';
				$texttd_class           ='col2';

				$cellpadding 	= 3;
				$colspan 	 	= 5;
				$table_class   = 'dyn_tableAB';
				include 'show_dynamic_fields.php';
			?>
		   <tr>
				<td colspan="5" align="right" valign="middle">
					<table width="100%" cellpadding="1" cellspacing="1" border="0" class="fieldA">
					<?php
						// Get the list of billing address static fields to be shown in the checkout out page in required order
						$sql_checkout = "SELECT field_det_id,field_key,field_name,field_req,field_error_msg   
											FROM 
												general_settings_site_checkoutfields 
											WHERE 
												sites_site_id = $ecom_siteid 
												AND field_hidden=0 
												AND field_type='PERSONAL' 
											ORDER BY 
												field_order";
						$ret_checkout = $db->query($sql_checkout);
						if($db->num_rows($ret_checkout))
						{						
							while($row_checkout = $db->fetch_array($ret_checkout))
							{// Section to handle the case of required fields

								if($row_checkout['field_req']==1)
								{
									if($row_checkout['field_key']=='checkout_email')
									{
										$chkout_Email[] = "'".$row_checkout['field_key']."'";
									}
									$chkout_Req[]		= "'".$row_checkout['field_key']."'";
									$chkout_Req_Desc[]	= "'".$row_checkout['field_error_msg']."'"; 
								}	
					?>
							<tr>
								<td align="right" width="25%" class="col1">
								<?php echo stripslashes($row_checkout['field_name']); if($row_checkout['field_req']==1) { echo '<span class="redtext">*</span>';}?>
								</td>
								<td align="left" width="75%" class="col2">
								<?php
									echo get_Field($row_checkout['field_key'],$saved_checkoutvals,$cartData['customer']);
								?>
								</td>
							</tr>
					<?php
							}
						}
						if($show_cart_password==1)
						{ 
							$chkout_Password[]		= "'checkout_passwd'";
							$chkout_Password_Desc[]	= "'".$Captions_arr['CART']['PASS_PASSWORD']."'";
							$chkout_Password[]		= "'checkout_pwd_cnf'";
							$chkout_Password_Desc[]	= "'".$Captions_arr['CART']['CONF_PASS_PASSWORD']."'";
						}
					?>
					</table>
				</td>
			</tr>
			<?php
			if($show_cart_password==1)
					{
			 ?>			<tr>
							<td colspan="5" align="left" valign="middle" class="shoppingcartcontentZ">
								<b><?=$Captions_arr['CART']['SHOW_PWD_CART_MESS']?></b>
							</td>
						</tr>
						<tr>
						<td colspan="5" align="left" valign="middle" class="shoppingcartcontentZ">
						<table width="100%" cellpadding="1" cellspacing="1" border="0">
						<tr>
						<td align="right" valign="middle" class="col1"><?=$Captions_arr['CART']['PASS_PASSWORD']?></td>
						<td align="left" valign="middle" class="col2"><input name="checkout_passwd" type="password" class="regiinput" id="checkout_passwd" size="25" value="" /></td>
						</tr>
						<tr>
						<td align="left" valign="middle" class="col1"><?=$Captions_arr['CART']['CONF_PASS_PASSWORD']?></td>
						<td align="left" valign="middle" class="col2"><input name="checkout_pwd_cnf" type="password" class="regiinput" id="checkout_pwd_cnf" size="25" value="" /></td>
						</tr>	
						</table>
						</td>
						</tr>
							<?php
					}
				// Including the file to show the dynamic fields for checkout to the bottom of static fields in same section as that of static fields
		  		$table_class   			= 'dyn_tableAB';
		  		$head_class  			= 'shoppingcartheaderP';
				$cur_pos 				= 'BottomInStatic';
				$section_typ			= 'checkout'; 
				$formname 				= 'frm_checkout';
				$head_class  			= '';
				$cellspacing			= 1;
				$cont_class 			= 'col1'; 
				$texttd_class           ='col2';

				$cellpadding			 = 3;
				$cont_leftwidth 		= '25%'; 
				$cont_rightwidth 		= '75%';
				$colspan 	 			= 5;
				include 'show_dynamic_fields.php';
			 // check whether billing and shipping address can be different in the site.
			  if ($Settings_arr['same_billing_shipping_checkout']==0)
			  {
			  ?>
			  	<tr>
					<td colspan="5" align="left" valign="middle" class="shoppingcartheader_noborder">
					<?php echo $Captions_arr['CART']['CART_IS_DELIVERY_ADDRESS_SAME']?>
					<select name="checkout_billing_same" onchange="javascript: handle_deliveryaddress_change(this);" >
					<option value="Y" <?php echo ($saved_checkoutvals['checkout_billing_same']=='Y')?'selected':''?>>Yes</option>
					<option value="N" <?php echo ($saved_checkoutvals['checkout_billing_same']=='N')?'selected':''?>>No</option>
					</select>
					</td>
				</tr>	
				<tr id="checkout_delivery_tr" <?php echo (($saved_checkoutvals['checkout_billing_same']=='Y') or ($saved_checkoutvals['checkout_billing_same']==''))?'style="display:none"':''?>>
					<td colspan="5" align="left" valign="middle" class="shoppingcartcontentC">
					<div class="shipTitle"><?php echo $Captions_arr['CART']['CART_FILL_DELIVERY_ADDRESS']?></div>
					<table width="100%" cellpadding="1" cellspacing="1" border="0" class="fieldA">
					
						<?php
							$chkoutdel_Email = $chkoutdel_Req = $chkoutdel_Req_Desc = array();
							// Get the list of delivery address static fields to be shown in the checkout out page in required order
							$sql_checkout = "SELECT field_det_id,field_key,field_name,field_req,field_error_msg   
												FROM 
													general_settings_site_checkoutfields
												WHERE 
													sites_site_id = $ecom_siteid 
													AND field_hidden=0 
													AND field_type='DELIVERY' 
												ORDER BY 
													field_order";
							$ret_checkout = $db->query($sql_checkout);
							if($db->num_rows($ret_checkout))
							{
								while($row_checkout = $db->fetch_array($ret_checkout))
								{
									// Section to handle the case of required fields
									if($row_checkout['field_req']==1)
									{
										if($row_checkout['field_key']=='checkoutdelivery_email')
										{
											$chkoutdel_Email[] = "'".$row_checkout['field_key']."'";
										}
										$chkoutdel_Req[]		= "'".$row_checkout['field_key']."'";
										$chkoutdel_Req_Desc[]	= "'".$row_checkout['field_error_msg']."'"; 		

									}
						?>
								<tr>
								<td align="right" width="25%" class="col1">
								<?php echo stripslashes($row_checkout['field_name']); if($row_checkout['field_req']==1) { echo '<span class="redtext">*</span>';}?>
								</td>
								<td align="left" width="75%" class="col2">
								<?php
									echo get_Field($row_checkout['field_key'],$saved_checkoutvals,$cartData);
								?>
								</td>
								</tr>
						<?php
								}
							}
						?>
						</table>
					</td>
				</tr>
			<?php
					
				}	
				else // case if shipping address 
				{
			?>
					<input type="hidden" name="checkout_billing_same" id="checkout_billing_same" value="Y" />
			<?php
				}
				// Including the file to show the dynamic fields for checkout to the bottom of static fields
				$colspan 				= 6;
		  		$head_class  			= 'shoppingcartheaderP';
				$cur_pos 				= 'Bottom';
				$section_typ			= 'checkout';
				$formname 			= 'frm_checkout';		

				$head_class  			= '';			

				$checkout_caption	= 'Continue Checkout';
				$cont_leftwidth 		= '25%';
				$cont_rightwidth		= '75%';
				$cellspacing 			= 1;
//				$cont_class = 'shoppingcartcontent_noborder';
				$cellpadding 			= 6;
								$texttd_class           ='col2';

				include 'show_dynamic_fields.php';			

				// Check whether credit card details is to be taken or not
				if($cartData["payment"]["method"]['paymethod_takecarddetails']==1)
				{
					$checkout_caption = 'Checkout';
			?>

					 <tr>
						<td colspan="5" align="left" valign="middle" class="shoppingcartcontentC">
						<table width="100%" cellpadding="0" cellspacing="0" border="0" class="fieldA">
						<tr>
						<td colspan="2" align="left" class="shoppingcartheaderP"><?php echo $Captions_arr['CART']['CART_CREDIT_CARD_DETAILS']?></td>
						</tr>
						<?php
							// Get the list of credit card static fields to be shown in the checkout out page in required order
							$sql_checkout = "SELECT field_det_id,field_key,field_name,field_req,field_error_msg   
												FROM 
													general_settings_site_checkoutfields 
												WHERE 
													sites_site_id = $ecom_siteid 
													AND field_hidden=0 
													AND field_type='CARD' 
												ORDER BY 
													field_order";
							$ret_checkout = $db->query($sql_checkout);
							if($db->num_rows($ret_checkout))
							{						
								while($row_checkout = $db->fetch_array($ret_checkout))
								{			
									// Section to handle the case of required fields
									if($row_checkout['field_req']==1)
									{
										if($row_checkout['field_key']=='checkoutpay_expirydate' or $row_checkout['field_key']=='checkoutpay_issuedate')
										{
											$chkout_Req[]		= "'".$row_checkout['field_key']."_month'";
											$chkout_Req_Desc[]	= "'".$row_checkout['field_error_msg']."'";
											$chkout_Req[]		= "'".$row_checkout['field_key']."_year'";
											$chkout_Req_Desc[]	= "'".$row_checkout['field_error_msg']."'"; 
										}
										else
										{
											$chkout_Req[]		= "'".$row_checkout['field_key']."'";
											$chkout_Req_Desc[]	= "'".$row_checkout['field_error_msg']."'"; 
										}	
									}		
						?>
								<tr>
								<td align="right" width="25%" class="col1">
								<?php echo stripslashes($row_checkout['field_name']); if($row_checkout['field_req']==1) { echo '<span class="redtext">*</span>';}?>
								</td>
								<td align="left" width="75%" class="col2">
								<?php
									echo get_Field($row_checkout['field_key'],$saved_checkoutvals,$cartData);
								?>
								</td>
								</tr>
						<?php
								}
							}
						?>
						</table>
						</td>
					 </tr>	
			<?php	
				}
				elseif($cartData["payment"]["method"]['paymethod_key']=='ABLE2BUY') // section to show the able to buy options
				{
					// Get the able2buy options set for current site
					$sql_able = "SELECT det_code,det_caption 
									FROM 
										payment_method_forsites_able2buy_details 
									WHERE 
									sites_site_id = $ecom_siteid 
										AND det_hidden = 0 
									ORDER BY 
										det_order";
					$ret_able = $db->query($sql_able);
					if ($db->num_rows($ret_able)==0)// just given for a precuation
					{
						echo '	<tr>
								<td colspan="6" align="center" valign="middle" class="red_msg">
								<br/><br/> --'.$Captions_arr['CART']['CART_NOT_ABLE_TO_BUY'].'-- <br/><br/>
								</td>
								</tr> ';
						exit;
					}
					else
					{					

			?>
					<tr>
						<td colspan="5" align="left" valign="middle">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td colspan="2" align="left" class="shoppingcartheader">
							<?php echo $Captions_arr['CART']['CART_ABLE_TO_BUY']?>
						</td>
						<?php	
							$checked = 'checked="checked"'; // to keep the first option selected always by default
							while ($row_able = $db->fetch_array($ret_able))
							{
						?>
								<tr>
									<td width="3%" align="left">
									<input type="radio" name="cgid" value="<?php echo $row_able['det_code']?>" <?php echo $checked?>>
								</td>
								<td width="90%" align="left" class="shoppingcartcontent_noborder">
								<?php echo stripslashes($row_able['det_caption'])?></td>
								</tr>
						<?php
								$checked ='';
							}						
						?>
						</table>
						</td>
					</tr>		
			<?php	
					}
					$checkout_caption = 'Checkout';
				}
				else// case if credit card details not to be picked
				{
					// Check which is the selected payment type
					switch($cartData["payment"]["type"])
					{	
						case 'cheque': // case of cheque
						?>
						<tr>
						<td colspan="5" align="left" valign="middle" class="shoppingcartheader">	
						<table width="100%" cellpadding="0" cellspacing="0" border="0" class="fieldA">
						<tr>
						<td colspan="2" align="left" class="shoppingcartheaderP"><?php echo $Captions_arr['CART']['CART_CHEQUE_DETAILS']?></td>
						</tr>
						<?php
							// Get the list of credit card static fields to be shown in the checkout out page in required order
							$sql_checkout = "SELECT field_det_id,field_key,field_name,field_req,field_error_msg   
											FROM 
													general_settings_site_checkoutfields 
												WHERE 
													sites_site_id = $ecom_siteid 
													AND field_hidden=0 
													AND field_type='CHEQUE' 
												ORDER BY 
													field_order";
							$ret_checkout = $db->query($sql_checkout);
							if($db->num_rows($ret_checkout))
							{						
								while($row_checkout = $db->fetch_array($ret_checkout))
								{			
									// Section to handle the case of required fields
									if($row_checkout['field_req']==1)
									{
										$chkout_Req[]		= "'".$row_checkout['field_key']."'";
										$chkout_Req_Desc[]	= "'".$row_checkout['field_error_msg']."'"; 
									}			
						?>
								<tr>
								<td align="right" width="50%" class="col1">
								<?php echo stripslashes($row_checkout['field_name']); if($row_checkout['field_req']==1) { echo '<span class="redtext">*</span>';}?>
								</td>
								<td align="left" width="50%" class="col2">
								<?php
									echo get_Field($row_checkout['field_key'],$saved_checkoutvals,$cartData);
								?>
								</td>
								</tr>
						<?php
								}
							}
						?>
						</table>
						</td>
					 	</tr>
						<?php	
							$checkout_caption = 'Checkout';
						break;
						case 'invoice':
							$checkout_caption = 'Place Invoice';
						break;
						case 'cash_on_delivery':
							$checkout_caption = 'Checkout';
						break;
						case 'pay_on_phone':
							$checkout_caption = 'Checkout';
					};
				}	
			?>
			  
                           
</table>
<div>
   </div>
            
      <div>
       </div>
         </div>
</div>
<div class="customerboughtBottom"></div>
</div>
<script type="text/javascript">
		function validate_checkout_fields(frm)
		{
			<?php
				// Blank checking
				if (count($chkout_Req))
				{
					$chkout_Req_Str 			= implode(",",$chkout_Req);
					$chkout_Req_Desc_Str 		= implode(",",$chkout_Req_Desc);
					echo "fieldRequired 		= Array(".$chkout_Req_Str.");";
					echo "fieldDescription 		= Array(".$chkout_Req_Desc_Str.");";
				}
				else
				{
					echo "fieldRequired 		= Array();";
					echo "fieldDescription 		= Array();";
				}	
				// Email checking
				if (count($chkout_Email))
				{
				$chkout_Email_Str = implode(",",$chkout_Email);
					echo "fieldEmail 		= Array(".$chkout_Email_Str.");";
				}
				else
					echo "fieldEmail 		= Array();";
				// Password checking
				if (count($chkout_Confirm))
				{
					$chkout_Confirm_Str 	= implode(",",$chkout_Confirm);
					$chkout_Confirmdesc_Str	= implode(",",$chkout_Confirmdesc);
					echo "fieldConfirm 		= Array(".$chkout_Confirm_Str.");";
					echo "fieldConfirmDesc 	= Array(".$chkout_Req_Desc_Str.");";
				}
				else
				{
					echo "fieldConfirm 		= Array();";
					echo "fieldConfirmDesc 	= Array();";
				}	
				// Numeric checking
				if (count($chkout_Numeric))
				{
					$chkout_Numeric_Str 	= implode(",",$chkout_Numeric);
					echo "fieldNumeric 		= Array(".$chkout_Numeric_Str.");";
				}
				else
					echo "fieldNumeric 		= Array();";
				?>
			if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric))
			{
				if(frm.checkout_billing_same.value == 'N')
				{
					<?php
					// Blank checking
					if (count($chkoutdel_Req))
					{
						$chkoutdel_Req_Str 			= implode(",",$chkoutdel_Req);
						$chkoutdel_Req_Desc_Str 	= implode(",",$chkoutdel_Req_Desc);
						echo "fielddelRequired 		= Array(".$chkoutdel_Req_Str.");";
						echo "fielddelDescription 	= Array(".$chkoutdel_Req_Desc_Str.");";
					}
					else
					{
						echo "fielddelRequired 		= Array();";
						echo "fielddelDescription 	= Array();";
					}	
					// Email checking
					if (count($chkoutdel_Email))
					{
						$chkoutdel_Email_Str = implode(",",$chkoutdel_Email);
						echo "fielddelEmail 		= Array(".$chkoutdel_Email_Str.");";
					}
					else
						echo "fielddelEmail 		= Array();";
					// Password checking
					if (count($chkoutdel_Confirm))
					{
						$chkoutdel_Confirm_Str 	= implode(",",$chkoutdel_Confirm);
						$chkoutdel_Confirmdesc_Str	= implode(",",$chkoutdel_Confirmdesc);
						echo "fielddelConfirm 		= Array(".$chkoutdel_Confirm_Str.");"; 
						echo "fielddelConfirmDesc 	= Array(".$chkoutdel_Confirmdesc_Str.");";
					}
					else
					{
						echo "fielddelConfirm 		= Array();";
						echo "fielddelConfirmDesc 	= Array();";
					}	
					// Numeric checking
					if (count($chkoutdel_Numeric))
					{
						$chkoutdel_Numeric_Str 	= implode(",",$chkoutdel_Numeric);
						echo "fielddelNumeric 		= Array(".$chkoutdel_Numeric_Str.");";
					}
					else
						echo "fielddelNumeric 		= Array();";
			?>
					if(!Validate_Form_Objects(frm,fielddelRequired,fielddelDescription,fielddelEmail,fielddelConfirm,fielddelConfirmDesc,fielddelNumeric))
						return false;
				}

				<?php
			if($show_cart_password==1)
			{
			?>
				fieldddelConfirm 		= Array(<?php echo implode(',',$chkout_Password)?>);
				fieldddelConfirmDesc 	= Array(<?php echo implode(',',$chkout_Password_Desc)?>);
				fieldddelRequired 		= Array();
				fieldddelDescription 	= Array();
				fieldddelEmail 		= Array();
				fieldddelNumeric 	= Array();
					if(!Validate_Form_Objects(frm,fieldddelRequired,fieldddelDescription,fieldddelEmail,fieldddelConfirm,fieldddelConfirmDesc,fieldddelNumeric))
					{
						return false;
					}					
			<?php	
			}			
			?>		
				/* Checking the case of checkboxes or radio buttons */
				<?php
					if (count($chkout_multi))
					{
						for ($i=0;$i<count($chkout_multi);$i++)
						{
							echo 
								"var atleast_one = false;
									for(j=0;j<frm.elements.length;j++)
									{
										if (frm.elements[j].type=='checkbox' || frm.elements[j].type=='radio')
										{
											if (frm.elements[j].name=='".$chkout_multi[$i]."'+'[]')
											{
												if(frm.elements[j].checked==true)
													atleast_one = true;
											}		
										}
									}
									if (atleast_one == false)
									{
										alert('".$chkout_multi_msg[$i]."');
										document.getElementById('".$chkout_multi[$i]."'+'[]').focus();
										return false;
									}									
								";	
						}
					}
					?>
				
				/* Handling the case of credit card related sections*/
				if(frm.checkoutpay_cardtype)
				{
					if(frm.checkoutpay_cardtype.value)
					{
						objarr = frm.checkoutpay_cardtype.value.split('_');
						if(objarr.length==4) /* if the value splitted to exactly 4 elements*/
						{
							var key 		= objarr[0];
							var issuereq 	= objarr[1];
							var seccount 	= objarr[2];
							var cc_count 	= objarr[3];
							if (isNaN(frm.checkoutpay_cardnumber.value))
							{
								alert('Credit card number should be numeric');
								frm.checkoutpay_cardnumber.focus();
							    return false;
							}
							if (frm.checkoutpay_cardnumber.value.length>cc_count)
							{
								alert('Credit card number should not contain more than '+cc_count+' digits');
								frm.checkoutpay_cardnumber.focus();
							    return false;
							}
							if (frm.checkoutpay_securitycode.value.length>seccount)
							{
								alert('Security Code should not contain more than '+seccount+' digits');
								frm.checkoutpay_securitycode.focus();
								return false;
							}
						}
					}
				}		
				/* Handling the case of terms checkbox in checkout page*/
				for (i=0;i<frm.elements.length;i++)
				{
					if (frm.elements[i].type=='checkbox' && frm.elements[i].name.substr(0,10)=='cart_terms')
					{
						if (frm.elements[i].checked==false)/* Case if checkbox not checked*/
						{
							alert('Please accept the terms and conditions');
							frm.elements[i].focus();
							return false;
						}
					}
				}
				if(document.getElementById('save_checkoutdetails'))
					document.getElementById('save_checkoutdetails').value  	= 1;
				document.getElementById('cart_mod').value 					= 'show_orderplace_preview';	
				document.getElementById('continue_checkout').style.display	= 'none';
				document.getElementById('backto_cart').style.display		= 'none';
				document.getElementById('checkout_msg_div').style.display	= 'block';
				return true;
			}	
			else
				return false;
			}	
		</script>
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
				  ?><?
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
								<td class="totalsHead"><?php echo $Captions_arr['CART']['CART_TOTPRICE']?></td>
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
								<td  align="right" valign="middle" class="totalsHead"><?php echo $Captions_arr['CART']['CART_DELIVERY_LOC']?></td><td align="right" class="total"> <?php echo stripslashes($row_loc['location_name'])?></td>
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
		  </table></td></tr></table> </td>
						</tr>
						<?php
					}
						?>
						</tbody></table>
					</div></div>
						<div class="summarywrapbottom"></div>
						</div>
						<?PHP
						if ($showdisplayHtml=="")
						{ 
						require("themes/$ecom_themename/html/intercartHtml.php");
						$showdisplayHtml= new showdisplay_Html(); // Creating an object for the cart_Html class
						//$showdisplayHtml->show_reviews();
						}	
						?>
  </div>
  <div class="bordercont">
  <table border="0" width="100%" cellpadding="0" cellspacing="0" class="bordercontA">
  <tr>
				<td colspan="5" align="right" valign="middle" class="cartterms">			
					 <?php
						if($Settings_arr['terms_and_condition_at_checkout'])
						{
					?>
							<br /><input class="shoppingcart_radio" type="checkbox" name="cart_terms" id="cart_terms" value="1" /><?php echo $Captions_arr['CART']['CART_ACCEPT_TERMS_CONDITIONS']?>
					<?php	
						}
					?>	
				</td>
			  </tr>
			<tr>
            <td colspan="5" align="right" valign="middle" class="shoppingcartcontent">			
            <div class="cart_continue_div" align="left">
	             <input name="backto_cart" type="button" class="buttonred_cart_continue_small" id="backto_cart" value="<?php echo $Captions_arr['CART']['CART_BACK_CARTPAGE']?>" onclick="show_wait_button(this,'Please Wait...');gobackto_cart('<?php echo ($protectedUrl)? base64_encode($ecom_hostname):$ecom_hostname?>')"/>
	         </div>    
             <?php
			 // Handling the case of login required before checkout.
				$show_checkoutbutton = true;
				if($Settings_arr['forcecustomer_login_checkout'] and !$cust_id)
				{
					$show_checkoutbutton = false;
				}
				if($show_checkoutbutton==true)	
				{
			  ?>	
			  		<div class="cart_checkout_div" align="right">
					<?php
					$prev_req = 0;
					if($cartData["payment"]["method"]['paymethod_key']=='GOOGLE_CHECKOUT')
					{
						$google_prev_req 		= $ecom_common_settings['paymethodKey']['GOOGLE_CHECKOUT']['payment_method_preview_req'];
						$prev_req 				= $google_prev_req;
					}
					else
						$prev_req = 1;
					if ($prev_req==1)
					{
							if($_REQUEST['pret']==1) // case of showing paypal payment confirmation button
							{
						?>	
								<?php /*?><img src="<?php echo url_site_image('paypal_checkout_logo.gif')?>" border="0" align="absbottom" /><?php */?><?php
								$checkout_caption	= "Make Payment";
							}
					?>
							<input name="continue_checkout" type="submit" class="buttonred_cart" id="continue_checkout" value="<?php echo $checkout_caption?>"/>
					<?php
					}
					else
					{
					?>
						<input type="image" name="continue_checkout" src="https://checkout.google.com/buttons/checkout.gif?merchant_id=711690661192356&amp;w=160&amp;h=43&amp;style=white&amp;variant=text&amp;loc=en_GB" id="continue_checkout" border="0" style="border:0" />
					<?php	
					}
					?>
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
  </table>
  </div>
  </form>

		 <?php
		 	
		}
		// Defining function to show the Order Preview page
		function Show_OrderPreview($return_order_arr)
		{
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,
					$Captions_arr,$inlineSiteComponents,$sitesel_curr,$default_Currency_arr,$ecom_testing,$ecom_themename,$components,
					$ecom_common_settings;
			$session_id 			= $sessid = session_id();	// Get the session id for the current section		
			$Captions_arr['CART'] 	= getCaptions('CART'); // Getting the captions to be used in this page
			$cust_id				= get_session_var("ecom_login_customer"); // Get the customer id from session
			$cartData 				= cartCalc(); // Calling the function to calculate the details related to cart
			
			// Get the details regarding current order from orders table
			$back_to_cart = false;
			/* Donate bonus Start */
			$sql_ord = "SELECT order_id,order_date,order_custtitle,order_custfname,order_custmname,order_custsurname,
								order_custcompany,order_buildingnumber,order_street,order_city,order_state,order_country,
								order_custpostcode,order_custphone,order_custfax,order_custmobile,order_custemail,
								order_notes,order_giftwrap,order_giftwrap_per,order_giftwrapmessage,order_giftwrapmessage_text,
								order_giftwrap_message_charge,order_giftwrap_minprice,order_giftwraptotal,
								order_deliverytype,order_deliverylocation,order_delivery_option,order_deliveryprice_only,
								order_deliverytotal,order_splitdeliveryreq,order_extrashipping,order_bonusrate,
								order_bonuspoint_discount,order_bonuspoints_used,order_bonuspoint_inorder,order_paymenttype,
								order_paymentmethod,order_paystatus,order_hide,order_status,order_deposit_amt,
								order_deposit_cleared,order_currency_code,order_currency_convertionrate,order_tax_total,
								order_tax_to_delivery,order_tax_to_giftwrap,order_customer_or_corporate_disc,
								order_customer_discount_type,order_customer_discount_percent,order_customer_discount_value,
								order_totalprice,order_subtotal,order_pre_order,gift_vouchers_voucher_id,
								order_gift_voucher_number,promotional_code_code_id,promotional_code_code_number,
								order_bonuspoints_donated,disable_id   
							FROM 
								orders 
							WHERE 
								order_id = ".$return_order_arr['order_id']." 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			/* Donate bonus End */
			$ret_ord = $db->query($sql_ord);
			if ($db->num_rows($ret_ord))
			{
				$row_ord = $db->fetch_array($ret_ord);
			}
			else
			{	
				$back_to_cart = true;
			}	
			if ($back_to_cart==false)
			{
				// Get the delivery address details for current order
				$sql_del = "SELECT delivery_id,delivery_title,delivery_fname,delivery_mname,delivery_lname,
									delivery_companyname,delivery_buildingnumber,delivery_street,
									delivery_city,delivery_state,delivery_country,delivery_zip,
									delivery_phone,delivery_fax,delivery_mobile,delivery_email,
									delivery_completed,delivery_same_as_billing 
								FROM 
									order_delivery_data 
								WHERE 
									orders_order_id = ".$return_order_arr['order_id']." 
								LIMIT 
									1";
				$ret_del = $db->query($sql_del);
				if($db->num_rows($ret_del))
				{
					$row_del = $db->fetch_array($ret_del);
					// Get the products in current order
					$sql_orddet = "SELECT orderdet_id,products_product_id,product_name,order_qty,product_soldprice,
										order_retailprice,order_discount,order_discount_type,order_rowtotal,
										order_preorder,order_deposit_percentage,order_deposit_value,
										order_stock_combination_id,order_detail_discount_type 
									FROM 
										order_details 
									WHERE 
										orders_order_id = ".$return_order_arr['order_id']." 
										AND order_delivery_data_delivery_id = ".$row_del['delivery_id'];
					$ret_orddet = $db->query($sql_orddet);
					if ($db->num_rows($ret_orddet)==0)
					{
						$back_to_cart = true;
					}
										
				}	
				
			}	
			if($back_to_cart)
			{
				// If order is fake then redirect back to cart page
				echo "<script type='text/javascript'>window.location='http://".$ecom_hostname."/cart.html'</script>";
				exit;	
			}	
			if($row_ord['order_paymentmethod']!='') // If payment method exits
			{
				if($ecom_common_settings['paymethodKey'][$row_ord['order_paymentmethod']]['payment_method_preview_req']==1)
					$auto_submit	= false;
				else
					$auto_submit	= true;
			}
			else // If payment method does not exists only payment type only exits
				$auto_submit	= false;
		?>	
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="">
			 <?php
			if($auto_submit==false)
			{
			?>
			  <tr>
				<td colspan="5" align="left" valign="middle"><div class="treemenu"><a href="http://<?php echo $ecom_hostname?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?php echo $Captions_arr['CART']['CART_CHECKOUTPREVIEWHEADING']?></div></td>
			  </tr>
			 <?php
			 }
			 ?> 
			 <tr>
				<td colspan="5" align="right" valign="middle" >
				<?php
				$display_option = 'ALL';
				// Including the file which hold the login for fields to be passed to payment gateway
				include 'order_preview_gateway_include.php';
				?>		 
				</td>
			</tr>
			  </table>
			 
<div class="cartcontentsWrap">
<div class="customerboughtWrap">
<div class="customerboughtTop"></div>
<div class="customerboughtbg">
<div class="shipwrap">
<div class="shipTitle"><?php echo $Captions_arr['CART']['CART_BILL_DETAILS']?></div>
<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
<?php
			if($auto_submit==false)
			{
				
				// Including the file to show the dynamic fields for orders to the give position of static fields
				$cur_pos 		= 'Top';
				$show_header	= 1;
				include 'show_dynamic_fields_orders.php';
		?>	
			
			<?php
					// Including the file to show the dynamic fields for orders to the give position of static fields
					$cur_pos 		= 'TopInStatic';
					$show_header	= 0;
					include 'show_dynamic_fields_orders.php';
					?>
					 <tr>
				<td colspan="5" align="right" valign="middle">
					<table width="100%" cellpadding="1" cellspacing="1" border="0" class="fieldA">
					<?php
					// Get the list of billing address static fields to be shown in the checkout out page in required order
					$sql_checkout = "SELECT field_name,field_orgname 
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
						while($row_checkout = $db->fetch_array($ret_checkout))
						{		
				?>
							<tr>
								<td align="left" colspan="2" class="col1" width="25%">
								<?php echo stripslashes($row_checkout['field_name'])?>
								</td>
								<td align="left" colspan="3" width="75%" class="col2">
								<?php
									echo stripslashes($row_ord[$row_checkout['field_orgname']]);
								?>
								</td>
							</tr>
				<?php
						}
					}
					?>
					</table>
				</td>
			</tr>
					<?php
				// Including the file to show the dynamic fields for orders to the give position of static fields
				$cur_pos 		= 'BottomInStatic';
				$show_header	= 0;
				//include 'show_dynamic_fields_orders.php';
				?>
				<tr>
				<td colspan="5" align="right" valign="middle">
					<?php
					 // check whether billing and shipping address can be different in the site.
			  if ($row_del['delivery_same_as_billing']==1)
			  {
			  ?>
			  	<div class="shipTitle">
					<?php echo $Captions_arr['CART']['CART_DELIVERY_SAME_BILLADDRESS']?>
					</div>	
			<?php
				}
				else
				{
					?>
					<div class="shipTitle">
					<?php echo $Captions_arr['CART']['DELIVERY_ADDRESS']?>
					</div>	
					<?php
				}
				?>
					<table width="100%" cellpadding="1" cellspacing="1" border="0" class="fieldA">
				<?php
			  // check whether billing and shipping address can be different in the site.
			  if ($row_del['delivery_same_as_billing']==1)
			  {
			  ?>
			  	
			<?php
				}
				else
				{				
				
					// Get the list of billing address static fields to be shown in the checkout out page in required order
					$sql_checkout = "SELECT field_name,field_orgname 
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
						while($row_checkout = $db->fetch_array($ret_checkout))
						{		
				?>
							<tr>
								<td align="left" colspan="2" class="col1" width="25%">
								<?php echo stripslashes($row_checkout['field_name'])?>
								</td>
								<td align="left" colspan="3" class="col2" width="75%">
								<?php
									echo stripslashes($row_del[$row_checkout['field_orgname']]);
								?>
								</td>
							</tr>
				<?php
						}
					}		

				 }
			  ?>
			  	</table>
					</td>
				</tr>
			  <?php
				// Including the file to show the dynamic fields for orders to the give position of static fields
				$cur_pos 		= 'Bottom';
				$show_header	= 1;
				include 'show_dynamic_fields_orders.php';
				
			
			  
			}	
			  ?>			  
			<tr>
            <td colspan="5" align="right" valign="middle" class="">	
           	<?php
				/*switch($return_order_arr['payMethod']['paymethod_key'])
				{
					case 'ABLE2BUY': // Display the form for able2buy
						$button_maincaption = 'Confirm Order';
						$button_clickmsg	= 'Please wait...';
						$button_class		= 'buttonred_cart';
						include 'includes/able2verify.php';
					break;
					case 'WORLD_PAY': // Display the form for worldpay
						$button_maincaption = 'Confirm Order';
						$pass_type			= 'order';
						$button_clickmsg	= 'Please wait...';
						$button_class		= 'buttonred_cart';
						include 'includes/world_pay.php';
					break;
					case 'GOOGLE_CHECKOUT':
						require_once('includes/google_library/googlecart.php');
						require_once('includes/google_library/googleitem.php');
						require_once('includes/google_library/googleshipping.php');
						require_once('includes/google_library/googletax.php');
						include("includes/google_checkout.php");
					break;
					case 'HSBC':
						$button_maincaption 		= 'Confirm Order';
						$button_clickmsg			= 'Please wait...';
						$button_class				= 'buttonred_cart';
						include("includes/hsbc_pay.php");
					break;
					case 'PROTX_VSP':
						$button_maincaption 	= 'Confirm Order';
						$pass_type					= 'order';
						$button_clickmsg		= 'Please wait...';
						$button_class				= 'buttonred_cart';
						include 'includes/protx_vsp.php';
					break;
				};*/
				$display_option = 'BUTTON_ONLY';
				// Including the file which hold the login for fields to be passed to payment gateway
				//include 'order_preview_gateway_include.php';
			?>			
			</td>
          </tr>                           
</table>
<div>
   </div>
            
      <div>
       </div>
         </div>
</div>
<div class="customerboughtBottom"></div>
</div>
<?php
			if($auto_submit==false)
			{
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
		  
		      // Following section iterate through the products in cart and show its list
			  while($row_orddet = $db->fetch_array($ret_orddet))
			  {
				$vars_exists = false;
				// Check whether variables exists for current product in order
				$sql_vars = "SELECT var_id,var_name,var_value,var_price 
								FROM 
									order_details_variables 
								WHERE 
									orders_order_id = ".$return_order_arr['order_id']." 
									AND order_details_orderdet_id =".$row_orddet['orderdet_id'];
				$ret_vars = $db->query($sql_vars);
				
				// Check whether messages exists for current product in order
				$sql_msgs = "SELECT message_id,message_caption,message_value  
								FROM 
									order_details_messages  
								WHERE 
									orders_order_id = ".$return_order_arr['order_id']." 
									AND order_details_orderdet_id =".$row_orddet['orderdet_id'];
				$ret_msgs = $db->query($sql_msgs);
				
				if ($db->num_rows($ret_vars) or $db->num_rows($ret_msgs))  // Check whether variable of messages exists
				{
					$vars_exists 	= true;
					$trmainclass		= 'shoppingcartcontent_noborder';
					$tdpriceBclass		= 'shoppingcartpriceB_noborder';
					$tdpriceAclass		= 'shoppingcartpriceA_noborder';
				}
				else
				{
					$trmainclass 		= 'shoppingcartcontent';	
					$tdpriceBclass		= 'shoppingcartpriceB';
					$tdpriceAclass		= 'shoppingcartpriceA';
				}	
			  ?>
				 <tr>						
							<td class="image" valign="top">
					<?php 
					// Check whether thumb nail is to be shown here
					if ($Settings_arr['thumbnail_in_viewcart']==1)
					{
					?>
						<a class="cart_img_link" href="<?php url_product($row_orddet['products_product_id'],$row_orddet['product_name'],-1)?>" title="<?php echo stripslashes($row_orddet['product_name'])?>">
						<?php
							// Calling the function to get the image to be shown
							$pass_type = get_default_imagetype('cart');
							//$img_arr = get_imagelist('prod',$row_orddet['products_product_id'],$pass_type,0,0,1);
							// Check whether combination image is allowed for this product
							$sql_checkprod = "SELECT product_variablecombocommon_image_allowed 
												FROM 
													products 
												WHERE 
													product_id = ".$row_orddet['products_product_id']." 
												LIMIT 
													1";
							$ret_checkprod = $db->query($sql_checkprod);
							if($db->num_rows($ret_checkprod))
							{
								$row_checkprod = $db->fetch_array($ret_checkprod);
							}
							if($row_orddet['order_stock_combination_id'] and $row_checkprod['product_variablecombocommon_image_allowed']=='Y')
								$img_arr = 	get_imagelist_combination($row_orddet['order_stock_combination_id'],$pass_type,0,1);
							else
								$img_arr = get_imagelist('prod',$row_orddet['products_product_id'],$pass_type,0,0,1);
							if(count($img_arr))
							{
								show_image(url_root_image($img_arr[0][$pass_type],1),$row_orddet['product_name'],$row_orddet['product_name']);
							}
							else
							{
								// calling the function to get the default image
								$no_img = get_noimage('prod',$pass_type); 
								if ($no_img)
								{
									show_image($no_img,$row_orddet['product_name'],$row_orddet['product_name']);
								}	
							}	
						?>
						</a><br />
					<?php
					}
					?>
					</td>
						     <td class="desc" valign="top">
							<a href="<?php echo url_product($row_orddet['product_id'],$row_orddet['product_name'])?>" title="<?php echo stripslashes($row_orddet['product_name'])?>" class="newCartTitle"><?php echo stripslashes($row_orddet['product_name'])?></a>
						</td><td align="center" valign="top" class="qty">
					<?php 
						// Get the required details from products table
						$sql_checkprods = "SELECT product_det_qty_type,product_det_qty_drop_prefix,product_det_qty_drop_suffix 
												FROM 
													products 
												WHERE 
													product_id = ".$row_orddet['products_product_id']." 
												LIMIT 
													1";
						$ret_checkprods = $db->query($sql_checkprods);
						if ($db->num_rows($ret_checkprods))
						{
							$row_checkprods = $db->fetch_array($ret_checkprods);
						}
						if($row_checkprods['product_det_qty_type']=='DROP')
						{
							if (trim($row_checkprods['product_det_qty_drop_prefix'])!='')
								echo stripslashes($row_checkprods['product_det_qty_drop_prefix']).' ';
							echo $row_orddet["order_qty"];
							if (trim($row_checkprods['product_det_qty_drop_suffix'])!='')
								echo ' '.stripslashes($row_checkprods['product_det_qty_drop_suffix']);
						}
						else
							echo $row_orddet["order_qty"]
						?>
					</td>
					<td class="price" valign="top"><?php echo print_price($row_orddet['order_rowtotal'],true)?></td>
				  </tr>
				  <?php
				  	// If variables exists for current product, show it in the following section
					if ($vars_exists) 
					{
				  ?>
					<tr>
						<td colspan="4">
						<?
						
						// show the variables for the product if any
						if ($db->num_rows($ret_vars))
						{
							//print_r($products_arr['prod_vars']);
							while($row_vars = $db->fetch_array($ret_vars))
							{
								if (trim($row_vars['var_value'])!='')
									print "<span class='cartvariable'>".$row_vars['var_name'].": ". $row_vars['var_value']."</span><br />"; 
								else
									print "<span class='cartvariable'>".$row_vars['var_name']."</span><br />"; 
									
							}	
						}
						// Show the product messages if any
						if ($db->num_rows($ret_msgs)) 
						{	
							while($row_msgs = $db->fetch_array($ret_msgs))
							{
								print "<span class='cartvariable'>".stripslashes($row_msgs['message_caption']).": ".stripslashes($row_msgs['message_value'])."</span><br />"; 
							}	
						}	
					?>
					</td>
					</tr>	
			  <?php
				}
			  }
		  
               ?>
               <tr>
						<td colspan="4">
						<table cellpadding="2" cellspacing="2" class="summaryTotals">						   
							<tbody>
							  <tr>
								<td  class="totalsHead"><?php echo $Captions_arr['CART']['CART_TOTPRICE']?>&nbsp;</td>
								<td align="right" class="total"><?php echo print_price($row_ord["order_subtotal"],true)?></td>
							</tr>
							<?php
							if ($row_ord['order_deliverytype'] != 'None') // Check whether any delivery method is selected for the site
			{
				 	// Case if location is to be displayed
					if ($row_ord['order_deliverylocation'])
					{
									
				?>	
							<tr>
								<td  align="right" valign="middle" class="totalsHead"><?php echo $Captions_arr['CART']['CART_DELIVERY_LOC']?></td><td align="right" class="total"> <?php echo stripslashes($row_ord['order_deliverylocation'])?></td>
							</tr>
				<?php
					}
						
					
				if ($row_ord['order_splitdeliveryreq']==1)
				{
				?>
					<tr>
						<td  align="left" valign="middle" class="totalsHead"><?php echo $Captions_arr['CART']['CART_DELIVERY_SPLIT_ACTIVATED']?></td>
						<td  align="left" valign="middle" class="total"><?php echo $Captions_arr['CART']['CART_YES']?></td>
					</tr>
				<?php	
				}
				// Check whether delivery is charged, then show the total after applying delivery charge
				if($row_ord["order_deliverytotal"]>0)
				{
				?>
				  <tr>
					<td  align="right" valign="middle" class="totalsHead"><?php echo $Captions_arr['CART']['CART_TOTAL_DELIVERY_CHARGES']?></td>
					<td align="right" valign="middle" class="total"><?php echo print_price($row_ord["order_deliverytotal"],true)?></td>
				  </tr>
		  <?php
		  		}
		  	} 
		  	// Section to show the extra shipping cost
			 if($row_ord["order_extrashipping"]>0)
			 {
			 ?>
				 <tr>
					<td  align="right" valign="middle" class="totalsHead"><?php echo $Captions_arr['CART']['CART_EXTRA_SHIPPING']?></td>
					<td align="right" valign="middle" class="total"><?php echo print_price($row_ord["order_extrashipping"],true)?></td>
				  </tr>
			 <?php	
			 }
			 // Section to show the tax details
			 if($row_ord["order_tax_total"]>0)
			 {
			 	// Get the tax details from order_tax_details
				$sql_tax = "SELECT tax_name,tax_percent,tax_charge 
								FROM 
									order_tax_details 
								WHERE 
									orders_order_id = ".$return_order_arr['order_id'];
				$ret_tax = $db->query($sql_tax);
				if ($db->num_rows($ret_tax))
				{
			 ?>
					<tr>
						<td  align="right" valign="top" class="totalsHead" >
						<?php echo $Captions_arr['CART']['CART_TAX_CHARGE_APPLIED']?>
						<?	
							$charge_arr = array();
							while($row_tax = $db->fetch_array($ret_tax))
							{
								echo '<br/>('.$row_tax['tax_name']; ?> @ <? print $row_tax['tax_percent']; ?>%)
						<?	
								$charge_arr[] = print_price($row_tax['tax_charge']);
							}
						?>						</td>
						<td align="right" valign="top" class="total">
						<?	
							for($i=0;$i<count($charge_arr);$i++)
							{
								echo $charge_arr[$i]?> <br />
						<?	
							}
						?>
						</td>
					</tr>
				<?php
				}
			}
			  // Check whether customer or corporate discount is allowed
			 if ($row_ord['order_customer_discount_value']>0)
			 {
			 ?>
				<tr>
					<td  align="right" valign="middle" class="totalsHead" >
					<?php
						if ($row_ord["order_customer_or_corporate_disc"]=='CUST' and $row_ord['order_customer_discount_value']>0)
						{
							if ($row_ord["order_customer_or_corporate_disc"]=='Disc_Group') // check whether discount is due to customer group
								echo 'Customer Group Discount ('.$row_ord["order_customer_discount_percent"].'%)&nbsp;';
							else // case if discount is due to discount set for current customer
								echo 'Customer Discount ('.$row_ord["order_customer_discount_percent"].'%)&nbsp;';
						}	
						elseif ($row_ord["order_customer_or_corporate_disc"]=='CORP' and $row_ord['order_customer_discount_value']>0)
							echo 'Corporate Discount ('.$row_ord['order_customer_discount_percent'].'%)&nbsp;';
					?></td>
					<td align="right" valign="middle" class="total">
					<?php 
						echo print_price($row_ord['order_customer_discount_value'],true);
					?>
					</td>
				</tr>
			 <?php
			}
			// If gift voucher or promotional code is valid
				if (($row_ord['promotional_code_code_id']!=0 or $row_ord['gift_vouchers_voucher_id']!=0))
				{
					if($row_ord['promotionalcode_id']!=0)
					{
						$sql_prom = "SELECT code_type,code_number,code_lessval 
										FROM 
											order_promotional_code 
										WHERE 
											orders_order_id = ".$return_order_arr['order_id']." 
											AND promotional_code_code_id=".$row_ord['promotional_code_code_id']." 
										LIMIT 
											1";
						$ret_prom = $db->query($sql_prom);
						if ($db->num_rows($ret_prom))
						{
							$row_prom 		= $db->fetch_array($ret_prom);
							$prom_caption	= 'Promotional Code Discount Applied';
							$prom_lessval	= print_price($row_prom['code_lessval']);
						}
					}
					elseif($row_ord['gift_vouchers_voucher_id']!=0)
					{
						$sql_vouch = "SELECT voucher_value_used  
										FROM 
											order_voucher 
										WHERE 
											orders_order_id = ".$return_order_arr['order_id']." 
											AND voucher_id=".$row_ord['gift_vouchers_voucher_id']." 
										LIMIT 
											1";
						$ret_vouch = $db->query($sql_vouch);
						if ($db->num_rows($ret_vouch))
						{
							$row_voucher	= $db->fetch_array($ret_vouch);
							$prom_caption	= 'Gift Voucher Discount Applied';
							$prom_lessval	= print_price($row_voucher['voucher_value_used']);
						}
					}
				 ?>
				  <tr>
					<td  align="right" valign="middle" class="totalsHead" >
					<?php 
						echo $prom_caption;
					?>
					</td>
					<td align="right" valign="middle" class="total">
					<?php 
						echo $prom_lessval;
					?>
					</td>
				  </tr>
		  <?php
		  	}
				// Check whether the bonus points module is available in the site
			if ($row_ord['order_bonuspoints_used']>0)
			{
				// Show the following only if any bonus point is spend
				if ($row_ord['order_bonuspoint_discount']>0)
				{
				?>
					  <tr>
						<td  align="right" valign="top" class="totalsHead" ><?php echo $Captions_arr['CART']['CART_SPENT_BONUS_POINTS_VALUE']?> </td>
						<td align="right" valign="top" class="total"><? echo '(-) '.print_price($row_ord['order_bonuspoint_discount'],true);?></td>
					  </tr>
			<?php	
				}
			}
			// Bonus Points Donated
			if ($row_ord["order_bonuspoints_donated"]>0)
			{
			?>
				  <tr>
					<td align="right" valign="middle" class="totalsHead" ><?php echo $Captions_arr['CART']['CART_BONUS_DONATING']?>&nbsp;</td>
					<td  align="right" valign="middle" class="total"><?php echo $row_ord["order_bonuspoints_donated"]?></td>
				  </tr>
			<?php	
			}
			// Bonus Points Earned
				if ($row_ord["order_bonuspoint_inorder"]>0)
				{
			?>
				  <tr>
					<td  align="right" valign="middle" class="totalsHead" ><?php echo $Captions_arr['CART']['CART_TOTAL_POINTS_EARN']?>&nbsp;</td>
					<td  align="right" valign="middle" class="total"><?php echo $row_ord["order_bonuspoint_inorder"]?></td>
				  </tr>
			<?php	
			}
			
		  	// show the total final price
			if ($row_ord['order_totalprice']>0)
			{
			?>
			 
			  <tr>
				<td  align="right" valign="middle" class="grandTotalHead" ><?php echo $Captions_arr['CART']['CART_TOTAL_FINAL_COST']?>&nbsp; </td>
				<td align="right" valign="middle" class="grandTotal"><?php echo print_price($row_ord['order_totalprice'],true)?></td>
			  </tr>
		  <?php
		  	}
			$rem_val = $row_ord['order_totalprice'] - $row_ord['order_deposit_amt'];
			if($rem_val>0 and $row_ord['order_deposit_amt']>0)
			{
			?>
				<tr>
					<td  align="right" valign="middle" class="totalsHead" ><?php echo $Captions_arr['CART']['CART_LESS_AMT_LATER']?>&nbsp; </td>
					<td align="right" valign="middle" class="total"><?php echo print_price($row_ord['order_deposit_amt'],true)?></td>
			  	</tr>
				<tr>
					<td  align="right" valign="middle" class="grandTotalHead" ><?php echo $Captions_arr['CART']['CART_TOTAL_AMT_NOW']?>&nbsp; </td>
					<td align="right" valign="middle" class="grandTotal"><?php echo print_price($rem_val,true)?>
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
			// Check whether any delivery group exists for the site
					if ($row_ord['order_delivery_option'])
					{
				?>	
							<tr>
								<td  align="left" valign="middle" class="totalsHead"><?php echo $Captions_arr['CART']['CART_DELIVERY_OPT']?>&nbsp;:&nbsp;<?php echo stripslashes($row_ord['order_delivery_option'])?></td>
							</tr>
				<?php
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
					echo getpaymenttype_Name($row_ord['order_paymenttype']);
				?>
				</td>
			  </tr>
			  <?php
			  		if($row_ord['order_paymenttype'] =='credit_card')
				{
			  ?>
				  <tr>
					<td  align="left" valign="middle" class="totalsHead" >			
						<?php echo $Captions_arr['CART']['CART_GATEWAY_USED']?>&nbsp;:&nbsp;
							
					<?php 
										echo getpaymentmethod_Name($row_ord['order_paymentmethod']);
?>
					</td>
				  </tr>
			  <?php
			  	}
			  	
				 if($row_ord['disable_id'])
					 {
						$sql_disb = "SELECT type FROM disability_type WHERE id = ".$row_ord['disable_id']." LIMIT 1";
						$ret_disb = $db->query($sql_disb);
						if($db->num_rows($ret_disb))
						{
							$row_disb = $db->fetch_array($ret_disb);
					 ?>
							<tr>
								<td  align="left" valign="middle" class="totalsHead"><?php echo $Captions_arr['CART']['CART_DISABILITY_CAPTION']?>&nbsp;:&nbsp;<?php echo stripslashes($row_disb['type'])?></td>
							</tr>
					<?php
						}	
					 }	
			   	
		  ?>
		  </table></td></tr>
		  </table> </td>
						</tr>
						<?php
					
						?>
						</tbody></table>
					</div></div>
						<div class="summarywrapbottom"></div>
						</div>
						<?PHP
						global $ecom_themename;
						if ($showdisplayHtml=="")
						{ 
						require("themes/$ecom_themename/html/intercartHtml.php");
						$showdisplayHtml= new showdisplay_Html(); // Creating an object for the cart_Html class
						//$showdisplayHtml->show_reviews();
						}	
						?>
  </div>
  <?PHP }?>
  <div class="bordercont">
  <table border="0" width="100%" cellpadding="0" cellspacing="0" class="bordercontA">
  			<tr>
            <td  align="right" valign="middle" class="">	
  			<?php
				/*switch($return_order_arr['payMethod']['paymethod_key'])
				{
					case 'ABLE2BUY': // Display the form for able2buy
						$button_maincaption = 'Confirm Order';
						$button_clickmsg	= 'Please wait...';
						$button_class		= 'buttonred_cart';
						include 'includes/able2verify.php';
					break;
					case 'WORLD_PAY': // Display the form for worldpay
						$button_maincaption = 'Confirm Order';
						$pass_type			= 'order';
						$button_clickmsg	= 'Please wait...';
						$button_class		= 'buttonred_cart';
						include 'includes/world_pay.php';
					break;
					case 'GOOGLE_CHECKOUT':
						require_once('includes/google_library/googlecart.php');
						require_once('includes/google_library/googleitem.php');
						require_once('includes/google_library/googleshipping.php');
						require_once('includes/google_library/googletax.php');
						include("includes/google_checkout.php");
					break;
					case 'HSBC':
						$button_maincaption 		= 'Confirm Order';
						$button_clickmsg			= 'Please wait...';
						$button_class				= 'buttonred_cart';
						include("includes/hsbc_pay.php");
					break;
					case 'PROTX_VSP':
						$button_maincaption 	= 'Confirm Order';
						$pass_type					= 'order';
						$button_clickmsg		= 'Please wait...';
						$button_class				= 'buttonred_cart';
						include 'includes/protx_vsp.php';
					break;
				};*/
				$display_option = 'BUTTON_ONLY';
				// Including the file which hold the login for fields to be passed to payment gateway
				include 'order_preview_gateway_include.php';
			?>	
			</td>
			</tr>
  </table>
  </div>

		 <?php		
		}
		
		/* Function to show the checkout success message*/
		function Show_CheckoutSuccess($order_id=0)
		{
			global $db,$ecom_hostname,$Captions_arr,$ecom_siteid,$ecom_isadword,$ecom_adword_conversionid,$ecom_adword_conversionlanguage,
					$ecom_adword_conversionformat,$ecom_adword_conversioncolor,$ecom_adword_conversionlabel,$protectedUrl,$ecom_success_script;
			$show_downloadable_msg = '';
			if ($order_id>0)
			{
				// Get the grand total from orders table for current order
				$sql_ord = "SELECT order_totalprice,order_paystatus,order_cpc_keyword, order_cpc_se_id, order_cpc_click_id,
								order_cpc_click_pm_id, order_cost_per_click_id,order_paymenttype,order_paymentmethod   
								FROM 
									orders 
								WHERE 
									order_id=".$order_id." 
									AND sites_site_id=$ecom_siteid 
								LIMIT 
									1";
				$ret_ord = $db->query($sql_ord);
				if($db->num_rows($ret_ord))
				{
					google_analytics_ecom_tracking_code($order_id);
					$row_ord 		= $db->fetch_array($ret_ord);
					$total_price 	= $row_ord['order_totalprice'];
					$cur_stat		= $row_ord['order_paystatus'];
					
						// Deciding whether to call the cost per click order total saving section
						//$const_ids = get_session_var('COST_PER_CLICK');
						$const_ids 		= trim($row_ord['order_cost_per_click_id']);
						if ($const_ids!='')
						{
							if($cur_stat == 'Paid')
							{
						
								// Resetting the cost per click id field in orders table to '' to avoid adding multiple times
								$sql_update = "UPDATE orders 
												SET 
													order_cost_per_click_id = ''  
												WHERE 
													order_id = $order_id 
													AND sites_site_id = $ecom_siteid 
												LIMIT 
													1";	
								$db->query($sql_update);					
								cost_per_click($const_ids,$total_price);
							}	
							set_session_var('COST_PER_CLICK','');
							clear_session_var('COST_PER_CLICK');
						}
						// Case of hits to sale report
						if($row_ord['order_cpc_click_id'] > 0 && $cur_stat == 'Paid')
						{
							// Resetting the hits to sale ration fields in orders table to 0 to avoid adding multiple times
							$sql_update = "UPDATE orders 
											SET 
												order_cpc_keyword= '',
												order_cpc_se_id=0,
												order_cpc_click_id=0,
												order_cpc_click_pm_id=0 
											WHERE 
												order_id = $order_id 
												AND sites_site_id = $ecom_siteid 
											LIMIT 
												1";
							$db->query($sql_update);
							seo_revenue_report($row_ord,$total_price);
						}
						$_SESSION['cpc_keyword'] 		= '';
						$_SESSION['cpc_se_id'] 			= '';
						$_SESSION['cpc_click_id'] 		= '';
						$_SESSION['cpc_click_pm_id'] 	= '';
						unset($_SESSION['cpc_keyword']);
						unset($_SESSION['cpc_se_id']);
						unset($_SESSION['cpc_click_id']);
						unset($_SESSION['cpc_click_pm_id']);
						
						// Check whether any downloadable products exists in current order
						$sql_check = "SELECT ord_down_id 
												FROM 
													order_product_downloadable_products 
												WHERE 
													orders_order_id = $order_id 
												LIMIT 
													1";
						$ret_check = $db->query($sql_check);
						if ($db->num_rows($ret_check))
						{
							 if($cur_stat=='Paid' or $cur_stat=='free')
								$show_downloadable_msg = 'CART_DOWNLOADABLE_DO'; // order status is paid and so show link to my downloads page
							else
								$show_downloadable_msg = 'CART_DOWNLOADABLE_WAIT';	// order is not paid so just show that downloads can be done only payment is success and show link to download page
						}	
					}
			}
			/*
				##################################################################################
				Start Google Purchase Conversion Recording section
				##################################################################################
			*/
			if($ecom_isadword)
			{
				if ($order_id>0)
				{
				?>
						<!-- Google Code for purchase Conversion Page -->
						<script language="JavaScript" type="text/javascript">
						<!--
						var google_conversion_id 		= <?php echo $ecom_adword_conversionid; /*1050720287;*/ ?>; 			
						var google_conversion_language 	= "<?php echo $ecom_adword_conversionlanguage; /*"en_GB";*/ ?>"; 	
						var google_conversion_format 	= "<?php echo $ecom_adword_conversionformat; /*"1";*/ ?>";		
						var google_conversion_color 	= "<?php echo $ecom_adword_conversioncolor; /*"FFFFFF";*/ ?>";		
						if (<?=$total_price?>) 
						{
						  var google_conversion_value 	= <?=$total_price?>;
						}
						var google_conversion_label 	= "<?php echo $ecom_adword_conversionlabel; /*"purchase";*/ ?>";
						//-->
						</script>
						<script language="JavaScript" src="https://www.googleadservices.com/pagead/conversion.js">
						</script>
						<noscript>
						<img height=1 width=1 border=0 src="https://www.googleadservices.com/pagead/conversion/<?php echo $ecom_adword_conversionid?>/imp.gif?value=<?=$total_price?>&label=<?php echo $ecom_adword_conversionlabel?>&script=0">
						</noscript>
			<?php		
					
				}
			}	
			if(trim($ecom_success_script)!='' and $protectedUrl==false)
			{
				if ($order_id>0)
				{
					// Get the total number of items ordered
					$sql_total = "SELECT sum(order_orgqty) as totqty 
									FROM 
										order_details 
									WHERE 
										orders_order_id = $order_id";
					$ret_total = $db->query($sql_total);
					if($db->num_rows($ret_total))
						$row_total = $db->fetch_array($ret_total);
					$total_qty		= $row_total['totqty'];
					$cust_id		= get_session_var("ecom_login_customer");
					$cust_type		= ($cust_id)?0:1;
					$succ_script	= trim($ecom_success_script);
					$sr_arr 		= array('[TOTAL_PRICE]','[CUST_TYPE]','[ORDER_ID]','[UNITS_ORDERED]');
					$rp_arr 		= array($total_price,$cust_type,$order_id,$total_qty);
					$succ_script 	= str_replace($sr_arr,$rp_arr,trim($succ_script));
					
					echo stripslashes($succ_script);
				}	
			}	
			/*
				##################################################################################
				End Google Purchase Conversion Recording section
				##################################################################################
			*/
			
			
			// Clearing the cart details if checkout is successfull
			$sess_id = session_id();
			clear_Cart($sess_id);
			
			$Captions_arr['CART'] 	= getCaptions('CART'); // Getting the captions to be used in this page
			
		?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="shoppingcarttable">
			  <tr>
				<td align="left" valign="middle"><div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?php echo $Captions_arr['CART']['CART_CHECKOUT_SUCCESS_TITLE']?></div></td>
			  </tr>
			  <tr>
			  	<td align="left" class="shoppingcartcontent_indent_highlight">
				<?php 
				if($row_ord['order_paymenttype']=='pay_on_phone')
				{
					echo $Captions_arr['CART']['CART_CHECKOUT_PAY_MSG1']."<br><br>".str_replace('[ordid]',$order_id,$Captions_arr['CART']['CART_CHECKOUT_PAY_MSG2']);
				}
				else
					echo $Captions_arr['CART']['CART_CHECKOUT_SUCCESS_MSG'];
				if($order_id>0)
				{
				// Get the details product with highest price in current order
					$sql_det = "SELECT products_product_id,product_name  
									FROM 
										order_details 
									WHERE 
										orders_order_id=$order_id 
									ORDER BY 
										product_soldprice 
										DESC 
									LIMIT 
										1";
					$ret_det = $db->query($sql_det);
					if($db->num_rows($ret_det))
					{
						$row_det = $db->fetch_array($ret_det);
						$url = url_product($row_det['products_product_id'],$row_det['product_name'],1);
						/*
					?>
					<center><br><br></br><a href="http://api.tweetmeme.com/share?url=<?php echo $url?>&alias=<shortenedurl>&service=bit.ly"><img src="http://api.tweetmeme.com/imagebutton.gif?url=<?php echo $url?>" height="61" width="51" border="0" /></a></center><br></br><br></br>
					<?php
					*/
					}
				}	
				?>
				</td>
			</tr>
			<?php 
				if($show_downloadable_msg!='') // Check whether the link which takes user to the mydownloads page after success
				{
			?>
				 <tr>
					<td align="left" class="shoppingcartcontent_noborder">
					<?php echo $Captions_arr['CART'][$show_downloadable_msg]?>
					</td>
				</tr>
				 <tr>
					<td align="center" class="shoppingcartcontent_noborder">
					<a href="http://<?php echo $ecom_hostname; ?>/mydownloads.html" title="<?php echo $Captions_arr['CART']['CART_DOWNLOAD_LIST_LINK']?>" class="favoriteprodlink"><?php echo $Captions_arr['CART']['CART_DOWNLOADABLE_LINK']?> </a><?php echo $Captions_arr['CART']['CART_DOWNLOADABLE_LINK_CONT']?>.</td>
					</td>
				</tr>
			<?php
				}			
			?>
			</table>	
		<?php	
		}
		/* Function to show the checkout failed message*/
		function Show_CheckoutFailed()
		{
			global $db,$ecom_hostname,$Captions_arr,$ecom_siteid,$Captions_arr;
			$sess_id = session_id();
			// Get the error details from cart table
			$sql_cart = "SELECT cart_error_msg_ret 
							FROM 
								cart_supportdetails  
							WHERE 
								sites_site_id =$ecom_siteid
								AND session_id='".$sess_id."'";
			$ret_cart = $db->query($sql_cart);
			if($db->num_rows($ret_cart))
			{
				$row_cart 	= $db->fetch_array($ret_cart);
				$msg		= stripslashes(trim($row_cart['cart_error_msg_ret']));
			}
			// update the cart_error_msg_ret field with blank 
			$update_array						= array();
			$update_array['cart_error_msg_ret']	= '';
			$db->update_from_array($update_array,'cart_supportdetails',array('sites_site_id'=>$ecom_siteid,'session_id'=>$sess_id));					
			
			$Captions_arr['CART'] 	= getCaptions('CART'); // Getting the captions to be used in this page
		?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="shoppingcarttable">
			<tr>
				<td align="left" valign="middle"><div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?php echo $Captions_arr['CART']['CART_CHECKOUT_FAILED_TITLE']?></div></td>
			</tr>
			<?php
				if($msg)
				{
			?>
				<tr>
					<td align="left" class="shoppingcartcontent_indent_highlight">
					<?php echo $msg?>
					</td>
				</tr>
			<?php
				}
				else
				{
			?>
					<tr>
				<td align="left" class="shoppingcartcontent_indent_highlight">
				<?php echo $Captions_arr['CART']['CART_CHECKOUT_FAILED_MSG']?><br /><br />
				<?php
				if($_REQUEST['error']) {
					echo $_REQUEST['error'];
				}
				?>
				</td>
			</tr>
			<?php	
				}
			?>
			</table>	
			<!--<script type="text/javascript">
			var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
			document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
			</script>
			<script type="text/javascript">
			var pageTracker = _gat._getTracker("UA-3381865-1");
			pageTracker._initData();
			pageTracker._trackPageview();
			</script>-->

		<?php	
		}
		function Show_NoChexCommonSuccess()
		{
			global $db,$ecom_hostname,$Captions_arr,$ecom_siteid,$Captions_arr;
			$sess_id = session_id();
			$Captions_arr['CART'] 	= getCaptions('CART'); // Getting the captions to be used in this page
		?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="shoppingcarttable">
			<tr>
				<td align="left" valign="middle"><div class="treemenu"><a href="<? url_link('');?>" title="<?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?php echo $Captions_arr['CART']['CART_NOCHEXCHECKOUT_SUCCESS']?></div></td>
			</tr>
			<tr>
				<td align="left" valign="middle"></td>
			</tr>
			
					<tr>
				<td align="center" class="shoppingcartcontent_indent_highlight">
				<br />
				<?php echo stripslash_normal($Captions_arr['CART']['CART_NOCHEXCHECKOUT_SUCCESS_MSG'])?>
				<br />
				<br />
				</td>
			</tr>
			</table>	
		<?php	
		}
		function show_error_msg($msg)
		{
			global $Captions_arr;
			$Captions_arr['CART']	= getCaptions('CART');
		?>
			<div  class="carterrordiv_alert" id="instockmsg_div">
			<div class="carterrordiv_head">
			<?php echo $Captions_arr['CART']['CART_ERROR_HEADING']?>.
			</div>

			<div align="right" class="carterrormsg_span" ><a href="javascript: hide_instockmsg_div()"><img src="<?php url_site_image('close.gif')?>" border="0" /></a></div>
			<span class="carterrormsg_msg">
			<?php echo $msg?></span>
			</div>
		<?php	
		}
		function showpromotional_error_msg($msg)
		{
			global $Captions_arr;
			$Captions_arr['CART']	= getCaptions('CART');
		?>
			<div  class="carterrordiv_alert" id="instockmsg_div">
			<div class="carterrordiv_head">
			<?php echo stripslash_normal($Captions_arr['CART']['CART_ERROR_HEADING'])?>.
			</div>

			<div align="right" class="carterrormsg_span" ><a href="#" onclick="hide_instockmsg_div();document.frm_cart.cart_savepromotional.value=1;handle_form_submit(document.frm_cart,'save_commondetails','#a_prom')"><img src="<?php url_site_image('close.gif')?>" border="0" /></a></div>
			<span class="carterrormsg_msg">
			<?php echo $msg?></span>
			</div>
		<?php	
		}
	};	
?>