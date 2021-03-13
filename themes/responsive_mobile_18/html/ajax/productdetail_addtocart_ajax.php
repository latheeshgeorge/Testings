<?php
	function show_product_details($product_id,$var_arr=array())
	{
	global $db,$ecom_siteid,$Captions_arr,$ecom_themename,$productHtml,$Settings_arr;
	$enable_special_type_display    = $Settings_arr['proddet_special_display'];
	$Captions_arr['PROD_DETAILS'] 	= getCaptions('PROD_DETAILS');

	if($product_id)
	{
	echo $sql_outerprod = "SELECT product_id,product_name,product_variablestock_allowed,product_show_cartlink,product_show_enquirelink,
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
							product_hide,product_saleicon_show,product_saleicon_text,product_newicon_show,product_newicon_text,product_commonsizechart_link,produt_common_sizechart_target     
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
	<div class="p_close"><a href="javascript:void(0)" onclick="close_ajax_div()"><img src="<?php url_site_image('close.png')?>" width="47" height="45" border="0" /></a></div>
	<div class="p_content_otr">
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
		$productHtml->Show_Image_Normal($row_outerprods);
	?>
	</div>
	<?php
	$HTML_loading_ajax = '<div class="proddet_loading_outer_div" style="height:15px;padding:5px 0 0 0;"><div id="proddet_loading_div_ajaxinner" style="display:none;padding:5px 0 0 0;">
		<img src="'.url_site_image('proddet_loading.gif',1).'" alt="loading..." width="43px" height="11px;align:left">
		</div></div>';
		echo $HTML_loading_ajax;
	?>
	</div>
	<div class="p_content_r">
	<div class="p_content_name">
	<?php 			
	 echo "<a href='".url_product($row_outerprods['product_id'],$row_outerprods['product_name'],1)."' title='".stripslashes($row_outerprods['product_name'])."'>".stripslashes($row_outerprods['product_name'])."</a>";
	?>
	</div>
	<?php
	    $var_count = $productHtml->get_countvariables_count($row_outerprods['product_id']);
		if($enable_special_type_display==1 && $var_count==1)
		{
		$var_listed = $productHtml->show_ProductVariables_specialdisplay($row_outerprods,'popup');
		}
		else
		{
	?>
	<div class="p_content_price" id="price_holder">
	<?php 
	$price_class_arr['ul_class'] 	= 'prodeulprice';
	$price_class_arr['normal_class'] 	= 'p_content_price_B';
	$price_class_arr['strike_class'] 	= 'p_content_price_A';
	$price_class_arr['yousave_class'] 	= 'p_content_price_C';
	$price_class_arr['discount_class'] 	= 'productdetdiscountprice';
	echo show_Price($row_outerprods,$price_class_arr,'prod_detail');	
	?>
	</div>
	<?php 
	$var_listed 		= $productHtml->show_ProductVariables_ajax($row_outerprods,'column',$product_id);
	$button_displayed   = $productHtml->show_buttons_ajax($row_outerprods);
	?>
	<div class="p_content_bulk">
	<?php
	$productHtml->show_BulkDiscounts($row_outerprods); 
	?>
	</div>
	<?php
}
	?>
	</div>
	</div>
	</div>
	</form>
	<?php 
      }
	}
function display_cart_details($mod,$alert)
{ 	
	global $Captions_arr,$ecom_siteid,$db;
		 $Captions_arr['CART']	= getCaptions('CART');

		switch($mod)
		{
		  case 'Cart':
			            //$cart_data = cartCalc();
						$tot_price = print_price(get_session_var('cart_total'),true);
						$tot_cart_count = get_session_var('cart_total_items');
			//$tot_cart_count = count($cart_data["products"]);
			//$tot_price 		= print_price($cart_data['totals']['price']);	
			?>
			<div class="p_main_a">
			<div class="p_content_otr_a">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td colspan="2" align="right" valign="top"  class="p_cart_top"><a href="javascript:void(0)" onclick="unloadQuickviewPopupBox()"><img src="<?php url_site_image('closeq.png')?>"  /></a></td>
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
			<td align="left" colspan="2">
			<div class="ajax_button_new_outer">
				<div class="div_continue"><a href="javascript:void(0)" onclick="unloadQuickviewPopupBox()"><input name="continue_submit" class="buttonred_cartQ" id="continue_submit" value="Continue Shopping"  type="button"></a></div>
			<div class="div_viewcart"><a href="<?php echo SITE_URL?>/cart.html"><input name="continue_submit" class="buttonred_cartQ" id="continue_submit" value="View Cart"  type="button" onclick="window.location='<?php echo SITE_URL?>/cart.html'" ></a></div>
			</div>	
			</td>
			</tr>
			</table>
			</div>
			</div>
			<?php					
		  break;
		  case 'Enquire':
		  $session_id 				= session_id();	// Get the session id for the current section
		$Captions_arr['ENQUIRE'] 	= getCaptions('ENQUIRE');
		$Captions_arr['CART'] 		= getCaptions('CART');
		
		$sql_select_enq = "SELECT count(*) as cnt FROM product_enquiries_cart WHERE sites_site_id =$ecom_siteid AND session_id='$session_id'";
		$ret_select_enq = $db->query($sql_select_enq);
		if($db->num_rows($ret_select_enq)>0)
		{
			$row_select_enq = $db->fetch_array($ret_select_enq);
		}
		  ?>
		   <div  class="cart-lista" data-toggle="modal" data-target="#myModal">
                            <i class="fa fa-shopping-cart"></i>
                            <span class="badge"><?php echo  (get_session_var('cart_total_items'))?get_session_var('cart_total_items'):0?></span>
                        </div>
		   <div class="modal fade cart-list" id="myModal" role="dialog">
    <div class="modal-dialog ">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">My Enquiry(<?php echo  ($row_select_enq['cnt'])?$row_select_enq['cnt']:0?>)</h4>
        </div>
        							<div class="alert alert-success fade in red_alert" id="red_alert" style="display:none;" ><span  >Item added to the Enquiry</span></div>

        <div class="modal-body">
			<p><a href="javascript:void(0)" onclick="unloadQuickviewPopupBox()"><input name="continue_submit" class="btn btn-add-to-cart btn-lg sharp" id="continue_submit" value="Continue Shopping"  type="button"></a>
			</p>
			<p><a href="<?php echo SITE_URL?>/enquiry.html"><input name="continue_submit" class="btn btn-add-to-cart btn-lg sharp" id="continue_submit" value="View Enquiry"  type="button" onclick="window.location='<?php echo SITE_URL?>/enquiry.html'"></a>
			</p>			
			</div>        
      </div>
    </div>
  </div>
		  <?php
		  break;
		}
		
}    	
				
?>
