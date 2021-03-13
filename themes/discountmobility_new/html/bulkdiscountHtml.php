<?php
	/*############################################################################
	# Script Name 	: bestsellerHtml.php
	# Description 	: Page which holds the display logic for middle Bestsellers
	# Coded by 		: Anu
	# Created on	: 28-Mar-2008
	# Modified by	: Anu
	# Modified On	: 28-Mar-2008
	##########################################################################*/
	class bulkdiscount_Html
	{
		// Defining function to show the shelf details
		function Show_Bulkdiscount()
		{
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents,$ecom_tax_total_arr,$PriceSettings_arr;

		$Captions_arr['BULKDISC_PROD'] 	= getCaptions('BULKDISC_PROD');
		$Captions_arr['PROD_DETAILS'] 	= getCaptions('PROD_DETAILS');
		$bottom_content = '';
		$sql_bottom = "SELECT general_multibuy_bottomcontent 
							FROM 
								general_settings_sites_common 
							WHERE 
								sites_site_id = $ecom_siteid 
							LIMIT 
								1";
		$ret_bottom = $db->query($sql_bottom);
		if($db->num_rows($ret_bottom))
		{
			$row_bottom = $db->fetch_array($ret_bottom);
			$bottom_content = stripslashes($row_bottom['general_multibuy_bottomcontent']);
		}
			// query for display 	title
		$prodsort_by		= ($_REQUEST['bulkdet_sortby'])?$_REQUEST['bulkdet_sortby']:'product_name';
		$prodperpage		= ($_REQUEST['bulkdet_prodperpage'])?$_REQUEST['bulkdet_prodperpage']:$Settings_arr['product_maxcntperpage_bestseller'];// product per page
		$prodsort_order		= ($_REQUEST['bulkdet_sortorder'])?$_REQUEST['bulkdet_sortorder']:$Settings_arr['product_orderby_bestseller'];
		$sql_tot			=	"SELECT count(a.product_id)  
									FROM 
										products a
									WHERE 
										a.sites_site_id = $ecom_siteid 
										AND a.product_hide ='N' 
										AND a.product_bulkdiscount_allowed='Y'";
		$ret_tot 			= $db->query($sql_tot);
		list($tot_cnt)		= 	$db->fetch_array($ret_tot);		
		// Building the sql 
		$sql_best			= '';
		// Call the function which prepares variables to implement paging
		$ret_arr 			= array();
		$pg_variable		= 'bulk_pg';
		$start_var 			= prepare_paging($_REQUEST[$pg_variable],$prodperpage,$tot_cnt);
		$Limit				= " LIMIT ".$start_var['startrec'].", ".$prodperpage;
		switch ($prodsort_by)
		{
			case 'product_name': // case of order by product name
			$prodsort_bysql		= 'a.product_name';
			break;
			case 'price': // case of order by price
			$prodsort_bysql		= 'a.product_webprice';
			break;
			case 'product_id': // case of order by price
			$prodsort_bysql		= 'a.product_id';
			break;
			default: // by default order by product name
			$prodsort_bysql		= 'a.product_name';
			break;
		};
		$sql_prod = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
					a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
					a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
					product_total_preorder_allowed,a.product_applytax,a.product_shortdesc,product_bonuspoints,
					a.product_stock_notification_required,a.product_alloworder_notinstock,product_variables_exists,product_variablesaddonprice_exists,
					a.product_variablecomboprice_allowed,a.product_variablecombocommon_image_allowed,a.default_comb_id,
					a.price_normalprefix,a.price_normalsuffix, a.price_fromprefix, a.price_fromsuffix,price_specialofferprefix, a.price_specialoffersuffix, 
					a.price_discountprefix, a.price_discountsuffix, a.price_yousaveprefix, a.price_yousavesuffix,a.price_noprice,
					a.product_averagerating,a.product_saleicon_show,a.product_saleicon_text,a.product_newicon_show,a.product_newicon_text,
					a.product_freedelivery        
				FROM 
					products a
				WHERE 
					a.product_hide = 'N' 
					AND a.sites_site_id = $ecom_siteid
					AND a.product_bulkdiscount_allowed='Y' 
					ORDER BY 
							$prodsort_bysql $prodsort_order 
						$Limit ";
		$ret_prod = $db->query($sql_prod);
	
						
						if ($db->num_rows($ret_prod))
						{
							// Calling the function to get the type of image to shown for current 
							$pass_type = get_default_imagetype('midshelf');
							$prod_compare_enabled = isProductCompareEnabled();
							// Number of result to display on the page, will be in the LIMIT of the sql query also
							$querystring = ""; // if any additional query string required specify it over here
								?>
										<table border="0" cellpadding="0" cellspacing="0" class="shelfAtable">
										<tr>
											<td colspan="3" align="left"><div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?php  echo $Captions_arr['BULKDISC_PROD']['BULKDISC_PROD'];?></div></td>
										</tr>
									<?php
											if ($Captions_arr['BULKDISC_PROD']['BULKDISC_PROD_TOPMSG']!='')
											{
										?>
												<tr>
													<td colspan="3" class="shelfBproddes" align="left">
														<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bulk_hdr_table">
														<tr>
														<td class="bulk_hdr_left">&nbsp;</td>
														<td class="bulk_hdr_right"><?php echo str_replace('[tot_cnt]',$tot_cnt,stripslashes($Captions_arr['BULKDISC_PROD']['BULKDISC_PROD_TOPMSG']));?></td>
														</tr>
														</table>
													</td>
												</tr>
										<?php		
											}
										if ($tot_cnt>0 )
										{
										?>
											<tr>
												<td colspan="3" class="pagingcontainertd" align="center">
												<?php 
													$path = '';
													//show_paging($path,$_REQUEST[$pg_variable],$start_arr['counterstart'],$tot_cnt,$prodperpage,$pg_variable,'')
														$query_string .= "disp_id=".$_REQUEST['disp_id'];
														paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr,0); 	
												?>	
												</td>
											</tr>
										<?php
										}
										?>
                                        <tr>
									<td colspan="3" class="" align="center">
                                        <?php
											$max_col = 3;
											$cur_col = 0;
											$prodcur_arr = array();
											while($row_prod = $db->fetch_array($ret_prod))
											{
												$prodcur_arr[] = $row_prod;
												/*if($cur_col ==0)
													echo '<tr>';*/
												//##############################################################
												// Showing the title, description and image part for the product
												//##############################################################
										?>
												<div class="catBoxWrap_threecolumn">
<?php
												//if ($shelfData['shelf_showimage']==1)// Check whether description is to be displayed
												{
													?>
																<div class="featureimgqrap_three_column"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" <?php echo getmicrodata_producturl()?> class="product_image" >
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
																</a> </div>
																<?php
												}
																//if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
																{
																?>
																<div class="prod_list_name_link_three_column">
																<h1 class="shelfBprodname_three_column">
																<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><span <?php echo getmicrodata_productname()?>><?php echo stripslashes($row_prod['product_name'])?></span></a>
																</h1>

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
																$HTML_rating = display_rating($row_prod['product_averagerating'],1);
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
											//if($desc!='')
											{
						?>						<div class="prod_list_sale_three"><?php //echo $desc?></div>
						<?php				}
										}
										if($row_prod['product_newicon_show']==1)
										{
											$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
											//if($desc!='')
											{
						?>						<div class="prod_list_new_three"><?php //echo $desc?></div>
						<?php				}
										}
										?>
										<div class="list_more_div">
												<?php show_moreinfo($row_prod,'list_more')?>
												</div>
												
										<?php
										//if($row_prod['product_saleicon_show']==1)
										{
											$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
											//if($desc!='')
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
												$cur_col++;
												if ($cur_col>=$max_col)
												{
													/*echo "</tr>
														<tr>														
														<td class='shelfAtabletdA' colspan='3'></td>
														</tr>";*/
													$cur_tempcol = $cur_col = 0;
													//##############################################################
													// Showing the more info and add to cart links after each row in 
													// case of breaking to new row while looping
													//##############################################################
												}
											}
											?>
                                            </td>
                                            </tr>
                                            
                                            <?php
											// If in case total product is less than the max allowed per row then handle that situation
											if ($cur_col<$max_col and $cur_col>0)
											{
												echo "<td colspan='".($max_col-$cur_col)."'>&nbsp;</td></tr>
												<tr>														
												<td class='shelfAtabletdA' colspan='".$cur_col."'></td>
												<td colspan='".($max_col-$cur_col)."'></td>
												</tr>
												";
												$cur_tempcol = $cur_col = 0;
											}
										if($bottom_content!='')
										{	
										?>
										<tr>
										<td align="left" class="bulkdisc_bottom_desc" colspan="3">
										<?php echo $bottom_content?>
										</td>
										</tr>	
										<?php
										}
										?>
										</table>
								<?php		
							
						}
		}
	};	
?>
