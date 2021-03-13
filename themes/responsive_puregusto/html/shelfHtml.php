<?php
	/*############################################################################
	# Script Name 	: shelfHtml.php
	# Description 	: Page which holds the display logic for middle shelves
	# Coded by 		: Sny
	# Created on	: 10-Aug-2009
	# Modified by	: 
	# Modified On	: 
	##########################################################################*/
	class shelf_Html
	{
		// Defining function to show the shelf details
		function Show_Shelf($cur_title,$shelf_arr)
		{
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents,$shelf_for_inner,$ecom_allpricewithtax,$PriceSettings_arr;
			if (count($shelf_arr))
			{
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
				
				//Iterating through the shelf array to fetch the product to be shown.
				foreach ($shelf_arr as $k=>$shelfData)
				{ 
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
							if($shelf_for_inner==true) /* Case if call is to display shelf at the bottom on inner pages*/
							{
								if($shelfData['shelf_currentstyle']!='gallery')
									$shelfData['shelf_currentstyle']='nor';
									$shelfData['shelf_currentstyle']='gallery';
							}		
							else
								$shelfData['shelf_currentstyle']='list';
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
						$pg_variable	= 'shelf_'.$shelfData['shelf_id'].'_pg';
						if ($_REQUEST['req']!='' && $shelfData['shelf_currentstyle']=='list')// LIMIT for products is applied only if not displayed in home page
						{
							$start_var 		= prepare_paging($_REQUEST[$pg_variable],$prodperpage,$tot_cnt);
							$Limit			= " LIMIT ".$start_var['startrec'].", ".$prodperpage;
						}
						if($_REQUEST['req']=='')
						{
							if($shelfData['shelf_currentstyle']=='new')
							{
								$Limit			= " LIMIT 0,6";
							}
							else
							{
								$Limit			= " LIMIT 0,4";
							}
						}	
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
											a.product_freedelivery,a.product_actualstock           
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
							$pass_type = 'image_thumbpath';
							// Number of result to display on the page, will be in the LIMIT of the sql query also
							$querystring = ""; // if any additional query string required specify it over here
							$HTML_comptitle = $HTML_maindesc = $HTML_paging = $HTML_showall = '';
								
							//if ($_REQUEST['req']=='')// LIMIT for products is applied only if not displayed in home page
							{
								
							if ($tot_cnt>0 and ($_REQUEST['req']!='') and $tot_cnt>$prodperpage)
								{
									$paging 		= paging_footer_advanced($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr);
									$HTML_paging 	='
									<div class="subcat_nav_pdt_no_shelf"><span>'.$paging['total_cnt'].'</span></div></td></tr>
<tr>
<td class="pagingtd" colspan="2">
<div class="page_nav_content"><ul>';//.'';
									$HTML_paging 	.= $paging['navigation']['start_nav'].$paging['navigation']['page_no'].$paging['navigation']['end_nav'];
									$HTML_paging 	.= ' 
														</ul></div>
													
														
														
														';
								}
								if($_REQUEST['req']=='')
								{
									//$shelfData['shelf_currentstyle'] = 'nor';
								}
								if($_REQUEST['req']=='' and  $tot_cnt>0) // case of show all link
								{
								   $HTML_showall = "
								   
								   <div class='normal_mid_showall'><a href='".url_shelf_all($shelfData['shelf_id'],$shelfData['shelf_name'],1)."' title=''>".$Captions_arr['COMMON']['SHOW_ALL']."</a></div>
								  ";
								} 
								if($_REQUEST['req']=='')
								{
								if($shelfData['shelf_currentstyle']=='nor')
								{ 									

								?>
								 <div class="container shelf-container">  
 <h3><?php echo utf8_encode($cur_title);?></h3>


	<?php 
	$rwCnt	=	1;
	$HTML_price = '';
	$HTML_title = '';
					    while($row_prod = $db->fetch_array($ret_prod))
						{	
							
									$HTML_title = '<a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">'.stripslash_normal($row_prod['product_name']).'</a> ';
							
							
							?>
								<div class="grid_1_of_4 images_1_of_4">
									<div class="product-grid">										
										<?php 
										$rate = $row_prod['product_averagerating'];
										//echo $rating = $this->display_rating_responsive($rate,1,$row_prod['product_id']);
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
																</a>
												<p>

												</p>
												
												
											</div>
											<div class="resproduct_price">
											<?php
												if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
												{
													?>
													<?php			
													$price_class_arr['ul_class'] 		= 'price-avl';
													$price_class_arr['li_class'] 		= 'price';
													$price_class_arr['normal_class'] 	= 'price';
													$price_class_arr['strike_class'] 	= 'price_strike';
													$price_class_arr['yousave_class'] 	= 'price_yousave';
													$price_class_arr['discount_class'] 	= 'price_offer';
														//echo show_Price($row_prod,$price_class_arr,'shelfcenter_1'); //price_categorydetails_1_reqbreak
													$price_array =  show_Price($row_prod,$price_class_arr,'cat_detail_1',false,3);
													//print_r($price_array);
													$base =  $price_array['base_price'];
													$disc =  $price_array['discounted_price'];
													$disc_perc =  $price_array['disc_percent'];

													?>
													<ul class="price-avl">
													<?php 
													if($base!='')
													{
														?>
													<li class="price_strike"><?php echo $base; ?></li>
													<?php
													}
													else
													{
													?>
													<li class="price_strike">&nbsp;</li>
	
													<?php
													}
													if($disc!='')
													{
													?>
													<li class="price"><?php echo $disc; ?></li>
													<?php
												}
												else
												{
												?>
												<li class="price">&nbsp;</li>

												<?php
												}
												$you_perc =  $price_array['yousave_price'];

												if($disc_perc!='')
												{
													?>
													<li class="price_offer"><?php echo $disc_perc; ?></li><?php	
												}
												elseif($you_perc!='')
												{
												?>
													<li class="price_yousave"><?php echo $you_perc; ?></li><?php													
												}												
												else
												{
												?>
										         <li class="price_yousave">&nbsp;</li><?php	

												
												}	
														
													//show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
													?>
													</ul>
													<?php
													//echo show_Price($row_prod,$price_class_arr,'shelfcenter_1');
													?>
													 						
												   <?php 
												  }
												?>
											</div>
											<div class="product-info">
													<div class="product-info-cust">
														<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">Details</a>
													</div>
													<div class="product-info-price">
															<?php $frm_name = uniqid('catdet_'); ?>
													
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
											
									</div>
								</div>						
							
								<?php
								
					    }?>
					    
					              </div>
													<div class="container">
<div class="spcl_shlf_showall_otr"> <a href="<?php  url_shelf_all($shelfData['shelf_id'],$shelfData['shelf_name'],-1)?>" class="spcl_shlf_showall" title="<?php echo stripslash_normal($Captions_arr['COMMON']['SHOW_ALL'])?>"><?php echo stripslash_normal($Captions_arr['COMMON']['SHOW_ALL'])?></a> </div>
</div>

					   
												
								<?php
								
							}
							else if($shelfData['shelf_currentstyle']=='new')
							{
								?>
							<div class="row  recent-product-wrap">
							<div class="container recent-product">
							<?php
							$html_prod = "";
							$cnt=1;
						  $html_prod .='<div class="col-md-12">';
							$html_prod .='<ul class="list-unstyled list-thumbs-pro">';
							while($row_prod = $db->fetch_array($ret_prod))
							{
							
							$html_prod .='<li class="product">
							<div class="product-thumb-info">
							<div class="product-thumb-info-image">
							<table width="100%" border="0">
							<tr>
							<td align="center" valign="middle" height="130">
							';
							$html_prod .='<a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],0).'" title="'.stripslashes($row_prod['product_name']).'" >';
																	// Calling the function to get the image to be shown
																	$img_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,0,0,1);
																	if(count($img_arr))
																	{
																		$html_prod_img =show_image(url_root_image($img_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name'],'','',1);
																	}
																	else
																	{
																		// calling the function to get the default image
																		$no_img = get_noimage('prod',$pass_type); 
																		if ($no_img)
																		{
																			$html_prod_img =show_image($no_img,$row_prod['product_name'],$row_prod['product_name'],'','',1);
																		}
																	}
                                                    $html_prod .=$html_prod_img;
													$html_prod .='</a>';
							$html_prod .='</td>
							</tr></table></div>

							<div class="product-thumb-info-content">
							<h4><a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],0).'" title="'.stripslashes($row_prod['product_name']).'" >'.stripslashes($row_prod['product_name']).'</a></h4>';
$price_class_arr['ul_class'] 		= 'price-avl';
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
													$html_prod .=  '<div class="resproduct_price">'.show_Price($row_prod,$price_class_arr,'shelfcenter_1').'</div>';

							/*$html_prod .='<span class="price"><span>';
							$disc =  $price_array['discounted_price'];
													$base =  $price_array['base_price'];
													if($disc>0)
													$html_prod .= $disc;
													else
													$html_prod .= $base;
							$html_prod .='
							</span></span>';
							*/ 
							
							$html_prod .='<a  class="btn btn-default get" href="'.url_product($row_prod['product_id'],$row_prod['product_name'],0).'" title="'.stripslashes($row_prod['product_name']).'" >SHOP NOW</a>';
							$html_prod .=' 
							</div>
							</div>
							</li>';
							$cnt++;
							}
							
							$html_prod .='</ul>';

							$html_prod .='</div>';
							
							echo $html_prod;
							?>

							</div>    
							</div>
								<?php
							}
						}
								if($shelfData['shelf_currentstyle']=='list')
								{
									?>
																		

								 <div class="container shelf-containerlist">  
									 <div class="toolbar"></div>
 <h3 class="title-h3"><?php echo utf8_encode($cur_title);?></h3>


	<?php 
	$rwCnt	=	1;
	$HTML_price = '';
	$HTML_title = '';
					    while($row_prod = $db->fetch_array($ret_prod))
						{	
							
									$HTML_title = '<a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">'.stripslash_normal($row_prod['product_name']).'</a> ';
							
							
							?>
								<div class="grid_1_of_4 images_1_of_4">
									<div class="product-grid">

										<?php 
										$rate = $row_prod['product_averagerating'];
										//echo $rating = $this->display_rating_responsive($rate,1,$row_prod['product_id']);
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
																</a>
												<p>

												</p>
												<?php
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
												
											</div>
											<div class="product-info">
													<div class="product-info-cust">
														<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">Details</a>
													</div>
													<div class="product-info-price">
															<?php $frm_name = uniqid('shelflist_'); ?>
													
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
											
									</div>
								</div>						
							
								<?php
								
					    }?>
					    
					              </div>
					              <?php
								} 
							}
						}
					}
				}
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