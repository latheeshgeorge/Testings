<?php
	/*############################################################################
	# Script Name 	: bestsellerHtml.php
	# Description 	: Page which holds the display logic for middle Bestsellers
	# Coded by 		: Anu
	# Created on	: 28-Mar-2008
	# Modified by	: Anu
	# Modified On	: 28-Mar-2008
	##########################################################################*/
	class bestseller_Html
	{
		// Defining function to show the shelf details
		function Show_Bestseller($display_id)
		{
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents;

			$Captions_arr['BEST_SELLERS'] = getCaptions('BEST_SELLER');
			$Captions_arr['PROD_DETAILS'] 	= getCaptions('PROD_DETAILS');
			// query for display 	title
			$query_disp	= "SELECT 
				 display_title 
			FROM 
				display_settings 
			WHERE 
				sites_site_id='$ecom_siteid' 
				AND display_id ='$display_id'
				AND layout_code='$default_layout' ";
			$result_disp = $db->query($query_disp);
			list($cur_title) = $db->fetch_array($result_disp);
			// ##############################################################################################################
			// Building the query for bestseller
			// ##############################################################################################################
			// Getting the settings for best sellers form the settings table
			$bestseller_type 		= $Settings_arr['best_seller_picktype'];
			$prodperpage			= $Settings_arr['product_maxcntperpage_bestseller'];
			// Deciding the sort by field
			$bestsort_by			= $Settings_arr['product_orderfield_bestseller'];
			switch ($bestsort_by)
			{
				case 'custom':
					$bestsort_by	= 'b.bestsel_sortorder';
				break;
				case 'product_name':
					$bestsort_by	= 'a.product_name';
				break;
				case 'price':
					$bestsort_by	= 'a.product_webprice';
				break;
				case 'product_id': // case of order by price
					$bestsort_by	= 'a.product_id';
				break;	
			};
	
						
		if($bestseller_type == 1) // Case of manual picking
		{	
			$sql_bestsel_all	=	"SELECT count(a.product_id)  
							FROM 
								products a,general_settings_site_bestseller b 
							WHERE 
								a.sites_site_id = $ecom_siteid 
								AND a.product_id = b.products_product_id 
								AND b.bestsel_hidden = 0 
								AND a.product_hide ='N' ";
			$ret_bestsel_all 		= $db->query($sql_bestsel_all);
			list($tot_cnt)	= 	$db->fetch_array($ret_bestsel_all);		
			$bestsort_order			= $Settings_arr['product_orderby_bestseller'];
			// Building the sql 
			$sql_best				= '';
		
			// Call the function which prepares variables to implement paging
			$ret_arr 		= array();
			$pg_variable	= 'bestsell_pg';
			if ($_REQUEST['req']!='')// LIMIT for products is applied only if not displayed in home page
			{
				$start_var 		= prepare_paging($_REQUEST[$pg_variable],$prodperpage,$tot_cnt);
				$Limit			= " LIMIT ".$start_var['startrec'].", ".$prodperpage;
			}	
			else
				$Limit = '';
				
			$sql_best = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
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
								products a,general_settings_site_bestseller b 
							WHERE 
								a.sites_site_id = $ecom_siteid 
								AND a.product_id = b.products_product_id 
								AND b.bestsel_hidden = 0 
								AND a.product_hide ='N' 
							ORDER BY 
								$bestsort_by $bestsort_order 
							$Limit ";
			
		}
		elseif ($bestseller_type == 0) // case of automatic picking
		{
		$sql_bestsel_all = "SELECT a.product_id     
						FROM 
							
							products a,order_details b,orders p 
						WHERE 
							p.order_id=b.orders_order_id 
							AND p.sites_site_id=$ecom_siteid 
							AND p.order_status NOT IN ('CANCELLED','NOT_AUTH') 
							AND b.products_product_id=a.product_id 
							AND a.product_hide ='N' 
						GROUP BY 
							a.product_id ";
		$ret_bestsel_all 		= $db->query($sql_bestsel_all);
		$tot_cnt				= 	$db->num_rows($ret_bestsel_all);	

		$bestsort_order			= $Settings_arr['product_orderby_bestseller'];
		// Building the sql 
		$sql_best				= '';
	
		// Call the function which prepares variables to implement paging
		$ret_arr 		= array();
		$pg_variable	= 'bestsell_pg';
		if ($_REQUEST['req']!='')// LIMIT for products is applied only if not displayed in home page
		{
			$start_var 		= prepare_paging($_REQUEST[$pg_variable],$prodperpage,$tot_cnt);
			$Limit			= " LIMIT ".$start_var['startrec'].", ".$prodperpage;
		}	
		else
			$Limit = '';
			
		$sql_best = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
							a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
							a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
							product_total_preorder_allowed,a.product_applytax,a.product_shortdesc,sum(b.order_orgqty) as totcnt ,
							a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,a.product_variablesaddonprice_exists,
							a.product_variablecomboprice_allowed,a.product_variablecombocommon_image_allowed,a.default_comb_id,
							a.price_normalprefix,a.price_normalsuffix, a.price_fromprefix, a.price_fromsuffix,a.price_specialofferprefix, a.price_specialoffersuffix, 
							a.price_discountprefix, a.price_discountsuffix, a.price_yousaveprefix, a.price_yousavesuffix,a.price_noprice,
							a.product_averagerating,a.product_saleicon_show,a.product_saleicon_text,a.product_newicon_show,a.product_newicon_text,
							a.product_freedelivery          
						FROM 
							products a,order_details b,orders p 
						WHERE 
							p.order_id=b.orders_order_id 
							AND p.sites_site_id=$ecom_siteid 
							AND p.order_status NOT IN ('CANCELLED','NOT_AUTH') 
							AND b.products_product_id=a.product_id 
							AND a.product_hide ='N' 
						GROUP BY 
							a.product_id 
						ORDER BY 
							totcnt DESC   
						$Limit "; //orders a,order_details b,products p 
	}		
	
						$ret_prod = $db->query($sql_best);
						
						if ($db->num_rows($ret_prod))
						{
							$comp_active = isProductCompareEnabled();

							// Calling the function to get the type of image to shown for current 
							$pass_type = get_default_imagetype('midshelf');
							// Number of result to display on the page, will be in the LIMIT of the sql query also
							$querystring = ""; // if any additional query string required specify it over here
							switch($Settings_arr['bestseller_prodlisting'])
							{
								case '2row': // case of one in a row for normal
								case '3row':
								?>
									<table border="0" cellpadding="0" cellspacing="0" class="shelfBtable">
				<?php 				
				
				if($Captions_arr['BEST_SELLERS']['BEST_SELLER_CAPTION']!='')
									{
				?>				<tr><td colspan="3" class="shelfBheader" align="left"><?php echo stripslashes($Captions_arr['BEST_SELLERS']['BEST_SELLER_CAPTION']);?></td></tr>
				<?php				}
									//$desc = trim($shelfData['shelf_description']);
									if ($tot_cnt>0 and ($_REQUEST['req']!=''))
									{
				?>				<tr>
									<td colspan="3" class="pagingcontainertd" align="center">
				<?php 					$path = '';
										$query_string .= "";
										paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr); 	
				?>					</td>
								</tr>
				<?php				}
				?>				<tr>
									<td colspan="3">
										<table border="0" cellpadding="0" cellspacing="0" class="shelfBtableZ">
											<tr>
									<td colspan="3" class="" align="center">
				<?php				$cur_row = 1 ;
									$max_col = 3;
									$col_cnts = 0;
									while($row_prod = $db->fetch_array($ret_prod))
									{
										$col_cnts++;
										if($col_cnts==3)
										{
											$col_cnts =0;
											$maincls = 'catBoxWrap_threecolumn_rt';	
										}
										else
											$maincls = 'catBoxWrap_threecolumn';
										?>
										<div class="<?php echo $maincls?>">
<?php
												show_finanacebanner($row_prod);
												//if ($shelfData['shelf_showimage']==1)// Check whether description is to be displayed
												{
													?>
																<div class="featureimgqrap_three_column"><div><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" <?php echo getmicrodata_producturl()?> class="product_image" >
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
																</a></div> </div>
																<?php
												}
																//if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
																{
																?>
																<div class="prod_list_name_link_three_column">
																<span class="shelfBprodname_three_column">
																<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><span <?php echo getmicrodata_productname()?>><?php echo stripslashes($row_prod['product_name'])?></span></a>
																</span>

																</div>
																<?php
																}
												//if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
												{
												?>										
													<div class="prod_list_price_three">
										<?php			
										            $price_class_arr['ul_class'] 		= 'shelfBul_three_column';
													$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
													$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
													$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
													$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
													$price_class_arr['link_capt'] 	= 'appr_cls';

													//	echo show_Price($row_prod,$price_class_arr,'shelfcenter_1'); price_categorydetails_1_reqbreak
													echo show_Price($row_prod,$price_class_arr,'cat_detail_1');
													show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
												  }
																										
													?>																			
														
										</div>
										<div class="det_all_outer">

										<?php
										//if($shelfData['shelf_showrating']==1)
													{
														$module_name = 'mod_product_reviews';
														if(in_array($module_name,$inlineSiteComponents))
														{
															if($row_prod['product_averagerating']>=0)
															{
																$HTML_rating = display_rating($row_prod['product_averagerating'],1,'star-green.gif','star-white.gif',$row_prod['product_id']);
																?>
																	<div class="starBlock">
																	<div class="ratingStars">
																	<?php
																	echo $HTML_rating;
																	?>
																	</div>
																	</div>
																<?php
															}
														}
													}
										?>
										<?php			
										if($row_prod['product_saleicon_show']==1)
										{
											$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
											if($desc!='')
											{
						?>						<div class="prod_list_sale_three"><?php echo $desc?></div>
						<?php				}
										}
										/*
										if($row_prod['product_newicon_show']==1)
										{
											$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
											//if($desc!='')
											{
						?>						<div class="prod_list_new_three"><?php //echo $desc?></div>
						<?php				}
										}
										*/ 
										?>
										<div class="list_more_div">
												<?php show_moreinfo($row_prod,'list_more')?>
												</div>
										<div class="list_compare_div">
												<?php if($comp_active)  { // to display Compare icon
															dislplayCompareButton($row_prod['product_id']);
														}?>
												</div>		
												
										<?php
										if($row_prod['product_saleicon_show']==1)
										{
											$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
											if($desc!='')
											{
						?>						<div class="prod_list_sale_desc"><?php echo $desc?></div>
						<?php				}
										}
										?>
										</div>
										<?php
																	$frm_name = uniqid('catdet_');
													?>
																	<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
																	<input type="hidden" name="fpurpose" value="" />
																	<input type="hidden" name="fproduct_id" value="" />
																	<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
																	<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
																	<div class="prod_list_buy">
													<?php			$class_arr['ADD_TO_CART']       = '';
																	$class_arr['PREORDER']          = '';
																	$class_arr['ENQUIRE']           = '';
																	$class_arr['QTY_DIV']           = 'normal_shlfA_pdt_input';
																	$class_arr['QTY']               = ' ';
																	$class_td['QTY']				= 'prod_list_buy_a';
																	$class_td['TXT']				= 'prod_list_buy_b';
																	$class_td['BTN']				= 'prod_list_buy_c';
																	echo show_addtocart_v5($row_prod,$class_arr,$frm_name,false,'','',true,$class_td);
													?>						</div>
												</form>
										</div>
										<?php
									}
						?>				
						</td>
						</tr>
						</table>
									</td>
								</tr>
								</table>
									<?php
									break;									
								};
							
							
						}
		}
	};	
?>
