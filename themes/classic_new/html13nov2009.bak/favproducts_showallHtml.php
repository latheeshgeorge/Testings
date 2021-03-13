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

			$sql_last_login = "SELECT customer_last_login_date FROM customers WHERE sites_site_id=$ecom_siteid AND customer_id=$customer_id";
			$ret_last_login = $db->query($sql_last_login);
			list($row_last_login) = $db->fetch_array($ret_last_login);
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
			$prod_compare_enabled = isProductCompareEnabled();
			?>
			<div class="shelfAheader">
					<?php
						if ($db->num_rows($ret_fav_products)==1)
							echo stripslash_normal($Captions_arr['LOGIN_HOME']['LOGIN_HOME_FAV_PRODUCT_HEADER']);
						else
							echo stripslash_normal($Captions_arr['LOGIN_HOME']['LOGIN_HOME_FAV_PRODUCTS_HEADER']);
						?>
				
			</div>
			<?php
			$displaytype = $Settings_arr['favorite_prodlisting'];

			switch($displaytype)
			{ 
				case '1row':
				?>
				<div class="shelfBtable">
					<?php
					while($row_prod = $db->fetch_array($ret_fav_products))
					{
					?>
						<div class="shelfBtabletd">
												<div class="shelfBtabletdinner">
												<div class="shelfBprodname">
												<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="shelfBprodnamelink"><?php echo stripslashes($row_prod['product_name'])?></a>
												</div>
												<div class="shelfBleft">
												<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
												<?php
													
													// Calling the function to get the image to be shown
													$pass_type = get_default_imagetype('midshelf');
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
												<div class="compare_li">
												<?php
												if($prod_compare_enabled)
												{
													dislplayCompareButton($row_prod['product_id']);
												}
												?>	
												</div> 
												</div>
												<div class="shelfBmid">	
												<h6 class="shelfBproddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6>
												<?php
												$price_class_arr['ul_class'] 		= 'shelfBpriceul';
													$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
													$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
													$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
													$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
													echo show_Price($row_prod,$price_class_arr,'bestseller_1');
													if($row_prod['product_saleicon_show']==1)
													{
														$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
														if($desc!='')
														{
												?>
															<div class="shelfB_sale"><?php echo $desc?></div>
												<?php
														}
													}	
													if($row_prod['product_newicon_show']==1)
													{
														$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
														if($desc!='')
														{
												?>
															<div class="shelfB_newsale"><?php echo $desc?></div>		
												<?php
														}
													}
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
												<div class="shelfBright"> 
												<?php 
												if($row_prod['product_freedelivery']==1)
												{	
												?>
													<div class="shelfB_free"></div>
												<?php
												}
												if($row_prod['product_bulkdiscount_allowed']=='Y')
												{
												?>
													<div class="shelfB_bulk"></div>
												<?php
												}
												$frm_name = uniqid('best_');
												?>
												<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
												<input type="hidden" name="fpurpose" value="" />
												<input type="hidden" name="fproduct_id" value="" />
												<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
												<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
												<div class="infodivB">
												<div class="infodivBleft">
												<?php show_moreinfo($row_prod,'infolink')?></div>
												<div class="infodivBright">
												<?php
													$class_arr 					= array();
													$class_arr['ADD_TO_CART']	= 'infolinkB';
													$class_arr['PREORDER']		= 'infolinkB';
													$class_arr['ENQUIRE']		= 'infolinkB';
													show_addtocart($row_prod,$class_arr,$frm_name)
												?>
												</div>		
												</div>
												</form>
												</div>
												</div>
												</div>
					<? 
					}
					?>
					</div>
				<?
				break;
				case '3row':
			?>					
					<div class="shelfAtable" >
					<?php
					$max_col = 3;
					$cur_col = 0;
					$prodcur_arr = array();	
					while($row_prod = $db->fetch_array($ret_fav_products))
					{		if($cur_col == 0)
								{ 
									echo '<div class="mid_shlf2_con_main">';
								}
								$cur_col ++;
									?>		
																
									<div class="shelfAtabletd">
									<div class="shelfAtabletdinner">
									<ul class="shelfAul">
									<li><h2 class="shelfAprodname"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h2></li>
									<li class="compare_li">
									<?php
									if($prod_compare_enabled)
									{
										dislplayCompareButton($row_prod['product_id']);
									}?>
									</li>														
									<li class="shelfimg">
									<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
									<?php
									// Calling the function to get the type of image to shown for current 
									//$pass_type = get_default_imagetype('midshelf');
									// Calling the function to get the image to be shown
									$pass_type = get_default_imagetype('midshelf');
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
									if($row_prod['product_freedelivery']==1)
									{
									?>
									<div class="shelfA_free"></div>
									<?php
									}
									if($row_prod['product_bulkdiscount_allowed']=='Y')
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
										if($row_prod['product_averagerating']>=0)
										{
										?>
											<li><div class="shelfB_rate">
										<?php
											display_rating($row_prod['product_averagerating']);
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
											echo show_Price($row_prod,$price_class_arr,'bestseller_3');
											show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
											?>	
										</li>
										<li><h6 class="shelfAproddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6></li>
									</ul>
									<?
									if($row_prod['product_saleicon_show']==1)
									{
										$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
										if($desc!='')
										{
									?>	
										<div class="shelfA_sale"><?php echo $desc?></div>
									<?php
										}
									}
									if($row_prod['product_newicon_show']==1)
									{
										$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
										if($desc!='')
										{
									?>
										<div class="shelfA_newsale"><?php echo $desc?></div>
									<?php
										}
									}
									$frm_name = uniqid('best_');
									
									?>
									<div class="bonus_point"><?php show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points?>
									</div>
									<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
									<input type="hidden" name="fpurpose" value="" />
									<input type="hidden" name="fproduct_id" value="" />
									<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
									<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
									<div class="infodiv">
									<div class="infodivleft">
									<?php show_moreinfo($row_prod,'infolink')?></div>
									<div class="infodivright">
									<?php
									$class_arr 					= array();
									$class_arr['ADD_TO_CART']	= 'infolink';
									$class_arr['PREORDER']		= 'infolink';
									$class_arr['ENQUIRE']		= 'infolink';
									show_addtocart($row_prod,$class_arr,$frm_name)
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