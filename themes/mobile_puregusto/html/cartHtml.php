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
					
			if(check_IndividualSslActive())
			{
				$ecom_selfhttp = "https://";
			}
			else
			{
				$ecom_selfhttp = "http://";
			}
					
					//print_r($_REQUEST);
			$session_id 			= session_id();	// Get the session id for the current section
			$cust_id				= get_session_var("ecom_login_customer"); // Get the customer id from session
			$Captions_arr['CART'] 	= getCaptions('CART'); // Getting the captions to be used in this page
			$Captions_arr['CUST_LOGIN'] = getCaptions('CUST_LOGIN');
			$cartData 				= cartCalc(); // Calling the function to calculate the details related to cart
			// Calling function to get the messages to be shown in the cart page
			$cart_alert_arr 			= get_CartMessages($_REQUEST['hold_section'],1);
			$cart_alert					= $cart_alert_arr['msg'];
			//$Settings_arr['forcecustomer_login_checkout'] = 1;
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
			$HTML_treemenu = '<div class="tree_menu_con">
								  <ul class="tree_menu">
								<li><a href="'.url_link('',1).'" title="'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a> | </li>
								 <li>'.stripslash_normal($Captions_arr['CART']['CART_MAINHEADING']).'</li>
								</ul>
							</div>';
			echo $HTML_treemenu;	
		?>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="ctable">
            <tr>
            <td class="curvea_top"><div class="white"><?php echo stripslash_normal($Captions_arr['CART']['CART_MAINHEADING']);?></div></td>
            </tr>
            <tr>
            <td class="tda">
            <?
            $HTML_topdesc ='';
            $HTML_topdesc .='<div class="cart_top_outer">';
            if (count($cartData['products'])>0)
            { // Check whether logged in 
            $HTML_topdesc .= '<div class="cart_top_info">';
            if ($cust_id) // Case logged in 
            {
            $HTML_topdesc .=' <div class="cart_top_info_name"><strong>'.stripslash_normal($Captions_arr['CART']['CART_LOGGED_IN_AS']).'&nbsp;'.get_session_var('ecom_login_customer_shortname').'</strong></div>
            <div class="cart_top_info_link">'.stripslash_normal($Captions_arr['CART']['CART_IF_YOU_NOT_LOG']).' <a href="'.$ecom_selfhttp.$ecom_hostname.'/logout.html?rets=1" title="Logout" class="cartlogin_link">'.stripslash_normal($Captions_arr['CART']['CART_HERE']).'</a>&nbsp;'.stripslash_normal($Captions_arr['CART']['CART_TO_LOGOUT']).' </div>';
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
            if($ps_url) // show the continue shopping button only if ps_url have value
            {
            $HTML_topdesc .= '<div class="cart_shop_cont"><div><a href="#" onClick="show_wait_button(this,\'Please Wait...\');window.location=\''.$ps_url.'\'">'.stripslash_normal($Captions_arr['CART']['CART_CONT_SHOP']).'</a></div></div>';
            }
            else
            $HTML_topdesc .= '&nbsp;';
            $HTML_topdesc .= '</div>';
            $HTML_topdesc .= '</div>';  
            echo $HTML_topdesc;  
            if($cart_alert and count($cartData['products'])>0)
            {
            ?>
            <div class="cart_msg_outer">
            <div class="cart_msg_top"></div>
            <div class="cart_msg_txt">
            - <?php echo $cart_alert?> -
            </div>
            <div class="cart_msg_bottom"></div>
            </div>
            <?php
            }
            ?>
            </td>
            </tr>
			<?php 
            if (count($cartData['products'])==0) // Done to show the message if no products in cart
            {
            ?>
            <tr>
            <td class="tda_noprod">
            <?php echo stripslash_normal($Captions_arr['CART']['CART_NO_PRODUCTS'])?></td>
            </tr>	
            <?php
            }
       		 else
			{	  
			?>
            <tr>
                <td class="tda"> 
                    <form method="post" name="frm_cart"  id="frm_cart" class="frm_cls" action="<?php url_link('cart.html')?>">
                    <input type="hidden" name="fpurpose" value="" />
                    <input type="hidden" name="remcart_id" value="" />
                    <input type="hidden" name="cart_mod" value="show_cart" />
                    <input type="hidden" name="hold_section" value="" />
                    <?php $cur_indx = $all_prods_free_delivery_cnt = 0;		  
                    // Following section iterate through the products in cart and show its list
                    foreach ($cartData['products'] as $products_arr)
                    {
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
                    if($cur_indx%2==0)
                    {
                   		$cur_class  ='shoppingcart_pdt1';                   		
                   		$cur_tclass  ='gtableA';

					}
                    else
                    {
                    	$cur_class = 'shoppingcart_pdt1';
                    	$cur_tclass  ='gtableA';

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
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="<?php echo $cur_tclass?>">
                    <tr>
                        <td colspan="2" class="tdbgrys"><a href="<?php echo url_product($products_arr['product_id'],$products_arr['product_name'])?>" title="<?php echo $products_arr['product_name']?>" ><?php echo $products_arr['product_name']?></a></td>
                    </tr>
                    <tr>
                           <td class="td">
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
									<br />
								<?php
								}
								?>
								 <?php 
                    if($Settings_arr["product_show_instock"]==1)
					{
						?>
					        
                            <?php
                            // Calling the function to check whether the current product is in preorder or not
                            $preorder = check_Inpreorder($products_arr['product_id']);
                            if ($preorder['in_preorder']=='Y')
                                echo '<div class="nostock">Available on'.'<br />'.$preorder['in_date'].'</div>';
                            else
                            {
                                if($products_arr['stock']>0 or $products_arr['product_alloworder_notinstock']=='Y')
                                echo '<div class="instock">In Stock</div>';
                            }	
                            ?>                          
						<?php
					}
                    ?>
				</td>
                         <td class="td">
                        <?php 
                        // If variables exists for current product, show it in the following section
                        if ($vars_exists) 
                        {
                            // show the variables for the product if any
                            if ($products_arr['prod_vars']) 
                            {
                                foreach($products_arr["prod_vars"] as $productVars)
                                {
                                    if (trim($productVars['var_value'])!='')
                                    print "<div class='nblack'>".stripslashes($productVars['var_name']).": ". stripslashes($productVars['var_value'])."</div>"; 
                                    else
                                    print "<div class='nblack'>".stripslashes($productVars['var_name'])."</div"; 
                                    
                                }	 
                            }
                            // Show the product messages if any
                            if ($products_arr['prod_msgs']) 
                            {	
                                foreach($products_arr["prod_msgs"] as $productMsgs)
                                {
                                    print "<div class='nblack'>".stripslashes($productMsgs['message_title']).": ". stripslashes($productMsgs['message_value'])."</div>"; 
                                }	
                            }	
                        }
                        ?>
                        <?php
                         if($products_arr['product_det_qty_type']=='DROP')
						{
							if (trim($products_arr['product_det_qty_drop_prefix'])!='')
							echo stripslash_normal($products_arr['product_det_qty_drop_prefix']).' ';
							echo $products_arr['cart_qty'];
							if (trim($products_arr['product_det_qty_drop_suffix'])!='')
							echo ' '.stripslash_normal($products_arr['product_det_qty_drop_suffix']);
							?>
							<input type="hidden" name="cart_qty_<?php echo $products_arr['cart_id']?>" id="cart_qty_<?php echo $products_arr['cart_id']?>" value="<?php echo $products_arr["cart_qty"]?>" />
							<?php	
						}
                        else
						{ 
							?>
							
							<input name="cart_qty_<?php echo $products_arr['cart_id']?>" type="text" id="cart_qty_<?php echo $products_arr['cart_id']?>" size="5" maxlength="4" value="<?php echo $products_arr["cart_qty"]?>"  class="det_qty_txt"  />
							 <label>
                            <a href="#" onclick="document.frm_cart.remcart_id.value='<?php echo $products_arr['cart_id']?>';handle_form_submit(document.frm_cart,'Update_qty','#upd')" title="<?php echo stripslash_normal($Captions_arr['CART']['CART_UPDATE'])?>"><input type="button" name="button" id="button" value="<?php echo stripslash_normal($Captions_arr['CART']['CART_UPDATE'])?>" class="breda" /></a>
                          </label>							
							<?php
						} 
                        ?>
                         <a href="#" class="update_link" onclick="if (confirm_message('<?php echo stripslash_normal($Captions_arr['CART']['CART_ITEM_REM_MSG'])?>')) {document.frm_cart.remcart_id.value='<?php echo $products_arr['cart_id']?>';handle_form_submit(document.frm_cart,'Remove_qty','#rem')}" title="<?php echo stripslash_normal($Captions_arr['CART']['CART_REMOVE'])?>">
                            <input type="button" name="button3" id="button3" value="<?php echo stripslash_normal($Captions_arr['CART']['CART_REMOVE'])?>"  class="bred"/>
                            </a>
                        </td>
                    </tr>                   
                    <tr>
                        <td class="tdbgrysa"><?php /* 
                        if($products_arr['product_det_qty_type']=='DROP')
						{
							if (trim($products_arr['product_det_qty_drop_prefix'])!='')
							echo stripslash_normal($products_arr['product_det_qty_drop_prefix']).' ';
							echo $products_arr['cart_qty'];
							if (trim($products_arr['product_det_qty_drop_suffix'])!='')
							echo ' '.stripslash_normal($products_arr['product_det_qty_drop_suffix']);
							?>
							<input type="hidden" name="cart_qty_<?php echo $products_arr['cart_id']?>" id="cart_qty_<?php echo $products_arr['cart_id']?>" value="<?php echo $products_arr["cart_qty"]?>" />
							<?php	
						}
                        else
						{ 
							?>
							
							<input name="cart_qty_<?php echo $products_arr['cart_id']?>" type="text" id="cart_qty_<?php echo $products_arr['cart_id']?>" size="5" maxlength="4" value="<?php echo $products_arr["cart_qty"]?>"  class="det_qty_txt"  />
							 <label>
                            <a href="#" onclick="document.frm_cart.remcart_id.value='<?php echo $products_arr['cart_id']?>';handle_form_submit(document.frm_cart,'Update_qty','#upd')" title="<?php echo stripslash_normal($Captions_arr['CART']['CART_UPDATE'])?>"><input type="button" name="button" id="button" value="<?php echo stripslash_normal($Captions_arr['CART']['CART_UPDATE'])?>" class="breda" /></a>
                          </label>							
							<?php
						} 
						*/ 
                        ?>
                        </td>
                        <td class="tdbgrys" align="right"><span class="fbig"><?php echo print_price($products_arr['final_price'],true)?></span></td>
                    </tr>
                    <?php
                    /*
                    <tr>
                        <td colspan="2" align="right" class="td">
                            <a href="#" class="update_link" onclick="if (confirm_message('<?php echo stripslash_normal($Captions_arr['CART']['CART_ITEM_REM_MSG'])?>')) {document.frm_cart.remcart_id.value='<?php echo $products_arr['cart_id']?>';handle_form_submit(document.frm_cart,'Remove_qty','#rem')}" title="<?php echo stripslash_normal($Captions_arr['CART']['CART_REMOVE'])?>">
                            <input type="submit" name="button3" id="button3" value="<?php echo stripslash_normal($Captions_arr['CART']['CART_REMOVE'])?>"  class="bred"/>
                            </a>
                        </td>
                    </tr>
                    */ 
                    ?>
                    </table>
                    <?php 
					}
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
						?>
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="gtable"> 
						<?php
						if ($show_giftdet)
						{
							?>	
							<tr>
								<td  colspan="2" align="left" valign="middle" class="tdbgry"><a name="a_gwrap">&nbsp;</a><?php echo $Captions_arr['CART']['CART_GIFTWRAP_REQ']?> (<?php echo print_price($row_gift['giftwrap_minprice'],true)?>)</td>
							</tr>
							<tr>
                                <td   colspan="2" align="right" valign="middle" class="tdbgry">
                                <?php
                                	echo generateselectbox('chk_giftwrapreq',array(0=>'No',1=>'Yes'),$row_cartdet['giftwrap_req'],'','handle_form_submit(document.frm_cart,"save_commondetails","#a_gwrap")');
                                ?>
                                </td>
							</tr>
							<?php 
							if($row_cartdet['giftwrap_req']==1)
							{ 
							?>
                                <tr id="giftwrap_details_tr" >
                                    <td  align="left" valign="middle" class="tdbgry" colspan="2">
                                        <table width="100%" border="0" cellspacing="1" cellpadding="1">
                                            <tr>
                                            	<td  align="right" valign="middle" class="tdbgry"><?php echo $Captions_arr['CART']['CART_GIFTWRAP_MSG']?>(<?php echo print_price($row_gift['giftwrap_messageprice'],true)?>)</td>
                                            </tr>
                                            <tr>
                                            	<td  align="right" valign="middle" class="tdbgry">
												<?php
                                                echo generateselectbox('giftwrap_message_req',array(0=>'No',1=>'Yes'),$row_cartdet['giftwrap_msg_req'],'','handle_form_submit(document.frm_cart,"save_commondetails","#a_gwrap")');
                                                ?>
                                            	</td>
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
                                    <table width="100%" border="0" cellpadding="1" cellspacing="1" class="gtable">
                                        <tr>
                                        	<td align="left" class="tdbgry"><a name="a_gwrapopt">&nbsp;</a><?php echo $Captions_arr['CART']['CART_WRAP_OPT']?></td>
                                        </tr>
                                    </table>
                                    <table width="100%" border="0" cellpadding="1" cellspacing="1" class="gtable">
                                        <tr>
                                        <?php
                                        // show the section to display the various types of ribbons
                                        if ($db->num_rows($ret_ribbon))
                                        {
                                        ?>
                                            <td class="tdbgry">
                                            <span class="shoppingcartgiftwrap_detsubheading"><?php echo $Captions_arr['CART']['CART_RIBBON']?></span><br />
                                            &nbsp;&nbsp;&nbsp;&nbsp;<input name="ribbon_radio" type="radio" class="shoppingcart_radio" id="ribbon_radio" value="0" checked="checked" onclick="handle_form_submit(document.frm_cart,'save_commondetails','#a_gwrapopt')" />
                                            <?php echo $Captions_arr['CART']['CART_NONE'];
                                            while ($row_ribbon = $db->fetch_array($ret_ribbon))
											{
												// Making the decision of checked radio button and the collapse of expand image
												if ($row_cartdet['giftwrap_ribbon_id']==$row_ribbon['ribbon_id'])
												{
													$checked 	= 'checked="checked"';
												}
												else
												{
													$checked 	= '';
												}		
												?>		
												<input type="radio" name="ribbon_radio" id="ribbon_radio" value="<?php echo $row_ribbon['ribbon_id']?>" class="shoppingcart_radio" <?php echo $checked?>  onclick="handle_form_submit(document.frm_cart,'save_commondetails','#a_gwrapopt')"/><?php echo stripslashes($row_ribbon['ribbon_name'])?> (<?php echo print_price($row_ribbon['ribbon_extraprice'],true)?>)<br />
												<?php
											}
                                            ?>											
                                            </td>
                                        	<?php
                                       	 	++$giftwrap_cnt;
                                        }
                                        // show the section to show the different types of papers
                                        if ($db->num_rows($ret_paper))
                                        {
                                        ?>
                                            <td class="tdbgry">
                                                <span class="shoppingcartgiftwrap_detsubheading"><?php echo $Captions_arr['CART']['CART_PAPER']?></span><br />
                                                &nbsp;&nbsp;&nbsp;&nbsp;<input name="paper_radio" type="radio" class="shoppingcart_radio" id="paper_radio" value="0" checked="checked" onclick="handle_form_submit(document.frm_cart,'save_commondetails','#a_gwrapopt')" />
                                                <?php echo $Captions_arr['CART']['CART_NONE']; 
                                                while ($row_paper = $db->fetch_array($ret_paper))
                                                {
													// Making the decision of checked radio button and the collapse of expand image
													if ($row_cartdet['giftwrap_paper_id']==$row_paper['paper_id'])
													{
														$checked 	= 'checked="checked"';
													}
													else
													{
														$checked 	= '';
													}	
													?>		
														<input type="radio" name="paper_radio" id="paper_radio" value="<?php echo $row_paper['paper_id']?>" class="shoppingcart_radio" <?php echo $checked?> onclick="handle_form_submit(document.frm_cart,'save_commondetails','#a_gwrapopt')"/><?php echo stripslashes($row_paper['paper_name'])?> (<?php echo print_price($row_paper['paper_extraprice'],true)?>)<br />
													<?php
													}
                                                ?>											
                                            </td>
                                        <?php
                                        ++$giftwrap_cnt;
                                        }
                                        // show the section to display the various cards
                                        if ($db->num_rows($ret_card))
                                        {
                                        if ($giftwrap_cnt==2)
                                        echo "</tr><tr>";
                                        ?>
                                         <td class="tdbgry">
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
                                            }
                                            else
                                            {
                                            $checked 	= '';
                                            }	
                                            ?>		
                                            <input type="radio" name="card_radio" id="card_radio" value="<?php echo $row_card['card_id']?>" class="shoppingcart_radio" <?php echo $checked ?> onclick="handle_form_submit(document.frm_cart,'save_commondetails','#a_gwrapopt')"/><?php echo stripslashes($row_card['card_name'])?> (<?php echo print_price($row_card['card_extraprice'],true)?>)<br />
                                            <?php
                                            }
                                            ?>											
                                        </td>
                                        <?php
                                        ++$giftwrap_cnt;
                                        }
                                        // Show the section to show various bows
                                        if ($db->num_rows($ret_bow))
                                        {
                                        if ($giftwrap_cnt==2)
                                        echo "</tr><tr>";
                                        ?>
                                         <td class="tdbgry">
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
                                            }
                                            else
                                            {
                                            $checked 	= '';
                                            }
                                            ?>		
                                            <input type="radio" name="bow_radio" id="bow_radio" value="<?php echo $row_bow['bow_id']?>" class="shoppingcart_radio" <?php echo $checked ?> onclick="handle_form_submit(document.frm_cart,'save_commondetails','#a_gwrapopt')"/><?php echo stripslashes($row_bow['bow_name'])?> (<?php echo print_price($row_bow['bow_extraprice'],true)?>)<br />
                                            <?php
                                            }
                                            ?>											
                                        </td>
                                        <?php
                                        ++$giftwrap_cnt;
                                        }
                                        if($giftwrap_cnt>2 and $giftwrap_cnt<4)
                                        {
                                        ?>
                                        <td align="left" valign="top" class="tdbgry" colspan="<?php echo (4-$giftwrap_cnt)?>">&nbsp;</td>
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
					}
                    // Check whether giftwrap charge is there, if it is there then show the total of gift wrap
                    if($cartData["totals"]["giftwrap"])
                    {
                    ?>
                    <tr>
                        <td class="tdbgry"><?php echo $Captions_arr['CART']['CART_GIFTWRAP_TOTAL']?></td>
                        <td class="tdbgry"><?php echo print_price($cartData["totals"]["giftwrap"],true)?></td>
                    </tr>
                    <?php
                    }?>
                    </table>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="gtable">
                        <?php
                        // Calling the function to decide the delivery details display section 
                        $deliverydet_Arr = get_Delivery_Display_Details();
                        if ($deliverydet_Arr['delivery_type'] != 'None') // Check whether any delivery method is selected for the site
                        {
                        ?>
                        <tr>
                        <td class="tdbgry_z" colspan="2">
                        <input type="hidden" name="cart_delivery_id" id="cart_delivery_id" value="<?php echo $deliverydet_Arr['delivery_id']?>"  />	
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <?php
                        // Case if location is to be displayed
                        if (count($deliverydet_Arr['locations']))
                        {
                        ?>	
                        <tr>
                        <td class="tdbgry"><?php echo stripslash_normal($Captions_arr['CART']['CART_DELIVERY_LOC'])?>
                        <a name="a_deliv">&nbsp;</a> </td>
                        </tr>
                        <tr>                        
                        <td class="tdbgry">
							
							<?php                        
                        echo generateselectbox('cart_deliverylocation',$deliverydet_Arr['locations'],$row_cartdet['location_id'],'','handle_form_submit(document.frm_cart,"save_commondetails","#a_deliv")');
                        ?>	       </td>
                        </tr>
                        <?php
                        }
                        // Check whether any delivery group exists for the site
                        if (count($deliverydet_Arr['del_groups']))
                        {
                        ?>
                        <tr>
                        <td  class="tdbgry"><?php echo stripslash_normal($Captions_arr['CART']['CART_DELIVERY_OPT'])?>
                        </td>
                        </tr>
                        <tr>                       
                        <td class="tdbgry">
						<style>
							.selectclassnew{
								width:280px;
							}
							</style>	
							
							  <?php
                        echo generateselectbox('cart_deliveryoption',$deliverydet_Arr['del_groups'],$row_cartdet['delopt_det_id'],'','handle_form_submit(document.frm_cart,"save_commondetails","#a_deliv")',0,'selectclassnew');
                        ?>	       </td>
                        </tr>
                        <?php
                        }
                        ?>   
                        </table>
                        </td>
                        </tr>
                        <?php
                        // Check whether split delivery is supported by current site for current delivery method
                        if ($deliverydet_Arr['allow_split_delivery']=='Y' and $cartData["pre_order"] != "none" and count($cartData['products'])>1)
                        {
                        ?>
                        <tr>
                        <td class="tdbgry" colspan="2">
                        <table width="100%" border="0" cellspacing="1" cellpadding="1">
                        <tr>
                        <td class="tdbgry" ><?php echo stripslash_normal($Captions_arr['CART']['CART_WANT_DELIVERY_SPLIT'])?></td>
                        <td class="tdbgry" ><input type="checkbox" name="cart_splitdelivery" id="cart_splitdelivery" <?php echo ($row_cartdet['split_delivery']==1)?'checked="checked"':''?> onclick="handle_form_submit(document.frm_cart,'save_commondetails','#a_deliv')" /></td>
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
                        <td class="tdbgry">
                        <? 
                        print $cartData["delivery"]["group".$i]["items"].stripslash_normal($Captions_arr['CART']['CART_DELIVERY_CHARGE_FOR']);
                        if($cartData["delivery"]["group".$i]["items"] > 1)
                        { 
                        print ' '.stripslash_normal($Captions_arr['CART']['CART_SPLIT_ITEMS']); 
                        }
                        else
                        { 
                        print ' '.stripslash_normal($Captions_arr['CART']['CART_SPLIT_ITEM']); 
                        }
                        print ' '.stripslash_normal($Captions_arr['CART']['CART_TO_DESPATCH_ON'])." " . $show_date ?></td>
                        <td class="td">
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
                        <td class="tdbgry"><?php echo stripslash_normal($Captions_arr['CART']['CART_TOTAL_DELIVERY_CHARGES'])?></td>
                        <td class="tdbgry" align="right"><?php echo print_price($cartData["totals"]["delivery"],true)?></td>
                        </tr>
                        <?php
                        }
                       
                        if($ecom_siteid==104) // case 
                        {
							$disable_arr = array();
							$disable_arr[0] = "--Please Select--"; 
							$sql_disable = "SELECT * FROM disability_type ORDER BY sort_order";
							$ret_disable = $db->query($sql_disable);
						?>
							<input type="hidden" name="dis_msg_disp" id="dis_msg_disp" value="<?php echo $Captions_arr['CART']['CART_DISABLE_SELMSG']?>" />
						<?php
								// Case if location is to be displayed
							if ($db->num_rows($ret_disable)>0)
							{
								?>
								<tr>
									 <td class="tdbgry" colspan="2"><?php echo $Captions_arr['CART']['CART_DISABILITY']?></td>
								</tr>
								<tr>
									 <td class="tdbgry" colspan="2">	 
										<select name = "disability_type" id = "disability_type" onchange='handle_form_submit(document.frm_cart,"save_commondetails","#a_deliv1")'>
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

										</select>
										<?php
											//$disable_arr[-1] = $Captions_arr['CART']['CART_DISABLE_NONE']; 
												//echo generateselectbox('disability_type',$disable_arr,$row_cartdet['disable_id'],'','handle_form_submit(document.frm_cart,"save_commondetails","#a_deliv")');
										?>	</td>
								   </tr>
					
						<?php	
							 }
						}
						 // Section to show the tax details
                        if($cartData["totals"]["tax"])
                        {
                        ?>
                        <tr>
                        <td  class="tdbgry">
                        <?php echo stripslash_normal($Captions_arr['CART']['CART_TAX_CHARGE_APPLIED'])?>
                        <?	
                        foreach($cartData["tax"] as $tax)
                        {
                        echo '<br />('.$tax['tax_name']; ?> @ <? print $tax['tax_val']; ?>%)
                        <?	
                        }
                        ?>						</td>
                        <td  class="tdbgry" align="right">
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
                        if($cartData["totals"]["extraShipping"])
                        {
                        ?>
                        <tr>
                        <td  class="tdbgry"><?php echo stripslash_normal($Captions_arr['CART']['CART_EXTRA_SHIPPING'])?></td>
                        <td  class="tdbgry" align="right"><?php echo print_price($cartData["totals"]["extraShipping"],true)?></td>
                        </tr>
                        <?php	
                        }
                        if ($cartData["bonus"]["value"])
                        {
                        ?>
                        <tr>
                        <td  class="tdbgry" ><?php echo stripslash_normal($Captions_arr['CART']['CART_SPENT_BONUS_POINTS_VALUE'])?>&nbsp; </td>
                        <td  class="tdbgry" align="right">(-) <? echo print_price($cartData["bonus"]["value"],true);?></td>
                        </tr>
                        <?php			
                        }
                        // If gift voucher or promotional code is valid
                        if (($row_cartdet['promotionalcode_id']!=0 or $row_cartdet['voucher_id']!=0))
                        {
                        ?>
                        <tr>
                            <td  class="tdbgry">
                            <?php 
                            if ($row_cartdet['promotionalcode_id']!=0 and $cartData["bonus"]['type']=='promotional' and $cartData['totals']['pro_type']!='product')
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
                            <td class="tdbgry" align="right">
                            <?php 
                            if ($row_cartdet['promotionalcode_id']!=0 and $cartData["bonus"]['type']=='promotional' and $cartData['totals']['pro_type']!='product')
                            echo '(-) '.print_price($cartData['totals']['lessval'],true);
                            elseif($row_cartdet['voucher_id']!=0 and $cartData["bonus"]['type']=='voucher')
                            echo '(-) '.print_price($cartData['totals']['lessval'],true);
                            ?></td>
                        </tr>
                        <?php
                        if ($row_cartdet['promotionalcode_id']!=0 and $cartData["bonus"]['type']=='promotional' and $cartData['totals']['promotional_type']!='product')
						{
						} 
						elseif ($row_cartdet['promotionalcode_id']!=0 and $cartData['totals']['promotional_type']!='product')
						{
						?>	 
							<tr>
								<td colspan="6" align="center" valign="middle" class="shoppingcartcontent_prom_alert">
									<?php echo stripslash_normal($Captions_arr['CART']['CART_PROMO_NO_LINK_PROD']);?></td>
							</tr>
						<?php
						}
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
						  <td  align="left" class="tdbgry">
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
						  ?>					<br/>
						  <?php
						 	 if ($cartData["bonus"]['type']=='voucher' or $cartData["bonus"]['type']=='promotional' or $cartData['totals']['promotional_type']=='product') // handle the case of logged in or not.
							{
							?>
							<span style="float:right;">
									<input name="cart_savepromotional" type="hidden" id="cart_savepromotional" value="0"/>	
								 	<input name="cancel_promotionalcode" type="button" class="buttongray" id="cancel_promotionalcode" value="<?php echo $Captions_arr['CART']['CART_PROMO_CANCEL_BUTTON']?>" onclick="document.frm_cart.cart_savepromotional.value=1;handle_form_submit(document.frm_cart,'save_commondetails','#a_prom')" />
						        </span>
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
						  <a name="a_prom">&nbsp;</a>
						  </td>
						</tr>
					  </table>
					</div></td>
				  </tr>
		  <?php
		  			}
		  		}	
                        // show the total final price
                        if($cartData["totals"]["bonus_price"])
                        {
                        ?>
                        <tr>
                            <td class="tdbgry_final"><?php echo $Captions_arr['CART']['CART_TOTAL_FINAL_COST']?>&nbsp; </td>
                            <td class="tdbgry_final" align="right"><?php echo print_price($cartData["totals"]["bonus_price"],true)?></td>
                        </tr>
                        <?php
                        }
                        $rem_val = $cartData["totals"]["bonus_price"] - $str_reduceval;
                        if($rem_val >0 and $str_reduceval>0)
                        {
                        ?>
                        <tr>
                            <td class="tdbgry_final"><?php echo $Captions_arr['CART']['CART_LESS_AMT_LATER']?>&nbsp; </td>
                            <td class="tdbgry_final" align="right"><?php echo print_price($str_reduceval,true)?></td>
                        </tr>
                        <tr>
                            <td class="tdbgry_final"><?php echo $Captions_arr['CART']['CART_TOTAL_AMT_NOW']?>&nbsp; </td>
                            <td class="tdbgry_final" align="right"><?php echo print_price($rem_val,true)?>
                            <input type="hidden" name="store_reduceval" value="<?php echo $str_reduceval?>" />
                            </td>
                        </tr>
                        <?php
                        }	
                        ?>
                        <tr>
                        	<td class="tdbgry" colspan="2">&nbsp;</td>
                        </tr>
                        <?php
                        if($cartData["totals"]['full_free_delivery']==1 or $all_prods_free_delivery_cnt ==count($cartData['products']))
                        {
                        ?>
                        <tr>
                            <td class="tdbgry" colspan="2"><div>
                            <?php echo $Captions_arr['CART']['CART_BOTTOM_FREE_DELIVER_MSG']?></div></td>
                        </tr>
                        <?php
                        }
                        else
                        {
                        ?>
                        <tr>
                        	<td colspan="2" align="right" valign="middle" class=""><div class="cart_fre_dlry_blank"></div></td>
                        </tr>
                        <?php	
                        }
                        ?>
                    </table>
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
					AND a.paymethod_showinmobile  =1    
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
					AND a.paymethod_showinmobile  =1    
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
                    if($show_multiple==true)
                    {	
                    ?>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="gtable">
                        <tr>
                        <td class="td">
							<?php 
                            echo stripslash_normal($Captions_arr['CART']['CART_PAYMENT_MULTIPLE_MSG']);
                            ?>
                        </td>
                        </tr>	
                    </table>	
                    <?php
                    }
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
                    $hide_direct_checkout_button = false ; // Today start -- today end
                    $Settings_arr['paytype_listingtype']='dropdown';
                   
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
							//echo "Type ".$Settings_arr['paytype_listingtype'];
							// Check how the payment types to be displayed to the user
							if ($Settings_arr['paytype_listingtype']=='dropdown') // Case of payment listing type is to shown as drop downbox
							{
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
								/*if($row_paytypes['paytype_code']=='credit_card')
								{
									if($hide_direct_checkout_button==true)
									$exclude_current = true;
								}
								*/ 	
								//if($exclude_current==false) // today end
								$paymethod[$row_paytypes['paytype_id']] = stripslash_normal($row_paytypes['paytype_caption']).$add_text;	
									if($row_paytypes['paytype_code']=='credit_card')
									{
										if($row_paytypes['paytype_id']==$row_cartdet['paytype_id'])
										$cc_exists = true;
									}					
							}
							?>	
							<table width="100%" border="0" cellspacing="0" cellpadding="0" class="gtable">
                                <tr>
                                    <td class="td_paymentmaintd"><?php echo stripslash_normal($Captions_arr['CART']['CART_SEL_PAYTYPE'])?>
                                    </td></tr>

                                <tr>
                                    <td class="td_paymentmaintd"><a name="a_pay">&nbsp;</a>
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
								$pay_maxcnt = 7;
								$pay_cnt	= 0;
								?>
								<table width="100%" border="0" cellspacing="0" cellpadding="0" class="gtable">
                                    <tr>
                                        <td class="td">
                                        <a name="a_pay"></a>
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                <?php
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
													?>
													<td>
                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                            <tr>
                                                                <td class="td"><?php
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
                                                                ?></td>
                                                            <td class="td"> 
                                                            	<input class="shoppingcart_radio" type="radio" name="cart_paytype" id="cart_paytype" value="<?php echo $row_paytypes['paytype_id']?>" <?php echo ($row_paytypes['paytype_id']==$row_cartdet['paytype_id'])?'checked="checked"':''?> <?php echo $on_change?>/>
                                                            </td>
                                                            <td class="td"><?php echo stripslash_normal($row_paytypes['paytype_caption']).$add_text?></td>
                                                            </tr>
                                                        </table>    
                                                    </td>
													<?php
												}
                                                for($i=$pay_cnt;$i<$pay_maxcnt;$i++)
                                                {
                                                echo "<td  style='padding-left:40px'></td>";
                                                }
                                                ?>	
                                                </tr>
                                            </table>    
                                         </td>
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
						//$hide_direct_checkout_button = true; // today start
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
                                                        echo generateselectbox('cart_paymethod',$paymethodcc,$row_cartdet['paymethod_id'],'','');
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
                    } // End of $cartData["totals"]["bonus_price"] >0 
                    // Check whether the google checkout button is to be displayed
                    if($google_exists and $google_recommended==0 && $show_checkoutbutton==true)
					{
						$row_google = $db->fetch_array($ret_google);
						?> 
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="gtable">
                            <tr>
                                <td class="td">
                                    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="shoppingcarttable">
										<?php
                                        
                                        if($totpaycnt>0 or $hide_direct_checkout_button == false) // today start -- today end
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
					 $HTML_bottomdesc ="<table width='100%' border='0' cellspacing='0' cellpadding='0'>
										<tr>";
					if($ps_url) // show the continue shopping button only if ps_url have value
                    {
						$HTML_bottomdesc .= "
						<td align='left'>";
						//$HTML_bottomdesc .= '<div class="cart_shop_cont"><div><a href="#" onClick="show_wait_button(this,\'Please Wait...\');window.location=\''.$ps_url.'\'"><input name="'.stripslash_normal($Captions_arr['CART']['CART_GO_CHKOUT']).'" type="button" value="'.stripslash_normal($Captions_arr['CART']['CART_CONT_SHOP']).'"  class="byellow_det"/></a></div></div>';
						$HTML_bottomdesc .= '<div class="cart_shop_cont"><div><a href="#" onClick="show_wait_button(this,\'Please Wait...\');window.location=\''.$ps_url.'\'">'.stripslash_normal($Captions_arr['CART']['CART_CONT_SHOP']).'</a></div></div>';
						$HTML_bottomdesc .='</td>
						';
                    }
                    else
						$HTML_bottomdesc .= '';					
                    // Handling the case of login required before checkout.
                    if (count($cartData['products'])>0)
                    {
						$HTML_bottomdesc .= "
						<td  align='right'>";
						if($hide_direct_checkout_button==false)// today start
						{
							if($show_checkoutbutton==true)	
							{
								$HTML_bottomdesc .= '<div class="cart_shop_chkout"><div> <a href="#" onClick="handle_checkout_submit(\''.$ecom_hostname.'\',0,\''.$cartData["totals"]["bonus_price"].'\')"><input name="'.stripslash_normal($Captions_arr['CART']['CART_GO_CHKOUT']).'" type="button" value="'.stripslash_normal($Captions_arr['CART']['CART_GO_CHKOUT']).'"  class="byellow_det"/></a></div></div>';
							}
							else
							{
								$HTML_bottomdesc .= "<div class='cart_checkout_div' align='right'><span class='red_msg'><a href=\"".url_link('custlogin.html',1)."?redirect_back=1&pagetype=cart\" title=\"Login\" class=\"cartlogin_link\">Please Click here to login to continue to Checkout.</a></span></div>";
							}
						}// today start
						$HTML_bottomdesc .='</td>';
                    }
                    
					$HTML_bottomdesc .= '</tr></table>';	
                    echo $HTML_bottomdesc;
                    // case if paypal button is to be displayed
                    if($ecom_common_settings['paymethodKey']['PAYPAL_EXPRESS']['paymethod_key'] == "PAYPAL_EXPRESS" and $show_checkoutbutton==true)// and $_SERVER['REMOTE_ADDR']=='118.102.196.27')
                    {
                    ?>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="gtable">
                        <tr>
                            <td class="td">
                                <table width="100%" border="0" cellpadding="0" cellspacing="0" class="shoppingcarttable">
                                <?php
                                if(($totpaycnt>0) or ($google_exists and $google_recommended==0) or $hide_direct_checkout_button==false) // today start -- today end
                                {
                                ?>
                                    <tr>
                                        <td align="center" valign="middle" class="google_or" colspan="2">
                                        <img src="<?php echo url_site_image('gateway_or.gif')?>" alt="Or" border="0" />
                                        </td>
                                    </tr>	
                                <?php
                                }
                                ?>
                                    <tr>
                                    	<?php/*<td align="left" valign="top" class="google_td" width="60%"><?php echo stripslashes($Captions_arr['CART']['CART_PAYPAL_HELP_MSG']);?></td>*/?>
                                        <td align="center" valign="middle" class="google_td">
                                        <input type='image' name='submit' src='https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif' border='0' align='top' alt='PayPal' onclick="handle_checkout_submit('<?php echo $ecom_hostname?>',2,'<?php echo $cartData["totals"]["bonus_price"]?>')"/>
                                        </td>
                                    </tr>	
                                </table>
                            </td>
                        </tr>
                    </table>
                    <?php 	
                    }
                   
                    ?>
                    <input type="hidden" name="pass_url" id="pass_url" value="<?php echo $ps_url?>"/>  
                    <input type="hidden" name="cc_req_indicator" id="cc_req_indicator" value="<?php echo $cc_exists?>" />
                    <input type="hidden" name="paysel_msg_disp" id="paysel_msg_disp" value="<?php echo stripslash_normal($Captions_arr['CART']['CART_SEL_PAYTYPE_MSG'])?>" />
                    <input type="hidden" name="gate_msg_disp" id="gate_msg_disp" value="<?php echo stripslash_normal($Captions_arr['CART']['CART_SEL_PAYGATEWAY_MSG'])?>" />
                    <input type="hidden" name="del_msg_disp" id="del_msg_disp" value="<?php echo stripslash_normal($Captions_arr['CART']['CART_DELIVERY_LOC_MSG'])?>" />
                    </form> </td>
            </tr>
			<?php 
			}
        ?>
        </table> 
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
	
     
		
		<?php	
		}
		// Defining function to show the checkout page
		function Show_Checkout()
		{
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,
					$inlineSiteComponents,$Settings_arr,$protectedUrl,$ecom_common_settings,$image_path,
					$ecom_site_delivery_location_country_map;
			global $show_cart_password;
			
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
		    /*if(!$cust_id)
		    {
			   ?>
			   <script language="javascript">
			   window.location = "http://<?php echo $ecom_hostname?>/cart.html";
			   </script>
			   <?php
			   exit;
			}*/
			
			$cartData = cartCalc(); // Calling the function to calculate the details related to cart
				// Calling function to get the messages to be shown in the cart page
			$cart_alert = get_CartMessages($_REQUEST['hold_section']);
			// Calling function to get the checkout details saved temporarly for current site in current session
			$disable_field = false;
            //$disable_field = true;//delete these while live 
			      
			//$address = array("FIRSTNAME"=>'latheesh',"MIDDLENAME"=>'t',"LASTNAME"=>'george',"BUSINESS"=>'Businessline',"SHIPTOSTREET"=>'street11',"SHIPTOSTREET2"=>'lencaster2',"SHIPTOCITY"=>'lencastercity',"SHIPTOSTATE"=>'lencasterstate',"SHIPTOCOUNTRYCODE"=>'IN'); 

			if($_REQUEST['pret']==1) // case if coming back from PAYPAL with token.
			{
				if($_REQUEST['token'])
				{
					$Settings_arr['same_billing_shipping_checkout'] = 0;
					$disable_field = true;//for solving issue related the delivery address 
					$address = GetShippingDetails($_REQUEST['token']);
					$ack = strtoupper($address["ACK"]);
					if($ack == "SUCCESS" ) // case if address details obtained correctly
					{
						$_REQUEST['payer_id'] = $address['PAYERID'];
						//$cartData["payment"]["method"]['paymethod_key'] = '';
					}
					else // case if address not obtained from paypay .. so show the error msg in cart
					{
						$msg = 'CART_PAYPAL_EXP_NO_ADDRESS_RET';
						echo "<form method='post' action='".$ecom_selfhttp."$ecom_hostname/cart.html' id='cart_invalid_form' name='cart_invalid_form'><input type='hidden' name='hold_section' value='".$msg."' size='1'/><div style='position:absolute; left:0;top:0;padding:5px;background-color:#CC0000;color:#FFFFFF;font-size:12px;font-weight:bold'>Loading...</div></form><script type='text/javascript'>document.cart_invalid_form.submit();</script>";
						exit;
					}
				}	
			}
			$saved_checkoutvals = get_CheckoutValues_puregusto($address);
			
			
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
			
			if($ecom_siteid==104)
			{
				if (!$row_cartdet['disable_id'])// case if disability is not selected
				{
						echo "<form method='post' action='".$ecom_selfhttp."$ecom_hostname/cart.html' id='cart_invalid_form' name='cart_invalid_form'><input type='hidden' name='hold_section' value='".$msg."' size='1'/><div style='position:absolute; left:0;top:0;padding:5px;background-color:#CC0000;color:#FFFFFF;font-size:12px;font-weight:bold'>Loading...</div></form><script type='text/javascript'>document.cart_invalid_form.submit();</script>";
						exit;
				}
			}
			
			
			// done to handle the case of protected or normal area	
			if($protectedUrl)
				$http = url_protected('index.php?req=cart&cart_mod=show_checkout',1);
			else 	
				$http = url_link('checkout.html',1);	
				
				
				/*<?php echo $http?>index.php?req=cart&cart_mod=show_checkout*/
			$HTML_treemenu = '<div class="tree_menu_con">
								  <ul class="tree_menu">
								<li><a href="'.url_link('',1).'" title="'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a> | </li>
								 <li>'.stripslash_normal($Captions_arr['CART']['CART_CHECKOUTHEADING']).'</li>
								</ul>
								  </div> ';
			echo $HTML_treemenu;		
		?>	
						<form method="post" name="frm_checkout"  id="frm_checkout" class="frm_cls" action="<?php echo $http?>" onsubmit="return validate_checkout_fields(document.frm_checkout)">
						<input type="hidden" name="fpurpose" id="fpurpose" value="place_order_preview" />
						<input type="hidden" name="remcart_id" id="remcart_id" value="" />
						<input type="hidden" name="cart_mod" id="cart_mod" value="show_checkout" />
						<input type="hidden" name="hold_section" id="hold_section" value="" />
                        <input type="hidden" name="ret_back" id="ret_back" value="" />
                        <input type="hidden" name="from_mod" id="from_mod" value="" />
						<input type="hidden" name="save_checkoutdetails" id="save_checkoutdetails" value="" />
						<input type="hidden" name="pret" value="<?php echo $_REQUEST['pret'];?>"/>
						<input type="hidden" name="token" value="<?php echo $_REQUEST['token'];?>"/>
                        <input type="hidden" name="erm" value="<?php echo $_REQUEST['erm'];?>"/>

						<input type="hidden" name="payer_id" value="<?php echo $_REQUEST['payer_id'];?>"/>
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="ctable">
						<?php
						if(file_exists($image_path.'/site_images/checkout_special_top.gif'))
						{
						?>
						<tr>
						<td  class="mainmiddle_carttdA" valign="top" align="right">
						<div class="cart_bottom_html"><img src="<?php url_site_image('checkout_special_top.gif')?>"></div>
						</td>
						</tr>
						<?php
						}
						?>
								  <tr>
   								 <td class="curvea_top">
								<div class="white">
									<?php echo stripslash_normal($Captions_arr['CART']['CART_CHECKOUTHEADING'])?></div>
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
									<div class="cart_msg_txt">- <?php echo $cart_alert?> -</div>
								<?php
								}
								?>  
								</td>
								</tr>
								<tr>
								<td class="top_checkout_backcart"><div class="chk_back_btn">
											<div><a href="#" class="cart_blink_checkout" onclick="show_wait_button(this,'Please Wait...');gobackto_cart('<?php echo ($protectedUrl)? base64_encode($ecom_hostname):$ecom_hostname?>')"><?php echo stripslash_normal($Captions_arr['CART']['CART_BACK_CARTPAGE'])?></a></div>
										</div> 
								</td>
								</tr>		
								<tr>
									<td align="left" valign="top" class="tda" >
							<table width="100%" border="0" cellpadding="0" cellspacing="0" class="gtable">
								<tbody> 
								<?php
									// Initializing the array to hold the values to be used in the javascript validation of the static and dynamic fields
									$chkout_Req			= $chkout_Req_Desc = $chkout_Email = $chkout_Confirm = $chkout_Confirmdesc = array();
									$chkout_Numeric 	= $chkout_multi = $chkout_multi_msg	= array();
					
									// Including the file to show the dynamic fields for checkout to the top of static fields
									
									$cur_pos 				= 'Top';
									$section_typ			= 'checkout';
									$formname 				= 'frm_checkout'; 
									$head_class  			= 'tdbgry';
									$specialhead_tag_start 	= '<span class="reg_header"><span>';
									$specialhead_tag_end 	= '</span></span>';
									$colspan 				= '';
									$cont_leftwidth 		= '50%'; 
									$cont_rightwidth 		= '50%';
									$cellspacing 			= 1;
									$cont_class 			= 'regiconent'; 
									$texttd_class			= '';
									$cellpadding 			= 1;		
									$colspan 	 			= 2;
									$table_class			='checkout_det_table';
									include 'show_dynamic_fields.php';
					                $head_class  			= '';
							?>
									<tr>
										<td colspan="2" class="tdbgry"><?php echo stripslash_normal($Captions_arr['CART']['CART_FILL_BILLING_DETAILS'])?></td>
									</tr>
									<?php
									// Including the file to show the dynamic fields for checkout to the top of static fields in same section as that of static fields
									$colspan 		= 2;
									$cur_pos 		= 'TopInStatic';
									$section_typ	= 'checkout'; 
									$formname 	= 'frm_checkout';
									$head_class  	= 'tdbgry';
									$colspan 		= '';
									$specialhead_tag_start 	= '<span class="reg_header"><span>';
									$specialhead_tag_end 	= '</span></span>';
									$cont_leftwidth 		= '50%'; 
									$cont_rightwidth 	= '50%';
									$cellspacing 	= 1;
									$cont_class 			= 'regiconent'; 
									$texttd_class			= 'regi_txtfeild';
									$cellpadding 	= 1;		
									$colspan 	 	= 1;
									$table_class			='';
									include 'show_dynamic_fields.php';
									$head_class  	= '';
									if($cust_id_commented)
									{
									   if($_REQUEST['checkout_account_same']=='')
										{
										 $account_same = 'Y';
										}
										else
										{
										  $account_same = $_REQUEST['checkout_account_same'];
										}
									}
									else
									{
										 $account_same = 'N';
									}
												  ?>												 
                                                  <tr>
													  
													  <td align="left" valign="middle" class="td" colspan="2">
														<?php 
														 if($cust_id_commented)
														{
														echo stripslash_normal($Captions_arr['CART']['CART_IS_BILLING_ADDRESS_SAME']);
														}
														?>
                                                        </td></tr>
													<tr>
                                                       <td align="left" valign="middle" class="td" colspan="2">
													 <?php
													 if($cust_id_commented)
													 {
													 ?>	   
														   
														   
														<select name="checkout_account_same" onchange="handle_billingaddress_change('billing',document.frm_checkout,0)" class="yselect">
														<option value="Y" <?php echo ($account_same=='Y')?'selected':''?>>Yes</option>
														<option value="N" <?php echo ($account_same=='N')?'selected':''?>>No</option>
														</select>
													<?php
													}
													else
													{
														?>
														<input type="hidden" name="checkout_account_same" value="N">
														<?php
													}
													?>	
														</td>
														
                                                        
													</tr>	
                                                    <?php
													//if(($account_same=='N')) 
													{ 
													 $saved_request = array();
													//print_r($_REQUEST);
													if($_REQUEST['from_mod']=='delivery')
													{
														foreach($_REQUEST as $kk=>$vval)
														{ 
														  if(substr($kk,0,8)=='checkout')
														  { 
															$saved_request[$kk] = $vval;
														  }
														}
													}
													$saved_checkoutvals = get_CheckoutValues_puregusto($address);
													if($ecom_siteid==105)
													{ 
														$str_spl_arr = array(); 
														if($_REQUEST['pret']==1) // case if coming back from PAYPAL with token.
														{
															if($_REQUEST['token'])
															{
																if($address['SHIPTONAME']!='')
																{
																	$str_spl_arr = explode(' ',$address['SHIPTONAME']);
																	$saved_checkoutvals['checkout_fname'] = $str_spl_arr[0];
																	$saved_checkoutvals['checkout_surname'] = $str_spl_arr[1];
																	$saved_checkoutvals['checkout_billing_same'] = "N";
																	$replace_chckarr = true;							
																}						
															}
														}
													}
									?>	
                                                    <tr id="checkout_billing_tr" <?php /*style="display:none"*/?>>
                                                        <td colspan="2" align="right" valign="middle" class="tda">
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
                                                                                        <td align="left" class="td">
                                                                                        <?php echo stripslash_normal($row_checkout['field_name']); if($row_checkout['field_req']==1) { echo '<span class="redtext">*</span>';}?>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="left" class="td">
                                                                                        <?php
                                                                                        $class_array['txtarea_cls'] = 'regiinput';
                                                                                            echo get_Field_puregusto($row_checkout['field_key'],$saved_checkoutvals,'','',$class_array,$disable_field);
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
											}
											//else
											{
											        // Get the list of billing address static fields to be shown in the checkout out page in required order
                                                         // print_r($cartData['customer']);
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
																				$field_name_get = $this->get_Field_name($row_checkout['field_key'],'',$cartData['customer'],'',$cust_id);
																				
                                                                                ?>  
                                                                                <input type="hidden" id="billingsame_<?php echo $row_checkout['field_key']; ?>"  name="billingsame_<?php echo $row_checkout['field_key']; ?>" value="<?php echo $field_name_get; ?>" />
                                                                            <?php
                                                                            }
                                                                        }
                                                                           
											}
											// Including the file to show the dynamic fields for checkout to the bottom of static fields in same section as that of static fields
											$cur_pos 				= 'BottomInStatic';
											$section_typ			= 'checkout'; 
											$formname 			= 'frm_checkout';
											$head_class  			= 'tdbgry';
											$cellspacing			= 1;
											$cont_class 			= 'regiconent'; 
											$texttd_class			= '';
											$cellpadding			 = 3;		
											$cont_leftwidth 		= '50%'; 
											$cont_rightwidth 	= '50%';
											$colspan 	 			= 6;
											$specialhead_tag_start 	= '<span class="reg_header"><span>';
											$specialhead_tag_end 	= '</span></span>';
											include 'show_dynamic_fields.php';
											$head_class  			= '';
											
									 // check whether billing and shipping address can be different in the site.
										          $disabled ="";
                                                  $selboxname = "checkout_billing_same";
												  if ($Settings_arr['same_billing_shipping_checkout']==0)
												  { 
													  $displaynone ="display:none;";
													  if($disable_field)
													  {
														  
													   $saved_checkoutvals['checkout_billing_same'] = "N";
													   $disabled = "disabled"
													   ?>
													   <input type="hidden" name="checkout_billing_same" id="checkout_billing_same" value="N" />

													   <?php
													   $selboxname = "checkout_billing_samed";
													   $displaynone ="display:''";
													  }
													 
													   if($_REQUEST['checkout_billing_same']=='')
														{
														  $billing_same = $saved_checkoutvals['checkout_billing_same'];
														}
														else
														{
														  $billing_same = $_REQUEST['checkout_billing_same'];
														}
														
												  ?>
                                                  <tr><td align="left" valign="middle" class="td" colspan="2">
														<?php echo stripslash_normal($Captions_arr['CART']['CART_IS_DELIVERY_ADDRESS_SAME'])?></td></tr>
													<tr>
                                                       <td align="left" valign="middle" class="td" colspan="2">
														<select name="<?php echo $selboxname ?>" id="<?php echo $selboxname ?>" onchange="handle_billingaddress_change('delivery',document.frm_checkout,0)" <?php echo $disabled ?> class="yselect" >
														<option value="Y" <?php echo ($billing_same=='Y')?'selected':''?>>Yes</option>
														<option value="N" <?php echo ($billing_same=='N')?'selected':''?>>No</option>
														</select>
														</td>
                                                        
													</tr>	
                                                    <?php
													//if(($billing_same=='N')) 
													{?> 
													<tr id="checkout_delivery_tr" style="<?php echo$displaynone ?>" >
														<td colspan="2" align="left" valign="middle" >
														<table width="100%" cellpadding="1" cellspacing="1" border="0">
														<tr>
														<td colspan="2" align="left" class="tdbgry"><?php echo stripslash_normal($Captions_arr['CART']['CART_FILL_DELIVERY_ADDRESS'])?></td>
														</tr>	
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
																	<td align="left" class="td">
																	<?php echo stripslash_normal($row_checkout['field_name']); if($row_checkout['field_req']==1) { echo '<span class="redtext">*</span>';}?>
																	</td>
																	</tr>
                                                                    <tr>
																	<td align="left" class="td">
																	<?php
																	$class_array['txtarea_cls'] = 'regiinput';
																	echo get_Field_puregusto($row_checkout['field_key'],$saved_checkoutvals,$cartData,'',$class_array,$disable_field);
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
													}	
													else // case if shipping address 
													{
												?>
														<input type="hidden" name="checkout_billing_same" id="checkout_billing_same" value="Y" />
												<?php
													}
													// Including the file to show the dynamic fields for checkout to the bottom of static fields
													$colspan 				= 2;
													$cur_pos 				= 'Bottom';
													$section_typ			= 'checkout'; 
													$formname 			= 'frm_checkout';
													$head_class  			= 'tdbgry';
													$checkout_caption	= 'Checkout';
													$cont_leftwidth 		= '50%';
													$cont_rightwidth		= '50%';
													$cellspacing 			= 1;
													$cont_class 			= 'regiconent'; 
													$texttd_class			= 'regi_txtfeild';
													$specialhead_tag_start 	= '<span class="reg_header"><span>';
													$specialhead_tag_end 	= '</span></span>';				
													$cellpadding 			= 1;	
													$table_class			='checkout_det_table';
													include 'show_dynamic_fields.php';
													if($show_cart_password==1)
													{
											 ?>	
														<tr>
															<td colspan="2" align="left" valign="middle" class="shoppingcartcontent">
																<?=$Captions_arr['CART']['SHOW_PWD_CART_MESS']?>
															</td>
														</tr>
														<tr>
														<td colspan="2" align="left" valign="middle" class="shoppingcartcontent">
														<table width="100%" cellpadding="1" cellspacing="1" border="0">
														<tr>
														<td align="left" valign="middle" class="td"><?=$Captions_arr['CART']['PASS_PASSWORD']?></td></tr>
														<tr>
														<td align="left" valign="middle" class="td"><input name="checkout_passwd" type="password" class="regiinput" id="checkout_passwd" size="20" value="" /></td>
														</tr>	
														<tr>
														<td align="left" valign="middle" class="td"><?=$Captions_arr['CART']['CONF_PASS_PASSWORD']?></td></tr>
														<tr>
														<td align="left" valign="middle" class="td"><input name="checkout_pwd_cnf" type="password" class="regiinput" id="checkout_pwd_cnf" size="20" value="" /></td>
														</tr>	
														</table>
														</td>
														</tr>	
											<?php
													}
													 
													?>
													
									 </tbody></table>
									</td>
                                    </tr>
                                    <tr>
									<td align="left" valign="top" class="tda" >
									<?php
                                    if (count($cartData['products'])==0) // Done to show the message if no products in cart
                                    {
                                    ?>
                                    <table class="gtable" border="0" cellpadding="0" cellspacing="0" width="100%">
                                    <tbody><tr><td colspan="2" class="td">
                                        <?php echo stripslash_normal($Captions_arr['CART']['CART_NO_PRODUCTS'])?>
                                    </td>
                                    </tr>	
                                    </tbody>
                                    </table>
                                    <?php
                                    }
                                    else
                                    {   
                                    $prod_cart_arr =array();
                                    $cur_indx = 0;
                                    // Following section iterate through the products in cart and show its list
                                    foreach ($cartData['products'] as $products_arr)
                                      { 
                                       $prod_cart_arr[] = $products_arr['product_id']; 
                                        if($cur_indx%2==0)
                                            $cur_class  ='shoppingcart_pdt1';
                                        else
                                            $cur_class = 'shoppingcart_pdt2';
                                        $cur_indx++;
                                        
                                        $vars_exists = false;
                                        if ($products_arr['prod_vars'] or $products_arr['prod_msgs'])  // Check whether variable of messages exists
                                        {
                                            $vars_exists 	= true;
                                            
                                        }
                                        // Handling the case of product deposit
                                        if($products_arr['product_deposit'])
                                        {
                                            if ($products_arr['final_price']>$products_arr['product_deposit_less'])
                                                $str_reduceval	+= $products_arr['product_deposit_less'];
                                        }
                                    ?>
                                     <table class="gtable" border="0" cellpadding="0" cellspacing="0" width="100%">
                                      <tbody><tr>
                                        <td colspan="2" class="tdbgry">&nbsp;<a href="<?php echo url_product($products_arr['product_id'],$products_arr['product_name'])?>" title="<?php echo $products_arr['product_name']?>" class="cart_pdt_link"><?php echo $products_arr['product_name']?></a></td>
                                      </tr>
                                      <?php
                                      if ($vars_exists) 
									{
									?>
									<tr>
                                        <td colspan="2" class="td">
														<?
														// show the variables for the product if any
														if ($products_arr['prod_vars']) 
														{
															//print_r($products_arr['prod_vars']);
															foreach($products_arr["prod_vars"] as $productVars)
															{
																if (trim($productVars['var_value'])!='')
																	print "<div class='nblack'><span class='cartvariable'>".stripslashes($productVars['var_name']).": ". stripslashes($productVars['var_value'])."</span></div><br />"; 
																else
																	print "<div class='nblack'><span class='cartvariable'>".stripslashes($productVars['var_name'])."</span></div><br />"; 
																	
															}	
														}
														// Show the product messages if any
														if ($products_arr['prod_msgs']) 
														{	
															foreach($products_arr["prod_msgs"] as $productMsgs)
															{
																print "<div class='nblack'><span class='cartvariable'>".stripslashes($productMsgs['message_title']).": ". stripslashes($productMsgs['message_value'])."</span></div><br />"; 
															}	
														}
														?>
														</td>
		  												</tr>
														<?php	
														}	
														?>
                                                      <tr>
                                                        <td class="tdbgry"><?php echo stripslash_normal($Captions_arr['CART']['CART_QTY'])?>:<?php 
                                                                                                    if($products_arr['product_det_qty_type']=='DROP')
                                                                                                    {
                                                                                                        if (trim($products_arr['product_det_qty_drop_prefix'])!='')
                                                                                                        echo stripslash_normal($products_arr['product_det_qty_drop_prefix']).' ';
                                                                                                        echo $products_arr['cart_qty'];
                                                                                                        if (trim($products_arr['product_det_qty_drop_suffix'])!='')
                                                                                                        echo ' '.stripslash_normal($products_arr['product_det_qty_drop_suffix']);
                                                                                                    }
                                                                                                    else
                                                                                                    {
                                                                                                        echo "&nbsp;&nbsp;".$products_arr["cart_qty"];
                                                                                                    }
                                                                                                    ?>	</td>
                                                        <td class="tdbgry"><span class="fbig"><?php echo print_price($products_arr['final_price'],true)?><span></span></span></td>
                                                      </tr>
													</tbody></table>
													<?php
                                                }
                                                ?>
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="gtable">
                                                <tr>
                                                <td align="left" valign="middle" class="td"><?php echo stripslash_normal($Captions_arr['CART']['CART_TOTPRICE'])?>&nbsp;</td>
                                                <td align="right" valign="middle" class="td"><span class="black"><?php echo print_price($cartData["totals"]["subtotal"],true)?></span></td>
                                                </tr>
                                                <tr>
                                                <td colspan="2">
                                                <div class="checkout_seperation_td">
                                                </div>
                                                </td>
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
                                                            <td  align="left" valign="middle" class="td"><?php echo stripslash_normal($Captions_arr['CART']['CART_GIFTWRAP_REQ'])?> (<?php echo print_price($row_gift['giftwrap_minprice'],true)?>)</td>
                                                            <td  align="right" valign="middle" class="td"><span class="black"><?php if ($row_cartdet['giftwrap_req']==1) echo 'Yes'?></span></td>
                                                        </tr>
                                                    <?php
                                                        if($row_cartdet['giftwrap_msg_req']==1)
                                                        {
                                                    ?>
                                                        <tr>
                                                            <td  align="left" valign="middle" class="td"><?php echo stripslash_normal($Captions_arr['CART']['CART_GIFTWRAP_MSG'])?>(<?php echo print_price($row_gift['giftwrap_messageprice'],true)?>)</td>
                                                            <td  align="right" valign="middle" class="td"><span class="black"><?php echo stripslash_normal($Captions_arr['CART']['CART_YES'])?></span></td>
                                                        </tr>
                                                        <tr>
                                                            <td  align="left" valign="middle" class="td"><?php echo stripslash_normal($Captions_arr['CART']['CART_MSG'])?></td>
                                                            <td  align="right" valign="middle" class="td"><span class="black"><?php echo nl2br(stripslash_normal($row_cartdet['giftwrap_msg']))?></span></td>
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
                                                            <td align="left" valign="middle" class="td"><?php echo stripslash_normal($Captions_arr['CART']['CART_RIBBON'])?>(<?php echo print_price($row_ribbon['ribbon_extraprice'],true)?>)</td>
                                                            <td align="right" valign="middle" class="td"><span class="black"><?php echo stripslash_normal($row_ribbon['ribbon_name'])?></span></td>
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
                                                            <td  align="left" valign="middle" class="td"><?php echo stripslash_normal($Captions_arr['CART']['CART_PAPER'])?>(<?php echo print_price($row_paper['paper_extraprice'],true)?>)</td>
                                                            <td  align="right" valign="middle" class="td"><span class="black"><?php echo stripslash_normal($row_paper['paper_name'])?></span></td>
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
                                                            <td align="left" valign="middle" class="td"><?php echo stripslash_normal($Captions_arr['CART']['CART_CARDS'])?>(<?php echo print_price($row_card['card_extraprice'],true)?>)</td>
                                                            <td align="right" valign="middle" class="td"><span class="black"><?php echo stripslash_normal($row_card['card_name'])?></span></td>
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
                                                            <td align="left" valign="middle" class="td"><?php echo stripslash_normal($Captions_arr['CART']['CART_BOWS'])?>(<?php echo print_price($row_bow['bow_extraprice'],true)?>)</td>
                                                            <td align="right" valign="middle" class="td"><span class="black"><?php echo stripslash_normal($row_bow['bow_name'])?></span></td>
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
                                                    <td align="left" valign="middle" class="td"><?php echo stripslash_normal($Captions_arr['CART']['CART_GIFTWRAP_TOTAL'])?></td>
                                                    <td align="right" valign="middle" class="td"><span class="black"><?php echo print_price($cartData["totals"]["giftwrap"],true)?></span></td>
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
                                                                <td  align="left" valign="middle" class="td"><?php echo stripslash_normal($Captions_arr['CART']['CART_DELIVERY_LOC'])?></td>
                                                                <td  align="right" valign="middle" class="td"><span class="black"><?php echo stripslash_normal($row_loc['location_name'])?></span></td>
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
                                                                <td  align="left" valign="middle" class="td"><?php echo stripslash_normal($Captions_arr['CART']['CART_DELIVERY_OPT'])?></td>
                                                                <td  align="right" valign="middle" class="td"><span class="black"><?php echo stripslash_normal($row_grp['delivery_group_name'])?></span></td>
                                                            </tr>
                                                <?php
                                                        }
                                                    }	
                                                if ($row_cartdet['split_delivery']==1)
                                                {
                                                ?>
                                                    <tr>
                                                        <td  align="left" valign="middle" class="td"><?php echo stripslash_normal($Captions_arr['CART']['CART_DELIVERY_SPLIT_ACTIVATED'])?></td>
                                                        <td  align="right" valign="middle" class="td"><span class="black"><?php echo stripslash_normal($Captions_arr['CART']['CART_YES'])?></span></td>
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
                                                            <td class="td"  align="left" valign="middle">
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
                                                            <td class="td" align="right" valign="middle"><span class="black">
                                                            <? print print_price($cartData["delivery"]["group".$i]["cost"],true)?>
                                                            </span></td>
                                                        </tr>
                                                    <?
                                                    }				
                                                } 
                                                // Check whether delivery is charged, then show the total after applying delivery charge
                                                if($cartData["totals"]["delivery"])
                                                {
                                                ?>
                                                  <tr>
                                                    <td align="left" valign="middle" class="td"><?php echo stripslash_normal($Captions_arr['CART']['CART_TOTAL_DELIVERY_CHARGES'])?></td>
                                                    <td align="right" valign="middle" class="td"><span class="black"><?php echo print_price($cartData["totals"]["delivery"],true)?></span></td>
                                                  </tr>
                                                <?php
                                                }
                                                }
                                                // Section to show the extra shipping cost
                                                if($cartData["totals"]["extraShipping"])
                                                {
                                                ?>
                                                 <tr>
                                                    <td align="left" valign="middle" class="td"><?php echo stripslash_normal($Captions_arr['CART']['CART_EXTRA_SHIPPING'])?></td>
                                                    <td align="right" valign="middle" class="td"><span class="black"><?php echo print_price($cartData["totals"]["extraShipping"],true)?></span></td>
                                                  </tr>
                                                <?php	
                                                }
                                                // Section to show the tax details
                                                if($cartData["totals"]["tax"])
                                                {
                                                ?>
                                                    <tr>
                                                        <td align="left" valign="middle" class="td"><div align="">
                                                        <?php echo stripslash_normal($Captions_arr['CART']['CART_TAX_CHARGE_APPLIED'])?>
                                                        <?	
                                                            foreach($cartData["tax"] as $tax)
                                                            {
                                                                echo '<br/>('.$tax['tax_name']; ?> @ <? print $tax['tax_val']; ?>%)
                                                        <?	
                                                            }
                                                        ?></div>						</td>
                                                        <td align="right" valign="middle" class="td"><span class="black">
                                                        <?	
                                                            echo print_price($cartData["totals"]["tax"],true);
                                                        ?>					</span>	</td>
                                                    </tr>
                                                <?php
                                                }
                                                
                                                // If gift voucher or promotional code is valid
                                                if (($row_cartdet['promotionalcode_id']!=0 or $row_cartdet['voucher_id']!=0))
                                                {
                                                 ?>
                                                  <tr>
                                                    <td align="left" valign="middle" class="td">
                                                    <?php 
                                                    if ($row_cartdet['promotionalcode_id']!=0 and $cartData["bonus"]['type']=='promotional' and $cartData['totals']['pro_type']!='product')
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
                                                    <td align="right" valign="middle" class="td"><span class="black">
                                                    <?php 
                                                    if ($row_cartdet['promotionalcode_id']!=0 and $cartData["bonus"]['type']=='promotional' and $cartData['totals']['pro_type']!='product')
                                                        echo '(-) '.print_price($cartData['totals']['lessval'],true);
                                                    elseif($row_cartdet['voucher_id']!=0 and $cartData["bonus"]['type']=='voucher')
                                                        echo '(-) '.print_price($cartData['totals']['lessval'],true);
                                                    ?></span></td>
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
                                                        <td align="left" valign="top" class="td"><?php echo stripslash_normal($Captions_arr['CART']['CART_SPENT_BONUS_POINTS_VALUE'])?> </td>
                                                        <td align="right" valign="top" class="td"><span class="black">(-) <? echo print_price($cartData["bonus"]["value"],true);?></span></td>
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
                                                    <td  align="left" valign="middle" class="td"><?php echo stripslash_normal($Captions_arr['CART']['CART_TOTAL_POINTS_EARN'])?>&nbsp;</td>
                                                    <td  align="right" valign="middle" class="td">: <span class="black"><?php echo $cartData["totals"]["bonus"]?></span></td>
                                                  </tr>
                                                <?php	
                                                }
                                                // show the total final price
                                                if($cartData["totals"]["bonus_price"]>0)
                                                {
                                                ?>
                                                
                                                <tr>
                                                <td  align="left" valign="middle" class="td_final"><?php echo stripslash_normal($Captions_arr['CART']['CART_TOTAL_FINAL_COST'])?>&nbsp; </td>
                                                <td align="right" valign="middle" class="td_final"><span class="black"><?php echo print_price($cartData["totals"]["bonus_price"],true)?></span></td>
                                                </tr>
                                                <tr>
                                                <td colspan="2" align="right" valign="middle" class="cart_totalA">&nbsp;</td>
                                                </tr>
                                                <?php
                                                }
                                                $rem_val = $cartData["totals"]["bonus_price"] - $str_reduceval;
                                                if($rem_val >0 and $str_reduceval>0)
                                                {
                                                ?>
                                                <tr>
                                                    <td  align="left" valign="middle" class="td"><?php echo stripslash_normal($Captions_arr['CART']['CART_LESS_AMT_LATER'])?>&nbsp; </td>
                                                    <td align="right" valign="middle" class="td"><span class="black"><?php echo print_price($str_reduceval,true)?></span></td>
                                                </tr>
                                                <tr>
                                                    <td  align="left" valign="middle" class="td"><?php echo stripslash_normal($Captions_arr['CART']['CART_TOTAL_AMT_NOW'])?>&nbsp; </td>
                                                    <td align="right" valign="middle" class="td"><span class="black"><?php echo print_price($rem_val,true)?></span>
                                                    <input type="hidden" name="store_reduceval" value="<?php echo $str_reduceval?>" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                <td colspan="2" align="right" valign="middle" class="tdbgry">&nbsp;</td>
                                                </tr>
                                                <?php
                                                }		
                                                ?>
                                                </table>
												<?
                                                if($cartData["payment"]["method"]['paymethod_takecarddetails']==1 || $cartData["payment"]["method"]['paymethod_key']=='ABLE2BUY')
                                                {
                                                $class_table = 'gtable';
                                                }
                                                else
                                                {
                                                $class_table = 'gtableA';
                                                }
                                                ?>
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="<?=$class_table?>">
                                                <?php
                                                // Check whether credit card details is to be taken or not
                                                if($cartData["payment"]["method"]['paymethod_takecarddetails']==1)
                                                {
                                                $checkout_caption = 'Checkout';
                                                ?>
                                                <tr>
                                                <td  class="tdbgry" valign="top"><?php echo stripslash_normal($Captions_arr['CART']['CART_CREDIT_CARD_DETAILS'])?></td>
                                                </tr>
                                                <tr>
                                                <td  class="tda" valign="top">
                                                <table width="100%" border="0" cellpadding="1" cellspacing="1" class="gtable">
                                                    <tbody>
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
                                                                <td class="tdbdr"  align="left" valign="top">
                                                                <?php echo stripslash_normal($row_checkout['field_name']); if($row_checkout['field_req']==1) { echo '<span class="redtext">*</span>';}?>
                                                            </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="tdbdr"  align="left" valign="top">
                                                                <?php
                                                                $class_array['txtarea_cls'] = 'input';
                                                                echo get_Field($row_checkout['field_key'],$saved_checkoutvals,$cartData,'',$class_array);
                                                                ?>
                                                            </td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    }
                                                ?>	
                                                </tbody></table>    
                                                </td>
                                                </tr>
                                                <?
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
                                                <td  align="center" valign="middle" class="red_msg">
                                                <br/><br/> --'.stripslash_normal($Captions_arr['CART']['CART_NOT_ABLE_TO_BUY']).'-- <br/><br/>
                                                </td>
                                                </tr> ';
                                                exit;
                                                }
                                                else
                                                {					
                                                ?>
                                                <tr>
                                                <td  class="tdbgry" valign="top"><?php echo stripslash_normal($Captions_arr['CART']['CART_ABLE_TO_BUY'])?></td>
                                                </tr>
                                                <tr>
                                                <td class="tdbgry" align="left" valign="middle">
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="gtable">
                                                    <?php	
                                                    $checked = 'checked="checked"'; // to keep the first option selected always by default
                                                    while ($row_able = $db->fetch_array($ret_able))
                                                    {
                                                        ?>
                                                        <tr>
                                                            <td class="tdbdr" width="30%" align="left">
                                                                <input type="radio" name="cgid" value="<?php echo $row_able['det_code']?>" <?php echo $checked?>>
                                                            </td>
                                                            <td width="70%" align="left" class="td">
                                                                <?php echo stripslash_normal($row_able['det_caption'])?></td>
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
                                                <td  class="tdbgry" valign="middle"><?php echo stripslash_normal($Captions_arr['CART']['CART_CHEQUE_DETAILS'])?></td>
                                                </tr>
                                                <tr>
                                                <td align="left" valign="middle" class="tdbgry">	
                                                <table width="100%" cellpadding="1" cellspacing="1" border="0" class="gtable">
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
                                                        <td class="tdbdr" align="left" valign="top">
                                                            <?php echo stripslash_normal($row_checkout['field_name']); if($row_checkout['field_req']==1) { echo '<span class="redtext">*</span>';}?>
                                                        
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="tdbdr" align="left" valign="top">
                                                        <?php
                                                            $class_array['txtarea_cls'] = 'regiinput';
                                                            echo get_Field($row_checkout['field_key'],$saved_checkoutvals,$cartData,'',$class_array);
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
                                                if($ecom_siteid!=84)
                                                $checkout_caption = 'Place Invoice';
                                                else
                                                $checkout_caption = 'Confirm Purchase';
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
                                                <td  align="right" valign="middle" class="cartterms">			
                                                <?php
                                                if($Settings_arr['terms_and_condition_at_checkout'])
                                                {
                                                ?>
                                               <input class="shoppingcart_radio" type="checkbox" name="cart_terms" id="cart_terms" value="1" /><a href="<?php echo $ecom_selfhttp.$ecom_hostname; ?>/https://www.puregusto.co.uk/terms-of-business-pg50174.html" class="terms_link"><?php echo stripslash_normal($Captions_arr['CART']['CART_ACCEPT_TERMS_CONDITIONS'])?></a>
                                                <?php	
                                                }
                                                ?>	
                                                </td>
                                                </tr>
                                                <tr>
                                                <td align="right" valign="top" class="td">
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
                                                        <img src="<?php echo url_site_image('paypal_checkout_logo.gif')?>" border="0" /><?php
                                                        $checkout_caption	= "Make Payment";
                                                    }
                                                ?>
                                                <div class="chk_paymnt_btn"><div> <input name="continue_checkout" type="submit" class="byellow_det" id="continue_checkout" value="<?php echo $checkout_caption?>"/></div></div>
                                                <?php
                                                }
                                                else
                                                {
                                                ?>
                                                    <div style="float:right"><input type="image" name="continue_checkout" src="https://checkout.google.com/buttons/checkout.gif?merchant_id=711690661192356&amp;w=160&amp;h=43&amp;style=white&amp;variant=text&amp;loc=en_GB" id="continue_checkout" border="0" style="border:0" /></div>
                                                <?php	
                                                }
                                                ?>
                                                
                                                <?php
                                                }
                                                else
                                                {
                                                
                                                echo "<div class='cart_checkout_div' align='right'><span class='red_msg'>".stripslash_normal($Captions_arr['CART']['CART_LOGIN_CHECKOUT'])."</span></div>";
                                                }
                                                ?>
                                                <div class="cart_top_links">
                                                <div class="chk_back_btn"><div><a href="#" class="cart_blink_checkout" onclick="<?php /*?>show_wait_button(this,'Please Wait...');<?php */?>gobackto_cart('<?php echo ($protectedUrl)? base64_encode($ecom_hostname):$ecom_hostname?>')"><?php echo stripslash_normal($Captions_arr['CART']['CART_BACK_CARTPAGE'])?></a></div></div> 
                                                </div>
                                                </td>
                                                </tr>
                                                <input type="hidden" name="pass_url" id="pass_url" value="<?php echo $_REQUEST['pass_url']?>"/>
                                                <div id='checkout_msg_div' align="center" style="width:100%; display:none">
                                                <span class="red_msg">
                                                <?php echo stripslash_normal($Captions_arr['CART']['CART_PLEASE_WAIT'])?>
                                                </span>
                                                </div>
                                                <?php
                                                if(file_exists($image_path.'/site_images/checkout_special_inner_bottom.gif'))
                                                {
                                                ?>
                                                <tr>
                                                <td  valign="top" align="right">
                                                <div class="cart_bottom_html"><img src="<?php url_site_image('checkout_special_inner_bottom.gif')?>"></div>
                                                </td>
                                                </tr>
                                                <?php
                                                }
                                                ?>
                                                </table>
												<?php
                                            }
                                             ?>
                                        </td>
                                    </tr>
							</table>
							</form>
						
		<script type="text/javascript">
		function validate_checkout_fields(frm)
		{
			if(frm.checkout_account_same.value=='N')
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
			}
			else
			{
					fieldRequired 		= Array();
					fieldDescription 	= Array();
					fieldEmail 		    = Array();
					fieldConfirm 		= Array();;
					fieldConfirmDesc 	= Array();;
					fieldNumeric 		= Array();
			}
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
				if(document.getElementById('checkout_fname'))
						{ 
							  if(frm.checkout_fname.value.length>20)
							  {
								alert('First Name should not contain more than 20 characters');
								frm.checkout_fname.focus();
								return false;
							  }
							  
							   var myStr = frm.checkout_fname.value;
							   if(!/^[A-Za-z\s\-\&0-9]+$/.test(myStr)) {
								  alert('Illegal characters found for the First Name');
								 frm.checkout_fname.focus();
								 return false;
								}
								
						}
						if(document.getElementById('checkout_surname'))
						{ 
							  if(frm.checkout_surname.value.length>20)
							  {
								alert('SurName should not contain more than 20 characters');
								frm.checkout_surname.focus();
								return false;
							  }
							  
							   var myStr1 = frm.checkout_surname.value;
							   if(!/^[A-Za-z\s\-\&0-9]+$/.test(myStr1)) {
								  alert('Illegal characters found for the SurName');
								 frm.checkout_surname.focus();
								 return false;
								}
								
						}
						if(document.getElementById('checkout_comp_name'))
						{ 
							if(frm.checkout_comp_name.value.length>0)
							  {
								 							  
								   var myStr2 = frm.checkout_comp_name.value;
								   if(!/^[A-Za-z\s\-\&0-9]+$/.test(myStr2)) {
									  alert('Illegal characters found for the Company Name');
									 frm.checkout_comp_name.focus();
									 return false;
									}
							}
								
						}
						if(document.getElementById('checkout_fax'))
						{ 
							  if(frm.checkout_fax.value.length>20)
							  {
								alert('Fax number should not contain more than 20 characters');
								frm.checkout_fax.focus();
								return false;
							  }
							   myStr = frm.checkout_fax.value;
							   if( myStr.match(/[\<\>!@#\$%^&\*,]+/i) ) {
								alert('Illegal characters found for the Fax number');
								frm.checkout_fax.focus();
								return false;
								}
						}
						if(document.getElementById('checkout_phone'))
						{ 
							  if(frm.checkout_phone.value.length>20)
							  {
								alert('Phone Number should not contain more than 20 characters');
								frm.checkout_phone.focus();
								return false;
							  }
							  
							   var myStr2 = frm.checkout_phone.value;
							   if(!/^[A-Za-z\s\-\+\(\)0-9]+$/.test(myStr2)) {
								  alert('Illegal characters found for the phone number');
								 frm.checkout_phone.focus();
								 return false;
								}
								
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
				if(frm.checkout_account_same.value=='N')
				{
					<?php
				// case if special tax calculation is activated -- Started
				if($ecom_site_delivery_location_country_map==1)
				{
					if($row_cartdet['location_id']) // case if delivery location is selected
					{
						echo 'loc_country_arr = new Array();
							  var check_country_id = 0;';
						// Get the list of countries mapped under current location
						$sql_countrylist = "SELECT general_settings_site_country_country_id  
												FROM 
													general_settings_site_country_location_map  
												WHERE 
													delivery_site_location_location_id=".$row_cartdet['location_id']." 
													AND sites_site_id = $ecom_siteid ";
						$ret_countrylist = $db->query($sql_countrylist);
						if($db->num_rows($ret_countrylist))
						{
							$cntry_indx = 0;
							while ($row_countrylist = $db->fetch_array($ret_countrylist))
							{
								echo "loc_country_arr[$cntry_indx]='".$row_countrylist['general_settings_site_country_country_id']."';";
								$cntry_indx++;
							}
													
							echo '
									if(document.frm_checkout.checkout_billing_same.value=="Y")
									{
										check_country_id = document.frm_checkout.checkout_country.value;
									}
									else
									{
										check_country_id = document.frm_checkout.checkoutdelivery_country.value;
									}
									var match_found = false;
									for(i=0;i<loc_country_arr.length;i++)
									{
										if(loc_country_arr[i]==check_country_id)
											match_found=true;
									}	
									if(match_found==false)
									{
										alert("Country selected in Delivery Address does not match with the region selected in cart page.\n\n Please select the correct country or go back to cart page to view the list of countries in the selected region.");
										return false;	
									}
							';
						}
					}
				}	
				// case if special tax calculation is activated -- Ended
				?>
				}
				if(document.getElementById('save_checkoutdetails'))
					document.getElementById('save_checkoutdetails').value  	= 1;
				document.getElementById('cart_mod').value 					= 'show_orderplace_preview';	
				/*document.getElementById('continue_checkout').style.display	= 'none';*/
				if (document.getElementById('continue_checkout'))
					document.getElementById('continue_checkout').value = 'Please wait..';
				if (document.getElementById('backto_cart'))	
					document.getElementById('backto_cart').style.display		= 'none';
				document.getElementById('checkout_msg_div').style.display	= 'block';
				return true;
			}	
			else
				return false;
		}	
		 /*window.onload = handle_billingaddress_change('onload','',<?php echo ($cust_id)?$cust_id:0?>);*/
		if(document.frm_checkout.checkout_billing_same.value=='N')
		{
			handle_billingaddress_change('delivery',document.frm_checkout,0);
		}										
		</script>	
		 <?php	
		 if($replace_chckarr == true)
		 {
			echo "<script type=\"text/javascript\">
			fill_delivery_asbilling('document.frm_checkout');

			</script>";
		}	
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
		
		$HTML_treemenu = '<div class="tree_menu_conB">
							  <div class="tree_menu_topB"></div>
							  <div class="tree_menu_midB">
								<div class="tree_menu_content">
								  <ul class="tree_menu">
								<li><a href="'.url_link('',1).'" title="'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a> | </li>
								 <li>'.stripslash_normal($Captions_arr['CART']['CART_CHECKOUTPREVIEWHEADING']).'</li>
								</ul>
								  </div>
							  </div>
							  <div class="tree_menu_bottomB"></div>
							</div>';
			echo $HTML_treemenu;	
		?>	
		<?php /*?><form method="post" name="frm_checkout"  id="frm_checkout" class="frm_cls" action="<?php echo $http?>" onsubmit="return validate_checkout_fields(document.frm_checkout)">
		<input type="hidden" name="fpurpose" id="fpurpose" value="place_order_preview" />
		<input type="hidden" name="remcart_id" id="remcart_id" value="" />
		<input type="hidden" name="cart_mod" id="cart_mod" value="show_checkout" />
		<input type="hidden" name="hold_section" id="hold_section" value="" />
		<input type="hidden" name="save_checkoutdetails" id="save_checkoutdetails" value="" />
		<input type="hidden" name="pret" value="<?php echo $_REQUEST['pret'];?>"/>
		<input type="hidden" name="token" value="<?php echo $_REQUEST['token'];?>"/>
		<input type="hidden" name="payer_id" value="<?php echo $_REQUEST['payer_id'];?>"/><?php */?>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="ctable">
		<tr>
		<td colspan="6" class="curvea_top">
		<div class="white"><?php echo stripslash_normal($Captions_arr['CART']['CART_CHECKOUTPREVIEWHEADING'])?></div>
		</td>
		</tr>
		<tr>
		<td class="top_checkout_backcart">
		<div class="chk_back_btn">
		<div><a href="#" class="cart_blink_checkout" onClick="show_wait_button(this,'Please Wait...');gobackto_cart('<?php echo ($protectedUrl)? base64_encode($ecom_hostname):$ecom_hostname?>')"><?php echo stripslash_normal($Captions_arr['CART']['CART_BACK_CARTPAGE'])?></a></div>
		<?php /*?><div><input class="orderpreview_backtocart" type="button" onclick="show_wait_button(this,'Please Wait...');gobackto_cart('<?php echo ($protectedUrl)? base64_encode($ecom_hostname):$ecom_hostname?>')" value="<?php echo stripslash_normal($Captions_arr['CART']['CART_BACK_CARTPAGE'])?>" /></div><?php */?>
		</div>  
		</td>
		</tr>
		<?php /*?><tr>
		<td  colspan="2" align="right" valign="middle" class="shoppingcartcontent">	
		<?php
			$display_option = 'ALL';
			// Including the file which hold the login for fields to be passed to payment gateway
			include 'order_preview_gateway_include.php';
		?>		 
		</td>
		</tr><?php */?>
		<?php
		if($auto_submit==false)
		{
		?>
		<tr>
		<td align="left" valign="top" class="td">
		
		<table width="100%" border="0" cellpadding="1" cellspacing="1" class="gtable">
		<tbody> 
		<?php
		$cur_pos 		= 'Top';
		$show_header	= 1;
		$table_class			='checkout_det_table';
		$head_class  			= 'tdbgry';
		$cont_class             = 'regiconent';
		include 'show_dynamic_fields_orders.php';
		$table_class			='';
		?>
		<tr>
		<td colspan="2" class="tdbgry">Billing Address Details<?php //echo stripslash_normal($Captions_arr['CART']['CART_FILL_BILLING_DETAILS'])?></td>
		</tr>
		
		<?php
		// Including the file to show the dynamic fields for orders to the give position of static fields
		$cur_pos 		= 'TopInStatic';
		$show_header	= 0;
		$table_class			='';
		$head_class  			= 'tdbgry';
		$cont_class             = 'regiconent';
		include 'show_dynamic_fields_orders.php';
		
		?>	
		<tr>
		<td colspan="2" align="right" valign="middle">
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
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
					<td align="left" width="50%" class="regiconent">
					<?php 
							echo stripslash_normal($row_checkout['field_name'])
					?>
					</td>
					<td align="left" width="50%" class="regi_txtfeild">
					<?php
							echo stripslash_normal($row_ord[$row_checkout['field_orgname']]);
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
		$head_class  			= 'tdbgry';
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
		<td colspan="2" class="tdbgry"><?php echo stripslash_normal($Captions_arr['CART']['DELIVERY_ADDRESS'])?></td>
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
		?>
				<tr>
				<td align="left" width="50%" class="regiconent">
				<?php echo stripslash_normal($row_checkout['field_name'])?>
				</td>
				<td align="left" width="50%" class="regi_txtfeild">
				<?php
					echo stripslash_normal($row_del[$row_checkout['field_orgname']]);
				?>
				</td>
				</tr>
		<?php
		}
		}	
		?>
        </tbody>
		</table>
		</td>
		</tr>		
		<?php
		}	
		// Including the file to show the dynamic fields for orders to the give position of static fields
		$cur_pos 		= 'Bottom';
		$show_header	= 1;
		$head_class  			= 'tdbgry';
		$table_class			='checkout_det_table';
		$cont_class             = 'regiconent';
		include 'show_dynamic_fields_orders.php';
		?>
		
		</tbody></table>
		</td>
        </tr>
        <tr>
		<td align="right" valign="top"class="td">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="gtable">
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
		<tr>
			<td colspan="2" class="tdbgry">&nbsp;<a href="<?php echo url_product($row_orddet['products_product_id'],$row_orddet['product_name'])?>" title="<?php echo $row_orddet['product_name']?>" class="cart_pdt_link"><?php echo $row_orddet['product_name']?></a></td>
		  </tr>
          <?php
        // If variables exists for current product, show it in the following section
		if ($vars_exists) 
		{
		?>
		<tr>
		<td align="left" valign="middle"  class="<?php echo $cur_class?>">
		<?
		
		// show the variables for the product if any
		if ($db->num_rows($ret_vars))
		{
		//print_r($products_arr['prod_vars']);
		while($row_vars = $db->fetch_array($ret_vars))
		{
		if (trim($row_vars['var_value'])!='')
		print "<span class='cartvariable'>".stripslashes($row_vars['var_name']).": ". stripslashes($row_vars['var_value'])."</span><br />"; 
		else
		print "<span class='cartvariable'>".stripslashes($row_vars['var_name'])."</span><br />"; 
		
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
		?>
		</td>
		</tr>	
		<?php
		}
		?>
        <tr>
			<td class="tdbgry"><?php echo stripslash_normal($Captions_arr['CART']['CART_QTY'])?><?php 
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
														?>	</td>
			<td class="tdbgry"><span class="fbig"><?php echo print_price($row_orddet['order_rowtotal'],true)?></span></td>
		  </tr>
		<?php
		}
		?>	
		<tr class="cart_table">
		<td colspan="2" align="right" valign="top" >
		<table width="100%" border="0" cellspacing="0" cellpadding="0" >
		<tr>
		<td align="right" valign="middle" class="cart_td_normal"><?php echo stripslash_normal($Captions_arr['CART']['CART_TOTPRICE'])?>&nbsp;</td>
		<td align="right" valign="middle" class="cart_td_normal"><span class="black"><?php echo print_price($row_ord["order_subtotal"],true)?></span></td>
		</tr>
		 <?php
		if($row_ord['order_giftwrap']=='Y') // Check whether giftwrap module exists for the current site
		{
		// Show the following if giftwrap option is set for the site
		?>	
		<tr>
		<td  align="right" valign="middle" class="cart_td_normal"><?php echo stripslash_normal($Captions_arr['CART']['CART_GIFTWRAP_REQ'])?> (<?php echo print_price($row_ord['order_giftwrap_minprice'],true)?>)</td>
		<td  align="right" valign="middle" class="cart_td_normal"><span class="black"><?php if ($row_ord['order_giftwrap']=='Y') echo 'Yes'?></span></td>
		</tr>
		<?php
		if($row_ord['order_giftwrapmessage']=='Y')
		{
		?>
		<tr>
		<td align="right" valign="middle" class="cart_td_normal"><?php echo stripslash_normal($Captions_arr['CART']['CART_GIFTWRAP_MSG'])?>(<?php echo print_price($row_ord['order_giftwrap_message_charge'],true)?>)</td>
		<td align="right" valign="middle" class="cart_td_normal"><span class="black">Yes</span></td>
		</tr>
		<tr>
		<td align="right" valign="middle" class="cart_td_normal"><?php echo stripslash_normal($Captions_arr['CART']['CART_MSG'])?></td>
		<td align="right" valign="middle" class="cart_td_normal"><span class="black"><?php echo nl2br(stripslash_normal($row_ord['order_giftwrapmessage_text']))?></span></td>
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
		<td align="right" valign="middle" class="cart_td_normal">
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
		echo $gift_caption?>(<?php echo print_price($row_giftwrap_det['giftwrap_price'],true)?>)</td>
		<td align="right" valign="middle" class="cart_td_normal"><span class="black"><?php echo stripslash_normal($row_giftwrap_det['giftwrap_name'])?></span></td>
		</tr>
		<?php		
		}
		?>
		<tr>
		<td align="right" valign="middle" class="cart_td_normal"><?php echo stripslash_normal($Captions_arr['CART']['CART_GIFTWRAP_TOTAL'])?></td>
		<td align="right" valign="middle" class="cart_td_normal"><span class="black"><?php echo print_price($row_ord["order_giftwraptotal"],true)?></span></td>
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
		<td align="right" valign="middle" class="cart_td_normal"><?php echo stripslash_normal($Captions_arr['CART']['CART_DELIVERY_LOC'])?></td>
		<td align="right" valign="middle" class="cart_td_normal"><span class="black"><?php echo stripslash_normal($row_ord['order_deliverylocation'])?></span></td>
		</tr>
		<?php
		}
		// Check whether any delivery group exists for the site
		if ($row_ord['order_delivery_option'])
		{
		?>	
		<tr>
		<td align="right" valign="middle" class="cart_td_normal"><?php echo stripslash_normal($Captions_arr['CART']['CART_DELIVERY_OPT'])?></td>
		<td align="right" valign="middle" class="cart_td_normal"><span class="black"><?php echo stripslash_normal($row_ord['order_delivery_option'])?></span></td>
		</tr>
		<?php
		}	
		if ($row_ord['order_splitdeliveryreq']==1)
		{
		?>
		<tr>
		<td align="right" valign="middle" class="cart_td_normal"><?php echo stripslash_normal($Captions_arr['CART']['CART_DELIVERY_SPLIT_ACTIVATED'])?></td>
		<td align="right" valign="middle" class="cart_td_normal"><span class="black"><?php echo stripslash_normal($Captions_arr['CART']['CART_YES'])?></span></td>
		</tr>
		<?php	
		}
		// Check whether delivery is charged, then show the total after applying delivery charge
		if($row_ord["order_deliverytotal"]>0)
		{
		?>
		<tr>
		<td align="right" valign="middle" class="cart_td_normal"><?php echo stripslash_normal($Captions_arr['CART']['CART_TOTAL_DELIVERY_CHARGES'])?></td>
		<td align="right" valign="middle" class="cart_td_normal"><span class="black"><?php echo print_price($row_ord["order_deliverytotal"],true)?></span></td>
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
		<td align="right" valign="middle" class="cart_td_normal"><span class="black"><?php echo print_price($row_ord["order_extrashipping"],true)?></span></td>
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
		<td align="right" valign="middle" class="td">
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
		<td align="right" valign="middle" class="td"><span class="black">
		<?	
		for($i=0;$i<count($charge_arr);$i++)
		{
		echo $charge_arr[$i]?> <br />
		<?	
		}
		?>
		</span>
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
		<span class="black">
		<?php 
		echo print_price($row_ord['order_customer_discount_value'],true);
		?></span>
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
		<td align="right" valign="middle" class="cart_td_normal"><span class="black">
		<?php 
		echo $prom_lessval;
		?></span>
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
		<td align="right" valign="middle" class="cart_td_normal"><span class="black">(-) <? echo print_price($row_ord['order_bonuspoint_discount'],true);?></span></td>
		</tr>
		<?php	
		}
		}
		// Bonus Points Earned
		if ($row_ord["order_bonuspoint_inorder"]>0)
		{
		?>
		<tr>
		<td align="right" valign="middle" class="cart_td_normal"><?php echo stripslash_normal($Captions_arr['CART']['CART_TOTAL_POINTS_EARN'])?>&nbsp;</td>
		<td align="LEFT" valign="middle" class="cart_td_normal"><span class="black">: <?php echo $row_ord["order_bonuspoint_inorder"]?></span></td>
		</tr>
		<?php	
		}
		// show the total final price
		if ($row_ord['order_totalprice']>0)
		{
		?>
		
		<tr>
		<td  align="right" valign="middle" class="td_final"><?php echo stripslash_normal($Captions_arr['CART']['CART_TOTAL_FINAL_COST'])?>&nbsp; </td>
		<td align="right" valign="middle" class="td_final"><span class="black"><?php echo print_price($row_ord['order_totalprice'],true)?></span></td>
		</tr>
		<?php
		}
		$rem_val = $row_ord['order_totalprice'] - $row_ord['order_deposit_amt'];
		if($rem_val>0 and $row_ord['order_deposit_amt']>0)
		{
		?>
		<tr>
		<td  align="right" valign="middle" class="cart_totalA"><?php echo stripslash_normal($Captions_arr['CART']['CART_LESS_AMT_LATER'])?>&nbsp; </td>
		<td align="right" valign="middle" class="cart_totalA"><span class="black"><?php echo print_price($row_ord['order_deposit_amt'],true)?></span></td>
		</tr>
		<tr>
		<td align="right" valign="middle" class="cart_totalA"><?php echo stripslash_normal($Captions_arr['CART']['CART_TOTAL_AMT_NOW'])?>&nbsp; </td>
		<td align="right" valign="middle" class="cart_totalA"><span class="black"><?php echo print_price($rem_val,true)?></span>
		</td>
		</tr>
		<?php
		}		
		?>
		<tr>
		<td  align="right" valign="middle" class="cart_td_normal" colspan="2">	
		<?php
		//$display_option = 'BUTTON_ONLY';
		//$display_option = 'ALL';
		//// Including the file which hold the login for fields to be passed to payment gateway
		//include 'order_preview_gateway_include.php';
		?>			
		</table>
		</td>
		</tr>
		
		</table>
		</td>
		
		</tr>
		<?
		}
		?>
		<tr>
		<td colspan="6" align="right" valign="middle" class="shoppingcartcontent">	
		<?php
		//$display_option = 'BUTTON_ONLY';
		$display_option = 'ALL';
		// Including the file which hold the login for fields to be passed to payment gateway
		include 'order_preview_gateway_include.php';
		?>			 
		</td>
		</tr>
<?php /*?>		<tr>
		<td colspan="6" align="right" valign="top" class="mainmiddle_carttdA">
		<div class="cart_bottom_links">  <img src="site_images/cartr-ssl.gif" /></div>   
		</td>
		</tr>
		<tr>
		<td colspan="6" align="right" valign="top" class="mainmiddle_carttdA">
		<div class="cart_bottom_html">  <img src="site_images/33.gif" /></div>   
		</td>
		</tr><?php */?>
		</table>
		<?php /*?></form><?php */?>
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
					
					
					$sql_ord_det = "SELECT * FROM order_details WHERE orders_order_id = $order_id ORDER BY orderdet_id ASC";
					$ret_ord_det = $db->query($sql_ord_det);
					if($db->num_rows($ret_ord_det))
					{
						$basket_details = '';
						while ($row_ord_det = $db->fetch_array($ret_ord_det))
						{
							if($basket_details!='')
							{
								$basket_details .=',';
							}
							$basket_details .= "{ productname: '".stripslashes($row_ord_det['product_name'])."',
												  productid: '".stripslashes($row_ord_det['products_product_id'])."',
												  quantity: '".stripslashes($row_ord_det['order_orgqty'])."',
									              price: '".stripslashes($row_ord_det['product_soldprice'])."'
												}";
						}
						?>
						<script type="text/javascript">
						var _kkstrack = {
						merchantInfo : [{ country:"uk", merchantId:"16213013" }],
						orderValue: '<?=$total_price?>',
						orderId: '<?php echo $order_id?>',
						basket:  [<?php echo $basket_details?>]
						};
						(function() {
						var s = document.createElement('script');
						s.type = 'text/javascript';
						s.async = true;
						s.src = 'https://s.kk-resources.com/ks.js';
						var x = document.getElementsByTagName('script')[0];
						x.parentNode.insertBefore(s, x);
						})();
						</script>
						<?php
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
			/*
			if(trim($ecom_success_script)!='' and $protectedUrl==false)
			{
				$succ_script = trim($ecom_success_script);
				$succ_script = str_replace('[TOTAL_PRICE]',$total_price,trim($succ_script));
				echo stripslash_normal($succ_script);
			}
			*/ 		
			/*
				##################################################################################
				End Google Purchase Conversion Recording section
				##################################################################################
			*/
			
			
			// Clearing the cart details if checkout is successfull
			$sess_id = session_id();
			clear_Cart($sess_id);
			
			$Captions_arr['CART'] 	= getCaptions('CART'); // Getting the captions to be used in this page
			
			$HTML_treemenu = '<div class="tree_menu_conB">
							  <div class="tree_menu_topB"></div>
							  <div class="tree_menu_midB">
								<div class="tree_menu_content">
								  <ul class="tree_menu">
								<li><a href="'.url_link('',1).'" title="'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a> | </li>
								 <li>'.stripslash_normal($Captions_arr['CART']['CART_CHECKOUT_SUCCESS_TITLE']).'</li>
								</ul>
								  </div>
							  </div>
							  <div class="tree_menu_bottomB"></div>
							</div>';
			echo $HTML_treemenu;	
		?>
			 
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="shoppingcarttable">
			  <tr>
				<td align="left" valign="middle"></td>
			  </tr>
			  <tr>
			  	<td align="left" class="regicontentA_message">
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
		<?php	
		}
		/* Function to show the checkout failed message*/
		function Show_CheckoutFailed()
		{
			global $db,$ecom_hostname,$Captions_arr,$ecom_siteid,$Captions_arr;
			
			if(check_IndividualSslActive())
			{
				$ecom_selfhttp = "https://";
			}
			else
			{
				$ecom_selfhttp = "http://";
			}
			
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
			$HTML_treemenu = '<div class="tree_menu_conB">
							  <div class="tree_menu_topB"></div>
							  <div class="tree_menu_midB">
								<div class="tree_menu_content">
								  <ul class="tree_menu">
								<li><a href="'.url_link('',1).'" title="'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a> | </li>
								 <li>'.stripslash_normal($Captions_arr['CART']['CART_CHECKOUT_FAILED_TITLE']).'</li>
								</ul>
								  </div>
							  </div>
							  <div class="tree_menu_bottomB"></div>
							</div>';
			echo $HTML_treemenu;	
		?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="shoppingcarttable">
			<tr>
				<td align="left" valign="middle"></td>
			</tr>
			<?php
				if($msg)
				{
			?>
				<tr>
					<td align="left" class="regicontentA_message">
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
		<?php	
		}
		/* Function to show the checkout failed message*/
		function Show_NoChexCommonSuccess()
		{
			global $db,$ecom_hostname,$Captions_arr,$ecom_siteid,$Captions_arr;
			
			if(check_IndividualSslActive())
			{
				$ecom_selfhttp = "https://";
			}
			else
			{
				$ecom_selfhttp = "http://";
			}
			
			$sess_id = session_id();
			
			$Captions_arr['CART'] 	= getCaptions('CART'); // Getting the captions to be used in this page
			
			$HTML_treemenu = '<div class="tree_menu_conB">
							  <div class="tree_menu_topB"></div>
							  <div class="tree_menu_midB">
								<div class="tree_menu_content">
								  <ul class="tree_menu">
								<li><a href="'.url_link('',1).'" title="'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a> | </li>
								 <li>'.stripslash_normal($Captions_arr['CART']['CART_NOCHEXCHECKOUT_SUCCESS']).'</li>
								</ul>
								  </div>
							  </div>
							  <div class="tree_menu_bottomB"></div>
							</div>';
			echo $HTML_treemenu;
		?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="shoppingcarttable">
			<tr>
				<td align="left" valign="middle"></td>
			</tr>
			
					<tr>
				<td align="center" class="regicontentA_message">
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
			
			if(check_IndividualSslActive())
			{
				$ecom_selfhttp = "https://";
			}
			else
			{
				$ecom_selfhttp = "http://";
			}
			
			$Captions_arr['CART']	= getCaptions('CART');
		?>
			<div  class="carterrordiv_alert" id="instockmsg_div">
			<div class="carterrordiv_head">
			<?php echo stripslash_normal($Captions_arr['CART']['CART_ERROR_HEADING'])?>.
			</div>

			<div align="right" class="carterrormsg_span" ><a href="javascript: hide_instockmsg_div()"><img src="<?php url_site_image('closeq.png')?>" border="0" /></a></div>
			<span class="carterrormsg_msg">
			<?php echo $msg?></span>
			</div>
		<?php	
		}
		function showpromotional_error_msg($msg)
		{
			global $Captions_arr;
			
			if(check_IndividualSslActive())
			{
				$ecom_selfhttp = "https://";
			}
			else
			{
				$ecom_selfhttp = "http://";
			}
			
			$Captions_arr['CART']	= getCaptions('CART');
		?>
			<div  class="carterrordiv_alert" id="instockmsg_div">
			<div class="carterrordiv_head">
			<?php echo stripslash_normal($Captions_arr['CART']['CART_ERROR_HEADING'])?>.
			</div>

			<div align="right" class="carterrormsg_span" ><a href="#" onclick="hide_instockmsg_div();document.frm_cart.cart_savepromotional.value=1;handle_form_submit(document.frm_cart,'save_commondetails','#a_prom')"><img src="<?php url_site_image('closeq.png')?>" border="0" /></a></div>
			<span class="carterrormsg_msg">
			<?php echo $msg?></span>
			</div>
		<?php	
		}
		// Function to return the static fields to be used in the checkout page
		function get_Field_name($key='',$saved_checkoutvals = array(),$customer_arr,$cur_form='',$cust_id='')
		{ 
			global $db,$ecom_siteid,$ecom_is_country_textbox,$Settings_arr;
			
			if(check_IndividualSslActive())
			{
				$ecom_selfhttp = "https://";
			}
			else
			{
				$ecom_selfhttp = "http://";
			}
			
			// If not values exists for checkout fields for current site in current session, then check whether customer is logged in
			// If logged in then show the default values for billing address
			//if (count($saved_checkoutvals)==0)
			{
				// Check whether logged in
				if($cust_id)
				{
					if($cur_form=='frm_buygiftvoucher') // case of // gift voucher buy section
					{
						$sql_cust = "SELECT customer_title,customer_compname,customer_fname,customer_mname
											customer_surname,customer_buildingname,customer_streetname,
											customer_towncity,customer_statecounty,country_id,
											customer_postcode,customer_phone,customer_mobile,
											customer_fax,customer_email_7503 
										FROM
											customers
										WHERE
											customer_id =".get_session_var('ecom_login_customer')."
										LIMIT
											1";
						$ret_cust = $db->query($sql_cust);
						if ($db->num_rows($ret_cust))
							$row_cust = $db->fetch_array($ret_cust);
		
						// Set the values to be shown for voucher fields
						$saved_checkoutvals['checkout_vouchertitle'] 			= $row_cust['customer_title'];
						$saved_checkoutvals['checkout_vouchercomp_name'] 		= $row_cust['customer_compname'];
						$saved_checkoutvals['checkout_voucherfname'] 			= $row_cust['customer_fname'];
						$saved_checkoutvals['checkout_vouchermname'] 			= $row_cust['customer_mname'];
						$saved_checkoutvals['checkout_vouchersurname'] 			= $row_cust['customer_surname'];
						$saved_checkoutvals['checkout_voucherbuilding']			= $row_cust['customer_buildingname'];
						$saved_checkoutvals['checkout_voucherstreet'] 			= $row_cust['customer_streetname'];
						$saved_checkoutvals['checkout_vouchercity'] 			= $row_cust['customer_towncity'];
						$saved_checkoutvals['checkout_voucherstate'] 			= $row_cust['customer_statecounty'];
						$saved_checkoutvals['checkout_country'] 				= $row_cust['country_id'];
						$saved_checkoutvals['checkout_voucherzipcode'] 			= $row_cust['customer_postcode'];
						$saved_checkoutvals['checkout_voucherphone'] 			= $row_cust['customer_phone'];
						$saved_checkoutvals['checkout_vouchermobile'] 			= $row_cust['customer_mobile'];
						$saved_checkoutvals['checkout_voucherfax'] 				= $row_cust['customer_fax'];
						$saved_checkoutvals['checkout_voucheremail'] 			= $row_cust['customer_email_7503'];
					}
					else
					{
						// Set the values to be shown for billing address fields
						$saved_checkoutvals['checkout_title'] 				= $customer_arr['customer_title'];
						$saved_checkoutvals['checkout_comp_name'] 			= $customer_arr['customer_compname'];
						$saved_checkoutvals['checkout_fname'] 				= $customer_arr['customer_fname'];
						$saved_checkoutvals['checkout_mname'] 				= $customer_arr['customer_mname'];
						$saved_checkoutvals['checkout_surname'] 			= $customer_arr['customer_surname'];
						$saved_checkoutvals['checkout_building']			= $customer_arr['customer_buildingname'];
						$saved_checkoutvals['checkout_street'] 				= $customer_arr['customer_streetname'];
						$saved_checkoutvals['checkout_city'] 				= $customer_arr['customer_towncity'];
						$saved_checkoutvals['checkout_state'] 				= $customer_arr['customer_statecounty'];
						$saved_checkoutvals['checkout_country'] 			= $customer_arr['country_id'];
						$saved_checkoutvals['checkout_zipcode'] 			= $customer_arr['customer_postcode'];
						$saved_checkoutvals['checkout_phone'] 				= $customer_arr['customer_phone'];
						$saved_checkoutvals['checkout_mobile'] 				= $customer_arr['customer_mobile'];
						$saved_checkoutvals['checkout_fax'] 				= $customer_arr['customer_fax'];
						$saved_checkoutvals['checkout_email'] 				= $customer_arr['customer_email_7503'];
					}
		
					// Get the name of state
					/*if ($saved_checkoutvals['checkout_state']!=0)
					{
		
						$sql_state = "SELECT state_name
										FROM
											general_settings_site_state
										WHERE
											state_id=".$saved_checkoutvals['checkout_state']."
											AND sites_site_id = $ecom_siteid
										LIMIT
											1";
						$ret_state = $db->query($sql_state);
						if ($db->num_rows($ret_state))
						{
							$row_state = $db->fetch_array($ret_state);
							$saved_checkoutvals['checkout_state'] = stripslashes($row_state['state_name']);
						}
						else
							$saved_checkoutvals['checkout_state'] = '';
					}
					else
						$saved_checkoutvals['checkout_state'] = '';*/
		
					// Get the name of country
					/*if ($saved_checkoutvals['checkout_country']!=0)
					{
		
						$sql_country = "SELECT country_name
										FROM
											general_settings_site_country
										WHERE
											country_id=".$saved_checkoutvals['checkout_country']."
											AND sites_site_id = $ecom_siteid
										LIMIT
											1";
						$ret_country = $db->query($sql_country);
						if ($db->num_rows($ret_country))
						{
							$row_country = $db->fetch_array($ret_country);
							$saved_checkoutvals['checkout_country'] = stripslashes($row_country['country_name']);
						}
						else
						 $saved_checkoutvals['checkout_country']='';
					}
					else
						$saved_checkoutvals['checkout_country'] = '';
						
						*/
		
				}
			}
			// Deciding which is the field to be displayed
			return $saved_checkoutvals[$key];
			}
	};	
	// Function to return the static fields to be used in the checkout page
function get_Field_puregusto($key='',$saved_checkoutvals,$customer_arr,$cur_form='',$class_array=array(),$disable_field=false)
{
	global $db,$ecom_siteid,$ecom_is_country_textbox,$Settings_arr,$ecom_load_mobile_theme;
	global $ecom_selfhttp;
	if($ecom_load_mobile_theme)
		$box_size = 20;
	else
		$box_size = 30;
		if($disable_field == true)
		{
		  $disable_field = "readonly='readonly' style='background-color:#EBEBE4;'";
		  $disable_select = "disabled";
		}
		else
		{
		  $disable_field = "";
		  $disable_select = ""; 	
		}
	// If not values exists for checkout fields for current site in current session, then check whether customer is logged in
	// If logged in then show the default values for billing address
	if (count($saved_checkoutvals)==0)
	{
		// Check whether logged in
		if(get_session_var('ecom_login_customer'))
		{
			if($cur_form=='frm_buygiftvoucher') // case of // gift voucher buy section
			{
				$sql_cust = "SELECT customer_title,customer_compname,customer_fname,customer_mname
									customer_surname,customer_buildingname,customer_streetname,
									customer_towncity,customer_statecounty,country_id,
									customer_postcode,customer_phone,customer_mobile,
									customer_fax,customer_email_7503 
								FROM
									customers
								WHERE
									customer_id =".get_session_var('ecom_login_customer')."
								LIMIT
									1";
				$ret_cust = $db->query($sql_cust);
				if ($db->num_rows($ret_cust))
					$row_cust = $db->fetch_array($ret_cust);

				// Set the values to be shown for voucher fields
				$saved_checkoutvals['checkout_vouchertitle'] 			= $row_cust['customer_title'];
				$saved_checkoutvals['checkout_vouchercomp_name'] 		= $row_cust['customer_compname'];
				$saved_checkoutvals['checkout_voucherfname'] 			= $row_cust['customer_fname'];
				$saved_checkoutvals['checkout_vouchermname'] 			= $row_cust['customer_mname'];
				$saved_checkoutvals['checkout_vouchersurname'] 			= $row_cust['customer_surname'];
				$saved_checkoutvals['checkout_voucherbuilding']			= $row_cust['customer_buildingname'];
				$saved_checkoutvals['checkout_voucherstreet'] 			= $row_cust['customer_streetname'];
				$saved_checkoutvals['checkout_vouchercity'] 			= $row_cust['customer_towncity'];
				$saved_checkoutvals['checkout_voucherstate'] 			= $row_cust['customer_statecounty'];
				$saved_checkoutvals['checkout_country'] 				= $row_cust['country_id'];
				$saved_checkoutvals['checkout_voucherzipcode'] 			= $row_cust['customer_postcode'];
				$saved_checkoutvals['checkout_voucherphone'] 			= $row_cust['customer_phone'];
				$saved_checkoutvals['checkout_vouchermobile'] 			= $row_cust['customer_mobile'];
				$saved_checkoutvals['checkout_voucherfax'] 				= $row_cust['customer_fax'];
				$saved_checkoutvals['checkout_voucheremail'] 			= $row_cust['customer_email_7503'];
			}
			else
			{
				// Set the values to be shown for billing address fields
				$saved_checkoutvals['checkout_title'] 				= $customer_arr['customer_title'];
				$saved_checkoutvals['checkout_comp_name'] 			= $customer_arr['customer_compname'];
				$saved_checkoutvals['checkout_fname'] 				= $customer_arr['customer_fname'];
				$saved_checkoutvals['checkout_mname'] 				= $customer_arr['customer_mname'];
				$saved_checkoutvals['checkout_surname'] 			= $customer_arr['customer_surname'];
				$saved_checkoutvals['checkout_building']			= $customer_arr['customer_buildingname'];
				$saved_checkoutvals['checkout_street'] 				= $customer_arr['customer_streetname'];
				$saved_checkoutvals['checkout_city'] 				= $customer_arr['customer_towncity'];
				$saved_checkoutvals['checkout_state'] 				= $customer_arr['customer_statecounty'];
				$saved_checkoutvals['checkout_country'] 			= $customer_arr['country_id'];
				$saved_checkoutvals['checkout_zipcode'] 			= $customer_arr['customer_postcode'];
				$saved_checkoutvals['checkout_phone'] 				= $customer_arr['customer_phone'];
				$saved_checkoutvals['checkout_mobile'] 				= $customer_arr['customer_mobile'];
				$saved_checkoutvals['checkout_fax'] 				= $customer_arr['customer_fax'];
				$saved_checkoutvals['checkout_email'] 				= $customer_arr['customer_email_7503'];
			}

			// Get the name of state
			/*if ($saved_checkoutvals['checkout_state']!=0)
			{

				$sql_state = "SELECT state_name
								FROM
									general_settings_site_state
								WHERE
									state_id=".$saved_checkoutvals['checkout_state']."
									AND sites_site_id = $ecom_siteid
								LIMIT
									1";
				$ret_state = $db->query($sql_state);
				if ($db->num_rows($ret_state))
				{
					$row_state = $db->fetch_array($ret_state);
					$saved_checkoutvals['checkout_state'] = stripslashes($row_state['state_name']);
				}
				else
					$saved_checkoutvals['checkout_state'] = '';
			}
			else
				$saved_checkoutvals['checkout_state'] = '';*/

			// Get the name of country
			/*if ($saved_checkoutvals['checkout_country']!=0)
			{

				$sql_country = "SELECT country_name
								FROM
									general_settings_site_country
								WHERE
									country_id=".$saved_checkoutvals['checkout_country']."
									AND sites_site_id = $ecom_siteid
								LIMIT
									1";
				$ret_country = $db->query($sql_country);
				if ($db->num_rows($ret_country))
				{
					$row_country = $db->fetch_array($ret_country);
					$saved_checkoutvals['checkout_country'] = stripslashes($row_country['country_name']);
				}
				else
				 $saved_checkoutvals['checkout_country']='';
			}
			else
				$saved_checkoutvals['checkout_country'] = '';
				
				*/

		}
	}
	$txt_cls 		= ($class_array['txtbox_cls'])?'class="'.$class_array['txtbox_cls'].'"':'';
	$txtarea_cls 	= ($class_array['txtarea_cls'])?'class="'.$class_array['txtarea_cls'].'"':'';
	$select_cls 	= ($class_array['select_cls'])?'class="'.$class_array['select_cls'].'"':'';
	
	$txt_onblur 		= ($class_array['onblur'])?$class_array['onblur']:'';

	// Deciding which is the field to be displayed
	switch($key)
	{
		case 'checkout_title':
		case 'checkout_vouchertitle':
		case 'checkoutdelivery_title':
		case 'customer_title':
			$ret = '<select name="'.$key.'" id="'.$key.'"'.$select_cls.'>';
			$sel = ($saved_checkoutvals[$key]=='Mr.')?'selected':'';
			$ret .='<option value="Mr." '.$sel.'>Mr.</option>';
			$sel = ($saved_checkoutvals[$key]=='Mrs.')?'selected':'';
			$ret .='<option value="Mrs." '.$sel.'>Mrs.</option>';
			$sel = ($saved_checkoutvals[$key]=='Miss.')?'selected':'';
			$ret .='<option value="Miss." '.$sel.'>Miss.</option>';
			$sel = ($saved_checkoutvals[$key]=='Ms.')?'selected':'';
			$ret .='<option value="Ms." '.$sel.'>Ms.</option>';
			$sel = ($saved_checkoutvals[$key]=='M/s.')?'selected':'';
			$ret .='<option value="M/s." '.$sel.'>M/s.</option>';
			$sel = ($saved_checkoutvals[$key]=='Dr.')?'selected':'';
			$ret .='<option value="Dr." '.$sel.'>Dr.</option>';
			$sel = ($saved_checkoutvals[$key]=='Sir.')?'selected':'';
			$ret .='<option value="Sir." '.$sel.'>Sir.</option>';
			$sel = ($saved_checkoutvals[$key]=='Rev.')?'selected':'';
			$ret .='<option value="Rev." '.$sel.'>Rev.</option>';
			$ret .='</select>';
		break;
		case 'checkout_country':
			if($ecom_is_country_textbox!=1)
			{
				// get the list of countries to be displayed here
				$sql_country = "SELECT country_id,country_name,country_order 	
									FROM 
										general_settings_site_country 
									WHERE 
										sites_site_id = $ecom_siteid 
										AND country_hide=1  
									ORDER BY 
										country_order DESC, country_name ASC";
				$ret_country = $db->query($sql_country);
				$ret = '<select name="checkout_country" id="checkout_country" '.$select_cls.'>';
				$ret .= '<option value="">- Select -</option>';
				if($db->num_rows($ret_country))
				{
					if(!$saved_checkoutvals['checkout_country'])
						$saved_checkoutvals['checkout_country'] = $Settings_arr['default_country_id'];
					$special_exists = false;
					$display_seperator = false;
					while ($row_country = $db->fetch_array($ret_country))
					{
						if($row_country['country_id']==$saved_checkoutvals['checkout_country'])
							$checked = ' selected="selected"';
						else
							$checked = '';
						
						if($row_country['country_order']!=0)
						{
							$special_exists = true;
						}
						if($special_exists and !$display_seperator and $row_country['country_order']==0)
						{
								$ret.= '<optgroup label="== == == == == == == == == =="></optgroup>';
								$display_seperator = true;
						}	
						
						$ret.= '<option value="'.$row_country['country_id'].'"'.$checked.'>'.stripslashes($row_country['country_name']).'</option>';
						
					}					
				}
				$ret .= '</select>';		

			}
			elseif($ecom_is_country_textbox==1)
			{
				$ret = '<input type="text" name="'.$key.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" size="'.$box_size.'" '.$txt_cls.'/>';
			}
		break;
		case 'checkoutdelivery_country':
			if($ecom_is_country_textbox!=1)
			{
				// get the list of countries to be displayed here
				$sql_country = "SELECT country_id,country_name,country_order  	
									FROM 
										general_settings_site_country 
									WHERE 
										sites_site_id = $ecom_siteid 
										AND country_hide=1  
									ORDER BY 
										country_order DESC, country_name ASC";
				$ret_country = $db->query($sql_country);
				$ret = '<select name="checkoutdelivery_country" id="checkoutdelivery_country" '.$select_cls.'>';
				$ret .= '<option value="">- Select -</option>';
				if($db->num_rows($ret_country))
				{
					if(!trim($saved_checkoutvals['checkoutdelivery_country']))
						$saved_checkoutvals['checkoutdelivery_country'] = $Settings_arr['default_country_id'];
					$special_exists = false;
					$display_seperator = false;
					while ($row_country = $db->fetch_array($ret_country))
					{
						if($row_country['country_id']==$saved_checkoutvals['checkoutdelivery_country'])
							$checked = ' selected="selected"';
						else
							$checked = '';
						
						if($row_country['country_order']!=0)
						{
							$special_exists = true;
						}
						if($special_exists and !$display_seperator and $row_country['country_order']==0)
						{
								$ret.= '<optgroup label="== == == == == == == == == =="></optgroup>';
								$display_seperator = true;
						}
						if($saved_checkoutvals['checkoutdelivery_country']!=0 AND ($disable_field == true))
						{	
						$ret .= '<option value="'.$row_country['country_id'].'"'.$checked.'   '.$disable_select.'>'.stripslashes($row_country['country_name']).'</option>';
						}
						else
						{
						$ret .= '<option value="'.$row_country['country_id'].'"'.$checked.'>'.stripslashes($row_country['country_name']).'</option>';
						}
					}					
				}
				$ret .= '</select>';
				if($saved_checkoutvals['checkoutdelivery_country']!=0 AND ($disable_field == true))
				{
				$ret .= '<input type="hidden" name="checkoutdelivery_country" value="'.$saved_checkoutvals['checkoutdelivery_country'].'" />';
				}
			}	
			elseif($ecom_is_country_textbox==1)
			{
				$ret = '<input type="text" name="'.$key.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" size="'.$box_size.'" '.$txt_cls.'/>';
			}
		break;
		case 'checkout_vouchercountry':
			if($ecom_is_country_textbox!=1)
			{
				// get the list of countries to be displayed here
				$sql_country = "SELECT country_id,country_name 	
									FROM 
										general_settings_site_country 
									WHERE 
										sites_site_id = $ecom_siteid 
										AND country_hide=1  
									ORDER BY 
										country_name";
				$ret_country = $db->query($sql_country);
				$ret = '<select name="checkout_vouchercountry" id="checkout_vouchercountry">';
				$ret .= '<option value="">- Select -</option>';
				if($db->num_rows($ret_country))
				{
					if(!$saved_checkoutvals['checkout_vouchercountry'])
						$saved_checkoutvals['checkout_vouchercountry'] = $Settings_arr['default_country_id'];
					while ($row_country = $db->fetch_array($ret_country))
					{
						if($row_country['country_id']==$saved_checkoutvals['checkout_vouchercountry'])
							$checked = ' selected="selected"';
						else
							$checked = '';
						$ret.= '<option value="'.$row_country['country_id'].'"'.$checked.'>'.stripslashes($row_country['country_name']).'</option>';
					}					
				}
				$ret .= '</select>';
			}
			elseif($ecom_is_country_textbox==1)
			{
				$ret = '<input type="text" name="'.$key.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" size="'.$box_size.'" '.$txt_cls.'/>';
			}
		break;
		case 'cbo_country':
			if($ecom_is_country_textbox!=1)
			{
				// get the list of countries to be displayed here
				$sql_country = "SELECT country_id,country_name 	
									FROM 
										general_settings_site_country 
									WHERE 
										sites_site_id = $ecom_siteid 
										AND country_hide=1  
									ORDER BY 
										country_name";
				$ret_country = $db->query($sql_country);
				$ret = '<select name="cbo_country" id="cbo_country">';
				$ret .= '<option value="">- Select -</option>';
				if($db->num_rows($ret_country))
				{
					if(!$saved_checkoutvals['cbo_country'])
						$saved_checkoutvals['cbo_country'] = $Settings_arr['default_country_id'];
					while ($row_country = $db->fetch_array($ret_country))
					{
						if($row_country['country_id']==$saved_checkoutvals['cbo_country'])
							$checked = ' selected="selected"';
						else
							$checked = '';
						$ret.= '<option value="'.$row_country['country_id'].'"'.$checked.'>'.stripslashes($row_country['country_name']).'</option>';
					}					
				}
				$ret .= '</select>';
			}
			elseif($ecom_is_country_textbox==1)
			{
				$ret = '<input type="text" name="'.$key.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" size="'.$box_size.'" '.$txt_cls.'/>';
			}
		break;
		


		case 'checkoutdelivery_fname':
		case 'checkoutdelivery_mname':
		case 'checkoutdelivery_surname':
			if(trim($saved_checkoutvals[$key])!='')
			{
				$saved_checkoutvals[$key] = substr($saved_checkoutvals[$key],0,20);
			}
			if($saved_checkoutvals[$key]!='' AND $disable_field == true)
			{
			$ret = '<input type="text" name="'.$key.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" size="'.$box_size.'" maxlength="20" '.$txt_cls.' '.$txt_onblur.' '.$disable_field.'/>';
			}
			else
			{
			$ret = '<input type="text" name="'.$key.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" size="'.$box_size.'" maxlength="20" '.$txt_cls.' '.$txt_onblur.'/>';
			}
		break;
		case 'checkoutdelivery_comp_name':
			if(trim($saved_checkoutvals[$key])!='')
			{
				$saved_checkoutvals[$key] = substr($saved_checkoutvals[$key],0,40);
			}
			if($saved_checkoutvals[$key]!='' AND $disable_field == true)
			{
			$ret = '<input type="text" name="'.$key.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" size="'.$box_size.'" maxlength="40" '.$txt_cls.' '.$txt_onblur.' '.$disable_field.'/>';
			}
			else
			{
			$ret = '<input type="text" name="'.$key.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" size="'.$box_size.'" maxlength="40" '.$txt_cls.' '.$txt_onblur.'/>';
			}
		break;
		case 'checkoutdelivery_building':
		case 'checkoutdelivery_street':
			if(trim($saved_checkoutvals[$key])!='')
			{
				$saved_checkoutvals[$key] = substr($saved_checkoutvals[$key],0,48);
			}
			if($saved_checkoutvals[$key]!='' AND $disable_field == true)
			{
			$ret = '<input type="text" name="'.$key.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" size="'.$box_size.'" maxlength="48" '.$txt_cls.' '.$txt_onblur.' '.$disable_field.'/>';
			}
			else
			{
			$ret = '<input type="text" name="'.$key.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" size="'.$box_size.'" maxlength="48" '.$txt_cls.' '.$txt_onblur.'/>';
			}
		break;
		case 'checkoutdelivery_city':
			if(trim($saved_checkoutvals[$key])!='')
			{
				$saved_checkoutvals[$key] = substr($saved_checkoutvals[$key],0,40);
			}
			if($saved_checkoutvals[$key]!='' AND $disable_field == true)
			{
			$ret = '<input type="text" name="'.$key.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" size="'.$box_size.'" maxlength="40" '.$txt_cls.' '.$txt_onblur.' '.$disable_field.'/>';
			}
			else
			{
			$ret = '<input type="text" name="'.$key.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" size="'.$box_size.'" maxlength="40" '.$txt_cls.' '.$txt_onblur.'/>';
			}
		break;
		case 'checkoutdelivery_state':
			if(trim($saved_checkoutvals[$key])!='')
			{
				$saved_checkoutvals[$key] = substr($saved_checkoutvals[$key],0,40);
			}
			if($saved_checkoutvals[$key]!='' AND $disable_field == true)
			{
			$ret = '<input type="text" name="'.$key.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" size="'.$box_size.'" maxlength="40" '.$txt_cls.' '.$txt_onblur.' '.$disable_field.'/>';
			}
			else
			{
			$ret = '<input type="text" name="'.$key.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" size="'.$box_size.'" maxlength="40" '.$txt_cls.' '.$txt_onblur.'/>';
			}
		break;
		case 'checkoutdelivery_zipcode':
			if(trim($saved_checkoutvals[$key])!='')
			{
				$saved_checkoutvals[$key] = substr($saved_checkoutvals[$key],0,10);
			}
			if($saved_checkoutvals[$key]!='' AND $disable_field == true)
			{
			$ret = '<input type="text" name="'.$key.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" size="'.$box_size.'" maxlength="10" '.$txt_cls.' '.$txt_onblur.' '.$disable_field.'/>';
			}
			else
			{
			$ret = '<input type="text" name="'.$key.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" size="'.$box_size.'" maxlength="10" '.$txt_cls.' '.$txt_onblur.'/>';
			}
		break;	
		case 'checkoutdelivery_phone':
			if(trim($saved_checkoutvals[$key])!='')
			{
				$saved_checkoutvals[$key] = substr($saved_checkoutvals[$key],0,20);
			}
			if($saved_checkoutvals[$key]!='' AND $disable_field == true)
			{
			$ret = '<input type="text" name="'.$key.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" size="'.$box_size.'" maxlength="20" '.$txt_cls.' '.$txt_onblur.' '.$disable_field.'/>';
			}
			else
			{
			$ret = '<input type="text" name="'.$key.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" size="'.$box_size.'" maxlength="20" '.$txt_cls.' '.$txt_onblur.'/>';
			}
		break;
		case 'checkout_fname':
		case 'checkout_mname':
		case 'checkout_surname':
			if(trim($saved_checkoutvals[$key])!='')
			{
				$saved_checkoutvals[$key] = substr($saved_checkoutvals[$key],0,20);
			}
			$ret = '<input type="text" name="'.$key.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" size="'.$box_size.'" maxlength="20" '.$txt_cls.' '.$txt_onblur.'/>';
		break;	
		case 'checkout_comp_name':
			if(trim($saved_checkoutvals[$key])!='')
			{
				$saved_checkoutvals[$key] = substr($saved_checkoutvals[$key],0,40);
			}
			$ret = '<input type="text" name="'.$key.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" size="'.$box_size.'" maxlength="40" '.$txt_cls.' '.$txt_onblur.'/>';
		break;
		case 'checkout_building':
		case 'checkout_street':
			if(trim($saved_checkoutvals[$key])!='')
			{
				$saved_checkoutvals[$key] = substr($saved_checkoutvals[$key],0,48);
			}
			$ret = '<input type="text" name="'.$key.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" size="'.$box_size.'" maxlength="48" '.$txt_cls.' '.$txt_onblur.'/>';
		break;
		case 'checkout_city':
			if(trim($saved_checkoutvals[$key])!='')
			{
				$saved_checkoutvals[$key] = substr($saved_checkoutvals[$key],0,40);
			}
			$ret = '<input type="text" name="'.$key.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" size="'.$box_size.'" maxlength="40" '.$txt_cls.' '.$txt_onblur.'/>';
		break;
		
		case 'checkout_zipcode':
			if(trim($saved_checkoutvals[$key])!='')
			{
				$saved_checkoutvals[$key] = substr($saved_checkoutvals[$key],0,10);
			}
			$ret = '<input type="text" name="'.$key.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" size="'.$box_size.'" maxlength="10" '.$txt_cls.' '.$txt_onblur.'/>';
		break;
		case 'checkout_phone':
		case 'checkout_mobile':
			if(trim($saved_checkoutvals[$key])!='')
			{
				$saved_checkoutvals[$key] = substr($saved_checkoutvals[$key],0,20);
			}
			$ret = '<input type="text" name="'.$key.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" size="'.$box_size.'" maxlength="20" '.$txt_cls.' '.$txt_onblur.'/>';
		break;
		case 'checkout_email':
			if(trim($saved_checkoutvals[$key])!='')
			{
				$saved_checkoutvals[$key] = substr($saved_checkoutvals[$key],0,254);
			}
			$ret = '<input type="text" name="'.$key.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" size="'.$box_size.'" maxlength="254" '.$txt_cls.' '.$txt_onblur.'/>';
		break;
		
		case 'checkout_state':
			if(trim($saved_checkoutvals[$key])!='')
			{
				$saved_checkoutvals[$key] = substr($saved_checkoutvals[$key],0,40);
			}
			$ret = '<input type="text" name="'.$key.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" size="'.$box_size.'" maxlength="40" '.$txt_cls.' '.$txt_onblur.'/>';
		break;
		case 'checkout_address2':
		case 'checkout_fax':
		
		case 'checkoutdelivery_mobile':
			if(trim($saved_checkoutvals[$key])!='')
			{
				$saved_checkoutvals[$key] = substr($saved_checkoutvals[$key],0,20);
			}
			if($saved_checkoutvals[$key]!='' AND $disable_field == true)
			{
			$ret = '<input type="text" name="'.$key.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" size="'.$box_size.'" maxlength="20" '.$txt_cls.' '.$txt_onblur.' '.$disable_field.'/>';
			}
			else
			{
			$ret = '<input type="text" name="'.$key.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" size="'.$box_size.'" maxlength="20" '.$txt_cls.' '.$txt_onblur.'/>';
			}
		break;
		case 'checkoutdelivery_email':
			if(trim($saved_checkoutvals[$key])!='')
			{
				$saved_checkoutvals[$key] = substr($saved_checkoutvals[$key],0,254);
			}
			if($saved_checkoutvals[$key]!='' AND $disable_field == true)
			{
			$ret = '<input type="text" name="'.$key.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" size="'.$box_size.'" maxlength="254" '.$txt_cls.' '.$txt_onblur.' '.$disable_field.'/>';
			}
			else
			{
			$ret = '<input type="text" name="'.$key.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" size="'.$box_size.'" maxlength="254" '.$txt_cls.' '.$txt_onblur.'/>';
			}
		break;
		case 'checkout_vouchercomp_name':
		case 'checkout_voucherfname':
		case 'checkout_vouchermname':
		case 'checkout_vouchersurname':
		case 'checkout_voucherbuilding':
		case 'checkout_voucherstreet':
		case 'checkout_vouchercity':
		case 'checkout_voucherstate':
		case 'checkout_voucherzipcode':
		case 'checkout_voucherphone':
		case 'checkout_vouchermobile':
		case 'checkout_voucherfax':
		case 'checkout_voucheremail':
		

		
		case 'checkoutdelivery_address2':
		
		
		case 'checkoutdelivery_fax':
		
		
		case 'customer_fname':
		case 'customer_mname':
		case 'customer_surname':
		case 'customer_position':
		case 'customer_buildingname':
		case 'customer_streetname':
		case 'customer_towncity':
		case 'cbo_state':
		case 'customer_postcode':
		/*case 'cbo_country':*/
		case 'customer_phone':
		case 'customer_mobile':
		case 'customer_fax':
		
		case 'customer_compname':
		case 'customer_compregno':
		case 'customer_compvatregno':

		case 'checkoutpay_nameoncard':

		case 'checkoutchq_number':
		case 'checkoutchq_bankname':

		case 'checkoutpay_cardnumber':
			$ret = '<input type="text" name="'.$key.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" size="'.$box_size.'" '.$txt_cls.' '.$txt_onblur.'/>';
		break;
		case 'checkoutpay_issuenumber':
			if ($txt_cls=='')
				$txt_cls = 'class="inputissue_normal"';
			$ret = '<input type="text" name="'.$key.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" size="4" '.$txt_cls.'/>';
		break;
		case 'checkoutpay_securitycode':
			$ret = '<input type="text" name="'.$key.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" size="4" '.$txt_cls.'/>';
		break;
		case 'customer_comptype':
			$ret = '<select name="'.$key.'" id="'.$key.'" '.$select_cls.'>';
			$sql = "SELECT comptype_id,comptype_name
						FROM 
							general_settings_sites_customer_company_types 
						WHERE 
							sites_site_id=$ecom_siteid 
						AND 
							comptype_hide=0 
						ORDER BY 
							comptype_order";
			$rets = $db->query($sql);
			if ($db->num_rows($rets))
			{
				/*while ($row = $db->fetch_array($rets))
				{
					$key = $row['comptype_id'];
					$ret .= '<option value="'.$key.'">'.stripslashes($row['comptype_name']).'</option>';
				}*/
				while ($row = $db->fetch_array($rets))
				{
					$key1 = $row['comptype_id'];
					$selc='';
					if($saved_checkoutvals[$key]==$key1)
					{
						$selc = 'selected';
					}
					$ret .= '<option value="'.$key1.'" '.$selc.'>'.stripslashes($row['comptype_name']).'</option>';
				}
			}

			$ret .= '</select>';
		break;
		case 'checkoutpay_cardtype':
			/*if ($cur_form=='frm_buygiftvoucher') // case of gift voucher buying section
				$ret = '<select name="'.$key.'" id="'.$key.'" onchange="sel_credit_card_voucher(this)">';
			else
				$ret = '<select name="'.$key.'" id="'.$key.'" onchange="sel_credit_card(this)">';*/
			
			if ($cur_form=='frm_buygiftvoucher') // case of gift voucher buying section
				$ret = '<select name="'.$key.'" id="'.$key.'" '.$select_cls.'>';
			else
				$ret = '<select name="'.$key.'" id="'.$key.'" '.$select_cls.'>';	

			$ret .= "<option value=''>-- Select --</option>";
			
			$sql = "SELECT a.cardtype_key,a.cardtype_caption,a.cardtype_issuenumber_req,a.cardtype_securitycode_count,cardtype_numberofdigits,a.cardtype_paypalprokey 
					FROM
						payment_methods_supported_cards a,payment_methods_sites_supported_cards b
					WHERE
						b.sites_site_id = $ecom_siteid
						AND a.cardtype_id=b.payment_methods_supported_cards_cardtype_id
					ORDER BY
						b.supportcard_order";
			$rets = $db->query($sql);
			if ($db->num_rows($rets))
			{
				while ($row = $db->fetch_array($rets))
				{
					if($customer_arr['payment']['method']['paymethod_key']=='PAYPALPRO')
						$key = $row['cardtype_paypalprokey'];
					else
						$key = $row['cardtype_key'];
					$ret .= '<option value="'.$key.'_'.$row['cardtype_issuenumber_req'].'_'.$row['cardtype_securitycode_count'].'_'.$row['cardtype_numberofdigits'].'">'.stripslashes($row['cardtype_caption']).'</option>';
				}
			}

			$ret .= '</select>';
		break;
		case 'checkoutpay_expirydate':
			$ret = '<select name="'.$key.'_month" id="'.$key.'_month" '.$select_cls.'>';
			for($i=1;$i<=12;$i++)
			{
				if($i<10)
					$j = '0'.$i;
				else
					$j = $i;
				$ret .= "<option value='".$j."'>$j</option>";
			}
			$ret .= '</select>';
			$ret .="&nbsp;";
			$ret .= '<select name="'.$key.'_year" id="'.$key.'_year" '.$select_cls.'>';
			for($i=date('Y');$i<date('Y')+10;$i++)
			{
				if($customer_arr['payment']['method']['paymethod_key']=='PAYPALPRO' or $customer_arr['from_giftvoucher']==1)
					$ret .= "<option value='".$i."'>".substr($i,-2)."</option>";
				else
					$ret .= "<option value='".substr($i,-2)."'>".substr($i,-2)."</option>";
			}
			$ret .= '</select>';
		break;
		case 'checkoutpay_issuedate':
			$ret = '<select name="'.$key.'_month" id="'.$key.'_month" '.$select_cls.'>';
			$ret .= "<option value=''>--</option>";
			for($i=1;$i<=12;$i++)
			{
				if($i<10)
					$j = '0'.$i;
				else
					$j = $i;
				$ret .= "<option value='".$j."'>$j</option>";
			}
			$ret .= '</select>';
			$ret .="&nbsp;";
			$ret .= '<select name="'.$key.'_year" id="'.$key.'_year" '.$select_cls.'>';
			$ret .= "<option value=''>--</option>";
			for($i=date('Y');$i>date('Y')-20;$i--)
			{
				if($customer_arr['payment']['method']['paymethod_key']=='PAYPALPRO' or $customer_arr['from_giftvoucher']==1)
					$ret .= "<option value='".$i."'>".substr($i,-2)."</option>";
				else
					$ret .= "<option value='".substr($i,-2)."'>".substr($i,-2)."</option>";
			}
			$ret .= '</select>';
		break;
		case 'checkoutchq_date':
			if ($txt_cls=='')
				$txt_cls = 'class="inputissue_normal"';
			$ret = '<input type="text" name="'.$key.'" id="'.$key.'" value="" size="10" maxlength="10" '.$txt_cls.'/> (e.g. 01-01-2008)';
		break;
		case 'checkout_notes':
		case 'checkout_vouchernotes':
			$ret = '<textarea name="'.$key.'" id="'.$key.'" rows="5" cols="25" '.$txtarea_cls.'>'.$saved_checkoutvals[$key].'</textarea>';
		break;
		case 'checkoutchq_bankbranch':
			$ret = '<textarea name="'.$key.'" id="'.$key.'" rows="5" cols="25" '.$txtarea_cls.'></textarea>';
		break;
	};
	return $ret;
}
// Function to get the checkout values temporarly saved for current cart
function get_CheckoutValues_puregusto($pass_arr=array())
{
	global $db,$ecom_siteid,$ecom_is_country_textbox;
	global $ecom_selfhttp;
	$sess_id = Get_session_Id_from();
	$ret_arr = array();
	if(count($pass_arr)==0)
	{
		$sql = "SELECT checkout_fieldname,checkout_value
					FROM
						cart_checkout_values
					WHERE
						session_id='".$sess_id."'
						AND sites_site_id = $ecom_siteid";
		$ret = $db->query($sql);
		if($db->num_rows($ret))
		{
			while($row = $db->fetch_array($ret))
			{
				$ret_arr[$row['checkout_fieldname']] = stripslashes($row['checkout_value']);
			}
		}
	}
	else
	{
		if(trim($pass_arr['SHIPTONAME'])!='')
		{ 
			$shipname_arr = explode(' ',trim($pass_arr['SHIPTONAME']));
			$pass_arr['FIRSTNAME'] = $shipname_arr[0];
			$pass_arr['LASTNAME']  = $shipname_arr[1];
		}
		
		$ret_arr['checkout_fname'] 				= trim($pass_arr['FIRSTNAME']);
		$ret_arr['checkout_mname'] 				= trim($pass_arr['MIDDLENAME']);
		$ret_arr['checkout_surname'] 			= trim($pass_arr['LASTNAME']);
		$ret_arr['checkout_comp_name'] 			= trim($pass_arr['BUSINESS']);
		$ret_arr['checkout_building'] 			= trim($pass_arr['SHIPTOSTREET']);
		$ret_arr['checkout_street'] 			= trim($pass_arr['SHIPTOSTREET2']);
		$ret_arr['checkout_city'] 				= trim($pass_arr['SHIPTOCITY']);
		$ret_arr['checkout_state'] 				= trim($pass_arr['SHIPTOSTATE']);
		
		//Delivery section filling
		$ret_arr['checkoutdelivery_fname'] 		= trim($pass_arr['FIRSTNAME']);
		$ret_arr['checkoutdelivery_mname'] 				= trim($pass_arr['MIDDLENAME']);
		$ret_arr['checkoutdelivery_surname'] 			= trim($pass_arr['LASTNAME']);
		$ret_arr['checkoutdelivery_comp_name'] 			= trim($pass_arr['BUSINESS']);
		$ret_arr['checkoutdelivery_building'] 			= trim($pass_arr['SHIPTOSTREET']);
		$ret_arr['checkoutdelivery_street'] 			= trim($pass_arr['SHIPTOSTREET2']);
		$ret_arr['checkoutdelivery_city'] 				= trim($pass_arr['SHIPTOCITY']);
		$ret_arr['checkoutdelivery_state'] 				= trim($pass_arr['SHIPTOSTATE']);
		
		$pass_arr['SHIPTOCOUNTRYCODE']			= trim($pass_arr['SHIPTOCOUNTRYCODE']);
		//echo "country code".$pass_arr['SHIPTOCOUNTRYCODE'];
		if($pass_arr['SHIPTOCOUNTRYCODE'])
		{
			// try to get the country details using the country code 
			 $sql_country_det = "SELECT country_id,country_name 
								FROM 
									general_settings_site_country 
								WHERE 
									sites_site_id = $ecom_siteid  
									AND country_code='".addslashes($pass_arr['SHIPTOCOUNTRYCODE'])."'  
								LIMIT 
									1";
			$ret_country_det = $db->query($sql_country_det);
			if($db->num_rows($ret_country_det))
			{
				$row_country_det = $db->fetch_array($ret_country_det);
				if($ecom_is_country_textbox==1)
				{
					$ret_arr['checkout_country'] 			= $row_country_det['country_name'];
					$ret_arr['checkoutdelivery_country'] 			= $row_country_det['country_name'];

					
				}
				else
				{
					$ret_arr['checkout_country'] 			= $row_country_det['country_id'];
					$ret_arr['checkoutdelivery_country'] 			= $row_country_det['country_id'];

				}
			}
		}
		$ret_arr['checkout_zipcode'] 			= $pass_arr['SHIPTOZIP'];
		$ret_arr['checkout_phone'] 				= $pass_arr['PHONENUM'];
		//delivery
		$ret_arr['checkoutdelivery_zipcode']= $pass_arr['SHIPTOZIP'];
		$ret_arr['checkoutdelivery_phone'] = $pass_arr['PHONENUM'];
	}
	return $ret_arr;
}
?>
