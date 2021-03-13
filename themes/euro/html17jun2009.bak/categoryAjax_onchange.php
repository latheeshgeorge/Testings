<?php
	include("../../../config.php");
	include("../../../includes/price_display.php");
	include("../../../functions/functions.php");
	require("../../../includes/session.php");
	require("../../../includes/urls.php");
	
	if(file_exists($image_path.'/settings_cache/general_settings.php'))
		include "$image_path/settings_cache/general_settings.php";
	
	// Including the price display settings array file
		if(file_exists($image_path.'/settings_cache/price_display_settings.php'))
			include "$image_path/settings_cache/price_display_settings.php";	

		// Calling the function to get all the captions set in the COMMON section
		$Captions_arr['COMMON']	= getCaptions('COMMON');
	
		// Calling the function to get the details of default currency
		$default_Currency_arr	= get_default_currency();
	
		// Assigning the current currency to the variable
		$sitesel_curr			= get_session_var('SEL_CURR');
		// If sitesel_curr have no value then set it as the default currency
		if (!$sitesel_curr)
		{
			$sitesel_curr		= $default_Currency_arr['currency_id'];// setting the default currency value
		}
		// Get details of current currency
		$current_currency_details = get_current_currency_details();
	
if($_REQUEST['fpurpose']=='ajax_show_variable_price')
{
	$prod_id 	= $_REQUEST['prodid'];
	$val_id_arr	= explode('~',$_REQUEST['pass_var']);
	$var_arrs	= explode('~',$_REQUEST['pass_varid']);
	for($i=0;$i<count($var_arrs);$i++)
	{
		$var_arr[$var_arrs[$i]] = $val_id_arr[$i];
	}
	show_Variable_price_detail($prod_id,$var_arr);
}
elseif ($_REQUEST['fpurpose']=='ajax_show_bulk_discount')
{
	$prod_id 	= $_REQUEST['prodid'];
	$val_id_arr	= explode('~',$_REQUEST['pass_var']);
	$var_arrs	= explode('~',$_REQUEST['pass_varid']);
	for($i=0;$i<count($var_arrs);$i++)
	{
		$var_arr[$var_arrs[$i]] = $val_id_arr[$i];
	}
	show_BulkDiscounts_prod_detail($prod_id,$var_arr);
}
if ($_REQUEST['curval'])
{
	if($_REQUEST['page_type']=='cont_change') // handling case to change tab content using ajax
	{
		$tab_id 		= $_REQUEST['tabid'];
		$prod_id	= $_REQUEST['prodid'];
		if($prod_id)
		{
			if($tab_id>0)
			{
				$sql_tab = "SELECT tab_content 
									FROM 
										product_tabs 
									WHERE 
										tab_id = $tab_id 
										AND products_product_id = $prod_id 
									LIMIT 
										1";
				$ret_tab = $db->query($sql_tab);
				if($db->num_rows($ret_tab))
				{
					$row_tab = $db->fetch_array($ret_tab);
					echo nl2br(stripslashes($row_tab['tab_content']));
				}						
			}
			elseif($tab_id==0)
			{
				$sql_prod = "SELECT product_longdesc,product_shortdesc 
										FROM 
											products 
										WHERE 
											product_id =$prod_id  
											AND product_hide = 'N' 
										LIMIT 
											1";
				$ret_prod = $db->query($sql_prod);
				if ($db->num_rows($ret_prod))
				{
					$row_prod = $db->fetch_array($ret_prod);
					if($row_prod['product_longdesc'])
						$content	= stripslashes($row_prod['product_longdesc']);
					elseif ($row_prod['product_shortdesc'])
						$content	= stripslashes($row_prod['product_shortdesc']);
					echo nl2br(stripslashes($content));	
				}		
			}
		}	
	}
	 elseif($_REQUEST['page_type'] == 'cat') // case of showing subcategories
	 {

		// Check whether subcategories exists under current product
		$sql_subcat = "SELECT category_id ,category_name 
								FROM 
									product_categories 
								WHERE 
									sites_site_id = $ecom_siteid 
									AND parent_id = ".$_REQUEST['curval']." 
									AND category_hide = 'N'  
								ORDER BY 
									category_order ASC";
		$ret_subcat = $db->query($sql_subcat);
		if ($db->num_rows($ret_subcat))
		{
			$t = $_REQUEST['counter']; $t++; 
			$show_desc = '';
			//Get the short description of parent category
			$sql_pcat = "SELECT category_shortdescription 
									FROM 
										product_categories 
									WHERE 
										sites_site_id = $ecom_siteid 
										AND category_id =".$_REQUEST['curval']."  
										AND category_hide = 'N' 
									LIMIT 
										1";
			$ret_pcat = $db->query($sql_pcat);
			if($db->num_rows($ret_pcat))
			{
				$row_pcat = $db->fetch_array($ret_pcat);
				$show_desc_arr = explode('~',stripslashes($row_pcat['category_shortdescription']));
				if(count($show_desc_arr)>1)
				{
					$show_desc = $show_desc_arr[1];
				}
				else
					$show_desc = $show_desc_arr[0];
			}							
		?>
			<div class="pro_ajx" >
			<div class="pro_ajx_hdr">
			<div class="pro_ajx_stplt">Step <?php echo $t?></div>
			<div class="pro_ajx_stptxt"><?php echo $show_desc?></div>
			</div>
			<div class="pro_ajx_cont">
				<select name="first_cat_<?=$_REQUEST['counter']?>" id="first_cat_<?=$_REQUEST['counter']?>" onChange="handle_div(this,<?=$_REQUEST['counter']?>,'cat')" >
					<option value="">--Select --</option>
				<?php
				while($row_subcat = $db->fetch_array($ret_subcat)) 
				{
				?>
					<option value="<?=$row_subcat['category_id']?>"><?=$row_subcat['category_name']?></option>
				<?
				}
				?>
				</select>
			</div>
			</div>
		<div id="<?=$t?>" class="pro_ajx_cont_sub"></div>
	
		<?php
	}
	 else // case of showing products select box
	 {
	 		$show_desc = '';
			//Get the short description of parent category
			$sql_pcat = "SELECT category_shortdescription,product_orderfield ,product_orderby
									FROM 
										product_categories 
									WHERE 
										sites_site_id = $ecom_siteid 
										AND category_id =".$_REQUEST['curval']." 
										AND category_hide = 'N' 
									LIMIT 
										1";
			$ret_pcat = $db->query($sql_pcat);
			if($db->num_rows($ret_pcat))
			{
				$row_pcat = $db->fetch_array($ret_pcat);
				$show_desc_arr = explode('~',stripslashes($row_pcat['category_shortdescription']));
				if(count($show_desc_arr)>1)
				{
					$show_desc = $show_desc_arr[1];
				}
				else
					$show_desc = $show_desc_arr[0];
				
				if(trim($row_pcat['product_orderfield'])!='')
				{
					$def_orderfield 	= $row_pcat['product_orderfield'];
					$def_orderby		= $row_pcat['product_orderby'];
				}
				else
				{
					$def_orderfield 	= 'product_name';
					$def_orderby		= 'ASC';
				}	
				switch ($def_orderfield)
				{
					case 'custom': // case of order by customer fiekd
					$def_orderfield		= 'b.product_order';
					break;
					case 'product_name': // case of order by product name
					$def_orderfield		= 'a.product_name';
					break;
					case 'price': // case of order by price
					$def_orderfield		= 'a.product_webprice';
					break;
					case 'product_id': // case of order by price
					$def_orderfield		= 'a.product_id';
					break;
					default: // by default order by product name
					$def_orderfield		= 'a.product_name';
					break;
				};
			}	
		
		// Check whether products exists under current category
		$sql_prod = "SELECT a.product_id,a.product_name 
								FROM 
									products a, product_category_map b 
								WHERE 
									a.sites_site_id = $ecom_siteid 
									AND a.product_id = b.products_product_id 
									AND b.product_categories_category_id = ".$_REQUEST['curval']." 
									AND a.product_hide = 'N' 
								ORDER BY 
									$def_orderfield $def_orderby ";
		$ret_prod = $db->query($sql_prod);
		if ($db->num_rows($ret_prod))
		{
			
			$t = $_REQUEST['counter'];
			$t++; 
		?>
		
			<div class="pro_ajx" >
			<div class="pro_ajx_hdr">
			<div class="pro_ajx_stplt">Step <?php echo $t?></div>
			<div class="pro_ajx_stptxt"><?php echo $show_desc?></div>
			</div>
			<div class="pro_ajx_cont">
				<select name="product_id" id="product_id" onchange="handle_div(this,<?=$_REQUEST['counter']?>,'pdt')">
					<option value="">--Select --</option>
				<?php
				while($row_prod = $db->fetch_array($ret_prod)) 
				{
				?>
					<option value="<?=$row_prod['product_id']?>"><?=$row_prod['product_name']?></option>
				<?
				}
				?>
				</select>
			</div>
			</div>
			<div id="<?=$t?>" class="pro_ajx_cont_sub"></div>
	<?php
		}
		else // case if no products exists
		{
		?>
		<div class="pro_ajx" >
		<div class="pro_ajx_hdr">
			<div class="pro_ajx_noprods">
			Sorry no products under this category
			</div>
		</div>
		</div>
		<?php	
		}
	}
	} 
	else
	{
		$pass_product_id = $_REQUEST['curval'];
		$sql_prod = "SELECT * 
								FROM 
									products 
								WHERE 
									product_id =$pass_product_id 
									AND product_hide = 'N' 
								LIMIT 
									1";
		$ret_prod = $db->query($sql_prod);		
		$row_prod = $db->fetch_array($ret_prod);	
		$Captions_arr['PROD_DETAILS'] 	= getCaptions('PROD_DETAILS');
		// Including the general settings array file
		if(file_exists($image_path.'/settings_cache/general_settings.php'))
			include "$image_path/settings_cache/general_settings.php";
			
		// Including the price display settings array file
		if(file_exists($image_path.'/settings_cache/price_display_settings.php'))
			include "$image_path/settings_cache/price_display_settings.php";	
	
		$ecom_common_settings 			= get_Common_Settings();
		$ecom_tax_total_arr 					= $ecom_common_settings['tax'];// // Calling the function to get the tax for the current site
		$Captions_arr['COMMON']				= getCaptions('COMMON');
		// Get the captions for price
		$Captions_arr['PRICE_DISPLAY']	= getCaptions('PRICE_DISPLAY');
	
		// Calling the function to get the details of default currency
		$default_Currency_arr					= get_default_currency();
	
		// Assigning the current currency to the variable
		$sitesel_curr								= get_session_var('SITE_CURR');
		
		if($_REQUEST['cbo_selcurrency'])
		{
			$sitesel_curr = $_REQUEST['cbo_selcurrency'];
			set_session_var('SITE_CURR',$sitesel_curr);
			//Finding the symbol for current currency
			$sql_curr  	= "SELECT curr_sign_char FROM general_settings_site_currency WHERE currency_id = $sitesel_curr";
			$ret_curr	= $db->query($sql_curr);
			if($db->num_rows($ret_curr))
			{
				$row_curr  			= $db->fetch_array($ret_curr);
				$sitesel_currsign 		= $row_curr['curr_sign_char'];
			}
			set_session_var('SITE_CURR_SIGN',$sitesel_currsign);
		}
		// If sitesel_curr have no value then set it as the default currency
		if (!$sitesel_curr)
		{
			$sitesel_curr		= $default_Currency_arr['currency_id'];// setting the default currency value
		}
		// Get details of current currency
		$current_currency_details = get_current_currency_details();
		echo '
			<input type="hidden" name="fpurpose" value="" />
			<input type="hidden" name="fproduct_id" value="'.$pass_product_id.'" />
			<input type="hidden" name="pass_url" value="" />
			<input type="hidden" name="pass_combid" value="" />
		';
		?>
		<div class="ajx_det_main" >
			<div class="ajx_det_main_tp"></div>
		   <div class="ajx_det_main_mid">
		   <div class="ajx_det_image">
			<?php
						$ret_arr 				=	Show_Image_Normal($row_prod);
						$exclude_tabid		= $ret_arr['exclude_tabid'];
						$exclude_prodid		= $ret_arr['exclude_prodid'];
						$imgs_main_arr = explode('big/',$ret_arr['img_det']);
						if ($ret_arr['img_det']=='')
							$price_det_class = 'ajx_det_price_no_img';
						else
							$price_det_class = 'ajx_det_price';
						
					   show_more_images($row_prod,$exclude_tabid,$exclude_prodid);
			?>
			</div>
			<div class="<?php echo $price_det_class?>">
			<?php
					echo '<div id="price_holder">';
					$price_class_arr['ul_class'] 			= 'ajx_picr_ul';
					$price_class_arr['normal_class'] 		= 'ajx_normalprice';
					$price_class_arr['strike_class'] 		= 'ajx_strikeprice';
					$price_class_arr['yousave_class'] 	= 'ajx_yousaveprice';
					$price_class_arr['discount_class'] 	= 'ajx_discountprice';				
					echo show_Price($row_prod,$price_class_arr,'prod_detail');	
					echo '</div>';
					show_ProductVariables($row_prod,'column');
					echo '<div id="bulkdisc_holder" style="display:block; clear:both">';
					show_BulkDiscounts($row_prod['product_id']);
					echo '</div>';
					show_buttons($row_prod);
			?>
			</div>
		   </div>
		   

		   <div class="ajx_det_main_btm"></div>
		   </div>
		   <?php
		   show_Downloads($row_prod['product_id']);
		   	//Check whether tab exists for current product
			$sql_tab = "SELECT tab_id,tab_title 
								FROM 
									product_tabs 
								WHERE 
									products_product_id = ".$pass_product_id."
									AND tab_hide=0 
								ORDER BY 
									tab_order";
			$ret_tab = $db->query($sql_tab);
			if($row_prod['product_longdesc'])
				$content	= stripslashes($row_prod['product_longdesc']);
			elseif ($row_prod['product_shortdesc'])
				$content	= stripslashes($row_prod['product_shortdesc']);
			if((trim($content)!='' and trim($content)!='<br>') or $db->num_rows($ret_tab)>0)
			{
		   ?>
			<div class="ajx_det_main" >
			<div class="ajx_det_main_tp"></div>
				<div class="ajx_des_mid">
					<div class="ajx_det_tab" >
						<div class="protabcontainer">
						<ul class="protab">
						<li class="selectedtab" id="tabid_0" onclick="handle_ajax_desc('0','<?php echo $row_prod['product_id']?>')">Overview</li>
						<?php
						$cur_tab_ids = 0;
							if($db->num_rows($ret_tab))
							{
								while ($row_tab = $db->fetch_array($ret_tab))
								{
									$cur_tab_ids .= '~'.$row_tab['tab_id'];
						?>	
									<li id="tabid_<?php echo $row_tab['tab_id']?>" onclick="handle_ajax_desc('<?php echo $row_tab['tab_id']?>','<?php echo $row_prod['product_id']?>')"><?php echo stripslashes($row_tab['tab_title'])?></li>
						<?php
								}
							}
						?>
						</ul>
						</div>
						<div class="ajx_det_cont" id="proddet_maincontent"><?php echo nl2br(stripslashes($content))?>
						</div>
						<input type="hidden" name="hold_tab_ids" id="hold_tab_ids" value="<?php echo $cur_tab_ids?>" />
					</div>
				</div>
			<div class="ajx_det_main_btm"></div>
			</div>
			<input type="hidden" name="ajax_pass_prod_id" id="ajax_pass_prod_id" value="<?php echo $row_prod['product_id']?>" />
	<?php	
		}
	}
}
function show_ProductVariables($row_prod,$pos='column')
{
	global $db,$ecom_siteid,$Captions_arr;
	$i = 0;
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
	$var_cnt = $db->num_rows($ret_var);
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
	// Check whether total number of variables is 1 or more than 1
	if($var_cnt==1)
	{
		$vardisp_type = $row_prod['product_variable_display_type']; // take the display type from settings for current product
	}
	else 
		$vardisp_type = 'ADD'; // if the variable count is > 1 then by default the Add option will be displayed
	
	if ($db->num_rows($ret_var) or $db->num_rows($ret_msg))
	{
	?>
	 <div class="ajx_var_tp"></div>
	<?php
				// Case of variables
				if ($db->num_rows($ret_var))
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
							$clss = ($i%2==0)?'productvariabletdA':'productvariabletdB';
							$var_exists = true;
				?>
						<div class="ajx_var_mid">	
						  <div><?php echo stripslashes($row_var['var_name'])?></div>
						  <div>
								<?php
								if ($row_var['var_value_exists']==1)
								{
									if($row_prod['product_variablecomboprice_allowed']=='Y')
									{
										$onchange_function = " onchange ='handle_show_prod_det_bulk_disc(\"price\")' ";
									}
									else
									{
										$onchange_function = '';
									}
								?>
										<select name="var_<?php echo $row_var['var_id']?>" <?php echo $onchange_function?>>
										<?php 
											while ($row_vals = $db->fetch_array($ret_vals))
											{
										?>
												<option value="<?php echo $row_vals['var_value_id']?>" <?php echo ($_REQUEST['var_'.$row_var['var_id']]==$row_vals['var_value_id'])?'selected':''?>><?php echo stripslashes($row_vals['var_value'])?><?php echo Show_Variable_Additional_Price($row_prod,$row_vals['var_addprice'],$vardisp_type)?></option>
										<?php
											}
										?>
										</select>							
								<?php
								}
								else
								{
								?>
									<input type="checkbox" name="var_<?php echo $row_var['var_id']?>" id="var_<?php echo $row_var['var_id']?>" value="1" <?php echo ($_REQUEST['var_'.$row_var['var_id']])?'checked="checked"':''?>/><?php echo Show_Variable_Additional_Price($row_prod,$row_var['var_price'],$vardisp_type)?>
								<?php
								}
								?>
							</div>
							</div>
				<?php
							$i++;
						}
						
					}
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
							$clss = ($i%2==0)?'productvariabletdA':'productvariabletdB';
							$var_exists = true;
					?>
								<div class="ajx_var_mid">
							  <div><?php echo stripslashes($row_msg['message_title'])?></div>
								<div>
									<?php
									if ($row_msg['message_type']=='TXTBX')
									{
									?>
										<input type="text" name="varmsg_<?php echo $row_msg['message_id']?>" id="varmsg_<?php echo $row_msg['message_id']?>" value="<?php echo $_REQUEST['varmsg_'.$row_msg['message_id']]?>" />
									<?php
									}
									else
									{
									?>
										<textarea name="varmsg_<?php echo $row_msg['message_id']?>" id="varmsg_<?php echo $row_msg['message_id']?>" rows="3" cols="15"><?php echo $_REQUEST['varmsg_'.$row_msg['message_id']]?></textarea>
									<?php
									}
									?>
								</div>
								</div>
								
					<?php
							$i++;
						}
				}
				// ######################################################
				// End of variable messages
				// ######################################################
		?>
		 <div class="ajx_var_btm">
		</div>		
		<?php
	}
	return $var_exists;
}

function show_buttons($row_prod)
{
	global $Captions_arr,$showqty,$Settings_arr;
	$cust_id 	= get_session_var("ecom_login_customer");
	$showqty	= $Settings_arr['show_qty_box'];// show the qty box
	$cur_qty_caption = ($row_prod['product_det_qty_caption']!='')?stripslashes($row_prod['product_det_qty_caption']):$Captions_arr['PROD_DETAILS']['PRODDET_QUANTITY'];
	?>
	<div class="ajx_buy">
	<?php
	if($showqty==1)// this decision is made in the main shop settings
	{
		if($row_prod['product_det_qty_type']=='NOR')
		{
?>
	  	<div class="quantity_details"><?php echo $cur_qty_caption?><input type="text" class="quainput_det" name="qty"  value="<?php echo ($_REQUEST['qty'])?$_REQUEST['qty']:1?>" /></div>
<?php
		}
		elseif($row_prod['product_det_qty_type']=='DROP')
		{
			$dropdown_values = explode(',',stripslashes($row_prod['product_det_qty_drop_values']));
			if (count($dropdown_values) and stripslashes($row_prod['product_det_qty_drop_values'])!='')
			{
?>
				<div class="quantity_details"><?php echo $cur_qty_caption ?>
				<select name="qty">
				<?php 
					$qty_prefix = stripslashes($row_prod['product_det_qty_drop_prefix']);
					$qty_suffix = stripslashes($row_prod['product_det_qty_drop_suffix']);
					foreach ($dropdown_values as $k=>$v)
					{
						if(is_numeric(trim($v)))
						{
				?>
						<option value="<?php echo trim($v)?>"><?php echo $qty_prefix.' '.trim($v).' '.$qty_suffix?></option>
				<?php
						}	
					}
				?>
				</select>
				</div>
			<?php	
			}				
		}
	}
	// Get the caption to be show in the button
	$caption_key = show_addtocart($row_prod,array(0),'',true);
	if ($caption_key!='') // Check whether the buynow or preorder button is to be displayed
	{
?>
		<input name="Submit_buy" type="submit" class="ajx_buy_btn" id="Submit_buy" value="<?php echo $Captions_arr['PROD_DETAILS'][$caption_key];?>" onClick="show_wait_button(this,'Please Wait...');document.frm_proddetails.fpurpose.value='Prod_Addcart'" />
<?php
	}
	// Check whether the enquire link is to be displayed
	if ($row_prod['product_show_enquirelink']==1)
	{
?>			
		<input name="Submit_enq" type="submit" class="ajx_buy_btn" id="Submit_enq" value="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_ENQUIRY'];?>" onClick="show_wait_button(this,'Please Wait...');document.frm_proddetails.fpurpose.value='Prod_Enquire'" />
<?php
	}
if($cust_id) // ** Show the wishlist button only if logged in 
{
?>
	<input name="submit_wishlist" type="submit" class="ajx_buy_btn" id="submit_wishlist" value="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_ADDWISHLIST'];?>"  onclick="show_wait_button(this,'Please Wait...');document.frm_proddetails.fpurpose.value='Prod_Addwishlist'"  />
<?php
}	
?>
</div>
<?php	
	return true;
}
function Show_Image_Normal($row_prod)
{
	global $db,$ecomn_siteid,$ecom_hostname,$ecom_themename;	
		$show_normalimage = true;
		if ($show_normalimage)
		{
			if ($_REQUEST['prodimgdet'])	
				$showonly = $_REQUEST['prodimgdet'];
			else
				$showonly = 0;
			// Calling the function to get the type of image to shown for current 
			$pass_type = get_default_imagetype('proddet');
			// Calling the function to get the image to be shown
			$prodimg_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,0,$showonly,1);
			if(count($prodimg_arr))
			{
			?>
				
			<?php
				$exclude_prodid = $prodimg_arr[0]['image_id']; 		// exclude id in case of multi images for products
				/*<a href="javascript:showImagePopup('<?php echo $prodimg_arr[0]['image_extralargepath']?>','<?php echo $ecom_hostname?>','<?php echo $ecom_themename?>')"  title="Click to Zoom">*/
				
				show_image(url_root_image($prodimg_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name'],'','main_det_img');
				
				$show_noimage 	= false;
				$imgs_main_arr = explode('big/',$prodimg_arr[0][$pass_type]);
			?>	
				<input type="hidden" name="main_img_hold_var" id="main_img_hold_var" value="<?php echo $imgs_main_arr[1]?>" />

			<?php
			}
			else
			{	
				// calling the function to get the default no image 
				$no_img = get_noimage('prod','big'); 
				if ($no_img)
				{
					//show_image($no_img,$row_prod['product_name'],$row_prod['product_name']);
				}	
			}
		}
		$ret_arr['exclude_tabid']		= $exclude_tabid;
		$ret_arr['exclude_prodid'] 	= $exclude_prodid;
		$ret_arr['img_det']				= $prodimg_arr[0]['image_bigpath'];
		return $ret_arr;
}
function show_more_images($row_prod,$exclude_tabid,$exclude_prodid)
{
		global $db,$ecom_hostname,$ecom_themename;
		$show_normalimage = false;
		$prodimg_arr		= array();
		$pass_type = 'image_iconpath';
		$show_normalimage = true;
		 if ($show_normalimage==true) // the following is to be done only coming for normal image display
		 {
			if ($exclude_prodid)
				$prodimg_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type.',image_thumbpath',$exclude_prodid,0);

		 } 
			if(count($prodimg_arr)>0)// Case if more than one image assigned to current product
			{
		?>	
		<div class="ajx_det_main_midA">
			  <table width="100%" border="0" cellpadding="0" cellspacing="3" class="productdethumbtable">
				<tr>
				<td>
				<ul class="hoverbox">
				<?php
				if ($pass_type=='image_thumbpath') // If the more image type is Thumb then show 3 in a row otherwise show 2 in a row
				{
					$maximg_col 	=1;
					$width				= '100%';
				}	
				else
				{
					$maximg_col = 3;
					$width			= '45px';
				}	
				$curimg_col = 0;
				$tabstr		= ($_REQUEST['prod_curtab'])?'&amp;prod_curtab='.$_REQUEST['prod_curtab']:'';
				$img_det = '';
				$i=1;
				foreach ($prodimg_arr as $k=>$v)
				{
					$title = ($v['image_title'])?stripslashes($v['image_title']):$row_prod['product_name'];
					if ($img_det!='')
						$img_det .= '~';
						$imgs_arr = explode('icon/',$v['image_iconpath']);
					$img_det .= $imgs_arr[1];
					$cur_moreimg_id = 'moreid_'.$i;
				?>
					<li>
					<a href="javascript:handle_image_swap('<?php echo $i?>')" title="<?=$title?>" id="<?php $imid?>">
					<?php
						 show_image(url_root_image($v['image_iconpath'],1),$title,$title,'preview',$cur_moreimg_id);
	 					$i++;
					?>
					</a>
					</li>
				<?php
				}
				?>	
				</ul>
				<input type="hidden" name="more_img_hold_var" id="more_img_hold_var" value="<?php echo $img_det?>" />
				</td>
				</tr>
			  </table>
			  </div>
	  <?php
		}
	}
	
	function show_BulkDiscounts($prod_id)
	{
		global $db,$ecom_siteid,$Captions_arr;
		$Captions_arr['PROD_DETAILS'] 	= getCaptions('PROD_DETAILS');
		$sql_prod = "SELECT product_id, product_variablecomboprice_allowed 
							FROM 
								products 
							WHERE 
								sites_site_id = $ecom_siteid 
								AND product_id = $prod_id 
							LIMIT 
								1";
		$ret_prod = $db->query($sql_prod);
		if ($db->num_rows($ret_prod))
		{
			$row_prod = $db->fetch_array($ret_prod);
		}					
		$var_arr = array();	
			if (count($var_arr)==0 and $row_prod['product_variablecomboprice_allowed']=='Y') // case if variable combination price is allowed and also if var arr is null
			{
				$sql_var = "SELECT var_id,var_name  
								FROM 
									product_variables 
								WHERE 
									products_product_id = ".$row_prod['product_id']." 
									AND var_hide= 0 
									AND var_value_exists = 1 
								ORDER BY 
									var_order";
				$ret_var = $db->query($sql_var);
				if($db->num_rows($ret_var))
				{
					while ($row_var = $db->fetch_array($ret_var))
					{
						$curvar_id= $row_var['var_id'];
						// Get the value id of first value for this variable
						$sql_data = "SELECT var_value_id 
												FROM 
													product_variable_data 
												WHERE 
													product_variables_var_id = ".$curvar_id." 
												ORDER BY var_order  
												LIMIT 
													1";
						$ret_data = $db->query($sql_data);
						if ($db->num_rows($ret_data))
						{
							$row_data = $db->fetch_array($ret_data);
						}							
						$var_arr[$curvar_id] = $row_data['var_value_id'];
					}
				}
			}
			// Section to show the bulk discount details
			$comb_arr = get_combination_id($row_prod['product_id'],$var_arr);
			$bulkdisc_details = product_BulkDiscount_Details($row_prod['product_id'],$comb_arr['combid']);
		if (count($bulkdisc_details['qty']))
		{
		?>	
			<div class="bulk_discount_button_div">
				<input type="button" name="bulkdisc" value="View Bulk Discount" onclick="handle_bulk_disc('bulk_discount_deta_div')" class="ajx_bulk_btn" />
			</div>
			<div class="bulk_discount_detail_div" id="bulk_discount_deta_div" style="display:none; overflow:auto">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bulkdiscounttable">
			  <tr>
				<td align="left" class="bulkdiscountdetailheader"><?php echo $Captions_arr['PROD_DETAILS']['BULK_DISC_HEAD']?></td>
			  </tr>
			  <?php
				for($i=0;$i<count($bulkdisc_details['qty']);$i++)
				{
				?>	
				   <tr>
					<td class="bulkdiscountdetailcontent" align="left"><?php echo $bulkdisc_details['qty'][$i].' '.$Captions_arr['PROD_DETAILS']['BULK_FOR'].' '?> 
						<?php echo print_price($bulkdisc_details['price'][$i]).' '.$Captions_arr['PROD_DETAILS']['BULK_EACH']?>
					</td>
				  </tr>
			  <?php
				}
			  ?>
			</table>
			</div>
		<?php
		}
	}
	function show_Downloads($prod_id)
	{
		global $db,$ecom_siteid,$Captions_arr,$ecom_hostname;
		// Check whether any downloads exists for current product
				$sql_attach = "SELECT * 
								FROM 
									product_attachments 
								WHERE 
									products_product_id = ".$prod_id."
									AND attachment_hide=0 
								LIMIT 
									1";
				$ret_attach = $db->query($sql_attach);
				if ($db->num_rows($ret_attach))
				{
					$Captions_arr['PROD_DETAILS'] 	= getCaptions('PROD_DETAILS');
			?>
           <div class="pro_det_dwn" >
	       <div class="pro_det_dwn_tp"></div>
			<div class="pro_det_dwn_hdr">
	   <table width="100%" border="0" cellspacing="0" cellpadding="0" class="productdownloadtable">
                <tr>
                  <td class="productdownloadheader"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_DOWNLOADS'];?></td>
                </tr>
                <tr>
                  <td><ul class="downloadul">
                      <?php
								// Get the list of video attachments
								$sql_video = "SELECT * 
												FROM 
													product_attachments 
												WHERE 
													products_product_id = ".$prod_id."
													AND attachment_hide=0 
													AND attachment_type='Video' 
												ORDER BY 
													attachment_order";
								$ret_video = $db->query($sql_video);
								if ($db->num_rows($ret_video))
								{
								?>
                      <li class="video">
                        <?php //echo $Captions_arr['PROD_DETAILS']['PRODDET_VIDEO'];?>
                        <ul  class="sub">
                          <?php	
								$cnts = 1;
								while ($row_video = $db->fetch_array($ret_video))
								{
								?>
                          <li><a class="downloadlink" href="http://<?php echo $ecom_hostname?>/download.php?attach_id=<?php echo $row_video['attachment_id']?>" title="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_CLICKDOWNLOAD'];?>"><?php echo $cnts++?>. <?php echo stripslashes($row_video['attachment_title'])?></a></li>
                          <?php		
								}
								?>
                        </ul>
                      </li>
                    <?php
								}
								// Get the list of audio attachments
								$sql_audio = "SELECT * 
												FROM 
													product_attachments 
												WHERE 
													products_product_id = ".$prod_id."
													AND attachment_hide=0 
													AND attachment_type='Audio' 
												ORDER BY 
													attachment_order";
								$ret_audio = $db->query($sql_audio);
								if ($db->num_rows($ret_audio))
								{
								?>
                      <li class="audio">
                        <?php //echo $Captions_arr['PROD_DETAILS']['PRODDET_AUDIO'];?>
                        <ul  class="sub">
                          <?php	
									$cnts = 1;
								while ($row_audio = $db->fetch_array($ret_audio))
								{
								?>
                          <li><a href="http://<?php echo $ecom_hostname?>/download.php?attach_id=<?php echo $row_audio['attachment_id']?>" class="downloadlink" title="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_CLICKDOWNLOAD'];?>"><?php echo $cnts++?>. <?php echo stripslashes($row_audio['attachment_title'])?></a></li>
                          <?php		
								}
								?>
                        </ul>
                      </li>
                    <?php
								}
								// Get the list of pdf attachments
								$sql_pdf = "SELECT * 
												FROM 
													product_attachments 
												WHERE 
													products_product_id = ".$prod_id."
													AND attachment_hide=0 
													AND attachment_type='Pdf' 
												ORDER BY 
													attachment_order";
								$ret_pdf = $db->query($sql_pdf);
								if ($db->num_rows($ret_pdf))
								{
								?>
                      <li class="pdf">
                        <?php //echo $Captions_arr['PROD_DETAILS']['PRODDET_PDF'];?>
                        <ul  class="sub">
                          <?php	
									$cnts = 1;
								while ($row_pdf = $db->fetch_array($ret_pdf))
								{
								?>
                          <li><a href="http://<?php echo $ecom_hostname?>/download.php?attach_id=<?php echo $row_pdf['attachment_id']?>" class="downloadlink" title="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_CLICKDOWNLOAD'];?>"><?php echo $cnts++?>. </a><a href="http://<?php echo $ecom_hostname?>/download.php?attach_id=<?php echo $row_pdf['attachment_id']?>" class="downloadlink" title="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_CLICKDOWNLOAD'];?>"><?php echo stripslashes($row_pdf['attachment_title'])?></a></li>
                          <?php		
								}
								?>
                        </ul>
                      </li>
                    <?php
								}
								// Get the list of other attachments
								$sql_other = "SELECT * 
												FROM 
													product_attachments 
												WHERE 
													products_product_id = ".$prod_id."
													AND attachment_hide=0 
													AND attachment_type='Other' 
												ORDER BY 
													attachment_order";
								$ret_other = $db->query($sql_other);
								if ($db->num_rows($ret_other))
								{
								?>
                      <li class="others">
                        <?php //echo $Captions_arr['PROD_DETAILS']['PRODDET_OTHER'];?>
                        <ul  class="sub">
                          <?php	
									$cnts = 1;
								while ($row_other = $db->fetch_array($ret_other))
								{
								?>
                          <li><a href="http://<?php echo $ecom_hostname?>/download.php?attach_id=<?php echo $row_other['attachment_id']?>" class="downloadlink" title="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_CLICKDOWNLOAD'];?>"><?php echo $cnts++?>. <?php echo stripslashes($row_other['attachment_title'])?></a></li>
                          <?php		
								}
								?>
                        </ul>
                      </li>
                    <?php
								}
								?>
                  </ul></td>
                </tr>
            </table>
			 </div>
	       <div class="pro_det_dwn_btm"></div>
    	   </div>
          <?php
				}
	}
	function show_Variable_price_detail($product_id,$var_arr=array())
	{
		global $db,$ecom_siteid,$Captions_arr;
		$Captions_arr['PROD_DETAILS'] 	= getCaptions('PROD_DETAILS');
		$sql_prod = "SELECT product_id,product_name,product_variablestock_allowed,product_show_cartlink,product_show_enquirelink,
											product_preorder_allowed,product_show_enquirelink,product_webstock,product_webprice,
											product_discount,product_discount_enteredasval,product_bulkdiscount_allowed,
											product_total_preorder_allowed,product_applytax,product_shortdesc,product_longdesc,
											product_averagerating,product_deposit,product_deposit_message,product_bonuspoints,
											product_variable_display_type,product_variable_in_newrow,productdetail_moreimages_showimagetype,
											product_default_category_id,product_details_image_type,product_flv_filename,product_stock_notification_required,
											product_alloworder_notinstock,product_variables_exists,product_variablesaddonprice_exists,
											product_variablecomboprice_allowed  
							FROM 
								products 
							WHERE 
								sites_site_id = $ecom_siteid 
								AND product_id = $product_id 
							LIMIT 
								1";
		$ret_prod = $db->query($sql_prod);
		if ($db->num_rows($ret_prod))
		{
			$row_prod = $db->fetch_array($ret_prod);
		}						
			if (count($var_arr)==0 and $row_prod['product_variablecomboprice_allowed']=='Y') // case if variable combination price is allowed and also if var arr is null
			{
				$sql_var = "SELECT var_id,var_name  
								FROM 
									product_variables 
								WHERE 
									products_product_id = ".$row_prod['product_id']." 
									AND var_hide= 0 
									AND var_value_exists = 1 
								ORDER BY 
									var_order";
				$ret_var = $db->query($sql_var);
				if($db->num_rows($ret_var))
				{
					while ($row_var = $db->fetch_array($ret_var))
					{
						$curvar_id= $row_var['var_id'];
						// Get the value id of first value for this variable
						$sql_data = "SELECT var_value_id 
												FROM 
													product_variable_data 
												WHERE 
													product_variables_var_id = ".$curvar_id." 
												ORDER BY var_order  
												LIMIT 
													1";
						$ret_data = $db->query($sql_data);
						if ($db->num_rows($ret_data))
						{
							$row_data = $db->fetch_array($ret_data);
						}							
						$var_arr[$curvar_id] = $row_data['var_value_id'];
					}
				}
			}
			// Section to show the bulk discount details
			$comb_arr = get_combination_id($row_prod['product_id'],$var_arr);
			$row_prod['combination_id'] 		= $comb_arr['combid']; // this done to handle the case of showing the variables price in show price
			$row_prod['check_comb_price'] 	= 'YES';// this done to handle the case of showing the variables price in show price
			$price_class_arr['ul_class'] 			= 'ajx_picr_ul';
			$price_class_arr['normal_class'] 		= 'ajx_normalprice';
			$price_class_arr['strike_class'] 		= 'ajx_strikeprice';
			$price_class_arr['yousave_class'] 	= 'ajx_yousaveprice';
			$price_class_arr['discount_class'] 	= 'ajx_discountprice';		
			echo show_Price($row_prod,$price_class_arr,'prod_detail');
		}
	function show_BulkDiscounts_prod_detail($product_id,$var_arr=array())
	{
		global $db,$ecom_siteid,$Captions_arr;
		$Captions_arr['PROD_DETAILS'] 	= getCaptions('PROD_DETAILS');
		$sql_prod = "SELECT product_id, product_variablecomboprice_allowed 
							FROM 
								products 
							WHERE 
								sites_site_id = $ecom_siteid 
								AND product_id = $product_id 
							LIMIT 
								1";
		$ret_prod = $db->query($sql_prod);
		if ($db->num_rows($ret_prod))
		{
			$row_prod = $db->fetch_array($ret_prod);
		}						
			if (count($var_arr)==0 and $row_prod['product_variablecomboprice_allowed']=='Y') // case if variable combination price is allowed and also if var arr is null
			{
				$sql_var = "SELECT var_id,var_name  
								FROM 
									product_variables 
								WHERE 
									products_product_id = ".$row_prod['product_id']." 
									AND var_hide= 0 
									AND var_value_exists = 1 
								ORDER BY 
									var_order";
				$ret_var = $db->query($sql_var);
				if($db->num_rows($ret_var))
				{
					while ($row_var = $db->fetch_array($ret_var))
					{
						$curvar_id= $row_var['var_id'];
						// Get the value id of first value for this variable
						$sql_data = "SELECT var_value_id 
												FROM 
													product_variable_data 
												WHERE 
													product_variables_var_id = ".$curvar_id." 
												ORDER BY var_order  
												LIMIT 
													1";
						$ret_data = $db->query($sql_data);
						if ($db->num_rows($ret_data))
						{
							$row_data = $db->fetch_array($ret_data);
						}							
						$var_arr[$curvar_id] = $row_data['var_value_id'];
					}
				}
			}
			// Section to show the bulk discount details
			$comb_arr = get_combination_id($row_prod['product_id'],$var_arr);
			$bulkdisc_details = product_BulkDiscount_Details($row_prod['product_id'],$comb_arr['combid']);
			if (count($bulkdisc_details['qty']))
			{
			?>	
				<div class="bulk_discount_button_div">
				<input type="button" name="bulkdisc" value="View Bulk Discount" onclick="handle_bulk_disc('bulk_discount_deta_div')" class="ajx_bulk_btn" />
				</div>
				<div class="bulk_discount_detail_div" id="bulk_discount_deta_div" style="display:none; overflow:auto">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bulkdiscounttable">
					<tr>
					<td align="left" class="bulkdiscountdetailheader"><?php echo $Captions_arr['PROD_DETAILS']['BULK_DISC_HEAD']?></td>
					</tr>
					<?php
					for($i=0;$i<count($bulkdisc_details['qty']);$i++)
					{
					?>	
					<tr>
					<td class="bulkdiscountdetailcontent" align="left"><?php echo $bulkdisc_details['qty'][$i].' '.$Captions_arr['PROD_DETAILS']['BULK_FOR'].' '?> 
						<?php echo print_price($bulkdisc_details['price'][$i]).' '.$Captions_arr['PROD_DETAILS']['BULK_EACH']?>
					</td>
					</tr>
					<?php
					}
					?>
					</table>
			</div>
			<?php
			}
	}
?>