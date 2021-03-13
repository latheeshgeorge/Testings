<?php
if($_REQUEST['rb_submit'] and (!$_REQUEST['emt_id']) and $_REQUEST['element_label'] ) // to add new value
{
	if(count($_SESSION['element_values']))
	{
	$flag_name = true;
	$result = $db->query("select max(sort_no)+1 as cnt from elements where sites_site_id=$ecom_siteid and element_sections_section_id=".$_REQUEST['section_id']);
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
	$sql_check =  "SELECT element_id FROM elements WHERE  sites_site_id=$ecom_siteid AND element_sections_section_id=".$_REQUEST['section_id']." AND element_label='".$element_label."' AND element_type='radio' LIMIT 1";
	$ret_check =  $db->query($sql_check);
	if($db->num_rows($ret_check)>0)
	{
	  $flag_name= false;
	}
	if($flag_name)
	{
		$sql_insert = "INSERT INTO elements SET sites_site_id='$ecom_siteid', element_label='$element_label', element_name='$element_name', element_valign='".$_REQUEST['valign']."', element_align='".$_REQUEST['align']."', element_type='radio', mandatory='$mandatory', error_msg='$error_msg', sort_no='".$row['cnt']."', element_sections_section_id=".$_REQUEST['section_id'];
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
						$db->query("INSERT INTO element_value SET elements_element_id=$insert_id,element_values='".$_POST["$i"]."', selected='".$selected."'");
					}		
				}
				$_SESSION['element_values'] = array();
				?>
				<script language="JavaScript">
				window.opener.location.href="../home.php?request=customform&fpurpose=manage_form&form_type=<?=$_REQUEST['form_type']?>&section_id=<?=$_REQUEST['section_id']?>";
				window.close();
				</script>
				<?php

	}
	else
	{
		$alert = "ERROR!..Element Name already exists";
	}
  }
  else
  {
    $alert = "Add Values for Radio Button";
  }
  
}
if($_REQUEST['rb_submit'] and $_REQUEST['emt_id'])
{
	if(count($_SESSION['element_values']))
	{
		$flag_name = true;
		$element_label = str_replace("'","",$_REQUEST['element_label']);
		$element_label = str_replace("\"","",$element_label);
		$error_msg = str_replace("\"","",$_REQUEST['error_msg']);
		$error_msg = str_replace("'","",$error_msg);
		if($error_msg and count($_SESSION['element_values']))// not to make mandatory if it does not hav any values
		{
			$mandatory = 'Y';
		}
		else
		{
			$mandatory = 'N';
		}
		$sql_check =  "SELECT element_id FROM elements WHERE  sites_site_id=$ecom_siteid AND element_sections_section_id=".$_REQUEST['section_id']." AND element_label='".$element_label."' AND element_id<>".$_REQUEST['emt_id']." AND element_type='radio' LIMIT 1";
		$ret_check =  $db->query($sql_check);
		if($db->num_rows($ret_check)>0)
		{
		  $flag_name= false;
		}
		if($flag_name)
		{
			$sql_insert = "UPDATE elements SET sites_site_id='$ecom_siteid', element_label='$element_label', element_valign='".$_REQUEST['valign']."', element_align='".$_REQUEST['align']."', mandatory='$mandatory', error_msg='$error_msg' WHERE sites_site_id=$ecom_siteid AND element_id=".$_REQUEST['emt_id'];
			$db->query($sql_insert);
			$db->query("DELETE FROM element_value WHERE elements_element_id=".$_REQUEST['emt_id']." ");
					
					for($i=0; $i<count($_SESSION['element_values']); $i++)
					{
						if($_REQUEST['rb_select'] == $i)
							$selected = 1;
						else
							$selected = 0;
						if(trim($_POST["$i"]) != "") {
							$db->query("INSERT INTO element_value SET elements_element_id=".$_REQUEST['emt_id'].",element_values='".$_POST["$i"]."', selected='$selected'");
						}	
					}
					$_SESSION['element_values'] = array();
					?>
					<script language="JavaScript">
					window.opener.location.href="../home.php?request=customform&fpurpose=manage_form&form_type=<?=$_REQUEST['form_type']?>&section_id=<?=$_REQUEST['section_id']?>";
					window.close();
					</script>
					<?php
		}
		else
		{
			$alert = "ERROR!..Element Name already exists";
		}
    }
	else
	{
	   $alert = "Add Values for Radio Button";
	}	
}
if($_REQUEST['add_values'])
{
	$_SESSION['element_values'][] = " ";
	//unset($_SESSION['element_values']);
}if(!$_REQUEST['cb_submit'] && !$_REQUEST['add_values'] ){
unset($_SESSION['element_values']);
}
?>
<?php
if($_REQUEST['emt_id'])
{
	$result = $db->query("select element_name,element_label,element_valign,element_align,error_msg from elements where element_id=".$_REQUEST['emt_id']);
	$row = $db->fetch_array($result);
	if(($_REQUEST['emt_id']) and (!$_REQUEST['add_values']))
	{
		$result = $db->query("select value_id,element_values,selected from element_value where elements_element_id=".$_REQUEST['emt_id']);
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
<script>
function valform(frm)
{
 var cnt =0; 
 var i =0;
 if(frm.element_label.value=='')
	{
	 alert('Enter the Label');
	 return false;
	}
	else
	{ 
		for(i=0;i<=frm.elements.length;i++)
		{ 
		 if (frm.elements[i].type =='text' && frm.elements[i].name!='element_label' && frm.elements[i].name!='error_msg')
			{ 
			 if(frm.elements[i].value=='')
				{  
					alert('Enter the value');
					return false;
				}
			}
		}
	}
}
</script>
<form name="frmrb" action="add_elements.php" method="POST" class="frmcls" onsubmit="return valform(this);">
<table width="100%" align="center" cellspacing="0" cellpadding="0">
	<tr >
		<td colspan="2" align="left" class="listingtableheader">
			<?php if($_REQUEST['emt_id']) echo "Edit Radio Button"; else echo "Add Radio Button"; ?>		</td>
	</tr>
	<tr >
		<td class="tdcolorgray">
			Label
		</td>
		<td class="tdcolorgray">
			<input type="text" name="element_label" value="<?php if($_REQUEST['element_label']) echo $_REQUEST['element_label']; else echo $row[element_label]; ?>" size="35">
		</td>
	</tr>
	<tr >
		<td class="tdcolorgray">
			Horizontal Align
		</td>
		<td class="tdcolorgray">
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
	<tr >
		<td class="tdcolorgray">
			Vertical Align
		</td>
		<td class="tdcolorgray">
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
	<tr >
		<td class="tdcolorgray" ><b>Values</b></td>
		<td class="tdcolorgray">
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
			echo "No values";
		}
	?>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center" class="tdcolorgray">
			<input type="submit" value="Add values" name="add_values" class="red">
		</td>
	</tr>
	<tr >
		<td class="tdcolorgray">
			Error Message
		</td>
		<td class="tdcolorgray">
			<input type="text" name="error_msg" value="<?php if($error_msg) echo $error_msg; else echo $row['error_msg']; ?>">
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center" class="tdcolorgray">
			<?php
			if($_REQUEST['emt_id'])
			{
		?>
			<input type="submit" name="rb_submit" value="Update" class="red">
			<?php
			}
			else
			{
			?>
			<input type="submit" name="rb_submit" value="Create" class="red" >
			<?php
			}
			?>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center" class="tdcolorgray">
			<font color="#ff0000"><?=$alert?></font>
		</td>
	</tr>
</table>
<input type="hidden" name="type" value="<?=$_REQUEST['type']?>">
<input type="hidden" name="emt_id" value="<?=$_REQUEST['emt_id']?>">
<input type="hidden" name="section_id" value="<?=$_REQUEST['section_id']?>" />
<input type="hidden" name="form_type" value="<?=$_REQUEST['form_type']?>" />
<input type="hidden" name="start" value="<?=$_REQUEST['start']?>" />
<input type="hidden" name="pg" value="<?=$_REQUEST['pg']?>" />
<input type="hidden" name="search_name" value="<?=$_REQUEST['search_name']?>" />
<input type="hidden" name="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
</form>
