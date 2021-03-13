<?php
	/*#################################################################
	# Script Name 	: list_pricedisplay.php
	# Description 	: Page for listing Product Vendors
	# Coded by 		: SKR
	# Created on	: 22-June-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
//Define constants for this page
$table_name='delivery_methods';
$page_type='Delivery Charges';
$help_msg = '';
$sql="SELECT * FROM $table_name where deliverymethod_id=".$_REQUEST['deliveryid'];
$res= $db->query($sql);
$row=$db->fetch_array($res);
//$row = $db->fetch_array($res);					

?>
<form name="frmlistDelivery" action="home.php?request=delivery_settings" method="post" >	
<input type="hidden" name="fpurpose" value="delivery_settings" />
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=delivery_settings"> Delivery Charges</a><span> '<? echo $row['deliverymethod_name'];?>'</span></div> 
</td>
        </tr>
       <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="4">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
        <tr>
          <td colspan="4" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
          </tr>
		   <tr>
          <td colspan="4" align="left" valign="middle" class="sorttd" ><b>Delivery Method:</b></td>
         
        </tr>
		<tr>
		<td colspan="4" align="left" valign="middle" class="sorttd" >
		<table border="0" cellspacing="0" cellpadding="4">
	<tr>
	  <td align="center" width="161"><b>Option</b></td>
      <td align="center" width="92"><b>Charges</b></td>
	</tr>
  <tr>
  <td align="right" width="280"> More than previous but less than 
   <select name="del_optionbig[]" class="input">
    <option value=''></option>
	<? for($i=1 ;$i<=1000; $i++){?>
	<option value="0"><? echo $i; ?></option>
	<? }?>
	</select>
	&nbsp;.&nbsp;
	<select name="del_optionsmall[]" class="input">
		<option value=''></option>
		<? for($i=0 ;$i<100; $i++){?>
		<option value="00"><? if($i<10)echo '0'.$i; else echo $i;?></option>
		<? }?>
	</select>
	Kg
      </td>
      <td align="center" width="93"> 
	<input name="price[]" class="input" type="text" size="5">
      </td>
    </tr>
    <tr>
      <td align="right" width="280">More than previous but less than 
        <select name="del_optionbig[]" class="input">
			<option value=''></option>
				<? for($i=1 ;$i<=1000; $i++){?>
			<option value="0"><? echo $i; ?></option>&nbsp;.&nbsp;
				<? }?>
		</select>
	&nbsp;.&nbsp;
		<select name="del_optionsmall[]" class="input">
			<option value=''></option>
			<? for($i=0 ;$i<100; $i++){?>
		<option value="00"><? if($i<10)echo '0'.$i; else echo $i;?></option>
		<? }?>
		</select>
		Kg
      </td>
      <td align="center" width="93"> 
		<input name="price[]" class="input" type="text" size="5">
      </td>
  	</tr>
    <tr>
      <td align="right" width="280">More than previous but less than
        <select name="del_optionbig[]" class="input">
			<option value=''></option>
			<? for($i=1 ;$i<=1000; $i++){?>
			<option value="0"><? echo $i; ?></option>&nbsp;.&nbsp;
				<? }?>
		</select>

	&nbsp;.&nbsp;
	<select name="del_optionsmall[]" class="input">
		<option value=''></option>
		<? for($i=0 ;$i<100; $i++){?>
		<option value="00"><? if($i<10)echo '0'.$i; else echo $i;?></option>
		<? }?>
	</select>
	Kg
      </td>
      <td align="center" width="93"> 
	<input name="price[]" class="input" type="text" size="5">
      </td>
  	</tr>
 	 <tr id="largebuttonlink">
		<td colspan="2" align="right">&nbsp;<input type="button" value="Submit Rates" class="bigsubmit" onClick="formhandler('deliveryData')"></td>
		<td colspan="2" align="right">&nbsp;<input type="button" value="Submit and enter more rates" class="bigsubmit" onClick="formhandler('deliveryData_more')"></td>
	</tr>
</table>
		</td>
		</tr>	 
		
		<tr>
		  <td colspan="4" align="center" valign="middle" class="sorttd" >
		   <input name="Submit" type="submit" class="red" value="Save" />			</td>
		</tr>
		
		   
      </table>
</form>
