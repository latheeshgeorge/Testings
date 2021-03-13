<?php
	/*############################################################################
	# Script Name 	: shelfgroupHtml.php
	# Description 	: Page which holds the display logic for middle shelves
	# Coded by 		: Joby
	# Created on	: 09-May-2011
	# Modified by	: 
	# Modified On	: 
	##########################################################################*/
	class shelfgroup_Html
	{
		// Defining function to show the shelf details
		function Show_Shelfgroup($cur_title,$shelfgroup_arr)
		{
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents,$shelf_for_inner;
			if (count($shelfgroup_arr))
			{
				//print_r($Settings_arr);
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
				$show_max               =0;
				//print_r($shelfgroup_arr);
				
				?>
				<script type="text/javascript">
				function showmask_shelfgrp(id)
				{
					objs = eval('document.getElementById("'+id+'")');
					objs.style.display = 'block';
				}
				function hidemask_shelfgrp(id)
				{
					objs = eval('document.getElementById("'+id+'")');
					objs.style.display = 'none';
				}
				</script>
                <div class="group_shlf_mid_con">
                <div class="group_shlf_mid_top"></div>
                <div class="group_shlf_mid_mid">
                
                <div class="group_shlf_mid_tab">
                        <ul class="group_protab">
                
				<?php

				foreach ($shelfgroup_arr as $k=>$shelfgroupData)
			/*1*/	{
					
					 $shelfgroup_id = $shelfgroupData['id'];
			
									
				   $sql_shelf_tab = "SELECT a.shelf_id,a.shelf_name
								FROM 
									product_shelf a LEFT JOIN shelf_group_shelf b 
									 ON (a.shelf_id = b.shelf_shelf_id ) 
								WHERE
									a.sites_site_id = $ecom_siteid 
									AND b.shelf_group_id  = $shelfgroup_id 
									AND a.shelf_hide = 0
									ORDER BY b.shelf_order 
								    ";
								    
					  $sql_shelf_tab = "SELECT a.shelf_id,a.shelf_name
								FROM 
									product_shelf a ,shelf_group_shelf b 
									 
								WHERE
									a.shelf_id = b.shelf_shelf_id
									AND a.sites_site_id = $ecom_siteid 
									AND b.shelf_group_id  = $shelfgroup_id 
									AND a.shelf_hide = 0
									ORDER BY b.shelf_order 
								    ";			    
				$ret_shelf_tab = $db->query($sql_shelf_tab);
					if ($db->num_rows($ret_shelf_tab))// Check whether result is there
					{
						$cnt1 =1;
						while ($row_shelf_tab = $db->fetch_array($ret_shelf_tab))
						{
							?>
                            <?php
							 if($cnt1 == 1)
							 {
							 ?>
							 <li><div id="tsghead_<?php echo $row_shelf_tab['shelf_id']; ?>_<?php echo $shelfgroup_id; ?>" onclick="show_current_tab('sgtab_<?php echo $row_shelf_tab['shelf_id']; ?>_<?php echo $shelfgroup_id; ?>','tsghead_<?php echo $row_shelf_tab['shelf_id']; ?>_<?php echo $shelfgroup_id; ?>','<?php echo $shelfgroup_id; ?>')" class="pro_groupseltableft"><span><?php echo stripslashes($row_shelf_tab['shelf_name']); ?></span></div></li>                                        
							<?php
							 }
							 else
							 {
							 ?>
							<li><div id="tsghead_<?php echo $row_shelf_tab['shelf_id']; ?>_<?php echo $shelfgroup_id; ?>" onclick="show_current_tab('sgtab_<?php echo $row_shelf_tab['shelf_id']; ?>_<?php echo $shelfgroup_id; ?>','tsghead_<?php echo $row_shelf_tab['shelf_id']; ?>_<?php echo $shelfgroup_id; ?>','<?php echo $shelfgroup_id; ?>')" class="groupprotableft"><span><?php echo stripslashes($row_shelf_tab['shelf_name']); ?></span></div></li>        
							 <?php
							 }
							 ?>
                            
                            
                            
                            <?php
							
							$cnt1++;
							}
								
						}		
					
					?>

                        </ul>
                 </div> 
                 
                <?php				
									
					 $sql_shelf = "SELECT a.shelf_id,a.shelf_name,a.shelf_description,a.shelf_displaytype,shelf_showimage,shelf_showtitle,
										shelf_showdescription,shelf_showprice,shelf_currentstyle,shelf_activateperiodchange,
										shelf_displaystartdate,shelf_displayenddate,a.shelf_showrating,a.shelf_showbonuspoints    
								FROM 
									product_shelf a LEFT JOIN shelf_group_shelf b 
									 ON (a.shelf_id = b.shelf_shelf_id ) 
								WHERE
									a.sites_site_id = $ecom_siteid 
									AND b.shelf_group_id  = $shelfgroup_id 
									AND a.shelf_hide = 0
									ORDER BY b.shelf_order 
								    ";
					 $sql_shelf = "SELECT a.shelf_id,a.shelf_name,a.shelf_description,a.shelf_displaytype,shelf_showimage,shelf_showtitle,
										shelf_showdescription,shelf_showprice,shelf_currentstyle,shelf_activateperiodchange,
										shelf_displaystartdate,shelf_displayenddate,a.shelf_showrating,a.shelf_showbonuspoints    
								FROM 
									product_shelf a, shelf_group_shelf b 
								WHERE
									a.shelf_id = b.shelf_shelf_id									 
									AND a.sites_site_id = $ecom_siteid 
									AND b.shelf_group_id  = $shelfgroup_id 
									AND a.shelf_hide = 0
									ORDER BY b.shelf_order 
								    ";			    

									
					$ret_shelf = $db->query($sql_shelf);
					if ($db->num_rows($ret_shelf))// Check whether result is there
			/*2*/		{
				$cnt = 1;
						while ($shelfData = $db->fetch_array($ret_shelf))
			/*3*/		{
										
			?>

                 <?php
				 if($cnt == 1)
				 {
				 ?>
                 <div class="group_shlf_mid_outer_first" id="sgtab_<?php echo $shelfData['shelf_id']; ?>_<?php echo $shelfgroup_id; ?>">                                     
                <?php
				 }
				 else
				 {
				 ?>
                 <div class="group_shlf_mid_outer" id="sgtab_<?php echo $shelfData['shelf_id']; ?>_<?php echo $shelfgroup_id; ?>">        
                 <?php
				 }
				 ?>
				
				
				<?php		
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
						if($_REQUEST['req']!='') // If coming to show the details in middle area other than from the home page then show the details in normal shelf style
						{
							if($shelf_for_inner==true) // Case if call is to display shelf at the bottom on inner pages
								$shelfData['shelf_currentstyle']='inner_listing';
							else
								$shelfData['shelf_currentstyle']='nor';
						}	
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
											a.product_show_pricepromise,a.product_freedelivery         
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
							$pass_type = get_default_imagetype('midshelf');
							// Number of result to display on the page, will be in the LIMIT of the sql query also
			
										
										?>									
                                     
                                      <div class="group_shlf_mid_hdr"><?php stripslashes(trim($shelfData['shelf_description'])); ?> </div>
                                       <div class="group_shlf_mid_cont">
                                        
                                        
											<div class="group_link_pdt_con">
											  
												<div class="group_link_pdt_inner" id="container<?php echo $shelfData['shelf_id']; ?>_<?php echo $shelfgroup_id; ?>">
													<div class="shlf_a_con">   
												<?php 
												$col_cnts =0;
												 while($row_prod = $db->fetch_array($ret_prod))
												{
												
												$col_cnts++;
												if($col_cnts==3)
												{
												$col_cnts =0;
												$maincls = 'catBoxWrap_threecolumn_grp';	
												}
												else
												$maincls = 'catBoxWrap_threecolumn_grp';
												
												$unqid = uniqid('');
												$curmaskid = "shelgroup_$unqid";
												?>
												<div class="<?php echo $maincls?>" onmouseover="showmask_shelfgrp('<?php echo $curmaskid ?>')" onmouseout="hidemask_shelfgrp('<?php echo $curmaskid ?>')">
												<div id="<?php echo $curmaskid?>" class="overlay-box-grp">
						
						<?php
							$frm_name = uniqid('shelfgroupquick_');
							?>
							<div id="containers">
							<div class="quickviewclass" onclick="quick_view_prod('<?php echo $row_prod['product_id'] ?>','<?php echo $frm_name?>')">
								
								<img src="<?php url_site_image('quick_view_small.png')?>" alt="Quick View">
								<form name="<?php echo $frm_name?>" id="<?php echo $frm_name?>">
								<input type="hidden" name="product_id" value="<?php echo $row_prod['product_id'] ?>">
								<input type="hidden" name="ajax_fpurpose" value="Quick_Prod_Show_Details">
								</form>	
								
								</div>
							<div class="moreinfoclass"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>"><img src="<?php url_site_image('more-info-small.png')?>" alt="more info"></a></div>
							<div class="prod_list_buy">
							<?php
							$frm_name = uniqid('addtocartshelf_');
							?>
							<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
							<input type="hidden" name="fpurpose" value="" />
							<input type="hidden" name="fproduct_id" value="" />
							<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
							<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
							<input type="hidden" name="ajax_fpurpose_quick" id="ajax_fpurpose_quick<?php echo $frm_name ?>" value="">

							<?php
							$class_arr['ADD_TO_CART']       = '';
							$class_arr['PREORDER']          = '';
							$class_arr['ENQUIRE']           = '';
							$class_arr['QTY_DIV']           = 'normal_shlfA_pdt_input';
							$class_arr['QTY']               = ' ';
							$class_td['QTY']				= 'prod_list_buy_a';
							$class_td['TXT']				= 'prod_list_buy_b';
							$class_td['BTN']				= 'prod_list_buy_c_small';
							
							$class_arr['showquick']         = 1;
							echo show_addtocart_v5($row_prod,$class_arr,$frm_name,false,'','',true,$class_td);
							?>
							</form>	
							</div>
							</div>
							</div>
												<?php
												$pass_type = 'image_bigcategorypath';
												if ($shelfData['shelf_showimage']==1)// Check whether description is to be displayed
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
												//$no_img = get_noimage('prod',$pass_type);
												$no_img = url_site_image('no_small_image_tab.gif',1);
												if ($no_img)
												{
												show_image($no_img,$row_prod['product_name'],$row_prod['product_name']);
												}
												}
												?>							
												</a> </div>
												<?php
												}
												if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
												{
												?>
												<div class="prod_list_name_link_three_column">
												<span class="shelfBprodname_three_column">
												<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><span <?php echo getmicrodata_productname()?>><?php echo stripslashes($row_prod['product_name'])?></span></a>
												</span>
												
												</div>
												<?php
												}
												if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
												{
												?>										
												<div class="prod_list_price_three">
												<?php			
												$price_class_arr['ul_class'] 		= 'shelfBul_three_column';
												$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
												$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
												$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
												$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
												$price_class_arr['link_capt'] 	= 'appr_cls';
												
												//	echo show_Price($row_prod,$price_class_arr,'shelfcenter_1'); price_categorydetails_1_reqbreak
												echo show_Price($row_prod,$price_class_arr,'cat_detail_1');
												show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
												}
												
												?>																			
												
												</div>												
												<?php
												$frm_name = uniqid('catdet_');
												/*
												?>
												<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
												<input type="hidden" name="fpurpose" value="" />
												<input type="hidden" name="fproduct_id" value="" />
												<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
												<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
												<div class="prod_list_buy">
												<?php			
												
												$class_arr['ADD_TO_CART']       = '';
												$class_arr['PREORDER']          = '';
												$class_arr['ENQUIRE']           = '';
												$class_arr['QTY_DIV']           = 'normal_shlfA_pdt_input';
												$class_arr['QTY']               = ' ';
												$class_td['QTY']				= 'prod_list_buy_a';
												$class_td['TXT']				= 'prod_list_buy_b';
												$class_td['BTN']				= 'prod_list_buy_cA';
												echo show_addtocart_v5($row_prod,$class_arr,$frm_name,false,'','',true,$class_td);
												?>						
												</div>
												</form>
												<?php */?> 
												</div>
												<?php
												
												
												}
												?>
													</div>
												</div>
											</div>                                
                                                                        
                                        </div> 
                                        
                                        
                                       
                                       
                                       
                                       
                                       
                                       
                                      
										
										
										
			
										
					<?php
		
						}
					}
					else
					{
						removefrom_Display_Settings($shelfData['shelf_id'],'mod_shelf');
					}
				
				
				?>
				 
                </div>

                
		
			<?php
				$cnt++;
			/*3*/		}		
			/*2*/	}	
					
			/*1*/ }
				
			?>
            </div> 
                <div class="group_shlf_mid_bottom"></div> 
                </div>
                <?php	
				
				
				
			}	
		}
	};	
?>
