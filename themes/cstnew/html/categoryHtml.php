<?php
	/*############################################################################
	# Script Name 	: categoryHtml.php
	# Description 	: Page which holds the display logic for category details
	# Coded by 		: Sny
	# Created on	: 07-Sep-2010
	# Modified by	: 
	# Modified On	: 
	##########################################################################*/
	class category_Html
	{
		// Defining function to show the selected category details
		function Show_CategoryDetails($row_cat)
		{
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$ecom_themename,$Settings_arr,$ecom_themeid,$default_layout,$inlineSiteComponents,$Settings_arr;
			$Captions_arr['CAT_DETAILS'] 	= getCaptions('CAT_DETAILS');
			// ** Check whether category image module is there for current site
			if(in_array('mod_catimage',$inlineSiteComponents))
			{
				$img_support = true;
			}
			else
				$img_support = false;
		   	$custom_id = get_session_var('ecom_login_customer');
			if($_REQUEST['type_cat']=='cate_root')
			{
			   if($_REQUEST['resultcat']=='added')
			   {
			   $alert = stripslash_normal($Captions_arr['CAT_DETAILS']['ADD_MSG']);
			   }
			   else if($_REQUEST['resultcat']=='removed')
			   {
			   $alert = stripslash_normal($Captions_arr['CAT_DETAILS']['REM_MSG']);
			   }
			} 
			$HTML_treemenu = $HTML_catdesc = $HTML_alert = $HTML_icons = '' ;
			if ($row_cat['category_turnoff_treemenu']==0)
			{
				$HTML_treemenu = '	<div class="tree_menu_con">
									<div class="tree_menu_top_list"></div>
									<div class="tree_menu_mid_list">
									<div class="tree_menu_content_list">
										<ul class="tree_menu">'.
											generate_tree_menu($_REQUEST['category_id'],-1,'','<li>','</li>').'
										</ul>
									</div>
									</div>
									<div class="tree_menu_bottom_list"></div>
									</div>';
	  		}
			if($alert)
			{
				$HTML_alert = '<div class="red_msg">
								- '.$alert.' -
								</div>';
			}
			if ($row_cat['category_paid_for_longdescription']=='Y' and trim($row_cat['category_paid_description'])!='' and trim($row_cat['category_paid_description'])!='<br>')
			{
				$cat_desc =   stripslash_normal($row_cat['category_paid_description']);
			}
			elseif (trim($row_cat['category_shortdescription'])!='')
			{
				$cat_desc = nl2br(stripslash_normal($row_cat['category_shortdescription']));
			}
			if ($cat_desc!='')
			{
				$HTML_catdesc = '<div class="cate_content_mid">'.$cat_desc.'</div>';
			}
			else
			{
			$HTML_catdesc = '<div class="cate_content_midC"></div>';
			}
			if($row_cat['category_turnoff_mainimage']==0)
			{
				if ($img_support) // ** Support Category Image
				{
					if ($row_cat['category_showimageofproduct']==0) // Case to check for images directly assigned to category
					{
						if ($_REQUEST['catthumb_id'])	
							$showonly = $_REQUEST['catthumb_id'];
						else
							$showonly = 0;
						// Calling the function to get the type of image to shown for current 
						$pass_type = get_default_imagetype('category');	
						// Calling the function to get the image to be shown
						$catimg_arr = get_imagelist('prodcat',$row_cat['category_id'],$pass_type,0,$showonly,1);
						if(count($catimg_arr))
						{
							$exclude_catid 	= $catimg_arr[0]['image_id']; // exclude id in case of multi images for category
							$HTML_image = '<div class="cat_main_image">'.show_image(url_root_image($catimg_arr[0][$pass_type],1),$row_cat['category_name'],$row_cat['category_name'],'imgwraptext','',1).'</div>';
						}
					}
					else // Case of check for the first available image of any of the products under this category
					{
						// Calling the function to get the id of products under current category with image assigned to it
						$cur_prodid = find_AnyProductWithImageUnderCategory($_REQUEST['category_id']);
						if ($cur_prodid)// case if any product with image assigned to it under current category exists
						{
							// Calling the function to get the type of image to shown for current 
							$pass_type = get_default_imagetype('category');	
							// Calling the function to get the image to be shown
							$img_arr = get_imagelist('prod',$cur_prodid,$pass_type,0,0,1);
							
							if(count($img_arr))
							{
								$HTML_image = '<div class="cat_main_image">'.show_image(url_root_image($img_arr[0][$pass_type],1),$row_cat['category_name'],$row_cat['category_name'],'imgwraptext','',1).'</div>';
							}
						}
					}	
				}
			}
			$url= url_category($_REQUEST['category_id'],$row_cat['category_name'],'');
			/*
			if($custom_id)
			{
				$sql_cat_det = "SELECT customer_customer_id FROM customer_fav_categories WHERE sites_site_id=$ecom_siteid AND categories_categories_id=".$_REQUEST['category_id'] ." AND customer_customer_id=$custom_id LIMIT 1";
				$ret_num_cat= $db->query($sql_cat_det);
			 	if($db->num_rows($ret_num_cat)==0) 
				{ 
					$HTML_icons = '<a href="javascript:if(confirm(\''.stripslash_javascript($Captions_arr['CAT_DETAILS']['CAT_FAVOURITE_CONFIRM']).'\')) { document.frm_catedetails.fpurpose.value=\'add_favourite\';document.frm_catedetails.submit();}" title="'.stripslash_normal($Captions_arr['CAT_DETAILS']['CAT_FAVOURITE']).'"><img src="'.url_site_image('favico.gif',1).'" alt="'.stripslash_normal($Captions_arr['CAT_DETAILS']['CAT_FAVOURITE']).'" border="0" /></a>';
				}
			   	else if($db->num_rows($ret_num_cat)>0)
			   	{
					$HTML_icons ='<a href="javascript:if(confirm(\''.stripslash_javascript($Captions_arr['CAT_DETAILS']['CAT_REM_FAVOURITE_CONFIRM']).'\')){ document.frm_catedetails.fpurpose.value=\'rem_favourite\';document.frm_catedetails.submit(); }" title="'.stripslash_normal($Captions_arr['CAT_DETAILS']['CAT_REM_FAVOURITE']).'"><img src="'.url_site_image('rem_favico.gif',1).'" alt="'.stripslash_normal($Captions_arr['CAT_DETAILS']['CAT_REM_FAVOURITE']).'" border="0" /></a>';
			   	}
			}
			*/ 
			if ($row_cat['category_turnoff_pdf']==0)
			{
				$HTML_icons .= '<a  href="javascript:download_pdf_stream()" title="'.stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_DOWNLOADPDF']).'"><img src="'.url_site_image('pdfico.gif',1).'" alt="'.stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_DOWNLOADPDF']).'" border="0" /></a>';
			}
			$sql_prods 		= "SELECT count(a.products_product_id) as cnt 
										FROM 
											product_category_map a,products b
										WHERE 
											a.product_categories_category_id = ".$_REQUEST['category_id']." 
											AND a.products_product_id=b.product_id 
											AND b.product_hide='N'";
			$ret_prods		= $db->query($sql_prods);
			$row_prods      = $db->fetch_array($ret_prods);
			$num_prods      = $row_prods['cnt'];
			/*if($num_prods>0)
			{	
				$HTML_icons .= '<a href="'.url_category_rss($row_cat['category_id'],$row_cat['category_name'],1).'" title="'.stripslash_normal($Captions_arr['CAT_DETAILS']['CAT_RSS']).'"><img src="'.url_site_image('rssico.gif',1).'" alt="'.stripslash_normal($Captions_arr['CAT_DETAILS']['CAT_RSS']).'" border="0" /></a>';
			}*/
			$cat_desc = stripslashes($row_cat['category_bottom_description']);
			if ($cat_desc!='')
			{
				$HTML_catbottomdesc = '<div class="cate_content_bottom">'.
										$cat_desc.'</div>';
			}
			$subcatshow 	= false;
			$prodshow		= false;
			$subcat_exists 	= false;
			if ($row_cat['category_subcatlisttype']=='Middle' or $row_cat['category_subcatlisttype']=='Both')
				$subcatshow = true;
			// Show the products in middle only or in both.
			if ($row_cat['product_displaywhere'] == 'middle' or $row_cat['product_displaywhere'] == 'both')
				$prodshow = true;
	  ?>
	   <script type="text/javascript" language="javascript">
	  function download_pdf() 
	  {
	  	<?php /*document.getElementById('bw').innerHTML = '<iframe src="http://www.web2pdfconvert.com/convert.aspx?cURL=http://<?=$_SERVER['HTTP_HOST']?><?=$_SERVER['REQUEST_URI']?>&amp;outputmode=stream&amp;allowactivex=yes&amp;ref=form">';
		show_processing();
		setTimeout('hide_processing()', 20000);*/ ?>
	  }
	  </script>
		<form method="post" name="frm_catedetails" id="frm_catedetails" action="" class="frm_cls">
		<input type="hidden" name="fpurpose" value="" />
		<input type="hidden" name="caturl" value="<? echo $url?>" />
		<input type="hidden" name="type_cat" value="cate_root" />
		<input type='hidden' name='category_id' value="<?=$_REQUEST['category_id']?>"/>
		<?=$HTML_treemenu?>
	  	<div class="sub_cate_con">
		<div class="sub_cate_top"></div>
		<div class="sub_cate_mid">
		<div class="sub_cate_content">
		<div class="sub_cate_hdr"><h1><?php echo stripslashes($row_cat['category_name'])?></h1></div>
		<div class="sub_cate_icon"><?=$HTML_icons?></div>
		</div>
		</div>
		</div>
	  	<?php
			echo $HTML_alert;
			echo $HTML_image;
			echo $HTML_catdesc;
			if($subcatshow)
			{
				// ** Check for handling the case of caching
				$cache_exists 	= false;
				$cache_required	= false;
				$cache_type		= 'category';	
				if ($Settings_arr['enable_caching_in_site']==1)
				{
					$cache_required = true;
					if ($_REQUEST['category_id'])// Look for cache only if category id is there
					{
						$passid 		= $_REQUEST['category_id'];
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
					$sql_subcat 	= "SELECT category_id,category_name,category_showimageofproduct,default_catgroup_id,subcategory_showimagetype,category_shortdescription   
								FROM
									product_categories 
								WHERE 
									parent_id = ".$_REQUEST['category_id']." 
									AND sites_site_id = $ecom_siteid 
									AND category_hide=0
								ORDER BY
									category_order		
									";
					$ret_subcat = $db->query($sql_subcat);
					if ($db->num_rows($ret_subcat))
					{
						$this->Show_Subcategories($ret_subcat,$url,$row_cat); // ** Calling the function to show the subcategories
						$subcat_exists = true;
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
			?>
			</form>
			<?php
			
			// Check whether the grid display is activated for current category
					$sql_activated = "SELECT bootlace_grid_enable FROM product_categories WHERE category_id = ".$_REQUEST['category_id']." LIMIT 1";
					$ret_activated = $db->query($sql_activated);
					if($db->num_rows($ret_activated))
					{
						$row_activated = $db->fetch_array($ret_activated);
						if($row_activated['bootlace_grid_enable']==1)
						{
							$tot_cnt = $num_prods;
							if(trim($row_cat['product_orderfield'])!='')
							{
								$def_orderfield 	= $row_cat['product_orderfield'];
								$def_orderby		= $row_cat['product_orderby'];
							}
							else
							{
								$def_orderfield 	= $Settings_arr['product_orderfield'];
								$def_orderby		=  $Settings_arr['product_orderby'];
							}
							if ($tot_cnt>0)
							{ 
								$base_sort_by = $prodsort_by			= ($_REQUEST['catdet_sortby'])?$_REQUEST['catdet_sortby']:$def_orderfield;
								$prodperpage			= ($_REQUEST['catdet_prodperpage'])?$_REQUEST['catdet_prodperpage']:$Settings_arr['product_maxcntperpage'];// product per page
							
								switch ($prodsort_by)
								{
									case 'custom': // case of order by customer fiekd
									$prodsort_by		= 'b.product_order';
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
								$prodsort_order	= ($_REQUEST['catdet_sortorder'])?$_REQUEST['catdet_sortorder']:$def_orderby;
								$start_var 		= prepare_paging($_REQUEST['catdet_pg'],$prodperpage,$tot_cnt);
								$sql_prod_grid = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
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
											products a,product_category_map b 
										WHERE 
											b.product_categories_category_id = ".$_REQUEST['category_id']." 
											AND a.product_id = b.products_product_id 
											AND a.product_hide = 'N' 
										ORDER BY 
											$prodsort_by $prodsort_order ";
												
							$ret_prod_grid = $db->query($sql_prod_grid);		
							$this->Show_Products_grid($ret_prod_grid); // ** Calling function to show the grid display
						}	
					}
				}	
			
			if($prodshow)// Checks whether products to be shown in the middle area
			{
				$tot_cnt = $num_prods;
				if(trim($row_cat['product_orderfield'])!='')
				{
					$def_orderfield 	= $row_cat['product_orderfield'];
					$def_orderby		= $row_cat['product_orderby'];
				}
				else
				{
					$def_orderfield 	= $Settings_arr['product_orderfield'];
					$def_orderby		=  $Settings_arr['product_orderby'];
				}
				if ($tot_cnt>0)
				{ 
					$base_sort_by = $prodsort_by			= ($_REQUEST['catdet_sortby'])?$_REQUEST['catdet_sortby']:$def_orderfield;
					$prodperpage			= ($_REQUEST['catdet_prodperpage'])?$_REQUEST['catdet_prodperpage']:$Settings_arr['product_maxcntperpage'];// product per page
				
					switch ($prodsort_by)
					{
						case 'custom': // case of order by customer fiekd
						$prodsort_by		= 'b.product_order';
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
					$prodsort_order	= ($_REQUEST['catdet_sortorder'])?$_REQUEST['catdet_sortorder']:$def_orderby;
					$start_var 		= prepare_paging($_REQUEST['catdet_pg'],$prodperpage,$tot_cnt);
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
									products a,product_category_map b 
								WHERE 
									b.product_categories_category_id = ".$_REQUEST['category_id']." 
									AND a.product_id = b.products_product_id 
									AND a.product_hide = 'N' 
								ORDER BY 
									$prodsort_by $prodsort_order 
								LIMIT 
									".$start_var['startrec'].", ".$prodperpage;
					$ret_prod = $db->query($sql_prod);
					$this->Show_Products($ret_prod,$tot_cnt,$start_var,$row_cat,$base_sort_by,$prodsort_order); // ** Calling function to show the products under current category
				}
				else
				{
				}		
			}
				   
		   // Including the shelf menu to show the shelf menus assigned to current category.
			/* $sql_inline = "SELECT display_order,display_component_id,display_id,display_title 
							FROM 
								display_settings a,features b 
							WHERE 
								a.sites_site_id=$ecom_siteid 
								AND a.display_position='middle' 
								AND b.feature_allowedinmiddlesection = 1  
								AND layout_code='".$default_layout."' 
								AND a.features_feature_id=b.feature_id 
								AND b.feature_modulename='mod_shelfgroup' 
							ORDER BY 
									display_order 
									ASC";
			$ret_inline = $db->query($sql_inline); // to dispplay the shelf for the category page
			if ($db->num_rows($ret_inline))
			{
				while ($row_inline = $db->fetch_array($ret_inline))
				{
					//$modname 			= $row_inline['feature_modulename'];
					$body_dispcompid	= $row_inline['display_component_id'];
					$body_dispid		= $row_inline['display_id'];
					$body_title			= $row_inline['display_title'];
					$shelf_for_inner	= true;
					include ("includes/base_files/shelfgroup.php");
					$shelf_for_inner	= false;
				}
			}
			*/
		   
		 
		   global $shelf_for_inner;
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
			$ret_inline = $db->query($sql_inline); // to dispplay the shelf for the category page
			if ($db->num_rows($ret_inline))
			{
				while ($row_inline = $db->fetch_array($ret_inline))
				{  
					//$modname 			= $row_inline['feature_modulename'];
					$body_dispcompid	= $row_inline['display_component_id'];
					$body_dispid		= $row_inline['display_id'];
					$body_title			= $row_inline['display_title'];
					$shelf_for_inner	= true;
					include ("includes/base_files/shelf.php");
					$shelf_for_inner	= false;
				}
			}
			echo $HTML_catbottomdesc;
		}
		// ** Function to show the subcategories
		function Show_Subcategories($ret_subcat,$url,$row_cat)
		{
			global $db,$inlineSiteComponents,$Captions_arr,$Settings_arr,$ecom_siteid;
			$heading = $HTML_subcat_header = $HTML_alert = '';
			if(in_array('mod_catimage',$inlineSiteComponents))
			{
				$img_support = true;
			}
			else
				$img_support = false;
			$custom_id = get_session_var('ecom_login_customer');
			$subcategory_showimagetype = $row_cat['subcategory_showimagetype'];
			if ($db->num_rows($ret_subcat)==1)
			{
				if($Captions_arr['CAT_DETAILS']['CATDET_SUBCAT']!='')
				{
					$heading =  stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_SUBCAT']);
				}
			}
			else
			{
				if($Captions_arr['CAT_DETAILS']['CATDET_SUBCATS']!='')
				{
					$heading =  stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_SUBCATS']);
				}
			}
			if($heading!='')
			{
				$HTML_subcat_header = '<div class="subcat_header">'.$heading.'</div>';
			}	
		    if($_REQUEST['type_cat']=='sub_cat')
			{
			   if($_REQUEST['resultcat']=='added')
			   {
			   $alert = stripslash_normal($Captions_arr['CAT_DETAILS']['ADD_MSG']);
			   }
			   else if($_REQUEST['resultcat']=='removed')
			   {
			   $alert = stripslash_normal($Captions_arr['CAT_DETAILS']['REM_MSG']);
			   }
			 } 
			if($alert)
			{
				echo $HTML_alert = '<div class="red_msg">- '.$alert.' -</div>';
			}
				switch ($row_cat['category_subcatlistmethod'])
				{
					case '2row': // 2 in a row
						echo 
								'
								<div class="subcat_content">
								<div class="subcat_2row_outr">
								';
						$cur_col = 0;
						while ($row_subcat = $db->fetch_array($ret_subcat))
						{
							$HTML_subcatname = $HTML_image = $HTML_short_desc = '';
							if($cur_col ==0)
								echo '<div class="horizontal_container">';
							$cur_col++;
							if($row_cat['category_showname']==1)
							{
						  		$HTML_subcatname = '<a href="'.url_category($row_subcat['category_id'],$row_subcat['category_name'],1).'" title="'.stripslash_normal($row_subcat['category_name']).'">'.stripslash_normal($row_subcat['category_name']).'</a>';
							}
							if($row_cat['category_showimage']==1)
							{
								 $HTML_image = '<a href="'.url_category($row_subcat['category_id'],$row_subcat['category_name'],1).'" class="subcategoreynamelink" title="'.stripslash_normal($row_subcat['category_name']).'">';
									if($ecom_siteid==62)// eurolabels
										$pass_type = 'image_bigpath';
									else
										$pass_type = 'image_bigpath';
									if ($row_subcat['category_showimageofproduct']==0) // Case to check for images directly assigned to category
									{
										// Calling the function to get the image to be shown
										$img_arr = get_imagelist('prodcat',$row_subcat['category_id'],$pass_type,0,0,1);
										if(count($img_arr))
										{
											$HTML_image .= show_image(url_root_image($img_arr[0][$pass_type],1),$row_subcat['category_name'],$row_subcat['category_name'],'','',1);
											$show_noimage = false;
										}
										else
											$show_noimage = true;
									}
									else // Case of check for the first available image of any of the products under this category
									{
										// Calling the function to get the id of products under current category with image assigned to it
										$cur_prodid = find_AnyProductWithImageUnderCategory($row_subcat['category_id']);
										if ($cur_prodid)// case if any product with image assigned to it under current category exists
										{
											// Calling the function to get the image to be shown
											$img_arr = get_imagelist('prod',$cur_prodid,$pass_type,0,0,1);
											
											if(count($img_arr))
											{
												$HTML_image .= show_image(url_root_image($img_arr[0][$pass_type],1),$row_subcat['category_name'],$row_cat['category_name'],'','',1);
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
										$no_img = get_noimage('prodcat',$pass_type); 
										if ($no_img)
										{
											$HTML_image .= show_image($no_img,$row_subcat['category_name'],$row_subcat['category_name'],'','',1);
										}	
									}
								$HTML_image .= '</a>';
							}
							if($row_cat['category_showshortdesc']==1)
							{
								$HTML_short_desc = '<div class="subcat_2row_des">'.stripslash_normal($row_subcat['category_shortdescription']).'</div>';
							}
					?>
							
							<div class="subcat_2row_pdt_outr">
							<div class="subcat_2row_pdt_name"><?=$HTML_subcatname?></div>
							<div class="subcat_2row_pdt_btm">
							<div class="subcat_2row_image"><?=$HTML_image?></div>
							<?=$HTML_short_desc?>
							</div>
							</div>
							<?
							if($cur_col>=2)
							{
								echo '</div>';
								$cur_col = 0;
							}	
						}
						if($cur_col>0 and $cur_col<4)
							echo '</div>';	
						echo '
								</div>
								</div>
						';
					break;
					case '3row': // 4 in a row
						echo 
								'
								<div class="subcat_content">
								<div class="subcat_3row_outrA">
								';
						$cur_col = 0;
						while ($row_subcat = $db->fetch_array($ret_subcat))
						{
							$HTML_subcatname = $HTML_image = $HTML_short_desc = '';
							if($cur_col ==0)
								echo '<div class="horizontal_container">';
							$cur_col++;
							if($row_cat['category_showname']==1)
							{
						  		$HTML_subcatname = '<a href="'.url_category($row_subcat['category_id'],$row_subcat['category_name'],1).'" title="'.stripslash_normal($row_subcat['category_name']).'">'.stripslash_normal($row_subcat['category_name']).'</a>';
							}
							if($row_cat['category_showimage']==1)
							{
								 $HTML_image = '<a href="'.url_category($row_subcat['category_id'],$row_subcat['category_name'],1).'" class="subcategoreynamelink" title="'.stripslash_normal($row_subcat['category_name']).'">';
									$pass_type = 'image_thumbpath';
									if ($row_subcat['category_showimageofproduct']==0) // Case to check for images directly assigned to category
									{
										// Calling the function to get the image to be shown
										$img_arr = get_imagelist('prodcat',$row_subcat['category_id'],$pass_type,0,0,1);
										if(count($img_arr))
										{
											$HTML_image .= show_image(url_root_image($img_arr[0][$pass_type],1),$row_subcat['category_name'],$row_subcat['category_name'],'','',1);
											$show_noimage = false;
										}
										else
											$show_noimage = true;
									}
									else // Case of check for the first available image of any of the products under this category
									{
										// Calling the function to get the id of products under current category with image assigned to it
										$cur_prodid = find_AnyProductWithImageUnderCategory($row_subcat['category_id']);
										if ($cur_prodid)// case if any product with image assigned to it under current category exists
										{
											// Calling the function to get the image to be shown
											$img_arr = get_imagelist('prod',$cur_prodid,$pass_type,0,0,1);
											
											if(count($img_arr))
											{
												$HTML_image .= show_image(url_root_image($img_arr[0][$pass_type],1),$row_subcat['category_name'],$row_cat['category_name'],'','',1);
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
										$no_img = get_noimage('prodcat',$pass_type); 
										if ($no_img)
										{
											$HTML_image .= show_image($no_img,$row_subcat['category_name'],$row_subcat['category_name'],'','',1);
										}	
									}
								$HTML_image .= '</a>';
							}
							if($row_cat['category_showshortdesc']==1)
							{
								$HTML_short_desc = '<div class="subcat_3row_desA">'.stripslash_normal($row_subcat['category_shortdescription']).'</div>';
							}
					?>
							
							<div class="subcat_3row_pdt_outrA">
							<div class="subcat_3row_pdt_nameA"><?=$HTML_subcatname?></div>
							<div class="subcat_3row_pdt_btmA">
							<div class="subcat_3row_imageA"><?=$HTML_image?></div>
							<?=$HTML_short_desc?>
							</div>
							</div>
							<?
							if($cur_col>=3)
							{
								echo '</div>';
								$cur_col = 0;
							}	
						}
						if($cur_col>0 and $cur_col<3)
							echo '</div>';	
						echo '
								</div>
								</div>
						';
					break;
					case '4row': // 4 in a row
						echo 
								'
								<div class="subcat_content">
								<div class="subcat_3row_outr">
								';
						$cur_col = 0;
						while ($row_subcat = $db->fetch_array($ret_subcat))
						{
							$HTML_subcatname = $HTML_image = $HTML_short_desc = '';
							if($cur_col ==0)
								echo '<div class="horizontal_container">';
							$cur_col++;
							if($row_cat['category_showname']==1)
							{
						  		$HTML_subcatname = '<a href="'.url_category($row_subcat['category_id'],$row_subcat['category_name'],1).'" title="'.stripslash_normal($row_subcat['category_name']).'">'.stripslash_normal($row_subcat['category_name']).'</a>';
							}
							if($row_cat['category_showimage']==1)
							{
								 $HTML_image = '<a href="'.url_category($row_subcat['category_id'],$row_subcat['category_name'],1).'" class="subcategoreynamelink" title="'.stripslash_normal($row_subcat['category_name']).'">';
									$pass_type = 'image_thumbcategorypath';
									if ($row_subcat['category_showimageofproduct']==0) // Case to check for images directly assigned to category
									{
										// Calling the function to get the image to be shown
										$img_arr = get_imagelist('prodcat',$row_subcat['category_id'],$pass_type,0,0,1);
										if(count($img_arr))
										{
											$HTML_image .= show_image(url_root_image($img_arr[0][$pass_type],1),$row_subcat['category_name'],$row_subcat['category_name'],'','',1);
											$show_noimage = false;
										}
										else
											$show_noimage = true;
									}
									else // Case of check for the first available image of any of the products under this category
									{
										// Calling the function to get the id of products under current category with image assigned to it
										$cur_prodid = find_AnyProductWithImageUnderCategory($row_subcat['category_id']);
										if ($cur_prodid)// case if any product with image assigned to it under current category exists
										{
											// Calling the function to get the image to be shown
											$img_arr = get_imagelist('prod',$cur_prodid,$pass_type,0,0,1);
											
											if(count($img_arr))
											{
												$HTML_image .= show_image(url_root_image($img_arr[0][$pass_type],1),$row_subcat['category_name'],$row_cat['category_name'],'','',1);
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
										$no_img = get_noimage('prodcat',$pass_type); 
										if ($no_img)
										{
											$HTML_image .= show_image($no_img,$row_subcat['category_name'],$row_subcat['category_name'],'','',1);
										}	
									}
								$HTML_image .= '</a>';
							}
							if($row_cat['category_showshortdesc']==1)
							{
								$HTML_short_desc = '<div class="subcat_3row_des">'.stripslash_normal($row_subcat['category_shortdescription']).'</div>';
							}
					?>
							
							<div class="subcat_3row_pdt_outr">
							<div class="subcat_3row_pdt_name"><?=$HTML_subcatname?></div>
							<div class="subcat_3row_pdt_btm">
							<div class="subcat_3row_image"><?=$HTML_image?></div>
							<?=$HTML_short_desc?>
							</div>
							</div>
							<?
							if($cur_col>=4)
							{
								echo '</div>';
								$cur_col = 0;
							}	
						}
						if($cur_col>0 and $cur_col<4)
							echo '</div>';	
						echo '
								</div>
								</div>
						';
					break;
				};		
		}
		// ** Function to list the products
		function Show_Products($ret_prod,$tot_cnt,$start_var,$cat_det,$def_orderfield,$def_orderby)
		{
			global $db,$Settings_arr,$Captions_arr,$inlineSiteComponents,$compare_button_displayed;
			$showqty		= $Settings_arr['show_qty_box'];// show the qty box
		    $prodsort_by	= ($_REQUEST['catdet_sortby'])?$_REQUEST['catdet_sortby']:$def_orderfield;
			$prodperpage	= ($_REQUEST['catdet_prodperpage'])?$_REQUEST['catdet_prodperpage']:$Settings_arr['product_maxcntperpage'];// product per page
			$prodsort_order	= ($_REQUEST['catdet_sortorder'])?$_REQUEST['catdet_sortorder']:$def_orderby;
			$HTML_paging	= '';

			if ($tot_cnt>0)
			{
				$pg_variable				= 'catdet_pg';
				$start_var 					= prepare_paging($_REQUEST[$pg_variable],$prodperpage,$tot_cnt);
				$path 						= '';
				$pageclass_arr['container'] = 'pagenavcontainer';
				$pageclass_arr['navvul']	= 'pagenavul';
				$pageclass_arr['current']	= 'pagenav_current';
				
				$query_string 	= "&amp;catdet_sortby=".$_REQUEST['catdet_sortby'].'&amp;catdet_sortorder='.$_REQUEST['catdet_sortorder'].'&amp;catdet_prodperpage='.$_REQUEST['catdet_prodperpage'];
				$paging 		= paging_footer_advanced($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr);
				
				if($start_var['pages']>1)
				{
					$HTML_paging	= '	<div class="page_nav_con">
										<div class="page_nav_top"></div>
											<div class="page_nav_mid">
												<div class="page_nav_content">
												<ul>
												'.$paging['navigation']['start_nav'].$paging['navigation']['page_no'].$paging['navigation']['end_nav'].'
												</ul>
												</div>
											</div>
										<div class="page_nav_bottom"></div>
	    							</div>';
				}					
									
			}
			if($paging['total_cnt'])
				$HTML_totcnt = '<div class="subcat_nav_pdt_no"><span>'.$paging['total_cnt'].'</span></div>';
			$HTML_topcontent = 	'<div class="subcat_nav_content" >
								'.$HTML_totcnt.'
								<div class="subcat_nav_top"></div>
								<div class="subcat_nav_bottom">
								<div class=" page_nav_cont">
								<div class="navtxt">'.stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_SORTBY']).'</div>
								<div class="navselect">';
								$selval_arr = array (
														'custom'		=> stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_DEFAULT']),
														'product_name'	=> stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_PRODNAME']),
														'price'			=> stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_PRICE']),
														'product_id'	=> stripslash_normal($Captions_arr['CAT_DETAILS']['SHOPDET_DATEADDED']));
			$HTML_topcontent .=	generateselectbox('catdet_sortbytop',$selval_arr,$prodsort_by,'','',0,'',false,'catdet_sortbytop');
								$selord_arr = array (
														'ASC'	=> stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_LOW2HIGH']),
														'DESC'	=> stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_HIGH2LOW']) 
													);
			$HTML_topcontent .=	generateselectbox('catdet_sortordertop',$selord_arr,$prodsort_order,'','',0,'',false,'catdet_sortordertop');
			$HTML_topcontent .=	'								
								</div>
								</div>
								<div class=" page_nav_contA">
								<div  class="navtxt">'.stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_ITEMSPERPAGE']).'</div>
								<div class="navselect">';
			if(!$_REQUEST['catdet_prodperpage'])
			{
				$catdet_prodperpage = $Settings_arr['product_maxcntperpage'];
			}
			else
			{
				$catdet_prodperpage = $_REQUEST['catdet_prodperpage'];
			}
			$perpage_arr = array();
			for ($ii=$Settings_arr["productlist_interval"];$ii<=$Settings_arr["productlist_maxval"];$ii+=$Settings_arr["productlist_interval"])
				$perpage_arr[$ii] = $ii;
			$HTML_topcontent .=	generateselectbox('catdet_prodperpagetop',$perpage_arr,$catdet_prodperpage,'','',0,'',false,'catdet_prodperpagetop');
			$HTML_topcontent .= '
								</div>
								</div>
								<div class=" page_nav_contB">
								<input type="button" name="submit_Page" value="'.stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_GO']).'" class="nav_button" onclick="handle_categorydetailsdropdownval_sel(\''.url_category($_REQUEST['category_id'],stripslash_javascript($cat_det['category_name']),4,$_REQUEST['catgroup_id'],1).'\',\'catdet_sortbytop\',\'catdet_sortordertop\',\'catdet_prodperpagetop\')" />
								</div>
								</div>
								</div>';
		
			echo $HTML_topcontent;
			echo $HTML_paging;
			// ** Showing the products listing either 1 in a row or 3 in a row
			$pass_type = get_default_imagetype('prodcat');
			$comp_active = isProductCompareEnabled();
			switch($cat_det['product_displaytype'])
			{
				/*
				case '3row': // case of three in a row for normal
					$HTML_comptitle = $HTML_maindesc = $HTML_paging = $HTML_showall = '';
				?>
					
					<div class="normal_shlf_list">
					<div class="normal_shlf_list_top"></div>
					<div class="normal_shlf_list_mid">
					<? 
					echo $HTML_comptitle;
					echo $HTML_maindesc;
					$max_col = 3;
					$cur_col = 0;
					$prodcur_arr = array();
					while($row_prod = $db->fetch_array($ret_prod))
					{
						$prodcur_arr[] = $row_prod;
						$HTML_title = $HTML_image = $HTML_desc = '';
						$HTML_sale = $HTML_new = $HTML_compare = $HTML_rating = '';
						$HTML_price = $HTML_bulk= $HTML_bonus = $HTML_compare = $HTML_freedel= $HTML_bonus_bar = '';
						if($cat_det['product_showtitle']==1)// whether title is to be displayed
						{
							$HTML_title = '<div class="normal_shlf_listA_pdt_name"><a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">'.stripslash_normal($row_prod['product_name']).'</a></div>';
						}
						if ($cat_det['product_showimage']==1)// Check whether description is to be displayed
						{
							$HTML_image ='<a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">';
							// Calling the function to get the image to be shown
							$pass_type ='image_thumbpath';
							$img_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,0,0,1);
							if(count($img_arr))
							{
								$HTML_image .= show_image(url_root_image($img_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name'],'','',1);
							}
							else
							{
								// calling the function to get the default image
								$no_img = get_noimage('prod',$pass_type); 
								if ($no_img)
								{
									$HTML_image .= show_image($no_img,$row_prod['product_name'],$row_prod['product_name'],'','',1);
								}       
							}       
							$HTML_image .= '</a>';
						}
						if($row_prod['product_bulkdiscount_allowed']=='Y')
						{
							$HTML_bulk = '<img src="'.url_site_image('multi-buyA.gif',1).'" />';
						}
						else
							$HTML_bulk = '&nbsp;';
						if ($cat_det['product_showshortdescription']==1)// Check whether description is to be displayed
						{
							$HTML_desc = stripslash_normal($row_prod['product_shortdesc']);
						}
						if($row_prod['product_bonuspoints']>0 and $cat_det['product_showbonuspoints']==1)// Check whether description is to be displayed)
						{
							$HTML_bonus = 'Bonus: '.$row_prod['product_bonuspoints'];
							$bonus_class = 'normal_shlf_listA_pdt_bonus';
						}
						else
						{
							$HTML_bonus = '&nbsp;';
							$bonus_class = 'normal_shlf_listA_pdt_bonus_blank';
						}	
						if($HTML_bonus!='&nbsp;' or $HTML_bulk !='&nbsp;')
						{
							$HTML_bonus_bar = ' <div class="normal_shlf_listA_pdt_bonus_otr">
												<div class="normal_shlf_listA_multibuy">'.$HTML_bulk.'</div>
												<div class="'.$bonus_class.'"><span>'.$HTML_bonus.'</span></div>
												</div>';
						}	
						if($row_prod['product_freedelivery']==1)
						{
							$HTML_freedel = ' <div class="normal_shlf_listA_free"></div>';
						}
						else
						{
							$HTML_freedel = ' <div class="normal_shlf_listA_freeC"></div>';
						}
						if($row_prod['product_saleicon_show']==1)
						{
							$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
						    $HTML_sale = '<div class="normal_shlf_listA_pdt_sale"></div>';
						}
						if($row_prod['product_newicon_show']==1)
						{
							 $desc = stripslash_normal(trim($row_prod['product_newicon_text']));
							 $HTML_new = '<div class="normal_shlf_listA_pdt_new"></div>';
						}
						if($cat_det['product_showrating']==1)
						{
							$module_name = 'mod_product_reviews';
							if(in_array($module_name,$inlineSiteComponents))
							{
								if($row_prod['product_averagerating']>=0)
								{
									$HTML_rating = display_rating($row_prod['product_averagerating'],1);
								}
							}
						}
						else
							$HTML_rating = '&nbsp;';
						if ($cat_det['product_showprice']==1)// Check whether description is to be displayed
						{
							$price_class_arr['class_type']          = 'div';
							$price_class_arr['normal_class']        = 'normal_shlf_listA_pdt_priceA';
							$price_class_arr['strike_class']        = 'normal_shlf_listA_pdt_priceB';
							$price_class_arr['yousave_class']       = 'normal_shlf_listA_pdt_priceC';
							$price_class_arr['discount_class']      = 'normal_shlf_listA_pdt_priceC';
							$HTML_price = show_Price($row_prod,$price_class_arr,'shelfcenter_3');
						}
						if($cur_col==0)
						{
							echo  '<div class="outer_shlf_list_container">';
						}	
					?>
						<div class="normal_list_pdt_mid">
						<?=$HTML_title;?>
						<div class="normal_shlf_listA_pdt_img_otr">
						<div class="normal_listA_offer">
						<?//=$HTML_sale?>
						<?//=$HTML_new?>
						<?//=$HTML_freedel?>				
						</div>
						<div class="normal_shlf_listA_pdt_img"><?=$HTML_image?></div>
						</div>
						<?php /*
						<div class="normal_shlf_listA_pdt_price"><?=$HTML_price?></div>
						
						<?//=$HTML_bonus_bar?>
						<div class="normal_shlf_listA_pdt_des"><?=$HTML_desc?></div>    
						<div class="normal_shlf_listA_pdt_rate"><?//=$HTML_rating?></div>
						*  *//* ?>                                 
						<div class="normal_shlf_listA_pdt_des"><?=$HTML_desc?></div>    
                        </div>
					<?
						$cur_col++;
						if($cur_col>=$max_col)
						{
							$cur_col =0;
							echo "</div>";
						}
					}
					if($cur_col<$max_col)
					{
						if($cur_col!=0)
						{ 
							echo "</div>";
						} 
					}
					?>
					<div class="normal_shlf_list_bottom"></div> 
					</div>   
					</div>
			<?php		
				break;
				*/ 
				case '3row':
				case '4row': // case of three in a row for normal
					$HTML_comptitle = $HTML_maindesc = $HTML_paging = $HTML_showall = '';
				?>
					
					<div class="normal_shlf_list">
					<div class="normal_shlf_list_top"></div>
					<div class="normal_shlf_list_mid">
					<? 
					$max_col = 4;
					$cur_col = 0;
					$prodcur_arr = array();
					while($row_prod = $db->fetch_array($ret_prod))
					{
						$prodcur_arr[] = $row_prod;
						$HTML_title = $HTML_image = $HTML_desc = '';
						$HTML_sale = $HTML_new = $HTML_compare = $HTML_rating = '';
						$HTML_price = $HTML_bulk= $HTML_bonus = $HTML_compare = $HTML_freedel= $HTML_bonus_bar = '';
						if($cat_det['product_showtitle']==1)// whether title is to be displayed
						{
							$HTML_title = '<div class="normal_shlf_listB_pdt_name"><a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">'.stripslash_normal($row_prod['product_name']).'</a></div>';
						}
						if ($cat_det['product_showimage']==1)// Check whether description is to be displayed
						{
							$HTML_image ='<a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">';
							// Calling the function to get the image to be shown
							$pass_type ='image_thumbcategorypath';
							$img_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,0,0,1);
							if(count($img_arr))
							{
								$HTML_image .= show_image(url_root_image($img_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name'],'','',1);
							}
							else
							{
								// calling the function to get the default image
								$no_img = get_noimage('prod',$pass_type); 
								if ($no_img)
								{
									$HTML_image .= show_image($no_img,$row_prod['product_name'],$row_prod['product_name'],'','',1);
								}       
							}       
							$HTML_image .= '</a>';
	
						}
						if ($cat_det['product_showshortdescription']==1)// Check whether description is to be displayed
						{
							$HTML_desc = stripslash_normal($row_prod['product_shortdesc']);
						}
						if($row_prod['product_saleicon_show']==1)
						{
							$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
						     $HTML_sale = '<div class="normal_shlf_listB_pdt_sale"></div>';
						}
						if($row_prod['product_newicon_show']==1)
						{
							$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
						    $HTML_new = '<div class="normal_shlf_listB_pdt_new"></div>';
						}
						if($cat_det['product_showrating']==1)
						{
							$module_name = 'mod_product_reviews';
							if(in_array($module_name,$inlineSiteComponents))
							{
								if($row_prod['product_averagerating']>=0)
								{
									$HTML_rating = '<div class="normal_shlf_listB_pdt_rate">'.display_rating($row_prod['product_averagerating'],1).'</div>';
								}
							}
						}
						else
							$HTML_rating = '';
						if ($cat_det['product_showprice']==1)// Check whether description is to be displayed
						{
							$price_class_arr['class_type']          = 'div';
							$price_class_arr['normal_class']        = 'normal_shlf_listB_pdt_priceA';
							$price_class_arr['strike_class']        = 'normal_shlf_listB_pdt_priceB';
							$price_class_arr['yousave_class']       = 'normal_shlf_listB_pdt_priceC';
							$price_class_arr['discount_class']      = 'normal_shlf_listB_pdt_priceC';
							$HTML_price = show_Price($row_prod,$price_class_arr,'shelfcenter_3');
						}
						if($row_prod['product_bulkdiscount_allowed']=='Y')
						{
							$HTML_bulk = '<div class="normal_shlf_listB_multibuy"><img src="'.url_site_image('multi-buyAB.gif',1).'" title="Multi Buy" /></div>';
						}
							
						if($row_prod['product_bonuspoints']>0 and $cat_det['product_showbonuspoints']==1)
						{
							$HTML_bonus = '<div class="normal_shlf_listB_pdt_bonus"><span>Bonus: '.$row_prod['product_bonuspoints'].'</span></div>';
						}
						else
						{
							$HTML_bonus = '';
						}	
						if($row_prod['product_freedelivery']==1)
						{
							$HTML_freedel = '<div class="normal_shlf_listB_free"></div>';
						}
						else
						{
							$HTML_freedel = '<div class="normal_shlf_listB_freeC"></div>';
						}
						$frm_name = uniqid('shelf_');
						if($HTML_bonus!='' or $HTML_bulk !='')
						{
							$HTML_bonus_bar = '<div class="normal_shlf_listB_pdt_bonus_otr">'
												.$HTML_bulk.$HTML_bonus.
											  '</div>';
						}	
						if($cur_col==0)
						{
							echo  '<div class="outer_shlf_list_container">';
						}	
							
						?>	
						<div class="normal_listB_pdt_mid">
                         <?php
							//echo $HTML_sale;
							//echo $HTML_new;
							?>
						<?=$HTML_title;?>
						<div class="normal_shlf_listB_pdt_img_otr">
							<div class="normal_listB_offer">
							<?php
							//echo $HTML_freedel;
							?>
							</div>
						<div class="normal_shlf_listB_pdt_img"><?=$HTML_image?></div>
						</div>
						<?php /*
						<div class="normal_shlf_listB_pdt_price"><?=$HTML_price?></div>
						*/?>
						<?//=$HTML_bonus_bar?>									
						<div class="normal_shlf_listB_pdt_des"><?=$HTML_desc?></div>
						<?//=$HTML_rating;?>	
						<?php
																	$frm_name = uniqid('catdet_');
													?>
																												<div class="prod_list_buy">
													
													<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
						<input type="hidden" name="fpurpose" value="" />
						<input type="hidden" name="fproduct_id" value="" />
						<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
						<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />																
					
						<?php			$class_arr['ADD_TO_CART']       = '';
																	$class_arr['PREORDER']          = '';
																	$class_arr['ENQUIRE']           = '';
																	$class_arr['QTY_DIV']           = 'normal_shlfA_pdt_input';
																	$class_arr['QTY']               = ' ';
																	$class_td['QTY']				= 'prod_list_buy_a';
																	$class_td['TXT']				= 'prod_list_buy_b';
																	$class_td['BTN']				= 'prod_list_buy_c';
																	echo show_addtocart_v5_ajax($frm_name,$row_prod,$class_arr,true,$class_td,true,false);
																	
																	?>
																	
						</form>
																		</div>
												
						</div>
						
					<?
						$cur_col++;
						if($cur_col>=$max_col)
						{
							$cur_col =0;
							echo "</div>";
						}
					}
					if($cur_col<$max_col)
					{
						if($cur_col!=0)
						{ 
							echo "</div>";
						} 
					}
					?>
					<div class="normal_shlf_list_bottom"></div> 
					</div>   
					</div>
			<?php		
				break;
			};
			echo $HTML_paging;
		}
		function Show_HomeCatgeory($title,$ret_category) 
		{
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$Captions_arr,$inlineSiteComponents,$homecatgroup_id;
			if(in_array('mod_catimage',$inlineSiteComponents))
			{
				$img_support = true;
			}
			else
				$img_support = false;
			// Get the display type for current group
			$sql_group = "SELECT catgroup_listtype 
							FROM 
								product_categorygroup 
							WHERE 
								catgroup_id = $homecatgroup_id 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$ret_group = $db->query($sql_group);
			if($db->num_rows($ret_group))
			{
				$row_group = $db->fetch_array($ret_group);
			}
			
			switch ($row_group['catgroup_listtype'])
			{
				case 'Menu':
					echo '<div class="cate_mid_con">';
					if($title!='')
						echo '<div class="cate_mid_hdr">'.$title.'</div>';
					$cur_col = 0;
					while ($row_subcat = $db->fetch_array($ret_category))
					{
						$HTML_subcatname = $HTML_image = $HTML_short_desc = $HTML_more_icon = '';
						if($cur_col==0)
						{
							echo '<div class="cate_mid_con_in">';
							$cat_main_class_name = 'cate_mid_left';
						}
						elseif($cur_col==1)
						{
							$cat_main_class_name = 'cate_mid_right';
						}
						$HTML_subcatname 	= '<a href="'.url_category($row_subcat['category_id'],$row_subcat['category_name'],1).'" title="'.stripslash_normal($row_subcat['category_name']).'">'.stripslash_normal($row_subcat['category_name']).'</a>';
						$HTML_image 		= '<a href="'.url_category($row_subcat['category_id'],$row_subcat['category_name'],1).'" class="subcategoreynamelink" title="'.stripslash_normal($row_subcat['category_name']).'">';
						switch($subcategory_showimagetype)
						{
							case 'Default':
								$pass_type = 'image_thumbpath';
							break;
							case 'Icon':
								$pass_type = 'image_iconpath';
							break;
							case 'Thumb':
								$pass_type = 'image_thumbpath';
							break;
							case 'Medium':
								$pass_type = 'image_thumbcategorypath';
							break;
							case 'Big':
								$pass_type = 'image_bigpath';
							break;
							case 'Extra':
								$pass_type = 'image_extralargepath';
							break;
							default:
							  $pass_type = 'image_thumbpath';
							break;
						};
						if ($row_subcat['category_showimageofproduct']==0) // Case to check for images directly assigned to category
						{
							// Calling the function to get the image to be shown
							$img_arr = get_imagelist('prodcat',$row_subcat['category_id'],$pass_type,0,0,1);
							if(count($img_arr))
							{
								$HTML_image .= show_image(url_root_image($img_arr[0][$pass_type],1),$row_subcat['category_name'],$row_subcat['category_name'],'','',1);
								$show_noimage = false;
							}
							else
								$show_noimage = true;
						}
						else // Case of check for the first available image of any of the products under this category
						{
							// Calling the function to get the id of products under current category with image assigned to it
							$cur_prodid = find_AnyProductWithImageUnderCategory($row_subcat['category_id']);
							if ($cur_prodid)// case if any product with image assigned to it under current category exists
							{
								// Calling the function to get the image to be shown
								$img_arr = get_imagelist('prod',$cur_prodid,$pass_type,0,0,1);
								
								if(count($img_arr))
								{
									$HTML_image .= show_image(url_root_image($img_arr[0][$pass_type],1),$row_subcat['category_name'],$row_cat['category_name'],'','',1);
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
							$no_img = get_noimage('prodcat',$pass_type); 
							if ($no_img)
							{
								$HTML_image .= show_image($no_img,$row_subcat['category_name'],$row_subcat['category_name'],'','',1);
							}	
						}
					$HTML_image .= '</a>';
				
					$HTML_short_desc = stripslash_normal($row_subcat['category_shortdescription']);
					$HTML_more_icon 		= '<a href="'.url_category($row_subcat['category_id'],$row_subcat['category_name'],1).'" class="subcategoreynamelink" title="'.stripslash_normal($row_subcat['category_name']).'"><img src="'.url_site_image('cat-more.gif',1).'" border="0"/></a>';
						?>	
						<div class="<?php echo $cat_main_class_name?>">
						<div class="cate_mid_ca_top"></div>
						<div class="cate_mid_ca_mid">
						<div class="cate_mid_ca_name"><?php echo $HTML_subcatname?></div>
						<div class="cate_mid_ca_in">
						
						<div class="cate_mid_ca_in_top"></div>
						<div class="cate_mid_ca_in_mid">
						
						<div class="cate_mid_ca_in_img"><?php echo $HTML_image?></div>
						<div class="cate_mid_ca_in_des"><?php echo $HTML_short_desc?></div>
						
						</div>
						<div class="cate_mid_ca_in_bottom"></div>
						</div>
						<div class="cate_mid_ca_more"><?php echo $HTML_more_icon?></div>
						</div>
						<div class="cate_mid_ca_bottom"></div>
						</div>	
						<?php
						$cur_col++;	
						if($cur_col==2)
						{
							$cur_col=0;
							echo '</div>';
						}
					}
					if($cur_col>0 and $cur_col<2)
						echo '</div>';
					echo '</div>';	
				break;
				default:
					echo '<div class="cate_mid_con_in">';
					if($title!='')
						echo '<div class="cate_mid_hdr">'.$title.'</div>';
					$cur_col = 0;
					while ($row_subcat = $db->fetch_array($ret_category))
					{
						$HTML_subcatname = $HTML_image = $HTML_short_desc = $HTML_more_icon = '';
						if($cur_col==0)
						{
							echo '<div class="cate_mid_con_in">';
							$cat_main_class_name = 'cate_midA_left';
						}
						elseif($cur_col==1)
						{
							$cat_main_class_name = 'cate_midA_right';
						}
						$HTML_subcatname 	= '<a href="'.url_category($row_subcat['category_id'],$row_subcat['category_name'],1).'" title="'.stripslash_normal($row_subcat['category_name']).'">'.stripslash_normal($row_subcat['category_name']).'</a>';
						$HTML_image 		= '<a href="'.url_category($row_subcat['category_id'],$row_subcat['category_name'],1).'" class="subcategoreynamelink" title="'.stripslash_normal($row_subcat['category_name']).'">';
						switch($subcategory_showimagetype)
						{
							case 'Default':
								$pass_type = 'image_thumbpath';
							break;
							case 'Icon':
								$pass_type = 'image_iconpath';
							break;
							case 'Thumb':
								$pass_type = 'image_thumbpath';
							break;
							case 'Medium':
								$pass_type = 'image_thumbcategorypath';
							break;
							case 'Big':
								$pass_type = 'image_bigpath';
							break;
							case 'Extra':
								$pass_type = 'image_extralargepath';
							break;
							default:
							  $pass_type = 'image_thumbpath';
							break;
						};
						if ($row_subcat['category_showimageofproduct']==0) // Case to check for images directly assigned to category
						{
							// Calling the function to get the image to be shown
							$img_arr = get_imagelist('prodcat',$row_subcat['category_id'],$pass_type,0,0,1);
							if(count($img_arr))
							{
								$HTML_image .= show_image(url_root_image($img_arr[0][$pass_type],1),$row_subcat['category_name'],$row_subcat['category_name'],'','',1);
								$show_noimage = false;
							}
							else
								$show_noimage = true;
						}
						else // Case of check for the first available image of any of the products under this category
						{
							// Calling the function to get the id of products under current category with image assigned to it
							$cur_prodid = find_AnyProductWithImageUnderCategory($row_subcat['category_id']);
							if ($cur_prodid)// case if any product with image assigned to it under current category exists
							{
								// Calling the function to get the image to be shown
								$img_arr = get_imagelist('prod',$cur_prodid,$pass_type,0,0,1);
								
								if(count($img_arr))
								{
									$HTML_image .= show_image(url_root_image($img_arr[0][$pass_type],1),$row_subcat['category_name'],$row_cat['category_name'],'','',1);
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
							$no_img = get_noimage('prodcat',$pass_type); 
							if ($no_img)
							{
								$HTML_image .= show_image($no_img,$row_subcat['category_name'],$row_subcat['category_name'],'','',1);
							}	
						}
					$HTML_image .= '</a>';
				
					$HTML_short_desc = stripslash_normal($row_subcat['category_shortdescription']);
					$HTML_more_icon 		= '<a href="'.url_category($row_subcat['category_id'],$row_subcat['category_name'],1).'" class="subcategoreynamelink" title="'.stripslash_normal($row_subcat['category_name']).'"><img src="'.url_site_image('cat-more.gif',1).'" border="0"/></a>';
						?>	
						<div class="<?php echo $cat_main_class_name?>">
						
						<div class="cate_midA_ca_top"></div>
						<div class="cate_midA_ca_mid">
						<div class="cate_midA_ca_name"><?php echo $HTML_subcatname?></div>
						<div class="cate_midA_ca_mainimg"><?php echo $HTML_image?></div>
						</div>
						<div class="cate_midA_ca_bottom"></div>
						</div>
						<?php
						$cur_col++;	
						if($cur_col==2)
						{
							$cur_col=0;
							echo '</div>';
						}
					}
					if($cur_col>0 and $cur_col<2)
						echo '</div>';
					echo '</div>';	
				break;
			};
		
		}
		function Show_Products_Grid($ret_prod)
		{
			global $ecom_siteid,$db,$Settings_arr,$Captions_arr,$inlineSiteComponents,$compare_button_displayed;
			$showqty		= $Settings_arr['show_qty_box'];// show the qty box
			
			if($db->num_rows($ret_prod))
			{
				// get the id of the label named "Specification"
				$sql_lab = "SELECT label_id FROM product_site_labels WHERE sites_site_id = $ecom_siteid AND label_name = 'Specification' AND is_textbox=1 LIMIT 1";
				$ret_lab = $db->query($sql_lab);
				if($db->num_rows($ret_lab))
				{
					$row_lab = $db->fetch_array($ret_lab);
					$chk_label_id = $row_lab['label_id'];
			?>

					<table width="100%" class="grid_main_table">
					<?php 
					if(trim($Captions_arr['CAT_DETAILS']['PRODCAT_GRID_HEADING'])!='')
					{
					?>	
					<tr>
					<td class="grid_header_text" colspan="7"><?php echo $Captions_arr['CAT_DETAILS']['PRODCAT_GRID_HEADING']?></td>
					</tr>
					<?php
				}
					?>
					<tr>
						<td class='grid_header_td'>Cross Section (mm)</td>
						<td class='grid_header_td'>Length <br/>(mm)</td>
						<td class='grid_header_td'>AWG</td>
						<td class='grid_header_td' colspan="1">Color Din</td>
						
						<td class='grid_header_td' colspan="1">Color T</td>
						<td class='grid_header_td' colspan="1">Color W</td>
						<td class='grid_header_td'>Part Number</td>
					</tr>	
					<?php
					
					while($row_prod = $db->fetch_array($ret_prod))
					{
						// Get the value of the label "Specification" set for this product
						$sql_getlabel_val = "SELECT label_value 
												FROM 
													product_labels 
												WHERE 
													product_site_labels_label_id = $chk_label_id 
													AND products_product_id = ".$row_prod['product_id']." 
												LIMIT 
													1";
						$ret_getlabel_val = $db->query($sql_getlabel_val);
						if($db->num_rows($ret_getlabel_val))
						{
							$row_getlabel_val = $db->fetch_array($ret_getlabel_val);
							$label_val = trim($row_getlabel_val['label_value']);				
							$data_arr = explode('/',$label_val);
					?>
							<tr>
							<td class='grid_normal_td'><?php echo stripslashes($data_arr[0])?></td>
							<td class='grid_normal_td'><?php echo stripslashes($data_arr[1])?></td>
							<td class='grid_normal_td'><?php echo stripslashes($data_arr[2])?></td>
							<td class='grid_normal_td'><?php echo $this->get_color_hexval(stripslashes($data_arr[3]))?>
							<div class='grid_normal_td_img'><?php echo stripslashes($this->strip_hyphen($data_arr[3]))?></div></td>
							<td class='grid_normal_td'><?php echo $this->get_color_hexval(stripslashes($data_arr[4]))?>
							<div class='grid_normal_td_img'><?php echo stripslashes($this->strip_hyphen($data_arr[4]))?></div></td>
							<td class='grid_normal_td'><?php echo $this->get_color_hexval( stripslashes($data_arr[5]))?>
							<div class='grid_normal_td_img'><?php echo stripslashes($this->strip_hyphen($data_arr[5]))?></div></td>
							<td class='grid_normal_td_bold'>
							<?php
							$frm_name = 'frm_cat'.uniqid('').$row_prod['product_id']; ?>
							<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls">
							<input type="hidden" name="fpurpose" value="" />
							<input type="hidden" value="<?php echo $row_prod['product_id']?>" name="fproduct_id"/>
							<input type="hidden" value="<?php echo $row_prod['product_id']?>" name="product_id"/>
							<input id="product_id_ajax" type="hidden" value="<?php echo $row_prod['product_id']?>" name="product_id"/>
							<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
							<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
							<input type="hidden" name="ajax_div_holder" id="ajax_div_holder" value="" />
							<span onclick="ajax_addto_cart_fromlist('show_prod_det_ajax','','<?php echo $frm_name?>','<?php echo $ecome_hostname?>')" style="cursor:pointer" class="gridprod_cls"><?php echo ltrim(stripslashes($row_prod['product_name']),'0')?></span>						
							</form>	
								
								<?php /*<a href=""><?php echo ltrim(stripslashes($row_prod['product_name']),'0')?></a></td>*/?>
							</tr>
					<?php
						}
					}
					?>
					</table>
					<?php
				}	
			}
		}	
		function get_color_hexval($cname)
		{
			global $db,$ecom_siteid;
			$cname = trim($cname);
			if($cname!='' and $cname!='-')
			{
				$sql_ccode = "SELECT color_hexcode FROM general_settings_site_colors WHERE sites_site_id = $ecom_siteid AND color_name='".$cname."' LIMIT 1";
				$ret_ccode = $db->query($sql_ccode);
				if($db->num_rows($ret_ccode))
				{
					$row_ccode = $db->fetch_array($ret_ccode);
					if(trim($row_ccode['color_hexcode']))
					{
					?>
						<div style="margin-left:23px;float:left;text-align:center;border:1px solid #000;width:30px;height:15px;background-color:<?php echo trim($row_ccode['color_hexcode'])?>">&nbsp;</div>
					<?php	
					}
				}
			}
		}
		function strip_hyphen($data)
		{
			return str_replace('-','',$data);
		}
	};	
	
	function show_addtocart_v5_ajax_cstnew($frm,$prod_arr,$class_arr,$istable=false,$class_tdarr=array(),$isbutton=false,$return = false,$prefix='',$suffix='',$override_hideqty=0)
	{ 
		global $db,$ecom_hostname,$Captions_arr,$Settings_arr,$ecom_siteid;
		// Getting the values of captions from the common caption table
		$addtocart_cap 			= $Captions_arr['COMMON']['ADD_TO_CART'];
		$enquire_cap			= $Captions_arr['COMMON']['ENQUIRE'];
		$preorder_cap			= $Captions_arr['COMMON']['PREORDER'];
		$show_only_on_login 	= $Settings_arr['hide_addtocart_login'];
		$mod					= '';
		$curtype				= '';
		$addto_cart_withajax    = $Settings_arr['enable_ajax_in_site'];//checking for the ajax function for adding to cart is enabled or not

		//to sheck whether quantity box should be shown or not
		$showqty		= $Settings_arr['show_qty_box'];
		$quantity_box_display   = false;
		
        if($istable == true)
		{
		$class_qty = $class_tdarr['QTY'];
		$class_txt = $class_tdarr['TXT'];
		$class_btn = $class_tdarr['BTN'];	
		$quantity_div_class     = ($class_arr['QTY_TD']!='')?$class_arr['QTY_TD']:'quantity';  
        $quantity_class         = ($class_arr['QTY']!='')?$class_arr['QTY']:'quainput';		
		}  
		else
		{			
		        $quantity_div_class     = ($class_arr['QTY_DIV']!='')?$class_arr['QTY_DIV']:'quantity';  
                $quantity_class         = ($class_arr['QTY']!='')?$class_arr['QTY']:'quainput';     
		}	
		//echo "herer".$showqty.' - '.$override_hideqty;
		if($showqty && $override_hideqty!=1)
		{
			if($istable == true)
			{
				$quantity_box  = '<td align="right" valign="middle" class="'.$class_qty.'">';
				if($prefix!='')
				{
					  $quantity_box  .=  $prefix;
				}
				$quantity_box  .= $Captions_arr['COMMON']['COMMON_QTY'].'</td>
								 <td align="left" valign="middle" class="'.$class_txt.'"> <input type="text" class="'.$quantity_class.'" name="qty"  value="1" /></td>';
		    }
		    else
		    {
			  	$quantity_box  = '<div class="'.$quantity_div_class.'">'.$Captions_arr['COMMON']['COMMON_QTY'].'<input type="text" class="'.$quantity_class.'" name="qty"  value="1" /></div>';

			}					 
		}
		if($override_hideqty==1)
		{
			$quantity_box  = '<input type="hidden" class="quainput" name="qty"  value="1" />';
		}
		// Check whether add to cart should be hidden when not logged in
		if ($show_only_on_login==1)
		{
			// Get the customer id from the session
			$cust_id 			= get_session_var("ecom_login_customer");
			if (!$cust_id)
				return;
		}
		$show_buy_now = false;
		$variable_check_forajax = false;//to check whether there is a variable exists for the product
		$var_exists 			= false; 
		if($prod_arr['product_variablestock_allowed']=='Y') // case if variable stock exists. if variable stock exists then variables exists
		{
			$var_exists = true;
			$variable_check_forajax = true;
			// Call function to check whether there exists stock for atleast one variable combination for current product
			$stock = get_atleastone_variablestock($prod_arr['product_id']);
			if ($return==true and $stock==0 and $prod_arr['product_stock_notification_required']=='Y')// if stock notification is set to y and also in variable stock and if no stock then the buy button is to be displayed. so this condition is used
				$show_buy_now = true;
			// Check whether stock exists
			if($stock > 0 or $prod_arr['product_alloworder_notinstock']=='Y' or $show_buy_now==true)
			{
				if($prod_arr['product_show_cartlink']==1)// if show cart link is set to display
				{				
						$link = "javascript:submit_to_det_form('".$frm."')";
						$quantity_box_display = true;
					    $quantity_box_display = true;					
					
					$caption	= $addtocart_cap;
					$class		= $class_arr['ADD_TO_CART'];
					$mod		= 'PRODDET_BUYNOW';
				}
				elseif ($prod_arr['product_show_enquirelink'])// Check whether enquire link is set to display
				{
						$link = "javascript:submit_to_det_form('".$frm."')";
						$quantity_box_display = true;
					
					$caption	= $enquire_cap;
					$class		= $class_arr['ENQUIRE'];
					$curtype	= 'Prod_Enquire';					
					$mod		= '';
				}
			}
			else // case stock does not exists
			{
				// Check whether preorder is allowed
				if ($prod_arr['product_preorder_allowed']=='Y' and $prod_arr['product_total_preorder_allowed']>0)
				{
					if($prod_arr['product_show_cartlink']==1)// show preorder link only if show cart link is set to display
					{
						    $link = "javascript:submit_to_det_form('".$frm."')";
							$quantity_box_display = true;						
						$caption	= $preorder_cap;
						$class		= $class_arr['PREORDER'];
						$mod		= 'PRODDET_PREORDER';
					}
				}
				elseif ($prod_arr['product_show_enquirelink'])// Check whether enquire link is set to display
				{
						$link = "javascript:submit_to_det_form('".$frm."')";
						$quantity_box_display = true;					
					$caption	= $enquire_cap;
					$class		= $class_arr['ENQUIRE'];
					$curtype	= 'Prod_Enquire';					
					$mod		= '';
				}
			}
		}
		else // case if variable stock does not exists
		{	
			if($prod_arr['product_variables_exists']=='Y')
			{
				$var_exists = true;
				$variable_check_forajax = true;	//this is for checking for variable exists for ajax enabled cart adding
			}
			else
			{
				$var_exists = false;	
				$variable_check_forajax = false;				
			}	
			// Check whether stock exists and
			if($prod_arr['product_webstock']>0  or $prod_arr['product_alloworder_notinstock']=='Y')
			{				
				if($prod_arr['product_show_cartlink']==1)// show preorder link only if show cart link is set to display
				{
					if ($var_exists){
						$link = "javascript:submit_to_det_form('".$frm."')";
						$quantity_box_display = true;
					}
					else{
						$link 		= "javascript:submit_form('".$frm."','Prod_Addcart','".$prod_arr['product_id']."')";
						$quantity_box_display = true;
						$curtype	= 'Prod_Addcart';
					}
					$caption	= $addtocart_cap;
					$class		= $class_arr['ADD_TO_CART'];
					$mod		= 'PRODDET_BUYNOW';
				}
				elseif ($prod_arr['product_show_enquirelink'])// Check whether enquire link is set to display
				{
					if ($var_exists){
						$link = "javascript:submit_to_det_form('".$frm."')";
						$quantity_box_display = true;
					}
					else{
						$link		= "javascript:submit_form('".$frm."','Prod_Enquire','".$prod_arr['product_id']."')";
						$quantity_box_display = true;
						$curtype	= 'Prod_Enquire';
					}
					$curtype	= 'Prod_Enquire';					
					$caption	= $enquire_cap;
					$class		= $class_arr['ENQUIRE'];
					$mod		= '';
				}
			}
			else // case stock does not exists
			{
				// Check whether preorder is allowed
				if ($prod_arr['product_preorder_allowed']=='Y' and $prod_arr['product_total_preorder_allowed']>0)
				{
					if($prod_arr['product_show_cartlink']==1)// show preorder link only if show cart link is set to display
					{
						if ($var_exists){
							$link = "javascript:submit_to_det_form('".$frm."')";
							$quantity_box_display = true;
						}
						else{
							$link 		= "javascript:submit_form('".$frm."','Prod_Preorder','".$prod_arr['product_id']."')";
							$quantity_box_display = true;
							$curtype	= 'Prod_Preorder';
						}
						$curtype	= 'Prod_Preorder';						
						$caption	= $preorder_cap;
						$class		= $class_arr['PREORDER'];
						$mod		= 'PRODDET_PREORDER';
					}
				}
				elseif ($prod_arr['product_show_enquirelink'])// Check whether enquire link is set to display
				{
					if ($var_exists){
						$link = "javascript:submit_to_det_form('".$frm."')";
						$quantity_box_display = true;
					}
					else{
						$link		= "javascript:submit_form('".$frm."','Prod_Enquire','".$prod_arr['product_id']."')";
						$quantity_box_display = true;
						$curtype	= 'Prod_Enquire';
					}
					$curtype	= 'Prod_Enquire';					
					$caption	= $enquire_cap;
					$class		= $class_arr['ENQUIRE'];
					$mod		= '';
				}
			}
		}
		
		if ($return==true)
		{
			return $mod;
		}	
		elseif ($link)
		{	
		
		if($addto_cart_withajax==1)
		{ 
			$link ="";
			if($ecom_siteid!=70)
			{
		   ?>
		   	<input type="hidden" name="ajax_div_holder" id="ajax_div_holder" value="" />
		   	<?php
		   	}?>
			<input type='hidden' name='fproduct_id' value="<?=$prod_arr['product_id']?>"/>
		   	<input type='hidden' name='product_id' value="<?=$prod_arr['product_id']?>"/>
			
			<?php 
			if($ecom_siteid!=70)
			{
			?>
			<input type="hidden" id="product_id_ajax" name="product_id" value="<?=$prod_arr['product_id']?>" />
			<input type='hidden' name='ajaxform_name' id="ajaxform_name" value="<?=$frm?>"/>
			<?php
			}
			?>

		   <?php
		    if ($variable_check_forajax==true){					 
			$link  ="ajax_addto_cart_fromlist('show_prod_det_ajax','".$curtype."','".$frm."','".SITE_URL."')";
			}
			else
			{		
			//$btn_box  ='<td align="left" valign="middle" class="'.$class_btn.'"> <a href="'.$link.'" title="'.$caption.'" class="'.$class.'"><input name="'.$caption.'" type="button" /></a></td>';
			$link  ="ajax_addto_cart_fromlist('add_prod_tocart_ajax','".$curtype."','".$frm."','".SITE_URL."')";
			}
	    }
	    else
	    {
			if ($var_exists==true){					 
				$link = "javascript:submit_to_det_form('".$frm."')";
			}
			else
			{		
				$link = "javascript:submit_form('".$frm."','".$curtype."','".$prod_arr['product_id']."')";
			}
		   
		}
		
		$outer_cont = "";
		if($istable=='true')
		{
			$outer_cont        = '<table width="100%" border="0" cellspacing="0" cellpadding="0">
						          <tr>';
			$btn_box  		   = '<td align="left" valign="middle" class="'.$class_btn.'">';
			
			$btn_box_bottom    = '</td>';
			$outer_cont_bottom = '</tr></table>';
		}
		else
		{
		    $outer_cont        = '';
		    if($class_arr['BTN_CLS']!='')
		    {
				$class_btn = $class_arr['BTN_CLS'];
		    
			$btn_box  		   = '<div class="'.$class_btn.'">';
			
			$btn_box_bottom    = '</div>';
		     }
			else
			{
				$btn_box ='';
			    $btn_box_bottom    = '';
			}
			$outer_cont_bottom = '';
		}
		$show_but ='';
		if($isbutton == true)
		{
			$show_but =  '<input value="'.$caption.'" name="'.$caption.'" type="button" onclick="'.$link.'" />';			
		}
		else
		{
			 $check_arr = is_grid_display_enabled_prod($prod_arr['product_id']);
				if($check_arr['enabled']==false)
				{
					
					$show_but =  '<a href="javascript:void(0);" onclick="'.$link.'" title="'.$caption.'" class="'.$class.'">'.$caption.'</a>'; 
				}
				else
				{
					$show_but =  '<a href="'.url_product($prod_arr['product_id'],$prod_arr['product_name'],1).'" onclick="" title="'.$caption.'" class="'.$class.'">'.$caption.'</a>'; 

				}	
		}	
			$show_but .='<input type="hidden" name="prod_list_submit_common" value="'.$curtype.'" />';
		    echo $outer_cont;
			  if($quantity_box_display)
			   echo $quantity_box;
			   echo $btn_box;
			   echo $show_but;
			   echo $btn_box_bottom;
			   echo $outer_cont_bottom;
					
			
		    if($suffix!='')
			echo $suffix;
		}
	}
?>
