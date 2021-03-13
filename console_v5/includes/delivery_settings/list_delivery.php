<?php
	/*#################################################################
	# Script Name 	: list_pricedisplay.php
	# Description 	: Page for listing Product Vendors
	# Coded by 		: LTSH
	# Created on	: 25-Oct-2007
	# Modified by	: 
	# Modified by	: LSH
	# Modified On	: 17-Jan-2012
	#################################################################*/
//Define constants for this page
$table_name='delivery_methods';
$page_type='Delivery Charges';
$help_msg = get_help_messages('LIST_DELIVERY_MESS1');
$sql="SELECT * FROM $table_name order by deliverymethod_order ";
$res= $db->query($sql);

// Check whether the required options required for delivery are set from delivery settings section if not show the link to that page to set it
$sql_common = "SELECT delivery_settings_weight_min_limit, delivery_settings_weight_max_limit, delivery_settings_weight_increment, 
					delivery_settings_common_min, delivery_settings_common_max, delivery_settings_common_increment 
				FROM 
					general_settings_sites_common 
				WHERE 
					sites_site_id = $ecom_siteid 
				LIMIT 
					1";
$ret_common = $db->query($sql_common);
if ($db->num_rows($ret_common))
{
	$row_common = $db->fetch_array($ret_common);
	if($row_common['delivery_settings_common_max']==0 or $row_common['delivery_settings_common_increment']==0)
	{
		echo "<br><br><span class='redtext'><b>Delivery settings are not properly set</b></span><br><br> <a href='home.php?request=delivery_settings_more' class='smalllink'>Please click here to go to the delivery settings page</a>";
		exit;
	}
}

?>
<form name="frmlistDelivery" action="home.php?request=delivery_settings" method="post" >	
<input type="hidden" name="fpurpose" value="delivery_settings" />
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span> Delivery Charges</span></div></td>
        </tr>
        <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="3">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
		<? if($submit){
		
		  $alert = "Changes Saved Successfully";?>
		 <tr>
          <td colspan="3" align="center" valign="middle" class="errormsg" ><?=$alert ?></td>
        </tr>
		<? }?>
		<tr>
          <td colspan="3" align="left" valign="top">
		  <div class="editarea_div">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
         <tr>
          <td width="47%" align="left" valign="middle" class="sorttd" ><b>Delivery Method:</b></td>
         
          <td colspan="2" align="right" valign="middle" class="sorttd" ><a href="home.php?request=delivery_settings&fpurpose=list_delmethod_groups" class="edittextlink" title="Click here to manage delivery method groups"><b>Manage Delivery Method Groups</b></a><a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_DELIVERY_SETIINGS_METHODGROUP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		<?
		$sqldeli="SELECT * FROM general_settings_site_delivery WHERE sites_site_id=$ecom_siteid";
		$resdeli= $db->query($sqldeli);
    	$rowdeli= $db->fetch_array($resdeli);
		if(!$db->num_rows($resdeli)){
		$sqlnone="SELECT deliverymethod_id FROM delivery_methods where deliverymethod_text ='None' ";
		$resnone= $db->query($sqlnone);
    	$rownone= $db->fetch_array($resnone);
		$rowdeli['delivery_methods_delivery_id']=$rownone['deliverymethod_id'];
		}
		while($row=$db->fetch_array($res)){
		 //echo $row['deliverymethod_id'];
		//echo $row['deliverymethod_id'];
		$loc_check_req = 0;
		if($row['deliverymethod_location_required']==1 and ($row['deliverymethod_id']==$rowdeli['delivery_methods_delivery_id']))
		{
			// Check whether delivery location added 
			$sql_loc_cnt = "SELECT count(location_id) FROM delivery_site_location WHERE sites_site_id = $ecom_siteid AND delivery_methods_deliverymethod_id =".$rowdeli['delivery_methods_delivery_id'];
			$ret_loc_cnt = $db->query($sql_loc_cnt);
			list($loc_cnts) = $db->fetch_array($ret_loc_cnt);	
			$loc_check_req = 1;
		}
		$del_mess=get_help_messages('LIST_DELIVERY_SETIINGS_DELIVERY_METHOD');
		$del_rep_mess=str_replace('[del_meth]',$row['deliverymethod_name'],$del_mess);
		$del_rep_mess_set=str_replace('[del_meth]',$row['deliverymethod_name'],get_help_messages('LIST_DELIVERY_SETIINGS_DELIVERY_METHOD_SET_VAL'));
        $del_rep_mess_edit_loc=str_replace('[del_meth]',$row['deliverymethod_name'],get_help_messages('LIST_DELIVERY_SETIINGS_DELIVERY_METHOD_EDIT_LOCATION'));
  
		?>
		  <tr>
          <td colspan="3" align="left" valign="middle" class="tdcolorgray" >
		  <input class="input" type="radio" name="delivery_id"  value="<? echo $row['deliverymethod_id'];?>" <? if($rowdeli['delivery_methods_delivery_id']==$row['deliverymethod_id']) echo "checked"; ?>/> <? echo $row['deliverymethod_name'];?> &nbsp;&nbsp;<? if($row['deliverymethod_text']!='None'){?><a href="#" onmouseover ="ddrivetip('<?=$del_rep_mess?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a><? }?><? if($rowdeli['delivery_methods_delivery_id']==$row['deliverymethod_id'] && $row['deliverymethod_text']!='None'&& $row['deliverymethod_location_required']!=1){ ?>&nbsp;<input name="Set_Values" type="button" class="red" value="Set Values" onclick="show_processing();location.href='home.php?request=delivery_settings&fpurpose=editdelivery&deliveryid=<?=$row['deliverymethod_id']?>'" /> <a href="#" onmouseover ="ddrivetip('<?=$del_rep_mess_set?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a><? }elseif($rowdeli['delivery_methods_delivery_id']==$row['deliverymethod_id'] && $row['deliverymethod_text']!='None'&& $row['deliverymethod_location_required']==1){ ?>
		  <input name="Set_Values1" type="button" class="red" value="Edit By Location" onclick="show_processing();location.href='home.php?request=delivery_settings&fpurpose=editdeliverylocation&deliveryid=<?=$row['deliverymethod_id']?>'"/>
		  <a href="#" onmouseover ="ddrivetip('<?=$del_rep_mess_edit_loc?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a><? } ?>
	<?php
	if($loc_check_req==1)
	{
		if($loc_cnts==0)
		{
		?>
			<div style='color:#FF0000; float:right;'><strong><span style='text-decoration:blink'><== Urgent:</span></strong> Please use <strong>"Edit by Location"</strong> button to manage locations for this Delivery Method. Orders cannot be placed in website if locations are not set. .<div>
		<?php	
		}
	}
	?>
	</td>
		  </tr>
		<?
		}
		?>
		<tr>
		<td colspan="3" align="center" valign="middle" class="tdcolorgray" >
		Allow Split Delivery
          <select name="charge_split" class="dropdown" id="charge_split">
            <option value="Y" <? if($rowdeli['charge_split_delivery']=='Y') echo "selected";?>>Yes</option>
			<option value="N"<? if($rowdeli['charge_split_delivery']=='N') echo "selected";?>>N</option>
			</select>	<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_DELIVERY_SETIINGS_SPLIT_DELIVERY')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>	</td>
		</tr>
		 <tr>
          <td colspan="3" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
        </tr>
		  <tr>
          <td colspan="3" align="left" valign="middle" class="sorttd" >&nbsp;</td>
        </tr>
		
		<tr>
		  <td colspan="3" align="center" valign="middle" class="sorttd" >
		   <input name="Submit" type="submit" class="red" value="Save" />	 <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_DELIVERY_SETIINGS_SAVE_DEL_METH')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>	</td>
		</tr>
		</table>
		</div>
		</td>
		</tr>
      </table>
</form>
