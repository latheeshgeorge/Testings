<?php

    // ###############################################################################################################
	//  Function which holds the display logic of states to be shown when called using ajax;				
	// ###############################################################################################################
	function show_customermaininfo($cust_id,$alert='',$mod)
	{
		global $db,$ecom_siteid ;
		
		$sql_customer	= "SELECT *,date_format(customer_payonaccount_laststatementdate,'%d %b %Y') state_date FROM customers  WHERE customer_id=".$cust_id." LIMIT 1";
		$res_customer	= $db->query($sql_customer);
		$row_customer 	= $db->fetch_array($res_customer);
		$customer_id = $cust_id;
		?>
		 <div class="editarea_div">
		<table border="0" cellspacing="0" cellpadding="0" width="100%" class="fieldtable">
		<?php 
		if($alert)
		{			
		?>
        <tr>
          <td colspan="2" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
        </tr>
		<?
		}
		?>
		<tr>
		   <td align="left" class="seperationtd" colspan="2"><table width="100%" border="0" cellspacing="1" cellpadding="1">
             <tr>
               <td width="14%" align="left" colspan="2">Account Type </td>
               <td>
			   <select name="customer_accounttype" id="customer_accounttype">
			   <option value="personal" <?php echo ($row_customer['customer_accounttype']=='personal')?'selected':''?>>Personal Account</option>
			   <option value="business" <?php echo ($row_customer['customer_accounttype']=='business')?'selected':''?>>Business Account</option>
               </select>
               </td>
             </tr>
           </table></td>
		 </tr>
		<?php
		$cur_pos = 'Top';
		$formname = 'frmEditCustomer';
		if($mod=='edit')
		{
			include 'includes/customer_search/show_dynamic_fields_edit.php';
		}
		else
		{
			include '../includes/customer_search/show_dynamic_fields_edit.php';
		}
		?>  
		 
		 <tr>
		<td colspan="2" align="left" class="seperationtd">Personal Details</td>
		</tr>
		<?
		$cur_pos = 'TopInStatic';
		$formname = 'frmEditCustomer';
		if($mod=='edit')
		{
			include 'includes/customer_search/show_dynamic_fields_edit.php';
		}
		else
		{
			include '../includes/customer_search/show_dynamic_fields_edit.php';
		}
		?>
        <tr>
		<td width="50%" valign="top"  class="tdcolorgray">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		
		
		
	
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Customer Title <span class="redtext">*</span></td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <select class="input" name="customer_title"  />
		  <option value="" >-select-</option>
		  <option value="Mr." <? if($row_customer['customer_title']=='Mr.') echo "selected";?> >Mr.</option>
		  <option value="Ms." <? if($row_customer['customer_title']=='Ms.') echo "selected";?>>Ms.</option>
		  <option value="Mrs." <? if($row_customer['customer_title']=='Mrs.') echo "selected";?>>Mrs.</option>
		  <option value="Miss." <? if($row_customer['customer_title']=='Miss.') echo "selected";?>>Miss.</option>
		  <option value="M/s." <? if($row_customer['customer_title']=='M/s.') echo "selected";?>>M/s.</option>
		  <option value="Dr." <?php if($row_customer['customer_title']=='Dr.') echo "selected";?>>Dr.</option>
		  <option value="Sir." <?php if($row_customer['customer_title']=='Sir.') echo "selected";?>>Sir.</option>
		  <option value="Rev." <?php if($row_customer['customer_title']=='Rev.') echo "selected";?>>Rev.</option>
		 </select>		  </td>
        </tr>
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >First Name <span class="redtext">*</span></td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_fname" value="<?=$row_customer['customer_fname']?>" maxlength="100" />		  </td>
        </tr>
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Middle Name</td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_mname" value="<?=$row_customer['customer_mname']?>"  />		  </td>
        </tr>
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Surname</td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_surname" value="<?=$row_customer['customer_surname']?>"  />		  </td>
        </tr>
		<tr>
          <td width="28%" align="left" valign="middle" class="tdcolorgray" >Building Name / No.</td>
          <td width="72%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_buildingname" value="<?=$row_customer['customer_buildingname']?>"  />		  </td>
        </tr>
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Street Name</td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_streetname" value="<?=$row_customer['customer_streetname']?>"  />		  </td>
        </tr>
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Town/City</td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_towncity" value="<?=$row_customer['customer_towncity']?>"  />		  </td>
        </tr>
		</table></td>
		<td valign="top" class="tdcolorgray">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		
		<tr>
          <td width="35%" align="left" valign="middle" class="tdcolorgray" >Country</td>
          <td width="65%" align="left" valign="middle" class="tdcolorgray">
		  <select class="input" name="country_id"<?php /*?> onchange="changestate(0,0);"<?php */?>>
		  <option value="0">-select-</option>
		  <?
		  $sql_country="SELECT country_id,country_name FROM general_settings_site_country WHERE sites_site_id=".$ecom_siteid."" ;
		 
		  $res_country=$db->query($sql_country);
		  while($row_country=$db->fetch_array($res_country))
		  {
		  ?>
		  <option value="<?=$row_country['country_id']?>" <? if($row_customer['country_id']==$row_country['country_id']) echo "selected";?>><?=$row_country['country_name']?></option>
		  <?
		  }
		  ?>
		  </select>		  </td>
        </tr>
		<?php /*?><tr>
		<td colspan="2" align="left" >
		<div id="state_tr"  align="left" >	<? show_display_state_list($row_customer['country_id'],$row_customer['customer_statecounty']) ?>	</div>		</td>
		</tr><?php */?>
		<tr >
				<td align="left" valign="middle" class="tdcolorgray" >State</td>
				<td align="left" valign="middle" class="tdcolorgray"> <input type="text" name="customer_statecounty" id="customer_statecounty" value="<?php echo $row_customer['customer_statecounty']?>"  /></td>
		</tr>
		<tr>
          <td width="35%" align="left" valign="middle" class="tdcolorgray" >Postcode <?php if($ecom_siteid!=76){ ?><span class="redtext">*</span><?php } ?></td>
          <td width="65%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_postcode" value="<?=$row_customer['customer_postcode']?>"  />		  </td>
        </tr>
		
		<tr>
          <td width="35%" align="left" valign="middle" class="tdcolorgray" >Phone <span class="redtext">*</span></td>
          <td width="65%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_phone" value="<?=$row_customer['customer_phone']?>" />		  </td>
        </tr>
		<tr>
          <td width="35%" align="left" valign="middle" class="tdcolorgray" >Fax</td>
          <td width="65%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_fax" value="<?=$row_customer['customer_fax']?>"  />		  </td>
        </tr>
		<tr>
          <td width="35%" align="left" valign="middle" class="tdcolorgray" >Mobile</td>
          <td width="65%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_mobile"  value="<?=$row_customer['customer_mobile']?>" />		  </td>
        </tr>
		
		<tr>
          <td width="35%" align="left" valign="middle" class="tdcolorgray" >Activate</td>
          <td width="65%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="checkbox" name="customer_activated" value="1" <? if($row_customer['customer_activated']) echo "checked";?>  />		  
		  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CUSTOMERS_ACTIVE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		<tr>
          <td width="35%" align="left" valign="middle" class="tdcolorgray" >Hide</td>
          <td width="65%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="checkbox" name="customer_hide"  value="1" <? if($row_customer['customer_hide']) echo "checked";?> />	&nbsp;
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CUSTOMERS_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		<tr>
          <td colspan="2" align="left" valign="middle" class="tdcolorgray" >Receive Newsletters On New or Discount Products <input class="input" type="checkbox" name="customer_prod_disc_newsletter_receive"  value="1" <? if($row_customer['customer_prod_disc_newsletter_receive']=='Y') echo "checked";?> /></td>
		  </tr>
		</table>		</td>
		</tr>
		<?
		//if($row_customer['customers_corporation_department_department_id']!=0)
		{
		?>
		<tr>
		<td colspan="2" align="left" class="seperationtd">Company Details</td>
		</tr>
		<tr>
		<td  width="50%" class="tdcolorgray" >
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
          <td width="28%" align="left" valign="middle" class="tdcolorgray" >Company Type</td>
          <td width="72%" align="left" valign="middle" class="tdcolorgray">
		  <? $sqlcomp = "SELECT comptype_id,comptype_name FROM general_settings_sites_customer_company_types WHERE  sites_site_id=".$ecom_siteid. " ORDER BY comptype_order";
		     $res_sqlcomp =$db->query($sqlcomp);
			 
		  ?>
		  <select name="comptype_id">
		  <option value="0">-select-</option>
		  <? 
		   while($row_sqlcomp=$db->fetch_array($res_sqlcomp)) {
		   ?>
		  <option value="<?=$row_sqlcomp['comptype_id']?>"<? if($row_sqlcomp['comptype_id']==$row_customer['customer_comptype']){ echo "selected"; }?>><?=$row_sqlcomp['comptype_name']?></option>
		  <?
		   } 
		  ?>
		  </select>	</td>
        </tr>
		
		<tr>
         <td width="28%" align="left" valign="middle" class="tdcolorgray" >Company Name</td>
          <td width="72%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_compname" value="<?=$row_customer['customer_compname']?>"  /></td> 
        </tr>
		</table>		</td>
		<td class="tdcolorgray" >
		<?php /*?><table  width="100%" border="0" cellspacing="0" cellpadding="0">
	
		
		<tr>
          <td width="35%" align="left" valign="middle" class="tdcolorgray" >Company RegNo</td>
          <td width="65%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_compregno" value="<?=$row_customer['customer_compregno']?>"  />       	    </td>
        </tr>
		<tr>
		 <td width="35%" align="left" valign="middle" class="tdcolorgray" >Company VatRegNo</td>
          <td width="65%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_compvatregno" value="<?=$row_customer['customer_compvatregno']?>"  />		</tr>
		</table><?php */?>		</td>
		</tr>
		<tr>
          <td colspan="2" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
		</tr>
		<?
		}
		?>

		
		<tr>
		<td colspan="2" align="left" class="seperationtd">Customer Login</td>
		</tr>
		<tr>
		<td  width="50%" class="tdcolorgray">
		<table width="100%" cellpadding="0" cellspacing="0">
		<tr>
          <td width="28%" align="left" valign="middle" class="tdcolorgray" >Email <span class="redtext">*</span></td>
          <td width="72%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_email" value="<?=$row_customer['customer_email_7503']?>"  />		  </td>
        </tr>
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Password </td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="password" name="customer_pwd"   />		  </td>
        </tr>
		<tr>
		  <td width="25%" align="left" valign="middle" class="tdcolorgray" >Confirm Password </td>
		  <td align="left" valign="middle" class="tdcolorgray">		  <input class="input" type="password" name="customer_pwd_cnf"  />	</td>
		  </tr>
		</table>		</td>
		<td  width="50%" class="tdcolorgray">&nbsp;		</td>
		</tr>
		<?
		$cur_pos = 'BottomInStatic';
		$formname = 'frmEditCustomer';
		if($mod=='edit')
		{
			include 'includes/customer_search/show_dynamic_fields_edit.php';
		}
		else
		{
			include '../includes/customer_search/show_dynamic_fields_edit.php';
		}
		?>
		<tr>
          <td colspan="2" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
    </tr>
		  <tr>
		<td width="50%" align="left" class="seperationtd">Affiliate</td>
		<td align="left" class="seperationtd">Other</td>
		</tr>
		<tr>
		<td width="50%" class="tdcolorgrayleft" valign="top">
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
          <td width="30%" align="left" valign="middle" class="tdcolorgray" >Affiliate</td>
          <td width="70%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="checkbox" name="customer_anaffiliate" value="1" <? if($row_customer['customer_anaffiliate']) echo "checked";?>   />		  
		  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CUSTOMERS_AFFILIATE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Approved Affiliate</td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray"> 
		  <input class="input" type="checkbox" name="customer_approved_affiliate" value="1"  <? if($row_customer['customer_approved_affiliate']) echo "checked";?> />		  
		  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CUSTOMERS_AFFAPPR')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Affiliate Commission</td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_affiliate_commission" size="3" value="<?=$row_customer['customer_affiliate_commission']?>"  />		 
		  (%)&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CUSTOMERS_COMMAFF')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> </td>
        </tr>
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Affiliate Tax Id</td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_affiliate_taxid" value="<?=$row_customer['customer_affiliate_taxid']?>"  />		
		  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CUSTOMERS_AFFTAXID')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		</table>		</td>
		<td width="50%" class="tdcolorgrayleft">
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
          <td width="28%" align="left" valign="middle" class="tdcolorgray" >Bonus Point</td>
          <td width="72%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_bonus" size="3"  value="<?=$row_customer['customer_bonus']?>" />		  </td>
        </tr>
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Discount</td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_discount" size="3"  value="<?=$row_customer['customer_discount']?>" />
		  (%)		  </td>
        </tr>
		
		<?php /*?><tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Reffered By</td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_referred_by" value="<?=$row_customer['customer_referred_by']?>"  />		  </td>
        </tr><?php */?>
		
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Shop</td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <select name="shop_id">
		  <option value="web">web</option>
		  </select>		  </td>
        </tr>
		
		<tr>
          <td width="30%" align="left" valign="middle" class="tdcolorgray" >Allow Product Discount</td>
          <td width="70%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="checkbox" name="customer_allow_product_discount" value="1" <? if($row_customer['customer_allow_product_discount']) echo "checked";?>  />		  </td>
        </tr>
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Use Bonus Point</td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="checkbox" name="customer_use_bonus_points" value="1" <? if($row_customer['customer_use_bonus_points']) echo "checked";?>  />		  </td>
        </tr>
		</table> </td>
		</tr>
		<tr>
		<td colspan="2" align="left" class="seperationtd">Pay on Account</td>
		</tr>
		<tr>
		<td  width="100%" class="tdcolorgray"  colspan="2">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr><td width="51%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		  <td align="left" valign="top" class="tdcolorgray" >Credit limit</td>
		  <td align="left" valign="middle" class="tdcolorgray"><?PHP  $cursymbol = display_curr_symbol();  echo $cursymbol?>
		    <input class="input" type="text" name="customer_payonaccount_maxlimit" value="<?=$row_customer['customer_payonaccount_maxlimit']?>"  /></td>
		  </tr>
		<tr>
		  <td align="left" valign="top" class="tdcolorgray" > Billing Cycle</td>
		  <td align="left" valign="middle" class="tdcolorgray">Day&nbsp;<? 
		    for($i=1;$i<=28;$i++)
			{ 
			  $date_arr[$i]=$i; 
			} 

                echo generateselectbox('customer_payonaccount_billcycle_day',$date_arr,$row_customer['customer_payonaccount_billcycle_day'],'','');
                echo '&nbsp; Interval ';
                $mon_arr = array (1=>'Every Month');
                for ($i=2;$i<=12;$i++)
                {
                    $mon_arr[$i]  = "Once in $i Months";
                }
                echo generateselectbox('customer_payonaccount_billcycle_month_duration',$mon_arr,$row_customer['customer_payonaccount_billcycle_month_duration'],'','');
                  ?>
                  </td>
		  </tr>
		<tr>
		  <td align="left" valign="top" class="tdcolorgray" >&nbsp;</td>
		  <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
		  </tr>
		<tr>
          <td width="22%" align="left" valign="top" class="tdcolorgray" >Current Status</td>
          <td width="78%" align="left" valign="middle" class="tdcolorgray">
          <? 
                if($row_customer['customer_payonaccount_status']=='NO')
                    $pay_arr = array('NO'=>'Not Requsted','ACTIVE' =>'Active','INACTIVE'=>'Inactive','REQUESTED'=>'Requested','REJECTED'=>'Rejected') ;
                else
                    $pay_arr = array('ACTIVE'=>'Active','INACTIVE'=>'Inactive','REQUESTED'=>'Requested','REJECTED'=>'Rejected') ;
		   
		    $on_change ='showreason_text(this.value)';
                echo generateselectbox('cbo_customer_payonaccount_status',$pay_arr,$row_customer['customer_payonaccount_status'],'',$on_change);
                if($row_customer['customer_payonaccount_status']=='REJECTED')
                    $display = '';
                else
                    $display = 'none';
	?>
        </td>
        </tr>
		<tr id="rejectreason_id" style="display:<?=$display?>">
          <td width="22%" align="left" valign="top" class="tdcolorgray" >Reason</td>
          <td width="78%" align="left" valign="middle" class="tdcolorgray">
		  <textarea name="customer_payonaccount_rejectreason" id="customer_payonaccount_rejectreason" cols="30" rows="6"><?=$row_customer['customer_payonaccount_rejectreason']?></textarea>	 </td>
        </tr>
		</table>
		</td>
		<td width="49%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		 <td width="41%" align="left" valign="middle" class="tdcolorgray" ><strong>Credit balance</strong></td>
          <td width="59%" align="left" valign="middle" class="tdcolorgray"><strong><?PHP 
		  $rem_limit = $row_customer['customer_payonaccount_maxlimit'] - $row_customer['customer_payonaccount_usedlimit'];
		   echo display_price($rem_limit)?></strong></td>	</tr>
		 <tr>
          <td width="41%" align="left" valign="middle" class="tdcolorgray" >Credits Used</td>
          <td width="59%" align="left" valign="middle" class="tdcolorgray"><? echo display_price($row_customer['customer_payonaccount_usedlimit'])?></td>
        </tr>
		  <tr>
		 
		 <td width="41%" align="left" valign="middle" class="tdcolorgray" >
		 Last Statement date</td>
          <td width="59%" align="left" valign="middle" class="tdcolorgray">
		  <?
			  	if ($row_customer['state_date'] and $row_customer['customer_payonaccount_atleast_one_statement']==1)
				{
			  		echo $row_customer['state_date'];
			 	} 
			 	else
			 		echo '- No Statements found -';
			  ?></td>	</tr>
		   <tr>
		     <td colspan="2" align="right" valign="top">&nbsp;</td>
		     </tr>
			 <?php 
			 if($row_customer['customer_payonaccount_status']=='ACTIVE' or $row_customer['customer_payonaccount_status']=='INACTIVE')
			 {
			 ?>
		   <tr>
		  <td colspan="2" align="right" valign="top"><a href="home.php?request=payonaccount&fpurpose=account_summary&customer_id=<?=$row_customer['customer_id']?>" title="Account Summary" class="edittextlink">Click here to view the Account Summary</a></td>
		  </tr>
		  <?php
		  }
		  ?>
		  </table>		  </td></tr>
		</table>		</td>
		</tr>
		<?php
		
		$cur_pos = 'Bottom';
		$formname = 'frmEditCustomer';
		if($mod=='edit')
		{
                    include 'includes/customer_search/show_dynamic_fields_edit.php';
		}
		else
		{
                    include '../includes/customer_search/show_dynamic_fields_edit.php';
		}
			// Check whether there is in any additional values added for this customer
			$sql_check = "SELECT * FROM customer_registration_values WHERE customers_customer_id=$cust_id ORDER BY element_sections_section_id";
			$ret_check = $db->query($sql_check);
			if($db->num_rows($ret_check)=='67678')// to remove the code--- not to display 
			{
		?>
				<tr>
				<td colspan="2">
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<?php
					$prev_secid = 0;
					while ($row_check = $db->fetch_array($ret_check))
					{
						if ($prev_secid!=$row_check['element_sections_section_id'])
						{	
							$prev_secid = $row_check['element_sections_section_id'];
				?>	
                                                    <tr>
                                                    <td align="left" class="seperationtd" colspan="2">
                                                            <?php 
                                                                    echo stripslashes($row_check['element_sections_section_name']);
                                                            ?>						  </td>
                                                    </tr> 
				<?php
						}
				?>		 
						<tr>
						  <td width="28%" align="left" valign="middle" class="tdcolorgray" ><?php echo stripslashes($row_check['reg_label'])?></td>
						  <td width="72%" align="left" valign="middle" class="tdcolorgray">
						  <?php
						  if($row_check['element_type']=='textarea') // case if type is text area
						  {
						  ?>
						  	<textarea name="Additional_<?php echo $row_check['id']?>" id="Additional_<?php echo $row_check['id']?>" rows="3" cols="20"><?php echo stripslashes($row_check['reg_val'])?></textarea>
						  <?php
						  }
						  else // case if other than text area
						  {
						  ?>
						 	 <input class="input" type="text" name="Additional_<?php echo $row_check['id']?>" id="Additional_<?php echo $row_check['id']?>" value="<?php echo stripslashes($row_check['reg_val'])?>" />
						 <?php
						  
						  }
						 ?>						  </td>
						</tr>
				<?php
					}
				?>	
				</table>				</td>
				</tr>
			
		  <?
		  }
		  ?>
		  </table>	
		  </div>
		  <div class="editarea_div">
		   <table width="100%">
		   <tr>
		   	<td align="right" valign="middle">		  
				 <input name="Submit" type="submit" class="red" value="Update" /></td></tr>
					<? 
					if($mod!='edit')
					{
			 ?><input type="hidden" name="not_edit" value="1" id="not_edit" />
			 <? }?>
			 </td>
			</tr>
			</table>
			</div>
		  <?
	}
	function show_newsletter_group_list($cust_id,$alert)
	{
		global $db,$ecom_siteid ;
		$sql = "SELECT customer_in_mailing_list 
					FROM 
						customers 
					WHERE 
						customer_id =$cust_id 
						AND sites_site_id = $ecom_siteid 
					LIMIT 
						1";
		$ret = $db->query($sql);
		if($db->num_rows($ret))
		{
			$row = $db->fetch_array($ret);
			$customer_in_mailing_list = $row['customer_in_mailing_list'];
		}
		$sql = "SELECT news_customer_id FROM newsletter_customers WHERE customer_id=".$cust_id;  
		$res = $db->query($sql);
		$row = $db->fetch_array($res);
		$news_cust_id = $row['news_customer_id'];
		if($news_cust_id>0) {
		 // #Selecting already assigned groups
		  $sql_group_assign="SELECT custgroup_id FROM customer_newsletter_group_customers_map WHERE customer_id=".$news_cust_id;
		  
		  $res_group_assign = $db->query($sql_group_assign);
		  $arr_assigned=array();

		  while($row_assigned = $db->fetch_array($res_group_assign))
		  {
				$arr_assigned[]=$row_assigned['custgroup_id'];
					
		   }
		} else {
			$arr_assigned=array();
		}	
		$sql_group="SELECT custgroup_id,custgroup_name FROM customer_newsletter_group WHERE sites_site_id=".$ecom_siteid." 
						AND custgroup_active='1'" ;
		$res_group = $db->query($sql_group);
		 
		?>
		<div class="editarea_div">
		<table width="100%" cellpadding="0" cellspacing="0" border="0" class="fieldtable" >
		<tr>
				  <td align="left" colspan="7" class="helpmsgtd"><div class="helpmsg_divcls">
				 <?php echo get_help_messages('EDIT_CUST_NEWSGROUP_SUBMSG')?>	</div>
				  </td>
		  </tr>
		         <?php
				if($alert)
				{
					?>
							<tr>
								<td  align="center" class="errormsg"><?php echo $alert?></td>
							</tr>
				 <?php
				}
		?>
		<tr>
          <td  align="left" valign="middle" class="tdcolorgray" >
		  <strong>Would you like to receive newsletters </strong><input name="customer_in_mailing_list" id="customer_in_mailing_list" type="checkbox" value="1" <?php echo ($customer_in_mailing_list)?'checked':''?> onchange="maillinglist_onchange(this)" />
		  </td>
		  </tr>
		<?php		
		if($db->num_rows($res_group)>0)
		  { 
		 ?>
		<tr>
          <td  align="left" valign="middle" class="tdcolorgray" >
			  <table width="100%" border="0" cellpadding="0" cellspacing="0">
			  <tr>
			  <?
			  $tmp_grcnt=0;
			  
			  while($row_group = $db->fetch_array($res_group))
			  {
			  ?>
			  <td><input type="checkbox" name="chk_group[]" value="<?=$row_group['custgroup_id']?>" <? if(in_array($row_group['custgroup_id'],$arr_assigned)) echo "checked";?> onchange="mailinglist_mainsel()" /><?=$row_group['custgroup_name']?></td>
			  <?
			  $tmp_grcnt++;
			  if($tmp_grcnt>2)
			  {
			  	echo "</tr><tr>";
				$tmp_grcnt=0;
			  }
			  }
			  ?>
			  </tr>
			  </table></td>
        </tr>
		
		<? }
		else
		{
		?>
		 <tr>
					 <td colspan="2" align="center" valign="middle" class="norecordredtext_small">
								  No Newsletter group found					 </td>
		  </tr>
		
		<? 
		}?>
		</table>
		</div>
		<div class="editarea_div">
		   <table width="100%">
		   <tr>
		   	<td align="right" valign="middle"><input name="Submit" type="button" class="red" value="Save" onclick="call_ajax_savenewsletter_group('newsgroup')"  />	</td>
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
			$sql_state="SELECT state_id,state_name 
						FROM general_settings_site_state 
						WHERE sites_site_id=".$ecom_siteid." AND general_settings_site_country_country_id=".$country_id.""; 
		    $ret_state = $db->query($sql_state);	
	?>
			<table width="100%" cellpadding="0" cellspacing="0" border="0" align="left">
			<tr>
				<td width="35%" align="left" valign="middle" class="tdcolorgray" >State/County</td>
				<td width="65%" align="left" valign="middle" class="tdcolorgray">
				<select class="input" name="customer_statecounty"  onchange="state_other()">
					<option value="">-select-</option>
				<?
				if ($db->num_rows($ret_state))
				{
					while($row_state=$db->fetch_array($ret_state))
					{
					?>
					<option value="<?=$row_state['state_id']?>" <? if($row_state['state_id']==$state_id) echo "selected";?>><?=$row_state['state_name']?></option>
					<?
					}
				} 
				?>
					<option value="-1" >-Other-</option>	
				</select>
				</td>
			</tr>
			</table>
<?
		}
	}
// ###############################################################################################################
	// 				Function which holds the display logic of fav categories assigned  to be shown when called using ajax;
	// ###############################################################################################################
	
	function show_favcategory_list($cust_id,$alert='')
	{
		global $db,$ecom_siteid ;
		// Get the list of categories under current category group
			$sql_cat = "SELECT a.category_id,a.category_name,a.category_hide,b.id FROM product_categories a,
						customer_fav_categories b WHERE b.sites_site_id=$ecom_siteid AND 
						b.customer_customer_id=$cust_id AND 
						a.category_id=b.categories_categories_id  ORDER BY a.category_name";//ORDER BY a.category_name
			$ret_cat = $db->query($sql_cat);
	?>	<div class="editarea_div">
		<table width="100%" cellpadding="0" cellspacing="1" border="0">
		<tr>
				  <td align="left" colspan="7" class="helpmsgtd"><div class="helpmsg_divcls">
				 <?php echo get_help_messages('EDIT_CUST_CATEGORY_SUBMSG')?></div>	
				  </td>
		  </tr>
		 <?
		$sql_cat ="SELECT category_id,category_name FROM product_categories WHERE sites_site_id=".$ecom_siteid." AND parent_id=0";
		$res_cat = $db->query($sql_cat);
		if($db->num_rows($res_cat)>0)
		{
		   ?>
				<tr>
					  <td colspan="4" align="right" valign="middle" class="tdcolorgray" ><input name="Addmore" type="button" class="red" id="Addmore" value="Assign More" onclick="document.frmEditCustomer.fpurpose.value='list_assign_categories';document.frmEditCustomer.submit();" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ASS_CUSTOMER_FAVCAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
					<?php
					$sql_categories = "SELECT categories_categories_id FROM customer_fav_categories WHERE customer_customer_id=".$cust_id." AND sites_site_id=".$ecom_siteid;
					$res_categories =$db->query($sql_categories);
					if ($db->num_rows($res_categories))
					{
					?>
						<div id="categoriesunassign_div" class="unassign_div" >
						&nbsp;&nbsp;&nbsp;<input name="categories_unassign" type="button" class="red" id="categories_unassign" value="Un Assign" onclick="call_ajax_deleteall('category','checkboxcategories[]')" />
						<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('UNASS_CUSTOMER_FAVCAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>					</div>	
					<?php
					}				
					?></td>
       		 </tr>
			<? }?> 
				<?php
				if($alert)
				{
					?>
							<tr>
								<td colspan="4" align="center" class="errormsg"><?php echo $alert?></td>
							</tr>
				 <?php
				}
				if ($db->num_rows($ret_cat))
				{
				$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditCustomer,\'checkboxcategories[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditCustomer,\'checkboxcategories[]\')"/>','Slno.','Category Name','Hidden');
				$header_positions=array('center','center','left','center','center');
				$colspan = count($table_headers);
				echo table_header($table_headers,$header_positions); 
				$cnt = 1;
				while ($row_cat = $db->fetch_array($ret_cat))
				{
					$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
				?>
					
					<tr>
					<td width="5%"  align="left" class="<?php echo $cls?>"><input type="checkbox" name="checkboxcategories[]" value="<?php echo $row_cat['id'];?>" /></td>
					<td width="5%" align="left" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
					<td width="45%" align="left" class="<?php echo $cls?>"><a href="home.php?request=prod_cat&fpurpose=edit&checkbox[0]=<?php echo $row_cat['category_id']?>&catname=<?=$_REQUEST['catname']?>&parentid=<?=$_REQUEST['parentid']?>&catgroupid=<?=$_REQUEST['catgroupid']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>" title="edit" class="edittextlink"><?php echo stripslashes($row_cat['category_name']);?></a></td>
					<td width="15%" align="center" class="<?php echo $cls?>"><?php echo ($row_cat['category_hide']==1)?'Yes':'No'?></td>
					</tr>
				<?php
				}
				}
				else
				{
				?>
				   <tr>
					 <td colspan="7" align="center" valign="middle" class="norecordredtext_small">
  								  <input type="hidden" name="favcategory_norec" id="favcategory_norec" value="1" />
								  No Categories Assigned for this Customer					 </td>
		  </tr>
				<?
				}
				?>	
</table></div>
<?
	}
	
	// ###############################################################################################################
	// 				Function which holds the display logic of products assigned to the page group to be shown when called using ajax;
	// ###############################################################################################################
	function show_product_list($edit_id,$alert='')
	{
		global $db,$ecom_siteid;
			 // Get the list of paroduct assigned for the static page group
				$sql_products = "SELECT
										 p.product_id,p.product_name,p.product_webprice,spdp.id,p.product_hide 
									FROM
										 products p,customer_fav_products spdp
									WHERE 
										spdp.products_product_id=p.product_id  
									AND 
										spdp.sites_site_id=$ecom_siteid
									AND 
										customer_customer_id=$edit_id 
								 	ORDER BY 
								 		product_name";
				$ret_products = $db->query($sql_products);
	?>				<div class="editarea_div">
					<table width="100%" cellpadding="1" cellspacing="1" border="0">
					<tr>
				  <td align="left" colspan="5" class="helpmsgtd"><div class="helpmsg_divcls">
				 <?php echo get_help_messages('EDIT_CUST_PROD_SUBMSG')?></div>	
				  </td>
				  </tr>
					<?
					 $sql_prod="SELECT product_id FROM products WHERE sites_site_id=".$ecom_siteid;
					 $res_prod = $db->query($sql_prod);
					 if($db->num_rows($res_prod)>0)
					  {
					  //Check whether Products are added to this static Page Group
						$sql_product_in_cust = "SELECT products_product_id FROM customer_fav_products
									 WHERE customer_customer_id=$edit_id";
						$ret_product_in_cust = $db->query($sql_product_in_cust);		
		 
		 ?>
					<tr>
					  <td colspan="5" align="right" valign="middle" class="tdcolorgray" ><input name="Addmore" type="button" class="red" id="Addmore" value="Assign More" onclick="assign_submit()" />
							<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CUST_ASS_PROD')?>`')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
							<?php
							if ($db->num_rows($ret_product_in_cust))
							{
							?>
								<div id="productsunassign_div" class="unassign_div" >
								&nbsp;&nbsp;&nbsp;<input name="products_unassign" type="button" class="red" id="products_unassign" value="Un Assign" onclick="call_ajax_deleteall('product','checkboxproducts[]')" />
								<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CUSTOMER_PROD_UNASS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>					</div>	
							<?php
							}				
							?></td>
					</tr>
					<?php 
					}
						if($alert)
						{
					?>
							<tr>
								<td colspan="5" align="center" class="errormsg"><?php echo $alert?></td>
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

									<td class="<?php echo $cls?>" align="center"><?php echo ($row_products['product_hide']=='Y')?'Yes':'No'?></td>
								</tr>
							<?php
							}
						}
						else
						{
						?>
								<tr>
								  <td colspan="5" align="center" valign="middle" class="norecordredtext_small">No favourite product Assigned to this Customer. <input type="hidden" name="products_norec" id="products_norec" value="1" /></td>
								</tr>
						<?php
						}
						?>	
				</table>	
				</div>
	<?php	
	}
	function show_order_list($customerid,$alert='')
	{
		global $db,$ecom_siteid;
		// Check whether any order has been placed with the current voucher number
		$sql_order= "SELECT order_id ,order_date,order_totalprice,order_custtitle,order_custfname,order_custmname,order_custsurname,order_status,order_paystatus,order_pre_order,order_refundamt  
								FROM 
									orders 
								WHERE 
									customers_customer_id=$customerid	 
									AND order_status NOT IN ('CANCELLED','NOT_AUTH') 
									AND sites_site_id=$ecom_siteid 
								ORDER BY 
									order_date 
										DESC";
			$ret_order = $db->query($sql_order);
	?>
					<div class="editarea_div">
					<table width="100%" cellpadding="1" cellspacing="1" border="0">
					<tr>
				  <td align="left" colspan="8" class="helpmsgtd"><div class="helpmsg_divcls">
				 <?php echo get_help_messages('CUST_ORDER_HISTORY_SUBMSG')?></div>	
				  </td>
				  </tr>
					<?php
						if($alert)
						{
					?>
							<tr>
								<td colspan="8" align="center" class="errormsg"><?php echo $alert?></td>
							</tr>
				 <?php
				 		}
						if ($db->num_rows($ret_order))
						{
							$table_headers = array('Slno.','Order Id','Order Date','Preorder','Order Total','Refund','Order Status','Pay Status');
							$header_positions=array('center','center','center','center','right','right','center','center');
							$colspan = count($table_headers);
							echo table_header($table_headers,$header_positions);
							$cnt = 1;
							while ($row_order = $db->fetch_array($ret_order))
							{
								$date = dateFormat($row_order['order_date'],'');
								$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
							?>

								<tr onclick="window.location='home.php?request=orders&fpurpose=ord_details&edit_id=<?php echo stripslashes($row_order['order_id']);?>'">
									<td width="5%" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
									<td align="center" class="<?php echo $cls?>"><a class="edittextlink" href="home.php?request=orders&fpurpose=ord_details&edit_id=<?php echo stripslashes($row_order['order_id']);?>" title="Click to view the order details"><?php echo stripslashes($row_order['order_id']);?></a></td>
									<td align="center" class="<?php echo $cls?>"><?php echo stripslashes($date);?></td>
									<td align="center" class="<?php echo $cls?>"><?php echo stripslashes($row_order['order_pre_order']);?></td>
									<td align="right" class="<?php echo $cls?>"><?php echo display_price($row_order['order_totalprice']);?></td>
									<td align="right" class="<?php echo $cls?>"><?php echo display_price($row_order['order_refundamt']);?></td>
									<td align="center" class="<?php echo $cls?>"><?php echo getorderstatus_Name($row_order['order_status']);?></td>
									<td align="center" class="<?php echo $cls?>"><?php echo getpaymentstatus_Name($row_order['order_paystatus']);?></td>
								</tr>
							<?php
							}
						}
						else
						{
						?>
								<tr>
								  <td colspan="4" align="center" valign="middle" class="norecordredtext_small">
								  <input type="hidden" name="order_norec" id="order_norec" value="1" />
								  No Orders found.</td>
								</tr>
						<?php
						}
						?>
				</table>
				</div>
	<?php
	}	
?>
