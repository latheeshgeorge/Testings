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
						$pg_variable	= 'shelf_'.$shelfData['shelf_id'].'_pg';
						if ($_REQUEST['req']!='')// LIMIT for products is applied only if not displayed in home page
						{
							$start_var 		= prepare_paging($_REQUEST[$pg_variable],$prodperpage,$tot_cnt);
							$Limit			= " LIMIT ".$start_var['startrec'].", ".$prodperpage;
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
											a.product_freedelivery,a.product_bestsellericon_show          
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
							if($shelfData['shelf_currentstyle']!='inner_listing') // case of normal design layout
							{
							if ($tot_cnt>0 and ($_REQUEST['req']!='') and $tot_cnt>$prodperpage)
								{
									$paging 		= paging_footer_advanced($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr);
									$HTML_paging 	='<tr>
											<td class="ttcnt" colspan="2">
									<div class="subcat_nav_pdt_no_shelf"><span>'.$paging['total_cnt'].'</span></div></td></tr>
<tr>
<td class="pagingtd" colspan="2">
<div class="page_nav_content"><ul>';//.'';
									$HTML_paging 	.= $paging['navigation']['start_nav'].$paging['navigation']['page_no'].$paging['navigation']['end_nav'];
									$HTML_paging 	.= ' 
														</ul></div>
													
														
														
														</td>
														</tr>';
								}
								if($_REQUEST['req']=='' and  $tot_cnt>0) // case of show all link
								{
								   $HTML_showall = "
								   <tr>
									<td class=''>
								   <div class='normal_mid_showall'><a href='".url_shelf_all($shelfData['shelf_id'],$shelfData['shelf_name'],1)."' title=''>".$Captions_arr['COMMON']['SHOW_ALL']."</a></div>
								   </td>
								   </tr>";
								}
								if($_REQUEST['req']=='')
								{ 									
											
								?>	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="shlf_table_1row">
								<?php	
										if($cur_title)
										{
								?>	<tr>
										<td class="curvea_top_z"><div class="curvea_div"><?php echo $cur_title; ?></div></td>
									</tr>
								<?php	}
										$rwCnt	=	1;
										while($row_prod = $db->fetch_array($ret_prod))
										{
											if($rwCnt % 2 == 0)
											{	$trCls	=	"shlf_table_1row_td_a";	}
											else
											{	$trCls	=	"shlf_table_1row_td_a";	}
											$rwCnt++;
								?>
									<tr>
										<td class="<?php echo $trCls;?>">
											<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="shlf_1row_link">
											<table width="100%" border="0" cellspacing="0" cellpadding="0">
											
								<?php		if ($shelfData['shelf_showimage']==1)
											{
								?>
								<tr>				
								<td class="shlf_td_1row_img" valign="top">
									<?php			if($row_prod['product_newicon_show']==1)
												{
								?>					<div class="shlf_table_1row_offer"><img src="<?php url_site_image('new.png')?>" /></div>
								<?php 			}
												if($row_prod['product_saleicon_show']==1)
												{
								?>					<div class="shlf_table_1row_offer"><img src="<?php url_site_image('sale.png')?>" /></div>
								<?php 			}
												if($row_prod['product_bestsellericon_show']==1)
												{
								?>					<div class="pdt_list_bestseller_3row"><img src="<?php url_site_image('best_seller.png')?>" /></div>
								<?php 			}
												
								?>	
												<div class="shlf_table_1row_img_home">
													<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
								<?php				
													$fld_mode	=	IMG_MODE;
													$fld_size	=	IMG_SIZE;
													//$fld_name	=	'image_extralargepath';
													// Calling the function to get the image to be shown
													$img_arr = get_imagelist('prod',$row_prod['product_id'],$fld_mode,0,0,1);
													if(count($img_arr))
													{
														$imgPath	=	url_root_image($img_arr[0][$fld_mode],1);
														$imgProperty=	image_property($imgPath);
														//echo "<pre>";print_r($imgProperty);
														if($imgProperty['width'] > 0 && $imgProperty['height'] > 0 )
														{
															$newWidth	=	ceil($imgProperty['width']/$fld_size);//echo $newWidth;echo "<br>";
															$newHeight	=	ceil($imgProperty['height']/$fld_size);//echo $newHeight;echo "<br>";
															show_image_mobile($imgPath,$row_prod['product_name'],$row_prod['product_name'],'','',0,$newWidth,$newHeight);
														}
														else
														{
															show_image($imgPath,$row_prod['product_name'],$row_prod['product_name']);
														}
													}
													else
													{
														// calling the function to get the default image
														$no_img = get_noimage('prod',$pass_type); 
														if ($no_img)
														{
															$imgProperty	=	image_property($no_img);
															
															if($imgProperty['width'] > 0 && $imgProperty['height'] > 0 )
															{
																$newWidth	=	$imgProperty['width']/$fld_size;
																$newHeight	=	$imgProperty['height']/$fld_size;
																show_image_mobile($no_img,$row_prod['product_name'],$row_prod['product_name'],'','',0,$newWidth,$newHeight);
															}
															else
															{
																show_image($no_img,$row_prod['product_name'],$row_prod['product_name']);
															}
														}
													}
								?>					</a>
													</div>
													
											</td>
								</tr>
								<?php
											}
								?>				<tr>
												<td class="shlf_td_1row_desc">
												<div class="shlf_table_1row_otr"> 
								<?php			if($shelfData['shelf_showtitle']==1)
												{
								?>					<div class="shlf_table_1row_name">
														<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="shlf_1row_link"><?php echo stripslashes($row_prod['product_name'])?></a>
													</div> 
								<?php			}
												/*
												if ($shelfData['shelf_showdescription']==1)
												{
								?>
													<div class="shlf_table_1row_des">
														<?php echo stripslashes($row_prod['product_shortdesc'])?><?php //show_moreinfo($row_prod,'list_more')?>
													</div>
								<?php			}
								*/ 
												if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
												{
								?>					
													<div class="shlf_table_1row_price">
								<?php				
								$price_class_arr['ul_class'] 		= 'shelfBul_three_column_home';
								$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
								$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
								$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
								$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
								echo show_Price($row_prod,$price_class_arr,'cat_detail_1');
								/*echo show_Price($row_prod,$price_class_arr,'cat_detail_1');
													show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg*/
								?>					</div>
								<?php 				
												}
								?>				</div>
												</td>
											</tr>
											</table></a>
										</td>
									</tr>
								<?php		}
								?>
									<tr>
										<td class="shlf_shwall_b">
											<div class="spcl_shlfA_showall_otr">
												<a href="<?php  url_shelf_all($shelfData['shelf_id'],$shelfData['shelf_name'],-1)?>" class="spcl_shlfA_showall" title="<?php echo stripslash_normal($Captions_arr['COMMON']['SHOW_ALL'])?>"><?php echo stripslash_normal($Captions_arr['COMMON']['SHOW_ALL'])?></a>
											</div>
										</td>
									</tr>
									</table>														
								
								  
									<?php
								}
								else
								{	 
								?>
										<table class="htable" width="100%" border="0">
										<?php
											echo $HTML_paging;
											?>
										</table>
								
										<table width="100%" border="0" cellspacing="0" cellpadding="0" class="shlf_table_1row">
								<?php	
										if($cur_title)
										{
								?>	<tr>
										<td class="curvea_top_z"><div class="curvea_div"><?php echo $cur_title; ?></div></td>
									</tr>
								<?php	}
										$rwCnt	=	1;
										while($row_prod = $db->fetch_array($ret_prod))
										{
											if($rwCnt % 2 == 0)
											{	$trCls	=	"shlf_table_1row_td_b";	}
											else
											{	$trCls	=	"shlf_table_1row_td_b";	}
											$rwCnt++;
								?>
									<tr>
										<td class="<?php echo $trCls;?>">
										<?php
										if($shelfData['shelf_showtitle']==1)
												{
								?>					<div class="shlf_table_1row_nameTop">
														<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="shlf_1row_link_list"><?php echo stripslashes($row_prod['product_name'])?></a>
													</div> 
								<?php			}
								?>
											<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="shlf_1row_link">
											<table width="100%" border="0" cellspacing="0" cellpadding="0">
											<tr>
								<?php		if ($shelfData['shelf_showimage']==1)
											{
								?>				<td valign="top">
												<div class="shlf_table_1row_img">
													<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
								<?php				
													$fld_mode	=	IMG_MODE;
													$fld_size	=	IMG_SIZE;
													//$fld_name	=	'image_extralargepath';
													// Calling the function to get the image to be shown
													$img_arr = get_imagelist('prod',$row_prod['product_id'],$fld_mode,0,0,1);
													if(count($img_arr))
													{
														$imgPath	=	url_root_image($img_arr[0][$fld_mode],1);
														$imgProperty=	image_property($imgPath);
														//echo "<pre>";print_r($imgProperty);
														if($imgProperty['width'] > 0 && $imgProperty['height'] > 0 )
														{
															$newWidth	=	ceil($imgProperty['width']/$fld_size);//echo $newWidth;echo "<br>";
															$newHeight	=	ceil($imgProperty['height']/$fld_size);//echo $newHeight;echo "<br>";
															show_image_mobile($imgPath,$row_prod['product_name'],$row_prod['product_name'],'','',0,$newWidth,$newHeight);
														}
														else
														{
															show_image($imgPath,$row_prod['product_name'],$row_prod['product_name']);
														}
													}
													else
													{
														// calling the function to get the default image
														$no_img = get_noimage('prod',$pass_type); 
														if ($no_img)
														{
															$imgProperty	=	image_property($no_img);
						                                    /*
															if($imgProperty['width'] > 0 && $imgProperty['height'] > 0 )
															{
																$newWidth	=	$imgProperty['width']/$fld_size;
																$newHeight	=	$imgProperty['height']/$fld_size;
																show_image_mobile($no_img,$row_prod['product_name'],$row_prod['product_name'],'','',0,$newWidth,$newHeight);
															}
															else
															*/ 
															{
																show_image($no_img,$row_prod['product_name'],$row_prod['product_name']);
															}
														}
													}
								?>					</a>
													</div>
										</td>
								<?php
											}
								?>				
												<td valign="top">
												<div class="shlf_table_1row_otr"> 
								<?php			
												if ($shelfData['shelf_showdescription']==1)
												{
								?>
													<div class="shlf_table_1row_des">
														<?php echo stripslashes($row_prod['product_shortdesc'])?><?php //show_moreinfo($row_prod,'list_more')?>
													</div>
								<?php			}
												if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
												{
								?>					
													<div class="shlf_table_1row_price">
								<?php				$price_class_arr['ul_class'] 		= 'shelfBul_three_column';
													$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
													$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
													$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
													$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
													echo show_Price($row_prod,$price_class_arr,'cat_detail_1');
													
													//	echo show_Price($row_prod,$price_class_arr,'shelfcenter_1'); price_categorydetails_1_reqbreak
													//echo show_Price($row_prod,$price_class_arr,'cat_detail_1');
													show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
								?>					</div>
								<?php 				
												}
												
								?>				
								<div class="offers_div">
				                <?php
				                if($row_prod['product_bulkdiscount_allowed']=='Y')
										{
				?>						<div class="prod_list_bulk_container"><div class="prod_list_bulk"></div></div>
				<?php					}
										if($row_prod['product_newicon_show']==1)
										{
				?>							<div class="prod_list_new_container"><div class="prod_list_new_img"></div></div>
				<?php 					}
										if($row_prod['product_saleicon_show']==1)
										{
				?>							<div class="prod_list_sale_container"><div class="prod_list_sale_img"></div></div>
				<?php 					}
										if($row_prod['product_show_pricepromise']==1)
										{
										?>
											<div class="prod_list_promise_container"><div class="prod_list_promise_img"></div></div>
										<?php
										}
										?>
					

				                
				                </div>
								</div>
												</td>
											</tr>
											</table></a>
										</td>
									</tr>
								<?php		}
								?>
									</table>
								<?php
								}
							}
							elseif($shelfData['shelf_currentstyle']=='inner_listing') // case of shelf to be displayed in inner pages
							{
							         if( $tot_cnt>0) // case of show all link
										{
										   $HTML_showall = "
										   <tr>
                                            <td class='shlf_shwall'>
										   <div class='spcl_shlfA_showall_otr'><a href='".url_shelf_all($shelfData['shelf_id'],$shelfData['shelf_name'],1)."' title=''>".$Captions_arr['COMMON']['SHOW_ALL']."</a></div>
										   </td>
										   </tr>";
									
										?>
                                    
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="ctable">
                                                <?php
												//echo $HTML_paging;
                                                if($cur_title!='')
                                                {
                                                ?>
                                                    <tr>
                                                    <td class="curvea_top"><div class="white"><?php echo $cur_title ?></div></td>
                                                    </tr>
                                                <?php 
												}
                                                while($row_prod = $db->fetch_array($ret_prod))
                                                {
                                                ?>
                                                    <tr>
                                                    <td class="curve_pdt">
                                                        <div class="product">
                                                            <a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1) ?>" title="<?php echo stripslash_normal($row_prod['product_name']) ?>" class="linkbold"><?php echo stripslash_normal($row_prod['product_name'])?></a>
                                                        </div>
                                                    </td>
                                                    </tr>
                                                <?php 
												}
												echo  $HTML_showall;
												?>
                                                </table>
                                          
                                <?php
								 }
							}	
						}
					}
					else
					{
						removefrom_Display_Settings($shelfData['shelf_id'],'mod_shelf');
					}
				}
			}	
		}
	};	
?>
