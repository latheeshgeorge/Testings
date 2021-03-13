<?php
	/*
	#################################################################
	# Script Name 		: edit_settings_default.php
	# Description 		: Page for managing the main shop settings
	# Coded by 			: Snl
	# Created on		: 14-Jun-2007
	# Modified by		: Sny
	# Modified On		: 25-Aug-2008
	#################################################################
	*/	
	
	$help_msg 			= get_help_messages('EDIT_MAINSHOP_MESS1');
	//# Retrieving the values of super admin from the table
	$sql 							= "SELECT * FROM general_settings_sites_common WHERE sites_site_id=".$ecom_siteid;
	$res_admin 				= $db->query($sql);
	$fetch_arr_admin 	= $db->fetch_array($res_admin);
	
	$sql 							= "SELECT * FROM general_settings_sites_common_onoff WHERE sites_site_id=".$ecom_siteid;
	$res_admin 				= $db->query($sql);
	$fetch_arr_admin_1 	= $db->fetch_array($res_admin);
	
	//section for inactive customers
	$sql_inactive = "SELECT id FROM general_settings_sites_mail_inactivecustomers WHERE sites_site_id=".$ecom_siteid." LIMIT 1";
	$ret_inactive = $db->query($sql_inactive);
	if($db->num_rows($ret_inactive)>0) 
	{
	  $row_inactive = $db->fetch_array($ret_inactive);
	  $email_id     =  $row_inactive['id'];
	} 
	// This is done to move the fields and its value from general_settings_sites_common_onoff table to the same array which have values from general_settings_sites_common table	
	foreach ($fetch_arr_admin_1 as $k=>$v)
	{
		$fetch_arr_admin[$k]=$v;	
	}
	//$fetch_arr_admin 	= $db->fetch_array($res_admin);
	if(!$curtab)
	$curtab		= ($_REQUEST['curtab'])?$_REQUEST['curtab']:'main_tab_td';

	$editor_elements = "voucher_buy_text,voucher_spend_text,email_content";
	include_once(ORG_DOCROOT."/console_v5/js/tinymce.php");
?>

<script type="text/javascript">
function handle_expansion(imgobj,mod)
{
	var src = imgobj.src;
	var retindx = src.search('plus.gif');
	switch(mod)
	{
		case 'product_bestseller':
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('product_bestsellertr_details'))
					document.getElementById('product_bestsellertr_details').style.display = '';
				if(document.getElementById('product_bestsellerunassign_div'))
					document.getElementById('product_bestsellerunassign_div').style.display = '';	
				call_ajax_showlistall('product_bestseller');	
				
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('product_bestsellertr_details'))
					document.getElementById('product_bestsellertr_details').style.display = 'none';
				
				if(document.getElementById('product_bestsellertrtr_norec'))
					document.getElementById('product_bestsellertrtr_norec').style.display = 'none';
				
				if(document.getElementById('product_bestsellerunassign_div'))
					document.getElementById('product_bestsellerunassign_div').style.display = 'none';	
				
			}	
		break;
		case 'prodtab': /* Case of product assigned*/
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('prod_tr'))
					document.getElementById('prod_tr').style.display = '';
				//if(document.getElementById('varunassign_div'))
				//	document.getElementById('varunassign_div').style.display = '';	
				call_ajax_showlistall('prodtab')
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('prod_tr'))
					document.getElementById('prod_tr').style.display = 'none';
				if(document.getElementById('produnassign_div'))
					document.getElementById('produnassign_div').style.display = 'none';
			}	
		break;
	 };

}
function call_ajax_showlistall(mod)
{  
	var atleastone 			= 0;
	var fpurpose			= '';
	var retdivid			= '';
	var moredivid			= '';
	var cur_id              = '<?php echo $email_id ?>';
	var qrystr										= '';
	switch(mod)
	{
		case 'product_bestseller': // Case of Products in the bestseller
			retdivid   	= 'productbestseller_div';
			fpurpose	= 'list_productbestseller';
			moredivid	= 'productbestsellerunassign_div';
			
		break;
		case 'prodtab': // Case of product tab
			retdivid   	= 'prodtab_div';
			moredivid	= 'produnassign_div';
			fpurpose	= 'list_prodtab';
			qrystr      = 'cur_id='+cur_id;
		break;
	};
    document.getElementById('retdiv_id').value 		= retdivid;/* Name of div to show the result */	
	document.getElementById('retdiv_more').value 	= moredivid;/* Name of div to show the result */	
	retobj 											= eval("document.getElementById('"+retdivid+"')");
	retobj.innerHTML 								= '<center><img src="images/loading.gif" alt="Loading"></center>';															
	
	/* Calling the ajax function */
	Handlewith_Ajax('services/general_settings.php','fpurpose='+fpurpose+'&'+qrystr);		
}		
function normal_assign_prodBestsellerAssign()
{
		window.location 			= 'home.php?request=general_settings&fpurpose=prodBestsellerAssign';
}
function call_ajax_changeorderall(mod,checkboxname)
{
	var atleastone 			= 0;
	var ch_ids 				= '';
	var ch_order			= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';
	var orderbox			= '';
	switch(mod)
	{
		case 'product_bestseller': // Case of Static pages in the group
			atleastmsg 	= 'Please select the Products to Change the Order';
			confirmmsg 	= 'Are you sure you want to Change the Order of selected Product(s)?';
			retdivid   	= 'productbestseller_div';
			moredivid	= 'productbestsellerunassign_div';
			fpurpose	= 'save_order';
			orderbox	= 'product_bestseller_order_';
		break;
		
	}
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmGeneralSettings.elements.length;i++)
	{
	if (document.frmGeneralSettings.elements[i].type =='checkbox' && document.frmGeneralSettings.elements[i].name== checkboxname)
		{
		

			if (document.frmGeneralSettings.elements[i].checked==true)
			{
				atleastone = 1;
				if (ch_ids!='')
					ch_ids += '~';
				 ch_ids += document.frmGeneralSettings.elements[i].value;
				
				 obj = eval("document.getElementById('"+orderbox+document.frmGeneralSettings.elements[i].value+"')");
				if (ch_order != '')
					ch_order += '~';
				 ch_order += obj.value; email
			}	
		}
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
			Handlewith_Ajax('services/general_settings.php','fpurpose='+fpurpose+'&ch_order='+ch_order+'&ch_ids='+ch_ids+'&'+qrystr);
		}	
	}	
}

function call_ajax_deleteall(mod,checkboxname)
{
	
	var atleastone 			= 0;
	var del_ids 			= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';
	var cur_id              = '<?php echo $email_id ?>';

	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmGeneralSettings.elements.length;i++)
	{
		if (document.frmGeneralSettings.elements[i].type =='checkbox' && document.frmGeneralSettings.elements[i].name==checkboxname)
		{

			if (document.frmGeneralSettings.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmGeneralSettings.elements[i].value;
			}	
		}
	}
	switch(mod)
	{
		case 'product_bestseller': // Case of Products in the bestseller
			atleastmsg 	= 'Please select the Product(s) to be deleted from the bestseller list';
			confirmmsg 	= 'Are you sure you want to delete the selected Product(s) from the bestseller list?';
			retdivid   	= 'productbestseller_div';
			moredivid	= 'productbestsellerunassign_div';
			fpurpose	= 'prodbestsellerUnAssign';
		break;
		case 'prods': 		   
			atleastmsg 	= 'Please select the product(s) to be unassigned';
			confirmmsg 	= 'Are you sure you want to to unassign the  selected product(s)?';
			retdivid   	= 'prodtab_div';
			moredivid	= 'produnassign_div';
			fpurpose	= 'unassign_prods';
			qrystr      = 'cur_id='+cur_id;
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
			Handlewith_Ajax('services/general_settings.php','fpurpose='+fpurpose+'&del_ids='+del_ids+'&'+qrystr);
		}	
	}	

}

function setimagesize_todefault(typ)
{
	var len = typ.length;
	var allDivs = document.getElementsByTagName('select'); /* get all select */
	for(i=0;i<allDivs.length; i++)
	{
	  var aDiv = allDivs[i];
	  var sID = aDiv.id;
	  if (sID.indexOf(typ)!=-1)
	  	aDiv.selectedIndex =0;
	}
}
function display_compare_count(){
	if(document.frmGeneralSettings.product_compare_enable.checked == true){
		document.getElementById('compare_count_id').style.display='';
	}else{
		document.getElementById('compare_count_id').style.display='none';
	}
}
function assign_sslImage(paymt_methods_sites_id){
	document.frmGeneralSettings.fpurpose.value='add_sslimg';
	document.frmGeneralSettings.payment_methods_forsites_id.value=paymt_methods_sites_id;
	document.frmGeneralSettings.submit();
}
function assign_default_sslImage(paymt_methods_sites_id){
	document.getElementById('assign_button_'+paymt_methods_sites_id).style.display='none';
	//if(confirm('Are you sure You want to change to default SSL image')){
	document.frmGeneralSettings.payment_methods_forsites_id.value=paymt_methods_sites_id;
	document.frmGeneralSettings.fpurpose.value='add_default_sslimg';
	//}
}
function checkall() 
{
	frm = document.frmGeneralSettings;
		frm.imageverification_news_req.checked  = true;
		frm.imageverification_vouch_req.checked = true;
		frm.imageverification_site_req.checked  = true;
		frm.imageverification_cust_req.checked  = true;
		frm.imageverification_prod_req.checked  = true;
}
function uncheckall() 
{
	frm = document.frmGeneralSettings;
	frm.imageverification_news_req.checked  = false;
	frm.imageverification_vouch_req.checked = false;
	frm.imageverification_site_req.checked  = false;
	frm.imageverification_cust_req.checked  = false;
	frm.imageverification_prod_req.checked  = false;
}
function product_decr_display() {
	frm = document.frmGeneralSettings;
	if(frm.product_maintainstock.checked == true) 
		document.getElementById('prd_decr').style.display='';
	else 	
		document.getElementById('prd_decr').style.display='none';
}
function valforms(frm)
{
	var atleastone = false;
	fieldRequired = Array();
	fieldDescription = Array();
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
			 var alertMsg =  "Please Enter ";
   	var l_Msg = alertMsg.length;
	var re = /[<,>,",',%,&,*,;,^,(,)]/i;
	var e = / /g;
	if (alertMsg.length == l_Msg)
   	{
		 /************ Special Chars Validation ************/
		
			var obj = frm.ban_ipaddress;
			if (obj)	{
				var re = /[a-z,A-Z,!,@,#,$,<,>,",',%,&,*,;,(,)]/i;	    
				var result = obj.value.search(re); // checks invalid characters 
				if( result != -1 )  	{
					result++;
					alert('Invalid Charater of Ipaddress'); 
					obj.focus();
					obj.select();
				   	return false;
				} 
			}	// END IF obj
			
		}
		show_processing();
		return true;
		}	
		else
		{
			return false;
		}
	
}
function handle_tabs(id,mod)
{ 
	/* commented for uploading product review only Aug2014 */
	tab_arr 									= new Array('main_tab_td','security_tab_td','administration_tab_td','inventory_tab_td'<?php /*,'email_tab_td' */?>,'review_tab_td','abandoned_tab_td');
	var fpurpose							= '';
	var retdivid								= '';
	var curtab								= '<?php echo $curtab?>';
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
		case 'designmain_info':
			/*fpurpose ='list_design_maininfo';*/
			document.frmGeneralSettings.fpurpose.value = 'list_design_maininfo';
			document.frmGeneralSettings.submit();
			return;
		break;
		case 'security': // Case of Categories in the group
			document.getElementById('retdiv_id').value ='master_div';
			fpurpose	= 'list_security';
		break;
		case 'administration': // Case of Display Products in the group
			 document.getElementById('retdiv_id').value ='master_div';
			fpurpose	= 'list_administration';
		break;
		case 'inventory': // Case of Display Categories in the group
		     document.getElementById('retdiv_id').value ='master_div';
			fpurpose	= 'list_inventory';
		break;
		case 'email': // Case of Display Categories in the group
			document.frmGeneralSettings.fpurpose.value = 'sent_email_inactive';
			document.frmGeneralSettings.curtab.value = 'email_tab_td';
			document.frmGeneralSettings.submit();
			return;
		break;
		/*Code for submit review starts here*/
		case 'review': // Case of Display Product Review Email Settings
			document.getElementById('retdiv_id').value ='master_div';
			fpurpose	= 'product_review_email';
		break;
		/*Code for submit review ends here*/
		case 'abandoned':
			document.getElementById('retdiv_id').value ='master_div';
			fpurpose	= 'abandoned_cart_email';
		break;
	}
	retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
	//retobj									= document.getElementById('retdiv_id');
	retobj.innerHTML 						= '<center><img src="images/loading.gif" alt="Loading"></center>';															
	
	/* Calling the ajax function */
	//alert('fpurpose='+fpurpose);
	Handlewith_Ajax('services/general_settings.php','fpurpose='+fpurpose);	
}
function save_settings(mod)
{
	if(mod=='security')
	{
		document.frmGeneralSettings.fpurpose.value = 'settings_security_update';
		document.frmGeneralSettings.submit();
	}
	else if(mod=='admin')
	{
		document.frmGeneralSettings.fpurpose.value = 'settings_administration_update';
		document.frmGeneralSettings.submit();
	}
	else if(mod=='inventory')
	{
		document.frmGeneralSettings.fpurpose.value = 'settings_inventory_update';
		document.frmGeneralSettings.submit();
	}
	else if(mod=='email')
	{
		document.frmGeneralSettings.fpurpose.value = 'settings_inactiveemail_update';
		document.frmGeneralSettings.submit();
	}
	/* Code for submit review starts here */
	else if(mod=='review')
	{
		document.frmGeneralSettings.fpurpose.value = 'settings_productreview_update';
		document.frmGeneralSettings.submit();
	}
	else if(mod=='abandoned')
	{
		document.frmGeneralSettings.fpurpose.value = 'settings_abandonedcart_update';
		document.frmGeneralSettings.submit();
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
			targetobj 	= eval("document.getElementById('"+targetdiv+"')");
			targetobj.innerHTML = ret_val; /* Setting the output to required div */
		}
		else
		{
			show_request_alert(req.status);
			//alert("Problem in requesting XML :"+req.statusText);
		}
	}	
}
function val_checkbox()
{
	if(document.getElementById('sent_email_not_logged').checked==true || document.getElementById('sent_email_not_purchase').checked==true){
		document.getElementById('sent_email_cust').checked=true;
	}else{
		document.getElementById('sent_email_cust').checked=false;
	}
}
function normal_assign_ProductAssign(editid)
{
		window.location 			= 'home.php?request=general_settings&fpurpose=productAssign&pass_email_id='+editid;
}
function preview_settings()
{       
	var cur_id              = '<?php echo $email_id ?>';
	document.frmGeneralSettings.fpurpose.value = 'showreview';

	document.frmGeneralSettings.email_id.value = cur_id;
	document.frmGeneralSettings.nextdo.value = 'preview';
	document.frmGeneralSettings.submit();	 
			//return true;
		//window.location 			= 'home.php?request=general_settings&fpurpose=showreview&email_id='+cur_id;
}
function call_ajax_savedetails(mod,checkboxname)
{
	var atleastone 			= 0;
	var editid				= '<?php echo $edit_id?>';
	var ch_ids 				= '';
	var ch_variable			= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
   var cur_id              = '<?php echo $email_id ?>';

	//var retdivid			= 'catimg_div';
	//var moredivid			= 'catimgunassign_div';
	//var fpurpose			= 'save_catimagedetails';
	var ch_order			= '';	
	switch(mod)
	{
		case 'prods':
		  /* check whether any checkbox is ticked */
		           for(i=0;i<document.frmGeneralSettings.elements.length;i++)
					{
					if (document.frmGeneralSettings.elements[i].type =='checkbox' && document.frmGeneralSettings.elements[i].name== checkboxname)
						{

							if (document.frmGeneralSettings.elements[i].checked==true)
								{
									atleastone = 1;
									if (ch_ids!='')
									ch_ids += '~';
					 				ch_ids += document.frmGeneralSettings.elements[i].value;
				 
					 				obj1 = eval("document.getElementById('prod_order_"+document.frmGeneralSettings.elements[i].value+"')");
				 
									if (ch_order != '')
									 ch_order += '~';
									 ch_order += obj1.value; 
							}	
						}
			}
		  // Case of saving the order
			atleastmsg 		= 'Please select the product(s) to be saved';
			confirmmsg 	= 'Are you sure you want to save the  order of selected product(s)?';
			retdivid   		= 'prod_div';
			moredivid		= 'produnassign_div';
			fpurpose		= 'save_proddetails';
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
			retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
		    Handlewith_Ajax('services/general_settings.php','fpurpose='+fpurpose+'&cur_id='+cur_id+'&ch_order='+ch_order+'&ch_ids='+ch_ids);	

		}	
	}
}
function save_settings_email(mod,nextdo)
{
	var frm = document.frmGeneralSettings;
	if(mod=='email')
	{
		var atleastone = false;
		fieldRequired = Array('email_interval','next_email_sent','sent_email_cust','email_subject');
		fieldDescription = Array('Email interval','Next Email date','Customer Type','Email subject');
		fieldEmail = Array();
		fieldConfirm = Array();
		fieldConfirmDesc  = Array();
		fieldNumeric = Array();
		if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
			document.frmGeneralSettings.fpurpose.value = 'settings_inactiveemail_update';
			if(nextdo=='save')
			{
				document.frmGeneralSettings.nextdo.value = 'save';
			}
			else if(nextdo='savec')
			{
				var cur_id              = '<?php echo $email_id ?>';
				
				document.frmGeneralSettings.email_id.value = cur_id;
				document.frmGeneralSettings.nextdo.value = 'savec';
			}
			document.frmGeneralSettings.submit();	 
			return true;
			}	
			else
			{
				return false;
			}		
	}
}
/* Code for submit review starts here */
function showVoucherData()
{
	if(document.getElementById("review_giftvoucher_sent").checked == true)
	{
		document.getElementById("voucherdata").style.display = 'block';
	}
	else
	{
		document.getElementById("voucherdata").style.display = 'none';
	}
}
/* Code for submit review ends here */
</script>
<form name="frmGeneralSettings" method="post" action="home.php?request=general_settings&fpurpose=settings_default_update" onsubmit="return valforms(this);">
<table border="0" cellpadding="0" cellspacing="0" width="100%" >
<tr>
<td valign="top" class="contentarea">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="6" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=general_settings">General Settings</a> <span> Main Shop Settings </span></div></td>
        </tr>
       <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="6">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
		<tr>
			<td colspan="6" align="left">
				<table width="100%" border="0" cellspacing="1" cellpadding="1">
				<tr>
						<td width="4%"  align="left" class="<?php if($curtab=='main_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="main_tab_td" onClick="handle_tabs('main_tab_td','designmain_info')"> <span>Design & Layout</span></td>
						<td width="4%"  align="left" class="<?php if($curtab=='security_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="security_tab_td" onClick="handle_tabs('security_tab_td','security')"><span>Security</span></td>
						<td width="8%"  align="left" class="<?php if($curtab=='administration_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="administration_tab_td" onClick="handle_tabs('administration_tab_td','administration')"><span>Administration Area</span></td>
						<td width="7%"  align="left" class="<?php if($curtab=='inventory_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="inventory_tab_td" onClick="handle_tabs('inventory_tab_td','inventory')"> <span>Inventory Management</span></td>
						<?php
						/*
						<td width="5%"  align="left" class="<?php if($curtab=='email_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="email_tab_td" onClick="handle_tabs('email_tab_td','email')"> <span>Emails to Inactive Customer</span></td>
						*/?> 

						<td width="7%" align="left"class="<?php if($curtab=='review_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="review_tab_td" onClick="handle_tabs('review_tab_td','review')"> <span>Product Review Email</span></td>
						<td width="7%" align="left"class="<?php if($curtab=='abandoned_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="abandoned_tab_td" onClick="handle_tabs('abandoned_tab_td','abandoned')"> <span>Abandoned Cart Email</span></td>
				        <td width="65%" align="left">&nbsp;</td>
				</tr>
				</table>				
			</td>
		</tr>
		 <tr>
          <td colspan="6">
		  <div id='master_div'>
			<?php 
			if ($curtab=='main_tab_td')
			{
				show_design_maininfo($alert);
			}
			elseif ($curtab=='security_tab_td')
			{
				show_security_list($alert);
			}
			elseif ($curtab=='administration_tab_td')
			{
				show_administration_list($alert);
			}
			elseif ($curtab=='inventory_tab_td')
			{
				show_inventory_list($alert);
			}
			/* commented for uploading product review only Aug2014*/
			/*
			elseif ($curtab=='email_tab_td')
			{
				show_email_inactive($alert);
			}/* Code for submit review starts here */
			elseif ($curtab=='review_tab_td')
			{
				show_product_review($alert);
			}/* Code for submit review ends here */		
			elseif ($curtab=='abandoned_tab_td')
			{
				show_abandoned_cart_email($alert);
			}
			?>		
		  </div>
		  </td>
		  </tr> 	 
         <tr>
          <td colspan="6" align="center" valign="middle" class="tdcolorgray">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="6" align="right" valign="middle" class="tdcolorgray">&nbsp;</td>
        </tr>
        <tr>
         <td colspan="6" align="center" valign="middle" class="tdcolorgray"></td>
        </tr>
        <tr>
          <td colspan="6" align="center" valign="middle" class="tdcolorgray">&nbsp;</td>
        </tr>
      </table>
</td>
</tr>

</table>

<input type="hidden" name="retdiv_id" id="retdiv_id" value="master_div" />
<input type="hidden" name="src_page" id="src_page" value="mainshop" />
<input type="hidden" name="fpurpose" id="fpurpose" value="settings_default_update_settings" />
<input type="hidden" name="curtab" id="curtad" value="" />
<input type="hidden" name="retdiv_more" id="retdiv_more" value="" />
		<input type="hidden" name="src_id" id="src_id" value="" />
</form>
