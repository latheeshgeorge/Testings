<?php
	/*#################################################################
	# Script Name 	: add_settings_captions.php
	# Description 	: Page for adding General settings Captions
	# Coded by 		: ANU
	# Created on	: 14-June-2007
	# Modified by	: LSH 
	# Modified On	: 02-sep-2008
	#################################################################*/
#Define constants for this page
$table_name='general_settings_site_currency';
$page_type = 'General Settings Currencies';
$help_msg = get_help_messages('ADD_CURENCY_MESS1');

// check whether currency rates to be picked automatically or not
$pick_automatically = get_general_settings('pick_currency_rate_automatically');


?>	
<script language="javascript" type="text/javascript">
function valform(frm)
{
	var pick_auto = '<?php echo $pick_automatically['pick_currency_rate_automatically']?>';
	if(pick_auto==0)
	{
		fieldRequired = Array('curr_name','curr_sign_char','curr_code','curr_rate','numeric_code');
		fieldDescription = Array('Currency Name','Currency Symbol','Currency Code','Currency Rate','Numeric Code');
	}
	else
	{
		fieldRequired = Array('curr_name','curr_sign_char','curr_code','numeric_code');
		fieldDescription = Array('Currency Name','Currency Symbol','Currency Code','Numeric Code');
	}	
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	if(pick_auto==0)
		fieldNumeric = Array('curr_rate','numeric_code','curr_margin');
	else
		fieldNumeric = Array('numeric_code','curr_margin');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
	if(pick_auto==0)
	{
		 if(document.frmAddCurrency.curr_rate.value<0)
		{
			alert('Enter positive value for currency rate');
			document.frmAddCurrency.curr_rate.focus();
			return false;
		}
	}
	  if(document.frmAddCurrency.curr_margin.value<0)
		{
			alert('Enter positive value for margin rate');
			document.frmAddCurrency.curr_margin.focus();
			return false;
		}	
		show_processing();
		return true;
	} else {
		return false;
	}
}
</script>
<form name='frmAddCurrency' action='home.php?request=general_settings_currency' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=general_settings_currency&currency_name=<?=$_REQUEST['currency_name']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>">List Currencies</a><span> Add Currencies</span></td>
        </tr>
        <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="2">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
		
		<?php if($alert) {?>
        <tr>
          <td colspan="2" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
          </tr><? }?>
        <tr>
          <td width="23%" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td width="77%" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
        </tr>
		<tr>
          <td colspan="2" align="center" valign="middle">
		  <div class="editarea_div" >
		  <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
          <td width="20%" align="left" valign="middle" class="tdcolorgray" >Currency Name <span class="redtext">*</span> </td>
          <td width="80%" align="left" valign="middle" class="tdcolorgray"><input  type="text" name="curr_name" id="curr_name" value="<?=$_REQUEST['curr_name']?>" /></td>
        </tr>
        <?php /*?> <tr>
           <td align="left" valign="middle" class="tdcolorgray" >Currency Sign <span class="redtext">*</span> </td>
           <td align="left" valign="middle" class="tdcolorgray"><input  type="text" name="curr_sign" id="curr_sign" value="<?=$_REQUEST['curr_sign']?>" /></td>
         </tr><?php */?>
         <tr>
           <td align="left" valign="middle" class="tdcolorgray" >Currency Symbol <span class="redtext">*</span> </td>
           <td align="left" valign="middle" class="tdcolorgray"><input  type="text" name="curr_sign_char" id="curr_sign_char" value="<?=$_REQUEST['curr_sign_char']?>" />
		   <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CURRENCY_CURRENCY_CHARACTER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
         </tr>
         <tr>
           <td align="left" valign="middle" class="tdcolorgray" >Currency Code <span class="redtext">*</span> </td>
           <td align="left" valign="middle" class="tdcolorgray"><input  type="text" name="curr_code" id="curr_code" value="<?=$_REQUEST['curr_code']?>" />
		   <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CURRENCY_CURRENCY_CODE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> </td>
         </tr>
		  <?php
		   	if($pick_automatically['pick_currency_rate_automatically']==0)
			{
		   ?>
         <tr>
           <td align="left" valign="middle" class="tdcolorgray" >Currency Rate <span class="redtext">*</span> </td>
           <td align="left" valign="middle" class="tdcolorgray">
		  
		   		<input  type="text" name="curr_rate" id="curr_rate" value="<?=$_REQUEST['curr_rate']?>" />
		   <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CURRENCY_CURRENCY_RATE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
   		   </td>
         </tr>
		 <?php
		   }
 		?>
         <tr>
           <td align="left" valign="middle" class="tdcolorgray" >Margin for Rate </td>
           <td align="left" valign="middle" class="tdcolorgray"><input name="curr_margin" type="text" id="curr_margin" value="<?php echo $_REQUEST['curr_margin']?>" />
           <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CURRENCY_CURRENCY_MARGIN')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
         </tr>
        <?php /*?> <tr>
          <td align="left" valign="middle" class="tdcolorgray" >Set as Default   </td>
          <td align="left" valign="middle" class="tdcolorgray">
            <input type="checkbox" name="curr_default" value="1" <?php echo($_REQUEST['curr_default']==1)?'checked':'';?>  />          </td>
         </tr><?php */?>
		  <tr>
           <td align="left" valign="middle" class="tdcolorgray" >Currency Numeric Code <span class="redtext">*</span> </td>
           <td align="left" valign="middle" class="tdcolorgray"><input  type="text" name="numeric_code" id="numeric_code" value="<?=$_REQUEST['numeric_code']?>" />
		   <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CURRENCY_CURRENCY_NUMERICCODE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
         </tr>
		 </table>
		 </div>
		 </td>
		 </tr>
		 
		<tr>
			<td colspan="2" align="center" valign="middle">
				<div class="editarea_div" >
					<table width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td align="right" valign="middle" class="tdcolorgray">				
							<input type="hidden" name="currency_name" id="currency_name" value="<?=$_REQUEST['currency_name']?>" />
							<input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
							<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
							<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
							<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
							<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
							<input type="hidden" name="currency_id" id="currency_id" value="<?=$currency_id?>" />
							<input type="hidden" name="fpurpose" id="fpurpose" value="insert_currency" />
							<input name="Submit" type="submit" class="red" value="Add" />
						</td>
					</tr>
					</table>
				</div>
			</td>
		</tr>
      </table>
</form>	  

