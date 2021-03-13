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
                    // Component Title
                    $HTML_title = $HTML_comptitle = $HTML_image = $HTML_desc = $HTML_price = '';                   
		?>          <form method="post" action="<?php url_link('manage_products.html')?>" name='frm_featured' id="frm_featured" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_featured['product_id']?>)">
                    <input type="hidden" name="fpurpose" value="" />
                    <input type="hidden" name="fproduct_id" value="" />
                    <input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
                    <input type="hidden" name="fproduct_url" value="<?php url_product($row_featured['product_id'],$row_featured['product_name'])?>" />
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="ft_table">
					<?php
					if($title!='')
					{
					?>
					<tr>
					<td colspan="2" class="ft_td"><div class="ft_div"><?php echo $title ?></div></td>
					</tr>
					<?php
					}
					?>
					<tr>
					<td colspan="2" class="ft_td_name" ><a href="<?php url_product($row_featured['product_id'],$row_featured['product_name'],-1) ?>" title="<?php stripslash_normal($row_featured['product_name']) ?>" class="linkbold_y"><?php echo stripslash_normal($row_featured['product_name']) ?></a></td>
					</tr>
					<?php
					$HTML_image = '
									<a href="'.url_product($row_featured['product_id'],$row_featured['product_name'],1).'" title="'.stripslash_normal($row_featured['product_name']).'">';
					// Find out which sized image is to be displayed as featured product image
					/*switch($row_featured['featured_showimagetype'])
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
					*/
					$fld_name = 'image_iconpath';
					// Calling the function to get the image to be shown
					$img_arr = get_imagelist('prod',$row_featured['product_id'],$fld_name,0,0,1);
					if(count($img_arr))
					{
					$HTML_image .= show_image(url_root_image($img_arr[0][$fld_name],1),$row_featured['product_name'],$row_featured['product_name'],'','',1);
					}
					else
					{
					// calling the function to get the no image
					$no_img = get_noimage('prod'); 
					if ($no_img)
					{
							$HTML_image .=  show_image($no_img,$row_featured['product_name'],$row_featured['product_name'],'','',1);
					}       
					}       
					$HTML_image .=  '</a>'; 
					?>
					<tr>
					<td  class="ft_td_img">
					<?php echo $HTML_image ;?>
					</td>
					<td class="ft_td_price">
					<?php
					$price_arr =  show_Price($row_featured,array(),'featured',false,4);
					//print_r($price_arr);
					if($price_arr['base_price'])
					$HTML_price = '<div class="pricea">'.$price_arr['base_price'].'</div>';
					if($price_arr['discounted_price'])
					$HTML_price .= '<div class="priceb">'.$price_arr['discounted_price'].'</div>';
					if($price_arr['disc'])
					$HTML_price .= '<div class="pricec">'.$price_arr['disc']." ".$Captions_arr['COMMON']['FEAT_OFF'].'</div>';
					echo $HTML_price;
					?>
					</td>
					</tr>
					</table>       
                   </form>
		<?php	
		}
	};	
?>
