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
		<div class="treemenu">
          <ul>
            <li><a href="<? url_link('');?>" title="<?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> &gt;&gt; </li>
            <li><?php echo $Captions_arr['HELP']['HELP_HEAD']?></li>
          </ul>
        </div>
		 <div class="inner_contnt" >
        <div class="inner_contnt_top"></div>
		<div class="inner_contnt_middle">
		<div class="inner_contnt_hdr"><?php echo $Captions_arr['HELP']['HELP_HEAD']?></div>
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
			</ul>
	<?php	
			}
	?>
			</div>
			<div class="inner_contnt_bottom"></div>
			</div>
	<?php		
		}
	};	
?>