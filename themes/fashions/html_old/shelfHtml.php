<?php
	/*############################################################################
	# Script Name 	: shelfHtml.php
	# Description 	: Page which holds the display logic for middle shelves
	# Coded by 		: Sny
	# Created on	: 29-Dec-2007
	# Modified by	: Sny
	# Modified On	: 01-Feb-2008
	##########################################################################*/
	class shelf_Html
	{
		// Defining function to show the shelf details
		function Show_Shelf($cur_title,$shelf_arr)
		{ 
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr;
			if (count($shelf_arr))
			{
				$shelfsort_by			= $Settings_arr['product_orderfield_shelf'];
				$prodperpage			= $Settings_arr['product_maxcntperpage_shelf'];// product per page
			
				switch ($shelfsort_by)
				{
					case 'custom': // case of order by customer fiekd
					$shelfsort_by		= 'b.product_order';
					break;
					case 'product_name': // case of order by product name
					$shelfsort_by		= 'a.product_name';
					break;
					case 'price': // case of order by price
					$shelfsort_by		= 'a.product_webprice';
					break;
					case 'product_id': // case of order by price
					$prodsort_by		= 'a.product_id';
					break;	
					default: // by default order by product name
					$shelfsort_by		= 'a.product_name';
					break;
				};
				$shelfsort_order		= $Settings_arr['product_orderby_shelf'];
				$prev_shelf				= 0;
				//Iterating through the shelf array to fetch the product to be shown.
				foreach ($shelf_arr as $k=>$shelfData)
				{
					// Check whether shelf_activateperiodchange is set to 1
					$active 	= $shelfData['shelf_activateperiodchange'];
					if($active==1)
					{
						$proceed	= validate_component_dates($shelfData['shelf_displaystartdate'],$shelfData['shelf_displayenddate']);
					}
					else
						$proceed	= true;	
					if ($proceed)
					{
						/*// Getting the feature_id for mod_shelf
						$sql_feature = "SELECT feature_id FROM features WHERE feature_modulename='mod_shelf'";
						$ret_feature = $db->query($sql_feature);
						if ($db->num_rows($ret_feature))
						{
							$row_feature 	= $db->fetch_array($ret_feature);
							$feat_id		= $row_feature['feature_id'];
						}
						// Find the layoutid for current layout code
						$sql_layout = "SELECT layout_id 
										FROM 
											themes_layouts 
										WHERE 
											themes_theme_id = $ecom_themeid 
											AND layout_code='$default_layout'";
						$ret_layout = $db->query($sql_layout);
						if ($db->num_rows($ret_layout))
						{
							$row_layout = $db->fetch_array($ret_layout);
							$layid		= $row_layout['layout_id'];
						}					
						// Get the title to be shown from the display settings table
						$sql_disp = "SELECT display_title 
										FROM 
											display_settings 
										WHERE 
											sites_site_id = $ecom_siteid 
											AND features_feature_id=$feat_id 
											AND display_position ='middle' 
											AND themes_layouts_layout_id=$layid 
											AND layout_code ='".$default_layout."' 
											AND display_component_id=".$shelfData['shelf_id'];
						$ret_disp = $db->query($sql_disp);
						if ($db->num_rows($ret_disp))
						{
							$row_disp 	= $db->fetch_array($ret_disp);
							$cur_title 	= stripslashes($row_disp['display_title']); 
						}
						// check whether any title passed and title not obtained from the display settings for current position 
						//Only for this theme.... $title=<shelfname>
						if(!$cur_title) $cur_title = $shelfData['shelf_name'];*/
						// Get the total number of product in current shelf
						$sql_totprod = "SELECT count(b.products_product_id) 
									FROM 
										products a,product_shelf_product b 
									WHERE 
										b.product_shelf_shelf_id = ".$shelfData['shelf_id']." 
										AND a.product_id = b.products_product_id 
										AND a.product_hide = 'N' ";
						$ret_totprod 	= $db->query($sql_totprod);
						list($tot_cnt) 	= $db->fetch_array($ret_totprod); 
						
						// Call the function which prepares variables to implement paging
						$ret_arr 		= array();
						$pg_variable	= 'shelf_'.$shelfData['shelf_id'].'_pg';
						if ($_REQUEST['req']!='')// LIMIT for products is applied only if not displayed in home page
						{
							$start_var 		= prepare_paging($_REQUEST[$pg_variable],$prodperpage,$tot_cnt);
							$Limit			= " LIMIT ".$start_var['startrec'].", ".$prodperpage;
							$shelfData['shelf_displaytype'] = 'row';
						}	
						else // case of home page
						{
							$Limit = '';
							$shelfData['shelf_displaytype'] = 'list';// making the displaytype for shelf to list even if it is not list, while displaying in home page
						}	
						
									
						// Get the list of products to be shown in current shelf
						$sql_prod = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
										a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
										a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
										product_total_preorder_allowed,a.product_applytax,a.product_shortdesc,a.product_bonuspoints,
										a.product_stock_notification_required,a.product_alloworder_notinstock     
									FROM 
										products a,product_shelf_product b 
									WHERE 
										b.product_shelf_shelf_id = ".$shelfData['shelf_id']." 
										AND a.product_id = b.products_product_id 
										AND a.product_hide = 'N' 
									ORDER BY 
										$shelfsort_by $shelfsort_order 
									$Limit	";
						$ret_prod = $db->query($sql_prod);
						if ($db->num_rows($ret_prod))
						{
							$comp_active = isProductCompareEnabled();
							$pass_type = 'image_iconpath';//get_default_imagetype('midshelf');
							// Number of result to display on the page, will be in the LIMIT of the sql query also
							$querystring = ""; // if any additional query string required specify it over here
							
							if($shelfData['shelf_currentstyle']=='nor') // case of normal design layout
							{
								switch($shelfData['shelf_displaytype'])
								{
									case 'list': // case of list style
									?>
									<div class="shelfB" align="left"> 
                   					<?php 
										if($cur_title)
										{
									?>
											<div class="shelfBheader" ><?php echo $cur_title?></div >
									<?php
										}
									?>		
											<div class="shelfBli" >
												<ul class="shelfinner">
												<?php
												while($row_prod = $db->fetch_array($ret_prod))
												{
												?>
													<li>
														<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
															<?php
																// Calling the function to get the type of image to shown for current 
																
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
													</li >
												<?php
												}
												?>
												<li ><a href="<?php  url_shelf_all($shelfData['shelf_id'],$shelfData['shelf_name'],-1)?>" class="shelfBbutton" title="<?php echo $Captions_arr['COMMON']['SHOW_ALL']?>"><?php echo $Captions_arr['COMMON']['SHOW_ALL']?></a></li >  
												</ul>     
											</div>
							
									</div>
									<?php
									break;
									case 'row': // case of three in a row for normal
									
										if($cur_title)
										{
										?>
											<div class="best_seller_heading"><?php echo $cur_title?></div>
										<?php
										}
										$desc = trim($shelfData['shelf_description']);
										if($desc!='' and $desc!='&nbsp;')
										{
									?>
											<div class="pro_details_content"><?php echo $desc?></div>
									<?php		
										}
										if ($tot_cnt>0 and ($_REQUEST['req']!=''))
										{
										?>
										<div class="pro_nav_right" align="right" >
										 <div class="pro_nav_products" align="center"><?php paging_show_totalcount($tot_cnt,'Products',$start_var['pg'],$start_var['pages'])?></div>
											
												<?php 
													$path = '';
													$path = '';
												$pageclass_arr['container'] = 'pagenavcontainer';
												$pageclass_arr['navvul']	= 'pagenavul';
												$pageclass_arr['current']	= 'pagenav_current';
													
													//show_paging($path,$_REQUEST[$pg_variable],$start_arr['counterstart'],$tot_cnt,$prodperpage,$pg_variable,'')
														$query_string .= "";
														paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr,0); 	
												?>	
											</div>
										<?php
										}
										?>	
										<?php
											$prodcur_arr = array();
											while($row_prod = $db->fetch_array($ret_prod))
											{
												$prodcur_arr[] = $row_prod;
												
												//
												?>
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
												<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls">
													<input type="hidden" name="fpurpose" value="" />
													<input type="hidden" name="fproduct_id" value="" />
													<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
													
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
											// If in case total product is less than the max allowed per row then handle that situation
											$prodcur_arr = array();
											?>
								<?php		
									break;
								};
							}
						}
					}
				}
			}	
		}
	};	
?>