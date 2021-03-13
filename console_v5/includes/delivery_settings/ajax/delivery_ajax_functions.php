<?PHP
	function show_country_list($locid,$delid,$alert='')
	{
		global $db,$ecom_siteid,$ecom_site_delivery_location_country_map;
		if ($ecom_site_delivery_location_country_map==1)
		{
	?>
			<table width="80%" cellpadding="1" cellspacing="1" border="0" align="right">
			<tr>
				<td class="seperationtd" colspan="3">Countries Assigned to this Location&nbsp;&nbsp;&nbsp;<input type='button' name='assign_more' value='Assign More' class="red" onclick="window.location='home.php?request=delivery_settings&fpurpose=assign_country&deliveryid=<?=$delid?>&locationid=<? echo $locid;?>'"/></td>
			</tr>
			<?php 
			if($alert!='')
			{
			?>
				<tr  id="error_tr">
					<td class='errormsg' colspan="3" align="center"><?php echo $alert?></td>
				</tr>	
			<?php
			} 
			// Get the list of countries assigned to this delivery location
			$sql_country = "SELECT country_id,country_name,general_settings_site_country_country_id  
								FROM 
									general_settings_site_country a,general_settings_site_country_location_map b
								WHERE 
									b.delivery_site_location_location_id = ".$locid." 
									AND b.delivery_methods_deliverymethod_id='".$delid."' 
									AND a.country_id=b.general_settings_site_country_country_id ORDER BY country_name";
			$ret_country = $db->query($sql_country);
			if ($db->num_rows($ret_country))
			{
				$cur_col = 0;
				$max_col = 3;
				?>
				<tr>
					<td colspan="2" align="left"><img src="images/checkbox.gif" onclick="select_all_chk('checkbox_<?php echo $locid?>[]')" border="0"><img src="images/uncheckbox.gif" onclick="select_none_chk('checkbox_<?php echo $locid?>[]')" border="0"></td>
					<td align="right"><input type="button" name="unassign_country" value="Unassign Selected Countries" class="red" onclick="country_unassign('checkbox_<?php echo $locid?>[]','<?php echo $locid?>','<?php echo $delid?>')"/></td>
				</tr>
				<?php
				while ($row_country = $db->fetch_array($ret_country))
				{
					if($cur_col==0)
						echo '<tr>';
			?>
					<td class="listingtablestyleB" align='left' style="border-left:solid 1px #000000;border-bottom:solid 1px #000000;padding:1px;" width="30%"><input type="checkbox" value="<?php echo $row_country['general_settings_site_country_country_id']?>" id="checkbox_<?php echo $locid?>[]" name="checkbox_<?php echo $locid?>[]"> &nbsp;<?php echo stripslashes($row_country['country_name'])?></td>

			<?php	
					$cur_col++;
					if($cur_col>=$max_col)
					{
						$cur_col = 0;
						echo '</tr>';
					}
				}
				if($cur_col>0 and $cur_col<$max_col)
					echo '<td class="listingtablestyleB" colspan="'.($max_col-$cur_col).'"style="border-left:solid 1px #000000;border-bottom:solid 1px #000000;padding:1px">&nbsp;</td></tr>';
			}
			else
			{						
			?>
			<tr>
				<td class="errormsg" colspan="3" align='center'>- No Countries Assigned Yet - </td>
			</tr>
			<?php
			}
			?>
			</table>
	<?php
		}	
	}
	function show_delivery_maininfo($locid,$delid,$alert='')
	{
	global $db,$ecom_siteid;
		$table_name			= 'delivery_site_option_details';

	$sqlloc="SELECT location_name,location_id,location_free_delivery,location_tax_applicable,location_free_delivery_subtotal FROM delivery_site_location where delivery_methods_deliverymethod_id=".$delid . " AND sites_site_id =". $ecom_siteid." AND location_id=".$locid;
		$resloc = $db->query($sqlloc);
		$rowloc = $db->fetch_array($resloc);
		
		// Check whether any delivery methods groups exists for this site
		
		/*$sql_check 		= "SELECT delivery_group_id,delivery_group_name FROM general_settings_site_delivery_group WHERE sites_site_id=$ecom_siteid AND delivery_group_hidden=0";
		$ret_check 		= $db->query($sql_check);*/
		if(!$group_exists)
		{
			$sql="SELECT * FROM $table_name where delivery_methods_deliverymethod_id=".$_REQUEST['deliveryid'] . " AND sites_site_id =". $ecom_siteid." AND delivery_site_location_location_id=".$locid." ORDER BY delopt_option";
			$res= $db->query($sql);
		}	
		
		$sqllocfree = "SELECT allow_free_delivery_location  FROM general_settings_sites_common WHERE sites_site_id =". $ecom_siteid;
		$reslocfree = $db->query($sqllocfree);
		$rowlocfree = $db->fetch_array($reslocfree);
if($rowlocfree['allow_free_delivery_location'] > 0)
	{
		$show_subtotal	=	"showsubtotal();";
?>
<script language="javascript">
function showsubtotal()
{
	var $nc = jQuery.noConflict();
	if($nc('#location_free_delivery').attr('checked'))
	{
		$nc("#show_subtotal").show();
	}
	else
	{
		$nc("#show_subtotal").hide();
	}
}
</script>
<?php
	}
	else
	{
		$show_subtotal	=	"";
	}
	if($rowloc['location_free_delivery']==1)
	{	$hide_div	=	"";	}
	else
	{	$hide_div	=	"display:none;";	}
?>
		            <div class="editarea_div">

	<table width="100%" border="0" cellpadding="4" cellspacing="0">
		
		<tr>
	  <td align="left" style="width:35%;"><b>Location  :</b>  <input name="location" type="text" value="<? echo $rowloc['location_name'] ?>" size="50"></td><td  style="width:18%;" align='left'><input type="checkbox" onclick="javascript: handle_change_free_delivery(); <?php echo $show_subtotal;?>" name="location_free_delivery" id="location_free_delivery" value="1" <?php echo ($rowloc['location_free_delivery']==1)?'checked="checked"':''?>/>Allow Free Delivery? <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_SETTINGS_DELIVERY_FREE_DELIVERY')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.gif" width="17" height="13" border="0" /></a><div><input type="checkbox" name="location_tax_applicable" id="location_tax_applicable" value="1" <?php echo ($rowloc['location_tax_applicable']==1)?'checked="checked"':''?>/>Apply Tax? <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_SETTINGS_DELIVERY_APPLY_TAX')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.gif" width="17" height="13" border="0" /></a></div></td>
      <td align="left" style="width:40%;">
		<?php if($rowlocfree['allow_free_delivery_location'] > 0) { ?>
            <div style="float:left; <?php echo $hide_div; ?>" id="show_subtotal"><b>The minimum subtotal to allow free delivery :</b>
            	<input name="location_free_delivery_subtotal" size="10" type="text" value="<?php echo $rowloc['location_free_delivery_subtotal']; ?>" />
            </div>
        <?php } ?>
    </td>
	</tr>
	<tr id='free_msg' style="display:<?php echo ($rowloc['location_free_delivery']==1)?'none':'none'?>">
	<td align='center' colspan="4" ><span class="redtext">Charge set for this location (if any) will be ignored as free delivery is ticked</span></td>
	</tr>
	<?php // } 
		if($group_exists==false) // case if delivery groups does not exists
		{
	?>
	
  <?
		  $row=$db->fetch_array($res);
		   ?>
		   <tr>
		  <td align="right" width="238"> 
		   	Charge (<?php echo  display_curr_symbol()?>)
			</td>
			  	<td align="left" width="704"> 
					<input name="price[]" class="input" type="text" size="5" value="<? echo $row['delopt_price']?>">
			 </td>
		  </tr> 
	            
          <tr id="largebuttonlink">
				<td align="center">&nbsp;<input type="button" value="Submit Rates" class="red"  onClick="valform()"></td>
				<td align="left">
				  </td>
			</tr>
	
	  	</table>
			</div>
	<?php
	}
	else
	{
		while ($row_check = $db->fetch_array($ret_check))
		{
			$curgroup_active = 0;
			$sql_first	= "SELECT delivery_group_active_in_location FROM $table_name where delivery_group_id=".$row_check['delivery_group_id']." 
									AND delivery_methods_deliverymethod_id=".$_REQUEST['deliveryid'] . " 
									AND delivery_site_location_location_id = ".$_REQUEST['locationid']." 
									AND sites_site_id =". $ecom_siteid." ORDER BY  delopt_option LIMIT 1";
			$ret_first  = $db->query($sql_first);
			if($db->num_rows($ret_first))
			{
				$row_first = $db->fetch_array($ret_first);
				$curgroup_active = $row_first['delivery_group_active_in_location']; 
			}
?>
			<tr>
				<td colspan="4" align="left" valign="middle" class="seperationtd" >
				<?php echo stripslashes($row_check['delivery_group_name']); if ($ecom_site_delivery_location_country_map==1){?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='checkbox' value='1' name='group_active_<?php echo $row_check['delivery_group_id']?>' <?php echo ($curgroup_active==1)?'checked':''?>>Tick to Activate this group under this location<?php }?>				</td>
			</tr>
			<tr>
			  <td align="center" width="238"><b></b></td>
			  <td align="left" width="704"><b></b></td>
			</tr>
  <?
			$k			= 0;
			$sql 		= "SELECT * FROM $table_name where delivery_group_id=".$row_check['delivery_group_id']." 
							AND delivery_methods_deliverymethod_id=".$_REQUEST['deliveryid'] . " 
							AND delivery_site_location_location_id = ".$_REQUEST['locationid']." 
							AND sites_site_id =". $ecom_siteid." ORDER BY delopt_option";
			$res 		= $db->query($sql);
		  $row = $db->fetch_array($res);
		
			   	$option=$row['delopt_option'];
				//list($big,$small) = split('[.]',$option);
				  
			   ?>
			   <tr>
			  <td align="right" width="238">Charges (<?php echo  display_curr_symbol()?>)</td>
				  <td align="left" width="704"> 
				<input name="price_<?php echo $row_check['delivery_group_id']?>[]" class="input" type="text" size="5" value="<? echo $row['delopt_price']?>">				  </td>
		  </tr> 
		  <?
			 			
				
			}	
	?>
			
			</table>
			</div>
				            <div class="editarea_div">
	<table width="100%" border="0" cellpadding="4" cellspacing="0">
 	           
          <tr id="largebuttonlink">
				<td align="center">
    				<input type="hidden" name="action" value="Submit Rates" />
                    <input type="button" value="Submit Rates" class="red"  onClick="valform()">
                </td>
				<td align="left">
				  </td>
			</tr>
 	</table>
</div>
<?php	
	}
	?>	
	<?php	
	}
	function show_delivery_maininfo_amount($locid,$delid,$alert='',$sql_check)
	{
	global $db,$ecom_siteid,$ecom_site_delivery_location_country_map;
		$table_name			= 'delivery_site_option_details';
	$group_exists = false;
	$ret_check 			= $db->query($sql_check);
			$main_html 		= Build_main_delivery_dropdown_html();

	
	if($db->num_rows($ret_check))
	{
		$group_exists = true;
	}
	  if(!$group_exists)
		{
			$sql="SELECT * FROM $table_name where delivery_methods_deliverymethod_id=".$delid . " AND sites_site_id =". $ecom_siteid." AND delivery_site_location_location_id=".$locid." ORDER BY delopt_option";
			$res= $db->query($sql);
		}
	   $sqlloc="SELECT location_name,location_id,location_free_delivery,location_tax_applicable,location_free_delivery_subtotal FROM delivery_site_location where delivery_methods_deliverymethod_id=".$delid . " AND sites_site_id =". $ecom_siteid." AND location_id=".$locid;
		$resloc = $db->query($sqlloc);
		$rowloc = $db->fetch_array($resloc);	
		
		$sqllocfree = "SELECT allow_free_delivery_location  FROM general_settings_sites_common WHERE sites_site_id =". $ecom_siteid;
		$reslocfree = $db->query($sqllocfree);
		$rowlocfree = $db->fetch_array($reslocfree);
		
		// Check whether any delivery methods groups exists for this site
		
		/*$sql_check 		= "SELECT delivery_group_id,delivery_group_name FROM general_settings_site_delivery_group WHERE sites_site_id=$ecom_siteid AND delivery_group_hidden=0";
		$ret_check 		= $db->query($sql_check);*/
			
if($rowlocfree['allow_free_delivery_location'] > 0)
	{
		$show_subtotal	=	"showsubtotal();";
?>
<script language="javascript">
function showsubtotal()
{
	var $nc = jQuery.noConflict();
	if($nc('#location_free_delivery').attr('checked'))
	{
		$nc("#show_subtotal").show();
	}
	else
	{
		$nc("#show_subtotal").hide();
	}
}
</script>
<?php
	}
	else
	{
		$show_subtotal	=	"";
	}
	if($rowloc['location_free_delivery']==1)
	{	$hide_div	=	"";	}
	else
	{	$hide_div	=	"display:none;";	}
?>
	            <div class="editarea_div">

	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		
		<tr>
	  <td align="left" style="width:35%;"><b>Location  :</b>  <input name="location" type="text" value="<? echo $rowloc['location_name'] ?>" size="50"></td><td align='left' style="width:18%;"><input type="checkbox" onclick="javascript: handle_change_free_delivery(); <?php echo $show_subtotal;?>" name="location_free_delivery" id="location_free_delivery" value="1" <?php echo ($rowloc['location_free_delivery']==1)?'checked="checked"':''?>/>Allow Free Delivery? <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_SETTINGS_DELIVERY_FREE_DELIVERY')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.gif" width="17" height="13" border="0" /></a><div><input type="checkbox" name="location_tax_applicable" id="location_tax_applicable" value="1" <?php echo ($rowloc['location_tax_applicable']==1)?'checked="checked"':''?>/>Apply Tax? <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_SETTINGS_DELIVERY_APPLY_TAX')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.gif" width="17" height="13" border="0" /></a></div></td>
      <td align="left" style="width:40%;">
		<?php if($rowlocfree['allow_free_delivery_location'] > 0) { ?>
            <div style="float:left; <?php echo $hide_div; ?>" id="show_subtotal"><b>The minimum subtotal to allow free delivery :</b>
            	<input name="location_free_delivery_subtotal" size="10" type="text" value="<?php echo $rowloc['location_free_delivery_subtotal']; ?>" />
            </div>
        <?php } ?>
    </td>
	</tr>
	<tr id='free_msg' style="display:<?php echo ($rowloc['location_free_delivery']==1)?'none':'none'?>">
	<td align='center' colspan="4" ><span class="redtext">Charge set for this location (if any) will be ignored as free delivery is ticked</span></td>
	</tr>
	<?php // } 
		if($group_exists==false) // case if delivery groups does not exists
		{
	?>
          <tr>
            <td align="center" width="60%"><b>Option</b></td>
            <td align="center" width="117"><b>Charges (<?php echo  display_curr_symbol()?>)</b></td>
          </tr>
          <?
    $k=0;
  while($row = $db->fetch_array($res))
  {
   	$option=$row['delopt_option'];
    //list($big,$small) = split('[.]',$option);
	$big = $option;
	$k++;
	if($k==1)
	{
   		$msg='More than zero but less than ';
	}
	else
	{
    	$msg='More than previous but less than';
	}
		
   ?>
          <tr>
            <td align="right" width="329"><? echo $msg; ?>
                <select name="del_optionbig[]" class="input">
                  <option value=''></option>
                  <? /*for($i=$othmin ;$i<=$othmax; $i+=$othinc){  ?>
                  <option value="<? echo $i; ?>"<? if($big==$i) echo "selected"; ?>><? echo $i; ?></option>
                  <? }*/
				  Build_main_delivery_dropdown($big,$main_html);?>
                </select>            </td>
            <td align="center" width="117"><input name="price[]" class="input" type="text" size="5" value="<? echo $row['delopt_price']?>" />            </td>
          </tr>
          <?
  }
  
   for($j=0; $j<5; $j++){ 
   if($j==0 && $k==0){
   $msg='More than zero but less than ';
   }else{
    $msg='More than previous but less than';}
    ?>
          <tr>
            <td align="right" width="329"><? echo $msg;?>
                <select name="del_optionbig[]" class="input">
                  <option value=''></option>
                  <? /* for($i=$othmin ;$i<=$othmax; $i+=$othinc){  ?>
                  <option value="<? echo $i; ?>"><? echo $i; ?></option>
                  <? }*/
				  Build_main_delivery_dropdown('',$main_html);?>
                </select>            </td>
            <td align="center" width="117"><input name="price[]" class="input" type="text" size="5" />            </td>
          </tr>
          <? } ?>
          <tr id="largebuttonlink">
            <td align="center">&nbsp;
                <input name="button" type="button" class="red"  onclick="valform()" value="Submit Rates" /></td>
            <td align="left"><input name="button" type="button" class="red"  onclick="valform('more')" value="Submit and enter more rates" /></td>
          </tr>
          </table>
          </div>
          <?php
	}
	else
	{
		while ($row_check = $db->fetch_array($ret_check))
		{
			$curgroup_active = 0;
			$sql_first	= "SELECT delivery_group_active_in_location FROM $table_name where delivery_group_id=".$row_check['delivery_group_id']." 
									AND delivery_methods_deliverymethod_id=".$delid . " 
									AND delivery_site_location_location_id = ".$locid." 
									AND sites_site_id =". $ecom_siteid." ORDER BY  delopt_option LIMIT 1";
			$ret_first  = $db->query($sql_first);
			if($db->num_rows($ret_first))
			{
				$row_first = $db->fetch_array($ret_first);
				$curgroup_active = $row_first['delivery_group_active_in_location']; 
			}
?>
			<tr>
				<td colspan="4" align="left" valign="middle" class="seperationtd" >
				<?php echo stripslashes($row_check['delivery_group_name']); if ($ecom_site_delivery_location_country_map==1){?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='checkbox' value='1' name='group_active_<?php echo $row_check['delivery_group_id']?>' <?php echo ($curgroup_active==1)?'checked':''?>>Tick to Activate this group under this location<?php }?>				</td>
			</tr>
			<tr>
			  <td align="center" width="238"><b></b></td>
			  <td align="left" width="704"><b></b></td>
			</tr>
  <?
			$k			= 0;
			$sql 		= "SELECT * FROM $table_name where delivery_group_id=".$row_check['delivery_group_id']." 
							AND delivery_methods_deliverymethod_id=".$delid . " 
							AND delivery_site_location_location_id = ".$locid." 
							AND sites_site_id =". $ecom_siteid." ORDER BY delopt_option";
			$res 		= $db->query($sql);
	while($row=$db->fetch_array($res))
  {
	$option=$row['delopt_option'];
	//list($big,$small) = split('[.]',$option);
	$big = $option;
  	$k++;
  	if($k==1)
	{
   		$msg='More than zero but less than ';
	}
	else
	{
    	$msg='More than previous but less than';
	}
   ?>
          <tr>
            <td align="right" width="329"><? echo $msg; ?>
                <select name="del_optionbig_<?php echo $row_check['delivery_group_id']?>[]" class="input">
                  <option value=''></option>
                  <? /* for($i=$othmin ;$i<=$othmax; $i+=$othinc){ ?>
                  <option value="<? echo $i; ?>"<? if($big==$i) echo "selected"; ?>><? echo $i; ?></option>
                  <? }*/
				  Build_main_delivery_dropdown($big,$main_html);?>
                </select>            </td>
            <td align="center" width="117"><input name="price_<?php echo $row_check['delivery_group_id']?>[]" class="input" type="text" size="5" value="<? echo $row['delopt_price']?>" />            </td>
          </tr>
          <?
  }
  
   for($j=0; $j<5; $j++)
   { 
		if($j==0 && $k==0)
		{
		   $msg='More than zero but less than ';
		}
		else
		{
		    $msg='More than previous but less than';
		}
    ?>
          <tr>
            <td align="right" width="329"><? echo $msg;?>
                <select name="del_optionbig_<?php echo $row_check['delivery_group_id']?>[]" class="input">
                  <option value=''></option>
                  <?  /*for($i=$othmin ;$i<=$othmax; $i+=$othinc){  ?>
                  <option value="<? echo $i; ?>"><? echo $i; ?></option>
                  <? }*/
				  Build_main_delivery_dropdown('',$main_html);?>
                </select>            </td>
            <td align="center" width="117"><input name="price_<?php echo $row_check['delivery_group_id']?>[]" class="input" type="text" size="5" />            </td>
          </tr>
          <? 
	}
				
			}	
	?>
	</table>
		</div>
		
        <div class="editarea_div">
				    <table width="100%" border="0" cellspacing="0" cellpadding="0">	     
          <tr id="largebuttonlink">
				<td align="center">
    				<input type="hidden" name="action" value="Submit Rates" />
                    <input type="button" value="Submit Rates" class="red"  onClick="valform()">
                </td>
				<td align="left">
				  </td>
			</tr> 	 
 	   </table>
 	   </div>
 	  
<?php	
	}
	?>	
	<?php	
	}
	function show_date_time($locid,$delid,$datetimeapp,$alert='')
	{
		global $db,$ecom_siteid;
		//if($datetimeapp == 1)
		//{
			$sql_date	=	"SELECT				date
									FROM		delivery_location_date_map 
									WHERE		location_location_id = ".$locid." 
									AND			sites_site_id =". $ecom_siteid." 
									AND			date >= curdate()
									ORDER BY 	date ASC";
			$ret_date	=	$db->query($sql_date);
			if($db->num_rows($ret_date) > 0)
			{
				$dateJS		=	"";
				$dateJS		.=	"addDates: [";
				while($row_date = $db->fetch_array($ret_date))
				{
					$dateArr	=	array();
					$dateArr	=	explode("-",$row_date['date']);
					//echo "<pre>";print_r($dateArr);
					$dateJS		.=	"'".$dateArr[1]."/".$dateArr[2]."/'+y, ";
				}
				$dateJS		=	substr($dateJS, 0, -2);
				$dateJS		.=	"]";
			}
			
			$sql_time	=	"SELECT				*
									FROM		delivery_location_time_map
									WHERE		location_location_id = ".$locid." 
									AND			sites_site_id =".$ecom_siteid."
									ORDER BY	sort_order ASC
							";//echo $sql_time;echo "<br>";
			$ret_time	=	$db->query($sql_time);
		//}
?>
<script language="javascript">
function showDateTimeSettings()
{
	if(document.getElementById('location_datetime_applicable').checked)
	{
		document.getElementById('DateTimeSettings').style.display	=	'block';
	}
	else
	{
		document.getElementById('DateTimeSettings').style.display	=	'none';
	}
}
</script>
	            <div class="editarea_div">

<table width="100%" border="0" cellpadding="4" cellspacing="0">
<tr>
	<td width="100%" height="10" align="left">
    	<input type="checkbox" name="location_datetime_applicable" id="location_datetime_applicable" value="1" <?php if($datetimeapp == 1) echo "checked='checked'";?> onclick="javascript: showDateTimeSettings();" /> Enable date time settings for this location?
    </td>
</tr>
<tr>
	<td width="100%" align="left" valign="top">
    <div id="DateTimeSettings" style="display:none;">
    	<table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td align="left" width="25%" valign="top">
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td align="left"><div id="multipleDate"></div><br><input type="hidden" id="datesField" name="datesField" readonly="readonly"></td>
                </tr>
                </table>
            </td>
            <td align="left" width="75%" valign="top">
                <table width="100%" border="0" cellpadding="4" cellspacing="0">
				<?php
                    $table_headers = array('Slno.','Time','Sort Order');
                    $header_positions=array('center','left','left');
                    $colspan = count($table_headers);
                    echo table_header($table_headers,$header_positions); 
                    $cnt = 1;
                    if($db->num_rows($ret_time) > 0)
                    {
                        while ($row_time = $db->fetch_array($ret_time))
                        {
                ?>
                <input type="hidden" name="time_id[]" value="<?php echo $row_time['id']?>" />
                <tr>
                    <td width="10%" align="center"><?php echo ($cnt++)?>.</td>
                    <td align="left" width="60%">
                        <input type="text" name="time_value[]" size="60" value="<?php echo stripslashes($row_time['time'])?>" />
                    </td>
                    <td align="left" width="40%">
                        <input type="text" name="time_order[]" size="10" value="<?php echo stripslashes($row_time['sort_order'])?>" />
                    </td>
                </tr>
                <?php		
                        }	
                    }
                    for($i=0;$i<3;$i++)
                    {
                 ?>
                 <tr>
                    <td width="10%" align="center"><?php echo ($cnt+$i)?>.</td>
                    <td align="left" width="60%">
                        <input type="text" name="time_value[]" size="60" value="" />
                    </td>
                    <td align="left" width="40%">
                        <input type="text" name="time_order[]" size="10" value="" />
                    </td>
                </tr>
                 <?php
                    }
                 ?>
                </table>
            </td>
        </tr>
        </table>
        </div>
	</td>
</tr>
</table>
		</div>
		
        <div class="editarea_div">
				    <table width="100%" border="0" cellspacing="0" cellpadding="0">	
<tr>
	<td align="center">
    	<input type="hidden" name="action" value="Submit Dates" />
    <input type="submit" value="Submit Dates" class="red"></td>
</tr>
</table>
</div><?php
		if($datetimeapp == 1)
		{
?>	<script language="javascript">document.getElementById('DateTimeSettings').style.display	=	'block';</script>
<?php	}
		//if($datetimeapp == 1)
		//{
?>	<script language="javascript">
		var today = new Date();
		var y = today.getFullYear();
		$('#multipleDate').multiDatesPicker({
			altField: '#datesField',
			beforeShowDay: $.datepicker.noSundays,
			maxPicks: 120,
			<?php echo $dateJS;?>
			//beforeShowDay: $.datepicker.noWeekends,
			//addDisabledDates: ['05/29/'+y],
			//addDates: ['05/10/'+y, '05/20/'+y, '06/12/'+y, '04/12/'+y],
			//numberOfMonths: [3,1],
		});
	</script>
<?php	//}
	}
?>	 	
	
