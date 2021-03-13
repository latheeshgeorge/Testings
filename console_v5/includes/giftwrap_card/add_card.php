<?php
	/*#################################################################
	# Script Name 	: add_card.php
	# Description 	: Page for adding Giftwrap Card
	# Coded by 		: SKR
	# Created on	: 21-July-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
#Define constants for this page
$page_type = 'Cards';
$help_msg = get_help_messages('ADD_GIFTWRAP_CARD_MESS1');

?>	
<script language="javascript" type="text/javascript">
function valform(frm)
{
	fieldRequired = Array('card_name');
	fieldDescription = Array('Card Name');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array('card_extraprice','card_order');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		if(frm.card_extraprice.value<0)
		{
		  alert('Extra Price entered should be a positive value.');
		  frm.card_extraprice.focus();
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
<form name='frmAddUser' action='home.php?request=giftwrap_cards' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=giftwrap_cards&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List  Giftwrap Cards</a><span>Add Card</span></div></td>
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
		  <table width="100%" class="fieldtable">
        <tr>
          <td width="15%" align="left" valign="middle" class="tdcolorgray" >Card Name <span class="redtext">*</span> </td>
          <td width="85%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="card_name" value="<?=$_REQUEST['card_name']?>"/>
		  </td>
        </tr>
		 <tr>
          <td width="15%" align="left" valign="middle" class="tdcolorgray" >Extra Price (<?php echo  display_curr_symbol()?>)</td>
          <td width="85%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="card_extraprice" size="3" value="<?=$_REQUEST['card_extraprice']?>"  />
		  </td>
        </tr>
		 <tr>
          <td width="15%" align="left" valign="middle" class="tdcolorgray" >Order</td>
          <td width="85%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="card_order" size="3"  value="<?=$_REQUEST['card_order']?>" />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_GIFTWRAP_CARD_ORDER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
        <tr>
          <td align="left" valign="middle" class="tdcolorgray" >Hidden</td>
          <td align="left" valign="middle" class="tdcolorgray"><input type="radio" name="card_active" value="0" <? if($_REQUEST['card_active']==0 && $_REQUEST['card_active']!='' ) echo "checked";?> />Yes<input type="radio" name="card_active" value="1" <? if($_REQUEST['card_active']==1 || $_REQUEST['card_active']=='') echo "checked";?> />No
		   <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_GIFTWRAP_CARD_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		 <tr>
          <td colspan="2" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
         
        </tr>
      </table>
	  </div>
	  </td>
	  </tr>
	  <tr>
           <td colspan="2" align="right" valign="middle" class="tdcolorgray" >
		  <div class="editarea_div">
		  <table width="100%">
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

