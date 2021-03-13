<?php
	/*############################################################################
	# Script Name 	: ShopHtml.php
	# Description 	: Page which holds the display logic for category details
	# Coded by 		: LSH
	# Created on	: 16-Jan-2009
	# Modified by	: Sny
	# Modified On	: 28-Apr-2008
	##########################################################################*/
	class shop_Html
	{
		// Defining function to show the selected Shop details
		function Show_ShopDetails($ret_shop='')
		{
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$ecom_themename,$Settings_arr,$ecom_themeid,$default_layout,$inlineSiteComponents,$Settings_arr;
			if(check_IndividualSslActive())
			{
				$ecom_selfhttp = "https://";
			}
			else
			{
				$ecom_selfhttp = "http://";
			}
			$Captions_arr['SHOP_DETAILS'] 	= getCaptions('SHOP_DETAILS');
			// ** Fetch the category details
			if($_REQUEST['shop_id'])
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
			   $alert = stripslash_normal($Captions_arr['SHOP_DETAILS']['ADD_MSG']);
			   }
			   else if($_REQUEST['resultcat']=='removed')
			   {
			   $alert = stripslash_normal($Captions_arr['SHOP_DETAILS']['REM_MSG']);
			   }
			 } 
		?>
		 <script type="text/javascript" language="javascript">
		  function download_pdf()
		  {
			document.getElementById('bw').innerHTML = '<iframe src="http://www.web2pdfconvert.com/convert.aspx?cURL=<?=$ecom_selfhttp.$_SERVER['HTTP_HOST']?><?=$_SERVER['REQUEST_URI']?>&outputmode=stream&allowactivex=yes&ref=form">';
			show_processing();
			setTimeout('hide_processing()', 20000);
		  }
	  </script>
		<div class="treemenu">
		<ul>
            <?php 
			if($_REQUEST['shop_id'])
			{
			?>
			<li>
			<a href="<? url_link('');?>"><?=stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']);?></a> >> <a href="<? url_link('shopbybrand.html');?>"><?=stripslash_normal($Captions_arr['SHOP_DETAILS']['SHOPS_ALL'])?></a> >> <?php echo $row_shop['shopbrand_name']; ?>
			</li>
			<?php
			}
			else
			{
			?>
				  <li><a href="<? url_link('');?>"><?=stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']);?></a> &gt;&gt; </li>
				  <li><?=stripslash_normal($Captions_arr['SHOP_DETAILS']['SHOPS_ALL'])?></li>
			<?php
			}
			?>
		</ul>
		</div>
		<?php
				if($alert)
				{
			?>
					<div  class="red_msg">
						- <?php echo $alert?> -
						</div>
			<?php
				}
				if($_REQUEST['shop_id'])
				{
				?>
						<div class="cat_list_desptn">
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
				$url= url_shops($_REQUEST['shopbrand_id'],$row_shop['shopbrand_name'],'');
				echo stripslashes($row_shop['shopbrand_description'])
				?>	
				</div>
					  <?php
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
						?>
					<div class="subcat_hdr">
					<?php if($db->num_rows($ret_subshop) and $row_shop['shopbrand_subshoplisttype']=='Middle')
					{
					?>
				 <div class="subcat_hdr_nanme">
				<?php
				  	if ($db->num_rows($ret_subshop)==1)
						echo stripslash_normal($Captions_arr['SHOP_DETAILS']['SHOPDET_SUBSHOP']);
					else
						echo stripslash_normal($Captions_arr['SHOP_DETAILS']['SHOPDET_SUBSHOPS']);
				  ?>	
				  </div>
				  <? 
				  }
				  if($_REQUEST['shop_id'])
				  {
				  ?>
				    <div class="subcat_hdr_icons">
					 <a href="javascript:download_pdf_common('<?=$_SERVER['HTTP_HOST']?>','<?=$_SERVER['REQUEST_URI']?>')" title="<?php echo stripslash_normal($Captions_arr['SHOP_DETAILS']['SHOPDET_DOWNLOADPDF'])?>">
				  	 <img src="<?php url_site_image('list-pdf.gif')?>" alt="<?php echo stripslash_normal($Captions_arr['SHOP_DETAILS']['SHOPDET_DOWNLOADPDF'])?>" border="0" /></a>
					</div>
				  <?
				  }
				  ?>
			   </div>
			 <?php
				$subshopshow 	    = false;
				if($_REQUEST['shop_id'])
				$prodshow		    = true;
				$subshop_exists 	= false;
				if(!$_REQUEST['shop_id'])
				$row_shop['shopbrand_subshoplisttype']='Middle';
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
					
						
						if ($db->num_rows($ret_subshop))
						{
					?>
					<?php	
									$this->Show_Subshops($ret_subshop,$url,$_REQUEST['shop_id']); // ** Calling the function to show the subcategories
									$subshop_exists = true;
					?>
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
									product_total_preorder_allowed,a.product_applytax,a.product_shortdesc,product_bonuspoints,
									a.product_stock_notification_required,a.product_alloworder_notinstock,product_variables_exists,product_variablesaddonprice_exists,
									a.product_variablecomboprice_allowed,a.product_variablecombocommon_image_allowed,a.default_comb_id,
									a.price_normalprefix,a.price_normalsuffix, a.price_fromprefix, a.price_fromsuffix,price_specialofferprefix, a.price_specialoffersuffix, 
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
					$this->Show_Products($ret_prod,$tot_cnt,$start_var,$row_shop); // ** Calling function to show the products under current category
				}
				else
				{
					if ($subshop_exists==false)// ** Show the no products only if there exists no subcategories for current product
					{
							$this->Show_NoProducts(); // ** Calling function to show the no products message
					}	
				}		
			}				
		?>
		<div align="left">
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
			</div>
			<?php 
			if (trim($row_shop['shopbrand_bottomdescription'])!='' and trim($row_shop['shopbrand_bottomdescription'])!='<br>')
			{
				$shop_bottom_desc =  stripslashes($row_shop['shopbrand_bottomdescription']);
			?>
				<div class="cat_list_desptn">
				<?php echo stripslashes($shop_bottom_desc);?>
				</div>
			<?php	
			}	
		}
		// ** Function to show the subShops
		function Show_Subshops($ret_subshop,$url,$shopid)
		{
			global $db,$inlineSiteComponents,$Captions_arr,$Settings_arr,$ecom_siteid;
			if(check_IndividualSslActive())
			{
				$ecom_selfhttp = "https://";
			}
			else
			{
				$ecom_selfhttp = "http://";
			}
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
				   $alert = stripslash_normal($Captions_arr['SHOP_DETAILS']['ADD_MSG']);
				   }
				   else if($_REQUEST['resultcat']=='removed')
				   {
				   $alert = stripslash_normal($Captions_arr['SHOP_DETAILS']['REM_MSG']);
				   }
			 } 
		?>
			  
			   <div class="subcat_list">
				<?php
				if($shopid==0)
				{
				$sql 				= "SELECT general_shopsall_topcontent,general_shopsall_bottomcontent FROM general_settings_sites_common WHERE sites_site_id=".$ecom_siteid;
		        $res_admin 			= $db->query($sql);
		        $fetch_arr_admin 	= $db->fetch_array($res_admin);
					if($fetch_arr_admin['general_shopsall_topcontent']!='')
					{
									?>
						<div class="mid_shlf2_desc" >
						  <?php echo $fetch_arr_admin['general_shopsall_topcontent']?>
						</div>
						<?php
					}
				}
				if($alert)
				{
				?>
					<div class="red_msg">
							- <?php echo $alert; ?> -
							</div>
				<?php
					}
					$max_col = 3;
					$cur_col = 0;
					while ($row_subshop = $db->fetch_array($ret_subshop))
					{
					 if($cur_col==0)
					 {
					 ?>
					  <div class="subshop_list_inner">
					  <?
					  }
					  ?>
						 <div class="subshop_div_image">
						  <?php 
						
						 if($img_support) // ** Show sub category image only if catimage module is there for the site
							{
						  ?>
						  		<div class="subshop_div_image_img">
								<a href="<?php url_shops($row_subshop['shopbrand_id'],$row_subshop['shopbrand_name'],-1)?>" class="subcategoreynamelink" title="<?php echo stripslash_normal($row_subshop['shopbrand_name'])?>">
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
									$shop_id = $row_subshop['shopbrand_id'];
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
								</div>
						  <?php
						 	} 
						  ?>
						  		 <div class="subshop_namelink"><a href="<?php url_shops($row_subshop['shopbrand_id'],$row_subshop['shopbrand_name'],-1)?>"  title="<?php echo stripslash_normal($row_subshop['shopbrand_name'])?>"><?php echo stripslash_normal($row_subshop['shopbrand_name'])?></a></div>
						</div>
				<?php
						$cur_col++;
						if ($cur_col>=$max_col)
						{
							$cur_col = 0;
							echo "</div>";
						}
					}
					if ($cur_col < $max_col && $cur_col>0)
						{
							echo "</div>";
						}
				?>	
				<?php
			 	    if($fetch_arr_admin['general_shopsall_bottomcontent']!='' && $shopid==0)
					{
						?>
						<div class="mid_shlf2_desc" ><?php echo $fetch_arr_admin['general_shopsall_bottomcontent']?>
							</div>		
						<?php
					}
					?>
			  </div>
				
		<?php
		}
		
		// ** Function to list the products
		function Show_Products($ret_prod,$tot_cnt,$start_var,$shop_det)
		{
			global $db,$Settings_arr,$Captions_arr,$inlineSiteComponents,$compare_button_displayed;
			if(check_IndividualSslActive())
			{
				$ecom_selfhttp = "https://";
			}
			else
			{
				$ecom_selfhttp = "http://";
			}
			$showqty				= $Settings_arr['show_qty_box'];// show the qty box
			$prodsort_by			= ($_REQUEST['shopdet_sortby'])?$_REQUEST['shopdet_sortby']:$Settings_arr['productshop_orderfield'];
		  	$prodperpage			= ($_REQUEST['shopdet_prodperpage'])?$_REQUEST['shopdet_prodperpage']:$Settings_arr['product_maxcntperpage_shops'];// product per page
			$prodsort_order			= ($_REQUEST['shopdet_sortorder'])?$_REQUEST['shopdet_sortorder']:$Settings_arr['productshop_orderby'];
		?>
			<div class="list_page_con">
			<div class="list_page_top"></div>
			<div class="list_page_middle">
			<div class="lst_nav">
			<ul>
			<li><?php echo stripslash_normal($Captions_arr['SHOP_DETAILS']['SHOPDET_SORTBY'])?></li>
			<li>
			<select name="shopdet_sortbytop" id="shopdet_sortbytop">
  						<option value='custom' <?php echo ($prodsort_by=='custom')?'selected="selected"':''?> >Default</option>
                        <option value="product_name" <?php echo ($prodsort_by=='product_name')?'selected="selected"':''?>><?php echo stripslash_normal($Captions_arr['SHOP_DETAILS']['SHOPDET_PRODNAME'])?></option>
                        <option value="price" <?php echo ($prodsort_by=='price')?'selected="selected"':''?>><?php echo stripslash_normal($Captions_arr['SHOP_DETAILS']['SHOPDET_PRICE'])?></option>
						  <option value="product_id" <?php echo ($prodsort_by=='product_id')?'selected="selected"':''?>><?php echo stripslash_normal($Captions_arr['SHOP_DETAILS']['SHOPDET_DATEADDED'])?></option>
                        </select>
			<select name="shopdet_sortordertop" id="shopdet_sortordertop">
                            <option value="ASC" <?php echo ($prodsort_order=='ASC')?'selected="selected"':''?>><?php echo stripslash_normal($Captions_arr['SHOP_DETAILS']['SHOPDET_LOW2HIGH'])?></option>
                            <option value="DESC" <?php echo ($prodsort_order=='DESC')?'selected="selected"':''?>><?php echo stripslash_normal($Captions_arr['SHOP_DETAILS']['SHOPDET_HIGH2LOW'])?></option>
                                                    </select>
			</li>	
			<li>
			<?php echo stripslash_normal($Captions_arr['SHOP_DETAILS']['SHOPDET_ITEMSPERPAGE'])?>
			</li>
			<li>
			<?php
			if(!$_REQUEST['shopdet_prodperpage']){
			$prodperpage = $Settings_arr['product_maxcntperpage'];
			}else{
			$prodperpage = $_REQUEST['shopdet_prodperpage'];
			}
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
			<input type="button" name="submit_Page" value="<?php echo stripslash_normal($Captions_arr['SHOP_DETAILS']['SHOPDET_GO'])?>" class="button_list_go" onclick="handle_shopdetailsdropdownval_sel('<?php echo url_shops($_REQUEST['shop_id'],$shop_det['shopbrand_name'],4)?>','shopdet_sortbytop','shopdet_sortordertop','shopdet_prodperpagetop')" />
			</li>
			</ul>
			</div>
			<div class="pagingcontainertd" >
			<div class="pro_nav_pages" align="right"><?php echo paging_show_totalcount($tot_cnt,'Product(s)',$start_var['pg'],$start_var['pages'])?></div>
		<div class="pro_nav_links" align="right">
			<?php 
			 $pg_variable = 'shopdet_pg';
				if ($tot_cnt>0)
				{
												$path = '';
												$pageclass_arr['container'] = 'pagenavcontainer';
												$pageclass_arr['navvul']	= 'pagenavul';
												$pageclass_arr['current']	= 'pagenav_current';
												
												$query_string = "&amp;shopdet_sortby=".$prodsort_by.'&amp;shopdet_sortorder='.$prodsort_order.'&amp;shopdet_prodperpage='.$prodperpage;
												paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr,0); 	
				}
				?>	
			</div>
			</div>
			</div>
	   		<div class="list_page_bottom"></div>
			</div>
			<?php
			// Calling the function to get the type of image to shown for current 
			$pass_type = get_default_imagetype('subshop_prod');
			$prod_compare_enabled = isProductCompareEnabled();
			switch($shop_det['shopbrand_product_displaytype'])
			{
				case '1row': // case of one in a row for normal
				?>
					<div class="mid_shlf_con" >
					<?php
					while($row_prod = $db->fetch_array($ret_prod))
					{
					?>
						<div class="mid_shlf_top"></div>
						<div class="mid_shlf_middle">
						<?php		
						if($shop_det['shopbrand_product_showtitle']==1)// whether title is to be displayed
						{
						?>	
							<div class="mid_shlf_pdt_name"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslash_normal($row_prod['product_name'])?>"><?php echo stripslash_normal($row_prod['product_name'])?></a></div>
						<?php
						}
						?>
						<div class="mid_shlf_mid">
						<div class="mid_shlf_pdt_image">
						<?php
						if ($shop_det['shopbrand_product_showimage']==1)// Check whether description is to be displayed
						{
							?>	
							<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslash_normal($row_prod['product_name'])?>">
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
						if($prod_compare_enabled)
						{
						?>
							<div class="mid_shlf_pdt_compare" >
							<?php	dislplayCompareButton($row_prod['product_id']);?>
							</div>
						<?php	
						}
						?>
						</div>
						</div>
						<div class="mid_shlf_pdt_des">
						<?php
						if ($shop_det['shopbrand_product_showshortdescription']==1)// Check whether description is to be displayed
						{
							echo stripslash_normal($row_prod['product_shortdesc']);
						}
						if($shop_det['shopbrand_product_showrating']==1)
						{
							$module_name = 'mod_product_reviews';
							if(in_array($module_name,$inlineSiteComponents))
							{
								if($row_prod['product_averagerating']>=0)
								{
								?>
								<div class="mid_shlf_pdt_rate">
								<?php
									display_rating($row_prod['product_averagerating']);
								?>
								</div>
								<?php
								}
							}	
						}	
						
						if($shop_det['shopbrand_product_showbonuspoints']==1)
						{
							show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points
						}	
						if($row_prod['product_bulkdiscount_allowed']=='Y')
						{
						?>
							<div class="mid_shlf_pdt_bulk"><img src="<?php url_site_image('bulk-discount.gif')?>" alt="Bulk Discount"/></div>
						<?php
						}
						if($row_prod['product_saleicon_show']==1)
						{
							$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
							if($desc!='')
							{
							?>	
								<div class="mid_shlf_pdt_sale"><?php echo $desc?></div>
							<?php
							}
						}
						if($row_prod['product_newicon_show']==1)
						{
							$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
							if($desc!='')
							{
							?>
								<div class="mid_shlf_pdt_newsale"><?php echo $desc?></div>
							<?php
							}
						}
						?>
						</div>
						<div class="mid_shlf_pdt_price">
						<?php 
						if($row_prod['product_freedelivery']==1)
						{	
						?>
							<div class="mid_shlf_free"></div>
						<?php
						}
						if ($shop_det['shopbrand_product_showprice']==1)// Check whether description is to be displayed
						{ 
							$price_class_arr['class_type'] 		= 'div';
							$price_class_arr['normal_class'] 	= 'shlf_normalprice';
							$price_class_arr['strike_class'] 	= 'shlf_strikeprice';
							$price_class_arr['yousave_class'] 	= 'shlf_yousaveprice';
							$price_class_arr['discount_class'] 	= 'shlf_discountprice';
							echo show_Price($row_prod,$price_class_arr,'shopbrand_1');
						}
						$frm_name = uniqid('shop_');
						?>	
						<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
						<input type="hidden" name="fpurpose" value="" />
						<input type="hidden" name="fproduct_id" value="" />
						<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
						<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
						<div class="mid_shlf_buy">
						<div class="mid_shlf_info_btn"><?php show_moreinfo($row_prod,'mid_shlf_info_link')?></div>
						<div class="mid_shlf_buy_btn">
						<?php
						$class_arr 					= array();
						$class_arr['ADD_TO_CART']	= 'mid_shlf_buy_link';
						$class_arr['PREORDER']		= 'mid_shlf_buy_link';
						$class_arr['ENQUIRE']		= 'mid_shlf_buy_link';
						//show_addtocart($row_prod,$class_arr,$frm_name);
						/* Code for ajax setting starts here */
						$class_arr['BTN_CLS']       = 'mid_shlf_buy_link';
						show_addtocart_v5_ajax($frm_name,$row_prod,$class_arr);
						/* Code for ajax setting ends here */
						?>
						</div>
						</div>
						</form>       
						</div>
						</div>
						<div class="mid_shlf_bottom"></div>
					<? 
					}
					?>
					</div>
				<?php
				break;
				case '2row': // case of vertical display
				?>
					<div class="mid_shlf2_con" >
					<?php
					$max_col = 2;
					$cur_col = 0;
					$prodcur_arr = array();
					while($row_prod = $db->fetch_array($ret_prod))
					{
						$prodcur_arr[] = $row_prod;
						//##############################################################
						// Showing the title, description and image part for the product
						//##############################################################
						if($cur_col == 0)
						{
							echo '<div class="mid_shlf2_con_main">';
						}
						$cur_col ++;
						
						?>
						<div class="mid_shlf2_con_pdt">
						<div class="mid_shlf2_top"></div>
						<div class="mid_shlf2_middle">
						<?php
						if($shop_det['shopbrand_product_showtitle']==1)// whether title is to be displayed
						{
						?>
							<div class="mid_shlf2_pdt_name"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslash_normal($row_prod['product_name'])?>"><?php echo stripslash_normal($row_prod['product_name'])?></a></div>
						<?php
						}
						if ($shop_det['shopbrand_product_showimage']==1)// Check whether description is to be displayed
						{
							?>
							<div class="mid_shlf2_pdt_image">
							<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslash_normal($row_prod['product_name'])?>">
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
							</div>
							<?php
						}
						?>
						<div class="mid_shlf2_free_con">
						<?php
						if($row_prod['product_freedelivery']==1)
						{	
						?>
							<div class="mid_shlf2_free"></div>
						<?php
						}
						if($row_prod['product_bulkdiscount_allowed']=='Y')
						{
						?>
							<div class="mid_shlf2_pdt_bulk"><img src="<?php url_site_image('bulk-discount.gif')?>" border="0" alt="Bulk Discount" /></div>
						<?php
						}
						?>
						</div>
						<?php
						if($prod_compare_enabled)
						{
						?>
							<div class="mid_shlf2_pdt_compare" >
							<?php	dislplayCompareButton($row_prod['product_id']);?>
							</div>
						<?php	
						}
						if ($shop_det['shopbrand_product_showshortdescription']==1)// Check whether description is to be displayed
						{
						?>
							<div class="mid_shlf2_pdt_des">
							<?php echo stripslash_normal($row_prod['product_shortdesc'])?>
							</div>
						<?php
						}
						if($shop_det['shopbrand_product_showrating']==1)
						{
							$module_name = 'mod_product_reviews';
							if(in_array($module_name,$inlineSiteComponents))
							{
								if($row_prod['product_averagerating']>=0)
								{
								?>
								<div class="mid_shlf_pdt_rate">
								<?php
									display_rating($row_prod['product_averagerating']);
								?>
								</div>
								<?php
								}
							}
						}	
						if($shop_det['shopbrand_product_showbonuspoints']==1)
						{
							show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points
						}	
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
						?>
						
						<div class="mid_shlf2_buy">
						<?php
						$frm_name = uniqid('shopdet_');
						?>	
						<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
						<input type="hidden" name="fpurpose" value="" />
						<input type="hidden" name="fproduct_id" value="" />
						<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
						<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
						<div class="mid_shlf2_info_btn"><?php show_moreinfo($row_prod,'mid_shlf2_info_link')?></div>
						<div class="mid_shlf2_buy_btn">
						<?php
						$class_arr 					= array();
						$class_arr['ADD_TO_CART']	= 'mid_shlf2_buy_link';
						$class_arr['PREORDER']		= 'mid_shlf2_buy_link';
						$class_arr['ENQUIRE']		= 'mid_shlf2_buy_link';
						//show_addtocart($row_prod,$class_arr,$frm_name);
						/* Code for ajax setting starts here */
						$class_arr['BTN_CLS']       = 'mid_shlf2_buy_link';
						show_addtocart_v5_ajax($frm_name,$row_prod,$class_arr);
						/* Code for ajax setting ends here */
						?>
						</div>
						</form>
						</div>
						<?php
						if ($shop_det['shopbrand_product_showprice']==1)// Check whether description is to be displayed
						{
							?>
							<div class="mid_shlf2_pdt_price">
							<?php
							$price_class_arr['class_type'] 		= 'div';
							$price_class_arr['normal_class'] 	= 'shlf2_normalprice';
							$price_class_arr['strike_class'] 	= 'shlf2_strikeprice';
							$price_class_arr['yousave_class'] 	= 'shlf2_yousaveprice';
							$price_class_arr['discount_class'] 	= 'shlf2_discountprice';
							echo show_Price($row_prod,$price_class_arr,'shopbrand_3');	
							?>
							</div>
							<?php
						}
						?>	
						</div>
						<div class="mid_shlf2_bottom"></div>
						</div>
						<?php
						if($cur_col>=$max_col)
						{
							$cur_col =0;
							echo "</div>";
						}
					}
					// If in case total product is less than the max allowed per row then handle that situation
					if($cur_col<$max_col)
					{
						if($cur_col!=0)
						{ 
						echo "</div>";
						} 
					}
					?>
					
					</div>
			<?php		
				break;
			};
			?>
			<div class="list_page_con">
								<div class="list_page_top"></div>
								<div class="list_page_middle">
								<div class="pagingcontainertd" >
          						<div class="pro_nav_pages" align="right"><?php echo paging_show_totalcount($tot_cnt,'Product(s)',$start_var['pg'],$start_var['pages'])?></div>
												
									<?php 
									 $pg_variable = 'shopdet_pg';
										if ($tot_cnt>0)
										{
																		$path = '';
																		$pageclass_arr['container'] = 'pagenavcontainer';
																		$pageclass_arr['navvul']	= 'pagenavul';
																		$pageclass_arr['current']	= 'pagenav_current';
																		
																		$query_string = "&amp;shopdet_sortby=".$prodsort_by.'&amp;shopdet_sortorder='.$prodsort_order.'&amp;shopdet_prodperpage='.$prodperpage;
																		paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr,0); 	
										}
									?>	
								</div>
								</div>
							   <div class="list_page_bottom"></div>
					</div>
								
			<?php
		}
		// ** Function to show the no products message
		function Show_NoProducts()
		{
			global $Captions_arr,$db,$ecom_siteid;
			$Captions_arr['SHOP_DETAILS'] 	= getCaptions('SHOP_DETAILS');
		?>
			<table border="0" cellpadding="0" cellspacing="0" class="shelfBtable">
			<?php
				if ($Captions_arr['SHOP_DETAILS']['NO_PROD'])
				{
			?>
        	<tr>
	          	<td colspan="3" class="shelfBheader"><?php echo stripslash_normal($Captions_arr['SHOP_DETAILS']['NO_PROD'])?></td>
          	</tr>
			<?php
			}
				if ($Captions_arr['SHOP_DETAILS']['NO_PROD_MSG'])
				{
			?>
				<tr>
					<td align="left" valign="middle" class="shelfBtabletd"><h2 class="shelfBprodname" ><?php echo stripslash_normal($Captions_arr['SHOP_DETAILS']['NO_PROD_MSG'])?></h2>
					</td>
				</tr>	
			<?php
			}
			?> 
			</table>
		<?php	
		}
	};	
?>
