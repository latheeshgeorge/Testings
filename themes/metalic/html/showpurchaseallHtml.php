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
					<div class="shelf_1row">
					<div class="shelf_1row_header"><?php echo $Captions_arr['LOGIN_HOME']['LOGIN_HOME_PURCHASE_HEADING'];?></div>
				
			  <?
			  $pass_type = get_default_imagetype('midshelf');
		//$prodcur_arr = array();
			while($row_prod = $db->fetch_array($ret_purchase))
				{
					$prodcur_arr[] = $row_prod;
					//##############################################################
					// Showing the title, description and image part for the product
					//##############################################################
			?>
			  <div class="shelf_main">
											
											<div class="shelf_1row_img"> 
											
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
																						?></a>
											<?php
											if(isProductCompareEnabled())  {
															dislplayCompareButton($row_prod['product_id']);}?>
											</div>	
											
											<div class="shelf_1row_name"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></div>
											
											<div class="shelf_1row_content"><?php echo stripslashes($row_prod['product_shortdesc'])?> </div>
											<div class="shelf_1row_price">
											
											 <?php
																					if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
																					{
																						$price_class_arr['ul_class'] 		= 'shelf_price_ul';
																						$price_class_arr['normal_class'] 	= 'shelf_normal';
																						$price_class_arr['strike_class'] 	= 'shelf_strike';
																						$price_class_arr['yousave_class'] 	= 'shelf_normal';
																						$price_class_arr['discount_class'] 	= 'shelf_normal';
																						echo show_Price($row_prod,$price_class_arr,'shelfcenter_1');
																						show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
																					}
											
											$frm_name = uniqid('shelf_');
												?>	
											<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls">
											<input type="hidden" name="fpurpose" value="" />
											<input type="hidden" name="fproduct_id" value="" />
											<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
																		
											  <ul class="shelf_button">
												<li class="shelf_button_li">
												 <div class="more_div">
													<?php show_moreinfo($row_prod,'button_yellow')?>
												</div>
												 <?php
													$class_arr 					= array();
													$class_arr['ADD_TO_CART']	= 'button_yellow';
													$class_arr['PREORDER']		= 'button_yellow';
													$class_arr['ENQUIRE']		= 'button_yellow';
													$class_div                  = 'button_div';
													show_addtocart($row_prod,$class_arr,$frm_name,'','','',$class_div)
												  ?>
												</li>
											  </ul>
											</form>
										    <? 	show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points
											?>
											</div>
											
											</div>
			
				 <?
			     }//end of for loop
	            ?>
			</div>
				<?
				break;
			
			 }	//endof switch					
			
			 }//end Checking

		}
	};	
?>