<?php
	/*############################################################################
	# Script Name 	: homepageHtml.php
	# Description 	: Page which holds the display logic for Home page Content
	# Coded by 		: LSH
	# Created on	: 03-June-2008
	# Modified by	: LSH
	# Modified On	: 03-June-2008
	##########################################################################*/
	class Homepage_Html
	{
		// Defining function to show the selected static pages
		function Show_HomePage($ret_homepage)
		{
			global 	$Captions_arr,$ecom_siteid,$db,$ecom_hostname,$ecom_themename,
			$ecom_themeid,$default_layout,$inlineSiteComponents,$Settings_arr;
			// ** Fetch any captions for product details page
			$Captions_arr['STATIC_PAGES'] = getCaptions('STATIC_PAGES');
			// ** Fetch the product details
			$row_homepage	= $db->fetch_array($ret_homepage);
			// ** Check to see whether current user is logged in 
			$sql_feature = "SELECT feature_id FROM features WHERE feature_modulename='mod_homepagecontent'";
			$ret_feature = $db->query($sql_feature);
			if ($db->num_rows($ret_feature))
			{
				$row_feature 	= $db->fetch_array($ret_feature);
				$feat_id		= $row_feature['feature_id'];
			}
			// Find the layoutid for current layout code
			$sql_layout = "SELECT layout_id 
							FROM 
								themes_layouts 
							WHERE 
								themes_theme_id = $ecom_themeid 
								AND layout_code='$default_layout'";
			$ret_layout = $db->query($sql_layout);
			if ($db->num_rows($ret_layout))
			{
				$row_layout = $db->fetch_array($ret_layout);
				$layid		= $row_layout['layout_id'];
			}					
			// Get the title to be shown from the display settings table
			$sql_disp = "SELECT display_title 
							FROM 
								display_settings 
							WHERE 
								sites_site_id = $ecom_siteid 
								AND features_feature_id=$feat_id 
								AND display_position ='middle' 
								AND themes_layouts_layout_id=$layid 
								AND layout_code ='".$default_layout."' 
								";
			$ret_disp = $db->query($sql_disp);
			if ($db->num_rows($ret_disp))
			{
				$row_disp 	= $db->fetch_array($ret_disp);
				$cur_title 	= stripslash_normal($row_disp['display_title']); 
			}
		?>
			<form method="post" name="frm_homepage" id="frm_homepage" action="" class="frm_cls">
			<input type="hidden" name="fpurpose" value="" />
			<div class="home_con">
			<div class="home_top"></div>
			<div class="home_middle">
			<?php echo stripslashes($row_homepage['content'])?>
			</div>
			<div class="home_bottom"></div>
			</div>
			</form>
	<?php	
		}
	};	
?>