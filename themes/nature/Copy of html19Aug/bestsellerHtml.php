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

			$Captions_arr['BEST_SELLERS'] = getCaptions('BEST_SELLERS');
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
										totcnt $bestsort_order 
									$Limit "; //orders a,order_details b,products p 
				}		
				$ret_prod = $db->query($sql_best);
				if ($db->num_rows($ret_prod))
				{
					// Calling the function to get the type of image to shown for current 
					$pass_type = get_default_imagetype('midshelf');
					$prod_compare_enabled = isProductCompareEnabled();
					// Number of result to display on the page, will be in the LIMIT of the sql query also
					$querystring = ""; // if any additional query string required specify it over here
					switch($Settings_arr['bestseller_prodlisting'])
					{
						case '1row': // case of horizontal for normal
						?>
							<div class="treemenu">
							  <ul>
								<li><a href="<? url_link('');?>" title="<?=stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']);?>"><?=stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']);?></a> &gt;&gt; </li>
								<li><?php echo $cur_title?></li>
							  </ul>
							</div>
							<div class="mid_shlf_con" >
							<?php
							if ($tot_cnt>0 and ($_REQUEST['req']!='') and $tot_cnt>$prodperpage)
							{
							?>
								<div class="list_page_con">
								<div class="list_page_top"></div>
								<div class="list_page_middle">
								<div class="pagingcontainertd" >
          						<div class="pro_nav_pages" align="right"><?php echo paging_show_totalcount($tot_cnt,'Products',$start_var['pg'],$start_var['pages'])?></div>
												
									<?php 
										$path = '';
										$query_string .= "";
										paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr,0); 	
									?>
								</div>
								</div>
							   <div class="list_page_bottom"></div>
								</div>
							<?php
							}
							while($row_prod = $db->fetch_array($ret_prod))
							{
							?>
								<div class="mid_shlf_top"></div>
								<div class="mid_shlf_middle">
								<div class="mid_shlf_pdt_name"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></div>
								<div class="mid_shlf_mid">
									<div class="mid_shlf_pdt_image">
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
									if($comp_active)
									{
									?>
										<div class="mid_shlf_pdt_compare" >
										<?php	dislplayCompareButton($row_prod['product_id']);?>
										</div>
									<?php	
									}
									?>
									</div>
								</div>
								<div class="mid_shlf_pdt_des">
									<?php
									echo stripslashes($row_prod['product_shortdesc']);
									$module_name = 'mod_product_reviews';
									if(in_array($module_name,$inlineSiteComponents))
									{
										if($row_prod['product_averaterating']>=0)
										{
										?>
											<div class="mid_shlf_pdt_rate">
												<?php
												for ($i=0;$i<$row_prod['product_averagerating'];$i++)
												{
													echo '<img src="'.url_site_image('star-red.gif',1).'" border="0" alt="revscoreimg" />&nbsp;'; 
												}
												?>
											</div>
										<?php
										}
									}	
									if($row_prod['product_bulkdiscount_allowed']=='Y')
									{
									?>
										<div class="mid_shlf_pdt_bulk"><img src="<?php url_site_image('bulk-discount.gif')?>" alt="Bulk Discount"/></div>
									<?php
									}
									if($row_prod['product_saleicon_show']==1)
									{
									$desc = stripslashes(trim($row_prod['product_saleicon_text']));
									if($desc!='')
									{
									?>	
										<div class="mid_shlf_pdt_sale"><?php echo $desc?></div>
									<?php
									}
									}
									if($row_prod['product_newicon_show']==1)
									{
										$desc = stripslashes(trim($row_prod['product_newicon_text']));
										if($desc!='')
										{
										?>
											<div class="mid_shlf_pdt_newsale"><?php echo $desc?></div>
										<?php
										}
									}
									?>
								</div>
								<div class="mid_shlf_pdt_price">
									<?php 
									if($row_prod['product_freedelivery']==1)
									{	
									?>
										<div class="mid_shlf_free"></div>
									<?php
									}
									$price_class_arr['class_type'] 		= 'div';
									$price_class_arr['normal_class'] 	= 'shlf_normalprice';
									$price_class_arr['strike_class'] 	= 'shlf_strikeprice';
									$price_class_arr['yousave_class'] 	= 'shlf_yousaveprice';
									$price_class_arr['discount_class'] 	= 'shlf_discountprice';
									echo show_Price($row_prod,$price_class_arr,'bestseller_1');
									$frm_name = uniqid('best_');
									?>	
									<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
									<input type="hidden" name="fpurpose" value="" />
									<input type="hidden" name="fproduct_id" value="" />
									<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
									<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
									<div class="mid_shlf_buy">
									<div class="mid_shlf_info_btn"><?php show_moreinfo($row_prod,'mid_shlf_info_link')?></div>
									<div class="mid_shlf_buy_btn">
									<?php
										$class_arr 					= array();
										$class_arr['ADD_TO_CART']	= 'mid_shlf_buy_link';
										$class_arr['PREORDER']		= 'mid_shlf_buy_link';
										$class_arr['ENQUIRE']		= 'mid_shlf_buy_link';
										show_addtocart($row_prod,$class_arr,$frm_name)
									?>
									</div>
									</div>
									</form>       
								</div>
								</div>
								<div class="mid_shlf_bottom"></div>
								<? 
								}
								?>
							</div>
						<?php
						break;
						case '2row': // case of Vertical
						?>
							<div class="treemenu">
							  <ul>
								<li><a href="<? url_link('');?>" title="<?=stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']);?>"><?=stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']);?></a> &gt;&gt; </li>
								<li><?php echo $cur_title?></li>
							  </ul>
							</div>
							<div class="mid_shlf2_con" >
							<?php
							if ($tot_cnt>0 and ($_REQUEST['req']!='') and $tot_cnt>$prodperpage)
							{
							?>
								<div class="list_page_con">
								<div class="list_page_top"></div>
								<div class="list_page_middle">
								<div class="pagingcontainertd" >
          						<div class="pro_nav_pages" align="right"><?php echo paging_show_totalcount($tot_cnt,'Products',$start_var['pg'],$start_var['pages'])?></div>
												
									<?php 
										$path = '';
										$query_string .= "";
										paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr,0); 	
									?>
								</div>
								</div>
							   <div class="list_page_bottom"></div>
								</div>
							<?php
							}
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
									  echo '<div class="mid_shlf2_con_main">';
									}
									$cur_col ++;

							?>
								<div class="mid_shlf2_con_pdt">
								<div class="mid_shlf2_top"></div>
								<div class="mid_shlf2_middle">
								<div class="mid_shlf2_pdt_name"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></div>
								<div class="mid_shlf2_pdt_image">
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
								<div class="mid_shlf2_free_con">
								<?php
								if($row_prod['product_freedelivery']==1)
								{	
								?>
								<div class="mid_shlf2_free"></div>
								<?php
								}
								if($row_prod['product_bulkdiscount_allowed']=='Y')
								{
								?>
									<div class="mid_shlf2_pdt_bulk"><img src="<?php url_site_image('bulk-discount.gif')?>" border="0" alt="Bulk Discount" /></div>
								<?php
								}
								?>
								</div>
								<?php
								if($comp_active)
								{
								?>
									<div class="mid_shlf2_pdt_compare" >
									<?php	dislplayCompareButton($row_prod['product_id']);?>
									</div>
								<?php	
								}
								?>
								<div class="mid_shlf2_pdt_des">
								<?php echo stripslashes($row_prod['product_shortdesc'])?>
								</div>
								<?php
								if($row_prod['product_saleicon_show']==1)
								{
									$desc = stripslashes(trim($row_prod['product_saleicon_text']));
									if($desc!='')
									{
								?>	
										<div class="mid_shlf2_pdt_sale"><?php echo $desc?></div>
								<?php
									}
								}	
								if($row_prod['product_newicon_show']==1)
								{
									$desc = stripslashes(trim($row_prod['product_newicon_text']));
									if($desc!='')
									{
								?>
										<div class="mid_shlf2_pdt_newsale"><?php echo $desc?></div>
								<?php
									}
								}	
								?>
								
								<div class="mid_shlf2_buy">
								<?php
									$frm_name = uniqid('best_');
								?>	
								<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
								<input type="hidden" name="fpurpose" value="" />
								<input type="hidden" name="fproduct_id" value="" />
								<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
								<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
								<div class="mid_shlf2_info_btn"><?php show_moreinfo($row_prod,'mid_shlf2_info_link')?></div>
								<div class="mid_shlf2_buy_btn">
								<?php
									$class_arr 					= array();
									$class_arr['ADD_TO_CART']	= 'mid_shlf2_buy_link';
									$class_arr['PREORDER']		= 'mid_shlf2_buy_link';
									$class_arr['ENQUIRE']		= 'mid_shlf2_buy_link';
									show_addtocart($row_prod,$class_arr,$frm_name)
								?>
								</div>
								</form>
								</div>
								<div class="mid_shlf2_pdt_price">
								<?php
								$price_class_arr['class_type'] 		= 'div';
								$price_class_arr['normal_class'] 	= 'shlf2_normalprice';
								$price_class_arr['strike_class'] 	= 'shlf2_strikeprice';
								$price_class_arr['yousave_class'] 	= 'shlf2_yousaveprice';
								$price_class_arr['discount_class'] 	= 'shlf2_discountprice';
								echo show_Price($row_prod,$price_class_arr,'bestseller_3');	
								?>
								</div>
								</div>
								<div class="mid_shlf2_bottom"></div>
								</div>
								<?php
									if($cur_col>=$max_col)
									{
										$cur_col =0;
										echo "</div>";
									}
								}
								// If in case total product is less than the max allowed per row then handle that situation
								if($cur_col<$max_col)
								{
									if($cur_col!=0)
									{ 
										echo "</div>";
									} 
								}
								?>
								
							</div>
					<?php		
						break;
					};
				}
			}
	};	
?>