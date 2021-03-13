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
			// query for display 	title
			$query_disp	= "SELECT 
								 display_title 
							FROM 
								display_settings 
							WHERE 
								sites_site_id='$ecom_siteid' 
								AND display_id ='$display_id'";
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
										a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,
										a.product_variablesaddonprice_exists,a.product_averagerating,a.product_saleicon_show,
										a.product_saleicon_text,a.product_newicon_show,a.product_newicon_text,a.product_freedelivery     
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
						$totcnt = $db->num_rows($ret_prod);
						
						if ($db->num_rows($ret_prod))
						{
							// Calling the function to get the type of image to shown for current 
							$pass_type = get_default_imagetype('midshelf');
							$comp_active = isProductCompareEnabled();
							// Number of result to display on the page, will be in the LIMIT of the sql query also
							$querystring = ""; // if any additional query string required specify it over here
								switch($Settings_arr['bestseller_prodlisting'])
								{
									case '1row': // case of one in a row for normal
										?>
										<div class="tree_con">
										<div class="tree_top"></div>
											<div class="tree_middle">
												<div class="pro_det_treemenu">
													<ul>
													<li><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> &gt;&gt; </li>
													<li> <?php  echo $cur_title;?> </li>
													</ul>
												</div>
											</div>
										<div class="tree_bottom"></div>
										</div>
										<?php 
										    if ($Captions_arr['BEST_SELLERS']['BEST_SELLER_CAPTION']!='')
									        { ?>
												<div class="mid_shelfB_name"><?php echo $Captions_arr['BEST_SELLERS']['BEST_SELLER_CAPTION']?></div>
											<?php
											}
											if ($tot_cnt>0 )
								          	{
											?>
											
											<div class="pagingcontainertd_outer" align="center">
												<div class="pagingcontainertd_lf" align="center">
													<?php 
													 paging_show_totalcount($tot_cnt,'Products',$start_var['pg'],$start_var['pages']);
													 ?>
													 
													</div>
												<div class="pagingcontainertd_rt" >
													 <div class="pagingcontainertd" align="center">
													<?
													$path = '';
													$query_string .= "";
													paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr,0); 	
													?>	
												    </div>
												 </div>
													
												</div>
											<?php
											}
											?>	
											<?php
											while($row_prod = $db->fetch_array($ret_prod))
											{
											?>
												 <div class="mid_shelfB">
													<table width="100%" border="0" cellpadding="0" cellspacing="0"  class="mid_shelfB_table">
													<tr>
														<td class="mid_shelfB_top_lf">&nbsp;</td>
														<td class="mid_shelfB_top_mid">
															<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="prod_infolink"><?php echo stripslashes($row_prod['product_name'])?></a>
														</td>
														<td class="mid_shelfB_top_rt">&nbsp;</td>
													</tr>
													<tr>
															<td colspan="3" class="mid_shelfB_mid" valign="top">
															<table border="0" cellpadding="0" cellspacing="0" width="100%">
															<tr>
															<td class="shelfBimg" valign="top">
															<?php
															if($row_prod['product_bulkdiscount_allowed']=='Y')
															{
															?>
																<div class="bulk_discounta"></div>
															<?php
															}
															else	
															{
															?>
																<div class="bulk_discounta_blank"></div>
															<?php
															}
															?>
																<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="prod_infolink">
																<?php
																	// Calling the function to get the type of image to shown for current 
																	//$pass_type = get_default_imagetype('midshelf');
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
																if($comp_active)
																{
																	dislplayCompareButton($row_prod['product_id']);
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
															</td>
															<td class="shelfB_cnts" valign="top">
																<?php
																if($row_prod['product_freedelivery']==1)
																{	
																?>
																	<div class="free_deliverya"></div>
																<?php
																}
																else
																{	
																?>
																	<div class="free_deliverya_blank"></div>
																<?php
																}
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
																		<h6 class="shelfBproddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6>
																	<?php
																		$price_class_arr['ul_class'] 		= 'shelfAul';
																		$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
																		$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
																		$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
																		$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
																		echo show_Price($row_prod,$price_class_arr,'shelfcenter_1');
																		show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
																		show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points																
																		?>
																	
														      </td>
														   </tr>
														   </table>
														  </td>
													</tr>
													<tr>
													<td class="mid_shelfB_btm_lf">&nbsp;</td>
													<td class="mid_shelfB_btm_mid">
														<?php
														$frm_name = uniqid('bestseller_');
														?>	
															<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
															<input type="hidden" name="fpurpose" value="" />
															<input type="hidden" name="fproduct_id" value="" />
															<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
															<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
															<div class="infodiv">
																<div class="infodivleft">		<?php show_moreinfo($row_prod,'infolink')?>	</div>
																<div class="infodivright"><?php
																	$class_arr 					= array();
																	$class_arr['ADD_TO_CART']	= 'quantity_infolink';
																	$class_arr['PREORDER']		= 'quantity_infolink';
																	$class_arr['ENQUIRE']		= 'quantity_infolink';
																	show_addtocart($row_prod,$class_arr,$frm_name)
																?> </div>
															</div>
															</form>
													  </td>
													<td class="mid_shelfB_btm_rt">&nbsp;</td>
													</tr>
													</table>
													
											</div>
									    <?php 
									 	}


									break;
									case '2row': // case of three in a row for normal
									?>
									<div class="tree_con">
									<div class="tree_top"></div>
											<div class="tree_middle">
												<div class="pro_det_treemenu">
													<ul>
													<li><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> &gt;&gt; </li>
													<li> <?php  echo $cur_title;?> </li>
													</ul>
												</div>
											</div>
										<div class="tree_bottom"></div>
										</div>
									<?php
									if ($Captions_arr['BEST_SELLERS']['BEST_SELLER_CAPTION']!='')
									{
										?>
										<div class="mid_shelfA_name"><?php echo $Captions_arr['BEST_SELLERS']['BEST_SELLER_CAPTION']?></div>
									<?php
									}
									if ($tot_cnt>0 )
									{
									?>
									 
									<div class="pagingcontainertd_outer" align="center">
												<div class="pagingcontainertd_lf" align="center">
													<?php 
													 paging_show_totalcount($tot_cnt,'Products',$start_var['pg'],$start_var['pages']);
													 ?>
													 
													</div>
												<div class="pagingcontainertd_rt" >
													 <div class="pagingcontainertd" align="center">
													<?
													$path = '';
													//show_paging($path,$_REQUEST[$pg_variable],$start_arr['counterstart'],$tot_cnt,$prodperpage,$pg_variable,'')
													$query_string .= "";
													paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr,0); 	
													?>	
												    </div>
												 </div>
													
												</div>
									<?php
									}
									?>
									<table width="100%" border="0" cellpadding="0" cellspacing="0"  class="mid_shelfA">
									<tr>
									<?php
									$max_col = 2;
									$cur_col = 0;
									$prodcur_arr = array();
									while($row_prod = $db->fetch_array($ret_prod))
									{
										$cur_col++;
										$prodcur_arr[] = $row_prod;
										//##############################################################
										// Showing the title, description and image part for the product
										//##############################################################
										
										if($cur_col%2==0)
											$cls ='mid_shelfA_right'; 
										else
											$cls ='mid_shelfA_left'; 
										?>
										<td class="<?=$cls?>" valign="top">
										<table width="100%" border="0" cellpadding="0" cellspacing="0"  class="mid_shelfA_table">
											<tr>
												<td class="mid_shelfA_top_lf">&nbsp;</td>
												<td class="mid_shelfA_top_mid">
												<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="prod_infolink"><?php echo stripslashes($row_prod['product_name'])?></a>
												</td>
												<td class="mid_shelfA_top_rt">&nbsp;</td>
											</tr>
											<tr>
												<td colspan="3" class="mid_shelfA_mid" align="center">
												<?php 
												if($row_prod['product_bulkdiscount_allowed']=='Y')
												{
												?>
													<div class="bulk_discount"></div>
												<?php
												}
												else
												{
												?>
													<div class="bulk_discount_blank"></div>
												<?php
												}
											
												if($row_prod['product_freedelivery']==1)
												{	
												?>
													<div class="free_delivery"></div>
												<?php
												}
												else
												{	
												?>
													<div class="free_delivery_blank"></div>
												<?php
												}	
												?>
												<ul class="shelfAul">
												<li class="shelfAimg">
												<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
												<?php
												// Calling the function to get the type of image to shown for current 
												//$pass_type = get_default_imagetype('midshelf');
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
											if($comp_active)  
											{
												dislplayCompareButton($row_prod['product_id']);
											}?>
												</li>
												<?php
												$module_name = 'mod_product_reviews';
												if(in_array($module_name,$inlineSiteComponents))
												{
													if($row_prod['product_averagerating']>=0)
													{
													?>
														<li class="shelfB_rate">
														<?php
															display_rating($row_prod['product_averagerating']);
														?>
														</li>
													<?php
													}
												}	
												?>        
												<li class="shelfAproddesli"><h6 class="shelfAproddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6></li>                     
												<?php
												$price_class_arr['ul_class'] 		= 'shelfBul';
												$price_class_arr['normal_class'] 	= 'shelfAnormalprice';
												$price_class_arr['strike_class'] 	= 'shelfAstrikeprice';
												$price_class_arr['yousave_class'] 	= 'shelfAyousaveprice';
												$price_class_arr['discount_class'] 	= 'shelfAdiscountprice';
												echo show_Price($row_prod,$price_class_arr,'shelfcenter_3');
												show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
												show_bonus_points_msg($row_prod,'bonus_point_1row'); // Show bonus points
												?>
												</ul>	
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
												</td>
											</tr>
											<tr>
											<td class="mid_shelfA_btm_lf">&nbsp;</td>
											<?php $frm_name = uniqid('shelf_'); ?>
											<td class="mid_shelfA_btm_mid">
											
									<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
										<input type="hidden" name="fpurpose" value="" />
										<input type="hidden" name="fproduct_id" value="" />
										<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
										<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
											<div class="infodiv_shlfA">
											<div class="infodivleft_shlfA"><?php show_moreinfo($row_prod,'infolink')?></div>
											<div class="infodivright_shlfA">
											<?php
											$class_arr 					= array();
											$class_arr['ADD_TO_CART']	= 'quantity_infolink';
											$class_arr['PREORDER']		= 'quantity_infolink';
											$class_arr['ENQUIRE']		= 'quantity_infolink';
											show_addtocart($row_prod,$class_arr,$frm_name)
											?></div>
											</div></form></td>
											<td class="mid_shelfA_btm_rt">&nbsp;</td>
											</tr>
										</table>
										</td>
										<?
										if($totcnt==$cur_col)
										{ 
											if ($cur_col<2)
												echo '<td>&nbsp;</td>';
											echo "</tr>";
										}
										else
										{
										if ($cur_col%2==0)
										{
										echo "</tr>";
										//##############################################################
										// Showing the more info and add to cart links after each row in 
										// case of breaking to new row while looping
										//##############################################################
										echo "<tr>";
										}
										}	
									}
									
									?>
									</table>
								<?php		
									break;
									case '3row': // case of three in a row for normal
									?>
									<div class="tree_con">
									<div class="tree_top"></div>
											<div class="tree_middle">
												<div class="pro_det_treemenu">
													<ul>
													<li><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> &gt;&gt; </li>
													<li> <?php  echo $cur_title;?> </li>
													</ul>
												</div>
											</div>
										<div class="tree_bottom"></div>
										</div>
									<?php
									if ($Captions_arr['BEST_SELLERS']['BEST_SELLER_CAPTION']!='')
									{
										?>
										<div class="mid_shelfA_name"><?php echo $Captions_arr['BEST_SELLERS']['BEST_SELLER_CAPTION']?></div>
									<?php
									}
									if ($tot_cnt>0 )
									{
									?>
									 
									<div class="pagingcontainertd_outer" align="center">
												<div class="pagingcontainertd_lf" align="center">
													<?php 
													 paging_show_totalcount($tot_cnt,'Products',$start_var['pg'],$start_var['pages']);
													 ?>
													 
													</div>
												<div class="pagingcontainertd_rt" >
													 <div class="pagingcontainertd" align="center">
													<?
													$path = '';
													//show_paging($path,$_REQUEST[$pg_variable],$start_arr['counterstart'],$tot_cnt,$prodperpage,$pg_variable,'')
													$query_string .= "";
													paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr,0); 	
													?>	
												    </div>
												 </div>
													
												</div>
									<?php
									}
									?>
									<table width="100%" border="0" cellpadding="0" cellspacing="0"  class="mid_shelfA">
									<tr>
									<?php
									$max_col = 3;
									$cur_col = 0;
									$prodcur_arr = array();
									while($row_prod = $db->fetch_array($ret_prod))
									{
										$cur_col++;
										$prodcur_arr[] = $row_prod;
										//##############################################################
										// Showing the title, description and image part for the product
										//##############################################################
										
										if($cur_col%3==0)
											$cls ='mid_shelfB_right'; 
										else
											$cls ='mid_shelfB_left'; 
										?>
										<td class="<?=$cls?>" valign="top">
										<table width="100%" border="0" cellpadding="0" cellspacing="0"  class="mid_shelfA_table">
											<tr>
												<td class="mid_shelfA_top_lf">&nbsp;</td>
												<td class="mid_shelfA_top_mid">
												<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="prod_infolink"><?php echo stripslashes($row_prod['product_name'])?></a>
												</td>
												<td class="mid_shelfA_top_rt">&nbsp;</td>
											</tr>
											<tr>
												<td colspan="3" class="mid_shelfA_mid" align="center">
												<?php 
												if($row_prod['product_bulkdiscount_allowed']=='Y')
												{
												?>
													<div class="bulk_discount"></div>
												<?php
												}
												else
												{
												?>
													<div class="bulk_discount_blank"></div>
												<?php
												}
											
												if($row_prod['product_freedelivery']==1)
												{	
												?>
													<div class="free_delivery"></div>
												<?php
												}
												else
												{	
												?>
													<div class="free_delivery_blank"></div>
												<?php
												}	
												?>
												<ul class="shelfAul">
												<li class="shelfAimg">
												<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
												<?php
												// Calling the function to get the type of image to shown for current 
												//$pass_type = get_default_imagetype('midshelf');
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
											if($comp_active)  
											{
												dislplayCompareButton($row_prod['product_id']);
											}?>
												</li>
												<?php
												$module_name = 'mod_product_reviews';
												if(in_array($module_name,$inlineSiteComponents))
												{
													if($row_prod['product_averagerating']>=0)
													{
													?>
														<li class="shelfB_rate">
														<?php
															display_rating($row_prod['product_averagerating']);
														?>
														</li>
													<?php
													}
												}	
												?>        
												<li class="shelfAproddesli"><h6 class="shelfAproddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6></li>                     
												<?php
												$price_class_arr['ul_class'] 		= 'shelfBul';
												$price_class_arr['normal_class'] 	= 'shelfAnormalprice';
												$price_class_arr['strike_class'] 	= 'shelfAstrikeprice';
												$price_class_arr['yousave_class'] 	= 'shelfAyousaveprice';
												$price_class_arr['discount_class'] 	= 'shelfAdiscountprice';
												echo show_Price($row_prod,$price_class_arr,'shelfcenter_3');
												show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
												show_bonus_points_msg($row_prod,'bonus_point_1row'); // Show bonus points
												?>
												</ul>	
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
												</td>
											</tr>
											<tr>
											<td class="mid_shelfA_btm_lf">&nbsp;</td>
											<?php $frm_name = uniqid('shelf_'); ?>
											<td class="mid_shelfA_btm_mid">
											
									<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
										<input type="hidden" name="fpurpose" value="" />
										<input type="hidden" name="fproduct_id" value="" />
										<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
										<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
											<div class="infodiv_shlfA">
											<div class="infodivleft_shlfA"><?php show_moreinfo($row_prod,'infolink')?></div>
											<div class="infodivright_shlfA">
											<?php
											$class_arr 					= array();
											$class_arr['ADD_TO_CART']	= 'quantity_infolink';
											$class_arr['PREORDER']		= 'quantity_infolink';
											$class_arr['ENQUIRE']		= 'quantity_infolink';
											show_addtocart($row_prod,$class_arr,$frm_name)
											?></div>
											</div></form></td>
											<td class="mid_shelfA_btm_rt">&nbsp;</td>
											</tr>
										</table>
										</td>
										<?
										if($totcnt==$cur_col)
										{ 
											if ($cur_col<3)
												echo '<td>&nbsp;</td>';
											echo "</tr>";
										}
										else
										{
										if ($cur_col%3==0)
										{
										echo "</tr>";
										//##############################################################
										// Showing the more info and add to cart links after each row in 
										// case of breaking to new row while looping
										//##############################################################
										echo "<tr>";
										}
										}	
									}
									
									?>
									</table>
								<?php		
									break;
								};
							
							
						}
		}
	};	
?>