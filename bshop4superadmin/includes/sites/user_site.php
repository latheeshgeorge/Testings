<?php
#################################################################
# Script Name 	: user_site.php
# Description 	: Page for Listing Site Users
# Coded by 		: SKR
# Created on	: 05-June-2007
# Modified by	: 
# Modified On	: 
#################################################################
#Define constants for this page
$page_type = 'Users';
$help_msg = 'This section shows the console users for this Site.';

$sql = "SELECT user_email_9568,user_pwd_5124,default_user,user_active FROM sites_users_7584 WHERE sites_site_id=".$_REQUEST['site_id'];
$res = $db->query($sql);
?>


<table width="100%" border="0" cellpadding="0" cellspacing="1" class="maininnertable">
      <tr>
        <td align="left" class="menutabletoptd">&nbsp;&nbsp;<strong>List <?=$page_type?></strong></td>
      </tr>
      
      <tr>
        <td class="maininnertabletd2">
			<?=$help_msg?>
		</td>
      </tr>
	  <tr>
        <td class="maininnertabletd2">
		<table width="100%"  border="0" cellpadding="4" cellspacing="1" class="">
			<tr align="left">
			  <td colspan="3" class="redtext"><div align="left">* <span>Default User </span></div></td>
			</tr>
		</table>
		<?php
		while($row = $db->fetch_array($res)) {
		?>
			<table width="100%"  border="0" cellpadding="4" cellspacing="1" class="">
				<tr>
				  <td width="22%" align="right" class="fontblacknormal">User Name<?php if($row['default_user'] == 1) echo '<span class="redtext">*</span>';?></td>
				  <td width="1%" align="center">:</td>
				  <td width="77%" align="left"><?=$row['user_email_9568']?></td>
				</tr>
				<tr>
				  <td width="22%" align="right" class="fontblacknormal">Password</td>
				  <td width="1%" align="center">:</td>
				  <td width="77%" align="left"><?=base64_decode($row['user_pwd_5124'])?></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Currently Active </td>
				  <td align="center">:</td>
				  <td align="left"><?php echo ($row['user_active']==1)?'Yes':'No'?></td>
			  </tr>
				<tr>
				  <td colspan="3" align="right" class="maininnertabletd2">&nbsp;</td>
				</tr>
			</table>
		<?php
		}
		?>
		</td>
      </tr>
    </table>