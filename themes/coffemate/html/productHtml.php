<?php
/*############################################################################
# Script Name 		: productHtml.php
# Description 		: Page which holds the display logic for product details
# Coded by 			: Sny
# Created on		: 22-Jan-2008
# Modified by		: Sny
# Modified On		: 04-Aug-2008

##########################################################################*/
class product_Html
{
// Defining function to show the selected product details
function Show_ProductDetails($row_prod)
{
		global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$ecom_themename,
			$ecom_themeid,$default_layout,$inlineSiteComponents,$Settings_arr,$meta_arr,$ecom_twitteraccountId,$mobile_browser;
		$addto_cart_withajax =  $settings_arr['enable_ajax_in_site'];
		$enable_special_type_display    = $Settings_arr['proddet_special_display'];
		
		
           
		// ** Fetch any captions for product details page
		$Captions_arr['PROD_DETAILS'] 	= getCaptions('PROD_DETAILS');
		$Captions_arr['PRODUCT_REVIEWS'] 	= getCaptions('PRODUCT_REVIEWS');
		

		// ** Fetch the product details
		//$row_prod	= $db->fetch_array($ret_prod);
		// ** Check to see whether current user is logged in 
		$cust_id 	= get_session_var("ecom_login_customer");

		// ** Check whether qty box is to be shown
		$showqty	= $Settings_arr['show_qty_box'];// show the qty box
		if($_REQUEST['result']=='exists')
		{
			//$Captions_arr['PROD_DETAILS']['PRODDET_ADDFAVADDED']
			$alert = $Captions_arr['PROD_DETAILS']['PRODDET_ADDFAVEXISTS'];
		}
		else if($_REQUEST['result']=='added')
		{
			//$Captions_arr['PROD_DETAILS']['PRODDET_ADDFAVEXISTS']
			$alert = $Captions_arr['PROD_DETAILS']['PRODDET_ADDFAVADDED'];
		}
		else if($_REQUEST['result']=='removed')
		{
			$alert = $Captions_arr['PROD_DETAILS']['PRODDET_ADDFAVREMOVED'];
		}
			$prod_img_show_type =$row_prod['product_details_image_type'];
		/*	
		?>
		<script type="text/javascript" src="http://<? echo $ecom_hostname?>/images/<? echo $ecom_hostname?>/scripts/tinybox.js"></script>
		*/
		?> 
			<script type="text/javascript">
		function showmask_proddet(id)
		{
			objs = eval('document.getElementById("catprodmask_'+id+'")');
			objs.style.display = 'block';
		}
		function hidemask_proddet(id)
		{
			objs = eval('document.getElementById("catprodmask_'+id+'")');
			objs.style.display = 'none';
		}
		</script>
		
		<script type="text/javascript">
		function show_pdf_new(url)
		{
			var winxx = window.open(url,'pdf_window','width=600,height=600,top=100,left=200,location=no',false);
			winxx.focus();
		}	
			
		function ajax_return_productdetailscontents() 
		{
			if(document.getElementById('how_displayed'))
				var how_displayed = document.getElementById('how_displayed').value;
			else
				var how_displayed = '';	
			if(how_displayed!='proddet_ajax_list')
			{ 
				//alert('insert if');
				var ret_val = '';
				var disp 	= 'no';
				var docroot = '<?php echo SITE_URL?>';
				var prod_id	= <?php echo $_REQUEST['product_id']?>;
				var loading_gif = '<?php echo url_site_image('loading.gif',1)?>';
				if(req.readyState==4)
				{
					if(req.status==200)
					{ 
						ret_val 		= req.responseText;
						targetobj 	= eval("document.getElementById('"+document.getElementById('ajax_div_holder').value+"')");
						targetobj.innerHTML = ret_val; /* Setting the output to required div */
					
					$j("#content-2").mThumbnailScroller({
					axis:"y",
					type:"click-70",
					speed:30,
					theme:"buttons-out"
					});	
					
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
						if(document.getElementById('ajax_div_holder').value=='cart_details_ajaxholder')
						{
							//alert('proddet*'+docroot);
							ajax_addto_cart_fromlist('show_shop_cart','','',docroot);
							show_ajax_holder('cart');
							hideme('#proddet_loading_div_ajax');

						}
							hide_loading('proddet_loading_div');
							if(document.getElementById('variable_linka_loading_div'))
							{
								/*hide_loading('variable_linka_loading_div');*/
							}
							
					}
					else
					{
					hide_loading('proddet_loading_div');
					/*alert(req.status);*/
					}
				}
			}	
			else
			{
				var ret_val = '';
				var disp 	= 'no';
				var docroot = '<?php echo SITE_URL?>';
				//var prod_id	= <?php echo $_REQUEST['product_id']?>;
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
						
						$j("#content-2").mThumbnailScroller({
					axis:"y",
					type:"click-70",
					speed:30,
					theme:"buttons-out"
					});	
						if(document.getElementById('ajax_div_holder').value=='price_holder')
						{
							handle_show_prod_det_bulk_disc('bulk',docroot,prod_id,loading_gif,mod);
							//hideme('#proddet_loading_div_ajaxinner');

						}					
						else if(document.getElementById('ajax_div_holder').value=='bulkdisc_holder')
						{ 
							//if(document.getElementById('comb_img_allowed').value=='Y' ) /* Do the following only if variable combination image option is set for current product */ 
							handle_show_prod_det_bulk_disc('main_img',docroot,prod_id,loading_gif,mod);
							//hideme('#proddet_loading_div_ajaxinner');
						}	
                        else if(document.getElementById('ajax_div_holder').value=='mainimage_holder')
						{ 
								handle_show_prod_det_bulk_disc('more_img',docroot,prod_id,loading_gif,mod);

								//hideme('#proddet_loading_div_ajaxinner');
						}
						if(document.getElementById('ajax_div_holder').value=='cart_details_ajaxholder')
						{
							ajax_addto_cart_fromlist('show_shop_cart','','',docroot);
							show_ajax_holder('cart');
						}				
						if(document.getElementById('ajax_div_holder').value=='prod_details_ajaxholder')
						{
							show_ajax_holder('prod');
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
		if($Settings_arr['javascript_jquery']==1)
		{
			echo "jQuery.noConflict(); /* This is done to avoid error in light box due to the usage of $ in jquery*/";
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
		
		function ajax_addto_cart11(mod)
		{
			if(mod=='show_det')
			{
				var fpurpose = 'Prod_Addcart';
				var qrystr = '';
				for(i=0;i<document.frm_proddetails.elements.length;i++)
				{
				qrystr += '&'+document.frm_proddetails.elements[i].name+'='+document.frm_proddetails.elements[i].value;
				}
				var retdivid									= 'cart_details_ajaxholder';
			}
			else if(mod=='show_shop_cart')
			{
				var fpurpose = 'Shopping_cart';
				var qrystr = '';				
				var retdivid									= 'shoppingcart_ajax_container_outer';
			} 
			qrystr += '&position=top';
			document.getElementById('ajax_div_holder').value = retdivid;
			retobj 											= eval("document.getElementById('"+retdivid+"')");	
			Handlewith_Ajax('<?php echo $ORG_DOCROOT?>/includes/base_files/add_tocart_ajax.php','ajax_fpurpose='+fpurpose+'&'+qrystr);

		}
		function show_finance_example()
		{
			if (document.getElementById('det_fin_example_div'))
			{
				if(document.getElementById('det_fin_example_div').style.display=='none')
				{
					document.getElementById('det_fin_example_div').style.display='';
				}	
				else
				{
					document.getElementById('det_fin_example_div').style.display='none';
				}	
				
			}
		}
		
		</script>
		<?php
		if(in_array('mod_emailafriend',$inlineSiteComponents) and $Settings_arr['proddet_showemailfriend']==1)
		$email_show = 1; // email a friend link
		if($Settings_arr['proddet_showwritereview']==1)
		$writereview_show = 1;	// write review link
		if($Settings_arr['proddet_showreadreview']==1)
		$readreview_show = 1; // read review link
		if(in_array('mod_downloadpdf',$inlineSiteComponents) and $Settings_arr['proddet_showpdf']==1)
		$pdf_show				= 1;	 // pdf download link
		$tabs_arr		= $tabs_cont_arr	= array();
			$docroot			= SITE_URL;
			$prodid				= $_REQUEST['product_id'];
			$loading_gif		= url_site_image('loading.gif',1);
			$tabs_content_arr = array();
		if($row_prod['product_longdesc'])
		$tabs_content_arr[0]	= stripslashes($row_prod['product_longdesc']);
		elseif ($row_prod['product_shortdesc'])
		$tabs_content_arr[0]	= stripslashes($row_prod['product_shortdesc']);	
		   if (count($tabs_content_arr))
			{
				$tabs_arr 			= array (0=>'Overview');
				$tabs_arr_onclick[0]= "show_curtab_content('ultab_0','".$docroot."',".$prodid.",'".$loading_gif."')";
			}	
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
		$label_val = $this->show_ProductLabels($prodid);
			if (trim($label_val)!='')
			{
				$tabs_arr[-1] 			= stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_KEY_FEATURES']);
				$tabs_content_arr[-1]	= $label_val;
				$tabs_arr_onclick[-1]	= "show_curtab_content('ultab_-1','".$docroot."',".$prodid.",'".$loading_gif."')";
			}
		if($writereview_show == 1 or $readreview_show == 1)
			{
				$sql_prodreview	= "SELECT review_id
										review_author,review_rating,review_details 
									FROM  
										 product_reviews 
									WHERE  
										sites_site_id = $ecom_siteid
										AND products_product_id  =  ".$_REQUEST['product_id']."
										AND review_status = 'APPROVED'  
										AND review_hide=0 
									LIMIT 1";
				$ret_prodreview = $db->query($sql_prodreview);
				if($db->num_rows($ret_prodreview))
				{ 
					for($i=1;$i<=$row_prod['product_averagerating'];$i++)
					{
						$review_stars .='<img src="'.url_site_image('star-green.png',1).'"  />';
					}
					$tabs_arr[-4] 			= 'Reviews ';//stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_SIZECHART_HEAD']);
					$tabs_content_arr[-4]	= 'reviews';
					$tabs_arr_onclick[-4]	= "show_curtab_content('ultab_-4','".$docroot."',".$prodid.",'".$loading_gif."')";
				}
				else
				{
					
					$tabs_arr[-4] 			= 'Reviews ';//stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_SIZECHART_HEAD']);
					$tabs_content_arr[-4]	= 'reviews';
					$tabs_arr_onclick[-4]	= "show_curtab_content('ultab_-4','".$docroot."',".$prodid.",'".$loading_gif."')";	
				}
					$tabs_arr[-4] .="<a href='#' id='review'></a>";
			}
		$HTML_loading = '<div class="proddet_loading_outer_div" style="height:15px"><div id="proddet_loading_div" style="display:none;padding:5px 0 0 0;">
		<img src="'.url_site_image('proddet_loading.gif',1).'" alt="loading..." width="43px" height="11px;align:left">
		</div></div>';	

					

		?>
		

		
			<div class="detail-contentwrap" id="detail-contentwrap">
				<form method="post" name="frm_proddetails" id="frm_proddetails" action="<?php url_link('manage_products.html')?>" class="frm_cls" onsubmit="return prod_detail_submit(this)">
		<input type="hidden" name="fpurpose" value="" />
		<input type="hidden" name="fproduct_id" value="<?php echo $_REQUEST['product_id']?>" />
		<input type="hidden" name="pricepromise_url" value="<?php url_link('pricepromise'.$row_prod['product_id'].'.html')?>" />
		<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
		<input type="hidden" name="pass_combid" value="<?php echo $_REQUEST['pass_combid']?>" />
		<input type="hidden" name="comb_img_allowed" id="comb_img_allowed" value="<?php echo $row_prod['product_variablecombocommon_image_allowed']?>" />
		<input type="hidden" name="ajax_div_holder" id="ajax_div_holder" value="" />
		<input type="hidden" name="pagetype" id="pagetype" value="" />
						<div class="treemenu"><?php echo generate_tree(-1,$_REQUEST['product_id'])?></div>

				<?php
				$this->Show_links($row_prod);
				?>
			<div class="prod_det_name">
			<div class="prod_det_name_link">

			<?php	echo '<h1>'.stripslashes($row_prod['product_name']).'</h1>';

			if($_REQUEST['for_notification']==1) // make the decision whether the instock message is to be displayed here or in div
			{	
			$this->show_Instock_msg($row_prod,$_REQUEST['stockalert']);	}
			else // case if displaying the instock notification message here itself
			{	
			$this->show_Instock_msg($row_prod,$_REQUEST['stockalert']);	}
			?>			</div>
			<?php

			//$this->show_offer_buttons($row_prod);	
			?>
			</div>
			<?php
			$pass_type = get_default_imagetype('proddet');
				//$pass_type  = 'image_thumbpath';
				// Calling the function to get the image to be shown
				if($row_prod['product_variablecombocommon_image_allowed']=='Y')
				$prodimg_arr1 = get_imagelist_combination($row_prod['default_comb_id'],$pass_type,0,1);
				else
				$prodimg_arr1 = get_imagelist('prod',$row_prod['product_id'],$pass_type,0,$showonly,1);
				//print_r($prodimg_arr1);
				/*if(count($prodimg_arr1))
				{
					?>
					<div class="prod_det_zoom"><a href="<?php url_root_image($prodimg_arr1[0]['image_extralargepath'])?>"
					rel='lightbox[gallery]' title="<?=$row_prod['product_name']?>">Click Here to Enlarge</a></div>
					<?php
				}*/
				?>
				<?=$HTML_loading?>
				<?php
				$exclude_tabid			= $ret_arr['exclude_tabid'];
				$exclude_prodid			= $ret_arr['exclude_prodid'];
			
			global $load_mobile_theme_arr;
			if($load_mobile_theme_arr[0]==1)
			{
				$zoomtype = '';
			}
			else
			{
				//$zoomtype = 'zoomType: "lens",';
			}	
	
			
			
			?>

			<div id="moreimage_holder">


			<?php                       
			// Showing additional images
			$this->show_more_images($row_prod,$exclude_tabid,$exclude_prodid);
			
			
			
			?>
			</div>

			
				<div class="detail_big_img_wrap" >

			<div id="mainimage_holder">

			<?php
			$this->Show_Image_Normal($row_prod); 
			?>
			</div>
			</div>
			<style type="text">
			

body{margin:0px;padding:0px;font-family:Arial;}
a img,:link img,:visited img { border: none; }
table { border-collapse: collapse; border-spacing: 0; }
:focus { outline: none; }
*{margin:0;padding:0;}
p, blockquote, dd, dt{margin:0 0 8px 0;line-height:1.5em;}
fieldset {padding:0px;padding-left:7px;padding-right:7px;padding-bottom:7px;}
fieldset legend{margin-left:15px;padding-left:3px;padding-right:3px;color:#333;}
dl dd{margin:0px;}
dl dt{}

.clearfix:after{clear:both;content:".";display:block;font-size:0;height:0;line-height:0;visibility:hidden;}
.clearfix{display:block;zoom:1}


ul#thumblist{display:block;}
ul#thumblist li{float:left;margin-right:2px;list-style:none;}
ul#thumblist li a{display:block;border:1px solid #CCC;}
ul#thumblist li a.zoomThumbActive{
    border:1px solid red;
}

.jqzoom{

	text-decoration:none;
	float:left;
}





			</style>
			
			<div class="detail_price-wrap">
			<div id="price_holder">

					<?php
				$price_class_arr['ul_class'] 	= 'shelfBul_three_column';
				$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
				$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
				$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
				$price_class_arr['discount_class'] 	= 'productdetdiscountprice';;
				$price_class_arr['emi_class'] 		= 'emi_price_details';
				echo show_Price($row_prod,$price_class_arr,'prod_detail');
			   ?>
			   </div>
			   <div id="bulkdisc_holder">

			      <?php
				$this->show_BulkDiscounts($row_prod); 
                ?>
                </div>
                <?php
				$var_listed = $this->show_ProductVariables($row_prod,'column');
				
				$this->show_bonus_points($row_prod);
?>
<?php
			$this->offer_buttons($row_prod);
			?>
			<?php
				$this->show_buttons($row_prod);
				?>	
						
			</div>
			</form>

<?php
if (count($tabs_arr))
		{
			$curtab 		= ($_REQUEST['prod_curtab'])?$_REQUEST['prod_curtab']:0;
			?>
				<table class="overview_table" border="0" cellpadding="0" cellspacing="0">
				<tr>
				<td  class="overview_table_hdr_tab" id="protabs">
				<?php		
				//$curtab 		= ($_REQUEST['prod_curtab'])?$_REQUEST['prod_curtab']:0;
				$prodimgdetid 	= (!$_REQUEST['prodimgdet'])?0:$_REQUEST['prodimgdet'];
				$class = 'selectedtab_sel';
				$cnt=0;
				foreach($tabs_arr as $k_tabid=>$v_tabtitle)
				{
						$cnt++;
						$sel = ($k_tabid == $curtab)?'selectedtab_sel':'selectedtab_unsel';											
			    ?>
				<div class="<?php echo $sel?>" onclick="<?php echo $tabs_arr_onclick[$k_tabid]?>" id="tabhead_<?php echo $k_tabid?>"> <?php echo $v_tabtitle?></div>
				<?php
				}
				//if($curtab==0)
				//$curtab=$sel_tabid;
				?>		
				</td>
				</tr>
				<tr>
				<td  class="overview_table_cont">					
					<?php
					if($curtab == -4)
					{
					require("themes/$ecom_themename/html/ajax/productdetail_ajax.php");
					$content_rev = show_product_reviews($_REQUEST['product_id'],$id='ultab_-7');	
					 echo $content_rev;
					}
				$sr_arr = array('<font size="1">','font-size: 10px;','<FONT size="1">','FONT-SIZE: 10px;','FONT-SIZE: 10px','<FONT face=StoneSans-Semibold size=1>','FONT size=1','<FONT face=tahoma,arial,sans-serif color=#000000 size=1>','<h6>','</h6>','<h2>','<H6>','</H6>','<H2>','<p>','<P>','margin-bottom: 110px','MARGIN-BOTTOM: 110px');
				$rp_arr = array('<font size="2">','font-size: 12px;','<font size="2">','font-size: 12px;','font-size: 12px;','<font face="StoneSans-Semibold" size="2">','font size=2','<font face=tahoma,arial,sans-serif color=#000000 size=2>','','','<h2 style="display:block; width:100%;padding-top:10px">','','','<h2 style="display:block; width:100%;float:left;padding-top:10px">','<p><br/>','<p><br/>','','');
				foreach ($tabs_content_arr as $k_tabcontid=>$v_tabcontent)
				{
					if ($k_tabcontid==0 && $curtab==0)
						$display = "style=\"display:\"";
					else
						$display = "style=\"display:none\"";
						
					//$disp_content = str_replace($sr_arr,$rp_arr,$v_tabcontent);
					$disp_content = $v_tabcontent;
				?>
					<div id='ultab_<?php echo $k_tabcontid?>' class="deat_tab_conts" <?=$display?>><? echo $disp_content?></div>
				<?php
				}
				?>
					</td>
				</tr>
				</table>
				<?php	
		}
			// ** Check whether any linked products exists for current product
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
		}
		global $cookieval;
		global $components;
			$position_rec = 'proddetail';
			include(ORG_DOCROOT."/themes/$ecom_themename/modules/mod_recentlyviewedproduct.php");
			?>
			</div>
			<?php
		

	
}
//function to show the links like write review 
function Show_links($row_prod)
{
global $ecom_siteid,$db,$Captions_arr,$Settings_arr,$inlineSiteComponents;	

		// Section which decides which all section to be displayed 
		$email_show 		= 0;
		$favourite_show		= 0;
		$writereview_show	= 0;
		$readreview_show	= 0;
		$pdf_show			= 0;
		$compare_show		= 0;
		if(isProductCompareEnabledInProductDetails())
		{
			$def_cat_id = $row_prod['product_default_category_id'];
			$sql_comp 	= "SELECT 
								a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
								a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
								a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
								a.product_total_preorder_allowed,a.product_applytax,a.product_shortdesc,a.product_variable_display_type,
								a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,
								a.product_variablesaddonprice_exists,
								a.product_variablecomboprice_allowed,a.product_variablecombocommon_image_allowed,a.default_comb_id,
								a.price_normalprefix,a.price_normalsuffix, a.price_fromprefix, a.price_fromsuffix,price_specialofferprefix,  a.price_specialoffersuffix, 
								a.price_discountprefix, a.price_discountsuffix, a.price_yousaveprefix, a.price_yousavesuffix,a.price_noprice,
								a.product_averagerating,a.product_saleicon_show,a.product_saleicon_text,a.product_newicon_show,a.product_newicon_text,
								a.product_freedelivery       
							FROM 
								products a,product_category_map b 
							WHERE 
								b.product_categories_category_id=".$def_cat_id." 
							AND 
								a.product_id <>".$_REQUEST['product_id']." 		
							AND 
								a.sites_site_id=$ecom_siteid 
							AND 
								a.product_id = b.products_product_id  	
							ORDER BY 
								b.product_order 
							LIMIT 1";
			$ret_comp_prod = $db->query($sql_comp);
			if($db->num_rows($ret_comp_prod))
			{
				$compare_show = 1; // compare link
			}	
		}	
			$docroot			= SITE_URL;
			$prodid				= $_REQUEST['product_id'];
			$loading_gif		= url_site_image('loading.gif',1);
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
?>
<div class="productdet_links">
			<?php
			if($readreview_show==1)
			{
				$review_first = '';
$sql_prodreview	= "SELECT DATE_FORMAT(review_date,'%d-%b-%Y') as rvdate,review_id,review_author,review_rating,review_details 
									FROM  
										 product_reviews 
									WHERE  
										sites_site_id = $ecom_siteid
										AND products_product_id  =  ".$_REQUEST['product_id']."
										AND review_status = 'APPROVED'  
										AND review_hide=0 
										ORDER BY review_rating DESC 
									LIMIT 6";
$ret_prodreview = $db->query($sql_prodreview);
$num_reviews    = $db->num_rows($ret_prodreview); 
if($num_reviews==1)
{
  $rev_capt = "Review";
}
else if($num_reviews>1)
{
   $rev_capt = "Reviews";
}
if($db->num_rows($ret_prodreview))
{
      $review_stars = '';
		for($i=1;$i<=$row_prod['product_averagerating'];$i++)
		{
			$review_stars .='<img src="'.url_site_image('star-green.png',1).'"  />';
		}
		$onclick = "show_curtab_content('ultab_-4','".$docroot."',".$prodid.",'".$loading_gif."')"
?>
			<?php echo  $review_stars;?><a href="#tabhead_-4" class="productdetailslink" onclick="<?php echo $onclick?>"><?php echo $num_reviews." ".$rev_capt;?></a>
			<?php
			}
		}	
			// Check whether the compare feature is enabled in the product details page 	
			/*if( $compare_show==1)
			{
				$def_cat_id = $row_prod['product_default_category_id'];

				?>
				<a href="<?php url_productcompare($_REQUEST['product_id'],$row_prod['product_name'])?>" class="productdetailslink"><? echo $Captions_arr['PROD_DETAILS']['PRODDET_COMPARE_LINK'] ?></a>
				<?php
			}
			*/ 
			/*
			if($favourite_show==1) // ** Show the add to favourite only if logged in 
			{
				$sql_prod = "SELECT products_product_id FROM customer_fav_products WHERE sites_site_id=$ecom_siteid AND products_product_id=".$_REQUEST['product_id']." AND customer_customer_id=$cust_id LIMIT 1";
				$ret_num= $db->query($sql_prod); 
				if($db->num_rows($ret_num)==0) 
				{ 
					?>				<a href="#" class="productdetailslink" onclick="if(confirm('<? echo $Captions_arr['PROD_DETAILS']['PRODDET_ADD_CONFIRM'] ?>')){document.frm_proddetails.fpurpose.value='add_favourite';document.frm_proddetails.submit();}"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_FAVOURITE'];?></a>
					<?php
				}
				else
				{
					?>				<a href="#" class="productdetailslink" onclick="if(confirm('<? echo $Captions_arr['PROD_DETAILS']['PRODDET_REM_CONFIRM'] ?>')){document.frm_proddetails.fpurpose.value='remove_favourite';document.frm_proddetails.submit();}"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_REMFAVOURITE'];?></a>
					<?php
				}
			}
			*/ 
			if($email_show==1) // Check whether the email a friend module is there for current site
			{
				?>				<a href="<?php url_link('emailafriend'.$_REQUEST['product_id'].'.html')?>" class="productdetailslink"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_EMAILFRIEND'];?></a>
				<?php
			}
			
			if($pdf_show==1) // Check whether the download pdf module is there for current site
			{  
				?>				<a href="javascript:download_pdf_common('<?php echo $_SERVER['HTTP_HOST']?>','<?php echo $_SERVER['REQUEST_URI']?>')" class="productdetailslink"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_DOWNLOADPDF'];?></a>
				<?php
			}
			?>
			</div>
<?php
}
/*Function to show the offer buttons
 */ 
    function offer_buttons($row_prod)
	{
		global $ecom_siteid,$db,$Captions_arr,$Settings_arr,$inlineSiteComponents;
       ?>
			<ul class="save-icons">
				<?php
				if($row_prod['product_freedelivery']==1)
				{
				?>
				<li><img src="<?php echo url_site_image('free-delivery.jpg')?>" width="61" height="73" /></li>
				<?php
				}
				/*
				?>
				<li><img src="<?php echo url_site_image('vat_exempt.jpg')?>" width="61" height="73" /></li>
				<?php
				*/ 
				if($row_prod['product_show_pricepromise']==1)
				{
				?>
				<li><img src="<?php echo url_site_image('price_promise.jpg')?>" width="61" height="73" onclick="handle_price_promise()" /></li>

				<?php
				}
				$in_combo = is_product_in_any_valid_combo($row_prod);
				if($in_combo==1)
				{
					?>
					<a href="<?php url_link('showallbundle'.$row_prod['product_id'].'.html')?>" title=""><div class="combo_del_logo"></div></a>
					<?php
				}
				?>
				</ul>

       <?php
       
	}
// ** Function to show the details of products which are linked with current product.
function Show_Linked_Product($ret_prod)
{
		global $ecom_siteid,$db,$Captions_arr,$Settings_arr,$inlineSiteComponents;
		// Calling the function to get the type of image to shown for current 
		$pass_type = get_default_imagetype('link_prod');
		$prod_compare_enabled = isProductCompareEnabled();
					$max_col = 2;
					$cur_col = 1;
					$cur_row = 1;
					$comp_active = isProductCompareEnabled();
                    $pass_type = get_default_imagetype('midshelf');
									$width_one_set 	= 325;
									$min_number_req	= 6;
									$min_width_req 	= $width_one_set * $min_number_req;
									$total_cnt		= $db->num_rows($ret_prod);
									$calc_width		= $total_cnt * $width_one_set;
									if($calc_width < $min_width_req)
										$div_width = $min_width_req;
									else
										$div_width = $calc_width; 
                    // Number of result to display on the page, will be in the LIMIT of the sql query also
                    ?>
                    <div class="shlf_b_outer" >
						<div class="shlf_b_header"><?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_PEOPLE_INTERESTED']);?></div>
                    <div class="shlf_b_nav"><a onmouseout="stopMe()" onmouseover="scrollDivRight('containerA')"><img src="     <?php echo url_site_image('shlfbarw-l.png');?>">
                    </a></div>
                    <div class="shlfb_thumb_outer" id="containerA">
                    <div style="width: <?php echo $div_width?>px;" id="scroller"> 
                    <?php
                    $prodcur_arr = array();
                    while($row_prod = $db->fetch_array($ret_prod))
                    {
                    
								$prodcur_arr[] = $row_prod;
								$col_cnts++;
								$wrapmainclass = 'newShelfWrap';
								
								
							?>
							<div id="pgng<?php echo $globalnxtpg; ?>_<?php echo $row_prod['product_id']; ?>" align="left" class="message_box" >
							<div class="<?php echo $wrapmainclass?>" id="catprod_<?php echo $row_prod['product_id']; ?>"   style="position:relative" onmouseover="showmask_proddet('<?php echo $row_prod['product_id']; ?>')" onmouseout="hidemask_proddet('<?php echo $row_prod['product_id']; ?>')">
							<div id="catprodmask_<?php echo $row_prod['product_id']; ?>" class="overlay-box">
						
						<?php
							$frm_name = uniqid('quick_');
							?>
							<div id="containers">
							<div class="quickviewclass" onclick="quick_view_prod('<?php echo $row_prod['product_id'] ?>','<?php echo $frm_name?>')">
								
								<img src="<?php url_site_image('quick_view.png')?>" alt="Quick View">
								<form name="<?php echo $frm_name?>" id="<?php echo $frm_name?>">
								<input type="hidden" name="product_id" value="<?php echo $row_prod['product_id'] ?>">
								<input type="hidden" name="ajax_fpurpose" value="Quick_Prod_Show_Details">
								</form>	
								
								</div>
							<div class="moreinfoclass"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>"><img src="<?php url_site_image('more-info.png')?>"></a></div>
							<div class="prod_list_buy">
							<?php
							$frm_name = uniqid('addtocart_');
							?>
							<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
							<input type="hidden" name="fpurpose" value="" />
							<input type="hidden" name="fproduct_id" value="" />
							<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
							<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
							<input type="hidden" name="ajax_fpurpose_quick" id="ajax_fpurpose_quick<?php echo $frm_name ?>" value="">

							<?php
							$class_arr['ADD_TO_CART']       = '';
							$class_arr['PREORDER']          = '';
							$class_arr['ENQUIRE']           = '';
							$class_arr['QTY_DIV']           = 'normal_shlfA_pdt_input';
							$class_arr['QTY']               = ' ';
							$class_td['QTY']				= 'prod_list_buy_a';
							$class_td['TXT']				= 'prod_list_buy_b';
							$class_td['BTN']				= 'prod_list_buy_c';
							
							$class_arr['showquick']         = 1;
							echo show_addtocart_v5($row_prod,$class_arr,$frm_name,false,'','',true,$class_td);
							?>
							</form>	
							</div>
							</div>
							</div>
							
								<div class="pro-wrap">
									<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" <?php echo getmicrodata_producturl()?> class="product_image" >
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
							
							<div class="newShelfdetails"><span class="shelfBprodname_three_column">
							
								<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><span><?php echo stripslashes($row_prod['product_name'])?></span></a>
							
							</span>
							<?php
							
								 $price_class_arr['ul_class'] 		= 'shelfBul_three_column';
								$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
								$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
								$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
								$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
								//	echo show_Price($row_prod,$price_class_arr,'shelfcenter_1'); price_categorydetails_1_reqbreak
								echo show_Price($row_prod,$price_class_arr,'cat_detail_1');
							
							
							?>
							</div>
							</div>
							</div>
							
							<?php
								$cur_row ++;
                    }
                    ?> 
										
						 </div>
                    </div>
                    <div class="shlf_b_navA"><a onmouseover="scrollDivLeft('containerA','<?php echo $div_width?>')" onmouseout="stopMe()"><img src="<?php url_site_image('shlfbarw-r.png')?>"  /></a></div>
                    </div>
					<?php						
		
}
// ** Function to show the list of products to be compared with current product.
function Show_Compare_Product($ret_prod)
{
global $ecom_siteid,$db,$Captions_arr,$Settings_arr;
$Captions_arr['PROD_DETAILS'] 	= getCaptions('PROD_DETAILS');
				?>
				<form method="post" action="" name="frm_proddet_comp" id="frm_proddet_comp" target="_blank">
				<input type="hidden" name="fproduct_id" value="<?php echo $_REQUEST['product_id']?>" /> 
				<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" /> 
				<input type="hidden" name="fpurpose" value="proddet_compare" /> 
				<input type="hidden" name="detcomp_prods" id="detcomp_prods" value="" />
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
							alert('<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_COMPARING'];?>'); 
						else if(totcnt>maxcnt) 
							alert('<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_SORRY_COMPARING'];?>'+(maxcnt)+' <?php echo $Captions_arr['PROD_DETAILS']['PRODDET_SORRY_COMPARING_TIME'];?>'); 
						else
						{
							checked_comp = def_prodid+','+checked_comp;
							document.frm_proddet_comp.detcomp_prods.value = checked_comp;
							document.frm_proddet_comp.submit();
						}
				}
				</script>
				<table border="0" cellpadding="0" cellspacing="0" class="shelfBtable">
				<tr>
          <td colspan="3" class="message_header"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_COMPARE_HEAD'];?></td>
        </tr>
				
				<tr>
				<td colspan="3" class="productdetd_main" align="left"><?php  $msg = $Captions_arr['PROD_DETAILS']['PRODDET_MAX_COMP_PROD']; $msg = str_replace('[prodno]',$Settings_arr['no_of_products_to_compare'],$msg); echo $msg?></td>
				</tr>
				<tr>
				<td align="left"><input type="button" name="prodet_backprod"
				value="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_BACK_TO_DETAIL'];?>"
				class="buttonred_cart_logbonus"
				onclick="window.location='<?php url_product($_REQUEST['product_id'],'',-1)?>'" /></td>
				<td align="right">&nbsp;</td>
				<td align="right"><input type="button" name="prodet_comparebutton"
				value="<?php echo $Captions_arr['PROD_DETAILS']['COMPARE_PRODUCTS'];?>"
				class="buttonred_cart" onclick="handle_proddet_compare()" /></td>
				</tr>
				<?php
				// Calling the function to get the type of image to shown for current 
				//$pass_type = get_default_imagetype('link_prod');
				$pass_type = 'image_gallerythumbpath';
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
					<tr onmouseover="this.className='normalshelf_hover'"	onmouseout="this.className='shelfBtabletd'">
					<td align="left" valign="middle" class="shelfBtabletd">
					<h1 class="shelfBprodname">
					<input type="checkbox" name="chkproddet_comp_<?php echo $row_prod['product_id']?>" id="chkproddet_comp_<?php echo $row_prod['product_id']?>" Value="<?php echo $row_prod['product_id']?>" <?php echo $compare_checked?> />
					<a	href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h1>
					<h6 class="shelfBproddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6>
					</td>
					<td align="center" valign="middle" class="shelfBtabletd">
					<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>"	title="<?php echo stripslashes($row_prod['product_name'])?>">
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
					</td>
					<td align="left" valign="middle" class="shelfBtabletd">
					<?php
					$price_class_arr['ul_class'] 		= 'shelfBul';
					$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
					$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
					$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
					$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';

					echo show_Price($row_prod,$price_class_arr,'linkprod_1');
					show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
					$frm_name = uniqid('linked_');
					?>	
					<form method="post"	action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>"	class="frm_cls"	onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
					<input type="hidden" name="fpurpose" value="" /> 
					<input type="hidden" name="fproduct_id" value="" /> <input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
					<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
					<?php
					if($showqty==1)// this decision is made in the main shop settings
					{
						?>
						<div class="quantity"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_QUANTITY']?>
						<input type="text" class="quainput_big" name="qty" value="1" />
						</div>
						<?php
					}
					?>
					<div class="prod_list_buy_com">
					<?php		$class_arr['ADD_TO_CART']	=	'';
					$class_arr['PREORDER']		=	'';
					$class_arr['ENQUIRE']		=	'';
					$class_arr['QTY_DIV']		=	'normal_shlfA_pdt_input';
					$class_arr['QTY']			=	' ';
					$class_td['QTY']			=	'prod_list_buy_a';
					$class_td['TXT']			=	'prod_list_buy_b';
					$class_td['BTN']			=	'prod_list_buy_c';
					echo show_addtocart_v5($row_prod,$class_arr,$frm_name,false,'','',true,$class_td);
					?>						
					</div>
					</form>
					</td>
					</tr>
					<?php
				}
				?>	
				<tr>
				<td colspan="3" align="right"><input type="button"
				name="prodet_comparebutton"
				value="<?php echo $Captions_arr['COMMON']['COMPARE_PRODUCTS'];?>"
				class="buttonred_cart" onclick="handle_proddet_compare()" /></td>
				</tr>
				</table>
				<?php
}
function show_bonus_points($row_prod)
{
		global $db,$ecom_siteid,$Captions_arr,$Settings_arr;
		
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
			if($row_prod['product_bonuspoints']>0)
			{
			$HTML_bonus = '<div class="prod_details_bonus"><span class="bonus_point_caption_b"><span >'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_BONUSPOINTSA']).'</span></span>

			<span class="bonus_point_caption_b">'.$row_prod['product_bonuspoints'].'</span>
			<span class="bonus_point_number_c"><span>'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_BONUSPOINTSB']).'</span></span></div>';
			}
			echo $HTML_bonus;
			?>
				
				
		<?php

		}
}
// ** Function to display the variables. This is written as function to avoid code repetation in various if conditions
function show_ProductVariables($row_prod,$pos='column')
{
				global $db,$ecom_siteid,$Captions_arr;
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
				AND var_value_exists = 1 
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

				if ($pos!='column') // Check the case of labels only if value of pos is not 'column'
				{
				// Check whether any labels to be displayed for current product
				$sql_labels = "SELECT a.label_id,a.label_name,b.label_value,a.is_textbox,product_site_labels_values_label_value_id  
				FROM
				product_site_labels a,product_labels b 
				WHERE 
				b.products_product_id = ".$_REQUEST['product_id']." 
				AND a.label_hide = 0 
				AND a.label_id = b.product_site_labels_label_id";
				$ret_labels = $db->query($sql_labels);
				$label_exists = false;
				while ($row_labels = $db->fetch_array($ret_labels) and $label_exists==false) // added the AND condition to avoid the case of exiting the loop if atleast one label have value
				{
					$vals = '';
					if ($row_labels['is_textbox']==1)
					$vals = stripslashes($row_labels['label_value']);
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
							$vals = stripslashes($row_labelval['label_value']);
						}

					}
					if (trim($vals))
					{
					$label_exists = true;	
					}
				}	
				}
				// Check whether total number of variables is 1 or more than 1
				if($var_cnt==1)
				{
					$vardisp_type = $row_prod['product_variable_display_type']; // take the display type from settings for current product
				}
				else 
					$vardisp_type = 'ADD'; // if the variable count is > 1 then by default the Add option will be displayed

				if ($db->num_rows($ret_var) or $db->num_rows($ret_msg))
				{
						/*
						?>

						<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<?php
						if($pos!='column')
						{
							?>
							<tr>
							<td class="variable_bottom_border">
							<div class="variabletabcontainer">
							<ul class="variabletab">
							<li id="var_li" class="variableselected"
							<?php /* onclick is required only in shown in a new row*//*if ($pos=='row') {?>
							onclick="handle_proddetail_variable('var')" <?php }?>><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_PRODVARS']?></li>	
							<?php
							if($pos!='column' and $label_exists) // show the product labels section only if variables in a new row
							{
								?>						
								<li id="label_li" onclick="handle_proddetail_variable('label')"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_KEY_FEATURES']?></li>
								<?php
							}
							?>
							</ul>
							</div>
							</td>
							</tr>
							<?php
						}
						*/ 
						?>
						<div class="variable-wrap">
<?php
						/*
						?>
						<style type="text/css">

						
						</style>
*/
?>
						
						<?php
						// Case of variables
						if ($db->num_rows($ret_var) or $db->num_rows($ret_msg))
								{
									$cnt_div =0;
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
											$clssA = ($i%2==0)?'productvariabletdB':'productvariabletdB';
											$clssB = ($i%2==0)?'productvariabletdAB':'productvariabletdBB';

											$var_exists = true;
											
											if ($row_var['var_value_exists']==1)
											{  
                                             ?>
											<ul>
											<li><label><?php echo stripslashes($row_var['var_name'])?></label><span>
											<?php
													if($row_prod['product_variablecomboprice_allowed']=='Y' and $row_prod['product_variablecombocommon_image_allowed']=='Y')
													{
														$onchange_function      = " onchange ='handle_show_prod_det_bulk_disc(\"price\",\"".SITE_URL."\",\"".$_REQUEST['product_id']."\",\"".url_site_image('loading.gif',1)."\",\"\")' ";
														$onclick_var            = "price";
													}
													elseif($row_prod['product_variablecomboprice_allowed']=='Y' and $row_prod['product_variablecombocommon_image_allowed']=='N')
													{
														$onchange_function      = " onchange ='handle_show_prod_det_bulk_disc(\"price\",\"".SITE_URL."\",\"".$_REQUEST['product_id']."\",\"".url_site_image('loading.gif',1)."\",\"\")' ";
														$onclick_var            = "price";
													}
													elseif($row_prod['product_variablecomboprice_allowed']=='N' and $row_prod['product_variablecombocommon_image_allowed']=='Y')
													{
														$onchange_function      = $onchange_function      = " onchange ='handle_show_prod_det_bulk_disc(\"main_img\",\"".SITE_URL."\",\"".$_REQUEST['product_id']."\",\"".url_site_image('loading.gif',1)."\",\"\")' ";
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
													{	      $proc_link = false;
														      if($ecom_siteid==	106)
																{
																	$var_namea = strtolower(trim($row_var['var_name']));
																	
																	if($var_namea=='insurance options')
																	{
																		$proc_link = true;
																		$onchange_function = "onchange = 'handle_show_value_link(\"".$_REQUEST['product_id']."\",\"".SITE_URL."\",\"".$row_var['var_id']."\",\"".url_site_image('loading.gif',1)."\")' ";
																	
																	}
																
																 }			
														?>
														<select name="var_<?php echo $row_var['var_id']?>" id="var_<?php echo $row_var['var_id']?>" <?php echo $onchange_function?> style='float:left'>
														<?php 
														$val_img_set =false;
														$curvalimg_sml = $curvalimg_big = '';
														while ($row_vals = $db->fetch_array($ret_vals))
														{
															
															// Check whether any image is set for this variable value
															if ($row_vals['images_image_id'])
															{
																$sql_valimg = "SELECT image_iconpath,image_bigpath 
																				FROM 
																					images 
																				WHERE 
																					image_id =".$row_vals['images_image_id']." 
																					AND sites_site_id = $ecom_siteid 
																				LIMIT 
																					1";	
																$ret_valimg = $db->query($sql_valimg);
																if($db->num_rows($ret_valimg))
																{
																	$row_valimg = $db->fetch_array($ret_valimg);
																	$curvalimg_sml = url_root_image($row_valimg['image_iconpath'],1);
																	$curvalimg_big = url_root_image($row_valimg['image_bigpath'],1);
																	$val_img_set = true;
																}
															}
															?>
															<option value="<?php echo $row_vals['var_value_id']?>" <?php echo ($_REQUEST['var_'.$row_var['var_id']]==$row_vals['var_value_id'])?'selected':''?>><?php echo stripslashes($row_vals['var_value'])?><?php echo Show_Variable_Additional_Price($row_prod,$row_vals['var_addprice'],$vardisp_type)?></option>
															<?php
														}
														?>
														</select>
														</span></li>
														</ul>	
														<?php
														if($val_img_set)
														{
														?>
														<div class="ienlarger" style='padding-left:10px;padding-top:3px'><a href="#"><img src="<?php echo $curvalimg_sml?>" border="0">
														<span><img src="<?php echo $curvalimg_big?>" alt="large" /><br />
														<div class='varhover_text'><?php echo $row_var['var_name']?></div></span></a>
														</div>						
														<?php
														}
														if($proc_link==true)
														{
														$HTML_loading_url = '<div class="proddet_loading_outer_div" style="height:15px"><div id="variable_linka_loading_div" style="display:none;padding:5px 0 0 0;">
														<img src="'.url_site_image('proddet_loading.gif',1).'" alt="loading..." width="43px" height="11px;align:left">
														</div></div>';
														//echo $HTML_loading_url;
														?>
														<div class="variable_linka" id="variable_linka" style="padding-top:5px;">
                                                        &nbsp;<a class="facts_class" href="javascript:show_pdf_new(&quot;http://dealer.premiercare.info/introducer/12771/TOB12771.pdf&quot;)">Key Facts</a>
                                                        </div>			
														<?php
														}
														
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
																	$onclick_function   = " onclick ='var_onclick(\"".$onclick_var."\",\"var_".$row_var['var_id']."\",".$row_vals['var_value_id'].",\"".$normal_cls_clr."\",\"".$special_cls_clr."\",\"".$normal_cls_clrimg."\",\"".$special_cls_clrimg."\",\"".$normal_cls_sz."\",\"".$special_cls_sz."\",\"".$docroot."\",\"".$prodid."\",\"".$loading_gif."\",\"\")' ";
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
													?>
													
													<?php
											}											
											?>
											
											<?php
											$i++;
										}

								}
								?>  
								<?php
						}
						// ######################################################
						// End of variables section
						// ######################################################

						// ##############################################################################
						//  Case of variable messages
						// ##############################################################################

						if ($db->num_rows($ret_msg))
							{
								?>
								<ul>
								<?php
								while ($row_msg = $db->fetch_array($ret_msg))
									{
										$clss = ($i%2==0)?'productvariabletdA':'productvariabletdB';
										$var_exists = true;
										?>
										<li><label><?php echo stripslashes($row_msg['message_title'])?></label>
										<span>
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
											<textarea name="varmsg_<?php echo $row_msg['message_id']?>" id="varmsg_<?php echo $row_msg['message_id']?>" rows="3" cols="15"><?php echo $_REQUEST['varmsg_'.$row_msg['message_id']]?></textarea>
											<?php
										}
										?>
										</span>
										</li>
										<?php
										$i++;
								}
								?> 
								</ul> 
								<?php		
						}
						// ######################################################
						// End of variable messages
						// ######################################################
						?>
						</div>
						<?php
				}
				$sql_var_checkbox = "SELECT var_id,var_name,var_value_exists, var_price,var_value_display_dropdown,images_image_id  
				FROM 
				product_variables 
				WHERE 
				products_product_id = ".$_REQUEST['product_id']." 
				AND var_hide= 0
				AND var_value_exists = 0 
				ORDER BY 
				var_order";
				$ret_var_checkbox = $db->query($sql_var_checkbox);
									if ($db->num_rows($ret_var_checkbox))
									{
									?>
									
									<?php
									?>
									<div class="pdvariablesTab">
										<?php
										while ($row_var_checkbox = $db->fetch_array($ret_var_checkbox))
										{
										$val_img_set_checkbox = false;
										// Check whether any image is set for this variable value
										if ($row_var_checkbox['images_image_id'])
										{
											$sql_valimg_checkbox = "SELECT image_iconpath,image_bigpath 
															FROM 
																images 
															WHERE 
																image_id =".$row_var_checkbox['images_image_id']." 
																AND sites_site_id = $ecom_siteid 
															LIMIT 
																1";	
											$ret_valimg_checkbox = $db->query($sql_valimg_checkbox);
											if($db->num_rows($ret_valimg_checkbox))
											{
												$row_valimg_checkbox = $db->fetch_array($ret_valimg_checkbox);
												$curvalimg_sml_checkbox = url_root_image($row_valimg_checkbox['image_iconpath'],1);
												$curvalimg_big_checkbox = url_root_image($row_valimg_checkbox['image_bigpath'],1);
												$val_img_set_checkbox = true;
											}
										}
										else
										{
											$curvalimg_sml_checkbox = '';
											$curvalimg_big_checkbox = '';
										}
										?>
														
										<div class="pdrow">

										<div class="ienlarger" style='padding-left:10px;padding-top:3px'>
										<?php
										if($curvalimg_big_checkbox=='' and $curvalimg_sml_checkbox=='')
										{
											$curvalimg_sml_checkbox = url_site_image('no_iconsamll_image.gif',1);
											$curvalimg_big_checkbox = '';//url_site_image('no_iconsamll_image.gif',1);
										}
										?>
											<a href="#"><img src="<?php echo $curvalimg_sml_checkbox?>" border="0">
										<span>
										<?php if ($curvalimg_big_checkbox!='')
										{
										?>
										<img src="<?php echo $curvalimg_big_checkbox?>" alt="large" />
										<?php
										}
										?>
										<br />
										<div class='varhover_text'><?php echo $row_var_checkbox['var_name']?></div></span></a>
										</div>	

										<div class="pdcol1"><?php echo stripslashes($row_var_checkbox['var_name'])?></div>
										<div class="pdcol2"><?php echo Show_Variable_Additional_Price($row_prod,$row_var_checkbox['var_price'],$vardisp_type)?></div><div class="pdcol3">
										<input type="checkbox" name="var_<?php echo $row_var_checkbox['var_id']?>" id="var_<?php echo $row_var_checkbox['var_id']?>" value="1" <?php echo ($_REQUEST['var_'.$row_var_checkbox['var_id']])?'checked="checked"':''?> />
										</div>
										</div>
										<?php
										}
										?>
										</div>									

										<?PHP
				}
				return $var_exists;
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
								<div class="bulk-wrap">
<div class="bulkPrice"><ul>
							<?php
							for($i=0;$i<count($bulkdisc_details['qty']);$i++)
							{
									?>
									<li>	
									<?php echo $Captions_arr['PROD_DETAILS']['BULK_BUY']?> <?php echo $bulkdisc_details['qty'][$i].' '.$Captions_arr['PROD_DETAILS']['BULK_FOR'].' '?> 
									<?php //echo print_price($bulkdisc_details['price'][$i]).' '.$Captions_arr['PROD_DETAILS']['BULK_EACH']?>
									<span class="price_red"><?php 	echo product_BulkDiscount_Build_Price($bulkdisc_details['price'][$i],$bulkdisc_details['price_without'][$i]).' '.stripslash_normal($Captions_arr['PROD_DETAILS']['BULK_EACH']);?></span>
									</li>
									<?php
							}
							?>
							</ul>
							</div>
<div class="bulk-banner"><img src="<?php echo url_site_image('banner_price.png')?>" width="154" height="54" /></div>

</div> 
							<?php
					}
					else 
					{
					?>
					<div class="bulk_con" id="bulkdisc_holder">
					</div>
					<?php 	
					}
}
/* Function to show the instock notification */
/* Function to show the bulk discount*/
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
										<div class="div_alert" id="instockmsg_div">
										<div align="right" class="instockmsg_span"><a
										href="javascript: hide_instockmsg_div()"><img
										src="<?php url_site_image('close.gif')?>" border="0" /></a></div>
										<?php echo $Captions_arr['PROD_DETAILS']['PRODUCT_OUT_STOCK']?>.
										<br />
										<span class="instockmsg_out_stock">
										<?php echo $Captions_arr['PROD_DETAILS']['PRODUCT_OUT_STOCK_EMAIL']?></span>
										<input type="text" name="stock_email" /> <input type="hidden"
										name="prod_mod" value="stock_notify" /> <input type="hidden"
										name="hid_notify" value="stock" /> <input type="button"
										name="stocknotif_submit" value=" Send Request " class="buttongray"
										onclick=" validate_stocknotify(document.frm_proddetails)" /> <input
										type="hidden" name="product_id"
										value="<?PHP echo $_REQUEST['product_id']; ?>" /> <!--	<span style="font-weight:bold;">
										<a href="javascript:handle_instocknotification('<?php// echo $row_prod['product_id']?>','<?php// echo $ecom_hostname?>')" style="color:#000000;text-decoration:underline">click here</a></span>  -->
										</div>
										<?php	
									}
									else
									{
										?>
										<div class="alert_inner"><?php echo $Captions_arr['PROD_DETAILS']['PRODUCT_OUT_STOCK']?>.
										<br />
										<span style="font-size: 12px; font-weight: normal; color: #000000;"><?php echo $Captions_arr['PROD_DETAILS']['PRODUCT_OUT_STOCK_EMAIL']?><br />
										<input type="text" name="stock_email" /> 
										<input type="hidden" name="prod_mod" value="stock_notify" />
										<input type="hidden" name="hid_notify" value="stock" />
										<input type="button" name="stocknotif_submit" value=" Send Request " class="buttongray" onclick=" validate_stocknotify(document.frm_proddetails)" />
										<input type="hidden" name="product_id" value="<?PHP echo $_REQUEST['product_id']; ?>" />
										</span>
										</div>
										<?php
									}		
							}
					}
}

/* Function to show the lables set for the product */
function show_ProductLabels($prod_id,$pos='row')
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
									$ret_val = '<table width="50%" border="0" cellpadding="0" cellspacing="0"';
									//if ($pos=='row') 
									//$ret_val .=' id="proddet_label_table" style="display: none"';
									$ret_val .='>';
									//if($pos!='row') // show the product labels section only if variables in a new row
									{
									/*
									$ret_val .='<tr>
									<td class="variable_bottom_border" colspan="2">
									<div class="variabletabcontainer">
									<ul class="variabletab">
									<li class="variableselected">'.$Captions_arr['PROD_DETAILS']['PRODDET_KEY_FEATURES'].'</li>
									</ul>
									</div>
									</td>
									</tr>';
									*/ 
									}			
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
											
												$display_ok=true;
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
																if($i%2==0)
																{
																	$clss = 'productvariabletdAA';
																	$clssB = 'productvariabletdA';
																}
																else
																{
																	$clss = 'productvariabletdBB';
																	$clssB = 'productvariabletdB';
																}
																$ret_val .= '   <tr>
																	<td align="left" valign="middle" class="'.$clss.'">'.$v[$i]['name'].'</td>
																	<td align="left" valign="middle" class="'.$clssB.'">:&nbsp;'.$v[$i]['val'].'</td>
																</tr>';	
															}
														}
													}
												}
									}	
									$ret_val .= '</table>';	
								}
							}
						}	
					}
					if($display_ok==false)
					$ret_val = '';
					return $ret_val ;	
}
function show_inssurance($prod_id)
{
	             global $Captions_arr,$showqty,$Settings_arr,$insurance_cat,$db;
	             $proceed_in = false;
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
							$cat_id = $row_cats['product_categories_category_id'];
							if(in_array($cat_id,$insurance_cat))
							{
								$proceed_in = true;
							}
						}
					}
					return $proceed_in;	
}
function show_offer_buttons($row_prod)
{
					global $Captions_arr,$showqty,$Settings_arr,$insurance_cat,$ecom_siteid;
					$cat_id = $row_prod['product_default_category_id'];
				    $proceed_inssurance = false;
				    $proceed_inssurance = $this->show_inssurance($row_prod['product_id']);					

					?>		
					<?php 
					// Special Buttons	
					// Check whether this product is linked with any of the combo deals which is currently active
					$in_combo = is_product_in_any_valid_combo($row_prod);
					
					?>
					<div class="logo_wrap">
					<?php /*<div class="eng_del_logo"></div>
		<div class="vat_del_logo"></div> **/
		?>
			<?php
			if($row_prod['product_freedelivery']==1)
			{
				?>	
				<a href="<?php url_link('freedelivery'.$row_prod['product_id'].'.html')?>" title="Free Delivery"><div class="free_del_logo"></div></a>
				<?php
			}
			?>

			<?php
			if($row_prod['product_show_pricepromise']==1)
			{
				?>	
				<a href="javascript:handle_price_promise()" title="Price Promise"><div class="price_del_logo"></div></a>
				<?php
			}
			if($row_prod['product_saleicon_show']==1)
			{
			?>
						<div class="sale_del_logo"></div>

			<?php
			}
			?>
			<?php
			$in_combo = is_product_in_any_valid_combo($row_prod);
			if($in_combo==1)
			{
				?>
				<a href="<?php url_link('showallbundle'.$row_prod['product_id'].'.html')?>" title=""><div class="combo_del_logo"></div></a>
				<?php
			}
			
			?>
			</div>  

			<?php 
			if($proceed_inssurance==true)
			{
			?>
			 <div class="ins_banner_wrap"><img src="<?php url_site_image('Banner_insure.jpg')?>" alt="" border="0"></div>
			<?php
			}
		
				
}
function show_buttons($row_prod)
{
global $Captions_arr,$showqty,$Settings_arr,$ecom_hostname,$db,$ecom_siteid;
					$addto_cart_withajax =  $Settings_arr['enable_ajax_in_site'];
					$cust_id 	= get_session_var("ecom_login_customer");
					//$showqty	= $Settings_arr['show_qty_box'];// show the qty box
					$showqty     = 1;
					$cur_qty_caption = ($row_prod['product_det_qty_caption']!='')?stripslashes($row_prod['product_det_qty_caption']):$Captions_arr['PROD_DETAILS']['PRODDET_QUANTITY'];
					// Get the caption to be show in the button
					$caption_key = show_addtocart($row_prod,array(0),'',true);
					?>
<div class="qty-detail">
		<ul class="qty-detail-area">
					
	<?php
					if ($caption_key!='') // Check whether the buynow or preorder button is to be displayed
					{
							?>
						
							<?php
							if($showqty==1)// this decision is made in the main shop settings
							{
								if($row_prod['product_det_qty_type']=='NOR')
								{
									?>
									<li><?php echo $cur_qty_caption?></li>

									<li><img src="<?php echo url_site_image('plus-qty.png')?>" width="20" height="20" onclick="increment_quantity('frm_proddetails')" /></li>
									<li><input type="text" class="text-field" name="qty" value="<?php echo ($_REQUEST['qty'])?$_REQUEST['qty']:1?>" maxlength="4" /></li>
									<li><img src="<?php echo url_site_image('minus-qty.png')?>" width="20" height="20" onclick="decrement_quantity('frm_proddetails')" /></li>
									<?php
								}
								elseif($row_prod['product_det_qty_type']=='DROP')
								{
									$dropdown_values = explode(',',stripslashes($row_prod['product_det_qty_drop_values']));
									if (count($dropdown_values) and stripslashes($row_prod['product_det_qty_drop_values'])!='')
									{
										?>
										<li><?php echo $cur_qty_caption?></li>
									<li><img src="<?php echo url_site_image('plus-qty.png')?>" width="20" height="20" onclick="increment_quantity('frm_proddetails')" /></li>

										
										<li><select name="qty">
										<?php 
										$qty_prefix = stripslashes($row_prod['product_det_qty_drop_prefix']);
										$qty_suffix = stripslashes($row_prod['product_det_qty_drop_suffix']);
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
										</li>
										<li><img src="<?php echo url_site_image('minus-qty.png')?>" width="20" height="20" onclick="decrement_quantity('frm_proddetails')"/></li>

										<?php				
									}				
								}
							}
							?>							
							<?php
							if($addto_cart_withajax == 1)
							{
								?>
								<li>
								<input name="Submit_buy" type="button" class="red-button" id="Submit_buy" value="<?php echo $Captions_arr['PROD_DETAILS'][$caption_key];?>"	onclick="ajax_addto_cart_fromlist('add_prod_tocart_ajax','Prod_Addcart','frm_proddetails','<?php echo SITE_URL?>')" />
								</li>
								<?php /*ajax_addto_cart('show_det',this.form,'<?php echo SITE_URL ?>') */?>
								<?php
							}
							else
							{
								?>
								<li>
								<input name="Submit_buy" type="submit" class="red-button" id="Submit_buy" value="<?php echo $Captions_arr['PROD_DETAILS'][$caption_key];?>"	onclick="show_wait_button(this,'Please Wait...');document.frm_proddetails.fpurpose.value='Prod_Addcart'" /></li>
								<?php
							}
							?>
									
							<?php
					}			
					?>
					</ul>
					</div>
					<?php
							if ($row_prod['product_show_enquirelink']==1 )
							{
								?>
								<div class="qty-detail">
		                        <ul class="qty-detail-area">
								<?php 
								// Check whether the enquire link is to be displayed
								if ($row_prod['product_show_enquirelink']==1)
								{
									if($addto_cart_withajax == 1)
									{
										?>
										<li><input name="Submit_enq" type="submit" class="buttonblackbuy" id="Submit_enq" value="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_ENQUIRY'];?>" onclick="ajax_addto_cart_fromlist('add_prod_tocart_ajax','Prod_Enquire','frm_proddetails','<?php echo SITE_URL?>')" /></li>
										<?php
									}
									else
									{	
										?>
										<li><input name="Submit_enq" type="submit" class="buttonblackbuy" id="Submit_enq" value="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_ENQUIRY'];?>" onclick="show_wait_button(this,'Please Wait...');document.frm_proddetails.fpurpose.value='Prod_Enquire'" /></li>
										<?php
									}
								}
								
								?>
								</ul>
								</div>
								<?php
								}
								if($cust_id)
								{
									if($Settings_arr['proddet_showfavourite']==1)
									$favourite_show = 1;
								}
								if ($Settings_arr['proddet_showwishlist'] || $favourite_show==1)
								{
									?>
									<ul class="list-icon">
									<?php
									
									if($cust_id) // ** Show the wishlist button only if logged in 
									{
										?>
										<li class="icon-star"><input name="submit_wishlist" type="submit" class="buttonblackbuy" id="submit_wishlist" value="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_ADDWISHLIST'];?>" onclick="show_wait_button(this,'Please Wait...');document.frm_proddetails.fpurpose.value='Prod_Addwishlist'" /></li>
										<?php
									}
									else
									{
										$wishlist_onclick = 'window.location=\'http://'.$ecom_hostname.'/wishlistcustlogin.html\'';
									?>
										<li class="icon-star"><input name="submit_wishlist" type="button" class="buttonblackbuy" id="submit_wishlist" value="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_ADDWISHLIST'];?>" onclick="<?php echo $wishlist_onclick?>" /></li>

									<?php
									}
									?>
									
									<?php		
								
									if($favourite_show==1) // ** Show the add to favourite only if logged in 
									{
									$sql_prod = "SELECT products_product_id FROM customer_fav_products WHERE sites_site_id=$ecom_siteid AND products_product_id=".$_REQUEST['product_id']." AND customer_customer_id=$cust_id LIMIT 1";
									$ret_num= $db->query($sql_prod);
									if($db->num_rows($ret_num)==0) 
									{ 
									?>				<li class="icon-fav"><a href="#" class="productdetailslink" onclick="if(confirm('<? echo $Captions_arr['PROD_DETAILS']['PRODDET_ADD_CONFIRM'] ?>')){document.frm_proddetails.fpurpose.value='add_favourite';document.frm_proddetails.submit();}"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_FAVOURITE'];?></a></li>
									<?php
									}
									else
									{
									?>				<li class="icon-fav"><a href="#" class="productdetailslink" onclick="if(confirm('<? echo $Captions_arr['PROD_DETAILS']['PRODDET_REM_CONFIRM'] ?>')){document.frm_proddetails.fpurpose.value='remove_favourite';document.frm_proddetails.submit();}"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_REMFAVOURITE'];?></a></li>
									<?php
									}
									}
								?>
								</ul>
								<?php
								}
								if($Settings_arr['show_bookmarks'])
								{
									?>	<div class="share">
									<b><?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['BOOKMARK_HEADING'])?></b><br />
									<?php		bookmarks(url_product($row_prod['product_id'],$row_prod['product_name'],1),htmlspecialchars($meta_arr['title']),$ecom_twitteraccountId); 
									?>	</div>
									<?php	
								} 
								?>
								<?php	
					
					return true;
}
function show_more_images($row_prod,$exclude_tabid,$exclude_prodid)
{
				
					global $db,$ecom_hostname,$ecom_themename;
					$exclude_prodid = 0;
					$show_normalimage = false;
					$prodimg_arr		= array();
					$pass_type = 'image_thumbcategorypath';
					$extralarge_img = "";

					if ($_REQUEST['prod_curtab'] and $row_prod['product_variablecombocommon_image_allowed']=='N')
					{
						//if ($exclude_tabid)
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
						//if ($exclude_prodid)
						$prodimg_arr = get_imagelist_combination($row_prod['default_comb_id'],$pass_type.',image_bigcategorypath',$exclude_prodid,0);
						}		
						else
						{
						//if ($exclude_prodid)
						$prodimg_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type.',image_bigcategorypath',$exclude_prodid,0);
						}

					} 

					if(count($prodimg_arr)>0)// Case if more than one image assigned to current product
					{
						?>				

							

							<div class="detail_thumb_wrap">
										   <div id="content-2" class="content light">
<?php
								if(count($prodimg_arr)>4)
								{
								?>
								<?php
								}
								?>
								 <div class="clearfix" >
	<ul id="thumblist" class="clearfix" >


							<?php
							if ($pass_type=='image_thumbpath') // If the more image type is Thumb then show 3 in a row otherwise show 2 in a row
							{
							$maximg_col 	=1;
							$width				= '100%';
							}	
							else
							{
							$maximg_col = 3;
							$width			= '45px';
							}	
							$curimg_col = 0;
							$tabstr		= ($_REQUEST['prod_curtab'])?'&amp;prod_curtab='.$_REQUEST['prod_curtab']:'';
							$cnt = 0 ;
							$cls = "";
							foreach ($prodimg_arr as $k=>$v)
							{ 
							$extralarge_img = "";
							if($cnt==0)
							$cls = 'class="zoomThumbActive"';
							else
							$cls = "";
							$title = ($v['image_title'])?stripslashes($v['image_title']):$row_prod['product_name'];


							list($sml_width,$sml_height) = getimagesize(ORG_DOCROOT.'/images/'.$ecom_hostname.'/'.$v['image_bigcategorypath']);
							list($big_width,$big_height) = getimagesize(ORG_DOCROOT.'/images/'.$ecom_hostname.'/'.$v['image_extralargepath']);

							//echo '<br>sml '.$sml_width.'x'.$sml_height;
							//echo '<br>bg '.$big_width.'x'.$big_height;

							if($sml_width and $sml_height and $big_width and $big_height)
							{
							if ($big_width<$sml_width or $big_height<$sml_height)
							{
							$extralarge_img = url_root_image($v['image_bigpath'],1);
							}
							else
							$extralarge_img = url_root_image($v['image_extralargepath'],1);
							}
							else
							$extralarge_img = url_root_image($v['image_extralargepath'],1);
							//echo $extralarge_img;

							?>
							<li>
							<?php /*?><a href="#" onclick="link_submit('<?php echo $_REQUEST['prod_curtab']?>','<?php echo $v['image_id']?>','<?php echo url_product($row_prod['product_id'],$row_prod['product_name'],1)?>',0)" title="<?php echo $title?>"><?php 
							<a href="javascript:showImagePopup('<?php echo $v['image_extralargepath']?>','<?php echo $ecom_hostname?>','<?php echo $ecom_themename?>');"  title="<?php echo $title?>">
							*/?>
							<a <?php echo $cls;?> href='javascript:void(0);' rel="{gallery: 'gal1', smallimage: '<?php echo url_root_image($v['image_bigpath']);?>',largeimage: '<?php echo $extralarge_img?>'}">
							<img id="<?php echo $k; ?>" src="<?php echo url_root_image($v['image_thumbcategorypath'],1);?>" />
							</a>
							</li>
							<?php
							$cnt ++;
						}
						?>	
						
						</ul>
					</div>
					<?php
								if(count($prodimg_arr)>4)
								{
								?>
								<?php
								}
								?>
					</div>
					</div>
				
						<?php
					}
}	
function show_more_images_ajax($row_prod,$exclude_tabid,$exclude_prodid)
{
				
					global $db,$ecom_hostname,$ecom_themename;
					$exclude_prodid = 0;
					$show_normalimage = false;
					$prodimg_arr		= array();
					$pass_type = 'image_thumbcategorypath';
					$extralarge_img = "";

					if ($_REQUEST['prod_curtab'] and $row_prod['product_variablecombocommon_image_allowed']=='N')
					{
						//if ($exclude_tabid)
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
						//if ($exclude_prodid)
						$prodimg_arr = get_imagelist_combination($row_prod['default_comb_id'],$pass_type.',image_bigcategorypath',$exclude_prodid,0);
						}		
						else
						{
						//if ($exclude_prodid)
						$prodimg_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type.',image_bigcategorypath',$exclude_prodid,0);
						}

					} 

					if(count($prodimg_arr)>0)// Case if more than one image assigned to current product
					{
						?>				

							

							<div class="detail_thumb_wrap">
						   <div id="content-2" class="content light">

								 <div class="clearfix" >
	<ul id="thumblist" class="clearfix" >


							<?php
							if ($pass_type=='image_thumbpath') // If the more image type is Thumb then show 3 in a row otherwise show 2 in a row
							{
							$maximg_col 	=1;
							$width				= '100%';
							}	
							else
							{
							$maximg_col = 3;
							$width			= '45px';
							}	
							$curimg_col = 0;
							$tabstr		= ($_REQUEST['prod_curtab'])?'&amp;prod_curtab='.$_REQUEST['prod_curtab']:'';
							$cnt = 0 ;
							$cls = "";
							foreach ($prodimg_arr as $k=>$v)
							{ 
							$extralarge_img = "";
							if($cnt==0)
							$cls = 'class="zoomThumbActive"';
							else
							$cls = "";
							$title = ($v['image_title'])?stripslashes($v['image_title']):$row_prod['product_name'];


							list($sml_width,$sml_height) = getimagesize(ORG_DOCROOT.'/images/'.$ecom_hostname.'/'.$v['image_bigcategorypath']);
							list($big_width,$big_height) = getimagesize(ORG_DOCROOT.'/images/'.$ecom_hostname.'/'.$v['image_extralargepath']);

							//echo '<br>sml '.$sml_width.'x'.$sml_height;
							//echo '<br>bg '.$big_width.'x'.$big_height;

							if($sml_width and $sml_height and $big_width and $big_height)
							{
							if ($big_width<$sml_width or $big_height<$sml_height)
							{
							$extralarge_img = url_root_image($v['image_bigpath'],1);
							}
							else
							$extralarge_img = url_root_image($v['image_extralargepath'],1);
							}
							else
							$extralarge_img = url_root_image($v['image_extralargepath'],1);
							//echo $extralarge_img;

							?>
							<li>
							<?php /*?><a href="#" onclick="link_submit('<?php echo $_REQUEST['prod_curtab']?>','<?php echo $v['image_id']?>','<?php echo url_product($row_prod['product_id'],$row_prod['product_name'],1)?>',0)" title="<?php echo $title?>"><?php 
							<a href="javascript:showImagePopup('<?php echo $v['image_extralargepath']?>','<?php echo $ecom_hostname?>','<?php echo $ecom_themename?>');"  title="<?php echo $title?>">
							*/?>
							<a <?php echo $cls;?> href='javascript:void(0);' rel="{gallery: 'gal1', smallimage: '<?php echo url_root_image($v['image_bigpath']);?>',largeimage: '<?php echo $extralarge_img?>'}">
							<img id="<?php echo $k; ?>" src="<?php echo url_root_image($v['image_thumbcategorypath'],1);?>" />
							</a>
							</li>
							<?php
							$cnt ++;
						}
						?>	
						
						</ul>
</div>
					</div>
					</div>
				
						<?php
					}
}	
// Function which holds the logic for image swapping using javascript
function Show_Javascript_Image_Swapper($row_prod)
{
global $ecom_hostname,$ecom_themename,$db;
					?>
					<script type="text/javascript">
					// JavaScript Document
					// Globals
					// Major version of Flash
					var installedMajorVersion = 1;
					// Minor version of Flash
					var installedMinorVersion = 0;
					// Minor version of Flash
					var installedRevision = 0;
					// Major version of Flash required
					var requiredMajorVersion = 9;
					// Minor version of Flash required
					var requiredMinorVersion = 0;
					// Minor version of Flash required
					var requiredRevision = 0;
					// Version check based upon the values entered above in "Globals"
					var hasRequestedVersion = DetectFlashVer(requiredMajorVersion, requiredMinorVersion, requiredRevision);
					var hasAnyVersion = DetectFlashVer(installedMajorVersion, installedMinorVersion, installedRevision);
					var oLoader = new Image();
					oLoader.src = '<?php url_site_image('product_loading_spinner.gif')?>';			
					var oZoomImage = new Image();
					var tZoomLoaded;
					var	arrMainImage = new Array;
					var	arrThumbImage = new Array;
					var	arrBigImage = new Array;
					var tmpHolder = new Array
					var tmpHolderBig = new Array
					var firstTime360 = true
					</script>
					<?php
					$show_normalimage = false;
					if ($_REQUEST['prod_curtab'])
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
					$exclude_tabid 	= $tabimg_arr[0]['image_id']; 		// exclude id in case of multi images for products
					$main_img			=  url_root_image($tabimg_arr[0]['image_bigpath'],1);
					$large_img			=  url_root_image($tabimg_arr[0]['image_extralargepath'],1);
					$icon_img			=  url_root_image($tabimg_arr[0]['image_iconpath'],1);
					?>
					<script type="text/javascript">
					arrMainImage[0] = new Array("<?php echo $icon_img?>", "<?php echo $main_img?>", "<?php echo $large_img?>","<?php echo $main_img?>");
					</script>
					<?php
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
					$prodimg_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,0,$showonly,1);
					if(count($prodimg_arr))
					{
					$exclude_prodid = $prodimg_arr[0]['image_id']; 		// exclude id in case of multi images for products
					$main_img			=  url_root_image($prodimg_arr[0]['image_bigpath'],1);
					$large_img			=  url_root_image($prodimg_arr[0]['image_extralargepath'],1);
					$icon_img			=  url_root_image($prodimg_arr[0]['image_iconpath'],1);
					?>
					<script type="text/javascript">
					arrMainImage[0] = new Array("<?php echo $icon_img?>", "<?php echo $main_img?>", "<?php echo $large_img	?>", "<?php echo $main_img	?>");
					</script>
					<?php
					$show_noimage 	= false;
					}
					else
					{	
					// calling the function to get the default no image 
					$no_img = get_noimage('prod','big'); 
					if ($no_img)
					{
					?>
					<script type="text/javascript">
					arrMainImage[0] = new Array("<?php echo $no_img?>", "<?php echo $no_img?>", "<?php echo $no_img?>" , "<?php echo $no_img?>");
					</script>
					<?php
					}	
					}
					}
					// Finding the thumb images
					$pass_type = 'image_iconpath';
					if ($_REQUEST['prod_curtab'])// case if came by clicking the tab
					{
					if ($exclude_tabid)
					$prodthumbimg_arr = get_imagelist('prodtab',$_REQUEST['prod_curtab'],$pass_type.',image_thumbpath',$exclude_tabid,0,0,'rand()');	
					if (count($prodthumbimg_arr)==0) // case if no more tab images exists
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
					if ($exclude_prodid)
					$prodthumbimg_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type.',image_thumbpath',$exclude_prodid,0,0,'rand()');

					}
					if (count($prodthumbimg_arr))
					{
					$i = 0;
					foreach ($prodthumbimg_arr as $k=>$v)
					{
					$icnimg			= url_root_image($v['image_iconpath'],1);
					$icon_arr[$i] 	= $icnimg;
					$bgimg			= url_root_image($v['image_bigpath'],1);
					$large_img		=  url_root_image($v['image_extralargepath'],1);
					?>
					<script type="text/javascript">
					arrThumbImage[<?php echo $i?>] = new Array("<?php echo $icnimg?>", "<?php echo $bgimg?>", "<?php echo $large_img?>", "<?php echo $bgimg?>");
					</script>
					<?php		
					$i++;
					}
					}
					?>
					<script type="text/javascript">
					for (i=0; i < arrThumbImage.length; i++) 
					{
					var preload = new Image();
					preload.src = arrThumbImage[i][1];
					preload.src = arrThumbImage[i][2];
					}
					for (i=0; i < arrMainImage.length; i++)
					{
					var preload = new Image();
					preload.src = arrMainImage[i][1];
					}	
					</script>
					<table style="border: solid 1px #CCCCCC; width: 290px;" border="0"
					cellpadding="0" cellspacing="0">
					<tr>
					<td align="left" valign="top" style="padding: 5px">
					<div class="content_product_container" style="width: 290px;">
					<div class="content_product_images" style="width: 290px;">
					<div>
					<div style="display: block;" id="pnlMainImage">
					<div id="dvMainImageZoom"
					class="content_product_images_mainimage_zoom"
					style="width: 290px; height: 370px; display: none;" align="left"
					title="Click and drag image"><img id="imgMainImageZoom" src="" alt=""
					style='border: 0'></div>
					<a id="hypMainImage" href="javascript:ShowZoomImage();"
					style="display: block; clear: both;"
					onmouseover="window.status='Click on images to enlarge them'; return true;"
					onmouseout="window.status=''; return true;"
					title="<?php echo $row_prod['product_name']?>"> <img
					id="imgMainImage" class="content_product_images_mainimage"
					src="<?php echo $main_img?>"
					alt="<?php echo $row_prod['product_name']?>"
					style="border-width: 0px; display: block;"> </a> <a
					style="display: block; float: left; width: 20%" id="hypZoomPlus"
					href="javascript:ShowZoomImage();"> <img
					src="<?php url_site_image('product_ZoomPlus.gif')?>" alt="Zoom In"
					border="0"></a> <a style="display: none; float: left; width: 20%"
					id="hypZoomMinus" href="javascript:HideZoomImage();"> <img
					src="<?php url_site_image('product_ZoomMinus.gif')?>" alt="Zoom Out"
					border="0"></a> <a id="hypDragImage"
					style="border-width: 0px; float: left; clear: right; width: 78%; display: block;">
					<img src="<?php echo url_site_image('product_clickndrag.gif')?>"
					alt="Click and drag the zoomed image" style="border-width: 0px;"> </a>
					<?php 
					if($row_prod['product_flv_filename']!='') // make the 
					{
					?>
					<a id="hypVideoImage" href="javascript:ShowVideo();"
					style="border-width: 0px; float: left; width: 78%; overflow: hidden">
					<img src="<?php url_site_image('product_ViewCatwalk.gif')?>"
					alt="Click here to view the video" style="border-width: 0px;"></a> 
					<?php
					}
					else
					echo '<div style="float:left;width:78%; overflow:hidden"><img src="'.url_site_image('blank.gif',1).'" border="0" id="img_blank"/></div>';
					?>	
					</div>
					<?php 
					if($row_prod['product_flv_filename']!='')
					{
					?>
					<div style="display: none; height: 370px" id="divFlash"
					class="content_product_flash"><script type="text/javascript"
					src="http://<?php echo $ecom_hostname?>/images/<?php echo $ecom_hostname?>/scripts/swfobject.js"></script>
					<script type="text/javascript">
					<?php /*?>var s1 = new SWFObject("http://<?php echo $ecom_hostname?>/themes/<?php echo $ecom_themename?>/swf/player.swf","ply","288","370","9","#FFFFFF");<?php */?>
					var s1 = new SWFObject("http://<?php echo $ecom_hostname?>/images/<?php echo $ecom_hostname?>/swf/player.swf","ply","288","370","9","#FFFFFF");
					s1.addParam("allowfullscreen","true");
					s1.addParam("allowscriptaccess","always");
					s1.addParam("flashvars","file=http://<?php echo $ecom_hostname?>/images/<?php echo $ecom_hostname?>/product_flv/<?php echo $row_prod['product_flv_filename']?>");
					s1.write("divFlash");
					</script></div>

					<div class="content_product_images_video_control">
					<div id="pnlVideoControl"><img id="blank_zoom"
					src="<?php url_site_image('zoom_blank.gif')?>" alt=""
					style='display: none; float: left; width: 20%'> <a id="hypPhotoImage"
					href="javascript:HideVideo();"
					style="display: none; float: left; width: 78%"> <img
					src="<?php url_site_image('product_ViewPictures.gif')?>"
					alt="Click here to go back to images" style="border-width: 0px;"> </a>
					</div>
					<?php
					}
					?>
					</div>
					<div id="pnlThumbImages" class="content_product_images_thumbimages">
					<ul class="hoverbox">
					<?php
					for($i=0;$i<count($icon_arr);$i++)
					{
					?>	
					<li><a
					href="javascript:ReplaceImageFromThumb(<?php echo ($i+1)?>);"
					onmouseover="window.status='Click on images to enlarge them'; return true;"
					onmouseout="window.status=''; return true;"
					title="Click on images to enlarge them"> <img
					id="imgThumb<?php echo ($i+2)?>" src="<?php echo $icon_arr[$i]?>"
					alt="Click on images to enlarge them" style="border-width: 1px;"></a>
					</li>
					<?php
					}
					?> 
					</ul>
					</div>
					</div>
					</div>
					</div>
					</td>
					</tr>
					</table>
					<span id="content_product_loading" style="display: none;"><span
					id="container"><img
					src="<?php url_site_image('product_loading_spinner.gif')?>"
					alt="Loading..." style="margin-left: 50%; margin-top: 50%;"></span></span>
					<script language="JavaScript">LoadTmpHolder()</script>
					<?php
}
function Show_Image_Normal($row_prod)
					{
					global $db,$ecomn_siteid,$ecom_hostname,$ecom_themename;	
					$show_normalimage = false;
					/*
					if ($_REQUEST['prod_curtab'] and $row_prod['product_variablecombocommon_image_allowed']=='N')
					{
					if ($_REQUEST['prodimgdet'])	
					$showonly = $_REQUEST['prodimgdet'];
					else
					$showonly = 0;
					// Calling the function to get the type of image to shown for current 
					$pass_type  = 'image_bigcategorypath';
					//$pass_type = get_default_imagetype('proddet');
					// Calling the function to get the image to be shown
					$tabimg_arr = get_imagelist('prodtab',$_REQUEST['prod_curtab'],$pass_type,0,$showonly,1);
					
					if(count($tabimg_arr))
					{
					$exclude_tabid = $tabimg_arr[0]['image_id']; 		// exclude id in case of multi images for products
					?>
					<a href="<?php url_root_image($tabimg_arr[0]['image_extralargepath'])?>"
					title="<?php echo $row_prod['product_name']?>" rel='lightbox[gallery]'>
					<?php
					show_image(url_root_image($tabimg_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name']);
					?>
					</a>
					<?php
					$show_noimage 	= false;
					}
					else
					$show_normalimage = true;
					}
					else
					*/ 
					$show_normalimage = true;
?>

<?php
					if ($show_normalimage)
					{
					if ($_REQUEST['prodimgdet'])	
					$showonly = $_REQUEST['prodimgdet'];
					else
					$showonly = 0;
					// Calling the function to get the type of image to shown for current 
					//$pass_type = get_default_imagetype('proddet');
					$pass_type  = 'image_bigpath';
					// Calling the function to get the image to be shown
					if($row_prod['product_variablecombocommon_image_allowed']=='Y')
					$prodimg_arr = get_imagelist_combination($row_prod['default_comb_id'],$pass_type,0,1);
					else
					$prodimg_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,0,$showonly,1);
					if(count($prodimg_arr))
					{
					$exclude_prodid = $prodimg_arr[0]['image_id']; 		// exclude id in case of multi images for products
					/*<a href="javascript:showImagePopup('<?php echo $prodimg_arr[0]['image_extralargepath']?>','<?php echo $ecom_hostname?>','<?php echo $ecom_themename?>')"  title="Click to Zoom">*/
					?>				
					
					<?php
					//echo url_root_image($prodimg_arr[0]['image_bigcategorypath'],1);
					//show_image(url_root_image($prodimg_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name']);
					
					list($sml_width,$sml_height) = getimagesize(ORG_DOCROOT.'/images/'.$ecom_hostname.'/'.$prodimg_arr[0]['image_bigcategorypath']);
					list($big_width,$big_height) = getimagesize(ORG_DOCROOT.'/images/'.$ecom_hostname.'/'.$prodimg_arr[0]['image_extralargepath']);
					
										
					if($sml_width and $sml_height and $big_width and $big_height)
					{
						if ($big_width<$sml_width or $big_height<$sml_height)
						{
							$extralarge_img = url_root_image($prodimg_arr[0]['image_bigpath'],1);
						}
						else
							$extralarge_img = url_root_image($prodimg_arr[0]['image_extralargepath'],1);
					}
					else
						$extralarge_img = url_root_image($prodimg_arr[0]['image_extralargepath'],1);
					?>
					    <div class="clearfix">
        <a href="<?php echo $extralarge_img?>" class="jqzoom" rel='gal1'   >
            <img src="<?php echo url_root_image($prodimg_arr[0]['image_bigpath'],1)?>"    style="border: 4px solid #666;">

</a>
</div>
					
					<?php
					$show_noimage 	= false;
					}
					else
					{	
					// calling the function to get the default no image 
					$no_img = get_noimage('prod','big'); 
					if ($no_img)
					{
						?><div class="no_img">
							<?php
					show_image($no_img,$row_prod['product_name'],$row_prod['product_name']);
					?>
					</div>
					<?php
					}	
					}
					}
					?>
					<?php
					$ret_arr['exclude_tabid']		= $exclude_tabid;
					$ret_arr['exclude_prodid'] 	= $exclude_prodid;
					return $ret_arr;
}
function show_buttons_ajax($row_prod)
					{
					global $Captions_arr,$showqty,$Settings_arr,$ecom_hostname,$db,$ecom_siteid;
					$addto_cart_withajax =  $Settings_arr['enable_ajax_in_site'];
					$cust_id 	= get_session_var("ecom_login_customer");
					//$showqty	= $Settings_arr['show_qty_box'];// show the qty box
					$showqty     = 1;
					$cur_qty_caption = ($row_prod['product_det_qty_caption']!='')?stripslashes($row_prod['product_det_qty_caption']):$Captions_arr['PROD_DETAILS']['PRODDET_QUANTITY'];
					// Get the caption to be show in the button
					$caption_key = show_addtocart($row_prod,array(0),'',true);
					
					?>
<div class="qty-detail">
		<ul class="qty-detail-area">
					
	<?php
					if ($caption_key!='') // Check whether the buynow or preorder button is to be displayed
					{
							?>
						
							<?php
							if($showqty==1)// this decision is made in the main shop settings
							{
								if($row_prod['product_det_qty_type']=='NOR')
								{
									?>
									<li><?php echo $cur_qty_caption?></li>
									<li><img src="<?php echo url_site_image('plus-qty.png')?>" width="20" height="20" onclick="increment_quantity('frm_proddetails_ajax')" /></li>
									<li><input type="text" class="text-field" name="qty" value="<?php echo ($_REQUEST['qty'])?$_REQUEST['qty']:1?>" maxlength="4" /></li>
									<li><img src="<?php echo url_site_image('minus-qty.png')?>" width="20" height="20" onclick="decrement_quantity('frm_proddetails_ajax')" /></li>
									<?php
								}
								elseif($row_prod['product_det_qty_type']=='DROP')
								{
									$dropdown_values = explode(',',stripslashes($row_prod['product_det_qty_drop_values']));
									if (count($dropdown_values) and stripslashes($row_prod['product_det_qty_drop_values'])!='')
									{
										?>
										<li><?php echo $cur_qty_caption?></li>
									<li><img src="<?php echo url_site_image('plus-qty.png')?>" width="20" height="20" onclick="increment_quantity_ajax(this)" /></li>

										
										<li><select name="qty">
										<?php 
										$qty_prefix = stripslashes($row_prod['product_det_qty_drop_prefix']);
										$qty_suffix = stripslashes($row_prod['product_det_qty_drop_suffix']);
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
										</li>
										<li><img src="<?php echo url_site_image('minus-qty.png')?>" width="20" height="20" onclick="decrement_quantity_ajax(this)" /></li>

										<?php				
									}				
								}
							}
							?>							
							<?php
									?>
								<li>
								<input name="Submit_buy" type="button" class="red-button" id="Submit_buy" value="<?php echo $Captions_arr['PROD_DETAILS'][$caption_key];?>"	onclick="ajax_addto_cart_fromlist('add_prod_tocart_ajax','Prod_Addcart','frm_proddetails_ajax','<?php echo SITE_URL?>')" />
								</li>
								<?php /*ajax_addto_cart('show_det',this.form,'<?php echo SITE_URL ?>') */?>
								<?php
							
							?>
									
							<?php
					}			
					?>
					</ul>
					</div>
					<?php
					if ($row_prod['product_show_enquirelink']==1 )
					{
								?>
								<div class="qty-detail">
		                        <ul class="qty-detail-area">
								
										<li><input name="Submit_enq" type="submit" class="buttonblackbuy" id="Submit_enq" value="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_ENQUIRY'];?>" onclick="ajax_addto_cart_fromlist('add_prod_tocart_ajax','Prod_Enquire','frm_proddetails_ajax','<?php echo SITE_URL?>')" /></li>
									<?php
							
								
								?>
								</ul>
								</div>
								
								<?php
						}
							
	 if($cust_id)
		{
			if($Settings_arr['proddet_showfavourite']==1)
			$favourite_show = 1;
		}
								/*
								 if ($Settings_arr['proddet_showwishlist'] || $favourite_show==1)
								{ 
									?>
									<ul class="list-icon">
									<?php
									
									if($cust_id) // ** Show the wishlist button only if logged in 
									{
										?>
										<li class="icon-star"><input name="submit_wishlist" type="submit" class="buttonblackbuy" id="submit_wishlist" value="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_ADDWISHLIST'];?>" onclick="show_wait_button(this,'Please Wait...');document.frm_proddetails_ajax.fpurpose.value='Prod_Addwishlist'" /></li>
										<?php
									}
									else
									{
										$wishlist_onclick = 'window.location=\'http://'.$ecom_hostname.'/wishlistcustlogin.html\'';
									?>
										<li class="icon-star"><input name="submit_wishlist" type="button" class="buttonblackbuy" id="submit_wishlist" value="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_ADDWISHLIST'];?>" onclick="<?php echo $wishlist_onclick?>" /></li>

									<?php
									}
									?>
									
									<?php		
								    /*
									if($favourite_show==1) // ** Show the add to favourite only if logged in 
									{
									$sql_prod = "SELECT products_product_id FROM customer_fav_products WHERE sites_site_id=$ecom_siteid AND products_product_id=".$_REQUEST['product_id']." AND customer_customer_id=$cust_id LIMIT 1";
									$ret_num= $db->query($sql_prod);
									if($db->num_rows($ret_num)==0) 
									{ 
									?>				<li class="icon-fav"><a href="#" class="productdetailslink" onclick="if(confirm('<? echo $Captions_arr['PROD_DETAILS']['PRODDET_ADD_CONFIRM'] ?>')){document.frm_proddetails_ajax.fpurpose.value='add_favourite';document.frm_proddetails_ajax.submit();}"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_FAVOURITE'];?></a></li>
									<?php
									}
									else
									{
									?>				<li class="icon-fav"><a href="#" class="productdetailslink" onclick="if(confirm('<? echo $Captions_arr['PROD_DETAILS']['PRODDET_REM_CONFIRM'] ?>')){document.frm_proddetails_ajax.fpurpose.value='remove_favourite';document.frm_proddetails_ajax.submit();}"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_REMFAVOURITE'];?></a></li>
									<?php
									}
									
									}
									
									
								?>
								</ul>
								<?php
								
								
								}
								*/ 
								/*
								if($Settings_arr['show_bookmarks'])
								{
									?>	<div class="share">
									<b><?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['BOOKMARK_HEADING'])?></b><br />
									<?php		bookmarks(url_product($row_prod['product_id'],$row_prod['product_name'],1),htmlspecialchars($meta_arr['title']),$ecom_twitteraccountId); 
									?>	</div>
									<?php	
								} 
								*/ 
					
					return true;
}
// ** Function to display the variables. This is written as function to avoid code repetation in various if conditions
function show_ProductVariables_ajax($row_prod,$pos='column',$product_id)
{
					
				global $db,$ecom_siteid,$Captions_arr;
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
				AND var_value_exists = 1 
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

				if ($pos!='column') // Check the case of labels only if value of pos is not 'column'
				{
				// Check whether any labels to be displayed for current product
				$sql_labels = "SELECT a.label_id,a.label_name,b.label_value,a.is_textbox,product_site_labels_values_label_value_id  
				FROM
				product_site_labels a,product_labels b 
				WHERE 
				b.products_product_id = ".$_REQUEST['product_id']." 
				AND a.label_hide = 0 
				AND a.label_id = b.product_site_labels_label_id";
				$ret_labels = $db->query($sql_labels);
				$label_exists = false;
				while ($row_labels = $db->fetch_array($ret_labels) and $label_exists==false) // added the AND condition to avoid the case of exiting the loop if atleast one label have value
				{
					$vals = '';
					if ($row_labels['is_textbox']==1)
					$vals = stripslashes($row_labels['label_value']);
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
							$vals = stripslashes($row_labelval['label_value']);
						}

					}
					if (trim($vals))
					{
					$label_exists = true;	
					}
				}	
				}
				// Check whether total number of variables is 1 or more than 1
				if($var_cnt==1)
				{
					$vardisp_type = $row_prod['product_variable_display_type']; // take the display type from settings for current product
				}
				else 
					$vardisp_type = 'ADD'; // if the variable count is > 1 then by default the Add option will be displayed

				if ($db->num_rows($ret_var) or $db->num_rows($ret_msg))
				{
						/*
						?>

						<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<?php
						if($pos!='column')
						{
							?>
							<tr>
							<td class="variable_bottom_border">
							<div class="variabletabcontainer">
							<ul class="variabletab">
							<li id="var_li" class="variableselected"
							<?php /* onclick is required only in shown in a new row*//*if ($pos=='row') {?>
							onclick="handle_proddetail_variable('var')" <?php }?>><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_PRODVARS']?></li>	
							<?php
							if($pos!='column' and $label_exists) // show the product labels section only if variables in a new row
							{
								?>						
								<li id="label_li" onclick="handle_proddetail_variable('label')"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_KEY_FEATURES']?></li>
								<?php
							}
							?>
							</ul>
							</div>
							</td>
							</tr>
							<?php
						}
						*/ 
						?>
						<div class="variable-wrap">
<?php
						/*
						?>
						<style type="text/css">

						
						</style>
*/
?>
						
						<?php
						// Case of variables
						if ($db->num_rows($ret_var) or $db->num_rows($ret_msg))
								{
									$cnt_div =0;
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
											$clssA = ($i%2==0)?'productvariabletdB':'productvariabletdB';
											$clssB = ($i%2==0)?'productvariabletdAB':'productvariabletdBB';

											$var_exists = true;
											
											if ($row_var['var_value_exists']==1)
											{  
                                             ?>
											<ul>
											<li><label><?php echo stripslashes($row_var['var_name'])?></label><span>
											<?php
													if($row_prod['product_variablecomboprice_allowed']=='Y' and $row_prod['product_variablecombocommon_image_allowed']=='Y')
													{
														$onchange_function      = " onchange ='handle_show_prod_det_bulk_disc(\"price\",\"".SITE_URL."\",\"".$_REQUEST['product_id']."\",\"".url_site_image('loading.gif',1)."\",\"ajax\")' ";
														$onclick_var            = "price";
													}
													elseif($row_prod['product_variablecomboprice_allowed']=='Y' and $row_prod['product_variablecombocommon_image_allowed']=='N')
													{
														$onchange_function      = " onchange ='handle_show_prod_det_bulk_disc(\"price\",\"".SITE_URL."\",\"".$_REQUEST['product_id']."\",\"".url_site_image('loading.gif',1)."\",\"ajax\")' ";
														$onclick_var            = "price";
													}
													elseif($row_prod['product_variablecomboprice_allowed']=='N' and $row_prod['product_variablecombocommon_image_allowed']=='Y')
													{
														$onchange_function      = $onchange_function      = " onchange ='handle_show_prod_det_bulk_disc(\"main_img\",\"".SITE_URL."\",\"".$_REQUEST['product_id']."\",\"".url_site_image('loading.gif',1)."\",\"ajax\")' ";
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
													{	      $proc_link = false;
														      if($ecom_siteid==	106)
																{
																	$var_namea = strtolower(trim($row_var['var_name']));
																	
																	if($var_namea=='insurance options')
																	{
																		$proc_link = true;
																		$onchange_function = "onchange = 'handle_show_value_link(\"".$_REQUEST['product_id']."\",\"".SITE_URL."\",\"".$row_var['var_id']."\",\"".url_site_image('loading.gif',1)."\")' ";
																	
																	}
																
																 }			
														?>
														<select name="var_<?php echo $row_var['var_id']?>" id="var_<?php echo $row_var['var_id']?>" <?php echo $onchange_function?> style='float:left'>
														<?php 
														$val_img_set =false;
														$curvalimg_sml = $curvalimg_big = '';
														while ($row_vals = $db->fetch_array($ret_vals))
														{
															
															// Check whether any image is set for this variable value
															if ($row_vals['images_image_id'])
															{
																$sql_valimg = "SELECT image_iconpath,image_bigpath 
																				FROM 
																					images 
																				WHERE 
																					image_id =".$row_vals['images_image_id']." 
																					AND sites_site_id = $ecom_siteid 
																				LIMIT 
																					1";	
																$ret_valimg = $db->query($sql_valimg);
																if($db->num_rows($ret_valimg))
																{
																	$row_valimg = $db->fetch_array($ret_valimg);
																	$curvalimg_sml = url_root_image($row_valimg['image_iconpath'],1);
																	$curvalimg_big = url_root_image($row_valimg['image_bigpath'],1);
																	$val_img_set = true;
																}
															}
															?>
															<option value="<?php echo $row_vals['var_value_id']?>" <?php echo ($_REQUEST['var_'.$row_var['var_id']]==$row_vals['var_value_id'])?'selected':''?>><?php echo stripslashes($row_vals['var_value'])?><?php echo Show_Variable_Additional_Price($row_prod,$row_vals['var_addprice'],$vardisp_type)?></option>
															<?php
														}
														?>
														</select>
														</span></li>
														</ul>	
														<?php
														if($val_img_set)
														{
														?>
														<div class="ienlarger" style='padding-left:10px;padding-top:3px'><a href="#"><img src="<?php echo $curvalimg_sml?>" border="0">
														<span><img src="<?php echo $curvalimg_big?>" alt="large" /><br />
														<div class='varhover_text'><?php echo $row_var['var_name']?></div></span></a>
														</div>						
														<?php
														}
														if($proc_link==true)
														{
														$HTML_loading_url = '<div class="proddet_loading_outer_div" style="height:15px"><div id="variable_linka_loading_div" style="display:none;padding:5px 0 0 0;">
														<img src="'.url_site_image('proddet_loading.gif',1).'" alt="loading..." width="43px" height="11px;align:left">
														</div></div>';
														//echo $HTML_loading_url;
														?>
														<div class="variable_linka" id="variable_linka" style="padding-top:5px;">
                                                        &nbsp;<a class="facts_class" href="javascript:show_pdf_new(&quot;http://dealer.premiercare.info/introducer/12771/TOB12771.pdf&quot;)">Key Facts</a>
                                                        </div>			
														<?php
														}
														
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
																	$onclick_function   = " onclick ='var_onclick(\"".$onclick_var."\",\"var_".$row_var['var_id']."\",".$row_vals['var_value_id'].",\"".$normal_cls_clr."\",\"".$special_cls_clr."\",\"".$normal_cls_clrimg."\",\"".$special_cls_clrimg."\",\"".$normal_cls_sz."\",\"".$special_cls_sz."\",\"".$docroot."\",\"".$prodid."\",\"".$loading_gif."\",\"\")' ";
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
													?>
													
													<?php
											}											
											?>
											
											<?php
											$i++;
										}

								}
								?>  
								<?php
						}
						// ######################################################
						// End of variables section
						// ######################################################

						// ##############################################################################
						//  Case of variable messages
						// ##############################################################################

						if ($db->num_rows($ret_msg))
							{
								?>
								<ul>
								<?php
								while ($row_msg = $db->fetch_array($ret_msg))
									{
										$clss = ($i%2==0)?'productvariabletdA':'productvariabletdB';
										$var_exists = true;
										?>
										<li><label><?php echo stripslashes($row_msg['message_title'])?></label>
										<span>
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
											<textarea name="varmsg_<?php echo $row_msg['message_id']?>" id="varmsg_<?php echo $row_msg['message_id']?>" rows="3" cols="15"><?php echo $_REQUEST['varmsg_'.$row_msg['message_id']]?></textarea>
											<?php
										}
										?>
										</span>
										</li>
										<?php
										$i++;
								}
								?> 
								</ul> 
								<?php		
						}
						// ######################################################
						// End of variable messages
						// ######################################################
						?>
						</div>
						<?php
				}
				$sql_var_checkbox = "SELECT var_id,var_name,var_value_exists, var_price,var_value_display_dropdown,images_image_id  
				FROM 
				product_variables 
				WHERE 
				products_product_id = ".$_REQUEST['product_id']." 
				AND var_hide= 0
				AND var_value_exists = 0 
				ORDER BY 
				var_order";
				$ret_var_checkbox = $db->query($sql_var_checkbox);
									if ($db->num_rows($ret_var_checkbox))
									{
									?>
									
									<?php
									?>
									<div class="pdvariablesTab">
										<?php
										while ($row_var_checkbox = $db->fetch_array($ret_var_checkbox))
										{
										$val_img_set_checkbox = false;
										// Check whether any image is set for this variable value
										if ($row_var_checkbox['images_image_id'])
										{
											$sql_valimg_checkbox = "SELECT image_iconpath,image_bigpath 
															FROM 
																images 
															WHERE 
																image_id =".$row_var_checkbox['images_image_id']." 
																AND sites_site_id = $ecom_siteid 
															LIMIT 
																1";	
											$ret_valimg_checkbox = $db->query($sql_valimg_checkbox);
											if($db->num_rows($ret_valimg_checkbox))
											{
												$row_valimg_checkbox = $db->fetch_array($ret_valimg_checkbox);
												$curvalimg_sml_checkbox = url_root_image($row_valimg_checkbox['image_iconpath'],1);
												$curvalimg_big_checkbox = url_root_image($row_valimg_checkbox['image_bigpath'],1);
												$val_img_set_checkbox = true;
											}
										}
										else
										{
											$curvalimg_sml_checkbox = '';
											$curvalimg_big_checkbox = '';
										}
										?>
														
										<div class="pdrow">

										<div class="ienlarger" style='padding-left:10px;padding-top:3px'>
										<?php
										if($curvalimg_big_checkbox=='' and $curvalimg_sml_checkbox=='')
										{
											$curvalimg_sml_checkbox = url_site_image('no_iconsamll_image.gif',1);
											$curvalimg_big_checkbox = '';//url_site_image('no_iconsamll_image.gif',1);
										}
										?>
											<a href="#"><img src="<?php echo $curvalimg_sml_checkbox?>" border="0">
										<span>
										<?php if ($curvalimg_big_checkbox!='')
										{
										?>
										<img src="<?php echo $curvalimg_big_checkbox?>" alt="large" />
										<?php
										}
										?>
										<br />
										<div class='varhover_text'><?php echo $row_var_checkbox['var_name']?></div></span></a>
										</div>	

										<div class="pdcol1"><?php echo stripslashes($row_var_checkbox['var_name'])?></div>
										<div class="pdcol2"><?php echo Show_Variable_Additional_Price($row_prod,$row_var_checkbox['var_price'],$vardisp_type)?></div><div class="pdcol3">
										<input type="checkbox" name="var_<?php echo $row_var_checkbox['var_id']?>" id="var_<?php echo $row_var_checkbox['var_id']?>" value="1" <?php echo ($_REQUEST['var_'.$row_var_checkbox['var_id']])?'checked="checked"':''?> />
										</div>
										</div>
										<?php
										}
										?>
										</div>									

										<?PHP
				}
				return $var_exists;
}
//* Function to get the count of variables
function get_countvariables_count($prod_id)
{
	global $db,$ecom_siteid,$Captions_arr;

		$sql_valuecnt = "SELECT count(var_id) as varcnts  
						FROM 
							product_variables 
						WHERE 
							products_product_id = '".$prod_id."' 
							AND var_value_exists=1 
							AND var_hide=0";
		$ret_valuecnt = $db->query($sql_valuecnt);
		if($db->num_rows($ret_valuecnt)>0)
		{
		$row_valuecnt = $db->fetch_array($ret_valuecnt);
		return $row_valuecnt['varcnts'];
		}
		else
		{
		  return 0;
		}
}
// ** Function to display the variables. This is written as function to avoid code repetation in various if conditions
function show_ProductVariables_specialdisplay($row_prod,$mod='normal')
{
				global $db,$ecom_siteid,$Captions_arr,$Settings_arr,$ecom_hostname,$ecom_themename;
				$i = 0;
				$product_id = $row_prod['product_id'];
				if($mod=='normal')
				{
				     $frm_name = "frm_proddetails";
				}
				else
				{
				    $frm_name = "frm_proddetails_ajax";
				}
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
						<div class="<?php echo ($mod=='normal')?'p_variable_A':'p_variable'; ?>">
						<?php
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
						  }		  
							if($sizechart_heading['size_Availvalues']==true) // If size chart is set to show in a pop up window
							{
							if(is_array($sizechart_heading))
							{
							?>
							<div class="deat_pdt_sizechart_A"><div class="sizechartleft"><div><a href="javascript:showsizechartPopup('<?php echo $row_prod['product_id']?>','<?php echo $ecom_hostname?>','<?php echo $ecom_themename?>')" title="<?php echo stripslash_normal($sizechartmain_title)?>">Size Chart<?php ?></a></div></div></div>
							<?php	
							}
							}
							if($sizechart_heading['size_Availvalues']==false && $row_prod['product_commonsizechart_link']!='')
							{
							?>
							<div class="deat_pdt_sizechart_A"><div class="sizechartleft"><div><a href="<?=$row_prod['product_commonsizechart_link']?>" title="SizeChart" 	target="<?=$row_prod['produt_common_sizechart_target']?>">Size Chart<?php ?></a></div></div></div>
							<?
							}
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
											$clss = ($i%2==0)?'productvariabletdA':'productvariabletdB';
											$var_exists = true;
											$var_disp_type ='DROPDOWN';
											$comb_arr =array();
											$var_arr  = array();
											$var_id = '';
											$value_id = '';
												while ($row_vals = $db->fetch_array($ret_vals))
												{
													$stock_arr =array();
													$var_id = $row_var['var_id'];
													$value_id = $row_vals['var_value_id'];
												//$row_prod['combination_id'] = '';
                                                $var_arr[$var_id] =  $value_id;
												$comb_arr = get_combination_id($product_id,$var_arr);
												$row_prod['combination_id'] 		= $comb_arr['combid']; // this done to handle the case of showing the variables price in show price
												$row_prod['check_comb_price'] 	= 'YES';// this done to handle the case of showing the variables price in show price
												$frm = 'frm_prdtdetails_'.$value_id;
												?>
												
												<input type="hidden" id="var_<?php echo $var_id?>" name="var_<?php echo $var_id?>" value="<?php echo $value_id?>"/>

												<div class="<?php echo ($mod=='normal')?'p_variable_variable_name_A':'p_variable_variable_name'; ?>"><?php echo stripslashes($row_var['var_name'])?>&nbsp;:&nbsp;<?php echo stripslashes($row_vals['var_value'])?></div>
												<div class="<?php echo ($mod=='normal')?'p_variable_price_C_A':'p_variable_price_C'; ?>">
													
													<?php													
													$price_class_arr['ul_class'] 			= 'prodeulprice';
													$price_class_arr['normal_class'] 		= 'productdetnormalprice';
													$price_class_arr['strike_class'] 		= 'productdetstrikeprice';
													$price_class_arr['yousave_class'] 	= 'productdetyousaveprice';
													$price_class_arr['discount_class'] 	= 'productdetdiscountprice';
													/* Code for ajax setting starts here */
													$row_prod['cur_var_value_id'] 		= $value_id;
													/* Code for ajax setting ends here */
													echo show_Price($row_prod,$price_class_arr,'prod_detail');?>
													<?php
													/*
													<ul class="prodeuladprice">
														<li><?php echo Show_Variable_Additional_Price($row_prod,$row_vals['var_addprice'],$vardisp_type);?></li>
													</ul>
													*/?> 
													</div>
												<?php
												$bulkdisc_details = product_BulkDiscount_Details($product_id,$comb_arr['combid']);
												if (count($bulkdisc_details['qty']))
												{
												?>	
												 <div class="<?php echo ($mod=='normal')?'variable_inner_a_A':'variable_inner_a'; ?>" id="bulkdisc_holder">

													  <?php
														for($i=0;$i<count($bulkdisc_details['qty']);$i++)
														{
														?>	
															 <?php echo $Captions_arr['PROD_DETAILS']['BULK_BUY']?> <?php echo $bulkdisc_details['qty'][$i].' '.$Captions_arr['PROD_DETAILS']['BULK_FOR'].' '?> 
																<?php //echo print_price($bulkdisc_details['price'][$i]).' '.$Captions_arr['PROD_DETAILS']['BULK_EACH']?>
																<?php 	echo product_BulkDiscount_Build_Price($bulkdisc_details['price'][$i],$bulkdisc_details['price_without'][$i]).' '.stripslash_normal($Captions_arr['PROD_DETAILS']['BULK_EACH']);?>
																<br />
													  <?php
														}
													  ?>
													  
													  </div>
												<?php
												}
																					?>
												<div class="<?php echo ($mod=='normal')?'p_variable_buy_Az':'p_variable_buy'; ?>">
												<?php
												$showqty	= $Settings_arr['show_qty_box'];// show the qty box
												$cur_qty_caption = ($row_prod['product_det_qty_caption']!='')?stripslashes($row_prod['product_det_qty_caption']):$Captions_arr['PROD_DETAILS']['PRODDET_QUANTITY'];
												$caption_key    = show_addtocart($row_prod,array(0),'',true);
												$stock_arr  	= check_stock_available($product_id,$var_arr);
												$stock 		    = $stock_arr['stock'];
												if($caption_key=='' || ($stock==0 && $caption_key!='PRODDET_PREORDER') )
												{
												?>
												  <div class="<?php echo ($mod=='normal')?'p_variable_enquire_A_A':'p_variable_enquire_A_A'; ?>"><a href="javascript:void(0)" onclick="ajax_addto_cart_special_display('add_prod_tocart_ajax','Prod_Enquire','<?php echo $frm_name ?>','<?php echo SITE_URL?>','<?php echo $var_id ?>','<?php echo $value_id ?>')"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_ENQUIRY'];?></a></div>									
												<?php
												}
												else
												{
												if($showqty)
												{	
													if($row_prod['product_det_qty_type']=='NOR')
													{
													?>
													<div class="<?php echo ($mod=='normal')?'p_variable_buy_A_A':'p_variable_buy_A'; ?>"><?php echo $cur_qty_caption ?></div>											
													<div class="<?php echo ($mod=='normal')?'p_variable_buy_B_A':'p_variable_buy_B'; ?>"><input name="varqty_<?php echo $value_id?>" value="1" type="text" maxlength="4" /></div>
													<?php
													}
													elseif($row_prod['product_det_qty_type']=='DROP')
													{
														$dropdown_values = explode(',',stripslashes($row_prod['product_det_qty_drop_values']));
														if (count($dropdown_values) and stripslashes($row_prod['product_det_qty_drop_values'])!='')
														{
														?>
															<div class="<?php echo ($mod=='normal')?'p_variable_buy_A_A':'p_variable_buy_A'; ?>"><?php echo $cur_qty_caption ?></div>
															<div class="<?php echo ($mod=='normal')?'p_variable_buy_B_A':'p_variable_buy_B'; ?>">
															<select name="varqty_<?php echo $value_id?>">
															<?php 
																$qty_prefix = stripslashes($row_prod['product_det_qty_drop_prefix']);
																$qty_suffix = stripslashes($row_prod['product_det_qty_drop_suffix']);
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
												<div class="p_variable_buy_C_A"><a href="javascript:void(0)" onclick="ajax_addto_cart_special_display('add_prod_tocart_ajax','Prod_Addcart','<?php echo $frm_name ?>','<?php echo SITE_URL?>','<?php echo $var_id ?>','<?php echo $value_id ?>')"><?php echo $Captions_arr['PROD_DETAILS'][$caption_key];?></a></div>
												<div class="p_variable_buy_C_Otr">
													<?php
													if ($row_prod['product_show_enquirelink']==1)
													{
													?>	
														<div class="p_variable_buy_C_Q"><a href="javascript:void(0)" onclick="ajax_addto_cart_special_display('add_prod_tocart_ajax','Prod_Enquire','<?php echo $frm_name ?>','<?php echo SITE_URL?>','<?php echo $var_id ?>','<?php echo $value_id ?>')"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_ENQUIRY'];?></a></div>									
													<?php 
													}
													if ($Settings_arr['proddet_showwishlist'])
													{
													?>
														<div class="p_variable_buy_C_W"><a href="javascript:void(0)" onclick="ajax_addto_cart_special_display('add_prod_tocart_ajax','Prod_Addwishlist','<?php echo $frm_name ?>','<?php echo SITE_URL?>','<?php echo $var_id ?>','<?php echo $value_id ?>')"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_ADDWISHLIST'];?></a></div>									
													<?php
													}
													?>
													</div>												<?php
											    }											   
												?>
												</div>
												
												<?php
												}
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
								?>
								<?php
								while ($row_msg = $db->fetch_array($ret_msg))
									{
										$clss = ($i%2==0)?'productvariabletdA':'productvariabletdB';
										$var_exists = true;
										?>
										<div class="<?php echo ($mod=='normal')?'p_variable_Mes_A':'p_variable_Mes'; ?>">
										<div class="<?php echo ($mod=='normal')?'p_variable_Mes_A_A':'p_variable_Mes_A'; ?>"><?php echo stripslashes($row_msg['message_title'])?></div>
										<div class="<?php echo ($mod=='normal')?'p_variable_Mes_B_A':'p_variable_Mes_B'; ?>">
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
											<textarea name="varmsg_<?php echo $row_msg['message_id']?>" id="varmsg_<?php echo $row_msg['message_id']?>" rows="3" cols="15"><?php echo $_REQUEST['varmsg_'.$row_msg['message_id']]?></textarea>
											<?php
										}
										?>
										</div>	
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
						</div>
						<?php
				}
				return $var_exists;
}
};	
?>
