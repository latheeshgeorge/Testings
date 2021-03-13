<?php
function show_model_select($mid)
{ 
	global $db,$ecom_siteid;
	$row_massign = array();
	
	$ret_arrs_n		= get_lsrefine_holder();
	$d_make_id 		= $ret_arrs_n['make_id'];

	$d_model_id 	= $ret_arrs_n['model_id'];
	//$sel_model_id = $_SESSION['searchmodel_modelid'];
	$sql_mname = "SELECT model_id,model_caption FROM ls_refine_model a 
    LEFT JOIN ls_refine_make_model_map b
    ON 
    a.model_id = b.ls_refine_model_model_id 
    WHERE 
		a.sites_site_id=".$ecom_siteid." 
	AND  
		a.model_hide=0  
    AND 
		ls_refine_make_make_id='".$mid."' 
	ORDER BY 
		b.map_order ASC";
	$ret_mname = $db->query($sql_mname);
	?>
	<option value="0">--Select Model--</option>  
	<?php
	
	if($db->num_rows($ret_mname)>0)
	{    
	?>
		

		<?php
		while($row_mname = $db->fetch_array($ret_mname))
		{
				
			?>
				<option value="<?php echo $row_mname['model_id'] ?>" <?php echo ($d_model_id==$row_mname['model_id'] and $d_make_id==$mid)?'selected="selected"':''?> ><?php echo $row_mname['model_caption'] ?></option>  

			<?php				
		}
		?>
	<?php
	}
	
}
function show_serialno_select($mid)
{ 
	global $db,$ecom_siteid;
	$row_massign = array();
	
	$ret_arrs_n		= get_lsrefine_holder();
	$d_make_id 		= $ret_arrs_n['make_id'];
	$d_model_id 	= $ret_arrs_n['model_id'];
	$d_srno 		= $ret_arrs_n['serialno_id'];
	$d_category_id  = $ret_arrs_n['cat_id'];
	
	//$sel_serial_no = $_SESSION['searchmodel_serialno'];
    $sql_mname = "SELECT DISTINCT modelserialno_id,modelserialno_caption FROM ls_refine_modelserialno a 
    LEFT JOIN ls_refine_model_serialno_map b
    ON 
    a.modelserialno_id = b.ls_refine_modelserialno_modelserialno_id 
    WHERE 
		a.sites_site_id=".$ecom_siteid." 
	AND  
		a.modelserialno_hide=0  
    AND 
		ls_refine_model_model_id ='".$mid."' 
	ORDER BY modelserialno_caption";
	$ret_mname = $db->query($sql_mname);
	?>
	<option value="0">--Serial Prefix--</option>  
	<?php
	
	if($db->num_rows($ret_mname)>0 && $mid>0)
	{    
	?>
		

		<?php
		while($row_mname = $db->fetch_array($ret_mname))
		{
				
			?>
				<option value="<?php echo $row_mname['modelserialno_id'] ?>" <?php echo (($d_srno==$row_mname['modelserialno_id']) and ($d_model_id==$mid))?'selected="selected"':''?>><?php echo $row_mname['modelserialno_caption'] ?></option>  

			<?php				
		}
		?>
	<?php
	}
	
}
function show_model_image($mid)
{ 
	global $db,$ecom_siteid,$image_path;
	
	//image_thumbpath
	 $sql_image  = "SELECT image_thumbpath,image_bigpath  FROM images a LEFT JOIN ls_refine_model b ON b.images_image_id=a.image_id WHERE a.sites_site_id=$ecom_siteid AND b.model_id='".$mid."' LIMIT 1";
	$ret_image = $db->query($sql_image);
	$row_image = $db->fetch_array($ret_image);
	 //$thumb_image  = $row_image['image_thumbpath'];
	 $thumb_image  = $row_image['image_bigpath'];
	if($thumb_image!='')
	{
	?>
	<img src="<?php echo url_root_image($thumb_image)?>">
	<?php
	}
	
}
function show_srno_image($mid)
{ 
	global $db,$ecom_siteid,$image_path;
	$ret_arrs_n		= get_lsrefine_holder();
	$d_make_id 		= $ret_arrs_n['make_id'];
	$d_model_id 	= $ret_arrs_n['model_id'];
	$d_srno 		= $ret_arrs_n['serialno_id'];
	$d_category_id  = $ret_arrs_n['cat_id'];
	if($mid==0)
	{
	$mid = $d_srno;
	}
	//image_thumbpath
	 $sql_imageid = "SELECT images_image_id FROM images_serialno WHERE  serialno_serialno_id 	='".$mid."'";
	 $ret_imageid = $db->query($sql_imageid); 
	 if($db->num_rows($ret_imageid)>0)
	 {    
		 ?>
		 <ul>
		 <?php
		     while($row_imageid=$db->fetch_array($ret_imageid))		    
				{ 
					$sql_image  = "SELECT image_thumbpath,image_bigpath  FROM images WHERE sites_site_id=$ecom_siteid AND image_id='".$row_imageid['images_image_id']."' LIMIT 1";
					$ret_image = $db->query($sql_image);
					$row_image = $db->fetch_array($ret_image);
					//$thumb_image  = $row_image['image_thumbpath'];
					$thumb_image  = $row_image['image_bigpath'];
					if($thumb_image!='')
					{
					?>
					
					<li class="serialimg_li"><img src="<?php echo url_root_image($thumb_image)?>"></li>
					
					<?php
					}
				}
				?>
				</ul>
				<?php
			
	}
}
function show_category_model($category_id=0,$mkid=0,$mdid=0,$serialno=0,$page_id=0,$per_page=0) 
{
	if($_SERVER['REMOTE_ADDR']=='182.72.159.170')
	{
		//print_r($_SESSION);
		//echo 'x'.$_SESSION['searchmodel_categoryid'];
	}
global $db,$ecom_siteid;
$limit = "LIMIT 0,10";
if($category_id>0)
{
	global $model_subcatexists;
	$model_subcatexists = 1; 	
	$sql_subcat 	= "SELECT category_id,category_name,category_showimageofproduct,default_catgroup_id,subcategory_showimagetype,category_shortdescription,category_paid_description    
								FROM
									product_categories 
								WHERE 
									parent_id = '".$category_id."' 
									AND sites_site_id = $ecom_siteid 
									AND category_hide=0
								ORDER BY
									category_order		
									";
					$ret_subcat = $db->query($sql_subcat);
	?>

				<?php
				if($db->num_rows($ret_subcat)>0)
				{
					?>
						<div class="title_name_head"> <span class="title_name"></span></div>

					<?php
					while($row_category=$db->fetch_array($ret_subcat))
					{
						?>
						<div class="subcat_3row_pdt_outr subcat_3row_pdt_outrcat" catid="<?php echo $row_category['category_id']?>" id="<?php echo $row_category['category_id']?>" catname="<?php echo$row_category['category_name'];?>">
					<div class="subcat_3row_pdt_name">
						
						<?php 
						echo '<a href="javascript:void(0)" class="model_linkclass_cat"  title="'.stripslash_normal($row_category['category_name']).'">'.stripslash_normal($row_category['category_name']).'</a>';
						?>
						</div>
					<div class="subcat_3row_pdt_btm">
					<div class="subcat_3row_image">
						<?php
						
								 $HTML_image = '<a href="javascript:void(0)"  class="model_imageclass" title="'.stripslash_normal($row_category['category_name']).'">';
											$pass_type = 'image_thumbpath';
									if ($row_category['category_showimageofproduct']==0) // Case to check for images directly assigned to category
									{									
										// Calling the function to get the image to be shown
										$img_arr = get_imagelist('prodcat',$row_category['category_id'],$pass_type,0,0,1);
										if(count($img_arr))
										{
											$HTML_image .= show_image(url_root_image($img_arr[0][$pass_type],1),$row_category['category_name'],$row_category['category_name'],'','',1);
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
												$HTML_image .= show_image(url_root_image($img_arr[0][$pass_type],1),$row_category['category_name'],$row_cat['category_name'],'','',1);
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
											$HTML_image .= show_image($no_img,$row_category['category_name'],$row_category['category_name'],'','',1);
										}	
									}
								$HTML_image .= '</a>';
							echo $HTML_image;
						 ?>
						</div>
					<div class="subcat_3row_des"></div>
					</div>
					</div>
						<?php
				
						
					}
				}
	
}
else
{
	$sql_category ='';
	if($mdid==0)
	{
		$mdid = -1;
	}	
	if($serialno>0)
				{
		  $sql_category .= "SELECT DISTINCT 
		a.category_id,a.category_name,a.category_showimageofproduct 
					FROM product_categories a 
					LEFT JOIN product_categories_ls_refine b
					ON a.category_id=b.product_categories_category_id
					WHERE b.ls_refine_make_make_id='".$mkid."' AND b.ls_refine_model_model_id='".$mdid."' and a.sites_site_id=$ecom_siteid";
					if($serialno>0)
					{
						$sql_category .= " AND b.ls_refine_modelserialno_modelserialno_id=$serialno";
					}
					//$sql_category .= " ".$limit." ";				
					$ret_category  = $db->query($sql_category);
				}
					
				?>
				<div class="title_name_head"> <span class="title_name">Categories</span></div>

				<?php
				if($db->num_rows($ret_category)>0)
				{
					while($row_category=$db->fetch_array($ret_category))
					{
						?>
						<div class="subcat_3row_pdt_outr subcat_3row_pdt_outrcat" catid="<?php echo $row_category['category_id']?>" id="<?php echo $row_category['category_id']?>" catname="<?php echo$row_category['category_name'];?>">
					<div class="subcat_3row_pdt_name">
						
						<?php 
						echo '<a href="javascript:void(0)" class="model_linkclass_cat"  title="'.stripslash_normal($row_category['category_name']).'">'.stripslash_normal($row_category['category_name']).'</a>';
						?>
						</div>
					<div class="subcat_3row_pdt_btm">
					<div class="subcat_3row_image">
						<?php
						
								 $HTML_image = '<a href="javascript:void(0)"  class="model_imageclass" title="'.stripslash_normal($row_category['category_name']).'">';
											$pass_type = 'image_thumbpath';
									if ($row_category['category_showimageofproduct']==0) // Case to check for images directly assigned to category
									{									
										// Calling the function to get the image to be shown
										$img_arr = get_imagelist('prodcat',$row_category['category_id'],$pass_type,0,0,1);
										if(count($img_arr))
										{
											$HTML_image .= show_image(url_root_image($img_arr[0][$pass_type],1),$row_category['category_name'],$row_category['category_name'],'','',1);
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
												$HTML_image .= show_image(url_root_image($img_arr[0][$pass_type],1),$row_category['category_name'],$row_cat['category_name'],'','',1);
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
											$HTML_image .= show_image($no_img,$row_category['category_name'],$row_category['category_name'],'','',1);
										}	
									}
								$HTML_image .= '</a>';
							echo $HTML_image;
						 ?>
						</div>
					<div class="subcat_3row_des"></div>
					</div>
					</div>
						<?php
				
						
					}
				}
				else
				{
				    ?>
				    <div class="subcat_3row_redalert"> <span class='nopr_model'>No categories Found!!!</span>
						</div>
				    <?php
				}
			}
?>
					
						
<?php
}
function show_category_productmodel($category_id,$category_name,$mkid,$mdid,$srno=0,$pg_id=1,$prodperpage=5) 
{
   global $db,$ecom_siteid;
            $sql_prods 		= "SELECT count(a.products_product_id) as cnt 
										FROM 
											product_category_map a,products b
										WHERE 
											a.product_categories_category_id = '".$category_id."' 
											AND a.products_product_id=b.product_id 
											AND b.product_hide='N'";
			$ret_prods		= $db->query($sql_prods);
			$row_prods      = $db->fetch_array($ret_prods);
			$num_prods      = $row_prods['cnt'];
			$start_var 					= prepare_paging($pg_id,$prodperpage,$num_prods);
			$prodsort_by	= 'a.product_name';
			$prodsort_order = 'ASC';
			$limit = $prodperpage;  
			if (isset($pg_id)) { $page  = $pg_id; } else { $page=1; };  
			$start_from = ($page-1) * $limit;  
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
									b.product_categories_category_id = '".$category_id."' 
									AND a.product_id = b.products_product_id 
									ANd a.sites_site_id =$ecom_siteid 
									AND a.product_hide = 'N' 
								ORDER BY 
									$prodsort_by $prodsort_order 
								LIMIT 
									".$start_from.", ".$limit;
							
					$ret_prod = $db->query($sql_prod);
						$pass_type = get_default_imagetype('prodcat');
						
                    ?>
                    
                    <div class="category_namesp"><?php echo $category_name;?></div>
					<div class="category_backp" id="category_backpid">Back to Categories</div>
                    	<input type="hidden" name="select_make_hidden" id="select_make_hidden" value="<?php echo $mkid;?>" />
						<input type="hidden" name="select_model_hidden"  id="select_model_hidden" value="<?php echo $mdid;?>" />
                        <input type="hidden" name="select_srno_hidden"  id="select_srno_hidden" value="<?php echo $srno;?>" />

                        <input type="hidden" name="select_category_hidden" id="select_category_hidden" value="<?php echo $category_id;?>" />
                        <input type="hidden" name="select_categoryn_hidden" id="select_categoryn_hidden" value="<?php echo $category_name;?>" />
 
                    <?php
                    	$sql_cat 	= "SELECT category_paid_description    
								FROM
									product_categories 
								WHERE 
									category_id = '".$category_id."' 
									AND sites_site_id = $ecom_siteid 
									AND category_hide=0
								ORDER BY
									category_order		
									";
					       $ret_cat = $db->query($sql_cat);
					       $row_cat = $db->fetch_array($ret_cat);
					    ?>
					    <div>
					    <?php
					    echo $row_cat['category_paid_description'];
					    ?>
					    </div>
					    <?php   
					
                      $model_subcatexists = 0;
                      global $model_subcatexists;
                      show_category_model($category_id);

                    $pagLink_tot ='';
                    if($num_prods>0)
                    {
                    $pagLink_tot = "<span class='tot_prod_num'>".$num_prods." Prodcuts found</span>";
				    }
                    $pagLink = "<div class='pagination_nav' ><ul class='pagination'>";  
							$total_pages = $start_var['pages'];
							if($total_pages>1)
							{
							for ($i=1; $i<=$total_pages; $i++) {
								if($pg_id==$i) 
								{
								 $page_class = 'redlinkA';	
								}
								else
								{
								$page_class = 'blacklinkA';
								} 
							$pagLink .= "<li class='".$page_class."' id=".$i."><a href='javascript:void(0)'  name='paging_name' >".$i."</a></li>";  
							};  
							$pagLink = $pagLink . "</ul></div>";  
							}
							echo $pagLink = $pagLink_tot.$pagLink;
							$show_prod =1 ;
							if($model_subcatexists==1 && $db->num_rows($ret_prod)==0)
							{
							 $show_prod = 0; 
							}
							if($show_prod==1)
							{
							?>
							<div class="title_name_head"> <span class="title_name">Products</span></div>

							<div class="subcat_3row_pdt_outrdiv">

							<?php
							if($db->num_rows($ret_prod)>0)
							{
								while($row_prod = $db->fetch_array($ret_prod))
								{
								$prodcur_arr[] = $row_prod;
								$HTML_title = $HTML_image = $HTML_desc = '';
								$HTML_sale = $HTML_new = $HTML_compare = $HTML_rating = '';
								$HTML_price = $HTML_bulk= $HTML_bonus = $HTML_compare = $HTML_freedel= $HTML_bonus_bar = '';

								$HTML_title = '<a href="#" onclick="gotoproduct(\''.url_product($row_prod['product_id'],$row_prod['product_name'],1) .'\','.$row_prod['product_id'].')" title="'.stripslash_normal($row_prod['product_name']).'">'.stripslash_normal($row_prod['product_name']).'</a>';


								$HTML_image ='<a href="#" onclick="gotoproduct(\''.url_product($row_prod['product_id'],$row_prod['product_name'],1) .'\','.$row_prod['product_id'].')" title="'.stripslash_normal($row_prod['product_name']).'">';
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

								?>
								<div class="subcat_3row_pdt_outr">

								<div class="subcat_3row_pdt_name"><?php echo $HTML_title;?></div>
								<div class="subcat_3row_pdt_btmprod">
								<div class="product_items"><?php echo $HTML_image;?></div>
								<div class="price_lists"><?php 
								$price_class_arr['class_type']          = 'div';
								$price_class_arr['normal_class']        = 'normal_model';
								$price_class_arr['strike_class']        = 'strike_model';
								$price_class_arr['yousave_class']       = 'save_model';
								$price_class_arr['discount_class']      = 'discount_model';
								echo  $HTML_price = show_Price($row_prod,$price_class_arr,'shelfcenter_3');
								$frm_name = uniqid('shelf_');?></div>

								<div class="normal_shlfB_pdt_infoA">

								<div class="normal_shlfB_pdt_buy_outr">
								<div class="normal_shlfB_pdt_buy">
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
								$class_arr['QTY_DIV']           = 'normal_shlfB_pdt_input';
								$class_arr['QTY']               = ' ';
								// section added for the new ajax function to add cart
								$class_arr['BTN_CLS']           = 'normal_shlfB_pdt_buy_btn';

								//show_addtocart($row_prod,$class_arr,$frm_name,false,'','','normal_shlfB_pdt_buy_btn');
								show_addtocart_v5_ajax($frm_name,$row_prod,$class_arr);
								?>
								</form>
								</div>
								</div>
								</div>
								<div class="normal_shlfB_pdt_info">
								<a href="#" onclick="gotoproduct('<?php echo url_product($row_prod['product_id'],$row_prod['product_name']) ?>',<?php echo $row_prod['product_id'];?>)">More Info</a>
								<?php //show_moreinfo($row_prod,'')?>

								</div>

								</div>
								</div>



								<?php
								}
							}
							else
							{
							echo "<span class='nopr_model'>No Products Found !!!</span>";
							}
							?>
							</div>
							<?php
							}
}
?>
