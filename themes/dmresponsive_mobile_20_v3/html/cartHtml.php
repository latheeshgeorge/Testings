<?php
	/*############################################################################
	# Script Name 		: cartHtml.php
	# Description 		: Page which holds the display logic for cart and checkout
	# Coded by 			: Sny
	# Created on		: 18-Aug-2009
	# Modified by		: 
	# Modified On		: 
	##########################################################################*/
	class cart_Html
	{
		// Defining function to show the cart details
		function Show_Cart()
		{ 
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,
					$Captions_arr,$inlineSiteComponents,$sitesel_curr,$default_Currency_arr,$ecom_testing,$ecom_themename,$components,
					$ecom_common_settings,$image_path,$ecom_site_delivery_location_country_map; 
					//print_r($_REQUEST);
			if(check_IndividualSslActive())
			{
				$ecom_selfhttp = "https://";
			}
			else
			{
				$ecom_selfhttp = "http://";
			}
			$session_id 			= session_id();	// Get the session id for the current section
			$cust_id				= get_session_var("ecom_login_customer"); // Get the customer id from session
			$Captions_arr['CART'] 	= getCaptions('CART'); // Getting the captions to be used in this page
			$Captions_arr['CUST_LOGIN'] = getCaptions('CUST_LOGIN');
			$cartData 				= cartCalc(); // Calling the function to calculate the details related to cart
			// Calling function to get the messages to be shown in the cart page
			$cart_alert_arr 			= get_CartMessages($_REQUEST['hold_section'],1);
			$cart_alert					= $cart_alert_arr['msg'];
				
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
				//print_r($row_cartdet);
						$pass_fnpaytypeid = ($row_cartdet['paytype_id'])?$row_cartdet['paytype_id']:0;

			// Section to decide whether the promotional code popup message is to be displayed
			$prom_alert					= '';
			if ($row_cartdet['promotionalcode_id']!=0 and $cartData["bonus"]['type']=='promotional' and $cartData['totals']['promotional_type']!='product' and $cartData['totals']['promotional_type']!='freeproduct') 
			{
				if ($cartData['totals']['lessval']<=0 and $cartData["totals"]["prom_cust_msg"]!='')
				{
					$prom_alert = str_replace('[min_amt]',print_price($cartData["bonus"]['promotion']['code_minimum']),$Captions_arr['CART']['CART_PROM_MIN_REQ_MSG']);
					if($prom_alert!='')
						$this->showpromotional_error_msg($prom_alert);
				}
			}
			 if (count($cartData['products'])>0)
             { 
				$show_checkoutbutton = true;
				if($Settings_arr['forcecustomer_login_checkout'] and !$cust_id)
				{ 
				$show_checkoutbutton = false;
				}           
             }
			
						/*$HTML_treemenu = '<div class="breadcrumbs">
    <div class="container">
      <div class="row">
        <div class="col-xs-12">
          <ul><li class="home"><a  href="'.url_link('',1).'" title="'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a><span>&#8594; </span></li>
				 <li class="category13">'.stripslash_normal($Captions_arr['CART']['CART_MAINHEADING']).'</li>
				 </ul>
        </div>
      </div>
    </div>
  </div>';	*/
  $HTML_treemenu = '	<div class="breadcrump"><nav class="breadcrumb">
  <a class="breadcrumb-item" href="'.url_link('',1).'" title="'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a>

  <span class="breadcrumb-item active">'.stripslash_normal($Captions_arr['CART']['CART_MAINHEADING']).'</span>
</nav></div>';
					
	  		
	  		
		
			
		?>
 <?php	
				if($cart_alert and count($cartData['products'])>0)
                            {
                                ?>
              <table class="table">
              <tr>
              <td>
					
                              
                                    <div class="alert alert-success">
                                    - <?php echo $cart_alert?> -
                                    </div>
                                   
                               
              </td>
              </tr>
              </table>
               <?php
                  }
               ?>
        <div class="container-fluid">
			<?php
			echo $HTML_treemenu;
			?>
        <div class="row">
			
	<div class="container-fluid">
         
          <div class="containerA">
			  <form method="post" name="frm_cart"  id="frm_cart"  action="<?php url_link('cart.html')?>">
                 <input type="hidden" name="fpurpose" value="" />
			<input type="hidden" name="remcart_id" value="" />
			<input type="hidden" name="cart_mod" value="show_cart" />
			<input type="hidden" name="hold_section" value="" />
			<input type="hidden" name="ajax_div_holder" id="ajax_div_holder" value="" />
            <div class="container-fluidD">
					
					 <?php
          //$Settings_arr['forcecustomer_login_checkout'] = 1;
			if($cart_alert_arr['err']==1) // Check whether this cart message is to be displayed in a div or in above cart as normal error message
			{
				$this->show_error_msg($cart_alert);
				$cart_alert = '';
			}
                     ?>
					<table class="cart_tableA" width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tbody><tr>
                            <td colspan="10">
                            <?
                            $HTML_topdesc ='';
                            $HTML_topdesc .='<div class="cart_top_outer">';
                            if (count($cartData['products'])>0)
                            { // Check whether logged in 
                                $HTML_topdesc .= '<div class="cart_top_info">';
                                if ($cust_id) // Case logged in 
                                {
									 $ctsr_arr = array('Mr.','Mrs.','Miss.','Ms.','M/s.','Dr.','Sir.','Rev.');
				                       $ctrp_arr = array('','','','','','','','');
                                      $HTML_topdesc .=' <div class="cart_top_info_name">'.stripslash_normal($Captions_arr['CART']['CART_LOGGED_IN_AS']).'&nbsp;'.str_replace($ctsr_arr,$ctrp_arr,get_session_var('ecom_login_customer_shortname')).'</div>
                                      <div class="cart_top_info_link">'.stripslash_normal($Captions_arr['CART']['CART_IF_YOU_NOT_LOG']).'&nbsp;<a href="'.$ecom_selfhttp.$ecom_hostname.'/logout.html?rets=1" title="Logout" class="cartlogin_link">'.stripslash_normal($Captions_arr['CART']['CART_HERE']).'</a>&nbsp;'.stripslash_normal($Captions_arr['CART']['CART_TO_LOGOUT']).' </div>';
                                }
                                else
                                {
                                    $HTML_topdesc .='<div class="cart_top_info_link">'.stripslash_normal($Captions_arr['CART']['CART_NOT_LOGGED_IN']).'&nbsp;<a href="'.url_link('custlogin.html',1).'?redirect_back=1&pagetype=cart" title="Login" class="cartlogin_link">'.stripslash_normal($Captions_arr['CART']['CART_HERE']).'</a>&nbsp;'.stripslash_normal($Captions_arr['CART']['CART_TO_LOGIN']).'</div>';
                                }
                                $HTML_topdesc .= '</div>';
                            }
                            switch($Settings_arr['config_continue_shopping'])
                            {
                                case 'home':
                                // Calling function to get the url to which customer to be taken back when hit the Continue shopping button
                                $ps_url 	= $ecom_selfhttp.$ecom_hostname;
                                break;
                                default:
                                // Calling function to get the url to which customer to be taken back when hit the Continue shopping button
                                $ps_url = get_continueURL($_REQUEST['pass_url']);
                                break;
                            }	
                            $HTML_topdesc .= '<div class="cart_top_links">';
                            if (count($cartData['products'])>0)
                            { 
                                // Check whether logged in 
                                // Handling the case of login required before checkout.
                                $show_checkoutbutton = true;
                                if($Settings_arr['forcecustomer_login_checkout'] and !$cust_id)
                                {
                                    $show_checkoutbutton = false;
                                }
                                /*if($show_checkoutbutton==true)	
                                {
                                $HTML_topdesc .= '<div class="cart_shop_chkout"><div> <a href="#" onClick="handle_checkout_submit(\''.$ecom_hostname.'\',0,\''.$cartData["totals"]["bonus_price"].'\')">'.stripslash_normal($Captions_arr['CART']['CART_GO_CHKOUT']).'</a></div></div>';
                                }
                                else
                                {
                                $HTML_topdesc .= "<div class='cart_checkout_div' align='right'><span class='red_msg'>You need to login to continue to checkout</span></div>";
                                }*/
                            }
                            if($_REQUEST['cont_pass_val'] !='')
								$ps_url = $_REQUEST['cont_pass_val'];
                            if(!$ps_url)
                            {
							 $ps_url 	= $ecom_selfhttp.$ecom_hostname;
							}
                            if($ps_url) // show the continue shopping button only if ps_url have value
                            {
                                $HTML_topdesc .= '<div class="cart_shop_contA"><div><a class="btn btn-warning btn-shop" href="#" onClick="show_wait_button(this,\'Please Wait...\');window.location=\''.$ps_url.'\'" class="cupid-green"><i class="fa fa-angle-left"></i>'.stripslash_normal($Captions_arr['CART']['CART_CONT_SHOP']).'</a></div></div>';
                            }
                            else
                            $HTML_topdesc .= '&nbsp;';
                            $HTML_topdesc .= '</div>';
                            $HTML_topdesc .= '</div>';  
                            echo $HTML_topdesc;  
                            ?>
                            </td>
                        </tr>
                    </tbody></table>
              
               
             
              <table class="table table-bordered table-striped cart-page" >
               <thead>
                <tr>
                  <th><?php echo stripslash_normal($Captions_arr['CART']['CART_ITEM'])?></th>
                  <th></th>
                  <th><?php echo stripslash_normal($Captions_arr['CART']['CART_QTY'])?></th>
                  <th><?php echo stripslash_normal($Captions_arr['CART']['CART_DISCOUNT'])?></th>
                  <th><?php echo stripslash_normal($Captions_arr['CART']['CART_TOTAL'])?></th>
                </tr>
               </thead>
               
               
               
               <tbody>
				    
												
				   <?php
                                        if (count($cartData['products'])==0) // Done to show the message if no products in cart
                                        {
                                        ?>
                                        <tr >
                                            <th align="center" valign="middle" class="shoppingcart_noitem" colspan="5">
                                            <?php echo stripslash_normal($Captions_arr['CART']['CART_NO_PRODUCTS'])?></th>
                                        </tr>	
                                        <?php
                                        }
                                        else
                                        {
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
												
												
												// consider the extra shipping only if delivery is not free
                                            if($cartData["totals"]['location_based_free_delivery_check']==1) // case if free delivery is to be checked based on delivery location
                                            {
                                                if($cartData["totals"]['full_free_delivery']==1 or ($products_arr["product_freedelivery"]==1 and $cartData["totals"]['location_based_free_delivery']==1)) 
                                                $all_prods_free_delivery_cnt++;
                                            }
                                            else
                                            {
                                                if($cartData["totals"]['full_free_delivery']==1 or $products_arr["product_freedelivery"]==1) 
                                                $all_prods_free_delivery_cnt++;
                                            }                                            
                                            
                                                $cur_indx++;
                                                $vars_exists = false;
                                            if ($products_arr['prod_vars'] or $products_arr['prod_msgs'])  // Check whether variable of messages exists
                                            {
                                                $vars_exists 	= true;
                                            }
                                            if($products_arr['product_deposit'])
                                            {
                                                if ($products_arr['final_price']>$products_arr['product_deposit_less'])
                                                $str_reduceval	+= $products_arr['product_deposit_less'];
                                            }
											?>

												<tr>
												<td style="text-align:center;background-color:#FFF;">
														<?php
														// Check whether thumb nail is to be shown here
														if ($Settings_arr['thumbnail_in_viewcart']==1)
														{
														?>
															<a href="<?php url_product($products_arr['product_id'],$products_arr['product_name'],-1)?>" title="<?php echo stripslash_normal($products_arr['product_name'])?>">
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
												<td><a href="<?php echo url_product($products_arr['product_id'],$products_arr['product_name'])?>" title="1<?php echo $products_arr['product_name']?>" ><strong><?php echo $products_arr['product_name']?></strong></a>
																											

												<?php			
												
													if($check_arr['enabled']==false)
													{	
														$var_arr= array();										   
														// If variables exists for current product, show it in the following section
														if ($vars_exists) 
														{
															// show the variables for the product if any
                                                if ($products_arr['prod_vars']) 
                                                {
																//print_r($products_arr['prod_vars']);
																$var_arr= array();
																foreach($products_arr["prod_vars"] as $productVars)
																{
																	$mpn = '';
																	$var_arr[$productVars['var_id']] =  $productVars['var_value_id'];
																	if (trim($productVars['var_value'])!='')
																	{
																		$tc_vurval = tc_remove_spaces ($productVars['var_value']);
																		$tc_return_arr = tc_checkbox_check($tc_vurval,$tc_default_cat_id);
																		//start 
																		if(count($tc_return_arr))
																		{
																		$sql_ckeck_term = "SELECT terms_req,terms_req_new FROM cart WHERE cart_id=".$products_arr['cart_id']." AND sites_site_id=".$ecom_siteid." LIMIT 1";
																		$ret_check_term = $db->query($sql_ckeck_term);
																		$selected = $selected2 ="";
																		if($db->num_rows($ret_check_term)>0)
																		{
																		$row_chk_term = $db->fetch_array($ret_check_term);
																		if($row_chk_term['terms_req']==1)
																		{
																		   $selected = "checked";
																		}
																		if($row_chk_term['terms_req_new']==1)
																		{
																		   $selected2 = "checked";
																		}
																		}

																		if($tc_return_arr['typ']=='3m')
																		{
																			$caption = "I have read and agree to the <a href='javascript:show_pdf_new(\"".$tc_return_arr['key']."\")'>Key Facts</a> and <a href='javascript:show_pdf_new(\"".$tc_return_arr['link']."\")'>TOB</a>";
																		}
																		else
																		{
																			$caption = "I have read and agree to the <a href='javascript:show_pdf_new(\"".$tc_return_arr['key']."\")'>Key Facts</a> and <a href='javascript:show_pdf_new(\"".$tc_return_arr['link']."\")'>IDD</a>";
																		}


																		print "<span class='cartvariable'>".$productVars['var_name'].": ". $productVars['var_value']."</span><br />
																		<span class='cartvariable_checkbox'><input type='checkbox' name='tccheckbox_".$products_arr['cart_id']."' value='".$products_arr['cart_id']."' ".$selected.">".$caption."</span><br />
																		<span class='cartvariable_checkbox'><input type='checkbox' name='tcmorecheckbox_".$products_arr['cart_id']."' value='".$products_arr['cart_id']."' ".$selected2.">I wish to insure my mobility equipment as detailed in the keyfacts and agree to accept the insurer's normal form of policy for the type of insurance I have chosen. I confirm that I will look after my mobility equipment with utmost care at all times. I agree that any information provided to the insurer regarding me for the purpose of accepting my insurance and handling any claims may, if necessary, be divulged to third parties, providing it will be processed by the insurer in compliance with the provisions of the Data Protection Act 1998.</span><br />";


																		}
																		else
																		{
																						
																		print "<span class='cartvariable'>".$productVars['var_name'].": ". $productVars['var_value']."</span><br />"; 
																		/*
																		if($productVars['var_value']=='Free 12 months RTB Warranty')
																		{
																		print "	<span class='cartvariable_checkbox'><input type='checkbox' name='free12checkbox_".$products_arr['cart_id']."' value='".$products_arr['cart_id']."' ".$selected2.">Please tick this box to confirm that you have read the standard 12 months \"return to base\" warranty terms & conditions. <a class='trigger_popup_fricc'>View t&c's </a></span><br />";
																		}
																		*/ 
																		}//end
																						
																		/*										
																		$sql_getmpn = "SELECT var_mpn FROM product_variable_data WHERE var_value_id = ".$productVars['var_value_id']." LIMIT 1";
																		$ret_getmpn = $db->query($sql_getmpn);
																		if($db->num_rows($ret_getmpn))
																		{
																			$row_getmpn = $db->fetch_array($ret_getmpn);
																			if(trim($row_getmpn['var_mpn'])!='')
																			$mpn = ' <br>'.$Captions_arr['CART']['CART_MPN'].stripslashes($row_getmpn['var_mpn']);
																		}
																		print "<p>".stripslashes($productVars['var_name']).": ". stripslashes($productVars['var_value']).$mpn."</p>"; 
																		*/ 

																	}	
																	else
																		print "<p>".stripslashes($productVars['var_name'])."</p>"; 
																}	 
															}
															// Show the product messages if any
															if ($products_arr['prod_msgs']) 
															{	
																foreach($products_arr["prod_msgs"] as $productMsgs)
																{
																	print "<p>".stripslashes($productMsgs['message_title']).": ". stripslashes($productMsgs['message_value'])."</p>"; 
																}	
															}	
															
														}
													}
													
												?>
												 <div class="product-table table-responsive">
												<?php show_BulkDiscounts($products_arr,$var_arr); ?>
												</div>
												</td>
												<td>
													
                
 <?php
											 $hide_qty = false;
											 if($products_arr['cart_promotional_code_id']>0 AND $cartData['totals']['promotional_type']=='freeproduct' AND $cartData['totals']['promotional_code_id'] == $products_arr['cart_promotional_code_id'])
											 {
												//print_r($cartData);
												$select_prom_prod = "SELECT products_product_id FROM promotional_code_product WHERE products_product_id = ".$products_arr['product_id']." AND  promotional_code_code_id = ".$products_arr['cart_promotional_code_id']." LIMIT 1";
												$ret_prom_prod    = $db->query($select_prom_prod);
												if($db->num_rows($ret_prom_prod)>0)
												{
													$hide_qty = true;
												}
											 }
                                                if($products_arr['product_det_qty_type']=='DROP')
                                                {
													?>
													<div class="qty_drop">
													<?php
                                                    if (trim($products_arr['product_det_qty_drop_prefix'])!='')
                                                        echo stripslash_normal($products_arr['product_det_qty_drop_prefix']).' ';
                                                        echo $products_arr['cart_qty'];
                                                    if (trim($products_arr['product_det_qty_drop_suffix'])!='')
                                                        echo ' '.stripslash_normal($products_arr['product_det_qty_drop_suffix']);
                                                    ?>
                                                    <input type="hidden" name="cart_qty_<?php echo $products_arr['cart_id']?>" id="cart_qty_<?php echo $products_arr['cart_id']?>" value="<?php echo $products_arr["cart_qty"]?>" />
                                                    </div>
                                                    <?php	
                                                }
                                                else
                                                {
                                                    ?>
                                                    <input class="form-control cart_qty" name="cart_qty_<?php echo $products_arr['cart_id']?>" type="text" id="cart_qty_<?php echo $products_arr['cart_id']?>" size="5" maxlength="4" value="<?php echo $products_arr["cart_qty"]?>"   />
                                                    <?php
                                                    if($hide_qty == false)
                                                    {
                                                    ?>
                                                    <button data-original-title="Update" onClick="document.frm_cart.remcart_id.value='<?php echo $products_arr['cart_id']?>';handle_form_submit(document.frm_cart,'Update_qty','#upd')" rel="tooltip" title="" class="editcart btn btn-primary"><i class="fa fa-edit"></i></button>
                                                    <?php
													}
                                                }
                                                 if($hide_qty == false)
                                                 {
                                                ?>												
												<a data-original-title="Delete" href="#" onclick="if (confirm_message('<?php echo $Captions_arr['CART']['CART_ITEM_REM_MSG']?>')) {document.frm_cart.remcart_id.value='<?php echo $products_arr['cart_id']?>';handle_form_submit(document.frm_cart,'Remove_qty','#rem')}" class="deletecart btn btn-danger" rel="tooltip" title=""><i class="fa fa-trash"></i></a>
												<?php
												}
												?>
												</td>
												<td class="disc-td">
													<?php 													 
														
                                            //echo print_price($products_arr['product_webprice'],true)
												if($products_arr['discounted_price']>0)
												{
													echo print_price($products_arr['discounted_price'],true);
												}
												else
												{
													echo print_price($products_arr['product_webprice'],true);
												}	
                                            
                                            ?> </br> 
											<?php 
											$disp_arr = display_cart_discount($products_arr,$Captions_arr,true);
											if ($disp_arr['valonly'])
											{
											?>	<span>
												<?php echo $disp_arr['val'].$disp_arr['caption']?>
												</span>
											<?php
											}
											?>									
														
													</td>
												<td class="finalpr-td"><?php echo print_price($products_arr['final_price'],true)?></td>
												</tr>

											<?php
											}
									   if(count($cartData['products']))
										{
										?>
										<tr>
                                                <td colspan="4" class="text-right total_cap"><?php echo stripslash_normal($Captions_arr['CART']['CART_TOTPRICE'])?></td>
                                                <td class="total_captot"><div class="right"><strong><?php echo print_price($cartData["totals"]["subtotal"],true)?></strong></div></td>
                                                </tr>									
										<?php
										}
										   
											if (count($cartData['products'])>0) // Done to show the message if no products in cart
											{
												// Calling the function to decide the delivery details display section 
												$deliverydet_Arr = get_Delivery_Display_Details();
												if ($deliverydet_Arr['delivery_type'] != 'None') // Check whether any delivery method is selected for the site
												{
											  ?>
													
													<input type="hidden" name="cart_delivery_id" id="cart_delivery_id" value="<?php echo $deliverydet_Arr['delivery_id']?>"  />	
													 
													 <?php
														// Case if location is to be displayed
														if (count($deliverydet_Arr['locations']))
														{
													?>	
														  <tr>
														 <td colspan="5"  class="text-right1" ><div align="right">
															<p> <?php echo $Captions_arr['CART']['CART_DELIVERY_LOC']?></p>
																<?php
																	
																		echo generateselectbox('cart_deliverylocation',$deliverydet_Arr['locations'],$row_cartdet['location_id'],'','handle_form_submit(document.frm_cart,"save_commondetails","#a_deliv")','','form-control');
																?>	</div></td>
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
														 <td colspan="5"  class="text-right" ><div class="delopt_cls"><?php echo $Captions_arr['CART']['CART_DELIVERY_OPT']?></div>  
															
															  <?php
																//echo generateselectbox('cart_deliveryoption',$deliverydet_Arr['del_groups'],$row_cartdet['delopt_det_id'],'','handle_form_submit_popup(document.frm_cart,"save_commondetails","#a_deliv","normal")');
																echo generateselectbox('cart_deliveryoption',$deliverydet_Arr['del_groups'],$row_cartdet['delopt_det_id'],'','handle_form_submit(document.frm_cart,"save_commondetails","#a_deliv")');
															  ?>						 
															  <input type="hidden" id="previous" name="previous" value="<?php echo $row_cartdet['delopt_det_id']?>">
															  </td>
														   </tr>
														<?php
														}
														?>   
													 
													<?php
													 // Check whether split delivery is supported by current site for current delivery method
													if ($deliverydet_Arr['allow_split_delivery']=='Y' and $cartData["pre_order"] != "none" and count($cartData['products'])>1)
													{
													?>
														<tr>
															<td colspan="5"  class="text-right" ><?php echo $Captions_arr['CART']['CART_WANT_DELIVERY_SPLIT']?>
															<input type="checkbox" name="cart_splitdelivery" id="cart_splitdelivery" <?php echo ($row_cartdet['split_delivery']==1)?'checked="checked"':''?> onclick="handle_form_submit(document.frm_cart,'save_commondetails','#a_deliv')" /></td>
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
															<td colspan="4"  class="text-right" >
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
																<td >
																<? 
																if($this->checkprice_display($pass_fnpaytypeid)) {
																print print_price($cartData["delivery"]["group".$i]["cost"],true);}?></td>
															</tr>
														<?
														}				
													} 
												}
											 $disable_arr = array();
												$disable_arr[0] = "--Please Select--"; 
												$sql_disable = "SELECT * FROM disability_type ORDER BY sort_order";
												$ret_disable = $db->query($sql_disable);
											 ?>
											 <tr>
													<td colspan="5"  class="text-right" >
														<input type="hidden" name="dis_msg_disp" id="dis_msg_disp" value="<?php echo $Captions_arr['CART']['CART_DISABLE_SELMSG']?>" />
														  <div class="disableshipwrap">
												<table border="0" cellpadding="1" cellspacing="1" width="100%" class="disability">	
													<?php							
																				//start disabilty
													// Case if location is to be displayed
													if ($db->num_rows($ret_disable)>0)
													{
												?>	
														 <tr>
														 <td align="right" colspan="2"><div align="right"><p><?php echo $Captions_arr['CART']['CART_DISABILITY']?></p> 
															<select name = "disability_type" id = "disability_type" onchange='handle_form_submit(document.frm_cart,"save_commondetails","#a_deliv")'>
															<option value=" ">--Please Select--</option>

															<?php
															while($row_disable = $db->fetch_array($ret_disable))
															{
																$id = $row_disable[id];
																$disable_arr[$id] =  $row_disable['type'];
																?>
																<option value="<?php echo $id ?>" <?php if($row_cartdet['disable_id']==$id) { echo "selected";} ?>><?php echo $row_disable['type']; ?></option>
																<?php
															}
															?>
															 <?php /*<option value="00" <?php if($_REQUEST['disability_type']=="00" && $row_cartdet['disable_id']=='00') echo "selected"; ?>><?php echo $Captions_arr['CART']['CART_DISABLE_NONE']; ?></option> */?>

															</select></div>
															<?php
																//$disable_arr[-1] = $Captions_arr['CART']['CART_DISABLE_NONE']; 
																	//echo generateselectbox('disability_type',$disable_arr,$row_cartdet['disable_id'],'','handle_form_submit(document.frm_cart,"save_commondetails","#a_deliv")');
															?>	</td>
													   </tr>
												   <?php
												   }
												 ?></table>
												<div>
												<div class="disability_div">
													 
													 Please specify your condition to ensure we exempt you from VAT for your purchase. By selecting a condition you confirm that 
											<ul class="disability_ul">
											<li>You are a  registered charity OR</li>
											<li>You  are chronically sick or disabled OR </li>
											<li>The products are being purchased on behalf of an individual who is chronically sick or disabled. </li>
											<li>The product(s) being supplied by Discount Mobility is for personal or domestic use. </li>
											<li>You claim that the supply of the product is eligible for relief from VAT under the VAT Act 1994</li>
											</ul>
											If you need further clarification please feel free to contact the team at any time on the number 01245 905 144 and our advisors will gladly be of assistance to you.
												</div>
											  </div>
											</div>



											<div class="cartbottomdisable"></div>
											</div></td>
											</tr>
											<?php
												//end disability
												
												if (($row_cartdet['promotionalcode_id']!=0 or $row_cartdet['voucher_id']!=0) or $Settings_arr['show_cart_promotional_voucher']==1)
												{
					                              // If gift voucher or promotional code is valid
													if (($row_cartdet['promotionalcode_id']!=0 or $row_cartdet['voucher_id']!=0))
													{
														?>
														<tr>
															<td colspan="5"  class="text-right" >
															<?php

															if ($row_cartdet['promotionalcode_id']!=0 and $cartData["bonus"]['type']=='promotional' and $cartData['totals']['pro_type']!='product' and $cartData['totals']['pro_type']!='freeproduct')
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
															?>
															
															<?php 
															if ($row_cartdet['promotionalcode_id']!=0 and $cartData["bonus"]['type']=='promotional' and $cartData['totals']['pro_type']!='product' and $cartData['totals']['pro_type']!='freeproduct')
															echo ($cartData['totals']['lessval']>0)?print_price($cartData['totals']['lessval'],true):0;
															elseif($row_cartdet['voucher_id']!=0 and $cartData["bonus"]['type']=='voucher')
															echo ($cartData['totals']['lessval']>0)?print_price($cartData['totals']['lessval'],true):0;
															?>

															<?php
															if ($row_cartdet['promotionalcode_id']!=0 and $cartData["bonus"]['type']=='promotional' and $cartData['totals']['promotional_type']!='product' and $cartData['totals']['promotional_type']!='freeproduct')
															{
															} 
															elseif ($row_cartdet['promotionalcode_id']!=0 and $cartData['totals']['promotional_type']!='product' and $cartData['totals']['promotional_type']!='freeproduct')
															{
															?>	 

															<?php echo stripslash_normal($Captions_arr['CART']['CART_PROMO_NO_LINK_PROD']);?>
															<?php
															}
															?>
															</td>
													</tr>
													<?php
													}		
													if ($Settings_arr['show_cart_promotional_voucher']==1)
													{
														if ($cartData["bonus"]['type']=='' or $cartData["bonus"]['type']=='promotional' or $cartData["bonus"]['type']=='voucher')
														{
															?>
															<tr>
															<td colspan="5"  class="text-right" >
											  
															<?php
															
															if ($cartData["bonus"]['type']=='promotional' or $cartData['totals']['promotional_type']=='product' or $cartData['totals']['promotional_type']=='freeproduct')
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
															?>
												 
												  <div class="input-group1 promo_butrightA1">

															<?php
															if ($cartData["bonus"]['type']=='voucher' or $cartData["bonus"]['type']=='promotional' or $cartData['totals']['promotional_type']=='product' or $cartData['totals']['promotional_type']=='freeproduct') // handle the case of logged in or not.
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
															<input name="cart_promotionalcode" type="text" id="cart_promotionalcode" size="15" class="promo_input form-control1 input-search1" />
															<?php /*<input name="submit_promotionalcode" type="button" class="buttongray" id="submit_promotionalcode" value="<?php echo $Captions_arr['CART']['CART_PROMO_GO']?>" onclick="document.frm_cart.cart_savepromotional.value=1;handle_form_submit(document.frm_cart,'save_commondetails','')" /> */?>
															<span class="input-group-btn1"><button class="btn-secondary promogo_new" onclick="document.frm_cart.cart_savepromotional.value=1;handle_form_submit(document.frm_cart,'save_commondetails','')" data-original-title="" title=""><?php echo $Captions_arr['CART']['CART_PROMO_GO']?></button>
															 </span>
															<?php
															}
														   ?>
														   </div>											 	
															
														   </td>
														   </tr>														 
														   <?php
														}
													
													}
												
												}	
			$show_bonus_main_div 	= true;
			$proceed_bonus 			= show_cart_bonus_point_section($cartData,$cust_id);
			if (is_Feature_exists('mod_bonuspoints') || $proceed_bonus)
			{
				if($Settings_arr['cust_allowspendbonuspoints']==1)
				{
					?>        
				<tr>
					<td colspan="5"  class="text-right" >
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
					/*					
					?>
					<div class="promoTitle">Bonus Points</div>
					<?php
					*/ 
					if ($proceed_bonus)
					{					
					if ($cartData["totals"]["bonus"] and $cust_id)
					{
					?>
					<p class="bonus_earn"><?php echo $Captions_arr['CART']['CART_TOTAL_POINTS_EARN']?>: <strong><?php echo $cartData["totals"]["bonus"]?></strong></p>
					<?php
					}
					if($cust_id) // case if logged in
					{
					if ($cartData["customer"]["customer_bonus"] and $Settings_arr['cust_allowspendbonuspoints']==1) // does current customer have bonus points
					{
					if ($Settings_arr['minimum_bonuspoints'] < (int)($cartData["customer"]['customer_bonus']))
					{
					?>
					<?php echo $Captions_arr['CART']['CART_SPENT_BONUS_POINTS']?> &nbsp;&nbsp;<strong>(<? print (int)(($cartData["bonus"]["left"])); ?> &nbsp;<?php echo $Captions_arr['CART']['CART_REMAINING']?>)</strong>
					<div class="input-group1 promo_butrightA1">
					<input type="hidden" name="maxBonusPoints"  class="input" value="<? print $cartData["customer"]["customer_bonus"]; ?>">
					<input type="text" name="spendBonusPoints" size="4" value="<? print $cartData["bonus"]["spending"]; ?>" class="promo_input form-control1">
					<input type="hidden" name="leftBonusPoints" value="<? print $cartData["bonus"]["left"] ?>">
					<input  class="btn1 btn-default promogo_new" type="button" value="<?php echo $Captions_arr['CART']['CART_SPEND']?>" onclick="handle_form_submit(document.frm_cart,'save_commondetails','#bonus')">
					
					</div>
					
					<?php
					}
					else
					{
					?>
					<?php echo str_replace('[min_points]',$Settings_arr['minimum_bonuspoints'],$Captions_arr['CART']['CART_MIN_BONUS_REQ'])?>
					<?php
					}
					}
					}
					else
					{
					if ($cartData["totals"]["bonus"] and $Settings_arr['cust_allowspendbonuspoints']==1)
					{
					?>
					<?php echo $Captions_arr['CART']['CART_TOTAL_POINTS_EARN'];?>:<b><?php echo $cartData["totals"]["bonus"];?></b>
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
					<?php echo $Captions_arr['CART']['CART_IF_HAVE_ACCT'];?><input type="button" name="cust_login_bonus" id="cust_login_bonus" value="<?php echo $Captions_arr['CART']['CART_BONUS_LOGIN_MSG']?>"  class="btn btn-primary" onclick="window.location ='<?php url_link('custlogin.html?redirect_back=1&pagetype=cart')?>'"/>
					<?php
					}
					}	
					?>
					</td>
					</tr>
					<?php
				  } 
				}
				
				 $total_delivery_charge = $cartData["totals"]["extraShipping"] +  $cartData["totals"]["delivery"];
                                                // Check whether delivery is charged, then show the total after applying delivery charge
                                                //if($total_delivery_charge && $row_cartdet['location_id'] >0)
                                                if($total_delivery_charge)
                                                {
                                                ?>
                                                <tr>
                                                    <td colspan="4"  class="text-right" ><?php echo stripslash_normal($Captions_arr['CART']['CART_TOTAL_DELIVERY_CHARGES'])?>&nbsp;&nbsp;</td>
                                                    <td ><?php echo print_price($total_delivery_charge,true)?></td>
                                                </tr>
                                                <?php
                                                }
                                                // Section to show the tax details
                                                if($cartData["totals"]["tax"])
                                                {
                                                ?>
                                                 <tr>
                                                    <td colspan="4"  class="text-right">
                                                    <?php echo stripslash_normal($Captions_arr['CART']['CART_TAX_CHARGE_APPLIED'])?>
                                                    <?	
                                                    foreach($cartData["tax"] as $tax)
                                                    {
                                                        echo '<br />('.$tax['tax_name']; ?> @ <? print $tax['tax_val']; ?>%)
                                                        <?	
                                                    }
                                                    ?>						</td>
                                                    <td >
                                                    <?	
                                                    foreach($cartData["tax"] as $tax)
                                                    {
                                                        echo print_price($tax["charge"],true); ?> 
                                                    <?	
                                                    }
                                                    ?>
                                                    </td>
                                                </tr>
                                                <?php
                                                }
                                                
                                                // Section to show the extra shipping cost
                                                /*if($cartData["totals"]["extraShipping"])
                                                {
                                                ?>
                                                    <tr>
                                                        <td align="right" valign="middle"  class="cart_td_normal"><?php echo stripslash_normal($Captions_arr['CART']['CART_EXTRA_SHIPPING'])?></td>
                                                        <td align="right" valign="middle"  class="cart_td_normal"><?php echo print_price($cartData["totals"]["extraShipping"],true)?></td>
                                                    </tr>
                                                <?php	
                                                }*/
                                                if ($cartData["bonus"]["value"])
                                                {
                                                ?>
                                                    <tr>
                                                        <td colspan="4"  class="text-right" ><?php echo stripslash_normal($Captions_arr['CART']['CART_SPENT_BONUS_POINTS_VALUE'])?>&nbsp; </td>
                                                        <td >(-) <? echo print_price($cartData["bonus"]["value"],true);?></td>
                                                    </tr>
                                                <?php			
                                                }
                                                
                                                // If gift voucher or promotional code is valid
                                                if (($row_cartdet['promotionalcode_id']!=0 or $row_cartdet['voucher_id']!=0))
                                                {
                                                ?>
                                                <tr>
                                                    <td colspan="4"  class="text-right">
                                                    <?php 
                                                    if ($row_cartdet['promotionalcode_id']!=0 and $cartData["bonus"]['type']=='promotional' and $cartData['totals']['pro_type']!='product' or $cartData['totals']['pro_type']=='freeproduct')
                                                    {
                                                        ?>
                                                        <?php echo stripslash_normal($Captions_arr['CART']['CART_PROMOTIONAL_CODE_DISC_APPLIED'])?>
                                                        
                                                        <?php
                                                    }
                                                    elseif($row_cartdet['voucher_id']!=0 and $cartData["bonus"]['type']=='voucher')
                                                    {
                                                        ?>
                                                        <?php echo stripslash_normal($Captions_arr['CART']['CART_GIFTVOUCHER_DISC_APPLIED'])?>
                                                        <?php
                                                    }
                                                    ?>
                                                    </td>
                                                    <td >
                                                        <?php 
                                                        if ($row_cartdet['promotionalcode_id']!=0 and $cartData["bonus"]['type']=='promotional' and $cartData['totals']['pro_type']!='product')
                                                            echo '(-) '.print_price($cartData['totals']['lessval'],true);
                                                        elseif($row_cartdet['voucher_id']!=0 and $cartData["bonus"]['type']=='voucher')
                                                            echo '(-) '.print_price($cartData['totals']['lessval'],true);
                                                        ?>
                                                    </td>
                                                </tr>
                                                <?php
                                                }	
                                                // show the total final price
                                                if($cartData["totals"]["bonus_price"])// remove the extra shipping cost if no delivery location selected
                                                {
                                                    if($row_cartdet['location_id'] == 0 && $cartData["totals"]["extraShipping"] >0)
                                                    {
                                                        $cartData["totals"]["bonus_price"]  = $cartData["totals"]["bonus_price"] - $cartData["totals"]["extraShipping"];
                                                    }
                                                ?>
                                                <tr>
                                                <td colspan="4"  class="text-right total_cap"><?php echo stripslash_normal($Captions_arr['CART']['CART_TOTAL_FINAL_COST'])?>&nbsp; </td>
                                                <td class="total_captot" ><div class="right"><?php echo print_price($cartData["totals"]["bonus_price"],true)?></div></td>
                                                </tr>
                                                <?php
                                                }
                                                $rem_val = $cartData["totals"]["bonus_price"] - $str_reduceval;
                                                if($rem_val >0 and $str_reduceval>0)
                                                {
                                                    ?>
                                                    <tr>
                                                        <td colspan="4"  class="text-right" ><?php echo stripslash_normal($Captions_arr['CART']['CART_LESS_AMT_LATER'])?>&nbsp; </td>
                                                        <td ><?php echo print_price($str_reduceval,true)?></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="4"  class="text-right"><?php echo stripslash_normal($Captions_arr['CART']['CART_TOTAL_AMT_NOW'])?>&nbsp; </td>
                                                        <td  ><?php echo print_price($rem_val,true)?>
                                                        <input type="hidden" name="store_reduceval" value="<?php echo $str_reduceval?>" />
                                                        </td>
                                                    </tr>
                                                <?php
                                                }	
                                                ?>
                                                
                                                <?php
                                                if($cartData["totals"]['full_free_delivery']==1 or $all_prods_free_delivery_cnt ==count($cartData['products']))
                                                {
                                                    ?>
                                                    <tr>
                                                        <td colspan="5"  class="text-right">
                                                    <?php echo $Captions_arr['CART']['CART_BOTTOM_FREE_DELIVER_MSG']?></td>
                                                    </tr>
                                                    <?php
                                                }                                               
          ?>										

												 
												
												<?php
											}
										}
										 
									?>
                   
                
                
               </tbody>
               
               
               
              </table>
              <?php
                   if (count($cartData['products'])!=0) // Done to show the message if no products in cart
					{
						?>
						<table class="payment_table" width="100%" border="0" cellpadding="0" cellspacing="0">
						   <?php
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
                    $more_pay_condition 	.= " AND paymethod_key<>'PAYPAL_EXPRESS' "; // today start -- today end .=
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
                    if($show_multiple==true and $Captions_arr['CART']['CART_PAYMENT_MULTIPLE_MSG']!='')
													 {/*
														?>
														<tr>                       
														<td    class="text-right">
														<?php
														echo stripslash_normal($Captions_arr['CART']['CART_PAYMENT_MULTIPLE_MSG']);
														?>
														</td>
														</tr>
														<?php */
													 }  // Running the qry to pick the payment types to be displayed
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
                    $hide_direct_checkout_button = false ; // Today start -- today end
                    if ($db->num_rows($ret_paytypes))
                    {
						if($db->num_rows($ret_paytypes)==1)// Check whether there are more than 1 payment type. If no then dont show the payment option to user, just use hidden field
						{
						$row_paytypes = $db->fetch_array($ret_paytypes);
						if($row_paytypes['paytype_code']=='credit_card')
						{
						//if($row_paytypes['paytype_id']==$row_cartdet['paytype_id'])
						$cc_exists = true;
						// today start	
						//if($totpaycnt==0)
						//$hide_direct_checkout_button = true;
						
						// today end
						}
						?>
						<input type="hidden" name="cart_paytype" id="cart_paytype" value="<?php echo $row_paytypes['paytype_id']?>" />
						<?php
						}
						 else
						{
							$Settings_arr['paytype_listingtype']='dropdown';
							// Check how the payment types to be displayed to the user
							if ($Settings_arr['paytype_listingtype']=='dropdown') // Case of payment listing type is to shown as drop downbox
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
								
                    $paymethod[0] = stripslash_normal($Captions_arr['CART']['CART_SELECT']);
                    while ($row_paytypes = $db->fetch_array($ret_paytypes))
                    {
                    if($row_paytypes['paytype_code']=='pay_on_account')
                    {
                    $add_text = ' (Credit Available '. print_price($payonaccount_remlimit,true).')';
                    }	
                    else
                    $add_text = '';
                    // today start
                    $exclude_current = false; 
                    if($row_paytypes['paytype_code']=='credit_card')
                    {
                    if($hide_direct_checkout_button==true)
                    $exclude_current = true;
                    }	
                    if($exclude_current==false) // today end
                    $paymethod[$row_paytypes['paytype_id']] = stripslash_normal($row_paytypes['paytype_caption']).$add_text;	
                    if($row_paytypes['paytype_code']=='credit_card')
                    {
                    if($row_paytypes['paytype_id']==$row_cartdet['paytype_id'])
                    $cc_exists = true;
                    
                    }					
                    }
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
                    ?>	
                     <tr>
													<td    class="text-right">
													<table width="100%" border="0" cellspacing="0" cellpadding="0">
													<tr>
													<td    class="text-right"><a name="a_pay">&nbsp;</a><label style="padding-top:5px;padding-right:5px;"><?php echo stripslash_normal($Captions_arr['CART']['CART_SEL_PAYTYPE'])?></label>
                    <?php
                    if($totpaycnt>1)// decide whether to add the onchange event
                        echo generateselectbox('cart_paytype',$paymethod,$row_cartdet['paytype_id'],'','handle_form_submit(document.frm_cart,"save_commondetails","#a_pay")','','form-control');
                    else 
                    {
						if($finance_available==true)
						{
                        echo generateselectbox('cart_paytype',$paymethod,$row_cartdet['paytype_id'],'','handle_form_submit(document.frm_cart,"save_commondetails","#a_pay")','','form-control');
						}
						else
                        echo generateselectbox('cart_paytype',$paymethod,$row_cartdet['paytype_id'],'','','','form-control');
                    
				     }
                  
                    ?>
                    </td>
                    </tr>
                     <?php
				    }
									  if($ecom_siteid==113 || $ecom_siteid==104 || $ecom_siteid==74)
									{
										$sql_verific = "SELECT invoice_verification_code FROM general_settings_sites_common_onoff WHERE sites_site_id = 113 ";
										$ret_verific =  $db->query($sql_verific);
										$row_verific = $db->fetch_array($ret_verific);
										$verific_code = $row_verific['invoice_verification_code'];
										if($row_cartdet['paytype_id']==3)
										{
										 ?>
										 <tr>
											 <td colspan="2"  ></td>
											 <td colspan="4"  ><div class="verify_box" >
										 Enter the verification code for Invoice :&nbsp;
										 <input type="text" name ="invoice_verification_code" id="invoice_verification_code" value=""  size="6" >
										 </div></td></tr>
										 <?
										}
									//$row_cartdet['paytype_id']
									}
									?>
                    <tr>
						<td>
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
							<td align="left" class="fin_text1">
								<div align="right" class="fin_text">
							Enter the Deposit/Admin Fee Amount 	&pound;<input type='text' name='fin_deposit' id="fin_deposit" value='<?php echo $findeposit?>' onblur="reload_finance(this)">(min 10%)
							   </div>
							</td>
						</tr>
						</table>	
						<div id="finance_holder">
						<?php $this->finance_details($cartData,$findeposit);?>
						</div>	
						</td>
					</tr>	
					<tr>
						<td colspan="6" align="left" valign="middle">
						<table style="width:100%;border:0;">
						<tr>
						<td class="payTitle" colspan="2">
						Will I Qualify for Credit?&nbsp;&nbsp;We can only consider your finance application if:
						</td>
						</tr>
						<tr>
						<td style="font-weight:bold;" colspan="2">
							(Please tick the qualifying questions to continue to checkout)
							
						</td>
						</tr>
						<tr>
						<td  style="width:20px">
							<input type="checkbox" name="chk_18over" id="chk_18over" value="1">
						</td>
						<td class="">
							You are 18 or over, and employed at least 16 hours per week or claiming a Disability Living Allowance or Pension,
							You have a good credit history with no late payments, debt relief orders, CCJs, IVAs or bankruptcies,
							You are a permanent UK resident and able to supply a UK address history for the last 3 years.
						</td>
						</tr>						
						<tr>
						<td colspan="2" style="padding-top:6px;">
							<span class="astericcls">*</span>&nbsp;Applications from students (unless employed at least 16 hours per week or claiming a Disability Living Allowance), unemployed and unemployed single house persons will not be considered &nbsp;<span class="astericcls">*</span>&nbsp;<br/><br/>
							<span style="font-size:16px;font-weight:bold;">&nbsp;<span class="astericcls">*</span>&nbsp;If you do not meet the above requirements, consider asking a family member or relative to apply for credit on your behalf. All deliveries will be to the name and address of the person who applied for the finance. &nbsp;<span class="astericcls">*</span>&nbsp;</span>
						</td>
						</tr>
						<tr>
						<td colspan="2" style="padding-top:6px;">
							<ul class="Findesc">
							<li>ScootaMart LTD trading as discount-mobility.co.uk acts as a credit intermediary and offer credit products provided exclusively by Klarna Bank AB (publ).</li>
							<li>Klarna Bank AB (publ) is Authorised by the Swedish Financial Services Authority (Finansinspektionen) and is subject to limited regulation by the Financial Conduct Authority.</li>
							<li>Details about the extent of our regulation by the Financial Conduct Authority are available from us on request.</li>
							<li> Finance is only available to permanent UK residents aged >18, subject to status, terms and conditions apply. </li>
							<li>Registered office: Sveavägen 46, 111 34 Stockholm, Sweden. Corporation ID number: 556737-0431&nbsp;</li>
							</ul>			
							
						</td>
						</tr>
						</table>
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
                    </table>
                    </td>
                    </tr>
                    <?php
							}
							 elseif ($Settings_arr['paytype_listingtype']=='icons') // Case if payment types is to be displayed as list items
							{
								
                    //$pay_maxcnt = 7;
                    //$pay_cnt	= 0;
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
                    <tr>
                    <td colspan="10" align="left" valign="top" class="" >
                    
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="payment_table">
                    <tr>
                    <td align="left" valign="top" class="payment_table_header">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
					<td colspan="3" align="left" class="payicon_heading">
					<a name="a_pay">&nbsp;</a><?php echo stripslash_normal($Captions_arr['CART']['CART_SEL_PAYTYPE'])?>
					</td>
					</tr>
                    <?php
					$maxx=2;
					$cur =0;
                    while ($row_paytypes = $db->fetch_array($ret_paytypes))
                    {
                    $pay_cnt++;
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
                    if($cur==0)
						echo '<tr>';
					?>
					<td style="width:50%">
						 <table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
						<td class="payment_td_left">
					   <?php
					   	$show_ptype = true;
												if($row_paytypes['paytype_code']=='4min_finance')
												{
													if($Settings_arr['4minfinance_lowerlimit']>$cartData["totals"]["bonus_price"])
													{
														$show_ptype = false;
													}
												}
					   /*
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
						*/
						if ($show_ptype)
						{
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
						?></td>
						<td align="left" valign="middle" class="payment_td_middle"> 
						<input class="shoppingcart_radio" type="radio" name="cart_paytype" id="cart_paytype" value="<?php echo $row_paytypes['paytype_id']?>" <?php echo ($row_paytypes['paytype_id']==$row_cartdet['paytype_id'])?'checked="checked"':''?> <?php echo $on_change?>/>
						</td>
						<td align="left" valign="middle" class="payment_td_right"><?php echo stripslash_normal($row_paytypes['paytype_caption']).$add_text?></td>
						</tr>
						</table>
					</td>
                      <?php
					  $cur++;
					  if($cur>=$maxx)
					  {
					  	$cur = 0;
						echo '</tr>';
					  }
					}	
                    }
                    if($cur>0 and $cur<$maxx)
					{
						echo '<td></td></tr>';
					}
									  if($ecom_siteid==113 || $ecom_siteid==104 || $ecom_siteid==74)
									{
										$sql_verific = "SELECT invoice_verification_code FROM general_settings_sites_common_onoff WHERE sites_site_id = 113 ";
										$ret_verific =  $db->query($sql_verific);
										$row_verific = $db->fetch_array($ret_verific);
										$verific_code = $row_verific['invoice_verification_code'];
										if($row_cartdet['paytype_id']==3)
										{
										 ?>
										 <tr>
											 <td colspan="2"  ></td>
											 <td colspan="4"  ><div class="verify_box" >
										 Enter the verification code for Invoice :&nbsp;
										 <input type="text" name ="invoice_verification_code" id="invoice_verification_code" value=""  size="6" >
										 </div></td></tr>
										 <?
										}
									//$row_cartdet['paytype_id']
									}
									  ?>	
                   
                    </table>    </td>
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
								<div class="fin_text">
							Enter the Deposit/Admin Fee Amount 	&pound;<input type='text' name='fin_deposit' id="fin_deposit" value='<?php echo $findeposit?>' onblur="reload_finance(this)">(min 10%)
							</div></td>
						</tr>
						</table>	
						<div id="finance_holder">
						<?php $this->finance_details($cartData,$findeposit);?>
						</div>	
						</td>
					</tr>	
					<tr>
						<td colspan="6" align="left" valign="middle">
						<table style="width:100%;border:0;">
						<tr>
						<td class="payTitle" colspan="2">
						Will I Qualify for Credit?&nbsp;&nbsp;We can only consider your finance application if:
						</td>
						</tr>
						<tr>
						<td style="font-weight:bold;" colspan="2">
							(Please tick the qualifying questions to continue to checkout)
							
						</td>
						</tr>
						<tr>
						<td style="width:1%">
							<input type="checkbox" name="chk_18over" id="chk_18over" value="1">
						</td>
						<td class="">
							You are 18 or over, and employed at least 16 hours per week or claiming a Disability Living Allowance or Pension,<br>
							You have a good credit history with no late payments, debt relief orders, CCJs, IVAs or bankruptcies,<br>
							You are a permanent UK resident and able to supply a UK address history for the last 3 years.
						</td>
						</tr>						
						<tr>
						<td colspan="2" style="padding-top:6px;">
							<span class="astericcls">*</span>&nbsp;Applications from students (unless employed at least 16 hours per week or claiming a Disability Living Allowance), unemployed and unemployed single house persons will not be considered &nbsp;<span class="astericcls">*</span>&nbsp;<br/><br/>
							<span style="font-size:16px;font-weight:bold;">&nbsp;<span class="astericcls">*</span>&nbsp;If you do not meet the above requirements, consider asking a family member or relative to apply for credit on your behalf. All deliveries will be to the name and address of the person who applied for the finance. &nbsp;<span class="astericcls">*</span>&nbsp;</span>
						</td>
						</tr>
						<tr>
						<td colspan="2" style="padding-top:6px;">
							ScootaMart LTD trading as discount-mobility.co.uk acts as a credit intermediary and offer credit products provided exclusively by Klarna Bank AB (publ).<br/>
Klarna Bank AB (publ) is Authorised by the Swedish Financial Services Authority (Finansinspektionen) <br/>
and is subject to limited regulation by the Financial Conduct Authority. Details about the extent of our <br/>
regulation by the Financial Conduct Authority are available from us on request. Finance is only <br/>
available to permanent UK residents aged >18, subject to status, terms and conditions apply. <br/>
Registered office: Sveavägen 46, 111 34 Stockholm, Sweden. Corporation ID number: 556737-0431&nbsp;<br/><br/>
							
						</td>
						</tr>
						</table>
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
							}
						}
					}
					else
					{
					$sql_invoice = "SELECT paytype_id FROM payment_types WHERE paytype_code='invoice' LIMIT 1";
                    $ret_invoice = $db->query($sql_invoice);
                    $row_invoice = $db->fetch_array($ret_invoice);
                    $hide_direct_checkout_button = true; // today start
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
								<table width="100%" border="0" cellspacing="0" cellpadding="0" class="gtable">
                                    <tr>
                                        <td class="td">
                                        <?php
                                        $pay_maxcnt = 2;
                                        $pay_cnt	= 0;
                                        ?>
                                            <table width="100%" border="0" cellspacing="3" cellpadding="0" id="paymethod_id">
                                                <tr>
                                                <td colspan="<?php echo $pay_maxcnt?>" class="td"><?php echo stripslash_normal($Captions_arr['CART']['CART_SEL_PAYGATEWAY'])?></td>
                                                </tr>
                                                <tr>
                                                    <td class="td">
                                                        <?php
                                                        while ($row_paymethods = $db->fetch_array($ret_paymethods))
                                                        {
                                                        $caption = ($row_paymethods['payment_method_sites_caption'])?$row_paymethods['payment_method_sites_caption']:$row_paymethods['paymethod_name'];
                                                        // Check whether image is assigned for current payment method
                                                        $pay_cnt++;
                                                        $paymethodcc[$row_paymethods['paymethod_id']] = stripslash_normal($caption);	
                                                        }
                                                        echo generateselectbox('cart_paymethod',$paymethodcc,$row_cartdet['paymethod_id'],'','','','form-control');
                                                        ?>	
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>	
								</table>
								<?php
								}
							}
                    }
                    ?>
                    
                    <?php
                    
				}
                    ?>
                    <input type="hidden" name="pass_url" id="pass_url" value="<?php echo $ps_url?>"/>  
                    <input type="hidden" name="cc_req_indicator" id="cc_req_indicator" value="<?php echo $cc_exists?>" />
                     <input type="hidden" name="paysel_msg_disp" id="paysel_msg_disp" value="<?php echo $Captions_arr['CART']['CART_SEL_PAYTYPE_MSG']?>" />
			  <input type="hidden" name="gate_msg_disp" id="gate_msg_disp" value="<?php echo $Captions_arr['CART']['CART_SEL_PAYGATEWAY_MSG']?>" />
			  <input type="hidden" name="del_msg_disp" id="del_msg_disp" value="<?php echo $Captions_arr['CART']['CART_DELIVERY_LOC_MSG']?>" />
			  <input type="hidden" name="tc_msg_disp" id="tc_msg_disp" value="<?php echo $Captions_arr['CART']['CART_TC_CHECKBOX_MSG']?>" />
			  <input type="hidden" name="tcmore_msg_disp" id="tcmore_msg_disp" value="<?php echo $Captions_arr['CART']['CART_TCMORE_CHECKBOX_MSG']?>" />
	       	  <input type="hidden" name="meth_msg_disp" id="meth_msg_disp" value="<?php echo $Captions_arr['CART']['CART_DELIVERY_METH_MSG']?>" />
	       	  <input type="hidden" name="free1_msg_disp" id="free1_msg_disp" value="<?php echo $Captions_arr['CART']['CART_FREE1YR_MSG']?>" />
                    <?php
                    /*
                       
                    <input type="hidden" name="paysel_msg_disp" id="paysel_msg_disp" value="<?php echo stripslash_normal($Captions_arr['CART']['CART_SEL_PAYTYPE_MSG'])?>" />
                    <input type="hidden" name="gate_msg_disp" id="gate_msg_disp" value="<?php echo stripslash_normal($Captions_arr['CART']['CART_SEL_PAYGATEWAY_MSG'])?>" />
                    <input type="hidden" name="del_msg_disp" id="del_msg_disp" value="<?php echo stripslash_normal($Captions_arr['CART']['CART_DELIVERY_LOC_MSG'])?>" />
                     */
                     ?>
                                              </table>
						                   </td>
										</tr>
										<?php
									}									
										
                    // Check whether the google checkout button is to be displayed
                    if($google_exists and $google_recommended==0 && $show_checkoutbutton==true)
                    {
                    $row_google = $db->fetch_array($ret_google);
                    ?> <tr>
											<td>
											<table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                        <td colspan="10" align="right" valign="top" class="mainmiddle_carttdA">
                        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="shoppingcarttable">
                        
                        <tr>
                        <td  align="left" valign="top" class="google_td"><?php echo stripslashes($Captions_arr['CART']['CART_GOOGLE_HELP_MSG']);?></td>
                        <td  align="right" valign="middle" class="google_td">
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
                        </td>
                        </tr>
                         </table>
											          <?php
                    }
                   
                    ?>
              
              
            </div>
             <div class="container-fluid sign-in continue-bottA" id="continue-bottA">
				 
				<?php
			 // case if paypal button is to be displayed
                    if($ecom_common_settings['paymethodKey']['PAYPAL_EXPRESS']['paymethod_key'] == "PAYPAL_EXPRESS" and $show_checkoutbutton==true)// and $_SERVER['REMOTE_ADDR']=='118.102.196.27')
                    {
                    ?> 
				
						
								<div class="continue_rightA">	
								
							<?php	 if($ps_url) // show the continue shopping button only if ps_url have value
         {
        ?>
		<a href="#" onClick="show_wait_button(this,'Please Wait...');window.location='<?php echo $ps_url ?>'" class="btn btn-warning btn-shop"><i class="fa fa-angle-left"></i><?php echo stripslash_normal($Captions_arr['CART']['CART_CONT_SHOP']);?></a>
		<?php
		}?>
		</div>					
							
							<div class="continue_leftA">	
								
				<?php if (count($cartData['products'])>0)
		{
			if($hide_direct_checkout_button==false)// today start
			{ 
				if($show_checkoutbutton==true)	
				{ 
					?>
					<button onclick="handle_checkout_submit('<?php echo $ecom_hostname ?>',0,'<?php echo $cartData["totals"]["bonus_price"]?>')" class="btn btn-success btn-block btn-checkout" >checkout<i class="fa fa-angle-right"></i></button></div>
					<?php
				}
			}
		}?>				
			
				
				</div>
			 <table width="100%" cellpadding="0" cellspacing="0" class="table continue-bott"  id="sign">	
			 <?php 
			 if(($totpaycnt>0) or ($google_exists and $google_recommended==0) or $hide_direct_checkout_button==false) // today start -- today end
							{ ?> 
		<tr>
							<td width="50%" height="51" style="text-align:left">
								<?php
								/*
							<script type="text/javascript">//<![CDATA[
							var tlJsHost = ((window.location.protocol == "https:") ? "https://secure.comodo.com/" : "http://www.trustlogo.com/");
							document.write(unescape("%3Cscript src='" + tlJsHost + "trustlogo/javascript/trustlogo.js' type='text/javascript'%3E%3C/script%3E"));
							//]]>
							</script>
							<script language="JavaScript" type="text/javascript">
							TrustLogo("https://www.puregusto.co.uk/images/www.puregusto.co.uk/site_images/comodo_secure_seal_113x59_transp.png", "SC5", "none");
							</script>
							<a href="https://ssl.comodo.com/ev-ssl-certificates.php" id="comodoTL">EV SSL</a>
	
								*/
								?>
							</td>
							<td colspan="2" class="hidden-xs"></td>
							
							<td width="40%">&nbsp;</td>
          <td width="" valign="middle" ><img src="<?php echo url_site_image('or.png')?>" alt="Or" border="0" />
                        </div></td>
		</tr>
		<?php }?>
		<tr>
		  <td height="32">&nbsp;</td>
		  <td colspan="2" class="hidden-xs"></td>
		  <td colspan="2" valign="top"><div align="right">
			   	<?php $button_img_path = 'https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif';     ?>  
		    <input type="image" name="submit" src="<?php echo $button_img_path?>" border="0" align="top" alt="PayPal" onclick="handle_checkout_submit('<?php echo $ecom_hostname?>',2,'<?php echo $cartData["totals"]["bonus_price"]?>')" />
	      </div></td>
		  </tr>	  
    </table>
    <?php }?>
    
    
    <div class="delfn_mainclass">
    
		<?php
                        $deldate_arr = get_expected_deliverydate();
						if($deldate_arr['hr']!='' or $deldate_arr['min']!='')
						{
							$str	= "For an estimated expedited delivery of <span class='delfndate_span'>[del_date]</span>, place your order in <span class='delfntime_span'>[hour] hour(s), [mins] minute(s)</span>";

							$dlsr_arr = array('[hour]','[mins]','[del_date]');
							$dlrp_arr	= array($deldate_arr['hr'],$deldate_arr['min'],date('l jS \of F Y',$deldate_arr['del_date']));
							
							$deldate_status = str_replace($dlsr_arr,$dlrp_arr,$str);
							
							echo "<div align='center' class='alert alert-info'>".$deldate_status."</div>";
						}
						?>	
		
		
		
	</div>
		</form>
                   <?php     
                     if(!$cust_id and count($cartData['products']))
                        {
                        $Captions_arr['CUST_LOGIN'] 	= getCaptions('CUST_LOGIN'); // Getting the captions to be used in this page
                        ?>
                       <form class="form-signin">
                          <h1 class="h3 mb-3 font-weight-normal"><?php echo stripslash_normal($Captions_arr['CART']['CART_LOGIN'])?></h1>
      <label for="inputEmail" class="sr-only"><?php echo stripslash_normal($Captions_arr['CART']['CART_EMAIL'])?></label>
      <input type="email" id="custlogin_uname_main" class="form-control" placeholder="Email address" name="custlogin_uname" required="">
      <label for="inputPassword" class="sr-only"><?php echo stripslash_normal($Captions_arr['CART']['CART_PASSWORD'])?></label>
      <input type="password" name="custlogin_pass" id="custlogin_pass_main" class="form-control" placeholder="Password" required="">
      

		
		<button class="btn btn-lg  btn-block regbt" type="button" onclick="submit_cust_login('frm_cart');" name="button3" id="button3">Sign in</button>
		 <div class="checkbox1">
          <a class="signup_a" href="<?php url_link('registration.html')?>">Signup Now</a>
			<a href="<?php url_link('forgotpassword.html')?>" class="lgn_txt_link"><?php echo stripslash_normal($Captions_arr['CUST_LOGIN']['FORGOT_PASS'])?></a> 
        </div>
		</form>
					
							
                        <?php
                        }
                        ?> 
                    <form name="frm_custlogin_cart" id="frm_custlogin_cart" method="post" action="" onsubmit="return validate_login(this)" class="frm_cls">	
                    <input type="hidden" name="redirect_back" value="1" /> 
                    <input type="hidden" name="custlogin_uname" id="custlogin_uname"  value="" />
                    <input type="hidden" name="custlogin_pass" id="custlogin_pass"  value="" />
                    <input type="hidden" name="pass_url" id="pass_url" value="<?php echo $ps_url?>" />
                    <input type="hidden" name="cart_mod" value="show_cart" /> 
                    <input type="hidden" name="custcartlogin_Submit" value="Login" /> 
                    <input type="hidden" name="fpurpose" value="" />
                    <input type="hidden" name="remcart_id" value="" />
                    <input type="hidden" name="hold_section" value="" />
                    </form>
                      </table>

</div>
          </div>
          

        </div>
        </div>
        </div>
       
   
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
	
     
		
		<?php
		// $this->upsell_products_display(); 	
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
			if(check_IndividualSslActive())
			{
				$ecom_selfhttp = "https://";
			}
			else
			{
				$ecom_selfhttp = "http://";
			}
			$session_id = Get_session_Id_from();
			//need to use dynamic instead of 104 
			$sql_finance = "SELECT * FROM finance_details WHERE sites_site_id = 104 ORDER BY finance_sortorder";
			$ret_finance = $db->query($sql_finance);
			if($db->num_rows($ret_finance))
			{
			/*$finance_arr = array(
							0=>array('code'=>'ONIB24-15.9','rate'=>'15.9','name'=>'2 Year Classic Credit (15.9% APR)'),
							1=>array('code'=>'ONIB36-15.9','rate'=>'15.9','name'=>'3 Year Classic Credit (15.9% APR)'),
							2=>array('code'=>'ONIB48-15.9','rate'=>'15.9','name'=>'4 Year Classic Credit (15.9% APR)')
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
			// Get the id of 48 months finance code ONIB48-15.9
			$sql_getc = "SELECT finance_id FROM finance_details WHERE sites_site_id = $ecom_siteid and finance_code='ONIB48-15.9' LIMIT 1";
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
.finwrap2Year {
    float: left;
    width: 99%;
    height: auto;
    background: #ebf5fe;
    padding: 6px;
    margin-right: 10px;
    padding-top: 15px;
}

.finwrap3Year {
    float: left;
    width: 99%;
    height: auto;
    background: #e2fcf1;
    padding: 6px;
    margin-right: 10px;
    padding-top: 15px;
}

.finwrap4Year {
    float: left;
    width: 99%;
    height: auto;
    background: #f3f4e6;
    padding: 6px;
    margin-right: 10px;
    padding-top: 15px;
    margin-top: 10px;
}

.finwrap5Year {
    float: left;
    width: 99%;
    height: auto;
    background: #f3ffe6;
    padding: 6px;
    margin-right: 10px;
    padding-top: 15px;
    margin-top: 10px;
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
<?php
$sql_gtparam = "SELECT * FROM finance_paymentgateway_details WHERE sites_site_id = $ecom_siteid LIMIT 1";
	$ret_gtparam = $db->query($sql_gtparam);
	if($db->num_rows($ret_gtparam))
	{
		$row_gtparam = $db->fetch_array($ret_gtparam);
		$API_key = trim($row_gtparam['finpay_apikey']);
		$INST_Id = trim($row_gtparam['finpay_installationid']);
	}
?>
<script  type="text/javascript" src="https://test.dekopay.com/js/libraries/jquery/jquery-3.3.1.min.js"></script>
		    <script type="text/javascript" src="https://secure.dekopay.com/js_api/FinanceDetails.js.php?api_key=<?php echo $API_key ?>"></script>

		   			
			<table width="100%"	cellpadding="0" cellspacing="0" border="0" class="finance_table">
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
					
				<td align="left" width="29%">
					<div class="finwrap<?php echo $clr_i?>Year">
					<table width="100%"	cellpadding="1" cellspacing="1" border="0">
					 <tr>
					  <td width="2%" height="35" align="left" valign="middle"><input type="radio" name="finradio" id="radio" value="<?php echo $fin_id?>" <?php echo ($sel_code==$fin_id)?'checked':''?> />
						<label for="radio"></label></td>
					  <td width="90%" align="left" valign="middle"><div class="finwrap<?php echo $clr_i?>Title"><strong><?php echo $fin_name?></strong></div></td>
					</tr>
					
					
					
				
					
					<tr>
					  <td colspan="2">
						  <table width="100%" border="0" cellspacing="0" cellpadding="5">
						<tr>
						  <td width="50%">Order Value</td>
						  <td width="50%" align="right"><?php echo print_price($fin_order_price,true);?></td>
						</tr>
						<tr>
						  <td>Deposit/Admin Fee</td>
						  <td align="right"><?php echo print_price($fin_deposit_amt,true);?></td>
						</tr>
						<tr>
						  <td>Loan Amount</td>
						  <td align="right" id="loanamount<?php echo $clr_i;?>"><?php echo print_price($fin_loanamt,true);?></td>
						</tr>
						<tr>
						  <td height="26">Monthly Payment</td>
						  <td align="right"><div class="finselect" id="monthly_repayment<?php echo $clr_i;?>"><?php echo print_price($fn_permonth,true);?></div>
</td>
						</tr>
						<tr>
						  <td>Loan Repayment</td>
						  <td align="right" id="loanrepayment<?php echo $clr_i;?>"><?php echo print_price($fn_loanrepay,true);?></td>
						</tr>
						<tr>
						  <td>Cost Of Loan</td>
						  <td align="right" id="costofloan<?php echo $clr_i;?>"><?php echo print_price($fn_costofloan,true);?></td>
						</tr>
						<tr>
						  <td>Total payable</td>
						  <td align="right" id="total_repayment<?php echo $clr_i;?>"><?php echo print_price($fn_totalpayable,true);?></td>
						</tr>
					  </table>
					  	<script type="text/javascript">
                  //  var my_fd_obj<?php echo $clr_i;?> = new FinanceDetails("<?php echo $fin_code ?>", <?php echo $fin_order_price?>, 10, <?php echo $fin_deposit_amt;?>);
                   // var my_fd_obj = new FinanceDetails("ONIF12", 7500, 10, 0);
                    
					$("#monthly_repayment<?php echo $clr_i;?>").html("&pound;"+(my_fd_obj<?php echo $clr_i;?>.m_inst).toFixed(2));
					$("#total_repayment<?php echo $clr_i;?>").html("&pound;"+(my_fd_obj<?php echo $clr_i;?>.total).toFixed(2));
					$("#costofloan<?php echo $clr_i;?>").html("&pound;"+(my_fd_obj<?php echo $clr_i;?>.l_cost).toFixed(2));
					$("#loanamount<?php echo $clr_i;?>").html("&pound;"+(my_fd_obj<?php echo $clr_i;?>.l_amount).toFixed(2));
					$("#loanrepayment<?php echo $clr_i;?>").html("&pound;"+(my_fd_obj<?php echo $clr_i;?>.l_repay).toFixed(2));
                    </script>
					  </td>
					  </tr>
					 </table>
					</div>
				</td>
			<?php
			if($clr_i==3)
			{
			  echo "</tr><tr>";
			}
			}
			?>
			</tr>
			<tr>
			<td colspan="3" width = "100%">
				<div class="disability_divA">
					<img border="0" title="New" src="<?php echo url_site_image('new_post.gif')?>"></img>
			Please note that your EMI finance example and monthly repayment are designed to give you an indication of how much you may expect to pay for your loan. Due to the many factors involved in KLARNA making their decisions, we cannot guarantee being able to obtain a loan at the above quoted rates / prices.
			</div>
			</td>
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
			if(check_IndividualSslActive())
			{
				$ecom_selfhttp = "https://";
			}
			else
			{
				$ecom_selfhttp = "http://";
			}
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
						//instead of site id = 104 use $ecom_siteid when live
						$sql_getc = "SELECT finance_id,finance_name FROM finance_details WHERE sites_site_id = 104 and  finance_id=$saved_finid LIMIT 1";
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
						echo "<form method='post' action='".$ecom_selfhttp."$ecom_hostname/cart.html' id='cart_invalid_form' name='cart_invalid_form'><input type='hidden' name='hold_section' value='".$msg."' size='1'/><div style='position:absolute; left:0;top:0;padding:5px;background-color:#CC0000;color:#FFFFFF;font-size:12px;font-weight:bold'>Loading...</div></form><script type='text/javascript'>document.cart_invalid_form.submit();</script>";
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
			
			
	       
			// done to handle the case of protected or normal area	
			if($protectedUrl)
				$http = url_protected('index.php?req=cart&cart_mod=show_checkout',1);
			else 	
				$http = url_link('checkout.html',1);	
				
				/*<?php echo $http?>index.php?req=cart&cart_mod=show_checkout*/				
		
		
  
  $HTML_treemenu = '	<div class="breadcrump"><nav class="breadcrumb">
  <a class="breadcrumb-item" href="'.url_link('',1).'" title="'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a>

  <span class="breadcrumb-item active">'.$Captions_arr['CART']['CART_CHECKOUTHEADING'].'</span>
</nav></div>';
 
		?>	
  <div class="checkout_container container-fluid"  >
	  <?php
	   echo $HTML_treemenu;
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
	<div class="row chkout">
	<div class="col-md-6 fieldtab">
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
				//include 'show_dynamic_fields.php';
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
				//include 'show_dynamic_fields.php';
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
									<div class="field_name_div">
								<?php echo stripslashes($row_checkout['field_name']); if($row_checkout['field_req']==1) { echo '<span class="redtext">*</span>';}?>
								</div>
								<div class="field_value_div">
								<?php
								$class_array['txtbox_cls']='form-control';
								$class_array['select_cls']='form-control';

								
									echo get_Field($row_checkout['field_key'],$saved_checkoutvals,$cartData['customer'],'',$class_array);
								?>
								</div>
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
				//include 'show_dynamic_fields.php';
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
								<td align="right"  class="col1">
									<div class="field_name_div">
								<?php echo stripslashes($row_checkout['field_name']); if($row_checkout['field_req']==1) { echo '<span class="redtext">*</span>';}?>
								</div>
								<div class="field_value_div">
								<?php
								$class_array['txtbox_cls']='form-control';
								$class_array['select_cls']='form-control';

								
									echo get_Field($row_checkout['field_key'],$saved_checkoutvals,$cartData,'',$class_array);
								?>
								</div>
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

				//include 'show_dynamic_fields.php';			

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
								<td  width="25%" class="col1">
									<div class="field_name_div">
								<?php echo stripslashes($row_checkout['field_name']); if($row_checkout['field_req']==1) { echo '<span class="redtext">*</span>';}?>
								</div>
								<div class="field_value_div">
								<?php
									echo get_Field($row_checkout['field_key'],$saved_checkoutvals,$cartData);
								?>
								</div>
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
</div>
<div class="col-md-6">
	<table class="table summary">
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
				?>	<div class="dividerA" style="padding-bottom:10px;border-bottom:1px solid #ccc;">&nbsp;</div></td></tr>
			   <?php				
               }
               ?>
               <tr>
						<td colspan="4">
						<table cellpadding="2" cellspacing="2" class="summaryTotals" width="100%">						   
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
	</div>	
	</div>
	
	<div class="backto_container">
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
				<table border="0" width="100%" cellpadding="0" cellspacing="0" class="chk-continue_cls">		
					
					<tr>
            <td  align="left" valign="top">	
	             <input name="backto_cart" type="button" class="btn btn-warning btn-shop" id="backto_cart" value="<?php echo $Captions_arr['CART']['CART_BACK_CARTPAGE']?>" onclick="show_wait_button(this,'Please Wait...');gobackto_cart('<?php echo ($protectedUrl)? base64_encode($ecom_hostname):$ecom_hostname?>')"/>
             </td>
                         <td  align="right" valign="top">	

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
							<input name="continue_checkout" type="submit" class="btn-lg btn btn-success btn-block btn-checkout" id="continue_checkout" value="<?php echo $checkout_caption?>"/>
					<?php
					}
					else
					{
					?>
						<input type="image" name="continue_checkout" src="https://checkout.google.com/buttons/checkout.gif?merchant_id=711690661192356&amp;w=160&amp;h=43&amp;style=white&amp;variant=text&amp;loc=en_GB" id="continue_checkout" border="0"  class="btn-primary-bt topcart-bt" style="border:0" />
					<?php	
					}
					?>
			  <?php
			  	}
				else
				{
						echo "<div class='cart_checkout_div' align='right'><span class='red_msg'>".$Captions_arr['CART']['CART_LOGIN_CHECKOUT']."</span></div>";
				}
			  ?>
				 
				</td>
                </tr>
                <tr>
            <td  align="left" colspan="2" valign="top" id='checkout_msg_div' align="center" style="width:100%; display:none">
				<input type="hidden" name="pass_url" id="pass_url" value="<?php echo $_REQUEST['pass_url']?>"/>
				 <div >
					<span class="red_msg">
					<?php echo $Captions_arr['CART']['CART_PLEASE_WAIT']?>
					</span>
				</div>	
				</td>
                </tr>
				</table>
			</td>
          </tr>
  </table>
  </div>
	
	
		  
    </form>
     
  </div>

		 <?php	
		}
		// Defining function to show the Order Preview page
		function Show_OrderPreview($return_order_arr)
		{
		global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,
		$Captions_arr,$inlineSiteComponents,$sitesel_curr,$default_Currency_arr,$ecom_testing,$ecom_themename,$components,
		$ecom_common_settings;
		
		if(check_IndividualSslActive())
		{
			$ecom_selfhttp = "https://";
		}
		else
		{
			$ecom_selfhttp = "http://";
		}
		
		$Captions_arr['CART'] 	= getCaptions('CART'); // Getting the captions to be used in this page
		$session_id 			= $sessid = session_id();	// Get the session id for the current section
		$cust_id				= get_session_var("ecom_login_customer"); // Get the customer id from session
		$cartData 				= cartCalc(); // Calling the function to calculate the details related to cart
		//echo 'cur order id'.$return_order_arr['order_id'].' session '.get_session_var('gateway_ord_id');
		//clear_Cart($sessid);// calling the function to clear the cart
		
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
		echo "<script type='text/javascript'>window.location='".$ecom_selfhttp.$ecom_hostname."/cart.html'</script>";
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
		
	
								
		
			$HTML_treemenu = '	<div class="breadcrump"><nav class="breadcrumb">
  <a class="breadcrumb-item" href="'.url_link('',1).'" title="'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a>
  
  <span class="breadcrumb-item active">'.stripslash_normal($Captions_arr['CART']['CART_CHECKOUTPREVIEWHEADING']).'</span>
</nav></div>';
			
							echo $HTML_treemenu;	
		?>	
		
		<?php
		/*
		<tr>
		<td colspan="6">
		<div class="cart_top_header">
		<div class="cart_top_info_hdr_preview"><?php echo stripslash_normal($Captions_arr['CART']['CART_CHECKOUTPREVIEWHEADING'])?></div>
		</div>
		</td>
		</tr>		
		<?php
		*/ 
		if($auto_submit==false)
		{
		?>
		<div class="checkout_container"  >
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="cart_table">
		<tr>
		<td align="left" valign="top" class="checkout_left">
			<div class="row chkout">
		<div class="col-md-6 fieldtab">
		<table width="100%" border="0" cellpadding="1" cellspacing="0" class="">
		<tbody> 
		<?php
		$cur_pos 		= 'Top';
		$show_header	= 1;
		$table_class			='checkout_det_table';
		$head_class  			= 'checkout_hdr';
		$cont_class             = 'regiconent';
		include 'show_dynamic_fields_orders.php';
		$table_class			='';
		?>
		<tr>
		<td colspan="2" class="checkout_hdr"><?php echo stripslash_normal($Captions_arr['CART']['CART_SHOW_BILLING_DETAILS'])?></td>
		</tr>
		
		<?php
		// Including the file to show the dynamic fields for orders to the give position of static fields
		$cur_pos 		= 'TopInStatic';
		$show_header	= 0;
		$table_class			='';
		$head_class  			= 'inner_checkout_hdr';
		$cont_class             = 'regiconent';
		include 'show_dynamic_fields_orders.php';
		
		?>	
		<tr>
		<td colspan="2" align="right" valign="middle">
		<table width="100%" cellpadding="1" cellspacing="1" border="0">
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
			if($row_ord[$row_checkout['field_orgname']]!=='')
			{
			?>
				<tr>
					<td align="left"  class="regiconent">
					<?php echo stripslash_normal($row_checkout['field_name'])?>
					</td>
					<td align="left"  class="regi_txtfeild">
					<?php
		echo stripslash_normal($row_ord[$row_checkout['field_orgname']]);
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
		$cur_pos 		= 'BottomInStatic';
		$show_header	= 0;
		$head_class  			= 'inner_checkout_hdr';
	    $table_class			='';
		$cont_class             = 'regiconent';
		include 'show_dynamic_fields_orders.php';
		// check whether billing and shipping address can be different in the site.
		if ($row_del['delivery_same_as_billing']==1)
		{
		?>
		<tr>
		<td colspan="2" align="left" valign="middle" class="regiconentA">
		<?php echo stripslash_normal($Captions_arr['CART']['CART_DELIVERY_SAME_BILLADDRESS'])?>
		</td>
		</tr>	
		<?php
		}
		else
		{				
		?>
		
		<tr>
		<td colspan="2" class="checkout_hdr"><?php echo stripslash_normal($Captions_arr['CART']['DELIVERY_ADDRESS'])?></td>
		</tr>
		<tr>
		<td colspan="2" align="right" valign="middle">
		<table width="100%" border="0" cellpadding="1" cellspacing="1">
		<tbody>
		
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
					if($row_del[$row_checkout['field_orgname']]!='')
					{		
		?>
				<tr>
				<td align="left"  class="regiconent">
				<?php echo stripslash_normal($row_checkout['field_name'])?>
				</td>
				<td align="left"  class="regi_txtfeild">
				<?php
		echo stripslash_normal($row_del[$row_checkout['field_orgname']]);
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
		}	
		// Including the file to show the dynamic fields for orders to the give position of static fields
		$cur_pos 		= 'Bottom';
		$show_header	= 1;
		$head_class  			= 'checkout_hdr';
		$table_class			='checkout_det_table';
		$cont_class             = 'regiconent';
		include 'show_dynamic_fields_orders.php';
		?>
		
		</tbody>
		</table>
		</div>
					<div class="col-md-6">

		
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table summary">
		<tr>
						<td colspan="2" class="colHead"><?php echo $Captions_arr['CART']['CART_ITEM']?></td>
						<td class="colHead" align="center"><?php echo $Captions_arr['CART']['CART_QTY']?></td>
						<td class="colHead" align="right"><?php echo $Captions_arr['CART']['CART_PRICE']?></td>
						</tr>
						
						<tr><td colspan="4"><div class="smallDivider">&nbsp;</div></td></tr>
		
		<?php
		
		// Following section iterate through the products in cart and show its list
		$cur_indx = 0;
		while($row_orddet = $db->fetch_array($ret_orddet))
		{
		$vars_exists = false;
		if($cur_indx%2==0)
		$cur_class  ='shoppingcart_pdt1';
		else
		$cur_class = 'shoppingcart_pdt2';
		$cur_indx++;
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
		
		}
		?>
		<tr >
		<td  align="left" valign="top" class="image">
		<?php 
		// Check whether thumb nail is to be shown here
		if ($Settings_arr['thumbnail_in_viewcart']==1)
		{
		?>
		<a href="<?php url_product($row_orddet['products_product_id'],$row_orddet['product_name'],-1)?>" title="<?php echo stripslash_normal($row_orddet['product_name'])?>">
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
		</a>
		<?
		}
		?>
		</td>
		<td class="desc" valign="top"><a href="<?php echo url_product($row_orddet['products_product_id'],$row_orddet['product_name'])?>" title="<?php echo $row_orddet['product_name']?>" class="newCartTitle"><?php echo $row_orddet['product_name']?></a>
		
		</td>
		<td align="left" valign="top" class="qty"><label>
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
		echo stripslash_normal($row_checkprods['product_det_qty_drop_prefix']).' ';
		echo $row_orddet["order_qty"];
		if (trim($row_checkprods['product_det_qty_drop_suffix'])!='')
		echo ' '.stripslash_normal($row_checkprods['product_det_qty_drop_suffix']);
		}
		
		else
		{
			echo "&nbsp;&nbsp;".$row_orddet["order_qty"];
		}
		?>	
		</label></td>
		<td align="right" valign="middle" class="price" ><?php echo print_price($row_orddet['order_rowtotal'],true)?></td>
		</tr>
		<tr>
		<td colspan="4">
		<?php
		
		
		$check_arr = is_grid_display_enabled_prod($row_orddet['products_product_id']);
		 if($check_arr['enabled']==false)
         {
		// If variables exists for current product, show it in the following section
			if ($vars_exists) 
			{
				/*
			?>
			<tr>
			<td colspan="2">&nbsp;</td>
			<td align="left" valign="middle" colspan="4" class="<?php echo $cur_class?>">
			<?
			*/
			// show the variables for the product if any
			if ($db->num_rows($ret_vars))
			{
			//print_r($products_arr['prod_vars']);
			while($row_vars = $db->fetch_array($ret_vars))
			{
				echo " <div>";
				if (trim($row_vars['var_value'])!='')
				{
					// get the var_value_id for current value for the current variable 
					$sql_getvarvals = "SELECT var_value_id FROM product_variable_data WHERE product_variables_var_id = ".$row_vars['var_id']." AND var_value = '".$row_vars['var_value']."' LIMIT 1";
					$ret_getvarvals = $db->query($sql_getvarvals);
					if($db->num_rows($ret_getvarvals))
					{
						$row_getvarvals = $db->fetch_array($ret_getvarvals);
						$sql_getmpn = "SELECT var_mpn FROM product_variable_data WHERE var_value_id = ".$row_getvarvals['var_value_id']." LIMIT 1";
						$ret_getmpn = $db->query($sql_getmpn);
						if($db->num_rows($ret_getmpn))
						{
							$row_getmpn = $db->fetch_array($ret_getmpn);
							if(trim($row_getmpn['var_mpn'])!='')
							$mpn = ' <br>'.$Captions_arr['CART']['CART_MPN'].stripslashes($row_getmpn['var_mpn']);
						}
					}	
					print "<span class='cartvariable'>".stripslashes($row_vars['var_name']).": ". stripslashes($row_vars['var_value']).$mpn."</span><br />"; 
				}	
				else
					print "<span class='cartvariable'>".stripslashes($row_vars['var_name'])."</span><br />"; 
					
			   echo "</div>";
			}	
			}
			// Show the product messages if any
			if ($db->num_rows($ret_msgs)) 
			{	
				while($row_msgs = $db->fetch_array($ret_msgs))
				{
					print "<span class='cartvariable'>".stripslash_normal($row_msgs['message_caption']).": ".stripslash_normal($row_msgs['message_value'])."</span><br />"; 
				}	
			}	
			/*
			?>
			</td>
			</tr>	
			<?php
			*/ 
			}
		}
		?>
		<div class="dividerA" style="padding-bottom:10px;border-bottom:1px solid #ccc;">&nbsp;</div>
		</td>
		</tr>
		<?php		
		}
		?>	
		<tr >
		<td colspan="4" align="right" valign="top" class="chk_td_price">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td align="right" valign="middle" class="cart_total"><?php echo stripslash_normal($Captions_arr['CART']['CART_TOTPRICE'])?>&nbsp;</td>
		<td align="right" valign="middle" class="cart_total"><?php echo print_price($row_ord["order_subtotal"],true)?></td>
		</tr>
		 <?php
		if($row_ord['order_giftwrap']=='Y') // Check whether giftwrap module exists for the current site
		{
		// Show the following if giftwrap option is set for the site
		?>	
		<tr>
		<td  align="left" valign="middle" class="cart_td_normal" colspan="2"><?php echo stripslash_normal($Captions_arr['CART']['CART_GIFTWRAP_REQ'])?> (<?php echo print_price($row_ord['order_giftwrap_minprice'],true)?>): <?php if ($row_ord['order_giftwrap']=='Y') echo 'Yes'?></td>
		</tr>
		<?php
		if($row_ord['order_giftwrapmessage']=='Y')
		{
		?>
		<tr>
		<td align="right" valign="middle" class="cart_td_normal"><?php echo stripslash_normal($Captions_arr['CART']['CART_GIFTWRAP_MSG'])?>(<?php echo print_price($row_ord['order_giftwrap_message_charge'],true)?>)</td>
		<td align="right" valign="middle" class="cart_td_normal">Yes</td>
		</tr>
		<tr>
		<td align="right" valign="middle" class="cart_td_normal"><?php echo stripslash_normal($Captions_arr['CART']['CART_MSG'])?></td>
		<td align="right" valign="middle" class="cart_td_normal"><?php echo nl2br(stripslash_normal($row_ord['order_giftwrapmessage_text']))?></td>
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
		<td align="left" valign="middle" class="cart_td_normal" colspan="2">
		<?php 
		switch(strtolower($row_giftwrap_det['giftwrap_type']))
		{
		case 'ribbon':
		$gift_caption = stripslash_normal($Captions_arr['CART']['CART_RIBBON']);
		break;
		case 'paper':
		$gift_caption = stripslash_normal($Captions_arr['CART']['CART_PAPERS']);
		break;
		case 'card':
		$gift_caption = stripslash_normal($Captions_arr['CART']['CART_CARDS']);
		break;
		case 'bow':
		$gift_caption = stripslash_normal($Captions_arr['CART']['CART_BOWS']);
		break;
		};
		echo $gift_caption?>(<?php echo print_price($row_giftwrap_det['giftwrap_price'],true)?>): <?php echo stripslash_normal($row_giftwrap_det['giftwrap_name'])?></td>
		</tr>
		<?php		
		}
		?>
		<tr>
		<td align="right" valign="middle" class="cart_td_normal"><?php echo stripslash_normal($Captions_arr['CART']['CART_GIFTWRAP_TOTAL'])?></td>
		<td align="right" valign="middle" class="cart_td_normal"><?php echo print_price($row_ord["order_giftwraptotal"],true)?></td>
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
		<td align="left" valign="middle" class="cart_td_normal" colspan="2"><?php echo stripslash_normal($Captions_arr['CART']['CART_DELIVERY_LOC'])?>: <?php echo stripslash_normal($row_ord['order_deliverylocation'])?></td>
		</tr>
		<?php
		}
		// Check whether any delivery group exists for the site
		if ($row_ord['order_delivery_option'])
		{
		?>	
		<tr>
		<td align="left" valign="middle" class="cart_td_normal" colspan="2"><?php echo stripslash_normal($Captions_arr['CART']['CART_DELIVERY_OPT'])?>: <?php echo stripslash_normal($row_ord['order_delivery_option'])?></td>
		</tr>
		<?php
		}	
		if ($row_ord['order_splitdeliveryreq']==1)
		{
		?>
		<tr>
		<td align="right" valign="middle" class="cart_td_normal"><?php echo stripslash_normal($Captions_arr['CART']['CART_DELIVERY_SPLIT_ACTIVATED'])?></td>
		<td align="right" valign="middle" class="cart_td_normal"><?php echo stripslash_normal($Captions_arr['CART']['CART_YES'])?></td>
		</tr>
		<?php	
		}
		// Check whether delivery is charged, then show the total after applying delivery charge
		if($row_ord["order_deliverytotal"]>0)
		{
		?>
		<tr>
		<td align="right" valign="middle" class="cart_td_normal"><?php echo stripslash_normal($Captions_arr['CART']['CART_TOTAL_DELIVERY_CHARGES'])?></td>
		<td align="right" valign="middle" class="cart_td_normal"><?php echo print_price($row_ord["order_deliverytotal"],true)?></td>
		</tr>
		<?php
		}
		}
		// Section to show the extra shipping cost
		if($row_ord["order_extrashipping"]>0)
		{
		?>
		<tr>
		<td align="right" valign="middle" class="cart_td_normal"><?php echo stripslash_normal($Captions_arr['CART']['CART_EXTRA_SHIPPING'])?></td>
		<td align="right" valign="middle" class="cart_td_normal"><?php echo print_price($row_ord["order_extrashipping"],true)?></td>
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
		<td align="right" valign="middle" class="cart_td_normal">
		<?php echo stripslash_normal($Captions_arr['CART']['CART_TAX_CHARGE_APPLIED'])?>
		<?	
		$charge_arr = array();
		while($row_tax = $db->fetch_array($ret_tax))
		{
		echo '<br/>('.$row_tax['tax_name']; ?> @ <? print $row_tax['tax_percent']; ?>%)
		<?	
		$charge_arr[] = print_price($row_tax['tax_charge']);
		}
		?>						</td>
		<td align="right" valign="middle" class="cart_td_normal">
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
		<td align="right" valign="middle" class="cart_td_normal">
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
		<td align="right" valign="middle" class="cart_td_normal">
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
		if($row_ord['promotional_code_code_id']!=0)
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
		$prom_caption	= stripslash_normal($Captions_arr['CART']['CART_PROMOTIONAL_CODE_DISC_APPLIED']);
		$prom_lessval	= '(-) '.print_price($row_prom['code_lessval']);
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
		$prom_caption	= stripslash_normal($Captions_arr['CART']['CART_GIFTVOUCHER_DISC_APPLIED']);
		$prom_lessval	= '(-) '.print_price($row_voucher['voucher_value_used']);
		}
		}
		?>
		<tr>
		<td align="right" valign="middle" class="cart_td_normal">
		<?php 
		echo $prom_caption;
		?>
		</td>
		<td align="right" valign="middle" class="cart_td_normal">
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
		<td align="right" valign="middle" class="cart_td_normal"><?php echo stripslash_normal($Captions_arr['CART']['CART_SPENT_BONUS_POINTS_VALUE'])?> </td>
		<td align="right" valign="middle" class="cart_td_normal">(-) <? echo print_price($row_ord['order_bonuspoint_discount'],true);?></td>
		</tr>
		<?php	
		}
		}
		// Bonus Points Earned
		if ($row_ord["order_bonuspoint_inorder"]>0)
		{
		?>
		<tr>
		<td align="left" valign="middle" class="cart_td_normal"><?php echo stripslash_normal($Captions_arr['CART']['CART_TOTAL_POINTS_EARN'])?>: <?php echo $row_ord["order_bonuspoint_inorder"]?></td>
		<td align="left" valign="middle" class="cart_td_normal"></td>
		</tr>
		<?php	
		}
		// show the total final price
		if ($row_ord['order_totalprice']>0)
		{
		?>
		
		<tr>
		<td  align="right" valign="middle" class="cart_totalA"><?php echo stripslash_normal($Captions_arr['CART']['CART_TOTAL_FINAL_COST'])?>&nbsp; </td>
		<td align="right" valign="middle" class="cart_totalA"><?php echo print_price($row_ord['order_totalprice'],true)?></td>
		</tr>
		<?php
		}
		$rem_val = $row_ord['order_totalprice'] - $row_ord['order_deposit_amt'];
		if($rem_val>0 and $row_ord['order_deposit_amt']>0)
		{
		?>
		<tr>
		<td  align="right" valign="middle" class="cart_totalA"><?php echo stripslash_normal($Captions_arr['CART']['CART_LESS_AMT_LATER'])?>&nbsp; </td>
		<td align="right" valign="middle" class="cart_totalA"><?php echo print_price($row_ord['order_deposit_amt'],true)?></td>
		</tr>
		<tr>
		<td align="right" valign="middle" class="cart_totalA"><?php echo stripslash_normal($Captions_arr['CART']['CART_TOTAL_AMT_NOW'])?>&nbsp; </td>
		<td align="right" valign="middle" class="cart_totalA"><?php echo print_price($rem_val,true)?>
		</td>
		</tr>
		<?php
		}		
		?>
		
		</table>
		</td>
		</tr>
		
		</table>
		</td>
		
		</tr>
		<tr>
            <td colspan="2" align="right" valign="middle" class="shoppingcartcontent">	
				<div class="order_confirmation">
				<table border="0" width="100%" cellpadding="0" cellspacing="0" class="chk-continue_cls">		
					
					<tr>
            <td  align="left" valign="top">	
	             <input class="btn btn-warning btn-shop" type="button" onclick="show_wait_button(this,'Please Wait...');gobackto_cart('<?php echo ($protectedUrl)? base64_encode($ecom_hostname):$ecom_hostname?>')" value="<?php echo stripslash_normal($Captions_arr['CART']['CART_BACK_CARTPAGE'])?>" />
             </td>
                         <td  align="right" valign="top" class="btn_order_confirm">	
             <?php
		$display_option = 'ALL';
		// Including the file which hold the login for fields to be passed to payment gateway
		include 'order_preview_gateway_include.php';
		//$display_option = 'BUTTON_ONLY';
		//$display_option = 'ALL';
		// Including the file which hold the login for fields to be passed to payment gateway
		//include 'order_preview_gateway_include.php';
		?>	</div>
		</div>
				</td>
                </tr>
                
				</table>
			</td>
          </tr>
		</table>
		</div>
		
		<?php /*?></form><?php */?>
		
		
		<?
		}
		?>
		

		<?php		
		}
		
		/* Function to show the checkout success message*/
		function Show_CheckoutSuccess($order_id=0)
		{
			global $db,$ecom_hostname,$Captions_arr,$ecom_siteid,$ecom_isadword,$ecom_adword_conversionid,$ecom_adword_conversionlanguage,
					$ecom_adword_conversionformat,$ecom_adword_conversioncolor,$ecom_adword_conversionlabel,$protectedUrl,$ecom_success_script;
			if(check_IndividualSslActive())
			{
				$ecom_selfhttp = "https://";
			}
			else
			{
				$ecom_selfhttp = "http://";
			}	
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
				echo stripslash_normal($succ_script);
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
			
							
				
				
  $HTML_treemenu = '	<div class="breadcrump"><nav class="breadcrumb">
  <a class="breadcrumb-item" href="'.url_link('',1).'" title="'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a>
  
  <span class="breadcrumb-item active">'.stripslash_normal($Captions_arr['CART']['CART_CHECKOUT_SUCCESS_TITLE']).'</span>
</nav></div>';	
  echo $HTML_treemenu;	
		?>
			<div class="container"> 
			<div class="container"> 
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="shoppingcarttable">
			  <tr>
				<td align="left" valign="middle"></td>
			  </tr>
			  <tr>
			  	<td align="left" class="regicontentAA">
				<?php 
				echo stripslash_normal($Captions_arr['CART']['CART_CHECKOUT_SUCCESS_MSG']);
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
					<center><br><br></br><a href="http://api.tweetmeme.com/share?url=<?php echo $url?>&alias=<shortenedurl>&service=bit.ly"><img src="http://api.tweetmeme.com/imagebutton.gif?url=<?php echo $url?>" height="61" width="51" border="0" /></a></center>
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
					<td align="left" class="regiconent">
					<?php echo stripslash_normal($Captions_arr['CART'][$show_downloadable_msg])?>
					</td>
				</tr>
				 <tr>
					<td align="center" class="regiconent">
					<a href="<?php echo $ecom_selfhttp.$ecom_hostname; ?>/mydownloads.html" title="<?php echo stripslash_normal($Captions_arr['CART']['CART_DOWNLOAD_LIST_LINK'])?>" class="favoriteprodlink"><?php echo stripslash_normal($Captions_arr['CART']['CART_DOWNLOADABLE_LINK'])?> </a><?php echo stripslash_normal($Captions_arr['CART']['CART_DOWNLOADABLE_LINK_CONT'])?>.</td>
					</td>
				</tr>
			<?php
				}			
			?>
			</table>
			</div>
			</div>
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
				$msg		= stripslash_normal(trim($row_cart['cart_error_msg_ret']));
			}
			// update the cart_error_msg_ret field with blank 
			$update_array						= array();
			$update_array['cart_error_msg_ret']	= '';
			$db->update_from_array($update_array,'cart_supportdetails',array('sites_site_id'=>$ecom_siteid,'session_id'=>$sess_id));					
			
			$Captions_arr['CART'] 	= getCaptions('CART'); // Getting the captions to be used in this page
			//$HTML_treemenu = '<div class="treemenu"><a href="'.url_link('',1).'" title="'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a><span>&raquo;</span>'.stripslash_normal($Captions_arr['CART']['CART_CHECKOUT_FAILED_TITLE']).'</div>';


			//echo $HTML_treemenu;
				
			
		
  $HTML_treemenu = '	<div class="breadcrump"><nav class="breadcrumb">
  <a class="breadcrumb-item" href="'.url_link('',1).'" title="'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a>
  
  <span class="breadcrumb-item active">'.stripslash_normal($Captions_arr['CART']['CART_CHECKOUT_FAILED_TITLE']).'</span>
</nav></div>';	
  echo $HTML_treemenu;
				/*<?php echo $http?>index.php?req=cart&cart_mod=show_checkout*/

			//echo $HTML_treemenu;
		?><div class="container"> 
			<div class="container"> 
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="shoppingcarttable">
			<tr>
				<td align="left" valign="middle"></td>
			</tr>
			<?php
				if($msg)
				{
			?>
				<tr>
					<td align="left" class="regicontentA">
					<?php echo $msg?>
					</td>
				</tr>
			<?php
				}
				else
				{
			?>
					<tr>
						<td align="left" class="regicontentA">
						<?php echo stripslash_normal($Captions_arr['CART']['CART_CHECKOUT_FAILED_MSG'])?><br /><br />
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
			</div>
			</div>
		<?php	
		}
		/* Function to show the checkout failed message*/
		function Show_NoChexCommonSuccess()
		{
			global $db,$ecom_hostname,$Captions_arr,$ecom_siteid,$Captions_arr;
			$sess_id = session_id();
			
			$Captions_arr['CART'] 	= getCaptions('CART'); // Getting the captions to be used in this page
			
						
		$HTML_treemenu = '	<div class="breadcrump"><nav class="breadcrumb">
  <a class="breadcrumb-item" href="'.url_link('',1).'" title="'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a>
  
  <span class="breadcrumb-item active">'.stripslash_normal($Captions_arr['CART']['CART_NOCHEXCHECKOUT_SUCCESS']).'</span>
</nav></div>';
			echo $HTML_treemenu;
		?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="shoppingcarttable">
			<tr>
				<td align="left" valign="middle"></td>
			</tr>
			
					<tr>
				<td align="center" class="regicontentA">
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
			<div  class="alert alert-danger" id="instockmsg_div">
<a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>			
<?php echo stripslash_normal($Captions_arr['CART']['CART_ERROR_HEADING'])?>.	

			<span class="carterrormsg_msg">
			<?php echo $msg?></span>
			</div>
		<?php	
		}
		function show_paypal_msg($msg)
		{
			global $Captions_arr,$ecom_siteid;
			$Captions_arr['CART']	= getCaptions('CART');
			//if($ecom_siteid==44)
			//{
		?>
				<div  class="alert alert-danger" id="instockmsg_div">
<a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>			
				<?php echo stripslash_normal($Captions_arr['CART']['CART_PAYPAL_HEADING'])?>.	
				<span class="carterrormsg_msg">
				<?php echo stripslash_normal($Captions_arr['CART']['CART_PAYPAL_MESSAGE'])?>.
				</span>
				</div>
		<?php
			//}	
		}
		function showpromotional_error_msg($msg)
		{
			global $Captions_arr;
			$Captions_arr['CART']	= getCaptions('CART');
		?>
			<div  class="alert alert-danger" id="instockmsg_div">
			<?php echo stripslash_normal($Captions_arr['CART']['CART_ERROR_HEADING'])?>.
			<span class="carterrormsg_msg">
			<?php echo $msg?></span>
			</div>
		<?php	
		}
		function upsell_products_display()
		{
			global $db,$ecom_siteid,$ecom_hostname,$inlineSiteComponents;
			global $Captions_arr;
			
			if(check_IndividualSslActive())
			{
				$ecom_selfhttp = "https://";
			}
			else
			{
				$ecom_selfhttp = "http://";
			}
			$Captions_arr['PROD_DETAILS'] = getCaptions('PROD_DETAILS');
			$Captions_arr['CART']	= getCaptions('CART');
			$session_id 	= session_id();
			$avoid_prod_arr	= array(0);
			// Get the list of products existing in the cart.
			$sql_cart = "SELECT products_product_id 
							FROM 
								cart 
							WHERE 
								sites_site_id = $ecom_siteid 
								AND session_id = '$session_id'";
			$ret_cart = $db->query($sql_cart);
			if($db->num_rows($ret_cart))
			{
				while ($row_cart = $db->fetch_array($ret_cart))
				{
					$avoid_prod_arr[] = $row_cart['products_product_id'];
				}
			}
			$cat_arr = $prods_arr = $prod_arr = array();
			
			// Get the list of categories to which each of these products are linked with
			$sql_cat = "SELECT product_categories_category_id FROM product_category_map WHERE products_product_id IN(".implode(',',$avoid_prod_arr).") ";
			$ret_cat = $db->query($sql_cat);
			if($db->num_rows($ret_cat))
			{
				while ($row_cat = $db->fetch_array($ret_cat))
				{
					$cat_arr[] = $row_cat['product_categories_category_id'];
				}
				
				if(count($cat_arr))
				{
					
					/*// Get the list of products which are mapped to obtained categories
					$sql_prods = "SELECT distinct products_product_id FROM product_category_map WHERE product_categories_category_id IN (".implode(',',$cat_arr).")";
					$ret_prods = $db->query($sql_prods);
					if($db->num_rows($ret_prods))
					{
						while ($row_prods = $db->fetch_array($ret_prods))
						{
							if(!in_array($row_prods['products_product_id'],$avoid_prod_arr))
							{
								$prods_arr[] = $row_prods['products_product_id'];
							}
								
						}
					}*/
					
						$start		= date("Y-m-d",mktime(0, 0, 0, date("m")-12  , date("d"), date("Y")));
						$end 		= date("Y-m-d");
						$sql_best 	= "SELECT p.product_id,sum(b.order_orgqty) as totcnt  
										FROM 
											orders a,order_details b,products p 
										WHERE 
											a.order_id=b.orders_order_id 
											AND a.sites_site_id=$ecom_siteid 
											AND b.products_product_id=p.product_id 
											AND p.product_hide ='N' 
											AND a.order_date >= '$start 00:00:00' AND a.order_date <= '$end 23:59:59'
											AND a.order_status NOT IN ('CANCELLED','NOT_AUTH') 
											AND p.product_default_category_id IN (".implode(',',$cat_arr).") 
											AND p.product_id NOT IN (".implode(',',$avoid_prod_arr).") 
										GROUP BY 
											b.products_product_id  
										ORDER BY 
											totcnt DESC 
										LIMIT 
											20";
					$res = $db->query($sql_best);
					if($db->num_rows($res))
					{
						while ($row_prods = $db->fetch_array($res))
						{
							if(!in_array($row_prods['product_id'],$avoid_prod_arr))
							{
								$prods_arr[] = $row_prods['product_id'];
							}
								
						}
					}
				}
			}
			$hold_prods_arr = $prods_arr;
			// Check whether any upsell products are manually set up in the database
			$sql_manupsell = "SELECT products_product_id 
								FROM 
									upsell_products_map 
								WHERE 
									sites_site_id = $ecom_siteid 
								ORDER  BY 
									upsell_order ASC";
			$ret_manupsell = $db->query($sql_manupsell);
			if($db->num_rows($ret_manupsell))
			{
				while ($row_manupsell = $db->fetch_array($ret_manupsell))
				{
					if(!in_array($row_manupsell['products_product_id'],$avoid_prod_arr) and !in_array($row_manupsell['products_product_id'],$hold_prods_arr))
					{
						$prods_arr[] = $row_manupsell['products_product_id'];
					}
				}
			}
			
			if(count($prods_arr))
			{
				$prod_arr = array();
				$sql_prod = "SELECT * FROM products WHERE product_id IN (".implode(',',$prods_arr).") AND product_hide = 'N' and sites_site_id = $ecom_siteid ";
				$ret_prod = $db->query($sql_prod);
				if($db->num_rows($ret_prod))
				{
						$pass_type = get_default_imagetype('link_prod');

			$width_one_set 	= 166;
			$min_number_req	= 4;
			$min_width_req 	= $width_one_set * $min_number_req;
			$total_cnt		= $db->num_rows($ret_prod);
			$calc_width		= $total_cnt * $width_one_set;
			if($calc_width < $min_width_req)
				$div_width = $min_width_req;
			else
				$div_width = $calc_width; 
					while ($row_prod = $db->fetch_array($ret_prod))
					{
						$prod_arr[] = $row_prod;
					}
					//echo "<pre>";var_dump($prod_arr);echo "</pre>";
			?>
					<div class="container-fluid yomaylike">
<div class="headtitle-white"><?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_PEOPLE_INTERESTED']);?></div>
<script type="text/javascript">
			$(function() {

				var $c = $('#carousel1'),
					$w = $(window);

				$c.carouFredSel({
					align: false,
					items:3,
					scroll: {
						items: 2,
						duration: 8000,
						timeoutDuration: 0,
						easing: 'linear',
						pauseOnHover: 'immediate'
					}
				});

			});
		</script>


<div class="container-fluid">
<div id="demo2">
<div class="customNavigation2">
									<a class="prev glyphicon glyphicon-arrow-left  btn-info"><i class="fas fa-angle-left"></i></a>
									<a class="next glyphicon glyphicon-arrow-right  btn-info"><i class="fas fa-angle-right"></i></a>
									</div>

	<div id="owl-demo2" class="owl-carousel">      
									<?php
								$i=0;
								$cnt_rem = count($prod_arr);
								$cur_col=0;
								$max_col=2;
								while($i<$cnt_rem)
								{
									$row_prod = $prod_arr[$i];
					?>
					<div class="item">
					<div class="product-grid">
					<div class="product-title"><a class="product_name_a" href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a>
</div>
					<p class="product-info"><?php echo stripslash_normal($row_prod['product_shortdesc']); ?></p>

					<div class="product-img-wrap"><<a class="product_pic_a" href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" <?php //echo getmicrodata_producturl()?> >
										<?php
																			// Calling the function to get the image to be shown
																			$img_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,0,0,1);
																			if(count($img_arr))
																			{
																				show_image(url_root_image($img_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name']);
																			}
																			else
																			{
																				// calling the function to get the default image
																				$no_img = get_noimage('prod',$pass_type); 
																				if ($no_img)
																				{
																					show_image($no_img,$row_prod['product_name'],$row_prod['product_name']);
																				}
																			}
																			?>							
																		</a></div>  
					<?php $price_arr =  show_Price($row_prod,$price_class_arr,'cat_detail_1',false,5);
															//if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
																//{
																	$price_class_arr['ul_class'] 		= 'price-avl';
																	$price_class_arr['li_class'] 		= 'price';
																	$price_class_arr['normal_class'] 	= 'price';
																	$price_class_arr['strike_class'] 	= 'price_strike';
																	$price_class_arr['yousave_class'] 	= 'price_yousave';
																	$price_class_arr['discount_class'] 	= 'price_offer';
																	
																	//echo show_Price($row_prod,$price_class_arr,'shelfcenter_1');
																  
																 // }
																  $disc =  $price_arr['prince_without_captions']['discounted_price'];
																  $base =  $price_arr['prince_without_captions']['base_price'];
															
																	$curprice_tax_cap = curprice_tax($price_arr,$row_prod);
														   ?>
															<p class="price-details">2<span class="price"><?php
															if($disc!='')
															{
																	echo $disc;
																	echo $curprice_tax_cap;
															}
															else if($base!='')
															{
															echo $base;
															echo $curprice_tax_cap;
															}
															?> <?php //echo $curprice_tax_cap; ?></span></p><div class="product-table table-responsive">
<?php show_BulkDiscounts($row_prod,array()); ?></div>
					<div class="addwrap">
					<?php $frm_name = uniqid('catdet_'); ?>
													
																	<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
																	<input type="hidden" name="fpurpose" value="" />
																	<input type="hidden" name="fproduct_id" value="" />
																	<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
																	<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
													<?php			$class_arr['ADD_TO_CART']       = 'btn btn-outline-secondary addbt';
																	$class_arr['PREORDER']          = 'input-group-addon';
																	$class_arr['ENQUIRE']           = 'btn btn-outline-secondary addenq';
																	$class_arr['QTY_DIV']           = '';
																	$class_arr['QTY']               = 'form-control qty_txt';
																	$class_arr['BTN_CLS']     = 'btn btn-outline-secondary addbt';
																	echo show_addtocart_responsive($frm_name,$row_prod,$class_arr,1,'shelf',false,false,'new_v2');?>
															<a class="btn btn-outline-secondary detailbt" href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">Details</a>
										
																	</form>
					</div>

					</div>
					</div>
										

					<?php
					$i=$i+1;
					$cur_col++;
				
			}
					?>		
</div>								
</div>

</div>

</div>
			<?php	
				}
			}
		}
	};	
	
?>
