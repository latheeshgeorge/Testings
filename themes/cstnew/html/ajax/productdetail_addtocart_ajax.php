<?php
	function show_product_details($product_id,$var_arr=array())
	{
		
	global $db,$ecom_siteid,$Captions_arr,$ecom_themename,$productHtml,$Settings_arr;
	$enable_special_type_display    = $Settings_arr['proddet_special_display'];
	$Captions_arr['PROD_DETAILS'] 	= getCaptions('PROD_DETAILS');
	$enable_grid      = false;
	if($_REQUEST['grid']=='true')
	{
	  $enable_grid    = true;
	}	

	if($product_id)
	{
	 $sql_outerprod = "SELECT product_id,product_name,product_variablestock_allowed,product_show_cartlink,product_show_enquirelink,
							product_preorder_allowed,product_show_enquirelink,product_webstock,product_webprice,
							product_discount,product_discount_enteredasval,product_bulkdiscount_allowed,
							product_total_preorder_allowed,product_applytax,product_shortdesc,product_longdesc,
							product_averagerating,product_deposit,product_deposit_message,product_bonuspoints,
							product_variable_display_type,product_variable_in_newrow,productdetail_moreimages_showimagetype,
							product_default_category_id,product_details_image_type,product_flv_filename,product_stock_notification_required,
							product_alloworder_notinstock,product_variables_exists,product_variablesaddonprice_exists,
							product_variablecomboprice_allowed,product_det_qty_type,product_det_qty_caption,product_det_qty_drop_values,
							product_det_qty_drop_prefix,product_det_qty_drop_suffix,product_variablecombocommon_image_allowed,default_comb_id,
							price_normalprefix, price_normalsuffix, price_fromprefix, price_fromsuffix,price_specialofferprefix, price_specialoffersuffix, 
							price_discountprefix, price_discountsuffix, price_yousaveprefix, price_yousavesuffix, price_noprice,product_freedelivery,product_show_pricepromise,
							product_hide,product_saleicon_show,product_saleicon_text,product_newicon_show,product_newicon_text,product_commonsizechart_link,produt_common_sizechart_target,
							product_intensivecode,manufacture_id ,product_model      
					FROM 
						products 
					WHERE 
						product_id=".$product_id." 
						AND sites_site_id=$ecom_siteid 
						AND product_hide ='N' 
					LIMIT 
						1";
	$ret_outerprod		= $db->query($sql_outerprod);
	if($db->num_rows($ret_outerprod))
	$row_outerprods 	= $db->fetch_array($ret_outerprod);
	}
	if($db->num_rows($ret_outerprod))
	{
	//$var_listed = show_ProductVariables($row_outerprods,'column');
	// Show_Image_Normal($row_outerprods);
	?> 
	<form method="post" name="frm_proddetails_ajax" id="frm_proddetails_ajax" action="<?php url_link('manage_products.html')?>" class="frm_cls" onsubmit="return prod_detail_submit(this)">
	<input type="hidden" name="fpurpose" value="" />
	<input type="hidden" name="fproduct_id" value="<?php echo $product_id?>" />
	<input type="hidden" name="product_id_ajax" id="product_id_ajax" value="<?php echo $product_id?>" />
	<input type="hidden" name="pricepromise_url" value="<?php url_link('pricepromise'.$row_outerprods['product_id'].'.html')?>" />
	<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
	<input type="hidden" name="pass_combid" value="<?php echo $_REQUEST['pass_combid']?>" />
	<input type="hidden" name="comb_img_allowed" id="comb_img_allowed" value="<?php echo $row_outerprods['product_variablecombocommon_image_allowed']?>" />
	<input type="hidden" name="pagetype" id="pagetype" value="" />	
	<input type="hidden" name="how_displayed" id="how_displayed" value="proddet_ajax_list" />		         
	<div class="p_main">
	<?php
			
	if($row_outerprods['product_freedelivery']==1)
	{
	?>	
	<div class="p_content_free"></div>
	<?php
	}
	else
	{
	?>	
	<div class="p_content_free_no"></div>
	<?php
	}
	?>
	<div class="p_close"><a href="javascript:void(0)" onclick="close_ajax_div()"><img src="<?php url_site_image('close.png')?>" border="0" /></a></div>
	<div class="p_content_otr">
	<table border="0" cellpadding="0" cellspacing="0">
	<tr>
	<td class="show_left" valign="top">
	<div class="p_content_l">
		<?php
			if($row_outerprods['product_show_pricepromise']==1)
			{
			?>
			<div class="p_content_promice"></div>
			<?php
			}
	?>
	<div class="p_content_image" id="mainimage_holder">
	<?php
		$productHtml->Show_Image_Normal($row_outerprods,false,true);
	?>
	</div>
	<?php
	$HTML_loading_ajax = '<div class="proddet_loading_outer_div" style="height:15px;padding:5px 0 0 0;"><div id="proddet_loading_div_ajaxinner" style="display:none;padding:5px 0 0 0;">
		<img src="'.url_site_image('proddet_loading.gif',1).'" alt="loading..." width="43px" height="11px;align:left">
		</div></div>';
		echo $HTML_loading_ajax;
	?>
	</div>
	</td>
	<td class="show_right" valign="top">
<div class="det_det_otr">
            
            <div class="det_name_con">
               
                <div class="det_price_otr">                    
                    <div class="buy_holder">
                        <div class="deat_pdt_buy_outr">                        
                            <div class="deat_pdt_buy_right">
								 <div class="det_name_otr">
                   <div class="det_comp_stock">                   
                   <div class="det_name_top"><h1>
						<?php 	
							echo ltrim(stripslashes($row_outerprods['product_name']),'');
	                    ?>
                    </h1></div>
                    <div class="det_name_bottom"></div>
                    </div>
                   
                </div>
									<div class="p_content_price" id="price_holder">

                            <?php 
                            $was_price = $cur_price = $sav_price = '';
							$price_arr = show_Price($row_outerprods,$price_class_arr,'prod_detail',false,5);
							//echo "<pre>";print_r($price_arr);
							if($price_arr['price_with_captions']['discounted_price'])
							{
								$was_price = $price_arr['price_with_captions']['base_price'];
								$cur_price = $price_arr['price_with_captions']['discounted_price'];
								if($price_arr['price_with_captions']['disc_percent'])
									$sav_price = $price_arr['price_with_captions']['disc'];
								else
									$sav_price = $price_arr['price_with_captions']['yousave_price'];
							}
							else
							{
								$was_price = '';
								$cur_price = $price_arr['price_with_captions']['base_price'];
								$sav_price = '';
							}
								$HTML_price = '<div class="deat_price">';
								if($was_price)
								$HTML_price .= '<div class="deat_priceA">'.$was_price.'</div>';
								if($cur_price)
								$HTML_price .= '<div class="deat_priceB">'.$cur_price.'</div>';	

								if($sav_price)
								{
								$HTML_price .= '<div class="deat_priceC">
								<div class="deat_priceCleft"><span>'.$sav_price.'</span></div>
								</div>';
								}					
								$HTML_price .= '</div>';
							
							echo $HTML_price;	
							?>
							</div>
							<?php
							$var_listed 		= $productHtml->show_ProductVariables_ajax($row_outerprods,'column',$product_id,$enable_grid);
							$button_displayed   = $productHtml->show_buttons_ajax($row_outerprods);
							?>
							
                            </div>
					       	<div class="p_content_bulk" id="bulkdisc_holder">
					       <?php
								$productHtml->show_BulkDiscounts($row_outerprods); 
						   ?>
						    </div>
						   <?php
	
	if(trim($row_outerprods['product_shortdesc'])!='')
	{
	?>
	<div class="p_content_shortdesc"><?php echo stripslashes($row_outerprods['product_shortdesc'])?></div>
	<?php
	}
	
	if ($row_outerprods['product_show_enquirelink']==1 )
	{
	?>
	<div class="deat_pdt_buy_left">
	<div class="deat_pdt_buyA">
	<?php 
	// Check whether the enquire link is to be displayed
	if ($row_outerprods['product_show_enquirelink']==1)
	{
	?>
	<input name="Submit_enq" type="button" class="buttonblackbuy_ajax" id="Submit_enq" value="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_ENQUIRY'];?>" onclick="ajax_addto_cart_fromlist('add_prod_tocart_ajax','Prod_Enquire','frm_proddetails_ajax','<?php echo SITE_URL?>')" /> 
	<?php
	}			
	?>
	</div>
	</div>

	<?php	
	}
	?>
                        </div>
                    </div>                        
                      
                    </div>
                    <div class="det_price_bottom"></div>
                </div>
            <div class="det_con_top"></div>
                <div class="det_con_bottom">
                </div>                    
            </div>
	</td>
	</tr>
	</table>	
	</div>
	</div>
	</form>
	<?php 
      }
	}
function display_cart_details($mod,$alert,$custid=0)
{ 	
	global $Captions_arr;
		 $Captions_arr['CART']	= getCaptions('CART');

		switch($mod)
		{
		  case 'Cart':
			            //$cart_data = cartCalc();
					//	$tot_price = print_price(get_session_var('cart_total'),true);
						$tot_price = print_price(get_session_var('cart_subtotal'),true);
						
						$tot_cart_count = get_session_var('cart_total_items');
			//$tot_cart_count = count($cart_data["products"]);
			//$tot_price 		= print_price($cart_data['totals']['price']);	
			?>
            
			<div class="p_main_a">
			<div class="p_content_otr_a">
			<form name="cart_summary_ajax_form" id="cart_summary_ajax_form" action="<?php echo SITE_URL?>/cart.html" method="post">
			<input type="hidden" name="cont_pass_val" id="cont_pass_val" value="" />
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td colspan="2" align="right" valign="top"  class="p_cart_top"><a href="javascript:void(0)" onclick="close_ajax_div()"><img src="<?php url_site_image('closeq.png')?>"  /></a></td>
			</tr>
			<tr>
			<td colspan="2" align="center"  class="p_cart_msg"><?php echo $alert; ?><br /></td>
			</tr>
			<tr>
			<td align="right" valign="middle" class="lfr"><?php echo $Captions_arr['CART']['AJAX_CART_NUMBER']?>&nbsp;:</td>
			<td align="left" valign="middle" class="p_cart_cont"><?php echo $tot_cart_count;?></td>
			</tr>
			<tr>
			<td align="right" valign="middle" class="p_cart_contA"><?php echo $Captions_arr['CART']['AJAX_CART_TOTAL']?>&nbsp;:</td>
			<td align="left" valign="middle" class="p_cart_contA"><?php echo $tot_price; ?></td>
			</tr>
			<tr>
			<td align="left" valign="middle">&nbsp;</td>
			<td align="left" valign="middle">&nbsp;</td>
			</tr>
			<tr>
			<td align="left" valign="middle"><a href="javascript:void(0)" onclick="close_ajax_div()"><input name="continue_submit" class="buttonred_cartQ" id="continue_submit" value="Continue Shopping"  type="button"></a>
			</td>
			<td align="right" valign="middle">
			<!--<a href="<?php echo SITE_URL?>/cart.html"><input name="continue_submit" class="buttonred_cartQ" id="continue_submit" value="View Cart"  type="button" onclick="window.location='<?php echo SITE_URL?>/cart.html'" ></a>-->
			<a href="javascript:void(0)"><input name="continue_submit" class="buttonred_cartQ" id="continue_submit" value="View Cart"  type="button" onclick="show_cart_from_ajax()" ></a>
			</td>
			</tr>
			</table>
			</form>
			</div>
			</div>
			<?php					
		  break;
		  case 'Enquire':
		  ?>
		  <div class="p_main_enq">
			<div class="p_content_otr_a">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="enquiry_table_ajax">
			<tr>
			<td colspan="2" align="right" valign="top"  class="p_cart_top_enquiry"><a href="javascript:void(0)" onclick="close_ajax_div()"><img src="<?php url_site_image('closeq.png')?>"  /></a></td>
			</tr>
			<tr>
			<td colspan="2" align="center"  class="p_cart_msg_enquiry"><?php echo $alert; ?><br /></td>
			</tr>
			<tr>
			<td align="left" valign="middle">&nbsp;</td>
			<td align="left" valign="middle">&nbsp;</td>
			</tr>
			<tr>
			<td align="left" valign="bottom"><a href="javascript:void(0)" onclick="close_ajax_div()"><input name="continue_submit" class="buttonred_cartQ" id="continue_submit" value="Continue Shopping"  type="button"></a>
			</td>
			<td align="right" valign="bottom"><a href="<?php echo SITE_URL?>/enquiry.html"><input name="continue_submit" class="buttonred_cartQ" id="continue_submit" value="View Enquiry"  type="button" onclick="window.location='<?php echo SITE_URL?>/enquiry.html'"></a></td>
			</tr>
			</table>
			</div>
			</div>
		  <?php
		  break;
		  case 'Wishlist':
		  ?>
		  <div class="p_main_enq">
			<div class="p_content_otr_a">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="enquiry_table_ajax">
			<tr>
			<td colspan="2" align="right" valign="top"  class="p_cart_top_enquiry"><a href="javascript:void(0)" onclick="close_ajax_div()"><img src="<?php url_site_image('closeq.png')?>"  /></a></td>
			</tr>
			<tr>
			<td colspan="2" align="center"  class="p_cart_msg_enquiry"><?php echo $alert; ?><br /></td>
			</tr>
			<tr>
			<td align="left" valign="middle">&nbsp;</td>
			<td align="left" valign="middle">&nbsp;</td>
			</tr>
			<tr>
			<td align="left" valign="bottom"><a href="javascript:void(0)" onclick="close_ajax_div()"><input name="continue_submit" class="buttonred_cartQ" id="continue_submit" value="Continue Shopping"  type="button"></a>
			</td>
			<?php
			 if($custid > 0)
			 {
				 $link = '<a href="'.SITE_URL.'/wishlist.html"><input name="continue_submit" class="buttonred_cartQ" id="continue_submit" value="View Wishlist"  type="button" onclick="window.location='.SITE_URL.'/wishlist.html"></a>';
			 }
			 else
			 {
				 	$link = '<a href="'.SITE_URL.'/custlogin.html"><input name="continue_submit" class="buttonred_cartQ" id="continue_submit" value="Login"  type="button" onclick="window.location='.SITE_URL.'/custlogin.html"></a>';

			 }
			?>
			<td align="right" valign="bottom"><?php echo $link ?></td>
			</tr>
			</table>
			</div>
			</div>
		  <?php
		  break;
		}
		
}    	
				
?>
