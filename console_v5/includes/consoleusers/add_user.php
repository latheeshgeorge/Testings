<?php
	/*#################################################################
	# Script Name 	: add_user.php
	# Description 	: Page for adding Site Users
	# Coded by 		: SKR
	# Created on	: 12-June-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
#Define constants for this page
$page_type = 'Users';
$help_msg = get_help_messages('ADD_CONUSERS_MESS1');
?>	
<script language="javascript" type="text/javascript">
function valform(frm)
{
	fieldRequired = Array('user_fname','user_lname','user_type','user_email','user_pwd');
	fieldDescription = Array('First Name','Last Name','User Type','Email','Password');
	fieldEmail = Array('user_email');
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	fieldSpecChars = Array('user_fname','user_lname','user_phone','user_mobile','user_pwd');
	fieldCharDesc = Array('First Name','Last Name','Phone','Mobile','Password');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric,fieldSpecChars,fieldCharDesc)) {
		if(frm.user_pwd.value!='' ){
			if( frm.user_pwd.value.match(/[\s]+/)){
				alert("Password should not contain white space");
				frm.user_pwd.select();
				return false;
			}
		}
		show_processing();
		return true;
	} else {
		return false;
	}
}
function handle_usertype(cur_val)
{
	var disp_type;
	if (cur_val=='su')
		document.getElementById('perm_table').style.display = '';
	else
		document.getElementById('perm_table').style.display = 'none';
	
		
}
function select_all_userfile(frm,obj)
	{
		var atleastone = false;
		var len  = frm.elements.length;
		for (i=0;i<len;i++)
		{
			
			if (frm.elements[i].type== "checkbox" && frm.elements[i].name ==obj) 
			{
				if (frm.elements[i].checked==false)
					frm.elements[i].checked = true;
			}
		}
		frm.user_console_turnoverdisplay.checked = true;	
	}
	function select_none_userfile(frm,obj)
	{
		var atleastone = false;
		var len  = frm.elements.length;
		for (i=0;i<len;i++)
		{
			
			if (frm.elements[i].type== "checkbox" && frm.elements[i].name ==obj) 
			{
				frm.elements[i].checked=false;
			}
		}
		frm.user_console_turnoverdisplay.checked = false;
	}
</script>
<form name='frmAddUser' action='home.php?request=console_user' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=console_user&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Users</a> <span> Add User</span></div></td>
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
			<td class="tdcolorgray">
			<div class="editarea_div">
				<table width="100%">
				<tr>
				  <td width="15%" align="left" valign="middle" class="tdcolorgray" >Title <span class="redtext">*</span> </td>
				  <td width="32%" align="left" valign="middle" class="tdcolorgray">
				  <select name="user_title" id="user_title">
					  <option value="Mr" <?php if($_REQUEST['user_title']=='Mr') echo "selected"?>>Mr.</option>
					  <option value="Ms" <?php if($_REQUEST['user_title']=='Ms') echo "selected"?>>Ms.</option>
					  <option value="Mrs" <?php if($_REQUEST['user_title']=='Mrs') echo "selected"?>>Mrs.</option>
					  <option value="Miss" <?php if($_REQUEST['user_title']=='Miss') echo "selected"?>>Miss.</option>
					  <option value="M/s" <?php if($_REQUEST['user_title']=='M/s') echo "selected"?>>M/s.</option>
				  </select>		  </td>
				  <td width="12%" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
				  <td width="41%" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
				</tr>
				<tr>
				  <td align="left" valign="middle" class="tdcolorgray" >First name <span class="redtext">*</span> </td>
				  <td align="left" valign="middle" class="tdcolorgray"><input class="input" type="text" name="user_fname"  value="<?php echo $_REQUEST['user_fname']?>" maxlength="30"/></td>
				  <td align="left" valign="middle" class="tdcolorgray">Last name <span class="redtext">*</span></td>
				  <td align="left" valign="middle" class="tdcolorgray"><input class="input" type="text" name="user_lname" value="<?php echo $_REQUEST['user_lname']?>" maxlength="30"/></td>
				</tr>
				  <tr>
				  <td align="left" valign="middle" class="tdcolorgray" >Address </td>
				  <td colspan="3" align="left" valign="middle" class="tdcolorgray"><textarea name="user_address" cols="30" rows="4"><?php echo $_REQUEST['user_address']?></textarea></td>
				</tr>
			  <tr>
				<td align="left" valign="middle" class="tdcolorgray" >Company</td>
				<td align="left" valign="middle" class="tdcolorgray"><input class="input" type="text" name="user_company" value="<?=$_REQUEST['user_company']?>" /></td>
				<td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
				<td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
			</tr>
			  <tr>
			  <td align="left" valign="middle" class="tdcolorgray" >Phone </td>
			  <td align="left" valign="middle" class="tdcolorgray"><input class="input" type="text" name="user_phone" value="<?php echo $_REQUEST['user_phone']?>" /></td>
			  <td align="left" valign="middle" class="tdcolorgray">Mobile</td>
			  <td align="left" valign="middle" class="tdcolorgray"><input class="input" type="text" name="user_mobile"  value="<?php echo $_REQUEST['user_mobile']?>" maxlength="15"/></td>
	   		 </tr>
       
			 <tr>
			  <td align="left" valign="middle" class="tdcolorgray" >User Type<span class="redtext"> *</span> </td>
			  <td align="left" valign="middle" class="tdcolorgray">
			  <select name="user_type" id="user_type" onchange="handle_usertype(this.value)">
			  <option value="">-- Select --</option>
			  <option value="sa" <?php if($_REQUEST['user_type']=='sa') echo "selected"?>>System Administrator</option>
			   <option value="su" <?php if($_REQUEST['user_type']=='su') echo "selected"?>>System User</option>
			  </select>		<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_CONSOLEUSER_TYPE')?> ')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>  </td>
			  <td align="left" valign="middle" class="tdcolorgray">Active?</td>
			  <td align="left" valign="middle" class="tdcolorgray"><input type="radio" name="user_active" value="1" />
				Yes
				  <input type="radio" name="user_active" value="0" checked="checked"  />
			   No <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_CONUSER_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
			</tr>
			 <tr>
			  <td align="left" valign="middle" class="tdcolorgray" >Branch<span class="redtext"> *</span> </td>
			  <td align="left" valign="middle" class="tdcolorgray">
			   <?php 
				// Get the list of shops under current site
				$sql_shops = "SELECT shop_id,shop_title,shop_active FROM sites_shops WHERE sites_site_id=$ecom_siteid ORDER BY shop_title";
				$ret_shops =$db->query($sql_shops);
			  ?>
				<select name="shop_id">
				<option value="0" <?php echo ($row_user['shop_id']==0)?'selected="selected"':''?>>Web</option>
			  <?php
				if($db->num_rows($ret_shops))
				{
					while ($row_shops = $db->fetch_array($ret_shops))
					{
						if($row_shops['shop_active']==1)
							$stat = 'Active';
						else
							$stat = 'Inactive';
				?>
						 <option value="<?php echo $row_shops['shop_id']?>"<?php echo ($row_user['shop_id']==$row_shops['shop_id'])?'selected="selected"':''?>><?php echo stripslashes($row_shops['shop_title']).'('.$stat.')'?></option>
				<?php		
					}
				}
			  ?>
			  </select>
			 <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_CONSOLEUSER_SHOP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> </td>
			  <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
			  <td align="left" valign="middle" class="tdcolorgray"></td>
			</tr>
			 <tr>
			   <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
			   <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
			   <td align="left" valign="middle" class="tdcolorgray">Password<span class="redtext"> *</span></td>
			   <td align="left" valign="middle" class="tdcolorgray"><input type="password"  name="user_pwd" /> </td>
		</tr>
			 <tr>
			   <td align="left" valign="middle" class="tdcolorgray" >Email<span class="redtext"> *</span> </td>
			   <td align="left" valign="middle" class="tdcolorgray"><input class="input" type="text" name="user_email" value="<?=$_REQUEST['user_email']?>" />
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_CONSOLEUSER_EMAIL')?> ')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
			   <td align="left" valign="middle" class="tdcolorgray" nowrap="nowrap">Confirm Password<span class="redtext"> *</span></td>
			   <td align="left" valign="middle" class="tdcolorgray"><input type="password"  name="user_cnfmpwd" />            </td>
			</tr>
			 <tr>
			  <td colspan="4" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
			</tr>
			</table>
			<table width="100%" border="0" cellspacing="0" cellpadding="0" id="perm_table">
			<?php
				
				
			?>
				 <tr id="admin_hide1">
				  <td colspan="2" align="left" valign="middle" class="tdcolorgray" ><b>User Permissions
				  <img src="images/checkbox.gif" border="0" onclick="select_all_userfile(document.frmAddUser,'checkbox[]')" alt="Check all" title="Check all"/><img src="images/uncheckbox.gif" border="0" onclick="select_none_userfile(document.frmAddUser,'checkbox[]')" alt="Uncheck all" title="Uncheck all"/>
				  </b> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_CONSOLEUSER_PERMIT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
				</tr>
			<?php
				$exists_features = false;
				$extserv_arr	= array(0);
				$extfeat_arr	= array(0);
				// Get the services and features in the console level of current site
				$sql_console = "SELECT services_service_id,features_feature_id FROM console_levels_details WHERE console_levels_level_id=$ecom_levelid";
				$ret_console = $db->query($sql_console);
				if($db->num_rows($ret_console))
				{
					while($row_console = $db->fetch_array($ret_console))
					{
						$extserv_arr[] = $row_console['services_service_id'];
						$extfeat_arr[] = $row_console['features_feature_id'];
					}
					$service_arr	= array_unique($extserv_arr);
					$feature_arr	= array_unique($extfeat_arr);
					$extserv_arr	= $service_arr;
					$extfeat_arr	= $extfeat_arr;
				}
				$service_str		= implode(",",$extserv_arr);
				$feature_str		= implode(",",$extfeat_arr);
				$sql_service		= "SELECT service_id,service_name FROM services WHERE hide='0' AND service_id IN ($service_str) ORDER BY ordering";
				$res_service		= $db->query($sql_service);
				while($row_service 	= $db->fetch_array($res_service))
				{
					
					$service_id		= $row_service['service_id'];
					$service_name	= $row_service['service_name'];
					?>
				<tr id="admin_hide<?php echo $service_id?>">
				  <td colspan="2" align="left" valign="middle" class="sorttd" ><b><?=$service_name?></b></td>
				</tr>
				<tr id="admin_hidesub<?php echo $service_id?>">
					  <td align="left" valign="middle" class="tdcolorgray"  nowrap="nowrap" colspan="2">
					<?
					$sql_feature="SELECT menu_id,menu_title FROM mod_menu a,features b WHERE b.feature_id IN ($feature_str) AND a.sites_site_id=$ecom_siteid AND b.services_service_id=$service_id AND b.feature_displaytouser=1 AND b.feature_hide=0 AND a.features_feature_id=b.feature_id ORDER BY menu_title ";
					$res_feature = $db->query($sql_feature);
					if($db->num_rows($res_feature))
					{
					?>
					 <table width="100%" cellpadding="1" cellspacing="1" border="0">
					  <tr>
					<?php
						$max_col = 2;
						$cur_col = 0;
						while($row_feature = $db->fetch_array($res_feature))
						{
						?>
						  <td align="left" valign="middle" class="tdcolorgray" width="33%">
						  <input type="checkbox" name="checkbox[]" value="<?=$row_feature['menu_id']?>" />
						  <?=$row_feature['menu_title']; ?></td>
		
						<?php
							$cur_col++;
							if($cur_col > $max_col)
							{
								echo "</tr><tr>";
								$cur_col =0;
							}
							$exists_features = true;
						}
						if ($cur_col<=$max_col)
						echo "<td colspan='".($max_cols-$cur_col+1)."'>&nbsp;</td>";
					?>
					   </tr>
						</table>
					<?php	
					}
				?>
					</td>
			  </tr>
				<?php	
				}
				?>
				<tr id="admin_hide0">
				<td colspan="2" align="left" valign="middle" class="sorttd" ><b>Console Home Page</b></td>
				</tr>
				<tr id="admin_hidesub0">
				<td align="left" valign="middle" class="tdcolorgray"  nowrap="nowrap" colspan="2">
				<table width="100%" cellpadding="1" cellspacing="1" border="0">
				<tr>
				<td align="left" valign="middle" class="tdcolorgray" width="33%">
				<input type="checkbox" name="user_console_turnoverdisplay" value="1" <?php echo ($row_user['user_console_turnoverdisplay'])?'checked':'';?> />
				Orders By day (Past 7 days) & No:of Orders By Month (Past 7 months)</td>
				</tr>
				</table>
				</td>
				</tr>
				<?php
				if ($exists_features==false)
				{
				?>
				 <tr id="admin_hideno">
				  <td colspan="2" align="center" valign="middle" class="errormsg">No Features found</td>
				</tr>
				<?php
				}
			
			?>
			<tr>
			  <td colspan="2" align="center" valign="middle" class="tdcolorgray" >&nbsp;</td>
			</tr>
		  </table>
		  </div>
		 </td>
		 </tr>
		 <tr>
			<td width="100%" class="tdcolorgray">
			<div class="editarea_div">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
			  <td colspan="3" align="right" valign="middle" class="tdcolorgray">
			  
			  <input type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>" />
			   <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
			  <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
			  <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
			  <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
			  <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
			  <input type="hidden" name="fpurpose" id="fpurpose" value="insert" />
			  <input name="Submit" type="submit" class="red" value="Save" /></td>
			</tr>
		  </table>
			</div>
		 </table>
	  <script type="text/javascript">
	  	handle_usertype('<?php echo $_REQUEST['user_type']?>');
	  </script>
</form>	  

