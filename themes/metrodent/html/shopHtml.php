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
			document.getElementById('bw').innerHTML = '<iframe src="http://www.web2pdfconvert.com/convert.aspx?cURL=http://<?=$_SERVER['HTTP_HOST']?><?=$_SERVER['REQUEST_URI']?>&outputmode=stream&allowactivex=yes&ref=form">';
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
				  /*
				  if($_REQUEST['shop_id'])
				  {
				  ?>
				    <div class="subcat_hdr_icons">
					 <a href="javascript:download_pdf_stream('<?=$_SERVER['HTTP_HOST']?>','<?=$_SERVER['REQUEST_URI']?>')" title="<?php echo stripslash_normal($Captions_arr['SHOP_DETAILS']['SHOPDET_DOWNLOADPDF'])?>">
				  	 <img src="<?php url_site_image('list-pdf.gif')?>" alt="<?php echo stripslash_normal($Captions_arr['SHOP_DETAILS']['SHOPDET_DOWNLOADPDF'])?>" border="0" /></a>
					</div>
				  <?
				  }
				  */ 
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
					?>
					<form method="post" name="frm_shopdetails" id="frm_shopdetails" action="" class="frm_cls">
		            <input type="hidden" name="fpurpose" value="" />
		            		            <input type="hidden" name="pageval" value="" />

					<?php
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
				?>
				</form>
				<?php	
			}				
		?>
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
				/*
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
				*/ 
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
						$cnt++;  
						if($cnt==3)
						{  
					    ?>
						 <div class="sub_cat_otr_in_a">
						 <?php
						 }
						 else
						 {
						?>
						 <div class="sub_cat_otr_in">
						 <?php
						 }
							 ?>
						<div class="sub_cat_otr_img">  <a href="<?php url_category($row_subshop['shopbrand_id'],$row_subshop['shopbrand_name'],-1)?>" class="subcategoreynamelink" title="<?php echo stripslash_normal($row_subshop['shopbrand_name'])?>">
								 <?php	
								 $pass_type = get_default_imagetype('subshop');								
									if ($row_subshop['shopbrand_showimageofproduct']==0) // Case to check for images directly assigned to category
									{
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
										$cur_prodid = find_AnyProductWithImageUnderCategory($row_subshop['shopbrand_id']);
										if ($cur_prodid)// case if any product with image assigned to it under current category exists
										{
											// Calling the function to get the image to be shown
											$img_arr = get_imagelist('prod',$cur_prodid,$pass_type,0,0,1);
											
											if(count($img_arr))
											{
												show_image(url_root_image($img_arr[0][$pass_type],1),$row_subshop['shopbrand_name'],$row_subshop['shopbrand_name']);
												$show_noimage = false;
											}
											else 
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
								</a></div>
         <div class="sub_cat_otr_name"><a href="<?php url_category($row_subshop['shopbrand_id'],$row_subshop['shopbrand_name'],-1)?>" title="<?php echo stripslash_normal($row_subshop['shopbrand_name'])?>"><?php echo stripslash_normal($row_subshop['shopbrand_name'])?></a></div>
         </div>
         <?php	
					}					
				?>	
				<?php
				/*
			 	    if($fetch_arr_admin['general_shopsall_bottomcontent']!='' && $shopid==0)
					{
						?>
						<div class="mid_shlf2_desc" ><?php echo $fetch_arr_admin['general_shopsall_bottomcontent']?>
							</div>		
						<?php
					}
					*/ 
					?>
			  </div>
				
		<?php
		}
		
		// ** Function to list the products
		function Show_Products($ret_prod,$tot_cnt,$start_var,$shop_det)
		{
			global $db,$Settings_arr,$Captions_arr,$inlineSiteComponents,$compare_button_displayed;
			$showqty				= $Settings_arr['show_qty_box'];// show the qty box
			$prodsort_by			= ($_REQUEST['shopdet_sortby'])?$_REQUEST['shopdet_sortby']:$Settings_arr['productshop_orderfield'];
		  	$prodperpage			= ($_REQUEST['shopdet_prodperpage'])?$_REQUEST['shopdet_prodperpage']:$Settings_arr['product_maxcntperpage_shops'];// product per page
			$prodsort_order			= ($_REQUEST['shopdet_sortorder'])?$_REQUEST['shopdet_sortorder']:$Settings_arr['productshop_orderby'];
					 $pg_variable = 'shopdet_pg';

		?>
			        <div class="subcat_nav_content">
		<div class="subcat_nav_bottom">

		<div class="subcat_nav_pdt_no"><span></span><?php echo paging_show_totalcount($tot_cnt,'Products',$start_var['pg'],$start_var['pages'])?></span></div>
		<div class=" page_nav_cont">
		<div class="navtxt"><?php echo stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_SORTBY'])?></div>
		<div class="navselect"><select name="shopdet_sortby" id="shopdet_sortby">
  						<option value='custom' <?php echo ($prodsort_by=='custom')?'selected="selected"':''?> >Default</option>
                        <option value="product_name" <?php echo ($prodsort_by=='product_name')?'selected="selected"':''?>><?php echo stripslash_normal($Captions_arr['SHOP_DETAILS']['SHOPDET_PRODNAME'])?></option>
                        <option value="price" <?php echo ($prodsort_by=='price')?'selected="selected"':''?>><?php echo stripslash_normal($Captions_arr['SHOP_DETAILS']['SHOPDET_PRICE'])?></option>
						  <option value="product_id" <?php echo ($prodsort_by=='product_id')?'selected="selected"':''?>><?php echo stripslash_normal($Captions_arr['SHOP_DETAILS']['SHOPDET_DATEADDED'])?></option>
                        </select>
					<select name="shopdet_sortorder" id="shopdet_sortorder">
                            <option value="ASC" <?php echo ($prodsort_order=='ASC')?'selected="selected"':''?>><?php echo stripslash_normal($Captions_arr['SHOP_DETAILS']['SHOPDET_LOW2HIGH'])?></option>
                            <option value="DESC" <?php echo ($prodsort_order=='DESC')?'selected="selected"':''?>><?php echo stripslash_normal($Captions_arr['SHOP_DETAILS']['SHOPDET_HIGH2LOW'])?></option>
                                                    </select>							
		</div>
		</div>
		<div class=" page_nav_contA">
		<div class="navtxt"><?php echo stripslash_normal($Captions_arr['SHOP_DETAILS']['SHOPDET_ITEMSPERPAGE'])?></div>
		<div class="navselect"><?php
			if(!$_REQUEST['shopdet_prodperpage']){
			$prodperpage = $Settings_arr['product_maxcntperpage'];
			}else{
			$prodperpage = $_REQUEST['shopdet_prodperpage'];
			}
			?>
			<select name="shopdet_prodperpage" id="shopdet_prodperpage">
						<?php
							for ($ii=$Settings_arr["productlist_interval"];$ii<=$Settings_arr["productlist_maxval"];$ii+=$Settings_arr["productlist_interval"])
							{
						?>
								<option value="<?php echo $ii?>" <?php echo ($prodperpage==$ii)?'selected="selected"':''?>><?php echo $ii?></option>
						<?php	
							}
						?>
						</select>
		</div>
		</div>
		<div class=" page_nav_contB">
			<input type="button" name="submit_Page" value="<?php echo stripslash_normal($Captions_arr['SHOP_DETAILS']['SHOPDET_GO'])?>" class="button_list_go" onclick="handle_shopdetailsdropdownval_sel('<?php echo url_shops($_REQUEST['shop_id'],$shop_det['shopbrand_name'],4)?>','shopdet_sortby','shopdet_sortorder','shopdet_prodperpage')" />
		</div>
		</div>
		<div class="page_nav_content">
		<ul>
		<?php 
		$path = '';
		$query_string .= "&shopdet_prodperpage=".$_REQUEST['shopdet_prodperpage']."&shopdet_sortby=".$_REQUEST['shopdet_sortby']."&shopdet_sortorder=".$_REQUEST['shopdet_sortorder'];
		$paging = paging_footer_advanced($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],$prodperpage,$pg_variable,'Products',$pageclass_arr,3); 	
		echo $paging['navigation']['start_nav'].$paging['navigation']['page_no'].$paging['navigation']['end_nav'];
		?>
		</ul>
		</div>                                
		</div>		
			<?php
			// ** Showing the products listing either 1 in a row or 3 in a row
			$pass_type = get_default_imagetype('subshop_prod');
			$prod_compare_enabled = isProductCompareEnabled();
					while($row_prod = $db->fetch_array($ret_prod))
					{
								?>
								<div class="shlf_pdt_otr">
								<div class="shlf_pdt_l">
								<?php 
								 if($row_prod['product_saleicon_show']==1)
									{
										$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
										//if($desc!='')
										{
									?>	
											<div class="shlf_pdt_l_spcl"><img src="<?php echo url_site_image('new-sale.gif')?>" /></div>
									<?php
										}
									}										
									else if($row_prod['product_newicon_show']==1)
									{
										$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
										//if($desc!='')
										{
									?>
											<div class="shlf_pdt_l_spcl"><img src="<?php echo url_site_image('new-product.gif')?>" /></div>
									<?php
										}
									}
									else if($row_prod['product_discount'] > 0)
									{
										?>
										<div class="shlf_pdt_l_spcl"><img src="<?php echo url_site_image('special-offer.gif')?>" /></div>
										<?php									  
									}
									else 
									{
										?>
										<div class="shlf_pdt_l_spcl_null"></div>
										<?php									  
									}		
									?>
								<div class="shlf_pdt_l_otr">
								<?php		
						      if($shop_det['shopbrand_product_showtitle']==1)// whether title is to be displayed
									{
									?>	
										<div class="shlf_pdt_name"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslash_normal($row_prod['product_name'])?>"><?php echo stripslash_normal($row_prod['product_name'])?></a></div> 

									<?php
								}
						if ($shop_det['shopbrand_product_showshortdescription']==1)// Check whether description is to be displayed
									{
										?>
										<div class="shlf_pdt_des"><?php echo stripslash_normal($row_prod['product_shortdesc']);?></div>
										<?php
									}
									?>
										<div class="shlf_pdt_price">

									<?php
						    if ($shop_det['shopbrand_product_showprice']==1)// Check whether description is to be displayed
									{ 
										$price_class_arr['class_type'] 		= 'div';

										$price_class_arr['normal_class'] 	= 'normal_shlfA_pdt_priceA';
								$price_class_arr['strike_class'] 	= 'normal_shlfA_pdt_priceB';
								$price_class_arr['yousave_class'] 	= 'normal_shlfA_pdt_priceC';
								$price_class_arr['discount_class'] 	= 'normal_shlfA_pdt_priceC';
										
										echo show_Price($row_prod,$price_class_arr,'shelfcenter_1');
									}
									?>

								</div> 
								</div> 
								<div class="shlf_pdt_buy"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" class="more_info_b">More Info</a></div> 
								</div> 
								<div class="shlf_pdt_r">	<?php
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
								<?php 
									}
									?></div> 
								</div>
								<? 
					}
					?>
					</div>
				<?php			
			if ($tot_cnt>0)
			{
			?>
				<div class="subcat_nav_content">
				<div class="subcat_nav_bottom">
				<div class="subcat_nav_pdt_no"><span></span><?php echo paging_show_totalcount($tot_cnt,'Products',$start_var['pg'],$start_var['pages'])?></span></div>
				</div>
				<div class="page_nav_content">
				<ul>
				<?php 
				$path = '';
				$query_string .= "";
				$paging = paging_footer_advanced($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],$prodperpage,$pg_variable,'Products',$pageclass_arr); 	
				echo $paging['navigation']['start_nav'].$paging['navigation']['page_no'].$paging['navigation']['end_nav'];
				?>
				</ul>
				</div>                                
				</div>			
		<?
			}							
			
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
