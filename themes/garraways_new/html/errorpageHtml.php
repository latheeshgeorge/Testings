<?php
	/*############################################################################
	# Script Name 	: errorpageHtml.php
	# Description 	: Page which holds the display logic for Static pages
	# Coded by 		: Sny
	# Created on	: 29-May-2014
	# Modified by	: 
	# Modified On	: 
	##########################################################################*/
	class error_Html
	{
		// Defining function to show the selected static pages
		function Show_ErrorPage()
		{
			global 	$Captions_arr,$ecom_siteid,$db,$ecom_hostname,$ecom_themename,
					$ecom_themeid,$default_layout,$inlineSiteComponents,$Settings_arr,$vImage,$publickey;
			// ** Fetch any captions for product details page
			$Captions_arr['STATIC_PAGES'] 	= getCaptions('STATIC_PAGES');
			// Check whether there exists a static page with the name '404-error-page'
			$content_404 = '';
			$sql_chk = "SELECT content 
						FROM 
							static_pages 
						WHERE 
							title='404-error-page' 
							AND sites_site_id = $ecom_siteid 
						LIMIT 
							1";
			$ret_chk = $db->query($sql_chk);
			if($db->num_rows($ret_chk))
			{
				$row_chk = $db->fetch_array($ret_chk);
				$content_404 = trim(stripslashes($row_chk['content']));
			}
			if ($content_404=='')
				$content_404 = $Captions_arr['STATIC_PAGES']['ERROR_TEXT1'];
			
		?>
		<div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <? echo $Captions_arr['STATIC_PAGES']['ERROR_HEADING']?></div>
			<table border="0" cellpadding="0" cellspacing="0" class="staticpagetable">
			<tr>
			  <td valign="top" class="staticpagecontent">
				  <h2><?php //echo $Captions_arr['STATIC_PAGES']['ERROR_HEADING']?></h2>
			  <?php 
					echo $content_404;
			  /*
			  ?>
			  <br><br><?php echo $Captions_arr['STATIC_PAGES']['ERROR_TEXT2'];?> <a href='<?php url_link('')?>' style="color:#74ac3a;font-weight:bold">Return Home</a>.<?php */?>
			  </td>
			  
			</tr>
		  </table>
 		</form>
		
	<?php	
		}
		
	};	
?>
