<?php
	/*############################################################################
	# Script Name 	: categoryHtml.php
	# Description 	: Page which holds the display logic for category details
	# Coded by 		: Sny
	# Created on	: 11-Aug-2009
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
									<div class="tree_menu_top"></div>
									<div class="tree_menu_mid">
									<div class="tree_menu_content">
										<ul class="tree_menu">'.
											str_replace('&','&amp;',generate_tree_menu($_REQUEST['category_id'],-1,'','<li>','</li>')).'
										</ul>
									</div>
									</div>
									<div class="tree_menu_bottom"></div>
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
								$HTML_image = show_image(url_root_image($img_arr[0][$pass_type],1),$row_cat['category_name'],$row_cat['category_name'],'imgwraptext','',1);
							}
						}
					}	
				}
			}
			$url= url_category($_REQUEST['category_id'],$row_cat['category_name'],'');
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
			if ($row_cat['category_turnoff_pdf']==0)
			{
				$HTML_icons .= '<a  href="javascript:download_pdf_common(\''.$_SERVER['HTTP_HOST'].'\',\''.$_SERVER['REQUEST_URI'].'\')" title="'.stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_DOWNLOADPDF']).'"><img src="'.url_site_image('pdfico.gif',1).'" alt="'.stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_DOWNLOADPDF']).'" border="0" /></a>';
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
			if($num_prods>0)
			{	
				//$HTML_icons .= '<a href="'.url_category_rss($row_cat['category_id'],$row_cat['category_name'],1).'" title="'.stripslash_normal($Captions_arr['CAT_DETAILS']['CAT_RSS']).'"><img src="'.url_site_image('rssico.gif',1).'" alt="'.stripslash_normal($Captions_arr['CAT_DETAILS']['CAT_RSS']).'" border="0" /></a>';
			}
			$cat_desc = stripslashes($row_cat['category_bottom_description']);
			
			if ($cat_desc!='')
			{
				$HTML_catbottomdesc = '<div class="cate_content_mid">'.
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
	   <script type="text/javascript">
	  function download_pdf() 
	  {
	  	
	  }
	  </script>
		
			<div class="normal_shlf_mid_con">
			
			<div class="normal_shlf_mid_top"></div>
			<div class="normal_shlf_mid_mid"> 
			<form method="post" name="frm_catedetails" id="frm_catedetails" action="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" class="frm_cls">
		<input type="hidden" name="fpurpose" value="" />
		<input type="hidden" name="caturl" value="<? echo $url?>" />
		<input type="hidden" name="type_cat" value="cate_root" />
		<input type='hidden' name='category_id' value="<?=$_REQUEST['category_id']?>"/>
				<?=$HTML_treemenu?>
	  		<div class="sub_cate_con">
			<div class="sub_cate_top"></div>
				<div class="sub_cate_mid">
					<div class="sub_cate_content">
		<?php /*<div class="sub_cate_hdr"><h1><?php echo stripslashes($row_cat['category_name'])?></h1></div>*/?>
					<div class="sub_cate_icon"><?=$HTML_icons?></div>
				</div>
			</div>
			</div>
	  	<?php
			echo $HTML_alert;
			echo $HTML_image;
           if($_REQUEST['catdet_pg']<=1)
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
					//$this->Show_Products($ret_prod,$tot_cnt,$start_var,$row_cat['category_name'],$row_cat['default_catgroup_id'],$row_cat['product_displaytype'],$row_cat['product_showimage'],$row_cat['product_showtitle'],$row_cat['product_showshortdescription'],$row_cat['product_showprice'],$row_cat['sdf'],,$base_sort_by,$prodsort_order); // ** Calling function to show the products under current category
					$this->Show_Products($ret_prod,$tot_cnt,$start_var,$row_cat,$base_sort_by,$prodsort_order); // ** Calling function to show the products under current category
				}
				else
				{
					if ($subcat_exists==false and $row_cat['category_turnoff_noproducts']==0)// ** Show the no products only if there exists no subcategories for current product
					{
					?>
						<?php
							$this->Show_NoProducts(); // ** Calling function to show the no products message
						?>
					<?php
					}	
				}		
			}
			if($_REQUEST['catdet_pg']<=1)
			echo $HTML_catbottomdesc;
			?>
			</div>
			<div class="normal_shlfA_mid_bottom"></div> 
			<?php /*?></div>   <?php */?>
			</div>
			
			<?php
			include ("includes/base_files/combo_middle.php");
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
					include ("includes/base_files/shelf.php");
				}
			}
			
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
					
					case '4row': // 3 in a row
						echo 
								'
								<div class="subcat_content">
      							<div class="subcat_3row_outr">
								';
						$cur_col = 0;
						while ($row_subcat = $db->fetch_array($ret_subcat))
						{
							$row_subcat['category_name'] = str_replace('&','&amp;',stripslash_normal($row_subcat['category_name']));
							$HTML_subcatname = $HTML_image = $HTML_short_desc = '';
							if($cur_col==0)
								echo '<div class="horizontal_container">';
							$cur_col++;
							if($row_cat['category_showname']==1)
							{
						  		$HTML_subcatname = '<a href="'.url_category($row_subcat['category_id'],$row_subcat['category_name'],1).'" title="'.stripslash_normal($row_subcat['category_name']).'">'.stripslash_normal($row_subcat['category_name']).'</a>';
							}
							if($row_cat['category_showimage']==1)
							{
								 $HTML_image = '<a href="'.url_category($row_subcat['category_id'],$row_subcat['category_name'],1).'" class="subcategoreynamelink" title="'.stripslash_normal($row_subcat['category_name']).'">';
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
									if($ecom_siteid==75) // bypassed for iloveflooring
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
								$HTML_short_desc = stripslash_normal(str_replace('&','&amp;',$row_subcat['category_shortdescription']));
							}
					?>
							<div class="subcat_3row_pdt_outr">
							<div class="subcat_3row_pdt_name"><?=$HTML_subcatname?></div>
							<div class="subcat_3row_pdt_btm">
							<div class="subcat_3row_image"><?=$HTML_image?></div>
							<div class="subcat_3row_des"><?=$HTML_short_desc?></div>
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
			if($paging['total_cnt'] and $start_var['pages']>1)
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
				case '4row': // case of three in a row for normal
										$HTML_comptitle = $HTML_maindesc = $HTML_paging = $HTML_showall = '';
										if($cur_title!='')
										{
											   $HTML_comptitle ='  <div class="normal_shlfA_header">
																   <div class="normal_shlfA_hd_inner">
																   <div class="normal_shlfA_hd"><span>'.stripslashes($cur_title).'</span></div>
																   </div> 
																   </div>';
										}
									/*	$desc = trim($shelfData['shelf_description']);
										if($desc!='' and $desc!='&nbsp;')
										{
											 $desc = stripslashes($desc);
											 $HTML_maindesc = '<div class="normal_shlfA_desc_outr">'.$desc.'</div>';
										}
										*/ 
										if ($tot_cnt>0 and ($_REQUEST['req']!='') and $tot_cnt>$prodperpage)
										{
											$paging 		= paging_footer_advanced($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr);
											$HTML_paging 	='
																<div class="page_nav_con_shelf">
																<div class="page_nav_top_shelf"></div>
																<div class="page_nav_mid_shelf">
																<div class="page_nav_content_shelf"><ul>';//.'';
											$HTML_paging 	.= $paging['navigation']['start_nav'].$paging['navigation']['page_no'].$paging['navigation']['end_nav'];
											$HTML_paging 	.= ' 
																</ul></div>
																</div>
																<div class="page_nav_bottom_shelf"></div>
																</div>';
										}
										if($_REQUEST['req']=='' and  $tot_cnt>0) // case of show all link
										{
										   $HTML_showall = "<div class='normal_midA_showall'><a href='".url_shelf_all($shelfData['shelf_id'],$shelfData['shelf_name'],1)."' title=''>".$Captions_arr['COMMON']['SHOW_ALL']."</a></div>";
										}
									
									?>
										
										<div class="normal_shlfA_mid_mid">
										<? 
										echo $HTML_comptitle;
										echo $HTML_maindesc;
										//echo $HTML_paging;
  										$max_col = 4;
										$cur_col = 0;
										$prodcur_arr = array();
										while($row_prod = $db->fetch_array($ret_prod))
										{
											$prodcur_arr[] = $row_prod;
											$row_prod['product_name'] = str_replace('&','&amp;',stripslash_normal($row_prod['product_name']));
											$HTML_title = $HTML_image = $HTML_desc = '';
											$HTML_sale = $HTML_new = $HTML_compare = $HTML_rating = '';
											$HTML_price = $HTML_bulk= $HTML_bonus = $HTML_compare = $HTML_freedel= $HTML_bonus_bar = '';
											if($cat_det['product_showtitle']==1)// whether title is to be displayed
											{
												$HTML_title = '<div class="normal_shlfA_pdt_name"><a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">'.stripslash_normal($row_prod['product_name']).'</a></div>';
											}
						if ($cat_det['product_showimage']==1)// Check whether description is to be displayed
											{
												$HTML_image ='<a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">';
												// Calling the function to get the image to be shown
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
												if($desc!='')
												{
													  $HTML_sale = '<div class="normal_shlfA_pdt_sale">'.$desc.'</div>';
												}
											}
											if($row_prod['product_newicon_show']==1)
											{
												$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
												if($desc!='')
												{
													  $HTML_new = '<div class="normal_shlfA_pdt_new">'.$desc.'</div>';
												}
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
												$price_class_arr['normal_class']        = 'normal_shlfB_pdt_priceA';
												$price_class_arr['strike_class']        = 'normal_shlfB_pdt_priceB';
												$price_class_arr['yousave_class']       = 'normal_shlfB_pdt_priceC';
												$price_class_arr['discount_class']      = 'normal_shlfB_pdt_priceC';
												$HTML_price = show_Price($row_prod,$price_class_arr,'shelfcenter_3');
											}
											if($row_prod['product_bulkdiscount_allowed']=='Y')
											{
												$HTML_bulk = '<img src="'.url_site_image('multi-buyA.gif',1).'" />';
											}
											if($row_prod['product_bonuspoints']>0 and $shelfData['shelf_showbonuspoints']==1)
											{
												$HTML_bonus = 'Bonus: '.$row_prod['product_bonuspoints'];
												$bonus_class = 'normal_shlfA_pdt_bonus';
											}
											else
											{
												$HTML_bonus = '&nbsp;';
												$bonus_class = 'normal_shlfA_pdt_bonus_blank';
											}	
											if($comp_active)
												$HTML_compare = dislplayCompareButton($row_prod['product_id'],'','',1,'compare_li_2row');
											if($row_prod['product_freedelivery']==1)
											{
												$HTML_freedel = ' <div class="normal_shlfA_free"></div>';
											}
											$frm_name = uniqid('shelf_');
											if($HTML_bonus!='&nbsp;' or $HTML_rating !='&nbsp;')
											{
												$HTML_bonus_bar = '<div class="normal_shlfA_pdt_bonus_otr">
																	<div class="'.$bonus_class.'">'.$HTML_bonus.'</div>
																	
																	</div>';
											}	
											if($cur_col==0)
											{
												$outer_class = 'normal_shlfA_pdt_outr';
												echo  '<div class="outer_shlfA_container">';
											}	
											else
											{
												$outer_class = 'normal_shlfA_pdt_outr_right';
											}
										?>
											<div class="<?=$outer_class?>">
											<div class="normal_shlfA_pdt_top"></div>
											<div class="normal_shlfA_pdt_mid">
											<?=$HTML_title;?>											
											<div class="normal_shlfA_pdt_img_otr">
											<div class="normal_shlfA_pdt_img"><?=$HTML_image?></div>
											<div class="normal_shlfA_pdt_price">
											<div class="normal_shlfA_pdt_price_top"></div>
											<div class="normal_shlfA_pdt_price_mid">
											<?=$HTML_price?>
											</div>
											<div class="normal_shlfA_pdt_price_bottom"></div>
											</div>
											</div>
											
											<?php /*?><div class="normal_shlfA_pdt_com"><?=$HTML_compare?></div><?php */?>
											<div class="normal_shlfA_pdt_des_otr">
											<div class="normal_shlfA_pdt_buy_outr">
											<div class="normal_shlfA_pdt_buy">
											<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
											<input type="hidden" name="fpurpose" value="" />
											<input type="hidden" name="fproduct_id" value="" />
											<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
											<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
											<?
												$class_arr                      = array();
												$class_arr['ADD_TO_CART']       = '';
												$class_arr['PREORDER']          = '';
												$class_arr['ENQUIRE']           = '';
												$class_arr['QTY_DIV']           = 'normal_shlfA_pdt_input';
												$class_arr['QTY']               = ' ';
												$class_arr['BTN_CLS']           = 'normal_shlfA_pdt_buy_btn';
												show_addtocart_v5_ajax($frm_name,$row_prod,$class_arr);
												//show_addtocart($row_prod,$class_arr,$frm_name,false,'','','normal_shlfA_pdt_buy_btn');
											?>
											</form>
											</div>
											<?php /*<div class="normal_shlfA_pdt_info"><?php show_moreinfo($row_prod,'')?></div>
											*/?>
											</div>
											</div>
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
										//echo $HTML_paging;
										echo $HTML_showall;
										?>										 
										</div>
				<?php
				break;
				
									 case '4rowall': // case of three in a row for normal
										$HTML_comptitle = $HTML_maindesc = $HTML_paging = $HTML_showall = '';
										if($cur_title!='')
										{
											   $HTML_comptitle ='  <div class="normal_shlfA_header">
																   <div class="normal_shlfA_hd_inner">
																   <div class="normal_shlfA_hd"><span>'.stripslashes($cur_title).'</span></div>
																   </div> 
																   </div>';
										}										
										if ($tot_cnt>0 and ($_REQUEST['req']!='') and $tot_cnt>$prodperpage)
										{
											$paging 		= paging_footer_advanced($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr);
											$HTML_paging 	='<div class="subcat_nav_pdt_no_shelf"><span>'.$paging['total_cnt'].'</span></div>
																<div class="page_nav_con_shelf">
																<div class="page_nav_top_shelf"></div>
																<div class="page_nav_mid_shelf">
																<div class="page_nav_content_shelf"><ul>';//.'';
											$HTML_paging 	.= $paging['navigation']['start_nav'].$paging['navigation']['page_no'].$paging['navigation']['end_nav'];
											$HTML_paging 	.= ' 
																</ul></div>
																</div>
																<div class="page_nav_bottom_shelf"></div>
																</div>';
										}							
									
									?>								
									
										<div class="normal_shlfA_mid_mid">
										<? 
										echo $HTML_comptitle;
										echo $HTML_maindesc;
										//echo $HTML_paging;
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
												$HTML_title = '<div class="normal_shlfA_pdt_name"><a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">'.stripslash_normal($row_prod['product_name']).'</a></div>';
											}
						if ($cat_det['product_showimage']==1)// Check whether description is to be displayed
											{
												$HTML_image ='<a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">';
												// Calling the function to get the image to be shown
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
												if($desc!='')
												{
													  $HTML_sale = '<div class="normal_shlfA_pdt_sale">'.$desc.'</div>';
												}
											}
											if($row_prod['product_newicon_show']==1)
											{
												$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
												if($desc!='')
												{
													  $HTML_new = '<div class="normal_shlfA_pdt_new">'.$desc.'</div>';
												}
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
												$price_class_arr['normal_class']        = 'normal_shlfB_pdt_priceA';
												$price_class_arr['strike_class']        = 'normal_shlfB_pdt_priceB';
												$price_class_arr['yousave_class']       = 'normal_shlfB_pdt_priceC';
												$price_class_arr['discount_class']      = 'normal_shlfB_pdt_priceC';
												$HTML_price = show_Price($row_prod,$price_class_arr,'shelfcenter_3');
											}
											if($row_prod['product_bulkdiscount_allowed']=='Y')
											{
												$HTML_bulk = '<img src="'.url_site_image('multi-buyA.gif',1).'" />';
											}
											if($row_prod['product_bonuspoints']>0 and $shelfData['shelf_showbonuspoints']==1)
											{
												$HTML_bonus = 'Bonus: '.$row_prod['product_bonuspoints'];
												$bonus_class = 'normal_shlfA_pdt_bonus';
											}
											else
											{
												$HTML_bonus = '&nbsp;';
												$bonus_class = 'normal_shlfA_pdt_bonus_blank';
											}	
											if($comp_active)
												$HTML_compare = dislplayCompareButton($row_prod['product_id'],'','',1,'compare_li_2row');
											if($row_prod['product_freedelivery']==1)
											{
												$HTML_freedel = ' <div class="normal_shlfA_free"></div>';
											}
											$frm_name = uniqid('shelf_');
											if($HTML_bonus!='&nbsp;' or $HTML_rating !='&nbsp;')
											{
												$HTML_bonus_bar = '<div class="normal_shlfA_pdt_bonus_otr">
																	<div class="'.$bonus_class.'">'.$HTML_bonus.'</div>
																	
																	</div>';
											}	
											if($cur_col==0)
											{
												$outer_class = 'normal_shlfA_pdt_outr';
												echo  '<div class="outer_shlfA_container">';
											}	
											else
											{
												$outer_class = 'normal_shlfA_pdt_outr_right';
											}
										?>
											<div class="<?=$outer_class?>">
											<div class="normal_shlfA_pdt_top"></div>
											<div class="normal_shlfA_pdt_mid">
											<?=$HTML_title;?>
											<?
												echo $HTML_sale;
												echo $HTML_new
											?>
											<div class="normal_shlfA_pdt_img_otr">
											<div class="normal_shlfA_pdt_img"><?=$HTML_image?></div>
											<?=$HTML_freedel?>
											<div class="normal_shlfA_pdt_price">
											<div class="normal_shlfA_pdt_price_top"></div>
											<div class="normal_shlfA_pdt_price_mid">
											<?=$HTML_price?>
											</div>
											<div class="normal_shlfA_pdt_price_bottom"></div>
											</div>
											</div>
											
											<?php /*?><div class="normal_shlfA_pdt_com"><?=$HTML_compare?></div><?php */?>
											<?=$HTML_bonus_bar?>
											<div class="normal_shlfA_pdt_des_otr">
											<div class="normal_shlfA_pdt_buy_outr">
											<div class="normal_shlfA_pdt_buy">
											<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
											<input type="hidden" name="fpurpose" value="" />
											<input type="hidden" name="fproduct_id" value="" />
											<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
											<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
											<?
												$class_arr                      = array();
												$class_arr['ADD_TO_CART']       = '';
												$class_arr['PREORDER']          = '';
												$class_arr['ENQUIRE']           = '';
												$class_arr['QTY_DIV']           = 'normal_shlfA_pdt_input';
												$class_arr['QTY']               = ' ';
												$class_arr['BTN_CLS']           = 'normal_shlfA_pdt_buy_btn';
												show_addtocart_v5_ajax($frm_name,$row_prod,$class_arr);
												//show_addtocart($row_prod,$class_arr,$frm_name,false,'','','normal_shlfA_pdt_buy_btn');
											?>
											</form>
											</div>
									        <div class="normal_shlfA_multibuy"><?=$HTML_bulk?></div>
											<div class="normal_shlfA_pdt_des"><?=$HTML_desc?></div>
											<div class="normal_shlfA_pdt_rate"><?=$HTML_rating?></div>
											<?php /*<div class="normal_shlfA_pdt_info"><?php show_moreinfo($row_prod,'')?></div>
											*/?>
											</div>
											</div>
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
										//echo $HTML_paging;
										echo $HTML_showall;
										?>										  
										</div>
			<?php		
				break;
			};
			//echo $HTML_paging;
		}
		// ** Function to show the no products message
		function Show_NoProducts()
		{
			global $Captions_arr;
		/*
		?>
		<div class="sub_cate_con">
		<div class="sub_cate_top"></div>
		<div class="sub_cate_mid">
		<div class="sub_cate_content">
		<div class="sub_cate_hdr"><?php echo stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_PRODUCTS'])?></div>
		<div class="sub_cate_icon"></div>
		</div>
		</div>
		</div>
		<?*/?>
		<div class="cate_content_mid">
		<?php echo stripslash_normal($Captions_arr['CAT_DETAILS']['NO_PROD_MSG'])?>
		</div>
		<?php	
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
					echo '<div class="cat_mid_otr">';
					if($title!='')
						echo '<div class="cate_mid_hdr">'.$title.'</div>';
					$cur_col = 0;
					while ($row_subcat = $db->fetch_array($ret_category))
					{
						$HTML_subcatname = $HTML_image = $HTML_short_desc = $HTML_more_icon = '';
						if($cur_col==0)
						{
							echo '<div class="cat_mid_row_otrA">';
							$cat_main_class_name = 'cat_mid_row_left';
						}
						elseif($cur_col==1)
						{
							$cat_main_class_name = 'cat_mid_row_right';
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
							<div class="cat_mid_row_l_t"></div>
							<div class="cat_mid_row_l_m">
							<div class="cat_mid_pdt_l"><?php echo $HTML_image?></div>
							<div class="cat_mid_pdt_r">
							<div class="cat_mid_pdt_name"><?php echo $HTML_subcatname?></div>
							<div class="cat_mid_pdt_des"><?php echo$HTML_short_desc?></div>
							<div class="cat_mid_pdt_more"><?php echo '<a href="'.url_category($row_subcat['category_id'],$row_subcat['category_name'],1).'" title="'.stripslash_normal($row_subcat['category_name']).'">View Section</a>'?></div>
							</div>
							</div>
							<div class="cat_mid_row_l_b"></div>
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
			
		
		}
	};	
?>
