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
					$ecom_themeid,$default_layout,$inlineSiteComponents,$Settings_arr,$publickey,$site_key;
			// ** Fetch any captions for product details page
			$Captions_arr['STATIC_PAGES'] 	= getCaptions('STATIC_PAGES');
			
			$captcha_code = '<div><div class="g-recaptcha" data-sitekey="'.$site_key.'"></div></div>';
			
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
		<script src='https://www.google.com/recaptcha/api.js'></script>
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
			  if($_REQUEST['page_id']==727 or $_REQUEST['page_id']==50072)
			  {
				 $file_pathc = ORG_DOCROOT."/images/".$ecom_hostname."/otherfiles/contact.html";
				 if(file_exists($file_pathc))
				 { 
					$row_statpage['content'] = file_get_contents($file_pathc);
				 } 
			  }
			  $stat_cont = str_replace($sr_array,$rep_array,stripslashes($row_statpage['content']));
				$stat_cont= str_replace('[captcha_here]',$captcha_code,$stat_cont);
			  echo $stat_cont;
			  //echo stripslashes($row_statpage['content'])
			  ?>
			  </td>
			  
			</tr>
			
		  </table>
			</div>
			<div class="inner_contnt_bottom"></div>
			</div> 
 		</form>
        <?php
		} 
				
		
				?>

	<?php	
		}
		
	};	
?>
