<?php
	/*############################################################################
	# Script Name 	: shelfHtml.php
	# Description 		: Page which holds the display logic for middle shelves
	# Coded by 		: Sny
	# Created on		: 14-May-2009
	# Modified by		: Sny
	# Modified On		: 15-May-2009
	##########################################################################*/
	class shelf_Html
	{
		// Defining function to show the shelf details
		function Show_Shelf($cur_title,$shelf_arr)
		{
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr;
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
										a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,a.product_variablesaddonprice_exists       
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
									case 'dropdown':
									case 'list':
									case '1row': // case of one in a row for normal
									?>
										<div class="shelf_main_con" >
										<?php 
										if($cur_title)
										{
										?>
											<div class="shelf_top"><?php echo $cur_title?></div>
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
											if ($tot_cnt>0 and ($_REQUEST['req']!=''))
											{
											?>
												<div class="pagingcontainertd" align="center">
													<?php 
														$path = '';
														$query_string .= "";
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
												<?php
														if ($shelfData['shelf_showimage']==1)// Check whether description is to be displayed
														{
													?>		
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
														}
													?>	
												</div>
												<div class="shlf_pdt_compare" >
												<?php if($comp_active)  {
															dislplayCompareButton($row_prod['product_id']);
														}?>
												</div>
												</div>
												<div class="shlf_pdt_txt">
												<ul class="shlf_pdt_ul">
												<?php
													if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
													{
												?>
														<li class="shlf_pdt_name" ><h3><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h3></li>
												
												<?php
													}
													if ($shelfData['shelf_showdescription']==1)// Check whether description is to be displayed
													{
												?>		
														<li class="shlf_pdt_des"><h6>	<?php echo stripslashes($row_prod['product_shortdesc'])?></h6></li>
												<?php
													}
												?>			
												</ul>
												<?php
												if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
												{ 
													$price_class_arr['ul_class'] 		= 'shelf_price_ul';
													$price_class_arr['normal_class'] 	= 'shelfAnormalprice';
													$price_class_arr['strike_class'] 	= 'shelfAstrikeprice';
													$price_class_arr['yousave_class'] 	= 'shelfAyousaveprice';
													$price_class_arr['discount_class'] 	= 'shelfAdiscountprice';
													echo show_Price($row_prod,$price_class_arr,'shelfcenter_1');
													show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
													show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points
													$frm_name = uniqid('shelf_');
												}
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
										<div class="shelfA_main_con" > 
										<?php 
										if($cur_title)
										{
										?>
											<div class="shelfA_top"><?php echo $cur_title?></div>
										<?php
										}
										?>
										<div class="shelfA_mid">
										<?php
										$desc = trim($shelfData['shelf_description']);
										if($desc!='' and $desc!='&nbsp;')
										{
									?>
											<div class="shelf_bottom_desc"><?php echo $desc?></div>
									<?php		
										}
										
										if ($tot_cnt>0 and ($_REQUEST['req']!=''))
										{
										?>
											<div class="pagingcontainertd">
												<?php 
													$path = '';
													//show_paging($path,$_REQUEST[$pg_variable],$start_arr['counterstart'],$tot_cnt,$prodperpage,$pg_variable,'')
														$query_string .= "";
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
										<?php
												if ($shelfData['shelf_showimage']==1)// Check whether description is to be displayed
												{
											?>		
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
													}
												  ?>
												 
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
																	  <?php
															if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
															{
														?>
															<li class="shlfA_pdt_name" ><h3><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>"><?php echo stripslashes($row_prod['product_name'])?></a></h3></li>
														<?php
															}
															if ($shelfData['shelf_showdescription']==1)// Check whether description is to be displayed
															{
														?>
																<li class="shlfA_pdt_des" ><h6><?php echo stripslashes($row_prod['product_shortdesc'])?></h6></li>
														<?php
															}
														?>	
														<li class='shlfA_pdt_bonus'><h6><?php show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points?></h6></li>
														</ul>
														<?php	
															if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
															{
														?>
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
															}
															$frm_name = uniqid('shelf_');
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
							elseif($shelfData['shelf_currentstyle']=='christ') // case of christmas layout
							{
								switch($shelfData['shelf_displaytype'])
								{
									case '1row':
									case 'dropdown':
									case 'list': // case of one in a row for christmas  christmas_rowtableB class="" 
									?>
										<div class="shelf_main_con_christ" >
										<?php 
										if($cur_title)
										{
										?>
											<div class="shelf_top_christ"><?php echo $cur_title?></div>
										<?
										}
										?>
											<div class="shelf_mid_christ">
										<?php
										$desc = trim($shelfData['shelf_description']);
										if($desc!='' and $desc!='&nbsp;')
										{
										?>
											<div class="shelf_top_desc_christ"><?php echo $desc?></div>
										<?php		
										}
											if ($tot_cnt>0 and ($_REQUEST['req']!=''))
											{
											?>
												<div class="pagingcontainertd_christ" align="center">
													<?php 
														$path = '';
														$query_string .= "";
														paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr); 	
													?>	
													</div>
											<?php
											}
											
											$cur = 1;
											
											while($row_prod = $db->fetch_array($ret_prod))
											{
												if($cur==$tot_cnt)
													$main_shelf = 'shlf_main_last_christ';
												else
													$main_shelf = 'shlf_main_christ';
												$cur++;
												
										?>
												<div class="<?php echo $main_shelf?>">
												<div class="shlf_pdt_img_outr_christ">
												<div class="shlf_pdt_img_christ">
												<?php
														if ($shelfData['shelf_showimage']==1)// Check whether description is to be displayed
														{
													?>		
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
														}
													?>	
												</div>
												<div class="shlf_pdt_compare_christ" >
												<?php if($comp_active)  {
															dislplayCompareButton($row_prod['product_id']);
														}?>
												</div>
												</div>
												<div class="shlf_pdt_txt_christ">
												<ul class="shlf_pdt_u_christl">
												<?php
													if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
													{
												?>
														<li class="shlf_pdt_name_christ" ><h3><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h3></li>
												
												<?php
													}
													if ($shelfData['shelf_showdescription']==1)// Check whether description is to be displayed
													{
												?>		
														<li class="shlf_pdt_des_christ"><h6>	<?php echo stripslashes($row_prod['product_shortdesc'])?></h6></li>
												<?php
													}
												?>			
												</ul>
												<?php
												if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
												{ 
													$price_class_arr['ul_class'] 		= 'shelf_price_ul_christ';
													$price_class_arr['normal_class'] 	= 'shelfAnormalprice_christ';
													$price_class_arr['strike_class'] 	= 'shelfAstrikeprice_christ';
													$price_class_arr['yousave_class'] 	= 'shelfAyousaveprice_christ';
													$price_class_arr['discount_class'] 	= 'shelfAdiscountprice_christ';
													echo show_Price($row_prod,$price_class_arr,'shelfcenter_1');
													show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
													show_bonus_points_msg($row_prod,'bonus_point_christ'); // Show bonus points
													$frm_name = uniqid('shelf_');
												}
												?>
												<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
												<input type="hidden" name="fpurpose" value="" />
												<input type="hidden" name="fproduct_id" value="" />
												<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
												<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
												<div class="infodiv_christ">
												<div class="infodivleft_christ"><?php show_moreinfo($row_prod,'infolink_christ')?></div>
												<div class="infodivright_christ">
												<?php
														$class_arr 							= array();
														$class_arr['ADD_TO_CART']	= 'quantity_infolink_christ';
														$class_arr['PREORDER']			= 'quantity_infolink_christ';
														$class_arr['ENQUIRE']			= 'quantity_infolink_christ';
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
										<div class="shelf_bottom_christ"></div>	
								</div>
									<?php	
									break;
									case '3row': // case of three in a row for christmas  christmas_1rowtableB
									?>
										<div class="shelfA_main_con_christ" > 
										<?php 
										if($cur_title)
										{
										?>
											<div class="shelfA_top_christ"><?php echo $cur_title?></div>
										<?php
										}
										?>
										<div class="shelfA_mid_christ">
										<?php
										$desc = trim($shelfData['shelf_description']);
										if($desc!='' and $desc!='&nbsp;')
										{
									?>
											<div class="shelf_bottom_desc_christ"><?php echo $desc?></div>
									<?php		
										}
										if ($tot_cnt>0 and ($_REQUEST['req']!=''))
										{
										?>
											<div class="pagingcontainertd_christ">
												<?php 
													$path = '';
													//show_paging($path,$_REQUEST[$pg_variable],$start_arr['counterstart'],$tot_cnt,$prodperpage,$pg_variable,'')
														$query_string .= "";
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
													$main_shelf = 'shlfA_main_last_christ';
												else
													$main_shelf = 'shlfA_main_christ';
												$cur++;
												//##############################################################
												// Showing the title, description and image part for the product
												//##############################################################
												if($cur_col==0)
												{
													$main_inner_shelf = 'shlfA_inner_main_lst_christ';
										?>
													 <div class="<?php echo $main_shelf?>">
										<?php
												}
												else
													$main_inner_shelf = 'shlfA_inner_main_christ';
										?>
												<div class="<?php echo $main_inner_shelf?>">
										<?php
												if ($shelfData['shelf_showimage']==1)// Check whether description is to be displayed
												{
											?>		
													<div class="shlfA_pdt_img_christ">
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
													}
												  ?>
												 
												  <?php 
												  if($comp_active) 
												  {
												  ?>
												  	<div class="shlfA_pdt_compare_christ" >
												<?php
														dislplayCompareButton($row_prod['product_id']);
												?>		
													</div>
												<?php
													}?>
												  <div class="shlfA_pdt_txt_christ">
																	  <ul class="shlfA_pdt_ul_christ">
																	  <?php
															if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
															{
														?>
															<li class="shlfA_pdt_name_christ" ><h3><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>"><?php echo stripslashes($row_prod['product_name'])?></a></h3></li>
														<?php
															}
															if ($shelfData['shelf_showdescription']==1)// Check whether description is to be displayed
															{
														?>
																<li class="shlfA_pdt_des_christ" ><h6><?php echo stripslashes($row_prod['product_shortdesc'])?></h6></li>
														<?php
															}
														?>	
														<li class='shlfA_pdt_bonus_christ'><h6><?php show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points?></h6></li>
														</ul>
														<?php	
															if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
															{
														?>
																<li>
																	<?php
																		$price_class_arr['ul_class'] 		= 'shelfA_price_ul_christ';
																		$price_class_arr['normal_class'] 	= 'shelfBnormalprice_christ';
																		$price_class_arr['strike_class'] 	= 'shelfBstrikeprice_christ';
																		$price_class_arr['yousave_class'] 	= 'shelfByousaveprice_christ';
																		$price_class_arr['discount_class'] 	= 'shelfBdiscountprice_christ';
																		echo show_Price($row_prod,$price_class_arr,'shelfcenter_3');
																		show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
																	?>	
																</li>
														<?php
															}
															$frm_name = uniqid('shelf_');
															?>
															<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
															<input type="hidden" name="fpurpose" value="" />
															<input type="hidden" name="fproduct_id" value="" />
															<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
															<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
															<div class="infodivB_christ">
																<div class="infodivleftB_christ"><?php show_moreinfo($row_prod,'infolinkB_christ')?></div>
																<div class="infodivrightB_christ">
																<?php
																		$class_arr 							= array();
																		$class_arr['ADD_TO_CART']	= 'quantity_infolinkB_christ';
																		$class_arr['PREORDER']			= 'quantity_infolinkB_christ';
																		$class_arr['ENQUIRE']			= 'quantity_infolinkB_christ';
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
										<div class="shelfA_bottom_christ"></div>
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
										<div class="shelf_main_con_newyear" >
										<?php 
										if($cur_title)
										{
										?>
											<div class="shelf_top_newyear"><?php echo $cur_title?></div>
										<?
										}
										?>
											<div class="shelf_mid_newyear">
										<?php
										$desc = trim($shelfData['shelf_description']);
										if($desc!='' and $desc!='&nbsp;')
										{
										?>
											<div class="shelf_top_desc_newyear"><?php echo $desc?></div>
										<?php		
										}
											if ($tot_cnt>0 and ($_REQUEST['req']!=''))
											{
											?>
												<div class="pagingcontainertd_newyear" align="center">
													<?php 
														$path = '';
														$query_string .= "";
														paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr); 	
													?>	
													</div>
											<?php
											}
											
											$cur = 1;
											
											while($row_prod = $db->fetch_array($ret_prod))
											{
												if($cur==$tot_cnt)
													$main_shelf = 'shlf_main_last_newyear';
												else
													$main_shelf = 'shlf_main_newyear';
												$cur++;
												
										?>
												<div class="<?php echo $main_shelf?>">
												<div class="shlf_pdt_img_outr_newyear">
												<div class="shlf_pdt_img_newyear">
												<?php
														if ($shelfData['shelf_showimage']==1)// Check whether description is to be displayed
														{
													?>		
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
														}
													?>	
												</div>
												<div class="shlf_pdt_compare_newyear" >
												<?php if($comp_active)  {
															dislplayCompareButton($row_prod['product_id']);
														}?>
												</div>
												</div>
												<div class="shlf_pdt_txt_newyear">
												<ul class="shlf_pdt_u_newyearl">
												<?php
													if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
													{
												?>
														<li class="shlf_pdt_name_newyear" ><h3><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h3></li>
												
												<?php
													}
													if ($shelfData['shelf_showdescription']==1)// Check whether description is to be displayed
													{
												?>		
														<li class="shlf_pdt_des_newyear"><h6>	<?php echo stripslashes($row_prod['product_shortdesc'])?></h6></li>
												<?php
													}
												?>			
												</ul>
												<?php
												if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
												{ 
													$price_class_arr['ul_class'] 		= 'shelf_price_ul_newyear';
													$price_class_arr['normal_class'] 	= 'shelfAnormalprice_newyear';
													$price_class_arr['strike_class'] 	= 'shelfAstrikeprice_newyear';
													$price_class_arr['yousave_class'] 	= 'shelfAyousaveprice_newyear';
													$price_class_arr['discount_class'] 	= 'shelfAdiscountprice_newyear';
													echo show_Price($row_prod,$price_class_arr,'shelfcenter_1');
													show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
													show_bonus_points_msg($row_prod,'bonus_point_newyear'); // Show bonus points
													$frm_name = uniqid('shelf_');
												}
												?>
												<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
												<input type="hidden" name="fpurpose" value="" />
												<input type="hidden" name="fproduct_id" value="" />
												<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
												<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
												<div class="infodiv_newyear">
												<div class="infodivleft_newyear"><?php show_moreinfo($row_prod,'infolink_newyear')?></div>
												<div class="infodivright_newyear">
												<?php
														$class_arr 							= array();
														$class_arr['ADD_TO_CART']	= 'quantity_infolink_newyear';
														$class_arr['PREORDER']			= 'quantity_infolink_newyear';
														$class_arr['ENQUIRE']			= 'quantity_infolink_newyear';
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
										<div class="shelf_bottom_newyear"></div>	
								</div>
									<?php	
									break;
									case '3row': // case of three in a row for new year
									?>
										<div class="shelfA_main_con_newyear" > 
										<?php 
										if($cur_title)
										{
										?>
											<div class="shelfA_top_newyear"><?php echo $cur_title?></div>
										<?php
										}
										?>
										<div class="shelfA_mid_newyear">
										<?php
										$desc = trim($shelfData['shelf_description']);
										if($desc!='' and $desc!='&nbsp;')
										{
									?>
											<div class="shelf_bottom_desc_newyear"><?php echo $desc?></div>
									<?php		
										}
										if ($tot_cnt>0 and ($_REQUEST['req']!=''))
										{
										?>
											<div class="pagingcontainertd_newyear">
												<?php 
													$path = '';
													//show_paging($path,$_REQUEST[$pg_variable],$start_arr['counterstart'],$tot_cnt,$prodperpage,$pg_variable,'')
														$query_string .= "";
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
													$main_shelf = 'shlfA_main_last_newyear';
												else
													$main_shelf = 'shlfA_main_newyear';
												$cur++;
												//##############################################################
												// Showing the title, description and image part for the product
												//##############################################################
												if($cur_col==0)
												{
													$main_inner_shelf = 'shlfA_inner_main_lst_newyear';
										?>
													 <div class="<?php echo $main_shelf?>">
										<?php
												}
												else
													$main_inner_shelf = 'shlfA_inner_main_newyear';
										?>
												<div class="<?php echo $main_inner_shelf?>">
										<?php
												if ($shelfData['shelf_showimage']==1)// Check whether description is to be displayed
												{
											?>		
													<div class="shlfA_pdt_img_newyear">
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
													}
												  ?>
												 
												  <?php 
												  if($comp_active) 
												  {
												  ?>
												  	<div class="shlfA_pdt_compare_newyear" >
												<?php
														dislplayCompareButton($row_prod['product_id']);
												?>		
													</div>
												<?php
													}?>
												  <div class="shlfA_pdt_txt_newyear">
															<ul class="shlfA_pdt_ul_newyear">
														<?php
															if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
															{
														?>
															<li class="shlfA_pdt_name_newyear" ><h3><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>"><?php echo stripslashes($row_prod['product_name'])?></a></h3></li>
														<?php
															}
															if ($shelfData['shelf_showdescription']==1)// Check whether description is to be displayed
															{
														?>
																<li class="shlfA_pdt_des_newyear" ><h6><?php echo stripslashes($row_prod['product_shortdesc'])?></h6></li>
														<?php
															}
														?>	
														<li class='shlfA_pdt_bonus_newyear'><h6><?php show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points?></h6></li>
														</ul>
														<?php	
															if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
															{
														?>
																<li>
																	<?php
																		$price_class_arr['ul_class'] 		= 'shelfA_price_ul_newyear';
																		$price_class_arr['normal_class'] 	= 'shelfBnormalprice_newyear';
																		$price_class_arr['strike_class'] 	= 'shelfBstrikeprice_newyear';
																		$price_class_arr['yousave_class'] 	= 'shelfByousaveprice_newyear';
																		$price_class_arr['discount_class'] 	= 'shelfBdiscountprice_newyear';
																		echo show_Price($row_prod,$price_class_arr,'shelfcenter_3');
																		show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
																	?>	
																</li>
														<?php
															}
															$frm_name = uniqid('shelf_');
															?>
															<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
															<input type="hidden" name="fpurpose" value="" />
															<input type="hidden" name="fproduct_id" value="" />
															<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
															<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
															<div class="infodivB_newyear">
																<div class="infodivleftB_newyear"><?php show_moreinfo($row_prod,'infolinkB_newyear')?></div>
																<div class="infodivrightB_newyear">
																<?php
																		$class_arr 							= array();
																		$class_arr['ADD_TO_CART']	= 'quantity_infolinkB_newyear';
																		$class_arr['PREORDER']			= 'quantity_infolinkB_newyear';
																		$class_arr['ENQUIRE']			= 'quantity_infolinkB_newyear';
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
										<div class="shelfA_bottom_newyear"></div>
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