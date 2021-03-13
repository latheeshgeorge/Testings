<?php
	/*############################################################################
	# Script Name 	: bestsellerHtml.php
	# Description 	: Page which holds the display logic for middle Bestsellers
	# Coded by 		: Anu
	# Created on	: 28-Mar-2008
	# Modified by	: Anu
	# Modified On	: 28-Mar-2008
	##########################################################################*/
	class bulkdiscount_Html
	{
		// Defining function to show the shelf details
		function Show_Bulkdiscount()
		{
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents,$ecom_tax_total_arr,$PriceSettings_arr;

		$Captions_arr['BULKDISC_PROD'] 	= getCaptions('BULKDISC_PROD');
		$Captions_arr['PROD_DETAILS'] 	= getCaptions('PROD_DETAILS');
		$bottom_content = '';
		$sql_bottom = "SELECT general_multibuy_bottomcontent 
							FROM 
								general_settings_sites_common 
							WHERE 
								sites_site_id = $ecom_siteid 
							LIMIT 
								1";
		$ret_bottom = $db->query($sql_bottom);
		if($db->num_rows($ret_bottom))
		{
			$row_bottom = $db->fetch_array($ret_bottom);
			$bottom_content = stripslashes($row_bottom['general_multibuy_bottomcontent']);
		}
			// query for display 	title
		$prodsort_by		= ($_REQUEST['bulkdet_sortby'])?$_REQUEST['bulkdet_sortby']:'product_name';
		$prodperpage		= ($_REQUEST['bulkdet_prodperpage'])?$_REQUEST['bulkdet_prodperpage']:$Settings_arr['product_maxcntperpage_bestseller'];// product per page
		$prodsort_order		= ($_REQUEST['bulkdet_sortorder'])?$_REQUEST['bulkdet_sortorder']:$Settings_arr['product_orderby_bestseller'];
		$sql_tot			=	"SELECT count(a.product_id)  
									FROM 
										products a
									WHERE 
										a.sites_site_id = $ecom_siteid 
										AND a.product_hide ='N' 
										AND a.product_bulkdiscount_allowed='Y'";
		$ret_tot 			= $db->query($sql_tot);
		list($tot_cnt)		= 	$db->fetch_array($ret_tot);		
		// Building the sql 
		$sql_best			= '';
		// Call the function which prepares variables to implement paging
		$ret_arr 			= array();
		$pg_variable		= 'bulk_pg';
		$start_var 			= prepare_paging($_REQUEST[$pg_variable],$prodperpage,$tot_cnt);
		$Limit				= " LIMIT ".$start_var['startrec'].", ".$prodperpage;
		switch ($prodsort_by)
		{
			case 'product_name': // case of order by product name
			$prodsort_bysql		= 'a.product_name';
			break;
			case 'price': // case of order by price
			$prodsort_bysql		= 'a.product_webprice';
			break;
			case 'product_id': // case of order by price
			$prodsort_bysql		= 'a.product_id';
			break;
			default: // by default order by product name
			$prodsort_bysql		= 'a.product_name';
			break;
		};
		$sql_prod = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
					a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
					a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
					product_total_preorder_allowed,a.product_applytax,a.product_shortdesc,product_bonuspoints,
					a.product_stock_notification_required,a.product_alloworder_notinstock,product_variables_exists,product_variablesaddonprice_exists,
					a.product_variablecomboprice_allowed,a.product_variablecombocommon_image_allowed,a.default_comb_id,
					a.price_normalprefix,a.price_normalsuffix, a.price_fromprefix, a.price_fromsuffix,price_specialofferprefix, a.price_specialoffersuffix, 
					a.price_discountprefix, a.price_discountsuffix, a.price_yousaveprefix, a.price_yousavesuffix,a.price_noprice,
					a.product_averagerating,a.product_saleicon_show,a.product_saleicon_text,a.product_newicon_show,a.product_newicon_text,
					a.product_freedelivery        
				FROM 
					products a
				WHERE 
					a.product_hide = 'N' 
					AND a.sites_site_id = $ecom_siteid
					AND a.product_bulkdiscount_allowed='Y' 
					ORDER BY 
							$prodsort_bysql $prodsort_order 
						$Limit ";
		$ret_prod = $db->query($sql_prod);
	
						
						if ($db->num_rows($ret_prod))
						{
							// Calling the function to get the type of image to shown for current 
							$pass_type = get_default_imagetype('midshelf');
							$prod_compare_enabled = isProductCompareEnabled();
							// Number of result to display on the page, will be in the LIMIT of the sql query also
							$querystring = ""; // if any additional query string required specify it over here
								?>
										<table border="0" cellpadding="0" cellspacing="0" class="shelfAtable">
										<tr>
											<td colspan="3" align="left"><div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?php  echo $Captions_arr['BULKDISC_PROD']['BULKDISC_PROD'];?></div></td>
										</tr>
									<?php
											if ($Captions_arr['BULKDISC_PROD']['BULKDISC_PROD_TOPMSG']!='')
											{
										?>
												<tr>
													<td colspan="3" class="shelfBproddes" align="left">
														<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bulk_hdr_table">
														<tr>
														<td class="bulk_hdr_left">&nbsp;</td>
														<td class="bulk_hdr_right"><?php echo str_replace('[tot_cnt]',$tot_cnt,stripslashes($Captions_arr['BULKDISC_PROD']['BULKDISC_PROD_TOPMSG']));?></td>
														</tr>
														</table>
													</td>
												</tr>
										<?php		
											}
										if ($tot_cnt>0 )
										{
										?>
											<tr>
												<td colspan="3" class="pagingcontainertd" align="center">
												<?php 
													$path = '';
													//show_paging($path,$_REQUEST[$pg_variable],$start_arr['counterstart'],$tot_cnt,$prodperpage,$pg_variable,'')
														$query_string .= "disp_id=".$_REQUEST['disp_id'];
														paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr,0); 	
												?>	
												</td>
											</tr>
										<?php
										}
											$max_col = 3;
											$cur_col = 0;
											$prodcur_arr = array();
											while($row_prod = $db->fetch_array($ret_prod))
											{
												$prodcur_arr[] = $row_prod;
												if($cur_col ==0)
													echo '<tr>';
												//##############################################################
												// Showing the title, description and image part for the product
												//##############################################################
										?>
												<td class="shelfAtabletd" align="left" valign="top" onmouseover="this.className='normalshelf_hover'" onmouseout="this.className='shelfAtabletd'">
												<div class="bulk_view_free"></div>
													<ul class="shelfBul">
																<li><h1 class="shelfAprodname">
																<div class="multibuy_name_div">
																<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h1>
																</div>
																</li>
																<li class="bestsellerimg">
																	<div class="multibuy_img_div">
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
																	</div>
																</li>
														  	<li>
													
													<?php 	/*$pass_arr['main_cls'] 		= 'bonus_point';
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
													
													?></li>
													<li>
																	<?php
																		$price_class_arr['ul_class'] 		= 'bulkpriceul';
																		$price_class_arr['normal_class'] 	= 'bulkpriceul_liA';
																		$price_class_arr['strike_class'] 	= 'bulkpriceul_liA';
																		$price_class_arr['yousave_class'] 	= 'bulkpriceul_liA';
																		$price_class_arr['discount_class'] 	= 'bulkpriceul_liA';
																		$price_arr = show_Price($row_prod,$price_class_arr,'shelfcenter_3',false,6);
																		if($price_arr['discounted_price'])
																			$indiv_price 	= $price_arr['discounted_price'];
																		else
																			$indiv_price	= $price_arr['base_price'];
																		//echo "Individual Price: ".print_price($indiv_price);	
																		$comb_id = array();
																		// Get the highest bulk discount value for current product
																		if($row_prod['product_variablecomboprice_allowed']=='Y')
																		{
																			// get the id of first combination
																			$sql_comb = "SELECT comb_id 
																							FROM 
																								product_variable_combination_stock 
																							WHERE 
																								products_product_id  = ".$row_prod['product_id'];
																			$ret_comb = $db->query($sql_comb);
																			if($db->num_rows($ret_comb))
																			{
																				while($row_comb = $db->fetch_array($ret_comb))
																				{
																					$comb_id[] = $row_comb['comb_id'];
																				}	
																			}					
																		}
																		if(count($comb_id))
																			$comb_add_cond = " AND (comb_id IN(".implode(',',$comb_id)."))";
																		else
																			$comb_add_cond = " AND comb_id = 0 ";
																		$sql_bulk = "SELECT  bulk_qty, bulk_price 
																						FROM 
																							product_bulkdiscount 
																						WHERE 
																							products_product_id = ".$row_prod['product_id']."
																							$comb_add_cond  
																						ORDER BY 
																							bulk_price  
																						LIMIT 
																							1";
																		$ret_bulk = $db->query($sql_bulk);
																		if($db->num_rows($ret_bulk))
																		{
																			$row_bulk = $db->fetch_array($ret_bulk);
																			if($row_bulk['bulk_qty']<=0)
																				$bulk_qty = 1;
																			else
																				$bulk_qty = $row_bulk['bulk_qty'];
																			$bulk_price = $row_bulk['bulk_price']/$bulk_qty;
																			if (($PriceSettings_arr['price_displaytype']=='show_price_inc_tax' or $PriceSettings_arr['price_displaytype']=='show_both') and $row_prod['product_applytax']=='Y')
																			{
																				$tax_arr 		= $ecom_tax_total_arr;
																				$tax_val		= $tax_arr['tax_val'];
																				$bulk_price		= $row_bulk['bulk_price'] + ($row_bulk['bulk_price']*$tax_val/100);
																			}
																			else
																				$bulk_price = $row_bulk['bulk_price'];
																			echo "<ul class='bulkpriceul'>
																			
																			<li class='bulkpriceul_liA'>".$Captions_arr['PROD_DETAILS']['BULK_BUY'].' '.$row_bulk['bulk_qty'].' '.$Captions_arr['PROD_DETAILS']['BULK_FOR'].' '.print_price($bulk_price).' '.$Captions_arr['PROD_DETAILS']['BULK_EACH'].' </li></ul>';
																		}
																		//echo show_Price($row_prod,$price_class_arr,'shelfcenter_3');
																	?>	
																 </li>
																 <li class="bulk_view">
																 <a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><img src="<?php url_site_image('bulk-btn.gif')?>" border="0" alt="<?php echo stripslashes($row_prod['product_name'])?>" title="<?php echo stripslashes($row_prod['product_name'])?>"></a></li>
																</ul>
												</td>
											<?php
												$cur_col++;
												if ($cur_col>=$max_col)
												{
													echo "</tr>
														<tr>														
														<td class='shelfAtabletdA' colspan='3'></td>
														</tr>";
													$cur_tempcol = $cur_col = 0;
													//##############################################################
													// Showing the more info and add to cart links after each row in 
													// case of breaking to new row while looping
													//##############################################################
												}
											}
											// If in case total product is less than the max allowed per row then handle that situation
											if ($cur_col<$max_col and $cur_col>0)
											{
												echo "<td colspan='".($max_col-$cur_col)."'>&nbsp;</td></tr>
												<tr>														
												<td class='shelfAtabletdA' colspan='".$cur_col."'></td>
												<td colspan='".($max_col-$cur_col)."'></td>
												</tr>
												";
												$cur_tempcol = $cur_col = 0;
											}
										if($bottom_content!='')
										{	
										?>
										<tr>
										<td align="left" class="bulkdisc_bottom_desc" colspan="3">
										<?php echo $bottom_content?>
										</td>
										</tr>	
										<?php
										}
										?>
										</table>
								<?php		
							
						}
		}
	};	
?>