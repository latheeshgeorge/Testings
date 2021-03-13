<?php
/*#################################################################
# Script Name 	: header.php
# Description 	: Admin side header
# Coded by 		: Sny
# Created on	: 31-May-2007
# Modified by	: 
# Modified On	: 
#################################################################*/
	
$conct_header='Welcome to Bshop v4.0 Super Admin Area!';
?>
<table width="100%"  border="0" align="left" cellpadding="0" cellspacing="0">
<tr>
        <td width="57%" align="left" valign="top" class="toptd"><img src="images/logo.gif" /></td>
		<td width="43%" align="right" valign="bottom" class="toptd">&nbsp;<a href="home.php?request=logout"><img src="images/logoot.gif" width="64" height="19" border="0"/>&nbsp; </a></td>
</tr>
  <tr style="font-size: 14px; font-weight: bold;">
    <td align="center" colspan="2"><span class="fontredheading">&nbsp;<?=$conct_header;?></span></td>
  </tr>
</table>