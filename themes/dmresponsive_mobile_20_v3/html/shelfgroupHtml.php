<?php
	/*############################################################################
	# Script Name 	: shelfgroupHtml.php
	# Description 	: Page which holds the display logic for middle shelves
	# Coded by 		: Joby
	# Created on	: 09-May-2011
	# Modified by	: 
	# Modified On	: 
	##########################################################################*/
	class shelfgroup_Html
	{
		// Defining function to show the shelf details
		function Show_Shelfgroup($cur_title,$shelfgroup_arr)
		{
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents,$shelf_for_inner,$position_1;
			global $js_owl,$css_owl;
			global $from_home;

			if (count($shelfgroup_arr))
			{ 
				//print_r($Settings_arr);
				$shelfsort_by			= $Settings_arr['product_orderfield_shelf'];
				$prodperpage			= $Settings_arr['product_maxcntperpage_shelf'];// product per page
			
				switch ($shelfsort_by)
				{
					case 'custom': // case of order by customer fiekd
					$shelfsort_by		= 'b.product_order';
					break;
					case 'product_name': // case of order by product name
					$shelfsort_by		= 'a.product_name';
					break;
					case 'price': // case of order by price
					$shelfsort_by		= 'a.product_webprice';
					break;
					case 'product_id': // case of order by price
					$prodsort_by		= 'a.product_id';
					break;	
					default: // by default order by product name
					$shelfsort_by		= 'a.product_name';
					break;
				};
				$shelfsort_order		= $Settings_arr['product_orderby_shelf'];
				$prev_shelf				= 0;
				$show_max               =0;
				//print_r($shelfgroup_arr);
				if($from_home==true)
				{
					//print_r($shelfgroup_arr);
				?>
				 <?php 	   
		echo   " <link href=\"".url_head_link("images/".$ecom_hostname."/css/easy-responsive-tabs.css",1)."\" rel=\"stylesheet\">";
    ?>
    <link href="http://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">

		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>

<script type="text/javascript" src="<?php echo url_head_link("images/".$ecom_hostname."/scripts/easy-responsive-tabs.js",1)?>"></script>
		
     <?php if($position_1=='bottom')
     { 
     ?>
    
      <div class="col-sm recommendation" data-aos="fade-right">
	<p class="head-title">Featured Products</p>
      <div class="row">
  <div class="col">
	  <div class="demo2">
<div id="horizontalTab1">

		 <?php
                    
                  foreach ($shelfgroup_arr as $k=>$shelfgroupData)
                    /*1*/	{
                    
                    $shelfgroup_id = $shelfgroupData['id'];
                    
                    $sql_shelf_tab = "SELECT a.shelf_id,a.shelf_name
                    FROM 
                    product_shelf a LEFT JOIN shelf_group_shelf b 
                    ON (a.shelf_id = b.shelf_shelf_id ) 
                    WHERE
                    a.sites_site_id = $ecom_siteid 
                    AND b.shelf_group_id  = $shelfgroup_id 
                    AND a.shelf_hide = 0
                    ORDER BY a.shelf_order 
                    ";
                    $ret_shelf_tab = $db->query($sql_shelf_tab);
                    if ($db->num_rows($ret_shelf_tab))// Check whether result is there
                    {
						?>	    <ul class="resp-tabs-list">


						<?php
                    $cnt2 =1;
                    while ($row_shelf_tab = $db->fetch_array($ret_shelf_tab))
                    {
						if($cnt2 ==1)
						{
						  $active ='class="active"';
						}
						else
						{
						  $active ='';
						}
						?>
						
						<li><?php echo stripslashes($row_shelf_tab['shelf_name']); ?></li>
						
                    <?php                   
                    $cnt2++;
                    }
                    ?>
                    </ul>
		
                    <?php
                    }		
                    ?>
		
		<?php				
       
                    $sql_shelf = "SELECT a.shelf_id,a.shelf_name,a.shelf_description,a.shelf_displaytype,shelf_showimage,shelf_showtitle,
                    shelf_showdescription,shelf_showprice,shelf_currentstyle,shelf_activateperiodchange,
                    shelf_displaystartdate,shelf_displayenddate,a.shelf_showrating,a.shelf_showbonuspoints    
                    FROM 
                    product_shelf a LEFT JOIN shelf_group_shelf b 
                    ON (a.shelf_id = b.shelf_shelf_id ) 
                    WHERE
                    a.sites_site_id = $ecom_siteid 
                    AND b.shelf_group_id  = $shelfgroup_id 
                    AND a.shelf_hide = 0
                    ORDER BY a.shelf_order 
                    ";
                    
                    
                    $ret_shelf = $db->query($sql_shelf);
				if ($db->num_rows($ret_shelf))// Check whether result is there
				/*2*/{
				?>
<div class="resp-tabs-container">
									<?php
									$cnt = 1;
									$css_owl = '';
									$js_owl  = '';
									while ($shelfData = $db->fetch_array($ret_shelf))
									/*3*/		{
										// Get the total number of product in current shelf
									$sql_totprod = "SELECT count(b.products_product_id) 
									FROM 
									products a,product_shelf_product b 
									WHERE 
									b.product_shelf_shelf_id = ".$shelfData['shelf_id']." 
									AND a.product_id = b.products_product_id 
									AND a.product_hide = 'N' ";
									$ret_totprod 	= $db->query($sql_totprod);
									list($tot_cnt) 	= $db->fetch_array($ret_totprod); 

									// Call the function which prepares variables to implement paging
									$ret_arr 		= array();

									// Get the list of products to be shown in current shelf
									$sql_prod = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
									a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
									a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
									product_total_preorder_allowed,a.product_applytax,a.product_shortdesc,a.product_bonuspoints,
									a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,a.product_variablesaddonprice_exists,
									a.product_variablecomboprice_allowed,a.product_variablecombocommon_image_allowed,a.default_comb_id,
									a.price_normalprefix,a.price_normalsuffix, a.price_fromprefix, a.price_fromsuffix,a.price_specialofferprefix, a.price_specialoffersuffix, 
									a.price_discountprefix, a.price_discountsuffix, a.price_yousaveprefix, a.price_yousavesuffix,a.price_noprice,
									a.product_averagerating,a.product_saleicon_show,a.product_saleicon_text,a.product_newicon_show,a.product_newicon_text,
									a.product_freedelivery         
									FROM 
									products a,product_shelf_product b 
									WHERE 
									b.product_shelf_shelf_id = ".$shelfData['shelf_id']." 
									AND a.product_id = b.products_product_id 
									AND a.product_hide = 'N' 
									ORDER BY 
									$shelfsort_by $shelfsort_order 
									$Limit	";
									$ret_prod = $db->query($sql_prod);
									
											?> <div><p><?php
															$prodcur_arr = array();
															
														if ($db->num_rows($ret_prod))
														{
														$comp_active = isProductCompareEnabled();
														$pass_type = get_default_imagetype('midshelf');	
														while($row_prod = $db->fetch_array($ret_prod))
														{
															?>
															<div class="row">
															<div class="col-md-3 img-wrap"><a class="product_pic_a" href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" <?php //echo getmicrodata_producturl()?> >
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
																		</a>	</div>
															<div class="col-md-9">  <h2 class="heading">
															<a class="product_name_a" href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a>
										</h2>
															<p><?php echo $row_prod['product_shortdesc'];?></p>
															<?php
															$price_arr =  show_Price($row_prod,$price_class_arr,'cat_detail_1',false,5);
															$was_price = str_replace('+ VAT','',$price_arr['prince_without_captions']['base_price']);
															$save_price = str_replace('+ VAT','',$price_arr['prince_without_captions']['yousave_price']);
															if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
																{
																	$price_class_arr['ul_class'] 		= 'price-avl';
																	$price_class_arr['li_class'] 		= 'price';
																	$price_class_arr['normal_class'] 	= 'price';
																	$price_class_arr['strike_class'] 	= 'price_strike';
																	$price_class_arr['yousave_class'] 	= 'price_yousave';
																	$price_class_arr['discount_class'] 	= 'price_offer';
																	
																	//echo show_Price($row_prod,$price_class_arr,'shelfcenter_1');
																  
																  }
																 // print_r($price_arr);
																  $disc =  $price_arr['prince_without_captions']['discounted_price'];
																  $base =  $price_arr['prince_without_captions']['base_price'];
															
																	$curprice_tax_cap = curprice_tax($price_arr,$row_prod);
														   ?>
															<p class="price-details">
																<?php
																$price_arr = array();
		//$price_arr =  show_Price($row_featured,array(),'featured',false,3);
		//if($row_prod['featured_showprice']==1)// Check whether price is to be displayed
		{
?>			<div class="featuredprice">
<?php		
			$price_class_arr['ul_class'] 			= 'prodeulprice';
			$price_class_arr['normal_class'] 		= 'productdetnormalprice';
			$price_class_arr['strike_class'] 		= 'productdetstrikeprice';
			$price_class_arr['yousave_class'] 	= 'productdetyousaveprice';
			$price_class_arr['discount_class'] 	= 'productdetdiscountprice';
			$price_class_arr['emi_class'] 		= 'emi_price_details';
			$price_class_arr['emi_addclass'] 	= 'emi_addclass';
						//$price_class_arr['from_detailspage'] 	= 1;

			//	echo show_Price($row_prod,$price_class_arr,'shelfcenter_1'); price_categorydetails_1_reqbreak
			echo show_Price($row_prod,$price_class_arr,'shelfgroup');	


			/*if($price_arr['discounted_price'])
				echo $price_arr['discounted_price'];
			else
				echo $price_arr['base_price'];*/
			show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
?>			</div>
<?php	}
?>
															</p>
															<?php
$price_bal2 = show_Price($row_prod,$price_class_arr,'other_3',false,5);
if($price_bal2['prince_without_captions']['discounted_price'])
{
	$calcpricearr = explode('&pound;',$price_bal2['prince_without_captions']['discounted_price']);
	$calcprice  = $calcpricearr[1];
}
else
{
	$calcpricearr = explode('&pound;',$price_bal2['prince_without_captions']['base_price']);
	$calcprice  = $calcpricearr[1];
}
//print_r($price_bal2);		   
	$calcprice = $calcprice + $calcprice*.05;
?>
<script type="text/javascript">
							var my_fd_obj = new FinanceDetails("<?php echo $fin_code; ?>", <?php echo $calcprice;?>, 10, 0);
							/*alert('here');*/
								$("#finpermonth_<?php echo $row_prod['product_id']?>").html("&pound;"+(my_fd_obj.m_inst).toFixed(2));
								
							</script> 


															<div class="addwrap">										
															<?php $frm_name = uniqid('shelfgroup_'); ?>
															<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
																	<input type="hidden" name="fpurpose" value="" />
																	<input type="hidden" name="fproduct_id" value="" />
																	<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
																	<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
													<?php			$class_arr['ADD_TO_CART']       = 'btn btn-outline-secondary addbt';
																	$class_arr['PREORDER']          = 'input-group-addon';
																	$class_arr['ENQUIRE']           = 'btncart addcartbtn btn btn-outline-secondary enqbt';
																	$class_arr['QTY_DIV']           = '';
																	$class_arr['QTY']               = 'form-control qty_txt';
																	$class_arr['BTN_CLS']     = 'btn btn-outline-secondary addbt';
																	echo show_addtocart_responsive($frm_name,$row_prod,$class_arr,1,'shelf',false,false,'new_v2');?>
																	
																	<a class="btn btn-outline-secondary detailbt" href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">Details</a>
																																		</form>

															</div>

															</div>
															</div>
															<?php
															}
														}
															?>
												</p>
												</div>
												<!-- .tab_container -->
												<?php
												$cnt++;
										
									}
									?>
					</div>
<?php
					}
				}
?>
</div>
</div>
</div>
</div>
</div>
					<script>
$(document).ready(function () {
$('#horizontalTab1').easyResponsiveTabs({
type: 'default', //Types: default, vertical, accordion           
width: 'auto', //auto or any width like 600px
fit: true,   // 100% fit in a container
closed: 'accordion', // Start closed if in accordion view
activate: function(event) { // Callback function if tab is switched
var $tab = $(this);
var $info = $('#tabInfo');
var $name = $('span', $info);
$name.text($tab.text());
$info.show();
}
});
});
</script>
                   
						
                <?php
	 }
	 else if($position_1=='bottom22')
     {
	 }
	 else
	 { 
     ?>
    
      <div class="col-sm recommendation" data-aos="fade-right">
	<p class="head-title">RECOMMENDATIONS</p>
      <div class="row">
  <div class="col">
	  <div class="demo">
<div id="horizontalTab">

		 <?php
                    
                  foreach ($shelfgroup_arr as $k=>$shelfgroupData)
                    /*1*/	{
                    
                    $shelfgroup_id = $shelfgroupData['id'];
                    
                    $sql_shelf_tab = "SELECT a.shelf_id,a.shelf_name
                    FROM 
                    product_shelf a LEFT JOIN shelf_group_shelf b 
                    ON (a.shelf_id = b.shelf_shelf_id ) 
                    WHERE
                    a.sites_site_id = $ecom_siteid 
                    AND b.shelf_group_id  = $shelfgroup_id 
                    AND a.shelf_hide = 0
                    ORDER BY a.shelf_order 
                    ";
                    $ret_shelf_tab = $db->query($sql_shelf_tab);
                    if ($db->num_rows($ret_shelf_tab))// Check whether result is there
                    {
						?>	    <ul class="resp-tabs-list">


						<?php
                    $cnt2 =1;
                    while ($row_shelf_tab = $db->fetch_array($ret_shelf_tab))
                    {
						if($cnt2 ==1)
						{
						  $active ='class="active"';
						}
						else
						{
						  $active ='';
						}
						?>
						
						<li><?php echo stripslashes($row_shelf_tab['shelf_name']); ?></li>
						
                    <?php                   
                    $cnt2++;
                    }
                    ?>
                    </ul>
		
                    <?php
                    }		
                    ?>
		
		<?php				
       
                    $sql_shelf = "SELECT a.shelf_id,a.shelf_name,a.shelf_description,a.shelf_displaytype,shelf_showimage,shelf_showtitle,
                    shelf_showdescription,shelf_showprice,shelf_currentstyle,shelf_activateperiodchange,
                    shelf_displaystartdate,shelf_displayenddate,a.shelf_showrating,a.shelf_showbonuspoints    
                    FROM 
                    product_shelf a LEFT JOIN shelf_group_shelf b 
                    ON (a.shelf_id = b.shelf_shelf_id ) 
                    WHERE
                    a.sites_site_id = $ecom_siteid 
                    AND b.shelf_group_id  = $shelfgroup_id 
                    AND a.shelf_hide = 0
                    ORDER BY a.shelf_order 
                    ";
                    
                    
                    $ret_shelf = $db->query($sql_shelf);
				if ($db->num_rows($ret_shelf))// Check whether result is there
				/*2*/{
				?>
<div class="resp-tabs-container">
									<?php
									$cnt = 1;
									$css_owl = '';
									$js_owl  = '';
									while ($shelfData = $db->fetch_array($ret_shelf))
									/*3*/		{
										// Get the total number of product in current shelf
									$sql_totprod = "SELECT count(b.products_product_id) 
									FROM 
									products a,product_shelf_product b 
									WHERE 
									b.product_shelf_shelf_id = ".$shelfData['shelf_id']." 
									AND a.product_id = b.products_product_id 
									AND a.product_hide = 'N' ";
									$ret_totprod 	= $db->query($sql_totprod);
									list($tot_cnt) 	= $db->fetch_array($ret_totprod); 

									// Call the function which prepares variables to implement paging
									$ret_arr 		= array();

									// Get the list of products to be shown in current shelf
									$sql_prod = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
									a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
									a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
									product_total_preorder_allowed,a.product_applytax,a.product_shortdesc,a.product_bonuspoints,
									a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,a.product_variablesaddonprice_exists,
									a.product_variablecomboprice_allowed,a.product_variablecombocommon_image_allowed,a.default_comb_id,
									a.price_normalprefix,a.price_normalsuffix, a.price_fromprefix, a.price_fromsuffix,a.price_specialofferprefix, a.price_specialoffersuffix, 
									a.price_discountprefix, a.price_discountsuffix, a.price_yousaveprefix, a.price_yousavesuffix,a.price_noprice,
									a.product_averagerating,a.product_saleicon_show,a.product_saleicon_text,a.product_newicon_show,a.product_newicon_text,
									a.product_freedelivery         
									FROM 
									products a,product_shelf_product b 
									WHERE 
									b.product_shelf_shelf_id = ".$shelfData['shelf_id']." 
									AND a.product_id = b.products_product_id 
									AND a.product_hide = 'N' 
									ORDER BY 
									$shelfsort_by $shelfsort_order 
									$Limit	";
									$ret_prod = $db->query($sql_prod);
									
											?> <div><p><?php
															$prodcur_arr = array();
															
														if ($db->num_rows($ret_prod))
														{
														$comp_active = isProductCompareEnabled();
														$pass_type = get_default_imagetype('midshelf');	
														while($row_prod = $db->fetch_array($ret_prod))
														{
															?>
															<div class="row">
															<div class="col-md-3 img-wrap"><a class="product_pic_a" href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" <?php //echo getmicrodata_producturl()?> >
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
																		</a>	</div>
															<div class="col-md-9">  <h2 class="heading">
															<a class="product_name_a" href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a>
										</h2>
															<p><?php echo $row_prod['product_shortdesc'];?></p>
															<?php
															$price_arr =  show_Price($row_prod,$price_class_arr,'cat_detail_1',false,5);
															$was_price = str_replace('+ VAT','',$price_arr['prince_without_captions']['base_price']);
															$save_price = str_replace('+ VAT','',$price_arr['prince_without_captions']['yousave_price']);
															if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
																{
																	$price_class_arr['ul_class'] 		= 'price-avl';
																	$price_class_arr['li_class'] 		= 'price';
																	$price_class_arr['normal_class'] 	= 'price';
																	$price_class_arr['strike_class'] 	= 'price_strike';
																	$price_class_arr['yousave_class'] 	= 'price_yousave';
																	$price_class_arr['discount_class'] 	= 'price_offer';
																	
																	//echo show_Price($row_prod,$price_class_arr,'shelfcenter_1');
																  
																  }
																 // print_r($price_arr);
																  $disc =  $price_arr['prince_without_captions']['discounted_price'];
																  $base =  $price_arr['prince_without_captions']['base_price'];
															
																	$curprice_tax_cap = curprice_tax($price_arr,$row_prod);
														   ?>
															<p class="price-details">
																<?php
																$price_arr = array();
		//$price_arr =  show_Price($row_featured,array(),'featured',false,3);
		//if($row_prod['featured_showprice']==1)// Check whether price is to be displayed
		{
?>			<div class="featuredprice">
<?php		
			$price_class_arr['ul_class'] 			= 'prodeulprice';
			$price_class_arr['normal_class'] 		= 'productdetnormalprice';
			$price_class_arr['strike_class'] 		= 'productdetstrikeprice';
			$price_class_arr['yousave_class'] 	= 'productdetyousaveprice';
			$price_class_arr['discount_class'] 	= 'productdetdiscountprice';
			$price_class_arr['emi_class'] 		= 'emi_price_details';
			$price_class_arr['emi_addclass'] 	= 'emi_addclass';
						//$price_class_arr['from_detailspage'] 	= 1;

			//	echo show_Price($row_prod,$price_class_arr,'shelfcenter_1'); price_categorydetails_1_reqbreak
			echo show_Price($row_prod,$price_class_arr,'shelfgroup');	


			/*if($price_arr['discounted_price'])
				echo $price_arr['discounted_price'];
			else
				echo $price_arr['base_price'];*/
			show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
?>			</div>
<?php	}
?>
															</p>
															<?php
$price_bal2 = show_Price($row_prod,$price_class_arr,'other_3',false,5);
if($price_bal2['prince_without_captions']['discounted_price'])
{
	$calcpricearr = explode('&pound;',$price_bal2['prince_without_captions']['discounted_price']);
	$calcprice  = $calcpricearr[1];
}
else
{
	$calcpricearr = explode('&pound;',$price_bal2['prince_without_captions']['base_price']);
	$calcprice  = $calcpricearr[1];
}
//print_r($price_bal2);		   
	$calcprice = $calcprice + $calcprice*.05;
?>
<script type="text/javascript">
							var my_fd_obj = new FinanceDetails("<?php echo $fin_code; ?>", <?php echo $calcprice;?>, 10, 0);
							/*alert('here');*/
								$("#finpermonth_<?php echo $row_prod['product_id']?>").html("&pound;"+(my_fd_obj.m_inst).toFixed(2));
								
							</script> 


															<div class="addwrap">										
															<?php $frm_name = uniqid('shelfgroup_'); ?>
															<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
																	<input type="hidden" name="fpurpose" value="" />
																	<input type="hidden" name="fproduct_id" value="" />
																	<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
																	<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
													<?php			$class_arr['ADD_TO_CART']       = 'btn btn-outline-secondary addbt';
																	$class_arr['PREORDER']          = 'input-group-addon';
																	$class_arr['ENQUIRE']           = 'btncart addcartbtn btn btn-outline-secondary enqbt';
																	$class_arr['QTY_DIV']           = '';
																	$class_arr['QTY']               = 'form-control qty_txt';
																	$class_arr['BTN_CLS']     = 'btn btn-outline-secondary addbt';
																	echo show_addtocart_responsive($frm_name,$row_prod,$class_arr,1,'shelf',false,false,'new_v2');?>
																	
																	<a class="btn btn-outline-secondary detailbt" href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">Details</a>
																																		</form>

															</div>

															</div>
															</div>
															<?php
															}
														}
															?>
												</p>
												</div>
												<!-- .tab_container -->
												<?php
												$cnt++;
										
									}
									?>
					</div>
<?php
					}
				}
?>
</div>
</div>
</div>
</div>
</div>
					<script>
$(document).ready(function () {
$('#horizontalTab').easyResponsiveTabs({
type: 'default', //Types: default, vertical, accordion           
width: 'auto', //auto or any width like 600px
fit: true,   // 100% fit in a container
closed: 'accordion', // Start closed if in accordion view
activate: function(event) { // Callback function if tab is switched
var $tab = $(this);
var $info = $('#tabInfo');
var $name = $('span', $info);
$name.text($tab.text());
$info.show();
}
});
});
</script>
                   
						
                <?php
					}
					
				}	
			}	
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
	};	
?>
