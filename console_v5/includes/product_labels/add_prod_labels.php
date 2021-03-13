<?php
	/*#################################################################
	# Script Name 	: add_prod_labels.php
	# Description 	: Page for adding Site Product Labels
	# Coded by 		: ANu
	# Created on	: 28-June-2007
	# Modified by	: Sny 
	# Modified On	: 08-Apr-2010 
	#################################################################*/
#Define constants for this page
$page_type = 'Product Labels';
$help_msg = get_help_messages('ADD_PROD_LAB_MESS1');

?>	
<script language="javascript" type="text/javascript">
function valform(frm)
{
	fieldRequired = Array('label_name');
	fieldDescription = Array('Label Name');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
	//checking for atlest one value for the drop down
			
	atleastone=0;
	cnt = 0;
	if(document.getElementById("dropdown").checked){
	var checks = 1;
	}
if(checks==1)     {         
	for(i=0;i<document.frmAddLabels.elements.length;i++){
		if (document.frmAddLabels.elements[i].type =='text' && document.frmAddLabels.elements[i].name !='label_name'){ 
			if (document.frmAddLabels.elements[i].value!=''){	
				return true;
			}
			else{
			atleastone=1;
			}
		}
		
	}
}
if(checks==1)     {   	
	if(atleastone){
	alert("Please Add atleast one value for the drop down");
	return false;
	}
}
		show_processing();
		return true;
	} else {
		return false;
	}
}
</script>
<form name='frmAddLabels' action='home.php?request=prod_labels' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=prod_labels&sort_by=<?=$sort_by?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&search_labelgroup=<?=$_REQUEST['search_labelgroup']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Product Labels</a><span> Add Product Labels</span></div></td>
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
          <td colspan="4" align="left" valign="top">
		  <div class="editarea_div">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
		 <tr>
		 <td width="41%" valign="top" class="tdcolorgray" >
				 <table width="100%" border="0" cellpadding="0" cellspacing="0">
					 <tr>
					   <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
					   <td colspan="3" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
					 </tr>
					<tr>
					  <td width="37%" align="left" valign="middle" class="tdcolorgray" >Label Name <span class="redtext">*</span> </td>
					  <td  align="left" valign="middle" class="tdcolorgray">
					  <input name="label_name" type="text" class="input" size="30" value="<?=$_REQUEST['label_name']?>"  />		  </td>
					</tr>
						  <tr>
							  <td width="37%" align="left" valign="middle" class="tdcolorgray" > Hidden</td>
							  <td width="63%" align="left" valign="middle" class="tdcolorgray"><input type="radio" name="label_hide" value="1" <? if($_REQUEST['label_hide']==1) echo "checked";?>/>
								Yes
								<input type="radio" name="label_hide" value="0"  <? if($_REQUEST['label_hide']==0) echo "checked";?>/>
								No <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_LAB_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
						 </tr>
						<tr>
							  <td width="37%" align="left" valign="middle" class="tdcolorgray">Show in search</td>
							  <td width="63%" align="left" valign="middle" class="tdcolorgray"><input name="in_search" type="checkbox" value="1" <? if($_REQUEST['in_search']==1) echo "checked"?> />&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_LAB_SEARCH')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
						</tr>	
					  <tr>
						  <td width="37%" align="left" valign="middle" class="tdcolorgray" >Type</td>
						  <td  align="left" valign="middle" class="tdcolorgray"><input name="is_textbox" type="radio" id="textbox"   onclick="handle_expansion('textbox','cattr_head')" value="1" <? if($_REQUEST['is_textbox']==1) echo "checked"?> />
							  Text Box
							  <input type="radio" name="is_textbox" id="dropdown" value="0" onclick="handle_expansion('dropdown','cattr_head')" <? if($_REQUEST['is_textbox']==0) echo "checked"?> />
							  Drop down <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_LAB_TYPE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
					 </tr>
					 <?php
					 	// Check whether there exists any product label groups
						$sql_label_grp = "SELECT group_id, group_name,group_hide  
											FROM 
												product_labels_group 
											WHERE 
												sites_site_id = $ecom_siteid 
											ORDER BY 
												group_name";
						$ret_label_grp = $db->query($sql_label_grp);
						if($db->num_rows($ret_label_grp))
						{
					 ?>
							<tr>
							<td align="left" valign="middle" class="tdcolorgray">Label Group</td>
							
							<?php
							$grp_arr = array();
							while ($row_label_grp = $db->fetch_array($ret_label_grp))
							{
								$grp_arr[$row_label_grp['group_id']] = stripslashes($row_label_grp['group_name']);
							}
							?>
								<td  align="left" valign="middle" class="tdcolorgray">
								<?php
									echo generateselectbox('group_id[]',$grp_arr,$_REQUEST['group_id'],'','');
					?>
								</td>
								</tr>
				   <?php
				   			
				   		}
				   ?>
				</table>
	</td>
		<td width="59%" class="tdcolorgray" valign="top" align="left">
		<table cellpadding="0" cellspacing="0" border="0" >
		<tr>
				<td colspan="5" align="left" valign="middle" class="tdcolorgray" id="cattr_head"  <?php echo ($_REQUEST['is_textbox'])?'style="display:none"':'style="display:"';?> >
				<b>The following are the values in the drop down.</b><a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_LAB_DROPVALUES')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
				
				<table width="100%" border="0">
				<?php 
				$labelcnt = 0;
				 for($i=0;$i<5;$i++){
				  $labelcnt++;
				  ?>
				   <tr>
					<td width="34%" align="left" valign="middle" class="tdcolorgray">Value <?=$labelcnt?></td>
					<td width="66%" align="left" valign="middle" class="tdcolorgray" ><input name="label_value<?=$labelcnt?>" type="text" value="<?=$_REQUEST["label_value$labelcnt"]?>" />				</td>
				  </tr>
				  <? }?>
				</table>			</td>
		</tr>
		</table> 
	</td>
	</tr>
	</table>
	</div>
	</td>
	</tr>
	
	<tr>
		<td colspan="4" align="right" valign="top">
			<div class="editarea_div">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="right" valign="middle" class="tdcolorgray">
						<input type="hidden" name="labelcnt" id="labelcnt" value="<?=$labelcnt?>" />
						<input type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>" />
						<input type="hidden" name="search_labelgroup" id="search_labelgroup" value="<?=$_REQUEST['search_labelgroup']?>" />
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

<script language="javascript" type="text/javascript">
function handle_expansion(imgobj,mod)
{

	if(imgobj == 'dropdown'){
		if(document.getElementById(imgobj).checked=true){
			document.getElementById(mod).style.display = '';
			document.getElementById('cattr_headmore').style.display = '';
		}
	}else if(imgobj == 'textbox'){
		if(document.getElementById(imgobj).checked=true){
		document.getElementById(mod).style.display = 'none';
		document.getElementById('cattr_headmore').style.display = 'none';
		}
	}
				
}
</script>