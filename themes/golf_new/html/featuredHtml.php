<?php
/*############################################################################
	# Script Name 	: featuredHtml.php
	# Description 		: Page which holds the display logic for featured product
	# Coded by 		: Sny
	# Created on		: 28-Dec-2007
	# Modified by		: Sny
	# Modified On		: 07-Oct-2009
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
		<div class="featuredheader"><?php echo $title?></div> <div class="featured_offer"></div>
	<?php
		}
	?>
	<div class="featuredinner"> 
		<table width="100%" border="0" cellspacing="5" cellpadding="0">
		<tr>
		<td colspan="3" rowspan="3" align="left" valign="top">
		<?php 
			if ($row_featured['featured_showimage']==1)
			{
		?>
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
			</a>
		<?php
			}
		?>
		</td>
		<td width="56%" align="left" valign="top" class="featuredprodname">
		<?php
		if ($row_featured['featured_showtitle']==1)
		{
		?>
			<a href="<?php url_product($row_featured['product_id'],$row_featured['product_name'],-1)?>" title="<?php echo stripslashes($row_featured['product_name'])?>" class="featuredprodnamelink"><?php echo stripslashes($row_featured['product_name'])?></a>
		<?php
		}
		?>
		</td>
		</tr>
		<tr>
		<td align="left" valign="top">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td width="4%" align="left" valign="top" class="featuredbuytd"><img src="<?php url_site_image('fet-but-left.gif')?>"/></td>
			<td width="46%" align="left" valign="middle" class="featuredbuytd">
			<?php
			if ($row_featured['featured_showprice']==1)// Check whether price is to be displayed
			{
			?>
				<span class="featuredprice">
			<?php
				$price_arr =  show_Price($row_featured,array(),'featured',false,3);
				if($price_arr['discounted_price'])
					echo $price_arr['discounted_price'];
				else
					echo $price_arr['base_price']
			?>
				</span>
			<?php		
			}	
			?>
			</td>
			<td width="46%" align="right" valign="middle" class="featuredbuytd">
			<a href="<?php url_product($row_featured['product_id'],$row_featured['product_name'],-1)?>" title="Buy"><img src="<?php url_site_image('fet-buy.gif')?>" border="0" alt="buy"></a></td>
			<td width="4%" align="right" valign="top" class="featuredbuytd"><img src="<?php url_site_image('fet-but-right.gif')?>"/></td>
			</tr>
			</table>
		</td>
		</tr>
		<?php
			if ($row_featured['featured_showshortdescription']==1)// Check whether description is to be displayed
			{
				$desc = ($row_featured['featured_desc'])?$row_featured['featured_desc']:$row_featured['product_shortdesc'];
				if ($desc)
				{
		?>
					<tr>
						<td align="left" valign="top" class="featuredproddes"><?php echo stripslashes($desc)?></td>
					</tr>
		<?php
				}
			}
			?>
		</table>
	</div>
	</div>
		</form>
		<?php	
		}
	};	
?>