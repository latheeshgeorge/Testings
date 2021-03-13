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
			$comp_active = isProductCompareEnabled();
			?>
			<div class="mid_shlf2_hdr">
				<div class="mid_shlf2_hdr_top"></div>
					<div class="mid_shlf2_hdr_middle"> 
					<?php
						if ($db->num_rows($ret_fav_products)==1)
							echo stripslash_normal($Captions_arr['LOGIN_HOME']['LOGIN_HOME_FAV_PRODUCT_HEADER']);
						else
							echo stripslash_normal($Captions_arr['LOGIN_HOME']['LOGIN_HOME_FAV_PRODUCTS_HEADER']);
						?>
					</div>
				<div class="mid_shlf2_hdr_bottom"></div>
			</div>
			<?php
			$displaytype = $Settings_arr['favorite_prodlisting'];
			$pass_type = get_default_imagetype('midshelf');
			
			switch($displaytype)
			{ 
				case '1row':
				?>
				<div class="normal_shlf_mid_con">
											<div class="normal_shlf_mid_top"></div>
											<div class="normal_shlf_mid_mid">
											<?
												while($row_prod = $db->fetch_array($ret_fav_products))
												{
													$HTML_title = $HTML_image = $HTML_desc = '';
													$HTML_sale = $HTML_new = $HTML_compare = $HTML_rating = '';
													$HTML_price = $HTML_bulk= $HTML_bonus = $HTML_compare = $HTML_freedel= $HTML_bonus_bar = '';
													
														$HTML_title = '<div class="normal_shlfB_pdt_name"><a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">'.stripslash_normal($row_prod['product_name']).'</a></div>';
														$HTML_image ='<a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">';
														// Calling the function to get the image to be shown
														$img_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,0,0,1);
														if(count($img_arr))
														{
															$HTML_image .= show_image(url_root_image($img_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name'],'','',1);
														}
														else
														{
															// calling the function to get the default image
															$no_img = get_noimage('prod',$pass_type); 
															if ($no_img)
															{
																$HTML_image .= show_image($no_img,$row_prod['product_name'],$row_prod['product_name'],'','',1);
															}       
														}       
														$HTML_image .= '</a>';
														$HTML_desc = stripslash_normal($row_prod['product_shortdesc']);
													if($row_prod['product_saleicon_show']==1)
													{
														$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
														if($desc!='')
														{
															  $HTML_sale = '<div class="normal_shlfB_pdt_sale">'.$desc.'</div>';
														}
													}
													if($row_prod['product_newicon_show']==1)
													{
														$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
														if($desc!='')
														{
															  $HTML_new = '<div class="normal_shlfB_pdt_new">'.$desc.'</div>';
														}
													}
													
														$module_name = 'mod_product_reviews';
														if(in_array($module_name,$inlineSiteComponents))
														{
															if($row_prod['product_averagerating']>=0)
															{
																$HTML_rating = display_rating($row_prod['product_averagerating'],1);
															}
														}
													
														$price_class_arr['class_type']          = 'div';
														$price_class_arr['normal_class']        = 'normal_shlfB_pdt_priceA';
														$price_class_arr['strike_class']        = 'normal_shlfB_pdt_priceB';
														$price_class_arr['yousave_class']       = 'normal_shlfB_pdt_priceC';
														$price_class_arr['discount_class']      = 'normal_shlfB_pdt_priceC';
														$HTML_price = show_Price($row_prod,$price_class_arr,'shelfcenter_1');
													if($row_prod['product_bulkdiscount_allowed']=='Y')
													{
														$HTML_bulk = '<img src="'.url_site_image('multi-buyA.gif',1).'" />';
													}
													else
														$HTML_bulk = '&nbsp;';
													if($row_prod['product_bonuspoints']>0 and $shelfData['shelf_showbonuspoints']==1)
													{
														$HTML_bonus = 'Bonus: '.$row_prod['product_bonuspoints'];
														$bonus_class = 'normal_shlfB_pdt_bonus';
													}
													else
													{
														$HTML_bonus = '&nbsp;';
														$bonus_class = 'normal_shlfB_pdt_bonus_blank';
													}	
													if($comp_active)
														$HTML_compare = dislplayCompareButton($row_prod['product_id'],'','',1);
													if($row_prod['product_freedelivery']==1)
													{
														$HTML_freedel = ' <div class="normal_shlfB_free"></div>';
													}
													if($HTML_bonus!='&nbsp;' or $HTML_rating !='&nbsp;')
													{
														$HTML_bonus_bar = ' <div class="normal_shlfB_pdt_bonus_otr">
																			<div class="'.$bonus_class.'">'.$HTML_bonus.'</div>
																			<div class="normal_shlfB_pdt_rate">'.$HTML_rating.'</div>
																			</div>';
													}	
													
													$frm_name = uniqid('best_');
											?>
													<div class="normal_shlfB_pdt_outr">
													<?=$HTML_freedel?>
													<div class="normal_shlfB_pdt_top"></div>
													<div class="normal_shlfB_pdt_mid">
													<?=$HTML_title?>
													<div class="normal_shlfB_pdt_img_otr">
													<div class="normal_shlfB_pdt_img"><?=$HTML_image?></div>
													</div>
													<div class="normal_shlfB_pdt_des_otr">
													<div class="normal_shlfB_pdt_des"><?=$HTML_desc?></div>
													<?=$HTML_sale?>
													<?=$HTML_new?>
													<div class="normal_shlfB_pdt_com_otr">
													<div class="normal_shlfB_multibuy"><?=$HTML_bulk?></div>
													<div class="normal_shlfB_pdt_com"><?=$HTML_compare?></div>
													</div>
													<?=$HTML_bonus_bar?>
													</div>
													<div class="normal_shlfB_pdt_right_otr">
													<div class="normal_shlfB_pdt_price">
													<div class="normal_shlfB_pdt_price_top"></div>
													<div class="normal_shlfB_pdt_price_mid">
													<?=$HTML_price?>
													</div>
													<div class="normal_shlfB_pdt_price_bottom"></div>
													</div>
													<div class="normal_shlfB_pdt_buy_outr">
													<div class="normal_shlfB_pdt_buy">
													<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
													<input type="hidden" name="fpurpose" value="" />
													<input type="hidden" name="fproduct_id" value="" />
													<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
													<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />																
													<?
														$class_arr                      = array();
														$class_arr['ADD_TO_CART']       = '';
														$class_arr['PREORDER']          = '';
														$class_arr['ENQUIRE']           = '';
														$class_arr['QTY_DIV']           = 'normal_shlfB_pdt_input';
														$class_arr['QTY']               = ' ';
														
														/* Code for ajax setting starts here */
														$class_arr['BTN_CLS']           = 'normal_shlfB_pdt_buy_btn';												
														//show_addtocart($row_prod,$class_arr,$frm_name,false,'','','normal_shlfB_pdt_buy_btn');
														show_addtocart_v5_ajax($frm_name,$row_prod,$class_arr);
														/* Code for ajax setting ends here */
													?>
													</form>
													</div>
													</div>
													<div class="normal_shlfB_pdt_info"><?php show_moreinfo($row_prod,'')?></div>
													</div>
													</div>
													</div>
											<?php
												}
											?>
											</div>
											<div class="normal_shlf_mid_bottom"></div> 
											</div>
				<?
				break;
				case '2row':
			?>					
				<div class="normal_shlfA_mid_con">
				<div class="normal_shlfA_mid_top"></div>
				<div class="normal_shlfA_mid_mid">
				<? 
				$max_col = 2;
				$cur_col = 0;
				$prodcur_arr = array();
				while($row_prod = $db->fetch_array($ret_fav_products))
				{
					$prodcur_arr[] = $row_prod;
					$HTML_title = $HTML_image = $HTML_desc = '';
					$HTML_sale = $HTML_new = $HTML_compare = $HTML_rating = '';
					$HTML_price = $HTML_bulk= $HTML_bonus = $HTML_compare = $HTML_freedel= $HTML_bonus_bar = '';
						$HTML_title = '<div class="normal_shlfA_pdt_name"><a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">'.stripslash_normal($row_prod['product_name']).'</a></div>';
					
						$HTML_image ='<a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">';
						// Calling the function to get the image to be shown
						$img_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,0,0,1);
						if(count($img_arr))
						{
							$HTML_image .= show_image(url_root_image($img_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name'],'','',1);
						}
						else
						{
							// calling the function to get the default image
							$no_img = get_noimage('prod',$pass_type); 
							if ($no_img)
							{
								$HTML_image .= show_image($no_img,$row_prod['product_name'],$row_prod['product_name'],'','',1);
							}       
						}       
						$HTML_image .= '</a>';
						$HTML_desc = stripslash_normal($row_prod['product_shortdesc']);
					if($row_prod['product_saleicon_show']==1)
					{
						$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
						if($desc!='')
						{
							  $HTML_sale = '<div class="normal_shlfA_pdt_sale">'.$desc.'</div>';
						}
					}
					if($row_prod['product_newicon_show']==1)
					{
						$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
						if($desc!='')
						{
							  $HTML_new = '<div class="normal_shlfA_pdt_new">'.$desc.'</div>';
						}
					}
						$module_name = 'mod_product_reviews';
						if(in_array($module_name,$inlineSiteComponents))
						{
							if($row_prod['product_averagerating']>=0)
							{
								$HTML_rating = display_rating($row_prod['product_averagerating'],1);
							}
						}
					else
						$HTML_rating = '&nbsp;';
					
						$price_class_arr['class_type']          = 'div';
						$price_class_arr['normal_class']        = 'normal_shlfB_pdt_priceA';
						$price_class_arr['strike_class']        = 'normal_shlfB_pdt_priceB';
						$price_class_arr['yousave_class']       = 'normal_shlfB_pdt_priceC';
						$price_class_arr['discount_class']      = 'normal_shlfB_pdt_priceC';
						$HTML_price = show_Price($row_prod,$price_class_arr,'shelfcenter_3');
					if($row_prod['product_bulkdiscount_allowed']=='Y')
					{
						$HTML_bulk = '<img src="'.url_site_image('multi-buyA.gif',1).'" />';
					}
					if($row_prod['product_bonuspoints']>0 and $shelfData['shelf_showbonuspoints']==1)
					{
						$HTML_bonus = 'Bonus: '.$row_prod['product_bonuspoints'];
						$bonus_class = 'normal_shlfA_pdt_bonus';
					}
					else
					{
						$HTML_bonus = '&nbsp;';
						$bonus_class = 'normal_shlfA_pdt_bonus_blank';
					}	
					if($comp_active)
						$HTML_compare = dislplayCompareButton($row_prod['product_id'],'','',1,'compare_li_2row');
					if($row_prod['product_freedelivery']==1)
					{
						$HTML_freedel = ' <div class="normal_shlfA_free"></div>';
					}
					$frm_name = uniqid('best_');
					if($HTML_bonus!='&nbsp;' or $HTML_rating !='&nbsp;')
					{
						$HTML_bonus_bar = '<div class="normal_shlfA_pdt_bonus_otr">
											<div class="'.$bonus_class.'">'.$HTML_bonus.'</div>
											<div class="normal_shlfA_pdt_rate">'.$HTML_rating.'</div>
											</div>';
					}	
					if($cur_col==0)
					{
						$outer_class = 'normal_shlfA_pdt_outr';
						echo  '<div class="outer_shlfA_container">';
					}	
					else
					{
						$outer_class = 'normal_shlfA_pdt_outr_right';
					}
				?>
					<div class="<?=$outer_class?>">
					<?=$HTML_freedel?>
					<div class="normal_shlfA_pdt_top"></div>
					<div class="normal_shlfA_pdt_mid">
					<?=$HTML_title;?>
					<div class="normal_shlfA_pdt_img_otr">
					<div class="normal_shlfA_pdt_img"><?=$HTML_image?></div>
					<div class="normal_shlfA_pdt_price">
					<div class="normal_shlfA_pdt_price_top"></div>
					<div class="normal_shlfA_pdt_price_mid">
					<?=$HTML_price?>
					</div>
					<div class="normal_shlfA_pdt_price_bottom"></div>
					</div>
					<div class="normal_shlfA_multibuy"><?=$HTML_bulk?></div>
					<?=$HTML_compare?>
					</div>
					<?
						echo $HTML_sale;
						echo $HTML_new
					?>
					<?php /*?><div class="normal_shlfA_pdt_com"><?=$HTML_compare?></div><?php */?>
					<?=$HTML_bonus_bar?>
					<div class="normal_shlfA_pdt_des_otr">
					<div class="normal_shlfA_pdt_des"><?=$HTML_desc?></div>
					<div class="normal_shlfA_pdt_buy_outr">
					<div class="normal_shlfA_pdt_buy">
					<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
					<input type="hidden" name="fpurpose" value="" />
					<input type="hidden" name="fproduct_id" value="" />
					<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
					<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
					<?
						$class_arr                      = array();
						$class_arr['ADD_TO_CART']       = '';
						$class_arr['PREORDER']          = '';
						$class_arr['ENQUIRE']           = '';
						$class_arr['QTY_DIV']           = 'normal_shlfA_pdt_input';
						$class_arr['QTY']               = ' ';
												
						/* Code for ajax setting starts here */
						$class_arr['BTN_CLS']           = 'normal_shlfB_pdt_buy_btn';												
						//show_addtocart($row_prod,$class_arr,$frm_name,false,'','','normal_shlfA_pdt_buy_btn');
						show_addtocart_v5_ajax($frm_name,$row_prod,$class_arr);
						/* Code for ajax setting ends here */
					?>
					</form>
					</div>
					<div class="normal_shlfA_pdt_info"><?php show_moreinfo($row_prod,'')?></div>
					</div>
					</div>
					</div>
					</div> 
				<?
					$cur_col++;
					if($cur_col>=$max_col)
					{
						$cur_col =0;
						echo "</div>";
					}
				}
				if($cur_col<$max_col)
				{
					if($cur_col!=0)
					{ 
						echo "</div>";
					} 
				}
				?>
				<div class="normal_shlfA_mid_bottom"></div> 
				</div>   
				</div>
				<? 
				break;
				}//end of switchcase
		}
	};	
?>