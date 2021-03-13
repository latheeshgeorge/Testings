<? 
	/*#################################################################
	# Script Name 	: add_promotional_code.php
	# Description 	: Page for adding promotional codes
	# Coded by 		: Sny
	# Created on	: 29-Oct-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
	
	
	// Define constants for this page
	$page_type = 'Promotional Codes';
	//$help_msg = 'This section helps in editing the Promotional Codes';
    $help_msg = get_help_messages('EDIT_PROM_CODE_MESS1');
	// Get the details of selected promotional code
	$sql_prom = "SELECT * FROM promotional_code WHERE code_id=".$_REQUEST['checkbox'][0];
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
				if(document.frm_promo.code_value.value=='' || isNaN(document.frm_promo.code_value.value))
				{
					alert('Discount % is invalid');
					return false;
				}
				else if(document.frm_promo.code_value.value>100)
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
				if(document.frm_promo.code_value.value=='' || isNaN(document.frm_promo.code_value.value) || document.frm_promo.code_value.value<0)
				{
					alert('Discount % is invalid');
					return false;
				}
				else if(document.frm_promo.code_value.value>100 && document.frm_promo.code_type.value=='percent')
				{
					alert('Discount % should be less than 100');
					return false;
				}
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
	
	function handle_codetype(val)
	{
		if(val=='default')
		{
			document.getElementById('tr_discmin').style.display='none';
			document.getElementById('tr_discval').style.display='';
			document.getElementById('dis_val').innerHTML = 'Discount % <span class="redtext">*</span>';
		}
		else if (val=='percent')
		{
			document.getElementById('tr_discmin').style.display='';
			document.getElementById('tr_discval').style.display='';
			document.getElementById('dis_val').innerHTML = 'Discount % <span class="redtext">*</span>';
		}
		else if (val=='money')
		{
			document.getElementById('tr_discmin').style.display='';
			document.getElementById('tr_discval').style.display='';
			document.getElementById('dis_val').innerHTML = 'Discount value <span class="redtext">*</span>';
		}
		else if (val=='product')
		{
			document.getElementById('tr_discmin').style.display='none';
			document.getElementById('tr_discval').style.display='none';
		}
	}
	function ajax_return_contents() 
	{
	var ret_val='';
	if(req.readyState==4)
	{
		if(req.status==200)
		{
			ret_val 	= req.responseText;
			targetdiv 	= document.getElementById('retdiv_id').value;
			norecdiv 	= document.getElementById('retdiv_more').value;
			targetobj 	= eval("document.getElementById('"+targetdiv+"')");
			targetobj.innerHTML = ret_val; /* Setting the output to required div */
			/* Decide the display of action buttons*/
			switch(targetdiv)
			{
				case 'prom_product_div':
					if(document.getElementById('promprod_norec'))
					{
						if(document.getElementById('promprod_norec').value==1)
						{
							disp = 'none';
						}	
						else
							disp = '';
					}
					else
						disp = '';
				break;
				
		  }
			if (disp!='no')
			{
				norecobj 	= eval("document.getElementById('"+norecdiv+"')");
				if (norecobj)
					norecobj.style.display = disp;
			}	
			if(document.getElementById('mainerr_tr'))
				document.getElementById('mainerr_tr').style.display = 'none';
			hide_processing();
		}
		else
		{
			 show_request_alert(req.status);
			//alert("Problem in requesting XML :"+req.statusText);
		}
	}
}
function call_ajax_unassign(cname,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var curid				= 0;
	var cat_ids 			= '';
	var cat_orders			= '';
	var group_id			= '<?php echo $edit_id?>';
	var qrystr				= 'catgroupname='+cname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&group_id='+group_id;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frm_promo.elements.length;i++)
	{
		if (document.frm_promo.elements[i].type =='checkbox' && document.frm_promo.elements[i].name=='checkboxcat[]')
		{

			if (document.frm_promo.elements[i].checked==true)
			{
				atleastone = 1;
				if (cat_ids!='')
					cat_ids += '~';
				 cat_ids += document.frm_promo.elements[i].value;
			}	
		}
	}
	if (atleastone==0) 
	{
		alert('Please select the product categories to be Unassigned');
	}
	else
	{
		if(confirm('Unassign selected categories?'))
		{
				show_processing();
				Handlewith_Ajax('services/product_category_groups.php','fpurpose=unassigncat&'+qrystr+'&catids='+cat_ids);
		}	
	}	
}
function call_ajax_prodGroupUnAssign(cname,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var curid				= 0;
	var product_ids 		= '';
	var cat_orders			= '';
	var group_id			= '<?php echo $edit_id?>';
	var qrystr				= 'catgroupname='+cname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&group_id='+group_id;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frm_promo.elements.length;i++)
	{
		if (document.frm_promo.elements[i].type =='checkbox' && document.frm_promo.elements[i].name=='checkboxproduct[]')
		{

			if (document.frm_promo.elements[i].checked==true)
			{
				atleastone = 1;
				if (product_ids!='')
					product_ids += '~';
				 product_ids += document.frm_promo.elements[i].value;
			}	
		}
	}
	if (atleastone==0) 
	{
		alert('Please select the products to be Unassigned');
	}
	else
	{
		if(confirm('Unassign selected products?'))
		{
				show_processing();
				Handlewith_Ajax('services/product_category_groups.php','fpurpose=prodGroupUnAssign&'+qrystr+'&productids='+product_ids);
		}	
	}	
}
function call_ajax_categoryGroupUnAssign(cname,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var curid				= 0;
	var category_ids 		= '';
	var cat_orders			= '';
	var group_id			= '<?php echo $edit_id?>';
	var qrystr				= 'catgroupname='+cname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&group_id='+group_id;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frm_promo.elements.length;i++)
	{
		if (document.frm_promo.elements[i].type =='checkbox' && document.frm_promo.elements[i].name=='checkboxcategory[]')
		{

			if (document.frm_promo.elements[i].checked==true)
			{
				atleastone = 1;
				if (category_ids!='')
					category_ids += '~';
				 category_ids += document.frm_promo.elements[i].value;
			}	
		}
	}
	if (atleastone==0) 
	{
		alert('Please select the categories to be Unassigned');
	}
	else
	{
		if(confirm('Unassign selected categories?'))
		{
				show_processing();
				Handlewith_Ajax('services/product_category_groups.php','fpurpose=categoryGroupUnAssign&'+qrystr+'&category_ids='+category_ids);
		}	
	}	
}
function normal_assignselpromproduct(codenumber,sortby,sortorder,recs,start,pg,editid)
{
		window.location 			= 'home.php?request=prom_code&fpurpose=assign_promprod&pass_codenumber='+codenumber+'&pass_sortby='+sortby+'&pass_sortorder='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_editid='+editid;
}
function normal_assign_prodGroupAssign(cname,sortby,sortorder,recs,start,pg,groupid)
{
		window.location 			= 'home.php?request=prod_cat_group&fpurpose=prodGroupAssign&pass_catgroupname='+cname+'&pass_sortby='+sortby+'&pass_sortorder='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_group_id='+groupid;
}
function call_ajax_showlistall(mod)
{  
	var atleastone 										= 0;
	var cur_promid										= '<?php echo $_REQUEST['checkbox'][0]?>';
	var cat_orders										= '';
	var fpurpose										= '';
	var retdivid										= '';
	var moredivid										= '';
	switch(mod)
	{
		case 'prom_product': // Case of Categories in the group
			retdivid   	= 'prom_product_div';
			fpurpose	= 'list_prom_products';
			moredivid	= 'prom_product_unassign_div';
		break;
		case 'show_promotionalorder': // Case of category images
			retdivid   	= 'promotionalorder_div';
			fpurpose	= 'list_orders';
		break;
	}
	document.getElementById('retdiv_id').value 		= retdivid;/* Name of div to show the result */	
	document.getElementById('retdiv_more').value 	= moredivid;/* Name of div to show the result */	
	retobj 											= eval("document.getElementById('"+retdivid+"')");
	retobj.innerHTML 								= '<center><img src="images/loading.gif" alt="Loading"></center>';															
	var qrystr										= '';
	/* Calling the ajax function */
	Handlewith_Ajax('services/promotional_code.php','fpurpose='+fpurpose+'&cur_promid='+cur_promid);	
}		
function handle_expansion(imgobj,mod)
{
	var src = imgobj.src;
	var retindx = src.search('plus.gif');
	switch(mod)
	{
		case 'prom_product':
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('prom_product_tr'))
					document.getElementById('prom_product_tr').style.display = '';
				
				if(document.getElementById('prom_product_div'))
					document.getElementById('prom_product_div').style.display = '';	
				call_ajax_showlistall('prom_product');		
				
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('prom_product_tr'))
					document.getElementById('prom_product_tr').style.display = 'none';
				if(document.getElementById('prom_product_div'))
					document.getElementById('prom_product_div').style.display = 'none';	
				
			}	
		break;
	 
		case 'displayproduct_group' :
			if(retindx!=-1)
			{
				imgobj.src='images/minus.gif';
				if(document.getElementById('displayproduct_groupunassign_div'))
					document.getElementById('displayproduct_groupunassign_div').style.display = '';
				if(document.getElementById('displayproduct_grouptr_details'))
					document.getElementById('displayproduct_grouptr_details').style.display = '';
				call_ajax_showlistall('displayproduct_group');	
				
			}
			else
			{
				imgobj.src='images/plus.gif';
				if(document.getElementById('displayproduct_groupunassign_div'))
					document.getElementById('displayproduct_groupunassign_div').style.display = 'none';
				if(document.getElementById('displayproduct_grouptr_details'))
					document.getElementById('displayproduct_grouptr_details').style.display = 'none';
				
	            

			}
		break;
		case 'promotionalorder': /* Case of orders which used the voucher*/
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('promotionalorder_tr'))
					document.getElementById('promotionalorder_tr').style.display = '';
				if(document.getElementById('promotionalorderunassign_div'))
					document.getElementById('promotionalorderunassign_div').style.display = '';	
				call_ajax_showlistall('show_promotionalorder');
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('promotionalorder_tr'))
					document.getElementById('promotionalorder_tr').style.display = 'none';
				if(document.getElementById('promotionalorderunassign_div'))
					document.getElementById('promotionalorderunassign_div').style.display = 'none';
			}	
		break;
	};
}
function call_ajax_deleteall(mod,checkboxname)
{
	
	var atleastone 			= 0;
	var edit_id				= '<?php echo $_REQUEST['checkbox'][0] ?>';
	var del_ids 			= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frm_promo.elements.length;i++)
	{
		if (document.frm_promo.elements[i].type =='checkbox' && document.frm_promo.elements[i].name==checkboxname)
		{

			if (document.frm_promo.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frm_promo.elements[i].value;
			}	
		}
	}
	switch(mod)
	{
		case 'prom_product': // Case of Categories Products in the group
			atleastmsg 	= 'Please select the product(s) to be unassigned from this promotional code';
			confirmmsg 	= 'Are you sure you want to unassign selected product(s) from this promotional code?';
			retdivid   	= 'prom_product_div';
			moredivid	= 'prom_product_unassign_div';
			fpurpose	= 'unassignpromproduct';
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
			
			document.getElementById('retdiv_id').value 	= retdivid;/* Name of div to show the result */
			document.getElementById('retdiv_more').value 	= moredivid;/* Name of div to show the result */	
			retobj 										= eval("document.getElementById('"+retdivid+"')");
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/promotional_code.php','fpurpose='+fpurpose+'&edit_id='+edit_id+'&del_ids='+del_ids+'&'+qrystr);
		}	
	}	

}
function call_ajax_changestatus(mod,checkboxname)
{
	
	var atleastone 			= 0;
	var edit_id				= '<?php echo $_REQUEST['checkbox'][0] ?>';
	var ch_ids 				= '';
	var prod_active			= document.getElementById('product_active').value;
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frm_promo.elements.length;i++)
	{
		if (document.frm_promo.elements[i].type =='checkbox' && document.frm_promo.elements[i].name==checkboxname)
		{

			if (document.frm_promo.elements[i].checked==true)
			{
				atleastone = 1;
				if (ch_ids!='')
					ch_ids += '~';
				 ch_ids += document.frm_promo.elements[i].value;
			}	
		}
	}
	switch(mod)
	{
		case 'prom_product': // Case of Categories Products in the group
			atleastmsg 	= 'Please select the product(s) to change the hidden status';
			confirmmsg 	= 'Are you sure you want to change hidden status of selected product(s)?';
			retdivid   	= 'prom_product_div';
			moredivid	= 'prom_product_unassign_div';
			fpurpose	= 'chstatuspromproduct';
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
			
			document.getElementById('retdiv_id').value 	= retdivid;/* Name of div to show the result */
			document.getElementById('retdiv_more').value 	= moredivid;/* Name of div to show the result */	
			retobj 										= eval("document.getElementById('"+retdivid+"')");
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/promotional_code.php','fpurpose='+fpurpose+'&prod_active='+prod_active+'&edit_id='+edit_id+'&ch_ids='+ch_ids+'&'+qrystr);
		}	
	}	

}
function call_ajax_saveprice(mod,checkboxname)
{
	
	var atleastone 			= 0;
	var edit_id				= '<?php echo $_REQUEST['checkbox'][0] ?>';
	var sav_ids 			= '';
	var sav_price			= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';
	var err					= 0;	
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frm_promo.elements.length;i++)
	{
		
		if (document.frm_promo.elements[i].type =='checkbox' && document.frm_promo.elements[i].name==checkboxname)
		{
			
			if (document.frm_promo.elements[i].checked==true)
			{
				atleastone = 1;
				if (sav_ids!='')
				{
					sav_ids 	+= '~';
					sav_price 	+= '~';
				}	
				
			
				priceobj 	= eval('document.getElementById("prom_prod_price_'+document.frm_promo.elements[i].value+'")');
				hidwebprice = eval('document.getElementById("hid_webprice_'+document.frm_promo.elements[i].value+'")');
				
				sav_price 	+= priceobj.value;
				hid_web_price = hidwebprice.value;
				sav_ids 	+= document.frm_promo.elements[i].value;
				if(hidwebprice.value<priceobj.value)
				{
					alert("Web Price Should Be greater than Promotional Price");
					err = 1;
				}	
			
			
			}	
		}
	}
	if(err==0) {
	
	switch(mod)
	{
		case 'save_price': // Case of saving the price
			atleastmsg 	= 'Please select the product(s) for which the promotional price is to be saved';
			confirmmsg 	= 'Are you sure you want to save the promotional price for selected product(s)?';
			retdivid   	= 'prom_product_div';
			moredivid	= 'prom_product_unassign_div';
			fpurpose	= 'savepricepromproduct';
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
			
			document.getElementById('retdiv_id').value 	= retdivid;/* Name of div to show the result */
			document.getElementById('retdiv_more').value 	= moredivid;/* Name of div to show the result */	
			retobj 										= eval("document.getElementById('"+retdivid+"')");
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/promotional_code.php','fpurpose='+fpurpose+'&edit_id='+edit_id+'&sav_ids='+sav_ids+'&sav_price='+sav_price+'&'+qrystr);
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
	<td align="left" class="treemenutd"><a href="home.php?request=prom_code&codenumber=<?php echo $_REQUEST['codenumber']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>">Promotional Code</a>  &raquo; Edit Promotional Code </b></td>
	</tr>
	<tr>
	<td align="left" class="helpmsgtd" >
	<?=$help_msg ?></td>
	</tr>
	<tr>
	<td></td>
	</tr>
	<?php 
		if ($alert)
		{
?>
          <tr id="mainerr_tr">
            <td align="center" class="errormsg"><?php echo $alert?></td>
          </tr>
<?php
		}
?>
	</table>
	<table width="100%" border="0" cellspacing="1" cellpadding="1">
      <tr>
        <td width="55%" align="left" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="2">

          <tr>
            <td align="left" class="tdcolorgray" >Promotional Code <span class="redtext">*</span> </td>
            <td align="center" class="tdcolorgray" >:</td>
            <td align="left" class="tdcolorgray" ><input name="code_number" type="text" id="code_number" size="40" value="<?php echo $row_prom['code_number']?>" /></td>
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
                <option value="product" <?php if ($ctype=='product') echo 'selected="selected"'?>>Value Off on selected products</option>
              </select>
            </td>
          </tr>
          <tr id="tr_discmin" style="display:none;">
            <td align="left" class="tdcolorgray" >Discount for Minimum </td>
            <td align="center" class="tdcolorgray" >:</td>
            <td align="left" class="tdcolorgray" ><input name="code_minimum" type="text" size="8" value="<?php echo $row_prom['code_minimum']?>" /></td>
          </tr>
          <tr id="tr_discval">
            <td align="left" class="tdcolorgray" ><div id='dis_val'>Discount % <span class="redtext">*</span></div></td>
            <td align="center" class="tdcolorgray" >:</td>
            <td align="left" class="tdcolorgray" ><input name="code_value" type="text" size="8" value="<?php echo $row_prom['code_value']?>" /></td>
          </tr>
		  <tr>
		  <td align="left" valign="middle" class="tdcolorgray">Hidden?</td>
		  <td align="center" class="tdcolorgray" >:</td>
		   <td align="left" valign="middle" class="tdcolorgray"><input type="radio" name="code_hide" value="1" <? if($row_prom['code_hidden']==1) echo "checked";?>  />
		     Yes
		     <input type="radio" name="code_hide" value="0"  <? if($row_prom['code_hidden']==0) echo "checked";?> />
		     No&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROM_CODE_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>

		  </tr>
          <tr id="tr_discval">
            <td align="left" class="tdcolorgray" >&nbsp;</td>
            <td align="center" class="tdcolorgray" >&nbsp;</td>
            <td align="left" class="tdcolorgray" ><input type="button" name="Button" value="Save" onclick="validate_promotional_code()" class="red" /></td>
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
            <td colspan="2" align="left" class="tdcolorgray" ><strong>Value Off on selected products :-</strong> </td>
          </tr>
          <tr>
            <td class="tdcolorgray" >&nbsp;</td>
            <td align="left" class="tdcolorgray" >In case if  promotional price is to be given for selected products, this option can be used. The option to link the products to the promotional code will be displayed once the promotional code details are saved.</td>
          </tr>
        </table></td>
      </tr>
    </table>
    </td>
</tr>
 <tr >
            <td align="center" class="tdcolorgray">&nbsp;</td>
          </tr>
		  <tr >
            <td align="center" class="tdcolorgray">&nbsp;</td>
          </tr>
		  <tr >
            <td align="center" class="tdcolorgray">&nbsp;</td>
          </tr>
		  
<?php
	if($ctype=='product')
	{
?>
		<tr >
          <td colspan="4" align="left" valign="bottom">&nbsp;
            <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td width="3%" class="seperationtd"><img id="cat_imgtag" src="images/plus.gif" border="0" onclick="handle_expansion(this,'prom_product')" title="Click"/></td>
              <td width="97%" align="left" class="seperationtd">Products in this promotional code</td>
            </tr>
          </table></td>
        </tr>
		 <?php
		 // Get the list of categories under current category group
			$sql_promprod = "SELECT products_product_id FROM promotional_code_product  
						WHERE promotional_code_code_id=".$_REQUEST['checkbox'][0];
			$ret_promprod = $db->query($sql_promprod);
		 ?>
		 <tr>
		  <td align="right" colspan="4" class="tdcolorgray_buttons">
			<input name="Assignmore" type="button" class="red" id="Assignmore" value="Assign More" onclick="normal_assignselpromproduct('<?php echo $_REQUEST['codenumber']?>','<?php echo $_REQUEST['sort_by']?>','<?php echo $_REQUEST['sort_order']?>','<?php echo $_REQUEST['records_per_page']?>','<?php echo $_REQUEST['start']?>','<?php echo $_REQUEST['pg']?>','<?php echo $_REQUEST['checkbox'][0]?>');" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROM_CODE_ASS_PROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
			<?php
			if ($db->num_rows($ret_promprod))
			{
			?>
			<div id="prom_product_unassign_div" class="unassign_div" style="display:none">
			<input name="Save_prodall" type="button" class="red" id="Save_prodall" value="Save Price" onclick="call_ajax_saveprice('save_price','checkboxprod[]')" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROM_CODE_SAVE_PRICE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
			Change Status
			<?php
				$prom_prod_status = array(1=>'No',0=>'Yes');
				echo generateselectbox('product_active',$prom_prod_status,1);
			?>
			<input name="ch_status" type="button" class="red" id="ch_status" value="Change" onclick="call_ajax_changestatus('prom_product','checkboxprod[]')" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROM_CODE_ASS_PROD_CHASTATUS')?>')"; onmouseout="hideddrivetip()">
			<img src="images/helpicon.png" width="17" height="13" border="0" /></a>
			<input name="Unassign" type="button" class="red" id="Unassign" value="Unassign" onclick="call_ajax_deleteall('prom_product','checkboxprod[]')" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROM_CODE_UNASS_PROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
			</div>	
			<?php
			}
			?>
		  </td>
		  </tr>
		
			 <tr  id="prom_product_tr" style="display:none" >
          		<td colspan="4" align="left" valign="middle" class="tdcolorgray" >
				<div id="prom_product_div" style="text-align:center">
			    </div>
			   </td>
			</tr>
<?php
	}
?>	
<tr>
          <td colspan="4" align="left" valign="bottom">
            <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td width="3%" class="seperationtd"><img id="cat_imgtag" src="images/plus.gif" border="0" onclick="handle_expansion(this,'promotionalorder')" title="Click"/></td>
              <td width="97%" align="left" class="seperationtd">Order(s) which used this Promotional code</td>
            </tr>
          </table></td>
        </tr>
		<tr >
		<tr id="promotionalorder_tr" style="display:none" >
			<td align="left" colspan="4" valign="middle" class="tdcolorgray"  >
				<div id="promotionalorder_div" style="text-align:center"></div>
			</td>
		</tr>		
</table>
	<input type="hidden" name="fpurpose" value="updatecode">
	<input type="hidden" name="codenumber" value="<?php echo $_REQUEST['codenumber']?>">
	<input type="hidden" name="sort_by" value="<?php echo $_REQUEST['sort_by']?>">
	<input type="hidden" name="sort_order" value="<?php echo $_REQUEST['sort_order']?>">
	<input type="hidden" name="start" value="<?php echo $_REQUEST['start']?>">
	<input type="hidden" name="pg" value="<?php echo $_REQUEST['pg']?>">
	<input type="hidden" name="checkbox[0]" value="<?php echo $_REQUEST['checkbox'][0]?>">
	<input type="hidden" name="records_per_page" value="<?php echo $_REQUEST['records_per_page']?>">
	<input type="hidden" name="retdiv_id" id="retdiv_id" value="maincontent" />
    <input type="hidden" name="retdiv_more" id="retdiv_more" value="" />
</form>
<script type="text/javascript">
	handle_codetype('<?php echo $ctype?>');
</script>