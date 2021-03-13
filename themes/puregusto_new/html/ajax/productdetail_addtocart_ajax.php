<?php
	function display_intercart_details()
	{
		global $db,$ecom_siteid,$Captions_arr,$ecom_themename,$productHtml,$intercartHtml,$Settings_arr;
		?>
		<?php
			$intercartHtml->show_cartdetails();
		?>
		<?php
	}
	function show_product_details($product_id,$var_arr=array(),$qty='',$mod='')
	{
	global $db,$ecom_siteid,$Captions_arr,$ecom_themename,$productHtml,$Settings_arr;
	$enable_special_type_display    = $Settings_arr['proddet_special_display'];
	$Captions_arr['PROD_DETAILS'] 	= getCaptions('PROD_DETAILS');
	$Captions_arr['PRICE_DISPLAY'] 	= getCaptions('PRICE_DISPLAY');

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
	<?php
	if($mod=='interCart')
	{
	?>
	<input type="hidden" name="intercart" id="intercart" value="1" />
	<?php
	}
	?>
	<div class="p_main">
	<?php
	/*		
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
	*/ 
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
	<div class="prod_det_name_link">
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
	          $price_class_arr['ul_class'] 	= 'shelfBul_three_column';
				$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
				$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
				$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
				$price_class_arr['discount_class'] 	= 'productdetdiscountprice';;
				$price_class_arr['emi_class'] 		= 'emi_price_details';
	echo show_Price($row_outerprods,$price_class_arr,'prod_detail');	
	?>
	</div>
	<div  id="bulkdisc_holder">
	<?php
	$productHtml->show_BulkDiscounts($row_outerprods); 
	?>
	</div>
	<?php 
	$button_displayed   = $productHtml->show_buttons_ajax($row_outerprods);
	$var_listed 		= $productHtml->show_ProductVariables_ajax($row_outerprods,'column',$product_id);
	?>
	
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
function display_cart_details($mod,$alert,$custid=0)
{ 	
	global $Captions_arr;
		 $Captions_arr['CART']	= getCaptions('CART');

		switch($mod)
		{
		  case 'Cart':
		  case 'interCart':
		  $onclick = '';
		  if($mod=='interCart')
		  {
		   $onclick ='';
		   $onclickA ='onclick="close_ajax_div()"';
		   $value = "OK";
		  }
		  else
		  {
			  $onclickA ='';
		  $onclick = 'onclick="show_cart_from_ajax()"';
		  $value = "View Cart";
		  }
			            //$cart_data = cartCalc();
						//$tot_price = print_price(get_session_var('cart_total'),true); // this is for grand total
						$tot_cart_count = get_session_var('cart_total_items');
						$tot_price = print_price(get_session_var('cart_subtotal'),true); // showing only the subtotal as per request from the client
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
			<td align="right" valign="middle"><a href="javascript:void(0)" <?php echo $onclickA;?>><input name="continue_submit" class="buttonred_cartQ" id="continue_submit" value="<?php echo $value; ?>"  type="button" <?php echo $onclick;?> ></a></td>
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
			<td colspan="2" align="right" valign="top"  class="p_cart_top"><a href="javascript:void(0)" onclick="close_ajax_div()"><img src="<?php url_site_image('closeq.png')?>"  /></a></td>
			</tr>
			<tr>
			<td colspan="2" align="center"  class="p_cart_msg"><?php echo $alert; ?><br /></td>
			</tr>
			<tr>
			<td align="left" valign="middle">&nbsp;</td>
			<td align="left" valign="middle">&nbsp;</td>
			</tr>
			<tr>
			<td align="left" valign="middle"><a href="javascript:void(0)" onclick="close_ajax_div()"><input name="continue_submit" class="buttonred_cartQ" id="continue_submit" value="Continue Shopping"  type="button"></a>
			</td>
			<td align="right" valign="middle"><a href="<?php echo SITE_URL?>/enquiry.html"><input name="continue_submit" class="buttonred_cartQ" id="continue_submit" value="View Enquiry"  type="button" onclick="window.location='<?php echo SITE_URL?>/enquiry.html'"></a></td>
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
			<td colspan="2" align="right" valign="top"  class="p_cart_top"><a href="javascript:void(0)" onclick="close_ajax_div()"><img src="<?php url_site_image('closeq.png')?>"  /></a></td>
			</tr>
			<tr>
			<td colspan="2" align="center"  class="p_cart_msg"><?php echo $alert; ?><br /></td>
			</tr>
			<tr>
			<td align="left" valign="middle">&nbsp;</td>
			<td align="left" valign="middle">&nbsp;</td>
			</tr>
			<tr>
			<td align="left" valign="middle"><a href="javascript:void(0)" onclick="close_ajax_div()"><input name="continue_submit" class="buttonred_cartQ" id="continue_submit" value="Continue Shopping"  type="button"></a>
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
			<td align="right" valign="middle"><?php echo $link ?></td>
			</tr>
			</table>
			</div>
			</div>
		  <?php
		  break;
		}
		
}
function Quick_show_product_details($product_id,$var_arr=array(),$qty='',$mod='')
	{
	global $db,$ecom_siteid,$Captions_arr,$ecom_themename,$productHtml,$Settings_arr;
	$enable_special_type_display    = $Settings_arr['proddet_special_display'];
	$Captions_arr['PROD_DETAILS'] 	= getCaptions('PROD_DETAILS');
	$Captions_arr['PRICE_DISPLAY'] 	= getCaptions('PRICE_DISPLAY');

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
		/*
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
	<?php
	if($mod=='interCart')
	{
	?>
	<input type="hidden" name="intercart" id="intercart" value="1" />
	<?php
	}
	?>
	<div class="p_main1">
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
	<div class="p_close"><a href="javascript:void(0)" onclick="quickclose_ajax_div()"><img src="<?php url_site_image('close.png')?>" width="47" height="45" border="0" /></a></div>
	<div class="p_content_otr1">
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
	$button_displayed   = $productHtml->show_buttons_ajax($row_outerprods);
	$var_listed 		= $productHtml->show_ProductVariables_ajax($row_outerprods,'column',$product_id);
	?>
	<div class="p_content_bulk" id="bulkdisc_holder">
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
	 
	*/ 
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
      <div class="detail-contentwrap">
	<div class="p_close"><a href="javascript:void(0)" onclick="unloadQuickviewPopupBox()"><img src="<?php url_site_image('close.png')?>"  border="0" /></a></div>
<div class="q_content_name">
	<?php 			
	 echo stripslashes($row_outerprods['product_name']);
	?>
	</div>
	<div id="moreimage_holder">
<?php                       
			// Showing additional images
			$productHtml->show_more_images($row_outerprods,$exclude_tabid,$exclude_prodid);			
			
?>
			</div>
			<div class="detail_big_img_wrap" id="mainimage_holder"><?php
		$productHtml->Show_Image_Normal($row_outerprods);
		
	?></div>
<div class="detail_price-wrap" >
	<div id="price_holder">
	<?php 
	$price_class_arr['ul_class'] 	= 'shelfBul_three_column';
	$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
	$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
	$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
	$price_class_arr['discount_class'] 	= 'productdetdiscountprice';
	echo show_Price($row_outerprods,$price_class_arr,'prod_detail');	
	?>
	</div>
	<div id="bulkdisc_holder">
	<?php
	$productHtml->show_BulkDiscounts($row_outerprods); 
	
	?>
	</div>
	<?php
	$var_listed  = $productHtml->show_ProductVariables_ajax($row_outerprods,'column',$product_id);
	$productHtml->show_bonus_points($row_outerprods);
    //$productHtml->offer_buttons($row_outerprods);
	
	$productHtml->show_buttons_ajax($row_outerprods);
	
	?>	
	
	</div>
      <?php 
      }
       
	}    	
				
?>