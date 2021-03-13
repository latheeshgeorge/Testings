<?php
	/*############################################################################
	# Script Name 	: shelfgroupHtml.php
	# Description 	: Page which holds the display logic for middle shelves
	# Coded by 		: Joby
	# Created on	: 09-May-2011
	# Modified by	: 
	# Modified On	: 
	##########################################################################*/
	class shelfgroup_Html
	{
		// Defining function to show the shelf details
		function Show_Shelfgroup($cur_title,$shelfgroup_arr)
		{
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents,$shelf_for_inner;
			global $js_owl,$css_owl;
			if (count($shelfgroup_arr))
			{ 
				//print_r($Settings_arr);
				$shelfsort_by			= $Settings_arr['product_orderfield_shelf'];
				$prodperpage			= $Settings_arr['product_maxcntperpage_shelf'];// product per page
			
				switch ($shelfsort_by)
				{
					case 'custom': // case of order by customer fiekd
					$shelfsort_by		= 'b.product_order';
					break;
					case 'product_name': // case of order by product name
					$shelfsort_by		= 'a.product_name';
					break;
					case 'price': // case of order by price
					$shelfsort_by		= 'a.product_webprice';
					break;
					case 'product_id': // case of order by price
					$prodsort_by		= 'a.product_id';
					break;	
					default: // by default order by product name
					$shelfsort_by		= 'a.product_name';
					break;
				};
				$shelfsort_order		= $Settings_arr['product_orderby_shelf'];
				$prev_shelf				= 0;
				$show_max               =0;
				//print_r($shelfgroup_arr);
				
				?>
				<div class="container">
   
				<div class="tabs-7">
              	<ul class="tabs">

                    <?php
                    
                    foreach ($shelfgroup_arr as $k=>$shelfgroupData)
                    /*1*/	{
                    
                    $shelfgroup_id = $shelfgroupData['id'];
                    
                    $sql_shelf_tab = "SELECT a.shelf_id,a.shelf_name
                    FROM 
                    product_shelf a LEFT JOIN shelf_group_shelf b 
                    ON (a.shelf_id = b.shelf_shelf_id ) 
                    WHERE
                    a.sites_site_id = $ecom_siteid 
                    AND b.shelf_group_id  = $shelfgroup_id 
                    AND a.shelf_hide = 0
                    ORDER BY a.shelf_order 
                    ";
                    $ret_shelf_tab = $db->query($sql_shelf_tab);
                    if ($db->num_rows($ret_shelf_tab))// Check whether result is there
                    {
                    $cnt1 =1;
                    while ($row_shelf_tab = $db->fetch_array($ret_shelf_tab))
                    {
						?>
						
						<li><a href="#tab_<?php echo stripslashes($row_shelf_tab['shelf_id']); ?>_<?php echo $cnt1;?>"><?php echo stripslashes($row_shelf_tab['shelf_name']); ?></a></li>
						
                    <?php                   
                    $cnt1++;
                    }
                    }		
                    ?>
                    </ul>
                    <?php				
                    
                    $sql_shelf = "SELECT a.shelf_id,a.shelf_name,a.shelf_description,a.shelf_displaytype,shelf_showimage,shelf_showtitle,
                    shelf_showdescription,shelf_showprice,shelf_currentstyle,shelf_activateperiodchange,
                    shelf_displaystartdate,shelf_displayenddate,a.shelf_showrating,a.shelf_showbonuspoints    
                    FROM 
                    product_shelf a LEFT JOIN shelf_group_shelf b 
                    ON (a.shelf_id = b.shelf_shelf_id ) 
                    WHERE
                    a.sites_site_id = $ecom_siteid 
                    AND b.shelf_group_id  = $shelfgroup_id 
                    AND a.shelf_hide = 0
                    ORDER BY a.shelf_order 
                    ";
                    
                    
                    $ret_shelf = $db->query($sql_shelf);
				if ($db->num_rows($ret_shelf))// Check whether result is there
				/*2*/{
				?>
				<section class="tab_content_wrapper">
					<?php
                    $cnt = 1;
                    $css_owl = '';
                    $js_owl  = '';
                    while ($shelfData = $db->fetch_array($ret_shelf))
		/*3*/		{

					?>                
									<article class="tab_content" id="tab_<?php echo stripslashes($shelfData['shelf_id']); ?>_<?php echo $cnt;?>">

									<div id="demo<?php echo $cnt;?>">

									<div class="container">
									<div class="row">
									<div class="span12">

									<div class="customNavigation<?php echo $cnt;?>">
									<a class="btn prev glyphicon glyphicon-arrow-left btn1 btn-info"></a>
									<a class="btn next glyphicon glyphicon-arrow-right btn1 btn-info"></a>
									</div>

									<div id="owl-demo<?php echo $cnt;?>" class="owl-carousel">      
									<?php
									$css_owl .="#owl-demo".$cnt." .item{
									background: #fff;
									padding:0px 0px;
									margin: 10px;
									color: #FFF;
									-webkit-border-radius: 3px;
									-moz-border-radius: 3px;
									border-radius: 3px;
									text-align: center;
									}";		
									$js_owl .= " var owl".$cnt." = $('#owl-demo".$cnt."');
												 $('#owl-demo".$cnt."').owlCarousel();
												  $('.next').click(function(){
													owl".$cnt.".trigger('owl.next');
												  });
												  $('.prev').click(function(){
													owl".$cnt.".trigger('owl.prev');
												  });
												  $('.play').click(function(){
													owl".$cnt.".trigger('owl.play',1000);
												  });
												  $('.stop').click(function(){
													owl".$cnt.".trigger('owl.stop');
												  });";
									// Check whether shelf_activateperiodchange is set to 1
									$active 	= $shelfData['shelf_activateperiodchange'];
									if($active==1)
									{
									$proceed	= validate_component_dates($shelfData['shelf_displaystartdate'],$shelfData['shelf_displayenddate']);
									}
									else
									$proceed	= true;	
									if ($proceed)
									{
									if($_REQUEST['req']!='') // If coming to show the details in middle area other than from the home page then show the details in normal shelf style
									{
									if($shelf_for_inner==true) // Case if call is to display shelf at the bottom on inner pages
									$shelfData['shelf_currentstyle']='inner_listing';
									else
									$shelfData['shelf_currentstyle']='nor';
									}	
									// Get the total number of product in current shelf
									$sql_totprod = "SELECT count(b.products_product_id) 
									FROM 
									products a,product_shelf_product b 
									WHERE 
									b.product_shelf_shelf_id = ".$shelfData['shelf_id']." 
									AND a.product_id = b.products_product_id 
									AND a.product_hide = 'N' ";
									$ret_totprod 	= $db->query($sql_totprod);
									list($tot_cnt) 	= $db->fetch_array($ret_totprod); 

									// Call the function which prepares variables to implement paging
									$ret_arr 		= array();

									// Get the list of products to be shown in current shelf
									$sql_prod = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
									a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
									a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
									product_total_preorder_allowed,a.product_applytax,a.product_shortdesc,a.product_bonuspoints,
									a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,a.product_variablesaddonprice_exists,
									a.product_variablecomboprice_allowed,a.product_variablecombocommon_image_allowed,a.default_comb_id,
									a.price_normalprefix,a.price_normalsuffix, a.price_fromprefix, a.price_fromsuffix,a.price_specialofferprefix, a.price_specialoffersuffix, 
									a.price_discountprefix, a.price_discountsuffix, a.price_yousaveprefix, a.price_yousavesuffix,a.price_noprice,
									a.product_averagerating,a.product_saleicon_show,a.product_saleicon_text,a.product_newicon_show,a.product_newicon_text,
									a.product_freedelivery         
									FROM 
									products a,product_shelf_product b 
									WHERE 
									b.product_shelf_shelf_id = ".$shelfData['shelf_id']." 
									AND a.product_id = b.products_product_id 
									AND a.product_hide = 'N' 
									ORDER BY 
									$shelfsort_by $shelfsort_order 
									$Limit	";
									$ret_prod = $db->query($sql_prod);
									if ($db->num_rows($ret_prod))
									{
									$comp_active = isProductCompareEnabled();
									$pass_type = get_default_imagetype('midshelf');
											$width_one_set 	= 200;
											$min_number_req	= 5;
											$min_width_req 	= $width_one_set * $min_number_req;
											$total_cnt		= $db->num_rows($ret_prod);
											$calc_width		= $total_cnt * $width_one_set;
											if($calc_width < $min_width_req)
												$div_width = $min_width_req;
											else
												$div_width = $calc_width; 
									// Number of result to display on the page, will be in the LIMIT of the sql query also
									?>

									<?php 
									$prodcur_arr = array();
									while($row_prod = $db->fetch_array($ret_prod))
									{

									?>
									<div class="item"><div class="product-grid">
									<?php
									/*
									<div class="product-grid-head">
										
										<?php 
												$rate = $row_prod['product_averagerating'];
												echo $rating = $this->display_rating_responsive($rate,1,$row_prod['product_id']);
												?>
									</div>
									*/
									?> 
									<div class="product-pic">
									<a class="product_name_a" href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a>
													<a class="product_pic_a" href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" <?php //echo getmicrodata_producturl()?> >
										<?php
																			// Calling the function to get the image to be shown
																			$img_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,0,0,1);
																			if(count($img_arr))
																			{
																				show_image(url_root_image($img_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name']);
																			}
																			else
																			{
																				// calling the function to get the default image
																				$no_img = get_noimage('prod',$pass_type); 
																				if ($no_img)
																				{
																					show_image($no_img,$row_prod['product_name'],$row_prod['product_name']);
																				}
																			}
																			?>							
																		</a>								<p></p>
										<?php 
										$HTML_title = '<a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">'.stripslash_normal($row_prod['product_name']).'</a> ';
										// echo $HTML_title;
												if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
												{
													$price_class_arr['ul_class'] 		= 'price-avl';
													$price_class_arr['li_class'] 		= 'price';
													$price_class_arr['normal_class'] 	= 'price';
													$price_class_arr['strike_class'] 	= 'price_strike';
													$price_class_arr['yousave_class'] 	= 'price_yousave';
													$price_class_arr['discount_class'] 	= 'price_offer';
														//echo show_Price($row_prod,$price_class_arr,'shelfcenter_1'); //price_categorydetails_1_reqbreak
													//$price_array =  show_Price($row_prod,$price_class_arr,'cat_detail_1',false,3);
													//print_r($price_array);
													
													/*?>
													 <ul class="price-avl">
								<li class="price"><span>
													<?php
													$disc =  $price_array['discounted_price'];
													$base =  $price_array['base_price'];
													if($disc>0)
													echo $disc;
													else
													echo $base;
													//show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
													?>
													</span>
													</li>
													</ul>
													*/
													echo show_Price($row_prod,$price_class_arr,'shelfcenter_1');
												  
												  }
												?>					
															<div class="clear"> </div>
									</div>
									<div class="product-info">
										<div class="product-info-cust">
																<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">Details</a>
										</div>
										<div class="product-info-price">															<?php $frm_name = uniqid('catdet_'); ?>
															
																			<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
																	<input type="hidden" name="fpurpose" value="" />
																	<input type="hidden" name="fproduct_id" value="" />
																	<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
																	<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
													<?php			$class_arr['ADD_TO_CART']       = 'input-group-addon';
																	$class_arr['PREORDER']          = 'input-group-addon';
																	$class_arr['ENQUIRE']           = 'input-group-addon';
																	$class_arr['QTY_DIV']           = '';
																	$class_arr['QTY']               = 'form-control';
																	$class_arr['BTN_CLS']     = 'input-group mb-2 mr-sm-2 mb-sm-0';
																	echo show_addtocart_responsive($frm_name,$row_prod,$class_arr,1,'shelf',false,false,'new');?>
																	
																	</form>
										</div>
										<div class="clear"> </div>
									</div>
									<div class="more-product-info">
										<span> </span>
									</div>
									</div></div>
									<?php
									}
									?>       

									<?php
									}
									}
									else
									{
									removefrom_Display_Settings($shelfData['shelf_id'],'mod_shelf');
									}
									?>
									</div>


									</div>
									</div>
									</div>

									</div>
									</article>

									<?php
									$cnt++;
                    /*3*/   }	
                      global $js_owl,$css_owl;
                    ?> 
                           </section>
                    <?php	
                    /*2*/	}	
                    
                    /*1*/ }
                    
                    ?>
                    </div>
                    </div>
                   
						
                <?php	
			}	
		}
		function display_rating_responsive($rate,$ret=0,$prod_id=0)
{ 
	global $ecom_siteid,$Settings_arr;
	if($Settings_arr['proddet_showwritereview']==1 or $Settings_arr['proddet_showreadreview']==1)
	{
		$retn ='<div class="container-star"><p>';
		$rate = ceil($rate);
		for ($i=0;$i<$rate;$i++)
		{
					if($ret==0)
						echo '<span class="glyphicon glyphicon-star"></span>'; 
					elseif($ret==1)
						$retn .= '<span class="glyphicon glyphicon-star"></span>';
		}
		if($rate<5)
		{
			$rem = ceil(5-$rate);
			for ($i=0;$i<$rem;$i++)
			{
						if($ret==0)
							echo '<span class="glyphicon glyphicon-star-empty"></span>'; 
						elseif($ret==1)
							$retn .= '<span class="glyphicon glyphicon-star-empty"></span>';    
			}
		}
		if($ecom_siteid==104 or $ecom_siteid==106)
		{  
			global $db;
			$cnt = 0;
		       if($prod_id>0)
		       {
		          $sql_prodreview	= "SELECT count(review_id) as cnt
									FROM  
										 product_reviews 
									WHERE  
										sites_site_id = $ecom_siteid
										AND products_product_id  =  ".$prod_id."
										AND review_status = 'APPROVED'  
										AND review_hide=0 
									LIMIT 10";
				 $ret_prodreview = $db->query($sql_prodreview);
				    if($db->num_rows($ret_prodreview))
					{
						$row_prodreview = $db->fetch_array($ret_prodreview);
				        $cnt = $row_prodreview['cnt']; 
					
					}
					if($cnt>0)
					{
					   $retn .= '<a href="'.url_product($prod_id,'',1).'?prod_curtab=-4#review" title="'.stripslashes($row_prod['product_name']).'"><div class="rev_cnt">	'.$cnt.' Review(s)</div></a>';
					}					
				}
		 }
		 $retn .='</p>
			    </div>';	
			if($ret==1)
				return $retn;
	}
}
	};	
?>
