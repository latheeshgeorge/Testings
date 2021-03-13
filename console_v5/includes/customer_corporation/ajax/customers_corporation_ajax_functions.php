<?php
	 // ###############################################################################################################
	//  Function which holds the display logic of states to be shown when called using ajax;				
	// ###############################################################################################################
	function show_customer_maniinfo($corporation_id,$alert='')
	{
	 global $db,$ecom_siteid ;
	$sql="SELECT corporation_name,corporation_type,corporation_regno,corporation_vatno,corporation_otherdetails,corporation_admin_id,corporation_billing_id,corporation_discount_method,corporation_discount,corporation_allow_product_discount,corporation_costplus FROM customers_corporation WHERE sites_site_id=$ecom_siteid AND corporation_id=".$corporation_id." LIMIT 1";
	$res=$db->query($sql);
	$row=$db->fetch_array($res);
	?>
	 <div class="sorttd_div" >
	<table border="0" cellspacing="0" cellpadding="0" width="100%" class="fieldtable">
	<?php 
		if($alert)
		{			
		?>
        <tr>
          <td colspan="4" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
        </tr>
		<?
		}
		?>
        <tr>
          <td width="14%" align="left" valign="middle" class="tdcolorgray" >Business Name  <span class="redtext">*</span> </td>
          <td width="32%" align="left" valign="middle" class="tdcolorgray"><input name="corporation_name" type="text" id="corporation_name" value="<?=$row['corporation_name']?>"  maxlength="100"/></td>
          <td width="17%" align="left" valign="middle" class="tdcolorgray">Reg No </td>
          <td width="37%" align="left" valign="middle" class="tdcolorgray"><input name="corporation_regno" type="text" id="corporation_regno"  value="<?php  echo $row['corporation_regno'];?>" /></td>
        </tr>
		 
		 <tr  >
		   <td align="left" valign="middle" class="tdcolorgray" >Business Type</td>
		   <td align="left" valign="middle" class="tdcolorgray"><?php
						$type_arr = array('Sole Trade'=>'Sole Trade','Partership'=>'Partership','Limited'=>'Limited');
						echo generateselectbox('corporation_type',$type_arr,$row['corporation_type'],'','handletype_change(this.value)');
					  ?>&nbsp;
					  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CUST_CORP_TYPE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		   <td align="left" valign="middle" class="tdcolorgray">Vat No </td>
		   <td align="left" valign="middle" class="tdcolorgray"><input name="corporation_vatno" type="text" id="corporation_vatno"  value="<?php  echo $row['corporation_vatno'];?>" /></td>
    </tr>
		<?php /* <tr  >
		   <td align="left" valign="middle" class="tdcolorgray" >Discount (%) <?php //Discount Method ?> </td>
		   <td align="left" valign="middle" class="tdcolorgray">
		   <input name="corporation_discount" type="text" id="corporation_discount"  value="<?php  echo $row['corporation_discount'];?>" size="4" />
		   <input type="hidden" name='corporation_discount_method' id='corporation_discount_method' value='Discount' />
		   <?php
					  	$type_arr = array('Discount'=>'Discount','Cost Plus'=>'Cost Plus');
						//echo generateselectbox('corporation_discount_method',$type_arr,$row['corporation_discount_method'],'','handletype_change(this.value)');
					  ?>&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CUST_CORP_DISCMTHD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		   <td align="left" valign="middle" class="tdcolorgray" >Allow Product Discount</td>
		   <td align="left" valign="middle" class="tdcolorgray">
		   &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CUST_CORP_PRODDISC')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		 </tr>*/
		 ?>
		 <?php /*<tr id="tr_disc" >
		   <td align="left" valign="middle" class="tdcolorgray" ></td>
		   <td align="left" valign="middle" class="tdcolorgray"><input name="corporation_discount" type="text" id="corporation_discount"  value="<?php  echo $row['corporation_discount'];?>" />
		   (%)</td>
		   <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
		   <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
    </tr>
		 <tr id="tr_cost">
          <td width="14%" align="left" valign="middle" class="tdcolorgray" >Cost Plus </td>
          <td width="32%" align="left" valign="middle" class="tdcolorgray"><input name="corporation_costplus" type="text" id="corporation_costplus"  value="<?php  echo $row['corporation_costplus'];?>" />
           (%)</td>
          <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
	    </tr>
	    */
		?>
       
         <tr>
           <td align="left" valign="top" class="tdcolorgray" >Other Details </td>
           <td colspan="3" align="left" valign="middle" class="tdcolorgray" ><textarea name="corporation_otherdetails" cols="35" rows="5" id="otherdetails"><?=$row['corporation_otherdetails']?></textarea></td>
		  </tr>
		  </table>
		  </div>
		  
		   <div class="sorttd_div" >
		   <table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td align="right" valign="middle" class="tdcolorgray" >
				  <input name="Submit" type="submit" class="red" value="Submit" />
				</td>
			</tr>
			</table>
			</div>
	<?

	}
	function show_department_maniinfo($department_id,$alert)
	{
			global $db,$ecom_siteid ;
			$sql="SELECT department_name,department_building,department_street,department_town,country_id,state_id,department_postcode,department_phone,department_fax,department_hide FROM customers_corporation_department WHERE sites_site_id=$ecom_siteid AND department_id=".$department_id." LIMIT 1";
			$res=$db->query($sql);
			$row=$db->fetch_array($res);
			?><div class="sorttd_div" >
		<table border="0" cellspacing="0" cellpadding="0" width="100%">	
		<?php 
			if($alert)
			{			
				?>
				<tr>
				<td  align="center" valign="middle" class="errormsg" colspan="2" ><?=$alert?></td>
				</tr>
			<?
			}
		?>
			<tr><td width="48%" class="tdcolorgray" valign="top">
		 <table cellpadding="0" cellspacing="0" border="0" width="100%">	
		<tr>
		  <td  align="left" valign="middle" class="tdcolorgray" >Department Name <span class="redtext">*</span> </td>
		  <td  align="left" valign="middle" class="tdcolorgray"><input name="department_name" type="text" id="department_name" value="<?=$row['department_name']?>" /></td>
		   </tr>
		<tr  >
		  <td align="left" valign="middle" class="tdcolorgray" >Department Building </td>
		  <td align="left" valign="middle" class="tdcolorgray"><input name="department_building" type="text" id="department_building"  value="<?php  echo $row['department_building'];?>" /></td>
 		   </tr>
		<tr  >
		  <td align="left" valign="middle" class="tdcolorgray" >Street</td>
		  <td align="left" valign="middle" class="tdcolorgray"><input name="department_street" type="text" id="department_street"  value="<?php  echo $row['department_street'];?>" /></td>
		   </tr>
		<tr  >
		  <td align="left" valign="middle" class="tdcolorgray" >Town </td>
		  <td align="left" valign="middle" class="tdcolorgray"><input name="department_town" type="text" id="department_town"  value="<?php  echo $row['department_town'];?>" /></td>
		   </tr>
		<tr >
		  <td align="left" valign="middle" class="tdcolorgray" >Hidden</td>
		  <td align="left" valign="middle" class="tdcolorgray"><input type="radio" name="department_hide" value="1" <? if($row['department_hide']==1) echo "checked";?> />&nbsp;Yes&nbsp;<input type="radio" name="department_hide"  value="0" <? if($row['department_hide']==0) echo "checked";?>  />&nbsp;No&nbsp;
		  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_DEP_CUST_CORP_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		</tr>
		</table>
	 </td>
	 <td width="52%" class="tdcolorgray" valign="top">
		 <table cellpadding="0" cellspacing="0" border="0" width="100%">
			 <tr>
				  <td width="40%"  align="left" valign="middle" class="tdcolorgray" >Country</td>
				  <td width="60%"  align="left" valign="middle" class="tdcolorgray">
				  <select class="input" name="country_id" onchange="changestate(0,0);">
				  <option value="0">-select-</option>
				  <?
				  $sql_country="SELECT country_id,country_name FROM general_settings_site_country WHERE sites_site_id=".$ecom_siteid." AND country_hide=1" ;
				  $res_country=$db->query($sql_country);
				  while($row_country=$db->fetch_array($res_country))
				  {
				  ?>
				  <option value="<?=$row_country['country_id']?>" <? if($row['country_id']==$row_country['country_id']) echo "selected";?>><?=$row_country['country_name']?></option>
				  <?
				  }
				  ?>
			   </select>		  </td>
			</tr>
			<tr>
				<td colspan="2" align="left" >
				<div id="state_tr"  align="left" >	<? show_display_state_list($row['country_id'],$row['state_id']) ?>	</div>		</td>
			</tr>
			<tr id="state_other_tr" style=" display:none">
				<td align="right" valign="middle" class="tdcolorgray" >Enter Other State Here<span class="redtext">*</span></td>
				<td align="left" valign="middle" class="tdcolorgray"> <input type="text" name="other_state" id="other_state"  /></td>
			</tr> 
			 <tr>
				  <td align="left" valign="middle" class="tdcolorgray">Post code  </td>
				  <td align="left" valign="middle" class="tdcolorgray"><input name="department_postcode" type="text" id="department_postcode"  value="<?php  echo $row['department_postcode'];?>" /></td>
	
			 </tr>
			 <tr>
				   <td align="left" valign="middle" class="tdcolorgray" >Phone</td>
				   <td align="left" valign="middle" class="tdcolorgray"><input name="department_phone" type="text" id="department_phone"  value="<?php  echo $row['department_phone'];?>" /></td>
			 </tr>
			 <tr>
				   <td align="left" valign="middle" class="tdcolorgray">Fax</td>
				   <td align="left" valign="middle" class="tdcolorgray"><input name="department_fax" type="text" id="department_fax"  value="<?php  echo $row['department_fax'];?>" /></td>
			 </tr>	
		</table>
	</td>
	</tr>
	</table></div>
	
	<div class="sorttd_div" >
		<table border="0" cellspacing="0" cellpadding="0" width="100%">	
		<tr>
		<td align="RIGHT" valign="middle" class="tdcolorgray" colspan="2"> <input name="Submit" type="submit" class="red" value="Save" /></td>
		</tr>
		</table>
	</div>
			<?
	}
	function show_display_state_list($country_id,$state_id=0)
	{
		global $db,$ecom_siteid ;
		if($country_id)
		{
		$sql_state="SELECT state_id,state_name FROM general_settings_site_state WHERE sites_site_id=".$ecom_siteid." AND general_settings_site_country_country_id=".$country_id.""; 
	    $ret_state = $db->query($sql_state);	
		
			?>
			<table width="100%" cellpadding="0" cellspacing="0" border="0" >
			<tr>
          	<td width="40%" align="left" valign="middle" class="tdcolorgray" >State/County</td>
          	<td width="60%" align="left" valign="middle" class="tdcolorgray">
			  <select class="input" name="customer_statecounty" onchange="state_other();" >
			  <option value="">-select-</option>
			  <?
			  while($row_state=$db->fetch_array($ret_state))
			  {
			  ?>
			  <option value="<?=$row_state['state_id']?>" <? if($state_id==$row_state['state_id']) echo "selected";?>><?=$row_state['state_name']?></option>
			  <?
			  }
			  ?>
			   <option value="-1">-other-</option>
			  </select>
		  	</td>
          </tr>
		  </table>
			<?
		}	
	}

	// ###############################################################################################################
	// 				Function which holds the display logic of departments added the corporation to be shown when called using ajax;
	// ###############################################################################################################
	function show_department_list($edit_id,$alert='')
	{
		global $db,$ecom_siteid;
			 // Get the list of departments under this coustomer corporation
				$sql_departments = "SELECT department_id,department_name,department_hide  FROM customers_corporation_department WHERE  customers_corporation_corporation_id=$edit_id ORDER BY department_name";
				$ret_departments = $db->query($sql_departments);
	?>
					<div class="sorttd_div" >
					<table width="100%" cellpadding="1" cellspacing="1" border="0">
							<?php
				 // Check whether categories are Assiged to this Adverts
					$sql_departments_in_corporation = "SELECT department_id FROM customers_corporation_department
								 WHERE customers_corporation_corporation_id=$edit_id";
					$ret_departments_in_corporation = $db->query($sql_departments_in_corporation);
?>
				<tr>
					<td colspan="4" class="helpmsgtd" align="left"><div class="helpmsg_divcls"><?=get_help_messages('EDIT_BUSINESS_CUSTOMER_DEPARTMENT_SUBHEAD')?></div>
					</td>
				</tr>
				<?	if($alert)
						{
					?>
							<tr>
								<td colspan="6" align="center" class="errormsg"><?php echo $alert?></td>
							</tr>
				<? }?>			
				<tr>
					<td colspan="4" align="right" valign="middle" class="tdcolorgray" ><input name="Adddepartments" type="button" class="red" id="Adddepartments" value="Add Departments" onclick="document.frmEditCustomerCorporation.fpurpose.value='add_departments';document.frmEditCustomerCorporation.submit();" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CUST_CORP_ASS_DEP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
					<?php
					if ($db->num_rows($ret_departments_in_corporation))
					{
					?>
						<div id="departmentunassign_div" class="unassign_div" >
						Change Hidden Status to 
						<?php
							$department_status = array(0=>'No',1=>'Yes');
							echo generateselectbox('department_chstatus',$department_status,0);
						?>
						<input name="category_chstatus" type="button" class="red" id="department_chstatus" value="Change" onclick="call_ajax_changestatuspagesall('department','checkboxdepartment[]')" />
						<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CUST_CORP_ASS_DEP_CHSTATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
									
						&nbsp;&nbsp;&nbsp;<input name="department_unassign" type="button" class="red" id="department_unassign" value="Delete" onclick="call_ajax_deleteall('department','checkboxdepartment[]')" />
						<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CUST_CORP_UNASS_DEP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>					</div>	
					<?php
					}				
					?></td>
       			 </tr>

				 <?php
				 		
						if ($db->num_rows($ret_departments))
						{
							$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditCustomerCorporation,\'checkboxdepartment[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditCustomerCorporation,\'checkboxdepartment[]\')"/>','Slno.','Department Name','Hidden');
							$header_positions=array('center','center','left','center');
							$colspan = count($table_headers);
							echo table_header($table_headers,$header_positions); 
							$cnt = 1;
							while ($row_departments = $db->fetch_array($ret_departments))
							{
								$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
							?>
								
								<tr>
									<td width="5%" align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxdepartment[]" value="<?php echo $row_departments['department_id'];?>" /></td>
									<td width="5%" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
									<td align="left" class="<?php echo $cls?>"><a href="home.php?request=customer_corporation&fpurpose=edit_department&corporation_id=<?=$edit_id?>&checkbox[0]=<?php echo $edit_id;?>&department_id=<?=$row_departments['department_id']?>&pass_sort_by=<?=$_REQUEST['sort_by']?>&pass_sort_order=<?=$_REQUEST['sort_order']?>&pass_records_per_page=<?=$_REQUEST['records_per_page']?>&pass_search_name=<?=$_REQUEST['search_name']?>&pass_start=<?=$_REQUEST['start']?>&pass_pg=<?=$_REQUEST['pg']?>" class="edittextlink" title="Edit"><?php echo stripslashes($row_departments['department_name']);?></a></td>
									<td class="<?php echo $cls?>" align="center"><?php echo ($row_departments['department_hide']==1)?'Yes':'No'?></td>
								</tr>
							<?php
							}
						}
						else
						{
						?>
								<tr>
								  <td colspan="4" align="center" valign="middle" class="norecordredtext_small"><input type="hidden" name="department_norec" id="department_norec" value="1" />
								  No Departments Added for this Business Customer.</td>
								</tr>
						<?php
						}
						?>	
				</table>	
				</div>
	<?php	
	}
	
	// ###############################################################################################################
	// 				Function which holds the display logic of customers added the Department to be shown when called using ajax;
	// ###############################################################################################################
	function show_customers_list($edit_id,$alert='',$pg=0,$records_per_page)
	{   
		global $db,$ecom_siteid;
		if (!($pg > 0) || $pg == 0) { $pg = 1; }
		$start = ($pg - 1) * $records_per_page;#Starting record.
		$count_no = ($pg == 1)?0:$records_per_page*($pg-1);
		
		$sql_customers_in_departments = "SELECT customer_id FROM customers
						 WHERE customers_corporation_department_department_id=$edit_id";
		$ret_customers_in_departments = $db->query($sql_customers_in_departments);
		$cust_count = $db->num_rows($ret_customers_in_departments);
		$pages = ceil($cust_count/$records_per_page);
		//if($no_of_pages > $pages){
		$no_of_pages =$pages;
		//}
		
			 // Get the list of departments under this coustomer corporation
		$sql_customers = "SELECT customer_id,customer_fname,customer_mname,customer_title,customer_surname,customer_email_7503,customer_hide,customer_activated,customers_corporation_department_department_id FROM customers WHERE  customers_corporation_department_department_id=$edit_id AND sites_site_id = $ecom_siteid ORDER BY customer_fname LIMIT $start,$records_per_page ";
		$ret_customers = $db->query($sql_customers);
	?><div class="sorttd_div" >
		<table width="100%" cellpadding="1" cellspacing="1" border="0">
		 <?php
		 // Check whether customers are Assiged to this Departments
			$sql_customers_in_departments = "SELECT customer_id FROM customers
						 WHERE customers_corporation_department_department_id=$edit_id";
			$ret_customers_in_departments = $db->query($sql_customers_in_departments);
			$cust_count = $db->num_rows($ret_customers_in_departments);
			//$no_of_recs = 2;
		 ?>
		<tr>
			<td colspan="6" class="helpmsgtd" align="left"><div class="helpmsg_divcls"><?=get_help_messages('EDIT_DEPARTMENT_CUSTOMERS_SUBHEAD')?></div>
			</td>
		</tr>
		<?
		if($alert)
		{
	   ?>
			<tr>
				<td colspan="6" align="center" class="errormsg"><?php echo $alert?></td>
			</tr>
	<?php
		}
		?>
		 <tr>
          <td colspan="6" align="right" valign="middle" class="tdcolorgray" ><input name="Addcustomers" type="button" class="red" id="Addcustomers" value="Add Customers" onclick="document.frmEditCorporationDepratment.fpurpose.value='add_customers';document.frmEditCorporationDepratment.submit();" />
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_DEP_CUST_CORP_ASS_CUST')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
				<?php
				
				if ($cust_count)
				{
				?>
					<div id="customersunassign_div" class="unassign_div" >
					&nbsp;&nbsp;&nbsp;<input name="customers_unassign" type="button" class="red" id="customers_unassign" value="Un Assign" onclick="call_ajax_deleteall('customers','checkboxcustomers[]',<?=$records_per_page?>)" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_DEP_CUST_CORP_UNASS_CUST')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>					</div>	
				<?php
				}				
				?></td>
        </tr>
		<?php
		
		if ($cust_count)
		{
		?>
		<tr>
			<td colspan="6" align="center"  class="listeditd"> 
			<?php 
			for($i=1;$i<=$no_of_pages;$i++)
			  {
				if($i!=$pg)
				{
				?>
	
				<a href="#" onclick="call_ajax_showlistallWithPaging('customers',<?=$no_of_pages?>,<?=$i?>,<?=$records_per_page?>)"; class="edittextlink"><?=$i?></a>&nbsp;<?php }else{
				echo $i;
				}
			}
		?>
		<?php 
			if($no_of_pages)
			echo " Page <b>$pg</b> of <b>$no_of_pages</b> Pages ";
		?>
		</td>
		</tr>
	<?php 
	 }
		
		if ($db->num_rows($ret_customers))
		{
		
			$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditCorporationDepratment,\'checkboxcustomers[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditCorporationDepratment,\'checkboxcustomers[]\')"/>','Slno.','Customer Name','CustomerEmail','Hide?','Is Activated');
			$header_positions=array('center','center','left','center','center','center');
			$colspan = count($table_headers);
			echo table_header($table_headers,$header_positions); 
			$cnt = 1;
			while ($row_customers = $db->fetch_array($ret_customers))
			{
				$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
			?>
				
				<tr>
					<td width="5%" align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxcustomers[]" value="<?php echo $row_customers['customer_id'];?>" /></td>
					<td width="5%" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
					<td align="left" class="<?php echo $cls?>"><a href="home.php?request=customer_search&fpurpose=edit&corporation_id=<?=$edit_id?>&department_id=<?=$edit_id?>&customer_id=<?=$row_customers['customer_id']?>&start=&pg=&records_per_page= &sort_by=&sort_order=" class="edittextlink" title="Edit"><?php echo stripslashes($row_customers['customer_fname']).'&nbsp;',stripslashes($row_customers['customer_mname']).'&nbsp;',stripslashes($row_customers['customer_surname']);?></a></td>
					<td align="center" class="<?php echo $cls?>"><?php echo ($row_customers['customer_email_7503'])?></td>
					<td align="center" class="<?php echo $cls?>"><?php echo ($row_customers['customer_hide'])?'Yes':'No'?></td>
				    <td align="center" class="<?php echo $cls?>"><?php echo ($row_customers['customer_activated'])?'Yes':'No'?></td>
				</tr>
			<?php
			}
		}
		else
		{
		?>
				<tr>
				  <td colspan="6" align="center" valign="middle" class="norecordredtext_small"><input type="hidden" name="customers_norec" id="customers_norec" value="1" />
				  No Customers Added for this Department.</td>
				</tr>
		<?php
		}
			?>	
	</table></div>	
	<?php	
	}
		
?>