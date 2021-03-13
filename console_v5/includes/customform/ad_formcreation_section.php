
<table width="100%" border="0" cellspacing="0" cellpadding="10" height="100%">
	<tr>
		<td valign="top">
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td><b><a href="home.php?request=customform&form_type=<?=$_REQUEST['form_type']?>"><?=$section_type_array[$_REQUEST['form_type']]?></a>&nbsp; <font size="3">&raquo;</font> Add Custom Form Sections</b></td>
				</tr>
				<tr>
					<td><img src="images/blueline.gif" alt="" height="1" width="400" border="0"></td>
				</tr>
				<tr>
				  <td class="maininnertabletd3">This page can be used to add a new custom form section. Once a section is created, form elements such as textbox, textarea etc can be added to it.</td>
			  </tr>
		  </table>
			<br>
			<table width="100%">
			<tr><td valign="top">
			
			<form name="section" action="home.php?request=customform&fpurpose=formcreation" method="post">
				<table border="0" cellspacing="0" cellpadding="4">
					<tr>
						<td><b>Section Name:</b> </td><td><input type="text" name="section_name" value="" /></td>
					</tr>
					<tr>
						<td><b>Position:</b> </td><td>
						<select name="position">
						<option value="top">Top</option>
						<option value="bottom">Bottom</option>
						</select> <br />(Top => Above the hardcoded Customer details fields. <br />Bottom => Below the hardcoded Customer DETAILS fields.)
						</td>
					</tr>
					<tr>
						<td><b>Help Instructions:</b> </td><td><textarea name="message" rows="5" cols="40"></textarea></td>
					</tr>
					<tr>
						<td colspan="2" align="center"><input type="submit" class="bigsubmit" name="submit" value="Add New Section" onclick="show_processing();"></td>
					</tr>
				</table>
				<input type="hidden" name="form_type" id="form_type" value="<?=$_REQUEST['form_type']?>" />
			</form>
			
			</td></tr>
			</table>
			
			
		</td>
	</tr>
</table>