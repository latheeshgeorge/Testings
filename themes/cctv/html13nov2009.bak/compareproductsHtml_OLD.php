<?php
	/*############################################################################
	# Script Name 	: shelfHtml.php
	# Description 	: Page which holds the display logic for middle shelves
	# Coded by 		: Sny
	# Created on	: 29-Dec-2007
	# Modified by	: Sny
	# Modified On	: 01-Feb-2008
	##########################################################################*/
	class compareproducts_Html
	{
		// Defining function to show the shelf details
		function Show_Products($title,$compare_products_arr)
		{
		
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents;
			
			// ** Fetch any captions for product details page
			$Captions_arr['PROD_DETAILS'] 	= getCaptions('PROD_DETAILS');
			
			$num_products = count($_SESSION['compare_products']);
			if ($num_products  && isProductCompareEnabled())
			{
				$showqty		= $Settings_arr['show_qty_box'];// show the qty box
				$compare_product_ids	=implode(",",$_SESSION['compare_products']);		
								
						// Get the list of products to be shown in compare products
						$sql_prod = "SELECT product_id,product_name,product_variablestock_allowed,product_show_cartlink,
										product_preorder_allowed,product_show_enquirelink,product_webstock,product_webprice,
										product_discount,product_discount_enteredasval,product_bulkdiscount_allowed,
										product_total_preorder_allowed,product_applytax,product_shortdesc,product_averagerating,
										manufacture_id,product_model,product_bonuspoints 
									FROM 
										products   
									WHERE 
										 product_id IN (".$compare_product_ids.")";
						$ret_prod = $db->query($sql_prod);
						
						$sql_label_cnt = "SELECT id as cnt_labels 
									  FROM
									  		product_labels  
									  WHERE  
									  		products_product_id IN (".$compare_product_ids.")";
						$ret_label_cnt = $db->query($sql_label_cnt);
						$label_cnt = $db->num_rows($ret_label_cnt);
						
						$sql_var_cnt = "SELECT var_id as cnt_vars 
									  FROM
									  		product_variables
									  WHERE  
									  		products_product_id IN (".$compare_product_ids.")";
						$ret_var_cnt = $db->query($sql_var_cnt);
						$var_cnt = $db->num_rows($ret_var_cnt);
						
						if ($db->num_rows($ret_prod))
						{
							
							?>
			<table border="0" cellpadding="0" cellspacing="0" class="productcomparisontable">
						<?php
						if($cur_title)
						{
						?>
							<tr>
								<td colspan="<?=$num_products ?>" class="shelfAheader" align="left"><?php echo $cur_title?></td>
							</tr>
						<?php
						}
						?>
						
						<?php
							$max_col = $Settings_arr['no_of_products_to_compare'];
							$cur_col = 0;
							$compare_width = 100/$num_products ;
							$prodcur_arr = array();
							$prod_name_arr = array();
							$i = 0;
							$rowvar = 0;
							$manuvar =0;
							$modvar =0;
							$bonusvar =0;
							
							while($row_prod = $db->fetch_array($ret_prod))
							{
								//$prodcur_arr[] = $row_prod;
								//print_r($prodcur_arr);
								//##############################################################
								// Showing the title, description and image part for the product
								//##############################################################
								$prod_name_arr[] = $row_prod['product_name'];
								$prod_id_arr[] 	= $row_prod['product_id'];
								//$prod_id_arr[]['product_id'] 	= $row_prod['product_id'];
								$prod_variablestock_arr[] 			= $row_prod['product_variablestock_allowed'];
								$prod_shortdesc_arr[]		 		= $row_prod['product_shortdesc'];
								$prod_webstock_arr[] 				= $row_prod['product_webstock'];
								$prod_webprice_arr[] 				= $row_prod['product_webprice'];
								$prod_discount_arr[] 				= $row_prod['product_discount'];
								$prod_discount_enteredasval_arr[]	= $row_prod['product_discount_enteredasval'];
								$prod_bulkdiscount_allowed_arr[] 	= $row_prod['product_bulkdiscount_allowed'];
								$prod_total_preorder_allowed_arr[] 	= $row_prod['product_total_preorder_allowed'];
								$prod_applytax_arr[] 				= $row_prod['product_applytax'];
								$prod_variablestock_allowed_arr[] 	= $row_prod['product_variablestock_allowed'];
								$prod_show_cartlink_arr[] 			= $row_prod['product_show_cartlink'];
								$prod_preorder_allowed_arr[] 		= $row_prod['product_preorder_allowed'];
								$prod_show_enquirelink_arr[] 		= $row_prod['product_show_enquirelink'];
								$prod_rating[] 						= $row_prod['product_averagerating'];	
								$prod_manufact[] 					= $row_prod['manufacture_id'];	
								$prod_model[] 						= $row_prod['product_model'];		
								$product_bonuspoints[] 				= $row_prod['product_bonuspoints'];		
									if($row_prod['product_bulkdiscount_allowed']=='Y') {
									 	$rowvar = 1;
									}
									if(trim($row_prod['manufacture_id'])) {
									 	$manuvar = 1;
									}
									if(trim($row_prod['product_model'])) {
									 	$modvar = 1;
									} 
									if(trim($row_prod['product_bonuspoints'])) {
									 	$bonusvar = 1;
									}				
								
								}  
						?> 
						<tr >
								<? for($i=0;$i<$num_products;$i++){
								 ?>
								<td class="productcom_name" align="left" valign="middle" style="width:<?=$compare_width?>%;">
									<a class="productcom_name_link" href="<?php url_product($prod_id_arr[$i],"'".$prod_name_arr[$i]."'",-1)?>" title="<?php echo stripslashes($prod_name_arr[$i])?>"><?php echo stripslashes($prod_name_arr[$i])?></a>
								</td>
								<? } ?>
						</tr>
								
								<tr >
								<? for($i=0;$i<$num_products;$i++){
								 ?>
								<td class="productcom_rating" align="right" valign="middle" style="width:<?=$compare_width?>%;">
								<?PHP
									// Check whether the product review module is active for the site
									$module_name = 'mod_product_reviews';
									if(in_array($module_name,$inlineSiteComponents))
									{
											echo $Captions_arr['PROD_DETAILS']['PRODDET_REVIEW_CAPTION'];
											for ($x=0;$x<$prod_rating[$i];$x++)
											{
												echo '<img src="'.url_site_image('reviewstar_on.gif',1).'" border="0" alt="revscoreimg" />&nbsp;'; 
											}
											for ($x=$prod_rating[$i];$x<10;$x++)
											{
												echo '<img src="'.url_site_image('reviewstar_off.gif',1).'" border="0"  alt="revscoreimg"/>&nbsp;'; 
											}
								} ?>
								</td>
								<? } ?>
								</tr><tr>
								<? for($i=0;$i<$num_products;$i++){  ?>
								<td class="productcom_image" align="center">
								<a href="<?php url_product($prod_id_arr[$i],"'".$prod_name_arr[$i]."'",-1)?>" title="<?php echo stripslashes($prod_name_arr[$i])?>">
								<?php 
							//	echo $prod_id_arr[$i];
								// Calling the function to get the type of image to shown for current 
									$pass_type = get_default_imagetype('midshelf');
									// Calling the function to get the image to be shown
									$img_arr = get_imagelist('prod',"'".$prod_id_arr[$i]."'",$pass_type,0,0,1);
									if(count($img_arr))
									{
										show_image(url_root_image($img_arr[0][$pass_type],1),"'".$prod_name_arr[$i]."'","'".$prod_name_arr[$i]."'");
									}
									else
									{
										// calling the function to get the default image
										$no_img = get_noimage('prod',$pass_type); 
										if ($no_img)
										{
											show_image($no_img,"'".$prod_name_arr[$i]."'","'".$prod_name_arr[$i]."'");
										}	
									}	
								?></a>
								</td>
								<? }?>
								</tr>
								<?PHP 
								if ($Settings_arr['product_show_instock'])
									{
								?>
								<tr>
								<? 
								for($i=0;$i<$num_products;$i++)
								{ 
								
									   $compare=1;
										echo '<td class="productcom_avaiable" valign="top">&nbsp;';
										 echo " <span class='stockdetailstd'>".get_stockdetails($prod_id_arr[$i],$compare)."</span>";
										echo '</td>';
										
								}		
									?>	</tr>
								<? } ?>	
									
								<tr>
								<? for($i=0;$i<$num_products;$i++){ 
										echo '<td class="productcom_price_td" valign="top">';
										$prod_price_arr['product_id']							= $prod_id_arr[$i];
										$prod_price_arr['product_variablestock_allowed']		= $prod_variablestock_allowed_arr[$i];
										$prod_price_arr['product_webstock']						= $prod_webstock_arr[$i];
										$prod_price_arr['product_webprice']						= $prod_webprice_arr[$i];
										$prod_price_arr['product_discount']						= $prod_discount_arr[$i];
										$prod_price_arr['product_discount_enteredasval']		= $prod_discount_enteredasval_arr[$i];
										$prod_price_arr['product_bulkdiscount_allowed']			= $prod_bulkdiscount_allowed_arr[$i];
										$prod_price_arr['product_total_preorder_allowed']		= $prod_total_preorder_allowed_arr[$i];
										$prod_price_arr['product_applytax']						= $prod_applytax_arr[$i];
										
										$price_class_arr['ul_class'] 		= 'productcom_price';
										$price_class_arr['normal_class'] 	= 'productcom_normalprice';
										$price_class_arr['strike_class'] 	= 'productcom_strikeprice';
										$price_class_arr['yousave_class'] 	= 'productcom_yousaveprice';
										$price_class_arr['discount_class'] 	= 'shelfAdiscountprice';
										echo show_Price($prod_price_arr,$price_class_arr,'shelfcenter_3');
										echo '</td>';
										}
									?>	</tr> 
									<tr>
									<? for($i=0;$i<$num_products;$i++){ ?>
									 <td class="productcom_details" valign="top">
									 <?
									echo stripslashes($prod_shortdesc_arr[$i])?>
									</td>
									<? } ?>
									</tr>
									<? if ($label_cnt)
										{ 
									?>
									<tr>
									<? for($i=0;$i<$num_products;$i++){ ?>
									<td class="productcom_label" valign="top">
									<?
										$this->display_labels($prod_id_arr[$i]);
									?>
									</td>
									<?
									 } 
									 ?>
									</tr>
									
									<tr>
									<? for($i=0;$i<$num_products;$i++){ ?>
									 <td class="productcom_details" valign="top">
									 <?  
									
								if($manuvar==1) { 
									(trim($prod_manufact[$i]))?$manufactId = stripslashes($prod_manufact[$i]):$manufactId = "NA";	
									echo "<strong>Manufacture Id : </strong>".$manufactId; }
								if($modvar==1) {	
									(trim($prod_model[$i]))?$prod_model = stripslashes($prod_manufact[$i]):$prod_model = "NA";
									echo "<br/><strong>Model Number :</strong>".$prod_model; }
								if($bonusvar==1) {	
									(trim($product_bonuspoints[$i]))?$product_bonuspoints = stripslashes($product_bonuspoints[$i]):$product_bonuspoints = "NA";
									echo "<br/><strong>Bonus Points :</strong>".$product_bonuspoints; }
									?>
									</td>
									<? } ?>
									</tr>
									
									
									
									<? 
									$var_class = 'productcom_varialbe';
									}else{
									$var_class = 'productcom_label';
									}
									if ($var_cnt)
									{ 
									?>
									<tr>
									<? for($i=0;$i<$num_products;$i++){ ?>
									<td class="<?=$var_class?>" valign="top">
									<?
										$this->show_ProductVariables($prod_id_arr[$i]);
									?>
									</td>
									<?
									}
									  ?>
									</tr>
									<?PHP if($rowvar==1) { ?>
									<tr>
									<? for($i=0;$i<$num_products;$i++){ ?>
									 <td class="productcom_details" valign="top">
									 <?  
											$this->show_BulkDiscounts($prod_id_arr[$i]);
									?>&nbsp;
									</td>
									<? } ?>
									</tr>
										<? } ?>
									
									
									<? 
									$info_class = 'productcom_label';
									 }else{
									 $info_class = 'productcom_varialbe';
									 }
									 
									?>
									<tr>
									<? for($i=0;$i<$num_products;$i++){
									$frm_name = uniqid('compare_');
											?>	
									
									
									<td class="<?=$info_class?>" valign="top">
									<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls">
									<input type="hidden" name="fpurpose" value="" />
									<input type="hidden" name="fproduct_id" value="" />
									<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" /><div style="width:45%;float:left;">
									<?
									
									$prod_info_arr['product_id']					 = $prod_id_arr[$i];
									$prod_info_arr['product_name']					 = $prod_name_arr[$i];
									$prod_info_arr['product_total_preorder_allowed'] = $prod_total_preorder_allowed_arr[$i];
									$prod_info_arr['product_applytax']				 = $prod_applytax_arr[$i];
									$prod_info_arr['product_variablestock_allowed']	 = $prod_variablestock_allowed_arr[$i];
									$prod_info_arr['product_show_cartlink']			 = $prod_show_cartlink_arr[$i];
									$prod_info_arr['product_preorder_allowed']		 = $prod_preorder_allowed_arr[$i];
									$prod_info_arr['product_show_enquirelink']		 = $prod_show_enquirelink_arr[$i];
									$prod_info_arr['product_webstock']		 = $prod_webstock_arr[$i];
										$class_arr 					= array();
										$class_arr['ADD_TO_CART']	= 'quantity_infolink';
										$class_arr['PREORDER']		= 'quantity_infolink';
										$class_arr['ENQUIRE']		= 'quantity_infolink';
										show_moreinfo($prod_info_arr,'quantity_infolink');
										?>
										</div><div style="width:45%;float:left;">
										<?
									//	print_r($prod_info_arr);
										show_addtocart($prod_info_arr,$class_arr,$frm_name);
										
									?></div></form>
									</td>
									
									<?
									 } 
									 ?>
									</tr>
							</table>
			<?php 		
			}								
					
		}
	}		
	function display_labels($product_id){
	global $db,$ecom_siteid;
			// ** Get the list of all labels set for the site
				$sql_labels = "SELECT a.label_id,a.label_name,b.label_value,a.is_textbox,product_site_labels_values_label_value_id 
								FROM
									product_site_labels a,product_labels b 
								WHERE 
									b.products_product_id = ".$product_id." 
									AND a.label_hide = 0 
									AND a.label_id = b.product_site_labels_label_id 
								ORDER BY 
									a.label_order";
									$ret_labels = $db->query($sql_labels);
				if ($db->num_rows($ret_labels))
				{
					while ($row_labels = $db->fetch_array($ret_labels))
						{
							if ($row_labels['is_textbox']==1)
								$vals = stripslashes($row_labels['label_value']);
							else
							{
								$sql_labelval = "SELECT label_value 
													FROM 
														product_site_labels_values  
													WHERE 
														product_site_labels_label_id=".$row_labels['label_id']." 
														AND label_value_id = ".$row_labels['product_site_labels_values_label_value_id'];
								$ret_labelval = $db->query($sql_labelval);
								if ($db->num_rows($ret_labelval))
								{
									$row_labelval = $db->fetch_array($ret_labelval);
									$vals = stripslashes($row_labelval['label_value']);
								}
														
							}
							if ($vals)
							{
							?>
								 <span style="display:block"><strong><?php echo stripslashes($row_labels['label_name'])?>:</strong> 
										<?php echo $vals?></span>
							<?php	
							}	
						}
				
					}
				
			
	}
	
							
				/*									
												<tr>
			  <td colspan="2" align="left" class="productdetd">
			  <?php
				// ** Get the list of all labels set for the site
				$sql_labels = "SELECT a.label_id,a.label_name,b.label_value,a.is_textbox,product_site_labels_values_label_value_id 
								FROM
									product_site_labels a,product_labels b 
								WHERE 
									b.products_product_id = ".$row_prod['product_id']." 
									AND a.label_hide = 0 
									AND a.label_id = b.product_site_labels_label_id 
								ORDER BY 
									a.label_order";
				$ret_labels = $db->query($sql_labels);
				if ($db->num_rows($ret_labels))
				{
			  ?>
					<ul class="productdetailsfeature">
					<li><strong><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_PRODOVERVIEW'];?></strong></li>
			   
				<?php
						while ($row_labels = $db->fetch_array($ret_labels))
						{
							if ($row_labels['is_textbox']==1)
								$vals = stripslashes($row_labels['label_value']);
							else
							{
								$sql_labelval = "SELECT label_value 
													FROM 
														product_site_labels_values  
													WHERE 
														product_site_labels_label_id=".$row_labels['label_id']." 
														AND label_value_id = ".$row_labels['product_site_labels_values_label_value_id'];
								$ret_labelval = $db->query($sql_labelval);
								if ($db->num_rows($ret_labelval))
								{
									$row_labelval = $db->fetch_array($ret_labelval);
									$vals = stripslashes($row_labelval['label_value']);
								}
														
							}
							if ($vals)
							{
							?>
								<li>
									<ul>
										<li><strong><?php echo stripslashes($row_labels['label_name'])?></strong></li>
										<li><?php echo $vals?></li>
									</ul>
								</li>
							<?php	
							}	
						}
				?>
					</ul>
				<?php
				}
				?>			</td>
			</tr>
			<?
			if($row_prod['product_variablestock_allowed']=='Y') // case of showing the variables in the same row as that of image
				{
				?>
				<tr>
				<td align="left" valign="top" class="productdetd">
					<?php
						// Show the product variables and product messages
						$this->show_ProductVariables($row_prod['product_id']);
					?>	
				</td>
				</tr>
				<?php
				}
				?>
			
			
		
												<? $frm_name = uniqid('shelf_'); ?>
												<tr>
													<td class="shelfAtabletd" >
														<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls">
														<input type="hidden" name="fpurpose" value="" />
														<input type="hidden" name="fproduct_id" value="" />
														<?php
															if($showqty==1)// this decision is made in the main shop settings
															{
														?>
															<div class="quantity"><?php echo $Captions_arr['COMMON']['COMMON_QTY']?><input type="text" class="quainput" name="qty"  value="1" /></div>
														<?php
															}
														?>
														<div class="infodiv">
															<div class="infodivleft"><?php show_moreinfo($row_prod,'infolink')?></div>
															<div class="infodivright">
															<?php
																$class_arr 					= array();
																$class_arr['ADD_TO_CART']	= 'infolink';
																$class_arr['PREORDER']		= 'infolink';
																$class_arr['ENQUIRE']		= 'infolink';
																show_addtocart($row_prod,$class_arr,$frm_name)
															?>
															</div>
														</div>
														</form>
													</td>
													
													
											<?php
											/*if ($cur_col>=$max_col)
												{
													echo "</tr><tr>";
													}*/
												//$row_prod[$cur_col]
												//$cur_col++;
												/*if ($cur_col>=$max_col)
												{
													echo "</tr>";
													$cur_tempcol = $cur_col = 0;
													//##############################################################
													// Showing the more info and add to cart links after each row in 
													// case of breaking to new row while looping
													//##############################################################
													echo "<tr>";
													foreach($prodcur_arr as $k=>$prod_arr)
													{
														$frm_name = uniqid('shelf_');
													?>
														<td class="shelfAtabletd" style="width:<?=$compare_width?>%;">
															<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls">
															<input type="hidden" name="fpurpose" value="" />
															<input type="hidden" name="fproduct_id" value="" />
															<?php
																if($showqty==1)// this decision is made in the main shop settings
																{
															?>
																<div class="quantity"><?php echo $Captions_arr['COMMON']['COMMON_QTY']?><input type="text" class="quainput" name="qty"  value="1" /></div>
															<?php
																}
															?>
															<div class="infodiv">
																<div class="infodivleft"><?php show_moreinfo($prod_arr,'infolink')?></div>
																<div class="infodivright">
																<?php
																	$class_arr 					= array();
																	$class_arr['ADD_TO_CART']	= 'infolink';
																	$class_arr['PREORDER']		= 'infolink';
																	$class_arr['ENQUIRE']		= 'infolink';
																	show_addtocart($prod_arr,$class_arr,$frm_name)
																?>
																</div>
															</div>
															</form>
														</td>
											<?php
														++$cur_tempcol;
														// done to handle the case of breaking to new linel
														if ($cur_tempcol>=$max_col)
														{
															echo "</tr>";
															$cur_tempcol=0;
														}
													}
													echo "<tr>";
													$prodcur_arr = array();	
												}*/
											//}
											// If in case total product is less than the max allowed per row then handle that situation
											//if ($cur_col<$max_col)
											//{
											
											//	echo "<td colspan='".($max_col-$cur_col)."'>&nbsp;</td></tr><tr>";
												//$cur_tempcol = $cur_col = 0;
												//##############################################################
												// Done to handle the case of showing the qty, add to cart and more info links
												// in case if total product is less than the max allower per row.
												//##############################################################
												//foreach($prodcur_arr as $k=>$prod_arr)
												//{
												
													
												
													//++$cur_tempcol;
													//if ($cur_tempcol>=$max_col)
													//{
													//	echo "</tr><tr>";
													///	$cur_tempcol=0;
													//}
												//}
											//	echo "<td colspan='".($max_col-$cur_tempcol)."'>&nbsp;</td></tr>";
											
											//}
											//else
											//	echo "</tr>";
											/* $prodcur_arr = array();
											?></tr>
										</table>
							<?
							
						}
				}	
		}*/
		
	/* Function to show the bulk discount*/
		function show_BulkDiscounts($product_id)
		{
			global $db,$ecom_siteid,$Captions_arr;
			// Section to show the bulk discount details
			$bulkdisc_details = product_BulkDiscount_Details($product_id);
			if (count($bulkdisc_details['qty']))
			{
			?>	
				<table width="100%" border="0" cellspacing="0" cellpadding="0" >
				  <tr>
					<td align="left" class="bulkdiscountheader"><?php echo $Captions_arr['PROD_DETAILS']['BULK_DISC_HEAD']?></td>
				  </tr>
				  <?php
					for($i=0;$i<count($bulkdisc_details['qty']);$i++)
					{
					?>	
					   <tr>
						<td class="bulkdiscountcontent" align="left"><?php echo $bulkdisc_details['qty'][$i].' '.$Captions_arr['PROD_DETAILS']['BULK_FOR'].' '?> 
							<?php echo print_price($bulkdisc_details['price'][$i]).' '.$Captions_arr['PROD_DETAILS']['BULK_EACH']?>
						</td>
					  </tr>
				  <?php
					}
				  ?>
				</table>
			<?php
			} else {
				echo " Not Available ";
			}
		}
		
			// ** Function to display the variables. This is written as function to avoid code repetation in various if conditions
		function show_ProductVariables($product_id)
		{
			global $db,$ecom_siteid;
			//$_REQUEST['product_id']  =  $product_id;
			// ######################################################
			// Check whether any variables exists for current product
			// ######################################################
			$sql_var = "SELECT var_id,var_name,var_value_exists, var_price 
						FROM 
							product_variables 
						WHERE 
							products_product_id = ".$product_id." 
							AND var_hide= 0
						ORDER BY 
							var_order";
			$ret_var = $db->query($sql_var);
	
			
			if ($db->num_rows($ret_var))
			{
				
		  ?>
				
				<?php
					// Case of variables
					if ($db->num_rows($ret_var) or $db->num_rows($ret_msg))
					{
						while ($row_var = $db->fetch_array($ret_var))
						{
							if ($row_var['var_value_exists']==1)
							{
								// check whether values exists current variable
								$sql_vals = "SELECT var_value_id, var_addprice,var_value 
												FROM 
													product_variable_data 
												WHERE 
													product_variables_var_id =".$row_var['var_id']." 
												ORDER BY 
													var_order";
								$ret_vals = $db->query($sql_vals);
								if ($db->num_rows($ret_vals))
								{
									$var_Proceed = true;
								}
							}
							else
								$var_Proceed = true;
							if ($var_Proceed)// Show the variable if it is valid to show
							{
							if ($row_var['var_value_exists']==1)
									{
									$var_vals='';
					?>
							 <span style="display:block"><strong><?php echo stripslashes($row_var['var_name'])?>:</strong>
							
									<?php
									
									while ($row_vals = $db->fetch_array($ret_vals))
												{
												//echo stripslashes($row_vals['var_value']).",";
												if($var_vals!='')
												$var_vals .= ",".$row_vals['var_value'];
												else
													$var_vals = $row_vals['var_value'];
												}
												echo $var_vals;
												
									/*?>
											<select name="var_<?php echo $row_var['var_id']?>">
											<?php 
												while ($row_vals = $db->fetch_array($ret_vals))
												{
											?>
													<option value="<?php echo $row_vals['var_value_id']?>"><?php echo stripslashes($row_vals['var_value'])?><?php echo Show_Variable_Additional_Price($row_vals['var_addprice'])?></option>
											<?php
												}
											?>
											</select>			*/	?>	</span>		
									<?php
									}
									
									?>
								
					<?php
							}
						}
				?>  
			<?php
				}
				// ######################################################
				// End of variables section
				// ######################################################
				
				
					
			?>	
			
			<?php
				
			}
		}
	};	
?>