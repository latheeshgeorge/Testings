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
		global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$Captions_arr;
		$row_featured = $db->fetch_array($ret_featured);
		$Captions_arr['COMMON'] 	= getCaptions('COMMON');
		
		$sql_gtparam = "SELECT * FROM finance_paymentgateway_details WHERE sites_site_id = $ecom_siteid LIMIT 1";
		$ret_gtparam = $db->query($sql_gtparam);
		if($db->num_rows($ret_gtparam))
		{
			$row_gtparam = $db->fetch_array($ret_gtparam);
			$API_key = trim($row_gtparam['finpay_apikey']);
			$INST_Id = trim($row_gtparam['finpay_installationid']);
		}
		$sql_getc = "SELECT finance_id,finance_rate,finance_code FROM finance_details WHERE sites_site_id = $ecom_siteid and finance_code='ONIB48-15.9' LIMIT 1";
		$ret_getc = $db->query($sql_getc);
		if($db->num_rows($ret_getc))
		{
		$row_getc = $db->fetch_array($ret_getc);
		$fin_code = $row_getc['finance_code'];
		}
		
?>	
<script  type="text/javascript" src="https://test.dekopay.com/js/libraries/jquery/jquery-3.3.1.min.js"></script>
										<script type="text/javascript" src="https://secure.dekopay.com/js_api/FinanceDetails.js.php?api_key=<?php echo $API_key ?>"></script>
							
<form method="post" action="<?php url_link('manage_products.html')?>" name='frm_featured' id="frm_featured" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_featured['product_id']?>)">
		<input type="hidden" name="fpurpose" value="" />
		<input type="hidden" name="fproduct_id" value="" />
		<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
		<input type="hidden" name="fproduct_url" value="<?php url_product($row_featured['product_id'],$row_featured['product_name'])?>" />
		<table style="border:0;border-spacing: 0;border-collapse: collapse;width:100%" class="featured_table">
		<tbody>
		<tr>
		  <td colspan="2" class="featuredproddet" style="text-align:left;vertical-align:top">
          <?php	if($title)
				{
		?>			<div class="featuredheader"><?php echo $title?></div>
		<?php	}
		?> 	</td>
		  </tr>
		<tr>
			
			<td class="featuredprodimg" style="text-align:left;vertical-align:top">
<?php 	if ($row_featured['featured_showimage']==1)
		{
?>		   <a href="<?php url_product($row_featured['product_id'],$row_featured['product_name'],-1)?>" title="<?php echo stripslashes($row_featured['product_name'])?>">
<?php		// Find out which sized image is to be displayed as featured product image
			switch($row_featured['featured_showimagetype'])
			{
				case 'Thumb':
							$fld_name = 'image_thumbpath';
							break;
				case 'Medium':
							$fld_name = 'image_thumbcategorypath';
							break;
				case 'Big':
							$fld_name = 'image_bigpath';
							break;
				case 'Extra':
							$fld_name = 'image_extralargepath';
							break;
			};
			// Calling the function to get the image to be shown
			$img_arr = get_imagelist('prod',$row_featured['product_id'],$fld_name,0,0,1);
			if(count($img_arr))
			{
				show_image(url_root_image($img_arr[0][$fld_name],1),$row_featured['product_name'],$row_featured['product_name']);
			}
			else
			{
				// calling the function to get the no image
				$no_img = get_noimage('prod'); 
				if ($no_img)
				{
					show_image($no_img,$row_featured['product_name'],$row_featured['product_name']);
				}
			}
?>			</a>
			</td>
<?php	}
?>			<td colspan="1" class="featuredproddet" style="text-align:left;vertical-align:top;width:56%">
<?php	
		if ($row_featured['featured_showtitle']==1)
		{
?> 			<div class="featuredprodname"><a href="<?php url_product($row_featured['product_id'],$row_featured['product_name'],-1)?>" title="<?php echo stripslashes($row_featured['product_name'])?>" class="fet_name_link"><?php echo stripslashes($row_featured['product_name'])?></a></div>
<?php	}
		// Check whether selected to show either desc or the price 	
		if ($row_featured['featured_showshortdescription']==1)
		{
			$desc = ($row_featured['featured_desc'])?$row_featured['featured_desc']:$row_featured['product_shortdesc'];
			if ($desc)
			{
?>			<div class="featuredproddes">
					<?php echo stripslashes($desc)?>
			</div>
<?php		}
		}
		$price_arr = array();
		//$price_arr =  show_Price($row_featured,array(),'featured',false,3);
		if($row_featured['featured_showprice']==1)// Check whether price is to be displayed
		{
?>			<div class="featuredprice">
<?php		
			$price_class_arr['ul_class'] 		= 'shelfBul';
			$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
			$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
			$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
			$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
			//	echo show_Price($row_prod,$price_class_arr,'shelfcenter_1'); price_categorydetails_1_reqbreak
			echo show_Price($row_featured,$price_class_arr,'featured');	


			/*if($price_arr['discounted_price'])
				echo $price_arr['discounted_price'];
			else
				echo $price_arr['base_price'];*/
			show_excluding_vat_msg($row_featured,'vat_div');// show excluding VAT msg
?>			</div>
<?php	}

$price_bal2 = show_Price($row_featured,$price_class_arr,'other_3',false,5);
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
								$("#finpermonth_<?php echo $row_featured['product_id']?>").html("&pound;"+(my_fd_obj.m_inst).toFixed(2));
								
							</script> 
<?php
		$frm_name = 'frm_featured';
		$class_arr['ADD_TO_CART']       = '';
		$class_arr['PREORDER']          = '';
		$class_arr['ENQUIRE']           = '';
		$class_arr['QTY_DIV']           = 'normal_shlfA_pdt_input';
		$class_arr['QTY']               = ' ';
		$class_td['QTY']				= 'prod_list_buy_feata';
		$class_td['TXT']				= 'prod_list_buy_b';
		$class_td['BTN']				= 'prod_list_buy_c';
		echo show_addtocart_v5($row_featured,$class_arr,$frm_name,false,'','',true,$class_td);
?>			</td>
		</tr>
		</tbody>
		</table>
	</form>
<?php
	}
};	
?>
