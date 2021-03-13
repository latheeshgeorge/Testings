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
							$pass_type =  get_default_imagetype('midshelf');
						}	
						else // case of home page
						{
							$Limit = '';
							$shelfData['shelf_displaytype'] = 'list';// making the displaytype for shelf to list even if it is not list, while displaying in home page
							$pass_type = 'image_iconpath';//get_default_imagetype('midshelf');
						}	
						// Get the list of products to be shown in current shelf
						$sql_prod = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
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
							// Number of result to display on the page, will be in the LIMIT of the sql query also
							$querystring = ""; // if any additional query string required specify it over here
							if($_REQUEST['req']!='') // If coming to show the details in middle area other than from the home page then show the details in normal shelf style
								$shelfData['shelf_currentstyle']='nor';
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
													<?php 
													if ($shelfData['shelf_showtitle']==1)// Check whether description is to be displayed
													{
													?>
													<li> <h1 class="pro_name"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h1></li>
													<?php 
													}
													if ($shelfData['shelf_showimage']==1)// Check whether description is to be displayed
													{
													?>
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
													}
												
												$price_class_arr['ul_class'] 		= '';
												$price_class_arr['normal_class'] 	= 'pro_price_offer';
												$price_class_arr['strike_class'] 	= 'pro_price';
												$price_class_arr['yousave_class'] 	= 'pro_price_offer';
												$price_class_arr['discount_class'] 	= 'pro_price_dis';
											?>
										
											<?php
											if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
												echo show_Price($row_prod,$price_class_arr,'cat_detail_1'); 
											?>
											
											<?php	
												$frm_name = uniqid('catdet_');
												$prefix = '<li>';
												$suffix = '</li>';
												//show_excluding_vat_msg($row_prod,'vat_div',$prefix,$suffix);// show excluding VAT msg
												//show_bonus_points_msg($row_prod,'bonus_point',$prefix,$suffix); // Show bonus points
												
											if($shelfData['shelf_showbonuspoints']==1)
											{
												show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points
											}
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
											// If in case total product is less than the max allowed per row then handle that situation
											$prodcur_arr = array();
											?>
								<?php		
									break;
								};
							}
						}
					}
					else
					{
						removefrom_Display_Settings($shelfData['shelf_id'],'mod_shelf');
					}
				}
			}	
		}
	};	
?>