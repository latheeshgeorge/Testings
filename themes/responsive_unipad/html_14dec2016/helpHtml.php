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
															'help_heading'=>stripslash_normal($row_help['help_heading']),
															'help_description'=>stripslashes($row_help['help_description'])
														);
			}
		
		     		$HTML_treemenu = '	<div class="row breadcrumbs">
												<div class="container">
												<div class="container-tree">
												<ul>
												<li><a href="'.url_link('',1).'" title="'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a></li>
												<li> → '.stripslash_normal($Captions_arr['HELP']['HELP_HEAD']).'</li>

												</ul>
												</div>
												</div></div>';	
							echo $HTML_treemenu;	
	?>
		<div class='faq_outer'>
		<a name="top"></a>
		<div class="<?php echo CONTAINER_CLASS;?>">
		<div class="panel-group" id="accordion">
		
		<?php 
			// Showing the headings and descriptions
			foreach ($help_arr as $k=>$v)
			{ 
				$help_id = $k;
				echo 	"<div class=\"panel panel-default\" >
							<div class=\"panel-heading\">
							<a class=\"accordion-toggle\" data-toggle=\"collapse\" data-parent=\"#accordion\" href=\"#collapseOne".$help_id."\"><h4 class=\"panel-title\">

							Q:&nbsp;".$v['help_heading']."<i class=\" pull-right\"></i>
							</h4></a>
							</div>
							<div id=\"collapseOne".$help_id."\" class=\"panel-collapse collapse out\">
							<div class=\"panel-body\">
							";
							
		?>	
		<?php 
				$sr_array  = array('rgb(0, 0, 0)','#000000');
				$rep_array = array('rgb(255,255,255)','#ffffff'); 
				$ans_desc = str_replace($sr_array,$rep_array,$v['help_description']);
			echo $ans_desc?>			
	<?php	
	echo  "
								   </div>
									</div>
									</div>";
			}
	?>
			</div> 
		</div> 
		</div>
	<?php		
		}
	};	
?>
