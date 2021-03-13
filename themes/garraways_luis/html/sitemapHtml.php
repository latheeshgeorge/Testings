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
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$inlineSiteComponents;
			global $ecom_themename,	$ecom_themeid,$Settings_arr;
			
			if(check_IndividualSslActive())
			{
				$ecom_selfhttp = "https://";
			}
			else
			{
				$ecom_selfhttp = "http://";
			}
			
			//print_r($category_datas[0]);
			$HTML_img = $HTML_alert = $HTML_treemenu='';
				$HTML_treemenu = '<div class="tree_menu_conA">
								  <div class="tree_menu_topA"></div>
								  <div class="tree_menu_midA">
									<div class="tree_menu_content">
									   <ul class="tree_menu">
										<li><a href="'.url_link('',1).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a></li>
										 <li>'.stripslash_normal($Captions_arr['SITEMAP']['SITEMAP_TREEMENU_TITLE']).'</li>
										</ul>
									  </div>
								  </div>
								  <div class="tree_menu_bottomA"></div>
								</div>';
				echo $HTML_treemenu;
?>
		 <div class="inner_contnt" >
        <div class="inner_contnt_top"></div>
		<div class="inner_contnt_middle">
		<?php 
		if(!$_REQUEST['start_key'])
		{
		?>	
		<table class="noResult_class" cellpadding="3" cellspacing="0" align="center" border="0" width="98%">
	<tbody><tr>
	<td class="search_noresult_td">
	I'm Sorry We Couldn't Find Anything That Matches Your Search, However Why Not Look at Our Featured Products Below or Alternatively You Will Find Our Full Sitemap Below.
		Or Alternatively You Can Call Us Freephone On 0800 644 6650 Or Email <a href="mailto:Sales@PureGusto.co.uk">Sales@PureGusto.co.uk</a>
	</td>
	</tr>
	</tbody></table>
			
		
		<?
		
			global $shelf_for_inner;
			$sql_inline = "SELECT display_order,display_component_id,display_id,display_title 
				FROM 
					display_settings a,features b 
				WHERE 
					a.sites_site_id=$ecom_siteid 
					AND a.display_position='middle' 
					AND b.feature_allowedinmiddlesection = 1  
					AND layout_code='home' 
					AND a.features_feature_id=b.feature_id 
					AND b.feature_modulename='mod_shelf' 
				ORDER BY 
						display_order 
						ASC";
			$ret_inline = $db->query($sql_inline); // to dispplay the advert for the category page
			if ($db->num_rows($ret_inline))
			{
				?>
				<div style='width:98%;padding-right:5px;'>
				<?php
				while ($row_inline = $db->fetch_array($ret_inline))
				{
					//$modname 			= $row_inline['feature_modulename'];
					$body_dispcompid	= $row_inline['display_component_id'];
					$body_dispid		= $row_inline['display_id'];
					$body_title			= $row_inline['display_title'];
					$shelf_for_inner	= true;
					$module_name		= 'mod_shelf';
					global $source_area;
					$source_area		= 'sitemap';
					$_REQUEST['page_id'] = 50070;
					include ("includes/base_files/shelf.php");
					$_REQUEST['page_id'] = '';
					$shelf_for_inner	= false;

				}
				?>
				</div>
				<?php
			}
		
	}
 //////////////***************LISTING OF STATIC PAGES ,CATEGORIES AND PRODUCTS FOR SITE MAP*************///////////////////
		if(is_array($_SESSION['site_map_links']))
		{
			$static_heading =  false;
			$categ_pdt_heading = false;
			$display_last_category =  false;
			$saved_heading = false;

			?>
			<table width="100%" border="0" cellpadding="0" cellspacing="4"  class="regitable">
			<?php
				$max_elements	=	50;
				$row_count = 0;
				$static_cnt=0; 
				$element_cnt = 0;
				$saved_cnt = 0;
				if(!$_REQUEST['start_key'])
				{
					$_REQUEST['start_key'] = 0;
				}
				$current_start	= $_REQUEST['start_key'];
				$prev_start	= $current_start-$max_elements;	
				foreach($_SESSION['site_map_links'] as $link_key => $linkval)
				{
					if($link_key > $_REQUEST['start_key'])
					{
						$element_cnt++;
						$linkval_key = array_keys($linkval);
						if(substr($linkval_key[0],1,16) == 'site_map_static_')
						{
							if($Captions_arr['SITEMAP']['SITEMAP_STATIC_PAGE_HEADING'] && !$static_heading)
							{ ?>
								<tr>
								<td colspan="2" class="favoritesheader"><?=stripslash_normal($Captions_arr['SITEMAP']['SITEMAP_STATIC_PAGE_HEADING'])?></td>
								</tr>
							<? 
								$static_heading=true;
							}
							$pg_id = substr($linkval_key[0],17,-1);
							if($static_cnt%2==0)
							{
								$row_count++;
								echo '<tr>';
							}
						?>
							<td align="left" valign="middle" class="sitemapcontents" width="50%">
							<a href="<?php url_static_page($pg_id,$linkval["'site_map_static_"."$pg_id'"])?>" class="sitemapcatlink" title="<?php echo stripslash_normal($linkval["'site_map_static_"."$pg_id'"])?>"><?=$linkval["'site_map_static_"."$pg_id'"]?>
							</a>
							</td>
						<?
							$static_cnt++;
							if($static_cnt%2==0)
							{
						?>
							</tr>
						<? }
						
						}
						elseif(substr($linkval_key[0],1,15) == 'site_map_categ_')
						{
							if($Captions_arr['SITEMAP']['SITEMAP_CATEGS_AND_PDTS_HEADING'] && !$categ_pdt_heading)
							{ ?>
								<tr>
								<td colspan="2" class="favoritesheader"><?=stripslash_normal($Captions_arr['SITEMAP']['SITEMAP_CATEGS_AND_PDTS_HEADING'])?></td>
								</tr>
							<? 
								$categ_pdt_heading=true;
							}
						$display_last_category =  true;
						$categ_id = substr($linkval_key[0],16,-1);
						if($i%2!=0)
						{
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
						elseif(substr($linkval_key[0],1,14) == 'site_map_prod_')
						{
							if($Captions_arr['SITEMAP']['SITEMAP_CATEGS_AND_PDTS_HEADING'] && !$categ_pdt_heading) { ?>
							<tr>
							<td colspan="2" class="favoritesheader"><?=stripslash_normal($Captions_arr['SITEMAP']['SITEMAP_CATEGS_AND_PDTS_HEADING'])?></td>
							</tr>
						<? 
							$categ_pdt_heading=true;
						}
						$pdt_id = substr($linkval_key[0],15,-1);
						if(!$display_last_category)
						{ // Display the category name if the listing starts from any products
							$sql_last_categid = "SELECT product_default_category_id 
													FROM 
														products 
													WHERE 
														product_id = ".$pdt_id." 
														AND product_hide ='N' 
														AND sites_site_id = $ecom_siteid LIMIT 1";
							$ret_last_categid	=	$db->query($sql_last_categid);
							list($last_categid) = $db->fetch_array($ret_last_categid);
							if($last_categid)
							{
								$sql_last_catname = "SELECT category_id,category_name 
														FROM 
															product_categories
														WHERE 
															category_id = ".$last_categid." 
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
						}
						if($i%2==0)
						{
							$row_count++;
							echo '<tr>';
						}
						?>
						
						<td align="left" valign="middle" class="sitemapcontents" width="50%">
						<a href="<?php url_product($pdt_id,$sitemap_products['product_name'],-1)?>" class="sitemapprodlink" title="<?php echo stripslash_normal($linkval["'site_map_prod_"."$pdt_id'"])?>">&raquo; <?=$linkval["'site_map_prod_"."$pdt_id'"]?>
						</a>
						</td>
						<?
						$i++;
						if($i%2==0)
						{
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
						
						if($element_cnt >= $max_elements)
						{
							$next = $link_key+1;
							break;
						}
					}
			}
			?>
				</table>
			
				<table width="100%" border="0" cellpadding="0" cellspacing="4">
				<tr>
				
				<td colspan="2" align="center" class="pagingcontainertd_normal"> 
				<?php
				 if((is_array($_SESSION['site_map_links'][$prev_start]) || !is_array($_SESSION['site_map_links'][$start_key])) && $_REQUEST['start_key']) 
				 {
				?>
					<a href="<? echo $ecom_selfhttp.$ecom_hostname?>/sitemap.html?start_key=<?=$prev_start?>" title="Prev"> &laquo; Prev  </a>&nbsp;&nbsp;
				<? 
				}
				if(is_array($_SESSION['site_map_links'][$next]))
				{
				?>
					<a href="<? echo $ecom_selfhttp.$ecom_hostname?>/sitemap.html?start_key=<?=$link_key?>" title="Next"> Next &raquo;</a> 
				<? 
				}
				?>
				</td>
				</tr>
				</table>
				</div>
				<div class="inner_contnt_bottom"></div>
				</div>	
			<?php	
			}
		}
	};	
?>
	<?php 
	



?>
			
