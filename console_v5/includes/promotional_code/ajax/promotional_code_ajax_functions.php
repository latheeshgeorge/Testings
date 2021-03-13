<?php
	// #################################################################################################
	//		Edit Promotional Code	
	//	################################################################################################
	
	function show_promocode_maininfo($code_id,$alert='')
	{
		global $db,$ecom_siteid,$ecom_themeid,$ecom_hostname;
		//$help_msg = 'This section helps in editing the Promotional Codes';
		$help_msg = get_help_messages('EDIT_PROM_CODE_MESS1');
		// Get the details of selected promotional code
		$sql_prom = "SELECT * FROM promotional_code WHERE code_id=".$code_id;
		$ret_prom = $db->query($sql_prom);
		if($db->num_rows($ret_prom))
		{
			$row_prom = $db->fetch_array($ret_prom);
			$ctype = $row_prom['code_type'];
		}
		if($_REQUEST['alert']==1)
		{
		 $alert ="Promotional code added successfully<br>Please select the products to be linked with this promotional code";
		}
?>
<table width="100%" border="0" cellspacing="1" cellpadding="1">
      <tr>
		 <td colspan="2" align="left" valign="top">
		 <div class="editarea_div">
		 <table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td align="left" class="helpmsgtd" valign="top" colspan="2" ><div class="helpmsg_divcls"><?PHP echo $help_msg; ?></div></td>
			  </tr>
		  <tr>
			 <td width="60%" align="left" valign="top">
		 		<table width="100%" border="0" cellspacing="1" cellpadding="2">
          <tr>
            <td align="left" class="tdcolorgray" >Promotional Code <span class="redtext">*</span> </td>
            <td align="center" class="tdcolorgray" >:</td>
            <td align="left" class="tdcolorgray" ><input name="code_number" type="text" id="code_number" size="40" value="<?php echo stripslashes(htmlentities($row_prom['code_number']))?>" /></td>
          </tr>
          <tr>
            <td align="left" class="tdcolorgray" >Start From <span class="redtext">*</span></td>
            <td align="center" class="tdcolorgray" >:</td>
            <td align="left" class="tdcolorgray" >
			<?php 
				$startdate 	= explode("-",$row_prom['code_startdate']);
				$enddate 	= explode("-",$row_prom['code_enddate']);
			?>
			<input name="code_startdate" type="text" id="+" value="<?php echo $startdate[2]."-".$startdate[1]."-".$startdate[0]?>" size="10" maxlength="10" />
              <a href="javascript:show_calendar('frm_promo.code_startdate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a>
			  (dd-mm-yyyy) </td>
          </tr>
          <tr>
            <td align="left" class="tdcolorgray" >End On <span class="redtext">*</span></td>
            <td align="center" class="tdcolorgray" >:</td>
            <td align="left" class="tdcolorgray" ><input name="code_enddate" type="text" id="code_enddate" value="<?php echo $enddate[2]."-".$enddate[1]."-".$enddate[0]?>" size="10" maxlength="10" />
              <a href="javascript:show_calendar('frm_promo.code_enddate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a>
			  (dd-mm-yyyy)</td>
          </tr>
          <tr>
            <td width="37%" align="left" class="tdcolorgray" >Code Type </td>
            <td width="2%" align="center" class="tdcolorgray" >:</td>
            <td width="61%" align="left" class="tdcolorgray" ><select name="code_type" id="code_type" onchange="handle_codetype(this.value)">
                <option value="default" <?php if ($ctype=='default') echo 'selected="selected"'?>>% Off on grand total</option>
                <option value="money" <?php if ($ctype=='money') echo 'selected="selected"'?>>Money Off on minimum value of grand total</option>
                <option value="percent" <?php if ($ctype=='percent') echo 'selected="selected"'?>>% Off on minimum value of grand total</option>
                <option value="product" <?php if ($ctype=='product') echo 'selected="selected"'?>>Off on selected products</option>
                <?php 
                //New promotional code for puregusto site
                if($ecom_siteid==126 || $ecom_siteid==112)
                {
                ?>
                <option value="freeproduct" <?php if ($ctype=='freeproduct') echo 'selected="selected"'?>>Adds selected products to the basket at a discounted or FREE </option>
                <option value="orddiscountpercent" <?php if ($ctype=='orddiscountpercent') echo 'selected="selected"'?>>% Total Order Discount When Purchasing A Qualifying Item</option>
                <?php
				}
                ?>
              </select>            </td>
          </tr>
          <tr id="tr_discmin" style="display:none;">
            <td align="left" class="tdcolorgray" >Discount for Minimum <span class="redtext">*</span></td>
            <td align="center" class="tdcolorgray" >:</td>
            <td align="left" class="tdcolorgray" ><input name="code_minimum" type="text" size="8" value="<?php echo $row_prom['code_minimum']?>" /></td>
          </tr>
          <tr id="tr_discval">
            <td align="left" class="tdcolorgray" ><div id='dis_val'>Discount % <span class="redtext">*</span></div></td>
            <td align="center" class="tdcolorgray" >:</td>
            <td align="left" class="tdcolorgray" ><input name="code_value" type="text" size="8" value="<?php echo $row_prom['code_value']?>" /></td>
          </tr>
          <tr id="tr_disctype">
            <td align="left" class="tdcolorgray" ><div id='dis_type'>Discount Type </div></td>
            <td align="center" class="tdcolorgray" >:</td>
            <td align="left" class="tdcolorgray" ><select name="code_dis_type" id="code_dis_type">
              <option value="0" <?php if($row_prom['code_dis_type'] == '0') echo ' selected="selected"';?>>Value</option>
              <option value="1" <?php if($row_prom['code_dis_type'] == '1') echo ' selected="selected"';?>>%</option>
            </select></td>
          </tr>
          <tr>
            <td align="left" valign="middle" class="tdcolorgray">Customer should login to use this? </td>
            <td align="center" class="tdcolorgray" >&nbsp;</td>
            <td align="left" valign="middle" class="tdcolorgray"><input type="checkbox" name="code_login_to_use" id="code_login_to_use"  value="1" <?php echo ($row_prom['code_login_to_use']==1)?'checked="checked"':'' ?> onclick="handle_customer_main_limit(this)"/>
              &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('PROMO_REQ_LOGIN_TO_USE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          </tr>
		  <tr>
		    <td align="left" valign="middle" class="tdcolorgray">Allow Free Delivery </td>
		    <td align="center" class="tdcolorgray" >:</td>
		    <td align="left" valign="middle" class="tdcolorgray"><input type="checkbox" name="code_freedelivery" value="1"  <?php echo ($row_prom['code_freedelivery']==1)?'checked="checked"':'' ?>/>
			&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ALLOW_FREE_DELIVERY_PROMO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	      </tr>
	       <tr>
		    <td align="left" valign="middle" class="tdcolorgray">Apply Customer Direct Discount also? </td>
		    <td align="center" class="tdcolorgray" >:</td>
		    <td align="left" valign="middle" class="tdcolorgray"><input type="checkbox" name="code_apply_direct_discount_also" value="1"  <?php echo ($row_prom['code_apply_direct_discount_also']=='Y')?'checked="checked"':'' ?>/>
			&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('APPLY_CUST_DIRECT_DISC_PROM')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	      </tr>
	       <tr>
		    <td align="left" valign="middle" class="tdcolorgray">Apply Customer Group Discount also? </td>
		    <td align="center" class="tdcolorgray" >:</td>
		    <td align="left" valign="middle" class="tdcolorgray"><input type="checkbox" name="code_apply_custgroup_discount_also" value="1"  <?php echo ($row_prom['code_apply_custgroup_discount_also']=='Y')?'checked="checked"':'' ?>/>
			&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('APPLY_CUST_GROUP_DISC_PROM')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	      </tr>
	       <tr id="prod_dir_tr" style="display:<?php echo ($ctype=='product')?'checked':''?>">
		    <td align="left" valign="middle" class="tdcolorgray">Apply Product Direct Discount also? </td>
		    <td align="center" class="tdcolorgray" >:</td>
		    <td align="left" valign="middle" class="tdcolorgray"><input type="checkbox" name="code_apply_direct_product_discount_also" value="1"  <?php echo ($row_prom['code_apply_direct_product_discount_also']=='Y')?'checked="checked"':'' ?>/>
			&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('APPLY_PROD_DIRECT_DISC_PROM')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	      </tr>
		  <tr>
		  <td align="left" valign="middle" class="tdcolorgray">Total Usage limit</td>
		  <td align="center" class="tdcolorgray" >:</td>
		   <td align="left" valign="middle" class="tdcolorgray"><input type="checkbox" name="code_unlimit_check"  value="1" <? if($row_prom['code_unlimit_check']==1) echo "checked";?>  onclick="handle_codetype('unlimited')" /> 
		   Unlimited?&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROM_CODE_LIMIT_CHECK')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> </td>
		  </tr>
		  <? 
		  if($row_prom['code_unlimit_check']==1)
		  $display = 'none' ;
		  else
		  $display = '' ;
		  ?>
		  <tr id="limt_txt_id" style="display:<?=$display?>">
		  <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
		  <td align="center" class="tdcolorgray" >&nbsp;</td>
		   <td align="left" valign="middle" class="tdcolorgray">
		   <table cellpadding="0" cellspacing="0" border="0" width="100%">
		  <tr > <td align="center" valign="middle" >Enter limit here</td><td align="left" valign="middle" class="tdcolorgray"> <input type="text" name="code_limit"  value="<?=$row_prom['code_limit']?>" size="4" />  </td>
		  </tr>
		  </table>		  </td>
		  </tr>
		  <tr id="cust_main_usage_div" style="display:<?php echo ($row_prom['code_login_to_use']==0)?'none':''?>">
		  <td align="left" valign="middle" class="tdcolorgray" nowrap="nowrap">Total usage for same customer</td>
		  <td align="center" class="tdcolorgray" >:</td>
		   <td align="left" valign="middle" class="tdcolorgray"><input type="checkbox" name="code_customer_unlimit_check" id="code_customer_unlimit_check"  value="1" <? if($row_prom['code_customer_unlimit_check']==1) echo "checked";?>  onclick="handle_codetype('customer_unlimited')" /> 
		   Unlimited?&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROM_CODE_LIMIT_CHECK')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> </td>
		  </tr>
		  <? 
		  if($row_prom['code_customer_unlimit_check']==1)
		  $display = 'none' ;
		  else
		  $display = '' ;
		  ?>
		  <tr id="limt_customer_txt_id" style="display:<?=$display?>">
		  <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
		  <td align="center" class="tdcolorgray" >&nbsp;</td>
		   <td align="left" valign="middle" class="tdcolorgray">
		   <table cellpadding="0" cellspacing="0" border="0" width="100%">
		  <tr > <td align="center" valign="middle" >Enter limit here</td><td align="left" valign="middle" class="tdcolorgray"> <input type="text" name="code_customer_limit"  value="<?=$row_prom['code_customer_limit']?>" size="4" />  </td>
		  </tr>
		  </table>		  </td>
		  </tr>
		  <tr id="hidden_trs" style="display:<?php echo ($row_prom['code_type']=='product')?'none':''?>">
		  <td align="left" valign="middle" class="tdcolorgray">Hidden?</td>
		  <td align="center" class="tdcolorgray" >:</td>
		   <td align="left" valign="middle" class="tdcolorgray"><input type="radio" name="code_hide" value="1" <? if($row_prom['code_hidden']==1) echo "checked";?>  />
		     Yes
		     <input type="radio" name="code_hide" value="0"  <? if($row_prom['code_hidden']==0) echo "checked";?> />
		     No&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROM_CODE_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		  </tr>
        </table></td>
        <td width="45%" align="left" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="1">
          <tr>
            <td colspan="2" align="left" class="tdcolorgray" ><span class="redtext"><strong>Code Types </strong></span></td>
          </tr>
          <tr>
            <td colspan="2" class="tdcolorgray" ><strong>% Off on grand total :- </strong></td>
          </tr>
          <tr>
            <td width="2%" class="tdcolorgray" >&nbsp;</td>
            <td width="98%" align="left" class="tdcolorgray" >The discount % specified in the Discount % field will be deducted from the grand total.</td>
          </tr>
          <tr>
            <td colspan="2" align="left" class="tdcolorgray" ><strong>Money Off on minimum value of grand total :-</strong> </td>
          </tr>
          <tr>
            <td class="tdcolorgray" >&nbsp;</td>
            <td align="left" class="tdcolorgray" >E.g. In case if 100 is to be deducted from the total if purchase is made for a minimum value of 1000, then specify 1000 in &quot;Discount for Minimum&quot; and 100 in &quot;Discount Value&quot; </td>
          </tr>
          <tr>
            <td colspan="2" align="left" class="tdcolorgray" ><strong>% Off on minimum value of grand total :-</strong> </td>
          </tr>
          <tr>
            <td class="tdcolorgray" >&nbsp;</td>
            <td align="left" class="tdcolorgray" >E.g.In case if 10% is to be deducted from the total if purchase is made for a minimum value of 1000, then specify 1000 in &quot;Discount for Minimum&quot; and 10 in &quot;Discount % &quot; </td>
          </tr>
          <tr>
            <td colspan="2" align="left" class="tdcolorgray" ><strong>Off on selected products :-</strong> </td>
          </tr>
          <tr>
            <td class="tdcolorgray" >&nbsp;</td>
            <td align="left" class="tdcolorgray" >In case if  promotional price is to be given for selected products, this option can be used. The option to link the products to the promotional code will be displayed once the promotional code details are saved.</td>
          </tr>
        </table>
		</td>
		</tr>
		</table>
		</div></td>
      </tr>
	  <tr>
        <td colspan="2" align="right" valign="top" class="tdcolorgray">
		<div class="editarea_div">
		 <table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="100%" align="right" valign="middle"><input type="button" name="Button" value="Save" onclick="validate_promotional_code()" class="red" /></td>				
		  </tr>
		 </table>
		</div>
		</td>
	</tr>
    </table>

<?php
}
function show_prom_product_list($code_id,$alert='',$code_type='product')
{
		global $db,$ecom_siteid ;
		
		$sql_dis_type	=	"SELECT code_dis_type,code_type FROM promotional_code WHERE code_id = $code_id  AND sites_site_id=$ecom_siteid ";
		$ret_dis_type	=	$db->query($sql_dis_type);
		if ($db->num_rows($ret_dis_type))
		{
			$row_dis_type = $db->fetch_array($ret_dis_type);
			if($row_dis_type['code_dis_type'] == 1)
			{
				$disTypeTitle	=	'Promotional Price in %';
			}
			else
			{
				$disTypeTitle	=	'Promotional Price ';
			}
		}
		else
		{
			$row_dis_type['code_dis_type']	=	0;
		}
		if($code_type!='')
		{
		  // Get the list of products assigned to current promotional code
			$sql_product = "SELECT 	b.pcode_det_id,b.product_price,a.product_id,a.product_name,a.product_variables_exists, 
		  							a.product_hide,a.product_webprice,a.product_variablecomboprice_allowed,a.product_discount,
		  							a.product_discount_enteredasval 
		  					FROM 
		  					
								products a,promotional_code_product b 
							WHERE a.sites_site_id=$ecom_siteid 
								AND promotional_code_code_id=$code_id 
								AND promotional_code_type='$code_type'
								AND a.product_id=b.products_product_id 
							ORDER BY 
								pcode_det_id";
		  $ret_product = $db->query($sql_product);
		}
		  ?><div class="editarea_div">
		  <table width="100%" cellpadding="0" cellspacing="1" border="0"> 
		  <tr>
          <td colspan="6" align="left" valign="middle" class="helpmsgtd" ><div class="helpmsg_divcls"><?=get_help_messages('EDIT_COMBO_PRODS') ?></div></td>
        </tr>
				<?php
				if($alert)
				{
					?>
							<tr>
								<td colspan="6" align="center" class="errormsg"><?php echo $alert?></td>
							</tr>
				 <?php
				}
				
		  // Get the list of products under current category group
		 $sql_products_in_prom = "SELECT pcode_det_id, products_product_id 
		 								FROM 
											promotional_code_product  
										WHERE  
											promotional_code_code_id=$code_id
										AND promotional_code_type= '$code_type'";
		 
		 $ret_products_in_prom = $db->query($sql_products_in_prom);
		 ?>
		 <tr>
		  <td align="right" colspan="6" class="tdcolorgray_buttons">
          <input type="hidden" name="code_dis_type" id="code_dis_type" value="<?php echo $row_dis_type['code_dis_type'];?>" />
		  <?php
			if ($db->num_rows($ret_products_in_prom))
			{
				// Check whether promotional code is inactive
				$sql_prom = "SELECT code_hidden 
								FROM 
									promotional_code 
								WHERE 
									code_id = $code_id  
									AND sites_site_id = $ecom_siteid 
								LIMIT 
									1";
				$ret_prom = $db->query($sql_prom);
				if ($db->num_rows($ret_prom))
				{
					$row_prom = $db->fetch_array($ret_prom);
				}
				if($row_prom['code_hidden']==1)
				{
			?>
			  		<input name="activate_button" type="button" class="red" id="activate_button" value="Activate Promotional Code" onclick="call_activate_promotional()"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		  <?php
		  		}
				else
				{
			?>		
					<input name="deactivate_button" type="button" class="red" id="deactivate_button" value="Deactivate Promotional Code" onclick="call_deactivate_promotional()"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<?php	
				}
		  	}
		  ?>
			<input name="Assignmore" type="button" class="red" id="Assignmore" value="Assign More" onclick="normal_assignselpromproduct('<?php echo $_REQUEST['codenumber']?>','<?php echo $_REQUEST['sort_by']?>','<?php echo $_REQUEST['sort_order']?>','<?php echo $_REQUEST['records_per_page']?>','<?php echo $_REQUEST['start']?>','<?php echo $_REQUEST['pg']?>','<?php echo $code_id?>','<?php echo $code_type?>');" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROM_CODE_ASS_PROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
			<?php
			if ($db->num_rows($ret_products_in_prom))
			{
			?>
			<div id="prom_product_unassign_div" class="unassign_div">
			<input name="Save_prodall" type="button" class="red" id="Save_prodall" value="Save Details" onclick="call_ajax_savedetails('save_det','checkboxproduct[]')" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROM_CODE_SAVE_PRICE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
			<input name="Unassign" type="button" class="red" id="Unassign" value="Unassign" onclick="call_ajax_deleteall('prom_product','checkboxproduct[]')" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROM_CODE_UNASS_PROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>			</div>		
			<?php
			}
			?>		  </td>
			</tr>
				<?PHP
				if ($db->num_rows($ret_product))
				{
					if($code_type=='orddiscountpercent')
					{
						$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frm_promo,\'checkboxproduct[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frm_promo,\'checkboxproduct[]\')"/>','Slno.','Product Name','','Hidden');
					}
					else
					{
						$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frm_promo,\'checkboxproduct[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frm_promo,\'checkboxproduct[]\')"/>','Slno.','Product Name',$disTypeTitle.' <br>(if no variables)','Hidden');
					}
				$header_positions=array('center','center','left','center','center');
				$colspan = count($table_headers);
				echo table_header($table_headers,$header_positions); 
				$cnt = 1;
				while ($row_product = $db->fetch_array($ret_product))
				{
					
					$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
					//$org_price = $row_product['product_webprice'];
				?>
					
					<tr>
					<td width="5%" align="left" class="<?php echo $cls?>"><input type="checkbox" name="checkboxproduct[]" value="<?php echo $row_product['pcode_det_id'];?>" /></td>
					<td width="5%" align="left" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
					<td class="<?php echo $cls?>" align="left"><a href="home.php?request=products&checkbox[0]=<?=$row_product['product_id']?>&fpurpose=edit" class="edittextlink" ><?php echo stripslashes($row_product['product_name']);?></a></td>
					<td class="<?php echo $cls?>" align="center" width='20%'>
					<?php 
					if($row_product['product_variables_exists']=='N')
					{ 
						if($code_type!='orddiscountpercent')
						{
					?>
						<input type="text" name="promprice_<?php echo $row_product['pcode_det_id']?>" id="promprice_<?php echo $row_product['pcode_det_id']?>" size="8" value="<?php echo $row_product['product_price']?>"/> <strong>(<?php echo display_price($row_product['product_webprice'])?> )</strong>
					<?php 
						}
					}
					?>
					</td>
					<td class="<?php echo $cls?>" align="center" width="10%"><?php echo ($row_product['product_hide']=='Y')?'Yes':'No'?></td>
					</tr>
				<?php
					// Check whether there exists variables for current product
					if($row_product['product_variables_exists']=='Y')
					{
						// Get the list of all combinations already set for current product
						$sql_comb = "SELECT comb_id,prom_price 
										FROM 
											promotional_code_products_variable_combination 
										WHERE
											promotional_code_product_pcode_det_id = ".$row_product['pcode_det_id'];
						$ret_comb = $db->query($sql_comb);
						if($db->num_rows($ret_comb))
						{
							$i = 1;
						?>
						<tr>
						<td colspan="2">&nbsp;</td>
							<td colspan="5" class="listingtableheader" valign="top" align="left">
							Selected Variable Combinations 
							</td>
						</tr>	
						<?php	
							$combCnt	=	0;
							while ($row_comb = $db->fetch_array($ret_comb))
							{
								$combCnt++;
								// get the details from combo_products_variables table
								$sql_prodvars = "SELECT a.var_id,a.var_value_id,b.var_value_exists,b.var_name,b.var_price    
													FROM 
														promotional_code_products_variable_combination_map a, product_variables b 
													WHERE 
														a.promotional_code_products_variable_combination_comb_id=".$row_comb['comb_id']." 
														AND a.var_id=b.var_id 
													ORDER BY 
														a.promotional_code_products_variable_combination_comb_id,b.var_order";
								$ret_prodvars = $db->query($sql_prodvars);
								if($db->num_rows($ret_prodvars))
								{					
									$curvar_arr = $row_showvar_arr = $ret_arr = $price_arr = array();
									$show_price = 0;
									while ($row_prodvars = $db->fetch_array($ret_prodvars))
									{	
										if($row_prodvars['var_value_exists']==1)
											$curvar_arr[$row_prodvars['var_id']]=$row_prodvars['var_value_id'];
										$row_showvar_arr[] = $row_prodvars;	
									}
									if($row_product['product_variablecomboprice_allowed']=='Y') // case if variable price is maintained
									{
										$ret_arr = get_combination_id($row_product['product_id'],$curvar_arr);
										if($ret_arr['combid'])
										{
											// get the combination price 
											$sql_combprice = "SELECT comb_price 
																FROM 
																	product_variable_combination_stock 
																WHERE 
																	comb_id = ".$ret_arr['combid']." 
																LIMIT 
																	1";
											$ret_combprice = $db->query($sql_combprice);
											if($db->num_rows($ret_combprice))
											{
												$row_combprice = $db->fetch_array($ret_combprice);
												$show_price		= $row_combprice['comb_price'];
											}
										}
									}
									else
									{
										for($i=0;$i<count($row_showvar_arr);$i++)
										{
											if($row_showvar_arr[$i]['var_value_exists']==1)
											{
												$sql_valprice = "SELECT var_addprice 
																	FROM 
																		product_variable_data 
																	WHERE 
																		var_value_id=".$row_showvar_arr[$i]['var_value_id']." 
																	LIMIT 
																		1";
												$ret_valprice = $db->query($sql_valprice);
												if($db->num_rows($ret_valprice))
												{	
													$row_valprice = $db->fetch_array($ret_valprice);
													$show_price += $row_valprice['var_addprice'];
												}
											}
											else
											{
												$show_price += $row_showvar_arr[$i]['var_price'];
											}
										}
										$disc_asval = $row_product['product_discount_enteredasval'];
										$disc_price =  $row_product['product_webprice'];
										if ($disc_asval==1)
										{
											$discount	= $row_product['product_discount'];
											$disc_price = $disc_price - $discount;
										}	 
										else if($disc_asval==2)  // For Exact Discount Price 
										{
											$discount	= $disc_price-$row_product['product_discount'];
											$disc_price	= $disc_price-$discount; 	// For Exact Discount Price 
										}	 	
										else 
										{
											$discount	= $row_product['product_discount'];
											$disc_price = $disc_price - ($disc_price * $discount/100);
										}
										
										$show_price += $disc_price;
									}
				?>
								  <tr>
									<td colspan="2" valign="top" align="right"></td>
									<td colspan="5" class="listingtablestyleB">
									<table width="50%" cellpadding="1" cellspacing="1" border="0">
									<tr>
										<td align="left" valign="middle" colspan="2" style="border-bottom:1px solid #8FB3E5">
										<a href="javascript:delete_combination('<?php echo $row_comb['comb_id']?>')"><img src="images/delete_comb.gif" border="0"/></a>&nbsp;<strong>Combination #<?php echo $combCnt;?></strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if($row_dis_type['code_type'] != 'orddiscountpercent'){?><strong>Promotional Price for this combination: </strong><input type="text" name="combprice_<?php echo $row_product['pcode_det_id']?>_<?php echo $row_comb['comb_id']?>" id="combprice_<?php echo $row_product['pcode_det_id']?>_<?php echo $row_comb['comb_id']?>" value="<?php echo $row_comb['prom_price']?>" size="8"/> <strong>( <?php echo display_price($show_price);?>)</strong><?php }?>
										</td>
									</tr>
									<?php
										for($i=0;$i<count($row_showvar_arr);$i++)
										{
									?>
											<tr>
											<td align="left" width="50%" valign="middle"><strong><?php echo stripslashes($row_showvar_arr[$i]['var_name']);?></strong></td> 
											<td align="left" valign="middle">
											<?php
											if($row_showvar_arr[$i]['var_value_exists']==1) // case if 
											{
												$cur_var_arr[$row_showvar_arr[$i]['var_id']] = $row_showvar_arr[$i]['var_value_id'];
											?>
												<strong>:</strong> 	
											<?php	
												// Get the caption for value from product_variable_data table
												$sql_data = "SELECT var_value 
																FROM 
																	product_variable_data 
																WHERE 
																	var_value_id = ".$row_showvar_arr[$i]['var_value_id']." 
																LIMIT 
																	1";
												$ret_data = $db->query($sql_data);
												if($db->num_rows($ret_data))
												{
													$row_data = $db->fetch_array($ret_data);
													echo stripslashes($row_data['var_value']);
												}
											}
											
										}
									?>
									</table>
									</td>
									</tr>
				<?php		
								}
								$i++;
								
							}
						}
						// Get the list of variables for current product in order which have values 
						$sql_var = "SELECT var_id,var_name,var_value_exists 
										FROM 
											product_variables 
										WHERE 
											products_product_id = ".$row_product['product_id']." 
											AND var_hide=0 
										ORDER BY 
											var_order";
						$ret_var = $db->query($sql_var);
						if($db->num_rows($ret_var))
						{
						?>
						<tr>
						<td colspan="2" >&nbsp;</td>
						<td colspan="4"><a href="javascript:handle_more_combination('<?php echo $row_product['pcode_det_id']?>')"  class="redtext">
						<div id="add_more_div_<?php echo $row_product['pcode_det_id']?>">Click here to Add More Combinations <img src="images/right_arr.gif" border="0"></div></a>
							<input type="hidden" name="more_comb_hidden_<?php echo $row_product['pcode_det_id']?>" id="more_comb_hidden_<?php echo $row_product['pcode_det_id']?>" value="0" />
						</td>
						</tr>
						<tr id="add_more_tr_<?php echo $row_product['pcode_det_id']?>" style="display:none">
						<td colspan="2" >&nbsp;</td>
						<td colspan="4" class="listingtableheader">Select New Variable Combinations for this Product</td>
						</tr>
						<tr id="add_more_tr_more_<?php echo $row_product['pcode_det_id']?>" style="display:none">
						<td colspan="2">&nbsp;</td>
						<td colspan="4" class="listingtablestyleB" >
						<table width="50%" cellpadding="1" cellspacing="1" border="0">
						<?php
							while ($row_var = $db->fetch_array($ret_var))
							{
						?>	
								<tr>
									<td align="left" width="50%" valign="middle"><strong><?php echo stripslashes($row_var['var_name']);?></strong></td> 
									<td align="left" valign="middle"><strong>:</strong> 
									<?php
										if($row_var['var_value_exists']==1)
										{
									?>
											<select name="comb_var_<?php echo $row_product['pcode_det_id']?>_<?php echo $row_var['var_id']?>_<?php echo $row_product['product_id']?>" id="comb_var_<?php echo $row_product['pcode_det_id']?>_<?php echo $row_var['var_id']?>_<?php echo $row_product['product_id']?>">
											<?php
												// Get the values set for current variables
												$sql_val = "SELECT var_value_id, var_value 
																FROM 
																	product_variable_data 
																WHERE 
																	product_variables_var_id = ".$row_var['var_id']." 
																ORDER BY 
																	var_order ";
												$ret_val = $db->query($sql_val);
												if ($db->num_rows($ret_val))
												{
													while ($row_val = $db->fetch_array($ret_val))
													{
													?>
													<option value="<?php echo $row_val['var_value_id']?>" ><?php echo stripslashes($row_val['var_value'])?></option>
													<?php		
													}
												}
											?>
											</select>
									<?php
										}
										else
										{
									?>
											<input  type="checkbox" name="comb_var_<?php echo $row_product['pcode_det_id']?>_<?php echo $row_var['var_id']?>_<?php echo $row_product['product_id']?>" id="comb_var_<?php echo $row_product['pcode_det_id']?>_<?php echo $row_var['var_id']?>_<?php echo $row_product['product_id']?>" value="1" />
									<?php	
										}
									?>	
									</td>
									</tr>
									
						<?php		 
							}
						?>
						</table>
						</td>
						</tr>
						<?php	
						}
											
					}
				}
				}
				else
				{
				?>
					<tr>
					  <td colspan="5" align="center" valign="middle" class="norecordredtext_small">
	  				  <input type="hidden" name="productcombo_norec" id="productcombo_norec" value="1" />
					  No Products Assigned for this promotional code </td>
					</tr>
				<?	
				}
				?>
				</table></div>
		
<?	}
	function show_order_list($promotionalid,$alert='')
	{
		global $db,$ecom_siteid;
			// Get the voucher number related to current voucher id
			$sql_promot = "SELECT code_id,code_number FROM promotional_code WHERE code_id=$promotionalid";
			$ret_promot = $db->query($sql_promot);
			if ($db->num_rows($ret_promot))
			{
				$row_promot 	= $db->fetch_array($ret_promot);
				$vnum			= stripslashes($row_promot['code_number']); 
			}
			 // Check whether any order has been placed with the current voucher number
			$sql_order= "SELECT a.order_id ,a.customers_customer_id, a.order_date,a.order_custtitle,a.order_custfname,
								a.order_custmname,a.order_custsurname,a.order_totalprice,a.order_status  
									FROM 
										orders a,order_promotionalcode_track b
									WHERE 
										a.promotional_code_code_id=$promotionalid
										AND a.promotional_code_code_id = b.promotional_code_code_id  
										AND a.order_id = b.orders_order_id   
										AND a.order_status NOT IN ('NOT_AUTH') 
										AND a.promotional_code_code_number ='".addslashes($vnum)."' 
										AND a.sites_site_id=$ecom_siteid 
									ORDER BY 
										a.order_date DESC";
			$ret_order = $db->query($sql_order);
			$table_headers = array('Slno.','Order Id','Order Date','Customer name','Order Total','Order Status');
			$header_positions=array('center','center','center','center','center','center');
			$colspan = count($table_headers);
							?> 
							<div class="editarea_div">
					<table width="100%" cellpadding="1" cellspacing="1" border="0">
					<tr>
							  <td colspan="6" align="left" class="helpmsgtd"><div class="helpmsg_divcls"><?=get_help_messages('ADD_ORDER_PROMOCODE')?></div></td>
					  </tr>
					<?php 
						if($alert)
						{
					?>
							<tr>
								<td colspan="<? echo $colspan?>" align="center" class="errormsg"><?php echo $alert?></td>
							</tr>
				 <?php
				 		}
						if ($db->num_rows($ret_order))
						{
							echo table_header($table_headers,$header_positions); 
							$cnt = 1;
							while ($row_order = $db->fetch_array($ret_order))
							{
								$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
								$date = dateFormat($row_order['order_date'],'');
								$ordstats = getorderstatus_Name($row_order['order_status']);
								$cancel_stat = ($row_order['order_status']=='CANCELLED')?' <span class="redtext">'.$ordstats.' </span>':$ordstats;
							
							?>
								
								<tr>
									<td width="5%" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
									<td align="center" class="<?php echo $cls?>"><a href="home.php?request=orders&fpurpose=ord_details&edit_id=<?=$row_order['order_id']?>" class="edittextlink"><?php echo stripslashes($row_order['order_id']);?></a></td>
									<td align="center" class="<?php echo $cls?>"><?php echo stripslashes($date);?></td>
									<td align="center" class="<?php echo $cls?>">
									<?php
										if($row_order['customers_customer_id']!=0)
										{
									?>
									<a href="home.php?request=customer_search&fpurpose=edit&checkbox[0]=<?php echo $row_order['customers_customer_id']?>" class="edittextlink"><?php echo stripslashes($row_order['order_custtitle']).".".stripslashes($row_order['order_custfname'])." ".stripslashes($row_order['order_custlname']);?></a>
									<?php
										}
										else
										{
									?>
									<?php echo stripslashes($row_order['order_custtitle']).".".stripslashes($row_order['order_custfname'])." ".stripslashes($row_order['order_custlname']);?>
									<?php	
										}
									?>
									</td>
									<td align="center" class="<?php echo $cls?>"><?php echo display_price($row_order['order_totalprice']);?></td>
									<td align="center" class="<?php echo $cls?>"><?php echo $cancel_stat?></td>
								</tr>
							<?php
							}
						}
						else
						{
						?>
								<tr>
								  <td colspan="6" align="center" valign="middle" class="norecordredtext_small">
								  <input type="hidden" name="order_norec" id="order_norec" value="1" />
								  No Orders found.</td>
								</tr>
						<?php
						}
						?>	
				</table></div>	
	<?php	
	}
	function show_customer_list($promotionalid,$alert='')
	{
		global $db,$ecom_siteid;
		// Get the voucher number related to current voucher id
		$sql_promot = "SELECT code_id,code_number FROM promotional_code WHERE code_id=$promotionalid";
		$ret_promot = $db->query($sql_promot);
		if ($db->num_rows($ret_promot))
		{
			$row_promot 	= $db->fetch_array($ret_promot);
			$vnum			= stripslashes($row_promot['code_number']); 
		}
		// Check whether any order has been placed with the current voucher number
		$sql_track_order= "SELECT track_id, customers_customer_id , count(customers_customer_id) AS cnt, code_number, promotional_code_code_id,orders_order_id
							 FROM order_promotionalcode_track 
							 	WHERE promotional_code_code_id=$promotionalid  
									  AND code_number ='".addslashes($vnum)."' 
									  AND sites_site_id=$ecom_siteid 
									  AND customers_customer_id >0
									  	 GROUP BY customers_customer_id	 
									  		";
		$ret_track_order = $db->query($sql_track_order);
		$table_headers = array('Slno.','Customer name','Total Used',' Details ');
		$header_positions=array('center','left','center','center');
		$colspan = count($table_headers);
							?>
							<div class="editarea_div">
					<table width="100%" cellpadding="1" cellspacing="1" border="0">
					<tr>
							  <td colspan="6" align="left" class="helpmsgtd"><div class="helpmsg_divcls"><?=get_help_messages('ADD_ORDER_PROMOCODE_CUST')?></div></td>
					  </tr>
					<?php 
						if($alert)
						{
					?>
							<tr>
								<td colspan="<? echo $colspan?>" align="center" class="errormsg"><?php echo $alert?></td>
							</tr>
				 <?php
				 		}
						if ($db->num_rows($ret_track_order))
						{
							echo table_header($table_headers,$header_positions); 
							$cnt = 1;
							while ($row_track_order = $db->fetch_array($ret_track_order))
							{
								$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
							//	
								
								$custsql  = "SELECT customer_title, customer_fname, customer_mname, customer_surname 
												FROM customers 
													WHERE customer_id='".$row_track_order['customers_customer_id']."'";
								$custres  = $db->query($custsql);
								$custrow  = $db->fetch_array($custres);		
								$custname = $custrow['customer_title']." ".$custrow['customer_fname']." ".$custrow['customer_mname']." ".$custrow['customer_surname'];   			
								
							?>
								
								<tr>
									<td width="5%" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
									
									<td align="left" class="<?php echo $cls?>"><a href="home.php?request=customer_search&fpurpose=edit&checkbox[0]=<?php echo $row_track_order['customers_customer_id']?>" class="edittextlink"><?php echo stripslashes($custname);?></a></td>
									<td align="center" class="<?php echo $cls?>"><?php echo $row_track_order['cnt'];?></td>
									<td align="center" class="<?php echo $cls?>"> <a href="#" onclick="track_details('<?=$row_track_order['track_id']?>')" class="edittextlink"> Details 
									  <input type="hidden" name="hid_det_<?=$row_track_order['track_id']?>" />
									</a></td>
								</tr>
								<tr id="det_<?=$row_track_order['track_id']?>" style="display:none;">
									<td colspan="1" align="left">&nbsp;</td>
									<td   colspan="3" align="right"> 
										<table width="70%" >
										 <?PHP
	    $table_sub_headers = array('Slno.','Order Id','Order Date',' Order Total ');
		$header_sub_positions=array('center','center','center','center');
		$colspan_sub = count($table_sub_headers);
		echo table_header($table_sub_headers,$header_sub_positions); 
											$count = 1;
											$sql = "SELECT orders.order_id, orders.order_date, orders.order_totalprice 
														 FROM orders, order_promotionalcode_track 
														 	WHERE orders.customers_customer_id='".$row_track_order['customers_customer_id']."' 
																  AND order_promotionalcode_track.orders_order_id=orders.order_id 
																  AND order_promotionalcode_track.promotional_code_code_id=$promotionalid  
								  							      AND order_promotionalcode_track.code_number ='".$vnum."'  	";
											$res = $db->query($sql);
											while($row = $db->fetch_array($res))				
											{
											$clss = ($count%2==0)?'listingtablestyleA':'listingtablestyleB';
											$date = dateFormat($row['order_date'],'');
										 ?>	
											<tr>
												<td width="5%" align="center" class="<?php echo $clss?>"> <? echo $count++; ?> </td>
												<td width="25%" align="center" class="<?php echo $clss?>"> <a href="home.php?request=orders&fpurpose=ord_details&edit_id=<?=$row['order_id']?>" class="edittextlink"><? echo $row['order_id']; ?></a> </td>
												<td width="25%" align="center" class="<?php echo $clss?>"> <? echo $date; ?> </td>
												<td width="45%" align="center" class="<?php echo $clss?>"> <? echo $row['order_totalprice']; ?> </td>
											</tr>
										 <?PHP } ?>	
										</table>
									</td>
								</tr>
							<?php
							}
						}
						else
						{
						?>
								<tr>
								  <td colspan="6" align="center" valign="middle" class="norecordredtext_small">
								  <input type="hidden" name="order_norec" id="order_norec" value="1" />
								  No Customers found.</td>
								</tr>
						<?php
						}
						?>	
				</table>	</div>
	<?php	
	}
?>
