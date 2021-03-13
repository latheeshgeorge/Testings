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
			$HTML_treemenu = $HTML_catdesc = $HTML_alert = $HTML_icons = '' ;
			if($alert)
			{
				$HTML_alert = '<div class="red_msg">
								- '.$alert.' -
								</div>';
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
							$HTML_image = show_image(url_root_image($catimg_arr[0][$pass_type],1),$row_cat['category_name'],$row_cat['category_name'],'imgwraptext','',1);
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
				
			if($prodshow==true)
			{
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
				$tot_cnt = $num_prods;
			}	
	  ?>
		<form method="post" name="frm_catedetails" id="frm_catedetails" action="" class="frm_cls">
		<input type="hidden" name="fpurpose" value="" />
		<input type="hidden" name="caturl" value="<? echo $url?>" />
		<input type="hidden" name="type_cat" value="cate_root" />
		<input type='hidden' name='category_id' value="<?=$_REQUEST['category_id']?>"/>
		<div class="category_main" id="category_main_div">
		<?php
		$product_div_display = '';
		if($HTML_image)
		{
			$show_image = true;
			// Check whether the category id is there in the session array
			if(is_array($_SESSION['plum_categoryid_arr']))
			{
				if(in_array($_REQUEST['category_id'],$_SESSION['plum_categoryid_arr']))
				{
					$show_image = false;
				}
				else
				{
					$_SESSION['plum_categoryid_arr'][] = $_REQUEST['category_id'];
				}
			}
			else
			{
				$_SESSION['plum_categoryid_arr'][] = $_REQUEST['category_id'];
			}
		?>
		<?php
			if($show_image==true)
			{ 
				echo $HTML_image;
				$product_div_display = 'none';
			}
				
		}
		echo '</div><div id="category_sub_div" style="display:'.$product_div_display.'">';
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
			/*$sql_prods 		= "SELECT count(a.products_product_id) as cnt 
								FROM 
									product_category_map a,products b
								WHERE 
									a.product_categories_category_id = ".$_REQUEST['category_id']." 
									AND a.products_product_id=b.product_id 
									AND b.product_hide='N'";
			$ret_prods		= $db->query($sql_prods);
			$row_prods      = $db->fetch_array($ret_prods);
			$num_prods      = $row_prods['cnt'];
			$tot_cnt = $num_prods;*/
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
			echo $HTML_catbottomdesc;
			echo '</div>
			';
			if($show_image==true)
			{
				if(($product_div_display=='none' and $tot_cnt>0) or ($subcat_exists==true))
				{
				?>
				<script type="text/javascript">
					jQuery.noConflict();
					var $j = jQuery;
					$j(document).ready(
						function(){
						$j("#category_main_div").fadeOut(10000,function() {
							$j("#category_sub_div").show('fast');
						});
						
					});
				</script>
			<?php
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
				echo '<div class="category_main_products">';
				while ($row_subcat = $db->fetch_array($ret_subcat))
				{
					$HTML_subcatname = $HTML_image = $HTML_short_desc = '';
					if($row_cat['category_showimage']==1)
					{
						 $HTML_image = '';
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
									$HTML_image .= url_root_image($img_arr[0][$pass_type],1);
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
										$HTML_image .= url_root_image($img_arr[0][$pass_type],1);
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
									$HTML_image .= url_site_image('no_small_image.gif',1);
								}	
							}
						
					}
					$sr = "'";
				$rp = '&quot;';	
				$pname = str_replace($sr,$rp,stripslash_normal($row_subcat['category_name']));
			?>
					<div class="products_inner"><a href="<?php url_category($row_subcat['category_id'],$row_subcat['category_name'])?>" title="<?php echo stripslash_normal($row_subcat['category_name'])?>"><img src="<?php echo $HTML_image?>" onmouseover="tooltip.show('<?php echo $pname?>');" onmouseout="tooltip.hide();" /></a></div>
			<?php
				}	
				echo '<div>';	
		}
		// ** Function to list the products
		function Show_Products($ret_prod,$tot_cnt,$start_var,$cat_det,$def_orderfield,$def_orderby)
		{
			global $db,$Settings_arr,$Captions_arr,$inlineSiteComponents,$compare_button_displayed;
			$HTML_paging	= $HTML_image = '';
			// ** Showing the products listing either 1 in a row or 3 in a row
			$pass_type = get_default_imagetype('prodcat');
			echo '<div class="category_main_products">';
			while($row_prod = $db->fetch_array($ret_prod))
			{
				$HTML_image = '';
				if ($cat_det['product_showimage']==1)// Check whether description is to be displayed
				{
					// Calling the function to get the image to be shown
					$img_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,0,0,1);
					if(count($img_arr))
					{
						$HTML_image .= url_root_image($img_arr[0][$pass_type],1);
					}
					else
					{
						// calling the function to get the default image
						$no_img = get_noimage('prod',$pass_type); 
						if ($no_img)
						{
							$HTML_image .= $no_img;
						}       
					}       
				$sr = "'";
				$rp = '&quot;';	
				$pname = str_replace($sr,$rp,stripslash_normal($row_prod['product_name']));
			?>		
			<div class="products_inner"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" title="<?php echo stripslash_normal($row_prod['product_name'])?>"><img src="<?php echo $HTML_image?>" onmouseover="tooltip.show('<?php echo $pname?>');" onmouseout="tooltip.hide();" /></a></div>
			<?php
				}
			}
			echo '<div>';
		}
		// ** Function to show the no products message
		function Show_NoProducts()
		{
			global $Captions_arr;
		?>
		<div class="cate_content_mid">
		<?php echo stripslash_normal($Captions_arr['CAT_DETAILS']['NO_PROD_MSG'])?>
		</div>
		<?php	
		}
		function Show_HomeCatgeory($title,$ret_category) 
		{
		}
	};	
?>