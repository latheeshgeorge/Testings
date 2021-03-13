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
        $tot_cnt = $db->num_rows($ret_purchase);
		if($db->num_rows($ret_purchase)>0)
			{
			
			switch($Settings_arr['recentpurchased_prodlisting'])
			{ 
				case '1row':
				?>
					<div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a></div>
						<div class="shelf_main_con" >
								<div class="shelf_top"><?php echo $Captions_arr['LOGIN_HOME']['LOGIN_HOME_PURCHASE_HEADING'];?></div>
								<div class="shelf_mid">
			  <?
		//$prodcur_arr = array();
		$cur=1;
			while($row_prod = $db->fetch_array($ret_purchase))
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
													<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
													<?php
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
															$no_img = get_noimage('prod',$pass_type); 
															if ($no_img)
															{
																show_image($no_img,$row_prod['product_name'],$row_prod['product_name']);
															}	
														}	
													?>
													</a>
												</div>
												<div class="shlf_pdt_compare" >
												<?php if($comp_active)  {
															dislplayCompareButton($row_prod['product_id']);
														}?>
												</div>
												</div>
												<div class="shlf_pdt_txt">
												<ul class="shlf_pdt_ul">
												<li class="shlf_pdt_name" ><h3><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h3></li>
												<li class="shlf_pdt_des"><h6>	<?php echo stripslashes($row_prod['product_shortdesc'])?></h6></li>
												</ul>
												<?php
													$price_class_arr['ul_class'] 		= 'shelf_price_ul';
													$price_class_arr['normal_class'] 	= 'shelfAnormalprice';
													$price_class_arr['strike_class'] 	= 'shelfAstrikeprice';
													$price_class_arr['yousave_class'] 	= 'shelfAyousaveprice';
													$price_class_arr['discount_class'] 	= 'shelfAdiscountprice';
													echo show_Price($row_prod,$price_class_arr,'shelfcenter_1');
													show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
													show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points
													$frm_name = uniqid('best_');
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
											}//end of for loop
	            ?>
				</div>
										<div class="shelf_bottom"></div>	
										</div>			
				<?
				break;
				case '3row':
				?>
							<div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a></div>

										<div class="shelfA_main_con" > 
										<div class="shelfA_top"><?php echo $Captions_arr['LOGIN_HOME']['LOGIN_HOME_PURCHASE_HEADING'];?></div>
										<div class="shelfA_mid">	
										<?php
											$max_col = 3;
											$cur_col = 0;
											$prodcur_arr = array();
											$cur_tot_cnt = $db->num_rows($ret_purchase);
											$cur = 1;
											while($row_prod = $db->fetch_array($ret_purchase))
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
													<div class="shlfA_pdt_img">
													<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
													<?php
														$pass_type = get_default_imagetype('midshelf');
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
															<li class="shlfA_pdt_name" ><h3><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>"><?php echo stripslashes($row_prod['product_name'])?></a></h3></li>
																<li class="shlfA_pdt_des" ><h6><?php echo stripslashes($row_prod['product_shortdesc'])?></h6></li>
														<li class='shlfA_pdt_bonus'><h6><?php show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points?></h6></li>
														</ul>

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
															$frm_name = uniqid('best_');
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
			<?
			    break;	
			 }	//endof switch					
			
			 }//end Checking

		}
	};	
?>