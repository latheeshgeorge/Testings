<?php
	/*############################################################################
	# Script Name 	: searchHtml.php
	# Description 	: Page which holds the display logic for search
	# Coded by 		: LSH
	# Created on	: 01-Feb-2008
	##########################################################################*/
	class search_Html
	{
		// Defining function to show the shelf details
		function Show_Search($title,$shelf_arr)
		{
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr;
			if (count($shelf_arr))
			{
				$shelfsort_by			= $Settings_arr['product_orderfield_search'];
				$prodperpage			= $Settings_arr['product_maxcntperpage_search'];// product per page
				$showqty				= $Settings_arr['show_qty_box'];// show the qty box
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
					default: // by default order by product name
					$shelfsort_by		= 'a.product_name';
					break;
				};
				$shelfsort_order		= $Settings_arr['product_orderby_search'];
				$prev_shelf				= 0;
				//Iterating through the shelf array to fetch the product to be shown.
				/*echo "<pre>";
				print_r($shelf_arr);*/
				foreach ($shelf_arr as $k=>$shelfData)
				{
					// Check whether shelf_activateperiodchange is set to 1
					// Getting the feature_id for mod_shelf
						$sql_feature = "SELECT feature_id FROM features WHERE feature_modulename='mod_shelf'";
						$ret_feature = $db->query($sql_feature);
						if ($db->num_rows($ret_feature))
						{
							$row_feature 	= $db->fetch_array($ret_feature);
							$feat_id		= $row_feature['feature_id'];
						}
						// Find the layoutid for current layout code
						$sql_layout = "SELECT layout_id 
										FROM 
											themes_layouts 
										WHERE 
											themes_theme_id = $ecom_themeid 
											AND layout_code='$default_layout'";
						$ret_layout = $db->query($sql_layout);
						if ($db->num_rows($ret_layout))
						{
							$row_layout = $db->fetch_array($ret_layout);
							$layid		= $row_layout['layout_id'];
						}					
						// Get the title to be shown from the display settings table
						/*$sql_disp = "SELECT display_title 
										FROM 
											display_settings 
										WHERE 
											sites_site_id = $ecom_siteid 
											AND features_feature_id=$feat_id 
											AND display_position ='middle' 
											AND themes_layouts_layout_id=$layid 
											AND layout_code ='".$default_layout."' 
											AND display_component_id=".$shelfData['shelf_id'];
						$ret_disp = $db->query($sql_disp);
						if ($db->num_rows($ret_disp))
						{
							$row_disp 	= $db->fetch_array($ret_disp);
							$cur_title 	= stripslashes($row_disp['display_title']); 
						}*/
						// Get the total number of product in current shelf
						$sql_totprod = "SELECT count(product_id) 
									FROM 
										products 
									WHERE 
										product_id = ".$shelfData['product_id']." 
										AND product_hide = 'N' ";
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
										product_total_preorder_allowed,a.product_applytax,a.product_shortdesc 
									FROM 
										products a,product_shelf_product b 
									WHERE 
										a.product_id =". $shelfData['product_id'] ."
										AND a.product_hide = 'N' 
									ORDER BY 
										$shelfsort_by $shelfsort_order 
									$Limit	";
									//echo $sql_prod;
						$ret_prod = $db->query($sql_prod);
						if ($db->num_rows($ret_prod))
						{
							// Number of result to display on the page, will be in the LIMIT of the sql query also
							$querystring = ""; // if any additional query string required specify it over here
								switch($Settings_arr['search_prodlisting'])
								{
									case '1row': // case of one in a row for normal
									?>
										<table border="0" cellpadding="0" cellspacing="0" class="shelfBtable">
										<?php
											if($cur_title)
											{
										?>
											<tr>
												<td colspan="3" class="shelfBheader" align="left"><?php echo $cur_title?></td>
											</tr>
										<?php
											}
											if ($tot_cnt>0 and ($_REQUEST['req']!=''))
											{
											?>
												<tr>
													<td colspan="3" class="pagingcontainertd" align="center">
													<?php 
														$path = '';
														$query_string .= "";
														paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr); 	
													?>	
													</td>
												</tr>
											<?php
											}
											while($row_prod = $db->fetch_array($ret_prod))
											{
										?>
												<tr>
													<td align="left" valign="middle" class="shelfBtabletd">
														<?php
															if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
															{
														?>
																<h1 class="shelfBprodname"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h1>
														<?php
															}
															if ($shelfData['shelf_showdescription']==1)// Check whether description is to be displayed
															{
														?>		
																<h6 class="shelfBproddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6>
														<?php
															}
														?>		
													</td>
													<td align="center" valign="middle" class="shelfBtabletd">
													<?php
														if ($shelfData['shelf_showimage']==1)// Check whether description is to be displayed
														{
													?>		
															<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
															<?php
																// Calling the function to get the type of image to shown for current 
																$pass_type = get_default_imagetype('midshelf');
																// Calling the function to get the image to be shown
																$img_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,0,0,1);
																if(count($img_arr))
																{
																	show_image(url_root_image($img_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name']);
																}
																else
																{
																	// calling the function to get the default image
																	$no_img = get_noimage('prod'); 
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
													</td>
													<td align="left" valign="middle" class="shelfBtabletd">
													<?php
														if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
														{
															$price_class_arr['ul_class'] 		= 'shelfBul';
															$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
															$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
															$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
															$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
															echo show_Price($row_prod,$price_class_arr,'shelfcenter_1');
														}
															$frm_name = uniqid('shelf_');
													?>	
															<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls">
															<input type="hidden" name="fpurpose" value="" />
															<input type="hidden" name="fproduct_id" value="" />
															<?php
																if($showqty==1)// this decision is made in the main shop settings
																{
															?>
																<div class="quantity"><?php echo $Captions_arr['COMMON']['COMMON_QTY']?><input type="text" class="quainput" name="qty"  value="1" /></div>
															<?php
																}
															?>
															<div class="infodiv">
																<div class="infodivleft"><?php show_moreinfo($row_prod,'infolink')?></div>
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
													</td>
											</tr>
										<?php
											}
										?>	
										</table>
									<?php
									break;
									case '3row': // case of three in a row for normal
									?>
										<table border="0" cellpadding="0" cellspacing="0" class="shelfAtable">
										<?php
										if($cur_title)
										{
										?>
											<tr>
												<td colspan="3" class="shelfAheader" align="left"><?php echo $cur_title?></td>
											</tr>
										<?php
										}
										if ($tot_cnt>0 and ($_REQUEST['req']!=''))
										{
										?>
											<tr>
												<td colspan="3" class="pagingcontainertd" align="center">
												<?php 
													$path = '';
													//show_paging($path,$_REQUEST[$pg_variable],$start_arr['counterstart'],$tot_cnt,$prodperpage,$pg_variable,'')
														$query_string .= "";
														paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr); 	
												?>	
												</td>
											</tr>
										<?php
										}
										?>	
										<tr>
										<?php
											$max_col = 3;
											$cur_col = 0;
											$prodcur_arr = array();
											while($row_prod = $db->fetch_array($ret_prod))
											{
												$prodcur_arr[] = $row_prod;
												//##############################################################
												// Showing the title, description and image part for the product
												//##############################################################
										?>
												<td class="shelfAtabletd" align="left" valign="top">
													<ul class="shelfAul">
														<?php
															if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
															{
														?>
																<li><h1 class="shelfAprodname"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h1></li>
														<?php
															}
															if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
															{
														?>
																<li>
																	<?php
																		$price_class_arr['ul_class'] 		= 'shelfBul';
																		$price_class_arr['normal_class'] 	= 'shelfAnormalprice';
																		$price_class_arr['strike_class'] 	= 'shelfAstrikeprice';
																		$price_class_arr['yousave_class'] 	= 'shelfAyousaveprice';
																		$price_class_arr['discount_class'] 	= 'shelfAdiscountprice';
																		echo show_Price($row_prod,$price_class_arr,'shelfcenter_3');
																	?>	
																</li>
														<?php
															}
															if ($shelfData['shelf_showimage']==1)// Check whether description is to be displayed
															{
														?>		
																<li>
																	<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
																	<?php
																		// Calling the function to get the type of image to shown for current 
																		$pass_type = get_default_imagetype('midshelf');
																		// Calling the function to get the image to be shown
																		$img_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,0,0,1);
																		if(count($img_arr))
																		{
																			show_image(url_root_image($img_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name']);
																		}
																		else
																		{
																			// calling the function to get the default image
																			$no_img = get_noimage('prod'); 
																			if ($no_img)
																			{
																				show_image($no_img,$row_prod['product_name'],$row_prod['product_name']);
																			}	
																		}	
																	?>
																	</a>
																</li>
														<?php
															}
														if ($shelfData['shelf_showdescription']==1)// Check whether description is to be displayed
														{
														?>
															<li><h6 class="shelfAproddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6></li>
														<?php
														}
														?>	
													</ul>
												</td>
											<?php
												$cur_col++;
												if ($cur_col>=$max_col)
												{
													echo "</tr>";
													$cur_tempcol = $cur_col = 0;
													//##############################################################
													// Showing the more info and add to cart links after each row in 
													// case of breaking to new row while looping
													//##############################################################
													echo "<tr>";
													foreach($prodcur_arr as $k=>$prod_arr)
													{
														$frm_name = uniqid('shelf_');
													?>
														<td class="shelfAtabletd">
															<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls">
															<input type="hidden" name="fpurpose" value="" />
															<input type="hidden" name="fproduct_id" value="" />
															<?php
																if($showqty==1)// this decision is made in the main shop settings
																{
															?>
																<div class="quantity"><?php echo $Captions_arr['COMMON']['COMMON_QTY']?><input type="text" class="quainput" name="qty"  value="1" /></div>
															<?php
																}
															?>
															<div class="infodiv">
																<div class="infodivleft"><?php show_moreinfo($prod_arr,'infolink')?></div>
																<div class="infodivright">
																<?php
																	$class_arr 					= array();
																	$class_arr['ADD_TO_CART']	= 'infolink';
																	$class_arr['PREORDER']		= 'infolink';
																	$class_arr['ENQUIRE']		= 'infolink';
																	show_addtocart($prod_arr,$class_arr,$frm_name)
																?>
																</div>
															</div>
															</form>
														</td>
											<?php
														++$cur_tempcol;
														// done to handle the case of breaking to new linel
														if ($cur_tempcol>=$max_col)
														{
															echo "</tr>";
															$cur_tempcol=0;
														}
													}
													echo "<tr>";
													$prodcur_arr = array();	
												}
											}
											// If in case total product is less than the max allowed per row then handle that situation
											if ($cur_col<$max_col)
											{
												echo "<td colspan='".($max_col-$cur_col)."'>&nbsp;</td></tr><tr>";
												$cur_tempcol = $cur_col = 0;
												//##############################################################
												// Done to handle the case of showing the qty, add to cart and more info links
												// in case if total product is less than the max allower per row.
												//##############################################################
												foreach($prodcur_arr as $k=>$prod_arr)
												{
													$frm_name = uniqid('shelf_');
												?>
													<td class="shelfAtabletd">
														<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls">
														<input type="hidden" name="fpurpose" value="" />
														<input type="hidden" name="fproduct_id" value="" />
														<?php
															if($showqty==1)// this decision is made in the main shop settings
															{
														?>
															<div class="quantity"><?php echo $Captions_arr['COMMON']['COMMON_QTY']?><input type="text" class="quainput" name="qty"  value="1" /></div>
														<?php
															}
														?>
														<div class="infodiv">
															<div class="infodivleft"><?php show_moreinfo($prod_arr,'infolink')?></div>
															<div class="infodivright">
															<?php
																$class_arr 					= array();
																$class_arr['ADD_TO_CART']	= 'infolink';
																$class_arr['PREORDER']		= 'infolink';
																$class_arr['ENQUIRE']		= 'infolink';
																show_addtocart($prod_arr,$class_arr,$frm_name)
															?>
															</div>
														</div>
														</form>
													</td>
										<?php
													++$cur_tempcol;
													if ($cur_tempcol>=$max_col)
													{
														echo "</tr><tr>";
														$cur_tempcol=0;
													}
												}
												echo "<td colspan='".($max_col-$cur_tempcol)."'>&nbsp;</td></tr>";
											}
											else
												echo "</tr>";
											$prodcur_arr = array();
											?>
										</table>
								<?php		
									break;
							};
					}
				}
			}	
		}
	};	
?>