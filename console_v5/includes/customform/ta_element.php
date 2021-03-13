<?php
if($_REQUEST['ta_submit'] and (!$_REQUEST['emt_id']))
{
	$flag_name = true;
	$result = $db->query("select max(sort_no)+1 as cnt from elements where site_id=$ecom_siteid and section_id=".$_REQUEST['section_id']);
	$row = $db->fetch_array($result);
	$element_name = 'e_'.uniqid(rand());
	$element_label = str_replace("'","",$_REQUEST['element_label']);
	$element_label = str_replace("\"","",$element_label);
	if((trim($_REQUEST['rows']) == "") or (!is_numeric($_REQUEST['rows'])))
		$rows = 4;
	else
		$rows = $_REQUEST['rows'];
	if((trim($_REQUEST['cols']) == "") or (!is_numeric($_REQUEST['cols'])))
		$cols = 30;
	else
		$cols = $_REQUEST['cols'];
	
		
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
		$sql_insert = "INSERT INTO elements SET site_id='$ecom_siteid', element_label='$element_label', element_name='$element_name', element_valign='".$_REQUEST['valign']."', element_align='".$_REQUEST['align']."', element_type='textarea', element_rows=$rows, element_cols=$cols, element_size='".$_REQUEST['size']."', mandatory='$mandatory', error_msg='$error_msg', sort_no='".$row['cnt']."', section_id=".$_REQUEST['section_id'];
		$db->query($sql_insert);
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
if($_REQUEST['ta_submit'] and $_REQUEST['emt_id'])
{
	$flag_name = true;
	
	if((trim($_REQUEST['rows']) == "") or (!is_numeric($_REQUEST['rows'])))
		$rows = 4;
	else
		$rows = $_REQUEST['rows'];
	if((trim($_REQUEST['cols']) == "") or (!is_numeric($_REQUEST['cols'])))
		$cols = 30;
	else
		$cols = $_REQUEST['cols'];
		
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
			$sql_insert = "UPDATE elements SET element_label='$element_label',  element_valign='".$_REQUEST['valign']."', element_align='".$_REQUEST['align']."', element_size='".$_REQUEST['size']."', mandatory='$mandatory', element_rows=$rows, element_cols=$cols, error_msg='$error_msg' WHERE site_id='$ecom_siteid' AND element_id=".$_REQUEST['emt_id'];
		$db->query($sql_insert);
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
if($_REQUEST['emt_id']) {
$result =$db->query("select element_name,element_label,element_valign,element_align,element_rows,element_cols,error_msg from elements where element_id=".$_REQUEST['emt_id']);
	$row = $db->fetch_array($result);
}
$valign_array = array('top','bottom','middle');
$halign_array = array('left','right','center');
?>
<form name="frmtb" action="" method="POST">
<table width="100%" align="center">
	<tr class="subhead">
		<td colspan="2" align="center">
			<h1><?php if($_REQUEST['emt_id']) echo "Edit Text Area"; else echo "Add Text Area"; ?></h1>
		</td>
	</tr>
	<tr class="subhead">
		<td>
			Label
		</td>
		<td>
			<input type="text" name="element_label" value="<?php if($element_label) echo $element_label; else echo $row[element_label]; ?>" size="35">
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
		<td>
			Rows
		</td>
		<td>
			<input type="text" name="rows" value="<?php if($rows) echo $rows; else if($row[element_rows]) echo $row[element_rows]; else echo "4";?>" size="2">
		</td>
	</tr>
	<tr class="subhead">
		<td>
			Cols
		</td>
		<td>
			<input type="text" name="cols" value="<?php if($cols) echo $cols; else if($row[element_cols]) echo $row[element_cols]; else echo "30";?>" size="2">
		</td>
	</tr>
	<tr class="subhead">
		<td>
			Error Message
		</td>
		<td>
			<input type="text" name="error_msg" value="<?php if($error_msg) echo $error_msg; else echo $row[error_msg]; ?>">
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center">
			<?php
			if($_REQUEST['emt_id'])
			{
			?>
			<input type="submit" name="ta_submit" value="Update" class="smallsubmit">
			<?php
			}
			else
			{
			?>
			<input type="submit" name="ta_submit" value="Create" class="smallsubmit">
			<?php
			}
			?>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center" class="subhead">
			<font color="#ff0000"><?=$alert?></font>
		</td>
	</tr>
</table>
<input type="hidden" name="type" value="<?=$_REQUEST['type']?>">
<input type="hidden" name="emt_id" value="<?=$_REQUEST['emt_id']?>">
<input type="hidden" name="section_id" value="<?=$_REQUEST['section_id']?>" />
</form>
