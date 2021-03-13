<?php
/*############################################################################
	# Script Name 	: myfavoritesHtml.php
	# Description 	: Page which holds the display logic for listing my favorite categories and products
	# Coded by 		: ANU
	# Created on	: 02-Feb-2008
	# Modified by	: 
	# Modified On	: 
	##########################################################################*/
	class myhome_Html
	{
		// Defining function to show the site review
		function Show_MyhomeFavoriteCategories($ret_favcat,$tot_cntcateg,$start_varcateg,$pg_variablecateg)
		{
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$ecom_themename,$Settings_arr,$ecom_themeid,$default_layout,$inlineSiteComponents,$customer_id,$ids;
			
			$sql_last_login = "SELECT customer_last_login_date FROM customers WHERE sites_site_id=$ecom_siteid AND customer_id=$customer_id";
			$ret_last_login = $db->query($sql_last_login);
			list($row_last_login) = $db->fetch_array($ret_last_login);
		
			if(in_array('mod_catimage',$inlineSiteComponents))
			{
				$img_support = true;
			}
			else
				$img_support = false;
		?>
<div class="shelfA_main_con" > 
<?php
while($row_favcat = $db->fetch_array($ret_favcat)) 
{
	$prodcur_arr =array();
	$limit			= $Settings_arr['product_limit_homepage_favcat_recent'];
	if($limit==0)
	$limit = 3;
	//Taking the New products added in the category after customer's last login	
	$sql_prod_first = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
										a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
										a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
										product_total_preorder_allowed,a.product_applytax,a.product_shortdesc,product_bonuspoints ,
										a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,a.product_variablesaddonprice_exists     
									FROM 
										products a,product_category_map b 
									WHERE 
										b.product_categories_category_id = ".$row_favcat['category_id']." 
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
													AND b.product_categories_category_id = ".$row_favcat['category_id']." 
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
										b.product_categories_category_id = ".$row_favcat['category_id']." 
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
	
		if(count($prodcur_arr))
		{
		?>
		<div class="shelfA_top"><a href="<?php url_category($row_favcat['category_id'],$row_favcat['category_name'],-1,$_REQUEST['catgroup_id'],0)?>" class="categoreyname_headerlink" title="<?php echo stripslashes($row_favcat['category_name'])?>"><?php echo stripslashes($row_favcat['category_name'])?></a></div>
											<div class="shelfA_mid">

	  <?
			$max_col = 3;
			$cur_col = 0;
			$cur_tot_cnt = count($prodcur_arr);
			$cur = 1;
		//$prodcur_arr = array();
		
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
											}//End For
	
		// If in case total product is less than the max allowed per row then handle that situation
		
		$prodcurtd_arr = array();
	
		if ($cur_col<$max_col and $cur_col>0)
											{
												echo '</div>';
											}
											?>
										<div class="show_all" align="right"><h6 align="right"><a href="<?php  url_category_all($row_favcat['category_id'],$row_favcat['category_name'],-1)?>" class="showall" title="<?php echo $Captions_arr['COMMON']['SHOW_ALL']?>"><? echo $Captions_arr['COMMON']['SHOW_ALL']?></a></h6></div>
										</div>
										<div class="shelfA_bottom"></div>
										<? 
										}?>
										

	   <?
	
	}//Endwhile
	
	?>
</div>
		<?php
		return $ids_in;
	}

/////////////**********TO DISPLAY FAVORITE PRODUCTS**********///////////////
		// ** Function to list the products
		function Show_MyhomeFavoriteProducts($ret_fav_products,$tot_cntprod,$start_varprod,$pg_variableprod)
		{
		global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$ecom_themename,$Settings_arr,$ecom_themeid,$default_layout,$inlineSiteComponents,$ids_in;

		?>
   	<div class="shelfA_main_con" > 

				<?php
						// ** Showing the products listing either 1 in a row or 3 in a row
						//$pg_variable = 'catdet_pg';
					$displaytype = $Settings_arr['favorite_prodlisting'];
						switch($displaytype)
						{
							case '1row': // case of one in a row for normal
							?>
								<div class="shelf_top">	<?php
								if ($db->num_rows($ret_fav_products)==1)
								echo $Captions_arr['LOGIN_HOME']['LOGIN_HOME_FAV_PRODUCT_HEADER'];
								else
								echo $Captions_arr['LOGIN_HOME']['LOGIN_HOME_FAV_PRODUCTS_HEADER'];
								?>	
								</div>
										<?php 
										if($Captions_arr['PREORDER']['PREORDER_CAPTION']!='')
										{
										?>
											<div class="shelf_top"><?php echo $Captions_arr['PREORDER']['PREORDER_CAPTION']!=''?></div>
										<?
										}
										?>
											<div class="shelf_mid">
										<?php
											if ($tot_cnt>0)
											{
											?>
												<div class="pagingcontainertd" align="center">
													<?php 
														$path = '';
														$query_string .= "disp_id=".$_REQUEST['disp_id'];
														paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr); 	
													?>
													</div>
											<?php
											}
											
											$cur = 1;
											
									     while($row_prod = $db->fetch_array($ret_fav_products))
											{
												if($cur==$tot_cntprod)
													$main_shelf = 'shlf_main_last';
												else
													$main_shelf = 'shlf_main';
												$cur++;
												
										?>
												<div class="<?php echo $main_shelf?>">
												<div class="shlf_pdt_img_outr">
												<div class="shlf_pdt_img">
													<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
													<?php
													$pass_type = get_default_imagetype('fav_prod');
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
													</a>
												</div>
												<div class="shlf_pdt_compare" >
												<?php if($comp_active)  {
															dislplayCompareButton($row_prod['product_id']);
														}?>
												</div>
												</div>
												<div class="shlf_pdt_txt">
												<ul class="shlf_pdt_ul">
												<li class="shlf_pdt_name" ><h3><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h3></li>
												<li class="shlf_pdt_des"><h6>	<?php echo stripslashes($row_prod['product_shortdesc'])?></h6></li>
												</ul>
												<?php
													$price_class_arr['ul_class'] 		= 'shelf_price_ul';
													$price_class_arr['normal_class'] 	= 'shelfAnormalprice';
													$price_class_arr['strike_class'] 	= 'shelfAstrikeprice';
													$price_class_arr['yousave_class'] 	= 'shelfAyousaveprice';
													$price_class_arr['discount_class'] 	= 'shelfAdiscountprice';
													echo show_Price($row_prod,$price_class_arr,'shelfcenter_1');
													show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
													show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points
													$frm_name = uniqid('best_');
												?>
												<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
												<input type="hidden" name="fpurpose" value="" />
												<input type="hidden" name="fproduct_id" value="" />
												<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
												<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
												<div class="infodiv">
												<div class="infodivleft"><?php show_moreinfo($row_prod,'infolink')?></div>
												<div class="infodivright">
												<?php
														$class_arr 							= array();
														$class_arr['ADD_TO_CART']	= 'quantity_infolink';
														$class_arr['PREORDER']			= 'quantity_infolink';
														$class_arr['ENQUIRE']			= 'quantity_infolink';
														show_addtocart($row_prod,$class_arr,$frm_name)
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
										</div>
							<?php
							break;
							case '3row': // case of three in a row for normal
							?>
						    <div class="shelfA_top">	<?php
								if ($db->num_rows($ret_fav_products)==1)
								echo $Captions_arr['LOGIN_HOME']['LOGIN_HOME_FAV_PRODUCT_HEADER'];
								else
								echo $Captions_arr['LOGIN_HOME']['LOGIN_HOME_FAV_PRODUCTS_HEADER'];
								?>	
								</div>

										<div class="shelfA_mid">
										<?php
										if ($tot_cnt>0)
										{
										?>
											<div class="pagingcontainertd">
												<?php 
													$path = '';
													$query_string .= "disp_id=".$_REQUEST['disp_id'];
													paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr); 	
												?>		
											</div>
										<?php
										}
											$max_col = 3;
											$cur_col = 0;
											$prodcur_arr = array();
											$cur_tot_cnt = $db->num_rows($ret_fav_products);
											$cur = 1;
								         	while($row_prod = $db->fetch_array($ret_fav_products))
											{
												$prodcur_arr[] = $row_prod;
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
													<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
													<?php
														$pass_type = get_default_imagetype('fav_prod');
														// Calling the function to get the type of image to shown for current 
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
														</a>
													 </div>		
												  <?php 
												  if($comp_active) 
												  {
												  ?>
												  	<div class="shlfA_pdt_compare" >
												<?php
														dislplayCompareButton($row_prod['product_id']);
												?>		
													</div>
												<?php
													}?>
												  <div class="shlfA_pdt_txt">
																	  <ul class="shlfA_pdt_ul">
															<li class="shlfA_pdt_name" ><h3><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>"><?php echo stripslashes($row_prod['product_name'])?></a></h3></li>
																<li class="shlfA_pdt_des" ><h6><?php echo stripslashes($row_prod['product_shortdesc'])?></h6></li>
														<li class='shlfA_pdt_bonus'><h6><?php show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points?></h6></li>
														</ul>

																<li>
																	<?php
																		$price_class_arr['ul_class'] 		= 'shelfA_price_ul';
																		$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
																		$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
																		$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
																		$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
																		echo show_Price($row_prod,$price_class_arr,'shelfcenter_3');
																		show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
																	?>	
																</li>
														<?php
															$frm_name = uniqid('best_');
															?>
															<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
															<input type="hidden" name="fpurpose" value="" />
															<input type="hidden" name="fproduct_id" value="" />
															<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
															<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
															<div class="infodivB">
																<div class="infodivleftB"><?php show_moreinfo($row_prod,'infolinkB')?></div>
																<div class="infodivrightB">
																<?php
																		$class_arr 							= array();
																		$class_arr['ADD_TO_CART']	= 'quantity_infolinkB';
																		$class_arr['PREORDER']			= 'quantity_infolinkB';
																		$class_arr['ENQUIRE']			= 'quantity_infolinkB';
																		show_addtocart($row_prod,$class_arr,$frm_name)
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
										</div>
						<?php		
							break;
						};
				?>
				</div>
		
	<?	}
		
function Show_MyhomePurcahaseProducts($ret_purchase)
		{
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$ecom_themename,$Settings_arr,$ecom_themeid,$default_layout,$inlineSiteComponents;
		   if($db->num_rows($ret_purchase)>0)
			{
			?>
										<div class="shelfA_main_con" > 
										<div class="shelfA_top"><?php echo $Captions_arr['LOGIN_HOME']['LOGIN_HOME_PURCHASE_HEADING'];?></div>
									    <div class="shelfA_mid">	

										<?php
											$max_col = 3;
											$cur_col = 0;
											$prodcur_arr = array();
											$cur_tot_cnt = $db->num_rows($ret_purchase);
											$cur = 1;
											while($row_prod = $db->fetch_array($ret_purchase))
											{
												$prodcur_arr[] = $row_prod;
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
													<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
													<?php
														$pass_type = get_default_imagetype('midshelf');
														// Calling the function to get the type of image to shown for current 
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
														</a>
													 </div>		
												  <?php 
												  if($comp_active) 
												  {
												  ?>
												  	<div class="shlfA_pdt_compare" >
												<?php
														dislplayCompareButton($row_prod['product_id']);
												?>		
													</div>
												<?php
													}?>
												  <div class="shlfA_pdt_txt">
																	  <ul class="shlfA_pdt_ul">
															<li class="shlfA_pdt_name" ><h3><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>"><?php echo stripslashes($row_prod['product_name'])?></a></h3></li>
																<li class="shlfA_pdt_des" ><h6><?php echo stripslashes($row_prod['product_shortdesc'])?></h6></li>
														<li class='shlfA_pdt_bonus'><h6><?php show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points?></h6></li>
														</ul>

																<li>
																	<?php
																		$price_class_arr['ul_class'] 		= 'shelfA_price_ul';
																		$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
																		$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
																		$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
																		$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
																		echo show_Price($row_prod,$price_class_arr,'shelfcenter_3');
																		show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
																	?>	
																</li>
														<?php
															$frm_name = uniqid('best_');
															?>
															<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
															<input type="hidden" name="fpurpose" value="" />
															<input type="hidden" name="fproduct_id" value="" />
															<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
															<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
															<div class="infodivB">
																<div class="infodivleftB"><?php show_moreinfo($row_prod,'infolinkB')?></div>
																<div class="infodivrightB">
																<?php
																		$class_arr 							= array();
																		$class_arr['ADD_TO_CART']	= 'quantity_infolinkB';
																		$class_arr['PREORDER']			= 'quantity_infolinkB';
																		$class_arr['ENQUIRE']			= 'quantity_infolinkB';
																		show_addtocart($row_prod,$class_arr,$frm_name)
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
										<div class="show_all" align="right"><h6><a href="http://<?=$ecom_hostname?>/showpurchaseall.html" class="showall" title="<?php echo $Captions_arr['COMMON']['SHOW_ALL']?>"><? echo $Captions_arr['COMMON']['SHOW_ALL']?></a></h6></div>
									    </div>
										<div class="shelfA_bottom"></div>
										</div>													

											
			<?
			
			 }//end Checking
         }

///////////*************END FAVORITE PRODUCTS**************//////////////////

		function Display_WelcomeMessage($mesgHeader,$Message){
		global $Captions_arr,$ecom_hostname,$db,$ecom_siteid;
		$sql_user = "SELECT customer_fname,customer_discount,customer_allow_product_discount FROM customers where customer_id = ".get_session_var("ecom_login_customer")." LIMIT 1";
		$ret_user = $db->query($sql_user);
		list($username,$customer_discount,$allow_discount) = $db->fetch_array($ret_user);
		?>
		<div class="treemenu"><a href="<? url_link('')?>">Home</a> >> <?php echo  $Captions_arr['LOGIN_HOME']['LOGIN_HOME_TREE_MENU']?></div>
     <div class="loginwelcomemsg_header" > 
       	 <?php echo  str_replace("[username]",$username,$Captions_arr['LOGIN_HOME']['LOGIN_HOME_HEADER_MESG']);?>
		 </div>
	  <?php
	  	if($Captions_arr['LOGIN_HOME']['LOGIN_HOME_MESG_TEXT']!='')
		{
	  ?>
		 <div class="loginwelcomemsg_text"> <?php echo  str_replace("[username]",$username,$Captions_arr['LOGIN_HOME']['LOGIN_HOME_MESG_TEXT']);?></div>
	  <?php
	  	}
		?>
		<div class="shelfA_main_con" > 
		<?
		$cnt = 0;
			 $sql_assigned="SELECT 
			  							customer_discount_group_cust_disc_grp_id 
									FROM 
										customer_discount_customers_map 
									WHERE 
										sites_site_id = ".$ecom_siteid." 
									AND 
										customers_customer_id=".get_session_var("ecom_login_customer")."";
			$ret_assigned = $db->query($sql_assigned);
			
			$num_assigned = $db->num_rows($ret_assigned); // To get Number OF Rows
      		if($num_assigned>0) 
			{
				while($row_assigned =$db->fetch_array($ret_assigned))
				{ 
				$cnt ++;
				$group_id = $row_assigned['customer_discount_group_cust_disc_grp_id'];
				if($group_id)
				{
					$sql_discount = "SELECT 
											cust_disc_grp_discount 
										FROM 
											customer_discount_group 
										WHERE 
											cust_disc_grp_id=".$group_id." AND cust_disc_grp_active=1 LIMIT 1";
					$ret_discount = $db->query($sql_discount);
					$row_discount = $db->fetch_array($ret_discount);
					$sql_products_id = "SELECT 
											DISTINCT pc.products_product_id,p.product_id,p.product_name,p.product_shortdesc,p.product_webprice,
											p.product_discount,p.product_discount_enteredasval,p.product_bulkdiscount_allowed,p.product_variables_exists,p.product_variablesaddonprice_exists    
										FROM 
											customer_discount_group_products_map pc,products p 
										WHERE 
											pc.customer_discount_group_cust_disc_grp_id=".$group_id." 
										 AND p.product_hide='N' AND pc.products_product_id=p.product_id";
					$ret_products_id = $db->query($sql_products_id);
					}
					$flag=1;
					if($db->num_rows($ret_products_id)>0)
					{
						if($allow_discount==1 && $row_discount['cust_disc_grp_discount']>0 && $customer_discount>0)
						{
							$flag=2;
						}
					}
					else
					{
					 if($row_discount['cust_disc_grp_discount']>0 )
						$flag=2;
					 elseif($customer_discount>0)
					 {
						$flag=2;
					 }
					}
					if($cnt==1)
					{
						if($flag==2)
						{
						?>
						
						<div class="logindetailheader" ><?=$Captions_arr['LOGIN_HOME']['LOGIN_HOME_DISC_DETAILS']?></div>
						
						<?
						}
					}
					if($db->num_rows($ret_products_id )>0 || !$group_id || $row_discount['cust_disc_grp_discount']==0)
					{
					 if($cnt==1)
					 {		
						 if($customer_discount>0)
						 {
						 ?> 
						 <div class="shlfA_pdt_txt">
						 <ul class="loginwelcomemsg_text" >
						 <li><?=$Captions_arr['LOGIN_HOME']['LOGIN_HOME_NORMAL_DISC']?></li>
						 <li>:&nbsp;<?=$customer_discount.'%'?></li>
						 </ul></div>
						  <?
						  }
					  }  
					 }
					 elseif($db->num_rows($ret_products_id )==0 && $group_id)
					 {	
						if($row_discount['cust_disc_grp_discount']>0)
						{
							?>
						<div  class="loginwelcomemsg_text" ><?=$Captions_arr['LOGIN_HOME']['LOGIN_HOME_GORUP_DISC']?>
						</div>
						<div class="loginwelcomemsg_text">:&nbsp;<?=$row_discount['cust_disc_grp_discount'].'%'?></div>
							<?
						 }
					 }	 
					  if($row_discount['cust_disc_grp_discount']>0)
							{   
							$Cnt_prd=0;
							?>
									
										<? if($db->num_rows($ret_products_id )>0)
											{
											if($allow_discount==1)
											{
											 $homemsg = str_replace("[value]", $row_discount['cust_disc_grp_discount'].'%', $Captions_arr['LOGIN_HOME']['LOGIN_HOME_GORUP_PRODUCTS']);
										 ?>
											<div  class="shelf_top"  colspan="3"><?=$homemsg?></div>
											
											<?
											 $tot_cnt=$db->num_rows($ret_products_id );
											$ids = array();
											while($row_prod = $db->fetch_array($ret_products_id))
											{
												//sunil
												$ids[] = $row_prod['product_id'];
											
												if($cur==$tot_cnt)
													$main_shelf = 'shlf_main_last';
												else
													$main_shelf = 'shlf_main';
												$cur++;
												
										?>      <div class="shelf_mid">
												<div class="<?php echo $main_shelf?>">
												<div class="shlf_pdt_img_outr">
												<div class="shlf_pdt_img">
													<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
													<?php
														$pass_type = get_default_imagetype('fav_prod');

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
													</a>
												</div>
												<div class="shlf_pdt_compare" >
												<?php if($comp_active)  {
															dislplayCompareButton($row_prod['product_id']);
														}?>
												</div>
												</div>
												<div class="shlf_pdt_txt">
												<ul class="shlf_pdt_ul">
												<li class="shlf_pdt_name" ><h3><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h3></li>
												<li class="shlf_pdt_des"><h6>	<?php echo stripslashes($row_prod['product_shortdesc'])?></h6></li>
												</ul>
												<?php
													$price_class_arr['ul_class'] 		= 'shelf_price_ul';
													$price_class_arr['normal_class'] 	= 'shelfAnormalprice';
													$price_class_arr['strike_class'] 	= 'shelfAstrikeprice';
													$price_class_arr['yousave_class'] 	= 'shelfAyousaveprice';
													$price_class_arr['discount_class'] 	= 'shelfAdiscountprice';
													echo show_Price($row_prod,$price_class_arr,'shelfcenter_1');
													show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
													show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points
													$frm_name = uniqid('best_');
												?>
												<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
												<input type="hidden" name="fpurpose" value="" />
												<input type="hidden" name="fproduct_id" value="" />
												<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
												<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
												<div class="infodiv">
												<div class="infodivleft"><?php show_moreinfo($row_prod,'infolink')?></div>
												<?php /*?><div class="infodivright">
												<?php
														$class_arr 							= array();
														$class_arr['ADD_TO_CART']	= 'quantity_infolink';
														$class_arr['PREORDER']			= 'quantity_infolink';
														$class_arr['ENQUIRE']			= 'quantity_infolink';
														show_addtocart($row_prod,$class_arr,$frm_name)
												?>
												</div><?php */?>
												</div>
												</div>
												</form>
												</div>
												</div>
										<?php
											 }
											  }
											 }
							?>
							</div>
							<?
							}
					}
				}
				else
				{
				 if($customer_discount>0)
				 {
				 ?>
				  <div class="loginwelcomemsg_text" width="40%"><?=$Captions_arr['LOGIN_HOME']['LOGIN_HOME_NORMAL_DISC']?></div><div class="loginwelcomemsg_text">:&nbsp;<?=$customer_discount.'%'?>
				  </div>
				 <?
				  }
				}
			  ?>
		  </div>
		<?php
			return $ids;	
		}
		function Display_Message($mesgHeader,$Message){
		
		?>
				<div class="shelfA_main_con" > 
				<div class="message_header" > 
					 <?php echo $mesgHeader;?></div>
				  <div  class="message"><?php echo $Message; ?></div>
				   </div>
		<?php	
		}
		
	};	
?>
			