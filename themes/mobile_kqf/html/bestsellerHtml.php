<?php
	/*############################################################################
	# Script Name 	: bestsellerHtml.php
	# Description 	: Page which holds the display logic for middle Bestsellers
	# Coded by 		: Sny
	# Created on	: 11-Aug-2009
	# Modified by	: 
	# Modified On	: 
	##########################################################################*/
	class bestseller_Html
	{
		// Defining function to show the shelf details
		function Show_Bestseller($display_id)
		{
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents;

			$Captions_arr['BEST_SELLERS'] = getCaptions('BEST_SELLER');
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
			$bestseller_type 		= $Settings_arr['best_seller_picktype'];
			$prodperpage			= $Settings_arr['product_maxcntperpage_bestseller'];
			// Deciding the sort by field
			$bestsort_by			= $Settings_arr['product_orderfield_bestseller'];
			switch ($bestsort_by)
			{
				case 'custom':
					$bestsort_by	= 'b.bestsel_sortorder';
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
			/* Sony Jul 01, 2013 */
			global $discthm_group_prod_array;
			$more_conditions = '';
			if(count($discthm_group_prod_array))
			{
				$more_conditions = " AND a.product_id IN ( ".implode(',',$discthm_group_prod_array).") ";
			}
		/* Sony Jul 01, 2013 */
			if($bestseller_type == 1) // Case of manual picking
			{	
				$sql_bestsel_all	=	"SELECT count(a.product_id)  
								FROM 
									products a,general_settings_site_bestseller b 
								WHERE 
									a.sites_site_id = $ecom_siteid 
									AND a.product_id = b.products_product_id 
									AND b.bestsel_hidden = 0 
									AND a.product_hide ='N' ";
				$ret_bestsel_all 	= $db->query($sql_bestsel_all);
				list($tot_cnt)		= 	$db->fetch_array($ret_bestsel_all);		
				$bestsort_order		= $Settings_arr['product_orderby_bestseller'];
				// Building the sql 
				$sql_best			= '';
				// Call the function which prepares variables to implement paging
				$ret_arr 			= array();
				$pg_variable		= 'bestsell_pg';
				$start_var 			= prepare_paging($_REQUEST[$pg_variable],$prodperpage,$tot_cnt);
				$Limit				= " LIMIT ".$start_var['startrec'].", ".$prodperpage;
				$sql_best 			= "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
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
											products a,general_settings_site_bestseller b 
										WHERE 
											a.sites_site_id = $ecom_siteid 
											AND a.product_id = b.products_product_id 
											AND b.bestsel_hidden = 0 
											AND a.product_hide ='N' 
											$more_conditions
										ORDER BY 
											$bestsort_by $bestsort_order 
										$Limit ";
			}
			elseif ($bestseller_type == 0) // case of automatic picking
			{
				$sql_bestsel_all = "SELECT a.product_id     
									FROM 
										
										products a,order_details b,orders p 
									WHERE 
										p.order_id=b.orders_order_id 
										AND p.sites_site_id=$ecom_siteid 
										AND p.order_status NOT IN ('CANCELLED','NOT_AUTH') 
										AND b.products_product_id=a.product_id 
										AND a.product_hide ='N' 
										$more_conditions 
									GROUP BY 
										a.product_id ";
				$ret_bestsel_all 		= $db->query($sql_bestsel_all);
				$tot_cnt				= 	$db->num_rows($ret_bestsel_all);	
		
				$bestsort_order			= $Settings_arr['product_orderby_bestseller'];
				// Building the sql 
				$sql_best				= '';
			
				// Call the function which prepares variables to implement paging
				$ret_arr 		= array();
				$pg_variable	= 'bestsell_pg';
				$start_var 		= prepare_paging($_REQUEST[$pg_variable],$prodperpage,$tot_cnt);
				$Limit			= " LIMIT ".$start_var['startrec'].", ".$prodperpage;
				$sql_best 		= "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
										a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
										a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
										product_total_preorder_allowed,a.product_applytax,a.product_shortdesc,sum(b.order_orgqty) as totcnt ,
										a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,
										a.product_variablesaddonprice_exists,a.product_averagerating,a.product_saleicon_show,
										a.product_saleicon_text,a.product_newicon_show,a.product_newicon_text,a.product_freedelivery     
									FROM 
										products a,order_details b,orders p 
									WHERE 
										p.order_id=b.orders_order_id 
										AND p.sites_site_id=$ecom_siteid 
										AND p.order_status NOT IN ('CANCELLED','NOT_AUTH') 
										AND b.products_product_id=a.product_id 
										AND a.product_hide ='N' 
										$more_conditions 
									GROUP BY 
										a.product_id 
									ORDER BY 
										totcnt DESC    
									$Limit "; //orders a,order_details b,products p 
				}		
				$ret_prod = $db->query($sql_best);
				if ($db->num_rows($ret_prod))
				{
				$HTML_comptitle = $HTML_maindesc = $HTML_paging = $HTML_showall = '';
				$comp_active = isProductCompareEnabled();
					// Calling the function to get the type of image to shown for current 
					$pass_type = get_default_imagetype('midshelf');
					$prod_compare_enabled = isProductCompareEnabled();
					// Number of result to display on the page, will be in the LIMIT of the sql query also
					$querystring = ""; // if any additional query string required specify it over here
					if ($tot_cnt>0 and ($_REQUEST['req']!='') and $tot_cnt>$prodperpage)
										{
											$paging 			= paging_footer_advanced($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr);
											$HTML_paging ='<div class="subcat_nav_pdt_no_shelf"><span>'.$paging['total_cnt'].'</span></div>
															<div class="page_nav_top"></div>
																<div class="page_nav_mid">
																		<div class="page_nav_content">
																			<ul>';//.'';
																				$HTML_paging .= $paging['navigation']['start_nav'].$paging['navigation']['page_no'].$paging['navigation']['end_nav'];
																				$HTML_paging .= ' 
																			</ul>
																		</div>
																	</div>
																<div class="page_nav_bottom"></div>
															</div>';
										}
									
										$HTML_treemenu = '
											<div class="tree_menu_con_list">
												<div class="tree_menu_top_list"></div>
													<div class="tree_menu_mid_list">
														<div class="tree_menu_content_list">
															<ul class="tree_menu">
																<li><a href="'.url_link('',1).'" title="'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a> | </li>
																<li>'.stripslash_normal($Captions_arr['BEST_SELLERS']['BEST_SELLERS_MAIN_HEAD']).'</li>
															</ul>
														</div>
													</div>
												<div class="tree_menu_bottom_list"></div>
											</div>';
										echo $HTML_treemenu;	
										echo $HTML_paging;

					
						?>
					                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="shlf_table_1row">

					<? 
					echo $HTML_comptitle;
					echo $HTML_maindesc;
					$max_col = 4;
					$cur_col = 0;
					$prodcur_arr = array();
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
				<?php		//if ($shelfData['shelf_showimage']==1)
							//{
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
												show_image(url_root_image($img_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name']);
											}
										}
										else
										{
											// calling the function to get the default image
											$no_img = get_noimage('prod',$fld_mode); 
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
				</td>							
								<td class="shlf_td_1row_desc">
								<div class="shlf_table_1row_otr"> 
				<?php			//if($shelfData['shelf_showtitle']==1)
								//{
				?>					<div class="shlf_table_1row_name">
										<a><?php echo stripslashes($row_prod['product_name'])?></a>
									</div> 
				<?php			//}
								//if ($shelfData['shelf_showdescription']==1)
								//{
				?>
									<div class="shlf_table_1row_des">
										<?php echo stripslashes($row_prod['product_shortdesc'])?><?php //show_moreinfo($row_prod,'list_more')?>
									</div>
				<?php			//}
								//if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
								//{
										$price_class_arr['ul_class'] 		= 'row1_price';
													$price_class_arr['normal_class'] 	= 'row1_price_a';
													$price_class_arr['strike_class'] 	= 'row1_price_a';
													$price_class_arr['yousave_class'] 	= 'row1_price_b';
													$price_class_arr['discount_class'] 	= 'row1_price_b';
								$price_arr =  show_Price($row_prod,$price_class_arr,'cat_detail_1',false,6);
								if($price_arr['discounted_price'] != "" || $price_arr['base_price'] != "")
								{
				?>
									<div class="shlf_table_1row_price">				<?php				
									//	echo show_Price($row_prod,$price_class_arr,'shelfcenter_1'); price_categorydetails_1_reqbreak
									
									if($price_arr['discounted_price'])
									   echo "<div class='row2_price_a'>".$Captions_arr['COMMON']['LIST_PRICE']." ".print_price($price_arr['discounted_price'])."</div>";
									else
										echo "<div class='row2_price_a'>".$Captions_arr['COMMON']['LIST_PRICE']." ".print_price($price_arr['base_price'])."</div>";
									if($price_arr['disc_percent'])
										echo "<br><div class='row2_price_b'>".$price_arr['disc_percent']."% ".$Captions_arr['COMMON']['FEAT_OFF']."</div>";
								//echo show_Price($row_prod,$price_class_arr,'cat_detail_1');
									//show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
				?>					</div>
				<?php 			}
				?>				</div>
								</td>
							</tr>
							</table></a>
						</td>
					</tr>
					<?php
					}					
					?>
					</table>
					<?php
										
					echo $HTML_paging;
				}
			}
	};	
?>
