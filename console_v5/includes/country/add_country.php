<?php
	/*#################################################################
	# Script Name 	: add_country.php
	# Description 	: Page for adding Site Country
	# Coded by 		: SKR
	# Created on	: 15-June-2007
	# Modified by	: LG
	# Modified On	: 28-01-2008
	#################################################################*/
#Define constants for this page
$page_type = 'Country';
$help_msg = get_help_messages('ADD_COUNTRY_MESS1');

?>	
<script language="javascript" type="text/javascript">
function valform(frm)
{
	fieldRequired = Array('country_name','country_code','numeric_code');
	fieldDescription = Array('Country Name','Country Code','Numeric Code');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array('numeric_code');
	fieldSpecChars = Array('country_name');
	fieldCharDesc = Array('Country name');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric,fieldSpecChars,fieldCharDesc)) {
		show_processing();
		return true;
	} else {
		return false;
	}
}
</script>
<form name='frmAddUser' action='home.php?request=general_settings_country' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=general_settings">General Settings</a>  <a href="home.php?request=general_settings_country&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Country</a><span> Add Country</span></div></td>
        </tr>
       <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="2">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
		<?php 
		if($alert)
		{			
		?>
        <tr>
          <td colspan="2" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
        </tr>
		<?
		}
		?>
		<tr>
          <td colspan="2" align="left" valign="top">
		  <div class="editarea_div">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="15%" align="left" valign="middle" class="tdcolorgray" >Country Name <span class="redtext">*</span> </td>
          <td width="85%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="country_name" value="<?php echo $_REQUEST['country_name']?>"  />
		  </td>
        </tr>
          <tr>
          <td width="15%" align="left" valign="middle" class="tdcolorgray" >Country Code<span class="redtext">*</span> </td>
          <td width="85%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="country_code" value="<?php echo $_REQUEST['country_code']?>"  />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_COUNTRY_CODE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
        <tr>
          <td width="15%" align="left" valign="middle" class="tdcolorgray" >Country Numeric Code<span class="redtext">*</span> </td>
          <td width="85%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="numeric_code" value="<?php echo $_REQUEST['numeric_code']?>"  />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_COUNTRY_NUMERIC_CODE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
        <tr>
          <td align="left" valign="middle" class="tdcolorgray" >Active</td>
          <td align="left" valign="middle" class="tdcolorgray"><input type="radio" name="country_hide" value="1" checked/>Yes<input type="radio" name="country_hide" value="0"/>No
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_COUNTRY_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		</table>
		</div>
		</td>
		</tr>
		
		<tr>
			<td colspan="2" align="right" valign="middle">
				<div class="editarea_div">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td align="right" valign="middle" class="tdcolorgray">		
							<input type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>" />
							<input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
							<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
							<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
							<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
							<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
							<input type="hidden" name="fpurpose" id="fpurpose" value="insert" />
							<input name="Submit" type="submit" class="red" value="Submit" />
						</td>
					</tr>
					</table>
				</div>
			</td>
		</tr>
      </table>
</form>	  

