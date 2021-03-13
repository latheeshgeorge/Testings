<?php
	/*############################################################################
	# Script Name 	: favcategory_showallHtml.php
	# Description 	: Page which holds the display logic for all products under fav category
	# Coded by 		: LSH
	# Created on	: 14-oct-2008
	# Modified by	: LH
	# Modified On	: 
	##########################################################################*/
	class categoryshowall_Html
	{ 
		// Defining function to show the shelf details
		function Show_favcatproducts($catid,$catname)
		{
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr;
			
			$customer_id 					= get_session_var("ecom_login_customer");

			$sql_last_login = "SELECT customer_last_login_date FROM customers WHERE sites_site_id=$ecom_siteid AND customer_id=$customer_id";
			$ret_last_login = $db->query($sql_last_login);
			list($row_last_login) = $db->fetch_array($ret_last_login);
			
			$prodcur_arr =array();
			$limit = $Settings_arr['product_maxcnt_fav_category'];
			//Taking the New products added in the category after customer's last login	
			$sql_prod_first = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
												a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
												a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
												product_total_preorder_allowed,a.product_applytax,a.product_shortdesc,product_bonuspoints,
												a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,a.product_variablesaddonprice_exists,
												a.product_variablecomboprice_allowed,a.product_variablecombocommon_image_allowed,a.default_comb_id,
												a.price_normalprefix,a.price_normalsuffix, a.price_fromprefix, a.price_fromsuffix,a.price_specialofferprefix, a.price_specialoffersuffix, 
												a.price_discountprefix, a.price_discountsuffix, a.price_yousaveprefix, a.price_yousavesuffix,a.price_noprice,
												a.product_averagerating,a.product_saleicon_show,a.product_saleicon_text,a.product_newicon_show,a.product_newicon_text,
												a.product_freedelivery                     
											FROM 
												products a,product_category_map b 
											WHERE 
												b.product_categories_category_id = ".$catid." 
												AND a.product_id = b.products_product_id 
												AND a.product_hide = 'N'
												AND a.sites_site_id = $ecom_siteid 
												AND a.product_adddate >= '".$row_last_login."' 
												ORDER BY a.product_webprice ASC LIMIT $limit";
			$ret_prod_first = $db->query($sql_prod_first);
			
			if($db->num_rows($ret_prod_first))
			{ 
				$limit = $limit-$db->num_rows($ret_prod_first);
				if($db->num_rows($ret_prod_first)>0)
				{
					while($row_prod_first = $db->fetch_array($ret_prod_first))
					{
					  $prodcur_arr[] = $row_prod_first;
					  $ids[] = $row_prod_first['product_id'];
					}
				}
			}
			if(count($ids)==0)
			{
			$ids = array('-1');
			}
			$ids_in = implode(',',$ids);
			//if no 3 new products found then
			if($limit>0)
			{
				//second case -  taking products with higest discount
				$sql_prod_sec = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
													a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
													a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
													product_total_preorder_allowed,a.product_applytax,a.product_shortdesc,product_bonuspoints,
													a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,a.product_variablesaddonprice_exists ,
													a.product_variablecomboprice_allowed,a.product_variablecombocommon_image_allowed,a.default_comb_id,
													a.price_normalprefix,a.price_normalsuffix, a.price_fromprefix, a.price_fromsuffix,a.price_specialofferprefix, a.price_specialoffersuffix, 
													a.price_discountprefix, a.price_discountsuffix, a.price_yousaveprefix, a.price_yousavesuffix,a.price_noprice  ,    
													CASE a.product_discount_enteredasval 
														WHEN  '0'
															THEN (product_webprice * product_discount /100)
														WHEN  '1'
															THEN product_discount
														WHEN  '2'
															THEN (product_webprice-product_discount)
														END  AS discountval,      
													    a.product_variablecomboprice_allowed,a.product_variablecombocommon_image_allowed,a.default_comb_id,
														a.price_normalprefix,a.price_normalsuffix, a.price_fromprefix, a.price_fromsuffix,a.price_specialofferprefix, a.price_specialoffersuffix, 
														a.price_discountprefix, a.price_discountsuffix, a.price_yousaveprefix, a.price_yousavesuffix,a.price_noprice,
														a.product_averagerating,a.product_saleicon_show,a.product_saleicon_text,a.product_newicon_show,a.product_newicon_text,
														a.product_freedelivery 
													FROM  
															products a,product_category_map b  
													WHERE 
															sites_site_id =$ecom_siteid
															AND product_discount >0 
															AND b.product_categories_category_id = ".$catid." 
															AND a.product_id = b.products_product_id 
															AND a.product_hide = 'N'
															AND a.product_id NOT IN ($ids_in)
													ORDER  BY 
															discountval DESC LIMIT $limit";
				$ret_prod_sec = $db->query($sql_prod_sec);
				
				if($db->num_rows($ret_prod_sec))
				{ 
				$limit = $limit-$db->num_rows($ret_prod_sec);
					if($db->num_rows($ret_prod_sec)>0)
					{
						while($row_prod_sec = $db->fetch_array($ret_prod_sec))
						{
						  $prodcur_arr[] = $row_prod_sec;
						  $ids[] = $row_prod_sec['product_id'];
						}
					}
				}
				if(count($ids)==0)
				{
					$ids = array('-1');
				}
				$ids_in = implode(',',$ids);
			}
			//Still no 3 products found after new products and discounted products then
			if($limit>0)
			{
				$sql_prod_third = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
												a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
												a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
												product_total_preorder_allowed,a.product_applytax,a.product_shortdesc,product_bonuspoints, 
												a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,a.product_variablesaddonprice_exists,
												a.product_variablecomboprice_allowed,a.product_variablecombocommon_image_allowed,a.default_comb_id,
												a.price_normalprefix,a.price_normalsuffix, a.price_fromprefix, a.price_fromsuffix,a.price_specialofferprefix, a.price_specialoffersuffix, 
												a.price_discountprefix, a.price_discountsuffix, a.price_yousaveprefix, a.price_yousavesuffix,a.price_noprice ,
												a.product_averagerating,a.product_saleicon_show,a.product_saleicon_text,a.product_newicon_show,a.product_newicon_text,
												a.product_freedelivery         
											FROM 
												products a,product_category_map b 
											WHERE 
												b.product_categories_category_id = ".$catid." 
												AND a.product_id = b.products_product_id 
												AND a.product_hide = 'N'
												AND a.sites_site_id = $ecom_siteid 
												AND a.product_id NOT IN ($ids_in) 
												ORDER BY a.product_webprice ASC LIMIT $limit";
				$ret_prod_third = $db->query($sql_prod_third);
				if($db->num_rows($ret_prod_third)>0)
				{
					while($row_prod_third = $db->fetch_array($ret_prod_third))
					{
					  $prodcur_arr[] = $row_prod_third;
					  $ids[] = $row_prod_third['product_id'];
					}
				}
				if(count($ids)==0)
				{
					$ids = array('-1');
				}
				$ids_in = implode(',',$ids);
			}
			switch($Settings_arr['favoritecategory_prodlisting'])
			{ 
				case 'nor':
				if(count($prodcur_arr))
				{
				?>
				<div class="pro_linkprod_div"><a href="<?php url_category($catid,$catname,-1,$_REQUEST['catgroup_id'],0)?>" class="categoreyall_headerlink_myhome" title="<?php echo stripslashes($catname)?>"><?php echo stripslashes($catname)?></a></div>
				
			  <?
		//$prodcur_arr = array();
		foreach( $prodcur_arr as $k=>$product_array)
		{
		 $prodcurtd_arr[] = $product_array;
					//##############################################################
					// Showing the title, description and image part for the product
					//##############################################################
			?>
			
			<div class="search_main">
                    
                           					 <div class="search_image"><a href="<?php url_product($product_array['product_id'],$product_array['product_name'],-1)?>" title="<?php echo stripslashes($product_array['product_name'])?>"><?php // Calling the function to get the type of image to shown for current 
																	$pass_type = get_default_imagetype('fav_prod');
																	// Calling the function to get the image to be shown
																	$img_arr = get_imagelist('prod',$product_array['product_id'],$pass_type,0,0,1);
																	if(count($img_arr))
																	{
																		show_image(url_root_image($img_arr[0][$pass_type],1),$product_array['product_name'],$product_array['product_name']);
																	}
																	else
																	{
																		// calling the function to get the default image
																		$no_img = get_noimage('prod',$pass_type); 
																		if ($no_img)
																		{
																			show_image($no_img,$product_array['product_name'],$product_array['product_name']);
																		}	
																	}
																	?>
																	</a>
																	<?php
														$prefix = "<div class='compare_li'>";
															$suffix = "</div>";
														 if(isProductCompareEnabled())  {
															dislplayCompareButton($product_array['product_id'],$prefix,$suffix);
														}?>	
														</div>	
                           								
														 <div class="search_content">
																<div class="search_name" ><a href="<?php url_product($product_array['product_id'],$product_array['product_name'],-1)?>" title="<?php echo stripslashes($product_array['product_name'])?>" class="categoreyname_headerlink_myhome"><?php echo stripslashes($product_array['product_name'])?></a></div>
																 <div class="search_des"><?php  echo stripslashes($product_array['product_shortdesc'])?></div>
														         <div class="search_price">
																 <UL>
																 
																  <?php $price_class_arr['ul_class'] 		= '';
																$price_class_arr['normal_class'] 	= 'search_normal_price';
																$price_class_arr['strike_class'] 	= 'search_price_strike';
																$price_class_arr['yousave_class'] 	= 'search_price_offer';
																$price_class_arr['discount_class'] 	= 'search_price_dis';
																echo show_Price($product_array,$price_class_arr,'shelfcenter_1');
																?>
																</UL>
																  </div>
														          </div>
														 <div class="search_buy" align="right">
															
																<label><a href="<?php url_product($product_array['product_id'],$product_array['product_name'],-1)?>" title="<?php echo stripslashes($product_array['product_name'])?>">
																</a></label>
															
														<?php
															
																$frm_name = uniqid('mafavhome_');
														?>	
																<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $product_array['product_id']?>)">
																<input type="hidden" name="fpurpose" value="" />
																<input type="hidden" name="fproduct_id" value="" />
																<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
																<input type="hidden" name="fproduct_url" value="<?php url_product($product_array['product_id'],$product_array['product_name'])?>" />
																	<label><?php show_moreinfo($product_array,'product_info')?></label>
																	
																	<?php
																	$prefix = "<DIV class='product_list_button_list'><label>"; 
																	$suffix = "</label> </DIV>";
																		$class_arr 					= array();
																		$class_arr['ADD_TO_CART']	= 'product_list_button';
																		$class_arr['PREORDER']		= 'product_list_button';
																		$class_arr['ENQUIRE']		= 'product_list_button';
																		show_addtocart($product_array,$class_arr,$frm_name,false,$prefix,$suffix)
																	?>
																</form>
																</div>
															</div>	
				 <?
			     }//end of for loop
			   }//end of prod checking
	            ?>
				<?
				break;
				}//end of switchcase
		}
	};	
?>