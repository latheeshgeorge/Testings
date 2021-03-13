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
			<div class="mid_fet_pdt">
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="mid_fet_pdt_top_table" >
 <?php
	if($title)
	{
			?>
  <tr>
    <td class="mid_fet_pdt_top_lf">&nbsp;</td>
    <td class="mid_fet_pdt_top_mid"><?php echo $title?></td>
    <td class="mid_fet_pdt_top_rt">&nbsp;</td>
  </tr>
  <? }?>
  <tr>
    <td colspan="3" class="mid_fet_pdt_mid">
    <?php
	if ($row_featured['featured_showtitle']==1)
	{
	?>
    <div class="feat_pdt_name">
    <h2 class="featuredprodname"><a href="<?php url_product($row_featured['product_id'],$row_featured['product_name'],-1)?>" title="<?php echo stripslashes($row_featured['product_name'])?>"><?php echo stripslashes($row_featured['product_name'])?></a></h2></div>
    <?php
	}
	?>
	<?php
	// Check whether selected to show either desc or the price 	
	if ($row_featured['featured_showshortdescription']==1 or $row_featured['featured_showprice']==1)
	{
	?>	
	<div class="feat_cnts">
    <?php
			if ($row_featured['featured_showshortdescription']==1)// Check whether description is to be displayed
			{
			$desc = ($row_featured['featured_desc'])?$row_featured['featured_desc']:$row_featured['product_shortdesc'];
			if ($desc)
			{
			?>
			<h6 class="featuredproddes"><?php echo stripslashes($desc)?></h6>
			<?php
			}
			}
			if ($row_featured['featured_showprice']==1)// Check whether price is to be displayed
			{
			
			$price_class_arr['normal_class'] 	= 'featnormalprice';
			$price_class_arr['strike_class'] 	= 'featstrikeprice';
			$price_class_arr['yousave_class'] 	= 'featyousaveprice';
			$price_class_arr['discount_class'] 	= 'discountprice';
			echo show_Price($row_featured,$price_class_arr,'featured');
			show_excluding_vat_msg($row_featured,'vat_div');// show excluding VAT msg
			show_bonus_points_msg($row_featured,'bonus_point'); // Show bonus points
		    }
		  ?>
        </div>
	<?
	 }

	?>	
	<?php 
	if ($row_featured['featured_showimage']==1)
	{
	?>	
	 <div class="feat_image">
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
					</a> </div>
	
	<?php
	}
	?>
                        
</td>
</tr>
  <tr>
    <td class="mid_fet_pdt_btm_lf">&nbsp;</td>
    <td class="mid_fet_pdt_btm_mid">
	<div class="infodiv">
					<div class="infodivleft"><?php show_moreinfo($row_featured,'infolink')?></div>
						<div class="infodivright">
							<?php
							$class_arr 					= array();
							$class_arr['ADD_TO_CART']	= 'quantity_infolink';
							$class_arr['PREORDER']		= 'quantity_infolink';
							$class_arr['ENQUIRE']		= 'quantity_infolink';
							show_addtocart($row_featured,$class_arr,'frm_featured');
						?>	
					  </div>
			  </div>  </td>
    <td class="mid_fet_pdt_btm_rt">&nbsp;</td>
  </tr>
</table>
</div>
</form>
		<?php	
		}
	};	
?>