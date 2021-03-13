<?php
	/*#################################################################
	# Script Name 	: list_pricedisplay.php
	# Description 	: Page for listing Product Vendors
	# Coded by 		: SKR
	# Created on	: 22-June-2007
	# Modified by	: Sny
	# Modified On	: 13-Nov-2007
	#################################################################*/
//Define constants for this page
$table_name='delivery_site_option_details';
$page_type='Delivery Charges';
$help_msg 			= get_help_messages('ADD_SETTINGS_DELIVERY_AMNT_MESS1');
$delivery_id= $_REQUEST['deliveryid'];
$sqlname="SELECT * FROM delivery_methods where deliverymethod_id=".$_REQUEST['deliveryid'] ;
$resname= $db->query($sqlname);
$rowname = $db->fetch_array($resname);

// Check whether any delivery methods groups exists for this site
$group_exists	= false;
$sql_check 		= "SELECT delivery_group_id,delivery_group_name FROM general_settings_site_delivery_group WHERE sites_site_id=$ecom_siteid AND delivery_group_hidden=0";
$ret_check 		= $db->query($sql_check);
if($db->num_rows($ret_check))
{
	$group_exists = true;
}
else
{
	$sql 		= "SELECT * FROM $table_name where delivery_methods_deliverymethod_id=".$_REQUEST['deliveryid'] . " AND sites_site_id =". $ecom_siteid." ORDER BY delopt_option";
	$res 		= $db->query($sql);
}	

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
function valform(require)
{
var require;
 for(var i=0; i<document.frmlistDelivery.elements.length;i++)
 {  
  if(document.frmlistDelivery.elements[i].name.substr(0,5)=='price')
  { 
   	if (document.frmlistDelivery.elements[i].value<0)
			{
				alert('Price should be a Positive value');
				document.frmlistDelivery.elements[i].focus();
				return false;
			}
  }
  
 }
 if(require=='more')
 {
 document.forms.frmlistDelivery.more_req.value=1;
 }
 show_processing();document.frmlistDelivery.fpurpose.value='deliveryData';document.forms.frmlistDelivery.submit();
}
</script>
<form name="frmlistDelivery" action="home.php?request=delivery_settings" method="post" >	
<input type="hidden" name="fpurpose" value="deliveryData">
<input type="hidden" name="deliveryid" value="<?php echo $delivery_id;?>">
<input type="hidden" name="type1" value="Amount">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=delivery_settings"> Delivery Charges</a><span> '<? echo $rowname['deliverymethod_name'];?>'</span></div></td>
        </tr>
        <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="3">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
         <?
		 if($_REQUEST['alert'])
		 {
		 ?>
        	<tr>
          	<td colspan="3" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
          	</tr>
		 <?
		 }
		 ?>
		 <!--  <tr>
          <td colspan="4" align="left" valign="middle" class="sorttd" ><b>Delivery Method:</b></td>
        </tr>-->
		<?php
			if($group_exists==false) // case if delivery groups does not exists
			{
		?><tr>
				<td colspan="3" align="left" valign="middle"  >
								<div class="editarea_div">

				<table width="100%" border="0" cellpadding="4" cellspacing="0">
				<tr>
				<td colspan="3" align="left" valign="middle" class="sorttd" >
				<table width="637" border="0" cellpadding="4" cellspacing="0">
				<tr>
					<td align="right" width="700"><b>Option</b></td>
					<td align="center" width="300"><b>Charges (<?php echo  display_curr_symbol()?>)</b></td>
				</tr>
  <?
  $k=0;
  while($row=$db->fetch_array($res))
  {
   $option=$row['delopt_option'];
  //echo "test".  $option;
    //list($big,$small) = split('[.]',$option);
	$big = $option;
	 //echo $big;
	  
   $k++;
  if($k==1){
   $msg='More than zero but less than ';
   }else{
    $msg='More than previous but less than';}
		
   ?>
   <tr>
  <td align="right" width="700"> <? echo $msg; ?> 
   <select name="del_optionbig[]" class="input">
    <option value=''></option>
	<? /*for($i=$othmin ;$i<=$othmax; $i+=$othinc){ ?>
	<option value="<? echo $i; ?>"<? if($big==$i) echo "selected"; ?>><? echo $i; ?></option>
	<? }*/
	Build_main_delivery_dropdown($big,$main_html); ?>
	</select>
	      </td>
      <td align="center" width="300"> 
	<input name="price[]" class="input" type="text" size="5" value="<? echo $row['delopt_price']?>">      </td>
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
  <td align="right" width="700"> <? echo $msg?> 
   <select name="del_optionbig[]" class="input">
    <option value=''></option>
	<? /*for($i=$othmin ;$i<=$othmax; $i+=$othinc){?>
	<option value="<? echo $i; ?>"><? echo $i; ?></option>
	<? }*/
	Build_main_delivery_dropdown('',$main_html);
	?>
	</select>
		  </td>
      <td align="center" width="300"> 
	<input name="price[]" class="input" type="text" size="5">      </td>
    </tr>
	<? } ?>
	</table>
	</td>
	</tr>
	</table>
	</div>		
	</td>
		</tr>
    <tr>
					<td colspan="3" align="left" valign="top" >	
						<div class="editarea_div">
						<table width="100%" cellpadding="0" cellspacing="0">
		 <tr id="largebuttonlink">
						<td align="right" width="48%">&nbsp;<input type="button" value="Submit Rates" class="red"  onClick="return valform()"></td>
						<td  align="right">&nbsp;
						  <input type="button" value="Submit and enter more rates" class="red"  onClick="return valform('more')"></td>
						</tr>
		</table></div>		</td>
		</tr>
		
<?php
			}
			else // case if delivery groups exists
			{
				?>	<tr>
					<td colspan="3" align="left" valign="top" >	
						<div class="editarea_div">
						<table width="100%" cellpadding="0" cellspacing="0">
				<?php
				while ($row_check = $db->fetch_array($ret_check))
				{	
?>
					<tr>
					<td colspan="3" align="left" valign="top" >	
						<table width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td colspan="3" align="left" valign="middle" class="seperationtd" >
						<?php echo stripslashes($row_check['delivery_group_name'])?>						</td>
					</tr>
					<tr>
					<td colspan="3" align="left" valign="middle" class="sorttd" >
					
					<table width="629" border="0" cellpadding="4" cellspacing="0">
					<tr>
						<td align="right" width="700"><b>Option</b></td>
						<td align="center" width="300"><b>Charges (<?php echo  display_curr_symbol()?>)</b></td>
					</tr>
  <?
					  $k=0;
						$sql 		= "SELECT * FROM $table_name where delivery_group_id=".$row_check['delivery_group_id']." AND delivery_methods_deliverymethod_id=".$_REQUEST['deliveryid'] . " AND sites_site_id =". $ecom_siteid." ORDER BY delopt_option";
						$res 		= $db->query($sql);
					  while($row=$db->fetch_array($res))
					  {
					   $option=$row['delopt_option'];
					  //echo "test".  $option;
						//list($big,$small) = split('[.]',$option);
						 //echo $big;
						  $big = $option;
					   $k++;
					  if($k==1){
					   $msg='More than zero but less than ';
					   }else{
						$msg='More than previous but less than';}
							
					   ?>
					   <tr>
					  <td align="right" width="700"> <? echo $msg; ?> 
					   <select name="del_optionbig_<?php echo $row_check['delivery_group_id']?>[]" class="input">
						<option value=''></option>
						<? /*for($i=$othmin ;$i<=$othmax; $i+=$othinc){ ?>
						<option value="<? echo $i; ?>"<? if($big==$i) echo "selected"; ?>><? echo $i; ?></option>
						<? }*/
						Build_main_delivery_dropdown($big,$main_html);?>
						</select>
						</td>
						<td align="center" width="300"> 
						<input name="price_<?php echo $row_check['delivery_group_id']?>[]" class="input" type="text" size="5" value="<? echo $row['delopt_price']?>">						  </td>
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
						<td align="right" width="700"> <? echo $msg?>
						<select name="del_optionbig_<?php echo $row_check['delivery_group_id']?>[]" class="input">
							<option value=''></option>
							<? /*for($i=$othmin ;$i<=$othmax; $i+=$othinc){ ?>
							<option value="<? echo $i; ?>"><? echo $i; ?></option>
							<? }*/
							Build_main_delivery_dropdown('',$main_html);?>
						</select>
												</td>
						<td align="center" width="300"> 
						<input name="price_<?php echo $row_check['delivery_group_id']?>[]" class="input" type="text" size="5">						</td>
						</tr>
						<? 
							} 
						?>
						</table>
						</td>
						</tr>
						</table>
						</td>
						</tr>
						<?php	
						}
						?>
						</table>
						</div>
						</td>
						</tr>
						<tr id="largebuttonlink">
					<td colspan="3" align="left" valign="top" >	
						<div class="editarea_div">
						<table width="100%" cellpadding="0" cellspacing="0">						
						<tr >
						<td align="right" width="48%">&nbsp;<input type="button" value="Submit Rates" class="red"  onClick="return valform()"></td>
						<td  align="right">&nbsp;
						  <input type="button" value="Submit and enter more rates" class="red"  onClick="return valform('more')"></td>
						</tr>
						</table>
						</div>
						</td>
						</tr>
<?php
			}	
?>					
      </table>
<input type="hidden" name="more_req" value="" />
</form>
<script type="text/javascript">
hide_processing();
</script>
