<?php
/*Here id = Event Id*/
if(!$_REQUEST['form_type']) {
	$sql_section = "SELECT section_type FROM element_sections WHERE section_id=".$_REQUEST['section_id'];
	$res_section = $db->query($sql_section);
	list($_REQUEST['form_type']) = $db->fetch_array($res_section);
}
if($_REQUEST['Update'])
{
	for($i=0; $i<$no_of_elements; $i++)
	{
		$element_id = $_POST["element_row_no$i"];
		$sort_no[] = $_POST["sort$element_id"];/*Storing all sort number to an array*/
		$label_text[] = $_POST["label$element_id"];
	}
	$flag_sort = true;
	if(is_array($sort_no))
	{
		/*Checking whether any two sort no equals*/
		foreach($sort_no as $k => $v)
		{
			$start_key = $k+1;
			for($i=$start_key; $i<$no_of_elements; $i++)
			{
				if($v == $sort_no[$i])
				{
					$flag_sort = false;
					break;
				}
			}
		}
	}	
	/*Checking whether any two sort no equals*/
	if($flag_sort)
	{
			for($i=0; $i<$no_of_elements; $i++)
			{
				$element_id = $_POST["element_row_no$i"];
				$label = $_POST["label$element_id"];
				$label = str_replace("'","",$label);
				$label = str_replace("\"","",$label);
				
				$update_query = "UPDATE elements SET element_name='".$_POST["name$element_id"]."',
				element_label='".$label."',
				element_align='".$_POST["align$element_id"]."',
				element_valign='".$_POST["valign$element_id"]."',
				sort_no='".$_POST["sort$element_id"]."',";
				
				if($mandatory[$element_id])
					$update_query .= "mandatory='Y'";
				else
					$update_query .= "mandatory='N'";
				
				$update_query .= " WHERE element_id='$element_id' AND site_id=$ecom_siteid";
				$db->query($update_query);
				
				$alert = "Sucessfully Updated!";
			}
	}
	else
	{
		$alert = "ERROR! Sort number is repeated";
	}
}
?>
<?php
	if($_REQUEST['Delete'])
	{
			$db->query("DELETE FROM elements WHERE element_id=".$_REQUEST['delete_id']." AND site_id=$ecom_siteid");
			$db->query("DELETE FROM element_value WHERE element_id=".$_REQUEST['delete_id']);
	}
?>
<?php

	$sql = "SELECT section_name FROM element_sections WHERE section_id=".$_REQUEST['section_id'];
	$res = $db->query($sql);
	$row = $db->fetch_array($res);
	
	$valign_array = array('top','bottom','middle');
	$halign_array = array('right','left','center');
	$table_headers = array('#','Label','Type','Align','Valign','Sort','Mandatory');
 /*During edit operation no value is assigned to type also a variable named from_element_page is passed to add_elements, this for reseting the session array*/?>
<script language="JavaScript">
function open_elements(type)
{
	if(type == 'edit')
	{
		if(document.frmregistration_form.delete_id.value == "")
		{
			alert("No row Selected");
			return false;
		}
		window.open('services/add_elements.php?from_element_page=yes&section_id=<?=$_REQUEST['section_id']?>&emt_id='+document.frmregistration_form.delete_id.value,'win','width=500,height=250,scrollbars=yes');
	}
	else
	{
		window.open('services/add_elements.php?from_element_page=yes&section_id=<?=$_REQUEST['section_id']?>&type='+type,'win','width=500,height=250,scrollbars=yes');
	}
	return false;
}
function validate()
{
	if(document.frmregistration_form.delete_id.value == "")
	{
		alert("No row Selected");
		return false;
	}
	if(confirm("Are you sure?"))
	{
		show_processing();
		return true;
	}
	else
	{
		return false;
	}
}
function open_preview(id)
{
	window.open('form_preview.php?section_id=<?=$_REQUEST['section_id']?>','win','width=500,height=400,scrollbars=yes');
	return false;
}
</script>

<form name="frmregistration_form" method="POST" action="home.php?request=customform&fpurpose=formcreation">
<table width="100%" align="center">
	<tr>
		<td colspan="2">
		<table width="100%" border="0" cellspacing="0" cellpadding="2">
					<tr>
							<td><b><a href="home.php?request=customform&form_type=<?=$_REQUEST['form_type']?>"><?=$section_type_array[$_REQUEST['form_type']]?></a> <font size="3">&raquo;</font> Manage Custom form Elements</b></td>
			  </tr>
			<tr>
				<td><img src="images/blueline.gif" alt="" height="1" width="400" border="0"></td>
			</tr>
			<tr>
			<td><br />Below you can find the options for adding text box, textarea, dropdown box, checkbox & radio button for creating your own forms. A popup window will appear and you can enter the Label you wish for that field. F.e if you want customer to enter his name then you can opt for a Text box. In the popup window enter the Label you wish f.e 'Your Name'. There is option to set whether this field is mandatory or not. If mandatory enter your error message to be shown while customer is filling the form.<br /><br />All these added fields will be listed in this page where there are options for you to set its sort order, alignments etc.</td>
			</tr></table>
		</td>
	</tr>
	<tr class="header">
		<td width="87%"><b>Custom <?=$section_type_array[$_REQUEST['form_type']]?> for section: <font color="#FF0000"><?=$row['section_name']?></font></b></td>
		<td width="13%"><input type="button" name="Preview" value="Preview" onclick="return open_preview()" class="smallsubmit">
	  &nbsp;<a href="#" style="cursor:pointer;" onmouseover="return overlib('Use this button to preview the current custom form to see how it will be displayed to the customer in enquiry section.',100,VAUTO);" onmouseout="return nd();"><img src="images/help_icon.gif" border="0" alt="" /></a></td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="4">
	
	<?php
	echo table_header($table_headers);
		$i = 0;
		$result = $db->query("select element_id,element_name,element_label,element_type,element_align,element_valign,sort_no,mandatory from elements where  site_id=$ecom_siteid and section_id=".$_REQUEST['section_id']." order by sort_no");
		
		while($rows = $db->fetch_array($result))
		{
			$count_no++;
		if($count_no %2 == 0)
			$class_val="maininnertabletd1";
		else
			$class_val="maininnertabletd2";	
	?>
	
		<tr class="<?=$class_val;?>">
			<td align="center">
				<input type="radio" name="rb_select" value="<?=$rows['element_id']?>" onclick="document.frmregistration_form.delete_id.value=<?=$rows['element_id']?>"> <input type="hidden" name="element_row_no<?=$i?>" value="<?=$rows['element_id']?>"><!-- The element id is stored in this hidden variable-->
			</td>
			<td align="center">
				<input type="text" name="label<?=$rows['element_id']?>" value="<?=$rows['element_label']?>" size="30">
				<input type="hidden" name="name<?=$rows['element_id']?>" value="<?=$rows['element_name']?>">
			</td>
			<td align="center">
				<?=$rows['element_type']?>
			</td>
			<td align="center">
				<select name="align<?=$rows['element_id']?>">
				<?php 
					foreach($halign_array as $v) 
					{
						if($v == $rows['element_align'])
						{
						?>
						<option value="<?=$v?>" selected><?=$v?></option>
						<?php
						}
						else
						{
						?>
						<option value="<?=$v?>"><?=$v?></option>
						<?php
						}
					}
				?>
				</select>
			</td>
			<td align="center">
				<select name="valign<?=$rows['element_id']?>">
				<?php 
					foreach($valign_array as $v) 
					{
						if($v == $rows['element_valign'])
						{
						?>
						<option value="<?=$v?>" selected><?=$v?></option>
						<?php
						}
						else
						{
						?>
						<option value="<?=$v?>"><?=$v?></option>
						<?php
						}
					}
				?>
				</select>
			</td>
			<td align="center">
				<input type="text" name="sort<?=$rows['element_id']?>" value="<?=$rows['sort_no']?>" size="2">
			</td>
			<td align="center">
				<table>
				<tr>
					<td>
				<?php if($rows['mandatory'] == 'Y') { ?>
				<input type="checkbox" name="mandatory[<?=$rows['element_id']?>]" value="1" checked>
				<?php } else { ?>
				<input type="checkbox" name="mandatory[<?=$rows['element_id']?>]" value="1">
				<?php } ?></td>
				<td>
				<input type="button" name="error_msg" value="ErrorMsg" onclick="window.open('add_error_msg.php?emt_id=<?=$rows['element_id']?>','win','width=300,height=150'); return false;" class="smallsubmit">
				  </td>
				</tr>
				</table>
			</td>
		</tr>
	<?php
			$i++;
		}
		if($i == 0)
		{
			?>
				<tr>
					<td colspan="7">No elements added</td>
				</tr>
			<?php
		}
	?>
</table>
<table width="100%">
<?php
	if($i > 0)
	{
?>
	<tr>
		<td align="center" colspan="2">
			<input type="submit" name="Update" value="Update" class="smallsubmit" onclick="show_processing();">
			<a href="#" style="cursor:pointer;" onmouseover="return overlib('Use <strong>Update</strong> button to save any changes made in the list.',100,VAUTO);" onmouseout="return nd();"><img src="images/help_icon.gif" border="0" alt="" /></a>&nbsp;&nbsp;
			<input type="button" name="Edit" value="Edit" onclick="open_elements('edit')" class="smallsubmit">
			<a href="#" style="cursor:pointer;" onmouseover="return overlib('Use <strong>edit</strong> button to edit the selected component in current form.',100,VAUTO);" onmouseout="return nd();"><img src="images/help_icon.gif" border="0" alt="" /></a>&nbsp;&nbsp;
			&nbsp;
			<input type="submit" name="Delete" value="Delete" onclick="return validate()" class="smallsubmit">
			<a href="#" style="cursor:pointer;" onmouseover="return overlib('Use <strong>delete</strong> button delete a component from the current form.',100,VAUTO);" onmouseout="return nd();"><img src="images/help_icon.gif" border="0" alt="" /></a>&nbsp;&nbsp; <br>
			<br>		</td>
	</tr>
<?php
	}
?>	
	<tr>
	  <td align="center" colspan="2">
			<input type="button" name="text" value="Add TextBox" onclick="open_elements('tb')" class="bigsubmit">
			<a href="#" style="cursor:pointer;" onmouseover="return overlib('Use <strong>Add TextBox</strong> button to add a new text box to the current form.',100,VAUTO);" onmouseout="return nd();"><img src="images/help_icon.gif" border="0" alt="" /></a>&nbsp;&nbsp;
		  &nbsp;
			<input type="button" name="textarea" value="Add TextArea" onclick="open_elements('ta')" class="bigsubmit">
			<a href="#" style="cursor:pointer;" onmouseover="return overlib('Use <strong>Add TextArea</strong> button to add a new textarea to the current form.',100,VAUTO);" onmouseout="return nd();"><img src="images/help_icon.gif" border="0" alt="" /></a>&nbsp;&nbsp;
		  &nbsp;
			<input type="button" name="radio" value="Add RadioButton" onclick="open_elements('rb')" class="bigsubmit"><a href="#" style="cursor:pointer;" onmouseover="return overlib('Use <strong>Add RadioButton</strong> button to add a new radio button group to the current form.',100,VAUTO);" onmouseout="return nd();"><img src="images/help_icon.gif" border="0" alt="" /></a>&nbsp;&nbsp;<br>
		<input type="button" name="checkbox" value="Add CheckBox" onclick="open_elements('cb')" class="bigsubmit">&nbsp;<a href="#" style="cursor:pointer;" onmouseover="return overlib('Use <strong>Add CheckBox</strong> button to add a new checkbox group to the current form.',100,VAUTO);" onmouseout="return nd();"><img src="images/help_icon.gif" border="0" alt="" /></a>&nbsp;&nbsp;
<input type="button" name="select" value="Add ComboBox" onclick="open_elements('sb')" class="bigsubmit"><a href="#" style="cursor:pointer;" onmouseover="return overlib('Use <strong>Add ComboBox</strong> button to add a new dropdown box to the current form.',100,VAUTO);" onmouseout="return nd();"><img src="images/help_icon.gif" border="0" alt="" /></a>&nbsp;&nbsp;</td>
	</tr>
	<tr class='subhead'>
		<td align="center">
			<font color="#ff0000"><?=$alert?></font>		</td>
	</tr>
</table>
<input type="hidden" name="no_of_elements" value="<?=$i?>"><!-- No of elements-->
<input type="hidden" name="delete_id">
<input type="hidden" name="section_id" value="<?=$_REQUEST['section_id']?>" />
<input type="hidden" name="form_type" value="<?=$_REQUEST['form_type']?>" />
</form>