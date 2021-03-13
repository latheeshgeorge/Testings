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
	//echo "testamt";
	$table_name		= 'delivery_site_option_details';
	$page_type		= 'Delivery Charges';
    $help_msg = get_help_messages('EDIT_DELIVERY_LOCATION_AMT_MESS1');
	$delivery_id	= $_REQUEST['deliveryid'];
	
	// Check whether any delivery methods groups exists for this site
	$group_exists	= false;
	$sql_check 		= "SELECT delivery_group_id,delivery_group_name FROM general_settings_site_delivery_group WHERE sites_site_id=$ecom_siteid AND delivery_group_hidden=0";
	$ret_check 		= $db->query($sql_check);
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
		
		$sql_check 		= "SELECT delivery_group_id,delivery_group_name FROM general_settings_site_delivery_group WHERE sites_site_id=$ecom_siteid AND delivery_group_hidden=0";
		$ret_check 		= $db->query($sql_check);
		if(!$group_exists)
		{
			$sql="SELECT * FROM $table_name where delivery_methods_deliverymethod_id=".$_REQUEST['deliveryid'] . " AND sites_site_id =". $ecom_siteid." 
							AND delivery_site_location_location_id=".$_REQUEST['locationid']." 
							ORDER BY 
								delopt_option";
			$res= $db->query($sql);
		}	
	}
	if (!$_REQUEST['locationid'])
		$_REQUEST['locationid'] = -1;
	$sqlname="SELECT * FROM delivery_methods where deliverymethod_id=".$_REQUEST['deliveryid'] ;
	$resname= $db->query($sqlname);
	$rowname = $db->fetch_array($resname);
	
	$gensql = "SELECT delivery_settings_weight_min_limit, delivery_settings_weight_max_limit, delivery_settings_weight_increment,
					  delivery_settings_common_min, delivery_settings_common_max, delivery_settings_common_increment     	  
					   FROM general_settings_sites_common 
							WHERE sites_site_id='".$ecom_siteid."'";
	$genres = $db->query($gensql);
	$gennum = $db->num_rows($genres);
	$genrow = $db->fetch_array($genres);
	if($gennum==0) {
		$errmsg =  " Please Set Delivery Settings. <br/> Please <a href='home.php?request=general_settings&fpurpose=settings_default'> Click here </a> to go to General Settings  ";
	} else {
		$wgmin = $genrow['delivery_settings_weight_min_limit'];
		$wgmax = $genrow['delivery_settings_weight_max_limit'];
		$wginc = $genrow['delivery_settings_weight_increment'];
		
		$othmin = $genrow['delivery_settings_common_min'];
		$othmax = $genrow['delivery_settings_common_max'];
		$othinc = $genrow['delivery_settings_common_increment'];				
	} 
		$main_html 		= Build_main_delivery_dropdown_html();

?>
<script type="text/javascript">
show_processing();
</script>
<form name="frmlistDelivery" action="home.php?request=delivery_settings" method="post" >	
<input type="hidden" name="fpurpose" value="deliveryData">
<input type="hidden" name="request" value="delivery_settings" />
<input type="hidden" name="deliveryid" value="<?php echo $delivery_id;?>">
 <input type="hidden" name="type1" value="Location_And_Amount">
 <input type="hidden" name="locationid" value="<?php echo $_REQUEST['locationid'];?>">

  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4" align="left" valign="middle" class="treemenutd"><a href="home.php?request=delivery_settings"> Delivery Charges</a> &gt;&gt; '<? echo $rowname['deliverymethod_name'];?>'</td>
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
<!--		   <tr>
          <td colspan="4" align="left" valign="middle" class="sorttd" ><b>Delivery Method:</b></td>
          </tr>-->
		<tr>
		<td colspan="4" align="left" valign="middle" class="sorttd" ><table width="678" border="0" cellpadding="4" cellspacing="0">
          <tr>
            <td align="left"  colspan="2"><b>Location  :</b>
                <input name="location" type="text" value="<? echo $rowloc['location_name'] ?>" size="50" /></td>
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
    list($big,$small) = split('[.]',$option);
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
                <input name="button" type="button" class="red"  onclick="show_processing();document.frmlistDelivery.fpurpose.value='deliveryData';document.forms.frmlistDelivery.submit();" value="Submit Rates" /></td>
            <td align="left"><input name="button" type="button" class="red"  onclick="show_processing();document.frmlistDelivery.fpurpose.value='deliveryData';document.forms.frmlistDelivery.more_req.value=1;document.forms.frmlistDelivery.submit();" value="Submit and enter more rates" /></td>
          </tr>
          <?php
	}
	else
	{
		
		while ($row_check = $db->fetch_array($ret_check))
		{
?>
          <tr>
            <td colspan="4" align="left" valign="middle" class="seperationtd" ><?php echo stripslashes($row_check['delivery_group_name'])?></td>
          </tr>
          <tr>
            <td align="center" width="60%"><b>Option</b></td>
            <td align="center" width="117"><b>Charges (<?php echo  display_curr_symbol()?>)</b></td>
          </tr>
          <?
    $k=0;
	$sql 		= "SELECT * FROM $table_name where delivery_group_id=".$row_check['delivery_group_id']." 
							AND delivery_methods_deliverymethod_id=".$_REQUEST['deliveryid'] . " 
							AND delivery_site_location_location_id = ".$_REQUEST['locationid']." 
							AND sites_site_id =". $ecom_siteid;
	$res 		= $db->query($sql);
  while($row=$db->fetch_array($res))
  {
	$option=$row['delopt_option'];
	list($big,$small) = split('[.]',$option);
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
	?>
          <?php	
		}
?>
          <tr id="largebuttonlink">
            <td align="center">&nbsp;
                <input name="button" type="button" class="red"  onclick="show_processing();document.frmlistDelivery.fpurpose.value='deliveryData';document.forms.frmlistDelivery.submit();" value="Submit Rates" /></td>
            <td align="left"><input name="button" type="button" class="red"  onclick="show_processing();document.frmlistDelivery.fpurpose.value='deliveryData';document.forms.frmlistDelivery.more_req.value=1;document.forms.frmlistDelivery.submit();" value="Submit and enter more rates" /></td>
          </tr>
          <?php		
	}	
?>
        </table></td>
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
