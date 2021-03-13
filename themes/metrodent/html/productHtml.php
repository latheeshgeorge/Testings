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
					 $ecom_themeid,$default_layout,$inlineSiteComponents,$Settings_arr,$ecom_twitteraccountId,$meta_arr;
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
				$alert = stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_ADDFAVADDED']);
			}
			else if($_REQUEST['result']=='removed')
			{
				$alert = stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_ADDFAVREMOVED']);
			}
			
			$prod_img_show_type =$row_prod['product_details_image_type'];
		?>
		 <script type="text/javascript" language="javascript">
		  function download_pdf()
		  {
			document.getElementById('bw').innerHTML = '<iframe src="http://www.web2pdfconvert.com/convert.aspx?cURL=http://<?=$_SERVER['HTTP_HOST']?><?=$_SERVER['REQUEST_URI']?>&outputmode=stream&allowactivex=yes&ref=form">';
			show_processing();
			setTimeout('hide_processing()', 20000);
		  }
	  </script>
		<div class="treemenu"><ul><?php echo generate_tree(-1,$_REQUEST['product_id'],'<li>','</li>')?></ul></div>
		<form method="post" name="frm_proddetails" id="frm_proddetails" action="<?php url_link('manage_products.html')?>" class="frm_cls" onsubmit="return prod_detail_submit(this)">
		<input type="hidden" name="fpurpose" value="" />
		<input type="hidden" name="fproduct_id" value="<?php echo $_REQUEST['product_id']?>" />
		<input type="hidden" name="pricepromise_url" value="<?php url_link('pricepromise'.$row_prod['product_id'].'.html')?>" />
		<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
		<input type="hidden" name="pass_combid" value="<?php echo $_REQUEST['pass_combid']?>" />
		<input type="hidden" name="comb_img_allowed" id="comb_img_allowed" value="<?php echo $row_prod['product_variablecombocommon_image_allowed']?>" />
		<input type="hidden" name="ajax_div_holder" id="ajax_div_holder" value="" />
		<input type="hidden" name="pagetype" id="pagetype" value="" />
		<script type="text/javascript">
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
							handle_show_prod_det_bulk_disc('bulk',docroot,prod_id,loading_gif);
						}
						else if(document.getElementById('ajax_div_holder').value=='bulkdisc_holder')
						{
							if(document.getElementById('comb_img_allowed').value=='Y' ) /* Do the following only if variable combination image option is set for current product */ 
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
				echo "jQuery.noConflict();";
			}
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
		function handle_image_swap(src_id)
		{
			imglocal_arr = new Array();
			var img_path = '<?php echo "http://$ecom_hostname/images/$ecom_hostname/"?>';
			var destindex = 0;
			if(document.getElementById('main_img_hold_var'))
			{
				var main_img = document.getElementById('main_img_hold_var').value
				if(main_img!='')
				{
					imglocal_arr[0]  = main_img;
				}	
			}
			if(document.getElementById('more_img_hold_var'))
			{	
				var more_img = document.getElementById('more_img_hold_var').value;
				if(more_img!='')
				{
					more_img_arr = more_img.split('~');
					for(i=0;i<more_img_arr.length;i++)
					{
						imglocal_arr[i+1] = more_img_arr[i];
					}
				}
			}
			if (src_id)
			{
				document.getElementById('main_det_img').src = img_path + 'big/'+imglocal_arr[src_id];
				
				srcobj = eval ("document.getElementById('moreid_"+src_id+"')");
				srcobj.src =  img_path + 'icon/'+imglocal_arr[destindex];
				
				var tempval 			= imglocal_arr[destindex];
				imglocal_arr[destindex] 	= imglocal_arr[src_id];
				imglocal_arr[src_id] 		= tempval;
				
				document.getElementById('main_img_hold_var').value = imglocal_arr[destindex];
				var temp_hold = '';
				if (imglocal_arr.length>1)
				{
					for(i=1;i<imglocal_arr.length;i++)
					{
						if(temp_hold!='')
							temp_hold += '~';
						temp_hold += imglocal_arr[i];
					}
				}
				document.getElementById('more_img_hold_var').value = temp_hold;
			}
		}
		
		</script>	
			

	   <div class="det_pdt_otr">
	   <div class="det_pdt_inr">

		<?php
		$email_show 		= 0;
		$favourite_show		= 0;
		$writereview_show	= 0;
		$readreview_show	= 0;
		$pdf_show			= 0;
		$compare_show		= 0;
		if(isProductCompareEnabledInProductDetails())
		{
			$def_cat_id = $row_prod['product_default_category_id'];
			$sql_comp = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
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
			$pdf_show				= 1;	 // pdf download link
		if($cust_id)
		{
			if($Settings_arr['proddet_showfavourite']==1)
				$favourite_show = 1;
		}
		// Check whether size chart details exists for current product
		$size_checkval = false;
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
		$sizechart_heading['size_Availvalues'] = $size_checkval;	   $cnt =   count($sizevalue);
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
		if($alert)
		{
		?>
		  <div class="red_msg"> - <?php echo $alert?> - </div>
		<?php
		}
		elseif($_REQUEST['stockalert'])
		{
		?>
				<div class="red_msg"> - <?php echo stripslash_normal($Captions_arr['PROD_DETAILS'][$_REQUEST['stockalert']])	?> - </div>
		<?php	
		}
		?>
		  <div class="det_pdt_r"><?php
			$return_arr = $this->Show_Image_Normal($row_prod,true);
			// Showing additional images
			$this->show_more_images($row_prod,$return_arr['exclude_tabid'],$return_arr['exclude_prodid']);
				$this->Show_Image_Normal($row_prod);

		?></div>
    <?php
		if($_REQUEST['for_notification']==1) // make the decision whether the instock message is to be displayed here or in div
		{
			$this->show_Instock_msg($row_prod,$_REQUEST['stockalert']);
		}	
		else // case if displaying the instock notification message here itself
			$this->show_Instock_msg($row_prod,$_REQUEST['stockalert']);
			
			$HTML_loading = '<div class="proddet_loading_outer_div" style="height:15px"><div id="proddet_loading_div" style="display:none;padding:5px 0 0 0;">
							<img src="'.url_site_image('proddet_loading.gif',1).'" alt="loading..." width="43px" height="11px;align:left">
							</div></div>';	
		?>
  
		<?php 
		$stk_det = get_stockdetails($_REQUEST['product_id']);
		/*if($stk_det!='')
		{
		?>
		<div class="stock_txt"><?php echo $stk_det;?></div>
		<?
		}
		if ($Settings_arr['show_bookmarks'])
		{
		?>
		<div class="book_mark">
		<div class="book_head"><?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['BOOKMARK_HEADING'])?></div>
		<?php bookmarks(url_product($row_prod['product_id'],$row_prod['product_name'],1),htmlspecialchars($meta_arr['title']),$ecom_twitteraccountId); ?>
		</div>
		<?php
		}
		*/ 
		/*
		if ($Settings_arr['bonus_points_instock'] and $row_prod['product_bonuspoints']>0)
		{
			 $sql 				= "SELECT bonus_point_details_content FROM general_settings_sites_common WHERE sites_site_id=".$ecom_siteid." LIMIT 1";
		      $res_admin 		= $db->query($sql);
		      $fetch_arr_admin 	= $db->fetch_array($res_admin);
			   $HTML_content_bonus ='';
			   if($fetch_arr_admin['bonus_point_details_content']!='')
			   {
			   $HTML_content_bonus = '<div class="deat_bonusC"><a href="'.url_link('bonuspoint_content.html',1).'" title=""><img src="'.url_site_image('bonusmoreinfo.gif',1).'" border="0" /></a></div>';
			   }
				$HTML_bonus = '<div class="deat_bonus">
								<div class="deat_bonusA">'.$row_prod['product_bonuspoints'].'</div>
								<div class="deat_bonusB">'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_BONUSPOINTS']).'</div>'.$HTML_content_bonus.
								'</div>';
		}
					echo $HTML_bonus;
		if ($Settings_arr['proddet_showwishlist'])
		{
			if($cust_id) // ** Show the wishlist button only if logged in 
			{
			?>
				<img src="<?php url_site_image('add-wishlist.gif')?>" alt="<?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_ADDWISHLIST']);?>" title="<?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_ADDWISHLIST']);?>" onclick="show_wait_button(this,'Please Wait...');document.frm_proddetails.fpurpose.value='Prod_Addwishlist';document.frm_proddetails.submit()" />
			<?php
			}	
			else
			{
			?>
				<img src="<?php url_site_image('add-wishlist.gif')?>" alt="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_ADDWISHLIST'];?>" title="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_ADDWISHLIST'];?>" onclick="window.location='<?php url_link('wishlistcustlogin.html')?>'"/>
			<?php
			}
		}	
		if ($row_prod['product_show_enquirelink']==1)
		{
		?>
			<img src="<?php url_site_image('add-enqry.gif')?>" title="<?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_ENQUIRY']);?>" alt="<?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_ENQUIRY']);?>" onclick="show_wait_button(this,'Please Wait...');document.frm_proddetails.fpurpose.value='Prod_Enquire';document.frm_proddetails.submit();" />			
		<?php
		}
	
		if( $compare_show==1)
		{
			$def_cat_id = $row_prod['product_default_category_id'];
		?>	
			<a href="<?php url_productcompare($_REQUEST['product_id'],$row_prod['product_name'])?>" class="productdetailslink"  title="<? echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_COMPARE_LINK'])?>"><img src="<?php url_site_image('compare_prods.gif')?>" border="0" alt="<? echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_COMPARE_LINK'])?>" /></a>
		<?php
		}
		?>
		</div>
		</div>
		<div class="pro_det_center">
		<?php
			$cur_disc = '';
			$price_arr = show_Price($row_prod,$price_class_arr,'prod_detail',false,3);	
			if($price_arr['disc_percent']!='')
				$cur_disc = $price_arr['disc_percent'];
			elseif($price_arr['yousave_price']!='')
				$cur_disc = $price_arr['yousave_price']	;		
			if($cur_disc != '' and $row_prod['product_variablecomboprice_allowed']!='Y')
			{
		?>
			<div class="pro_det_off">
			<?php
				echo $cur_disc;
			?>	
			</div>
		<?php
		}
		
		$module_name = 'mod_product_reviews';
		if(in_array($module_name,$inlineSiteComponents))
		{
			if($row_prod['product_averagerating']>=0)
			{
		?>
				<div class="pro_det_rate">
				<?php
					display_rating($row_prod['product_averagerating']);
				?>
				</div>
		<?php
			}
		}	
			if($email_show==1)
			{
		?>
				<div class="pro_det_email"><a href="<?php url_link('emailafriend'.$_REQUEST['product_id'].'.html')?>" title="<?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_EMAILFRIEND']);?>"><?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_EMAILFRIEND']);?></a></div>
		<?php
			}
			*/ 
		?>
   <div class="det_pdt_l">
     <div class="det_pdt_l_otr">
     <div class="det_pdt_name"><a href="#"><?php echo stripslash_normal($row_prod['product_name'])?></a></div> 

        <div class="det_pdt_price">
			<?php 
			$price_class_arr['ul_class'] 			= 'prodeulprice';
			$price_class_arr['normal_class'] 		= 'det_priceA';
			$price_class_arr['strike_class'] 		= 'det_priceB';
			$price_class_arr['yousave_class'] 		= 'det_priceC';
			$price_class_arr['discount_class'] 		= 'det_priceC';
			echo show_Price($row_prod,$price_class_arr,'prod_detail');
			
			?>
          </div>         
     </div>
       <div class="det_pdt_overview">
           <?php echo  $row_prod['product_shortdesc']; ?>          
        </div>  
     
    
		<?php
		/*
		if($favourite_show==1) // Decide whether favorite option is to be displayed
		{
			$sql_prod = "SELECT products_product_id FROM customer_fav_products WHERE sites_site_id=$ecom_siteid AND products_product_id=".$_REQUEST['product_id']." AND customer_customer_id=$cust_id LIMIT 1";
			$ret_num= $db->query($sql_prod);
			if($db->num_rows($ret_num)==0) 
			{ 
			?>
				<a href="#" onClick="if(confirm('<? echo stripslash_javascript($Captions_arr['PROD_DETAILS']['PRODDET_ADD_CONFIRM']) ?>')){document.frm_proddetails.fpurpose.value='add_favourite';document.frm_proddetails.submit();}"><img src="<?php url_site_image('favourite.gif')?>" alt="<?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_FAVOURITE']);?>" title="<?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_FAVOURITE']);?>" /></a>
			<?php
			}
			else
			{
			?>
				<a href="#" class="productdetailslink" onClick="if(confirm('<? echo stripslash_javascript($Captions_arr['PROD_DETAILS']['PRODDET_REM_CONFIRM']) ?>')){document.frm_proddetails.fpurpose.value='remove_favourite';document.frm_proddetails.submit();}"><img src="<?php url_site_image('remfavourite.gif')?>" alt="<?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_REMFAVOURITE']);?>" title="<?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_REMFAVOURITE']);?>" /></a>
			<?php
			}
		}*/
		if($pdf_show==1) // Check whether the download pdf module is there for current site
		{ 
		?>
			<a href="javascript:download_pdf_common('<?=$_SERVER['HTTP_HOST']?>','<?=$_SERVER['REQUEST_URI']?>')" title="<?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_DOWNLOADPDF']);?>"><img src="<?php url_site_image('pdf.gif')?>" border="0" /></a>

		<?php
		}
		if($row_prod['product_newicon_show']==1)
		{
			$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
			//if($desc!='')
			{
			?>
				<div class="mid_shlf_pdt_newsale"><?php echo $desc?></div>
			<?php
			}
		}
		if($row_prod['product_saleicon_show']==1)
		{
			$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
			//if($desc!='')
			{
			?>	
				<div class="mid_shlf_pdt_sale"><?php echo $desc?></div>
			<?php
			}
		}
		/*
		if(in_array('mod_product_reviews',$inlineSiteComponents)) // Check whether the product review module is there for current site
		{
			if($writereview_show==1)
			{
		?>
				<a href="<?php url_link('writeproductreview'.$_REQUEST['product_id'].'.html')?>" title="<?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_WRITEREVIEW']);?>"><img src="<?php url_site_image('write-review.gif')?>" /></a>
		<?php
			}
			if($readreview_show==1)
			{
		?>	
				<a href="<?php url_link('readproductreview'.$_REQUEST['product_id'].'.html')?>" title="<?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_READREVIEW']);?>"><img src="<?php url_site_image('read-review.gif')?>" border="0" /></a>
		<?php
			}
		}
		if ($row_prod['product_deposit']>0 and $row_prod['product_deposit_message']!='')
		{
		?>
			<div class=" depositemess_outer">
			<div class="deposite_mess_divA"><?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_DEPOSTIT_REQ'])?> <?php echo $row_prod['product_deposit'].' '.stripslash_normal($Captions_arr['PROD_DETAILS']['DEP_PERCENTAGE'])?></div>
			<div class="deposite_mess_divB"><?php echo nl2br(stripslash_normal($row_prod['product_deposit_message']))?></div>
			</div>
		<?php	
		}
		*/
		?>         
     <?php 		//$this->show_buttons($row_prod); ?>
   </div> 
    <?php
     	//$this->show_BulkDiscounts($row_prod);
		// Calling function to show the variables and variable messages ( If any )
		$this->show_ProductVariables($row_prod,'',$sizechart_heading);	
		$this->show_product_downloads($row_prod['product_id']);
		?>
    </div>    
		
     <div class="details_pdt">
              
             <div class="details_pdt_l">
			<?php
			if ($row_prod['product_show_enquirelink']==1)
		{
		?>
			<a href="#" onclick="show_wait_button(this,'Please Wait...');document.frm_proddetails.fpurpose.value='Prod_Enquire';document.frm_proddetails.submit();"><?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_ENQUIRY']);?></a>			
		<?php
		}
			
			if($email_show==1)
			{
		?>
				<a href="<?php url_link('emailafriend'.$_REQUEST['product_id'].'.html')?>" title="<?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_EMAILFRIEND']);?>"><?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_EMAILFRIEND']);?></a>
		<?php
			}
			if ($Settings_arr['proddet_showwishlist'])
		{
			if($cust_id) // ** Show the wishlist button only if logged in 
			{
			?>
			<a href="#" onclick="show_wait_button(this,'Please Wait...');document.frm_proddetails.fpurpose.value='Prod_Addwishlist';document.frm_proddetails.submit()">
				<?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_ADDWISHLIST']);?></a>
			<?php
			}	
			else
			{
			?><a href="#" onclick="window.location='<?php url_link('wishlistcustlogin.html')?>'">
				<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_ADDWISHLIST'];?></a>
			<?php
			}
		}	
		if(in_array('mod_product_reviews',$inlineSiteComponents)) // Check whether the product review module is there for current site
		{
			if($writereview_show==1)
			{
		?>
				<a href="<?php url_link('writeproductreview'.$_REQUEST['product_id'].'.html')?>" title="<?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_WRITEREVIEW']);?>"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_WRITEREVIEW'];?></a>
		<?php
			}
			if($readreview_show==1)
			{
		?>	
				<a href="<?php url_link('readproductreview'.$_REQUEST['product_id'].'.html')?>" title="<?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_READREVIEW']);?>"><?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_READREVIEW']);?></a>
		<?php
			}
		}
			?>
			</div>
              <div class="details_pdt_r">
               <?php 
				   $show_main_button = true;
				  /*if($Settings_arr['hide_price_login']==1)
				  {
					  if(!$cust_id)
					  {
					   $show_main_button = false;
					  }
			      }*/
				  ?>
              <?php 
              if($show_main_button==true)
              $this->show_buttons($row_prod);
              else
              {
				  ?>
				   <div class="show_text_det">
				   <a class="show_text_deta" href="<?php url_link('custlogin.html') ?>"> Register or Login to View our Prices and to Buy</a>
				   </div>
				  <?php
				  
			  }
              ?>            
             
              
              </div>
              </div>
            </div>

    <?php
		$tabs_arr			= $tabs_cont_arr	= array();
		if($row_prod['product_longdesc'])
			$tabs_content_arr[0]	= stripslashes($row_prod['product_longdesc']);
		elseif ($row_prod['product_shortdesc'])
			$tabs_content_arr[0]	= stripslashes($row_prod['product_shortdesc']);
		if (count($tabs_content_arr))
			$tabs_arr 		= array ('PRODDET_OVERVIEW'=>'Overview');
							
		// Get the list of tabs for current product
		$sql_tab = "SELECT tab_id,tab_title,tab_content,product_common_tabs_common_tab_id 
						FROM 
							product_tabs 
						WHERE 
							products_product_id = ".$_REQUEST['product_id']."
							AND tab_hide=0 
						ORDER BY 
							tab_order"
					;
		$ret_tab = $db->query($sql_tab);
		if($db->num_rows($ret_tab))
		{
			while ($row_tab = $db->fetch_array($ret_tab))
			{
			   $tabs_arr[$row_tab['tab_id']]			= stripslash_normal($row_tab['tab_title']);
			   if($row_tab['product_common_tabs_common_tab_id']==0)
				{
					$tabs_content_arr[$row_tab['tab_id']]	= stripslashes($row_tab['tab_content']);
				}
				else
				{
						$sql_comm_tab = "SELECT tab_content  	 
						FROM 
							product_common_tabs 
						WHERE 
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
			}
		}
		?>
		</div>
	    <div class="det_pdt_content">
    <div class="deat_tab_con">
		<ul class="deat_protab">
		<?php
		if (count($tabs_arr))
		{		
			$curtab 		= ($_REQUEST['prod_curtab'])?$_REQUEST['prod_curtab']:0;
			if ($curtab==0 && $_REQUEST['prodmod']!='')
				$curtab = '';
			$prodimgdetid 	= (!$_REQUEST['prodimgdet'])?0:$_REQUEST['prodimgdet'];
			$chk=1;
			foreach($tabs_arr as $k_tabid=>$v_tabtitle)
			{
				$sel = ($k_tabid == $curtab)?' class="selectedtab"':'';
				if ($k_tabid=='PRODDET_OVERVIEW')
				{
					$k_tabid = 0;
					if ($_REQUEST['prodimgdet']=='')
					{
						$pass_url		= url_product($row_prod['product_id'],$row_prod['product_name'],1);
					}
					elseif ($prodimgdetid!=0)
					{
						$pass_url		= url_product_tabs($row_prod['product_id'],$row_prod['product_name'],$prodimgdetid,$k_tabid,'#protabs',1);
					}
					else
					{
						$pass_url		= url_product($row_prod['product_id'],$row_prod['product_name'],1);
					}
				}
				else
				{
					$pass_url		= url_product_tabs($row_prod['product_id'],$row_prod['product_name'],$prodimgdetid,$k_tabid,'#protabs',1);
				}	
				if(!$sel)
				{		 ?>
				
					<li><div id="tab_content_div_<?php echo $chk?>_3" class="protableft" ><a href="<?php echo $pass_url?>" title="<?php echo $v_tabtitle?>"><?php echo $v_tabtitle?></a></div></li>
					
	<?php
				}
				else
				{
					$show_tab_title = $v_tabtitle;
		?>
				
					<li><div id="tab_content_div_<?php echo $chk?>_3" class="pro_seltableft"><a href="<?php echo $pass_url?>" title="<?php echo $v_tabtitle?>"><?php echo $v_tabtitle?></a></div></li>
					
	<?php		
				}
				$chk++;
			}
		}
		?>
		</ul>
		<?php
		/*
		// Check whether labels exists for current product
		$ret_label_content = $this->show_ProductLabels($_REQUEST['product_id']);
		if($ret_label_content!='')
		{
			*/
			/*
		?>
			<div id="label_content_div_1" class="<?php echo ($_REQUEST['prodmod']=='productlabels')?'det_sel_tab_con':'det_tab_con'?>">
			<div id="label_content_div_2" class="<?php echo ($_REQUEST['prodmod']=='productlabels')?'det_sel_tab_top':'det_tab_top'?>"></div>
			<?php /*?><div id="label_content_div_3" class="det_tab_mid" ><a href="javascript:handle_tab_contents('label_content_div')" title="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_KEY_FEATURES']?>"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_KEY_FEATURES']?></a></div><?php */
			/*
			?>
		
			<div id="label_content_div_3" class="<?php echo ($_REQUEST['prodmod']=='productlabels')?'det_sel_tab_mid':'det_tab_mid'?>" ><a href="<?php url_link('productlabel'.$row_prod['product_id'].'.html')?>#protabs" title="<?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_KEY_FEATURES'])?>"><?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_KEY_FEATURES'])?></a></div>
			<div id="label_content_div_4" class="<?php echo ($_REQUEST['prodmod']=='productlabels')?'det_sel_tab_bottom':'det_tab_bottom'?>"></div>
			</div>
		<?php
		}
		
		if($Settings_arr['showsizechart_in_popup']!=1 and (is_array($heading)) && count($sizevalue)) // check whether size chart is set to show in a pop up window
		{
			if(is_array($heading))
			{ 
		?>
				<div id="sizechart_content_div_1" class="<?php echo ($_REQUEST['prodmod']=='sizechart')?'det_sel_tab_con':'det_tab_con'?>">
				<div id="sizechart_content_div_2" class="<?php echo ($_REQUEST['prodmod']=='sizechart')?'det_sel_tab_top':'det_tab_top'?>"></div>
				<div id="sizechart_content_div_3" class="<?php echo ($_REQUEST['prodmod']=='sizechart')?'det_sel_tab_mid':'det_tab_mid'?>" ><a href="<?php url_link('sizechart'.$row_prod['product_id'].'.html')?>#protabs" title="<?php echo $sizechartmain_title?>"><?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_SIZECHART_HEAD'])?></a></div>
				<div id="sizechart_content_div_4" class="<?php echo ($_REQUEST['prodmod']=='sizechart')?'det_sel_tab_bottom':'det_tab_bottom'?>"></div>
				</div>
		<?php	
			}
		}
		*/ 	
	  ?>

	<input type='hidden' name="tab_cnts" id="tab_cnts" value="<?php echo count($tabs_arr)?>" />
	</div> 
	<div class="det_pdt_content_in" id="tab_content_div">
	<a name="protabs"></a>
	<?php
	if($_REQUEST['prodmod']=='')
	{
		echo $tabs_content_arr[$curtab];
	}
	?>
	</div>
	 </div>  
	 <?php
	 if($ret_label_content!='')
		echo $ret_label_content;
		/*
		 * 		// Check whether any downloads exists for current product
				$sql_attach = "SELECT * 
								FROM 
									product_attachments 
								WHERE 
									products_product_id = ".$_REQUEST['product_id']."
									AND attachment_hide=0 
								LIMIT 
									1";
				$ret_attach = $db->query($sql_attach);
				if ($db->num_rows($ret_attach))
				{
			?>
			
				  <div class="det_pdtA_con" >
				<div class="det_pdtA_top"></div>
				<div class="det_pdtA_middle">
				<div class="det_pdtA_tabs">
	   			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="productdownloadtable">
                <tr>
                  <td class="productdownloadheader"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_DOWNLOADS'];?></td>
                </tr>
                <tr>
                  <td><ul class="downloadul">
                      <?php
								// Get the list of video attachments
								$sql_video = "SELECT * 
												FROM 
													product_attachments 
												WHERE 
													products_product_id = ".$_REQUEST['product_id']."
													AND attachment_hide=0 
												ORDER BY 
													attachment_order";
								$ret_video = $db->query($sql_video);
								if ($db->num_rows($ret_video))
								{
								?>
                      <li class="video">
                        <?php //echo $Captions_arr['PROD_DETAILS']['PRODDET_VIDEO'];?>
                        <ul  class="sub">
                          <?php	
								$cnts = 1;

								while ($row_video = $db->fetch_array($ret_video))
								{
									if($row_video['attachment_icon_img']!='')
										$download_icon = "http://$ecom_hostname/images/$ecom_hostname/attachments/icons/".$row_video['attachment_icon_img'];
									else
										$download_icon = url_site_image('download-icon.gif',1);
								?>
                          				<li><span><a href="http://<?php echo $ecom_hostname?>/download.php?attach_id=<?php echo $row_video['attachment_id']?>" class="downloadlink" title="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_CLICKDOWNLOAD'];?>"><img src="<?php echo $download_icon?>" border="0" /></a></span><span><a href="http://<?php echo $ecom_hostname?>/download.php?attach_id=<?php echo $row_video['attachment_id']?>" class="downloadlink" title="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_CLICKDOWNLOAD'];?>"><?php //echo $cnts++. ?><?php echo stripslashes($row_video['attachment_title'])?></a></span></li>
                          <?php		
								}
								?>
                        </ul>
                      </li>
                    <?php
								}
								?>
                  </ul></td>
                </tr>
            </table>
				</div>
				</div>
				<div class="det_pdtA_bottom"></div>
				</div>          
				<?php
		}
		 */ 
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
 <?php
}
// ** Function to show the details of products which are linked with current product.
function Show_Linked_Product($ret_prod)
{
	global $ecom_siteid,$db,$Captions_arr,$Settings_arr;
	// Calling the function to get the type of image to shown for current 
	$pass_type = get_default_imagetype('link_prod');
	$prod_compare_enabled = isProductCompareEnabled();
	
?>
	   <div class="rel_otr">
		<div class="rel_otr_hdr"><?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_PEOPLE_INTERESTED']);?></div>
		<?php
		$cnt = 0;
		while($row_prod = $db->fetch_array($ret_prod))
		{ 
			if($cnt==0)
			{
			?>
			<div class="rel_cat_otr_pdt">
			<?php
		    }
			//echo $cnt;
			if($cnt==4)
			{
		?>      <div class="rel_cat_otr_in_a">
			<?php 
		    }
		    else
		    {
			?>
			     <div class="rel_cat_otr_in">
			<?php	
			}
			?>

			<div class="rel_cat_otr_img">
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
				
				<div class="rel_cat_otr_name"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslash_normal($row_prod['product_name'])?>"><?php echo stripslash_normal($row_prod['product_name'])?></a></div>
			<?php 
			$price_class_arr['ul_class'] 			= 'prodeulprice';
			$price_class_arr['normal_class'] 		= 'rel_cat_otr_priceA';
			$price_class_arr['strike_class'] 		= 'rel_cat_otr_priceC';
			$price_class_arr['yousave_class'] 		= 'rel_cat_otr_priceB';
			$price_class_arr['discount_class'] 		= 'det_priceC';
			echo show_Price($row_prod,$price_class_arr,'prod_detail');
			
			?>
			</div>
			<?php 
			$cnt  ++;
			 if($cnt==4)
			 {
			?>
			</div>
		    <?php
		    $cnt =0;
		     }
		
		}
		if($cnt<3)
		{
			?></div>
			<?php
		}
		?>
		 </div>	  
<?php

}
// ** Function to show the list of products to be compared with current product.
function Show_Compare_Product($ret_prod)
{
	global $ecom_siteid,$db,$Captions_arr,$Settings_arr,$inlineSiteComponents;
	$Captions_arr['PROD_DETAILS'] 	= getCaptions('PROD_DETAILS');
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
		<div class="treemenu">
		<ul>
		<li><a href="<? url_link('');?>" title="<?=stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']);?>"><?=stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']);?></a> &gt;&gt; </li>
		<li><?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_COMPARE_HEAD']);?></li>
		</ul>
		</div>
		<div class="mid_shlf_con" >
		<?php  $msg = stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_MAX_COMP_PROD']); $msg = str_replace('[prodno]',$Settings_arr['no_of_products_to_compare'],$msg); echo $msg?>
		<div class="compare_main_div">
		<div class="compare_back_button">
			<input type="button" name="prodet_backprod" value="<?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_BACK_TO_DETAIL']);?>" class="buttonred_cart" onclick="window.location='<?php url_product($_REQUEST['product_id'],'',-1)?>'"/>
		</div>
		<div class="compare_gobutton">
			<input type="button" name="prodet_comparebutton" value="<?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['COMPARE_PRODUCTS']);?>" class="buttonred_cart" onclick="handle_proddet_compare()"/>
		</div>
		</div>
		<?php
		// Calling the function to get the type of image to shown for current 
		$pass_type = get_default_imagetype('link_prod');
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
	?>
			<div class="mid_shlf_top"></div>
			<div class="mid_shlf_middle">
			<div class="mid_shlf_pdt_name"><input type="checkbox" name="chkproddet_comp_<?php echo $row_prod['product_id']?>" id="chkproddet_comp_<?php echo $row_prod['product_id']?>" value="<?php echo $row_prod['product_id']?>" <?php echo $compare_checked?>/><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslash_normal($row_prod['product_name'])?>"><?php echo stripslash_normal($row_prod['product_name'])?></a></div>
			<div class="mid_shlf_mid">
				<div class="mid_shlf_pdt_image">
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
				<? 
				if($comp_active)
				{
				?>
					<div class="mid_shlf_pdt_compare" >
					<?php	dislplayCompareButton($row_prod['product_id']);?>
					</div>
				<?php	
				}
				?>
				</div>
			</div>
			<div class="mid_shlf_pdt_des">
				<?php
				echo stripslash_normal($row_prod['product_shortdesc']);
				$module_name = 'mod_product_reviews';
				if(in_array($module_name,$inlineSiteComponents))
				{
					if($row_prod['product_averagerating']>=0)
					{
					?>
						<div class="mid_shlf_pdt_rate">
						<?php
							display_rating($row_prod['product_averagerating']);
						?>
						</div>
					<?php
					}
				}	
				if($row_prod['product_bulkdiscount_allowed']=='Y')
				{
				?>
					<div class="mid_shlf_pdt_bulk"><img src="<?php url_site_image('bulk-discount.gif')?>" alt="Bulk Discount"/></div>
				<?php
				}
				if($row_prod['product_saleicon_show']==1)
				{
				$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
				if($desc!='')
				{
				?>	
					<div class="mid_shlf_pdt_sale"><?php echo $desc?></div>
				<?php
				}
				}
				if($row_prod['product_newicon_show']==1)
				{
					$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
					if($desc!='')
					{
					?>
						<div class="mid_shlf_pdt_newsale"><?php echo $desc?></div>
					<?php
					}
				}
				?>
			</div>
			<div class="mid_shlf_pdt_price">
				<?php 
				if($row_prod['product_freedelivery']==1)
				{	
				?>
					<div class="mid_shlf_free"></div>
				<?php
				}
				$price_class_arr['class_type'] 		= 'div';
				$price_class_arr['normal_class'] 	= 'shlf_normalprice';
				$price_class_arr['strike_class'] 	= 'shlf_strikeprice';
				$price_class_arr['yousave_class'] 	= 'shlf_yousaveprice';
				$price_class_arr['discount_class'] 	= 'shlf_discountprice';
				echo show_Price($row_prod,$price_class_arr,'linkprod_1');
				$frm_name = uniqid('linked_');
				?>	
				<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
				<input type="hidden" name="fpurpose" value="" />
				<input type="hidden" name="fproduct_id" value="" />
				<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
				<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
				<div class="mid_shlf_buy">
				<div class="mid_shlf_info_btn"><?php show_moreinfo($row_prod,'mid_shlf_info_link')?></div>
				<div class="mid_shlf_buy_btn">
				<?php
					$class_arr 					= array();
					$class_arr['ADD_TO_CART']	= 'mid_shlf_buy_link';
					$class_arr['PREORDER']		= 'mid_shlf_buy_link';
					$class_arr['ENQUIRE']		= 'mid_shlf_buy_link';
					show_addtocart($row_prod,$class_arr,$frm_name)
				?>
				</div>
				</div>
				</form>       
			</div>
			</div>
			<div class="mid_shlf_bottom"></div>
	<?php
		}
	?>
		
	<div  align="right" class="compare_bottom_btn"><input type="button" name="prodet_comparebutton" value="<?php echo stripslash_normal($Captions_arr['COMMON']['COMPARE_PRODUCTS']);?>" class="buttonred_cart" onclick="handle_proddet_compare()"/>
	</div>
	</div>
<?php
}
// ** Function to display the variables. This is written as function to avoid code repetation in various if conditions
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
         <div class="variable_pdt">
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
					$clss = ($i%2==0)?'var_pdt_l_otr_l':'var_pdt_l_otr_r';
					$var_exists = true;
				?>	 <div class="<?php echo $clss?>">
					<div class="var_pdt_l_txt"><?php echo stripslash_normal($row_var['var_name'])?></div>
					<div class="var_pdt_l_in">
				<?php
					if ($row_var['var_value_exists']==1)
					{		
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
							}/* else
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
										$ret_arr = handle_variable_color_section($row_vals,$first_val,$color_type);	
										
										$show_value			= $ret_arr['show_value'];
										$clr_val 			= $ret_arr['clr_val'];
										$normal_cls 		= $ret_arr['normal_cls'];
										$special_cls 		= $ret_arr['special_cls'];
										
										$normal_cls_sz 		= "size_var_div";
										$special_cls_sz 	= "size_var_div_sel";
										$normal_cls_clrimg 	= "colorimg_div";
										$special_cls_clrimg	= "colorimg_div_sel";
										$normal_cls_clr 	= "color_div";
										$special_cls_clr	= "color_div_sel";	
										$varvaldivid 		= "valdiv_var_".$row_var['var_id']."_".$row_vals['var_value_id'];
										//$onclick_function   = " onclick ='var_onclick(\"".$onclick_var."\",\"var_".$row_var['var_id']."\",".$row_vals['var_value_id'].",\"".$normal_cls."\",\"".$special_cls."\",\"".$docroot."\",\"".$prodid."\",\"".$loading_gif."\")' ";
										$onclick_function   = " onclick ='var_onclick(\"".$onclick_var."\",\"var_".$row_var['var_id']."\",".$row_vals['var_value_id'].",\"".$normal_cls_clr."\",\"".$special_cls_clr."\",\"".$normal_cls_clrimg."\",\"".$special_cls_clrimg."\",\"".$normal_cls_sz."\",\"".$special_cls_sz."\",\"".$docroot."\",\"".$prodid."\",\"".$loading_gif."\")' ";
									?>
									 <div id='<?php echo $varvaldivid?>' class="<?php echo ($first_one==1)?$special_cls:$normal_cls?>" <?php echo $clr_val. ' '.$onclick_function?> title="<?php echo $row_vals['var_value']?>" >
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
							  */ 
						}
						else
						{
						?>
							<input type="checkbox" name="var_<?php echo $row_var['var_id']?>" id="var_<?php echo $row_var['var_id']?>" value="1" <?php echo ($_REQUEST['var_'.$row_var['var_id']])?'checked="checked"':''?>/><?php echo Show_Variable_Additional_Price($row_prod,$row_var['var_price'],$vardisp_type)?>
						<?php
						}
						?>
					</div>
					</div>
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
				if($i%2==0)
					{
					$clss='productvariabletdA';
					$clss2='productvariabletdAA';
					}
					else
					{
					$clss='productvariabletdB';
					$clss2='productvariabletdBB';
					}
				//$clss = ($i%2==0)?'productvariabletdA':'productvariabletdB';
				$var_exists = true;
			?>
					 <div class="var_pdt_l_txt"><?php echo stripslash_normal($row_msg['message_title'])?></div>
					 <div class="var_pdt_l_in">
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
					</div>
			<?php
				$i++;
			}
		}
		// ######################################################
		// End of variable messages
		// ######################################################
		?>
		</div>
		<?php
		/*
		if($Settings_arr['showsizechart_in_popup']==1 and $sizechart_heading['size_Availvalues']==true) // If size chart is set to show in a pop up window
		{
			if($i%2==0)
			{
				$clss='productvariabletdA';
				$clss2='productvariabletdAA';
			}
			else
			{
				$clss='productvariabletdB';
				$clss2='productvariabletdBB';
			}
		 <table width="100%" border="0" cellpadding="0" cellspacing="0" class="variabletable" id="proddet_var_table">
			<tr>
					<td align="right" valign="top" class="<?php echo $clss?>" colspan="2">
			<div class="show_sizechart_popup_div">
			<a href="javascript:showsizechartPopup('<?php echo $row_prod['product_id']?>','<?php echo $ecom_hostname?>','<?php echo $ecom_themename?>')" title="<?php echo stripslash_normal($sizechartmain_title)?>">size chart<?php /*?><img src="<?php url_site_image('size-chart.gif')?>" alt="<?php echo stripslash_normal($sizechartmain_title)?>" border="0" /><?php */?></a>
			<?php
			/*
			?>
			</div>
			</td>
			</tr>
			</table>
		}
		*/
	}
	 if($sizechart_heading['size_Availvalues']==false && $row_prod['product_commonsizechart_link']!='')
		{
		?>  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="variabletable" id="proddet_var_table">
				<tr>
					<td align="right" valign="top" class="<?php echo $clss?>" colspan="2">
					   <div class="show_sizechart_popup_div">
						<a href="<?=$row_prod['product_commonsizechart_link']?>" title="SizeChart" 	target="<?=$row_prod['produt_common_sizechart_target']?>">size chart</a>
						</div>
					</td>
				</tr>
			</table>
		 <?
		}
	return $var_exists;
}
function show_product_downloads($prod_id)
{
	global $db,$ecom_siteid,$Captions_arr,$ecom_hostname,$Settings_arr;
	
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
		 <div class='variable_pdt' style="padding-left:12px">
		 <div class="var_pdt_l_otr_r_new">
		<div class="deat_conts_hdr"><span><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_DOWNLOADS'];?></span></div>
		<?php
		$cnts = 1;
		while ($row_attach = $db->fetch_array($ret_attach))
		{
		?>
			<div class="var_pdt_l_in_new" style="float:left"><img src='<?php url_site_image('pdf_prod.gif')?>' style="padding-right:5px;"><a href="http://<?php echo $ecom_hostname?>/product_download.php?attach_id=<?php echo $row_attach['attachment_id']?>" title="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_CLICKDOWNLOAD'];?>"><?php echo stripslashes($row_attach['attachment_title'])?></a></div>
		<?php
        $cnts++;
		}
		?>
		</div>
		</div>
	<?php
	}
}
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
		<div class="bulk_con">
		<div class="bulk_top"><?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['BULK_DISC_HEAD'])?></div>
		<div class="bulk_bottom">
		<div class="bulk_inner">
		<?php
		for($i=0;$i<count($bulkdisc_details['qty']);$i++)
		{
			if($i>0)
				echo "<br>";
			echo $bulkdisc_details['qty'][$i].' '.stripslash_normal($Captions_arr['PROD_DETAILS']['BULK_FOR']).' ';
			//echo print_price($bulkdisc_details['price'][$i]).' '.stripslash_normal($Captions_arr['PROD_DETAILS']['BULK_EACH']);
			echo product_BulkDiscount_Build_Price($bulkdisc_details['price'][$i],$bulkdisc_details['price_without'][$i]).' '.stripslash_normal($Captions_arr['PROD_DETAILS']['BULK_EACH']);
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
				<div  class="div_alert" id="instockmsg_div">
				<div align="right" class="instockmsg_span" ><a href="javascript: hide_instockmsg_div()"><img src="<?php url_site_image('close.gif')?>" border="0" /></a></div>
					<?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODUCT_OUT_STOCK'])?>.
				 <br />
				<span class="instockmsg_out_stock">
				<?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODUCT_OUT_STOCK_EMAIL'])?></span>
				<input type="text"   name="stock_email" />
				<input type="hidden" name="prod_mod" value="stock_notify" />
				<input type="hidden" name="hid_notify" value="stock" /> 
				<input type="button" name="stocknotif_submit" value="Send" class="buttongray" onclick=" validate_stocknotify(document.frm_proddetails)"  />
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
					<input type="button" name="stocknotif_submit" value="Send" class="buttongray" onclick=" validate_stocknotify(document.frm_proddetails)"  />
					<input type="hidden" name="product_id" value="<?PHP echo $_REQUEST['product_id']; ?>" />
				</span>
				</div>
	<?php
			}		
		}
	}
}
		
/* Function to show the lables set for the product */
function show_ProductLabels($prod_id)
{
	global $db,$ecom_siteid,$Captions_arr;
	$display_ok = false;
	$ret_val = '';
	// Check whether labels exists for current product
	$cats_arr = $grp_arr = array();
	// Get the categories that area linked with current product
	$sql_cats = "SELECT product_categories_category_id 
					FROM 
						product_category_map 
					WHERE 
						products_product_id = $prod_id";
	$ret_cats = $db->query($sql_cats);
	if($db->num_rows($ret_cats))
	{
		while ($row_cats = $db->fetch_array($ret_cats))
		{
			$cats_arr[] = $row_cats['product_categories_category_id'];
		}
		$sql_grps = "SELECT DISTINCT product_labels_group_group_id  
						FROM 
							product_category_product_labels_group_map a, product_labels_group b 
						WHERE 
							a.product_labels_group_group_id = b.group_id 
							AND b.group_hide = 0 
							AND product_categories_category_id IN (".implode(',',$cats_arr).") ";
		$ret_grps = $db->query($sql_grps);
		if($db->num_rows($ret_grps))
		{
			while ($row_grps = $db->fetch_array($ret_grps))
			{
				$grp_arr[] = $row_grps['product_labels_group_group_id'];
			}	
			// Check whether there exists atleast one label to display
			$sql_lblcheck = "SELECT a.map_id 
								FROM 
									product_labels_group_label_map a , product_labels_group b
								WHERE 
									product_labels_group_group_id IN (".implode(',',$grp_arr).") 
									AND a.product_labels_group_group_id=b.group_id 
									AND b.group_hide = 0 
								LIMIT 
									2";
			$ret_lblcheck 	= $db->query($sql_lblcheck);
			$grp_nos		= $db->num_rows($ret_lblcheck);
			if($grp_nos)
			{
				// Get the product label group details in order
				$sql_grp = "SELECT group_id,group_name,group_name_hide  
								FROM 
									product_labels_group 
								WHERE 
									group_id IN (".implode(',',$grp_arr).") 
								ORDER BY 
									group_order";
				$ret_grp = $db->query($sql_grp);
				if($db->num_rows($ret_grp))
				{
					$disp_val = ($_REQUEST['prodmod']=='productlabels')?'':'none';
					$ret_val = '<div class="det_pdtA_tab_cnts" id="label_content_div" style="display:'.$disp_val.'">
								<div class="det_overview">'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_KEY_FEATURES']).'</div>
								<table width="100%" border="0" cellpadding="0" cellspacing="0" class="keyfeature" id="proddet_var_table">';
				
					$i=1;
					$grp_cnt = 0;
					$label_arr = array();
					while ($row_grp = $db->fetch_array($ret_grp))
					{
						// Check whether there exists atleast one label under this group to display
						$sql_labels = "SELECT a.label_id,a.label_name,a.in_search,a.is_textbox,c.product_site_labels_values_label_value_id,c.label_value 
											FROM 
												product_site_labels a,product_labels_group_label_map b,product_labels c
											WHERE 
												b.product_labels_group_group_id = ".$row_grp['group_id']." 
												AND c.products_product_id = $prod_id
												AND a.label_id = b.product_site_labels_label_id 
												AND a.label_id = c.product_site_labels_label_id 
												AND a.label_hide = 0 
												AND (c.product_site_labels_values_label_value_id>0 OR  label_value <> '')
											ORDER BY 
												b.map_order";
						$ret_labels = $db->query($sql_labels);
						if($db->num_rows($ret_labels))
						{
							$grp_cnt++;
							$label_arr[$row_grp['group_name'].'~'.$row_grp['group_name_hide']] = array();
							while ($row_labels = $db->fetch_array($ret_labels))
							{
								$vals = '';
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
								}
							}	
						}
					}
					if(count($label_arr))
					{
						$display_ok = true;
						$prev_grp = '';
						foreach ($label_arr as $k=>$v)
						{
							if($prev_grp!=$k)
							{
								$gname_arr = explode('~',$k);
								// Show the name only if it is not made hidden
								if($gname_arr[1]==0)
								{
									$ret_val .='<tr>
													<td class="keyfeatureHeading" align="left" colspan="2">'.$gname_arr[0].'</td>
												</tr>';
								}			
							}	
							if(is_array($v))
							{
								if(count($v))
								{
									for($i=0;$i<count($v);$i++)
									{
										$clss = ($i%2==0)?'keyfeatureA':'keyfeatureB';
										$ret_val .= '
													<tr>
														<td align="left" valign="middle" class="'.$clss.'">'.$v[$i]['name'].'</td>
														<td align="left" valign="middle" class="'.$clss.'">:&nbsp;'.$v[$i]['val'].'</td>
													</tr>';		
									}
								}
							}
						}
					}	
					$ret_val .= '</table>
								</div>
								';	
				}
			}
		}	
	}
	if($display_ok==false)
		$ret_val = '';
	return  $ret_val ;	
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
      <div class="det_pdt_buy">
   <div class="deat_pdt_buyB">
		<div class="deat_pdt_buyBinner">
		<?php	
	if($showqty==1)// this decision is made in the main shop settings
	{
		if($row_prod['product_det_qty_type']=='NOR')
		{
?>
<div class="buyBinner_qty">Qty</div>

			<div class="buyBinner_txt"><input type="text" class="det_qty_txt" name="qty"  value="<?php echo ($_REQUEST['qty'])?$_REQUEST['qty']:1?>" maxlength="2" /></div>
<?php
		}
		/*
		elseif($row_prod['product_det_qty_type']=='DROP')
		{
			$dropdown_values = explode(',',stripslash_normal($row_prod['product_det_qty_drop_values']));
			if (count($dropdown_values) and stripslash_normal($row_prod['product_det_qty_drop_values'])!='')
			{
			?>
				<div>
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
		*/ 
	}
?>
		<div class="buyBinner_link"><a href="#" class="det_buy_link" onclick="show_wait_button(this,'Please Wait...');document.frm_proddetails.fpurpose.value='Prod_Addcart';document.frm_proddetails.submit();"><?php echo stripslash_normal($Captions_arr['PROD_DETAILS'][$caption_key]);?></a></div>
</div>
</div>
   </div> 
<?php
	}	
	return true;
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
		<div class="zoom_con"> 
		<div class="zoom_top">Zoom </div>
		<div class="zoom_middle">
		<?php
		$tabstr		= ($_REQUEST['prod_curtab'])?'&amp;prod_curtab='.$_REQUEST['prod_curtab']:'';
		foreach ($prodimg_arr as $k=>$v)
		{ 
			$title = ($v['image_title'])?stripslash_normal($v['image_title']):$row_prod['product_name'];
		?>
			<a href="<?php url_root_image($v['image_extralargepath'])?>" rel='lightbox[gallery]' title="<?=$title?>">
			<?php
				 show_image(url_root_image($v['image_iconpath'],1),$title,$title,'preview');
			?>
			</a>
		<?php
		}
		?>	
		</div>
		<div class="zoom_bottom"> </div>
		</div>	
<?php
	}
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
function Show_Size_chart($heading,$cnt_hd,$sizevalue)
{
	global $db,$ecomn_siteid,$ecom_hostname,$ecom_themename;
	?>
	<table width="100%" border="0" cellpadding="2" cellspacing="0">
	<tr>
	<?php 
	foreach($heading AS $val)
	{ 
	?>
		<td align="center"  class="productsizechartheading" ><?PHP echo $val; ?></td>
	<?php
	} 
	?>
	</tr>
	 <?php 
	for($i=0; $i<$cnt_hd; $i++)
	{
		$cls = ($cls=='productsizechartvalueA')?'productsizechartvalueB':'productsizechartvalueA';
	?>
    <tr>
    <?php
	foreach($sizevalue as $k=>$v)
	{
	?>
      <td class="<?php echo $cls; ?>" align="center" ><?PHP echo ($sizevalue[$k][$i])?$sizevalue[$k][$i]:'-'; ?></td>
    <?php
	} 
	?>
    </tr>
    <? 
	}
	?>
	</table>
		 <?php
}
};	
?>
