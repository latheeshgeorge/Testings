<?php
	/*#################################################################
	# Script Name 	: edit_prod_labels.php
	# Description 	: Page for editing Site Labels
	# Coded by 		: ANU
	# Created on	: 28-June-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
#Define constants for this page
$page_type = 'Product Labels';
$help_msg = get_help_messages('EDIT_PROD_LAB_MESS1');
$label_id=($_REQUEST['label_id']?$_REQUEST['label_id']:$_REQUEST['checkbox'][0]);
$sql_label="SELECT label_name,in_search,is_textbox,label_hide 
				FROM product_site_labels  WHERE label_id=".$label_id." AND sites_site_id=".$ecom_siteid." ";
$res_label= $db->query($sql_label);
if($db->num_rows($res_label)==0)  { echo " <font color='red'> You Are Not Authorised  </a>"; exit; }
$row_label = $db->fetch_array($res_label);
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
	else
	{
	var checks = 0;
	}
if(checks)     {         
	for(i=0;i<document.frmEditLables.elements.length;i++){
		if (document.frmEditLables.elements[i].type =='text' && document.frmEditLables.elements[i].name !='label_name'){ 
			if (document.frmEditLables.elements[i].value!=''){	
				return true;
			}
			else{
			atleastone=1;
			}
		}
		
	}
}	
if(checks == 1){
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
<form name='frmEditLables' action='home.php?request=prod_labels' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=prod_labels&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&search_labelgroup=<?=$_REQUEST['search_labelgroup']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Product Labels</a><span> Edit Product Labels</span></div></td>
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
		<td  class="tdcolorgray" valign="top">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
          <td width="36%" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td colspan="3" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
        </tr>
        <tr>
          <td width="36%" align="left" valign="middle" class="tdcolorgray" >Label Name <span class="redtext">*</span> </td>
          <td colspan="3" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="label_name" value="<?=$row_label['label_name']?>"  />		  </td>
        </tr>
		  <tr>
            <td align="left" valign="middle" class="tdcolorgray" > Hidden</td>
		    <td width="64%" align="left" valign="middle" class="tdcolorgray"><input type="radio" name="label_hide" value="1" <?php echo ($row_label['label_hide']==1)?'checked="checked"':''?> />
Yes
  <input type="radio" name="label_hide" value="0" <?php echo ($row_label['label_hide']==0)?'checked="checked"':''?>/>
No<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_LAB_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          </tr>
		   <tr>
	   		 <td width="36%" align="left" valign="middle" class="tdcolorgray">Show in search</td>
           	 <td width="64%" align="left" valign="middle" class="tdcolorgray"><input name="in_search" type="checkbox" value="1"  <?php echo ($row_label['in_search']==1)?'checked="checked"':''?> />&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_LAB_SEARCH')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		   </tr>
		   
		  <tr>
            <td align="left" valign="middle" class="tdcolorgray" >Type</td>
		    <td  align="left" valign="middle" class="tdcolorgray" >
			<input type="radio" name="is_textbox" id="textbox" value="1" <?php echo ($row_label['is_textbox']==1)?'checked="checked"':''?> onclick="handle_expansion('textbox','cattr_head')" />
		      Text Box
		      <input type="radio" name="is_textbox" id="dropdown" value="0" <?php echo ($row_label['is_textbox']==0)?'checked="checked"':''?> onclick="handle_expansion('dropdown','cattr_head')"/>
		      Drop down <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_LAB_TYPE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
            <input type="hidden" name="check_type" value="<?=$row_label['is_textbox']?>"  id="check_type"/>
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
							// Get the list of groups to which the current label is assigned 
							$sql_label_map = "SELECT product_labels_group_group_id 
												FROM 
													product_labels_group_label_map
												WHERE 
													product_site_labels_label_id = $label_id";
							$ret_label_map = $db->query($sql_label_map);
							if($db->num_rows($ret_label_map))
							{
								while ($row_label_map = $db->fetch_array($ret_label_map))
								{
									$label_sel[] = $row_label_map['product_labels_group_group_id'];
								}
							}
							?>
								<td  align="left" valign="middle" class="tdcolorgray">
								<?php
									echo generateselectbox('group_id[]',$grp_arr,$label_sel,'','');
					?>
								</td>
								</tr>
				   <?php
				   			
				   		}
				   ?>
		</table>
		</td>
		<td width="56%" valign="top" class="tdcolorgray">
		 	<table width="545" border="0" cellpadding="0" cellspacing="0">
				<tr>
		    <td width="545"  align="left" valign="middle" class="tdcolorgray" id="cattr_head" <?php echo ($row_label['is_textbox'])?'style="display:none"':'style="display:"';?> >
			<b>The following are the values in the drop down.</b><a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_LAB_DROPVALUES')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
			
			<table width="112%" border="0">
			<?php 
			if($row_label['is_textbox'] == '0') { 
			$sql_labelvalues = "SELECT label_value_id,product_site_labels_label_id,label_value,label_value_order FROM product_site_labels_values WHERE product_site_labels_label_id =".$label_id." ORDER BY label_value_order ASC";
			$res_labelvalues = $db->query($sql_labelvalues);
			$labelcnt=0;
			
			while($labelvalues =$db->fetch_array($res_labelvalues)){ 
			$labelcnt++;
			?>
              <tr>
                <td width="15%" align="left" valign="middle" class="tdcolorgray">Value <?=$labelcnt?></td>
                <td width="85%" align="left" valign="middle" class="tdcolorgray" ><input name="label_value<?=$labelcnt?>" type="text" value="<?=$labelvalues['label_value']?>" />
				<input name="value_id<?=$labelcnt;?>" type="hidden" value="<?=$labelvalues['label_value_id'];?>" id="value_id<?=$labelcnt?>"  size="30">				</td>
              </tr>
             
              <? }
			}
			 for($i=0;$i<5;$i++){
			  $labelcnt++;
			  ?>
			   <tr>
                <td width="15%" align="left" valign="middle" class="tdcolorgray">Value <?=$labelcnt?></td>
                <td width="85%" align="left" valign="middle" class="tdcolorgray" ><input name="label_value<?=$labelcnt?>" type="text" value="" />				</td>
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
						<input type="hidden" name="label_id" id="label_id" value="<?=$label_id?>" />
						<input type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>" />
						<input type="hidden" name="search_labelgroup" id="search_labelgroup" value="<?=$_REQUEST['search_labelgroup']?>" />
						<input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
						<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
						<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
						<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
						<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
						<input type="hidden" name="fpurpose" id="fpurpose" value="update" />
						<input name="Submit" type="submit" class="red" value="Update" /> 
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
