<?php
	/*#################################################################
	# Script Name 	: add_sizechart.php
	# Description 	: Page for adding Product Size Chart
	# Coded by 		: ANU
	# Created on	: 17-Mar-2008
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
#Define constants for this page
$page_type = 'Product Specification Heading';
$help_msg = get_help_messages('ADD_PROD_SIZE_CHART_MESS1');

?>	
<script language="javascript" type="text/javascript">
function valform(frm)
{
	fieldRequired = Array('heading_title');
	fieldDescription = Array('Heading');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array('heading_sortorder');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		show_processing();
		return true;
	} else {
		return false;
	}
}
</script>
<form name='frmAddUser' action='home.php?request=sizechart' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=sizechart&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Product Specification Heading </a><span> Add Product Specification Heading</span></div></td>
        </tr>
        <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="3">
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
          <td colspan="3" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
        </tr>
		<?
		}
		?>
		<tr>
          <td colspan="3" align="left" valign="top" class="tdcolorgray" >
		<div class="editarea_div">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="22%" align="left" valign="middle" class="tdcolorgray" >Product Specification Heading <span class="redtext">*</span>  </td>
          <td width="46%" align="left" valign="middle" class="tdcolorgray">
		  <input name="heading_title" type="text" class="input"  value="<?=$_REQUEST['heading_title']?>" size="45" />		  </td>
          <td width="32%" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
        </tr>
		 <tr>
          <td width="22%" align="left" valign="middle" class="tdcolorgray" >Sort Order</td>
          <td width="46%" align="left" valign="middle" class="tdcolorgray"><input class="input" type="text" name="heading_sortorder" value="<?=$_REQUEST['heading_sortorder']?>" size="2">
           <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_SIZE_CHART_ORDER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> </td>
          <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
        </tr>
		 <tr>
		   <td align="left" valign="middle" class="tdcolorgray">Hidden</td>
		   <td align="left" valign="middle" class="tdcolorgray"><input type="radio" name="heading_hide" value="1" <? if($_REQUEST['heading_hide']==1) echo "checked"?> />
		     Yes
		     <input type="radio" name="heading_hide" value="0" <? if($_REQUEST['heading_hide']==0) echo "checked"?> />
		     No <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_SIZE_CHART_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
        </tr>
       
       </table>
	   </div>
	   </td>
	   </tr>
	   
		<tr>
			<td colspan="3" align="right" valign="top" class="tdcolorgray" >
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
							<input name="Submit" type="submit" class="red" value="Save" />
						</td>
					</tr>
					</table>
				</div>
			</td>
		</tr>
      </table>
</form>	  

