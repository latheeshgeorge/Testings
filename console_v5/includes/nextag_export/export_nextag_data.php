<? 
	/*#################################################################
	# Script Name 	: export_nextag_data.php
	# Description 	: Page for exporting the data for nextag
	# Coded by 		: Sny
	# Created on	: 04-Jan-2012
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
	$help_msg = '<br>This section allows to download the data in CSV format which can be submitted in Nextag. <br><br>Please click on the following button to download the file.<br><br>';
?>
<form action="export_nextag_data.php" method="post" name="frm_promo">
<table width="100%" border="0" cellspacing="0" cellpadding="0" height="100%">
<tr>
<td valign="top">
	<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
	<td align="left" class="treemenutd"><div class="treemenutd_div"><span>Nextag Data Exporter</span></div></td>
	</tr>
	<tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" >
	  <?php 
		  Display_Main_Help_msg($help_arr,$help_msg);
	  ?>
	 </td>
	</tr>
	<tr>
	<td></td>
	</tr>
	</table>
				 		<div class="editarea_div">

	<table width="100%" border="0" cellspacing="1" cellpadding="1">
      
      <tr>
        <td align="center" valign="top"><input type="submit" name="nextag_export_submit" value="Click to Download the Data file" class="red" /></td>
        </tr>
    </table>
    </div>
    </td>
</tr>
</table>
</form>
