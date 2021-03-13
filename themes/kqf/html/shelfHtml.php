<?php
	/*############################################################################
	# Script Name 	: shelfHtml.php
	# Description 	: Page which holds the display logic for middle shelves
	# Coded by 		: Sny
	# Created on	: 29-Dec-2007
	# Modified by	: Sny
	# Modified On	: 01-Feb-2008
	##########################################################################*/
	class shelf_Html
	{
		// Defining function to show the shelf details
		function Show_Shelf($cur_title,$shelf_arr)
{
global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents;
$ajax_return_function = 'ajax_return_productdetailscontents';
$Captions_arr['PROD_DETAILS'] 	= getCaptions('PROD_DETAILS');
include_once "ajax/ajax.php";
if (count($shelf_arr))
{
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
//Iterating through the shelf array to fetch the product to be shown.
foreach ($shelf_arr as $k=>$shelfData)
{
// Check whether shelf_activateperiodchange is set to 1
$active 	= $shelfData['shelf_activateperiodchange'];
if($active==1)
{
$proceed	= validate_component_dates($shelfData['shelf_displaystartdate'],$shelfData['shelf_displayenddate']);
}
else
$proceed	= true;	

/* Sony Jul 01, 2013 */
global $discthm_group_prod_array,$discthm_group_shelf_array;
$more_conditions = '';
if(count($discthm_group_prod_array))
{
	$more_conditions = " AND a.product_id IN ( ".implode(',',$discthm_group_prod_array).") ";
}

if(count($discthm_group_shelf_array))
{
	if(!in_array($shelfData['shelf_id'],$discthm_group_shelf_array))
	{
		$proceed	= false;	
	}

}
/* Sony Jul 01, 2013 */


if ($proceed)
{
// Get the total number of product in current shelf
$sql_totprod = "SELECT count(b.products_product_id) 
FROM 
products a,product_shelf_product b 
WHERE 
b.product_shelf_shelf_id = ".$shelfData['shelf_id']." 
AND a.product_id = b.products_product_id 
AND a.product_hide = 'N' 
$more_conditions ";
$ret_totprod 	= $db->query($sql_totprod);
list($tot_cnt) 	= $db->fetch_array($ret_totprod); 

// Call the function which prepares variables to implement paging
$ret_arr 		= array();
$pg_variable	= 'shelf_'.$shelfData['shelf_id'].'_pg';
if ($_REQUEST['req']!='')// LIMIT for products is applied only if not displayed in home page
{
$start_var 		= prepare_paging($_REQUEST[$pg_variable],$prodperpage,$tot_cnt);
$Limit			= " LIMIT ".$start_var['startrec'].", ".$prodperpage;
}	
else
{
if($shelfData['shelf_currentstyle']=='nor')
{
$Limit = " LIMIT 0,3";
}
else if($shelfData['shelf_currentstyle']=='new')
{
$Limit = " LIMIT 0,3";
}
}

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
$more_conditions 
ORDER BY 
$shelfsort_by $shelfsort_order 
$Limit	";
$ret_prod = $db->query($sql_prod);
if ($db->num_rows($ret_prod))
{
$comp_active = isProductCompareEnabled();
// Number of result to display on the page, will be in the LIMIT of the sql query also
$querystring = ""; // if any additional query string required specify it over here
if($_REQUEST['req']!='') // If coming to show the details in middle area other than from the home page then show the details in normal shelf style
$shelfData['shelf_currentstyle']='nor';
if($shelfData['shelf_currentstyle']=='nor') // case of normal design layout or bestseller like display
{
switch($shelfData['shelf_displaytype'])
{
case '3row': // case of one in a row for normal
?>				<table border="0" cellpadding="0" cellspacing="0" class="shelfBtable">
<?php 
$pass_type = get_default_imagetype('midshelf');
				
$desc = trim($shelfData['shelf_description']);
if($desc!='' and $desc!='&nbsp;')
{
?>				<tr><td colspan="3" class="shelfBproddes" align="left"><?php echo $desc?></td></tr>
<?php				}
if ($tot_cnt>0 and ($_REQUEST['req']!=''))
{
?>				<tr>
<td colspan="3" class="pagingcontainertd" align="center">
<?php 					$path = '';
$query_string .= "";
paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr); 	
?>					</td>
</tr>
<?php				
}
?>				
<tr>
<td colspan="3">
<?php
if($cur_title)
{
?>				
<span class="shelfheadtop"><?php echo $cur_title?></span>

<?php
}
?>
<div class="centerwrap">
<div class="productlist">
<?php
// Calling the function to get the type of image to shown for current 
$cur_row = 1 ;
$max_col = 3;
while($row_prod = $db->fetch_array($ret_prod))
{
?>		<div class="productlist_item">
<div class="product_container">
<?php
/*$prodcur_arr[] = $row_prod;
if($cur_row==0)
{
echo "<tr>";
}
if($cur_row!=0 && $cur_row%2==0)
{
$cls = "prod_list_td";
}
else
{
$cls = "prod_list_td_r";
}*/
?>
<?php
if ($shelfData['shelf_showimage']==1)// Check whether description is to be displayed
{
if($row_prod['product_newicon_show']==1)
{
?>		<div class="new"><img src="<?php url_site_image('icon_new.png')?>" width="39" height="19" alt="icon new" /></div>
<?php	}
if($row_prod['product_saleicon_show']==1)
{
?>		<div class="new"><img src="<?php url_site_image('icon_sale.png')?>" width="50" height="51" alt="icon sale" /></div>
<?php	}
?>
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
?>		</a>
<?php 
}
?></div>
<?php
if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
{
?>		<div class="product_name"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></div>
<?php
}
if($row_prod['product_bulkdiscount_allowed']=='Y')
{
?>		<div class="prod_list_bulk"><img src="<?php url_site_image('bulk.png')?>"  alt="bulk discount"/></div>
<?php 
}
if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
{
?>
<?php
$price_class_arr['ul_class'] 		= 'price';
$price_class_arr['normal_class'] 	= 'productprice';
$price_class_arr['strike_class'] 	= 'retailprice';
$price_class_arr['yousave_class'] 	= 'yousaveprice';
$price_class_arr['discount_class'] 	= 'discountprice';
echo show_Price($row_prod,$price_class_arr,'cat_detail_1');
//show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
?>
<?php
}	
if($shelfData['shelf_showbonuspoints']==1)
{
if($row_prod['product_bonuspoints'] > 0)
{
/*echo '<div class="prod_list_bonusB">
<span class="bonus_point_number_a">
<span>'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_BONUSPOINTSA']).'</span>
</span>
<span class="bonus_point_caption_b">'.$row_prod['product_bonuspoints'].'</span>
<span class="bonus_point_number_c">
<span>'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_BONUSPOINTSB']).'</span></span>
</div>';*/
}
}
?>
<?php 
if ($shelfData['shelf_showdescription']==1)// Check whether description is to be displayed
{
?>
<!--<div class="prod_list_des"><?php echo stripslashes($row_prod['product_shortdesc'])?><?php show_moreinfo($row_prod,'list_more')?></div>-->
<?php
}
if($row_prod['product_saleicon_show']==1)
{
$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
if($desc!='')
{
?>	
<!--<div class="prod_list_new"><?php echo $desc?></div>-->
<?php
}
}
if($row_prod['product_newicon_show']==1)
{
$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
if($desc!='')
{
?>	<!--<div class="prod_list_new"><?php echo $desc?></div>-->
<?php
}
}
?>
<div class="moreinfo">
<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">More Info</a>
</div>
<div class="addtocartWrap">
<div class="prod_list_buy">
<?php 
$frm_name = uniqid('catdet_');
?>
<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="fproduct_id" value="" />
<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
<?php
$class_arr['ADD_TO_CART']       = '';
$class_arr['PREORDER']          = '';
$class_arr['ENQUIRE']           = '';
$class_arr['QTY_DIV']           = 'normal_shlfA_pdt_input';
$class_arr['QTY']               = ' ';
$class_td['QTY']				= 'prod_list_buy_a';
$class_td['TXT']				= 'prod_list_buy_b';
$class_td['BTN']				= 'prod_list_buy_c';
echo show_addtocart_v5($row_prod,$class_arr,$frm_name,false,'','',true,$class_td);
?>
</form>
</div>
</div>

<?php
if($cur_row>=$max_col)
{
$cur_row = 0;
}
$cur_row ++;	

if($cur_row<$max_col)
{
}
?>	
</div>
<?php
}
?>
</div>
</div>
</td>
</tr>
<?php
if($_REQUEST['req']=='' and  $tot_cnt>0) // case of show all link
{
?>
<tr>
<td colspan="3" align="right"><?php //echo "shelf id - ".$shelfData['shelf_id']." - ".$shelfData['shelf_name'];?><a href='<?php url_shelf_all($shelfData['shelf_id'],$shelfData['shelf_name'],-1);?>' title='<?php $Captions_arr['COMMON']['SHOW_ALL'];?>' class="middle_showall_link"><?php echo $Captions_arr['COMMON']['SHOW_ALL'];?></a>
</td>
</tr>
<?php
}
?>
</table>
<?php		break;									
};
}
else if($shelfData['shelf_currentstyle']=='new') // case of normal design layout or bestseller like display
{ 
switch($shelfData['shelf_displaytype'])
{
case '3row': 
//$pass_type = get_default_imagetype('midshelf');
$pass_type ='image_bigcategorypath';
?>				<table border="0" cellpadding="0" cellspacing="0" class="shelfBtable">
<?php 
$pass_type = get_default_imagetype('midshelf');
				
$desc = trim($shelfData['shelf_description']);
if ($tot_cnt>0 and ($_REQUEST['req']!=''))
{
?>				<tr>
<td colspan="3" class="pagingcontainertd" align="center">
<?php 					$path = '';
$query_string .= "";
paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr); 	
?>					</td>
</tr>
<?php				
}
?>				
<tr>
<td colspan="3">
<?php

if($desc!='' and $desc!='&nbsp;')
{
?>				<div class="midshelfBproddes" align="left"><?php echo $desc?></div>
<?php				}
if($cur_title)
{
?>				
<span class="shelfheadtop"><?php echo $cur_title?></span>

<?php
}
?>
<div class="centerwrap">
<div class="productlist">
<?php
// Calling the function to get the type of image to shown for current 
$cur_row = 1 ;
$max_col = 3;
while($row_prod = $db->fetch_array($ret_prod))
{
?>		<div class="productlist_item">
<div class="product_container">
<?php
/*$prodcur_arr[] = $row_prod;
if($cur_row==0)
{
echo "<tr>";
}
if($cur_row!=0 && $cur_row%2==0)
{
$cls = "prod_list_td";
}
else
{
$cls = "prod_list_td_r";
}*/
?>
<?php
if ($shelfData['shelf_showimage']==1)// Check whether description is to be displayed
{
if($row_prod['product_newicon_show']==1)
{
?>		<div class="new"><img src="<?php url_site_image('icon_new.png')?>" width="39" height="19" alt="icon new" /></div>
<?php	}
if($row_prod['product_saleicon_show']==1)
{
?>		<div class="new"><img src="<?php url_site_image('icon_sale.png')?>" width="50" height="51" alt="icon sale" /></div>
<?php	}
?>
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
?>		</a>
<?php 
}
?></div>
<?php
if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
{
?>		<div class="product_name"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></div>
<?php
}
if($row_prod['product_bulkdiscount_allowed']=='Y')
{
?>		<div class="prod_list_bulk"><img src="<?php url_site_image('bulk.png')?>" alt="bulk discount"/></div>
<?php 
}
if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
{
?>
<?php
$price_class_arr['ul_class'] 		= 'price';
$price_class_arr['normal_class'] 	= 'productprice';
$price_class_arr['strike_class'] 	= 'retailprice';
$price_class_arr['yousave_class'] 	= 'yousaveprice';
$price_class_arr['discount_class'] 	= 'discountprice';
echo show_Price($row_prod,$price_class_arr,'cat_detail_1');
//show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
?>
<?php
}	
if($shelfData['shelf_showbonuspoints']==1)
{
if($row_prod['product_bonuspoints'] > 0)
{
/*echo '<div class="prod_list_bonusB">
<span class="bonus_point_number_a">
<span>'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_BONUSPOINTSA']).'</span>
</span>
<span class="bonus_point_caption_b">'.$row_prod['product_bonuspoints'].'</span>
<span class="bonus_point_number_c">
<span>'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_BONUSPOINTSB']).'</span></span>
</div>';*/
}
}
?>
<?php 
if ($shelfData['shelf_showdescription']==1)// Check whether description is to be displayed
{
?>
<!--<div class="prod_list_des"><?php echo stripslashes($row_prod['product_shortdesc'])?><?php show_moreinfo($row_prod,'list_more')?></div>-->
<?php
}
if($row_prod['product_saleicon_show']==1)
{
$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
if($desc!='')
{
?>	
<!--<div class="prod_list_new"><?php echo $desc?></div>-->
<?php
}
}
if($row_prod['product_newicon_show']==1)
{
$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
if($desc!='')
{
?>	<!--<div class="prod_list_new"><?php echo $desc?></div>-->
<?php
}
}
?>
<div class="moreinfo">
<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">More Info</a>
</div>
<div class="addtocartWrap">
<div class="prod_list_buy">
<?php 
$frm_name = uniqid('catdet_');
?>
<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="fproduct_id" value="" />
<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
<?php
$class_arr['ADD_TO_CART']       = '';
$class_arr['PREORDER']          = '';
$class_arr['ENQUIRE']           = '';
$class_arr['QTY_DIV']           = 'normal_shlfA_pdt_input';
$class_arr['QTY']               = ' ';
$class_td['QTY']				= 'prod_list_buy_a';
$class_td['TXT']				= 'prod_list_buy_b';
$class_td['BTN']				= 'prod_list_buy_c';
echo show_addtocart_v5($row_prod,$class_arr,$frm_name,false,'','',true,$class_td);
?>
</form>
</div>
</div>

<?php
if($cur_row>=$max_col)
{
$cur_row = 0;
}
$cur_row ++;	

if($cur_row<$max_col)
{
}
?>	
</div>
<?php
}
?>
</div>
</div>
</td>
</tr>
<?php
if($_REQUEST['req']=='' and  $tot_cnt>0) // case of show all link
{
?>
<tr>
<td colspan="3" align="right"><?php //echo "shelf id - ".$shelfData['shelf_id']." - ".$shelfData['shelf_name'];?><a href='<?php url_shelf_all($shelfData['shelf_id'],$shelfData['shelf_name'],-1);?>' title='<?php $Captions_arr['COMMON']['SHOW_ALL'];?>' class="middle_showall_link"><?php echo $Captions_arr['COMMON']['SHOW_ALL'];?></a>
</td>
</tr>
<?php
}
?>
</table>
<?php		break;									
};
}							
elseif($shelfData['shelf_currentstyle']=='scroll') // case of christmas layout
{

$pass_type ='image_thumbpath';
$width_one_set 	= 176;
$min_number_req	= 4;
$min_width_req 	= $width_one_set * $min_number_req;
$total_cnt		= $db->num_rows($ret_prod);
$calc_width		= $total_cnt * $width_one_set;
if($calc_width < $min_width_req)
$div_width = $min_width_req;
else
$div_width = $calc_width; 
?><div class="scrollboxwrap">
<?php
if($cur_title)
{
?>				
<h2><?php echo $cur_title?></h2>

<?php				}
?>
<div class="link_pdt_outr_bottom">
<div class="link_pdt_top_bottom"></div>
<div class="link_pdt_conts_bottom">
<div class="det_link_pdt_con_bottom">
<div class="det_link_nav"><a href="#null" onmouseover="scrollDivRight('containerC')" onmouseout="stopMe()"><img src="<?php url_site_image('arrow_left.png')?>" alt="arrow left"></a></div>
<div id="containerC" class="det_link_pdt_inner_bottom">
<div id="scroller" style="width:<?php echo $div_width?>px">
<?php
$cnts = $db->num_rows($ret_prod);
while($row_prod = $db->fetch_array($ret_prod))
{ 
?>
<div class="scrollimg"><ul>
<li>
<?php
if($shelfData['shelf_showimage']==1) // whether image is to be displayed
{   
?>
<div class="shelf_thump_wrap">
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
<?php 
}
?>
<div class="thump_details">
<div class="productname"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslash_normal($row_prod['product_name'])?>"><?php echo stripslash_normal($row_prod['product_name'])?></a></div>
<?php
if($shelfData['shelf_showprice']==1) // whether price is to be displayed
{

?>				<div class="productprice">
<?php			
$price_arr =  show_Price($row_prod,array(),'compshelf',false,4);
//print_r($price_arr);
if($price_arr['discounted_price'])
{
echo $price_arr['discounted_price'];
//echo '</br>'. $price_arr['yousave_price'];
}
else
echo $price_arr['base_price'];
?>				
</div>
<?php		
}
?>

<div class="cartLink"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">Add to cart</a>
</div>
</div>

</li>
</ul></div>
<?php
}
?>
</div>
</div>
<div class="det_link_nav"> <a href="#null" onmouseover="scrollDivLeft('containerC','<?php echo $div_width?>')" onmouseout="stopMe()"><img src="<?php url_site_image('arrow_right.png')?>" alt="arrow right"/></a></div>
</div>
</div>
<div class="link_pdt_bottom"></div>
</div>
</div>
<?php
}
/* 
elseif($shelfData['shelf_currentstyle']=='new') // case of new year layout
{
switch($shelfData['shelf_displaytype'])
{
case '1row': // case of one in a row for new year
case 'dropdown':
case 'list':
?>
<table border="0" cellpadding="0" cellspacing="0" class="newyear_tableB" >
<?php
if($cur_title)
{
?>
<tr>
<td colspan="3" class="newyear_topbgB" align="left"><?php echo stripslashes($cur_title)?></td>
</tr>
<?php
}
$desc = trim($shelfData['shelf_description']);
if($desc!='' and $desc!='&nbsp;')
{
?>
<tr>
<td colspan="3" class="newyear_proddes" align="left"><?php echo $desc?></td>
</tr>
<?php		
}
if ($tot_cnt>0 and ($_REQUEST['req']!=''))
{
?>
<tr>
<td colspan="3" align="center" class="pagingcontainertd">
<?php 
$path = '';
//show_paging($path,$_REQUEST[$pg_variable],$start_arr['counterstart'],$tot_cnt,$prodperpage,$pg_variable,'')
$query_string .= "";
paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr); 	
?>	
</td>
</tr>
<?php
} 
while($row_prod = $db->fetch_array($ret_prod))
{
$frm_name = uniqid('shelf_');
?>
<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="fproduct_id" id="fproduct_id" value="" />
<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
<tr onmouseover="this.className='newyearshelf_1row_hover'" onmouseout="this.className='newyear_1rowtd'">
<td align="left" valign="middle" class="newyear_1rowtd">

<?php
if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
{
?>
<h1 class="newyear_prodname" ><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" class="newyear_prodnamelink" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h1>

<?php
}
if($shelfData['shelf_showdescription']==1)// whether desc is to be displayed
{
?>	
<h6 class="newyear_proddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6>
<?php
}
?>

</td>
<td align="center" valign="middle" class="newyear_1rowtd">
<?php
if($shelfData['shelf_showimage']==1)// whether image is to be displayed
{
?>		
<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
<?php
// Calling the function to get the type of image to shown for current 
//	$pass_type = get_default_imagetype('midshelf');
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
<?php
}
?>
<?php if($comp_active)  {
dislplayCompareButton($row_prod['product_id']);
}?>
</td>
<td align="left" valign="middle" class="newyear_1rowtd">

<?php
if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
{
$price_class_arr['ul_class'] 		= 'newyear_priceulB';
$price_class_arr['normal_class'] 	= 'newyear_pricenormalB';
$price_class_arr['strike_class'] 	= 'newyear_pricestrikeB';
$price_class_arr['yousave_class'] 	= 'newyear_priceyousaveB';
$price_class_arr['discount_class'] 	= 'newyear_pricediscountB';
echo show_Price($row_prod,$price_class_arr,'shelfcenter_1');
show_excluding_vat_msg($row_prod,'vat_div_pad');// show excluding VAT msg
//show_bonus_points_msg($row_prod,'bonus_point_pad'); // Show bonus points
$pass_arr['main_cls'] 		= 'bonus_point';
$pass_arr['caption_cls'] 	= 'bonus_point_caption';
$pass_arr['point_cls'] 		= 'bonus_point_number';
show_bonus_points_msg_multicolor($row_prod,$pass_arr);

}
?>
<ul class="newyear_infodivB">
<li class="newyear_infodivleftB"><?php show_moreinfo($row_prod,'newyear_infolinkB1row')?></li>
<li class="newyear_infodivrightB">
<?php
$class_arr 					= array();
$class_arr['ADD_TO_CART']	= 'newyear_quantiryinkB1row';
$class_arr['PREORDER']		= 'newyear_quantiryinkB1row';
$class_arr['ENQUIRE']		= 'newyear_quantiryinkB1row';
show_addtocart($row_prod,$class_arr,$frm_name)
?>
</li>
</ul>
</td>
</tr>
</form>
<?php
}
?>	
</table>
<?php	
break;
case '3row': // case of three in a row for new year
?>
<table border="0" cellpadding="0" cellspacing="0" class="newyear_tableB">
<?php
if($cur_title)
{
?>	
<tr>
<td colspan="3" class="newyear_topbgB" align="left"><?php echo $cur_title?></td>
</tr>
<?php
}
$desc = trim($shelfData['shelf_description']);
if($desc!='' and $desc!='&nbsp;')
{
?>
<tr>
<td colspan="3" class="newyear_proddes" align="left"><?php echo $desc?></td>
</tr>
<?php		
}
if ($tot_cnt>0 and ($_REQUEST['req']!=''))
{
?>
<tr>
<td colspan="3" class="pagingcontainertd" align="center">
<?php 
$path = '';
//show_paging($path,$_REQUEST[$pg_variable],$start_arr['counterstart'],$tot_cnt,$prodperpage,$pg_variable,'')
$query_string .= "";
paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr); 	
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
?>
<td class="newyeartoptd" valign="top"  onmouseover="this.className='newyearshelf_3row_hover'" onmouseout="this.className='newyeartoptd'">
<ul class="newyear_ulB">
<?php
if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
{
?>
<li class="newyear_nameB"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></li>

<?php
}
if($shelfData['shelf_showimage']==1)// whether image is to be displayed
{
?>		
<li class="shelfimg"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
<?php
// Calling the function to get the type of image to shown for current 
//$pass_type = get_default_imagetype('midshelf');
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

</li>

<?php
}
if($shelfData['shelf_showdescription']==1)// whether desc is to be displayed
{
?>	
<li><?php echo stripslashes($row_prod['product_shortdesc'])?></li>
<?php
}
?>	
</ul>
<?php //show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points
$pass_arr['main_cls'] 		= 'bonus_point';
$pass_arr['caption_cls'] 	= 'bonus_point_caption';
$pass_arr['point_cls'] 		= 'bonus_point_number';
show_bonus_points_msg_multicolor($row_prod,$pass_arr);
?>												
</td>
<?php

$cur_col++;
if ($cur_col>=$max_col)
{

echo "</tr>";
$cur_tempcol = $cur_col = 0;
//##############################################################
// Showing the more info and add to cart links after each row in 
// case of breaking to new row while looping
//##############################################################


echo "<tr>";

foreach($prodcur_arr as $k=>$prod_arr)
{
$frm_name = uniqid('shelf_');
?>
<td class="newyearbottomtd" valign="bottom">
<?php if($comp_active)  { // to display Compare icon
dislplayCompareButton($prod_arr['product_id']);
}?>
<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $prod_arr['product_id']?>)">
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="fproduct_id" value="" />
<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
<input type="hidden" name="fproduct_url" value="<?php url_product($prod_arr['product_id'],$prod_arr['product_name'])?>" />
<?php
if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
{
$price_class_arr['ul_class'] 		= 'newyear_priceulB';
$price_class_arr['normal_class'] 	= 'newyear_pricenormalB';
$price_class_arr['strike_class'] 	= 'newyear_pricestrikeB';
$price_class_arr['yousave_class'] 	= 'newyear_priceyousaveB';
$price_class_arr['discount_class'] 	= 'newyear_pricediscountB';
echo show_Price($prod_arr,$price_class_arr,'shelfcenter_3');
show_excluding_vat_msg($prod_arr,'vat_div_pad');// show excluding VAT msg
}
?>	
<ul class="newyear_infodivB">
<li class="newyear_infodivleftB"><?php show_moreinfo($prod_arr,'newyear_infolinkB')?></li>
<li class="newyear_infodivrightB">
<?php
$class_arr 							= array();
$class_arr['ADD_TO_CART']	= 'newyear_quantitylinkBrow';
$class_arr['PREORDER']			= 'newyear_quantitylinkBrow';
$class_arr['ENQUIRE']			= 'newyear_quantitylinkBrow';
show_addtocart($prod_arr,$class_arr,$frm_name)
?>
</li>
</ul>
</form>
</td>
<?php
++$cur_tempcol;
// done to handle the case of breaking to new linel
if ($cur_tempcol>=$max_col)
{
echo "</tr>";
$cur_tempcol=0;
}
}
echo "<tr>";
$prodcur_arr = array();	
}
}
// If in case total product is less than the max allowed per row then handle that situation
if ($cur_col<$max_col)
{
echo "<td colspan='".($max_col-$cur_col)."'>&nbsp;</td></tr><tr>";
$cur_tempcol = $cur_col = 0;
//##############################################################
// Done to handle the case of showing the qty, add to cart and more info links
// in case if total product is less than the max allower per row.
//##############################################################
foreach($prodcur_arr as $k=>$prod_arr)
{
$frm_name = uniqid('shelf_');
?>
<td class="newyearbottomtd" valign="bottom">
<?php if($comp_active)  { // to display Compare icon
dislplayCompareButton($prod_arr['product_id']);
}?>
<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $prod_arr['product_id']?>)">
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="fproduct_id" value="" />
<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
<input type="hidden" name="fproduct_url" value="<?php url_product($prod_arr['product_id'],$prod_arr['product_name'])?>" />
<?php
if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
{
$price_class_arr['ul_class'] 		= 'newyear_priceulB';
$price_class_arr['normal_class'] 	= 'newyear_pricenormalB';
$price_class_arr['strike_class'] 	= 'newyear_pricestrikeB';
$price_class_arr['yousave_class'] 	= 'newyear_priceyousaveB';
$price_class_arr['discount_class'] 	= 'newyear_pricediscountB';
echo show_Price($prod_arr,$price_class_arr,'shelfcenter_3');
show_excluding_vat_msg($prod_arr,'vat_div_pad');// show excluding VAT msg
}
?>	
<ul class="newyear_infodivB">
<li class="newyear_infodivleftB"><?php show_moreinfo($prod_arr,'newyear_infolinkB')?></li>
<li class="newyear_infodivrightB">
<?php
$class_arr 					= array();
$class_arr['ADD_TO_CART']	= 'newyear_infolinkB';
$class_arr['PREORDER']		= 'newyear_infolinkB';
$class_arr['ENQUIRE']		= 'newyear_infolinkB';
show_addtocart($prod_arr,$class_arr,$frm_name)
?>
</li>
</ul>
</form>
</td>
<?php
++$cur_tempcol;
if ($cur_tempcol>=$max_col)
{
echo "</tr><tr>";
$cur_tempcol=0;
}
}
echo "<td colspan='".($max_col-$cur_tempcol)."'>&nbsp;</td></tr>";
}
else
echo "</tr>";
$prodcur_arr = array();
?>
</table>

<?php
break;
};
}
*/ 
}
}
else
{
	//removefrom_Display_Settings($shelfData['shelf_id'],'mod_shelf');
}
}
}	
}
};	
?>
