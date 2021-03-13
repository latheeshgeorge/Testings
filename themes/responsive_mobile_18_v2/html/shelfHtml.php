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
									//$shelfData['shelf_currentstyle']='gallery';
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
									 if($shelfData['shelf_currentstyle']=='gallery')
									 {
										
													$rwCnt	=	1;
													$HTML_price = '';
													$HTML_title = '';
													?>
													<div class="col-sm-6 col-md-6 col-xl-3" data-aos="fade-up">
													<h3 class="title-cat"><?php echo utf8_encode($cur_title);?></h3>
													<?php 
													while($row_prod = $db->fetch_array($ret_prod))
													{ 
													
														$HTML_title = '<a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">'.stripslash_normal($row_prod['product_name']).'</a> ';
													?>
													<div class="product-grid">
													<div class="product-title"><?php echo $HTML_title;?></div>
													<p class="product-info"><?php echo stripslash_normal(utf8_encode(replace_unwanted_quotes($row_prod['product_shortdesc']))); ?></p>

													<div class="product-img-wrap">	<?php $html_prodi ='<a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],0).'" title="'.stripslashes($row_prod['product_name']).'" >';
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
                                                    $html_prodi .=$html_prod_img;
													$html_prodi.='</a>';
													echo $html_prodi;
													?>
													</div>  
													<?php
													$price_arr =  show_Price($row_prod,$price_class_arr,'cat_detail_1',false,5);
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
															
																	$curprice_tax_cap = curprice_tax($price_arr,$row_prod);
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
																	echo $disc;
																	else
																	echo $base;
															?> <?php echo $curprice_tax_cap; ?>
															</span>
															</p>
																										<?php
													$sql_prodA = "SELECT product_bulkdiscount_allowed,product_applytax,product_discount,product_discount_enteredasval,product_variablecomboprice_allowed   
													FROM
													products
													WHERE product_id = ".$row_prod['product_id']." LIMIT 1 ";
													$ret_prodA = $db->query($sql_prodA);
													
													if($db->num_rows($ret_prodA))
													{
													$row_prodA = $db->fetch_array($ret_prodA);
													$bulk_disc = $row_prodA['product_bulkdiscount_allowed'];
													$combo_price = $row_prodA['product_variablecomboprice_allowed'];
													}
													$bulkdisc_details = product_BulkDiscount_Details_Puregusto($row_prod['product_id'],'','');
													
													//echo "<pre>";
													//print_r($bulkdisc_details);
													//echo "</pre>";
													?>
													<div class="product-table">

													<?php
													if($combo_price =='N')
													{
														?>
														<?php
														if($bulk_disc=='Y')
														{
															
													?>
													
															<?php
															$qty_bulk = '';
															$price_bulk = '';
															$cnt = 0;
															foreach ($bulkdisc_details as $key=>$val) {
																//print_r($val);
																for($i=0;$i<count($val);$i++)
																{
																if($key=='qty')
																$qty_bulk .= "<th>".$val[$i]."</th>";
																else if($key=='price')
																$price_bulk .= "<td>".$val[$i]."</td>";
																$cnt++;
																}																
															}
															?>
															
															<?php
																if($qty_bulk!='')
																{
																?>

																<table class="qty-discount-table">
																<thead><tr><th>Qty</th> <?php echo $qty_bulk;?></tr></thead>
																<tbody><tr><td>Price</td> <?php echo $price_bulk;?></tr></tbody>
																</table>
																
																<?php
																}
																?>
																<?php
														}
														?>

														<?php
													}
													
													?>
													</div>

													<div class="addwrap">
													<?php $frm_name = uniqid('shelf_'); ?>
															<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
																	<input type="hidden" name="fpurpose" value="" />
																	<input type="hidden" name="fproduct_id" value="" />
																	<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
																	<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
													<?php			$class_arr['ADD_TO_CART']       = 'btn btn-outline-secondary addbt';
																	$class_arr['PREORDER']          = 'btn btn-outline-secondary addbt';
																	$class_arr['ENQUIRE']           = 'btn btn-outline-secondary addbt';
																	$class_arr['QTY_DIV']           = '';
																	$class_arr['QTY']               = 'form-control qty_txt';
																	$class_arr['BTN_CLS']     = 'btn btn-outline-secondary addbt';
																	echo show_addtocart_responsive($frm_name,$row_prod,$class_arr,1,'shelf',false,false,'new_v2');?>
																	
																	<a  href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="btn btn-outline-secondary detailbt">Details</a>
																																		</form>
													</div>

													</div>
													<?php
													 }
													?>

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
