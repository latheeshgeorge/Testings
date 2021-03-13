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
	<div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >>
	<? if($product_id){?> <a href="<?php url_product($product_id,$prod_name,-1)?>"><?=$prod_name?></a> >> <? }?>
	<?=stripslash_normal($Captions_arr['PROD_FREE_DELIVERY']['PROD_FREE_DELIVERY_TREEMENU_TITLE'])?></div>
	  <table border="0" cellpadding="0" cellspacing="0" class="staticpagetable">
		<tr>
		<td  class="message_header"><?=stripslash_normal($Captions_arr['PROD_FREE_DELIVERY']['PROD_FREE_DELIVERY_TREEMENU_TITLE'])?></td></tr>
		
		<? if($fetch_arr_admin['product_freedelivery_content']!='')
		{
		?>
		 <tr>
		<td valign="top" class="staticpagecontent">
		<? echo stripslashes($fetch_arr_admin['product_freedelivery_content'])?>
		</td>
		</tr>
		<? }	
		else
		{
		?>
		<tr>
		<td class="staticpagecontent">
		<?php
		echo stripslash_normal($Captions_arr['PROD_FREE_DELIVERY']['PROD_FREE_DELIVERY_NOCONTENT']);
		?>
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
