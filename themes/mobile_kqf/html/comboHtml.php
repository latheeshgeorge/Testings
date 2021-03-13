<?php
	/*############################################################################
	# Script Name 	: comboHtml.php
	# Description 	: Page which holds the display logic for middle combo
	# Coded by 		: Sny
	# Created on	: 22-Mar-2010
	# Modified by	: Sny
	# Modified On	: 23-Mar-2010
	##########################################################################*/
	class combo_Html
	{
		// Defining function to show the combo details
		function Show_Combo($title,$description,$combo_id)
		{ return;
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr;
			$combosort_by			= $Settings_arr['product_orderfield_combo'];
			$Captions_arr['COMBO']	= getCaptions('COMBO');
			//$prodperpage			= $Settings_arr['product_maxcntperpage_shelf'];// product per page
			$showqty				= $Settings_arr['show_qty_box'];// show the qty box
			switch ($combosort_by)
				{
				case 'custom': // case of order by customer field
					$combosort_by		= 'b.comboprod_order';
				break;
				case 'product_name': // case of order by product name
					$combosort_by		= 'a.product_name';
				break;
				case 'price': // case of order by price
					$combosort_by		= 'a.product_webprice';
				break;
				default: // by default order by product name
					$combosort_by		= 'a.product_name';
				break;
				case 'product_id': // case of order by price
					$prodsort_by		= 'a.product_id';
					break;
			};
				$combosort_order		= $Settings_arr['product_orderby_combo'];
				///$prev_shelf				= 0;
				 // Check whether shelf_activateperiodchange is set to 1
				 $active 	= $comboData['combo_activateperiodchange'];
				 // Get the list of products to be shown in current shelf
				$sql_prod = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
									a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
									a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
									a.product_total_preorder_allowed,a.product_applytax,a.product_shortdesc,a.product_variable_display_type,
									b.combo_discount,a.product_bonuspoints ,b.comboprod_id,
									a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,a.product_variablesaddonprice_exists,
									a.product_variablecomboprice_allowed,a.product_variablecombocommon_image_allowed,a.default_comb_id,
									a.price_normalprefix,a.price_normalsuffix, a.price_fromprefix, a.price_fromsuffix,price_specialofferprefix, a.price_specialoffersuffix, 
									a.price_discountprefix, a.price_discountsuffix, a.price_yousaveprefix, a.price_yousavesuffix,a.price_noprice,
									a.product_averagerating,a.product_saleicon_show,a.product_saleicon_text,a.product_newicon_show,a.product_newicon_text,
									a.product_freedelivery       
									 
								FROM 
									products a,combo_products b 
								WHERE 
									b.combo_combo_id = ".$combo_id." 
									AND a.product_id = b.products_product_id 
									AND a.product_hide = 'N' 
									AND (
												CASE product_alloworder_notinstock
														WHEN ('N') THEN 
																CASE product_preorder_allowed
																	WHEN ('Y') THEN product_total_preorder_allowed>0 
																ELSE 	
																	product_actualstock>0 
																END
														ELSE
															1
												END
											)											
								ORDER BY 
									$combosort_by $combosort_order ";
								
					$ret_prod = $db->query($sql_prod);
					if ($db->num_rows($ret_prod))
					{
						// Get the number of products actually in current combo deal
						$sql_cnt = "SELECT count(comboprod_id) as cnts 
											FROM 
												combo_products 
											WHERE 
												combo_combo_id = $combo_id 
												AND sites_site_id = $ecom_siteid ";
						$ret_cnt 	= $db->query($sql_cnt);
						list($tot_cnts)= $db->fetch_array($ret_cnt);;
						if ($tot_cnts==$db->num_rows($ret_prod))
							$proceed_combo = true;
						else
							$proceed_combo = false;
							
						$querystring = ""; // if any additional query string required specify it over here
						
						
						//section to check whether the comboproducts is in customer group
				/* Sony Jul 01, 2013 */
				global $discthm_group_prod_array;
				if(count($discthm_group_prod_array))
				{
				$sql_prod_chk = "SELECT a.product_id      
								FROM 
									products a,combo_products b 
								WHERE 
									b.combo_combo_id = ".$combo_id." 
									AND a.product_id = b.products_product_id 
									AND a.product_hide = 'N' 
									AND (
												CASE product_alloworder_notinstock
														WHEN ('N') THEN 
																CASE product_preorder_allowed
																	WHEN ('Y') THEN product_total_preorder_allowed>0 
																ELSE 	
																	product_actualstock>0 
																END
														ELSE
															1
												END
											)											
								ORDER BY 
									$combosort_by $combosort_order ";
					$ret_prod_chk = $db->query($sql_prod_chk);
					$prod_array   = array(); 
					while($row_prod_chk=$db->fetch_array($ret_prod_chk))
					{
					  $prod_array[] = $row_prod_chk['product_id'];
					}
					$inter_array = array();
					$inter_array = array_intersect($discthm_group_prod_array,$prod_array);
					//print_r($inter_array);

					//print_r($prod_array);
					if(!array_diff($prod_array, $inter_array) && !array_diff($inter_array, $prod_array))
					{
					   $proceed_combo1 = true;
					}
					else
					{
					   $proceed_combo = false;
					}
				}
						$HTML_tree = $HTML_heading = $HTML_desc  = $HTML_bundleprice = '' ;
						
						$HTML_tree = '
										<div class="tree_menu_con">
										<div class="tree_menu_top"></div>
										<div class="tree_menu_mid">
										<div class="tree_menu_content">
										<ul class="tree_menu">
										<li><a href="'.url_link('',1).'" title="'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a></li>
										<li>'.stripslashes($title).'</li>
										</ul>
										</div>
										</div>
										<div class="tree_menu_bottom"></div>
										</div>
									';
						if($proceed_combo)
						{
							// Get the combo bundle price
							$sql_combo = "SELECT combo_bundleprice 
											FROM 
												combo 
											WHERE 
												combo_id = $combo_id 
												AND sites_site_id  = $ecom_siteid 
											LIMIT 
												1";
							$ret_combo = $db->query($sql_combo);
							list($bundle_price) = $db->fetch_array($ret_combo); 
							if(($description !='')&&($description !='&nbsp;')&& ($description!='<br>' or $description!='<br/>'))
							{
								$HTML_desc = '
											 	<div class="combo_content_mid">
											   	'.$description.'
												</div>';
							}
							$sql_prod_combo = "SELECT a.product_id,b.comboprod_id
													FROM 
														products a,combo_products b 
													WHERE 
														b.combo_combo_id = ".$combo_id." 
														AND a.product_id = b.products_product_id 
														AND a.product_hide = 'N' 
														AND (
																	CASE product_alloworder_notinstock
																			WHEN ('N') THEN 
																					CASE product_preorder_allowed
																						WHEN ('Y') THEN product_total_preorder_allowed>0 
																					ELSE 	
																						product_actualstock>0 
																					END
																			ELSE
																				1
																	END
																)";										
								
							$ret_prod_tot = $db->query($sql_prod_combo);
							$price_prod_total=0;
							while($row_prod_totalprice = $db->fetch_array($ret_prod_tot))
							{     
								$sql_combination_tot 	= "SELECT comb_id
															FROM 
																combo_products_variable_combination 
															WHERE 
																combo_products_comboprod_id = ".$row_prod_totalprice['comboprod_id'];
								$ret_combination_tot 	= $db->query($sql_combination_tot);
								$tot_combinations_tot 	= $db->num_rows($ret_combination_tot);
								if($tot_combinations_tot>0)
								{
									$price_prod_total_combo=0;
									$curr_combo = 0;
									$prod_max = 0;
									while ($row_combination_tot = $db->fetch_array($ret_combination_tot))
									{
										$curr_combo ++;
										$comb_id_tot	= $row_combination_tot['comb_id'];
										$comb_price 	= show_price_combo($row_prod_totalprice['product_id'],$comb_id_tot);
										if($comb_price>$prod_max)
										{
											$prod_max = $comb_price;
											$prod_comb_id = $row_prod_totalprice['product_id'];
											$comb_price_final = $prod_max;
										}
									}
								}
								else
								{
									$comb_price_final = show_price_combo($row_prod_totalprice['product_id'],0);
								}
								$combprice_arr[$row_prod_totalprice['product_id']] = $comb_price_final;
								$price_prod_total	+= $comb_price_final;
							}
								$price_prod_total_save = $price_prod_total - $bundle_price;
								$HTML_bundleprice = '
														<div class="combo_price_con">
														<div class="combo_price_top"></div>
														<div class="combo_price_mid">
														<div class="combo_price_content">
														<div class="combo_yousave">';
								if ($price_prod_total_save>0)
								{
									$HTML_bundleprice .= $Captions_arr['COMBO']['COMBO_BUNDLE_ORG_PRICE'];
									$HTML_bundleprice .= ' '.print_price($price_prod_total_save,true,false);	
								}
								$HTML_bundleprice .='</div>
													<div class="combo_mainprice">'.stripslash_normal($Captions_arr['COMBO']['COMBO_BUNDLE_PRICE']).' '.print_price($bundle_price,true,false).'
													</div>
													<div class="combo_btn">
													<input name="submit_buycombo" type="button" class="combobig" id="submit_buycombo" value="'.stripslash_normal($Captions_arr['COMBO']['COMBO_BUY_ALL_BUTTON']).'" onclick="buy_combo();"/>
													</div>
													</div>
													</div>
													<div class="combo_price_bottom"></div>
													</div>
													';
						}			
					?>
						
						<?=$HTML_tree?>
						<?php
						if($proceed_combo)
						{
						?>
						<div>
						<form method="post" action="<?php url_link('manage_products.html')?>" name='buyall_combo' id="buyall_combo" class="frm_cls">
						<input type="hidden" name="fpurpose" value="Combo_Buyall" />
						<input type="hidden" name="fproduct_id" value="" />
						<input type="hidden" name="pass_url" value="'.<?php $_SERVER['REQUEST_URI']?>.'" />
						<input type="hidden" name="product_ids" id="product_ids" value="" />
						<input type="hidden" name="product_qtys" id="product_qtys" value="" />
						<?php
							echo $HTML_desc;
							echo $HTML_bundleprice;
							$combo_pdt_cnt = 0;
							$product_ids = '';
							// Calling the function to get the type of image to shown for current 
							$pass_type = 'image_gallerythumbpath';//get_default_imagetype('midcombo');
							$cur_cnt = 0;
							$tot_prods = $db->num_rows($ret_prod);	
							while($row_prod = $db->fetch_array($ret_prod))
							{    
								// Overriding the product discount with the % set for the current combo
								$row_prod['product_discount_enteredasval'] 	= 0;
								$row_prod['product_discount'] 	=	$row_prod['combo_discount'];
								if($product_ids!='')
									$product_ids  .= ",".$row_prod['product_id'];
								else
									$product_ids  .= $row_prod['product_id'];
								$cur_cnt++;
								$HTML_prodname = $HTML_prodimg = $HTML_plus = '';
								$HTML_prodname = '<div class="combo_pdt_name"><a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">'.stripslashes($row_prod['product_name']).'</a></div>';
								if($cur_cnt<$tot_prods)
								{
									$HTML_plus = '<div ><img src="'.url_site_image('combo-plus.gif',1).'"/></div>';
								}
								else
									$HTML_plus = '';	
								$HTML_prodimg = '<a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">';
								// Calling the function to get the image to be shown
								$img_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,0,0,1);
								if(count($img_arr))
								{
									$HTML_prodimg .= show_image(url_root_image($img_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name'],'','',1);
								}
								else
								{
									// calling the function to get the default image
									$no_img = get_noimage('prod',$pass_type); 
									if ($no_img)
									{
										$HTML_prodimg .=show_image($no_img,$row_prod['product_name'],$row_prod['product_name'],'','',1);
									}	
								}	
								$HTML_prodimg .= '</a>';	
								?>
								<div class="combo_pdt_con">
								<div class="combo_pdt_top"></div>
								<div class="combo_pdt_mid">
								<div class="combo_pdt_content">
								<?=$HTML_prodname ?>
								<div class="combo_pdt_img_otr">
								<div class="combo_pdt_img">
								<div>
								<?=$HTML_prodimg?>
								<?=$HTML_plus?>
								</div>
								</div>
								<?php
								// Check whether combinations exists for current product in combo_products_variable_combination table
								$sql_combination 	= "SELECT comb_id
														FROM 
															combo_products_variable_combination 
														WHERE 
															combo_products_comboprod_id = ".$row_prod['comboprod_id'];
								$ret_combination 	= $db->query($sql_combination);
								$tot_combinations 	= $db->num_rows($ret_combination);
								if($tot_combinations) // Case if combination exists
								{	
									$cur_combination = 0;
								?>	
									<div class="combo_pdt_des_outr">
								<?php	
									while ($row_combination = $db->fetch_array($ret_combination))
									{
										$row_prod['combination_id'] = $comb_id = $row_combination['comb_id'];
										$row_prod['comboprod_id'] 	= $prodmap_id = $row_prod['comboprod_id'];
										$cur_combination++;
									?>
										<div class="combo_pdt_des">
										<div class="combo_pdt_radio"><input type="radio" name="combprod_map_<?php echo $prodmap_id?>" id="combprod_map_<?php echo $prodmap_id?>" value="<?php echo $comb_id?>" <?php echo ($cur_combination==1)?'checked="checked"':''?> /></div>
									<?php	
										$sql_comb_det = "SELECT a.var_id,a.var_value_id 
															FROM 
																combo_products_variable_combination_map a,product_variables b
															WHERE 
																combo_products_variable_combination_comb_id = $comb_id 
																AND a.var_id=b.var_id 
															ORDER BY 
																b.var_order";
										$ret_comb_det = $db->query($sql_comb_det);
										if ($db->num_rows($ret_comb_det) )
										{
										?>
											<div class="combo_pdt_var_outr">
										<?php
											while ($row_comb_det = $db->fetch_array($ret_comb_det))
											{
												// Get the name of variable 
												$sql_var = "SELECT var_id,var_name,var_value_exists 
																FROM 
																	product_variables 
																WHERE 
																	var_id = ".$row_comb_det['var_id']." 
																LIMIT 
																	1";
												$ret_var = $db->query($sql_var);
												if($db->num_rows($ret_var))
												{
													$row_var = $db->fetch_array($ret_var);
												} 
												if($row_var['var_value_exists']==1)
												{
													// Get the name of variable value
													$sql_vardate = "SELECT var_value 
																	FROM 
																		product_variable_data  
																	WHERE 
																		var_value_id = ".$row_comb_det['var_value_id']." 
																	LIMIT 
																		1";
													$ret_vardata = $db->query($sql_vardate);
													if($db->num_rows($ret_vardata))
													{
														$row_vardata = $db->fetch_array($ret_vardata);
													} 
												}
												else
													$row_vardata = array();
											?>	
													
												<div class="combo_pdt_var"><?php echo stripslash_normal($row_var['var_name']);?>
												<span>
												<?php
												if ($row_var['var_value_exists']==1)
												{
													echo stripslash_normal($row_vardata['var_value']);
													if($comb_id==0)
													{
												?>
														<input type="hidden" name="varhold<?=$row_prod['product_id']?>_<?php echo $row_var['var_id']?>" id="varhold<?=$row_prod['product_id']?>_<?php echo $row_var['var_id']?>" value="<?php echo $row_comb_det['var_value_id']?>" />
												<?php
													}
													else
													{
												?>
														<input type="hidden" name="combprod_<?php echo $comb_id?>_<?=$row_prod['product_id']?>_<?php echo $row_var['var_id']?>" id="combprod_<?php echo $comb_id?>_<?=$row_prod['product_id']?>_<?php echo $row_var['var_id']?>" value="<?php echo $row_comb_det['var_value_id']?>" />									
												<?php	
													}	
												}
												else
												{
													if($comb_id==0)
													{
												?>
														<input type="hidden" name="varhold<?=$row_prod['product_id']?>_<?php echo $row_var['var_id']?>" id="varhold<?=$row_prod['product_id']?>_<?php echo $row_var['var_id']?>" value="1" />
												<?php
													}
													else
													{
												?>
														<input type="hidden" name="combprod_<?php echo $comb_id?>_<?=$row_prod['product_id']?>_<?php echo $row_var['var_id']?>" id="combprod_<?php echo $comb_id?>_<?=$row_prod['product_id']?>_<?php echo $row_var['var_id']?>"  value="1" />									
												<?php	
													}	
												}
												?>
												</span>
												</div>
											
										<?php
										}
										?>
											</div>
										<?php
										// Get distinct combinations exists for current product map irrespective of combination
										if($cur_combination == $tot_combinations)
										{
											$sql_comb = "SELECT comb_id 
															FROM 
																combo_products_variable_combination 
															WHERE 
																combo_products_comboprod_id = $prodmap_id";
											$ret_comb = $db->query($sql_comb);
											if($db->num_rows($ret_comb))
											{
												while ($row_comb = $db->fetch_array($ret_comb))
												{
													$comb_arr[] = $row_comb['comb_id'];
												}
												$sql_allvars = "SELECT distinct a.var_id 
																	FROM 
																		combo_products_variable_combination_map a, product_variables b
																	WHERE 
																		a.var_id = b.var_id
																		AND a.combo_products_variable_combination_comb_id IN (".implode(',',$comb_arr).") 
																	ORDER BY b.var_order";
												$ret_allvars = $db->query($sql_allvars);
												if($db->num_rows($ret_allvars))
												{
													while($row_allvars = $db->fetch_array($ret_allvars))
													{
												?>
														<input type="hidden" name="var<?=$row_prod['product_id']?>_<?php echo $row_allvars['var_id']?>" id="var<?=$row_prod['product_id']?>_<?php echo $row_allvars['var_id']?>" value="" />				
												<?php	
													}
												}
											}
										?>
										<input type="hidden" class="quainput" name="qty_<?=$row_prod['product_id']?>"  value="1" />
										<?php	
										}
										}
										?>
										</div>
										<?php
									}
									?>
									<div class="combo_pdt_price_outr">
									<div class="combo_pdt_priceA"><?php echo $Captions_arr['COMBO']['COMBO_BUNDLE_DEAL_PRICE']?>  <?php echo print_price($row_prod['combo_discount'],true,false)?>  </div>
									<div class="combo_pdt_priceB"><?php echo $Captions_arr['COMBO']['COMBO_BUNDLE_SAVE']?> 
									<?php 
										$save_price = $combprice_arr[$row_prod['product_id']] - $row_prod['combo_discount'];
										echo print_price($save_price,true,false)
									?>
									</div>
									</div>
									<?php
								}
								else
								{
								?>
								<div class="combo_pdt_des_outr">
								<div class="combo_pdt_des">
								<div class="combo_short_desc"><?php echo stripslashes($row_prod['product_shortdesc']);?></div>
								<input type="hidden" class="quainput" name="qty_<?=$row_prod['product_id']?>"  value="1" />
								<div class="combo_pdt_price_outr">
					            <div class="combo_pdt_priceA"><?php echo $Captions_arr['COMBO']['COMBO_BUNDLE_DEAL_PRICE']?>  <?php echo print_price($row_prod['combo_discount'],true,false)?>  </div>
					            <div class="combo_pdt_priceB"><?php echo $Captions_arr['COMBO']['COMBO_BUNDLE_SAVE']?> 
								<?php 
										$save_price = $combprice_arr[$row_prod['product_id']] - $row_prod['combo_discount'];
										echo print_price($save_price,true,false)
									?>
								</div>
					            </div>
						        </div>
								</div>
								<?php
								}
								?>
									
								</div>
								</div>
								</div>
								<div class="combo_pdt_bottom"></div>
								</div>
								<?php
							}
							?>
							<div class="combo_price_con">
							<div class="combo_price_top"></div>
							<div class="combo_price_mid">
							<div class="combo_price_content">
							
							
							<div class="combo_yousave">
							<?
								if ($price_prod_total_save>0)
								{
									echo $Captions_arr['COMBO']['COMBO_BUNDLE_ORG_PRICE'];
									echo ' '.print_price($price_prod_total_save,true,false);	
								}	
							?>
							</div>
							<div class="combo_mainprice">
							<?php 
								echo stripslash_normal($Captions_arr['COMBO']['COMBO_BUNDLE_PRICE']).' ';
								echo print_price($bundle_price,true,false);?>
							</div>
							<div class="combo_btn">
							<input name="submit_buycombo" type="button" class="combobig" id="submit_buycombo" value="<?=stripslash_normal($Captions_arr['COMBO']['COMBO_BUY_ALL_BUTTON'])?>" onclick="buy_combo();"/>
							</div>
							</div>
							</div>
							<div class="combo_price_bottom"></div>
							</div>
							</form>
							</div>
							<?php
						}
						else // case if combo deal cannot be displayed since some of the products are out of stock
						{
						?>
						<div class="combo_hdr_con">
						<div class="combo_hdr_top"></div>
						<div class="combo_hdr_mid">
						<div class="combo_hdr_content">
						<?php  echo stripslashes($title);?>
						</div>
						</div>
						</div>
						<div class="combo_content_mid">
						<?php 
								echo stripslash_normal($Captions_arr['COMBO']['COMBO_DEAL_CANNOT_DISPLAY']);
						?>
						</div>
						<?php	
						}
						
					}	
					else // Case if nothing is to be displayed for current combo
					{
					?>
						<div class="tree_menu_con">
						<div class="tree_menu_top"></div>
						<div class="tree_menu_mid">
						<div class="tree_menu_content">
						<ul class="tree_menu">
						<li><a href="<? url_link('');?>" title="<?=stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']);?>"><?=stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']);?></a></li>
						<li><?php  echo $title;?></li>
						</ul>
						</div>
						</div>
						<div class="tree_menu_bottom"></div>
						</div>
						<div class="combo_hdr_con">
						<div class="combo_hdr_top"></div>
						<div class="combo_hdr_mid">
						<div class="combo_hdr_content">
						<?php  echo $title;?>
						</div>
						</div>
						</div>
						<div class="combo_content_mid">
						<?php 
						echo stripslash_normal($Captions_arr['COMBO']['COMBO_DEAL_CANNOT_DISPLAY']);
						?>
						</div>
					<?php
					}
			
	}
	function Show_Combo_Multiple($ret_combos,$combprod_id,$deal_mod)
	{ 
		global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr;
		$Captions_arr['COMBO']	= getCaptions('COMBO');
		$combosort_by			= $Settings_arr['product_orderfield_combo'];
                                                                                                                                                                   		$showqty				= $Settings_arr['show_qty_box'];// show the qty box
		switch ($combosort_by)
			{
			case 'custom': // case of order by customer field
				$combosort_by		= 'b.comboprod_order';
			break;
			case 'product_name': // case of order by product name
				$combosort_by		= 'a.product_name';
			break;
			case 'price': // case of order by price
				$combosort_by		= 'a.product_webprice';
			break;
			default: // by default order by product name
				$combosort_by		= 'a.product_name';
			break;
			case 'product_id': // case of order by price
				$combosort_by		= 'a.product_id';
				break;
		};
		$combosort_order		= $Settings_arr['product_orderby_combo'];
		if($deal_mod!='middle_area') // Show the tree menu only if coming to show the deal in middle area in home, category, product or static pages
		{
		?>
			<div class="tree_menu_con">
			<div class="tree_menu_top"></div>
			<div class="tree_menu_mid">
			<div class="tree_menu_content">
			<ul class="tree_menu">
			<li><a href="<? url_link('');?>" title="<?=stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']);?>"><?=stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']);?></a></li>
			<?php
				if($deal_mod=='showprodrelated') // case if coming to show deal with selected products only
				{
					// Get the name of current product
					$sql_prodname = "SELECT product_name 
									FROM 
										products 
									WHERE 
										product_id = ".$combprod_id." 
									LIMIT 
										1";
					$ret_prodname= $db->query($sql_prodname);
					if($db->num_rows($ret_prodname))
					{
						$row_prodname  	= $db->fetch_array($ret_prodname);
						$prodname		= '<li><a href="'.url_product($combprod_id,$row_prodname['product_name'],2).'" title="'.stripslash_normal($row_prodname['product_name']).'">'.stripslash_normal($row_prodname['product_name']).'</a></li> '; 
						echo $prodname;
					}	  
				}
				if($Captions_arr['COMBO']['COMBO_BUNDLED_OFFER']!='')
			    echo '<li><a href="#">'.stripslash_normal($Captions_arr['COMBO']['COMBO_BUNDLED_OFFER']).'</a></li>';
			?>
			</ul>
			</div>
			</div>
			<div class="tree_menu_bottom"></div>
			</div>
		<?
		}
		$sql 				= "SELECT general_comboall_topcontent,general_comboall_bottomcontent FROM general_settings_sites_common WHERE sites_site_id=".$ecom_siteid;
		$res_admin 			= $db->query($sql);
		$fetch_arr_admin 	= $db->fetch_array($res_admin);
		$HTML_topdesc ='';	
			if($fetch_arr_admin['general_comboall_topcontent']!='')
			{
			$HTML_topdesc .='
				<div class="cate_content_bottom" >'.$fetch_arr_admin['general_comboall_topcontent'].'
					</div>		
				';
			}
		echo $HTML_topdesc;
		$max_cnt = 3;
		$cur_cnt = 0;
		// Fetch the details of combos
		while ($row_combos = $db->fetch_array($ret_combos))
		{
			$active 	= $row_combos['combo_activateperiodchange'];
			$combo_id  = $row_combos['combo_id'];
			 // Get the list of products to be shown in current shelf
			$sql_prod = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
								a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
								a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
								a.product_total_preorder_allowed,a.product_applytax,a.product_shortdesc,a.product_variable_display_type,
								b.combo_discount,a.product_bonuspoints ,b.comboprod_id,
								a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,a.product_variablesaddonprice_exists,
								a.product_variablecomboprice_allowed,a.product_variablecombocommon_image_allowed,a.default_comb_id,
								a.price_normalprefix,a.price_normalsuffix, a.price_fromprefix, a.price_fromsuffix,price_specialofferprefix, a.price_specialoffersuffix, 
								a.price_discountprefix, a.price_discountsuffix, a.price_yousaveprefix, a.price_yousavesuffix,a.price_noprice 
							FROM 
								products a,combo_products b 
							WHERE 
								b.combo_combo_id = ".$combo_id." 
								AND a.product_id = b.products_product_id 
								AND a.product_hide = 'N' 
								AND (
											CASE product_alloworder_notinstock
													WHEN ('N') THEN 
															CASE product_preorder_allowed
																WHEN ('Y') THEN product_total_preorder_allowed>0 
															ELSE 	
																product_actualstock>0 
															END
													ELSE
														1
											END
										)											
							ORDER BY 
								$combosort_by $combosort_order ";
							
			$ret_prod = $db->query($sql_prod);
			// Get the number of products actually in current combo deal
			$sql_cnt = "SELECT count(comboprod_id) as cnts 
								FROM 
									combo_products 
								WHERE 
									combo_combo_id = $combo_id 
									AND sites_site_id = $ecom_siteid ";
			$ret_cnt 	= $db->query($sql_cnt);
			list($tot_cnts)= $db->fetch_array($ret_cnt);;
			if ($tot_cnts==$db->num_rows($ret_prod))
				$proceed_combo = true;
			else
				$proceed_combo = false;
			//section to check whether the comboproducts is in customer group
				/* Sony Jul 01, 2013 */
				global $discthm_group_prod_array;
				$sql_prod_chk = "SELECT a.product_id      
								FROM 
									products a,combo_products b 
								WHERE 
									b.combo_combo_id = ".$combo_id." 
									AND a.product_id = b.products_product_id 
									AND a.product_hide = 'N' 
									AND (
												CASE product_alloworder_notinstock
														WHEN ('N') THEN 
																CASE product_preorder_allowed
																	WHEN ('Y') THEN product_total_preorder_allowed>0 
																ELSE 	
																	product_actualstock>0 
																END
														ELSE
															1
												END
											)											
								ORDER BY 
									$combosort_by $combosort_order ";
					$ret_prod_chk = $db->query($sql_prod_chk);
					$prod_array   = array(); 
					while($row_prod_chk=$db->fetch_array($ret_prod_chk))
					{
					  $prod_array[] = $row_prod_chk['product_id'];
					}
					$inter_array = array();
					$inter_array = array_intersect($discthm_group_prod_array,$prod_array);
					//print_r($inter_array);

					//print_r($prod_array);
					if(!array_diff($prod_array, $inter_array) && !array_diff($inter_array, $prod_array))
					{
					   $proceed_combo1 = true;
					}
					else
					{
					   $proceed_combo = false;
					}
					//end of customer group check
			if($proceed_combo==true)
			{
				$HTML_comboname = $HTML_viewall = '';
				if ($row_combos['combo_name'] and $row_combos['combo_hidename']==0)
				{
					$HTML_comboname = '<a href="#">'.stripslashes($row_combos['combo_name']).'</a>';
				}
				$HTML_viewall = '<a href="'.url_combo($row_combos['combo_id'],$row_combos['combo_name'],1).'">View Deal</a>';
				$bundle_price = $row_combos['combo_bundleprice'];
				$cnt=1;
				$cur_col = 0;
				$max_col = 2;
				$proddet_arr = array();
				$combo_id 		= $row_combos['combo_id'];
				$sql_prod_combo = "SELECT a.product_id,b.comboprod_id
										FROM 
											products a,combo_products b 
										WHERE 
											b.combo_combo_id = ".$combo_id." 
											AND a.product_id = b.products_product_id 
											AND a.product_hide = 'N' 
											AND (
														CASE product_alloworder_notinstock
																WHEN ('N') THEN 
																		CASE product_preorder_allowed
																			WHEN ('Y') THEN product_total_preorder_allowed>0 
																		ELSE 	
																			product_actualstock>0 
																		END
																ELSE
																	1
														END
													)";										
								
				$ret_prod_tot = $db->query($sql_prod_combo);
				$price_prod_total=0;
				while($row_prod_totalprice = $db->fetch_array($ret_prod_tot))
				{     
					$sql_combination_tot 	= "SELECT comb_id
												FROM 
													combo_products_variable_combination 
												WHERE 
													combo_products_comboprod_id = ".$row_prod_totalprice['comboprod_id'];
					$ret_combination_tot 	= $db->query($sql_combination_tot);
					$tot_combinations_tot 	= $db->num_rows($ret_combination_tot);
					if($tot_combinations_tot>0)
					{
						$price_prod_total_combo=0;
						$curr_combo = 0;
						$prod_max = 0;
						while ($row_combination_tot = $db->fetch_array($ret_combination_tot))
						{
							$curr_combo ++;
							$comb_id_tot	= $row_combination_tot['comb_id'];
							$comb_price 	= show_price_combo($row_prod_totalprice['product_id'],$comb_id_tot);
							if($comb_price>$prod_max)
							{
								$prod_max = $comb_price;
								$prod_comb_id = $row_prod_totalprice['product_id'];
								$comb_price_final = $prod_max;
							}
						}
					}
					else
					{
						$comb_price_final = show_price_combo($row_prod_totalprice['product_id'],0);
					}
					$combprice_arr[$row_prod_totalprice['product_id']] = $comb_price_final;
					$price_prod_total	+= $comb_price_final;
				}
				$price_prod_total_save = $price_prod_total - $bundle_price;
			?>
					
				<div class="combo_pdt_con">
				<div class="combo_pdt_top"></div>
				<div class="combo_pdt_mid">
				<div class="combo_pdt_content">
				
				<div class="combo_deal_name">
				<div class="combo_deal_nameleft">
				<?=$HTML_comboname?>
				</div>
				<div class="combo_deal_namebtn"><div class="combo_deal_view"><div><?=$HTML_viewall?></div></div></div>
				</div>
				<div class="combo_deal_img_otr"><div class="combo_deal_img">
				<?php
				while ($row_prod = $db->fetch_array($ret_prod))
				{
					$row_prod['product_discount_enteredasval'] 	= 0;
					$row_prod['product_discount'] 				= $row_prod['combo_discount'];
					$proddet_arr[] = $row_prod;
					if($cur_col==0)
					{
						echo '<div class="combo_deal_img_inner" >';
					}
					$HTML_image = '';	
					// Calling the function to get the type of image to shown for current 
					$pass_type = 'image_iconpath';
					// Calling the function to get the image to be shown
					$img_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,0,0,1);
					if(count($img_arr))
					{
						$HTML_image = show_image(url_root_image($img_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name'],'','',1);
					}
					else
					{
						// calling the function to get the default image
						$no_img = get_noimage('prod',$pass_type); 
						if ($no_img)
						{
							$HTML_image = show_image($no_img,$row_prod['product_name'],$row_prod['product_name'],'','',1);
						}	
					}
					if ($HTML_image!='')
					{
						$HTML_image = '<a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.$Captions_arr['COMMON']['SHOW_DEAL'].'">'.$HTML_image.'</a>';
					}
				?>
					
					<div class="combo_deal_inner_img" >
					<?=$HTML_image?>
					<div class="combo_deal_img_no"><?php echo $cnt++?></div>
					</div>
					<?php 
					if($cur_col==0)
					{
						echo '<div class="combo_deal_img_sep" ></div>';
					}
					$cur_col++;
					if ($cur_col>=$max_col)
					{
						$cur_col = 0;
						echo '</div>';
					}	
				}
				if($cur_col>0 and $cur_col<$max_col)
					echo '</div>';
				?>
				</div>
				<div class="combo_deal_name_outr">
				<?php
				$description = stripslashes($row_combos['combo_description']);
				if(($description !='')&&($description !='&nbsp;')&& ($description!='<br>' or $description!='<br/>'))
				{
				?>
					<div class="combo_deal_des"><?php echo $description?></div>	
				<?php
				}
				for($i=0;$i<count($proddet_arr);$i++)
				{
				?>	
					<div class="combo_deal_namelink">
					<div class="combo_deal_name_no"><?php echo ($i+1)?></div>
					<div class="combo_deal_namelinkinner"><a href="<?php url_product($proddet_arr[$i]['product_id'],$proddet_arr[$i]['product_name'])?>" title="<?php echo stripslashes($row_combos['combo_name'])?>"><?php echo stripslashes($proddet_arr[$i]['product_name'])?></a></div>
					</div>
				<?php
				}
				?>
				<div class="combo_pdt_price_outr">
				<div class="combo_pdt_priceC">
				<?php 
					echo stripslash_normal($Captions_arr['COMBO']['COMBO_BUNDLE_PRICE']).' ';
					echo print_price($bundle_price,true,false);
				?>
				</div>
				
				<?
				if ($price_prod_total_save>0)
				{
					echo '<div class="combo_pdt_priceB">';
					echo $Captions_arr['COMBO']['COMBO_BUNDLE_ORG_PRICE'];
					echo ' '.print_price($price_prod_total_save,true,false);	
					echo '</div>';
				}	
				?>
				</div>
				</div>
				</div>
				</div>
				</div>
				<div class="combo_pdt_bottom"></div>
				</div>	
			<?php
			}
		}
			if($fetch_arr_admin['general_comboall_bottomcontent']!='')
			{
			$HTML_bottomdesc .='
				<div class="cate_content_bottom" >'.$fetch_arr_admin['general_comboall_bottomcontent'].'
					</div>		
				';
			}
		   echo $HTML_bottomdesc;
	}	
};	
?>
