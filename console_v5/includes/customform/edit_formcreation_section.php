<?php
$sql = "SELECT section_name,sort_no,position,message FROM element_sections WHERE sites_site_id=$ecom_siteid AND section_id=".$_REQUEST['section_id'];
$res = $db->query($sql);
if($db->num_rows($res)==0) { echo " <font color='red'> You Are Not Authorised  </a>"; exit; }
$row = $db->fetch_array($res);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="10" height="100%">
	<tr>
		<td valign="top">
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td><b><a href="home.php?request=customform&form_type=<?=$_REQUEST['form_type']?>"><?=$section_type_array[$_REQUEST['form_type']]?></a>&nbsp; <font size="3">&raquo;</font> Edit Custom Form Sections</b></td>
				</tr>
				<tr>
					<td><img src="images/blueline.gif" alt="" height="1" width="400" border="0"></td>
				</tr>
				<tr>
				  <td class="maininnertabletd3">This page can be used to edit the details of an existing custom form section.</td>
			  </tr>
		  </table>
			<br>
			<table>
			<tr><td valign="top">
			
			<form name="section" action="home.php?request=customform" method="post">
				<table border="0" cellspacing="0" cellpadding="4">
					<tr>
						<td><b>Section Name:</b> </td><td><input type="text" name="section_name" value="<?=$row['section_name']?>" /></td>
					</tr>
					<tr>
						<td><b>Sort Order:</b> </td><td><input type="text" name="sort_no" value="<?=$row['sort_no']?>" size="5"/>
						  &nbsp;<a href="#" style="cursor:pointer;" onmouseover="return overlib('If more than one section exists in same position, then this field decides the order in which they are to be displayed in enquiry section.',100,VAUTO);" onmouseout="return nd();"><img src="images/help_icon.gif" border="0" alt="" /></a></td>
					</tr>
					<tr>
						<td><b>Position:</b> </td><td>
						<select name="position">
						<option value="top" <?php if($row['position'] == 'top') echo 'selected'; ?>>Top</option>
						<option value="bottom" <?php if($row['position'] == 'bottom') echo 'selected'; ?>>Bottom</option>
						</select> <br />(Top => Above the hardcoded Customer details fields. <br />Bottom => Below the hardcoded Customer DETAILS fields.)
						</td>
					</tr>
					<tr>
						<td><b>Help Instructions:</b> </td><td><textarea name="message" rows="5" cols="40"><?=$row['message']?></textarea></td>
					</tr>
					<tr>
						<td colspan="2" align="center"><input type="submit" class="bigsubmit" name="submit" value="Update" onclick="show_processing();"></td>
					</tr>
				</table>
				<input type="hidden" name="section_id" value="<?=$_REQUEST['section_id']?>" />
				<input type="hidden" name="form_type" id="form_type" value="<?=$_REQUEST['form_type']?>" />
			</form>
			
				
			</td></tr>
			</table>
			
			
		</td>
	</tr>
</table>