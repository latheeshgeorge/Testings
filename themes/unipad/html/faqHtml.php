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
				$faq_arr[$row_faq['faq_id']] = array 	
													(
														'faq_question'=>stripslash_normal($row_faq['faq_question']),
														'faq_answer'=>stripslashes($row_faq['faq_answer'])
													);
			}
			$HTML_treemenu = '<div class="treemenu">
								<a href="'.url_link('',1).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a>
								<span>»</span>'.stripslash_normal($Captions_arr['FAQ']['FAQ_HEAD']).'</div>';
			echo $HTML_treemenu;	
	?>
	<script>
		   jQuery.noConflict();
            var $j = jQuery; 
  $j(function() {
    $j( "#accordion" ).accordion({
         active: false,
            autoHeight: false,
            navigation: true,
            collapsible: true,
            heightStyle: "content" 
    });
  });
  </script>
		<a name="top"></a>
		 <div class="inner_contnt" >
        <div class="inner_contnt_top"></div>
		<div class="inner_contnt_middle">
			<div id="accordion">

		<?php
		/*
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
		*/  
			// Showing the questions and answers
			foreach ($faq_arr as $k=>$v)
			{
		?>	
			<h3 class="faqqst">Q:&nbsp;<?php echo $v['faq_question']?></h3>
			<div class="faqcontent">
			<?php 
				$sr_array  = array('rgb(0, 0, 0)','#000000');
				$rep_array = array('rgb(255,255,255)','#ffffff'); 
				$ans_desc = str_replace($sr_array,$rep_array,$v['faq_answer']);
			echo $ans_desc?>
			</div>
			
	<?php	
			}
	?>
		</div>
		</div>
		<div class="inner_contnt_bottom"></div>
		</div> 
	<?php		
		}
	};	
?>
