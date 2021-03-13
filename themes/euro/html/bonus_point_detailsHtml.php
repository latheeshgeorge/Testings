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
 ?>
	 <div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a>
	          >><?=stripslash_normal($Captions_arr['COMMON']['BONUS_DETAILS_TREEMENU_TITLE'])?></div>
			<div class="shelf_main_con" >
			<div class="shelf_top"><?=stripslash_normal($Captions_arr['COMMON']['BONUS_DETAILS_TREEMENU_TITLE'])?></div>
			<div class="shelf_mid">
				<table border="0" cellpadding="0" cellspacing="0" class="staticpagetable">
				<? if($fetch_arr_admin['bonus_point_details_content']!='')
					{ 
					?>
					<tr>
						<td valign="top" class="staticpagecontent">
							<?   echo stripslashes($fetch_arr_admin['bonus_point_details_content']); 
							?>
						</td>
					</tr>
					<? }
					else
					{
					?>
					<tr>
						<td class="staticpagecontent">
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
			<div class="shelf_bottom"></div>	
			</div>
		<?php	
		}
		
	};	
?>
