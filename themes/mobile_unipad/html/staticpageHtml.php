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
										<li><a href="'.url_link('',1).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a> | </li>
										 <li>'.stripslash_normal($row_statpage['title']).'</li>
										</ul>
									  </div>
								  </div>
								  <div class="tree_menu_bottomA"></div>
								</div>';
			echo $HTML_treemenu;
			if($row_statpage['content']!='')
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
			  echo stripslashes($row_statpage['content'])?>
			  </td>
			  
			</tr>
			
		  </table>
			</div>
			<div class="inner_contnt_bottom"></div>
			</div> 
 		</form>
        <?php
		} 
				
		
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
		
	};	
?>
