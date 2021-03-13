<?php
	/*#################################################################
	# Script Name 	: list_pricedisplay.php
	# Description 	: Page for listing Product Vendors
	# Coded by 		: SKR
	# Created on	: 22-June-2007
	# Modified by	: Sny
	# Modified On	: 19-Nov-2007
	#################################################################*/
	
	//Define constants for this page
	$table_name			= 'delivery_site_option_details';
	$page_type			= 'Delivery Charges';
    $help_msg = get_help_messages('EDIT_DELIVERY_LOCATION_MESS1');
	$delivery_id		= $_REQUEST['deliveryid'];

	// Check whether any delivery methods groups exists for this site
	$group_exists		= false;
	$sql_check 			= "SELECT delivery_group_id,delivery_group_name FROM general_settings_site_delivery_group WHERE sites_site_id=$ecom_siteid AND delivery_group_hidden=0";
	$ret_check 			= $db->query($sql_check);
	if($db->num_rows($ret_check))
	{
		$group_exists = true;
	}
	if($_REQUEST['locationid'])
	{
		$sqlloc="SELECT location_name,location_id FROM delivery_site_location where delivery_methods_deliverymethod_id=".$_REQUEST['deliveryid'] . " AND sites_site_id =". $ecom_siteid." AND location_id=".$_REQUEST['locationid'];
		$resloc = $db->query($sqlloc);
		$rowloc = $db->fetch_array($resloc);
		
		// Check whether any delivery methods groups exists for this site
		
		/*$sql_check 		= "SELECT delivery_group_id,delivery_group_name FROM general_settings_site_delivery_group WHERE sites_site_id=$ecom_siteid AND delivery_group_hidden=0";
		$ret_check 		= $db->query($sql_check);*/
		if(!$group_exists)
		{
			$sql="SELECT * FROM $table_name where delivery_methods_deliverymethod_id=".$_REQUEST['deliveryid'] . " AND sites_site_id =". $ecom_siteid." AND delivery_site_location_location_id=".$_REQUEST['locationid']." ORDER BY delopt_option";
			$res= $db->query($sql);
		}	
	}
	if (!$_REQUEST['locationid'])
		$_REQUEST['locationid'] = -1;
	$sqlname="SELECT * FROM delivery_methods where deliverymethod_id=".$_REQUEST['deliveryid'] ;
	$resname= $db->query($sqlname);
	$rowname = $db->fetch_array($resname);

	
?>
<script type="text/javascript">
show_processing();
</script>
<form name="frmlistDelivery" action="home.php?request=delivery_settings" method="post" >	
<input type="hidden" name="fpurpose" value="deliveryData">
<input type="hidden" name="request" value="delivery_settings" />
<input type="hidden" name="deliveryid" value="<?php echo $delivery_id;?>">
 <input type="hidden" name="type1" value="Location">
 <input type="hidden" name="locationid" value="<?php echo $_REQUEST['locationid'];?>">

  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4" align="left" valign="middle" class="treemenutd"><a href="home.php?request=delivery_settings"> Delivery Charges </a>&gt;&gt; '<? echo $rowname['deliverymethod_name'];?>'
 
</td>
        </tr>
        <tr>
          <td colspan="4" align="left" valign="middle" class="helpmsgtd" ><?=$help_msg ?></td>
        </tr>
		 <? if($_REQUEST['alert']){
		 $alert ="Details Saved Successfully";
		 ?>
        <tr>
          <td colspan="4" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
          </tr>
		  <? }?>
		 <tr>
		<td colspan="4" align="left" valign="middle" class="sorttd" >
		<table width="100%" border="0" cellpadding="4" cellspacing="0">
		
		<tr>
	  <td align="left" colspan="2"><b>Location  :</b>  <input name="location" type="text" value="<? echo $rowloc['location_name'] ?>" size="50"></td>
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
	              <td colspan="2" align="center">&nbsp;</td>
          </tr>
          <tr id="largebuttonlink">
				<td align="center">&nbsp;<input type="button" value="Submit Rates" class="red"  onClick="show_processing();document.frmlistDelivery.fpurpose.value='deliveryData';document.forms.frmlistDelivery.submit();"></td>
				<td align="left">
				  </td>
			</tr>
	<tr id="largebuttonlink">
	  <td colspan="4" align="center">&nbsp;</td>
	  </tr>
	<?php
	}
	else
	{
		while ($row_check = $db->fetch_array($ret_check))
		{
?>
			<tr>
				<td colspan="4" align="left" valign="middle" class="seperationtd" >
				<?php echo stripslashes($row_check['delivery_group_name'])?>				</td>
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
				list($big,$small) = split('[.]',$option);
				  
			   ?>
			   <tr>
			  <td align="right" width="238">Charges (<?php echo  display_curr_symbol()?>)</td>
				  <td align="left" width="704"> 
				<input name="price_<?php echo $row_check['delivery_group_id']?>[]" class="input" type="text" size="5" value="<? echo $row['delopt_price']?>">				  </td>
		  </tr> 
		  <?
			 			
				
			}	
	?>
			
			
 	            <tr id="largebuttonlink">
 	              <td colspan="2" align="center">&nbsp;</td>
          </tr>
          <tr id="largebuttonlink">
				<td align="center">&nbsp;<input type="button" value="Submit Rates" class="red"  onClick="show_processing();document.frmlistDelivery.fpurpose.value='deliveryData';document.forms.frmlistDelivery.submit();"></td>
				<td align="left">
				  </td>
			</tr>
 	 <tr id="largebuttonlink">
 	   <td colspan="4" align="center">&nbsp;</td>
 	   </tr>
<?php	
	}
	?>	
</table>
		</td>
		</tr>

		 <tr>
          <td colspan="4" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
         
        </tr>
		<tr>
        	<td colspan="4" align="left" valign="middle" class="sorttd" >&nbsp;</td>
		</tr>
		
		   
      </table>
	    <input type="hidden" name="more_req" value="" />
</form>
<script type="text/javascript">
hide_processing();
</script>

