<?php
/*############################################################################
	# Script Name 	: callbackHtml.php
	# Description 	: Page which holds the display logic for call back
	# Coded by 		: ANU
	# Created on	: 04-Jan-2008
	# Modified by	: 
	# Modified On	: 
	##########################################################################*/
	class freedelivery_Html
	{
		// Defining function to show the Call Back
		function Show_Freedelivery($prod_name='',$product_id=0)
		{
			 global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$vImage,$Settings_arr,$alert;
			 $sql 							= "SELECT product_freedelivery_content FROM general_settings_sites_common WHERE sites_site_id=".$ecom_siteid;
		     $res_admin 				= $db->query($sql);
		     $fetch_arr_admin 	= $db->fetch_array($res_admin);
			 $Captions_arr['PROD_FREE_DELIVERY'] 	= getCaptions('PROD_FREE_DELIVERY');
			 $HTML_img = $HTML_alert = $HTML_treemenu='';
				$HTML_treemenu .=
				'<ul class="tree_menu_details">
					<li><a href="'.url_link('',1).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a></li>';
					if($product_id!=0)
					{
					 $HTML_treemenu .='<li><a href="'.url_product($product_id,$prod_name,1).'">'.$prod_name.'</a></li>';
					}
					$HTML_treemenu .='<li>'.stripslash_normal($Captions_arr['PROD_FREE_DELIVERY']['PROD_FREE_DELIVERY_TREEMENU_TITLE']).'</li>
					</ul>
					  ';
				echo $HTML_treemenu;
 ?>
  <div class="inner_contnt" >
        <div class="inner_contnt_top"></div>
		<div class="inner_contnt_middle">
			<table border="0" cellpadding="0" cellspacing="0" class="statictable">
			 <? if($fetch_arr_admin['product_freedelivery_content']!='')
					 {
					 ?>
						<tr>
						  <td valign="top">
						  <?php 
						  echo stripslashes($row_statpage['content'])?>
						  </td>
						  
						</tr>
						<?php
								}
								else
								{
								?>
								 <tr>
								 <td valign="top">
								 <?php
								 echo stripslash_normal($Captions_arr['PROD_FREE_DELIVERY']['PROD_FREE_DELIVERY_NOCONTENT']);
								 ?>
							   </td>
							   </tr>
								<?php
								}
								?>
					  </table>
						</div>
						<div class="inner_contnt_bottom"></div>
						</div> 
		  
					<?php	
					}
		
	};	
?>
