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
														show_addtocart($row_prod,$class_arr,$frm_name,false,'','','normal_shlfB_pdt_buy_btn');
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
				case '3row':
				break;
				?>
                <div class="product_list_outer"> 
					<div class="pdt_list_outer">
		<?php	while($row_prod = $db->fetch_array($ret_fav_products))
				{
					$prodcur_arr[] = $row_prod;
					$HTML_title = $HTML_image = $HTML_desc = '';
					$HTML_sale = $HTML_new = $HTML_compare = $HTML_rating = '';
					$HTML_price = $HTML_bulk= $HTML_bonus = $HTML_compare = $HTML_freedel= $HTML_bonus_bar = '';
					$HTML_new = $HTML_sale = '';
					
					//if($cat_det['product_showtitle']==1)// whether title is to be displayed
						$HTML_title = '<div class="pdt_list_pdt_name"><a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">'.stripslash_normal($row_prod['product_name']).'</a></div>';
				
					//if ($cat_det['product_showimage']==1)// Check whether description is to be displayed
					{
						$HTML_image ='<a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">';
						// Calling the function to get the image to be shown
						$pass_type = 'image_thumbcategorypath';
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
					}
					//if ($cat_det['product_showshortdescription']==1)// Check whether description is to be displayed
					{
						$HTML_desc = stripslash_normal($row_prod['product_shortdesc']);
					}
					/*
					$price_class_arr['class_type']          = 'div';
					$price_class_arr['normal_class']        = 'normal_shlfB_pdt_priceA';
					$price_class_arr['strike_class']        = 'normal_shlfB_pdt_priceB';
					$price_class_arr['yousave_class']       = 'normal_shlfB_pdt_priceC';
					$price_class_arr['discount_class']      = 'normal_shlfB_pdt_priceC';
					$price_arr =  show_Price($row_prod,array(),'compshelf',false,4);
					
					if($price_arr['discounted_price'])
						$HTML_price = $price_arr['discounted_price'];
					else
						$HTML_price = $price_arr['base_price'];
						*/ 
				
					//$HTML_price = show_Price($row_prod,$price_class_arr,'shelfcenter_3');
					if($cur_col==0)
					{
						echo  '<div class="pdt_list_thumb_outer">';
					}
					if($cur_col%2==0 && $cur_col!=0)	
					{
						$cls = "pdt_list_pdt";
					}
					else
					{
						$cls = "pdt_list_pdt";
					}
		?>			<div class="<?php echo $cls?>">
						<div class="pdt_list_pdt_mid">
		<?php		if($row_prod['product_saleicon_show']==1)
					{
						$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
						//if($desc!='')
						{
							$HTML_sale = '<div class="pdt_list_sale_3row"></div>';
						}
					}
					if($row_prod['product_newicon_show']==1)
					{
						$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
						//if($desc!='')
						{
							$HTML_new = '<div class="pdt_list_new_3row"></div>';
						}
					}
					echo $HTML_new;
					echo  $HTML_sale;
					
					if($cat_det['product_showrating']==1)
					{
						$module_name = 'mod_product_reviews';
						if(in_array($module_name,$inlineSiteComponents))
						{
							if($row_prod['product_averagerating']>=0)
							{
								$HTML_rating = '<div class="list_d_pdt_rate">'.display_rating($row_prod['product_averagerating'],1).'</div>';
							}
						}
					}
					//echo $HTML_rating;
		?>					<div class="pdt_list_pdt_r"><?php echo $HTML_image?></div>
							<?=$HTML_title;?>
							<?php
							if($cat_det['product_showshortdescription']==1)// whether title is to be displayed
							{
								
								echo '<p class="product_descB">'.stripslash_normal($row_prod['product_shortdesc']).'</p>';
								
							}
							$price_class_arr['ul_class'] 		= 'shelfBul_lu';
													$price_class_arr['normal_class'] 	= 'shelfBnormalprice_lu';
													$price_class_arr['strike_class'] 	= 'shelfBstrikeprice_lu';
													$price_class_arr['yousave_class'] 	= 'shelfByousaveprice_lu';
													$price_class_arr['discount_class'] 	= 'shelfBdiscountprice_lu';
													//	echo show_Price($row_prod,$price_class_arr,'shelfcenter_1'); price_categorydetails_1_reqbreak
													echo show_Price($row_prod,$price_class_arr,'shelfcenter_3');
													show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg

							?>
							<div class="pdt_list_pdt_l">
                            <div class="pdt_list_pdt_buy_otr">
                            
		<?php
				if($row_prod['product_bonuspoints']>0 and $cat_det['product_showbonuspoints']==1)
				{
				$HTML_bonus = 'Bonus: '.$row_prod['product_bonuspoints'].'';
				}
				else
				{
				$HTML_bonus = '';
				}
				/*
				?>
				<div class="pdt_list_bonus">  <?php echo $HTML_bonus;?> </div>  
					*/
					?>		
		<?php		
		/*
		if(isProductCompareEnabled())
					{
						?>
						<div class="pdt_list_pdt_compare">
							<?php
						if(is_array($_SESSION['compare_products']))
						{
							if(in_array($row_prod['product_id'],$_SESSION['compare_products']))
							{
								$select = "checked";
							}
							else
							{
								$select = "";
							}
						}
						else
							$select = "";
						$compare_button_displayed = true; ?><form name="add_to_compare" id="add_to_compare" action="" method="post" ><input type="hidden" name="submit_Compare_pdts" id="submit_Compare_pdts" value="1"><input type="checkbox" name="compare_products_<?php echo $row_prod['product_id'];?>" value="ADD TO COMPARE" class="buttonred_large" onclick="addtoCompare(<?php echo $row_prod['product_id']?>)" id="compare_products_<?php echo $row_prod['product_id'];?>" <?php echo $select ?> /></form> Select To Compare
						 </div>
						<?php
				}
				*/ 
		?>					
		<div class="more_info_lu">			
		<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" class="det_buy_link_more" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslash_normal($Captions_arr['COMMON']['MORE_INFO'])?></a>
		</div>
        <?php		$frm_name = uniqid('best_');
		?>
					<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
                        <input type="hidden" name="fpurpose" value="" />
                        <input type="hidden" name="fproduct_id" value="" />
                        <input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
                        <input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
                        <input type="hidden" name="qty" id="qty" value="1" />																
		<?php
                        $class_arr                      = array();
                        $class_arr['ADD_TO_CART']       = 'cupid-green';
                        $class_arr['PREORDER']          = 'cupid-green';
                        $class_arr['ENQUIRE']           = 'cupid-green';
                        $class_arr['QTY_DIV']           = 'normal_shlfB_pdt_input';
                        $class_arr['QTY']               = 'quainput';							
                        /* Code for ajax setting starts here */
                        $class_arr['BTN_CLS']           = 'cupid-green';												
                        //show_addtocart($row_prod,$class_arr,$frm_name,false,'','','normal_shlfB_pdt_buy_btn');
                        show_addtocart_v5_ajax($frm_name,$row_prod,$class_arr,false);
                        /* Code for ajax setting ends here */
                        //echo "product display style - ".$cat_det['product_displaytype'];
		?>
					</form>				
				
				</div>
				</div>
				<div class="pdt_list_m_otr">
				           
				<?php
				/*
				<div class="pdt_list_pdt_more"><a class="" title="Love Meter T-Shirt Heart Lights" href="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>">More Info</a></div>
				*/?> 
				</div>   
				
				<?php
				/*
				if($row_prod['product_bulkdiscount_allowed']=='Y' || $row_prod['product_freedelivery']==1)
				{
				echo "<div class='pdt_list_free_otr'>";
				}
				
				if($row_prod['product_freedelivery']==1)
				{
				echo $HTML_freedel = ' <div class="pdt_list_free_del"> </div>';
				}
				if($row_prod['product_bulkdiscount_allowed']=='Y')
				{
				echo $HTML_bulk = '<div class="pdt_list_free_bulk"> </div>';
				}
				if($row_prod['product_bulkdiscount_allowed']=='Y' || $row_prod['product_freedelivery']==1)
				{
				echo "</div>";
				}
				*/ 
				/*				  <div class="pdt_list_pdt_des"><?php echo $HTML_desc;?></div>
				*/ 
				
				
				?>
				
				
				</div>
				</div>
				
				<?php
				
				
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
				</div>
				</div>
                <?php
				}//end of switchcase
		}
	};	
?>