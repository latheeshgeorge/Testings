<?php
	/*############################################################################
	# Script Name 	: favcategory_showallHtml.php
	# Description 	: Page which holds the display logic for all products under fav category
	# Coded by 		: LSH
	# Created on	: 14-oct-2008
	# Modified by	: LH
	# Modified On	: 
	##########################################################################*/
	class purchaseshowall_Html
	{
		// Defining function to show the shelf details
		function Show_purchasedproductsall($ret_purchase)
		{
		global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$ecom_themename,$Settings_arr,$ecom_themeid,$default_layout,$inlineSiteComponents,$customer_id,$ids,$inlineSiteComponents;
		$Captions_arr['PROD_DETAILS'] 	= getCaptions('PROD_DETAILS');
		if($db->num_rows($ret_purchase)>0)
			{
			
			switch($Settings_arr['recentpurchased_prodlisting'])
			{ 
				case '1row':
				?>
				<table border="0" cellpadding="0" cellspacing="0" class="shelfAtable">
					<tr>
												<td colspan="3" class="pro_de_shelfBheader" align="left"><?php echo $Captions_arr['LOGIN_HOME']['LOGIN_HOME_PURCHASE_HEADING'];?></td>
					</tr>
				
			  <?
		//$prodcur_arr = array();
			while($row_prod = $db->fetch_array($ret_purchase))
				{
					$prodcur_arr[] = $row_prod;
					//##############################################################
					// Showing the title, description and image part for the product
					//##############################################################
			?>
			  <tr onmouseover="this.className='normalshelf_hover'" onmouseout="this.className='shelfBtabletd'">
													<td align="left" valign="middle" class="shelfBtabletd">
												
														
																<h1 class="shelfBprodname"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h1>
														
														
																<h6 class="shelfBproddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6>
													<?php
														if($row_prod['product_saleicon_show']==1)
														{
															$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
															if($desc!='')
															{
																?>	
																<div class="mid_shlf2_pdt_sale"><?php echo $desc?></div>
																<?php
															}
														}
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
														$module_name = 'mod_product_reviews';
														if(in_array($module_name,$inlineSiteComponents))
														{
															if($row_prod['product_averagerating']>=0)
															{
														?>
															<div class="mid_shlf2_free_star">
															<?php
																display_rating($row_prod['product_averagerating'],0,'star-green.png','star-white.png');
																?>
															</div>
														<?php
															}
														}	
													?>
													</td>
													<td align="center" valign="middle" class="shelfBtabletd">
														
															<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
															<?php
																$pass_type = get_default_imagetype('midshelf');

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
													
														<?php if(isProductCompareEnabled())  {
															dislplayCompareButton($row_prod['product_id']);
														}?>
													</td>
													<td align="left" valign="middle" class="shelfBtabletd">
													<?php
															$price_class_arr['ul_class'] 		= 'shelfBul';
															$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
															$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
															$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
															$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
															echo show_Price($row_prod,$price_class_arr,'shelfcenter_1');
															show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
															//show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points
															/*$pass_arr['main_cls'] 		= 'bonus_point';
															$pass_arr['caption_cls'] 	= 'bonus_point_caption';
															$pass_arr['point_cls'] 		= 'bonus_point_number';
															show_bonus_points_msg_multicolor($row_prod,$pass_arr);*/
															if($row_prod['product_bonuspoints'] > 0)
															{
																echo '<div class="prod_list_bonusB">
																		<span class="bonus_point_number_a">
																			<span>'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_BONUSPOINTSA']).'</span>
																		</span>
																		<span class="bonus_point_caption_b">'.$row_prod['product_bonuspoints'].'</span>
																		<span class="bonus_point_number_c">
																			<span>'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_BONUSPOINTSB']).'</span></span>
																		</div>';
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
														<?php
														if($row_prod['product_bulkdiscount_allowed']=='Y' || $row_prod['product_freedelivery']==1)
														{
															?>
															<div class="mid_shlf2_free_div">
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
															</div>
															<?php
														}
														?>
													</td>
											</tr>
				 <?
			     }//end of for loop
	            ?>
				</table>
				<?
				break;
				case '3row':
				?>
			<table border="0" cellpadding="0" cellspacing="0" class="shelfAtable">
			
											<tr>
												<td colspan="3" class="pro_de_shelfBheader" align="left"><?php echo $Captions_arr['LOGIN_HOME']['LOGIN_HOME_PURCHASE_HEADING'];?></td>
											</tr>
										
										<?php
											$max_col = 3;
											$cur_col = 0;
											$prodcur_arr = array();
											while($row_prod = $db->fetch_array($ret_purchase))
											{
												$prodcur_arr[] = $row_prod;
												//##############################################################
												// Showing the title, description and image part for the product
												//##############################################################
										?>
												<td class="shelfAtabletd" align="left" valign="top" onmouseover="this.className='normalshelf_hover'" onmouseout="this.className='shelfAtabletd'">
												
													<ul class="shelfBul">
														
																<li><h1 class="shelfAprodname"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h1></li>
															
														
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
																<li class="shelfimg">
																	<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
																	<?php
																		// Calling the function to get the type of image to shown for current 
																		$pass_type = get_default_imagetype('fav_prod');
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
																	
																
																	</a>	</li>
																	<?php if(isProductCompareEnabled())  {
																		dislplayCompareButton($row_prod['product_id']);
																	}?>
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
																
															<li><h6 class="shelfAproddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6></li>
													</ul>
														<?php //show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points
														
														/*$pass_arr['main_cls'] 		= 'bonus_point';
														$pass_arr['caption_cls'] 	= 'bonus_point_caption';
														$pass_arr['point_cls'] 		= 'bonus_point_number';
														show_bonus_points_msg_multicolor($row_prod,$pass_arr);*/
														if($row_prod['product_bonuspoints'] > 0)
														{
															echo '<div class="prod_list_bonusB">
																	<span class="bonus_point_number_a">
																		<span>'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_BONUSPOINTSA']).'</span>
																	</span>
																	<span class="bonus_point_caption_b">'.$row_prod['product_bonuspoints'].'</span>
																	<span class="bonus_point_number_c">
																		<span>'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_BONUSPOINTSB']).'</span></span>
																	</div>';
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
														$module_name = 'mod_product_reviews';
														if(in_array($module_name,$inlineSiteComponents))
														{
															if($row_prod['product_averagerating']>=0)
															{
														?>
															<div class="mid_shlf2_free_star">
															<?php
																display_rating($row_prod['product_averagerating'],0,'star-green.png','star-white.png');
																?>
															</div>
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
														$frm_name = uniqid('mafavhome_');
													?>
														<td class="shelfAtabletdA">
															
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
												 if($cur_col>0)
												echo "<td colspan='".($max_col-$cur_col)."'>&nbsp;</td></tr><tr>";
												$cur_tempcol = $cur_col = 0;
												//##############################################################
												// Done to handle the case of showing the qty, add to cart and more info links
												// in case if total product is less than the max allower per row.
												//##############################################################
												foreach($prodcur_arr as $k=>$prod_arr)
												{
													$frm_name = uniqid('mafavhome_');
												?>
													
													<td class="shelfAtabletdA">
														
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
												 if($cur_col>0)
												echo "<td colspan='".($max_col-$cur_tempcol)."'>&nbsp;</td></tr>";
											}
											else
												echo "</tr>";
											$prodcur_arr = array();
											?>

										</table>
			<?
			    break;	
			 }	//endof switch					
			
			 }//end Checking

		}
	};	
?>