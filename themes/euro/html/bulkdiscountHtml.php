<?php
	/*############################################################################
	# Script Name 	: bulkdiscountHtml.php
	# Description 		: Page which holds the display logic for middle Bestsellers
	# Coded by 		: Sny
	# Created on		: 15-May-2009
	# Modified by		: 
	# Modified On		:
	##########################################################################*/
	class bulkdiscount_Html
	{
		// Defining function to show the shelf details
		function Show_Bulkdiscount()
		{
		global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents;

		$Captions_arr['BULKDISC_PROD'] = getCaptions('BULKDISC_PROD');
			// query for display 	title
			$prodsort_by			= ($_REQUEST['bulkdet_sortby'])?$_REQUEST['bulkdet_sortby']:'product_name';
		$prodperpage			= ($_REQUEST['bulkdet_prodperpage'])?$_REQUEST['bulkdet_prodperpage']:$Settings_arr['product_maxcntperpage_bestseller'];// product per page
		$prodsort_order			= ($_REQUEST['bulkdet_sortorder'])?$_REQUEST['bulkdet_sortorder']:$Settings_arr['product_orderby_bestseller'];
		$sql_tot	=	"SELECT count(a.product_id)  
				FROM 
					products a
				WHERE 
					a.sites_site_id = $ecom_siteid 
					AND a.product_hide ='N' 
					AND a.product_bulkdiscount_allowed='Y'";
		$ret_tot 	= $db->query($sql_tot);
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
							<div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?php  echo $Captions_arr['BULKDISC_PROD']['BULKDISC_PROD'];?></div>
							<div class="shelfA_main_con" > 
							<div class="shelfA_top"><?php  echo $Captions_arr['BULKDISC_PROD']['BULKDISC_PROD'];?></div>
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
								<div class="pagingcontainertd" align="center">
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
													  if($prod_compare_enabled) 
													  {
													  ?>
														<div class="shlfA_pdt_compare" >
													<?php
															dislplayCompareButton($row_prod['product_id']);
													?>		
														</div>
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
			}
		}
	};	
?>