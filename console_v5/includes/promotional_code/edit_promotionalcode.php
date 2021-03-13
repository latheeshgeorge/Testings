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
    $help_msg  = get_help_messages('EDIT_PROM_CODE_MESS1');
	($_REQUEST['code_id']>0)?$code_id=$_REQUEST['code_id']:$code_id=$_REQUEST['checkbox'][0];
	// Get the details of selected promotional code
	$sql_prom = "SELECT * FROM promotional_code WHERE code_id=".$code_id." AND sites_site_id=$ecom_siteid";
	$ret_prom = $db->query($sql_prom);
	if($db->num_rows($ret_prom)==0)  { echo " <font color='red'> You Are Not Authorised  </a>"; exit; } 
	if($db->num_rows($ret_prom))
	{
		$row_prom = $db->fetch_array($ret_prom);
		$ctype = $row_prom['code_type'];
		$usedlimit = $row_prom['code_usedlimit'];
	}
	if($_REQUEST['alert']==1)
	{
	 $alert ="Promotional code added successfully<br>Please select the products to be linked with this promotional code";
	}
	(trim($code_number))?$code_number=$_REQUEST['code_number']:$code_number=$row['code_number'];
	$curtab		= ($_REQUEST['curtab'])?$_REQUEST['curtab']:'main_tab_td';

?>	
<script language="Javascript"> 	
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
				if(document.frm_promo.code_value.value==''  || document.frm_promo.code_value.value<0 || isNaN(document.frm_promo.code_value.value))
				{
					alert('Discount % is invalid');
					return false;
				}
				else if(document.frm_promo.code_value.value>=100)
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
				if(document.frm_promo.code_value.value=='' || isNaN(document.frm_promo.code_value.value) || document.frm_promo.code_value.value<0)
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
			 if(document.frm_promo.code_limit.value=='' || isNaN(document.frm_promo.code_limit.value) || document.frm_promo.code_limit.value<0 || document.frm_promo.code_limit.value < <?=$usedlimit ?>)
			 {
				alert('Invalid Limit');
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
		if(document.frm_promo.code_unlimit_check.checked == false && document.frm_promo.code_customer_unlimit_check.checked == false)
		{ 
			 if(parseInt(document.frm_promo.code_customer_limit.value) > parseInt(document.frm_promo.code_limit.value) )
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
	function ajax_return_contents() 
	{
	var ret_val='';
	if(req.readyState==4)
	{
		if(req.status==200)
		{
			ret_val 	= req.responseText;
			targetdiv 	= document.getElementById('retdiv_id').value;
		//	norecdiv 	= document.getElementById('retdiv_more').value;
			targetobj 	= eval("document.getElementById('"+targetdiv+"')");
			targetobj.innerHTML = ret_val; /* Setting the output to required div */
			/* Decide the display of action buttons */
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
			/*if (disp!='no')
			{
				norecobj 	= eval("document.getElementById('"+norecdiv+"')");
				if (norecobj)
					norecobj.style.display = disp;
			}	*/
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
	handle_codetype('<?php echo $ctype?>');
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

function normal_assignselpromproduct(codenumber,sortby,sortorder,recs,start,pg,code_id,code_type='')
{
		window.location 			= 'home.php?request=prom_code&fpurpose=assign_promprod&codenumber='+codenumber+'&pass_sort_by='+sortby+'&pass_sort_order='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_editid='+code_id+'&pass_code_type='+code_type;
}

function call_ajax_showlistall(mod)
{  
	var atleastone 										= 0;
	var cur_promid										= '<?php echo $code_id?>';
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
/*function handle_expansion(imgobj,mod)
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
		case 'promotionalorder': /* Case of orders which used the voucher/
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
}*/
function call_ajax_deleteall(mod,checkboxname)
{
	
	var atleastone 			= 0;
	var edit_id				= '<?php echo $code_id ?>';
	var code_type				= '<?php echo $ctype ?>';
	var del_ids 			= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';
	var cname								= '<?php echo $_REQUEST['catgroupname']?>';
	var codenumber							= '<?php echo $_REQUEST['codenumber']?>';
	var sortby								= '<?php echo $_REQUEST['sort_by']?>';
	var sortorder							= '<?php echo $_REQUEST['sort_order']?>';
	var recs								= '<?php echo $_REQUEST['records_per_page']?>';
	var start								= '<?php echo $_REQUEST['start']?>';
	var pg									= '<?php echo $_REQUEST['pg']?>';
	var curtab								= '<?php echo $curtab?>';
	var code_number							= '<?php echo $code_number; ?>';
	var qrystr								= 'catgroupname='+cname+'&codenumber='+codenumber+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&curtab='+curtab+'&code_number='+code_number;

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
		//	retdivid   	= 'prom_product_div';
		//	moredivid	= 'prom_product_unassign_div';
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
			
			//document.getElementById('retdiv_id').value 	= retdivid;/* Name of div to show the result */
		//	document.getElementById('retdiv_more').value 	= moredivid;/* Name of div to show the result */	
			retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/promotional_code.php','fpurpose='+fpurpose+'&edit_id='+edit_id+'&code_type='+code_type+'&del_ids='+del_ids+'&'+qrystr);
		}	
	}	

}
function call_ajax_changestatus(mod,checkboxname)
{
	
	var atleastone 			= 0;
	var edit_id				= '<?php echo $code_id ?>';
	var ch_ids 				= '';
	var prod_active			= document.getElementById('product_active').value;
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';
	var cname								= '<?php echo $_REQUEST['catgroupname']?>';
	var codenumber							= '<?php echo $_REQUEST['codenumber']?>';
	var sortby								= '<?php echo $_REQUEST['sort_by']?>';
	var sortorder							= '<?php echo $_REQUEST['sort_order']?>';
	var recs								= '<?php echo $_REQUEST['records_per_page']?>';
	var start								= '<?php echo $_REQUEST['start']?>';
	var pg									= '<?php echo $_REQUEST['pg']?>';
	var curtab								= '<?php echo $curtab?>';
	var code_number							= '<?php echo $code_number; ?>';
	var qrystr								= 'catgroupname='+cname+'&codenumber='+codenumber+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&curtab='+curtab+'&code_number='+code_number;

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
			//document.getElementById('retdiv_id').value 	= retdivid;/* Name of div to show the result */
			//document.getElementById('retdiv_more').value 	= moredivid;/* Name of div to show the result */	
			retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/promotional_code.php','fpurpose='+fpurpose+'&prod_active='+prod_active+'&edit_id='+edit_id+'&ch_ids='+ch_ids+'&'+qrystr);
		}	
	}	

}
function call_ajax_saveprice(mod,checkboxname)
{
	
	var atleastone 			= 0;
	var edit_id				= '<?php echo $code_id ?>';
	var sav_ids 			= '';
	var sav_price			= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';
	var err					= 0;
	err_neg                = 0;
	var cname								= '<?php echo $_REQUEST['catgroupname']?>';
	var codenumber							= '<?php echo $_REQUEST['codenumber']?>';
	var sortby								= '<?php echo $_REQUEST['sort_by']?>';
	var sortorder							= '<?php echo $_REQUEST['sort_order']?>';
	var recs								= '<?php echo $_REQUEST['records_per_page']?>';
	var start								= '<?php echo $_REQUEST['start']?>';
	var pg									= '<?php echo $_REQUEST['pg']?>';
	var curtab								= '<?php echo $curtab?>';
	var code_number							= '<?php echo $code_number; ?>';
	var qrystr								= 'catgroupname='+cname+'&codenumber='+codenumber+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&curtab='+curtab+'&code_number='+code_number;
	
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
							
				if(parseFloat(hidwebprice.value)<parseFloat(priceobj.value))
				{
					alert("Web Price Should Be greater than Promotional Price");
					err = 1;
				}
				if(parseFloat(priceobj.value)<0)
				{
				    alert("Promotional Price should be a positive value.");
					err_neg = 1;
					return false;
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
		//	retdivid   	= 'prom_product_div';
		//	moredivid	= 'prom_product_unassign_div';
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
			
		//	document.getElementById('retdiv_id').value 	= retdivid;/* Name of div to show the result */
			//document.getElementById('retdiv_more').value 	= moredivid;/* Name of div to show the result */	
			retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/promotional_code.php','fpurpose='+fpurpose+'&edit_id='+edit_id+'&sav_ids='+sav_ids+'&sav_price='+sav_price+'&'+qrystr);
		}	
	}
	}	
}

function call_ajax_savedetails(mod,checkboxname)
{
	var atleastone 			= 0;
	var codedistype			= 0;
	var code_id				= '<?php echo $code_id?>';
    var code_type			= '<?php echo $ctype ?>';
	var prom_combid			= '';
	var prom_combprice		= '';
	var mainprom_combid			= '';
	var mainprom_combprice		= '';
	var ch_ids 				= '';
	var ch_dis 				= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';
	var pricebox				= '';
	var varid				= '';
	var varvalueid			= '';
	var varprodids			= '';
	var tempname;
	var discgreater			= false;
	switch(mod)
	{
		case 'save_det': // Case of saving product details
			atleastmsg 	= 'Please select the Products to Save the details';
			confirmmsg 	= 'Are you sure you want to Save the details of selected Product(s)?';
			fpurpose	= 'save_details';
			//pricebox	= 'product_combo_price_';
			varbox		= 'comb_var_';
			
		break;
		
	}
	var temparr,tempnames;
	var tempprod = tempvar = tempvarval = tempprodasn = '';
	/* check whether any checkbox is ticked */
	var codeDisTypeVal	=	document.getElementById('code_dis_type').value;
	for(i=0;i<document.frm_promo.elements.length;i++)
	{
		if (document.frm_promo.elements[i].type =='checkbox' && document.frm_promo.elements[i].name== checkboxname)
		{
			if (document.frm_promo.elements[i].checked==true)
			{
				atleastone = 1;
				if (ch_ids!='')
					ch_ids += '~';
				ch_ids += document.frm_promo.elements[i].value;
				for(ii=0;ii<document.frm_promo.elements.length;ii++)
				{
					hidobj = eval("document.getElementById('more_comb_hidden_"+document.frm_promo.elements[i].value+"')");
					if(hidobj)
					{
						if (hidobj.value==1)
						{
							tempnames = 'comb_var_'+document.frm_promo.elements[i].value;
							if (document.frm_promo.elements[ii].name.substr(0,tempnames.length)==tempnames)
							{
								temparr 	= document.frm_promo.elements[ii].name.split('_');
								tempprodasn = temparr[2];
								tempprod 	= temparr[4];
								tempvar		= temparr[3];
								if(document.frm_promo.elements[ii].type=='select-one')
									tempvarval	= document.frm_promo.elements[ii].value; 
								else if(document.frm_promo.elements[ii].type=='checkbox')
								{
									if(document.frm_promo.elements[ii].checked==true)
										tempvarval	= 1;
									else
										tempvarval	= 0;
								}
								
								
								if (varprodids != '')
									varprodids += '~';
								varprodids += tempprodasn+'_'+tempvar+'_'+tempvarval; 
							}
						}	
					}
					/* Case of direct promotional price for product without variables*/
					newobj = eval("document.getElementById('promprice_"+document.frm_promo.elements[i].value+"')");
					if(newobj)
					{
						var tmp = newobj.name.split('_');
						if(mainprom_combid!='')
						{
							mainprom_combid += '~';
							mainprom_combprice += '~';
							
						}	
						mainprom_combid += tmp[1];
						if(codeDisTypeVal == 1)
						{
							if(newobj.value > 100)
							{
								codedistype	=	1;
							}
						}
						mainprom_combprice += newobj.value;
					}
					var curname = 'combprice_'+document.frm_promo.elements[i].value;
					var curlen  = curname.length;
					if(document.frm_promo.elements[ii].name.substr(0,curlen)==curname)
					{
						var tmp = document.frm_promo.elements[ii].name.split('_');
						if(prom_combid!='')
						{
							prom_combid += '~';
							prom_combprice += '~';
							
						}	
						prom_combid += tmp[2];
						prom_combprice += document.frm_promo.elements[ii].value;
					} 
				}
			}	
		}
	}
	if (atleastone==0)
	{
		alert(atleastmsg);
	}
	else if(codedistype == 1)
	{
		alert("Promotional Price cannot be greater than 100%!!!");
	}
	else
	{
		if(confirm(confirmmsg))
		{ 
			retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/promotional_code.php','fpurpose='+fpurpose+'&code_id='+code_id+'&ch_ids='+ch_ids+'&varprod_ids='+varprodids+'&prom_combid='+prom_combid+'&prom_combprice='+prom_combprice+'&mainprom_combid='+mainprom_combid+'&mainprom_combprice='+mainprom_combprice+'&code_type='+code_type+'&'+qrystr);
		}
	}	
}
function delete_combination(delid)
{
	if(confirm('Are you sure you want to delete this combination?'))
	{
		var atleastone 			= 0;
		var code_id				= '<?php echo $code_id?>';
		var cat_orders			= '';
		var fpurpose			= '';
		var retdivid			= '';
		var moredivid			= '';
		retdivid   				= 'productcombo_div';
		fpurpose				= 'delete_productvarcombination';
		retobj 					= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
		retobj.innerHTML 		= '<center><img src="images/loading.gif" alt="Loading"></center>';															
		var qrystr				= '';
		/* Calling the ajax function */
		Handlewith_Ajax('services/promotional_code.php','fpurpose='+fpurpose+'&code_id='+code_id+'&delid='+delid);		
	}	
}
																  	       
function handle_tabs(id,mod)
	{
	<?PHP if ($ctype=='product' || $ctype=='freeproduct' || $ctype=='orddiscountpercent') { ?>
	tab_arr 								= new Array('main_tab_td','products_tab_td','order_tab_td','customer_tab_td');  
	<? } else { ?>
	tab_arr 								= new Array('main_tab_td','order_tab_td','customer_tab_td');  
	<? } ?>
	var atleastone 							= 0;
	var code_id								= '<?php echo $code_id?>';
	var ctype								= '<?php echo $ctype?>';
	var cat_orders							= '';
	var fpurpose							= '';
	var retdivid							= '';
	var moredivid							= '';
	var cname								= '<?php echo $_REQUEST['catgroupname']?>';
	var codenumber							= '<?php echo $_REQUEST['codenumber']?>';
	var sortby								= '<?php echo $_REQUEST['sort_by']?>';
	var sortorder							= '<?php echo $_REQUEST['sort_order']?>';
	var recs								= '<?php echo $_REQUEST['records_per_page']?>';
	var start								= '<?php echo $_REQUEST['start']?>';
	var pg									= '<?php echo $_REQUEST['pg']?>';
	var curtab								= '<?php echo $curtab?>';
	var code_number							= '<?php echo $code_number; ?>';
	var qrystr								= 'catgroupname='+cname+'&codenumber='+codenumber+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&code_id='+code_id+'&curtab='+curtab+'&code_number='+code_number;
	for(i=0;i<tab_arr.length;i++)
	{
		if(tab_arr[i]!=id)
		{
			obj = eval ("document.getElementById('"+tab_arr[i]+"')");
			obj.className = 'toptab';
		}
	}
	obj = eval ("document.getElementById('"+id+"')");
	obj.className = 'toptab_sel';
	
	switch(mod)
	{
		case 'promocode_info':
			fpurpose ='list_promo_maininfo';
			
		break;  
		case 'displayproducts_group': // Case of Categories in the group
			fpurpose	= 'list_prom_products';
			
		break;  
		case 'displayorder_group': // Case of Display Products in the group 
			fpurpose	= 'list_orders';
			
		break;
		case 'displaycustomer_group': // Case of Display Products in the group 
			fpurpose	= 'list_customers';
			
		break;
	}
	retobj 									= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
	//retobj								= document.getElementById('retdiv_id');
	retobj.innerHTML 						= '<center><img src="images/loading.gif" alt="Loading"></center>';
		
	/* Calling the ajax function */
	//alert('fpurpose='+fpurpose+'&cur_groupid='+group_id);
	
	Handlewith_Ajax('services/promotional_code.php','fpurpose='+fpurpose+'&code_id='+code_id+'&code_type='+ctype+'&'+qrystr);	
	
}

function track_details(id) {
	if(document.getElementById('det_'+id).style.display=='none') {
		document.getElementById('det_'+id).style.display = '';
	 } else {
	 	document.getElementById('det_'+id).style.display = 'none'
	 }	
	
}
function handle_more_combination(prodid)
{
	var stat,caption;
	obj1 = eval("document.getElementById('more_comb_hidden_"+prodid+"')");
	obj2 = eval("document.getElementById('add_more_tr_"+prodid+"')");
	obj3 = eval("document.getElementById('add_more_tr_more_"+prodid+"')");
	obj4 = eval("document.getElementById('add_more_div_"+prodid+"')");
	if(obj1)
	{
		if(obj1.value==0)
		{
			stat = '';
			obj1.value = 1;
			caption = 'Click here to hide <img src="images/down_arr.gif" border="0">';
		}	
		else
		{
			obj1.value = 0;
			stat = 'none';
			caption = 'Click here to Add More Combinations <img src="images/right_arr.gif" border="0">';
		}	
	}
	if(obj2)
	{
		obj2.style.display = stat;
	}
	if(obj3)
	{
		obj3.style.display = stat;
	}
	if(obj4)
	{
		obj4.innerHTML = caption;
	}
}
function call_activate_promotional()
{
	var code_id	= '<?php echo $code_id?>';
	var code_type			= '<?php echo $ctype ?>';
	var qrystr		= '';		
	var ch_ids 				= '';
	var ch_order			= '';
	var ch_dis 				= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';
	var orderbox			= '';
	var disbox				= '';
	var varid				= '';
	var varvalueid			= '';
	var varprodids			= '';
	var tempname;
	var fpurpose			= 'activate_code';
	if(confirm('If you have modified any details please save them using "Save Details" button before activating the Promotional Code, Otherwise the changes will be lost.\n\n Do you want to continue?'))
	{
		if(confirm('Are you sure you want to activate this Promotional Code?'))
		{ 
			var temparr,tempnames;
			var tempprod = tempvar = tempvarval = tempprodasn = '';
			retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/promotional_code.php','fpurpose='+fpurpose+'&code_id='+code_id+'&code_type='+code_type+'&'+qrystr);
		}
	}	
}
function call_deactivate_promotional()
{
	var code_id	= '<?php echo $code_id?>';
	var qrystr		= '';		
	var fpurpose			= 'deactivate_code';
	var code_type			= '<?php echo $ctype ?>';

	if(confirm('If you have modified any details please save them using "Save Details" button before deactivating the Promotional Code, otherwise the changes will be lost.\n\n Do you want to continue?'))
	{
		if(confirm('Are you sure you want to Deactivate this Promotional Code?'))
		{ 
	
			retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/promotional_code.php','fpurpose='+fpurpose+'&code_id='+code_id+'&code_type='+code_type+'&'+qrystr);
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
<form action="home.php?request=prom_code&codenumber=<?php echo $_REQUEST['codenumber']?>&start=<?php echo $_REQUEST['start']?>&pg=<?php echo $_REQUEST['pg']?>&records_per_page=<?php echo $_REQUEST['records_per_page']?>&sort_by=<?php echo $_REQUEST['sort_by']?>&sort_order=<?php echo $_REQUEST['sort_order']?>" method="post" name="frm_promo">
<table width="100%" border="0" cellspacing="0" cellpadding="0" height="100%">
<tr>
<td valign="top">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td align="left" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=prom_code&codenumber=<?php echo $_REQUEST['codenumber']?>&start=<?php echo $_REQUEST['start']?>&pg=<?php echo $_REQUEST['pg']?>&records_per_page=<?php echo $_REQUEST['records_per_page']?>&sort_by=<?php echo $_REQUEST['sort_by']?>&sort_order=<?php echo $_REQUEST['sort_order']?>">Promotional Code</a><span> Edit Promotional Code</span></div></td>
	</tr>
	<tr>
		  <td align="left" valign="middle" class="helpmsgtd_main">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
	<tr>
	  <td align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabmenu_x">
				<tr>  
						<td  align="left" onClick="handle_tabs('main_tab_td','promocode_info')" class="<?php if($curtab=='main_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="main_tab_td"><span>Main Info </span></td>
					<?PHP if ($ctype=='product' OR $ctype=='freeproduct' OR $ctype=='orddiscountpercent') { ?>
						<td  align="left" onClick="handle_tabs('products_tab_td','displayproducts_group')" class="<?php if($curtab=='products_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="products_tab_td"> <span>Products In this Promotional Code  </span></td> <? } ?>
						<td  align="left" class="<?php if($curtab=='order_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="order_tab_td" onClick="handle_tabs('order_tab_td','displayorder_group')"><span>Orders which have used this Promotional Code  </span></td>
						<td  align="left" class="<?php if($curtab=='customer_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="customer_tab_td" onClick="handle_tabs('customer_tab_td','displaycustomer_group')"><span>Registered Customers who used this Promotional Code </span></td>
						<td width="90%" align="left">&nbsp;</td>  
				</tr> 
		</table></td>
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
    <tr >
            <td ><div id='master_div'>
			<?php 
				if ($curtab=='main_tab_td')
				{
					show_promocode_maininfo($code_id,$alert);
				}
				elseif ($curtab=='products_tab_td')
				{
					show_prom_product_list($code_id,$alert,$ctype);
				}
				elseif ($curtab=='order_tab_td')
				{
					show_order_list($code_id,$alert);
				}
			?>	
		  </div></td>
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
</table>
	<input type="hidden" name="fpurpose" value="updatecode">
	<input type="hidden" name="codenumber" value="<?php echo $_REQUEST['codenumber']?>">
	<input type="hidden" name="sort_by" value="<?php echo $_REQUEST['sort_by']?>">
	<input type="hidden" name="sort_order" value="<?php echo $_REQUEST['sort_order']?>">
	<input type="hidden" name="start" value="<?php echo $_REQUEST['start']?>">
	<input type="hidden" name="pg" value="<?php echo $_REQUEST['pg']?>">
	<input type="hidden" name="checkbox[0]" value="<?php echo $code_id?>">
	<input type="hidden" name="code_id" value="<?php echo $code_id; ?>">
	
	<input type="hidden" name="records_per_page" value="<?php echo $_REQUEST['records_per_page']?>">
    <input type="hidden" name="retdiv_id" id="retdiv_id" value="master_div" />
</form>
<script type="text/javascript">
	handle_codetype('<?php echo $ctype?>');
</script>
