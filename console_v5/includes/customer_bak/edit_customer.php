<?php
	/*#################################################################
	# Script Name 	: edit_customer.php
	# Description 	: Page for editing Customer
	# Coded by 		: SKR
	# Created on	: 14-Aug-2007
	# Modified by	: Sny
	# Modified On	: 06-Nov-2007
	#################################################################*/
#Define constants for this page
$page_type 		= 'Customer';
$help_msg 		= 'This section helps in editing the Customers';
$customer_id	= ($_REQUEST['customer_id']?$_REQUEST['customer_id']:$_REQUEST['checkbox'][0]);
$sql_customer	= "SELECT * FROM customers  WHERE customer_id=".$customer_id;
$res_customer	= $db->query($sql_customer);
$row_customer 	= $db->fetch_array($res_customer);
?>	
<script language="javascript" type="text/javascript">
function valform(frm)
{
	fieldRequired = Array('customer_fname');
	fieldDescription = Array('First Name');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		show_processing();
		return true;
	} else {
		return false;
	}
}

function changestate(country_id,state_id)
{
	var retdivid='state_tr';
	var fpurpose;
	if(country_id==0)
	{
		country_id=document.frmEditCustomer.country_id.value;
	}
	if(country_id!="")
	{
		document.getElementById('state_tr').style.display='';
		fpurpose	= 'list_state';
		document.getElementById('retdiv_id').value 		= retdivid;/* Name of div to show the result */
		retobj 											= eval("document.getElementById('"+retdivid+"')");
		retobj.innerHTML 								= '<center><img src="images/loading.gif" alt="Loading"></center>';															
		var qrystr										= '';
		/* Calling the ajax function */
		Handlewith_Ajax('services/customer.php','fpurpose='+fpurpose+'&'+qrystr+'&country_id='+country_id+'&state_id='+state_id);
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
function handle_expansionall(imgobj,mod)
{
	var src 			= imgobj.src;
	var retindx 		= src.search('plus.gif');
	switch(mod)
	{
		case 'products': // Case of product tabs
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('products_tr'))
					document.getElementById('products_tr').style.display = '';
				if(document.getElementById('productsunassign_div'))
					document.getElementById('productsunassign_div').style.display = '';	
				call_ajax_showlistall('products');
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('products_tr'))
					document.getElementById('products_tr').style.display = 'none';
				if(document.getElementById('productsunassign_div'))
					document.getElementById('productsunassign_div').style.display = 'none';
			}	
		break;
		case 'category': // Case of product tabs
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('cat_id'))
					document.getElementById('cat_id').style.display = '';
				
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('cat_id'))
					document.getElementById('cat_id').style.display = 'none';
			}	
		break;
		case 'newsletter': // Case of product tabs
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('news_id'))
					document.getElementById('news_id').style.display = '';
				
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('news_id'))
					document.getElementById('news_id').style.display = 'none';
			}	
		break;
		
	};
}
function call_ajax_showlistall(mod)
{  
	var atleastone 		= 0;
	var customer_id		= '<?php echo $customer_id?>';
	var cat_orders		= '';
	var fpurpose		= '';
	var retdivid		= '';
	var moredivid		= '';
	switch(mod)
	{
		
		case 'products': // Case of product assigned to the Page group
			retdivid   	= 'products_div';
			fpurpose	= 'list_products_ajax';
			moredivid	= 'productsunassign_div';
		break;
	}
	document.getElementById('retdiv_id').value 		= retdivid;/* Name of div to show the result */	
	document.getElementById('retdiv_more').value 	= moredivid;/* Name of div to show the result */	
	retobj 											= eval("document.getElementById('"+retdivid+"')");
	retobj.innerHTML 								= '<center><img src="images/loading.gif" alt="Loading"></center>';															
	var qrystr										= '';
	/* Calling the ajax function */
	//alert('fpurpose='+fpurpose+'&cur_groupid='+group_id);
	Handlewith_Ajax('services/customer.php','fpurpose='+fpurpose+'&customer_id='+customer_id);
}
function call_ajax_changestatuspagesall(mod,checkboxname)
{ 
	var atleastone 		= 0;
	var customer_id		= '<?php echo $customer_id;?>';
	var ch_ids 			= '';
	var qrystr			= '';
	var atleastmsg 		= '';
	var confirmmsg 		= '';
	var retdivid		= '';
	var fpurpose		= '';

	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmEditCustomer.elements.length;i++)
	{
	
	if (document.frmEditCustomer.elements[i].type =='checkbox' && document.frmEditCustomer.elements[i].name==checkboxname)
		{

			if (document.frmEditCustomer.elements[i].checked==true)
			{
				atleastone = 1;
				if (ch_ids!='')
					ch_ids += '~';
				 ch_ids += document.frmEditCustomer.elements[i].value;
			}	
		}
	}
	switch(mod)
	{
		
		case 'product': // Case of product tabs
			atleastmsg 	= 'Please select the product(s) to change the status';
			confirmmsg 	= 'Are you sure you want to Change the Status of selected Product(s) ?';
			retdivid   	= 'products_div';
			moredivid	= 'productsunassign_div';
			fpurpose	= 'changestat_product_ajax';
			var chstat	= document.getElementById('product_chstatus').value;
		break;
		
	}
	if (atleastone==0)
	{
		alert(atleastmsg);
	}
	else
	{
		if(confirm(confirmmsg))
		{	
			document.getElementById('retdiv_id').value 		= retdivid;/* Name of div to show the result */
			document.getElementById('retdiv_more').value	= moredivid;/* Name of div to show the result */	
			retobj 											= eval("document.getElementById('"+retdivid+"')");
			retobj.innerHTML 								= '<center><img src="images/loading.gif" alt="Loading"></center>';
		Handlewith_Ajax('services/customer.php','fpurpose='+fpurpose+'&chstat='+chstat+'&customer_id='+customer_id+'&ch_ids='+ch_ids);
		}	
	}	
}
function call_ajax_deleteall(mod,checkboxname)
{
	var atleastone 			= 0;
	var customer_id			= '<?php echo $customer_id?>';
	var del_ids 			= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmEditCustomer.elements.length;i++)
	{
		if (document.frmEditCustomer.elements[i].type =='checkbox' && document.frmEditCustomer.elements[i].name==checkboxname)
		{

			if (document.frmEditCustomer.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmEditCustomer.elements[i].value;
			}	
		}
	}
	switch(mod)
	{
		
		case 'product': // Case of product tabs
			atleastmsg 	= 'Please select the product(s) to be deleted';
			confirmmsg 	= 'Are you sure you want to delete the selected Product(s)?'
			retdivid   	= 'products_div';
			moredivid	= 'productsunassign_div';
			fpurpose	= 'delete_product_ajax';
		break;
		
	}
	if (atleastone==0)
	{
		alert(atleastmsg);
	}
	else
	{
		if(confirm(confirmmsg))
		{ 
			document.getElementById('retdiv_id').value 		= retdivid;/* Name of div to show the result */
			document.getElementById('retdiv_more').value 	= moredivid;/* Name of div to show the result */	
			retobj 											= eval("document.getElementById('"+retdivid+"')");
			retobj.innerHTML 								= '<center><img src="images/loading.gif" alt="Loading"></center>';
			
			Handlewith_Ajax('services/customer.php','fpurpose='+fpurpose+'&customer_id='+customer_id+'&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}	
</script>
<form name='frmEditCustomer' action='home.php?request=customer' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" align="left" valign="middle" class="treemenutd"><a href="home.php?request=customer&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Customers</a> &gt;&gt; Edit Customer</td>
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
		
		?>  
		 <tr>
		<td colspan="2" align="left" class="seperationtd">Personal Details</td>
		</tr>
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
		  <option value="M/s" <? if($row_customer['customer_title']=='M/s.') echo "selected";?>>M/s.</option>
		 </select>		  </td>
        </tr>
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >First Name <span class="redtext">*</span></td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_fname" value="<?=$row_customer['customer_fname']?>"  />		  </td>
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
		  <select class="input" name="country_id" onchange="changestate(0,0);">
		  <option value="0">-select-</option>
		  <?
		  $sql_country="SELECT country_id,country_name FROM general_settings_site_country WHERE sites_site_id=".$ecom_siteid." AND country_hide=1" ;
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
		<tr>
		<td colspan="2" align="left" >
		<div id="state_tr" style="display:none" align="left" >		</div>		</td>
		</tr>
		<tr>
          <td width="35%" align="left" valign="middle" class="tdcolorgray" >Postcode</td>
          <td width="65%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_postcode" value="<?=$row_customer['customer_postcode']?>"  />		  </td>
        </tr>
		
		<tr>
          <td width="35%" align="left" valign="middle" class="tdcolorgray" >Phone</td>
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
		  <input class="input" type="checkbox" name="customer_activated" value="1" <? if($row_customer['customer_activated']) echo "checked";?>  />		  </td>
        </tr>
		<tr>
          <td width="35%" align="left" valign="middle" class="tdcolorgray" >Hide</td>
          <td width="65%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="checkbox" name="customer_hide"  value="1" <? if($row_customer['customer_hide']) echo "checked";?> />		  </td>
        </tr>
		<tr>
          <td colspan="2" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
		  </tr>
		</table>		</td>
		</tr>
		<?
		if($row_customer['customers_corporation_department_department_id']!=0)
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
		</table>
		</td>
		<td class="tdcolorgray" >
		<table  width="100%" border="0" cellspacing="0" cellpadding="0">
	
		
		<tr>
          <td width="35%" align="left" valign="middle" class="tdcolorgray" >Company RegNo</td>
          <td width="65%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_compregno" value="<?=$row_customer['customer_compregno']?>"  />
         	  </td>
        </tr>
		<tr>
		 <td width="35%" align="left" valign="middle" class="tdcolorgray" >Company VatRegNo</td>
          <td width="65%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_compvatregno" value="<?=$row_customer['customer_compvatregno']?>"  />	
		</tr>
		</table>
		</td>
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
		</table>		</td>
		<td  width="50%" class="tdcolorgray">&nbsp;		</td>
		</tr>
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
		  <input class="input" type="checkbox" name="customer_anaffiliate" value="1" <? if($row_customer['customer_anaffiliate']) echo "checked";?>   />		  </td>
        </tr>
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Approved Affiliate</td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray"> 
		  <input class="input" type="checkbox" name="customer_approved_affiliate" value="1"  <? if($row_customer['customer_approved_affiliate']) echo "checked";?> />		  </td>
        </tr>
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Affiliate Commission</td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_affiliate_commission" size="3" value="<?=$row_customer['customer_affiliate_commission']?>"  />		  </td>
        </tr>
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Affiliate Tax Id</td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_affiliate_taxid" value="<?=$row_customer['customer_affiliate_taxid']?>"  />		  </td>
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
		  <input class="input" type="text" name="customer_discount" size="3"  value="<?=$row_customer['customer_discount']?>" />		  </td>
        </tr>
		
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Reffered By</td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_referred_by" value="<?=$row_customer['customer_referred_by']?>"  />		  </td>
        </tr>
		
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
		</table>		</td>
		</tr>
		<?php
			// Check whether there is in any additional values added for this customer
			$sql_check = "SELECT * FROM customer_registration_values WHERE customers_customer_id=$customer_id ORDER BY element_sections_section_id";
			$ret_check = $db->query($sql_check);
			if($db->num_rows($ret_check))
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
							?>
						  </td>
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
						 ?> 
						  </td>
						</tr>
				<?php
					}
				?>	
				</table>
				</td>
				</tr>
		  <?
		  }
		 // #Selecting already assigned groups
		  $sql_group_assign="SELECT custgroup_id FROM customer_group_customers_map WHERE customer_id=".$customer_id;
		  
		  $res_group_assign = $db->query($sql_group_assign);
		  $arr_assigned=array();

		  while($row_assigned = $db->fetch_array($res_group_assign))
		  {
				$arr_assigned[]=$row_assigned['custgroup_id'];
					
		   }
				
		  
		  
		  $sql_group="SELECT custgroup_id,custgroup_name FROM customer_group WHERE sites_site_id=".$ecom_siteid;
		  $res_group = $db->query($sql_group);
		  if($db->num_rows($res_group)>0)
		  {
		  ?>
		  <tr>
		  <td colspan="2" align="left" valign="middle">
		   <table width="100%" border="0" cellspacing="1" cellpadding="1">
		   <td width="3%" class="seperationtd"><img id="cat_imgtag" src="images/plus.gif" border="0" onclick="handle_expansionall(this,'newsletter')" title="Click"/></td>
           <td colspan="2" align="left" valign="middle" class="seperationtd" >To recieve newsletters please select from the following:</td>
          </table>
		  </td></tr>
		  <tr id="news_id" style="display:none">
          <td colspan="2" align="left" valign="middle" class="tdcolorgray" >
			  <table width="100%" border="0" cellpadding="0" cellspacing="0">
			  <tr>
			  <?
			  $tmp_grcnt=0;
			  
			  while($row_group = $db->fetch_array($res_group))
			  {
			  ?>
			  <td><input type="checkbox" name="chk_group[]" value="<?=$row_group['custgroup_id']?>" <? if(in_array($row_group['custgroup_id'],$arr_assigned)) echo "checked";?> /><?=$row_group['custgroup_name']?></td>
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
			  &nbsp;</td>
        </tr>
		  <?
		  }
		  
		$sql_cat ="SELECT category_id,category_name FROM product_categories WHERE sites_site_id=".$ecom_siteid." AND parent_id=0";
		$res_cat = $db->query($sql_cat);
		if($db->num_rows($res_cat)>0)
		{
		   ?><tr>
		   <td colspan="2" align="left" valign="middle">
		   <table width="100%" border="0" cellspacing="1" cellpadding="1">
		   <td width="3%" class="seperationtd"><img id="cat_imgtag" src="images/plus.gif" border="0" onclick="handle_expansionall(this,'category')" title="Click"/></td>
           <td  align="left" valign="middle" class="seperationtd"  width="97%">Please Select Your Favorite Categories:
		   		  <a href="#" onmouseover ="ddrivetip('If You Select The categories Below It Will Display In The Home Page')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;

		   </td>
		   </table>
		   </td>
		   </tr>
          <tr id="cat_id" style="display:none">
          <td colspan="2" align="left" valign="middle" class="tdcolorgray" >
			  <table width="100%" border="0" cellpadding="0" cellspacing="0">
			  <tr >
			  <?
			  $sql_categories = "SELECT categories_categories_id FROM customer_fav_categories WHERE customer_customer_id=".$customer_id." AND sites_site_id=".$ecom_siteid;
			  $res_categories =$db->query($sql_categories);
			   $arr_assigned_cat=array();
			    while($row_assigned_cat = $db->fetch_array($res_categories))
		 		 {
				$arr_assigned_cat[]=$row_assigned_cat['categories_categories_id'];
					
		  		 }
			  
			  $tmp_catcnt=0;
			  while($row_cat = $db->fetch_array($res_cat))
			  {
			  ?>
			  <td><input type="checkbox" name="chk_cat[]" value="<?=$row_cat['category_id']?>" <? if(in_array($row_cat['category_id'],$arr_assigned_cat)) echo "checked";?> /><?=$row_cat['category_name']?></td>
			  <?
			  $tmp_catcnt++;
			  if($tmp_catcnt>2)
			  {
			  	echo "</tr><tr>";
				$tmp_catcnt=0;
			  }
			  }
			  ?>
			  </tr>
			  </table>
			  &nbsp;</td>
        </tr>
		<? }
		
		
		 $sql_prod="SELECT product_id FROM products WHERE sites_site_id=".$ecom_siteid;
		  $res_prod = $db->query($sql_prod);
		  if($db->num_rows($res_prod)>0)
		  {
		  ?>
		<tr>
          <td colspan="2" align="left" valign="middle">  
		  <?php
		  //Check whether Products are added to this static Page Group
			$sql_product_in_cust = "SELECT products_product_id FROM customer_fav_products
						 WHERE customer_customer_id=$customer_id";
			$ret_product_in_cust = $db->query($sql_product_in_cust);		
		 
		 ?>
		 <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td width="3%" class="seperationtd"><img id="cat_imgtag" src="images/plus.gif" border="0" onclick="handle_expansionall(this,'products')" title="Click"/></td>
              <td width="97%" align="left" class="seperationtd">Favorites Products  
			  <a href="#" onmouseover ="ddrivetip('This Products Will Be Displayed Once The Customer Login ')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
</td>
            </tr>
          </table></td>
        </tr>
		<tr>
          <td colspan="2" align="right" valign="middle" class="tdcolorgray" ><input name="Addmore" type="button" class="red" id="Addmore" value="Assign More" onclick="document.frmEditCustomer.fpurpose.value='list_assign_products';document.frmEditCustomer.sort_order.value='';document.frmEditCustomer.sort_by.value='';document.frmEditCustomer.submit();" />
				<a href="#" onmouseover ="ddrivetip('Allows to assigin Products to this Customer.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
				<?php
				if ($db->num_rows($ret_product_in_cust))
				{
				?>
					<div id="productsunassign_div" class="unassign_div" style="display:none">
					Change Hidden Status to 
					<?php
						$products_status = array(0=>'No',1=>'Yes');
						echo generateselectbox('product_chstatus',$products_status,0);
					?>
					<input name="product_chstatus" type="button" class="red" id="product_chstatus" value="Change" onclick="call_ajax_changestatuspagesall('product','checkboxproducts[]')" />
					<a href="#" onmouseover ="ddrivetip('Allows to Change the status of selected Product assigned for the customer. Select the Product(s), select the new status and press the \'Change\' button.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
								
					&nbsp;&nbsp;&nbsp;<input name="products_unassign" type="button" class="red" id="products_unassign" value="Un Assign" onclick="call_ajax_deleteall('product','checkboxproducts[]')" />
					<a href="#" onmouseover ="ddrivetip('Allows to Un assign the selected Product(s)  for the customer .')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
					</div>	
				<?php
				}				
				?></td>
        </tr>
		<tr id="products_tr">
			<td align="right" colspan="2" class="tdcolorgray_buttons">
			<div id="products_div" style="text-align:center">
			</div>
			</td>
		</tr>
		<? } ?>
		<!--		for displaying the products assigned to the static Page group ENDS  -->	
		<tr>
          <td colspan="2" align="left" valign="middle" class="tdcolorgray" >&nbsp; </td>
        </tr>  
		<tr>
          <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td align="left" valign="middle" class="tdcolorgray">
		  
		  <input type="hidden" name="customer_id" id="customer_id" value="<?=$customer_id?>" />
		  <input type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>" />
		  <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
		  <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
		  <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
		  <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
		  <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
		  <input type="hidden" name="fpurpose" id="fpurpose" value="update" />
		  <input type="hidden" name="checkbox[0]" id="checkbox[0]" value="<?php echo $customer_id?>" />
		  <input type="hidden" name="src_page" id="src_page" value="customer" />
		  <input type="hidden" name="src_id" id="src_id" value="<?php echo $customer_id?>" />
		  <input type="hidden" name="retdiv_id" id="retdiv_id" value="" />
		 <input type="hidden" name="retdiv_more" id="retdiv_more" value="" />
		  <input name="Submit" type="submit" class="red" value="Update" /></td>
        </tr>
  </table>
</form>	  

<script>
changestate(<?=$row_customer['country_id']?>,'<?=$row_customer['customer_statecounty']?>');
</script>