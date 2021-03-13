<?php
/*############################################################################
	# Script Name 	: callbackHtml.php
	# Description 	: Page which holds the display logic for call back
	# Coded by 		: ANU
	# Created on	: 04-Jan-2008
	# Modified by	: 
	# Modified On	: 
	##########################################################################*/
	class bonuspoint_Html
	{
		// Defining function to show the Call Back
		function Show_Bonusdetails()
		{
			 global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$vImage,$Settings_arr,$alert;
			 $sql 							= "SELECT bonus_point_details_content FROM general_settings_sites_common WHERE sites_site_id=".$ecom_siteid;
		     $res_admin 				= $db->query($sql);
		     $fetch_arr_admin 	= $db->fetch_array($res_admin);
			 $Captions_arr['COMMON'] 	= getCaptions('COMMON');
			 $HTML_img = $HTML_alert = $HTML_treemenu='';
				$HTML_treemenu .=
				'<div class="tree_menu_conA">
				  <div class="tree_menu_topA"></div>
				  <div class="tree_menu_midA">
					<div class="tree_menu_content">
					  <ul class="tree_menu">
					<li><a href="'.url_link('',1).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a></li>';
					if($product_id!=0)
					{
					 $HTML_treemenu .='<li><a href="'.url_product($product_id,$prod_name,1).'">'.$prod_name.'</a></li>';
					}
					$HTML_treemenu .='<li>'.stripslash_normal($Captions_arr['COMMON']['BONUS_DETAILS_TREEMENU_TITLE']).'</li>
					</ul>
					  </div>
				  </div>
				  <div class="tree_menu_bottomA"></div>
				</div>';
				echo $HTML_treemenu;
 ?>
		  <div class="reg_shlf_outr">
           <div class="reg_shlf_inner">
            <div class="reg_shlf_inner_top"></div>
           <div class="reg_shlf_inner_cont">
           <div class="reg_shlf_hdr_outr"><div class="reg_shlf_hdr_in"><span><?=stripslash_normal($Captions_arr['COMMON']['BONUS_DETAILS_TREEMENU_TITLE'])?></span></div></div>

            <div class="reg_shlf_cont_div">
           <div class="reg_shlf_pdt_con">
			 <table width="100%" border="0" cellpadding="0" cellspacing="3"  class="reg_table">
					 <? if($fetch_arr_admin['bonus_point_details_content']!='')
					 {
					 ?>
					 <tr>
					 <td>
					 <table border="0" cellspacing="0" cellpadding="0" width="100%" class="bottom_cont_table_price">
						<tr>
						<td class="price_bottcntnt">
						<? echo stripslashes($fetch_arr_admin['bonus_point_details_content'])?>
						 </td>
						</tr>
						</table>
					    </td>
					 </tr>
					<?php
					}
					else
					{
					?>
					 <tr>
					 <td class="regiconentA">
					 <?php
					 echo stripslash_normal($Captions_arr['COMMON']['BONUS_DETAILS_NO_CONTENT']);
				     ?>
				   </td>
				   </tr>
				   	<?php
					}
					?>
				</table>
			</div>
		 </div>
		 </div>
         <div class="reg_shlf_inner_bottom"></div>
        </div>
        </div>
		<?php	
		}
		
	};	
?>
