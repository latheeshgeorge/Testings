<?php
	/*############################################################################
	# Script Name 	: bestsellerHtml.php
	# Description 	: Page which holds the display logic for middle Bestsellers
	# Coded by 		: Anu
	# Created on	: 28-Mar-2008
	# Modified by	: Anu
	# Modified On	: 28-Mar-2008
	##########################################################################*/
	class preorder_Html
	{
	
		// Defining function to show the shelf details
		function Show_Preorder($display_id)
		{
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr;

			$Captions_arr['PREORDER'] = getCaptions('PREORDER');
			// query for display 	title
			$query_disp	= "SELECT 
							 display_title 
						FROM 
							display_settings 
						WHERE 
							sites_site_id='$ecom_siteid' 
							AND display_id ='$display_id'";
			$result_disp = $db->query($query_disp);
			list($cur_title) = $db->fetch_array($result_disp);
	// ##############################################################################################################
	// Building the query for bestseller
	// ##############################################################################################################
	// Getting the settings for best sellers form the settings table
	$prodperpage			= $Settings_arr['product_maxcntperpage_preorder'];
	// Deciding the sort by field
	$bestsort_by			= $Settings_arr['product_orderfield_preorder'];
	switch ($bestsort_by)
	{
		case 'custom':
			$bestsort_by	= 'a.product_preorder_custom_order';
		break;
		case 'product_name':
			$bestsort_by	= 'a.product_name';
		break;
		case 'price':
			$bestsort_by	= 'a.product_webprice';
		break;
		case 'product_id': // case of order by price
			$bestsort_by	= 'a.product_id';
		break;	
	};
	
						
		
		$sql_preorder_all	=	"SELECT count(a.product_id)  
						FROM 
							products a 
						WHERE 
							a.sites_site_id = $ecom_siteid 
							AND a.product_preorder_allowed = 'Y' 
							AND a.product_hide ='N' 
							AND a.product_alloworder_notinstock ='N' ";
		$ret_preorder_all 		= $db->query($sql_preorder_all);
		list($tot_cnt)	= 	$db->fetch_array($ret_preorder_all);		
		$bestsort_order			= $Settings_arr['product_orderby_preorder'];
		// Building the sql 
		$sql_best				= '';
	
		// Call the function which prepares variables to implement paging
		$ret_arr 		= array();
		$pg_variable	= 'bestsell_pg';
		if ($_REQUEST['req']!='')// LIMIT for products is applied only if not displayed in home page
		{
			$start_var 		= prepare_paging($_REQUEST[$pg_variable],$prodperpage,$tot_cnt);
			$Limit			= " LIMIT ".$start_var['startrec'].", ".$prodperpage;
		}	
		else
			$Limit = '';
			
		$sql_best = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
										a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
										a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
										product_total_preorder_allowed,a.product_applytax,a.product_shortdesc,a.product_bonuspoints,
										a.product_stock_notification_required,a.product_alloworder_notinstock,product_variables_exists,product_variablesaddonprice_exists,
										a.product_variablecomboprice_allowed,a.product_variablecombocommon_image_allowed,a.default_comb_id,
										a.price_normalprefix,a.price_normalsuffix, a.price_fromprefix, a.price_fromsuffix,a.price_specialofferprefix, a.price_specialoffersuffix, 
										a.price_discountprefix, a.price_discountsuffix, a.price_yousaveprefix, a.price_yousavesuffix,a.price_noprice               
						FROM 
							products a
						WHERE 
							a.sites_site_id = $ecom_siteid 
							AND a.product_preorder_allowed = 'Y' 
							AND a.product_hide ='N' 
							AND a.product_alloworder_notinstock ='N' 
						ORDER BY 
							$bestsort_by $bestsort_order 
						$Limit ";
		
		
	
						$ret_prod = $db->query($sql_best);
						$totcnt = $db->num_rows($ret_prod);
						if ($db->num_rows($ret_prod))
						{
							// Number of result to display on the page, will be in the LIMIT of the sql query also
							$querystring = ""; // if any additional query string required specify it over here
							// Calling the function to get the type of image to shown for current 
							$pass_type = get_default_imagetype('midshelf');
							$prod_compare_enabled = isProductCompareEnabled();
								switch($Settings_arr['preorder_prodlisting'])
								{
									case '1row': // case of one in a row for normal
									?>
									<div class="tree_top"></div>
											<div class="tree_middle">
												<div class="pro_det_treemenu">
													<ul>
													<li><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> &gt;&gt; </li>
													<li> <?php  echo $cur_title;?> </li>
													</ul>
												</div>
											</div>
										<div class="tree_bottom"></div>
										</div>
										<?php 
										if ($Captions_arr['PREORDER']['PREORDER_CAPTION']!='')
											{
										?>
												<div class="mid_shelfB_name"><?php echo $Captions_arr['PREORDER']['PREORDER_CAPTION']?></div>
											<?php
											}
											if ($tot_cnt>0 )
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
												
												<tr>
													<td class="mid_shelfB_top_lf">&nbsp;</td>
													<td class="mid_shelfB_top_mid"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="prod_infolink"><?php echo stripslashes($row_prod['product_name'])?></a></td>
													<td class="mid_shelfB_top_rt">&nbsp;</td>
												</tr>
												<tr>
														<td colspan="3" class="mid_shelfB_mid">
														<div class="shelfBimg">
																
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
															<?php if($comp_active)  {
																dislplayCompareButton($row_prod['product_id']);
															}?>
														</div> 
														<div class="shelfB_cnts">
																	 
																	<h6 class="shelfBproddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6>
																<ul class="shelfBul">
																<?php
																 
																	$price_class_arr['ul_class'] 		= 'shelfBul';
																	$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
																	$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
																	$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
																	$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
																	echo show_Price($row_prod,$price_class_arr,'shelfcenter_1');
																	show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
																	show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points
																	?>
																
																</ul>	
													   </div>
													  </td>
												</tr>
												<tr>
												<td class="mid_shelfB_btm_lf">&nbsp;</td>
												<td class="mid_shelfB_btm_mid">
													<?php
													$frm_name = uniqid('best_');
													?>	
														   <form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
															<input type="hidden" name="fpurpose" value="" />
															<input type="hidden" name="fproduct_id" value="" />
															<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
															<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />														<div class="infodiv">
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


									break;
									case '2row': // case of three in a row for normal
									?>
										<div class="tree_con">
									<div class="tree_top"></div>
											<div class="tree_middle">
												<div class="pro_det_treemenu">
													<ul>
													<li><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> &gt;&gt; </li>
													<li> <?php  echo $cur_title;?> </li>
													</ul>
												</div>
											</div>
										<div class="tree_bottom"></div>
										</div>
									<?php
									if ($Captions_arr['PREORDER']['PREORDER_CAPTION']!='')
									{
										?>
										<div class="mid_shelfA_name"><?php echo $Captions_arr['PREORDER']['PREORDER_CAPTION']?></div>
									<?php
									}
									if ($tot_cnt>0 )
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
									<table width="100%" border="0" cellpadding="0" cellspacing="0"  class="mid_shelfA">
									<tr>
									<?php
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
									<td class="<?=$cls?>">
									<table width="100%" border="0" cellpadding="0" cellspacing="0"  class="mid_shelfA_table">
										<tr>
											<td class="mid_shelfA_top_lf">&nbsp;</td>
											<td class="mid_shelfA_top_mid"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="prod_infolink"><?php echo stripslashes($row_prod['product_name'])?></a></td>
											<td class="mid_shelfA_top_rt">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" class="mid_shelfA_mid">
											<ul class="shelfAul">
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
											<li><h6 class="shelfAproddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6></li>                     
											<?php
											$price_class_arr['ul_class'] 		= 'shelfBul';
											$price_class_arr['normal_class'] 	= 'shelfAnormalprice';
											$price_class_arr['strike_class'] 	= 'shelfAstrikeprice';
											$price_class_arr['yousave_class'] 	= 'shelfAyousaveprice';
											$price_class_arr['discount_class'] 	= 'shelfAdiscountprice';
											echo show_Price($row_prod,$price_class_arr,'shelfcenter_3');
											show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
											?>
											</ul>	
											</td>
										</tr>
										<tr>
										<td class="mid_shelfA_btm_lf">&nbsp;</td>
										<?php $frm_name = uniqid('best_'); ?>
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
					
				
			
		}
	};	
?>