<?php
	/*
	#################################################################
	# Script Name 	: edit_settings_list_order.php
	# Description 	: Page for general settings for listings
	# Coded by 		: ANU
	# Created on	: 14-Jun-2007
	# Modified by	: Sny
	# Modified On	: 21-Dec-2007
	#################################################################
	*/	
	
	$help_msg 	= get_help_messages('LIST_ORDER_MESS1');
	if(!$curtab)
	$curtab		= ($_REQUEST['curtab'])?$_REQUEST['curtab']:'main_tab_td';
?>
<script type="text/javascript">
function handle_tabs(id,mod)
{ 
	tab_arr 									= new Array('main_tab_td','regcustomers_tab_td','others_tab_td');
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
		case 'prod_listing':
			fpurpose ='list_prodlisting_maininfo';
		break;
		case 'reg_customers_details': // Case of Categories in the group
			fpurpose	= 'list_regcustomers_details';
		break;
		case 'others': // Case of Display Products in the group
			fpurpose	= 'list_others_cat_shop';
		break;
	}	
	retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
	//retobj										= document.getElementById('retdiv_id');
	retobj.innerHTML 						= '<center><img src="images/loading.gif" alt="Loading"></center>';															
	
	/* Calling the ajax function */
	//alert('fpurpose='+fpurpose+'&cur_groupid='+group_id);
	Handlewith_Ajax('services/general_settings.php','fpurpose='+fpurpose);	
}
function save_settings(mod)
{
	if(mod=='regcust')
	{
		document.frmGeneralSettings.fpurpose.value = 'settings_regcust_update';
		document.frmGeneralSettings.submit();
	}
	else if(mod=='others_catshop')
	{
		document.frmGeneralSettings.fpurpose.value = 'settings_others_catshop_update';
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
</script>
<form name="frmGeneralSettings" method="post" action="home.php">
<table border="0" cellpadding="0" cellspacing="0" width="100%" >
<tr>
<td valign="top" class="contentarea">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=general_settings">General Settings</a><span>List Order Settings </span></div></td>
        </tr>
       <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="4">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
			<tr>
			<td colspan="4" align="left">
				<table width="100%" border="0" cellspacing="1" cellpadding="1">
				<tr>
						<td  align="left" onClick="handle_tabs('main_tab_td','prod_listing')" class="<?php if($curtab=='main_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="main_tab_td"><span>Product Listing</span></td>
						<td  align="left" onClick="handle_tabs('regcustomers_tab_td','reg_customers_details')" class="<?php if($curtab=='regcustomers_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="regcustomers_tab_td"><span>Settings for Registered Customers</span></td>
						<td  align="left" onClick="handle_tabs('others_tab_td','others')" class="<?php if($curtab=='others_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="others_tab_td"><span>Category menu,shop menu, Review Listing &amp; Saved Search </span></td>
						<td width="90%" align="left">&nbsp;</td>
				</tr>
				</table>				
			</td>
		</tr>
		 <tr>
          <td colspan="4">
		  <div id='master_div'>
			<?php 
			if ($curtab=='main_tab_td')
			{
				show_prod_listing($alert);
			}
			elseif ($curtab=='regcustomers_tab_td')
			{
				show_regcustomers_listing($alert);
			}
			elseif ($curtab=='others_tab_td')
			{
				show_others_listing($alert);
			}
				
			?>		
		  </div>
		  </td>
		  </tr>
      </table>
</td>
</tr>
</table>
<input type="hidden" name="fpurpose" value="settings_prod_list_order_update" />
<input type="hidden" name="request" value="general_settings" />
<input type="hidden" name="retdiv_id" id="retdiv_id" value="master_div" />
</form>
