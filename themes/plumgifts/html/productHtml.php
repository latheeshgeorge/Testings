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
					 $ecom_themeid,$default_layout,$inlineSiteComponents,$Settings_arr,$meta_arr,$ecom_twitteraccountId;
			// ** Fetch any captions for product details page
			$Captions_arr['PROD_DETAILS'] 	= getCaptions('PROD_DETAILS');
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
				while(list($size_value) = $db->fetch_array($charres))
				{
					$sizevalue[$heading_id][] = $size_value;
				}
			 }
	
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
			
			/********************** HTML Generating starts here *********************/
			$HTML_treemenu = $HTML_showstock = $HTML_compare = $HTML_saleicon = $HTML_fav = $HTML_readrev = $HTML_writerev = '';
			$HTML_email = $HTML_pdf = $HTML_price = $HTML_bonus = $HTML_wishlist = $HTML_enquiry = $HTML_promise_buttons = '';
			$HTML_treemenu = ' 
			
			<ul class="tree_menu_details">'.generate_tree_menu(-1,$_REQUEST['product_id'],'','<li>','</li>').'
								</ul>';
			$disp_stk = get_stockdetails($_REQUEST['product_id']);
			$prod_name = '<a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'" class="deat_pdt_namelink">
					'.stripslash_normal($row_prod['product_name']).'</a>';
			if($disp_stk!='')
				$HTML_showstock = '<div class="deat_pdt_stock"><div class="deat_pdt_stock_left"><span>'.$disp_stk.'</span></div></div>';
			$was_price = $cur_price = $sav_price = '';
			//$price_arr = show_Price($row_prod,$price_class_arr,'prod_detail',false,5);
			
			 $price_arr = array();
			$price_arr =  show_Price($row_prod,array(),'prod_detail',false,5);
			if($price_arr['price_with_captions']['discounted_price'])
			{
				$was_price = $price_arr['price_with_captions']['base_price'];
				$cur_price = $price_arr['price_with_captions']['discounted_price'];
				if($price_arr['prince_without_captions']['disc_percent'])
					$sav_price =$price_arr['prince_without_captions']['disc']." ".$Captions_arr['COMMON']['PROD_OFF'];
			}
			else
			{
				$was_price = '';
				$cur_price = $price_arr['price_with_captions']['base_price'];
				$sav_price = '';
			}
			/*if($price_arr['disc'])
			{
			
			   $was_price = $price_arr['base_price'];
				$cur_price = $price_arr['discounted_price'];
			  $sav_price = $Captions_arr['COMMON']['PROD_OFF']." ".$price_arr['disc'];
			}
			else
			{
				$was_price = '';
				$cur_price = $price_arr['base_price'];
				$sav_price = '';
			}*/
			    
			$HTML_price = '';
			if($was_price)
				$HTML_price .= '<div class="deat_priceA">'.$was_price.'</div>';
			if($cur_price)
				$HTML_price .= '<div class="deat_priceB">'.$cur_price.'</div>';	
			if($sav_price)
			{
				$HTML_price_disc .= '<div class="deat_priceC">'.$sav_price.'</div>';
			}					
			
			if ($Settings_arr['bonus_points_instock'] and $row_prod['product_bonuspoints']>0)
			{
				$HTML_bonus = '<div class="deat_bonus">
								<div class="deat_bonusA">'.$row_prod['product_bonuspoints'].'</div>
								<div class="deat_bonusB">'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_BONUSPOINTS']).'</div>
								<div class="deat_bonusC"><a href="'.url_link('bonuspoint_content.html',1).'" title=""><img src="'.url_site_image('bonusmoreinfo.gif',1).'" /></a></div>
								</div>';
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
			
			$HTML_bottomblock .='				  
								</div>   
								</div>';
			?>
			<script type="text/javascript" language="javascript">
			function ajax_return_productdetailscontents() 
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
						else if(document.getElementById('ajax_div_holder').value=='mainimage_holder')
						{
							if(document.getElementById('comb_img_allowed').value=='Y') /* Do the following only if variable combination image option is set for current product */ 
								handle_show_prod_det_bulk_disc('more_img',docroot,prod_id,loading_gif);
						}
						hide_loading('proddet_loading_div');
					}
					else
					{
						hide_loading('proddet_loading_div');
						/*alert(req.status);*/
					}
				}
			}
			<?php
			if($Settings_arr['javascript_jquery']==1)
			{
				echo "jQuery.noConflict(); /* This is done to avoid error in light box due to the usage of $ in jquery*/";
			}
			?>
			</script>
			<form method="post" name="frm_proddetails" id="frm_proddetails" action="<?php url_link('manage_products.html')?>" class="frm_cls" onsubmit="return prod_detail_submit(this)">
			<input type="hidden" name="fpurpose" value="" />
			<input type="hidden" name="fproduct_id" value="<?php echo $_REQUEST['product_id']?>" />
			<input type="hidden" name="pricepromise_url" value="<?php url_link('pricepromise'.$row_prod['product_id'].'.html')?>" />
			<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
			<input type="hidden" name="pass_combid" value="<?php echo $_REQUEST['pass_combid']?>" />
			<input type="hidden" name="comb_img_allowed" id="comb_img_allowed" value="<?php echo $row_prod['product_variablecombocommon_image_allowed']?>" />
			<input type="hidden" name="ajax_div_holder" id="ajax_div_holder" value="" />
			<input type="hidden" name="pagetype" id="pagetype" value="" />
			
			<?=$HTML_treemenu?>    
				<div class="details_main_image">
				<div class="details_image"><div id="mainimage_holder">	
				<?php  $this->Show_Image_Normal($row_prod);?></div>
				</div>
				<div id="moreimage_holder">
				<?php
				$return_arr = $this->Show_Image_Normal($row_prod,true);
				// Showing additional images
				$this->show_more_images($row_prod,$return_arr['exclude_tabid'],$return_arr['exclude_prodid']);
				?>
				</div>
				</div>
				 <div class="details_main_cont">
				<div class="deat_pdt_name">
				<?=$prod_name ?>
				</div>
				<?php if($row_prod['product_longdesc']!=''){?>
    			  <div class="deat_pdt_des"><?php echo  stripslashes($row_prod['product_longdesc']);?></div>  
				<? 
				}
				?>
				<div class="deat_pdt_price">
				<div id='price_holder'>
				 <div class="deat_price">
				 <?=$HTML_price?>
				 </div>
				 </div>
				<?=$HTML_price_disc?>
				 </div>
				<? $this->show_ProductVariables($row_prod,'',$sizechart_heading);
				 $this->show_buttons($row_prod);?>	
      
                    </div>
					<?php
// ** Check whether any linked products exists for current product
			$sql_linked = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
							a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
							a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
							a.product_total_preorder_allowed,a.product_applytax,product_shortdesc,
							a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,
							a.product_variablesaddonprice_exists,a.product_variablecomboprice_allowed,
							a.product_variablecombocommon_image_allowed,a.default_comb_id,
							a.price_normalprefix,a.price_normalsuffix, a.price_fromprefix, a.price_fromsuffix,a.price_specialofferprefix, a.price_specialoffersuffix, 
							a.price_discountprefix, a.price_discountsuffix, a.price_yousaveprefix, a.price_yousavesuffix,a.price_noprice,
							a.product_averagerating,a.product_saleicon_show,a.product_saleicon_text,a.product_newicon_show,a.product_newicon_text,
							a.product_freedelivery        
							FROM 
								products a,product_linkedproducts b 
							WHERE 
								b.link_parent_id=".$_REQUEST['product_id']." 
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
			}    
			?>               
	
			</form>
			<form method="post" action="">
			<input type="hidden" />
			</form>
			<?php
		}

function Show_Image_Normal($row_prod,$just_return_id=false)
{
	global $db,$ecomn_siteid,$ecom_hostname,$ecom_themename;	
	  $show_normalimage = false;
	  if ($_REQUEST['prod_curtab'] and $row_prod['product_variablecombocommon_image_allowed']=='N')
	  {
		if ($_REQUEST['prodimgdet'])	
			$showonly = $_REQUEST['prodimgdet'];
		else
			$showonly = 0;
		// Calling the function to get the type of image to shown for current 
		$pass_type = get_default_imagetype('proddet');
		// Calling the function to get the image to be shown
		$tabimg_arr = get_imagelist('prodtab',$_REQUEST['prod_curtab'],$pass_type,0,$showonly,1);
		if(count($tabimg_arr))
		{
			$exclude_tabid = $tabimg_arr[0]['image_id']; 		// exclude id in case of multi images for products
			if($just_return_id!=true)
			{
			?>
			<a href="<?php url_root_image($tabimg_arr[0]['image_extralargepath'])?>" title="<?php echo $row_prod['product_name']?>" rel='lightbox[gallery]' >
			<?php
			show_image(url_root_image($tabimg_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name']);
			?>
			</a>
			<?php
			}
			$show_noimage 	= false;
		}
		else
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
			$pass_type = get_default_imagetype('proddet');
			// Calling the function to get the image to be shown
			if($row_prod['product_variablecombocommon_image_allowed']=='Y')
				$prodimg_arr = get_imagelist_combination($row_prod['default_comb_id'],$pass_type,0,1);
			else
				$prodimg_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,0,$showonly,1);
			if(count($prodimg_arr))
			{
				$exclude_prodid = $prodimg_arr[0]['image_id']; 		// exclude id in case of multi images for products
				if($just_return_id!=true)
				{
				?>
				<a href="<?php url_root_image($prodimg_arr[0]['image_extralargepath'])?>" rel='lightbox[gallery]' title="<?=$row_prod['product_name']?>">
				<?php
				show_image(url_root_image($prodimg_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name']);
				?>
				</a>
				<?php
				}
				$show_noimage 	= false;
			}
			else
			{	
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

function show_more_images($row_prod,$exclude_tabid,$exclude_prodid)
{
	global $db,$ecom_hostname,$ecom_themename;
	$show_normalimage = false;
	$prodimg_arr		= array();
	$pass_type 	= 'image_iconpath';
	if ($_REQUEST['prod_curtab'] and $row_prod['product_variablecombocommon_image_allowed']=='N')
	{
		if ($exclude_tabid)
			$prodimg_arr = get_imagelist('prodtab',$_REQUEST['prod_curtab'],$pass_type.',image_thumbpath',$exclude_tabid,0);	
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
				$prodimg_arr = get_imagelist_combination($row_prod['default_comb_id'],$pass_type,$exclude_prodid,0);
		}		
		else
		{
			if ($exclude_prodid)
				$prodimg_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,$exclude_prodid,0);
		}
	} 
	if(count($prodimg_arr)>0)// Case if more than one image assigned to current product
	{
	?>	
	     <div class="details_thumb_image">
			<div class="deat_pdt_thumbimg">
			<div class="det_link_thumbimg_con">
			<div class="det_thumbimg_nav"><a onmouseout="stopMe()" onmouseover="scrollDivRight('containerB')" href="#null"><img src="<?php url_site_image('js-arrow-lefta.gif')?>"></a></div>
			<div class="det_thumbimg_inner" id="containerB">
			<div id="scroller_thumb">
			<?php
			$tabstr		= ($_REQUEST['prod_curtab'])?'&amp;prod_curtab='.$_REQUEST['prod_curtab']:'';
			foreach ($prodimg_arr as $k=>$v)
			{ 
				$title = ($v['image_title'])?stripslash_normal($v['image_title']):$row_prod['product_name'];
			?>
				<div class="det_thumbimg_pdt">
					<div class="det_thumbimg_image">
					<a href="<?php url_root_image($v['image_extralargepath'])?>" rel='lightbox[gallery]' title="<?=$title?>">
					<?php
						 show_image(url_root_image($v['image_iconpath'],1),$title,$title,'preview');
					?>
					</a>
					</div>
				</div>
			<?php
			}
			?>	
			</div>
			
			</div>
			<div class="det_thumbimg_nav"> <a onmouseover="scrollDivLeft('containerB',<?php echo (count($prodimg_arr)*50)?>)" onmouseout="stopMe()" href="#null"><img src="<?php url_site_image('js-arrow-righta.gif')?>"></a></div>
			</div>
			</div>
     	</div>
	<?php
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
  		<div class="deat_pdt_varable">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="deat_pdt_varable_table">
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
				  <tr>
					<td align="right" valign="middle" class="varable_table_left"><?php echo stripslash_normal($row_var['var_name'])?> : </td>
					<td align="left" valign="middle" class="varable_table_right">
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
									<select name="var_<?php echo $row_var['var_id']?>" id="var_<?php echo $row_var['var_id']?>" <?php echo $onchange_function?>>
									<?php 
									while ($row_vals = $db->fetch_array($ret_vals))
									{
									?>
										<option value="<?php echo $row_vals['var_value_id']?>" <?php echo ($_REQUEST['var_'.$row_var['var_id']]==$row_vals['var_value_id'])?'selected':''?>><?php echo stripslash_normal($row_vals['var_value'])?><?php echo Show_Variable_Additional_Price($row_prod,$row_vals['var_addprice'],$vardisp_type)?></option>
									<?php
									}
									?>
									</select>							
						<?php
							  }
							  else
							  {
									$first_val	 	= '';
									$first_one 		= 1;
									$docroot 		= SITE_URL; 
									$prodid			= $_REQUEST['product_id'];
									$loading_gif 	= url_site_image('loading.gif',1);
									while ($row_vals = $db->fetch_array($ret_vals))
									{
										if($first_val=='')
											$first_val = $row_vals['var_value_id'];
										// Calling function to process the variables and get back the required values	
										$ret_arr = handle_variable_color_section($row_vals,$first_val,$color_type);	
										
										$show_value		= $ret_arr['show_value'];
										$clr_val 		= $ret_arr['clr_val'];
										$normal_cls 	= $ret_arr['normal_cls'];
										$special_cls 	= $ret_arr['special_cls'];
										
										$normal_cls_sz 		= "size_var_div";
										$special_cls_sz 	= "size_var_div_sel";
										$normal_cls_clrimg 	= "colorimg_div";
										$special_cls_clrimg	= "colorimg_div_sel";
										$normal_cls_clr 	= "color_div";
										$special_cls_clr	= "color_div_sel";
										$varvaldivid 		= "valdiv_var_".$row_var['var_id']."_".$row_vals['var_value_id'];
										$onclick_function   = " onclick ='var_onclick(\"".$onclick_var."\",\"var_".$row_var['var_id']."\",".$row_vals['var_value_id'].",\"".$normal_cls_clr."\",\"".$special_cls_clr."\",\"".$normal_cls_clrimg."\",\"".$special_cls_clrimg."\",\"".$normal_cls_sz."\",\"".$special_cls_sz."\",\"".$docroot."\",\"".$prodid."\",\"".$loading_gif."\")' ";
									?>
									 <div id='<?php echo $varvaldivid?>' class="<?php echo ($first_one==1)?$special_cls:$normal_cls?>" <?php echo $clr_val. ' '.$onclick_function?> title="<?php echo stripslashes($row_vals['var_value'])?>" <?php if(!$show_value) {?>onmouseover="tooltip.show('<?php echo stripslash_normal($row_vals['var_value'])?>');" onmouseout="tooltip.hide();" <?php }?>>
									 <?
									 
									 if($show_value)
									 	echo stripslashes($row_vals['var_value']);
									?>
									 </div>
									<?php
									   $first_one=2;
									}
						 ?>
								<input type='hidden' name="var_<?php echo $row_var['var_id']?>" id="var_<?php echo $row_var['var_id']?>" value="<?php echo $first_val?>" />
						 
						<?php
							  }
						}
						else
						{
						?>
							<input type="checkbox" name="var_<?php echo $row_var['var_id']?>" id="var_<?php echo $row_var['var_id']?>" value="1" <?php echo ($_REQUEST['var_'.$row_var['var_id']])?'checked="checked"':''?>/><?php echo Show_Variable_Additional_Price($row_prod,$row_var['var_price'],$vardisp_type)?>
						<?php
						}
						?>
					</td>
				  </tr>
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
				  <tr>
					<td align="right" valign="top" class="varable_table_left"><?php echo stripslash_normal($row_msg['message_title'])?> : </td>
					<td align="left" valign="top" class="varable_table_left">
						<?php
						if ($row_msg['message_type']=='TXTBX')
						{
						?>
							<input type="text" name="varmsg_<?php echo $row_msg['message_id']?>" id="varmsg_<?php echo $row_msg['message_id']?>" value="<?php echo $_REQUEST['varmsg_'.$row_msg['message_id']]?>" />
						<?php
						}
						else
						{
						?>
							<textarea name="varmsg_<?php echo $row_msg['message_id']?>" id="varmsg_<?php echo $row_msg['message_id']?>" rows="3" cols="25"><?php echo $_REQUEST['varmsg_'.$row_msg['message_id']]?></textarea>
						<?php
						}
						?>
					</td>
				  </tr>
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
		</table>
		</div>
		<?php
	}
	if($ecom_siteid == 76) // case of jelly bean
	{
		if($_REQUEST['product_id']==491758 or $_REQUEST['product_id']==491520)
		{
			$Settings_arr['showsizechart_in_popup'] = 1;
			?>
			 <div class="deat_pdt_sizechart"><div class="sizechartleft"><div><a href="http://<?php echo $ecom_hostname?>/pg49401/faq-sizing.html" title="<?php echo stripslash_normal($sizechartmain_title)?>">Size Chart<?php ?></a></div></div></div>
			<?php	
		}	
		
	}
	else
	{
		if($Settings_arr['showsizechart_in_popup']==1) // If size chart is set to show in a pop up window
		{
			if(is_array($sizechart_heading))
			{
		?>
			 <div class="deat_pdt_sizechart"><div class="sizechartleft"><div><a href="javascript:showsizechartPopup('<?php echo $row_prod['product_id']?>','<?php echo $ecom_hostname?>','<?php echo $ecom_themename?>')" title="<?php echo stripslash_normal($sizechartmain_title)?>">Size Chart<?php ?></a></div></div></div>
		<?php	
			}
		}
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
		<div class="deat_pdt_buyBinner">
	<?php	
	if($showqty==1)// this decision is made in the main shop settings
	{
		if($row_prod['product_det_qty_type']=='NOR')
		{
?>
			<div class="buyBinner_qty"><img src="<? url_site_image('qty.gif')?>" /></div>
			<div class='buyBinner_txt'><input type="text" class="det_qty_txt" name="qty"  value="<?php echo ($_REQUEST['qty'])?$_REQUEST['qty']:1?>" maxlength="2" /></div>
<?php
		}
		elseif($row_prod['product_det_qty_type']=='DROP')
		{
			$dropdown_values = explode(',',stripslash_normal($row_prod['product_det_qty_drop_values']));
			if (count($dropdown_values) and stripslash_normal($row_prod['product_det_qty_drop_values'])!='')
			{
			?>
				<div class='buyBinner_txt'>
				<select name="qty">
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
<div class="buyBinner_link"><a href="#" class="det_buy_link" onclick="show_wait_button(this,'Please Wait...');document.frm_proddetails.fpurpose.value='Prod_Addcart';document.frm_proddetails.submit();"><img src="<?php url_site_image('cart-buy-btn.gif');?>" title="<?php echo stripslash_normal($Captions_arr['PROD_DETAILS'][$caption_key])?>" border="0" /></a></div>
</div>
<?php
	}	
	return true;
}
// ** Function to show the details of products which are linked with current product.
function Show_Linked_Product($ret_prod)
{
	global $ecom_siteid,$db,$Captions_arr,$Settings_arr;
	// Calling the function to get the type of image to shown for current 
	$pass_type = get_default_imagetype('link_prod');
	$prod_compare_enabled = isProductCompareEnabled();
	switch($Settings_arr['linked_prodlisting'])
	{
		case '1row':
		case '2row':
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
		<div class="det_link_pdt_conts">
    	<div class="det_link_pdt_hdr"><div class="link_pdt_hdr"><span><?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_PEOPLE_INTERESTED']);?></div>
		<div class="det_det_link_pdt_con">
		<div class="det_det_link_nav">
		<a href="#null" onmouseover="scrollDivRight('containerA')" onmouseout="stopMe()"><img src="<?php url_site_image('js-arrow-left.gif')?>"></a>
		</div>
		<div class="det_det_link_pdt_inner" id="containerA">
		<div style="width: 830px;" id="scroller">
					<?php
		$cnts = $db->num_rows($ret_prod);
		while($row_prod = $db->fetch_array($ret_prod))
		{
		?>
		<?php
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
				?>
			<div class="det_det_link_pdt">
				<div class="det_det_link_image" onmouseover="tooltip.show('<?php echo stripslash_normal($row_prod['product_name'])?>');" onmouseout="tooltip.hide();" >
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
			</div>					
<?php
		}
		?>					
		</div>
		</div>
		<div class="det_det_link_nav"> 
		<a href="#null" onmouseover="scrollDivLeft('containerA','<?php echo $div_width?>')" onmouseout="stopMe()"><img src="<?php url_site_image('js-arrow-right.gif')?>" /></a>
		</div>
		</div>
        </div>
<?php
		
	break;
	};	
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
						<li><a href="'.url_link('',1).'" title="'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a> &gt;&gt; </li>
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
		echo $HTML_comptitle,$HTML_maindesc;
		// Calling the function to get the type of image to shown for current 
		$pass_type = get_default_imagetype('link_prod');
		$prod_compare_enabled = isProductCompareEnabled();
		while($row_prod = $db->fetch_array($ret_prod))
		{
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
			if($row_prod['product_saleicon_show']==1)
			{
				$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
				if($desc!='')
				{
					  $HTML_sale = '<div class="normal_shlfB_pdt_sale">'.$desc.'</div>';
				}
			}
			if($row_prod['product_newicon_show']==1)
			{
				$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
				if($desc!='')
				{
					  $HTML_new = '<div class="normal_shlfB_pdt_new">'.$desc.'</div>';
				}
			}
			$module_name = 'mod_product_reviews';
			if(in_array($module_name,$inlineSiteComponents))
			{
				if($row_prod['product_averagerating']>=0)
				{
					$HTML_rating = display_rating($row_prod['product_averagerating'],1);
				}
			}
			$price_class_arr['class_type']          = 'div';
			$price_class_arr['normal_class']        = 'normal_shlfB_pdt_priceA';
			$price_class_arr['strike_class']        = 'normal_shlfB_pdt_priceB';
			$price_class_arr['yousave_class']       = 'normal_shlfB_pdt_priceC';
			$price_class_arr['discount_class']      = 'normal_shlfB_pdt_priceC';
			$HTML_price = show_Price($row_prod,$price_class_arr,'shelfcenter_1');
			
			if($row_prod['product_bonuspoints']>0)
			{
				$HTML_bonus = 'Bonus: '.$row_prod['product_bonuspoints'];
				$bonus_class = 'normal_shlfB_pdt_bonus';
			}
			else
			{
				$HTML_bonus = '&nbsp;';
				$bonus_class = 'normal_shlfB_pdt_bonus_blank';
			}	
			if($prod_compare_enabled)
				$HTML_compare = dislplayCompareButton($row_prod['product_id'],'','',1,'compare_li_2row');
			if($row_prod['product_freedelivery']==1)
			{
				$HTML_freedel = ' <div class="normal_shlfB_free"></div>';
			}
			if($HTML_bonus!='&nbsp;' or $HTML_rating !='&nbsp;')
			{
				$HTML_bonus_bar = ' <div class="normal_shlfB_pdt_bonus_otr">
									<div class="'.$bonus_class.'">'.$HTML_bonus.'</div>
									<div class="normal_shlfB_pdt_rate">'.$HTML_rating.'</div>
									</div>';
			}	
	?>
	
			<div class="normal_shlfB_pdt_outr">
			<?=$HTML_freedel?>
			<div class="normal_shlfB_pdt_top"></div>
			<div class="normal_shlfB_pdt_mid">
			<?=$HTML_title?>
			<div class="normal_shlfB_pdt_img_otr">
			<div class="normal_shlfB_pdt_img"><?=$HTML_image?></div>
			</div>
			<div class="normal_shlfB_pdt_des_otr">
			<div class="normal_shlfB_pdt_des"><?=$HTML_desc?></div>
			<?=$HTML_sale?>
			<?=$HTML_new?>
			<div class="normal_shlfB_pdt_com_otr">
			<div class="normal_shlfB_multibuy"><?=$HTML_bulk?></div>
			<div class="normal_shlfB_pdt_com"><?=$HTML_compare?></div>
			</div>
			<?=$HTML_bonus_bar?>
			</div>
			<div class="normal_shlfB_pdt_right_otr">
			<div class="normal_shlfB_pdt_price">
			<div class="normal_shlfB_pdt_price_top"></div>
			<div class="normal_shlfB_pdt_price_mid">
			<?=$HTML_price?>
			</div>
			<div class="normal_shlfB_pdt_price_bottom"></div>
			</div>
			<div class="normal_shlfB_pdt_info"><?php show_moreinfo($row_prod,'')?></div>
			</div>
			
			</div>
			</div>
	
	<?php
		}
	?>
	<div  align="left"><input type="button" name="prodet_comparebutton" value="<?php echo stripslash_normal($Captions_arr['COMMON']['COMPARE_PRODUCTS']);?>" class="buttonred_cart" onclick="handle_proddet_compare()"/>
	</div>
	</div>
	<div class="normal_shlf_mid_bottom"></div> 
	</div>	
<?php
}
// ** Function to display the variables. This is written as function to avoid code repetation in various if conditions

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
			<!--	<span style="font-weight:bold;">
				<a href="javascript:handle_instocknotification('<?php// echo $row_prod['product_id']?>','<?php// echo $ecom_hostname?>')" style="color:#000000;text-decoration:underline">click here</a></span>  -->
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
};	
?>