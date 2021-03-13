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
			$prod_compare_enabled = isProductCompareEnabled();
			?>
			<div class="mid_shlf2_hdr">
				<div class="mid_shlf2_hdr_top"></div>
					<div class="mid_shlf2_hdr_middle"> <?php echo stripslash_normal($Captions_arr['LOGIN_HOME']['LOGIN_HOME_PURCHASE_HEADING']);?>
					</div>
				<div class="mid_shlf2_hdr_bottom"></div>
			</div>
			<?php
			switch($Settings_arr['recentpurchased_prodlisting'])
			{ 
				case '1row':
				?>
				<div class="mid_shlf_con" >
					<?php
					while($row_prod = $db->fetch_array($ret_purchase))
					{
					?>
						<div class="mid_shlf_top"></div>
						<div class="mid_shlf_middle">
						<?php		
						if($show_title==1)// whether title is to be displayed
						{
						?>	
							<div class="mid_shlf_pdt_name"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslash_normal($row_prod['product_name'])?>"><?php echo stripslash_normal($row_prod['product_name'])?></a></div>
						<?php
						}
						?>
						<div class="mid_shlf_mid">
						<div class="mid_shlf_pdt_image">
						
							<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslash_normal($row_prod['product_name'])?>">
							<?php
							// Calling the function to get the image to be shown
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
							<? 
						if($prod_compare_enabled)
						{
						?>
							<div class="mid_shlf_pdt_compare" >
							<?php	dislplayCompareButton($row_prod['product_id']);?>
							</div>
						<?php	
						}
						?>
						</div>
						</div>
						<div class="mid_shlf_pdt_des">
						<?php
							echo stripslash_normal($row_prod['product_shortdesc']);
						$module_name = 'mod_product_reviews';
						if(in_array($module_name,$inlineSiteComponents))
						{
							if($row_prod['product_averagerating']>=0)
							{
							?>
							<div class="mid_shlf_pdt_rate">
							<?php
								display_rating($row_prod['product_averagerating']);
							?>
							</div>
							<?php
							}
						}	
						if($row_prod['product_bulkdiscount_allowed']=='Y')
						{
						?>
							<div class="mid_shlf_pdt_bulk"><img src="<?php url_site_image('bulk-discount.gif')?>" alt="Bulk Discount"/></div>
						<?php
						}
						if($row_prod['product_saleicon_show']==1)
						{
							$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
							if($desc!='')
							{
							?>	
								<div class="mid_shlf_pdt_sale"><?php echo $desc?></div>
							<?php
							}
						}
						if($row_prod['product_newicon_show']==1)
						{
							$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
							if($desc!='')
							{
							?>
								<div class="mid_shlf_pdt_newsale"><?php echo $desc?></div>
							<?php
							}
						}
						?>
						</div>
						<div class="mid_shlf_pdt_price">
						<?php 
						if($row_prod['product_freedelivery']==1)
						{	
						?>
							<div class="mid_shlf_free"></div>
						<?php
						}
						if ($show_price==1)// Check whether description is to be displayed
						{ 
							$price_class_arr['class_type'] 		= 'div';
							$price_class_arr['normal_class'] 	= 'shlf_normalprice';
							$price_class_arr['strike_class'] 	= 'shlf_strikeprice';
							$price_class_arr['yousave_class'] 	= 'shlf_yousaveprice';
							$price_class_arr['discount_class'] 	= 'shlf_discountprice';
							echo show_Price($row_prod,$price_class_arr,'shopbrand_1');
						}
						$frm_name = uniqid('shop_');
						?>	
						<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
						<input type="hidden" name="fpurpose" value="" />
						<input type="hidden" name="fproduct_id" value="" />
						<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
						<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
						<div class="mid_shlf_buy">
						<div class="mid_shlf_info_btn"><?php show_moreinfo($row_prod,'mid_shlf_info_link')?></div>
						<div class="mid_shlf_buy_btn">
						<?php
						$class_arr 					= array();
						$class_arr['ADD_TO_CART']	= 'mid_shlf_buy_link';
						$class_arr['PREORDER']		= 'mid_shlf_buy_link';
						$class_arr['ENQUIRE']		= 'mid_shlf_buy_link';
						
						/* Code for ajax setting starts here */
						$class_arr['BTN_CLS']           = 'normal_shlfB_pdt_buy_btn';												
						//show_addtocart($row_prod,$class_arr,$frm_name)
						show_addtocart_v5_ajax($frm_name,$row_prod,$class_arr);
						/* Code for ajax setting ends here */
						?>
						</div>
						</div>
						</form>       
						</div>
						</div>
						<div class="mid_shlf_bottom"></div>
					<? 
					}
					?>
					</div>
				<?
				break;
				case '2row':
				?>
			   <div class="mid_shlf2_con" >
					<?php
					$max_col = 2;
					$cur_col = 0;
					while($row_prod = $db->fetch_array($ret_purchase))
					{
						$prodcur_arr[] = $row_prod;
						//##############################################################
						// Showing the title, description and image part for the product
						//##############################################################
						if($cur_col == 0)
						{
							echo '<div class="mid_shlf2_con_main">';
						}
						$cur_col ++;
						
						?>
						<div class="mid_shlf2_con_pdt">
						<div class="mid_shlf2_top"></div>
						<div class="mid_shlf2_middle">
						
							<div class="mid_shlf2_pdt_name"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslash_normal($row_prod['product_name'])?>"><?php echo stripslash_normal($row_prod['product_name'])?></a></div>
							<div class="mid_shlf2_pdt_image">
							<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslash_normal($row_prod['product_name'])?>">
							<?php
							// Calling the function to get the image to be shown
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
							</div>
						<?php
						$module_name = 'mod_product_reviews';
						if(in_array($module_name,$inlineSiteComponents))
						{
							if($row_prod['product_averagerating']>=0)
							{
							?>
							<div class="mid_shlf_pdt_rate">
							<?php
								display_rating($row_prod['product_averagerating']);
							?>
							</div>
							<?php
							}
						}
						?>				
						<div class="mid_shlf2_free_con">
						<?php
						if($row_prod['product_freedelivery']==1)
						{	
						?>
							<div class="mid_shlf2_free"></div>
						<?php
						}
						if($row_prod['product_bulkdiscount_allowed']=='Y')
						{
						?>
							<div class="mid_shlf2_pdt_bulk"><img src="<?php url_site_image('bulk-discount.gif')?>" border="0" alt="Bulk Discount" /></div>
						<?php
						}
						?>
						</div>
						<?php
						if($prod_compare_enabled)
						{
						?>
							<div class="mid_shlf2_pdt_compare" >
							<?php	dislplayCompareButton($row_prod['product_id']);?>
							</div>
						<?php	
						}
						?>
							<div class="mid_shlf2_pdt_des">
							<?php echo stripslash_normal($row_prod['product_shortdesc'])?>
							</div>
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
						?>
						
						<div class="mid_shlf2_buy">
						<?php
						$frm_name = uniqid('shopdet_');
						?>	
						<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
						<input type="hidden" name="fpurpose" value="" />
						<input type="hidden" name="fproduct_id" value="" />
						<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
						<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
						<div class="mid_shlf2_info_btn"><?php show_moreinfo($row_prod,'mid_shlf2_info_link')?></div>
						<div class="mid_shlf2_buy_btn">
						<?php
						$class_arr 					= array();
						$class_arr['ADD_TO_CART']	= 'mid_shlf2_buy_link';
						$class_arr['PREORDER']		= 'mid_shlf2_buy_link';
						$class_arr['ENQUIRE']		= 'mid_shlf2_buy_link';
						
						/* Code for ajax setting starts here */
						$class_arr['BTN_CLS']           = 'normal_shlfB_pdt_buy_btn';												
						//show_addtocart($row_prod,$class_arr,$frm_name)
						show_addtocart_v5_ajax($frm_name,$row_prod,$class_arr);
						/* Code for ajax setting ends here */
						?>
						</div>
						</form>
						</div>
						<?php
							?>
							<div class="mid_shlf2_pdt_price">
							<?php
							$price_class_arr['class_type'] 		= 'div';
							$price_class_arr['normal_class'] 	= 'shlf2_normalprice';
							$price_class_arr['strike_class'] 	= 'shlf2_strikeprice';
							$price_class_arr['yousave_class'] 	= 'shlf2_yousaveprice';
							$price_class_arr['discount_class'] 	= 'shlf2_discountprice';
							echo show_Price($row_prod,$price_class_arr,'shopbrand_3');	
							?>
							</div>
						</div>
						<div class="mid_shlf2_bottom"></div>
						</div>
						<?php
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
			<?
			    break;	
			 }	//endof switch					
			
			 }//end Checking

		}
	};	
?>