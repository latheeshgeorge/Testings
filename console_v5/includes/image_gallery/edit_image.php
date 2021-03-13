<?php
	/*#################################################################
	# Script Name 	: edit_image.php
	# Description 	: Page for editing a selected image
	# Coded by 		: Sny
	# Created on	: 17-Jul-2007
	# Modified by	: Sny
	# Modified On	: 30-Jul-2007
	#################################################################*/
//#Define constants for this page
$page_type = 'Edit Image';
$help_msg = get_help_messages('EDIT_IMG_GAL_IMG');
$records_per_page 	= ($_REQUEST['records_per_page'])?$_REQUEST['records_per_page']:10;//#Total records shown in a page
$pg 				= ($_REQUEST['pg'])?$_REQUEST['pg']:0;//#Total records shown in a page
$curtab				= ($_REQUEST['curtab'])?$_REQUEST['curtab']:'show_imageedit_general';



// Check whether the current image is valid
$sql_check = "SELECT image_id FROM images WHERE image_id=".$_REQUEST['edit_id']." AND sites_site_id=$ecom_siteid LIMIT 1";
$ret_check = $db->query($sql_check);
if($db->num_rows($ret_check)==0)  { echo " <font color='red'> You Are Not Authorised  </a>"; exit; }
if($db->num_rows($ret_check)==0)
{
	echo "<span class='redtext'>Invalid Information</span>";
	exit;
}
// Section to decide whether a back link is to be shown in the edit page
$back_link = '';
switch($back_frm)
{	
	case 'prod_cat':// case of coming from product category page
		$back_link = "<a href='home.php?request=prod_cat&fpurpose=edit&curtab=image_tab_td&checkbox[0]=".$_REQUEST['org_id']."'>Edit Product Category</a>".' ';
	break;
	case 'prod_shop':// case of coming from product category page
		$back_link = "<a href='home.php?request=shopbybrand&fpurpose=edit&curtab=image_tab_td&checkbox[0]=".$_REQUEST['org_id']."'>Edit Shopbybrand</a>".'  ';
	break;
	case 'prods':// case of coming from products page
		$back_link = "<a href='home.php?request=products&fpurpose=edit&curtab=images_tab_td&checkbox[0]=".$_REQUEST['org_id']."'>Edit Products</a>".' ';
	break;
	case 'gift_ribbon':// case of coming from giftwrap ribbon
		$back_link = "<a href='home.php?request=giftwrap_ribbons&fpurpose=edit&curtab=images_tab_td&checkbox[0]=".$_REQUEST['org_id']."'>Edit Ribbon</a>".' ';
	break;
	case 'gift_card':// case of coming from giftwrap card
		$back_link = "<a href='home.php?request=giftwrap_cards&fpurpose=edit&curtab=images_tab_td&checkbox[0]=".$_REQUEST['org_id']."'>Edit Cards</a>".' ';
	break;
	case 'gift_paper':// case of coming from giftwrap paper
		$back_link = "<a href='home.php?request=giftwrap_papers&fpurpose=edit&curtab=images_tab_td&checkbox[0]=".$_REQUEST['org_id']."'>Edit Papers</a>".' ';
	break;
	case 'gift_bow':// case of coming from giftwrap bowr
		$back_link = "<a href='home.php?request=giftwrap_bows&fpurpose=edit&curtab=images_tab_td&checkbox[0]=".$_REQUEST['org_id']."'>Edit Bows</a>".' ';
	break;
};

?>	
<script type="text/javascript" src="js/simpletreemenu.js"></script>
<script language="javascript" type="text/javascript">
function ajax_return_contents() 
{
	var ret_val = '';
	var disp 	= 'no';
	if(req.readyState==4)
	{
		if(req.status==200)
		{
			ret_val 		= req.responseText;
			var targetdiv 	= document.getElementById('retdiv_id').value;
			var norecdiv 	= document.getElementById('retdiv_more').value;
			hide_allother_error_td();
			targetobj 			= eval("document.getElementById('"+targetdiv+"')");
			targetobj.innerHTML = ret_val; /* Setting the output to required div */
			/* Decide the display of action buttons*/
			switch(targetdiv)
			{
				case 'prodcatassign_div':
					if(document.getElementById('prodcat_norec'))
					{
						if(document.getElementById('prodcat_norec').value==1)
						{
							disp = 'none';
						}	
						else
							disp = '';
					}
					else
						disp = '';	
				break;
				case 'prodshopassign_div':
					if(document.getElementById('prodshop_norec'))
					{
						if(document.getElementById('prodshop_norec').value==1)
						{
							disp = 'none';
						}	
						else
							disp = '';
					}
					else
						disp = '';	
				break;
				case 'prodsassign_div':
					if(document.getElementById('prods_norec'))
					{
						if(document.getElementById('prods_norec').value==1)
						{
							disp = 'none';
						}	
						else
							disp = '';
					}
					else
						disp = '';	
				break;
				case 'comboassign_div':
					if(document.getElementById('combo_norec'))
					{
						if(document.getElementById('combo_norec').value==1)
						{
							disp = 'none';
						}	
						else
							disp = '';
					}
					else
						disp = '';	
				break;
				case 'paperassign_div':
					if(document.getElementById('paper_norec'))
					{
						if(document.getElementById('paper_norec').value==1)
						{
							disp = 'none';
						}	
						else
							disp = '';
					}
					else
						disp = '';	
				break;
				case 'cardassign_div':
					if(document.getElementById('card_norec'))
					{
						if(document.getElementById('card_norec').value==1)
						{
							disp = 'none';
						}	
						else
							disp = '';
					}
					else
						disp = '';	
				break;
				case 'ribbonassign_div':
					if(document.getElementById('ribbon_norec'))
					{
						if(document.getElementById('ribbon_norec').value==1)
						{
							disp = 'none';
						}	
						else
							disp = '';
					}
					else
						disp = '';	
				break;
				case 'bowassign_div':
					if(document.getElementById('bow_norec'))
					{
						if(document.getElementById('bow_norec').value==1)
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
			//alert(disp+' -- '+norecdiv);
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
function hide_allother_error_td()
{
	if (document.getElementById('alert_prodcat'))
		document.getElementById('alert_prodcat').style.display ='none';
	if (document.getElementById('alert_prods'))
		document.getElementById('alert_prods').style.display ='none';
	if (document.getElementById('alert_combo'))
		document.getElementById('alert_combo').style.display ='none';
	if (document.getElementById('alert_paper'))
		document.getElementById('alert_paper').style.display ='none';	
	if (document.getElementById('alert_card'))
		document.getElementById('alert_card').style.display ='none';		
	if (document.getElementById('alert_ribbon'))
		document.getElementById('alert_ribbon').style.display ='none';		
	if (document.getElementById('alert_bow'))
		document.getElementById('alert_bow').style.display ='none';		
}
function call_ajax_unassignall(mod,checkboxname)
{
	var atleastone 			= 0;
	var del_ids 			= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';
	var editid				= '<?php echo $_REQUEST['edit_id'];?>';
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmEditImage.elements.length;i++)
	{
		if (document.frmEditImage.elements[i].type =='checkbox' && document.frmEditImage.elements[i].name==checkboxname)
		{

			if (document.frmEditImage.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmEditImage.elements[i].value;
			}	
		}
	}
	switch(mod)
	{
		case 'prodcat': // Case of product categories
			atleastmsg 	= 'Please select the product categories(s) from which the current image is to be unassigned';
			confirmmsg 	= 'Are you sure you want to unassign current image from the selected Product Categorie(s)?';
				
			retdivid   	= 'prodcatassign_div';
			moredivid	= 'prodcatunassign_div';
			fpurpose	= 'unassign_prodcat';
		break;
		case 'prodshop': // Case of product categories
			atleastmsg 	= 'Please select the shop(s) from which the current image is to be unassigned';
			confirmmsg 	= 'Are you sure you want to unassign current image from the selected Shop(s)?';
				
			retdivid   	= 'prodshopassign_div';
			moredivid	= 'prodshopunassign_div';
			fpurpose	= 'unassign_prodshop';
		break;
		case 'prods': // Case of products
			atleastmsg 	= 'Please select the product(s) from which the current image is to be unassigned';
			confirmmsg 	= 'Are you sure you want to unassign current image from the selected Product(s)?';
				
			retdivid   	= 'prodsassign_div';
			moredivid	= 'prodsunassign_div';
			fpurpose	= 'unassign_prods';
		break;
		case 'combo': // Case of combo deal
			atleastmsg 	= 'Please select the combodeals(s) from which the current image is to be unassigned';
			confirmmsg 	= 'Are you sure you want to unassign current image from the selected combo deal(s)?';
				
			retdivid   	= 'comboassign_div';
			moredivid	= 'combounassign_div';
			fpurpose	= 'unassign_combo';
		break;
		case 'paper': // Case of giftwrap paper
			atleastmsg 	= 'Please select the giftwrap paper(s) from which the current image is to be unassigned';
			confirmmsg 	= 'Are you sure you want to unassign current image from the selected giftwrap paper(s)?';
				
			retdivid   	= 'paperassign_div';
			moredivid	= 'paperunassign_div';
			fpurpose	= 'unassign_paper';
		break;
		case 'card': // Case of giftwrap card
			atleastmsg 	= 'Please select the giftwrap card(s) from which the current image is to be unassigned';
			confirmmsg 	= 'Are you sure you want to unassign current image from the selected giftwrap card(s)?';
				
			retdivid   	= 'cardassign_div';
			moredivid	= 'cardunassign_div';
			fpurpose	= 'unassign_card';
		break;
		case 'ribbon': // Case of giftwrap ribbon
			atleastmsg 	= 'Please select the giftwrap ribbon(s) from which the current image is to be unassigned';
			confirmmsg 	= 'Are you sure you want to unassign current image from the selected giftwrap ribbon(s)?';
				
			retdivid   	= 'ribbonassign_div';
			moredivid	= 'ribbonunassign_div';
			fpurpose	= 'unassign_ribbon';
		break;
		case 'bow': // Case of giftwrap bow
			atleastmsg 	= 'Please select the giftwrap bow(s) from which the current image is to be unassigned';
			confirmmsg 	= 'Are you sure you want to unassign current image from the selected giftwrap bow(s)?';
				
			retdivid   	= 'bowassign_div';
			moredivid	= 'bowunassign_div';
			fpurpose	= 'unassign_bow';
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
			document.getElementById('retdiv_more').value= moredivid;/* Name of div to show the result */
			retobj 										= eval("document.getElementById('"+retdivid+"')");
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/image_gallery.php','fpurpose='+fpurpose+'&edit_id='+editid+'&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
function handle_expansionall(imgobj,mod)
{
	var src 			= imgobj.src;
	var retindx 		= src.search('plus.gif');
	switch(mod)
	{
		case 'prodcat': /* Case of product categories*/
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('prodcat_tr'))
					document.getElementById('prodcat_tr').style.display = '';
				//if(document.getElementById('varunassign_div'))
				//	document.getElementById('varunassign_div').style.display = '';	
				call_ajax_showlistall('prodcat');
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('prodcat_tr'))
					document.getElementById('prodcat_tr').style.display = 'none';
				if(document.getElementById('prodcatunassign_div'))
					document.getElementById('prodcatunassign_div').style.display = 'none';
			}	
		break;
		case 'prodshop': /* Case of product categories*/
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('prodshop_tr'))
					document.getElementById('prodshop_tr').style.display = '';
				//if(document.getElementById('varunassign_div'))
				//	document.getElementById('varunassign_div').style.display = '';	
				call_ajax_showlistall('prodshop');
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('prodshop_tr'))
					document.getElementById('prodshop_tr').style.display = 'none';
				if(document.getElementById('prodshopunassign_div'))
					document.getElementById('prodshopunassign_div').style.display = 'none';
			}	
		break;
		case 'prods': /* Case of products*/
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('prods_tr'))
					document.getElementById('prods_tr').style.display = '';
				//if(document.getElementById('varunassign_div'))
				//	document.getElementById('varunassign_div').style.display = '';	
				call_ajax_showlistall('prods');
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('prods_tr'))
					document.getElementById('prods_tr').style.display = 'none';
				if(document.getElementById('prodsunassign_div'))
					document.getElementById('prodsunassign_div').style.display = 'none';
			}	
		break;
		case 'combo': /* Case of combo deal*/
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('combo_tr'))
					document.getElementById('combo_tr').style.display = '';
				//if(document.getElementById('varunassign_div'))
				//	document.getElementById('varunassign_div').style.display = '';	
				call_ajax_showlistall('combo');
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('combo_tr'))
					document.getElementById('combo_tr').style.display = 'none';
				if(document.getElementById('combounassign_div'))
					document.getElementById('combounassign_div').style.display = 'none';
			}	
		break;
		case 'paper': /* Case of giftwrap paper*/
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('paper_tr'))
					document.getElementById('paper_tr').style.display = '';
				//if(document.getElementById('varunassign_div'))
				//	document.getElementById('varunassign_div').style.display = '';	
				call_ajax_showlistall('paper');
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('paper_tr'))
					document.getElementById('paper_tr').style.display = 'none';
				if(document.getElementById('paperunassign_div'))
					document.getElementById('paperunassign_div').style.display = 'none';
			}	
		break;
		case 'card': /* Case of giftwrap card*/
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('card_tr'))
					document.getElementById('card_tr').style.display = '';
				//if(document.getElementById('varunassign_div'))
				//	document.getElementById('varunassign_div').style.display = '';	
				call_ajax_showlistall('card');
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('card_tr'))
					document.getElementById('card_tr').style.display = 'none';
				if(document.getElementById('cardunassign_div'))
					document.getElementById('cardunassign_div').style.display = 'none';
			}	
		break;
		case 'ribbon': /* Case of giftwrap ribbon*/
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('ribbon_tr'))
					document.getElementById('ribbon_tr').style.display = '';
				//if(document.getElementById('varunassign_div'))
				//	document.getElementById('varunassign_div').style.display = '';	
				call_ajax_showlistall('ribbon');
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('ribbon_tr'))
					document.getElementById('ribbon_tr').style.display = 'none';
				if(document.getElementById('ribbonunassign_div'))
					document.getElementById('ribbonunassign_div').style.display = 'none';
			}	
		break;
		case 'bow': /* Case of giftwrap bow*/
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('bow_tr'))
					document.getElementById('bow_tr').style.display = '';
				//if(document.getElementById('varunassign_div'))
				//	document.getElementById('varunassign_div').style.display = '';	
				call_ajax_showlistall('bow');
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('bow_tr'))
					document.getElementById('bow_tr').style.display = 'none';
				if(document.getElementById('bowunassign_div'))
					document.getElementById('bowunassign_div').style.display = 'none';
			}	
		break;
	};
}
function call_ajax_showlistall(mod)
{
	var atleastone 										= 0;
	var editid											= '<?php echo $_REQUEST['edit_id'];?>';
	var cat_orders										= '';
	var fpurpose										= '';
	var retdivid										= '';
	var moredivid										= '';
	switch(mod)
	{
		case 'prodcat': // Case of product categories
			retdivid   	= 'prodcatassign_div';
			moredivid	= 'prodcatunassign_div';
			fpurpose	= 'list_prodcat';
		break;
		case 'prodshop': // Case of product categories
			retdivid   	= 'prodshopassign_div';
			moredivid	= 'prodshopunassign_div';
			fpurpose	= 'list_prodshop';
		break;
		case 'prods': // Case of products
			retdivid   	= 'prodsassign_div';
			moredivid	= 'prodsunassign_div';
			fpurpose	= 'list_prods';
		break;
		case 'combo': // Case of combo deal
			retdivid   	= 'comboassign_div';
			moredivid	= 'combounassign_div';
			fpurpose	= 'list_combo';
		break;
		case 'paper': // Case of giftwrap paper
			retdivid   	= 'paperassign_div';
			moredivid	= 'paperunassign_div';
			fpurpose	= 'list_paper';
		break;
		case 'card': // Case of giftwrap card
			retdivid   	= 'cardassign_div';
			moredivid	= 'cardunassign_div';
			fpurpose	= 'list_card';
		break;
		case 'ribbon': // Case of giftwrap ribbon
			retdivid   	= 'ribbonassign_div';
			moredivid	= 'ribbonunassign_div';
			fpurpose	= 'list_ribbon';
		break;
		case 'bow': // Case of giftwrap bow
			retdivid   	= 'bowassign_div';
			moredivid	= 'bowunassign_div';
			fpurpose	= 'list_bow';
		break;
	}
	document.getElementById('retdiv_id').value 		= retdivid;/* Name of div to show the result */	
	document.getElementById('retdiv_more').value 	= moredivid;/* Name of div to show the result */	
	retobj 											= eval("document.getElementById('"+retdivid+"')");
	retobj.innerHTML 								= '<center><img src="images/loading.gif" alt="Loading"></center>';															
	var qrystr										= '';
	/* Calling the ajax function */
	Handlewith_Ajax('services/image_gallery.php','fpurpose='+fpurpose+'&'+qrystr+'&edit_id='+editid);
}	
function call_ajax_save_general()
{
	document.frmEditImage.action='home.php?request=img_gal&fpurpose=save_imageedit_general';
	show_processing();
	document.frmEditImage.submit();
}
function call_delete_image()
{
	if(confirm('Are you sure you want to delete this image?'))
	{
		document.frmEditImage.action='home.php?request=img_gal&fpurpose=delete_image';
		show_processing();
		document.frmEditImage.submit();
	}	
}

function call_ajax_handle_change_directory()
{
	var changedir								= document.getElementById('change_subdir').value;
	if (confirm('Are you sure you want to move current image to the selected directory?'))
	{
		var changedir								= document.getElementById('change_subdir').value;
		var editid									= document.getElementById('edit_id').value;
		document.getElementById('retdiv_id').value 	= 'image_main';
		retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
		retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';	
		var fpurpose								= 'image_changedirectory';
		var qrystr									= 'retsrc=edit&ch_dir='+changedir+'&sel_prods='+editid;
		/* Calling the ajax function */
		Handlewith_Ajax('services/image_gallery.php','fpurpose='+fpurpose+'&'+qrystr);
	}
}
function call_ajax_handle_assign_category()
{
	if(document.getElementById('assign_category').value ==0)
	{
		alert('Please select the category to which the current image to be assigned.');
		return false;
	}
	if (confirm('Are you sure you want to assign the current image to the selected category?'))
	{
		document.getElementById('retdiv_id').value 	= 'image_main';
		var editid									= document.getElementById('edit_id').value;
		var assign_cat								= document.getElementById('assign_category').value;
		retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
		retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';	
		var fpurpose								= 'image_assigncategory';
		var qrystr									= 'retsrc=edit&assign_cat='+assign_cat+'&sel_prods='+editid;

		/* Calling the ajax function */
		Handlewith_Ajax('services/image_gallery.php','fpurpose='+fpurpose+'&'+qrystr);
	}
}
function call_ajax_handle_assign_shop()
{
	if(document.getElementById('assign_shop').value ==0)
	{
		alert('Please select the shop to which the current image to be assigned.');
		return false;
	}
	if (confirm('Are you sure you want to assign the current image to the selected shop?'))
	{
		document.getElementById('retdiv_id').value 	= 'image_main';
		var editid									= document.getElementById('edit_id').value;
		var assign_shop								= document.getElementById('assign_shop').value;
		retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
		retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';	
		var fpurpose								= 'image_assignshop';
		var qrystr									= 'retsrc=edit&assign_shop='+assign_shop+'&sel_prods='+editid;

		/* Calling the ajax function */
		Handlewith_Ajax('services/image_gallery.php','fpurpose='+fpurpose+'&'+qrystr);
	}
}
function call_ajax_handle_assign_combo()
{
	if(document.getElementById('assign_combo').value ==0)
	{
		alert('Please select the combo deal to which the current image to be assigned.');
		return false;
	}
	if (confirm('Are you sure you want to assign the current image to the selected combodeal?'))
	{
		var assign_combo							= document.getElementById('assign_combo').value;
		var editid									= document.getElementById('edit_id').value;
		document.getElementById('retdiv_id').value 	= 'image_main';
		retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
		retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';	
		var fpurpose								= 'image_assigncombo';
		var qrystr									= 'retsrc=edit&sel_prods='+editid+'&assign_combo='+assign_combo;
		/* Calling the ajax function */
		Handlewith_Ajax('services/image_gallery.php','fpurpose='+fpurpose+'&'+qrystr);
	}
}
function call_ajax_handle_assign_paper()
{
	if(document.getElementById('assign_paper').value ==0)
	{
		alert('Please select the giftwrap paper to which the current image to be assigned.');
		return false;
	}
	if (confirm('Are you sure you want to assign the current image to the selected Giftwrap paper?'))
	{
		var assign_paper							= document.getElementById('assign_paper').value;
		var editid									= document.getElementById('edit_id').value;
		document.getElementById('retdiv_id').value 	= 'image_main';
		retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
		retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';	
		var fpurpose								= 'image_assignpaper';
		var qrystr									= 'retsrc=edit&sel_prods='+editid+'&assign_paper='+assign_paper;
		/* Calling the ajax function */
		Handlewith_Ajax('services/image_gallery.php','fpurpose='+fpurpose+'&'+qrystr);
	}
}
function call_ajax_handle_assign_card()
{
	if(document.getElementById('assign_card').value ==0)
	{
		alert('Please select the giftwrap card to which the current image to be assigned.');
		return false;
	}
	if (confirm('Are you sure you want to assign the current image to the selected Giftwrap card?'))
	{
		var assign_card								= document.getElementById('assign_card').value;
		var editid									= document.getElementById('edit_id').value;
		document.getElementById('retdiv_id').value 	= 'image_main';
		retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
		retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';	
		var fpurpose								= 'image_assigncard';
		var qrystr									= 'retsrc=edit&sel_prods='+editid+'&assign_card='+assign_card;
		/* Calling the ajax function */
		Handlewith_Ajax('services/image_gallery.php','fpurpose='+fpurpose+'&'+qrystr);
	}
}
function call_ajax_handle_assign_ribbon()
{
	if(document.getElementById('assign_ribbon').value ==0)
	{
		alert('Please select the giftwrap ribbon to which the current image to be assigned.');
		return false;
	}
	if (confirm('Are you sure you want to assign the current image to the selected Giftwrap ribbon?'))
	{
		var assign_ribbon							= document.getElementById('assign_ribbon').value;
		var editid									= document.getElementById('edit_id').value;
		document.getElementById('retdiv_id').value 	= 'image_main';
		retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
		retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';	
		var fpurpose								= 'image_assignribbon';
		var qrystr									= 'retsrc=edit&sel_prods='+editid+'&assign_ribbon='+assign_ribbon;
		/* Calling the ajax function */
		Handlewith_Ajax('services/image_gallery.php','fpurpose='+fpurpose+'&'+qrystr);
	}
}
function call_ajax_handle_assign_bow()
{
	if(document.getElementById('assign_bow').value ==0)
	{
		alert('Please select the giftwrap bow to which the current image to be assigned.');
		return false;
	}
	if (confirm('Are you sure you want to assign the current image to the selected Giftwrap bow?'))
	{
		var assign_bow								= document.getElementById('assign_bow').value;
		var editid									= document.getElementById('edit_id').value;
		document.getElementById('retdiv_id').value 	= 'image_main';
		retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
		retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';	
		var fpurpose								= 'image_assignbow';
		var qrystr									= 'retsrc=edit&sel_prods='+editid+'&assign_bow='+assign_bow;
		/* Calling the ajax function */
		Handlewith_Ajax('services/image_gallery.php','fpurpose='+fpurpose+'&'+qrystr);
	}
}
function call_ajax_sel_product()
{
	if(document.getElementById('sel_category').value ==0)
	{
		alert('Please select the category.');
		return false;
	}
	if (confirm('Are you sure you want to assign the selected image to product(s) under selected category?'))
	{
		var src_caption								= document.getElementById('src_caption').value;
		var src_option								= document.getElementById('src_option').value;
		var recs									= document.getElementById('recs').value;
		var pg										= document.getElementById('pgs').value;
		var sel_prods								= document.getElementById('sel_prods').value;
		var curdirid								= document.getElementById('curdir_id').value;
		var sel_category							= document.getElementById('sel_category').value;
		var sel_prods								= document.getElementById('edit_id').value;
		var qrystr									= 'retsrc=edit&sel_prods='+sel_prods+'&curdir_id='+curdirid+'&src_caption='+src_caption+'&src_option='+src_option+'&recs='+recs+'&pgs='+pg+'&sel_prods='+sel_prods+'&sel_category='+sel_category;
		/* Calling the function to show the image upload section*/
		show_processing();
		window.location = 'home.php?request=img_gal&fpurpose=sel_prod_for_cat&'+qrystr;
	}	

}
function handle_tabs(id)
{
	var mod;
	switch(id)
	{
		case 'general_info':
			document.getElementById('assigned_info').className 	= 'toptab';
			document.getElementById('operation_info').className = 'toptab';
			document.getElementById('curtab').value 			= 'general_info';
			mod = 'show_imageedit_general';
		break;
		case 'assigned_info':
			document.getElementById('general_info').className 	= 'toptab';
			document.getElementById('operation_info').className = 'toptab';
			document.getElementById('curtab').value 			= 'assigned_info';
			mod = 'show_imageedit_assigned';
		break;
		case 'operation_info':
			document.getElementById('general_info').className 	= 'toptab';
			document.getElementById('assigned_info').className 	= 'toptab';
			document.getElementById('curtab').value 			= 'operation_info';
			mod = 'show_imageedit_operation';
		break;
	}
	obj = eval("document.getElementById('"+id+"')");
	if (obj.className=='toptab')
		obj.className = 'toptab_sel';
	call_ajax_show_tab(mod)
		
}
function call_ajax_show_tab(mod)
{
	var editid		= document.getElementById('edit_id').value;
	var qrystr		= 'edit_id='+editid;
	var fpurpose	= mod;
	document.getElementById('retdiv_id').value = 'image_main';
	document.getElementById('image_main').innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
	/* Calling the ajax function */
	Handlewith_Ajax('services/image_gallery.php','fpurpose='+fpurpose+'&'+qrystr);
}
</script>
<link rel="stylesheet" type="text/css" href="js/simpletree.css" />
<form method="post" enctype="multipart/form-data" name="frmEditImage">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=img_gal&txt_searchcaption=<?php echo $_REQUEST['src_caption']?>&search_option=<?php echo $_REQUEST['src_option']?>&records_per_page=<?php echo $_REQUEST['recs']?>&pg=<?php echo $_REQUEST['pgs']?>&curdir_id=<?php echo $_REQUEST['curdir_id']?>&sel_prods=<?php echo $_REQUEST['sel_prods']?>">Image Gallery</a> <?php echo $back_link?><span>Edit Image</span></div> </td>
        </tr>
		<tr>
		  <td colspan="2" align="left" valign="middle" class="helpmsgtd_main">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
		<?php
			if ($alert)
			{
		?>
				<!--<tr id="main_alert">
				  <td colspan="2" align="center" valign="middle" class="errormsg" ><?php //echo $alert?></td>
				</tr>-->
		<?php
			}
		?>
        <tr>
          <td  align="left" valign="top" colspan="2">
		 
		  <table width="100%" border="0" cellspacing="1" cellpadding="1">
		  <tr>
			<td  align="left" onClick="handle_tabs('general_info')" class="<?php if($curtab=='show_imageedit_general') echo "toptab_sel"; else echo "toptab"?>" id="general_info"><span>General Info</span></td>
			<td  align="left" onClick="handle_tabs('assigned_info')" class="<?php if($curtab=='show_imageedit_assigned') echo "toptab_sel"; else echo "toptab"?>" id="assigned_info"><span>Assigned To</span> </td>
			<td  align="left" onClick="handle_tabs('operation_info')" class="<?php if($curtab=='show_imageedit_operation') echo "toptab_sel"; else echo "toptab"?>" id="operation_info"><span>Operations</span> </td>
			<td width="99%" align="right"><input type="button" name="Submit_delete" value="Delete Image" class="red" onclick="call_delete_image()"/> &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_IMG_GAL_IMG_DELETE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		  </tr>
		</table>	  </td>
        </tr>
        <tr>
          <td  align="left" valign="top" colspan="2">
		  <div id="image_main">
		  	<?php
				if($curtab=='show_imageedit_assigned')
				{
					imageedit_assigned($_REQUEST['edit_id'],$alert);
				}
				elseif($curtab=='show_imageedit_operation')
				{
					imageedit_operation($_REQUEST['edit_id'],$list_alert);
				}
				else
					imageedit_general($_REQUEST['edit_id'],$alert);
			?>
		  </div>
		  </td>
        </tr>
      </table>
	 	<input type="hidden" name="retdiv_id" id="retdiv_id" value="" />
	  	<input type="hidden" name="curtab" id="curtab" value="<?=$curtab?>" />
	  	<input type="hidden" name="curdir_id" id="curdir_id" value="<?=$_REQUEST['curdir_id']?>" />
		<input type="hidden" name="edit_id" id="edit_id" value="<?=$_REQUEST['edit_id']?>" />
		<input type="hidden" name="src_caption" id="src_caption" value="<?=$_REQUEST['src_caption']?>" />
		<input type="hidden" name="src_option" id="src_option" value="<?=$_REQUEST['src_option']?>" />
		<input type="hidden" name="recs" id="recs" value="<?=$_REQUEST['recs']?>" />
		<input type="hidden" name="pgs" id="pgs" value="<?=$_REQUEST['pgs']?>" />
		<input type="hidden" name="org_id" id="org_id" value="<?=$_REQUEST['org_id']?>" />
		<input type="hidden" name="back_frm" id="back_frm" value="<?=$_REQUEST['back_frm']?>" />
		<input type="hidden" name="sel_prods" id="sel_prods" value="<?=$_REQUEST['sel_prods']?>" />
		<input type="hidden" name="retdiv_id" id="retdiv_id" value="" />
		<input type="hidden" name="retdiv_more" id="retdiv_more" value="" />
</form>