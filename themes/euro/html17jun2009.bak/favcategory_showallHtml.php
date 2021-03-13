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
												a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,a.product_variablesaddonprice_exists       
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
			switch($Settings_arr['favoritecategory_prodlisting'])
			{ 
				case '1row':
				?>
				<div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> </div>
										<div class="shelf_main_con" >
										<? if(count($prodcur_arr)) 
											{			
											?>
											<div class="shelf_top"><a href="<?php url_category($catid,$catname,-1,$_REQUEST['catgroup_id'],0)?>" class="categoreyname_headerlink" title="<?php echo stripslashes($catname)?>"><?php echo stripslashes($catname)?></a></div>
											<div class="shelf_mid">
										<?php
											$cur = 1;
											$tot_cnt = count($prodcur_arr);
											foreach( $prodcur_arr as $k=>$product_array)
											{
											   $prodcurtd_arr[] = $product_array;
												if($cur==$tot_cnt)
													$main_shelf = 'shlf_main_last';
												else
													$main_shelf = 'shlf_main';
												$cur++;
												
										   ?>
												<div class="<?php echo $main_shelf?>">
												<div class="shlf_pdt_img_outr">
												<div class="shlf_pdt_img">
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
												</div>
												<div class="shlf_pdt_compare" >
												<?php if($comp_active)  {
															dislplayCompareButton($product_array['product_id']);
														}?>
												</div>
												</div>
												<div class="shlf_pdt_txt">
												<ul class="shlf_pdt_ul">
												<li class="shlf_pdt_name" ><h3><a href="<?php url_product($product_array['product_id'],$product_array['product_name'],-1)?>" title="<?php echo stripslashes($product_array['product_name'])?>"><?php echo stripslashes($product_array['product_name'])?></a></h3></li>
												<li class="shlf_pdt_des"><h6>	<?php echo stripslashes($product_array['product_shortdesc'])?></h6></li>
												</ul>
												<?php
													$price_class_arr['ul_class'] 		= 'shelf_price_ul';
													$price_class_arr['normal_class'] 	= 'shelfAnormalprice';
													$price_class_arr['strike_class'] 	= 'shelfAstrikeprice';
													$price_class_arr['yousave_class'] 	= 'shelfAyousaveprice';
													$price_class_arr['discount_class'] 	= 'shelfAdiscountprice';
													echo show_Price($product_array,$price_class_arr,'shelfcenter_1');
													show_excluding_vat_msg($product_array,'vat_div');// show excluding VAT msg
													show_bonus_points_msg($product_array,'bonus_point'); // Show bonus points
												    $frm_name = uniqid('shelf_');
												?>
												<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $product_array['product_id']?>)">
												<input type="hidden" name="fpurpose" value="" />
												<input type="hidden" name="fproduct_id" value="" />
												<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
												<input type="hidden" name="fproduct_url" value="<?php url_product($product_array['product_id'],$product_array['product_name'])?>" />
												<div class="infodiv">
												<div class="infodivleft"><?php show_moreinfo($product_array,'infolink')?></div>
												<div class="infodivright">
												<?php
														$class_arr 							= array();
														$class_arr['ADD_TO_CART']	= 'quantity_infolink';
														$class_arr['PREORDER']			= 'quantity_infolink';
														$class_arr['ENQUIRE']			= 'quantity_infolink';
														show_addtocart($product_array,$class_arr,$frm_name)
												?>
												</div>
												</div>
												</form>
												</div>
												</div>
										<?php
											}
										?>
										</div>
										<div class="shelf_bottom"></div>
										<? 
												$prodcurtd_arr = array();

										}?>	
										</div>
				<?
				break;
				case '3row':
				
			?>					
			<div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a></div>
										<div class="shelfA_main_con" > 
										<? if(count($prodcur_arr)) 
											{	
											?>
										<div class="shelfA_top"><a href="<?php url_category($catid,$catname,-1,$_REQUEST['catgroup_id'],0)?>" class="categoreyname_headerlink" title="<?php echo stripslashes($catname)?>"><?php echo stripslashes($catname)?></a></div>
										<div class="shelfA_mid">
										<?php
											$max_col = 3;
											$cur_col = 0;
											$cur_tot_cnt = count($prodcur_arr);
											$cur = 1;
											foreach( $prodcur_arr as $k=>$product_array)
											{
											   $prodcurtd_arr[] = $product_array;
												if($cur>($cur_tot_cnt-$max_col))
													$main_shelf = 'shlfA_main_last';
												else
													$main_shelf = 'shlfA_main';
												$cur++;
												//##############################################################
												// Showing the title, description and image part for the product
												//##############################################################
												if($cur_col==0)
												{
													$main_inner_shelf = 'shlfA_inner_main_lst';
										?>
													 <div class="<?php echo $main_shelf?>">
										<?php
												}
												else
													$main_inner_shelf = 'shlfA_inner_main';
										?>
												<div class="<?php echo $main_inner_shelf?>">
													<div class="shlfA_pdt_img">
													<a href="<?php url_product($product_array['product_id'],$product_array['product_name'],-1)?>" title="<?php echo stripslashes($product_array['product_name'])?>">
													<?php
														$pass_type = get_default_imagetype('midshelf');
														// Calling the function to get the type of image to shown for current 
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
													 </div>		
												  <?php 
												  if($comp_active) 
												  {
												  ?>
												  	<div class="shlfA_pdt_compare" >
												<?php
														dislplayCompareButton($product_array['product_id']);
												?>		
													</div>
												<?php
													}?>
												  <div class="shlfA_pdt_txt">
																	  <ul class="shlfA_pdt_ul">
															<li class="shlfA_pdt_name" ><h3><a href="<?php url_product($product_array['product_id'],$product_array['product_name'],-1)?>" title="<?php url_product($product_array['product_id'],$product_array['product_name'],-1)?>"><?php echo stripslashes($product_array['product_name'])?></a></h3></li>
																<li class="shlfA_pdt_des" ><h6><?php echo stripslashes($product_array['product_shortdesc'])?></h6></li>
														<li class='shlfA_pdt_bonus'><h6><?php show_bonus_points_msg($product_array,'bonus_point'); // Show bonus points?></h6></li>
														</ul>

																<li>
																	<?php
																		$price_class_arr['ul_class'] 		= 'shelfA_price_ul';
																		$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
																		$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
																		$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
																		$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
																		echo show_Price($product_array,$price_class_arr,'shelfcenter_3');
																		show_excluding_vat_msg($product_array,'vat_div');// show excluding VAT msg
																	?>	
																</li>
														<?php
															$frm_name = uniqid('best_');
															?>
															<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $product_array['product_id']?>)">
															<input type="hidden" name="fpurpose" value="" />
															<input type="hidden" name="fproduct_id" value="" />
															<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
															<input type="hidden" name="fproduct_url" value="<?php url_product($product_array['product_id'],$product_array['product_name'])?>" />
															<div class="infodivB">
																<div class="infodivleftB"><?php show_moreinfo($product_array,'infolinkB')?></div>
																<div class="infodivrightB">
																<?php
																		$class_arr 							= array();
																		$class_arr['ADD_TO_CART']	= 'quantity_infolinkB';
																		$class_arr['PREORDER']			= 'quantity_infolinkB';
																		$class_arr['ENQUIRE']			= 'quantity_infolinkB';
																		show_addtocart($product_array,$class_arr,$frm_name)
																	?>
																</div>
															</div>
															</form>
												  </div>
												  </div>
											<?php
												$cur_col++;
												if ($cur_col>=$max_col)
												{
												?>
													</div>
												<?php
													 $cur_col = 0;
												}	 
											}
											if ($cur_col<$max_col and $cur_col>0)
											{
												echo '</div>';
											}
											?>
										</div>
										<div class="shelfA_bottom"></div>
										<? 
										$prodcurtd_arr = array();
										}?>
										</div>
				<? 
				break;
				}//end of switchcase
		}
	};	
?>