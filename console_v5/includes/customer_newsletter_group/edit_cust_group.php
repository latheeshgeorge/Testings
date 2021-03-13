<?php
	/*#################################################################
	# Script Name 	: edit_cust_group.php
	# Description 	: Page for editing Customer Group
	# Coded by 		: SKR
	# Created on	: 21-Aug-2007
	# Modified by	: ANU
	# Modified On	: 
	#################################################################*/
#Define constants for this page
$page_type = 'Customer Newsletter Group';
$help_msg = get_help_messages('EDIT_CUST_GROUP_MESS1');
$custgroup_id=($_REQUEST['custgroup_id']?$_REQUEST['custgroup_id']:$_REQUEST['checkbox'][0]);
$sql_group="SELECT custgroup_name,custgroup_active FROM customer_newsletter_group  
				WHERE custgroup_id=".$custgroup_id." AND sites_site_id='".$ecom_siteid."'";
$res_group= $db->query($sql_group);
if($db->num_rows($res_group)==0)  { echo " <font color='red'> You Are Not Authorised  </a>"; exit; } 
$row_group = $db->fetch_array($res_group);
?>	
<script language="javascript" type="text/javascript">
function valform(frm)
{
	fieldRequired = Array('custgroup_name');
	fieldDescription = Array('Group Name');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array('custgroup_discount');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		if(frm.custgroup_discount.value>99) {
			alert(" Discount Should be less than 100% ");
			return false;
		} else {
			show_processing();
			return true;
		}
	} else {
		return false;
	}
}
function handle_expansion(imgobj,mod)
{
	var src 			= imgobj.src;
	var retindx 		= src.search('plus.gif');
	switch(mod)
	{
		case 'customer': /* Case of bow images*/
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('customer_tr'))
					document.getElementById('customer_tr').style.display = '';
				if(document.getElementById('customerunassign_div'))
					document.getElementById('customerunassign_div').style.display = '';	
				call_ajax_showlistall('customer');
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('customer_tr'))
					document.getElementById('customer_tr').style.display = 'none';
				if(document.getElementById('customerunassign_div'))
					document.getElementById('customerunassign_div').style.display = 'none';
			}	
		break;
	};
}
function call_ajax_showlistall(mod)
{
	var atleastone 										= 0;
	var custgroup_id									= '<?php echo $custgroup_id;?>';
	var cat_orders										= '';
	var fpurpose										= '';
	var retdivid										= '';
	var moredivid										= '';
	switch(mod)
	{
		case 'customer': // Case of category images
			retdivid   	= 'customer_div';
			moredivid	= 'customerunassign_div';
			fpurpose	= 'list_customer';
		break;
	};
	document.getElementById('retdiv_id').value 		= retdivid;/* Name of div to show the result */	
	document.getElementById('retdiv_more').value 	= moredivid;/* Name of div to show the result */	
	retobj 											= eval("document.getElementById('"+retdivid+"')");
	retobj.innerHTML 								= '<center><img src="images/loading.gif" alt="Loading"></center>';															
	var qrystr										= '';
	/* Calling the ajax function */
	Handlewith_Ajax('services/customer_newsletter_group.php','fpurpose='+fpurpose+'&'+qrystr+'&custgroup_id='+custgroup_id);
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
			norecdiv 	= document.getElementById('retdiv_more').value;
			targetobj 	= eval("document.getElementById('"+targetdiv+"')");
			targetobj.innerHTML = ret_val; /* Setting the output to required div */
			/* Decide the display of action buttons*/
			switch(targetdiv)
			{
				case 'customer_div':
					if(document.getElementById('customer_norec'))
					{
						if(document.getElementById('customer_norec').value==1)
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
function call_ajax_deleteall(checkboxname)
{
	var atleastone 			= 0;
	var custgroup_id		= '<?php echo $custgroup_id?>';
	var del_ids 			= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmEditCustomerGroup.elements.length;i++)
	{
		if (document.frmEditCustomerGroup.elements[i].type =='checkbox' && document.frmEditCustomerGroup.elements[i].name==checkboxname)
		{

			if (document.frmEditCustomerGroup.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmEditCustomerGroup.elements[i].value;
			}	
		}
	}
	
	atleastmsg 	= 'Please select the customer(s) to be unassigned.';
	confirmmsg 	= 'Are you sure you want to unassign the selected Customer(s)?';
	retdivid   	= 'customer_div';
	moredivid	= 'customerunassign_div';
	fpurpose	= 'unassign_customerdetails';
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
			Handlewith_Ajax('services/customer_newsletter_group.php','fpurpose='+fpurpose+'&custgroup_id='+custgroup_id+'&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
function normal_assign_customerGroupAssign(searchname,sortby,sortorder,recs,start,pg,custgroupid)
{
		window.location 			= 'home.php?request=cust_group&fpurpose=add_customer&pass_searchname='+searchname+'&pass_sortby='+sortby+'&pass_sortorder='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_custgroup_id='+custgroupid;
}
</script>
<form name='frmEditCustomerGroup' action='home.php?request=cust_group' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=cust_group&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Customer Newsletter Groups</a><span> Edit <?=$page_type?></span></div></td>
        </tr>
        <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="2">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
		<?php 
		if($alert)
		{			
		?>
        <tr>
          <td colspan="2" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
          </tr>
		<?
		}
		?>  
		<tr>
          <td colspan="2" align="center" valign="middle" class="tdcolorgray" >
		  <div class="editarea_div">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
         <tr>
          <td width="15%" align="left" valign="middle" class="tdcolorgray" >Group Name <span class="redtext">*</span> </td>
          <td width="85%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="custgroup_name" value="<?=$row_group['custgroup_name']?>"  />
		  </td>
        </tr>
		<!-- <tr>
          <td width="15%" align="left" valign="middle" class="tdcolorgray" >Discount</td>
          <td width="85%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="custgroup_discount" size="3" value="<?=$row_group['custgroup_discount']?>"  />
		  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CUST_GROUP_DISC')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr> -->
		<tr>
          <td align="left" valign="middle" class="tdcolorgray" >Active</td>
          <td align="left" valign="middle" class="tdcolorgray"><input type="radio" name="custgroup_active" value="1" <? if($row_group['custgroup_active']==1) echo "checked";?> />Yes<input type="radio" name="custgroup_active" value="0" <? if($row_group['custgroup_active']==0) echo "checked";?> />No
		  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CUST_GROUP_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
        <tr>
         
          <td align="center" valign="middle" class="tdcolorgray" colspan="2">
		  
		  <input type="hidden" name="custgroup_id" id="custgroup_id" value="<?=$custgroup_id?>" />
		  <input type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>" />
		  <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
		  <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
		  <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
		  <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
		  <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
		  <input type="hidden" name="fpurpose" id="fpurpose" value="update" />
		  <input type="hidden" name="checkbox[0]" id="checkbox[0]" value="<?php echo $custgroup_id?>" />
		  <input type="hidden" name="src_page" id="src_page" value="gift_bow" />
		  <input type="hidden" name="src_id" id="src_id" value="<?php echo $custgroup_id?>" />
		  <input type="hidden" name="retdiv_id" id="retdiv_id" value="" />
		 <input type="hidden" name="retdiv_more" id="retdiv_more" value="" />
		  <input name="Submit" type="submit" class="red" value="Update" /></td>
        </tr>
		
		<tr>
          <td colspan="4" align="left" valign="bottom">
            <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td width="3%" class="seperationtd"><img id="cat_imgtag" src="images/plus.gif" border="0" onclick="handle_expansion(this,'customer')" title="Click"/></td>
              <td width="97%" align="left" class="seperationtd">Customers Assigned to this group </td>
            </tr>
          </table></td>
        </tr>
        <tr >
		   <?php
			// Get the list of images for this bow
			$sql_customer = "SELECT map_id FROM customer_newsletter_group_customers_map 
						 WHERE custgroup_id=$custgroup_id LIMIT 1";
			$ret_customer= $db->query($sql_customer);
		 ?>
		 <tr>
		  <td align="right" colspan="4" class="tdcolorgray_buttons">
				<input name="Assign_Image" type="button" class="red" id="Assign_Customer" value="Assign More" onclick="normal_assign_customerGroupAssign('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>','<?php echo $custgroup_id?>');" />
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CUST_GROUP_ASS_CUST')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
				<?php
				if ($db->num_rows($ret_customer))
				{
				?>
					<div id="customerunassign_div" class="unassign_div" style="display:none">
					<input name="customer_unassign" type="button" class="red" id="customer_unassign" value="Un assign" onclick="call_ajax_deleteall('checkboxdisplaycustomer[]')" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CUST_GROUP_UNASS_CUST')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>				</div>	
				<?php
				}				
				?>		  </td>
        </tr>
		<tr id="customer_tr">
			<td align="right" colspan="4" class="tdcolorgray_buttons">
				<div id="customer_div" style="text-align:center"></div>
			</td>
		</tr>
		</table>
		</div>
		</td>
		</tr>
	
      </table>
</form>	  

