<?php
	/*#################################################################
	# Script Name 	: edit_section.php
	# Description 	: Page for editing Dynamic Form Section
	# Coded by 		: SKR
	# Created on	: 18-Aug-2007
	# Modified by	: Sny
	# Modified On	: 07-Oct-2007
	#################################################################*/
#Define constants for this page
$page_type 	= 'Section';
$help_msg 		= get_help_messages('EDIT_CHECKOUT_FORM_MESS1');
$section_id		= ($_REQUEST['section_id']?$_REQUEST['section_id']:$_REQUEST['checkbox'][0]);
$sql_section	= "SELECT section_name,activate,sort_no,message,position,section_type,section_to_specific_products,
						  hide_heading 
						  		FROM element_sections  
									WHERE  sites_site_id=$ecom_siteid AND section_id=".$section_id;
$res_section	= $db->query($sql_section);
if($db->num_rows($res_section)==0) { echo " <font color='red'> You Are Not Authorised  </a>"; exit; }
$row_section 	= $db->fetch_array($res_section);
?>	
<script language="javascript" type="text/javascript">
function valform(frm)
{
	fieldRequired 		= Array('section_name');
	fieldDescription 	= Array('Section Name');
	fieldEmail 			= Array();
	fieldConfirm 		= Array();
	fieldConfirmDesc  	= Array();
	fieldNumeric 		= Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		show_processing();
		return true;
	} else {
		return false;
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
			norecdiv 	= document.getElementById('retdiv_more').value;
			targetobj 	= eval("document.getElementById('"+targetdiv+"')");
			targetobj.innerHTML = ret_val; /* Setting the output to required div */
			/* Decide the display of action buttons*/
			switch(targetdiv)
			{
				case 'sec_product_div':
					if(document.getElementById('secprod_norec'))
					{
						if(document.getElementById('secprod_norec').value==1)
						{
							disp = 'none';
						}	
						else
							disp = '';
					}
					else
						disp = '';
				break;
				
		  }
			if (disp!='no')
			{
				norecobj 	= eval("document.getElementById('"+norecdiv+"')");
				if (norecobj)
					norecobj.style.display = disp;
			}	
			if(document.getElementById('mainerr_tr'))
				document.getElementById('mainerr_tr').style.display = 'none';
			hide_processing();
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
		case 'sec_product':
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('sec_product_tr'))
					document.getElementById('sec_product_tr').style.display = '';
				
				if(document.getElementById('sec_product_div'))
					document.getElementById('sec_product_div').style.display = '';	
				call_ajax_showlistall('sec_product');		
				
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('sec_product_tr'))
					document.getElementById('sec_product_tr').style.display = 'none';
				if(document.getElementById('sec_product_div'))
					document.getElementById('sec_product_div').style.display = 'none';	
				
			}	
		break;
	};
}
function call_ajax_showlistall(mod)
{  
	var atleastone 										= 0;
	var cur_secmid										= '<?php echo $section_id?>';
	var form_type										= '<?=$row_section['section_type']?>';
	var cat_orders										= '';
	var fpurpose										= '';
	var retdivid										= '';
	var moredivid										= '';
	switch(mod)
	{
		case 'sec_product': // Case of Categories in the group
			retdivid   	= 'sec_product_div';
			fpurpose	= 'list_section_products';
			moredivid	= 'sec_product_unassign_div';
		break;
	}
	document.getElementById('retdiv_id').value 		= retdivid;/* Name of div to show the result */	
	document.getElementById('retdiv_more').value 	= moredivid;/* Name of div to show the result */	
	retobj 											= eval("document.getElementById('"+retdivid+"')");
	retobj.innerHTML 								= '<center><img src="images/loading.gif" alt="Loading"></center>';															
	var qrystr										= '';
	/* Calling the ajax function */
	Handlewith_Ajax('services/custom_form.php','fpurpose='+fpurpose+'&cur_secid='+cur_secmid+'&form_type='+form_type);	
}	
function normal_assignselsectionproduct(search_name,sortby,sortorder,recs,start,pg,editid)
{
		window.location 	= 'home.php?request=customform&form_type=<?=$row_section['section_type']?>&fpurpose=assign_secprod&pass_search_name='+search_name+'&pass_sort_by='+sortby+'&pass_sort_order='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_editid='+editid;
}	
function call_ajax_deleteall(mod,checkboxname)
{
	
	var atleastone 			= 0;
	var edit_id				= '<?php echo $section_id ?>';
	var form_type			= '<?=$row_section['section_type']?>';
	var del_ids 			= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';
	var sortby				='<? echo $_REQUEST['sort_by']?>';
	var sortorder			='<? echo $_REQUEST['sort_order']?>';
	var recs				='<? echo $_REQUEST['records_per_page']?>';
	var start  				='<? echo $_REQUEST['start']?>';
	var pg					='<? echo $_REQUEST['pg']?>';
	var search_name			='<? echo $_REQUEST['search_name']?>';
  var qrystr				= '&search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;

	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmEditSection.elements.length;i++)
	{
		if (document.frmEditSection.elements[i].type =='checkbox' && document.frmEditSection.elements[i].name==checkboxname)
		{

			if (document.frmEditSection.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmEditSection.elements[i].value;
			}	
		}
	}
	switch(mod)
	{
		case 'sec_product':
			atleastmsg 	= 'Please select the product(s) to be unassigned from this dynamic section';
			confirmmsg 	= 'Are you sure you want to unassign selected product(s) from this dynamic section?';
			retdivid   	= 'sec_product_div';
			moredivid	= 'sec_product_unassign_div';
			fpurpose	= 'unassignsecproduct';
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
			document.getElementById('retdiv_more').value 	= moredivid;/* Name of div to show the result */	
			retobj 										= eval("document.getElementById('"+retdivid+"')");
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/custom_form.php','fpurpose='+fpurpose+'&form_type='+form_type+'&edit_id='+edit_id+'&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
function call_ajax_changestatus(mod,checkboxname)
{
	
	var atleastone 			= 0;
	var edit_id				= '<?php echo $section_id ?>';
	var form_type			= '<?=$row_section['section_type']?>';
	var ch_ids 				= '';
	var prod_active			= document.getElementById('product_active').value;
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmEditSection.elements.length;i++)
	{
		if (document.frmEditSection.elements[i].type =='checkbox' && document.frmEditSection.elements[i].name==checkboxname)
		{

			if (document.frmEditSection.elements[i].checked==true)
			{
				atleastone = 1;
				if (ch_ids!='')
					ch_ids += '~';
				 ch_ids += document.frmEditSection.elements[i].value;
			}	
		}
	}
	switch(mod)
	{
		case 'sec_product': // Case of Categories Products in the group
			atleastmsg 	= 'Please select the product(s) to change the hidden status';
			confirmmsg 	= 'Are you sure you want to change hidden status of selected product(s)?';
			retdivid   	= 'sec_product_div';
			moredivid	= 'sec_product_unassign_div';
			fpurpose	= 'chstatussecproduct';
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
			document.getElementById('retdiv_more').value 	= moredivid;/* Name of div to show the result */	
			retobj 											= eval("document.getElementById('"+retdivid+"')");
			retobj.innerHTML 								= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/custom_form.php','fpurpose='+fpurpose+'&form_type='+form_type+'&prod_active='+prod_active+'&edit_id='+edit_id+'&ch_ids='+ch_ids+'&'+qrystr);
		}	
	}	

}
</script>
<form name='frmEditSection' action='home.php?request=customform&form_type=<?=$row_section['section_type']?>' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=customform&form_type=<?=$row_section['section_type']?>&search_name=<?php echo $_REQUEST['search_name']?>&start=<?php echo $_REQUEST['start']?>&pg=<?php echo $_REQUEST['pg']?>&records_per_page=<?php echo $_REQUEST['records_per_page']?>&sort_by=<?php echo $_REQUEST['sort_by']?>&sort_order=<?php echo $_REQUEST['sort_order']?>"><? echo ucwords($_REQUEST['form_type'])?> Form</a><span> Edit Section</span></div></td>
        </tr>
       <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="4">
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
          <td colspan="4" align="center" valign="middle" class="errormsg" id="mainerr_tr"><?=$alert?></td>
    </tr>
		<?
		}
		?> 
		<tr>
          <td colspan="4" align="center" valign="middle">
		  <div class="editarea_div" >
		  <table width="100%" cellpadding="0" cellspacing="0"> 
          <tr>
          <td width="14%" align="left" valign="middle" class="tdcolorgray" >Section Name <span class="redtext">*</span> </td>
          <td colspan="3" align="left" valign="middle" class="tdcolorgray">
		  <input name="section_name" type="text" class="input" value="<?=$row_section['section_name']?>" size="80"  />		  </td>
        </tr>
		 <tr>
          <td width="14%" align="left" valign="middle" class="tdcolorgray" >Position</td>
          <td align="left" valign="middle" class="tdcolorgray" nowrap="nowrap">
		  <select name="position">
		  <option value="Top" <? if($row_section['position']=='Top') echo "selected";?>>Top</option>
		  <option value="TopInStatic" <? if($row_section['position']=='TopInStatic') echo "selected";?>>Top With in the Static Section</option>
		  <option value="BottomInStatic" <? if($row_section['position']=='BottomInStatic') echo "selected";?>>Bottom With in the Static Section</option>
		  <option value="Bottom" <? if($row_section['position']=='Bottom') echo "selected";?>>Bottom</option>
		  </select>	<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CHECKOUT_FORM_POS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>	  </td>
          <td width="21%" align="right" valign="middle" class="tdcolorgray">Sort Order</td>
	      <td width="47%" align="left" valign="middle" class="tdcolorgray"><input class="input" type="text" name="sort_no" size="3" value="<?=$row_section['sort_no']?>"  /><a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CHECKOUT_FORM_ORDER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	    </tr>
        <tr>
          <td align="left" valign="middle" class="tdcolorgray" >Active</td>
          <td width="18%" align="left" valign="middle" class="tdcolorgray"><input type="radio" name="activate" value="1" <? if($row_section['activate']==1) echo "checked";?> />
            &nbsp;Yes&nbsp;
            <input type="radio" name="activate" value="0" <? if($row_section['activate']==0) echo "checked";?> />
          &nbsp;No<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CHECKOUT_FORM_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          <td align="right" valign="middle" class="tdcolorgray">
		  <?php	
		  	if($row_section['section_type']!='register')
			{
		  ?>	
		 	 Only for Specific Products
		  <?php
		  	}
		  ?>		  </td>
          <td align="left" valign="middle" class="tdcolorgray">
		  <?php	
		  	if($row_section['section_type']!='register')
			{
		  ?>
			  <input type="radio" name="section_to_specific_products" value="1" <? if($row_section['section_to_specific_products']==1) echo "checked";?> />
				Yes
				  &nbsp;
				  <input type="radio" name="section_to_specific_products" value="0" <? if($row_section['section_to_specific_products']==0) echo "checked";?> />
			  No 
			  		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CHECKOUT_FORM_SPECPROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
		  <?php
		  }
		  ?></td>
        </tr>
        <tr>
          <td align="left" valign="middle" class="tdcolorgray" >Hide Heading </td>
          <td align="left" valign="middle" class="tdcolorgray"><input type="radio" name="hide_heading" value="1" <? if($row_section['hide_heading']==1) echo "checked";?> />
&nbsp;Yes&nbsp;
<input type="radio" name="hide_heading" value="0" <? if($row_section['hide_heading']==0) echo "checked";?> />
&nbsp;No<a href="#" onmouseover ="ddrivetip('If the section heading is to be made hidden in site tick this checkbox.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          <td align="right" valign="middle" class="tdcolorgray">&nbsp;</td>
          <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
        </tr>
		 <tr>
          <td width="14%" align="left" valign="middle" class="tdcolorgray" >Help Instructions</td>
          <td colspan="3" align="left" valign="middle" class="tdcolorgray">
	     <textarea name="message" cols="60" rows="4"><?echo stripslashes($row_section['message'])?></textarea></td>
    	</tr>
		</table>
		</div>
		</td>
		</tr>
		
		<tr>
			<td colspan="4" align="right" valign="middle">
				<div class="editarea_div">
					<table width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td align="right" valign="middle" class="tdcolorgray">
							<input type="hidden" name="section_id" id="section_id" value="<?=$section_id?>" />
							<input type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>" />
							<input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
							<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
							<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
							<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
							<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
							<input type="hidden" name="form_type" id="form_type" value="<?=$_REQUEST['form_type']?>" />							
							<input type="hidden" name="retdiv_id" id="retdiv_id" value="maincontent" />
							<input type="hidden" name="retdiv_more" id="retdiv_more" value="" />
							<input type="hidden" name="fpurpose" id="fpurpose" value="update" />
							<input name="Submit" type="submit" class="red" value="Update" />
						</td>
					</tr>
					</table>
				</div>
			</td>
		</tr>
		</table>
		<?php
			if($row_section['section_to_specific_products']==1)
			{
		?>		
				<div class="editarea_div">
				<table width="100%" cellpadding="0" cellspacing="0">
				<tr >
				  <td colspan="4" align="left" valign="bottom">&nbsp;
					<table width="100%" border="0" cellspacing="1" cellpadding="1">
					<tr>
					  <td width="3%" class="seperationtd"><img id="cat_imgtag" src="images/plus.gif" border="0" onclick="handle_expansion(this,'sec_product')" title="Click"/></td>
					  <td width="97%" align="left" class="seperationtd">Products to which this dynamic section is linked to</td>
					</tr>
				  </table></td>
				</tr>
			<?php
			 // Chech whether any product assigned to current section
				$sql_secrod = "SELECT products_product_id FROM element_section_products  
							WHERE element_sections_section_id=".$section_id;
				$ret_secprod = $db->query($sql_secrod);
			 ?>
			 <tr>
			  <td align="right" colspan="4" class="tdcolorgray_buttons">
				<input name="Assignmore" type="button" class="red" id="Assignmore" value="Assign More" onclick="normal_assignselsectionproduct('<?php echo $_REQUEST['search_name']?>','<?php echo $_REQUEST['sort_by']?>','<?php echo $_REQUEST['sort_order']?>','<?php echo $_REQUEST['records_per_page']?>','<?php echo $_REQUEST['start']?>','<?php echo $_REQUEST['pg']?>','<?php echo $section_id?>');" />
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CHECKOUT_FORM_ASSPROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
				<?php
				if ($db->num_rows($ret_secprod))
				{
				?>
				<div id="sec_product_unassign_div" class="unassign_div" style="display:none">
				<!--Change Status -->
				<?php
					//$sec_prod_status = array(1=>'No',0=>'Yes');
					//echo generateselectbox('product_active',$sec_prod_status,1);
				?>
				<!--<input name="ch_status" type="button" class="red" id="ch_status" value="Change" onclick="call_ajax_changestatus('sec_product','checkboxprod[]')" />
				<a href="#" onmouseover ="ddrivetip('<?//=get_help_messages('EDIT_CHECKOUT_FORM_CHSTATUS')?>')"; onmouseout="hideddrivetip()">
				<img src="images/helpicon.png" width="17" height="13" border="0" /></a> -->
				<input name="Unassign" type="button" class="red" id="Unassign" value="Unassign" onclick="call_ajax_deleteall('sec_product','checkboxprod[]')" />
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CHECKOUT_FORM_UNASSPROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>				</div>	
				<?php
				}
				?>			  </td>
    </tr>
				 <tr  id="sec_product_tr" style="display:none" >
					<td colspan="4" align="left" valign="middle" class="tdcolorgray" >
					<div id="sec_product_div" style="text-align:center">					</div>				   </td>
				</tr>
				 </table>
				 </div>
			<?php
			}
			?>	
 
	  
</form>	  

