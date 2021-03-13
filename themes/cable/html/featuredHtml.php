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
                        $HTML_comptitle = '<div class="featured_header">'.$title.'</div>';
                    }
                    // Title
                    if ($row_featured['featured_showtitle']==1)
                    {
                        $HTML_title ='<div class="featured_name"><a href="'.url_product($row_featured['product_id'],$row_featured['product_name'],1).'" title="'.stripslash_normal($row_featured['product_name']).'">'.stripslash_normal($row_featured['product_name']).'</a></div>';
                    }
                    // Image
                    if ($row_featured['featured_showimage']==1)
                    {
                        $HTML_image = '<div class="featured_image">
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
                        $price_class_arr['class_type']          = 'div';
                        $price_class_arr['normal_class']        = 'featured_priceB';
                        $price_class_arr['strike_class']        = 'featured_priceA';
                        $price_class_arr['yousave_class']       = 'featured_priceC';
                        $price_class_arr['discount_class']      = 'featured_priceC';
						$HTML_price = show_Price($row_featured,$price_class_arr,'featured');
						/*$price_arr =  show_Price($row_featured,array(),'featured',false,3);
						if($price_arr['base_price'])
							$HTML_price = '<div class="featured_priceB">'.$price_arr['base_price'].'</div>';
						if($price_arr['discounted_price'])
							$HTML_price .= '<div class="featured_priceC">'.$price_arr['discounted_price'].'</div>';*/
						

                    }
		?>
                    <form method="post" action="<?php url_link('manage_products.html')?>" name='frm_featured' id="frm_featured" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_featured['product_id']?>)">
                    <input type="hidden" name="fpurpose" value="" />
                    <input type="hidden" name="fproduct_id" value="" />
                    <input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
                    <input type="hidden" name="fproduct_url" value="<?php url_product($row_featured['product_id'],$row_featured['product_name'])?>" />
                    <div class="featured_con">
                    <div class="featured_left">
                    <?php
                    echo $HTML_title;
                    echo $HTML_desc;
                    /*
                    ?>
                    <div class="featured_price">
                    <?=$HTML_price;?>
                    </div>
                     <? */ ?>
                    <div class="featured_buy"><a href="<?php url_product($row_featured['product_id'],$row_featured['product_name'],-1)?>"><img src="<?php url_site_image('fet-buy.gif')?>" /></a></div>
                    </div>
                    <div class="featured_right">
                    <?=$HTML_comptitle?>
                    <div class="featured_price_outr">
                    
                    <?=$HTML_image;?>
                    </div>
                    </div>
                    </div>
                    </form>
		<?php	
		}
	};	
?>
