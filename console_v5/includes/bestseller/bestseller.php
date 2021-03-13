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
	
	$help_msg 			= get_help_messages('BESTSELLER_MESS1');
	$curtab		= ($_REQUEST['curtab'])?$_REQUEST['curtab']:'main_tab_td';
	 
	//# Retrieving the values of super admin from the table
	
	//$fetch_arr_admin 	= $db->fetch_array($res_admin);
    $sql 				= "SELECT * FROM general_settings_sites_common WHERE sites_site_id=".$ecom_siteid;
	$res_admin 			= $db->query($sql);
	$fetch_arr_admin 	= $db->fetch_array($res_admin);
	$pick_type = $fetch_arr_admin['best_seller_picktype'];
	$sql 				= "SELECT * FROM general_settings_sites_common_onoff WHERE sites_site_id=".$ecom_siteid;
	$res_admin 			= $db->query($sql);
	$fetch_arr_admin_1 	= $db->fetch_array($res_admin);
	
	// This is done to move the fields and its value from general_settings_sites_common_onoff table to the same array which have values from general_settings_sites_common table	
	foreach ($fetch_arr_admin_1 as $k=>$v)
	{
		$fetch_arr_admin[$k]=$v;	
	}
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
	 };

}
function call_ajax_showlistall(mod)
{  
	var atleastone 			= 0;
	var fpurpose			= '';
	var retdivid			= '';
	var moredivid			= '';
	switch(mod)
	{
		case 'product_bestseller': // Case of Products in the bestseller
			retdivid   	= 'productbestseller_div';
			fpurpose	= 'list_productbestseller';
			moredivid	= 'productbestsellerunassign_div';
			
		break;
	};
    document.getElementById('retdiv_id').value 		= retdivid;/* Name of div to show the result */	
	document.getElementById('retdiv_more').value 	= moredivid;/* Name of div to show the result */	
	retobj 											= eval("document.getElementById('"+retdivid+"')");
	retobj.innerHTML 								= '<center><img src="images/loading.gif" alt="Loading"></center>';															
	var qrystr										= '';
	/* Calling the ajax function */
	
	Handlewith_Ajax('services/bestseller.php','fpurpose='+fpurpose);		
}		
function normal_assign_prodBestsellerAssign()
{
		var pick_type							= '<?php echo $pick_type?>';
		var qrystr				= '';
	    var qrystr								= 'pick_type='+pick_type;
		window.location 			= 'home.php?request=bestseller&fpurpose=prodBestsellerAssign&'+qrystr;
}
function normal_assign_prodUpsellAssign()
{
		var pick_type							= '<?php echo $pick_type?>';
		var qrystr				= '';
	    var qrystr								= 'pick_type='+pick_type;
		window.location 			= 'home.php?request=bestseller&fpurpose=prodUpsellAssign&'+qrystr;
}
function call_ajax_changeorderall(mod,checkboxname)
{
	var atleastone 			= 0;
	var pick_type			= '<?php echo $pick_type?>';
	var ch_ids 				= '';
	var ch_order			= '';
    var qrystr				= 'pick_type='+pick_type;
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
			//retdivid   	= 'productbestseller_div';
			//moredivid	= 'productbestsellerunassign_div';
			fpurpose	= 'save_order';
			orderbox	= 'product_bestseller_order_';
		break;
		case 'product_upsell': // Case of Static pages in the group
			atleastmsg 	= 'Please select the Products to Change the Order';
			confirmmsg 	= 'Are you sure you want to Change the Order of selected Product(s)?';
			fpurpose	= 'save_order_upsell';
			orderbox	= 'product_bestseller_order_';
		break;
		
	}
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmBestseller.elements.length;i++)
	{
	if (document.frmBestseller.elements[i].type =='checkbox' && document.frmBestseller.elements[i].name== checkboxname)
		{
		

			if (document.frmBestseller.elements[i].checked==true)
			{
				atleastone = 1;
				if (ch_ids!='')
					ch_ids += '~';
				 ch_ids += document.frmBestseller.elements[i].value;
				
				 obj = eval("document.getElementById('"+orderbox+document.frmBestseller.elements[i].value+"')");
				if (ch_order != '')
					ch_order += '~';
				 ch_order += obj.value; 
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
			retobj 				= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
			retobj.innerHTML 	= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/bestseller.php','fpurpose='+fpurpose+'&ch_order='+ch_order+'&ch_ids='+ch_ids+'&'+qrystr);
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
	var pick_type			= '<?php echo $pick_type?>';
	var fpurpose			= '';
	var qrystr				= 'pick_type='+pick_type;

	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmBestseller.elements.length;i++)
	{
		if (document.frmBestseller.elements[i].type =='checkbox' && document.frmBestseller.elements[i].name==checkboxname)
		{

			if (document.frmBestseller.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmBestseller.elements[i].value;
			}	
		}
	}
	switch(mod)
	{
		case 'product_bestseller': // Case of Products in the bestseller
			atleastmsg 	= 'Please select the Product(s) to be deleted from the bestseller list';
			confirmmsg 	= 'Are you sure you want to delete the selected Product(s) from the bestseller list?';
			//retdivid   	= 'productbestseller_div';
			//moredivid	= 'productbestsellerunassign_div';
			fpurpose	= 'prodbestsellerUnAssign';
		break;
		case 'product_upsell': // Case of Products in the bestseller
			atleastmsg 	= 'Please select the Product(s) to be deleted from the Upsell list';
			confirmmsg 	= 'Are you sure you want to delete the selected Product(s) from the Upsell list?';
			fpurpose	= 'produpsellUnAssign';
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
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/bestseller.php','fpurpose='+fpurpose+'&del_ids='+del_ids+'&'+qrystr);
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

function handle_tabs(id,mod)
	{
	tab_arr 								= new Array('main_tab_td','prods_tab_td','upselprods_tab_td');    
	var atleastone 							= 0;
	var cat_orders							= '';
	var fpurpose							= '';
	var retdivid							= '';
	var moredivid							= '';
	var pick_type							= '<?php echo $pick_type?>';
	var search_name							= '<?php echo $_REQUEST['search_name']?>';
	var sortby								= '<?php echo $sort_by?>';
	var sortorder							= '<?php echo $sort_order?>';
	var recs								= '<?php echo $records_per_page?>';
	var start								= '<?php echo $start?>';
	var pg									= '<?php echo $pg?>';
	var curtab								= '<?php echo $curtab?>';
	
	var qrystr								= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&curtab='+curtab+'&pick_type='+pick_type;
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
		case 'best_main_info':
			fpurpose ='list_best_maininfo';
		break;
		case 'prod_best_info': // Case of Categories in the group
			//retdivid   	= 'master_div';
			fpurpose	= 'list_productbestseller';
			//moredivid	= 'category_groupunassign_div';
			
		break;  
		case 'prod_upsell_info': // Case of Categories in the group
			fpurpose	= 'list_productupsell';
		break;  
	}
	retobj 									= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
	retobj.innerHTML 						= '<center><img src="images/loading.gif" alt="Loading"></center>';
	Handlewith_Ajax('services/bestseller.php','fpurpose='+fpurpose+'&'+qrystr);	
}
</script>
<form action="home.php?request=bestseller" name="frmBestseller" method="post">
<input type="hidden" name="retdiv_id" id="retdiv_id" value="master_div" />
<input type="hidden" name="retdiv_more" id="retdiv_more" value="" />
<input type="hidden" name="src_page" id="src_page" value="mainshop" />
<input type="hidden" name="fpurpose" id="fpurpose" value="settings_default_update" />
		<input type="hidden" name="src_id" id="src_id" value="" />

<table border="0" cellpadding="0" cellspacing="0" width="100%" >
<tr>
<td valign="top" class="contentarea">
<?
		if(is_module_valid('mod_bestsellers','any')){
		?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
          <td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span> Best Sellers</span></div></td>
        </tr>
        <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="2">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
		<tr>
          <td colspan="2" align="center" valign="middle" ><table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabmenu_x">
				<tr>  
						<td  align="left" onClick="handle_tabs('main_tab_td','best_main_info')" class="<?php if($curtab=='main_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="main_tab_td"><span>Main Info</span></td>
						<td  align="left" onClick="handle_tabs('prods_tab_td','prod_best_info')" class="<?php if($curtab=='prods_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="prods_tab_td"> <span>Products to be displayed as  Bestsellers </span></td>
						<td  align="left" onClick="handle_tabs('upselprods_tab_td','prod_upsell_info')" class="<?php if($curtab=='upselprods_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="upselprods_tab_td"> <span>Products to be displayed as Upsell Products</span></td>
						<td width="80%" align="left">&nbsp;</td>  
				</tr></table></td>
        </tr>
		<tr >
          <td  align="left" valign="top" id="bestseller_maintr">
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
		
		<tr >
		  <td  colspan="5" align="left" valign="bottom"><div id='master_div'>
			<?php
		 
			if ($curtab=='main_tab_td')
			{
				include ('ajax/bestseller_ajax_functions.php');

				bestseller_maininfo($alert);
			}
			elseif ($curtab=='prods_tab_td')
			{
				include ('ajax/bestseller_ajax_functions.php');
				show_product_bestseller_list($alert);
			}
			elseif ($curtab=='upselprods_tab_td')
			{
				include ('ajax/bestseller_ajax_functions.php');
				show_product_upsell_list($alert);
			}
		
			?>		
		  </div></td>
		  </tr>
		</table>		</td>
		</tr>
		<? }?>	
</table></td></tr></table>
</form>
