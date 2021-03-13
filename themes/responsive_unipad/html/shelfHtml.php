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
							/*else
								$shelfData['shelf_currentstyle']='list';*/
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
						if ($_REQUEST['req']!='' && ($shelfData['shelf_currentstyle']=='nor' OR $shelfData['shelf_currentstyle']=='list'))// LIMIT for products is applied only if not displayed in home page
						{
							$start_var 		= prepare_paging($_REQUEST[$pg_variable],$prodperpage,$tot_cnt);
							//$Limit			= " LIMIT ".$start_var['startrec'].", ".$prodperpage;
						}
						if($_REQUEST['req']=='')
						{
							if($shelfData['shelf_currentstyle']=='new')
							{
								$Limit			= " LIMIT 0,6";
							}
							elseif($shelfData['shelf_currentstyle']=='nor')
							{
								$Limit			= " LIMIT 0,6";
							}
						}
						
						if($shelf_for_inner==1)
						{
							if($shelfData['shelf_currentstyle']=='new')
							{
								$Limit			= " LIMIT 0,100";
							}
							elseif($shelfData['shelf_currentstyle']=='nor')
							{
								$Limit			= " LIMIT 0,100";
							}
						}
						else
						{
							if($_REQUEST['req']!='')
							{
								$Limit = "LIMIT ".$start_var['startrec'].", ".$prodperpage;
							}
						}
						//print_r($_REQUEST);	
						if ($shelfData['shelf_currentstyle'] =='gallery')
						{
							$shelfsort_by		= 'b.product_order';
						}
						else
						{
							$shelfsort_by = " a.product_actualstock DESC, a.product_webprice ASC ";
							$shelfsort_order = '';
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
						if ($db->num_rows($ret_prod) || $shelfData['shelf_currentstyle']=='topprod')
						{ 
							$comp_active = isProductCompareEnabled();
							$pass_type = 'image_bigpath';
							// Number of result to display on the page, will be in the LIMIT of the sql query also
							$querystring = ""; // if any additional query string required specify it over here
							$HTML_comptitle = $HTML_maindesc = $HTML_paging = $HTML_showall = '';
								
							//if ($_REQUEST['req']=='')// LIMIT for products is applied only if not displayed in home page
							{
								
							if ($tot_cnt>0 and ($_REQUEST['req']!='') and $tot_cnt>$prodperpage)
								{
									//$paging 		= paging_footer_advanced($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr);
									$modS = 'resp';
									$paging 		= paging_footer_advanced_responsive($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr,$modS);
									/*$HTML_paging 	='
									<div class="subcat_nav_pdt_no_shelf"><span>'.$paging['total_cnt'].'</span></div></td></tr>
<tr>
<td class="pagingtd" colspan="2">
<div class="page_nav_content"><ul>';//.'';
									$HTML_paging 	.= $paging['navigation']['start_nav'].$paging['navigation']['page_no'].$paging['navigation']['end_nav'];
									$HTML_paging 	.= ' 
														</ul></div>
													
														
														
														';*/
														
								if($start_var['pages']>1)
								{
													
									$HTML_paging	= '	 
									<div class="pages" style="padding-bottom:16px;">
									<ul class="pagination">
																
									'.$paging['navigation']['start_nav'].$paging['navigation']['page_no'].$paging['navigation']['end_nav'].'
									</ul>
									</div>';
								}
														
														
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
								//echo $shelfData['shelf_currentstyle'];
								//if($_REQUEST['req']=='')
								{ 						

								if($shelfData['shelf_currentstyle']=='nor' or $shelfData['shelf_currentstyle']=='list')
								{ 									
									if($_REQUEST['req']=='static_page' and $_REQUEST['page_id']!='')
									{
										$cur_title = '<h2 class="h1-header-cls-bottompadding">'.$cur_title.'</h2>';
									}
								?>
								 <div class="shelf-container shelf-containerA container-fluid">  
 <?php /*<div class=""><div class="header_shelf"><?php echo $cur_title;?></div></div> */ ?>

<div class="">
	<?php 
	
								//echo "totcnt $tot_cnt req=".$_REQUEST['req']." perpag $prodperpage";
								if ($tot_cnt>0 and ($_REQUEST['req']!='') and $tot_cnt>$prodperpage and $shelf_for_inner!=1)
								{
									//$paging 		= paging_footer_advanced($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr);
									$modS = 'resp';
									$paging 		= paging_footer_advanced_responsive($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr,$modS);
									/*$HTML_paging 	='
									<div class="subcat_nav_pdt_no_shelf"><span>'.$paging['total_cnt'].'</span></div></td></tr>
<tr>
<td class="pagingtd" colspan="2">
<div class="page_nav_content"><ul>';//.'';
									$HTML_paging 	.= $paging['navigation']['start_nav'].$paging['navigation']['page_no'].$paging['navigation']['end_nav'];
									$HTML_paging 	.= ' 
														</ul></div>
													
														
														
														';*/
														
									if($start_var['pages']>1)
									{
														
										$HTML_paging	= '	 
										<div class="pages" style="padding-bottom:16px;">
										<ul class="pagination">
																	
										'.$paging['navigation']['start_nav'].$paging['navigation']['page_no'].$paging['navigation']['end_nav'].'
										</ul>
										</div>';
									}
									echo $HTML_paging;
														
								}	
								//echo $HTML_paging;

	
	$rwCnt	=	0;
	$HTML_price = '';
	$HTML_title = '';
					    while($row_prod = $db->fetch_array($ret_prod))
						{	
							
									$HTML_title = '<a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">'.stripslash_normal($row_prod['product_name']).'</a> ';
							
							
							
							/*if($rwCnt==1)
							{
							  echo '<div class="'.CONTAINER_CLASS.'">';
							}*/
							?>
								<div class="grid_1_of_4 images_1_of_4">
									<div class="product-grid">
										
										<?php 
										$rate = $row_prod['product_averagerating'];
										//echo $rating = $this->display_rating_responsive($rate,1,$row_prod['product_id']);
										?>
												<p>
												<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a>

												</p>
											<div class="product-pic bandnew">
												<?php
												$str_arrstr = array();
												$HTML_newAA = '';
												$HTML_newBB ='';
												if($row_prod['product_newicon_show']==1)
													{
														$descstr = stripslash_normal(trim($row_prod['product_newicon_text']));
														if($descstr!='')
														{
															$str_arrstr = explode ("~", $descstr);
															
														}
													}
													        if($str_arrstr[0]!='' AND $str_arrstr[0]=='Brand New For 2020')
															{
															  echo $HTML_newAA = '<div class="normal_shlfAA_pdt_new"><img src="'.url_site_image('brand_new.png',1).'" alt="Brand New"></div>';
														    }
														    else
														    {
															 	echo $HTML_newAA = '<div class="normal_shlfAA_pdt_new_blank">&nbsp;</div>';

															}
														   
												?>
												<?php
											if($row_prod['product_actualstock']==0)
											{
										?>
												<div class="nowletgraph"><img src="<?php echo url_site_image('nowLet.svg',1) ?>" alt="Now Let"></div>
										<?php		
											}
											if($row_prod['product_actualstock']>0)
													$availability_msg = '<span class="availablegraph">'.$Captions_arr['COMMON']['PRODDET_AVAIL_1_YEAR'].'</span>';
												else
													$availability_msg = '<span class="availablegraph">'.$Captions_arr['COMMON']['PRODDET_AVAIL_2_YEAR'].'</span>';
												echo $availability_msg;
										?>
													<a href="javascript:handletap('<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>','shelf<?php echo $row_prod['product_id']?>')" title="<?php echo stripslashes($row_prod['product_name'])?>" <?php //echo getmicrodata_producturl()?> >
														<?php
														// Calling the function to get the image to be shown
														$img_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,0,0,1);
														if(count($img_arr))
														{
															$moreimgarr= array();
															$moreimgstr = '';
															$prodmoreimg_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,0,0);
															if(count($prodmoreimg_arr))
															{
																for($mi=0;$mi<count($prodmoreimg_arr);$mi++)
																{
																	$moreimgarr[]= url_root_image($prodmoreimg_arr[$mi][$pass_type],1);
																}
																$moreimgstr = implode(",",$moreimgarr);
															}
															//$imgpass_arr = array('id'=>$img_arr[0]['image_id'],'typ'=>'big');
															show_image(url_root_image($img_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name'],'listscrollimg" data-imageid="shelf'.$row_prod['product_id'].'" data-tapped="0" data-immore="'.$moreimgstr,'',0,$imgpass_arr);
															
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
												
												<?php
															if($str_arrstr[1]!=''  AND $str_arrstr[1]=='Examples Of Finish1')
														    {
															  echo $HTML_newBB = '<div class="normal_shlfBB_pdt_new"><img src="'.url_site_image('example-finish.png',1).'" alt="Example Finish"></div>';
															}
															 else
														    {
															 	echo $HTML_newBB = '<div class="normal_shlfAA_pdt_new_blank">&nbsp;</div>';

															}
												if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
												{
													?>
													<?php			
													$price_class_arr['ul_class'] 		= 'price-avl';
													$price_class_arr['normal_class'] 	= '';
													$price_class_arr['strike_class'] 	= 'price';
													$price_class_arr['yousave_class'] 	= '';
													$price_class_arr['discount_class'] 	= '';
													//	echo show_Price($row_prod,$price_class_arr,'shelfcenter_1'); price_categorydetails_1_reqbreak
													$price_array =  show_Price($row_prod,$price_class_arr,'cat_detail_1',false,3);
													//print_r($price_array);
													?>
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
													<div class="clear"> </div>
												   <?php 
												  }
												$link 		= url_product($row_prod['product_id'],$row_prod['product_name'],1);

												?>
												<div class="moreinfolist"><a href="<?php echo $link?>" title="<?php echo $row_prod['product_name']?>" class="moreinfolist_a">More Info</a></div>
												<?php
								 if($_REQUEST['req']=='')
								 $mod['source'] = "shelf";
								 else
								 $mod['source'] = "list";
								 show_ProductLabels_Unipad($row_prod['product_id'],$mod); ?>
											</div>
<?php                       
                            //confirm message section start here 
                            $frm_name = uniqid('catdet_'); 
							$sql_pp = "SELECT product_actualstock FROM products WHERE product_id =".$row_prod['product_id']." LIMIT 1";
							$ret_pp = $db->query($sql_pp);
							$row_pp = $db->fetch_array($ret_pp);
							if($row_pp['product_actualstock']==0)
							{
							$onclick = "";
							$type ='button';
							$class_arr['BTN_CLS']     = 'btn btn-add-to-cart btn-lg sharp enquire-confirm';
							}
							else
							{
							$type ='submit';
							$class_arr['BTN_CLS']     = 'btn btn-add-to-cart btn-lg sharp';
							$onclick = "return product_enterkey(this,".$row_prod['product_id'].")";
							}
							?>
							
							
							<?php
							 //confirm message section end here
							?>
											<div class="product-info">
													<div class="product-info-cust">
														<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">Details</a>
													</div>
													<div class="product-info-price">
															
													
																	<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="<?php echo $onclick ?>">
																	<input type="hidden" name = "form_name_<?php echo $row_prod['product_id']?>" id = "form_name_<?php echo $row_prod['product_id']?>" value="<?php echo $frm_name; ?>">
																	<input type="hidden" name="fpurpose" value="" />
																	<input type="hidden" name="qty" value="1" />
																	<input type="hidden" name="fproduct_id" value="" />
																	<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
																	<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
													<?php			$class_arr['ADD_TO_CART']       = '';
																	$class_arr['PREORDER']          = '';
																	$class_arr['ENQUIRE']           = '';
																	$class_arr['QTY_DIV']           = '';
																	$class_arr['QTY']               = ' ';
																	$class_td['QTY']				= '';
																	$class_td['TXT']				= '';
																	$class_td['BTN']				= '';
																	//$class_arr['BTN_CLS']     = 'btn btn-add-to-cart btn-lg sharp';
																	echo show_addtocart_responsive($frm_name,$row_prod,$class_arr,0,'shelf',false,true,'','',$type);?>
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
								
								$rwCnt++;
								//if($rwCnt==4)
								//{
								 // echo '</div>';
								  //$rwCnt=1;
								//}
								
								
					    }
					    if($_REQUEST['req']=='' or $shelf_for_inner==true)
						{
						if($tot_cnt>$rwCnt)
						{
						?>
						<div class="<?php //echo CONTAINER_CLASS;?>">
						<div class="spcl_shlf_showall_otr"> <a href="<?php  url_shelf_all($shelfData['shelf_id'],$shelfData['shelf_name'],-1)?>" class="spcl_shlf_showall" title="<?php echo stripslash_normal($Captions_arr['COMMON']['SHOW_ALL'])?>"><?php echo stripslash_normal($Captions_arr['COMMON']['SHOW_ALL'])?></a> </div>
						</div>
						<?php
						}
						}
						else
						{
						echo $HTML_paging;
						}
					    $desc = trim($shelfData['shelf_description']);
									if($desc!='')
									{
										echo $desc;
									}
					    
					    $listscroll_obj = '.listscrollimg';
						$listscroll_delay = 1400;
						include "listprod_scroller.php";
					    
					    /*if($rwCnt<4)
					    {
						 echo '</div>';
						}*/
					    ?>
					    </div>
					    
					              </div>        
													
												
								


					   
												
								<?php
								
							}
								elseif($shelfData['shelf_currentstyle']=='gallery')
								{
									/*echo  "<link href=\"".url_head_link("images/".$ecom_hostname."/mobile_photoswype/css/photoswipe.css",1)."\" media=\"screen\" type=\"text/css\" rel=\"stylesheet\" />";
									echo "<script type=\"text/javascript\" src=\"".url_head_link("images/".$ecom_hostname."/mobile_photoswype/js/klass.min.js",1)."\"></script>";
									echo "<script type=\"text/javascript\" src=\"".url_head_link("images/".$ecom_hostname."/mobile_photoswype/js/code.photoswipe-3.0.5.min.js",1)."\"></script>";
							*/
									
									/*echo  "<link href=\"".url_head_link("images/".$ecom_hostname."/css/jquery.bsPhotoGallery.css",1)."\" type=\"text/css\" rel=\"stylesheet\" />";
									echo "<script type=\"text/javascript\" src=\"".url_head_link("images/".$ecom_hostname."/scripts/jquery.bsPhotoGallery.js",1)."\"></script>";
									*/
									
									echo '
									<!-- PhotoSwipe Core CSS file -->
									<link rel="stylesheet" href="'.url_head_link("images/".$ecom_hostname."/scripts/jqueryphotoswype/src/PhotoSwipe/photoswipe.css",1).'">

									<!-- PhotoSwipe Skin CSS file (styling of UI - buttons, caption, etc.)-->
									<link rel="stylesheet" href="'.url_head_link("images/".$ecom_hostname."/scripts/jqueryphotoswype/src/PhotoSwipe/default-skin/default-skin.css",1).'">

									<!-- PhotoSwipe Core JS file -->
									<script src="'.url_head_link("images/".$ecom_hostname."/scripts/jqueryphotoswype/src/PhotoSwipe/photoswipe.min.js",1).'"></script>

									<!-- PhotoSwipe UI JS file -->
									<script src="'.url_head_link("images/".$ecom_hostname."/scripts/jqueryphotoswype/src/PhotoSwipe/photoswipe-ui-default.min.js",1).'"></script>

									<!-- jqPhotoSwipe JS file -->
									<script src="'.url_head_link("images/".$ecom_hostname."/scripts/jqueryphotoswype/src/jqPhotoSwipe.js",1).'"></script>
									';
									
									
									?>
									<style type="text/css">
									ul.dd {
										  padding:0 0 0 0;
										  margin:0 0 40px 0;
									  }
									  ul.dd li {
										  list-style:none;
										  margin-bottom:10px;
									  }
									</style>
									<?php 
									  
									$prev_id = 0;							  
									$imghold_arr = array();
									$galleryids = 1;
									while($row_prod = $db->fetch_array($ret_prod))
									{
										$cnt_cls++;
										//$pass_type ='image_bigpath';
										$pass_type ='image_bigpath';
										$img_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,0,0,0);
										
										if(count($img_arr))
										{
										?>
											<div class="gallery_propertyouter">
												<div class="gallery_propertyname"><a href="<?php echo url_product($row_prod['product_id'],$row_prod['product_name'],1)?>"><?php echo stripslashes($row_prod['product_name'])?></a></div>
												<?php
												for($im_i=0;$im_i<count($img_arr);$im_i++)
												{ 

												?>
												<a class="fancybox" href="<?php echo url_root_image($img_arr[$im_i]['image_extralargepath'],1)?>" data-fancybox-group="gall_<?php echo $galleryids?>" title="<?php echo stripslashes($row_prod['product_name'])?>" alt="<?php echo stripslashes($row_prod['product_name'])?>"><img src="<?php echo url_root_image($img_arr[$im_i]['image_thumbpath'],1)?>" border="0" style="height: auto;height: 70px;width: 70px !important;margin-bottom:10px;" alt="<?php echo stripslashes($row_prod['product_name'])?>"></a>
												
												

												<?php
												}
												?>
												
											</div>
										<?php	
											$galleryids++;
										}	
									}
									$desc = trim($shelfData['shelf_description']);
									if($desc!='')
									{
										echo $desc;
									}
									?>
									<script type="text/javascript">
									 $(document).ready(function(){
										$(".fancybox").jqPhotoSwipe();
									  });
									  </script>
									<?php
									
									
								}
								elseif($shelfData['shelf_currentstyle']=='topprod')
								{ 
									?>
									 
									<?php
								    $sql = "SELECT b.product_name,b.product_id,a.hits ,c.total_hits,b.product_variablestock_allowed,b.product_show_cartlink,
											b.product_preorder_allowed,b.product_show_enquirelink,b.product_webstock,b.product_webprice,
											b.product_discount,b.product_discount_enteredasval,b.product_bulkdiscount_allowed,
											product_total_preorder_allowed,b.product_applytax,b.product_shortdesc,b.product_bonuspoints,
											b.product_stock_notification_required,b.product_alloworder_notinstock,b.product_variables_exists,b.product_variablesaddonprice_exists,
											b.product_variablecomboprice_allowed,b.product_variablecombocommon_image_allowed,b.default_comb_id,
											b.price_normalprefix,b.price_normalsuffix, b.price_fromprefix, b.price_fromsuffix,b.price_specialofferprefix, b.price_specialoffersuffix, 
											b.price_discountprefix, b.price_discountsuffix, b.price_yousaveprefix, b.price_yousavesuffix,b.price_noprice,
											b.product_averagerating,b.product_saleicon_show,b.product_saleicon_text,b.product_newicon_show,b.product_newicon_text,
											b.product_freedelivery,b.product_actualstock    
								FROM 
									product_hit_count a, products b,product_hit_count_totals c 
								WHERE 
									a.product_id=b.product_id 
									AND b.product_id=c.products_product_id 
									AND b.sites_site_id=$ecom_siteid 
									AND c.sites_site_id=$ecom_siteid 
									AND a.month='".date("m")."' 
									AND a.year='".date("Y")."' 
								ORDER BY 
									a.hits 
								DESC 
								LIMIT 
									3";
									$res = $db->query($sql);
									$cur_row = 0;
									if($db->num_rows($res))
									{
										?>
										<div class="shelf-container shelf-containerA">  
 <div class=""><div class="header_shelf"><?php echo $cur_title;?></div></div>

<div class="">
										<?php
										while($row_prod = $db->fetch_array($res))
										 {
											$HTML_title = '<a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">'.stripslash_normal($row_prod['product_name']).'</a> ';
							
							
							
							/*if($rwCnt==1)
							{
							  echo '<div class="'.CONTAINER_CLASS.'">';
							}*/
							?>
								<div class="grid_1_of_4 images_1_of_4">
									<div class="product-grid">
										
										<?php 
										$rate = $row_prod['product_averagerating'];
										//echo $rating = $this->display_rating_responsive($rate,1,$row_prod['product_id']);
										?>
										<?php
											if($row_prod['product_actualstock']==0)
											{
										?>
												<div class="nowlet_cls_inner"><img src="<?php echo url_site_image('nowLet.svg',1) ?>" alt="Now Let"></div>
										<?php		
											}
											if($row_prod['product_actualstock']>0)
													$availability_msg = '<span class="red_available">'.$Captions_arr['COMMON']['PRODDET_AVAIL_1_YEAR'].'</span>';
												else
													$availability_msg = '<span class="red_available">'.$Captions_arr['COMMON']['PRODDET_AVAIL_2_YEAR'].'</span>';
												echo $availability_msg;
										?>	
										
											<p>
												<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a>

												</p>
											<div class="product-pic">
												
													<a href="javascript:handletap('<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>','shelf<?php echo $row_prod['product_id']?>')" title="<?php echo stripslashes($row_prod['product_name'])?>" <?php //echo getmicrodata_producturl()?> >
														<?php
														// Calling the function to get the image to be shown
														$img_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,0,0,1);
														if(count($img_arr))
														{
															$moreimgarr= array();
															$moreimgstr = '';
															$prodmoreimg_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,0,0);
															if(count($prodmoreimg_arr))
															{
																for($mi=0;$mi<count($prodmoreimg_arr);$mi++)
																{
																	$moreimgarr[]= url_root_image($prodmoreimg_arr[$mi][$pass_type],1);
																}
																$moreimgstr = implode(",",$moreimgarr);
															}
															show_image(url_root_image($img_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name'],'listscrollimg" data-imageid="shelf'.$row_prod['product_id'].'" data-tapped="0" data-immore="'.$moreimgstr);
															
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
												
												
												<?php
												//if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
												{
													?>
													<?php			
													$price_class_arr['ul_class'] 		= 'price-avl';
													$price_class_arr['normal_class'] 	= '';
													$price_class_arr['strike_class'] 	= 'price';
													$price_class_arr['yousave_class'] 	= '';
													$price_class_arr['discount_class'] 	= '';
													//	echo show_Price($row_prod,$price_class_arr,'shelfcenter_1'); price_categorydetails_1_reqbreak
													$price_array =  show_Price($row_prod,$price_class_arr,'cat_detail_1',false,3);
													//print_r($price_array);
													?>
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
													<div class="clear"> </div>
												   <?php 
												  }
												$link 		= url_product($row_prod['product_id'],$row_prod['product_name'],1);

												?>
												<div class="moreinfolist"><a href="<?php echo $link?>" title="<?php echo $row_prod['product_name']?>" class="moreinfolist_a">More Info</a></div>
												
												
												
												<?php
								 if($_REQUEST['req']=='')
								 $mod['source'] = "shelf";
								 else
								 $mod['source'] = "list";
								 show_ProductLabels_Unipad($row_prod['product_id'],$mod); ?>
											</div>
											<?php                       
											//confirm message section start here 
											$frm_name = uniqid('catdet_'); 
											$sql_pp = "SELECT product_actualstock FROM products WHERE product_id =".$row_prod['product_id']." LIMIT 1";
											$ret_pp = $db->query($sql_pp);
											$row_pp = $db->fetch_array($ret_pp);
											if($row_pp['product_actualstock']==0)
											{
											$onclick = "";
											$type ='button';
											$class_arr['BTN_CLS']     = 'btn btn-add-to-cart btn-lg sharp enquire-confirm';
											}
											else
											{
											$type ='submit';
											$class_arr['BTN_CLS']     = 'btn btn-add-to-cart btn-lg sharp';
											$onclick = "return product_enterkey(this,".$row_prod['product_id'].")";
											}
											?>
											<div class="product-info">
													<div class="product-info-cust">
														<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">Details</a>
													</div>
													<div class="product-info-price">
															<?php $frm_name = uniqid('catdet_'); ?>
													
																	<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
																	<input type="hidden" name = "form_name_<?php echo $row_prod['product_id']?>" id = "form_name_<?php echo $row_prod['product_id']?>" value="<?php echo $frm_name; ?>">

																	<input type="hidden" name="fpurpose" value="" />
																	<input type="hidden" name="qty" value="1" />
																	<input type="hidden" name="fproduct_id" value="" />
																	<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
																	<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
													<?php			$class_arr['ADD_TO_CART']       = '';
																	$class_arr['PREORDER']          = '';
																	$class_arr['ENQUIRE']           = '';
																	$class_arr['QTY_DIV']           = '';
																	$class_arr['QTY']               = ' ';
																	$class_td['QTY']				= '';
																	$class_td['TXT']				= '';
																	$class_td['BTN']				= '';
																	//$class_arr['BTN_CLS']     = 'btn btn-add-to-cart btn-lg sharp';
																	echo show_addtocart_responsive($frm_name,$row_prod,$class_arr,0,'shelf',false,true,'','',$type);?>
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
								
								//$rwCnt++;
								//if($rwCnt==4)
								//{
								 // echo '</div>';
								  //$rwCnt=1;
								//}
								
					    }
					    
					    $listscroll_obj = '.listscrollimg';
						$listscroll_delay = 1400;
						include "listprod_scroller.php";
					    
					    /*if($rwCnt<4)
					    {
						 echo '</div>';
						}*/
					    ?>
					    </div>
					    
					              </div>
					              <?php 
											 
										
									 }
								} //end of topprod
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
