<?php
	/*#################################################################
	# Script Name 	: edit_combo.php
	# Description 	: Page for editing Combo
	# Coded by 		: SKR
	# Created on	: 27-July-2007
	# Modified by	: SKR
	# Modified On	: 31-July-2007
	# Modified by	: LHG
	# Modified On	: 13-Feb-2008
	#################################################################*/
#Define constants for this page
$page_type = 'Combo';
$help_msg =get_help_messages('EDIT_COMBO_MESS1');
$curtab		= ($_REQUEST['curtab'])?$_REQUEST['curtab']:'main_tab_td';
$combo_id=($_REQUEST['combo_id']?$_REQUEST['combo_id']:$_REQUEST['checkbox'][0]);
$sql_combo="SELECT combo_name,combo_description,combo_active,combo_showinall,combo_activateperiodchange,
				   combo_displaystartdate,combo_displayenddate,combo_hidename 
				   			FROM combo  
								WHERE combo_id=".$combo_id." AND sites_site_id='".$ecom_siteid."'";
$res_combo= $db->query($sql_combo);
if($db->num_rows($res_combo)==0)  { echo " <font color='red'> You Are Not Authorised  </a>"; exit; } 
$row_combo = $db->fetch_array($res_combo);
$showinallpages= $row_combo['combo_showinall'];
// Find the feature_id for mod_combo module from features table
	$sql_feat = "SELECT feature_id FROM features WHERE feature_modulename='mod_combo'";
	$ret_feat = $db->query($sql_feat);
	if ($db->num_rows($ret_feat))
	{
		$row_feat 	= $db->fetch_array($ret_feat);
		$feat_id	= $row_feat['feature_id'];
	}
	
	// Find the display settings details for this combo
	$sql_disp = "SELECT a.display_id,a.display_position,a.layout_code,a.themes_layouts_layout_id,a.display_order,b.layout_name FROM 
					display_settings a,themes_layouts b WHERE a.sites_site_id = $ecom_siteid AND display_component_id=$combo_id AND 
					features_feature_id=$feat_id AND a.themes_layouts_layout_id=b.layout_id";
	$ret_disp = $db->query($sql_disp);
	
	$editor_elements = "combo_description";
	include_once(ORG_DOCROOT."/console/js/tinymce.php");
?>	
<script language="javascript" type="text/javascript">
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
function delete_combination(delid)
{
	if(confirm('Are you sure you want to delete this combination?'))
	{
		var atleastone 			= 0;
		var combo_id			= '<?php echo $combo_id?>';
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
		Handlewith_Ajax('services/combo.php','fpurpose='+fpurpose+'&combo_id='+combo_id+'&delid='+delid);		
	}	
}
function valform(frm)
{
	fieldRequired = Array('combo_name','display_id[]');
	fieldDescription = Array('Combo Name','Combo Position');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	if(document.frmEditCombo.combo_activateperiodchange.checked  ==true){
			val_dates = compareDates(document.frmEditCombo.combo_displaystartdate,"Start Date\n Correct Format:dd-mm-yyyy ",document.frmEditCombo.combo_displayenddate,"End Date\n Correct Format:dd-mm-yyyy");
			if(!val_dates){
				return false;
			}
		}
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		show_processing();
		return true;
	} else {
		return false;
	}
}
function activeperiod(check,bid){
 if(document.frmEditCombo.combo_activateperiodchange.checked == true){
		document.getElementById(bid).style.display = '';
		}
		else{
		document.getElementById(bid).style.display = 'none';
		document.frmEditCombo.combo_activateperiodchange.checked = false;
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
function handle_expansion(imgobj,mod)
{

	var src = imgobj.src;
	var retindx = src.search('plus.gif');
	switch(mod)
	{
		case 'product_combo':
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('product_combotr_details'))
					document.getElementById('product_combotr_details').style.display = '';
				if(document.getElementById('product_combounassign_div'))
					document.getElementById('product_combounassign_div').style.display = '';	
					//alert("product_combounassign_div")
				call_ajax_showlistall('product_combo');	
				
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('product_combotr_details'))
					document.getElementById('product_combotr_details').style.display = 'none';
				
				if(document.getElementById('product_combounassign_div'))
					document.getElementById('product_combounassign_div').style.display = 'none';	
				
			}	
		break;
		case 'display_product_combo':
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('display_product_combotr_details'))
					document.getElementById('display_product_combotr_details').style.display = '';
				
				if(document.getElementById('display_product_combounassign_div'))
					document.getElementById('display_product_combounassign_div').style.display = '';	
				
				call_ajax_showlistall('display_product_combo');	
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('display_product_combotr_details'))
					document.getElementById('display_product_combotr_details').style.display = 'none';
				
				if(document.getElementById('display_product_combounassign_div'))
					document.getElementById('display_product_combounassign_div').style.display = 'none';	
				
			}	
		break;
		case 'display_category_combo':
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('display_category_combotr_details'))
					document.getElementById('display_category_combotr_details').style.display = '';
				
					
				if(document.getElementById('display_category_combounassign_div'))
					document.getElementById('display_category_combounassign_div').style.display = '';	
				call_ajax_showlistall('display_category_combo');	
				
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('display_category_combotr_details'))
					document.getElementById('display_category_combotr_details').style.display = 'none';
				
				
				if(document.getElementById('display_category_combounassign_div'))
					document.getElementById('display_category_combounassign_div').style.display = 'none';	
				
			}	
		break;
		case 'display_static_combo':
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('display_static_combotr_details'))
					document.getElementById('display_static_combotr_details').style.display = '';
				
				if(document.getElementById('display_static_combounassign_div'))
					document.getElementById('display_static_combounassign_div').style.display = '';	
				call_ajax_showlistall('display_static_combo');		
				
				
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('display_static_combotr_details'))
					document.getElementById('display_static_combotr_details').style.display = 'none';
				
				if(document.getElementById('display_static_combounassign_div'))
					document.getElementById('display_static_combounassign_div').style.display = 'none';	
				
			}	
		break;
		case 'combimg':
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('combimg_tr'))
					document.getElementById('combimg_tr').style.display = '';
				
				if(document.getElementById('combimgunassign_div'))
					document.getElementById('combimgunassign_div').style.display = '';	
				call_ajax_showlistall('display_combimage');		
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('combimg_tr'))
					document.getElementById('combimg_tr').style.display = 'none';
				
				if(document.getElementById('combimgunassign_div'))
					document.getElementById('combimgunassign_div').style.display = 'none';	
				
			}	
		break;
	 };

}
function call_ajax_showlistall(mod)
{  
	var atleastone 										= 0;
	var combo_id										= '<?php echo $combo_id?>';
	var cat_orders										= '';
	var fpurpose										= '';
	var retdivid										= '';
	var moredivid										= '';
	switch(mod)
	{
		case 'product_combo': // Case of Products in the combo
			retdivid   	= 'productcombo_div';
			fpurpose	= 'list_productcombo';
			//moredivid	= 'product_combounassign_div';
			
		break; 
		case 'display_product_combo': // Case of Display Products 
			retdivid   	= 'display_productcombo_div';
			fpurpose	= 'list_display_productcombo';
			//moredivid	= 'display_product_combounassign_div';
		break;
		case 'display_category_combo': // Case of Display Products 
			retdivid   	= 'display_categorycombo_div';
			fpurpose	= 'list_display_categorycombo';
			//moredivid	= 'display_category_combounassign_div';
		break;
		case 'display_static_combo': // Case of Display Products 
			retdivid   	= 'display_staticcombo_div';
			fpurpose	= 'list_display_staticcombo';
			//moredivid	= 'display_static_combounassign_div';
		break;	
		case 'display_combimage': // Case of combo image listing
			retdivid   	= 'combimg_div';
			fpurpose	= 'list_combimages';
			//moredivid	= 'combimgunassign_div';
		break;	
			
	}	
//    document.getElementById('retdiv_id').value 		= retdivid;/* Name of div to show the result */	
//	document.getElementById('retdiv_more').value 	= moredivid;/* Name of div to show the result */	
	retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
	retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';															
	var qrystr									= '';
	/* Calling the ajax function */
	//alert('fpurpose='+fpurpose+'&cur_groupid='+group_id);
	Handlewith_Ajax('services/combo.php','fpurpose='+fpurpose+'&combo_id='+combo_id);		
}		
function normal_assign_prodComboAssign(searchname,sortby,sortorder,recs,start,pg,comboid)
{
		window.location 			= 'home.php?request=combo&fpurpose=prodComboAssign&pass_searchname='+searchname+'&pass_sortby='+sortby+'&pass_sortorder='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_combo_id='+comboid;
}
function normal_assign_displayProdComboAssign(searchname,sortby,sortorder,recs,start,pg,comboid)
{
		window.location 			= 'home.php?request=combo&fpurpose=displayProdComboAssign&pass_searchname='+searchname+'&pass_sortby='+sortby+'&pass_sortorder='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_combo_id='+comboid;
}
function normal_assign_displayCategoryComboAssign(searchname,sortby,sortorder,recs,start,pg,comboid)
{
		window.location 			= 'home.php?request=combo&fpurpose=displayCategoryComboAssign&pass_searchname='+searchname+'&pass_sortby='+sortby+'&pass_sortorder='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_combo_id='+comboid;
}
function normal_assign_displayStaticComboAssign(searchname,sortby,sortorder,recs,start,pg,comboid)
{
		window.location 			= 'home.php?request=combo&fpurpose=displayStaticComboAssign&pass_searchname='+searchname+'&pass_sortby='+sortby+'&pass_sortorder='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_combo_id='+comboid;
}
function call_activate_combo()
{
	var combo_id	= '<?php echo $combo_id?>';
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
	var fpurpose			= 'activate_combo';
	orderbox				= 'product_combo_order_';
	disbox					= 'product_combo_discount_';
	varbox					= 'comb_var_';
	if(confirm('If you have modified any details please save them using "Save Details" button before activating the deal, Otherwise the changes will be lost.\n\n Do you want to continue?'))
	{
		if(confirm('Are you sure you want to activate this combo deal?'))
		{ 
			var temparr,tempnames;
			var tempprod = tempvar = tempvarval = tempprodasn = '';
			retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/combo.php','fpurpose='+fpurpose+'&combo_id='+combo_id+'&'+qrystr);
			/*Handlewith_Ajax('services/combo.php','fpurpose='+fpurpose+'&combo_id='+combo_id+'&ch_order='+ch_order+'&ch_dis='+ch_dis+'&ch_ids='+ch_ids+'&varprod_ids='+varprodids+'&'+qrystr);*/
		}
	}	
}
function call_deactivate_combo()
{
	var combo_id	= '<?php echo $combo_id?>';
	var qrystr		= '';		
	var fpurpose			= 'deactivate_combo';
	if(confirm('If you have modified any details please save them using "Save Details" button before deactivating the deal, otherwise the changes will be lost.\n\n Do you want to continue?'))
	{
		if(confirm('Are you sure you want to Deactivate this combo deal?'))
		{ 
	
			retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/combo.php','fpurpose='+fpurpose+'&combo_id='+combo_id+'&'+qrystr);
		}
	}	
}
function call_ajax_changeorderall(mod,checkboxname)
{
	var atleastone 			= 0;
	var combo_id			= '<?php echo $combo_id?>';
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
	var discgreater			= false;
	switch(mod)
	{
		case 'product_combo': // Case of Static pages in the group
			atleastmsg 	= 'Please select the Products to Save the details';
			confirmmsg 	= 'Are you sure you want to Save the details of selected Product(s)?';
			//retdivid   	= 'productcombo_div';
			//moredivid	= 'productcombounassign_div';
			fpurpose	= 'save_order';
			orderbox	= 'product_combo_order_';
			disbox		= 'product_combo_discount_';
			varbox		= 'comb_var_';
			
		break;
		
	}
	var temparr,tempnames;
	var tempprod = tempvar = tempvarval = tempprodasn = '';
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmEditCombo.elements.length;i++)
	{
		if (document.frmEditCombo.elements[i].type =='checkbox' && document.frmEditCombo.elements[i].name== checkboxname)
		{
			if (document.frmEditCombo.elements[i].checked==true)
			{
				atleastone = 1;
				if (ch_ids!='')
					ch_ids += '~';
				ch_ids += document.frmEditCombo.elements[i].value;
				
				obj = eval("document.getElementById('"+orderbox+document.frmEditCombo.elements[i].value+"')");
				if (ch_order != '')
					ch_order += '~';
				ch_order += ' '+obj.value; 
				
				obj1 = eval("document.getElementById('"+disbox+document.frmEditCombo.elements[i].value+"')");
				/*obj3 = eval("document.getElementById('product_org_price_"+document.frmEditCombo.elements[i].value+"')");*/
				
				if(obj1.value=='' || isNaN(obj1.value))
					obj1.value = 0;
					
				var val1 = parseFloat(obj1.value);
				/*var val2 = parseFloat(obj3.value);*/
				
				if (ch_dis != '')
					ch_dis += '~';
				
				if (val1!='')
				{
					if(isNaN(val1))
						obj1.value = 0;
					else
					{
						if (val1<0)
							obj1.value = 0;
					}
				}
				else
					obj1.value = 0;
				/*if(val1>val2)
				{
					discgreater = true;
				}
				*/	
					
				ch_dis += ' '+obj1.value; 
				
				
				for(ii=0;ii<document.frmEditCombo.elements.length;ii++)
				{
					hidobj = eval("document.getElementById('more_comb_hidden_"+document.frmEditCombo.elements[i].value+"')");
					if(hidobj)
					{
						if (hidobj.value==1)
						{
							tempnames = 'comb_var_'+document.frmEditCombo.elements[i].value;
							if (document.frmEditCombo.elements[ii].name.substr(0,tempnames.length)==tempnames)
							{
								temparr 	= document.frmEditCombo.elements[ii].name.split('_');
								tempprodasn = temparr[2];
								tempprod 	= temparr[4];
								tempvar		= temparr[3];
								if(document.frmEditCombo.elements[ii].type=='select-one')
									tempvarval	= document.frmEditCombo.elements[ii].value; 
								else if(document.frmEditCombo.elements[ii].type=='checkbox')
								{
									if(document.frmEditCombo.elements[ii].checked==true)
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
				}
			}	
		}
	}
	if (atleastone==0)
	{
		alert(atleastmsg);
	}
	else
	{
		/*if(discgreater)
		{
			alertmsgs = 'Discount set for certain product(s) exceed the webprice for those products. Please set the discount correctly and try again';
			alert(alertmsgs);
		}
		else
		{*/
			if(confirm(confirmmsg))
			{ 
				//document.getElementById('retdiv_id').value 	= retdivid;/* Name of div to show the result */
				//document.getElementById('retdiv_more').value 	= moredivid;/* Name of div to show the result */	
				retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
				retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
				Handlewith_Ajax('services/combo.php','fpurpose='+fpurpose+'&combo_id='+combo_id+'&ch_order='+ch_order+'&ch_dis='+ch_dis+'&ch_ids='+ch_ids+'&varprod_ids='+varprodids+'&'+qrystr);
			}
		/*}		*/
	}	
}

function call_ajax_deleteall(mod,checkboxname)
{
	
	var atleastone 			= 0;
	var combo_id			= '<?php echo $combo_id?>';
	var del_ids 			= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';
	var search_name								='<?php echo $_REQUEST['search_name']?>';
	var sortby								= '<?php echo $_REQUEST['sort_by']?>';
	var sortorder							= '<?php echo $_REQUEST['sort_order']?>';
	var recs									= '<?php echo $_REQUEST['records_per_page']?>';
	var start								= '<?php echo $_REQUEST['start']?>';
	var pg									= '<?php echo $_REQUEST['pg']?>';
	var curtab								= '<?php echo $curtab?>';
	var showinall							= '<?php echo $showinallpages?>';
	var qrystr									= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&combo_id='+combo_id+'&curtab='+curtab+'&showinall='+showinall;

	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmEditCombo.elements.length;i++)
	{
		if (document.frmEditCombo.elements[i].type =='checkbox' && document.frmEditCombo.elements[i].name==checkboxname)
		{

			if (document.frmEditCombo.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmEditCombo.elements[i].value;
			}	
		}
	}
	switch(mod)
	{
		case 'product_combo': // Case of Products in the combo
			atleastmsg 	= 'Please select the Product(s) to be deleted from the combo';
			confirmmsg 	= 'Are you sure you want to delete the selected Product(s) from the combo?';
			//retdivid   	= 'productcombo_div';
		//	moredivid	= 'product_combounassign_div';
			fpurpose	= 'prodComboUnAssign';
		break;
		case 'combo_image': // Case of Products in the combo
			atleastmsg 	= 'Please select the Image(s) to be deleted from the combo';
			confirmmsg 	= 'Are you sure you want to delete the selected Image(s) from the combo?';
			//retdivid   	= 'combimg_div';
			//moredivid	= 'combimgunassign_div'; 
			fpurpose	= 'unassign_combimagedetails';
		break;
		
		case 'display_product_combo':// Case of Display Products in the combo
			atleastmsg 	= 'Please select the Product(s) to be deleted from the combo';
			confirmmsg 	= 'Are you sure you want to delete the selected Product(s) from the combo?';
			//retdivid   	= 'display_productcombo_div';
			//moredivid	= 'display_product_combounassign_div';
			fpurpose	= 'displayProdComboUnAssign';
		break;
		case 'display_category_combo':// Case of Display Categories in the combo
			atleastmsg 	= 'Please select the Category(s) to be deleted from the combo';
			confirmmsg 	= 'Are you sure you want to delete the selected Category(s) from the combo?';
			//retdivid   	= 'display_categorycombo_div';
			//moredivid	= 'display_category_combounassign_div';
			fpurpose	= 'displayCategoryComboUnAssign';
			
			
		break;
		case 'display_static_combo':// Case of Display Static Pages in the combo
			atleastmsg 	= 'Please select the Static Page(s) to be deleted from the combo';
			confirmmsg 	= 'Are you sure you want to delete the selected Static Page(s) from the combo?';
			//retdivid   	= 'display_staticcombo_div';
		//	moredivid	= 'display_static_combounassign_div';
			fpurpose	= 'displayStaticComboUnAssign';
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
			Handlewith_Ajax('services/combo.php','fpurpose='+fpurpose+'&combo_id='+combo_id+'&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
function call_ajax_saveimagedetails(checkboxname)
{
	var atleastone 			= 0;
	var editid				= '<?php echo $combo_id?>';
	var ch_ids 				= '';
	var ch_variable			= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= 'combimg_div';
	var moredivid			= 'combimgunassign_div';
	var fpurpose			= 'save_combimagedetails';
	var ch_order			= '';
	var ch_title			= '';
	
	
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmEditCombo.elements.length;i++)
	{
		if (document.frmEditCombo.elements[i].type =='checkbox' && document.frmEditCombo.elements[i].name== checkboxname)
		{

			if (document.frmEditCombo.elements[i].checked==true)
			{
				atleastone = 1;
				if (ch_ids!='')
					ch_ids += '~';
				 ch_ids += document.frmEditCombo.elements[i].value;
				 
				 obj1 = eval("document.getElementById('img_ord_"+document.frmEditCombo.elements[i].value+"')");
				 obj2 = eval("document.getElementById('img_title_"+document.frmEditCombo.elements[i].value+"')");
				 
				if (ch_order != '')
					ch_order += '~';
				 ch_order += obj1.value; 
				 
				 if (ch_title != '')
					ch_title += '~';
				 ch_title += obj2.value; 
			}	
		}
	}
	
	if (atleastone==0)
	{
		alert('Please select the image(s) to be saved');
	}
	else
	{
		if(confirm('Are you sure you want to save the title and order of selected images?'))
		{
			document.getElementById('retdiv_id').value 	= retdivid;/* Name of div to show the result */
			retobj 										= eval("document.getElementById('"+retdivid+"')");
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/combo.php','fpurpose='+fpurpose+'&edit_id='+editid+'&ch_order='+ch_order+'&ch_title='+ch_title+'&ch_ids='+ch_ids+'&'+qrystr);
		}	
	}
}

/*function call_ajax_deleteall(checkboxname)
{
	var atleastone 			= 0;
	var editid				= '<?php echo $combo_id?>';
	var del_ids 			= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';
	/* check whether any checkbox is ticked */
	//for(i=0;i<document.frmEditCombo.elements.length;i++)
	//{
	//	if (document.frmEditCombo.elements[i].type =='checkbox' && document.frmEditCombo.elements[i].name==checkboxname)
	//	{

	//		if (document.frmEditCombo.elements[i].checked==true)
	//		{
	//			atleastone = 1;
	//			if (del_ids!='')
	//				del_ids += '~';
	//	//		 del_ids += document.frmEditCombo.elements[i].value;
//			}	
//		}
//	}
	
	//atleastmsg 	= 'Please select the combo image(s) to be unassigned.';
//	confirmmsg 	= 'Are you sure you want to unassign the selected Image(s)?';
//	retdivid   	= 'combimg_div';
//	moredivid	= 'combimgunassign_div';
//	fpurpose	= 'unassign_combimagedetails';
//	if (atleastone==0)
//	{
//		alert(atleastmsg);
//	}
//	else
//	{
//		if(confirm(confirmmsg))
//		{
//			document.getElementById('retdiv_id').value 	= retdivid;/* Name of div to show the result */
//			document.getElementById('retdiv_more').value= moredivid;/* Name of div to show the result */
//			retobj 										= eval("document.getElementById('"+retdivid+"')");
//			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
//			Handlewith_Ajax('services/combo.php','fpurpose='+fpurpose+'&edit_id='+editid+'&del_ids='+del_ids+'&'+qrystr);
//		}	
//	}	
//}*/
function handle_imagesel(id)
{
	var ret_str	= '';
	var new_str = ''
	tdobj		= eval("document.getElementById('img_td_"+id+"')");
	if(tdobj.className=='imagelistproducttabletd')
	{
		tdobj.className = 'imagelistproducttabletd_sel';
	}	
	else
	{
		tdobj.className = 'imagelistproducttabletd';
	}	
}


function handle_tabs(id,mod)
{
    
    
	tab_arr 								= new Array('main_tab_td','products_tab_td','prodmenu_tab_td','categmenu_tab_td','statmenu_tab_td','seo_tab_td');
	var atleastone 							= 0;
	var combo_id							= '<?php echo $combo_id?>';
	var cat_orders							= '';
	var fpurpose							= '';
	var retdivid							= '';
	var moredivid							= '';
	var search_name								='<?php echo $_REQUEST['search_name']?>';
	var sortby								= '<?php echo $_REQUEST['sort_by']?>';
	var sortorder							= '<?php echo $_REQUEST['sort_order']?>';
	var recs									= '<?php echo $_REQUEST['records_per_page']?>';
	var start								= '<?php echo $_REQUEST['start']?>';
	var pg									= '<?php echo $_REQUEST['pg']?>';
	var curtab								= '<?php echo $curtab?>';
	var showinall							= '<?php echo $showinallpages?>';
	var qrystr									= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&combo_id='+combo_id+'&curtab='+curtab+'&showinall='+showinall;
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
		case 'combomain_info':
			//fpurpose ='list_combo_maininfo';
			document.frmEditCombo.fpurpose.value = 'list_combo_maininfo';
			document.frmEditCombo.submit();
			return;
		break;
		case 'products_combo': // Case of Categories in the group
			//retdivid   	= 'master_div';
			fpurpose	= 'list_productcombo';
			//moredivid	= 'category_groupunassign_div';
		break;
		case 'displayprod_group': // Case of Display Products in the group
			//retdivid   	= 'master_div';
			fpurpose	= 'list_display_productcombo';
			//moredivid	= 'displayproduct_groupunassign_div';
		break;
		case 'displaycateg_group': // Case of Display Categories in the group
			//retdivid   	= 'master_div';
			fpurpose	= 'list_display_categorycombo';
			//moredivid	= 'displaycategory_groupunassign_div';
		break;
		case 'displaystatic_group': // Case of Display Categories in the group
			//retdivid   	= 'master_div';
			fpurpose	= 'list_display_staticcombo';
			//moredivid	= 'displaystatic_groupunassign_div';
		break;
		case 'seo': // Case of Display Categories in the group
			//retdivid   	= 'master_div';
			fpurpose	= 'list_seo';
			//moredivid	= 'displaystatic_groupunassign_div';
		break;
	}
	retobj 									= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
	//retobj										= document.getElementById('retdiv_id');
	retobj.innerHTML 						= '<center><img src="images/loading.gif" alt="Loading"></center>';															
	
	/* Calling the ajax function */
	//alert('fpurpose='+fpurpose+'&cur_groupid='+group_id);
	Handlewith_Ajax('services/combo.php','fpurpose='+fpurpose+'&combo_id='+combo_id+'&'+qrystr);	
}
function call_save_seo(mod)
{
	var atleastone 			= 0;
	var editid												=<?php echo $combo_id?>;
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
	var catname						='<?php echo $_REQUEST['catname']?>';
	var sortby							= '<?php echo $_REQUEST['sort_by']?>';
	var sortorder						= '<?php echo $_REQUEST['sort_order']?>';
	var recs							= '<?php echo $_REQUEST['records_per_page']?>';
	var start							= '<?php echo $_REQUEST['start']?>';
	var pg								= '<?php echo $_REQUEST['pg']?>';
	var curtab							= '<?php echo $curtab?>';
	var showinall						= '<?php echo $showinallpages?>';
	var qrystr							= 'catname='+catname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&curtab='+curtab+'&showinall='+showinall;
	fpurpose  = 'save_seo';
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmEditCombo.elements.length;i++)
	{  
	if (document.frmEditCombo.elements[i].type =='text' && document.frmEditCombo.elements[i].name.substr(0,7)== 'keyword')
		{			
				atleastone = 1;
				if (ch_ids!='')
					ch_ids += '~';
				 ch_ids += document.frmEditCombo.elements[i].value;			
		}
	}
	atleastmsg = "Enter the Title";
	var page_title = '';
	var meta ='';
	page_title = document.frmEditCombo.page_title.value;
	meta       = document.frmEditCombo.page_meta.value;
	qrystr +='&page_title='+page_title+'&page_meta='+meta;	 
	//if(page_title=='')
	//{
		//alert(atleastmsg);
	//}
	//else
	{
		
			retobj 											= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
			retobj.innerHTML 								= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/combo.php','fpurpose='+fpurpose+'&combo_id='+editid+'&ch_ids='+ch_ids+'&'+qrystr);
			
	}	
}
</script>
<form name='frmEditCombo' action='home.php?request=combo' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="5" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=combo&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Combo</a><span>Edit Combo for '<? echo $row_combo['combo_name'];?>'</span></div></td>
        </tr>
		 <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="5">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
        <tr>
          <td colspan="5" align="left" valign="middle">
		     	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabmenu_x">
					<tr> 
						<td align="left" onClick="handle_tabs('main_tab_td','combomain_info')" class="<?php if($curtab=='main_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="main_tab_td"><span>Main Info</span></td>
						<td  align="left" onClick="handle_tabs('products_tab_td','products_combo')" class="<?php if($curtab=='products_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="products_tab_td"><span>Products in this Deal</span></td>
						<td  align="left" onClick="handle_tabs('prodmenu_tab_td','displayprod_group')" class="<?php if($curtab=='prodmenu_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="prodmenu_tab_td"><span>Display Deal for Following Products</span></td>
						<td align="left" onClick="handle_tabs('categmenu_tab_td','displaycateg_group')" class="<?php if($curtab=='categmenu_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="categmenu_tab_td"><span>Display Deal for Following Categories</span></td>
						<td  align="left" onClick="handle_tabs('statmenu_tab_td','displaystatic_group')" class="<?php if($curtab=='statmenu_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="statmenu_tab_td"><span>Display Deal for Following Static Pages</span></td>
						<td  align="left" onClick="handle_tabs('seo_tab_td','seo')" class="<?php if($curtab=='seo_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="seo_tab_td"><span>SEO Settings</span></td>

						<td width="90%" align="left">&nbsp;</td>
					</tr>
				</table>
		  </td>
        </tr>
       
        <tr>
          <td colspan="5" align="center" valign="middle" class="tdcolorgray1" > 
		  	 <div id='master_div'>
			<?php 
			if ($curtab=='main_tab_td')
			{
				//include_once("classes/fckeditor.php");
				show_combo_maininfo($combo_id,$alert);
			}
			elseif ($curtab=='products_tab_td')
			{
				show_product_combo_list($combo_id,$alert);
			}
			elseif ($curtab=='prodmenu_tab_td')
			{
				show_display_product_combo_list($combo_id,$alert);
			} 
			elseif ($curtab=='categmenu_tab_td')
			{
				show_display_category_combo_list($combo_id,$alert);
			}
			elseif ($curtab=='statmenu_tab_td')
			{
				show_display_static_combo_list($combo_id,$alert);
			}
			?>		
		  </div>
		  </td>
        </tr>
		
        <tr>
          
          <td colspan="5" align="center" valign="middle" class="tdcolorgray">
		  
		  <input type="hidden" name="combo_id" id="combo_id" value="<?=$combo_id?>" />
		  <input type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>" />
		 	<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
		  	<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
		  	<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
		  	<input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
			<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
		  <input type="hidden" name="retdiv_id" id="retdiv_id" value="master_div" />
		  <input type="hidden" name="retdiv_more" id="retdiv_more" value="" />
		  <input type="hidden" name="fpurpose" id="fpurpose" value="update" />
		</td>
        </tr>
		<tr>
		<td colspan="5" align="center" valign="middle" class="tdcolorgray">		</td>
		</tr>
		
      </table>
	  <input type="hidden" name="src_page" id="src_page" value="comb_img" />
	  <input type="hidden" name="src_id" id="src_id" value="<?php echo $combo_id?>" />
</form>	  

