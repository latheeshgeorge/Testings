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
                    global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$Captions_arr,$ecom_allpricewithtax;
                    $row_featured = $db->fetch_array($ret_featured);
                    $sql_prod_offer = "SELECT product_newicon_show,product_saleicon_show FROM products WHERE product_id=".$row_featured['product_id']." AND sites_site_id=$ecom_siteid LIMIT 1";
                    $ret_prod_offer = $db->query($sql_prod_offer);
                    $row_prod_offer = $db->fetch_array($ret_prod_offer);
                    // Component Title
                    $HTML_title = $HTML_comptitle = $HTML_image = $HTML_desc = $HTML_price = '';                   
		?>          <form method="post" action="<?php url_link('manage_products.html')?>" name='frm_featured' id="frm_featured" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_featured['product_id']?>)">
                    <input type="hidden" name="fpurpose" value="" />
                    <input type="hidden" name="fproduct_id" value="" />
                    <input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
                    <input type="hidden" name="fproduct_url" value="<?php url_product($row_featured['product_id'],$row_featured['product_name'])?>" />
					<a href="<?php url_product($row_featured['product_id'],$row_featured['product_name'],-1)?>" title="<?php echo stripslashes($row_featured['product_name'])?>">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="ft_table1">
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
					if($row_featured['product_newicon_show']==1)
												{
								$HTML_image = "<div class=\"shlf_table_2row_offer\">cvbcvb<img src=\"".url_site_image('new.png')."\" /></div>";
								    			}
												if($row_featured['product_saleicon_show']==1)
												{
								$HTML_image = "<div class=\"shlf_table_2row_offer\">cvbvcb<img src=\"".url_site_image('sale.png')."\" /></div>";
								 			    }
					$HTML_image .= '
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
					//echo "img mode - ".IMG_MODE;echo "<br>";
					$fld_mode	=	IMG_MODE;
					$fld_size	=	IMG_SIZE;
					//$fld_mode	=	'image_extralargepath'; 
					//$fld_mode	=	'image_bigpath'; 
					// Calling the function to get the image to be shown
					$img_arr = get_imagelist('prod',$row_featured['product_id'],$fld_mode,0,0,1);
					if(count($img_arr))
					{
						$imgPath		=	url_root_image($img_arr[0][$fld_mode],1);
						//echo $imgPath;echo "<br>";
						$imgProperty	=	image_property($imgPath);
						//echo "<pre>";print_r($imgProperty);
						if($imgProperty['width'] > 0 && $imgProperty['height'] > 0 )
						{
							$newWidth	=	ceil($imgProperty['width']/$fld_size);//echo $newWidth;echo "<br>";
							$newHeight	=	ceil($imgProperty['height']/$fld_size);//echo $newHeight;echo "<br>";
							$HTML_image .= show_image_mobile($imgPath,$row_featured['product_name'],$row_featured['product_name'],'','',1,$newWidth,$newHeight);
						}
						else
						{
							$HTML_image .= show_image($imgPath,$row_featured['product_name'],$row_featured['product_name'],'','',1);
						}
					}
					else
					{
					// calling the function to get the no image
					$no_img = get_noimage('prod'); 
					if ($no_img)
					{
						$imgProperty	=	image_property($no_img);
						
						if($imgProperty['width'] > 0 && $imgProperty['height'] > 0 )
						{
							$newWidth	=	$imgProperty['width']/$fld_size;
							$newHeight	=	$imgProperty['height']/$fld_size;
							$HTML_image .= show_image_mobile($no_img,$row_featured['product_name'],$row_featured['product_name'],'','',1,$newWidth,$newHeight);
						}
						else
						{
							$HTML_image .=  show_image($no_img,$row_featured['product_name'],$row_featured['product_name'],'','',1);
						}
					}       
					}       
					$HTML_image .=  '</a>'; 
					?>
					<tr>
					<td  class="ft_td_img">
						<?php
						if($row_prod_offer['product_newicon_show']==1)
										{
				?>							<div class="prod_feat_new_container"><div class="prod_feat_new_img"></div></div>
				<?php 					}
										if($row_prod_offer['product_saleicon_show']==1)
										{
				?>							<div class="prod_feat_new_container"><div class="prod_feat_sale_img"></div></div>
				<?php 					}
						?>
					<?php echo $HTML_image ;
					$desc = ($row_featured['featured_desc'])?$row_featured['featured_desc']:$row_featured['product_shortdesc'];

					?>
					</td>
					<td class="ft_td_price" valign="top">
					<div class="ft_div_desc">
					<?php echo $desc;?></div>
					<?php
					//$ecom_allpricewithtax	=	1;
					//$price_arr =  show_Price($row_featured,array(),'featured',false,6);
					/*echo "<pre>";print_r($price_arr);
				
					echo "</pre>";*/
					
					$price_class_arr['ul_class'] 		= 'shelfBul_three_column';
					$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
					$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
					$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
					$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
					//echo show_Price($row_featured,$price_class_arr,'featured');
					
					
					/*if($price_arr['disc_percent'])
						$HTML_price .= '<div class="row2_price_b">'.$price_arr['disc_percent']."% ".$Captions_arr['COMMON']['FEAT_OFF'].'</div>';
					elseif($price_arr['yousave_price'])
						$HTML_price .= '<div class="row2_price_b">'.print_price($price_arr['yousave_price']).' '.$Captions_arr['COMMON']['FEAT_OFF'].'</div>';
					
					if($price_arr['base_price'])
						$HTML_price = '<div class="row2_price_base">'.$Captions_arr['COMMON']['LIST_PRICE']." ".print_price($price_arr['base_price']).'</div>';		
					if($price_arr['discounted_price'])
						$HTML_price .= '<div class="row2_price_a">'.$Captions_arr['COMMON']['LIST_PRICE']." ".print_price($price_arr['discounted_price']).'</div>';	
					echo $HTML_price;*/
					?>
					</td>
					</tr>
					</table>       
                   </a>
                   </form>
		<?php	
		}
	};	
?>