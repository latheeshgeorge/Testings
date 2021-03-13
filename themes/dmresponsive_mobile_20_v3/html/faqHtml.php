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
			
			
												
												$HTML_treemenu = '	<div class="breadcrump">
					<nav class="breadcrumb">
						 <a class="breadcrumb-item" href="'.url_link('',1).'" title="'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a>     
						 <span class="breadcrumb-item active">'.stripslash_normal($Captions_arr['FAQ']['FAQ_HEAD']).'</span>					</nav>
				
				</div>';
			echo $HTML_treemenu;	
			
	?>
		<div class='faq_outer'>
		<a name="top"></a>
		<div class="container">
		<div class="panel-group" id="accordion">


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
				 $faq_id = $k;
				 echo 	"<div class=\"panel panel-default\" >
							<div class=\"panel-heading\">
							<a class=\"accordion-toggle\" data-toggle=\"collapse\" data-parent=\"#accordion\" href=\"#collapseOne".$faq_id."\"><h4 class=\"panel-title\">

							Q:&nbsp;".$v['faq_question']."<i class=\" pull-right\"></i>
							</h4></a>
							</div>
							<div id=\"collapseOne".$faq_id."\" class=\"panel-collapse collapse out\">
							<div class=\"panel-body\">
							";
		?>	
			<?php 
				$sr_array  = array('rgb(0, 0, 0)','#000000');
				$rep_array = array('rgb(255,255,255)','#ffffff'); 
				$ans_desc = str_replace($sr_array,$rep_array,$v['faq_answer']);
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
