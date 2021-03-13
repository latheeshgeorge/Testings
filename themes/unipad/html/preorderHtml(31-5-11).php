<?php
	/*############################################################################
	# Script Name 	: preorderHtml.php
	# Description 	: Page which holds the display logic for middle preorder products
	# Coded by 		: Sny
	# Created on	: 11-Aug-2009
	# Modified by	: 
	# Modified On	: 
	##########################################################################*/
	class preorder_Html
	{
		// Defining function to show the shelf details
		function Show_Preorder($display_id)
		{
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents;

			$Captions_arr['PREORDER'] = getCaptions('PREORDER');
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
			$ret_preorder_all 	= $db->query($sql_preorder_all);
			list($tot_cnt)		= 	$db->fetch_array($ret_preorder_all);		
			$bestsort_order		= $Settings_arr['product_orderby_preorder'];
			// Building the sql 
			$sql_best			= '';
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
								a.price_discountprefix, a.price_discountsuffix, a.price_yousaveprefix, a.price_yousavesuffix,a.price_noprice,
								a.product_averagerating,a.product_saleicon_show,a.product_saleicon_text,a.product_newicon_show,a.product_newicon_text,
								a.product_freedelivery              
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
			if ($db->num_rows($ret_prod))
			{
				// Number of result to display on the page, will be in the LIMIT of the sql query also
				$querystring = ""; // if any additional query string required specify it over here
				// Calling the function to get the type of image to shown for current 
				$pass_type = get_default_imagetype('midshelf');
				$prod_compare_enabled = isProductCompareEnabled();
					switch($Settings_arr['preorder_prodlisting'])
					{
						case '2row': // case of vertical display
									$HTML_comptitle = $HTML_maindesc = $HTML_paging = $HTML_showall = '';
										if ($tot_cnt>0 and ($_REQUEST['req']!='') and $tot_cnt>$prodperpage)
										{
											$paging 		= paging_footer_advanced($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr);
											
											$HTML_totcnt = '<div class="subcat_nav_pdt_no"><span>'.$paging['total_cnt'].'</span></div>';
											$HTML_paging	= '<div class="page_nav_content">
																		<ul>
																		'.$paging['navigation']['start_nav'].$paging['navigation']['page_no'].$paging['navigation']['end_nav'].'
																		</ul>
																</div>';					
										}
										$HTML_treemenu = '	<div class="tree_menu_con">
														<div class="tree_menu_top_list"></div>
														<div class="tree_menu_mid_list">
														<div class="tree_menu_content_list">
															<ul class="tree_menu">
															<li><a href="'.url_link('',1).'" title="'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a></li>
															 <li>'.stripslash_normal($Captions_arr['PREORDER']['PREORDER_CAPT']).'</li>
															</ul>
														</div>
														</div>
														<div class="tree_menu_bottom_list"></div>
														</div>';				
										
										echo $HTML_treemenu;
										echo $HTML_totcnt;
										echo $HTML_paging;	
									?>
										
										<? 
										echo $HTML_maindesc;
  										$max_col = 2;
										$cur_col = 0;
										$prodcur_arr = array();
										while($row_prod = $db->fetch_array($ret_prod))
										{
											$prodcur_arr[] = $row_prod;
											$HTML_title = $HTML_image = $HTML_desc = '';
											$HTML_sale = $HTML_new = $HTML_compare = $HTML_rating = '';
											$HTML_price = $HTML_bulk= $HTML_bonus = $HTML_compare = $HTML_freedel= $HTML_bonus_bar = '';
											$HTML_title = '<div class="list_d_pdt_name"><a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">'.stripslash_normal($row_prod['product_name']).'</a></div>';
											$HTML_image ='<a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">';
											// Calling the function to get the image to be shown
											$pass_type ='image_thumbpath';
											$img_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,0,0,1);
											if(count($img_arr))
											{
												$HTML_image .= show_image(url_root_image($img_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name'],'','',1);
											}
											else
											{
												// calling the function to get the default image
												$no_img = get_noimage('prod',$pass_type); 
												if ($no_img)
												{
													$HTML_image .= show_image($no_img,$row_prod['product_name'],$row_prod['product_name'],'','',1);
												}       
											}       
											$HTML_image .= '</a>';
											$HTML_desc = stripslash_normal($row_prod['product_shortdesc']);
											if($row_prod['product_saleicon_show']==1)
											{
												$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
												if($desc!='')
												{
													  $HTML_sale = '<div class="list_d_sale"></div>';
												}
											}
											if($row_prod['product_newicon_show']==1)
											{
												$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
												if($desc!='')
												{
													  $HTML_new = '<div class="list_d_new"></div>';
												}
											}
											$module_name = 'mod_product_reviews';
											if(in_array($module_name,$inlineSiteComponents))
											{
												if($row_prod['product_averagerating']>=0)
												{
													$HTML_rating = '<div class="list_d_pdt_rate">'.display_rating($row_prod['product_averagerating'],1).'</div>';
												}
											}
											$price_class_arr['class_type']          = 'div';
											$price_class_arr['normal_class']        = 'normal_shlfA_pdt_priceA';
											$price_class_arr['strike_class']        = 'normal_shlfA_pdt_priceB';
											$price_class_arr['yousave_class']       = 'normal_shlfB_pdt_priceC';
											$price_class_arr['discount_class']      = 'normal_shlfB_pdt_priceC';
											$HTML_price_arry = show_Price($row_prod,$price_class_arr,'shelfcenter_3','',3);
											if($HTML_price_arry['discounted_price'])
											{
												$HTML_price=$HTML_price_arry['discounted_price'];
											}
											else
											{
												$HTML_price=$HTML_price_arry['base_price'];
											}
											if($row_prod['product_bulkdiscount_allowed']=='Y')
											{
												$HTML_bulk = '<img src="'.url_site_image('bulk-d.gif',1).'" title="Multi Buy" />';
											}
												
											if($row_prod['product_bonuspoints']>0)
											{
												$HTML_bonus = '<div class="normal_shlfA_pdt_bonus">Bonus: '.$row_prod['product_bonuspoints'].'</div>';
											}
											else
											{
												$HTML_bonus = '';
											}	
											if($row_prod['product_freedelivery']==1)
											{
												$HTML_freedel = ' <img src="'.url_site_image('freedel.gif',1).'" title="Free Delivery"/>';
											}
											$frm_name = uniqid('shelf_');
											/*if($HTML_bonus!='' or $HTML_bulk !='' or $HTML_freedel!='')
											{
												$HTML_bonus_bar = '<div class="normal_shlfA_pdt_bonus_otr"><div class="bulk_free_outer">'.
																	$HTML_bulk.$HTML_freedel.'</div>'.$HTML_bonus.
																  '</div>';
											}*/	
											if($cur_col==0)
											{
												echo  '<div class="list_d_pdt_otr_row">';
											}	
											/*if($cur_col<$max_col-1)
											{
											  $outer_class = 'normal_shlfA_pdt_outr';
											}	
											else
											{
												$outer_class = 'normal_shlfA_pdt_outr_right';
											}*/
										?>
											<div class="list_d_pdt_otr">
                        
											<?php
                                                echo $HTML_new;
                                                echo $HTML_sale;
                                            ?>	
                                            <div class="list_d_pdt_name_otr">
                                            <?=$HTML_title;?>
                                            <div class="list_d_pdt_des"><?=$HTML_desc?></div>
                                            </div>
                                            <div class="list_d_pdt_img"><?=$HTML_image?></div>
                                            <div class="list_d_pdt_buy">
                          
                                            <div class="list_d_pdt_buy_ba"> 
                                            <div class="list_d_pdt_price"> <?php echo $HTML_price; ?></div>
                                            <div class="list_d_pdt_buy_in"><a href="<?php echo url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']); ?>">BUY NOW</a></div>
                                            </div>
                                            </div>
                                            <?=$HTML_rating;?>	
                                            
                                             <div class="list_d_pdt_offer"><?=$HTML_bulk?><?=$HTML_freedel?></div>
                                            
                    
                                                                
                    
                                            </div> 
										<?
											$cur_col++;
											if($cur_col>=$max_col)
											{
												$cur_col =0;
												echo "</div>";
											}
										}
										if($cur_col<$max_col)
										{
											if($cur_col!=0)
											{ 
												echo "</div>";
											} 
										}
										?>
										
					<?php		
									echo $HTML_paging;
						break;
						case '4row': // case of vertical display
									$HTML_comptitle = $HTML_maindesc = $HTML_paging = $HTML_showall = '';
									
																		
										if ($tot_cnt>0 and ($_REQUEST['req']!='') and $tot_cnt>$prodperpage)
										{
											$paging 		= paging_footer_advanced($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr);
											$HTML_totcnt = '<div class="subcat_nav_pdt_no"><span>'.$paging['total_cnt'].'</span></div>';
											$HTML_paging	= '<div class="page_nav_content">
																		<ul>
																		'.$paging['navigation']['start_nav'].$paging['navigation']['page_no'].$paging['navigation']['end_nav'].'
																		</ul>
															   </div>';					
										}
										$HTML_treemenu = '	<div class="tree_menu_con">
														<div class="tree_menu_top_list"></div>
														<div class="tree_menu_mid_list">
														<div class="tree_menu_content_list">
															<ul class="tree_menu">
															<li><a href="'.url_link('',1).'" title="'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a></li>
															 <li>'.stripslash_normal($Captions_arr['PREORDER']['PREORDER_CAPT']).'</li>
															</ul>
														</div>
														</div>
														<div class="tree_menu_bottom_list"></div>
														</div>';				
										echo $HTML_treemenu;
										echo $HTML_totcnt;	
										echo $HTML_paging;	
									?>
										<div class="shlf_c_con">
										<div class="shlf_c_bottom">
										<? 
										echo $HTML_maindesc;
  										$max_col = 4;
										$cur_col = 0;
										$cnt1 = 1;
										$prodcur_arr = array();
										while($row_prod = $db->fetch_array($ret_prod))
										{
											$prodcur_arr[] = $row_prod;
											$HTML_title = $HTML_image = $HTML_desc = '';
											$HTML_sale = $HTML_new = $HTML_compare = $HTML_rating = '';
											$HTML_price = $HTML_bulk= $HTML_bonus = $HTML_compare = $HTML_freedel= $HTML_bonus_bar = '';
											$HTML_title = '<div class="shlf_c_pdt_name">'.stripslash_normal($row_prod['product_name']).'</div>';
											$HTML_image ='<a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">';
											// Calling the function to get the image to be shown
											$pass_type ='image_thumbpath';
											$img_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,0,0,1);
											if(count($img_arr))
											{
												$HTML_image .= show_image(url_root_image($img_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name'],'','',1);
											}
											else
											{
												// calling the function to get the default image
												$no_img = get_noimage('prod',$pass_type); 
												if ($no_img)
												{
													$HTML_image .= show_image($no_img,$row_prod['product_name'],$row_prod['product_name'],'','',1);
												}       
											}       
											$HTML_image .= '</a>';
											/*$HTML_desc = stripslash_normal($row_prod['product_shortdesc']);
											if($row_prod['product_saleicon_show']==1)
											{
												$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
												if($desc!='')
												{
													  $HTML_sale = '<div class="normal_shlfA_pdt_sale"></div>';
												}
											}
											if($row_prod['product_newicon_show']==1)
											{
												$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
												if($desc!='')
												{
													  $HTML_new = '<div class="normal_shlfA_pdt_new"></div>';
												}
											}
											$module_name = 'mod_product_reviews';
											if(in_array($module_name,$inlineSiteComponents))
											{
												if($row_prod['product_averagerating']>=0)
												{
													$HTML_rating = '<div class="normal_shlfA_pdt_rate">'.display_rating($row_prod['product_averagerating'],1).'</div>';
												}
											}*/
											$price_class_arr['class_type']          = 'div';
											$price_class_arr['normal_class']        = 'normal_shlfA_pdt_priceA';
											$price_class_arr['strike_class']        = 'normal_shlfA_pdt_priceB';
											$price_class_arr['yousave_class']       = 'normal_shlfB_pdt_priceC';
											$price_class_arr['discount_class']      = 'normal_shlfB_pdt_priceC';
											$HTML_price_arry = show_Price($row_prod,$price_class_arr,'shelfcenter_3','',3);
											if($HTML_price_arry['discounted_price'])
											{
												$HTML_price=$HTML_price_arry['discounted_price'];
											}
											else
											{
												$HTML_price=$HTML_price_arry['base_price'];
											}
											/*if($row_prod['product_bulkdiscount_allowed']=='Y')
											{
												$HTML_bulk = '<div class="normal_shlfA_multibuy"><img src="'.url_site_image('multi-buyA.gif',1).'" title="Multi Buy" /></div>';
											}
												
											if($row_prod['product_bonuspoints']>0)
											{
												$HTML_bonus = '<div class="normal_shlfA_pdt_bonus">Bonus: '.$row_prod['product_bonuspoints'].'</div>';
											}
											else
											{
												$HTML_bonus = '';
											}	
											if($row_prod['product_freedelivery']==1)
											{
												$HTML_freedel = ' <div class="normal_shlfA_free"><img src="'.url_site_image('free-dela.gif',1).'" title="Free Delivery"/></div>';
											}
											$frm_name = uniqid('shelf_');
											if($HTML_bonus!='' or $HTML_bulk !='' or $HTML_freedel!='')
											{
												$HTML_bonus_bar = '<div class="normal_shlfA_pdt_bonus_otr"><div class="bulk_free_outer">'.
																	$HTML_bulk.$HTML_freedel.'</div>'.$HTML_bonus.
																  '</div>';
											}	*/
											if($cur_col==0)
											{
												echo  '<div class="shlf_c_pdt_otr_row">';
											}	
											
										?>
											<div class="shlf_c_pdt_otr">
											<?=$HTML_title;?>
                                            <div class="shlf_c_pdt_img"><?=$HTML_image?></div>
                                            <?php
                                            $class_buy = ( $cnt1 % 2 ? 'red' : 'blue' );
                                            ?>
                                            <div class="shlf_c_pdt_buy">
                                            
                                            <?php
                                            if($class_buy == 'red')
                                            {
                                            ?>
                                                <div class="shlf_c_pdt_buy_t"></div>
                                                <div class="shlf_c_pdt_buy_b"> 
                                                <div class="shlf_c_pdt_price">
                                            <?php
                                            }
                                            else
                                            {
                                            ?>
                                                <div class="shlf_c_pdt_buy_ta"></div>
                                                <div class="shlf_c_pdt_buy_ba"> 
                                                <div class="shlf_c_pdt_price">
                                            <?php
                                            }
                                            ?>
                                            
                                            <?=$HTML_price?></div>
                                                <div class="shlf_c_pdt_buy_btn"><a href="<?php echo url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']); ?>">BUY NOW</a></div>
                                                </div>
                                           </div>
                                            
                                            
                                            
                                            </div> 
										<?
											$cur_col++;
											if($cur_col>=$max_col)
											{
												$cur_col =0;
												echo "</div>";
											}
											
										$cnt1++;
										}
										if($cur_col<$max_col)
										{
											if($cur_col!=0)
											{ 
												echo "</div>";
											} 
										}
										?>
                                    </div>   
									</div>
										
					<?php		
									echo $HTML_paging;
						break;
					};
			}
			
		}
	};	
?>
