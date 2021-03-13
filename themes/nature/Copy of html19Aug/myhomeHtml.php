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
					<div class="mid_shlf_hdr_middle" ><a href="<?php url_category($row_favcat['category_id'],$row_favcat['category_name'],-1,$_REQUEST['catgroup_id'],0)?>" class="categoreyname_headerlink" title="<?php echo stripslashes($row_favcat['category_name'])?>"><?php echo stripslashes($row_favcat['category_name'])?></a></div>
				    <div class="mid_shlf2_con" >
								<?php
								$max_col = 2;
								$cur_col = 0;
					foreach($prodcur_arr as $k=>$product_array)
								{
					            $prodcur_arr[] = $product_array;
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
									
										<div class="mid_shlf2_pdt_name"><a href="<?php url_product($product_array['product_id'],$product_array['product_name'],-1)?>" title="<?php echo stripslashes($product_array['product_name'])?>"><?php echo stripslashes($product_array['product_name'])?></a></div>
										<div class="mid_shlf2_pdt_image">
										<a href="<?php url_product($product_array['product_id'],$product_array['product_name'],-1)?>" title="<?php echo stripslashes($product_array['product_name'])?>">
										<?php
										// Calling the function to get the image to be shown
										$pass_type = get_default_imagetype('fav_prod');
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
									if($comp_active)
									{
									?>
										<div class="mid_shlf2_pdt_compare" >
										<?php	dislplayCompareButton($product_array['product_id']);?>
										</div>
									<?php	
									}
									?>
										<div class="mid_shlf2_pdt_des">
										<?php echo stripslashes($product_array['product_shortdesc'])?>
										</div>
									<?php
									if($product_array['product_saleicon_show']==1)
									{
										$desc = stripslashes(trim($product_array['product_saleicon_text']));
									if($desc!='')
											{
										?>	
										<div class="mid_shlf2_pdt_sale"><?php echo $desc?></div>
										<?php
										}
									}	
									if($product_array['product_newicon_show']==1)
									{
										$desc = stripslashes(trim($product_array['product_newicon_text']));
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
									<?php
										?>
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
					<div class="fav_showall"><h6 align="right"><a href="<?php  url_category_all($row_favcat['category_id'],$row_favcat['category_name'],-1)?>" class="showall" title="<?php echo $Captions_arr['COMMON']['SHOW_ALL']?>"><? echo $Captions_arr['COMMON']['SHOW_ALL']?></a></h6></div>
				   <?
					}//End the prod checking
				
				}//Endwhile
	
	?>
		<?php
		return $ids_in;
	}

/////////////**********TO DISPLAY FAVORITE PRODUCTS**********///////////////
		// ** Function to list the products
		function Show_MyhomeFavoriteProducts($ret_fav_products,$tot_cntprod,$start_varprod,$pg_variableprod)
		{
		global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$ecom_themename,$Settings_arr,$ecom_themeid,$default_layout,$inlineSiteComponents,$ids_in;
		?>
		<div class="list_page_con">
			<div class="list_page_top"></div>
			<div class="list_page_middle">
			<div class="pagingcontainertd" >
				<div class="pro_nav_pages" align="right"><?php echo paging_show_totalcount($tot_cntprod,'Product(s)',$start_var['pg'],$start_var['pages'])?></div>
				<div class="pro_nav_links" align="right">
					<?php 
					 $pg_variable = 'shopdet_pg';
						if ($tot_cntprod>0)
						{
														$path = '';
														$pageclass_arr['container'] = 'pagenavcontainer';
														$pageclass_arr['navvul']	= 'pagenavul';
														$pageclass_arr['current']	= 'pagenav_current';
														$query_string .= '&categ_pg='.$_REQUEST['categ_pg'];
														paging_footer($path,$query_string,$tot_cntprod,$start_varprod['pg'],$start_varprod['pages'],'',$pg_variableprod,'Favourite products',$pageclass_arr,0); 
						}
						?>	
					</div>
				</div>
			</div>
	   		<div class="list_page_bottom"></div>
			</div>
				<div class="pro_de_shelfBheader" align="left"> <?php
				  	if ($db->num_rows($ret_fav_products)==1)
						echo $Captions_arr['LOGIN_HOME']['LOGIN_HOME_FAV_PRODUCT_HEADER'];
					else
						echo $Captions_arr['LOGIN_HOME']['LOGIN_HOME_FAV_PRODUCTS_HEADER'];
				  ?>
				  </div>
				 <?php
						// ** Showing the products listing either 1 in a row or 3 in a row
						//$pg_variable = 'catdet_pg';
					    $displaytype = $Settings_arr['favorite_prodlisting'];
						switch($displaytype)
						{
							case '1row': // case of one in a row for normal
							?>
								<div class="mid_shlf_con" >
						<?php
						while($row_prod = $db->fetch_array($ret_fav_products))
						{
							?>
							<div class="mid_shlf_top"></div>
							<div class="mid_shlf_middle">
								<div class="mid_shlf_pdt_name"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></div>
								<div class="mid_shlf_mid">
								<div class="mid_shlf_pdt_image">
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
									<? 
								if($prod_compare_enabled)
								{
								?>
									<div class="mid_shlf_pdt_compare" >
									<?php	dislplayCompareButton($row_prod['product_id']);?>
									</div>
								<?php	
								}
								?>
								</div>
								</div>
								<div class="mid_shlf_pdt_des">
								<?php
								echo stripslashes($row_prod['product_shortdesc']);
								$module_name = 'mod_product_reviews';
								if(in_array($module_name,$inlineSiteComponents))
								{
									if($row_prod['product_averaterating']>=0)
									{
									?>
									<div class="mid_shlf_pdt_rate">
									<?php
									for ($i=0;$i<$row_prod['product_averagerating'];$i++)
									{
									echo '<img src="'.url_site_image('star-red.gif',1).'" border="0" alt="revscoreimg" />&nbsp;'; 
									}
									?>
									</div>
									<?php
									}
								}	
								if($row_prod['product_bulkdiscount_allowed']=='Y')
								{
								?>
									<div class="mid_shlf_pdt_bulk"><img src="<?php url_site_image('bulk-discount.gif')?>" alt="Bulk Discount"/></div>
								<?php
								}
								if($row_prod['product_saleicon_show']==1)
								{
									$desc = stripslashes(trim($row_prod['product_saleicon_text']));
									if($desc!='')
									{
									?>	
										<div class="mid_shlf_pdt_sale"><?php echo $desc?></div>
									<?php
									}
								}
								if($row_prod['product_newicon_show']==1)
								{
									$desc = stripslashes(trim($row_prod['product_newicon_text']));
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
								if($row_prod['product_freedelivery']==1)
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
									echo show_Price($row_prod,$price_class_arr,'shopbrand_1');
								$frm_name = uniqid('shop_');
								?>	
								<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
								<input type="hidden" name="fpurpose" value="" />
								<input type="hidden" name="fproduct_id" value="" />
								<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
								<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
								<div class="mid_shlf_buy">
								<div class="mid_shlf_info_btn"><?php show_moreinfo($row_prod,'mid_shlf_info_link')?></div>
								<div class="mid_shlf_buy_btn">
								<?php
								$class_arr 					= array();
								$class_arr['ADD_TO_CART']	= 'mid_shlf_buy_link';
								$class_arr['PREORDER']		= 'mid_shlf_buy_link';
								$class_arr['ENQUIRE']		= 'mid_shlf_buy_link';
								show_addtocart($row_prod,$class_arr,$frm_name)
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
							<?php
							break;
							case '2row': // case of three in a row for normal
							?>
								<div class="mid_shlf2_con" >
						<?php
						$max_col = 2;
						$cur_col = 0;
						$prodcur_arr = array();
						while($row_prod = $db->fetch_array($ret_fav_products))
						{
						$prodcur_arr[] = $row_prod;
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
						
							<div class="mid_shlf2_pdt_name"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></div>
							<div class="mid_shlf2_pdt_image">
							<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
							<?php
							// Calling the function to get the image to be shown
							$pass_type = get_default_imagetype('fav_prod');
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
						<div class="mid_shlf2_free_con">
						<?php
						if($row_prod['product_freedelivery']==1)
						{	
						?>
							<div class="mid_shlf2_free"></div>
						<?php
						}
						if($row_prod['product_bulkdiscount_allowed']=='Y')
						{
						?>
							<div class="mid_shlf2_pdt_bulk"><img src="<?php url_site_image('bulk-discount.gif')?>" border="0" alt="Bulk Discount" /></div>
						<?php
						}
						?>
						</div>
						<?php
						if($comp_active)
						{
						?>
							<div class="mid_shlf2_pdt_compare" >
							<?php	dislplayCompareButton($row_prod['product_id']);?>
							</div>
						<?php	
						}
						?>
							<div class="mid_shlf2_pdt_des">
							<?php echo stripslashes($row_prod['product_shortdesc'])?>
							</div>
						<?php
						if($row_prod['product_saleicon_show']==1)
						{
							$desc = stripslashes(trim($row_prod['product_saleicon_text']));
						if($desc!='')
								{
							?>	
							<div class="mid_shlf2_pdt_sale"><?php echo $desc?></div>
							<?php
							}
						}	
						if($row_prod['product_newicon_show']==1)
						{
							$desc = stripslashes(trim($row_prod['product_newicon_text']));
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
						<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
						<input type="hidden" name="fpurpose" value="" />
						<input type="hidden" name="fproduct_id" value="" />
						<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
						<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
						<div class="mid_shlf2_info_btn"><?php show_moreinfo($row_prod,'mid_shlf2_info_link')?></div>
						<div class="mid_shlf2_buy_btn">
						<?php
						$class_arr 					= array();
						$class_arr['ADD_TO_CART']	= 'mid_shlf2_buy_link';
						$class_arr['PREORDER']		= 'mid_shlf2_buy_link';
						$class_arr['ENQUIRE']		= 'mid_shlf2_buy_link';
						show_addtocart($row_prod,$class_arr,$frm_name)
						?>
						</div>
						</form>
						</div>
						<?php
							?>
							<div class="mid_shlf2_pdt_price">
							<?php
							$price_class_arr['class_type'] 		= 'div';
							$price_class_arr['normal_class'] 	= 'shlf2_normalprice';
							$price_class_arr['strike_class'] 	= 'shlf2_strikeprice';
							$price_class_arr['yousave_class'] 	= 'shlf2_yousaveprice';
							$price_class_arr['discount_class'] 	= 'shlf2_discountprice';
							echo show_Price($row_prod,$price_class_arr,'shopbrand_3');	
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
						<?php		
							break;
						};
				?>
				<div class="list_page_con">
					<div class="list_page_top"></div>
					<div class="list_page_middle">
					<div class="pagingcontainertd" >
					<div class="pro_nav_pages" align="right"><?php echo paging_show_totalcount($tot_cntprod,'Product(s)',$start_var['pg'],$start_var['pages'])?></div>
					<div class="pro_nav_links" align="right">
					<?php 
					 $pg_variable = 'shopdet_pg';
						if ($tot_cntprod>0)
						{
														$path = '';
														$pageclass_arr['container'] = 'pagenavcontainer';
														$pageclass_arr['navvul']	= 'pagenavul';
														$pageclass_arr['current']	= 'pagenav_current';
														$query_string .= '&categ_pg='.$_REQUEST['categ_pg'];
														paging_footer($path,$query_string,$tot_cntprod,$start_varprod['pg'],$start_varprod['pages'],'',$pg_variableprod,'Favourite products',$pageclass_arr,0); 
						}
						?>	
					</div>
					</div>
					</div>
					<div class="list_page_bottom"></div>
				</div>
				<?
		
		}
		
function Show_MyhomePurcahaseProducts($ret_purchase)
		{
		global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$ecom_themename,$Settings_arr,$ecom_themeid,$default_layout,$inlineSiteComponents;
		   if($db->num_rows($ret_purchase)>0)
			{
			?>
						<div class="pro_de_shelfBheader" align="left"><?php echo $Captions_arr['LOGIN_HOME']['LOGIN_HOME_PURCHASE_HEADING'];?>
						</div>
						<div class="mid_shlf2_con" >
							<?php
							$max_col = 3;
							$cur_col = 0;
							$prodcur_arr = array();
							while($row_prod = $db->fetch_array($ret_purchase))
							{
								$prodcur_arr[] = $row_prod;
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
								<div class="mid_shlf2_pdt_name"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></div>
									<div class="mid_shlf2_pdt_image">
									<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
									<?php
									// Calling the function to get the image to be shown
									$pass_type = get_default_imagetype('fav_prod');
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
								<div class="mid_shlf2_free_con">
								<?php
								if($row_prod['product_freedelivery']==1)
								{	
									?>
									<div class="mid_shlf2_free"></div>
									<?php
								}
								if($row_prod['product_bulkdiscount_allowed']=='Y')
								{
									?>
									<div class="mid_shlf2_pdt_bulk"><img src="<?php url_site_image('bulk-discount.gif')?>" border="0" alt="Bulk Discount" /></div>
									<?php
								}
								?>
								</div>
								<?php
								if($comp_active)
								{
									?>
									<div class="mid_shlf2_pdt_compare" >
									<?php	dislplayCompareButton($row_prod['product_id']);?>
									</div>
									<?php	
								}
								?>
								<div class="mid_shlf2_pdt_des">
									<?php echo stripslashes($row_prod['product_shortdesc'])?>
								</div>
								<?php
								if($row_prod['product_saleicon_show']==1)
								{
									$desc = stripslashes(trim($row_prod['product_saleicon_text']));
										if($desc!='')
										{
										?>	
										<div class="mid_shlf2_pdt_sale"><?php echo $desc?></div>
										<?php
										}
								}	
									if($row_prod['product_newicon_show']==1)
									{
										$desc = stripslashes(trim($row_prod['product_newicon_text']));
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
									<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
									<input type="hidden" name="fpurpose" value="" />
									<input type="hidden" name="fproduct_id" value="" />
									<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
									<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
									<div class="mid_shlf2_info_btn"><?php show_moreinfo($row_prod,'mid_shlf2_info_link')?></div>
									<div class="mid_shlf2_buy_btn">
									<?php
									$class_arr 					= array();
									$class_arr['ADD_TO_CART']	= 'mid_shlf2_buy_link';
									$class_arr['PREORDER']		= 'mid_shlf2_buy_link';
									$class_arr['ENQUIRE']		= 'mid_shlf2_buy_link';
									show_addtocart($row_prod,$class_arr,$frm_name)
									?>
									</div>
									</form>
									</div>
								<?php
								?>
									<div class="mid_shlf2_pdt_price">
									<?php
									$price_class_arr['class_type'] 		= 'div';
									$price_class_arr['normal_class'] 	= 'shlf2_normalprice';
									$price_class_arr['strike_class'] 	= 'shlf2_strikeprice';
									$price_class_arr['yousave_class'] 	= 'shlf2_yousaveprice';
									$price_class_arr['discount_class'] 	= 'shlf2_discountprice';
									echo show_Price($row_prod,$price_class_arr,'shopbrand_3');	
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
				<div class="fav_showall"><h6 align="right"><a href="http://<?=$ecom_hostname?>/showpurchaseall.html" class="showall" title="<?php echo $Captions_arr['COMMON']['SHOW_ALL']?>"><? echo $Captions_arr['COMMON']['SHOW_ALL']?></a></h6></div>

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
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="loginwelcomemsg_table">
      <tr>
        <td width="7%" align="left" valign="middle" class="loginwelcomemsg_header" > 
       	 <?php echo  str_replace("[username]",$username,$Captions_arr['LOGIN_HOME']['LOGIN_HOME_HEADER_MESG']);?></td>
      </tr>
	  <?php
	  	if($Captions_arr['LOGIN_HOME']['LOGIN_HOME_MESG_TEXT']!='')
		{
	  ?>
		  <tr>
			<td align="left" valign="middle" class="loginwelcomemsg_text"> <?php echo  str_replace("[username]",$username,$Captions_arr['LOGIN_HOME']['LOGIN_HOME_MESG_TEXT']);?></td>
		  </tr>
	  <?php
	  	}
		?>
		
		<tr>
		<td><table width="100%" border="0" cellspacing="0" cellpadding="0" class="loginwelcomemsg_table">
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
						
						<tr>
							<td class="logindetailheader" align="left" colspan="2"><?=$Captions_arr['LOGIN_HOME']['LOGIN_HOME_DISC_DETAILS']?></td>
						</tr>
						
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
						  <tr>
							  <td align="left" valign="middle" class="loginwelcomemsg_text" width="40%"><?=$Captions_arr['LOGIN_HOME']['LOGIN_HOME_NORMAL_DISC']?></td><td align="left" valign="middle" class="loginwelcomemsg_text">:&nbsp;<?=$customer_discount.'%'?></td>
						  </tr>
						  <?
						  }
					  }  
					 }
					 elseif($db->num_rows($ret_products_id )==0 && $group_id)
					 {	
						if($row_discount['cust_disc_grp_discount']>0)
						{
							?>
							<tr>
								<td align="left" valign="middle" class="loginwelcomemsg_text" width="40%"><?=$Captions_arr['LOGIN_HOME']['LOGIN_HOME_GORUP_DISC']?></td><td align="left" valign="middle" class="loginwelcomemsg_text">:&nbsp;<?=$row_discount['cust_disc_grp_discount'].'%'?></td>
							</tr>
							<?
						 }
					 }	 
					  if($row_discount['cust_disc_grp_discount']>0)
							{   
							$Cnt_prd=0;
							?>
							<tr><td colspan="2"><table cellpadding="0" cellspacing="0" border="0" width="100%">
										<? if($db->num_rows($ret_products_id )>0)
											{
											if($allow_discount==1)
											{
											 $homemsg = str_replace("[value]", $row_discount['cust_disc_grp_discount'].'%', $Captions_arr['LOGIN_HOME']['LOGIN_HOME_GORUP_PRODUCTS']);
										 ?>
											 <tr>
												<td align="left" valign="middle" class="logindiscountmsg_text"  colspan="3"><?=$homemsg?></td>
											</tr>
											<?
											$ids = array();
											while($row_products_id = $db->fetch_array($ret_products_id))
											{
														$prodcur_arr[] = $row_products_id;
														//sunil
														$ids[] = $row_products_id['product_id'];
											?>						
											<tr >
											<td align="left" valign="middle"class="newshelfBtabletd" onmouseover="this.className='newnormalshelf_hover'" onmouseout="this.className='newshelfBtabletd'">
											<table width="100%" border="0" cellspacing="0" cellpadding="0" class="newshelfBtableinner">
											<tr>
											<td class="newshelfBtableinnertd" valign="top" align="left">
											
											<h2 class="newshelfBprodname"><a href="<?php url_product($row_products_id['product_id'],$row_products_id['product_name'],-1)?>" title="<?php echo stripslashes($row_products_id['product_name'])?>"><?php echo stripslashes($row_products_id['product_name'])?></a></h2>
											
											<h6 class="newshelfBproddes"><?php echo stripslashes($row_products_id['product_shortdesc'])?></h6>
										
											</td>
											
											<td class="newshelfBtableinnertd" valign="middle" align="center">
											
											<a href="<?php url_product($row_products_id['product_id'],$row_products_id['product_name'],-1)?>" title="<?php echo stripslashes($row_products_id['product_name'])?>">
															<?php
																// Calling the function to get the type of image to shown for current 
																$pass_type = get_default_imagetype('fav_prod');
																// Calling the function to get the image to be shown
																$img_arr = get_imagelist('prod',$row_products_id['product_id'],$pass_type,0,0,1);
																if(count($img_arr))
																{
																	show_image(url_root_image($img_arr[0][$pass_type],1),$row_products_id['product_name'],$row_products_id['product_name']);
																}
																else
																{
																	// calling the function to get the default image
																	$no_img = get_noimage('prod',$pass_type); 
																	if ($no_img)
																	{
																		show_image($no_img,$row_products_id['product_name'],$row_products_id['product_name']);
																	}	
																}	
															?>
															</a> 
											
											<?php if($prod_compare_enabled)  { 
														dislplayCompareButton($row_products_id['product_id']);
													 }
													 ?>
											</td>
											<td class="newshelfBtableinnertd" valign="middle" align="left">
											
											<?php
													
														$price_class_arr['ul_class'] 		= 'shelfBul';
														$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
														$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
														$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
														$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
														echo show_Price($row_products_id,$price_class_arr,'shelfcenter_1');
														show_excluding_vat_msg($row_products_id,'vat_div');// show excluding VAT msg
														show_bonus_points_msg($row_products_id,'bonus_point'); // Show bonus points
														$frm_name = uniqid('mafavhome_');
												?>	
														<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_products_id['product_id']?>)">
														<input type="hidden" name="fpurpose" value="" />
														<input type="hidden" name="fproduct_id" value="" />
														<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
														<input type="hidden" name="fproduct_url" value="<?php url_product($row_products_id['product_id'],$row_products_id['product_name'])?>" />
														<div class="infodiv">
															<div class="infodivleft"><?php show_moreinfo($row_products_id,'infolink')?></div>
															<?php
																$class_arr 					= array();
																$class_arr['ADD_TO_CART']	= 'quantity_infolink';
																$class_arr['PREORDER']		= 'quantity_infolink';
																$class_arr['ENQUIRE']		= 'quantity_infolink';
																show_addtocart($row_products_id,$class_arr,$frm_name,true,'','','infodivright')
															?>
														</div>
														</form>
											
											</td>
											</tr>
																					
											</table></td>
											</tr>
											<? }
											  }
											 }
							?>
							</table>
							</td>
							</tr>
							<?
							}
					}
				}
				else
				{
				 if($customer_discount>0)
				 {
				 ?>
				  <tr>
							  <td align="left" valign="middle" class="loginwelcomemsg_text" width="40%"><?=$Captions_arr['LOGIN_HOME']['LOGIN_HOME_NORMAL_DISC']?></td><td align="left" valign="middle" class="loginwelcomemsg_text">:&nbsp;<?=$customer_discount.'%'?></td>
				 </tr>
				 <?
				  }
				}
			  ?>
			  <tr><td>&nbsp;</td></tr>
		  </table>
		  </td>
		  </tr>
        </table>
		<?php
			return $ids;	
		}
		function Display_Message($mesgHeader,$Message){
		
		?>
		<table width="100%" border="0" cellspacing="4" cellpadding="0" class="regifontnormal">
      <tr>
        <td width="7%" align="left" valign="middle" class="message_header" > 
         <?php echo $mesgHeader;?></td>
      
      </tr>
      <tr>
        <td align="left" valign="middle" class="message"><?php echo $Message; ?></td>
        
      </tr>
        </table>
		<?php	
		}
		
	};	
?>
			