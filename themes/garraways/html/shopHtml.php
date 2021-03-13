<?php
	/*############################################################################
	# Script Name 	: categoryHtml.php
	# Description 	: Page which holds the display logic for category details
	# Coded by 		: Sny
	# Created on	: 16-Jan-2008
	# Modified by	: Sny
	# Modified On	: 28-Apr-2008
	##########################################################################*/
	class shop_Html
	{
		// Defining function to show the selected Shop details
		function Show_ShopDetails($ret_shop='')
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
		
			if($_REQUEST['shop_id'])
			{
			?>
				<div class="treemenu"><a href="<? url_link('');?>"><?=stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']);?></a> >> <a href="<? url_link('shopbybrand.html');?>"><?=stripslash_normal($Captions_arr['SHOP_DETAILS']['SHOPS_ALL'])?></a> >> <?php echo $row_shop['shopbrand_name']; ?></div>
      		<?php	
			}	
			else
			{
			?>
			<div class="treemenu"><a href="<? url_link('');?>"><?=stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']);?></a> >>
				  <?=stripslash_normal($Captions_arr['SHOP_DETAILS']['SHOPS_ALL'])?>
				
		   </div>
			<?php
			}
			?>
        <table border="0" cellpadding="0" cellspacing="0" class="shelfAtable"> 
		<?php
				if($alert)
				{
			?>
					<tr>
						<td colspan="3" align="center" valign="middle" class="red_msg">
						- <?php echo $alert?> -
						</td>
					</tr>
			<?php
				}
				
				?>
        <tr>
          <td colspan="3" >
			  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="categoreyimage">
			  <?php
			  	if($_REQUEST['shop_id'])
				{
				?>
				<tr>
				  		<td align="left" valign="top"  class="categoreyimagetd">
						<?php
						if($row_shop['shopbrand_turnoff_mainimage']==0)
						 {	
							if ($img_support) // ** Support shop Image
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
					</td>
				 </tr>
				 <tr>
				 	<td align="right">
				 	<form method="post" name="frm_catedetails" id="frm_catedetails" action="" class="frm_cls">
		            <input type="hidden" name="fpurpose" value="" />
		            <input type="hidden" name="caturl" value="<? echo $url?>" />
					 <input type="hidden" name="type_cat" value="cate_root" />
                     <input type='hidden' name='shop_id' value="<?=$_REQUEST['shop_id']?>"/>
				  	 <div class="categoreyimagediv" align="right"><a href="<?=url_Shop_PDF($_REQUEST['shop_id'],$row_shop['shopbrand_name'])?>" class="productdetailslink" title="<?php echo $Captions_arr['SHOP_DETAILS']['SHOPDET_DOWNLOADPDF']?>">
				  	 <img src="<?php url_site_image('pdf_download.gif')?>" alt="<?php echo $Captions_arr['SHOP_DETAILS']['SHOPDET_DOWNLOADPDF']?>" border="0" /></a></div>
				   </form>
				 	</td>
				 </tr>
				 <?
				}
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
						<tr>
						  <td <?php echo ($img_support)?'colspan="2"':''?> align="left" valign="middle"  class="categorydetailstext">
						  <?php
							//echo $shop_desc;
						  ?>
						  </td>
						</tr>
				<?php
					}
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
						<?php	
						}
					}
				?>
			  </table>
		  </td>
        </tr>

		<?php
		
				$subshopshow 	    = false;
				if($_REQUEST['shop_id'])
				$prodshow		    = true;
				$subshop_exists 	= false;
				if(!$_REQUEST['shop_id'])
				$row_shop['shopbrand_subshoplisttype']='Middle';
				
				if ($row_shop['shopbrand_subshoplisttype']=='Middle')
					$subshopshow = true;  

				// Show the products in middle only or in both.
				//if ($row_shop['shopbrand_product_displaytype'] == 'middle' or $row_shop['product_displaywhere'] == 'both')
				//	$prodshow = true;
			
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
					if($_REQUEST['shop_id'])	// Check whether shop id exists, then pick the sort field as set from console other wise set the sort field as shop by brand name
						$shopbranddet_sort			= ($_REQUEST['shopbranddet_sortby'])?$_REQUEST['shopbranddet_sortby']:$Settings_arr['shopbybrand_shops_orderfield'];
					else
						$shopbranddet_sort			= 'shopbrand_name';
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
									$this->Show_Subshops($ret_subshop,$url,$_REQUEST['shop_id']); // ** Calling the function to show the subcategories
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
			
			if($prodshow)  // Checks whether products to be shown in the middle area
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
									a.price_discountprefix, a.price_discountsuffix, a.price_yousaveprefix, a.price_yousavesuffix,a.price_noprice,
									a.product_averagerating,a.product_saleicon_show,a.product_saleicon_text,a.product_newicon_show,a.product_newicon_text,
									a.product_freedelivery            
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
					//$this->Show_Products($ret_prod,$tot_cnt,$start_var,$row_shop['shopbrand_name'],$row_shop['shopbrand_id'],$row_shop['shopbrand_product_displaytype'],$row_shop['shopbrand_product_showimage'],$row_shop['shopbrand_product_showtitle'],$row_shop['shopbrand_product_showshortdescription'],$row_shop['shopbrand_product_showprice'],$row_shop['shopbrand_product_showrating'],$row_shop['shopbrand_product_showbonuspoints']); // ** Calling function to show the products under current category
					$this->Show_Products($ret_prod,$tot_cnt,$start_var,$row_shop); // ** Calling function to show the products under current category
				}
				else
				{
					if ($subshop_exists==false)// ** Show the no products only if there exists no subcategories for current product
					{
					?>
					<tr>
						<td colspan="3">
						<?php
							$this->Show_NoProducts(); // ** Calling function to show the no products message
						?>
						</td>
					</tr>
					<?php
					}	
				}		
			}				
		?>
		<tr>
			<td colspan="3" align="center">
				<?php 
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
				
				?>
			</td>
		</tr>		
		<?php 
		if (trim($row_shop['shopbrand_bottomdescription'])!='' and trim($row_shop['shopbrand_bottomdescription'])!='<br>')
		{
			$shop_bottom_desc =  stripslashes($row_shop['shopbrand_bottomdescription']);
		?>
			<tr>
					<td align="left" valign="top"  class="shelfBproddes" colspan="3">
					<?php echo stripslashes($shop_bottom_desc);?>
					</td>
			</tr>
		<?php	
		}
		?>
        </table>
		<?php	
		}
		// ** Function to show the subShops
		function Show_Subshops($ret_subshop,$url,$shopid=0)
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
			  	<table width="100%" border="0" cellpadding="0" cellspacing="2" class="subcategoreytable">
				<?php
				 if($shopid>0)
			     {
				 ?>
				<tr>
				  <td colspan="3" align="left" valign="middle" class="subcategoreyheader">
				  <?php
				  	if ($db->num_rows($ret_subshop)==1)
						echo $Captions_arr['SHOP_DETAILS']['SHOPDET_SUBSHOP'];
					else
						echo $Captions_arr['SHOP_DETAILS']['SHOPDET_SUBSHOPS'];
				  ?>	
				  </td>
				</tr>
				<?php
				}
				if($shopid==0)
				{
				$sql 				= "SELECT general_shopsall_topcontent,general_shopsall_bottomcontent FROM general_settings_sites_common WHERE sites_site_id=".$ecom_siteid;
		        $res_admin 			= $db->query($sql);
		        $fetch_arr_admin 	= $db->fetch_array($res_admin);
					if($fetch_arr_admin['general_shopsall_topcontent']!='')
					{
						?>
						<tr>
								<td align="left" valign="top"  class="shelfBproddes" colspan="3"><?php echo $fetch_arr_admin['general_shopsall_topcontent']?>
								</td>
						</tr>			
						<?php
					}
				}
				if($alert)
				{
				?>
						<tr>
							<td colspan="3" align="center" valign="middle" class="red_msg">
							- <?php echo $alert; ?> -
							</td>
						</tr>
				<?php
					}
					
				?>
				<tr>
				<?php	
					$max_col = 3;
					$cur_col = 0;
					while ($row_subshop = $db->fetch_array($ret_subshop))
					{
				?>
						<td width="33%" align="center" valign="middle" class="subcategoreyimage" onmouseover="this.className='subcategory_hover'" onmouseout="this.className='subcategoreyimage'">
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
									$shop_id =$row_subshop['shopbrand_id'];
									$cur_prodid = find_AnyProductWithImageUnderShop($shop_id);
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
						  		<a href="<?php url_shops($row_subshop['shopbrand_id'],$row_subshop['shopbrand_name'],-1)?>" class="subcategoreynamelink" title="<?php echo stripslashes($row_subshop['shopbrand_name'])?>"><?php echo stripslashes($row_subshop['shopbrand_name'])?></a>
						</td>
				<?php
						$cur_col++;
						if ($cur_col>=$max_col)
						{
							$cur_col = 0;
							echo "</tr><tr>";
						}
					}
					if ($cur_col<$max_col)
						echo '<td colspan="'.($max_col-$cur_col).'" class="subcategoreyimage">&nbsp;</td>';
				?>	
				   </tr>
			    	<?php
			 	    if($fetch_arr_admin['general_shopsall_bottomcontent']!='' && $shopid==0)
					{
						?>
						<tr>
								<td align="left" valign="top"  class="shelfBproddes" colspan="3"><?php echo $fetch_arr_admin['general_shopsall_bottomcontent']?>
								</td>
						</tr>			
						<?php
					}
					?>
			  </table>
		<?php
		}
		
		// ** Function to list the products
		//function Show_Products($ret_prod,$tot_cnt,$start_var,$catname,$catgroupid,$displaytype,$show_image,$show_title,$show_desc,$show_price,$show_rating,$show_bonus)
		function Show_Products($ret_prod,$tot_cnt,$start_var,$shop_det)
		{
			global $db,$Settings_arr,$Captions_arr,$inlineSiteComponents,$compare_button_displayed,$inlineSiteComponents;
			$showqty				= $Settings_arr['show_qty_box'];// show the qty box
			$prodsort_by			= ($_REQUEST['shopdet_sortby'])?$_REQUEST['shopdet_sortby']:$Settings_arr['productshop_orderfield'];
		  	$prodperpage			= ($_REQUEST['shopdet_prodperpage'])?$_REQUEST['shopdet_prodperpage']:$Settings_arr['product_maxcntperpage_shops'];// product per page
			$prodsort_order			= ($_REQUEST['shopdet_sortorder'])?$_REQUEST['shopdet_sortorder']:$Settings_arr['productshop_orderby'];
		?>
			<tr>
				<td colspan="2" class="shelfAheader" >
				<?php echo paging_show_totalcount($tot_cnt,'Product(s)',$start_var['pg'],$start_var['pages'])?></td>
				<td align="right" width="20%" class="shelfAheader">
				<?php if(isProductCompareEnabled() && !$compare_button_displayed) {$compare_button_displayed = true; ?>
				<form name="add_to_compare" id="add_to_compare" action="" method="post" >
				<input type="hidden" name="submit_Compare_pdts" id="submit_Compare_pdts" value="1">
				<input type="hidden" name="compare_products" id="compare_products" value=""><!--<input type="submit" name="submit_Compare_pdts" value="ADD TO COMPARE" class="buttonred_large" onclick="handle_addtoCompare()" />-->	</form>		<? }?>	&nbsp;</td></tr>
			<tr>
				<td colspan="3" align="left" valign="top" class="shelfAheader">
					<table width="100%" border="0" cellpadding="0" cellspacing="0" class="productpagenavtable">
					<tr>
						<td width="63%" height="30" align="right" valign="middle" class="productpagenavtext"><?php echo $Captions_arr['SHOP_DETAILS']['SHOPDET_SORTBY']?>
                        <select name="shopdet_sortbytop" id="shopdet_sortbytop">
  						<option value='custom' <?php echo ($prodsort_by=='custom')?'selected="selected"':''?> >Default</option>
                        <option value="product_name" <?php echo ($prodsort_by=='product_name')?'selected="selected"':''?>><?php echo $Captions_arr['SHOP_DETAILS']['SHOPDET_PRODNAME']?></option>
                        <option value="price" <?php echo ($prodsort_by=='price')?'selected="selected"':''?>><?php echo $Captions_arr['SHOP_DETAILS']['SHOPDET_PRICE']?></option>
						  <option value="product_id" <?php echo ($prodsort_by=='product_id')?'selected="selected"':''?>><?php echo $Captions_arr['SHOP_DETAILS']['SHOPDET_DATEADDED']?></option>
                        </select>
                          <select name="shopdet_sortordertop" id="shopdet_sortordertop">
                            <option value="ASC" <?php echo ($prodsort_order=='ASC')?'selected="selected"':''?>><?php echo $Captions_arr['SHOP_DETAILS']['SHOPDET_LOW2HIGH']?></option>
                            <option value="DESC" <?php echo ($prodsort_order=='DESC')?'selected="selected"':''?>><?php echo $Captions_arr['SHOP_DETAILS']['SHOPDET_HIGH2LOW']?></option>
                                                    </select></td>
						<td width="37%" align="right" valign="middle" class="productpagenavtext"><?php echo $Captions_arr['SHOP_DETAILS']['SHOPDET_ITEMSPERPAGE']?>
						<?php
						//if(!$_REQUEST['shopdet_prodperpage']){
						//$shopdet_prodperpage = $Settings_arr['product_maxcntperpage'];
						//}else{
						//$shopdet_prodperpage = $_REQUEST['shopdet_prodperpage'];
						//}
						?>
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
						<input type="button" name="submit_Page" value="<?php echo $Captions_arr['SHOP_DETAILS']['SHOPDET_GO']?>" class="buttonred" onclick="handle_shopdetailsdropdownval_sel('<?php echo url_shops($_REQUEST['shop_id'],$shop_det['shopbrand_name'],4)?>','shopdet_sortbytop','shopdet_sortordertop','shopdet_prodperpagetop')" />
						</td>
					</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="3">
				<?php
						// ** Showing the products listing either 1 in a row or 3 in a row
						$pg_variable = 'shopdet_pg';
						// Calling the function to get the type of image to shown for current 
						$pass_type = get_default_imagetype('subshop_prod');
						$prod_compare_enabled = isProductCompareEnabled();
						switch($shop_det['shopbrand_product_displaytype'])
						{
							case '1row': // case of one in a row for normal
							?>
								<table border="0" cellpadding="0" cellspacing="0" class="shelfBtable">
								<?php
									if ($tot_cnt>0)
									{
									?>
										<tr>
											<td colspan="3" class="pagingcontainertd" align="center">
											<?php 
												$path = '';
												$pageclass_arr['container'] = 'pagenavcontainer';
												$pageclass_arr['navvul']	= 'pagenavul';
												$pageclass_arr['current']	= 'pagenav_current';
												
												$query_string = "&amp;shopdet_sortby=".$prodsort_by.'&amp;shopdet_sortorder='.$prodsort_order.'&amp;shopdet_prodperpage='.$prodperpage;
												paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr); 	
											?>	
											</td>
										</tr>
									<?php
									}
									while($row_prod = $db->fetch_array($ret_prod))
									{
									$prodcur_arr[] = $row_prod;
								?>
										<tr onmouseover="this.className='normalshelf_hover'" onmouseout="this.className='shelfBtabletd'">
											<td align="left" valign="middle" class="shelfBtabletd">
														<? if($shop_det['shopbrand_product_showtitle']==1)// whether title is to be displayed
														 {?>
														<h1 class="shelfBprodname"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h1>
														<? }?>
														<? 
														if ($shop_det['shopbrand_product_showshortdescription']==1)// Check whether description is to be displayed
														{?>
														<h6 class="shelfBproddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6>
														<? }?>
														<?php
														if($row_prod['product_saleicon_show']==1)
														{
															$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
															if($desc!='')
															{
																?>	
																<div class="mid_shlf2_pdt_sale"><?php echo $desc?></div>
																<?php
															}
														}
														if($row_prod['product_newicon_show']==1)
														{
															$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
															if($desc!='')
															{
																?>
																<div class="mid_shlf2_pdt_newsale"><?php echo $desc?></div>
																<?php
															}
														}
														if($shop_det['shopbrand_product_showrating']==1)
														{
															$module_name = 'mod_product_reviews';
															if(in_array($module_name,$inlineSiteComponents))
															{
																if($row_prod['product_averagerating']>=0)
																{
															?>
																<div class="mid_shlf2_free_star">
																<?php
																	display_rating($row_prod['product_averagerating']);
																	?>
																</div>
															<?php
																}
															}
														}	
													?>
											</td>
											<td align="center" valign="middle" class="shelfBtabletd">
											<?
											if ($shop_det['shopbrand_product_showimage']==1)// Check whether description is to be displayed 
											{?>
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
													<? 
													}
													?>
													<?php if($prod_compare_enabled)  { 
												dislplayCompareButton($row_prod['product_id']);
											 }
											 ?>
											</td>
											<td align="left" valign="middle" class="shelfBtabletd">
											<?php //$show_title,$show_desc,
											if ($shop_det['shopbrand_product_showprice']==1)// Check whether description is to be displayed
											{
												$price_class_arr['ul_class'] 		= 'shelfBul';
												$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
												$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
												$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
												$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
												echo show_Price($row_prod,$price_class_arr,'shopbrand_1');
												show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
												//show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points
											}	
											    if($shop_det['shopbrand_product_showbonuspoints']==1)
												{
													$pass_arr['main_cls'] 		= 'bonus_point';
													$pass_arr['caption_cls'] 	= 'bonus_point_caption';
													$pass_arr['point_cls'] 		= 'bonus_point_number';
													show_bonus_points_msg_multicolor($row_prod,$pass_arr);
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
															$class_arr['ADD_TO_CART']	= 'quantity_infolink';
															$class_arr['PREORDER']		= 'quantity_infolink';
															$class_arr['ENQUIRE']		= 'quantity_infolink';
															show_addtocart($row_prod,$class_arr,$frm_name)
														?>
														</div>
													</div>
													</form>
													<?php
														if($row_prod['product_bulkdiscount_allowed']=='Y' || $row_prod['product_freedelivery']==1)
														{
															?>
															<div class="mid_shlf2_free_div">
															<?php
															if($row_prod['product_bulkdiscount_allowed']=='Y')
															{
																?>
																<img src="<?php url_site_image('bulk-dis.gif')?>" alt="Bulk Discount"/>
																<?php
															}
															if($row_prod['product_freedelivery']==1)
															{	
																?>
																<img src="<?php url_site_image('free-deli.gif')?>" alt="Free Delivery"/>
																<?php
															}
															?>
															</div>
															<?php
														}
														?>
											</td>
									</tr>
								<?php
									}
								?>	
								<?php
									if ($tot_cnt>0)
									{
									?>
										<tr>
											<td colspan="3" class="pagingcontainertd" align="center">
											<?php 
												$path = '';
												$pageclass_arr['container'] = 'pagenavcontainer';
												$pageclass_arr['navvul']	= 'pagenavul';
												$pageclass_arr['current']	= 'pagenav_current';
												
												$query_string = "&amp;shopdet_sortby=".$prodsort_by.'&amp;shopdet_sortorder='.$prodsort_order.'&amp;shopdet_prodperpage='.$prodperpage;
												paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr); 	
											?>	
											</td>
										</tr>
									<?php
									}
									?>
								</table>
							<?php
							break;
							case '3row': // case of three in a row for normal
							?>
								<table border="0" cellpadding="0" cellspacing="0" class="shelfAtable">
								<?php
								if ($tot_cnt>0)
								{
								?>
									<tr>
										<td colspan="3" class="pagingcontainertd" align="center">
										<?php 
											$path = '';
											//show_paging($path,$_REQUEST[$pg_variable],$start_arr['counterstart'],$tot_cnt,$prodperpage,$pg_variable,'')
												$pageclass_arr['container'] = 'pagenavcontainer';
												$pageclass_arr['navvul']	= 'pagenavul';
												$pageclass_arr['current']	= 'pagenav_current';
												
												$query_string = "&amp;shopdet_sortby=".$_REQUEST['shopdet_sortby'].'&amp;shopdet_sortorder='.$_REQUEST['shopdet_sortorder'].'&amp;shopdet_prodperpage='.$_REQUEST['shopdet_prodperpage'];
												paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr,0); 	
										?>	
										</td>
									</tr>
								<?php
								}
								?>	
								<tr>
								<?php
									$max_col = 3;
									$cur_col = 0;
									$prodcur_arr = array();
									while($row_prod = $db->fetch_array($ret_prod))
									{
										$prodcur_arr[] = $row_prod;
										//##############################################################
										// Showing the title, description and image part for the product
										//##############################################################
								?>
										<td class="shelfAtabletd" align="left" valign="top"  onmouseover="this.className='normalshelf_hover'" onmouseout="this.className='shelfAtabletd'">
											<ul class="shelfBul">
												<li>
												<? if($shop_det['shopbrand_product_showtitle']==1)// whether title is to be displayed
												 {?>
												<h1 class="shelfAprodname"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h1></li>
												<? }?>
												<li>
												<?php
												if ($shop_det['shopbrand_product_showprice']==1)// Check whether description is to be displayed
												{
													$price_class_arr['ul_class'] 		= 'shelfpriceul';
													$price_class_arr['normal_class'] 	= 'shelfAnormalprice';
													$price_class_arr['strike_class'] 	= 'shelfAstrikeprice';
													$price_class_arr['yousave_class'] 	= 'shelfAyousaveprice';
													$price_class_arr['discount_class'] 	= 'shelfAdiscountprice';
													echo show_Price($row_prod,$price_class_arr,'shopbrand_3');
													show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
													}
												?>	
												</li>
												<?php
												if($row_prod['product_saleicon_show']==1)
												{
													$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
													if($desc!='')
													{
													?>	
														<li><div class="mid_shlf2_pdt_sale"><?php echo $desc?></div></li>
													<?php
													}
												}
												?>
												<li>
												<? if ($shop_det['shopbrand_product_showimage']==1)// Check whether description is to be displayed
													{?>
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
													<? }?>
												</li>
												<?php if($prod_compare_enabled)  {	
											 		dislplayCompareButton($row_prod['product_id']);
													}
												?>
												<li>
												<?
												if ($shop_det['shopbrand_product_showshortdescription']==1)// Check whether description is to be displayed
												{?>
												<h6 class="shelfAproddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6>
												<? }?>
												<?php 
												 if($shop_det['shopbrand_product_showbonuspoints']==1)
												 {
												  	$pass_arr['main_cls'] 		= 'bonus_point';
													$pass_arr['caption_cls'] 	= 'bonus_point_caption';
													$pass_arr['point_cls'] 		= 'bonus_point_number';
													show_bonus_points_msg_multicolor($row_prod,$pass_arr);
												 }
												?>
												</li>
												<?
															if($row_prod['product_bulkdiscount_allowed']=='Y' || $row_prod['product_freedelivery']==1)
															{
																?>
																<li><div class="mid_shlf2_free_div">
																<?php
																if($row_prod['product_bulkdiscount_allowed']=='Y')
																{
																	?>
																	<img src="<?php url_site_image('bulk-dis.gif')?>" alt="Bulk Discount"/>
																	<?php
																}
																if($row_prod['product_freedelivery']==1)
																{	
																	?>
																	<img src="<?php url_site_image('free-deli.gif')?>" alt="Free Delivery"/>
																	<?php
																}
																?>
																</div></li>
																<?php
															}
												?>
											</ul>
											<?php
														if($shop_det['shopbrand_product_showrating']==1)
														{
															$module_name = 'mod_product_reviews';
															if(in_array($module_name,$inlineSiteComponents))
															{
																if($row_prod['product_averagerating']>=0)
																{
															?>
																<div class="mid_shlf2_free_star">
																<?php
																	display_rating($row_prod['product_averagerating']);
																	?>
																</div>
															<?php
																}
															}
														}	
														/*if($row_prod['product_saleicon_show']==1)
														{
															$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
															if($desc!='')
															{
																?>	
																<div class="mid_shlf2_pdt_sale"><?php echo $desc?></div>
																<?php
															}
														}*/
														if($row_prod['product_newicon_show']==1)
														{
															$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
															if($desc!='')
															{
																?>
																<div class="mid_shlf2_pdt_newsale"><?php echo $desc?></div>
																<?php
															}
														}
														?>
										</td>
									<?php
										$cur_col++;
										if ($cur_col>=$max_col)
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
												$frm_name = uniqid('shopdet_');
											?>
												<td class="shelfAtabletdA">
												
													<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $prod_arr['product_id']?>)">
													<input type="hidden" name="fpurpose" value="" />
													<input type="hidden" name="fproduct_id" value="" />
													<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
													<input type="hidden" name="fproduct_url" value="<?php url_product($prod_arr['product_id'],$prod_arr['product_name'])?>" />
													<div class="infodiv">
														<div class="infodivleft"><?php show_moreinfo($prod_arr,'infolink')?></div>
														<div class="infodivright">
														<?php
															$class_arr 					= array();
															$class_arr['ADD_TO_CART']	= 'quantity_infolink';
															$class_arr['PREORDER']			= 'quantity_infolink';
															$class_arr['ENQUIRE']			= 'quantity_infolink';
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
										}
									}
									// If in case total product is less than the max allowed per row then handle that situation
									if ($cur_col<$max_col)
									{
										echo "<td colspan='".($max_col-$cur_col)."'>&nbsp;</td></tr><tr>";
										$cur_tempcol = $cur_col = 0;
										//##############################################################
										// Done to handle the case of showing the qty, add to cart and more info links
										// in case if total product is less than the max allower per row.
										//##############################################################
										foreach($prodcur_arr as $k=>$prod_arr)
										{
											$frm_name = uniqid('shopdet_');
										?>
											<td class="shelfAtabletdA">
											
												<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $prod_arr['product_id']?>)">
												<input type="hidden" name="fpurpose" value="" />
												<input type="hidden" name="fproduct_id" value="" />
												<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
												<input type="hidden" name="fproduct_url" value="<?php url_product($prod_arr['product_id'],$prod_arr['product_name'])?>" />
												<div class="infodiv">
													<div class="infodivleft"><?php show_moreinfo($prod_arr,'infolink')?></div>
													<div class="infodivright">
													<?php
														$class_arr 					= array();
														$class_arr['ADD_TO_CART']	= 'quantity_infolink';
														$class_arr['PREORDER']		= 'quantity_infolink';
														$class_arr['ENQUIRE']		= 'quantity_infolink';
														show_addtocart($prod_arr,$class_arr,$frm_name)
													?>
													</div>
												</div>
												</form>
											</td>
								<?php
											++$cur_tempcol;
											if ($cur_tempcol>=$max_col)
											{
												echo "</tr><tr>";
												$cur_tempcol=0;
											}
										}
										echo "<td colspan='".($max_col-$cur_tempcol)."'>&nbsp;</td></tr>";
									}
									else
										echo "</tr>";
									$prodcur_arr = array();
									?>
									<?php
								if ($tot_cnt>0)
								{
								?>
									<tr>
										<td colspan="3" class="pagingcontainertd" align="center">
										<?php 
											$path = '';
											//show_paging($path,$_REQUEST[$pg_variable],$start_arr['counterstart'],$tot_cnt,$prodperpage,$pg_variable,'')
												$pageclass_arr['container'] = 'pagenavcontainer';
												$pageclass_arr['navvul']	= 'pagenavul';
												$pageclass_arr['current']	= 'pagenav_current';
												
												$query_string = "&amp;shopdet_sortby=".$_REQUEST['shopdet_sortby'].'&amp;shopdet_sortorder='.$_REQUEST['shopdet_sortorder'].'&amp;shopdet_prodperpage='.$_REQUEST['shopdet_prodperpage'];
												paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,$Captions_arr['SHOP_DETAILS']['SHOPDET_PRODUCTS'],$pageclass_arr,0); 	
										?>	
										</td>
									</tr>
								<?php
								}
								?>	
								</table>
						<?php		
							break;
						};
				?>
				</td>
			</tr>
			<tr>
				<td colspan="3" align="left" valign="top" class="shelfAheader">
					<table width="100%" border="0" cellpadding="0" cellspacing="0" class="productpagenavtable">
					<tr>
						<td width="63%" height="30" align="right" valign="middle" class="productpagenavtext">
						<?php echo $Captions_arr['SHOP_DETAILS']['shopdet_sortby'];?>
						<select name="shopdet_sortbybottom" id="shopdet_sortbybottom">
							<option value='custom'  <?php echo ($prodsort_by=='')?'selected="selected"':''?> >Default</option>
                            <option value="product_name" <?php echo ($prodsort_by=='product_name')?'selected="selected"':''?>><?php echo $Captions_arr['SHOP_DETAILS']['SHOPDET_PRODNAME']?></option>
                            <option value="price" <?php echo ($prodsort_by=='price')?'selected="selected"':''?>><?php echo $Captions_arr['SHOP_DETAILS']['SHOPDET_PRICE']?></option>
						  <option value="product_id" <?php echo ($prodsort_by=='product_id')?'selected="selected"':''?>><?php echo $Captions_arr['SHOP_DETAILS']['SHOPDET_DATEADDED']?></option>
                      </select>
                          <select name="shopdet_sortorderbottom" id="shopdet_sortorderbottom">
                            <option value="ASC" <?php echo ($prodsort_order=='ASC')?'selected="selected"':''?>><?php echo $Captions_arr['SHOP_DETAILS']['SHOPDET_LOW2HIGH']?></option>
                            <option value="DESC" <?php echo ($prodsort_order=='DESC')?'selected="selected"':''?>><?php echo $Captions_arr['SHOP_DETAILS']['SHOPDET_HIGH2LOW']?></option>
                          </select></td>
						<td width="37%" align="right" valign="middle" class="productpagenavtext"><?php echo $Captions_arr['SHOP_DETAILS']['SHOPDET_ITEMSPERPAGE']?>
						<select name="shopdet_prodperpagebottom" id="shopdet_prodperpagebottom">
						<?php
							for ($ii=$Settings_arr["productlist_interval"];$ii<=$Settings_arr["productlist_maxval"];$ii+=$Settings_arr["productlist_interval"])
							{
						?>
								<option value="<?php echo $ii?>" <?php echo ($prodperpage==$ii)?'selected="selected"':''?>><?php echo $ii?></option>
						<?php	
							}
						?>
						</select>
						<input type="button" name="submit_Page" value="<?php echo $Captions_arr['SHOP_DETAILS']['SHOPDET_GO']?>" class="buttonred" onclick="handle_shopdetailsdropdownval_sel('<?php echo url_shops($_REQUEST['shop_id'],$catname,4)?>','shopdet_sortbybottom','shopdet_sortorderbottom','shopdet_prodperpagebottom')" />
						</td>
					</tr>
					</table>
				</td>
			</tr>
		<?php
		}
		
		// ** Function to show the no products message
		function Show_NoProducts()
		{
			global $Captions_arr;
		?>
			<table border="0" cellpadding="0" cellspacing="0" class="shelfBtable">
        	<tr>
	          	<td colspan="3" class="shelfBheader"><?php echo $Captions_arr['SHOP_DETAILS']['NO_PROD']?></td>
          	</tr>
           	<tr>
        	 	<td align="left" valign="middle" class="shelfBtabletd"><h1 class="shelfBprodname" ><?php echo $Captions_arr['SHOP_DETAILS']['NO_PROD_MSG']?></h1>
			 	</td>
			</tr>	 
			</table>
		<?php	
		}
	};	
?>