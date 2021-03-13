<?php
/*#################################################################
# Script Name 	: view_site_pymt_method_values.php
# Description 	: Page for viewing Payment method details values for a site
# Coded by 		: ANU
# Created on	: 7-June-2007
# Modified by	: 
# Modified On	: 
#################################################################
*/
//#Define constants for this page
$page_type = 'Payment Method Details Values';
$help_msg = 'This section helps in editing/viewing the values for a Payment Method.';

//#Sql
$sql_payment_method_details = "SELECT payment_method_details_id,payment_methods_details_caption,
									  payment_methods_details_isrequired 
										    FROM payment_methods_details as pmd 
												 WHERE pmd.payment_methods_paymethod_id=".$_REQUEST['paymethod_id']."";
$res_payment_method_details = $db->query($sql_payment_method_details);

$sql_payment_method_details_value_for_site="SELECT  paydet_id,payment_methods_details_payment_method_details_id,
													payment_methods_forsites_details_values 
															FROM payment_methods_details as pmd  
																	LEFT JOIN payment_methods_forsites_details as pmsd 
																	ON  pmd.payment_method_details_id = pmsd.payment_methods_details_payment_method_details_id  
																		WHERE (pmd.payment_methods_paymethod_id= ".$_REQUEST['paymethod_id']." 
																			AND pmsd.sites_site_id=".$_REQUEST['site_id'].")";
$res_payment_method_details_value_for_site = $db->query($sql_payment_method_details_value_for_site);
$pymt_details_values = array();
while($row_value 	= $db->fetch_array($res_payment_method_details_value_for_site)) {
//$payment_methods_details_payment_method_details_id[] = $row['payment_methods_details_payment_method_details_id'];
$pymt_details_values[$row_value['payment_methods_details_payment_method_details_id']] = $row_value['payment_methods_forsites_details_values'];
$pymt_details_values_id[$row_value['payment_methods_details_payment_method_details_id']] = $row_value['paydet_id'];

}

?>

<form name='frmEditPaymentMethodSiteValues' action='home.php?request=sites' method="post" onsubmit="return valform(this);">

<table width="100%" border="0" cellpadding="0" cellspacing="1" class="maininnertable">
      <tr>
        <td align="left" class="menutabletoptd"><a href="home.php?request=sites&amp;title=<?=$title?>&amp;domain=<?=$domain?>&amp;client=<?=$client?>&amp;theme=<?php $_REQUEST['theme']?>&amp;site_status=<?=$site_status?>&amp;sort_by=<?=$pass_sort_by?>&amp;sort_order=<?=$pass_sort_order?>&amp;records_per_page=<?=$pass_records_per_page?>&amp;pg=<?=$pass_pg?>">List Sites </a></b>>&gt;&nbsp;<a href="home.php?request=sites&amp;title=<?=$title?>&amp;domain=<?=$domain?>&amp;client=<?=$client?>&amp;theme=<?php $_REQUEST['theme']?>&amp;site_status=<?=$site_status?>&amp;sort_by=<?=$pass1sort_by?>&amp;sort_order=<?=$pass1sort_order?>&amp;records_per_page=<?=$pass1records_per_page?>&amp;pg=<?=$pass1pg?>&fpurpose=List_Payment_Types&site_id=<?=$_REQUEST['site_id']?>&pay_method=<?=$pay_method?>" title="list Payment Methods">List Payment Type  </a>>><a href="home.php?request=sites&amp;title=<?=$title?>&amp;domain=<?=$domain?>&amp;client=<?=$client?>&amp;theme=<?php $_REQUEST['theme']?>&amp;site_status=<?=$site_status?>&amp;sort_by=<?=$sort_by?>&amp;sort_order=<?=$sort_order?>&amp;records_per_page=<?=$records_per_page?>&amp;pg=<?=$pg?>&fpurpose=list_pymt_methods&site_id=<?=$_REQUEST['site_id']?>&pay_method=<?=$pay_method?>" title="list Payment Methods">List Payment Method  </a> <font size="1">>></font> <strong>Edit <?=$page_type?></strong></td>
      </tr>
      
      <tr>
        <td class="maininnertabletd3">
			<?=$help_msg?>
		</td>
      </tr>
	  <tr>
        <td class="maininnertabletd2" valign="top" >
			<table width="100%"  border="0" cellpadding="4" cellspacing="1" class="">
			 <?php
	  	if($error_msg)
		{
	  ?>
		  <tr>
			<td align="center" class="error_msg" colspan="<?=$colspan?>"><?php echo $error_msg?></td>
		  </tr>
	  <?php
	  	}
	  ?>
				<tr align="left">
				  <td class="redtext"><div align="left">* <span>are required </span></div></td>
				</tr>
				
				<tr>
				  <td align="right" class="fontblacknormal"><table width="100%" border="0">
				  <?php 
				  $required_feild   = '';
				  while ($row 		= $db->fetch_array($res_payment_method_details)) {?>
                    <tr class="">
                      <td width="46%" align="right" class="fontblacknormal"><input type="hidden" name="payment_methods_details_id[<?=$row['payment_method_details_id']?>]" id="payment_methods_details_id[<?=$row['payment_method_details_id']?>]" value="<?=$pymt_details_values_id[$row['payment_method_details_id']]?>" />
                      <?=$row['payment_methods_details_caption']?></td>
                      <td width="4%" align="center">:</td>
                      <td width="50%" align="left"><input name="details_values[<?=$row['payment_method_details_id']?>]" type="text" id="details_values[<?=$row['payment_method_details_id']?>]" value="<?=(array_key_exists($row['payment_method_details_id'],$pymt_details_values))? $pymt_details_values[$row['payment_method_details_id']]:''?>" size="30" /><?php 
					  if($row['payment_methods_details_isrequired']){ 
					  echo '<span class="redtext">*</span>';
					  //creating the values for the javascript array required feilds
					 //$required_feild  .=  "'".$row['payment_methods_details_caption']."'".',';
					 // $required_feild  .= "'".$pymt_details_values_id[$row['payment_method_details_id']]."'".',';
					 $required_feild .= "'".'details_values['.$row['payment_method_details_id'].']'."'".',';
					  $required_feildDescription  .= "'".$row['payment_methods_details_caption']."'".',';
					  }?>                        </td>
                    </tr>
					<? }?>
                    <tr>
                      <td colspan="3"><?php
					    $pos = strrpos($required_feild, ",");
					 	$fieldRequired_forjs = substr($required_feild,0, $pos); //the feldRequired array for javascript
						$posDesc = strrpos($required_feildDescription, ",");
						$fieldRequiredDescription_forjs = substr($required_feildDescription,0, $posDesc);//the feldRequired Description array for javascript
					  ?></td>
                    </tr>
                  </table></td>
			    </tr>
				<tr align="center">
				<td><input type="hidden" name="site_id" id="site_id" value="<?=$_REQUEST['site_id']?>" />
				    <input type="hidden" name="paymethod_id" id="paymethod_id" value="<?=$_REQUEST['paymethod_id']?>" />
				    <input type="hidden" name="pay_method" id="pay_method" value="<?=$_REQUEST['pay_method']?>" />
			        <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
			        <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
			        <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
			        <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
							<!--pasing values from the sites listing page starts-->

					<input type="hidden" name="pass_sort_by" id="pass_sort_by" value="<?=$_REQUEST['pass_sort_by']?>" />
			        <input type="hidden" name="pass_sort_order" id="pass_sort_order" value="<?=$_REQUEST['pass_sort_order']?>" />
			        <input type="hidden" name="pass_records_per_page" id="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
			        <input type="hidden" name="pass_pg" id="pass_pg" value="<?=$_REQUEST['pass_pg']?>" />
	<input type="hidden" name="pass1sort_by" id="pass1sort_by" value="<?=$_REQUEST['pass1sort_by']?>" />
	<input type="hidden" name="pass1sort_order" id="pass_sort_order" value="<?=$_REQUEST['pass1sort_order']?>" />
	<input type="hidden" name="pass1records_per_page" id="pass1records_per_page" value="<?=$_REQUEST['pass1records_per_page']?>" />
	<input type="hidden" name="pass1pg" id="pass1pg" value="<?=$_REQUEST['pass1pg']?>" />
					<input type="hidden" name="title" id="title" value="<?=$_REQUEST['title']?>" />
			        <input type="hidden" name="domain" id="domain" value="<?=$_REQUEST['domain']?>" />
			        <input type="hidden" name="client" id="client" value="<?=$_REQUEST['client']?>" />
			        <input type="hidden" name="status" id="status" value="<?=$_REQUEST['status']?>" />
					<input type="hidden" name="theme" id="theme" value="<?=$_REQUEST['theme']?>" />
		<!--pasing values from the sites listing page ends-->

					
			        <input type="hidden" name="fpurpose" id="fpurpose" value="update_paymethod_values" />
			        <input type="hidden" name="payment_methods_forsites_id" id="payment_methods_forsites_id" value="<?=$_REQUEST['pymt_methods_forsites_id']?>" />
			        <input type="Submit" name="Submit" id="Submit" value="Update Values" class="input-button">				
			      </p></td>
				<td align="left">&nbsp;</td>
				</tr>
				<tr>
				  <td align="right">&nbsp;</td>
				</tr>
			</table>
		</td>
      </tr>
    </table>
</form>
<script language="javascript">
function valform(frm)
{
	fieldRequired = Array(<?=$fieldRequired_forjs?>);
	fieldDescription = Array(<?=$fieldRequiredDescription_forjs?>);
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		return true;
	} else {
		return false;
	}
}
</script>
