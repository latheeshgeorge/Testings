<?php
/*############################################################################
# Script Name 	: favcategory_showallHtml.php
# Description 	: Page which holds the display logic for all products under fav category
# Coded by 		: LSH
# Created on	: 14-oct-2008
# Modified by	: LH
# Modified On	: 
##########################################################################*/
class favprodshowall_Html
{
	// Defining function to show the shelf details
	function Show_favproducts($cust_id)
	{
		global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents;
		$customer_id 					= get_session_var("ecom_login_customer");
		$Captions_arr['PROD_DETAILS'] 	= getCaptions('PROD_DETAILS');
		if($customer_id)
		{
			$sql_last_login = "SELECT customer_last_login_date FROM customers WHERE sites_site_id=$ecom_siteid AND customer_id=$customer_id";
			$ret_last_login = $db->query($sql_last_login);
			list($row_last_login) = $db->fetch_array($ret_last_login);
		}
		$Captions_arr['LOGIN_HOME'] = getCaptions('LOGIN_HOME'); // to get values for the captions from the general settings site captions
		$prodcur_arr =array();
		$prodperpage			= ($Settings_arr['product_maxcnt_fav_category']>0)?$Settings_arr['product_maxcnt_fav_category']:10;//Hardcoded at the moment. Need to change to a variable that can be set in the console.
		//$limit = $Settings_arr['product_maxcnt_fav_category'];
		$favsort_by				= $Settings_arr['product_orderby_favorite'];
		$prodsort_order			= $Settings_arr['product_orderfield_favorite'];
		switch ($prodsort_order)
		{
			case 'product_name': // case of order by product name
						$prodsort_order		= 'product_name';
						break;
			case 'price': // case of order by price
						$prodsort_order		= 'product_webprice';
						break;
			case 'product_id': // case of order by price
						$prodsort_order		= 'product_id';
						break;
		};
		if ($_REQUEST['req']!='')// LIMIT for Favorites is applied only if not displayed in home page
		{
			$start_varprod 		= prepare_paging($_REQUEST[$pg_variableprod],$prodperpage,$tot_cntprod);
			$Limitprod			= " LIMIT ".$start_varprod['startrec'].", ".$prodperpage;
		}
		else
			$Limitprod = '';
			
		$pg_variableprod		= 'prod_pg';
		$sql_fav_products = "SELECT id,a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
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
										products a,customer_fav_products cfp
									WHERE
										 a.product_id = cfp.products_product_id AND a.product_hide='N'  AND
								cfp.sites_site_id = $ecom_siteid  AND cfp.customer_customer_id = $cust_id
									ORDER BY $prodsort_order $favsort_by $Limitprod	";
		$ret_fav_products = $db->query($sql_fav_products);
		$prod_compare_enabled = isProductCompareEnabled();
?>		<div class="treemenu">
			<a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> 
<?php	if ($db->num_rows($ret_fav_products)==1)
			echo stripslash_normal($Captions_arr['LOGIN_HOME']['LOGIN_HOME_FAV_PRODUCT_HEADER']);
		else
			echo stripslash_normal($Captions_arr['LOGIN_HOME']['LOGIN_HOME_FAV_PRODUCTS_HEADER']);
?>		</div>
<?php	$displaytype = $Settings_arr['favorite_prodlisting'];
		$pass_type = get_default_imagetype('midshelf');
		$comp_active = isProductCompareEnabled();
		switch($displaytype)
		{
			case '2row':
?>		<table border="0" cellpadding="0" cellspacing="0" class="shelfBtable">
			<tr>
			<td colspan="3" class="" align="center">
<?php			$max_col = 2;
				$cur_col = 1;
				$cur_row = 1;
				while($row_prod = $db->fetch_array($ret_fav_products))
				{
					/*if($cur_row==0)
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
					}*/
?>		
<div class="catBoxWrap_threecolumn">
<?php
												//if ($shelfData['shelf_showimage']==1)// Check whether description is to be displayed
												{
													?>
																<div class="featureimgqrap_three_column"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" <?php echo getmicrodata_producturl()?> class="product_image" >
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
																//if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
																{
																?>
																<div class="prod_list_name_link_three_column">
																<h1 class="shelfBprodname_three_column">
																<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><span <?php echo getmicrodata_productname()?>><?php echo stripslashes($row_prod['product_name'])?></span></a>
																</h1>

																</div>
																<?php
																}
												//if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
												{
												?>										
													<div class="prod_list_price_three">
										<?php			
										            $price_class_arr['ul_class'] 		= 'shelfBul_three_column';
													$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
													$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
													$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
													$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
													//	echo show_Price($row_prod,$price_class_arr,'shelfcenter_1'); price_categorydetails_1_reqbreak
													echo show_Price($row_prod,$price_class_arr,'cat_detail_1');
													show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
												  }
																										
													?>																			
														
										</div>
										<div class="det_all_outer">

										<?php
										//if($shelfData['shelf_showrating']==1)
													{
														$module_name = 'mod_product_reviews';
														if(in_array($module_name,$inlineSiteComponents))
														{
															if($row_prod['product_averagerating']>=0)
															{
																$HTML_rating = display_rating($row_prod['product_averagerating'],1);
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
																	echo show_addtocart_v5($row_prod,$class_arr,$frm_name,false,'','',true,$class_td);
													?>						</div>
												</form>
										</div>
<?php				/*if($cur_row>=$max_col)
					{
						echo "</tr>";
						$cur_row = 0;
					}*/
					$cur_row ++;
				}
?>		</table>
<?php	break;
		};	
	}
}
?>
