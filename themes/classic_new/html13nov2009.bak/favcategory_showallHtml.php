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
												a.price_discountprefix, a.price_discountsuffix, a.price_yousaveprefix, a.price_yousavesuffix,a.price_noprice    
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
														END  AS discountval
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
												a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,a.product_variablesaddonprice_exists       
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
			$isProductCompareEnabled = isProductCompareEnabled();
			switch($Settings_arr['favoritecategory_prodlisting'])
			{ 
				case '1row':
				?>
				<div class="shelfBtable">
										<?
				if(count($prodcur_arr))
				{
				?>
											<div class="shelfBheader"><a href="<?php url_category($catid,$catname,-1,$_REQUEST['catgroup_id'],0)?>" class="categoreyname_headerlink" title="<?php echo stripslashes($catname)?>"><?php echo stripslashes($catname)?></a></div> 
										<?php
										}
										      foreach( $prodcur_arr as $k=>$product_array)
												{
												 $prodcurtd_arr[] = $product_array;										
												?>
												<div class="shelfBtabletd">
												<div class="shelfBtabletdinner">
												<div class="shelfBprodname">
												<a href="<?php url_product($product_array['product_id'],$product_array['product_name'],-1)?>" title="<?php echo stripslashes($product_array['product_name'])?>" class="shelfBprodnamelink"><?php echo stripslashes($product_array['product_name'])?></a>
												</div>
												<div class="shelfBleft">
												<a href="<?php url_product($product_array['product_id'],$product_array['product_name'],-1)?>" title="<?php echo stripslashes($product_array['product_name'])?>">
												<?php
													$pass_type = get_default_imagetype('midshelf');
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
												<div class="compare_li">
												<?php
												if($isProductCompareEnabled)
												{
													dislplayCompareButton($product_array['product_id']);
												}
												?>	
												</div> 
												<?php
													$module_name = 'mod_product_reviews';
												?>
												</div>
												<div class="shelfBmid">	
												<h6 class="shelfBproddes"><?php echo stripslashes($product_array['product_shortdesc'])?></h6>
												<?php
												   $price_class_arr['ul_class'] 		= 'shelfBpriceul';
													$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
													$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
													$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
													$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
													echo show_Price($product_array,$price_class_arr,'bestseller_1');
													if($product_array['product_saleicon_show']==1)
													{
														$desc = stripslash_normal(trim($product_array['product_saleicon_text']));
														if($desc!='')
														{
												?>
															<div class="shelfB_sale"><?php echo $desc?></div>
												<?php
														}
													}	
													if($product_array['product_newicon_show']==1)
													{
														$desc = stripslash_normal(trim($product_array['product_newicon_text']));
														if($desc!='')
														{
												?>
															<div class="shelfB_newsale"><?php echo $desc?></div>		
												<?php
														}
													}	
													
												if(in_array($module_name,$inlineSiteComponents))
													{
														if($product_array['product_averagerating']>=0)
														{
														?>
															<div class="shelfB_rate">
															<?php
																display_rating($product_array['product_averagerating']);
															?>
															</div>
														<?php
														}
													}	
													?>						  
												</div>
												<div class="shelfBright"> 
												<?php 
												if($product_array['product_freedelivery']==1)
												{	
												?>
													<div class="shelfB_free"></div>
												<?php
												}
												if($product_array['product_bulkdiscount_allowed']=='Y')
												{
												?>
													<div class="shelfB_bulk"></div>
												<?php
												}
												$frm_name = uniqid('best_');
												?>
												<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $product_array['product_id']?>)">
												<input type="hidden" name="fpurpose" value="" />
												<input type="hidden" name="fproduct_id" value="" />
												<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
												<input type="hidden" name="fproduct_url" value="<?php url_product($product_array['product_id'],$product_array['product_name'])?>" />
												<div class="infodivB">
												<div class="infodivBleft">
												<?php show_moreinfo($product_array,'infolink')?></div>
												<div class="infodivBright">
												<?php
													$class_arr 					= array();
													$class_arr['ADD_TO_CART']	= 'infolinkB';
													$class_arr['PREORDER']		= 'infolinkB';
													$class_arr['ENQUIRE']		= 'infolinkB';
													show_addtocart($product_array,$class_arr,$frm_name)
												?>
												</div>		
												</div>
												</form>
												</div>
												</div>
												</div>	
										<?php
											}
										?>	
										</div>
				<?
				break;
				case '3row':
			?>					
			<div class="shelfAtable" >
									<?
				if(count($prodcur_arr))
				{
				?>
									<div class="shelfAheader"><a href="<?php url_category($catid,$catname,-1,$_REQUEST['catgroup_id'],0)?>" class="categoreyname_headerlink" title="<?php echo stripslashes($catname)?>"><?php echo stripslashes($catname)?></a></div>
									<?
									}
									$max_col = 3;
									$cur_col = 0;
									foreach( $prodcur_arr as $k=>$product_array)
									{
									 $prodcurtd_arr[] = $product_array;
										if($cur_col == 0)
										{ 
										 	echo '<div class="mid_shlf2_con_main">';
										}
										$cur_col ++;
									?>		
																
									<div class="shelfAtabletd">
									<div class="shelfAtabletdinner">
									<ul class="shelfAul">
									<li><h2 class="shelfAprodname"><a href="<?php url_product($product_array['product_id'],$product_array['product_name'],-1)?>" title="<?php echo stripslashes($product_array['product_name'])?>"><?php echo stripslashes($product_array['product_name'])?></a></h2></li>
									<li class="compare_li">
									<?php
									if($isProductCompareEnabled)
									{
										dislplayCompareButton($product_array['product_id']);
									}?>
									</li>														
									<li class="shelfimg">
									<a href="<?php url_product($product_array['product_id'],$product_array['product_name'],-1)?>" title="<?php echo stripslashes($product_array['product_name'])?>">
									<?php
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
									if($product_array['product_freedelivery']==1)
									{
									?>
									<div class="shelfA_free"></div>
									<?php
									}
									if($product_array['product_bulkdiscount_allowed']=='Y')
									{
									?>
									<div class="shelfA_bulk"></div>
									<?php
									}
									?>
									</li>
									<?
									$module_name = 'mod_product_reviews';
									if(in_array($module_name,$inlineSiteComponents))
									{
										if($product_array['product_averagerating']>=0)
										{
										?>
											<li><div class="shelfB_rate">
										<?php
											display_rating($product_array['product_averagerating']);
										?>
											</div></li>
										<?php
										}
									}	
									?>
										<li>
											<?php
											$price_class_arr['ul_class'] 		= 'shelfApriceul';
											$price_class_arr['normal_class'] 	= 'shelfAnormalprice';
											$price_class_arr['strike_class'] 	= 'shelfAstrikeprice';
											$price_class_arr['yousave_class'] 	= 'shelfAyousaveprice';
											$price_class_arr['discount_class'] 	= 'shelfAdiscountprice';
											echo show_Price($product_array,$price_class_arr,'bestseller_3');
											show_excluding_vat_msg($product_array,'vat_div');// show excluding VAT msg
											?>	
										</li>
										<li><h6 class="shelfAproddes"><?php echo stripslashes($product_array['product_shortdesc'])?></h6></li>
									</ul>
									<?
									if($product_array['product_saleicon_show']==1)
									{
										$desc = stripslash_normal(trim($product_array['product_saleicon_text']));
										if($desc!='')
										{
									?>	
										<div class="shelfA_sale"><?php echo $desc?></div>
									<?php
										}
									}
									if($product_array['product_newicon_show']==1)
									{
										$desc = stripslash_normal(trim($product_array['product_newicon_text']));
										if($desc!='')
										{
									?>
										<div class="shelfA_newsale"><?php echo $desc?></div>
									<?php
										}
									}
							    $frm_name = uniqid('mafavhome_');
									
									?>
									<div class="bonus_point"><?php show_bonus_points_msg($product_array,'bonus_point'); // Show bonus points?>
									</div>
									<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $product_array['product_id']?>)">
									<input type="hidden" name="fpurpose" value="" />
									<input type="hidden" name="fproduct_id" value="" />
									<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
									<input type="hidden" name="fproduct_url" value="<?php url_product($product_array['product_id'],$product_array['product_name'])?>" />
									<div class="infodiv">
									<div class="infodivleft">
									<?php show_moreinfo($product_array,'infolink')?></div>
									<div class="infodivright">
									<?php
									$class_arr 					= array();
									$class_arr['ADD_TO_CART']	= 'infolink';
									$class_arr['PREORDER']		= 'infolink';
									$class_arr['ENQUIRE']		= 'infolink';
									show_addtocart($product_array,$class_arr,$frm_name)
									?> 
									</div>		
									</div>
									</form>
									</div>
									</div>
									<?
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