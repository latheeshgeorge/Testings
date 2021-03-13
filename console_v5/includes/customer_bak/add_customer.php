<?php
	/*#################################################################
	# Script Name 	: add_customer.php
	# Description 	: Page for adding Customer
	# Coded by 		: SKR
	# Created on	: 13-Aug-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
#Define constants for this page
$page_type = 'Customer';
$help_msg = 'This section helps in adding the Customers';

// #######################################################################################################
// Start ## Section to builds the dynamic fields to be placed in the javascript validation
// #######################################################################################################
$field_str = '';
$field_msg = '';
$checkboxfld_arr[] = array();
$checkboxstr_arr[] = array();
$radiofld_arr[] = array();
$radiofldstr_arr[] = array();
// Check whether any dynamic section set up for customer registration in current site  and is compulsory
$sql_dyn = "SELECT * FROM element_sections WHERE sites_site_id=$ecom_siteid AND 
			activate = 1 AND section_type = 'register' ORDER BY sort_no";
$ret_dyn = $db->query($sql_dyn);
if ($db->num_rows($ret_dyn))
{
	while ($row_dyn = $db->fetch_array($ret_dyn))
	{
		$sql_elem = "SELECT * FROM elements WHERE sites_site_id=$ecom_siteid AND 
					element_sections_section_id =".$row_dyn['section_id']." AND mandatory ='Y' ORDER BY sort_no";
		$ret_elem = $db->query($sql_elem);
		if ($db->num_rows($ret_elem))
		{
			while ($row_elem = $db->fetch_array($ret_elem))
			{
				if($row_elem['error_msg'])// check whether error message is specified
				{
					if ($row_elem['element_type'] == 'checkbox')
					{
						// Check whether their exists values
						$sql_val = "SELECT * FROM element_value WHERE elements_element_id = ".$row_elem['element_id'];
						$ret_val = $db->query($sql_val);
						if ($db->num_rows($ret_val))
						{
							$no=0;
							$check_elements = array();
							while ($row_val = $db->fetch_array($ret_val))
							{
								$check_elements[] = $row_elem['element_name'].'_'.$no;
								$no++;
							}
							if(count($check_elements))
							{
								$checkboxfld_arr[] = $check_elements;
								$checkboxstr_arr[] = $row_elem['error_msg'];	
							}	
						}	
						
					}
					elseif ($row_elem['element_type'] == 'radio')
					{
						// Check whether their exists values
						$sql_val = "SELECT * FROM element_value WHERE elements_element_id = ".$row_elem['element_id'];
						$ret_val = $db->query($sql_val);
						if ($db->num_rows($ret_val))
						{
							$no=0;
							$radio_elements = array();
							while ($row_val = $db->fetch_array($ret_val))
							{
								$radio_elements[] = $row_elem['element_name'].'['.$no.']';
								$no++;
							}
							if(count($radio_elements))
							{
								$radiofld_arr[] 	= $radio_elements;
								$radiofldstr_arr[] 	= $row_elem['error_msg'];	
							}	
						}	
						
					}
					else
					{
						if($field_str!='')
						{
							$field_str .= ',';
							$field_msg .= ',';
						}
						$field_str .= "'".$row_elem['element_name']."'";	
						$field_msg .= "'".$row_elem['error_msg']."'";	
					}	
				}	
			}
		}
							
	}
	if($field_str)	{
		$field_str = ",".$field_str;
		$field_msg = ",".$field_msg;
	}

}
// #######################################################################################################
// Finish ## Section to builds the dynamic fields to be placed in the javascript validation
// #######################################################################################################		
?>	
<script language="javascript" type="text/javascript">
function valform(frm)
{
	fieldRequired = Array('customer_title','customer_fname','customer_email','customer_pwd'<?php echo $field_str?>);
	fieldDescription = Array('Title','First Name','Email','Password'<?php echo $field_msg?>);
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		<?php
			// Logic to build the dynamic field validation
			$ptr = 0;
			foreach ($checkboxfld_arr as $k=>$v)
			{
				$flds = '';
				if(count($v))
				{
					echo "if (";
					foreach($v as $nam=>$fld)
					{	
						if($flds!='')
							$flds .=' && ';
						$flds .= "document.frmAddCustomer.".$fld.".checked!=true ";
					}
					echo "$flds)
					{
						alert('".$checkboxstr_arr[$ptr]."');
						return false;
					}	
					";
				}	
				$ptr++;
			}	
			// case of radio button
			$ptr = 0;
			
			foreach ($radiofld_arr as $k=>$v)
			{
				$flds = '';
				if(count($v))
				{
					echo "if (";
					foreach($v as $nam=>$fld)
					{	
						if($flds!='')
							$flds .=' && ';
						$flds .= "document.frmAddCustomer.".$fld.".checked!=true ";
					}
					echo "$flds)
					{
						alert('".$radiofldstr_arr[$ptr]."');
						return false;
					}	
					";
				}	
				$ptr++;
			}	
		?>	
		show_processing();
		return true;
	} else {
		return false;
	}
}
function changestate()
{
	var retdivid='state_tr';
	var country_id;
	var fpurpose;
	country_id=document.frmAddCustomer.country_id.value;
	if(country_id!="")
	{
		document.getElementById('state_tr').style.display='';
		fpurpose	= 'list_state';
		document.getElementById('retdiv_id').value 		= retdivid;/* Name of div to show the result */
		retobj 											= eval("document.getElementById('"+retdivid+"')");
		retobj.innerHTML 								= '<center><img src="images/loading.gif" alt="Loading"></center>';															
		var qrystr										= '';
		/* Calling the ajax function */
		Handlewith_Ajax('services/customer.php','fpurpose='+fpurpose+'&'+qrystr+'&country_id='+country_id);
	}
	else
	{
		
		document.getElementById('state_tr').style.display='none';
	}
}
function ajax_return_contents() 
{
	var ret_val = '';
	var disp 	= 'no';
	if(req.readyState==4)
	{
		if(req.status==200)
		{
			ret_val 	= req.responseText;
			targetdiv 	= document.getElementById('retdiv_id').value;
			targetobj 	= eval("document.getElementById('"+targetdiv+"')");
			targetobj.innerHTML = ret_val; /* Setting the output to required div */
			hide_processing();
		}
		else
		{
			 show_request_alert(req.status);
			//alert("Problem in requesting XML :"+req.statusText);
		}
	}
}


</script>
<form name='frmAddCustomer' action='home.php?request=customer' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" align="left" valign="middle" class="treemenutd"><a href="home.php?request=customer&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Customer</a> &gt;&gt; Add Customer</td>
        </tr>
        <tr>
          <td colspan="2" align="left" valign="middle" class="helpmsgtd" ><?=$help_msg ?></td>
        </tr>
		<?php 
		if($alert)
		{			
		?>
        <tr>
          <td colspan="2" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
        </tr>
		<?
		}
		$cur_pos = 'Top';
		include 'show_dynamic_fields.php';
		?>
		 <tr>
		<td colspan="2" align="left" class="seperationtd">Personal Details</td>
		</tr>
		<tr>
		<td width="50%" valign="top"  class="tdcolorgrayleft">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		
		
		
	
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Customer Title <span class="redtext">*</span></td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <select class="input" name="customer_title"  />
		  <option value="" >-select-</option>
		  <option value="Mr." >Mr.</option>
		  <option value="Ms.">Ms.</option>
		  <option value="Mrs.">Mrs.</option>
		  <option value="M/s">M/s.</option>
		 </select>
		  </td>
        </tr>
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >First Name <span class="redtext">*</span></td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_fname"  />
		  </td>
        </tr>
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Middle Name</td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_mname"  />
		  </td>
        </tr>
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Surname</td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_surname"  />
		  </td>
        </tr>
		<tr>
          <td width="28%" align="left" valign="middle" class="tdcolorgray" >Building Name / No.</td>
          <td width="72%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_buildingname"  />
		  </td>
        </tr>
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Street Name</td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_streetname"  />
		  </td>
        </tr>
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Town/City</td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_towncity"  />
		  </td>
        </tr>
		
		
		</table></td>
		<td valign="top" class="tdcolorgrayleft">
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
          <td width="28%" align="left" valign="middle" class="tdcolorgray" >Country</td>
          <td width="72%" align="left" valign="middle" class="tdcolorgray">
		  <select class="input" name="country_id" onchange="changestate()">
		  <option value="0">-select-</option>
		  <?
		  $sql_country="SELECT country_id,country_name FROM general_settings_site_country WHERE sites_site_id=".$ecom_siteid." AND country_hide=1" ;
		  $res_country=$db->query($sql_country);
		  while($row_country=$db->fetch_array($res_country))
		  {
		  ?>
		  <option value="<?=$row_country['country_id']?>"><?=$row_country['country_name']?></option>
		  <?
		  }
		  ?>
		  </select>
		  
		  </td>
        </tr>
		<tr>
		<td colspan="2" align="left">
		<div id="state_tr" style="display:none;">		</div>
		</td>
		</tr>
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Postcode</td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_postcode"  />
		  </td>
        </tr>
		
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Phone</td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_phone"  />
		  </td>
        </tr>
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Fax</td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_fax"  />
		  </td>
        </tr>
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Mobile</td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_mobile"  />
		  </td>
        </tr>
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Activate</td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="checkbox" name="customer_activated" value="1"  />
		  </td>
        </tr>
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Hide</td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="checkbox" name="customer_hide"  value="1" />
		  </td>
        </tr>
		</table>
		</td>
		</tr>
		
		<tr>
		<td colspan="2" >&nbsp;</td>
		</tr>
		
		<tr>
		<td colspan="2" align="left" class="seperationtd">Customer Login</td>
		</tr>
		<tr>
		<td width="50%">
		<table width="100%" cellpadding="0" cellspacing="0"> 
		<tr>
          <td width="28%" align="left" valign="middle" class="tdcolorgray" >Email <span class="redtext">*</span></td>
          <td width="72%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_email"  />
		  </td>
        </tr>
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Password <span class="redtext">*</span></td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="password" name="customer_pwd"  />
		  </td>
        </tr>
		</table></td>
		<td width="50%" class="tdcolorgray">&nbsp;</td>
		</tr>
		
		<tr>
		<td colspan="2" >&nbsp;</td>
		</tr>
		<tr>
		<td width="50%" align="left" class="seperationtd">Affiliate</td>
		<td align="left" class="seperationtd">Other</td>
		</tr>
		<tr>
		<td width="50%" class="tdcolorgrayleft">
		<table width="100%" cellpadding="0" cellspacing="0">
		<tr>
          <td width="28%" align="left" valign="middle" class="tdcolorgray" >Affiliate</td>
          <td width="72%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="checkbox" name="customer_anaffiliate" value="1"   />
		  </td>
        </tr>
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Approved Affiliate</td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="checkbox" name="customer_approved_affiliate" value="1"  />
		  </td>
        </tr>
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Affiliate Commission</td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_affiliate_commission" size="3"  />
		  </td>
        </tr>
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Affiliate Tax Id</td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_affiliate_taxid"  />
		  </td>
        </tr>
		</table>
		</td>
		<td width="50%" valign="top" class="tdcolorgrayleft">
		<table width="100%" cellpadding="0" cellspacing="0">
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Bonus Point</td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_bonus" size="3"  value="<?=$row_customer['customer_bonus']?>" />
		  </td>
        </tr>
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Discount</td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_discount" size="3"  value="<?=$row_customer['customer_discount']?>" />
		  </td>
        </tr>
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Shop</td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <select name="shop_id">
		  <option value="web">web</option>
		  </select>
		  
		  </td>
        </tr>
		<tr>
          <td width="28%" align="left" valign="middle" class="tdcolorgray" >Allow Product Discount</td>
          <td width="72%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="checkbox" name="customer_allow_product_discount" value="1"  />
		  </td>
        </tr>
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Use Bonus Point</td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="checkbox" name="customer_use_bonus_points" value="1"  />
		  </td>
        </tr>
		</table>
		</td>
		</tr>
		
		<tr>
		<td width="50%">
		<table width="100%" cellpadding="0" cellspacing="0">
		
		
		</table>
		</td>
		</tr>
		<?php
		  $cur_pos = 'Bottom';
		  include 'show_dynamic_fields.php';
		?>
		<tr>
          <td colspan="2" align="left" valign="middle" class="seperationtd" >To recieve newsletters please select from the following:</td></tr>
        <tr>
          <td colspan="2" align="left" valign="middle" class="tdcolorgray" >
		  <?
		  
		  $sql_group="SELECT custgroup_id,custgroup_name FROM customer_group WHERE sites_site_id=".$ecom_siteid;
		  $res_group = $db->query($sql_group);
		  if($db->num_rows($res_group)>0)
		  {
		  ?>
			  <table width="100%" border="0" cellpadding="0" cellspacing="0">
			  <tr>
			  <?
			  $tmp_grcnt=0;
			  
			  while($row_group = $db->fetch_array($res_group))
			  {
			  ?>
			  <td><input type="checkbox" name="chk_group[]" value="<?=$row_group['custgroup_id']?>"  /><?=$row_group['custgroup_name']?></td>
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
			  </table>
		  <?
		  }
		  ?>
		  &nbsp;</td>
    </tr>
		<tr>
          <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td align="left" valign="middle" class="tdcolorgray">
		  
		  <input type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>" />
		   <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
		  <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
		  <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
		  <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
		  <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
		  <input type="hidden" name="fpurpose" id="fpurpose" value="insert" />
		  <input type="hidden" name="retdiv_id" id="retdiv_id" value="" />
		  <input name="Submit" type="submit" class="red" value="Submit" /></td>
        </tr>
  </table>
</form>	  

