<?php
	/*#################################################################
	# Script Name 	: add_product_label_groups.php
	# Description 	: Page for adding Site Product Label Groups
	# Coded by 		: Sny
	# Created on	: 07-Apr-2010
	# Modified by	: 
	# Modified On	:
	#################################################################*/
#Define constants for this page
$page_type = 'Product Label Groups';
$help_msg = get_help_messages('ADD_PROD_LAB_GRP_MESS1');

?>	
<script language="javascript" type="text/javascript">
function valform(frm)
{
	fieldRequired = Array('group_name');
	fieldDescription = Array('Group Name');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		show_processing();
		return true;
	}
	else
	{
		return false;
	}
}
</script>
<form name='frmAddLabelGroups' action='home.php?request=prod_label_groups' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=prod_label_groups&sort_by=<?=$sort_by?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Product Label Groups</a><span> Add Product Label Groups</span></div></td>
        </tr>
        <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="4">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
		<?
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
		 <td width="100%" colspan="4" valign="top" class="tdcolorgray" >
		 <div class="editarea_div">
				 <table width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr>
					  <td width="14%" align="left" valign="middle" class="tdcolorgray" >Group Name <span class="redtext">*</span> </td>
					  <td  align="left" valign="middle" class="tdcolorgray">
					  <input name="group_name" type="text" class="input" size="30" value="<?=$_REQUEST['group_name']?>"  />		  </td>
					</tr>
						  <tr>
						    <td align="left" valign="middle" class="tdcolorgray" >Group Sort Order </td>
						    <td align="left" valign="middle" class="tdcolorgray"><input name="group_order" type="text" size="4" value="<?=$_REQUEST['group_order']?>"/></td>
			       </tr>
						  <tr>
						    <td align="left" valign="middle" class="tdcolorgray" > Hide Group Name</td>
						    <td align="left" valign="middle" class="tdcolorgray"><input type="radio" name="group_name_hide" value="1" <? if($_REQUEST['group_name_hide']==1) echo "checked";?>/>
						      Yes
						      <input type="radio" name="group_name_hide" value="0"  <? if($_REQUEST['group_name_hide']==0) echo "checked";?>/>
						      No <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_LABGRP_HIDE_NAME')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
			       </tr>
						  <tr>
							  <td width="14%" align="left" valign="middle" class="tdcolorgray" > Hidden</td>
							  <td width="86%" align="left" valign="middle" class="tdcolorgray"><input type="radio" name="group_hide" value="1" <? if($_REQUEST['group_hide']==1) echo "checked";?>/>
								Yes
								<input type="radio" name="group_hide" value="0"  <? if($_REQUEST['group_hide']==0) echo "checked";?>/>
							No <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_LABGRP_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
						 </tr>
				</table>
				</div>
	</td>
		<!--<td width="59%" class="tdcolorgray" valign="left" align="left">
		<?php /*?><table width="382" border="0" cellpadding="0" cellspacing="0" >
		<tr>
				<td colspan="5" align="left" valign="middle" class="tdcolorgray" id="cattr_head"  <?php echo ($_REQUEST['is_textbox'])?'style="display:none"':'style="display:"';?> >
				<b>Product Categories to which this group is linked.</b><a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_LABGROP_CAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
				<table width="100%" border="0"> 
				   <tr>
					<td class="tdcolorgray">
					<?php
					$cat_arr = generate_category_tree(0,0,false,true);
					echo generateselectbox('category_id[]',$cat_arr,$_REQUEST['category_id'],'','',20);
					?>
					</td>
				  </tr>
				</table>			</td>
		</tr>
		</table><?php */?> 
	</td>-->
	</tr>
		 
	<tr>
		<td width="100%" colspan="4" valign="top" class="tdcolorgray" >
			<div class="editarea_div">
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="100%" align="right" valign="middle" class="tdcolorgray" >
						<input type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>" />
						<input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
						<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
						<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
						<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
						<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
						<input type="hidden" name="fpurpose" id="fpurpose" value="insert" />
						<input name="Submit" type="submit" class="red" value="Save Group" />
					</td>
				</tr>
				</table>
			</div>
		</td>
	</tr>
      </table>
</form>