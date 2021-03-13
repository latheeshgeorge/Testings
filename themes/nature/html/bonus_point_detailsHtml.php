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
 ?>
		<div class="treemenu">
				<ul>
				  <li><a href="<? url_link('');?>"><?=stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']);?></a> &gt;&gt; </li>
				  <li><?=stripslash_normal($Captions_arr['COMMON']['BONUS_DETAILS_TREEMENU_TITLE'])?></li>
				</ul>
		     </div>
	 <div class="inner_contnt">
        <div class="inner_contnt_top"></div>
		<div class="inner_contnt_middle">
         <div class="inner_contnt_hdr"><?=stripslash_normal($Captions_arr['COMMON']['BONUS_DETAILS_TREEMENU_TITLE'])?></div>
			 <table width="100%" border="0" cellpadding="0" cellspacing="3"  class="emailfriendtable">
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
			<div class="inner_contnt_bottom"></div>
			</div>

		<?php	
		}
		
	};	
?>
