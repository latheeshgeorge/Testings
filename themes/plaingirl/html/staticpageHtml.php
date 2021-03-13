<?php
	/*############################################################################
	# Script Name 	: staticpageHtml.php
	# Description 	: Page which holds the display logic for Static pages
	# Coded by 		: Anu
	# Created on	: 22-Feb-2008
	# Modified by	: ANU
	# Modified On	: 22-Feb-2008
	##########################################################################*/
	class static_Html
	{
		// Defining function to show the selected static pages
		function Show_StaticPage($row_statpage)
		{
			global 	$Captions_arr,$ecom_siteid,$db,$ecom_hostname,$ecom_themename,
					$ecom_themeid,$default_layout,$inlineSiteComponents,$Settings_arr;
			// ** Fetch any captions for product details page
			$Captions_arr['STATIC_PAGES'] 	= getCaptions('STATIC_PAGES');
			
			// ** Fetch the product details
			//$row_statpage	= $db->fetch_array($ret_statpage);
			
			// ** Check to see whether current user is logged in 
			$cust_id 	= get_session_var("ecom_login_customer");
			$HTML_img = $HTML_alert = $HTML_treemenu='';
			$HTML_treemenu = '<div class="tree_menu_conA">
								  <div class="tree_menu_topA"></div>
								  <div class="tree_menu_midA">
									<div class="tree_menu_content">
									   <ul class="tree_menu">
										<li><a href="'.url_link('',1).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a></li>
										 <li>'.stripslash_normal($row_statpage['title']).'</li>
										</ul>
									  </div>
								  </div>
								  <div class="tree_menu_bottomA"></div>
								</div>';
			echo $HTML_treemenu;
			//if($row_statpage['content']!='')
			{
				if($row_statpage['page_id']!=50475 and $row_statpage['page_id']!=50586 )
				{
				
		?>
		<form method="post" name="frm_staticpage" id="frm_staticpage" action="" class="frm_cls">
		<input type="hidden" name="fpurpose" value="" />
		<input type="hidden" name="fproduct_id" value="<?php echo $_REQUEST['product_id']?>" />
		 <div class="inner_contnt" >
        <div class="inner_contnt_top"></div>
		<div class="inner_contnt_middle">
			<table border="0" cellpadding="0" cellspacing="0" class="statictable">
			<tr>
			  <td valign="top">
			  <?php 
				$sr_array  = array();//array('rgb(0, 0, 0)','#000000');
				$rep_array = array();//array('rgb(255,255,255)','#ffffff'); 
				$stat_cont = str_replace($sr_array,$rep_array,stripslashes($row_statpage['content']));
			  echo $stat_cont?>
			  </td>
			  
			</tr>
			
		  </table>
			</div>
			<div class="inner_contnt_bottom"></div>
			</div> 
 		</form>
        <?php
			}
			else
			{
				$gal_name = '';
				if($row_statpage['page_id']==50475)
				{
				  $gal_name = 'Gallery_holder_hidden';
				}
				else if($row_statpage['page_id']==50586)
				{
					$gal_name = 'Gallery_holder_hidden_second';
				}
			     
				// Get the id of the category with the name "Gallery_holder_hidden"
				$sql="SELECT category_id FROM product_categories WHERE sites_site_id = $ecom_siteid AND category_name ='$gal_name' LIMIT 1";
				$ret = $db->query($sql);
				$pass_type = 'image_bigcategorypath';
				$catimg_arr = array();
				if($db->num_rows($ret))
				{
					$row = $db->fetch_array($ret);
					$catimg_arr = get_imagelist('prodcat',$row['category_id'],$pass_type,0,0);
					
				/*
			?>
				<link href="http://<?php echo $ecom_hostname?>/images/lightbox.css" media="screen" type="text/css" rel="stylesheet" />
				<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/prototype/1.6.0.2/prototype.js"></script>
				<script type="text/javascript" src="http://<?php echo $ecom_hostname?>/images/lightbox.js"></script>			
				<script type="text/javascript" src="http://<?php echo $ecom_hostname?>/images/effects.js"></script>		
				*/
				?> 
				<script type="text/javascript" language="javascript">
			
					jQuery.noConflict(); /* This is done to avoid error in light box due to the usage of $ in jquery*/			
			
			</script>		
					<style type="text/css">
					.main_div1 {
						text-align:center;
						width:925px;
					}
					
					.img_div1{
						vertical-align: middle;
						padding:2px;
						height:310px;
						width:280px;
						display: table-cell;
						margin:auto;
					}
					
					.repat_div1{
						width: 280px;
						height:370px;
						float:left;
						border:1px solid #DCDCDC;
						text-align:center;
						
					}
					.helper {
						display: inline-block;
						vertical-align: middle;
					}
					.img_cap1
					{
						width:99%;
						height:50px;
						text-align:center;
						font-weight:bold;
						font-size:11px;
						background-color:#DCDCDC;
						color:#7a7a7a;
						margin:1px; 
						padding:2px 2px 2px 2px;
					}
					.img_div1  img {
						display: block;
						margin-left: auto;
						margin-right: auto;
						vertical-align: middle;
						text-align:center;
						border:1px solid #DCDCDC;
					}
					</style>
					
					<div class="main_div1">
					<?php
					foreach ($catimg_arr as $ks=>$vm)
					{
					?>
						<div class="repat_div1">
							<div class="img_div1"><a href="<?php url_root_image($vm['image_extralargepath'])?>" rel="lightbox[gallery]" title="<?php echo $vm['image_title'];?>"><img  src="<?php url_root_image($vm['image_bigcategorypath'])?>" title="<?php echo $vm['image_title'];?>"></a></div>
							<div class="img_cap1"><?php echo $vm['image_title'];?></div>
						</div>
					<?php   
					}
					?>
					</div>
					<?php
				}
			?>	
			<?php	
			}
		} 
		  
			if($row_statpage['page_id']!=50475)
			{
				global $shelf_for_inner;
				$sql_inline = "SELECT display_order,display_component_id,display_id,display_title 
					FROM 
						display_settings a,features b 
					WHERE 
						a.sites_site_id=$ecom_siteid 
						AND a.display_position='middle' 
						AND b.feature_allowedinmiddlesection = 1  
						AND layout_code='".$default_layout."' 
						AND a.features_feature_id=b.feature_id 
						AND b.feature_modulename='mod_shelf' 
					ORDER BY 
							display_order 
							ASC";
				$ret_inline = $db->query($sql_inline); // to dispplay the advert for the category page
				if ($db->num_rows($ret_inline))
				{
					while ($row_inline = $db->fetch_array($ret_inline))
					{
						//$modname 			= $row_inline['feature_modulename'];
						$body_dispcompid	= $row_inline['display_component_id'];
						$body_dispid		= $row_inline['display_id'];
						$body_title			= $row_inline['display_title'];
						$shelf_for_inner	= true;
						include ("includes/base_files/shelf.php");
						$shelf_for_inner	= false;

					}
				}
			}	
		}
		
	};	
?>