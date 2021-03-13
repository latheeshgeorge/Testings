<?php
	/*############################################################################
	# Script Name 	: shelfHtml.php
	# Description 	: Page which holds the display logic for middle shelves
	# Coded by 		: Sny
	# Created on	: 10-Aug-2009
	# Modified by	: 
	# Modified On	: 
	##########################################################################*/
	class shelf_Html
	{
		// Defining function to show the shelf details
		function Show_Shelf($cur_title,$shelf_arr)
		{
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents;
			if (count($shelf_arr))
			{
				$shelfsort_by			= $Settings_arr['product_orderfield_shelf'];
				$prodperpage			= $Settings_arr['product_maxcntperpage_shelf'];// product per page
			
				switch ($shelfsort_by)
				{
					case 'custom': // case of order by customer fiekd
					$shelfsort_by		= 'b.product_order';
					break;
					case 'product_name': // case of order by product name
					$shelfsort_by		= 'a.product_name';
					break;
					case 'price': // case of order by price
					$shelfsort_by		= 'a.product_webprice';
					break;
					case 'product_id': // case of order by price
					$prodsort_by		= 'a.product_id';
					break;	
					default: // by default order by product name
					$shelfsort_by		= 'a.product_name';
					break;
				};
				$shelfsort_order		= $Settings_arr['product_orderby_shelf'];
				$prev_shelf				= 0;
				$show_max               =0;
				//Iterating through the shelf array to fetch the product to be shown.
				foreach ($shelf_arr as $k=>$shelfData)
				{
					// Check whether shelf_activateperiodchange is set to 1
					$active 	= $shelfData['shelf_activateperiodchange'];
					if($active==1)
					{
						$proceed	= validate_component_dates($shelfData['shelf_displaystartdate'],$shelfData['shelf_displayenddate']);
					}
					else
						$proceed	= true;	
					if ($proceed)
					{
						if($_REQUEST['req']!='') // If coming to show the details in middle area other than from the home page then show the details in normal shelf style
							$shelfData['shelf_currentstyle']='nor';
						// Get the total number of product in current shelf
						$sql_totprod = "SELECT count(b.products_product_id) 
									FROM 
										products a,product_shelf_product b 
									WHERE 
										b.product_shelf_shelf_id = ".$shelfData['shelf_id']." 
										AND a.product_id = b.products_product_id 
										AND a.product_hide = 'N' ";
						$ret_totprod 	= $db->query($sql_totprod);
						list($tot_cnt) 	= $db->fetch_array($ret_totprod); 
						
						// Call the function which prepares variables to implement paging
						$ret_arr 		= array();
						$pg_variable	= 'shelf_'.$shelfData['shelf_id'].'_pg';
						if ($_REQUEST['req']!='')// LIMIT for products is applied only if not displayed in home page
						{
							$start_var 		= prepare_paging($_REQUEST[$pg_variable],$prodperpage,$tot_cnt);
							$Limit			= " LIMIT ".$start_var['startrec'].", ".$prodperpage;
						}	
						else
						{
							if($shelfData['shelf_currentstyle']=='nor' and $shelfData['shelf_displaytype']=='1row')
								$Limit = ' LIMIT 2';
							elseif($shelfData['shelf_currentstyle']=='nor' and $shelfData['shelf_displaytype']=='2row')
								$Limit = ' LIMIT 4';
							elseif($shelfData['shelf_currentstyle']=='sp1' and $shelfData['shelf_displaytype']=='1row')
								$Limit = ' LIMIT 4';
							elseif($shelfData['shelf_currentstyle']=='sp1' and $shelfData['shelf_displaytype']=='2row')
								$Limit = ' LIMIT 3';	
                        }                       
						// Get the list of products to be shown in current shelf
						$sql_prod = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
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
											products a,product_shelf_product b 
										WHERE 
											b.product_shelf_shelf_id = ".$shelfData['shelf_id']." 
											AND a.product_id = b.products_product_id 
											AND a.product_hide = 'N' 
										ORDER BY 
											$shelfsort_by $shelfsort_order 
										$Limit	";
						$ret_prod = $db->query($sql_prod);
						if ($db->num_rows($ret_prod))
						{
							$comp_active = isProductCompareEnabled();
							$pass_type = get_default_imagetype('midshelf');
							// Number of result to display on the page, will be in the LIMIT of the sql query also
							$querystring = ""; // if any additional query string required specify it over here
							
							if($shelfData['shelf_currentstyle']=='nor') // case of normal design layout
							{
								switch($shelfData['shelf_displaytype'])
								{
									case '1row': // case of one in a row for normal
										$HTML_comptitle = $HTML_maindesc = $HTML_paging = $HTML_showall = '';
										if($cur_title!='')
										{
											   $HTML_comptitle ='  <div class="normal_shlfB_header">
																   <div class="normal_shlfB_hd_inner">
																   <div class="normal_shlfB_hd"><span>'.stripslashes($cur_title).'</span></div>
																   </div> 
																   </div>';
										}
										$desc = trim($shelfData['shelf_description']);
										if($desc!='' and $desc!='&nbsp;')
										{
											 $desc = stripslashes($desc);
											 $HTML_maindesc = '<div class="normal_shlfB_desc_outr">'.$desc.'</div>';
										}
										if ($tot_cnt>0 and ($_REQUEST['req']!='') and $tot_cnt>$prodperpage)
										{
											$paging 			= paging_footer_advanced($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr);
											$HTML_paging ='<div class="subcat_nav_pdt_no_shelf"><span>'.$paging['total_cnt'].'</span></div>
															<div class="page_nav_con_shelf">
															<div class="page_nav_top_shelf"></div>
															<div class="page_nav_mid_shelf">
															<div class="page_nav_content_shelf"><ul>';//.'';
											$HTML_paging .= $paging['navigation']['start_nav'].$paging['navigation']['page_no'].$paging['navigation']['end_nav'];
											$HTML_paging .= ' 
															</ul></div>
															</div>
															<div class="page_nav_bottom_shelf"></div>
															</div>';
										}
										if($_REQUEST['req']=='' and  $tot_cnt>0) // case of show all link
										{
										   $HTML_showall = "<div class='normal_mid_showall'><a href='".url_shelf_all($shelfData['shelf_id'],$shelfData['shelf_name'],1)."' title=''>".$Captions_arr['COMMON']['SHOW_ALL']."</a></div>";
										}
									?>
											<div class="normal_shlf_mid_con">
											<div class="normal_shlf_mid_top"></div>
											<div class="normal_shlf_mid_mid">
											<?
												echo $HTML_comptitle;
												echo $HTML_paging;
												echo $HTML_maindesc;
												
												while($row_prod = $db->fetch_array($ret_prod))
												{
													$HTML_title = $HTML_image = $HTML_desc = '';
													$HTML_sale = $HTML_new = $HTML_compare = $HTML_rating = '';
													$HTML_price = $HTML_bulk= $HTML_bonus = $HTML_compare = $HTML_freedel= $HTML_bonus_bar = '';
													if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
													{
														$HTML_title = '<div class="normal_shlfB_pdt_name"><a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">'.stripslash_normal($row_prod['product_name']).'</a></div>';
													}
													if ($shelfData['shelf_showimage']==1)// Check whether description is to be displayed
													{
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
													}
													if ($shelfData['shelf_showdescription']==1)// Check whether description is to be displayed
													{
														$HTML_desc = stripslash_normal($row_prod['product_shortdesc']);
													}
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
													if($shelfData['shelf_showrating']==1)
													{
														$module_name = 'mod_product_reviews';
														if(in_array($module_name,$inlineSiteComponents))
														{
															if($row_prod['product_averagerating']>=0)
															{
																$HTML_rating = display_rating($row_prod['product_averagerating'],1);
															}
														}
													}
													else
														$HTML_rating = '&nbsp;';
													if ($shelfData['shelf_showprice']==1)
													{
														$price_class_arr['class_type']          = 'div';
														$price_class_arr['normal_class']        = 'normal_shlfB_pdt_priceA';
														$price_class_arr['strike_class']        = 'normal_shlfB_pdt_priceB';
														$price_class_arr['yousave_class']       = 'normal_shlfB_pdt_priceC';
														$price_class_arr['discount_class']      = 'normal_shlfB_pdt_priceC';
														$HTML_price = show_Price($row_prod,$price_class_arr,'shelfcenter_1');
													}
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
													
													$frm_name = uniqid('shelf_');
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
												echo $HTML_paging;
												echo $HTML_showall;
											?>
											</div>
											<div class="normal_shlf_mid_bottom"></div> 
											</div> 
									<? 
									break;
									case '2row': // case of three in a row for normal
										$HTML_comptitle = $HTML_maindesc = $HTML_paging = $HTML_showall = '';
										if($cur_title!='')
										{
											   $HTML_comptitle ='  <div class="normal_shlfA_header">
																   <div class="normal_shlfA_hd_inner">
																   <div class="normal_shlfA_hd"><span>'.stripslashes($cur_title).'</span></div>
																   </div> 
																   </div>';
										}
										$desc = trim($shelfData['shelf_description']);
										if($desc!='' and $desc!='&nbsp;')
										{
											 $desc = stripslashes($desc);
											 $HTML_maindesc = '<div class="normal_shlfA_desc_outr">'.$desc.'</div>';
										}
										if ($tot_cnt>0 and ($_REQUEST['req']!='') and $tot_cnt>$prodperpage)
										{
											$paging 		= paging_footer_advanced($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr);
											$HTML_paging 	='<div class="subcat_nav_pdt_no_shelf"><span>'.$paging['total_cnt'].'</span></div>
																<div class="page_nav_con_shelf">
																<div class="page_nav_top_shelf"></div>
																<div class="page_nav_mid_shelf">
																<div class="page_nav_content_shelf"><ul>';//.'';
											$HTML_paging 	.= $paging['navigation']['start_nav'].$paging['navigation']['page_no'].$paging['navigation']['end_nav'];
											$HTML_paging 	.= ' 
																</ul></div>
																</div>
																<div class="page_nav_bottom_shelf"></div>
																</div>';
										}
										if($_REQUEST['req']=='' and  $tot_cnt>0) // case of show all link
										{
										   $HTML_showall = "<div class='normal_midA_showall'><a href='".url_shelf_all($shelfData['shelf_id'],$shelfData['shelf_name'],1)."' title=''>".$Captions_arr['COMMON']['SHOW_ALL']."</a></div>";
										}
									
									?>
										
										<div class="normal_shlfA_mid_con">
										<div class="normal_shlfA_mid_top"></div>
										<div class="normal_shlfA_mid_mid">
										<? 
										echo $HTML_comptitle;
										echo $HTML_maindesc;
										echo $HTML_paging;
  										$max_col = 2;
										$cur_col = 0;
										$prodcur_arr = array();
										while($row_prod = $db->fetch_array($ret_prod))
										{
											$prodcur_arr[] = $row_prod;
											$HTML_title = $HTML_image = $HTML_desc = '';
											$HTML_sale = $HTML_new = $HTML_compare = $HTML_rating = '';
											$HTML_price = $HTML_bulk= $HTML_bonus = $HTML_compare = $HTML_freedel= $HTML_bonus_bar = '';
											if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
											{
												$HTML_title = '<div class="normal_shlfA_pdt_name"><a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">'.stripslash_normal($row_prod['product_name']).'</a></div>';
											}
											if ($shelfData['shelf_showimage']==1)// Check whether description is to be displayed
											{
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
											}
											if ($shelfData['shelf_showdescription']==1)// Check whether description is to be displayed
											{
												$HTML_desc = stripslash_normal($row_prod['product_shortdesc']);
											}
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
											if($shelfData['shelf_showrating']==1)
											{
												$module_name = 'mod_product_reviews';
												if(in_array($module_name,$inlineSiteComponents))
												{
													if($row_prod['product_averagerating']>=0)
													{
														$HTML_rating = display_rating($row_prod['product_averagerating'],1);
													}
												}
											}
											else
												$HTML_rating = '&nbsp;';
											if ($shelfData['shelf_showprice']==1)
											{
												$price_class_arr['class_type']          = 'div';
												$price_class_arr['normal_class']        = 'normal_shlfB_pdt_priceA';
												$price_class_arr['strike_class']        = 'normal_shlfB_pdt_priceB';
												$price_class_arr['yousave_class']       = 'normal_shlfB_pdt_priceC';
												$price_class_arr['discount_class']      = 'normal_shlfB_pdt_priceC';
												$HTML_price = show_Price($row_prod,$price_class_arr,'shelfcenter_3');
											}
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
											$frm_name = uniqid('shelf_');
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
												show_addtocart($row_prod,$class_arr,$frm_name,false,'','','normal_shlfA_pdt_buy_btn');
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
										echo $HTML_paging;
										echo $HTML_showall;
										?>
										<div class="normal_shlfA_mid_bottom"></div> 
										</div>   
										</div>
								<?php		
									break;
								};
							}
							elseif($shelfData['shelf_currentstyle']=='sp1') // case of special layout 1
							{
								switch($shelfData['shelf_displaytype'])
								{
									case '1row':
										$HTML_comptitle = $HTML_maindesc = $HTML_paging = $HTML_showall = '';
										if($cur_title!='')
										{
											   $HTML_comptitle ='  <div class="spcl_shlf_mid_hdr">'.stripslashes($cur_title).'</div>';
										}
										$desc = trim($shelfData['shelf_description']);
										if($desc!='' and $desc!='&nbsp;')
										{
											 $desc = stripslashes($desc);
											 $HTML_maindesc = $desc;
										}
										if($_REQUEST['req']=='' and  $tot_cnt>0) // case of show all link
										{
										   $HTML_showall = "<div class='special1row_midA_showall'><a href='".url_shelf_all($shelfData['shelf_id'],$shelfData['shelf_name'],1)."' title=''>".$Captions_arr['COMMON']['SHOW_ALL']."</a></div>";
										}
									?>
										<div class="spcl_shlf_mid_con">
										<div class="spcl_shlf_mid_top"></div>
										<div class="spcl_shlf_mid_mid">
										<div class="spcl_shlf_mid_cont">
										<?=$HTML_comptitle?>
										<?=$HTML_maindesc?></div> 
										<div class="spcl_shlf_mid_pdt_otr">
										<?
										$pass_type = 'image_gallerythumbpath';
										while($row_prod = $db->fetch_array($ret_prod))
										{
											$HTML_title = $HTML_image = $HTML_desc = '';
											$HTML_sale = $HTML_new = $HTML_compare = $HTML_rating = '';
											$HTML_price = $HTML_bulk= $HTML_bonus = $HTML_compare = $HTML_freedel= '';
											if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
											{
												$HTML_title = '<div class="spcl_shlf_mid_pdt_name"><a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">'.stripslash_normal($row_prod['product_name']).'</a></div>';
											}
											if ($shelfData['shelf_showimage']==1)// Check whether description is to be displayed
											{
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
											}
											if ($shelfData['shelf_showdescription']==1)// Check whether description is to be displayed
											{
												$HTML_desc = stripslash_normal($row_prod['product_shortdesc']);
											}
											
											if ($shelfData['shelf_showprice']==1)
											{
												$price_class_arr['class_type']          = 'div';
												$price_class_arr['normal_class']        = 'spcl_shlf_mid_pdt_priceB';
												$price_class_arr['strike_class']        = 'spcl_shlf_mid_pdt_priceA';
												$price_class_arr['yousave_class']       = '';
												$price_class_arr['discount_class']      = '';
												$price_arr 	= show_Price($row_prod,$price_class_arr,'shelfcenter_1',false,3);
												$HTML_price = '<div class="spcl_shlf_mid_pdt_priceA">'.$price_arr['base_price'].'</div>';
												if($price_arr['discounted_price']!='')
												{
													$HTML_price = '<div class="spcl_shlf_mid_pdt_priceA">'.$price_arr['base_price'].'</div>';
													$HTML_price .= '<div class="spcl_shlf_mid_pdt_priceB">'.$price_arr['discounted_price'].'</div>';
												}
												else
													$HTML_price = '<div class="spcl_shlf_mid_pdt_priceB">'.$price_arr['base_price'].'</div>';	
													 

											}
										?>
											<div class="spcl_shlf_mid_pdt">
											<div class="spcl_shlf_mid_pdt_img"><?=$HTML_image?></div>
											<div class="spcl_shlf_mid_pdt_name_otr">
											<div class="spcl_shlf_mid_pdt_name"><?=$HTML_title?></div>
											<div class="spcl_shlf_mid_pdt_des"><?=$HTML_desc?></div>
											</div>
											<div class="spcl_shlf_mid_pdt_price">
											<?=$HTML_price?>
											</div>
											</div>
										<?php
										}
										echo $HTML_showall;
										?>	
										</div>
										</div> 
										<div class="spcl_shlf_mid_bottom"></div> 
										</div>
									<?php	
									break;
									case '2row': // case of two in a row 
										$HTML_comptitle = $HTML_maindesc = $HTML_paging = $HTML_showall = '';
										if($cur_title!='')
										{
											$HTML_comptitle ='<div class="spcl_shlfC_hd"><span>'.stripslashes($cur_title).'</span></div>';
										}
										$desc = trim($shelfData['shelf_description']);
										if($desc!='' and $desc != '&nbsp;')
										{
											$desc = stripslashes($desc);
											$HTML_maindesc = $desc;
										}
										if($_REQUEST['req']=='' and  $tot_cnt>0) // case of show all link
										{
											$HTML_showall = "<div class='special1row_midA_showall'><a href='".url_shelf_all($shelfData['shelf_id'],$shelfData['shelf_name'],1)."' title=''>".$Captions_arr['COMMON']['SHOW_ALL']."</a></div>";
										}
									?>
									<div class="spcl_shlfC_mid_con">
									<div class="spcl_shlfC_mid_top">
									<div class="spcl_shlfC_hd_inner">
									<?=$HTML_comptitle?>
									</div>
									</div>
									</div>
									<div class="spcl_shlfC_mid_bottom">
									<?php
									$pass_type = 'image_gallerythumbpath';
									while($row_prod = $db->fetch_array($ret_prod))
									{
										$HTML_title = $HTML_image = $HTML_desc = '';
										$HTML_sale = $HTML_new = $HTML_compare = $HTML_rating = '';
										$HTML_price = $HTML_bulk= $HTML_bonus = $HTML_compare = $HTML_freedel= '';
										if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
										{
											$HTML_title = '<div class="spcl_shlfC_mid_pdt_name"><a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">'.stripslash_normal($row_prod['product_name']).'</a></div>';
										}
										if ($shelfData['shelf_showimage']==1)// Check whether description is to be displayed
										{
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
										}
										if ($shelfData['shelf_showprice']==1)
										{
											/*$price_class_arr['class_type']          = 'div';
											$price_class_arr['normal_class']        = 'spcl_shlfC_mid_pdt_priceB';
											$price_class_arr['strike_class']        = 'spcl_shlfC_mid_pdt_priceA';
											$price_class_arr['yousave_class']       = 'spcl_shlfC_mid_pdt_priceC';
											$price_class_arr['discount_class']      = 'spcl_shlfC_mid_pdt_priceC';
											$HTML_price 							= show_Price($row_prod,$price_class_arr,'shelfcenter_3');*/
											$price_arr =  show_Price($row_prod,array(),'shelfcenter_3',false,3);
											if($price_arr['base_price'])
												$HTML_price = '<div class="spcl_shlfC_mid_pdt_priceB">'.$price_arr['base_price'].'</div>';
											if($price_arr['discounted_price'])
												$HTML_price .= '<div class="spcl_shlfC_mid_pdt_priceC">'.$price_arr['discounted_price'].'</div>';
											

										}
									?>
										<div class="spcl_shlfC_mid_pdt_otr">
										<div class="spcl_shlfC_mid_pdt_img"><?=$HTML_image?></div>
										<div class="spcl_shlfC_mid_pdt_name"><?=$HTML_title?></div>
										<div class="spcl_shlfC_mid_pdt_price"><?=$HTML_price?></div>
										<div class="spcl_shlfC_mid_pdt_buy"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><img src="<? url_site_image('shlf-buy.gif')?>" /></a></div>
										</div>
									<?php
									}
									?>
									<div class="spcl_shlfC_mid_cont"><?=$HTML_maindesc?></div>
									<?=$HTML_showall?> 
									</div>
									<?
									break;
								};
							}
						}
					}
					else
					{
						removefrom_Display_Settings($shelfData['shelf_id'],'mod_shelf');
					}
				}
			}	
		}
	};	
?>