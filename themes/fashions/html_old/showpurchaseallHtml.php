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
				case 'nor':
				?>
				<div class="pro_linkprod_div"><?php echo $Captions_arr['LOGIN_HOME']['LOGIN_HOME_PURCHASE_HEADING'];?></div>
				
			  <?
		//$prodcur_arr = array();
			while($row_prod = $db->fetch_array($ret_purchase))
				{
					$prodcur_arr[] = $row_prod;
					//##############################################################
					// Showing the title, description and image part for the product
					//##############################################################
			?>
			 
			 <div class="search_main">
                    
                           					 <div class="search_image"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php // Calling the function to get the type of image to shown for current 
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
																	<?php
														$prefix = "<div class='compare_li'>";
															$suffix = "</div>";
														 if(isProductCompareEnabled())  {
															dislplayCompareButton($row_prod['product_id'],$prefix,$suffix);
														}?>	
														</div>	
                           								
														 <div class="search_content">
																<div class="search_name" ><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="categoreyname_headerlink_myhome"><?php echo stripslashes($row_prod['product_name'])?></a></div>
																 <div class="search_des"><?php  echo stripslashes($row_prod['product_shortdesc'])?></div>
														         <div class="search_price">
																 <UL>
																 
																  <?php $price_class_arr['ul_class'] 		= '';
																$price_class_arr['normal_class'] 	= 'search_normal_price';
																$price_class_arr['strike_class'] 	= 'search_price_strike';
																$price_class_arr['yousave_class'] 	= 'search_price_offer';
																$price_class_arr['discount_class'] 	= 'search_price_dis';
																echo show_Price($row_prod,$price_class_arr,'shelfcenter_1');
																?>
																</UL>
																  </div>
														          </div>
														 <div class="search_buy" align="right">
															
																<label><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
																</a></label>
															
														<?php
															
																$frm_name = uniqid('mafavhome_');
														?>	
																<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls">
																<input type="hidden" name="fpurpose" value="" />
																<input type="hidden" name="fproduct_id" value="" />
																<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
																
																	<label><?php show_moreinfo($row_prod,'product_info')?></label>
																	
																	<?php
																	$prefix = "<DIV class='product_list_button_list'><label>"; 
																	$suffix = "</label> </DIV>";
																		$class_arr 					= array();
																		$class_arr['ADD_TO_CART']	= 'product_list_button';
																		$class_arr['PREORDER']		= 'product_list_button';
																		$class_arr['ENQUIRE']		= 'product_list_button';
																		show_addtocart($row_prod,$class_arr,$frm_name,false,$prefix,$suffix)
																	?>
																</form>
																</div>
															</div>	
				 <?
			     }//end of for loop
	            ?>
				<?
				break;
				
			 }	//endof switch					
			
			 }//end Checking

		}
	};	
?>