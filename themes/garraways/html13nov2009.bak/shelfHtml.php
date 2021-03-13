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
						/*// Getting the feature_id for mod_shelf
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
						$sql_disp = "SELECT display_title 
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
						}
						// check whether any title passed and title not obtained from the display settings for current position 
						if(!$cur_title) $cur_title = $title;*/
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
											a.price_discountprefix, a.price_discountsuffix, a.price_yousaveprefix, a.price_yousavesuffix,a.price_noprice        
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
										<table border="0" cellpadding="0" cellspacing="0" class="shelfBtable">
										<?php 
										if($cur_title){
										?>
											
											<tr>
												<td colspan="3" class="shelfBheader" align="left"><?php echo $cur_title?></td>
											</tr>
										<?	}
											$desc = trim($shelfData['shelf_description']);
											if($desc!='' and $desc!='&nbsp;')
											{
										?>
												<tr>
													<td colspan="3" class="shelfBproddes" align="left"><?php echo $desc?></td>
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
												<tr onmouseover="this.className='normalshelf_hover'" onmouseout="this.className='shelfBtabletd'">
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
														<?php if($comp_active)  {
															dislplayCompareButton($row_prod['product_id']);
														}?>
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
															show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
															//show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points
															$pass_arr['main_cls'] 		= 'bonus_point';
															$pass_arr['caption_cls'] 	= 'bonus_point_caption';
															$pass_arr['point_cls'] 		= 'bonus_point_number';
															show_bonus_points_msg_multicolor($row_prod,$pass_arr);
														}
															$frm_name = uniqid('shelf_');
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
																	$class_arr 					= array();
																	$class_arr['ADD_TO_CART']	= 'quantity_infolink';
																	$class_arr['PREORDER']		= 'quantity_infolink';
																	$class_arr['ENQUIRE']		= 'quantity_infolink';
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
										$desc = trim($shelfData['shelf_description']);
										if($desc!='' and $desc!='&nbsp;')
										{
									?>
											<tr>
												<td colspan="3" class="shelfAproddes" align="left"><?php echo $desc?></td>
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
												<td class="shelfAtabletd" align="left" valign="top" onmouseover="this.className='normalshelf_hover'" onmouseout="this.className='shelfAtabletd'">
												
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
																		show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
																	?>	
																</li>
														<?php
															}
															if ($shelfData['shelf_showimage']==1)// Check whether description is to be displayed
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
														<?php //show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points
															$pass_arr['main_cls'] 		= 'bonus_point';
															$pass_arr['caption_cls'] 	= 'bonus_point_caption';
															$pass_arr['point_cls'] 		= 'bonus_point_number';
															show_bonus_points_msg_multicolor($row_prod,$pass_arr);
														?>
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
															<?php if($comp_active)  {
															dislplayCompareButton($prod_arr['product_id']);
															}?>
															<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $prod_arr['product_id']?>)">
															<input type="hidden" name="fpurpose" value="" />
															<input type="hidden" name="fproduct_id" value="" />
															<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
															<input type="hidden" name="fproduct_url" value="<?php url_product($prod_arr['product_id'],$prod_arr['product_name'])?>" />
															
															<div class="infodiv">
																<div class="infodivleft"><?php show_moreinfo($prod_arr,'infolink')?></div>
																<div class="infodivright">
																<?php
																	$class_arr 					= array();
																	$class_arr['ADD_TO_CART']	= 'quantity_infolink';
																	$class_arr['PREORDER']		= 'quantity_infolink';
																	$class_arr['ENQUIRE']		= 'quantity_infolink';
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
														<?php if($comp_active)  {
														dislplayCompareButton($prod_arr['product_id']);
														}?>
														<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $prod_arr['product_id']?>)">
														<input type="hidden" name="fpurpose" value="" />
														<input type="hidden" name="fproduct_id" value="" />
														<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
														<input type="hidden" name="fproduct_url" value="<?php url_product($prod_arr['product_id'],$prod_arr['product_name'])?>" />
														<div class="infodiv">
															<div class="infodivleft"><?php show_moreinfo($prod_arr,'infolink')?></div>
															<div class="infodivright">
															<?php
																$class_arr 					= array();
																$class_arr['ADD_TO_CART']	= 'quantity_infolink';
																$class_arr['PREORDER']		= 'quantity_infolink';
																$class_arr['ENQUIRE']		= 'quantity_infolink';
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
							elseif($shelfData['shelf_currentstyle']=='christ') // case of christmas layout
							{
								switch($shelfData['shelf_displaytype'])
								{
									case '1row': // case of one in a row for christmas  christmas_rowtableB class="" 
									case 'dropdown':
									case 'list':
									?>
											<table border="0" cellpadding="0" cellspacing="0" class="christmas_1rowtableB">
										<?php
											if($cur_title)
											{
										?>
											<tr>
												<td colspan="3" class="christmas_specialoffertopbg" align="left"><?php echo $cur_title?></td>
											</tr>
										<?php
											}
											$desc = trim($shelfData['shelf_description']);
											if($desc!='' and $desc!='&nbsp;')
											{
										?>
												<tr>
													<td colspan="3" class="christmas_proddesB" align="left"><?php echo $desc?></td>
												</tr>
										<?php		
											}
											if ($tot_cnt>0 and ($_REQUEST['req']!=''))
											{
										?>
												<tr>
													<td colspan="3" align="center" class="pagingcontainertd">
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
											while($row_prod = $db->fetch_array($ret_prod))
											{
												$frm_name = uniqid('shelf_');
											?>
													<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
													<input type="hidden" name="fpurpose" value="" />
													<input type="hidden" name="fproduct_id" value="" />
													<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
													<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
													<tr onmouseover="this.className='christmasshelf_1row_hover'" onmouseout="this.className='christmas_1rowtdB'">
												  		<td align="left" valign="middle" class="christmas_1rowtdB">
														
												  	<?php
															if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
															{
													?>
												  				<h1 class="christmas_prodnameB" ><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" class="christmas_prodnamelinkB" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h1>
															
												  	<?php
															}
															if ($shelfData['shelf_showdescription']==1)// Check whether description is to be displayed
															{
													?>	
													  			<h6 class="christmas_proddesB"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6>
													<?php
															}
													?>	
													 	</td>
												  		<td align="center" valign="middle" class="christmas_1rowtdB">
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
														<?php if($comp_active)  {
														dislplayCompareButton($row_prod['product_id']);
														 }?>
												  	</td>
												  	<td align="left" valign="middle" class="christmas_1rowtdB"> 
													
												  		<?php
														if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
														{
															$price_class_arr['ul_class'] 		= 'christmas_specialofferpriceul';
															$price_class_arr['normal_class'] 	= 'christmas_specialofferpricenormal';
															$price_class_arr['strike_class'] 	= 'christmas_specialofferpricestrike';
															$price_class_arr['yousave_class'] 	= 'christmas_specialofferpriceyousave';
															$price_class_arr['discount_class'] 	= 'christmas_specialofferpricediscount';
															echo show_Price($row_prod,$price_class_arr,'shelfcenter_1');
															show_excluding_vat_msg($row_prod,'vat_div_pad');// show excluding VAT msg
															//show_bonus_points_msg($row_prod,'bonus_point_pad'); // Show bonus points
															$pass_arr['main_cls'] 		= 'bonus_point';
															$pass_arr['caption_cls'] 	= 'bonus_point_caption';
															$pass_arr['point_cls'] 		= 'bonus_point_number';
															show_bonus_points_msg_multicolor($row_prod,$pass_arr);
														}
													?>	
															<ul class="christmas_specialofferinfodiv">
																<li class="christmas_specialofferinfodivleft"><?php show_moreinfo($row_prod,'christmas_specialofferinfolink')?></li>
																<li class="christmas_specialofferinfodivright">
																<?php
																	$class_arr 					= array();
																	$class_arr['ADD_TO_CART']	= 'christmasquantity_infolink';
																	$class_arr['PREORDER']			= 'christmasquantity_infolink';
																	$class_arr['ENQUIRE']			= 'christmasquantity_infolink';
																	show_addtocart($row_prod,$class_arr,$frm_name)
																?>
																</li>
															</ul>     
													</td>
												</tr>
													</form>
										<?php
											}
										/*if ($tot_cnt>0 and ($_REQUEST['req']!=''))
											{
										?>
												<tr>
													<td colspan="3" align="center" class="pagingcontainertd">
													<?php 
														$path = '';
														//show_paging($path,$_REQUEST[$pg_variable],$start_arr['counterstart'],$tot_cnt,$prodperpage,$pg_variable,'')
														$query_string .= "";
														paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr); 	
													?>	
													</td>
												</tr>
										<?php
											} */
										?>	
										</table>
									<?php	
									break;
									case '3row': // case of three in a row for christmas  christmas_1rowtableB
									?>
										<table border="0" cellpadding="0" cellspacing="0" class="christmas_specialtable">
									<?php
										if($cur_title)
										{
									?>	
											<tr>
												<td colspan="3" class="christmas_specialoffertopbg" align="left"><?php echo $cur_title?></td>
											</tr>
									<?php
										}
										$desc = trim($shelfData['shelf_description']);
										if($desc!='' and $desc!='&nbsp;')
										{
										?>
												<tr>
													<td colspan="3" class="christmas_proddesB" align="left"><?php echo $desc?></td>
												</tr>
										<?php		
										}
										if ($tot_cnt>0 and ($_REQUEST['req']!=''))
										{
										?>
											<tr>
												<td colspan="3" align="center" class="pagingcontainertd">
												<?php 
													$path = '';
													//show_paging($path,$_REQUEST[$pg_variable],$start_arr['counterstart'],$tot_cnt,$prodperpage,$pg_variable,'')
														$query_string .= "";
														paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr); 	
												?>												</td>
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
												<td class="christmastoptd" valign="top"  onmouseover="this.className='christmasshelf_3row_hover'" onmouseout="this.className='christmastoptd'">
															<ul class="christmas_specialofferul">
															<?php
																if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
																{
															?>
																<li class="christmas_specialoffername"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></li>
																
																<?php
																}
																if ($shelfData['shelf_showimage']==1)// Check whether description is to be displayed
																{
														?>
																<li class="shelfimg"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
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
																
																</li>
															
													<?php
														}
														if ($shelfData['shelf_showdescription']==1)// Check whether description is to be displayed
														{
													?>	
															<li><?php echo stripslashes($row_prod['product_shortdesc'])?></li>
													<?php
														}
													?>			
															</ul>
															<?php //show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points
															$pass_arr['main_cls'] 		= 'bonus_point';
															$pass_arr['caption_cls'] 	= 'bonus_point_caption';
															$pass_arr['point_cls'] 		= 'bonus_point_number';
															show_bonus_points_msg_multicolor($row_prod,$pass_arr);
															?>
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
														
														<td class="christmasbottomtd" valign="bottom">
														<?php if($comp_active)  {
															dislplayCompareButton($prod_arr['product_id']);
														 }?>
															<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $prod_arr['product_id']?>)">
															<input type="hidden" name="fpurpose" value="" />
															<input type="hidden" name="fproduct_id" value="" />
															<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
															<input type="hidden" name="fproduct_url" value="<?php url_product($prod_arr['product_id'],$prod_arr['product_name'])?>" />	
																
																	<?php
																	if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
																	{
																		$price_class_arr['ul_class'] 		= 'christmas_specialofferpriceul';
																		$price_class_arr['normal_class'] 	= 'christmas_specialofferpricenormal';
																		$price_class_arr['strike_class'] 	= 'christmas_specialofferpricestrike';
																		$price_class_arr['yousave_class'] 	= 'christmas_specialofferpriceyousave';
																		$price_class_arr['discount_class'] 	= 'christmas_specialofferpricediscount';
																		echo show_Price($prod_arr,$price_class_arr,'shelfcenter_3');
																		show_excluding_vat_msg($prod_arr,'vat_div_pad');// show excluding VAT msg
																	}
																	?>	         
																	<ul class="christmas_specialofferinfodiv">
																		<li class="christmas_specialofferinfodivleft"><?php show_moreinfo($prod_arr,'christmas_specialofferinfolink')?></li>
																		<li class="christmas_specialofferinfodivright">
																		<?php
																		$class_arr 					= array();
																		$class_arr['ADD_TO_CART']	= 'christmasquantity_infolink';
																		$class_arr['PREORDER']			= 'christmasquantity_infolink';
																		$class_arr['ENQUIRE']			= 'christmasquantity_infolink';
																		show_addtocart($prod_arr,$class_arr,$frm_name);
																	
																	?>
																		</li>
																	</ul>
															</form>														</td>
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
													<td class="christmasbottomtd" valign="bottom">
														
														<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $prod_arr['product_id']?>)">
														<input type="hidden" name="fpurpose" value="" />
														<input type="hidden" name="fproduct_id" value="" />
														<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
														<input type="hidden" name="fproduct_url" value="<?php url_product($prod_arr['product_id'],$prod_arr['product_name'])?>" />	
																<?php
																if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
																{
																	$price_class_arr['ul_class'] 		= 'christmas_specialofferpriceul';
																	$price_class_arr['normal_class'] 	= 'christmas_specialofferpricenormal';
																	$price_class_arr['strike_class'] 	= 'christmas_specialofferpricestrike';
																	$price_class_arr['yousave_class'] 	= 'christmas_specialofferpriceyousave';
																	$price_class_arr['discount_class'] 	= 'christmas_specialofferpricediscount';
																	echo show_Price($prod_arr,$price_class_arr,'shelfcenter_3');
																	show_excluding_vat_msg($prod_arr,'vat_div_pad');// show excluding VAT msg
																}
																?>	         
																<ul class="christmas_specialofferinfodiv">
																	<li class="christmas_specialofferinfodivleft"><?php show_moreinfo($prod_arr,'christmas_specialofferinfolink')?></li>
																	<li class="christmas_specialofferinfodivright">
																	<?php
																	$class_arr 					= array();
																	$class_arr['ADD_TO_CART']	= 'christmasquantity_infolink';
																	$class_arr['PREORDER']			= 'christmasquantity_infolink';
																	$class_arr['ENQUIRE']			= 'christmasquantity_infolink';
																	show_addtocart($prod_arr,$class_arr,$frm_name)
																	?>
																	</li>
																</ul>
														</form>													</td>
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
							elseif($shelfData['shelf_currentstyle']=='new') // case of new year layout
							{
								switch($shelfData['shelf_displaytype'])
								{
									case '1row': // case of one in a row for new year
									case 'dropdown':
									case 'list':
									?>
										<table border="0" cellpadding="0" cellspacing="0" class="newyear_tableB" >
										<?php
										if($cur_title)
										{
										?>
										<tr>
										  <td colspan="3" class="newyear_topbgB" align="left"><?php echo stripslashes($cur_title)?></td>
										</tr>
										<?php
										}
										$desc = trim($shelfData['shelf_description']);
										if($desc!='' and $desc!='&nbsp;')
										{
										?>
												<tr>
													<td colspan="3" class="newyear_proddes" align="left"><?php echo $desc?></td>
												</tr>
										<?php		
										}
										if ($tot_cnt>0 and ($_REQUEST['req']!=''))
										{
										?>
											<tr>
												<td colspan="3" align="center" class="pagingcontainertd">
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
										while($row_prod = $db->fetch_array($ret_prod))
										{
											$frm_name = uniqid('shelf_');
										?>
											<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
											<input type="hidden" name="fpurpose" value="" />
											<input type="hidden" name="fproduct_id" id="fproduct_id" value="" />
											<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
											<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
											<tr onmouseover="this.className='newyearshelf_1row_hover'" onmouseout="this.className='newyear_1rowtd'">
												<td align="left" valign="middle" class="newyear_1rowtd">
												
												<?php
												if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
												{
												?>
												  <h1 class="newyear_prodname" ><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" class="newyear_prodnamelink" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h1>
												 
												<?php
												}
												if($shelfData['shelf_showdescription']==1)// whether desc is to be displayed
												{
												?>	
													<h6 class="newyear_proddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6>
												<?php
												}
											  ?>
											  
											   </td>
												<td align="center" valign="middle" class="newyear_1rowtd">
												<?php
												if($shelfData['shelf_showimage']==1)// whether image is to be displayed
												{
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
												<?php
												}
												?>
													<?php if($comp_active)  {
													dislplayCompareButton($row_prod['product_id']);
													}?>
												</td>
												<td align="left" valign="middle" class="newyear_1rowtd">
												
												<?php
												if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
												{
													$price_class_arr['ul_class'] 		= 'newyear_priceulB';
													$price_class_arr['normal_class'] 	= 'newyear_pricenormalB';
													$price_class_arr['strike_class'] 	= 'newyear_pricestrikeB';
													$price_class_arr['yousave_class'] 	= 'newyear_priceyousaveB';
													$price_class_arr['discount_class'] 	= 'newyear_pricediscountB';
													echo show_Price($row_prod,$price_class_arr,'shelfcenter_1');
													show_excluding_vat_msg($row_prod,'vat_div_pad');// show excluding VAT msg
													//show_bonus_points_msg($row_prod,'bonus_point_pad'); // Show bonus points
													$pass_arr['main_cls'] 		= 'bonus_point';
													$pass_arr['caption_cls'] 	= 'bonus_point_caption';
													$pass_arr['point_cls'] 		= 'bonus_point_number';
													show_bonus_points_msg_multicolor($row_prod,$pass_arr);
													
												}
												?>
												<ul class="newyear_infodivB">
												<li class="newyear_infodivleftB"><?php show_moreinfo($row_prod,'newyear_infolinkB1row')?></li>
												<li class="newyear_infodivrightB">
												<?php
													$class_arr 					= array();
													$class_arr['ADD_TO_CART']	= 'newyear_quantiryinkB1row';
													$class_arr['PREORDER']		= 'newyear_quantiryinkB1row';
													$class_arr['ENQUIRE']		= 'newyear_quantiryinkB1row';
													show_addtocart($row_prod,$class_arr,$frm_name)
												?>
												</li>
												</ul>
												</td>
											</tr>
											</form>
									<?php
										}
									?>	
									  </table>
									<?php	
									break;
									case '3row': // case of three in a row for new year
									?>
										<table border="0" cellpadding="0" cellspacing="0" class="newyear_tableB">
									<?php
									 if($cur_title)
										{
									?>	
										<tr>
											<td colspan="3" class="newyear_topbgB" align="left"><?php echo $cur_title?></td>
										</tr>
									<?php
										}
										$desc = trim($shelfData['shelf_description']);
										if($desc!='' and $desc!='&nbsp;')
										{
										?>
												<tr>
													<td colspan="3" class="newyear_proddes" align="left"><?php echo $desc?></td>
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
												<td class="newyeartoptd" valign="top"  onmouseover="this.className='newyearshelf_3row_hover'" onmouseout="this.className='newyeartoptd'">
															<ul class="newyear_ulB">
															<?php
																if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
																{
															?>
																	<li class="newyear_nameB"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></li>
																
															<?php
																}
																if($shelfData['shelf_showimage']==1)// whether image is to be displayed
																{
															?>		
																	<li class="shelfimg"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
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
																	
																	</li>
															
															<?php
																}
																if($shelfData['shelf_showdescription']==1)// whether desc is to be displayed
																{
															?>	
																	<li><?php echo stripslashes($row_prod['product_shortdesc'])?></li>
															<?php
																}
															?>	
															</ul>
														<?php //show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points
																$pass_arr['main_cls'] 		= 'bonus_point';
																$pass_arr['caption_cls'] 	= 'bonus_point_caption';
																$pass_arr['point_cls'] 		= 'bonus_point_number';
																show_bonus_points_msg_multicolor($row_prod,$pass_arr);
														?>												
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
														<td class="newyearbottomtd" valign="bottom">
														<?php if($comp_active)  { // to display Compare icon
															dislplayCompareButton($prod_arr['product_id']);
														}?>
															<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $prod_arr['product_id']?>)">
															<input type="hidden" name="fpurpose" value="" />
															<input type="hidden" name="fproduct_id" value="" />
															<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
															<input type="hidden" name="fproduct_url" value="<?php url_product($prod_arr['product_id'],$prod_arr['product_name'])?>" />
														<?php
														if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
														{
															$price_class_arr['ul_class'] 		= 'newyear_priceulB';
															$price_class_arr['normal_class'] 	= 'newyear_pricenormalB';
															$price_class_arr['strike_class'] 	= 'newyear_pricestrikeB';
															$price_class_arr['yousave_class'] 	= 'newyear_priceyousaveB';
															$price_class_arr['discount_class'] 	= 'newyear_pricediscountB';
															echo show_Price($prod_arr,$price_class_arr,'shelfcenter_3');
															show_excluding_vat_msg($prod_arr,'vat_div_pad');// show excluding VAT msg
														}
														?>	
															<ul class="newyear_infodivB">
																<li class="newyear_infodivleftB"><?php show_moreinfo($prod_arr,'newyear_infolinkB')?></li>
																<li class="newyear_infodivrightB">
																<?php
																			$class_arr 							= array();
																			$class_arr['ADD_TO_CART']	= 'newyear_quantitylinkBrow';
																			$class_arr['PREORDER']			= 'newyear_quantitylinkBrow';
																			$class_arr['ENQUIRE']			= 'newyear_quantitylinkBrow';
																			show_addtocart($prod_arr,$class_arr,$frm_name)
																		?>
																</li>
															</ul>
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
													<td class="newyearbottomtd" valign="bottom">
														<?php if($comp_active)  { // to display Compare icon
															dislplayCompareButton($prod_arr['product_id']);
														}?>
														<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $prod_arr['product_id']?>)">
														<input type="hidden" name="fpurpose" value="" />
														<input type="hidden" name="fproduct_id" value="" />
														<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
														<input type="hidden" name="fproduct_url" value="<?php url_product($prod_arr['product_id'],$prod_arr['product_name'])?>" />
														<?php
														if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
														{
															$price_class_arr['ul_class'] 		= 'newyear_priceulB';
															$price_class_arr['normal_class'] 	= 'newyear_pricenormalB';
															$price_class_arr['strike_class'] 	= 'newyear_pricestrikeB';
															$price_class_arr['yousave_class'] 	= 'newyear_priceyousaveB';
															$price_class_arr['discount_class'] 	= 'newyear_pricediscountB';
															echo show_Price($prod_arr,$price_class_arr,'shelfcenter_3');
															show_excluding_vat_msg($prod_arr,'vat_div_pad');// show excluding VAT msg
														}
														?>	
															<ul class="newyear_infodivB">
																<li class="newyear_infodivleftB"><?php show_moreinfo($prod_arr,'newyear_infolinkB')?></li>
																<li class="newyear_infodivrightB">
																<?php
																			$class_arr 					= array();
																			$class_arr['ADD_TO_CART']	= 'newyear_infolinkB';
																			$class_arr['PREORDER']		= 'newyear_infolinkB';
																			$class_arr['ENQUIRE']		= 'newyear_infolinkB';
																			show_addtocart($prod_arr,$class_arr,$frm_name)
																		?>
																</li>
															</ul>
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
					else
					{
						removefrom_Display_Settings($shelfData['shelf_id'],'mod_shelf');
					}
				}
			}	
		}
	};	
?>