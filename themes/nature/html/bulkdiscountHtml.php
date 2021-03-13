<?php
	/*############################################################################
	# Script Name 	: bestsellerHtml.php
	# Description 	: Page which holds the display logic for middle Bestsellers
	# Coded by 		: Sny
	# Created on	: 11-Aug-2009
	# Modified by	: 
	# Modified On	: 
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
							<div class="treemenu">
							  <ul>
								<li><a href="<? url_link('');?>" title="<?=stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']);?>"><?=stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']);?></a> &gt;&gt; </li>
								<li><?php echo stripslash_normal($Captions_arr['BULKDISC_PROD']['BULKDISC_PROD'])?></li>
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
								<div class="mid_shlf2_pdt_name"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslash_normal($row_prod['product_name'])?>"><?php echo stripslash_normal($row_prod['product_name'])?></a></div>
								<div class="mid_shlf2_pdt_image">
								<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslash_normal($row_prod['product_name'])?>">
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
								if($prod_compare_enabled)
								{
								?>
									<div class="mid_shlf2_pdt_compare" >
									<?php	dislplayCompareButton($row_prod['product_id']);?>
									</div>
								<?php	
								}
								?>
								<div class="mid_shlf2_pdt_des">
								<?php echo stripslash_normal($row_prod['product_shortdesc'])?>
								</div>
								<?php
								$module_name = 'mod_product_reviews';
								if(in_array($module_name,$inlineSiteComponents))
								{
									if($row_prod['product_averagerating']>=0)
									{
								?>
									<div class="mid_shlf_pdt_rate">
									<?php
										display_rating($row_prod['product_averagerating']);
									?>
									</div>
								<?php
									}
								}
								show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points
								if($row_prod['product_saleicon_show']==1)
								{
									$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
									if($desc!='')
									{
								?>	
										<div class="mid_shlf2_pdt_sale"><?php echo $desc?></div>
								<?php
									}
								}	
								if($row_prod['product_newicon_show']==1)
								{
									$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
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
									//show_addtocart($row_prod,$class_arr,$frm_name);
									/* Code for ajax setting starts here */
									$class_arr['BTN_CLS']           = 'mid_shlf2_buy_link';
									show_addtocart_v5_ajax($frm_name,$row_prod,$class_arr);
									/* Code for ajax setting ends here */
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
							?>
							</div>
					<?php		
				}
			}
	};	
?>