<?php
/*############################################################################
	# Script Name 	: featuredHtml.php
	# Description 	: Page which holds the display logic for featured product
	# Coded by 		: Sny
	# Created on	: 28-Dec-2007
	# Modified by	: Sny
	# Modified On	: 22-Jan-2008
	##########################################################################*/
	class categ_group_Html
	{
		// Defining function to show the featured property
		function Show_Categ_group($title,$ret_featured)
		{
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$Captions_arr;
			$row_featured = $db->fetch_array($ret_featured);
		?>
			<form method="post" action="<?php url_link('manage_products.html')?>" name='frm_featured' id="frm_featured" class="frm_cls">
			<input type="hidden" name="fpurpose" value="" />
			<input type="hidden" name="fproduct_id" value="" />
			<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="featuredtable">
			<?php
				if($title)
				{
			?>
				<tr>
					<td <?php echo ($row_featured['featured_showimage']==1)?'colspan="2"':''?> align="left" valign="top" class="featuredheader"><?php echo $title?></td>
				</tr>
			<?php
				}
				if ($row_featured['featured_showtitle']==1)
				{
			?>
					<tr>
						<td <?php echo ($row_featured['featured_showimage']==1)?'colspan="2"':''?> align="left" valign="top"><h1 class="featuredprodname"><a href="<?php url_product($row_featured['product_id'],$row_featured['product_name'],-1)?>" title="<?php echo stripslashes($row_featured['product_name'])?>"><?php echo stripslashes($row_featured['product_name'])?></a></h1></td>
					</tr>
			<?php
				}
			?>		
			<tr>
			<?php 
				if ($row_featured['featured_showimage']==1)
				{
			?>	
					<td align="left" valign="middle" class="featuredtabletd">
					<a href="<?php url_product($row_featured['product_id'],$row_featured['product_name'],-1)?>" title="<?php echo stripslashes($row_featured['product_name'])?>">
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
					</a>					</td>
			<?php
				}
			?>
					<td align="left" valign="top" class="featuredtabletd">
					<?php
					// Check whether selected to show either desc or the price 	
					if ($row_featured['featured_showshortdescription']==1 or $row_featured['featured_showprice']==1)
					{
					?>
						<ul class="featured">
					<?php
							if ($row_featured['featured_showshortdescription']==1)// Check whether description is to be displayed
							{
								$desc = ($row_featured['featured_desc'])?$row_featured['featured_desc']:$row_featured['product_shortdesc'];
								if ($desc)
								{
					?>
									<li>
										<h6 class="featuredproddes"><?php echo stripslashes($desc)?></h6>
									</li>
					<?php
								}
							}
							if ($row_featured['featured_showprice']==1)// Check whether price is to be displayed
							{
								$price_class_arr['normal_class'] 	= 'normalprice';
								$price_class_arr['strike_class'] 	= 'strikeprice';
								$price_class_arr['yousave_class'] 	= 'yousaveprice';
								$price_class_arr['discount_class'] 	= 'discountprice';
								echo show_Price($row_featured,$price_class_arr,'featured');
								//show_excluding_vat_msg($row_featured,'vat_div');// show excluding VAT msg
								//show_bonus_points_msg($row_featured,'bonus_point'); // Show bonus points
							}	
					?>
						</ul>        
			<?php
					}
					?>
					<table border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td>
						<?php show_moreinfo($row_featured,'infolink')?>						</td>
						<td>
						<?php
							$class_arr 					= array();
							$class_arr['ADD_TO_CART']	= 'quantity_infolink';
							$class_arr['PREORDER']		= 'quantity_infolink';
							$class_arr['ENQUIRE']		= 'quantity_infolink';
							show_addtocart($row_featured,$class_arr,'frm_featured');
						?>						</td>
					  </tr>
					</table>				</td>
			</tr>
			</table>
		</form>
		<?php	
		}
	};	
?>