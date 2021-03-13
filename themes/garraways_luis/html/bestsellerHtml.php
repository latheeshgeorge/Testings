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
											$HTML_totcnt = '<div class="sub_cat_hdr_otr"><div class="sub_cat_hdr">'.$paging['total_cnt'].'</div></div>';
											$HTML_paging ='<div class="page_nav_content">
																			<ul>';//.'';
																				$HTML_paging .= $paging['navigation']['start_nav'].$paging['navigation']['page_no'].$paging['navigation']['end_nav'];
																				$HTML_paging .= ' 
																			</ul>
															</div>';
										}
									
										$HTML_treemenu = '
											<div class="tree_menu_con">
													<div class="tree_menu_mid_list">
														<div class="tree_menu_content_list">
															<ul class="tree_menu">
																<li><a href="'.url_link('',1).'" title="'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a></li>
																<li>'.stripslash_normal($Captions_arr['BEST_SELLERS']['BEST_SELLERS_MAIN_HEAD']).'</li>
															</ul>
														</div>
													</div>
											</div>';
										echo $HTML_treemenu;
										echo $HTML_totcnt;
										echo $HTML_paging;
										
										
										$max_col = 3;
										$cur_col = 0;
										$prodcur_arr = array();
										?>
										<div class="product_list_outer"> 
					<div class="pdt_list_outer">
		<?php	while($row_prod = $db->fetch_array($ret_prod))
				{
					$prodcur_arr[] = $row_prod;
					$HTML_title = $HTML_image = $HTML_desc = '';
					$HTML_sale = $HTML_new = $HTML_compare = $HTML_rating = '';
					$HTML_price = $HTML_bulk= $HTML_bonus = $HTML_compare = $HTML_freedel= $HTML_bonus_bar = '';
					$HTML_new = $HTML_sale = '';
					
					//if($cat_det['product_showtitle']==1)// whether title is to be displayed
						$HTML_title = '<div class="pdt_list_pdt_name"><a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">'.stripslash_normal($row_prod['product_name']).'</a></div>';
				
					//if ($cat_det['product_showimage']==1)// Check whether description is to be displayed
					{
						$HTML_image ='<a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">';
						// Calling the function to get the image to be shown
						//$pass_type = 'image_thumbcategorypath';
						$pass_type = 'image_thumbpath';
						
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
					}
					//if ($cat_det['product_showshortdescription']==1)// Check whether description is to be displayed
					{
						$HTML_desc = stripslash_normal($row_prod['product_shortdesc']);
					}
					/*
					$price_class_arr['class_type']          = 'div';
					$price_class_arr['normal_class']        = 'normal_shlfB_pdt_priceA';
					$price_class_arr['strike_class']        = 'normal_shlfB_pdt_priceB';
					$price_class_arr['yousave_class']       = 'normal_shlfB_pdt_priceC';
					$price_class_arr['discount_class']      = 'normal_shlfB_pdt_priceC';
					$price_arr =  show_Price($row_prod,array(),'compshelf',false,4);
					
					if($price_arr['discounted_price'])
						$HTML_price = $price_arr['discounted_price'];
					else
						$HTML_price = $price_arr['base_price'];
						*/ 
				
					//$HTML_price = show_Price($row_prod,$price_class_arr,'shelfcenter_3');
					if($cur_col==0)
					{
						echo  '<div class="pdt_list_thumb_outer">';
					}
					if($cur_col%2==0 && $cur_col!=0)	
					{
						$cls = "pdt_list_pdt";
					}
					else
					{
						$cls = "pdt_list_pdt";
					}
		?>			<div class="<?php echo $cls?>">
						<div class="pdt_list_pdt_mid">
		<?php		if($row_prod['product_saleicon_show']==1)
					{
						$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
						//if($desc!='')
						{
							$HTML_sale = '<div class="pdt_list_sale_3row"></div>';
						}
					}
					if($row_prod['product_newicon_show']==1)
					{
						$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
						//if($desc!='')
						{
							$HTML_new = '<div class="pdt_list_new_3row"></div>';
						}
					}
					echo $HTML_new;
					echo  $HTML_sale;
					
					//if($cat_det['product_showrating']==1)
					{
						$module_name = 'mod_product_reviews';
						if(in_array($module_name,$inlineSiteComponents))
						{
							if($row_prod['product_averagerating']>=0)
							{
								$HTML_rating = '<div class="list_d_pdt_rate">'.display_rating($row_prod['product_averagerating'],1).'</div>';
							}
						}
					}
					//echo $HTML_rating;
		?>					
		<?=$HTML_title;?>
		<div class="pdt_list_pdt_r"><?php echo $HTML_image?></div>
							
							<?php
							//if($cat_det['product_showshortdescription']==1)// whether title is to be displayed
							{
								
								echo '<p class="product_descB">'.stripslash_normal($row_prod['product_shortdesc']).'</p>';
								
							}
							$price_class_arr['ul_class'] 		= 'shelfBul_lu';
													$price_class_arr['normal_class'] 	= 'shelfBnormalprice_lu';
													$price_class_arr['strike_class'] 	= 'shelfBstrikeprice_lu';
													$price_class_arr['yousave_class'] 	= 'shelfByousaveprice_lu';
													$price_class_arr['discount_class'] 	= 'shelfBdiscountprice_lu';
													//	echo show_Price($row_prod,$price_class_arr,'shelfcenter_1'); price_categorydetails_1_reqbreak
													echo show_Price($row_prod,$price_class_arr,'cat_detail_1');
													show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg

							?>
							<div class="pdt_list_pdt_l">
                            <div class="pdt_list_pdt_buy_otr">
                            
		<?php
				if($row_prod['product_bonuspoints']>0 and $cat_det['product_showbonuspoints']==1)
				{
				$HTML_bonus = 'Bonus: '.$row_prod['product_bonuspoints'].'';
				}
				else
				{
				$HTML_bonus = '';
				}
				/*
				?>
				<div class="pdt_list_bonus">  <?php echo $HTML_bonus;?> </div>  
					*/
					?>		
		<?php		
		/*
		if(isProductCompareEnabled())
					{
						?>
						<div class="pdt_list_pdt_compare">
							<?php
						if(is_array($_SESSION['compare_products']))
						{
							if(in_array($row_prod['product_id'],$_SESSION['compare_products']))
							{
								$select = "checked";
							}
							else
							{
								$select = "";
							}
						}
						else
							$select = "";
						$compare_button_displayed = true; ?><form name="add_to_compare" id="add_to_compare" action="" method="post" ><input type="hidden" name="submit_Compare_pdts" id="submit_Compare_pdts" value="1"><input type="checkbox" name="compare_products_<?php echo $row_prod['product_id'];?>" value="ADD TO COMPARE" class="buttonred_large" onclick="addtoCompare(<?php echo $row_prod['product_id']?>)" id="compare_products_<?php echo $row_prod['product_id'];?>" <?php echo $select ?> /></form> Select To Compare
						 </div>
						<?php
				}
				*/ 
		?>					
		<div class="more_info_lu">			
		<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" class="det_buy_link_more" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslash_normal($Captions_arr['COMMON']['MORE_INFO'])?></a>
		</div>
        <?php		$frm_name = uniqid('shelf_');
		?>
					<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
                        <input type="hidden" name="fpurpose" value="" />
                        <input type="hidden" name="fproduct_id" value="" />
                        <input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
                        <input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
                        <input type="hidden" name="qty" id="qty" value="1" />																
		<?php
                        $class_arr                      = array();
                        $class_arr['ADD_TO_CART']       = 'cupid-green_bt';
                        $class_arr['PREORDER']          = 'cupid-green_bt';
                        $class_arr['ENQUIRE']           = 'cupid-green_bt';
                        $class_arr['QTY_DIV']           = 'normal_shlfB_pdt_input';
                        $class_arr['QTY']               = 'quainput';							
                        /* Code for ajax setting starts here */
                        $class_arr['BTN_CLS']           = 'cupid-green';												
                        //show_addtocart($row_prod,$class_arr,$frm_name,false,'','','normal_shlfB_pdt_buy_btn');
                        show_addtocart_v5_ajax_local($frm_name,$row_prod,$class_arr,false,array(),true);
                        /* Code for ajax setting ends here */
                        //echo "product display style - ".$cat_det['product_displaytype'];
		?>
					</form>				
				
				</div>
				</div>
				<div class="pdt_list_m_otr">
				           
				<?php
				getpacksize_puregusto($row_prod['product_id']);
				/*
				<div class="pdt_list_pdt_more"><a class="" title="Love Meter T-Shirt Heart Lights" href="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>">More Info</a></div>
				*/?> 
				</div>   
				
				<?php
				/*
				if($row_prod['product_bulkdiscount_allowed']=='Y' || $row_prod['product_freedelivery']==1)
				{
				echo "<div class='pdt_list_free_otr'>";
				}
				
				if($row_prod['product_freedelivery']==1)
				{
				echo $HTML_freedel = ' <div class="pdt_list_free_del"> </div>';
				}
				if($row_prod['product_bulkdiscount_allowed']=='Y')
				{
				echo $HTML_bulk = '<div class="pdt_list_free_bulk"> </div>';
				}
				if($row_prod['product_bulkdiscount_allowed']=='Y' || $row_prod['product_freedelivery']==1)
				{
				echo "</div>";
				}
				*/ 
				/*				  <div class="pdt_list_pdt_des"><?php echo $HTML_desc;?></div>
				*/ 
				
				
				?>
				
				
				</div>
				</div>
				
				<?php
				
				
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
				</div>
				</div>
										
									<?php
										
					echo $HTML_paging;
				}
			}
	};	
?>
