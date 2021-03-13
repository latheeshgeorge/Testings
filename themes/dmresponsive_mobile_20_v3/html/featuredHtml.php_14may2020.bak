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
                    global $from_home;
                    $row_featured = $db->fetch_array($ret_featured);
                    $sql_prod_offer = "SELECT product_newicon_show,product_saleicon_show FROM products WHERE product_id=".$row_featured['product_id']." AND sites_site_id=$ecom_siteid LIMIT 1";
                    $ret_prod_offer = $db->query($sql_prod_offer);
                    $row_prod_offer = $db->fetch_array($ret_prod_offer);
                    // Component Title
					$HTML_title = $HTML_comptitle = $HTML_image = $HTML_desc = $HTML_price = '';
					if($title!='')
					{
						$HTML_comptitle = $title;
					}
					// Title
					if ($row_featured['featured_showtitle']==1)
					{
						$HTML_title ='<a href="'.url_product($row_featured['product_id'],$row_featured['product_name'],1).'" title="'.stripslash_normal($row_featured['product_name']).'">'.stripslash_normal($row_featured['product_name']).'</a>';
					}
					// Image
					if ($row_featured['featured_showimage']==1)
					{
						$HTML_image = '<a href="'.url_product($row_featured['product_id'],$row_featured['product_name'],1).'" title="'.stripslash_normal($row_featured['product_name']).'">';
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
					}
					// Short Description
					if ($row_featured['featured_showshortdescription']==1)
					{
						$desc = ($row_featured['featured_desc'])?$row_featured['featured_desc']:$row_featured['product_shortdesc'];
						if ($desc)
						{
							$HTML_desc = ''.stripslashes($desc).'';
						}
					}
					if ($row_featured['featured_showprice']==1)
					{
						/*$price_class_arr['class_type']          = 'div';
						$price_class_arr['normal_class']        = 'featured_priceB';
						$price_class_arr['strike_class']        = 'featured_priceA';
						$price_class_arr['yousave_class']       = 'featured_priceC';
						$price_class_arr['discount_class']      = 'featured_priceC';*/
						//$HTML_price = show_Price($row_featured,$price_class_arr,'featured');
						$price_arr =  show_Price($row_featured,array(),'featured',false,4);
						if($price_arr['base_price'])
							$HTML_price = '<div class="featured_buy_l">'.$price_arr['base_price'].'</div>';
					}
					$frm_name='frm_featured';
                    // Component Title
                   // $HTML_title = $HTML_comptitle = $HTML_image = $HTML_desc = $HTML_price = '';       
                    if($from_home==true)
					{            
					?>
					     
					<div class="col-md col-sm featured" data-aos="fade-up" >					
					<p class="head-title-black"> <?php echo $HTML_comptitle; ?></p>
					<div class="row">

					<div class=" col-md-3 img-wrap" ><?php echo $HTML_image;?></div>
					<div class="col-md-9">  <h2 class="heading"><?php echo $HTML_title ; ?></h2>
					<p><?php echo $HTML_desc;?></p>
					<?php
															$price_arr =  show_Price($row_featured,$price_class_arr,'cat_detail_1',false,5);
															$was_price = str_replace('+ VAT','',$price_arr['prince_without_captions']['base_price']);
															$save_price = str_replace('+ VAT','',$price_arr['prince_without_captions']['yousave_price']);
															if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
																{
																	$price_class_arr['ul_class'] 		= 'price-avl';
																	$price_class_arr['li_class'] 		= 'price';
																	$price_class_arr['normal_class'] 	= 'price';
																	$price_class_arr['strike_class'] 	= 'price_strike';
																	$price_class_arr['yousave_class'] 	= 'price_yousave';
																	$price_class_arr['discount_class'] 	= 'price_offer';
																	
																	//echo show_Price($row_prod,$price_class_arr,'shelfcenter_1');
																  
																  }
																  $disc =  $price_arr['prince_without_captions']['discounted_price'];
																  $base =  $price_arr['prince_without_captions']['base_price'];
															
																	$curprice_tax_cap = curprice_tax($price_arr,$row_featured);
														   ?>
															<p class="price-details">
																<?php
																if($disc!='')
																{
																if($was_price!='')
																{
																?>
																<p><span class="price-strike">Was <?php echo $was_price ?>  </span><span class="save-price">(Save <?php echo $save_price;?> )</span></p>
																<?php
																}
																}
																?>
																<span class="price"><?php
															if($disc!='')
																	$price_d = $disc;
																	else
																	$price_d = $base;
															?> <?php 
															if($price_d!='')
															echo $price_d.$curprice_tax_cap; ?>
															</p>

					<div class="addwrap">
					<form method="post" action="<?php url_link('manage_products.html')?>" name='frm_featured' id="frm_featured" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_featured['product_id']?>)">
																	<input type="hidden" name="fpurpose" value="" />
																	<input type="hidden" name="fproduct_id" value="" />
																	<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
																	<input type="hidden" name="fproduct_url" value="<?php url_product($row_featured['product_id'],$row_featured['product_name'])?>" />
													<?php			$class_arr['ADD_TO_CART']       = 'btn btn-outline-secondary addbt';
																	$class_arr['PREORDER']          = 'input-group-addon';
																	$class_arr['ENQUIRE']           = 'input-group-addon';
																	$class_arr['QTY_DIV']           = '';
																	$class_arr['QTY']               = 'form-control qty_txt';
																	$class_arr['BTN_CLS']     = 'btn btn-outline-secondary addbt';
																	echo show_addtocart_responsive($frm_name,$row_featured,$class_arr,1,'shelf',false,false,'new_v2');?>
																	
																	<a class="btn btn-outline-secondary detailbt" href="<?php url_product($row_featured['product_id'],$row_featured['product_name'],-1)?>" title="<?php echo stripslashes($row_featured['product_name'])?>">Details</a>
																																		</form>
					</div>



					</div>
					</div>
</form>

					</div>
					

					<?php	
					}
		}
	};	
?>
