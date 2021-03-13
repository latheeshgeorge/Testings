<? 
	/*#################################################################
	# Script Name 	: export_for_ebay.php
	# Description 	: Page for exporting the files required to be submitted to ebay
	# Coded by 		: Sny
	# Created on	: 19-Feb-2013
	# Modified by	: 
	# Modified On	: 
	#################################################################*/

	//Define constants for this page
	$page_type = 'Ebay Export';
	$help_msg = get_help_messages('LIST_EBAY_MESS');
	
?>
<script type='text/javascript'>
function handle_select_allcategory()
{
	obj 	= document.getElementById('sel_category_id[]');
	var sel = '';
	if (document.getElementById('sel_category_all').checked)
		sel = true;
	else
		sel = false;
	for (i=obj.options.length-1;i>=0;i--)
	{
		obj.options[i].selected = sel;
	}
}
/*
function validate_curform()
{
	/* check whether atleast one item selected for category */
	/*obj 		= document.getElementById('sel_category_id[]');
	atleastone 	= false;	
	var sel = '';
	for (i=0;i<obj.options.length;i++)
	{
		if(obj.options[i].selected==true)
		{
			atleastone = true;
		}	
	}
	if(atleastone)
	{
		return true;
	}
	else
	{
		alert('Please select atleast one category of products to be included in the export');
		return false;
	}
}*/
function valform(frm)
{
    fieldRequired = Array('txt_city');
	fieldDescription = Array('City');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();	
	
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		//show_processing();
		return true;
	} else {
		return false;
	}	
}	
</script>
<form action="export_for_ebay.php" method="post" name="frm_promo" onsubmit="return valform(this)">
<table width="100%" border="0" cellspacing="0" cellpadding="0" height="100%">
<tr>
<td valign="top">
	<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
	<td align="left" class="treemenutd"><div class="treemenutd_div"><span>Ebay Exporter</span></div></td>
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
        <td width="63%" align="left" valign="top" class="advanced_seperator">
		<div class="advanced_seperator_div_2">
		  <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td colspan="2" align="left" class="listingtableheader" >The following settings will be used to fill the respective columns for the products in the exported file. </td>
            </tr>
            <tr>
              <td width="22%" align="left" class="listingtablestyleA" ><strong>Site </strong></td>
              <td width="78%" align="left" class="listingtablestyleA" >
			  	<select name="cbo_site" id="cbo_site">
                 <option value="0">United States</option>
				 <option value="2">Canada</option>
				 <option value="3" selected="selected">United kingdom</option>
				 <option value="15">Australia</option>
				 <option value="16">Austria</option>
				 <option value="23">Belgium (French)</option>
				 <option value="71">France</option>
				 <option value="77">Germany</option>
				 <option value="100">eBay Motors</option>
				 <option value="101">Italy</option>
				 <option value="123">Belgium (Dutch)</option>
				 <option value="146">Netherlands</option>
				 <option value="186">Spain</option>
				 <option value="193">Switzerland</option>
				 <option value="196">Taiwan</option>
				 <option value="223">China</option>
				 <option value="203">India</option>
                </select>
				</td>
            </tr>
            <tr>
              <td align="left" class="listingtablestyleB" ><strong>City / State <span class='redtext'>*</span></strong></td>
              <td align="left" class="listingtablestyleB" ><input name="txt_city" type="text" id="txt_city" size="35" maxlength="45" />&nbsp;<a href="#" onmouseover ="ddrivetip('Indicates the geographical location of the item. <br><br>Not applicable for the eBay China site.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
               
                <br />Maximum length: 45 characters</td>
            </tr>
            <tr>
              <td align="left" class="listingtablestyleA" ><strong>Country</strong></td>
              <td align="left" class="listingtablestyleA" ><?php
			  	$sql_country = "SELECT country_id,country_name,country_code 
			  					FROM 
									common_country 
								ORDER BY 
									country_name 
								ASC";
				$ret_country = $db->query($sql_country);			 					
			  ?>
                  <select name="cbo_country" id="cbo_country">
                    <?php
			  if($db->num_rows($ret_country))
			  {
			  	while ($row_country = $db->fetch_array($ret_country))
				{
					if($row_country['country_code']=='GB')
						$sel = 'selected="selected"';
					else
						$sel = '';
			  ?>
                    <option value="<?php echo $row_country['country_code']?>" <?php echo $sel?>><?php echo stripslashes($row_country['country_name'])?></option>
                    <?php
			  	}
			  }
			  ?>
                  </select>
				  &nbsp;<a href="#" onmouseover ="ddrivetip('The country in which the seller is located')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
                 </td>
            </tr>

            <tr>
              <td align="left" class="listingtablestyleB" ><strong>Duration</strong></td>
              <td align="left" class="listingtablestyleB" >
			  <select name="cbo_duration" id="cbo_duration">
			  <option value="30" selected="selected">30</option>
			  <option value="30">60</option>
			  <option value="30">90</option>
			  <option value="30">120</option>
              </select>&nbsp;<a href="#" onmouseover ="ddrivetip('Number of days the listing will be active.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> </td>
            </tr>
            <tr>
              <td align="left" class="listingtablestyleA" ><strong>Hit Counter Style </strong></td>
              <td align="left" class="listingtablestyleA" >
			  <select name="cbo_counter" id="cbo_counter">
                  <option value="0" selected="selected">No hit counter</option>
                  <option value="1">Honesty style</option>
                  <option value="2">Green LED</option>
                  <option value="3">Hidden</option>
                </select>              </td>
            </tr>
           <?php /*?> <tr>
              <td align="left" class="listingtablestyleB" ><strong>Payment Instruction </strong></td>
              <td align="left" class="listingtablestyleB" ><textarea name="txt_instruction" id="txt_instruction" cols="45" rows="3"></textarea></td>
            </tr><?php */?>
          </table>
		</div></td>
		<td width="37%" align="left" valign="top" class="advanced_seperator">
		<div class="advanced_seperator_div_2">
		<a href="home.php?request=ebay_category">Click Here To Map Ebay Categories</a>
		</div>	</td>
	</tr>	
	</table>
    <table width="100%" border="0" cellspacing="1" cellpadding="2">
          <tr>
            <td colspan="4" align="left" class="tdcolorgray" >&nbsp;</td>
          </tr>  
          
          <tr >
            <td colspan='4' align="center" class="tdcolorgray" ><span style="padding-top:8px">
              <input type="submit" name="generate_file" value="Generate File" class="red" />
              <a href="#" onmouseover ="ddrivetip('<? echo 'Click on Generate File button to download the file to be uploaded to Ebay';?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> <br />
            </span></td>
          <tr >
            <td colspan='4' align="center" class="tdcolorgray" ><span style="padding-top:8px">* Please check the prices at the end to make sure they are correct.  If they are not please contact support@thewebclinic.co.uk </span></td>
          </table>
    </div>
      </td>
    </tr>
  </table>
</form>
