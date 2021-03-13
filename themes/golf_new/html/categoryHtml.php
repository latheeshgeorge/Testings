<?php
	/*############################################################################
	# Script Name 	: categoryHtml.php
	# Description 	: Page which holds the display logic for category details
	# Coded by 		: Sny
	# Created on	: 16-Jan-2008
	# Modified by	: Sny
	# Modified On	: 22-Jan-2008
	##########################################################################*/
	class category_Html
	{
		// Defining function to show the selected category details
		function Show_CategoryDetails($row_cat)
		{
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$ecom_themename,$Settings_arr,$ecom_themeid,$default_layout,$inlineSiteComponents,$Settings_arr;
			$Captions_arr['CAT_DETAILS'] 	= getCaptions('CAT_DETAILS');
			// ** Fetch the category details
			//$row_cat	= $db->fetch_array($ret_cat);
			// ** Check whether category image module is there for current site
			if(in_array('mod_catimage',$inlineSiteComponents))
			{
				$img_support = true;
			}
			else
				$img_support = false;
		   $custom_id = get_session_var('ecom_login_customer');
		  if($_REQUEST['type_cat']=='cate_root'){
			   if($_REQUEST['resultcat']=='added')
			   {
			   $alert = $Captions_arr['CAT_DETAILS']['ADD_MSG'];
			   }
			   else if($_REQUEST['resultcat']=='removed')
			   {
			   $alert = $Captions_arr['CAT_DETAILS']['REM_MSG'];
			   }
			 } 
			 if ($row_cat['category_turnoff_treemenu']==0)
			{
		?>
				<div class="treemenu"><?php echo generate_tree($_REQUEST['category_id'],-1)?></div>
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
		<?php
		
		if ($ecom_siteid == 72)
		{
		?>
		<tr>
			<td colspan="3" align="center">
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
		}
		?>
        <tr>
          <td colspan="3" >
			  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="categoreyimage">
			   <tr>
			  <td align="center"><h1><?php echo ucwords(stripslashes($row_cat['category_name']))?></h1></td> 
			   </tr>
				<tr>
						<td width="28%" align="left" valign="top"  class="categoreyimagetd">
						<?php
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
										show_image(url_root_image($catimg_arr[0][$pass_type],1),$row_cat['category_name'],$row_cat['category_name'],'imgwraptext');
										//$show_noimage 	= false;
									}
									/*else
										$show_noimage = true;*/
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
											show_image(url_root_image($img_arr[0][$pass_type],1),$row_cat['category_name'],$row_cat['category_name'],'imgwraptext');
											//$show_noimage = false;
										}
									}
								}	
							
						}
					}
					// ** Showing the category description if it exists
					if ($row_cat['category_paid_for_longdescription']=='Y' and trim($row_cat['category_paid_description'])!='' and trim($row_cat['category_paid_description'])!='<br>')
					{
						$cat_desc =  stripslashes($row_cat['category_paid_description']);
					}
					elseif (trim($row_cat['category_shortdescription'])!='')
					{
						$cat_desc = nl2br(stripslashes($row_cat['category_shortdescription']));
					}
					if ($cat_desc!='')
					{
							echo $cat_desc;
					}
					?>							
					</td>
					
				</tr>
				<tr>
				<td align="right">
				<?php 
					$url= url_category($_REQUEST['category_id'],$row_cat['category_name'],'');
					?>
						<form method="post" name="frm_catedetails" id="frm_catedetails" action="" class="frm_cls">
			            <input type="hidden" name="fpurpose" value="" />
			            <input type="hidden" name="caturl" value="<? echo $url?>" />
						<input type="hidden" name="type_cat" value="cate_root" />
	                    <input type='hidden' name='category_id' value="<?=$_REQUEST['category_id']?>"/>
					   	<div class="categoreyimagediv" align="right">
				   <? 
				   	if($custom_id)
				   	{
					  	$sql_cat_det = "SELECT customer_customer_id FROM customer_fav_categories WHERE sites_site_id=$ecom_siteid AND categories_categories_id=".$_REQUEST['category_id'] ." AND customer_customer_id=$custom_id LIMIT 1";
					    $ret_num_cat= $db->query($sql_cat_det);
						  if($db->num_rows($ret_num_cat)==0) 
						  { 
						   ?>
						   	<a href="javascript:if(confirm('<?php echo $Captions_arr['CAT_DETAILS']['CAT_FAVOURITE_CONFIRM']?>')) { document.frm_catedetails.fpurpose.value='add_favourite';document.frm_catedetails.submit();}" title="<?php echo $Captions_arr['CAT_DETAILS']['CAT_FAVOURITE']?>"><img src="<?php url_site_image('add_to_fav_icon.gif')?>" alt="<?php echo $Captions_arr['CAT_DETAILS']['CAT_FAVOURITE']?>" border="0" /></a>
						   <? 
						   }
						   else if($db->num_rows($ret_num_cat)>0)
						   {
						   ?>
						   	<a href="javascript:if(confirm('<?php echo $Captions_arr['CAT_DETAILS']['CAT_REM_FAVOURITE_CONFIRM']?>')){ document.frm_catedetails.fpurpose.value='rem_favourite';document.frm_catedetails.submit(); }" title="<?php echo $Captions_arr['CAT_DETAILS']['CAT_REM_FAVOURITE']?>"><img src="<?php url_site_image('rem_from_fav_icon.gif')?>" alt="<?php echo $Captions_arr['CAT_DETAILS']['CAT_REM_FAVOURITE']?>" border="0" /></a>
						   <?
						   }
			   	 	}
					if ($row_cat['category_turnoff_pdf']==0)
					{
				?>
							<!--<a  href="<?/*=url_Cat_PDF($_REQUEST['category_id'],$row_cat['category_name'])*/?>" title="<?php /*echo $Captions_arr['CAT_DETAILS']['CATDET_DOWNLOADPDF']*/?>"><img src="<?php /*url_site_image('pdf_download.gif')*/?>" alt="<?php /*echo $Captions_arr['CAT_DETAILS']['CATDET_DOWNLOADPDF']*/?>" border="0" /></a>-->
							<a  href="javascript:download_pdf_stream('<?php echo $_SERVER['HTTP_HOST']?>','<?php echo $_SERVER['REQUEST_URI']?>')" title="<?php echo $Captions_arr['CAT_DETAILS']['CATDET_DOWNLOADPDF']?>"><img src="<?php url_site_image('pdf_download.gif')?>" alt="<?php echo $Captions_arr['CAT_DETAILS']['CATDET_DOWNLOADPDF']?>" border="0" /></a>
				<?php	
				 
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
				if($num_prods>0) {	
				   	?>
						<a href="<?php url_category_rss($row_cat['category_id'],$row_cat['category_name'])?>" title="<?php echo $Captions_arr['CAT_DETAILS']['CAT_RSS']?>"><img src="<?php url_site_image('rss_icon.gif')?>" alt="<?php echo $Captions_arr['CAT_DETAILS']['CAT_RSS']?>" border="0" /></a>
				 <? } ?>  		</div>
				   	</form>
				   </td>
				</tr>
			 <?php
					if ($exclude_catid)
					{
						// Calling the function to get the type of image to shown for current 
						$catimg_arr = get_imagelist('prodcat',$row_cat['category_id'],'image_thumbpath',$exclude_catid,0);
					}	
					if(count($catimg_arr)>0 and $row_cat['category_turnoff_moreimages']==0)// Case if more than one image assigned to category
					{	
					?>
						<tr>
							<td align="left" <?php echo ($img_support)?'colspan="2"':''?> valign="bottom" class="moreimages">
							<?php echo $Captions_arr['CAT_DETAILS']['CATDET_MOREIMAGES']?>
							</td>
						</tr>	
						<tr>
							<td align="left" <?php echo ($img_support)?'colspan="2"':''?> valign="bottom" class="">
								<table width="100%" cellpadding="0" cellspacing="0" border="0">
								<tr>
								<?php
									$maximg_col = 3;
									$curimg_col = 0;
									foreach ($catimg_arr as $k=>$v)
									{
										?>
										<td valign="bottom" class="moreimages_td"><a href="<?php url_root_image($v['image_extralargepath'])?>" title="<?php echo $row_cat['category_name']?>"  rel='lightbox[gallery]'>
										<?php show_image(url_root_image($v['image_thumbpath'],1),$row_cat['category_name'],$row_cat['category_name']);
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
				?>
			  </table>
		  </td>
        </tr>
		<?php
				$subcatshow 	= false;
				$prodshow		= false;
				$subcat_exists 	= false;
				if ($row_cat['category_subcatlisttype']=='Middle' or $row_cat['category_subcatlisttype']=='Both')
					$subcatshow = true;
				// Show the products in middle only or in both.
				if ($row_cat['product_displaywhere'] == 'middle' or $row_cat['product_displaywhere'] == 'both')
					$prodshow = true;
					
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
						// ** Check whether any subcategories exists
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
					?>
							<tr>
								<td colspan="3">
					<?php	
									$this->Show_Subcategories($ret_subcat,$url,$row_cat); // ** Calling the function to show the subcategories
									$subcat_exists = true;
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
					
					$prodsort_add_condition	 = '';
					switch ($prodsort_by)
					{
						case 'custom': // case of order by customer fixed
						$prodsort_by		= 'b.product_order';
						break;
						case 'product_name': // case of order by product name
						$prodsort_by		= 'a.product_name';
						break;
						case 'price': // case of order by price
						//$prodsort_by		= 'a.product_webprice';
						if($ecom_siteid==72)
						{
							$prodsort_by		= 'discounted_price';
							$prodsort_add_condition = ", 
										(CASE a.product_discount_enteredasval 
											WHEN '0' THEN (a.product_webprice-(a.product_webprice * a.product_discount /100)) 
											WHEN '1' THEN (a.product_webprice-a.product_discount) 
											WHEN '2' THEN (a.product_discount)  
										END) as discounted_price  ";
						}
						else
						{
							$prodsort_by		= 'a.product_webprice';
							$prodsort_add_condition = '';
						}	
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
									a.product_freedelivery $prodsort_add_condition               
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
					//$this->Show_Products($ret_prod,$tot_cnt,$start_var,$row_cat['category_name'],$row_cat['default_catgroup_id'],$row_cat['product_displaytype'],$row_cat['product_showimage'],$row_cat['product_showtitle'],$row_cat['product_showshortdescription'],$row_cat['product_showprice'],$base_sort_by,$prodsort_order); // ** Calling function to show the products under current category
					$this->Show_Products($ret_prod,$tot_cnt,$start_var,$row_cat,$base_sort_by,$prodsort_order); // ** Calling function to show the products under current category
				}
				else
				{
					if ($subcat_exists==false and $row_cat['category_turnoff_noproducts']==0)// ** Show the no products only if there exists no subcategories for current product
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
			<td colspan="3" align="left">
			<?php 
			include ("includes/base_files/combo_middle.php");
			?>
			</td>
		</tr>	
		<?php
		if ($ecom_siteid != 72)
		{
		?>
		<tr>
			<td colspan="3" align="center">
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
		}
			if (trim($row_cat['category_bottom_description'])!='' and trim($row_cat['category_bottom_description'])!='<br>')
			{
				$cat_bottom_desc =  stripslashes($row_cat['category_bottom_description']);
			?>
				<tr>
					<td colspan="3">
						<table width="100%" border="0" cellpadding="0" cellspacing="0" class="categoreyimage">
						<tr>
						<td width="28%" align="left" valign="top"  class="categoreyimagetd">
						<?php echo stripslashes($cat_bottom_desc);?>
						</td>
						</tr>
						</table>
					</td>
				</tr>
			<?php	
			}	
			?>	
        </table>
		<?php	
		}
		// ** Function to show the subcategories
		function Show_Subcategories($ret_subcat,$url,$row_cat)
		{
			global $db,$inlineSiteComponents,$Captions_arr,$Settings_arr,$ecom_siteid;
			if(in_array('mod_catimage',$inlineSiteComponents))
			{
				$img_support = true;
			}
			else
				$img_support = false;
				
				$custom_id = get_session_var('ecom_login_customer');
			
			$subcategory_showimagetype = $row_cat['subcategory_showimagetype'];
				
		    if($_REQUEST['type_cat']=='sub_cat'){
				   if($_REQUEST['resultcat']=='added')
				   {
				   $alert = $Captions_arr['CAT_DETAILS']['ADD_MSG'];
				   }
				   else if($_REQUEST['resultcat']=='removed')
				   {
				   $alert = $Captions_arr['CAT_DETAILS']['REM_MSG'];
				   }
			 } 
		?>
			  	<table width="100%" border="0" cellpadding="0" cellspacing="5" class="subcategoreytable">
				  <?php
				  	if ($db->num_rows($ret_subcat)==1)
					{
					 if($Captions_arr['CAT_DETAILS']['CATDET_SUBCAT']!='')
					 {
					?>
						<tr>
							<td colspan="3" align="left" valign="middle" class="subcategoreyheader">
							<?PHP
								echo $Captions_arr['CAT_DETAILS']['CATDET_SUBCAT'];
							?>
							 </td>
						</tr>
					<?PHP
					  }
					}
					else
					{
					 if($Captions_arr['CAT_DETAILS']['CATDET_SUBCATS']!='')
					 {
					?>
					<tr>
					  <td colspan="3" align="left" valign="middle" class="subcategoreyheader">
					   <?PHP
							echo $Captions_arr['CAT_DETAILS']['CATDET_SUBCATS'];
					  ?>	
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
							- <?php echo $alert?> -
							</td>
						</tr>
				<?php
					}
					switch ($row_cat['category_subcatlistmethod'])
					{
						case '1row':
						?>
						<tr>
							<td colspan="3" align="left" valign="middle">
							<table width="100%" cellpadding="0" cellspacing="0" border="0" class="subcategoreytable">
							<?php
							while ($row_subcat = $db->fetch_array($ret_subcat))
							{			
							?>
								<tr>
								<td align="center" class="subcategoreyimage" width="33%">
								<?php
								if($img_support && $row_cat['category_showimage']==1  && $subcategory_showimagetype!='None' ) // ** Show sub category image only if catimage module is there for the site subcategoreynamelink
									{
								  ?>
										<a href="<?php url_category($row_subcat['category_id'],$row_subcat['category_name'],-1,$_REQUEST['catgroup_id'],0)?>"  title="<?php echo stripslashes($row_subcat['category_name'])?>">
								 <?php
										
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
												show_image(url_root_image($img_arr[0][$pass_type],1),$row_subcat['category_name'],$row_subcat['category_name']);
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
													show_image(url_root_image($img_arr[0][$pass_type],1),$row_subcat['category_name'],$row_cat['category_name']);
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
												show_image($no_img,$row_subcat['category_name'],$row_subcat['category_name']);
											}	
										}
									?>
										</a>
								  <?php
									}
								?>	
								</td>
								<td align="left" class="subcategorey_text" colspan="2">
								<?php
									if($row_cat['category_showname']==1)
									{
								  ?>
									 <a href="<?php url_category($row_subcat['category_id'],$row_subcat['category_name'],-1)?>" class="subcategoreynamelink" title="<?php echo stripslashes($row_subcat['category_name'])?>"><?php echo stripslashes($row_subcat['category_name'])?></a>
								 <?php
									}
								 ?>
								<h6 class="shelfBproddes">
								<?php
									if($row_cat['category_showshortdesc']==1)
									{
										if($row_cat['category_showname']==1)
											echo '<br/>';
										echo  stripslashes($row_subcat['category_shortdescription']);
									}
								?>
								</h6>
								</td>
								</tr>
							<?php
							}
							?>
							</table>
							</td>
						</tr>	
						<?php
						break;
						case '3row': // 3 in a row
						$max_col = 3;
						$cur_col = 0;
					while ($row_subcat = $db->fetch_array($ret_subcat))
					{
						if($cur_col==0)
							echo "<tr>";
				?>
						<td width="33%" align="center" valign="middle" class="subcategoreyimage" onmouseover="this.className='subcategory_hover'" onmouseout="this.className='subcategoreyimage'">
						  <?php
						    if($row_cat['category_showname']==1)
							{
						  ?>
						 <a href="<?php url_category($row_subcat['category_id'],$row_subcat['category_name'],-1)?>" class="subcategoreynamelink" title="<?php echo stripslashes($row_subcat['category_name'])?>"><?php echo stripslashes($row_subcat['category_name'])?></a>
						<?php
							}
						  	if($img_support and $row_cat['category_showimage']==1 && $subcategory_showimagetype!='None' ) // ** Show sub category image only if catimage module is there for the site subcategoreynamelink
							{
						  ?>
						  		<a href="<?php url_category($row_subcat['category_id'],$row_subcat['category_name'],-1,$_REQUEST['catgroup_id'],0)?>"  title="<?php echo stripslashes($row_subcat['category_name'])?>">
						 <?php
								
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
										show_image(url_root_image($img_arr[0][$pass_type],1),$row_subcat['category_name'],$row_subcat['category_name']);
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
											show_image(url_root_image($img_arr[0][$pass_type],1),$row_subcat['category_name'],$row_cat['category_name']);
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
										show_image($no_img,$row_subcat['category_name'],$row_subcat['category_name']);
									}	
								}
							?>
								</a>
						  <?php
						  	}
							if($row_cat['category_showshortdesc']==1)
							{
							?>
								<h6 class="shelfBproddes">
							<?php	
								if($row_cat['category_showname']==1)
									echo '<br/>';
								echo  stripslashes($row_subcat['category_shortdescription']);
							?>
							</h6>
							<?php	
							}
							?>
						</td>
				<?php
						$cur_col++;
						if ($cur_col>=$max_col)
						{
							$cur_col = 0;
							echo "</tr>";
						}
					}
					if ($cur_col<$max_col && $cur_col>0)
						echo '<td colspan="'.($max_col-$cur_col).'" class="subcategoreyimage">&nbsp;</td></tr>';
					?>
					
					<?php						
					break;
					};		
				?>	
			  </table>
		<?php
		}
		
		// ** Function to list the products
		//function Show_Products($ret_prod,$tot_cnt,$start_var,$catname,$catgroupid,$displaytype,$show_image,$show_title,$show_desc,$show_price,$def_orderfield,$def_orderby)
		function Show_Products($ret_prod,$tot_cnt,$start_var,$cat_det,$def_orderfield,$def_orderby)
		{
			global $db,$Settings_arr,$Captions_arr,$inlineSiteComponents,$compare_button_displayed;
			$showqty	= $Settings_arr['show_qty_box'];// show the qty box
		    $prodsort_by			= ($_REQUEST['catdet_sortby'])?$_REQUEST['catdet_sortby']:$def_orderfield;
			$prodperpage			= ($_REQUEST['catdet_prodperpage'])?$_REQUEST['catdet_prodperpage']:$Settings_arr['product_maxcntperpage'];// product per page
			$prodsort_order	= ($_REQUEST['catdet_sortorder'])?$_REQUEST['catdet_sortorder']:$def_orderby;
		?>
			<tr>
				<td colspan="3" class="shelfAheader" ><?php echo paging_show_totalcount($tot_cnt,'Product(s)',$start_var['pg'],$start_var['pages'])?><?php if(isProductCompareEnabled() && !$compare_button_displayed) {$compare_button_displayed = true; ?><form name="add_to_compare" id="add_to_compare" action="" method="post" ><input type="hidden" name="submit_Compare_pdts" id="submit_Compare_pdts" value="1"><input type="hidden" name="compare_products" id="compare_products" value=""><!--<input type="submit" name="submit_Compare_pdts" value="ADD TO COMPARE" class="buttonred_large" onclick="handle_addtoCompare()" />-->	</form>		<? }?></td></tr>
			<tr>
				<td colspan="3" align="left" valign="top" class="shelfAheader">
					<table width="100%" border="0" cellpadding="0" cellspacing="0" class="productpagenavtable">
					<tr>
						<td width="63%" height="30" align="right" valign="middle" class="productpagenavtext"><?php echo $Captions_arr['CAT_DETAILS']['CATDET_SORTBY']?>
                          <select name="catdet_sortbytop" id="catdet_sortbytop">
						   <option value="custom" <?php echo ($prodsort_by=='custom')?'selected="selected"':''?>><?php echo $Captions_arr['CAT_DETAILS']['CATDET_DEFAULT']?></option>
                            <option value="product_name" <?php echo ($prodsort_by=='product_name')?'selected="selected"':''?>><?php echo $Captions_arr['CAT_DETAILS']['CATDET_PRODNAME']?></option>
                          <option value="price" <?php echo ($prodsort_by=='price')?'selected="selected"':''?>><?php echo $Captions_arr['CAT_DETAILS']['CATDET_PRICE']?></option>
                    	  <option value="product_id" <?php echo ($prodsort_by=='product_id')?'selected="selected"':''?>><?php echo $Captions_arr['CAT_DETAILS']['SHOPDET_DATEADDED']?></option>
                        </select>
                          <select name="catdet_sortordertop" id="catdet_sortordertop">
                            <option value="ASC" <?php echo ($prodsort_order=='ASC')?'selected="selected"':''?>><?php echo $Captions_arr['CAT_DETAILS']['CATDET_LOW2HIGH']?></option>
                            <option value="DESC" <?php echo ($prodsort_order=='DESC')?'selected="selected"':''?>><?php echo $Captions_arr['CAT_DETAILS']['CATDET_HIGH2LOW']?></option>
                                                    </select></td>
						<td width="37%" align="right" valign="middle" class="productpagenavtext"><?php echo $Captions_arr['CAT_DETAILS']['CATDET_ITEMSPERPAGE']?>
						<?php
						if(!$_REQUEST['catdet_prodperpage']){
						$catdet_prodperpage = $Settings_arr['product_maxcntperpage'];
						}else{
						$catdet_prodperpage = $_REQUEST['catdet_prodperpage'];
						}
						?>
						<select name="catdet_prodperpagetop" id="catdet_prodperpagetop">
						<?php
							for ($ii=$Settings_arr["productlist_interval"];$ii<=$Settings_arr["productlist_maxval"];$ii+=$Settings_arr["productlist_interval"])
							{
						?>
								<option value="<?php echo $ii?>" <?php echo ($catdet_prodperpage==$ii)?'selected="selected"':''?>><?php echo $ii?></option>
						<?php	
							}
						?>
						</select>
						<input type="button" name="submit_Page" value="<?php echo $Captions_arr['CAT_DETAILS']['CATDET_GO']?>" class="buttonred" onclick="handle_categorydetailsdropdownval_sel('<?php echo url_category($_REQUEST['category_id'],$cat_det['category_name'],4,$_REQUEST['catgroup_id'],0)?>','catdet_sortbytop','catdet_sortordertop','catdet_prodperpagetop')" />
						</td>
					</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="3">
				<?php
						// ** Showing the products listing either 1 in a row or 3 in a row
						$pg_variable = 'catdet_pg';
						$pass_type = get_default_imagetype('prodcat');
						$prod_compare_enabled = isProductCompareEnabled();
						switch($cat_det['product_displaytype'])
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
												
												$query_string = "&amp;catdet_sortby=".$_REQUEST['catdet_sortby'].'&amp;catdet_sortorder='.$_REQUEST['catdet_sortorder'].'&amp;catdet_prodperpage='.$_REQUEST['catdet_prodperpage'];
												paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr); 	
											?>	
											</td>
										</tr>
									<?php
									}
									// Calling the function to get the type of image to shown for current 
									while($row_prod = $db->fetch_array($ret_prod))
									{
										$prodcur_arr[] = $row_prod;

									?>						
									<tr >
									<td align="left" valign="middle"class="newshelfBtabletd" onmouseover="this.className='newnormalshelf_hover'" onmouseout="this.className='newshelfBtabletd'">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" class="newshelfBtableinner">
									<tr>
									<td class="newshelfBtableinnertd" valign="top" align="left">
									<? if($cat_det['product_showtitle']==1)// whether title is to be displayed
									 {
									?>
										<h2 class="newshelfBprodname"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h2>
									<? }
									?>
									<? if ($cat_det['product_showshortdescription']==1)// Check whether description is to be displayed
										{
									?>
											<h6 class="newshelfBproddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6>
									<? 
										}
										if($cat_det['product_showrating']==1)
										{
											$module_name = 'mod_product_reviews';
											if(in_array($module_name,$inlineSiteComponents))
											{
												if($row_prod['product_averagerating']>=0)
												{
											?>
												<div class="shlfa_star">
												<?php
													display_rating($row_prod['product_averagerating']);
													?>
												</div>
											<?php
												}
											}
										}		
									    if($row_prod['product_bulkdiscount_allowed']=='Y'  || $row_prod['product_freedelivery']==1 )
										{
										?>
											<div class="shlf_sale">
											<? if($row_prod['product_bulkdiscount_allowed']=='Y')
											{
											?>
											<img src="<?php url_site_image('bulk-discount.gif')?>" alt="Bulk Discount"/>
											<?
											}
											if($row_prod['product_freedelivery']==1){
											?>
											<img src="<?php url_site_image('free-img.gif')?>" alt="Free Delivery"/>
											<?
											}
											?>
											</div>
										<?php
										}
										
										?>
									</td>
									
									<td class="newshelfBtableinnertd" valign="middle" align="center">
									
									<? 
										if ($cat_det['product_showimage']==1)// Check whether description is to be displayed
										{
									?>
											<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
									<?php
										
											
											// Calling the function to get the image to be shown
											$img_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,0,0,1);
											
											if(count($img_arr))
											{
												show_image(url_root_image($img_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name'],'newshelfBimg');
											}
											else
											{
												// calling the function to get the default image
												$no_img = get_noimage('prod',$pass_type); 
												if ($no_img)
												{
													show_image($no_img,$row_prod['product_name'],$row_prod['product_name'],'newshelfBimg');
												}	
											}
									?>
											</a>
									<?php
										}
									?> 
									
									<?php if($prod_compare_enabled)  { 
												dislplayCompareButton($row_prod['product_id']);
											 }
											 ?>
									</td>
									<td class="newshelfBtableinnertd" valign="middle" align="left">
									
									<?php
									    if($row_prod['product_saleicon_show']==1)
										{
											$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
											if($desc!='')
											{
										?>	
												<div class="shlfa_sale_sale"><?php echo $desc?></div>
										<?php
											}
										}
										if($row_prod['product_newicon_show']==1)
										{
											$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
											if($desc!='')
											{
										?>
												<div class="shlfa_sale_new"><?php echo $desc?></div>
										<?php
											}
										}
											
											if ($cat_det['product_showprice']==1)// Check whether description is to be displayed
											{
												$price_class_arr['ul_class'] 		= 'shelfpriceul';
												$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
												$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
												$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
												$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
											//	echo show_Price($row_prod,$price_class_arr,'shelfcenter_1'); price_categorydetails_1_reqbreak
												echo show_Price($row_prod,$price_class_arr,'cat_detail_1'); 
												show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
											}	
										if($cat_det['product_showbonuspoints']==1)
										{
											show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points
										}
												$frm_name = uniqid('catdet_');
										?>	
												<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
													<input type="hidden" name="fpurpose" value="" />
													<input type="hidden" name="fproduct_id" value="" />
													<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
													<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
													<div class="buy_main">
													<div class="shlf_buy_btn"><div class="shlf_buy_btnleft">
													<?php
													$class_arr 					= array();
													$class_arr['ADD_TO_CART']	= 'link_buy';
													$class_arr['PREORDER']		= 'link_buy';
													$class_arr['ENQUIRE']		= 'link_buy';
													$class_arr['BTN_CLS']       = 'add_divlink';
													show_addtocart_v5_ajax($frm_name,$row_prod,$class_arr);
													//show_addtocart($row_prod,$class_arr,$frm_name,false,'','','add_divlink',0)
													?></div>
													</div>      
													<div class="shlf_info_btn"><div class="shlf_info_btnleft">
													<?php show_moreinfo($row_prod,'info_buy')?>
													</div></div>   
													</div>    
												</form>
									
									</td>
									</tr>
																			
									</table></td>
									</tr>
									<? }
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
												$pageclass_arr['container'] = 'pagenavcontainer';
												$pageclass_arr['navvul']	= 'pagenavul';
												$pageclass_arr['current']	= 'pagenav_current';
												$query_string = "&amp;catdet_sortby=".$_REQUEST['catdet_sortby'].'&amp;catdet_sortorder='.$_REQUEST['catdet_sortorder'].'&amp;catdet_prodperpage='.$_REQUEST['catdet_prodperpage'];
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
										if($cur_col == 0)
										{
										  echo "<tr>";
										}
										$cur_col ++;

										?>
											<td class="newshelfAtabletd" onmouseover="this.className='newshelfAtabletd_hover'" onmouseout="this.className='newshelfAtabletd'" align="left" valign="top">												
													<ul class="shelfAul">
														<?php
															if($cat_det['product_showtitle']==1)// whether title is to be displayed
															{
														?>
																<li><h2 class="shelfAprodname"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h2></li>
															
														<?php
															}
															if ($cat_det['product_showprice']==1)// Check whether description is to be displayed
															{
														?>
																<li>
																	<?php
																		$price_class_arr['ul_class'] 		= 'shelfBul';
																		$price_class_arr['normal_class'] 	= 'shelfAnormalprice';
																		$price_class_arr['strike_class'] 	= 'shelfAstrikeprice';
																		$price_class_arr['yousave_class'] 	= 'shelfAyousaveprice';
																		$price_class_arr['discount_class'] 	= 'shelfAdiscountprice';
																		//echo show_Price($row_prod,$price_class_arr,'shelfcenter_3');
																		echo show_Price($row_prod,$price_class_arr,'cat_detail_3');
																		show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
																	?>	
																</li>
														<?php
															}
															if ($cat_det['product_showimage']==1)// Check whether description is to be displayed
															{
														?>		
																<li class="shelfimg">
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
																</li>
																
														<?php
															}
															if($cat_det['product_showrating']==1)
															{
														$module_name = 'mod_product_reviews';
															if(in_array($module_name,$inlineSiteComponents))
															{
																if($row_prod['product_averagerating']>=0)
																{
																?>
																	<li class="shlf_star">
																		<?php
																		display_rating($row_prod['product_averagerating']);
																		?>
																	</li>
																<?php
																}
															}
															}		
														if($row_prod['product_freedelivery']==1 || $row_prod['product_bulkdiscount_allowed']=='Y')
														{
															?>
															<li class="shlf_sale">
																<?php
																if($row_prod['product_bulkdiscount_allowed']=='Y')
																{	
																	?>
																	<img src="<?php url_site_image('bulk-discount.gif')?>" alt="Bulk Discount"/>
																	<?php
																}
																if($row_prod['product_freedelivery']==1){
																	?>
																	<img src="<?php url_site_image('free-img.gif')?>" alt="Free Delivery"/>
																	<?
																}
																?>
															</li>
															<?
														}
														if ($cat_det['product_showshortdescription']==1)// Check whether description is to be displayed
														{
														?>
															<li><h6 class="shelfAproddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6></li>
														<?php
														}
														?>	
													</ul>
														<?php 
											if($cat_det['product_showbonuspoints']==1)
											{
												show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points
											}
								        	$frm_name = uniqid('catdet_');
										?>
															<?php if($prod_compare_enabled)  {
													dislplayCompareButton($row_prod['product_id']);
											 }
											 	if($row_prod['product_saleicon_show']==1)
												{
													$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
													if($desc!='')
													{
														?>	
														<div class="shlf_sale_sale"><?php echo $desc?></div>
														<?php
													}
													}
													if($row_prod['product_newicon_show']==1)
													{
													$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
													if($desc!='')
													{
														?>
														<div class="shlf_sale_new"><?php echo $desc?></div>
														<?php
													}
												}	
											 ?>
											 
													<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
													<input type="hidden" name="fpurpose" value="" />
													<input type="hidden" name="fproduct_id" value="" />
													<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
													<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
														<div class="buy_main">
																<div class="shlf_buy_btn">
																<div class="shlf_buy_btnleft">
																<?php
																$class_arr 							= array();
																$class_arr['ADD_TO_CART']	= 'link_buy';
																$class_arr['PREORDER']		= 'link_buy';
																$class_arr['ENQUIRE']		= 'link_buy';
																$class_arr['BTN_CLS']       = 'add_divlink';
																show_addtocart_v5_ajax($frm_name,$row_prod,$class_arr);
																//show_addtocart($row_prod,$class_arr,$frm_name,false,'','','add_divlink',0)
																?>
																</div>
																</div>      
																<div class="shlf_info_btn"><div class="shlf_info_btnleft">
																<?php show_moreinfo($row_prod,'info_buy')?>
																</div>
																</div>   
															</div>
														</form>
															</td>
											<?php
														if($cur_col>=$max_col)
														{
														$cur_col =0;
														echo "</tr>";
														
														}

														// done to handle the case of breaking to new linel
												}
											// If in case total product is less than the max allowed per row then handle that situation
								                	if($cur_col<$max_col)
													{
													   if($cur_col!=0)
														{ 
															$cur_td=0;
															echo "<td colspan='".($max_col-$cur_col)."'>&nbsp;</td>";
															echo "</tr>";
														} 
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
											//show_paging($path,$_REQUEST[$pg_variable],$start_arr['counterstart'],$tot_cnt,$prodperpage,$pg_variable,'')
												$pageclass_arr['container'] = 'pagenavcontainer';
												$pageclass_arr['navvul']	= 'pagenavul';
												$pageclass_arr['current']	= 'pagenav_current';
												$query_string = "&amp;catdet_sortby=".$_REQUEST['catdet_sortby'].'&amp;catdet_sortorder='.$_REQUEST['catdet_sortorder'].'&amp;catdet_prodperpage='.$_REQUEST['catdet_prodperpage'];
												paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,$Captions_arr['CAT_DETAILS']['CATDET_PRODUCTS'],$pageclass_arr,0); 	
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
						<td width="63%" height="30" align="right" valign="middle" class="productpagenavtext"><?php echo $Captions_arr['CAT_DETAILS']['CATDET_SORTBY']?><select name="catdet_sortbybottom" id="catdet_sortbybottom">
                           <option value="custom" <?php echo ($prodsort_by=='custom')?'selected="selected"':''?>><?php echo $Captions_arr['CAT_DETAILS']['CATDET_DEFAULT']?></option>
						    <option value="product_name" <?php echo ($prodsort_by=='product_name')?'selected="selected"':''?>><?php echo $Captions_arr['CAT_DETAILS']['CATDET_PRODNAME']?></option>
                            <option value="price" <?php echo ($prodsort_by=='price')?'selected="selected"':''?>><?php echo $Captions_arr['CAT_DETAILS']['CATDET_PRICE']?></option>
                    	    <option value="product_id" <?php echo ($prodsort_by=='product_id')?'selected="selected"':''?>><?php echo $Captions_arr['CAT_DETAILS']['SHOPDET_DATEADDED']?></option>
							</select>
                          <select name="catdet_sortorderbottom" id="catdet_sortorderbottom">
                            <option value="ASC" <?php echo ($prodsort_order=='ASC')?'selected="selected"':''?>><?php echo $Captions_arr['CAT_DETAILS']['CATDET_LOW2HIGH']?></option>
                            <option value="DESC" <?php echo ($prodsort_order=='DESC')?'selected="selected"':''?>><?php echo $Captions_arr['CAT_DETAILS']['CATDET_HIGH2LOW']?></option>
                          </select></td>
						<td width="37%" align="right" valign="middle" class="productpagenavtext"><?php echo $Captions_arr['CAT_DETAILS']['CATDET_ITEMSPERPAGE']?>
						<select name="catdet_prodperpagebottom" id="catdet_prodperpagebottom">
						<?php
							for ($ii=$Settings_arr["productlist_interval"];$ii<=$Settings_arr["productlist_maxval"];$ii+=$Settings_arr["productlist_interval"])
							{
						?>
								<option value="<?php echo $ii?>" <?php echo ($catdet_prodperpage==$ii)?'selected="selected"':''?>><?php echo $ii?></option>
						<?php	
							}
						?>
						</select>
						<input type="button" name="submit_Page" value="<?php echo $Captions_arr['CAT_DETAILS']['CATDET_GO']?>" class="buttonred" onclick="handle_categorydetailsdropdownval_sel('<?php echo url_category($_REQUEST['category_id'],$catname,4,$_REQUEST['catgroup_id'],0)?>','catdet_sortbybottom','catdet_sortorderbottom','catdet_prodperpagebottom')" />
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
	          	<td colspan="3" class="shelfBheader"><?php echo $Captions_arr['CAT_DETAILS']['CATDET_PRODUCTS']?></td>
          	</tr>
           	<tr>
        	 	<td align="left" valign="middle" class="shelfBtabletd"><h2 class="shelfBprodname" ><?php echo $Captions_arr['CAT_DETAILS']['NO_PROD_MSG']?></h2>
			 	</td>
			</tr>	 
			</table>
		<?php	
		}
		function Show_HomeCatgeory($title,$ret_category) 
		{ 
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$Captions_arr,$inlineSiteComponents;
			if(in_array('mod_catimage',$inlineSiteComponents))
			{
				$img_support = true;
			}
			else
				$img_support = false;
		?>
			
			
			<div class="cate_main_outer" >
			<div class="cate_main_con" > <form method="post" action="<?php url_link('manage_products.html')?>" name='frm_category' id="frm_category" class="frm_cls">
			<input type="hidden" name="fpurpose" value="" />
			<input type="hidden" name="fproduct_id" value="" />
			<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
				<?php	
					$max_col = 3;
					$cur_col = 0;
					while ($row_category = $db->fetch_array($ret_category))
					{
				
				?>
						<div class="cate_con">
						<div class="cate_top"><a href="<?php url_category($row_category['category_id'],$row_category['category_name'],-1)?>" class="cate_mid_lnk" title="<?php echo stripslashes($row_category['category_name'])?>"><?php echo stripslashes($row_category['category_name'])?></a></div>
						<div class="cate_mid">
						
						 <div class="cate_mid_img">
						 <?php 
							if($row_category['category_showimagetype']!='None' && ($img_support)) // ** Show sub category image only if catimage module is there for the site subcategoreynamelink
							{
						  ?>
							<a href="<?php url_category($row_category['category_id'],$row_category['category_name'],-1)?>" class="subcategoreyimage_home" title="<?php echo stripslashes($row_category['category_name'])?>">
						 <?php
								 switch($row_category['category_showimagetype'])
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
									};
								if ($row_category['category_showimageofproduct']==0) // Case to check for images directly assigned to category
								{
									
									
									// Calling the function to get the image to be shown
									
									$img_arr = get_imagelist('prodcat',$row_category['category_id'],$pass_type,0,0,1);
									if(count($img_arr))
									{
										show_image(url_root_image($img_arr[0][$pass_type],1),$row_category['category_name'],$row_category['category_name']);
										$show_noimage = false;
									}
									else
										$show_noimage = true;
								}
								else // Case of check for the first available image of any of the products under this category
								{
									// Calling the function to get the id of products under current category with image assigned to it
									$cur_prodid = find_AnyProductWithImageUnderCategory($row_category['category_id']);
									if ($cur_prodid)// case if any product with image assigned to it under current category exists
									{
																		
										// Calling the function to get the image to be shown
										$img_arr = get_imagelist('prod',$cur_prodid,$pass_type,0,0,1);
										
										if(count($img_arr))
										{
											show_image(url_root_image($img_arr[0][$pass_type],1),$row_category['category_name'],$row_category['category_name']);
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
										show_image($no_img,$row_category['category_name'],$row_category['category_name']);
									}	
								}
							?>
								</a>
						  <?php
						  	}
						  ?>
						 </div>
						 <div class="cate_mid_outr">
						<div class="cate_mid_txt">
						<?php 
						$show_desc_arr = explode('~',stripslashes($row_category['category_shortdescription']));
						echo stripslashes($show_desc_arr[0])
						?></div>
						<div class="cate_mid_info"><a href="<?php url_category($row_category['category_id'],$row_category['category_name'],-1)?>"><img src="<?php url_site_image('ca-info.gif')?>" border="0" /></a></div>
						</div>
						</div>
						<div class="cate_bottom"></div>
						</div>
				<?php
					}
				?>	
				</form></div>
     			</div>
	<?PHP	
		}
	};	
?>
