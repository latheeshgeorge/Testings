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
			<div class="featured_pdt_otr">
			<div class="featured_pdt_l">
			<?php
			if ($row_featured['featured_showtitle']==1)
			{
			?>
			<div class="featured_pdt_name"><a href="<?php url_product($row_featured['product_id'],$row_featured['product_name'],-1)?>" title="<?php echo stripslash_normal($row_featured['product_name'])?>"><?php echo stripslash_normal($row_featured['product_name'])?></a></div>
			<?php
			}
			?>
			<?php
			if ($row_featured['featured_showshortdescription']==1)
			{
			$desc = ($row_featured['featured_desc'])?$row_featured['featured_desc']:$row_featured['product_shortdesc'];
			if ($desc)
			{
			?>
			<div class="featured_pdt_des"><?php echo stripslashes($desc)?></div>
			<?php
			}
			}
			?>
			<?php
			if ($row_featured['featured_showprice']==1)
			{
			?>
			<div class="featured_pdt_price">
			<?php
			$price_class_arr['class_type'] 		= 'div';
			$price_class_arr['normal_class'] 	= 'normal_shlfA_pdt_priceA';
			$price_class_arr['strike_class'] 	= 'normal_shlfA_pdt_priceB';
			$price_class_arr['yousave_class'] 	= 'normal_shlfA_pdt_priceC';
			$price_class_arr['discount_class'] 	= 'normal_shlfA_pdt_priceC';
			echo show_Price($row_featured,$price_class_arr,'featured');
			?>	
			</div>
			<?php
			}
			?>   
			<div class="featured_pdt_buy"><a href="<?php url_product($row_featured['product_id'],$row_featured['product_name'],-1)?>" class="more_info_a">More Info</a></div> 
			</div> 
			<?php 
			if ($row_featured['featured_showimage']==1)
			{
			?>	
			<div class="featured_pdt_r">
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
			</div>
			
		<?php	
		}
	};	
?>
