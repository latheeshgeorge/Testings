<?php
/*############################################################################
	# Script Name 	: featuredHtml.php
	# Description 	: Page which holds the display logic for featured product
	# Coded by 		: Sny
	# Created on	: 28-Dec-2007
	# Modified by	: Sny
	# Modified On	: 22-Jan-2008
	##########################################################################*/
	class featured_Html
	{
		// Defining function to show the featured property
		function Show_Featured($title,$ret_featured)
		{
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$Captions_arr;
			$row_featured = $db->fetch_array($ret_featured);
		?>
			<form method="post" action="<?php url_link('manage_products.html')?>" name='frm_featured' id="frm_featured" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_featured['product_id']?>)">
			<input type="hidden" name="fpurpose" value="" />
			<input type="hidden" name="fproduct_id" value="" />
			<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
			<input type="hidden" name="fproduct_url" value="<?php url_product($row_featured['product_id'],$row_featured['product_name'])?>" />
			<div class="featured_div">
             <?php
				if($title)
				{
			   ?>
			  <div class="featured_header" > <?php echo $title?></div>
			  <?
			    }
			   ?>
			   <?php 
				if ($row_featured['featured_showimage']==1)
				{
			   ?>	
              <div class="featured_img"><a href="<?php url_product($row_featured['product_id'],$row_featured['product_name'],-1)?>" title="<?php echo stripslashes($row_featured['product_name'])?>">
					<?php
						// Find out which sized image is to be displayed as featured product image
						switch($row_featured['featured_showimagetype'])
						{
							case 'Thumb':
								$fld_name = 'image_thumbpath';
							break;
							case 'Medium':
								$fld_name = 'image_thumbcategorypath';
							break;
							case 'Big':
								$fld_name = 'image_bigpath';
							break;
							case 'Extra':
								$fld_name = 'image_extralargepath';
							break;
						};
						// Calling the function to get the image to be shown
						$img_arr = get_imagelist('prod',$row_featured['product_id'],$fld_name,0,0,1);
						if(count($img_arr))
						{
							show_image(url_root_image($img_arr[0][$fld_name],1),$row_featured['product_name'],$row_featured['product_name']);
						}
						else
						{
							// calling the function to get the no image
							$no_img = get_noimage('prod'); 
							if ($no_img)
							{
								show_image($no_img,$row_featured['product_name'],$row_featured['product_name']);
							}	
						}	
					?>
					</a></div>
               <?
			    }  
			  if ($row_featured['featured_showtitle']==1)
				{
			  ?>
			  <div class="featured_name"><a href="<?php url_product($row_featured['product_id'],$row_featured['product_name'],-1)?>" title="<?php echo stripslashes($row_featured['product_name'])?>"><?php echo stripslashes($row_featured['product_name'])?></a></div>
			  <?
			   }
			// Check whether selected to show either desc or the price 	
			if ($row_featured['featured_showshortdescription']==1)
			{
			?>
			  <div class="featured_des">
			   <?php
			            $desc = ($row_featured['featured_desc'])?$row_featured['featured_desc']:$row_featured['product_shortdesc'];
								if ($desc)
								{
					?>
									<?php echo stripslashes($desc)?>
					<?php
								}
					?>		
			  </div>
			<?
			 }
			?>	
              <div class="featured_button">
			  <div class="more_div">
               <?php show_moreinfo($row_featured,'button_yellow')?>
			  </div>
			   <?php
							$class_arr 					= array();
							$class_arr['ADD_TO_CART']	= 'button_yellow';
							$class_arr['PREORDER']		= 'button_yellow';
							$class_arr['ENQUIRE']		= 'button_yellow';
							$class_div                  = 'button_div';
							show_addtocart($row_featured,$class_arr,'frm_featured','','','',$class_div);
			   ?>	
              </div>
			  <?php
				// Check whether selected to show either desc or the price 	
				if ($row_featured['featured_showprice']==1)
				{
				?>
              

			  <?
			  if ($row_featured['featured_showprice']==1)// Check whether price is to be displayed
				{
				?>
				<div class="featured_price">
				  <ul class="featured_price_ul">
				  <?
						$price_class_arr['normal_class'] 	= 'featured_normal';
						$price_class_arr['strike_class'] 	= 'featured_strike';
						$price_class_arr['yousave_class'] 	= 'yousaveprice';
						$price_class_arr['discount_class'] 	= 'discountprice';
						echo show_Price($row_featured,$price_class_arr,'featured');
						
					
					?>
					</ul>
				  </div>
			  <?
			  	 show_bonus_points_msg($row_featured,'bonus_point'); // Show bonus points
				 show_excluding_vat_msg($row_featured,'vat_div');// show excluding VAT msg
			     }	
			    }
			  ?>
  </div>
		</form>
		<?php	
		}
	};	
?>