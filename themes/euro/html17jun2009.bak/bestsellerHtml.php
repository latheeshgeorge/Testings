<?php
	/*############################################################################
	# Script Name 	: bestsellerHtml.php
	# Description 		: Page which holds the display logic for middle Bestsellers
	# Coded by 		: Sny
	# Created on		: 15-May-2009
	# Modified by		: 
	# Modified On		:
	##########################################################################*/
	class bestseller_Html
	{
	
		// Defining function to show the shelf details
		function Show_Bestseller($display_id)
		{
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr;

			$Captions_arr['BEST_SELLERS'] = getCaptions('BEST_SELLERS');
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
											a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,a.product_variablesaddonprice_exists     
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
							a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,a.product_variablesaddonprice_exists     
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
							totcnt $bestsort_order 
						$Limit "; //orders a,order_details b,products p 
	}		
	
						$ret_prod = $db->query($sql_best);
						
						if ($db->num_rows($ret_prod))
						{
							// Calling the function to get the type of image to shown for current 
							$pass_type = get_default_imagetype('midshelf');
							$prod_compare_enabled = isProductCompareEnabled();
							// Number of result to display on the page, will be in the LIMIT of the sql query also
							$querystring = ""; // if any additional query string required specify it over here
								switch($Settings_arr['bestseller_prodlisting'])
								{
									case '1row': // case of one in a row for normal
									?>
										<div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?php  echo $cur_title;?></div>
										<div class="shelf_main_con" >
										<div class="shelf_top"><?php  echo $cur_title;?></div>
										<?php 
										if($Captions_arr['BEST_SELLERS']['BEST_SELLER_CAPTION']!='')
										{
										?>
											<div class="shelf_top"><?php echo $Captions_arr['BEST_SELLERS']['BEST_SELLER_CAPTION']!=''?></div>
										<?
										}
										?>
											<div class="shelf_mid">
										<?php
										$desc = trim($shelfData['shelf_description']);
										if($desc!='' and $desc!='&nbsp;')
										{
										?>
											<div class="shelf_top_desc"><?php echo $desc?></div>
										<?php		
										}
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
											
											while($row_prod = $db->fetch_array($ret_prod))
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
										<div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?php  echo $cur_title;?></div>
										<div class="shelfA_main_con" > 
										<div class="shelfA_top"><?php  echo $cur_title;?></div>
										<div class="shelfA_mid">
										<?php
										$desc = trim($shelfData['shelf_description']);
										if($desc!='' and $desc!='&nbsp;')
										{
									?>
											<div class="shelf_bottom_desc"><?php echo $desc?></div>
									<?php		
										}
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
											$cur_tot_cnt = $db->num_rows($ret_prod);
											$cur = 1;
											while($row_prod = $db->fetch_array($ret_prod))
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
							
							
						}
		}
	};	
?>