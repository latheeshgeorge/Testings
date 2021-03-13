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
			$comp_active = isProductCompareEnabled();
		?>
			<form method="post" action="<?php url_link('manage_products.html')?>" name='frm_featured' id="frm_featured" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_featured['product_id']?>)">
			<input type="hidden" name="fpurpose" value="" />
			<input type="hidden" name="fproduct_id" value="" />
			<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
			<input type="hidden" name="fproduct_url" value="<?php url_product($row_featured['product_id'],$row_featured['product_name'])?>" />
			
			<div class="shelf_main_con" >
			<?php 
			if($title)
			{
			?>
			<div class="shelf_top"><?php echo $title?></div>
			<?
			}
			?>
			<div class="shelf_mid">
			<div class="shlf_main_last">
			<div class="shlf_pdt_img_outr">
			<div class="shlf_pdt_img">
			<?php
			if ($row_featured['featured_showimage']==1)
			{
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
			?>		
			<a href="<?php url_product($row_featured['product_id'],$row_featured['product_name'],-1)?>" title="<?php echo stripslashes($row_featured['product_name'])?>">
			<?php
			// Calling the function to get the type of image to shown for current 
			//$pass_type = get_default_imagetype('midshelf');
			// Calling the function to get the image to be shown
				$img_arr = get_imagelist('prod',$row_featured['product_id'],$fld_name,0,0,1);
			if(count($img_arr))
			{
				show_image(url_root_image($img_arr[0][$fld_name],1),$row_featured['product_name'],$row_featured['product_name']);
			}
			else
			{
			// calling the function to get the default image
			$no_img = get_noimage('prod',$pass_type); 
			if ($no_img)
			{
				show_image($no_img,$row_featured['product_name'],$row_featured['product_name']);
			}	
			}	
			?>
			</a>
			<?php
			}
			?>	
			</div>
			<div class="shlf_pdt_compare" >
			<?php if($comp_active)  {
			dislplayCompareButton($row_featured['product_id']);
			}?>
			</div>
			</div>
			<div class="shlf_pdt_txt">
			<ul class="shlf_pdt_ul">
			<?php
			if ($row_featured['featured_showtitle']==1)
			{
			?>
			<li class="shlf_pdt_name" ><h3><a href="<?php url_product($row_featured['product_id'],$row_featured['product_name'],-1)?>" title="<?php echo stripslashes($row_featured['product_name'])?>"><?php echo stripslashes($row_featured['product_name'])?></a></h3></li>
			
			<?php
			}
			if ($row_featured['featured_showshortdescription']==1)// Check whether description is to be displayed
			{
				$desc = ($row_featured['featured_desc'])?$row_featured['featured_desc']:$row_featured['product_shortdesc'];
				if ($desc)
				{
			?>		
				<li class="shlf_pdt_des"><h6>	<?php echo stripslashes($desc)?></h6></li>
			<?php
				}
			}
			?>			
			</ul>
			<?php
			if ($row_featured['featured_showprice']==1)// Check whether price is to be displayed
			{ 
			$price_class_arr['ul_class'] 			= 'shelf_price_ul';
			$price_class_arr['normal_class'] 		= 'shelfAnormalprice';
			$price_class_arr['strike_class'] 		= 'shelfAstrikeprice';
			$price_class_arr['yousave_class'] 	= 'shelfAyousaveprice';
			$price_class_arr['discount_class'] 	= 'shelfAdiscountprice';
			echo show_Price($row_featured,$price_class_arr,'featured');
			show_excluding_vat_msg($row_featured,'vat_div');// show excluding VAT msg
			show_bonus_points_msg($row_featured,'bonus_point'); // Show bonus points
			}
			?>
			<div class="infodiv">
			<div class="infodivleft"><?php show_moreinfo($row_featured,'infolink')?></div>
			<div class="infodivright">
			<?php
			$class_arr 							= array();
			$class_arr['ADD_TO_CART']	= 'quantity_infolink';
			$class_arr['PREORDER']			= 'quantity_infolink';
			$class_arr['ENQUIRE']			= 'quantity_infolink';
			show_addtocart($row_featured,$class_arr,'frm_featured');
			?>
			</div>
			</div>
			</div>
			</div>
			</div>
			<div class="shelf_bottom"></div>	
			</div>
			</form>
		<?php	
		}
	};	
?>