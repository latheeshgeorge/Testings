<?php
	/*#################################################################
	# Script Name 	: add_customer_corporation.php
	# Description 	: Page for adding Customer Corporation
	# Coded by 		: ANU
	# Created on	: 15-Aug-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
#Define constants for this page
$page_type = 'Business Customer';
$help_msg = get_help_messages('ADD_DEP_CUST_CORP_MESS1');
$corporation_id = $_REQUEST['corporation_id'];
$sql_corporation = "SELECT corporation_name FROM customers_corporation WHERE corporation_id = ".$corporation_id;
$ret_coporation = $db->query($sql_corporation);
$corporation = $db->fetch_array($ret_coporation);
?>	
<script language="javascript" type="text/javascript">

function valform(frm)
{
	fieldRequired = Array('department_name');
	fieldDescription = Array('Depratment Name');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		
		var state_ids=document.frmAddCorporationDepratment.customer_statecounty.value;
		if(state_ids==-1)
		{
			 if(document.frmAddCorporationDepratment.other_state.value=='')
			 {
			 	alert("Enter other state name");
				document.frmAddCorporationDepratment.other_state.focus();
				 return false;
			 }
		}
		else
		{
		    show_processing();
			return true;
		}	
	} else {
		return false;
	}
}
// change state
function changestate()
{
	var retdivid='state_tr';
	var fpurpose;
    var country_ids=document.frmAddCorporationDepratment.country_id.value;
	if(country_ids!="")
	{
		document.getElementById('state_tr').style.display='';
		fpurpose	= 'list_state';
		document.getElementById('retdiv_id').value 		= retdivid;/* Name of div to show the result */
		retobj 											= eval("document.getElementById('"+retdivid+"')");
		retobj.innerHTML 								= '<center><img src="images/loading.gif" alt="Loading"></center>';															
		Handlewith_Ajax('services/customer_corporation.php','fpurpose='+fpurpose+'&country_id='+country_ids);
	}
	else
	{
		
		document.getElementById('state_tr').style.display='none';
	}
}
function state_other()
{
	var stte_ids=document.frmAddCorporationDepratment.customer_statecounty.value;
	if(stte_ids==-1)
	{
		document.getElementById('state_other_tr').style.display='';
	}
	else
	{
		document.getElementById('state_other_tr').style.display='none';

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
<form name='frmAddCorporationDepratment' action='home.php?request=customer_corporation' enctype="multipart/form-data"  method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=customer_corporation&amp;sort_by=<?=$_REQUEST['sort_by']?>&amp;sort_order=<?=$_REQUEST['sort_order']?>&amp;records_per_page=<?=$_REQUEST['records_per_page']?>&amp;search_name=<?=$_REQUEST['search_name']?>&amp;start=<?=$_REQUEST['start']?>&amp;pg=<?=$_REQUEST['pg']?>">List Business Customers </a> <a href="home.php?request=customer_corporation&fpurpose=edit&corporation_id=<?=$corporation_id?>&amp;sort_by=<?=$_REQUEST['sort_by']?>&amp;sort_order=<?=$_REQUEST['sort_order']?>&amp;records_per_page=<?=$_REQUEST['records_per_page']?>&amp;search_name=<?=$_REQUEST['search_name']?>&amp;start=<?=$_REQUEST['start']?>&amp;pg=<?=$_REQUEST['pg']?>&curtab=department_tab_td"> Edit Business Customers</a> <span> Add Departments under: <b>" <?=$corporation['corporation_name']?> "</b></span></div></td>
    </tr>
   <tr>
	  <td colspan="2" align="left" valign="middle" class="helpmsgtd_main">
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
      <td colspan="2" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
    </tr>
    <?
		}
		?>
	<tr>
      <td colspan="2" class="tdcolorgray" valign="top">
		<div class="sorttd_div" >
	 	<table width="100%">
		<tr>
		<td width="50%" align="left">
	 <table cellpadding="0" cellspacing="0" border="0" width="100%" align="left">
	   <tr>
		  <td width="46%"  align="left" valign="middle" class="tdcolorgray" >Department Name <span class="redtext">*</span> </td>
		  <td width="54%"  align="left" valign="middle" class="tdcolorgray"><input name="department_name" type="text" id="department_name" value="<?=$_REQUEST['department_name']?>" maxlength="100" /></td>
        </tr>
    	<tr  >
		  <td align="left" valign="middle" class="tdcolorgray" >Department Building </td>
		  <td align="left" valign="middle" class="tdcolorgray"><input name="department_building" type="text" id="department_building"  value="<?php  echo $_REQUEST['department_building'];?>" /></td>
        </tr>
    	<tr  >
			  <td align="left" valign="middle" class="tdcolorgray" >Street</td>
			  <td align="left" valign="middle" class="tdcolorgray"><input name="department_street" type="text" id="department_street"  value="<?php  echo $_REQUEST['department_street'];?>" /></td>
         </tr>
  		  <tr  >
			  <td align="left" valign="middle" class="tdcolorgray" >Town </td>
			  <td align="left" valign="middle" class="tdcolorgray"><input name="department_town" type="text" id="department_town"  value="<?php  echo $_REQUEST['department_town'];?>" /></td>
         </tr>
   		 <tr >
		  	<td align="left" valign="middle" class="tdcolorgray" >Hidden</td>
		  	<td  align="left" valign="middle" class="tdcolorgray"><input type="radio" name="department_hide" value="1" <? if($_REQUEST['department_hide']==1) echo "checked";?> />
		  	  &nbsp;Yes&nbsp;
		  	  <input type="radio" name="department_hide"  value="0" <? if($_REQUEST['department_hide']==0) echo "checked";?>  />
		  	  &nbsp;No
	  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_DEP_CUST_CORP_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
         </tr>
	</table>
	</td>
	<td width="50%" align="right">
		<table cellpadding="0" cellspacing="0" border="0" width="100%" align="right">
			<tr>
				  <td width="40%"  align="left" valign="middle" class="tdcolorgray" >Country</td>
				  <td width="60%"  align="left" valign="middle" class="tdcolorgray">
				  <select class="input" name="country_id" onchange="changestate();">
				  <option value="0">-select-</option>
				  <?
				  $sql_country="SELECT country_id,country_name FROM general_settings_site_country WHERE sites_site_id=".$ecom_siteid." AND country_hide=1" ;
				  $res_country=$db->query($sql_country);
				  while($row_country=$db->fetch_array($res_country))
				  {
				  ?>
				  <option value="<?=$row_country['country_id']?>" ><?=$row_country['country_name']?></option>
				  <?
				  }
				  ?>
			  </select>		  </td>
			</tr>	
			<tr>
				<td colspan="2" align="left" >
				<div id="state_tr" style="display:none" align="left" >		</div>		</td>
			</tr>
			<tr id="state_other_tr" style=" display:none">
				<td align="right" valign="middle" class="tdcolorgray" >Enter Other State Here<span class="redtext">*</span></td>
				<td align="left" valign="middle" class="tdcolorgray"> <input type="text" name="other_state" id="other_state"  /></td>
			</tr> 
			<tr>
			  <td align="left" valign="middle" class="tdcolorgray">Post code  </td>
			  <td align="left" valign="middle" class="tdcolorgray"><input name="department_postcode" type="text" id="department_postcode"  value="<?php  echo $_REQUEST['department_postcode'];?>" /></td>
			</tr>
			<tr>
			  <td align="left" valign="middle" class="tdcolorgray" >Phone</td>
			  <td align="left" valign="middle" class="tdcolorgray"><input name="department_phone" type="text" id="department_phone"  value="<?php  echo $_REQUEST['department_phone'];?>" /></td>
			</tr>
			<tr>
			  <td align="left" valign="middle" class="tdcolorgray">Fax</td>
			  <td align="left" valign="middle" class="tdcolorgray"><input name="department_fax" type="text" id="department_fax"  value="<?php  echo $_REQUEST['department_fax'];?>" /></td>
			</tr>
			<tr>
			  <td height="22" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
			  <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
			</tr>
		</table>
		</td>
		</tr>
		</table>
		</div>
		</td>
		</tr>
    
    <tr>
      <td colspan="2" align="center" valign="middle" class="tdcolorgray">
	   <div class="sorttd_div" >
	   <table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td align="right" valign="middle" class="tdcolorgray" >
			  <input type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>" />
			  <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
			  <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
			  <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
			  <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
			  <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
			  <input type="hidden" name="fpurpose" id="fpurpose" value="insert_department" />
			   <input type="hidden" name="corporation_id" id="corporation_id" value="<?=$_REQUEST['corporation_id']?>" />
			  <input type="hidden" name="retdiv_id" id="retdiv_id" value="" />
			  <input type="hidden" name="retdiv_more" id="retdiv_more" value="" />
			  <input name="Submit" type="submit" class="red" value="Save" />
			</td>
		</tr>
		</table>
		</div>
      </td>
    </tr>
  </table>
  
</form>	  
<!--<script type="text/javascript">
	handletype_change('');
</script>
-->