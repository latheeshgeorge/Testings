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
$help_msg = get_help_messages('EDIT_CURENCY_MESS1');
if(is_array($_REQUEST['currency_id'])){
	list($currency_id)=$_REQUEST['currency_id'];
}else{
$currency_id = $_REQUEST['currency_id'];
}

// get the id of default currency for current site
$sql_def = "SELECT currency_id FROM general_settings_site_currency WHERE sites_site_id=$ecom_siteid AND curr_default = 1";
$ret_def = $db->query($sql_def);
if($db->num_rows($ret_def))
{
	$row_def  	= $db->fetch_array($ret_def);
	$def_id		= $row_def['currency_id'];
}
// check whether currency rates to be picked automatically or not
$pick_automatically = get_general_settings('pick_currency_rate_automatically');
 
 //sql for selecting the details for the settings option
$sql_settings_currencies 	= "SELECT currency_id,curr_name,curr_sign,curr_sign_char,curr_code,curr_rate,curr_default,
									  curr_numeric_code,curr_margin  
									  		FROM general_settings_site_currency 
												WHERE sites_site_id=$ecom_siteid AND currency_id = $currency_id ";
$res_settings_currencies  = $db->query($sql_settings_currencies );
if($db->num_rows($res_settings_currencies)==0) { echo " <font color='red'> You Are Not Authorised  </a>"; exit; }
$settings_currencies 		= $db->fetch_array($res_settings_currencies);




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
		 if(document.frmEditCurrency.curr_rate.value<0)
		{
			alert('Enter positive value for currency rate');
			document.frmEditCurrency.curr_rate.focus();
			return false;
		}
	}
	 if(document.frmEditCurrency.curr_margin.value<0)
		{
			alert('Enter positive value for margin rate');
			document.frmEditCurrency.curr_margin.focus();
			return false;
		}	
		show_processing();
		return true;
	} else {
		return false;
	}
}
</script>
<form name='frmEditCurrency' action='home.php?request=general_settings_currency' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=general_settings_currency&currency_name=<?=$_REQUEST['currency_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>">List Currencies</a><span> Edit Currencies</span></div></td>
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
          <td colspan="2" align="center" valign="middle">
		  <div class="editarea_div" >
		  <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
          <td width="21%" align="left" valign="middle" class="tdcolorgray" >Currency Name <span class="redtext">*</span> </td>
          <td width="79%" align="left" valign="middle" class="tdcolorgray"><input  type="text" name="curr_name" id="curr_name" value="<?=$settings_currencies['curr_name']?>" /> </td>
        </tr>
        <?php /*?> <tr>
           <td align="left" valign="middle" class="tdcolorgray" >Currency Sign <span class="redtext">*</span> </td>
           <td align="left" valign="middle" class="tdcolorgray"><input  type="text" name="curr_sign" id="curr_sign" value="<?=$settings_currencies['curr_sign']?>" /></td>
         </tr><?php */?>
         <tr>
           <td align="left" valign="middle" class="tdcolorgray" >Currency Symbol <span class="redtext">*</span> </td>
           <td align="left" valign="middle" class="tdcolorgray"><input  type="text" name="curr_sign_char" id="curr_sign_char" value="<?=$settings_currencies['curr_sign_char']?>" /> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CURRENCY_CURRENCY_CHARACTER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
         </tr>
         <tr>
           <td align="left" valign="middle" class="tdcolorgray" >Currency Code <span class="redtext">*</span> </td>
           <td align="left" valign="middle" class="tdcolorgray"><input  type="text" name="curr_code" id="curr_code" value="<?=$settings_currencies['curr_code']?>" /> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CURRENCY_CURRENCY_CODE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
         </tr>
		 <tr>
           <td align="left" valign="middle" class="tdcolorgray" >Currency Rate <span class="redtext">*</span> </td>
           <td align="left" valign="middle" class="tdcolorgray">
		   <?php
		   	if($pick_automatically['pick_currency_rate_automatically']==0)
			{
				if($def_id != $currency_id)
				{
		   ?>
			   <input  type="text" name="curr_rate" id="curr_rate" value="<?=$settings_currencies['curr_rate']?>" />
			   <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CURRENCY_CURRENCY_RATE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> 
       <?php
	   			}
				else
				{
			?>
			<input  type="hidden" name="curr_rate" id="curr_rate" value="<?=$settings_currencies['curr_rate']?>" />
			<?php	
					echo $settings_currencies['curr_rate'];
				}
	   		}
			else
			{
				echo $settings_currencies['curr_rate'];
				?>
			 <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CURRENCY_CURRENCY_RATE_AUTO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> 

				<?
			}	
	   		?>			</td>
         </tr>
		  <?php
		   	if($settings_currencies['currency_id']!=$def_id)
			{
		   ?>
		 <tr>
           <td align="left" valign="middle" class="tdcolorgray" >Margin for Rate</td>
		   <td align="left" valign="middle" class="tdcolorgray">
		   	<input name="curr_margin" type="text" id="curr_margin" value="<?php echo $settings_currencies['curr_margin']?>" />
	       <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CURRENCY_CURRENCY_MARGIN')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
    </tr>
	<?php
		}
		else
		{
		?>
			<input name="curr_margin" type="hidden" id="curr_margin" value="<?php echo $settings_currencies['curr_margin']?>" />
		<?php
		}
		
	    /*?>  <tr>
          <td align="left" valign="middle" class="tdcolorgray" >Set as Default   </td>
          <td align="left" valign="middle" class="tdcolorgray">
            <input type="checkbox" name="curr_default" value="1" <?php echo($settings_currencies['curr_default']==1)?'checked':'';?>  />          </td>
         </tr><?php */?>
		 <tr>
           <td align="left" valign="middle" class="tdcolorgray" >Currency Numeric Code<span class="redtext"> *</span> </td>
           <td align="left" valign="middle" class="tdcolorgray"><input  type="text" name="numeric_code" id="numeric_code" value="<?=$settings_currencies['curr_numeric_code']?>" />
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
							<input type="hidden" name="fpurpose" id="fpurpose" value="update_currency" />
							<input name="Submit" type="submit" class="red" value="Update" />
						</td>
					</tr>
					</table>
				</div>
			</td>	
		</tr>
      </table>
</form>	  

