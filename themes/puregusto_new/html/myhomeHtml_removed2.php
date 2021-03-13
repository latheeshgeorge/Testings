
														

<!--<td class="shelfAtabletd" align="left" valign="top" onmouseover="this.className='normalshelf_hover'" onmouseout="this.className='shelfAtabletd'">
											<ul class="shelfBul">
											<li><h1 class="shelfAprodname"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h1>
											</li>
												<li>
												<?php
													$price_class_arr['ul_class'] 		= 'shelfpriceul';
													$price_class_arr['normal_class'] 	= 'shelfAnormalprice';
													$price_class_arr['strike_class'] 	= 'shelfAstrikeprice';
													$price_class_arr['yousave_class'] 	= 'shelfAyousaveprice';
													$price_class_arr['discount_class'] 	= 'shelfAdiscountprice';
													echo show_Price($row_prod,$price_class_arr,'shelfcenter_3');
													show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
												?>	
											 </li>
											 <?php
											if($row_prod['product_saleicon_show']==1)
											{
												$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
												if($desc!='')
												{
												?>	
													<li><div class="mid_shlf2_pdt_sale"><?php echo $desc?></div></li>
												<?php
												}
											}
											?>
											<li class="bestsellerimg">
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
											</li>
											<?php if($prod_compare_enabled)  {
													dislplayCompareButton($row_prod['product_id']);
													}?>
											<?php
										
										?>
											<li><h6 class="shelfAproddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6>
											<?php 
												$pass_arr['main_cls'] 		= 'bonus_point';
												$pass_arr['caption_cls'] 	= 'bonus_point_caption';
												$pass_arr['point_cls'] 		= 'bonus_point_number';
												show_bonus_points_msg_multicolor($row_prod,$pass_arr);
											?>
											</li>
											<?php
											if($row_prod['product_bulkdiscount_allowed']=='Y' || $row_prod['product_freedelivery']==1)
												{
													?>
													<li><div class="mid_shlf2_free_div">
													<?php
													if($row_prod['product_bulkdiscount_allowed']=='Y')
													{
														?>
														<img src="<?php url_site_image('bulk-dis.gif')?>" alt="Bulk Discount"/>
														<?php
													}
													if($row_prod['product_freedelivery']==1)
													{	
														?>
														<img src="<?php url_site_image('free-deli.gif')?>" alt="Free Delivery"/>
														<?php
													}
													?>
													</div></li>
													<?php
												}
										?>
											</ul>
											
											<?php
										$module_name = 'mod_product_reviews';
										if(in_array($module_name,$inlineSiteComponents))
										{
											if($row_prod['product_averagerating']>=0)
											{
											?>
											<div class="mid_shlf2_free_star">
											<?php
											display_rating($row_prod['product_averagerating']);
											?>
											</div>
											<?php
											}
										}	
										/*if($row_prod['product_saleicon_show']==1)
										{
											$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
											if($desc!='')
											{
											?>	
											<div class="mid_shlf2_pdt_sale"><?php echo $desc?></div>
											<?php
											}
										}*/
										if($row_prod['product_newicon_show']==1)
										{
											$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
											if($desc!='')
											{
											?>
											<div class="mid_shlf2_pdt_newsale"><?php echo $desc?></div>
											<?php
											}
										}
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
													$frm_name = uniqid('myhome_');
												?>
													<td class="shelfAtabletdA">
														
														<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls">
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
																$class_arr['PREORDER']			= 'quantity_infolink';
																$class_arr['ENQUIRE']			= 'quantity_infolink';
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
										if ($cur_col<$max_col and $cur_col>0)
										{
											echo "<td colspan='".($max_col-$cur_col)."'>&nbsp;</td></tr><tr>";
											$cur_tempcol = $cur_col = 0;
											//##############################################################
											// Done to handle the case of showing the qty, add to cart and more info links
											// in case if total product is less than the max allower per row.
											//##############################################################
											foreach($prodcur_arr as $k=>$prod_arr)
											{
												$frm_name = uniqid('myhome_');
											?>
												
												<td class="shelfAtabletdA">
													
													<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls">
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
										
									</td>
									</tr>-->