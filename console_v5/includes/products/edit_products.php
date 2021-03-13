<?php
	/*#################################################################
	# Script Name 	: edit_products.php
	# Description 		: Page for edition Products
	# Coded by 		: Sny
	# Created on		: 28-June-2007
	# Modified by		: Sny
	# Modified On		: 22-Sep-2008
	#################################################################*/
	


	//Define constants for this page
	$page_type 		= 'Products';
	$help_msg 		= get_help_messages('EDIT_PROD1');
	// Get the details of current product from products table
	$sql_prod 		= "SELECT * FROM products WHERE product_id=$edit_id and sites_site_id=$ecom_siteid";
	$ret_prod 		= $db->query($sql_prod);
	if ($db->num_rows($ret_prod))
	{
		$row_prod = $db->fetch_array($ret_prod);
	}
	else
	{
		echo "<span class='redtext'>Invalid Product Request</span>";
		exit;
	}
	// Get some settings from general setting for this site
	$gen_arr 	= get_general_settings('product_maintainstock,epos_available','general_settings_sites_common');
	$curtab		= ($_REQUEST['curtab'])?$_REQUEST['curtab']:'main_tab_td';
	
	

	
?>	
<script language="javascript" type="text/javascript">
function show_varvalues(valID)
{
	if(document.getElementById('check_refine_'+valID).checked == true)
	{
		document.getElementById('show_refine_'+valID).style.display = 'block';
	}
	else
	{
		document.getElementById('show_refine_'+valID).style.display = 'none';
	}
}
function check_refine_val(varID,valID,valTyp)
{
	var curVal,newCurVal;
	
	curVal	=	document.getElementById(valTyp+'_'+varID).value;
	
	if(valTyp == 'chkbox_type')
	{
		if(document.getElementById('check_item_'+valID).checked == true)
		{
			newCurVal	=	curVal+valID+'#';
			document.getElementById(valTyp+'_'+varID).value	=	newCurVal;
		}
		else
		{
			newCurVal	=	curVal.replace(valID+'#', ''); 
			document.getElementById(valTyp+'_'+varID).value	=	newCurVal;
		}
	}
	else if(valTyp == 'box_type')
	{
		if(document.getElementById('box_item_'+valID).checked == true)
		{
			newCurVal	=	curVal+valID+'#';
			document.getElementById(valTyp+'_'+varID).value	=	newCurVal;
		}
		else
		{
			newCurVal	=	curVal.replace(valID+'#', ''); 
			document.getElementById(valTyp+'_'+varID).value	=	newCurVal;
		}
	}
	
}
function save_catvars(checkboxname,val)
{
	var atleastone 			= 0;
	var numricrange			= 0;
	var orngrange			= 0;
	var lhincorrect			= 0;
	var catid				= document.getElementById('defaultcat_id').value;
	var prodid				= '<?php echo $_REQUEST['checkbox'][0]?>';
	var ch_ids 				= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= 'save_carvar_msg';
	//var moredivid			= 'prodimgunassign_'+combid+'_div';
	var fpurpose			= 'save_prodcatvars';
	var confirmval = '';
	var cntr = 0;
	
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmEditProduct.elements.length;i++)
	{
		if (document.frmEditProduct.elements[i].type =='checkbox' && document.frmEditProduct.elements[i].name== checkboxname)
		{
			var	ch_varid	=	'';
			var ch_vartyp	=	'';
			if (document.frmEditProduct.elements[i].checked==true)
			{
				atleastone	=	1;
				ch_varid	=	document.frmEditProduct.elements[i].value;
				ch_vartyp	=	document.getElementById('chek_refine_type_'+ch_varid).value;
				
				if (ch_ids!='' || cntr>0)
					ch_ids += '~';
					
				 ch_ids += ch_varid;
				 ch_ids += '#'+ch_vartyp+'#';
				 
				 if(ch_vartyp == 'CHECKBOX')
				 {
				 	ch_ids += document.getElementById('chkbox_type_'+ch_varid).value;
				 }
				 if(ch_vartyp == 'BOX')
				 {
				 	ch_ids += document.getElementById('box_type_'+ch_varid).value;
				 }
				 if(ch_vartyp == 'RANGE')
				 {
				 	var lowVal	=	parseFloat(document.getElementById('var_lowval_'+ch_varid).value);
					var highVal	=	parseFloat(document.getElementById('var_highval_'+ch_varid).value);					
					var lowNum	=	isNaN(lowVal);
					var highNum	=	isNaN(highVal);
					
				 	var lowOrgVal	=	parseFloat(document.getElementById('var_lowval_org_'+ch_varid).value);
					var highOrgVal	=	parseFloat(document.getElementById('var_highval_org_'+ch_varid).value);
					
					if(lowNum == false && highNum == false)
					{
						if(lowVal>lowOrgVal || highVal<highOrgVal)
						{
							//if(lowVal == highVal == 0)
							if((lowVal == highVal) || (highVal==0))
							{
								ch_ids += document.getElementById('var_lowval_'+ch_varid).value;
								ch_ids += '#'+document.getElementById('var_highval_'+ch_varid).value;
							}
							else if(lowVal < highVal)
							{
								ch_ids += document.getElementById('var_lowval_'+ch_varid).value;
								ch_ids += '#'+document.getElementById('var_highval_'+ch_varid).value;
							}
							else
							{
								lhincorrect = 1;
							}
						}
						else
						{
							orngrange = 1;
						}
					}
					else
					{
						numricrange	=	1;
					}
				 }
				 
				 cntr++;
			}	
		}
	}
	
	if(numricrange == 1)
	{
		alert('Please enter a numeric value for range fields');
	}
	else if(orngrange == 1)
	{
		alert('Please enter range values specified in the message.');
	}
	else if(lhincorrect == 1)
	{
		alert('Please enter low value less than hight value');
	}
	else
	{
		
		if(val != 'def') {
		confirmval = confirm('Are you sure you want to save the category variables?');
		} else {
		confirmval = 1;
		}
		
		if(confirmval)
		{
			if(atleastone == 0)
			{
				fpurpose	=	'delete_prodcatvars';
			}
			document.getElementById('retdiv_id').value 	= retdivid;/* Name of div to show the result */
			retobj 										= eval("document.getElementById('"+retdivid+"')");
			
			//show_img_type								= document.frmEditProduct.productdetail_moreimages_showimagetype.value;
				
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/products.php','fpurpose='+fpurpose+'&cur_prodid='+prodid+'&catid='+catid+'&ch_ids='+ch_ids+'&'+qrystr);
		}	
	}
}
</script>
<script language="javascript" type="text/javascript">
	var pname 			= '<? echo addslashes($_REQUEST['productname'])?>';
	var manid 				= '<?=$_REQUEST['manufactureid']?>';
	var catid 				= '<?=$_REQUEST['categoryid']?>';
	var vendorid 			= '<?=$_REQUEST['vendorid']?>';
	var rprice_from 		= '<?=$_REQUEST['rprice_from']?>';
	var rprice_to 			= '<?=$_REQUEST['rprice_to']?>';
	var cpricefrom 		= '<?=$_REQUEST['cprice_from']?>';
	var cpriceto 			= '<?=$_REQUEST['cprice_to']?>';
	var discount 			= '<?=$_REQUEST['discount']?>';
	var discountas 		= '<?=$_REQUEST['discountas']?>';
	var bulkdiscount 		= '<?=$_REQUEST['bulkdiscount']?>';
	var stockatleast		= '<?=$_REQUEST['stockatleast']?>';
	var preorder 			= '<?=$_REQUEST['preorder']?>';
	var prodhidden 		= '<?=$_REQUEST['prodhidden']?>';
	var in_mobile_api_sites = '<?=$_REQUEST['in_mobile_api_sites']?>';
	var sortby 				= '<?php echo $sort_by?>';
	var sortorder 			= '<?php echo $sort_order?>';
	var recs 				= '<?php echo $records_per_page?>';
	var start				= '<?php echo $start?>';
	var pg 					= '<?php echo $pg?>';
	var maintainstock	= '<?php echo $gen_arr['product_maintainstock']?>';
	/* preloading the image to be shown on loading*/
	pic1= new Image(); 
	pic1.src="images/loading.gif";
	 
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
			norecdiv 	= document.getElementById('retdiv_more').value;
			var mod		= document.getElementById('modused').value;
			targetobj 	= eval("document.getElementById('"+targetdiv+"')");
			targetobj.innerHTML = ret_val; /* Setting the output to required div */
			if(document.getElementById('comb_id').value!='')
			{
				noimgobj = eval("document.getElementById('no_comb_img_"+document.getElementById('comb_id').value+"')");
				if (noimgobj)
				{
					newobj = eval("document.getElementById('no_comb_assign_div_"+document.getElementById('comb_id').value+"')");
					newobj.innerHTML = '<img src="images/no_comb_img.gif" border="0" title="Images  Not assigned for this combination"/>';
				}
				document.getElementById('comb_id').value = '';
			}
			if(document.frmEditProduct.product_variablestock_allowed && document.getElementById('curtab').value=='stock_tab_td')
			{
				handle_varstock(document.frmEditProduct.product_variablestock_allowed);	
			}	
			/* Decide the display of action buttons*/
			switch(targetdiv)
			{
				case 'prodvar_div':
					if(document.getElementById('prodvar_norec'))
					{
						if(document.getElementById('prodvar_norec').value==1)
						{
							disp = 'none';
						}	
						else
							disp = '';
					}
					else
						disp = '';	
				break;
				case 'prodmsg_div':
					if(document.getElementById('prodmsg_norec'))
					{
						if(document.getElementById('prodmsg_norec').value==1)
						{
							disp = 'none';
						}	
						else
							disp = '';
					}
					else
						disp = '';	
				break;
				case 'prodtab_div':
					if(document.getElementById('prodtab_norec'))
					{
						if(document.getElementById('prodtab_norec').value==1)
						{
							disp = 'none';
						}	
						else
							disp = '';
					}
					else
						disp = '';	
				break;
				case 'prodattach_div':
					if(document.getElementById('prodattach_norec'))
					{
						if(document.getElementById('prodattach_norec').value==1)
						{
							disp = 'none';
						}	
						else
							disp = '';
					}
					else
						disp = '';	
				break;
				case 'prodlink_div':
					if(document.getElementById('prodlink_norec'))
					{
						if(document.getElementById('prodlink_norec').value==1)
						{
							disp = 'none';
						}	
						else
							disp = '';
					}
					else
						disp = '';	
				break;
				case 'stock_div':
					if(document.getElementById('stock_norec'))
					{
						if(document.getElementById('stock_norec').value==1)
						{
							disp = 'none';
						}	
						else
							disp = '';
					}
					else
						disp = '';
						
				break;
				case 'moveto_showcategory_div':				
					document.getElementById('popup_bg_div').style.display='';
					document.getElementById('moveto_showcategory_div').style.display='';						
				break;
				case 'categorymain_div':
				category_click_labels_load();
				break;	
				case 'catvar_div':
					if(document.getElementById('catvar_norec'))
					{
						if(document.getElementById('catvar_norec').value==1)
						{
							disp = 'none';
						}	
						else
							disp = '';
					}
					else
						disp = '';	
				break;

			};
			if (disp!='no')
			{ 
				norecobj 	= eval("document.getElementById('"+norecdiv+"')");
				if (norecobj)
					norecobj.style.display = disp;
			}	
			hide_processing();
		}
		else
		{
			 show_request_alert(req.status);
			//alert("Problem in requesting XML :"+req.statusText);
		}
	}
}
function valforms(frm)
{
	if(document.getElementById('curtab').value!='desc_tab_td')
	{
	var atleastone 		= false;
	fieldRequired 		= Array('product_name','product_shortdesc');
	fieldDescription 	= Array('Product Name','Short Description');
	fieldEmail 			= Array();
	fieldConfirm 		= Array();
	fieldConfirmDesc  	= Array();
	fieldSpecChars = Array('product_barcode');
	fieldNumeric 		= Array('product_webprice','product_costprice','product_total_preorder_allowed','product_deposit','product_extrashippingcost','product_weight','product_bonuspoints','product_reorderqty','product_discount');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric,fieldSpecChars)) {
			/* Check whether category is selected*/
			obj = document.getElementById('category_id[]');
			/*
			if(obj.length==0)
			{
				alert('Category is required');
				return false;
			}
			else
			{*/	

				/*
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
				*/
				var atleast = false;
				var id=0;
				for(i=0;i<document.frmEditProduct.elements.length;i++)
				{
					if (document.frmEditProduct.elements[i].type =='hidden' && document.frmEditProduct.elements[i].name=='category_id[]')
					{
							atleast = true;
							id = document.frmEditProduct.elements[i].value;
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
				/*
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
			if(document.frmEditProduct.product_preorder_allowed.value==1)
			{
				 if(document.frmEditProduct.product_total_preorder_allowed.value<0)
				 {
				 alert('Total Preorder should be a positive value');
						return false;
				 }
			}
			/* Check whether instock date is specified */
			if(document.frmEditProduct.product_preorder_allowed.checked)
			{
				if(document.frmEditProduct.product_total_preorder_allowed.value=='')
				{
					alert('Total Preorder allowed not specified');
					return false;
				}
				if(document.frmEditProduct.product_instock_date.value=='' || document.frmEditProduct.product_instock_date.value=='00-00-0000')
				{
					alert('Instock Date allowed not specified');
					return false;
				}
			}	
			
			if (document.frmEditProduct.product_discount.value<0)
			{
				alert('Discount value should be positive');
				return false;
			}
			if(document.frmEditProduct.product_discount_enteredasval.value==0)
			{
				if (document.frmEditProduct.product_discount.value>=100)
				{
					alert('Discount % should be less than 100');
					return false;
				}
			}
			if(document.frmEditProduct.product_discount_enteredasval.value==1 || document.frmEditProduct.product_discount_enteredasval.value==2)
			{ 
			var disc = parseFloat(document.frmEditProduct.product_discount.value);
			var web =  parseFloat(document.frmEditProduct.product_webprice.value);
				if(disc>0 && web>0)
				{
					if (web <= disc)
					{
						alert('Discount value should be less than webprice');
						return false;
					}
				}
			}
			if (document.frmEditProduct.product_deposit.value>=100 || document.frmEditProduct.product_deposit.value<0)
			{
				alert('Deposit % should be less than 100 and a positive value');
				document.frmEditProduct.product_deposit.focus();
				return false;
			}
			if (document.frmEditProduct.product_extrashippingcost.value<0)
			{
				alert('Extra shipping cost should be a positive value');
				return false;
			}
			if (document.frmEditProduct.product_bonuspoints.value<0)
			{
				alert('Bonus points should be a Positive value');
				return false;
			}
			if (document.frmEditProduct.product_weight.value<0)
			{
				alert('Weight should be a Positive value');
				return false;
			}
			var pr_retail =0;
			var pr_peritem = 0;
			var pr_peritem_numeric =0;
			for(var i=0; i < frm.elements.length; i++)
			{ 
			  if(frm.elements[i].name.substr(0,17)=='prodbulknew_price' || frm.elements[i].name.substr(0,14)=='prodbulk_price')
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
			if (document.frmEditProduct.product_webprice.value<0 || pr_retail==1)
			{
				alert('Retail Price should be a Positive value');
					document.frmEditProduct.product_webprice.focus();
				return false;
			}
			if (document.frmEditProduct.product_costprice.value<0)
			{
				alert('Cost Price should be a Positive value');
				document.frmEditProduct.product_costprice.focus();
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
			document.getElementById('retdiv_id').value = 'maincontent';
			show_processing();
			return true;
		}	
		else
		{
			return false;
		}
	}
	else
		return true;
}
function handle_expansionall(imgobj,mod)
{
	var src 			= imgobj.src;
	var retindx 		= src.search('plus.gif');
	switch(mod)
	{
		case 'prodvar': /* Case of product variables*/
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('variable_tr'))
					document.getElementById('variable_tr').style.display = '';
				//if(document.getElementById('varunassign_div'))
				//	document.getElementById('varunassign_div').style.display = '';	
				call_ajax_showlistall('prodvar',pname,manid,catid,vendorid,rprice_from,rprice_to,cpricefrom,cpriceto,discount,discountas,bulkdiscount,stockatleast,preorder,prodhidden,in_mobile_api_sites,sortby,sortorder,recs,start,pg)
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('variable_tr'))
					document.getElementById('variable_tr').style.display = 'none';
				if(document.getElementById('varunassign_div'))
					document.getElementById('varunassign_div').style.display = 'none';
			}	
		break;
		case 'prodmsg': // Case of product messages
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('varmsg_tr'))
					document.getElementById('varmsg_tr').style.display = '';
				//if(document.getElementById('varmsgunassign_div'))
				//	document.getElementById('varmsgunassign_div').style.display = '';	
				call_ajax_showlistall('prodmsg',pname,manid,catid,vendorid,rprice_from,rprice_to,cpricefrom,cpriceto,discount,discountas,bulkdiscount,stockatleast,preorder,prodhidden,in_mobile_api_sites,sortby,sortorder,recs,start,pg)
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('varmsg_tr'))
					document.getElementById('varmsg_tr').style.display = 'none';
				if(document.getElementById('varmsgunassign_div'))
					document.getElementById('varmsgunassign_div').style.display = 'none';
			}	
		break;
		case 'prodtab': // Case of product tabs
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('tab_tr'))
					document.getElementById('tab_tr').style.display = '';
				//if(document.getElementById('tabunassign_div'))
				//	document.getElementById('tabunassign_div').style.display = '';	
				call_ajax_showlistall('prodtab',pname,manid,catid,vendorid,rprice_from,rprice_to,cpricefrom,cpriceto,discount,discountas,bulkdiscount,stockatleast,preorder,prodhidden,in_mobile_api_sites,sortby,sortorder,recs,start,pg)
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('tab_tr'))
					document.getElementById('tab_tr').style.display = 'none';
				if(document.getElementById('tabunassign_div'))
					document.getElementById('tabunassign_div').style.display = 'none';
			}	
		break;
		case 'prodattach': // Case of product attachment
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('prodattach_tr'))
					document.getElementById('prodattach_tr').style.display = '';
				//if(document.getElementById('tabunassign_div'))
				//	document.getElementById('tabunassign_div').style.display = '';	
				call_ajax_showlistall('prodattach',pname,manid,catid,vendorid,rprice_from,rprice_to,cpricefrom,cpriceto,discount,discountas,bulkdiscount,stockatleast,preorder,prodhidden,in_mobile_api_sites,sortby,sortorder,recs,start,pg)
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('prodattach_tr'))
					document.getElementById('prodattach_tr').style.display = 'none';
				if(document.getElementById('prodattachunassign_div'))
					document.getElementById('prodattachunassign_div').style.display = 'none';
			}	
		break;
		case 'prodlink': // Case of linked products
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('prodlink_tr'))
					document.getElementById('prodlink_tr').style.display = '';
				//if(document.getElementById('prodlinkunassign_div'))
				//	document.getElementById('prodlinkunassign_div').style.display = '';	
				call_ajax_showlistall('prodlink',pname,manid,catid,vendorid,rprice_from,rprice_to,cpricefrom,cpriceto,discount,discountas,bulkdiscount,stockatleast,preorder,prodhidden,in_mobile_api_sites,sortby,sortorder,recs,start,pg)
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('prodlink_tr'))
					document.getElementById('prodlink_tr').style.display = 'none';
				if(document.getElementById('prodlinkunassign_div'))
					document.getElementById('prodlinkunassign_div').style.display = 'none';
			}	
		break;
		case 'prodstock': // Case of stock section
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('stock_tr'))
					document.getElementById('stock_tr').style.display = '';
				//if(document.getElementById('prodlinkunassign_div'))
					//document.getElementById('prodlinkunassign_div').style.display = '';	
				call_ajax_showlistall('prodstock',pname,manid,catid,vendorid,rprice_from,rprice_to,cpricefrom,cpriceto,discount,discountas,bulkdiscount,stockatleast,preorder,prodhidden,in_mobile_api_sites,in_mobile_api_sites,sortby,sortorder,recs,start,pg)
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('stock_tr'))
					document.getElementById('stock_tr').style.display = 'none';
				if(document.getElementById('stockunassign_div'))
					document.getElementById('stockunassign_div').style.display = 'none';
			}	
		break;
		case 'sizechart_values': // case of size chart values display
			if (document.getElementById('mainerror_tr'))
				document.getElementById('mainerror_tr').style.display = 'none';
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('sizechart_tr'))
					document.getElementById('sizechart_tr').style.display = '';
	
				call_ajax_showlistall('prod_sizechartvalues',pname,manid,catid,vendorid,rprice_from,rprice_to,cpricefrom,cpriceto,discount,discountas,bulkdiscount,stockatleast,preorder,prodhidden,in_mobile_api_sites,sortby,sortorder,recs,start,pg)
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('sizechart_tr'))
					document.getElementById('sizechart_tr').style.display = 'none';
			}	
		break;
		case 'prodcombo': // Case of product combo details
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('combo_tr'))
					document.getElementById('combo_tr').style.display = '';
				call_ajax_showlistall('prodcombo',pname,manid,catid,vendorid,rprice_from,rprice_to,cpricefrom,cpriceto,discount,discountas,bulkdiscount,stockatleast,preorder,prodhidden,in_mobile_api_sites,sortby,sortorder,recs,start,pg)
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('combo_tr'))
					document.getElementById('combo_tr').style.display = 'none';
			}	
		break;
		case 'prodshelf': // Case of product shelf details
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('linkshelf_tr'))
					document.getElementById('linkshelf_tr').style.display = '';
				call_ajax_showlistall('prodshelf',pname,manid,catid,vendorid,rprice_from,rprice_to,cpricefrom,cpriceto,discount,discountas,bulkdiscount,stockatleast,preorder,prodhidden,in_mobile_api_sites,sortby,sortorder,recs,start,pg)
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('linkshelf_tr'))
					document.getElementById('linkshelf_tr').style.display = 'none';
			}	
		break;
		case 'prodshop': // Case of product shop details
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('linkshop_tr'))
					document.getElementById('linkshop_tr').style.display = '';
				call_ajax_showlistall('prodshop',pname,manid,catid,vendorid,rprice_from,rprice_to,cpricefrom,cpriceto,discount,discountas,bulkdiscount,stockatleast,preorder,prodhidden,in_mobile_api_sites,sortby,sortorder,recs,start,pg)
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('linkshop_tr'))
					document.getElementById('linkshop_tr').style.display = 'none';
			}	
		break;
		case 'prodcustgroup': // Case of customer group details
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('linkcustgroup_tr'))
					document.getElementById('linkcustgroup_tr').style.display = '';
				call_ajax_showlistall('prodcustgroup',pname,manid,catid,vendorid,rprice_from,rprice_to,cpricefrom,cpriceto,discount,discountas,bulkdiscount,stockatleast,preorder,prodhidden,in_mobile_api_sites,sortby,sortorder,recs,start,pg)
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('linkcustgroup_tr'))
					document.getElementById('linkcustgroup_tr').style.display = 'none';
			}	
		break;
		case 'prodpromo': // Case of promotional code details
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('linkpromo_tr'))
					document.getElementById('linkpromo_tr').style.display = '';
				call_ajax_showlistall('prodpromo',pname,manid,catid,vendorid,rprice_from,rprice_to,cpricefrom,cpriceto,discount,discountas,bulkdiscount,stockatleast,preorder,prodhidden,in_mobile_api_sites,sortby,sortorder,recs,start,pg)
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('linkpromo_tr'))
					document.getElementById('linkpromo_tr').style.display = 'none';
			}	
		break;
		case 'common_settings': // Case of promotional code details
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('common_tr'))
					document.getElementById('common_tr').style.display = '';
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('common_tr'))
					document.getElementById('common_tr').style.display = 'none';
			}	
		break;
		case 'direct_settings': // Case of promotional code details
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('direct_tr'))
					document.getElementById('direct_tr').style.display = '';
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('direct_tr'))
					document.getElementById('direct_tr').style.display = 'none';
			}	
		break;
		case 'prodlinked': // Case of linked products expansion
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('linkedprod_tr'))
					document.getElementById('linkedprod_tr').style.display = '';
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('linkedprod_tr'))
					document.getElementById('linkedprod_tr').style.display = 'none';
			}
		break;
		case 'subprods': // Case of subproducts products expansion
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('subprod_tr'))
					document.getElementById('subprod_tr').style.display = '';
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('subprod_tr'))
					document.getElementById('subprod_tr').style.display = 'none';
			}
		break;
		/*case 'catvars': // Case of product category variable
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('catvars_tr'))
					document.getElementById('catvars_tr').style.display = '';
				//if(document.getElementById('prodlinkunassign_div'))
				//	document.getElementById('prodlinkunassign_div').style.display = '';	
				call_ajax_showlistall('catvars',pname,manid,catid,vendorid,rprice_from,rprice_to,cpricefrom,cpriceto,discount,discountas,bulkdiscount,stockatleast,preorder,prodhidden,in_mobile_api_sites,sortby,sortorder,recs,start,pg)
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('catvars_tr'))
					document.getElementById('catvars_tr').style.display = 'none';
				if(document.getElementById('catvarsunassign_div'))
					document.getElementById('catvarsunassign_div').style.display = 'none';
			}	
		break;*/
	};
}
function go_editall(id,fpurpose)
{
	document.frmEditProduct.fpurpose.value 	= fpurpose;
	document.frmEditProduct.edit_id.value 	= id;
	document.frmEditProduct.submit();
}
function go_editall_general(id)
{
	if(confirm('This is a Common Product Tab.\n\n Are you sure you want to go to the Common Product Tab edit page?'))
	{
		show_processing();
		window.location = 'home.php?request=common_prod_tab&fpurpose=edit&checkbox[0]='+id;		
	}
}
function go_editall_generalattach(id)
{
	if(confirm('This is a Common Product Attachment.\n\n Are you sure you want to go to the Common Product Attachment edit page?'))
	{
		show_processing();
		window.location = 'home.php?request=common_prod_attachment&fpurpose=edit&checkbox[0]='+id;		
	}
}
function handle_qty_more_options(obj)
{
	if (obj.value=='NOR')
		document.getElementById('qty_more_box').style.display = 'none';
	else
		document.getElementById('qty_more_box').style.display = '';
}
function call_ajax_showlistall(mod,pname,manid,catid,vendorid,rprice_from,rprice_to,cpricefrom,cpriceto,discount,discountas,bulkdiscount,stockatleast,preorder,prodhidden,in_mobile_api_sites,sortby,sortorder,recs,start,pg)
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
		case 'prodvar': // Case of product variables
			retdivid   	= 'prodvar_div';
			moredivid	= 'varunassign_div';
			fpurpose	= 'list_prodvar';
		break;
		case 'prodmsg': // Case of product messages
			retdivid   	= 'prodmsg_div';
			moredivid	= 'varmsgunassign_div';
			fpurpose	= 'list_prodmsg';
		break;
		case 'prodtab': // Case of product tab
			retdivid   	= 'prodtab_div';
			moredivid	= 'tabunassign_div';
			fpurpose	= 'list_prodtab';
		break;
		case 'prodattach': // Case of product attachments
			retdivid   	= 'prodattach_div';
			moredivid	= 'prodattachunassign_div';
			fpurpose	= 'list_prodattach';
		break;
		case 'prodlink': // Case of product link
			retdivid   	= 'main_product_div';
			moredivid	= 'prodlinkunassign_div';
			fpurpose	= 'list_prodlink';
		break;
		case 'prodstock': // Case of product stock
			retdivid   	= 'stock_div';
			moredivid	= 'stockunassign_div';
			fpurpose	= 'list_prodstock';
		break;
		case 'prod_sizechartvalues':
			retdivid   	= 'sizechart_div';
			fpurpose	= 'list_sizechartvalues';
		break;
		case 'prodcombo':
			retdivid   	= 'combo_div';
			fpurpose	= 'list_prodcombo';
		break;
		case 'prodshelf':
			retdivid   	= 'linkshelf_div';
			fpurpose	= 'list_prodshelf';
		break;
		case 'prodshop':
			retdivid   	= 'linkshop_div';
			fpurpose	= 'list_prodshop';
		break;
		case 'prodcustgroup':
			retdivid   	= 'linkcustgroup_div';
			fpurpose	= 'list_prodcustgroup';
		break;
		case 'prodpromo':
			retdivid   	= 'linkpromo_div';
			fpurpose	= 'list_prodpromo';
		break;
		case 'prodlabels':
			retdivid   	= 'labelmain_div';
			fpurpose	= 'list_labels_block';
			catobj = document.getElementById('category_id[]');
			var cat_str = '';
			var method = document.getElementById('modused').value;
			for(i=0;i<document.frmEditProduct.elements.length;i++)
			{
				if(method=='assign')
				{
					/*if (document.frmEditProduct.elements[i].type =='checkbox' && document.frmEditProduct.elements[i].name== 'checkbox_assigncat[]')
					{
						if (document.frmEditProduct.elements[i].checked==true)
						{
							atleastone ++;
							if (cat_str!='')
								cat_str += '~';
							 cat_str += document.frmEditProduct.elements[i].value;
						} 
					}
					*/
					if (document.frmEditProduct.elements[i].type =='hidden' && document.frmEditProduct.elements[i].name== 'category_id[]')
					{						
							atleastone ++;
							if (cat_str!='')
								cat_str += '~';
							 cat_str += document.frmEditProduct.elements[i].value;
					}
				}
				else if(method=='remove')
				{
					if (document.frmEditProduct.elements[i].type =='hidden' && document.frmEditProduct.elements[i].name== 'category_id[]')
					{						
							atleastone ++;
							if (cat_str!='')
								cat_str += '~';
							 cat_str += document.frmEditProduct.elements[i].value;
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
		break;	
		case 'catvars': // Case of product category variables
			retdivid   	= 'catvar_div';
			fpurpose	= 'list_catvar';
		break;
	};
	document.getElementById('retdiv_id').value 		= retdivid;/* Name of div to show the result */	
	document.getElementById('retdiv_more').value 	= moredivid;/* Name of div to show the result */
	retobj 										= eval("document.getElementById('"+retdivid+"')");
	retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';															
	/* Calling the ajax function */
	Handlewith_Ajax('services/products.php','fpurpose='+fpurpose+'&'+qrystr+'&cur_prodid='+prod_id);
}	
function category_click_labels_load()
{
	call_ajax_showlistall('prodlabels',pname,manid,catid,vendorid,rprice_from,rprice_to,cpricefrom,cpriceto,discount,discountas,bulkdiscount,stockatleast,preorder,prodhidden,in_mobile_api_sites,sortby,sortorder,recs,start,pg)
}
function delete_rotate_image(del_index)
{
	var prodid 			= '<?php echo $edit_id?>';
	var fpurpose 		= 'delete_prodrotate';
	var retdivid			= 'flv_rotate_exist_div';
	if (confirm('Are you sure you want to delete the image?') )
	{
		document.getElementById('retdiv_id').value 	= retdivid;/* Name of div to show the result */
		retobj 										= eval("document.getElementById('"+retdivid+"')");
		retobj.innerHTML 						= '<center><img src="images/loading.gif" alt="Loading"></center>';
		Handlewith_Ajax('services/products.php','fpurpose='+fpurpose+'&cur_prodid='+prodid+'&cur_index='+del_index);
	}
}
function call_ajax_deleteall(mod,checkboxname)
{
	var atleastone 			= 0;
	var prodid				= '<?php echo $_REQUEST['checkbox'][0]?>';
	var del_ids 			= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid					= '';
	var fpurpose			= '';
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmEditProduct.elements.length;i++)
	{
		if (document.frmEditProduct.elements[i].type =='checkbox' && document.frmEditProduct.elements[i].name==checkboxname)
		{

			if (document.frmEditProduct.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmEditProduct.elements[i].value; 
			}	
		}
	}
	switch(mod)
	{
		case 'prodvar': // Case of product variables
			atleastmsg 	= 'Please select the product variable(s) to be deleted';
			if (maintainstock==1) /* Check whether stock is maintained for this product */
			{
				confirmmsg 	= 'When the selected variable(s) are removed, all the variable combinations for this product will also be removed. \n\n Are you sure you want to delete the selected Product Variable(s)?';
			}
			else
			{
				confirmmsg 	= 'Are you sure you want to delete the selected Product Variable(s)?';
			}	
			retdivid   	= 'main_product_div';
			moredivid	= 'varunassign_div';
			fpurpose	= 'delete_prodvar';
			if(document.getElementById('cat_imgtagstock'))
				document.getElementById('cat_imgtagstock').src = 'images/plus.gif';
			if(document.getElementById('stock_tr'))
				document.getElementById('stock_tr').style.display = 'none';
			if(document.getElementById('stockunassign_div'))
				document.getElementById('stockunassign_div').style.display = 'none';
		break;
		case 'prodvid': // Case of product messages
			atleastmsg 	= 'Please select the product video(s) to be deleted';
			confirmmsg 	= 'Are you sure you want to delete the selected Product Videos Message(s)?'
			retdivid   	= 'main_product_div';
			moredivid	= 'varunassign_div';
			fpurpose	= 'delete_prodvid';
		break;
		case 'prodmsg': // Case of product messages
			atleastmsg 	= 'Please select the product variable message(s) to be deleted';
			confirmmsg 	= 'Are you sure you want to delete the selected Product Variable Message(s)?'
			retdivid   	= 'prodmsg_div';
			moredivid	= 'varmsgunassign_div';
			fpurpose	= 'delete_prodmsg';
		break;
		case 'prodtab': // Case of product tabs
			atleastmsg 	= 'Please select the product tab(s) to be deleted';
			confirmmsg 	= 'Are you sure you want to delete the selected Product Tab(s)?'
			retdivid   	= 'prodtab_div';
			moredivid	= 'tabunassign_div';
			fpurpose	= 'delete_prodtab';
		break;
		case 'prodlink': // Case of linked products
			atleastmsg 	= 'Please select the linked product(s) to be Unassigned';
			confirmmsg 	= 'Are you sure you want to Unassign the selected Linked Product(s)?'
			retdivid   	= 'main_product_div';
			moredivid	= 'prodlinkunassign_div';
			fpurpose	= 'delete_prodlink';
		break;
		case 'subprod': // Case of sub products
			atleastmsg 	= 'Please select the sub product(s) to be Unassigned';
			confirmmsg 	= 'Are you sure you want to Unassign the selected  Sub Product(s)?'
			retdivid   	= 'main_product_div';
			moredivid	= 'prodlinkunassign_div';
			fpurpose	= 'delete_subprod';
		break;
		case 'prodimg': // Images assigned to products
			atleastmsg 	= 'Please select the image(s) to be Unassigned';
			confirmmsg 	= 'Are you sure you want to Unassign the selected Image(s)?'
			retdivid   	= 'main_product_div';
			moredivid	= 'prodimgunassign_div';
			fpurpose	= 'unassign_prodimagedetails';
		break;
		case 'googleprodimg': // Images assigned to products
			atleastmsg 	= 'Please select the image(s) to be Unassigned';
			confirmmsg 	= 'Are you sure you want to Unassign the selected Image(s)?'
			retdivid   	= 'main_product_div';
			moredivid	= 'prodimgunassign_div';
			fpurpose	= 'unassign_googleprodimagedetails';
		break;
		case 'prodattach': // case of product attachments
			atleastmsg  = 'Please select the attachment(s) to be Deleted';
			confirmmsg = 'Are you sure you want to Delete the selected Attachment(s)?'
			retdivid   	= 'prodattach_div';
			moredivid	= 'prodattachunassign_div';
			fpurpose	= 'delete_prodattach';
		break;
	<?php /*?>	case 'prodbulk':
			atleastmsg 	= 'Please select the bulk discount value(s) to be Deleted';
			confirmmsg 	= 'Are you sure you want to Delete the selected bulk discount value(s)?'
			retdivid   	= 'main_product_div';
			moredivid	= 'prodbulkunassign_div';
			fpurpose	= 'delete_prodbulk';
		break;<?php */?>
		case 'proddownload': // case of product downloadable
			atleastmsg 	 = 'Please select the downloadable(s) to be Deleted';
			confirmmsg = 'Are you sure you want to Delete the selected downloadable(s)?'
			retdivid   	 = 'main_product_div';
			moredivid	 = '';
			fpurpose	 = 'delete_proddownload';
		break;
		case 'prodflv': // case of product flash delete
			atleastone 		= 1;
			confirmmsg 	= 'Are you sure you want to Delete the flv file for this product?'
			retdivid   	 	= 'main_product_div';
			moredivid	 	= '';
			fpurpose	 	= 'delete_prodflv';
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
			if (moredivid!='')
				document.getElementById('retdiv_more').value= moredivid;/* Name of div to show the result */
			retobj 										= eval("document.getElementById('"+retdivid+"')");
			retobj.innerHTML 						= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/products.php','fpurpose='+fpurpose+'&cur_prodid='+prodid+'&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
function call_ajax_delete_combimg(mod,checkboxname,divid,combid)
{
	var atleastone 			= 0;
	var prodid					= '<?php echo $_REQUEST['checkbox'][0]?>';
	var del_ids 				= '';
	var qrystr					= '';
	var atleastmsg 			= 'Please select the image(s) to be unassigned from current combination';
	var confirmmsg 			= 'Are you sure you want to unassigned the selected image(s) from current combination?';
	var retdivid					= divid;
	var fpurpose				= 'unassign_prodcomboimagedetails';
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmEditProduct.elements.length;i++)
	{
		if (document.frmEditProduct.elements[i].type =='checkbox' && document.frmEditProduct.elements[i].name==checkboxname)
		{

			if (document.frmEditProduct.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmEditProduct.elements[i].value; 
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
			document.getElementById('comb_id').value = combid;
			/*if (moredivid!='')
				document.getElementById('retdiv_more').value= moredivid;*/ /* Name of div to show the result */
			retobj 										= eval("document.getElementById('"+retdivid+"')");
			retobj.innerHTML 						= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/products.php','fpurpose='+fpurpose+'&cur_prodid='+prodid+'&combid='+combid+'&str='+divid+'&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
function call_ajax_changestatusprodall(mod,checkboxname)
{
	var atleastone 			= 0;
	var prodid				= '<?php echo $_REQUEST['checkbox'][0]?>';
	var ch_ids 				= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmEditProduct.elements.length;i++)
	{
		if (document.frmEditProduct.elements[i].type =='checkbox' && document.frmEditProduct.elements[i].name==checkboxname)
		{

			if (document.frmEditProduct.elements[i].checked==true)
			{
				atleastone = 1;
				if (ch_ids!='')
					ch_ids += '~';
				 ch_ids += document.frmEditProduct.elements[i].value;
			}	
		}
	}
	switch(mod)
	{
		case 'prodvar': // Case of product variables
			atleastmsg 	= 'Please select the product variables to change the status';
			confirmmsg 	= 'Are you sure you want to Change the Status of selected Product Variable(s)?';
			retdivid   	= 'main_product_div';
			moredivid	= 'varunassign_div';
			fpurpose	= 'changestat_prodvar';
			var chstat	= document.getElementById('prodvar_chstatus').value;
		break;
		case 'prodvid': // Case of product variables
			atleastmsg 	= 'Please select the product videos to change the status';
			confirmmsg 	= 'Are you sure you want to Change the Status of selected Product Video(s)?';
			retdivid   	= 'main_product_div';
			moredivid	= 'varunassign_div';
			fpurpose	= 'changestat_prodvid';
			var chstat	= document.getElementById('prodvid_chstatus').value;
		break;
		case 'prodmsg': // Case of product messages
			atleastmsg 	= 'Please select the product variable messages to change the status';
			confirmmsg 	= 'Are you sure you want to Change the Status of selected Product Variable Message(s)?';
			retdivid   	= 'prodmsg_div';
			moredivid	= 'varmsgunassign_div';
			fpurpose	= 'changestat_prodmsg';
			var chstat	= document.getElementById('prodmsg_chstatus').value;
		break;
		case 'prodtab': // Case of product tabs
			atleastmsg 	= 'Please select the product tabs to change the status';
			confirmmsg 	= 'Are you sure you want to Change the Status of selected Product Tab(s)?';
			retdivid   	= 'prodtab_div';
			moredivid	= 'tabunassign_div';
			fpurpose	= 'changestat_prodtab';
			var chstat	= document.getElementById('prodtab_chstatus').value;
		break;
		case 'prodlink': // Case of linked products
			atleastmsg 	= 'Please select the linked product(s) to change the status';
			confirmmsg 	= 'Are you sure you want to Change the Status of selected Linked Product(s)?';
			retdivid   	= 'main_product_div';
			moredivid	= 'prodlinkunassign_div';
			fpurpose	= 'changestat_prodlink';
			var chstat	= document.getElementById('prodlink_chstatus').value;
		break;
		case 'prodattach': // Case of product attachments
			atleastmsg 	= 'Please select the attachment(s) to change the status';
			confirmmsg 	= 'Are you sure you want to Change the Status of selected Attachment(s)?';
			retdivid   	= 'prodattach_div';
			moredivid	= 'prodattachunassign_div';
			fpurpose	= 'changestat_prodattach';
			var chstat	= document.getElementById('prodattach_chstatus').value;
		break;
		case 'proddownload': // Case of product downloadables
			atleastmsg 	= 'Please select the downloadable(s) to change the status';
			confirmmsg = 'Are you sure you want to Change the Status of selected downloadable(s)?';
			retdivid   	= 'main_product_div';
			moredivid	= '';
			fpurpose	= 'changestat_proddownload';
			var chstat	= document.getElementById('proddownload_chstatus').value;
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
			retobj 										= eval("document.getElementById('"+retdivid+"')");
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/products.php','fpurpose='+fpurpose+'&chstat='+chstat+'&cur_prodid='+prodid+'&ch_ids='+ch_ids+'&'+qrystr);
		}	
	}	
}
function call_ajax_changeorderall(mod,checkboxname)
{
	var atleastone 			= 0;
	var prodid				= '<?php echo $_REQUEST['checkbox'][0]?>';
	var ch_ids 				= '';
	var ch_order			= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';
	var orderbox			= '';
	var show_in             = '';
	var ch_showin           = '';   
	switch(mod)
	{
		case 'prodvar': // Case of product variables
			atleastmsg 	= 'Please select the product variables to change the Order';
			confirmmsg 	= 'Are you sure you want to Change the Order of selected Product Variable(s)?';
			retdivid   	= 'main_product_div';
			moredivid	= 'varunassign_div';
			fpurpose	= 'changeorder_prodvar';
			orderbox	= 'prodvar_order_';
		break;
		case 'prodvid': // Case of product variables
			atleastmsg 	= 'Please select the product videos to change the Order';
			confirmmsg 	= 'Are you sure you want to Change the Order of selected Product Video(s)?';
			retdivid   	= 'main_product_div';
			moredivid	= 'varunassign_div';
			fpurpose	= 'changeorder_prodvid';
			orderbox	= 'prodvid_order_';
		break;
		case 'prodmsg': // Case of product messages
			atleastmsg 	= 'Please select the product variable message to change the order';
			confirmmsg 	= 'Are you sure you want to Change the order of selected Product Variable Message(s)?';
			retdivid   	= 'prodmsg_div';
			moredivid	= 'varmsgunassign_div';
			fpurpose	= 'changeorder_prodmsg';
			orderbox	= 'prodmsg_order_';
		break;
		case 'prodtab': // Case of product tabs
			atleastmsg 	= 'Please select the product tabs to change the order';
			confirmmsg 	= 'Are you sure you want to Change the Order of selected Product Tab(s)?';
			retdivid   	= 'prodtab_div';
			moredivid	= 'tabunassign_div';
			fpurpose	= 'changeorder_prodtab';
			orderbox	= 'prodtab_order_';
		break;
		case 'prodlink': // Case of linked products
			atleastmsg 	= 'Please select the linked product(s) to change the details';
			confirmmsg 	= 'Are you sure you want to Change the details of selected Linked Product(s)?';
			retdivid   	= 'main_product_div';
			moredivid	= 'prodlinkunassign_div';
			fpurpose	= 'changeorder_prodlink';
			orderbox	= 'prodlink_order_';
			show_in     = 'show_in_';
		break;
		case 'subprod': // Case of Sub products
			atleastmsg 	= 'Please select the sub product(s) to change the Sort order';
			confirmmsg 	= 'Are you sure you want to Change the sort order of selected Sub Product(s)?';
			retdivid   	= 'main_product_div';
			moredivid	= 'prodlinkunassign_div';
			fpurpose	= 'changeorder_subprod';
			orderbox	= 'subprod_order_';
			show_in     = 'show_in_';
		break;
		case 'prodattach': // Case of product attachments
			atleastmsg 	= 'Please select the attachment(s) to change the order';
			confirmmsg 	= 'Are you sure you want to Change the Order of selected Attachment(s)?';
			retdivid   	= 'prodattach_div';
			moredivid	= 'prodattachunassign_div';
			fpurpose	= 'changeorder_prodattach';
			orderbox	= 'prodattach_order_';
		break;
		case 'proddownload': // Case of product downloadables
			atleastmsg 	= 'Please select the downloadable(s) to change the order';
			confirmmsg 	= 'Are you sure you want to Change the Order of selected Downloadable(s)?';
			retdivid   	= 'main_product_div';
			moredivid	= '';
			fpurpose	= 'changeorder_proddownload';
			orderbox	= 'proddownload_order_';
		break;
	}
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmEditProduct.elements.length;i++)
	{
		if (document.frmEditProduct.elements[i].type =='checkbox' && document.frmEditProduct.elements[i].name== checkboxname)
		{

			if (document.frmEditProduct.elements[i].checked==true)
			{
				atleastone = 1;
				if (ch_ids!='')
					ch_ids += '~';
				 ch_ids += document.frmEditProduct.elements[i].value;
				 
				 obj = eval("document.getElementById('"+orderbox+document.frmEditProduct.elements[i].value+"')");
				 
				if (ch_order != '')
					ch_order += '~';
				 ch_order += obj.value; 
				 if(document.getElementById(show_in+document.frmEditProduct.elements[i].value))
				 {
					obj_new = eval("document.getElementById('"+show_in+document.frmEditProduct.elements[i].value+"')");
					 
					if (ch_showin != '')
						ch_showin += '~';
					 ch_showin += obj_new.value; 
					 
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
		if(confirm(confirmmsg))
		{
			document.getElementById('retdiv_id').value 	= retdivid;/* Name of div to show the result */
			retobj 										= eval("document.getElementById('"+retdivid+"')");
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/products.php','fpurpose='+fpurpose+'&cur_prodid='+prodid+'&ch_order='+ch_order+'&ch_showin='+ch_showin+'&ch_ids='+ch_ids+'&'+qrystr);
		}	
	}	
}

function call_ajax_changesubproduct(mod,checkboxname)
{
	var atleastone 			= 0;
	var prodid				= '<?php echo $_REQUEST['checkbox'][0]?>';
	var ch_ids 				= '';
	var ch_order			= '';
	var ch_name				= '';
	var ch_price			= '';
	var ch_tax				= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';
	var orderbox			= '';
	var namebox           	= ''; 
	var pricebox           	= ''; 
	var taxbox           	= '';   

	switch(mod)
	{
		case 'subprod': // Case of Sub products
			atleastmsg 	= 'Please select the sub product(s) to change the dtails';
			confirmmsg 	= 'Are you sure you want to save the details of selected Sub Product(s)?';
			retdivid   	= 'main_product_div';
			moredivid	= 'prodlinkunassign_div';
			fpurpose	= 'changeorder_subprod';
			orderbox	= 'subprod_order_';
			namebox     = 'subproduct_name_';
			pricebox    = 'subproduct_price_';
			taxbox      = 'subproduct_applytax_';
		break;
	}
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmEditProduct.elements.length;i++)
	{
		if (document.frmEditProduct.elements[i].type =='checkbox' && document.frmEditProduct.elements[i].name== checkboxname)
		{

			if (document.frmEditProduct.elements[i].checked==true)
			{
				atleastone = 1;
				if (ch_ids!='')
					ch_ids += '~';
				 ch_ids += document.frmEditProduct.elements[i].value;
				 
				 obj = eval("document.getElementById('"+orderbox+document.frmEditProduct.elements[i].value+"')");
				 
				if (ch_order != '')
					ch_order += '~';
				 ch_order += ' '+obj.value; 
				 
				 if(document.getElementById(namebox+document.frmEditProduct.elements[i].value))
				 {
					obj_new = eval("document.getElementById('"+namebox+document.frmEditProduct.elements[i].value+"')");
					if(obj_new.value=='')
					{
						alert ('Please enter the name');
						obj_new.focus();
						return false;
					} 
					if (ch_name != '')
						ch_name += '~';
					 ch_name += ' '+encodeURIComponent(encodeURI(obj_new.value)); 
				 }
				 if(document.getElementById(pricebox+document.frmEditProduct.elements[i].value))
				 {
					obj_new1 = eval("document.getElementById('"+pricebox+document.frmEditProduct.elements[i].value+"')");
					 
					if (ch_price != '')
						ch_price += '~';
					 ch_price +=  ' '+obj_new1.value; 
					 
				 }
				 
				 
				if (ch_tax != '')
					ch_tax += '~';
				if(document.getElementById(taxbox+document.frmEditProduct.elements[i].value))
				{
					obj_new2 = eval("document.getElementById('"+taxbox+document.frmEditProduct.elements[i].value+"')");		
					if(obj_new2.checked==true)
					{
						ch_tax += 'Y'; 
					}
					else
					{
						ch_tax += 'N'; 
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
		if(confirm(confirmmsg))
		{
			document.getElementById('retdiv_id').value 	= retdivid;/* Name of div to show the result */
			retobj 										= eval("document.getElementById('"+retdivid+"')");
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/products.php','fpurpose='+fpurpose+'&cur_prodid='+prodid+'&ch_order='+ch_order+'&ch_name='+ch_name+'&ch_price='+ch_price+'&ch_tax='+ch_tax+'&ch_ids='+ch_ids+'&'+qrystr);
		}	
	}	
}


function call_ajax_changevariablestock(mod)
{
	var atleastone 								= 0;
	var prodid									= '<?php echo $_REQUEST['checkbox'][0]?>';
	var ch_ids 									= '';
	var ch_variable								= '';
	var qrystr									= '';
	var atleastmsg 								= '';
	var confirmmsg 								= '';
	var retdivid								= 'main_product_div';
	var moredivid								= 'stockunassign_div';
	var fpurpose								= 'stock_mainstoreonchange';
	var main_store								= document.getElementById('main_store').value;
	document.getElementById('retdiv_id').value 	= retdivid;/* Name of div to show the result */
	retobj 										= eval("document.getElementById('"+retdivid+"')");
	retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
	Handlewith_Ajax('services/products.php','fpurpose='+fpurpose+'&cur_prodid='+prodid+'&main_store='+main_store+'&'+qrystr);
}	
function call_ajax_savedescription()
{
	document.getElementById('retdiv_id').value 	= 'main_product_div';/* Name of div to show the result */
	document.frmEditProduct.fpurpose.value 		= 'save_edit_desc';
	document.frmEditProduct.submit();
}	
function call_ajax_saveimagedetails(checkboxname,val)
{
	var atleastone 			= 0;
	var prodid				= '<?php echo $_REQUEST['checkbox'][0]?>';
	var ch_ids 				= '';
	var ch_variable		= '';
	var qrystr				= '';
	var atleastmsg 		= '';
	var confirmmsg 		= '';
	var retdivid				= 'main_product_div';
	var moredivid			= 'prodimgunassign_div';
	var fpurpose			= 'save_prodimagedetails';
	var ch_order			= '';
	var ch_title			= '';
	var show_img_type		= '';
	var confirmval = '';
	var cntr = 0;
	
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmEditProduct.elements.length;i++)
	{
		if (document.frmEditProduct.elements[i].type =='checkbox' && document.frmEditProduct.elements[i].name== checkboxname)
		{

			if (document.frmEditProduct.elements[i].checked==true)
			{
				atleastone = 1;
				if (ch_ids!='' || cntr>0)
					ch_ids += '~';
				 ch_ids += document.frmEditProduct.elements[i].value;
				 
				 obj1 = eval("document.getElementById('img_ord_"+document.frmEditProduct.elements[i].value+"')");
				 obj2 = eval("document.getElementById('img_title_"+document.frmEditProduct.elements[i].value+"')");
				 
				if (ch_order != '' || cntr>0)
					ch_order += '~';
				 ch_order += obj1.value; 
				 
				 if (ch_title != '' || cntr>0)
					ch_title += '~';
				 ch_title += obj2.value; 
				 cntr++;
			}	
		}
	}
	
	if (atleastone==0 && val != 'def')
	{
		alert('Please select the image(s) to be saved');
	}
	else
	{
		if(val != 'def') {
		confirmval = confirm('Are you sure you want to save the title and order of selected images?');
		} else {
		confirmval = 1;
		}
		
		if(confirmval)
		{
			document.getElementById('retdiv_id').value 	= retdivid;/* Name of div to show the result */
			retobj 										= eval("document.getElementById('"+retdivid+"')");
			
			//show_img_type								= document.frmEditProduct.productdetail_moreimages_showimagetype.value;
				
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/products.php','fpurpose='+fpurpose+'&cur_prodid='+prodid+'&ch_order='+ch_order+'&ch_title='+ch_title+'&ch_ids='+ch_ids+'&'+qrystr+'&imgsav='+val);
		}	
	}
}
function call_ajax_savecomboimagedetails(checkboxname,divid,combid,val)
{
	var atleastone 			= 0;
	var prodid				= '<?php echo $_REQUEST['checkbox'][0]?>';
	var ch_ids 				= '';
	var ch_variable		= '';
	var qrystr				= '';
	var atleastmsg 		= '';
	var confirmmsg 		= '';
	var retdivid				= divid;
	var moredivid			= 'prodimgunassign_'+combid+'_div';
	var fpurpose			= 'save_prodcomboimagedetails';
	var ch_order			= '';
	var ch_title			= '';
	var show_img_type		= '';
	var confirmval = '';
	var cntr = 0;
	
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmEditProduct.elements.length;i++)
	{
		if (document.frmEditProduct.elements[i].type =='checkbox' && document.frmEditProduct.elements[i].name== checkboxname)
		{

			if (document.frmEditProduct.elements[i].checked==true)
			{
				atleastone = 1;
				if (ch_ids!='' || cntr>0)
					ch_ids += '~';
				 ch_ids += document.frmEditProduct.elements[i].value;
				 
				 obj1 = eval("document.getElementById('img_ord_"+combid+"_"+document.frmEditProduct.elements[i].value+"')");
				 obj2 = eval("document.getElementById('img_title_"+combid+"_"+document.frmEditProduct.elements[i].value+"')");
				 
				if (ch_order != '' || cntr>0)
					ch_order += '~';
				 ch_order += obj1.value; 
				 
				 if (ch_title != '' || cntr>0)
					ch_title += '~';
				 ch_title += obj2.value; 
				 cntr++;
			}	
		}
	}
	
	if (atleastone==0 && val != 'def')
	{
		alert('Please select the image(s) to be saved');
	}
	else
	{
		if(val != 'def') {
		confirmval = confirm('Are you sure you want to save the title and order of selected images?');
		} else {
		confirmval = 1;
		}
		
		if(confirmval)
		{
			document.getElementById('retdiv_id').value 	= retdivid;/* Name of div to show the result */
			retobj 										= eval("document.getElementById('"+retdivid+"')");
			
			//show_img_type								= document.frmEditProduct.productdetail_moreimages_showimagetype.value;
				
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/products.php','fpurpose='+fpurpose+'&cur_prodid='+prodid+'&combid='+combid+'&ch_order='+ch_order+'&ch_title='+ch_title+'&ch_ids='+ch_ids+'&'+qrystr+'&str='+divid);
		}	
	}
}
function call_ajax_savevariabledisplaydetails()
{
	var atleastone 			= 0;
	var prodid				= '<?php echo $_REQUEST['checkbox'][0]?>';
	var retdivid			= 'main_product_div';
	var moredivid		= 'varunassign_div';
	var fpurpose			= 'save_prodvardisplaytypedetails';
	obj 						= document.frmEditProduct.product_variable_display_type;
	obj2						= document.frmEditProduct.product_variable_in_newrow;
	var val					= '';
	var newrow			= 0;
	if(obj2.checked==true)
		newrow = 1;
	else
		newrow = 0;
	if (obj)
	{
		if(!obj[0].checked && !obj[1].checked)
		{
			alert('Please select the display type for the variable');
			return false;
		}
		if (obj[0].checked)
			val = 'ADD';
		else
			val = 'FULL';
	}		
	if(confirm('Are you sure you want to save more options?'))
	{
		document.getElementById('retdiv_id').value 	= retdivid;/* Name of div to show the result */
		retobj 										= eval("document.getElementById('"+retdivid+"')");
		retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
		Handlewith_Ajax('services/products.php','fpurpose='+fpurpose+'&cur_prodid='+prodid+'&disp_type='+val+'&newrow='+newrow);
	}	
}
function save_combo_bulk_disc(comboid,div_id)
{
	var prodid				= '<?php echo $_REQUEST['checkbox'][0]?>';
	var fpurpose			= 'save_combo_bulk_disc';
	var cbulkqty			= cbulkprice = '';
	var cbulkqty_new	= cbulkprice_new = '';
	var bulk_id				= '';
	var b_qty = b_price = '';
	var str					= '';
	var curlen				= 0;
	var name_arr			= new Array();
	var cnt					= '';
	if(document.getElementById('product_variablecomboprice_allowed').checked==true)
	{
		for(i=0;i<document.frmEditProduct.elements.length;i++)
		{
			str 		= 'prodbulk_qty_'+comboid+'_';
			curlen 	= str.length;
			if (document.frmEditProduct.elements[i].type=='text' && document.frmEditProduct.elements[i].name.substr(0,curlen)==str)
			{
				name_arr = document.frmEditProduct.elements[i].name.split('_');
				cnt			= name_arr[2]+'_'+name_arr[3];
				b_qty		= document.getElementById('prodbulk_qty_'+cnt).value;
				b_price		= document.getElementById('prodbulk_price_'+cnt).value;
				
				if(cbulkqty!='')
					cbulkqty += '~';
				if(cbulkprice!='')
					cbulkprice += '~';
				if(bulk_id!='')
					bulk_id += '~';	
				cbulkqty += ' '+b_qty;
				cbulkprice += ' '+b_price;	
				bulk_id		+= ' '+name_arr[3];
			}
			
			str 		= 'prodbulknew_qty_'+comboid+'_';
			curlen 	= str.length;
			if (document.frmEditProduct.elements[i].type=='text' && document.frmEditProduct.elements[i].name.substr(0,curlen)==str)
			{
				name_arr = document.frmEditProduct.elements[i].name.split('_');
				cnt			= name_arr[2]+'_'+name_arr[3];
				b_qty		= document.getElementById('prodbulknew_qty_'+cnt).value;
				b_price		= document.getElementById('prodbulknew_price_'+cnt).value;
				if(cbulkqty_new!='')
					cbulkqty_new += '~';
				if(cbulkprice_new!='')
					cbulkprice_new += '~';
				cbulkqty_new += ' '+b_qty;
				cbulkprice_new += ' '+b_price;	
			}
			
		}
		document.getElementById('retdiv_id').value 	= div_id;/* Name of div to show the result */
		retobj 										= eval("document.getElementById('"+div_id+"')");
		retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
		Handlewith_Ajax('services/products.php','fpurpose='+fpurpose+'&cur_prodid='+prodid+'&comboid='+comboid+'&cbulkqty='+cbulkqty+'&cbulkprice='+cbulkprice+'&bulkid='+bulk_id+'&cbulkqty_new='+cbulkqty_new+'&cbulkprice_new='+cbulkprice_new+'&pass_str='+div_id);
	}
	else
	{
		alert('Sorry!! Combination price not allowed for this product');
		return false;
	}
}

function call_ajax_savestock()/* Function to save stock */
{
	var atleastone 		= 0;
	var prodid				= '<?php echo $_REQUEST['checkbox'][0]?>';
	var ch_ids 				= '';
	var ch_variable		= '';
	var qrystr				= '';
	var atleastmsg 		= '';
	var confirmmsg 		= '';
	var retdivid				= 'main_product_div';
	var moredivid			= 'stockunassign_div';
	var fpurpose			= 'save_prodvarstock';
	var varcnt				= document.frmEditProduct.var_cnt.value;
	var main_store			= document.getElementById('main_store').value;
	if (document.frmEditProduct.movetoqtymain_to_shop)
		var movetoqtymain_to_shop      = document.frmEditProduct.movetoqtymain_to_shop.value;
	else
		var movetoqtymain_to_shop      = -1	

	if(document.frmEditProduct.storemain_shop)
		var storemain_shop      = document.frmEditProduct.storemain_shop.value;
	else
		var storemain_shop = -1;

	if(document.getElementById('product_mainstock')){
	if(document.frmEditProduct.product_mainstock.value<0)
	{
	alert('Stock value should be positive');
	return false;
	}
	else if(isNaN(document.frmEditProduct.product_mainstock.value))
	{
	alert('Stock value should be Numeric');
	document.frmEditProduct.product_mainstock.focus();
	return false;
	}
	else
	var main_stock_prod			= document.getElementById('product_mainstock').value;
	}else{
		var main_stock_prod = 0;
	}
	var curval 				= '';
	if(document.getElementById('product_variablestock_allowed'))
	{
		if(document.getElementById('product_variablestock_allowed').checked==true)
			var allow_varstock = 1;
		else
			var allow_varstock = 0;
	}
	else
		var allow_varstock = 0;		
	if(document.getElementById('product_variablecomboprice_allowed'))
	{
		if(document.getElementById('product_variablecomboprice_allowed').checked==true)
			var allow_varcomboprice = 1;
		else
			var allow_varcomboprice = 0;
	}
	else
		var allow_varcomboprice = 0;
	
	
	if(document.getElementById('product_variableweight_allowed'))
	{
		if(document.getElementById('product_variableweight_allowed').checked==true)
			var allow_varweight = 1;
		else
			var allow_varweight = 0;
	}
	else
		var allow_varweight = 0;
	
		
	if(document.getElementById('product_variablecombocommon_image_allowed'))
	{
		if(document.getElementById('product_variablecombocommon_image_allowed').checked==true)
			var allow_varcomboimage = 1;
		else
			var allow_varcomboimage = 0;
	}
	else
		var allow_varcomboimage = 0;		
		
	qrystr		+= '&varcnt=' + varcnt + '&allow_varcomboimage='+allow_varcomboimage+'&allow_varstock=' + allow_varstock+'&allow_varcomboprice='+allow_varcomboprice+'&main_stock_prod=' + main_stock_prod+'&movetoqtymain_to_shop=' + movetoqtymain_to_shop+'&storemain_shop='+ storemain_shop+'&allow_varweight='+allow_varweight;
	
	/* Decide Whether variables exists for current product */
	if (varcnt==0) /* No Variables*/
	{
		
		stkobj		= eval("document.getElementById('stock_"+prodid+"')");
		qrystr		+= '&stock=' + stkobj.value;
		
		strobj		= eval("document.getElementById('store_"+prodid+"')");
		if(strobj)
		{
			qrystr		+= '&movetostore=' + strobj.value;
		}	
		
		moveobj		= eval("document.getElementById('movetoqty_"+prodid+"')");
		if(moveobj)
		{
			qrystr		+= '&movetoqty=' + moveobj.value;
		}	
		
		priceobj	= eval("document.getElementById('price_"+prodid+"')");
		if(priceobj)
			qrystr		+= '&price=' + priceobj.value;
		
		codeobj	= eval("document.getElementById('barcode_"+prodid+"')");
		if(codeobj)
			qrystr		+= '&barcode=' + codeobj.value;
		
		codeobj	= eval("document.getElementById('special_product_code_"+prodid+"')");
		if(codeobj)
			qrystr		+= '&special_product_code=' + codeobj.value;
			
	}
	else /* case if variables exists */
	{
		checkcnts_str = '';
		stockval_str 	= movetoval_str 	= movetoqtyval_str 		= priceval_str 		= combval_str 	= barcodeval_str	= specialcode_str  = weightval_str = '';
		for(i=0;i<document.frmEditProduct.elements.length;i++)
		{
			curval = '';
			if(document.frmEditProduct.elements[i].name.substr(0,21)=='special_product_code_')
			{
				if(specialcode_str!='')
				{
					specialcode_str 	+= '~';
				}
				if(document.frmEditProduct.elements[i].value=='')
					curval = ' ';
				else
				{
					curval = document.frmEditProduct.elements[i].value;
					var re = /[!,@,#,$,<,>,",',%,&,*,;,(,)]/i;	    
					var result = curval.search(re); // checks invalid characters 
					if( result != -1 ) 
					{
						result++;
						alert('Invalid Charater at Position '+result+' of Special product code'); 
						document.frmEditProduct.elements[i].focus();
						document.frmEditProduct.elements[i].select();
						return false;
					}
				}
				 specialcode_str 	+= curval;	
			}	
			if(document.frmEditProduct.elements[i].name.substr(0,8)=='barcode_')
			{
				if(barcodeval_str!='')
				{
					barcodeval_str 	+= '~';
				}
				if(document.frmEditProduct.elements[i].value=='')
					curval = ' ';
				else
				{
					curval = document.frmEditProduct.elements[i].value;
					var re = /[!,@,#,$,<,>,",',%,&,*,;,(,)]/i;	    
					var result = curval.search(re); // checks invalid characters 
					if( result != -1 ) 
					{
						result++;
						alert('Invalid Charater at Position '+result+' of barcode'); 
						document.frmEditProduct.elements[i].focus();
						document.frmEditProduct.elements[i].select();
						return false;
					}
				}
				 barcodeval_str 	+= curval;	
			}	
			if(document.frmEditProduct.elements[i].name.substr(0,10)=='checkcnts_')
			{
				if(checkcnts_str!='')
				{
					checkcnts_str 	+= '~';
				}
				checkcnts_str 	+= document.frmEditProduct.elements[i].name; /* This variable is used to iterate in the saving section */
			}	
			if(document.frmEditProduct.elements[i].name.substr(0,6)=='stock_')
			{
				if(stockval_str!='')
				{
					stockval_str 	+= '~';
				}
				if(document.frmEditProduct.elements[i].value=='')
					curval = ' ';
				else
					curval = document.frmEditProduct.elements[i].value;
				stockval_str 	+= curval;
				//stockval_str 	+= document.frmEditProduct.elements[i].value;
			}	
			if(document.frmEditProduct.elements[i].name.substr(0,7)=='combid_')
			{
				if(combval_str!='')
				{
					combval_str 	+= '~';
				}
				combval_str 	+= document.frmEditProduct.elements[i].value;
			}
			if(document.frmEditProduct.elements[i].name.substr(0,6)=='store_')
			{
				if(movetoval_str!='')
				{
					movetoval_str 	+= '~';
				}
				if(document.frmEditProduct.elements[i].value=='')
					curval = ' ';
				else
					curval = document.frmEditProduct.elements[i].value;
				movetoval_str 	+= curval;
			}	
			if(document.frmEditProduct.elements[i].name.substr(0,10)=='movetoqty_')
			{
				if(movetoqtyval_str!='')
				{
					movetoqtyval_str 	+= '~';
				}
				if(document.frmEditProduct.elements[i].value=='')
					curval = ' ';
				else
					curval = document.frmEditProduct.elements[i].value;
				movetoqtyval_str 	+= curval;
			}	
			if(document.frmEditProduct.elements[i].name.substr(0,10)=='combprice_')
			{
				if(priceval_str!='')
				{
					priceval_str 	+= '~';
				}
				if(document.frmEditProduct.elements[i].value=='')
					curval = ' ';
				else
					curval = document.frmEditProduct.elements[i].value;
				priceval_str 	+= curval;
			}	
			if(document.frmEditProduct.elements[i].name.substr(0,11)=='combweight_')
			{
				if(weightval_str!='')
				{
					weightval_str 	+= '~';
				}
				if(document.frmEditProduct.elements[i].value=='')
					curval = ' ';
				else
					curval = document.frmEditProduct.elements[i].value;
				weightval_str 	+= curval;
			}	
		}
		qrystr += '&checkcnts_str='+checkcnts_str;
		qrystr += '&barcodeval_str='+barcodeval_str;
		qrystr += '&stockval_str='+stockval_str;
		qrystr += '&combval_str='+combval_str;
		qrystr += '&movetoval_str='+movetoval_str;
		qrystr += '&movetoqtyval_str='+movetoqtyval_str;
		qrystr += '&priceval_str='+priceval_str;
		qrystr += '&specialcode_str='+specialcode_str;
		qrystr += '&weightval_str='+weightval_str;
		if(varcnt>0)
		{
			/*codeobj	= document.getElementById('product_shop_price');
			if(codeobj)
				qrystr		+= '&product_shop_price=' + codeobj.value;*/
			/*codeobj	= document.getElementById('product_shop_barcode');
			if(codeobj)
				qrystr		+= '&product_shop_barcode=' + codeobj.value;	*/
		}	
	}
	confirmmsg 	= 'Are you sure you want to Save Changes?';
	if(confirm(confirmmsg))
	{
			var main_store								= document.getElementById('main_store').value;
			document.getElementById('retdiv_id').value 	= retdivid;/* Name of div to show the result */
			retobj 										= eval("document.getElementById('"+retdivid+"')");
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/products.php','fpurpose='+fpurpose+'&cur_prodid='+prodid+'&main_store='+main_store+'&'+qrystr);
		
	}
}
function handle_varstock(obj)
{
	var var_cnt = 0;
	if(obj)
	{
		if(document.getElementById('var_cnt'))
		{
			var_cnt = document.getElementById('var_cnt').value;
		}
		if(obj.checked==true)
		{
			if(document.getElementById('td_moveto'))
				document.getElementById('td_moveto').style.display='none';
		}
		else
		{
			if(document.getElementById('td_moveto'))
				document.getElementById('td_moveto').style.display='';
		}
		if(var_cnt>0)
		{
			for(i=0;i<document.frmEditProduct.elements.length;i++)
			{
				if (document.frmEditProduct.elements[i].name.substr(0,6)=='stock_' || document.frmEditProduct.elements[i].name.substr(0,10)=='movetoqty_' || document.frmEditProduct.elements[i].name.substr(0,6)=='store_')
				{
					if(obj.checked==true)
					{
						document.frmEditProduct.elements[i].className='normal_class';
						document.frmEditProduct.elements[i].readOnly  = false;
						document.getElementById('td_moveto').style.display='none';
					}
					else
					{
						document.frmEditProduct.elements[i].className='disabled_class';
						document.frmEditProduct.elements[i].readOnly  = true;
						document.getElementById('td_moveto').style.display='';
					}	
				}
			}	
		}
	}	
}	
function handle_combinationprice(obj)
{
	for(i=0;i<document.frmEditProduct.elements.length;i++)
	{
		if (document.frmEditProduct.elements[i].name.substr(0,10)=='combprice_')
		{
			if(obj.checked==true)
			{
				document.frmEditProduct.elements[i].className='normal_class';
				document.frmEditProduct.elements[i].readOnly  = false;
			}
			else
			{
				document.frmEditProduct.elements[i].className='disabled_class';
				document.frmEditProduct.elements[i].readOnly  = true;
			}	
		}
	}	
}
function handle_combinationweight(obj)
{
	for(i=0;i<document.frmEditProduct.elements.length;i++)
	{
		if (document.frmEditProduct.elements[i].name.substr(0,11)=='combweight_')
		{
			if(obj.checked==true)
			{
				document.frmEditProduct.elements[i].className='normal_class';
				document.frmEditProduct.elements[i].readOnly  = false;
			}
			else
			{
				document.frmEditProduct.elements[i].className='disabled_class';
				document.frmEditProduct.elements[i].readOnly  = true;
			}	
		}
	}	
}	
function show_combo_bulk(id,comboid)
{
	if(document.getElementById('product_variablecomboprice_allowed').checked ==true)
	{
		var editid																		= document.getElementById('checkbox[0]').value;
		var qrystr																		= 'prod_id='+editid+'&combo_id='+comboid+'&pass_str='+id;
		var fpurpose																	= 'show_combo_bulk_disc';
		document.getElementById('retdiv_id').value 						= id;
		obj																				= eval("document.getElementById('"+id+"')");
		obj.innerHTML 																= '<center><img src="images/loading.gif" alt="Loading"></center>';
		/* Calling the ajax function */
		Handlewith_Ajax('services/products.php','fpurpose='+fpurpose+'&'+qrystr);
	}
	else
	{
		alert('Sorry!! this operation is allowed only if "Combination Price" option is ticked');
	}	
}
function show_combo_images(id,comboid)
{
	if(document.getElementById('product_variablecombocommon_image_allowed').checked ==true)
	{
		var editid																		= document.getElementById('checkbox[0]').value;
		var qrystr																		= 'prod_id='+editid+'&combo_id='+comboid+'&pass_str='+id;
		var fpurpose																	= 'show_combo_images';
		document.getElementById('retdiv_id').value 						= id;
		obj																				= eval("document.getElementById('"+id+"')");
		obj.innerHTML 																= '<center><img src="images/loading.gif" alt="Loading"></center>';
		/* Calling the ajax function */
		Handlewith_Ajax('services/products.php','fpurpose='+fpurpose+'&'+qrystr);
	}
	else
	{
		alert('Sorry!! this operation is allowed only if "Combination Image" option is ticked');
	}	
}
function hide_bulk_discount(id)
{
	obj = eval("document.getElementById('"+id+"')");
	if (obj)
	{
		obj.innerHTML = '';
	}	
}
function handle_tabs(id)
{
	var mod;
	//document.getElementById('mainprod_alert').style.display='none';
	switch(id)
	{
		case 'main_tab_td':
			if(document.getElementById('desc_tab_td'))
				document.getElementById('desc_tab_td').className 		= 'toptab';
			if(document.getElementById('variable_tab_td'))
				document.getElementById('variable_tab_td').className 	= 'toptab';
			if(document.getElementById('stock_tab_td'))	
				document.getElementById('stock_tab_td').className 		= 'toptab';
			if(document.getElementById('linked_tab_td'))		
				document.getElementById('linked_tab_td').className 		= 'toptab';
			if(document.getElementById('images_tab_td'))		
				document.getElementById('images_tab_td').className 		= 'toptab';
			if(document.getElementById('videos_tab_td'))		
				document.getElementById('videos_tab_td').className 		= 'toptab';
				if(document.getElementById('googleimages_tab_td'))		
				document.getElementById('googleimages_tab_td').className 		= 'toptab';
			if(document.getElementById('attach_tab_td'))		
				document.getElementById('attach_tab_td').className 		= 'toptab';
			if(document.getElementById('size_chart_td'))
				document.getElementById('size_chart_td').className 		= 'toptab';	
			if(document.getElementById('download_tab_td'))
				document.getElementById('download_tab_td').className 		= 'toptab';		
			if(document.getElementById('offer_tab_td'))
				document.getElementById('offer_tab_td').className 			= 'toptab';	
			if(document.getElementById('sales_tab_td'))
				document.getElementById('sales_tab_td').className 			= 'toptab';	
			if(document.getElementById('seo_tab_td'))
				document.getElementById('seo_tab_td').className 			= 'toptab';	
			if(document.getElementById('catvar_tab_td'))
				document.getElementById('catvar_tab_td').className 			= 'toptab';			
		break;
		case 'desc_tab_td':
			/*document.getElementById('main_tab_td').className 		= 'toptab';
			document.getElementById('variable_tab_td').className 	= 'toptab';
			document.getElementById('stock_tab_td').className 		= 'toptab';
			document.getElementById('linked_tab_td').className 		= 'toptab';
			document.getElementById('images_tab_td').className 		= 'toptab';*/
			show_processing();
			document.getElementById('curtab').value = id;
			document.frmEditProduct.fpurpose.value ='edit';
			document.frmEditProduct.submit();
		break;
		case 'variable_tab_td':
			if(document.getElementById('main_tab_td'))
				document.getElementById('main_tab_td').className 		= 'toptab';
			if(document.getElementById('desc_tab_td'))	
				document.getElementById('desc_tab_td').className 		= 'toptab';
			if(document.getElementById('stock_tab_td'))	
				document.getElementById('stock_tab_td').className 		= 'toptab';
			if(document.getElementById('linked_tab_td'))		
				document.getElementById('linked_tab_td').className 		= 'toptab';
			if(document.getElementById('images_tab_td'))	
				document.getElementById('images_tab_td').className 		= 'toptab';
			if(document.getElementById('videos_tab_td'))	
				document.getElementById('videos_tab_td').className 		= 'toptab';
			if(document.getElementById('googleimages_tab_td'))	
				document.getElementById('googleimages_tab_td').className 		= 'toptab';
			if(document.getElementById('attach_tab_td'))		
				document.getElementById('attach_tab_td').className 		= 'toptab';
			if(document.getElementById('size_chart_td'))
				document.getElementById('size_chart_td').className 		= 'toptab';	
			if(document.getElementById('download_tab_td'))
				document.getElementById('download_tab_td').className 		= 'toptab';		
			if(document.getElementById('offer_tab_td'))
				document.getElementById('offer_tab_td').className 			= 'toptab';
			if(document.getElementById('sales_tab_td'))
				document.getElementById('sales_tab_td').className 			= 'toptab';
				if(document.getElementById('seo_tab_td'))
				document.getElementById('seo_tab_td').className 			= 'toptab';	
			if(document.getElementById('catvar_tab_td'))
				document.getElementById('catvar_tab_td').className 			= 'toptab';				
		break;
		case 'stock_tab_td':
			if(document.getElementById('main_tab_td'))	
				document.getElementById('main_tab_td').className 		= 'toptab';
			if(document.getElementById('desc_tab_td'))		
				document.getElementById('desc_tab_td').className 		= 'toptab';
			if(document.getElementById('variable_tab_td'))		
				document.getElementById('variable_tab_td').className 	= 'toptab';
			if(document.getElementById('linked_tab_td'))		
				document.getElementById('linked_tab_td').className 		= 'toptab';
			if(document.getElementById('images_tab_td'))		
				document.getElementById('images_tab_td').className 		= 'toptab';
			if(document.getElementById('videos_tab_td'))		
				document.getElementById('videos_tab_td').className 		= 'toptab';
			if(document.getElementById('googleimages_tab_td'))		
				document.getElementById('googleimages_tab_td').className 		= 'toptab';
			if(document.getElementById('attach_tab_td'))	
				document.getElementById('attach_tab_td').className 		= 'toptab';
			if(document.getElementById('size_chart_td'))
				document.getElementById('size_chart_td').className 		= 'toptab';	
			if(document.getElementById('download_tab_td'))
				document.getElementById('download_tab_td').className 		= 'toptab';	
			if(document.getElementById('offer_tab_td'))
				document.getElementById('offer_tab_td').className 			= 'toptab';	
			if(document.getElementById('sales_tab_td'))
				document.getElementById('sales_tab_td').className 			= 'toptab';	
				if(document.getElementById('seo_tab_td'))
				document.getElementById('seo_tab_td').className 			= 'toptab';		
			if(document.getElementById('catvar_tab_td'))
				document.getElementById('catvar_tab_td').className 			= 'toptab';	
		break;
		case 'linked_tab_td':
			if(document.getElementById('main_tab_td'))	
				document.getElementById('main_tab_td').className 		= 'toptab';
			if(document.getElementById('desc_tab_td'))		
				document.getElementById('desc_tab_td').className 		= 'toptab';
			if(document.getElementById('variable_tab_td'))		
				document.getElementById('variable_tab_td').className 	= 'toptab';
			if(document.getElementById('stock_tab_td'))			
				document.getElementById('stock_tab_td').className 		= 'toptab';
			if(document.getElementById('images_tab_td'))	
				document.getElementById('images_tab_td').className 		= 'toptab';
			if(document.getElementById('videos_tab_td'))	
				document.getElementById('videos_tab_td').className 		= 'toptab';
			if(document.getElementById('googleimages_tab_td'))	
				document.getElementById('googleimages_tab_td').className 		= 'toptab';
			if(document.getElementById('attach_tab_td'))		
				document.getElementById('attach_tab_td').className 		= 'toptab';			
			if(document.getElementById('size_chart_td'))
				document.getElementById('size_chart_td').className 		= 'toptab';
			if(document.getElementById('download_tab_td'))
				document.getElementById('download_tab_td').className 		= 'toptab';		
			if(document.getElementById('offer_tab_td'))
				document.getElementById('offer_tab_td').className 			= 'toptab';	
			if(document.getElementById('sales_tab_td'))
				document.getElementById('sales_tab_td').className 			= 'toptab';	
				if(document.getElementById('seo_tab_td'))
				document.getElementById('seo_tab_td').className 			= 'toptab';	
			if(document.getElementById('catvar_tab_td'))
				document.getElementById('catvar_tab_td').className 			= 'toptab';		
		break;
		case 'images_tab_td':
			if(document.getElementById('main_tab_td'))	
				document.getElementById('main_tab_td').className 		= 'toptab';
			if(document.getElementById('desc_tab_td'))	
				document.getElementById('desc_tab_td').className 		= 'toptab';
			if(document.getElementById('variable_tab_td'))	
				document.getElementById('variable_tab_td').className 	= 'toptab';
			if(document.getElementById('stock_tab_td'))		
				document.getElementById('stock_tab_td').className 		= 'toptab';
			if(document.getElementById('linked_tab_td'))	
				document.getElementById('linked_tab_td').className 		= 'toptab';
			if(document.getElementById('attach_tab_td'))		
				document.getElementById('attach_tab_td').className 		= 'toptab';
			if(document.getElementById('size_chart_td'))
				document.getElementById('size_chart_td').className 		= 'toptab';
			if(document.getElementById('download_tab_td'))
				document.getElementById('download_tab_td').className 		= 'toptab';	
			if(document.getElementById('offer_tab_td'))
				document.getElementById('offer_tab_td').className 			= 'toptab';	
			if(document.getElementById('sales_tab_td'))
				document.getElementById('sales_tab_td').className 			= 'toptab';	
				if(document.getElementById('seo_tab_td'))
				document.getElementById('seo_tab_td').className 			= 'toptab';	
			if(document.getElementById('catvar_tab_td'))
				document.getElementById('catvar_tab_td').className 			= 'toptab';		
			if(document.getElementById('googleimages_tab_td'))
				document.getElementById('googleimages_tab_td').className 			= 'toptab';		
		break;
		case 'videos_tab_td':
			if(document.getElementById('main_tab_td'))	
				document.getElementById('main_tab_td').className 		= 'toptab';
			if(document.getElementById('desc_tab_td'))	
				document.getElementById('desc_tab_td').className 		= 'toptab';
			if(document.getElementById('variable_tab_td'))	
				document.getElementById('variable_tab_td').className 	= 'toptab';
			if(document.getElementById('stock_tab_td'))		
				document.getElementById('stock_tab_td').className 		= 'toptab';
			if(document.getElementById('linked_tab_td'))	
				document.getElementById('linked_tab_td').className 		= 'toptab';
			if(document.getElementById('attach_tab_td'))		
				document.getElementById('attach_tab_td').className 		= 'toptab';
			if(document.getElementById('size_chart_td'))
				document.getElementById('size_chart_td').className 		= 'toptab';
			if(document.getElementById('download_tab_td'))
				document.getElementById('download_tab_td').className 		= 'toptab';	
			if(document.getElementById('offer_tab_td'))
				document.getElementById('offer_tab_td').className 			= 'toptab';	
			if(document.getElementById('sales_tab_td'))
				document.getElementById('sales_tab_td').className 			= 'toptab';	
				if(document.getElementById('seo_tab_td'))
				document.getElementById('seo_tab_td').className 			= 'toptab';	
			if(document.getElementById('catvar_tab_td'))
				document.getElementById('catvar_tab_td').className 			= 'toptab';		
			if(document.getElementById('googleimages_tab_td'))
				document.getElementById('googleimages_tab_td').className 			= 'toptab';		
			if(document.getElementById('images_tab_td'))
				document.getElementById('images_tab_td').className 			= 'toptab';		
		break;
		case 'googleimages_tab_td': 
			if(document.getElementById('main_tab_td'))	
				document.getElementById('main_tab_td').className 		= 'toptab';
			if(document.getElementById('desc_tab_td'))	
				document.getElementById('desc_tab_td').className 		= 'toptab';
			if(document.getElementById('variable_tab_td'))	
				document.getElementById('variable_tab_td').className 	= 'toptab';
			if(document.getElementById('stock_tab_td'))		
				document.getElementById('stock_tab_td').className 		= 'toptab';
			if(document.getElementById('linked_tab_td'))	
				document.getElementById('linked_tab_td').className 		= 'toptab';
			if(document.getElementById('attach_tab_td'))		
				document.getElementById('attach_tab_td').className 		= 'toptab';
			if(document.getElementById('size_chart_td'))
				document.getElementById('size_chart_td').className 		= 'toptab';
			if(document.getElementById('download_tab_td'))
				document.getElementById('download_tab_td').className 		= 'toptab';	
			if(document.getElementById('offer_tab_td'))
				document.getElementById('offer_tab_td').className 			= 'toptab';	
			if(document.getElementById('sales_tab_td'))
				document.getElementById('sales_tab_td').className 			= 'toptab';	
				if(document.getElementById('seo_tab_td'))
				document.getElementById('seo_tab_td').className 			= 'toptab';	
			if(document.getElementById('catvar_tab_td'))
				document.getElementById('catvar_tab_td').className 			= 'toptab';		
			if(document.getElementById('images_tab_td'))
				document.getElementById('images_tab_td').className 			= 'toptab';		
			if(document.getElementById('videos_tab_td'))
				document.getElementById('videos_tab_td').className 			= 'toptab';		
		break;
		case 'attach_tab_td':
			if(document.getElementById('main_tab_td'))
				document.getElementById('main_tab_td').className 		= 'toptab';
			if(document.getElementById('desc_tab_td'))	
				document.getElementById('desc_tab_td').className 		= 'toptab';
			if(document.getElementById('variable_tab_td'))		
				document.getElementById('variable_tab_td').className 	= 'toptab';
			if(document.getElementById('stock_tab_td'))			
				document.getElementById('stock_tab_td').className 		= 'toptab';
			if(document.getElementById('linked_tab_td'))	
				document.getElementById('linked_tab_td').className 		= 'toptab';
			if(document.getElementById('images_tab_td'))
				document.getElementById('images_tab_td').className 		= 'toptab';
			if(document.getElementById('videos_tab_td'))
				document.getElementById('videos_tab_td').className 		= 'toptab';
			if(document.getElementById('googleimages_tab_td'))
				document.getElementById('googleimages_tab_td').className 		= 'toptab';
			if(document.getElementById('size_chart_td'))
				document.getElementById('size_chart_td').className 		= 'toptab';	
			if(document.getElementById('download_tab_td'))
				document.getElementById('download_tab_td').className 		= 'toptab';	
			if(document.getElementById('offer_tab_td'))
				document.getElementById('offer_tab_td').className 			= 'toptab';	
			if(document.getElementById('sales_tab_td'))
				document.getElementById('sales_tab_td').className 			= 'toptab';	
				if(document.getElementById('seo_tab_td'))
				document.getElementById('seo_tab_td').className 			= 'toptab';	
			if(document.getElementById('catvar_tab_td'))
				document.getElementById('catvar_tab_td').className 			= 'toptab';		
		break;
		<?php /*?>case 'bulk_tab_td':
			if(document.getElementById('main_tab_td'))
				document.getElementById('main_tab_td').className 		= 'toptab';
			if(document.getElementById('desc_tab_td'))	
				document.getElementById('desc_tab_td').className 		= 'toptab';
			if(document.getElementById('variable_tab_td'))		
				document.getElementById('variable_tab_td').className 	= 'toptab';
			if(document.getElementById('stock_tab_td'))		
				document.getElementById('stock_tab_td').className 		= 'toptab';
			if(document.getElementById('linked_tab_td'))	
				document.getElementById('linked_tab_td').className 		= 'toptab';
			if(document.getElementById('images_tab_td'))		
				document.getElementById('images_tab_td').className 		= 'toptab';
			if(document.getElementById('attach_tab_td'))			
				document.getElementById('attach_tab_td').className 		= 'toptab';
			if(document.getElementById('size_chart_td'))
				document.getElementById('size_chart_td').className 		= 'toptab';
			if(document.getElementById('download_tab_td'))
				document.getElementById('download_tab_td').className 		= 'toptab';	
		break;<?php */?>
		case 'size_chart_td':
			if(document.getElementById('main_tab_td'))
				document.getElementById('main_tab_td').className 		= 'toptab';
			if(document.getElementById('desc_tab_td'))
				document.getElementById('desc_tab_td').className 		= 'toptab';
			if(document.getElementById('variable_tab_td'))	
				document.getElementById('variable_tab_td').className 	= 'toptab';
			if(document.getElementById('stock_tab_td'))
				document.getElementById('stock_tab_td').className 		= 'toptab';
			if(document.getElementById('linked_tab_td'))	
				document.getElementById('linked_tab_td').className 		= 'toptab';
			if(document.getElementById('images_tab_td'))		
				document.getElementById('images_tab_td').className 		= 'toptab';
			if(document.getElementById('videos_tab_td'))		
				document.getElementById('videos_tab_td').className 		= 'toptab';
			if(document.getElementById('googleimages_tab_td'))		
				document.getElementById('googleimages_tab_td').className 		= 'toptab';
			if(document.getElementById('attach_tab_td'))		
				document.getElementById('attach_tab_td').className 		= 'toptab';
			if(document.getElementById('download_tab_td'))
				document.getElementById('download_tab_td').className 		= 'toptab';	
			if(document.getElementById('offer_tab_td'))
				document.getElementById('offer_tab_td').className 			= 'toptab';	
			if(document.getElementById('sales_tab_td'))
				document.getElementById('sales_tab_td').className 			= 'toptab';	
				if(document.getElementById('seo_tab_td'))
				document.getElementById('seo_tab_td').className 			= 'toptab';	
			if(document.getElementById('catvar_tab_td'))
				document.getElementById('catvar_tab_td').className 			= 'toptab';		
		break;
		case 'download_tab_td':
			if(document.getElementById('main_tab_td'))	
				document.getElementById('main_tab_td').className 			= 'toptab';
			if(document.getElementById('desc_tab_td'))		
				document.getElementById('desc_tab_td').className 			= 'toptab';
			if(document.getElementById('variable_tab_td'))		
				document.getElementById('variable_tab_td').className 		= 'toptab';
			if(document.getElementById('stock_tab_td'))		
				document.getElementById('stock_tab_td').className 			= 'toptab';
			if(document.getElementById('linked_tab_td'))		
				document.getElementById('linked_tab_td').className 		= 'toptab';
			if(document.getElementById('images_tab_td'))	
				document.getElementById('images_tab_td').className 		= 'toptab';
			if(document.getElementById('videos_tab_td'))	
				document.getElementById('videos_tab_td').className 		= 'toptab';
			if(document.getElementById('googleimages_tab_td'))	
				document.getElementById('googleimages_tab_td').className 		= 'toptab';
			if(document.getElementById('attach_tab_td'))		
				document.getElementById('attach_tab_td').className 		= 'toptab';
			if(document.getElementById('size_chart_td'))
				document.getElementById('size_chart_td').className 		= 'toptab';
			if(document.getElementById('offer_tab_td'))
				document.getElementById('offer_tab_td').className 			= 'toptab';	
			if(document.getElementById('sales_tab_td'))
				document.getElementById('sales_tab_td').className 			= 'toptab';	
				if(document.getElementById('seo_tab_td'))
				document.getElementById('seo_tab_td').className 			= 'toptab';	
			if(document.getElementById('catvar_tab_td'))
				document.getElementById('catvar_tab_td').className 			= 'toptab';		
		break;
		case 'offer_tab_td':
			if(document.getElementById('main_tab_td'))	
				document.getElementById('main_tab_td').className 			= 'toptab';
			if(document.getElementById('desc_tab_td'))		
				document.getElementById('desc_tab_td').className 			= 'toptab';
			if(document.getElementById('variable_tab_td'))		
				document.getElementById('variable_tab_td').className 		= 'toptab';
			if(document.getElementById('stock_tab_td'))		
				document.getElementById('stock_tab_td').className 			= 'toptab';
			if(document.getElementById('linked_tab_td'))		
				document.getElementById('linked_tab_td').className 		= 'toptab';
			if(document.getElementById('images_tab_td'))	
				document.getElementById('images_tab_td').className 		= 'toptab';
			if(document.getElementById('videos_tab_td'))	
				document.getElementById('videos_tab_td').className 		= 'toptab';
			if(document.getElementById('googleimages_tab_td'))	
				document.getElementById('googleimages_tab_td').className 		= 'toptab';
			if(document.getElementById('attach_tab_td'))		
				document.getElementById('attach_tab_td').className 		= 'toptab';
			if(document.getElementById('size_chart_td'))
				document.getElementById('size_chart_td').className 		= 'toptab';
			if(document.getElementById('download_tab_td'))
				document.getElementById('download_tab_td').className 		= 'toptab';
			if(document.getElementById('sales_tab_td'))
				document.getElementById('sales_tab_td').className 			= 'toptab';	
				if(document.getElementById('seo_tab_td'))
				document.getElementById('seo_tab_td').className 			= 'toptab';	
			if(document.getElementById('catvar_tab_td'))
				document.getElementById('catvar_tab_td').className 			= 'toptab';				
		break;
		case 'sales_tab_td':
			if(document.getElementById('main_tab_td'))	
				document.getElementById('main_tab_td').className 			= 'toptab';
			if(document.getElementById('desc_tab_td'))		
				document.getElementById('desc_tab_td').className 			= 'toptab';
			if(document.getElementById('variable_tab_td'))		
				document.getElementById('variable_tab_td').className 		= 'toptab';
			if(document.getElementById('stock_tab_td'))		
				document.getElementById('stock_tab_td').className 			= 'toptab';
			if(document.getElementById('linked_tab_td'))		
				document.getElementById('linked_tab_td').className 		= 'toptab';
			if(document.getElementById('images_tab_td'))	
				document.getElementById('images_tab_td').className 		= 'toptab';
			if(document.getElementById('videos_tab_td'))	
				document.getElementById('videos_tab_td').className 		= 'toptab';
			if(document.getElementById('googleimages_tab_td'))	
				document.getElementById('googleimages_tab_td').className 		= 'toptab';
			if(document.getElementById('attach_tab_td'))		
				document.getElementById('attach_tab_td').className 		= 'toptab';
			if(document.getElementById('size_chart_td'))
				document.getElementById('size_chart_td').className 		= 'toptab';
			if(document.getElementById('download_tab_td'))
				document.getElementById('download_tab_td').className 		= 'toptab';	
			if(document.getElementById('offer_tab_td'))
				document.getElementById('offer_tab_td').className 			= 'toptab';	
				if(document.getElementById('seo_tab_td'))
				document.getElementById('seo_tab_td').className 			= 'toptab';	
			if(document.getElementById('catvar_tab_td'))
				document.getElementById('catvar_tab_td').className 			= 'toptab';			
		break;
		case 'seo_tab_td':
			if(document.getElementById('main_tab_td'))	
				document.getElementById('main_tab_td').className 			= 'toptab';
			if(document.getElementById('desc_tab_td'))		
				document.getElementById('desc_tab_td').className 			= 'toptab';
			if(document.getElementById('variable_tab_td'))		
				document.getElementById('variable_tab_td').className 		= 'toptab';
			if(document.getElementById('stock_tab_td'))		
				document.getElementById('stock_tab_td').className 			= 'toptab';
			if(document.getElementById('linked_tab_td'))		
				document.getElementById('linked_tab_td').className 		= 'toptab';
			if(document.getElementById('images_tab_td'))	
				document.getElementById('images_tab_td').className 		= 'toptab';
			if(document.getElementById('videos_tab_td'))	
				document.getElementById('videos_tab_td').className 		= 'toptab';
			if(document.getElementById('googleimages_tab_td'))	
				document.getElementById('googleimages_tab_td').className 		= 'toptab';
			if(document.getElementById('attach_tab_td'))		
				document.getElementById('attach_tab_td').className 		= 'toptab';
			if(document.getElementById('size_chart_td'))
				document.getElementById('size_chart_td').className 		= 'toptab';
			if(document.getElementById('download_tab_td'))
				document.getElementById('download_tab_td').className 		= 'toptab';	
			if(document.getElementById('offer_tab_td'))
				document.getElementById('offer_tab_td').className 			= 'toptab';	
				if(document.getElementById('sales_tab_td'))
				document.getElementById('sales_tab_td').className 			= 'toptab';	
			if(document.getElementById('catvar_tab_td'))
				document.getElementById('catvar_tab_td').className 			= 'toptab';			
		break;
		case 'catvar_tab_td':
			if(document.getElementById('main_tab_td'))	
				document.getElementById('main_tab_td').className 			= 'toptab';
			if(document.getElementById('desc_tab_td'))		
				document.getElementById('desc_tab_td').className 			= 'toptab';
			if(document.getElementById('variable_tab_td'))		
				document.getElementById('variable_tab_td').className 		= 'toptab';
			if(document.getElementById('stock_tab_td'))		
				document.getElementById('stock_tab_td').className 			= 'toptab';
			if(document.getElementById('linked_tab_td'))		
				document.getElementById('linked_tab_td').className 		= 'toptab';
			if(document.getElementById('images_tab_td'))	
				document.getElementById('images_tab_td').className 		= 'toptab';
			if(document.getElementById('videos_tab_td'))	
				document.getElementById('videos_tab_td').className 		= 'toptab';
			if(document.getElementById('googleimages_tab_td'))	
				document.getElementById('googleimages_tab_td').className 		= 'toptab';
			if(document.getElementById('attach_tab_td'))		
				document.getElementById('attach_tab_td').className 		= 'toptab';
			if(document.getElementById('size_chart_td'))
				document.getElementById('size_chart_td').className 		= 'toptab';
			if(document.getElementById('download_tab_td'))
				document.getElementById('download_tab_td').className 		= 'toptab';	
			if(document.getElementById('offer_tab_td'))
				document.getElementById('offer_tab_td').className 			= 'toptab';	
			if(document.getElementById('sales_tab_td'))
				document.getElementById('sales_tab_td').className 			= 'toptab';	
				if(document.getElementById('seo_tab_td'))
				document.getElementById('seo_tab_td').className 			= 'toptab';		
		break;
		
	};
	if (id!='desc_tab_td')
	{
		document.getElementById('images_tab_td').className 		= 'toptab';
		document.getElementById('desc_main_td').style.display 	= 'none';
		document.getElementById('curtab').value = id;
		obj = eval("document.getElementById('"+id+"')");
		
		if (obj.className=='toptab')
			obj.className = 'toptab_sel';
		call_ajax_show_tab('show_'+id)
	}	
}
function call_ajax_show_tab(mod)
{
	var editid												= document.getElementById('checkbox[0]').value;
	var qrystr												= 'prod_id='+editid;
	var fpurpose											= mod;
	document.getElementById('retdiv_id').value 				= 'main_product_div';
	document.getElementById('main_product_div').innerHTML 	= '<center><img src="images/loading.gif" alt="Loading"></center>';
	/* Calling the ajax function */
	Handlewith_Ajax('services/products.php','fpurpose='+fpurpose+'&'+qrystr);
}
function call_ajax_download_stock()
{
	if(document.getElementById('special_alert_tr'))
		document.getElementById('special_alert_tr').style.display = 'none';
	if(document.getElementById('stock_upload_tr'))
		document.getElementById('stock_upload_tr').style.display = 'none';
	if (confirm('Are you sure you want to download the combinations for this product in CSV?'))
		document.temp_form.submit();
}
function call_ajax_upload_stock()
{
	if(document.getElementById('special_alert_tr'))
		document.getElementById('special_alert_tr').style.display = 'none';
	if(document.getElementById('stock_upload_tr'))
	{
		if(document.getElementById('stock_upload_tr').style.display == '')
			document.getElementById('stock_upload_tr').style.display = 'none';
		else
			document.getElementById('stock_upload_tr').style.display = '';
	}		
}
function call_ajax_upload_stock_do()
{
	if(document.getElementById('file_stock_upload'))
	{
		if(document.getElementById('file_stock_upload').value=='')
		{
			alert ('Please select the file to upload');
			return false;
		}
	}
	if(confirm('Combination details of current product will be set as specified in the CSV file.\n\nAre you sure you want to upload the CSV file with combination details?'))
	{
		show_processing();
		document.frmEditProduct.fpurpose.value ='do_combtab_save';
		document.frmEditProduct.submit();
	}
}

function call_ajax_showbulkdisc()
{
	document.frmEditProduct.overridecase.value ='show_bulkdisc';
	document.frmEditProduct.submit();
}
function handle_imagesel(id)
{
	var ret_str	= '';
	var new_str = ''
	tdobj		= eval("document.getElementById('img_td_"+id+"')");
	if(tdobj.className == 'imagelistproducttabletd')
	{
		tdobj.className = 'imagelistproducttabletd_sel';
	}	
	else
	{
		tdobj.className = 'imagelistproducttabletd';
	}	
}
function move_right()
{
	var rem_arr = new Array();
	var indx = 0;
	var val = 0;
	src_loc = document.getElementById('free_pool[]');
	des_loc = document.getElementById('set_pool[]');
	for(i=0;i<src_loc.options.length;i++)
	{
			if(src_loc.options[i].selected)
			{
				var lgth = des_loc.options.length;
				des_loc.options[lgth]= new Option(src_loc.options[i].text,src_loc.options[i].value);
				rem_arr[indx] = i;
				indx++;
			}	
	}
	for(i=rem_arr.length-1;i>=0;--i)
	{
		val = rem_arr[i];	
		src_loc.remove(val);
	}	
}
function move_left()
{
	var rem_arr = new Array();
	var indx = 0;
	var val = 0;
	src_loc = document.getElementById('set_pool[]');
	des_loc = document.getElementById('free_pool[]');
	for(i=0;i<src_loc.options.length;i++)
	{
			if(src_loc.options[i].selected)
			{
				var lgth = des_loc.options.length;
				des_loc.options[lgth]= new Option(src_loc.options[i].text,src_loc.options[i].value);
				rem_arr[indx] = i;
				indx++;
			}	
	}
	for(i=rem_arr.length-1;i>=0;--i)
	{
		val = rem_arr[i];	
		src_loc.remove(val);
	}	
}
function handle_Heading_Save()
{
	var head_str = '';
	src_loc = document.getElementById('set_pool[]');
	if (document.getElementById('suberror_tr'))
		document.getElementById('suberror_tr').style.display = 'none';
	for(i=0;i<src_loc.options.length;i++)
	{
		src_loc.options[i].selected = true;
		if (head_str!='')
			head_str += '~';
		head_str += src_loc.options[i].value;
	}
	var editid												= document.getElementById('checkbox[0]').value;
	var sizingmainheading									= document.getElementById('txt_sizingmainheading').value;
	var qrystr												= 'prod_id='+editid+'&size_heads='+head_str+'&sizingmainheading='+sizingmainheading;
	var fpurpose											= 'save_Sizechartheading';
	document.getElementById('retdiv_id').value 				= 'main_product_div';
	document.getElementById('main_product_div').innerHTML 	= '<center><img src="images/loading.gif" alt="Loading"></center>';
	/* Calling the ajax function */
	Handlewith_Ajax('services/products.php','fpurpose='+fpurpose+'&'+qrystr);
}
function handle_commonDetails_Save()
{
	var editid												= document.getElementById('checkbox[0]').value;
	var common_link											= document.getElementById('product_commonsizechart_link').value;
	var common_target										= document.getElementById('produt_common_sizechart_target').value;
	var qrystr												= 'prod_id='+editid+'&common_link='+common_link+'&common_target='+common_target;
	var fpurpose											= 'save_commonsizechartvalues';
	document.getElementById('retdiv_id').value 				= 'main_product_div';
	document.getElementById('main_product_div').innerHTML 	= '<center><img src="images/loading.gif" alt="Loading"></center>';
	/* Calling the ajax function */
	Handlewith_Ajax('services/products.php','fpurpose='+fpurpose+'&'+qrystr);
}
function moveup()
{
	optarr = new Array;
	valarr = new Array;
	sel_indx = new Array;
	sel_val = new Array;
	j=0;
	tval='';
	ttext = '';
	src_loc = document.getElementById('set_pool[]');
	for(i=0;i<src_loc.options.length;i++)
	{
		valarr[i] = src_loc.options[i].value;
		optarr[i] = src_loc.options[i].text;
		if(src_loc.options[i].selected)
		{
			if (i==0) return;
			sel_indx[j] = i;
			sel_val[i] = src_loc.options[i].value;
			j++;
		}
		else
			sel_val[i] = -1;
	}
	change_i = 0;
	for(i=0;i<sel_indx.length;i++)
	{
		curi = i;
		for(j=0;j<src_loc.options.length;j++)
		{
			if (j==sel_indx[i])
			{
				tval = valarr[j-1];
				ttext = optarr[j-1];
				valarr[j-1] = valarr[j];
				optarr[j-1] = optarr[j];
				valarr[j] =tval;
				optarr[j] = ttext;
			}
		}
	}
	for(i=src_loc.options.length-1;i>=0;--i)
	{
		src_loc.remove(i);
	}	
	for(i=0;i<valarr.length;i++)
	{
		var lgth 				= src_loc.options.length;
		src_loc.options[lgth]	= new Option(optarr[i],valarr[i]);
	}
}
function movedown()
{
	optarr = new Array;
	valarr = new Array;
	sel_indx = new Array;
	j=0;
	tval='';
	ttext = '';
	src_loc = document.getElementById('set_pool[]');
	maxl = (src_loc.options.length-1);
	for(i=0;i<=maxl;i++)
	{
		valarr[i] = src_loc.options[i].value;
		optarr[i] = src_loc.options[i].text;
		if(src_loc.options[i].selected)
		{
			if (i==maxl) return;
			sel_indx[j] = i;
			j++;
		}
	}
	for(i=(sel_indx.length-1);i>=0;i--)
	{
		curi = i;
		for(j=0;j<=maxl;j++)
		{
			if (j==sel_indx[i])
			{
				tval = valarr[j+1];
				ttext = optarr[j+1];
				valarr[j+1] = valarr[j];
				optarr[j+1] = optarr[j];
				
				valarr[j] =tval;
				optarr[j] = ttext;
			}
		}
	}
	for(i=src_loc.options.length-1;i>=0;--i)
	{
		src_loc.remove(i);
	}	
	for(i=0;i<valarr.length;i++)
	{
		var lgth = src_loc.options.length;
		src_loc.options[lgth]= new Option(optarr[i],valarr[i]);
	}
}
function need_selected(vals,sel_val)
{
	for(i=0;i<sel_val.length;i++)
	{
		if (vals==sel_val[i])
		{
		}
	}
}
function handle_sizechartvalues()
{
	var new_str ='&0';
	var ext_str = '';
	if (document.getElementById('mainerror_tr'))
		document.getElementById('mainerror_tr').style.display = 'none';
	for(i=0;i<document.frmEditProduct.elements.length;i++)
	{
		if(document.frmEditProduct.elements[i].name.substr(0,6)=='value_')
		{
			if(new_str!='' ||ext_str!='')
				ext_str += '&';
			ext_str += document.frmEditProduct.elements[i].name+'='+document.frmEditProduct.elements[i].value;
		}	
		if(document.frmEditProduct.elements[i].name.substr(0,9)=='valuenew_')
		{
			if(new_str!='' || $ext_str!='')
				new_str += '&';
			new_str += document.frmEditProduct.elements[i].name+'='+document.frmEditProduct.elements[i].value;
		}	
	}
	var editid												= document.getElementById('checkbox[0]').value;
	var totrows												= document.getElementById('tot_rows').value;
	var qrystr												= 'prod_id='+editid+'&totrows='+totrows+'&'+new_str+'&'+ext_str;
	var fpurpose											= 'save_Sizechartvalues';
	document.getElementById('retdiv_id').value 				= 'sizechart_div';
	document.getElementById('sizechart_div').innerHTML 		= '<center><img src="images/loading.gif" alt="Loading"></center>';
	/* Calling the ajax function */
	Handlewith_Ajax('services/products.php','fpurpose='+fpurpose+'&'+qrystr);
}
function extdiscchange() 
{
if(document.frmEditProduct.product_discount_enteredasval.value==2) {
	document.getElementById('extdisc').innerHTML = 'Exact Discount Price ('+ document.frmEditProduct.hid_cur_sign.value+' )';
	} else if(document.frmEditProduct.product_discount_enteredasval.value==0) {
		document.getElementById('extdisc').innerHTML = 'Discount Percentage';
	} else if(document.frmEditProduct.product_discount_enteredasval.value==1) {
		document.getElementById('extdisc').innerHTML = 'Discount Value ('+ document.frmEditProduct.hid_cur_sign.value+' )';
	}
}
function handle_prodvarmore_click(obj)
{
	if(document.getElementById('varmore_tr'))
	{
		if (document.getElementById('varmore_tr').style.display=='none')
		{
			document.getElementById('varmore_tr').style.display='';
			obj.src='images/minus.gif';
		}
		else
		{
			document.getElementById('varmore_tr').style.display='none';
			obj.src='images/plus.gif';
		}
	}	
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
		/*if(document.getElementById('flv_tr1'))
			document.getElementById('flv_tr1').style.display = '';
		if(document.getElementById('flv_tr2'))
		*/
			document.getElementById('flv_tr2').style.display = '';
		if(document.getElementById('flv_rotate_tr'))
			document.getElementById('flv_rotate_tr').style.display = 'none';	
	}
	else if(obj.value=='FLASH_ROTATE')
	{
		if(document.getElementById('FLASH_ROTATE_dispdiv'))
			document.getElementById('FLASH_ROTATE_dispdiv').style.display='';	
		/*if(document.getElementById('flv_tr1'))
			document.getElementById('flv_tr1').style.display = 'none';
		if(document.getElementById('flv_tr2'))
			document.getElementById('flv_tr2').style.display = 'none';*/
		if(document.getElementById('flv_rotate_tr'))
			document.getElementById('flv_rotate_tr').style.display = '';	
	
	}
	else if(obj.value=='FLASH')
	{
		if(document.getElementById('FLASH_dispdiv'))
			document.getElementById('FLASH_dispdiv').style.display='';	
		/*if(document.getElementById('flv_tr1'))
			document.getElementById('flv_tr1').style.display = 'none';
		if(document.getElementById('flv_tr2'))
			document.getElementById('flv_tr2').style.display = 'none';	*/
		if(document.getElementById('flv_rotate_tr'))
			document.getElementById('flv_rotate_tr').style.display = 'none';	
	}
	else
	{
		if(document.getElementById('NORMAL_dispdiv'))
			document.getElementById('NORMAL_dispdiv').style.display='';	
		/*if(document.getElementById('flv_tr1'))
			document.getElementById('flv_tr1').style.display = 'none';
		if(document.getElementById('flv_tr2'))
			document.getElementById('flv_tr2').style.display = 'none';	*/
		if(document.getElementById('flv_rotate_tr'))
			document.getElementById('flv_rotate_tr').style.display = 'none';		
	}
}
function handle_addmore_img()
{
	var curcnt = parseInt(document.getElementById('flv_rotate_cnt').value);
	var curcontent = document.getElementById('flv_rotate_div').innerHTML;
	var nxtcnt = curcnt+1;
	if (nxtcnt<10)
		document.getElementById('flv_rotate_div').innerHTML = curcontent +'<br/>#'+nxtcnt+'&nbsp;&nbsp;&nbsp;';
	else	
		document.getElementById('flv_rotate_div').innerHTML = curcontent+'<br/>#'+nxtcnt+'&nbsp;';
	var curcontent = document.getElementById('flv_rotate_div').innerHTML;	
	document.getElementById('flv_rotate_div').innerHTML 	= curcontent + '<input type="file" name="product_flv_rotate_'+nxtcnt+'" id="product_flv_rotate_'+nxtcnt+'" />'; 
	document.getElementById('flv_rotate_cnt').value = nxtcnt;
}
function handle_preorder()
{
	document.frmEditProduct.product_total_preorder_allowed.value ='';
	document.frmEditProduct.product_instock_date.value ='';
	
	if(document.frmEditProduct.product_preorder_allowed.checked==true)
	{
		if(document.frmEditProduct.product_alloworder_notinstock)
			document.frmEditProduct.product_alloworder_notinstock.checked=false;
		if(document.getElementById('preorder_tr1'))
			document.getElementById('preorder_tr1').style.display = '';
		if(document.getElementById('preorder_tr2'))
			document.getElementById('preorder_tr2').style.display = '';	
		document.frmEditProduct.product_total_preorder_allowed.disabled  = false;
		document.frmEditProduct.product_total_preorder_allowed.className = 'normal_class';
		
		document.frmEditProduct.product_instock_date.disabled = false
		document.frmEditProduct.product_instock_date.className = 'normal_class';
			if(document.getElementById('orderoutstock_tr1'))
				document.getElementById('orderoutstock_tr1').style.display = 'none';
					//document.frmEditProduct.product_order_outstock_instock_date.disabled = true;	
	}
	else
	{
		
		document.frmEditProduct.product_total_preorder_allowed.disabled  = true;
		document.frmEditProduct.product_total_preorder_allowed.className = 'disabled_class';
		document.frmEditProduct.product_instock_date.disabled = true
		document.frmEditProduct.product_instock_date.className = 'disabled_class';
		if(document.getElementById('preorder_tr1'))
			document.getElementById('preorder_tr1').style.display = 'none';
		if(document.getElementById('preorder_tr2'))
			document.getElementById('preorder_tr2').style.display = 'none';	
			if(document.frmEditProduct.product_alloworder_notinstock.checked==true)
			{
			if(document.getElementById('orderoutstock_tr1'))
				document.getElementById('orderoutstock_tr1').style.display = '';
					document.frmEditProduct.product_order_outstock_instock_date.disabled = false;
			}			
	}
}
function handle_alwaysaddtocart()
{
	if (document.frmEditProduct.product_alloworder_notinstock.checked==true)
	{
		if(document.frmEditProduct.product_preorder_allowed.checked==true)
		{
			document.frmEditProduct.product_preorder_allowed.checked = false;
			 handle_preorder();
		}	
   	}
     	//document.frmEditProduct.product_order_outstock_instock_date.value ='';	
		if(document.frmEditProduct.product_alloworder_notinstock.checked==true)
		{	
			if(document.getElementById('orderoutstock_tr1'))
				document.getElementById('orderoutstock_tr1').style.display = '';				
			document.frmEditProduct.product_order_outstock_instock_date.disabled = false
			document.frmEditProduct.product_order_outstock_instock_date.className = 'normal_class';			
		}
		else
		{ 
			if(document.getElementById('orderoutstock_tr1'))
				document.getElementById('orderoutstock_tr1').style.display = 'none';	
			document.frmEditProduct.product_order_outstock_instock_date.disabled  = true;
			document.frmEditProduct.product_order_outstock_instock_date.className = 'disabled_class';
	    }	
}
function handle_bulkdiscount(obj)
{
	if(obj.checked)
		document.getElementById('bulkdisc_tr').style.display='';
	else
		document.getElementById('bulkdisc_tr').style.display='none';
}
function handle_product_sale_icon(obj)
{
	if(obj.checked)
	{
		document.getElementById('product_saleicon_text_id').style.display='';
		document.frmEditProduct.product_newicon_show.checked = false;
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
		document.frmEditProduct.product_saleicon_show.checked = false;
		document.getElementById('product_saleicon_text_id').style.display='none';
	}	
	else
	{
		document.getElementById('product_newicon_text_id').style.display='none';
	}	
}
function copyretail_to_stores()
{
	var webprice = document.frmEditProduct.product_webprice.value;
	for(i=0;i<document.frmEditProduct.elements.length;i++)
	{
		if (document.frmEditProduct.elements[i].name.substr(0,27)=='product_branch_retailprice_')
		{
			document.frmEditProduct.elements[i].value = webprice;		
		}
	}
}
function save_ImageDisplay_format()
{
	document.frmEditProduct.fpurpose.value ='save_product_imagelisttype';
	document.frmEditProduct.submit();
}
function show_preset_variables()
{
		var editid																		= document.getElementById('checkbox[0]').value;
		var qrystr																		= 'prod_id='+editid;
		var fpurpose																	= 'show_preset_var';
		document.getElementById('retdiv_id').value 						= 'presetvar_div';
		obj																				= eval("document.getElementById('presetvar_div')");
		obj.innerHTML 																= '<center><img src="images/loading.gif" alt="Loading"></center>';
		/* Calling the ajax function */
		Handlewith_Ajax('services/products.php','fpurpose='+fpurpose+'&'+qrystr);
		
}
function hide_preset_variables()
{
	if(document.getElementById('presetvar_div'))
		document.getElementById('presetvar_div').innerHTML = '';
}
function assign_preset_variables()
{
	var atleastone 	= 0;
	var prodid			= document.getElementById('checkbox[0]').value;
	var ch_ids 			= '';
		for(i=0;i<document.frmEditProduct.elements.length;i++)
		{
			if (document.frmEditProduct.elements[i].type =='checkbox' && document.frmEditProduct.elements[i].name== 'checkboxpresetvar[]')
			{
				if (document.frmEditProduct.elements[i].checked==true)
				{
					atleastone = 1;
					if (ch_ids!='')
						ch_ids += '~';
					 ch_ids += document.frmEditProduct.elements[i].value;
				}	
			}
		}
		
		if (atleastone==0)
		{
			alert('Please select atleast one preset variable for assigning');
		}
		else
		{
			if(confirm('Are you sure you want to assign the selected preset variables to current product?'))
			{
				var editid														= document.getElementById('checkbox[0]').value;
				var qrystr														= '';
				var fpurpose													= 'assign_preset_var';
				document.getElementById('retdiv_id').value 		= 'main_product_div';
				obj																= eval("document.getElementById('main_product_div')");
				obj.innerHTML 												= '<center><img src="images/loading.gif" alt="Loading"></center>';
				Handlewith_Ajax('services/products.php','fpurpose='+fpurpose+'&cur_prodid='+prodid+'&ch_ids='+ch_ids+'&'+qrystr);
			}	
		}	
}
function hide_special_msg()
{
	document.getElementById('special_alert_tr').style.display='none';
}
function clear_outofstockdate()
{
	document.getElementById('product_order_outstock_instock_date').value ='';
}
function show_categorypopup(invid)
{
	var qrystr														= '';
	var ch_ids   = '';
	var atleastone = 0;
	for(i=0;i<document.frmEditProduct.elements.length;i++)
	{
		if (document.frmEditProduct.elements[i].type =='hidden' && document.frmEditProduct.elements[i].name== 'category_id[]')
			{			
					atleastone ++;
					if (ch_ids!='')
						ch_ids += '~';
					 ch_ids += document.frmEditProduct.elements[i].value;
			}
	}	
	if(atleastone>0)
	{
		 qrystr +='ch_ids='+ch_ids;
	}
		qrystr +='&mod=edit';

	var fpurpose													= 'show_category_popup';
	document.getElementById('retdiv_id').value 						= 'moveto_showcategory_div';
	obj																= eval("document.getElementById('moveto_showcategory_div')");
	obj.innerHTML 													= '<center><img src="images/loading.gif" alt="Loading"></center>';
	showme('#moveto_showcategory_div');

	Handlewith_Ajax('services/products.php','fpurpose='+fpurpose+'&cur_prodid='+invid+'&'+qrystr);
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
	for(i=0;i<document.frmEditProduct.elements.length;i++)
	{
	   
	   if ((document.frmEditProduct.elements[i].type =='hidden') )
		{
			if(document.frmEditProduct.elements[i].name== 'passcheckbox_assigncat[]')
			{
					
						if (ch_ids!='')
							ch_ids += '~';
						 ch_ids += document.frmEditProduct.elements[i].value;				
			}
		}
	}	
	qrystr = 'catname='+catname+'&parentid='+parent+'&perpage='+perpage+'&ch_ids='+ch_ids;;
	qrystr +='&mod=edit';

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

    for(i=0;i<document.frmEditProduct.elements.length;i++)
	{
	   
	   if ((document.frmEditProduct.elements[i].type =='hidden') )
		{
			if(document.frmEditProduct.elements[i].name== 'passcheckbox_assigncat[]')
			{					
						if (ch_ids!='')
							ch_ids += '~';
						 ch_ids += document.frmEditProduct.elements[i].value;				
			}
		}
	}		
    //var catgroup                                                    = document.getElementById('catgroupid_pop').value ;
	qrystr = 'catname='+catname+'&parentid='+parent+'&perpage='+perpage+'&ch_ids='+ch_ids;
	qrystr +='&mod=edit';

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
	for(i=0;i<document.frmEditProduct.elements.length;i++)
	{
	   
	   if ((document.frmEditProduct.elements[i].type =='checkbox' || document.frmEditProduct.elements[i].type =='hidden') )
		{
			if (document.frmEditProduct.elements[i].name.substring(0,20)=='default_category_id_')
			{  
				if(document.frmEditProduct.elements[i].checked==true)
				{
					defval   = document.frmEditProduct.elements[i].value;
				}
			}
			if(document.frmEditProduct.elements[i].name== 'checkbox_assigncat[]')
			{
				if (document.frmEditProduct.elements[i].checked==true)
				{
					atleastone ++;
					if (ch_ids!='')
						ch_ids += '~';
					 ch_ids += document.frmEditProduct.elements[i].value;
				} 
			}
			if(document.frmEditProduct.elements[i].name== 'passcheckbox_assigncat[]')
			{
					
						if (ch_ids!='')
							ch_ids += '~';
						 ch_ids += document.frmEditProduct.elements[i].value;				
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
	
	for(i=0;i<document.frmEditProduct.elements.length;i++)
	{ 
	if (document.frmEditProduct.elements[i].type =='checkbox' && document.frmEditProduct.elements[i].name.substring(0,20)=='default_category_id_')
		{  	var eleval   = document.frmEditProduct.elements[i].value;

			 if(document.frmEditProduct.elements[i].checked==true)
				{
					if(document.frmEditProduct.elements[i].value==cid)
					{ 
						atleastone++;						
						document.frmEditProduct.elements[i].checked=true;
						document.frmEditProduct.default_category_id.value=cid;
						document.getElementById("li_default_category_id_"+cid).className = "li_category_selected";

					}
					else
					{
						document.frmEditProduct.elements[i].checked=false;
						document.getElementById("li_default_category_id_"+eleval).className = "li_category";

					}
				}			
				else
				{
				        document.frmEditProduct.elements[i].checked=false;
						document.getElementById("li_default_category_id_"+eleval).className = "li_category";
				}
										
		}		

	}
	if(atleastone==0)
		{
			document.frmEditProduct.default_category_id.value=0;

		} 
}
function remove_category(id,prodid)
{
	var cnt =0 ;
		var qrystr     = '';
		var ch_ids     = '';
		var defval     = '';

		for(i=0;i<document.frmEditProduct.elements.length;i++)
			{ 
					
				if (document.frmEditProduct.elements[i].name.substring(0,20)=='default_category_id_')
				{  
					if(document.frmEditProduct.elements[i].checked==true)
					{
						defval   = document.frmEditProduct.elements[i].value;
					}
				}
				if (document.frmEditProduct.elements[i].type =='hidden' && document.frmEditProduct.elements[i].name=='category_id[]')
				{
					
					cnt++;
					if (ch_ids!='')
						ch_ids += '~';
					 ch_ids += document.frmEditProduct.elements[i].value;
				}
		}
		qrystr += '&defval='+defval;
		qrystr += '&ch_ids='+ch_ids;
		
			if(confirm('Are you sure you want to unassign the product from this Category?'))
			{
					fpurpose = 'remove_category';
					document.getElementById('modused').value  						= 'remove';
					document.getElementById('retdiv_id').value 						= 'categorymain_div';
					retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
					retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
					Handlewith_Ajax('services/products.php','fpurpose='+fpurpose+'&cur_prodid='+prodid+'&cur_catid='+id+'&'+qrystr);
		    }
		
		if(document.frmEditProduct.default_category_id.value==id)
		{
		  document.frmEditProduct.default_category_id.value='';
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
	function call_save_seo(mod)
{
	var atleastone 			= 0;
	var editid												= document.getElementById('checkbox[0]').value;
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
	for(i=0;i<document.frmEditProduct.elements.length;i++)
	{  
	if (document.frmEditProduct.elements[i].type =='text' && document.frmEditProduct.elements[i].name.substr(0,7)== 'keyword')
		{			
				atleastone = 1;
				if (ch_ids!='')
					ch_ids += '~';
				 ch_ids += document.frmEditProduct.elements[i].value;			
		}
	}
	atleastmsg = "Enter the Title";
	var page_title = '';
	var meta ='';
	page_title = document.frmEditProduct.page_title.value;
	meta       = document.frmEditProduct.page_meta.value;
	if(document.frmEditProduct.is_apparel_site)
	{
	var gender = document.frmEditProduct.txtgender.value;	
	var age = document.frmEditProduct.txtage.value;	
	var colour = document.frmEditProduct.txtcolour.value;	
	var size  = document.frmEditProduct.txtsize.value;	
	var  is_apparel_site = 1;
	}
	
	qrystr +='&page_title='+page_title+'&page_meta='+meta+'&txtgender='+gender+'&txtage='+age+'&txtcolour='+colour+'&txtsize='+size+'&is_apparel_site='+is_apparel_site;	 
	//if(page_title=='')
	//{
		//alert(atleastmsg);
	//}
	//else
	{
		
			retobj 											= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
			retobj.innerHTML 								= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/products.php','fpurpose='+fpurpose+'&prod_id='+editid+'&ch_ids='+ch_ids+'&'+qrystr);
			
	}	
}
</script>
		<form name='frmEditProduct' action='home.php?request=products' method="post" onsubmit="return valforms(this);" enctype="multipart/form-data">
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
		<input type="hidden" name="in_mobile_api_sites" id="in_mobile_api_sites" value="<?=$_REQUEST['in_mobile_api_sites']?>" />
		<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
		<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
		<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
		<input type="hidden" name="pss_records_per_page" id="pss_records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
		<input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
		<input type="hidden" name="pss_start" id="pss_start" value="<?=$_REQUEST['start']?>" />
		<input type="hidden" name="pss_pg" id="pss_pg" value="<?=$_REQUEST['pg']?>" />
		<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
		<input type="hidden" name="parent_id" id="parent_id" value="<?=$_REQUEST['parent_id']?>" />
		<input type="hidden" name="checkbox[0]" id="checkbox[0]" value="<? echo $_REQUEST['checkbox'][0];?>" />
		<input type="hidden" name="edit_id" id="edit_id" value="" />
		<input type="hidden" name="src_page" id="src_page" value="prod" />
		<input type="hidden" name="src_id" id="src_id" value="<? echo $_REQUEST['checkbox'][0];?>" />
		<input type="hidden" name="overridecase" id="overridecase" value="" />
		<input type="hidden" name="retdiv_id" id="retdiv_id" value="maincontent" />
		<input type="hidden" name="retdiv_more" id="retdiv_more" value="" />
		<input type="hidden" name="curtab" id="curtab" value="<?=$curtab?>" />
		<input type="hidden" name="comb_id" id="comb_id" value="" />
		<input type="hidden" name="pass_strs" id="pass_strs" value="" />	
		<input type="hidden" name="fpurpose" id="fpurpose" value="save_edit" />
		<input type="hidden" name="default_category_id" id="default_category_id" value="<?php echo $row_prod['product_default_category_id'] ?>" />
        <input type="hidden" name="modused" id="modused" value="" />
		 <div id="popup_bg_div" class="popupbg_fadclass" style="display:none" ></div>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
          <td colspan="4" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=products&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&start=<?php echo $_REQUEST['start']?>&pg=<?php echo $_REQUEST['pg']?>&records_per_page=<?php echo $_REQUEST['records_per_page']?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>">List Products</a><span>Edit Product "<?=$row_prod['product_name']?>"</span></div>
		  </td>
        </tr>
        <tr>
		  <td colspan="4" align="left" valign="middle" class="helpmsgtd_main">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
		<tr>
          <td colspan="4" align="center" valign="middle">
			  <div style="top: 0px; left: 0px; position: fixed; display: none;" class="flashvideo_outer" id="div_defaultFlash_outer"></div>
          <div id="moveto_showcategory_div" class="processing_divcls_big_heightA" style="display:none" >
	</div>
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabmenu_x">
			  <tr>
				<td align="left" onClick="handle_tabs('main_tab_td')" class="<?php if($curtab=='main_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="main_tab_td"><span>Main Info</span></td>
				<td align="left" onClick="handle_tabs('desc_tab_td')" class="<?php if($curtab=='desc_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="desc_tab_td"><span>Description</span> </td>
				<?php
					if ($row_prod['product_downloadable_allowed']=='N')
					{
				?>	
				<td align="left"  onClick="handle_tabs('variable_tab_td')"class="<?php if($curtab=='variable_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="variable_tab_td"><span>Variables</span></td>
			  	<?php
					}
				?>	
				<td align="left" onClick="handle_tabs('stock_tab_td')" class="<?php if($curtab=='show_stock_tab' or $curtab=='stock_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="stock_tab_td"><span>Stock </span></td>
				<td align="left"  onClick="handle_tabs('linked_tab_td')" class="<?php if($curtab=='linked_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="linked_tab_td"><span>Linked / Sub Products </span> </td>
				<td align="left" onClick="handle_tabs('images_tab_td')" class="<?php if($curtab=='images_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="images_tab_td"><span>Images</span> </td>
				<td align="left" onClick="handle_tabs('googleimages_tab_td')" class="<?php if($curtab=='googleimages_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="googleimages_tab_td"><span>Google feed Images</span> </td>
				<?php
				if($ecom_siteid==115)
				{
				?>
				<td align="left" onClick="handle_tabs('videos_tab_td')" class="<?php if($curtab=='videos_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="videos_tab_td"><span>Videos</span> </td>
				<?php				   
				}
					// Checking whether the size chart option is to be shown here
					if(is_module_valid('mod_sizechart','any'))
					{
				?>
						<td align="left" onClick="handle_tabs('size_chart_td')" class="<?php if($curtab=='size_chart_td') echo "toptab_sel"; else echo "toptab"?>" id="size_chart_td"><span>More Product Specifications</span></td>
				        <?php
					}
					if ($row_prod['product_downloadable_allowed']=='Y')
					{
				?>
						<td align="left" onClick="handle_tabs('download_tab_td')" class="<?php if($curtab=='download_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="download_tab_td"><span>Downloadable Products</span></td>
				<?php
					}		
					// Check whether the promotions and offers table is to be displayed for this product
				$show_tab = 0;
				// Check whether atleast one combo exists which include this product
				$sql_combo 			= "SELECT a.combo_id  
											FROM 
												combo a ,combo_products b     
											WHERE 
												b.products_product_id=$edit_id 
												AND a.combo_id = b.combo_combo_id 
												AND a.sites_site_id = $ecom_siteid 
											LIMIT 
												1";
				$ret_combo 			= $db->query($sql_combo);
				$tot_combo_exists	= 	$db->num_rows($ret_combo);
				if ($tot_combo_exists>0)
					$show_tab = 1;
				if($show_tab==0)
				{
						// Check whether atleast one shelf exists which include this product
						$sql_shelf 			= "SELECT a.shelf_id  
													FROM 
														product_shelf a ,product_shelf_product b     
													WHERE 
														b.products_product_id=$edit_id 
														AND a.shelf_id = b.product_shelf_shelf_id 
														AND a.sites_site_id = $ecom_siteid 
													LIMIT 
														1";
						$ret_shelf 				= $db->query($sql_shelf);
						$tot_shelf_exists	= 	$db->num_rows($ret_shelf);
						if ($tot_shelf_exists>0)
							$show_tab = 1;
				}	
				if($show_tab==0)
				{
					// Check whether atleast one shop exists which include this product
					$sql_shop 				= "SELECT a.shopbrand_id 
														FROM 
															product_shopbybrand a ,product_shopbybrand_product_map b     
														WHERE 
															b.products_product_id=$edit_id 
															AND a.shopbrand_id = b.product_shopbybrand_shopbrand_id 
															AND a.sites_site_id = $ecom_siteid 
														LIMIT 
															1";
					$ret_shop 				= $db->query($sql_shop);
					$tot_shop_exists		= 	$db->num_rows($ret_shop);
					if ($tot_shop_exists>0)
							$show_tab = 1;
				}	
				if($show_tab==0)
				{
					// Check whether atleast one customer group exists which include this product
					$sql_cust					 = "SELECT a.cust_disc_grp_id 
															FROM 
																customer_discount_group a ,customer_discount_group_products_map b     
															WHERE 
																b.products_product_id=$edit_id 
																AND a.cust_disc_grp_id = b.customer_discount_group_cust_disc_grp_id 
																AND a.sites_site_id = $ecom_siteid 
															LIMIT 
																1";
					$ret_cust 						= $db->query($sql_cust);
					$tot_custgroup_exists		= 	$db->num_rows($ret_cust);
					if ($tot_custgroup_exists>0)
						$show_tab = 1;
				}
				if($show_tab==0)
				{				
					// Check whether atleast one promotional code exists which include this product
					$sql_prom 						= "SELECT a.code_id 
																FROM 
																	promotional_code a ,promotional_code_product b     
																WHERE 
																	b.products_product_id=$edit_id 
																	AND a.code_id = b.promotional_code_code_id 
																	AND a.sites_site_id = $ecom_siteid 
																LIMIT 
																	1";
					$ret_prom 						= $db->query($sql_prom);
					$tot_prom_exists			= $db->num_rows($ret_prom);
					if ($tot_prom_exists>0)
						$show_tab = 1;
				}	
				
				// Check whether this product is set as featured product
				if($show_tab ==0)
				{
					$sql_feat = "SELECT feature_id 
											FROM 
												product_featured 
											WHERE 
												products_product_id = $edit_id 
												AND sites_site_id = $ecom_siteid 
											LIMIT 
												1";
					$ret_feat = $db->query($sql_feat);
					if ($db->num_rows($ret_feat)>0)
						$show_tab = 1;
				}
				if ($show_tab == 1)
				{
				?>
			   		<td align="left" onClick="handle_tabs('offer_tab_td')" class="<?php if($curtab=='offer_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="offer_tab_td"><span>Promotions and Offers</span> </td>
				<?php
				}
				?>
				<td align="left" onClick="handle_tabs('sales_tab_td')" class="<?php if($curtab=='sales_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="sales_tab_td"><span>Sales Details</span> </td>
			    <td align="left" onClick="handle_tabs('seo_tab_td')" class="<?php if($curtab=='seo_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="seo_tab_td"><span>SEO Settings</span> </td>

			    <?php 
				if($ecom_enable_searchrefine_category==1) 
				{
				?>
				<td align="left" onClick="handle_tabs('catvar_tab_td')" class="<?php if($curtab=='catvar_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="catvar_tab_td"><span>Search Refine</span></td>
				<?php
				}
				?>
				<td width="99%" align="left">&nbsp;</td>
			  </tr>
			</table>
		  </td>
        </tr>
         <tr>
           <td colspan="4" align="left" valign="top" class="tdcolorgraynormal" >
		   <div id="main_product_div">
		   <?php
				if($curtab=='main_tab_td')
				{
					show_prodmaininfo($_REQUEST['checkbox'][0],$alert);
				}
				elseif($curtab=='desc_tab_td')
				{
					//show_proddescinfo($_REQUEST['checkbox'][0],$alert);
				}
				elseif($curtab=='variable_tab_td')
				{
					show_prodvariableinfo($_REQUEST['checkbox'][0],$alert);
				}
				elseif ($curtab=='linked_tab_td')
				{
					show_prodlinkedinfo($_REQUEST['checkbox'][0],$alert,$_REQUEST['srcs']);
				}
				elseif ($curtab=='images_tab_td')
				{
					show_prodimageinfo($_REQUEST['checkbox'][0],$alert);
				}	
				elseif ($curtab=='videos_tab_td')
				{
					show_prodvideoinfo($_REQUEST['checkbox'][0],$alert);
				}	
				elseif ($curtab=='googleimages_tab_td')
				{
					show_googleprodimageinfo($_REQUEST['checkbox'][0],$alert);
				}
				elseif ($curtab=='attach_tab_td')
				{
					show_prodattachinfo($_REQUEST['checkbox'][0],$alert);
				}
				elseif ($curtab=='size_chart_td')
				{
					show_prodsizecharttab($_REQUEST['checkbox'][0],$alert);
				}	
				elseif ($curtab=='download_tab_td')
				{
					show_proddownloadinfo($_REQUEST['checkbox'][0],$alert);
				}	
				elseif ($curtab=='stock_tab_td')
				{
					show_prodstockinfo($_REQUEST['checkbox'][0],0,$alert);
				}		
				elseif ($curtab=='catvar_tab_td')
				{
					show_prodcatvarinfo($_REQUEST['checkbox'][0],0,$alert);
				}
			?>	
		   </div>
		   </td>
         </tr>
       <?php 
	   /*?> <tr>
          <td width="34%" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td width="66%" colspan="3" align="left" valign="middle" class="tdcolorgray">

		 </td>
        </tr><?php */?>
      </table>
	   
 	<table width="100%" border="0" cellspacing="0" cellpadding="0" <?php echo ($curtab=='desc_tab_td')?'style="display:"':'style="display:none"'?> id="desc_main_td">
	<tr><td colspan="2">
		<?php
		/*
			<div id="moveto_showcategory_div" class="processing_divcls_big_height" style="top: 410px;" style="display:none">
	</div>
	*/?> 
	<div class="editarea_div">
	<table width="100%">
		 <?php
			if($alert)
			{
		?>
        	<tr>
          		<td colspan="2" align="center" valign="middle" class="errormsg" id="mainprod_alert" ><?=$alert?></td>
          	</tr>
		 <?php
		 	}
		 ?>
		 <tr>
			<td align="left" colspan="2" class="helpmsgtd"><div class="helpmsg_divcls"><?=get_help_messages('EDIT_PROD_DESC_MAIN')?></div></td>
		  </tr>
		  <tr>
			<td align="left" valign="top"  class="tdcolorgray">Long Description</td>
			<td align="left" valign="top" class="tdcolorgray">
				<?php
					$editor_elements = "long_desc";
					include_once(ORG_DOCROOT."/console/js/tinymce.php");
					/*$editor = new FCKeditor('long_desc') ;
					$editor->BasePath 	= '/console/js/FCKeditor/';
					$editor->Width 		= '650';
					$editor->Height 	= '300';
					$editor->ToolbarSet = 'BshopWithImages';
					$editor->Value 		= stripslashes($row_prod['product_longdesc']);
					$editor->Create();*/
				?>
				<textarea style="height:300px; width:650px" id="long_desc" name="long_desc"><?=stripslashes($row_prod['product_longdesc'])?></textarea>
			</td>
		  </tr>
		   <tr>
			<td align="left" valign="top"  class="tdcolorgray" nowrap="nowrap">Product Keywords</td>
			<td align="left" valign="top" class="tdcolorgray">
			<textarea name="product_keywords" cols="75" rows="6"><?=$row_prod['product_keywords'] ?></textarea>
			 <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_KEYWORDS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
			</td>
		  </tr>
		  <tr>
			<td align="left" colspan="2" class="tdcolorgray">&nbsp;</td>
		  </tr>
			<tr>
				<td align="center" colspan="2">
					<input name="prod_Submit" type="button" class="red" value="Save" onclick="call_ajax_savedescription()" />
				</td>
			</tr>	
			<tr>	          
		  <td colspan="4" align="left" valign="bottom">
		  <div class="productdet_mainoutercls">
		  <table width="100%" border="0" cellspacing="1" cellpadding="1">		
		  <tr>	          
		  <td colspan="4" align="left" valign="bottom">
            <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr >
              <td width="3%" class="seperationtd"><img id="cat_descimgtag" src="images/plus.gif" border="0" onclick="handle_expansionall(this,'prodtab')" title="Click"/></td>
              <td width="97%" align="left" class="seperationtd" style="cursor:pointer"  onclick="handle_expansionall(document.getElementById('cat_descimgtag'),'prodtab')" >More Description Tabs </td>
            </tr>
			 <tr>
			<td align="left" colspan="2" class="helpmsgtd"><div class="helpmsg_divcls"><?=get_help_messages('EDIT_PROD_TAB_MAIN')?></div></td>
		  </tr>
          </table></td>
        </tr>
        <tr >
		   <?php
			// Get the list of tabs for this product
			$sql_tab = "SELECT tab_id FROM product_tabs
						 WHERE products_product_id=$edit_id ORDER BY tab_order LIMIT 1";
			$ret_tab= $db->query($sql_tab);
			
			// Check whether any general product tab exists
			$sql_gen = "SELECT common_tab_id 
							FROM 
								product_common_tabs 
							WHERE 
								sites_site_id = $ecom_siteid
							LIMIT 
								1";
			$ret_gen = $db->query($sql_gen);
		 ?>
		 <tr>
		  <td align="right" colspan="4" class="tdcolorgray_buttons">
		   <?php
				if($db->num_rows($ret_gen))
				{
			?>
					<input name="Addmoregen_tab" type="button" class="red" id="Addmoregen_tab" value="Assign Common Tabs" onclick="document.frmEditProduct.fpurpose.value='add_prodgentab';document.frmEditProduct.submit();" />
					<a href="#" onmouseover ="ddrivetip('Allows to assign existing Common Product Tabs to this product.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
			<?php					
				}
			?>
				<input name="Addmore_tab" type="button" class="red" id="Addmore_tab" value="Add More" onclick="document.frmEditProduct.fpurpose.value='add_prodtab';document.frmEditProduct.submit();" />
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_ADDI_DTAILS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
				<?php
				if ($db->num_rows($ret_tab))
				{
				?>
					<div id="tabunassign_div" class="unassign_div" style="display:none">
					Change Hidden Status to 
					<?php
						$prodtab_chstatus = array(0=>'No',1=>'Yes');
						echo generateselectbox('prodtab_chstatus',$prodtab_chstatus,0);
					?>
					<input name="prodtab_chstatus" type="button" class="red" id="prodtab_chstatus" value="Change" onclick="call_ajax_changestatusprodall('prodtab','checkboxtab[]')" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_DESC_CHSTATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
					&nbsp;&nbsp;<input name="prodtab_chorder" type="button" class="red" id="prodtab_chorder" value="Save Order" onclick="call_ajax_changeorderall('prodtab','checkboxtab[]')" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_DESC_ORDER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
					
					&nbsp;&nbsp;&nbsp;<input name="prodtab_delete" type="button" class="red" id="prodtab_delete" value="Delete / Unassign" onclick="call_ajax_deleteall('prodtab','checkboxtab[]')" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_DESC_DEL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>				</div>	
				<?php
				}				
				?>		  </td>
        </tr>
		<tr id="tab_tr">
			<td align="right" colspan="4" class="tdcolorgray_buttons">
				<div id="prodtab_div" style="text-align:center"></div>
			</td>
		</tr>
		</table>
		  </div>
		</td>
		</tr>
		
		<tr>	          
		  <td colspan="4" align="left" valign="bottom">
		  <div class="productdet_mainoutercls">
		  <table width="100%" border="0" cellspacing="1" cellpadding="1">
		<tr>
          <td colspan="4" align="left" valign="bottom">
            <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td width="3%" class="seperationtd"><img id="cat_attachimgtag" src="images/plus.gif" border="0" onclick="handle_expansionall(this,'prodattach')" title="Click"/></td>
              <td width="97%" align="left" class="seperationtd" style="cursor:pointer" onclick="handle_expansionall(document.getElementById('cat_attachimgtag'),'prodattach')">Product Attachments</td>
            </tr>
			 <tr>
			<td align="left" colspan="2" class="helpmsgtd"><div class="helpmsg_divcls"><?=get_help_messages('EDIT_PROD_ATTACH_MAIN')?></div></td>
		  </tr>
          </table></td>
        </tr>
        <tr >
		   <?php
			// Get the list of product attachments 
			$sql_attach = "SELECT attachment_id,attachment_title,attachment_order,attachment_hide,attachment_type 
										FROM 
											product_attachments  
										WHERE 
											products_product_id=$edit_id 
										LIMIT 
										1";
			$ret_attach= $db->query($sql_attach);
			// Check whether any general product tab exists
			$sql_att = "SELECT common_attachment_id  
							FROM 
								product_common_attachments  
							WHERE 
								sites_site_id = $ecom_siteid
							LIMIT 
								1";
			$ret_att = $db->query($sql_att);
		 ?>
		 <tr>
		  <td align="right" colspan="4" class="tdcolorgray_buttons">
		  <?php
				if($db->num_rows($ret_att))
				{
			?>
					<input name="Addmoregen_attach" type="button" class="red" id="Addmoregen_attach" value="Assign Common Attachments" onclick="document.frmEditProduct.fpurpose.value='add_prodgenattach';document.frmEditProduct.submit();" />
					<a href="#" onmouseover ="ddrivetip('Allows to add additional description tabs for this product.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
			<?php					
				}
			?>
				<input name="Addmore_tab" type="button" class="red" id="Addmore_tab" value="Add More" onclick="document.frmEditProduct.fpurpose.value='add_prodattach';document.frmEditProduct.submit();" />
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_ATTCH_DTAILS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
				<?php
				if ($db->num_rows($ret_attach))
				{
				?>
				<div id="prodattachunassign_div" class="unassign_div" style="display:none">
				Change Hidden Status to 
				<?php
					$prodattach_charr = array(0=>'No',1=>'Yes');
					echo generateselectbox('prodattach_chstatus',$prodattach_charr,0);
				?>
				<input name="prodattach_chstatusbtn" type="button" class="red" id="prodattach_chstatusbtn" value="Change" onclick="call_ajax_changestatusprodall('prodattach','checkboxattach[]')" />
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_ATTCH_CHSTATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
				&nbsp;&nbsp;<input name="prodtab_chorder" type="button" class="red" id="prodtab_chorder" value="Save Order" onclick="call_ajax_changeorderall('prodattach','checkboxattach[]')" />
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_ATCH_ORDER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
				
				&nbsp;&nbsp;&nbsp;<input name="prodtab_delete" type="button" class="red" id="prodtab_delete" value="Delete / Unassign" onclick="call_ajax_deleteall('prodattach','checkboxattach[]')" />
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_ATCH_DEL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>				</div>	
				<?php
				}				
				?>		  </td>
        </tr>
		<tr id="prodattach_tr">
			<td align="right" colspan="4" class="tdcolorgray_buttons">
				<div id="prodattach_div" style="text-align:center"></div>
			</td>
		</tr>
		</table>
		</td>
		</tr>
		</table>
		</div>
		</div>
		</td>
		</tr>
		</table>
		
</form>	
<form method="post" action="do_stocktab_offline.php" name="temp_form" id="temp_form">.
<input type="hidden" name="prod_id" id="prod_id" value="<?php echo $edit_id?>" />
<input type="hidden" name="cur_mod" id="cur_mod" value="stock_download" />
</form>

