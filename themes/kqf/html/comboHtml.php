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
			$combosort_by				= $Settings_arr['product_orderfield_combo'];
			$Captions_arr['COMBO']	= getCaptions('COMBO');
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
									b.combo_discount,a.product_bonuspoints ,b.comboprod_id,
									a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,a.product_variablesaddonprice_exists,
									a.product_variablecomboprice_allowed,a.product_variablecombocommon_image_allowed,a.default_comb_id,
									a.price_normalprefix,a.price_normalsuffix, a.price_fromprefix, a.price_fromsuffix,price_specialofferprefix, a.price_specialoffersuffix, 
									a.price_discountprefix, a.price_discountsuffix, a.price_yousaveprefix, a.price_yousavesuffix,a.price_noprice,
									a.product_saleicon_show,a.product_saleicon_text,a.product_newicon_show,a.product_newicon_text,a.product_averagerating,
									a.product_freedelivery     
								FROM 
									products a,combo_products b 
								WHERE 
									b.combo_combo_id = ".$combo_id." 
									AND a.product_id = b.products_product_id 
									AND a.product_hide = 'N' 
									AND (
												CASE product_alloworder_notinstock
														WHEN ('N') THEN 
																CASE product_preorder_allowed
																	WHEN ('Y') THEN product_total_preorder_allowed>0 
																ELSE 	
																	product_actualstock>0 
																END
														ELSE
															1
												END
											)											
								ORDER BY 
									$combosort_by $combosort_order ";
								
					$ret_prod = $db->query($sql_prod);
					if ($db->num_rows($ret_prod))
					{
						// Get the number of products actually in current combo deal
						$sql_cnt = "SELECT count(comboprod_id) as cnts 
											FROM 
												combo_products 
											WHERE 
												combo_combo_id = $combo_id 
												AND sites_site_id = $ecom_siteid ";
						$ret_cnt 	= $db->query($sql_cnt);
						list($tot_cnts)= $db->fetch_array($ret_cnt);;
						if ($tot_cnts==$db->num_rows($ret_prod))
							$proceed_combo = true;
						else
							$proceed_combo = false;
							
						$querystring = ""; // if any additional query string required specify it over here
					
					
					//section to check whether the comboproducts is in customer group
				/* Sony Jul 01, 2013 */
				global $discthm_group_prod_array;
				if(count($discthm_group_prod_array))
				{
				$sql_prod_chk = "SELECT a.product_id      
								FROM 
									products a,combo_products b 
								WHERE 
									b.combo_combo_id = ".$combo_id." 
									AND a.product_id = b.products_product_id 
									AND a.product_hide = 'N' 
									AND (
												CASE product_alloworder_notinstock
														WHEN ('N') THEN 
																CASE product_preorder_allowed
																	WHEN ('Y') THEN product_total_preorder_allowed>0 
																ELSE 	
																	product_actualstock>0 
																END
														ELSE
															1
												END
											)											
								ORDER BY 
									$combosort_by $combosort_order ";
					$ret_prod_chk = $db->query($sql_prod_chk);
					$prod_array   = array(); 
					while($row_prod_chk=$db->fetch_array($ret_prod_chk))
					{
					  $prod_array[] = $row_prod_chk['product_id'];
					}
					$inter_array = array();
					$inter_array = array_intersect($discthm_group_prod_array,$prod_array);
					//print_r($inter_array);

					//print_r($prod_array);
					if(!array_diff($prod_array, $inter_array) && !array_diff($inter_array, $prod_array))
					{
					   $proceed_combo1 = true;
					}
					else
					{
					   $proceed_combo = false;
					}
				}
					//end of customer group check
					?>
			<form method="post" action="<?php url_link('manage_products.html')?>" name='buyall_combo' id="buyall_combo" class="frm_cls">
		    <input type="hidden" name="fpurpose" value="Combo_Buyall" />
			<input type="hidden" name="fproduct_id" value="" />
			<input type="hidden" name="pass_url" value="'.<?php $_SERVER['REQUEST_URI']?>.'" />
			<input type="hidden" name="product_ids" id="product_ids" value="" />
			<input type="hidden" name="product_qtys" id="product_qtys" value="" />
			<div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?php  echo $title;?><?php //echo $Captions_arr['COMBO']['COMBO_TREEMENU_TITLE']?></div>
			<table width="100%" border="0" cellpadding="0" cellspacing="0"  class="combotable">
		  	<tr>
			<td class="combocontent">
			<table border="0" cellpadding="0" cellspacing="0" class="shelfBtable">
			<?php
		  	if($proceed_combo)
		  	{
				// Get the combo bundle price
				$sql_combo = "SELECT combo_bundleprice 
								FROM 
									combo 
								WHERE 
									combo_id = $combo_id 
									AND sites_site_id  = $ecom_siteid 
								LIMIT 
									1";
				$ret_combo = $db->query($sql_combo);
				list($bundle_price) = $db->fetch_array($ret_combo); 
		?>
			
			<?php if(($description !='')&&($description !='&nbsp;')&& ($description!='<br>' or $description!='<br/>')) {?>
			<tr>
				  <td align="left" valign="middle" class="shelfBtabletd" colspan="3"><?php echo $description;?></td>
			</tr>
			<?php
			}
			?>
			<tr>
					  <td colspan="3" align="center" valign="middle" class="combosep">
					  <span class="bundle_price" >
					  <?=$Captions_arr['COMBO']['COMBO_BUNDLE_PRICE']?> <?php echo print_price($bundle_price,true,false);	?>
					  </span>
					  <label> 
							<?php /*<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
							<input type="hidden" name="product_ids" id="product_ids" value="" />
							<input type="hidden" name="product_qtys" id="product_qtys" value="" />*/?>
							<input name="submit_buycombo" type="button" class="buttonblackbig" id="submit_buycombo" value="<?=$Captions_arr['COMBO']['COMBO_BUY_ALL_BUTTON']?>" onclick="buy_combo();"/>
					  </label></td>
					  </tr>	  
			   <?php 
					$combo_pdt_cnt = 0;
					$product_ids = '';
					// Calling the function to get the type of image to shown for current 
					$pass_type = get_default_imagetype('midcombo');
					$prod_compare_enabled = isProductCompareEnabled();
					while($row_prod = $db->fetch_array($ret_prod))
					{ 
						// Overriding the product discount with the % set for the current combo
						$row_prod['product_discount_enteredasval'] 	= 0;
						$row_prod['product_discount'] 				=	$row_prod['combo_discount'];
						$td_pdt_style_class='';
						if($product_ids!='')
							$product_ids  .= ",".$row_prod['product_id'];
						else
							$product_ids  .= $row_prod['product_id'];
						if ($combo_pdt_cnt>0 and $combo_pdt_cnt!=$tot_cnts)
						{
						?>
							 <tr>
								<td colspan="3" align="center" valign="middle" class="combosep"><img src="<?php url_site_image('combosep.gif')?>" border="0" alt="combo deals" /></td>
							</tr>
						<? 
					  	}
						$combo_pdt_cnt ++;
						// Check whether combinations exists for current product in combo_products_variable_combination table
						$sql_combination 	= "SELECT comb_id
												FROM 
													combo_products_variable_combination 
												WHERE 
													combo_products_comboprod_id = ".$row_prod['comboprod_id'];
						$ret_combination 	= $db->query($sql_combination);
						$tot_combinations 	= $db->num_rows($ret_combination);
						if($tot_combinations) // Case if combination exists
						{
							$cur_combination = 1;
							$max_cnt = 3;
							$cur_cnt = 0;
							if($tot_combinations>1)
							{
								$disp_type = '3row';
								//echo '<div class="shelfAtable" >';
							}	
							else
							{
								$disp_type = '1row';
							    //echo '<div class="combo_shelfBtable">';
							}	
							while ($row_combination = $db->fetch_array($ret_combination))
							{
								$row_prod['combination_id'] = $row_combination['comb_id'];
								$row_prod['comboprod_id'] 	= $row_prod['comboprod_id'];
								$row_prod['cur_combination'] = $cur_combination;
								$row_prod['tot_combination'] = $tot_combinations;
								if($cur_combination==1)
									$keep_selected=true;
								else
									$keep_selected = false;
									if($disp_type =='3row' && $cur_cnt==0)									{
									  echo "<tr>";
									}
									elseif($disp_type =='1row')
									{
									echo "<tr>";
									}
								$this->show_combo_product_details($row_prod,$keep_selected,$disp_type,$cur_cnt,$max_cnt);
								$cur_cnt++;	
								$cur_combination++;
								if($disp_type =='3row')
								{
									if($cur_cnt>=$max_cnt)
									{
										$cur_cnt =0;
										echo "</tr>";
									}
								 }
								 else
								 {
								  echo "</tr>";
								 }
									// If in case total product is less than the max allowed per row then handle that situation
								
								
							}
							if($disp_type =='3row')
								{
									if($cur_cnt<$max_cnt)
									{
									   if($cur_cnt!=0)
										{ 
											echo "<td colspan='".($max_cnt-$cur_cnt)."'>&nbsp;</td>";
											echo "</tr>";
										} 
									}
								}
								else
								{
								  echo "</tr>";
								}
						}
						else // case if combination does not exists
						{
						?>
						 <tr>
						<?php	
						   $disp_type = '1row';
								$row_prod['combination_id'] = 0;
								$row_prod['comboprod_id'] 	= $row_prod['comboprod_id'];
								$this->show_combo_product_details($row_prod,true,'1row',$cur_cnt,$max_cnt);
						?>
						</tr>	
						<?php	
						}
							
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
						$td_pdt_varstyle_class = 'shelfBtabletd';
						
					 }?>
				   <tr>
					  <td colspan="3" align="center" valign="middle" class="combosep">
					  <span class="bundle_price" >
					  <?=$Captions_arr['COMBO']['COMBO_BUNDLE_PRICE']?> <?php echo print_price($bundle_price,true,false);	?>
					  </span>
					  <label> 
							<input name="submit_buycombo" type="button" class="buttonblackbig" id="submit_buycombo" value="<?=$Captions_arr['COMBO']['COMBO_BUY_ALL_BUTTON']?>" onclick="buy_combo();"/>
					  </label></td>
					  </tr>
				<?php
				
			}
			else // case if combo deal cannot be displayed since some of the products are out of stock
			{
			?>
				 <tr>
				  <td colspan="3" align="center" valign="middle" class="errormsg">
				  <?php 
						echo $Captions_arr['COMBO']['COMBO_DEAL_CANNOT_DISPLAY'];
					?>
				  </td>
				  </tr>
				<?php	
			}
			?>  
			  </table>
			</td></tr>
			</table></form>
		<?php		
		
			}
			else // Case if nothing is to be displayed for current combo
			{
			?>
				<div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?php  echo stripslashes($title);?><?php //echo $Captions_arr['COMBO']['COMBO_TREEMENU_TITLE']?></div>
				<table width="100%" border="0" cellpadding="0" cellspacing="0"  class="combotable">
				<tr>
				<td class="combocontent"><?php /*<div class="combonamediv"><?php //echo $title?></div> */ ?>
					<table border="0" cellpadding="0" cellspacing="0" class="shelfBtable">
					<tr>
						<td colspan="3" align="center" valign="middle" class="errormsg">
						<?php 
							echo $Captions_arr['COMBO']['COMBO_DEAL_CANNOT_DISPLAY'];
						?>
						</td>
					</tr>
					</table>
				</td>
				</tr>
				</table>
			<?php
			}
	}
    function show_combo_product_details($row_prod,$keepselected=false,$disp_type='',$cur_col,$max_col)
	{
		global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents;
		$Captions_arr['PROD_DETAILS'] 	= getCaptions('PROD_DETAILS');
		$combosort_by				= $Settings_arr['product_orderfield_combo'];
		$Captions_arr['COMBO']		= getCaptions('COMBO');
		$showqty					= $Settings_arr['show_qty_box'];// show the qty box
		$comb_id					= $row_prod['combination_id'];
		$prodmap_id					= $row_prod['comboprod_id'];
		$pass_type 					= get_default_imagetype('midcombo');
		$prod_compare_enabled 		= isProductCompareEnabled();
		//echo $row_prod['product_id'] ." -- ".$row_prod['combination_id'];
		if($keepselected==true)
			$main_class = 'imagelisttabletd_sel';
		else 
			$main_class = '';	
		switch($disp_type)
		{
			case '3row':
			?>
		<td class="shelfAtabletd" align="left" valign="top" onmouseover="this.className='normalshelf_hover'" onmouseout="this.className='shelfAtabletd'">
		<ul class="shelfBul">
		
			<li><h1 class="shelfAprodname_combo"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h1></li>
	     <li>
	<?php
				if($comb_id) // show the radio button only if combination exists
				{
					
				?>
				<input style="display:none" type="radio" name="combprod_map_<?php echo $prodmap_id?>" id="combprod_map_<?php echo $prodmap_id?>" value="<?php echo $comb_id?>" <?php echo ($keepselected==true)?'checked="checked"':''?> />
				<?php
				if($keepselected==true)
				{
					$sel_img = 'selected_img.png';
				}
				else
				{
					$sel_img = 'deselected_img.png';
				}
				?>		
				<img id="combosel_<?php echo $comb_id?>" src="<?php url_site_image($sel_img)?>" border="0" onclick="handle_combo_combination_selection('<?php echo $ecom_hostname?>','combosel_<?php echo $comb_id?>','combprod_map_<?php echo $prodmap_id?>','<?php echo $prodmap_id?>','<?php echo $comb_id?>')" />	
				<?php
				
				}
				else
				{
				?>
				<img  src="<?php url_site_image('selected_img.gif')?>" border="0" />
				<?php
				}	
				?>
				</li>
		<ul class="shelfpriceul">
		<li class="shelfAnormalprice">
		<?php echo print_price($row_prod['combo_discount'],true,false)?>
		</li>
		</ul>
			<li class="shelfimg">
				<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
				<?php
					// Calling the function to get the type of image to shown for current 
					//$pass_type = get_default_imagetype('midshelf');
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
				</a>	</li>
				<?php
				 if($prod_compare_enabled)  
				 {
				dislplayCompareButton($row_prod['product_id']);
				}
		?>
		<li><h6 class="shelfAproddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6>
		<?php
		 //show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points
			/*$pass_arr['main_cls'] 		= 'bonus_point';
			$pass_arr['caption_cls'] 	= 'bonus_point_caption';
			$pass_arr['point_cls'] 		= 'bonus_point_number';
			show_bonus_points_msg_multicolor($row_prod,$pass_arr);*/
			if($row_prod['product_bonuspoints']> 0)
			{
				echo '<div class="prod_list_bonusB">
						<span class="bonus_point_number_a">
							<span>'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_BONUSPOINTSA']).'</span>
						</span>
						<span class="bonus_point_caption_b">'.$row_prod['product_bonuspoints'].'</span>
						<span class="bonus_point_number_c">
							<span>'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_BONUSPOINTSB']).'</span></span>
						</div>';
			}
			?>
		</li>
		<?php
		if($row_prod['product_bulkdiscount_allowed']=='Y' || $row_prod['product_freedelivery']==1)
		{
			?>
			<li><div class="mid_shlf2_free_div">
			<?php
			if($row_prod['product_bulkdiscount_allowed']=='Y')
			{
				?>
				<img src="<?php url_site_image('bulk_discount.png')?>" alt="Bulk Discount"/>
				<?php
			}
			if($row_prod['product_freedelivery']==1)
			{	
				?>
				<img src="<?php url_site_image('free_delivery.png')?>" alt="Free Delivery"/>
				<?php
			}
			?>
			</div></li>
			<?php
		}
		?>
		</ul>
		<?php
		$module_name = 'mod_product_reviews';
		if(in_array($module_name,$inlineSiteComponents))
		{
			if($row_prod['product_averagerating']>=0)
			{
		?>
			<div class="mid_shlf2_free_star">
			<?php
				display_rating($row_prod['product_averagerating'],0,'star.png','star_lite.png');
				?>
			</div>
		<?php
			}
		}	
		if($row_prod['product_saleicon_show']==1)
		{
			$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
			if($desc!='')
			{
				?>	
				<div class="prod_list_new"><?php echo $desc?></div>
				<?php
			}
		}
		if($row_prod['product_newicon_show']==1)
		{
			$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
			if($desc!='')
			{
				?>
				<div class="prod_list_new"><?php echo $desc?></div>
				<?php
			}
		}
														
				if ($comb_id)
				{
					$sql_comb_det = "SELECT a.var_id,a.var_value_id 
										FROM 
											combo_products_variable_combination_map a,product_variables b
										WHERE 
											combo_products_variable_combination_comb_id = $comb_id 
											AND a.var_id=b.var_id 
										ORDER BY 
											b.var_order";
					$ret_comb_det = $db->query($sql_comb_det);
				?>
					<table width="100%" border="0" cellpadding="0" cellspacing="3">
				<?php
				// Case of variables
					if ($db->num_rows($ret_comb_det) )
					{
						while ($row_comb_det = $db->fetch_array($ret_comb_det))
						{
							// Get the name of variable 
							$sql_var = "SELECT var_id,var_name,var_value_exists 
											FROM 
												product_variables 
											WHERE 
												var_id = ".$row_comb_det['var_id']." 
											LIMIT 
												1";
							$ret_var = $db->query($sql_var);
							if($db->num_rows($ret_var))
							{
								$row_var = $db->fetch_array($ret_var);
							} 
							if($row_var['var_value_exists']==1)
							{
								// Get the name of variable value
								$sql_vardate = "SELECT var_value 
												FROM 
													product_variable_data  
												WHERE 
													var_value_id = ".$row_comb_det['var_value_id']." 
												LIMIT 
													1";
								$ret_vardata = $db->query($sql_vardate);
								if($db->num_rows($ret_vardata))
								{
									$row_vardata = $db->fetch_array($ret_vardata);
								} 
							}
							else
								$row_vardata = array();	
												
					?>
							<tr>
								<td align="left" valign="middle" class="mid_comboshelfBtabletd" width="40%">
									<b><?php 
										echo stripslash_normal($row_var['var_name']);
									?></b>
								</td>
								<td align="left" valign="middle" class="mid_comboshelfBtabletd">
								<b><?php
								if ($row_var['var_value_exists']==1)
								{
									echo ': '.$row_vardata['var_value'];
									if($comb_id==0)
									{
								?>
										<input type="hidden" name="varhold<?=$row_prod['product_id']?>_<?php echo $row_var['var_id']?>" id="varhold<?=$row_prod['product_id']?>_<?php echo $row_var['var_id']?>" value="<?php echo $row_comb_det['var_value_id']?>" />
								<?php
									}
									else
									{
								?>
										<input type="hidden" name="combprod_<?php echo $comb_id?>_<?=$row_prod['product_id']?>_<?php echo $row_var['var_id']?>" id="combprod_<?php echo $comb_id?>_<?=$row_prod['product_id']?>_<?php echo $row_var['var_id']?>" value="<?php echo $row_comb_det['var_value_id']?>" />									
								<?php	
									}	
								}
								else
								{
									if($comb_id==0)
									{
								?>
										<input type="hidden" name="varhold<?=$row_prod['product_id']?>_<?php echo $row_var['var_id']?>" id="varhold<?=$row_prod['product_id']?>_<?php echo $row_var['var_id']?>" value="1" />
								<?php
									}
									else
									{
								?>
										<input type="hidden" name="combprod_<?php echo $comb_id?>_<?=$row_prod['product_id']?>_<?php echo $row_var['var_id']?>" id="combprod_<?php echo $comb_id?>_<?=$row_prod['product_id']?>_<?php echo $row_var['var_id']?>"  value="1" />									
								<?php	
									}	
								}
								?>	
								</b></td>
							</tr>	
					<?php			
						}
					}
				?>  
				</table>
				<?php
				// Get distinct combinations exists for current product map irrespective of combination
				if($row_prod['cur_combination'] == $row_prod['tot_combination'])
				{
					$sql_comb = "SELECT comb_id 
									FROM 
										combo_products_variable_combination 
									WHERE 
										combo_products_comboprod_id = $prodmap_id";
					$ret_comb = $db->query($sql_comb);
					if($db->num_rows($ret_comb))
					{
						while ($row_comb = $db->fetch_array($ret_comb))
						{
							$comb_arr[] = $row_comb['comb_id'];
						}
						$sql_allvars = "SELECT distinct a.var_id 
											FROM 
												combo_products_variable_combination_map a, product_variables b
											WHERE 
												a.var_id = b.var_id
												AND a.combo_products_variable_combination_comb_id IN (".implode(',',$comb_arr).") 
											ORDER BY b.var_order";
						$ret_allvars = $db->query($sql_allvars);
						if($db->num_rows($ret_allvars))
						{
							while($row_allvars = $db->fetch_array($ret_allvars))
							{
						?>
								<input type="hidden" name="var<?=$row_prod['product_id']?>_<?php echo $row_allvars['var_id']?>" id="var<?=$row_prod['product_id']?>_<?php echo $row_allvars['var_id']?>" value="" />				
						<?php	
							}
						}
					}
				?>
				<input type="hidden" class="quainput" name="qty_<?=$row_prod['product_id']?>"  value="1" />
				<?php	
				}
				}
				else
				{
				?>
				<input type="hidden" class="quainput" name="qty_<?=$row_prod['product_id']?>"  value="1" />
				<?php
				}
				?>
				</td>
				<?php
			break;
			default: // case of any other case other than 2row
				if($comb_id) // show the radio button only if combination exists
				{
					
				?>
				<input style="display:none" type="radio" name="combprod_map_<?php echo $prodmap_id?>" id="combprod_map_<?php echo $prodmap_id?>" value="<?php echo $comb_id?>" <?php echo ($keepselected==true)?'checked="checked"':''?> />
				<?php
				}
				?>	
					<tr onmouseover="this.className='normalshelf_hover'" onmouseout="this.className='shelfBtabletd'">
										<td align="left" valign="middle" class="shelfBtabletd" colspan="3">
													<table width="100%" border="0" cellpadding="0" cellspacing="3">
													<tr>
													<td align="left" valign="middle" >	
																<h1 class="shelfBprodname"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h1>
														
																
																<h6 class="shelfBproddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6>
													<?php	
													if($row_prod['product_saleicon_show']==1)
														{
															$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
															if($desc!='')
															{
																?>	
																<div class="prod_list_new"><?php echo $desc?></div>
																<?php
															}
														}
														if($row_prod['product_newicon_show']==1)
														{
															$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
															if($desc!='')
															{
																?>
																<div class="prod_list_new"><?php echo $desc?></div>
																<?php
															}
														}
														$module_name = 'mod_product_reviews';
														if(in_array($module_name,$inlineSiteComponents))
														{
															if($row_prod['product_averagerating']>=0)
															{
														?>
															<div class="mid_shlf2_free_star">
															<?php
																display_rating($row_prod['product_averagerating'],0,'star.png','star_lite.png');
																?>
															</div>
														<?php
															}
														}	
										?>
													</td>
													<td align="center" valign="middle" class="shelfBtabletd_combo">
															<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
															<?php
																// Calling the function to get the type of image to shown for current 
																//$pass_type = get_default_imagetype('midshelf');
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
														<?php if($prod_compare_enabled)  {
															dislplayCompareButton($row_prod['product_id']);
														}?>
													</td>
													<td align="left" valign="middle" class="shelfBtabletd_combo">
													<ul class="shelfBul">
														<li class="shelfBnormalprice">
															<?php echo print_price($row_prod['combo_discount'],true,false)?>
														</li>
													</ul>
											<?php				
															//show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points
															/*$pass_arr['main_cls'] 		= 'bonus_point';
															$pass_arr['caption_cls'] 	= 'bonus_point_caption';
															$pass_arr['point_cls'] 		= 'bonus_point_number';
															show_bonus_points_msg_multicolor($row_prod,$pass_arr);*/
															if($row_prod['product_bonuspoints'] > 0)
															{
																echo '<div class="prod_list_bonusB">
																		<span class="bonus_point_number_a">
																			<span>'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_BONUSPOINTSA']).'</span>
																		</span>
																		<span class="bonus_point_caption_b">'.$row_prod['product_bonuspoints'].'</span>
																		<span class="bonus_point_number_c">
																			<span>'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_BONUSPOINTSB']).'</span></span>
																		</div>';
															}
															if($row_prod['product_bulkdiscount_allowed']=='Y' || $row_prod['product_freedelivery']==1)
															{
																?>
																<div class="mid_shlf2_free_div">
																<?php
																if($row_prod['product_bulkdiscount_allowed']=='Y')
																{
																	?>
																	<img src="<?php url_site_image('bulk_discount.png')?>" alt="Bulk Discount"/>
																	<?php
																}
																if($row_prod['product_freedelivery']==1)
																{	
																	?>
																	<img src="<?php url_site_image('free_delivery.png')?>" alt="Free Delivery"/>
																	<?php
																}
																?>
																</div>
																<?php
															}
															?>
															
													</td>
											</tr>
					
					<?
					if ($comb_id)
					{
						$sql_comb_det = "SELECT a.var_id,a.var_value_id 
											FROM 
												combo_products_variable_combination_map a,product_variables b
											WHERE 
												combo_products_variable_combination_comb_id = $comb_id 
												AND a.var_id=b.var_id 
											ORDER BY 
												b.var_order";
						$ret_comb_det = $db->query($sql_comb_det);
					?>
						<tr><td align="left" colspan="3"><table width="100%" border="0" cellpadding="0" cellspacing="3">
					<?php
					// Case of variables
						if ($db->num_rows($ret_comb_det) )
						{
							while ($row_comb_det = $db->fetch_array($ret_comb_det))
							{
								// Get the name of variable 
								$sql_var = "SELECT var_id,var_name,var_value_exists 
												FROM 
													product_variables 
												WHERE 
													var_id = ".$row_comb_det['var_id']." 
												LIMIT 
													1";
								$ret_var = $db->query($sql_var);
								if($db->num_rows($ret_var))
								{
									$row_var = $db->fetch_array($ret_var);
								} 
								if($row_var['var_value_exists']==1)
								{
									// Get the name of variable value
									$sql_vardate = "SELECT var_value 
													FROM 
														product_variable_data  
													WHERE 
														var_value_id = ".$row_comb_det['var_value_id']." 
													LIMIT 
														1";
									$ret_vardata = $db->query($sql_vardate);
									if($db->num_rows($ret_vardata))
									{
										$row_vardata = $db->fetch_array($ret_vardata);
									} 
								}
								else
									$row_vardata = array();	
													
						?>
								<tr>
									<td align="right" valign="middle" class="combo_mid_shelfBtabletd" width="15%"><b>
										<?php 
											echo stripslash_normal($row_var['var_name']);
										?>
									</b></td>
									<td align="left" valign="middle" class="combo_mid_shelfBtabletd"><b>
									<?php
									if ($row_var['var_value_exists']==1)
									{
										echo ': '.$row_vardata['var_value'];
										if($comb_id==0)
										{
									?>
											<input type="hidden" name="varhold<?=$row_prod['product_id']?>_<?php echo $row_var['var_id']?>" id="varhold<?=$row_prod['product_id']?>_<?php echo $row_var['var_id']?>" value="<?php echo $row_comb_det['var_value_id']?>" />
									<?php
										}
										else
										{
									?>
											<input type="hidden" name="combprod_<?php echo $comb_id?>_<?=$row_prod['product_id']?>_<?php echo $row_var['var_id']?>" id="combprod_<?php echo $comb_id?>_<?=$row_prod['product_id']?>_<?php echo $row_var['var_id']?>" value="<?php echo $row_comb_det['var_value_id']?>" />									
									<?php	
										}	
									}
									else
									{
										if($comb_id==0)
										{
									?>
											<input type="hidden" name="varhold<?=$row_prod['product_id']?>_<?php echo $row_var['var_id']?>" id="varhold<?=$row_prod['product_id']?>_<?php echo $row_var['var_id']?>" value="1" />
									<?php
										}
										else
										{
									?>
											<input type="hidden" name="combprod_<?php echo $comb_id?>_<?=$row_prod['product_id']?>_<?php echo $row_var['var_id']?>" id="combprod_<?php echo $comb_id?>_<?=$row_prod['product_id']?>_<?php echo $row_var['var_id']?>"  value="1" />									
									<?php	
										}	
									}
									?>	
									</b></td>
								</tr>	
						<?php			
							}
						}
					?>  
					       </table>
					    </td>
					  </tr>
					 </table>
				   </td>
				</tr>
					<?php
					// Get distinct combinations exists for current product map irrespective of combination
					if($row_prod['cur_combination'] == $row_prod['tot_combination'])
					{
						$sql_comb = "SELECT comb_id 
										FROM 
											combo_products_variable_combination 
										WHERE 
											combo_products_comboprod_id = $prodmap_id";
						$ret_comb = $db->query($sql_comb);
						if($db->num_rows($ret_comb))
						{
							while ($row_comb = $db->fetch_array($ret_comb))
							{
								$comb_arr[] = $row_comb['comb_id'];
							}
							$sql_allvars = "SELECT distinct a.var_id 
												FROM 
													combo_products_variable_combination_map a, product_variables b
												WHERE 
													a.var_id = b.var_id
													AND a.combo_products_variable_combination_comb_id IN (".implode(',',$comb_arr).") 
												ORDER BY b.var_order";
							$ret_allvars = $db->query($sql_allvars);
							if($db->num_rows($ret_allvars))
							{
								while($row_allvars = $db->fetch_array($ret_allvars))
								{
							?>
									<input type="hidden" name="var<?=$row_prod['product_id']?>_<?php echo $row_allvars['var_id']?>" id="var<?=$row_prod['product_id']?>_<?php echo $row_allvars['var_id']?>" value="" />				
							<?php	
								}
							}
						}
					?>
					<input type="hidden" class="quainput" name="qty_<?=$row_prod['product_id']?>"  value="1" />
					<?php	
					}
					}
					else
					{
					?>
					  </table>
				    </td>
				 </tr>
					<input type="hidden" class="quainput" name="qty_<?=$row_prod['product_id']?>"  value="1" />
					<?php
					}
					?>	
			<?php		
			break;
		};
	}
	function Show_Combo_Multiple($ret_combos,$combprod_id,$deal_mod)
	{
		global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr;
		$Captions_arr['COMBO']	= getCaptions('COMBO');
		$combosort_by			= $Settings_arr['product_orderfield_combo'];
		$Captions_arr['COMBO']	= getCaptions('COMBO');
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
				$combosort_by		= 'a.product_id';
				break;
		};
		$combosort_order		= $Settings_arr['product_orderby_combo'];
		?>
			
			<div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a>  
			<?php
					if($deal_mod=='showprodrelated') // case if coming to show deal with selected products only
					{
						// Get the name of current product
						$sql_prodname = "SELECT product_name 
										FROM 
											products 
										WHERE 
											product_id = ".$combprod_id." 
										LIMIT 
											1";
						$ret_prodname= $db->query($sql_prodname);
						if($db->num_rows($ret_prodname))
						{
							$row_prodname  	= $db->fetch_array($ret_prodname);
							$prodname		= '>> <a href="'.url_product($combprod_id,$row_prodname['product_name'],2).'" title="'.stripslashes($row_prodname['product_name']).'">'.stripslashes($row_prodname['product_name']).'</a></li> '; 
							echo $prodname;
						}	  
					}
				if($Captions_arr['COMBO']['COMBO_BUNDLED_OFFER']!='')
					echo '>> '.$Captions_arr['COMBO']['COMBO_BUNDLED_OFFER'];
					
					?>
			
			</div>
			<table width="100%" border="0" cellpadding="0" cellspacing="0"  >
		<?php
		$cur_tr=0;
		$max_cnt =3;
		// Fetch the details of combos
		while ($row_combos = $db->fetch_array($ret_combos))
		{
			 $active 	= $row_combos['combo_activateperiodchange'];
			 $combo_id  = $row_combos['combo_id'];
			 // Get the list of products to be shown in current shelf
			$sql_prod = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
								a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
								a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
								a.product_total_preorder_allowed,a.product_applytax,a.product_shortdesc,a.product_variable_display_type,
								b.combo_discount,a.product_bonuspoints ,b.comboprod_id,
								a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,a.product_variablesaddonprice_exists,
								a.product_variablecomboprice_allowed,a.product_variablecombocommon_image_allowed,a.default_comb_id,
								a.price_normalprefix,a.price_normalsuffix, a.price_fromprefix, a.price_fromsuffix,price_specialofferprefix, a.price_specialoffersuffix, 
								a.price_discountprefix, a.price_discountsuffix, a.price_yousaveprefix, a.price_yousavesuffix,a.price_noprice,
								a.product_saleicon_show,a.product_saleicon_text,a.product_newicon_show,a.product_newicon_text,a.product_averagerating,
								a.product_freedelivery   
							FROM 
								products a,combo_products b 
							WHERE 
								b.combo_combo_id = ".$combo_id." 
								AND a.product_id = b.products_product_id 
								AND a.product_hide = 'N' 
								AND (
											CASE product_alloworder_notinstock
													WHEN ('N') THEN 
															CASE product_preorder_allowed
																WHEN ('Y') THEN product_total_preorder_allowed>0 
															ELSE 	
																product_actualstock>0 
															END
													ELSE
														1
											END
										)											
							ORDER BY 
								$combosort_by $combosort_order ";
							
				$ret_prod = $db->query($sql_prod);
				// Get the number of products actually in current combo deal
				$sql_cnt = "SELECT count(comboprod_id) as cnts 
									FROM 
										combo_products 
									WHERE 
										combo_combo_id = $combo_id 
										AND sites_site_id = $ecom_siteid ";
				$ret_cnt 	= $db->query($sql_cnt);
				list($tot_cnts)= $db->fetch_array($ret_cnt);;
				if ($tot_cnts==$db->num_rows($ret_prod))
					$proceed_combo = true;
				else
					$proceed_combo = false;
				//section to check whether the comboproducts is in customer group
				/* Sony Jul 01, 2013 */
				global $discthm_group_prod_array;
				$sql_prod_chk = "SELECT a.product_id      
								FROM 
									products a,combo_products b 
								WHERE 
									b.combo_combo_id = ".$combo_id." 
									AND a.product_id = b.products_product_id 
									AND a.product_hide = 'N' 
									AND (
												CASE product_alloworder_notinstock
														WHEN ('N') THEN 
																CASE product_preorder_allowed
																	WHEN ('Y') THEN product_total_preorder_allowed>0 
																ELSE 	
																	product_actualstock>0 
																END
														ELSE
															1
												END
											)											
								ORDER BY 
									$combosort_by $combosort_order ";
					$ret_prod_chk = $db->query($sql_prod_chk);
					$prod_array   = array(); 
					while($row_prod_chk=$db->fetch_array($ret_prod_chk))
					{
					  $prod_array[] = $row_prod_chk['product_id'];
					}
					$inter_array = array();
					$inter_array = array_intersect($discthm_group_prod_array,$prod_array);
					//print_r($inter_array);

					//print_r($prod_array);
					if(!array_diff($prod_array, $inter_array) && !array_diff($inter_array, $prod_array))
					{
					   $proceed_combo1 = true;
					}
					else
					{
					   $proceed_combo = false;
					}
					//end of customer group check
				if($proceed_combo==true)
				{
				if($cur_tr==0)
				{
				echo "<tr>";
				}
				$cur_tr++;
				?>
						<td class="combo_multi_td">
						<div class="lf_combodeal">
							<?php
							if ($title and $row_combos['combo_hidename']==0)
							{
							?>			
							<div class="lf_combodeal_top"><?php echo $title?></div>
							<?php
							}
							?>
							<div class="lf_combodeal_middle">
							<?php
							$bundle_price = $row_combos['combo_bundleprice'];
							$cur_cnt=1;
							$i=0;
							$tot_cnt = $db->num_rows($ret_prod);
							while ($row_prod = $db->fetch_array($ret_prod))
							{
							$cur_cnt++;
								if($i!=0 && $i!=$tot_cnt)
								{
							?>
								<div class="lf_combodeal_plus"></div>
							<?php
								}
								$i++;
							?>		
							 <div class="lf_combodeal_img"> <?php
								// Calling the function to get the type of image to shown for current 
								$pass_type = 'image_iconpath';
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
								?></div>
							<?php
							}
							?>	
                            <div class="lf_combodeal_separation"></div>
							<div class="lf_combodeal_price">Bundled Price: <?php echo print_price($bundle_price)?></div>
							</div>
							<div class="lf_combodeal_bottom"> <a href="<?php url_combo($row_combos['combo_id'],$row_combos['combo_name'],-1)?>" class="lf-combodeal-showall" title="<?php echo $Captions_arr['COMMON']['SHOW_DET']?>"><?php echo $Captions_arr['COMMON']['SHOW_DET']?></a> </div>
						   </div>
						</td>
					<?
				}	
				if($cur_tr>=$max_cnt)
				{
				echo "</tr>";
				$cur_tr =0;
				}
		}
		if($cur_tr<$max_cnt && $cur_tr!=0)
		{
		$colsp= $max_cnt-$cur_tr;
		echo "<td colspan='".$colsp."' class='combo_multi_td'>&nbsp;</td></tr>";
		}
		?>
		
		</table>
			<?php
	}
};	
?>
