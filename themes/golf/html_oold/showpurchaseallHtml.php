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
		global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$ecom_themename,$Settings_arr,$ecom_themeid,$default_layout,$inlineSiteComponents,$customer_id,$ids;

		if($db->num_rows($ret_purchase)>0)
			{
			
			switch($Settings_arr['recentpurchased_prodlisting'])
			{ 
				case '1row':
				?>
				<table border="0" cellpadding="0" cellspacing="0" class="shelfAtable">
					<tr>
												<td colspan="3" class="pro_de_shelfBheader" align="left"><h1><?php echo $Captions_arr['LOGIN_HOME']['LOGIN_HOME_PURCHASE_HEADING'];?></h1></td>
					</tr>
				
			  <?
		     //$prodcur_arr = array();
			  while($row_prod = $db->fetch_array($ret_purchase))
				{
				    $prodcur_arr[] = $row_prod;
									?>						
									<tr >
									<td align="left" valign="middle"class="newshelfBtabletd" onmouseover="this.className='newnormalshelf_hover'" onmouseout="this.className='newshelfBtabletd'">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" class="newshelfBtableinner">
									<tr>
									<td class="newshelfBtableinnertd" valign="top" align="left">
									
									<h2 class="newshelfBprodname"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h2>
									
									<h6 class="newshelfBproddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6>
								
									</td>
									
									<td class="newshelfBtableinnertd" valign="middle" align="center">
									
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
									<td class="newshelfBtableinnertd" valign="middle" align="left">
									
									<?php
											
												$price_class_arr['ul_class'] 		= 'shelfBul';
												$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
												$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
												$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
												$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
												echo show_Price($row_prod,$price_class_arr,'shelfcenter_1');
												show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
												show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points
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
																			
									</table></td>
									</tr>
									<? }//end of for loop
	            ?>
				</table>
				<?
				break;
				case '3row':
				?>
			<table border="0" cellpadding="0" cellspacing="0" class="shelfAtable">
			
											<tr>
												<td colspan="3" class="pro_de_shelfBheader" align="left"><h1><?php echo $Captions_arr['LOGIN_HOME']['LOGIN_HOME_PURCHASE_HEADING'];?></h1></td>
											</tr>
										
										<?php
											$max_col = 3;
											$cur_col = 0;
											$prodcur_arr = array();
											while($row_prod = $db->fetch_array($ret_purchase))
											{
										
											$prodcur_arr[] = $row_prod;
										$ids_in .= ",".$row_prod['product_id'];
												//##############################################################
												// Showing the title, description and image part for the product
												//##############################################################
										if($cur_col == 0)
										{
										  echo "<tr>";
										}
										$cur_col ++;

										?>
                               <td class="newshelfAtabletd" onmouseover="this.className='newshelfAtabletd_hover'" onmouseout="this.className='newshelfAtabletd'" align="left" valign="top">												
													<ul class="shelfAul">
														
																<li><h2 class="shelfAprodname"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h2></li>
															
														
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
																	?></a>
																	
																</li>
																
														
															<li><h6 class="shelfAproddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6></li>
													</ul>
														<?php show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points?>
											<?php
												
													
														$frm_name = uniqid('mafavhome_');
													?>
															<?php if($comp_active)  {
															dislplayCompareButton($row_prod['product_id']);
															}?>
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
											<?php
														if($cur_col>=$max_col)
														{
														$cur_col =0;
														echo "</tr>";
														
														}

														// done to handle the case of breaking to new linel
												}
											// If in case total product is less than the max allowed per row then handle that situation
								                	if($cur_col<$max_col)
													{
													  if($cur_col!=0)
														{ 
															$cur_td=0;
															echo "<td colspan='".($max_col-$cur_col)."'>&nbsp;</td>";
															echo "</tr>";
														} 
													}
											?>

										</table>
			<?
			    break;	
			 }	//endof switch					
			
			 }//end Checking

		}
	};	
?>