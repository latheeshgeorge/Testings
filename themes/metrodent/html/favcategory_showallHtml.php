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
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents;
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
												product_freedelivery, product_show_pricepromise, product_saleicon_show, product_saleicon_text, 
												product_newicon_show, product_newicon_text                
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
													a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,a.product_variablesaddonprice_exists  ,
													CASE product_discount_enteredasval   
														WHEN  '0'
															THEN (product_webprice * product_discount /100)
														WHEN  '1'
															THEN product_discount
														WHEN  '2'
															THEN (product_webprice-product_discount)
														END  AS discountval,
														product_variablecomboprice_allowed,product_variablecombocommon_image_allowed,default_comb_id,
														price_normalprefix,price_normalsuffix, price_fromprefix, price_fromsuffix,price_specialofferprefix,price_specialoffersuffix, 
														price_discountprefix, price_discountsuffix, price_yousaveprefix, price_yousavesuffix,price_noprice,
														product_freedelivery, product_show_pricepromise, product_saleicon_show, product_saleicon_text, 
														product_newicon_show, product_newicon_text 
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
												product_variablecomboprice_allowed,product_variablecombocommon_image_allowed,default_comb_id,
												price_normalprefix,price_normalsuffix, price_fromprefix, price_fromsuffix,price_specialofferprefix,price_specialoffersuffix, 
												price_discountprefix, price_discountsuffix, price_yousaveprefix, price_yousavesuffix,price_noprice,
												product_freedelivery, product_show_pricepromise, product_saleicon_show, product_saleicon_text, 
												product_newicon_show, product_newicon_text       
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
			$prod_compare_enabled = isProductCompareEnabled();
			?>
			<div class="mid_shlf2_hdr">
				<div class="mid_shlf2_hdr_top"></div>
					<div class="mid_shlf2_hdr_middle"><a href="<?php url_category($catid,$catname,-1,$_REQUEST['catgroup_id'],0)?>" class="categoreyname_headerlink" title="<?php echo stripslash_normal($catname)?>"><?php echo stripslash_normal($catname)?></a>
					</div>
				<div class="mid_shlf2_hdr_bottom"></div>
			</div>
			<?php
			switch($Settings_arr['favoritecategory_prodlisting'])
			{ 
				case '1row':
				?>
				<div class="mid_shlf_con" >
					<?php
					foreach( $prodcur_arr as $k=>$product_array)
					{
					?>
						<div class="mid_shlf_top"></div>
						<div class="mid_shlf_middle">
						<div class="mid_shlf_pdt_name"><a href="<?php url_product($product_array['product_id'],$product_array['product_name'],-1)?>" title="<?php echo stripslash_normal($product_array['product_name'])?>"><?php echo stripslash_normal($product_array['product_name'])?></a></div>
						<div class="mid_shlf_mid">
						<div class="mid_shlf_pdt_image">
							<a href="<?php url_product($product_array['product_id'],$product_array['product_name'],-1)?>" title="<?php echo stripslash_normal($product_array['product_name'])?>">
							<?php
							// Calling the function to get the image to be shown
							$pass_type = get_default_imagetype('midshelf');
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
							<? 
						if($prod_compare_enabled)
						{
						?>
							<div class="mid_shlf_pdt_compare" >
							<?php	dislplayCompareButton($product_array['product_id']);?>
							</div>
						<?php	
						}
						?>
						</div>
						</div>
						<div class="mid_shlf_pdt_des">
						<?php
							echo stripslash_normal($product_array['product_shortdesc']);
							
						$module_name = 'mod_product_reviews';
						if(in_array($module_name,$inlineSiteComponents))
						{
							if($product_array['product_averagerating']>=0)
							{
							?>
							<div class="mid_shlf_pdt_rate">
							<?php
								display_rating($row_prod['product_averagerating']);
							?>
							</div>
							<?php
							}
						}	
						show_bonus_points_msg($product_array,'bonus_point'); // Show bonus points
						if($product_array['product_bulkdiscount_allowed']=='Y')
						{
						?>
							<div class="mid_shlf_pdt_bulk"><img src="<?php url_site_image('bulk-discount.gif')?>" alt="Bulk Discount"/></div>
						<?php
						}
						if($product_array['product_saleicon_show']==1)
						{
							$desc = stripslash_normal(trim($product_array['product_saleicon_text']));
							if($desc!='')
							{
							?>	
								<div class="mid_shlf_pdt_sale"><?php echo $desc?></div>
							<?php
							}
						}
						if($product_array['product_newicon_show']==1)
						{
							$desc = stripslash_normal(trim($product_array['product_newicon_text']));
							if($desc!='')
							{
							?>
								<div class="mid_shlf_pdt_newsale"><?php echo $desc?></div>
							<?php
							}
						}
						?>
						</div>
						<div class="mid_shlf_pdt_price">
						<?php 
						if($product_array['product_freedelivery']==1)
						{	
						?>
							<div class="mid_shlf_free"></div>
						<?php
						}
						
							$price_class_arr['class_type'] 		= 'div';
							$price_class_arr['normal_class'] 	= 'shlf_normalprice';
							$price_class_arr['strike_class'] 	= 'shlf_strikeprice';
							$price_class_arr['yousave_class'] 	= 'shlf_yousaveprice';
							$price_class_arr['discount_class'] 	= 'shlf_discountprice';
							echo show_Price($product_array,$price_class_arr,'shopbrand_1');
						$frm_name = uniqid('shop_');
						?>	
						<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $product_array['product_id']?>)">
						<input type="hidden" name="fpurpose" value="" />
						<input type="hidden" name="fproduct_id" value="" />
						<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
						<input type="hidden" name="fproduct_url" value="<?php url_product($product_array['product_id'],$product_array['product_name'])?>" />
						<div class="mid_shlf_buy">
						<div class="mid_shlf_info_btn"><?php show_moreinfo($product_array,'mid_shlf_info_link')?></div>
						<div class="mid_shlf_buy_btn">
						<?php
						$class_arr 					= array();
						$class_arr['ADD_TO_CART']	= 'mid_shlf_buy_link';
						$class_arr['PREORDER']		= 'mid_shlf_buy_link';
						$class_arr['ENQUIRE']		= 'mid_shlf_buy_link';
						show_addtocart($product_array,$class_arr,$frm_name)
						?>
						</div>
						</div>
						</form>       
						</div>
						</div>
						<div class="mid_shlf_bottom"></div>
					<? 
					}
					?>
					</div>
				<?
				break;
				case '2row':
			?>					
			<div class="mid_shlf2_con" >
					<?php
					$max_col = 2;
					$cur_col = 0;
					foreach( $prodcur_arr as $k=>$product_array)
				    {
				        $prodcurtd_arr[] = $product_array;
						//##############################################################
						// Showing the title, description and image part for the product
						//##############################################################
						if($cur_col == 0)
						{
							echo '<div class="mid_shlf2_con_main">';
						}
						$cur_col ++;
						
						?>
						<div class="mid_shlf2_con_pdt">
						<div class="mid_shlf2_top"></div>
						<div class="mid_shlf2_middle">
						
							<div class="mid_shlf2_pdt_name"><a href="<?php url_product($product_array['product_id'],$product_array['product_name'],-1)?>" title="<?php echo stripslash_normal($product_array['product_name'])?>"><?php echo stripslash_normal($product_array['product_name'])?></a></div>
							<div class="mid_shlf2_pdt_image">
							<a href="<?php url_product($product_array['product_id'],$product_array['product_name'],-1)?>" title="<?php echo stripslash_normal($product_array['product_name'])?>">
							<?php
							// Calling the function to get the image to be shown
							$pass_type = get_default_imagetype('midshelf');
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
							</div>
						<div class="mid_shlf2_free_con">
						<?php
						if($product_array['product_freedelivery']==1)
						{	
						?>
							<div class="mid_shlf2_free"></div>
						<?php
						}
						if($product_array['product_bulkdiscount_allowed']=='Y')
						{
						?>
							<div class="mid_shlf2_pdt_bulk"><img src="<?php url_site_image('bulk-discount.gif')?>" border="0" alt="Bulk Discount" /></div>
						<?php
						}
						?>
						</div>
						<?php
						if($prod_compare_enabled)
						{
						?>
							<div class="mid_shlf2_pdt_compare" >
							<?php	dislplayCompareButton($product_array['product_id']);?>
							</div>
						<?php	
						}
						?>
							<div class="mid_shlf2_pdt_des">
							<?php echo stripslash_normal($product_array['product_shortdesc'])?>
							</div>
						<?php
							$module_name = 'mod_product_reviews';
							if(in_array($module_name,$inlineSiteComponents))
							{
								if($row_prod['product_averagerating']>=0)
								{
							?>
								<div class="mid_shlf_pdt_rate">
								<?php
									display_rating($row_prod['product_averagerating']);
								?>
								</div>
							<?php
								}
							}
						show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points
						if($product_array['product_saleicon_show']==1)
						{
							$desc = stripslash_normal(trim($product_array['product_saleicon_text']));
						if($desc!='')
								{
							?>	
							<div class="mid_shlf2_pdt_sale"><?php echo $desc?></div>
							<?php
							}
						}	
						if($product_array['product_newicon_show']==1)
						{
							$desc = stripslash_normal(trim($product_array['product_newicon_text']));
							if($desc!='')
							{
							?>
							<div class="mid_shlf2_pdt_newsale"><?php echo $desc?></div>
							<?php
							}
						}	
						?>
						
						<div class="mid_shlf2_buy">
						<?php
						$frm_name = uniqid('shopdet_');
						?>	
						<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $product_array['product_id']?>)">
						<input type="hidden" name="fpurpose" value="" />
						<input type="hidden" name="fproduct_id" value="" />
						<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
						<input type="hidden" name="fproduct_url" value="<?php url_product($product_array['product_id'],$product_array['product_name'])?>" />
						<div class="mid_shlf2_info_btn"><?php show_moreinfo($product_array,'mid_shlf2_info_link')?></div>
						<div class="mid_shlf2_buy_btn">
						<?php
						$class_arr 					= array();
						$class_arr['ADD_TO_CART']	= 'mid_shlf2_buy_link';
						$class_arr['PREORDER']		= 'mid_shlf2_buy_link';
						$class_arr['ENQUIRE']		= 'mid_shlf2_buy_link';
						show_addtocart($product_array,$class_arr,$frm_name)
						?>
						</div>
						</form>
						</div>
							<div class="mid_shlf2_pdt_price">
							<?php
							$price_class_arr['class_type'] 		= 'div';
							$price_class_arr['normal_class'] 	= 'shlf2_normalprice';
							$price_class_arr['strike_class'] 	= 'shlf2_strikeprice';
							$price_class_arr['yousave_class'] 	= 'shlf2_yousaveprice';
							$price_class_arr['discount_class'] 	= 'shlf2_discountprice';
							echo show_Price($product_array,$price_class_arr,'shopbrand_3');	
							?>
							</div>
						</div>
						<div class="mid_shlf2_bottom"></div>
						</div>
						<?php
						if($cur_col>=$max_col)
						{
							$cur_col =0;
							echo "</div>";
						}
					}
					// If in case total product is less than the max allowed per row then handle that situation
					if($cur_col<$max_col)
					{
						if($cur_col!=0)
						{ 
						echo "</div>";
						} 
					}
					?>
					
					</div>
				<? 
				break;
				}//end of switchcase
		}
	};	
?>