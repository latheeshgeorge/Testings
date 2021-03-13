<?php
	/*############################################################################
	# Script Name 	: productHtml.php
	# Description 	: Page which holds the display logic for product details
	# Coded by 		: Sny
	# Created on	: 12-Aug-2009
	# Modified by	: 
	# Modified On	: 
	
	##########################################################################*/
	class product_Html
	{
		// Defining function to show the selected product details
		function Show_ProductDetails($row_prod)
		{
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$ecom_themename,
					 $ecom_themeid,$default_layout,$inlineSiteComponents,$Settings_arr,$meta_arr,$ecom_twitteraccountId,$cookieval,$load_mobile_theme_arr;
					 		$addto_cart_withajax =  1;
			global $ecom_selfhttp;
			// ** Fetch any captions for product details page
			$Captions_arr['PROD_DETAILS'] 	= getCaptions('PROD_DETAILS');
			$Captions_arr['PRODUCT_REVIEWS'] 	= getCaptions('PRODUCT_REVIEWS');
			$Captions_arr['COMMON'] 	= getCaptions('COMMON');
			
			// ** Fetch the product details
			//$row_prod	= $db->fetch_array($ret_prod);
			// ** Check to see whether current user is logged in 
			$cust_id 	= get_session_var("ecom_login_customer");
			
			// ** Check whether qty box is to be shown
			$showqty	= $Settings_arr['show_qty_box'];// show the qty box
			if($_REQUEST['result']=='exists')
			{
				$alert 	= stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_ADDFAVEXISTS']);
			}
			else if($_REQUEST['result']=='added')
			{
				$alert 	= stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_ADDFAVADDED']);
			}
			else if($_REQUEST['result']=='removed')
			{
				$alert 	= stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_ADDFAVREMOVED']);
			}
			$prod_img_show_type = $row_prod['product_details_image_type'];
			$email_show 		= 0;
			$favourite_show		= 0;
			$writereview_show	= 0;
			$readreview_show	= 0;
			$pdf_show			= 0;
			$compare_show		= 0;
			if(isProductCompareEnabledInProductDetails())
			{
				$def_cat_id = $row_prod['product_default_category_id'];
				$sql_comp 	= "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
										a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
										a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
										a.product_total_preorder_allowed,a.product_applytax,a.product_shortdesc,a.product_variable_display_type,
										a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,
										a.product_variablesaddonprice_exists,
										a.product_variablecomboprice_allowed,a.product_variablecombocommon_image_allowed,a.default_comb_id,
										a.price_normalprefix,a.price_normalsuffix, a.price_fromprefix, a.price_fromsuffix,price_specialofferprefix, a.price_specialoffersuffix, 
										a.price_discountprefix, a.price_discountsuffix, a.price_yousaveprefix, a.price_yousavesuffix,a.price_noprice,
										a.product_averagerating,a.product_saleicon_show,a.product_saleicon_text,a.product_newicon_show,a.product_newicon_text,
										a.product_freedelivery       
									FROM 
										products a,product_category_map b 
									WHERE 
										b.product_categories_category_id=".$def_cat_id." 
										AND a.product_id <>".$_REQUEST['product_id']." 		
										AND a.sites_site_id=$ecom_siteid 
										AND a.product_id = b.products_product_id  	
									ORDER BY 
										b.product_order 
									LIMIT 
										1";
				$ret_comp_prod = $db->query($sql_comp);
				if($db->num_rows($ret_comp_prod))
				{
					$compare_show = 1; // compare link
				}	
			}	
			if(in_array('mod_emailafriend',$inlineSiteComponents) and $Settings_arr['proddet_showemailfriend']==1)
				$email_show = 1; // email a friend link
			if($Settings_arr['proddet_showwritereview']==1)
				$writereview_show = 1;	// write review link
			if($Settings_arr['proddet_showreadreview']==1)
				$readreview_show = 1; // read review link
			if(in_array('mod_downloadpdf',$inlineSiteComponents) and $Settings_arr['proddet_showpdf']==1)
				$pdf_show	= 1;	 // pdf download link
			if($cust_id)
			{
				if($Settings_arr['proddet_showfavourite']==1)
					$favourite_show = 1;
			}

			
			/********************** HTML Generating starts here *********************/
			$HTML_treemenu = $HTML_showstock = $HTML_compare = $HTML_saleicon = $HTML_fav = $HTML_readrev = $HTML_writerev = '';
			$HTML_email = $HTML_pdf = $HTML_price = $HTML_bonus = $HTML_wishlist = $HTML_enquiry = $HTML_promise_buttons = '';
			$HTML_treemenu = ' <div class="breadcrumbs">
				<ol class="breadcrumb">'.generate_tree_menu(-1,$_REQUEST['product_id'],'','<li>','</li>').'</ol>
			</div>';
			$disp_stk = get_stockdetails($_REQUEST['product_id']);
			if($disp_stk!='')
				$HTML_showstock = '<div class="deat_pdt_stock"><div class="deat_pdt_stock_left"><span>'.$disp_stk.'</span></div></div>';
			if( $compare_show==1)
			{
				$def_cat_id = $row_prod['product_default_category_id'];
				$HTML_compare = '<a href="'.url_productcompare($_REQUEST['product_id'],$row_prod['product_name'],1).'" class="productdetailslink"  title="'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_COMPARE_LINK']).'"><img src="'.url_site_image('compare.gif',1).'" border="0" alt="'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_COMPARE_LINK']).'" /></a>';
			}
			if($row_prod['product_saleicon_show']==1)
			{
				$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
				if($desc!='')
				{
					$HTML_saleicon = '<div class="deat_pdt_sale">'.$desc.'</div>';
				}
			}
			if($row_prod['product_newicon_show']==1)
			{
				$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
				if($desc!='')
				{
					$HTML_saleicon = '<div class="deat_pdt_new">'.$desc.'</div>';
				}
			}
			if($favourite_show==1) // Decide whether favorite option is to be displayed
			{
				$sql_prod = "SELECT products_product_id FROM customer_fav_products WHERE sites_site_id=$ecom_siteid AND products_product_id=".$_REQUEST['product_id']." AND customer_customer_id=$cust_id LIMIT 1";
				$ret_num= $db->query($sql_prod);
				if($db->num_rows($ret_num)==0) 
				{ 
					$HTML_fav = '<a href="#" onClick="if(confirm(\''.stripslash_javascript($Captions_arr['PROD_DETAILS']['PRODDET_ADD_CONFIRM']).'\')){document.frm_proddetails.fpurpose.value=\'add_favourite\';document.frm_proddetails.submit();}"><img src="'.url_site_image('det-icon_01.gif',1).'" alt="'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_FAVOURITE']).'" title="'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_FAVOURITE']).'" /> '.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_FAVOURITE']).'</a>';
				}
				else
				{
					$HTML_fav = '<a href="#" class="productdetailslink" onClick="if(confirm(\''.stripslash_javascript($Captions_arr['PROD_DETAILS']['PRODDET_REM_CONFIRM']).'\')){document.frm_proddetails.fpurpose.value=\'remove_favourite\';document.frm_proddetails.submit();}"><img src="'.url_site_image('remfavourite.gif',1).'" alt="'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_REMFAVOURITE']).'" title="'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_REMFAVOURITE']).'" /> '.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_REMFAVOURITE']).'</a>';
				}
			}
			if(in_array('mod_product_reviews',$inlineSiteComponents)) // Check whether the product review module is there for current site
			{
				if($writereview_show==1)
				{
					$HTML_writerev = '<a href="'.url_link('writeproductreview'.$_REQUEST['product_id'].'.html',1).'" title="'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_WRITEREVIEW']).'">Write Review</a>';
				}
				if($readreview_show == 1 or $writereview_show == 1)
				{
					$HTML_readrev = '<a href="'.url_link('readproductreview'.$_REQUEST['product_id'].'.html',1).'" title="'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_READREVIEW']).'"><img src="'.url_site_image('det-icon_05.gif',1).'" border="0" /> '.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_READREVIEW']).'</a>';
				}
			}
			if($email_show==1)
			{
				$HTML_email = '<a href="'.url_link('emailafriend'.$_REQUEST['product_id'].'.html',1).'" title="'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_EMAILFRIEND']).'"><img src="'.url_site_image('det-icon_07.gif',1).'" border="0" /> '.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_EMAILFRIEND']).'</a>';
			}
			if($pdf_show==1) // Check whether the download pdf module is there for current site
			{ 
				$HTML_pdf = '<a href="javascript:download_pdf(\''.$_SERVER['HTTP_HOST'].'\',\''.$_SERVER['REQUEST_URI'].'\')" title="'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_DOWNLOADPDF']).'"><img src="'.url_site_image('det-icon_09.gif',1).'" border="0" /> '.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_DOWNLOADPDF']).'</a>';
			}
			/*$was_price = $cur_price = $sav_price = '';
			$price_arr = show_Price($row_prod,$price_class_arr,'prod_detail',false,3);
			
			if($price_arr['discounted_price'])
										$HTML_price = $price_arr['discounted_price'];
									else
										$HTML_price = $price_arr['base_price'];
										*/  					
			if ($Settings_arr['bonus_points_instock'] and $row_prod['product_bonuspoints']>0)
			{
				$HTML_bonus = '<div class="deat_bonus">
								<div class="deat_bonusA">'.$row_prod['product_bonuspoints'].'</div>
								<div class="deat_bonusB">'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_BONUSPOINTS']).'</div>
								<div class="deat_bonusC"><a href="'.url_link('bonuspoint_content.html',1).'" title=""><img src="'.url_site_image('bonusmoreinfo.gif',1).'" /></a></div>
								</div>';
			}
			
			$in_combo = is_product_in_any_valid_combo($row_prod);
			?>
			<?php			
			if($row_prod['product_flv_filename']!='')
			{
				/*$HTML_video = '<div class="deat_pdt_button">
								<a href="javascript:show_normal_video()"><img src="'.url_site_image('video-but.gif',1).'" border="0" /></a>
								</div>
								<div id="div_defaultFlash_outer" class="flashvideo_outer" style="display:none"></div>
								<div style="display: none;" id="div_defaultFlash" class="content_default_flash">
								<div id="flash_close_div" align="right"><a href="javascript:close_video()">Close</a></div>
								<div id="flash_player_div">
								<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://macromedia.com/cabs/swflash.cab#version=6,0,0,0" ID=flaMovie WIDTH=500 HEIGHT=350>
								<param NAME=movie VALUE="http://'.$ecom_hostname.'/images/'.$ecom_hostname.'/swf/player.swf">
								<param NAME=FlashVars VALUE="file=http://'.$ecom_hostname.'/images/'.$ecom_hostname.'/product_flv/'.$row_prod['product_flv_filename'].'">
								<param NAME=quality VALUE=medium>
								<param NAME=bgcolor VALUE=#99CC33>
								<embed src="http://'.$ecom_hostname.'/images/'.$ecom_hostname.'/swf/player.swf" FlashVars="file=http://'.$ecom_hostname.'/images/'.$ecom_hostname.'/product_flv/'.$row_prod['product_flv_filename'].'" bgcolor=#99CC33 WIDTH=500 HEIGHT=350 TYPE="application/x-shockwave-flash">
								</embed>
								</object>
								</div>
								</div>';*/
			}
			$HTML_loading = '<div class="proddet_loading_outer_div" style="height:15px"><div id="proddet_loading_div" style="display:none;padding:5px 0 0 0;">
							<img src="'.url_site_image('proddet_loading.gif',1).'" alt="loading..." width="43px" height="11px;align:left">
							</div></div>';	
							
			$HTML_bottomblock = '
								<div class="deat_icons_outr">
								<div class="deat_icons_top"></div>
								<div class="deat_icons_bottom">
								<div class="deat_pdt_icons"> 
								'.$HTML_saleicon.'
								<div class="deat_pdt_iconsleft">'.$HTML_fav.$HTML_readrev.$HTML_email.$HTML_pdf.'</div></div>';
			if ($Settings_arr['show_bookmarks'])
			{
				$HTML_bottomblock .='
								<div class="deat_pdt_bookmark_heading">'.stripslash_normal($Captions_arr['PROD_DETAILS']['BOOKMARK_HEADING']).'</div>
								<div class="deat_pdt_bookmark">'.bookmarks(url_product($row_prod['product_id'],$row_prod['product_name'],1),htmlspecialchars($meta_arr['title']),$ecom_twitteraccountId,1).'
								</div>';
			}
			$HTML_bottomblock .='				  
								</div>   
								</div>';
			?>
			<script type="text/javascript">
			
			function ajax_return_productdetailscontents() 
			{
				if(document.getElementById('how_displayed'))
				var how_displayed = document.getElementById('how_displayed').value;
			    else
				var how_displayed = '';	
				if(how_displayed!='proddet_ajax_list')
			    { 
				var ret_val = '';
				var disp 	= 'no';
				var docroot = '<?php SITE_URL?>';
				var prod_id	= <?php echo $_REQUEST['product_id']?>;
				var loading_gif = '<?php echo url_site_image('loading.gif',1)?>';
				if(req.readyState==4)
				{ 
					if(req.status==200)
					{ 
						ret_val 		= req.responseText;
						targetobj 	= eval("document.getElementById('"+document.getElementById('ajax_div_holder').value+"')");
						targetobj.innerHTML = ret_val; /* Setting the output to required div */
						if(document.getElementById('ajax_div_holder').value=='price_holder')
						{
							handle_show_prod_det_bulk_disc('main_img',docroot,prod_id,loading_gif);
						}
						/*
						else if(document.getElementById('ajax_div_holder').value=='mainimage_holder')
						{
						    handle_show_prod_det_bulk_disc('main_img',docroot,prod_id,loading_gif);
						}
						*/
						
						if(document.getElementById('ajax_div_holder').value=='cart_details_ajaxholder')
						{	
							if(document.getElementById('shoppingcart_ajax_container'))
								{
									ajax_addto_cart_fromlist('show_shop_cart','','',docroot);
									//show_ajax_holder('cart');
								}						
							show_ajax_holder('cart');
							hideme('#proddet_loading_div_ajax');
						}
						hide_loading('proddet_loading_div');
					}
					else
					{
						hide_loading('proddet_loading_div');
					}
				}
			}
			else
			{
				var ret_val = '';
				var disp 	= 'no';
				var docroot = '<?php echo SITE_URL?>';
				var prod_id 	= document.frm_proddetails_ajax.product_id_ajax.value;
				var mod = 'ajax';
				var loading_gif = '<?php echo url_site_image('loading.gif',1)?>';	
				if(req.readyState==4)
				{ 
					if(req.status==200)
					{ 
						ret_val 		= req.responseText;
						targetobj 	= eval("document.getElementById('"+document.getElementById('ajax_div_holder').value+"')");
						targetobj.innerHTML = ret_val; /* Setting the output to required div */														
						if(document.getElementById('ajax_div_holder').value=='price_holder')
						{
							handle_show_prod_det_bulk_disc('bulk',docroot,prod_id,loading_gif,mod);
						}					
						if(document.getElementById('ajax_div_holder').value=='cart_details_ajaxholder')
						{
							if(document.getElementById('shoppingcart_ajax_container'))
								{
									ajax_addto_cart_fromlist('show_shop_cart','','',docroot);
									//show_ajax_holder('cart');
								}	
							show_ajax_holder('cart');
						}
							hide_loading('proddet_loading_div');
							hideme('#proddet_loading_div_ajax');

					}
					else
					{
					hide_loading('proddet_loading_div');
					hideme('#proddet_loading_div_ajax');

					/*alert(req.status);*/
					}
				}
			}
		}
			<?php
			
			?>
			function handle_price_promise()
			{
				var url 	= '<?php echo url_link("pricepromisecustlogin.html",1)?>';
				var cust_id = '<?php echo $cust_id?>';
				if(cust_id)
				{
					document.frm_proddetails.fpurpose.value = 'price_promise';
					document.frm_proddetails.submit();
				}
				else
				{
					document.frm_proddetails.action = url;
					document.frm_proddetails.pagetype.value = 'prodhtml';
					document.frm_proddetails.submit();
				}
			}
			function show_summary_ajax(prodid,mod)
			{
				change_class(mod);
				var themename = '<?php echo $ecom_themename?>';	
				var siteid = '<?php echo $ecom_siteid?>';				
				if(mod=='show_img')
					{
						document.getElementById("show_img_tr").style.display ='';
						document.getElementById("show_ajax_tr").style.display = 'none';
					}
					else
					{											
						document.getElementById("show_img_tr").style.display ='none';
						document.getElementById("show_ajax_tr").style.display = '';

						var fpurpose = 'Show_summary';
						var qrystr   = '&theme_name='+themename+'&product_id='+prodid+'&mod='+mod+'&site_id='+siteid;	
						var docroot  = '<?php echo SITE_URL?>';			
						var retdivid = 'summary_ajax_container';
						document.getElementById('ajax_div_holder').value = retdivid;
						retobj	= eval("document.getElementById('"+retdivid+"')");	
						Handlewith_Ajax(docroot +'/includes/base_files/ajax_mobiletheme.php','ajax_fpurpose='+fpurpose+'&'+qrystr);
						retobj.innerHTML = "<div align='center'><img src ='<?php url_site_image('loading.gif')?>' alt='loading...' border='0'></div>";								
			       }

				}
				function change_class(mod)
				{
					if(mod=='summary')
					{			 
					document.getElementById("show_sum").className = "tdbga";
					document.getElementById("show_sum_a").className = "linkbold_a";
						if(document.getElementById("show_im"))
						{
							document.getElementById("show_im").className = "tdbgb";
							document.getElementById("show_im_a").className = "linkbold_click";
						}
						if(document.getElementById("show_desc"))
						{
							document.getElementById("show_desc").className = "tdbgb";
							document.getElementById("show_desc_a").className = "linkbold_click";
						}
					}
					else if(mod=='show_img')
					{
					document.getElementById("show_sum").className = "tdbgb";
					document.getElementById("show_sum_a").className = "linkbold_click";
					document.getElementById("show_im").className = "tdbga";
					document.getElementById("show_im_a").className = "linkbold_a";
					if(document.getElementById("show_desc"))
						{
							document.getElementById("show_desc").className = "tdbgb";
							document.getElementById("show_desc_a").className = "linkbold_click";
						}
					}
					else if(mod=='show_desc')
					{
					document.getElementById("show_sum").className = "tdbgb";
					document.getElementById("show_sum_a").className = "linkbold_click";
					if(document.getElementById("show_im"))
					{
						document.getElementById("show_im").className = "tdbgb";
						document.getElementById("show_im_a").className = "linkbold_click";
					}
					document.getElementById("show_desc").className = "tdbga";
					document.getElementById("show_desc_a").className = "linkbold_a";
					}
					
			}
			/*// Set up PhotoSwipe with all anchor tags in the Gallery container
			document.addEventListener('DOMContentLoaded', function(){

				var myPhotoSwipe = Code.PhotoSwipe.attach( window.document.querySelectorAll('#Gallery a'), { enableMouseWheel: false , enableKeyboard: false } );
				var myPhotoSwipe = Code.PhotoSwipe.attach( window.document.querySelectorAll('#Gallery_ajax a'), { enableMouseWheel: false , enableKeyboard: false } );

			}, false);*/
 
			</script>
<?php
		$HTML_treemenu = '
				
				<div class="breadcrumbs">
				<div class="container-fluid1">
        <div class="container-tree" style="padding-left:12px !important;">
          <ul>
				'.generate_tree_menu(-1,$_REQUEST['product_id'],'&#8594;','<li>',' </li>').
				'
			 </ul>
    </div>
  </div></div>';	
  echo $HTML_treemenu;
		/*
		?>
		<script type="text/javascript"
            src="https://cdn.rawgit.com/igorlino/fancybox-plus/1.3.6/src/jquery.fancybox-plus.js"></script>
    <link rel="stylesheet" type="text/css"
          href="https://cdn.rawgit.com/igorlino/fancybox-plus/1.3.6/css/jquery.fancybox-plus.css" media="screen"/>
          */
          /*
          ?> 
          <script src="https://cdn.rawgit.com/igorlino/elevatezoom-plus/1.1.20/src/jquery.ez-plus.js"></script>
            <script type="text/javascript"
            src="https://cdn.rawgit.com/igorlino/fancybox-plus/1.3.6/src/jquery.fancybox-plus.js"></script>
    <link rel="stylesheet" type="text/css"
          href="https://cdn.rawgit.com/igorlino/fancybox-plus/1.3.6/css/jquery.fancybox-plus.css" media="screen"/>
          */
          /*echo '
									<!-- PhotoSwipe Core CSS file -->
									<link rel="stylesheet" href="'.url_head_link("images/".$ecom_hostname."/scripts/jqueryphotoswype/src/PhotoSwipe/photoswipe.css",1).'">

									<!-- PhotoSwipe Skin CSS file (styling of UI - buttons, caption, etc.)-->
									<link rel="stylesheet" href="'.url_head_link("images/".$ecom_hostname."/scripts/jqueryphotoswype/src/PhotoSwipe/default-skin/default-skin.css",1).'">

									<!-- PhotoSwipe Core JS file -->
									<script src="'.url_head_link("images/".$ecom_hostname."/scripts/jqueryphotoswype/src/PhotoSwipe/photoswipe.min.js",1).'"></script>

									<!-- PhotoSwipe UI JS file -->
									<script src="'.url_head_link("images/".$ecom_hostname."/scripts/jqueryphotoswype/src/PhotoSwipe/photoswipe-ui-default.min.js",1).'"></script>

									<!-- jqPhotoSwipe JS file -->
									<script src="'.url_head_link("images/".$ecom_hostname."/scripts/jqueryphotoswype/src/jqPhotoSwipe.js",1).'"></script>
									';*/
          ?> 
          
			<div >
				<?php
				/* 
				$elvate_ext ='';
				$ext_scr ='';
				$winscroller='';
				if($load_mobile_theme_arr[0]==1)
				{
				  $elvate_ext = ",
				  zoomType: \"inline\",
				  lensShape : \"round\"";
				  
				  
			    }
				  $ext_scr = "$('#zoom_09').removeData('elevateZoom');
        $('.zoomWrapper img.zoomed').unwrap();
        $('.zoomContainer').remove();
        $('#zoom_09').unbind('touchmove');";
				
				?>
<div class="women_main">
		<!-- start content --><script type="text/javascript">
		$(document).ready(function () {
			<?php /*
			$("#gallery_09").on('click', function(e) {
		$('.zoomContainer').remove();
		$('.zoomWrapper img.zoomed').unwrap();
		$('#zoom_09').removeData('elevateZoom');
		
	});	
	*/
	/*
	?>
		$("#zoom_09").elevateZoom({
		gallery : "gallery_09",
		responsive:true,
		loadingIcon: '<?php echo url_site_image('spinner_load.gif',1)?>',
		galleryActiveClass: "active"
		<?php 
		echo $elvate_ext;
		?>
		
		}); 

    <?php
    //echo $ext_scr;
    
    ?>
    

		}); 


		</script>      
		*/
		$was_price = $cur_price = $sav_price = '';
			$price_arr = show_Price($row_prod,$price_class_arr,'prod_detail',false,5);
			//print_r($price_arr);
			if($price_arr['price_with_captions']['discounted_price'])
			{
				$was_price = $price_arr['price_with_captions']['base_price'];
				$cur_price = $price_arr['price_with_captions']['discounted_price'];
				if($price_arr['price_with_captions']['disc_percent'])
					$sav_price = $price_arr['price_with_captions']['disc_percent'];
				else
					$sav_price = $price_arr['price_with_captions']['yousave_price'];
			}
			else
			{
				$was_price = '';
				$cur_price = $price_arr['price_with_captions']['base_price'];
				$sav_price = '';
			}
		?>
			<div class="row single">
				<div class="col-md-8 det">
					
				  <div class="single_left">
					  <div class="header_shelfs" style="padding-left:12px;"><h1 class="h1-header-cls"><?php echo stripslash_normal($row_prod['product_name']); ?></h1> <strong> <?php 
					if($was_price)
					$HTML_price .= '<span class="was_price_p">'.$was_price.'</span>';
					if($cur_price)
					$HTML_price .= '<span class="cur_price_p" >'.$cur_price.'</span>';	
					echo $HTML_price;
				?> </strong>
					<?php
					if($row_prod['product_newicon_show']==1)
					{
						$descstr = stripslash_normal(trim($row_prod['product_newicon_text']));
						if($descstr!='')
						{
							$str_arrstr = explode ("~", $descstr);
							
						}
					}
					
						?>
				
				<div style="float:left; width:100%;">
					<?php
					if($str_arrstr[0]!='' AND $str_arrstr[0]=='Brand New For 2020')
					{
					  echo $HTML_newAA = '<div class="normal_shlfAA_pdt_new1"><img src="'.url_site_image('left_brand_new-detail.png',1).'" alt="Brand New"></div>';
					}					
					
					if($str_arrstr[1]!=''  AND $str_arrstr[1]=='Examples Of Finish')
					{
					  //echo $HTML_newBB = '<div class="normal_shlfBB_pdt_new"><img src="'.url_site_image('example-finish.png',1).'" alt="Example Finish"></div>';
					   echo $HTML_newBB = '<div class="normal_shlfAA_pdt_new2"><img src="'.url_site_image('right_brand_examples-detail.png',1).'" alt="Brand New"></div>';

					}					
					?>

				</div>
				
				
				</div>
					  
					  					   <div id="mainimage_holder">

					  <?php $this->Show_Image_Normal($row_prod,'main');?>
					</div>
				  
          	   </div>
          	    
				
	       </div>
	       				
    
     <?PHP   
    // global $components;
               // $position = 'left';
                //include(ORG_DOCROOT."/themes/$ecom_themename/modules/mod_recentlyviewedproduct.php");
					?>
		   <div class="col-md-4">
			   <div class="detail_right">
				   <?php
    $product_idd = $_REQUEST['product_id']; 
    $date = date("Y-m-d H:m:s", strtotime('-24 hours', time())); 
	 $sql_prod_check = "SELECT * FROM product_viewed_count WHERE views_sites_site_id=$ecom_siteid AND views_product_id=$product_idd AND views_time_stamp > '$date'";
	$ret_prod_check =  $db->query($sql_prod_check);
	if($db->num_rows($ret_prod_check)>0)
	{
		$num_views = $db->num_rows($ret_prod_check);
	?>
		<div class="Product_viewedcls" >Viewed <?php echo $num_views;?> times today </div>
    <?php
	}
    ?>
	<div id="propertymenu_webview">
	<div class="propertymenu">
<ul>
<?php /*<li class="print2"><a href="javascript:window.print();">Print Details</a></li>*/?>

<?php
/*
	$lat = 0;
	 $long = 0 ;
	 $sql_keyword = "SELECT product_keywords FROM products where product_id=".$row_prod['product_id']." AND sites_site_id=$ecom_siteid LIMIT 1";
	$ret_keyword = $db->query($sql_keyword);
	 $db->num_rows($ret_keyword);
	if($db->num_rows($ret_keyword)>0)
	{
		$row_keyword =  $db->fetch_array($ret_keyword);
		$address = urlencode($row_keyword['product_keywords']);
		if($address!='')
		{
		$add_arr = explode(",",$address);
		$to = $add_arr[0];
		$url = "http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$response = curl_exec($ch);
		curl_close($ch);
		$response_a = json_decode($response);
		 $lat = $response_a->results[0]->geometry->location->lat;
		 $long = $response_a->results[0]->geometry->location->lng;
			 if($lat!=0 && $long!=0)
			 {
			 ?>
			<li class="map2"><a href="#mapcode_head">View map</a></li>

			<?php
			}
		}
}
*/
?>

<?php
$HTML_enquiry = $HTML_email = '';
if ($row_prod['product_show_enquirelink']==1)
			{
				$HTML_enquiry = '<li class="basket2"><a href="javascript:void" onclick="show_wait_button(this,\'Please Wait...\');document.frm_proddetails.fpurpose.value=\'Prod_Enquire\';document.frm_proddetails.submit();">Arrange to View</a></li>';			
			}
			//echo $HTML_enquiry;
if($email_show==1)
{
	$HTML_email = '<li class="friend2"><a href="'.url_link('emailafriend'.$_REQUEST['product_id'].'.html',1).'" title="'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_EMAILFRIEND']).'"> Send to Friend</a></li>';
}	
echo $HTML_email;
	$HTML_callback = '<li class="callback2"><a href="'.url_link('callback.html',1).'" onclick="req_callback(this)" title="Request A Call Back"> Request A Call Back</a></li>';
		echo $HTML_callback;

$HTML_faq = '<li class="faq2"><a href="'.$ecom_selfhttp.$ecom_hostname.'/faq.html" title="FAQ"> Got A Question? Visit FAQ</a></li>';
echo $HTML_faq;
			
?>





</ul>
 

</div>
<div class="callwrap_outer">
<div class="callwrap">
<ul>
<li class='fb_call'><a href="https://www.messenger.com/t/unipadlancaster/" target="_blank"><img src='<?php url_site_image('floating_img.svg')?>' style="border:0;width:366px;height:85px;" alt="Speak With Unipad on Facebook Messenger"></a></li>
</ul>
<script type="text/javascript" src="//platform-api.sharethis.com/js/sharethis.js#property=58b812b3facf57001271be36&product=inline-share-buttons"></script>
<div class="social_share_div"><div class="sharethis-inline-share-buttons"></div>
</div>

</div>

</div>
</div>
<div class="callwrap1">
<ul>
<li class="call_icon"><a href="tel:07872377266" style="font-size:20px;"> 07872 377266
 </a></li>
</ul>
</div>



					<?php
					$prodid				= $_REQUEST['product_id'];
					$label_val = $this->show_ProductLabels($prodid);
					echo $label_val;
?>
<script type="text/javascript">
			
			function Show_confirm(frm) 
			{
				var fpurpose;
				//show_wait_button(thisa,'Please Wait...');
				
				document.frm_proddetails.fpurpose.value='Prod_Enquire';
				document.frm_proddetails.submit();
				
			}
			</script>
<div class="deat_pdt_buy_outr">
	<?php 
	$frm_name = 'frm_proddetails';
	?>
	<form method="post" name="frm_proddetails" id="frm_proddetails" action="<?php url_link('manage_products.html')?>" class="frm_cls" onsubmit="return prod_detail_submit(this)">
			<input type="hidden" name="fpurpose" value="" />
			<input type="hidden" name="fproduct_id" value="<?php echo $_REQUEST['product_id']?>" />
			<input type="hidden" name="pricepromise_url" value="<?php url_link('pricepromise'.$row_prod['product_id'].'.html')?>" />
			<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
			<input type="hidden" name="pass_combid" value="<?php echo $_REQUEST['pass_combid']?>" />
			<input type="hidden" name="comb_img_allowed" id="comb_img_allowed" value="<?php echo $row_prod['product_variablecombocommon_image_allowed']?>" />
			<input type="hidden" name="ajax_div_holder" id="ajax_div_holder" value="" />
			<input type="hidden" name="pagetype" id="pagetype" value="" />
				 	 <?php
					
					
					?>
					
					<div class="deat_pdt_buy_right">
						<?php
						 if($row_prod['product_show_cartlink']==0)
						 {
							 
							 $sql_pp = "SELECT product_actualstock FROM products WHERE product_id =".$row_prod['product_id']." LIMIT 1";
							$ret_pp = $db->query($sql_pp);
							$row_pp = $db->fetch_array($ret_pp);
							if($row_pp['product_actualstock']==0)
							{
							$onclick = "";
							//$type ='button';
							//$class_arr['BTN_CLS']     = 'btn btn-add-to-cart btn-lg sharp enquire-confirm';
							}
							else
							{
							//$type ='submit';
							//$class_arr['BTN_CLS']     = 'btn btn-add-to-cart btn-lg sharp';
							  $onclick = "show_wait_button(this,'Please Wait...');document.frm_proddetails.fpurpose.value='Prod_Enquire';document.frm_proddetails.submit();";
							}
							
							if ($row_prod['product_show_enquirelink']==1)	
							{
							 ?>
							 <input value="Arrange Viewing" name="Arrange Viewing" class="btncart btn btn-add-to-cart btn-lg sharp enquire-confirm" id="<?php echo $row_prod['product_id']?>" type="button" onclick="<?php echo $onclick ?>">
							<?php
							}
						}
						else
						{
						/*
						<span class="incr-btn" id="decr-btn"  >-</span>
                  <input id="qty" class="form-control" type="text" name="qty"  value="<?php echo ($_REQUEST['qty'])?$_REQUEST['qty']:1?>">
                  <span class="incr-btn" id="incr-btn" >+</span><?php 
                  */ 
                  $class_arr['ADD_TO_CART']       = 'btn btn-add-to-cart btn-lg sharp';
					$class_arr['PREORDER']          = 'btn btn-add-to-cart btn-lg sharp';
					$class_arr['ENQUIRE']           = 'btn btn-add-to-cart btn-lg sharp';
					$class_arr['QTY_DIV']           = '';
					$class_arr['QTY']               = ' ';
					$class_td['QTY']				= '';
					$class_td['TXT']				= '';
					$class_td['BTN']				= 'btn btn-add-to-cart btn-lg sharp';
					$class_arr['BTN_CLS']     = 'btn btn-add-to-cart btn-lg sharp';
					$frm_name = "frm_proddetails";
					$mod = show_addtocart_responsive($frm_name,$row_prod,$class_arr,0,'shelf',true,true);
                  echo show_addtocart_responsive($frm_name,$row_prod,$class_arr,0,'shelf',false,false);?>
					 <? //$this->show_buttons($row_prod);?>
					
					 <?php
					 /*
					<div class="deat_pdt_buy_left">
					<div class="deat_pdt_buyA"><? //=$HTML_wishlist?>
					</div>
					</div>
					<?php
                     */
              		
					}
					?>
				</div>
	 </form>
				</div>
 <script type="text/javascript">
							$(".enquire-confirm ").on("click", function(){ 
								
							$("#myModal").modal('show');
							});
							$("#modal-btn-si").on("click", function(){
							$("#myModal").modal('hide');
							Show_confirm('<?php echo $frm_name ?>');
							});

							$("#modal-btn-no").on("click", function(){
							$("#myModal").modal('hide');
							return false;
							});

							</script>

<?php					
					//print_r($_SERVER);
					//echo $_SERVER['REQUEST_URI'];
					$qr_url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
					if($load_mobile_theme_arr[0]==0)
								{
									?>
					<div id="qrwrap"><p class="qrcode_p"><strong>Save to mobile</strong><span>Scan this code to save this property on your mobile device.</span></p>
		 <div id="qrcode" >
		<script type="text/javascript">
		document.write('<img src="https://chart.googleapis.com/chart?&amp;cht=qr&amp;chs=95x95&amp;chl=<?php echo $qr_url ?>&amp;choe=UTF-8&amp;chld=H|0" alt="QR code">');
		</script>
		</div>
		</div>
					<?php
				}
?>			
	


		<?php
	 if($lat!=0 && $long!=0)
	 {
		?>
<div class="mapcode">
					<div class ="mapcode_head" id="mapcode_head">Location</div> 

					<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false"></script>
					<div style="overflow:hidden;height:300px;width:275px;">
					<div id="gmap_canvas" style="height:300px;width:275px;"></div>
					<style>#gmap_canvas img{max-width:none!important;background:none!important}</style>
					</div>
					<script type="text/javascript"> 
					function getdirections()
					{  
						var pcode ;
						pcode = document.getElementById('get-directions').value;
						window.open("https://www.google.co.uk/maps?saddr="+pcode+"&daddr=<?php echo $address?>","_blank");
					}
					function init_map()
					{
					var myOptions = {zoom:15,center:new google.maps.LatLng(<?php echo $lat?>,<?php echo $long ?>),mapTypeId: google.maps.MapTypeId.ROADMAP};
					map = new google.maps.Map(document.getElementById("gmap_canvas"), myOptions);
					marker = new google.maps.Marker({map: map,position: new google.maps.LatLng(<?php echo $lat?>,<?php echo $long ?>)});
					<?php
					/*infowindow = new google.maps.InfoWindow({content:"<b></b><br/>ernakulam <br/> vyttila" });
					google.maps.event.addListener(marker, "click", function(){infowindow.open(map,marker);});
					infowindow.open(map,marker);*/
					?>
					}google.maps.event.addDomListener(window, 'load', init_map);</script>

<div class="outdirections">
	<div class="outdirectionsD">
	<strong >Directions</strong>
	</div>
<div class="outdirectionsA">


<input id="get-directions" class="get-directions" size="20" value="Enter your postcode" onblur="if(this.value=='') this.value='Enter your postcode';" onfocus="if(this.value=='Enter your postcode') this.value='';" type="text">
<span class="outdirectionsA-span">
<a href="#" onclick="getdirections();return false;" class="">
<img src="<?php echo url_site_image('go.png')?>" alt="" border="0"></a>
<br/>
<br/>
</span>
</div>
<div class="outdirectionsB">

<br><span >Directions will opens in new window</span>
</div>
</div>
</div>				
	<?php
	}
?>
</div>
			   </div>
	  </div>
	<!-- end content -->


</div>
<div class="tabdetails">
   
<div class="tabs-7">
	
	<?php
	$tabs_arr		= $tabs_cont_arr	= array();
			$docroot			= SITE_URL;
			$prodid				= $_REQUEST['product_id'];
			$loading_gif		= url_site_image('loading.gif',1);
			$tabs_content_arr = array();
			if($row_prod['product_longdesc'])
		$tabs_content_arr[-1]	= stripslashes($row_prod['product_longdesc']);
		elseif ($row_prod['product_shortdesc'])
		$tabs_content_arr[-1]	= stripslashes($row_prod['product_shortdesc']);	
		   if (count($tabs_content_arr))
			{
				$tabs_arr[-1] 			= 'Overview';
				$tabs_arr_onclick[-1]= "show_curtab_content('ultab_-1','".$docroot."',".$prodid.",'".$loading_gif."')";
			}
			/*
			$label_val = $this->show_ProductLabels($prodid);
			if (trim($label_val)!='')
			{
				$tabs_arr[0] 			= stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_KEY_FEATURES']);
				$tabs_content_arr[0]	= $label_val;
				$tabs_arr_onclick[0]	= "show_curtab_content('ultab_0','".$docroot."',".$prodid.",'".$loading_gif."')";
			}
			*/
			
		// Get the list of tabs for current product
		$sql_tab = "SELECT tab_id,tab_title,tab_content,product_common_tabs_common_tab_id 
		FROM product_tabs 
		WHERE products_product_id = ".$_REQUEST['product_id']."
		AND tab_hide=0 
		ORDER BY tab_order"	;
		$ret_tab = $db->query($sql_tab);
		if($db->num_rows($ret_tab))
		{
				$cnt_tab = 0;
				while ($row_tab = $db->fetch_array($ret_tab))
				{
						$cnt_tab ++;
						if($cnt_tab==1)
						{
							$sel_tabid = $row_tab['tab_id'];
						}
						$tabs_arr[$row_tab['tab_id']]			= stripslashes($row_tab['tab_title']);
						if($row_tab['product_common_tabs_common_tab_id']==0)
						{
							$tabs_content_arr[$row_tab['tab_id']]	= stripslashes($row_tab['tab_content']);
						}
						else
						{
							 $sql_comm_tab = "SELECT tab_content FROM product_common_tabs WHERE 
							common_tab_id = ".$row_tab['product_common_tabs_common_tab_id']."
							AND sites_site_id = ".$ecom_siteid."  
							AND tab_hide=0 LIMIT 1";
								$ret_comm_tab = $db->query($sql_comm_tab);
							if($db->num_rows($ret_comm_tab))
							{
								$row_comm_tab = $db->fetch_array($ret_comm_tab);
								$tabs_content_arr[$row_tab['tab_id']]	= stripslashes($row_comm_tab['tab_content']);
							}
						}
					  $tabs_arr_onclick[$row_tab['tab_id']]	= "show_curtab_content('ultab_".$row_tab['tab_id']."','".$docroot."',".$prodid.",'".$loading_gif."')";
				}
		}
		
			$size_checkval = false;
			// Check whether size chart details exists for current product
			$sql = "SELECT heading_title, product_sizechart_heading.heading_id
					FROM 
						product_sizechart_heading, product_sizechart_heading_product_map 
					WHERE 
						product_sizechart_heading.sites_site_id = '".$ecom_siteid."' 
						AND product_sizechart_heading.heading_id=product_sizechart_heading_product_map.heading_id 
						AND product_sizechart_heading_product_map.products_product_id = '".$_REQUEST['product_id']."' 
					ORDER BY 
						product_sizechart_heading_product_map.map_order" ;
			$res = $db->query($sql);
			if($db->num_rows($res))
			{ 
					while(list($heading_title, $heading_id) = $db->fetch_array($res))
					{
						$heading[] = $heading_title;
						$charsql = "SELECT size_value 
									 FROM 
										product_sizechart_values 
									 WHERE 
										heading_id='".$heading_id."' 
										AND products_product_id = '".$_REQUEST['product_id']."' 
										AND sites_site_id  ='".$ecom_siteid."' 
									 ORDER BY 
										size_sortorder ";
								   
						$charres = $db->query($charsql);
						if($db->num_rows($charres))
						{  
						    $size_checkval = true;
							while(list($size_value) = $db->fetch_array($charres))
							{
								$sizevalue[$heading_id][] = $size_value;
							}
						}
					 }
					 	
					  $sizechart_heading[] = $heading;
					  $sizechart_heading['size_Availvalues'] = $size_checkval;
					   $cnt =   count($sizevalue);
					   $sql_prods = "SELECT product_sizechart_mainheading 
										FROM 
											products 
										WHERE 
											product_id = '".$_REQUEST['product_id']."'
											AND sites_site_id = $ecom_siteid 
										LIMIT 
											1";
						$ret_prods = $db->query($sql_prods);
						if ($db->num_rows($ret_prods))
						{
							$row_prods 				= $db->fetch_array($ret_prods);
							$sizechartmain_title 	= stripslash_normal($row_prods['product_sizechart_mainheading']); 
						}
						if($sizechartmain_title == '')
						{
							$sizechartmain_title 	= stripslash_normal($Settings_arr['product_sizechart_default_mainheading']);
						}
							
						if(count($sizevalue))
						{
							foreach($sizevalue as $k=>$v)
							{
								$cnt_hd = count($v);
							}
						}
					if($Settings_arr['showsizechart_in_popup']!=1 && $size_checkval == true) // if size chart is set to show in current page itself
				 	{	
						$tabs_arr[-3] 			= stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_SIZECHART_HEAD']);
						$tabs_content_arr[-3]	= ' ';
						$tabs_arr_onclick[-3]	= "show_curtab_content('ultab_-3','".$docroot."',".$prodid.",'".$loading_gif."')";		 
					}
				 
			}
		if($writereview_show == 2 or $readreview_show == 2)
			{
				
				$sql_prodreview	= "SELECT review_id,review_author,review_rating,review_details,review_date  
									FROM  
										 product_reviews 
									WHERE  
										sites_site_id = $ecom_siteid
										AND products_product_id  =  ".$_REQUEST['product_id']."
										AND review_status = 'APPROVED'  
										AND review_hide=0 
									LIMIT 5";
				$ret_prodreview = $db->query($sql_prodreview);
				if($db->num_rows($ret_prodreview))
				{ 
					$review_content = '<div class="review_write">'.$HTML_writerev.'</div>';
					$review_content .= '<div class="review_avg">Average Score: '.$row_prod['product_averagerating'].' / 5</div><br/>';
					while ($row_prodreview = $db->fetch_array($ret_prodreview))
					{
						$review_stars = '';
						for($i=1;$i<=$row_prodreview['review_rating'];$i++)
						{
							$review_stars .='<img src="'.url_site_image('star-green.gif',1).'"  />';
						}
						$review_first 	= stripslashes($row_prodreview['review_details']);
						$review_author 	= '<div class="review_auth_name">'.stripslashes($row_prodreview['review_author']).' ('.$row_prodreview['review_date'].')'.'</div>';
						$review_content .= '<div class="starBlock">
											<div class="ratingStars">'.
											$review_stars.'
											</div>
											</div>'.$review_first.'<br/>'. $review_author.'<br/>';
				  
					}	
					$tabs_arr[-4] 			= 'Reviews ';//stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_SIZECHART_HEAD']);
					$tabs_content_arr[-4]	= $review_content;
					$tabs_arr_onclick[-4]	= "show_curtab_content('ultab_-4','".$docroot."',".$prodid.",'".$loading_gif."')";
				}
				else
				{
					$review_stars = '';	
					$review_content = '<div class="review_write">'.$HTML_writerev.'</div>';

					$review_content .= '<div class="no_review">'.stripslash_normal($Captions_arr['PRODUCT_REVIEWS']['NO_PRODUCT_DETREVIEW_FOUND']).'</div>';

					
					$tabs_arr[-4] 			= 'Reviews ';//stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_SIZECHART_HEAD']);
					$tabs_content_arr[-4]	= $review_content;
					$tabs_arr_onclick[-4]	= "show_curtab_content('ultab_-4','".$docroot."',".$prodid.",'".$loading_gif."')";	
				}
					/*$tabs_arr[-4] .="<a href='#' id='review'></a>"; 
					 */
			}
			//if($Settings_arr['show_downloads_newrow']!=1)
			{
				 $sql_attach = "SELECT attachment_id  
								FROM 
									product_attachments 
								WHERE 
									products_product_id = ".$_REQUEST['product_id']." 
									AND attachment_hide=0  
								LIMIT 1";
				$ret_attach = $db->query($sql_attach);
				if($db->num_rows($ret_attach))
				{
					$tabs_arr[-5] 			= 'Downloads';//stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_SIZECHART_HEAD']);
					$tabs_content_arr[-5]	= 'downloads';
					$tabs_arr_onclick[-5]	= "show_curtab_content('ultab_-5','".$docroot."',".$prodid.",'".$loading_gif."')"; 
				}	
			}
	?>
	
	
	<ul class="nav nav-tabs">
		<?php
		$cnt_tb = 0 ;
		foreach($tabs_arr as $k=>$v)
		{
			if($cnt_tb == 0)
			{
				$start_cls ='class="active"';
			}
			else
			{
				$start_cls ='';
			}
			$cls_clsh = 'data-toggle="tab"';
		?>
				<li <?php echo $start_cls?> ><a <?php echo $cls_clsh;?> href="#tab<?php echo $cnt_tb;?>"><?php echo $v;?><span class="caret" style="display:none"></span></a></li>

		<?php
		$cnt_tb++;
		}
		?>
	</ul>
	 <div class="tab-content">   
        <?php
		$cnt_tbcnt = 0 ;
		foreach($tabs_content_arr as $k=>$v)
		{
			if($cnt_tbcnt == 0)
			{
			$cls_clst ='in active';
			}
			else
			{
			$cls_clst ='';
			}
		?>
		<div id="tab<?php echo $cnt_tbcnt?>" class="tab-pane fade <?php echo $cls_clst;?>">
			<?php echo $v; ?>
		</div>	 
    <?php
    $cnt_tbcnt++;
		}
    ?>
     
    </div>
</div>

    
    
    </div>
<div id="propertymenu_mobview">
	<div id="propertymenu">
<ul>
<?php /*<li class="print2"><a href="javascript:window.print();">Print Details</a></li>*/?>

<?php
/*
	$lat = 0;
	 $long = 0 ;
	 $sql_keyword = "SELECT product_keywords FROM products where product_id=".$row_prod['product_id']." AND sites_site_id=$ecom_siteid LIMIT 1";
	$ret_keyword = $db->query($sql_keyword);
	 $db->num_rows($ret_keyword);
	if($db->num_rows($ret_keyword)>0)
	{
		$row_keyword =  $db->fetch_array($ret_keyword);
		$address = urlencode($row_keyword['product_keywords']);
		if($address!='')
		{
		$add_arr = explode(",",$address);
		$to = $add_arr[0];
		$url = "http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$response = curl_exec($ch);
		curl_close($ch);
		$response_a = json_decode($response);
		 $lat = $response_a->results[0]->geometry->location->lat;
		 $long = $response_a->results[0]->geometry->location->lng;
			 if($lat!=0 && $long!=0)
			 {
			 ?>
			<li class="map2"><a href="#mapcode_head">View map</a></li>

			<?php
			}
		}
}
*/
?>

<?php
$HTML_enquiry = $HTML_email = '';
if ($row_prod['product_show_enquirelink']==1)
			{
				$HTML_enquiry = '<li class="basket2"><a href="javascript:void" onclick="show_wait_button(this,\'Please Wait...\');document.frm_proddetails.fpurpose.value=\'Prod_Enquire\';document.frm_proddetails.submit();">Arrange to View</a></li>';			
			}
			//echo $HTML_enquiry;
if($email_show==1)
{
	$HTML_email = '<li class="friend2"><a href="'.url_link('emailafriend'.$_REQUEST['product_id'].'.html',1).'" title="'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_EMAILFRIEND']).'"> Send to Friend</a></li>';
}	
echo $HTML_email;
	$HTML_callback = '<li class="callback2"><a href="'.url_link('callback.html',1).'" onclick="req_callback(this)" title="Request A Call Back"> Request A Call Back</a></li>';
		echo $HTML_callback;

$HTML_faq = '<li class="faq2"><a href="'.$ecom_selfhttp.$ecom_hostname.'/faq.html" title="FAQ"> Got A Question? Visit FAQ</a></li>';
echo $HTML_faq;
			
?>





</ul>
 

</div>
<div class="callwrap_outer">
<div class="callwrap">
<ul>
<li class='fb_call'><a href="https://www.messenger.com/t/unipadlancaster/" target="_blank"><img src='<?php url_site_image('floating_img.svg')?>' style="border:0;width:366px;height:85px;" alt="Speak With Unipad on Facebook Messenger"></a></li>
</ul>
<script type="text/javascript" src="//platform-api.sharethis.com/js/sharethis.js#property=58b812b3facf57001271be36&product=inline-share-buttons"></script>
<div class="social_share_div"><div class="sharethis-inline-share-buttons"></div>
</div>

</div>

</div>
</div>

<?php // ** Check whether any linked products exists for current product
		$sql_linked = "SELECT 
							a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
							a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
							a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
							a.product_total_preorder_allowed,a.product_applytax,product_shortdesc,product_bonuspoints,
							a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,a.product_variablesaddonprice_exists,
							a.product_variablecomboprice_allowed,a.product_variablecombocommon_image_allowed,a.default_comb_id,
							a.price_normalprefix,a.price_normalsuffix, a.price_fromprefix, a.price_fromsuffix,price_specialofferprefix, a.price_specialoffersuffix, 
							a.price_discountprefix, a.price_discountsuffix, a.price_yousaveprefix, a.price_yousavesuffix,a.price_noprice,
							a.product_averagerating,a.product_saleicon_show,a.product_saleicon_text,a.product_newicon_show,a.product_newicon_text,
							a.product_freedelivery                    
						FROM 
							products a,product_linkedproducts b 
						WHERE 
							b.link_parent_id=".$_REQUEST['product_id']." 
						AND (b.show_in='P' OR b.show_in='CP') 	
						AND a.sites_site_id=$ecom_siteid 
						AND a.product_id = b.link_product_id 
						AND a.product_hide = 'N' 
						AND b.link_hide=0
						ORDER BY 
							b.link_order";
		$ret_linked = $db->query($sql_linked);
		if ($db->num_rows($ret_linked))
		{
			$this->Show_Linked_Product($ret_linked);
		}?> 
    
			<?php
		}
		
function show_summary($mod="summary",$prod_id,$row_prod = array())
{		
		global $db,$ecom_siteid,$ecom_hostname,$ecom_themename,$Settings_arr,$Captions_arr;	
			$addto_cart_withajax =  1;
			$cust_id 	= get_session_var("ecom_login_customer");

			$tabs_arr			= $tabs_cont_arr	= array();
			$docroot			= SITE_URL;
			$prodid				= $prod_id;
			$loading_gif		= url_site_image('loading.gif',1);
			if($row_prod['product_longdesc'])
				$tabs_content_arr[0]	= stripslashes($row_prod['product_longdesc']);
			elseif ($row_prod['product_shortdesc'])
				$tabs_content_arr[0]	= stripslashes($row_prod['product_shortdesc']);
			if (count($tabs_content_arr))
			{
				$tabs_arr 			= array (0=>'Overview');
				$tabs_arr_onclick[0]= "show_curtab_content('ultab_0','".$docroot."',".$prodid.",'".$loading_gif."')";
			}			
			$size_checkval = false;
			// Check whether size chart details exists for current product
			$sql = "SELECT heading_title, product_sizechart_heading.heading_id
					FROM 
						product_sizechart_heading, product_sizechart_heading_product_map 
					WHERE 
						product_sizechart_heading.sites_site_id = '".$ecom_siteid."' 
						AND product_sizechart_heading.heading_id=product_sizechart_heading_product_map.heading_id 
						AND product_sizechart_heading_product_map.products_product_id = '".$prod_id."' 
					ORDER BY 
						product_sizechart_heading_product_map.map_order" ;
			$res = $db->query($sql);
			if($db->num_rows($res))
			{ 
					while(list($heading_title, $heading_id) = $db->fetch_array($res))
					{
						$heading[] = $heading_title;
						$charsql = "SELECT size_value 
									 FROM 
										product_sizechart_values 
									 WHERE 
										heading_id='".$heading_id."' 
										AND products_product_id = '".$prod_id."' 
										AND sites_site_id  ='".$ecom_siteid."' 
									 ORDER BY 
										size_sortorder ";
								   
						$charres = $db->query($charsql);
						if($db->num_rows($charres))
						{  
						    $size_checkval = true;
							while(list($size_value) = $db->fetch_array($charres))
							{
								$sizevalue[$heading_id][] = $size_value;
							}
						}
					 }
					 	
					  $sizechart_heading[] = $heading;
					  $sizechart_heading['size_Availvalues'] = $size_checkval;
					   $cnt =   count($sizevalue);
					   $sql_prods = "SELECT product_sizechart_mainheading 
										FROM 
											products 
										WHERE 
											product_id = '".$prod_id."'
											AND sites_site_id = $ecom_siteid 
										LIMIT 
											1";
						$ret_prods = $db->query($sql_prods);
						if ($db->num_rows($ret_prods))
						{
							$row_prods 				= $db->fetch_array($ret_prods);
							$sizechartmain_title 	= stripslash_normal($row_prods['product_sizechart_mainheading']); 
						}
						if($sizechartmain_title == '')
						{
							$sizechartmain_title 	= stripslash_normal($Settings_arr['product_sizechart_default_mainheading']);
						}
							
						if(count($sizevalue))
						{
							foreach($sizevalue as $k=>$v)
							{
								$cnt_hd = count($v);
							}
						}
					if($Settings_arr['showsizechart_in_popup']!=1 && $size_checkval == true) // if size chart is set to show in current page itself
				 	{	
						$tabs_arr[-3] 			= stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_SIZECHART_HEAD']);
						$tabs_content_arr[-3]	= ' ';
						$tabs_arr_onclick[-3]	= "show_curtab_content('ultab_-3','".$docroot."',".$prodid.",'".$loading_gif."')";		 
					}
				 
			}
			if($writereview_show == 1 or $readreview_show == 1)
			{
				$sql_prodreview	= "SELECT review_id
										review_author,review_rating,review_details 
									FROM  
										 product_reviews 
									WHERE  
										sites_site_id = $ecom_siteid
										AND products_product_id  =  ".$prod_id."
										AND review_status = 'APPROVED'  
										AND review_hide=0 
									LIMIT 1";
				$ret_prodreview = $db->query($sql_prodreview);
				if($db->num_rows($ret_prodreview))
				{
					for($i=1;$i<=$row_prod['product_averagerating'];$i++)
					{
						$review_stars .='<img src="'.url_site_image('star-greenA.gif',1).'"  />';
					}
					$tabs_arr[-4] 			= 'Reviews '.$review_stars;//stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_SIZECHART_HEAD']);
					$tabs_content_arr[-4]	= 'reviews';
					//$tabs_arr_onclick[-4]	= "show_curtab_content('ultab_-4','".$docroot."',".$prodid.",'".$loading_gif."')";
				}
			}
			if($Settings_arr['show_downloads_newrow']!=1)
			{
				$sql_attach = "SELECT attachment_id  
								FROM 
									product_attachments 
								WHERE 
									products_product_id = ".$prod_id." 
									AND attachment_hide=0  
								LIMIT 1";
				$ret_attach = $db->query($sql_attach);
				if($db->num_rows($ret_attach))
				{
					$tabs_arr[-5] 			= 'Downloads';//stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_SIZECHART_HEAD']);
					$tabs_content_arr[-5]	= 'downloads';
					$tabs_arr_onclick[-5]	= "show_curtab_content('ultab_-5','".$docroot."',".$prodid.",'".$loading_gif."')"; 
				}	
			}
			 //$label_val = $this->show_ProductLabels($prodid); 

			if (trim($label_val)!='')
			{
				$tabs_arr[-1] 			= stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_KEY_FEATURES']);
				$tabs_content_arr[-1]	= $label_val;
				$tabs_arr_onclick[-1]	= "show_curtab_content('ultab_-1','".$docroot."',".$prodid.",'".$loading_gif."')";
			}
			
			?>
 <table border = "0" cellspacing="0" cellpadding="0">			
			<?php
  if($mod == 'summary')
  {
	 
				$HTML_friend = '<div class="firend2"><a href="'.url_link('emailafriend'.$_REQUEST['product_id'].'.html',1).'" title="'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_EMAILFRIEND']).'"> Send to Friend</a></div>';
					
			if ($row_prod['product_show_enquirelink']==1)
			{ 
				/*if($addto_cart_withajax == 1)
									{ 
											$HTML_enquiry = '<div class="enquiry_div"><a href="#cart_details_ajaxholderPop"><div class="enquiry_div_in" title="'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_ENQUIRY']).'" alt="'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_ENQUIRY']).'" onclick="quick_view_prod(\'frm_proddetails\',\'Prod_Enquire\')">'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_ENQUIRY']).'</div></a></div>';			

									}
									else
									*/ 
									{	
														$HTML_enquiry = '<div class="enq2"><a href="javascript:void" onclick="show_wait_button(this,\'Please Wait...\');document.frm_proddetails.fpurpose.value=\'Prod_Enquire\';document.frm_proddetails.submit();">Arrange to View</a></div>';			

										   // $HTML_enquiry = '<div class="enquiry_div"><div class="enquiry_div_in" title="'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_ENQUIRY']).'" alt="'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_ENQUIRY']).'" onclick="show_wait_button(this,\'Please Wait...\');document.frm_proddetails.fpurpose.value=\'Prod_Enquire\';document.frm_proddetails.submit();">'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_ENQUIRY']).'</div></div>';			
										
									}
			}
	
      //$this->show_ProductVariables($row_prod,'',$sizechart_heading);
      ?>
     <tr id="show_ajax_trbutton"><td class="det_by">
 <?php $this->show_buttons($row_prod,$HTML_enquiry,$HTML_friend);?>
		</td></tr> 
		<?php
  }
 
  elseif($mod == 'show_img')
  {
  ?>
      <tr>
        <td class="td"><div id="mainimage_holder"><?php $return_arr = $this->Show_Image_Normal($row_prod,'main_tab',true); 
		   $this->show_more_images($row_prod,$return_arr['exclude_tabid'],$return_arr['exclude_prodid']);?></div>
        </td>
      </tr>
  <?php
  }
  elseif($mod == 'show_desc')
  {
  ?>
   <tr id="tr_desc">
    	 <td class="tda_desc">
         <?php 
		 echo   $tabs_content_arr[0];
		 ?>
         </td>
         </tr>
         <?php 
  }
    ?>
      </table> 
      <?php
}
function Show_Image_Normal($row_prod,$mod='',$just_return_id=false)
{
	global $db,$ecom_siteid,$ecom_hostname,$ecom_themename;	
	global $Captions_arr;
	  $show_normalimage = false;
	  if ($_REQUEST['prod_curtab'] and $row_prod['product_variablecombocommon_image_allowed']=='N')
	  {
		if ($_REQUEST['prodimgdet'])	
			$showonly = $_REQUEST['prodimgdet'];
		else
			$showonly = 0;
		
		
		$Captions_arr['COMMON'] 	= getCaptions('COMMON');	
		// Calling the function to get the type of image to shown for current 
		
		//$pass_type = 'image_iconpath';
		//$pass_type = 'image_thumbpath';
		$pass_type = 'image_bigpath';
		//echo $pass_type;echo "<br>";
		// Calling the function to get the image to be shown
		/*$tabimg_arr = get_imagelist('prodtab',$_REQUEST['prod_curtab'],$pass_type,0,$showonly,1);
		if(count($tabimg_arr))
		{
			$exclude_tabid = $tabimg_arr[0]['image_id']; 		// exclude id in case of multi images for products
			if($just_return_id!=true)
			{
			?>
					
			
				<div class="grid images_3_of_2" >
				<div class="zoom-wrapper">
					
					
				<div class="zoom-left">
					<div id="loading_prodeimg_div" style="display:none"><img src="<?php echo url_site_image('spinner_load.gif',1)?>"></div>
				<img style="border:1px solid #e8e8e6;" id="zoom_09" src="<?php url_root_image($tabimg_arr[0]['image_bigpath'])?>" data-zom="<?php url_root_image($tabimg_arr[0]['image_extralargepath'])?>" data-curidx="0" width="411">
				<?php		  
				// Showing additional images
				$this->show_more_images($row_prod,$exclude_tabid,$exclude_prodid);
				?>
				</div>
				</div>
				<div class="clearfix"></div>		
				</div>
			<?php
			}
			$show_noimage 	= false;
		}
		else*/
			$show_normalimage = true;
	  }
	  else
		$show_normalimage = true;
		
		if ($show_normalimage)
		{
				 
			if ($_REQUEST['prodimgdet'])	
				$showonly = $_REQUEST['prodimgdet'];
			else
				$showonly = 0;
			// Calling the function to get the type of image to shown for current 
			
			//$pass_type = 'image_iconpath';
			//$pass_type = 'image_thumbpath';
			$pass_type = 'image_bigpath';
			//echo $pass_type;echo "<br>";
			// Calling the function to get the image to be shown
			if($row_prod['product_variablecombocommon_image_allowed']=='Y')
				$prodimg_arr = get_imagelist_combination($row_prod['default_comb_id'],$pass_type,0,1);
			else
				$prodimg_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,0,$showonly,1);
			if(count($prodimg_arr))
			{
				$exclude_prodid = $prodimg_arr[0]['image_id']; 		// exclude id in case of multi images for products
				//print_r($row_prod);
				if($just_return_id!=true)
				{
?>
					<div class="grid images_3_of_2" id="detpage_mainimg">

					<div class="expand" id="zoomericon"></div>

					<div class="zoom-wrapper">
					<?php
					$sql_pp = "SELECT product_actualstock FROM products WHERE product_id =".$row_prod['product_id']." LIMIT 1";
			$ret_pp = $db->query($sql_pp);
			$row_pp = $db->fetch_array($ret_pp);
			$enquire_confirm = 0;
											if($row_pp['product_actualstock']==0)
											{
												$enquire_confirm = 1;
										?>
												<div class="nowlet_cls_inner_new" id="det_nowlet"><img src="<?php echo url_site_image('nowLet.svg',1) ?>" alt="Now Let"></div>
										<?php		
											}
											if($row_pp['product_actualstock']>0)
											{
													$availability_msg = '<span class="red_available_new" id="det_availability">'.$Captions_arr['COMMON']['PRODDET_AVAIL_1_YEAR'].'</span>';
											}
											else
											{	
													$availability_msg = '<span class="red_available_new" id="det_availability">'.$Captions_arr['COMMON']['PRODDET_AVAIL_2_YEAR'].'</span>';
											}		
												echo $availability_msg;
										?>		
					<div class="zoom-left">
						
					<img style="border:1px solid #e8e8e6;" id="zoom_09" src="<?php url_root_image($prodimg_arr[0]['image_bigpath'])?>" data-zom="<?php url_root_image($prodimg_arr[0]['image_extralargepath'])?>" width="411"  alt="<?php echo $row_prod['product_name']?>" data-curidx="0">
					<?php 
							  
					// Showing additional images
					$this->show_more_images($row_prod,$exclude_tabid,$exclude_prodid);
					?>
					</div>




					</div>
					<div class="clearfix"></div>		
					</div>
				
				<?php
				}
				$show_noimage 	= false;
			}
			else
			{	
				$pass_type = 'image_bigpath';
				// calling the function to get the default no image 
				$no_img = get_noimage('prod','big'); 
				if ($no_img)
				{
					if($just_return_id!=true)
					{
						show_image($no_img,$row_prod['product_name'],$row_prod['product_name']);
					}	
				}	
			}
		}
		$ret_arr['exclude_tabid']		= $exclude_tabid;
		$ret_arr['exclude_prodid'] 		= $exclude_prodid;
		return $ret_arr;
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
			

			
			<?php 
					if (count($prodimg_arr)>1)
					{
					?>	
						<div id="mainimg_tagline_div">
							<div id="mainslideshow_div">Start Slideshow</div>
							<div id="mainnavigation_div">
								<div id="mainimg_prev" class="glyphicon glyphicon-arrow-left"></div> 
								<div id="mainimg_caption">1 of <?php echo count($prodimg_arr);?></div> 
								<div id="mainimg_next"  class="glyphicon glyphicon-arrow-right"></div>
							</div>
						</div>
					<?php
				}
					?>
				<div id="gallery_09" class="more_images_wrapper" >
					
					<div id="proddet_Modal" class="modalimgpop">
					<div id="proddet_loadinghold_div"></div>	
					<span class="close_det" id="img_modal_close_det">&times;</span>
						<div class="customNavigation_zoom">
						<a class="btn prevab glyphicon glyphicon-arrow-left btn btn-info" id="modal_prevbtn"></a>
						<a class="btn nextab glyphicon glyphicon-arrow-right btn btn-info" id="modal_nextbtn"></a>
						</div>
					<div id="img_modal_caption_det"></div>
					
					
						
					<div id="img_rot_container" style="width:100%;position:absolute;overflow: auto;">
					<img class="modal-content_det" id="img01" src="loading.gif" data-curidx="0" alt="loading ...">
					</div>
					</div>	
					
					<div id="content-1" class="content" style="display:none !important;">
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
				</div>
				<script src="<?php echo $ecom_selfhttp.$ecom_hostname?>/images/<?php echo $ecom_hostname?>/scripts/jquery.mThumbnailScroller.js"></script>
			<script type="text/javascript">
			$(window).load(function(){
				
				
				
			});
			
			
			
			var show_maxelm;
			var show_curindx;
			var main_myInterval;
			
			  $( window ).resize(function() {
				  
				<?php /* for ipad */?> 
				if($(window).width()==768)
				{
					$("#det_availability").css({left: '300px', position:'absolute'});
					$("#det_nowlet").css({left: '328px', position:'absolute'});
				}
				if($(window).width()==1024)
				{
					$("#det_availability").css({left: '250px', position:'absolute'});
					$("#det_nowlet").css({left: '280px', position:'absolute'});
				}
				<?php /* for iphone*/?>
				if($(window).width()==375)
				{
					$("#det_availability").css({left: '100px', position:'absolute'});
					$("#det_nowlet").css({left: '130px', position:'absolute'});
				}
				if($(window).width()==667)
				{
					$("#det_availability").css({left: '250px', position:'absolute'});
					$("#det_nowlet").css({left: '280px', position:'absolute'});
				}
			});	
			
			
			
			$(document).ready(function() {
				
				<?php /* for ipad */?>
				if($(window).width()==768)
				{
					$("#det_availability").css({left: '300px', position:'absolute'});
					$("#det_nowlet").css({left: '328px', position:'absolute'});
				}
				if($(window).width()==1024)
				{
					$("#det_availability").css({left: '250px', position:'absolute'});
					$("#det_nowlet").css({left: '280px', position:'absolute'});
				}
				
				<?php /* for iphone*/?>
				if($(window).width()==375)
				{
					$("#det_availability").css({left: '100px', position:'absolute'});
					$("#det_nowlet").css({left: '130px', position:'absolute'});
				}
				if($(window).width()==667)
				{
					$("#det_availability").css({left: '250px', position:'absolute'});
					$("#det_nowlet").css({left: '280px', position:'absolute'});
				}
				
				$("#content-1").show();
				show_curindx = 0;
				show_curindx_main = 0;
				show_maxelm = '<?php echo ($ind-1) ?>';
				
				<?php /* When clicked on any more image */ ?>
				$('#content-1 li img').click(function(){
					stop_mainslideshow();
					$('#zoom_09').animate({opacity:.5});
					$('#zoom_09').attr('data-zom',$(this).attr('data-zom'));
					$('#zoom_09').attr('data-curidx',$(this).attr('data-curidx'));
					$('#zoom_09').attr('src',$(this).attr('data-big'));
					<?php /*$('#zoom_09').animate({opacity:1});*/?>
					show_curindx = parseInt($(this).attr('data-curidx'));
					$('#mainimg_caption').text((parseInt($(this).attr('data-curidx'))+1) +' of '+ (parseInt(show_maxelm)+1));
					/*$('html, body').animate({scrollTop: '0px'}, 600);*/
				});
				
				$('#zoom_09').load(function(){
					$('#zoom_09').animate({opacity:1});
				});	
				
				
				<?php /* Fadeout more image on hover */ ?>
				$('#content-1 li img').hover(function(){
					$(this).animate({'opacity':'0.5'}, 100);
				});
				
				<?php /* Fadein more image on mouseout */ ?>
				$('#content-1 li img').mouseout(function(){
					$(this).animate({'opacity':'1'}, 100);
				});

				<?php /* Showing the image in zoom modal window */ ?>
				$('#zoom_09').click(function(){
					$('html, body').animate({scrollTop: '0px'}, 300);
					$('#img01').attr('src','');
					$('#img_rot_container').css('left',0);
					//$('#proddet_loadinghold_div').css('background-image', 'url("<?php echo url_site_image('loading.gif',1)?>")').css('background-repeat', 'no-repeat').css('background-position','top center');
					$('#img01').attr('src',$(this).attr('data-zom'));
					$('#img01').attr('data-curidx',$(this).attr('data-curidx'));
					show_curindx = parseInt($(this).attr('data-curidx'));
					$('#img_modal_caption_det').text('Image '+(show_curindx+1)+' of '+ (parseInt(show_maxelm)+1));
					$('#proddet_Modal').fadeIn();
					stop_mainslideshow();
				});
				
				$('#zoomericon').click(function(){
					$('html, body').animate({scrollTop: '0px'}, 300);
					$('#img01').attr('src','');
					$('#img_rot_container').css('left',0);
					<?php /*$('#proddet_loadinghold_div').css('background-image', 'url("<?php echo url_site_image('loading.gif',1)?>")').css('background-repeat', 'no-repeat').css('background-position','top center'); */ ?>
					$('#img01').attr('src',$('#zoom_09').attr('data-zom'));
					$('#img01').attr('data-curidx',$('#zoom_09').attr('data-curidx'));
					show_curindx = parseInt($('#zoom_09').attr('data-curidx'));
					$('#img_modal_caption_det').text('Image '+(show_curindx+1)+' of '+ (parseInt(show_maxelm)+1));
					$('#proddet_Modal').fadeIn();
					stop_mainslideshow();
				});	
				
				<?php /* This event triggers when the image is fullyloaded in #img01 */ ?>
				$('#img01').load(function(){
					$('#proddet_Modal').css('background-image', 'none');
				});	
		
				<?php /* Takes care of the close button in modal window */ ?>
				$('#img_modal_close_det').click (function (){
					$('#proddet_Modal').fadeOut();
				});
				
				<?php /* Tracking click event of modal window to decide whether to close it or go to next or prev image */ ?>	
				$('#proddet_Modal').click(function(e){
					/* if (!$('#img01').is(':hover') && !$('#img_modal_caption_det').is(':hover') && !$('.prevab').is(':hover') && !$('.nextab').is(':hover')) 
					 {
						$('#proddet_Modal').fadeOut();
					 }
					 else
					 {
						 var pWidth = $('#img01').innerWidth(); //use .outerWidth() if you want borders
						   var pOffset = $('#img01').offset(); 
						   var x = e.pageX - pOffset.left;
							if(250 > x)
							{	
								mapzoomimage('left');
							}	
							else if(x>(pWidth-250))
							{
								mapzoomimage('right');
							}
					 }*/
				});
				
				$('#img01').mousemove(function(e){
						var pWidth = $('#img01').innerWidth(); //use .outerWidth() if you want borders
						   var pOffset = $('#img01').offset(); 
						   var x = e.pageX - pOffset.left;
						   if(250 > x)
							{	
								//$(this).css('cursor','pointer');
							}	
							else if(x>(pWidth-250))
							{
								//$(this).css('cursor','pointer');
							}
							else
							{
								//$(this).css('cursor','default');
							}
				});
				
			
				<?php /* To hide modal window on esc key */ ?>
				$(document).keypress(function(e) { 
					if (e.keyCode == 27) { 
						$("#proddet_Modal").fadeOut(500);
					}
					else if (e.keyCode == 37) { 
						mapzoomimage('left');
					}
					else if (e.keyCode == 39) { 
						mapzoomimage('right');
					} 
				});
				
				/* Functions to handle the navigation in product details page */
				
				$('#mainimg_next').click(function(){
					stop_mainslideshow();
					mapzoomimage_main('right',0);
				});
				
				$('#mainimg_prev').click(function(){
					stop_mainslideshow();
					mapzoomimage_main('left',0);
				});
				
				$('#mainslideshow_div').click(function(){
					slideshow_main($(this).text());
				});
				
				
				$('#modal_prevbtn').click(function(){
					mapzoomimage('left');;
				});
				
				$('#modal_nextbtn').click(function(){
					mapzoomimage('right');
				});
					
				
				$("#content-1").mThumbnailScroller({
					type:"click-50",
					theme:"buttons-out",
					easing: "easeOutSmooth"
				});

			});	
			
			function slideshow_main(txt)
			{
				if(txt=='Start Slideshow')
					{
						$('html, body').animate({scrollTop: '0px'}, 300);
						show_curindx = $('#zoom_09').attr('data-curidx');
						$('#mainslideshow_div').text('Stop Slideshow');
						main_myInterval = setInterval(function(){
							mapzoomimage_main('right',0);
						}
							,7000);
					}
					else
					{
						stop_mainslideshow();
					}
			}
			
			function stop_mainslideshow()
			{
				$('#mainslideshow_div').text('Start Slideshow');
				clearInterval(main_myInterval);
			}
			
			function mapzoomimage_main(navloc,imagesetonly)
			{
				var c_inx = parseInt(show_curindx);
				if(imagesetonly==0)
				{
					var ob_inx = -1;
					switch (navloc)
					{
						case 'left':
							if(c_inx>0)
							{
								ob_inx = c_inx - 1;
							}
							else
							{
								ob_inx = show_maxelm;
							}
						break;
						case 'right':
							if(c_inx<show_maxelm)
							{
								ob_inx = c_inx + 1;
							}
							else
							{
								ob_inx = 0;
							}
						break;
						case 'swiperight':
							if(c_inx>0)
							{
								ob_inx = c_inx - 1;
							}
							else
							{
								ob_inx = show_maxelm;
							}
						break;
						case 'swipeleft':
							if(c_inx<show_maxelm)
							{
								ob_inx = c_inx + 1;
							}
							else
							{
								ob_inx = 0;
							}
						break;
					};
				}
				else
				{
					ob_inx = c_inx;
				}	
				ob_inx=parseInt(ob_inx);
				if(ob_inx!=-1)
				{
					curobjs = $('img[data-curidx="'+ob_inx+'"]');
					$('#zoom_09').animate({opacity:.3},'300',function(){
					$('#zoom_09').attr('data-curidx',parseInt(ob_inx));
					$('#zoom_09').attr('data-zom',curobjs.attr('data-zom'));
					});
					$('#zoom_09').attr('data-curidx',curobjs.attr('data-curidx'));
					$('#zoom_09').attr('src',curobjs.attr('data-big')).load(function(){
						
						$('#mainimg_caption').text((show_curindx+1)+' of '+ (parseInt(show_maxelm)+1));
						$('#zoom_09').animate({opacity:1});
					});
					
					
					<?php /* $('#mainimage_holder .owl-wrapper').trigger('owl.goTo', ob_inx);*/ ?>
					show_curindx = ob_inx;
				}
			}
			
			function mapzoomimage(navloc)
			{
				
				var c_inx = parseInt(show_curindx);
				var ob_inx = -1;
				switch (navloc)
				{
					case 'left':
						if(c_inx>0)
						{
							ob_inx = c_inx - 1;
						}
						else
						{
							ob_inx = show_maxelm;
						}
					break;
					case 'right':
						if(c_inx<show_maxelm)
						{
							ob_inx = c_inx + 1;
						}
						else
						{
							ob_inx = 0;
						}
					break;
					case 'swiperight':
						if(c_inx>0)
						{
							ob_inx = c_inx - 1;
						}
						else
						{
							ob_inx = show_maxelm;
						}
					break;
					case 'swipeleft':
						if(c_inx<show_maxelm)
						{
							ob_inx = c_inx + 1;
						}
						else
						{
							ob_inx = 0;
						}
					break;
				};
				ob_inx=parseInt(ob_inx);
				
				mapzoomimage_main(navloc,1);
				if(ob_inx!=-1)
				{
					curobjs = $('img[data-curidx="'+ob_inx+'"]');
					$('#img01').attr('data-curidx',parseInt(ob_inx));
					$('#img_modal_caption_det').html('<img alt="Loading Please wait..." src="<?php echo url_site_image('loading.gif',1)?>"/>');
					
					var leftval = 900;
					var dur = 400;
					if(navloc=='right')
					{
						/*$('#img_rot_container').animate({'opacity':'.3'}, 100);
						$('#img_rot_container').animate({left: "-="+leftval},dur,"swing",function(){
							$('#img_rot_container').css('left',leftval);
							$('#img01').attr('src',curobjs.attr('data-zom'));
							$('#img_rot_container').animate({'opacity':'1'}, 100,function(){
							$('#img_rot_container').animate({left: "-="+leftval},dur,"swing",function(){$('#img_modal_caption_det').text('Image '+ (ob_inx+1) +' of '+ (parseInt(show_maxelm)+1));});});
						});*/
						$('#img_rot_container').animate({'opacity':'.3'}, 100,function(){
							$('#img01').attr('src',curobjs.attr('data-zom')).load(function(){
								$('#img_modal_caption_det').text('Image '+ (ob_inx+1) +' of '+ (parseInt(show_maxelm)+1));
								$('#img_rot_container').animate({'opacity':'1'}, 100);
							});
						});
						
						
						
					}
					else if(navloc=='left')
					{
						/*$('#img_rot_container').animate({'opacity':'.3'}, 100);
						$('#img_rot_container').animate({left: "+="+leftval},dur,"swing",function(){
							$('#img_rot_container').css('left',-leftval);
							$('#img01').attr('src',curobjs.attr('data-zom'));
							$('#img_rot_container').animate({'opacity':'1'}, 100,function(){
							$('#img_rot_container').animate({left: "+="+leftval},dur,"swing",function(){$('#img_modal_caption_det').text('Image '+ (ob_inx+1) +' of '+ (parseInt(show_maxelm)+1));});});
							
						});*/
						$('#img_rot_container').animate({'opacity':'.3'}, 100,function(){
							$('#img01').attr('src',curobjs.attr('data-zom')).load(function(){
								$('#img_modal_caption_det').text('Image '+ (ob_inx+1) +' of '+ (parseInt(show_maxelm)+1));
								$('#img_rot_container').animate({'opacity':'1'}, 100);
							});
						});
					}
					else if(navloc=='swipeleft')
					{
						/*$('#img_rot_container').animate({'opacity':'.3'}, 100);
						$('#img_rot_container').animate({left: "-="+leftval},dur,"swing",function(){
							$('#img_rot_container').css('left',leftval);
							$('#img01').attr('src',curobjs.attr('data-zom'));
							$('#img_rot_container').animate({'opacity':'1'}, 100,function(){
							$('#img_rot_container').animate({left: "-="+leftval},dur,"swing",function(){$('#img_modal_caption_det').text('Image '+ (ob_inx+1) +' of '+ (parseInt(show_maxelm)+1));});});
						});*/
						$('#img_rot_container').animate({'opacity':'.3'}, 100,function(){
							$('#img01').attr('src',curobjs.attr('data-zom')).load(function(){
								$('#img_modal_caption_det').text('Image '+ (ob_inx+1) +' of '+ (parseInt(show_maxelm)+1));
								$('#img_rot_container').animate({'opacity':'1'}, 100);
							});
						});
					}
					else if(navloc=='swiperight')
					{
						/*$('#img_rot_container').animate({'opacity':'.3'}, 100);
						$('#img_rot_container').animate({left: "+="+leftval},dur,"swing",function(){
							$('#img_rot_container').css('left',-leftval);
							$('#img01').attr('src',curobjs.attr('data-zom'));
							$('#img_rot_container').animate({'opacity':'1'}, 100,function(){
							$('#img_rot_container').animate({left: "+="+leftval},dur,"swing",function(){$('#img_modal_caption_det').text('Image '+ (ob_inx+1) +' of '+ (parseInt(show_maxelm)+1));});});
							
						});*/
						$('#img_rot_container').animate({'opacity':'.3'}, 100,function(){
							$('#img01').attr('src',curobjs.attr('data-zom')).load(function(){
								$('#img_modal_caption_det').text('Image '+ (ob_inx+1) +' of '+ (parseInt(show_maxelm)+1));
								$('#img_rot_container').animate({'opacity':'1'}, 100);
							});
						});
						
					}	
					show_curindx = ob_inx;
				}
			}
			
			
			var touchStartCoords =  {'x':-1, 'y':-1}, // X and Y coordinates on mousedown or touchstart events.
			touchEndCoords = {'x':-1, 'y':-1},// X and Y coordinates on mouseup or touchend events.
			direction = 'undefined',// Swipe direction
			minDistanceXAxis = 10,// Min distance on mousemove or touchmove on the X axis
			maxDistanceYAxis = 20,// Max distance on mousemove or touchmove on the Y axis
			maxAllowedTime = 800,// Max allowed time between swipeStart and swipeEnd
			startTime = 0,// Time on swipeStart
			elapsedTime = 0,// Elapsed time between swipeStart and swipeEnd
			
			
			targetElement1 = document.getElementById('img01');// Element to delegate
			targetElement2 = document.getElementById('zoom_09');// Element to delegate
			
			function swipeStart(e) {

			e = e ? e : window.event;
			e = ('changedTouches' in e)?e.changedTouches[0] : e;
			touchStartCoords = {'x':e.pageX, 'y':e.pageY};
			startTime = new Date().getTime();

			}

			function swipeMove(e){
			e = e ? e : window.event;
			
			}

			function swipeEnd(e) {
				e = e ? e : window.event;
				e = ('changedTouches' in e)?e.changedTouches[0] : e;
				touchEndCoords = {'x':e.pageX - touchStartCoords.x, 'y':e.pageY - touchStartCoords.y};
				elapsedTime = new Date().getTime() - startTime;
				if (elapsedTime <= maxAllowedTime){
					if (Math.abs(touchEndCoords.x) >= minDistanceXAxis && Math.abs(touchEndCoords.y) <= maxDistanceYAxis){
						direction = (touchEndCoords.x < 0)? 'left' : 'right';
						switch(direction){
						case 'left':
						mapzoomimage('swipeleft');
						break;
						case 'right':
						mapzoomimage('swiperight');
						break;
						default:

						break;
						}
					}
				}
			}
			
			function swipeStartmain(e) {

			e = e ? e : window.event;
			e = ('changedTouches' in e)?e.changedTouches[0] : e;
			touchStartCoords = {'x':e.pageX, 'y':e.pageY};
			startTime = new Date().getTime();

			}

			function swipeMovemain(e){
			e = e ? e : window.event;
			
			}

			function swipeEndmain(e) {
				e = e ? e : window.event;
				e = ('changedTouches' in e)?e.changedTouches[0] : e;
				touchEndCoords = {'x':e.pageX - touchStartCoords.x, 'y':e.pageY - touchStartCoords.y};
				elapsedTime = new Date().getTime() - startTime;
				if (elapsedTime <= maxAllowedTime){
					if (Math.abs(touchEndCoords.x) >= minDistanceXAxis && Math.abs(touchEndCoords.y) <= maxDistanceYAxis){
						direction = (touchEndCoords.x < 0)? 'left' : 'right';
						switch(direction){
						case 'left':
						mapzoomimage_main('swipeleft',0);
						break;
						case 'right':
						mapzoomimage_main('swiperight',0);
						break;
						default:

						break;
						}
					}
				}
			}
			
			

			function addMultipleListeners(el, s, fn) {
				var evts = s.split(' ');
				for (var i=0, iLen=evts.length; i<iLen; i++) {
				el.addEventListener(evts[i], fn, false);
				}
			}

			addMultipleListeners(targetElement1, 'touchstart', swipeStart);
			addMultipleListeners(targetElement1, 'touchmove', swipeMove);
			addMultipleListeners(targetElement1, 'touchend', swipeEnd);
			
			addMultipleListeners(targetElement2, 'touchstart', swipeStartmain);
			addMultipleListeners(targetElement2, 'touchmove', swipeMovemain);
			addMultipleListeners(targetElement2, 'touchend', swipeEndmain);
			
			</script>				
			<?php
			
		}
	}
}

function show_ProductVariables($row_prod,$pos='column',$sizechart_heading)
{
	global $db,$ecom_siteid,$Captions_arr,$Settings_arr,$ecom_themename,$ecom_hostname;
	$i = 0;
	// ######################################################
	// Check whether any variables exists for current product
	// ######################################################
	$sql_var = "SELECT var_id,var_name,var_value_exists, var_price,var_value_display_dropdown  
						FROM 
							product_variables 
						WHERE 
							products_product_id = ".$_REQUEST['product_id']." 
							AND var_hide= 0
						ORDER BY 
							var_order";
	$ret_var = $db->query($sql_var);
	$var_cnt = $db->num_rows($ret_var);
	// ##############################################################################
	//  Check whether variable message exists for the product
	// ##############################################################################
	$sql_msg = "SELECT message_id,message_title,message_type 
					FROM 
						product_variable_messages 
					WHERE 
						products_product_id = ".$_REQUEST['product_id']." 
						AND message_hide= 0
					ORDER BY 
						message_order";
	$ret_msg = $db->query($sql_msg);
	// Check whether total number of variables is 1 or more than 1
	if($var_cnt==1)
	{
		$vardisp_type = $row_prod['product_variable_display_type']; // take the display type from settings for current product
	}
	else 
		$vardisp_type = 'ADD'; // if the variable count is > 1 then by default the Add option will be displayed
	
	if ($db->num_rows($ret_var) or $db->num_rows($ret_msg))
	{
  ?>
		<?php
		// Case of variables
		if ($db->num_rows($ret_var) or $db->num_rows($ret_msg))
		{
			while ($row_var = $db->fetch_array($ret_var))
			{
				if ($row_var['var_value_exists']==1)
				{
					// check whether values exists current variable
					$sql_vals = "SELECT var_value_id, var_addprice,var_value,var_colorcode, images_image_id  
									FROM 
										product_variable_data 
									WHERE 
										product_variables_var_id =".$row_var['var_id']." 
									ORDER BY 
										var_order";
					$ret_vals = $db->query($sql_vals);
					if ($db->num_rows($ret_vals))
					{
						$var_Proceed = true;
					}
				}
				else
					$var_Proceed = true;
				if ($var_Proceed)// Show the variable if it is valid to show
				{
					$var_exists = true;
				?>	
				 
                        <?php

						if($row_prod['product_variablecomboprice_allowed']=='Y' and $row_prod['product_variablecombocommon_image_allowed']=='Y')
						{
							$onchange_function      = " onchange ='handle_show_prod_det_bulk_disc(\"price\",\"".SITE_URL."\",\"".$_REQUEST['product_id']."\",\"".url_site_image('loading.gif',1)."\")' ";
                            $onclick_var            = "price";
						}
						elseif($row_prod['product_variablecomboprice_allowed']=='Y' and $row_prod['product_variablecombocommon_image_allowed']=='N')
						{
							$onchange_function      = " onchange ='handle_show_prod_det_bulk_disc(\"price\",\"".SITE_URL."\",\"".$_REQUEST['product_id']."\",\"".url_site_image('loading.gif',1)."\")' ";
                            $onclick_var            = "price";
						}
						elseif($row_prod['product_variablecomboprice_allowed']=='N' and $row_prod['product_variablecombocommon_image_allowed']=='Y')
						{
							$onchange_function      = $onchange_function      = " onchange ='handle_show_prod_det_bulk_disc(\"main_img\",\"".SITE_URL."\",\"".$_REQUEST['product_id']."\",\"".url_site_image('loading.gif',1)."\")' ";
                            $onclick_var            = "main_img";
						}
						else
						{
							$onchange_function      = '';
                            $onclick_var       		= '';
						}
						if ($row_var['var_value_exists']==1)
						{
							$var_disp_type      	= 'DROPDOWN';// Default settings
							if($row_var['var_value_display_dropdown']==1)
								$var_disp_type 		= 'DROPDOWN';
						   	else
								$var_disp_type 		= 'OTHER';
							$color_type				= false;
							if($var_disp_type == 'OTHER')
							{
								if(var_color_display_check($row_var['var_name']))
								{
									$color_type 	= true;  
								}
							}							
							 if($var_disp_type=='DROPDOWN')
							  {
						?>
		
<dd>
						<div class="var"><?php echo stripslash_normal($row_var['var_name'])?> : </div> 
					 <div class="input-box"> <label>
									<select name="var_<?php echo $row_var['var_id']?>" id="var_<?php echo $row_var['var_id']?>" <?php echo $onchange_function?> class="required-entry product-custom-option form-control" >
									<?php 
									while ($row_vals = $db->fetch_array($ret_vals))
									{
									?>
										<option value="<?php echo $row_vals['var_value_id']?>" <?php echo ($_REQUEST['var_'.$row_var['var_id']]==$row_vals['var_value_id'])?'selected':''?>><?php echo stripslash_normal($row_vals['var_value'])?><?php echo Show_Variable_Additional_Price($row_prod,$row_vals['var_addprice'],$vardisp_type)?></option>
									<?php
									}
									?>
									</select>	
									</label>
                    </div>	
                    </dd>					
						<?php
							 }/*
							  else
							  {}*/
						}
						/*
						else
						{
						?>
							<input type="checkbox" name="var_<?php echo $row_var['var_id']?>" id="var_<?php echo $row_var['var_id']?>" value="1" <?php echo ($_REQUEST['var_'.$row_var['var_id']])?'checked="checked"':''?>/><?php echo Show_Variable_Additional_Price($row_prod,$row_var['var_price'],$vardisp_type)?>
						<?php
						}
						*/ 
						?>
					
				<?php
					$i++;
				}
			}
		}
		// ######################################################
		// End of variables section
		// ######################################################
		
		// ##############################################################################
		//  Case of variable messages
		// ##############################################################################
		
		if ($db->num_rows($ret_msg))
		{
			while ($row_msg = $db->fetch_array($ret_msg))
			{
				$var_exists = true;
			?>
				<div class="vara"><?php echo stripslash_normal($row_msg['message_title'])?> : </div>
					<div class="vara"> <label>
						<?php
						if ($row_msg['message_type']=='TXTBX')
						{
						?>
							<input class="form-control" type="text" name="varmsg_<?php echo $row_msg['message_id']?>" id="varmsg_<?php echo $row_msg['message_id']?>" value="<?php echo $_REQUEST['varmsg_'.$row_msg['message_id']]?>" />
						<?php
						}
						else
						{
						?>
							<textarea class="form-control" name="varmsg_<?php echo $row_msg['message_id']?>" id="varmsg_<?php echo $row_msg['message_id']?>" rows="3" cols="25"><?php echo $_REQUEST['varmsg_'.$row_msg['message_id']]?></textarea>
						<?php
						}
						?>
					</label>
                    </div>
			<?php
				$i++;
			}
		?>  
		<?php		
		}
		// ######################################################
		// End of variable messages
		// ######################################################
		?>
		<?php
	}		
		
	return $var_exists;
}
function show_buttons($row_prod)
{
	
	global $Captions_arr,$showqty,$Settings_arr;
	$cust_id 	= get_session_var("ecom_login_customer");
	$showqty	= $Settings_arr['show_qty_box'];// show the qty box
	$cur_qty_caption = ($row_prod['product_det_qty_caption']!='')?stripslash_normal($row_prod['product_det_qty_caption']):stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_QUANTITY']);
	// Get the caption to be show in the button
	$caption_key = show_addtocart($row_prod,array(0),'',true);
	if ($caption_key!='') // Check whether the buynow or preorder button is to be displayed
	{
	?>	
		<div class="deat_pdt_buyB">
		<div class="deat_pdt_buyBinner_book">
	<?php	
	if($showqty==1)// this decision is made in the main shop settings
	{
		if($row_prod['product_det_qty_type']=='NOR')
		{
?>
			<div class="buyBinner_qty_book"><?=$cur_qty_caption?></div>
			<div class='buyBinner_txt'><input type="text" class="det_qty_txt form-control" name="qty"  value="<?php echo ($_REQUEST['qty'])?$_REQUEST['qty']:1?>" maxlength="2" /></div>
<?php
		}
		elseif($row_prod['product_det_qty_type']=='DROP')
		{
			$dropdown_values = explode(',',stripslash_normal($row_prod['product_det_qty_drop_values']));
			if (count($dropdown_values) and stripslash_normal($row_prod['product_det_qty_drop_values'])!='')
			{
			?>
				<div class="buyBinner_qty_book"><?=$cur_qty_caption?></div>

				<div class='buyBinner_txt'>
				<select name="qty" class="form-control">
				<?php 
					$qty_prefix = stripslash_normal($row_prod['product_det_qty_drop_prefix']);
					$qty_suffix = stripslash_normal($row_prod['product_det_qty_drop_suffix']);
					foreach ($dropdown_values as $k=>$v)
					{
						$show_val = trim($v);
						if (is_numeric($show_val))
						{
				?>
						<option value="<?php echo $show_val?>"><?php echo $qty_prefix.' '.$show_val.' '.$qty_suffix?></option>
				<?php
						}		
					}
				?>
				</select>
				</div>
			<?php	
			}				
		}
	}
	
?>
<br/><br/>
<div class="buyBinner_link"><a href="#" class="btn btn-default add-to-cart" onClick="show_wait_button(this,'Please Wait...');document.frm_proddetails.fpurpose.value='Prod_Addcart';document.frm_proddetails.submit();"><i class="fa fa-shopping-cart"></i>Book Now</a></div>
</div>
</div>
<?php
	}	
	return true;
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
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "monthlycleaningservice")

								{	$label_image	=	'icon_cleaning_p.svg';		}
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "broadband")

								{	$label_image	=	'icons_broadband.svg';		}
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "fibrebroadband")

								{	$label_image	=	'feature_fibre_p.svg';		}
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "wirelessinternet")
								{	$label_image	=	'feature_WIFI_p.svg';		}
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "lettingperiod")
								{	$label_image	=	'feature_50_week_p.svg';		}
							
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "sportsgym")
								{	$label_image	=	'feature_Gym_centre_p.svg';		}
							
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "carparking")
								{	$label_image	=	'feature_parking.svg';		}
							
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
function Old_show_ProductLabels($prod_id)
{
	global $db,$ecom_siteid,$Captions_arr,$ecom_hostname;

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
							
							
							$ret_val	.= "<div><img src=".url_site_image($label_image,1)." />
							<span class='span_label_text'>".$row_labels['label_value']."</span>
							</div>";
									





								/*$vals = '';

								if ($row_labels['is_textbox']==1)

									$vals = stripslash_normal($row_labels['label_value']);

								else

								{

									$sql_labelval = "SELECT label_value 

														FROM 

															product_site_labels_values  

														WHERE 

															product_site_labels_label_id=".$row_labels['label_id']." 

															AND label_value_id = ".$row_labels['product_site_labels_values_label_value_id'];

									//echo "<br>".$sql_labelval;

									$ret_labelval = $db->query($sql_labelval);

									if ($db->num_rows($ret_labelval))

									{

										$row_labelval = $db->fetch_array($ret_labelval);

										$vals = stripslash_normal($row_labelval['label_value']);

									}																	

								}

								if ($vals)

								{

									$label_arr[$row_grp['group_name'].'~'.$row_grp['group_name_hide']][] = array('name'=>stripslash_normal($row_labels['label_name']),'val'=>$vals);

								}*/

							}					
							
	
						}

					}

					


				}

			}

		}	

	}

	//if($display_ok==false)
		//$ret_val = '';

	//return $ret_val ;	
	global $ecom_selfhttp;
	?>
	
      		<script src="<?php echo $ecom_selfhttp.$ecom_hostname?>/images/<?php echo $ecom_hostname?>/scripts/jquery.carouFredSel-6.0.4-packed.js" type="text/javascript"></script>
		<div class="details_right">			
		<div id="detail_carousel1">
				<?php echo $ret_val; ?>
			</div>
		</div>
		</div>
		
		<script type="text/javascript">
			$(function() {

				var $c = $('#detail_carousel1'),
					$w = $(window);

				$c.carouFredSel({
					align: false,
					items: 5,
					scroll: {
						items: 1,
						duration: 2000,
						timeoutDuration: 0,
						easing: 'linear',
						pauseOnHover: 'immediate'
					}
				});

			});
		</script>
	<?php


}

// ** Function to show the details of products which are linked with current product.
function Show_Linked_Product($ret_prod)
{
	global $ecom_siteid,$db,$Captions_arr,$Settings_arr,$ecom_hostname;
	global $ecom_selfhttp;
	// Calling the function to get the type of image to shown for current 
	$pass_type = get_default_imagetype('link_prod');
	$prod_compare_enabled = isProductCompareEnabled();	
			$width_one_set 	= 166;
			$min_number_req	= 4;
			$min_width_req 	= $width_one_set * $min_number_req;
			$total_cnt		= $db->num_rows($ret_prod);
			$calc_width		= $total_cnt * $width_one_set;
			if($calc_width < $min_width_req)
				$div_width = $min_width_req;
			else
				$div_width = $calc_width; 
?>
			
			
			    <div id="gallery_091" class="similar_prop_wrap">
					
					<div class="linked_heading">Similar Properties</div>	
					
					<div id="content-2" class="content" style="display:none !important;">
					<ul>
			<?php
			$cnts = $db->num_rows($ret_prod);
			$cnt = 0;
			$cntf = 0;
			while($row_prod = $db->fetch_array($ret_prod))
			{
				?>
				<li><div class="item"><div class="product-grid">
				<?php 
				/*
				?>
				<div class="product-grid-head">

				<?php 
					$rate = $row_prod['product_averagerating'];
					echo $rating = $this->display_rating_responsive($rate,1,$row_prod['product_id']);
					?>
				</div>
				*/ ?> 
				<div class="product-pic">
				<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" <?php //echo getmicrodata_producturl()?> >
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
														for($mi=0;$mi<count($prodmoreimg_arr);$mi++)
														{
															$moreimgarr[]= url_root_image($prodmoreimg_arr[$mi][$pass_type],1);
														}
														$moreimgstr = implode(",",$moreimgarr);
													}
													show_image(url_root_image($img_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name'],'listscrollimg" data-imageid="linked'.$row_prod['product_id'].'" data-tapped="0" data-immore="'.$moreimgstr);
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
											</a>								<p>
				<?php 
				$HTML_title = '<a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">'.stripslash_normal($row_prod['product_name']).'</a> ';
				echo $HTML_title;
				$price_class_arr['ul_class'] 		= 'price-avl';
								$price_class_arr['normal_class'] 	= '';
								$price_class_arr['strike_class'] 	= 'price';
								$price_class_arr['yousave_class'] 	= '';
								$price_class_arr['discount_class'] 	= '';
								//	echo show_Price($row_prod,$price_class_arr,'shelfcenter_1'); price_categorydetails_1_reqbreak
								$price_array =  show_Price($row_prod,$price_class_arr,'cat_detail_1',false,3);

				?>
				</p>
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
								<div class="clear"> </div>
								<?php
								$link 		= url_product($row_prod['product_id'],$row_prod['product_name'],1);
								?>
								<div class="moreinfolist"><a href="<?php echo $link?>" title="<?php echo $row_prod['product_name']?>" class="moreinfolist_a">More Info</a></div>
				</div>
				<div class="product-info">
				<div class="product-info-cust">
									<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">Details</a>
				</div>
				<div class="product-info-price">															<?php $frm_name = uniqid('catdet_'); ?>
								
												<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
												<input type="hidden" name="fpurpose" value="" />
												<input type="hidden" name="qty" value="1" />
												<input type="hidden" name="fproduct_id" value="" />
												<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
												<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
								<?php			$class_arr['ADD_TO_CART']       = '';
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
				</div>
				<div class="clear"> </div>
				</div>
				<div class="more-product-info">
				<span> </span>
				</div>
				</div></div></li>
				<?php

			}
			//$listscroll_obj = '.listscrollimg';
			//$listscroll_delay = 1400;
			//include "listprod_scroller.php";
			?>
			
             </ul>
					</div>
				</div>
				<script src="<?php echo $ecom_selfhttp.$ecom_hostname?>/images/<?php echo $ecom_hostname?>/scripts/jquery.mThumbnailScroller.js"></script>

 

    <script>
    $(document).ready(function() {
     $("#content-2").show();
     $("#content-2").mThumbnailScroller({
					type:"click-50",
					theme:"buttons-out",
					easing: "easeOutSmooth"
				});
    });
    </script>	
			
<?php	
}
function display_rating_responsive($rate,$ret=0,$prod_id=0)
{ 
	global $ecom_siteid,$Settings_arr;
	if($Settings_arr['proddet_showwritereview']==1 or $Settings_arr['proddet_showreadreview']==1)
	{
		$retn ='<div class="container-star"><p>';
		$rate = ceil($rate);
		for ($i=0;$i<$rate;$i++)
		{
					if($ret==0)
						echo '<span class="glyphicon glyphicon-star"></span>'; 
					elseif($ret==1)
						$retn .= '<span class="glyphicon glyphicon-star"></span>';
		}
		if($rate<5)
		{
			$rem = ceil(5-$rate);
			for ($i=0;$i<$rem;$i++)
			{
						if($ret==0)
							echo '<span class="glyphicon glyphicon-star-empty"></span>'; 
						elseif($ret==1)
							$retn .= '<span class="glyphicon glyphicon-star-empty"></span>';    
			}
		}
		if($ecom_siteid==104 or $ecom_siteid==106)
		{  
			global $db;
			$cnt = 0;
		       if($prod_id>0)
		       {
		          $sql_prodreview	= "SELECT count(review_id) as cnt
									FROM  
										 product_reviews 
									WHERE  
										sites_site_id = $ecom_siteid
										AND products_product_id  =  ".$prod_id."
										AND review_status = 'APPROVED'  
										AND review_hide=0 
									LIMIT 10";
				 $ret_prodreview = $db->query($sql_prodreview);
				    if($db->num_rows($ret_prodreview))
					{
						$row_prodreview = $db->fetch_array($ret_prodreview);
				        $cnt = $row_prodreview['cnt']; 
					
					}
					if($cnt>0)
					{
					   $retn .= '<a href="'.url_product($prod_id,'',1).'?prod_curtab=-4#review" title="'.stripslashes($row_prod['product_name']).'"><div class="rev_cnt">	'.$cnt.' Review(s)</div></a>';
					}					
				}
		 }
		 $retn .='</p>
			    </div>';	
			if($ret==1)
				return $retn;
	}
}
// ** Function to show the list of products to be compared with current product.
function Show_Compare_Product($ret_prod)
{
	global $ecom_siteid,$db,$Captions_arr,$Settings_arr,$inlineSiteComponents;
	$Captions_arr['PROD_DETAILS'] 	= getCaptions('PROD_DETAILS');
	
	$HTML_treemenu = ' <div class="tree_menu_con">
						<div class="tree_menu_top"></div>
						<div class="tree_menu_mid">
						<div class="tree_menu_content">
						<ul class="tree_menu">
						<li><a href="'.url_link('',1).'" title="'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a> </li>
						<li>'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_COMPARE_HEAD']).'</li>
						</ul>
						</div>
						</div>
						<div class="tree_menu_bottom"></div>
						</div>';
	$HTML_comptitle = $HTML_maindesc = $HTML_paging = $HTML_showall = '';
	$msg = stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_MAX_COMP_PROD']);
	$msg = str_replace('[prodno]',$Settings_arr['no_of_products_to_compare'],$msg);
	if($msg!='')
	{
		   $HTML_comptitle ='<div class="normal_shlfB_desc_outr">'.$msg.'</div>';
	}
	$HTML_maindesc = ' 	<div class="compare_main_div">
						<div class="compare_back_button">
							<div class="cart_top_links"><div class="cart_shop_cont"><div>
							<input type="button" name="prodet_backprod" value="'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_BACK_TO_DETAIL']).'" class="buttonred_cart" onclick="window.location=\''.url_product($_REQUEST['product_id'],'',1).'\'"/>
							</div>
							</div>
							</div>
						</div>
						<div class="compare_gobutton">
							<div class="cart_top_links"><div class="cart_shop_cont"><div>
						 	<input type="button" name="prodet_comparebutton" value="'.stripslash_normal($Captions_arr['PROD_DETAILS']['COMPARE_PRODUCTS']).'" class="buttonred_cart" onclick="handle_proddet_compare()"/>
						 	</div>
						 	</div>
						 	</div>
						</div>
						</div>';
	?>
	<form method="post" action="" name="frm_proddet_comp" id="frm_proddet_comp" target="_blank">
	<input type="hidden" name="fproduct_id" value="<?php echo $_REQUEST['product_id']?>" />
	<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
	<input type="hidden" name="fpurpose" value="proddet_compare" />
	<input type="hidden" name="detcomp_prods" id="detcomp_prods" value=""/>
	</form>
	<script type="text/javascript">
		function handle_proddet_compare()
		{
			var def_prodid 		= '<?php echo $_REQUEST['product_id']?>';
			var comp_chkbox 	= document.getElementsByTagName('input');
			var checked_comp	= '';
			var totcnt				= 0;
			var maxcnt		= '<?php echo $Settings_arr['no_of_products_to_compare']?>';
			for(i=0;i<comp_chkbox.length;i++)
			{
				if(comp_chkbox[i].name.substr(0,15)=='chkproddet_comp')
				{
					if(comp_chkbox[i].checked)
					{
						if(checked_comp!='')
							checked_comp = checked_comp + ',';
						checked_comp = checked_comp + comp_chkbox[i].value;
						totcnt++;
					}	
				}
			}
			if (checked_comp=='')
				alert('<?php echo stripslash_javascript($Captions_arr['PROD_DETAILS']['PRODDET_COMPARING']);?>'); 
			else if(totcnt>maxcnt) 
				alert('<?php echo stripslash_javascript($Captions_arr['PROD_DETAILS']['PRODDET_SORRY_COMPARING']);?>'+(maxcnt)+' <?php echo stripslash_javascript($Captions_arr['PROD_DETAILS']['PRODDET_SORRY_COMPARING_TIME']);?>'); 
			else
			{
				checked_comp = def_prodid+','+checked_comp;
				document.frm_proddet_comp.detcomp_prods.value = checked_comp;
				document.frm_proddet_comp.submit();
			}
		}
	</script>
	<?=$HTML_treemenu?>
		<div class="normal_shlf_mid_con">
		<div class="normal_shlf_mid_top"></div>
		<div class="normal_shlf_mid_mid">
		<? 
		echo $HTML_comptitle;
		echo $HTML_maindesc;
		echo $HTML_paging;
		$max_col = 3;
		$cur_col = 0;
		$prodcur_arr = array();
		while($row_prod = $db->fetch_array($ret_prod))
		{
		
			$prodcur_arr[] = $row_prod;
			$compare_checked = '';
			if(is_array($_SESSION['compare_products']))
			{
				if(in_array($row_prod['product_id'],$_SESSION['compare_products']))
				{
					$compare_checked = 'checked="checkeed"';
				}
			}
			$HTML_title = $HTML_image = $HTML_desc = '';
			$HTML_sale = $HTML_new = $HTML_compare = $HTML_rating = '';
			$HTML_price = $HTML_bulk= $HTML_bonus = $HTML_compare = $HTML_freedel= $HTML_bonus_bar = '';
				$HTML_title = '<div class="normal_shlfB_pdt_name"><input type="checkbox" name="chkproddet_comp_'.$row_prod['product_id'].'" id="chkproddet_comp_'.$row_prod['product_id'].'" value="'.$row_prod['product_id'].'" '.$compare_checked.'/><a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">'.stripslash_normal($row_prod['product_name']).'</a></div>';
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
				$HTML_desc = stripslash_normal($row_prod['product_shortdesc']);
				$price_class_arr['class_type']          = 'div';
				$price_class_arr['normal_class']        = 'normal_shlfB_pdt_priceA';
				$price_class_arr['strike_class']        = 'normal_shlfB_pdt_priceB';
				$price_class_arr['yousave_class']       = 'normal_shlfB_pdt_priceC';
				$price_class_arr['discount_class']      = 'normal_shlfB_pdt_priceC';
				$HTML_price = show_Price($row_prod,$price_class_arr,'shelfcenter_3');
			            if($cur_col==0)
						{
							echo  '<div class="outer_shlfB_container">';
						}	
						if($cur_col<$max_col-1)
						{
						  $outer_class = 'normal_shlfB_pdt_outr';
						}	
						else
						{
							$outer_class = 'normal_shlfB_pdt_outr_right';
						}
		?>
			<div class="<?=$outer_class?>">
			<div class="normal_shlfB_pdt_mid">
			<?=$HTML_title;?>
			<div class="normal_shlfB_pdt_img_otr">
			<div class="normal_shlfB_pdt_img"><?=$HTML_image?></div>
			</div>
			<div class="normal_shlfB_pdt_price"><?=$HTML_price?></div>
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
		echo $HTML_paging;
		echo $HTML_showall;
		?>
		<div class="normal_shlfA_mid_bottom"></div> 
		</div>   
		</div>	
<?php
}
// ** Function to display the variables. This is written as function to avoid code repetation in various if conditions

/* Function to show the bulk discount*/
function show_BulkDiscounts($row_prod,$var_arr=array())
{
	global $db,$ecom_siteid,$Captions_arr;
	if (count($var_arr)==0 and $row_prod['product_variablecomboprice_allowed']=='Y') // case if variable combination price is allowed and also if var arr is null
	{
		$sql_var = "SELECT var_id,var_name  
						FROM 
							product_variables 
						WHERE 
							products_product_id = ".$row_prod['product_id']." 
							AND var_hide= 0 
							AND var_value_exists = 1 
						ORDER BY 
							var_order";
		$ret_var = $db->query($sql_var);
		if($db->num_rows($ret_var))
		{
			while ($row_var = $db->fetch_array($ret_var))
			{
				$curvar_id= $row_var['var_id'];
				// Get the value id of first value for this variable
				$sql_data = "SELECT var_value_id 
										FROM 
											product_variable_data 
										WHERE 
											product_variables_var_id = ".$curvar_id." 
										ORDER BY var_order  
										LIMIT 
											1";
				$ret_data = $db->query($sql_data);
				if ($db->num_rows($ret_data))
				{
					$row_data = $db->fetch_array($ret_data);
				}							
				$var_arr[$curvar_id] = $row_data['var_value_id'];
			}
		}
	}
	// Section to show the bulk discount details
	$comb_arr = get_combination_id($row_prod['product_id'],$var_arr);
	$bulkdisc_details = product_BulkDiscount_Details($row_prod['product_id'],$comb_arr['combid']);
	if (count($bulkdisc_details['qty']))
	{
	?>	
		<div class="deat_bulk_outr">
		<div class="deat_bulk_top"><?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['BULK_DISC_HEAD'])?></div>
		<div class="deat_bulk_bottom">
		<div class="deat_bulk_conts">
		<?php
			for($i=0;$i<count($bulkdisc_details['qty']);$i++)
			{
				echo '<span>'.$bulkdisc_details['qty'][$i].' '.stripslash_normal($Captions_arr['PROD_DETAILS']['BULK_FOR']).' ';
				//echo print_price($bulkdisc_details['price'][$i]).' '.stripslash_normal($Captions_arr['PROD_DETAILS']['BULK_EACH']);
				echo product_BulkDiscount_Build_Price($bulkdisc_details['price'][$i],$bulkdisc_details['price_without'][$i]).' '.stripslash_normal($Captions_arr['PROD_DETAILS']['BULK_EACH']);
				echo '</span>';
			}
		?>
		</div>
		</div>
		</div>
	<?php
	}
}
/* Function to show the instock notification */
function show_Instock_msg($row_prod, $stockalert='')
{
	global $db,$ecom_siteid,$Captions_arr,$ecom_hostname,$Captions_arr;
	$show_notify = false;
	if ($row_prod['product_stock_notification_required']=='Y' and $row_prod['product_alloworder_notinstock']=='N')
	{
		// Check whether variable stock is managed
		if ($row_prod['product_variablestock_allowed']=='N' and $row_prod['product_webstock']==0)
		{ 
			if ($row_prod['product_preorder_allowed']=='Y' and $row_prod['product_total_preorder_allowed']>0)
			{
				$show_notify = false;
			}
			else
			{
				$show_notify = true;
			}	
		}
		elseif($row_prod['product_variablestock_allowed']=='Y')
		{ 
			if($_REQUEST['for_notification']==1)
				$show_notify = true;
			else
			{
				if ($row_prod['product_preorder_allowed']=='Y' and $row_prod['product_total_preorder_allowed']>0)
				{
					$show_notify = false;
				}
				else
				{
					/*// Check whether web stock exists for any of the combination for current product. if not then also show the message
					$sql_stk = "SELECT comb_id 
										FROM 
											product_variable_combination_stock 
										WHERE 
											products_product_id = ".$row_prod['product_id']." 
											AND web_stock>0 
										LIMIT 
											1";
					$ret_stk = $db->query($sql_stk);
					if ($db->num_rows($ret_stk)==0) // case if not stock exists for any of the combinations
						$show_notify = true;
						*/
				}		
			}
		}
		if($show_notify) // Check whether notificataion link is to be shown
		{
			if($_REQUEST['for_notification']==1)// case if coming back to the product details page after validation
			{ 
			?>
					<script type="text/javascript">
						if(document.getElementById('alert_main_div'))
							document.getElementById('alert_main_div').style.display='';
					</script>
				<div id="div_defaultFlash_outer" class="flashvideo_outer"></div>	
				<div  class="div_alert" id="instockmsg_div">
				<div align="right" class="instockmsg_span" ><a href="javascript: hide_instockmsg_div()"><img src="<?php url_site_image('close.gif')?>" border="0" /></a></div>
					<?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODUCT_OUT_STOCK'])?>.
				 <br />
				<span class="instockmsg_out_stock">
				<?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODUCT_OUT_STOCK_EMAIL'])?></span>
				<input type="text"   name="stock_email" />
				<input type="hidden" name="prod_mod" value="stock_notify" />
				<input type="hidden" name="hid_notify" value="stock" /> 
				<input type="button" name="stocknotif_submit" value=" Send Request " class="buttongray" onclick=" validate_stocknotify(document.frm_proddetails)"  />
				<input type="hidden" name="product_id" value="<?PHP echo $_REQUEST['product_id']; ?>" />
			
				</div>
			<?php	
				}
				else
				{
			?>	
				<div  class="alert_inner"><?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODUCT_OUT_STOCK'])?>.
				<br />
				<span style="font-size:12px;font-weight:normal;color:#000000;"><?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODUCT_OUT_STOCK_EMAIL'])?><br />

					<input type="text"   name="stock_email" />
					<input type="hidden" name="prod_mod" value="stock_notify" />
					<input type="hidden" name="hid_notify" value="stock" /> 
					<input type="button" name="stocknotif_submit" value=" Send Request " class="buttongray" onclick=" validate_stocknotify(document.frm_proddetails)"  />
					<input type="hidden" name="product_id" value="<?PHP echo $_REQUEST['product_id']; ?>" />
				</span>
				</div>
	<?php
			}		
		}
	}
}
function show_product_downloads($prod_id)
{
	global $db,$ecom_siteid,$Captions_arr,$ecom_hostname,$Settings_arr;
	global $ecom_selfhttp;
	$sql_attach = "SELECT * 
						FROM 
							product_attachments 
						WHERE 
							products_product_id = ".$prod_id." 
							AND attachment_hide=0  
						ORDER BY 
							attachment_order";
							
	$ret_attach = $db->query($sql_attach);
	if ($db->num_rows($ret_attach) and $Settings_arr['show_downloads_newrow']==1)
	{
?>
		<div class="deat_conts_outr">
		<div class="deat_conts_con">
		<div class="deat_conts_hdr"><span><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_DOWNLOADS'];?></span></div>
		</div>
		<div class="deat_conts_conts">
		<ul class="donloads_ul">
		<?php
		$cnts = 1;
		while ($row_attach = $db->fetch_array($ret_attach))
		{
		?>
		
			<li><div class="donloads_no"><?php echo $cnts?></div>
			<div class="donloadsleft"><span><a href="<?php echo $ecom_selfhttp.$ecom_hostname?>/download.php?attach_id=<?php echo $row_attach['attachment_id']?>" title="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_CLICKDOWNLOAD'];?>"><?php echo stripslashes($row_attach['attachment_title'])?></a></span></div>
			</li>
		<?php
		}
		?>
		</ul>
		</div>
		<div class="deat_conts_bottom"></div>
		</div>
	<?php
	}
}
};	
?>
