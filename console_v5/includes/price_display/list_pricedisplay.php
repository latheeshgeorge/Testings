<?php
/*#################################################################
	# Script Name 	: list_pricedisplay.php
	# Description 	: Page for listing Product Vendors
	# Coded by 		: SKR
	# Created on	: 22-June-2007
	# Modified by	: Sny
	# Modified On	: 29-Jan-2008
#################################################################*/
//Define constants for this page
$table_name		= 'general_settings_site_pricedisplay';
$page_type		= 'Price Display';
$help_msg 		= get_help_messages('LIST_PRICE_MESS1');
$sql			= "SELECT * FROM general_settings_site_pricedisplay WHERE sites_site_id=".$ecom_siteid;
$res			= $db->query($sql);
$row 			= $db->fetch_array($res);
if(!$curtab)
	$curtab		= ($_REQUEST['curtab'])?$_REQUEST['curtab']:'main_tab_td';

?>
<script type="text/javascript">
function handle_tabs(id,mod)
{ 
	tab_arr 								= new Array('main_tab_td','proddetails_tab_td','others_tab_td');
	var fpurpose							= '';
	var retdivid							= '';
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
		case 'pricedisplaymain_info':
			fpurpose ='list_pricedisplay_maininfo';
		break;
		case 'pricedisplay_prod_details': // Case of Categories in the group
			fpurpose	= 'list_pricedisplay_prod_details';
		break;
		case 'others': // Case of Display Products in the group
			fpurpose	= 'list_others';
		break;
	}	
	retobj 									= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
	//retobj										= document.getElementById('retdiv_id');
	retobj.innerHTML 						= '<center><img src="images/loading.gif" alt="Loading"></center>';															
	
	/* Calling the ajax function */
	//alert('fpurpose='+fpurpose+'&cur_groupid='+group_id);
	Handlewith_Ajax('services/settings_pricedisplay.php','fpurpose='+fpurpose);	
}
function save_settings(mod)
{
	if(mod=='prod_details')
	{
		document.frmlistPriceDisplay.fpurpose.value = 'settings_captions_update';
		document.frmlistPriceDisplay.submit();
	}
	else if(mod=='others')
	{
		document.frmlistPriceDisplay.fpurpose.value = 'settings_others_update';
		document.frmlistPriceDisplay.submit();
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
<form name="frmlistPriceDisplay" action="home.php" method="post" >	
<input type="hidden" name="request" value="general_settings_price" />

  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=general_settings">General Settings</a> <span> Price Display</span></div></td>
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
						<td  align="left" onClick="handle_tabs('main_tab_td','pricedisplaymain_info')" class="<?php if($curtab=='main_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="main_tab_td"> <span>Main Settings</span></td>
						<td  align="left" onClick="handle_tabs('proddetails_tab_td','pricedisplay_prod_details')" class="<?php if($curtab=='proddetails_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="proddetails_tab_td"><span>Price Display Captions</span></td>
						<td  align="left" onClick="handle_tabs('others_tab_td','others')" class="<?php if($curtab=='others_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="others_tab_td"><span>Other Settings</span></td>
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
				show_price_maininfo($alert);
			}
			elseif ($curtab=='proddetails_tab_td')
			{
				show_captions_list($alert);
			}
			elseif ($curtab=='others_tab_td')
			{
				show_others_list($alert);
			}
				
			?>		
		  </div>
		  </td>
		  </tr>
		
		   
		   
	    <?php /*?>  <tr>
          <td width="20%" align="left" valign="middle" class="tdcolorgray" >Apply Tax to Delivery</td>
          <td width="28%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="checkbox" name="price_applytax_todelivery" value="1" <? if($row['price_applytax_todelivery']=='1') echo "checked";?>  />		<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PRICEDISP_APPLY_TAX_TODELIV')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>  </td>
		    <td width="27%" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td width="25%" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
        </tr><?php */?>

		 
		  
		   
		<tr>
          <td align="left" valign="middle" class="tdcolorgray" colspan="2" >&nbsp;</td>
          <td align="right" valign="middle" class="tdcolorgray" colspan="2">
		  
		<input type="hidden" name="retdiv_id" id="retdiv_id" value="master_div" />
		  <input type="hidden" name="fpurpose" id="fpurpose" value="update_settings" />
        </tr>
      </table>
</form>
