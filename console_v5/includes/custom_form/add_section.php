<?php
	/*#################################################################
	# Script Name 	: add_section.php
	# Description 	: Page for adding Dynamic Form Section
	# Coded by 		: SKR
	# Created on	: 18-Aug-2007
	# Modified by	: LG
	# Modified On	: 29-Jan-2008
	#################################################################*/
#Define constants for this page
$page_type = 'Section';
$help_msg = get_help_messages('ADD_CHECKOUT_FORM_MESS1');

?>	
<script language="javascript" type="text/javascript">
function valform(frm)
{
	fieldRequired = Array('section_name');
	fieldDescription = Array('Section Name');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		show_processing();
		return true;
	} else {
		return false;
	}
}
</script>
<form name='frmAddSection' action='home.php?request=customform&form_type=<?php echo $_REQUEST['form_type']?>' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=customform&form_type=<?php echo $_REQUEST['form_type']?>"><? echo ucwords($_REQUEST['form_type'])?> Form</a><span> Add Section</span></div></td>
        </tr>
       <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="4">
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
          <td colspan="4" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
        </tr>
		<?
		}
		?>
		<tr>
          <td colspan="4" align="center" valign="middle">
		  <div class="editarea_div" >
		  <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
          <td width="13%" align="left" valign="middle" class="tdcolorgray" >Section Name <span class="redtext">*</span> </td>
          <td colspan="3" align="left" valign="middle" class="tdcolorgray">
		  <input name="section_name" type="text" class="input" size="80"  value="<?=$_REQUEST['section_name']?>" />		  </td>
        </tr>
		 <tr>
          <td width="13%" align="left" valign="middle" class="tdcolorgray" >Position</td>
          <td width="14%" align="left" valign="middle" class="tdcolorgray">
		  <select name="position">
		  <option value="Top" <? if($_REQUEST['position']=='Top') echo "selected";?>>Top</option>
		  <option value="TopInStatic" <? if($row_section['position']=='TopInStatic') echo "selected";?>>Top With in the Static Section</option>
		  <option value="BottomInStatic" <? if($row_section['position']=='BottomInStatic') echo "selected";?>>Bottom With in the Static Section</option>
		  <option value="Bottom" <? if($_REQUEST['position']=='Bottom') echo "selected";?>>Bottom</option>
		  </select>		<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_CHECKOUT_FORM_POS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>  </td>
          <td width="21%" align="left" valign="middle" class="tdcolorgray"><div align="right">Sort Order</div></td>
	      <td width="52%" align="left" valign="middle" class="tdcolorgray"><input class="input" type="text" name="sort_no" size="3"  value="<?=$_REQUEST['sort_no']?>" />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_CHECKOUT_FORM_ORDER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	    </tr>
		<tr>
          <td align="left" valign="middle" class="tdcolorgray" >Active</td>
          <td align="left" valign="middle" class="tdcolorgray"><input type="radio" name="activate" value="1"  <? if($_REQUEST['activate']==1 || $_REQUEST['activate'] ==''  ) echo "checked";?> />Yes<input type="radio" name="activate" value="0" <? if($_REQUEST['activate']==0 && $_REQUEST['activate'] !=''  ) echo "checked";?>/>No
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_CHECKOUT_FORM_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          <td align="left" valign="middle" class="tdcolorgray">
		    <div align="right">
	 	      <?php	
		  	if( $_REQUEST['form_type']!='register')
			{
		  ?>	
		 	 Only for Specific Products
		     <?php
		  	}
		  ?>
		    </div></td>
          <td align="left" valign="middle" class="tdcolorgray">
		   <?php	
		  	if($_REQUEST['form_type']!='register')
			{
		  ?>
			  <input type="radio" name="section_to_specific_products" value="1" <? if($_REQUEST['section_to_specific_products']==1) echo "checked";?> />
				Yes
				  &nbsp;
				  <input type="radio" name="section_to_specific_products" value="0" <? if($_REQUEST['section_to_specific_products']==0) echo "checked";?> />
			  No	
			  	  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_CHECKOUT_FORM_SPECPROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>

		  <?php
		  }
		  ?>
		</tr>
        <tr>
          <td align="left" valign="middle" class="tdcolorgray" >Hide Heading </td>
          <td colspan="3" align="left" valign="middle" class="tdcolorgray"><input type="radio" name="hide_heading" value="1"  <? if($_REQUEST['hide_heading']==1 || $_REQUEST['hide_heading'] ==''  ) echo "checked";?> />
            Yes
              <input type="radio" name="hide_heading" value="0" <? if($_REQUEST['hide_heading']==0 && $_REQUEST['hide_heading'] !=''  ) echo "checked";?>/>
          No <a href="#" onmouseover ="ddrivetip('If the section heading is to be made hidden in site tick this checkbox.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
       <tr>
          <td width="13%" align="left" valign="middle" class="tdcolorgray" >Help Instructions</td>
          <td colspan="3" align="left" valign="middle" class="tdcolorgray">
		  <textarea name="message" cols="60" rows="4"><? echo stripslashes($_REQUEST['message'])?></textarea>		  </td>
    </tr>
		 </table>
		 </div>
		 </td>
		 </tr>
		 
		<tr>
			<td colspan="4" align="right" valign="middle">
				<div class="editarea_div">
					<table width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td align="right" valign="middle" class="tdcolorgray">
							<input type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>" />
							<input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
							<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
							<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
							<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
							<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
							<input type="hidden" name="fpurpose" id="fpurpose" value="insert" />
							<input type="hidden" name="form_type" id="form_type" value="<?=$_REQUEST['form_type']?>" />
							<input name="Submit" type="submit" class="red" value="Submit" />
						</td>
					</tr>
					</table>
				</div>
			</td>
		</tr>
      </table>
</form>	  

