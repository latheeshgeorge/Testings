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
							$Limit = '';
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
									?>
										<div class="mid_shlf_con" >
										<div class="mid_shlf_hdr" >
										<div class="mid_shlf_hdr_top"></div>
										<?php 
										if($cur_title)
										{
										?>
										<div class="mid_shlf_hdr_middle"><?php echo $cur_title?></div>
										<?php
										}
										?>
										<div class="mid_shlf_hdr_bottom"></div>
										</div>
										
										<?php
										$desc = trim($shelfData['shelf_description']);
										if($desc!='' and $desc!='&nbsp;')
										{
										?>
											<div class="mid_shlf_desc"><?php echo $desc?></div>
										<?php		
										}
										if ($tot_cnt>0 and ($_REQUEST['req']!='') and $tot_cnt>$prodperpage)
										{
										?>
											<div class="list_page_con">
											<div class="list_page_top"></div>
											<div class="list_page_middle">
											<div class="pagingcontainertd" >
											<div class="pro_nav_pages" align="right"><?php echo paging_show_totalcount($tot_cnt,'Products',$start_var['pg'],$start_var['pages'])?></div>
															
												<?php 
													$path = '';
													$query_string .= "";
													paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr,0); 	
												?>
											</div>
											</div>
											<div class="list_page_bottom"></div>
											</div>
										<?php
										}
									while($row_prod = $db->fetch_array($ret_prod))
									{
									?>
									
										<div class="mid_shlf_top"></div>
										<div class="mid_shlf_middle">
									<?php		
										if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
										{
										?>	
										<div class="mid_shlf_pdt_name"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></div>
										<?php
										}
										?>
										<div class="mid_shlf_mid">
										<div class="mid_shlf_pdt_image">
										<?php
										if ($shelfData['shelf_showimage']==1)// Check whether description is to be displayed
										{
										?>	
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
									<? 
										}
										if($comp_active)
										{
										?>
											<div class="mid_shlf_pdt_compare" >
											<?php	dislplayCompareButton($row_prod['product_id']);?>
											</div>
										<?php	
										}
										?>
										</div>
										</div>
										<div class="mid_shlf_pdt_des">
										<?php
										if ($shelfData['shelf_showdescription']==1)// Check whether description is to be displayed
										{
											echo stripslashes($row_prod['product_shortdesc']);
										}
										$module_name = 'mod_product_reviews';
										if(in_array($module_name,$inlineSiteComponents))
										{
											if($row_prod['product_averaterating']>=0)
											{
										?>
											<div class="mid_shlf_pdt_rate">
											<?php
											for ($i=0;$i<$row_prod['product_averagerating'];$i++)
											{
												echo '<img src="'.url_site_image('star-red.gif',1).'" border="0" alt="revscoreimg" />&nbsp;'; 
											}
											?>
											</div>
										<?php
											}
										}	
										if($row_prod['product_bulkdiscount_allowed']=='Y')
										{
										?>
											<div class="mid_shlf_pdt_bulk"><img src="<?php url_site_image('bulk-discount.gif')?>" alt="Bulk Discount"/></div>
										<?php
										}
										if($row_prod['product_saleicon_show']==1)
										{
											$desc = stripslashes(trim($row_prod['product_saleicon_text']));
											if($desc!='')
											{
										?>	
												<div class="mid_shlf_pdt_sale"><?php echo $desc?></div>
										<?php
											}
										}
										if($row_prod['product_newicon_show']==1)
										{
											$desc = stripslashes(trim($row_prod['product_newicon_text']));
											if($desc!='')
											{
										?>
												<div class="mid_shlf_pdt_newsale"><?php echo $desc?></div>
										<?php
											}
										}
										?>
										</div>
										<div class="mid_shlf_pdt_price">
										<?php 
										if($row_prod['product_freedelivery']==1)
										{	
										?>
										<div class="mid_shlf_free"></div>
										<?php
										}
										if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
										{ 
											$price_class_arr['class_type'] 		= 'div';
											$price_class_arr['normal_class'] 	= 'shlf_normalprice';
											$price_class_arr['strike_class'] 	= 'shlf_strikeprice';
											$price_class_arr['yousave_class'] 	= 'shlf_yousaveprice';
											$price_class_arr['discount_class'] 	= 'shlf_discountprice';
											echo show_Price($row_prod,$price_class_arr,'shelfcenter_1');
										}
										$frm_name = uniqid('shelf_');
										?>	
										<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
										<input type="hidden" name="fpurpose" value="" />
										<input type="hidden" name="fproduct_id" value="" />
										<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
										<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
											<div class="mid_shlf_buy">
											<div class="mid_shlf_info_btn"><?php show_moreinfo($row_prod,'mid_shlf_info_link')?></div>
											<div class="mid_shlf_buy_btn">
											<?php
												$class_arr 					= array();
												$class_arr['ADD_TO_CART']	= 'mid_shlf_buy_link';
												$class_arr['PREORDER']		= 'mid_shlf_buy_link';
												$class_arr['ENQUIRE']		= 'mid_shlf_buy_link';
												show_addtocart($row_prod,$class_arr,$frm_name)
											?>
											</div>
											</div>
										</form>       
										</div>
										</div>
										<div class="mid_shlf_bottom"></div>
										
									<? }?>
										</div>
									<?php
									break;
									case '2row': // case of three in a row for normal
									?>
										<div class="mid_shlf2_con" >
										<div class="mid_shlf2_hdr" >
										<div class="mid_shlf2_hdr_top"></div>
										<?php 
										if($cur_title)
										{
										?>
										<div class="mid_shlf2_hdr_middle"><?php echo $cur_title?></div>
										<?php
										}
										?>
									   <div class="mid_shlf2_hdr_bottom"></div>
										</div>
										<?php
										$desc = trim($shelfData['shelf_description']);
										if($desc!='' and $desc!='&nbsp;')
										{
										?>
										<div class="mid_shlf2_desc"><?php echo $desc?></div>
										<?php
										}
										if ($tot_cnt>0 and ($_REQUEST['req']!='') and $tot_cnt>$prodperpage)
										{
										?>
											<div class="list_page_con">
											<div class="list_page_top"></div>
											<div class="list_page_middle">
											<div class="pagingcontainertd" >
											<div class="pro_nav_pages" align="right"><?php echo paging_show_totalcount($tot_cnt,'Products',$start_var['pg'],$start_var['pages'])?></div>
															
												<?php 
													$path = '';
													$query_string .= "";
													paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr,0); 	
												?>
											</div>
											</div>
											<div class="list_page_bottom"></div>
											</div>
										<?php
										}
											$max_col = 2;
											$cur_col = 0;
											$prodcur_arr = array();
											while($row_prod = $db->fetch_array($ret_prod))
											{
												$prodcur_arr[] = $row_prod;
												//##############################################################
												// Showing the title, description and image part for the product
												//##############################################################
												if($cur_col == 0)
												{
												  echo '<div class="mid_shlf2_con_main">';
												}
												$cur_col ++;

										?>
											<div class="mid_shlf2_con_pdt">
											<div class="mid_shlf2_top"></div>
											<div class="mid_shlf2_middle">
											<?php
											if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
											{
										?>
											<div class="mid_shlf2_pdt_name"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></div>
											<?php
											}
											if ($shelfData['shelf_showimage']==1)// Check whether description is to be displayed
											{
											?>
												<div class="mid_shlf2_pdt_image">
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
											<?php
											}
											?>
											<div class="mid_shlf2_free_con">
											<?php
											if($row_prod['product_freedelivery']==1)
											{	
											?>
											<div class="mid_shlf2_free"></div>
											<?php
											}
											if($row_prod['product_bulkdiscount_allowed']=='Y')
											{
											?>
												<div class="mid_shlf2_pdt_bulk"><img src="<?php url_site_image('bulk-discount.gif')?>" border="0" alt="Bulk Discount" /></div>
											<?php
											}
											?>
											</div>
											<?php
											if($comp_active)
											{
											?>
												<div class="mid_shlf2_pdt_compare" >
												<?php	dislplayCompareButton($row_prod['product_id']);?>
												</div>
											<?php	
											}
											if ($shelfData['shelf_showdescription']==1)// Check whether description is to be displayed
											{
											?>
											<div class="mid_shlf2_pdt_des">
											<?php echo stripslashes($row_prod['product_shortdesc'])?>
											</div>
											<?php
											}
											if($row_prod['product_saleicon_show']==1)
											{
												$desc = stripslashes(trim($row_prod['product_saleicon_text']));
												if($desc!='')
												{
											?>	
													<div class="mid_shlf2_pdt_sale"><?php echo $desc?></div>
											<?php
												}
											}	
											if($row_prod['product_newicon_show']==1)
											{
												$desc = stripslashes(trim($row_prod['product_newicon_text']));
												if($desc!='')
												{
											?>
													<div class="mid_shlf2_pdt_newsale"><?php echo $desc?></div>
											<?php
												}
											}	
											?>
											
											<div class="mid_shlf2_buy">
											<?php
												$frm_name = uniqid('shelf_');
											?>	
											<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
											<input type="hidden" name="fpurpose" value="" />
											<input type="hidden" name="fproduct_id" value="" />
											<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
											<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
											<div class="mid_shlf2_info_btn"><?php show_moreinfo($row_prod,'mid_shlf2_info_link')?></div>
											<div class="mid_shlf2_buy_btn">
											<?php
												$class_arr 					= array();
												$class_arr['ADD_TO_CART']	= 'mid_shlf2_buy_link';
												$class_arr['PREORDER']		= 'mid_shlf2_buy_link';
												$class_arr['ENQUIRE']		= 'mid_shlf2_buy_link';
												show_addtocart($row_prod,$class_arr,$frm_name)
											?>
											</div>
											</form>
											</div>
											<?php
											if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
											{
											?>
												<div class="mid_shlf2_pdt_price">
												<?php
												$price_class_arr['class_type'] 		= 'div';
												$price_class_arr['normal_class'] 	= 'shlf2_normalprice';
												$price_class_arr['strike_class'] 	= 'shlf2_strikeprice';
												$price_class_arr['yousave_class'] 	= 'shlf2_yousaveprice';
												$price_class_arr['discount_class'] 	= 'shlf2_discountprice';
												echo show_Price($row_prod,$price_class_arr,'shelfcenter_3');	
												?>
												</div>
											<?php
											}
											?>	
											</div>
											<div class="mid_shlf2_bottom"></div>
											</div>
											<?php
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
								<?php		
									break;
								};
							}
							elseif($shelfData['shelf_currentstyle']=='special1') // case of special layout 1
							{
								switch($shelfData['shelf_displaytype'])
								{
									case '1row':
									?>
									<div class="mid_shlfA_con" >
									<div class="mid_shlfA_top"></div>
									<div class="mid_shlfA_middle">
									<?php
										if($cur_title)
										{
									?>
                                         <div class="spcl_shlD_header"> <? echo $cur_title?></div>
									<? 
										}
											$desc = trim($shelfData['shelf_description']);
											if($desc!='' and $desc!='&nbsp;')
											{
										?>
												<div class="christmas_proddesB"><?php echo $desc?></div>
										<?php		
											}
											if ($tot_cnt>0 and ($_REQUEST['req']!=''))
											{
										?>
												<div class="pagingcontainertd">
												<?php 
													$path = '';
													$query_string .= "";
													paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr); 	
												?>	
												</div>
										<?php
											} 
												$cnt=0;
												$max_cnt = 2;
												while($row_prod = $db->fetch_array($ret_prod))
												{
													$frm_name = uniqid('shelf_');
													if($cnt==0)
														echo '<div class="mid_shlfA_pdt_con" >';
													$cnt++;
												?>
													<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
													<input type="hidden" name="fpurpose" value="" />
													<input type="hidden" name="fproduct_id" value="" />
													<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
													<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
													
													
													<div class="mid_shlfA_pdt" >
													<div class="mid_shlfA_pdt_top"></div>
													<div class="mid_shlfA_pdt_middle">
													<?php
													if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
													{
													?>
														<div class="mid_shlfA_pdt_name"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></div>
													<?php
													}
													?>
													
													<div class="mid_shlfA_pdt_buy">
													<?php
														$class_arr 					= array();
														$class_arr['ADD_TO_CART']	= 'shlfA_buy';
														$class_arr['PREORDER']		= 'shlfA_buy';
														$class_arr['ENQUIRE']		= 'shlfA_buy';
														show_addtocart($row_prod,$class_arr,$frm_name,false,'','','',1);
													?>
													</div>
													<?php
													if ($shelfData['shelf_showimage']==1)// Check whether description is to be displayed
													{
													  $pass_type = 'image_iconpath';
													 	//$pass_type = 'image_gallerythumbpath';
													?>
														<div class="mid_shlfA_pdt_image">
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
													<?php
													}
													?>
													<?php
													if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
													{
															$price_arr =  show_Price($row_prod,array(),'shelfcenter_1',false,3);
															if($price_arr['discounted_price'])
															{
													?>
																  <span class="mid_shlfA_pdt_price" ><? echo $price_arr['discounted_price'];?></span> 
													<?  	}elseif($price_arr['base_price'])
															{
													?>
															   <span class="mid_shlfA_pdt_price"><? echo $price_arr['base_price']?></span> 
													<?  	}
															
													}
													?>	
													</div>
													<div class="mid_shlfA_pdt_bottom"></div>
													</div>
													</form>
													<?php 
														if($cnt>=$max_cnt)
														{
															$cnt = 0;
															echo '</div>';
														}	
													}
													if($cnt<$max_cnt && $cnt>0)
															echo '</div>';
												?>
											</div>
											<div class="mid_shlfA_bottom"></div>
											</div>
									<?php	
									break;
									case '2row': // case of two in a row 
									?>
										<div class="mid_shlfA2_con">
										<div class="mid_shlfA2_top"></div>
										<div class="mid_shlfA2_middle">
										
										<div class="mid_shlfA2_off_banner"></div>
										<div class="mid_shlfA2_pdts">
										<?php 
										if($cur_title)
										{
										?>
											<div class="mid_shlfA2_name"><?php echo $cur_title?></div>
										<?php
										}
										$desc = trim($shelfData['shelf_description']);
										if($desc!='' and $desc!='&nbsp;')
										{
									?>
											<div class="shelfAproddes"><?php echo $desc?></div>
									<?php		
										}
										if ($tot_cnt>0 and ($_REQUEST['req']!=''))
										{
										?>
											<div class="pagingcontainertd">
												<?php 
													$path = '';
														$query_string .= "";
														paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr); 	
												?>	
											</div>
										<?php
										}
										?>	
										<?php
											$max_col = 4;
											$cur_col = 0;
											$prodcur_arr = array();
											while($row_prod = $db->fetch_array($ret_prod))
											{
												if($cur_col == 0)
												{
												  echo '<div class="mid_shlfA2_pdt_con">';
												}
												$cur_col ++;

										?>
												<div class="mid_shlfA2_pdt_main">
												<div class="mid_shlfA2_pdt_imge">
												<?php
												if ($shelfData['shelf_showimage']==1)// Check whether description is to be displayed
												{
										?>		
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
										<?php
												}
												?>			
												</div>
										<?php
												$frm_name = uniqid('shelf_');
										?>
												<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
												<input type="hidden" name="fpurpose" value="" />
												<input type="hidden" name="fproduct_id" value="" />
												<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
												<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
										
												<div class="mid_shlfA2_pdt_buy">
												<span class="shlfA2_link">
										<?php
												$class_arr 					= array();
												$class_arr['ADD_TO_CART']	= 'shlfA2_link';
												$class_arr['PREORDER']		= 'shlfA2_link';
												$class_arr['ENQUIRE']		= 'shlfA2_link';
												show_addtocart($row_prod,$class_arr,$frm_name,false,'','','',1);
										?>
												</span>	
												</div>
												</form>	
											<?php
												if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
												{
													$price_arr =  show_Price($row_prod,array(),'shelfcenter_1',false,3);
													if($price_arr['discounted_price'])
													{
												?>
														<div class="mid_shlfA2_pdt_price"><? echo $price_arr['discounted_price'];?></div> 
												<?  
													}
													elseif($price_arr['base_price'])
													{
												?>
														<div class="mid_shlfA2_pdt_price"><? echo $price_arr['base_price']?></div>
												<?  
													}
												}
											?>	
											</div>
											<?php
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
											</div>
											<div class="mid_shlfA2_bottom"></div>
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