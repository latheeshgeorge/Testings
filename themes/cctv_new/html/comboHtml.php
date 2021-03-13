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
				 //Toget the title for the combo
				 if($title =='')
				 {
				 $sql_combo_name = "SELECT combo_name 
								FROM 
									combo 
								WHERE 
									combo_id = $combo_id 
									AND sites_site_id  = $ecom_siteid 
								LIMIT 
									1";
				$ret_combo_name = $db->query($sql_combo_name);
				list($title) = $db->fetch_array($ret_combo_name); 
				 }
				
				 // Get the list of products to be shown in current shelf
				$sql_prod = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
									a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
									a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,a.product_freedelivery,
									a.product_total_preorder_allowed,a.product_applytax,a.product_shortdesc,a.product_variable_display_type,
									b.combo_discount,a.product_bonuspoints ,b.comboprod_id,
									a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,a.product_variablesaddonprice_exists,
									a.product_variablecomboprice_allowed,a.product_variablecombocommon_image_allowed,a.default_comb_id,
									a.price_normalprefix,a.price_normalsuffix, a.price_fromprefix, a.price_fromsuffix,price_specialofferprefix, a.price_specialoffersuffix, 
									a.price_discountprefix, a.price_discountsuffix, a.price_yousaveprefix, a.price_yousavesuffix,a.price_noprice  
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
					$tot_prod = $db->num_rows($ret_prod);
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
					?>
			<form method="post" action="<?php url_link('manage_products.html')?>" name='buyall_combo' id="buyall_combo" class="frm_cls">
			<input type="hidden" name="fpurpose" value="Combo_Buyall" />
			<input type="hidden" name="fproduct_id" value="" />
			<div class="tree_con">
			<div class="tree_top"></div>
				<div class="tree_middle">
					<div class="pro_det_treemenu">
					<ul>
					<li><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> &gt;&gt; </li>
					<li> <?php  echo $title;?> </li>
					</ul>
					</div>
				</div>
			<div class="tree_bottom"></div>
			</div>
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
					  <?=$Captions_arr['COMBO']['COMBO_BUNDLE_PRICE']?>: <?php echo print_price($bundle_price,true,false);	?>
					  </span>
					  <label> 
							<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
							<input type="hidden" name="product_ids" id="product_ids" value="" />
							<input type="hidden" name="product_qtys" id="product_qtys" value="" />
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
							?>
							
							<?php								
							$max_cnt = 2;
							$cur_cnt = 0;
							if($tot_combinations>1)
							{
								$disp_type = '3row';
								echo '<table width="100%" border="0" cellpadding="0" cellspacing="0"  class="mid_shelfA">
									';
							}	
							else
							{
								$disp_type = '1row';
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
									if($cur_cnt==0)
									{
									echo '<tr>';
									}
								$cur_cnt++;	
								$this->show_combo_product_details($row_prod,$keep_selected,$disp_type,$cur_cnt,$max_cnt);
								$cur_combination++;
								if($disp_type =='3row')
								{
									if($cur_cnt>=$max_cnt)
									{ 
									echo "</tr>";
									$cur_cnt=0;
									}
									
								}	
							}
								if($disp_type =='3row')
								{ 
								$cur_prodcnt=$max_cnt-$cur_cnt;
								
									if ($cur_cnt<$max_cnt)
									{
									echo "<td colspan='".$cur_prodcnt."'></td></tr>";
									}
									echo '</table>';
							    }										
							?>
						
							<?php
						}
						else // case if combination does not exists
						{
							?>
							<tr>
							<td colspan="3" >
						<?php
								$disp_type = '1row';
								$row_prod['combination_id'] = 0;
								$row_prod['comboprod_id'] 	= $row_prod['comboprod_id'];
								$this->show_combo_product_details($row_prod,true,'1row',$cur_cnt,$max_cnt);
								?>
							</td>
							</tr>	
								<?
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
		$combosort_by				= $Settings_arr['product_orderfield_combo'];
		$Captions_arr['COMBO']		= getCaptions('COMBO');
		$showqty					= $Settings_arr['show_qty_box'];// show the qty box
		$comb_id					= $row_prod['combination_id'];
		$prodmap_id					= $row_prod['comboprod_id'];
		$pass_type 					= get_default_imagetype('midcombo');
		$prod_compare_enabled 		= isProductCompareEnabled();

		if($keepselected==true)
			$main_class = 'imagelisttabletd_sel';
		else 
			$main_class = '';
		switch($disp_type)
		{
			case '1row':	
		?>
		<tr>
	<td class="mid_shelfB_top_lf33" colspan="3">
	<div class="mid_shelfB">
	<table width="100%" border="0" cellpadding="0" cellspacing="0"  class="mid_shelfB_table">
	
	<tr>
	<td class="mid_shelfB_top_lf">&nbsp;</td>
	<td class="mid_shelfB_top_mid">
	
		<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="prod_infolink"><?php echo stripslashes($row_prod['product_name'])?></a>
	
	</td>
	<td class="mid_shelfB_top_rt">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="3" class="mid_shelfB_mid" valign="top">
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
		<td class="shelfBimg" valign="top">
		<?php
		if($row_prod['product_bulkdiscount_allowed']=='Y')
			{
			?>
				<div class="bulk_discounta"></div>
			<?php
			}
			else
			{
			?>
				<div class="bulk_discounta_blank"></div>
			<?php
			}		
			?>
				<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="prod_infolink">
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
			
		</td>
		<td class="shelfB_cnts" valign="top">
			<?php
			if($row_prod['product_freedelivery']==1)
			{	
			?>
				<div class="free_deliverya"></div>
			<?php
			}
			else
			{	
			?>
				<div class="free_deliverya_blank"></div>
			<?php
			}
				?>	 
				<h6 class="shelfBproddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6>
				<ul class="shelfAul">
				<li class="shelfBnormalprice">
				  <?php echo print_price($row_prod['combo_discount'],true,false)?>
				</li>
				</ul>             
				<?php
				
					show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points																
					?>
				<?php if($prod_compare_enabled)  {
				dislplayCompareButton($row_prod['product_id']);
			}
					$module_name = 'mod_product_reviews';
					if(in_array($module_name,$inlineSiteComponents))
					{
						if($row_prod['product_averagerating']>=0)
						{
						?>
							<div class="shelfB_rate">
							<?php
								display_rating($row_prod['product_averagerating']);
							?>
							</div>
						<?php
						}
					}	
				?>
			<div class="combo_select_1row" >
			<?php
			if($comb_id) // show the radio button only if combination exists
			{
			?>
			<?php /*?><a href="javascript:handle_combo_combination_selection('<?php echo $ecom_hostname?>','combosel_<?php echo $comb_id?>','combprod_map_<?php echo $prodmap_id?>','<?php echo $prodmap_id?>','<?php echo $comb_id?>')">Click to Select</a> <?php */?>
			<input style="display:none" type="radio" name="combprod_map_<?php echo $prodmap_id?>" id="combprod_map_<?php echo $prodmap_id?>" value="<?php echo $comb_id?>" <?php echo ($keepselected==true)?'checked="checked"':''?> />
			<?php
			if($keepselected==true)
			{
			$sel_img = 'selected_img.gif';
			}
			else
			{
			$sel_img = 'deselected_img.gif';
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
			</div>
		  </td>
	   </tr>
			   <?php
			// Get the combination if exists for current combo product
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
			<tr>
			<td colspan="2">
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
			<td align="left" valign="middle" class="combo_var_1row" width="40%">
			<?php 
			echo stripslashes($row_var['var_name']);
			?>
			</td>
			<td align="left" valign="middle" class="combo_var_1row">
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
			</td>
			</tr>	
			<?php			
			}
			}
			?>  
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
			<input type="hidden" class="quainput" name="qty_<?=$row_prod['product_id']?>"  value="1" />
			<?php
			}
			?>	
			   </table>
			  </td>
			</tr>
			<tr>
			<td class="mid_shelfB_btm_lf">&nbsp;</td>
			<td class="mid_shelfB_btm_mid">
				<div class="infodiv">
				</div>
			</td>
			<td class="mid_shelfB_btm_rt">&nbsp;</td>
			</tr>
			</table>
			
			</div>
			</td>
										</tr>
		
		<?php
		 break;
		 case '3row':
		 if($cur_col%2==0)
		{
		$cls ='mid_shelfA_right'; 
		}
		else
		$cls ='mid_shelfA_left'; 
		 ?>
		 <td class="<?=$cls?>" valign="top">
									<table width="100%" border="0" cellpadding="0" cellspacing="0"  class="mid_shelfA_table">
										<tr>
											<td class="mid_shelfA_top_lf">&nbsp;</td>
											<td class="mid_shelfA_top_mid_combo">
											
											<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="prod_infolink"><?php echo stripslashes($row_prod['product_name'])?></a>
											</td>
											<td class="mid_shelfA_top_rt">&nbsp;</td>
										</tr>
										<tr>
												<td colspan="3" class="mid_shelfA_mid" align="center">
											<?php
											if($row_prod['product_bulkdiscount_allowed']=='Y')
											{
											?>
												<div class="bulk_discount"></div>
											<?php
											}
											else
											{
											?>
												<div class="bulk_discount_blank"></div>
											<?php
											}
											if($row_prod['product_freedelivery']==1)
											{	
											?>
												<div class="free_delivery"></div>
											<?php
											}
											else
											{	
											?>
												<div class="free_delivery_blank"></div>
											<?php
											}	
											?>	
											<ul class="shelfAul">
																							
											<li class="shelfAimg">
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
											
											</li>
											<li >
			<?php
			if($comb_id) // show the radio button only if combination exists
			{
		?>
				<?php /*?><a href="javascript:handle_combo_combination_selection('<?php echo $ecom_hostname?>','combosel_<?php echo $comb_id?>','combprod_map_<?php echo $prodmap_id?>','<?php echo $prodmap_id?>','<?php echo $comb_id?>')">Click to Select</a> <?php */?>
				<input style="display:none" type="radio" name="combprod_map_<?php echo $prodmap_id?>" id="combprod_map_<?php echo $prodmap_id?>" value="<?php echo $comb_id?>" <?php echo ($keepselected==true)?'checked="checked"':''?> />
		<?php
				if($keepselected==true)
				{
					$sel_img = 'selected_img.gif';
				}
				else
				{
					$sel_img = 'deselected_img.gif';
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
											<?php
												$module_name = 'mod_product_reviews';
												if(in_array($module_name,$inlineSiteComponents))
												{
													if($row_prod['product_averagerating']>=0)
													{
													?>
														<li class="shelfB_rate">
														<?php
															display_rating($row_prod['product_averagerating']);
														?>
														</li>
													<?php
													}
												}	
														
											?>        
											<li class="shelfAproddesli"><h6 class="shelfAproddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6></li>                     
											<?
											
											?>
											<ul class="shelfBul">
											<li class="shelfAnormalprice">
											  <?php echo print_price($row_prod['combo_discount'],true,false)?>
											</li>
											</ul>
											<?php
												show_bonus_points_msg($row_prod,'bonus_point_1row'); // Show bonus points
											?>
											
											</ul>	
											</td>
										</tr>
			<?php
									// Get the combination if exists for current combo product
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
				<tr>
				<td colspan="3" align="center" class="mid_shelfA_mid">
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
								<td align="left" valign="middle" class="combo_var_threerow" width="40%">
									<?php 
										echo stripslash_normal($row_var['var_name']);
									?>
								</td>
								<td align="left" valign="middle" class="combo_var_threerow">
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
								</td>
							</tr>	
					<?php			
						}
					}
				?>  
				</table>
									</td></tr>
									<tr>
										<td class="mid_shelfA_btm_lf">&nbsp;</td>
										<td class="mid_shelfA_btm_mid">	<div class="infodiv_shlfA">
										</div>
								        </td>
										<td class="mid_shelfA_btm_rt">&nbsp;</td>
										</tr>
									</table>
						</td>
				
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
				
		 <?php
		 break;
		}//end of switch case
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
				$prodsort_by		= 'a.product_id';
				break;
		};
		$combosort_order		= $Settings_arr['product_orderby_combo'];
		?>
			<div class="tree_con">
			<div class="tree_top"></div>
				<div class="tree_middle">
					<div class="pro_det_treemenu">
					<ul>
					<li><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> &gt;&gt; </li>
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
							$prodname		= '<li><a href="'.url_product($combprod_id,$row_prodname['product_name'],2).'" title="'.stripslashes($row_prodname['product_name']).'">'.stripslashes($row_prodname['product_name']).'</a></li>&gt;&gt; '; 
							echo $prodname;
						}	  
					}
					if($Captions_arr['COMBO']['COMBO_BUNDLED_OFFER']!='')
					{
					?>
					<li> <?php  echo $Captions_arr['COMBO']['COMBO_BUNDLED_OFFER'];?> </li>
					<?php
					}
					?>
					</ul>
					</div>
				</div>
			<div class="tree_bottom"></div>
			</div>
			<table width="100%" border="0" cellpadding="0" cellspacing="0"  class="combotable">
		<?php
		
		// Fetch the details of combos
		$combo_cnt = 0;
		$max_combo_cnt =2;
		while ($row_combos = $db->fetch_array($ret_combos))
		{
		    $combo_cnt ++;
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
								a.price_discountprefix, a.price_discountsuffix, a.price_yousaveprefix, a.price_yousavesuffix,a.price_noprice 
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
				
				if($proceed_combo==true)
				{
				if($combo_cnt==0)
				{
				echo "<tr>";
				}
				?>
					
					<td class="combocontent" valign="top">

						<div class="rt_combodeal">
						<?php
						if ($row_combos['combo_hidename']==0)
						{
						?>	
						<div class="rt_combodeal_top"><?php echo stripslashes($row_combos['combo_name'])?></div>
						<?
						}
						?>
						<div class="rt_combodeal_middle">
			<?php		
					$cnt 	= 0;
					$maxcnt = 4; 
					$tot_prods = $db->num_rows($ret_prod);
					if($tot_prods<$maxcnt)
						$maxcnt = $tot_prods;
					while ($row_prod = $db->fetch_array($ret_prod))
					{
						$cnt++;
			?>
						<div class="rt_combodeal_img">
						<a href="<?php url_combo($row_combos['combo_id'],$row_combos['combo_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
						<?php
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
						?>
						</a></div>
						<?php if($cnt!=$totcnt && $cnt%$maxcnt!=0 && $cnt>0)
						{
						?>
							<div class="rt_combodeal_plus"></div>
						<?php 
						}
					}
			?>
							</div>
							<div class="rt_combodeal_bottom">
							<a href="<?php url_combo($row_combos['combo_id'],$row_combos['combo_name'],-1)?>"  title="<?php echo $Captions_arr['COMMON']['SHOW_DEAL']?>" class="pre-combodeal-showall"><?php echo $Captions_arr['COMMON']['SHOW_DEAL']?></a>
							</div>
							</div>
					
						<span class="bundle_price" >
						<?php  echo $Captions_arr['COMBO']['COMBO_BUNDLE_PRICE'];?> <?php echo print_price($row_combos['combo_bundleprice'],true,false);	?>
						</span>

					</td>
					
			<?php
			     if($combo_cnt>=$max_combo_cnt)
				 {
				    echo "</tr>";
					$combo_cnt=0;
				 }
				}	
		}
		    if($combo_cnt < $max_combo_cnt)
				 {
				  $combo_cnt = $max_combo_cnt - $combo_cnt;
				    echo '<td colspan="'.$combo_cnt .'"></td></tr>';
				 }
		?>
	</table>
		<?php
	}
};	
?>