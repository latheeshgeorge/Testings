<?php

    // ###############################################################################################################
	//  Function which holds the display logic of states to be shown when called using ajax;				
	// ###############################################################################################################
	function show_display_state_list($country_id,$state_id=0)
	{
		global $db,$ecom_siteid ;
		$sql_state="SELECT state_id,state_name FROM general_settings_site_state WHERE sites_site_id=".$ecom_siteid." AND general_settings_site_country_country_id=".$country_id." AND state_hide=0"; 
	    $ret_state = $db->query($sql_state);	
		if ($db->num_rows($ret_state))
		{
			?>
			<table width="100%" cellpadding="0" cellspacing="0" border="0" align="left">
			<tr>
          	<td width="35%" align="left" valign="middle" class="tdcolorgray" >State/County</td>
          	<td width="65%" align="left" valign="middle" class="tdcolorgray">
			  <select class="input" name="customer_statecounty"  >
			  <option value="">-select-</option>
			  <?
			  while($row_state=$db->fetch_array($ret_state))
			  {
			  ?>
			  <option value="<?=$row_state['state_id']?>" <? if($row_state['state_id']==$state_id) echo "selected";?>><?=$row_state['state_name']?></option>
			  <?
			  }
			  ?>
			  </select>
		  	</td>
          </tr>
		  </table>
			<?
		}
	}
	// ###############################################################################################################
	// 				Function which holds the display logic of products assigned to the page group to be shown when called using ajax;
	// ###############################################################################################################
	function show_product_list($edit_id,$alert='')
	{
		global $db,$ecom_siteid;
			 // Get the list of paroduct assigned for the static page group
				$sql_products = "select p.product_id,p.product_name,p.product_webprice,spdp.id,spdp.product_hide FROM
products p,customer_fav_products spdp
WHERE spdp.products_product_id=p.product_id  AND spdp.sites_site_id=$ecom_siteid
AND customer_customer_id=$edit_id ORDER BY product_name";
//echo $sql_products;
				$ret_products = $db->query($sql_products);
	?>
					<table width="100%" cellpadding="1" cellspacing="1" border="0">
					<?php 
						if($alert)
						{
					?>
							<tr>
								<td colspan="6" align="center" class="errormsg"><?php echo $alert?></td>
							</tr>
				 <?php
				 		}
						if ($db->num_rows($ret_products))
						{
							$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditCustomer,\'checkboxproducts[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditCustomer,\'checkboxproducts[]\')"/>','Slno.','Product Name','Price','Hidden');
							$header_positions=array('center','center','left','center','center','center');
							$colspan = count($table_headers);
							echo table_header($table_headers,$header_positions); 
							$cnt = 1;
							while ($row_products = $db->fetch_array($ret_products))
							{
								$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
							?>
								
								<tr>
									<td width="5%" align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxproducts[]" value="<?php echo $row_products['id'];?>" /></td>
									<td width="5%" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
									<td align="left" class="<?php echo $cls?>"><a href="home.php?request=products&fpurpose=edit&checkbox[0]=<?php echo $row_products['product_id'];?>" class="edittextlink" title="Edit"><?php echo stripslashes($row_products['product_name']);?></a></td>
									<td align="center" class="<?php echo $cls?>"><?php echo stripslashes($row_products['product_webprice']);?></a></td>

									<td class="<?php echo $cls?>" align="center"><?php echo ($row_products['product_hide']==1)?'Yes':'No'?></td>
								</tr>
							<?php
							}
						}
						else
						{
						?>
								<tr>
								  <td colspan="5" align="center" valign="middle" class="norecordredtext_small">No product Assigned to this Page Group. <input type="hidden" name="products_norec" id="products_norec" value="1" /></td>
								</tr>
						<?php
						}
						?>	
				</table>	
	<?php	
	}
	
	
?>