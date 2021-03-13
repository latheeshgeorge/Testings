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
        $totcnt = $db->num_rows($ret_purchase);
		if($db->num_rows($ret_purchase)>0)
			{
			switch($Settings_arr['recentpurchased_prodlisting'])
			{ 
				case '1row':
				?>
					<div class="mid_shelfB_name"><?php echo $Captions_arr['LOGIN_HOME']['LOGIN_HOME_PURCHASE_HEADING'];?></div>
				
			  <?
		//$prodcur_arr = array();
			while($row_prod = $db->fetch_array($ret_purchase))
				{
					$prodcur_arr[] = $row_prod;
					//##############################################################
					// Showing the title, description and image part for the product
					//##############################################################
			?>
			  <div class="mid_shelfB">
												<table width="100%" border="0" cellpadding="0" cellspacing="0"  class="mid_shelfB_table">
												
												<tr>
													<td class="mid_shelfB_top_lf">&nbsp;</td>
													<td class="mid_shelfB_top_mid"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="prod_infolink"><?php echo stripslashes($row_prod['product_name'])?></a></td>
													<td class="mid_shelfB_top_rt">&nbsp;</td>
												</tr>
												<tr>
														<td colspan="3" class="mid_shelfB_mid">
														<div class="shelfBimg">
																
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
															<?php if($comp_active)  {
																dislplayCompareButton($row_prod['product_id']);
															}?>
														</div> 
														<div class="shelfB_cnts">
																	 
																	<h6 class="shelfBproddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6>
																<ul class="shelfBul">
																<?php
																	$price_class_arr['ul_class'] 		= 'shelfBul';
																	$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
																	$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
																	$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
																	$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
																	echo show_Price($row_prod,$price_class_arr,'shelfcenter_1');
																	show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
																	show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points
																	?>
																
																</ul>	
													   </div>
													  </td>
												</tr>
												<tr>
												<td class="mid_shelfB_btm_lf">&nbsp;</td>
												<td class="mid_shelfB_btm_mid">
													<?php
													$frm_name = uniqid('shelf_');
													?>	
														<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
															<input type="hidden" name="fpurpose" value="" />
															<input type="hidden" name="fproduct_id" value="" />
															<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
															<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />														<div class="infodiv">
															<div class="infodivleft">		<?php show_moreinfo($row_prod,'infolink')?>	</div>
															<div class="infodivright"><?php
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
												<td class="mid_shelfB_btm_rt">&nbsp;</td>
												</tr>
												</table>
												
										</div>
			
				 <?
			     }//end of for loop
	            ?>
				</table>
				<?
				break;
				case '2row':
				?>
					<div class="mid_shelfB_name"><?php echo $Captions_arr['LOGIN_HOME']['LOGIN_HOME_PURCHASE_HEADING'];?></div>
			<table width="100%" border="0" cellpadding="0" cellspacing="0"  class="mid_shelfA">
				<tr>
			
										
										
										<?php
											$max_col = 2;
											$cur_col = 0;
											$prodcur_arr = array();
											while($row_prod = $db->fetch_array($ret_purchase))
												{
												$cur_col++;
												$prodcur_arr[] = $row_prod;
												//##############################################################
												// Showing the title, description and image part for the product
												//##############################################################
												if($cur_col%2==0)
												{
												$cls ='mid_shelfA_right'; 
												}
												else
												$cls ='mid_shelfA_left'; 
												?>
												<td class="<?=$cls?>">
												<table width="100%" border="0" cellpadding="0" cellspacing="0"  class="mid_shelfA_table">
												<tr>
												<td class="mid_shelfA_top_lf">&nbsp;</td>
												<td class="mid_shelfA_top_mid"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="prod_infolink"><?php echo stripslashes($row_prod['product_name'])?></a></td>
												<td class="mid_shelfA_top_rt">&nbsp;</td>
												</tr>
												<tr>
												<td colspan="3" class="mid_shelfA_mid" align="center">
												<ul class="shelfAul">
												<li class="shelfAimg">
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
																				<?php if(isProductCompareEnabled())  {
												dislplayCompareButton($row_prod['product_id']);
												}?>
												
												</li>
												<li><h6 class="shelfAproddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6></li>                     
												<?php
												$price_class_arr['ul_class'] 		= 'shelfBul';
												$price_class_arr['normal_class'] 	= 'shelfAnormalprice';
												$price_class_arr['strike_class'] 	= 'shelfAstrikeprice';
												$price_class_arr['yousave_class'] 	= 'shelfAyousaveprice';
												$price_class_arr['discount_class'] 	= 'shelfAdiscountprice';
												echo show_Price($row_prod,$price_class_arr,'shelfcenter_3');
												show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
												?>	
												</ul>	
												</td>
												</tr>
												<tr>
												<td class="mid_shelfA_btm_lf">&nbsp;</td>
												<?php $frm_name = uniqid('mafavhome_'); ?>
												<td class="mid_shelfA_btm_mid">
												
												<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
												<input type="hidden" name="fpurpose" value="" />
												<input type="hidden" name="fproduct_id" value="" />
												<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
												<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
												<div class="infodiv_shlfA">
												<div class="infodivleft_shlfA"><?php show_moreinfo($row_prod,'infolink')?></div>
												<div class="infodivright_shlfA">
												<?php
												$class_arr 					= array();
												$class_arr['ADD_TO_CART']	= 'quantity_infolink';
												$class_arr['PREORDER']		= 'quantity_infolink';
												$class_arr['ENQUIRE']		= 'quantity_infolink';
												show_addtocart($row_prod,$class_arr,$frm_name)
												?></div>
												</div></form></td>
												<td class="mid_shelfA_btm_rt">&nbsp;</td>
												</tr>
												</table>
												</td>
												<?php
												if($totcnt==$cur_col)
												{ 
												echo "</tr>";
												}
												else
												{
												if($cur_col%2==0)
												{
												echo "</tr>";
												//##############################################################
												// Showing the more info and add to cart links after each row in 
												// case of breaking to new row while looping
												//##############################################################
												echo "<tr>";
												}
												}

												}
											// If in case total product is less than the max allowed per row then handle that situation
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