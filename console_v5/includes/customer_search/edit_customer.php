<?php
	/*#################################################################
	# Script Name 	: edit_customer.php
	# Description 		: Page for editing Customer
	# Coded by 		: SKR
	# Created on		: 14-Aug-2007
	# Modified by		: Sny
	# Modified On		: 07-Aug-2008
	#################################################################*/
#Define constants for this page
if(($_REQUEST['customer_id'] && !is_numeric($_REQUEST['customer_id']) )|| ($_REQUEST['checkbox'][0] && !is_numeric($_REQUEST['checkbox'][0]))){
	redirect_illegal();
	exit;
}
$page_type 		= 'Customer';
$help_msg 		= get_help_messages('EDIT_CUST_MESS1');
$customer_id	= ($_REQUEST['customer_id']?$_REQUEST['customer_id']:$_REQUEST['checkbox'][0]);
$sql_cust		= "SELECT customer_fname,customer_mname,customer_surname,customer_title,customer_payonaccount_usedlimit 
							FROM customers  
									WHERE customer_id=".$customer_id." 
									AND sites_site_id = $ecom_siteid  
										LIMIT 1";
$res_cust		= $db->query($sql_cust);

if ($db->num_rows($res_cust)==0)
	exit;
$row_cust 		= $db->fetch_array($res_cust);
$cust_name 		= stripslashes($row_cust['customer_title'])." ".stripslashes($row_cust['customer_fname']).' '.stripslashes($row_cust['customer_mname']).' '.stripslashes($row_cust['customer_surname']);
$curtab			= ($_REQUEST['curtab'])?$_REQUEST['curtab']:'main_tab_td';

$sql_customer_sel	= "SELECT customer_statecounty,country_id FROM customers  WHERE customer_id=".$customer_id." LIMIT 1";
$res_customer_sel	= $db->query($sql_customer_sel);
$row_customer_sel 	= $db->fetch_array($res_customer_sel);

// #######################################################################################################
// Start ## Section to builds the dynamic fields to be placed in the javascript validation
// #######################################################################################################
$field_str = '';
$field_msg = '';
?>	
<script language="javascript" type="text/javascript">

//ANU 
// function to validate the dynamic form feilds in the top with in the static section
function validate_TopInStaticregistration(frm){
	//alert(feildmsg);
	fieldRequired 		= Array(<?=$TopInStaticstr[0]?>);
	fieldDescription 	= Array(<?=$TopInStaticmsg[0]?>);
	fieldEmail 			= Array();
	fieldConfirm 		= Array();
	fieldConfirmDesc  	= Array();
	fieldNumeric 		= Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric))  {
	<?php
	// Logic to build the dynamic field validation for check boxes
	if(count($checkboxfld_arrTopInStatic)){
		foreach ($checkboxfld_arrTopInStatic as $k=>$v) {
			echo  "var retval_$k = new Array(); ";
				//echo "alert(frm.elements.length);";
			echo "for(var i=0; i < frm.elements.length; i++) {;
						var el = frm.elements[i];";
					//echo "alert(el);";
					echo " if(el.type == 'checkbox' && el.name == '$k"."[]'"." && el.checked) {
					 	retval_$k.push(el.value);
					 };";
				 echo "}";
				 //echo "alert(retval_$k.length);";
				 echo "if(!retval_$k.length){";
				 echo "alert('".$v."');";
				 echo "return false;
					   }";
		
					//echo "return true;";
		 }
	}
	// case of radio button
	if(count($radiofld_arrTopInStatic)){
		foreach ($radiofld_arrTopInStatic as $k=>$v) {
			echo "checkvalue='';";
			//	echo "alert(document.frm_registration.$k.length);";
			echo "for (i=0, n=frm.$k.length; i<n; i++) {
					 if (frm.$k"."[i]".".checked) {
   		 	 		 	var checkvalue = frm.$k"."[i]".".value;
    		 		 	break;
					 }
				 }";
				 echo "if(checkvalue==''){";
				 echo "alert('".$v."');";
				 echo "return false;
					   }";
					//echo "return true;";
		 }
	}
		?>	
	return true;
	}
	else
	{
		return false;
	}

}

// function to validate the dynamic form feilds in the Bottom with in the static section
function validate_BottomInStaticregistration(frm){
	//alert(feildmsg);
	fieldRequired 		= Array(<?=$BottomInStaticstr[0]?>);
	fieldDescription 	= Array(<?=$BottomInStaticmsg[0]?>);
	fieldEmail 			= Array();
	fieldConfirm 		= Array();
	fieldConfirmDesc  	= Array();
	fieldNumeric 		= Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric))  {
	<?php
	// Logic to build the dynamic field validation for check boxes
	if(count($checkboxfld_arrBottomInStatic)){
		foreach ($checkboxfld_arrBottomInStatic as $k=>$v) {
			echo  "var retval_$k = new Array(); ";
				//echo "alert(frm.elements.length);";
			echo "for(var i=0; i < frm.elements.length; i++) {;
						var el = frm.elements[i];";
					//echo "alert(el);";
					echo " if(el.type == 'checkbox' && el.name == '$k"."[]'"." && el.checked) {
					 	retval_$k.push(el.value);
					 };";
				 echo "}";
				 //echo "alert(retval_$k.length);";
				 echo "if(!retval_$k.length){";
				 echo "alert('".$v."');";
				 echo "return false;
					   }";
		
					//echo "return true;";
		 }
	}
	// case of radio button
	if(count($radiofld_arrBottomInStatic)){
		foreach ($radiofld_arrBottomInStatic as $k=>$v) {
			echo "checkvalue='';";
			//	echo "alert(document.frm_registration.$k.length);";
			echo "for (i=0, n=frm.$k.length; i<n; i++) {
					 if (frm.$k"."[i]".".checked) {
   		 	 		 	var checkvalue = frm.$k"."[i]".".value;
    		 		 	break;
					 }
				 }";
				 echo "if(checkvalue==''){";
				 echo "alert('".$v."');";
				 echo "return false;
					   }";
					//echo "return true;";
		 }
	}
		?>	
	return true;
	}
	else
	{
		return false;
	}

}


/* Function to validate the Customer Registration */
function validate_defaultregistration(frm)
{   
	<?php if($ecom_siteid==76)
	{
	?>
	fieldRequired 		= Array('customer_title','customer_fname','customer_phone');
	fieldDescription 	= Array('Customer Title','Customer Name','Customer Phone');
    <?php 
    }
    else
    {
    ?>
    fieldRequired 		= Array('customer_title','customer_fname','customer_postcode','customer_phone');
	fieldDescription 	= Array('Customer Title','Customer Name','Customer Postcode','Customer Phone');
    <?php
	}
    ?>
	fieldEmail 			= Array('customer_email');
	fieldConfirm 		= Array('customer_pwd','customer_pwd_cnf');
	fieldConfirmDesc  	= Array('Password','Confirm Password');
	fieldNumeric 		= Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)){
				var used_credit = <?=$row_cust['customer_payonaccount_usedlimit']?>;

		<?php /*?>if(frm.country_id.value!=0)
		{
			var state_ids=frm.customer_statecounty.value;
				if(state_ids==-1)
				{
					 if(frm.other_state.value=='')
					 {
						alert("Enter Other state name");
						frm.other_state.focus();
						 return false;
					 }
				}
		}<?php */?>		
	  if(parseFloat(frm.customer_payonaccount_maxlimit.value)<parseFloat(used_credit)) {
			alert("Maximum credit limit should be greater than used credit");
			return false;
		}
	    if(parseInt(frm.customer_affiliate_commission.value)>=100 || parseInt(frm.customer_affiliate_commission.value) < 0) {
			alert("Customer Affiliate Commission Rate Should Be Positive and below 100% ");
			frm.customer_affiliate_commission.focus();
			return false;
		} 
		 if(parseInt(frm.customer_discount.value)>=100 || parseInt(frm.customer_discount.value)<0) {
			alert("Customer discount Should Be Positive and Be below 100% ");
			frm.customer_discount.focus();
			return false;
		} 
			
		show_processing();
		return true;
	}
	else
	{
		return false;
	}
}

/* Function to validate the Customer Registration */
function validate_Topregistration(frm)
{
	//alert(feildmsg);
	fieldRequired 		= Array(<?=$topstr[0]?>);
	fieldDescription 	= Array(<?=$topmsg[0]?>);
	fieldEmail 			= Array();
	fieldConfirm 		= Array();
	fieldConfirmDesc  	= Array();
	fieldNumeric 		= Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric))  {
	<?php
	// Logic to build the dynamic field validation for check boxes
	if(count($checkboxfld_arrTop)){
		foreach ($checkboxfld_arrTop as $k=>$v) {
			echo  "var retval_$k = new Array(); ";
				//echo "alert(frm.elements.length);";
			echo "for(var i=0; i < frm.elements.length; i++) {;
						var el = frm.elements[i];";
					//echo "alert(el);";
					echo " if(el.type == 'checkbox' && el.name == '$k"."[]'"." && el.checked) {
					 	retval_$k.push(el.value);
					 };";
				 echo "}";
				 //echo "alert(retval_$k.length);";
				 echo "if(!retval_$k.length){";
				 echo "alert('".$v."');";
				 echo "return false;
					   }";
		
					//echo "return true;";
		 }
	}
	// case of radio button
	if(count($radiofld_arrTop)){
		foreach ($radiofld_arrTop as $k=>$v) {
			echo "checkvalue='';";
			//	echo "alert(document.frm_registration.$k.length);";
			echo "for (i=0, n=frm.$k.length; i<n; i++) {
					 if (frm.$k"."[i]".".checked) {
   		 	 		 	var checkvalue = frm.$k"."[i]".".value;
    		 		 	break;
					 }
				 }";
				 echo "if(checkvalue==''){";
				 echo "alert('".$v."');";
				 echo "return false;
					   }";
					//echo "return true;";
		 }
	}
		?>	
	return true;
	}
	else
	{
		return false;
	}
}

/* Function to validate the Customer Registration */
function validate_Bottomregistration(frm)
{
	
	//alert(feildmsg);
	fieldRequired 		= Array(<?=$bottomstr[0]?>);
	fieldDescription 	= Array(<?=$bottommsg[0]?>);
	fieldEmail 			= Array();
	fieldConfirm 		= Array();
	fieldConfirmDesc  	= Array();
	fieldNumeric 		= Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric))
	{
	<?php
		
	if(count($checkboxfld_arrBottom)){
		foreach ($checkboxfld_arrBottom as $k=>$v) {
			echo  "var retval_$k = new Array(); ";
			//echo "alert(frm.elements.length);";
			echo "for(var i=0; i < frm.elements.length; i++) {
						var el = frm.elements[i];";
					//echo "alert(el);";
					echo " if(el.type == 'checkbox' && el.name == '$k"."[]'"." && el.checked) {
					 	retval_$k.push(el.value);
					 };";
				 echo "}";
				 //echo "alert(retval_$k.length);";
				 echo "if(!retval_$k.length){";
				 echo "alert('".$v."');";
				 echo "return false;
					   }";
		 }
	}
	// case of radio button
	if(count($radiofld_arrBottom)){
		foreach ($radiofld_arrBottom as $k=>$v) {
			echo "checkvalue='';";
		//	echo "alert(document.frm_registration.$k.length);";
			echo "for (i=0, n=document.frmEditCustomer.$k.length; i<n; i++) {
  					 if (document.frmEditCustomer.$k"."[i]".".checked) {
   				  	 	 var checkvalue = document.frmEditCustomer.$k"."[i]".".value;
    				 	 break;
						 }
					 }";
					 echo "if(checkvalue==''){";
					 echo "alert('".$v."');";
					 echo "return false;
				 }";
							 //echo "return true;";
		 }
	}
		?>	
	return true;
	}
	else
	{
		return false;
	}
}


/*function validate_allforms(form){
	topfrm =  validate_Topregistration(form);
	if(topfrm){
		defalutfrm = validate_defaultregistration(form);
		if(defalutfrm){
			bottomfrm =  validate_Bottomregistration(form);
			
		return bottomfrm;
		}else{
			return false;
		}
	}else{
		return topfrm;
	}
}*/
function validate_allforms(form){
	topfrm =  validate_Topregistration(form);
	if(topfrm){
		TopInStaticfrm = validate_TopInStaticregistration(form);
		if(TopInStaticfrm) {
			defalutfrm = validate_defaultregistration(form);
			if(defalutfrm){
				BottomInStaticfrm = validate_BottomInStaticregistration(form);
				if(BottomInStaticfrm){
					bottomfrm =  validate_Bottomregistration(form);
					return bottomfrm;
				}else{
				return BottomInStaticfrm;
				}
			}else{
				return defalutfrm;
			}
		}else{
			return TopInStaticfrm;
		}
	}else{
		return topfrm;
	}
}

/*ANU*/
function changestate(country_ids,state_id)
{ 
	var retdivid='state_tr';
	var fpurpose;

	if(country_ids==0)
	{
		country_ids=document.frmEditCustomer.country_id.value;
	}
	if(country_ids!="")
	{
		document.getElementById('state_tr').style.display='';
		fpurpose	= 'list_state';
		document.getElementById('retdiv_id').value 		= retdivid;/* Name of div to show the result */
		retobj 											= eval("document.getElementById('"+retdivid+"')");
		retobj.innerHTML 								= '<center><img src="images/loading.gif" alt="Loading"></center>';															
		var qrystr										= '';
		document.getElementById('state_call').value=1;
		/* Calling the ajax function */
		Handlewith_Ajax('services/customer_search.php','fpurpose='+fpurpose+'&'+qrystr+'&country_id='+country_ids+'&state_id='+state_id);
	}
	else
	{
		
		document.getElementById('state_tr').style.display='none';
	}
}
//If other state
function state_other()
{
	var stte_ids=document.frmEditCustomer.customer_statecounty.value;
	if(stte_ids==-1)
	{
		document.getElementById('state_other_tr').style.display='';
	}
	else
	{
		document.getElementById('state_other_tr').style.display='none';

	}
	
}
function showreason_text(status)
{
  if(status=='REJECTED')
	{
		document.getElementById('rejectreason_id').style.display='';
	}
	else
	{
		document.getElementById('rejectreason_id').style.display='none';

	}
}
function handle_tabs(id,mod)
{
	tab_arr 								= new Array('main_tab_td','newsgroup_tab_td','category_tab_td','products_tab_td','order_tab_td');
	var atleastone 							= 0;
	var cust_id								= '<?php echo $customer_id?>';
	var fpurpose							= '';
	var retdivid							= '';
	var search_email						='<?php echo $_REQUEST['pass_search_email']?>';
	var search_name							='<?php echo $_REQUEST['pass_search_name']?>';
	var search_compname						='<?php echo $_REQUEST['pass_search_compname']?>';
	var corp_id								='<?php echo $_REQUEST['corporation_id']?>';
    var pay_acc								='<?php echo $_REQUEST['customer_payonaccount_status']?>';
	var dept_id								='<?php echo $_REQUEST['cbo_dept']?>';
	var sortby								= '<?php echo $_REQUEST['pass_sort_by']?>';
	var sortorder							= '<?php echo $_REQUEST['pass_sort_order']?>';
	var recs								= '<?php echo $_REQUEST['pass_records_per_page']?>';
	var start								= '<?php echo $_REQUEST['pass_start']?>';
	var pg									= '<?php echo $_REQUEST['pass_pg']?>';
	var curtab								= '<?php echo $curtab?>';
	var qrystr				= 'search_name='+search_name+'&search_compname='+search_compname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&search_email='+search_email+'&corporation_id='+corp_id+'&customer_payonaccount_status='+pay_acc+'&cbo_dept='+dept_id+'&curtab='+curtab;
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
		case 'customermain_info':
			fpurpose 	= 'list_customer_maininfo';
		break;
		case 'newsgroup': // Case of showing subcategories
			fpurpose	= 'list_newsgroup';
		break;
		case 'fav_category': // Case of showing category image section
			fpurpose	= 'list_categories_ajax';
		break;
		case 'fav_products':
			fpurpose	= 'list_products_ajax';
		break;
		case 'order_history':
			fpurpose	= 'list_orders_ajax';
		break;
	}
	retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
	retobj.innerHTML 						= '<center><img src="images/loading.gif" alt="Loading"></center>';															
	
	/* Calling the ajax function */
	Handlewith_Ajax('services/customer_search.php','fpurpose='+fpurpose+'&customer_id='+cust_id+'&'+qrystr);	
}
function call_ajax_savenewsletter_group(mod)
{
	var cust_id								= '<?php echo $customer_id?>';
	var cat_orders						= '';
	var fpurpose							= '';
	var retdivid								= '';
	var search_email						='<?php echo $_REQUEST['search_email']?>';
	var search_name							='<?php echo $_REQUEST['search_name']?>';
	var search_compname						='<?php echo $_REQUEST['search_compname']?>';
	var corp_id								='<?php echo $_REQUEST['corporation_id']?>';
    var pay_acc								='<?php echo $_REQUEST['customer_payonaccount_status']?>';
	var dept_id								='<?php echo $_REQUEST['cbo_dept']?>';
	var sortby								= '<?php echo $_REQUEST['sort_by']?>';
	var sortorder							= '<?php echo $_REQUEST['sort_order']?>';
	var recs									= '<?php echo $_REQUEST['records_per_page']?>';
	var start								= '<?php echo $_REQUEST['start']?>';
	var pg									= '<?php echo $_REQUEST['pg']?>';
	var curtab								= '<?php echo $curtab?>';
	var customer_ids						= '';
	var customer_in_mailing_list			= 0;
	if(document.getElementById('customer_in_mailing_list').checked==true)
		customer_in_mailing_list = 1;
	var qrystr				= 'search_name='+search_name+'&search_compname='+search_compname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&search_email='+search_email+'&corporation_id='+corp_id+'&customer_payonaccount_status='+pay_acc+'&cbo_dept='+dept_id+'&curtab='+curtab+'&customer_in_mailing_list='+customer_in_mailing_list;

	switch(mod)
	{
			case 'newsgroup':
			for(i=0;i<document.frmEditCustomer.elements.length;i++)
			{
				if (document.frmEditCustomer.elements[i].type =='checkbox' && document.frmEditCustomer.elements[i].name=='chk_group[]')
				{
					
					if (document.frmEditCustomer.elements[i].checked==true)
					{
						atleastone = 1;
						if (customer_ids!='')
						customer_ids += '~';
						customer_ids += document.frmEditCustomer.elements[i].value;
						
					}	
				}
			}
				fpurpose	= 'save_news_group';
			break;
	};
	
		//document.getElementById('retdiv_id').value 	= retdivid;/* Name of div to show the result */
		retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
		retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
		Handlewith_Ajax('services/customer_search.php','fpurpose='+fpurpose+'&customer_id='+cust_id+'&'+qrystr+'&customer_ids='+customer_ids);	
}
function maillinglist_onchange(obj)
{
	if(obj.checked==false)
	{
		for(i=0;i<document.frmEditCustomer.elements.length;i++)
		{
			if (document.frmEditCustomer.elements[i].type =='checkbox' && document.frmEditCustomer.elements[i].name.substr(0,9)=='chk_group')
			{ 
				document.frmEditCustomer.elements[i].checked = false;
			}
		}	
	}
}
function mailinglist_mainsel()
{
	var atleast_one = false;
	for(i=0;i<document.frmEditCustomer.elements.length;i++)
	{
		if (document.frmEditCustomer.elements[i].type =='checkbox' && document.frmEditCustomer.elements[i].name.substr(0,9)=='chk_group')
		{ 
			if(document.frmEditCustomer.elements[i].checked==true)
				atleast_one = true;
		}
	}	
	if(atleast_one)
		document.frmEditCustomer.customer_in_mailing_list.checked = true;
	/*else
		document.frmEditCustomer.customer_in_mailing_list.checked = false;*/
}	
function ajax_return_contents() 
{
	var ret_val='';
	var disp = 'no';
	if(req.readyState==4)
	{
		if(req.status==200)
		{
			ret_val 	= req.responseText;
			targetdiv 	= document.getElementById('retdiv_id').value;
			//norecdiv 	= document.getElementById('retdiv_more').value;
			
			targetobj 	= eval("document.getElementById('"+targetdiv+"')");
			targetobj.innerHTML = ret_val; /* Setting the output to required div */
			document.getElementById('retdiv_id').value= 'master_div';
		}	
	}	
}
function assign_submit() 
{
document.frmEditCustomer.fpurpose.value='list_assign_products';
document.frmEditCustomer.submit();
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
		Handlewith_Ajax('services/customer_search.php','fpurpose='+fpurpose+'&chstat='+chstat+'&customer_id='+customer_id+'&ch_ids='+ch_ids);
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
	var search_email						='<?php echo $_REQUEST['search_email']?>';
	var search_name							='<?php echo $_REQUEST['search_name']?>';
	var search_compname						='<?php echo $_REQUEST['search_compname']?>';
	var corp_id								='<?php echo $_REQUEST['corporation_id']?>';
    var pay_acc								='<?php echo $_REQUEST['customer_payonaccount_status']?>';
	var dept_id								='<?php echo $_REQUEST['cbo_dept']?>';
	var sortby								= '<?php echo $_REQUEST['sort_by']?>';
	var sortorder							= '<?php echo $_REQUEST['sort_order']?>';
	var recs									= '<?php echo $_REQUEST['records_per_page']?>';
	var start								= '<?php echo $_REQUEST['start']?>';
	var pg									= '<?php echo $_REQUEST['pg']?>';
	var curtab								= '<?php echo $curtab?>';
	var qrystr				= 'search_name='+search_name+'&search_compname='+search_compname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&search_email='+search_email+'&corporation_id='+corp_id+'&customer_payonaccount_status='+pay_acc+'&cbo_dept='+dept_id+'&curtab='+curtab;

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
			confirmmsg 	= 'Are you sure you want to unassign the selected Product(s)?'
			fpurpose	= 'delete_product_ajax';
		break;
		case 'category': // Case of product tabs
			atleastmsg 	= 'Please select the category(s) to be deleted';
			confirmmsg 	= 'Are you sure you want to unassign the selected Category(s)?'
			fpurpose	= 'delete_category_ajax';
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
			retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
			retobj.innerHTML 								= '<center><img src="images/loading.gif" alt="Loading"></center>';
			
			Handlewith_Ajax('services/customer_search.php','fpurpose='+fpurpose+'&customer_id='+customer_id+'&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}	
</script>
<form name='frmEditCustomer' action='home.php?request=customer_search' method="post" onsubmit="return validate_allforms(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=customer_search&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&search_name=<?=$_REQUEST['pass_search_name']?>&search_compname=<?php echo $_REQUEST['pass_search_compname']?>&search_email=<?=$_REQUEST['pass_search_email']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&corporation_id=<?=$_REQUEST['corporation_id']?>&customer_payonaccount_status=<?=$_REQUEST['customer_payonaccount_status']?>&cbo_dept=<?=$_REQUEST['cbo_dept']?>">List Customers</a><span> Edit Customer <? echo "'".$cust_name."'";?></span></div></td>
        </tr>
       <tr>
		  <td colspan="2" align="left" valign="middle" class="helpmsgtd_main">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
		<tr>
			<td colspan="2" align="left">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabmenu_x">
				<tr>
						<td align="left" onClick="handle_tabs('main_tab_td','customermain_info')" class="<?php if($curtab=='main_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="main_tab_td"><span>Main Info</span></td>
						<td align="left" onClick="handle_tabs('newsgroup_tab_td','newsgroup')" class="<?php if($curtab=='newsgroup_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="newsgroup_tab_td"><span>Newsletter Group for this Customer</span></td>
						<td align="left" onClick="handle_tabs('category_tab_td','fav_category')" class="<?php if($curtab=='category_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="category_tab_td"><span>Favourite Categories</span></td>
						<td align="left" onClick="handle_tabs('products_tab_td','fav_products')" class="<?php if($curtab=='products_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="products_tab_td"><span>Favourite Products</span></td>
						<td  align="left" onClick="handle_tabs('order_tab_td','order_history')" class="<?php if($curtab=='order_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="order_tab_td"><span>Order History</span></td>
						<td width="90%" align="left">&nbsp;</td>
				</tr>
				</table>			</td>
		</tr>
		 <tr>
	<td colspan="4">
	<div id='master_div'>
	<?php
	if ($curtab=='main_tab_td')
		{
			show_customermaininfo($customer_id,$alert,'edit');
		}
		if ($curtab=='newsgroup_tab_td')
		{
			show_newsletter_group_list($customer_id,$alert);
		}
		elseif ($curtab=='category_tab_td')
		{
			show_favcategory_list($customer_id,$alert);
		}
		elseif ($curtab=='products_tab_td')
		{
			show_product_list($customer_id,$alert);
		}
		elseif ($curtab=='order_tab_td')
		{
			show_shop_settings($customer_id,$alert);
		}
	?>
	</div>	</td>
	</tr>
		  	<tr><td colspan="2" align="center">&nbsp;</td></tr>
		  	<tr><td colspan="2" align="center">&nbsp;</td></tr>
		    <tr>
		      <td colspan="2" align="center">&nbsp;</td>
    </tr>
		<tr>
          <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td align="left" valign="middle" class="tdcolorgray">
		  
		  <input type="hidden" name="customer_id" id="customer_id" value="<?=$customer_id?>" />
		  <input type="hidden" name="pass_search_name" id="pass_search_name" value="<?=$_REQUEST['pass_search_name']?>" />
		  <input type="hidden" name="pass_search_compname" id="pass_search_compname" value="<?=$_REQUEST['pass_search_compname']?>" />
		  <input type="hidden" name="pass_search_email" id="pass_search_email" value="<?=$_REQUEST['pass_search_email']?>" />
		  <input type="hidden" name="pass_start" id="pass_start" value="<?=$_REQUEST['pass_start']?>" />
		  <input type="hidden" name="pass_sort_by" id="pass_sort_by" value="<?=$_REQUEST['pass_sort_by']?>" />
		  <input type="hidden" name="pass_sort_order" id="pass_sort_order" value="<?=$_REQUEST['pass_sort_order']?>" />
		  <input type="hidden" name="pass_records_per_page" id="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
		  <input type="hidden" name="pass_pg" id="pass_pg" value="<?=$_REQUEST['pass_pg']?>" />
		  <input type="hidden" name="fpurpose" id="fpurpose" value="update" />
		  <input type="hidden" name="checkbox[0]" id="checkbox[0]" value="<?php echo $customer_id?>" />
		  <input type="hidden" name="src_page" id="src_page" value="customer" />
		  <input type="hidden" name="src_id" id="src_id" value="<?php echo $customer_id?>" />
		  <input type="hidden" name="corporation_id" id="corporation_id" value="<?=$_REQUEST['corporation_id']?>" />
		  <input type="hidden" name="customer_payonaccount_status" id="customer_payonaccount_status" value="<?=$_REQUEST['customer_payonaccount_status']?>" />
		  <input type="hidden" name="cbo_dept" id="cbo_dept" value="<?=$_REQUEST['cbo_dept']?>" />
		  <input type="hidden" name="retdiv_id" id="retdiv_id" value="master_div" />
		  <input type="hidden" name="state_call" id="state_call" value="1" />
        </tr>
  </table>
</form>	 

