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
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents;

		$Captions_arr['BULKDISC_PROD'] = getCaptions('BULKDISC_PROD');
// query for display 	title
			$query_disp	= "SELECT 
									 display_title 
								FROM 
									display_settings 
								WHERE 
									sites_site_id='$ecom_siteid' 
									AND display_id ='$display_id'
									AND layout_code='$default_layout' ";
			$result_disp = $db->query($query_disp);
			list($cur_title) = $db->fetch_array($result_disp);
			// ##############################################################################################################
			// Building the query for bestseller
			// ##############################################################################################################
			// Getting the settings for best sellers form the settings table
			// query for display 	title
			$prodsort_by			= ($_REQUEST['bulkdet_sortby'])?$_REQUEST['bulkdet_sortby']:'product_name';
		$prodperpage			= ($_REQUEST['bulkdet_prodperpage'])?$_REQUEST['bulkdet_prodperpage']:$Settings_arr['product_maxcntperpage_bestseller'];// product per page
		$prodsort_order			= ($_REQUEST['bulkdet_sortorder'])?$_REQUEST['bulkdet_sortorder']:$Settings_arr['product_orderby_bestseller'];
		$sql_tot	=	"SELECT count(a.product_id)  
				FROM 
					products a
				WHERE 
					a.sites_site_id = $ecom_siteid 
					AND a.product_hide ='N' 
					AND a.product_bulkdiscount_allowed='Y'";
		$ret_tot 	= $db->query($sql_tot);
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
											if ($Captions_arr['BULKDISC_PROD']['BULKDISC_PROD']!='')
											{
										?>
												<tr>
													<td colspan="3" class="shelfAheader" align="left"><?php echo stripslashes($Captions_arr['BULKDISC_PROD']['BULKDISC_PROD']);?></td>
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
													$query_string .= "disp_id=".$_REQUEST['disp_id'];
													paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr); 	
												?>	
												</td>
											</tr>
										<?php
										}
										?>	
										<tr>
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
														<li><h2 class="shelfAprodname"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h2></li>
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
														?>
															<li><h6 class="shelfAproddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6></li>
														</ul>
														<?php show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points		$frm_name = uniqid('shelf_');
														if($prod_compare_enabled)
														{
															dislplayCompareButton($row_prod['product_id']);
														}
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
														$frm_name = 'bestseller';
														
														?>
														<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
														<input type="hidden" name="fpurpose" value="" />
														<input type="hidden" name="fproduct_id" value="" />
														<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
														<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
														<div class="buy_main">
															<div class="shlf_buy_btn">
															<div class="shlf_buy_btnleft">
															<?php
															$class_arr 							= array();
															$class_arr['ADD_TO_CART']	= 'link_buy';
															$class_arr['PREORDER']			= 'link_buy';
															$class_arr['ENQUIRE']			= 'link_buy';
															show_addtocart($row_prod,$class_arr,$frm_name,false,'','','add_divlink',0)
															?>
															</div>
															</div>      
															<div class="shlf_info_btn"><div class="shlf_info_btnleft">
															<?php show_moreinfo($row_prod,'info_buy')?>
															</div>
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
		}
	};	
?>