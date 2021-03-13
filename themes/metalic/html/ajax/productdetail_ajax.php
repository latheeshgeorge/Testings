<?php
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
				<div class="pro_bulk_div"><ul>
				<li><?php echo $Captions_arr['PROD_DETAILS']['BULK_DISC_HEAD']?></li>
				  <?php
					for($i=0;$i<count($bulkdisc_details['qty']);$i++)
					{
					?>	
					   
                        <li><?php echo $bulkdisc_details['qty'][$i].' '.$Captions_arr['PROD_DETAILS']['BULK_FOR'].' '?> 
							<?php echo print_price($bulkdisc_details['price'][$i]).' '.$Captions_arr['PROD_DETAILS']['BULK_EACH']?>
							</li>
				  <?php
					}
				  ?></ul>
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
								product_variablecomboprice_allowed,product_det_qty_type,product_det_qty_caption,product_det_qty_drop_values,
								product_det_qty_drop_prefix,product_det_qty_drop_suffix,product_variablecombocommon_image_allowed,default_comb_id,
								price_normalprefix, price_normalsuffix, price_fromprefix, price_fromsuffix,price_specialofferprefix, price_specialoffersuffix, 
								price_discountprefix, price_discountsuffix, price_yousaveprefix, price_yousavesuffix, price_noprice,product_freedelivery,product_show_pricepromise,
								product_hide,product_saleicon_show,product_saleicon_text,product_newicon_show,product_newicon_text   
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
			$price_class_arr['class_type']					= '';
			$price_class_arr['ul_class'] 		= 'prodeulprice';
			$price_class_arr['normal_class'] 	= 'productdetnormalprice';
			$price_class_arr['strike_class'] 	= 'productdetstrikeprice';
			$price_class_arr['yousave_class'] 	= 'productdetyousaveprice';
			$price_class_arr['discount_class'] 	= 'productdetdiscountprice';
			echo show_Price($row_prod,$price_class_arr,'prod_detail');
		}
		function show_Variable_main_image($product_id,$var_arr=array())
		{
			global $db,$ecom_siteid,$Captions_arr;
			$Captions_arr['PROD_DETAILS'] 	= getCaptions('PROD_DETAILS');
			$comb_arr = get_combination_id($product_id,$var_arr);
			if ($comb_arr['combid']>0)
			{
				$sql_prod = "SELECT product_name  
							FROM 
								products 
							WHERE 
								sites_site_id = $ecom_siteid 
								AND product_id = $product_id 
							LIMIT 
								1";
				$ret_prod = $db->query($sql_prod);
				if($db->num_rows($ret_prod))
				{
					$row_prod = $db->fetch_array($ret_prod);
				}
				// Calling the function to get the type of image to shown for current 
				$pass_type = get_default_imagetype('proddet');
				$prodimg_arr = get_imagelist_combination($comb_arr['combid'],$pass_type,0,1);
				if(count($prodimg_arr))
				{
					$exclude_prodid = $prodimg_arr[0]['image_id']; 		// exclude id in case of multi images for products
					/*<a href="javascript:showImagePopup('<?php echo $prodimg_arr[0]['image_extralargepath']?>','<?php echo $ecom_hostname?>','<?php echo $ecom_themename?>')"  title="Click to Zoom">*/
					show_image(url_root_image($prodimg_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name'],'','main_det_img');
					$imgs_main_arr = explode('big/',$prodimg_arr[0][$pass_type]);
					?>	
					<input type="hidden" name="main_img_hold_var" id="main_img_hold_var" value="<?php echo $imgs_main_arr[1]?>" />
					<input type="hidden" name="main_img_hold_id" id="main_img_hold_id" value="<?php echo $exclude_prodid?>" />
				<?php
				}
				else
				{
					$no_img = get_noimage('prod','big'); 
					if ($no_img)
					{
						show_image($no_img,$row_prod['product_name'],$row_prod['product_name']);
					}
				}
			}
			$ret_arr['exclude_prodid'] 	= $exclude_prodid;
			return $ret_arr;
		}
		function show_Variable_more_images($product_id,$exclude_prodid,$var_arr=array())
		{
				global $db,$ecom_hostname,$ecom_themename;
				$comb_arr 		= get_combination_id($product_id,$var_arr);
				$prodimg_arr	= array();
				$pass_type 	= 'image_iconpath';
				$show_normalimage = true;
				 if ($show_normalimage==true) // the following is to be done only coming for normal image display
				 {
					if ($exclude_prodid)
						$prodimg_arr = get_imagelist_combination($comb_arr['combid'],$pass_type.',image_thumbpath',$exclude_prodid,0);
				 } 
					if(count($prodimg_arr)>0)// Case if more than one image assigned to current product
					{
				?>	
				<div class="prdt_subimage">
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
							 <ul>
								<li>
							<a href="javascript:handle_image_swap('<?php echo $i?>')" title="<?=$title?>" id="<?php $imid?>">
							<?php
								 show_image(url_root_image($v['image_iconpath'],1),$title,$title,'preview',$cur_moreimg_id);
								$i++;
							?>
							</a>
							</li>
							</ul>
						<?php
						}
						?>	
						<input type="hidden" name="more_img_hold_var" id="more_img_hold_var" value="<?php echo $img_det?>" />
					  </div>
			  <?php
				}
			}
?>

