<?php
	/*#################################################################
	# Script Name 	:apply_settings_many.php
	# Description 		: Page for appliying settings for multiple Customers in single step
	# Coded by 		: LH
	# Created on		: 21-Aug-2008
	# Modified by		: LH
	# Modified On		: 21-Aug-2008

	#################################################################*/

	//Define constants for this page
	$page_type = 'Customers';
	$help_msg = get_help_messages('SETTINGS_TOMANY_MAIN_MESS_CUSTOMERS');
?>
<script language="javascript" type="text/javascript">
function display_select(frm){
for(i=0;i<document.frm_apply_settingstomany.elements.length;i++)
	{
	if (document.frm_apply_settingstomany.elements[i].type =='checkbox' && document.frm_apply_settingstomany.elements[i].name=='recievenewsletter_check')
		{
			if (document.frm_apply_settingstomany.elements[i].checked==true)
			{
				document.getElementById("recievenewsletter_id").style.display='';
			}
			else
			{
			   document.getElementById("recievenewsletter_id").style.display='none'; 
			}	
		}
		if (document.frm_apply_settingstomany.elements[i].type =='checkbox' && document.frm_apply_settingstomany.elements[i].name=='bonuspoint_check')
		{
			if (document.frm_apply_settingstomany.elements[i].checked==true)
			{
				document.getElementById("bonuspoints_id").style.display='';
			}
			else
			{
			   document.getElementById("bonuspoints_id").style.display='none'; 
			}	
		}
		if (document.frm_apply_settingstomany.elements[i].type =='checkbox' && document.frm_apply_settingstomany.elements[i].name=='custdiscount_check')
		{
			if (document.frm_apply_settingstomany.elements[i].checked==true)
			{
				document.getElementById("custdiscount_id").style.display='';
			}
			else
			{
			   document.getElementById("custdiscount_id").style.display='none'; 
			}	
		}
		if (document.frm_apply_settingstomany.elements[i].type =='checkbox' && document.frm_apply_settingstomany.elements[i].name=='allowproddiscount_check')
		{
			if (document.frm_apply_settingstomany.elements[i].checked==true)
			{
				document.getElementById("allowproddiscount_id").style.display='';
			}
			else
			{
			   document.getElementById("allowproddiscount_id").style.display='none'; 
			}	
		}
		if (document.frm_apply_settingstomany.elements[i].type =='checkbox' && document.frm_apply_settingstomany.elements[i].name=='usebonuspoint_check')
		{
			if (document.frm_apply_settingstomany.elements[i].checked==true)
			{
				document.getElementById("usebonuspoint_id").style.display='';
			}
			else
			{
			   document.getElementById("usebonuspoint_id").style.display='none'; 
			}	
		}
		if (document.frm_apply_settingstomany.elements[i].type =='checkbox' && document.frm_apply_settingstomany.elements[i].name=='affiliate_check')
		{
			if (document.frm_apply_settingstomany.elements[i].checked==true)
			{
				document.getElementById("affiliate_id").style.display='';
			}
			else
			{
			   document.getElementById("affiliate_id").style.display='none'; 
			}	
		}
	}	
}
function valforms(frm){
var atleastone 		= false;
	
if(frm.recievenewsletter_check.checked==false && frm.bonuspoint_check.checked==false && frm.custdiscount_check.checked==false && frm.allowproddiscount_check.checked==false && frm.usebonuspoint_check.checked==false)
{
		alert("Please select at least One Checkbox");
		return false;
}
else if(frm.select_customers.checked== false){
		alert("Please choose 'Apply to all customers option' for whom the settings to be applied");
		return false;
	}
if(frm.recievenewsletter_check.checked==true)
{
		if (frm.customer_prod_disc_newsletter_receive[0].checked==false  && frm.customer_prod_disc_newsletter_receive[1].checked==false)
		{
			alert('Please select option for Receive Newsletters On New or Discount Products ');
			return false;
		}
}
if(frm.bonuspoint_check.checked==true)
{
		if (frm.customer_bonus.value=='')
		{
			alert('Please Enter the Bonus Points ');
			return false;
		}
		if(frm.customer_bonus.value <0){
			alert("Bonus point should be a positive");
			return false
		}
		if(isNaN(frm.customer_bonus.value) ){
	         alert("Bonus Point Should be a numeric value");
		 return false
	    }	
}if(frm.custdiscount_check.checked==true)
{
		if (frm.customer_discount.value=='')
		{
			alert('Please Enter Discount');
			return false;
		}
		if(frm.customer_discount.value <0 || frm.customer_discount.value >100){
			alert("Discount value should be a positive and below 100%");
			return false
		}
		if(isNaN(frm.customer_discount.value) ){
	         alert("Discount Should be a numeric value");
		 return false
	    }	
}
if(frm.allowproddiscount_check.checked==true)
{
		if (frm.customer_allow_product_discount[0].checked==false  && frm.customer_allow_product_discount[1].checked==false)
		{
			alert('Please select option for Allow Product Discounts ');
			return false;
		}
}
if(frm.usebonuspoint_check.checked==true)
{
		if (frm.customer_use_bonus_points[0].checked==false  && frm.customer_use_bonus_points[1].checked==false)
		{
			alert('Please select option for Use Bonus Points ');
			return false;
		}
}
		if(confirm('Are you sure whether you want to set the above settings for all customers?'))
		return true;
		else
		return false;
}
</script>	
	<form name='frm_apply_settingstomany' action='home.php?request=customer_search' method="post" onsubmit="return valforms(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=customer_search&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&search_name=<?=$_REQUEST['pass_search_name']?>&search_compname=<?php echo $_REQUEST['pass_search_compname']?>&search_email=<?=$_REQUEST['pass_search_email']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&corporation_id=<?=$_REQUEST['corporation_id']?>&customer_payonaccount_status=<?=$_REQUEST['customer_payonaccount_status']?>&cbo_dept=<?=$_REQUEST['cbo_dept']?>">List Customers</a><span> Set Options for multiple customers</span></div></td>
        </tr>
       <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="3">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
		<?php 
			if($alert)
			{			
		?>
        		<tr>
          			<td colspan="3" align="center" valign="middle" class="errormsg"><?php echo($alert);?></td>
          		</tr>
		 <?php
		 	}
		 ?> 
		 	<tr>
				<td colspan="3" align="center" valign="middle">
				<div class="listingarea_div">
				<table width="100%"  border="0" cellpadding="2" cellspacing="0" class="">
			  <tr>
                <td colspan="3" align="left" class="seperationtd">
				<table width="40%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="11%"><input type="checkbox" name="bonuspoint_check" value="1" onclick="display_select(this)" <? if($_REQUEST['recievenewsletter_check_11']) echo "checked"; else echo '';?> /></td>
                    <td width="89%">Bonus Points&nbsp;&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SETTINGS_TOMANY_SELECT_BONUS_SET')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>					</td>
                  </tr>
				  
                </table></td>
              </tr>
			  <tr>
                <td colspan="3" align="left" class="seperationtd"><table width="40%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="11%"><input type="checkbox" name="custdiscount_check" value="1" onclick="display_select(this)" <? if($_REQUEST['recievenewsletter_check_11']) echo "checked"; else echo '';?> /></td>
                    <td width="89%">Customer discounts&nbsp;&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SETTINGS_TOMANY_SELECT_CUST_DISC')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>					</td>
                  </tr>
				  
                </table></td>
              </tr>
			  <tr>
                <td colspan="3" align="left" class="seperationtd"><table width="40%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="11%"><input type="checkbox" name="recievenewsletter_check" value="1" onclick="display_select(this)" <? if($_REQUEST['recievenewsletter_check_11']) echo "checked"; else echo '';?> /></td>
                    <td width="89%">Receive Newsletters On New or Discount Products&nbsp;&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SETTINGS_TOMANY_SELECT_NEWSLETTER_RECIEVE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>					</td>
                  </tr>
				  
                </table></td>
              </tr>
			  <tr>
                <td colspan="3" align="left" class="seperationtd"><table width="40%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="11%"><input type="checkbox" name="allowproddiscount_check" value="1" onclick="display_select(this)" <? if($_REQUEST['recievenewsletter_check_11']) echo "checked"; else echo '';?> /></td>
                    <td width="89%">Allow Product Discounts&nbsp;&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SETTINGS_TOMANY_SELECT_PROD_DISC_ALLOW')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>					</td>
                  </tr>
				  
                </table></td>
              </tr>
			  <tr>
                <td colspan="3" align="left" class="seperationtd"><table width="40%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="11%"><input type="checkbox" name="usebonuspoint_check" value="1" onclick="display_select(this)" <? if($_REQUEST['recievenewsletter_check_11']) echo "checked"; else echo '';?> /></td>
                    <td width="89%">Use Bonus Points&nbsp;&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SETTINGS_TOMANY_SELECT_BONUS_POINT_USE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>					</td>
                  </tr>
				  
                </table></td>
              </tr>
			  <?php /*?><tr>
                <td colspan="3" align="left" class="seperationtd"><table width="40%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="11%"><input type="checkbox" name="affiliate_check" value="1" onclick="display_select(this)" <? if($_REQUEST['recievenewsletter_check_11']) echo "checked"; else echo '';?> /></td>
                    <td width="89%">Affiliate&nbsp;&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SETTINGS_TOMANY_SELECT_SUBCAT_DISPLIST_METH')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>					</td>
                  </tr>
				  
                </table></td>
              </tr><?php */?>
			  
			  <tr style=" display:none;" id="bonuspoints_id"><td colspan="3" align="left" class="tdcolorgray">
			  <table width="100%" cellpadding="0" cellspacing="0" border="0">
			    <tr>
				  <td width="5%">&nbsp;</td>
           		  <td width="13%"  align="left" class="tdcolorgray">Bonus Point           		  </td>
                    <td width="82%"  align="left" class="tdcolorgray">
					<input type="text" name="customer_bonus" value="" size="10" />
                  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SETTINGS_TOMANY_SELECT_BONUS_SET')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
			  </tr>
			  </table></td></tr>
			  <tr style=" display:none;" id="custdiscount_id"><td colspan="3" align="left" class="tdcolorgray">
			  <table width="100%" cellpadding="0" cellspacing="0" border="0">
			    <tr>
					<td width="5%">&nbsp;</td>
               		<td width="13%"  align="left" class="tdcolorgray">Customer Discount           		  </td>
                    <td width="82%"  align="left" class="tdcolorgray">
					<input type="text" name="customer_discount" value="" size="10" />
                  (%)<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SETTINGS_TOMANY_SELECT_CUST_DISC')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
			  </tr>
			  </table></td></tr>
			  <tr style=" display:none;" id="recievenewsletter_id"><td colspan="3" align="left" class="tdcolorgray">
			  <table width="100%" cellpadding="0" cellspacing="0" border="0">
			    <tr>
			      <td colspan="3"  align="left" class="tdcolorgray">Receive Newsletters On New or Discount Products <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SETTINGS_TOMANY_SELECT_NEWSLETTER_RECIEVE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		        </tr>
			    <tr>
				<td width="5%">&nbsp;</td>
               		<td width="13%"  align="left" class="tdcolorgray"> <input name="customer_prod_disc_newsletter_receive"  type="radio" value="1"/> Turn On
           		  </td>
                    <td width="82%"  align="left" class="tdcolorgray"><input name="customer_prod_disc_newsletter_receive" type="radio" value="0"/> Turn Off
                  </td>
			  </tr>
			  </table></td></tr>
			  <tr style=" display:none;" id="allowproddiscount_id"><td colspan="3" align="left" class="tdcolorgray">
			  <table width="100%" cellpadding="0" cellspacing="0" border="0">
			    <tr>
			      <td colspan="3"  align="left" class="tdcolorgray">Allow Product Discounts <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SETTINGS_TOMANY_SELECT_PROD_DISC_ALLOW')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		        </tr>
			    <tr>
					<td width="5%">&nbsp;</td>
               		<td width="13%"  align="left" class="tdcolorgray"> <input name="customer_allow_product_discount"  type="radio" value="1"/> Turn On
           		  </td>
                    <td width="82%"  align="left" class="tdcolorgray"><input name="customer_allow_product_discount" type="radio" value="0"/> Turn Off
                  </td>
			  </tr>
			  </table></td></tr>
			  <tr style=" display:none;" id="usebonuspoint_id"><td colspan="3" align="left" class="tdcolorgray">
			  <table width="100%" cellpadding="0" cellspacing="0" border="0">
			    <tr>
			      <td colspan="3"  align="left" class="tdcolorgray">Use Bonus Points <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SETTINGS_TOMANY_SELECT_BONUS_POINT_USE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		        </tr>
			    <tr>
								<td width="5%">&nbsp;</td>

               		<td width="13%"  align="left" class="tdcolorgray"> <input name="customer_use_bonus_points"  type="radio" value="1"/> Turn On
           		  </td>
                    <td width="82%"  align="left" class="tdcolorgray"><input name="customer_use_bonus_points" type="radio" value="0"/> Turn Off
                  </td>
			  </tr>
			  </table></td></tr>
			  <?php /*?><tr style=" display:none;" id="affiliate_id"><td colspan="3" align="left" class="tdcolorgray">
			  <table width="100%" cellpadding="0" cellspacing="0" border="0">
			    <tr>
			      <td colspan="2"  align="left" class="tdcolorgray">Affiliate <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_GROUP_MOREIMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		        </tr>
			    <tr>
               		<td width="13%"  align="left" class="tdcolorgray"> <input name="customer_use_bonus_points"  type="radio" value="1"/> Turn On
           		  </td>
                    <td width="87%"  align="left" class="tdcolorgray"><input name="customer_use_bonus_points" type="radio" value="0"/> Turn Off
                  </td>
			  </tr>
			  <tr>
			      <td colspan="2"  align="left" class="tdcolorgray">Approved Affiliate <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_GROUP_MOREIMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		        </tr>
			    <tr>
               		<td width="13%"  align="left" class="tdcolorgray"> <input name="customer_use_bonus_points"  type="radio" value="1"/> Turn On
           		  </td>
                    <td width="87%"  align="left" class="tdcolorgray"><input name="customer_use_bonus_points" type="radio" value="0"/> Turn Off
                  </td>
			  </tr>
			    <tr>
           		  <td width="13%"  align="left" class="tdcolorgray">Affiliate Commission <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_GROUP_MOREIMG')?>')"; onmouseout="hideddrivetip()"></a>           		  </td>
                    <td width="87%"  align="left" class="tdcolorgray">
					<input type="text" name="customer_discount" value="" size="10" />
                  (%)<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_GROUP_MOREIMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="19" height="16" border="0" /></a></td>
			  </tr>
			  
			    <tr>
           		  <td width="13%"  align="left" class="tdcolorgray">Affiliate Tax Id <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_GROUP_MOREIMG')?>')"; onmouseout="hideddrivetip()"></a>           		  </td>
                    <td width="87%"  align="left" class="tdcolorgray">
					<input type="text" name="customer_discount" value="" size="10" />
				  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_GROUP_MOREIMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>                  </td>
			  </tr>
			  </table></td></tr><?php */?>
			  <tr>
		<td colspan="3" align="left" class="seperationtd">Select customers for whom  you want to set the above settings</td>
		</tr>
              <tr>
                <td colspan="3" align="left" class="tdcolorgray"><input type="radio" name="select_customers" checked="checked" value="All" onclick="display_customer_selector(this.value)"/>
Apply to all customers
 </td>
              </tr>
		      
              <tr>
                <td colspan="3" align="left" valign="middle" class="tdcolorgraynormal">&nbsp;</td>
              </tr>
        
         </table>
		 </div>
		 </td>
		 </tr>
		 
		<tr>
			<td colspan="3" align="center" valign="top">
				<div class="listingarea_div">
					<table width="100%"  border="0" cellpadding="2" cellspacing="0" class="">
					<tr>
						<td width="100%" align="right" valign="middle">
							<input name="Submit" type="submit" class="red" value="Set values" />
						</td>
					</tr>
					</table>
				</div>
			</td>
		</tr>
		
        <tr>
          <td width="16%" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td colspan="2" align="left" valign="middle" class="tdcolorgray">
		  <input type="hidden" name="fpurpose" id="fpurpose" value="save_settingstomany" /></td>
        </tr>
	</table>		  
	</form>