<?php
	/*############################################################################
	# Script Name 		: helpHtml.php
	# Description 		: Page which holds the display logic for HELP Page
	# Coded by 			: LG
	# Created on		: 31-Dec-2008
	# Modified by		: 
	# Modified On		: 
	##########################################################################*/
	class help_Html
	{
		// Defining function to show the selected static pages
		function Show_Help($title,$ret_help)
		{
			global 	$Captions_arr,$ecom_siteid,$db,$ecom_hostname,$ecom_themename,
					$ecom_themeid,$default_layout,$inlineSiteComponents,$Settings_arr;
			// ** Fetch any captions for product details page
			$Captions_arr['HELP'] 	= getCaptions('HELP');
			// ** Check to see whether current user is logged in 
			$cust_id 	= get_session_var("ecom_login_customer");
			while ($row_help = $db->fetch_array($ret_help))
			{
				$help_arr[$row_help['help_id']] = array (
																		'help_heading'=>stripslashes($row_help['help_heading']),
																		'help_description'=>stripslashes($row_help['help_description'])
																	);
			}
	?>
		<a name="top"></a>
		<div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?php echo $Captions_arr['HELP']['HELP_HEAD']?> </div>
		<?php 
			// Showing the headings and descriptions
			foreach ($help_arr as $k=>$v)
			{ 
		?>	
			<ul class="help_ans">
			<li class="helpqst"><a name="help<?php echo $k?>"></a><?php echo $v['help_heading']?></li>
			<li class="helpcontent">
			<?php echo $v['help_description']?>
			</li>
			
<!--			<li class="helptop"><a href="#top"><img src="<?php //url_site_image('top.gif')?>" border="0" alt="Go Top" /></a></li>
-->			</ul>
	<?php	
			}
		}
	};	
?>