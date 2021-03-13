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
 ?>
	<div class="tree_con">
		<div class="tree_top"></div>
		<div class="tree_middle">
			<div class="pro_det_treemenu">
				<ul>
				<li><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> &gt;&gt; </li>
				<? if($product_id!=0)
				{
				?>
				<li> <a href="<?php url_product($product_id,$prod_name,-1)?>"><?=$prod_name?></a> &gt;&gt;</li>
				<? 
				 }
				?>
				<li> <?=$Captions_arr['PROD_FREE_DELIVERY']['PROD_FREE_DELIVERY_TREEMENU_TITLE']?></li>
				</ul>
			</div>
		</div>
		<div class="tree_bottom"></div>
		</div>
			 <table width="100%" border="0" cellpadding="0" cellspacing="3"  class="emailfriendtable">
					 <? if($fetch_arr_admin['product_freedelivery_content']!='')
					 {
					 ?>
					 <tr>
					 <td>
					 <table border="0" cellspacing="0" cellpadding="0" width="100%" class="bottom_cont_table_price">
						<tr>
						<td class="price_bottcntnt">
						<? echo $fetch_arr_admin['product_freedelivery_content']?>
						 </td>
						</tr>
						</table>
					    </td>
					 </tr>
					<?php
					}
					?>
				</table>

		<?php	
		}
		
	};	
?>