<?php
/*############################################################################
	# Script Name 	: commonMessageHtml.php
	# Description 	: Page which holds the display logic for middle adverts
	# Coded by 		: Sobin
	# Created on	: 23-Oct-2014
	# Modified by	: 
	# Modified On	: 
	##########################################################################*/
	class commonmessage_Html
	{
		// Defining function to show the featured property
		function Newsletter_Message()
		{
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$Captions_arr;
			$Captions_arr['COMMON'] = getCaptions('COMMON');
			
			$sql_success_msg	=	"SELECT * FROM static_pages WHERE title = 'newslettersuccess' AND sites_site_id = ".$ecom_siteid;
			//echo $sql_success_msg;
			$ret_success_msg	=	$db->query($sql_success_msg);
			if ($db->num_rows($ret_success_msg))
			{
				$row_success_msg = $db->fetch_array($ret_success_msg);
			}
			
?>			<div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?=stripslash_normal($Captions_arr['COMMON']['SIGNUP_MESSAGE'])?></div>
			<table width="100%" border="0" cellspacing="4" cellpadding="0" class="regi_table">
			<tr>
			<td width="7%" align="left" valign="middle" class="message_header" > 
			<?=stripslash_normal($Captions_arr['COMMON']['SIGNUP_MESSAGE'])?></td>
			</tr>
			<tr>
			<td align="left" valign="middle" class="message"><?php echo $row_success_msg['content']; ?></td>
			</tr>
			</table>
<?php
		}
		function Createaccount_Message()
		{
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$Captions_arr;
			$Captions_arr['COMMON'] = getCaptions('COMMON');
			
			$sql_success_msg	=	"SELECT * FROM static_pages WHERE title = 'createaccountsuccess' AND sites_site_id = ".$ecom_siteid;
			//echo $sql_success_msg;
			$ret_success_msg	=	$db->query($sql_success_msg);
			if ($db->num_rows($ret_success_msg))
			{
				$row_success_msg = $db->fetch_array($ret_success_msg);
			}
			
?>			<div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?=stripslash_normal($Captions_arr['COMMON']['ACCOUNT_MESSAGE'])?></div>
			<table width="100%" border="0" cellspacing="4" cellpadding="0" class="regi_table">
			<tr>
			<td width="7%" align="left" valign="middle" class="message_header" > 
			<?=stripslash_normal($Captions_arr['COMMON']['ACCOUNT_MESSAGE'])?></td>
			</tr>
			<tr>
			<td align="left" valign="middle" class="message"><?php echo $row_success_msg['content']; ?></td>
			</tr>
			</table>
<?php
		}
	}
?>