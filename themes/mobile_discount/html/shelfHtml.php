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
						$sql_gtparam = "SELECT * FROM finance_paymentgateway_details WHERE sites_site_id = $ecom_siteid LIMIT 1";
											$ret_gtparam = $db->query($sql_gtparam);
											if($db->num_rows($ret_gtparam))
											{
												$row_gtparam = $db->fetch_array($ret_gtparam);
												$API_key = trim($row_gtparam['finpay_apikey']);
												$INST_Id = trim($row_gtparam['finpay_installationid']);
											}
											$sql_getc = "SELECT finance_id,finance_rate,finance_code FROM finance_details WHERE sites_site_id = $ecom_siteid and finance_code='ONIB48-15.9' LIMIT 1";
											$ret_getc = $db->query($sql_getc);
											if($db->num_rows($ret_getc))
											{
											$row_getc = $db->fetch_array($ret_getc);
											$fin_code = $row_getc['finance_code'];
											}
							?>
							<script  type="text/javascript" src="https://test.dekopay.com/js/libraries/jquery/jquery-3.3.1.min.js"></script>
										<script type="text/javascript" src="https://secure.dekopay.com/js_api/FinanceDetails.js.php?api_key=<?php echo $API_key ?>"></script>
										<?php
						if ($db->num_rows($ret_prod))
						{
							$comp_active = isProductCompareEnabled();
							$pass_type = get_default_imagetype('midshelf');
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
									switch($shelfData['shelf_displaytype'])
									{
										case 'scroll':										
								?>		
								<script type="text/javascript">
									jQuery.noConflict();
									var $ajax_j = jQuery; 
										$ajax_j(document).ready(function() {
										
											$ajax_j('.iosSlider').iosSlider({
												desktopClickDrag: true
											});										
										});
										
		</script>						
									<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table_scroll">
									<tr>
										<td>
										
		<div class = 'iosSlider'>
		
			<div class = 'slider'>
								<?php						while($row_prod = $db->fetch_array($ret_prod))
															{
								?>							<div class = 'item' >
								
																	<div class="shp_brnd_thumbimg_pdt">
								<?php							if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
																{
																		//$ecom_allpricewithtax	=	1;
																		//$PriceSettings_arr['price_displaytype']	=	'show_price_only';
																		
																			$price_class_arr['ul_class'] 		= 'shelfBul_three_column';
																			$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
																			$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
																			$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
																			$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
																			echo show_Price($row_prod,$price_class_arr,'cat_detail_1');
																	
																}
																$price_bal2 = show_Price($row_prod,$price_class_arr,'other_3',false,5);
																if($price_bal2['prince_without_captions']['discounted_price'])
																{
																	$calcpricearr = explode('&pound;',$price_bal2['prince_without_captions']['discounted_price']);
																	$calcprice  = $calcpricearr[1];
																}
																else
																{
																	$calcpricearr = explode('&pound;',$price_bal2['prince_without_captions']['base_price']);
																	$calcprice  = $calcpricearr[1];
																}
															   //print_r($price_bal2);		   
																	$calcprice = $calcprice + $calcprice*.05;
																		   ?>
																		   <script type="text/javascript">
													var my_fd_obj = new FinanceDetails("<?php echo $fin_code; ?>", <?php echo $calcprice;?>, 10, 0);
													/*alert('here');*/
														$("#finpermonth_<?php echo $row_prod['product_id']?>").html("&pound;"+(my_fd_obj.m_inst).toFixed(2));								
														
													</script> 
							<?php
																if ($shelfData['shelf_showimage']==1)
																{
								?>										<div class="shp_brnd_thumbimg_image">
																		<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
							<?php									
																	$fld_mode	=	IMG_MODE;
																	$fld_size	=	IMG_SIZE;
																	//$fld_name	=	'image_extralargepath';
																	// Calling the function to get the image to be shown
																	$img_arr	=	get_imagelist('prod',$row_prod['product_id'],$fld_mode,0,0,1);
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
								?>										</a>
																		</div>
								<?php							}
																if($shelfData['shelf_showtitle']==1)
																{
								?>
																		<div class="shp_brnd_thumbimg_name">
																			<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="shlf_2row_link"><?php echo stripslashes($row_prod['product_name'])?></a>
																		</div>
								<?php							}
								?>
																	</div>
																	</div>
								<?php						}
								?>
								
															</div>															
											</div>
										</td>
									</tr>
									</table>
								<?php
													break;
										case '2row_mob':
								?>	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="shlf_table_2row">
								<?php	
								
										if($cur_title)
										{
								?>	<tr>
										<td class="curvea_top_z" colspan="2"><div class="curvea_div"><?php echo $cur_title;?></div></td>
									</tr>
								<?php	}
										$clCnt	=	1;
										$trcnt  =   1;
										while($row_prod = $db->fetch_array($ret_prod))
										{
											//echo $clCnt;
											if($clCnt == 1)
											{	$colCls	=	"shlf_table_2row_td_a";	}
											if($clCnt == 2)
											{	$colCls	=	"shlf_table_2row_td_b";	}
											if($clCnt == 3)
											{	$colCls	=	"shlf_table_2row_td_c";	}
											if($clCnt == 4)
											{	$colCls	=	"shlf_table_2row_td_d";	}
											$clCnt++;
											$trcnt++;
											if($trcnt==1)
											{
											echo "<tr>";
											}
								?>		<td class="<?php echo $colCls;?>" >
											<div class="shlf_table_2row_otr">
								<?php		if ($shelfData['shelf_showimage']==1)
											{
												if($row_prod['product_newicon_show']==1)
												{
								?>				<div class="shlf_table_2row_offer"><img src="<?php url_site_image('new.png')?>" /></div>
								<?php 			}
												if($row_prod['product_saleicon_show']==1)
												{
								?>				<div class="shlf_table_2row_offer"><img src="<?php url_site_image('sale.png')?>" /></div>
								<?php 			}
								?>				<div class="shlf_table_2row_img">
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
															$newWidth	=	$imgProperty['width']/2;
															$newHeight	=	$imgProperty['height']/2;
															show_image_mobile($no_img,$row_prod['product_name'],$row_prod['product_name'],'','',0,$newWidth,$newHeight);
														}
														else
														{
															show_image($no_img,$row_prod['product_name'],$row_prod['product_name']);
														}
													}
												}
								?>				</a>
												</div>
								<?php		}
											if($shelfData['shelf_showtitle']==1)
											{
								?>				<div class="shlf_table_2row_name">
													<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="shlf_2row_link"><?php echo stripslashes($row_prod['product_name'])?></a>
												</div>
								<?php		}
											if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
											{
												$price_arr =  show_Price($row_prod,array(),'shelf',false,6);
												
								?>				
												<div class="shlf_table_2row_price">
								<?php				
												$price_class_arr['ul_class'] 		= 'shelfBul_three_column';
												$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
												$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
												$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
												$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
												echo show_Price($row_prod,$price_class_arr,'cat_detail_1');
													/*echo show_Price($row_prod,$price_class_arr,'cat_detail_1');
													show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg*/
								?>				</div>
								
								<?php 			
								$price_bal2 = show_Price($row_prod,$price_class_arr,'other_3',false,5);
																if($price_bal2['prince_without_captions']['discounted_price'])
																{
																	$calcpricearr = explode('&pound;',$price_bal2['prince_without_captions']['discounted_price']);
																	$calcprice  = $calcpricearr[1];
																}
																else
																{
																	$calcpricearr = explode('&pound;',$price_bal2['prince_without_captions']['base_price']);
																	$calcprice  = $calcpricearr[1];
																}
															   //print_r($price_bal2);		   
																	$calcprice = $calcprice + $calcprice*.05;
																		   ?>
																		   <script type="text/javascript">
													var my_fd_obj = new FinanceDetails("<?php echo $fin_code; ?>", <?php echo $calcprice;?>, 10, 0);
													/*alert('here');*/
														$("#finpermonth_<?php echo $row_prod['product_id']?>").html("&pound;"+(my_fd_obj.m_inst).toFixed(2));								
														
													</script> 
													<?php
											}
								?>			</div>
										</td>
								<?php		if($trcnt > 2)
											{	
											echo "</tr>";
											$trcnt =1;
											}
											
											if($clCnt == 5)
											{
												//echo "</tr><tr>";
												$clCnt	=	1;
											}											
										}
										//echo $clCnt;
										if($clCnt%2==0)
										{
											//echo $colCls;
											if($colCls=='shlf_table_2row_td_a')
												$clsnewtd = "shlf_table_2row_td_an";
											else
												$clsnewtd = "shlf_table_2row_td_cn";	
								?>
										<td class="<?php echo $clsnewtd; ?>" >&nbsp;</td>
								<?php	}
										if($trcnt <= 2)
										{	
											echo "</tr>";											   
										}
								?>									
									<tr>
										<td class="shlf_shwall_a" colspan="2">
											<div class="spcl_shlfA_showall_otr">
												<a href="<?php  url_shelf_all($shelfData['shelf_id'],$shelfData['shelf_name'],-1)?>" class="spcl_shlfA_showall" title="<?php echo stripslash_normal($Captions_arr['COMMON']['SHOW_ALL'])?>"><?php echo stripslash_normal($Captions_arr['COMMON']['SHOW_ALL'])?></a>
											</div>
										</td>
									</tr>
									</table>
								<?php				break;
											case '1row_mob':
											
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
											{	$trCls	=	"shlf_table_1row_td_b";	}
											else
											{	$trCls	=	"shlf_table_1row_td_a";	}
											$rwCnt++;
								?>
									<tr>
										<td class="<?php echo $trCls;?>">
											<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="shlf_1row_link">
											<table width="100%" border="0" cellspacing="0" cellpadding="0">
											<tr>
								<?php		if ($shelfData['shelf_showimage']==1)
											{
								?>				<td class="shlf_td_1row_img">
												<div class="shlf_table_1row_img">
													
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
								?>					
													</div>
								<?php			if($row_prod['product_newicon_show']==1)
												{
								?>					<div class="shlf_table_1row_offer"><img src="<?php url_site_image('new.png')?>" /></div>
								<?php 			}
												if($row_prod['product_saleicon_show']==1)
												{
								?>					<div class="shlf_table_1row_offer"><img src="<?php url_site_image('sale.png')?>" /></div>
								<?php 			}
								?>				</td>
								<?php
											}
								?>				
												<td class="shlf_td_1row_desc">
												<div class="shlf_table_1row_otr"> 
								<?php			if($shelfData['shelf_showtitle']==1)
												{
								?>					<div class="shlf_table_1row_name">
														<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="shlf_1row_link"><?php echo stripslashes($row_prod['product_name'])?></a>
													</div> 
								<?php			}
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
								<?php				
								$price_class_arr['ul_class'] 		= 'shelfBul_three_column';
								$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
								$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
								$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
								$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
								echo show_Price($row_prod,$price_class_arr,'cat_detail_1');
								/*echo show_Price($row_prod,$price_class_arr,'cat_detail_1');
													show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg*/
													$price_bal2 = show_Price($row_prod,$price_class_arr,'other_3',false,5);
																if($price_bal2['prince_without_captions']['discounted_price'])
																{
																	$calcpricearr = explode('&pound;',$price_bal2['prince_without_captions']['discounted_price']);
																	$calcprice  = $calcpricearr[1];
																}
																else
																{
																	$calcpricearr = explode('&pound;',$price_bal2['prince_without_captions']['base_price']);
																	$calcprice  = $calcpricearr[1];
																}
															   //print_r($price_bal2);		   
																	$calcprice = $calcprice + $calcprice*.05;
																		   ?>
																		   <script type="text/javascript">
													var my_fd_obj = new FinanceDetails("<?php echo $fin_code; ?>", <?php echo $calcprice;?>, 10, 0);
													/*alert('here');*/
														$("#finpermonth_<?php echo $row_prod['product_id']?>").html("&pound;"+(my_fd_obj.m_inst).toFixed(2));								
														
													</script> 
													</div>
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
								<?php		break;
								   default:
								   ?>
								  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="shlf_table_1row">
								<?php	
								$sql_shelf_title = "SELECT shelf_name FROM product_shelf WHERE shelf_id = ".$shelfData['shelf_id']." AND sites_site_id=".$ecom_siteid." LIMIT 1"; 
							$ret_shelf_title = $db->query($sql_shelf_title);
							$row_shelf_title = $db->fetch_array($ret_shelf_title);
							$cur_title  = stripslashes($row_shelf_title['shelf_name']);
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
											{	$trCls	=	"shlf_table_1row_td_a";	}
											$rwCnt++;
								?>
									<tr>
										<td class="<?php echo $trCls;?>">
											<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="shlf_1row_link">
											<table width="100%" border="0" cellspacing="0" cellpadding="0">
											<tr>
								<?php		if ($shelfData['shelf_showimage']==1)
											{
								?>				<td class="shlf_td_1row_img">
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
														//print_r($imgProperty);
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
								<?php			if($row_prod['product_newicon_show']==1)
												{
								?>					<div class="shlf_table_1row_offer"><img src="<?php url_site_image('new.png')?>" /></div>
								<?php 			}
												if($row_prod['product_saleicon_show']==1)
												{
								?>					<div class="shlf_table_1row_offer"><img src="<?php url_site_image('sale.png')?>" /></div>
								<?php 			}
								?>				</td>
								<?php
											}
								?>				
												<td class="shlf_td_1row_desc">
												<div class="shlf_table_1row_otr"> 
								<?php			if($shelfData['shelf_showtitle']==1)
												{
								?>					<div class="shlf_table_1row_name">
														<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="shlf_1row_link"><?php echo stripslashes($row_prod['product_name'])?></a>
													</div> 
								<?php			}
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
								<?php				
								$price_class_arr['ul_class'] 		= 'shelfBul_three_column';
								$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
								$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
								$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
								$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
								echo show_Price($row_prod,$price_class_arr,'cat_detail_1');
								/*echo show_Price($row_prod,$price_class_arr,'cat_detail_1');
													show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg*/
								?>					</div>
								<?php 			
								$price_bal2 = show_Price($row_prod,$price_class_arr,'other_3',false,5);
																if($price_bal2['prince_without_captions']['discounted_price'])
																{
																	$calcpricearr = explode('&pound;',$price_bal2['prince_without_captions']['discounted_price']);
																	$calcprice  = $calcpricearr[1];
																}
																else
																{
																	$calcpricearr = explode('&pound;',$price_bal2['prince_without_captions']['base_price']);
																	$calcprice  = $calcpricearr[1];
																}
															   //print_r($price_bal2);		   
																	$calcprice = $calcprice + $calcprice*.05;
																		   ?>
																		   <script type="text/javascript">
													var my_fd_obj = new FinanceDetails("<?php echo $fin_code; ?>", <?php echo $calcprice;?>, 10, 0);
													/*alert('here');*/
														$("#finpermonth_<?php echo $row_prod['product_id']?>").html("&pound;"+(my_fd_obj.m_inst).toFixed(2));								
														
													</script> 	
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
								   break;
									};
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
											{	$trCls	=	"shlf_table_1row_td_a";	}
											$rwCnt++;
								?>
									<tr>
										<td class="<?php echo $trCls;?>">
											<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="shlf_1row_link">
											<table width="100%" border="0" cellspacing="0" cellpadding="0">
											<tr>
								<?php		if ($shelfData['shelf_showimage']==1)
											{
								?>				<td>
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
								<?php			if($row_prod['product_newicon_show']==1)
												{
								?>					<div class="shlf_table_1row_offer"><img src="<?php url_site_image('new.png')?>" /></div>
								<?php 			}
												if($row_prod['product_saleicon_show']==1)
												{
								?>					<div class="shlf_table_1row_offer"><img src="<?php url_site_image('sale.png')?>" /></div>
								<?php 			}
								?>				</td>
								<?php
											}
								?>				
												<td>
												<div class="shlf_table_1row_otr"> 
								<?php			if($shelfData['shelf_showtitle']==1)
												{
								?>					<div class="shlf_table_1row_name">
														<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="shlf_1row_link"><?php echo stripslashes($row_prod['product_name'])?></a>
													</div> 
								<?php			}
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
								$price_bal2 = show_Price($row_prod,$price_class_arr,'other_3',false,5);
																if($price_bal2['prince_without_captions']['discounted_price'])
																{
																	$calcpricearr = explode('&pound;',$price_bal2['prince_without_captions']['discounted_price']);
																	$calcprice  = $calcpricearr[1];
																}
																else
																{
																	$calcpricearr = explode('&pound;',$price_bal2['prince_without_captions']['base_price']);
																	$calcprice  = $calcpricearr[1];
																}
															   //print_r($price_bal2);		   
																	$calcprice = $calcprice + $calcprice*.05;
																		   ?>
																		   <script type="text/javascript">
													var my_fd_obj = new FinanceDetails("<?php echo $fin_code; ?>", <?php echo $calcprice;?>, 10, 0);
													/*alert('here');*/
														$("#finpermonth_<?php echo $row_prod['product_id']?>").html("&pound;"+(my_fd_obj.m_inst).toFixed(2));								
														
													</script> 
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
