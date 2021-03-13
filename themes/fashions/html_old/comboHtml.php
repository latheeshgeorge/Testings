<?php
	/*############################################################################
	# Script Name 	: comboHtml.php
	# Description 	: Page which holds the display logic for middle combo
	# Coded by 		: Anu
	# Created on	: 26-Feb-2008
	# Modified by	: Anu
	# Modified On	: 26-Feb-2008
	##########################################################################*/
	class combo_Html
	{
		// Defining function to show the combo details
		function Show_Combo($title,$description,$combo_id)
			{
				global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr;
				$combosort_by			= $Settings_arr['product_orderfield_combo'];
				//$prodperpage			= $Settings_arr['product_maxcntperpage_shelf'];// product per page
				$showqty				= $Settings_arr['show_qty_box'];// show the qty box
				switch ($combosort_by)
					{
					case 'custom': // case of order by customer field
						$combosort_by		= 'b.comboprod_order';
					break;
					case 'product_name': // case of order by product name
						$combosort_by		= 'a.product_name';
					break;
					case 'price': // case of order by price
						$combosort_by		= 'a.product_webprice';
					break;
					default: // by default order by product name
						$combosort_by		= 'a.product_name';
					break;
					case 'product_id': // case of order by price
						$prodsort_by		= 'a.product_id';
						break;
				};
					$combosort_order		= $Settings_arr['product_orderby_combo'];
					///$prev_shelf				= 0;
					 // Check whether shelf_activateperiodchange is set to 1
					 $active 	= $comboData['combo_activateperiodchange'];
					 // Get the list of products to be shown in current shelf
					$sql_prod = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
										a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
										a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
										a.product_total_preorder_allowed,a.product_applytax,a.product_shortdesc,a.product_variable_display_type,
										b.combo_discount,a.product_bonuspoints ,
										a.product_stock_notification_required,a.product_alloworder_notinstock    
									FROM 
										products a,combo_products b 
									WHERE 
										b.combo_combo_id = ".$combo_id." 
										AND a.product_id = b.products_product_id 
										AND a.product_hide = 'N' 
									ORDER BY 
										$combosort_by $combosort_order ";
									
						$ret_prod = $db->query($sql_prod);
						if ($db->num_rows($ret_prod))
						{
							// Number of result to display on the page, will be in the LIMIT of the sql query also
							$querystring = ""; // if any additional query string required specify it over here
							
				
						?>
						<form method="post" action="<?php url_link('manage_products.html')?>" name='buyall_combo' id="buyall_combo" class="frm_cls">
		<div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?php  echo $title;?><?php //echo $Captions_arr['COMBO']['COMBO_TREEMENU_TITLE']?></div>
		<table width="100%" border="0" cellpadding="0" cellspacing="0"  class="combotable" width="100%">
		  <tr>
			<td class="combocontent">
			<table border="0" cellpadding="0" cellspacing="0" class="shelfBtable" width="100%">
			<?php if(($description !='')&&($description !='&nbsp;')&& ($description!='<br/>' or $description!='<br/>') ) {?>
			<tr>
				  <td align="left" valign="middle" class="shelfBtabletd" colspan="3"><?php echo $description;?></td></tr>
			   <?php 
			   }
				$combo_pdt_cnt = 0;
				$product_ids = '';
				$comp_active = isProductCompareEnabled();
				// Calling the function to get the type of image to shown for current 
				$pass_type = get_default_imagetype('fav_prod');
				while($row_prod = $db->fetch_array($ret_prod))
					{ 
					$combo_pdt_cnt ++;
					$td_pdt_style_class='';
					if($product_ids!='')
					$product_ids  .= ",".$row_prod['product_id'];
					else
					$product_ids  .= $row_prod['product_id'];
					// ######################################################
					// Check whether any variables exists for current product
					// ######################################################
					$sql_var = "SELECT var_id,var_name,var_value_exists, var_price 
								FROM 
									product_variables 
								WHERE 
									products_product_id = ".$row_prod['product_id']." 
									AND var_hide= 0
								ORDER BY 
									var_order";
					$ret_var = $db->query($sql_var);
					$variables_exists = $db->num_rows($ret_var);
					if($variables_exists){
						$td_pdt_style_class = 'shelfBtabletd_noborder';
					}else{
						$td_pdt_style_class = 'shelfBtabletd';
					}
					// Check whether total number of variables is 1 or more than 1
					if($variables_exists==1)
					{
						$vardisp_type = $row_prod['product_variable_display_type']; // take the display type from settings for current product
					}
					else 
					
						$vardisp_type = 'ADD'; // if the variable count is > 1 then by default the Add option will be displayed
					// ##############################################################################
					//  Check whether variable message exists for the product
					// ##############################################################################
					 $sql_msg = "SELECT message_id,message_title,message_type 
									FROM 
										product_variable_messages 
									WHERE 
										products_product_id = ".$row_prod['product_id']." 
										AND message_hide= 0
									ORDER BY 
										message_order";
					$ret_msg = $db->query($sql_msg);
					$variable_mesg_exists = $db->num_rows($ret_msg);
					if($variable_mesg_exists){
						$td_pdt_varstyle_class = 'shelfBtabletd_noborder';
					}else{
						$td_pdt_varstyle_class = 'shelfBtabletd';
					}
					if($combo_pdt_cnt>1){
					?>
					 <tr>
				  <td colspan="3" align="center" valign="middle" class="combosep"><?php echo '<img src="'.url_site_image('combosep.gif',1).'" border="0" alt="combo deals" />&nbsp;';?></td>
				  </tr><? 
				  }
					?>
				<?php /*?><tr  onmouseover="this.className='normalshelf_hover'" onmouseout="this.className='<?=$td_pdt_style_class?>'">
				  <td align="left" valign="middle" class="<?=$td_pdt_style_class?>">
				  <h1 class="shelfBprodname"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h1>
				  <h6 class="shelfBproddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6>
				  </td>
				  <td align="center" valign="middle" class="<?=$td_pdt_style_class?>"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
					<?php
						// Calling the function to get the type of image to shown for current 
						$pass_type = get_default_imagetype('midcombo');
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
					<?php if(isProductCompareEnabled())  { 
							dislplayCompareButton($row_prod['product_id']);
						}
					 ?>
					</td>
				  <td align="left" valign="middle" class="<?=$td_pdt_style_class?>"><?php 
					$price_class_arr['ul_class'] 		= 'shelfBul';
					$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
					$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
					$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
					$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
					echo show_Price($row_prod,$price_class_arr,'combo_1',true);
					//show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
					//show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points
				  ?>
				  <input type="hidden" name="fpurpose" value="Combo_Buyall" />
				  <input type="hidden" name="fproduct_id" value="" />
					<table border="0" cellspacing="0" cellpadding="0">
						
						
					  <tr>
						<td align="left" valign="bottom" class="infotd">
							<?php
								if($showqty==1)// this decision is made in the main shop settings
								{
							?>
								<div class="quantity"><?php echo $Captions_arr['COMMON']['COMMON_QTY']?><input type="hidden" class="quainput" name="qty_<?=$row_prod['product_id']?>"  value="1" /></div>
							<?php
								}
							?><?php show_moreinfo($row_prod,'infolink')?></td>
					<td align="left" valign="bottom" class="infotd"><?php
					//	$class_arr 					= array();
						//$class_arr['ADD_TO_CART']	= 'infolink';
						//$class_arr['PREORDER']		= 'infolink';
						//$class_arr['ENQUIRE']		= 'infolink';
						//show_addtocart($row_prod,$class_arr,$frm_name)
					?></td>
					  </tr>
	
					
					</table></td>
				</tr><?php */?>
				<tr>
				<td>
				<div class="search_main">
                    
                           					 <div class="search_image"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php 
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
																	<?php
														$prefix = "<div class='compare_li'>";
															$suffix = "</div>";
														 if($comp_active)  {
															dislplayCompareButton($row_prod['product_id'],$prefix,$suffix);
														}?>	
														</div>	
                           								
														 <div class="search_content">
																<div class="search_name" ><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="categoreyname_headerlink_myhome"><?php echo stripslashes($row_prod['product_name'])?></a></div>
																 <div class="search_des"><?php  echo stripslashes($row_prod['product_shortdesc'])?></div>
														      <?  if($showqty==1)// this decision is made in the main shop settings
																	{
																?>
																	<div class="quantity"><?php echo $Captions_arr['COMMON']['COMMON_QTY']?><input type="hidden" class="quainput" name="qty_<?=$row_prod['product_id']?>"  value="1" /></div>
																<?php
																	}
																	else
																	{
																	?>
																	<input type="hidden" class="quainput" name="qty_<?=$row_prod['product_id']?>"  value="1" />
																	<?php
																	}	
																	?>
																 <div class="search_price">
																 <UL>
																 
																  <?php $price_class_arr['ul_class'] 		= '';
																$price_class_arr['normal_class'] 	= 'search_normal_price';
																$price_class_arr['strike_class'] 	= 'search_price_strike';
																$price_class_arr['yousave_class'] 	= 'search_price_offer';
																$price_class_arr['discount_class'] 	= 'search_price_dis';
																echo show_Price($row_prod,$price_class_arr,'shelfcenter_1');
																?>
																</UL>
																  </div>
														          </div>
														 <div class="search_buy" align="right">
															
																<label><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
																</a></label>
															
														<?php
															
																$frm_name = uniqid('mafavhome_');
														?>	
																																	<label><?php show_moreinfo($row_prod,'product_info')?></label>
																	 <input type="hidden" name="fpurpose" value="Combo_Buyall" />
				 													 <input type="hidden" name="fproduct_id" value="" />
																	<?php
																	$prefix = "<DIV class='product_list_button_list'><label>"; 
																	$suffix = "</label> </DIV>";
																		//$class_arr 					= array();
																		//$class_arr['ADD_TO_CART']	= 'product_list_button';
																		//$class_arr['PREORDER']		= 'product_list_button';
																		//$class_arr['ENQUIRE']		= 'product_list_button';
																		//show_addtocart($row_prod,$class_arr,$frm_name,false,$prefix,$suffix)
																	?>
																</div>
				<div style="float:left; width:100%;background-color:#F5F5F5;">
				<!--	to check for product variables-->
												  <?php
					/// for checking the varibales in the  products
					
				if ($db->num_rows($ret_var) or $db->num_rows($ret_msg))
				{
			  ?>
					<table width="100%" border="0" cellpadding="0" cellspacing="3">
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
						?>
								  <tr>
									<td align="left" valign="middle" class="<?=$td_pdt_varstyle_class?>"><?php echo stripslashes($row_var['var_name'])?></td>
									<td align="left" valign="middle" class="shelfBtabletdright">
										<?php
										if ($row_var['var_value_exists']==1)
										{
										?>
												<select name="var<?=$row_prod['product_id']?>_<?php echo $row_var['var_id']?>">
												<?php 
													while ($row_vals = $db->fetch_array($ret_vals))
													{
												?>
														<option value="<?php echo $row_vals['var_value_id']?>"><?php echo stripslashes($row_vals['var_value'])?><?php echo Show_Variable_Additional_Price($row_prod,$row_vals['var_addprice'],$vardisp_type)?></option>
												<?php
													}
												?>
												</select>							
										<?php
										}
										else
										{
										?>
											<input type="checkbox" name="var<?=$row_prod['product_id']?>_<?php echo $row_var['var_id']?>" id="var<?php echo $row_var['var_id']?>" value="1" /><?php echo Show_Variable_Additional_Price($row_prod,$row_var['var_price'],$vardisp_type)?>
										<?php
										}
										?>
									</td>
								  </tr>
						<?php
								}
							}
					?>  
				<?php
					}
					// ######################################################
					// End of variables section
					// ######################################################
					
					// ##############################################################################
					//  Case of variable messages
					// ##############################################################################
					
						if ($db->num_rows($ret_msg))
						{
				  ?>
							<?php
								while ($row_msg = $db->fetch_array($ret_msg))
								{ 
							?>
									  <tr>
										<td align="left" valign="top" class="shelfBtabletd"><?php echo stripslashes($row_msg['message_title'])?></td>
										<td align="left" valign="top" class="shelfBtabletd">
											<?php
											if ($row_msg['message_type']=='TXTBX')
											{
											?>
												<input type="text" name="varmsg<?=$row_prod['product_id']?>_<?php echo $row_msg['message_id']?>" id="varmsg<?php echo $row_msg['message_id']?>" value="" />
											<?php
											}
											else
											{
											?>
												<textarea name="varmsg<?=$row_prod['product_id']?>_<?php echo $row_msg['message_id']?>" id="varmsg<?php echo $row_msg['message_id']?>" rows="3" cols="15"></textarea>
											<?php
											}
											?>
										</td>
									  </tr>
							<?php
								}
							?>  
				<?php		
						}
					// ######################################################
					// End of variable messages
					// ######################################################
						
				?>	
					</table>
				<?php
					}
				?>	
																</div>
															</div>	
				</td>
				</tr>
				<? }?>
				<!--<form name="buyall_combo" action="" id="buyall_combo" method="post">-->
			   <tr>
				  <td colspan="3" align="center" valign="middle" class="combosep"><label> 
				  		<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
				  		<input type="hidden" name="product_ids" id="product_ids" value="" />
					 	<input type="hidden" name="product_qtys" id="product_qtys" value="" />
						<input name="submit_buycombo" type="submit" class="buttongray" id="submit_buycombo" value="<?=$Captions_arr['COMBO']['COMBO_BUY_ALL_BUTTON']?>" onclick="buy_combo();"/>
				  </label></td>
				  </tr>
			  </table>
			</td></tr>
			</table></form>
		<?php		
		}
	}
};	
?>