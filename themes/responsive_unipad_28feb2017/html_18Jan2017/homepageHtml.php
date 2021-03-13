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
			<div class="container-fluid">
			<div class="welcome">
			<?php echo stripslashes($row_homepage['content'])?>
			</div>
      		</div>  
      		
      		<style type="text/css">
      		
			#carousel1 {
				/*margin-top: -100px;*/
			}
			#carousel1 div {
				text-align: center;
				width: 403px;
				height: 102px;
				float: left;
				position: relative;
				background-color: #E3E3E3;
			}
			#carousel1 div img {
				border: none;
			}
			#carousel1 div span {
				display: none;
			}
			#carousel1 div:hover span {
				background-color: #333;
				color: #fff;
				font-family: Arial, Geneva, SunSans-Regular, sans-serif;
				font-size: 14px;
				line-height: 22px;
				display: inline-block;
				width: 100px;
				padding: 2px 0;
				margin: 0 0 0 -50px;
				position: absolute;
				bottom: 30px;
				left: 50%;
				border-radius: 3px;
			}
      		
      		</style>
      		<script src="http://<?php echo $ecom_hostname?>/images/<?php echo $ecom_hostname?>/scripts/jquery.carouFredSel-6.0.4-packed.js" type="text/javascript"></script>
		<div class="container-fluid">
		<div class="unipad_features_head">
			<?php echo "Unipad Features"?>
			</div>	
		<div id="carousel1">
				<div>
					<img src="<?php url_site_image('icon-bar1.jpg')?>" />
				</div>
				<div>
					<img src="<?php url_site_image('icon-bar2.jpg')?>" />
				</div>
				<div>
					<img src="<?php url_site_image('icon-bar3.jpg')?>" />
				</div>
				<div>
					<img src="<?php url_site_image('icon-bar4.jpg')?>" />
				</div>
				<div>
					<img src="<?php url_site_image('icon-bar5.jpg')?>" />
				</div>
			</div>
		</div>
		
		<script type="text/javascript">
			$(function() {

				var $c = $('#carousel1'),
					$w = $(window);

				$c.carouFredSel({
					align: false,
					items: 4,
					scroll: {
						items: 1,
						duration: 6000,
						timeoutDuration: 0,
						easing: 'linear',
						pauseOnHover: 'immediate'
					}
				});

			});
		</script>
      		    		
	<?php	
		}
	};	
?>
