<?php
	/*############################################################################
	# Script Name 	: shelfHtml.php
	# Description 	: Page which holds the display logic for middle shelves
	# Coded by 		: Sny
	# Created on	: 29-Dec-2007
	# Modified by	: Sny
	# Modified On	: 01-Feb-2008
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
				//Iterating through the shelf array to fetch the product to be shown.
				foreach ($shelf_arr as $k=>$shelfData)
				{
					// Check whether shelf_activateperiodchange is set to 1
					$active 		= $shelfData['shelf_activateperiodchange'];
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
							$shelfData['shelf_currentstyle'] 	= 'nor';// If coming to show the details in middle area other than from the home page then show the details in normal shelf style
							$start_var 							= prepare_paging($_REQUEST[$pg_variable],$prodperpage,$tot_cnt);
							$Limit								= " LIMIT ".$start_var['startrec'].", ".$prodperpage;
						}	
						else
							$Limit 								= '';
						
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
							$prod_compare_enabled 	= isProductCompareEnabled();
							$pass_type 				= get_default_imagetype('midshelf');
							// Number of result to display on the page, will be in the LIMIT of the sql query also
							$querystring = ""; // if any additional query string required specify it over here
							if($_REQUEST['req']!='') // If coming to show the details in middle area other than from the home page then show the details in normal shelf style
								$shelfData['shelf_currentstyle']='nor';
							if($shelfData['shelf_currentstyle']=='nor') // case of normal design layout
							{
								switch($shelfData['shelf_displaytype'])
								{
									case 'dropdown':
									case 'list':
									case '1row': // case of one in a row for normal
									?>
										<div class="shelfBtable">
										<?
										if ($cur_title!='')
										{
										?>
											<div class="shelfBheader"><?php echo stripslashes($cur_title)?></div> 
										<?php
										}
										$desc = trim($shelfData['shelf_description']);
										if($desc!='' and $desc!='&nbsp;')
										{
										?>
											<div class="mid_shlf_desc"><?php echo stripslashes($desc)?></div>
										<?php		
										}
										if ($tot_cnt>0 and ($_REQUEST['req']!='') and $tot_cnt>$prodperpage)
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
										while($row_prod = $db->fetch_array($ret_prod))
										{
										?>
											<div class="shelfBtabletd">
											<div class="shelfBtabletdinner">
											<?php
											if($shelfData['shelf_showtitle']==1)
											{
											?>
											<div class="shelfBprodname"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="shelfBprodnamelink"><?php echo stripslashes($row_prod['product_name'])?></a></div>
											<?php
											}
											?>
											<div class="shelfBleft">
											<?php
											if($shelfData['shelf_showimage']==1)
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
											<div class="compare_li">
											<?php
											if($prod_compare_enabled)
											{
												dislplayCompareButton($row_prod['product_id']);
											}
											?>	
											</div> 
											
											</div>
											<div class="shelfBmid">	
											<?php
											if ($shelfData['shelf_showdescription']==1)
											{
											?>
												<h6 class="shelfBproddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6>
											<?php
											}
											?>
											<?php
												if ($shelfData['shelf_showprice']==1)
												{
													$price_class_arr['ul_class'] 		= 'shelfBpriceul';
													$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
													$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
													$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
													$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
													echo show_Price($row_prod,$price_class_arr,'cat_detail_1');
												}
												
											?>
											<?php
												if($row_prod['product_saleicon_show']==1)
												{
													$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
													if($desc!='')
													{
											?>
														<div class="shelfB_sale"><?php echo $desc?></div>
											<?php
													}
												}	
												if($row_prod['product_newicon_show']==1)
												{
													$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
													if($desc!='')
													{
											?>
														<div class="shelfB_newsale"><?php echo $desc?></div>		
											<?php
													}
												}	
										
											if($shelfData['shelf_showrating']==1)
											{
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
											}	
											if($shelfData['shelf_showbonuspoints']==1)
											{
												show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points
											}		
											?>			  
											</div>
											<div class="shelfBright"> 
											<?php
											if($row_prod['product_freedelivery']==1)
											{	
											?>
												<div class="shelfB_free"></div>
											<?php
											}
											if($row_prod['product_bulkdiscount_allowed']=='Y')
											{
											?>
												<div class="shelfB_bulk"></div>
											<?php
											}	
											$frm_name = uniqid('shelf_');
											?>
											<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
											<input type="hidden" name="fpurpose" value="" />
											<input type="hidden" name="fproduct_id" value="" />
											<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
											<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
											<div class="infodivB">
											<div class="infodivBleft">
											<?php show_moreinfo($row_prod,'infolink')?></div>
											<div class="infodivBright">
											<?php
												$class_arr 					= array();
												$class_arr['ADD_TO_CART']	= 'infolinkB';
												$class_arr['PREORDER']		= 'infolinkB';
												$class_arr['ENQUIRE']		= 'infolinkB';
												show_addtocart($row_prod,$class_arr,$frm_name)
											?>
											</div>		
											</div>
											</form>
											</div>
											</div>
											</div>	
		
										<?php
											}
									?>	
									</div>
										
									<?php
									break;
									case '3row': // case of three in a row for normal
									?>
									<div class="shelfAtable" >
									<?php 
									if($cur_title)
									{
									?>
									<div class="shelfAheader"><?php echo $cur_title?></div>
									<?
									}
									$desc = trim($shelfData['shelf_description']);
									if($desc!='' and $desc!='&nbsp;')
									{
									?>
									<div class="mid_shlf_desc"><?php echo stripslashes($desc)?></div>
									<?php		
 									}
									if ($tot_cnt>0 and ($_REQUEST['req']!='') and $tot_cnt>$prodperpage)
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
									while($row_prod = $db->fetch_array($ret_prod))
									{
										if($cur_col == 0)
										{ 
										  echo '<div class="mid_shlf2_con_main">';
										}
										$cur_col ++;
									?>		
																
									<div class="shelfAtabletd">
									<div class="shelfAtabletdinner">
									<ul class="shelfAul">
									<?php
									if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
									{
									?>
									<li><h2 class="shelfAprodname"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h2></li>
									<? }?>
									<li class="compare_li"><?php if($prod_compare_enabled)  {
									dislplayCompareButton($row_prod['product_id']);
									}?></li>														
									
									<? if ($shelfData['shelf_showimage']==1)// Check whether description is to be displayed
									{
									?>	
									<li class="shelfimg">
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
									<?
									if($row_prod['product_freedelivery']==1)
									{	
									?>
									<div class="shelfA_free"></div>
									<?
									}
									if($row_prod['product_bulkdiscount_allowed']=='Y')
									{
									?>
									<div class="shelfA_bulk"></div>
									<?
									}
									?>
									</li>
									<?
									}
									
									if($shelfData['shelf_showrating']==1)
									{
									$module_name = 'mod_product_reviews';
									if(in_array($module_name,$inlineSiteComponents))
									{
									if($row_prod['product_averagerating']>=0)
									{
									?>
									<li><div class="shelfB_rate">
									<?php
									display_rating($row_prod['product_averagerating']);
									?>
									</div></li>
									<?php
									}
									}	
									}	
									?>
									</ul>
									<?
									if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
									{
									?>
									<?php
									$price_class_arr['ul_class'] 		= 'shelfApriceul';
									$price_class_arr['normal_class'] 	= 'shelfAnormalprice';
									$price_class_arr['strike_class'] 	= 'shelfAstrikeprice';
									$price_class_arr['yousave_class'] 	= 'shelfAyousaveprice';
									$price_class_arr['discount_class'] 	= 'shelfAdiscountprice';
									echo show_Price($row_prod,$price_class_arr,'shelfcenter_3');
									show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
									?>	
									
									<?
									}
									
									if ($shelfData['shelf_showdescription']==1)// Check whether description is to be displayed
									{
									?>									
									<h6 class="shelfAproddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6>
									<? }
									if($row_prod['product_saleicon_show']==1)
									{
									$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
									if($desc!='')
									{
									?>	
									<div class="shelfA_sale"><?php echo $desc?></div>
									<?php
									}
									}
									if($row_prod['product_newicon_show']==1)
									{
									$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
									if($desc!='')
									{
									?>
									<div class="shelfA_newsale"><?php echo $desc?></div>
									<?php
									}
									}
									$frm_name = uniqid('shelf_');
									if($shelfData['shelf_showbonuspoints']==1)
									{
										show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points
									}	
									?>
									
									<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
									<input type="hidden" name="fpurpose" value="" />
									<input type="hidden" name="fproduct_id" value="" />
									<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
									<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
									<div class="infodiv">
									<div class="infodivleft">
									<?php show_moreinfo($row_prod,'infolink')?></div>
									<div class="infodivright">
									<?php
									$class_arr 					= array();
									$class_arr['ADD_TO_CART']	= 'infolink';
									$class_arr['PREORDER']		= 'infolink';
									$class_arr['ENQUIRE']		= 'infolink';
									show_addtocart($row_prod,$class_arr,$frm_name)
									?> 
									</div>		
									</div>
									</form>
									</div>
									</div>
									<?
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
							elseif($shelfData['shelf_currentstyle']=='christ') // case of special layout 1
							{
								$pass_type = 'image_thumbcategorypath';
								switch($shelfData['shelf_displaytype'])
								{
									case '1row':
									case 'dropdown':
									case 'list': // case of one in a row for christmas  christmas_rowtableB class="" 
									?>
										<div class="shelfDdiv">
										<?php
										if($cur_title)
										{
										?>
											<div class="shelfDheader"><?php echo stripslash_normal($cur_title)?></div>
										<?php
										}
										$desc = trim($shelfData['shelf_description']);
										if($desc!='' and $desc!='&nbsp;')
										{
										?>
											<div class="shelfDdescription"><?php echo stripslash_normal($desc)?></div>
										<?php
										}
										$cur_cnt = 0;
										$max_cnt =2;
										while($row_prod = $db->fetch_array($ret_prod))
										{
											$frm_name = uniqid('shelf_');
											if($cur_cnt==0)
											{
												echo '<div class="shelfDdiv_pdt">';
												echo '<div class="shelfDpdt_inner">';
												$class 		= 'shelfDleft';
												$img_cls	= 'shelfDimg';
											}	
											else
											{
												$class 		= 'shelfDright';
												$img_cls	= 'shelfDpdt_inner_img';
											}
											$cur_cnt++;
										?>	
											<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
											<input type="hidden" name="fpurpose" value="" />
											<input type="hidden" name="fproduct_id" value="" />
											<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
											<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
											<div class="<?php echo $class ?>">
											<?php
											$cur_disc = '';
											$price_arr = show_Price($row_prod,$price_class_arr,'prod_detail',false,3);	
											if($price_arr['disc_percent']!='')
												$cur_disc = $price_arr['disc_percent'];
											elseif($price_arr['yousave_price']!='')
												$cur_disc = $price_arr['yousave_price'];		
											if($cur_disc != '' and $row_prod['product_variablecomboprice_allowed']!='Y')
											{
											?>
												<div class="shelfD_offer">
												<?php echo $cur_disc?>
												</div>
											<?php
											}
											?>
											<div class="<?php echo $img_cls?>"> 
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
											</div>
											<div class="shelfDprice">
											<span>
											<?php 
												$price_arr =  show_Price($row_prod,array(),'shelfcenter_1',false,3);
												if($price_arr['discounted_price'])
													echo $price_arr['discounted_price'];
												else
													echo $price_arr['base_price'];
											?>
											</span>
											</div>
											<div class="shelfDbuy"><span>
											<?php
											$class_arr 					= array();
											$class_arr['ADD_TO_CART']	= 'infolink_special1';
											$class_arr['PREORDER']		= 'infolink_special1';
											$class_arr['ENQUIRE']		= 'infolink_special1';
											show_addtocart($row_prod,$class_arr,$frm_name,false,'','','',1);
											?> 
											</span></div>
											<div class="shelfDprodname">
											<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" class="shelfDprodnamelink" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a>
											</div>
											</div>
											</form>
										<?php 
											if($cur_cnt>=$max_cnt)
											{
												echo '</div>';
												echo '</div>';
												$cur_cnt = 0;
											}
										}
										if($cur_cnt>0 and $cur_cnt<$max_cnt)
										{
											echo '</div>';
											echo '</div>';
										}
										?>
										</div>
									<?php	
									break;
									case '3row': // case of three in a row for christmas  christmas_1rowtableB
									?>
									<div class="shelfCdiv">
									<?php
										if($cur_title)
										{
									?>	
											<div class="shelfCheader"><?php echo $cur_title?></div>
									<?php
										}
										$desc = trim($shelfData['shelf_description']);
										if($desc!='' and $desc!='&nbsp;')
										{
									?>	
											<div class="shelfCdescription"><?php echo stripslash_normal($desc)?></div>
									<?php
										}
									?>	
										<?php
											$cur_cnt = 0;
											$max_cnt = 4;
											while($row_prod = $db->fetch_array($ret_prod))
											{
												if($cur_cnt==0)
													echo '<div class="shelfCdiv_pdt">';
												$cur_cnt++;
										?>
												<div class="shelfCpdt_inner">
												<?
												$cur_disc = '';
												$price_arr = show_Price($row_prod,$price_class_arr,'prod_detail',false,3);	
												if($price_arr['disc_percent']!='')
													$cur_disc = $price_arr['disc_percent'];
												elseif($price_arr['yousave_price']!='')
													$cur_disc = $price_arr['yousave_price']	;		
												if($cur_disc != '' and $row_prod['product_variablecomboprice_allowed']!='Y')
												{
												?>
												<div class="shelfC_offer"><?php echo $cur_disc?></div>
												<?php
												}
												?>
												<div class="shelfCimg"> 
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
												</div>
												<div class="shelfCprice_con">
												<div class="shelfCprice"><span>
												<?php 
													$price_arr =  show_Price($row_prod,array(),'shelfcenter_3',false,3);
													if($price_arr['discounted_price'])
														echo $price_arr['discounted_price'];
													else
														echo $price_arr['base_price'];
												?>
												</span></div></div>
											</div>
										<?php
												if($cur_cnt>=$max_cnt)
												{
													echo '</div>';
													$cur_cnt = 0;
												}	
											}
											if($cur_cnt>0 and $cur_cnt<$max_cnt)
											{
												echo '</div>';
											}
										?>	
										</div>
							<?php	
									break;
								};
							}
							elseif($shelfData['shelf_currentstyle']=='new') // case of new year layout
							{
								switch($shelfData['shelf_displaytype'])
								{
									case '1row': // case of one in a row for new year
									case 'dropdown':
									case 'list':
									?>
									<div class="shelfEdiv">
									<?php
									if($cur_title)
									{
									?>
									<div class="shelfEheader">  <?php echo stripslashes($cur_title)?></div> 
									<?
									}
									$desc = trim($shelfData['shelf_description']);
									if($desc!='' and $desc!='&nbsp;')
									{
									?>
									<div class="shelfEdescription"> <?php echo $desc?></div>
									<?
									}
									while($row_prod = $db->fetch_array($ret_prod))
									{
										$frm_name = uniqid('shelf_');
									?>
									<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
										<input type="hidden" name="fpurpose" value="" />
										<input type="hidden" name="fproduct_id" id="fproduct_id" value="" />
										<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
										<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
									<div class="shelfEinner">
									<div class="shelfEleft">
									
									<?php
											if($shelfData['shelf_showimage']==1)// whether image is to be displayed
											{
											 $pass_type='image_iconpath';
											?>	
												<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
											<?php
											// Calling the function to get the type of image to shown for current 
										//	$pass_type = get_default_imagetype('midshelf');
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
									?>
									</div>
									<div class="shelfEmid">	
									<?php
									if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
									{
									?>
										<div class="shelfEprodname"> 
										<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" class="shelfEprodnamelink" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a>
										</div>
									<?
									}
									if ($shelfData['shelf_showdescription']==1)
									{
									?> 
										<div class="shelfEmid_proddesc">
										<?php echo stripslashes($row_prod['product_shortdesc'])?>
										</div>
									<?php
									}
									if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
									{
										$cur_disc = '';
										$price_arr = show_Price($row_prod,$price_class_arr,'prod_detail',false,3);	
										if($price_arr['discounted_price'])
										{
								?>
											<span class="shelfEnormalprice" ><? echo $price_arr['discounted_price'];?></span> 
								<?  	}
										elseif($price_arr['base_price'])
										{
								?>
										   <span class="shelfEnormalprice"><? echo $price_arr['base_price']?></span> 
								<?  	}
									}
									?>							  
									</div>
									<div class="shelfEright"> 
									<?
									if ($shelfData['shelf_showprice']==1) 
										{
										$cur_disc = '';
											$price_arr = show_Price($row_prod,$price_class_arr,'prod_detail',false,3);	
											if($price_arr['disc_percent']!='')
												$cur_disc = $price_arr['disc_percent'];
											elseif($price_arr['yousave_price']!='')
												$cur_disc = $price_arr['yousave_price']	;		
											if($cur_disc != '' and $row_prod['product_variablecomboprice_allowed']!='Y')
											{
											?>
											<div class="shelfE_offer"><?php echo $cur_disc?></div> 
											<?
											}
										}
									?>
										<div class="shelfEbuy">
										<span> <?php
											$class_arr 					= array();
											$class_arr['ADD_TO_CART']	= 'infolink_newspecial1';
											$class_arr['PREORDER']		= 'infolink_newspecial1';
											$class_arr['ENQUIRE']		= 'infolink_newspecial1';
											show_addtocart($row_prod,$class_arr,$frm_name,false,'','','',1);
											?> </span>
										</div>
									</div>
									</div>
									</form>
									<?
									}
									?>			
									</div>
									<?php	
									break;
									case '3row': // case of three in a row for new year
									?>
									<div class="shelfFdiv">
									<?php
									if($cur_title)
									{
									?>	
										<div class="shelfFheader"> <?php echo $cur_title?></div>
									<?
									}
									?>                            
										<div class="shelfFdiv_pdt">
											<?php
											$desc = trim($shelfData['shelf_description']);
											if($desc!='' and $desc!='&nbsp;')
											{
											?>
											<div class="shelfFdescription"> <?php echo $desc?></div>  
											<?
											}
											while($row_prod = $db->fetch_array($ret_prod))
											{
											$prodcur_arr[] = $row_prod; 
											$frm_name = uniqid('shelf_');
											?>
												<div class="shelfFpdt_inner">
													<div class="shelfFinner_pdt">
														<div class="shelfF_buy">
															<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
															<input type="hidden" name="fpurpose" value="" />
															<input type="hidden" name="fproduct_id" value="" />
															<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
															<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
																<?php
																$class_arr 					= array();
																$class_arr['ADD_TO_CART']	= 'infolink_newspecial1';
																$class_arr['PREORDER']		= 'infolink_newspecial1';
																$class_arr['ENQUIRE']		= 'infolink_newspecial1';
																show_addtocart($row_prod,$class_arr,$frm_name,false,'','','',1);
																?>
															</form>
														</div>
														<?php
														if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
														{
															?>
															<div class="shelfFprodname">
															<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="shelfFprodnamelink"><?php echo stripslashes($row_prod['product_name'])?></a></div>
															<?
														}
														?>
														<?
														if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
														{
															?>
																<div class="shelfFprice">
																	<?php
																		$price_arr =  show_Price($row_prod,array(),'shelfcenter_1',false,3);
																		if($price_arr['discounted_price'])
																		echo $price_arr['discounted_price'];
																		else
																		echo $price_arr['base_price'];
																	?>
																</div>
															<?
														}
														if($shelfData['shelf_showimage']==1)// whether image is to be displayed
														{
														 $pass_type='image_iconpath';
															?>						
																<div class="shelfFimg"> 
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
																</div>
															<?
														}
														?>
														</div>
												</div>
											<?
											}
											?>
										</div>
									</div>
									<?php
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