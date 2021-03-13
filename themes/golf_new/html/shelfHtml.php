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
							$Limit = '';
						//Section to display only three products in home page
						if($shelfData['shelf_currentstyle']=='christ' && $_REQUEST['req']=='')
						 {
						   	$show_max =3;
				          $Limit	= " LIMIT 0,".$show_max."";// product per page
						 }
						 else if($shelfData['shelf_currentstyle']=='new' && $_REQUEST['req']=='')
						 {
						   	$show_max =4;
				          $Limit	= " LIMIT 0,".$show_max."";// product per page
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
						if ($db->num_rows($ret_prod))
						{
							$comp_active = isProductCompareEnabled();
							$pass_type = get_default_imagetype('midshelf');
							// Number of result to display on the page, will be in the LIMIT of the sql query also
							$querystring = ""; // if any additional query string required specify it over here
							if($_REQUEST['req']!='')
							{
								$shelfData['shelf_currentstyle']='nor';
							}
							if($shelfData['shelf_currentstyle']=='nor') // case of normal design layout
							{
								switch($shelfData['shelf_displaytype'])
								{
									case 'dropdown':
									case 'list':
									case '1row': // case of one in a row for normal
									?>
									<table class="newshelfBtable" border="0" cellpadding="0" cellspacing="0">
									<tbody>
									<?php 
									if($cur_title){
									?>
									<tr>
									<td class="newshelfBheader" align="left"><?php echo stripslashes($cur_title);?></td>
									</tr>
									<? }
									$desc = stripslashes(trim($shelfData['shelf_description']));
									if($desc!='' and $desc!='&nbsp;')
									{
									?>
									<tr>
										<td colspan="3" class="shelfAdes" align="left"><?php echo $desc?></td>
									</tr>
									<?php		
									}
									if ($tot_cnt>0 and ($_REQUEST['req']!=''))
									{
									?>
									<tr>
										<td colspan="3" class="pagingcontainertd" align="center">
										<?php 
											$path = '';
											$query_string .= "";
											paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr); 	
										?>	
										</td>
									</tr>
									<?php
									}
									while($row_prod = $db->fetch_array($ret_prod))
									{
									?>						
									<tr >
									<td align="left" valign="middle"class="newshelfBtabletd" onmouseover="this.className='newnormalshelf_hover'" onmouseout="this.className='newshelfBtabletd'">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" class="newshelfBtableinner">
									<tr>
									<td class="newshelfBtableinnertd" valign="top" align="left">
									<?php
												if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
												{
											?>
									<h2 class="newshelfBprodname"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h2>
									<? }
									if ($shelfData['shelf_showdescription']==1)// Check whether description is to be displayed
												{
											?>		
									<h6 class="newshelfBproddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6>
									<? }
									/*
									if($shelfData['shelf_showrating']==1)
										{
											$module_name = 'mod_product_reviews';
											if(in_array($module_name,$inlineSiteComponents))
											{
												if($row_prod['product_averagerating']>=0)
												{
											?>
												<div class="shlfa_star">
												<?php
													display_rating($row_prod['product_averagerating']);
													?>
												</div>
											<?php
												}
											}	
										}
										*/
										if($row_prod['product_saleicon_show']==1)
											{
												$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
												if($desc!='')
												{
											?>	
													<div class="shlfa_sale_sale"><?php echo $desc?></div>
											<?php
												}
											}
											if($row_prod['product_newicon_show']==1)
											{
												$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
												if($desc!='')
												{
											?>
													<div class="shlfa_sale_new"><?php echo $desc?></div>
											<?php
												}
											} 
									    if($row_prod['product_bulkdiscount_allowed']=='Y'  || $row_prod['product_freedelivery']==1 )
										{
										?>
											<div class="shlf_sale">
											<? if($row_prod['product_bulkdiscount_allowed']=='Y')
											{
											?>
											<img src="<?php url_site_image('bulk-discount.gif')?>" alt="Bulk Discount"/>
											<?
											}
											if($row_prod['product_freedelivery']==1){
											?>
											<img src="<?php url_site_image('free-img.gif')?>" alt="Free Delivery"/>
											<?
											}
											?>
											</div>
										<?php
										}
										?>
									</td>
									
									<td class="newshelfBtableinnertd" valign="middle" align="center">
									<?php
											if ($shelfData['shelf_showimage']==1)// Check whether description is to be displayed
											{
										?>	
									<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
												<?php
													// Calling the function to get the type of image to shown for current 
													// Calling the function to get the image to be shown
													$img_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,0,0,1);
													if(count($img_arr))
													{
														show_image(url_root_image($img_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name'],'newshelfBimg');
													}
													else
													{
														// calling the function to get the default image
														$no_img = get_noimage('prod',$pass_type); 
														if ($no_img)
														{
															show_image($no_img,$row_prod['product_name'],$row_prod['product_name'],'newshelfBimg');
														}	
													}	
												?>
												</a> 
									
									<? }?>
									<?php if($comp_active)  {
												dislplayCompareButton($row_prod['product_id']);
											}?>
									</td>
									<td class="newshelfBtableinnertd" valign="top" align="left">
									
									<?php
										
											if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
											{ 
												$price_class_arr['ul_class'] 		= 'shelfpriceul';
												$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
												$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
												$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
												$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
												echo show_Price($row_prod,$price_class_arr,'shelfcenter_1');
												show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
											}
											if($shelfData['shelf_showbonuspoints']==1)
											{
												show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points
											}
												$frm_name = uniqid('shelf_');
										?>	
												<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
												<input type="hidden" name="fpurpose" value="" />
												<input type="hidden" name="fproduct_id" value="" />
												<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
												<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
												<div class="buy_main">
													<div class="shlf_info_btn"><div class="shlf_info_btnleft">
												<?php show_moreinfo($row_prod,'info_buy')?>
												</div></div>  
												<div class="shlf_buy_btn"><div class="shlf_buy_btnleft">
												<?php
														$class_arr 					= array();
														$class_arr['ADD_TO_CART']	= 'link_buy';
														$class_arr['PREORDER']		= 'link_buy';
														$class_arr['ENQUIRE']		= 'link_buy';
														show_addtocart($row_prod,$class_arr,$frm_name,false,'','','add_divlink',0)
													?></div>
												</div>      
												 
												</div>    
												<?php /*?><div class="infodiv">
													<div class="infodivleft"><?php show_moreinfo($row_prod,'infolink')?></div>
													<div class="infodivright">
													<?php
														$class_arr 					= array();
														$class_arr['ADD_TO_CART']	= 'info_buy';
														$class_arr['PREORDER']		= 'info_buy';
														$class_arr['ENQUIRE']		= 'info_buy';
														show_addtocart($row_prod,$class_arr,$frm_name)
													?>
													</div>
												</div><?php */?>
												</form>
									
									</td>
									</tr>
																			
									</table></td>
									</tr>
									<? }?>
									</tbody>
								</table>
									<?php
									break;
									case '3row': // case of three in a row for normal
									?>
										<table border="0" cellpadding="0" cellspacing="0" class="shelfAtable">
										<?php 
										if($cur_title)
										{
										?>
											<tr>
												<td colspan="3" class="shelfAheader" align="left"><?php echo $cur_title?></td>
											</tr>
										<?php
										}
										$desc = stripslashes(trim($shelfData['shelf_description']));
										if($desc!='' and $desc!='&nbsp;')
										{
									?>
											<tr>
												<td colspan="3" class="shelfAdes" align="left"><?php echo $desc?></td>
											</tr>
									<?php		
										}
										if ($tot_cnt>0 and ($_REQUEST['req']!=''))
										{
										?>
											<tr>
												<td colspan="3" class="pagingcontainertd" align="center">
												<?php 
													$path = '';
													//show_paging($path,$_REQUEST[$pg_variable],$start_arr['counterstart'],$tot_cnt,$prodperpage,$pg_variable,'')
														$query_string .= "";
														paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr); 	
												?>	
												</td>
											</tr>
										<?php
										}
										?>	
										<?php
											$max_col = 3;
											$cur_col = 0;
											$prodcur_arr = array();
											while($row_prod = $db->fetch_array($ret_prod))
											{
												$prodcur_arr[] = $row_prod;
												//##############################################################
												// Showing the title, description and image part for the product
												//##############################################################
										if($cur_col == 0)
										{
										  echo "<tr>";
										}
										$cur_col ++;

										?>
											<td class="newshelfAtabletd" onmouseover="this.className='newshelfAtabletd_hover'" onmouseout="this.className='newshelfAtabletd'" align="left" valign="top">												
													<ul class="shelfAul">
														<?php
															if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
															{
														?>
																<li><h2 class="shelfAprodname"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h2></li>
															
														<?php
															}
															if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
															{
														?>
																<li>
																	<?php
																		$price_class_arr['ul_class'] 		= 'shelfBul';
																		$price_class_arr['normal_class'] 	= 'shelfAnormalprice';
																		$price_class_arr['strike_class'] 	= 'shelfAstrikeprice';
																		$price_class_arr['yousave_class'] 	= 'shelfAyousaveprice';
																		$price_class_arr['discount_class'] 	= 'shelfAdiscountprice';
																		echo show_Price($row_prod,$price_class_arr,'shelfcenter_3');
																		show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
																	?>	
																</li>
														<?php
															}
															if ($shelfData['shelf_showimage']==1)// Check whether description is to be displayed
															{
														?>		
																<li class="shelfimg">
																	<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
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
																	
																</li>
																
														<?php
															}
															/*
														if($shelfData['shelf_showrating']==1)
														{
															$module_name = 'mod_product_reviews';
																if(in_array($module_name,$inlineSiteComponents))
																{
																	if($row_prod['product_averagerating']>=0)
																		{
																		?>
																		<li class="shlf_star">
																		<?php
																		display_rating($row_prod['product_averagerating']);
																		?>
																		</li>
																		<?php
																	}
															}	
														}
														*/
														if($row_prod['product_freedelivery']==1 || $row_prod['product_bulkdiscount_allowed']=='Y')
														{
															?>
															<li class="shlf_sale">
																<?php
																if($row_prod['product_bulkdiscount_allowed']=='Y')
																{	
																	?>
																	<img src="<?php url_site_image('bulk-discount.gif')?>" alt="Bulk Discount"/>
																	<?php
																}
																if($row_prod['product_freedelivery']==1){
																	?>
																	<img src="<?php url_site_image('free-img.gif')?>" alt="Free Delivery"/>
																	<?
																}
																?>
															</li>
															<?
														}
														if ($shelfData['shelf_showdescription']==1)// Check whether description is to be displayed
														{
														?>
															<li><h6 class="shelfAproddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6></li>
														<?php
														}
														?>	
													</ul>
														<?php 
														/*
														if($shelfData['shelf_showbonuspoints']==1)
														{
															show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points
														}
														*/ 
														$frm_name = uniqid('shelf_');
													?>
															<?php 
															/*
															if($comp_active)  {
															dislplayCompareButton($row_prod['product_id']);
															}
															*/ 
															if($row_prod['product_saleicon_show']==1)
															{
																$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
																if($desc!='')
																{
																	?>	
																	<div class="shlf_sale_sale"><?php echo $desc?></div>
																	<?php
																}
																}
																if($row_prod['product_newicon_show']==1)
																{
																$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
																if($desc!='')
																{
																	?>
																	<div class="shlf_sale_new"><?php echo $desc?></div>
																	<?php
																}
															}
															?>
															<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
															<input type="hidden" name="fpurpose" value="" />
															<input type="hidden" name="fproduct_id" value="" />
															<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
															<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
															<div class="shlf_info_btn"><div class="shlf_info_btnleft">
															<?php show_moreinfo($row_prod,'info_buy')?>
															</div></div> 
															<div class="buy_main">
															<div class="shlf_buy_btn"><div class="shlf_buy_btnleft">
															<?php
															$class_arr 					= array();
															$class_arr['ADD_TO_CART']	= 'link_buy';
															$class_arr['PREORDER']		= 'link_buy';
															$class_arr['ENQUIRE']		= 'link_buy';
															show_addtocart($row_prod,$class_arr,$frm_name,false,'','','add_divlink',0)
															?></div>
															</div>      
															  
															</div>
															</form>
															</td>
											<?php
														if($cur_col>=$max_col)
														{
														$cur_col =0;
														echo "</tr>";
														
														}

														// done to handle the case of breaking to new linel
												}
											// If in case total product is less than the max allowed per row then handle that situation
								                	if($cur_col<$max_col)
													{
													   if($cur_col!=0)
														{ 
															$cur_td=0;
															echo "<td colspan='".($max_col-$cur_col)."'>&nbsp;</td>";
															echo "</tr>";
														} 
													}
											?>
											
										</table>
								<?php		
									break;
								};
							}
							elseif($shelfData['shelf_currentstyle']=='christ') // case of christmas layout
							{
								switch($shelfData['shelf_displaytype'])
								{
									case '1row':
									case 'dropdown':
									case 'list': // case of one in a row for christmas  christmas_rowtableB class="" 
									?>
											<table width="100%" border="0" cellpadding="0" cellspacing="0" class="spcl_shlD_table">
                                              <tr>
                                                <td class="spcl_shlD_hdr"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="spcl_shlD_hdr_table">
                                                    <tr>
													<?php
											if($cur_title)
											{
										?>
                                                      <td align="left" valign="middle" class="spcl_shlD_header"> <? echo $cur_title?></td>
										   <? }?>
														  <td align="right" valign="middle"> <?
															if ($tot_cnt>0 and ($_REQUEST['req']=='') && $tot_cnt > $show_max)
															{
															?><a href="<?php  url_shelf_all($shelfData['shelf_id'],$shelfData['shelf_name'],-1)?>" class="spcl_shlD_showall" title="<?php echo $Captions_arr['COMMON']['SHOW_ALL']?>"><?php echo $Captions_arr['COMMON']['SHOW_ALL']?></a>
															<?
															}
															?>
														</td>
                                                    </tr>
                                                </table></td>
                                              </tr>
											  <?php
											$desc = stripslashes(trim($shelfData['shelf_description']));
											if($desc!='' and $desc!='&nbsp;')
											{
										?>
												<tr>
													<td colspan="3" class="christmas_proddesB" align="left"><?php echo $desc?></td>
												</tr>
										<?php		
											}
											if ($tot_cnt>0 and ($_REQUEST['req']!=''))
											{
										?>
												<tr>
													<td colspan="3" align="center" class="pagingcontainertd">
													<?php 
														$path = '';
														$query_string .= "";
														paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr); 	
													?>	
													</td>
												</tr>
										<?php
											} 
											?>
                                              <tr>
                                                <td class="spcl_shlD_pdt">
												<?php
												while($row_prod = $db->fetch_array($ret_prod))
												{
													$frm_name = uniqid('shelf_');
												?>
														<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
														<input type="hidden" name="fpurpose" value="" />
														<input type="hidden" name="fproduct_id" value="" />
														<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
														<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
	
													<table width="100%" border="0" cellpadding="0" cellspacing="0" class="spcl_shlD_pdt_table">
													  <tr>
														<td align="center" valign="top" class="spcl_shlD_pdt_image_td"><div>
														  <?php
															if ($shelfData['shelf_showimage']==1)// Check whether description is to be displayed
															{
															  //$pass_type = 'image_iconpath';
															  $pass_type = 'image_bigcategorypath';
														?>		
															
														<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
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
														<?
														}
														?>
														</div></td>
														<td align="left" valign="middle" class="spcl_shlD_pdt_price_td">
															<div class="spcl_shlD_pdt_price_td_div">
																 <div class="spcl_shlD_pdt_name_div">
																 <?php
																if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
																{
														?>
															
																<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" class="spcl_shlD_pdt_name_link" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a>
																 <?
																 }
																 ?>
																 </div>
																 <div class="spcl_shlD_pdt_price_div">
																 <?php
																if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
																{
																		$price_arr =  show_Price($row_prod,array(),'shelfcenter_1',false,3);
																         if($price_arr['base_price']){?>
														                   <span class="spcl_shlD_price"><? echo $price_arr['base_price']?></span> 
																		  <?  }
																		  if($price_arr['discounted_price']){?>
																		  <span class="spcl_shlD_offerprice" ><? echo $price_arr['discounted_price'];?></span> 
																<?  }
																}?>		  
																  </div>
															</div>
														</td>
														<td align="left" valign="middle" class="spcl_shlD_pdt_info_td">
														   <div> 
																			 <span class="spcl_shlD_pdt_info_td_link"><?php show_moreinfo($row_prod,'')?></span> 
																			<span class="spcl_shlD_pdt_info_td_link">
																				<?php
																			$class_arr 					= array();
																			$class_arr['ADD_TO_CART']	= '';
																			$class_arr['PREORDER']			= '';
																			$class_arr['ENQUIRE']			= '';
																			show_addtocart($row_prod,$class_arr,$frm_name,false,'','','',1)
																		  ?>
																			</span> 
															</div>
														</td>
													  </tr>
													</table>
													</form>
													<?php 
													}
												?>
												</td>
                                              </tr>
                                            </table>
									<?php	
									break;
									case '3row': // case of three in a row for christmas  christmas_1rowtableB
									if($_REQUEST['req']=='')
									{
									?>
									 
									<table width="100%" border="0" cellpadding="0" cellspacing="0" class="spcl_shlA_table">
										<tr>
										<td class="spcl_shlA_hdr" colspan="3"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="spcl_shlA_hdr_table">
										<tr>
										<td align="left" valign="middle"><?php
										if($cur_title)
										{
										?>    <span class="shlA_hdr_left"><span><?php echo $cur_title?></span></span>
										
										<? }?>
										<td align="right" valign="middle">
											<?
											if ($tot_cnt>0 and ($_REQUEST['req']=='') && $tot_cnt > $show_max)
											{
											?><a href="<?php  url_shelf_all($shelfData['shelf_id'],$shelfData['shelf_name'],-1)?>" class="spcl_shlA_showall" title="<?php echo $Captions_arr['COMMON']['SHOW_ALL']?>"><?php echo $Captions_arr['COMMON']['SHOW_ALL']?></a>
											<?
											}
											?>
										</td>
										<td align="right" valign="middle">&nbsp;</td>
										</tr>
										
										</table></td>
										</tr>
										<?php
										$desc = stripslashes(trim($shelfData['shelf_description']));
										if($desc!='' and $desc!='&nbsp;')
										{
										?>
											<tr>
												<td colspan="3" class="christmas_proddesBB" align="left"><?php echo $desc?></td>
											</tr>
										<?php		
										}
										if ($tot_cnt>0 and ($_REQUEST['req']!=''))
										{
										?>
										<tr>
											<td colspan="3" align="center" class="pagingcontainertd">
											<?php 
												$path = '';
													$query_string .= "";
													paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr); 	
											?>												</td>
										</tr>
										<?php
										}
										?>	
										<td class="spcl_shlA_pdt">
										<table width="100%" border="0" cellpadding="0" cellspacing="0" class="spcl_shlA_pdt_table">
										<?php
										$max_col = 3;
										$cur_col = 0;
										$prodcur_arr = array();
										while($row_prod = $db->fetch_array($ret_prod))
										{
											$prodcur_arr[] = $row_prod;
											//##############################################################
											// Showing the title, description and image part for the product
											//##############################################################
										if($cur_col == 0)
										{
										echo "<tr>";
										}
										$cur_col ++;
										?>
										<td align="center" valign="top" class="spcl_shlA_pdt_table_td">
										<table width="100%" border="0" cellspacing="0" cellpadding="0" class="spcl_shlA_pdt_table_inner">
										<tr>
										<td align="center" valign="middle" class="spcl_shlA_pdt_image_td">
										<div class="spcl_shlA_pdt_image_div">
										<?php
										if ($shelfData['shelf_showimage']==1)// Check whether description is to be displayed
															{
													?>
															<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
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
															
														
												<?php
													}
													?>
										</div>
										
										<?php $frm_name = uniqid('shelf_'); ?>
										<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
														<input type="hidden" name="fpurpose" value="" />
														<input type="hidden" name="fproduct_id" value="" />
														<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
														<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
										<div class="spcl_shlA_pdt_buy_div" ><?php
																	$class_arr 					= array();
																	$class_arr['ADD_TO_CART']	= 'spcl_shlA_pdt_buy';
																	$class_arr['PREORDER']			= 'spcl_shlA_pdt_buy';
																	$class_arr['ENQUIRE']			= 'spcl_shlA_pdt_buy';
																	show_addtocart($row_prod,$class_arr,$frm_name,false,'','','',1);
																
																?>
																</div></form>
										</td>
										</tr>
										<tr>
										<td align="center" valign="middle" class="spcl_shlA_pdt_name_td">
										<?php
										if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
										{
										?>
										<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="spcl_shlA_pdt_link"><?php echo stripslashes($row_prod['product_name'])?></a>
										<? }?></td>
										</tr>
										<tr>
										<td align="center" valign="middle" class="spcl_shlA_pdt_price_td">
										<?php
										if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
										{
										$price_arr =  show_Price($row_prod,array(),'shelfcenter_1',false,3);
										
										?>
										<span class="spcl_shlA_price"><? echo $price_arr['base_price']?></span>
										<? }?>
										</td>
										</tr>
										</table>
										</td>
										<?php
													if($cur_col>=$max_col)
													{
													$cur_col =0;
													echo "</tr>";
													
													}
										}
										if($cur_col<$max_col)
												{
												   if($cur_col!=0)
													{ 
														$cur_td=0;
														echo "<td colspan='".($max_col-$cur_col)."'>&nbsp;</td>";
														echo "</tr>";
													} 
												}
										?>
										</table></td>
									</table>
							<?php	
							        }//End of home page check
									else
									{
									?>
									<table border="0" cellpadding="0" cellspacing="0" class="shelfAtable">
										<?php 
										if($cur_title)
										{
										?>
											<tr>
												<td colspan="3" class="shelfAheader" align="left"><?php echo $cur_title?></td>
											</tr>
										<?php
										}
										$desc = stripslashes(trim($shelfData['shelf_description']));
										if($desc!='' and $desc!='&nbsp;')
										{
									?>
											<tr>
												<td colspan="3" class="shelfAproddes" align="left"><?php echo $desc?></td>
											</tr>
									<?php		
										}
										if ($tot_cnt>0 and ($_REQUEST['req']!=''))
										{
										?>
											<tr>
												<td colspan="3" class="pagingcontainertd" align="center">
												<?php 
													$path = '';
														$query_string .= "";
														paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr); 	
												?>	
												</td>
											</tr>
										<?php
										}
										?>	
										<?php
											$max_col = 3;
											$cur_col = 0;
											$prodcur_arr = array();
											while($row_prod = $db->fetch_array($ret_prod))
											{
												$prodcur_arr[] = $row_prod;
												//##############################################################
												// Showing the title, description and image part for the product
												//##############################################################
										if($cur_col == 0)
										{
										  echo "<tr>";
										}
										$cur_col ++;

										?>
											<td class="newshelfAtabletd" onmouseover="this.className='newshelfAtabletd_hover'" onmouseout="this.className='newshelfAtabletd'" align="left" valign="top">												
													<ul class="shelfAul">
														<?php
															if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
															{
														?>
																<li><h2 class="shelfAprodname"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h2></li>
															
														<?php
															}
															if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
															{
														?>
																<li>
																	<?php
																		$price_class_arr['ul_class'] 		= 'shelfBul';
																		$price_class_arr['normal_class'] 	= 'shelfAnormalprice';
																		$price_class_arr['strike_class'] 	= 'shelfAstrikeprice';
																		$price_class_arr['yousave_class'] 	= 'shelfAyousaveprice';
																		$price_class_arr['discount_class'] 	= 'shelfAdiscountprice';
																		echo show_Price($row_prod,$price_class_arr,'shelfcenter_3');
																		show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
																	?>	
																</li>
														<?php
															}
															if ($shelfData['shelf_showimage']==1)// Check whether description is to be displayed
															{
														?>		
																<li class="shelfimg">
																	<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
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
																	
																</li>
																
														<?php
															}
														if ($shelfData['shelf_showdescription']==1)// Check whether description is to be displayed
														{
														?>
															<li><h6 class="shelfAproddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6></li>
														<?php
														}
														?>	
													</ul>
														<?php show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points?>
											<?php
												
													
														$frm_name = uniqid('shelf_');
													?>
															<?php if($comp_active)  {
															dislplayCompareButton($row_prod['product_id']);
															}?>
															<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
															<input type="hidden" name="fpurpose" value="" />
															<input type="hidden" name="fproduct_id" value="" />
															<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
															<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
															
															<div class="infodiv">
																<div class="infodivleft"><?php show_moreinfo($row_prod,'infolink')?></div>
																<div class="infodivright">
																<?php
																	$class_arr 					= array();
																	$class_arr['ADD_TO_CART']	= 'quantity_infolink';
																	$class_arr['PREORDER']		= 'quantity_infolink';
																	$class_arr['ENQUIRE']		= 'quantity_infolink';
																	show_addtocart($row_prod,$class_arr,$frm_name)
																?>
																</div>
															</div>
															</form>
															</td>
											<?php
														if($cur_col>=$max_col)
														{
														$cur_col =0;
														echo "</tr>";
														}
														// done to handle the case of breaking to new linel
												}
											// If in case total product is less than the max allowed per row then handle that situation
								                	if($cur_col<$max_col)
													{
													   if($cur_col!=0)
														{ 
															$cur_td=0;
															echo "<td colspan='".($max_col-$cur_col)."'>&nbsp;</td>";
															echo "</tr>";
														} 
													}
											?>
										</table>
									<?
									}
									break;
								};
							}
							elseif($shelfData['shelf_currentstyle']=='new') // case of new year layout
							{
								switch($shelfData['shelf_displaytype'])
								{
									case '1row': // case of one in a row for new year
									case 'dropdown':
									case 'list':
									?>
								<table width="100%" border="0" cellpadding="0" cellspacing="0" class="spcl_shlC_table">
								<tr>
								<td class="spcl_shlC_hdr"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="spcl_shlC_hdr_table">
									<tr>
									  <td align="left" valign="middle">
									  <?php
									 if($cur_title)
										{
									?>	
									  <span class="shlC_hdr_left"><span><? echo $cur_title;?></span></span>
									  <? }?>
									  </td>
									   <td align="right" valign="middle">
									   <?
														if ($tot_cnt>0 and ($_REQUEST['req']=='') && $tot_cnt > $show_max)
														{
														?><a href="<?php  url_shelf_all($shelfData['shelf_id'],$shelfData['shelf_name'],-1)?>" class="spcl_shlC_showall" title="<?php echo $Captions_arr['COMMON']['SHOW_ALL']?>"><?php echo $Captions_arr['COMMON']['SHOW_ALL']?></a>
														<?
														}
														?>
									  </td>
									</tr>
								</table></td>
								</tr>
								<?php
								$desc = stripslashes(trim($shelfData['shelf_description']));
								if($desc!='' and $desc!='&nbsp;')
								{
								?>
								<tr>
									<td colspan="3" class="newyear_proddes" align="left"><?php echo $desc?></td>
								</tr>
								<?php		
								}
								if ($tot_cnt>0 and ($_REQUEST['req']!=''))
								{
								?>
								<tr>
								<td colspan="3" class="pagingcontainertd" align="center">
								<?php 
									$path = '';
									//show_paging($path,$_REQUEST[$pg_variable],$start_arr['counterstart'],$tot_cnt,$prodperpage,$pg_variable,'')
										$query_string .= "";
										paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr); 	
								?>
								</td>
								</tr>
								<?php
								}
								?>	
								<tr>
								<td class="spcl_shlC_pdt"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="spcl_shlC_pdt_table">
									  <?php
								$max_col = 2;
								$cur_col = 0;
								$prodcur_arr = array();
								while($row_prod = $db->fetch_array($ret_prod))
								{
								$prodcur_arr[] = $row_prod;
								//##############################################################
								// Showing the title, description and image part for the product
								//##############################################################
									if($cur_col == 0)
										{
										echo "<tr>";
										}
										$cur_col ++;
										?>
									  <td align="center" valign="top" class="spcl_shlC_pdt_table_td">
									  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spcl_shlC_pdt_table_inner">
										  <tr>
											<td align="center" valign="middle" class="spcl_shlC_pdt_image_td">
											<div>
											<?php
											if ($shelfData['shelf_showimage']==1)// Check whether description is to be displayed
											{
											 $pass_type='image_gallerythumbpath';
											?>
											<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
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
											<?php
											}
											$frm_name = uniqid('shelf_');
									?>
									</div></td>
											<td align="center" valign="top" class="spcl_shlC_pdt_price_td">
											<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
											<input type="hidden" name="fpurpose" value="" />
											<input type="hidden" name="fproduct_id" value="" />
											<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
											<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
											<div class="spcl_shlC_pdt_price_con">
											  <div class="spcl_shlC_pdt_price_div">
										  <?php
											if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
											{
											?>
											  <span class="spcl_shlC_name"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><? echo stripslashes($row_prod['product_name']);?></a></span> 
										 <?php 
										   } 
										   ?>
											  <?php
												if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
												{
													$price_arr =  show_Price($row_prod,array(),'shelfcenter_1',false,3);
													
														echo $price_arr['base_price'];
													if($price_arr['discounted_price'])
													{
													?><span class="spcl_shlC_offerprice">
														<?php
														echo $price_arr['discounted_price'];
														?>
														</span>
														<?php
													}
												}?>
											
											  </div>
											   <div class="spcl_shlC_pdt_buy_div" >
											  <?php
															$class_arr 							= array();
															$class_arr['ADD_TO_CART']	= 'spcl_shlC_pdt_buy';
															$class_arr['PREORDER']			= 'spcl_shlC_pdt_buy';
															$class_arr['ENQUIRE']			= 'spcl_shlC_pdt_buy';
															show_moreinfo($row_prod,'spcl_shlC_pdt_buy');
															//show_addtocart($row_prod,$class_arr,$frm_name,false,'','','',1)
														?>
											</div>                                                            
										  </form>	</td>
										  </tr>
									  </table></td>
										<?php
										if($cur_col>=$max_col)
										{
										$cur_col =0;
										echo "</tr>";
										
										}
										}
										if($cur_col<$max_col)
										{
										if($cur_col!=0)
										{ 
											$cur_td=0;
											echo "<td colspan='".($max_col-$cur_col)."'>&nbsp;</td>";
											echo "</tr>";
										} 
										}
										?>
								</table></td>
								</tr>
								</table>
									<?php	
									break;
									case '3row': // case of three in a row for new year
									if($_REQUEST['req']=='')
									{
									?>
										<table width="100%" border="0" cellpadding="0" cellspacing="0" class="spcl_shlB_table">
                                            <tr>
                                                <td class="spcl_shlB_hdr"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="spcl_shlB_hdr_table">
                                                    <tr>
													
                                                      <td align="left" valign="middle" class="shlB_hdr_left">
													<?php
													 if($cur_title)
														{
													?>	
														<?php echo $cur_title?>
													 <? }?>
													 </td>
													 <td align="right" valign="middle">
													 <?
														if ($tot_cnt>0 and ($_REQUEST['req']=='') && $tot_cnt > $show_max)
														{
														?><a href="<?php  url_shelf_all($shelfData['shelf_id'],$shelfData['shelf_name'],-1)?>" class="spcl_shlB_showall" title="<?php echo $Captions_arr['COMMON']['SHOW_ALL']?>"><?php echo $Captions_arr['COMMON']['SHOW_ALL']?></a>
														<?
														}
														?>
                                                      </td>
                                                    </tr>
                                                </table></td>
                                              </tr>
											  <?php
											  $desc = stripslashes(trim($shelfData['shelf_description']));
										if($desc!='' and $desc!='&nbsp;')
										{
										?>
												<tr>
													<td colspan="3" class="newyear_proddesB" align="left"><?php echo $desc?></td>
												</tr>
										<?php		
										}
										if ($tot_cnt>0 and ($_REQUEST['req']!=''))
										{
									?>
											<tr>
												<td colspan="3" class="pagingcontainertd" align="center">
												<?php 
													$path = '';
													//show_paging($path,$_REQUEST[$pg_variable],$start_arr['counterstart'],$tot_cnt,$prodperpage,$pg_variable,'')
													$query_string .= "";
													paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr); 	
												?>	
												</td>
											</tr>
									<?php
										}
									?>	
                                              <tr>
                                                <td class="spcl_shlB_pdt"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="spcl_shlB_pdt_table">
                                                   <?php
											$max_col = 4;
											$cur_col = 0;
											$prodcur_arr = array();
											while($row_prod = $db->fetch_array($ret_prod))
											{
												$prodcur_arr[] = $row_prod;
												//##############################################################
												// Showing the title, description and image part for the product
												//##############################################################
													if($cur_col == 0)
														{
														echo "<tr>";
														}
														$cur_col ++;
														?>
                                                      <td align="center" valign="top" class="spcl_shlB_pdt_table_td">
													  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spcl_shlB_pdt_table_inner">
                                                          <tr>
                                                            <td align="center" valign="middle" class="spcl_shlB_pdt_name_td"><div>
															<?php
																if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
																{
															?>
															 <a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="spcl_shlB_pdt_link"><?php echo stripslashes($row_prod['product_name'])?></a>
															 <? }?>
															 </div></td>
                                                          </tr>
                                                          <tr>
                                                            <td align="center" valign="top" class="spcl_shlB_pdt_image_td"><div>
															
															<?php
												if ($shelfData['shelf_showimage']==1)// Check whether description is to be displayed
														{ 
														 $pass_type='image_gallerythumbpath';
											?>
															<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
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
													<?php
														}
													$frm_name = uniqid('shelf_');
													?>
															 </div></td>
                                                          </tr>
                                                          <tr>
                                                            <td align="center" valign="top" class="spcl_shlB_pdt_price_td">
															<div class="spcl_shlB_pdt_price_con">
															<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
															<input type="hidden" name="fpurpose" value="" />
															<input type="hidden" name="fproduct_id" value="" />
															<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
															<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
                                                              <div class="spcl_shlB_pdt_buy_div" >
															  <?php
																			$class_arr 							= array();
																			$class_arr['ADD_TO_CART']	= 'spcl_shlB_pdt_buy';
																			$class_arr['PREORDER']			= 'spcl_shlB_pdt_buy';
																			$class_arr['ENQUIRE']			= 'spcl_shlB_pdt_buy';
																			show_addtocart($row_prod,$class_arr,$frm_name,false,'','','',1)
																		?>
															 </div>
															
                                                              <div class="spcl_shlB_pdt_price_div"> 
															<?php
															if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
															{
															 $price_arr =  show_Price($row_prod,array(),'shelfcenter_1',false,3);
																         if($price_arr['base_price']){?>
														                   <span class="spcl_shlB_baseprice"><? echo $price_arr['base_price']?></span> 
																		  <?  }
																		  if($price_arr['discounted_price']){?>
																		  <span class="spcl_shlB_offerprice" ><? echo $price_arr['discounted_price'];?></span> 
																<?  }
															}
															?></div>
															  </form>
                                                            </div></td>
                                                          </tr>
                                                      </table></td>
                                                  <?php
													if($cur_col>=$max_col)
													{
													$cur_col =0;
													echo "</tr>";
													
													}
												}
												if($cur_col<$max_col)
														{
														   if($cur_col!=0)
															{ 
																$cur_td=0;
																echo "<td colspan='".($max_col-$cur_col)."'>&nbsp;</td>";
																echo "</tr>";
															} 
														}
												?>
                                                </table></td>
                                              </tr>
                                            </table>
										
									<?php
									}
									else
									    {
										?>
										<table border="0" cellpadding="0" cellspacing="0" class="shelfAtable">
										<?php 
										if($cur_title)
										{
										?>
											<tr>
												<td colspan="3" class="shelfAheader" align="left"><?php echo $cur_title?></td>
											</tr>
										<?php
										}
										$desc = stripslashes(trim($shelfData['shelf_description']));
										if($desc!='' and $desc!='&nbsp;')
										{
									?>
											<tr>
												<td colspan="3" class="shelfAproddes" align="left"><?php echo $desc?></td>
											</tr>
									<?php		
										}
										if ($tot_cnt>0 and ($_REQUEST['req']!=''))
										{
										?>
											<tr>
												<td colspan="3" class="pagingcontainertd" align="center">
												<?php 
													$path = '';
														$query_string .= "";
														paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr); 	
												?>	
												</td>
											</tr>
										<?php
										}
										?>	
										<?php
											$max_col = 3;
											$cur_col = 0;
											$prodcur_arr = array();
											while($row_prod = $db->fetch_array($ret_prod))
											{
												$prodcur_arr[] = $row_prod;
												//##############################################################
												// Showing the title, description and image part for the product
												//##############################################################
										if($cur_col == 0)
										{
										  echo "<tr>";
										}
										$cur_col ++;

										?>
											<td class="newshelfAtabletd" onmouseover="this.className='newshelfAtabletd_hover'" onmouseout="this.className='newshelfAtabletd'" align="left" valign="top">												
													<ul class="shelfAul">
														<?php
															if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
															{
														?>
																<li><h2 class="shelfAprodname"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h2></li>
															
														<?php
															}
															if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
															{
														?>
																<li>
																	<?php
																		$price_class_arr['ul_class'] 		= 'shelfBul';
																		$price_class_arr['normal_class'] 	= 'shelfAnormalprice';
																		$price_class_arr['strike_class'] 	= 'shelfAstrikeprice';
																		$price_class_arr['yousave_class'] 	= 'shelfAyousaveprice';
																		$price_class_arr['discount_class'] 	= 'shelfAdiscountprice';
																		echo show_Price($row_prod,$price_class_arr,'shelfcenter_3');
																		show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
																	?>	
																</li>
														<?php
															}
															if ($shelfData['shelf_showimage']==1)// Check whether description is to be displayed
															{
														?>		
																<li class="shelfimg">
																	<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
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
																	
																</li>
																
														<?php
															}
														if ($shelfData['shelf_showdescription']==1)// Check whether description is to be displayed
														{
														?>
															<li><h6 class="shelfAproddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6></li>
														<?php
														}
														?>	
													</ul>
														<?php show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points?>
											<?php
												
													
														$frm_name = uniqid('shelf_');
													?>
															<?php if($comp_active)  {
															dislplayCompareButton($row_prod['product_id']);
															}?>
															<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
															<input type="hidden" name="fpurpose" value="" />
															<input type="hidden" name="fproduct_id" value="" />
															<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
															<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
															
															<div class="infodiv">
																<div class="infodivleft"><?php show_moreinfo($row_prod,'infolink')?></div>
																<div class="infodivright">
																<?php
																	$class_arr 					= array();
																	$class_arr['ADD_TO_CART']	= 'quantity_infolink';
																	$class_arr['PREORDER']		= 'quantity_infolink';
																	$class_arr['ENQUIRE']		= 'quantity_infolink';
																	show_addtocart($row_prod,$class_arr,$frm_name)
																?>
																</div>
															</div>
															</form>
															</td>
											<?php
														if($cur_col>=$max_col)
														{
														$cur_col =0;
														echo "</tr>";
														
														}

														// done to handle the case of breaking to new linel
												}
											// If in case total product is less than the max allowed per row then handle that situation
								                	if($cur_col<$max_col)
													{
													   if($cur_col!=0)
														{ 
															$cur_td=0;
															echo "<td colspan='".($max_col-$cur_col)."'>&nbsp;</td>";
															echo "</tr>";
														} 
													}
											?>
											
										</table>
										<?php
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
