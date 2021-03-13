<?php
/*#################################################################
# Script Name 	: main_general.php
# Description 	: Main page for general settings
# Coded by 		: ANU
# Created on	: 14-June-2007
# Modified by	: Sny
# Modified On	: 1-Feb-2007 
#################################################################*/
// Get the payment method id for protx and protx_vsp
//$help_msg = 'This section allows to make various general settings. Click on the sections to change the settings.';
$sql="SELECT paymethod_id FROM payment_methods WHERE paymethod_key IN('PROTX','PROTX_VSP')";
$res_sql=$db->query($sql); 
if ($db->num_rows($res_sql))
{
	while ($row_sql = $db->fetch_array($res_sql))
	{
		$row_arr[] = $row_sql['paymethod_id'];
	}
	$str_id = implode(',',$row_arr);
	// Check whether protx or protx vsp is set for current site
	$sqlcheck ="SELECT payment_methods_paymethod_id  
								FROM 
									payment_methods_forsites  
								WHERE 
									sites_site_id =".$ecom_siteid." 
									AND payment_methods_paymethod_id IN($str_id)   
								LIMIT 
								1";
	$res_sqlcheck=$db->query($sqlcheck);
}	
?>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
   <tr>
      <td colspan="6" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="javascript:void(0);">Website settings</a><span> General Settings</span></div> </td>
  </tr>
  <?php /*?><tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="6">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr><?php */?>
		
     <tr>
       <td colspan="6" class="tdcolorgray">&nbsp;</td>
     </tr>
	 <tr>
       <td colspan="6" class="tdcolorgray" align="left" valign="top">
	   <div class="editarea_div">
		<table cellpadding="0" cellpadding="0" width="100%">
      <tr>
        <td width="11%" align="right">&nbsp;<a href="home.php?request=general_settings&amp;fpurpose=captions" class="edittextlink"><img src="images/settings.gif" alt="" border="0" /></a></td>
        <td width="26%" align="left">&nbsp;<a href="home.php?request=general_settings&amp;fpurpose=settings_default" class="edittextlink">Main Shop Settings</a></td>
      <!--  <td width="4%" align="right"><a href="home.php?request=general_settings&amp;fpurpose=captions" class="edittextlink"><img src="images/settings.gif" alt="" border="0" /></a> </td>-->
     <!--   <td width="24%" align="left">&nbsp;<a href="home.php?request=general_settings&amp;fpurpose=captions" class="edittextlink">Headings and Various Captions</a> </td>-->
    <td width="6%" align="right">&nbsp;<a href="home.php?request=general_settings_country" class="edittextlink"><img src="images/settings.gif" alt="" border="0" /></a></td>
      <td width="24%" align="left">&nbsp;<a href="home.php?request=general_settings_country" class="edittextlink">Country</a></td> 
	  <td width="5%" align="right">&nbsp;<a href="home.php?request=general_settings&amp;fpurpose=bonus_rate" class="edittextlink"><img src="images/settings.gif" alt="" border="0" /></a></td>
	  <td width="28%" align="left">&nbsp;<a href="home.php?request=general_settings&amp;fpurpose=bonus_rate" class="edittextlink">Bonus Points</a></td>
    </tr>
      
    <tr>
      <td align="right">&nbsp;<a href="home.php?request=general_settings&amp;fpurpose=list_order" class="edittextlink"><img src="images/settings.gif" alt="" border="0" /></a></td>
      <td align="left">&nbsp;<a href="home.php?request=general_settings&amp;fpurpose=list_order" class="edittextlink">List Order Settings</a></td>
      <td align="right">&nbsp;<a href="home.php?request=general_settings_state" class="edittextlink"><img src="images/settings.gif" alt="" border="0" /></a></td>
      <td align="left">&nbsp;<a href="home.php?request=general_settings_state" class="edittextlink">State</a></td>
     <?
	 
	//  if($db->num_rows($res_sqlcheck)>0)
	//  {
	  ?>
<!--     <td width="3%" align="right">&nbsp;<a href="home.php?request=general_settings&amp;fpurpose=captions" class="edittextlink"><img src="images/settings.gif" alt="" border="0" /></a></td>
      <td width="19%" align="left">&nbsp;<a href="home.php?request=payment_capture_types" class="edittextlink">Payment capture Types</a></td>
 -->	  <? // }else{
	   ?>
	   <td width="5%" align="right">&nbsp;<a href="home.php?request=general_settings_comptype" class="edittextlink"><img src="images/settings.gif" alt="" border="0" /></a></td>
      <td width="28%" align="left">&nbsp;<a href="home.php?request=general_settings_comptype" class="edittextlink">Customer Registration Company Type</a></td>
	  <? //}?>
	  </tr>
	 <tr>
	   <td align="right">&nbsp;<a href="home.php?request=general_settings_price" class="edittextlink"><img src="images/settings.gif" alt="" border="0" /></a></td>
	   <td align="left">&nbsp;<a href="home.php?request=general_settings_price" class="edittextlink">Price Display Settings</a></td>
	   <td align="right">&nbsp;<a href="home.php?request=general_settings&amp;fpurpose=orderconfirmemail" class="edittextlink"><img src="images/settings.gif" alt="" border="0" /></a></td>
	   <td align="left">&nbsp;<a href="home.php?request=general_settings&amp;fpurpose=orderconfirmemail" class="edittextlink">Order Confirmation Emails</a></td>
	   <td  align="right"></td>
	   <td  align="left"></td>
  </tr>
	 <tr>
	  <!--  <td align="right">&nbsp;<a href="home.php?request=general_settings_currency" class="edittextlink"><img src="images/settings.gif" alt="" border="0" /></a></td>
	   <td align="left">&nbsp;<a href="home.php?request=general_settings_currency" class="edittextlink">Currency</a></td>
	   <td align="right">&nbsp;<a href="home.php?request=site_headers" class="edittextlink"><img src="images/settings.gif" alt="" border="0" /></a></td>
	  <td align="left">&nbsp;<a href="home.php?request=site_headers" class="edittextlink">Site headers</a></td> -->
	   <td  align="left"></td>
	   <td  align="left"></td>
  </tr>
	 <tr>
	  <!-- <td align="right">&nbsp;<a href="home.php?request=general_settings&amp;fpurpose=captions" class="edittextlink"><img src="images/settings.gif" alt="" border="0" /></a></td>
	   <td align="left">&nbsp;<a href="home.php?request=general_settings_tax" class="edittextlink">Tax Settings</a></td> -->
	   <td align="right"><?php /*?>&nbsp;<a href="home.php?request=settings_letter_templates" class="edittextlink"><img src="images/settings.gif" alt="" border="0" /></a><?php */?>
	   </td>
	   <td align="left">
	   <?php /*?>&nbsp;<a href="home.php?request=settings_letter_templates" class="edittextlink">Letter Templates</a><?php */?>
	   </td>
	   <td  align="right"><?php /*?>&nbsp;<a href="home.php?request=settings_static_checkfields" class="edittextlink"><img src="images/settings.gif" alt="" border="0" /></a><?php */?></td>
	   <td  align="left"><?php /*?>&nbsp;<a href="home.php?request=settings_static_checkfields" class="edittextlink">Manage Static Checkoutfields</a><?php */?></td>
  </tr>
	 <tr>
	   <!--<td align="right">&nbsp;<a href="home.php?request=delivery_settings" class="edittextlink"><img src="images/settings.gif" alt="" border="0" /></a></td>
	   <td align="left">&nbsp;<a href="home.php?request=delivery_settings" class="edittextlink">Delivery Settings</a></td> -->
	   <!--<td align="right">&nbsp;<a href="home.php?request=payment_types" class="edittextlink"><img src="images/settings.gif" alt="" border="0" /></a></td>
	   <td align="left">&nbsp;<a href="home.php?request=payment_types" class="edittextlink">Payment Types</a></td> -->
	<!-- <td align="right">&nbsp;<a href="home.php?request=settings_static_checkfields" class="edittextlink"><img src="images/settings.gif" alt="" border="0" /></a></td>
      <td align="left">&nbsp;<a href="home.php?request=settings_static_checkfields" class="edittextlink">Manage Static Checkoutfields</a></td> -->
  </tr>
	 <tr>
	  <!-- <td align="right">&nbsp;<a href="home.php?request=general_settings_Gift_Wrap" class="edittextlink"><img src="images/settings.gif" alt="" border="0" /></a></td>
	   <td align="left">&nbsp;<a href="home.php?request=general_settings_Gift_Wrap" class="edittextlink">Gift Wrap Settings</a></td> -->
	   <td align="right"></td>
	   <td align="left"></td>
	   <td  align="left"></td>
	   <td  align="left"></td>
  </tr>
	 <tr>
      <td align="right"></td>
      <td align="left"></td>
     <!-- <td align="right">&nbsp;<a href="home.php?request=general_settings_state" class="edittextlink"><img src="images/settings.gif" alt="" border="0" /></a></td>
      <td align="left">&nbsp;<a href="home.php?request=general_settings_state" class="edittextlink">State</a></td> -->
      <td  align="left"></td>
      <td  align="left"></td>
	</tr>
	</table>
	</div>
	</td>
	</tr>
</table>


