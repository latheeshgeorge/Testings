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
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr;
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
						
						// Get the list of products to be shown in current shelf
						$sql_prod = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
										a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
										a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
										product_total_preorder_allowed,a.product_applytax,a.product_shortdesc,a.product_bonuspoints,
										a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,a.product_variablesaddonprice_exists,
										a.product_variablecomboprice_allowed,a.product_variablecombocommon_image_allowed,a.default_comb_id,
											a.price_normalprefix,a.price_normalsuffix, a.price_fromprefix, a.price_fromsuffix,a.price_specialofferprefix, a.price_specialoffersuffix, 
											a.price_discountprefix, a.price_discountsuffix, a.price_yousaveprefix, a.price_yousavesuffix,a.price_noprice            
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
						$totcnt = $db->num_rows($ret_prod);

						if ($db->num_rows($ret_prod))
						{
							$comp_active = isProductCompareEnabled();
							$pass_type = get_default_imagetype('midshelf');
							// Number of result to display on the page, will be in the LIMIT of the sql query also
							$querystring = ""; // if any additional query string required specify it over here
							
							if($shelfData['shelf_currentstyle']=='nor') // case of normal design layout
							{
								switch($shelfData['shelf_displaytype'])
								{
									case 'dropdown':
									case 'list':
									case '1row': // case of one in a row for normal
									if ($_REQUEST['req']=='')
									{
								
											if($cur_title){
											?>
												<div class="mid_shelfB_name"><?php echo $cur_title?></div>
											<?php
											}
											$desc = trim($shelfData['shelf_description']);
											if($desc!='' and $desc!='&nbsp;')
											{
											?>
												<div class="shelfBproddes_top"><?php echo $desc?></div>
											<?php		
											}
											?> <?php
											if ($tot_cnt>0 and ($_REQUEST['req']!=''))
											{  
											?>
											<div  class="pagingcontainertd" align="center">
												<?php 
												$path = '';
												$query_string .= "";
												paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr); 	
												?>	
											</div>
											<?php
											}
											?>	
											<?php
											while($row_prod = $db->fetch_array($ret_prod))
											{
												?>
										        <div class="mid_shelfB">
												<table width="100%" border="0" cellpadding="0" cellspacing="0"  class="mid_shelfB_table">
												<?php
												if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
												{
												?>
												<tr>
													<td class="mid_shelfB_top_lf">&nbsp;</td>
													<td class="mid_shelfB_top_mid"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="prod_infolink"><?php echo stripslashes($row_prod['product_name'])?></a></td>
													<td class="mid_shelfB_top_rt">&nbsp;</td>
												</tr>
												<? }?>
												<tr>
														<td colspan="3" class="mid_shelfB_mid">
														<div class="shelfBimg">
															<?php
															if ($shelfData['shelf_showimage']==1)// Check whether description is to be displayed
															{
															?>		
																<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="prod_infolink">
																<?php
																	// Calling the function to get the type of image to shown for current 
																	//$pass_type = get_default_imagetype('midshelf');
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
															<?php if($comp_active)  {
																dislplayCompareButton($row_prod['product_id']);
															}?>
														</div> 
														<div class="shelfB_cnts">
																<?php 
																if ($shelfData['shelf_showdescription']==1)// Check whether description is to be displayed
																{
																	?>		 
																	<h6 class="shelfBproddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6>
																	<? 
																}
																?>	              
																<ul class="shelfBul">
																<?php
																if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
																{ 
																	$price_class_arr['ul_class'] 		= 'shelfBul';
																	$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
																	$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
																	$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
																	$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
																	echo show_Price($row_prod,$price_class_arr,'shelfcenter_1');
																	show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
																	show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points
																}
																	?>
																
																</ul>	
													   </div>
													  </td>
												</tr>
												<tr>
												<td class="mid_shelfB_btm_lf">&nbsp;</td>
												<td class="mid_shelfB_btm_mid">
													<?php
													$frm_name = uniqid('shelf_');
													?>	
														<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
														<input type="hidden" name="fpurpose" value="" />
														<input type="hidden" name="fproduct_id" value="" />
														<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
														<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
														<div class="infodiv">
															<div class="infodivleft">		<?php show_moreinfo($row_prod,'infolink')?>	</div>
															<div class="infodivright"><?php
																$class_arr 					= array();
																$class_arr['ADD_TO_CART']	= 'quantity_infolink';
																$class_arr['PREORDER']		= 'quantity_infolink';
																$class_arr['ENQUIRE']		= 'quantity_infolink';
																show_addtocart($row_prod,$class_arr,$frm_name)
															?> </div>
														</div>
														</form>
												  </td>
												<td class="mid_shelfB_btm_rt">&nbsp;</td>
												</tr>
												</table>
												
										</div>
										 <?php 
										 }
										 } //end of if for home page checking
										 else
										 {
										  if($cur_title){
											?>
												<div class="mid_shelfB_name"><?php echo $cur_title?></div>
											<?php
											}
											$desc = trim($shelfData['shelf_description']);
											if($desc!='' and $desc!='&nbsp;')
											{
											?>
												<div class="shelfBproddes_top"><?php echo $desc?></div>
											<?php		
											}
											?> <?php
											if ($tot_cnt>0 and ($_REQUEST['req']!=''))
											{  
											?>
											 	<div class="pagingcontainertd_outer" align="center">
												<div class="pagingcontainertd_lf" align="center">
													<?php 
													 paging_show_totalcount($tot_cnt,'Products',$start_var['pg'],$start_var['pages']);
													 ?>
													 
													</div>
												<div class="pagingcontainertd_rt" >
													 <div class="pagingcontainertd" align="center">
													<?
													$path = '';
													//show_paging($path,$_REQUEST[$pg_variable],$start_arr['counterstart'],$tot_cnt,$prodperpage,$pg_variable,'')
													$query_string .= "";
													paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr,0); 	
													?>	
												    </div>
												 </div>
													
												</div>
											
											<?php
											}
											?>	
											<?php
											while($row_prod = $db->fetch_array($ret_prod))
											{
												?>
										        <div class="mid_shelfB">
												<table width="100%" border="0" cellpadding="0" cellspacing="0"  class="mid_shelfB_table">
												<?php
												if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
												{
												?>
												<tr>
													<td class="mid_shelfB_top_lf">&nbsp;</td>
													<td class="mid_shelfB_top_mid"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="prod_infolink"><?php echo stripslashes($row_prod['product_name'])?></a></td>
													<td class="mid_shelfB_top_rt">&nbsp;</td>
												</tr>
												<? }?>
												<tr>
														<td colspan="3" class="mid_shelfB_mid">
														<div class="shelfBimg">
															<?php
															if ($shelfData['shelf_showimage']==1)// Check whether description is to be displayed
															{
															?>		
																<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="prod_infolink">
																<?php
																	// Calling the function to get the type of image to shown for current 
																	//$pass_type = get_default_imagetype('midshelf');
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
															<?php if($comp_active)  {
																dislplayCompareButton($row_prod['product_id']);
															}?>
														</div> 
														<div class="shelfB_cnts">
																<?php 
																if ($shelfData['shelf_showdescription']==1)// Check whether description is to be displayed
																{
																	?>		 
																	<h6 class="shelfBproddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6>
																	<? 
																}
																?>	              
																<ul class="shelfBul">
																<?php
																if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
																{ 
																	$price_class_arr['ul_class'] 		= 'shelfBul';
																	$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
																	$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
																	$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
																	$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
																	echo show_Price($row_prod,$price_class_arr,'shelfcenter_1');
																	show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
																	show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points
																}
																	?>
																
																</ul>	
													   </div>
													  </td>
												</tr>
												<tr>
												<td class="mid_shelfB_btm_lf">&nbsp;</td>
												<td class="mid_shelfB_btm_mid">
													<?php
													$frm_name = uniqid('shelf_');
													?>	
														<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
														<input type="hidden" name="fpurpose" value="" />
														<input type="hidden" name="fproduct_id" value="" />
														<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
														<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
														<div class="infodiv">
															<div class="infodivleft">		<?php show_moreinfo($row_prod,'infolink')?>	</div>
															<div class="infodivright"><?php
																$class_arr 					= array();
																$class_arr['ADD_TO_CART']	= 'quantity_infolink';
																$class_arr['PREORDER']		= 'quantity_infolink';
																$class_arr['ENQUIRE']		= 'quantity_infolink';
																show_addtocart($row_prod,$class_arr,$frm_name)
															?> </div>
														</div>
														</form>
												  </td>
												<td class="mid_shelfB_btm_rt">&nbsp;</td>
												</tr>
												</table>
												
										</div>
										 <?php 
										 }
										 }
									break;
									case '2row': // case of three in a row for normal
									if($cur_title)
									{
									?>
										<div class="mid_shelfA_name"><?php echo $cur_title?></div>
									<?php
									}
									if ($tot_cnt>0 and ($_REQUEST['req']!=''))
									{
									?>
									
												<div class="pagingcontainertd_outer" align="center">
												<div class="pagingcontainertd_lf" align="center">
													<?php 
													 paging_show_totalcount($tot_cnt,'Products',$start_var['pg'],$start_var['pages']);
													 ?>
													 
													</div>
													<div class="pagingcontainertd_rt">
													 <div class="pagingcontainertd" align="center">
													<?
													$path = '';
													//show_paging($path,$_REQUEST[$pg_variable],$start_arr['counterstart'],$tot_cnt,$prodperpage,$pg_variable,'')
													$query_string .= "";
													paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr,0); 	
													?>	
												 </div>
												 </div>
													
												</div>
									<?php
									}
									$desc = trim($shelfData['shelf_description']);
									if($desc!='' and $desc!='&nbsp;')
									{
									?>
									<div class="shelfBproddes_top" align="left"><?php echo $desc?></div>
									<?php		
									}
									?>
									<table width="100%" border="0" cellpadding="0" cellspacing="0"  class="mid_shelfA">
									<tr>
									<?php
									if($_REQUEST['req']!='')
										$max_col = 3;
									else
										$max_col = 2;
									$cur_col = 0;
									$prodcur_arr = array();
									while($row_prod = $db->fetch_array($ret_prod))
									{
									$cur_col++;
									$prodcur_arr[] = $row_prod;
									//##############################################################
									// Showing the title, description and image part for the product
									//##############################################################
									
									if($cur_col%2==0)
									{
									$cls ='mid_shelfA_right'; 
									}
									else
									$cls ='mid_shelfA_left'; 
									?>
									<td class="<?=$cls?>" valign="top">
									<table width="100%" border="0" cellpadding="0" cellspacing="0"  class="mid_shelfA_table">
										<tr>
											<td class="mid_shelfA_top_lf">&nbsp;</td>
											<td class="mid_shelfA_top_mid"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="prod_infolink"><?php echo stripslashes($row_prod['product_name'])?></a></td>
											<td class="mid_shelfA_top_rt">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" class="mid_shelfA_mid" align="center">
											<ul class="shelfAul">
											<?php
											if ($shelfData['shelf_showimage']==1)// Check whether description is to be displayed
											{
											?>													
											<li class="shelfAimg">
											<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
																				<?php
																					// Calling the function to get the type of image to shown for current 
																					//$pass_type = get_default_imagetype('midshelf');
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
																				<?php if($comp_active)  {
														dislplayCompareButton($row_prod['product_id']);
																}?>
											
											</li>
											<?php
											}
											if ($shelfData['shelf_showdescription']==1)// Check whether description is to be displayed
											{
											?>        
											<li><h6 class="shelfAproddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6></li>                     
											<?
											}
											
											if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
											{
											?>
											<?php
											$price_class_arr['ul_class'] 		= 'shelfBul';
											$price_class_arr['normal_class'] 	= 'shelfAnormalprice';
											$price_class_arr['strike_class'] 	= 'shelfAstrikeprice';
											$price_class_arr['yousave_class'] 	= 'shelfAyousaveprice';
											$price_class_arr['discount_class'] 	= 'shelfAdiscountprice';
											echo show_Price($row_prod,$price_class_arr,'shelfcenter_3');
											show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
											}
											?>
											</ul>	
											</td>
										</tr>
										<tr>
										<td class="mid_shelfA_btm_lf">&nbsp;</td>
										<?php $frm_name = uniqid('shelf_'); ?>
										<td class="mid_shelfA_btm_mid">
										
								<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
									<input type="hidden" name="fpurpose" value="" />
									<input type="hidden" name="fproduct_id" value="" />
									<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
									<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
										<div class="infodiv_shlfA">
										<div class="infodivleft_shlfA"><?php show_moreinfo($row_prod,'infolink')?></div>
										<div class="infodivright_shlfA">
										<?php
										$class_arr 					= array();
										$class_arr['ADD_TO_CART']	= 'quantity_infolink';
										$class_arr['PREORDER']		= 'quantity_infolink';
										$class_arr['ENQUIRE']		= 'quantity_infolink';
										show_addtocart($row_prod,$class_arr,$frm_name)
										?></div>
										</div></form></td>
										<td class="mid_shelfA_btm_rt">&nbsp;</td>
										</tr>
									</table>
									</td>
									<?
									if($totcnt==$cur_col)
									{ 
									echo "</tr>";
									}
									else
									{
									if ($cur_col%2==0)
									{
									echo "</tr>";
									//##############################################################
									// Showing the more info and add to cart links after each row in 
									// case of breaking to new row while looping
									//##############################################################
									echo "<tr>";
									}
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
									case 'dropdown':
									case 'list':
									case '1row': // case of one in a row for normal
									?>
									<div class="mid_spclA_pdt">
									 <table width="100%" border="0" cellpadding="0" cellspacing="0" class="mid_spclA_top_table" >
									<tr>
									  <td class="mid_spclA_pdt_top_lf">&nbsp;</td>
									  <td class="mid_spclA_pdt_top_mid">
									  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="mid_spclA_shlf_name">
										<tr>
										  <td width="7%" class="mid_spclA_shlf_name_left">&nbsp;</td>
										  <td width="59%" class="mid_spclA_shlf_name_mid">
										  <?php
											if($cur_title){
											?>
												<?php echo $cur_title?>
											<?php
											}
											?>
										  </td>
										  <td width="34%" class="mid_spclA_shlf_name_right">&nbsp;</td>
										</tr>
									  </table></td>
									  <td class="mid_spclA_pdt_top_rt">&nbsp;</td>
									</tr>
									<?php
									$desc = trim($shelfData['shelf_description']);
									if($desc!='' and $desc!='&nbsp;')
									{
									?>		
										 <tr>
										 <td colspan="3" class="mid_spclA_pdt_mid">
										 <?php echo stripslashes($desc);?>
										 </td>
										</tr>
									<?php
										}
									?>
									<tr>
								     <td colspan="3" class="mid_spclA_pdt_mid">
									 <?php
										while($row_prod = $db->fetch_array($ret_prod))
										{
									?>
											<table width="100%" border="0" cellspacing="5" cellpadding="0" class="mid_spclA_prod_table">
											<tr>
											<td align="left" valign="top" class="mid_spclA_prod_table_des">
												<?php
												if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
												{
												?>
												<div class="feat_pdt_name">
												<h2 class="mid_spclA_prodname"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h2>
												</div>
												<?php
												}
												if ($shelfData['shelf_showdescription']==1)// Check whether description is to be displayed
												{
													?>		 
													<div class="mid_spclA_proddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></div>
													<? 
												}
												?>	
												<table width="100%" border="0" cellspacing="5" cellpadding="0">
												<tr>
												<td width="44%" align="left" valign="top">
												<?php
													if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
													{ 
														$price_class_arr['ul_class'] 		= 'mid_spcl_price';
														$price_class_arr['normal_class'] 	= 'mid_spclA_normalprice';
														$price_class_arr['strike_class'] 	= 'mid_spclA_strikeprice';
														$price_class_arr['yousave_class'] 	= 'mid_spclA_yousaveprice';
														$price_class_arr['discount_class'] 	= 'mid_spclA_yousaveprice';
														echo show_Price($row_prod,$price_class_arr,'shelfcenter_1');
														show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
														show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points
													}
												?>
												</td>
												<td width="56%" align="left" valign="top">
												<?php 
													show_moreinfo($row_prod,'mid_spclA_info');
													$frm_name = uniqid('shelf_');
													?>	
														<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
														<input type="hidden" name="fpurpose" value="" />
														<input type="hidden" name="fproduct_id" value="" />
														<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
														<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
														<?php
															$class_arr 					= array();
															$class_arr['ADD_TO_CART']	= 'mid_spclA_buy';
															$class_arr['PREORDER']		= 'mid_spclA_buy';
															$class_arr['ENQUIRE']		= 'mid_spclA_buy';
															show_addtocart($row_prod,$class_arr,$frm_name,false,'','','',1);
														?> 
														</form>
												</td>
												</tr>
												</table>
											</td>
											<td align="center" class="mid_spclA_prod_table_img">
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
											</td>
											</tr>
											</table>
									<?php	
										}	
										if ($tot_cnt>0 and ($_REQUEST['req']!=''))
										{  
										?>
											<div class="shlfD_showall" >
											<?php 
											$path = '';
											$query_string .= "";
											paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr); 	
											?>	
											</div>
									<?php
										}
									?>	
									 </td>
										</tr>
										<tr>
										  <td class="mid_spclA_pdt_btm_lf">&nbsp;</td>
										  <td class="mid_spclA_pdt_btm_mid">&nbsp;</td>
										  <td class="mid_spclA_pdt_btm_rt">&nbsp;</td>
										</tr>
									  </table>		
									</div>
									<?php
									break;
									case '2row': // case of three in a row for christmas  christmas_1rowtableB
									?>
									<div class="n_spcl_shlf_new">
									<table width="100%" border="0" cellpadding="0" cellspacing="0" class="n_spcl_shlf_new_top_table" >
									<tr>
									<td class="n_spcl_shlf_new_top_lf">&nbsp;</td>
									<td class="n_spcl_shlf_new_top_mid">
									<?php
									if($cur_title)
									{
										echo $cur_title;
									}
									?>
									</td>
									<td class="n_spcl_shlf_new_top_rt">&nbsp;</td>
									</tr>
									<tr>
									<td colspan="3" class="n_spcl_shlf_new_mid">
									<?php
									$desc = trim($shelfData['shelf_description']);
									if($desc!='' and $desc!='&nbsp;')
									{
										echo stripslashes($desc);
									}
									?>
									</td>
									</tr>
									<tr>
									<td colspan="3" class="n_spcl_shlf_new_mid">
									<table width="100%" border="0" cellspacing="5" cellpadding="0" class="n_spcl_shlf_table_pdt">
									<?php
									$max_col = 3;
									$cur_col = 0;
									while($row_prod = $db->fetch_array($ret_prod))
									{
									//##############################################################
									// Showing the title, description and image part for the product
									//##############################################################
									if($cur_col==0)
										echo '<tr>';
									$cur_col++;
									?>
										<td class="n_spcl_shlf_table_pdt_td" valign="top">
										<table width="100%" border="0" cellspacing="0" cellpadding="0" class="n_spcl_shlf_table_pdt_table">
										<?php
										if ($shelfData['shelf_showimage']==1)// Check whether description is to be displayed
										{
										?>	
										<tr>
											<td align="center" valign="middle" class="n_spcl_shlf_table_pdt_table_img">
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
											</td>
										</tr>
										<?php
										}	
										if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
										{
										?>
										<tr>
											<td class="n_spcl_shlf_table_pdt_table_info" align="center" valign="middle">
											<?php
												$price_class_arr['ul_class'] 		= 'n_spcl_price';
												$price_class_arr['normal_class'] 	= 'n_spcl_normalprice';
												$price_class_arr['strike_class'] 	= 'n_spcl_strikeprice';
												$price_class_arr['yousave_class'] 	= 'n_spcl_yousaveprice';
												$price_class_arr['discount_class'] 	= 'n_spcl_yousaveprice';
												echo show_Price($row_prod,$price_class_arr,'shelfcenter_3');
												show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
											?>
											</td>
										</tr>
										<?php
										}
										?>
										</table>
										</td>
									<?
										if($cur_col>=$max_col)
										{
											$cur_col = 0;
											echo '</tr>';
										}
									}
									if($cur_col<$max_col)
										echo '</tr>';	
									?>
									</table>
									<?php
									if ($tot_cnt>0 and ($_REQUEST['req']!=''))
									{
									?>
										<div class="shlfB_showall">
										<?php 
											$path = '';
											$query_string .= "";
											paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr); 	
										?>
										</div>
									<?php
									}
									?>
									</td>
									</tr>
									<tr>
									<td class="n_spcl_shlf_new_btm_lf">&nbsp;</td>
									<td class="n_spcl_shlf_new_btm_mid">&nbsp;</td>
									<td class="n_spcl_shlf_new_btm_rt">&nbsp;</td>
									</tr>
									</table>
									
									</div>
<?php		
									break;
								};
							}
							elseif($shelfData['shelf_currentstyle']=='new') // case of new year layout
							{
								switch($shelfData['shelf_displaytype'])
								{
									case 'dropdown':
									case 'list':
									case '1row': // case of one in a row for normal
									 
									   if($_REQUEST['req']=='')
									   {
									   ?>
										<div class="mid_spcl_pdt">
										<table width="100%" border="0" cellpadding="0" cellspacing="0" class="mid_spcl_top_table" >
										<tr>
										<td class="mid_spcl_pdt_top_lf">&nbsp;</td>
										<td class="mid_spcl_pdt_top_mid">
										<?php   
										if($cur_title)
										{
											echo $cur_title;
										}
										?>
										</td>
										<td class="mid_spcl_pdt_top_rt">&nbsp;</td>
										</tr>
										<?php
										$desc = trim($shelfData['shelf_description']);
										if($desc!='' and $desc!='&nbsp;')
										{
										?>
											<tr>
											<td colspan="3" class="mid_spcl_pdt_btm_mid">
											<?php echo stripslashes($desc)?>
											</td>
											</tr>
										<?php		
										}
										?>
										<tr>
										<td colspan="3" class="mid_spcl_pdt_btm_mid">
										<?php
										while($row_prod = $db->fetch_array($ret_prod))
										{
										?>
												<table width="100%" border="0" cellspacing="5" cellpadding="0" class="mid_spcl_prod_table">
												<tr>
												<td align="center" class="mid_spcl_prod_table_img">
												<?php
												if ($shelfData['shelf_showimage']==1)// Check whether description is to be displayed
												{
												?>
													<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="prod_infolink">
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
												</td>
												<td align="left" valign="top" class="mid_spcl_prod_table_des">
												<?php
												if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
												{
												?>
												<div class="feat_pdt_name">
												<h2 class="mid_spcl_prodname"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h2></div>
												<?php
												}
												if ($shelfData['shelf_showdescription']==1)// Check whether description is to be displayed
												{
												?>		 
													<div class="mid_spcl_proddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></div>
												<? 
												}
												if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
												{ 
													$price_class_arr['ul_class'] 		= 'mid_spcl_price';
													$price_class_arr['normal_class'] 	= 'mid_spcl_normalprice';
													$price_class_arr['strike_class'] 	= 'mid_spcl_strikeprice';
													$price_class_arr['yousave_class'] 	= 'mid_spcl_yousaveprice';
													$price_class_arr['discount_class'] 	= 'mid_spcl_yousaveprice';
													echo show_Price($row_prod,$price_class_arr,'shelfcenter_1');
													show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
													show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points
												}
												?>
												<div class="infodiv">
												<div class="infodivleft"><?php show_moreinfo($row_prod,'infolink');?></div>
												<div class="infodivright">
												<?php
													$frm_name = uniqid('shelf_');
												?>	
													<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
													<input type="hidden" name="fpurpose" value="" />
													<input type="hidden" name="fproduct_id" value="" />
													<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
													<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
														<?php
															$class_arr 					= array();
															$class_arr['ADD_TO_CART']	= 'quantity_infolink';
															$class_arr['PREORDER']		= 'quantity_infolink';
															$class_arr['ENQUIRE']		= 'quantity_infolink';
															show_addtocart($row_prod,$class_arr,$frm_name)
														?> 
													</form>
												</div>
												</div>
												</td>
												</tr>
												</table>
										 <?php }
										 if ($tot_cnt>0 and ($_REQUEST['req']!=''))
										 {
										 ?>
										 	<div class="shlfC_showall">
										 <?php 
												$path = '';
												$query_string .= "";
												paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr); 	
											?>
										 	</div>
										 <?php
										 }
										 ?>
										</td>
											</tr>
										  <tr>
											<td class="mid_spcl_pdt_btm_lf">&nbsp;</td>
											<td class="mid_spcl_pdt_btm_mid">&nbsp;</td>
											<td class="mid_spcl_pdt_btm_rt">&nbsp;</td>
										  </tr>
										</table>
										
										</div>
										 
										 <?php
										 }
										 else
										 {
										  if($cur_title){
											?>
												<div class="mid_shelfB_name"><?php echo $cur_title?></div>
											<?php
											}
											$desc = trim($shelfData['shelf_description']);
											if($desc!='' and $desc!='&nbsp;')
											{
											?>
												<div class="shelfBproddes_top"><?php echo $desc?></div>
											<?php		
											}
											?> <?php
											if ($tot_cnt>0 and ($_REQUEST['req']!=''))
											{  
											?>
											 	<div class="pagingcontainertd_outer" align="center">
												<div class="pagingcontainertd_lf" align="center">
													<?php 
													 paging_show_totalcount($tot_cnt,'Products',$start_var['pg'],$start_var['pages']);
													 ?>
													 
													</div>
												<div class="pagingcontainertd_rt" >
													 <div class="pagingcontainertd" align="center">
													<?
													$path = '';
													//show_paging($path,$_REQUEST[$pg_variable],$start_arr['counterstart'],$tot_cnt,$prodperpage,$pg_variable,'')
													$query_string .= "";
													paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr,0); 	
													?>	
												    </div>
												 </div>
													
												</div>
											
											<?php
											}
											?>	
											<?php
											while($row_prod = $db->fetch_array($ret_prod))
											{
												?>
										        <div class="mid_shelfB">
												<table width="100%" border="0" cellpadding="0" cellspacing="0"  class="mid_shelfB_table">
												<?php
												if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
												{
												?>
												<tr>
													<td class="mid_shelfB_top_lf">&nbsp;</td>
													<td class="mid_shelfB_top_mid"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="prod_infolink"><?php echo stripslashes($row_prod['product_name'])?></a></td>
													<td class="mid_shelfB_top_rt">&nbsp;</td>
												</tr>
												<? }?>
												<tr>
														<td colspan="3" class="mid_shelfB_mid">
														<div class="shelfBimg">
															<?php
															if ($shelfData['shelf_showimage']==1)// Check whether description is to be displayed
															{
															?>		
																<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="prod_infolink">
																<?php
																	// Calling the function to get the type of image to shown for current 
																	//$pass_type = get_default_imagetype('midshelf');
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
															<?php if($comp_active)  {
																dislplayCompareButton($row_prod['product_id']);
															}?>
														</div> 
														<div class="shelfB_cnts">
																<?php 
																if ($shelfData['shelf_showdescription']==1)// Check whether description is to be displayed
																{
																	?>		 
																	<h6 class="shelfBproddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6>
																	<? 
																}
																?>	              
																<ul class="shelfBul">
																<?php
																if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
																{ 
																	$price_class_arr['ul_class'] 		= 'shelfBul';
																	$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
																	$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
																	$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
																	$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
																	echo show_Price($row_prod,$price_class_arr,'shelfcenter_1');
																	show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
																	show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points
																}
																	?>
																
																</ul>	
													   </div>
													  </td>
												</tr>
												<tr>
												<td class="mid_shelfB_btm_lf">&nbsp;</td>
												<td class="mid_shelfB_btm_mid">
													<?php
													$frm_name = uniqid('shelf_');
													?>	
														<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
														<input type="hidden" name="fpurpose" value="" />
														<input type="hidden" name="fproduct_id" value="" />
														<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
														<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
														<div class="infodiv">
															<div class="infodivleft">		<?php show_moreinfo($row_prod,'infolink')?>	</div>
															<div class="infodivright"><?php
																$class_arr 					= array();
																$class_arr['ADD_TO_CART']	= 'quantity_infolink';
																$class_arr['PREORDER']		= 'quantity_infolink';
																$class_arr['ENQUIRE']		= 'quantity_infolink';
																show_addtocart($row_prod,$class_arr,$frm_name)
															?> </div>
														</div>
														</form>
												  </td>
												<td class="mid_shelfB_btm_rt">&nbsp;</td>
												</tr>
												</table>
												
										</div>
										 <?php 
										 }
										 }
										 
									break;
									case '2row': // case of three in a row for new year
									?>
									
										<div class="spcl_shlf_new">
										<table width="100%" border="0" cellpadding="0" cellspacing="0" class="spcl_shlf_new_top_table" >
										<tr>
										<td class="spcl_shlf_new_top_lf">&nbsp;</td>
										<td class="spcl_shlf_new_top_mid">
										<?php
									 	if($cur_title)
										{
											echo stripslashes($cur_title);
										}	
										?>
										</td>
										<td class="spcl_shlf_new_top_rt">&nbsp;</td>
										</tr>
										<?php
										$desc = trim($shelfData['shelf_description']);
										if($desc!='' and $desc!='&nbsp;')
										{
										?>
											<tr>
											<td colspan="3" class="spcl_shlf_new_mid">
												<?php echo stripslashes($desc)?>
											</td>
											</tr>
										<?php
										}
										?>
										<tr>
										<td colspan="3" class="spcl_shlf_new_mid">
										<table width="100%" border="0" cellspacing="5" cellpadding="0" class="spcl_shlf_table_pdt">
										<?php
											if($_REQUEST['req']!='')
												$max_col = 3;
											else
												$max_col = 2;
											$cur_col = 0;
											$prodcur_arr = array();
											while($row_prod = $db->fetch_array($ret_prod))
											{ 
												//##############################################################
												// Showing the title, description and image part for the product
												//##############################################################
												if($cur_col==0)
													echo '<tr>';
												$cur_col++;
										?>
												<td class="spcl_shlf_table_pdt_td" valign="bottom">
												<table width="100%" border="0" cellspacing="0" cellpadding="0" class="spcl_shlf_table_pdt_table">
												<?php
												if($shelfData['shelf_showimage']==1)// whether image is to be displayed
												{
												?>
												<tr>
												<td align="center" valign="middle" class="spcl_shlf_table_pdt_table_img">
													<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
												<?php
														// Calling the function to get the type of image to shown for current 
														//$pass_type = get_default_imagetype('midshelf');
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
												</td>
												</tr>
												<?php
												}
												if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
												{
												?>
													<tr>
													<td class="spcl_shlf_table_pdt_table_name"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></td>
													</tr>
												<?php
												}	
												?>
												<tr>
												<td class="spcl_shlf_table_pdt_table_info">
												<?php
													$frm_name = uniqid('shelf_');
													?>	
														<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
														<input type="hidden" name="fpurpose" value="" />
														<input type="hidden" name="fproduct_id" value="" />
														<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
														<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
												<?php 
														show_moreinfo($row_prod,'spcl_shlf_link_info');
														$class_arr 							= array();
														$class_arr['ADD_TO_CART']		= 'spcl_shlf_link_buy';
														$class_arr['PREORDER']			= 'spcl_shlf_link_buy';
														$class_arr['ENQUIRE']			= 'spcl_shlf_link_buy';
														show_addtocart($row_prod,$class_arr,$frm_name,false,'','','',1);
												?>
													</form>
												</td>
												</tr>
												</table>
												</td>
												
											<?php
												if ($cur_col>=$max_col)
												{
													$cur_col = 0;
													echo '</tr>';
												}
											}
											if($cur_col<$max_col)
												echo '</tr>';
											?>
										</table>
										<?php
										if ($tot_cnt>0 and ($_REQUEST['req']!=''))
										 {
										 ?>
										 	<div class="shlfA_showall">
											 <?php 
												$path = '';
												$query_string .= "";
												paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr); 	
											?>
										 	</div>
										 <?php
										 }
										 ?>
										</td>
										</tr>
										<tr>
										<td class="spcl_shlf_new_btm_lf">&nbsp;</td>
										<td class="spcl_shlf_new_btm_mid">&nbsp;</td>
										<td class="spcl_shlf_new_btm_rt">&nbsp;</td>
										</tr>
										</table>
										</div>
									<?php
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