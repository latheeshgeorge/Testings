<?php
	/*############################################################################
	# Script Name 	: shopHtml.php
	# Description 		: Page which holds the display logic for shop details
	# Coded by 		: Sny
	# Created on		: 15-May-2009
	# Modified by		: 
	# Modified On		: 
	##########################################################################*/
	class shop_Html
	{
		// Defining function to show the selected Shop details
		function Show_ShopDetails($ret_shop)
		{
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$ecom_themename,$Settings_arr,$ecom_themeid,$default_layout,$inlineSiteComponents,$Settings_arr;
			$Captions_arr['SHOP_DETAILS'] 	= getCaptions('SHOP_DETAILS');
			// ** Fetch the category details
			$row_shop	= $db->fetch_array($ret_shop);
			
			
			
			// ** Check whether category image module is there for current site
			if(in_array('mod_shopimage',$inlineSiteComponents))
			{
				$img_support = true;
			}
			else
				$img_support = false; 
				
				
		   $custom_id = get_session_var('ecom_login_customer');
		  if($_REQUEST['type_cat']=='cate_root'){
			   if($_REQUEST['resultcat']=='added')
			   {
			   $alert = $Captions_arr['SHOP_DETAILS']['ADD_MSG'];
			   }
			   else if($_REQUEST['resultcat']=='removed')
			   {
			   $alert = $Captions_arr['SHOP_DETAILS']['REM_MSG'];
			   }
			 } 
		?>
		<div class="treemenu"><?php echo generate_shop_tree($row_shop['shopbrand_name']); ?></div>
      <?php
	  ?>
	   <div class="lst_outer">
		<?php
				if($alert)
				{
		?>
					<div class="red_msg">
						- <?php echo $alert?> -
					</div>
		<?php
				}
		?>
        <div class="lst_cat_header" >
		<?php echo stripslashes($row_shop['shopbrand_name']);?>
		</div>
		<div class="lst_cat_header" >
			<?php
			if($row_shop['shopbrand_turnoff_mainimage']==0)
			 {	
				 if ($img_support) // ** Support Category Image
				{
								if ($row_shop['shopbrand_showimageofproduct']==0) // Case to check for images directly assigned to category
								{
									if ($_REQUEST['shopthumb_id'])	
										$showonly = $_REQUEST['shopthumb_id'];
									else
										$showonly = 0;
									// Calling the function to get the type of image to shown for current 
								   $pass_type = get_default_imagetype('shop');	
							   	    
									// Calling the function to get the image to be shown
									$shopimg_arr = get_imagelist('prodshop',$row_shop['shopbrand_id'],$pass_type,0,$showonly,1); 
				
									if(count($shopimg_arr))
									{
									    $exclude_catid 	= $shopimg_arr[0]['image_id']; // exclude id in case of multi images for category
										show_image(url_root_image($shopimg_arr[0][$pass_type],1),$row_shop['shopbrand_name'],$row_shop['shopbrand_name'],'imgwraptext');
										$show_noimage 	= false;
									}
									else
										$show_noimage = true;
								}
								else // Case of check for the first available image of any of the products under this category
								{
									// Calling the function to get the id of products under current category with image assigned to it
									$cur_prodid = find_AnyProductWithImageUnderShop($_REQUEST['shop_id']);
									if ($cur_prodid)// case if any product with image assigned to it under current category exists
									{
										// Calling the function to get the type of image to shown for current 
										$pass_type = get_default_imagetype('shop');	 // ==================== DOUBT
										// Calling the function to get the image to be shown
										$img_arr = get_imagelist('prod',$cur_prodid,$pass_type,0,0,1);
		
										if(count($img_arr))
										{
											show_image(url_root_image($img_arr[0][$pass_type],1),$row_shop['shopbrand_name'],$row_shop['shopbrand_name'],'imgwraptext');
											$show_noimage = false;
										}
										else// case if no products exists under current category with image assigned to it
										$show_noimage = true;
									}
									else// case if no products exists under current category with image assigned to it
										$show_noimage = true;
								}	
								
								// ** Following section decides whether no image is to be displayed
								if($show_noimage)
								{
									// calling the function to get the default no image 
									$no_img = get_noimage('prod','big'); 
									if ($no_img)
									{
										//show_image($no_img,$row_shop['shopbrand_name'],$row_shop['shopbrand_name'],'imgwraptext');
									}	
								} 
					}
			}	
					?>
					<?php		
						 $sql_shop_name = "SELECT shopbrand_id,shopbrand_name
						FROM 
							product_shopbybrand 
						WHERE 
							sites_site_id		= $ecom_siteid 
							AND shopbrand_parent_id	= ".$_REQUEST['shop_id']." 
							AND shopbrand_hide	= 0
						LIMIT 
							1"; 
							$ret_shop_name = $db->query($sql_shop_name);
							$row_shop_name=$db->fetch_array($ret_shop_name);
							$url= url_shops($_REQUEST['shopbrand_id'],$row_shop_name['shopbrand_name'],'');
					
				echo stripslashes($row_shop['shopbrand_description'])
					?>
			</div>
					<?php
					?>
						<div class="lst_cat_des" >
					
						</div>
						<?php
					// ** Showing the category description if it exists
					if ($row_shop['category_paid_for_longdescription']=='Y' and trim($row_shop['category_paid_description'])!='')
					{
						$shop_desc =  stripslashes($row_shop['category_paid_description']);
					}
					elseif (trim($row_shop['shopbrand_product_showshortdescription'])!='')
					{
						$shop_desc = stripslashes($row_shop['shopbrand_product_showshortdescription']);
					}
					if ($shop_desc!='')// Show the following tr only if description exists
					{
				  ?>
						<div class="lst_cat_des" >
						  <?php
							//echo $shop_desc;
						  ?>
						 </div>
				<?php
					}
					?>							
				<div class="lst_cat_header" >
				<?php 
					$url= url_category($_REQUEST['category_id'],$row_cat['category_name'],'');
					?>
						<form method="post" name="frm_catedetails" id="frm_catedetails" action="" class="frm_cls">
		            <input type="hidden" name="fpurpose" value="" />
		            <input type="hidden" name="caturl" value="<? echo $url?>" />
					 <input type="hidden" name="type_cat" value="cate_root" />
                     <input type='hidden' name='shop_id' value="<?=$_REQUEST['shop_id']?>"/>
				  	 <div class="categoreyimagediv" align="right"><a href="<?=url_Shop_PDF($_REQUEST['shop_id'],$row_shop['shopbrand_name'])?>" class="productdetailslink" title="<?php echo $Captions_arr['SHOP_DETAILS']['SHOPDET_DOWNLOADPDF']?>">
				  	 <img src="<?php url_site_image('pdf_download.gif')?>" alt="<?php echo $Captions_arr['SHOP_DETAILS']['SHOPDET_DOWNLOADPDF']?>" border="0" /></a></div>
				   </form>
				   </div>
				   <div class="lst_cat_header" >
			 <?php
					if ($exclude_catid)
					{
						// Calling the function to get the type of image to shown for current 
						$shopimg_arr = get_imagelist('prodshop',$row_shop['shopbrand_id'],'image_thumbpath',$exclude_catid,0);
					}
					if($row_shop['shopbrand_turnoff_moreimages']==0)
			        {	
						if(count($shopimg_arr)>0)// Case if more than one image assigned to category
						{	
						?>
							<table width="100%" cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td align="left" <?php echo ($img_support)?'colspan="2"':''?> valign="bottom" class="moreimages">
								<?php echo $Captions_arr['SHOP_DETAILS']['SHOPDET_MOREIMAGES']?>
								</td>
							</tr>	
							<tr>
								<td align="left" <?php echo ($img_support)?'colspan="2"':''?> valign="bottom" class="">
									<table width="100%" cellpadding="0" cellspacing="0" border="0">
									<tr>
									<?php
										$maximg_col = 3;
										$curimg_col = 0;
										
										foreach ($shopimg_arr as $k=>$v)
										{
											?>
											<td valign="bottom" class="moreimages_td"><a href="<?php url_shops($row_shop['shopbrand_id'],$row_shop['shopbrand_name'],-1)?>?shopthumb_id=<?php echo $v['image_id']?>" title="<?php echo $row_shop['shopbrand_name']?>">
											<?php show_image(url_root_image($v['image_thumbpath'],1),$row_shop['shopbrand_name'],$row_shop['shopbrand_name']);
											?></a> 
											</td>
											<?php
											$curimg_col++;
											if ($curimg_col>=$maximg_col)
											{
												echo "</tr><tr>";
												$curimg_col = 0;
											}
										}
										if ($curimg_col<$maximg_col and $curimg_col>0)
										{
											echo "<td colspan='".($maximg_col-$curimg_col)."'>&nbsp;</td>";
										}
									?>
									</tr>
									</table>
								</td>
							</tr>
							 </table>
						<?php	
						}
				 }
				?>
		</div>
		<?php
				
				$subshopshow 	    = false;
				$prodshow		    = true;
				$subshop_exists 	= false;
				
				if ($row_shop['shopbrand_subshoplisttype']=='Middle')
					$subshopshow = true; 
					
				if($subshopshow)
				{
					// ** Check for handling the case of caching
					
					$cache_exists 	= false;
					$cache_required	= false;
					$cache_type		= 'shop';	
					if ($Settings_arr['enable_caching_in_site']==1)
					{
						$cache_required = true;
						if ($_REQUEST['shop_id'])// Look for cache only if category id is there
						{
							$passid 		= $_REQUEST['shop_id'];
							if (exists_Cache($cache_type,$passid))
							{
								$content_cache 	= getcontent_Cache($cache_type,$passid);
								if ($content_cache) // if cache exists show it
								{
									echo $content_cache;
									$cache_exists = true;
								}
							}
						}	
					}	
					if ($cache_exists==false)
					{
						if($cache_required)// if caching is required start recording the output
						{
							ob_start();
						}
					$shopbranddet_sort			= ($_REQUEST['shopbranddet_sortby'])?$_REQUEST['shopbranddet_sortby']:$Settings_arr['shopbybrand_shops_orderfield'];
					$shopbranddet_order			= ($_REQUEST['shopbranddet_sortorder'])?$_REQUEST['shopbranddet_sortorder']:$Settings_arr['shopbybrand_shops_orderby'];
					switch ($shopbranddet_sort)
					{
						case 'custom': // case of order by customer fiekd
						$shopbranddet_sort_by		= 'shopbrand_order';
						break;
						case 'shopbrand_name': // case of order by product name
						$shopbranddet_sort_by		= 'shopbrand_name';
						break;
					
						default: // by default order by product name
						$shopbranddet_sort_by		= 'shopbrand_name';
						break;
					};
						// ** Check whether any subshop exists
				       $sql_subshop 	= "SELECT shopbrand_id, shopbrand_name,shopbrand_subshoplisttype,
	              							shopbrand_product_displaytype, shopbrand_showimageofproduct,
									   		shopbrand_product_showimage, shopbrand_product_showtitle,
				  							shopbrand_product_showshortdescription, shopbrand_product_showprice, shopbrand_product_displaytype 
										FROM
											product_shopbybrand 
										WHERE 
											shopbrand_parent_id = ".$_REQUEST['shop_id']." 
											AND sites_site_id = $ecom_siteid 
											AND shopbrand_hide=0 
										ORDER BY 
											$shopbranddet_sort_by $shopbranddet_order"; //,category_showimageofproduct,default_catgroup_id
						
						$ret_subshop = $db->query($sql_subshop);
						
						if ($db->num_rows($ret_subshop))
						{
					?>
							<tr>
								<td colspan="3">
					<?php	
									$this->Show_Subshops($ret_subshop,$url); // ** Calling the function to show the subcategories
									$subshop_exists = true;
					?>
								</td>
							</tr>	
					<?php	
						}
						if($cache_required)// If caching was required then stop the recording and save the cache and display the cached content
						{
							$content = ob_get_contents();
							ob_end_clean();
							save_Cache($cache_type,$passid,$content);
							echo $content;
						}
					}
				}
			
			if($prodshow)// Checks whether products to be shown in the middle area
			{
				// ** Check whether any product exists under current category
				$sql_prods 		= "SELECT count(a.product_shopbybrand_shopbrand_id) as cnt 
									FROM 
										product_shopbybrand_product_map a,products b
									WHERE 
										a.product_shopbybrand_shopbrand_id = ".$_REQUEST['shop_id']." 
										AND a.products_product_id=b.product_id 
										AND b.product_hide='N'";
				$ret_prods		= $db->query($sql_prods);
				list($tot_cnt)	= $db->fetch_array($ret_prods);
				
				if ($tot_cnt>0)
				{
					//$prodsort_by			= ($_REQUEST['shopdet_sortby'])?$_REQUEST['shopdet_sortby']:$Settings_arr['product_orderfield'];
					//$prodperpage			= ($_REQUEST['shopdet_prodperpage'])?$_REQUEST['shopdet_prodperpage']:$Settings_arr['product_maxcntperpage'];// product per page
					$prodsort_by			= ($_REQUEST['shopdet_sortby'])?$_REQUEST['shopdet_sortby']:$Settings_arr['productshop_orderfield'];
		  			$prodperpage			= ($_REQUEST['shopdet_prodperpage'])?$_REQUEST['shopdet_prodperpage']:$Settings_arr['product_maxcntperpage_shops'];// product per page
					$prodsort_order			= ($_REQUEST['shopdet_sortorder'])?$_REQUEST['shopdet_sortorder']:$Settings_arr['productshop_orderby'];
					switch ($prodsort_by)
					{
						case 'custom': // case of order by customer fiekd
						$prodsort_by		= 'b.map_sortorder';
						break;
						case 'product_name': // case of order by product name
						$prodsort_by		= 'a.product_name';
						break;
						case 'price': // case of order by price
						$prodsort_by		= 'a.product_webprice';
						break;
						case 'product_id': // case of order by price
						$prodsort_by		= 'a.product_id';
						break;	
						default: // by default order by product name
						$prodsort_by		= 'a.product_name';
						break;
					};
					//$prodsort_order	= ($_REQUEST['shopdet_sortorder'])?$_REQUEST['shopdet_sortorder']:$Settings_arr['product_orderby'];
					$start_var 		= prepare_paging($_REQUEST['shopdet_pg'],$prodperpage,$tot_cnt);
					// Get the list of products to be shown in current shelf
					$sql_prod = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
									a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
									a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
									product_total_preorder_allowed,a.product_applytax,a.product_shortdesc,a.product_bonuspoints ,
									a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,a.product_variablesaddonprice_exists,
									a.product_variablecomboprice_allowed,a.product_variablecombocommon_image_allowed,a.default_comb_id,
									a.price_normalprefix,a.price_normalsuffix, a.price_fromprefix, a.price_fromsuffix,a.price_specialofferprefix, a.price_specialoffersuffix, 
									a.price_discountprefix, a.price_discountsuffix, a.price_yousaveprefix, a.price_yousavesuffix,a.price_noprice      
								FROM 
									products a,product_shopbybrand_product_map b 
								WHERE 
									b.product_shopbybrand_shopbrand_id = ".$_REQUEST['shop_id']." 
									AND a.product_id = b.products_product_id 
									AND a.product_hide = 'N' 
								ORDER BY 
									$prodsort_by $prodsort_order 
								LIMIT 
									".$start_var['startrec'].", ".$prodperpage;
					$ret_prod = $db->query($sql_prod);
					$this->Show_Products($ret_prod,$tot_cnt,$start_var,$row_shop['shopbrand_name'],$row_shop['shopbrand_id'],$row_shop['shopbrand_product_displaytype'],$row_shop['shopbrand_product_showimage'],$row_shop['shopbrand_product_showtitle'],$row_shop['shopbrand_product_showshortdescription'],$row_shop['shopbrand_product_showprice']); // ** Calling function to show the products under current category
				}
				else
				{
					if ($subshop_exists==false)// ** Show the no products only if there exists no subcategories for current product
					{
					?>
					
						<?php
							$this->Show_NoProducts(); // ** Calling function to show the no products message
						?>
						
					<?php
					}	
				}		
			}				
	?>
	</div>
		<?php	
		// Including the shelf to show the shelves assigned to current category.
							$sql_inline = "SELECT display_order,display_component_id,display_id,display_title 
											FROM 
												display_settings a,features b 
											WHERE 
												a.sites_site_id=$ecom_siteid 
												AND a.display_position='middle' 
												AND b.feature_allowedinmiddlesection = 1  
												AND layout_code='".$default_layout."' 
												AND a.features_feature_id=b.feature_id 
												AND b.feature_modulename='mod_shelf' 
											ORDER BY 
													display_order 
													ASC";
							$ret_inline = $db->query($sql_inline); // to dispplay the advert for the category page
							if ($db->num_rows($ret_inline))
							{
								while ($row_inline = $db->fetch_array($ret_inline))
								{
									//$modname 			= $row_inline['feature_modulename'];
									$body_dispcompid	= $row_inline['display_component_id'];
									$body_dispid		= $row_inline['display_id'];
									$body_title			= $row_inline['display_title'];
									include ("includes/base_files/shelf.php");
								}
							}
				
		}
		// ** Function to show the subShops
		function Show_Subshops($ret_subshop,$url)
		{	
			global $db,$inlineSiteComponents,$Captions_arr,$Settings_arr,$ecom_siteid;
			if(in_array('mod_shopimage',$inlineSiteComponents))
			{
				$img_support = true;
			}
			else
				$img_support = false;
				
				$custom_id = get_session_var('ecom_login_customer');
				
				
		       if($_REQUEST['type_cat']=='sub_shop'){
				   if($_REQUEST['resultcat']=='added')
				   {
				   $alert = $Captions_arr['SHOP_DETAILS']['ADD_MSG'];
				   }
				   else if($_REQUEST['resultcat']=='removed')
				   {
				   $alert = $Captions_arr['SHOP_DETAILS']['REM_MSG'];
				   }
			 } 
		?>
			    <div class="lst_sub_cat" >
				   <?php
				  	if ($db->num_rows($ret_subshop)==1)
						echo $Captions_arr['SHOP_DETAILS']['SHOPDET_SUBSHOP'];
					else
						echo $Captions_arr['SHOP_DETAILS']['SHOPDET_SUBSHOPS'];
				  ?>	
				</div>
				<?php
				if($alert)
				{
				?>
						<div class="red_msg">
							- <?php echo $alert?> -
						</div>
				<?php
					}
						?>
						 <div class="lst_sub_con">
				        <ul class="sub_cat">
						<?php
					while ($row_subshop = $db->fetch_array($ret_subshop))
					{
					
				?>
						<form method="post" name="frm_subcatedetails_<?=$row_subshop['shopbrand_id']?>" id="frm_subcatedetails_<?=$row_subshop['shopbrand_id']?>" action="" class="frm_cls">
						<input type="hidden" name="caturl" value="<? echo $url;?>" />
						<input type="hidden" name="type_cat" value="sub_cat" />
						<input type="hidden" name="sub_shop_id" value="<? echo $row_subshop['shopbrand_id'];?>" />
						<input type="hidden" name="fpurpose" value="" />
					    <input type='hidden' name='shop_id' value="<?=$_REQUEST['shop_id']?>"/>
						<li><h2>
						  <?php 
						 if($img_support) // ** Show sub category image only if catimage module is there for the site
							{
						  ?>
						  		<a href="<?php url_shops($row_subshop['shopbrand_id'],$row_subshop['shopbrand_name'],-1)?>" class="subcategoreynamelink" title="<?php echo stripslashes($row_subshop['shopbrand_name'])?>">
					        <?php
							
								if ($row_subshop['shopbrand_showimageofproduct']==0) // Case to check for images directly assigned to category
								{
									// Calling the function to get the type of image to shown for current 
									$pass_type = get_default_imagetype('subshop');	
									// Calling the function to get the image to be shown
									$img_arr = get_imagelist('prodshop',$row_subshop['shopbrand_id'],$pass_type,0,0,1);
									
									if(count($img_arr))
									{
										show_image(url_root_image($img_arr[0][$pass_type],1),$row_subshop['shopbrand_name'],$row_subshop['shopbrand_name']);
										$show_noimage = false;
									}
									else
										$show_noimage = true;
								}
								else // Case of check for the first available image of any of the products under this category
								{
								// Calling the function to get the id of products under current category with image assigned to it
									$cur_prodid = find_AnyProductWithImageUnderShop($_REQUEST['shop_id']);
									if ($cur_prodid)// case if any product with image assigned to it under current category exists
									{
										// Calling the function to get the type of image to shown for current 
										$pass_type = get_default_imagetype('subshop');
										// Calling the function to get the image to be shown
										$img_arr = get_imagelist('prod',$cur_prodid,$pass_type,0,0,1);
										
										if(count($img_arr))
										{
											show_image(url_root_image($img_arr[0][$pass_type],1),$row_subshop['shopbrand_name'],$row_shop['shopbrand_name']);
											$show_noimage = false;
										}
										else// case if no products exists under current category with image assigned to it
										$show_noimage = true;
									}
									else// case if no products exists under current category with image assigned to it
										$show_noimage = true;
								}
								// ** Following section makes the decision whether the no image is to be displayed
								if ($show_noimage)
								{
									// calling the function to get the default no image 
									$no_img = get_noimage('prodshop',$pass_type); 
									if ($no_img)
									{
										show_image($no_img,$row_subshop['shopbrand_name'],$row_subshop['shopbrand_name']);
									}	
								} 
							?>
								</a> 
						  <?php
						 	}
							
						  ?>
						 	<span><a href="<?php url_shops($row_subshop['shopbrand_id'],$row_subshop['shopbrand_name'],-1)?>" class="subcategoreynamelink" title="<?php echo stripslashes($row_subshop['shopbrand_name'])?>"><?php echo stripslashes($row_subshop['shopbrand_name'])?></a></span>
						<?php
							?>
						</form>
						</h2>
						</li>
				<?php
					}
					?>
					</ul>
					</div>
					<?php						
			}
		
		// ** Function to list the products
		function Show_Products($ret_prod,$tot_cnt,$start_var,$catname,$catgroupid,$displaytype,$show_image,$show_title,$show_desc,$show_price)
		{
			global $db,$Settings_arr,$Captions_arr,$inlineSiteComponents,$compare_button_displayed;
			$showqty				= $Settings_arr['show_qty_box'];// show the qty box
			$prodsort_by			= ($_REQUEST['shopdet_sortby'])?$_REQUEST['shopdet_sortby']:$Settings_arr['productshop_orderfield'];
		  	$prodperpage			= ($_REQUEST['shopdet_prodperpage'])?$_REQUEST['shopdet_prodperpage']:$Settings_arr['product_maxcntperpage_shops'];// product per page
			$prodsort_order			= ($_REQUEST['shopdet_sortorder'])?$_REQUEST['shopdet_sortorder']:$Settings_arr['productshop_orderby'];
		?>
<?php /*?>			<div><?php echo paging_show_totalcount($tot_cnt,'Product(s)',$start_var['pg'],$start_var['pages'])?></div>
<?php */?>
<div ><?php if(isProductCompareEnabled() && !$compare_button_displayed) {$compare_button_displayed = true; ?>
				<form name="add_to_compare" id="add_to_compare" action="" method="post" >
				<input type="hidden" name="submit_Compare_pdts" id="submit_Compare_pdts" value="1">
				<input type="hidden" name="compare_products" id="compare_products" value=""><!--<input type="submit" name="submit_Compare_pdts" value="ADD TO COMPARE" class="buttonred_large" onclick="handle_addtoCompare()" />-->	</form>		<? }?>
				</div>
           <div class="lst_nav">
     			<ul>
					<li><?php echo $Captions_arr['SHOP_DETAILS']['SHOPDET_SORTBY']?></li>
					<li>
						<select name="shopdet_sortbytop" id="shopdet_sortbytop">
  						<option value='custom' <?php echo ($prodsort_by=='custom')?'selected="selected"':''?> >Default</option>
                        <option value="product_name" <?php echo ($prodsort_by=='product_name')?'selected="selected"':''?>><?php echo $Captions_arr['SHOP_DETAILS']['SHOPDET_PRODNAME']?></option>
                        <option value="price" <?php echo ($prodsort_by=='price')?'selected="selected"':''?>><?php echo $Captions_arr['SHOP_DETAILS']['SHOPDET_PRICE']?></option>
						  <option value="product_id" <?php echo ($prodsort_by=='product_id')?'selected="selected"':''?>><?php echo $Captions_arr['SHOP_DETAILS']['SHOPDET_DATEADDED']?></option>
                        </select>
					</li>
					<li>	
						<select name="shopdet_sortordertop" id="shopdet_sortordertop">
                            <option value="ASC" <?php echo ($prodsort_order=='ASC')?'selected="selected"':''?>><?php echo $Captions_arr['SHOP_DETAILS']['SHOPDET_LOW2HIGH']?></option>
                            <option value="DESC" <?php echo ($prodsort_order=='DESC')?'selected="selected"':''?>><?php echo $Captions_arr['SHOP_DETAILS']['SHOPDET_HIGH2LOW']?></option>
                        </select>
					</li>
					<li><?php echo $Captions_arr['SHOP_DETAILS']['SHOPDET_ITEMSPERPAGE']?>
						<select name="shopdet_prodperpagetop" id="shopdet_prodperpagetop">
						<?php
							for ($ii=$Settings_arr["productlist_interval"];$ii<=$Settings_arr["productlist_maxval"];$ii+=$Settings_arr["productlist_interval"])
							{
						?>
								<option value="<?php echo $ii?>" <?php echo ($prodperpage==$ii)?'selected="selected"':''?>><?php echo $ii?></option>
						<?php	
							}
						?>
						</select>
						<input type="button" name="submit_Page" value="<?php echo $Captions_arr['SHOP_DETAILS']['SHOPDET_GO']?>" class="buttonred" onclick="handle_shopdetailsdropdownval_sel('<?php echo url_shops($_REQUEST['shop_id'],$catname,4)?>','shopdet_sortbytop','shopdet_sortordertop','shopdet_prodperpagetop')" />
						</li>
				</ul>
				</div>
				<?php
						// ** Showing the products listing either 1 in a row or 3 in a row
						$pg_variable = 'shopdet_pg';
						// Calling the function to get the type of image to shown for current 
						$pass_type = get_default_imagetype('subshop_prod');
						$prod_compare_enabled = isProductCompareEnabled();
						switch($displaytype)
						{
							case '1row': // case of one in a row for normal
							?>
								<?php
									if ($tot_cnt>0)
									{
									?>
										<div class="pagingcontainertd" align="center">
											<?php 
												$path = '';
												$pageclass_arr['container'] = 'pagenavcontainer';
												$pageclass_arr['navvul']	= 'pagenavul';
												$pageclass_arr['current']	= 'pagenav_current';
												
												$query_string = "&amp;shopdet_sortby=".$prodsort_by.'&amp;shopdet_sortorder='.$prodsort_order.'&amp;shopdet_prodperpage='.$prodperpage;
												paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr); 	
											?>	
										</div>
									<?php
									}
									while($row_prod = $db->fetch_array($ret_prod))
									{
									$prodcur_arr[] = $row_prod;
								?>
										<div class="list_main">
										<div class="shlf_pdt_img_outr">
										<div class="shlf_pdt_img">
										<?php
												if ($show_image==1)// Check whether description is to be displayed
												{
											?>		
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
											<?php
												}
											?>	
										</div>
										<div class="shlf_pdt_compare" >
										<?php if($prod_compare_enabled)  { 
												dislplayCompareButton($row_prod['product_id']);
												}
										?>
										</div>
										</div>
										<div class="shlf_pdt_txt">
										<ul class="shlf_pdt_ul">
										<?php
											if($show_title==1)// whether title is to be displayed
											{
										?>
												<li class="shlf_pdt_name" ><h3><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h3></li>
										
										<?php
											}
											if ($show_desc==1)// Check whether description is to be displayed
											{
										?>		
												<li class="shlf_pdt_des"><h6>	<?php echo stripslashes($row_prod['product_shortdesc'])?></h6></li>
										<?php
											}
										?>			
										</ul>
										<?php //$show_title,$show_desc,
												if($show_price==1){
													$price_class_arr['ul_class'] 		= 'shelfA_price_ul';
													$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
													$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
													$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
													$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
													echo show_Price($row_prod,$price_class_arr,'shopbrand_3');
													show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
													}
											    $frm_name = uniqid('shopdet_');
											?>	
										<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
											<input type="hidden" name="fpurpose" value="" />
											<input type="hidden" name="fproduct_id" value="" />
											<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
											<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
											<div class="infodiv">
											<div class="infodivleft"><?php show_moreinfo($row_prod,'infolink')?></div>
											<div class="infodivright">
												<?php
															$class_arr 					= array();
															$class_arr['ADD_TO_CART']	= 'quantity_infolinkB';
															$class_arr['PREORDER']			= 'quantity_infolinkB';
															$class_arr['ENQUIRE']			= 'quantity_infolinkB';
															show_addtocart($row_prod,$class_arr,$frm_name)
														?>
											</div>
											</div>
										</form>
										</div>
										</div>
								<?php
									}
								?>	
							<?php
							break;
							case '3row': // case of three in a row for normal
							?>
						 <div class="shelfA_main_con"> 
							<?php										
								if ($tot_cnt>0 and ($_REQUEST['req']!=''))
								{
								?>
										<div class="pagingcontainertd">
											<?php 
											$path = '';
											//show_paging($path,$_REQUEST[$pg_variable],$start_arr['counterstart'],$tot_cnt,$prodperpage,$pg_variable,'')
												$pageclass_arr['container'] = 'pagenavcontainer';
												$pageclass_arr['navvul']	= 'pagenavul';
												$pageclass_arr['current']	= 'pagenav_current';
												
												$query_string = "&amp;shopdet_sortby=".$_REQUEST['shopdet_sortby'].'&amp;shopdet_sortorder='.$_REQUEST['shopdet_sortorder'].'&amp;shopdet_prodperpage='.$_REQUEST['shopdet_prodperpage'];
												paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr,0); 	
										?>		
										</div>
									<?php
									}
									$max_col = 3;
									$cur_col = 0;
									$prodcur_arr = array();
									$cur_tot_cnt = $db->num_rows($ret_prod);
									$cur = 1;
									while($row_prod = $db->fetch_array($ret_prod))
									{
										$prodcur_arr[] = $row_prod;
										$cur++;
										//##############################################################
										// Showing the title, description and image part for the product
										//##############################################################
										if($cur_col==0)
										{
											$main_inner_shelf = 'shlfA_inner_main_lst';
								?>
											 <div class="list_main">
								<?php
										}
										else
											$main_inner_shelf = 'shlfA_inner_main';
								?>
										<div class="<?php echo $main_inner_shelf?>">
								<?php
										if ($show_image==1)// Check whether description is to be displayed
										{
									?>		
											<div class="shlfA_pdt_img">
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
													?></a>
											 </div>		
										<?php
											}
										  ?>
										 
										  <?php 
										  if($prod_compare_enabled) 
										  {
										  ?>
											<div class="shlfA_pdt_compare" >
										<?php
												dislplayCompareButton($row_prod['product_id']);
										?>		
											</div>
										<?php
											}?>
										  <div class="shlfA_pdt_txt">
												<ul class="shlfA_pdt_ul">
												<?php
													if($show_title==1)// whether title is to be displayed
													{
												?>
													<li class="shlfA_pdt_name" ><h3><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h3></li>
												<?php
													}
													if ($show_desc==1)// Check whether description is to be displayed
													{
												?>
													<li class="shlfA_pdt_des" ><h6><?php echo stripslashes($row_prod['product_shortdesc'])?></h6></li>
												<?php
													}
												?>	
													<li class='shlfA_pdt_bonus'><h6><?php show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points?></h6></li>
												</ul>
												<?php	
													if ($show_price==1)// Check whether description is to be displayed
													{
												?>
														<li>
														<?php
												if($show_price==1){
													$price_class_arr['ul_class'] 		= 'shelfA_price_ul';
													$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
													$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
													$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
													$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
													echo show_Price($row_prod,$price_class_arr,'shopbrand_3');
													show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
													}
												?>	
														</li>
												<?php
													}
												$frm_name = uniqid('shopdet_');													?>
													<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
													<input type="hidden" name="fpurpose" value="" />
													<input type="hidden" name="fproduct_id" value="" />
													<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
													<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
													<div class="infodivB">
														<div class="infodivleftB"><?php show_moreinfo($row_prod,'infolinkB')?></div>
														<div class="infodivrightB">
														<?php
															$class_arr 					= array();
															$class_arr['ADD_TO_CART']	= 'quantity_infolinkB';
															$class_arr['PREORDER']			= 'quantity_infolinkB';
															$class_arr['ENQUIRE']			= 'quantity_infolinkB';
															show_addtocart($row_prod,$class_arr,$frm_name)
														?>
														</div>
													</div>
													</form>
										  </div>
										  </div>
									<?php
										$cur_col++;
										if ($cur_col>=$max_col)
										{
										?>
											</div>
										<?php
											 $cur_col = 0;
										}	 
									}
									if ($cur_col<$max_col and $cur_col>0)
									{
										echo '</div>';
									}
									?>
								</div>
						<?php		
							break;
						};
				?>
			<div class="lst_nav" >
     			<ul>
				    <li><?php echo $Captions_arr['SHOP_DETAILS']['SHOPDET_SORTBY']?></li>
					<li>
						
						<select name="shopdet_sortbybottom" id="shopdet_sortbybottom">
							<option value='custom'  <?php echo ($prodsort_by=='')?'selected="selected"':''?> >Default</option>
                            <option value="product_name" <?php echo ($prodsort_by=='product_name')?'selected="selected"':''?>><?php echo $Captions_arr['SHOP_DETAILS']['SHOPDET_PRODNAME']?></option>
                            <option value="price" <?php echo ($prodsort_by=='price')?'selected="selected"':''?>><?php echo $Captions_arr['SHOP_DETAILS']['SHOPDET_PRICE']?></option>
						  <option value="product_id" <?php echo ($prodsort_by=='product_id')?'selected="selected"':''?>><?php echo $Captions_arr['SHOP_DETAILS']['SHOPDET_DATEADDED']?></option>
                      </select>
					</li>
					<li>	
						<select name="shopdet_sortorderbottom" id="shopdet_sortorderbottom">
                            <option value="ASC" <?php echo ($prodsort_order=='ASC')?'selected="selected"':''?>><?php echo $Captions_arr['SHOP_DETAILS']['SHOPDET_LOW2HIGH']?></option>
                            <option value="DESC" <?php echo ($prodsort_order=='DESC')?'selected="selected"':''?>><?php echo $Captions_arr['SHOP_DETAILS']['SHOPDET_HIGH2LOW']?></option>
                          </select>
					</li>
					<li><?php echo $Captions_arr['SHOP_DETAILS']['SHOPDET_ITEMSPERPAGE']?>
						<select name="shopdet_prodperpagebottom" id="shopdet_prodperpagebottom">
						<?php
							for ($ii=3;$ii<=33;$ii+=6)
							{
						?>
								<option value="<?php echo $ii?>" <?php echo ($prodperpage==$ii)?'selected="selected"':''?>><?php echo $ii?></option>
						<?php	
							}
						?>
						</select>
						<input type="button" name="submit_Page" value="<?php echo $Captions_arr['SHOP_DETAILS']['SHOPDET_GO']?>" class="buttonred" onclick="handle_shopdetailsdropdownval_sel('<?php echo url_shops($_REQUEST['shop_id'],$catname,4)?>','shopdet_sortbybottom','shopdet_sortorderbottom','shopdet_prodperpagebottom')" />
						</li>
				</ul>
				</div>
		<?php
		}
		
		// ** Function to show the no products message
		function Show_NoProducts()
		{
			global $Captions_arr;
			$Captions_arr['SHOP_DETAILS'] 	= getCaptions('SHOP_DETAILS');

		?>
			<div class="shelf_mid">
        	<div class="shelfBheader"><?php echo $Captions_arr['SHOP_DETAILS']['NO_PROD']?></div>
           	<div class="shelf_top_desc"><?php echo $Captions_arr['SHOP_DETAILS']['NO_PROD_MSG']?>
			 	</div>	 
			</div>
		<?php	
		}
	};	
?>
