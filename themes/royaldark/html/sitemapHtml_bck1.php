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
			$max_rows = 3;
			if($_REQUEST['start_static'])
				$stcnt = $_REQUEST['start_static'];
			else
				$stcnt = 0; 
				
			$limitoffset = $max_rows+1; 
			
			$sql_sitemap_static_pages = "SELECT page_id,pname,page_type,page_link,title 
								FROM 
									static_pages 
								WHERE
									sites_site_id = $ecom_siteid  and hide = 0 LIMIT $stcnt,$limitoffset";
			$ret_sitemap_static_pages = $db->query($sql_sitemap_static_pages);
			$static_row_cnt = $db->num_rows($ret_sitemap_static_pages);
?>
	<div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?=$Captions_arr['SITEMAP']['SITEMAP_TREEMENU_TITLE']?></div>
		<table width="100%" border="0" cellpadding="0" cellspacing="4"  class="regitable">
		<?php if($Captions_arr['SITEMAP']['SITEMAP_HEADING'])
		{ 
		?>
		<tr>
			<td colspan="2" class="favoritesheader"><?=$Captions_arr['SITEMAP']['SITEMAP_HEADING']?></td>
		</tr>
		<? 
		}
		
		
 //////////////***************LISTING OF SITE CATEGORIES AND PRODUCTS FOR SITE MAP*************///////////////////
		
		if(is_array($_SESSION['site_map_links'])){
		?>
		<table width="100%" border="0" cellpadding="0" cellspacing="4"  class="regitable">
			<?php
	 if($Captions_arr['SITEMAP']['SITEMAP_CATEGS_AND_PDTS_HEADING']) {?>
		<tr>
			<td colspan="2" class="favoritesheader"><?=$Captions_arr['SITEMAP']['SITEMAP_CATEGS_AND_PDTS_HEADING']?></td>
		</tr>
		<?php
		$max_rows = 3;
		$row_count = 0;
		$static_cnt=0; 
		if(!$_REQUEST['start_key']){
		$_REQUEST['start_key'] = 0;
		}
		$current_start	= $_REQUEST['start_key'];
		
		foreach($_SESSION['site_map_links'] as $link_key => $linkval){
		if($link_key > $_REQUEST['start_key']){
		$linkval_key = array_keys($linkval);
		if(substr($linkval_key[0],1,16) == 'site_map_static_'){
		$pg_id = substr($linkval_key[0],17,-1);
		if($static_cnt%2==0){
		$row_count++;
		echo '<tr>';
		}
		?>
		
		<td align="left" valign="middle" class="sitemapcontents" width="50%">
		<a href="<?php url_static_page($pg_id)?>" class="favoritecatlink" title="<?php echo stripslashes($linkval["'site_map_static_"."$pg_id'"])?>"><?=$linkval["'site_map_static_"."$pg_id'"]?>
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
		$categ_id = substr($linkval_key[0],16,-1);
		if($i%2!=0){
		echo '<td>&nbsp;</td>';
		}
		$row_count++;
		?>
		<tr>
			<td align="left" valign="middle" colspan="2" class=""><a href="<?php url_category($categ_id,$category_name,-1)?>" class="favoriteprodlink"><strong><?=$linkval["'site_map_categ_"."$categ_id'"]?></strong></a></td>
		  </tr>
		<?
		$i=0;
		}
		elseif(substr($linkval_key[0],1,14) == 'site_map_prod_'){
		$pdt_id = substr($linkval_key[0],15,-1);
		if($i%2==0){
		$row_count++;
		echo '<tr>';
		}
		?>
		
		<td align="left" valign="middle" class="sitemapcontents" width="50%">
		<a href="<?php url_product($pdt_id,$sitemap_products['product_name'],-1)?>" class="favoritecatlink" title="<?php echo stripslashes($linkval["'site_map_prod_"."$pdt_id'"])?>"><?=$linkval["'site_map_prod_"."$pdt_id'"]?>
			</a>
		</td>
		<?
		$i++;
		if($i%2==0) {
		?>
		</tr>
		<? }
		
		}
		if($row_count >= $max_rows){
			$next = $link_key+1;
			$prev	= $link_key-(2*$max_rows);
			if(!$prev){
				$prev_start	= 1;
			}
			elseif($prev<0){
				$prev_start	=0;
			}else{
				$prev_start = $prev;
			}
		//$prev_start = $link_key-1 - (2*$max_rows);
		break;
		}
		
		}
	}
		?>
		
		<table width="100%" border="0" cellpadding="0" cellspacing="4">
			<tr>
		
			<td colspan="2" align="center" class="pagingcontainertd">	
			<?php
			 if((is_array($_SESSION['site_map_links'][$prev_start]) || !is_array($_SESSION['site_map_links'][$start_key])) && $_REQUEST['start_key']) { ?>
			<a href="http://<?=$ecom_hostname?>/sitemap.html?start_key=<?=$_REQUEST['prev_start']?>" title="Prev"><span class="link_text"><< Prev </span></a>
			<? } ?>
			<?php 
			if(is_array($_SESSION['site_map_links'][$next])) { ?>
			<a href="http://<?=$ecom_hostname?>/sitemap.html?start_key=<?=$link_key?>&prev_start=<?=$current_start?>" title="Next"><span class="link_text">Next &raquo;</span></a>
			<? } ?>
	</td>
	
	</tr>
	</table>
		</table>
		<?
		}
	
		?>
		
	</table>
		
	
		<?php	
		}
	}
		
	};	
?>
	<?php 
	



?>
			