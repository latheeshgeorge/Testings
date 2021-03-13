<?php
	/*############################################################################
	# Script Name 	: compareproductsHtml.php
	# Description 		: Page which holds the display logic for comparing products
	# Coded by 		: Sny
	# Created on		: 04-Aug-2008
	# Modified by		: Sny
	# Modified On		: 08-Aug-2008
	##########################################################################*/
	
	class compareproducts_Html
	{
		// Defining function to show the shelf details
		function Show_Products($title,$compare_products_arr)
		{
		
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents;
			if(!isProductCompareEnabled() and  !isProductCompareEnabledInProductDetails()) // done to handle the case of typing the compare url directly and if feature in not active in site
				return;
			// ** Fetch any captions for product details page
			$Captions_arr['PROD_DETAILS'] 	= getCaptions('PROD_DETAILS');
			$num_products = count($_SESSION['compare_products']);
			if ($num_products)
			{
				$showqty					= $Settings_arr['show_qty_box'];// show the qty box
				$compare_product_ids	=$_SESSION['compare_products'];
				$compare_arr 				= array();
				$labelid_arr					= array();
				$var 							= 0;
				$shipping 					= 0;
				$bulk 						= 0;
				$desc 						= 0;
				$manufact 					= 0;
				$model 						= 0;
				$bonus 						= 0;
				$stock 						= 0;
				$rating 						= 0;
				$label 						= 0;
				$weight 					= 0;
				$review	 					= 0;
				$td_width					= (int)(85/count($compare_product_ids));
				for($i=0;$i<count($compare_product_ids);$i++)
				{
					$sql_prod = "SELECT product_id,product_name,product_variablestock_allowed,product_show_cartlink,product_shortdesc,
													product_preorder_allowed,product_show_enquirelink,product_webstock,product_webprice,
													product_discount,product_discount_enteredasval,product_bulkdiscount_allowed,
													product_total_preorder_allowed,product_applytax,product_shortdesc,product_averagerating,
													manufacture_id,product_model,product_bonuspoints,product_weight,product_extrashippingcost,
													product_stock_notification_required,product_alloworder_notinstock,product_variables_exists,product_variablesaddonprice_exists,
													product_variablecomboprice_allowed,product_variablecombocommon_image_allowed,default_comb_id,
													price_normalprefix,price_normalsuffix, price_fromprefix, price_fromsuffix,price_specialofferprefix,price_specialoffersuffix, 
													price_discountprefix, price_discountsuffix, price_yousaveprefix, price_yousavesuffix,price_noprice          
											FROM 
												products   
											WHERE 
												 product_id = ".$compare_product_ids[$i]."  
											LIMIT 
												1";
					$ret_prod = $db->query($sql_prod);
					if ($db->num_rows($ret_prod))
					{
						$row_prod = $db->fetch_array($ret_prod);
						$compare_arr['main'][] 		= $row_prod;
						$varval_str							= '';
						$compare_arr['var'][$i]			= array();
						if($Settings_arr['comp_showlabels']==1)
						{
							// Check whether variables exists for current products and also it is not a checkbox
							$sql_var = "SELECT var_id,var_name,var_value_exists  
										  FROM
												product_variables 
										  WHERE  
												products_product_id = ".$compare_product_ids[$i]." 
												AND var_value_exists=1 
												AND var_hide=0";
							$ret_var = $db->query($sql_var);
							$var_arr			= array();
							if($db->num_rows($ret_var))
							{
								while ($row_var = $db->fetch_array($ret_var))
								{
									$varval_str						= '';
									// Get the values for variables
									$sql_varval = "SELECT var_value 
															FROM 
																product_variable_data  
															WHERE 
																	product_variables_var_id =".$row_var['var_id']." 
															ORDER BY 
																var_order";
									$ret_varval = $db->query($sql_varval);
									$varval_arr		= array();
									if($db->num_rows($ret_varval))
									{
										while ($row_varval = $db->fetch_array($ret_varval))
										{
											$varval_arr[] = stripslashes($row_varval['var_value']);
										}
										$varval_str = implode(", ",$varval_arr);
									}
									if ($varval_str!='')
									{
										$var_arr[] = stripslashes($row_var['var_name']).': '.$varval_str;
										$var = 1;
									}
								}				
							}
						}	
						$compare_arr['var'][$i]			= $var_arr;
						// case of product weight
						if($Settings_arr['comp_showweight']==1)
						{
							if($row_prod['product_weight'] and $row_prod['product_weight'] !='0.00')
							{
								$compare_arr['weight'][] = $row_prod['product_weight'].' '.$Settings_arr['unit_of_weight'];
								$weight = 1;
							}	
							else
								$compare_arr['weight'][]  =  'N/A';	
						}		
						// case of product extra shipping
						if($Settings_arr['comp_showshipping']==1)
						{
							if($row_prod['product_extrashippingcost'] and $row_prod['product_extrashippingcost'] !='0.00')
							{
								$compare_arr['extrashipping'][] 	= $row_prod['product_extrashippingcost'];
								$shipping = 1;
							}	
							else
								$compare_arr['extrashipping'][]  	=  'N/A';
						}
						// case of bulk discount
						if($Settings_arr['comp_showbulkdisc']==1)
						{
							$bulkdisc_details = product_BulkDiscount_Details($row_prod['product_id']);
							if (count($bulkdisc_details['qty']))
							{
								$bulk_arr = array();
								for($j=0;$j<count($bulkdisc_details['qty']);$j++)
								{
								   $bulk_arr[] = $bulkdisc_details['qty'][$j].' '.$Captions_arr['PROD_DETAILS']['BULK_FOR'].' '. print_price($bulkdisc_details['price'][$j]).' '.$Captions_arr['PROD_DETAILS']['BULK_EACH'];
								}
								$compare_arr['bulkdiscount'][] = $bulk_arr;
								$bulk = 1;
							}
							else
							{
								$compare_arr['bulkdiscount'][]  = array(0=>'N/A');
							}
						}
						
						// product short description
						if($Settings_arr['comp_showdesc']==1)
						{
							if($row_prod['product_shortdesc']!='')
							{
								$compare_arr['description'][] 	= $row_prod['product_shortdesc'];
								$desc = 1;
							}	
							else
							{
								$compare_arr['description'][]  	=  'N/A';
							}	
						}						
						// case of manufacturer id
						if($Settings_arr['comp_showmanufact']==1)
						{
							if($row_prod['manufacture_id'])
							{
								$compare_arr['manfactureid'][] 	= $row_prod['manufacture_id'];
								$manufact = 1;
							}	
							else
							{
								$compare_arr['manfactureid'][]  	=  'N/A';
							}	
						}
						// case of product model
						if($Settings_arr['comp_showmodel']==1)
						{
							if($row_prod['product_model'])
							{
								$compare_arr['model'][] 	= $row_prod['product_model'];
								$model = 1;
							}	
							else
							{
								$compare_arr['model'][]  	=  'N/A';
							}	
						}
						if($Settings_arr['comp_showbonus']==1)
						{						
							if($row_prod['product_bonuspoints'])
							{
								$compare_arr['bonuspoints'][] 	= $row_prod['product_bonuspoints'];
								$bonus = 1;
							}	
							else
							{
								$compare_arr['bonuspoints'][]  	=  'N/A';
							}	
						}						
						if($Settings_arr['comp_showstock']==1)
						{
							// Case of stock 
							$stk						 	= get_stockdetails($row_prod['product_id'],1);
							if($stk)
							{
								$compare_arr['stock'][] 	= $stk;
								$stock = 1;
							}	
							else
							{
								$compare_arr['stock'][] 	= 'N/A';
							}	
						}
						if($Settings_arr['comp_showdesc']==1)
						{
							if($row_prod['product_averagerating'])
							{
								$rate = '';
									for ($x=0;$x<$row_prod['product_averagerating'];$x++)
									{
										$rate .= '<img src="'.url_site_image('reviewstar_on.gif',1).'" border="0" alt="review image" />&nbsp;'; 
									}
									for ($x=$row_prod['product_averagerating'];$x<5;$x++)
									{
										//$rate .= '<img src="'.url_site_image('reviewstar_off.gif',1).'" border="0"  alt="review image"/>&nbsp;'; 
									}
								$compare_arr['review'][] 	= $rate;
								$review = 1;
							}	
							else
							{
								$compare_arr['review'][]  	=  'N/A';
							}	
						}
							if($Settings_arr['comp_showlabels']==1)
							{						
							// Get the label id linked with current product
							$sql_labels = "SELECT a.product_site_labels_label_id 
														FROM 
															product_labels a,product_site_labels b
														WHERE 
															a.products_product_id = ".$row_prod['product_id']." 
															AND a.product_site_labels_label_id=b.label_id 
															AND b.label_hide=0
															AND CASE a.is_textbox 
															WHEN 1 
																THEN label_value <> '' 
															WHEN 0 
																THEN product_site_labels_values_label_value_id <>0
															END";
															
	
							$ret_labels = $db->query($sql_labels);
							if ($db->num_rows($ret_labels))
							{
								while ($row_labels = $db->fetch_array($ret_labels))
								{
										if (!in_array($row_labels['product_site_labels_label_id'],$labelid_arr))
											$labelid_arr[] = $row_labels['product_site_labels_label_id'];
								}
							}								
						}	
					}
				}
				$labelname_arr = array();
				if(count($labelid_arr))
				{
					// Get the list of labels in the order in which they are to be displayed and place it in an array
					$sql_label = "SELECT  label_id,label_name  
												FROM 
													product_site_labels 
												WHERE 
													label_id IN (".implode(',',$labelid_arr).") 
													AND sites_site_id = $ecom_siteid 
													AND label_hide=0 
												ORDER BY 
													label_order ";
					$ret_label = $db->query($sql_label);
					if ($db->num_rows($ret_label))
					{
						while ($row_label = $db->fetch_array($ret_label))
						{
							$labelname_arr[$row_label['label_id']] = stripslashes($row_label['label_name']);
							$label = 1;
						}
					}
				}						
?>
				<table width="100%" border="0" cellpadding="0" cellspacing="1" class="prod_comparison_table">
				<tr>
				<td align="left" valign="top" width="15%">&nbsp;</td>
						<?php
						// Calling the function to get the type of image to shown for current 
						$pass_type = get_default_imagetype('midshelf');
						for ($i=0;$i<$num_products;$i++)
						{
						?>
							<td align="center" valign="middle" class="prod_comparison_img" width="<?php echo $td_width?>%">
							<a href="<?php url_product($compare_arr['main'][$i]['product_id'],"'".$compare_arr['main'][$i]['product_name']."'",-1)?>" title="<?php echo stripslashes($compare_arr['main'][$i]['product_name'])?>">
									<?php 
								//	echo $prod_id_arr[$i];
									
										// Calling the function to get the image to be shown
										$img_arr = get_imagelist('prod',$compare_arr['main'][$i]['product_id'],$pass_type,0,0,1);
										if(count($img_arr))
										{
											show_image(url_root_image($img_arr[0][$pass_type],1),$compare_arr['main'][$i]['product_name'],$compare_arr['main'][$i]['product_name']);
										}
										else
										{
											// calling the function to get the default image
											$no_img = get_noimage('prod',$pass_type); 
											if ($no_img)
											{
												show_image($no_img,$compare_arr['main'][$i]['product_name'],$compare_arr['main'][$i]['product_name']);
											}	
										}	
									?></a>
									</td>
						<?php
						}
						?>
					  </tr>
					  <tr>
					  <td align="left" valign="top">&nbsp;</td>
					  <?php
						for ($i=0;$i<$num_products;$i++)
						{
						?>
							<td align="left" valign="top" class="prod_comparison_name"><a href="<?php url_product($compare_arr['main'][$i]['product_id'],"'".$compare_arr['main'][$i]['product_name']."'",-1)?>" title="<?php echo stripslashes($compare_arr['main'][$i]['product_name'])?>" class="prod_comparison_name"><?php echo stripslashes($compare_arr['main'][$i]['product_name'])?></a></td>
						<?php
						}
						?>	
					</tr>
				  <tr>
					  <td align="left" valign="top">&nbsp;</td>
					    <?php
						for ($i=0;$i<$num_products;$i++)
						{
						?>		
								<td align="left" valign="top"  class="prod_comparison_buy">
								<? $frm_name = uniqid('compare_'); ?>
								<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $compare_arr['main'][$i]['product_id']?>)">
								<input type="hidden" name="fpurpose" value="" />
								<input type="hidden" name="fproduct_id" value="" />
								<input type="hidden" name="pass_url" value="http://<?php echo $ecom_hostname?>/compare_products.html" />
								<input type="hidden" name="fproduct_url" value="<?php url_product($compare_arr['main'][$i]['product_id'],$compare_arr['main'][$i]['product_name'])?>" />
								<?php
										$class_arr 							= array();
										$class_arr['ADD_TO_CART']	= 'comparison_buy';
										$class_arr['PREORDER']			= 'comparison_buy';
										$class_arr['ENQUIRE']			= 'comparison_buy';
										///show_addtocart($compare_arr['main'][$i],$class_arr,$frm_name)
									?>
									<div class="compare_infodiv">
										<div class="compare_infodivleft"><?php show_moreinfo($compare_arr['main'][$i],'infolink')?></div>
										<div class="compare_infodivright">
										<?php
											$class_arr 					= array();
											$class_arr['ADD_TO_CART']	= 'quantity_infolink';
											$class_arr['PREORDER']		= 'quantity_infolink';
											$class_arr['ENQUIRE']		= 'quantity_infolink';
											show_addtocart($compare_arr['main'][$i],$class_arr,$frm_name)
										?>
										</div>
									</div>
								</form>
								</td>
						<?php
						}
						?>
					  </tr>
					  <?php
					   if ($Settings_arr['comp_showprice']==1)
					  {
					  		$enable_price_display = true;
					  		
							if($Settings_arr['hide_price_login']==1)
							{
								$cust_id		= get_session_var("ecom_login_customer"); // Get the customer id from session
								if(!$cust_id)
								{
									$enable_price_display = false;
								}	
							}
							
							if($enable_price_display)
							{
					  ?>
							  <tr>
								<td align="left" valign="top" class="comparison_mainheader"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_COMPPRICE'];?></td>
								  <?php
									for ($i=0;$i<$num_products;$i++)
									{
										$cls = ($i==0)?'comparison_contentA':'comparison_contentB';
									?>		
										<td align="left" valign="top" class="<?php echo $cls?>">
										<?php
												$price_class_arr['ul_class'] 			= 'productcom_price';
												$price_class_arr['normal_class'] 		= 'comparison_normalprice';
												$price_class_arr['strike_class'] 		= 'comparison_strikeprice';
												$price_class_arr['yousave_class'] 	= 'comparison_discountprice';
												$price_class_arr['discount_class'] 	= 'comparison_discountprice';
												echo show_Price($compare_arr['main'][$i],$price_class_arr,'shelfcenter_3');
										?>
										</td>
								<?php
									}
								?>
							  </tr>
					   <?php
					   	}
					   }
					  	if ($Settings_arr['product_show_instock'] and $stock ==1)
						{
					  ?>
						  <tr>
							<td align="left" valign="top" class="comparison_mainheader"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_COMPSTOCK'];?></td>
							 <?php
								for ($i=0;$i<$num_products;$i++)
								{
									$cls = ($i==0)?'comparison_contentA':'comparison_contentB';
								?>	
									<td align="left" valign="top" class="<?php echo $cls?>">
									<?php 
											echo $compare_arr['stock'][$i];
									?></td>
							<?php
								}	
							?>
						  </tr>
					 <?php
					 }
					 if (count($labelname_arr) and $label==1)
					 {
					 ?> 
						  <tr>
							<td colspan="4" align="left" valign="top" class="comparison_sec_header"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_COMPKEYFEATURE'];?></td>
						  </tr>
						  <?php 
						  	foreach ($labelname_arr as $k=>$v)
							{
						  ?>
							  <tr>
								<td align="left" valign="top" class="comparison_mainheader"><?php echo $v?></td>
								<?php
									for ($i=0;$i<$num_products;$i++)
									{
										$cls = ($i==0)?'comparison_contentA':'comparison_contentB';
										$prodid = $compare_arr['main'][$i]['product_id'];
										// Check whether value exists for current label in current product
										$sql_check = "SELECT product_site_labels_values_label_value_id,label_value,is_textbox 
																FROM 
																	product_labels 
																WHERE 
																	products_product_id = $prodid 
																	AND product_site_labels_label_id = ".$k." 
																LIMIT 
																	1";
										$ret_check = $db->query($sql_check);
										$curval	= 'N/A';	
										if ($db->num_rows($ret_check))
										{
											$row_check = $db->fetch_array($ret_check);
											if($row_check['is_textbox']==1) // case if type is textbox
											{
												if(trim($row_check['label_value']) != '')
													$curval = stripslashes($row_check['label_value']);					
											}	
											else // case if type is dropdown
											{
												$sql_labelval = "SELECT  label_value 
																		FROM 
																			product_site_labels_values 
																		WHERE 
																			label_value_id = ".$row_check['product_site_labels_values_label_value_id']."
																			AND product_site_labels_label_id = $k 
																		LIMIT 
																			1";
												$ret_labelval = $db->query($sql_labelval);
												if ($db->num_rows($ret_labelval))
												{
													$row_labelval 	= $db->fetch_array($ret_labelval);
													$curval 			= stripslashes($row_labelval['label_value']);												
												}
											}	
										}						
											
								?>
										<td align="left" valign="top" class="<?php echo $cls?>"><?php echo $curval?></td>
								<?php
									}
								?>
							  </tr>
						<?php
							}	
					}	
						// Check whether the general features heading is to be displayed
						if($desc==1 or $var==1 or $bulk==1 or $weight ==1 or $shipping ==1 or $manufact==1 or $model==1 or $bonus==1 or review==1)
						{
						?>						  
						  <tr>
							<td colspan="4" align="left" valign="top" class="comparison_sec_header"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_COMPGENFEATURE'];?></td>
						  </tr>
						  <?php 
						 } 
						  	if($desc==1)
							{
						  ?>
							  <tr>
								  <td align="left" valign="top" class="comparison_mainheader"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_COMPDESC'];?></td>
								  <?php
									for ($i=0;$i<$num_products;$i++)
									{
										$cls = ($i==0)?'comparison_contentA':'comparison_contentB';
									?>
										  <td align="left" valign="top" class="<?php echo $cls?>"><?php echo stripslashes($compare_arr['description'][$i])?></td>
									<?php
									}
									?>	  
								</tr>
					  <?php
					   }
					  	if (count($compare_arr['var']) and $var ==1)
						{
						?>
							
							<tr>
							<td align="left" valign="top" class="comparison_mainheader"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_COMPVAR'];?></td>
							<?php
							for ($i=0;$i<$num_products;$i++)
							{
								$cls = ($i==0)?'comparison_contentA':'comparison_contentB';
							?>		
								<td align="left" valign="top" class="<?php echo $cls?>">
							<?php
								//print_r($compare_arr['var'][$i]);
								if (count($compare_arr['var'][$i]))
								{
									$j=1;
									foreach ($compare_arr['var'][$i] as $key=>$val)
									{
										if($j >1)
										{
											echo '<br />';
										}	
										$j ++;
										echo $val;	
									}	
								}		
								else
									echo 'N/A';
								?>								</td>
								<?php
								}
								?>	
							</tr>
					  <?php
					  }
					  if($bulk==1)
					  {
					  ?>
						   <tr>
							<td align="left" valign="top" class="comparison_mainheader"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_COMPBULK'];?></td>
							 <?php
								for ($i=0;$i<$num_products;$i++)
								{
									$cls = ($i==0)?'comparison_contentA':'comparison_contentB';
								?>	
									<td align="left" valign="top" class="<?php echo $cls?>">
									<?php 
										$k=0;
										for($j = 0;$j<count($compare_arr['bulkdiscount'][$i]);$j++)
										{
											if($k>0)
											{
												echo "<br/>";
											}
											echo $compare_arr['bulkdiscount'][$i][$j];
											$k++;
										}
									?>
									</td>
							<?php
								}	
							?>
						  </tr>
					  <?php
					  }
					  if($weight==1)
					  {
					  ?>
					  <tr>
						<td align="left" valign="top" class="comparison_mainheader"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_COMPWEIGHT'];?></td>
						 <?php
							for ($i=0;$i<$num_products;$i++)
							{
								$cls = ($i==0)?'comparison_contentA':'comparison_contentB';
							?>	
								<td align="left" valign="top" class="<?php echo $cls?>">
								<?php 
										echo $compare_arr['weight'][$i];
								?>
								</td>
						<?php
							}	
						?>
					  </tr>
					  <?php
					  }
					  if ($shipping==1)
					  {
					  ?>
					 <tr>
						<td align="left" valign="top" class="comparison_mainheader"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_COMPSHIP'];?></td>
						 <?php
							for ($i=0;$i<$num_products;$i++)
							{
								$cls = ($i==0)?'comparison_contentA':'comparison_contentB';
							?>	
								<td align="left" valign="top" class="<?php echo $cls?>">
								<?php 
										echo print_price($compare_arr['extrashipping'][$i],true);
								?></td>
						<?php
							}	
						?>
					  </tr>
					  <?php
					  }
					  if ($manufact==1)
					  {
					  ?>
						  <tr>
							<td align="left" valign="top" class="comparison_mainheader"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_COMPMANUFACT'];?></td>
							 <?php
								for ($i=0;$i<$num_products;$i++)
								{
									$cls = ($i==0)?'comparison_contentA':'comparison_contentB';
								?>	
									<td align="left" valign="top" class="<?php echo $cls?>">
									<?php 
											echo $compare_arr['manfactureid'][$i];
									?>
									</td>
							<?php
								}	
							?>
						  </tr>
					  <?php
					  }
					  if($model==1)
					  {
					  ?>
							<tr>
							<td align="left" valign="top" class="comparison_mainheader"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_COMPMODEL'];?></td>
							 <?php
								for ($i=0;$i<$num_products;$i++)
								{
									$cls = ($i==0)?'comparison_contentA':'comparison_contentB';
								?>	
									<td align="left" valign="top" class="<?php echo $cls?>">
									<?php 
											echo $compare_arr['model'][$i];
									?></td>
							<?php
								}	
							?>
						  </tr>
					  <?php
					  }
					  if($bonus==1)
					  {
					  ?>
						   <tr>
							<td align="left" valign="top" class="comparison_mainheader"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_COMPPOINTS'];?></td>
							 <?php
								for ($i=0;$i<$num_products;$i++)
								{
									$cls = ($i==0)?'comparison_contentA':'comparison_contentB';
								?>	
									<td align="left" valign="top" class="<?php echo $cls?>">
									<?php 
											echo $compare_arr['bonuspoints'][$i];
									?>
									</td>
							<?php
								}	
							?>
						  </tr>
					  <?php
					  }
					  // Check whether the product review module is active for the site
						$module_name = 'mod_product_reviews';
						if(in_array($module_name,$inlineSiteComponents) and $review==1)
						{
						?>
					  <tr>
						<td align="left" valign="top" class="comparison_mainheader"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_REVIEW_CAPTION'];?></td>
						 <?php
							for ($i=0;$i<$num_products;$i++)
							{
								$cls = ($i==0)?'comparison_contentA':'comparison_contentB';
							?>	
								<td align="left" valign="top" class="<?php echo $cls?>">
								<?php 
										echo $compare_arr['review'][$i];
								?>
								</td>
						<?php
							}	
						?>
					  </tr>
					  <?php
						}
					?>
					</table>
<?php 
		}
	}
	function Show_noProducts($title)
	{
		global $ecom_siteid,$Captions_arr;
		$Captions_arr['PROD_DETAILS'] 	= getCaptions('PROD_DETAILS');
	?>
		<table width="100%" border="0" cellpadding="0" cellspacing="1" class="prod_comparison_table">
		<tr>
			<td align="center" valign="top" class="prod_comparison_name"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_COMPNOPROD'];?></td>
		</tr>
		</table>	
	<?php	
	}
};