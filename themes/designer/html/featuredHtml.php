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
                    if($title!='')
                    {
                        $HTML_comptitle = '<div class="featured_top">'.$title.'</div>';
                    }
                    // Title
                    if ($row_featured['featured_showtitle']==1)
                    {
                        $HTML_title ='<div class="featured_name"><a href="'.url_product($row_featured['product_id'],$row_featured['product_name'],1).'" title="'.stripslash_normal($row_featured['product_name']).'">'.stripslash_normal($row_featured['product_name']).'</a></div>';
                    }
                    // Image
                    if ($row_featured['featured_showimage']==1)
                    {
                        $HTML_image = '<div class="featured_img">
                                            <a href="'.url_product($row_featured['product_id'],$row_featured['product_name'],1).'" title="'.stripslash_normal($row_featured['product_name']).'">';
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
                            $HTML_image .=  '</a></div>';
                    }
                    // Short Description
                    if ($row_featured['featured_showshortdescription']==1)
                    {
                        $desc = ($row_featured['featured_desc'])?$row_featured['featured_desc']:$row_featured['product_shortdesc'];
                        if ($desc)
                        {
                            $HTML_desc = '<div class="featured_des">'.stripslashes($desc).'</div>';
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
		?>
                    <form method="post" action="<?php url_link('manage_products.html')?>" name='frm_featured' id="frm_featured" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_featured['product_id']?>)">
                    <input type="hidden" name="fpurpose" value="" />
                    <input type="hidden" name="fproduct_id" value="" />
                    <input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
                    <input type="hidden" name="fproduct_url" value="<?php url_product($row_featured['product_id'],$row_featured['product_name'])?>" />
                        <div class="featured_con">   
                        <?php echo $HTML_comptitle; ?>
                            <div class="featured_bottom">
							<?=$HTML_image;?>    
                            <?php
                                 if($price_arr['disc'])
									{
									?>
                                        <div class="featured_off">
                                        <div class="featured_off_in">
                                        <?php
                                        echo $price_arr['disc']." ".$Captions_arr['COMMON']['FEAT_OFF'];
                                        ?></div>
                                        </div>
									<?
									}
									else
									{
									?>
                                    	<div class="featured_off">
                                        <div class="featured_off_in">
                                        
                                        </div>
                                        </div>
                                    <?php	
									}
									?>
                                <div class="featured_cont">
                                <?php echo $HTML_title;?>
                                <?php echo $HTML_desc;?>
                                    <div class="featured_buy">
                                        <div class="featured_buy_otr">
                   							 <?=$HTML_price;?>
                                            <div class="featured_buy_r">
                                            <div class="featured_buy_btn"><?php
																	$class_arr 					= array();
																	$class_arr['ADD_TO_CART']	= 'quantity_infolink';
																	$class_arr['PREORDER']		= 'quantity_infolink';
																	$class_arr['ENQUIRE']		= 'quantity_infolink';
																	show_addtocart($row_featured,$class_arr,$frm_name,false,'','','',1)
																?></div>
                                            <div class="featured_buy_more">
                                            <?php show_moreinfo($row_featured,'featinfolink')?>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                    </form>
		<?php	
		}
	};	
?>