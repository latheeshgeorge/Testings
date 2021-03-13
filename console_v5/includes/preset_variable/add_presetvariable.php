<?php
	/*#################################################################
	# Script Name 	: add_presetvariable.php
	# Description 		: Page for adding Preset Product variables
	# Coded by 		: Sny
	# Created on		: 20-Oct-2009
	# Modified by		: 
	# Modified On		: 
	#################################################################*/
	//Define constants for this page
	$page_type = 'Products';
	$help_msg = get_help_messages('ADD_PRESET_MESS1');
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
	if(document.frmAddPresetProductVariable.var_value_exists[0].checked==false)
			fieldNumeric = Array('var_order','var_price');
	else
			fieldNumeric = Array('var_order');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric))
	{
				if(document.frmAddPresetProductVariable.var_value_exists[0].checked)
		{   
			for(i=0;i<document.frmAddPresetProductVariable.elements.length;i++)
			{  
				if (document.frmAddPresetProductVariable.elements[i].name.substr(0,11)=='extvar_val_')
				{ 
					if(document.frmAddPresetProductVariable.elements[i].value!='')
					{
						atleastone = true;
					}	
				}
				else if (document.frmAddPresetProductVariable.elements[i].name=='var_val[]')
				{
				  
					if(document.frmAddPresetProductVariable.elements[i].value!='')
					{
					   
						atleastone = true;
					}	
				}
				if (document.frmAddPresetProductVariable.elements[i].name.substr(0,13)=='var_valprice_' || document.frmAddPresetProductVariable.elements[i].name.substr(0,12)=='var_valprice')
				{ 
				curval = document.frmAddPresetProductVariable.elements[i].value;
				if(curval<0)
					{
					 cur_neg =1;
					 document.frmAddPresetProductVariable.elements[i].focus();
					}
					if(isNaN(curval))
					{
					  cur_num =1;
					 document.frmAddPresetProductVariable.elements[i].focus();
					}
					var re = /[!,@,#,$,<,>,",',%,&,*,;,(,)]/i;	    
				var result = curval.search(re); // checks invalid characters 
				if( result != -1 )  	{
					result++;
					alert('Invalid Charater at Position '+result+' of price'); 
					document.frmAddPresetProductVariable.elements[i].focus();
					document.frmAddPresetProductVariable.elements[i].select();
				   	return false;
					}
				}
				if (document.frmAddPresetProductVariable.elements[i].name.substr(0,13)=='var_val_order' || document.frmAddPresetProductVariable.elements[i].name.substr(0,12)=='var_valorder')
				{ 
				curval = document.frmAddPresetProductVariable.elements[i].value;
				if(curval<0)
					{
					 cur_negord =1;
					 document.frmAddPresetProductVariable.elements[i].focus();
					}
					var re = /[!,@,#,$,<,>,",',%,&,*,;,(,)]/i;	    
				var result = curval.search(re); // checks invalid characters 
				if( result != -1 )  	{
					result++;
					alert('Invalid Charater at Position '+result+' of order'); 
					document.frmAddPresetProductVariable.elements[i].focus();
					document.frmAddPresetProductVariable.elements[i].select();
				   	return false;
					}
				}
				
			}
			if(cur_neg ==1)
			{
			  alert('Additional price should be positive one.'); 
			  return false;
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
	var editid									= 0;
	var ch_ids 									= '';
	var ch_variable								= '';
	var qrystr									= '';
	var atleastmsg 								= '';
	var confirmmsg 								= '';
	var fpurpose								= 'prodvar_onchange';
	if(document.frmAddPresetProductVariable.var_value_exists[0].checked)
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
				for (i=0;i<document.frmAddPresetProductVariable.elements.length;i++)
				{
					if (document.frmAddPresetProductVariable.elements[i].name.substr(0,16)=='extvar_valprice_' || document.frmAddPresetProductVariable.elements[i].name.substr(0,13)=='var_valprice_')
					{
						document.frmAddPresetProductVariable.elements[i].value = setval;
					}
				}
			}
		break;
	};
}
function copy_from_prev(token)
{
	shop_arr = new Array();
	<?php
		if (count(shopid_arr))
		{
			for($i=0;$i<count($shopid_arr);$i++)
			{
				echo "shop_arr[$i]=".$shopid_arr[$i].";";
			}
		}
	?>
	/* splitting the token */
	tok_arr = token.split('~');
	var src		= tok_arr[0];
	var dest 	= tok_arr[1];
	var cursrc	= 'var_valprice_'+src;
	srclen  = cursrc.length;
	var curdest= 'var_valprice_'+dest;
	for(i=0;i<document.frmAddPresetProductVariable.elements.length;i++)
	{
		if (document.frmAddPresetProductVariable.elements[i].name.substr(0,srclen)==cursrc)
		{
			split_arr =  document.frmAddPresetProductVariable.elements[i].name.split('_');
			varid = split_arr[3];
			obj	=  eval("document.frmAddPresetProductVariable."+curdest+"_"+varid);
			obj.value = document.frmAddPresetProductVariable.elements[i].value;
		}
	}
}
</script>
<form name='frmAddPresetProductVariable' action='home.php?request=preset_var' method="post" onsubmit="return valforms(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=preset_var&search_variable_name=<?php echo $_REQUEST['search_variable_name']?>&start=<?php echo $_REQUEST['start']?>&pg=<?php echo $_REQUEST['pg']?>&records_per_page=<?php echo $_REQUEST['records_per_page']?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>">List Preset Product Variables</a><span> Add Preset Product Variable</span></div></td>
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
               <td width="32%" align="left"><input name="var_name" type="text" id="var_name" value="<?php echo stripslashes($_REQUEST['var_name'])?>" size="30"  maxlength="100"/></td>
               <td width="14%" align="left">Hide</td>
               <td width="33%" align="left"><input type="radio" name="var_hide" value="1" <?php echo ($row_var['var_hide']==1)?'checked="checked"':''?> />
Yes
  <input name="var_hide" type="radio" value="0" <?php echo ($_REQUEST['var_hide']==0)?'checked="checked"':''?> />
No<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_VAR_ADDVAR_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
             </tr>
             <tr>
               <td align="left">Values required for this variable? </td>
               <td align="left"><input name="var_value_exists" id="var_value_exists" type="radio" value="1" onclick="call_ajax_changevariablelist()" <?php echo ($_REQUEST['var_value_exists']==1)?' checked="checked"':''?>/>
Yes
  <input name="var_value_exists" type="radio" id="var_value_exists" onclick="call_ajax_changevariablelist()" value="0" <?php echo ($_REQUEST['var_value_exists']==0)?' checked="checked"':''?> />
No<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_VAR_ADDVAL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
               <td align="left">Order</td>
               <td align="left"><input name="var_order" type="text" size="5" value="<?php echo $_REQUEST['var_order']?>"/></td>
             </tr>
             <tr>
               <td colspan="4" align="left">&nbsp;</td>
             </tr>
           <tr>
               <td colspan="4" align="left">
			   <div id="prodvar_div" style="text-align:center">
			   <?php
			   		$val_exists = ($_REQUEST['var_value_exists'])?1:0;
				   	showvariablevalue_list(0,$val_exists,'');
			  	?> 
			   </div>
			   </td>
			</tr>     
      </table></div>
	  	</td>
		</tr>
		
		<tr>
			<td align="right" valign="middle">
			<div class="editarea_div">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="59%" colspan="3" align="right" valign="middle" class="tdcolorgray">
						<input type="hidden" name="search_variable_name" id="search_variable_name" value="<?=$_REQUEST['search_variable_name']?>" />
						<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
						<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
						<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
						<input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
						<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
						<input type="hidden" name="saveandaddmore" id="saveandaddmore" value="0" />
						<input type="hidden" name="fpurpose" id="fpurpose" value="save_addprodvar" />
						
						<input name="prodvar_Submit" type="submit" class="red" value="Save" /> 
						<div id="add_more_div" style="display:<?php echo ($row_var['var_value_exists']==1)?'inline':'none'?>; width:40px; padding-left:10px">
						<input name="prodvarmore_Submit" type="button" class="red" value="Save & Add more values" onclick="document.frmAddPresetProductVariable.saveandaddmore.value=1;document.frmAddPresetProductVariable.submit()" /></div>
					</td>
				</tr>
				</table>
			</div>
			</td>
		</tr>
		   
		</table>
</form>	  

