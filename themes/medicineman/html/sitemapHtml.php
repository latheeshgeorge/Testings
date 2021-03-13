<?php
/*############################################################################
	# Script Name 	: sitemapHtml.php
	# Description 	: Page which holds the display logic for listing links in sitemap
	# Coded by 		: ANU
	# Created on	: 08-Apr-2008
	# Modified by	: 
	# Modified On	: 
	##########################################################################*/
	class sitemap_Html
	{
		// Defining function to show the Site map
		function Show_Sitemap($category_datas)
		{
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname;
			//print_r($category_datas[0]);
?>
	<div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?=$Captions_arr['SITEMAP']['SITEMAP_TREEMENU_TITLE']?></div>
		<?
 //////////////***************LISTING OF STATIC PAGES ,CATEGORIES AND PRODUCTS FOR SITE MAP*************///////////////////
		if(is_array($_SESSION['site_map_links'])){
		$static_heading =  false;
		$categ_pdt_heading = false;
		$display_last_category =  false;
		$saved_heading = false;
		?>
		<table width="100%" border="0" cellpadding="0" cellspacing="4"  class="regitable">
		<?php
		$max_elements	=	75;
		$row_count = 0;
		$static_cnt=0; 
		$element_cnt = 0;
		$saved_cnt = 0;
		if(!$_REQUEST['start_key']){
		$_REQUEST['start_key'] = 0;
		}
		$current_start	= $_REQUEST['start_key'];
		$prev_start	= $current_start-$max_elements;	
		foreach($_SESSION['site_map_links'] as $link_key => $linkval){
		if($link_key > $_REQUEST['start_key']){
		$element_cnt++;
		$linkval_key = array_keys($linkval);
		if(substr($linkval_key[0],1,16) == 'site_map_static_'){
		 if($Captions_arr['SITEMAP']['SITEMAP_STATIC_PAGE_HEADING'] && !$static_heading) { ?>
		<tr>
			<td colspan="2" class="favoritesheader"><?=$Captions_arr['SITEMAP']['SITEMAP_STATIC_PAGE_HEADING']?></td>
		</tr>
		<? 
		$static_heading=true;
		}
		$pg_id = substr($linkval_key[0],17,-1);
		if($static_cnt%2==0){
		$row_count++;
		echo '<tr>';
		}
		?>
		
		<td align="left" valign="middle" class="sitemapcontents" width="50%">
		<a href="<?php url_static_page($pg_id,$linkval["'site_map_static_"."$pg_id'"])?>" class="sitemapcatlink" title="<?php echo stripslashes($linkval["'site_map_static_"."$pg_id'"])?>"><?=$linkval["'site_map_static_"."$pg_id'"]?>
		</a>
		</td>
		<?
		$static_cnt++;
		if($static_cnt%2==0) {
		?>
		</tr>
		<? }
		}
		elseif(substr($linkval_key[0],1,15) == 'site_map_categ_'){
		if($Captions_arr['SITEMAP']['SITEMAP_CATEGS_AND_PDTS_HEADING'] && !$categ_pdt_heading) { ?>
		<tr>
			<td colspan="2" class="favoritesheader"><?=$Captions_arr['SITEMAP']['SITEMAP_CATEGS_AND_PDTS_HEADING']?></td>
		</tr>
		<? 
		$categ_pdt_heading=true;
		}
		$display_last_category =  true;
		$categ_id = substr($linkval_key[0],16,-1);
		if($i%2!=0){
		echo '<td>&nbsp;</td>';
		}
		$row_count++;
		?>
		<tr>
		<td align="left" valign="middle" colspan="2" class="sitemapcontents"><a href="<?php url_category($categ_id,$linkval["'site_map_categ_"."$categ_id'"],-1)?>" class="sitemapcatlink"><strong><?=$linkval["'site_map_categ_"."$categ_id'"]?></strong></a></td>
		</tr>
		<?
		$i=0;
		}
		elseif(substr($linkval_key[0],1,14) == 'site_map_prod_'){
		if($Captions_arr['SITEMAP']['SITEMAP_CATEGS_AND_PDTS_HEADING'] && !$categ_pdt_heading) { ?>
		<tr>
			<td colspan="2" class="favoritesheader"><?=$Captions_arr['SITEMAP']['SITEMAP_CATEGS_AND_PDTS_HEADING']?></td>
		</tr>
		<? 
		$categ_pdt_heading=true;
		}
		$pdt_id = substr($linkval_key[0],15,-1);
		if(!$display_last_category){ // Display the category name if the listing starts from any products
		$sql_last_categid = "SELECT product_default_category_id 
							FROM products 
							WHERE product_id = ".$pdt_id."
							 AND product_hide ='N' 
							 AND sites_site_id = $ecom_siteid LIMIT 1";
		$ret_last_categid	=	$db->query($sql_last_categid);
		list($last_categid) = $db->fetch_array($ret_last_categid);
		$sql_last_catname = "SELECT category_id,category_name 
							FROM product_categories
							WHERE category_id = ".$last_categid."
							AND category_hide = 0 
							 AND sites_site_id = ".$ecom_siteid;	
		$ret_last_catname	=	$db->query($sql_last_catname);
		list($last_categid,$last_categ_name) = $db->fetch_array($ret_last_catname);
		?>
		<tr>
		<td align="left" valign="middle" colspan="2" class="sitemapcontents"><a href="<?php url_category($last_categid,$last_categ_name,-1)?>" class="sitemapcatlink"><strong><?=$last_categ_name?></strong></a></td>
		</tr>
		<?
		$display_last_category = true;
		}
		if($i%2==0){
		$row_count++;
		echo '<tr>';
		}
		?>
		
		<td align="left" valign="middle" class="sitemapcontents" width="50%">
		<a href="<?php url_product($pdt_id,$sitemap_products['product_name'],-1)?>" class="sitemapprodlink" title="<?php echo stripslashes($linkval["'site_map_prod_"."$pdt_id'"])?>">&raquo; <?=$linkval["'site_map_prod_"."$pdt_id'"]?>
		</a>
		</td>
		<?
		$i++;
		if($i%2==0) {
		?>
		</tr>
		<? }
		
		}
		elseif(substr($linkval_key[0],1,15) == 'site_map_saved_')
		{
			if($Captions_arr['SITEMAP']['SITEMAP_SAVED_PAGE_HEADING'] && !$saved_heading)
			{ ?>
				<tr>
				<td colspan="2" class="favoritesheader"><?=stripslash_normal($Captions_arr['SITEMAP']['SITEMAP_SAVED_PAGE_HEADING'])?></td>
				</tr>
			<? 
				$saved_heading=true;
			}
			$sv_id = substr($linkval_key[0],16,-1);
			if($saved_cnt%2==0)
			{
				$row_count++;
				echo '<tr>';
			}
		?>
			<td align="left" valign="middle" class="sitemapcontents" width="50%">
			<a href="<?php url_savedsearch($sv_id,$linkval["'site_map_saved_"."$sv_id'"])?>" class="sitemapcatlink" title="<?php echo stripslash_normal($linkval["'site_map_saved_"."$sv_id'"])?>"><?=$linkval["'site_map_saved_"."$sv_id'"]?>
			</a>
			</td>
		<?
			$saved_cnt++;
			if($saved_cnt%2==0)
			{
		?>
			</tr>
		<? }
		
		}
		if($element_cnt >= $max_elements){
		$next = $link_key+1;
		break;
		}
		
		}
		}
		?>
			</table>

		<table width="100%" border="0" cellpadding="0" cellspacing="4">
			<tr>
		
			<td colspan="2" align="center" class="pagingcontainertd"> 
			<?php
			 if((is_array($_SESSION['site_map_links'][$prev_start]) || !is_array($_SESSION['site_map_links'][$start_key])) && $_REQUEST['start_key']) { ?>
			<a href="http://<?=$ecom_hostname?>/sitemap.html?start_key=<?=$prev_start?>" title="Prev"> &laquo; Prev  </a>
			<? } ?>
			<?php 
			if(is_array($_SESSION['site_map_links'][$next])) { ?>
			<a href="http://<?=$ecom_hostname?>/sitemap.html?start_key=<?=$link_key?>" title="Next"> Next &raquo;</a> 
			<? } ?>
	</td>
	
	</tr>
	</table>
			
	
		<?php	
		}
	}
		
	};	
?>
