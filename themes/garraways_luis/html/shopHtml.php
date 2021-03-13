<?php
	/*############################################################################
	# Script Name 	: ShopHtml.php
	# Description 	: Page which holds the display logic for shops details
	# Coded by 		: Joby
	# Created on	: 30-May-2011
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
			document.getElementById('bw').innerHTML = '<iframe src="http://www.web2pdfconvert.com/convert.aspx?cURL=<? echo $ecom_selfhttp.$_SERVER['HTTP_HOST']?><?=$_SERVER['REQUEST_URI']?>&outputmode=stream&allowactivex=yes&ref=form">';
			show_processing();
			setTimeout('hide_processing()', 20000);
		  }
	  </script>
	  <?php
			$HTML_treemenu = $HTML_catdesc = $HTML_alert = $HTML_icons = '' ;
			$HTML_treemenu = '	<div class="tree_menu_con">
								<div class="tree_menu_top_list"></div>
								<div class="tree_menu_mid_list">
								<div class="tree_menu_content_list">
									<ul class="tree_menu">';
								if($_REQUEST['shop_id'])
								{
								$HTML_treemenu .='			<li>
									<a href="'.url_link('',1).'" title="'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a>
									</li>
									<li>
									<a href="'.url_link('shopbybrand.html',1).'">'.stripslash_normal($Captions_arr['SHOP_DETAILS']['SHOPS_ALL']).'</a>
									</li>
									<li>
									'.$row_shop['shopbrand_name'].'
									</li>';
								}
								else
								{
									$HTML_treemenu .='<li>
									<a href="'.url_link('',1).'" title="'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a>
									</li>
									<li>
									<a href="'.url_link('shopbybrand.html',1).'">'.stripslash_normal($Captions_arr['SHOP_DETAILS']['SHOPS_ALL']).'</a>
									</li>
									';
								}
								$HTML_treemenu .=	'</ul>
								</div>
								</div>
								<div class="tree_menu_bottom_list"></div>
								</div>';
			if($alert)
			{
			$HTML_alert = '<div class="red_msg">
							- '.$alert.' -
							</div>';
			}
			if($_REQUEST['shop_id'])
			{
				if($row_shop['shopbrand_turnoff_mainimage']==0)
				{
					if ($img_support) // ** Support Shop Image
					{
						if ($row_shop['shopbrand_showimageofproduct']==0) // Case to check for images directly assigned to shop
						{
							if ($_REQUEST['shopthumb_id'])	
											$showonly = $_REQUEST['shopthumb_id'];
										else
											$showonly = 0;
							// Calling the function to get the type of image to shown for current 
									   //$pass_type = get_default_imagetype('shop');	
									   $pass_type = 'image_bigcategorypath';
							// Calling the function to get the image to be shown
										$shopimg_arr = get_imagelist('prodshop',$row_shop['shopbrand_id'],$pass_type,0,$showonly,1); 
							if(count($shopimg_arr))
								{
									$exclude_catid 	= $shopimg_arr[0]['image_id']; // exclude id in case of multi images for category
									$HTML_image = '<div class="cat_main_image" align="center">'.show_image(url_root_image($shopimg_arr[0][$pass_type],1),$row_shop['shopbrand_name'],$row_shop['shopbrand_name'],'imgwraptext','',1).'</div>';
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
									$HTML_image =show_image(url_root_image($img_arr[0][$pass_type],1),$row_shop['shopbrand_name'],$row_shop['shopbrand_name'],'imgwraptext','',1);
									$show_noimage = false;
								}
								else// case if no products exists under current shop with image assigned to it
								$show_noimage = true;
							}
							else// case if no products exists under current shop with image assigned to it
								$show_noimage = true;
						}
					}
				}
			$url= url_shops($_REQUEST['shopbrand_id'],$row_shop['shopbrand_name'],'');
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
										shopbrand_product_showimage, shopbrand_product_showtitle,shopbrand_description,
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
			if($_REQUEST['shop_id'])
			  {
			$HTML_icons .= '';//<a  href="javascript:download_pdf()" title="'.stripslash_normal($Captions_arr['SHOP_DETAILS']['SHOPDET_DOWNLOADPDF']).'"><img src="'.url_site_image('pdf.gif',1).'" alt="'.stripslash_normal($Captions_arr['SHOP_DETAILS']['SHOPDET_DOWNLOADPDF']).'" border="0" /></a>';
			}
			$SHOP_desc = stripslashes($row_shop['shopbrand_description']);
			if ($SHOP_desc!='')
			{
			$HTML_shopdesc = '<div class="cate_content_mid">'.
			$SHOP_desc.'</div>';
			}
			$subshopshow 	    = false;
			if($_REQUEST['shop_id'])
			$prodshow		    = true;
			$subshop_exists 	= false;
			if(!$_REQUEST['shop_id'])
			$row_shop['shopbrand_subshoplisttype']='Middle';
			if ($row_shop['shopbrand_subshoplisttype']=='Middle')
				$subshopshow = true;  
			echo  $HTML_treemenu;
			if($_REQUEST['shop_id'])
			{
			?>
			<div class="sub_cat_hdr_otr">
			<div class="sub_cat_hdr"><?php echo stripslashes($row_shop['shopbrand_name'])?></div>
			<div class="sub_cat_hdr_icon"><?=$HTML_icons?></div>
			</div>
			<?php
			}
			echo $HTML_alert;
			echo $HTML_image;
			echo $HTML_shopdesc;
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
								$this->Show_Subshops($ret_subshop,$url,$_REQUEST['shop_id']); // ** Calling the function to show the subcategories
								$subshop_exists = true;
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
				}		
			}				
							global $shelf_for_inner;
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
									$shelf_for_inner	= true;
									include ("includes/base_files/shelf.php");
									$shelf_for_inner	= false;
								}
							}
			if (trim($row_shop['shopbrand_bottomdescription'])!='' and trim($row_shop['shopbrand_bottomdescription'])!='<br>')
			{
				$shop_bottom_desc =  stripslashes($row_shop['shopbrand_bottomdescription']);
			?>
				<div class="cate_content_mid">
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
				if($shopid==0)
				{
				$sql 				= "SELECT general_shopsall_topcontent,general_shopsall_bottomcontent FROM general_settings_sites_common WHERE sites_site_id=".$ecom_siteid;
		        $res_admin 			= $db->query($sql);
		        $fetch_arr_admin 	= $db->fetch_array($res_admin);
				$HTML_topdesc ='';	
					if($fetch_arr_admin['general_shopsall_topcontent']!='')
					{
					$HTML_topdesc .='
						<div class="cate_content_mid" >'.$fetch_arr_admin['general_shopsall_topcontent'].'
							</div>		
						';
					}
				}
				echo $HTML_topdesc;
				if($alert)
				{
				 $HTML_alert = '<div class="red_msg">
									- '.$alert.' -
									</div>';
				}
				echo $HTML_alert;
					echo '
					<div class="sub_cate_mid_cont">
					<div class="sub_cate_mid_cont_top">
					<ul class="sub_cate_mid_ul">';
					$cnt = 1;
					while ($row_subshop = $db->fetch_array($ret_subshop))
					{
						$HTML_subshopname = $HTML_image = $HTML_short_desc = $HTML_bottomdesc = '';
						$class = ( $cnt % 2 ? 'sub_cate_mid_ul_a' : 'sub_cate_mid_ul_b' );
						$HTML_subshopname = '<a href="'.url_shops($row_subshop['shopbrand_id'],$row_subshop['shopbrand_name'],1).'" title="'.stripslash_normal($row_subshop['shopbrand_name']).'" class="'.$class.'"><span>'.stripslash_normal($row_subshop['shopbrand_name']).'</span></a>';
					?>
							<li><?=$HTML_subshopname?></li>
					<?					
						$cnt++;
						}
							
						echo '</ul></div>';
						echo  '</div>';
						echo $HTML_bottomdesc;		
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
			$HTML_paging	= '';

			if ($tot_cnt>0)
			{
				$pg_variable				= 'shopdet_pg';
				$start_var 					= prepare_paging($_REQUEST[$pg_variable],$prodperpage,$tot_cnt);
				$path 						= '';
				$pageclass_arr['container'] = 'pagenavcontainer';
				$pageclass_arr['navvul']	= 'pagenavul';
				$pageclass_arr['current']	= 'pagenav_current';
				
				$query_string = "&amp;shopdet_sortby=".$prodsort_by.'&amp;shopdet_sortorder='.$prodsort_order.'&amp;shopdet_prodperpage='.$prodperpage;
				$paging 		= paging_footer_advanced($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Product(s)',$pageclass_arr);
				if($start_var['pages']>1)
				{
					$HTML_paging	= '<div class="page_nav_content">
										<ul>
										'.$paging['navigation']['start_nav'].$paging['navigation']['page_no'].$paging['navigation']['end_nav'].'
										</ul>
										</div>';
				}					
									
			}
			if($paging['total_cnt'])
				$HTML_totcnt = '<div class="subcat_nav_pdt_no"><span>'.$paging['total_cnt'].'</span></div>';
			$HTML_topcontent = 	'<div class="subcat_nav_content" >
								<div class="subcat_nav_top"></div>
								<div class="subcat_nav_bottom">
								'.$HTML_totcnt.'
								<div class=" page_nav_cont">
								<div class="navtxt">'.stripslash_normal($Captions_arr['SHOP_DETAILS']['SHOPDET_SORTBY']).'</div>
								<div class="navselect">';
								$selval_arr = array (
														'custom'		=> stripslash_normal($Captions_arr['SHOP_DETAILS']['SHOPDET_DEFAULT']),
														'product_name'	=> stripslash_normal($Captions_arr['SHOP_DETAILS']['SHOPDET_PRODNAME']),
														'price'			=> stripslash_normal($Captions_arr['SHOP_DETAILS']['SHOPDET_PRICE']),
														'product_id'	=> stripslash_normal($Captions_arr['SHOP_DETAILS']['SHOPDET_DATEADDED']));
			$HTML_topcontent .=	generateselectbox('shopdet_sortbytop',$selval_arr,$prodsort_by,'','',0,'',false,'shopdet_sortbytop');
								$selord_arr = array (
														'ASC'	=> stripslash_normal($Captions_arr['SHOP_DETAILS']['SHOPDET_LOW2HIGH']),
														'DESC'	=> stripslash_normal($Captions_arr['SHOP_DETAILS']['SHOPDET_HIGH2LOW']) 
													);
			$HTML_topcontent .=	generateselectbox('shopdet_sortordertop',$selord_arr,$prodsort_order,'','',0,'',false,'shopdet_sortordertop');
			$HTML_topcontent .=	'								
								</div>
								</div>
								<div class=" page_nav_contA">
								<div  class="navtxt">'.stripslash_normal($Captions_arr['SHOP_DETAILS']['SHOPDET_ITEMSPERPAGE']).'</div>
								<div class="navselect">';
			if(!$_REQUEST['shopdet_prodperpage'])
			{
				$catdet_prodperpage = $Settings_arr['product_maxcntperpage'];
			}
			else
			{
				$catdet_prodperpage = $_REQUEST['shopdet_prodperpage'];
			}
			$perpage_arr = array();
			for ($ii=$Settings_arr["productlist_interval"];$ii<=$Settings_arr["productlist_maxval"];$ii+=$Settings_arr["productlist_interval"])
				$perpage_arr[$ii] = $ii;
			$HTML_topcontent .=	generateselectbox('shopdet_prodperpagetop',$perpage_arr,$prodperpage,'','',0,'',false,'shopdet_prodperpagetop');
			$HTML_topcontent .= '
								</div>
								</div>
								<div class=" page_nav_contB">
								<input type="button" name="submit_Page" value="'.stripslash_normal($Captions_arr['SHOP_DETAILS']['SHOPDET_GO']).'" class="nav_button" onclick="handle_shopdetailsdropdownval_sel(\''.url_shops($_REQUEST['shop_id'],$shop_det['shopbrand_name'],4).'\',\'shopdet_sortbytop\',\'shopdet_sortordertop\',\'shopdet_prodperpagetop\')" />
								</div>
								</div>';
			$HTML_topcontent .= $HTML_paging;
			$HTML_topcontent .= '</div>';
		
			echo $HTML_topcontent;
			/*echo $HTML_paging;*/
			// ** Showing the products listing either 1 in a row or 3 in a row
			$pass_type = get_default_imagetype('prodcat');
			$comp_active = isProductCompareEnabled();
			
			$max_col = 3;
			$cur_col = 0;
			$prodcur_arr = array();
			?>
			<div class="product_list_outer"> 
			<div class="pdt_list_outer">
			<?php
			while($row_prod = $db->fetch_array($ret_prod))
			{
				$prodcur_arr[] = $row_prod;
				$HTML_title = $HTML_image = $HTML_desc = '';
				$HTML_sale = $HTML_new = $HTML_compare = $HTML_rating = '';
				$HTML_price = $HTML_bulk= $HTML_bonus = $HTML_compare = $HTML_freedel= $HTML_bonus_bar = '';
				if($shop_det['shopbrand_product_showtitle']==1)// whether title is to be displayed
				{
				$HTML_title = '<div class="pdt_list_pdt_name"><a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">'.stripslash_normal($row_prod['product_name']).'</a></div>';
				}
				if ($shop_det['shopbrand_product_showimage']==1)// Check whether description is to be displayed
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
				if ($shop_det['shopbrand_product_showshortdescription']==1)// Check whether description is to be displayed
				{
				$HTML_desc = stripslash_normal($row_prod['product_shortdesc']);
				}
				$price_class_arr['class_type']          = 'div';
				$price_class_arr['normal_class']        = 'normal_shlfB_pdt_priceA';
				$price_class_arr['strike_class']        = 'normal_shlfB_pdt_priceB';
				$price_class_arr['yousave_class']       = 'normal_shlfB_pdt_priceC';
				$price_class_arr['discount_class']      = 'normal_shlfB_pdt_priceC';
				 $price_arr =  show_Price($row_prod,array(),'compshelf',false,3);
				if($price_arr['discounted_price'])
					$HTML_price = $price_arr['discounted_price'];
				else
					$HTML_price = $price_arr['base_price'];
			
				//$HTML_price = show_Price($row_prod,$price_class_arr,'shelfcenter_3');
				if($cur_col==0)
				{
					echo  '<div class="pdt_list_thumb_outer">';
				}	
			if($cur_col%2==0 && $cur_col!=0)	
					{
					 $cls = "pdt_list_pdt_rt";
					}
					else
					{
					 $cls = "pdt_list_pdt";
					}
				?>
				
										
				
				<div class="<?php echo $cls?>">
			<div class="pdt_list_pdt_mid">
			<?php
			if($row_prod['product_saleicon_show']==1)
			{
				$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
				//if($desc!='')
				{
					  $HTML_sale = '<div class="pdt_list_sale"></div>';
				}
			}
			if($row_prod['product_newicon_show']==1)
			{
				$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
				//if($desc!='')
				{
					  $HTML_new = '<div class="pdt_list_new"></div>';
				}
			}
			echo $HTML_new;
			echo  $HTML_sale;
			?>
			

			<?=$HTML_title;?>
			
			<?php
			if($shop_det['shopbrand_product_showrating']==1)
			{
				$module_name = 'mod_product_reviews';
				if(in_array($module_name,$inlineSiteComponents))
				{
					if($row_prod['product_averagerating']>=0)
					{
					$HTML_rating = '<div class="list_d_pdt_rate">'.display_rating($row_prod['product_averagerating'],1).'</div>';
					}
				}
			}
			echo $HTML_rating;
			?>
			<div class="pdt_list_pdt_r"><?=$HTML_image?></div>
			<div class="pdt_list_pdt_l">
			<div class="pdt_list_pdt_buy_otr">
			<div class="pdt_list_pdt_price">
				<?
				if ($shop_det['shopbrand_product_showprice']==1)
				{
					echo $HTML_price;
				}	
				?></div>
			<div class="pdt_list_pdt_buy">
				<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslash_normal($Captions_arr['COMMON']['COMMON_BUY_NOW'])?></a>
						 
			</div>
			   </div>
				  </div>
			   <div class="pdt_list_m_otr">
			   <?php
				if($row_prod['product_bonuspoints']>0 and $shop_det['shopbrand_product_showbonuspoints']==1)
				{
					$HTML_bonus = 'Bonus: '.$row_prod['product_bonuspoints'].'';
				}
				else
				{
					$HTML_bonus = '';
				}	
			   
			   ?>
		<div class="pdt_list_bonus">  <?php echo $HTML_bonus;?> </div>             
		<div class="pdt_list_pdt_more"><a class="" title="Love Meter T-Shirt Heart Lights" href="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>">More Info</a></div>
			</div>   
			
			<?php
			if($row_prod['product_bulkdiscount_allowed']=='Y' || $row_prod['product_freedelivery']==1)
			{
				echo "<div class='pdt_list_free_otr'>";
			}
				
				if($row_prod['product_freedelivery']==1)
				{
					echo $HTML_freedel = ' <div class="pdt_list_free_del"> </div>';
				}
				if($row_prod['product_bulkdiscount_allowed']=='Y')
				{
					echo $HTML_bulk = '<div class="pdt_list_free_bulk"> </div>';
				}
			if($row_prod['product_bulkdiscount_allowed']=='Y' || $row_prod['product_freedelivery']==1)
			{
			   echo "</div>";
			}
			?>
			  <div class="pdt_list_pdt_des"><?php echo $HTML_desc;?></div>
			
			
		   </div>
			</div>
		   
		   <?php
		   
		   
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
			</div>
			</div>
			
		<?php

			echo $HTML_paging;
		}
		// ** Function to show the no products message
	};	
?>
