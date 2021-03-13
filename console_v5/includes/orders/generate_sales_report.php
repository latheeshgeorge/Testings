<?php
	
	/*#################################################################
	# Script Name 	: generate_sales_report.php
	# Description 	: file to fetch the criteria for sales report
	# Coded by 		: Sny
	# Created on	: 09-May-2016
	# Modified by	: Sny
	# Modified On	: 09-May-2016
	#################################################################*/
	
	//#Define constants for this page
	$table_name			= 'orders';
	$page_type			= 'Orders';
	$help_msg 			= 'You will be able to download the sales report for a given period using this feature. The report includes the actual order total, total tax value and also the order total without the tax value.';
	
	 $fdate = date("d-m-Y",mktime(0, 0, 0, date("m")-1  , 1, date("Y")));
	 $tdate = date("d-m-Y",strtotime("last day of previous month"));
?>
<script type = "text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script type="text/javascript">
jQuery.noConflict();
</script>
<form method="post" name="frm_orders" class="frmcls" action="do_generate_sales_report.php">
<table border="0" cellpadding="0" cellspacing="0" class="maintable">
     <tr>
      <td class="treemenutd" align="left" valign="middle"><div class="treemenutd_div"><a href="http://<?php echo $ecom_hostname?>/console_v5/home.php?request=orders">List Orders </a><span>Generate Sales Report</span></div></td>
    </tr>
	<tr>
	  <td align="left" valign="middle" class="helpmsgtd_main">
	  <?php 
		  Display_Main_Help_msg($help_arr,$help_msg);
	  ?>
	  </td>
	</tr>
	
	<tr>
	<td class='listeditd'>
		<br/>
	<table border="0" cellpadding="0" cellspacing="0" width="50%">
	<tr>
	<td align="left" colspan="7">
		Please select the date range
	</td>
	</tr>
	<tr>
	<td align="left" colspan="7">
		&nbsp;
	</td>
	</tr>
	<tr>
	<td align="left">&nbsp;</td>	
	<td align="left" width="18%"><input name="ord_fromdate" id="ord_fromdate" class="textfeild hasDatepicker" type="text" size="12" value="<?php echo $fdate;?>" /></td>
	<td width="6%" align="left"><a href="javascript:show_calendar('frm_orders.ord_fromdate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a></td>
	<td width="6%">and</td>
	<td width="18%" align="left"><input name="ord_todate" class="textfeild hasDatepicker" id="ord_todate" type="text" size="12" value="<?php echo $tdate;?>" /></td>
	<td width="13%" align="left"><a href="javascript:show_calendar('frm_orders.ord_todate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a></td>
	<td align="left"><input type="submit" value="Download" class="red"/></td>
	</tr>	
	<tr>
	<td align="left" colspan="7">
		&nbsp;
	</td>
	</tr>
	</table>	
	</td>
	</tr>
</table>	
</form>

<script type="text/javascript">
	
$(function() {
	$( "#ord_fromdate" ).datepicker({ dateFormat: "dd-mm-yy", changeMonth: true,changeYear: true, numberOfMonths: 1,showButtonPanel: false });
	$( "#ord_todate" ).datepicker({ dateFormat: "dd-mm-yy", changeMonth: true,changeYear: true, numberOfMonths: 1,showButtonPanel: false });
});
</script>
