<?php
/*Edit function is determined with the value $emt_id*/
if($_REQUEST['sb_submit'] and (!$_REQUEST['emt_id']))
{
	$flag_name = true;
	$result = $db->query("select max(sort_no)+1 as cnt from elements where site_id=$ecom_siteid and section_id=".$_REQUEST['section_id']);
	$row = $db->fetch_array($result);
	$element_name = 'e_'.uniqid(rand());
	$element_label = str_replace("'","",$_REQUEST['element_label']);
	$element_label = str_replace("\"","",$element_label);
	$error_msg = str_replace("\"","",$_REQUEST['error_msg']);
	$error_msg = str_replace("'","",$error_msg);
	if($error_msg)
	{
		$mandatory = 'Y';
	}
	else
	{
		$mandatory = 'N';
	}
	
	if($flag_name)
	{
		$sql_insert = "INSERT INTO elements SET site_id='$ecom_siteid', element_label='$element_label', element_name='$element_name', element_valign='".$_REQUEST['valign']."', element_align='".$_REQUEST['align']."', element_type='select', mandatory='$mandatory', error_msg='$error_msg', sort_no='".$row['cnt']."', section_id=".$_REQUEST['section_id'];
		$db->query($sql_insert);
			
				$insert_id = $db->insert_id();
				$element_id = $insert_id;
				for($i=0; $i<count($_SESSION['element_values']); $i++)
				{
					if($_REQUEST['rb_select'] == $i)
						$selected = 1;
					else
						$selected = 0;
					if(trim($_POST["$i"]) != "") {
						$db->query("INSERT INTO element_value SET element_id=$insert_id,element_values='".$_POST["$i"]."', selected='$selected'");
					}		
				}
				$_SESSION['element_values'] = array();
				?>
				<script language="JavaScript">
				window.opener.location.href="../home.php?request=customform&fpurpose=formcreation&section_id=<?=$_REQUEST['section_id']?>";
				window.close();
				</script>
				<?php

	}
	else
	{
		$alert = "ERROR!..Element Name already exists";
	}
}
if(($_REQUEST['sb_submit'] and $_REQUEST['emt_id']))
{
	$flag_name = true;
	$element_label = str_replace("'","",$_REQUEST['element_label']);
	$element_label = str_replace("\"","",$element_label);
	$error_msg = str_replace("\"","",$_REQUEST['error_msg']);
	$error_msg = str_replace("'","",$error_msg);
	if($error_msg)
	{
		$mandatory = 'Y';
	}
	else
	{
		$mandatory = 'N';
	}
	
	if($flag_name)
	{
		$sql_insert = "UPDATE elements SET site_id='$ecom_siteid', element_label='$element_label', element_valign='".$_REQUEST['valign']."', element_align='".$_REQUEST['align']."', mandatory='$mandatory', error_msg='$error_msg' WHERE site_id=$ecom_siteid AND element_id=".$_REQUEST['emt_id'];
		$db->query($sql_insert);
		$db->query("DELETE FROM element_value WHERE element_id=".$_REQUEST['emt_id']);
				
				for($i=0; $i<count($_SESSION['element_values']); $i++)
				{
					if($_REQUEST['rb_select'] == $i)
						$selected = 1;
					else
						$selected = 0;
					if(trim($_POST["$i"]) != "") {
						$db->query("INSERT INTO element_value SET element_id=".$_REQUEST['emt_id'].",element_values='".$_POST["$i"]."', selected='$selected'");
					}	
				}
				$_SESSION['element_values'] = array();
				?>
				<script language="JavaScript">
				window.opener.location.href="../home.php?request=customform&fpurpose=formcreation&section_id=<?=$_REQUEST['section_id']?>";
				window.close();
				</script>
				<?php
	}
	else
	{
		$alert = "ERROR!..Element Name already exists";
	}
}
if($_REQUEST['add_values'])
{
	$_SESSION['element_values'][] = " ";
}
?>
<?php
if($_REQUEST['emt_id'])
{
	$result = $db->query("select element_name,element_label,element_valign,element_align,error_msg from elements where element_id=".$_REQUEST['emt_id']);
		$row = $db->fetch_array($result);
	if(($_REQUEST['emt_id']) and (!$_REQUEST['add_values']))
	{
		$result = $db->query("select value_id,element_values,selected from element_value where element_id=".$_REQUEST['emt_id']);
			$_SESSION['element_values'] = array();
		while($row_values = $db->fetch_array($result))
		{
			if($row_values[selected] == 1)
				$selected_value = $row_values[element_values];
			$_SESSION['element_values'][] = $row_values[element_values];
		}
	}
}
$valign_array = array('top','bottom','middle');
$halign_array = array('left','right','center');
?>
<form name="frmrb" action="add_elements.php" method="POST">
<table width="100%" align="center">
	<tr class="subhead">
		<td colspan="2" align="center">
			<h1><?php if($_REQUEST['emt_id']) echo "Edit ComboBox"; else echo "Add ComboBox"; ?></h1>
		</td>
	</tr>
	<tr class="subhead">
		<td>
			Label
		</td>
		<td>
			<input type="text" name="element_label" value="<?php if($element_label) echo $element_label; else echo $row['element_label']; ?>" size="35">
		</td>
	</tr>
	<tr class="subhead">
		<td>
			Horizontal Align
		</td>
		<td>
			<select name="align">
				<?php 
					foreach($halign_array as $v) 
					{
						if($v == $align)
						{
						?>
						<option value="<?=$v?>" selected><?=$v?></option>
						<?php
						}
						else if($v == $row[element_align])
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
	</tr>
	<tr class="subhead">
		<td>
			Vertical Align
		</td>
		<td>
			<select name="valign">
				<?php 
					foreach($valign_array as $v) 
					{
						if($v == $valign)
						{
						?>
						<option value="<?=$v?>" selected><?=$v?></option>
						<?php
						}
						else if($v == $row[element_valign])
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
	</tr>
	<tr class="subhead">
		<td><b>Values</b></td>
		<td>
	<?php
		if(count($_SESSION['element_values']) > 0)
		{
			foreach($_SESSION['element_values'] as $k => $v)
			{
				?>
					<input type="text" name="<?=$k?>" value="<?php if(trim($v) != "") echo $v; else echo $_REQUEST[$k]; ?>">&nbsp;
					<?php
						if($selected_value == $v)
						{
					?>
						<input type="radio" name="rb_select" value="<?=$k?>" checked>Selected<br>
						<?php
						}
						else
						{
						?>
						<input type="radio" name="rb_select" value="<?=$k?>">Selected<br>
				<?php
						}
			}
		}
		else
		{
			echo "No Values";
		}
	?>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center">
			<input type="submit" value="Add Value" name="add_values" class="smallsubmit">
		</td>
	</tr>
	<tr class="subhead">
		<td>
			Error Message
		</td>
		<td>
			<input type="text" name="error_msg" value="<?php if($error_msg) echo $error_msg; else echo $row['error_msg']; ?>">
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center">
			<?php
			if($_REQUEST['emt_id'])
			{
		?>
			<input type="submit" name="sb_submit" value="Update" class="smallsubmit">
			<?php
			}
			else
			{
			?>
			<input type="submit" name="sb_submit" value="Create" class="smallsubmit">
			<?php
			}
			?>
		</td>
	</tr>
	<tr class="subhead">
		<td colspan="2" align="center">
			<font color="#ff0000"><?=$alert?></font>
		</td>
	</tr>
</table>
<input type="hidden" name="type" value="<?=$_REQUEST['type']?>">
<input type="hidden" name="emt_id" value="<?=$_REQUEST['emt_id']?>">
<input type="hidden" name="section_id" value="<?=$_REQUEST['section_id']?>" />
</form>
