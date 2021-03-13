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
		?>
		<tr>
			<td align="center" valign="middle" class="pagingcontainertd">
		<?php
			if($static_row_cnt){
		?></td>
		</tr>
		<?php 
		if($Captions_arr['SITEMAP']['SITEMAP_STATIC_PAGE_HEADING']){ 
		?>
			<tr>
				<td colspan="2" class="favoritesheader"><?=$Captions_arr['SITEMAP']['SITEMAP_STATIC_PAGE_HEADING']?></td>
			</tr>
			<? }?>
			<tr>
			<?php
			$i=0;
			$rowcnt = 1;
			while($sitemap_static_pages = $db->fetch_array($ret_sitemap_static_pages)) {
			$i++;
				if($rowcnt <= $max_rows) {
			?>
				<td align="left" valign="middle" class="sitemapcontents" width="50%"><a href="<?php if ($sitemap_static_pages['page_type']=='Page') url_static_page($sitemap_static_pages['page_id']); else echo $sitemap_static_pages['page_link'];?>" class="favoritecatlink" title="<?php echo stripslashes($sitemap_static_pages['title'])?>"><?=$sitemap_static_pages['pname']?>
				</a></td>
			<? 
					if($i%2== '0'){
						$rowcnt++;
						echo '</tr><tr>';
					}
				}
			} 
			?>
			</tr>
		<? 
		}
		//print_r($_SESSION['site_map_links']);
		foreach($_SESSION['site_map_links'] as $link_key => $linkval)		{
		print_r($link_key);
		echo "----";
		print_r($linkval);
		echo "<br/>----";
	}
	echo "dfsd".$_SESSION['site_map_links'][0];
		?>
		
	</table>
		<?php //////////////***************LISTING OF SITE CATEGORIES AND PRODUCTS FOR SITE MAP*************///////////////////
		if($static_row_cnt < $max_rows) {
		$prod_cat_row_cnt = 0;
		?>
		<table width="100%" border="0" cellpadding="0" cellspacing="4"  class="regitable">
		<?php if($Captions_arr['SITEMAP']['SITEMAP_CATEGS_AND_PDTS_HEADING']) {?>
		<tr>
			<td colspan="2" class="favoritesheader"><?=$Captions_arr['SITEMAP']['SITEMAP_CATEGS_AND_PDTS_HEADING']?></td>
		</tr>
		<? 
		}
		foreach($category_datas as $cat_id => $category_name){
		$rowcnt++;
		$prod_cat_row_cnt++;
		if($rowcnt <= $max_rows) {
		?>
		<tr>
			<td align="left" valign="middle" colspan="2" class=""><?=$rowcnt?><a href="<?php url_category($cat_id,$category_name,-1)?>" class="favoriteprodlink"><strong><?=$category_name?></strong></a></td>
		  </tr>
		  <?php 
		}
		 $sql_sitemap_products = "SELECT product_id,product_name 
		  								FROM products 
		 							 WHERE product_default_category_id = ".$cat_id." AND sites_site_id = ".$ecom_siteid;
		  $ret_sitemap_products = $db->query($sql_sitemap_products);
				if($db->num_rows($ret_sitemap_products)){						 
		  ?>
		<tr>
		
		<?php
		$i=0;
		while($sitemap_products = $db->fetch_array($ret_sitemap_products)) {
			$i++;
			if($rowcnt <= $max_rows) {
		?>
			<td align="left" valign="middle" class="sitemapcontents" width="50%"><?=$rowcnt?><a href="<?php url_product($sitemap_products['product_id'],$sitemap_products['product_name'],-1)?>" class="favoritecatlink" title="<?php echo stripslashes($sitemap_products['product_name'])?>"><?=$sitemap_products['product_name']?>
			</a></td>
		<? 
				if($i%2== '0') {
				$rowcnt++;
					echo '</tr><tr>';
				} 
			}
		}
		?></tr>
		
	<?	}
	}
	
	
	?>

	</table>
	<? 
	}
	
		if($static_row_cnt > $max_rows) {
		$start_static = $static_row_cnt;
	?>
		<table width="100%" border="0" cellpadding="0" cellspacing="4">
			<tr><td colspan="2" align="center" class="pagingcontainertd">
			<a href="http://<?=$ecom_hostname?>/sitemap.html?start_static=<?=$start_static?>" title="Next"><span class="link_text">Next &raquo;</span></a>

	</td></tr>
	</table>
	<?php 
	}
	?>	
		
		
			
		
		<?php	
		}
		function Display_Message($mesgHeader,$Message){
		global $Captions_arr;
		?>
		<table width="100%" border="0" cellspacing="4" cellpadding="0" class="regifontnormal">
      <tr>
        <td width="7%" align="left" valign="middle" class="message_header" > 
       	  <?php
		 	   echo $mesgHeader;
			   ?></td>
      
      </tr>
      <tr>
        <td align="left" valign="middle" class="message"><?php echo $Message; ?></td>
        
      </tr>
	   
           </table>
		<?php	
		}
		
	};	
?>
	<?php 
	



?>
			