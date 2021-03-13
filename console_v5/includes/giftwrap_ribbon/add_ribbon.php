<?php
	/*#################################################################
	# Script Name 	: add_ribbon.php
	# Description 	: Page for adding Giftwrap Ribbon
	# Coded by 		: SKR
	# Created on	: 23-July-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
#Define constants for this page
$page_type = 'Ribbons';
$help_msg = get_help_messages('ADD_GIFT_WRAP_ORDER_RIBBON_MESS1');
?>	
<script language="javascript" type="text/javascript">
function valform(frm)
{
	fieldRequired = Array('ribbon_name');
	fieldDescription = Array('Ribbon Name');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array('ribbon_extraprice','ribbon_order');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		if(frm.ribbon_extraprice.value<0)
		{
		  alert('Extra Price entered should be a positive value.');
		  frm.ribbon_extraprice.focus();
		  return false;
		}
		else
		{
		show_processing();
		return true;
		}
	} else {
		return false;
	}
}
</script>
<form name='frmAddUser' action='home.php?request=giftwrap_ribbons' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=giftwrap_ribbons&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Giftwrap Ribbons</a><span>Add Ribbon</span></div></td>
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
          <td colspan="2" align="center" valign="middle" class="tdcolorgray" >
		<div class="editarea_div">
		<table width="100%">
        <tr>
          <td width="15%" align="left" valign="middle" class="tdcolorgray" >Ribbon Name <span class="redtext">*</span> </td>
          <td width="85%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="ribbon_name" value="<?=$_REQUEST['ribbon_name']?>"  />
		  </td>
        </tr>
		 <tr>
          <td width="15%" align="left" valign="middle" class="tdcolorgray" >Extra Price (<?php echo  display_curr_symbol()?>)</td>
          <td width="85%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="ribbon_extraprice" size="3" value="<?=$_REQUEST['ribbon_extraprice']?>"  />
		  </td>
        </tr>
		 <tr>
          <td width="15%" align="left" valign="middle" class="tdcolorgray" >Order</td>
          <td width="85%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="ribbon_order" size="3" value="<?=$_REQUEST['ribbon_order'] ?>"/>
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_GIFT_WRAP_ORDER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
       
        <tr>
          <td align="left" valign="middle" class="tdcolorgray" >Hidden</td>
          <td align="left" valign="middle" class="tdcolorgray"><input type="radio" name="ribbon_active" value="0" <? if($_REQUEST['ribbon_active']==0 && $_REQUEST['ribbon_active']!='') echo "checked";?>  />Yes<input type="radio" name="ribbon_active" value="1" <? if($_REQUEST['ribbon_active']==1 || $_REQUEST['ribbon_active']=='') echo "checked";?>/>No <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_GIFT_WRAP_RIB_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		</table>
		</div>
		</td>
		</tr>
		
		<tr>
          <td align="right" colspan="2" valign="middle" class="tdcolorgray" >
		  <div class="editarea_div">
			<table width="100%">
			<tr>
				<td width="100%" align="right" valign="middle">
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

