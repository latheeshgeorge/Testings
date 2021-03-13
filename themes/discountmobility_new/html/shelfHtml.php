<?php
	/*############################################################################
	# Script Name 	: shelfHtml.php
	# Description 	: Page which holds the display logic for middle shelves
	# Coded by 		: Sny
	# Created on	: 29-Dec-2007
	# Modified by	: Sny
	# Modified On	: 01-Feb-2008
	##########################################################################*/
	class shelf_Html
	{
		// Defining function to show the shelf details
		function Show_Shelf($cur_title,$shelf_arr)
		{
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents;
			$ajax_return_function = 'ajax_return_productdetailscontents';
			$Captions_arr['PROD_DETAILS'] 	= getCaptions('PROD_DETAILS');			
			include_once "ajax/ajax.php";
			if (count($shelf_arr))
			{
				$shelfsort_by			= $Settings_arr['product_orderfield_shelf'];
				$prodperpage			= $Settings_arr['product_maxcntperpage_shelf'];// product per page
			    if($Settings_arr['product_maxcntperpage_shelf']==48)
				{
				 $Settings_arr['product_maxcntperpage_shelf'] = 100;
				}
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
						else
							$Limit = " LIMIT 0,6";
						
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
										a.product_freedelivery,a.product_discontinue           
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
							$sql_shelf_title = "SELECT shelf_name FROM product_shelf WHERE shelf_id = ".$shelfData['shelf_id']." AND sites_site_id=".$ecom_siteid." LIMIT 1"; 
							$ret_shelf_title = $db->query($sql_shelf_title);
							$row_shelf_title = $db->fetch_array($ret_shelf_title);
							$cur_title  = stripslashes($row_shelf_title['shelf_name']);
							$comp_active = isProductCompareEnabled();
							$pass_type = get_default_imagetype('midshelf');
							// Number of result to display on the page, will be in the LIMIT of the sql query also
							$querystring = ""; // if any additional query string required specify it over here
							//print_r($_REQUEST);
							//if($_REQUEST['req']!='') // If coming to show the details in middle area other than from the home page then show the details in normal shelf style
							{
								$shelfData['shelf_currentstyle']='newthree';
							}
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
							if($shelfData['shelf_currentstyle']=='nor' or $shelfData['shelf_currentstyle']=='new') // case of normal design layout or bestseller like display
							{ 
								switch($shelfData['shelf_displaytype'])
								{
									case 'dropdown':
									case 'list':
									case '2row': // case of one in a row for normal
				?>				<table style="border:0;border-spacing: 0;border-collapse: collapse;" class="shelfBtable">
				<?php 				if($cur_title)
									{
				?>				<tr><td class="shelfBheader" style="text-align:left"><?php echo $cur_title?></td></tr>
				<?php				}
									$desc = trim($shelfData['shelf_description']);
									if($desc!='' and $desc!='&nbsp;')
									{
				?>				<tr><td class="shelfBproddes" style="text-align:left"><?php echo $desc;?></td></tr>
				<?php				}
									if ($tot_cnt>0 and ($_REQUEST['req']!=''))
									{
				?>				<tr>
									<td class="pagingcontainertd" style="text-align:center">
				<?php 					$path = '';
										$query_string .= "";
										paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr); 	
				?>					</td>
								</tr>
				<?php				}
				?>				<tr>
									<td>
										<?php
										
										?>
										<table style="border:0;border-spacing: 0;border-collapse: collapse;" class="shelfBtable">
				<?php				$cur_row = 1 ;
									$max_col = 2;
									while($row_prod = $db->fetch_array($ret_prod))
									{
										if($cur_row==0)
										{
										  echo "<tr>";
										}
										if($cur_row!=0 && $cur_row%2==0)
										{
											$cls = "prod_list_td";
											$clsTble = "prod_list_td_tbl";
										}
										else
										{
										   $cls = "prod_list_td_r";
										   $clsTble = "prod_list_td_tbl_r";
										}
				?>						<td style="text-align:left;vertical-align:top" class="<?php echo $cls?>">
                <?php			
										if($row_prod['product_newicon_show']==1)
										{
				?>							<div class="prod_list_new_container"><div class="prod_list_new_img"></div></div>
				<?php 					}
										if($row_prod['product_saleicon_show']==1)
										{
				?>							<div class="prod_list_sale_container"><div class="prod_list_sale_img"></div></div>
				<?php 					}
				?>	
										<table style="width:100%;border:0;border-spacing: 0;border-collapse: collapse;" class="<?php echo $clsTble?>" <?php //echo getmicrodata_productscope()?>>
										<tr>
											<td colspan="2" class="prod_list_name_td">
												<div class="prod_list_name">
													<div class="prod_list_name_link">
				<?php					if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
										{
				?>										<span class="shelfBprodname"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><span <?php //echo getmicrodata_productname()?>><?php echo stripslashes($row_prod['product_name'])?></span></a></span>
													
				<?php					}										
				?>									</div>
												</div>
											</td>
										</tr>
										<tr>
											<td class="prod_list_img_td" style="vertical-align:top">
												<?php
												if ($shelfData['shelf_showimage']==1)// Check whether description is to be displayed
												{
													?>
													<div class="prod_list_img">
              <?php
										if($row_prod['product_bulkdiscount_allowed']=='Y')
										{
				?>						<div class="prod_list_bulk_container"><div class="prod_list_bulk"><img src="<?php url_site_image('bulk.png')?>" /></div></div>
				<?php					}
				?>
																					
															<div class="prod_list_img_div">
																<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" <?php //echo getmicrodata_producturl()?> >
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
															</div>

													</div>
													<?php
												}
												?>
											</td>
											<td  class="prod_list_price_td" style="vertical-align:top">
												<div class="prod_list_otx">
													<?php
												if ($shelfData['shelf_showdescription']==1)// Check whether description is to be displayed
												{
											?>
												<div class="prod_list_des"><?php show_prod_det_more_link(url_product($row_prod['product_id'],$row_prod['product_name'],1),$row_prod['product_shortdesc'])?></div>
												<?php 
												} 
												?>
													
												<?php
												if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
												{
													?>
													<div class="prod_list_price">
													<?php			$price_class_arr['ul_class'] 		= 'shelfBul';
													$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
													$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
													$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
													$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
													//	echo show_Price($row_prod,$price_class_arr,'shelfcenter_1'); price_categorydetails_1_reqbreak
													echo show_Price($row_prod,$price_class_arr,'cat_detail_1');
													show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
													?>						
													</div>
												   <?php 
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
								if($('#finpermonth').length){
								  $("#finpermonth").html("&pound;"+(my_fd_obj.m_inst).toFixed(2));
	
								}
								
							</script> 
													<?php		
																	if($shelfData['shelf_showbonuspoints']==1)
																	{
																	//show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points
																	/*$pass_arr['main_cls'] 		= 'prod_list_bonus';
																	$pass_arr['caption_cls'] 	= 'bonus_point_caption';
																	$pass_arr['point_cls'] 		= 'bonus_point_number';
																	show_bonus_points_msg_multicolor($row_prod,$pass_arr);*/
																	if($row_prod['product_bonuspoints'] > 0)
																	{
																		echo '<div class="prod_list_bonusB">
																				<span class="bonus_point_number_a">
																					<span>'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_BONUSPOINTSA']).'</span>
																				</span>
																				<span class="bonus_point_caption_b">'.$row_prod['product_bonuspoints'].'</span>
																				<span class="bonus_point_number_c">
																					<span>'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_BONUSPOINTSB']).'</span></span>
																				</div>';
																	}
																	}
																	?>
																	</div>
																	<?php
																	$frm_name = uniqid('catdet_');
													?>
																	<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
																	<input type="hidden" name="fpurpose" value="" />
																	<input type="hidden" name="fproduct_id" value="" />
																	<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
																	<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
																	<div class="prod_list_buy">
													<?php			$class_arr['ADD_TO_CART']       = '';
																	$class_arr['PREORDER']          = '';
																	$class_arr['ENQUIRE']           = '';
																	$class_arr['QTY_DIV']           = 'normal_shlfA_pdt_input';
																	$class_arr['QTY']               = ' ';
																	$class_td['QTY']				= 'prod_list_buy_a';
																	$class_td['TXT']				= 'prod_list_buy_b';
																	$class_td['BTN']				= 'prod_list_buy_c';
																	echo show_addtocart_v5($row_prod,$class_arr,$frm_name,false,'','',true,$class_td);
													?>						</div>
												</form>
												<div class="list_more_div">
												<?php show_moreinfo($row_prod,'list_more')?>
												</div>
											</td>
										</tr>
										<tr>
											<td colspan="2">
												
						<?php			if($row_prod['product_saleicon_show']==1)
										{
											$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
											if($desc!='')
											{
						?>						<div class="prod_list_new"><?php echo $desc?></div>
						<?php				}
										}
										if($row_prod['product_newicon_show']==1)
										{
											$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
											if($desc!='')
											{
						?>						<div class="prod_list_new"><?php echo $desc?></div>
						<?php				}
										}
						?>					</td>
										</tr>
										</table>
										</td>
						<?php			if($cur_row>=$max_col)
										{
											echo "</tr>";
											$cur_row = 0;
										}
										$cur_row ++;
									}
						?>				</table>
									</td>
								</tr>
								</table>
						<?php		break;									
								};
							}
							elseif($shelfData['shelf_currentstyle']=='newthree') // case of normal design layout or bestseller like display
							{ 
								switch($shelfData['shelf_displaytype'])
								{
									case 'dropdown':
									case 'list':
									case '2row': 
									case '3row': 

				?>				<table style="border:0;border-spacing: 0;border-collapse: collapse;" class="shelfBtable">
				<?php 				if($cur_title)
									{
				?>				<tr><td  class="shelfBheader" style="text-align:left"><?php echo $cur_title?></td></tr>
				<?php				}
									$desc = trim($shelfData['shelf_description']);
									if($desc!='' and $desc!='&nbsp;')
									{
				?>				<tr><td  class="shelfBproddes" style="text-align:left"><?php echo $desc;?></td></tr>
				<?php				}
									if ($tot_cnt>0 and ($_REQUEST['req']!=''))
									{
				?>				<tr>
									<td  class="pagingcontainertd" style="text-align:center">
				<?php 					$path = '';
										$query_string .= "";
										paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr); 	
				?>					</td>
								</tr>
				<?php				}
				?>				<tr>
									<td >
										<table style="border:0;border-spacing: 0;border-collapse: collapse;" class="shelfBtableZ">
											<tr>
									<td class="" style="text-align:center">
				<?php				$cur_row = 1 ;
									$max_col = 3;
									$col_cnts = 0;
									while($row_prod = $db->fetch_array($ret_prod))
									{
										$col_cnts++;
										if($col_cnts==3)
										{
											$col_cnts =0;
											$maincls = 'catBoxWrap_threecolumn_rt';	
										}
										else
											$maincls = 'catBoxWrap_threecolumn';
										?>
										<div class="<?php echo $maincls?>">
<?php
if($shelfData['shelf_id']==749)
{
	$clsf = "finanace_banner_promo_easter";
}
else
{
	$clsf = "finanace_banner"; 
}
												show_finanacebanner($row_prod,$clsf);
												if ($shelfData['shelf_showimage']==1)// Check whether description is to be displayed
												{
													?>
																<div class="featureimgqrap_three_column"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" <?php //echo getmicrodata_producturl()?> class="product_image" >
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
																</a> </div>
																<?php
												}
																if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
																{
																?>
																<div class="prod_list_name_link_three_column">
																<span class="shelfBprodname_three_column">
																<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><span <?php //echo getmicrodata_productname()?>><?php echo stripslashes($row_prod['product_name'])?></span></a>
																</span>

																</div>
																<?php
																}
												if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
												{
												?>										
													<div class="prod_list_price_three">
										<?php			
										            $price_class_arr['ul_class'] 		= 'shelfBul_three_column';
													$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
													$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
													$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
													$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
													$price_class_arr['link_capt'] 	= 'appr_cls';

													//	echo show_Price($row_prod,$price_class_arr,'shelfcenter_1'); price_categorydetails_1_reqbreak
													echo show_Price($row_prod,$price_class_arr,'cat_detail_1');
													show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
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
								$(".prod_list_price_three #finpermonth_<?php echo $row_prod['product_id']?>").html("&pound;"+(my_fd_obj.m_inst).toFixed(2));
								if($('#finpermonth').length){
								  $("#finpermonth").html("&pound;"+(my_fd_obj.m_inst).toFixed(2));
	
								}
							</script> 																		
														
										</div>
										<div class="det_all_outer">

										<?php
										if($shelfData['shelf_showrating']==1)
													{
														$module_name = 'mod_product_reviews';
														if(in_array($module_name,$inlineSiteComponents))
														{
															if($row_prod['product_averagerating']>=0)
															{
																$HTML_rating = display_rating($row_prod['product_averagerating'],1,'star-green.gif','star-white.gif',$row_prod['product_id']);
																?>
																	<div class="starBlock">
																	<div class="ratingStars">
																	<?php
																	echo $HTML_rating;
																	?>
																	</div>
																	</div>
																<?php
															}
														}
													}
										?>
										<?php			
										if($row_prod['product_saleicon_show']==1)
										{
											$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
											//if($desc!='')
											{
						?>						<div class="prod_list_sale_three"><?php //echo $desc?></div>
						<?php				}
										}
										if($row_prod['product_newicon_show']==1)
										{
											$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
											//if($desc!='')
											{
						?>						<div class="prod_list_new_three"><?php //echo $desc?></div>
						<?php				}
										}
										?>
										<div class="list_more_div">
												<?php show_moreinfo($row_prod,'list_more')?>
												</div>
												
										<?php
										//if($row_prod['product_saleicon_show']==1)
										{
											$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
											//if($desc!='')
											{
						?>						<div class="prod_list_sale_desc"><?php echo $desc?></div>
						<?php				}
										}
										?>
										</div>
										<?php
																	$frm_name = uniqid('catdet_');
													?>
																	<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
																	<input type="hidden" name="fpurpose" value="" />
																	<input type="hidden" name="fproduct_id" value="" />
																	<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
																	<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
																	<div class="prod_list_buy">
													<?php			$class_arr['ADD_TO_CART']       = '';
																	$class_arr['PREORDER']          = '';
																	$class_arr['ENQUIRE']           = '';
																	$class_arr['QTY_DIV']           = 'normal_shlfA_pdt_input';
																	$class_arr['QTY']               = ' ';
																	$class_td['QTY']				= 'prod_list_buy_a';
																	$class_td['TXT']				= 'prod_list_buy_b';
																	$class_td['BTN']				= 'prod_list_buy_c';
																	if($row_prod['product_discontinue']==1)
																	{
																		echo "<span class=\"discontinus\">Discontinued</span>";
																	}
																	else
																	{
																		echo show_addtocart_v5($row_prod,$class_arr,$frm_name,false,'','',true,$class_td);
																	}
													?>						</div>
												</form>
										</div>
										<?php
									}
						?>				
						</td>
						</tr>
						</table>
									</td>
								</tr>
								</table>
						<?php	break;	
								case 'featured':
									if($cur_title)
									{
									?>
									<table style="border:0;border-spacing: 0;border-collapse: collapse;width:100%" class="featured_table">
									<tr>
									  <td colspan="2" class="featuredproddet" style="text-align:left;vertical-align:top">
									<div class="featuredheader"><?php echo $cur_title?></div>
									</td>
									  </tr>
									</table>
									<?php
									}
									$feat_cnt=0;
									while($row_prod = $db->fetch_array($ret_prod))
									{
										$frm_name = uniqid('catdet_');
										if($feat_cnt==0)
										{
											$feat_cnt =1;
									?>
									<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
									<input type="hidden" name="fpurpose" value="" />
									<input type="hidden" name="fproduct_id" value="" />
									<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
									<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
									<table style="border:0;border-spacing: 0;border-collapse: collapse;width:100%" class="featured_table">
									<tbody>
									<?php
												$desc = trim($shelfData['shelf_description']);
												if($desc!='' and $desc!='&nbsp;')
												{
											?>
												<tr>
												<td colspan="2" align="left" style="padding: 0px 0px 10px 0">
													<?php echo stripslashes($desc)?>
												</td>
												</tr>
												<?php 
												} 
												?>	
									<tr>
										
										<td class="featuredprodimg" style="text-align:left;vertical-align:top">
							<?php 	//if ($row_prod['featured_showimage']==1)
									{
							?>		   <a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
							<?php		// Find out which sized image is to be displayed as featured product image
										switch($row_prod['featured_showimagetype'])
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
										$pass_type = 'image_bigcategorypath';
										// Calling the function to get the image to be shown
										$img_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,0,0,1);
										if(count($img_arr))
										{
											show_image(url_root_image($img_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name']);
										}
										else
										{
											// calling the function to get the no image
											$no_img = get_noimage('prod'); 
											if ($no_img)
											{
												show_image($no_img,$row_prod['product_name'],$row_prod['product_name']);
											}
										}
							?>			</a>
										</td>
							<?php	}
							?>			<td colspan="1" class="featuredproddet" style="text-align:left;vertical-align:top;width:56%">
							<?php	
									//if ($row_prod['featured_showtitle']==1)
									{
							?> 			<div class="featuredprodname"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="fet_name_link"><?php echo stripslashes($row_prod['product_name'])?></a></div>
							<?php	}
									// Check whether selected to show either desc or the price 	
									
									$price_arr = array();
									//$price_arr =  show_Price($row_featured,array(),'featured',false,3);
									//if($row_prod['featured_showprice']==1)// Check whether price is to be displayed
									{
							?>			<div class="featuredprice">
							<?php		
										$price_class_arr['ul_class'] 		= 'shelfBul';
										$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
										$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
										$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
										$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
										//	echo show_Price($row_prod,$price_class_arr,'shelfcenter_1'); price_categorydetails_1_reqbreak
										echo show_Price($row_prod,$price_class_arr,'featured');	


										/*if($price_arr['discounted_price'])
											echo $price_arr['discounted_price'];
										else
											echo $price_arr['base_price'];*/
										show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
							?>			</div>
							<?php	}
									//$frm_name$frm_name = 'frm_featured';
									$class_arr['ADD_TO_CART']       = '';
									$class_arr['PREORDER']          = '';
									$class_arr['ENQUIRE']           = '';
									$class_arr['QTY_DIV']           = 'normal_shlfA_pdt_input';
									$class_arr['QTY']               = ' ';
									$class_td['QTY']				= 'prod_list_buy_feata';
									$class_td['TXT']				= 'prod_list_buy_b';
									$class_td['BTN']				= 'prod_list_buy_c';
									echo show_addtocart_v5($row_prod,$class_arr,$frm_name,false,'','',true,$class_td);
							?>			</td>
									</tr>
									</tbody>
									</table>
								</form>
									<?php	
									}
									}
								break;
								
								};
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
