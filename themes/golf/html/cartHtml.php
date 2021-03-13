<?php
	/*############################################################################
	# Script Name 	: cartHtml.php
	# Description 		: Page which holds the display logic for cart and checkout
	# Coded by 		: Sny
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
					$Captions_arr,$inlineSiteComponents,$sitesel_curr,$default_Currency_arr,$ecom_testing,$ecom_themename,$components,
					$ecom_common_settings;
			$session_id 			= session_id();	// Get the session id for the current section
			$cust_id				= get_session_var("ecom_login_customer"); // Get the customer id from session
			$Captions_arr['CART'] 	= getCaptions('CART'); // Getting the captions to be used in this page
			$cartData 				= cartCalc(); // Calling the function to calculate the details related to cart
		
			// Calling function to get the messages to be shown in the cart page
			$cart_alert_arr 			= get_CartMessages($_REQUEST['hold_section'],1);
			$cart_alert					= $cart_alert_arr['msg'];
			if($cart_alert_arr['err']==1) // Check whether this cart message is to be displayed in a div or in above cart as normal error message
			{
				$this->show_error_msg($cart_alert);
				$cart_alert = '';
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
			
		?>	
			<form method="post" name="frm_cart"  id="frm_cart" class="frm_cls" action="<?php url_link('cart.html')?>">
			<input type="hidden" name="fpurpose" value="" />
			<input type="hidden" name="remcart_id" value="" />
			<input type="hidden" name="cart_mod" value="show_cart" />
			<input type="hidden" name="hold_section" value="" />
			
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="shoppingcarttable">
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
					<?php
					// Check whether logged in 
					if ($cust_id) // Case logged in 
					{
					  echo $Captions_arr['CART']['CART_LOGGED_IN_AS']; echo "&nbsp;".$cartData['customer']['customer_title']." ".$cartData['customer']['customer_fname']." ".$cartData['customer']['customer_mname']." ".$cartData['customer']['customer_surame'];?>
						<br />
						<? echo $Captions_arr['CART']['CART_IF_YOU_NOT_LOG'];?> <a href="http://<?php echo $ecom_hostname?>/logout.html?rets=1" title="Logout" class="cartlogin_link"><? echo $Captions_arr['CART']['CART_HERE'];?></a><? echo "&nbsp;". $Captions_arr['CART']['CART_TO_LOGOUT'];?> 
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
				<td colspan="5" align="left" valign="middle"><div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?php echo $Captions_arr['CART']['CART_MAINHEADING']?></div></td>
				<td width="9%" align="left" valign="middle" class="shoppingcartheader">
				<?php
					// Check whether the clear cart button is to be displayed
					if($Settings_arr['empty_cart']==1 and count($cartData['products']))
					{
				?>
				  		<input name="clearcart_button" type="button" class="buttonred_cart" id="clearcart_button" value="<?php echo $Captions_arr['CART']['CART_CLEAR']?>" onclick="if(confirm_message('Are you sure you want to clear all items in the cart?')){show_wait_button(this,'Please Wait...');document.frm_cart.cart_mod.value='clear_cart';document.frm_cart.submit();}" />
			    <?php
				 	}
				 ?>	</td>
			  </tr>
			  <tr>
				<td align="left" valign="middle" class="shoppingcartheaderA"><?php echo $Captions_arr['CART']['CART_ITEM']?></td>
				<td align="left" valign="middle" class="shoppingcartheaderA"><?php echo $Captions_arr['CART']['CART_PRICE']?></td>
				<td align="center" valign="middle" class="shoppingcartheaderA"><?php echo $Captions_arr['CART']['CART_AVAIL']?></td>
				<td align="center" valign="middle" class="shoppingcartheaderA"><?php echo $Captions_arr['CART']['CART_DISCOUNT']?></td>
				<td align="center" valign="middle" class="shoppingcartheaderA"><?php echo $Captions_arr['CART']['CART_QTY']?></td>
				<td align="right" valign="middle" class="shoppingcartheaderA"><?php echo $Captions_arr['CART']['CART_TOTAL']?></td>
			  </tr>
		  <?php
		  if (count($cartData['products'])==0) // Done to show the message if no products in cart
		  {
			?>
				<tr>
					<td align="center" valign="middle" class="shoppingcartcontent" colspan="6">
						<?php echo $Captions_arr['CART']['CART_NO_PRODUCTS']?>					</td>
				</tr>	
			<?php
		  }
		  else
		  {
		  	  // Following section iterate through the products in cart and show its list
			  foreach ($cartData['products'] as $products_arr)
			  {
				$vars_exists = false;
				if ($products_arr['prod_vars'] or $products_arr['prod_msgs'])  // Check whether variable of messages exists
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
				if($products_arr['product_deposit'])
				{
					if ($products_arr['final_price']>$products_arr['product_deposit_less'])
						$str_reduceval	+= $products_arr['product_deposit_less'];
				}
			  ?>
				  <tr>
					<td align="left" valign="middle" class="<?php echo $trmainclass?>">
					<?php 
					// Check whether thumb nail is to be shown here
					if ($Settings_arr['thumbnail_in_viewcart']==1)
					{
					?>
						<a href="<?php url_product($products_arr['product_id'],$products_arr['product_name'],-1)?>" title="<?php echo stripslashes($products_arr['product_name'])?>">
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
						<br />
					<?php
					}
					?>
					<a href="<?php echo url_product($products_arr['product_id'],$products_arr['product_name'])?>" title="<?php echo $products_arr['product_name']?>" class="shoppingcartprod_link"><?php echo stripslashes($products_arr['product_name'])?></a>					</td>
					<td align="left" valign="middle" class="<?php echo $tdpriceBclass?>"><?php echo print_price($products_arr['product_webprice'],true)?></td>
					<td align="center" valign="middle" class="<?php echo $trmainclass?>">
					<?php
						// Calling the function to check whether the current product is in preorder or not
						$preorder = check_Inpreorder($products_arr['product_id']);
						if ($preorder['in_preorder']=='Y')
							echo '<span class="cartinoutstock">Available on'.'<br />'.$preorder['in_date'].'</span>';
						else
						{
							if($products_arr['stock']>0 or $products_arr['product_alloworder_notinstock']=='Y')
								echo '<span class="cartinstock">In Stock</span>';
						}	
					?>					</td>
					<td align="center" valign="middle" class="<?php echo $tdpriceBclass?>">
					-<?php 
							// if combo disc exists then that should be displayed otherwise the normal or promotional disc is to be shown
							if ($products_arr["prom_prodcode_disc"] and $products_arr["savings"]["product"]) // Case if promotional code disc is there for current product
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
								echo print_price($products_arr["savings"]["product"],true);
					?>
					</td>
					<td align="center" valign="middle" class="<?php echo $trmainclass?>">
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
							<div class="updatediv" align="center"><a href="#" class="update_link" onclick="if (confirm_message('<?php echo $Captions_arr['CART']['CART_ITEM_REM_MSG']?>')) {document.frm_cart.remcart_id.value='<?php echo $products_arr['cart_id']?>';handle_form_submit(document.frm_cart,'Remove_qty','#rem')}" title="<?php echo $Captions_arr['CART']['CART_REMOVE']?>"><img src='<?php url_site_image('cart_delete.gif')?>' border="0" alt="<?php echo $Captions_arr['CART']['CART_REMOVE']?>"></a></div>
					<?php	
						}
						else
						{
					?>
					<div class="updatediv" align="center"><input name="cart_qty_<?php echo $products_arr['cart_id']?>" type="text" id="cart_qty_<?php echo $products_arr['cart_id']?>" size="3" maxlength="2" value="<?php echo $products_arr["cart_qty"]?>" style="width:16px;" />
					</div> 
					<div class="updatediv" align="center"><a href="#" onclick="document.frm_cart.remcart_id.value='<?php echo $products_arr['cart_id']?>';handle_form_submit(document.frm_cart,'Update_qty','#upd')" title="<?php echo $Captions_arr['CART']['CART_UPDATE']?>"><img src='<?php url_site_image('cart_update.gif')?>' border="0" alt="<?php echo $Captions_arr['CART']['CART_UPDATE']?>"></a>  <a href="#" class="update_link" onclick="if (confirm_message('<?php echo $Captions_arr['CART']['CART_ITEM_REM_MSG']?>')) {document.frm_cart.remcart_id.value='<?php echo $products_arr['cart_id']?>';handle_form_submit(document.frm_cart,'Remove_qty','#rem')}" title="<?php echo $Captions_arr['CART']['CART_REMOVE']?>"><img src='<?php url_site_image('cart_delete.gif')?>' border="0" alt="<?php echo $Captions_arr['CART']['CART_REMOVE']?>"></a></div>
					<?php
						}
					?>
					</td>
					<td align="right" valign="middle" class="<?php echo $tdpriceAclass?>"><?php echo print_price($products_arr['final_price'],true)?></td>
				  </tr>
				  <?php
				  	// If variables exists for current product, show it in the following section
					if ($vars_exists) 
					{
				  ?>
					<tr>
						<td align="left" valign="middle" colspan="6" class="shoppingcartcontent">
						<?
						
						// show the variables for the product if any
						if ($products_arr['prod_vars']) 
						{
							//print_r($products_arr['prod_vars']);
							foreach($products_arr["prod_vars"] as $productVars)
							{
								if (trim($productVars['var_value'])!='')
									print "<span class='cartvariable'>".$productVars['var_name'].": ". $productVars['var_value']."</span><br />"; 
								else
									print "<span class='cartvariable'>".$productVars['var_name']."</span><br />"; 
									
							}	 
						}
						// Show the product messages if any
						if ($products_arr['prod_msgs']) 
						{	
							foreach($products_arr["prod_msgs"] as $productMsgs)
							{
								print "<span class='cartvariable'>".$productMsgs['message_title'].": ". $productMsgs['message_value']."</span><br />"; 
							}	
						}	
					?>						</td>
					</tr>	
			  <?php
				}
			  }
		  ?>
			 	<tr>
				<td align="right" valign="middle" class="shoppingcartcontent_total" colspan="5"><?php echo $Captions_arr['CART']['CART_TOTPRICE']?></td>
				<td align="right" valign="middle" class="shoppingcartpriceC"><?php echo print_price($cartData["totals"]["subtotal"],true)?></td>
			  </tr>
		  <?php
		  		$module_name = 'mod_giftwrap';
				if(is_Feature_exists($module_name)) // Check whether giftwrap module exists for the current site
				{
					$show_giftdet = false;
					// Check whether gift wrap options are set for the product
					$sql_gift = "SELECT giftwrap_active,giftwrap_minprice,giftwrap_messageprice
									FROM 
										general_settings_site_giftwrap 
									WHERE 
										sites_site_id = $ecom_siteid 
									LIMIT 
										1";
					$ret_gift = $db->query($sql_gift);
					if ($db->num_rows($ret_gift))
					{
						$row_gift 		= $db->fetch_array($ret_gift);
						$show_giftdet 	= ($row_gift['giftwrap_active']==1)?true:false; 
					}
					// Show the following if giftwrap option is set for the site
					if ($show_giftdet)
					{
			?>	
						<tr>
						<td colspan="4" align="left" valign="middle" class="shoppingcartcontent"><a name="a_gwrap">&nbsp;</a><?php echo $Captions_arr['CART']['CART_GIFTWRAP_REQ']?> (<?php echo print_price($row_gift['giftwrap_minprice'],true)?>)</td>
						<td colspan="2" align="right" valign="middle" class="shoppingcartcontent">
						<?php /*?><input type="checkbox" name="chk_giftwrapreq11" id="chk_giftwrapreq111" value="1" onclick="handle_form_submit(document.frm_cart,'save_commondetails','#a_gwrap')" <?php echo($row_cartdet['giftwrap_req'])?'checked="checked"':''?>/><?php */?>
						<?php
							echo generateselectbox('chk_giftwrapreq',array(0=>'No',1=>'Yes'),$row_cartdet['giftwrap_req'],'','handle_form_submit(document.frm_cart,"save_commondetails","#a_gwrap")');
						?>
						</td>
						</tr>
					
						<tr id="giftwrap_details_tr" <?php echo ($row_cartdet['giftwrap_req'])?'':'style="display:none"'?>>
							<td colspan="6" align="left" valign="middle" class="shoppingcartcontent">
								<table width="100%" border="0" cellspacing="1" cellpadding="1">
								<tr>
								<td width="2%" align="left" valign="top">&nbsp;</td>
								<td width="53%" align="left" valign="top"><?php echo $Captions_arr['CART']['CART_GIFTWRAP_MSG']?>(<?php echo print_price($row_gift['giftwrap_messageprice'],true)?>)</td>
								<td width="45%" align="left" valign="top">
								<?php
									echo generateselectbox('giftwrap_message_req',array(0=>'No',1=>'Yes'),$row_cartdet['giftwrap_msg_req'],'','handle_form_submit(document.frm_cart,"save_commondetails","#a_gwrap")');
								?>
								
								</td>
								</tr>
								<tr id="giftwrap_message_req_tr" <?php echo ($row_cartdet['giftwrap_msg_req'])?'':'style="display:none"'?>>
								  <td align="left" valign="top">&nbsp;</td>
								  <td colspan="2" align="left" valign="top"><?php echo $Captions_arr['CART']['CART_TYP_MSG']?><br />								    
							      <textarea name="giftwrap_message" cols="50" rows="4"><?php echo stripslashes($row_cartdet['giftwrap_msg'])?></textarea></td>
								  </tr>
								</table>
								<?php
									// Check whether ribbons exists for this site
									$sql_ribbon = "SELECT ribbon_id,ribbon_name,ribbon_extraprice 
													FROM 
														giftwrap_ribbon 
													WHERE 
														ribbon_active = 1 
														AND sites_site_id = $ecom_siteid 
													ORDER BY 
														ribbon_order";
									$ret_ribbon = $db->query($sql_ribbon);
									
									// Check whether paper exists
									$sql_paper = "SELECT paper_id,paper_name,paper_extraprice 
													FROM 
														giftwrap_paper 
													WHERE 
														paper_active = 1 
														AND sites_site_id = $ecom_siteid 
													ORDER BY 
														paper_order";
									$ret_paper = $db->query($sql_paper);
									
									// Check whether card exists
									$sql_card = "SELECT card_id,card_name,card_extraprice 
													FROM 
														giftwrap_card 
													WHERE 
														card_active = 1 
														AND sites_site_id = $ecom_siteid 
													ORDER BY 
														card_order";
									$ret_card = $db->query($sql_card);
									
									// Check whether bows exists
									$sql_bow = "SELECT bow_id,bow_name,bow_extraprice 
													FROM 
														giftwrap_bows 
													WHERE 
														bow_active = 1 
														AND sites_site_id = $ecom_siteid 
													ORDER BY 
														bow_order";
									$ret_bow = $db->query($sql_bow);	
									$giftwrap_cnt = 0;		
									// Show the following section only if ribbon, paper, card or bow is set for the site
									if ($db->num_rows($ret_ribbon) or $db->num_rows($ret_paper) or $db->num_rows($ret_card) or $db->num_rows($ret_bow))
									{
								?>
										<table width="100%" border="0" cellpadding="1" cellspacing="1" class="shoppingcartgiftwrap_det">
										<tr>
										<td align="left" class="shoppingcartgiftwrap_detheading"><a name="a_gwrapopt">&nbsp;</a><?php echo $Captions_arr['CART']['CART_WRAP_OPT']?></td>
										</tr>
										</table>
										<table width="100%" border="0" cellpadding="1" cellspacing="1" class="shoppingcartgiftwrap_det">
										<tr>
										<?php
										// show the section to display the various types of ribbons
										if ($db->num_rows($ret_ribbon))
										{
										?>
											<td width="25%" align="left" valign="top" class="shoppingcartgiftwrap_dettd">
												<span class="shoppingcartgiftwrap_detsubheading"><?php echo $Captions_arr['CART']['CART_RIBBON']?></span><br />
												&nbsp;&nbsp;&nbsp;&nbsp;<input name="ribbon_radio" type="radio" class="shoppingcart_radio" id="ribbon_radio" value="0" checked="checked" onclick="handle_form_submit(document.frm_cart,'save_commondetails','#a_gwrapopt')" />
												 <?php echo $Captions_arr['CART']['CART_NONE']?><!--&nbsp; <img src="<?php //url_site_image('giftminus.gif')?>" onclick="hideall_giftwrapimagediv(document.frm_cart,'ribbonimg_')" alt="Collapse All" title="Collapse All"/>--><br />
												<?php
													while ($row_ribbon = $db->fetch_array($ret_ribbon))
													{
														// Making the decision of checked radio button and the collapse of expand image
														if ($row_cartdet['giftwrap_ribbon_id']==$row_ribbon['ribbon_id'])
														{
															$checked 	= 'checked="checked"';
															$imgsrc 	= 'giftminus.gif';
														}
														else
														{
															$checked 	= '';
															$imgsrc 	= 'giftplus.gif';
														}		
														// Get the image for ribbon
														$pass_type = 'image_thumbpath';//get_default_imagetype('ribbon');
														// Calling the function to get the image to be shown
														$img_arr = get_imagelist('ribbon',$row_ribbon['ribbon_id'],$pass_type,0,0);
														if(count($img_arr))
														{
												?>			<img id="gift_imgribbon_<?php echo $row_ribbon['ribbon_id']?>" src="<?php url_site_image($imgsrc)?>" onclick="handle_giftwrapimagediv(this,'<?php echo $ecom_hostname?>',document.getElementById('ribbonimg_div_<?php echo $row_ribbon['ribbon_id']?>'),'ribbonimg_',document.frm_cart)" alt="Click for thumb" title="Click for thumb"/>
												<?php
														}
														else
															echo '&nbsp;&nbsp;&nbsp;';
												?>		
														
														<input type="radio" name="ribbon_radio" id="ribbon_radio" value="<?php echo $row_ribbon['ribbon_id']?>" class="shoppingcart_radio" <?php echo $checked?>  onclick="handle_form_submit(document.frm_cart,'save_commondetails','#a_gwrapopt')"/><?php echo stripslashes($row_ribbon['ribbon_name'])?> (<?php echo print_price($row_ribbon['ribbon_extraprice'],true)?>)<br />
														<div id="ribbonimg_div_<?php echo $row_ribbon['ribbon_id']?>" class="giftwrapimg_div" <?php echo ($row_cartdet['giftwrap_ribbon_id']==$row_ribbon['ribbon_id'])?'':'style="display:none"'?>>
														<?php
															
															
															if(count($img_arr))
															{
																for($i=0;$i<count($img_arr);$i++)
																	show_image(url_root_image($img_arr[$i][$pass_type],1),$row_ribbon['ribbon_name'],$row_ribbon['ribbon_name']);
															}
																?>
														</div>
												<?php
													}
												?>											</td>
										<?php
											++$giftwrap_cnt;
										}
										// show the section to show the different types of papers
										if ($db->num_rows($ret_paper))
										{
										?>
											<td width="25%" align="left" valign="top" class="shoppingcartgiftwrap_dettd">
												<span class="shoppingcartgiftwrap_detsubheading"><?php echo $Captions_arr['CART']['CART_PAPER']?></span><br />
												&nbsp;&nbsp;&nbsp;&nbsp;<input name="paper_radio" type="radio" class="shoppingcart_radio" id="paper_radio" value="0" checked="checked" onclick="handle_form_submit(document.frm_cart,'save_commondetails','#a_gwrapopt')" />
												<?php echo $Captions_arr['CART']['CART_NONE']?> <!--<img src="<?php //url_site_image('giftminus.gif')?>" onclick="hideall_giftwrapimagediv(document.frm_cart,'paperimg_')" alt="Collapse All" title="Collapse All"/>--><br />
												<?php
													while ($row_paper = $db->fetch_array($ret_paper))
													{
														// Making the decision of checked radio button and the collapse of expand image
														if ($row_cartdet['giftwrap_paper_id']==$row_paper['paper_id'])
														{
															$checked 	= 'checked="checked"';
															$imgsrc 	= 'giftminus.gif';
														}
														else
														{
															$checked 	= '';
															$imgsrc 	= 'giftplus.gif';
														}	
														// Get the image for ribbon
														$pass_type = 'image_thumbpath';//get_default_imagetype('ribbon');
														// Calling the function to get the image to be shown
														$img_arr = get_imagelist('paper',$row_paper['paper_id'],$pass_type,0,0);
														if(count($img_arr))
														{
												?>			<img id="gift_imgpaper_<?php echo $row_paper['paper_id']?>" src="<?php url_site_image($imgsrc)?>" onclick="handle_giftwrapimagediv(this,'<?php echo $ecom_hostname?>',document.getElementById('paperimg_div_<?php echo $row_paper['paper_id']?>'),'paperimg_',document.frm_cart)" alt="Click for thumb" title="Click for thumb"/>
												<?php
														}
														else
															echo '&nbsp;&nbsp;&nbsp;';
												?>		
														
														<input type="radio" name="paper_radio" id="paper_radio" value="<?php echo $row_paper['paper_id']?>" class="shoppingcart_radio" <?php echo $checked?> onclick="handle_form_submit(document.frm_cart,'save_commondetails','#a_gwrapopt')"/><?php echo stripslashes($row_paper['paper_name'])?> (<?php echo print_price($row_paper['paper_extraprice'],true)?>)<br />
														<div id="paperimg_div_<?php echo $row_paper['paper_id']?>" class="giftwrapimg_div" <?php echo ($row_cartdet['giftwrap_paper_id']==$row_paper['paper_id'])?'':'style="display:none"'?>>
														<?php
															if(count($img_arr))
															{
																for($i=0;$i<count($img_arr);$i++)
																	show_image(url_root_image($img_arr[$i][$pass_type],1),$row_paper['paper_name'],$row_paper['paper_name']);
															}
																?>
														</div>
												<?php
													}
												?>											</td>
										<?php
											++$giftwrap_cnt;
										}
										// show the section to display the various cards
										if ($db->num_rows($ret_card))
										{
											if ($giftwrap_cnt==2)
												echo "</tr><tr>";
										?>
											<td width="25%" align="left" valign="top" class="shoppingcartgiftwrap_dettd">
												<span class="shoppingcartgiftwrap_detsubheading"><?php echo $Captions_arr['CART']['CART_CARDS']?></span><br />
												&nbsp;&nbsp;&nbsp;&nbsp;<input name="card_radio" type="radio" class="shoppingcart_radio" id="card_radio" value="0" checked="checked" onclick="handle_form_submit(document.frm_cart,'save_commondetails','#a_gwrapopt')" />
												<?php echo $Captions_arr['CART']['CART_NONE']?><!-- <img src="<?php //url_site_image('giftminus.gif')?>" onclick="hideall_giftwrapimagediv(document.frm_cart,'cardimg_')" alt="Collapse All" title="Collapse All"/>--><br />
												<?php
													while ($row_card = $db->fetch_array($ret_card))
													{
														// Making the decision of checked radio button and the collapse of expand image
														if (($row_cartdet['giftwrap_card_id']==$row_card['card_id']))
														{
															$checked 	= 'checked="checked"';
															$imgsrc 	= 'giftminus.gif';
														}
														else
														{
															$checked 	= '';
															$imgsrc 	= 'giftplus.gif';
														}	
														// Get the image for ribbon
														$pass_type = 'image_thumbpath';//get_default_imagetype('ribbon');
														// Calling the function to get the image to be shown
														$img_arr = get_imagelist('card',$row_card['card_id'],$pass_type,0,0);
														if(count($img_arr))
														{
												?>			<img id="gift_imgcard_<?php echo $row_card['card_id']?>" src="<?php url_site_image($imgsrc)?>" onclick="handle_giftwrapimagediv(this,'<?php echo $ecom_hostname?>',document.getElementById('cardimg_div_<?php echo $row_card['card_id']?>'),'cardimg_',document.frm_cart)" alt="Click for thumb" title="Click for thumb"/>
												<?php
														}
														else
															echo '&nbsp;&nbsp;&nbsp;';
												?>		
														
														<input type="radio" name="card_radio" id="card_radio" value="<?php echo $row_card['card_id']?>" class="shoppingcart_radio" <?php echo $checked ?> onclick="handle_form_submit(document.frm_cart,'save_commondetails','#a_gwrapopt')"/><?php echo stripslashes($row_card['card_name'])?> (<?php echo print_price($row_card['card_extraprice'],true)?>)<br />
														<div id="cardimg_div_<?php echo $row_card['card_id']?>" class="giftwrapimg_div" <?php echo ($row_cartdet['giftwrap_card_id']==$row_card['card_id'])?'':'style="display:none"'?>>
														<?php
															if(count($img_arr))
															{
																for($i=0;$i<count($img_arr);$i++)
																	show_image(url_root_image($img_arr[$i][$pass_type],1),$row_card['card_name'],$row_card['card_name']);
															}
																?>
														</div>
												<?php
													}
												?>											</td>
										<?php
											++$giftwrap_cnt;
										}
										// Show the section to show various bows
										if ($db->num_rows($ret_bow))
										{
											if ($giftwrap_cnt==2)
												echo "</tr><tr>";
										?>
											<td width="25%" align="left" valign="top" class="shoppingcartgiftwrap_dettd">
												<span class="shoppingcartgiftwrap_detsubheading"><?php echo $Captions_arr['CART']['CART_BOWS']?></span><br />
												&nbsp;&nbsp;&nbsp;&nbsp;<input name="bow_radio" type="radio" class="shoppingcart_radio" id="bow_radio" value="0" checked="checked" onclick="handle_form_submit(document.frm_cart,'save_commondetails','#a_gwrapopt')" />
												<?php echo $Captions_arr['CART']['CART_NONE']?> <!--<img src="<?php //url_site_image('giftminus.gif')?>" onclick="hideall_giftwrapimagediv(document.frm_cart,'bowimg_')" alt="Collapse All" title="Collapse All"/>--><br />
												<?php
													while ($row_bow = $db->fetch_array($ret_bow))
													{
														// Making the decision of checked radio button and the collapse of expand image
														if ($row_cartdet['giftwrap_bow_id']==$row_bow['bow_id'])
														{
															$checked 	= 'checked="checked"';
															$imgsrc 	= 'giftminus.gif';
														}
														else
														{
															$checked 	= '';
															$imgsrc 	= 'giftplus.gif';
														}
														// Get the image for ribbon
														$pass_type = 'image_thumbpath';//get_default_imagetype('ribbon');
														// Calling the function to get the image to be shown
														$img_arr = get_imagelist('bow',$row_bow['bow_id'],$pass_type,0,0);
														if(count($img_arr))
														{
												?>			<img id="gift_imgbow_<?php echo $row_bow['bow_id']?>" src="<?php url_site_image($imgsrc)?>" onclick="handle_giftwrapimagediv(this,'<?php echo $ecom_hostname?>',document.getElementById('bowimg_div_<?php echo $row_bow['bow_id']?>'),'bowimg_',document.frm_cart)" alt="Click for thumb" title="Click for thumb"/>
												<?php
														}
														else
															echo '&nbsp;&nbsp;&nbsp;';
												?>		
														
														<input type="radio" name="bow_radio" id="bow_radio" value="<?php echo $row_bow['bow_id']?>" class="shoppingcart_radio" <?php echo $checked ?> onclick="handle_form_submit(document.frm_cart,'save_commondetails','#a_gwrapopt')"/><?php echo stripslashes($row_bow['bow_name'])?> (<?php echo print_price($row_bow['bow_extraprice'],true)?>)<br />
														<div id="bowimg_div_<?php echo $row_bow['bow_id']?>" class="giftwrapimg_div" <?php echo ($row_cartdet['giftwrap_bow_id']==$row_bow['bow_id'])?'':'style="display:none"'?>>
														<?php
															if(count($img_arr))
															{
																for($i=0;$i<count($img_arr);$i++)
																	show_image(url_root_image($img_arr[$i][$pass_type],1),$row_bow['bow_name'],$row_bow['bow_name']);
															}
																?>
														</div>
												<?php
													}
												?>											</td>
										<?php
											++$giftwrap_cnt;
										}
										if($giftwrap_cnt>2 and $giftwrap_cnt<4)
										{
										?>
											<td align="left" valign="top" class="shoppingcartgiftwrap_dettd" colspan="<?php echo (4-$giftwrap_cnt)?>">&nbsp;</td>
										<?php
										}
										?>
										</tr>
										</table>
								<?php
									}
								?>
							</td>
						</tr>
		  <?php
		  			}
		  		}
			// Check whether giftwrap charge is there, if it is there then show the total of gift wrap
			if($cartData["totals"]["giftwrap"])
			{
			?>
			  <tr>
				<td colspan="5" align="right" valign="middle" class="shoppingcartcontent"><?php echo $Captions_arr['CART']['CART_GIFTWRAP_TOTAL']?></td>
				<td align="right" valign="middle" class="shoppingcartpriceC"><?php echo print_price($cartData["totals"]["giftwrap"],true)?></td>
			  </tr>
		  <?php
		  	}
			// Calling the function to decide the delivery details display section 
			$deliverydet_Arr = get_Delivery_Display_Details();
			if ($deliverydet_Arr['delivery_type'] != 'None') // Check whether any delivery method is selected for the site
			{
		  ?>
				<tr>
				<td colspan="6" align="right" valign="middle">
				<input type="hidden" name="cart_delivery_id" id="cart_delivery_id" value="<?php echo $deliverydet_Arr['delivery_id']?>"  />	
				 <table width="100%" border="0" cellspacing="1" cellpadding="1">
				 
				 <?php
				 	// Case if location is to be displayed
					if (count($deliverydet_Arr['locations']))
					{
				?>	
						 <tr>
						 <td width="53%" align="left" class="shoppingcartcontent"><?php echo $Captions_arr['CART']['CART_DELIVERY_LOC']?><a name="a_deliv">&nbsp;</a> </td>
						 <td width="47%" align="right" class="shoppingcartcontent">
							<?php
								
									echo generateselectbox('cart_deliverylocation',$deliverydet_Arr['locations'],$row_cartdet['location_id'],'','handle_form_submit(document.frm_cart,"save_commondetails","#a_deliv")');
							?>						</td>
					   </tr>
				   <?php
				   }
				   // Check whether any delivery group exists for the site
				   if (count($deliverydet_Arr['del_groups']))
				   {
				   ?>
					   <tr>
						 <td align="left" class="shoppingcartcontent"><?php echo $Captions_arr['CART']['CART_DELIVERY_OPT']?> </td>
						 <td align="right" class="shoppingcartcontent">
						  <?php
							echo generateselectbox('cart_deliveryoption',$deliverydet_Arr['del_groups'],$row_cartdet['delopt_det_id'],'','handle_form_submit(document.frm_cart,"save_commondetails","#a_deliv")');
						  ?>						 </td>
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
						<td colspan="6" align="center">
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
							<td class="shoppingcartcontent" colspan="4" align="center">
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
							<? print print_price($cartData["delivery"]["group".$i]["cost"],true)?></td>
						</tr>
					<?
					}				
				} 
		  	}
		  	// Check whether delivery is charged, then show the total after applying delivery charge
			if($cartData["totals"]["delivery"])
			{
			?>
			<tr>
				<td colspan="5" align="right" valign="middle" class="shoppingcartcontent"><?php echo $Captions_arr['CART']['CART_TOTAL_DELIVERY_CHARGES']?></td>
				<td align="right" valign="middle" class="shoppingcartpriceC"><?php echo print_price($cartData["totals"]["delivery"],true)?></td>
			  </tr>
			<?php
			}
		  	// Section to show the extra shipping cost
			 if($cartData["totals"]["extraShipping"])
			 {
			 ?>
				 <tr>
					<td colspan="5" align="right" valign="middle" class="shoppingcartcontent"><?php echo $Captions_arr['CART']['CART_EXTRA_SHIPPING']?></td>
					<td align="right" valign="middle" class="shoppingcartpriceC"><?php echo print_price($cartData["totals"]["extraShipping"],true)?></td>
				  </tr>
			 <?php	
			 }
		   // Section to show the tax details
			 if($cartData["totals"]["tax"])
			 {
			 ?>
					<tr>
						<td colspan="5" align="right" valign="top" class="shoppingcartcontent">
						<?php echo $Captions_arr['CART']['CART_TAX_CHARGE_APPLIED']?>
						<?	
							foreach($cartData["tax"] as $tax)
							{
								echo '<br/>('.$tax['tax_name']; ?> @ <? print $tax['tax_val']; ?>%)
						<?	
							}
						?>						</td>
						<td align="right" valign="top" class="shoppingcartpriceC">
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
				// If gift voucher or promotional code is valid
				if (($row_cartdet['promotionalcode_id']!=0 or $row_cartdet['voucher_id']!=0))
				{
				 ?>
				  <tr>
					<td colspan="5" align="right" valign="middle" class="shoppingcartcontent">
					<?php 
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
					?>					</td>
					<td align="right" valign="middle" class="shoppingcartpriceC">
					<?php 
					if ($row_cartdet['promotionalcode_id']!=0 and $cartData["bonus"]['type']=='promotional' and $cartData['totals']['pro_type']!='product')
						echo ($cartData['totals']['lessval']>0)?print_price($cartData['totals']['lessval'],true):0;
					elseif($row_cartdet['voucher_id']!=0 and $cartData["bonus"]['type']=='voucher')
						echo ($cartData['totals']['lessval']>0)?print_price($cartData['totals']['lessval'],true):0;
					
					?></td>
				  </tr>
		  <?php
		  		}
			 	if ($Settings_arr['show_cart_promotional_voucher']==1)
			 	{
					if ($cartData["bonus"]['type']=='' or $cartData["bonus"]['type']=='promotional' or $cartData["bonus"]['type']=='voucher')
					{
		  ?>
				  <tr>
					<td colspan="6" align="left" valign="middle"><div class="shopprodiv">
					  <table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
						  <td width="71%" align="left" class="shoppingcartcontent"><a name="a_prom">&nbsp;</a>
						  <?php	
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
						  ?>						  </td>
						  <td width="29%" align="right" class="shoppingcartcontent">
						  <?php
						 	 if ($cartData["bonus"]['type']=='voucher' or $cartData["bonus"]['type']=='promotional' or $cartData['totals']['promotional_type']=='product') // handle the case of logged in or not.
							{
							?>
									<input name="cart_savepromotional" type="hidden" id="cart_savepromotional" value="0"/>	
								 	<input name="cancel_promotionalcode" type="button" class="buttongray" id="cancel_promotionalcode" value="<?php echo $Captions_arr['CART']['CART_PROMO_CANCEL_BUTTON']?>" onclick="document.frm_cart.cart_savepromotional.value=1;handle_form_submit(document.frm_cart,'save_commondetails','#a_prom')" />
						  <?php
						  	}
						  	elseif ($cartData["bonus"]['type']=='')
							{
						  ?>
						  		<input name="cart_savepromotional" type="hidden" id="cart_savepromotional" value="0"/>
							  	<input name="cart_promotionalcode" type="text" id="cart_promotionalcode" size="12" />
							  	<input name="submit_promotionalcode" type="button" class="buttongray" id="submit_promotionalcode" value="<?php echo $Captions_arr['CART']['CART_PROMO_GO']?>" onclick="document.frm_cart.cart_savepromotional.value=1;handle_form_submit(document.frm_cart,'save_commondetails','')" />
						  <?php
						  	}
							
						  ?>
						  </td>
						</tr>
					  </table>
					</div></td>
				  </tr>
		  <?php
		  			}
		  		}
			// Bonus Points Earned
			
			if ($cartData["totals"]["bonus"] and $cust_id)
			{
			?>
				 <tr>
					<td colspan="4" align="right" valign="middle" class="shoppingcartcontent"><?php echo $Captions_arr['CART']['CART_TOTAL_POINTS_EARN']?>&nbsp;</td>
					<td colspan="2" align="center" valign="middle" class="shoppingcartpriceC"><?php echo $cartData["totals"]["bonus"]?></td>
				  </tr>
			<?php	
			}
			// Check whether the bonus points module is available in the site
			if (is_Feature_exists('mod_bonuspoints'))
			{
				if ($cartData["customer"]["customer_bonus"]) // does current customer have bonus points
				{
			?>
				 <tr>
					<td colspan="4" align="right" valign="middle" class="shoppingcartcontent"><a name="bonus">&nbsp;</a><?php echo $Captions_arr['CART']['CART_SPENT_BONUS_POINTS']?>(<? print (int)(($cartData["customer"]['customer_bonus']-$cartData["bonus"]["spending"])); ?> &nbsp;<?php echo $Captions_arr['CART']['CART_REMAINING']?>)&nbsp;</td>
					<td colspan="2" align="left" valign="middle" class="shoppingcartpriceC">
					<input type="hidden" name="maxBonusPoints"  class="input" value="<? print $cartData["customer"]["customer_bonus"]; ?>">
					<input type="text" name="spendBonusPoints" size="4" value="<? print $cartData["bonus"]["spending"]; ?>">
					<input type="hidden" name="leftBonusPoints" value="<? print $cartData["bonus"]["left"] ?>">
					<input  class="buttonred_cart" type="button" value="<?php echo $Captions_arr['CART']['CART_SPEND']?>" onclick="handle_form_submit(document.frm_cart,'save_commondetails','#bonus')">
					</td>
				  </tr>
				  <?php
				  	// Show the following only if any bonus point is spend
					if ($cartData["bonus"]["value"])
					{
					?>
					  <tr>
						<td colspan="4" align="right" valign="top" class="shoppingcartcontent"><?php echo $Captions_arr['CART']['CART_SPENT_BONUS_POINTS_VALUE']?> </td>
						<td colspan="2" align="right" valign="top" class="shoppingcartpriceC"><? echo print_price($cartData["bonus"]["value"],true);?></td>
					  </tr>
			<?php	
					}
				}
			}
		  	// show the total final price
			if($cartData["totals"]["bonus_price"])
			{
			?>
			 
			  <tr>
				<td colspan="5" align="right" valign="middle" class="shoppingcartcontent_total"><?php echo $Captions_arr['CART']['CART_TOTAL_FINAL_COST']?>&nbsp; </td>
				<td align="right" valign="middle" class="shoppingcartpriceC"><?php echo print_price($cartData["totals"]["bonus_price"],true)?></td>
			  </tr>
		  <?php
		  	}
		  	$rem_val = $cartData["totals"]["bonus_price"] - $str_reduceval;
			if($rem_val >0 and $str_reduceval>0)
			{
			?>
				<tr>
					<td colspan="5" align="right" valign="middle" class="shoppingcartcontent"><?php echo $Captions_arr['CART']['CART_LESS_AMT_LATER']?>&nbsp; </td>
					<td align="right" valign="middle" class="shoppingcartpriceC"><?php echo print_price($str_reduceval,true)?></td>
			  	</tr>
				<tr>
					<td colspan="5" align="right" valign="middle" class="shoppingcartcontent_total"><?php echo $Captions_arr['CART']['CART_TOTAL_AMT_NOW']?>&nbsp; </td>
					<td align="right" valign="middle" class="shoppingcartpriceC"><?php echo print_price($rem_val,true)?>
					<input type="hidden" name="store_reduceval" value="<?php echo $str_reduceval?>" />
					</td>
			  	</tr>
			<?php
			}	
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
			// Check whether google checkout is set for current site
			if($ecom_common_settings['paymethodKey']['GOOGLE_CHECKOUT']['paymethod_key'] == "GOOGLE_CHECKOUT")
			{
				$google_prev_req 		= $ecom_common_settings['paymethodKey']['GOOGLE_CHECKOUT']['payment_method_preview_req'];
				$google_recommended		= $ecom_common_settings['paymethodKey']['GOOGLE_CHECKOUT']['payment_method_google_recommended'];
				if($google_recommended ==0) // case if google checkout is set to work in the way google recommend
					$more_pay_condition = " AND paymethod_key<>'GOOGLE_CHECKOUT' ";
				else
					$more_pay_condition = '';
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
			{	
		  ?>
		  		<tr>
					<td colspan="6" align="left" valign="middle" class="google_header_text">
					<?php 

						echo $Captions_arr['CART']['CART_PAYMENT_MULTIPLE_MSG'];
					?>
					</td>
				</tr>		
		  <?php
			}
		  ?>	
          <tr>
            <td colspan="6" align="left" valign="middle">
			<a name="a_pay"></a>
			<?php
				
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
							if($row_paytypes['paytype_id']==$row_cartdet['paytype_id'])
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
									$add_text = ' (Credit Available '. print_price($payonaccount_remlimit,true).')';
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
					?>	
							
							  <table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
								  <td width="49%" align="right"><a name="a_pay">&nbsp;</a><?php echo $Captions_arr['CART']['CART_SEL_PAYTYPE']?></td>
									<td width="51%" align="right">
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
							$pay_maxcnt = 2;
							$pay_cnt	= 0;
					?>
							  
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
								  <tr>
									<td colspan="<?php echo $pay_maxcnt?>" class="cart_payment_header"><?php echo $Captions_arr['CART']['CART_SEL_PAYTYPE']?></td>
								  </tr>
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
								  ?>
										<td width="25%" align="left" class="shoppingcartcontent">
										<?php
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
											?>
											<input class="shoppingcart_radio" type="radio" name="cart_paytype" id="cart_paytype" value="<?php echo $row_paytypes['paytype_id']?>" <?php echo ($row_paytypes['paytype_id']==$row_cartdet['paytype_id'])?'checked="checked"':''?> <?php echo $on_change?>/><?php echo stripslashes($row_paytypes['paytype_caption']).$add_text?>
										</td>
								<?php
										$pay_cnt++;
										if ($pay_cnt>=$pay_maxcnt)
										{
											echo "</tr><tr>";
											$pay_cnt = 0;
										}
									}
									if ($pay_cnt<$pay_maxcnt)
									{
										echo "<td colspan=".($pay_maxcnt-$pay_cnt)." class='shoppingcartcontent'>&nbsp;</td>";
									}
								?>	
								  </tr>
								</table>
							
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
			?>
			</td>
          </tr>
           	<?php 
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
					  <tr>
						<td colspan="6" align="left" valign="middle">
							<?php
								$pay_maxcnt = 2;
								$pay_cnt	= 0;
							?>
								<table width="100%" border="0" cellspacing="0" cellpadding="0" id="paymethod_id">
								  <tr>
									<td colspan="<?php echo $pay_maxcnt?>" class="cart_payment_header"><?php echo $Captions_arr['CART']['CART_SEL_PAYGATEWAY']?></td>
								  </tr>
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
								  ?>
										<td width="25%" align="left" valign="top">
										<table width="100%" cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td align="left" valign="top" width="2%">
											<input class="shoppingcart_radio" type="radio" name="cart_paymethod" id="cart_paymethod" value="<?php echo $row_paymethods['paymethod_id']?>" <?php echo ($row_paymethods['paymethod_id']==$row_cartdet['paymethod_id'])?'checked="checked"':''?> <?php /*onclick="handle_form_submit(document.frm_cart,'save_commondetails','#a_pay')"*/?> />
											</td>
											<td align="left">
											<?php echo stripslashes($caption)?>
											</td>
										</tr>
										</table>
										</td>
								<?php
										$pay_cnt++;
										if ($pay_cnt>=$pay_maxcnt)
										{
											echo "</tr><tr>";
											$pay_cnt = 0;
										}
									}
									if ($pay_cnt<$pay_maxcnt)
									{
										echo "<td colspan=".($pay_maxcnt-$pay_cnt).">&nbsp;</td>";
									}
								?>	
								  </tr>
								</table>
						</td>
					 </tr>	
          <?php
		  			}
		  		}
		  	}
			
			} // End of $cartData["totals"]["bonus_price"] >0
		?>		  	
		
          <tr>
            <td colspan="6" align="right" valign="middle" class="shoppingcartcontent">
            
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
			
			  ?>
			  	  <div class="cart_continue_div"  align='left'>
				<?php
					if($ps_url) // show the continue shopping button only if ps_url have value
					{
				?>
				  
				  <input name="continue_submit" type="button" class="buttonred_cart" id="continue_submit" value="<?php echo $Captions_arr['CART']['CART_CONT_SHOP']?>" onclick="show_wait_button(this,'Please Wait...');window.location='<?php echo $ps_url?>'" />
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
				if($show_checkoutbutton==true)	
				{
			  ?>	
			  		<div class="cart_checkout_div"  align='right'>
             		 <input name="continue_checkout" type="button" class="buttonred_cart" id="continue_checkout" value="<?php echo $Captions_arr['CART']['CART_GO_CHKOUT']?>" onclick="handle_checkout_submit('<?php echo $ecom_hostname?>',0,'<?php echo $cartData["totals"]["bonus_price"]?>')" />
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
	       	</td>
          </tr>
		  <?php
		  }
			?>
		    </table>
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
					<td align="center" valign="middle" class="google_or">
					Or
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
	
		  if(!$cust_id and count($cartData['products']))
		  {
		  ?>
		  		<form name="frm_custlogin_cart" id="frm_custlogin_cart" method="post" action="" onsubmit="return validate_login(this)" class="frm_cls">
		  		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="shoppingcarttable">
         	 	<tr>
            		<td colspan="6" align="right" valign="middle">&nbsp;</td>
          		</tr>
         	 	<tr>
            		<td colspan="6" align="left" valign="middle" class="shoppingcartcontent">
					<table width="100%" border="0" cellspacing="1" cellpadding="1">
<tr>
	<td class="shoppingcartlogintdtext1" valign="top"><b><?php echo $Captions_arr['CART']['CART_SHOPPED_BEFORE']?>?</b><br />Please enter your details below to sign in  </td>
	<td width="34%" class="shoppingcartlogintdtext1">&nbsp;</td>
	<td width="45%" class="shoppingcartlogintdtext1" valign="top">
	<?php
	if($Settings_arr['hide_newuser']==0) // check whether new user page is to be displayed
	{
	echo $Captions_arr['CART']['CART_REGISTERED_CLICK_LINK'];
	}	
	?>	</td>
</tr>
<tr>
	<td width="21%" align="left" valign="middle"><?php echo $Captions_arr['CART']['CART_EMAIL']?></td>
	<td><input name="custlogin_uname" type="text" id="custlogin_uname" size="18" /></td>
	<td>&nbsp;</td>
</tr>
<tr>
	<td align="left" valign="middle"><?php echo $Captions_arr['CART']['CART_PASSWORD']?></td>
	<td><input name="custlogin_pass" id="custlogin_pass" type="password" size="18" /></td>
	<td>&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="left"><input name="custcartlogin_Submit" type="submit" class="buttonred_cart" value="<?php echo $Captions_arr['CART']['CART_LOGIN']?>" onClick="document.frm_custlogin_cart.pass_url.value = document.frm_cart.pass_url.value" /></td>
	<td align="right">
	<?php
	if($Settings_arr['hide_newuser']==0) // check whether new user page is to be displayed
	{
	?>
	<input name="new_user_cart" type="button" class="buttonred_cart" id="new_user_cart" value="<?php echo $Captions_arr['CART']['CART_SIGNUP']?>" onClick="window.location='<?php url_link('registration.html?pagetype=cart')?>'" />
	<?php
	}
	?>	</td>
</tr>
</table>
					
						
					</td>
          </tr>
		  </table>
		  <input type="hidden" name="cart_mod" value="show_cart" /> 
		  <input type="hidden" name="redirect_back" value="1" /> 
		  <input type="hidden" name="pass_url" id="pass_url" value="<?php echo $ps_url?>" />
		  </form>
		  <?php
		  }
		  ?>
      
		<?php	
		}
		
		// Defining function to show the checkout page
		function Show_Checkout()
		{
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents,$Settings_arr,$protectedUrl,$ecom_common_settings;
			$session_id 			= session_id();	// Get the session id for the current section
			$cust_id				= get_session_var("ecom_login_customer"); // Get the customer id from session
			$Captions_arr['CART'] 	= getCaptions('CART'); // Getting the captions to be used in this page
			
			$cartData = cartCalc(); // Calling the function to calculate the details related to cart
			// Calling function to get the messages to be shown in the cart page
			$cart_alert = get_CartMessages($_REQUEST['hold_section']);
			// Calling function to get the checkout details saved temporarly for current site in current session
			$saved_checkoutvals = get_CheckoutValues();
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
			
			
			
			// done to handle the case of protected or normal area	
			if($protectedUrl)
				$http = url_protected('index.php?req=cart&cart_mod=show_checkout',1);
			else 	
				$http = url_link('checkout.html',1);	
		?>	
			<form method="post" name="frm_checkout"  id="frm_checkout" class="frm_cls" action="<?php echo $http?>" onsubmit="return validate_checkout_fields(document.frm_checkout)">
			<input type="hidden" name="fpurpose" id="fpurpose" value="place_order_preview" />
			<input type="hidden" name="remcart_id" id="remcart_id" value="" />
			<input type="hidden" name="cart_mod" id="cart_mod" value="show_checkout" />
			<input type="hidden" name="hold_section" id="hold_section" value="" />
			<input type="hidden" name="save_checkoutdetails" id="save_checkoutdetails" value="" />
			
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="shoppingcarttable">
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
				
				?>
			  <tr>
				<td colspan="6" align="left" valign="middle"><div class="treemenu"><a href="http://<?php echo $ecom_hostname?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?php echo $Captions_arr['CART']['CART_CHECKOUTHEADING']?></div></td>
			  </tr>
			  <tr>
				<td align="left" valign="middle" class="shoppingcartheaderA"><?php echo $Captions_arr['CART']['CART_ITEM']?></td>
				<td align="left" valign="middle" class="shoppingcartheaderA"><?php echo $Captions_arr['CART']['CART_PRICE']?></td>
				<td align="center" valign="middle" class="shoppingcartheaderA"><?php echo $Captions_arr['CART']['CART_AVAIL']?></td>
				<td align="center" valign="middle" class="shoppingcartheaderA"><?php echo $Captions_arr['CART']['CART_DISCOUNT']?></td>
				<td align="center" valign="middle" class="shoppingcartheaderA"><?php echo $Captions_arr['CART']['CART_QTY']?></td>
				<td align="right" valign="middle" class="shoppingcartheaderA"><?php echo $Captions_arr['CART']['CART_TOTAL']?></td>
			  </tr>
		  <?php
		  if (count($cartData['products'])==0) // Done to show the message if no products in cart
		  {
			?>
				<tr>
					<td align="center" valign="middle" class="shoppingcartcontent" colspan="6">
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
				// Handling the case of product deposit
				if($products_arr['product_deposit'])
				{
					if ($products_arr['final_price']>$products_arr['product_deposit_less'])
						$str_reduceval	+= $products_arr['product_deposit_less'];
				}
			  ?>
				  <tr>
					<td align="left" valign="middle" class="<?php echo $trmainclass?>">
					<?php 
					// Check whether thumb nail is to be shown here
					if ($Settings_arr['thumbnail_in_viewcart']==1)
					{
					?>
						<a href="<?php url_product($products_arr['product_id'],$products_arr['product_name'],-1)?>" title="<?php echo stripslashes($products_arr['product_name'])?>">
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
						</a><br />
					<?php
					}
					?>
					<a href="<?php echo url_product($products_arr['product_id'],$products_arr['product_name'])?>" title="<?php echo $products_arr['product_name']?>" class="shoppingcartprod_link"><?php echo stripslashes($products_arr['product_name'])?></a>
					</td>
					<td align="left" valign="middle" class="<?php echo $tdpriceBclass?>"><?php echo print_price($products_arr['product_webprice'],true)?></td>
					<td align="center" valign="middle" class="<?php echo $trmainclass?>">
					<?php
						// Calling the function to check whether the current product is in preorder or not
						$preorder = check_Inpreorder($products_arr['product_id']);
						if ($preorder['in_preorder']=='Y')
							echo '<span class="cartinoutstock">Available on'.'<br />'.$preorder['in_date'].'</span>';
						else
						{
							if($products_arr['stock']>0 or $products_arr['product_alloworder_notinstock']=='Y')
								echo '<span class="cartinstock">In Stock</span>';
						}	
					?>
					</td>
					<td align="center" valign="middle" class="<?php echo $tdpriceBclass?>">
					-<?php 
							// if combo disc exists then that should be displayed otherwise the normal or promotional disc is to be shown
							if ($products_arr["prom_prodcode_disc"] and $products_arr["savings"]["product"]) // Case if promotional code disc is there for current product
								echo $Captions_arr['CART']['CART_PROM_DISC'].'<br/> '.print_price($products_arr["savings"]["product"],true);
							elseif ($products_arr["cust_disc_type"] !='' and $products_arr["savings"]["product"]) // Case if promotional code disc is there for current product
							{	
								switch($products_arr['cust_disc_type'])
								{
									case 'custgroup':
										echo 'Customer Group';
									break;
									case 'customer':
										echo 'Customer';
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
								echo $Captions_arr['CART']['CART_BULK_DISC'].'<br/> '.print_price($products_arr["savings"]["bulk"],true);
							}
							else
								echo print_price($products_arr["savings"]["product"],true);
					?>
					</td>
					<td align="center" valign="middle" class="<?php echo $trmainclass?>">
					<?php 
					if($products_arr['product_det_qty_type']=='DROP')
					{
						if (trim($products_arr['product_det_qty_drop_prefix'])!='')
							echo stripslashes($products_arr['product_det_qty_drop_prefix']).' ';
						echo $products_arr['cart_qty'];
						if (trim($products_arr['product_det_qty_drop_suffix'])!='')
							echo ' '.stripslashes($products_arr['product_det_qty_drop_suffix']);
					}
					else
						echo $products_arr["cart_qty"]
					?>
					</td>
					<td align="right" valign="middle" class="<?php echo $tdpriceAclass?>"><?php echo print_price($products_arr['final_price'],true)?></td>
				  </tr>
				  <?php
				  	// If variables exists for current product, show it in the following section
					if ($vars_exists) 
					{
				  ?>
					<tr>
						<td align="left" valign="middle" colspan="6" class="shoppingcartcontent">
						<?
						// show the variables for the product if any
						if ($products_arr['prod_vars']) 
						{
							//print_r($products_arr['prod_vars']);
							foreach($products_arr["prod_vars"] as $productVars)
							{
								if (trim($productVars['var_value'])!='')
									print "<span class='cartvariable'>".$productVars['var_name'].": ". $productVars['var_value']."</span><br />"; 
								else
									print "<span class='cartvariable'>".$productVars['var_name']."</span><br />"; 
									
							}	
						}
						// Show the product messages if any
						if ($products_arr['prod_msgs']) 
						{	
							foreach($products_arr["prod_msgs"] as $productMsgs)
							{
								print "<span class='cartvariable'>".$productMsgs['message_title'].": ". $productMsgs['message_value']."</span><br />"; 
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
				<td align="right" valign="middle" class="shoppingcartcontent">&nbsp;</td>
				<td align="right" valign="middle" class="shoppingcartcontent">&nbsp;</td>
				<td align="right" valign="middle" class="shoppingcartcontent">&nbsp;</td>
				<td align="right" valign="middle" class="shoppingcartcontent" colspan="2"><?php echo $Captions_arr['CART']['CART_TOTPRICE']?>&nbsp;</td>
				<td align="right" valign="middle" class="shoppingcartpriceC"><?php echo print_price($cartData["totals"]["subtotal"],true)?></td>
			  </tr>
		  <?php
		  		$module_name = 'mod_giftwrap';
				if(is_Feature_exists($module_name)) // Check whether giftwrap module exists for the current site
				{
					$show_giftdet = false;
					// Check whether gift wrap options are set for the product
					$sql_gift = "SELECT giftwrap_active,giftwrap_minprice,giftwrap_messageprice
									FROM 
										general_settings_site_giftwrap 
									WHERE 
										sites_site_id = $ecom_siteid 
									LIMIT 
										1";
					$ret_gift = $db->query($sql_gift);
					if ($db->num_rows($ret_gift))
					{
						$row_gift 		= $db->fetch_array($ret_gift);
						$show_giftdet 	= ($row_gift['giftwrap_active']==1)?true:false; 
					}
					// Show the following if giftwrap option is set for the site
					if ($show_giftdet and $row_cartdet['giftwrap_req']==1)
					{
			?>	
						<tr>
						<td colspan="3" align="left" valign="middle" class="shoppingcartcontent"><?php echo $Captions_arr['CART']['CART_GIFTWRAP_REQ']?> (<?php echo print_price($row_gift['giftwrap_minprice'],true)?>)</td>
						<td colspan="3" align="left" valign="middle" class="shoppingcartcontent"><?php if ($row_cartdet['giftwrap_req']==1) echo 'Yes'?></td>
						</tr>
					<?php
						if($row_cartdet['giftwrap_msg_req']==1)
						{
					?>
						<tr>
							<td colspan="3" align="left" valign="middle" class="shoppingcartcontent_indent"><?php echo $Captions_arr['CART']['CART_GIFTWRAP_MSG']?>(<?php echo print_price($row_gift['giftwrap_messageprice'],true)?>)</td>
							<td colspan="3" align="left" valign="middle" class="shoppingcartcontent"><?php echo $Captions_arr['CART']['CART_YES']?></td>
						</tr>
						<tr>
							<td colspan="3" align="left" valign="middle" class="shoppingcartcontent_indent"><?php echo $Captions_arr['CART']['CART_MSG']?></td>
							<td colspan="3" align="left" valign="middle" class="shoppingcartcontent"><?php echo nl2br(stripslashes($row_cartdet['giftwrap_msg']))?></td>
						</tr>
					<?php
						}
						if($row_cartdet['giftwrap_ribbon_id'])
						{
							$sql_ribbon = "SELECT ribbon_id,ribbon_name,ribbon_extraprice 
											FROM 
												giftwrap_ribbon 
											WHERE 
												ribbon_active = 1 
												AND ribbon_id=".$row_cartdet['giftwrap_ribbon_id'];
							$ret_ribbon = $db->query($sql_ribbon);
							if($db->num_rows($ret_ribbon))
							{
								$row_ribbon = $db->fetch_array($ret_ribbon);
							}
					?>
						<tr>
							<td colspan="3" align="left" valign="middle" class="shoppingcartcontent_indent"><?php echo $Captions_arr['CART']['CART_RIBBON']?>(<?php echo print_price($row_ribbon['ribbon_extraprice'],true)?>)</td>
							<td colspan="3" align="left" valign="middle" class="shoppingcartcontent"><?php echo stripslashes($row_ribbon['ribbon_name'])?></td>
						</tr>
					<?php	
						}
						if($row_cartdet['giftwrap_paper_id'])
						{
							$sql_paper = "SELECT paper_id,paper_name,paper_extraprice 
											FROM 
												giftwrap_paper 
											WHERE 
												paper_active = 1 
												AND paper_id = ".$row_cartdet['giftwrap_paper_id'];
							$ret_paper = $db->query($sql_paper);
							if($db->num_rows($ret_paper))
							{
								$row_paper = $db->fetch_array($ret_paper);
							}
					?>
						<tr>
							<td colspan="3" align="left" valign="middle" class="shoppingcartcontent_indent"><?php echo $Captions_arr['CART']['CART_PAPER']?>(<?php echo print_price($row_paper['paper_extraprice'],true)?>)</td>
							<td colspan="3" align="left" valign="middle" class="shoppingcartcontent"><?php echo stripslashes($row_paper['paper_name'])?></td>
						</tr>
					<?php	
						}
						if($row_cartdet['giftwrap_card_id'])
						{
							$sql_card = "SELECT card_id,card_name,card_extraprice 
											FROM 
												giftwrap_card 
											WHERE 
												card_active = 1 
												AND card_id = ".$row_cartdet['giftwrap_card_id'];
							$ret_card = $db->query($sql_card);
							if($db->num_rows($ret_card))
							{
								$row_card = $db->fetch_array($ret_card);
							}
					?>
						<tr>
							<td colspan="3" align="left" valign="middle" class="shoppingcartcontent_indent"><?php echo $Captions_arr['CART']['CART_CARDS']?>(<?php echo print_price($row_card['card_extraprice'],true)?>)</td>
							<td colspan="3" align="left" valign="middle" class="shoppingcartcontent"><?php echo stripslashes($row_card['card_name'])?></td>
						</tr>
					<?php	
						}
						if($row_cartdet['giftwrap_bow_id'])
						{
							$sql_bow = "SELECT bow_id,bow_name,bow_extraprice 
											FROM 
												giftwrap_bows 
											WHERE 
												bow_active = 1 
												AND bow_id = ".$row_cartdet['giftwrap_bow_id'];
							$ret_bow = $db->query($sql_bow);
							if($db->num_rows($ret_bow))
							{
								$row_bow = $db->fetch_array($ret_bow);
							}
					?>
						<tr>
							<td colspan="3" align="left" valign="middle" class="shoppingcartcontent_indent"><?php echo $Captions_arr['CART']['CART_BOWS']?>(<?php echo print_price($row_bow['bow_extraprice'],true)?>)</td>
							<td colspan="3" align="left" valign="middle" class="shoppingcartcontent"><?php echo stripslashes($row_bow['bow_name'])?></td>
						</tr>
					<?php	
						}
						
		  			}
		  		}
				// Check whether giftwrap charge is there, if it is there then show the total of gift wrap
				if($cartData["totals"]["giftwrap"])
				{
				?>
				  <tr>
					<td colspan="5" align="right" valign="middle" class="shoppingcartcontent"><?php echo $Captions_arr['CART']['CART_GIFTWRAP_TOTAL']?></td>
					<td align="right" valign="middle" class="shoppingcartpriceC"><?php echo print_price($cartData["totals"]["giftwrap"],true)?></td>
				  </tr>
			  <?php
				}
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
								<td colspan="3" align="left" valign="middle" class="shoppingcartcontent"><?php echo $Captions_arr['CART']['CART_DELIVERY_LOC']?></td>
								<td colspan="3" align="left" valign="middle" class="shoppingcartcontent"><?php echo stripslashes($row_loc['location_name'])?></td>
							</tr>
				<?php
						}
					}
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
								<td colspan="3" align="left" valign="middle" class="shoppingcartcontent_indent"><?php echo $Captions_arr['CART']['CART_DELIVERY_OPT']?></td>
								<td colspan="3" align="left" valign="middle" class="shoppingcartcontent"><?php echo stripslashes($row_grp['delivery_group_name'])?></td>
							</tr>
				<?php
						}
					}	
				if ($row_cartdet['split_delivery']==1)
				{
				?>
					<tr>
						<td colspan="3" align="left" valign="middle" class="shoppingcartcontent"><?php echo $Captions_arr['CART']['CART_DELIVERY_SPLIT_ACTIVATED']?></td>
						<td colspan="3" align="left" valign="middle" class="shoppingcartcontent"><?php echo $Captions_arr['CART']['CART_YES']?></td>
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
							<td class="shoppingcartcontent_indent" colspan="3" align="left">
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
							<td class="shoppingcartcontent" colspan="3" align="left">
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
					<td colspan="5" align="right" valign="middle" class="shoppingcartcontent"><?php echo $Captions_arr['CART']['CART_TOTAL_DELIVERY_CHARGES']?></td>
					<td align="right" valign="middle" class="shoppingcartpriceC"><?php echo print_price($cartData["totals"]["delivery"],true)?></td>
				  </tr>
		  <?php
		  		}
		  	}
		  	// Section to show the extra shipping cost
			 if($cartData["totals"]["extraShipping"])
			 {
			 ?>
				 <tr>
					<td colspan="5" align="right" valign="middle" class="shoppingcartcontent"><?php echo $Captions_arr['CART']['CART_EXTRA_SHIPPING']?></td>
					<td align="right" valign="middle" class="shoppingcartpriceC"><?php echo print_price($cartData["totals"]["extraShipping"],true)?></td>
				  </tr>
			 <?php	
			 }
			 // Section to show the tax details
			 if($cartData["totals"]["tax"])
			 {
			 ?>
					<tr>
						<td colspan="5" align="right" valign="top" class="shoppingcartcontent">
						<?php echo $Captions_arr['CART']['CART_TAX_CHARGE_APPLIED']?>
						<?	
							foreach($cartData["tax"] as $tax)
							{
								echo '<br/>('.$tax['tax_name']; ?> @ <? print $tax['tax_val']; ?>%)
						<?	
							}
						?>						</td>
						<td align="right" valign="top" class="shoppingcartpriceC">
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
					<td colspan="5" align="right" valign="middle" class="shoppingcartcontent">
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
					<td align="right" valign="middle" class="shoppingcartpriceC">
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
						<td colspan="5" align="right" valign="top" class="shoppingcartcontent"><?php echo $Captions_arr['CART']['CART_SPENT_BONUS_POINTS_VALUE']?> </td>
						<td align="right" valign="top" class="shoppingcartpriceC"><? echo print_price($cartData["bonus"]["value"],true);?></td>
					  </tr>
			<?php	
					}
				}
			}
			// Bonus Points Earned
				if ($cartData["totals"]["bonus"]>0 and $cust_id)
				{
			?>
				 <tr>
					<td colspan="3" align="left" valign="middle" class="shoppingcartcontent"><?php echo $Captions_arr['CART']['CART_TOTAL_POINTS_EARN']?>&nbsp;</td>
					<td colspan="3" align="center" valign="middle" class="shoppingcartcontent"><?php echo $cartData["totals"]["bonus"]?></td>
				  </tr>
			<?php	
			}
		  	// show the total final price
			if($cartData["totals"]["bonus_price"]>0)
			{
			?>
			 
			  <tr>
				<td colspan="5" align="right" valign="middle" class="shoppingcartcontent"><?php echo $Captions_arr['CART']['CART_TOTAL_FINAL_COST']?>&nbsp; </td>
				<td align="right" valign="middle" class="shoppingcartpriceC"><?php echo print_price($cartData["totals"]["bonus_price"],true)?></td>
			  </tr>
		  <?php
		  	}
			$rem_val = $cartData["totals"]["bonus_price"] - $str_reduceval;
			if($rem_val >0 and $str_reduceval>0)
			{
			?>
				<tr>
					<td colspan="5" align="right" valign="middle" class="shoppingcartcontent"><?php echo $Captions_arr['CART']['CART_LESS_AMT_LATER']?>&nbsp; </td>
					<td align="right" valign="middle" class="shoppingcartpriceC"><?php echo print_price($str_reduceval,true)?></td>
			  	</tr>
				<tr>
					<td colspan="5" align="right" valign="middle" class="shoppingcartcontent"><?php echo $Captions_arr['CART']['CART_TOTAL_AMT_NOW']?>&nbsp; </td>
					<td align="right" valign="middle" class="shoppingcartpriceC"><?php echo print_price($rem_val,true)?>
					<input type="hidden" name="store_reduceval" value="<?php echo $str_reduceval?>" />
					</td>
			  	</tr>
			<?php
			}		
		  }	
		  ?>
		  	  <tr>
				<td colspan="6" align="left" >&nbsp;	
			
			   </td>
			  </tr> 
			  	  <tr>
				<td colspan="6" align="left" >	
			<div class="cart_continue_div" align="left">
	             <input name="backto_cart" type="button" class="buttonred_cart" id="backto_cart" value="<?php echo $Captions_arr['CART']['CART_BACK_CARTPAGE']?>" onclick="show_wait_button(this,'Please Wait...');gobackto_cart('<?php echo ($protectedUrl)? base64_encode($ecom_hostname):$ecom_hostname?>')"/>
	         </div>  
			   </td>
			  </tr> 
		  <?php

		  		// Initializing the array to hold the values to be used in the javascript validation of the static and dynamic fields
				$chkout_Req			= $chkout_Req_Desc = $chkout_Email = $chkout_Confirm = $chkout_Confirmdesc = array();
				$chkout_Numeric 	= $chkout_multi = $chkout_multi_msg	= array();

				// Including the file to show the dynamic fields for checkout to the top of static fields
		  		
		  		$head_class  			= 'shoppingcartheader';
				$cur_pos 				= 'Top';
				$section_typ			= 'checkout';
				$formname 			= 'frm_checkout'; 
				$head_class  			= '';
				$colspan 				= '';
				$cont_leftwidth 		= '50%'; 
				$cont_rightwidth 	= '50%';
				$cellspacing 			= 1;
				$cont_class 			= 'shoppingcartcontent_noborder'; 
				$cellpadding 			= 3;		
				$colspan 	 			= 6;
				include 'show_dynamic_fields.php';

		?>
			
			<tr>
				<td colspan="6" align="left" class="shoppingcartheader"><?php echo $Captions_arr['CART']['CART_FILL_BILLING_DETAILS']?></td>
			</tr>
			<?php
				// Including the file to show the dynamic fields for checkout to the top of static fields in same section as that of static fields
				$colspan 		= 6;
				$head_class  	= 'shoppingcartheader';
				$cur_pos 		= 'TopInStatic';
				$section_typ	= 'checkout'; 
				$formname 	= 'frm_checkout';
				$head_class  	= '';
				$colspan 		= '';
				//$cont_leftwidth = '50%'; 
			//	$cont_rightwidth = '50%';
				$cellspacing 	= 1;
				$cont_class 	= 'shoppingcartcontent_noborder'; 
				$cellpadding 	= 3;		
				$colspan 	 	= 6;
				include 'show_dynamic_fields.php';


			?>	
		   <tr>
				<td colspan="6" align="right" valign="middle">
					<table width="100%" cellpadding="1" cellspacing="1" border="0">
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
							{		
								// Section to handle the case of required fields
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
								<td align="left" width="50%" class="shoppingcartcontent_noborder">
								<?php echo stripslashes($row_checkout['field_name']); if($row_checkout['field_req']==1) { echo '<span class="redtext">*</span>';}?>
								</td>
								<td align="left" width="50%">
								<?php
									echo get_Field($row_checkout['field_key'],$saved_checkoutvals,$cartData['customer']);
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
				// Including the file to show the dynamic fields for checkout to the bottom of static fields in same section as that of static fields
		  		$head_class  			= 'shoppingcartheader';
				$cur_pos 				= 'BottomInStatic';
				$section_typ			= 'checkout'; 
				$formname 			= 'frm_checkout';
				$head_class  			= '';
				$cellspacing			= 1;
				$cont_class 			= 'shoppingcartcontent_noborder'; 
				$cellpadding			 = 3;		
				$cont_leftwidth 		= '50%'; 
				$cont_rightwidth 	= '50%';
				$colspan 	 			= 6;
				include 'show_dynamic_fields.php';

			 // check whether billing and shipping address can be different in the site.
			  if ($Settings_arr['same_billing_shipping_checkout']==0)
			  {
			  ?>
			  	<tr>
					<td colspan="6" align="left" valign="middle" class="shoppingcartheader_noborder">
					<?php echo $Captions_arr['CART']['CART_IS_DELIVERY_ADDRESS_SAME']?>
					<select name="checkout_billing_same" onchange="handle_deliveryaddress_change(this)" >
					<option value="N" <?php echo ($saved_checkoutvals['checkout_billing_same']=='N')?'selected':''?>>No</option>
					<option value="Y" <?php echo ($saved_checkoutvals['checkout_billing_same']=='Y')?'selected':''?>>Yes</option>
					</select>
					</td>
				</tr>	
				<tr id="checkout_delivery_tr">
					<td colspan="6" align="left" valign="middle" class="shoppingcartcontent">
					<table width="100%" cellpadding="1" cellspacing="1" border="0">
					<tr>
					<td colspan="2" align="left" class="shoppingcartheader"><?php echo $Captions_arr['CART']['CART_FILL_DELIVERY_ADDRESS']?></td>
					</tr>	
						<?php
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
											$chkout_Email[] = "'".$row_checkout['field_key']."'";
										}
										$chkout_Req[]		= "'".$row_checkout['field_key']."'";
										$chkout_Req_Desc[]	= "'".$row_checkout['field_error_msg']."'"; 			
									}			
						?>
								<tr>
								<td align="left" width="50%" class="shoppingcartcontent_noborder">
								<?php echo stripslashes($row_checkout['field_name']); if($row_checkout['field_req']==1) { echo '<span class="redtext">*</span>';}?>
								</td>
								<td align="left" width="50%">
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
		  		$head_class  			= 'shoppingcartheader';
				$cur_pos 				= 'Bottom';
				$section_typ			= 'checkout'; 
				$formname 			= 'frm_checkout';
			
				$head_class  			= '';
				
				$checkout_caption	= 'Continue Checkout';
				$cont_leftwidth 		= '50%';
				$cont_rightwidth		= '50%';
				$cellspacing 			= 1;
//				$cont_class = 'shoppingcartcontent_noborder'; 
				$cellpadding 			= 6;		
				include 'show_dynamic_fields.php';
				
				// Check whether credit card details is to be taken or not
				if($cartData["payment"]["method"]['paymethod_takecarddetails']==1)
				{
					$checkout_caption = 'Checkout';
			?>
					 <tr>
						<td colspan="6" align="left" valign="middle" class="shoppingcartcontent">	
						<table width="100%" cellpadding="1" cellspacing="1" border="0">
						<tr>
						<td colspan="2" align="left" class="shoppingcartheader"><?php echo $Captions_arr['CART']['CART_CREDIT_CARD_DETAILS']?></td>
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
								<td align="left" width="50%" class="shoppingcartcontent_noborder">
								<?php echo stripslashes($row_checkout['field_name']); if($row_checkout['field_req']==1) { echo '<span class="redtext">*</span>';}?>
								</td>
								<td align="left" width="50%">
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
						<td colspan="6" align="left" valign="middle">
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
						<td colspan="6" align="left" valign="middle" class="shoppingcartheader">	
						<table width="100%" cellpadding="1" cellspacing="1" border="0">
						<tr>
						<td colspan="2" align="left" class="shoppingcartheader"><?php echo $Captions_arr['CART']['CART_CHEQUE_DETAILS']?></td>
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
								<td align="left" width="50%" class="shoppingcartcontent_noborder">
								<?php echo stripslashes($row_checkout['field_name']); if($row_checkout['field_req']==1) { echo '<span class="redtext">*</span>';}?>
								</td>
								<td align="left" width="50%">
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
			  <tr>
				<td colspan="6" align="left" valign="middle" class="cartterms">			
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
            <td colspan="6" align="right" valign="middle" class="shoppingcartcontent">			
            <div class="cart_continue_div" align="left">
	             <input name="backto_cart" type="button" class="buttonred_cart" id="backto_cart" value="<?php echo $Captions_arr['CART']['CART_BACK_CARTPAGE']?>" onclick="show_wait_button(this,'Please Wait...');gobackto_cart('<?php echo ($protectedUrl)? base64_encode($ecom_hostname):$ecom_hostname?>')"/>
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
						<?php /*?><input name="continue_checkout" type="submit" class="buttonred_cart" id="continue_checkout" value="<?php echo $checkout_caption?>"/><?php */?>
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
			</form>
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
				/* Checking the case of checkboxes or radio buttons */
				<?php
					if (count($chkout_multi))
					{
						for ($i=0;$i<count($chkout_multi);$i++)
						{
							echo 
								"
									var atleast_one = false;
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
		 <?php		
		}
		// Defining function to show the Order Preview page
		function Show_OrderPreview($return_order_arr)
		{
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,
					$Captions_arr,$inlineSiteComponents,$sitesel_curr,$default_Currency_arr,$ecom_testing,$ecom_themename,$components,
					$ecom_common_settings;
			$Captions_arr['CART'] 	= getCaptions('CART'); // Getting the captions to be used in this page
			$session_id 			= $sessid = session_id();	// Get the session id for the current section
			$cust_id				= get_session_var("ecom_login_customer"); // Get the customer id from session
			$cartData 				= cartCalc(); // Calling the function to calculate the details related to cart
			// Get the details regarding current order from orders table
			$back_to_cart = false;
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
								order_gift_voucher_number,promotional_code_code_id,promotional_code_code_number 
							FROM 
								orders 
							WHERE 
								order_id = ".$return_order_arr['order_id']." 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
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
										order_stock_combination_id 
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
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="shoppingcarttable">
			<tr>
				<td colspan="6" align="right" valign="middle" class="shoppingcartcontent">	
				<?php
					$display_option = 'ALL';
					// Including the file which hold the login for fields to be passed to payment gateway
					include 'order_preview_gateway_include.php';
				?>		 
				</td>
			</tr>
			<?php
			if($auto_submit==false)
			{
			?>
			  <tr>
				<td colspan="6" align="left" valign="middle"><div class="treemenu"><a href="http://<?php echo $ecom_hostname?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?php echo $Captions_arr['CART']['CART_CHECKOUTPREVIEWHEADING']?></div></td>
			  </tr>
			  <tr>
				<td align="left" valign="middle" class="shoppingcartheaderA"><?php echo $Captions_arr['CART']['CART_ITEM']?></td>
				<td align="left" valign="middle" class="shoppingcartheaderA"><?php echo $Captions_arr['CART']['CART_PRICE']?></td>
				<td align="center" valign="middle" class="shoppingcartheaderA"><?php echo $Captions_arr['CART']['CART_AVAIL']?></td>
				<td align="center" valign="middle" class="shoppingcartheaderA"><?php echo $Captions_arr['CART']['CART_DISCOUNT']?></td>
				<td align="center" valign="middle" class="shoppingcartheaderA"><?php echo $Captions_arr['CART']['CART_QTY']?></td>
				<td align="right" valign="middle" class="shoppingcartheaderA"><?php echo $Captions_arr['CART']['CART_TOTAL']?></td>
			  </tr>
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
					<td align="left" valign="middle" class="<?php echo $trmainclass?>">
					<?php 
					// Check whether thumb nail is to be shown here
					if ($Settings_arr['thumbnail_in_viewcart']==1)
					{
					?>
						<a href="<?php url_product($row_orddet['products_product_id'],$row_orddet['product_name'],-1)?>" title="<?php echo stripslashes($row_orddet['product_name'])?>">
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
					<a href="<?php echo url_product($row_orddet['products_product_id'],$row_orddet['product_name'])?>" title="<?php echo stripslashes($row_orddet['product_name'])?>" class="shoppingcartprod_link"><?php echo stripslashes($row_orddet['product_name'])?></a>
					</td>
					<td align="left" valign="middle" class="<?php echo $tdpriceBclass?>"><?php echo print_price($row_orddet['order_retailprice'],true)?></td>
					<td align="center" valign="middle" class="<?php echo $trmainclass?>">
					<?php
						// Calling the function to check whether the current product is in preorder or not
						if ($row_orddet['order_preorder']=='Y')
							echo '<span class="cartinoutstock">Available on'.'<br />'.$row_orddet['order_preorder_available_date'].'</span>';
						else
						{
							echo '<span class="cartinstock">In Stock</span>';
						}	
					?>
					</td>
					<td align="center" valign="middle" class="<?php echo $tdpriceBclass?>">
					-<?php 
							if ($row_orddet["order_discount"]>0)
							{
								// if combo disc exists then that should be displayed otherwise the normal or promotional disc is to be shown
								if ($row_orddet["order_discount_type"]=='promotional') // Case if promotional code disc is there for current product
									echo $Captions_arr['CART']['CART_PROM_DISC'].' '.print_price($row_orddet["order_discount"],true);
								elseif($row_orddet["order_discount_type"]=='combo') // Check whether combo discount is there
								{
									echo $Captions_arr['CART']['CART_COMBO_DISC'].' '.print_price($row_orddet["order_discount"],true);
								}
								elseif($row_orddet["order_discount_type"]=='bulk') // Check whether bulk discount is there
								{
									echo $Captions_arr['CART']['CART_BULK_DISC'].' '.print_price($row_orddet["order_discount"],true);
								}
								else
									echo print_price($row_orddet["order_discount"],true);
							}		
					?>
					</td>
					<td align="center" valign="middle" class="<?php echo $trmainclass?>">
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
					<td align="right" valign="middle" class="<?php echo $tdpriceAclass?>"><?php echo print_price($row_orddet['order_rowtotal'],true)?></td>
				  </tr>
				  <?php
				  	// If variables exists for current product, show it in the following section
					if ($vars_exists) 
					{
				  ?>
					<tr>
						<td align="left" valign="middle" colspan="6" class="shoppingcartcontent">
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
				<td align="right" valign="middle" class="shoppingcartcontent">&nbsp;</td>
				<td align="right" valign="middle" class="shoppingcartcontent">&nbsp;</td>
				<td align="right" valign="middle" class="shoppingcartcontent">&nbsp;</td>
				<td align="right" valign="middle" class="shoppingcartcontent" colspan="2"><?php echo $Captions_arr['CART']['CART_TOTPRICE']?>&nbsp;</td>
				<td align="right" valign="middle" class="shoppingcartpriceC"><?php echo print_price($row_ord["order_subtotal"],true)?></td>
			  </tr>
		  <?php
				if($row_ord['order_giftwrap']=='Y') // Check whether giftwrap module exists for the current site
				{
					// Show the following if giftwrap option is set for the site
			?>	
						<tr>
						<td colspan="3" align="left" valign="middle" class="shoppingcartcontent"><?php echo $Captions_arr['CART']['CART_GIFTWRAP_REQ']?> (<?php echo print_price($row_ord['order_giftwrap_minprice'],true)?>)</td>
						<td colspan="3" align="left" valign="middle" class="shoppingcartcontent"><?php if ($row_ord['order_giftwrap']=='Y') echo 'Yes'?></td>
						</tr>
					<?php
						if($row_ord['order_giftwrapmessage']=='Y')
						{
					?>
						<tr>
							<td colspan="3" align="left" valign="middle" class="shoppingcartcontent_indent"><?php echo $Captions_arr['CART']['CART_GIFTWRAP_MSG']?>(<?php echo print_price($row_ord['order_giftwrap_message_charge'],true)?>)</td>
							<td colspan="3" align="left" valign="middle" class="shoppingcartcontent">Yes</td>
						</tr>
						<tr>
							<td colspan="3" align="left" valign="middle" class="shoppingcartcontent_indent"><?php echo $Captions_arr['CART']['CART_MSG']?></td>
							<td colspan="3" align="left" valign="middle" class="shoppingcartcontent"><?php echo nl2br(stripslashes($row_ord['order_giftwrapmessage_text']))?></td>
						</tr>
					<?php
						}
						$sql_giftwrap_det = "SELECT giftwrap_name,giftwrap_price,giftwrap_type 
												FROM 
													order_giftwrap_details 
												WHERE 
													orders_order_id = ".$return_order_arr['order_id'];
						$ret_giftwrap_det = $db->query($sql_giftwrap_det);
						if ($db->num_rows($ret_giftwrap_det))
						{
							while ($row_giftwrap_det = $db->fetch_array($ret_giftwrap_det))
							{
					?>
								<tr>
									<td colspan="3" align="left" valign="middle" class="shoppingcartcontent_indent">
									<?php 
										switch($row_giftwrap_det['giftwrap_type'])
										{
											case 'ribbon':
												$gift_caption = 'Ribbon';
											break;
											case 'paper':
												$gift_caption = 'Paper';
											break;
											case 'card':
												$gift_caption = 'Card';
											break;
											case 'bow':
												$gift_caption = 'Bow';
											break;
										};
									echo $gift_caption?>(<?php echo print_price($row_giftwrap_det['giftwrap_price'],true)?>)</td>
									<td colspan="3" align="left" valign="middle" class="shoppingcartcontent"><?php echo stripslashes($row_giftwrap_det['giftwrap_name'])?></td>
								</tr>
					<?php		
							}
					?>
								<tr>
									<td colspan="5" align="right" valign="middle" class="shoppingcartcontent"><?php echo $Captions_arr['CART']['CART_GIFTWRAP_TOTAL']?></td>
									<td align="right" valign="middle" class="shoppingcartpriceC"><?php echo print_price($row_ord["order_giftwraptotal"],true)?></td>
								</tr>
					<?php		
						}
			}	
			
			if ($row_ord['order_deliverytype'] != 'None') // Check whether any delivery method is selected for the site
			{
				 	// Case if location is to be displayed
					if ($row_ord['order_deliverylocation'])
					{
				?>	
							<tr>
								<td colspan="3" align="left" valign="middle" class="shoppingcartcontent"><?php echo $Captions_arr['CART']['CART_DELIVERY_LOC']?></td>
								<td colspan="3" align="left" valign="middle" class="shoppingcartcontent"><?php echo stripslashes($row_ord['order_deliverylocation'])?></td>
							</tr>
				<?php
					}
					// Check whether any delivery group exists for the site
					if ($row_ord['order_delivery_option'])
					{
				?>	
							<tr>
								<td colspan="3" align="left" valign="middle" class="shoppingcartcontent_indent"><?php echo $Captions_arr['CART']['CART_DELIVERY_OPT']?></td>
								<td colspan="3" align="left" valign="middle" class="shoppingcartcontent"><?php echo stripslashes($row_ord['order_delivery_option'])?></td>
							</tr>
				<?php
					}	
				if ($row_ord['order_splitdeliveryreq']==1)
				{
				?>
					<tr>
						<td colspan="3" align="left" valign="middle" class="shoppingcartcontent"><?php echo $Captions_arr['CART']['CART_DELIVERY_SPLIT_ACTIVATED']?></td>
						<td colspan="3" align="left" valign="middle" class="shoppingcartcontent"><?php echo $Captions_arr['CART']['CART_YES']?></td>
					</tr>
				<?php	
				}
				// Check whether delivery is charged, then show the total after applying delivery charge
				if($row_ord["order_deliverytotal"]>0)
				{
				?>
				  <tr>
					<td colspan="5" align="right" valign="middle" class="shoppingcartcontent"><?php echo $Captions_arr['CART']['CART_TOTAL_DELIVERY_CHARGES']?></td>
					<td align="right" valign="middle" class="shoppingcartpriceC"><?php echo print_price($row_ord["order_deliverytotal"],true)?></td>
				  </tr>
		  <?php
		  		}
		  	}
		  	// Section to show the extra shipping cost
			 if($row_ord["order_extrashipping"]>0)
			 {
			 ?>
				 <tr>
					<td colspan="5" align="right" valign="middle" class="shoppingcartcontent"><?php echo $Captions_arr['CART']['CART_EXTRA_SHIPPING']?></td>
					<td align="right" valign="middle" class="shoppingcartpriceC"><?php echo print_price($row_ord["order_extrashipping"],true)?></td>
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
						<td colspan="5" align="right" valign="top" class="shoppingcartcontent">
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
						<td align="right" valign="top" class="shoppingcartpriceC">
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
					<td colspan="5" align="right" valign="middle" class="shoppingcartcontent">
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
					<td align="right" valign="middle" class="shoppingcartpriceC">
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
					<td colspan="5" align="right" valign="middle" class="shoppingcartcontent">
					<?php 
						echo $prom_caption;
					?>
					</td>
					<td align="right" valign="middle" class="shoppingcartpriceC">
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
						<td colspan="5" align="right" valign="top" class="shoppingcartcontent"><?php echo $Captions_arr['CART']['CART_SPENT_BONUS_POINTS_VALUE']?> </td>
						<td align="right" valign="top" class="shoppingcartpriceC"><? echo print_price($row_ord['order_bonuspoint_discount'],true);?></td>
					  </tr>
			<?php	
				}
			}
			// Bonus Points Earned
				if ($row_ord["order_bonuspoint_inorder"]>0)
				{
			?>
				  <tr>
					<td colspan="3" align="left" valign="middle" class="shoppingcartcontent"><?php echo $Captions_arr['CART']['CART_TOTAL_POINTS_EARN']?>&nbsp;</td>
					<td colspan="3" align="left" valign="middle" class="shoppingcartcontent"><?php echo $row_ord["order_bonuspoint_inorder"]?></td>
				  </tr>
			<?php	
			}
		  	// show the total final price
			if ($row_ord['order_totalprice']>0)
			{
			?>
			 
			  <tr>
				<td colspan="5" align="right" valign="middle" class="shoppingcartcontent"><?php echo $Captions_arr['CART']['CART_TOTAL_FINAL_COST']?>&nbsp; </td>
				<td align="right" valign="middle" class="shoppingcartpriceC"><?php echo print_price($row_ord['order_totalprice'],true)?></td>
			  </tr>
		  <?php
		  	}
			$rem_val = $row_ord['order_totalprice'] - $row_ord['order_deposit_amt'];
			if($rem_val>0 and $row_ord['order_deposit_amt']>0)
			{
			?>
				<tr>
					<td colspan="5" align="right" valign="middle" class="shoppingcartcontent"><?php echo $Captions_arr['CART']['CART_LESS_AMT_LATER']?>&nbsp; </td>
					<td align="right" valign="middle" class="shoppingcartpriceC"><?php echo print_price($row_ord['order_deposit_amt'],true)?></td>
			  	</tr>
				<tr>
					<td colspan="5" align="right" valign="middle" class="shoppingcartcontent"><?php echo $Captions_arr['CART']['CART_TOTAL_AMT_NOW']?>&nbsp; </td>
					<td align="right" valign="middle" class="shoppingcartpriceC"><?php echo print_price($rem_val,true)?>
					</td>
			  	</tr>
			<?php
			}		
				// Including the file to show the dynamic fields for orders to the give position of static fields
				$cur_pos 		= 'Top';
				$show_header	= 1;
				include 'show_dynamic_fields_orders.php';
		?>	
			<tr>
				<td colspan="6" align="left" class="shoppingcartheader"><?php echo $Captions_arr['CART']['CART_BILL_DETAILS']?></td>
			</tr>
			<?php
					// Including the file to show the dynamic fields for orders to the give position of static fields
					$cur_pos 		= 'TopInStatic';
					$show_header	= 0;
					include 'show_dynamic_fields_orders.php';
					
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
								<td align="left" colspan="3" class="shoppingcartcontent_noborder">
								<?php echo stripslashes($row_checkout['field_name'])?>
								</td>
								<td align="left" colspan="3">
								<?php
									echo stripslashes($row_ord[$row_checkout['field_orgname']]);
								?>
								</td>
							</tr>
				<?php
						}
					}
				// Including the file to show the dynamic fields for orders to the give position of static fields
				$cur_pos 		= 'BottomInStatic';
				$show_header	= 0;
				include 'show_dynamic_fields_orders.php';
				
			  // check whether billing and shipping address can be different in the site.
			  if ($row_del['delivery_same_as_billing']==1)
			  {
			  ?>
			  	<tr>
					<td colspan="6" align="left" valign="middle" class="shoppingcartheader_noborder">
					<?php echo $Captions_arr['CART']['CART_DELIVERY_SAME_BILLADDRESS']?>
					</td>
				</tr>	
			<?php
				}
				else
				{				
				?>
				<tr>
					<td colspan="6" align="left" valign="middle" class="shoppingcartheader_noborder">
					<?php echo $Captions_arr['CART']['DELIVERY_ADDRESS']?>
					</td>
				</tr>	
				<?php
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
								<td align="left" colspan="3" class="shoppingcartcontent_noborder">
								<?php echo stripslashes($row_checkout['field_name'])?>
								</td>
								<td align="left" colspan="3">
								<?php
									echo stripslashes($row_del[$row_checkout['field_orgname']]);
								?>
								</td>
							</tr>
				<?php
						}
					}		

				 }
			
				// Including the file to show the dynamic fields for orders to the give position of static fields
				$cur_pos 		= 'Bottom';
				$show_header	= 1;
				include 'show_dynamic_fields_orders.php';
				
			?>
			  <tr>
				<td colspan="3" align="left" valign="middle" class="shoppingcartcontent">			
					<?php echo $Captions_arr['CART']['CART_PAYMENT_TYPE']?>
				</td>
				<td colspan="3" align="left" valign="middle" class="shoppingcartcontent">			
				<?php 
					echo getpaymenttype_Name($row_ord['order_paymenttype']);
				?>
				</td>
			  </tr>
			  <?php
			  	if($row_ord['order_paymenttype'] =='credit_card')
				{
			  ?>
				  <tr>
					<td colspan="3" align="left" valign="middle" class="shoppingcartcontent">			
						<?php echo $Captions_arr['CART']['CART_GATEWAY_USED']?>
					</td>
					<td colspan="3" align="left" valign="middle" class="shoppingcartcontent">			
					<?php 
					// Get the name of payment method to be shown to the user
					echo getpaymentmethod_Name($row_ord['order_paymentmethod']);
					?>
					</td>
				  </tr>
			  <?php
			  	}
			}	 //auto sumit end
			  ?>
			<tr>
            <td colspan="6" align="right" valign="middle" class="shoppingcartcontent">	
           	<?php
				$display_option = 'BUTTON_ONLY';
				// Including the file which hold the login for fields to be passed to payment gateway
				include 'order_preview_gateway_include.php';
			?>			 
			</td>
          </tr>
		   </table>
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
								order_cpc_click_pm_id, order_cost_per_click_id  
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
						var google_conversion_id 		= <?php echo $ecom_adword_conversionid?>; 			<?php /*1050720287;*/?>
						var google_conversion_language 	= "<?php echo $ecom_adword_conversionlanguage?>"; 	<?php /*"en_GB";*/?>
						var google_conversion_format 	= "<?php echo $ecom_adword_conversionformat?>";		<?php /*"1";*/?>
						var google_conversion_color 	= "<?php echo $ecom_adword_conversioncolor?>";		<?php /*"FFFFFF";*/?>
						if (<?=$total_price?>) 
						{
						  var google_conversion_value 	= <?=$total_price?>;
						}
						var google_conversion_label 	= "<?php echo $ecom_adword_conversionlabel?>"; 		<?php /*"purchase";*/ ?>
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
				$succ_script = trim($ecom_success_script);
				$succ_script = str_replace('[TOTAL_PRICE]',$total_price,trim($succ_script));
				echo stripslashes($succ_script);
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
				<?php echo $Captions_arr['CART']['CART_CHECKOUT_SUCCESS_MSG']?>
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
	};	
	
?>