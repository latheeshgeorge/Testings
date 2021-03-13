<?php
	/*#################################################################
	# Script Name 	: edit_adverts.php
	# Description 	: Page for editing Adverts
	# Coded by 		: ANU
	# Created on	: 20-July-2007
	# Modified by	: Sny
	# Modified On	: 20-Dec-2007
	#################################################################*/
#Define constants for this page
$page_type = 'Banners';
$help_msg = get_help_messages('EDIT_ADVERT_MESS1');
$curtab		= ($_REQUEST['curtab'])?$_REQUEST['curtab']:'main_tab_td';
$advert_id = $edit_id;
$sql="SELECT advert_title,advert_hide,advert_showinall,advert_showinhome,advert_order,advert_source,advert_link,advert_type,
			 advert_activateperiodchange,advert_displaystartdate,advert_displayenddate,advert_target 
			 	FROM adverts 
					 WHERE sites_site_id=$ecom_siteid AND advert_id=".$advert_id. " AND sites_site_id='".$ecom_siteid."'";
$res=$db->query($sql);
if($db->num_rows($res)==0)  { echo " <font color='red'> You Are Not Authorised  </a>"; exit; } 
$row=$db->fetch_array($res);
$advert_showinall = $row['advert_showinall'];
(trim($advert_title))?$advert_title=$_REQUEST['advert_title']:$advert_title=$row['advert_title'];

$editor_elements = "txt_text";
include_once(ORG_DOCROOT."/console/js/tinymce.php");
?>	
<script language="javascript" type="text/javascript">
function activeperiod(check,bid){
 if(document.frmEditAdverts.advert_activateperiodchange.checked == true){
		document.getElementById(bid).style.display = '';
		}
		else{
		document.getElementById(bid).style.display = 'none';
		document.frmEditAdverts.advert_activateperiodchange.checked = false;
		}
		
		
}
function handletype_change(vals)
{	
	if (vals=='')
		vals = 'IMG';
	switch(vals)
	{
		case 'IMG':
			document.getElementById('tr_img').style.display = '';
			document.getElementById('tr_loc').style.display = 'none';
			document.getElementById('tr_text').style.display = 'none';
                        if(document.getElementById('tr_rotate'))
                            document.getElementById('tr_rotate').style.display = 'none';
			document.getElementById('tr_link').style.display = '';
			document.getElementById('tr_target').style.display = '';
			if (document.getElementById('resizeimg_div'))
				document.getElementById('resizeimg_div').style.display = '';
		break;
		case 'PATH':
			document.getElementById('tr_img').style.display = 'none';
			document.getElementById('tr_loc').style.display = '';
			document.getElementById('tr_text').style.display = 'none';
                         if(document.getElementById('tr_rotate'))
                            document.getElementById('tr_rotate').style.display = 'none';
			document.getElementById('tr_link').style.display = '';
			document.getElementById('tr_target').style.display = '';
			if (document.getElementById('resizeimg_div'))
				document.getElementById('resizeimg_div').style.display = 'none';
		break;
		case 'TXT':
			document.getElementById('tr_img').style.display = 'none';
			document.getElementById('tr_loc').style.display = 'none';
			document.getElementById('tr_text').style.display = '';
			document.getElementById('tr_link').style.display = 'none';
			document.getElementById('tr_target').style.display = 'none';
                        if(document.getElementById('tr_rotate'))
                            document.getElementById('tr_rotate').style.display = 'none';
			if (document.getElementById('resizeimg_div'))
				document.getElementById('resizeimg_div').style.display = 'none';
		break;
		case 'SWF':
			document.getElementById('tr_img').style.display = '';
			document.getElementById('tr_loc').style.display = 'none';
			document.getElementById('tr_text').style.display = 'none';
			document.getElementById('tr_link').style.display = 'none';
			document.getElementById('tr_target').style.display = 'none';
                        if(document.getElementById('tr_rotate'))
                            document.getElementById('tr_rotate').style.display = 'none';
			if (document.getElementById('resizeimg_div'))
				document.getElementById('resizeimg_div').style.display = 'none';
		break;
                case 'ROTATE':
                     document.getElementById('tr_img').style.display = 'none';
                        document.getElementById('tr_loc').style.display = 'none';
                        document.getElementById('tr_text').style.display = 'none';
                        document.getElementById('tr_link').style.display = 'none';
                        document.getElementById('tr_target').style.display = 'none';
                        if (document.getElementById('resizeimg_div'))
                                document.getElementById('resizeimg_div').style.display = 'none';
                        if(document.getElementById('tr_rotate'))
                            document.getElementById('tr_rotate').style.display = '';
                break;
	};
}
function delete_rotate_confirm(rotid)
{
    if(confirm('Are you sure you want to delete this rotate image?'))
    {
         document.rotate_img_frm.d_id.value = rotid;
         document.rotate_img_frm.submit();
    }
    
}

function ajax_return_contents() 
{
	var ret_val = '';
	var disp 	= 'no';
	var tab = '<?PHP echo $curtab; ?>';
	var typ = '<?PHP echo $row['advert_type']; ?>';
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
			
			if(norecdiv=='main_tab_td') {
				var adverttype = document.getElementById('advert_type').value;
				handletype_change( typ);
			}
			switch(targetdiv)
			{
				
				case 'category_div':
					if(document.getElementById('category_norec'))
					{
						if(document.getElementById('category_norec').value==1)
						{
							disp = 'none';
						}	
						else
							disp = '';
					}
					else
						disp = '';	
				break;
				case 'products_div':
					if(document.getElementById('products_norec'))
					{
						if(document.getElementById('products_norec').value==1)
						{
							disp = 'none';
						}	
						else
							disp = '';
					}
					else
						disp = '';	
				break;
				case 'assign_pages_div':
					if(document.getElementById('assign_pages_norec'))
					{
						if(document.getElementById('assign_pages_norec').value==1)
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

function call_ajax_deleteall(mod,checkboxname)
{
	var atleastone 			= 0;
	var advert_id			= '<?php echo $advert_id?>';
	var del_ids 			= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';
	var cname								= '<?php echo $_REQUEST['pass_search_name']?>';
	var sortby								= '<?php echo $_REQUEST['pass_sort_by']?>';
	var sortorder							= '<?php echo $_REQUEST['pass_sort_order']?>';
	var recs								= '<?php echo $_REQUEST['pass_records_per_page']?>';
	var start								= '<?php echo $_REQUEST['pass_start']?>';
	var pg									= '<?php echo $_REQUEST['pass_pg']?>';
	var curtab								= '<?php echo $curtab?>';
	var showinall							= '<?php echo $advert_showinall?>';
	var advert_title						= '<?php echo $advert_title; ?>';
	var qrystr								= 'pass_search_name='+cname+'&pass_sort_by='+sortby+'&pass_sort_order='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&curtab='+curtab+'&advert_showinall='+showinall+'&advert_title='+advert_title;

	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmEditAdverts.elements.length;i++)
	{
		if (document.frmEditAdverts.elements[i].type =='checkbox' && document.frmEditAdverts.elements[i].name==checkboxname)
		{

			if (document.frmEditAdverts.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmEditAdverts.elements[i].value;
			}	
		}
	}
	switch(mod)
	{
		
		case 'category': // Case of product messages
			atleastmsg 	= 'Please select the Product Categories to be deleted';
			confirmmsg 	= 'Are you sure you want to delete the selected Product Categories?'
		//	retdivid   	= 'category_div';
		//	moredivid	= 'categoryunassign_div';
			fpurpose	= 'delete_category_ajax';
		break;
		case 'product': // Case of product tabs
			atleastmsg 	= 'Please select the product(s) to be deleted';
			confirmmsg 	= 'Are you sure you want to delete the selected Product(s)?'
		//	retdivid   	= 'products_div';
		//	moredivid	= 'productsunassign_div';
			fpurpose	= 'delete_product_ajax';
		break;
		case 'assign_pages': // Case of linked products
			atleastmsg 	= 'Please select the Static Page(s) to be Unassigned';
			confirmmsg 	= 'Are you sure you want to Unassign the selected Static Page(s)?'
		//	retdivid   	= 'assign_pages_div';
		//	moredivid	= 'assign_pagesunassign_div';
			fpurpose	= 'delete_assign_pages';
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
		//	document.getElementById('retdiv_more').value 	= moredivid;/* Name of div to show the result */	
			retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
			
			Handlewith_Ajax('services/adverts.php','fpurpose='+fpurpose+'&cur_advert_id='+advert_id+'&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
function handle_showclick(mod)
{ 
	if (mod=='advert_showinall')
	{
		if (document.frmEditAdverts.advert_showinall.checked)
			document.frmEditAdverts.advert_showinhome.checked = false;
	}
	else
	{
		if (document.frmEditAdverts.advert_showinhome.checked)
			document.frmEditAdverts.advert_showinall.checked = false;
	}		
}

function valform(frm)
{
	fieldRequired = Array('advert_title','display_id[]');
	fieldDescription = Array('Banner Title','Display Location');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	var reqcnt = fieldRequired.length;
	if(document.frmEditAdverts.cbo_type.value=='IMG')
	{
			if(document.frmEditAdverts.img_source.value==0 && document.frmEditAdverts.path_source.value==1){
			fieldRequired[reqcnt] 	 = 'file_advert';
			fieldDescription[reqcnt] = 'Image';
			reqcnt++;
			}
	}	
	if(document.frmEditAdverts.cbo_type.value=='PATH')
	{
			fieldRequired[reqcnt] 	 = 'txt_imgloc';
			fieldDescription[reqcnt] = 'Path for Image';
			reqcnt++;
			if(document.frmEditAdverts.img_source.value==1 && document.frmEditAdverts.path_source.value==0){
			fieldRequired[reqcnt] 	 = 'txt_imgloc';
			fieldDescription[reqcnt] = 'Path for Image';
			reqcnt++;
			}
	}
	if(document.frmAddadverts.cbo_type.value=='ROTATE')
	{
			fieldRequired[reqcnt] 	 = 'rotate_height';
			fieldDescription[reqcnt] = 'Rotator Height';
			reqcnt++;
			fieldRequired[reqcnt] 	 = 'rotate_speed';
			fieldDescription[reqcnt] = 'Rotate Speed';
			reqcnt++;
			fieldNumeric[0] 			= 'rotate_height';
			fieldNumeric[1] 			= 'rotate_speed';
	}	
	
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		if(frm.advert_activateperiodchange.checked  ==true){
			val_dates = compareDates(frm.advert_displaystartdate,"Start Date\n Correct Format:dd-mm-yyyy ",frm.advert_displayenddate,"End Date\n Correct Format:dd-mm-yyyy");
			if(val_dates ){
				  show_processing();
				  return true;
			 }else{
			 return false
			 }
		}else{
		show_processing();
		return true;
		}
	} else {
		return false;
	}
}

</script>

<script language="javascript">

	function normal_categ_Assign(cname,sortby,sortorder,recs,start,pg,groupid,advert_title)
	{ 
			window.location 			= 'home.php?request=adverts&fpurpose=list_assign_categories&pass_search_name='+cname+'&pass_sort_by='+sortby+'&pass_sort_order='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_groupid='+groupid+'&advert_title='+advert_title;
	}
	function normal_prod_Assign(cname,sortby,sortorder,recs,start,pg,groupid,advert_title)
	{
			window.location 			= 'home.php?request=adverts&fpurpose=list_assign_products&pass_search_name='+cname+'&pass_sort_by='+sortby+'&pass_sort_order='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_group_id='+groupid+'&advert_title='+advert_title;
	}
	function normal_static_Assign(cname,sortby,sortorder,recs,start,pg,groupid,advert_title)
	{
			window.location 			= 'home.php?request=adverts&fpurpose=list_assign_pages&pass_search_name='+cname+'&pass_sort_by='+sortby+'&pass_sort_order='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_group_id='+groupid+'&advert_title='+advert_title;
	}
	
	function handle_tabs(id,mod)
	{
	tab_arr 								= new Array('main_tab_td','category_tab_td','products_tab_td','static_tab_td');  
	var atleastone 							= 0;
	var group_id							= '<?php echo $edit_id?>';
	var cat_orders							= '';
	var fpurpose							= '';
	var retdivid							= '';
	var moredivid							= '';
	var cname								= '<?php echo $_REQUEST['pass_search_name']?>';
	var sortby								= '<?php echo $_REQUEST['pass_sort_by']?>';
	var sortorder							= '<?php echo $_REQUEST['pass_sort_order']?>';
	var recs								= '<?php echo $_REQUEST['pass_records_per_page']?>';
	var start								= '<?php echo $_REQUEST['pass_start']?>';
	var pg									= '<?php echo $_REQUEST['pass_pg']?>';
	var curtab								= '<?php echo $curtab?>';
	var showinall							= '<?php echo $advert_showinall?>';
	var advert_title						= '<?php echo $advert_title; ?>';
	var qrystr								= 'pass_search_name='+cname+'&pass_sort_by='+sortby+'&pass_sort_order='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&group_id='+group_id+'&curtab='+curtab+'&advert_showinall='+showinall+'&advert_title='+advert_title;
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
		case 'catgroupmain_info':
			/*fpurpose ='list_adverts_maininfo';
			*/
			document.frmEditAdverts.fpurpose.value = 'list_adverts_maininfo';
			document.frmEditAdverts.submit();
			return;
		break;
		case 'displaycategory_group': // Case of Categories in the group
			
			//retdivid   	= 'master_div';
			fpurpose	= 'list_categoriesInAdverts_ajax';
			//moredivid	= 'category_groupunassign_div';
			
		break;  
		case 'displayproduct_group': // Case of Display Products in the group 
			//retdivid   	= 'master_div';
			fpurpose	= 'list_products_ajax';
			//moredivid	= 'displayproduct_groupunassign_div';
		break;
		case 'displaystatic_group': // Case of Display Categories in the group
			//retdivid   	= 'master_div';
			fpurpose	= 'list_assign_pages_ajax';
			//moredivid	= 'displaycategory_groupunassign_div';
		break;
		
	}
	retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
	//retobj										= document.getElementById('retdiv_id');
	retobj.innerHTML 						= '<center><img src="images/loading.gif" alt="Loading"></center>';
	document.getElementById('retdiv_more').value = id;															
		/*	if(id=='main_tab_td') {
				var adverttype = document.getElementById('advert_type').value;
				alert(adverttype);
				handletype_change('<?PHP echo $row['advert_type']; ?>');
			}*/
	/* Calling the ajax function */
	//alert('fpurpose='+fpurpose+'&cur_groupid='+group_id);
	Handlewith_Ajax('services/adverts.php','fpurpose='+fpurpose+'&group_id='+group_id+'&'+qrystr);	
}
</script>
<form name='frmEditAdverts' action='home.php?request=adverts' enctype="multipart/form-data" method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=adverts&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&search_name=<?=$_REQUEST['pass_search_name']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>">List Banners</a><span> Edit Banners</span></div></td>
        </tr>
      <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="4">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
		<tr>
          <td colspan="4" align="center" valign="middle" >
		  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabmenu_x" >
				<tr>  
						<td  align="left" onClick="handle_tabs('main_tab_td','catgroupmain_info')" class="<?php if($curtab=='main_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="main_tab_td"><span>Main Info</span></td>
						<td  align="left" onClick="handle_tabs('category_tab_td','displaycategory_group')" class="<?php if($curtab=='category_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="category_tab_td"> <span>Show Banner in these Categories</span></td>
						<td align="left" onClick="handle_tabs('products_tab_td','displayproduct_group')" class="<?php if($curtab=='products_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="products_tab_td"><span> Show Banner in these Products</span></td>
						<td  align="left" onClick="handle_tabs('static_tab_td','displaystatic_group')" class="<?php if($curtab=='static_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="static_tab_td"><span>Show Banner in these Static pages</span></td>
						<td width="90%" align="left">&nbsp;</td> 
				</tr> 
		</table>
			</td>
        </tr>
		 <tr>
          <td colspan="4">
		  <div id='master_div'>
			<?php 
			if($curtab=='main_tab_td')
			{
			 show_adverts_maininfo($edit_id,$alert);
			}
			elseif ($curtab=='category_tab_td')
			{
				show_category_list($edit_id,$alert);
			}
			elseif ($curtab=='products_tab_td')
			{
				show_product_list($edit_id,$alert);
			}
			elseif ($curtab=='static_tab_td')
			{
				show_assign_pages_list($edit_id,$alert);
			}
			
			?>		
		  </div>
		 

		  </td>
		  </tr>
		 <tr>
          <td colspan="4" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
        </tr>
		<tr>
          <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td align="right" valign="middle" class="tdcolorgray">
		   
  <input type="hidden" name="advert_id" id="advert_id" value="<?=$advert_id?>" />
  <input type="hidden" name="pass_search_name" id="pass_search_name" value="<?=$_REQUEST['pass_search_name']?>" />
  <input type="hidden" name="pass_start" id="pass_start" value="<?=$_REQUEST['pass_start']?>" />
  <input type="hidden" name="pass_sort_by" id="pass_sort_by" value="<?=$_REQUEST['pass_sort_by']?>" />
  <input type="hidden" name="pass_sort_order" id="pass_sort_order" value="<?=$_REQUEST['pass_sort_order']?>" />
  <input type="hidden" name="pass_records_per_page" id="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
  <input type="hidden" name="pass_pg" id="pass_pg" value="<?=$_REQUEST['pass_pg']?>" />
		  <input type="hidden" name="fpurpose" id="fpurpose" value="update_advert" />
		 
		  <input type="hidden" name="retdiv_more" id="retdiv_more" value="" />
		  <input type="hidden" name="retdiv_id" id="retdiv_id" value="master_div" />
  <input type="hidden" name="advert_type" id="advert_type" value="<?=$row['advert_type']?>" />		  
		  
		  </td>
          <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
          <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
		</tr>
		 <tr>
          <td colspan="4" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
        </tr>
			
	
      </table>
</form>
<form method='post' name='rotate_img_frm' id="rotate_img_frm">
    <input type="hidden" name="advert_id" id="advert_id" value="<?=$advert_id?>" />
    <input type="hidden" name="d_id" id="d_id" value="" />
    <input type="hidden" name="pass_search_name" id="pass_search_name" value="<?=$_REQUEST['pass_search_name']?>" />
    <input type="hidden" name="pass_start" id="pass_start" value="<?=$_REQUEST['pass_start']?>" />
    <input type="hidden" name="pass_sort_by" id="pass_sort_by" value="<?=$_REQUEST['pass_sort_by']?>" />
    <input type="hidden" name="pass_sort_order" id="pass_sort_order" value="<?=$_REQUEST['pass_sort_order']?>" />
    <input type="hidden" name="pass_records_per_page" id="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
    <input type="hidden" name="pass_pg" id="pass_pg" value="<?=$_REQUEST['pass_pg']?>" />
    <input type="hidden" name="fpurpose" id="fpurpose" value="delete_rotate" />
    <input type="hidden" name="retdiv_more" id="retdiv_more" value="" />
    <input type="hidden" name="retdiv_id" id="retdiv_id" value="master_div" />
    <input type="hidden" name="advert_type" id="advert_type" value="<?=$row['advert_type']?>" />
</form> 

 <?php 
			if ($curtab=='main_tab_td')
			{ ?>
			<script language="javascript">
			 // handle_tabs('main_tab_td','catgroupmain_info');				
			</script>
		<?	} ?>
<script type="text/javascript">
	handletype_change('<?PHP echo $row['advert_type']; ?>');
</script>