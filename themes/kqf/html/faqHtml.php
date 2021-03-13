<?php
	/*############################################################################
	# Script Name 		: faqHtml.php
	# Description 		: Page which holds the display logic for FAQ Page
	# Coded by 			: Sny
	# Created on		: 31-Dec-2008
	# Modified by		: 
	# Modified On		: 
	##########################################################################*/
	class faq_Html
	{
		// Defining function to show the selected static pages
		function Show_Faq($title,$ret_faq)
		{
			global 	$Captions_arr,$ecom_siteid,$db,$ecom_hostname,$ecom_themename,
					$ecom_themeid,$default_layout,$inlineSiteComponents,$Settings_arr;
			// ** Fetch any captions for product details page
			$Captions_arr['FAQ'] 	= getCaptions('FAQ');
			
			// ** Check to see whether current user is logged in 
			$cust_id 	= get_session_var("ecom_login_customer");
			while ($row_faq = $db->fetch_array($ret_faq))
			{
				$faq_arr[$row_faq['faq_id']] = array (
																		'faq_question'=>stripslashes($row_faq['faq_question']),
																		'faq_answer'=>stripslashes($row_faq['faq_answer'])
																	);
			}
	?>
		<a name="top"></a>
		<div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?php echo $Captions_arr['FAQ']['FAQ_HEAD']?> </div>
		<ul class="faq">
		<?php 
			foreach ($faq_arr as $k=>$v)
			{
		?>	
				<li><a href="#faq<?php echo $k?>"><?php echo $v['faq_question']?></a></li>
		<?php
			}
		?>		
		</ul>
		<?php 
			// Showing the questions and answers
			foreach ($faq_arr as $k=>$v)
			{
		?>	
			<ul class="faq_ans">
			<li class="faqqst"><a name="faq<?php echo $k?>"></a><?php echo $v['faq_question']?></li>
			<li class="faqcontent">
			<?php echo $v['faq_answer']?>
			</li>
			
			<li class="faqtop"><a href="#top"><img src="<?php url_site_image('top.png')?>" border="0" alt="Go Top" /></a></li>
			</ul>
	<?php	
			}
		}
	};	
?>