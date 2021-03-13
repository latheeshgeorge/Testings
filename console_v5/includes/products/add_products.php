<?php
	/*#################################################################
	# Script Name 	: add_products.php
	# Description 		: Page for adding Products
	# Coded by 		: Sny
	# Created on		: 26-June-2007
	# Modified by		: Sny
	# Modified On		: 18-Sep-2008
	#################################################################*/
//Define constants for this page
$page_type	= 'Products';
$help_msg 		= 'This section helps in adding Products';
$gen_arr 		= get_general_settings('unit_of_weight','general_settings_sites_common');
$sql_theme = "SELECT theme_imgdisplay_normal, theme_imgdisplay_video, theme_imgdisplay_flash, theme_imgdisplay_rotate,product_image_display_format  
						FROM 
							themes 
						WHERE 
							theme_id = $ecom_themeid 
						LIMIT 
							1";
$ret_theme = $db->query($sql_theme);
if($db->num_rows($ret_theme))
{
	$row_theme = $db->fetch_array($ret_theme);
}
?>	
<script language="javascript" type="text/javascript">
function handle_qty_more_options(obj)
{
	if (obj.value=='NOR')
		document.getElementById('qty_more_box').style.display = 'none';
	else
		document.getElementById('qty_more_box').style.display = '';
}
function handle_flv(obj)
{
	document.getElementById('JAVA_dispdiv').style.display='none';
	document.getElementById('FLASH_ROTATE_dispdiv').style.display='none';
	document.getElementById('FLASH_dispdiv').style.display='none';	
	document.getElementById('NORMAL_dispdiv').style.display='none';	
	if (obj.value =='JAVA')
	{
		if(document.getElementById('JAVA_dispdiv'))
			document.getElementById('JAVA_dispdiv').style.display='';	
		if(document.getElementById('flv_tr'))
			document.getElementById('flv_tr').style.display = '';
		if(document.getElementById('flv_rotate_tr'))
			document.getElementById('flv_rotate_tr').style.display = 'none';
			
	}
	else if(obj.value=='FLASH')
	{
		if(document.getElementById('FLASH_dispdiv'))
			document.getElementById('FLASH_dispdiv').style.display='';	
		if(document.getElementById('flv_tr'))
			document.getElementById('flv_tr').style.display = 'none';	
		if(document.getElementById('flv_rotate_tr'))
			document.getElementById('flv_rotate_tr').style.display = 'none';	
	}
	else if (obj.value=='FLASH_ROTATE')
	{
		if(document.getElementById('FLASH_ROTATE_dispdiv'))
			document.getElementById('FLASH_ROTATE_dispdiv').style.display='';	
		if(document.getElementById('flv_tr'))
			document.getElementById('flv_tr').style.display = 'none';
		if(document.getElementById('flv_rotate_tr'))
			document.getElementById('flv_rotate_tr').style.display = '';
	}
	else
	{
		if(document.getElementById('NORMAL_dispdiv'))
			document.getElementById('NORMAL_dispdiv').style.display='';	
		if(document.getElementById('flv_tr'))
			document.getElementById('flv_tr').style.display = 'none';
		if(document.getElementById('flv_rotate_tr'))
			document.getElementById('flv_rotate_tr').style.display = 'none';
	}
}
function copyretail_to_stores()
{
	var webprice = document.frmAddProduct.product_webprice.value;
	for(i=0;i<document.frmAddProduct.elements.length;i++)
	{
		if (document.frmAddProduct.elements[i].name.substr(0,27)=='product_branch_retailprice_')
		{
			document.frmAddProduct.elements[i].value = webprice;		
		}
	}
}
function valforms(frm)
{
	var atleastone = false;
	fieldRequired = Array('product_name','product_shortdesc');
	fieldDescription = Array('Product Name','Short Description');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldSpecChars = Array('product_barcode');
	fieldConfirmDesc  = Array();
	fieldNumeric = Array('product_webprice','product_costprice','product_total_preorder_allowed','product_deposit','product_extrashippingcost','product_weight','product_bonuspoints','product_reorderqty');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric,fieldSpecChars)) {
		
			/* Check whether category is selected*/
			obj = document.getElementById('category_id[]');
			var atleast = false;
				var id=0;
				for(i=0;i<document.frmAddProduct.elements.length;i++)
				{
					if (document.frmAddProduct.elements[i].type =='hidden' && document.frmAddProduct.elements[i].name=='category_id[]')
					{
							atleast = true;
							id = document.frmAddProduct.elements[i].value;
					}
				}
				if (atleast==false)
				{
					alert('please select the category');
					document.getElementById('Addmorecat_tab').focus();
					return false;
				}
				/* Check whether default catgroup is selected. if yes then check whether it is there in the selected group list */
				if (document.getElementById('default_category_id').value==0)
				{
					alert('Please select the default category');
					document.getElementById('default_category_id_'+id).focus();
					return false;
				}
			/*if(obj.options.length==0)
			{
				alert('Category is required');
				return false;
			}
			else
			{
				
				for(i=0;i<obj.options.length;i++)
				{
					if(obj.options[i].selected)
					{
						atleastone = true;
					}
				}
				if (atleastone==false)
				{
					alert('Please select the category');
					return false;
				}
				/* Check whether default catgroup is selected. if yes then check whether it is there in the selected group list */
				/*if (document.getElementById('default_category_id')==0)
				{
					alert('Please select the default category');
					return false;
				}
				else
				{
					var def_ok = false;
					for(i=0;i<obj.options.length;i++)
					{
						if(obj.options[i].selected)
						{
							if(obj.options[i].value==document.getElementById('default_category_id').value)
								def_ok = true;
						}
					}
					if (def_ok==false)
					{
						alert('Default Product Category not in selected Category list');
						return false;
					}
				}
				
			}*/
			if(document.frmAddProduct.product_preorder_allowed.value==1)
			{
				 if(document.frmAddProduct.product_total_preorder_allowed.value<0)
				 {
				 alert('Total Preorder should be a positive value');
						return false;
				 }
			}
			/* Check whether instock date is specified */
			if(document.frmAddProduct.product_preorder_allowed.checked)
			{
				if(document.frmAddProduct.product_total_preorder_allowed.value=='')
				{
					alert('Total Preorder allowed not specified');
					return false;
				}
				if(document.frmAddProduct.product_instock_date.value=='' || document.frmAddProduct.product_instock_date.value=='00-00-0000')
				{
					alert('Instock Date allowed not specified');
					return false;
				}
			}	
			
			if (document.frmAddProduct.product_webstock.value<0)
			{
				alert('FIxed Stock value should be positive');
				document.frmAddProduct.product_webstock.focus();
				return false;
			}
			if (document.frmAddProduct.product_discount.value<0)
			{
				alert('Discount value should be positive');
				return false;
			}
			if(document.frmAddProduct.product_discount_enteredasval.value==0)
			{
				if (document.frmAddProduct.product_discount.value>=100)
				{
					alert('Discount % should be less than 100');
					return false;
				}
			}
			if(document.frmAddProduct.product_discount_enteredasval.value==1 || document.frmAddProduct.product_discount_enteredasval.value==2)
			{
				var disc = parseFloat(document.frmAddProduct.product_discount.value);
				var web =  parseFloat(document.frmAddProduct.product_webprice.value);
				if(disc>0 && web>0)
				{
					if (web <= disc)
					{
						alert('Discount value should be less than webprice');
						return false;
					}
				}
			}	
			if (document.frmAddProduct.product_deposit.value>=100 || document.frmAddProduct.product_deposit.value<0)
			{
				alert('Deposit % should be less than 100 and a positive value');
				document.frmAddProduct.product_deposit.focus();
				return false;
			}
			if (document.frmAddProduct.product_extrashippingcost.value<0)
			{
				alert('Extra shipping cost should be a positive value');
				return false;
			}
			if (document.frmAddProduct.product_bonuspoints.value<0)
			{
				alert('Bonus points should be a Positive value');
				return false;
			}
			if (document.frmAddProduct.product_weight.value<0)
			{
				alert('Weight should be a Positive value');
				return false;
			}
			var pr_retail =0;
			var pr_peritem = 0;
			var pr_peritem_numeric =0;
			for(var i=0; i < frm.elements.length; i++)
			{ 
			  if(frm.elements[i].name.substr(0,17)=='prodbulknew_price')
			  {
					if(frm.elements[i].value<0)
					{
					 pr_peritem =1;
					 frm.elements[i].focus();
					}
					else if(isNaN(frm.elements[i].value))
					{ 
					 pr_peritem_numeric =1;
					 frm.elements[i].focus();
					}
				}
				if(frm.elements[i].name.substr(0,27)=='product_branch_retailprice_')
			    {
				    if(frm.elements[i].value<0)
					{
					 pr_retail =1;
					 frm.elements[i].focus();
					} 
				}
			}
			if (document.frmAddProduct.product_webprice.value<0 || pr_retail==1)
			{
				alert('Retail Price should be a Positive value');
				document.frmAddProduct.product_webprice.focus();
				return false;
			}
			if (document.frmAddProduct.product_costprice.value<0)
			{
				alert('Cost Price should be a Positive value');
				document.frmAddProduct.product_costprice.focus();
				return false;
			}
			if(pr_peritem ==1)
			{
			 alert('Price per item in the bulk discount should be a Positive value');
			 return false;
			} 
			if(pr_peritem_numeric ==1)
			{
			  alert('Price per item in the bulk discount should be a Numeric value');
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
			switch(targetdiv)
			{
			case 'moveto_showcategory_div':				
				document.getElementById('popup_bg_div').style.display='';
				document.getElementById('moveto_showcategory_div').style.display='';						
				break;
				case 'categorymain_div':
				category_click_labels_load();
				break;	
			};	
			hide_processing();
		}
		else
		{
			 show_request_alert(req.status);
			//alert("Problem in requesting XML :"+req.statusText);
		}
	}
}
function category_click_labels_load()
{
	call_ajax_showlistall('prodlabels');
}
function call_ajax_showlistall(mod)
{
	var atleastone 										= 0;
	var prod_id											= '<?php echo $_REQUEST['checkbox'][0];?>';
	var cat_orders										= '';
	var fpurpose										= '';
	var retdivid										= '';
	var moredivid										= '';
	var qrystr											= '';
	switch(mod)
	{
		case 'prodlabels':
			retdivid   	= 'labelmain_div';
			fpurpose	= 'list_labels_block';
			catobj = document.getElementById('category_id[]');
			var cat_str = '';
			var method = document.getElementById('modused').value;
			for(i=0;i<document.frmAddProduct.elements.length;i++)
			{
				if(method=='assign')
				{
					/*if (document.frmAddProduct.elements[i].type =='checkbox' && document.frmAddProduct.elements[i].name== 'checkbox_assigncat[]')
					{
						if (document.frmAddProduct.elements[i].checked==true)
						{
							atleastone ++;
							if (cat_str!='')
								cat_str += '~';
							 cat_str += document.frmAddProduct.elements[i].value;
						} 
					}
					*/
					if (document.frmAddProduct.elements[i].type =='hidden' && document.frmAddProduct.elements[i].name== 'category_id[]')
					{						
							atleastone ++; 
							if (cat_str!='')
								cat_str += '~';
							 cat_str += document.frmAddProduct.elements[i].value;
					}
				}
				else if(method=='remove')
				{
					if (document.frmAddProduct.elements[i].type =='hidden' && document.frmAddProduct.elements[i].name== 'category_id[]')
					{						
							atleastone ++;
							if (cat_str!='')
								cat_str += '~';
							 cat_str += document.frmAddProduct.elements[i].value;
					}
				
				}
			}
			if(atleastone>0)
			{
				/*for(i=0;i<catobj.options.length;i++)
				{
					if(catobj.options[i].selected)
					{
						if(cat_str!='')
							cat_str += '~';
						cat_str += catobj.options[i].value;
					}
				}
				*/
				qrystr += 'cat_str='+cat_str;
			}
			/*
			var cat_str = '';
			if(catobj.length)
			{
				for(i=0;i<catobj.options.length;i++)
				{
					if(catobj.options[i].selected)
					{
						if(cat_str!='')
							cat_str += '~';
						cat_str += catobj.options[i].value;
					}
				}
				qrystr += 'cat_str='+cat_str;
			}
			*/
		break;
	};
	document.getElementById('retdiv_id').value 		= retdivid;/* Name of div to show the result */	
	retobj 										= eval("document.getElementById('"+retdivid+"')");
	retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';															
	/* Calling the ajax function */
	Handlewith_Ajax('services/products.php','fpurpose='+fpurpose+'&'+qrystr);
}
function handle_preorder()
{
	document.frmAddProduct.product_total_preorder_allowed.value ='';
	document.frmAddProduct.product_instock_date.value ='';
	
	if(document.frmAddProduct.product_preorder_allowed.checked==true)
	{
		if(document.frmAddProduct.product_alloworder_notinstock)
			document.frmAddProduct.product_alloworder_notinstock.checked=false;
		
		if(document.getElementById('preorder_tr1'))
			document.getElementById('preorder_tr1').style.display = '';
		if(document.getElementById('preorder_tr2'))
			document.getElementById('preorder_tr2').style.display = '';	
		document.frmAddProduct.product_total_preorder_allowed.disabled  = false;
		document.frmAddProduct.product_total_preorder_allowed.className = 'normal_class';
		
		document.frmAddProduct.product_instock_date.disabled = false
		document.frmAddProduct.product_instock_date.className = 'normal_class';
		if(document.getElementById('orderoutstock_tr1'))
				document.getElementById('orderoutstock_tr1').style.display = 'none';
					document.frmAddProduct.product_order_outstock_instock_date.disabled = true;	
		
	}
	else
	{
		
		document.frmAddProduct.product_total_preorder_allowed.disabled  = true;
		document.frmAddProduct.product_total_preorder_allowed.className = 'disabled_class';
		document.frmAddProduct.product_instock_date.disabled = true
		document.frmAddProduct.product_instock_date.className = 'disabled_class';
		if(document.getElementById('preorder_tr1'))
			document.getElementById('preorder_tr1').style.display = 'none';
		if(document.getElementById('preorder_tr2'))
			document.getElementById('preorder_tr2').style.display = 'none';
			if(document.frmAddProduct.product_alloworder_notinstock.checked==true)
			{
			   if(document.getElementById('orderoutstock_tr1'))
				document.getElementById('orderoutstock_tr1').style.display = '';
					document.frmAddProduct.product_order_outstock_instock_date.disabled = false;
			}		
	}
}
function handle_product_sale_icon(obj)
{
	if(obj.checked)
	{
		document.getElementById('product_saleicon_text_id').style.display='';
		document.frmAddProduct.product_newicon_show.checked = false;
		document.getElementById('product_newicon_text_id').style.display='none';
	}	
	else
	{
		document.getElementById('product_saleicon_text_id').style.display='none';
	}	
}
function handle_product_new_icon(obj)
{
	if(obj.checked)
	{
		document.getElementById('product_newicon_text_id').style.display='';
		document.frmAddProduct.product_saleicon_show.checked = false;
		document.getElementById('product_saleicon_text_id').style.display='none';
	}	
	else
	{
		document.getElementById('product_newicon_text_id').style.display='none';
	}	
}
function handle_alwaysaddtocart()
{
	if (document.frmAddProduct.product_alloworder_notinstock.checked==true)
	{
		if(document.frmAddProduct.product_preorder_allowed.checked==true)
		{
			document.frmAddProduct.product_preorder_allowed.checked = false;
			 handle_preorder();
		}	
	}
	    document.frmAddProduct.product_order_outstock_instock_date.value ='';	
		if(document.frmAddProduct.product_alloworder_notinstock.checked==true)
		{	
			if(document.getElementById('orderoutstock_tr1'))
				document.getElementById('orderoutstock_tr1').style.display = '';				
			document.frmAddProduct.product_order_outstock_instock_date.disabled = false
			document.frmAddProduct.product_order_outstock_instock_date.className = 'normal_class';			
		}
		else
		{ 
			if(document.getElementById('orderoutstock_tr1'))
				document.getElementById('orderoutstock_tr1').style.display = 'none';	
			document.frmAddProduct.product_order_outstock_instock_date.disabled  = true;
			document.frmAddProduct.product_order_outstock_instock_date.className = 'disabled_class';
	    }	
}
function handle_bulkdiscount(obj)
{
	if(obj.checked)
		document.getElementById('bulkdisc_tr').style.display='';
	else
		document.getElementById('bulkdisc_tr').style.display='none';
}
function extdiscchange() 
{
if(document.frmAddProduct.product_discount_enteredasval.value==2) {
	document.getElementById('extdisc').innerHTML = 'Exact Discount Price ('+ document.frmAddProduct.hid_cur_sign.value+' )';
	} else if(document.frmAddProduct.product_discount_enteredasval.value==0) {
		document.getElementById('extdisc').innerHTML = 'Discount Percentage';
	} else if(document.frmAddProduct.product_discount_enteredasval.value==1) {
		document.getElementById('extdisc').innerHTML = 'Discount Value ('+ document.frmAddProduct.hid_cur_sign.value+' )';
	}
}
function clear_outofstockdate()
{
	document.getElementById('product_order_outstock_instock_date').value ='';
}
function show_categorypopup()
{
	var qrystr														= '';
	var ch_ids   = '';
	var atleastone = 0;
	for(i=0;i<document.frmAddProduct.elements.length;i++)
	{
		if (document.frmAddProduct.elements[i].type =='hidden' && document.frmAddProduct.elements[i].name== 'category_id[]')
			{			
					atleastone ++;
					if (ch_ids!='')
						ch_ids += '~';
					 ch_ids += document.frmAddProduct.elements[i].value;
			}
	}	
	if(atleastone>0)
	{
		 qrystr +='ch_ids='+ch_ids;
	}
	qrystr +='&mod=add';
	var fpurpose													= 'show_category_popup';
	document.getElementById('retdiv_id').value 						= 'moveto_showcategory_div';
	obj																= eval("document.getElementById('moveto_showcategory_div')");
	obj.innerHTML 													= '<center><img src="images/loading.gif" alt="Loading"></center>';
	Handlewith_Ajax('services/products.php','fpurpose='+fpurpose+'&'+qrystr);
} 
function call_cancel()
{
	document.getElementById('moveto_showcategory_div').style.display ='none';
	document.getElementById('popup_bg_div').style.display='none';

}
function call_ajax_page(invid,page)
{
	var qrystr														= '';
	var fpurpose													= 'show_category_popup';
	var catname      												= document.getElementById('catname_pop').value ;
	var parent                                                      = document.getElementById('parentid_pop').value ;
    var perpage														= document.getElementById('perpage_pop').value ;
    var ch_ids ='';
    //var catgroup                                                    = document.getElementById('catgroupid_pop').value ;
	for(i=0;i<document.frmAddProduct.elements.length;i++)
	{
	   
	   if ((document.frmAddProduct.elements[i].type =='hidden') )
		{
			if(document.frmAddProduct.elements[i].name== 'passcheckbox_assigncat[]')
			{
					
						atleastone ++;
						if (ch_ids!='')
							ch_ids += '~';
						 ch_ids += document.frmAddProduct.elements[i].value;				
			}
		}
	}	
	qrystr = 'catname='+catname+'&parentid='+parent+'&perpage='+perpage+'&ch_ids='+ch_ids;;
    qrystr +='&mod=add';
	document.getElementById('retdiv_id').value 						= 'moveto_showcategory_div';
	obj																= eval("document.getElementById('moveto_showcategory_div')");
	obj.innerHTML 													= '<center><img src="images/loading.gif" alt="Loading"></center>';
	Handlewith_Ajax('services/products.php','fpurpose='+fpurpose+'&cur_prodid='+invid+'&page='+page+'&'+qrystr);

}
function call_ajax_search(invid)
{
	var qrystr														= '';
	var fpurpose													= 'show_category_popup';
	var catname      												= document.getElementById('catname_pop').value ;
	var parent                                                      = document.getElementById('parentid_pop').value ;
    var perpage														= document.getElementById('perpage_pop').value ;
    var ch_ids ='';

    for(i=0;i<document.frmAddProduct.elements.length;i++)
	{
	   
	   if ((document.frmAddProduct.elements[i].type =='hidden') )
		{
			if(document.frmAddProduct.elements[i].name== 'passcheckbox_assigncat[]')
			{
					
						atleastone ++;
						if (ch_ids!='')
							ch_ids += '~';
						 ch_ids += document.frmAddProduct.elements[i].value;				
			}
		}
	}		
    //var catgroup                                                    = document.getElementById('catgroupid_pop').value ;
	qrystr = 'catname='+catname+'&parentid='+parent+'&perpage='+perpage+'&ch_ids='+ch_ids;
	qrystr +='&mod=add';
	document.getElementById('retdiv_id').value 						= 'moveto_showcategory_div';
	obj																= eval("document.getElementById('moveto_showcategory_div')");
	obj.innerHTML 													= '<center><img src="images/loading.gif" alt="Loading"></center>';
	Handlewith_Ajax('services/products.php','fpurpose='+fpurpose+'&cur_prodid='+invid+'&'+qrystr);

}
function call_ajax_assign_category(invid)
{
	var ch_ids     ='';
	var qrystr     = '';
	var atleastone = 0;
	var fpurpose													= 'assign_category_product_popup';
	var defval;
	var atleastmsg = 'Please select atleast one category';
	for(i=0;i<document.frmAddProduct.elements.length;i++)
	{
	   
	   if ((document.frmAddProduct.elements[i].type =='checkbox' || document.frmAddProduct.elements[i].type =='hidden') )
		{
			if (document.frmAddProduct.elements[i].name.substring(0,20)=='default_category_id_')
			{  
				if(document.frmAddProduct.elements[i].checked==true)
				{
					defval   = document.frmAddProduct.elements[i].value;
				}
			}
			if(document.frmAddProduct.elements[i].name== 'checkbox_assigncat[]')
			{
				if (document.frmAddProduct.elements[i].checked==true)
				{
					atleastone ++;
					if (ch_ids!='')
						ch_ids += '~';
					 ch_ids += document.frmAddProduct.elements[i].value;
				} 
			}
			if(document.frmAddProduct.elements[i].name== 'passcheckbox_assigncat[]')
			{
					
						atleastone ++;
						if (ch_ids!='')
							ch_ids += '~';
						 ch_ids += document.frmAddProduct.elements[i].value;				
			}
		}
	}
	qrystr += '&defval='+defval;
	if (atleastone==0)
	{
		alert(atleastmsg);
		return false;
	}
	else
	{		
			document.getElementById('popup_bg_div').style.display='none';
			document.getElementById('modused').value 						= 'assign';
			document.getElementById('retdiv_id').value 						= 'categorymain_div';

			retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/products.php','fpurpose='+fpurpose+'&cur_prodid='+invid+'&ch_ids='+ch_ids+'&'+qrystr);
	}
   	document.getElementById('moveto_showcategory_div').style.display ='none';
}
function select_default_category(cid)
{
	var atleastone = 0;
	
	for(i=0;i<document.frmAddProduct.elements.length;i++)
	{ 
	if (document.frmAddProduct.elements[i].type =='checkbox' && document.frmAddProduct.elements[i].name.substring(0,20)=='default_category_id_')
		{  	var eleval   = document.frmAddProduct.elements[i].value;

			 if(document.frmAddProduct.elements[i].checked==true)
				{
					if(document.frmAddProduct.elements[i].value==cid)
					{ 
						atleastone++;						
						document.frmAddProduct.elements[i].checked=true;
						document.frmAddProduct.default_category_id.value=cid;
						document.getElementById("li_default_category_id_"+cid).className = "li_category_selected";

					}
					else
					{
						document.frmAddProduct.elements[i].checked=false;
						document.getElementById("li_default_category_id_"+eleval).className = "li_category";

					}
				}			
				else
				{
				        document.frmAddProduct.elements[i].checked=false;
						document.getElementById("li_default_category_id_"+eleval).className = "li_category";
				}
										
		}		

	}
	if(atleastone==0)
		{
			document.frmAddProduct.default_category_id.value=0;

		} 
}
function remove_category(id,prodid)
{
	var cnt =0 ;
		var qrystr     = '';
		var ch_ids     = '';
		var defval     = '';

		for(i=0;i<document.frmAddProduct.elements.length;i++)
			{ 
					
				if (document.frmAddProduct.elements[i].name.substring(0,20)=='default_category_id_')
				{  
					if(document.frmAddProduct.elements[i].checked==true)
					{
						defval   = document.frmAddProduct.elements[i].value;
					}
				}
				if (document.frmAddProduct.elements[i].type =='hidden' && document.frmAddProduct.elements[i].name=='category_id[]')
				{
					
					cnt++;
					if (ch_ids!='')
						ch_ids += '~';
					 ch_ids += document.frmAddProduct.elements[i].value;
				}
		}
		qrystr += '&defval='+defval;
		qrystr += '&ch_ids='+ch_ids;
		
			if(confirm('Are you sure you want to remove the Category?'))
			{
					fpurpose = 'remove_category';
					document.getElementById('modused').value  						= 'remove';
					document.getElementById('retdiv_id').value 						= 'categorymain_div';
					retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
					retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
					Handlewith_Ajax('services/products.php','fpurpose='+fpurpose+'&cur_prodid='+prodid+'&cur_catid='+id+'&'+qrystr);
		    }
		
		if(document.frmAddProduct.default_category_id.value==id)
		{
		  document.frmAddProduct.default_category_id.value='';
		}

}
jQuery.noConflict();
var $ajax_j = jQuery; 
$ajax_j(function () {
		var top = Math.max($ajax_j(window).height() / 2 - $ajax_j("#moveto_showcategory_div")[0].offsetHeight / 2, 0);
		var left = Math.max($ajax_j(window).width() / 2 - $ajax_j("#moveto_showcategory_div")[0].offsetWidth / 2, 0);
		$ajax_j("#moveto_showcategory_div").css('top', top-275 + "px");
		$ajax_j("#moveto_showcategory_div").css('right', (left-200) + "px");
		$ajax_j("#moveto_showcategory_div").css('position', 'fixed');
	});	
	function showme(id)
	{		
		$ajax_j(id).show();
	}
	function hideme(id)
	{
		$ajax_j(id).hide();
		$ajax_j(id).hide();
	}
</script>
 <div id="popup_bg_div" class="popupbg_fadclass" style="display:none" ></div>
<form action='home.php?request=products' method="post" enctype="multipart/form-data" name='frmAddProduct' onsubmit="return valforms(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=products&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&start=<?php echo $_REQUEST['start']?>&pg=<?php echo $_REQUEST['pg']?>&records_per_page=<?php echo $_REQUEST['records_per_page']?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>">List Products</a><span> Add Product</span></div></td>
        </tr>
       <tr>
		  <td colspan="4" align="left" valign="middle" class="helpmsgtd_main">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>		 </td>
		</tr>
		<?php
			if($alert)
			{
		?>
        <tr>
          <td colspan="4" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
    </tr>
		 <?php
		 	}
		 ?>
         <tr>
           <td colspan="4" align="left" valign="top" class="tdcolorgray" >
            <div id="moveto_showcategory_div" class="processing_divcls_big_heightA" style="display:none" >
			
			</div>
		   <div class="editarea_div">
		   <table width="100%" border="0" cellspacing="0" cellpadding="1">
			<tr>
			<td align="left" valign="top" class="left_top_prodcls">
			<div class="productdet_mainoutercls">
		   <table width="100%" border="0" cellspacing="1" cellpadding="1">
             <tr>
               <td align="left">Product Type </td>
               <td align="left"><select name="product_downloadable_allowed" id="product_downloadable_allowed">
                 <option value="0" <?php  echo ($_REQUEST['product_downloadable_allowed']==0)?'selected':''?>>Normal Product</option>
                 <option value="1" <?php  echo ($_REQUEST['product_downloadable_allowed']==1)?'selected':''?>>Downloadable Product</option>
               </select>
               <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_DOWNALLOW')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
			  </tr>
             <tr>
               <td width="25%" align="left">Product Name&nbsp;<span class="redtext">*</span></td>
               <td align="left"><input name="product_name" type="text" size="30" value="<?php echo $_REQUEST['product_name']?>"  maxlength="100"/></td>
              </tr>
              <tr>
			 <td align="left">Sub Product</td>
             <td align="left"><input type="checkbox" name="product_subproduct" id="product_subproduct" value="1" <?php echo ($_REQUEST['product_subproduct']==1)?' checked="checked"':''?>><a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_SUBPRODUCT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
			 </tr>
			 
               <tr>
               <td align="left">Google Feed Name&nbsp;<span class="redtext">*</span></td>
               <td  align="left" ><input name="prod_googlefeed_name" type="text" size="30" value="<?php echo $_REQUEST['prod_googlefeed_name']?>"  maxlength="100"/></td>

             </tr>
             <tr>
               <td align="left">Product Id </td>
               <td align="left"><input name="manufacture_id" type="text" size="30" value="<?php echo $_REQUEST['manufacture_id'];?>"  maxlength="100"/></td>
              </tr>
             <tr>
               <td align="left">Model</td>
               <td align="left">
               <input name="product_model" type="text" size="40" value="<?php echo $_REQUEST['product_model']?>" maxlength="100"/>			   </td>
              </tr>
              <?php
			 	if($ecom_siteid == 117)
				{
			 ?>              
             <tr>
               <td align="left">Intensive Code</td>
               <td align="left">
               <input name="product_intensivecode" type="text" size="40" value="<?php echo $_REQUEST['product_intensivecode']?>" maxlength="100"/>			   </td>
              </tr>
             <tr>
               <td align="left">Metrodent Code</td>
               <td align="left">
               <input name="product_metrodentcode" type="text" size="40" value="<?php echo $_REQUEST['product_metrodentcode']?>" maxlength="100"/>			   </td>
              </tr>
             <tr>
               <td align="left">ISO Code</td>
               <td align="left">
               <input name="product_isocode" type="text" size="40" value="<?php echo $_REQUEST['product_isocode']?>" maxlength="100"/>			   </td>
              </tr>
			<?php
				}
			 ?>
			 <?php
			if($ecom_site_mobile_api==1)
			{
			?>
			<tr>
			<td align="left">Show In Mobile Application</td>
            <td align="left"><input name="in_mobile_api_sites_prod" type="checkbox" id="in_mobile_api_sites_prod" value="1" <?php echo ($_REQUEST['in_mobile_api_sites_prod']==1)?'checked="checked"':''?>/>
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_MOB_API')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>            
			</td></tr>  
			<?php 
			}
            ?>   
			 <tr>
			  <td  align="left">Barcode</td>
               <td align="left"><input name="product_barcode" type="text" size="37" value="<?php echo $_REQUEST['product_barcode']?>" maxlength="100" />
               <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_BARCODE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
             
			 </tr> 
			 <tr>
			  <td align="left">Fixed Stock </td>
               <td align="left"><input name="product_webstock" type="text" id="product_webstock" <?php echo $_REQUEST['product_webstock']?> size="15" /><a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_FIXES_STK')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
			 </tr>
			 
			 
			 <tr>
			  <td align="left">Hide</td>
               <td align="left"><input type="radio" name="product_hide" value="1" <?php echo ($_REQUEST['product_hide']==1)?'checked="checked"':''?> />
Yes
  <input name="product_hide" type="radio" value="0" checked="checked" <?php echo ($_REQUEST['product_hide']==0)?'checked="checked"':''?> />
No<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
             
			 </tr>
			 <tr>
			  <td align="left">Discontinue</td>
               <td align="left"><input type="radio" name="product_discontinue" value="1" <?php echo ($_REQUEST['product_discontinue']==1)?'checked="checked"':''?> />
Yes
  <input name="product_discontinue" type="radio" value="0" checked="checked" <?php echo ($_REQUEST['product_discontinue']==0)?'checked="checked"':''?> />
No<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
             
			 </tr>
             </table>
			 </div>
			 </td>
			 <td align="left" class="right_top_prodcls" valign="top">
			<div class="productdet_mainoutercls">
			  <table width="100%" border="0" cellspacing="1" cellpadding="1">
				<td colspan="4" class="seperationtd">Categories <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_MAINCAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0"  /></a>
				</td>
			  </tr>
       <tr>
      <td colspan="4"><?=get_help_messages('EDIT_CATEGORY_ASSIGN_DESC')?></td>
      </tr>
      <tr>
      <td colspan="4">
       <div id="categorymain_div">	  
	  </div>
	  <div class="assign_catclass">
		            <input class="red" type="button" onclick="javascript:show_categorypopup()" value="Assign Category" name="Addmorecat_tab" id="Addmorecat_tab">

	  </div>
      </td>
      </tr>
	  </table>
	  </div>
			 </td>
			 </tr>
			 </table>
			  <table width="100%" border="0" cellspacing="1" cellpadding="1">
			 <tr>
				<td align="left" valign="top" colspan="4">
			  <div id="labelmain_div">			  </div>			  </td>
			  </tr>
			  </table>
			  <div class="productdet_mainoutercls">
			 <table width="100%" border="0" cellspacing="1" cellpadding="1">
			  <tr>
               <td width="23%" align="left" class="seperationtd">Descriptions</td>
               <td width="22%" align="left" class="seperationtd">&nbsp;</td>
               <td width="18%" align="left" class="seperationtd">
			   <?php
			   if(is_product_special_product_code_active())
			   {
			   ?>
			   Special Product Code 
			   <?php
			   }
			   ?>			   </td>
               <td width="37%" align="left" class="seperationtd">
			   <?php
			   if(is_product_special_product_code_active())
			   {
			   ?>
			   <input name="product_special_product_code" type="text" id="product_special_product_code" value="<?php echo $_REQUEST['product_special_product_code']?>" />
			   
			    <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_SPECIAL_CODE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.gif" width="17" height="13" border="0" /></a>
				 <?php
			   }
			   ?>
				</td>
             </tr>
             <tr>
               <td align="left">Short Description &nbsp;<span class="redtext">*</span></td>
               <td colspan="3" align="left"><input name="product_shortdesc" type="text" size="104" value="<?php echo $_REQUEST['product_shortdesc']?>" maxlength="1000" /></td>
             </tr>
              <tr>
               <td align="left" valign="top">Google Shopping Description &nbsp;<span class="redtext">*</span></td>
               <td colspan="3" align="left">
				   <textarea name="google_shopping_desc"  cols="60" rows="8"  /><?php echo str_replace('"',"''",stripslashes($_REQUEST['google_shopping_desc']))?></textarea> </td>
             </tr>
			 <tr>
			 <td valign="top" colspan="4">
			 <table width="100%" border="0" cellspacing="1" cellpadding="1">
			  <tr>
               <td> 
			 <table width="100%" border="0" cellspacing="1" cellpadding="1">
			  <tr>
               <td align="left" valign="top" colspan="4">Long Description </td>
			  </tr>
			  <tr> 
               <td align="left">
                 <?php
					//include_once("classes/fckeditor.php");
					$editor_elements = "product_longdesc";
					include_once(ORG_DOCROOT."/console/js/tinymce.php");
					/*$editor = new FCKeditor('product_longdesc') ;
					$editor->BasePath 	= '/console/js/FCKeditor/';
					$editor->Width 		= '650';
					$editor->Height 	= '300';
					$editor->ToolbarSet = 'BshopWithImages';
					$editor->Value 		= stripslashes($_REQUEST['product_longdesc']);
					$editor->Create();*/
				?>
				<textarea style="height:300px; width:650px" id="product_longdesc" name="product_longdesc"><?=stripslashes($_REQUEST['product_longdesc'])?></textarea>				</td>
             </tr>
			 </table>
			 </td>
			 <td valign="top">
			 <table width="100%" border="0" cellspacing="1" cellpadding="1">
			  <tr>
               <td align="left" valign="top">Product keywords &nbsp;<span class="redtext"></span></td>
			   </tr>
			   <tr>
               <td colspan="3" align="left"><textarea name="product_keywords" cols="60" rows="6"><?=$_REQUEST['product_keywords'] ?></textarea>
			   <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_KEYWORDS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
             </tr>
			 </table>
			 </td>
			 </tr>
			 </table>
             
			 </table>
			 </div>
	  		 <table width="100%" border="0" cellspacing="1" cellpadding="1">
             <tr>
               <td colspan="2" width="50%" align="left" valign="top">
				   <table width="100%" border="0" cellspacing="1" cellpadding="1">
				   <tr>
                   <td colspan="3" align="left" >
				    <div class="productdet_mainoutercls">
					   <table width="100%" border="0" cellspacing="0" cellpadding="1">
					    <tr>
               <td colspan="3" align="left" valign="top" class="seperationtd">Price Settings</td>
             </tr>
                 <tr>
                   <td width="40%"  align="left" class="listingtableheader">Branch</td>
                   <td width="30%" align="left" class="listingtableheader">Retail Price </td>
                   <td width="30%" align="left" class="listingtableheader">Cost Price</td>
                 </tr>
				 <?php
				 		// Check whether any branches exists for current site
						$sql_store = "SELECT shop_id,shop_title 
												FROM 
													sites_shops 
												WHERE 
													sites_site_id = $ecom_siteid 
												ORDER BY 
													shop_order";
						$ret_store = $db->query($sql_store);
				 ?>
                 <tr>
                   <td align="left" class="listingtablestyleB"><strong>Web</strong></td>
                   <td align="left" class="listingtablestyleB"><?PHP  $cursymbol = display_curr_symbol();  echo $cursymbol?>
                       <input name="product_webprice" type="text" size="10" value="<?php echo $_REQUEST['product_webprice']?>"  maxlength="100"/>
					   <?php
					   	if($db->num_rows($ret_store))
						{
						?>
					   <img src="images/edit.gif" alt="Copy retail price set for web to other branches also"  title="Clilck to Copy retail price set for web to other branches also" width="16" height="18" onclick="copyretail_to_stores()" />
					   <?php
					   }
					   ?>					   </td>
                   <td align="left" class="listingtablestyleB"><?PHP  $cursymbol = display_curr_symbol();  echo $cursymbol?>
                       <input name="product_costprice" type="text" size="10" value="<?php echo $_REQUEST['product_costprice']?>"  maxlength="100"/></td>
                 </tr>
                 <?php
				 	// Check whether any branches exists for current site
					if($db->num_rows($ret_store))
					{
						$ii=0;
						while ($row_store = $db->fetch_array($ret_store))
						{
							$cls = ($ii%2==0)?'listingtablestyleA':'listingtablestyleB';
							$ii++;
				?>
                 <tr>
                   <td align="left" class="<?php echo $cls?>"><strong><?php echo stripslashes($row_store['shop_title'])?></strong></td>
                   <td colspan="2" align="left" class="<?php echo $cls?>"><?PHP  $cursymbol = display_curr_symbol();  echo $cursymbol?>
                       <input name="product_branch_retailprice_<?php echo $row_store['shop_id']?>" id="product_branch_retailprice_<?php echo $row_store['shop_id']?>" type="text" size="10" value="<?php echo $_REQUEST['product_branch_retailprice_'.$row_store['shop_id']]?>"  maxlength="100"/></td>
                 </tr>
                
                 <?php
						}	
					}
				 ?>
				 </table>
				 </div>
				
				  <div class="productdet_mainoutercls">
					   <table width="100%" border="0" cellspacing="0" cellpadding="0"> 
					   <tr>
               <td  colspan="3" align="left" valign="top" class="seperationtd">Discounts</td>
             </tr>
                 <tr>
                   <td colspan="3" align="left" nowrap="nowrap"><?=get_help_messages('PRODUCT_DISCOUNT_DESC')?></td>
                 </tr>
                 <tr>
                   <td  align="left" nowrap="nowrap"> Discount Type 
                       <?php
					if($row_prod['product_discount_enteredasval']==2) {
						
						echo "<script language='javascript'>
							document.getElementById('extdisc').innerHTML = 'Exact Discount Price';
						</script>";
					}
					$onchange = 'javascript:extdiscchange()';
					$disc_type = array(0=>'%',1=>'Discount Value',2=>'Exact Discount Price');
					$cursymbol = display_curr_symbol();
					echo generateselectbox('product_discount_enteredasval',$disc_type,$row_prod['product_discount_enteredasval'],'',$onchange);
					echo "<input type='hidden' name='hid_cur_sign' value='$cursymbol'>";
				?>
                     <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('PRODUCT_DISCOUNT_RATE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> </td>
                   <td width="22%" align="left" nowrap="nowrap"><span id="extdisc">Discount ( <?PHP echo $cursymbol; 
			    ?> )</span></td>
                   <td width="48%" align="left"><input name="product_discount" type="text" size="8" value="<?php echo $row_prod['product_discount']?>" maxlength="50"/>
                       <?PHP
			  if($row_prod['product_discount_enteredasval']==2) {
						echo "<script language='javascript'>
							document.getElementById('extdisc').innerHTML = 'Exact Discount Price';
						</script>";
					} else if($row_prod['product_discount_enteredasval']==0) {
						echo "<script language='javascript'>
							document.getElementById('extdisc').innerHTML = 'Discount Percentage';
						</script>";	
					} else if($row_prod['product_discount_enteredasval']==1) {
						echo "<script language='javascript'>
							document.getElementById('extdisc').innerHTML = 'Discount Value ('+ document.frmEditProduct.hid_cur_sign.value+' )';
						</script>";	
					}
			  ?>                   </td>
                 </tr>
                 </table>
				 </div>
               
			  <div class="productdet_mainoutercls">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
			 <tr>
               <td colspan="3" align="left" valign="top" class="seperationtd">Bulk Discount </td>
             </tr>
                 <tr>
                   <td width="45%"><input type="checkbox" name="product_bulkdiscount_allowed" value="1" <?php echo ($_REQUEST['product_bulkdiscount_allowed']==1)?'checked="checked"':''?> onclick="handle_bulkdiscount(this)"/>
                     Enable Bulk Discount</td>
                   <td width="55%">&nbsp;</td>
                 </tr>
                 <tr id="bulkdisc_tr" <?php echo ($_REQUEST['product_bulkdiscount_allowed']==1)?'style="display:block"':'style="display:none"'?>>
                   <td colspan="2"><table width="100%" cellpadding="1" cellspacing="1" border="0">
                       <tr>
                         <td width="10%" align="center" class="listingtableheader">#</td>
                         <td width="45%" class="listingtableheader" align="left">Quantity</td>
                         <td width="45%" class="listingtableheader" align="left">Price Per Item </td>
                       </tr>
                       <?php
				  $cnt = 1;
				  // Showing provision for 10 new values to be added
					for($i=0;$i<10;$i++)
					{
						$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
					?>
                       <tr>
                         <td align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
                         <td  class="<?php echo $cls?>" align="left">Atleast
                           <input type="text" name="prodbulknew_qty_<?php echo $i?>" id="prodbulknew_qty_<?php echo $i?>" value="" size="5"/></td>
                         <td  class="<?php echo $cls?>" align="left"><input type="text" name="prodbulknew_price_<?php echo $i?>" id="prodbulknew_price_<?php echo $i?>" value="" size="10"/></td>
                       </tr>
                       <?php
					}
					?>
                   </table></td>
                 </tr>
               </table>
			   </div>
           
			    <div class="productdet_mainoutercls">
			    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
               <td colspan="3" align="left" valign="top" class="seperationtd">Product Deposit </td>
             </tr>
             <tr>
               <td colspan="3" align="left" valign="top">                
                <?=get_help_messages('ADD_PROD_DEP_DESC')?>               </td>
             </tr>
			     <tr>
                   <td width="26%" align="left">Deposit % </td>
                   <td width="74%" align="left"><input name="product_deposit" type="text" size="8" value="<?php echo $_REQUEST['product_deposit']?>" maxlength="100"/>
                     <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_DEPPER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                 </tr>
                 <tr>
                   <td align="left">Message</td>
                   <td align="left"><textarea name="product_deposit_message" cols="25" rows="4"><?php echo $_REQUEST['product_deposit_message']?></textarea>
                     <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_DEPMSG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                 </tr>
               </table>
			   </div>
			    <div class="productdet_mainoutercls">
			   <table width="100%" border="0" cellspacing="1" cellpadding="1">
			  <tr>
               <td colspan="4" align="left" valign="top" class="seperationtd">Vendors</td>
             </tr>
             <tr>
               <td align="left" valign="top">Vendors</td>
               <td align="left" valign="top" colspan="3">
			   <?php
					$vendor_arr = array();
					// Get the list of vendors added for the site
					$sql_vendor = "SELECT vendor_id,vendor_name FROM product_vendors WHERE sites_site_id=$ecom_siteid 
									ORDER BY vendor_name";
					$ret_vendor = $db->query($sql_vendor);
					if ($db->num_rows($ret_vendor))
					{
						while ($row_vendor = $db->fetch_array($ret_vendor))
						{
							$vendorid = $row_vendor['vendor_id'];
							$vendor_arr[$vendorid] = stripslashes($row_vendor['vendor_name']);
						}
					}
					echo generateselectbox('vendor_id[]',$vendor_arr,$_REQUEST['vendor_id'],'','',5);
				?>			   			   
               <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_VENDORS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
             </tr>
             </table>
			   </div>
			    <div class="productdet_mainoutercls">
			   <table width="100%" border="0" cellspacing="0" cellpadding="0">
			    <tr>
               <td colspan="4" align="left" valign="top" class="seperationtd">Image Display Format in Details Page </td>
             </tr>
                 <tr>
                   <td colspan="2">Display format </td>
                   <td width="278" align="left">
				  <?php /*?> 	<select name="product_details_image_type" id="product_details_image_type" onchange="handle_flv(this)">
                       <option value="NORMAL" <?php echo ($_REQUEST['product_details_image_type']=='NORMAL')?'selected':''?>>Images Only</option>
                       <option value="JAVA" <?php echo ($_REQUEST['product_details_image_type']=='JAVA')?'selected':''?>>Images with Video</option>
                       <option value="FLASH" <?php echo ($_REQUEST['product_details_image_type']=='FLASH')?'selected':''?>>Images in  Flash</option>
                       <option value="FLASH_ROTATE" <?php echo ($_REQUEST['product_details_image_type']=='FLASH_ROTATE')?'selected':''?>>Rotating Images Using Flash</option>
                     </select><?php */?>
					 <select name="product_details_image_type" id="product_details_image_type" onchange="handle_flv(this)">
					 <?php
					 	$img_typ_arr = explode(',',$row_theme['product_image_display_format']);
						if (count($img_typ_arr))
						{
							foreach ($img_typ_arr as $k=>$v)
							{
								$showval_arr = explode('=>',$v);
					 ?>
                       			<option value="<?php echo $showval_arr[0]?>" <?php echo ($_REQUEST['product_details_image_type']==$showval_arr[0])?'selected':''?>><?php echo $showval_arr[1]?></option>
					  <?php
					  		}
					  	}
					  ?> 
                     </select>
                   <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_DETIMGTYPE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                   <td  align="left" class="fontredheading">
				   <?php
				   if($_REQUEST['product_details_image_type']=='')
				   	$_REQUEST['product_details_image_type'] = 'NORMAL';
					if($_REQUEST['product_details_image_type']=='NORMAL')
					{
						$normal_disp = '';
					}
					else
						$normal_disp = 'none';
					if($_REQUEST['product_details_image_type']=='JAVA')
					{
						$java_disp = '';
					}
					else
						$java_disp = 'none';
					if($_REQUEST['product_details_image_type']=='FLASH')
					{
						$flash_disp = '';
					}
					else
						$flash_disp = 'none';
					if($_REQUEST['product_details_image_type']=='FLASH_ROTATE')
					{
						$flashrotate_disp = '';
					}
					else
						$flashrotate_disp = 'none';			
					
					echo "
						<div id='NORMAL_dispdiv' style='display:".$normal_disp."'>".stripslashes($row_theme['theme_imgdisplay_normal'])."</div>
						<div id='JAVA_dispdiv' style='display:".$java_disp."'>".stripslashes($row_theme['theme_imgdisplay_video'])."</div>
						<div id='FLASH_dispdiv' style='display:".$flash_disp."'>".stripslashes($row_theme['theme_imgdisplay_flash'])."</div>
						<div id='FLASH_ROTATE_dispdiv' style='display:".$flashrotate_disp."'>".stripslashes($row_theme['theme_imgdisplay_rotate'])."</div>	
						";	
				?>				   </td>
                 </tr>
                 <tr id="flv_tr" style="display:none">
                   <td colspan="2" align="right">Flash video upload (flv) </td>
                   <td colspan="2"><input type="file" name="product_flv_filename" id="product_flv_filename" />
                     <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_FLV')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                 </tr>
                 <script type="text/javascript">
					function handle_addmore_img()
					{
						var curcnt = parseInt(document.getElementById('flv_rotate_cnt').value);
						var content = '';
						var nxtcnt = curcnt+1;
						if (nxtcnt<10)
						{
							content = document.getElementById('flv_rotate_div').innerHTML;
							document.getElementById('flv_rotate_div').innerHTML = content+'<br/>#'+nxtcnt+'&nbsp;&nbsp;&nbsp;';
						}	
						else	
						{
							content = document.getElementById('flv_rotate_div').innerHTML;
							document.getElementById('flv_rotate_div').innerHTML = content+'<br/>#'+nxtcnt+'&nbsp;';
						}	
						content = document.getElementById('flv_rotate_div').innerHTML;
						document.getElementById('flv_rotate_div').innerHTML = content+'<input type="file" name="product_flv_rotate_'+nxtcnt+'" id="product_flv_rotate_'+nxtcnt+'" />'; 
						document.getElementById('flv_rotate_cnt').value = nxtcnt;
					}
				</script>
				 <tr id="flv_rotate_tr" <?php echo ($row_prod['product_details_image_type']!='FLASH_ROTATE')?'style="display:none"':''?>>
				   <td width="38">&nbsp;</td>
                   <td width="145" valign="top" align="right">Select Images <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_ROTATE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                   <td colspan="2">
                       <div id ="flv_rotate_div">
                         <?php 
					 	for ($i=1;$i<19;$i++)
						{
							if($i<10)
								$nb = '&nbsp;&nbsp;&nbsp;';
							else
								$nb = '&nbsp;';
					 ?>
                         <br />
                         #<?php echo $i.$nb?>
                         <input type="file" name="product_flv_rotate_<?php echo $i?>" id="product_flv_rotate_<?php echo $i?>" />
                         <?php
						 }
						 ?>
                       </div>
                     <?php /*?> <div style="float:right">
                         <input type="button" name="add_more_img" id="add_more_img" value="More" onclick="handle_addmore_img()" class="red"/>
                       </div><?php */?>
                       <input type="hidden" name="flv_rotate_cnt" id="flv_rotate_cnt" value="<?php echo ($i-1)?>" />                   </td>
                 </tr>
               </table>
			   </div>
			   </td>
             </tr>  
               </table></td>
               <td colspan="2" align="left" valign="top">
				   <table width="100%" border="0" cellspacing="1" cellpadding="1">
                
             <tr>
             <td colspan="4">
			 <div class="productdet_mainoutercls">
				<table width="100%" border="0" cellspacing="1" cellpadding="1">
                 
				 <tr>
				 <td align="left" colspan="3" class="seperationtd">Price Caption Settings <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_PRICE_CAP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>				 </td>
				 </tr>
				 <tr>
				 <td colspan="3">
				 <table width="100%" cellpadding="1" cellspacing="0" border="0">
				 <tr>
					 <td align="left" class="listingtableheader">&nbsp;</td>
					 <td align="center" class="listingtableheader"><strong>Prefix</strong></td>
					 <td align="center" class="listingtableheader"><strong>Suffix</strong></td>
				 </tr>
				 <?php
					 $cls = ($ii%2==0)?'listingtablestyleA':'listingtablestyleB';
					$ii++;
				 ?>
				 <tr>
					 <td align="left" class="<?php echo $cls?>"><strong>'Normal' Price</strong></td>
					 <td align="center" class="<?php echo $cls?>"><input class="input" name="price_normalprefix" value="<?php echo $_REQUEST['price_normalprefix']?>" type="text"></td>
					 <td align="center" class="<?php echo $cls?>"><input class="input" name="price_normalsuffix" value="<?php echo $_REQUEST['price_normalsuffix']?>" type="text"></td>
				 </tr>
				  <?php
					 $cls = ($ii%2==0)?'listingtablestyleA':'listingtablestyleB';
					$ii++;
				 ?>
				 <tr>
					 <td align="left" class="<?php echo $cls?>"><strong>'From' Price</strong></td>
					 <td align="center" class="<?php echo $cls?>"><input class="input" name="price_fromprefix" value="<?php echo $_REQUEST['price_fromprefix']?>" type="text"></td>
					 <td align="center" class="<?php echo $cls?>"><input class="input" name="price_fromsuffix" value="<?php echo $_REQUEST['price_fromsuffix']?>" type="text"></td>
				 </tr>
				  <?php
					 $cls = ($ii%2==0)?'listingtablestyleA':'listingtablestyleB';
					$ii++;
				 ?>
				 <tr>
					 <td align="left" class="<?php echo $cls?>"><strong>'Special Offer' Price</strong></td>
					 <td align="center" class="<?php echo $cls?>"><input class="input" name="price_specialofferprefix" value="<?php echo $_REQUEST['price_specialofferprefix']?>" type="text"></td>
					 <td align="center" class="<?php echo $cls?>"><input class="input" name="price_specialoffersuffix" value="<?php echo $_REQUEST['price_specialoffersuffix']?>" type="text"></td>
				 </tr>
				  <?php
					 $cls = ($ii%2==0)?'listingtablestyleA':'listingtablestyleB';
					$ii++;
				 ?>
				  <tr>
					 <td align="left" class="<?php echo $cls?>"><strong>'Discount'</strong></td>
					 <td align="center" class="<?php echo $cls?>"><input class="input" name="price_discountprefix" value="<?php echo $_REQUEST['price_discountprefix']?>" type="text"></td>
					 <td align="center" class="<?php echo $cls?>"><input class="input" name="price_discountsuffix" value="<?php echo $_REQUEST['price_discountsuffix']?>" type="text"></td>
				 </tr>
				  <?php
					 $cls = ($ii%2==0)?'listingtablestyleA':'listingtablestyleB';
					$ii++;
				 ?>
				 <tr>
					 <td align="left" class="<?php echo $cls?>"><strong>'You Save' Price</strong></td>
					 <td align="center" class="<?php echo $cls?>"><input class="input" name="price_yousaveprefix" value="<?php echo $_REQUEST['price_yousaveprefix']?>" type="text"></td>
					 <td align="center" class="<?php echo $cls?>"><input class="input" name="price_yousavesuffix" value="<?php echo $_REQUEST['price_yousavesuffix']?>" type="text"></td>
				 </tr>
				  <?php
					 $cls = ($ii%2==0)?'listingtablestyleA':'listingtablestyleB';
					$ii++;
				 ?>
				  <tr>
					 <td align="left" class="<?php echo $cls?>"><strong>'No' Price</strong></td>
					 <td align="center" class="<?php echo $cls?>"><input class="input" name="price_noprice" value="<?php echo $_REQUEST['price_noprice']?>" type="text"></td>
					 <td align="center" class="<?php echo $cls?>">				 </tr>
				 </table>				 </td>
				 </tr>
               </table>
			   	  </div>
				  
				  
                
			  <div class="productdet_mainoutercls">
             <table width="100%" border="0" cellspacing="1" cellpadding="1">
			 <tr>
               <td colspan="4" align="left" valign="top" class="seperationtd">Other Settings</td>
             </tr>
                 <tr>
                   <td width="29%" align="left">Extra Shipping Cost</td>
                   <td width="27%" align="left"><input name="product_extrashippingcost" type="text" size="8" value="<?php echo $_REQUEST['product_extrashippingcost']?>"  maxlength="100"/>
                     <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_EXTRASHIP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                   <td width="44%" align="left"><input type="checkbox" name="product_applytax" value="1" <?php echo ($_REQUEST['product_applytax']==1)?'checked="checked"':''?> />
                     Apply Tax <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_APPLYTAX')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                 </tr>
                 <tr>
                   <td align="left">Bonus Points</td>
                   <td align="left"><input name="product_bonuspoints" type="text" size="8" value="<?php echo $_REQUEST['product_bonuspoints']?>"  maxlength="100"/>
                     <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_BONUSPNTS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                   <td align="left"><input name="product_show_cartlink" type="checkbox" value="1" checked="checked" <?php //echo ($_REQUEST['product_show_cartlink']==1)?'checked="checked"':''?> />
                     Show Buy Link
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_SHOWCART')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                 </tr>
                 <tr>
                   <td align="left">Weight</td>
                   <td align="left"><input name="product_weight" type="text" size="8" value="<?php echo $_REQUEST['product_weight']?>"  maxlength="100"/>
                       <?php echo $gen_arr['unit_of_weight']?>.</td>
                   <td align="left"><input type="checkbox" name="product_show_enquirelink" value="1" checked="checked" <?php //echo ($_REQUEST['product_show_enquirelink']==1)?'checked="checked"':''?>/>
                     Show Enquiry Link <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_SHOWENQ')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                 </tr>
                 <tr>
                   <td colspan="2" align="left">
                     <input type="checkbox" name="product_stock_notification_required" value="1" <?php echo ($_REQUEST['product_stock_notification_required']==1)?'checked="checked"':''?> />
In-Stock Notification allowed <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_INSTOCKREQ')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                   <td align="left"><input type="checkbox" name="product_freedelivery" id="product_freedelivery" value="1" <?php echo ( $_REQUEST['product_freedelivery']==1)?'checked="checked"':''?>/>
Allow Free Delivery <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_FREEDELIVERY')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> </td>
                 </tr>
                 <tr>
                   <td align="left" colspan="2"><input type="checkbox" name="product_saleicon_show" id="product_saleicon_show" value="1" <?php echo ( $row_prod['product_saleicon_show']==1)?'checked="checked"':''?> onclick="return handle_product_sale_icon(this)"/> Show Product Sale Icon <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SHOW_PROD_SALEICON')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                   <td align="left"><input type="checkbox" name="product_show_pricepromise" id="product_show_pricepromise" value="1" <?php echo ( $_REQUEST['product_show_pricepromise']==1)?'checked="checked"':''?>/>
Show Price Promise <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SHOW_PROD_PRICEPROMISE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                 </tr>
		    <tr id="product_saleicon_text_id"  style="display:none">
            <td align="right">Sale Icon Text</td>
            <td align="left" colspan="2"> <textarea name="product_saleicon_text" id="product_saleicon_text" rows="3" cols="40"><?php echo $row_prod['product_saleicon_text'] ?></textarea><a href="#" onmouseover ="ddrivetip('<?=get_help_messages('')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> </td>
          </tr>
				 <tr>
                   <td align="left" colspan="2"><input type="checkbox" name="product_newicon_show" id="product_newicon_show" value="1" <?php echo ( $row_prod['product_newicon_show']==1)?'checked="checked"':''?> onclick="return handle_product_new_icon(this)"/> Show product New Icon <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SHOW_PROD_NEWICON')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                     <td align="left"><input name="product_hide_on_nostock" type="checkbox" id="product_hide_on_nostock" value="1" <?php echo ($_REQUEST['product_hide_on_nostock']==1)?'checked':''?>/>
Hide product when out of stock <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_NOSTOCK_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                   </tr>
                   <tr>
                     <td align="left" colspan="3">
						 <input type="checkbox" name="product_alloworder_notinstock" id="product_alloworder_notinstock" value="1" <?php echo ($_REQUEST['product_alloworder_notinstock']==1)?'checked':''?> onclick="handle_alwaysaddtocart()" />
Allow ordering even if out of stock <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_NOSTOCK_ALLOWCART')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                   </tr>
                    <tr id="orderoutstock_tr1" <?php echo ($_REQUEST['product_alloworder_notinstock']=='Y')?'':'style="display:none"'?>>
			<td align="left" colspan="3">
			<table width="100%" border="0" cellspacing="1" cellpadding="1">
				<tr>
					<td width="5%">&nbsp;</td>
			<td width="20%">Instock Date</td>
			<td align="left" width="25%">
			<?php
			if ($_REQUEST['product_alloworder_notinstock']=='Y')
			{
			$orderoutindate_arr = explode("-",$_REQUEST['product_order_outstock_instock_date']);
			$orderoutindate		= $orderoutindate_arr[2]."-".$orderoutindate_arr[1]."-".$orderoutindate_arr[0];
			}	
			?>
			<input id ="product_order_outstock_instock_date" name="product_order_outstock_instock_date" type="text" size="15" value="<?php echo $orderoutindate?>"  <?php if ($_REQUEST['product_alloworder_notinstock']=='Y') echo 'class="normal_class"'; else echo 'class="disabled_class" disabled="disabled"';?>  readonly="true"/>			</td>
			<td  align="left" width="20%">			
			<span class="fontblacknormal"><a href="javascript:if (document.getElementById('product_alloworder_notinstock').checked) {show_calendar('frmAddProduct.product_order_outstock_instock_date');}" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a></span>&nbsp;(dd-mm-yyyy)</td>
			<td ><img src="images/cleardate.gif"  border="0" onclick="clear_outofstockdate()" title="Clear Date" alt = "Clear Date" /></td>
			</tr>
			</table>			</td>
          </tr>
				 <tr id="product_newicon_text_id" style="display:none">
            <td align="right">New Icon Text</td>
            <td align="left" colspan="2"> <textarea name="product_newicon_text" rows="3" cols="40"><?php echo $row_prod['product_newicon_text'] ?></textarea><a href="#" onmouseover ="ddrivetip('<?=get_help_messages('')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> </td>
           </tr>
                 <tr>
                   <td align="left">Qty box Caption</td>
                   <td align="left"><input name="product_det_qty_caption" type="text" id="product_det_qty_caption" value="<?php echo $_REQUEST['product_det_qty_caption']?>" size="12"/>
                     <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_DET_QTY_CAPTION')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
					 <td>&nbsp;</td>
					  <td>&nbsp;</td>
                </tr>
                 <tr>
                   <td align="left">
				   <?php
		   	$qty_type_disp = ($_REQUEST['product_det_qty_type']=='DROP')?'':'none';
		   ?>
Qty box Type</td>
                   <td align="left"><select name="product_det_qty_type" id="product_det_qty_type" onchange="handle_qty_more_options(this)">
                     <option value="NOR" <?php echo ($_REQUEST['product_det_qty_type']=='NOR')?'selected="selected"':''?>>Textbox</option>
                     <option value="DROP"<?php echo ($_REQUEST['product_det_qty_type']=='DROP')?'selected="selected"':''?>>Drop Down Box</option>
                   </select>
                     <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_DET_QTY_TYPE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                   <td align="left">&nbsp;</td>
                 </tr>
                 <tr>
                   <td colspan="3" align="left"><table width="100%" border="0" cellpadding="1" cellspacing="1" id="qty_more_box" style="display:<?php echo $qty_type_disp?>">
                     <tr>
                       <td>Please specify the values to be displayed in drop down box in this box seperated by comma (,) <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_DET_QTY_DROP_VAL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                       <td><textarea name="product_det_qty_drop_values" id="product_det_qty_drop_values" cols="30" rows="2"><?php echo $_REQUEST['product_det_qty_drop_values']?></textarea></td>
                     </tr>
                     <tr>
                       <td>Prefix to be used with values in drop down box<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_DET_QTY_DROP_PREFIX')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                       <td><input name="product_det_qty_drop_prefix" type="text" id="product_det_qty_drop_prefix" value="<?php echo $_REQUEST['product_det_qty_drop_prefix']?>" size="26" /></td>
                     </tr>
                     <tr>
                       <td width="54%" align="left">Suffix to be used with values in drop down box <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_DET_QTY_DROP_SUFFFIX')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                       <td width="46%" align="left"><input name="product_det_qty_drop_suffix" type="text" id="product_det_qty_drop_suffix" value="<?php echo $_REQUEST['product_det_qty_drop_suffix']?>" size="26" /></td>
                     </tr>
                   </table></td>
                 </tr>

               </table>
			   </div>
			   <div class="productdet_mainoutercls">
				 <table width="100%" border="0" cellspacing="1" cellpadding="1">
				 <tr>
               <td colspan="4" align="left" valign="top" class="seperationtd">Preorder</td>
             </tr>
                 <tr>
                   <td colspan="4" align="left"><?=get_help_messages('ADD_PROD_PREORDER_WHAT')?>
                     &nbsp;</td>
                 </tr>
				 
                 <tr>
                   <td colspan="4" align="left"><input type="checkbox" name="product_preorder_allowed" id="product_preorder_allowed" value="1" <?php echo ($_REQUEST['product_preorder_allowed']==1)?'checked="checked"':''?> onclick="handle_preorder()"/>
                     Allow Preorder </td>
                 </tr>
                 <tr id="preorder_tr1" <?php echo ($_REQUEST['product_preorder_allowed']==1)?'':'style="display:none"'?>>
                   <td align="left" width="5%">&nbsp;</td>
                   <td  align="left">Total  Preorder allowed</td>
                   <td  align="left" colspan="2"><input name="product_total_preorder_allowed" type="text" size="8" value="<?php echo $_REQUEST['product_total_preorder_allowed']?>" class="disabled_class" disabled="disabled"/>
                     <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_TOTAL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                 </tr>
                 <tr id="preorder_tr2" <?php echo ($_REQUEST['product_preorder_allowed']==1)?'':'style="display:none"'?>>
                   <td align="left"width="5%">&nbsp;</td>
                   <td align="left">Instock Date</td>
                   <td  align="left" width="22%"><input name="product_instock_date" type="text" size="15" value="<?php echo $_REQUEST['product_instock_date']?>"  class="disabled_class" disabled="disabled" /></td>
                   <td  align="left" width="42%"><span class="fontblacknormal"><a href="javascript:if (document.getElementById('product_preorder_allowed').checked) {show_calendar('frmAddProduct.product_instock_date');}" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a></span>&nbsp;(dd-mm-yyyy) &nbsp;&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_INSTK_DATE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                 </tr>
                 <tr id="preorder_tr2" <?php echo ($_REQUEST['product_preorder_allowed']==1)?'':'style="display:none"'?>>
                       <td align="left">&nbsp;</td>

                   <td align="left">&nbsp;</td>
                   <td align="left">&nbsp;</td>
                   <td align="left">&nbsp;</td>
                 </tr>
                 </table>
				 </div>
             </td>
             </tr>
                 
                 
               
               </table></td>
             </tr>
             
                        
             <tr>
               <td colspan="2" align="left" valign="top">
				   </td>
               <td colspan="2" align="left" valign="top"></td>
             </tr>
            
             <tr>
               <td colspan="4" align="left" valign="top">
			   
			   </td>
             </tr>
           </table></div></td>
         </tr>
         
        <tr>
          <td colspan="4" align="center" valign="middle" class="tdcolorgray" >
			<div class="editarea_div">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
			 <tr>
			   <td width="100%" align="right" valign="top" class="tdcolorgraynormal" >
				<input type="hidden" name="productname" id="productname" value="<?=$_REQUEST['productname']?>" />
				<input type="hidden" name="manufactureid" id="manufactureid" value="<?=$_REQUEST['manufactureid']?>" />
				<input type="hidden" name="categoryid" id="categoryid" value="<?=$_REQUEST['categoryid']?>" />
				<input type="hidden" name="vendorid" id="vendorid" value="<?=$_REQUEST['vendorid']?>" />
				<input type="hidden" name="rprice_from" id="rprice_from" value="<?=$_REQUEST['rprice_from']?>" />
				<input type="hidden" name="rprice_to" id="rprice_to" value="<?=$_REQUEST['rprice_to']?>" />
				<input type="hidden" name="cprice_from" id="cprice_from" value="<?=$_REQUEST['cprice_from']?>" />
				<input type="hidden" name="cprice_to" id="cprice_to" value="<?=$_REQUEST['cprice_to']?>" />
				<input type="hidden" name="discount" id="discount" value="<?=$_REQUEST['discount']?>" />
				<input type="hidden" name="discountas" id="discountas" value="<?=$_REQUEST['discountas']?>" />
				<input type="hidden" name="bulkdiscount" id="bulkdiscount" value="<?=$_REQUEST['bulkdiscount']?>" />
				<input type="hidden" name="stockatleast" id="stockatleast" value="<?=$_REQUEST['stockatleast']?>" />
				<input type="hidden" name="preorder" id="preorder" value="<?=$_REQUEST['preorder']?>" />
				<input type="hidden" name="prodhidden" id="prodhidden" value="<?=$_REQUEST['prodhidden']?>" />
				<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
				<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
				<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
				<input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
				<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
				<input type="hidden" name="parent_id" id="parent_id" value="<?=$_REQUEST['parent_id']?>" />
				<input type="hidden" name="retdiv_id" id="retdiv_id" value="maincontent" />
				<input type="hidden" name="fpurpose" id="fpurpose" value="save_add" />
			  <input name="prod_Submit" type="submit" class="red" value="Save" />			  
			          <input type="hidden" name="modused" id="modused" value="" />
		<input type="hidden" name="default_category_id" id="default_category_id" value="" />

			  </td>
			 </tr>
			 </table>
			 </div>			</td>
        </tr>
        <tr>
          <td width="24%" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td colspan="3" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
        </tr>
  </table>
</form>	  

