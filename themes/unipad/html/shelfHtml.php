<?php
	/*############################################################################
	# Script Name 	: shelfHtml.php
	# Description 	: Page which holds the display logic for middle shelves
	# Coded by 		: Sny
	# Created on	: 10-Aug-2009
	# Modified by	: 
	# Modified On	: 
	##########################################################################*/
	class shelf_Html
	{
		// Defining function to show the shelf details
		function Show_Shelf($cur_title,$shelf_arr)
		{ 
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents,$shelf_for_inner,$position;
			$Captions_arr['COMMON'] = getCaptions('COMMON');
			if (count($shelf_arr))
			{
				
				$shelfsort_by			= $Settings_arr['product_orderfield_shelf'];
				if($position == 'left-middle-band')
				{
				  $prodperpage			= 4;
				}
				else
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
						if($_REQUEST['req']!='') // If coming to show the details in middle area other than from the home page then show the details in normal shelf style
						{
							//if($shelf_for_inner==true) /* Case if call is to display shelf at the bottom on inner pages*/
						//		$shelfData['shelf_currentstyle']='inner_list';
							//else
							if($shelfData['shelf_currentstyle']!='gallery')
								$shelfData['shelf_currentstyle']='listingall';
						}
						else
						{
						       //if($shelf_for_inner==true)
							   //$shelfData['shelf_currentstyle']='inner_list';
							   //else
							   //$shelfData['shelf_currentstyle']= 'nor';
							   $shelfData['shelf_currentstyle']= 'listingall';
							   
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
						$pg_variable	= 'shelf_'.$shelfData['shelf_id'].'_pg';
						if ($_REQUEST['req']!='')// LIMIT for products is applied only if not displayed in home page
						{
							$start_var 		= prepare_paging($_REQUEST[$pg_variable],$prodperpage,$tot_cnt);
							$Limit			= " LIMIT ".$start_var['startrec'].", ".$prodperpage;
						}
						else
						{
							if($shelfData['shelf_currentstyle']=='inner_list' and $shelfData['shelf_displaytype']=='2row')
								$Limit = ' LIMIT 5';
							elseif($shelfData['shelf_currentstyle']=='inner_list' and $shelfData['shelf_displaytype']=='4row')
								$Limit = ' LIMIT 8';
                        }  
                        if($position == 'left-middle-band')
						{
							$Limit = ' LIMIT 4';
						}		
						// Get the list of products to be shown in current shelf
						$sql_prod = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
											a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_actualstock,a.product_webprice,
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
											a.product_webstock DESC,a.product_webprice ASC 
										$Limit	";
						$ret_prod = $db->query($sql_prod);
						if ($db->num_rows($ret_prod))
						{ 
							$comp_active = isProductCompareEnabled();
							$pass_type = get_default_imagetype('midshelf');
							//print_r($shelfData);
							// Number of result to display on the page, will be in the LIMIT of the sql query also
							$querystring = ""; // if any additional query string required specify it over here
							if($shelfData['shelf_currentstyle']=='gallery') // case of normal design layout
							{
									switch($shelfData['shelf_displaytype'])
								    {
										
										case 'olddefault':
										?>
										
										<table width="100%" border="0" cellspacing="0" cellpadding="0" class="gallery_table">
										<tr>
										<?php
											$max_col = 3;
											$cur_col=0;								  
											$imghold_arr = array();
											while($row_prod = $db->fetch_array($ret_prod))
											{
												$pass_type ='image_thumbpath';
												$img_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,0,0,0);
												
												if(count($img_arr))
												{
													for($im_i=0;$im_i<count($img_arr);$im_i++)
													{
														$imghold_arr[] = array('img'=>url_root_image($img_arr[$im_i][$pass_type],1),'id'=>$row_prod['product_id'],'name'=>$row_prod['product_name']);
													}
													
												}	
												
											}
												// Calling the function to get the image to be shown
												if(count($imghold_arr))
												{
													for($im_i=0;$im_i<count($imghold_arr);$im_i++)
													{
														if($cur_col==0)
														{
															echo '<tr>';
														}	
													?>
														<td class="gallery_td">
															<a href="<?php echo url_product($imghold_arr[$im_i]['id'],$imghold_arr[$im_i]['name'],0)?>" title="<?php echo stripslash_normal($imghold_arr[$im_i]['name'])?>">
															<?php 
															show_image($imghold_arr[$im_i]['img'],$imghold_arr[$im_i]['name'],$imghold_arr[$im_i]['name'],'','',0);
															?>    
															</a>
															
														</td>
													<?php
														$cur_col++;
														if($cur_col>=$max_col)
														{
															echo "</tr>";
															$cur_col = 0;
														}
													}
												}		
											
											if($max_col>$cur_col)
											{
												echo "<td class='gallery_td' colspan='".($max_col-$cur_col)."'>&nbsp</td></tr>";
											}	
											?>
										</table>	
										<?php
										break;
										default:
											$prev_id = 0;							  
											$imghold_arr = array();
											while($row_prod = $db->fetch_array($ret_prod))
											{
												$pass_type ='image_thumbpath';
												$img_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,0,0,0);
												
												if(count($img_arr))
												{
												?>
													<div class="gallery_propertyouter">
														<div class="gallery_propertyname"><a href="<?php echo url_product($row_prod['product_id'],$row_prod['product_name'],1)?>"><?php echo stripslashes($row_prod['product_name'])?></a></div>
												<?php	
													for($im_i=0;$im_i<count($img_arr);$im_i++)
													{
													?>
														<div class="gallery_image">
														<a href="<?php echo url_root_image($img_arr[$im_i]['image_bigpath'])?>" title="<?php echo stripslash_normal($row_prod['product_name'])?>" rel='lightbox[gallery_<?php echo $row_prod['product_id']?>]'>
															<?php 
															show_image(url_root_image($img_arr[$im_i][$pass_type],1),$row_prod['product_name'],$row_prod['product_name'],'','',0);
															?>    
															</a>
														</div>
														
													<?php
													}
													?>
													</div>
												<?php	
												}	
											}
										break;
									};
							}			
							elseif($shelfData['shelf_currentstyle']=='nor') // case of normal design layout
							{
								
									switch($shelfData['shelf_displaytype'])
								    {
										case 'scroll':
										
										$width_one_set 	= 305;
										$min_number_req	= 3;
										$min_width_req 	= $width_one_set * $min_number_req;
										$total_cnt		= $db->num_rows($ret_prod);
										$calc_width		= $total_cnt * $width_one_set;
										if($calc_width < $min_width_req)
											$div_width = $min_width_req;
										else
											$div_width = $calc_width; 
											$divid = uniqid('shelf');
										
										
										?>
										
								<div class="marquee" id="mycrawler2">

								<table width="100%" border="0" cellspacing="0" cellpadding="0">
								  <tr>
									  
									  <?php
									  
										while($row_prod = $db->fetch_array($ret_prod))
										{
										?>
										<td><div class="list_scroll" >
										<?php	
										
										$prodcur_arr[] = $row_prod;
										$HTML_title = $HTML_image = $HTML_desc = '';
										$HTML_sale = $HTML_new = $HTML_compare = $HTML_rating = '';
										$HTML_price = $HTML_bulk= $HTML_bonus = $HTML_compare = $HTML_freedel= $HTML_bonus_bar = '';
										if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
										{
											$HTML_title = '<a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">'.stripslash_normal($row_prod['product_name']).'</a>';
										}
										if ($shelfData['shelf_showimage']==1)// Check whether description is to be displayed
										{
											$HTML_image ='<a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">';
											// Calling the function to get the image to be shown
											$pass_type ='image_thumbcategorypath';
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
										
										if ($shelfData['shelf_showprice']==1)
										{
											$price_class_arr['class_type']          = 'div';
											$price_class_arr['normal_class']        = 'normal_shlfB_pdt_priceA';
											$price_class_arr['strike_class']        = 'normal_shlfB_pdt_priceB';
											$price_class_arr['yousave_class']       = 'normal_shlfB_pdt_priceC';
											$price_class_arr['discount_class']      = 'normal_shlfB_pdt_priceC';
											$price_arr = show_Price($row_prod,$price_class_arr,'shelfcenter_3',false,3);
											if($price_arr['discounted_price'])
												$HTML_price = $price_arr['discounted_price'];
											else
												$HTML_price = $price_arr['base_price'];
										}
										?>
												 
									  <div class="list_scroll_name" >
											<?=$HTML_title;?>
									  </div>
									   <div class="list_scroll_img" >
											<?php echo $HTML_image;?>
									   </div>
												
											   											
										<div class="list_scroll_price" >
											<?
											if ($shelfData['shelf_showprice']==1)
											{
												echo $HTML_price;
											}	
											?>
										</div>														 
									   <div class="list_scroll_buy_otr" >  
										   <div class="list_scroll_buy_l" >
											<?php
												$link 		= url_product($row_prod['product_id'],$row_prod['product_name'],1);
												?>
											
												<a href="<?php echo $link?>" title="<?php echo $row_prod['product_name']?>" class="">Buy</a>
											</div>
										   
											<div class="list_scroll_buy_r" ><a href="<?php echo $link?>" title="<?php echo $row_prod['product_name']?>" class="">More Info</a></div>
										</div>									
										</div></td>
										<?php
										}
										?>							
									 </tr>
								</table>
								</div>
									<script type="text/javascript">
									marqueeInit({
										uniqueid: 'mycrawler2',
										style: {
											'padding': '0',
											'width': '930px',
											'height': '265px',
											'margin' : '0',
											'float' :"left"
										},
										inc:5, //speed - pixel increment for each iteration of this marquee's movement
										mouse: 'cursor driven', //mouseover behavior ('pause' 'cursor driven' or false)
										moveatleast: 2,
										neutral: 150,
										savedirection: true,
										random: true
									});
									</script>
										<?php
										
										break;
										default:
										$HTML_comptitle = $HTML_maindesc = $HTML_paging = $HTML_showall = '';
										if($cur_title!='')
										{
											   $HTML_comptitle ='  <div class="shlf_a_top">
											   <div class="shlf_a_hdr">
											   <table border="0" class="shlf_a_hdrtable" align="center" cellspacing="0" cellpadding="0" >
												  <tr>
													<td class="shlf_a_hdrl">';
													   if($tot_cnt>0) // case of show all link
														{
														   //$HTML_comptitle .= "<a href='".url_shelf_all($shelfData['shelf_id'],$shelfData['shelf_name'],1)."' title=''>".stripslashes($cur_title)."</a>";
														   $HTML_comptitle .=stripslashes($cur_title);
														}
													$HTML_comptitle .='</td>
													<td class="shlf_a_hdrr">&nbsp;</td>
												  </tr>
											  </table>
											   </div>
											   </div>';
										}
										$desc = trim($shelfData['shelf_description']);
										if($desc!='' and $desc!='&nbsp;')
										{
											 $desc = stripslashes($desc);
											 $HTML_maindesc = '<p>'.$desc.'</p>';
										}
									?>
                                    <div class="shlf_a_con">   
                                    <? 
                                    echo $HTML_comptitle;
                                    echo $HTML_maindesc;
									$width_one_set 	= 305;
									$min_number_req	= 3;
									$min_width_req 	= $width_one_set * $min_number_req;
									$total_cnt		= $db->num_rows($ret_prod);
									$calc_width		= $total_cnt * $width_one_set;
									if($calc_width < $min_width_req)
										$div_width = $min_width_req;
									else
										$div_width = $calc_width; 
										$divid = uniqid('shelf');
                                    ?>
                                        	<div class="shlf_a_bottom">
                                            <div class="shlf_a_outer">
                                               <div class="shlf_thumb_outer">
                                               <?php
											   
											   $max_col = 3;
                                               $cur_col = 0;
											   
                                                while($row_prod = $db->fetch_array($ret_prod))
                                                {
                                                $prodcur_arr[] = $row_prod;
                                                $HTML_title = $HTML_image = $HTML_desc = '';
                                                $HTML_sale = $HTML_new = $HTML_compare = $HTML_rating = '';
                                                $HTML_price = $HTML_bulk= $HTML_bonus = $HTML_compare = $HTML_freedel= $HTML_bonus_bar = '';
                                                if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
                                                {
                                                    $HTML_title = '<div class="shlf_a_pdt_name"><a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">'.stripslash_normal($row_prod['product_name']).'</a></div>';
                                                }
                                                if ($shelfData['shelf_showimage']==1)// Check whether description is to be displayed
                                                {
                                                    $HTML_image ='<a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">';
                                                    // Calling the function to get the image to be shown
                                                    $pass_type ='image_thumbcategorypath';
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
                                                if ($shelfData['shelf_showdescription']==1)// Check whether description is to be displayed
                                                {
                                                    $HTML_desc = '<div class="shlf_a_pdt_des">'.stripslash_normal($row_prod['product_shortdesc']).'</div>';
                                                }
                                                if ($shelfData['shelf_showprice']==1)
                                                {
                                                    $price_class_arr['class_type']          = 'div';
                                                    $price_class_arr['normal_class']        = 'normal_shlfB_pdt_priceA';
                                                    $price_class_arr['strike_class']        = 'normal_shlfB_pdt_priceB';
                                                    $price_class_arr['yousave_class']       = 'normal_shlfB_pdt_priceC';
                                                    $price_class_arr['discount_class']      = 'normal_shlfB_pdt_priceC';
                                                    $price_arr = show_Price($row_prod,$price_class_arr,'shelfcenter_3',false,3);
													if($price_arr['discounted_price'])
														$HTML_price = $price_arr['discounted_price'];
													else
														$HTML_price = $price_arr['base_price'];
                                                }
                                                ?>
                                                <?php
                                                 if($cur_col==0)
												{
													echo  '<div class="shlf_d_pdt_otr_row1">';
												}	
												?>
                                                
                                                <div class="shlf_a_pdt">
                                                  <?php
                                                if($row_prod['product_saleicon_show']==1)
												{
													$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
													//if($desc!='')
													{
														  $HTML_sale = '<div class="pdt_list_sale"></div>';
													}
												}
												if($row_prod['product_newicon_show']==1)
												{
													$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
														  $HTML_new = '<div class="pdt_list_new"></div>';
												}
												echo $HTML_new ;
												echo $HTML_sale;
												?>
                                                <div class="shlf_a_pdt_top"></div>
                                               
                                                    <div class="shlf_a_pdt_mid">
                                                      <?=$HTML_title;?>
                                                    <div class="shlf_a_pdt_r">
														<?php echo $HTML_image;?>
                                                        </div>
                                                        <div class="shlf_a_pdt_l">
                                                       
                                                        <? /*=$HTML_desc*/ ?>
                                                                <div class="shlf_a_pdt_buy_otr">
                                                                <div class="shlf_a_pdt_price"><?
                                                                if ($shelfData['shelf_showprice']==1)
																{
																	echo $HTML_price;
																}	
																	?></div>
                                                                    <div class="shlf_a_pdt_buy">
                                                                    <?php $frm_name = 'frm_shelf'.uniqid('').$row_prod['product_id'] ?>
                                                                    <form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
                                                                    <input type="hidden" name="fpurpose" value="" />
                                                                    <input type="hidden" name="fproduct_id" value="" />
                                                                    <input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
                                                                    <input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
                                                                    <?php
                                                                    $class_arr 					= array();
                                                                    $class_arr['ADD_TO_CART']	= 'quantity_infolink';
                                                                    $class_arr['PREORDER']		= 'quantity_infolink';
                                                                    $class_arr['ENQUIRE']		= 'quantity_infolink';
                                                                    show_addtocart($row_prod,$class_arr,$frm_name,false,'','','',1);
                                                                    $link 		= url_product($row_prod['product_id'],$row_prod['product_name'],1);
                                                                    ?>
                                                                    </form>
                                                                    </div>
                                                                </div>
                                                       </div>
                                                       
                                                        <div class="shlf_a_pdt_more"><a href="<?php echo $link?>" title="<?php echo $row_prod['product_name']?>" class="">More Info</a></div>
                                                      
                                                    </div>
                                                  
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
                                           
                                                </div>
                                                                                              
                                                <?php /*<div class="shlf_thumb_outer_more"><a href="<?php echo url_shelf_all($shelfData['shelf_id'],$shelfData['shelf_name'],1); ?>"><?php echo $Captions_arr['COMMON']['SHOW_ALL']; ?></a></div> */ ?>
                                                
                                            </div>
                                        </div>
                                    </div>
								<?php
								break;	
							  }	
							}
							elseif($shelfData['shelf_currentstyle']=='listingall') // case of shelf to be displayed in inner pages
							{							
										$HTML_comptitle = $HTML_maindesc = $HTML_paging = $HTML_showall = '';
										if($cur_title!='')
										{
											   $HTML_comptitle ='<h3>'.stripslashes($cur_title).'</h3>';
																  
										}
										$desc = trim($shelfData['shelf_description']);
										if($desc!='' and $desc!='&nbsp;')
										{
											 $desc = stripslashes($desc);
											 $HTML_maindesc = '<p>'.$desc.'</p>';
										}
										if ($tot_cnt>0 and ($_REQUEST['req']!='') and $tot_cnt>$prodperpage)
										{
											$paging 		= paging_footer_advanced($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Properties',$pageclass_arr);
											$HTML_paging 	='<div class="subcat_nav_content">
															  <div class="subcat_nav_top"></div>
															  	<div class="subcat_nav_bottom">';
											if(!$shelf_for_inner)				  								
											{
												$HTML_paging.=  '<div class="subcat_nav_pdt_no"><span>'.$paging['total_cnt'].'</span></div>';
											}
											$HTML_paging.='	</div>
																<div class="page_nav_content"><ul>';//.'';
											$HTML_paging 	.= $paging['navigation']['start_nav'].$paging['navigation']['page_no'].$paging['navigation']['end_nav'];
											$HTML_paging 	.= ' 
																</ul></div></div>
																';
										}
										if($_REQUEST['req']=='' and  $tot_cnt==-1) // case of show all link
										{
										   //$HTML_showall = "<div class='shlf_dhdr_r'><a href='".url_shelf_all($shelfData['shelf_id'],$shelfData['shelf_name'],1)."' title=''>".$Captions_arr['COMMON']['SHOW_ALL']."</a></div>";
										}
										?>
                                           <?php //echo $HTML_comptitle;?>
                                            <div class="listwrap">
												<div class="property_shelf">
                                       		<?php
												echo $HTML_comptitle;
												echo $HTML_maindesc;
												echo $HTML_paging;
											?>	</div>
                                            </div>
                                            <?php

												$max_col = 3;
												$cur_col = 0;
												$prodcur_arr = array();
												?>
                                				<div class="listdetailwrap">
                                                <?php
												/*if($shelfData['shelf_name'] != "")
												{	echo "<h3>".$shelfData['shelf_name']."</h3>";	}
												if($shelfData['shelf_description'] != "")
												{	echo "<p>".$shelfData['shelf_description']."</p>";	}*/
												while($row_prod = $db->fetch_array($ret_prod))
												{
													$prodcur_arr[] = $row_prod;
													$HTML_title = $HTML_image = $HTML_desc = '';
													$HTML_sale = $HTML_new = $HTML_compare = $HTML_rating = '';
													$HTML_price = $HTML_bulk= $HTML_bonus = $HTML_compare = $HTML_freedel= $HTML_bonus_bar = '';
													
													$price_class_arr['class_type']          = 'div';
													$price_class_arr['normal_class']        = 'normal_shlfB_pdt_priceA';
													$price_class_arr['strike_class']        = 'normal_shlfB_pdt_priceB';
													$price_class_arr['yousave_class']       = 'normal_shlfB_pdt_priceC';
													$price_class_arr['discount_class']      = 'normal_shlfB_pdt_priceC';
													 $price_arr =  show_Price($row_prod,array(),'compshelf',false,3);
													if($price_arr['discounted_price'])
														$HTML_price = $price_arr['discounted_price'];
													else
														$HTML_price = $price_arr['base_price'];
													
													if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
													{
															$HTML_title = '<h3><a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">'.stripslash_normal($row_prod['product_name']).'</a> <span style="float:right;padding:0 15px 0 0;color:#FF5E33">'.$HTML_price.'</span></h3>';
													}
													if ($shelfData['shelf_showimage']==1)// Check whether description is to be displayed
													{
														$pass_type	=	'image_thumbpath';
														$img_arr	=	get_imagelist('prod',$row_prod['product_id'],$pass_type,0,0,1);
														$tabimg_arr	=	get_imagelist('prod',$row_prod['product_id'],'image_bigpath',0,0,1);
														
														$HTML_image ='<div class="slidemainImg"><a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'" >';
														$curimgid = $shelfData['shelf_id'].'_'.$row_prod['product_id'];
														global $def_mainimg_id;
														$def_mainimg_id = $curimgid;
														// Calling the function to get the image to be shown
														if(count($img_arr))
														{
															//$HTML_image .= show_image(url_root_image($img_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name'],'','',1);
															$HTML_image .= '<img src="'.url_root_image($img_arr[0][$pass_type],1).'" id="'.$curimgid.'" alt="'.$row_prod['product_name'].'">';														}
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
														
														if($row_prod['product_saleicon_show']==1)
														{
															//$HTML_image .= '<div class="icon-list-sale"><img src="'.url_site_image('big-sale.png',1).'" alt="Sale"></div>';
														}
														if($row_prod['product_newicon_show']==1)
														{
															//$HTML_image .= '<div class="icon-list-new"><img src="'.url_site_image('big-new.png',1).'" alt="New"></div>';
														}
													
													
													if($row_prod['product_actualstock']==0)
													{
														$HTML_image .= '<div class="nowlet_cls_inner"><img src="'.url_site_image('nowLet.png',1).'" alt="Now Let"></div>';
													}
											
														
														$HTML_image .='</div>';
													}
													if ($shelfData['shelf_showdescription']==1)// Check whether description is to be displayed
													{
															
															$HTML_desc = "<div class='slidethumbwrap'>".stripslash_normal($row_prod['product_shortdesc'])."</div>";
													}
													
												?>	
												<div class="list">
													<?=$HTML_title;?>
													
                                                    <div class="list_left">
                                                        <?=$HTML_image?>
                                                        
                                                        
                                                        
                                                        <?php //echo $HTML_desc;?>
                                                        <?php show_MoreImages_Unipad($row_prod,0,0);?>
                                                        
                                                        <?php
																if($row_prod['product_actualstock']>0)
																	$availability_msg = '<span class="green_available">'.$Captions_arr['COMMON']['PRODDET_AVAIL_1_YEAR'].'</span>';
																else
																	$availability_msg = '<span class="red_available">'.$Captions_arr['COMMON']['PRODDET_AVAIL_2_YEAR'].'</span>';
																echo $availability_msg;	
														?>
                                                    </div>
                                                <?php
													//$HTML_price = show_Price($row_prod,$price_class_arr,'shelfcenter_3');
													if($cur_col==0)
													{
														//echo  '<div class="pdt_list_thumb_outer">';
													}	
													if($cur_col%2==0 && $cur_col!=0)	
													{
													$cls = "pdt_list_pdt_rt";
													}
													else
													{
													$cls = "pdt_list_pdt";
													}
												?>
												
                                                <div class="list_right">
												<?php
														/*if($row_prod['product_actualstock']>0)
															$availability_msg = '<div class="green_available">'.$Captions_arr['COMMON']['PRODDET_AVAIL_1_YEAR'].'</div>';
														else
															$availability_msg = '<div class="red_available">'.$Captions_arr['COMMON']['PRODDET_AVAIL_2_YEAR'].'</div>';
														echo $availability_msg;	*/
												?>	
                                                <?php show_ProductVariables_Unipad($row_prod['product_id']); ?>
                                                    <?php /*?><ul class="list_point_left">
                                                        <li><a href="javascript:void(0);">6 Bedrooms with double....</a></li>
                                                        <li><a href="javascript:void(0);">Large Lounge consisting....</a></li>
                                                        <li><a href="javascript:void(0);">High End Gloss White...</a></li>
                                                        <li><a href="javascript:void(0);">Bedrooms with Oak...</a></li>
                                                        <li><a href="javascript:void(0);">Bathrooms � we have...</a></li>
                                                        <li><a href="javascript:void(0);">Superb Gloss Black...</a></li>
                                                        <li><a href="javascript:void(0);">Ceramic Hob and Oven....</a></li>                                                
                                                    </ul>
                                                    <ul class="list_point_right">
                                                        <li><a href="javascript:void(0);">6 Bedrooms with double....</a></li>
                                                        <li><a href="javascript:void(0);">Large Lounge consisting....</a></li>
                                                        <li><a href="javascript:void(0);">High End Gloss White...</a></li>
                                                        <li><a href="javascript:void(0);">Bedrooms with Oak...</a></li>
                                                        <li><a href="javascript:void(0);">Bathrooms � we have...</a></li>
                                                        <li><a href="javascript:void(0);">Superb Gloss Black...</a></li>
                                                        <li><a href="javascript:void(0);">Ceramic Hob and Oven....</a></li>
                                                    </ul><?php */?>
                                                 </div>
                                               <?php /*?> <?php
                                                if($row_prod['product_saleicon_show']==1)
												{
													$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
													//if($desc!='')
													{
														  $HTML_sale = '<div class="pdt_list_sale"></div>';
													}
												}
												if($row_prod['product_newicon_show']==1)
												{
													$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
													//if($desc!='')
													{
														  $HTML_new = '<div class="pdt_list_new"></div>';
													}
												}
												echo $HTML_new;
												echo  $HTML_sale;
												?>
                                                
                                                
                                                <?php
												if($shelfData['shelf_showrating']==1)
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
												else
												{
													$HTML_rating = '&nbsp;';
												}
                                                echo $HTML_rating;
												?>
                                                
												<?php
													if ($shelfData['shelf_showprice']==1)
													{
															echo $HTML_price;
													}	
												?>
													
                                                   <?php
												   
												   if($row_prod['product_bonuspoints']>0 and $shelfData['shelf_showbonuspoints']==1)
													{
														$HTML_bonus = 'Bonus: '.$row_prod['product_bonuspoints'];
													}
													else
													{
														$HTML_bonus = '&nbsp;';
													}
												   
												   ?>
													<div class="pdt_list_bonus">  <?php echo $HTML_bonus;?> </div>  
                                                
												<?php
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
												?>
											   </div><?php */?>
                                               
                                            <div class="listicon">
                                            	<?php show_ProductLabels_Unipad($row_prod['product_id']); ?>
                                                <?php /*?><ul class="featurelist">
                                                    <li><a href="javascript:void(0);"><img src="<?php url_site_image('icon_flat_tv.png');?>" /></a></li>
                                                    <li><a href="javascript:void(0);"><img src="<?php url_site_image('icon_double_bed.png');?>"  /></a></li>
                                                    <li><a href="javascript:void(0);"><img src="<?php url_site_image('icon_coffee_table.png');?>"  /></a></li>
                                                    <li><a href="javascript:void(0);"><img src="<?php url_site_image('icon_walkable.png');?>"/></a></li>
                                                </ul><?php */?>
                                                
                                                <div class="viewdetails">
													<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslash_normal($Captions_arr['COMMON']['COMMON_FULL_DETAILS'])?></a>                                                     
                              					</div>
                                            </div>

											<?php
												$cur_col++;
												if($cur_col>=$max_col)
												{
													/*$cur_col =0;
													echo "</div>";*/
												}
												echo "</div>";
											}
											if($cur_col<$max_col)
											{
												if($cur_col!=0)
												{ 
													//echo "</div>";
												} 
											}
                                            ?> 
                                                </div>
                                           
                                                
						<?php
							}
							elseif($shelfData['shelf_currentstyle']=='inner_list') // case of shelf to be displayed in inner pages
							{  
									
									 switch($position)
									 {
									  case 'left-middle-band':
									  $HTML_comptitle = $HTML_maindesc = $HTML_paging = $HTML_showall = '';
										if($cur_title!='')
										{
											   $HTML_comptitle ='<div class="shlf_d_hdr">'.stripslashes($cur_title).'</div>';
																  
										}
										$desc = trim($shelfData['shelf_description']);
										if($desc!='' and $desc!='&nbsp;')
										{
											 $desc = stripslashes($desc);
											 $HTML_maindesc = '<p>'.$desc.'</p>';
										}
										if ($tot_cnt>0 and ($_REQUEST['req']!='') and $tot_cnt>$prodperpage)
										{
											$paging 		= paging_footer_advanced($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr);
											$HTML_paging 	='<div class="subcat_nav_content">
															  <div class="subcat_nav_top"></div>
															  	<div class="subcat_nav_bottom">							
																<div class="subcat_nav_pdt_no"><span>'.$paging['total_cnt'].'</span></div>
																</div>
																<div class="page_nav_content"><ul>';//.'';
											$HTML_paging 	.= $paging['navigation']['start_nav'].$paging['navigation']['page_no'].$paging['navigation']['end_nav'];
											$HTML_paging 	.= ' 
																</ul></div></div>
																';
										}
										if($tot_cnt==-1) // case of show all link
										{
										   //$HTML_showall = "<div class='shlf_dhdr_r'><a href='".url_shelf_all($shelfData['shelf_id'],$shelfData['shelf_name'],1)."' title='' class='shlfd-showall'>".$Captions_arr['COMMON']['SHOW_ALL']."</a></div>";
										}
									 ?>
                                        <div class="shlf_d_con"> 
                                         <?php echo  $HTML_comptitle;?>
                                            <?php //echo  $HTML_showall;?>
                                        <div class="shlf_d_bottom">
                                        <?php echo $HTML_maindesc ; 
                                                    $max_col = 2;
                                                    $cur_col = 0;
                                                    $prodcur_arr = array();
													 while($row_prod = $db->fetch_array($ret_prod))
                                                     {
                                                        $prodcur_arr[] = $row_prod;
                                                        $HTML_title = $HTML_image = $HTML_desc = '';
                                                        $HTML_sale = $HTML_new = $HTML_compare = $HTML_rating = '';
                                                        $HTML_price = $HTML_bulk= $HTML_bonus = $HTML_compare = $HTML_freedel= $HTML_bonus_bar = '';
							if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
							{
                                                        $HTML_title = '<div class="shlf_d_pdt_name"><a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">'.stripslash_normal($row_prod['product_name']).'</a></div>';
							}
							if ($shelfData['shelf_showimage']==1)// Check whether description is to be displayed
							{
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
                                                        }
							if ($shelfData['shelf_showdescription']==1)// Check whether description is to be displayed
							{
                                                        $HTML_desc = stripslash_normal($row_prod['product_shortdesc']);
							}
                                                        $price_class_arr['class_type']          = 'div';
                                                        $price_class_arr['normal_class']        = 'normal_shlfB_pdt_priceA';
                                                        $price_class_arr['strike_class']        = 'normal_shlfB_pdt_priceB';
                                                        $price_class_arr['yousave_class']       = 'normal_shlfB_pdt_priceC';
                                                        $price_class_arr['discount_class']      = 'normal_shlfB_pdt_priceC';
                                                         $price_arr =  show_Price($row_prod,array(),'compshelf',false,3);
                                                        if($price_arr['discounted_price'])
                                                            $HTML_price = $price_arr['discounted_price'];
                                                        else
                                                            $HTML_price = $price_arr['base_price'];
                                                    
                                                        //$HTML_price = show_Price($row_prod,$price_class_arr,'shelfcenter_3');
                                                        if($cur_col==0)
                                                        {
                                                            echo  '<div class="shlf_d_pdt_otr_row">';
                                                        }	
                                                    ?>
                                                        <div class="shlf_d_pdt_otr"> 
                                                        <?=$HTML_title;?>
                                                        <div class="shlf_d_pdt_img">
														
                                                        <div class="shlf_d_pdt_imga">          
                                                      	<?=$HTML_image?>
                                                      	</div>
                              							<div class="shlf_d_pdt_morea"> 
                                                        <a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
                                                        <img height="110" width="13" src="<?php echo url_site_image('more-v.gif',1); ?>">
                                                        </a>
                                                        </div>  
                                                        
                                                        </div>
                                                        <div class="shlf_d_pdt_buy">
                                                        <div class="shlf_d_pdt_buy_ba"> 
                                                        <div class="shlf_d_pdt_price"> 
                                                    <?
                                                         if ($shelfData['shelf_showprice']==1)
														{
															echo $HTML_price;
														}	
														?></div>
                                                        <div class="shlf_d_pdt_buy_in"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslash_normal($Captions_arr['COMMON']['COMMON_BUY_NOW'])?></a></div>
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
                                                        }
                                                        if($cur_col<$max_col)
                                                        {
                                                        if($cur_col!=0)
                                                        { 
                                                        echo "</div>";
                                                        } 
                                                        }
                                                        ?>
                                                  
                                               
                                               <div class="shlf_thumb_outer_more1"><a href="<?php echo url_shelf_all($shelfData['shelf_id'],$shelfData['shelf_name'],1); ?>"><?php echo $Captions_arr['COMMON']['SHOW_ALL']; ?></a></div>  
                                               </div>       
										      
                                             </div>
                                              
                                     <?php
									 break;
									 default:
										if($shelfData['shelf_displaytype']=='scroll')
										{
										$width_one_set 	= 305;
										$min_number_req	= 3;
										$min_width_req 	= $width_one_set * $min_number_req;
										$total_cnt		= $db->num_rows($ret_prod);
										$calc_width		= $total_cnt * $width_one_set;
										if($calc_width < $min_width_req)
											$div_width = $min_width_req;
										else
											$div_width = $calc_width; 
											$divid = uniqid('shelf');										
										?>
										
								<div class="marquee" id="mycrawler2">
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
								  <tr>									  
									  <?php									  
										while($row_prod = $db->fetch_array($ret_prod))
										{
										?>
										<td><div class="list_scroll" >
										<?php	
										
										$prodcur_arr[] = $row_prod;
										$HTML_title = $HTML_image = $HTML_desc = '';
										$HTML_sale = $HTML_new = $HTML_compare = $HTML_rating = '';
										$HTML_price = $HTML_bulk= $HTML_bonus = $HTML_compare = $HTML_freedel= $HTML_bonus_bar = '';
										if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
										{
											$HTML_title = '<a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">'.stripslash_normal($row_prod['product_name']).'</a>';
										}
										if ($shelfData['shelf_showimage']==1)// Check whether description is to be displayed
										{
											$HTML_image ='<a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">';
											// Calling the function to get the image to be shown
											$pass_type ='image_thumbcategorypath';
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
										
										if ($shelfData['shelf_showprice']==1)
										{
											$price_class_arr['class_type']          = 'div';
											$price_class_arr['normal_class']        = 'normal_shlfB_pdt_priceA';
											$price_class_arr['strike_class']        = 'normal_shlfB_pdt_priceB';
											$price_class_arr['yousave_class']       = 'normal_shlfB_pdt_priceC';
											$price_class_arr['discount_class']      = 'normal_shlfB_pdt_priceC';
											$price_arr = show_Price($row_prod,$price_class_arr,'shelfcenter_3',false,3);
											if($price_arr['discounted_price'])
												$HTML_price = $price_arr['discounted_price'];
											else
												$HTML_price = $price_arr['base_price'];
										}
										?>
												 
									  <div class="list_scroll_name" >
											<?=$HTML_title;?>
									  </div>
									   <div class="list_scroll_img" >
											<?php echo $HTML_image;?>
									   </div>
												
											   											
										<div class="list_scroll_price" >
											<?
											if ($shelfData['shelf_showprice']==1)
											{
												echo $HTML_price;
											}	
											?>
										</div>
										<div class="list_scroll_buy_otr" >  
										   <div class="list_scroll_buy_l" >
											<?php
												$link 		= url_product($row_prod['product_id'],$row_prod['product_name'],1);
												?>
											
												<a href="<?php echo $link?>" title="<?php echo $row_prod['product_name']?>" class="">Buy</a>
											</div>
										   
											<div class="list_scroll_buy_r" ><a href="<?php echo $link?>" title="<?php echo $row_prod['product_name']?>" class="">More Info</a></div>
										</div>									
										</div></td>
										<?php
										}
										?>						
									 </tr>
								</table>
								</div>
									<script type="text/javascript">
									marqueeInit({
										uniqueid: 'mycrawler2',
										style: {
											'padding': '0',
											'width': '930px',
											'height': '265px',
											'margin' : '0',
											'float' :"left"
										},
										inc:5, //speed - pixel increment for each iteration of this marquee's movement
										mouse: 'cursor driven', //mouseover behavior ('pause' 'cursor driven' or false)
										moveatleast: 2,
										neutral: 150,
										savedirection: true,
										random: true
									});
									</script>
									<?php	
									}
								    break;		
								}															 
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
