<?php
/*############################################################################
	# Script Name 	: myfavoritesHtml.php
	# Description 	: Page which holds the display logic for listing my favorite categories and products
	# Coded by 		: ANU
	# Created on	: 02-Feb-2008
	# Modified by	: 
	# Modified On	: 
	##########################################################################*/
	class myfavories_Html
	{
		// Defining function to show the My favorites
		function Show_Myfavorites($alert)
		{
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$vImage,$Settings_arr;
			 $customer_id 					= get_session_var("ecom_login_customer");
			$Captions_arr['MY_FAVORITES'] = getCaptions('MY_FAVORITES'); // to get values for the captions from the general settings site captions
			$sql_tot_fav_categories = "SELECT count(id) 
								FROM 
									product_categories pc,customer_fav_categories cfc 
								WHERE
									 pc.category_id = cfc.categories_categories_id AND pc.category_hide =0 AND
							cfc.sites_site_id = $ecom_siteid  and cfc.customer_customer_id= $customer_id";
			$ret_totfav_categories = $db->query($sql_tot_fav_categories);
			list($tot_cntcateg) 	= $db->fetch_array($ret_totfav_categories); 
			$categperpage	=	10;
			$pg_variablecateg	= 'categ_pg';
			if ($_REQUEST['req']!='')// LIMIT for Favorites is applied only if not displayed in home page
						{
							$start_varcateg 		= prepare_paging($_REQUEST[$pg_variablecateg],$categperpage,$tot_cntcateg);
							$Limitcateg			= " LIMIT ".$start_varcateg['startrec'].", ".$categperpage;
						}	
						else
							$Limitcateg = '';
						
							
			$sql_fav_categories = "SELECT category_id,category_name,
														  categories_categories_id,id 
												FROM 
													product_categories pc,customer_fav_categories cfc 
												WHERE
													 pc.category_id = cfc.categories_categories_id AND pc.category_hide =0 AND
											cfc.sites_site_id = $ecom_siteid  AND cfc.customer_customer_id= $customer_id
												$Limitcateg	";
				$ret_fav_categories = $db->query($sql_fav_categories);
				
			///////**********************FOR FAVORITE PRODUCTS*****************************///////////
			$sql_tot_fav_products = "SELECT count(id) 
								FROM 
									products p,customer_fav_products cfp 
								WHERE
									 p.product_id = cfp.products_product_id AND p.product_hide='N' AND
							cfp.sites_site_id = $ecom_siteid  AND cfp.customer_customer_id= $customer_id";
			$ret_totfav_products = $db->query($sql_tot_fav_products);
			list($tot_cntprod) 	= $db->fetch_array($ret_totfav_products); 
			$prodperpage		= 10;
			$pg_variableprod	= 'prod_pg';
			if ($_REQUEST['req']!='')// LIMIT for Favorites is applied only if not displayed in home page
						{
							$start_varprod 		= prepare_paging($_REQUEST[$pg_variableprod],$prodperpage,$tot_cntprod);
							$Limitprod			= " LIMIT ".$start_varprod['startrec'].", ".$prodperpage;
						}	
						else
							$Limitprod = '';
						
							
		 $sql_fav_products = "SELECT id,product_name, products_product_id
								FROM 
									products p,customer_fav_products cfp
								WHERE
									 p.product_id = cfp.products_product_id AND p.product_hide='N' AND
							cfp.sites_site_id = $ecom_siteid  AND cfp.customer_customer_id = $customer_id
								$Limitprod	";
				$ret_fav_products = $db->query($sql_fav_products);
		
		
		  $HTML_treemenu = '	<div class="breadcrump">
					<nav class="breadcrumb">
						 <a class="breadcrumb-item" href="'.url_link('',1).'" title="'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a>     
						 <span class="breadcrumb-item active">'.stripslash_normal($Captions_arr['MY_FAVORITES']['FAVORITES_TREEMENU_TITLE']).'</span>					</nav>
				
				</div>';
?>
<div class="container-fluid">
<form method="post" action="" name="frm_myfavorites" id="frm_myfavorites" class="frm_cls" onsubmit="return validate_allforms(this);">
<input type="hidden" name="fpurpose" id="fpurpose" value="" />
	
		<?=$HTML_treemenu?>
<div class="reg_shlf_outr">
				<div class="reg_shlf_inner">
					<div class="reg_shlf_inner_top"></div>
					<div class="reg_shlf_inner_cont">
					<?php
					if($Captions_arr['MY_FAVORITES']['FAVORITES_CATEGORY_HEADING']){
					?>
					<div class="reg_shlf_hdr_outr"><div class="reg_shlf_hdr_in"><span><?=stripslash_normal($Captions_arr['MY_FAVORITES']['FAVORITES_CATEGORY_HEADING'])?> </span></div></div>
					<? }?>
						<div class="reg_shlf_cont_div">
							<div class="reg_shlf_pdt_con">		
		<table width="100%" border="0" cellpadding="3" cellspacing="4" class="reg_table"  >
		<tr><td colspan="3">
		<table border="0" cellpadding="3" cellspacing="0" width="100%" class="table">
		<tr>
		<td  colspan="3" align="center" valign="middle" class="pagingcontainertd_normal"><?php
		if($db->num_rows($ret_fav_categories)){
		$path = url_link('myfavorites.html',1);
		$query_string='';
		$query_string .= '&prod_pg='.$_REQUEST['prod_pg'];
		paging_footer($path,$query_string,$tot_cntcateg,$start_varcateg['pg'],$start_varcateg['pages'],'',$pg_variablecateg,'Favourite categories',$pageclass_arr); 
		?>
		</td>
		</tr>
		<tr>
		<td width="18%" align="left" valign="middle" class="ordertableheader"><?=stripslash_normal($Captions_arr['MY_FAVORITES']['CART_ITEM'])?></td>
		<td width="63%" align="left" valign="middle"  class="ordertableheader"><?=stripslash_normal($Captions_arr['MY_FAVORITES']['CATEGORY_ITEM'])?></td>
		<td width="19%" align="left" valign="middle"  class="ordertableheader"><?=stripslash_normal($Captions_arr['MY_FAVORITES']['ACTION'])?></td>
		</tr>
		<?php
		$i=0;
		while($fav_categories = $db->fetch_array($ret_fav_categories)) {
		$i++;
		?>
		<tr class="edithreflink_tronmouse" title="View Details" onmouseover="this.className='edithreflink_trmouseout'" onmouseout="this.className='edithreflink_tronmouse'">
		<td width="18%" align="left" valign="middle" class="favcontent"><?=$i?></td>
		<td width="63%" align="left" valign="middle" class="favcontent"><a  class="favoritecatlink" href="<?php url_category($fav_categories['category_id'],$fav_categories['category_name'],-1,$grpData['catgroup_id'],0)?>" ><?=$fav_categories['category_name']?></a></td>
		<td width="19%" align="left" valign="middle" class="favcontent"><a  class="favorite_linkdelete deletecart btn btn-danger" href="remove-favorite-category-<?=$fav_categories['id']?>.html" onclick="if(confirm('<?=stripslash_javascript($Captions_arr['MY_FAVORITES']['CATEGORY_REM_MSG'])?>'))return true;else return false;" >
		<i class="fa fa-trash"></i>
		 </a></td>
		</tr>
		<? } 
		}
		else{ ?>
		<tr>
		<td colspan="3" class="errormsg" align="center">
		<?php 
		echo stripslash_normal($Captions_arr['MY_FAVORITES']['FAVORITES_NO_FAVORITE_CATEGORIES']);
		?>
		</td>
		</tr>
		<?php 
		} 
		?>
		</table>
		</td></tr>
		</table>
			 </div>
						</div>
					</div>
					<div class="reg_shlf_inner_bottom"></div>
				</div>
				</div>
		<?php //////////////***************LISTING OF FAVORITE PRODUCTS*************///////////////////?>
		<div class="reg_shlf_outr">
				<div class="reg_shlf_inner">
					<div class="reg_shlf_inner_top"></div>
					<div class="reg_shlf_inner_cont">
					<?php
					if($Captions_arr['MY_FAVORITES']['FAVORITES_PRODUCTS_HEADING']){
					?>
					<div class="reg_shlf_hdr_outr"><div class="reg_shlf_hdr_in"><span><?=stripslash_normal($Captions_arr['MY_FAVORITES']['FAVORITES_PRODUCTS_HEADING'])?> </span></div></div>
					<? }?>
						<div class="reg_shlf_cont_div">
							<div class="reg_shlf_pdt_con">	
		<table width="100%" border="0" cellpadding="3" cellspacing="4"  class="reg_table">
		<tr><td colspan="3">
		<table border="0" cellpadding="3" cellspacing="0" width="100%" class="table">
		<tr>
			<td  colspan="3" align="center" valign="middle" class="pagingcontainertd_normal"><?php
			if($db->num_rows($ret_fav_products)){
			$path = url_link('myfavorites.html',1);
			$query_string='';
			$query_string .= '&categ_pg='.$_REQUEST['categ_pg'];
			paging_footer($path,$query_string,$tot_cntprod,$start_varprod['pg'],$start_varprod['pages'],'',$pg_variableprod,'Favourite products',$pageclass_arr); 
			?></td>
			
		</tr>
		<tr>
			<td width="18%" align="left" valign="middle" class="ordertableheader"><?=stripslash_normal($Captions_arr['MY_FAVORITES']['CART_ITEM'])?></td>
			<td width="63%" align="left" valign="middle"  class="ordertableheader"><?=stripslash_normal($Captions_arr['MY_FAVORITES']['PRODUCT_ITEM'])?></td>
			<td width="19%" align="left" valign="middle"  class="ordertableheader"><?=stripslash_normal($Captions_arr['MY_FAVORITES']['ACTION'])?></td>
		</tr>
		<?php
		
			$i=0;
		 while($fav_products = $db->fetch_array($ret_fav_products)) {
		$i++;?>
		<tr class="edithreflink_tronmouse" title="View Details" onmouseover="this.className='edithreflink_trmouseout'" onmouseout="this.className='edithreflink_tronmouse'">
			<td width="18%" align="left" valign="middle" class="favcontent"><?=$i?></td>
			<td width="63%" align="left" valign="middle" class="favcontent"><a href="<?php url_product($fav_products['products_product_id'],$fav_products['product_name'],-1)?>" class="favoriteprodlink"><?=$fav_products['product_name']?></a></td>
			<td width="19%" align="left" valign="middle" class="favcontent"><a href="remove-favorite-product-<?=$fav_products['id']?>.html" class="favorite_linkdelete deletecart btn btn-danger" onclick="if(confirm('<?=stripslash_javascript($Captions_arr['MY_FAVORITES']['PRODUCT_REM_MSG'])?>'))return true;else return false;" >
			<i class="fa fa-trash"></i>
			</a></td>
		</tr>
		
		<? }
		}else{ ?>
			<tr>
				<td colspan="3" class="errormsg" align="center">
				<?php 
						  echo stripslash_normal($Captions_arr['MY_FAVORITES']['FAVORITES_NO_FAVORITE_PRODUCTS']);
				?>
				</td>
			</tr>
		<?php } 
		
		?>
		</table>
		</td></tr>
		<?php /*******************END --- FAVORITE PRODUCTS*************************/?>
		
	</table>
		 </div>
						</div>
					</div>
					<div class="reg_shlf_inner_bottom"></div>
				</div>
				</div>
		</form>
		</div>
		<?php	
		}
		function Display_Message($mesgHeader,$Message){
		global $Captions_arr;
		?>
		<div class="reg_shlf_outr">
				<div class="reg_shlf_inner">
					<div class="reg_shlf_inner_top"></div>
					<div class="reg_shlf_inner_cont">
					<?php
					if($mesgHeader){
					?>
					<div class="reg_shlf_hdr_outr"><div class="reg_shlf_hdr_in"><span><?=stripslash_normal($mesgHeader)?> </span></div></div>
					<? }?>
						<div class="reg_shlf_cont_div">
							<div class="reg_shlf_pdt_con">
							<table width="100%" border="0" cellspacing="0" cellpadding="0" class="reg_table">
							<tr>
							<td align="left" valign="middle" class="message"><?php echo $Message; ?></td>
							</tr>
							</table>
							</div>
						</div>
					</div>
					<div class="reg_shlf_inner_bottom"></div>
				</div>
				</div>
		<?php	
		}
		
	};	
?>
	<?php 
	



?>
			
