<?php
	/*#################################################################
	# Script Name 	: edit_presetvariable.php
	# Description 		: Page for editing Preset Product variables
	# Coded by 		: Sny
	# Created on		: 20-Oct-2009
	# Modified by		: 
	# Modified On		: 
	#################################################################*/
//Define constants for this page
$page_type 	= 'Preset Product Variables';
$help_msg	 	= 'This section helps in editing Preset Product Variables';
$help_msg = get_help_messages('EDIT_PRESET_MESS1');

// Get the details of variable being editing
$sql_var = "SELECT * FROM product_preset_variables WHERE var_id=$edit_id";
$ret_var = $db->query($sql_var);
if ($db->num_rows($ret_var))
{
	$row_var = $db->fetch_array($ret_var);
}
?>	
<script language="javascript" type="text/javascript">
	/* preloading the image to be shown on loading*/
	pic1= new Image(); 
	pic1.src="images/loading.gif";
function valforms(frm)
{
	var atleastone = false;
	var cur_neg =0;
	var cur_negord =0;
	var cur_num =0;
	fieldRequired = Array('var_name');
	fieldDescription = Array('Variable Name');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	if(document.frmEditProductPresetVariable.var_value_exists[0].checked==false)
			fieldNumeric = Array('var_order','var_price');
	else
			fieldNumeric = Array('var_order');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric))
	{
		if(document.frmEditProductPresetVariable.var_value_exists[0].checked)
		{   
			for(i=0;i<document.frmEditProductPresetVariable.elements.length;i++)
			{  
				if (document.frmEditProductPresetVariable.elements[i].name.substr(0,11)=='extvar_val_')
				{ 
					if(document.frmEditProductPresetVariable.elements[i].value!='')
					{
						atleastone = true;
					}	
				}
				else if (document.frmEditProductPresetVariable.elements[i].name=='var_val[]')
				{
					if(document.frmEditProductPresetVariable.elements[i].value!='')
					{
					   
						atleastone = true;
					}	
				}
				 
				if (document.frmEditProductPresetVariable.elements[i].name.substr(0,16)=='extvar_valprice_')
				{ 
				curval = document.frmEditProductPresetVariable.elements[i].value;
					if(curval<0)
					{
					 cur_neg =1;
					 document.frmEditProductPresetVariable.elements[i].focus();
					}
					if(isNaN(curval))
					{
					  cur_num =1;
					 document.frmEditProductPresetVariable.elements[i].focus();
					}
					var re = /[!,@,#,$,<,>,",',%,&,*,;,(,)]/i;	    
				var result = curval.search(re); // checks invalid characters 
				if( result != -1 )  	{
					result++;
					alert('Invalid Charater at Position '+result+' of price'); 
					document.frmEditProductPresetVariable.elements[i].focus();
					document.frmEditProductPresetVariable.elements[i].select();
				   	return false;
					}
				}
				if (document.frmEditProductPresetVariable.elements[i].name.substr(0,16)=='extvar_valorder_')
				{ 
				curval = document.frmEditProductPresetVariable.elements[i].value;
				if(curval<0)
					{
					 cur_negord =1;
					 document.frmEditProductPresetVariable.elements[i].focus();
					}
					var re = /[!,@,#,$,<,>,",',%,&,*,;,(,)]/i;	    
				var result = curval.search(re); // checks invalid characters 
				if( result != -1 )  	{
					result++;
					alert('Invalid Charater at Position '+result+' of order'); 
					document.frmEditProductPresetVariable.elements[i].focus();
					document.frmEditProductPresetVariable.elements[i].select();
				   	return false;
					}
				}
				
			}
			if(cur_negord ==1)
			{
			  alert('Sort order should be positive one.'); 
			  return false;
			}
			if(cur_num ==1)
			{
			  alert('Additional price should be numeric one.'); 
			  return false;
			}
		}
		else
			atleastone = true;	
		if (atleastone==true)
		{
			show_processing();
			return true;
		}
		else
		{
			alert('Please specify atleast one value for the variable');
			return false;
		}
		
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
			document.getElementById('prodvar_div').innerHTML = ret_val; /* Setting the output to required div */
			hide_processing();
		}
		else
		{
			 show_request_alert(req.status);
			//alert("Problem in requesting XML :"+req.statusText);
		}
	}
}
function call_ajax_changevariablelist()
{
	var atleastone 								= 0;
	var editid									= '<?php echo $_REQUEST['checkbox'][0]?>';
	var ch_ids 									= '';
	var ch_variable								= '';
	var qrystr									= '';
	var atleastmsg 								= '';
	var confirmmsg 								= '';
	var fpurpose								= 'prodvar_onchange';
	if(document.frmEditProductPresetVariable.var_value_exists[0].checked)
	{
		var var_value_exists = 1;
		document.getElementById('add_more_div').style.display='inline';
	}
	else
	{
		var var_value_exists = 0;
		document.getElementById('add_more_div').style.display='none';
	}	
	document.getElementById('prodvar_div').innerHTML = '<center><img src="images/loading.gif" alt="Loading"></center>';
	Handlewith_Ajax('services/preset_variable.php','fpurpose='+fpurpose+'&edit_id='+editid+'&'+qrystr+'&var_value_pass='+var_value_exists);
}	
function do_operation(mod)
{
	switch(mod)
	{
		case 'setprice':
			var setval 		= document.getElementById('setprice').value;
			if(setval=='')
			{
				alert('Please specify the price to be set to all price fields');
				return false;
			}
			if (confirm('Are you sure you want to set the price of all values for the specified price?'))
			{
				for (i=0;i<document.frmEditProductPresetVariable.elements.length;i++)
				{
					if (document.frmEditProductPresetVariable.elements[i].name.substr(0,16)=='extvar_valprice_' || document.frmEditProductPresetVariable.elements[i].name.substr(0,13)=='var_valprice_')
					{
						document.frmEditProductPresetVariable.elements[i].value = setval;
					}
				}
			}
		break;
	};
}
function assign_color_value_image(valueid)
{
		document.frmEditProductPresetVariable.src_id.value = valueid;
		document.frmEditProductPresetVariable.fpurpose.value = 'add_prodvarimg';
		document.frmEditProductPresetVariable.submit();
}
function delete_color_value_image(valueid)
{
		if(confirm('Are you sure you want to unassign the image?'))
		{
			document.frmEditProductPresetVariable.fpurpose.value = 'rem_presetvarimg';
			document.frmEditProductPresetVariable.src_id.value = valueid;
			document.frmEditProductPresetVariable.saveandaddmore.value=1;
			document.frmEditProductPresetVariable.remvarvalueimg.value=1;
			document.frmEditProductPresetVariable.submit()
		}
			
}
</script>
<form name='frmEditProductPresetVariable' action='home.php?request=preset_var' method="post" onsubmit="return valforms(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=preset_var&search_variable_name=<?php echo $_REQUEST['search_variable_name']?>&start=<?php echo $_REQUEST['start']?>&pg=<?php echo $_REQUEST['pg']?>&records_per_page=<?php echo $_REQUEST['records_per_page']?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>">List Preset Product Variables</a><span> Edit Preset Product Variable</span></div></td>
        </tr>
        <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main">
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
          <td align="center" valign="middle" class="errormsg" ><?=$alert?></td>
          </tr>
		 <?php
		 	}
		 ?>
         <tr>
           <td align="left" valign="top" class="tdcolorgraynormal" >
		   <div class="editarea_div">
		   <table width="100%" border="0" cellspacing="0" cellpadding="0">
             <tr>
               <td width="21%" align="left">Variable Name<span class="redtext"> *</span></td>
               <td width="32%" align="left"><input name="var_name" type="text" id="var_name" value="<?php echo stripslashes($row_var['var_name'])?>" size="30" maxlength="100" /></td>
               <td width="14%" align="left">Hide</td>
               <td width="33%" align="left"><input type="radio" name="var_hide" value="1" <?php echo ($row_var['var_hide']==1)?'checked="checked"':''?> />
Yes
  <input name="var_hide" type="radio" value="0" <?php echo ($row_var['var_hide']==0)?'checked="checked"':''?> />
No<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_VAR_EDIT_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
             </tr>
             <tr>
               <td align="left">Values requred for this variable? </td>
               <td align="left"><input name="var_value_exists" id="var_value_exists" type="radio" value="1" <?php echo ($row_var['var_value_exists']==1)?'checked="checked"':''?> onclick="call_ajax_changevariablelist()"/>
Yes
  <input name="var_value_exists" id="var_value_exists" type="radio" value="0" <?php echo ($row_var['var_value_exists']==0)?'checked="checked"':''?> onclick="call_ajax_changevariablelist()" />
No<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_VAR_EDITVAL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
               <td align="left">Order</td>
               <td align="left"><input name="var_order" type="text" size="5" value="<?php echo $row_var['var_order']?>"/></td>
             </tr>
             <tr>
               <td colspan="4" align="left">&nbsp;</td>
             </tr>
           <tr>
               <td colspan="4" align="left">
			   <div id="prodvar_div" style="text-align:center">
					 <?php
			   if($ecom_gridenable==1)
			   {
					showvariablevalue_gridlist($edit_id,$row_var['var_value_exists'],'');
			   }
			   else
			   {
			      	showvariablevalue_list($edit_id,$row_var['var_value_exists'],'');
			   }	
			  	?> 
			  	 
			   </div>
			   </td>
			</tr>     
         
         <tr>
           <td colspan="4" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
         </tr>
        <tr>
          <td width="41%" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td width="59%" colspan="3" align="left" valign="middle" class="tdcolorgray">

			
			</td>
        </tr>
      </table>
	  	</td>
		</tr>
		
		<tr>
			<td align="left" valign="top" class="tdcolorgraynormal" >
				<div class="editarea_div">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td width="21%" align="right">
							<input type="hidden" name="search_variable_name" id="search_variable_name" value="<?=$_REQUEST['search_variable_name']?>" />
							<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
							<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
							<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
							<input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
							<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
							<input type="hidden" name="parent_id" id="parent_id" value="<?=$_REQUEST['parent_id']?>" />
							<input type="hidden" name="edit_id" id="edit_id" value="<?=$_REQUEST['edit_id']?>" />
							<input type="hidden" name="checkbox[0]" id="checkbox[0]" value="<? echo $_REQUEST['checkbox'][0];?>" />
							<input type="hidden" name="edit_id" id="edit_id" value="<? echo $_REQUEST['checkbox'][0];?>" />
							<input type="hidden" name="curtab" id="curtab" value="<?=$_REQUEST['curtab']?>" />
							<input type="hidden" name="fpurpose" id="fpurpose" value="save_editprodvar" />
							<input type="hidden" name="saveandaddmore" id="saveandaddmore" value="0" />
							<input type="hidden" name="src_page" id="src_page" value="presetvarimg" />
			<input type="hidden" name="src_id" id="src_id" value="" />
			<input type="hidden" name="srcvar_id" id="srcvar_id" value="<?=$edit_id?>" />
			<input type="hidden" name="remvarvalueimg" id="remvarvalueimg" value="" />
							
							<input name="prodvar_Submit" type="submit" class="red" value="Save" />
							<div id="add_more_div" style="display:<?php echo ($row_var['var_value_exists']==1)?'inline':'none'?>; width:40px; padding-left:10px">
							<input name="prodvarmore_Submit" type="button" class="red" value="Save & Add more value" onclick="document.frmEditProductPresetVariable.saveandaddmore.value=1;document.frmEditProductPresetVariable.submit()" />
							</div>
						</td>
					</tr>
					</table>
				</div>
			</td>
		</tr>
		</table>
</form>	  

