<?php
	/*############################################################################
	# Script Name 	: favcategory_showallHtml.php
	# Description 	: Page which holds the display logic for all products under fav category
	# Coded by 		: LSH
	# Created on	: 14-oct-2008
	# Modified by	: LH
	# Modified On	: 
	##########################################################################*/
	class favprodshowall_Html
	{
		// Defining function to show the shelf details
		function Show_favproducts($cust_id)
		{
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents;
			$customer_id 					= get_session_var("ecom_login_customer");
            if($customer_id)
			{
			$sql_last_login = "SELECT customer_last_login_date FROM customers WHERE sites_site_id=$ecom_siteid AND customer_id=$customer_id";
			$ret_last_login = $db->query($sql_last_login);
			list($row_last_login) = $db->fetch_array($ret_last_login);
			}
		     $Captions_arr['LOGIN_HOME'] = getCaptions('LOGIN_HOME'); // to get values for the captions from the general settings site captions
			$prodcur_arr =array();
				$prodperpage			= ($Settings_arr['product_maxcnt_fav_category']>0)?$Settings_arr['product_maxcnt_fav_category']:10;//Hardcoded at the moment. Need to change to a variable that can be set in the console.
				//$limit = $Settings_arr['product_maxcnt_fav_category'];
				$favsort_by				= $Settings_arr['product_orderby_favorite'];
				$prodsort_order			= $Settings_arr['product_orderfield_favorite'];
				switch ($prodsort_order)
				{
					case 'product_name': // case of order by product name
					$prodsort_order		= 'product_name';
					break;
					case 'price': // case of order by price
					$prodsort_order		= 'product_webprice';
					break;
					case 'product_id': // case of order by price
					$prodsort_order		= 'product_id';
					break;	
				};
				if ($_REQUEST['req']!='')// LIMIT for Favorites is applied only if not displayed in home page
				{
					$start_varprod 		= prepare_paging($_REQUEST[$pg_variableprod],$prodperpage,$tot_cntprod);
					$Limitprod			= " LIMIT ".$start_varprod['startrec'].", ".$prodperpage;
				}	
				else
					$Limitprod = '';
				$pg_variableprod		= 'prod_pg';
		 	   $sql_fav_products = "SELECT id,a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
								a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
								a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
								product_total_preorder_allowed,a.product_applytax,a.product_shortdesc,a.product_bonuspoints,
								a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,a.product_variablesaddonprice_exists,
								a.product_variablecomboprice_allowed,a.product_variablecombocommon_image_allowed,a.default_comb_id,
								a.price_normalprefix,a.price_normalsuffix, a.price_fromprefix, a.price_fromsuffix,a.price_specialofferprefix, a.price_specialoffersuffix, 
								a.price_discountprefix, a.price_discountsuffix, a.price_yousaveprefix, a.price_yousavesuffix,a.price_noprice,
								a.product_averagerating,a.product_saleicon_show,a.product_saleicon_text,a.product_newicon_show,a.product_newicon_text,
								a.product_freedelivery 
									FROM 
										products a,customer_fav_products cfp
									WHERE
										 a.product_id = cfp.products_product_id AND a.product_hide='N'  AND
								cfp.sites_site_id = $ecom_siteid  AND cfp.customer_customer_id = $cust_id
									ORDER BY $prodsort_order $favsort_by $Limitprod	";
					$ret_fav_products = $db->query($sql_fav_products);
					$tot_cnt 	= $db->num_rows($ret_fav_products); 
			$prod_compare_enabled = isProductCompareEnabled();
			?>
			<div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> 
			<?php
				if ($db->num_rows($ret_fav_products)==1)
					echo stripslash_normal($Captions_arr['LOGIN_HOME']['LOGIN_HOME_FAV_PRODUCT_HEADER']);
				else
					echo stripslash_normal($Captions_arr['LOGIN_HOME']['LOGIN_HOME_FAV_PRODUCTS_HEADER']);
			?>
			</div>
			
			<?php	
			$displaytype = $Settings_arr['favorite_prodlisting'];
			$pass_type = get_default_imagetype('midshelf');
			$comp_active = isProductCompareEnabled();
			switch($displaytype)
			{ 
				case '1row':
				?>
					<div class="shelf_main_con" >
					<div class="shelf_top"><?=stripslash_normal($Captions_arr['LOGIN_HOME']['LOGIN_HOME_FAV_PRODUCTS_HEADER'])?></div>
					<div class="shelf_mid">
						<?php
						$cur = 1;
						while($row_prod = $db->fetch_array($ret_fav_products))
						{
							if($cur==$tot_cnt)
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
								<?php
								$module_name = 'mod_product_reviews';
									if(in_array($module_name,$inlineSiteComponents))
									{
										if($row_prod['product_averagerating']>=0)
										{
										?>
											<div class="shelfB_rate">
											<?php
												display_rating($row_prod['product_averagerating']);
											?>
											</div>
										<?php
										}
									}
								?>	
							</div>
							<div class="shlf_pdt_compare" >
							<?php if($comp_active)  {
										dislplayCompareButton($row_prod['product_id']);
									}?>
							</div>
							</div>
							<div class="shlf_pdt_txt">
							<?php
								if($row_prod['product_freedelivery']==1)
								{	
								?>
									<div class="free_delivery"></div>
								<?php
								}
								if($row_prod['product_bulkdiscount_allowed']=='Y')
								{
								?>
									<div class="bulk_discount"></div>
								<?php
								}
								?>
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
								$frm_name = uniqid('favprod_');
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
							<?php
							if($row_prod['product_saleicon_show']==1)
								{
									$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
									if($desc!='')
									{
							?>
										<div class="mid_shlf_pdt_sale"><?php echo $desc?></div>
							<?php
									}
								}	
								if($row_prod['product_newicon_show']==1)
								{
									$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
									if($desc!='')
									{
							?>
										<div class="mid_shlf_pdt_new"><?php echo $desc?></div>		
							<?php
									}
								}	
								?>
							</div>
							</div>
					<?php
						}
					?>
					</div>
				   <div class="shelf_bottom"></div>
			     </div>
				<?
				break;
				case '3row':
			?>					
				<div class="shelfA_main_con" > 
				<div class="shelfA_top"><?=stripslash_normal($Captions_arr['LOGIN_HOME']['LOGIN_HOME_FAV_PRODUCTS_HEADER'])?></div>
				<div class="shelfA_mid">
				<?php
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
							 	
						  <div class="shlfA_pdt_txt">
											  <ul class="shlfA_pdt_ul">
									<li class="shlfA_pdt_name" ><h3><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>"><?php echo stripslashes($row_prod['product_name'])?></a></h3></li>
										<?php
											$module_name = 'mod_product_reviews';
												if(in_array($module_name,$inlineSiteComponents))
												{
													if($row_prod['product_averagerating']>=0)
													{
													?>
														<li><div class="shelf_rate">
														<?php
															display_rating($row_prod['product_averagerating']);
														?>
														</div></li>
													<?php
													}
												}
												?>
										<li class="shlfA_pdt_des" ><h6><?php echo stripslashes($row_prod['product_shortdesc'])?></h6></li>
								<li class='shlfA_pdt_bonus'><h6><?php show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points?></h6></li>
								<?php
									if($row_prod['product_saleicon_show']==1)
										{
											$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
											if($desc!='')
											{
									?>
												<li><div class="mid_shlf_pdt_salea"><?php echo $desc?></div></li>
									<?php
											}
										}	
										if($row_prod['product_newicon_show']==1)
										{
											$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
											if($desc!='')
											{
									?>
												<li><div class="mid_shlf_pdt_newa"><?php echo $desc?></div></li>
									<?php
											}
										}	
										?>
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
										<div class="bulk_discount_con">
								 <?php
									if($row_prod['product_freedelivery']==1)
										{	
										?>
											<div class="free_deliverya"></div>
										<?php
										}
										if($row_prod['product_bulkdiscount_allowed']=='Y')
										{
										?>
											<div class="bulk_discounta"></div>
										<?php
										}
										?>
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
				<? 
				break;
				}//end of switchcase
		}
	};	
?>