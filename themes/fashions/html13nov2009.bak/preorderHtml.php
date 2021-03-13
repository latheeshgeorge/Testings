<?php
	/*############################################################################
	# Script Name 	: preorderHtml.php
	# Description 	: Page which holds the display logic for middle preorder
	# Coded by 		: LH
	# Created on	: 27-Nov-2008
	# Modified by	: LH
	# Modified On	: 27-Nov-2008
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
										a.product_stock_notification_required,a.product_alloworder_notinstock,product_alloworder_notinstock,a.product_variables_exists,a.product_variablesaddonprice_exists,
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
						
						if ($db->num_rows($ret_prod))
						{
							// Number of result to display on the page, will be in the LIMIT of the sql query also
							$querystring = ""; // if any additional query string required specify it over here
							// Calling the function to get the type of image to shown for current 
							$pass_type = get_default_imagetype('prodcat');
							$comp_active = isProductCompareEnabled();
								switch($Settings_arr['preorder_prodlisting'])
								{
									case 'nor': // case of one in a row for normal
									?>

											<div class="pro_det_treemenu"<a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?php echo $cur_title?></div>
										<?
											if ($Captions_arr['PREORDER']['PREORDER_CAPTION']!='')
											{
										?>
												<div class="best_seller_heading"><?php echo stripslashes($Captions_arr['PREORDER']['PREORDER_CAPTION']);?></div>
										<?php		
											}
											if ($tot_cnt>0 )
											{
											?>
												 <div class="pro_nav_right" align="right" >
										 <div class="pro_nav_products" align="center"><?php paging_show_totalcount($tot_cnt,'Products',$start_var['pg'],$start_var['pages'])?></div>

													<?php 
														$path = '';
														$pageclass_arr['container'] = 'pagenavcontainer';
													$pageclass_arr['navvul']	= 'pagenavul';
													$pageclass_arr['current']	= 'pagenav_current';
														$query_string .= "disp_id=".$_REQUEST['disp_id'];
														paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr,0); 	
													?>													
												</div>
											<?php
											}
											while($row_prod = $db->fetch_array($ret_prod))
											{								?>
										<div class="product_list_main">
										<ul>
														
														<li> <h1 class="pro_name"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h1></li>
													<li><h1><div class="list_img" align="center">
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
													</div></h1>
													</li>
													<? 
												
												$price_class_arr['ul_class'] 		= '';
												$price_class_arr['normal_class'] 	= 'pro_price_offer';
												$price_class_arr['strike_class'] 	= 'pro_price';
												$price_class_arr['yousave_class'] 	= 'pro_price_offer';
												$price_class_arr['discount_class'] 	= 'pro_price_dis';
											?>
										
											<?php
												echo show_Price($row_prod,$price_class_arr,'cat_detail_1'); 
											?>
											
											<?php	
												$frm_name = uniqid('catdet_');
												$prefix = '<li>';
												$suffix = '</li>';
												//show_excluding_vat_msg($row_prod,'vat_div',$prefix,$suffix);// show excluding VAT msg
												//show_bonus_points_msg($row_prod,'bonus_point',$prefix,$suffix); // Show bonus points
											?>	
											<?php if($comp_active)  { 
											
												dislplayCompareButton($row_prod['product_id'],$prefix,$suffix);
											
											 }
											 ?>
											 <li>
												<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
													<input type="hidden" name="fpurpose" value="" />
													<input type="hidden" name="fproduct_id" value="" />
													<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
													<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
													<label><?php show_moreinfo($row_prod,'product_info')?></label>
													<? 
													$prefix = "<DIV class='product_list_button_list'><label>"; 
													$suffix = "</label> </DIV>";
													?>
														<?php
															$class_arr 					= array();
															$class_arr['ADD_TO_CART']	= 'product_list_button';
															$class_arr['PREORDER']			= 'product_list_button';
															$class_arr['ENQUIRE']			= 'product_list_button';
															show_addtocart($row_prod,$class_arr,$frm_name,false,$prefix,$suffix)
														?>
														
													</form>
											</li>
											</ul>
											</div>
											<? 
											}
									break;
									
								};
							
							
						}
					
				
			
		}
	};	
?>