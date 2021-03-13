<? 
	/*#################################################################
	# Script Name 	: add_promotional_code.php
	# Description 	: Page for adding promotional codes
	# Coded by 		: Sny
	# Created on	: 05-Sep-2007
	# Modified by	: Sny
	# Modified On	: 07-Sep-2007
	#################################################################*/
	if($_REQUEST['code_type'])
		$ctype = $_REQUEST['code_type'];
	else
		$ctype = 'default';
	//Define constants for this page
	$page_type = 'Promotional Code';
    $help_msg = get_help_messages('ADD_PROM_CODE_MESS1');

	//$help_msg = 'This section helps in adding the Promotional Codes';
?>	
<script language="Javascript"> 	
 	function validate_promotional_code()
	{

		var missing_field = '';
		/* Validating various fields */
		if(TrimText(document.frm_promo.code_number.value)=='')
		{
			missing_field += '\n-- Promotional Code';
		}
		if(TrimText(document.frm_promo.code_startdate.value)=='')
		{
			missing_field += '\n-- Start Date';
		}
		if(TrimText(document.frm_promo.code_enddate.value)=='')
		{
			missing_field += '\n-- End Date';
		}
		if (missing_field!='')
		{
			alert ('Missing Fields:\n'+missing_field);
			return false;
		}
		
		else
		{
		
			document.frm_promo.code_value.value = TrimText(document.frm_promo.code_value.value);
			if (document.frm_promo.code_type.value=='default')
			{
				if(document.frm_promo.code_value.value=='' ||  document.frm_promo.code_value.value<0 || isNaN(document.frm_promo.code_value.value))
				{
					alert('Discount % is invalid');
					return false;
				}
				else if(document.frm_promo.code_value.value>=100 || document.frm_promo.code_value.value<0)
				{
					alert('Discount % should be less than 100');
					return false;
				}
			}
			else if(document.frm_promo.code_type.value=='percent' || document.frm_promo.code_type.value=='money' )
			{
				document.frm_promo.code_minimum.value = TrimText(document.frm_promo.code_minimum.value);
				if(document.frm_promo.code_minimum.value=='' || isNaN(document.frm_promo.code_minimum.value) || document.frm_promo.code_minimum.value<0)
				{
					alert('Discount for minimum value is invalid');
					return false;
				}
				if(document.frm_promo.code_minimum.value==0)
				{
					alert('Enter a positive value for Discount for minimum value ');
					return false;
				}
				if(document.frm_promo.code_value.value=='' || document.frm_promo.code_value.value<0 || isNaN(document.frm_promo.code_value.value))
				{
					if(document.frm_promo.code_type.value=='percent' )
					{
						alert('Discount % is invalid');
						return false;
					}
					if( document.frm_promo.code_type.value=='money' )
					{
						alert('Discount value is invalid');
						return false;
					}	
				}
				else if(document.frm_promo.code_value.value>100 && document.frm_promo.code_type.value=='percent')
				{
					alert('Discount % should be less than 100');
					return false;
				}
			}
		}  
		if(document.frm_promo.code_unlimit_check.checked == false)
		{ 
			 if(document.frm_promo.code_limit.value=='' || isNaN(document.frm_promo.code_limit.value) || document.frm_promo.code_limit.value<0 )
			 {
				alert('Invalid Total Usage Limit');
				return false;
			 }
		}	
		if(document.frm_promo.code_login_to_use.checked==true)
		{
			if(document.frm_promo.code_customer_unlimit_check.checked == false)
			{ 
				 if(document.frm_promo.code_customer_limit.value=='' || isNaN(document.frm_promo.code_customer_limit.value) || document.frm_promo.code_customer_limit.value<0 )
				 {
					alert('Invalid Same Customer Usage Limit');
					return false;
				 }
			}	
		}	
		/*if(document.frm_promo.code_customer_unlimit_check.checked == false)
		{ 
			 if(document.frm_promo.code_customer_limit.value=='' || isNaN(document.frm_promo.code_customer_limit.value) || document.frm_promo.code_customer_limit.value<0 )
			 {
				alert('Invalid Same Customer Usage Limit');
				return false;
			 }
		}*/	
		if(document.frm_promo.code_unlimit_check.checked == false && document.frm_promo.code_customer_unlimit_check.checked == false)
		{ 
			 if(document.frm_promo.code_customer_limit.value > document.frm_promo.code_limit.value )
			 {
				alert('Invalid Total Usage Limit');
				return false;
			 }
		}		
		val_dates = compareDates(document.frm_promo.code_startdate,"Start Date\n Correct Format:dd-mm-yyyy ",document.frm_promo.code_enddate,"End Date\n Correct Format:dd-mm-yyyy");
			if(val_dates ){
				  document.frm_promo.submit();
			 }
	}	
	
	function TrimText(str) 
	{  if(str.charAt(0) == " ")
	  {  str = TrimText(str.substring(1));
	  }
	  if (str.charAt(str.length-1) == " ")
	  {  str = TrimText(str.substring(0,str.length-1));
	  }
	  return str;
	}
	function handle_customer_main_limit(id)
	{
		if (id.checked==true)
		{
			document.getElementById('cust_main_usage_div').style.display='';
			if(document.getElementById('code_customer_unlimit_check').checked==true)
			{
				if(document.getElementById('limt_customer_txt_id'))
					document.getElementById('limt_customer_txt_id').style.display ='none';
			}	
			else
			{
				if(document.getElementById('limt_customer_txt_id'))
				{
					document.getElementById('limt_customer_txt_id').style.display ='';
				}	
			}	
		}	
		else
		{
			document.getElementById('cust_main_usage_div').style.display='none';
			if(document.getElementById('limt_customer_txt_id'))
				document.getElementById('limt_customer_txt_id').style.display ='none';
		}	
	}
	function handle_codetype(val)
	{
		if(val=='default')
		{
			document.getElementById('tr_discmin').style.display='none';
			document.getElementById('tr_discval').style.display='';
			document.getElementById('prod_dir_tr').style.display='';
			document.getElementById('dis_val').innerHTML = 'Discount % <span class="redtext">*</span>';	
			document.getElementById('tr_disctype').style.display='none';
		}
		else if (val=='percent')
		{
			document.getElementById('tr_discmin').style.display='';
			document.getElementById('tr_discval').style.display='';
			document.getElementById('prod_dir_tr').style.display='';
			document.getElementById('dis_val').innerHTML = 'Discount % <span class="redtext">*</span>';
			document.getElementById('tr_disctype').style.display='none';
		}
		else if (val=='money')
		{
			document.getElementById('tr_discmin').style.display='';
			document.getElementById('tr_discval').style.display='';
			document.getElementById('prod_dir_tr').style.display='';
			document.getElementById('dis_val').innerHTML = 'Discount value <span class="redtext">*</span>';		
			document.getElementById('tr_disctype').style.display='none';
		}
		else if (val=='product')
		{
			document.getElementById('tr_discmin').style.display='none';
			document.getElementById('tr_discval').style.display='none';
			if (document.getElementById('code_apply_direct_product_discount_also'))
				document.getElementById('code_apply_direct_product_discount_also').checked=false;
			document.getElementById('prod_dir_tr').style.display='none';			
			document.getElementById('tr_disctype').style.display='';
		}
		else if(val=='orddiscountpercent')
		{
			document.getElementById('tr_discmin').style.display='none';
			document.getElementById('tr_discval').style.display='';
			document.getElementById('dis_val').innerHTML = 'Discount % <span class="redtext">*</span>';	
			document.getElementById('tr_disctype').style.display='none';

		}
		else if (val=='freeproduct')
		{
			document.getElementById('tr_discmin').style.display='none';
			document.getElementById('tr_discval').style.display='none';
			if (document.getElementById('code_apply_direct_product_discount_also'))
				document.getElementById('code_apply_direct_product_discount_also').checked=false;
			document.getElementById('prod_dir_tr').style.display='none';			
			document.getElementById('tr_disctype').style.display='';
		}
		else if(val=='unlimited')
		{
		  if(document.frm_promo.code_unlimit_check.checked==false)
		  {
		   	document.getElementById('limt_txt_id').style.display='';
		  }
		  else
		  {
		   document.getElementById('limt_txt_id').style.display='none';
		  }
		}
		else if(val=='customer_unlimited')
		{
		  if(document.frm_promo.code_customer_unlimit_check.checked==false)
		  {
		   	document.getElementById('limt_customer_txt_id').style.display='';
		  }
		  else
		  {
		   document.getElementById('limt_customer_txt_id').style.display='none';
		  }
		}
		
		newobj = eval("document.getElementById('hidden_trs')");
		if(val=='product')
		{
			if(newobj)
			{
				newobj.style.display = 'none';
			}
		}
		else
		{
			if(val != 'unlimited' && val != 'customer_unlimited')
			{		
				if(newobj)
				{
					newobj.style.display = '';
				}
			}
		}
	} 
</script>
<?php
	if($_REQUEST['txt_code'])
		$condition = " AND code_number LIKE '%".$_REQUEST['txt_code']."%'";
		
 	$sql_code_full	= "SELECT count(code_id) FROM promotional_code WHERE sites_site_id=$ecom_siteid $condition";
	$rstMain_full	= $db->query($sql_code_full);
	list($cnt)		= $db->fetch_array($rstMain_full);
?>
<form action="home.php?request=prom_code&codenumber=<?php echo $_REQUEST['codenumber']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>" method="post" name="frm_promo">
<table width="100%" border="0" cellspacing="0" cellpadding="0" height="100%">
<tr>
<td valign="top">
	<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
	<td align="left" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=prom_code&codenumber=<?php echo $_REQUEST['codenumber']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>">Promotional Code</a><span>Add Promotional Code</span></div></td>
	</tr>
	<tr>
		  <td align="left" valign="middle" class="helpmsgtd_main">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
	</table>
		<div class="editarea_div">
	<table width="100%" border="0" cellspacing="1" cellpadding="1" class="fieldtable">
      <tr>
        <td width="60%" align="left" valign="top">
		<table width="100%" border="0" cellspacing="1" cellpadding="2">
<?php 
		if ($alert)
		{
?>
          <tr>
            <td colspan="3" align="left" class="errormsg"><?php echo $alert?></td>
          </tr>
<?php
		}
?>
          <tr>
            <td align="left" class="tdcolorgray" >Promotional Code <span class="redtext">*</span></td>
            <td align="center" class="tdcolorgray" >:</td>
            <td align="left" class="tdcolorgray" ><input name="code_number" type="text" id="code_number" size="40" value="<?php echo $_REQUEST['code_number']?>" /></td>
          </tr>
          <tr>
            <td align="left" class="tdcolorgray" >Start From <span class="redtext">*</span></td>
            <td align="center" class="tdcolorgray" >:</td>
            <td align="left" class="tdcolorgray" ><input name="code_startdate" type="text" id="code_startdate" value="<?php echo $_REQUEST['code_startdate']?>" size="10" maxlength="10" />
              <a href="javascript:show_calendar('frm_promo.code_startdate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a>
			  (dd-mm-yyyy) </td>
          </tr>
          <tr>
            <td align="left" class="tdcolorgray" >End On <span class="redtext">*</span> </td>
            <td align="center" class="tdcolorgray" >:</td>
            <td align="left" class="tdcolorgray" ><input name="code_enddate" type="text" id="code_enddate" value="<?php echo $_REQUEST['code_enddate']?>" size="10" maxlength="10" />
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
            <td align="left" class="tdcolorgray" ><input name="code_minimum" type="text" size="8" value="<?php echo $_REQUEST['code_minimum']?>" /></td>
          </tr>
          <tr id="tr_discval">
            <td align="left" class="tdcolorgray" ><div id='dis_val'>Discount % <span class="redtext">*</span></div></td>
            <td align="center" class="tdcolorgray" >:</td>
            <td align="left" class="tdcolorgray" ><input name="code_value" type="text" size="8" value="<?php echo $_REQUEST['code_value']?>" /></td>
          </tr>
          <tr id="tr_disctype">
            <td align="left" class="tdcolorgray" ><div id='dis_type'>Discount Type </div></td>
            <td align="center" class="tdcolorgray" >:</td>
            <td align="left" class="tdcolorgray" ><select name="code_dis_type" id="code_dis_type">
              <option value="0" <?php if($_REQUEST['code_dis_type'] == '0') echo ' selected="selected"';?>>Value</option>
              <option value="1" <?php if($_REQUEST['code_dis_type'] == '1') echo ' selected="selected"';?>>%</option>
            </select></td>
          </tr>
		  <tr>
		    <td align="left" valign="middle" class="tdcolorgray">Customer should login to use this? </td>
		    <td align="center" class="tdcolorgray" >&nbsp;</td>
		    <td align="left" valign="middle" class="tdcolorgray"><input type="checkbox" name="code_login_to_use" value="1" checked="checked" onclick="handle_customer_main_limit(this)"/>
&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('PROMO_REQ_LOGIN_TO_USE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		    </tr>
		  <tr>
		    <td align="left" valign="middle" class="tdcolorgray">Allow Free Delivery? </td>
		    <td align="center" class="tdcolorgray" >:</td>
		    <td align="left" valign="middle" class="tdcolorgray"><input type="checkbox" name="code_freedelivery" value="1"  <?php echo ($_REQUEST['code_freedelivery']==1)?'checked="checked"':'' ?>/>
			&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ALLOW_FREE_DELIVERY_PROMO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	      </tr>
	       <tr>
		    <td align="left" valign="middle" class="tdcolorgray">Apply Customer Direct Discount also? </td>
		    <td align="center" class="tdcolorgray" >:</td>
		    <td align="left" valign="middle" class="tdcolorgray"><input type="checkbox" name="code_apply_direct_discount_also" value="1"  <?php echo ($_REQUEST['code_apply_direct_discount_also']==1)?'checked="checked"':'' ?>/>
			&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('APPLY_CUST_DIRECT_DISC_PROM')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	      </tr>
	       <tr>
		    <td align="left" valign="middle" class="tdcolorgray">Apply Customer Group Discount also? </td>
		    <td align="center" class="tdcolorgray" >:</td>
		    <td align="left" valign="middle" class="tdcolorgray"><input type="checkbox" name="code_apply_custgroup_discount_also" value="1"  <?php echo ($_REQUEST['code_apply_custgroup_discount_also']==1)?'checked="checked"':'' ?>/>
			&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('APPLY_CUST_GROUP_DISC_PROM')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	      </tr>
	       <tr id="prod_dir_tr" style="display:none">
		    <td align="left" valign="middle" class="tdcolorgray" >Apply Product Direct Discount also? </td>
		    <td align="center" class="tdcolorgray" >:</td>
		    <td align="left" valign="middle" class="tdcolorgray"><input type="checkbox" name="code_apply_direct_product_discount_also" id="code_apply_direct_product_discount_also" value="1"  <?php echo ($_REQUEST['code_apply_direct_product_discount_also']==1)?'checked="checked"':'' ?>/>
			&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('APPLY_PROD_DIRECT_DISC_PROM')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	      </tr>
		  <tr>
		  <td align="left" valign="middle" class="tdcolorgray">Total Usage limit</td>
		  <td align="center" class="tdcolorgray" >:</td>
		   <td align="left" valign="middle" class="tdcolorgray"><input type="checkbox" name="code_unlimit_check"  value="1" <? echo "checked";?>  onclick="handle_codetype('unlimited')" /> 
		   Unlimited?&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROM_CODE_LIMIT_CHECK')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> </td>
		  </tr>
		  <tr id="limt_txt_id" style="display:none;">
		  <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
		  <td align="center" class="tdcolorgray" >&nbsp;</td>
		   <td align="left" valign="middle" class="tdcolorgray">
		   <table cellpadding="0" cellspacing="0" border="0" width="100%">
		  <tr > <td align="center" valign="middle" >Enter limit here</td><td align="left" valign="middle" class="tdcolorgray"> <input type="text" name="code_limit"  value="<?=$_REQUEST['code_limit']?>" size="4" />  </td>
		  </tr>
		  </table>		  </td>
		  <tr>
		 <tr id="cust_main_usage_div">
		  <td align="left" valign="middle" class="tdcolorgray" nowrap="nowrap">Total usage for same customer</td>
		  <td align="center" class="tdcolorgray" >:</td>
		   <td align="left" valign="middle" class="tdcolorgray"><input type="checkbox" name="code_customer_unlimit_check" id="code_customer_unlimit_check"  value="1" <? echo "checked";?>  onclick="handle_codetype('customer_unlimited')" /> 
		   Unlimited?&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROM_CODE_LIMIT_CHECK')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> </td>
		  </tr>
		  <tr id="limt_customer_txt_id" style="display:none;">
		  <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
		  <td align="center" class="tdcolorgray" >&nbsp;</td>
		   <td align="left" valign="middle" class="tdcolorgray">
		   <table cellpadding="0" cellspacing="0" border="0" width="100%"> 
		  <tr > <td align="center" valign="middle" >Enter limit here</td><td align="left" valign="middle" class="tdcolorgray"> <input type="text" name="code_customer_limit"  value="<?=$_REQUEST['code_customer_limit']?>" size="4" />  </td>
		  </tr>
		  </table>		  </td>
		  <tr>
		  <tr id="hidden_trs" style="display:<?php echo ($_REQUEST['code_type']=='product')?'none':''?>">
		  <td align="left" valign="middle" class="tdcolorgray">Hidden?</td>
		  <td align="center" class="tdcolorgray" >:</td>
		   <td align="left" valign="middle" class="tdcolorgray"><input type="radio" name="code_hide" value="1" checked="checked" />
		     Yes
		     <input type="radio" name="code_hide" value="0" checked="checked" />
		     No&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROM_CODE_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		  </tr>
        </table></td>
        <td width="45%" align="left" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="1">
          <tr>
            <td colspan="2" class="tdcolorgray" ><span class="redtext"><strong>Code Types </strong></span></td>
          </tr>
          <tr>
            <td colspan="2" class="tdcolorgray" ><strong>% Off on grand total :- </strong></td>
          </tr>
          <tr>
            <td width="8%" class="tdcolorgray" >&nbsp;</td>
            <td width="92%" class="tdcolorgray" >The discount % specified in the Discount % field will be deducted from the grand total.</td>
          </tr>
          <tr>
            <td colspan="2" class="tdcolorgray" ><strong>Money Off on minimum value of grand total :-</strong> </td>
          </tr>
          <tr>
            <td class="tdcolorgray" >&nbsp;</td>
            <td class="tdcolorgray" >E.g. In case if 100 is to be deducted from the total if purchase is made for a minimum value of 1000, then specify 1000 in &quot;Discount for Minimum&quot; and 100 in &quot;Discount Value&quot; </td>
          </tr>
          <tr>
            <td colspan="2" class="tdcolorgray" ><strong>% Off on minimum value of grand total :-</strong> </td>
          </tr>
          <tr>
            <td class="tdcolorgray" >&nbsp;</td>
            <td class="tdcolorgray" >E.g.In case if 10% is to be deducted from the total if purchase is made for a minimum value of 1000, then specify 1000 in &quot;Discount for Minimum&quot; and 10 in &quot;Discount % &quot; </td>
          </tr>
          <tr>
            <td colspan="2" class="tdcolorgray" ><strong>Off on selected products :-</strong> </td>
          </tr>
          <tr>
            <td class="tdcolorgray" >&nbsp;</td>
            <td class="tdcolorgray" >In case if  promotional price is to be given for selected products, this option can be used. The option to link the products to the promotional code will be displayed once the promotional code details are saved.</td>
          </tr>
        </table></td>
      </tr>
    </table></div>
	<div class="editarea_div">
	<table  border="0" cellpadding="0" cellspacing="0" class="listingtable">
	   <tr>
		  <td align="right" valign="middle" class="listeditd"><input type="button" name="Button" value="Save" onclick="validate_promotional_code()" class="red" /> </td>
		</tr>
	</table>
	</div>
    </td>
</tr>
</table>
<input type="hidden" name="fpurpose" value="insertcode">
<input type="hidden" name="codenumber" value="<?php echo $_REQUEST['codenumber']?>">
<input type="hidden" name="sort_by" value="<?php echo $_REQUEST['sort_by']?>">
<input type="hidden" name="sort_order" value="<?php echo $_REQUEST['sort_order']?>">
<input type="hidden" name="start" value="<?php echo $_REQUEST['start']?>">
<input type="hidden" name="pg" value="<?php echo $_REQUEST['pg']?>">
<input type="hidden" name="records_per_page" value="<?php echo $_REQUEST['records_per_page']?>">
</form>
<script type="text/javascript">
	handle_codetype('<?php echo $ctype?>');
</script>
