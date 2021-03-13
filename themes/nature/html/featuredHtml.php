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
			<div class="fetrd_con">
			<div class="fetrd_top"></div>
			<div class="fetrd_middle">
			<div class="fetrdpdt_top"></div>
			<div class="fetrdpdt_middle">
			<div class="fetrdpdt_conts"> 
			<?php
			if ($row_featured['featured_showtitle']==1)
			{
			?>
			<div class="fetrdpdt_name"><a href="<?php url_product($row_featured['product_id'],$row_featured['product_name'],-1)?>" title="<?php echo stripslash_normal($row_featured['product_name'])?>"><?php echo stripslash_normal($row_featured['product_name'])?></a></div>
			<?php
			}
			?>
			<?php 
			if ($row_featured['featured_showimage']==1)
			{
			?>	
				<div class="fetrdpdt_image">
				<a href="<?php url_product($row_featured['product_id'],$row_featured['product_name'],-1)?>" title="<?php echo stripslash_normal($row_featured['product_name'])?>">
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
				</a>
				</div>
			<?php
			}
			?>
			<div class="fetrdpdt_des_con">
			<?php
			if ($row_featured['featured_showshortdescription']==1)
			{
				$desc = ($row_featured['featured_desc'])?$row_featured['featured_desc']:$row_featured['product_shortdesc'];
				if ($desc)
				{
			?>
					<div class="fetrdpdt_des"><?php echo stripslashes($desc)?></div>
			<?php
				}
			}
			?>
			<div class="fetrdpdt_buy"><a href="<?php url_product($row_featured['product_id'],$row_featured['product_name'],-1)?>" class="fetr_buy">Buy Now</a></div>
			<?php
			if ($row_featured['featured_showprice']==1)
			{
			?>
				<div class="fetrdpdt_price">
				<?php
					$price_class_arr['class_type'] 		= 'div';
					$price_class_arr['normal_class'] 	= 'fetrdpdt_normalprice';
					$price_class_arr['strike_class'] 	= 'fetrdpdt_strikeprice';
					$price_class_arr['yousave_class'] 	= 'fetrdpdt_yousaveprice';
					$price_class_arr['discount_class'] 	= 'fetrdpdt_discountprice';
					echo show_Price($row_featured,$price_class_arr,'featured');
				?>	
				</div>
			<?php
			}
			?>
			</div>
			</div>  
			<?php
			if($title)
			{
			?>
			<div class="fetrdpdt_hdr"><?php echo stripslash_normal($title)?></div>    
			<?php
			}
			?>
			</div>
			<div class="fetrdpdt_bottom"></div>
			</div>
			<div class="fetrd_bottom"></div>
			</div>
		</form>
		<?php	
		}
	};	
?>