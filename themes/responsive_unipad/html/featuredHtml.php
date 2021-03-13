<?php
/*############################################################################
	# Script Name 	: featuredHtml.php
	# Description 	: Page which holds the display logic for featured product
	# Coded by 		: Sny
	# Created on	: 28-Dec-2007
	# Modified by	: Sny
	# Modified On	: 22-Jan-2008
	##########################################################################*/
	class featured_Html
	{
		// Defining function to show the featured property
		function Show_Featured($title,$ret_featured)
		{
                    global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$Captions_arr,$ecom_allpricewithtax;
                    global $ecom_selfhttp;
                    $row_featured = $db->fetch_array($ret_featured);
                    $sql_prod_offer = "SELECT product_newicon_show,product_saleicon_show,product_actualstock FROM products WHERE product_id=".$row_featured['product_id']." AND sites_site_id=$ecom_siteid LIMIT 1";
                    $ret_prod_offer = $db->query($sql_prod_offer);
                    $row_prod_offer = $db->fetch_array($ret_prod_offer);
                    //print_r($row_featured);
                    $row_prod= $row_featured;
                    // Component Title
                    $HTML_title = $HTML_comptitle = $HTML_image = $HTML_desc = $HTML_price = '';                   
		?>          
                    			<link rel="stylesheet" href="<?php echo $ecom_selfhttp.$ecom_hostname?>/images/<?php echo $ecom_hostname?>/css/jquery.mThumbnailScroller.css">

                    
                    
                    <div class="featured">
					<?php
					if($title!='')
					{
					?>	
					<div class="ft_div"><?php echo $title ?></div>
					<?php
					}
					?>
					<div class="col-md-7">
					
					
							<div class="grid_1_of_4 images_1_of_4">
									<div class="product-grid">
										
										<?php 
										$rate = $row_prod['product_averagerating'];
										$pass_type = 'image_bigpath';
										//echo $rating = $this->display_rating_responsive($rate,1,$row_prod['product_id']);
										?>
										
											<div class="product-pic">
												    <div class="new_featured_stk">
													<?php
													if($row_prod_offer['product_actualstock']==0)
													{
													?>
													<div class="nowlet_cls_inner1"><img src="<?php echo url_site_image('nowLet.svg',1) ?>" alt="Now Let"></div>
													<?php		
													}
													if($row_prod_offer['product_actualstock']>0)
														$availability_msg = '<span class="red_available1">'.$Captions_arr['COMMON']['PRODDET_AVAIL_1_YEAR'].'</span>';
													else
														$availability_msg = '<span class="red_available1">'.$Captions_arr['COMMON']['PRODDET_AVAIL_2_YEAR'].'</span>';
													echo $availability_msg;
													?>	
													</div>	
													<a href="javascript:handletap('<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>','shelf<?php echo $row_prod['product_id']?>')" title="<?php echo stripslashes($row_prod['product_name'])?>" <?php //echo getmicrodata_producturl()?> >
														<?php
														// Calling the function to get the image to be shown
														$img_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,0,0,1);
														if(count($img_arr))
														{
															$moreimgarr= array();
															$moreimgstr = '';
															$prodmoreimg_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,0,0);
															if(count($prodmoreimg_arr))
															{
																$exclude_prodid = $prodmoreimg_arr[0]['image_id']; 
																for($mi=0;$mi<count($prodmoreimg_arr);$mi++)
																{
																	$moreimgarr[]= url_root_image($prodmoreimg_arr[$mi][$pass_type],1);
																}
																$moreimgstr = implode(",",$moreimgarr);
															}
															//$imgpass_arr = array('id'=>$img_arr[0]['image_id'],'typ'=>'big');
															?>
																		<table width="100%" border="0" cellspacing="0" cellpadding="0">
																		  <tr>
																			<td align="center"><img style="border:1px solid #e8e8e6;" id="zoom_09" src="<?php url_root_image($img_arr[0][$pass_type])?>" data-zom="<?php url_root_image($img_arr[0]['image_extralargepath'])?>" width="411"  alt="<?php echo $row_prod['product_name']?>" data-curidx="0"></td>
																		  </tr>
																		</table>
																		<div class="feat_price_big">
																		<?php			
					$price_class_arr['ul_class'] 		= 'price-avl';
					$price_class_arr['normal_class'] 	= '';
					$price_class_arr['strike_class'] 	= 'price';
					$price_class_arr['yousave_class'] 	= '';
					$price_class_arr['discount_class'] 	= '';
					//	echo show_Price($row_prod,$price_class_arr,'shelfcenter_1'); price_categorydetails_1_reqbreak
					$price_array =  show_Price($row_prod,$price_class_arr,'cat_detail_1',false,3);
					//print_r($price_array);
					?>
					 <ul class="price-avl">
					<li class="price"><span>
					<?php
					$disc =  $price_array['discounted_price'];
					$base =  $price_array['base_price'];
					if($disc>0)
					echo $disc;
					else
					echo $base;
					//show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
					?>
					</span>
					</li>
					</ul>	
					</div>		
															<?php
															//show_image(url_root_image($img_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name'],'listscrollimg" data-imageid="shelf'.$row_prod['product_id'].'" data-tapped="0" data-immore="'.$moreimgstr,'',0,$imgpass_arr);
															
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
											<div class="product-picmore">
												<?php $this->show_more_images($row_prod,$exclude_tabid,$exclude_prodid);?>
												
											</div>	

					
											
									</div>
							</div>						
						
					
					
					
					</div>
					<div class="col-md-5">
					
					<p class="featured_details"><a href="<?php url_product($row_featured['product_id'],$row_featured['product_name'],-1) ?>" title="<?php stripslash_normal($row_featured['product_name']) ?>" class="linkbold_y"><?php echo stripslash_normal($row_featured['product_name']) ?></a></p>	
	
					<p class="featured_desc">
					<?php
					if ($row_featured['featured_showshortdescription']==1)
					{
						$desc = ($row_featured['featured_desc'])?$row_featured['featured_desc']:$row_featured['product_shortdesc'];
						if ($desc)
						{
							echo $HTML_desc = '<div class="featured_des">'.stripslashes($desc).'</div>';
						}
					}
					$link 		= url_product($row_prod['product_id'],$row_prod['product_name'],1);
					?>
					
					</p>
					<div class="featured_stk_left">
					
					<p class="featured_labels">
					<?php
					 
					 $mod['source'] = "product";
					 $this->show_ProductLabels($row_prod['product_id']); ?>
					</p>		

					<span clas="featured_arrange">					
					<?php $frm_name = uniqid('featureddet_'); ?>
													
					<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
					<input type="hidden" name="fpurpose" value="" />
					<input type="hidden" name="qty" value="1" />
					<input type="hidden" name="fproduct_id" value="" />
					<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
					<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
					<?php
					$class_arr['ADD_TO_CART']       = '';
					$class_arr['PREORDER']          = '';
					$class_arr['ENQUIRE']           = '';
					$class_arr['QTY_DIV']           = '';
					$class_arr['QTY']               = ' ';
					$class_td['QTY']				= '';
					$class_td['TXT']				= '';
					$class_td['BTN']				= '';
					$class_arr['BTN_CLS']     = 'btn btn-add-to-cart btn-lg sharp';
					echo show_addtocart_responsive($frm_name,$row_prod,$class_arr,0,'shelf',false,true);?>
					</form>
					</span>

					<p class="featured_moreinfo"><div class="moreinfolist"><a href="<?php echo $link?>" title="<?php echo $row_prod['product_name']?>" class="moreinfolist_a">More Info</a></div></p>

		
					</div>
					</div>
					
					</div>
					
                    
                   
		<?php	
		}
		function show_more_images($row_prod,$exclude_tabid,$exclude_prodid,$return_count = false)
{
	global $db,$ecom_hostname,$ecom_themename,$ecom_hostname;
	global $ecom_selfhttp;
	$show_normalimage = false;
	$prodimg_arr		= array();
	$pass_type 	= 'image_thumbpath';
	if ($_REQUEST['prod_curtab'] and $row_prod['product_variablecombocommon_image_allowed']=='N')
	{
		if ($exclude_tabid)
			$prodimg_arr = get_imagelist('prodtab',$_REQUEST['prod_curtab'],$pass_type.',image_thumbpath',0,0);	
		if (count($prodimg_arr)==0) // case if no more tab images exists
		{
			$show_normalimage = true;
		}
	}
	else // case of coming with out tab id, so show the normal image list if any
	{
		$show_normalimage = true;
	}	
	if ($show_normalimage==true) // the following is to be done only coming for normal image display
	{ 
		if($row_prod['product_variablecombocommon_image_allowed']=='Y')
		{
			if ($exclude_prodid)
				$prodimg_arr = get_imagelist_combination($row_prod['default_comb_id'],$pass_type,0,0);
		}		
		else
		{
			if ($exclude_prodid)
				$prodimg_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,0,0);
		}
	} 
	$show_pic_tab = false;
	if(count($prodimg_arr)>0)// Case if more than one image assigned to current product
	{
		if($return_count==true)
		{
				$show_pic_tab = true;
				return $show_pic_tab;		   
		}
		else
		{
			$tabstr		= ($_REQUEST['prod_curtab'])?'&amp;prod_curtab='.$_REQUEST['prod_curtab']:'';
			?>
			<link rel="stylesheet" href="<?php echo $ecom_selfhttp.$ecom_hostname?>/images/<?php echo $ecom_hostname?>/css/jquery.mThumbnailScroller.css">
			<link rel="stylesheet" href="<?php echo $ecom_selfhttp.$ecom_hostname?>/images/<?php echo $ecom_hostname?>/css/zoommodal.css">
			

			
			
				<div id="gallery_09" class="more_images_wrapper" >			
					
				<div id="content-3" class="content light">
					<ul>
					<?php
					$cntg = 0; 
					$ind = 0;
					foreach ($prodimg_arr as $k=>$v)
					{ 
						$cntg++;
						$title = $row_prod['product_name'];//$v['image_title'];
					?>
					<li>
						
						<?php
					 show_image(url_root_image($v['image_thumbpath'],1),$title,$title,'preview" data-curidx="'.$ind.'" data-big="'.url_root_image($v['image_bigpath'],1).'" data-zom="'.url_root_image($v['image_extralargepath'],1));
					 $ind++;
					?>
					</li>

					<?php
					}
					?>
					</ul>
					</div>
					<div id="content-4" class="content light">
					<?php 
					/*
					<ul>
					<?php
					$cntg = 0; 
					$ind = 0;
					foreach ($prodimg_arr as $k=>$v)
					{ 
						$cntg++;
						$title = $row_prod['product_name'];//$v['image_title'];
					?>
					<li>
						
						<?php
					 show_image(url_root_image($v['image_thumbpath'],1),$title,$title,'preview" data-curidx="'.$ind.'" data-big="'.url_root_image($v['image_bigpath'],1).'" data-zom="'.url_root_image($v['image_extralargepath'],1));
					 $ind++;
					?>
					</li>

					<?php
					}
					?>
					</ul>
					*/
					?> 
					<div class="feat_price_small">
																		<?php			
					$price_class_arr['ul_class'] 		= 'price-avl';
					$price_class_arr['normal_class'] 	= '';
					$price_class_arr['strike_class'] 	= 'price';
					$price_class_arr['yousave_class'] 	= '';
					$price_class_arr['discount_class'] 	= '';
					//	echo show_Price($row_prod,$price_class_arr,'shelfcenter_1'); price_categorydetails_1_reqbreak
					$price_array =  show_Price($row_prod,$price_class_arr,'cat_detail_1',false,3);
					//print_r($price_array);
					?>
					 <ul class="price-avl">
					<li class="price"><span>
					<?php
					$disc =  $price_array['discounted_price'];
					$base =  $price_array['base_price'];
					if($disc>0)
					echo $disc;
					else
					echo $base;
					//show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
					?>
					</span>
					</li>
					</ul>	
					</div>	
					</div>
				</div>
			<?php
		}
		}
			?>	
					<script src="<?php echo $ecom_selfhttp.$ecom_hostname?>/images/<?php echo $ecom_hostname?>/scripts/jquery.mThumbnailScroller.js"></script>

				<script type="text/javascript">
				$( document ).ready(function() {
					$("#content-3").mThumbnailScroller({
						axis:"y",
						type:"click-thumb",
						theme:"buttons-out"
					});				
					$("#content-4").mThumbnailScroller({
						axis:"x",
						type:"click-thumb",
						theme:"buttons-out"
					});	
												
					$('#content-3 li img').click(function(){ 
						//$('#zoom_09').animate({opacity:.5});
						$('#zoom_09').attr('data-zom',$(this).attr('data-zom'));
						$('#zoom_09').attr('data-curidx',$(this).attr('data-curidx'));
						$('#zoom_09').attr('src',$(this).attr('data-big'));
					
					});
					
					$('#content-4 li img').click(function(){ 
						$('#zoom_09').attr('data-zom',$(this).attr('data-zom'));
						$('#zoom_09').attr('data-curidx',$(this).attr('data-curidx'));
						$('#zoom_09').attr('src',$(this).attr('data-big'));
					});	
					
					
				});
						</script>
						<?php
			}
			/* Function to show the lables set for the product */
function show_ProductLabels($prod_id)
{
	global $db,$ecom_siteid,$Captions_arr,$ecom_hostname;
	global $ecom_selfhttp;

	$display_ok	=	false;

	$ret_val	=	'';

	// Check whether labels exists for current product

	$cats_arr	=	$grp_arr	=	array();

	// Get the categories that area linked with current product

	$sql_cats	=	"SELECT product_categories_category_id FROM product_category_map WHERE products_product_id = $prod_id";	

	//echo "<br>".$sql_cats;

	$ret_cats	=	$db->query($sql_cats);

	if($db->num_rows($ret_cats))

	{

		while ($row_cats = $db->fetch_array($ret_cats))

		{

			$cats_arr[] = $row_cats['product_categories_category_id'];

		}

		$sql_grps	=	"SELECT

								DISTINCT	product_labels_group_group_id

								FROM 		product_category_product_labels_group_map a, product_labels_group b

								WHERE 		a.product_labels_group_group_id = b.group_id 

								AND 		b.group_hide = 0 

								AND			product_categories_category_id IN (".implode(',',$cats_arr).") ";

		//echo "<br>".$sql_grps;

		$ret_grps = $db->query($sql_grps);

		if($db->num_rows($ret_grps))

		{

			while ($row_grps = $db->fetch_array($ret_grps))

			{

				$grp_arr[]	=	$row_grps['product_labels_group_group_id'];

			}	

			// Check whether there exists atleast one label to display

			$sql_lblcheck	=	"SELECT			a.map_id 

										FROM 	product_labels_group_label_map a , product_labels_group b

										WHERE 	product_labels_group_group_id IN (".implode(',',$grp_arr).") 

										AND 	a.product_labels_group_group_id=b.group_id 

										AND		b.group_hide = 0";

			//echo "<br>".$sql_lblcheck;

			$ret_lblcheck 	= $db->query($sql_lblcheck);

			$grp_nos		= $db->num_rows($ret_lblcheck);

			if($grp_nos)

			{

				// Get the product label group details in order

				$sql_grp	=	"SELECT			group_id,group_name,group_name_hide

										FROM 	product_labels_group 

										WHERE 	group_id IN (".implode(',',$grp_arr).") 

										ORDER BY group_order";

				//echo "<br>".$sql_grp;

				$ret_grp	=	$db->query($sql_grp);

				if($db->num_rows($ret_grp))

				{

					$ret_val	=	'';

					$i			=	1;

					$grp_cnt	=	0;

					$label_arr	=	array();

					while ($row_grp = $db->fetch_array($ret_grp))

					{

						// Check whether there exists atleast one label under this group to display

						$sql_labels	=	"SELECT

														a.label_id,

														a.label_name,

														a.in_search,

														a.is_textbox,

														c.product_site_labels_values_label_value_id,

														c.label_value 

												FROM	product_site_labels a,product_labels_group_label_map b,product_labels c

												WHERE 	b.product_labels_group_group_id = ".$row_grp['group_id']." 

												AND		c.products_product_id = $prod_id

												AND		a.label_id = b.product_site_labels_label_id 

												AND		a.label_id = c.product_site_labels_label_id 

												AND		a.label_hide = 0 

												AND		(c.product_site_labels_values_label_value_id>0 OR  label_value <> '')

												ORDER BY b.map_order";

						//echo "<br>".$sql_labels;

						$ret_labels	=	$db->query($sql_labels);

						if($db->num_rows($ret_labels))

						{

							$grp_cnt++;
							
						?>
						<div id="owl-demo-unipaddetlabel1" class="owl-carousel_outer1">
						<script src="<?php echo $ecom_selfhttp.$ecom_hostname?>/images/<?php echo $ecom_hostname?>/scripts/jquery.carouFredSel-6.0.4-packed.js" type="text/javascript"></script>	
						<?php
						$items_str = '';	

							//$label_arr[$row_grp['group_name'].'~'.$row_grp['group_name_hide']] = array();

							while($row_labels = $db->fetch_array($ret_labels))

							{
                                $label_image ='';
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "bedroom")

								{	$label_image	=	'icon_double_bed_no_name_p.svg';		}

								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "bathroom")

								{	$label_image	=	'icon_bathroom_p.svg';		}

								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "walkable")

								{	$label_image	=	'icon_walkable.svg';		}

								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "flattv")

								{	$label_image	=	'icon_flat_tv_no_name.svg';		}

								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "coffeetable")

								{	$label_image	=	'icon_coffee_table_noname.svg';		}
								
								
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "utilitybills")

								{	$label_image	=	'features_utility_p.svg';		}

								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "bikestore")

								{	$label_image	=	'features_sheltered_p.svg';		}

								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "24/7maintenance")

								{	$label_image	=	'features_maintenance_p.svg';		}
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "doublebeds")
								
								{	$label_image	=	'icons_double_bed_p.svg';		}
								
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "toilet")

								{	$label_image	=	'icons_toilet.svg';		}
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "shower")

								{	$label_image	=	'feature_body_jet_p.svg';		}
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "citycentre")

								{	$label_image	=	'feature_city_centre_p.svg';		}
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "ensuitebathrooms")

								{	$label_image	=	'icon_ensuit_p.svg';		}
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "broadband")

								{	$label_image	=	'icons_broadband.svg';		}
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "fibrebroadband")

								{	$label_image	=	'feature_fibre_p.svg';		}
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "wirelessinternet")
								{	$label_image	=	'feature_WIFI_p.svg';		}
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "lettingperiod")
								{	$label_image	=	'feature_50_week_p.svg';		}
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "monthlycleaningservice")

								{	$label_image	=	'icon_cleaning.svg';		}
							
							$items_str .='<div class="label_outer_cntr">
									<div class="label_div_cls"><img src="'.url_site_image($label_image,1).'" alt="'.$row_labels['label_value'].'" />
									<div class="span_label_text">'.$row_labels['label_value'].'</div>
									</div></div>
								';	
							
							}
						?>
							<div id="carousel22">
								<?php echo $items_str;?>
							</div>
						</div>
						
						<?php /*<script type="text/javascript">
							$(document).ready(function() {
							var owls = $("#owl-demo-unipaddetlabel");
								owls.owlCarousel({
									items : 3, // items above 1000px browser width
									itemsDesktop : [1000,3], // items between 1000px and 901px
									itemsDesktopSmall : [900,3], // betweem 900px and 601px
									itemsTablet: [600,3], // items between 600 and 0;
									itemsMobile : false, // itemsMobile disabled - inherit from itemsTablet option 
									
									scrollPerPage: false,
									autoPlay:true,
									slideSpeed : 3500,
									paginationSpeed : 3000,
									rewindSpeed : 1550,
									pagination:false,
									autoplaySpeed:3500
								});
							});	
							</script>	
							
							*/?>
							
							<script type="text/javascript">
							$(function() {

								var cssSmall = {
									width: 100,
									height: 125
								};
								var cssMedium = {
									width: 150,
									height: 215,
									marginTop: 35
								};
								var cssLarge = {
									width: 200,
									height: 250,
									marginTop: 0
								};
								var aniConf = {
									queue: false,
									duration: 300
								};

								$('#carousel22')
									.children().css(cssSmall)
									.eq(1).css(cssSmall)
									.next().css(cssSmall)
									.next().css(cssSmall);

								$('#carousel22').carouFredSel({
									width: '100%',
									height: 180,
									/*items: 1,*/
									scroll: {
										items: 2,
										duration: 500,
									onBefore: function( data ) {								

										//	0 [ 1 ] 2  3  4
										data.items.old.eq(1).animate(cssSmall, aniConf);

										//	0  1 [ 2 ] 3  4
										data.items.old.eq(2).animate(cssSmall, aniConf);

										// 0  1  2  [ 3 ] 4
										data.items.old.eq(3).animate(cssSmall, aniConf);

										//	0  1  2  3 [ 4 ]
										data.items.old.eq(4).animate(cssSmall, aniConf);
										}
									}
								});

							});
							</script>
							
						<?php	
						}
					}
				}

			}

		}	

	}

}
	};	
?>
