<?php
	   function show_product_summary($mod="summary",$pass_prod_id,$ecom_siteid)
		{
			global $db,$ecom_siteid,$Captions_arr,$ecom_themename,$productHtml,$Settings_arr;
			$enable_special_type_display    = $Settings_arr['proddet_special_display'];
			$Captions_arr['PROD_DETAILS'] 	= getCaptions('PROD_DETAILS');
			if($pass_prod_id && $ecom_siteid) 
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
														product_id=".$pass_prod_id." 
														AND sites_site_id=$ecom_siteid 
														AND product_hide ='N' 
													LIMIT 
														1";
					$ret_outerprod		= $db->query($sql_outerprod);
					if($db->num_rows($ret_outerprod))
						$row_outerprods 	= $db->fetch_array($ret_outerprod);
					$productHtml->show_summary($mod,$pass_prod_id,$row_outerprods);
				}
		}		
?>

